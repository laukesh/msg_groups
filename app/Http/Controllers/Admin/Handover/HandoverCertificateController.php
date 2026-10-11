<?php

namespace App\Http\Controllers\Admin\Handover;

use App\Http\Controllers\Controller;
use App\Models\HandoverCertificate;
use App\Models\HandoverFinalCompletion;
use App\Models\HandoverProject;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HandoverCertificateController extends Controller
{
    /**
     * Handover Certificate dashboard.
     */
    public function index(Project $project): View
    {
        $handover = $this->getHandover($project);

        $finalCompletion = HandoverFinalCompletion::where(
            'handover_project_id',
            $handover->id
        )->first();

        $certificate = HandoverCertificate::where(
            'handover_project_id',
            $handover->id
        )
            ->with([
                'finalCompletion',
                'issuedBy',
                'submittedBy',
                'reviewedBy',
                'approvedBy',
            ])
            ->latest('id')
            ->first();

        $finalCompletionApproved =
            $finalCompletion &&
            $finalCompletion->status === 'Approved';

        $totalCertificates = HandoverCertificate::where(
            'handover_project_id',
            $handover->id
        )->count();

        $approvedCertificates = HandoverCertificate::where(
            'handover_project_id',
            $handover->id
        )
            ->where('status', 'Approved')
            ->count();

        $submittedCertificates = HandoverCertificate::where(
            'handover_project_id',
            $handover->id
        )
            ->whereIn('status', [
                'Submitted',
                'Under Review',
            ])
            ->count();

        $rejectedCertificates = HandoverCertificate::where(
            'handover_project_id',
            $handover->id
        )
            ->where('status', 'Rejected')
            ->count();

        $certificateApproved =
            $certificate &&
            $certificate->status === 'Approved';

        return view(
            'admin.handover.certificates.index',
            compact(
                'project',
                'handover',
                'finalCompletion',
                'certificate',
                'finalCompletionApproved',
                'totalCertificates',
                'approvedCertificates',
                'submittedCertificates',
                'rejectedCertificates',
                'certificateApproved'
            )
        );
    }

    /**
     * Create Handover Certificate.
     */
    public function store(
        Project $project,
        Request $request
    ): RedirectResponse {
        $handover = $this->getHandover($project);

        $finalCompletion = HandoverFinalCompletion::where(
            'handover_project_id',
            $handover->id
        )->first();

        if (!$finalCompletion) {
            return back()->with(
                'error',
                'Final Completion record has not been created yet.'
            );
        }

        if ($finalCompletion->status !== 'Approved') {
            return back()->with(
                'error',
                'Handover Certificate can only be created after Final Completion is Approved.'
            );
        }

        /*
         * Only one certificate per handover.
         */
        $existing = HandoverCertificate::where(
            'handover_project_id',
            $handover->id
        )->first();

        if ($existing) {
            return back()->with(
                'error',
                'A Handover Certificate already exists for this handover.'
            );
        }

        $validated = $request->validate([
            'certificate_type' => [
                'required',
                'in:Practical Completion,Final Completion,Handover Certificate,Taking Over Certificate,Other',
            ],
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'issue_date' => [
                'nullable',
                'date',
            ],
            'effective_date' => [
                'nullable',
                'date',
            ],
            'certificate_statement' => [
                'nullable',
                'string',
            ],
            'remarks' => [
                'nullable',
                'string',
            ],
            'document' => [
                'nullable',
                'file',
                'max:51200',
            ],
        ]);

        $certificateNo = $this->generateCertificateNo($project);

        $data = [
            'project_id' => $project->id,
            'handover_project_id' => $handover->id,

            'certificate_no' => $certificateNo,

            'certificate_type' =>
                $validated['certificate_type'],

            'title' =>
                $validated['title'],

            'description' =>
                $validated['description'] ?? null,

            'issue_date' =>
                $validated['issue_date'] ?? null,

            'effective_date' =>
                $validated['effective_date'] ?? null,

            'status' => 'Draft',

            'final_completion_id' =>
                $finalCompletion->id,

            'certificate_statement' =>
                $validated['certificate_statement'] ?? null,

            'remarks' =>
                $validated['remarks'] ?? null,

            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ];

        if ($request->hasFile('document')) {

            $path = $request->file('document')->store(
                'handover-certificates/' . $project->id,
                'public'
            );

            $data['document_path'] = $path;
        }

        $certificate = HandoverCertificate::create($data);

        return redirect()
            ->route(
                'admin.projects.handover.certificates.index',
                $project
            )
            ->with(
                'success',
                "Handover Certificate {$certificate->certificate_no} created successfully."
            );
    }

    /**
     * Update certificate.
     */
    public function update(
        Project $project,
        Request $request,
        HandoverCertificate $certificate
    ): RedirectResponse {
        $this->validateCertificateProject(
            $project,
            $certificate
        );

        if (!in_array($certificate->status, [
            'Draft',
            'Rejected',
        ])) {
            return back()->with(
                'error',
                'Certificate can only be edited in Draft or Rejected status.'
            );
        }

        $handover = $this->getHandover($project);

        $finalCompletion = HandoverFinalCompletion::where(
            'handover_project_id',
            $handover->id
        )->first();

        if (
            !$finalCompletion ||
            $finalCompletion->status !== 'Approved'
        ) {
            return back()->with(
                'error',
                'Final Completion must be Approved before editing the Handover Certificate.'
            );
        }

        $validated = $request->validate([
            'certificate_type' => [
                'required',
                'in:Practical Completion,Final Completion,Handover Certificate,Taking Over Certificate,Other',
            ],
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'issue_date' => [
                'nullable',
                'date',
            ],
            'effective_date' => [
                'nullable',
                'date',
            ],
            'certificate_statement' => [
                'nullable',
                'string',
            ],
            'remarks' => [
                'nullable',
                'string',
            ],
            'document' => [
                'nullable',
                'file',
                'max:51200',
            ],
        ]);

        $data = [
            'certificate_type' =>
                $validated['certificate_type'],

            'title' =>
                $validated['title'],

            'description' =>
                $validated['description'] ?? null,

            'issue_date' =>
                $validated['issue_date'] ?? null,

            'effective_date' =>
                $validated['effective_date'] ?? null,

            'certificate_statement' =>
                $validated['certificate_statement'] ?? null,

            'remarks' =>
                $validated['remarks'] ?? null,

            'final_completion_id' =>
                $finalCompletion->id,

            'updated_by' => auth()->id(),
        ];

        /*
         * Rejected → Draft.
         */
        if ($certificate->status === 'Rejected') {
            $data['status'] = 'Draft';
            $data['rejection_reason'] = null;
            $data['submitted_by'] = null;
            $data['submitted_at'] = null;
            $data['reviewed_by'] = null;
            $data['reviewed_at'] = null;
            $data['approved_by'] = null;
            $data['approved_at'] = null;
        }

        if ($request->hasFile('document')) {

            if (
                $certificate->document_path &&
                Storage::disk('public')->exists(
                    $certificate->document_path
                )
            ) {
                Storage::disk('public')->delete(
                    $certificate->document_path
                );
            }

            $path = $request->file('document')->store(
                'handover-certificates/' . $project->id,
                'public'
            );

            $data['document_path'] = $path;
        }

        $certificate->update($data);

        return back()->with(
            'success',
            'Handover Certificate updated successfully.'
        );
    }

    /**
     * Submit certificate.
     */
    public function submit(
        Project $project,
        HandoverCertificate $certificate
    ): RedirectResponse {
        $this->validateCertificateProject(
            $project,
            $certificate
        );

        if ($certificate->status !== 'Draft') {
            return back()->with(
                'error',
                'Only Draft certificates can be submitted.'
            );
        }

        $handover = $this->getHandover($project);

        $finalCompletion = HandoverFinalCompletion::where(
            'handover_project_id',
            $handover->id
        )->first();

        if (
            !$finalCompletion ||
            $finalCompletion->status !== 'Approved'
        ) {
            return back()->with(
                'error',
                'Final Completion must be Approved before submission.'
            );
        }

        if (empty($certificate->title)) {
            return back()->with(
                'error',
                'Certificate title is required.'
            );
        }

        $certificate->update([
            'status' => 'Submitted',

            'submitted_by' => auth()->id(),
            'submitted_at' => now(),

            'updated_by' => auth()->id(),
        ]);

        return back()->with(
            'success',
            'Handover Certificate submitted successfully.'
        );
    }

    /**
     * Start review.
     */
    public function review(
        Project $project,
        HandoverCertificate $certificate
    ): RedirectResponse {
        $this->validateCertificateProject(
            $project,
            $certificate
        );

        if ($certificate->status !== 'Submitted') {
            return back()->with(
                'error',
                'Only Submitted certificates can be moved to review.'
            );
        }

        $certificate->update([
            'status' => 'Under Review',

            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),

            'updated_by' => auth()->id(),
        ]);

        return back()->with(
            'success',
            'Handover Certificate moved to Under Review.'
        );
    }

    /**
     * Approve certificate.
     */
    public function approve(
        Project $project,
        HandoverCertificate $certificate
    ): RedirectResponse {
        $this->validateCertificateProject(
            $project,
            $certificate
        );

        if ($certificate->status !== 'Under Review') {
            return back()->with(
                'error',
                'Only certificates Under Review can be approved.'
            );
        }

        $handover = $this->getHandover($project);

        $finalCompletion = HandoverFinalCompletion::where(
            'handover_project_id',
            $handover->id
        )->first();

        if (
            !$finalCompletion ||
            $finalCompletion->status !== 'Approved'
        ) {
            return back()->with(
                'error',
                'Final Completion must remain Approved before certificate approval.'
            );
        }

        DB::transaction(function () use (
            $certificate,
            $handover
        ) {
            $certificate->update([
                'status' => 'Approved',

                'issue_date' =>
                    $certificate->issue_date ??
                    now()->toDateString(),

                'effective_date' =>
                    $certificate->effective_date ??
                    now()->toDateString(),

                'issued_by' =>
                    auth()->id(),

                'issued_at' =>
                    now(),

                'approved_by' =>
                    auth()->id(),

                'approved_at' =>
                    now(),

                'updated_by' =>
                    auth()->id(),
            ]);

            /*
             * Formal handover becomes completed.
             */
            $handover->update([
                'status' => 'Completed',

                'actual_handover_date' =>
                    $handover->actual_handover_date ??
                    now()->toDateString(),

                'updated_by' =>
                    auth()->id(),
            ]);
        });

        return back()->with(
            'success',
            'Handover Certificate approved successfully. Project handover is now completed.'
        );
    }

    /**
     * Reject certificate.
     */
    public function reject(
        Project $project,
        Request $request,
        HandoverCertificate $certificate
    ): RedirectResponse {
        $this->validateCertificateProject(
            $project,
            $certificate
        );

        if ($certificate->status !== 'Under Review') {
            return back()->with(
                'error',
                'Only certificates Under Review can be rejected.'
            );
        }

        $validated = $request->validate([
            'rejection_reason' => [
                'required',
                'string',
                'max:5000',
            ],
        ]);

        $certificate->update([
            'status' => 'Rejected',

            'rejection_reason' =>
                $validated['rejection_reason'],

            'updated_by' =>
                auth()->id(),
        ]);

        return back()->with(
            'success',
            'Handover Certificate rejected. It can be rectified and resubmitted.'
        );
    }

    /**
     * Cancel certificate.
     */
    public function cancel(
        Project $project,
        HandoverCertificate $certificate
    ): RedirectResponse {
        $this->validateCertificateProject(
            $project,
            $certificate
        );

        if (!in_array($certificate->status, [
            'Draft',
            'Rejected',
        ])) {
            return back()->with(
                'error',
                'Only Draft or Rejected certificates can be cancelled.'
            );
        }

        $certificate->update([
            'status' => 'Cancelled',
            'updated_by' => auth()->id(),
        ]);

        return back()->with(
            'success',
            'Handover Certificate cancelled successfully.'
        );
    }

    /**
     * Get current project handover.
     */
    protected function getHandover(
        Project $project
    ): HandoverProject {
        $handover = HandoverProject::where(
            'project_id',
            $project->id
        )
            ->latest('id')
            ->first();

        if (!$handover) {
            abort(
                404,
                'Handover process has not been started for this project.'
            );
        }

        return $handover;
    }

    /**
     * Validate certificate belongs to current project.
     */
    protected function validateCertificateProject(
        Project $project,
        HandoverCertificate $certificate
    ): void {
        if (
            (int) $certificate->project_id !==
            (int) $project->id
        ) {
            abort(404);
        }
    }

    /**
     * Generate certificate number.
     */
    protected function generateCertificateNo(
        Project $project
    ): string {
        $count = HandoverCertificate::where(
            'project_id',
            $project->id
        )->count() + 1;

        do {

            $certificateNo =
                'HC-' .
                str_pad(
                    (string) $project->id,
                    4,
                    '0',
                    STR_PAD_LEFT
                ) .
                '-' .
                str_pad(
                    (string) $count,
                    4,
                    '0',
                    STR_PAD_LEFT
                );

            $exists = HandoverCertificate::where(
                'project_id',
                $project->id
            )
                ->where(
                    'certificate_no',
                    $certificateNo
                )
                ->exists();

            if ($exists) {
                $count++;
            }

        } while ($exists);

        return $certificateNo;
    }
}