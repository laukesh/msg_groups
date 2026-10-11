<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Project;
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
    | Ensure Tender Belongs To Project
    |--------------------------------------------------------------------------
    */

    private function ensureTenderBelongsToProject(
        Project $project,
        ProcurementTender $procurementTender
    ): void {
        abort_unless(
            (int) $procurementTender->project_id === (int) $project->id,
            404
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Ensure Tender Bidder Belongs To Project + Tender
    |--------------------------------------------------------------------------
    */

    private function ensureTenderBidderBelongsToProject(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementTenderBidder $tenderBidder
    ): void {
        abort_unless(
            (int) $tenderBidder->project_id === (int) $project->id,
            404
        );

        abort_unless(
            (int) $tenderBidder->procurement_tender_id ===
            (int) $procurementTender->id,
            404
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(
        Project $project,
        ProcurementTender $procurementTender
    ): View {

        $this->ensureTenderBelongsToProject(
            $project,
            $procurementTender
        );

        $procurementTender->load([
            'procurementPackage.procurementPlan.project',
            'tenderBidders' => function ($query) use ($project) {
                $query->where(
                    'project_id',
                    $project->id
                );
            },
            'tenderBidders.bidder',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Already Assigned Bidders
        |--------------------------------------------------------------------------
        */

        $assignedBidderIds = ProcurementTenderBidder::query()
            ->where('project_id', $project->id)
            ->where(
                'procurement_tender_id',
                $procurementTender->id
            )
            ->pluck('procurement_bidder_id');

        /*
        |--------------------------------------------------------------------------
        | Available Bidders
        |--------------------------------------------------------------------------
        */

        $availableBidders = ProcurementBidder::query()
            ->where('status', 'Active')
            ->when(
                $assignedBidderIds->isNotEmpty(),
                function ($query) use ($assignedBidderIds) {
                    $query->whereNotIn(
                        'id',
                        $assignedBidderIds
                    );
                }
            )
            ->orderBy('company_name')
            ->get();

        return view(
            'procurement.tenders.bidders.index',
            compact(
                'project',
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
        Project $project,
        ProcurementTender $procurementTender
    ): RedirectResponse {

        $this->ensureTenderBelongsToProject(
            $project,
            $procurementTender
        );

        /*
        |--------------------------------------------------------------------------
        | Tender Award Lock
        |--------------------------------------------------------------------------
        */

        $tenderAwarded = $procurementTender
            ->awards()
            ->whereIn('status', [
                'Approved',
                'LOA Issued',
            ])
            ->exists();

        if ($tenderAwarded) {

            return back()->with(
                'error',
                'This Tender has already been awarded. New bidders cannot be added.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Request
        |--------------------------------------------------------------------------
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
        | Verify Bidder Exists
        |--------------------------------------------------------------------------
        */

        $bidderExists = ProcurementBidder::query()
            ->where('id', $validated['procurement_bidder_id'])
            ->where('status', 'Active')
            ->exists();

        if (! $bidderExists) {

            return back()
                ->withInput()
                ->withErrors([
                    'procurement_bidder_id' =>
                        'Selected bidder is not active or does not exist.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Verify Bidder Is Not Already Assigned
        |--------------------------------------------------------------------------
        */

        $exists = ProcurementTenderBidder::query()
            ->where(
                'project_id',
                $project->id
            )
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
        */

        $year = now()->format('Y');

        $lastReference = ProcurementTenderBidder::query()
            ->where(
                'project_id',
                $project->id
            )
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
        */

        while (
            ProcurementTenderBidder::query()
                ->where(
                    'project_id',
                    $project->id
                )
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

            'project_id' =>
                $project->id,

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

        return redirect()->route(
            'admin.projects.procurement.tenders.bidders.index',
            [
                'project' => $project,
                'procurementTender' => $procurementTender,
            ]
        )->with(
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
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementTenderBidder $tenderBidder
    ): RedirectResponse {

        $this->ensureTenderBelongsToProject(
            $project,
            $procurementTender
        );

        $this->ensureTenderBidderBelongsToProject(
            $project,
            $procurementTender,
            $tenderBidder
        );

        /*
        |--------------------------------------------------------------------------
        | Validate
        |--------------------------------------------------------------------------
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
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementTenderBidder $tenderBidder
    ): RedirectResponse {

        $this->ensureTenderBelongsToProject(
            $project,
            $procurementTender
        );

        $this->ensureTenderBidderBelongsToProject(
            $project,
            $procurementTender,
            $tenderBidder
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