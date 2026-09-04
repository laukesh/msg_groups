<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionInspection;
use App\Models\ConstructionQualityItp;
use App\Models\ConstructionQualityItpItem;
use App\Models\ConstructionQualityNcr;
use App\Models\ConstructionWorkOrder;
use App\Models\ProcurementContract;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ConstructionQualityNcrController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(Project $project): View
    {
        $ncrs = ConstructionQualityNcr::query()
            ->where(
                'project_id',
                $project->id
            )
            ->with([
                'contract.bidder',
                'workOrder',
                'itp',
                'inspection',
                'raisedBy',
            ])
            ->orderByDesc('id')
            ->get();


        $summary = [

            'total' =>
                $ncrs->count(),

            'open' =>
                $ncrs
                    ->where('status', 'Open')
                    ->count(),

            'submitted' =>
                $ncrs
                    ->where('status', 'Submitted')
                    ->count(),

            'under_review' =>
                $ncrs
                    ->where('status', 'Under Review')
                    ->count(),

            'corrective_action' =>
                $ncrs
                    ->where('status', 'Corrective Action Required')
                    ->count(),

            'verification' =>
                $ncrs
                    ->where('status', 'Verification')
                    ->count(),

            'closed' =>
                $ncrs
                    ->where('status', 'Closed')
                    ->count(),

            'critical' =>
                $ncrs
                    ->where('severity', 'Critical')
                    ->whereNotIn(
                        'status',
                        ['Closed']
                    )
                    ->count(),
        ];


        return view(
            'construction.quality.ncrs.index',
            compact(
                'project',
                'ncrs',
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
        |--------------------------------------------------------------------------
        | Project Contracts
        |--------------------------------------------------------------------------
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
                ->orderBy(
                    'contract_number'
                )
                ->get();


        /*
        |--------------------------------------------------------------------------
        | Project Work Orders
        |--------------------------------------------------------------------------
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
        |--------------------------------------------------------------------------
        | Approved / Relevant ITPs
        |--------------------------------------------------------------------------
        */

        $itps =
            ConstructionQualityItp::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->with([
                    'items',
                ])
                ->orderBy(
                    'itp_number'
                )
                ->get();


        /*
        |--------------------------------------------------------------------------
        | Project Inspections
        |--------------------------------------------------------------------------
        |
        | We use the existing Inspection module.
        |
        */

        $inspections =
            ConstructionInspection::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->orderByDesc('id')
                ->get();


        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */

        $users =
            User::query()
                ->orderBy('name')
                ->get();


        return view(
            'construction.quality.ncrs.create',
            compact(
                'project',
                'contracts',
                'workOrders',
                'itps',
                'inspections',
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

        $validated = $request->validate([

            'ncr_date' =>
                'required|date',

            'title' =>
                'required|string|max:255',

            'description' =>
                'required|string',

            'location' =>
                'nullable|string|max:255',

            'procurement_contract_id' =>
                'nullable|integer',

            'work_order_id' =>
                'nullable|integer',

            'construction_quality_itp_id' =>
                'nullable|integer',

            'construction_quality_itp_item_id' =>
                'nullable|integer',

            'construction_inspection_id' =>
                'nullable|integer',

            'severity' =>
                'required|in:Minor,Major,Critical',

            'raised_by' =>
                'nullable|integer|exists:users,id',

            'responsible_party' =>
                'nullable|string|max:100',

            'required_action' =>
                'nullable|string',

            'due_date' =>
                'nullable|date',

            'remarks' =>
                'nullable|string',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Verify Contract
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated['procurement_contract_id']
            )
        ) {

            $contract =
                $this->findProjectContract(
                    $project,
                    $validated['procurement_contract_id']
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
        |--------------------------------------------------------------------------
        | Verify Work Order
        |--------------------------------------------------------------------------
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
            |--------------------------------------------------------------------------
            | Automatically use Work Order contract
            |--------------------------------------------------------------------------
            */

            if (
                empty(
                    $validated['procurement_contract_id']
                )
                &&
                $workOrder->procurement_contract_id
            ) {

                $validated[
                    'procurement_contract_id'
                ] =
                    $workOrder->procurement_contract_id;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Verify ITP
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated['construction_quality_itp_id']
            )
        ) {

            $itp =
                ConstructionQualityItp::query()
                    ->where(
                        'id',
                        $validated[
                            'construction_quality_itp_id'
                        ]
                    )
                    ->where(
                        'project_id',
                        $project->id
                    )
                    ->first();


            if (!$itp) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'construction_quality_itp_id' =>
                            'The selected ITP does not belong to this project.',
                    ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Verify ITP Item
            |--------------------------------------------------------------------------
            */

            if (
                !empty(
                    $validated[
                        'construction_quality_itp_item_id'
                    ]
                )
            ) {

                $item =
                    ConstructionQualityItpItem::query()
                        ->where(
                            'id',
                            $validated[
                                'construction_quality_itp_item_id'
                            ]
                        )
                        ->where(
                            'construction_quality_itp_id',
                            $itp->id
                        )
                        ->first();


                if (!$item) {

                    return back()
                        ->withInput()
                        ->withErrors([
                            'construction_quality_itp_item_id' =>
                                'The selected ITP item does not belong to the selected ITP.',
                        ]);
                }
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Verify Inspection
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated['construction_inspection_id']
            )
        ) {

            $inspection =
                ConstructionInspection::query()
                    ->where(
                        'id',
                        $validated[
                            'construction_inspection_id'
                        ]
                    )
                    ->where(
                        'project_id',
                        $project->id
                    )
                    ->first();


            if (!$inspection) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'construction_inspection_id' =>
                            'The selected inspection does not belong to this project.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Create NCR
        |--------------------------------------------------------------------------
        */

        $ncr =
            DB::transaction(
                function () use (
                    $validated,
                    $project
                ) {

                    $ncrNumber =
                        $this->generateNcrNumber(
                            $project
                        );


                    return ConstructionQualityNcr::create([

                        'project_id' =>
                            $project->id,

                        'ncr_number' =>
                            $ncrNumber,

                        'ncr_date' =>
                            $validated['ncr_date'],

                        'title' =>
                            $validated['title'],

                        'description' =>
                            $validated['description'],

                        'location' =>
                            $validated['location']
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

                        'construction_quality_itp_id' =>
                            $validated[
                                'construction_quality_itp_id'
                            ]
                            ?? null,

                        'construction_quality_itp_item_id' =>
                            $validated[
                                'construction_quality_itp_item_id'
                            ]
                            ?? null,

                        'construction_inspection_id' =>
                            $validated[
                                'construction_inspection_id'
                            ]
                            ?? null,

                        'severity' =>
                            $validated['severity'],

                        'raised_by' =>
                            $validated['raised_by']
                            ?? auth()->id(),

                        'responsible_party' =>
                            $validated[
                                'responsible_party'
                            ]
                            ?? null,

                        'required_action' =>
                            $validated[
                                'required_action'
                            ]
                            ?? null,

                        'due_date' =>
                            $validated['due_date']
                            ?? null,

                        'status' =>
                            'Open',

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
                'admin.projects.construction.quality.ncrs.show',
                [
                    'project' =>
                        $project,

                    'ncr' =>
                        $ncr,
                ]
            )
            ->with(
                'success',
                'NCR '
                . $ncr->ncr_number
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
        ConstructionQualityNcr $ncr
    ): View {

        $this->ensureProjectNcr(
            $project,
            $ncr
        );


        $ncr->load([
            'contract.bidder',
            'workOrder',
            'itp',
            'itpItem',
            'inspection',
            'raisedBy',
            'verifiedBy',
            'closedBy',
            'creator',
            'updater',
            'actions.responsibleUser',
            'actions.creator',
        ]);


        $users =
            User::query()
                ->orderBy('name')
                ->get();


        return view(
            'construction.quality.ncrs.show',
            compact(
                'project',
                'ncr',
                'users'
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
        ConstructionQualityNcr $ncr
    ): View {

        $this->ensureProjectNcr(
            $project,
            $ncr
        );


        if (
            !in_array(
                $ncr->status,
                [
                    'Open',
                    'Rejected',
                ],
                true
            )
        ) {

            abort(
                403,
                'This NCR cannot be edited in its current status.'
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
                ->with([
                    'bidder',
                ])
                ->orderBy(
                    'contract_number'
                )
                ->get();


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


        $itps =
            ConstructionQualityItp::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->with([
                    'items',
                ])
                ->orderBy(
                    'itp_number'
                )
                ->get();


        $inspections =
            ConstructionInspection::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->orderByDesc('id')
                ->get();


        $users =
            User::query()
                ->orderBy('name')
                ->get();


        return view(
            'construction.quality.ncrs.edit',
            compact(
                'project',
                'ncr',
                'contracts',
                'workOrders',
                'itps',
                'inspections',
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
        ConstructionQualityNcr $ncr
    ): RedirectResponse {

        $this->ensureProjectNcr(
            $project,
            $ncr
        );


        if (
            !in_array(
                $ncr->status,
                [
                    'Open',
                    'Rejected',
                ],
                true
            )
        ) {

            abort(
                403,
                'This NCR cannot be edited in its current status.'
            );
        }


        $validated = $request->validate([

            'ncr_date' =>
                'required|date',

            'title' =>
                'required|string|max:255',

            'description' =>
                'required|string',

            'location' =>
                'nullable|string|max:255',

            'procurement_contract_id' =>
                'nullable|integer',

            'work_order_id' =>
                'nullable|integer',

            'construction_quality_itp_id' =>
                'nullable|integer',

            'construction_quality_itp_item_id' =>
                'nullable|integer',

            'construction_inspection_id' =>
                'nullable|integer',

            'severity' =>
                'required|in:Minor,Major,Critical',

            'raised_by' =>
                'nullable|integer|exists:users,id',

            'responsible_party' =>
                'nullable|string|max:100',

            'required_action' =>
                'nullable|string',

            'due_date' =>
                'nullable|date',

            'remarks' =>
                'nullable|string',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate Project References
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated['procurement_contract_id']
            )
            &&
            !$this->findProjectContract(
                $project,
                $validated['procurement_contract_id']
            )
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'procurement_contract_id' =>
                        'The selected contract does not belong to this project.',
                ]);
        }


        if (
            !empty(
                $validated['work_order_id']
            )
        ) {

            $exists =
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


            if (!$exists) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'work_order_id' =>
                            'The selected Work Order does not belong to this project.',
                    ]);
            }
        }


        if (
            !empty(
                $validated['construction_quality_itp_id']
            )
        ) {

            $itp =
                ConstructionQualityItp::query()
                    ->where(
                        'id',
                        $validated[
                            'construction_quality_itp_id'
                        ]
                    )
                    ->where(
                        'project_id',
                        $project->id
                    )
                    ->first();


            if (!$itp) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'construction_quality_itp_id' =>
                            'The selected ITP does not belong to this project.',
                    ]);
            }


            if (
                !empty(
                    $validated[
                        'construction_quality_itp_item_id'
                    ]
                )
                &&
                !ConstructionQualityItpItem::query()
                    ->where(
                        'id',
                        $validated[
                            'construction_quality_itp_item_id'
                        ]
                    )
                    ->where(
                        'construction_quality_itp_id',
                        $itp->id
                    )
                    ->exists()
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'construction_quality_itp_item_id' =>
                            'The selected ITP item does not belong to the selected ITP.',
                    ]);
            }
        }


        if (
            !empty(
                $validated['construction_inspection_id']
            )
            &&
            !ConstructionInspection::query()
                ->where(
                    'id',
                    $validated[
                        'construction_inspection_id'
                    ]
                )
                ->where(
                    'project_id',
                    $project->id
                )
                ->exists()
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'construction_inspection_id' =>
                        'The selected inspection does not belong to this project.',
                ]);
        }


        $validated['updated_by'] =
            auth()->id();


        $ncr->update(
            $validated
        );


        return redirect()
            ->route(
                'admin.projects.construction.quality.ncrs.show',
                [
                    'project' =>
                        $project,

                    'ncr' =>
                        $ncr,
                ]
            )
            ->with(
                'success',
                'NCR updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ConstructionQualityNcr $ncr
    ): RedirectResponse {

        $this->ensureProjectNcr(
            $project,
            $ncr
        );


        if (
            !in_array(
                $ncr->status,
                [
                    'Open',
                    'Rejected',
                ],
                true
            )
        ) {

            return back()
                ->withErrors([
                    'ncr' =>
                        'Only Open or Rejected NCRs can be deleted.',
                ]);
        }


        $ncr->delete();


        return redirect()
            ->route(
                'admin.projects.construction.quality.ncrs.index',
                $project
            )
            ->with(
                'success',
                'NCR deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Submit
    |--------------------------------------------------------------------------
    */

    public function submit(
        Project $project,
        ConstructionQualityNcr $ncr
    ): RedirectResponse {

        $this->ensureProjectNcr(
            $project,
            $ncr
        );


        if ($ncr->status !== 'Open') {

            return back()
                ->withErrors([
                    'ncr' =>
                        'Only Open NCRs can be submitted.',
                ]);
        }


        $ncr->update([

            'status' =>
                'Submitted',

            'submitted_at' =>
                now(),

            'updated_by' =>
                auth()->id(),
        ]);


        return back()
            ->with(
                'success',
                'NCR submitted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Start Review
    |--------------------------------------------------------------------------
    */

    public function startReview(
        Project $project,
        ConstructionQualityNcr $ncr
    ): RedirectResponse {

        $this->ensureProjectNcr(
            $project,
            $ncr
        );


        if ($ncr->status !== 'Submitted') {

            return back()
                ->withErrors([
                    'ncr' =>
                        'Only submitted NCRs can enter review.',
                ]);
        }


        $ncr->update([

            'status' =>
                'Under Review',

            'updated_by' =>
                auth()->id(),
        ]);


        return back()
            ->with(
                'success',
                'NCR review started.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Require Corrective Action
    |--------------------------------------------------------------------------
    */

    public function requireCorrectiveAction(
        Project $project,
        ConstructionQualityNcr $ncr
    ): RedirectResponse {

        $this->ensureProjectNcr(
            $project,
            $ncr
        );


        if ($ncr->status !== 'Under Review') {

            return back()
                ->withErrors([
                    'ncr' =>
                        'Only NCRs under review can be sent for corrective action.',
                ]);
        }


        $ncr->update([

            'status' =>
                'Corrective Action Required',

            'updated_by' =>
                auth()->id(),
        ]);


        return back()
            ->with(
                'success',
                'Corrective action has been requested.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Start Verification
    |--------------------------------------------------------------------------
    */

    public function startVerification(
        Project $project,
        ConstructionQualityNcr $ncr
    ): RedirectResponse {

        $this->ensureProjectNcr(
            $project,
            $ncr
        );


        if (
            $ncr->status !==
            'Corrective Action Submitted'
        ) {

            return back()
                ->withErrors([
                    'ncr' =>
                        'Only NCRs with submitted corrective action can enter verification.',
                ]);
        }


        $ncr->update([

            'status' =>
                'Verification',

            'updated_by' =>
                auth()->id(),
        ]);


        return back()
            ->with(
                'success',
                'NCR moved to verification.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Verify / Close
    |--------------------------------------------------------------------------
    */

    public function verify(
        Request $request,
        Project $project,
        ConstructionQualityNcr $ncr
    ): RedirectResponse {

        $this->ensureProjectNcr(
            $project,
            $ncr
        );


        if ($ncr->status !== 'Verification') {

            return back()
                ->withErrors([
                    'ncr' =>
                        'Only NCRs under verification can be closed.',
                ]);
        }


        $validated = $request->validate([

            'verification_remarks' =>
                'required|string',

        ]);


        $ncr->update([

            'status' =>
                'Closed',

            'verified_by' =>
                auth()->id(),

            'verified_at' =>
                now(),

            'verification_remarks' =>
                $validated[
                    'verification_remarks'
                ],

            'closed_by' =>
                auth()->id(),

            'closed_at' =>
                now(),

            'closure_remarks' =>
                $validated[
                    'verification_remarks'
                ],

            'updated_by' =>
                auth()->id(),
        ]);


        return back()
            ->with(
                'success',
                'NCR verified and closed successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    private function ensureProjectNcr(
        Project $project,
        ConstructionQualityNcr $ncr
    ): void {

        abort_unless(
            $ncr->project_id === $project->id,
            404
        );
    }


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


    private function generateNcrNumber(
        Project $project
    ): string {

        do {

            $number =
                'NCR-' .
                str_pad(
                    (string) $project->id,
                    4,
                    '0',
                    STR_PAD_LEFT
                ) .
                '-' .
                str_pad(
                    (string) (
                        ConstructionQualityNcr::query()
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
            ConstructionQualityNcr::query()
                ->where(
                    'ncr_number',
                    $number
                )
                ->exists()
        );


        return $number;
    }


    /*
    |--------------------------------------------------------------------------
    | Submit Corrective Action
    |--------------------------------------------------------------------------
    */

    public function submitCorrectiveAction(
        Request $request,
        Project $project,
        ConstructionQualityNcr $ncr
    ): RedirectResponse {

        $this->ensureProjectNcr(
            $project,
            $ncr
        );


        if (
            $ncr->status !==
            'Corrective Action Required'
        ) {

            return back()
                ->withErrors([
                    'ncr' =>
                        'This NCR is not currently awaiting corrective action.',
                ]);
        }


        $validated = $request->validate([

            'action_description' =>
                'required|string',

            'responsible_party' =>
                'nullable|string|max:100',

            'responsible_user_id' =>
                'nullable|integer|exists:users,id',

            'due_date' =>
                'nullable|date',

            'completed_date' =>
                'nullable|date',

        ]);


        DB::transaction(
            function () use (
                $validated,
                $ncr
            ) {

                /*
                |--------------------------------------------------------------------------
                | Create Corrective Action History
                |--------------------------------------------------------------------------
                */

                $ncr->actions()->create([

                    'action_type' =>
                        'Corrective Action',

                    'action_description' =>
                        $validated[
                            'action_description'
                        ],

                    'action_date' =>
                        now()->toDateString(),

                    'responsible_party' =>
                        $validated[
                            'responsible_party'
                        ]
                        ?? null,

                    'responsible_user_id' =>
                        $validated[
                            'responsible_user_id'
                        ]
                        ?? null,

                    'due_date' =>
                        $validated[
                            'due_date'
                        ]
                        ?? null,

                    'completed_date' =>
                        $validated[
                            'completed_date'
                        ]
                        ?? now()->toDateString(),

                    'status' =>
                        'Completed',

                    'created_by' =>
                        auth()->id(),

                    'updated_by' =>
                        auth()->id(),

                ]);


                /*
                |--------------------------------------------------------------------------
                | Update NCR
                |--------------------------------------------------------------------------
                */

                $ncr->update([

                    'status' =>
                        'Corrective Action Submitted',

                    'updated_by' =>
                        auth()->id(),

                ]);

            }
        );


        return back()
            ->with(
                'success',
                'Corrective action submitted successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Return for Correction
    |--------------------------------------------------------------------------
    */

    public function returnForCorrection(
        Request $request,
        Project $project,
        ConstructionQualityNcr $ncr
    ): RedirectResponse {

        $this->ensureProjectNcr(
            $project,
            $ncr
        );


        if (
            $ncr->status !==
            'Verification'
        ) {

            return back()
                ->withErrors([
                    'ncr' =>
                        'Only NCRs under verification can be returned for correction.',
                ]);
        }


        $validated = $request->validate([

            'verification_remarks' =>
                'required|string',

        ]);


        DB::transaction(
            function () use (
                $validated,
                $ncr
            ) {

                /*
                |--------------------------------------------------------------------------
                | Record Verification Failure
                |--------------------------------------------------------------------------
                */

                $ncr->actions()->create([

                    'action_type' =>
                        'Verification - Correction Required',

                    'action_description' =>
                        $validated[
                            'verification_remarks'
                        ],

                    'action_date' =>
                        now()->toDateString(),

                    'status' =>
                        'Open',

                    'verification_remarks' =>
                        $validated[
                            'verification_remarks'
                        ],

                    'created_by' =>
                        auth()->id(),

                    'updated_by' =>
                        auth()->id(),

                ]);


                /*
                |--------------------------------------------------------------------------
                | Send NCR Back
                |--------------------------------------------------------------------------
                */

                $ncr->update([

                    'status' =>
                        'Corrective Action Required',

                    'verification_remarks' =>
                        $validated[
                            'verification_remarks'
                        ],

                    'updated_by' =>
                        auth()->id(),

                ]);

            }
        );


        return back()
            ->with(
                'success',
                'NCR returned for corrective action.'
            );
    }
    
}