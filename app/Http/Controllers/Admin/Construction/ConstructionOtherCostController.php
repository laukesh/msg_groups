<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionOtherCost;
use App\Models\ConstructionWorkOrder;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ConstructionOtherCostController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        Project $project
    ): View {

        $costs =
            ConstructionOtherCost::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->with([
                    'workOrder',
                ])
                ->orderByDesc(
                    'cost_date'
                )
                ->orderByDesc('id')
                ->get();


        $summary = [

            'total' =>
                $costs->count(),

            'approved' =>
                $costs
                    ->where(
                        'status',
                        'Approved'
                    )
                    ->sum('amount'),

            'draft' =>
                $costs
                    ->where(
                        'status',
                        'Draft'
                    )
                    ->sum('amount'),

            'rejected' =>
                $costs
                    ->where(
                        'status',
                        'Rejected'
                    )
                    ->sum('amount'),
        ];


        return view(
            'construction.other-costs.index',
            compact(
                'project',
                'costs',
                'summary'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
        Project $project
    ): View {

        $workOrders =
            ConstructionWorkOrder::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->orderBy(
                    'work_order_number'
                )
                ->get();


        return view(
            'construction.other-costs.create',
            compact(
                'project',
                'workOrders'
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
            $request->validate([

                'construction_work_order_id' =>
                    'nullable|integer',

                'cost_date' =>
                    'required|date',

                'cost_type' =>
                    'required|string|max:100',

                'description' =>
                    'nullable|string',

                'amount' =>
                    'required|numeric|min:0.01',

                'currency' =>
                    'required|string|max:10',

                'status' =>
                    'required|in:Draft,Submitted,Approved,Rejected',

                'remarks' =>
                    'nullable|string',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Validate Work Order
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
                    ->first();


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
        | Generate Cost Number
        |--------------------------------------------------------------------------
        */

        $validated['cost_number'] =
            $this->generateCostNumber();


        $validated['project_id'] =
            $project->id;


        $validated['created_by'] =
            auth()->id();

        $validated['updated_by'] =
            auth()->id();


        $cost =
            DB::transaction(
                function () use ($validated) {

                    return
                        ConstructionOtherCost::create(
                            $validated
                        );
                }
            );


        return redirect()
            ->route(
                'admin.projects.construction.other-costs.show',
                [
                    'project' =>
                        $project,

                    'cost' =>
                        $cost,
                ]
            )
            ->with(
                'success',
                'Other Cost '
                . $cost->cost_number
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
        ConstructionOtherCost $cost
    ): View {

        $this->validateProject(
            $project,
            $cost
        );


        $cost->load([
            'workOrder',
        ]);


        return view(
            'construction.other-costs.show',
            compact(
                'project',
                'cost'
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
        ConstructionOtherCost $cost
    ): View {

        $this->validateProject(
            $project,
            $cost
        );


        if (
            !in_array(
                $cost->status,
                [
                    'Draft',
                    'Rejected',
                ],
                true
            )
        ) {

            return redirect()
                ->route(
                    'admin.projects.construction.other-costs.show',
                    [
                        'project' =>
                            $project,

                        'cost' =>
                            $cost,
                    ]
                )
                ->with(
                    'error',
                    'Only Draft or Rejected costs can be edited.'
                );
        }


        $workOrders =
            ConstructionWorkOrder::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->orderBy(
                    'work_order_number'
                )
                ->get();


        return view(
            'construction.other-costs.edit',
            compact(
                'project',
                'cost',
                'workOrders'
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
        ConstructionOtherCost $cost
    ): RedirectResponse {

        $this->validateProject(
            $project,
            $cost
        );


        if (
            !in_array(
                $cost->status,
                [
                    'Draft',
                    'Rejected',
                ],
                true
            )
        ) {

            return back()
                ->with(
                    'error',
                    'Only Draft or Rejected costs can be edited.'
                );
        }


        $validated =
            $request->validate([

                'construction_work_order_id' =>
                    'nullable|integer',

                'cost_date' =>
                    'required|date',

                'cost_type' =>
                    'required|string|max:100',

                'description' =>
                    'nullable|string',

                'amount' =>
                    'required|numeric|min:0.01',

                'currency' =>
                    'required|string|max:10',

                'status' =>
                    'required|in:Draft,Submitted,Approved,Rejected',

                'remarks' =>
                    'nullable|string',
            ]);


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


        unset(
            $validated['cost_number'],
            $validated['project_id'],
            $validated['created_by'],
            $validated['created_at']
        );


        $validated['updated_by'] =
            auth()->id();


        $cost->update(
            $validated
        );


        return redirect()
            ->route(
                'admin.projects.construction.other-costs.show',
                [
                    'project' =>
                        $project,

                    'cost' =>
                        $cost,
                ]
            )
            ->with(
                'success',
                'Other Cost updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ConstructionOtherCost $cost
    ): RedirectResponse {

        $this->validateProject(
            $project,
            $cost
        );


        if (
            !in_array(
                $cost->status,
                [
                    'Draft',
                    'Rejected',
                ],
                true
            )
        ) {

            return back()
                ->with(
                    'error',
                    'Only Draft or Rejected costs can be deleted.'
                );
        }


        $number =
            $cost->cost_number;


        $cost->delete();


        return redirect()
            ->route(
                'admin.projects.construction.other-costs.index',
                $project
            )
            ->with(
                'success',
                'Other Cost '
                . $number
                . ' deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PROJECT VALIDATION
    |--------------------------------------------------------------------------
    */

    protected function validateProject(
        Project $project,
        ConstructionOtherCost $cost
    ): void {

        if (
            (int) $cost->project_id
            !==
            (int) $project->id
        ) {

            abort(
                404,
                'Other Cost does not belong to this project.'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | NUMBER GENERATOR
    |--------------------------------------------------------------------------
    */

    protected function generateCostNumber(): string
    {
        do {

            $number =
                'OCOST-'
                . now()->format('Ymd')
                . '-'
                . strtoupper(
                    substr(
                        bin2hex(
                            random_bytes(3)
                        ),
                        0,
                        6
                    )
                );

        } while (
            ConstructionOtherCost::query()
                ->where(
                    'cost_number',
                    $number
                )
                ->exists()
        );


        return $number;
    }
}