<?php

namespace App\Http\Controllers\Admin\Handover;

use App\Http\Controllers\Controller;
use App\Models\HandoverDocument;
use App\Models\HandoverProject;
use App\Models\HandoverRequirement;
use App\Models\Project;
use App\Services\CommissioningAuditLogService;
use App\Services\HandoverReadinessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HandoverDocumentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        Project $project,
        Request $request
    ): View {

        $handover = $this->getHandover($project);

        $query = HandoverDocument::query()
            ->where('project_id', $project->id)
            ->where(
                'handover_project_id',
                $handover->id
            )
            ->with([
                'requirement',
                'uploadedBy',
                'submittedBy',
                'reviewedBy',
                'approvedBy',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'document_no',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'title',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'description',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'file_name',
                    'like',
                    "%{$search}%"
                );

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Document Type
        |--------------------------------------------------------------------------
        */

        if ($request->filled('document_type')) {

            $query->where(
                'document_type',
                $request->document_type
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Requirement
        |--------------------------------------------------------------------------
        */

        if ($request->filled('requirement_id')) {

            $query->where(
                'requirement_id',
                $request->requirement_id
            );
        }

        $documents = $query
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | KPI
        |--------------------------------------------------------------------------
        */

        $allDocuments = HandoverDocument::where(
            'project_id',
            $project->id
        )
            ->where(
                'handover_project_id',
                $handover->id
            )
            ->get();

        $totalDocuments = $allDocuments->count();

        $draftDocuments = $allDocuments
            ->where('status', 'Draft')
            ->count();

        $submittedDocuments = $allDocuments
            ->where('status', 'Submitted')
            ->count();

        $reviewDocuments = $allDocuments
            ->where('status', 'Under Review')
            ->count();

        $approvedDocuments = $allDocuments
            ->where('status', 'Approved')
            ->count();

        $rejectedDocuments = $allDocuments
            ->where('status', 'Rejected')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Requirements
        |--------------------------------------------------------------------------
        */

        $requirements = HandoverRequirement::query()
            ->where(
                'handover_project_id',
                $handover->id
            )
            ->whereIn(
                'requirement_type',
                [
                    'Documentation',
                    'O&M',
                    'As-Built',
                    'Warranty',
                    'Authority Approval',
                    'Training',
                    'Certificate',
                    'Other',
                ]
            )
            ->orderBy('requirement_code')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Options
        |--------------------------------------------------------------------------
        */

        $documentTypes = [
            'O&M Manual',
            'As-Built Drawing',
            'Warranty',
            'Authority Approval',
            'Training Record',
            'Handover Document',
            'Completion Certificate',
            'Statutory Certificate',
            'Test Report',
            'Other',
        ];

        $statuses = [
            'Draft',
            'Submitted',
            'Under Review',
            'Approved',
            'Rejected',
            'Archived',
        ];

        return view(
            'admin.handover.documents.index',
            compact(
                'project',
                'handover',
                'documents',

                'totalDocuments',
                'draftDocuments',
                'submittedDocuments',
                'reviewDocuments',
                'approvedDocuments',
                'rejectedDocuments',

                'requirements',
                'documentTypes',
                'statuses'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        Project $project,
        Request $request
    ): RedirectResponse {

        $handover = $this->getHandover($project);

        $validated = $request->validate([

            'requirement_id' => [
                'nullable',
                'exists:handover_requirements,id',
            ],

            'document_type' => [
                'required',
                'in:O&M Manual,As-Built Drawing,Warranty,Authority Approval,Training Record,Handover Document,Completion Certificate,Statutory Certificate,Test Report,Other',
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

            'document_version' => [
                'nullable',
                'string',
                'max:50',
            ],

            'file' => [
                'required',
                'file',
                'max:51200',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validate Requirement Belongs To Handover
        |--------------------------------------------------------------------------
        */

        $this->validateRequirement(
            $project,
            $handover,
            $validated['requirement_id'] ?? null
        );

        DB::beginTransaction();

        try {

            $nextNumber =
                HandoverDocument::where(
                    'project_id',
                    $project->id
                )->count() + 1;

            $documentNo =
                'DOC-' .
                str_pad(
                    $project->id,
                    5,
                    '0',
                    STR_PAD_LEFT
                ) .
                '-' .
                str_pad(
                    $nextNumber,
                    4,
                    '0',
                    STR_PAD_LEFT
                );

            while (
                HandoverDocument::where(
                    'project_id',
                    $project->id
                )
                    ->where(
                        'document_no',
                        $documentNo
                    )
                    ->exists()
            ) {

                $nextNumber++;

                $documentNo =
                    'DOC-' .
                    str_pad(
                        $project->id,
                        5,
                        '0',
                        STR_PAD_LEFT
                    ) .
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
            | Upload
            |--------------------------------------------------------------------------
            */

            $file = $request->file('file');

            $filePath = $file->store(
                'handover-documents/' . $project->id,
                'public'
            );

            /*
            |--------------------------------------------------------------------------
            | Create
            |--------------------------------------------------------------------------
            */

            $document = HandoverDocument::create([

                'project_id' =>
                    $project->id,

                'handover_project_id' =>
                    $handover->id,

                'requirement_id' =>
                    $validated['requirement_id'] ?? null,

                'document_no' =>
                    $documentNo,

                'document_type' =>
                    $validated['document_type'],

                'title' =>
                    $validated['title'],

                'description' =>
                    $validated['description'] ?? null,

                'document_version' =>
                    $validated['document_version'] ?? '1.0',

                'file_name' =>
                    $file->getClientOriginalName(),

                'file_path' =>
                    $filePath,

                'mime_type' =>
                    $file->getMimeType(),

                'file_size' =>
                    $file->getSize(),

                'status' =>
                    'Draft',

                'uploaded_by' =>
                    auth()->id(),

                'uploaded_at' =>
                    now(),

                'remarks' =>
                    $validated['remarks'] ?? null,

                'created_by' =>
                    auth()->id(),

                'updated_by' =>
                    auth()->id(),
            ]);

            CommissioningAuditLogService::action(
                'handover_document_created',
                $document,
                "Handover document {$document->document_no} created."
            );

            DB::commit();

            return back()->with(
                'success',
                'Handover document uploaded successfully.'
            );

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to upload handover document.'
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Project $project,
        Request $request,
        HandoverDocument $document
    ): RedirectResponse {

        $this->validateDocumentProject(
            $project,
            $document
        );

        if (!in_array($document->status, [
            'Draft',
            'Rejected',
        ])) {

            return back()->with(
                'error',
                'This document cannot be edited in its current status.'
            );
        }

        $validated = $request->validate([

            'requirement_id' => [
                'nullable',
                'exists:handover_requirements,id',
            ],

            'document_type' => [
                'required',
                'in:O&M Manual,As-Built Drawing,Warranty,Authority Approval,Training Record,Handover Document,Completion Certificate,Statutory Certificate,Test Report,Other',
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

            'document_version' => [
                'nullable',
                'string',
                'max:50',
            ],

            'file' => [
                'nullable',
                'file',
                'max:51200',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        $this->validateRequirement(
            $project,
            $document->handover,
            $validated['requirement_id'] ?? null
        );

        $oldValues = $document->getAttributes();

        $data = [
            'requirement_id' =>
                $validated['requirement_id'] ?? null,

            'document_type' =>
                $validated['document_type'],

            'title' =>
                $validated['title'],

            'description' =>
                $validated['description'] ?? null,

            'document_version' =>
                $validated['document_version'] ?? '1.0',

            'remarks' =>
                $validated['remarks'] ?? null,

            'updated_by' =>
                auth()->id(),
        ];

        /*
        |--------------------------------------------------------------------------
        | Replace File
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('file')) {

            if (
                $document->file_path &&
                Storage::disk('public')->exists(
                    $document->file_path
                )
            ) {
                Storage::disk('public')->delete(
                    $document->file_path
                );
            }

            $file = $request->file('file');

            $filePath = $file->store(
                'handover-documents/' . $project->id,
                'public'
            );

            $data['file_name'] =
                $file->getClientOriginalName();

            $data['file_path'] =
                $filePath;

            $data['mime_type'] =
                $file->getMimeType();

            $data['file_size'] =
                $file->getSize();

            $data['uploaded_by'] =
                auth()->id();

            $data['uploaded_at'] =
                now();
        }

        /*
        |--------------------------------------------------------------------------
        | Rejected → Draft
        |--------------------------------------------------------------------------
        */

        if ($document->status === 'Rejected') {

            $data['status'] = 'Draft';

            $data['rejection_reason'] = null;
            $data['submitted_by'] = null;
            $data['submitted_at'] = null;
            $data['reviewed_by'] = null;
            $data['reviewed_at'] = null;
            $data['approved_by'] = null;
            $data['approved_at'] = null;
        }

        $document->update($data);

        CommissioningAuditLogService::action(
            'handover_document_updated',
            $document,
            "Handover document {$document->document_no} updated.",
            $oldValues,
            $document->getAttributes()
        );

        return back()->with(
            'success',
            'Handover document updated successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        HandoverDocument $document
    ): RedirectResponse {

        $this->validateDocumentProject(
            $project,
            $document
        );

        if (!in_array($document->status, [
            'Draft',
            'Rejected',
        ])) {

            return back()->with(
                'error',
                'Only Draft or Rejected documents can be deleted.'
            );
        }

        $oldValues = $document->getAttributes();

        if (
            $document->file_path &&
            Storage::disk('public')->exists(
                $document->file_path
            )
        ) {

            Storage::disk('public')->delete(
                $document->file_path
            );
        }

        $document->delete();

        CommissioningAuditLogService::action(
            'handover_document_deleted',
            $document,
            "Handover document {$document->document_no} deleted.",
            $oldValues
        );

        return back()->with(
            'success',
            'Handover document deleted successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SUBMIT
    |--------------------------------------------------------------------------
    */

    public function submit(
        Project $project,
        HandoverDocument $document
    ): RedirectResponse {

        $this->validateDocumentProject(
            $project,
            $document
        );

        if ($document->status !== 'Draft') {

            return back()->with(
                'error',
                'Only Draft documents can be submitted.'
            );
        }

        if (!$document->file_path) {

            return back()->with(
                'error',
                'A document file is required before submission.'
            );
        }

        $oldValues = $document->getAttributes();

        $document->update([
            'status' =>
                'Submitted',

            'submitted_by' =>
                auth()->id(),

            'submitted_at' =>
                now(),

            'updated_by' =>
                auth()->id(),
        ]);

        CommissioningAuditLogService::action(
            'handover_document_submitted',
            $document,
            "Handover document {$document->document_no} submitted for review.",
            $oldValues,
            $document->getAttributes()
        );

        return back()->with(
            'success',
            'Document submitted for review.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REVIEW
    |--------------------------------------------------------------------------
    */

    public function review(
        Project $project,
        HandoverDocument $document
    ): RedirectResponse {

        $this->validateDocumentProject(
            $project,
            $document
        );

        if ($document->status !== 'Submitted') {

            return back()->with(
                'error',
                'Only submitted documents can be moved to review.'
            );
        }

        $oldValues = $document->getAttributes();

        $document->update([
            'status' =>
                'Under Review',

            'reviewed_by' =>
                auth()->id(),

            'reviewed_at' =>
                now(),

            'updated_by' =>
                auth()->id(),
        ]);

        CommissioningAuditLogService::action(
            'handover_document_review_started',
            $document,
            "Review started for document {$document->document_no}.",
            $oldValues,
            $document->getAttributes()
        );

        return back()->with(
            'success',
            'Document moved to review.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | APPROVE
    |--------------------------------------------------------------------------
    */

    public function approve(
        Project $project,
        HandoverDocument $document,
        HandoverReadinessService $readinessService
    ): RedirectResponse {

        $this->validateDocumentProject(
            $project,
            $document
        );

        if ($document->status !== 'Under Review') {

            return back()->with(
                'error',
                'Only documents under review can be approved.'
            );
        }

        $oldValues = $document->getAttributes();

        $document->update([
            'status' =>
                'Approved',

            'approved_by' =>
                auth()->id(),

            'approved_at' =>
                now(),

            'updated_by' =>
                auth()->id(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Automatically Complete Linked Requirement
        |--------------------------------------------------------------------------
        */

        if ($document->requirement_id) {

            $requirement =
                HandoverRequirement::where(
                    'id',
                    $document->requirement_id
                )
                ->where(
                    'handover_project_id',
                    $document->handover_project_id
                )
                ->first();

            if ($requirement) {

                $requirement->update([
                    'status' =>
                        'Completed',

                    'completed_by' =>
                        auth()->id(),

                    'completed_at' =>
                        now(),

                    'remarks' =>
                        trim(
                            ($requirement->remarks
                                ? $requirement->remarks .
                                  PHP_EOL . PHP_EOL
                                : '') .
                            'Requirement completed automatically after approval of document ' .
                            $document->document_no . '.'
                        ),

                    'updated_by' =>
                        auth()->id(),
                ]);
            }
        }

        CommissioningAuditLogService::action(
            'handover_document_approved',
            $document,
            "Handover document {$document->document_no} approved.",
            $oldValues,
            $document->getAttributes()
        );

        /*
        |--------------------------------------------------------------------------
        | Recalculate Readiness
        |--------------------------------------------------------------------------
        */

        $readinessService->sync($project);

        return back()->with(
            'success',
            'Document approved successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REJECT
    |--------------------------------------------------------------------------
    */

    public function reject(
        Project $project,
        Request $request,
        HandoverDocument $document
    ): RedirectResponse {

        $this->validateDocumentProject(
            $project,
            $document
        );

        if ($document->status !== 'Under Review') {

            return back()->with(
                'error',
                'Only documents under review can be rejected.'
            );
        }

        $validated = $request->validate([
            'rejection_reason' => [
                'required',
                'string',
            ],
        ]);

        $oldValues = $document->getAttributes();

        $document->update([
            'status' =>
                'Rejected',

            'rejection_reason' =>
                $validated['rejection_reason'],

            'reviewed_by' =>
                auth()->id(),

            'reviewed_at' =>
                now(),

            'remarks' =>
                trim(
                    ($document->remarks
                        ? $document->remarks .
                          PHP_EOL . PHP_EOL
                        : '') .
                    'Review Rejection: ' .
                    $validated['rejection_reason']
                ),

            'updated_by' =>
                auth()->id(),
        ]);

        CommissioningAuditLogService::action(
            'handover_document_rejected',
            $document,
            "Handover document {$document->document_no} rejected.",
            $oldValues,
            $document->getAttributes()
        );

        return back()->with(
            'success',
            'Document rejected. It can now be edited and resubmitted.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ARCHIVE
    |--------------------------------------------------------------------------
    */

    public function archive(
        Project $project,
        HandoverDocument $document
    ): RedirectResponse {

        $this->validateDocumentProject(
            $project,
            $document
        );

        if ($document->status !== 'Approved') {

            return back()->with(
                'error',
                'Only approved documents can be archived.'
            );
        }

        $oldValues = $document->getAttributes();

        $document->update([
            'status' =>
                'Archived',

            'updated_by' =>
                auth()->id(),
        ]);

        CommissioningAuditLogService::action(
            'handover_document_archived',
            $document,
            "Handover document {$document->document_no} archived.",
            $oldValues,
            $document->getAttributes()
        );

        return back()->with(
            'success',
            'Document archived successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GET HANDOVER
    |--------------------------------------------------------------------------
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

        abort_if(
            !$handover,
            404,
            'Handover has not been started for this project.'
        );

        return $handover;
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE DOCUMENT PROJECT
    |--------------------------------------------------------------------------
    */

    protected function validateDocumentProject(
        Project $project,
        HandoverDocument $document
    ): void {

        abort_if(
            (int) $document->project_id !==
            (int) $project->id,
            404
        );

        abort_if(
            !HandoverProject::where('id', $document->handover_project_id)
                ->where('project_id', $project->id)
                ->exists(),
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE REQUIREMENT
    |--------------------------------------------------------------------------
    */

    protected function validateRequirement(
        Project $project,
        HandoverProject $handover,
        $requirementId
    ): void {

        if (!$requirementId) {
            return;
        }

        $exists = HandoverRequirement::where(
            'id',
            $requirementId
        )
            ->where(
                'project_id',
                $project->id
            )
            ->where(
                'handover_project_id',
                $handover->id
            )
            ->exists();

        abort_unless(
            $exists,
            422,
            'Selected handover requirement does not belong to this project.'
        );
    }
}