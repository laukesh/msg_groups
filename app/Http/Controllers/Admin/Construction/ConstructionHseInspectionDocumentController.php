<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionHseInspection;
use App\Models\ConstructionHseInspectionDocument;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ConstructionHseInspectionDocumentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        Project $project,
        ConstructionHseInspection $inspection
    ): View {

        $this->validateInspectionRelation(
            $project,
            $inspection
        );

        $documents = $inspection
            ->documents()
            ->with([
                'uploadedBy',
                'creator',
                'updater',
            ])
            ->latest('id')
            ->get();

        return view(
            'construction.hse.inspection-documents.index',
            [
                'project' => $project,
                'inspection' => $inspection,
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
        ConstructionHseInspection $inspection
    ): View {

        $this->validateInspectionRelation(
            $project,
            $inspection
        );

        $documentNumber =
            $this->generateDocumentNumber();

        return view(
            'construction.hse.inspection-documents.create',
            [
                'project' => $project,
                'inspection' => $inspection,
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
        ConstructionHseInspection $inspection
    ): RedirectResponse {

        $this->validateInspectionRelation(
            $project,
            $inspection
        );


        $validated = $request->validate([

            'document_number' => [
                'required',
                'string',
                'max:100',
                'unique:construction_hse_inspection_documents,document_number',
            ],

            'document_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'document_title' => [
                'required',
                'string',
                'max:255',
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

            'description' => [
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
        | Upload File
        |--------------------------------------------------------------------------
        */

        $file = $request->file('file');

        $storedPath = $file->store(
            'construction/hse/inspections/' .
            $inspection->id .
            '/documents',
            'public'
        );


        /*
        |--------------------------------------------------------------------------
        | Create Document
        |--------------------------------------------------------------------------
        */

        $document =
            ConstructionHseInspectionDocument::create([

                'construction_hse_inspection_id' =>
                    $inspection->id,

                'document_number' =>
                    $validated['document_number'],

                'document_type' =>
                    $validated['document_type'] ?? null,

                'document_title' =>
                    $validated['document_title'],

                'file_name' =>
                    $file->getClientOriginalName(),

                'file_path' =>
                    $storedPath,

                'file_type' =>
                    $file->getClientMimeType(),

                'file_size' =>
                    $file->getSize(),

                'document_date' =>
                    $validated['document_date'] ?? null,

                'description' =>
                    $validated['description'] ?? null,

                'remarks' =>
                    $validated['remarks'] ?? null,

                'uploaded_by' =>
                    Auth::id(),

                'created_by' =>
                    Auth::id(),

                'updated_by' =>
                    Auth::id(),

            ]);


        return redirect()
            ->route(
                'admin.projects.construction.hse.inspections.documents.show',
                [
                    'project' => $project,
                    'inspection' => $inspection,
                    'document' => $document,
                ]
            )
            ->with(
                'success',
                'Inspection document uploaded successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ConstructionHseInspection $inspection,
        ConstructionHseInspectionDocument $document
    ): View {

        $this->validateInspectionRelation(
            $project,
            $inspection
        );


        abort_unless(
            (int) $document->construction_hse_inspection_id ===
            (int) $inspection->id,
            404
        );


        $document->load([
            'uploadedBy',
            'creator',
            'updater',
        ]);


        return view(
            'construction.hse.inspection-documents.show',
            [
                'project' => $project,
                'inspection' => $inspection,
                'document' => $document,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ConstructionHseInspection $inspection,
        ConstructionHseInspectionDocument $document
    ): RedirectResponse {

        $this->validateInspectionRelation(
            $project,
            $inspection
        );


        abort_unless(
            (int) $document->construction_hse_inspection_id ===
            (int) $inspection->id,
            404
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


        $document->delete();


        return redirect()
            ->route(
                'admin.projects.construction.hse.inspections.documents.index',
                [
                    'project' => $project,
                    'inspection' => $inspection,
                ]
            )
            ->with(
                'success',
                'Inspection document deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD
    |--------------------------------------------------------------------------
    */

    public function download(
        Project $project,
        ConstructionHseInspection $inspection,
        ConstructionHseInspectionDocument $document
    ) {

        $this->validateInspectionRelation(
            $project,
            $inspection
        );


        abort_unless(
            (int) $document->construction_hse_inspection_id ===
            (int) $inspection->id,
            404
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
            $document->file_name
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE INSPECTION
    |--------------------------------------------------------------------------
    */

    protected function validateInspectionRelation(
        Project $project,
        ConstructionHseInspection $inspection
    ): void {

        abort_unless(
            (int) $inspection->project_id ===
            (int) $project->id,
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DOCUMENT NUMBER
    |--------------------------------------------------------------------------
    */

    protected function generateDocumentNumber(): string
    {
        $lastId =
            ConstructionHseInspectionDocument::max('id') ?? 0;

        return 'HSE-INS-DOC-' .
            str_pad(
                $lastId + 1,
                5,
                '0',
                STR_PAD_LEFT
            );
    }
}