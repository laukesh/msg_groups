<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionHseSafetyMeeting;
use App\Models\ConstructionHseSafetyMeetingDocument;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ConstructionHseSafetyMeetingDocumentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        Project $project,
        ConstructionHseSafetyMeeting $meeting
    ): View {

        $this->validateMeetingRelation(
            $project,
            $meeting
        );

        $documents = $meeting
            ->documents()
            ->with([
                'uploadedBy',
                'creator',
                'updater',
            ])
            ->latest('id')
            ->get();

        return view(
            'construction.hse.safety-meeting-documents.index',
            [
                'project' => $project,
                'meeting' => $meeting,
                'documents' => $documents,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
        Project $project,
        ConstructionHseSafetyMeeting $meeting
    ): View {

        $this->validateMeetingRelation(
            $project,
            $meeting
        );

        $documentNumber =
            $this->generateDocumentNumber(
                $meeting
            );

        return view(
            'construction.hse.safety-meeting-documents.create',
            [
                'project' => $project,
                'meeting' => $meeting,
                'documentNumber' => $documentNumber,
            ]
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
        ConstructionHseSafetyMeeting $meeting
    ): RedirectResponse {

        $this->validateMeetingRelation(
            $project,
            $meeting
        );


        $validated = $request->validate([

            'document_number' => [
                'nullable',
                'string',
                'max:100',
            ],

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
            ],

            'document_date' => [
                'nullable',
                'date',
            ],

            'file' => [
                'required',
                'file',
                'max:51200',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        $file = $request->file('file');


        /*
        |--------------------------------------------------------------------------
        | Store File
        |--------------------------------------------------------------------------
        */

        $directory =
            'construction/hse/safety-meetings/' .
            $meeting->id;


        $filePath =
            $file->store(
                $directory,
                'public'
            );


        /*
        |--------------------------------------------------------------------------
        | Create Document
        |--------------------------------------------------------------------------
        */

        $document =
            ConstructionHseSafetyMeetingDocument::create([

                'construction_hse_safety_meeting_id' =>
                    $meeting->id,

                'document_number' =>
                    $validated['document_number']
                    ?? null,

                'document_name' =>
                    $validated['document_name'],

                'document_type' =>
                    $validated['document_type']
                    ?? null,

                'description' =>
                    $validated['description']
                    ?? null,

                'file_path' =>
                    $filePath,

                'original_file_name' =>
                    $file->getClientOriginalName(),

                'file_size' =>
                    $file->getSize(),

                'mime_type' =>
                    $file->getMimeType(),

                'document_date' =>
                    $validated['document_date']
                    ?? null,

                'uploaded_by' =>
                    Auth::id(),

                'remarks' =>
                    $validated['remarks']
                    ?? null,

                'created_by' =>
                    Auth::id(),

                'updated_by' =>
                    Auth::id(),

            ]);


        return redirect()
            ->route(
                'admin.projects.construction.hse.safety-meetings.documents.index',
                [
                    'project' => $project,
                    'meeting' => $meeting,
                ]
            )
            ->with(
                'success',
                'Document uploaded successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ConstructionHseSafetyMeeting $meeting,
        ConstructionHseSafetyMeetingDocument $document
    ): View {

        $this->validateDocumentRelation(
            $project,
            $meeting,
            $document
        );

        $document->load([
            'uploadedBy',
            'creator',
            'updater',
        ]);

        return view(
            'construction.hse.safety-meeting-documents.show',
            [
                'project' => $project,
                'meeting' => $meeting,
                'document' => $document,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD
    |--------------------------------------------------------------------------
    */

    public function download(
        Project $project,
        ConstructionHseSafetyMeeting $meeting,
        ConstructionHseSafetyMeetingDocument $document
    ) {

        $this->validateDocumentRelation(
            $project,
            $meeting,
            $document
        );


        abort_unless(
            Storage::disk('public')->exists(
                $document->file_path
            ),
            404
        );


        return Storage::disk('public')->download(
            $document->file_path,
            $document->original_file_name
                ?? $document->document_name
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ConstructionHseSafetyMeeting $meeting,
        ConstructionHseSafetyMeetingDocument $document
    ): RedirectResponse {

        $this->validateDocumentRelation(
            $project,
            $meeting,
            $document
        );


        /*
        |--------------------------------------------------------------------------
        | Delete Physical File
        |--------------------------------------------------------------------------
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
        |--------------------------------------------------------------------------
        | Delete Database Record
        |--------------------------------------------------------------------------
        */

        $document->delete();


        return redirect()
            ->route(
                'admin.projects.construction.hse.safety-meetings.documents.index',
                [
                    'project' => $project,
                    'meeting' => $meeting,
                ]
            )
            ->with(
                'success',
                'Document deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE MEETING RELATION
    |--------------------------------------------------------------------------
    */

    protected function validateMeetingRelation(
        Project $project,
        ConstructionHseSafetyMeeting $meeting
    ): void {

        abort_unless(
            (int) $meeting->project_id ===
            (int) $project->id,
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE DOCUMENT RELATION
    |--------------------------------------------------------------------------
    */

    protected function validateDocumentRelation(
        Project $project,
        ConstructionHseSafetyMeeting $meeting,
        ConstructionHseSafetyMeetingDocument $document
    ): void {

        $this->validateMeetingRelation(
            $project,
            $meeting
        );


        abort_unless(
            (int) $document
                ->construction_hse_safety_meeting_id ===
            (int) $meeting->id,
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE DOCUMENT NUMBER
    |--------------------------------------------------------------------------
    */

    protected function generateDocumentNumber(
        ConstructionHseSafetyMeeting $meeting
    ): string {

        $lastId =
            ConstructionHseSafetyMeetingDocument::max('id')
            ?? 0;


        return 'HSE-SMD-' .
            str_pad(
                $lastId + 1,
                6,
                '0',
                STR_PAD_LEFT
            );
    }
}