<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectGovernanceMeeting;
use App\Models\ProjectGovernanceMeetingDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProjectGovernanceMeetingDocumentController extends Controller
{
    /**
     * Display all documents for a governance meeting.
     */
    public function index(
        Project $project,
        ProjectGovernanceMeeting $meeting
    ): View {

        abort_unless(
            (int) $meeting->project_id === (int) $project->id,
            404
        );

        $documents = $meeting->documents()
            ->with('uploader')
            ->latest()
            ->get();

        return view(
            'projects.governance-meetings.documents.index',
            compact(
                'project',
                'meeting',
                'documents'
            )
        );
    }


    /**
     * Show create document form.
     */
    public function create(
        Project $project,
        ProjectGovernanceMeeting $meeting
    ): View {

        abort_unless(
            (int) $meeting->project_id === (int) $project->id,
            404
        );

        return view(
            'projects.governance-meetings.documents.create',
            compact(
                'project',
                'meeting'
            )
        );
    }


    /**
     * Store a meeting document.
     */
    public function store(
        Request $request,
        Project $project,
        ProjectGovernanceMeeting $meeting
    ): RedirectResponse {

        abort_unless(
            (int) $meeting->project_id === (int) $project->id,
            404
        );


        $validated = $request->validate([

            'document_name' => [
                'required',
                'string',
                'max:255',
            ],

            'document_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'file' => [
                'required',
                'file',
                'max:51200',
            ],

        ]);


        $file = $request->file('file');


        /*
         * Store meeting documents in:
         *
         * storage/app/public/project-governance-meetings/{meeting_id}/documents
         */
        $filePath = $file->store(
            'project-governance-meetings/' .
            $meeting->id .
            '/documents',
            'public'
        );


        ProjectGovernanceMeetingDocument::create([

            'project_governance_meeting_id' =>
                $meeting->id,

            'document_name' =>
                $validated['document_name'],

            'document_type' =>
                $validated['document_type'] ?? null,

            'description' =>
                $validated['description'] ?? null,

            'file_path' =>
                $filePath,

            'original_file_name' =>
                $file->getClientOriginalName(),

            'mime_type' =>
                $file->getClientMimeType(),

            'file_size' =>
                $file->getSize(),

            'uploaded_by' =>
                auth()->id(),

            'uploaded_at' =>
                now(),

            'status' =>
                'Active',

        ]);


        return redirect()->route(
            'admin.projects.governance-meetings.documents.index',
            [
                'project' => $project->id,
                'meeting' => $meeting->id,
            ]
        )->with(
            'success',
            'Meeting document uploaded successfully.'
        );
    }


    /**
     * Download a meeting document.
     */
    public function download(
        Project $project,
        ProjectGovernanceMeeting $meeting,
        ProjectGovernanceMeetingDocument $document
    ) {

        abort_unless(
            (int) $meeting->project_id === (int) $project->id,
            404
        );


        abort_unless(
            (int) $document->project_governance_meeting_id
                === (int) $meeting->id,
            404
        );


        abort_unless(
            $document->status === 'Active',
            404
        );


        if (
            !$document->file_path ||
            !Storage::disk('public')->exists(
                $document->file_path
            )
        ) {

            return back()->with(
                'error',
                'The requested document file could not be found.'
            );
        }


        return Storage::disk('public')->download(
            $document->file_path,
            $document->original_file_name
                ?: basename($document->file_path)
        );
    }


    /**
     * Delete a meeting document.
     */
    public function destroy(
        Project $project,
        ProjectGovernanceMeeting $meeting,
        ProjectGovernanceMeetingDocument $document
    ): RedirectResponse {

        abort_unless(
            (int) $meeting->project_id === (int) $project->id,
            404
        );


        abort_unless(
            (int) $document->project_governance_meeting_id
                === (int) $meeting->id,
            404
        );


        /*
         * Delete physical file.
         */
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


        /*
         * Delete database record.
         */
        $document->delete();


        return redirect()->route(
            'admin.projects.governance-meetings.documents.index',
            [
                'project' => $project->id,
                'meeting' => $meeting->id,
            ]
        )->with(
            'success',
            'Meeting document deleted successfully.'
        );
    }

    public function preview(
        Project $project,
        ProjectGovernanceMeeting $meeting,
        ProjectGovernanceMeetingDocument $document
    ) {
        abort_unless(
            (int) $meeting->project_id === (int) $project->id,
            404
        );

        abort_unless(
            (int) $document->project_governance_meeting_id
                === (int) $meeting->id,
            404
        );

        abort_unless(
            $document->status === 'Active',
            404
        );

        abort_unless(
            $document->file_path &&
            Storage::disk('public')->exists(
                $document->file_path
            ),
            404
        );

        $mimeType = $document->mime_type
            ?: Storage::disk('public')->mimeType(
                $document->file_path
            );

        /*
         * Browser-previewable files.
         */
        $previewable = [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'text/plain',
        ];

        if (!in_array($mimeType, $previewable, true)) {
            return redirect()->route(
                'admin.projects.governance-meetings.documents.download',
                [
                    'project' => $project->id,
                    'meeting' => $meeting->id,
                    'document' => $document->id,
                ]
            );
        }

        return Storage::disk('public')->response(
            $document->file_path,
            $document->original_file_name
                ?: basename($document->file_path),
            [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline',
            ]
        );
    }

}