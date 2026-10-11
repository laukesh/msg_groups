<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionSiteInstruction;
use App\Models\ConstructionWorkOrder;
use App\Models\ProcurementContract;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\ProjectConsultant;

class SiteInstructionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(Project $project): View
    {
        $instructions =
            ConstructionSiteInstruction::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->with([
                    'contract.bidder',
                    'workOrder.contract.bidder',
                    'issuedBy',
                    'issuedTo',
                ])
                ->orderByDesc(
                    'instruction_date'
                )
                ->orderByDesc(
                    'id'
                )
                ->paginate(15);


        $baseQuery =
            ConstructionSiteInstruction::query()
                ->where(
                    'project_id',
                    $project->id
                );


        $counts = [

            'total' =>
                (clone $baseQuery)->count(),

            'draft' =>
                (clone $baseQuery)
                    ->where(
                        'status',
                        'Draft'
                    )
                    ->count(),

            'issued' =>
                (clone $baseQuery)
                    ->where(
                        'status',
                        'Issued'
                    )
                    ->count(),

            'acknowledged' =>
                (clone $baseQuery)
                    ->where(
                        'status',
                        'Acknowledged'
                    )
                    ->count(),

            'in_progress' =>
                (clone $baseQuery)
                    ->where(
                        'status',
                        'In Progress'
                    )
                    ->count(),

            'complied' =>
                (clone $baseQuery)
                    ->where(
                        'status',
                        'Complied'
                    )
                    ->count(),

            'closed' =>
                (clone $baseQuery)
                    ->where(
                        'status',
                        'Closed'
                    )
                    ->count(),

        ];


        return view(
            'construction.site-instructions.index',
            compact(
                'project',
                'instructions',
                'counts'
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
            $this->projectContracts(
                $project
            );


        /*
        |--------------------------------------------------------------------------
        | Work Orders
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
                ->orderByDesc(
                    'id'
                )
                ->get();


        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */

        $users =
            User::query()
                ->orderBy(
                    'name'
                )
                ->get();

        $consultants = ProjectConsultant::query()
                        ->where(
                            'project_id',
                            $project->id
                        )
                        ->orderBy(
                            'company_name'
                        )
                        ->orderBy(
                            'consultant_name'
                        )
                        ->get();


        return view(
            'construction.site-instructions.create',
            compact(
                'project',
                'contracts',
                'workOrders',
                'users',
                'consultants',
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

    /*
    |--------------------------------------------------------------------------
    | Validate Request
    |--------------------------------------------------------------------------
    */

    $validated = $this->validateRequest($request);


    /*
    |--------------------------------------------------------------------------
    | Ensure Optional Values Exist
    |--------------------------------------------------------------------------
    */

    $validated['consultant_id'] =
        $validated['consultant_id'] ?? null;

    $validated['procurement_contract_id'] =
        $validated['procurement_contract_id'] ?? null;

    $validated['issued_to'] =
        $validated['issued_to'] ?? null;

    $validated['work_order_id'] =
        $validated['work_order_id'] ?? null;

    $validated['schedule_activity_id'] =
        $validated['schedule_activity_id'] ?? null;

    /*print_r($request['consultant_id']);
    print_r($validated['consultant_id']);
    die();*/


    /*
    |--------------------------------------------------------------------------
    | Verify Consultant Belongs To Project
    |--------------------------------------------------------------------------
    */

    if (!empty($validated['consultant_id'])) {

        $consultant = ProjectConsultant::query()
            ->where(
                'id',
                $validated['consultant_id']
            )
            ->where(
                'project_id',
                $project->id
            )
            ->first();

        if (!$consultant) {

            return back()
                ->withInput()
                ->withErrors([
                    'consultant_id' =>
                        'The selected consultant does not belong to this project.',
                ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Verify Contract
    |--------------------------------------------------------------------------
    */

    $contract = null;

    if (!empty($validated['procurement_contract_id'])) {

        $contract = $this->findProjectContract(
            $project,
            (int) $validated['procurement_contract_id']
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

    $workOrder = null;

    if (!empty($validated['work_order_id'])) {

        $workOrder = ConstructionWorkOrder::query()
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
        | If Work Order Selected But Contract Not Selected
        |--------------------------------------------------------------------------
        |
        | Automatically use the contract attached to the Work Order.
        |
        */

        if (
            !$contract &&
            !empty($workOrder->procurement_contract_id)
        ) {

            $contract = $this->findProjectContract(
                $project,
                (int) $workOrder->procurement_contract_id
            );


            if ($contract) {

                $validated['procurement_contract_id'] =
                    $contract->id;

            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Site Instruction
    |--------------------------------------------------------------------------
    */

    $instruction = DB::transaction(
        function () use (
            $validated,
            $project
        ) {

            /*
            |--------------------------------------------------------------------------
            | Generate Unique Instruction Number
            |--------------------------------------------------------------------------
            */

            $instructionNumber =
                $this->generateInstructionNumber(
                    $project
                );


            /*
            |--------------------------------------------------------------------------
            | Create Site Instruction
            |--------------------------------------------------------------------------
            */

            return ConstructionSiteInstruction::create([

                /*
                |--------------------------------------------------------------------------
                | Project
                |--------------------------------------------------------------------------
                */

                'project_id' =>
                    $project->id,


                /*
                |--------------------------------------------------------------------------
                | Instruction Information
                |--------------------------------------------------------------------------
                */

                'instruction_number' =>
                    $instructionNumber,

                'instruction_date' =>
                    $validated['instruction_date'],

                'instruction_type' =>
                    $validated['instruction_type']
                    ?? null,

                'subject' =>
                    $validated['subject'],

                'description' =>
                    $validated['description']
                    ?? null,


                /*
                |--------------------------------------------------------------------------
                | Consultant
                |--------------------------------------------------------------------------
                */

                'consultant_id' =>
                    $validated['consultant_id'],


                /*
                |--------------------------------------------------------------------------
                | Issued By
                |--------------------------------------------------------------------------
                */

                'issued_by' =>
                    auth()->id(),


                /*
                |--------------------------------------------------------------------------
                | Issued To
                |--------------------------------------------------------------------------
                */

                'issued_to' =>
                    $validated['issued_to'],


                /*
                |--------------------------------------------------------------------------
                | Procurement Contract
                |--------------------------------------------------------------------------
                */

                'procurement_contract_id' =>
                    $validated['procurement_contract_id'],


                /*
                |--------------------------------------------------------------------------
                | Legacy Contractor ID
                |--------------------------------------------------------------------------
                |
                | Contractor is obtained through:
                |
                | ProcurementContract
                |        ↓
                | ProcurementBidder
                |
                */

                'contractor_id' =>
                    null,


                /*
                |--------------------------------------------------------------------------
                | Work Order
                |--------------------------------------------------------------------------
                */

                'work_order_id' =>
                    $validated['work_order_id'],


                /*
                |--------------------------------------------------------------------------
                | Schedule Activity
                |--------------------------------------------------------------------------
                */

                'schedule_activity_id' =>
                    $validated['schedule_activity_id'],


                /*
                |--------------------------------------------------------------------------
                | Location
                |--------------------------------------------------------------------------
                */

                'location' =>
                    $validated['location']
                    ?? null,


                /*
                |--------------------------------------------------------------------------
                | Priority
                |--------------------------------------------------------------------------
                */

                'priority' =>
                    $validated['priority']
                    ?? 'Normal',


                /*
                |--------------------------------------------------------------------------
                | Required Action
                |--------------------------------------------------------------------------
                */

                'required_action' =>
                    $validated['required_action']
                    ?? null,


                /*
                |--------------------------------------------------------------------------
                | Due Date
                |--------------------------------------------------------------------------
                */

                'due_date' =>
                    $validated['due_date']
                    ?? null,


                /*
                |--------------------------------------------------------------------------
                | Initial Status
                |--------------------------------------------------------------------------
                */

                'status' =>
                    'Draft',


                /*
                |--------------------------------------------------------------------------
                | Remarks
                |--------------------------------------------------------------------------
                */

                'remarks' =>
                    $validated['remarks']
                    ?? null,


                /*
                |--------------------------------------------------------------------------
                | Audit
                |--------------------------------------------------------------------------
                */

                'created_by' =>
                    auth()->id(),

                'updated_by' =>
                    auth()->id(),

            ]);
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Redirect To Show
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route(
            'admin.projects.construction.site-instructions.show',
            [
                'project' =>
                    $project,

                'siteInstruction' =>
                    $instruction,
            ]
        )
        ->with(
            'success',
            'Site Instruction '
            . $instruction->instruction_number
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
        ConstructionSiteInstruction $siteInstruction
    ): View {

        $this->validateOwnership(
            $project,
            $siteInstruction
        );


        $siteInstruction->load([
            'contract.bidder',
            'workOrder.contract.bidder',
            'consultant',
            'issuedBy',
            'issuedTo',
            'createdBy',
            'updatedBy',
        ]);


        return view(
            'construction.site-instructions.show',
            compact(
                'project',
                'siteInstruction'
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
        ConstructionSiteInstruction $siteInstruction
    ): View {

        $this->validateOwnership(
            $project,
            $siteInstruction
        );


        if (
            $siteInstruction->status !== 'Draft'
        ) {

            return redirect()
                ->route(
                    'admin.projects.construction.site-instructions.show',
                    [
                        'project' =>
                            $project,

                        'siteInstruction' =>
                            $siteInstruction,
                    ]
                )
                ->with(
                    'error',
                    'Only Draft Site Instructions can be edited.'
                );
        }


        $contracts =
            $this->projectContracts(
                $project
            );


        $workOrders =
            ConstructionWorkOrder::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->with([
                    'contract.bidder',
                ])
                ->orderByDesc(
                    'id'
                )
                ->get();


        $users =
            User::query()
                ->orderBy(
                    'name'
                )
                ->get();

        $consultants = ProjectConsultant::query()
            ->where(
                'project_id',
                $project->id
            )
            ->orderBy(
                'company_name'
            )
            ->orderBy(
                'consultant_name'
            )
            ->get();


        return view(
            'construction.site-instructions.edit',
            compact(
                'project',
                'siteInstruction',
                'contracts',
                'workOrders',
                'users',
                'consultants'
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
        ConstructionSiteInstruction $siteInstruction
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $siteInstruction
        );


        if (
            $siteInstruction->status !== 'Draft'
        ) {

            return back()
                ->with(
                    'error',
                    'Only Draft Site Instructions can be edited.'
                );
        }


        $validated =
            $this->validateRequest(
                $request
            );


        /*
        |--------------------------------------------------------------------------
        | Verify Contract
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated[
                    'procurement_contract_id'
                ]
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
        |--------------------------------------------------------------------------
        | Verify Work Order
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated[
                    'work_order_id'
                ]
            )
        ) {

            $workOrder =
                ConstructionWorkOrder::query()
                    ->where(
                        'id',
                        $validated[
                            'work_order_id'
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
                        'work_order_id' =>
                            'The selected Work Order does not belong to this project.',
                    ]);
            }
        }


        $siteInstruction->update([

            'instruction_date' =>
                $validated[
                    'instruction_date'
                ],

            'instruction_type' =>
                $validated[
                    'instruction_type'
                ] ?? null,

            'subject' =>
                $validated[
                    'subject'
                ],

            'description' =>
                $validated[
                    'description'
                ] ?? null,

            'issued_to' =>
                $validated[
                    'issued_to'
                ] ?? null,

            'procurement_contract_id' =>
                $validated[
                    'procurement_contract_id'
                ] ?? null,

            'contractor_id' =>
                null,

            'consultant_id' => $validated['consultant_id'] ?? null,

            'work_order_id' =>
                $validated[
                    'work_order_id'
                ] ?? null,

            'schedule_activity_id' =>
                $validated[
                    'schedule_activity_id'
                ] ?? null,

            'location' =>
                $validated[
                    'location'
                ] ?? null,

            'priority' =>
                $validated[
                    'priority'
                ] ?? 'Normal',

            'required_action' =>
                $validated[
                    'required_action'
                ] ?? null,

            'due_date' =>
                $validated[
                    'due_date'
                ] ?? null,

            'remarks' =>
                $validated[
                    'remarks'
                ] ?? null,

            'updated_by' =>
                auth()->id(),

        ]);


        return redirect()
            ->route(
                'admin.projects.construction.site-instructions.show',
                [
                    'project' =>
                        $project,

                    'siteInstruction' =>
                        $siteInstruction,
                ]
            )
            ->with(
                'success',
                'Site Instruction updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Issue
    |--------------------------------------------------------------------------
    */

    public function issue(
        Project $project,
        ConstructionSiteInstruction $siteInstruction
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $siteInstruction
        );


        if (
            $siteInstruction->status !== 'Draft'
        ) {

            return back()
                ->with(
                    'error',
                    'Only Draft Site Instructions can be issued.'
                );
        }


        $siteInstruction->update([

            'status' =>
                'Issued',

            'updated_by' =>
                auth()->id(),

        ]);


        return back()
            ->with(
                'success',
                'Site Instruction issued successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Acknowledge
    |--------------------------------------------------------------------------
    */

    public function acknowledge(
        Project $project,
        ConstructionSiteInstruction $siteInstruction
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $siteInstruction
        );


        if (
            $siteInstruction->status !== 'Issued'
        ) {

            return back()
                ->with(
                    'error',
                    'Only Issued Site Instructions can be acknowledged.'
                );
        }


        $siteInstruction->update([

            'status' =>
                'Acknowledged',

            'acknowledgement_date' =>
                now(),

            'updated_by' =>
                auth()->id(),

        ]);


        return back()
            ->with(
                'success',
                'Site Instruction acknowledged successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Start
    |--------------------------------------------------------------------------
    */

    public function start(
        Project $project,
        ConstructionSiteInstruction $siteInstruction
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $siteInstruction
        );


        if (
            !in_array(
                $siteInstruction->status,
                [
                    'Issued',
                    'Acknowledged',
                ],
                true
            )
        ) {

            return back()
                ->with(
                    'error',
                    'This Site Instruction cannot be started from its current status.'
                );
        }


        $siteInstruction->update([

            'status' =>
                'In Progress',

            'updated_by' =>
                auth()->id(),

        ]);


        return back()
            ->with(
                'success',
                'Site Instruction marked as In Progress.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Comply
    |--------------------------------------------------------------------------
    */

    public function comply(
        Request $request,
        Project $project,
        ConstructionSiteInstruction $siteInstruction
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $siteInstruction
        );


        if (
            !in_array(
                $siteInstruction->status,
                [
                    'Acknowledged',
                    'In Progress',
                ],
                true
            )
        ) {

            return back()
                ->with(
                    'error',
                    'This Site Instruction cannot be marked as Complied.'
                );
        }


        $validated =
            $request->validate([

                'response' => [
                    'required',
                    'string',
                ],

            ]);


        $siteInstruction->update([

            'status' =>
                'Complied',

            'compliance_date' =>
                now()->toDateString(),

            'response' =>
                $validated['response'],

            'updated_by' =>
                auth()->id(),

        ]);


        return back()
            ->with(
                'success',
                'Site Instruction marked as Complied.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Close
    |--------------------------------------------------------------------------
    */

    public function close(
        Project $project,
        ConstructionSiteInstruction $siteInstruction
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $siteInstruction
        );


        if (
            $siteInstruction->status !== 'Complied'
        ) {

            return back()
                ->with(
                    'error',
                    'Only Complied Site Instructions can be closed.'
                );
        }


        $siteInstruction->update([

            'status' =>
                'Closed',

            'closed_date' =>
                now()->toDateString(),

            'updated_by' =>
                auth()->id(),

        ]);


        return back()
            ->with(
                'success',
                'Site Instruction closed successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Cancel
    |--------------------------------------------------------------------------
    */

    public function cancel(
        Project $project,
        ConstructionSiteInstruction $siteInstruction
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $siteInstruction
        );


        if (
            !in_array(
                $siteInstruction->status,
                [
                    'Draft',
                    'Issued',
                ],
                true
            )
        ) {

            return back()
                ->with(
                    'error',
                    'This Site Instruction cannot be cancelled.'
                );
        }


        $siteInstruction->update([

            'status' =>
                'Cancelled',

            'updated_by' =>
                auth()->id(),

        ]);


        return back()
            ->with(
                'success',
                'Site Instruction cancelled successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ConstructionSiteInstruction $siteInstruction
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $siteInstruction
        );


        if (
            !in_array(
                $siteInstruction->status,
                [
                    'Draft',
                    'Cancelled',
                ],
                true
            )
        ) {

            return back()
                ->with(
                    'error',
                    'Only Draft or Cancelled Site Instructions can be deleted.'
                );
        }


        $siteInstruction->delete();


        return redirect()
            ->route(
                'admin.projects.construction.site-instructions.index',
                $project
            )
            ->with(
                'success',
                'Site Instruction deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Project Contracts
    |--------------------------------------------------------------------------
    */

    protected function projectContracts(
        Project $project
    ) {

        return ProcurementContract::query()
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
    }


    /*
    |--------------------------------------------------------------------------
    | Find Project Contract
    |--------------------------------------------------------------------------
    */

    protected function findProjectContract(
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
            ->with([
                'bidder',
            ])
            ->first();
    }


    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    protected function validateRequest(
        Request $request
    ): array {

        return $request->validate([

            'instruction_date' => [
                'required',
                'date',
            ],

            'instruction_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'subject' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'procurement_contract_id' => [
                'nullable',
                'integer',
            ],

            'issued_to' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'work_order_id' => [
                'nullable',
                'integer',
            ],

            'schedule_activity_id' => [
                'nullable',
                'integer',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'priority' => [
                'required',
                'in:Low,Normal,High,Critical',
            ],

            'required_action' => [
                'nullable',
                'string',
            ],

            'due_date' => [
                'nullable',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
            'consultant_id' => [
                'nullable',
                'integer',
            ],

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Ownership
    |--------------------------------------------------------------------------
    */

    protected function validateOwnership(
        Project $project,
        ConstructionSiteInstruction $siteInstruction
    ): void {

        if (
            (int) $siteInstruction->project_id
            !==
            (int) $project->id
        ) {

            abort(
                404
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Instruction Number
    |--------------------------------------------------------------------------
    */

    protected function generateInstructionNumber(
        Project $project
    ): string {

        $lastNumber =
            ConstructionSiteInstruction::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->lockForUpdate()
                ->orderByDesc(
                    'id'
                )
                ->value(
                    'instruction_number'
                );


        $nextNumber = 1;


        if (
            $lastNumber
            &&
            preg_match(
                '/(\d+)$/',
                $lastNumber,
                $matches
            )
        ) {

            $nextNumber =
                ((int) $matches[1]) + 1;
        }


        do {

            $number =
                'SI-'
                . str_pad(
                    (string) $project->id,
                    4,
                    '0',
                    STR_PAD_LEFT
                )
                . '-'
                . str_pad(
                    (string) $nextNumber,
                    4,
                    '0',
                    STR_PAD_LEFT
                );


            $exists =
                ConstructionSiteInstruction::query()
                    ->where(
                        'instruction_number',
                        $number
                    )
                    ->exists();


            if ($exists) {

                $nextNumber++;

            } else {

                break;
            }

        } while (true);


        return $number;
    }
}