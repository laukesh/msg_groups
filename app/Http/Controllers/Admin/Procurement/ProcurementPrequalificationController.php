<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\ProcurementPrequalification;
use App\Models\ProcurementTender;
use App\Models\ProcurementTenderBidder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProcurementPrequalificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        ProcurementTender $procurementTender
    ): View {

        $procurementTender->load([
            'procurementPackage',
            'prequalifications.tenderBidder.bidder',
        ]);

        return view(
            'procurement.prequalifications.index',
            compact('procurementTender')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
        Request $request,
        ProcurementTender $procurementTender
    ): View {

        $procurementTender->load([
            'procurementPackage',
            'tenderBidders.bidder',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Selected Tender Bidder
        |--------------------------------------------------------------------------
        */

        $selectedTenderBidder = null;


        if ($request->filled('tender_bidder_id')) {

            $selectedTenderBidder =
                ProcurementTenderBidder::query()
                    ->with('bidder')
                    ->where(
                        'id',
                        $request->tender_bidder_id
                    )
                    ->where(
                        'procurement_tender_id',
                        $procurementTender->id
                    )
                    ->firstOrFail();


            /*
            |--------------------------------------------------------------------------
            | Check Existing Prequalification
            |--------------------------------------------------------------------------
            */

            $existingPrequalification =
                ProcurementPrequalification::query()
                    ->where(
                        'procurement_tender_id',
                        $procurementTender->id
                    )
                    ->where(
                        'procurement_tender_bidder_id',
                        $selectedTenderBidder->id
                    )
                    ->first();


            if ($existingPrequalification) {

                return redirect()
                    ->route(
                        'admin.procurement.tenders.prequalifications.show',
                        [
                            'procurementTender' =>
                                $procurementTender,

                            'prequalification' =>
                                $existingPrequalification,
                        ]
                    )
                    ->with(
                        'info',
                        'Prequalification already exists for this bidder.'
                    );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Existing Prequalification Bidder IDs
        |--------------------------------------------------------------------------
        */

        $assignedBidderIds =
            $procurementTender
                ->prequalifications()
                ->pluck(
                    'procurement_tender_bidder_id'
                );


        /*
        |--------------------------------------------------------------------------
        | Available Bidders
        |--------------------------------------------------------------------------
        */

        $availableBidders =
            $procurementTender
                ->tenderBidders()
                ->with('bidder')
                ->whereNotIn(
                    'id',
                    $assignedBidderIds
                )
                ->get();


        return view(
            'procurement.prequalifications.create',
            compact(
                'procurementTender',
                'availableBidders',
                'selectedTenderBidder'
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

            'procurement_tender_bidder_id' => [
                'required',
                'integer',
                'exists:procurement_tender_bidders,id',
            ],

            'submission_date' => [
                'nullable',
                'date',
            ],

            'evaluation_date' => [
                'nullable',
                'date',
            ],

            'evaluator_user_id' => [
                'nullable',
                'integer',
            ],

            'evaluator_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                'in:Draft,Submitted,Under Evaluation',
            ],

            'evaluation_summary' => [
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
        | Verify Tender Bidder
        |--------------------------------------------------------------------------
        */

        $tenderBidder =
            ProcurementTenderBidder::query()
                ->where(
                    'id',
                    $validated[
                        'procurement_tender_bidder_id'
                    ]
                )
                ->where(
                    'procurement_tender_id',
                    $procurementTender->id
                )
                ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Prequalification
        |--------------------------------------------------------------------------
        */

        $exists =
            ProcurementPrequalification::query()
                ->where(
                    'procurement_tender_id',
                    $procurementTender->id
                )
                ->where(
                    'procurement_tender_bidder_id',
                    $tenderBidder->id
                )
                ->exists();


        if ($exists) {

            return back()
                ->withInput()
                ->withErrors([
                    'procurement_tender_bidder_id' =>
                        'A prequalification already exists for this bidder in this Tender.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Generate Prequalification Number
        |--------------------------------------------------------------------------
        |
        | Format:
        |
        | PQ-2026-001
        | PQ-2026-002
        | PQ-2026-003
        |
        */

        $year = now()->format('Y');

        $prefix = 'PQ-' . $year . '-';


        $lastPrequalification =
            ProcurementPrequalification::query()
                ->where(
                    'prequalification_no',
                    'like',
                    $prefix . '%'
                )
                ->orderByDesc('id')
                ->first();


        $nextNumber = 1;


        if ($lastPrequalification) {

            $lastNumber =
                (int) str_replace(
                    $prefix,
                    '',
                    $lastPrequalification->prequalification_no
                );

            $nextNumber =
                $lastNumber + 1;
        }


        $prequalificationNumber =
            $prefix .
            str_pad(
                $nextNumber,
                3,
                '0',
                STR_PAD_LEFT
            );


        /*
        |--------------------------------------------------------------------------
        | Create Prequalification
        |--------------------------------------------------------------------------
        */

        $prequalification =
            ProcurementPrequalification::create([

                'procurement_tender_id' =>
                    $procurementTender->id,

                'procurement_tender_bidder_id' =>
                    $tenderBidder->id,

                'prequalification_no' =>
                    $prequalificationNumber,

                'submission_date' =>
                    $validated['submission_date'] ?? null,

                'evaluation_date' =>
                    $validated['evaluation_date'] ?? null,

                'evaluator_user_id' =>
                    $validated['evaluator_user_id'] ?? null,

                'evaluator_name' =>
                    $validated['evaluator_name'] ?? null,

                'status' =>
                    $validated['status'],

                'evaluation_summary' =>
                    $validated['evaluation_summary'] ?? null,

                'remarks' =>
                    $validated['remarks'] ?? null,

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
                'admin.procurement.tenders.prequalifications.show',
                [
                    'procurementTender' =>
                        $procurementTender,

                    'prequalification' =>
                        $prequalification,
                ]
            )
            ->with(
                'success',
                'Prequalification ' .
                $prequalificationNumber .
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
        ProcurementPrequalification $prequalification
    ): View {

        abort_unless(
            $prequalification->procurement_tender_id
                === $procurementTender->id,
            404
        );


        $prequalification->load([
            'tender.procurementPackage',
            'tenderBidder.bidder',
            'criteria',
        ]);


        return view(
            'procurement.prequalifications.show',
            compact(
                'procurementTender',
                'prequalification'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        ProcurementTender $procurementTender,
        ProcurementPrequalification $prequalification
    ): View {

        abort_unless(
            $prequalification->procurement_tender_id
                === $procurementTender->id,
            404
        );


        $prequalification->load([
            'tenderBidder.bidder',
        ]);


        return view(
            'procurement.prequalifications.edit',
            compact(
                'procurementTender',
                'prequalification'
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
        ProcurementTender $procurementTender,
        ProcurementPrequalification $prequalification
    ): RedirectResponse {

        abort_unless(
            $prequalification->procurement_tender_id
                === $procurementTender->id,
            404
        );


        $validated = $request->validate([

            'submission_date' => [
                'nullable',
                'date',
            ],

            'evaluation_date' => [
                'nullable',
                'date',
            ],

            'evaluator_user_id' => [
                'nullable',
                'integer',
            ],

            'evaluator_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                'in:Draft,Submitted,Under Evaluation',
            ],

            'evaluation_summary' => [
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
        | Update
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | prequalification_no is NOT updated.
        |
        */

        $prequalification->update([

            'submission_date' =>
                $validated['submission_date'] ?? null,

            'evaluation_date' =>
                $validated['evaluation_date'] ?? null,

            'evaluator_user_id' =>
                $validated['evaluator_user_id'] ?? null,

            'evaluator_name' =>
                $validated['evaluator_name'] ?? null,

            'status' =>
                $validated['status'],

            'evaluation_summary' =>
                $validated['evaluation_summary'] ?? null,

            'remarks' =>
                $validated['remarks'] ?? null,

            'updated_by' =>
                auth()->id(),
        ]);


        return redirect()
            ->route(
                'admin.procurement.tenders.prequalifications.show',
                [
                    'procurementTender' =>
                        $procurementTender,

                    'prequalification' =>
                        $prequalification,
                ]
            )
            ->with(
                'success',
                'Prequalification updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        ProcurementTender $procurementTender,
        ProcurementPrequalification $prequalification
    ): RedirectResponse {

        abort_unless(
            $prequalification->procurement_tender_id
                === $procurementTender->id,
            404
        );


        $prequalification->delete();


        return redirect()
            ->route(
                'admin.procurement.tenders.prequalifications.index',
                $procurementTender
            )
            ->with(
                'success',
                'Prequalification deleted successfully.'
            );
    }
}