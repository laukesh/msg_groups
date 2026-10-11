<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Project;
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
    | Project / Tender Validation
    |--------------------------------------------------------------------------
    */

    private function verifyTenderProject(
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
    | Award Validation
    |--------------------------------------------------------------------------
    */

    private function verifyAward(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementAward $award
    ): void {

        $this->verifyTenderProject(
            $project,
            $procurementTender
        );

        abort_unless(
            (int) $award->project_id === (int) $project->id,
            404
        );

        abort_unless(
            (int) $award->procurement_tender_id
                === (int) $procurementTender->id,
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

        $this->verifyTenderProject(
            $project,
            $procurementTender
        );

        $awards = ProcurementAward::query()
            ->where(
                'project_id',
                $project->id
            )
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
                'project',
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
        Project $project,
        ProcurementTender $procurementTender
    ): View {

        $this->verifyTenderProject(
            $project,
            $procurementTender
        );

        /*
        |--------------------------------------------------------------------------
        | Tender Award Lock
        |--------------------------------------------------------------------------
        */

        $loaIssued = $procurementTender
            ->awards()
            ->where(
                'project_id',
                $project->id
            )
            ->whereIn(
                'status',
                [
                    'LOA Issued',
                ]
            )
            ->exists();


        if ($loaIssued) {

            abort(
                403,
                'A new Award cannot be created because the Tender LOA has already been issued.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Approved Negotiations
        |--------------------------------------------------------------------------
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
                'project',
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
        Project $project,
        ProcurementTender $procurementTender
    ): RedirectResponse {

        $this->verifyTenderProject(
            $project,
            $procurementTender
        );


        /*
        |--------------------------------------------------------------------------
        | Tender Award Lock
        |--------------------------------------------------------------------------
        */

        $loaIssued = $procurementTender
            ->awards()
            ->where(
                'project_id',
                $project->id
            )
            ->where(
                'status',
                'LOA Issued'
            )
            ->exists();


        if ($loaIssued) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'A new Award cannot be created because the Tender LOA has already been issued.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Validate
        |--------------------------------------------------------------------------
        */

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

            /*
             * Award number is generated by system.
             */

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
        | Prevent Duplicate Award
        |--------------------------------------------------------------------------
        */

        $existingAward = ProcurementAward::query()
            ->where(
                'project_id',
                $project->id
            )
            ->where(
                'procurement_tender_id',
                $procurementTender->id
            )
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
        | Tender Submission
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
        | Bidder
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
        | AWD-2026-001
        | AWD-2026-002
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

            $nextNumber =
                $lastNumber + 1;

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
            $project,
            $procurementTender,
            $negotiation,
            $submission,
            $bidderName,
            $awardNumber
        ) {

            ProcurementAward::create([

                /*
                |--------------------------------------------------------------------------
                | Project Scope
                |--------------------------------------------------------------------------
                */

                'project_id' =>
                    $project->id,

                /*
                |--------------------------------------------------------------------------
                | Tender
                |--------------------------------------------------------------------------
                */

                'procurement_tender_id' =>
                    $procurementTender->id,

                /*
                |--------------------------------------------------------------------------
                | Negotiation
                |--------------------------------------------------------------------------
                */

                'procurement_negotiation_id' =>
                    $negotiation->id,

                /*
                |--------------------------------------------------------------------------
                | Submission
                |--------------------------------------------------------------------------
                */

                'procurement_tender_submission_id' =>
                    $submission->id,

                /*
                |--------------------------------------------------------------------------
                | Award
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
                | Financial
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
                'admin.projects.procurement.tenders.awards.index',
                [
                    'project' =>
                        $project,

                    'procurementTender' =>
                        $procurementTender,
                ]
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
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementAward $award
    ): View {

        $this->verifyAward(
            $project,
            $procurementTender,
            $award
        );


        abort_unless(
            in_array(
                $award->status,
                [
                    'Draft',
                    'Under Review',
                ],
                true
            ),
            403,
            'This Award cannot be edited in its current status.'
        );


        $award->load([
            'negotiation',
            'submission',
        ]);


        return view(
            'procurement.awards.edit',
            compact(
                'project',
                'procurementTender',
                'award'
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
        ProcurementTender $procurementTender,
        ProcurementAward $award
    ): RedirectResponse {

        $this->verifyAward(
            $project,
            $procurementTender,
            $award
        );


        if (!in_array(
            $award->status,
            [
                'Draft',
                'Under Review',
            ],
            true
        )) {

            return back()->with(
                'error',
                'This Award cannot be edited in its current status.'
            );
        }


        $validated = $request->validate([

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
                'after_or_equal:loa_date',
            ],

            'contract_required' => [
                'nullable',
                'boolean',
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


        $award->update([

            /*
            |--------------------------------------------------------------------------
            | Project ID NEVER changes
            |--------------------------------------------------------------------------
            */

            'project_id' =>
                $project->id,

            'award_title' =>
                $validated['award_title'],

            'award_date' =>
                $validated['award_date']
                ?? null,

            'award_type' =>
                $validated['award_type']
                ?? null,

            'loa_number' =>
                $validated['loa_number']
                ?? null,

            'loa_date' =>
                $validated['loa_date']
                ?? null,

            'acceptance_deadline' =>
                $validated['acceptance_deadline']
                ?? null,

            'contract_required' =>
                $validated['contract_required']
                ?? true,

            'description' =>
                $validated['description']
                ?? null,

            'terms_and_conditions' =>
                $validated['terms_and_conditions']
                ?? null,

            'remarks' =>
                $validated['remarks']
                ?? null,

            'updated_by' =>
                auth()->id(),
        ]);


        return redirect()
            ->route(
                'admin.projects.procurement.tenders.awards.show',
                [
                    'project' =>
                        $project,

                    'procurementTender' =>
                        $procurementTender,

                    'award' =>
                        $award,
                ]
            )
            ->with(
                'success',
                'Award '
                . $award->award_number
                . ' updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementAward $award
    ): View {

        $this->verifyAward(
            $project,
            $procurementTender,
            $award
        );


        $award->load([
            'tender',
            'negotiation',
            'submission',
        ]);


        return view(
            'procurement.awards.show',
            compact(
                'project',
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
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementAward $award
    ): RedirectResponse {

        $this->verifyAward(
            $project,
            $procurementTender,
            $award
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
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementAward $award
    ): RedirectResponse {

        $this->verifyAward(
            $project,
            $procurementTender,
            $award
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
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementAward $award
    ): RedirectResponse {

        $this->verifyAward(
            $project,
            $procurementTender,
            $award
        );


        if ($award->status !== 'Approved') {

            return back()->with(
                'error',
                'Only approved Awards can have an LOA issued.'
            );
        }


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
        */

        $loaNumber = DB::transaction(
            function () use (
                $award,
                $validated
            ) {

                $lockedAward = ProcurementAward::query()
                    ->where(
                        'id',
                        $award->id
                    )
                    ->lockForUpdate()
                    ->firstOrFail();


                if (
                    !empty($lockedAward->loa_number)
                    ||
                    $lockedAward->status === 'LOA Issued'
                ) {

                    throw new \RuntimeException(
                        'An LOA has already been issued for this Award.'
                    );
                }


                $year =
                    now()->format('Y');


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

                    $lastNumber =
                        (int) substr(
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
            }
        );


        return back()->with(
            'success',
            'Letter of Award '
            . $loaNumber
            . ' issued successfully.'
        );
    }
}