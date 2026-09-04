<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\ProcurementAward;
use App\Models\ProcurementContract;
use App\Models\ProcurementTender;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProcurementContractController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        ProcurementTender $procurementTender
    ): View {

        $contracts = ProcurementContract::query()
            ->where(
                'procurement_tender_id',
                $procurementTender->id
            )
            ->with([
                'award',
                'negotiation',
                'bidder',
            ])
            ->latest('id')
            ->get();

        return view(
            'procurement.contracts.index',
            compact(
                'procurementTender',
                'contracts'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
        ProcurementTender $procurementTender
    ): View {

        /*
        |--------------------------------------------------------------------------
        | Only LOA Issued awards can create a contract
        |--------------------------------------------------------------------------
        */

        $awards = ProcurementAward::query()
            ->where(
                'procurement_tender_id',
                $procurementTender->id
            )
            ->where(
                'status',
                'LOA Issued'
            )
            ->with([
                'negotiation',
                'submission.tenderBidder.bidder',
            ])
            ->latest('id')
            ->get();


        return view(
            'procurement.contracts.create',
            compact(
                'procurementTender',
                'awards'
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
        ProcurementTender $procurementTender
    ): RedirectResponse {

        $validated = $request->validate([

            'procurement_award_id' => [
                'required',
                'integer',
                'exists:procurement_awards,id',
            ],

            'contract_title' => [
                'required',
                'string',
                'max:255',
            ],

            'contract_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'contract_start_date' => [
                'nullable',
                'date',
            ],

            'contract_end_date' => [
                'nullable',
                'date',
                'after_or_equal:contract_start_date',
            ],

            'contract_duration_days' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'signing_date' => [
                'nullable',
                'date',
            ],

            'performance_security_required' => [
                'nullable',
                'boolean',
            ],

            'performance_security_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'retention_required' => [
                'nullable',
                'boolean',
            ],

            'retention_percentage' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'responsible_user_id' => [
                'nullable',
                'integer',
            ],

            'scope_of_work' => [
                'nullable',
                'string',
            ],

            'terms_and_conditions' => [
                'nullable',
                'string',
            ],

            'special_conditions' => [
                'nullable',
                'string',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Get Award
        |--------------------------------------------------------------------------
        */

        $award = ProcurementAward::query()
            ->where(
                'id',
                $validated['procurement_award_id']
            )
            ->where(
                'procurement_tender_id',
                $procurementTender->id
            )
            ->where(
                'status',
                'LOA Issued'
            )
            ->with([
                'negotiation',
                'submission.tenderBidder.bidder',
            ])
            ->first();


        if (!$award) {

            return back()
                ->withInput()
                ->withErrors([
                    'procurement_award_id' =>
                        'Only an LOA Issued Award belonging to this Tender can create a Contract.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Contract
        |--------------------------------------------------------------------------
        */

        $existingContract =
            ProcurementContract::query()
                ->where(
                    'procurement_award_id',
                    $award->id
                )
                ->first();


        if ($existingContract) {

            return back()
                ->withInput()
                ->withErrors([
                    'procurement_award_id' =>
                        'A Contract already exists for this Award.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Negotiation
        |--------------------------------------------------------------------------
        */

        $negotiation =
            $award->negotiation;


        /*
        |--------------------------------------------------------------------------
        | Submission
        |--------------------------------------------------------------------------
        */

        $submission =
            $award->submission;


        if (
            !$negotiation ||
            !$submission
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'procurement_award_id' =>
                        'Award is missing its negotiation or tender submission.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Resolve Tender Bidder
        |--------------------------------------------------------------------------
        */

        $tenderBidder =
            $submission->tenderBidder;


        if (!$tenderBidder) {

            return back()
                ->withInput()
                ->withErrors([
                    'procurement_award_id' =>
                        'Tender submission is not linked to a tender bidder.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Resolve Procurement Bidder
        |--------------------------------------------------------------------------
        */

        $bidder =
            $tenderBidder->bidder;


        if (!$bidder) {

            return back()
                ->withInput()
                ->withErrors([
                    'procurement_award_id' =>
                        'Tender bidder is not linked to a procurement bidder.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Create Contract
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $validated,
            $procurementTender,
            $award,
            $negotiation,
            $submission,
            $bidder
        ) {

            /*
            |--------------------------------------------------------------------------
            | Generate Contract Number
            |--------------------------------------------------------------------------
            |
            | Format:
            |
            | CON-2026-001
            | CON-2026-002
            | CON-2026-003
            |
            */

            $year = now()->format('Y');

            $prefix = 'CON-' . $year . '-';


            /*
            |--------------------------------------------------------------------------
            | Get Last Contract Number For Current Year
            |--------------------------------------------------------------------------
            */

            $lastContract =
                ProcurementContract::query()
                    ->where(
                        'contract_number',
                        'like',
                        $prefix . '%'
                    )
                    ->latest('id')
                    ->lockForUpdate()
                    ->first();


            /*
            |--------------------------------------------------------------------------
            | Determine Next Sequence
            |--------------------------------------------------------------------------
            */

            if ($lastContract) {

                $lastNumber =
                    (int) str_replace(
                        $prefix,
                        '',
                        $lastContract->contract_number
                    );

                $nextNumber =
                    $lastNumber + 1;

            } else {

                $nextNumber = 1;

            }


            /*
            |--------------------------------------------------------------------------
            | Generate Contract Number
            |--------------------------------------------------------------------------
            */

            $contractNumber =
                $prefix .
                str_pad(
                    $nextNumber,
                    3,
                    '0',
                    STR_PAD_LEFT
                );


            /*
            |--------------------------------------------------------------------------
            | Create Contract
            |--------------------------------------------------------------------------
            */

            ProcurementContract::create([

                /*
                |--------------------------------------------------------------------------
                | Procurement Chain
                |--------------------------------------------------------------------------
                */

                'procurement_tender_id' =>
                    $procurementTender->id,

                'procurement_award_id' =>
                    $award->id,

                'procurement_negotiation_id' =>
                    $negotiation->id,

                'procurement_tender_submission_id' =>
                    $submission->id,


                /*
                |--------------------------------------------------------------------------
                | Contractor / Supplier
                |--------------------------------------------------------------------------
                */

                'procurement_bidder_id' =>
                    $bidder->id,


                /*
                |--------------------------------------------------------------------------
                | Contract
                |--------------------------------------------------------------------------
                */

                'contract_number' =>
                    $contractNumber,

                'contract_title' =>
                    $validated['contract_title'],

                'contract_type' =>
                    $validated['contract_type']
                    ?? 'Procurement Contract',


                /*
                |--------------------------------------------------------------------------
                | Bidder Snapshot
                |--------------------------------------------------------------------------
                */

                'bidder_name' =>
                    $bidder->company_name
                    ?? $award->bidder_name,


                /*
                |--------------------------------------------------------------------------
                | Financial
                |--------------------------------------------------------------------------
                */

                'contract_amount' =>
                    $award->awarded_amount,

                'currency' =>
                    $award->currency,


                /*
                |--------------------------------------------------------------------------
                | Dates
                |--------------------------------------------------------------------------
                */

                'contract_start_date' =>
                    $validated['contract_start_date']
                    ?? null,

                'contract_end_date' =>
                    $validated['contract_end_date']
                    ?? null,

                'contract_duration_days' =>
                    $validated['contract_duration_days']
                    ?? null,

                'signing_date' =>
                    $validated['signing_date']
                    ?? null,


                /*
                |--------------------------------------------------------------------------
                | Status
                |--------------------------------------------------------------------------
                */

                'status' =>
                    'Draft',


                /*
                |--------------------------------------------------------------------------
                | LOA
                |--------------------------------------------------------------------------
                */

                'loa_number' =>
                    $award->loa_number,

                'loa_date' =>
                    $award->loa_date,


                /*
                |--------------------------------------------------------------------------
                | Security
                |--------------------------------------------------------------------------
                */

                'performance_security_required' =>
                    $validated[
                        'performance_security_required'
                    ] ?? false,

                'performance_security_amount' =>
                    $validated[
                        'performance_security_amount'
                    ] ?? 0,

                'retention_required' =>
                    $validated[
                        'retention_required'
                    ] ?? false,

                'retention_percentage' =>
                    $validated[
                        'retention_percentage'
                    ] ?? 0,


                /*
                |--------------------------------------------------------------------------
                | Responsible User
                |--------------------------------------------------------------------------
                */

                'responsible_user_id' =>
                    $validated[
                        'responsible_user_id'
                    ] ?? null,


                /*
                |--------------------------------------------------------------------------
                | Contract Content
                |--------------------------------------------------------------------------
                */

                'scope_of_work' =>
                    $validated[
                        'scope_of_work'
                    ] ?? null,

                'terms_and_conditions' =>
                    $validated[
                        'terms_and_conditions'
                    ] ?? null,

                'special_conditions' =>
                    $validated[
                        'special_conditions'
                    ] ?? null,

                'remarks' =>
                    $validated[
                        'remarks'
                    ] ?? null,


                /*
                |--------------------------------------------------------------------------
                | Audit
                |--------------------------------------------------------------------------
                */

                'created_by' =>
                    auth()->id(),

            ]);

        });


        return redirect()
            ->route(
                'admin.procurement.tenders.contracts.index',
                $procurementTender
            )
            ->with(
                'success',
                'Contract created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        ProcurementTender $procurementTender,
        ProcurementContract $contract
    ): View {

        abort_unless(
            $contract->procurement_tender_id
            === $procurementTender->id,
            404
        );


        $contract->load([
            'tender.package.procurementPlan.project',
            'tender.package.budget',

            'award',
            'negotiation',

            'submission.tenderBidder.bidder',

            'bidder',

            'invoices',
            'payments',

            'milestones',
        ]);


        return view(
            'procurement.contracts.show',
            compact(
                'procurementTender',
                'contract'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SUBMIT
    |--------------------------------------------------------------------------
    */

    public function submit(
        ProcurementTender $procurementTender,
        ProcurementContract $contract
    ): RedirectResponse {

        abort_unless(
            $contract->procurement_tender_id
            === $procurementTender->id,
            404
        );


        if ($contract->status !== 'Draft') {

            return back()->with(
                'error',
                'Only Draft Contracts can be submitted.'
            );
        }


        $contract->update([

            'status' =>
                'Under Review',

            'submitted_by' =>
                auth()->id(),

            'submitted_at' =>
                now(),

            'updated_by' =>
                auth()->id(),

        ]);


        return back()->with(
            'success',
            'Contract submitted for approval successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | APPROVE
    |--------------------------------------------------------------------------
    */

    public function approve(
        Request $request,
        ProcurementTender $procurementTender,
        ProcurementContract $contract
    ): RedirectResponse {

        abort_unless(
            $contract->procurement_tender_id
            === $procurementTender->id,
            404
        );


        if ($contract->status !== 'Under Review') {

            return back()->with(
                'error',
                'Only Contracts under review can be approved.'
            );
        }


        $validated = $request->validate([

            'approval_remarks' => [
                'nullable',
                'string',
            ],

        ]);


        $contract->update([

            'status' =>
                'Approved',

            'approved_by' =>
                auth()->id(),

            'approval_date' =>
                now()->format('Y-m-d'),

            'approval_remarks' =>
                $validated['approval_remarks']
                ?? null,

            'updated_by' =>
                auth()->id(),

        ]);


        return back()->with(
            'success',
            'Contract approved successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ACTIVATE
    |--------------------------------------------------------------------------
    */

    public function activate(
        ProcurementTender $procurementTender,
        ProcurementContract $contract
    ): RedirectResponse {

        abort_unless(
            $contract->procurement_tender_id
            === $procurementTender->id,
            404
        );


        if ($contract->status !== 'Approved') {

            return back()->with(
                'error',
                'Only approved Contracts can be activated.'
            );
        }


        if (!$contract->contract_start_date) {

            return back()->with(
                'error',
                'Contract start date is required before activation.'
            );
        }


        if (!$contract->contract_end_date) {

            return back()->with(
                'error',
                'Contract end date is required before activation.'
            );
        }


        if (
            $contract->contract_end_date
            < $contract->contract_start_date
        ) {

            return back()->with(
                'error',
                'Contract end date cannot be before start date.'
            );
        }


        $contract->update([

            'status' =>
                'Active',

            'activated_by' =>
                auth()->id(),

            'activated_at' =>
                now(),

            'updated_by' =>
                auth()->id(),

        ]);


        return back()->with(
            'success',
            'Contract activated successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CLOSE
    |--------------------------------------------------------------------------
    */

    public function close(
        Request $request,
        ProcurementTender $procurementTender,
        ProcurementContract $contract
    ): RedirectResponse {

        abort_unless(
            $contract->procurement_tender_id
            === $procurementTender->id,
            404
        );


        if ($contract->status !== 'Completed') {

            return back()->with(
                'error',
                'Only Completed contracts can be closed.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Milestones
        |--------------------------------------------------------------------------
        */

        $milestones = $contract
            ->milestones()
            ->get();


        if ($milestones->isEmpty()) {

            return back()->with(
                'error',
                'Contract cannot be closed because no milestones have been created.'
            );
        }


        $incompleteMilestones =
            $milestones->filter(
                function ($milestone) {

                    return !(
                        $milestone->status === 'Completed'
                        &&
                        (float)
                        $milestone->progress_percentage >= 100
                    );

                }
            );


        if ($incompleteMilestones->isNotEmpty()) {

            $milestoneNumbers =
                $incompleteMilestones
                    ->pluck('milestone_number')
                    ->filter()
                    ->implode(', ');


            return back()->with(
                'error',
                'Contract cannot be closed because the following milestone(s) are not completed: '
                . ($milestoneNumbers ?: 'Incomplete milestone(s)')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Invoices
        |--------------------------------------------------------------------------
        */

        $invoices = $contract
            ->invoices()
            ->with('payments')
            ->get();


        foreach ($invoices as $invoice) {

            $processedAmount =
                (float)
                $invoice
                    ->payments
                    ->where('status', 'Processed')
                    ->sum('amount');


            $invoiceAmount =
                (float)
                $invoice->net_amount;


            $balanceAmount =
                max(
                    0,
                    $invoiceAmount - $processedAmount
                );


            if ($balanceAmount > 0) {

                return back()->with(
                    'error',
                    'Contract cannot be closed because invoice '
                    . $invoice->invoice_number
                    . ' has an outstanding balance of '
                    . number_format(
                        $balanceAmount,
                        2
                    )
                    . ' '
                    . $invoice->currency
                    . '.'
                );
            }


            $pendingPayment =
                $invoice
                    ->payments
                    ->whereIn(
                        'status',
                        [
                            'Draft',
                            'Submitted',
                            'Approved',
                        ]
                    )
                    ->first();


            if ($pendingPayment) {

                return back()->with(
                    'error',
                    'Contract cannot be closed because payment '
                    . $pendingPayment->payment_number
                    . ' is still '
                    . $pendingPayment->status
                    . '.'
                );
            }

        }


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'completion_date' => [
                'required',
                'date',
            ],

            'closure_remarks' => [
                'required',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Close
        |--------------------------------------------------------------------------
        */

        $contract->update([

            'status' =>
                'Closed',

            'completion_date' =>
                $validated['completion_date'],

            'closed_at' =>
                now(),

            'closed_by' =>
                auth()->id(),

            'closure_remarks' =>
                $validated['closure_remarks'],

            'updated_by' =>
                auth()->id(),

        ]);


        return back()->with(
            'success',
            'Contract closed successfully.'
        );
    }
}