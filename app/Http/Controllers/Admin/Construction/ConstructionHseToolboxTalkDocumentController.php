<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionHseToolboxTalk;
use App\Models\ConstructionHseToolboxTalkDocument;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ConstructionHseToolboxTalkDocumentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        Project $project,
        ConstructionHseToolboxTalk $toolboxTalk
    ): View {

        $this->validateTalkRelation(
            $project,
            $toolboxTalk
        );

        $documents = $toolboxTalk
            ->documents()
            ->with([
                'uploadedBy',
                'creator',
                'updater',
            ])
            ->latest('id')
            ->get();

        return view(
            'construction.hse.toolbox-talk-documents.index',
            [
                'project' => $project,
                'toolboxTalk' => $toolboxTalk,
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
        ConstructionHseToolboxTalk $toolboxTalk
    ): View {

        $this->validateTalkRelation(
            $project,
            $toolboxTalk
        );

        $documentNumber =
            $this->generateDocumentNumber();

        return view(
            'construction.hse.toolbox-talk-documents.create',
            [
                'project' => $project,
                'toolboxTalk' => $toolboxTalk,
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
        ConstructionHseToolboxTalk $toolboxTalk
    ): RedirectResponse {

        $this->validateTalkRelation(
            $project,
            $toolboxTalk
        );


        $validated = $request->validate([

            'document_number' => [
                'required',
                'string',
                'max:100',
                'unique:construction_hse_toolbox_talk_documents,document_number',
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
        | Storage Directory
        |--------------------------------------------------------------------------
        */

        $directory =
            'construction/hse/toolbox-talks/' .
            $toolboxTalk->id;


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

        ConstructionHseToolboxTalkDocument::create([

            'construction_hse_toolbox_talk_id' =>
                $toolboxTalk->id,

            'document_number' =>
                $validated['document_number'],

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

            'file_size' =>
                $file->getSize(),

            'mime_type' =>
                $file->getMimeType(),

            'document_date' =>
                $validated['document_date'] ?? null,

            'uploaded_by' =>
                Auth::id(),

            'remarks' =>
                $validated['remarks'] ?? null,

            'created_by' =>
                Auth::id(),

            'updated_by' =>
                Auth::id(),

        ]);


        return redirect()
            ->route(
                'admin.projects.construction.hse.toolbox-talks.documents.index',
                [
                    'project' => $project,
                    'toolboxTalk' => $toolboxTalk,
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
        ConstructionHseToolboxTalk $toolboxTalk,
        ConstructionHseToolboxTalkDocument $document
    ): View {

        $this->validateDocumentRelation(
            $project,
            $toolboxTalk,
            $document
        );

        $document->load([
            'uploadedBy',
            'creator',
            'updater',
        ]);

        return view(
            'construction.hse.toolbox-talk-documents.show',
            [
                'project' => $project,
                'toolboxTalk' => $toolboxTalk,
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
        ConstructionHseToolboxTalk $toolboxTalk,
        ConstructionHseToolboxTalkDocument $document
    ) {

        $this->validateDocumentRelation(
            $project,
            $toolboxTalk,
            $document
        );


        abort_unless(
            $document->file_path &&
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
        ConstructionHseToolboxTalk $toolboxTalk,
        ConstructionHseToolboxTalkDocument $document
    ): RedirectResponse {

        $this->validateDocumentRelation(
            $project,
            $toolboxTalk,
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
                'admin.projects.construction.hse.toolbox-talks.documents.index',
                [
                    'project' => $project,
                    'toolboxTalk' => $toolboxTalk,
                ]
            )
            ->with(
                'success',
                'Document deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE TOOLBOX TALK
    |--------------------------------------------------------------------------
    */

    protected function validateTalkRelation(
        Project $project,
        ConstructionHseToolboxTalk $toolboxTalk
    ): void {

        abort_unless(
            (int) $toolboxTalk->project_id ===
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
        ConstructionHseToolboxTalk $toolboxTalk,
        ConstructionHseToolboxTalkDocument $document
    ): void {

        $this->validateTalkRelation(
            $project,
            $toolboxTalk
        );


        abort_unless(
            (int) $document
                ->construction_hse_toolbox_talk_id ===
            (int) $toolboxTalk->id,
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE DOCUMENT NUMBER
    |--------------------------------------------------------------------------
    */

    protected function generateDocumentNumber(): string
    {
        $lastId =
            ConstructionHseToolboxTalkDocument::max('id')
            ?? 0;

        return 'HSE-TBT-DOC-' .
            str_pad(
                $lastId + 1,
                6,
                '0',
                STR_PAD_LEFT
            );
    }
}