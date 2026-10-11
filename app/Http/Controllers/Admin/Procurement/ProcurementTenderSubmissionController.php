<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\ProcurementTender;
use App\Models\ProcurementTenderBidder;
use App\Models\ProcurementTenderSubmission;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProcurementTenderSubmissionController extends Controller
{
    /**
     * Display submissions for a tender.
     */
    public function index(
        Project $project,
        ProcurementTender $procurementTender
    ): View {
        // Make sure the tender belongs to the selected project
        abort_unless(
            (int) $procurementTender->project_id === (int) $project->id,
            404
        );

        $submissions = ProcurementTenderSubmission::query()
            ->where('project_id', $project->id)
            ->where('procurement_tender_id', $procurementTender->id)
            ->with('tenderBidder')
            ->latest('submission_date')
            ->latest('id')
            ->get();

        return view('procurement.tender-submissions.index', compact(
            'project',
            'procurementTender',
            'submissions'
        ));
    }

    /**
     * Show create submission form.
     */
    public function create(
        Project $project,
        ProcurementTender $procurementTender
    ): View {
        // Make sure the tender belongs to the selected project
        abort_unless(
            (int) $procurementTender->project_id === (int) $project->id,
            404
        );

        // Get bidders already used for submissions
        $assignedBidderIds = ProcurementTenderSubmission::query()
            ->where('project_id', $project->id)
            ->where('procurement_tender_id', $procurementTender->id)
            ->pluck('procurement_tender_bidder_id')
            ->filter()
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | Available Tender Bidders
        |--------------------------------------------------------------------------
        |
        | Only bidders belonging to this tender can be selected.
        |
        */

        $availableBidders = ProcurementTenderBidder::query()
            ->where('procurement_tender_id', $procurementTender->id)
            ->whereNotIn('id', $assignedBidderIds)
            ->with('contractor')
            ->get();

        return view('procurement.tender-submissions.create', compact(
            'project',
            'procurementTender',
            'availableBidders'
        ));
    }

    /**
     * Store a new tender submission.
     */
    public function store(
        Request $request,
        Project $project,
        ProcurementTender $procurementTender
    ): RedirectResponse {
        // Make sure the tender belongs to the selected project
        abort_unless(
            (int) $procurementTender->project_id === (int) $project->id,
            404
        );

        $validated = $request->validate([
            'procurement_tender_bidder_id' => [
                'required',
                'integer',
            ],

            'submission_date' => [
                'required',
                'date',
            ],

            'bid_validity_days' => [
                'nullable',
                'integer',
                'min:0',
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
                'string',
                'max:50',
            ],

            'is_complete' => [
                'nullable',
                'boolean',
            ],

            'submitted_by' => [
                'nullable',
                'integer',
            ],

            'submitted_by_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validate Bidder
        |--------------------------------------------------------------------------
        |
        | The selected bidder must belong to this tender.
        |
        */

        $bidder = ProcurementTenderBidder::query()
            ->where('id', $validated['procurement_tender_bidder_id'])
            ->where('procurement_tender_id', $procurementTender->id)
            ->first();

        abort_unless($bidder, 404);

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Submission
        |--------------------------------------------------------------------------
        */

        $existingSubmission = ProcurementTenderSubmission::query()
            ->where('project_id', $project->id)
            ->where('procurement_tender_id', $procurementTender->id)
            ->where(
                'procurement_tender_bidder_id',
                $validated['procurement_tender_bidder_id']
            )
            ->first();

        if ($existingSubmission) {
            return back()
                ->withInput()
                ->withErrors([
                    'procurement_tender_bidder_id' =>
                        'A submission already exists for the selected bidder.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Generate Submission Number
        |--------------------------------------------------------------------------
        */

        $submissionNumber = $this->generateSubmissionNumber();

        /*
        |--------------------------------------------------------------------------
        | Create Submission
        |--------------------------------------------------------------------------
        */

        $submission = ProcurementTenderSubmission::create([
            'project_id' => $project->id,

            'procurement_tender_id' =>
                $procurementTender->id,

            'procurement_tender_bidder_id' =>
                $validated['procurement_tender_bidder_id'],

            'submission_number' =>
                $submissionNumber,

            'submission_date' =>
                $validated['submission_date'],

            'bid_validity_days' =>
                $validated['bid_validity_days'] ?? null,

            'bid_valid_until' =>
                $validated['bid_valid_until'] ?? null,

            'quoted_amount' =>
                $validated['quoted_amount'],

            'currency' =>
                $validated['currency'],

            'technical_submission' =>
                $validated['technical_submission'] ?? null,

            'commercial_submission' =>
                $validated['commercial_submission'] ?? null,

            'compliance_declaration' =>
                $validated['compliance_declaration'] ?? null,

            'submission_status' =>
                $validated['submission_status'],

            'is_complete' =>
                $request->boolean('is_complete'),

            'submitted_by' =>
                $validated['submitted_by'] ?? null,

            'submitted_by_name' =>
                $validated['submitted_by_name'] ?? null,

            'remarks' =>
                $validated['remarks'] ?? null,

            'created_by' =>
                auth()->id(),

            'updated_by' =>
                auth()->id(),
        ]);

        return redirect()->route(
            'admin.projects.procurement.tenders.submissions.show',
            [
                'project' => $project,
                'procurementTender' => $procurementTender,
                'submission' => $submission,
            ]
        )->with(
            'success',
            'Tender submission created successfully.'
        );
    }

    /**
     * Generate submission number.
     */
    protected function generateSubmissionNumber(): string
    {
        $year = now()->year;

        $lastSubmission = ProcurementTenderSubmission::query()
            ->whereYear('created_at', $year)
            ->orderByDesc('id')
            ->first();

        if (!$lastSubmission) {
            $nextNumber = 1;
        } else {
            $lastNumber = (int) preg_replace(
                '/[^0-9]/',
                '',
                (string) $lastSubmission->submission_number
            );

            $nextNumber = $lastNumber + 1;
        }

        return 'SUB-' . $year . '-' . str_pad(
            $nextNumber,
            4,
            '0',
            STR_PAD_LEFT
        );
    }

    /**
     * Display a submission.
     */
    public function show(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementTenderSubmission $submission
    ): View {
        /*
        |--------------------------------------------------------------------------
        | Strict Project / Tender / Submission Validation
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $procurementTender->project_id === (int) $project->id,
            404
        );

        abort_unless(
            (int) $submission->project_id === (int) $project->id,
            404
        );

        abort_unless(
            (int) $submission->procurement_tender_id ===
            (int) $procurementTender->id,
            404
        );

        $submission->load([
            'tenderBidder.contractor',
            'technicalEvaluation',
            'commercialEvaluation',
            'bidComparisonItems',
        ]);

        return view('procurement.tender-submissions.show', compact(
            'project',
            'procurementTender',
            'submission'
        ));
    }

    /**
     * Show edit form.
     */
    public function edit(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementTenderSubmission $submission
    ): View {
        /*
        |--------------------------------------------------------------------------
        | Strict Project / Tender / Submission Validation
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $procurementTender->project_id === (int) $project->id,
            404
        );

        abort_unless(
            (int) $submission->project_id === (int) $project->id,
            404
        );

        abort_unless(
            (int) $submission->procurement_tender_id ===
            (int) $procurementTender->id,
            404
        );

        $submission->load('tenderBidder.contractor');

        return view('procurement.tender-submissions.edit', compact(
            'project',
            'procurementTender',
            'submission'
        ));
    }

    /**
     * Update a tender submission.
     */
    public function update(
        Request $request,
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementTenderSubmission $submission
    ): RedirectResponse {
        /*
        |--------------------------------------------------------------------------
        | Strict Project / Tender / Submission Validation
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $procurementTender->project_id === (int) $project->id,
            404
        );

        abort_unless(
            (int) $submission->project_id === (int) $project->id,
            404
        );

        abort_unless(
            (int) $submission->procurement_tender_id ===
            (int) $procurementTender->id,
            404
        );

        $validated = $request->validate([
            'submission_date' => [
                'required',
                'date',
            ],

            'bid_validity_days' => [
                'nullable',
                'integer',
                'min:0',
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
                'string',
                'max:50',
            ],

            'is_complete' => [
                'nullable',
                'boolean',
            ],

            'submitted_by' => [
                'nullable',
                'integer',
            ],

            'submitted_by_name' => [
                'nullable',
                'string',
                'max:255',
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
        | project_id, tender_id and bidder_id are intentionally NOT updated.
        | This prevents accidental cross-project / cross-tender mapping.
        |
        */

        $submission->update([
            'submission_date' =>
                $validated['submission_date'],

            'bid_validity_days' =>
                $validated['bid_validity_days'] ?? null,

            'bid_valid_until' =>
                $validated['bid_valid_until'] ?? null,

            'quoted_amount' =>
                $validated['quoted_amount'],

            'currency' =>
                $validated['currency'],

            'technical_submission' =>
                $validated['technical_submission'] ?? null,

            'commercial_submission' =>
                $validated['commercial_submission'] ?? null,

            'compliance_declaration' =>
                $validated['compliance_declaration'] ?? null,

            'submission_status' =>
                $validated['submission_status'],

            'is_complete' =>
                $request->boolean('is_complete'),

            'submitted_by' =>
                $validated['submitted_by'] ?? null,

            'submitted_by_name' =>
                $validated['submitted_by_name'] ?? null,

            'remarks' =>
                $validated['remarks'] ?? null,

            'updated_by' =>
                auth()->id(),
        ]);

        return redirect()->route(
            'admin.projects.procurement.tenders.submissions.show',
            [
                'project' => $project,
                'procurementTender' => $procurementTender,
                'submission' => $submission,
            ]
        )->with(
            'success',
            'Tender submission updated successfully.'
        );
    }

    /**
     * Delete a tender submission.
     */
    public function destroy(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementTenderSubmission $submission
    ): RedirectResponse {
        /*
        |--------------------------------------------------------------------------
        | Strict Project / Tender / Submission Validation
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $procurementTender->project_id === (int) $project->id,
            404
        );

        abort_unless(
            (int) $submission->project_id === (int) $project->id,
            404
        );

        abort_unless(
            (int) $submission->procurement_tender_id ===
            (int) $procurementTender->id,
            404
        );

        $submission->delete();

        return redirect()->route(
            'admin.projects.procurement.tenders.submissions.index',
            [
                'project' => $project,
                'procurementTender' => $procurementTender,
            ]
        )->with(
            'success',
            'Tender submission deleted successfully.'
        );
    }
}