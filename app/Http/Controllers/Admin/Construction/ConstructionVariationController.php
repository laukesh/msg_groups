<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionVariation;
use App\Models\ConstructionWorkOrder;
use App\Models\ProcurementContract;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ConstructionVariationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Project $project): View
    {
        $variations = ConstructionVariation::query()
            ->where('project_id', $project->id)
            ->with([
                'workOrder',
                'contract',
            ])
            ->orderByDesc('variation_date')
            ->orderByDesc('id')
            ->get();

        $summary = [
            'total' => $variations->count(),

            'approved' => $variations
                ->where('status', 'Approved')
                ->sum('amount'),

            'submitted' => $variations
                ->where('status', 'Submitted')
                ->sum('amount'),

            'draft' => $variations
                ->where('status', 'Draft')
                ->sum('amount'),

            'rejected' => $variations
                ->where('status', 'Rejected')
                ->sum('amount'),
        ];

        return view(
            'construction.variations.index',
            compact(
                'project',
                'variations',
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
            ->where('project_id', $project->id)
            ->orderBy('work_order_number')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Contracts belonging to this project
        |--------------------------------------------------------------------------
        */

        $contracts = ProcurementContract::query()
            ->with([
                'tender.package',
            ])
            ->whereHas(
                'tender.package',
                function ($query) use ($project) {
                    $query->where(
                        'project_id',
                        $project->id
                    );
                }
            )
            ->orderBy('contract_number')
            ->get();


        return view(
            'construction.variations.create',
            compact(
                'project',
                'workOrders',
                'contracts'
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

        $validated = $request->validate([

            'construction_work_order_id' =>
                'nullable|integer',

            'procurement_contract_id' =>
                'nullable|integer',

            'variation_date' =>
                'required|date',

            'variation_type' =>
                'required|string|max:100',

            'title' =>
                'required|string|max:255',

            'description' =>
                'nullable|string',

            'reason' =>
                'nullable|string',

            'amount' =>
                'required|numeric|min:0.01',

            'currency' =>
                'required|string|max:10',

            'status' =>
                    'nullable',

            'remarks' =>
                'nullable|string',
        ]);

        $validated['status'] = 'Draft';


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
        | Validate Contract
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated[
                    'procurement_contract_id'
                ]
            )
        ) {

            $contractExists =
                ProcurementContract::query()
                    ->whereKey(
                        $validated[
                            'procurement_contract_id'
                        ]
                    )
                    ->whereHas(
                        'tender.package',
                        function ($query) use ($project) {

                            $query->where(
                                'project_id',
                                $project->id
                            );
                        }
                    )
                    ->exists();


            if (!$contractExists) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'procurement_contract_id' =>
                            'The selected Contract does not belong to this project.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Generate Variation Number
        |--------------------------------------------------------------------------
        */

        $validated['variation_number'] =
            $this->generateVariationNumber();


        $validated['project_id'] =
            $project->id;


        $validated['created_by'] =
            auth()->id();

        $validated['updated_by'] =
            auth()->id();


        /*
        |--------------------------------------------------------------------------
        | Approval Fields
        |--------------------------------------------------------------------------
        */

        if (
            $validated['status'] === 'Submitted'
        ) {

            $validated['submitted_at'] =
                now();

            $validated['submitted_by'] =
                auth()->id();
        }


        if (
            $validated['status'] === 'Approved'
        ) {

            $validated['submitted_at'] =
                now();

            $validated['submitted_by'] =
                auth()->id();

            $validated['approved_at'] =
                now();

            $validated['approved_by'] =
                auth()->id();
        }


        if (
            $validated['status'] === 'Rejected'
        ) {

            $validated['rejected_at'] =
                now();

            $validated['rejected_by'] =
                auth()->id();
        }


        $variation =
            DB::transaction(
                function () use ($validated) {

                    return ConstructionVariation::create(
                        $validated
                    );
                }
            );


        return redirect()
            ->route(
                'admin.projects.construction.variations.show',
                [
                    'project' =>
                        $project,

                    'variation' =>
                        $variation,
                ]
            )
            ->with(
                'success',
                'Variation '
                . $variation->variation_number
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
        ConstructionVariation $variation
    ): View {

        $this->validateProject(
            $project,
            $variation
        );


        $variation->load([
            'workOrder',
            'contract',
        ]);


        return view(
            'construction.variations.show',
            compact(
                'project',
                'variation'
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
        ConstructionVariation $variation
    ): View {

        $this->validateProject(
            $project,
            $variation
        );


        if (
            !in_array(
                $variation->status,
                [
                    'Draft',
                    'Rejected',
                ],
                true
            )
        ) {

            return redirect()
                ->route(
                    'admin.projects.construction.variations.show',
                    [
                        'project' =>
                            $project,

                        'variation' =>
                            $variation,
                    ]
                )
                ->with(
                    'error',
                    'Only Draft or Rejected variations can be edited.'
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


        $contracts =
            ProcurementContract::query()
                ->with([
                    'tender.package',
                ])
                ->whereHas(
                    'tender.package',
                    function ($query) use ($project) {

                        $query->where(
                            'project_id',
                            $project->id
                        );
                    }
                )
                ->orderBy(
                    'contract_number'
                )
                ->get();


        return view(
            'construction.variations.edit',
            compact(
                'project',
                'variation',
                'workOrders',
                'contracts'
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
        ConstructionVariation $variation
    ): RedirectResponse {

        $this->validateProject(
            $project,
            $variation
        );


        if (
            !in_array(
                $variation->status,
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
                    'Only Draft or Rejected variations can be edited.'
                );
        }


        $validated = $request->validate([

            'construction_work_order_id' =>
                'nullable|integer',

            'procurement_contract_id' =>
                'nullable|integer',

            'variation_date' =>
                'required|date',

            'variation_type' =>
                'required|string|max:100',

            'title' =>
                'required|string|max:255',

            'description' =>
                'nullable|string',

            'reason' =>
                'nullable|string',

            'amount' =>
                'required|numeric|min:0.01',

            'currency' =>
                'required|string|max:10',

            'status' =>'nullable',

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
        | Validate Contract
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated[
                    'procurement_contract_id'
                ]
            )
        ) {

            $contractExists =
                ProcurementContract::query()
                    ->whereKey(
                        $validated[
                            'procurement_contract_id'
                        ]
                    )
                    ->whereHas(
                        'tender.package',
                        function ($query) use ($project) {

                            $query->where(
                                'project_id',
                                $project->id
                            );
                        }
                    )
                    ->exists();


            if (!$contractExists) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'procurement_contract_id' =>
                            'The selected Contract does not belong to this project.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Reset Approval Information
        |--------------------------------------------------------------------------
        */

        $validated['submitted_at'] = null;
        $validated['submitted_by'] = null;

        $validated['approved_at'] = null;
        $validated['approved_by'] = null;

        $validated['rejected_at'] = null;
        $validated['rejected_by'] = null;

        $validated['rejection_remarks'] = null;


        /*
        |--------------------------------------------------------------------------
        | Status Information
        |--------------------------------------------------------------------------
        */

        if (
            $validated['status'] === 'Submitted'
        ) {

            $validated['submitted_at'] =
                now();

            $validated['submitted_by'] =
                auth()->id();
        }


        if (
            $validated['status'] === 'Approved'
        ) {

            $validated['submitted_at'] =
                now();

            $validated['submitted_by'] =
                auth()->id();

            $validated['approved_at'] =
                now();

            $validated['approved_by'] =
                auth()->id();
        }


        if (
            $validated['status'] === 'Rejected'
        ) {

            $validated['rejected_at'] =
                now();

            $validated['rejected_by'] =
                auth()->id();
        }


        unset(
            $validated['variation_number'],
            $validated['project_id'],
            $validated['created_by'],
            $validated['created_at']
        );


        $validated['updated_by'] =
            auth()->id();
        $validated['status'] = $variation->status;

        $variation->update(
            $validated
        );


        return redirect()
            ->route(
                'admin.projects.construction.variations.show',
                [
                    'project' =>
                        $project,

                    'variation' =>
                        $variation,
                ]
            )
            ->with(
                'success',
                'Variation updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ConstructionVariation $variation
    ): RedirectResponse {

        $this->validateProject(
            $project,
            $variation
        );


        if (
            !in_array(
                $variation->status,
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
                    'Only Draft or Rejected variations can be deleted.'
                );
        }


        $number =
            $variation->variation_number;


        $variation->delete();


        return redirect()
            ->route(
                'admin.projects.construction.variations.index',
                $project
            )
            ->with(
                'success',
                'Variation '
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
        ConstructionVariation $variation
    ): void {

        if (
            (int) $variation->project_id
            !==
            (int) $project->id
        ) {

            abort(
                404,
                'Variation does not belong to this project.'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | NUMBER GENERATOR
    |--------------------------------------------------------------------------
    */

    protected function generateVariationNumber(): string
    {
        do {

            $number =
                'VAR-'
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
            ConstructionVariation::query()
                ->where(
                    'variation_number',
                    $number
                )
                ->exists()
        );


        return $number;
    }

    /*
    |--------------------------------------------------------------------------
    | SUBMIT
    |--------------------------------------------------------------------------
    */

    public function submit(
        Project $project,
        ConstructionVariation $variation
    ): RedirectResponse {

        $this->validateProject(
            $project,
            $variation
        );


        if ($variation->status !== 'Draft') {

            return back()
                ->with(
                    'error',
                    'Only Draft variations can be submitted.'
                );
        }


        $variation->update([

            'status' =>
                'Submitted',

            'submitted_at' =>
                now(),

            'submitted_by' =>
                auth()->id(),

            'rejected_at' =>
                null,

            'rejected_by' =>
                null,

            'rejection_remarks' =>
                null,

            'updated_by' =>
                auth()->id(),

        ]);


        return back()
            ->with(
                'success',
                'Variation submitted for approval successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | APPROVE
    |--------------------------------------------------------------------------
    */

    public function approve(
        Project $project,
        ConstructionVariation $variation
    ): RedirectResponse {

        $this->validateProject(
            $project,
            $variation
        );


        if ($variation->status !== 'Submitted') {

            return back()
                ->with(
                    'error',
                    'Only Submitted variations can be approved.'
                );
        }


        $variation->update([

            'status' =>
                'Approved',

            'approved_at' =>
                now(),

            'approved_by' =>
                auth()->id(),

            'rejected_at' =>
                null,

            'rejected_by' =>
                null,

            'rejection_remarks' =>
                null,

            'updated_by' =>
                auth()->id(),

        ]);


        return back()
            ->with(
                'success',
                'Variation approved successfully.'
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
        ConstructionVariation $variation
    ): RedirectResponse {

        $this->validateProject(
            $project,
            $variation
        );


        if ($variation->status !== 'Submitted') {

            return back()
                ->with(
                    'error',
                    'Only Submitted variations can be rejected.'
                );
        }


        $validated = $request->validate([

            'rejection_remarks' =>
                'required|string|max:5000',

        ]);


        $variation->update([

            'status' =>
                'Rejected',

            'rejected_at' =>
                now(),

            'rejected_by' =>
                auth()->id(),

            'rejection_remarks' =>
                $validated['rejection_remarks'],

            'approved_at' =>
                null,

            'approved_by' =>
                null,

            'updated_by' =>
                auth()->id(),

        ]);


        return back()
            ->with(
                'success',
                'Variation rejected successfully.'
            );
    }

}