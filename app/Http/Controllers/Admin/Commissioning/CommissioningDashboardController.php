<?php

namespace App\Http\Controllers\Admin\Commissioning;

use App\Http\Controllers\Controller;
use App\Models\CommissioningCertificate;
use App\Models\CommissioningScope;
use App\Models\CommissioningTest;
use App\Models\CommissioningTestPlan;
use App\Models\ConstructionWorkOrder;
use App\Models\Project;
use Illuminate\View\View;

class CommissioningDashboardController extends Controller
{
    /**
     * Display the commissioning dashboard for a project.
     */
    public function index(Project $project): View
    {
        /*
        |--------------------------------------------------------------------------
        | Project Scopes
        |--------------------------------------------------------------------------
        */

        $scopes = CommissioningScope::query()
            ->with('workOrder')
            ->withCount([
                'testPlans',
                'tests',
                'certificates',
            ])
            ->where('project_id', $project->id)
            ->orderBy('scope_code')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Construction Work Orders
        |--------------------------------------------------------------------------
        |
        | Work Orders are the source for Commissioning Scope.
        | Cancelled Work Orders are excluded.
        |
        */

        $workOrders = ConstructionWorkOrder::query()
            ->where('project_id', $project->id)
            ->where('status', '!=', 'Cancelled')
            ->orderBy('work_order_number')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Commissioning Scope Statistics
        |--------------------------------------------------------------------------
        */

        $totalScopes = $scopes->count();

        $completedScopes = $scopes
            ->whereIn('status', ['Completed', 'Accepted'])
            ->count();

        $inProgressScopes = $scopes
            ->whereIn('status', ['In Progress', 'Testing'])
            ->count();

        $pendingScopes = $scopes
            ->whereIn('status', ['Draft', 'Planned', 'Pending'])
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Overall Scope Progress
        |--------------------------------------------------------------------------
        */

        $scopeProgress = $totalScopes > 0
            ? round(
                $scopes->avg(
                    fn ($scope) => (float) ($scope->progress_percentage ?? 0)
                ),
                1
            )
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Test Plan Statistics
        |--------------------------------------------------------------------------
        */

        $totalPlans = CommissioningTestPlan::query()
            ->where('project_id', $project->id)
            ->count();

        $approvedPlans = CommissioningTestPlan::query()
            ->where('project_id', $project->id)
            ->where('status', 'Approved')
            ->count();

        $pendingPlans = CommissioningTestPlan::query()
            ->where('project_id', $project->id)
            ->whereIn('status', [
                'Draft',
                'Submitted',
                'Under Review',
            ])
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Test Statistics
        |--------------------------------------------------------------------------
        */

        $totalTests = CommissioningTest::query()
            ->where('project_id', $project->id)
            ->count();

        $passedTests = CommissioningTest::query()
            ->where('project_id', $project->id)
            ->where('result', 'Pass')
            ->count();

        $failedTests = CommissioningTest::query()
            ->where('project_id', $project->id)
            ->where('result', 'Fail')
            ->count();

        $pendingTests = CommissioningTest::query()
            ->where('project_id', $project->id)
            ->whereIn('status', [
                'Planned',
                'Scheduled',
                'In Progress',
                'Retest Required',
            ])
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Test Pass Rate
        |--------------------------------------------------------------------------
        |
        | Only executed tests are considered.
        |
        */

        $executedTests = $passedTests + $failedTests;

        $testPassRate = $executedTests > 0
            ? round(($passedTests / $executedTests) * 100, 1)
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Certificate Statistics
        |--------------------------------------------------------------------------
        */

        $totalCertificates = CommissioningCertificate::query()
            ->where('project_id', $project->id)
            ->count();

        $approvedCertificates = CommissioningCertificate::query()
            ->where('project_id', $project->id)
            ->where('status', 'Approved')
            ->count();

        $pendingCertificates = CommissioningCertificate::query()
            ->where('project_id', $project->id)
            ->whereIn('status', [
                'Draft',
                'Submitted',
                'Under Review',
            ])
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Certificate Approval Rate
        |--------------------------------------------------------------------------
        */

        $certificateApprovalRate = $totalCertificates > 0
            ? round(
                ($approvedCertificates / $totalCertificates) * 100,
                1
            )
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Handover Readiness
        |--------------------------------------------------------------------------
        |
        | Proposed commissioning gate:
        |
        | 1. At least one commissioning scope exists.
        | 2. All commissioning scopes are completed/accepted.
        | 3. No failed tests remain.
        | 4. No pending/retest tests remain.
        | 5. If certificates exist, all must be approved.
        |
        */

        $allScopesCompleted =
            $totalScopes > 0 &&
            $completedScopes === $totalScopes;

        $allTestsCompleted =
            $pendingTests === 0 &&
            $failedTests === 0;

        $certificatesReady =
            $totalCertificates === 0 ||
            $approvedCertificates === $totalCertificates;

        $readyForHandover =
            $allScopesCompleted &&
            $allTestsCompleted &&
            $certificatesReady;


        /*
        |--------------------------------------------------------------------------
        | Handover Readiness Message
        |--------------------------------------------------------------------------
        */

        if ($readyForHandover) {

            $handoverReadinessMessage =
                'Commissioning requirements are complete and the project is ready for handover review.';

        } elseif ($totalScopes === 0) {

            $handoverReadinessMessage =
                'No commissioning scopes have been created yet.';

        } elseif (!$allScopesCompleted) {

            $handoverReadinessMessage =
                'One or more commissioning scopes are still incomplete.';

        } elseif ($failedTests > 0) {

            $handoverReadinessMessage =
                'Failed commissioning tests must be resolved before handover.';

        } elseif ($pendingTests > 0) {

            $handoverReadinessMessage =
                'Pending commissioning tests must be completed before handover.';

        } elseif (!$certificatesReady) {

            $handoverReadinessMessage =
                'All required commissioning certificates must be approved.';

        } else {

            $handoverReadinessMessage =
                'Commissioning is not yet ready for handover.';

        }


        /*
        |--------------------------------------------------------------------------
        | Recent Tests
        |--------------------------------------------------------------------------
        */

        $recentTests = CommissioningTest::query()
            ->with([
                'scope',
                'plan',
            ])
            ->where('project_id', $project->id)
            ->latest('test_date')
            ->latest('id')
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Available Work Orders
        |--------------------------------------------------------------------------
        |
        | Used when creating a new Commissioning Scope.
        |
        */

        $scopedWorkOrderIds = $scopes
            ->pluck('construction_work_order_id')
            ->filter()
            ->unique();

        $availableWorkOrders = $workOrders
            ->whereNotIn('id', $scopedWorkOrderIds)
            ->values();


        /*
        |--------------------------------------------------------------------------
        | Dashboard Data
        |--------------------------------------------------------------------------
        */

        $dashboard = [

            'scopes' => [
                'total' => $totalScopes,
                'completed' => $completedScopes,
                'in_progress' => $inProgressScopes,
                'pending' => $pendingScopes,
                'progress' => $scopeProgress,
            ],

            'test_plans' => [
                'total' => $totalPlans,
                'approved' => $approvedPlans,
                'pending' => $pendingPlans,
            ],

            'tests' => [
                'total' => $totalTests,
                'passed' => $passedTests,
                'failed' => $failedTests,
                'pending' => $pendingTests,
                'pass_rate' => $testPassRate,
            ],

            'certificates' => [
                'total' => $totalCertificates,
                'approved' => $approvedCertificates,
                'pending' => $pendingCertificates,
                'approval_rate' => $certificateApprovalRate,
            ],

            'handover' => [
                'ready' => $readyForHandover,
                'message' => $handoverReadinessMessage,
            ],

        ];


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.commissioning.index',
            compact(
                'project',
                'scopes',
                'workOrders',
                'availableWorkOrders',
                'dashboard',
                'totalScopes',
                'completedScopes',
                'inProgressScopes',
                'pendingScopes',
                'scopeProgress',
                'totalPlans',
                'approvedPlans',
                'pendingPlans',
                'totalTests',
                'passedTests',
                'failedTests',
                'pendingTests',
                'testPassRate',
                'totalCertificates',
                'approvedCertificates',
                'pendingCertificates',
                'certificateApprovalRate',
                'readyForHandover',
                'handoverReadinessMessage',
                'recentTests'
            )
        );
    }
}