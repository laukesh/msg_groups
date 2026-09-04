<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\ProcurementTender;
use App\Models\ProcurementTenderBidder;
use App\Models\ProcurementTenderSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProcurementTenderSubmissionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(
        ProcurementTender $procurementTender
    ): View {

        $submissions = $procurementTender
            ->submissions()
            ->with('tenderBidder.bidder')
            ->latest()
            ->get();


        return view(
            'procurement.tender-submissions.index',
            compact(
                'procurementTender',
                'submissions'
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
        | Get Tender Bidders Who Do Not Have A Submission
        |--------------------------------------------------------------------------
        */

        $assignedSubmissionIds = $procurementTender
            ->submissions()
            ->pluck('procurement_tender_bidder_id');


        $availableBidders = $procurementTender
            ->tenderBidders()
            ->with('bidder')
            ->whereNotIn(
                'id',
                $assignedSubmissionIds
            )
            ->get();


        return view(
            'procurement.tender-submissions.create',
            compact(
                'procurementTender',
                'availableBidders'
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
        | Validate Request
        |--------------------------------------------------------------------------
        |
        | submission_number is intentionally NOT accepted from the user.
        | It is generated automatically below.
        |
        */

        $validated = $request->validate([

            'procurement_tender_bidder_id' => [
                'required',
                'integer',
                'exists:procurement_tender_bidders,id',
            ],

            'submission_date' => [
                'nullable',
                'date',
            ],

            'bid_validity_days' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'bid_valid_until' => [
                'nullable',
                'date',
            ],

            'quoted_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'currency' => [
                'required',
                'string',
                'max:10',
            ],

            'technical_submission' => [
                'nullable',
                'string',
            ],

            'commercial_submission' => [
                'nullable',
                'string',
            ],

            'compliance_declaration' => [
                'nullable',
                'string',
            ],

            'submission_status' => [
                'required',
                'in:Draft,Submitted,Under Review,Accepted,Rejected,Withdrawn',
            ],

            'is_complete' => [
                'nullable',
                'boolean',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Make Sure Selected Bidder Belongs To This Tender
        |--------------------------------------------------------------------------
        */

        $tenderBidder = ProcurementTenderBidder::query()
            ->where(
                'id',
                $validated['procurement_tender_bidder_id']
            )
            ->where(
                'procurement_tender_id',
                $procurementTender->id
            )
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Submission
        |--------------------------------------------------------------------------
        */

        $alreadyExists = ProcurementTenderSubmission::query()
            ->where(
                'procurement_tender_id',
                $procurementTender->id
            )
            ->where(
                'procurement_tender_bidder_id',
                $tenderBidder->id
            )
            ->exists();


        if ($alreadyExists) {

            return back()
                ->withInput()
                ->withErrors([
                    'procurement_tender_bidder_id' =>
                        'A submission already exists for this bidder in this Tender.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Generate Submission Number
        |--------------------------------------------------------------------------
        |
        | Example:
        |
        | SUB-2026-0001
        | SUB-2026-0002
        | SUB-2026-0003
        |
        */

        $submissionNumber =
            $this->generateSubmissionNumber();


        /*
        |--------------------------------------------------------------------------
        | Create Submission
        |--------------------------------------------------------------------------
        */

        $submission = ProcurementTenderSubmission::create([

            'procurement_tender_id' =>
                $procurementTender->id,

            'procurement_tender_bidder_id' =>
                $tenderBidder->id,

            'submission_number' =>
                $submissionNumber,

            'submission_date' =>
                $validated['submission_date']
                ?? null,

            'bid_validity_days' =>
                $validated['bid_validity_days']
                ?? null,

            'bid_valid_until' =>
                $validated['bid_valid_until']
                ?? null,

            'quoted_amount' =>
                $validated['quoted_amount'],

            'currency' =>
                $validated['currency'],

            'technical_submission' =>
                $validated['technical_submission']
                ?? null,

            'commercial_submission' =>
                $validated['commercial_submission']
                ?? null,

            'compliance_declaration' =>
                $validated['compliance_declaration']
                ?? null,

            'submission_status' =>
                $validated['submission_status'],

            'is_complete' =>
                $request->boolean('is_complete'),

            'submitted_by' =>
                auth()->id(),

            'submitted_by_name' =>
                auth()->user()?->name,

            'remarks' =>
                $validated['remarks']
                ?? null,

            'created_by' =>
                auth()->id(),
        ]);


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.procurement.tenders.submissions.show',
                [
                    'procurementTender' =>
                        $procurementTender,

                    'submission' =>
                        $submission,
                ]
            )
            ->with(
                'success',
                'Tender submission created successfully. Submission No.: '
                . $submissionNumber
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Submission Number
    |--------------------------------------------------------------------------
    */

    private function generateSubmissionNumber(): string
    {
        $year = now()->format('Y');


        /*
        |--------------------------------------------------------------------------
        | Find Latest Submission Number For Current Year
        |--------------------------------------------------------------------------
        */

        $lastSubmission = ProcurementTenderSubmission::query()
            ->where(
                'submission_number',
                'like',
                'SUB-' . $year . '-%'
            )
            ->orderByDesc('id')
            ->first();


        /*
        |--------------------------------------------------------------------------
        | Calculate Next Number
        |--------------------------------------------------------------------------
        */

        if ($lastSubmission) {

            $lastNumber = (int) str_replace(
                'SUB-' . $year . '-',
                '',
                $lastSubmission->submission_number
            );


            $nextNumber = $lastNumber + 1;

        } else {

            $nextNumber = 1;
        }


        /*
        |--------------------------------------------------------------------------
        | Build Submission Number
        |--------------------------------------------------------------------------
        */

        $submissionNumber =
            'SUB-' .
            $year .
            '-' .
            str_pad(
                $nextNumber,
                4,
                '0',
                STR_PAD_LEFT
            );


        /*
        |--------------------------------------------------------------------------
        | Safety Check
        |--------------------------------------------------------------------------
        */

        while (
            ProcurementTenderSubmission::query()
                ->where(
                    'submission_number',
                    $submissionNumber
                )
                ->exists()
        ) {

            $nextNumber++;


            $submissionNumber =
                'SUB-' .
                $year .
                '-' .
                str_pad(
                    $nextNumber,
                    4,
                    '0',
                    STR_PAD_LEFT
                );
        }


        return $submissionNumber;
    }


    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(
        ProcurementTender $procurementTender,
        ProcurementTenderSubmission $submission
    ): View {

        abort_unless(
            $submission->procurement_tender_id
                === $procurementTender->id,
            404
        );


        $submission->load([
            'tender',
            'tenderBidder.bidder',
        ]);


        return view(
            'procurement.tender-submissions.show',
            compact(
                'procurementTender',
                'submission'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(
        ProcurementTender $procurementTender,
        ProcurementTenderSubmission $submission
    ): View {

        abort_unless(
            $submission->procurement_tender_id
                === $procurementTender->id,
            404
        );


        $submission->load([
            'tenderBidder.bidder',
        ]);


        return view(
            'procurement.tender-submissions.edit',
            compact(
                'procurementTender',
                'submission'
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
        ProcurementTender $procurementTender,
        ProcurementTenderSubmission $submission
    ): RedirectResponse {

        abort_unless(
            $submission->procurement_tender_id
                === $procurementTender->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Validate
        |--------------------------------------------------------------------------
        |
        | submission_number is NOT validated here because it must never
        | be changed after creation.
        |
        */

        $validated = $request->validate([

            'submission_date' => [
                'nullable',
                'date',
            ],

            'bid_validity_days' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'bid_valid_until' => [
                'nullable',
                'date',
            ],

            'quoted_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'currency' => [
                'required',
                'string',
                'max:10',
            ],

            'technical_submission' => [
                'nullable',
                'string',
            ],

            'commercial_submission' => [
                'nullable',
                'string',
            ],

            'compliance_declaration' => [
                'nullable',
                'string',
            ],

            'submission_status' => [
                'required',
                'in:Draft,Submitted,Under Review,Accepted,Rejected,Withdrawn',
            ],

            'is_complete' => [
                'nullable',
                'boolean',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Update Submission
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | submission_number is intentionally not included.
        |
        */

        $submission->update([

            'submission_date' =>
                $validated['submission_date']
                ?? null,

            'bid_validity_days' =>
                $validated['bid_validity_days']
                ?? null,

            'bid_valid_until' =>
                $validated['bid_valid_until']
                ?? null,

            'quoted_amount' =>
                $validated['quoted_amount'],

            'currency' =>
                $validated['currency'],

            'technical_submission' =>
                $validated['technical_submission']
                ?? null,

            'commercial_submission' =>
                $validated['commercial_submission']
                ?? null,

            'compliance_declaration' =>
                $validated['compliance_declaration']
                ?? null,

            'submission_status' =>
                $validated['submission_status'],

            'is_complete' =>
                $request->boolean('is_complete'),

            'remarks' =>
                $validated['remarks']
                ?? null,

            'updated_by' =>
                auth()->id(),
        ]);


        return redirect()
            ->route(
                'admin.procurement.tenders.submissions.show',
                [
                    'procurementTender' =>
                        $procurementTender,

                    'submission' =>
                        $submission,
                ]
            )
            ->with(
                'success',
                'Tender submission updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy(
        ProcurementTender $procurementTender,
        ProcurementTenderSubmission $submission
    ): RedirectResponse {

        abort_unless(
            $submission->procurement_tender_id
                === $procurementTender->id,
            404
        );


        $submission->delete();


        return redirect()
            ->route(
                'admin.procurement.tenders.submissions.index',
                $procurementTender
            )
            ->with(
                'success',
                'Tender submission deleted successfully.'
            );
    }
}