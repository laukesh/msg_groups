<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\ProcurementContract;
use App\Models\ProcurementContractMilestone;
use App\Models\ProcurementTender;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProcurementContractMilestoneController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        ProcurementTender $procurementTender,
        ProcurementContract $contract
    ): View {

        $this->validateContract(
            $procurementTender,
            $contract
        );

        $milestones = $contract
            ->milestones()
            ->with([
                'invoices',
            ])
            ->orderBy('id')
            ->get();

        return view(
            'procurement.contract-milestones.index',
            compact(
                'procurementTender',
                'contract',
                'milestones'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
        ProcurementTender $procurementTender,
        ProcurementContract $contract
    ): View {

        $this->validateContract(
            $procurementTender,
            $contract
        );

        if ($contract->status !== 'Active') {

            abort(
                403,
                'Milestones can only be added to Active Contracts.'
            );
        }

        return view(
            'procurement.contract-milestones.create',
            compact(
                'procurementTender',
                'contract'
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
        ProcurementTender $procurementTender,
        ProcurementContract $contract
    ): RedirectResponse {

        $this->validateContract(
            $procurementTender,
            $contract
        );

        if ($contract->status !== 'Active') {

            return back()
                ->withInput()
                ->withErrors([
                    'contract' =>
                        'Milestones can only be added to Active Contracts.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Request
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'milestone_title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'planned_start_date' => [
                'nullable',
                'date',
            ],

            'planned_end_date' => [
                'nullable',
                'date',
                'after_or_equal:planned_start_date',
            ],

            'milestone_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'progress_percentage' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'deliverable_required' => [
                'nullable',
                'boolean',
            ],

            'deliverable_description' => [
                'nullable',
                'string',
            ],

            'responsible_user_id' => [
                'nullable',
                'integer',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Create Milestone
        |--------------------------------------------------------------------------
        */

        $milestone = DB::transaction(function () use (
            $validated,
            $contract,
            $request
        ) {

            /*
            |--------------------------------------------------------------------------
            | Generate Milestone Number
            |--------------------------------------------------------------------------
            |
            | Example:
            |
            | M-001
            | M-002
            | M-003
            |
            | Numbering starts again for each Contract.
            |
            */

            $existingMilestoneNumbers = ProcurementContractMilestone::query()
                ->where(
                    'procurement_contract_id',
                    $contract->id
                )
                ->pluck('milestone_number');


            $nextNumber = 1;


            foreach ($existingMilestoneNumbers as $number) {

                if (
                    preg_match(
                        '/^M-(\d+)$/',
                        $number,
                        $matches
                    )
                ) {

                    $currentNumber =
                        (int) $matches[1];

                    if (
                        $currentNumber >=
                        $nextNumber
                    ) {

                        $nextNumber =
                            $currentNumber + 1;
                    }
                }
            }


            $milestoneNumber =
                'M-' .
                str_pad(
                    $nextNumber,
                    3,
                    '0',
                    STR_PAD_LEFT
                );


            /*
            |--------------------------------------------------------------------------
            | Safety Check
            |--------------------------------------------------------------------------
            */

            while (
                ProcurementContractMilestone::query()
                    ->where(
                        'procurement_contract_id',
                        $contract->id
                    )
                    ->where(
                        'milestone_number',
                        $milestoneNumber
                    )
                    ->exists()
            ) {

                $nextNumber++;

                $milestoneNumber =
                    'M-' .
                    str_pad(
                        $nextNumber,
                        3,
                        '0',
                        STR_PAD_LEFT
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | Create
            |--------------------------------------------------------------------------
            */

            return ProcurementContractMilestone::create([

                'procurement_contract_id' =>
                    $contract->id,

                'milestone_number' =>
                    $milestoneNumber,

                'milestone_title' =>
                    $validated['milestone_title'],

                'description' =>
                    $validated['description']
                    ?? null,

                'planned_start_date' =>
                    $validated['planned_start_date']
                    ?? null,

                'planned_end_date' =>
                    $validated['planned_end_date']
                    ?? null,

                'milestone_amount' =>
                    $validated['milestone_amount'],

                'currency' =>
                    $contract->currency,

                'progress_percentage' =>
                    $validated['progress_percentage']
                    ?? 0,

                'status' =>
                    'Pending',

                'deliverable_required' =>
                    $request->boolean(
                        'deliverable_required'
                    ),

                'deliverable_description' =>
                    $validated['deliverable_description']
                    ?? null,

                'responsible_user_id' =>
                    $validated['responsible_user_id']
                    ?? null,

                'remarks' =>
                    $validated['remarks']
                    ?? null,

                'created_by' =>
                    auth()->id(),

                'updated_by' =>
                    auth()->id(),
            ]);
        });


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.procurement.tenders.contracts.milestones.index',
                [
                    'procurementTender' =>
                        $procurementTender,

                    'contract' =>
                        $contract,
                ]
            )
            ->with(
                'success',
                'Milestone ' .
                $milestone->milestone_number .
                ' created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractMilestone $milestone
    ): View {

        $this->validateMilestone(
            $procurementTender,
            $contract,
            $milestone
        );

        $milestone->load([
            'latestProgress',
            'documents',
            'invoices' => function ($query) {
                $query->latest('id');
            },
        ]);


        /*
        |--------------------------------------------------------------------------
        | Invoice Summary
        |--------------------------------------------------------------------------
        */

        $totalInvoiced = $milestone
            ->invoices
            ->where('status', '!=', 'Rejected')
            ->sum('net_amount');


        $remainingToInvoice = max(
            0,
            (float) $milestone->milestone_amount
                - (float) $totalInvoiced
        );


        /*
        |--------------------------------------------------------------------------
        | Payment Summary
        |--------------------------------------------------------------------------
        */

        $totalPaid = $milestone
            ->invoices
            ->sum(function ($invoice) {

                return $invoice
                    ->payments()
                    ->where('status', 'Processed')
                    ->sum('amount');
            });


        $totalOutstanding = max(
            0,
            (float) $totalInvoiced
                - (float) $totalPaid
        );


        return view(
            'procurement.contract-milestones.show',
            compact(
                'procurementTender',
                'contract',
                'milestone',
                'totalInvoiced',
                'remainingToInvoice',
                'totalPaid',
                'totalOutstanding'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | START
    |--------------------------------------------------------------------------
    */

    public function start(
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractMilestone $milestone
    ): RedirectResponse {

        $this->validateMilestone(
            $procurementTender,
            $contract,
            $milestone
        );

        if ($contract->status !== 'Active') {

            return back()->with(
                'error',
                'The Contract is not Active.'
            );
        }


        if ($milestone->status !== 'Pending') {

            return back()->with(
                'error',
                'Only Pending milestones can be started.'
            );
        }


        $milestone->update([

            'status' =>
                'In Progress',

            'actual_start_date' =>
                now()->format('Y-m-d'),

            'progress_percentage' =>
                max(
                    1,
                    (float) $milestone->progress_percentage
                ),

            'updated_by' =>
                auth()->id(),
        ]);


        return back()->with(
            'success',
            'Milestone started successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | COMPLETE
    |--------------------------------------------------------------------------
    */

    public function complete(
        Request $request,
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractMilestone $milestone
    ): RedirectResponse {

        $this->validateMilestone(
            $procurementTender,
            $contract,
            $milestone
        );


        if ($contract->status !== 'Active') {

            return back()->with(
                'error',
                'Milestone can only be completed under an Active Contract.'
            );
        }


        if ($milestone->status !== 'In Progress') {

            return back()->with(
                'error',
                'Only an In Progress milestone can be completed.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Deliverable Validation
        |--------------------------------------------------------------------------
        */

        if ($milestone->deliverable_required) {

            $documents = $milestone
                ->documents()
                ->get();


            if ($documents->isEmpty()) {

                return back()->with(
                    'error',
                    'This milestone requires at least one deliverable document before completion.'
                );
            }


            $unverifiedDocuments = $documents
                ->whereIn(
                    'status',
                    [
                        'Submitted',
                        'Rejected',
                    ]
                );


            if ($unverifiedDocuments->isNotEmpty()) {

                return back()->with(
                    'error',
                    'All deliverable documents must be verified before completing this milestone.'
                );
            }


            $verifiedDocuments = $documents
                ->where(
                    'status',
                    'Verified'
                );


            if ($verifiedDocuments->isEmpty()) {

                return back()->with(
                    'error',
                    'At least one deliverable document must be verified before completing this milestone.'
                );
            }
        }


        $validated = $request->validate([

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        $milestone->update([

            'progress_percentage' =>
                100,

            'status' =>
                'Completed',

            'actual_end_date' =>
                now()->toDateString(),

            'completed_by' =>
                auth()->id(),

            'completed_at' =>
                now(),

            'remarks' =>
                $validated['remarks']
                ?? $milestone->remarks,

            'updated_by' =>
                auth()->id(),
        ]);


        return redirect()
            ->route(
                'admin.procurement.tenders.contracts.milestones.show',
                [
                    'procurementTender' =>
                        $procurementTender,

                    'contract' =>
                        $contract,

                    'milestone' =>
                        $milestone,
                ]
            )
            ->with(
                'success',
                'Milestone completed successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE CONTRACT
    |--------------------------------------------------------------------------
    */

    private function validateContract(
        ProcurementTender $procurementTender,
        ProcurementContract $contract
    ): void {

        abort_unless(
            (int) $contract->procurement_tender_id
                ===
            (int) $procurementTender->id,
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE MILESTONE
    |--------------------------------------------------------------------------
    */

    private function validateMilestone(
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractMilestone $milestone
    ): void {

        $this->validateContract(
            $procurementTender,
            $contract
        );


        abort_unless(
            (int) $milestone->procurement_contract_id
                ===
            (int) $contract->id,
            404
        );
    }
}