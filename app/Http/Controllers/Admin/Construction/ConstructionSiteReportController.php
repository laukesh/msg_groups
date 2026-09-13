<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionSiteReport;
use App\Models\ConstructionWorkOrder;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ConstructionSiteReportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Project $project): View
    {
        $reports =
            ConstructionSiteReport::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->with([
                    'workOrder',
                    'preparedBy',
                ])
                ->orderByDesc(
                    'report_date'
                )
                ->orderByDesc('id')
                ->get();


        $summary = [

            'total' =>
                $reports->count(),

            'draft' =>
                $reports
                    ->where(
                        'status',
                        'Draft'
                    )
                    ->count(),

            'submitted' =>
                $reports
                    ->where(
                        'status',
                        'Submitted'
                    )
                    ->count(),

            'approved' =>
                $reports
                    ->where(
                        'status',
                        'Approved'
                    )
                    ->count(),
        ];


        return view(
            'construction.site-reports.index',
            compact(
                'project',
                'reports',
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
        $workOrders =
            ConstructionWorkOrder::query()
                ->where(
                    'project_id',
                    $project->id
                )
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


        $users =
            User::query()
                ->orderBy('name')
                ->get();


        return view(
            'construction.site-reports.create',
            compact(
                'project',
                'workOrders',
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
            $this->validateReport(
                $request
            );


        /*
        |--------------------------------------------------------------------------
        | Work Order Validation
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated[
                    'construction_work_order_id'
                ]
            )
        ) {

            $workOrderExists =
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


            if (!$workOrderExists) {

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
        | Project
        |--------------------------------------------------------------------------
        */

        $validated['project_id'] =
            $project->id;


        /*
        |--------------------------------------------------------------------------
        | Prepared By
        |--------------------------------------------------------------------------
        */

        $validated['prepared_by'] =
            $validated['prepared_by']
            ??
            auth()->id();


        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        $validated['created_by'] =
            auth()->id();

        $validated['updated_by'] =
            auth()->id();


        /*
        |--------------------------------------------------------------------------
        | Generate Report Number
        |--------------------------------------------------------------------------
        */

        $validated['report_number'] =
            $this->generateReportNumber();


        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        $report =
            DB::transaction(
                function () use (
                    $validated
                ) {

                    return ConstructionSiteReport::create(
                        $validated
                    );
                }
            );


        return redirect()
            ->route(
                'admin.projects.construction.site-reports.show',
                [
                    'project' =>
                        $project,

                    'report' =>
                        $report,
                ]
            )
            ->with(
                'success',
                'Site Report '
                . $report->report_number
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
        ConstructionSiteReport $report
    ): View {

        $this->validateReportProject(
            $project,
            $report
        );


        $report->load([
            'workOrder',
            'preparedBy',
            'submittedBy',
            'approvedBy',
            'creator',
            'updater',
        ]);


        return view(
            'construction.site-reports.show',
            compact(
                'project',
                'report'
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
        ConstructionSiteReport $report
    ): View {

        $this->validateReportProject(
            $project,
            $report
        );


        if (!$report->canEdit()) {

            return redirect()
                ->route(
                    'admin.projects.construction.site-reports.show',
                    [
                        'project' =>
                            $project,

                        'report' =>
                            $report,
                    ]
                )
                ->with(
                    'error',
                    'This Site Report cannot be edited in its current status.'
                );
        }


        $workOrders =
            ConstructionWorkOrder::query()
                ->where(
                    'project_id',
                    $project->id
                )
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


        $users =
            User::query()
                ->orderBy('name')
                ->get();


        return view(
            'construction.site-reports.edit',
            compact(
                'project',
                'report',
                'workOrders',
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
        ConstructionSiteReport $report
    ): RedirectResponse {

        $this->validateReportProject(
            $project,
            $report
        );


        if (!$report->canEdit()) {

            return back()
                ->with(
                    'error',
                    'This Site Report cannot be edited in its current status.'
                );
        }


        $validated =
            $this->validateReport(
                $request
            );


        /*
        |--------------------------------------------------------------------------
        | Work Order Validation
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated[
                    'construction_work_order_id'
                ]
            )
        ) {

            $workOrderExists =
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


            if (!$workOrderExists) {

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
        | Never Change System Fields
        |--------------------------------------------------------------------------
        */

        unset(
            $validated['report_number'],
            $validated['project_id'],
            $validated['created_by'],
            $validated['created_at'],
            $validated['submitted_by'],
            $validated['submitted_at'],
            $validated['approved_by'],
            $validated['approved_at'],
            $validated['approval_remarks']
        );


        $validated['updated_by'] =
            auth()->id();


        $report->update(
            $validated
        );


        return redirect()
            ->route(
                'admin.projects.construction.site-reports.show',
                [
                    'project' =>
                        $project,

                    'report' =>
                        $report,
                ]
            )
            ->with(
                'success',
                'Site Report updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ConstructionSiteReport $report
    ): RedirectResponse {

        $this->validateReportProject(
            $project,
            $report
        );


        if (!$report->isDraft()) {

            return back()
                ->with(
                    'error',
                    'Only Draft Site Reports can be deleted.'
                );
        }


        $reportNumber =
            $report->report_number;


        $report->delete();


        return redirect()
            ->route(
                'admin.projects.construction.site-reports.index',
                $project
            )
            ->with(
                'success',
                'Site Report '
                . $reportNumber
                . ' deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SUBMIT
    |--------------------------------------------------------------------------
    */

    public function submit(
        Project $project,
        ConstructionSiteReport $report
    ): RedirectResponse {

        $this->validateReportProject(
            $project,
            $report
        );


        if (
            !in_array(
                $report->status,
                [
                    'Draft',
                    'Revision Required',
                ],
                true
            )
        ) {

            return back()
                ->with(
                    'error',
                    'This Site Report cannot be submitted in its current status.'
                );
        }


        $report->update([

            'status' =>
                'Submitted',

            'submitted_by' =>
                auth()->id(),

            'submitted_at' =>
                now(),

            'updated_by' =>
                auth()->id(),

        ]);


        return back()
            ->with(
                'success',
                'Site Report submitted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    protected function validateReport(
        Request $request
    ): array {

        return $request->validate([

            'construction_work_order_id' =>
                'nullable|integer',

            'report_date' =>
                'required|date',

            'report_type' =>
                'required|string|max:100',

            'prepared_by' =>
                'nullable|integer|exists:users,id',

            'weather_condition' =>
                'nullable|string|max:100',

            'temperature' =>
                'nullable|string|max:50',

            'site_condition' =>
                'nullable|string|max:255',

            'overall_progress' =>
                'required|numeric|min:0|max:100',

            'work_summary' =>
                'nullable|string',

            'activities_completed' =>
                'nullable|string',

            'activities_planned' =>
                'nullable|string',

            'manpower_summary' =>
                'nullable|string',

            'equipment_summary' =>
                'nullable|string',

            'material_summary' =>
                'nullable|string',

            'safety_observations' =>
                'nullable|string',

            'quality_observations' =>
                'nullable|string',

            'delays' =>
                'nullable|string',

            'issues' =>
                'nullable|string',

            'corrective_actions' =>
                'nullable|string',

            'visitors' =>
                'nullable|string',

            'instructions' =>
                'nullable|string',

            'remarks' =>
                'nullable|string',

            'status' =>
                'nullable|string|max:50',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | PROJECT VALIDATION
    |--------------------------------------------------------------------------
    */

    protected function validateReportProject(
        Project $project,
        ConstructionSiteReport $report
    ): void {

        if (
            (int) $report->project_id
            !==
            (int) $project->id
        ) {

            abort(
                404,
                'Site Report does not belong to this project.'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE REPORT NUMBER
    |--------------------------------------------------------------------------
    */

    protected function generateReportNumber(): string
    {
        do {

            $number =
                'DSR-' .
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
            ConstructionSiteReport::query()
                ->where(
                    'report_number',
                    $number
                )
                ->exists()
        );


        return $number;
    }
}