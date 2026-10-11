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

class CommissioningTestController extends Controller
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

        $query = CommissioningTest::with([
            'scope',
            'plan',
            'parentTest',
            'retests',
            'performedBy',
        ])
        ->where('project_id', $project->id);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'test_no',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'test_type',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'location',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'actual_result',
                    'like',
                    "%{$search}%"
                )
                ->orWhereHas(
                    'scope',
                    function ($scope) use ($search) {

                        $scope
                            ->where(
                                'scope_code',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'scope_name',
                                'like',
                                "%{$search}%"
                            );
                    }
                )
                ->orWhereHas(
                    'plan',
                    function ($plan) use ($search) {

                        $plan
                            ->where(
                                'test_plan_no',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'title',
                                'like',
                                "%{$search}%"
                            );
                    }
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Result Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('result')) {

            $query->where(
                'result',
                $request->result
            );
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
        | Test Type
        |--------------------------------------------------------------------------
        */

        if ($request->filled('test_type')) {

            $query->where(
                'test_type',
                'like',
                '%' . trim($request->test_type) . '%'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Retest Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('retest')) {

            if ($request->retest === 'yes') {

                $query->where(function ($q) {

                    $q->where(
                        'retest_required',
                        1
                    )
                    ->orWhereNotNull(
                        'parent_test_id'
                    );
                });
            }

            if ($request->retest === 'no') {

                $query
                    ->where(
                        'retest_required',
                        0
                    )
                    ->whereNull(
                        'parent_test_id'
                    );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Date Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('from_date')) {

            $query->whereDate(
                'test_date',
                '>=',
                $request->from_date
            );
        }

        if ($request->filled('to_date')) {

            $query->whereDate(
                'test_date',
                '<=',
                $request->to_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Tests
        |--------------------------------------------------------------------------
        */

        $tests = $query
            ->latest('test_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | KPI
        |--------------------------------------------------------------------------
        */

        $base = CommissioningTest::where(
            'project_id',
            $project->id
        );

        $totalTests = (clone $base)->count();

        $plannedTests = (clone $base)
            ->where('status', 'Planned')
            ->count();

        $scheduledTests = (clone $base)
            ->where('status', 'Scheduled')
            ->count();

        $inProgressTests = (clone $base)
            ->where('status', 'In Progress')
            ->count();

        $completedTests = (clone $base)
            ->where('status', 'Completed')
            ->count();

        $passedTests = (clone $base)
            ->where('result', 'Pass')
            ->count();

        $failedTests = (clone $base)
            ->where('result', 'Fail')
            ->count();

        $conditionalTests = (clone $base)
            ->where('result', 'Conditional Pass')
            ->count();

        $notTested = (clone $base)
            ->where('result', 'Not Tested')
            ->count();

        $retestRequired = (clone $base)
            ->where(function ($q) {

                $q->where(
                    'retest_required',
                    1
                )
                ->orWhere(
                    'status',
                    'Retest Required'
                );
            })
            ->count();

        $retestExecutions = (clone $base)
            ->whereNotNull('parent_test_id')
            ->count();

        $passRate = $totalTests > 0
            ? round(
                ($passedTests / $totalTests) * 100,
                1
            )
            : 0;

        return view(
            'admin.commissioning.tests.index',
            compact(
                'project',
                'tests',
                'totalTests',
                'plannedTests',
                'scheduledTests',
                'inProgressTests',
                'completedTests',
                'passedTests',
                'failedTests',
                'conditionalTests',
                'notTested',
                'retestRequired',
                'retestExecutions',
                'passRate'
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

        /*
         * Only approved / in-progress plans should receive test executions.
         */

        $plans = CommissioningTestPlan::where(
            'project_id',
            $project->id
        )
        ->whereIn(
            'status',
            [
                'Approved',
                'In Progress',
            ]
        )
        ->orderBy('test_plan_no')
        ->get();

        $parentTests = CommissioningTest::where(
            'project_id',
            $project->id
        )
        ->whereNull('parent_test_id')
        ->where(function ($q) {

            $q->where(
                'retest_required',
                1
            )
            ->orWhere(
                'status',
                'Retest Required'
            )
            ->orWhere(
                'result',
                'Fail'
            );
        })
        ->orderByDesc('id')
        ->get();

        $users = User::orderBy('name')->get();

        return view(
            'admin.commissioning.tests.create',
            compact(
                'project',
                'scopes',
                'plans',
                'parentTests',
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

        $this->validateRelations(
            $project,
            $data
        );

        /*
        |--------------------------------------------------------------------------
        | Test Number uniqueness within project
        |--------------------------------------------------------------------------
        */

        $exists = CommissioningTest::where(
            'project_id',
            $project->id
        )
        ->where(
            'test_no',
            $data['test_no']
        )
        ->exists();

        if ($exists) {

            return back()
                ->withInput()
                ->withErrors([
                    'test_no' =>
                        'This Test Number already exists in this project.'
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Default values
        |--------------------------------------------------------------------------
        */

        $data['project_id'] = $project->id;

        $data['retest_required'] =
            $request->boolean('retest_required');

        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        /*
        |--------------------------------------------------------------------------
        | Automatically determine status
        |--------------------------------------------------------------------------
        */

        if ($data['status'] === 'Completed') {

            $data['completed_at'] = now();
        }

        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        $test = CommissioningTest::create($data);

        /*
        |--------------------------------------------------------------------------
        | Audit Log - Created
        |--------------------------------------------------------------------------
        */

        CommissioningAuditLogService::created(
            $test,
            "Commissioning test execution {$test->test_no} created."
        );

        /*
        |--------------------------------------------------------------------------
        | Retest Audit
        |--------------------------------------------------------------------------
        */

        if (!empty($test->parent_test_id)) {

            CommissioningAuditLogService::action(
                'retest_created',
                $test,
                "Retest {$test->test_no} created for parent test."
            );
        }

        return redirect()
            ->route(
                'admin.projects.commissioning.tests.show',
                [
                    'project' => $project->id,
                    'test' => $test->id,
                ]
            )
            ->with(
                'success',
                'Commissioning test execution created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        CommissioningTest $test
    ): View {

        $this->check(
            $project,
            $test
        );

        $test->load([
            'project',
            'scope.workOrder',
            'plan',
            'parentTest',
            'retests.parentTest',
            'createdBy',
            'updatedBy',
            'performedBy',
            'witnessedBy',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Retest / Result Summary
        |--------------------------------------------------------------------------
        */

        $retests = $test->retests;

        $totalRetests = $retests->count();

        $passedRetests = $retests
            ->where('result', 'Pass')
            ->count();

        $failedRetests = $retests
            ->where('result', 'Fail')
            ->count();

        $latestRetest = $retests->first();

        return view(
            'admin.commissioning.tests.show',
            compact(
                'project',
                'test',
                'retests',
                'totalRetests',
                'passedRetests',
                'failedRetests',
                'latestRetest'
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
        CommissioningTest $test
    ): View {

        $this->check(
            $project,
            $test
        );

        /*
        |--------------------------------------------------------------------------
        | Do not edit a completed test
        |--------------------------------------------------------------------------
        */

        abort_unless(
            in_array(
                $test->status,
                [
                    'Planned',
                    'Scheduled',
                    'In Progress',
                    'Retest Required',
                ],
                true
            ),
            403,
            'Completed or cancelled tests cannot be edited.'
        );

        $scopes = CommissioningScope::where(
            'project_id',
            $project->id
        )
        ->orderBy('scope_code')
        ->get();

        $plans = CommissioningTestPlan::where(
            'project_id',
            $project->id
        )
        ->whereIn(
            'status',
            [
                'Approved',
                'In Progress',
            ]
        )
        ->orderBy('test_plan_no')
        ->get();

        $parentTests = CommissioningTest::where(
            'project_id',
            $project->id
        )
        ->whereNull('parent_test_id')
        ->where(
            'id',
            '<>',
            $test->id
        )
        ->where(function ($q) {

            $q->where(
                'retest_required',
                1
            )
            ->orWhere(
                'status',
                'Retest Required'
            )
            ->orWhere(
                'result',
                'Fail'
            );
        })
        ->orderByDesc('id')
        ->get();

        $users = User::orderBy('name')->get();

        return view(
            'admin.commissioning.tests.edit',
            compact(
                'project',
                'test',
                'scopes',
                'plans',
                'parentTests',
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
        CommissioningTest $test
    ): RedirectResponse {

        $this->check(
            $project,
            $test
        );

        abort_unless(
            in_array(
                $test->status,
                [
                    'Planned',
                    'Scheduled',
                    'In Progress',
                    'Retest Required',
                ],
                true
            ),
            403,
            'Completed or cancelled tests cannot be edited.'
        );

        $data = $this->validateData(
            $request,
            $test->id
        );

        $this->validateRelations(
            $project,
            $data,
            $test->id
        );

        /*
        |--------------------------------------------------------------------------
        | Capture Old Values
        |--------------------------------------------------------------------------
        */

        $oldValues = $test->getAttributes();

        $oldStatus = $test->status;
        $oldResult = $test->result;

        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $data['retest_required'] =
            $request->boolean('retest_required');

        $data['updated_by'] = auth()->id();

        if (
            $data['status'] === 'Completed'
            && $test->status !== 'Completed'
        ) {

            $data['completed_at'] = now();
        }

        $test->update($data);

        /*
        |--------------------------------------------------------------------------
        | Audit Log - Updated
        |--------------------------------------------------------------------------
        */

        CommissioningAuditLogService::updated(
            $test,
            $oldValues,
            "Commissioning test execution {$test->test_no} updated."
        );

        /*
        |--------------------------------------------------------------------------
        | Status Change Audit
        |--------------------------------------------------------------------------
        */

        if ($oldStatus !== $test->status) {

            CommissioningAuditLogService::action(
                'status_changed',
                $test,
                "Commissioning test {$test->test_no} status changed from {$oldStatus} to {$test->status}.",
                [
                    'status' => $oldStatus,
                ],
                [
                    'status' => $test->status,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Result Change Audit
        |--------------------------------------------------------------------------
        */

        if ($oldResult !== $test->result) {

            CommissioningAuditLogService::action(
                'result_changed',
                $test,
                "Commissioning test {$test->test_no} result changed from {$oldResult} to {$test->result}.",
                [
                    'result' => $oldResult,
                ],
                [
                    'result' => $test->result,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Retest Required
        |--------------------------------------------------------------------------
        */

        if (
            $test->retest_required
            && !$oldValues['retest_required']
        ) {

            CommissioningAuditLogService::action(
                'retest_required',
                $test,
                "Retest required for commissioning test {$test->test_no}.",
                [
                    'retest_required' => false,
                ],
                [
                    'retest_required' => true,
                ]
            );
        }

        return redirect()
            ->route(
                'admin.projects.commissioning.tests.show',
                [
                    'project' => $project->id,
                    'test' => $test->id,
                ]
            )
            ->with(
                'success',
                'Commissioning test execution updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        CommissioningTest $test
    ): RedirectResponse {

        $this->check(
            $project,
            $test
        );

        abort_unless(
            !in_array(
                $test->status,
                [
                    'Completed',
                ],
                true
            ),
            403,
            'Completed tests cannot be deleted.'
        );

        if ($test->retests()->exists()) {

            return back()->with(
                'error',
                'This test cannot be deleted because retest records exist.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Capture Before Delete
        |--------------------------------------------------------------------------
        */

        $oldValues = $test->getAttributes();

        $testNo = $test->test_no;

        /*
        |--------------------------------------------------------------------------
        | Delete
        |--------------------------------------------------------------------------
        */

        $test->delete();

        /*
        |--------------------------------------------------------------------------
        | Audit Log - Deleted
        |--------------------------------------------------------------------------
        */

        CommissioningAuditLogService::log(
            'deleted',
            $test,
            "Commissioning test execution {$testNo} deleted.",
            $oldValues,
            null,
            $project->id
        );

        return redirect()
            ->route(
                'admin.projects.commissioning.tests.index',
                [
                    'project' => $project->id,
                ]
            )
            ->with(
                'success',
                'Commissioning test deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE RETEST
    |--------------------------------------------------------------------------
    */

    public function createRetest(
        Project $project,
        CommissioningTest $test
    ): View {

        $this->check(
            $project,
            $test
        );

        abort_unless(
            $test->result === 'Fail'
                || $test->retest_required
                || $test->status === 'Retest Required',
            422,
            'This test does not require a retest.'
        );

        $scopes = CommissioningScope::where(
            'project_id',
            $project->id
        )
        ->orderBy('scope_code')
        ->get();

        $plans = CommissioningTestPlan::where(
            'project_id',
            $project->id
        )
        ->whereIn(
            'status',
            [
                'Approved',
                'In Progress',
            ]
        )
        ->orderBy('test_plan_no')
        ->get();

        $users = User::orderBy('name')->get();

        return view(
            'admin.commissioning.tests.create',
            [
                'project' => $project,
                'scopes' => $scopes,
                'plans' => $plans,
                'parentTests' => collect(),
                'users' => $users,
                'parentTest' => $test,
                'isRetest' => true,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    private function validateData(
        Request $request,
        ?int $ignoreId = null
    ): array {

        return $request->validate([

            'commissioning_scope_id' =>
                'required|integer|exists:commissioning_scopes,id',

            'test_plan_id' =>
                'nullable|integer|exists:commissioning_test_plans,id',

            'test_no' =>
                'required|string|max:50',

            'test_date' =>
                'nullable|date',

            'test_time' =>
                'nullable|date_format:H:i',

            'test_type' =>
                'nullable|string|max:150',

            'location' =>
                'nullable|string|max:255',

            'expected_result' =>
                'nullable|string',

            'actual_result' =>
                'nullable|string',

            'measured_value' =>
                'nullable|string|max:100',

            'measured_unit' =>
                'nullable|string|max:50',

            'result' =>
                'required|in:Pass,Fail,Conditional Pass,Not Tested',

            'retest_required' =>
                'nullable|boolean',

            'parent_test_id' =>
                'nullable|integer|exists:commissioning_tests,id',

            'status' =>
                'required|in:Planned,Scheduled,In Progress,Completed,Retest Required,Cancelled',

            'remarks' =>
                'nullable|string',

            'attachment_path' =>
                'nullable|string|max:500',

            'performed_by' =>
                'nullable|integer|exists:users,id',

            'witnessed_by' =>
                'nullable|integer|exists:users,id',

            'consultant_representative' =>
                'nullable|string|max:255',

            'client_representative' =>
                'nullable|string|max:255',

            'site_condition' =>
                'nullable|string|max:255',

            'retest_date' =>
                'nullable|date',

            'completed_at' =>
                'nullable|date',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | RELATION VALIDATION
    |--------------------------------------------------------------------------
    */

    private function validateRelations(
        Project $project,
        array $data,
        ?int $ignoreId = null
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Scope belongs to project
        |--------------------------------------------------------------------------
        */

        abort_unless(
            CommissioningScope::whereKey(
                $data['commissioning_scope_id']
            )
            ->where(
                'project_id',
                $project->id
            )
            ->exists(),
            422,
            'Selected commissioning scope does not belong to this project.'
        );

        /*
        |--------------------------------------------------------------------------
        | Test Plan belongs to project + scope
        |--------------------------------------------------------------------------
        */

        if (!empty($data['test_plan_id'])) {

            abort_unless(
                CommissioningTestPlan::whereKey(
                    $data['test_plan_id']
                )
                ->where(
                    'project_id',
                    $project->id
                )
                ->where(
                    'commissioning_scope_id',
                    $data['commissioning_scope_id']
                )
                ->exists(),
                422,
                'Selected Test Plan does not belong to the selected scope.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Parent Test
        |--------------------------------------------------------------------------
        */

        if (!empty($data['parent_test_id'])) {

            abort_unless(
                CommissioningTest::whereKey(
                    $data['parent_test_id']
                )
                ->where(
                    'project_id',
                    $project->id
                )
                ->whereNull(
                    'parent_test_id'
                )
                ->where(
                    'id',
                    '<>',
                    $ignoreId
                )
                ->exists(),
                422,
                'Selected parent test is invalid.'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | PROJECT CHECK
    |--------------------------------------------------------------------------
    */

    private function check(
        Project $project,
        CommissioningTest $test
    ): void {

        abort_unless(
            (int) $test->project_id ===
            (int) $project->id,
            404
        );
    }
}