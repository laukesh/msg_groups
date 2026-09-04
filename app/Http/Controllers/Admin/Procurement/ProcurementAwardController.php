<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\ProcurementAward;
use App\Models\ProcurementNegotiation;
use App\Models\ProcurementTender;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProcurementAwardController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(
        ProcurementTender $procurementTender
    ): View {

        $awards = ProcurementAward::query()
            ->where(
                'procurement_tender_id',
                $procurementTender->id
            )
            ->with([
                'negotiation',
                'submission',
            ])
            ->latest('id')
            ->get();

        return view(
            'procurement.awards.index',
            compact(
                'procurementTender',
                'awards'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(
        ProcurementTender $procurementTender
    ): View {

        /*
        |--------------------------------------------------------------------------
        | Tender Award Lock
        |--------------------------------------------------------------------------
        */

        $loaIssued = $procurementTender
            ->awards()
            ->whereIn('status', [
                'LOA Issued',
            ])
            ->exists();


        if ($loaIssued) {

            abort(
                403,
                'A new Award cannot be created because the Tender LOA has already been issued.'
            );
        }

        /*
         * Only Approved Negotiations
         * are eligible for Award.
         */

        $negotiations = ProcurementNegotiation::query()
            ->where(
                'procurement_tender_id',
                $procurementTender->id
            )
            ->where(
                'status',
                'Approved'
            )
            ->with([
                'submission.tenderBidder.bidder',
            ])
            ->latest('id')
            ->get();


        return view(
            'procurement.awards.create',
            compact(
                'procurementTender',
                'negotiations'
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
        ProcurementTender $procurementTender
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Tender Award Lock
        |--------------------------------------------------------------------------
        */

        $loaIssued = $procurementTender
            ->awards()
            ->whereIn('status', [
                'LOA Issued',
            ])
            ->exists();


        if ($loaIssued) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'A new Award cannot be created because the Tender LOA has already been issued.'
                );
        }

        $validated = $request->validate([

            'procurement_negotiation_id' => [
                'required',
                'integer',
                'exists:procurement_negotiations,id',
            ],

            'award_title' => [
                'required',
                'string',
                'max:255',
            ],

            'award_date' => [
                'nullable',
                'date',
            ],

            'award_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'loa_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'loa_date' => [
                'nullable',
                'date',
            ],

            'acceptance_deadline' => [
                'nullable',
                'date',
            ],

            'contract_required' => [
                'nullable',
                'boolean',
            ],

            'responsible_user_id' => [
                'nullable',
                'integer',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'terms_and_conditions' => [
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
        | Get Approved Negotiation
        |--------------------------------------------------------------------------
        */

        $negotiation = ProcurementNegotiation::query()
            ->where(
                'id',
                $validated['procurement_negotiation_id']
            )
            ->where(
                'procurement_tender_id',
                $procurementTender->id
            )
            ->where(
                'status',
                'Approved'
            )
            ->with([
                'submission.tenderBidder.bidder',
            ])
            ->first();


        if (!$negotiation) {

            return back()
                ->withInput()
                ->withErrors([
                    'procurement_negotiation_id' =>
                        'Only an Approved Negotiation belonging to this Tender can be awarded.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent duplicate award
        |--------------------------------------------------------------------------
        */

        $existingAward = ProcurementAward::query()
            ->where(
                'procurement_negotiation_id',
                $negotiation->id
            )
            ->first();


        if ($existingAward) {

            return back()
                ->withInput()
                ->withErrors([
                    'procurement_negotiation_id' =>
                        'An Award already exists for this Negotiation.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Get Tender Submission
        |--------------------------------------------------------------------------
        */

        $submission =
            $negotiation->submission;


        if (!$submission) {

            return back()
                ->withInput()
                ->withErrors([
                    'procurement_negotiation_id' =>
                        'The approved negotiation has no tender submission.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Bidder Name
        |--------------------------------------------------------------------------
        */

        $bidderName =
            $negotiation->bidder_name
            ?? $submission
                ->tenderBidder
                ?->bidder
                ?->company_name
            ?? 'Unknown Bidder';


        /*
        |--------------------------------------------------------------------------
        | Generate Award Number
        |--------------------------------------------------------------------------
        |
        | Format:
        |
        | AWD-2026-001
        | AWD-2026-002
        | AWD-2026-003
        |
        */

        $year = now()->format('Y');


        $lastAward = ProcurementAward::query()
            ->where(
                'award_number',
                'like',
                'AWD-' . $year . '-%'
            )
            ->latest('id')
            ->first();


        if ($lastAward) {

            $lastNumber = (int) substr(
                $lastAward->award_number,
                strrpos(
                    $lastAward->award_number,
                    '-'
                ) + 1
            );

            $nextNumber = $lastNumber + 1;

        } else {

            $nextNumber = 1;
        }


        $awardNumber =
            'AWD-'
            . $year
            . '-'
            . str_pad(
                $nextNumber,
                3,
                '0',
                STR_PAD_LEFT
            );


        /*
        |--------------------------------------------------------------------------
        | Create Award
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $validated,
            $procurementTender,
            $negotiation,
            $submission,
            $bidderName,
            $awardNumber
        ) {

            ProcurementAward::create([

                'procurement_tender_id' =>
                    $procurementTender->id,

                'procurement_negotiation_id' =>
                    $negotiation->id,

                'procurement_tender_submission_id' =>
                    $submission->id,


                /*
                |--------------------------------------------------------------------------
                | Auto Generated
                |--------------------------------------------------------------------------
                */

                'award_number' =>
                    $awardNumber,


                'award_title' =>
                    $validated['award_title'],


                'award_date' =>
                    $validated['award_date']
                    ?? now()->format('Y-m-d'),


                'bidder_name' =>
                    $bidderName,


                /*
                |--------------------------------------------------------------------------
                | Amount comes from Approved Negotiation
                |--------------------------------------------------------------------------
                */

                'awarded_amount' =>
                    $negotiation->final_amount,

                'currency' =>
                    $negotiation->currency,


                'award_type' =>
                    $validated['award_type']
                    ?? 'Letter of Award',


                /*
                |--------------------------------------------------------------------------
                | Initial Status
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
                    $validated['loa_number']
                    ?? null,

                'loa_date' =>
                    $validated['loa_date']
                    ?? null,

                'acceptance_deadline' =>
                    $validated['acceptance_deadline']
                    ?? null,


                /*
                |--------------------------------------------------------------------------
                | Contract
                |--------------------------------------------------------------------------
                */

                'contract_required' =>
                    $validated['contract_required']
                    ?? true,


                'responsible_user_id' =>
                    $validated['responsible_user_id']
                    ?? null,


                /*
                |--------------------------------------------------------------------------
                | Audit
                |--------------------------------------------------------------------------
                */

                'issued_by' =>
                    auth()->id(),

                'created_by' =>
                    auth()->id(),


                /*
                |--------------------------------------------------------------------------
                | Description
                |--------------------------------------------------------------------------
                */

                'description' =>
                    $validated['description']
                    ?? null,

                'terms_and_conditions' =>
                    $validated['terms_and_conditions']
                    ?? null,

                'remarks' =>
                    $validated['remarks']
                    ?? null,
            ]);
        });


        return redirect()
            ->route(
                'admin.procurement.tenders.awards.index',
                $procurementTender
            )
            ->with(
                'success',
                'Award '
                . $awardNumber
                . ' created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(
        ProcurementTender $procurementTender,
        ProcurementAward $award
    ): View {

        abort_unless(
            $award->procurement_tender_id
            === $procurementTender->id,
            404
        );


        $award->load([
            'tender',
            'negotiation',
            'submission',
        ]);


        return view(
            'procurement.awards.show',
            compact(
                'procurementTender',
                'award'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Submit
    |--------------------------------------------------------------------------
    */

    public function submit(
        ProcurementTender $procurementTender,
        ProcurementAward $award
    ): RedirectResponse {

        abort_unless(
            $award->procurement_tender_id
            === $procurementTender->id,
            404
        );


        if ($award->status !== 'Draft') {

            return back()->with(
                'error',
                'Only Draft Awards can be submitted.'
            );
        }


        $award->update([

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
            'Award submitted for approval successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Approve
    |--------------------------------------------------------------------------
    */

    public function approve(
        Request $request,
        ProcurementTender $procurementTender,
        ProcurementAward $award
    ): RedirectResponse {

        abort_unless(
            $award->procurement_tender_id
            === $procurementTender->id,
            404
        );


        if ($award->status !== 'Under Review') {

            return back()->with(
                'error',
                'Only Awards under review can be approved.'
            );
        }


        $validated = $request->validate([
            'approval_remarks' => [
                'nullable',
                'string',
            ],
        ]);


        $award->update([

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
            'Award approved successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Issue LOA
    |--------------------------------------------------------------------------
    */

    public function issueLoa(
        Request $request,
        ProcurementTender $procurementTender,
        ProcurementAward $award
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Verify Award belongs to Tender
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $award->procurement_tender_id
                === $procurementTender->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Only Approved Award can receive LOA
        |--------------------------------------------------------------------------
        */

        if ($award->status !== 'Approved') {

            return back()->with(
                'error',
                'Only approved Awards can have an LOA issued.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Validate LOA Details
        |--------------------------------------------------------------------------
        |
        | LOA Number is intentionally NOT accepted from the request.
        | It will be generated automatically.
        |
        */

        $validated = $request->validate([

            'loa_date' => [
                'required',
                'date',
            ],

            'acceptance_deadline' => [
                'nullable',
                'date',
                'after_or_equal:loa_date',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate LOA
        |--------------------------------------------------------------------------
        */

        if (
            !empty($award->loa_number)
            ||
            $award->status === 'LOA Issued'
        ) {

            return back()->with(
                'error',
                'An LOA has already been issued for this Award.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Generate LOA Number
        |--------------------------------------------------------------------------
        |
        | Format:
        |
        | LOA-2026-001
        | LOA-2026-002
        | LOA-2026-003
        |
        */

        $loaNumber = DB::transaction(function () use (
            $award,
            $validated
        ) {

            /*
            |--------------------------------------------------------------------------
            | Lock Award Row
            |--------------------------------------------------------------------------
            |
            | Prevents the same Award from being issued twice
            | at the same time.
            |
            */

            $lockedAward = ProcurementAward::query()
                ->where('id', $award->id)
                ->lockForUpdate()
                ->firstOrFail();


            /*
            |--------------------------------------------------------------------------
            | Double Check
            |--------------------------------------------------------------------------
            */

            if (
                !empty($lockedAward->loa_number)
                ||
                $lockedAward->status === 'LOA Issued'
            ) {

                throw new \RuntimeException(
                    'An LOA has already been issued for this Award.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Generate Next LOA Number
            |--------------------------------------------------------------------------
            */

            $year = now()->format('Y');


            $lastLoa = ProcurementAward::query()
                ->where(
                    'loa_number',
                    'like',
                    'LOA-' . $year . '-%'
                )
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();


            if ($lastLoa) {

                $lastNumber = (int) substr(
                    $lastLoa->loa_number,
                    strrpos(
                        $lastLoa->loa_number,
                        '-'
                    ) + 1
                );

                $nextNumber =
                    $lastNumber + 1;

            } else {

                $nextNumber = 1;
            }


            $loaNumber =
                'LOA-'
                . $year
                . '-'
                . str_pad(
                    $nextNumber,
                    3,
                    '0',
                    STR_PAD_LEFT
                );


            /*
            |--------------------------------------------------------------------------
            | Save LOA
            |--------------------------------------------------------------------------
            */

            $lockedAward->update([

                'loa_number' =>
                    $loaNumber,

                'loa_date' =>
                    $validated['loa_date'],

                'acceptance_deadline' =>
                    $validated['acceptance_deadline']
                    ?? null,

                'status' =>
                    'LOA Issued',

                'loa_issued_by' =>
                    auth()->id(),

                'loa_issued_at' =>
                    now(),

                'updated_by' =>
                    auth()->id(),

            ]);


            return $loaNumber;
        });


        /*
        |--------------------------------------------------------------------------
        | Success
        |--------------------------------------------------------------------------
        */

        return back()->with(
            'success',
            'Letter of Award '
            . $loaNumber
            . ' issued successfully.'
        );
    }
}