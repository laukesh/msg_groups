<?php

namespace App\Http\Controllers\Admin\Commissioning;

use App\Http\Controllers\Controller;
use App\Models\CommissioningCertificate;
use App\Models\CommissioningScope;
use App\Models\CommissioningTest;
use App\Models\CommissioningTestPlan;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommissioningManagementController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search', ''));
        $status = $request->input('status');

        /*
        |--------------------------------------------------------------------------
        | Project Summary
        |--------------------------------------------------------------------------
        */

        $commissioningProjectQuery = Project::query();

        $totalProjects = (clone $commissioningProjectQuery)->count();

        $activeProjects = (clone $commissioningProjectQuery)
            ->whereIn('project_status', [
                'Active',
                'In Progress',
                'Construction',
            ])
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Commissioning Summary
        |--------------------------------------------------------------------------
        */

        $summary = [

            'total_scopes' => CommissioningScope::count(),

            'completed_scopes' => CommissioningScope::whereIn(
                'status',
                ['Completed', 'Accepted']
            )->count(),

            'total_test_plans' => CommissioningTestPlan::count(),

            'approved_test_plans' => CommissioningTestPlan::where(
                'status',
                'Approved'
            )->count(),

            'total_tests' => CommissioningTest::count(),

            'passed_tests' => CommissioningTest::where(
                'result',
                'Pass'
            )->count(),

            'failed_tests' => CommissioningTest::where(
                'result',
                'Fail'
            )->count(),

            'pending_tests' => CommissioningTest::whereIn(
                'status',
                [
                    'Planned',
                    'Scheduled',
                    'In Progress',
                    'Retest Required',
                ]
            )->count(),

            'total_certificates' => CommissioningCertificate::count(),

            'approved_certificates' => CommissioningCertificate::where(
                'status',
                'Approved'
            )->count(),

        ];


        /*
        |--------------------------------------------------------------------------
        | Project Statuses
        |--------------------------------------------------------------------------
        */

        $projectStatuses = Project::query()
            ->whereNotNull('project_status')
            ->where('project_status', '!=', '')
            ->distinct()
            ->orderBy('project_status')
            ->pluck('project_status');


        /*
        |--------------------------------------------------------------------------
        | Projects
        |--------------------------------------------------------------------------
        */

        $projectsQuery = Project::query()
            ->with('land')
            ->withCount([
                'commissioningScopes',
                'commissioningTestPlans',
                'commissioningTests',
                'commissioningCertificates',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {

            $projectsQuery->where(function ($query) use ($search) {

                $query
                    ->where(
                        'project_number',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'project_code',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'project_name',
                        'like',
                        '%' . $search . '%'
                    );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($status !== null && $status !== '') {

            $projectsQuery->where(
                'project_status',
                $status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $projects = $projectsQuery
            ->latest('id')
            ->paginate(15)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.commissioning.management.index',
            compact(
                'projects',
                'totalProjects',
                'activeProjects',
                'summary',
                'projectStatuses',
                'search',
                'status'
            )
        );
    }
}