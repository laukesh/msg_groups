<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionQualityItp;
use App\Models\ConstructionQualityItpItem;
use App\Models\ConstructionWorkOrder;
use App\Models\Project;
use App\Models\ProcurementContract;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ConstructionQualityItpController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(Project $project): View
    {
        $itps = ConstructionQualityItp::query()
            ->where(
                'project_id',
                $project->id
            )
            ->with([
                'contract.bidder',
                'workOrder',
                'preparer',
                'items',
            ])
            ->orderByDesc('id')
            ->get();


        $summary = [

            'total' =>
                $itps->count(),

            'draft' =>
                $itps
                    ->where('status', 'Draft')
                    ->count(),

            'submitted' =>
                $itps
                    ->where('status', 'Submitted')
                    ->count(),

            'under_review' =>
                $itps
                    ->where('status', 'Under Review')
                    ->count(),

            'approved' =>
                $itps
                    ->where('status', 'Approved')
                    ->count(),

            'rejected' =>
                $itps
                    ->where('status', 'Rejected')
                    ->count(),
        ];


        return view(
            'construction.quality.itps.index',
            compact(
                'project',
                'itps',
                'summary'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(Project $project): View
    {
        /*
        |----------------------------------------------------------------------
        | Project Contracts
        |----------------------------------------------------------------------
        */

        $contracts =
            ProcurementContract::query()
                ->whereHas(
                    'tender',
                    function ($query) use ($project) {

                        $query->whereHas(
                            'package',
                            function ($query) use ($project) {

                                $query->whereHas(
                                    'procurementPlan',
                                    function ($query) use ($project) {

                                        $query->where(
                                            'project_id',
                                            $project->id
                                        );
                                    }
                                );
                            }
                        );
                    }
                )
                ->with([
                    'bidder',
                    'tender.package',
                ])
                ->whereIn(
                    'status',
                    [
                        'Approved',
                        'Active',
                        'In Progress',
                    ]
                )
                ->orderBy(
                    'contract_number'
                )
                ->get();


        /*
        |----------------------------------------------------------------------
        | Project Work Orders
        |----------------------------------------------------------------------
        */

        $workOrders =
			    ConstructionWorkOrder::query()
			        ->where(
			            'project_id',
			            $project->id
			        )
			        ->with([
			            'contract.bidder',
			        ])
			        ->orderBy(
			            'work_order_number'
			        )
			        ->get();


        /*
        |----------------------------------------------------------------------
        | Users
        |----------------------------------------------------------------------
        */

        $users = User::query()
            ->orderBy('name')
            ->get();


        return view(
            'construction.quality.itps.create',
            compact(
                'project',
                'contracts',
                'workOrders',
                'users'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Project $project
    ): RedirectResponse {

        $validated = $this->validateRequest(
            $request
        );


        /*
        |----------------------------------------------------------------------
        | Verify Contract
        |----------------------------------------------------------------------
        */

        $contract = null;

        if (
            !empty(
                $validated['procurement_contract_id']
            )
        ) {

            $contract =
                $this->findProjectContract(
                    $project,
                    $validated[
                        'procurement_contract_id'
                    ]
                );


            if (!$contract) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'procurement_contract_id' =>
                            'The selected contract does not belong to this project.',
                    ]);
            }
        }


        /*
        |----------------------------------------------------------------------
        | Verify Work Order
        |----------------------------------------------------------------------
        */

        $workOrder = null;

        if (
            !empty(
                $validated['work_order_id']
            )
        ) {

            $workOrder =
                ConstructionWorkOrder::query()
                    ->where(
                        'id',
                        $validated['work_order_id']
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
                        'work_order_id' =>
                            'The selected Work Order does not belong to this project.',
                    ]);
            }


            /*
            |------------------------------------------------------------------
            | Automatically use Work Order contract
            |------------------------------------------------------------------
            */

            if (
                !$contract
                &&
                $workOrder->procurement_contract_id
            ) {

                $contract =
                    $this->findProjectContract(
                        $project,
                        $workOrder->procurement_contract_id
                    );


                if ($contract) {

                    $validated[
                        'procurement_contract_id'
                    ] =
                        $contract->id;
                }
            }
        }


        /*
        |----------------------------------------------------------------------
        | Create ITP
        |----------------------------------------------------------------------
        */

        $itp = DB::transaction(
            function () use (
                $validated,
                $project
            ) {

                $itpNumber =
                    $this->generateItpNumber(
                        $project
                    );


                return ConstructionQualityItp::create([

                    'project_id' =>
                        $project->id,

                    'itp_number' =>
                        $itpNumber,

                    'title' =>
                        $validated['title'],

                    'itp_type' =>
                        $validated['itp_type']
                        ?? null,

                    'description' =>
                        $validated['description']
                        ?? null,

                    'procurement_contract_id' =>
                        $validated[
                            'procurement_contract_id'
                        ]
                        ?? null,

                    'work_order_id' =>
                        $validated[
                            'work_order_id'
                        ]
                        ?? null,

                    'prepared_by' =>
                        $validated['prepared_by']
                        ?? auth()->id(),

                    'prepared_date' =>
                        $validated['prepared_date']
                        ?? now()->toDateString(),

                    'status' =>
                        'Draft',

                    'remarks' =>
                        $validated['remarks']
                        ?? null,

                    'created_by' =>
                        auth()->id(),

                    'updated_by' =>
                        auth()->id(),
                ]);
            }
        );


        return redirect()
            ->route(
                'admin.projects.construction.quality.itps.show',
                [
                    'project' =>
                        $project,

                    'itp' =>
                        $itp,
                ]
            )
            ->with(
                'success',
                'ITP '
                . $itp->itp_number
                . ' created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ConstructionQualityItp $itp
    ): View {

        $this->ensureProjectItp(
            $project,
            $itp
        );


        $itp->load([
            'contract.bidder',
            'workOrder',
            'preparer',
            'approver',
            'creator',
            'updater',
            'items',
        ]);


        return view(
            'construction.quality.itps.show',
            compact(
                'project',
                'itp'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(
        Project $project,
        ConstructionQualityItp $itp
    ): View {

        $this->ensureProjectItp(
            $project,
            $itp
        );


        if (
            !in_array(
                $itp->status,
                [
                    'Draft',
                    'Rejected',
                ],
                true
            )
        ) {

            abort(
                403,
                'Only Draft or Rejected ITPs can be edited.'
            );
        }


        $contracts =
            ProcurementContract::query()
                ->whereHas(
                    'tender',
                    function ($query) use ($project) {

                        $query->whereHas(
                            'package',
                            function ($query) use ($project) {

                                $query->whereHas(
                                    'procurementPlan',
                                    function ($query) use ($project) {

                                        $query->where(
                                            'project_id',
                                            $project->id
                                        );
                                    }
                                );
                            }
                        );
                    }
                )
                ->with('bidder')
                ->whereIn(
                    'status',
                    [
                        'Approved',
                        'Active',
                        'In Progress',
                    ]
                )
                ->orderBy('contract_number')
                ->get();


        $workOrders =
            ConstructionWorkOrder::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->with('contract.bidder')
                ->orderBy(
                    'work_order_number'
                )
                ->get();


        $users = User::query()
            ->orderBy('name')
            ->get();


        $itp->load('items');


        return view(
            'construction.quality.itps.edit',
            compact(
                'project',
                'itp',
                'contracts',
                'workOrders',
                'users'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Project $project,
        ConstructionQualityItp $itp
    ): RedirectResponse {

        $this->ensureProjectItp(
            $project,
            $itp
        );


        if (
            !in_array(
                $itp->status,
                [
                    'Draft',
                    'Rejected',
                ],
                true
            )
        ) {

            abort(
                403,
                'Only Draft or Rejected ITPs can be edited.'
            );
        }


        $validated =
            $this->validateRequest(
                $request
            );


        /*
        |----------------------------------------------------------------------
        | Validate Contract
        |----------------------------------------------------------------------
        */

        if (
            !empty(
                $validated[
                    'procurement_contract_id'
                ]
            )
        ) {

            if (
                !$this->findProjectContract(
                    $project,
                    $validated[
                        'procurement_contract_id'
                    ]
                )
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'procurement_contract_id' =>
                            'The selected contract does not belong to this project.',
                    ]);
            }
        }


        /*
        |----------------------------------------------------------------------
        | Validate Work Order
        |----------------------------------------------------------------------
        */

        if (
            !empty(
                $validated['work_order_id']
            )
        ) {

            $workOrder =
                ConstructionWorkOrder::query()
                    ->where(
                        'id',
                        $validated['work_order_id']
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
                        'work_order_id' =>
                            'The selected Work Order does not belong to this project.',
                    ]);
            }
        }


        $validated['updated_by'] =
            auth()->id();


        $itp->update(
            $validated
        );


        return redirect()
            ->route(
                'admin.projects.construction.quality.itps.show',
                [
                    'project' =>
                        $project,

                    'itp' =>
                        $itp,
                ]
            )
            ->with(
                'success',
                'ITP updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ConstructionQualityItp $itp
    ): RedirectResponse {

        $this->ensureProjectItp(
            $project,
            $itp
        );


        if (
            !in_array(
                $itp->status,
                [
                    'Draft',
                    'Rejected',
                ],
                true
            )
        ) {

            return back()
                ->withErrors([
                    'status' =>
                        'Only Draft or Rejected ITPs can be deleted.',
                ]);
        }


        $itp->delete();


        return redirect()
            ->route(
                'admin.projects.construction.quality.itps.index',
                $project
            )
            ->with(
                'success',
                'ITP deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Submit
    |--------------------------------------------------------------------------
    */

    public function submit(
        Project $project,
        ConstructionQualityItp $itp
    ): RedirectResponse {

        $this->ensureProjectItp(
            $project,
            $itp
        );


        if (
            $itp->status !== 'Draft'
        ) {

            return back()
                ->withErrors([
                    'status' =>
                        'Only Draft ITPs can be submitted.',
                ]);
        }


        if (
            !$itp->items()->exists()
        ) {

            return back()
                ->withErrors([
                    'items' =>
                        'At least one ITP item is required before submission.',
                ]);
        }


        $itp->update([

            'status' =>
                'Submitted',

            'updated_by' =>
                auth()->id(),

        ]);


        return back()
            ->with(
                'success',
                'ITP submitted for review.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Start Review
    |--------------------------------------------------------------------------
    */

    public function startReview(
        Project $project,
        ConstructionQualityItp $itp
    ): RedirectResponse {

        $this->ensureProjectItp(
            $project,
            $itp
        );


        if (
            $itp->status !== 'Submitted'
        ) {

            return back()
                ->withErrors([
                    'status' =>
                        'Only submitted ITPs can be moved to review.',
                ]);
        }


        $itp->update([

            'status' =>
                'Under Review',

            'updated_by' =>
                auth()->id(),

        ]);


        return back()
            ->with(
                'success',
                'ITP review started.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Approve
    |--------------------------------------------------------------------------
    */

    public function approve(
        Project $project,
        ConstructionQualityItp $itp
    ): RedirectResponse {

        $this->ensureProjectItp(
            $project,
            $itp
        );


        if (
            $itp->status !== 'Under Review'
        ) {

            return back()
                ->withErrors([
                    'status' =>
                        'Only ITPs under review can be approved.',
                ]);
        }


        $itp->update([

            'status' =>
                'Approved',

            'approved_by' =>
                auth()->id(),

            'approved_date' =>
                now()->toDateString(),

            'updated_by' =>
                auth()->id(),

        ]);


        return back()
            ->with(
                'success',
                'ITP approved successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Reject
    |--------------------------------------------------------------------------
    */

    public function reject(
        Request $request,
        Project $project,
        ConstructionQualityItp $itp
    ): RedirectResponse {

        $this->ensureProjectItp(
            $project,
            $itp
        );


        if (
            $itp->status !== 'Under Review'
        ) {

            return back()
                ->withErrors([
                    'status' =>
                        'Only ITPs under review can be rejected.',
                ]);
        }


        $validated = $request->validate([

            'approval_remarks' =>
                'required|string',

        ]);


        $itp->update([

            'status' =>
                'Rejected',

            'approval_remarks' =>
                $validated[
                    'approval_remarks'
                ],

            'updated_by' =>
                auth()->id(),

        ]);


        return back()
            ->with(
                'success',
                'ITP rejected and returned for revision.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Add Item
    |--------------------------------------------------------------------------
    */

    public function addItem(
        Request $request,
        Project $project,
        ConstructionQualityItp $itp
    ): RedirectResponse {

        $this->ensureProjectItp(
            $project,
            $itp
        );


        if (
            !in_array(
                $itp->status,
                [
                    'Draft',
                    'Rejected',
                ],
                true
            )
        ) {

            return back()
                ->withErrors([
                    'status' =>
                        'Items can only be added to Draft or Rejected ITPs.',
                ]);
        }


        $validated = $request->validate([

            'activity' =>
                'required|string|max:255',

            'inspection_test' =>
                'required|string|max:255',

            'stage' =>
                'nullable|string|max:100',

            'acceptance_criteria' =>
                'nullable|string',

            'reference_standard' =>
                'nullable|string|max:255',

            'inspection_type' =>
                'nullable|string|max:100',

            'responsible_party' =>
                'nullable|string|max:100',

            'hold_point' =>
                'nullable|boolean',

            'witness_point' =>
                'nullable|boolean',

            'required' =>
                'nullable|boolean',

            'remarks' =>
                'nullable|string',

        ]);


        $nextItemNumber =
            (
                $itp->items()->max(
                    'item_number'
                )
                ?? 0
            ) + 1;


        ConstructionQualityItpItem::create([

            'construction_quality_itp_id' =>
                $itp->id,

            'item_number' =>
                $nextItemNumber,

            'activity' =>
                $validated['activity'],

            'inspection_test' =>
                $validated['inspection_test'],

            'stage' =>
                $validated['stage']
                ?? null,

            'acceptance_criteria' =>
                $validated['acceptance_criteria']
                ?? null,

            'reference_standard' =>
                $validated['reference_standard']
                ?? null,

            'inspection_type' =>
                $validated['inspection_type']
                ?? null,

            'responsible_party' =>
                $validated['responsible_party']
                ?? null,

            'hold_point' =>
                $validated['hold_point']
                ?? false,

            'witness_point' =>
                $validated['witness_point']
                ?? false,

            'required' =>
                $validated['required']
                ?? true,

            'remarks' =>
                $validated['remarks']
                ?? null,

        ]);


        return back()
            ->with(
                'success',
                'ITP item added successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Item
    |--------------------------------------------------------------------------
    */

    public function updateItem(
        Request $request,
        Project $project,
        ConstructionQualityItp $itp,
        ConstructionQualityItpItem $item
    ): RedirectResponse {

        $this->ensureProjectItp(
            $project,
            $itp
        );


        if (
            $item->construction_quality_itp_id
            !==
            $itp->id
        ) {

            abort(404);
        }


        if (
            !in_array(
                $itp->status,
                [
                    'Draft',
                    'Rejected',
                ],
                true
            )
        ) {

            return back()
                ->withErrors([
                    'status' =>
                        'Items can only be edited in Draft or Rejected ITPs.',
                ]);
        }


        $validated = $request->validate([

            'activity' =>
                'required|string|max:255',

            'inspection_test' =>
                'required|string|max:255',

            'stage' =>
                'nullable|string|max:100',

            'acceptance_criteria' =>
                'nullable|string',

            'reference_standard' =>
                'nullable|string|max:255',

            'inspection_type' =>
                'nullable|string|max:100',

            'responsible_party' =>
                'nullable|string|max:100',

            'hold_point' =>
                'nullable|boolean',

            'witness_point' =>
                'nullable|boolean',

            'required' =>
                'nullable|boolean',

            'remarks' =>
                'nullable|string',

        ]);


        $item->update([

            'activity' =>
                $validated['activity'],

            'inspection_test' =>
                $validated['inspection_test'],

            'stage' =>
                $validated['stage']
                ?? null,

            'acceptance_criteria' =>
                $validated['acceptance_criteria']
                ?? null,

            'reference_standard' =>
                $validated['reference_standard']
                ?? null,

            'inspection_type' =>
                $validated['inspection_type']
                ?? null,

            'responsible_party' =>
                $validated['responsible_party']
                ?? null,

            'hold_point' =>
                $validated['hold_point']
                ?? false,

            'witness_point' =>
                $validated['witness_point']
                ?? false,

            'required' =>
                $validated['required']
                ?? true,

            'remarks' =>
                $validated['remarks']
                ?? null,

        ]);


        return back()
            ->with(
                'success',
                'ITP item updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Item
    |--------------------------------------------------------------------------
    */

    public function deleteItem(
        Project $project,
        ConstructionQualityItp $itp,
        ConstructionQualityItpItem $item
    ): RedirectResponse {

        $this->ensureProjectItp(
            $project,
            $itp
        );


        if (
            $item->construction_quality_itp_id
            !==
            $itp->id
        ) {

            abort(404);
        }


        if (
            !in_array(
                $itp->status,
                [
                    'Draft',
                    'Rejected',
                ],
                true
            )
        ) {

            return back()
                ->withErrors([
                    'status' =>
                        'Items can only be deleted from Draft or Rejected ITPs.',
                ]);
        }


        $item->delete();


        return back()
            ->with(
                'success',
                'ITP item deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    private function validateRequest(
        Request $request
    ): array {

        return $request->validate([

            'title' =>
                'required|string|max:255',

            'itp_type' =>
                'nullable|string|max:100',

            'description' =>
                'nullable|string',

            'procurement_contract_id' =>
                'nullable|integer',

            'work_order_id' =>
                'nullable|integer',

            'prepared_by' =>
                'nullable|integer|exists:users,id',

            'prepared_date' =>
                'nullable|date',

            'remarks' =>
                'nullable|string',

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Find Project Contract
    |--------------------------------------------------------------------------
    */

    private function findProjectContract(
        Project $project,
        int $contractId
    ): ?ProcurementContract {

        return ProcurementContract::query()
            ->whereKey(
                $contractId
            )
            ->whereHas(
                'tender',
                function ($query) use ($project) {

                    $query->whereHas(
                        'package',
                        function ($query) use ($project) {

                            $query->whereHas(
                                'procurementPlan',
                                function ($query) use ($project) {

                                    $query->where(
                                        'project_id',
                                        $project->id
                                    );
                                }
                            );
                        }
                    );
                }
            )
            ->first();
    }


    /*
    |--------------------------------------------------------------------------
    | Ensure Project ITP
    |--------------------------------------------------------------------------
    */

    private function ensureProjectItp(
        Project $project,
        ConstructionQualityItp $itp
    ): void {

        if (
            $itp->project_id !==
            $project->id
        ) {

            abort(
                404,
                'ITP does not belong to this project.'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Generate ITP Number
    |--------------------------------------------------------------------------
    */

    private function generateItpNumber(
        Project $project
    ): string {

        do {

            $number =
                'ITP-'
                . str_pad(
                    (string) $project->id,
                    4,
                    '0',
                    STR_PAD_LEFT
                )
                . '-'
                . str_pad(
                    (string) (
                        ConstructionQualityItp::query()
                            ->where(
                                'project_id',
                                $project->id
                            )
                            ->count()
                        + 1
                    ),
                    4,
                    '0',
                    STR_PAD_LEFT
                );

        } while (
            ConstructionQualityItp::query()
                ->where(
                    'itp_number',
                    $number
                )
                ->exists()
        );


        return $number;
    }
}