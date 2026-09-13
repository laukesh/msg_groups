<?php

namespace App\Http\Controllers\Admin\Commissioning;

use App\Http\Controllers\Controller;
use App\Models\CommissioningScope;
use App\Models\CommissioningTest;
use App\Models\CommissioningTestPlan;
use App\Models\Project;
use App\Models\User;
use App\Services\CommissioningAuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommissioningTestPlanController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request,
        Project $project
    ): View {

        $query = CommissioningTestPlan::with([
            'scope',
            'scope.workOrder',
            'responsibleUser',
        ])
        ->where('project_id', $project->id)
        ->latest('id');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'test_plan_no',
                    'LIKE',
                    "%{$search}%"
                )
                ->orWhere(
                    'title',
                    'LIKE',
                    "%{$search}%"
                )
                ->orWhere(
                    'test_type',
                    'LIKE',
                    "%{$search}%"
                )
                ->orWhere(
                    'discipline',
                    'LIKE',
                    "%{$search}%"
                )
                ->orWhereHas(
                    'scope',
                    function ($scopeQuery) use ($search) {

                        $scopeQuery
                            ->where(
                                'scope_code',
                                'LIKE',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'scope_name',
                                'LIKE',
                                "%{$search}%"
                            );
                    }
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | KPI
        |--------------------------------------------------------------------------
        */

        $base = CommissioningTestPlan::where(
            'project_id',
            $project->id
        );

        $totalPlans = (clone $base)->count();

        $draftPlans = (clone $base)
            ->where('status', 'Draft')
            ->count();

        $submittedPlans = (clone $base)
            ->where('status', 'Submitted')
            ->count();

        $approvedPlans = (clone $base)
            ->where('status', 'Approved')
            ->count();

        $completedPlans = (clone $base)
            ->where('status', 'Completed')
            ->count();

        $rejectedPlans = (clone $base)
            ->where('status', 'Rejected')
            ->count();

        $inProgressPlans = (clone $base)
            ->where('status', 'In Progress')
            ->count();

        $onHoldPlans = (clone $base)
            ->where('status', 'On Hold')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Plans
        |--------------------------------------------------------------------------
        */

        $plans = $query
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.commissioning.test-plans.index',
            compact(
                'project',
                'plans',
                'totalPlans',
                'draftPlans',
                'submittedPlans',
                'approvedPlans',
                'completedPlans',
                'rejectedPlans',
                'inProgressPlans',
                'onHoldPlans'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(Project $project): View
    {
        $scopes = CommissioningScope::where(
            'project_id',
            $project->id
        )
        ->orderBy('scope_code')
        ->get();

        $users = User::orderBy('name')->get();

        return view(
            'admin.commissioning.test-plans.create',
            compact(
                'project',
                'scopes',
                'users'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Project $project
    ): RedirectResponse {

        $data = $this->validateData($request);

        /*
        |--------------------------------------------------------------------------
        | Validate Scope
        |--------------------------------------------------------------------------
        */

        $scopeExists = CommissioningScope::whereKey(
            $data['commissioning_scope_id']
        )
        ->where(
            'project_id',
            $project->id
        )
        ->exists();

        abort_unless(
            $scopeExists,
            422,
            'Selected commissioning scope does not belong to this project.'
        );

        /*
        |--------------------------------------------------------------------------
        | Duplicate Number
        |--------------------------------------------------------------------------
        */

        $exists = CommissioningTestPlan::where(
            'project_id',
            $project->id
        )
        ->where(
            'test_plan_no',
            $data['test_plan_no']
        )
        ->exists();

        if ($exists) {

            return back()
                ->withInput()
                ->withErrors([
                    'test_plan_no' =>
                        'This Test Plan Number already exists in this project.'
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Create As Draft
        |--------------------------------------------------------------------------
        */

        $data['project_id'] = $project->id;
        $data['status'] = 'Draft';
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        $plan = CommissioningTestPlan::create($data);

        /*
        |--------------------------------------------------------------------------
        | Audit Log - Created
        |--------------------------------------------------------------------------
        */

        CommissioningAuditLogService::created(
            $plan,
            "Commissioning Test Plan {$plan->test_plan_no} created as Draft."
        );

        return redirect()
            ->route(
                'admin.projects.commissioning.test-plans.show',
                [
                    'project' => $project->id,
                    'test_plan' => $plan->id,
                ]
            )
            ->with(
                'success',
                'Commissioning Test Plan created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        CommissioningTestPlan $test_plan
    ): View {

        $this->check(
            $project,
            $test_plan
        );

        $plan = $test_plan;

        $plan->load([
            'scope',
            'scope.workOrder',
            'responsibleUser',
            'submittedBy',
            'approvedBy',
            'rejectedBy',
            'createdBy',
            'updatedBy',
            'tests' => function ($query) use ($project) {

                $query
                    ->where(
                        'project_id',
                        $project->id
                    )
                    ->with([
                        'scope',
                        'parentTest',
                    ])
                    ->latest('test_date')
                    ->latest('id');
            },
        ]);

        /*
        |--------------------------------------------------------------------------
        | Test Statistics
        |--------------------------------------------------------------------------
        */

        $totalTests = $plan->tests->count();

        $passedTests = $plan->tests
            ->where('result', 'Pass')
            ->count();

        $failedTests = $plan->tests
            ->where('result', 'Fail')
            ->count();

        $conditionalPassTests = $plan->tests
            ->where('result', 'Conditional Pass')
            ->count();

        $notTestedTests = $plan->tests
            ->where('result', 'Not Tested')
            ->count();

        $retestTests = $plan->tests
            ->where('status', 'Retest Required')
            ->count();

        $completedTests = $plan->tests
            ->where('status', 'Completed')
            ->count();

        $inProgressTests = $plan->tests
            ->where('status', 'In Progress')
            ->count();

        $passRate = $totalTests > 0
            ? round(
                ($passedTests / $totalTests) * 100,
                1
            )
            : 0;

        return view(
            'admin.commissioning.test-plans.show',
            compact(
                'project',
                'plan',
                'totalTests',
                'passedTests',
                'failedTests',
                'conditionalPassTests',
                'notTestedTests',
                'retestTests',
                'completedTests',
                'inProgressTests',
                'passRate'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        Project $project,
        CommissioningTestPlan $test_plan
    ): View {

        $this->check(
            $project,
            $test_plan
        );

        abort_unless(
            in_array(
                $test_plan->status,
                [
                    'Draft',
                    'Rejected',
                    'On Hold',
                ],
                true
            ),
            403,
            'This Test Plan cannot be edited in its current status.'
        );

        $plan = $test_plan;

        $scopes = CommissioningScope::where(
            'project_id',
            $project->id
        )
        ->orderBy('scope_code')
        ->get();

        $users = User::orderBy('name')->get();

        return view(
            'admin.commissioning.test-plans.edit',
            compact(
                'project',
                'plan',
                'scopes',
                'users'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Project $project,
        CommissioningTestPlan $test_plan
    ): RedirectResponse {

        $this->check(
            $project,
            $test_plan
        );

        abort_unless(
            in_array(
                $test_plan->status,
                [
                    'Draft',
                    'Rejected',
                    'On Hold',
                ],
                true
            ),
            403,
            'This Test Plan cannot be edited in its current status.'
        );

        $data = $this->validateData($request);

        /*
        |--------------------------------------------------------------------------
        | Validate Scope
        |--------------------------------------------------------------------------
        */

        $scopeExists = CommissioningScope::whereKey(
            $data['commissioning_scope_id']
        )
        ->where(
            'project_id',
            $project->id
        )
        ->exists();

        abort_unless(
            $scopeExists,
            422,
            'Selected commissioning scope does not belong to this project.'
        );

        /*
        |--------------------------------------------------------------------------
        | Duplicate Number
        |--------------------------------------------------------------------------
        */

        $exists = CommissioningTestPlan::where(
            'project_id',
            $project->id
        )
        ->where(
            'test_plan_no',
            $data['test_plan_no']
        )
        ->where(
            'id',
            '<>',
            $test_plan->id
        )
        ->exists();

        if ($exists) {

            return back()
                ->withInput()
                ->withErrors([
                    'test_plan_no' =>
                        'This Test Plan Number already exists in this project.'
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Capture Old Values
        |--------------------------------------------------------------------------
        */

        $oldValues = $test_plan->getAttributes();

        /*
        |--------------------------------------------------------------------------
        | Status Cannot Be Changed From Edit
        |--------------------------------------------------------------------------
        */

        unset($data['status']);

        $data['updated_by'] = auth()->id();

        /*
        |--------------------------------------------------------------------------
        | Rejected -> Draft When Edited
        |--------------------------------------------------------------------------
        */

        if ($test_plan->status === 'Rejected') {

            $data['status'] = 'Draft';

            $data['rejection_reason'] = null;
            $data['rejected_by'] = null;
            $data['rejected_at'] = null;
        }

        $test_plan->update($data);

        /*
        |--------------------------------------------------------------------------
        | Audit Log - Updated
        |--------------------------------------------------------------------------
        */

        CommissioningAuditLogService::updated(
            $test_plan,
            $oldValues,
            "Commissioning Test Plan {$test_plan->test_plan_no} updated."
        );

        /*
        |--------------------------------------------------------------------------
        | Additional Audit - Rejected To Draft
        |--------------------------------------------------------------------------
        */

        if (
            isset($oldValues['status']) &&
            $oldValues['status'] === 'Rejected' &&
            $test_plan->status === 'Draft'
        ) {

            CommissioningAuditLogService::action(
                'resubmission_started',
                $test_plan,
                "Rejected Test Plan {$test_plan->test_plan_no} edited and returned to Draft.",
                [
                    'status' => 'Rejected',
                ],
                [
                    'status' => 'Draft',
                ]
            );
        }

        return redirect()
            ->route(
                'admin.projects.commissioning.test-plans.show',
                [
                    'project' => $project->id,
                    'test_plan' => $test_plan->id,
                ]
            )
            ->with(
                'success',
                'Commissioning Test Plan updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        CommissioningTestPlan $test_plan
    ): RedirectResponse {

        $this->check(
            $project,
            $test_plan
        );

        abort_unless(
            in_array(
                $test_plan->status,
                [
                    'Draft',
                    'Rejected',
                ],
                true
            ),
            403,
            'Only Draft or Rejected Test Plans can be deleted.'
        );

        $testCount = CommissioningTest::where(
            'test_plan_id',
            $test_plan->id
        )->count();

        abort_unless(
            $testCount === 0,
            422,
            'This Test Plan cannot be deleted because test executions already exist.'
        );

        /*
        |--------------------------------------------------------------------------
        | Capture Before Delete
        |--------------------------------------------------------------------------
        */

        $oldValues = $test_plan->getAttributes();

        $testPlanNo = $test_plan->test_plan_no;

        /*
        |--------------------------------------------------------------------------
        | Delete
        |--------------------------------------------------------------------------
        */

        $test_plan->delete();

        /*
        |--------------------------------------------------------------------------
        | Audit Log - Deleted
        |--------------------------------------------------------------------------
        */

        CommissioningAuditLogService::log(
            'deleted',
            $test_plan,
            "Commissioning Test Plan {$testPlanNo} deleted.",
            $oldValues,
            null,
            $project->id
        );

        return redirect()
            ->route(
                'admin.projects.commissioning.test-plans.index',
                [
                    'project' => $project->id,
                ]
            )
            ->with(
                'success',
                'Commissioning Test Plan deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SUBMIT
    |--------------------------------------------------------------------------
    */

    public function submit(
        Project $project,
        CommissioningTestPlan $test_plan
    ): RedirectResponse {

        $this->check(
            $project,
            $test_plan
        );

        abort_unless(
            in_array(
                $test_plan->status,
                [
                    'Draft',
                    'Rejected',
                ],
                true
            ),
            422,
            'Only Draft or Rejected Test Plans can be submitted.'
        );

        $errors = [];

        if (!$test_plan->title) {
            $errors[] = 'Test Plan title is required.';
        }

        if (!$test_plan->commissioning_scope_id) {
            $errors[] = 'Commissioning scope is required.';
        }

        if (!$test_plan->test_type) {
            $errors[] = 'Test type is required.';
        }

        if (!$test_plan->test_procedure) {
            $errors[] = 'Test procedure is required.';
        }

        if (!empty($errors)) {
            return back()->withErrors($errors);
        }

        $oldStatus = $test_plan->status;

        $test_plan->update([
            'status' => 'Submitted',
            'submitted_by' => auth()->id(),
            'submitted_at' => now(),
            'rejected_by' => null,
            'rejected_at' => null,
            'rejection_reason' => null,
            'updated_by' => auth()->id(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Audit Log - Submitted
        |--------------------------------------------------------------------------
        */

        CommissioningAuditLogService::submitted(
            $test_plan,
            "Commissioning Test Plan {$test_plan->test_plan_no} submitted for approval."
        );

        return back()
            ->with(
                'success',
                'Test Plan submitted for approval.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | APPROVE
    |--------------------------------------------------------------------------
    */

    public function approve(
        Project $project,
        CommissioningTestPlan $test_plan
    ): RedirectResponse {

        $this->check(
            $project,
            $test_plan
        );

        abort_unless(
            $test_plan->status === 'Submitted',
            422,
            'Only Submitted Test Plans can be approved.'
        );

        $oldStatus = $test_plan->status;

        $test_plan->update([
            'status' => 'Approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'updated_by' => auth()->id(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Audit Log - Approved
        |--------------------------------------------------------------------------
        */

        CommissioningAuditLogService::approved(
            $test_plan,
            "Commissioning Test Plan {$test_plan->test_plan_no} approved and made ready for execution."
        );

        return back()
            ->with(
                'success',
                'Test Plan approved successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | REJECT
    |--------------------------------------------------------------------------
    */

    public function reject(
        Request $request,
        Project $project,
        CommissioningTestPlan $test_plan
    ): RedirectResponse {

        $this->check(
            $project,
            $test_plan
        );

        abort_unless(
            $test_plan->status === 'Submitted',
            422,
            'Only Submitted Test Plans can be rejected.'
        );

        $request->validate([
            'rejection_reason' =>
                'required|string|max:2000',
        ]);

        $oldStatus = $test_plan->status;

        $test_plan->update([
            'status' => 'Rejected',
            'rejected_by' => auth()->id(),
            'rejected_at' => now(),
            'rejection_reason' => $request->rejection_reason,
            'updated_by' => auth()->id(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Audit Log - Rejected
        |--------------------------------------------------------------------------
        */

        CommissioningAuditLogService::rejected(
            $test_plan,
            "Commissioning Test Plan {$test_plan->test_plan_no} rejected. Reason: "
                . $request->rejection_reason
        );

        return back()
            ->with(
                'success',
                'Test Plan rejected.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | HOLD
    |--------------------------------------------------------------------------
    */

    public function hold(
        Project $project,
        CommissioningTestPlan $test_plan
    ): RedirectResponse {

        $this->check(
            $project,
            $test_plan
        );

        abort_unless(
            in_array(
                $test_plan->status,
                [
                    'Approved',
                    'In Progress',
                ],
                true
            ),
            422,
            'This Test Plan cannot be put On Hold.'
        );

        $oldStatus = $test_plan->status;

        $test_plan->update([
            'status' => 'On Hold',
            'updated_by' => auth()->id(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Audit Log - On Hold
        |--------------------------------------------------------------------------
        */

        CommissioningAuditLogService::action(
            'on_hold',
            $test_plan,
            "Commissioning Test Plan {$test_plan->test_plan_no} put On Hold.",
            [
                'status' => $oldStatus,
            ],
            [
                'status' => 'On Hold',
            ]
        );

        return back()
            ->with(
                'success',
                'Test Plan put On Hold.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | RESUME
    |--------------------------------------------------------------------------
    */

    public function resume(
        Project $project,
        CommissioningTestPlan $test_plan
    ): RedirectResponse {

        $this->check(
            $project,
            $test_plan
        );

        abort_unless(
            $test_plan->status === 'On Hold',
            422,
            'Only On Hold Test Plans can be resumed.'
        );

        $oldStatus = $test_plan->status;

        $test_plan->update([
            'status' => 'Approved',
            'updated_by' => auth()->id(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Audit Log - Resumed
        |--------------------------------------------------------------------------
        */

        CommissioningAuditLogService::action(
            'resumed',
            $test_plan,
            "Commissioning Test Plan {$test_plan->test_plan_no} resumed and returned to Approved.",
            [
                'status' => $oldStatus,
            ],
            [
                'status' => 'Approved',
            ]
        );

        return back()
            ->with(
                'success',
                'Test Plan resumed successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | START
    |--------------------------------------------------------------------------
    */

    public function start(
        Project $project,
        CommissioningTestPlan $test_plan
    ): RedirectResponse {

        $this->check(
            $project,
            $test_plan
        );

        abort_unless(
            $test_plan->status === 'Approved',
            422,
            'Only Approved Test Plans can be started.'
        );

        $oldStatus = $test_plan->status;

        $test_plan->update([
            'status' => 'In Progress',
            'updated_by' => auth()->id(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Audit Log - Started
        |--------------------------------------------------------------------------
        */

        CommissioningAuditLogService::action(
            'started',
            $test_plan,
            "Commissioning Test Plan {$test_plan->test_plan_no} execution started.",
            [
                'status' => $oldStatus,
            ],
            [
                'status' => 'In Progress',
            ]
        );

        return back()
            ->with(
                'success',
                'Test Plan marked In Progress.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | COMPLETE
    |--------------------------------------------------------------------------
    */

    public function complete(
        Project $project,
        CommissioningTestPlan $test_plan
    ): RedirectResponse {

        $this->check(
            $project,
            $test_plan
        );

        abort_unless(
            $test_plan->status === 'In Progress',
            422,
            'Only In Progress Test Plans can be completed.'
        );

        $tests = CommissioningTest::where(
            'project_id',
            $project->id
        )
        ->where(
            'test_plan_id',
            $test_plan->id
        )
        ->get();

        /*
        |--------------------------------------------------------------------------
        | At Least One Test
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $tests->count() > 0,
            422,
            'At least one test execution is required before completing the Test Plan.'
        );

        /*
        |--------------------------------------------------------------------------
        | Incomplete Tests
        |--------------------------------------------------------------------------
        */

        $incompleteTests = $tests
            ->whereNotIn(
                'status',
                [
                    'Completed',
                    'Cancelled',
                ]
            )
            ->count();

        abort_unless(
            $incompleteTests === 0,
            422,
            'All test executions must be completed or cancelled before completing the Test Plan.'
        );

        /*
        |--------------------------------------------------------------------------
        | Failed Tests
        |--------------------------------------------------------------------------
        */

        $failedTests = $tests
            ->where(
                'result',
                'Fail'
            )
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Retest Required
        |--------------------------------------------------------------------------
        */

        $retestTests = $tests
            ->where(
                'status',
                'Retest Required'
            )
            ->count();

        abort_unless(
            $failedTests === 0
                && $retestTests === 0,
            422,
            'Test Plan cannot be completed while failed or retest-required tests exist.'
        );

        /*
        |--------------------------------------------------------------------------
        | Complete
        |--------------------------------------------------------------------------
        */

        $oldStatus = $test_plan->status;

        $test_plan->update([
            'status' => 'Completed',
            'updated_by' => auth()->id(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Audit Log - Completed
        |--------------------------------------------------------------------------
        */

        CommissioningAuditLogService::action(
            'completed',
            $test_plan,
            "Commissioning Test Plan {$test_plan->test_plan_no} completed successfully.",
            [
                'status' => $oldStatus,
            ],
            [
                'status' => 'Completed',
            ]
        );

        return back()
            ->with(
                'success',
                'Test Plan completed successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    private function validateData(
        Request $request
    ): array {

        return $request->validate([

            'commissioning_scope_id' =>
                'required|integer|exists:commissioning_scopes,id',

            'test_plan_no' =>
                'required|string|max:80',

            'title' =>
                'required|string|max:255',

            'discipline' =>
                'nullable|string|max:150',

            'test_type' =>
                'required|string|max:150',

            'purpose' =>
                'nullable|string',

            'prerequisites' =>
                'nullable|string',

            'test_procedure' =>
                'required|string',

            'required_instruments' =>
                'nullable|string',

            'required_personnel' =>
                'nullable|string',

            'witness_required' =>
                'nullable|boolean',

            'client_witness_required' =>
                'nullable|boolean',

            'consultant_witness_required' =>
                'nullable|boolean',

            'planned_start_date' =>
                'nullable|date',

            'planned_completion_date' =>
                'nullable|date|after_or_equal:planned_start_date',

            'responsible_user_id' =>
                'nullable|integer|exists:users,id',

            'description' =>
                'nullable|string',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | PROJECT CHECK
    |--------------------------------------------------------------------------
    */

    private function check(
        Project $project,
        CommissioningTestPlan $test_plan
    ): void {

        abort_unless(
            (int) $test_plan->project_id ===
            (int) $project->id,
            404
        );
    }
}