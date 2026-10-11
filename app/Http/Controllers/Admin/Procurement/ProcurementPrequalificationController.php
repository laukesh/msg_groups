<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Project;
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
    | Ensure Tender Belongs To Project
    |--------------------------------------------------------------------------
    */

    private function ensureTenderBelongsToProject(
        Project $project,
        ProcurementTender $procurementTender
    ): void {
        abort_unless(
            (int) $procurementTender->project_id ===
            (int) $project->id,
            404
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Ensure Prequalification Belongs To Project + Tender
    |--------------------------------------------------------------------------
    */

    private function ensurePrequalificationBelongsToProject(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementPrequalification $prequalification
    ): void {

        abort_unless(
            (int) $prequalification->project_id ===
            (int) $project->id,
            404
        );

        abort_unless(
            (int) $prequalification->procurement_tender_id ===
            (int) $procurementTender->id,
            404
        );
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
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
            'procurementPackage',
            'prequalifications' => function ($query) use ($project) {
                $query->where(
                    'project_id',
                    $project->id
                );
            },
            'prequalifications.tenderBidder.bidder',
        ]);

        return view(
            'procurement.prequalifications.index',
            compact(
                'project',
                'procurementTender'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
        Request $request,
        Project $project,
        ProcurementTender $procurementTender
    ): View {

        $this->ensureTenderBelongsToProject(
            $project,
            $procurementTender
        );

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
                        'project_id',
                        $project->id
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
                        'project_id',
                        $project->id
                    )
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
                        'admin.projects.procurement.tenders.prequalifications.show',
                        [
                            'project' =>
                                $project,

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
            ProcurementPrequalification::query()
                ->where(
                    'project_id',
                    $project->id
                )
                ->where(
                    'procurement_tender_id',
                    $procurementTender->id
                )
                ->pluck(
                    'procurement_tender_bidder_id'
                );

        /*
        |--------------------------------------------------------------------------
        | Available Bidders
        |--------------------------------------------------------------------------
        */

        $availableBidders =
            ProcurementTenderBidder::query()
                ->with('bidder')
                ->where(
                    'project_id',
                    $project->id
                )
                ->where(
                    'procurement_tender_id',
                    $procurementTender->id
                )
                ->when(
                    $assignedBidderIds->isNotEmpty(),
                    function ($query) use ($assignedBidderIds) {
                        $query->whereNotIn(
                            'id',
                            $assignedBidderIds
                        );
                    }
                )
                ->get();

        return view(
            'procurement.prequalifications.create',
            compact(
                'project',
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
        Project $project,
        ProcurementTender $procurementTender
    ): RedirectResponse {

        $this->ensureTenderBelongsToProject(
            $project,
            $procurementTender
        );

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
                    'project_id',
                    $project->id
                )
                ->where(
                    'procurement_tender_id',
                    $procurementTender->id
                )
                ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate
        |--------------------------------------------------------------------------
        */

        $exists =
            ProcurementPrequalification::query()
                ->where(
                    'project_id',
                    $project->id
                )
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
        */

        $year = now()->format('Y');

        $prefix = 'PQ-' . $year . '-';

        $lastPrequalification =
            ProcurementPrequalification::query()
                ->where(
                    'project_id',
                    $project->id
                )
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
        | Create
        |--------------------------------------------------------------------------
        */

        $prequalification =
            ProcurementPrequalification::create([

                'project_id' =>
                    $project->id,

                'procurement_tender_id' =>
                    $procurementTender->id,

                'procurement_tender_bidder_id' =>
                    $tenderBidder->id,

                'prequalification_no' =>
                    $prequalificationNumber,

                'submission_date' =>
                    $validated['submission_date']
                    ?? null,

                'evaluation_date' =>
                    $validated['evaluation_date']
                    ?? null,

                'evaluator_user_id' =>
                    $validated['evaluator_user_id']
                    ?? null,

                'evaluator_name' =>
                    $validated['evaluator_name']
                    ?? null,

                'status' =>
                    $validated['status'],

                'evaluation_summary' =>
                    $validated['evaluation_summary']
                    ?? null,

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
                'admin.projects.procurement.tenders.prequalifications.show',
                [
                    'project' =>
                        $project,

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
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementPrequalification $prequalification
    ): View {

        $this->ensureTenderBelongsToProject(
            $project,
            $procurementTender
        );

        $this->ensurePrequalificationBelongsToProject(
            $project,
            $procurementTender,
            $prequalification
        );

        $prequalification->load([
            'tender.procurementPackage',
            'tenderBidder.bidder',
            'criteria' => function ($query) use ($project) {
                $query->where(
                    'project_id',
                    $project->id
                );
            },
        ]);

        return view(
            'procurement.prequalifications.show',
            compact(
                'project',
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
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementPrequalification $prequalification
    ): View {

        $this->ensureTenderBelongsToProject(
            $project,
            $procurementTender
        );

        $this->ensurePrequalificationBelongsToProject(
            $project,
            $procurementTender,
            $prequalification
        );

        $prequalification->load([
            'tenderBidder.bidder',
        ]);

        return view(
            'procurement.prequalifications.edit',
            compact(
                'project',
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
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementPrequalification $prequalification
    ): RedirectResponse {

        $this->ensureTenderBelongsToProject(
            $project,
            $procurementTender
        );

        $this->ensurePrequalificationBelongsToProject(
            $project,
            $procurementTender,
            $prequalification
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

        $prequalification->update([

            'submission_date' =>
                $validated['submission_date']
                ?? null,

            'evaluation_date' =>
                $validated['evaluation_date']
                ?? null,

            'evaluator_user_id' =>
                $validated['evaluator_user_id']
                ?? null,

            'evaluator_name' =>
                $validated['evaluator_name']
                ?? null,

            'status' =>
                $validated['status'],

            'evaluation_summary' =>
                $validated['evaluation_summary']
                ?? null,

            'remarks' =>
                $validated['remarks']
                ?? null,

            'updated_by' =>
                auth()->id(),
        ]);

        return redirect()
            ->route(
                'admin.projects.procurement.tenders.prequalifications.show',
                [
                    'project' =>
                        $project,

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
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementPrequalification $prequalification
    ): RedirectResponse {

        $this->ensureTenderBelongsToProject(
            $project,
            $procurementTender
        );

        $this->ensurePrequalificationBelongsToProject(
            $project,
            $procurementTender,
            $prequalification
        );

        $prequalification->delete();

        return redirect()
            ->route(
                'admin.projects.procurement.tenders.prequalifications.index',
                [
                    'project' =>
                        $project,

                    'procurementTender' =>
                        $procurementTender,
                ]
            )
            ->with(
                'success',
                'Prequalification deleted successfully.'
            );
    }
}