<?php

namespace App\Http\Controllers\Admin\Commissioning;

use App\Http\Controllers\Controller;
use App\Models\CommissioningCertificate;
use App\Models\CommissioningScope;
use App\Models\CommissioningTest;
use App\Models\CommissioningTestPlan;
use App\Models\ConstructionWorkOrder;
use App\Models\Project;
use App\Services\CommissioningAuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommissioningScopeController extends Controller
{
    /**
     * Display commissioning scopes.
     */
    public function index(Project $project): View
    {
        $scopes = CommissioningScope::with('workOrder')
            ->where('project_id', $project->id)
            ->orderBy('scope_code')
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.commissioning.scopes.index',
            compact('project', 'scopes')
        );
    }


    /**
     * Show create form.
     */
    public function create(Project $project): View
    {
        $workOrders = ConstructionWorkOrder::where(
                'project_id',
                $project->id
            )
            ->whereNotIn('status', ['Cancelled'])
            ->orderBy('work_order_number')
            ->get();

        return view(
            'admin.commissioning.scopes.create',
            compact('project', 'workOrders')
        );
    }


    /**
     * Store commissioning scope.
     */
    public function store(
        Request $request,
        Project $project
    ): RedirectResponse {

        $data = $this->validated($request);

        $workOrder = ConstructionWorkOrder::where(
                'id',
                $data['construction_work_order_id']
            )
            ->where('project_id', $project->id)
            ->first();

        if (!$workOrder) {

            return back()
                ->withInput()
                ->withErrors([
                    'construction_work_order_id' =>
                        'Selected Work Order does not belong to this project.',
                ]);
        }


        $exists = CommissioningScope::where(
                'project_id',
                $project->id
            )
            ->where(
                'construction_work_order_id',
                $workOrder->id
            )
            ->exists();

        if ($exists) {

            return back()
                ->withInput()
                ->withErrors([
                    'construction_work_order_id' =>
                        'A commissioning scope already exists for this Work Order.',
                ]);
        }


        $data['project_id'] = $project->id;
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        /*
        |--------------------------------------------------------------------------
        | Create Scope
        |--------------------------------------------------------------------------
        */

        $scope = CommissioningScope::create($data);

        /*
        |--------------------------------------------------------------------------
        | Audit Log
        |--------------------------------------------------------------------------
        */

        CommissioningAuditLogService::created(
            $scope,
            "Commissioning scope {$scope->scope_code} created."
        );

        return redirect()
            ->route(
                'admin.projects.commissioning.scopes.index',
                ['project' => $project->id]
            )
            ->with(
                'success',
                'Commissioning scope created successfully.'
            );
    }


    /**
     * Display commissioning scope details.
     */
    public function show(
        Project $project,
        CommissioningScope $scope
    ): View {

        $this->check($project, $scope);

        $scope->load([
            'workOrder',
        ]);

        $testPlans = CommissioningTestPlan::where(
                'project_id',
                $project->id
            )
            ->where(
                'commissioning_scope_id',
                $scope->id
            )
            ->latest('id')
            ->get();

        $tests = CommissioningTest::with('plan')
            ->where(
                'project_id',
                $project->id
            )
            ->where(
                'commissioning_scope_id',
                $scope->id
            )
            ->latest('test_date')
            ->latest('id')
            ->get();

        $certificates = CommissioningCertificate::where(
                'project_id',
                $project->id
            )
            ->where(
                'commissioning_scope_id',
                $scope->id
            )
            ->latest('id')
            ->get();

        $testPlanCount = $testPlans->count();

        $testCount = $tests->count();

        $passedTests = $tests
            ->where('result', 'Pass')
            ->count();

        $failedTests = $tests
            ->where('result', 'Fail')
            ->count();

        $pendingTests = $tests
            ->whereIn('status', [
                'Planned',
                'Scheduled',
                'In Progress',
                'Retest Required',
            ])
            ->count();

        $certificateCount = $certificates->count();

        $approvedCertificates = $certificates
            ->where('status', 'Approved')
            ->count();

        $testPassRate = $testCount > 0
            ? round(($passedTests / $testCount) * 100, 1)
            : 0;

        return view(
            'admin.commissioning.scopes.show',
            compact(
                'project',
                'scope',
                'testPlans',
                'tests',
                'certificates',
                'testPlanCount',
                'testCount',
                'passedTests',
                'failedTests',
                'pendingTests',
                'certificateCount',
                'approvedCertificates',
                'testPassRate'
            )
        );
    }


    /**
     * Show edit form.
     */
    public function edit(
        Project $project,
        CommissioningScope $scope
    ): View {

        $this->check($project, $scope);

        $workOrders = ConstructionWorkOrder::where(
                'project_id',
                $project->id
            )
            ->whereNotIn('status', ['Cancelled'])
            ->orderBy('work_order_number')
            ->get();

        return view(
            'admin.commissioning.scopes.edit',
            compact(
                'project',
                'scope',
                'workOrders'
            )
        );
    }


    /**
     * Update commissioning scope.
     */
    public function update(
        Request $request,
        Project $project,
        CommissioningScope $scope
    ): RedirectResponse {

        $this->check($project, $scope);

        $data = $this->validated($request);

        $exists = CommissioningScope::where(
                'project_id',
                $project->id
            )
            ->where(
                'construction_work_order_id',
                $data['construction_work_order_id']
            )
            ->where(
                'id',
                '<>',
                $scope->id
            )
            ->exists();

        if ($exists) {

            return back()
                ->withInput()
                ->withErrors([
                    'construction_work_order_id' =>
                        'Another commissioning scope already uses this Work Order.',
                ]);
        }


        $workOrderExists = ConstructionWorkOrder::where(
                'id',
                $data['construction_work_order_id']
            )
            ->where('project_id', $project->id)
            ->exists();

        if (!$workOrderExists) {

            return back()
                ->withInput()
                ->withErrors([
                    'construction_work_order_id' =>
                        'Selected Work Order does not belong to this project.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Capture Old Values Before Update
        |--------------------------------------------------------------------------
        */

        $oldValues = $scope->getOriginal();

        $data['updated_by'] = auth()->id();

        $scope->update($data);

        /*
        |--------------------------------------------------------------------------
        | Audit Log
        |--------------------------------------------------------------------------
        */

        CommissioningAuditLogService::updated(
            $scope,
            $oldValues,
            "Commissioning scope {$scope->scope_code} updated."
        );

        return redirect()
            ->route(
                'admin.projects.commissioning.scopes.index',
                ['project' => $project->id]
            )
            ->with(
                'success',
                'Commissioning scope updated successfully.'
            );
    }


    /**
     * Delete commissioning scope.
     */
    public function destroy(
        Project $project,
        CommissioningScope $scope
    ): RedirectResponse {

        $this->check($project, $scope);

        /*
        |--------------------------------------------------------------------------
        | Capture Values Before Delete
        |--------------------------------------------------------------------------
        */

        $oldValues = $scope->getAttributes();

        $scopeCode = $scope->scope_code;

        /*
        |--------------------------------------------------------------------------
        | Delete
        |--------------------------------------------------------------------------
        */

        $scope->delete();

        /*
        |--------------------------------------------------------------------------
        | Audit Log
        |--------------------------------------------------------------------------
        */

        CommissioningAuditLogService::deleted(
            $scope,
            $oldValues,
            "Commissioning scope {$scopeCode} deleted."
        );

        return redirect()
            ->route(
                'admin.projects.commissioning.scopes.index',
                ['project' => $project->id]
            )
            ->with(
                'success',
                'Commissioning scope deleted successfully.'
            );
    }


    /**
     * Validation.
     */
    private function validated(Request $request): array
    {
        return $request->validate([

            'construction_work_order_id' =>
                'required|integer|exists:construction_work_orders,id',

            'scope_code' =>
                'required|string|max:50',

            'scope_name' =>
                'required|string|max:255',

            'scope_type' =>
                'nullable|string|max:100',

            'location' =>
                'nullable|string|max:255',

            'planned_start_date' =>
                'nullable|date',

            'planned_completion_date' =>
                'nullable|date|after_or_equal:planned_start_date',

            'actual_completion_date' =>
                'nullable|date',

            'status' =>
                'required|in:Planned,In Progress,Testing,Completed,Accepted,On Hold',

            'remarks' =>
                'nullable|string',

        ]);
    }


    /**
     * Ensure scope belongs to project.
     */
    private function check(
        Project $project,
        CommissioningScope $scope
    ): void {

        abort_unless(
            (int) $scope->project_id === (int) $project->id,
            404
        );
    }
}