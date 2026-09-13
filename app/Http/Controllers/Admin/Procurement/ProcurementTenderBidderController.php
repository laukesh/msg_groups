<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\ProcurementBidder;
use App\Models\ProcurementTender;
use App\Models\ProcurementTenderBidder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProcurementTenderBidderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(
        ProcurementTender $procurementTender
    ): View {

        $procurementTender->load([
            'procurementPackage.procurementPlan.project',
            'tenderBidders.bidder',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Already Assigned Bidders
        |--------------------------------------------------------------------------
        */

        $assignedBidderIds = $procurementTender
            ->tenderBidders()
            ->pluck('procurement_bidder_id');


        /*
        |--------------------------------------------------------------------------
        | Available Bidders
        |--------------------------------------------------------------------------
        */

        $availableBidders = ProcurementBidder::query()
            ->where('status', 'Active')
            ->whereNotIn(
                'id',
                $assignedBidderIds
            )
            ->orderBy('company_name')
            ->get();


        return view(
            'procurement.tenders.bidders.index',
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
        | Tender Award Lock
        |--------------------------------------------------------------------------
        |
        | Once an Award has been approved, no new bidder
        | can be added to the Tender.
        |
        */


        $tenderAwarded = $procurementTender
                    ->awards()
                    ->whereIn('status', [
                        'Approved',
                        'LOA Issued',
                    ])
                    ->exists();
        //echo "<pre>";print_r($tenderAwarded);die();

        if ($tenderAwarded) {

            return back()
                ->with(
                    'error',
                    'This Tender has already been awarded. New bidders cannot be added.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Request
        |--------------------------------------------------------------------------
        |
        | bidder_reference_no is NOT accepted from the user.
        | It will be generated automatically below.
        |
        */

        $validated = $request->validate([

            'procurement_bidder_id' => [
                'required',
                'integer',
                'exists:procurement_bidders,id',
            ],

            'invitation_date' => [
                'nullable',
                'date',
            ],

            'registration_date' => [
                'nullable',
                'date',
            ],

            'participation_status' => [
                'required',
                'string',
                'max:50',
            ],

            'prequalification_required' => [
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
        | Verify Bidder Is Not Already Assigned
        |--------------------------------------------------------------------------
        */

        $exists = ProcurementTenderBidder::query()
            ->where(
                'procurement_tender_id',
                $procurementTender->id
            )
            ->where(
                'procurement_bidder_id',
                $validated['procurement_bidder_id']
            )
            ->exists();


        if ($exists) {

            return back()
                ->withInput()
                ->withErrors([
                    'procurement_bidder_id' =>
                        'This bidder is already assigned to this Tender.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Generate Bidder Reference Number
        |--------------------------------------------------------------------------
        |
        | Example:
        |
        | TBR-2026-0001
        | TBR-2026-0002
        | TBR-2026-0003
        |
        */

        $year = now()->format('Y');


        $lastReference = ProcurementTenderBidder::query()
            ->where(
                'bidder_reference_no',
                'like',
                'TBR-' . $year . '-%'
            )
            ->orderByDesc('id')
            ->first();


        if ($lastReference) {

            $lastNumber = (int) str_replace(
                'TBR-' . $year . '-',
                '',
                $lastReference->bidder_reference_no
            );

            $nextNumber = $lastNumber + 1;

        } else {

            $nextNumber = 1;
        }


        $bidderReferenceNo =
            'TBR-' .
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
        |
        | Make sure generated reference is unique.
        |
        */

        while (
            ProcurementTenderBidder::query()
                ->where(
                    'bidder_reference_no',
                    $bidderReferenceNo
                )
                ->exists()
        ) {

            $nextNumber++;


            $bidderReferenceNo =
                'TBR-' .
                $year .
                '-' .
                str_pad(
                    $nextNumber,
                    4,
                    '0',
                    STR_PAD_LEFT
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Create Tender Bidder
        |--------------------------------------------------------------------------
        */

        ProcurementTenderBidder::create([

            'procurement_tender_id' =>
                $procurementTender->id,

            'procurement_bidder_id' =>
                $validated['procurement_bidder_id'],

            'bidder_reference_no' =>
                $bidderReferenceNo,

            'invitation_date' =>
                $validated['invitation_date']
                ?? null,

            'registration_date' =>
                $validated['registration_date']
                ?? null,

            'participation_status' =>
                $validated['participation_status'],

            'prequalification_required' =>
                $request->boolean(
                    'prequalification_required'
                ),

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

        return back()->with(
            'success',
            'Bidder added to Tender successfully. Reference No.: '
            . $bidderReferenceNo
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
        ProcurementTenderBidder $tenderBidder
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Ensure Tender Bidder Belongs To Tender
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $tenderBidder->procurement_tender_id
                === $procurementTender->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Validate
        |--------------------------------------------------------------------------
        |
        | bidder_reference_no is intentionally NOT included.
        | Existing reference number cannot be changed.
        |
        */

        $validated = $request->validate([

            'invitation_date' => [
                'nullable',
                'date',
            ],

            'registration_date' => [
                'nullable',
                'date',
            ],

            'participation_status' => [
                'required',
                'string',
                'max:50',
            ],

            'prequalification_required' => [
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
        | Update Tender Bidder
        |--------------------------------------------------------------------------
        */

        $tenderBidder->update([

            'invitation_date' =>
                $validated['invitation_date']
                ?? null,

            'registration_date' =>
                $validated['registration_date']
                ?? null,

            'participation_status' =>
                $validated['participation_status'],

            'prequalification_required' =>
                $request->boolean(
                    'prequalification_required'
                ),

            'remarks' =>
                $validated['remarks']
                ?? null,

            'updated_by' =>
                auth()->id(),
        ]);


        return back()->with(
            'success',
            'Tender bidder updated successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy(
        ProcurementTender $procurementTender,
        ProcurementTenderBidder $tenderBidder
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Ensure Tender Bidder Belongs To Tender
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $tenderBidder->procurement_tender_id
                === $procurementTender->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Delete
        |--------------------------------------------------------------------------
        */

        $tenderBidder->delete();


        return back()->with(
            'success',
            'Bidder removed from Tender successfully.'
        );
    }
}