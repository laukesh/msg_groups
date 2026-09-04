<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionProgressUpdate;
use App\Models\ConstructionSiteIssue;
use App\Models\ConstructionWorkOrder;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ConstructionSiteIssueController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Project $project): View
    {
        $issues = ConstructionSiteIssue::query()
            ->where('project_id', $project->id)
            ->with([
                'workOrder',
                'progress',
                'raisedBy',
                'assignedTo',
            ])
            ->orderByDesc('issue_date')
            ->orderByDesc('id')
            ->get();

        $today = now()->startOfDay();

        $summary = [

            'total' =>
                $issues->count(),

            'open' =>
                $issues->whereIn(
                    'status',
                    [
                        'Open',
                        'In Progress',
                        'Reopened',
                    ]
                )->count(),

            'high_priority' =>
                $issues->whereIn(
                    'priority',
                    [
                        'High',
                        'Critical',
                    ]
                )->whereIn(
                    'status',
                    [
                        'Open',
                        'In Progress',
                        'Reopened',
                    ]
                )->count(),

            'overdue' =>
                $issues->filter(
                    function ($issue) use ($today) {

                        return $issue->due_date
                            && $issue->due_date
                                ->lt($today)
                            && in_array(
                                $issue->status,
                                [
                                    'Open',
                                    'In Progress',
                                    'Reopened',
                                ],
                                true
                            );
                    }
                )->count(),

            'resolved' =>
                $issues->whereIn(
                    'status',
                    [
                        'Resolved',
                        'Closed',
                    ]
                )->count(),
        ];

        return view(
            'construction.site-issues.index',
            compact(
                'project',
                'issues',
                'summary'
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
        $workOrders = ConstructionWorkOrder::query()
            ->where(
                'project_id',
                $project->id
            )
            ->with([
                'contract.bidder',
            ])
            ->whereNotIn(
                'status',
                [
                    'Cancelled',
                ]
            )
            ->orderBy(
                'work_order_number'
            )
            ->get();

        $progressUpdates =
            ConstructionProgressUpdate::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->orderByDesc(
                    'progress_date'
                )
                ->orderByDesc('id')
                ->get();

        $users = User::query()
            ->orderBy('name')
            ->get();

        return view(
            'construction.site-issues.create',
            compact(
                'project',
                'workOrders',
                'progressUpdates',
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

        $validated =
            $this->validateIssue(
                $request
            );

        /*
        |--------------------------------------------------------------------------
        | Work Order → Project
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated[
                    'construction_work_order_id'
                ]
            )
        ) {

            $workOrder =
                ConstructionWorkOrder::query()
                    ->whereKey(
                        $validated[
                            'construction_work_order_id'
                        ]
                    )
                    ->where(
                        'project_id',
                        $project->id
                    )
                    ->exists();

            if (!$workOrder) {

                return back()
                    ->withInput()
                    ->withErrors([

                        'construction_work_order_id' =>
                            'The selected Work Order does not belong to this project.',

                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Progress → Project
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated[
                    'construction_progress_update_id'
                ]
            )
        ) {

            $progress =
                ConstructionProgressUpdate::query()
                    ->whereKey(
                        $validated[
                            'construction_progress_update_id'
                        ]
                    )
                    ->where(
                        'project_id',
                        $project->id
                    )
                    ->exists();

            if (!$progress) {

                return back()
                    ->withInput()
                    ->withErrors([

                        'construction_progress_update_id' =>
                            'The selected Progress Update does not belong to this project.',

                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Dates
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated['resolution_date']
            )
            &&
            $validated['resolution_date']
            <
            $validated['issue_date']
        ) {

            return back()
                ->withInput()
                ->withErrors([

                    'resolution_date' =>
                        'Resolution date cannot be earlier than issue date.',

                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Defaults
        |--------------------------------------------------------------------------
        */

        $validated['project_id'] =
            $project->id;

        $validated['raised_by'] =
            $validated['raised_by']
            ??
            auth()->id();

        $validated['created_by'] =
            auth()->id();

        $validated['updated_by'] =
            auth()->id();


        /*
        |--------------------------------------------------------------------------
        | Generate Unique Issue Number
        |--------------------------------------------------------------------------
        */

        $validated['issue_number'] =
            $this->generateIssueNumber();


        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        $issue =
            DB::transaction(
                function () use (
                    $validated
                ) {

                    return ConstructionSiteIssue::create(
                        $validated
                    );
                }
            );


        return redirect()
            ->route(
                'admin.projects.construction.site-issues.show',
                [
                    'project' =>
                        $project,

                    'issue' =>
                        $issue,
                ]
            )
            ->with(
                'success',
                'Site Issue / RFI '
                . $issue->issue_number
                . ' created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ConstructionSiteIssue $issue
    ): View {

        $this->validateIssueProject(
            $project,
            $issue
        );

        $issue->load([
            'workOrder.contract.bidder',
            'progress',
            'raisedBy',
            'assignedTo',
            'creator',
            'updater',
        ]);

        return view(
            'construction.site-issues.show',
            compact(
                'project',
                'issue'
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
        ConstructionSiteIssue $issue
    ): View {

        $this->validateIssueProject(
            $project,
            $issue
        );

        $workOrders = ConstructionWorkOrder::query()
            ->where(
                'project_id',
                $project->id
            )
            ->with([
                'contract.bidder',
            ])
            ->whereNotIn(
                'status',
                [
                    'Cancelled',
                ]
            )
            ->orderBy(
                'work_order_number'
            )
            ->get();

        $progressUpdates =
            ConstructionProgressUpdate::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->orderByDesc(
                    'progress_date'
                )
                ->orderByDesc('id')
                ->get();

        $users = User::query()
            ->orderBy('name')
            ->get();

        return view(
            'construction.site-issues.edit',
            compact(
                'project',
                'issue',
                'workOrders',
                'progressUpdates',
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
        ConstructionSiteIssue $issue
    ): RedirectResponse {

        $this->validateIssueProject(
            $project,
            $issue
        );

        $validated =
            $this->validateIssue(
                $request
            );


        /*
        |--------------------------------------------------------------------------
        | Work Order → Project
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated[
                    'construction_work_order_id'
                ]
            )
        ) {

            $workOrder =
                ConstructionWorkOrder::query()
                    ->whereKey(
                        $validated[
                            'construction_work_order_id'
                        ]
                    )
                    ->where(
                        'project_id',
                        $project->id
                    )
                    ->exists();

            if (!$workOrder) {

                return back()
                    ->withInput()
                    ->withErrors([

                        'construction_work_order_id' =>
                            'The selected Work Order does not belong to this project.',

                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Progress → Project
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated[
                    'construction_progress_update_id'
                ]
            )
        ) {

            $progress =
                ConstructionProgressUpdate::query()
                    ->whereKey(
                        $validated[
                            'construction_progress_update_id'
                        ]
                    )
                    ->where(
                        'project_id',
                        $project->id
                    )
                    ->exists();

            if (!$progress) {

                return back()
                    ->withInput()
                    ->withErrors([

                        'construction_progress_update_id' =>
                            'The selected Progress Update does not belong to this project.',

                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Dates
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated['resolution_date']
            )
            &&
            $validated['resolution_date']
            <
            $validated['issue_date']
        ) {

            return back()
                ->withInput()
                ->withErrors([

                    'resolution_date' =>
                        'Resolution date cannot be earlier than issue date.',

                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Do Not Change System Fields
        |--------------------------------------------------------------------------
        */

        unset(
            $validated['issue_number'],
            $validated['project_id'],
            $validated['created_by'],
            $validated['created_at']
        );


        /*
        |--------------------------------------------------------------------------
        | Automatic Resolution Date
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $validated['status'],
                [
                    'Resolved',
                    'Closed',
                ],
                true
            )
            &&
            empty(
                $validated['resolution_date']
            )
        ) {

            $validated['resolution_date'] =
                now()->toDateString();
        }


        if (
            !in_array(
                $validated['status'],
                [
                    'Resolved',
                    'Closed',
                ],
                true
            )
        ) {

            $validated['resolution_date'] =
                null;
        }


        $validated['updated_by'] =
            auth()->id();


        $issue->update(
            $validated
        );


        return redirect()
            ->route(
                'admin.projects.construction.site-issues.show',
                [
                    'project' =>
                        $project,

                    'issue' =>
                        $issue,
                ]
            )
            ->with(
                'success',
                'Site Issue / RFI updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ConstructionSiteIssue $issue
    ): RedirectResponse {

        $this->validateIssueProject(
            $project,
            $issue
        );

        $issueNumber =
            $issue->issue_number;

        $issue->delete();

        return redirect()
            ->route(
                'admin.projects.construction.site-issues.index',
                $project
            )
            ->with(
                'success',
                'Site Issue / RFI '
                . $issueNumber
                . ' deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    protected function validateIssue(
        Request $request
    ): array {

        return $request->validate([

            'construction_work_order_id' =>
                'nullable|integer',

            'construction_progress_update_id' =>
                'nullable|integer',

            'issue_date' =>
                'required|date',

            'issue_type' =>
                'required|string|max:100',

            'category' =>
                'nullable|string|max:100',

            'title' =>
                'required|string|max:255',

            'description' =>
                'nullable|string',

            'priority' =>
                'required|string|max:50',

            'raised_by' =>
                'nullable|integer|exists:users,id',

            'assigned_to' =>
                'nullable|integer|exists:users,id',

            'due_date' =>
                'nullable|date',

            'corrective_action' =>
                'nullable|string',

            'resolution' =>
                'nullable|string',

            'status' =>
                'required|string|max:50',

            'resolution_date' =>
                'nullable|date',

            'remarks' =>
                'nullable|string',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | PROJECT VALIDATION
    |--------------------------------------------------------------------------
    */

    protected function validateIssueProject(
        Project $project,
        ConstructionSiteIssue $issue
    ): void {

        if (
            (int) $issue->project_id
            !==
            (int) $project->id
        ) {

            abort(
                404,
                'Site Issue does not belong to this project.'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE NUMBER
    |--------------------------------------------------------------------------
    */

    protected function generateIssueNumber(): string
    {
        do {

            $number =
                'RFI-' .
                now()->format('Ymd') .
                '-' .
                strtoupper(
                    substr(
                        bin2hex(
                            random_bytes(3)
                        ),
                        0,
                        6
                    )
                );

        } while (
            ConstructionSiteIssue::query()
                ->where(
                    'issue_number',
                    $number
                )
                ->exists()
        );

        return $number;
    }
}