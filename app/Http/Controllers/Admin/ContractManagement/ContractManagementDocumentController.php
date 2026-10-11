<?php

namespace App\Http\Controllers\Admin\ContractManagement;

use App\Http\Controllers\Controller;
use App\Models\ContractManagementContract;
use App\Models\ContractManagementDocument;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ContractManagementDocumentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(
        Project $project,
        ContractManagementContract $contract
    ): View {

        $this->validateContract(
            $project,
            $contract
        );


        $documents =
            ContractManagementDocument::query()
                ->where(
                    'contract_management_contract_id',
                    $contract->id
                )
                ->with([
                    'uploader',
                    'updater',
                ])
                ->orderByDesc('document_date')
                ->orderByDesc('id')
                ->get();


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $summary = [

            'total' =>
                $documents->count(),

            'active' =>
                $documents
                    ->where(
                        'status',
                        'Active'
                    )
                    ->count(),

            'archived' =>
                $documents
                    ->where(
                        'status',
                        'Archived'
                    )
                    ->count(),

            'total_size' =>
                (int) $documents->sum(
                    'file_size'
                ),

            'document_types' =>
                $documents
                    ->groupBy('document_type')
                    ->count(),
        ];


        /*
        |--------------------------------------------------------------------------
        | Document Type Summary
        |--------------------------------------------------------------------------
        */

        $typeSummary =
            $documents
                ->groupBy('document_type')
                ->map(
                    function ($items) {

                        return $items->count();

                    }
                )
                ->sortDesc();


        return view(
            'contract-management.documents.index',
            compact(
                'project',
                'contract',
                'documents',
                'summary',
                'typeSummary'
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
        ContractManagementContract $contract
    ): View {

        $this->validateContract(
            $project,
            $contract
        );


        return view(
            'contract-management.documents.create',
            compact(
                'project',
                'contract'
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
        ContractManagementContract $contract
    ): RedirectResponse {

        $this->validateContract(
            $project,
            $contract
        );


        $validated = $request->validate([

            'document_title' =>
                'required|string|max:255',

            'document_type' =>
                'required|string|max:100',

            'document_date' =>
                'nullable|date',

            'document_version' =>
                'nullable|string|max:50',

            'description' =>
                'nullable|string',

            'status' =>
                'required|in:Active,Archived,Draft',

            'document_file' =>
                'required|file|max:51200|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Generate Document Number
        |--------------------------------------------------------------------------
        */

        $validated['document_number'] =
            $this->generateDocumentNumber();


        /*
        |--------------------------------------------------------------------------
        | Project / Contract
        |--------------------------------------------------------------------------
        */

        $validated['project_id'] =
            $project->id;


        $validated[
            'contract_management_contract_id'
        ] =
            $contract->id;


        /*
        |--------------------------------------------------------------------------
        | Upload File
        |--------------------------------------------------------------------------
        */

        $file =
            $request->file(
                'document_file'
            );


        $fileName =
            $file->getClientOriginalName();


        $mimeType =
            $file->getClientMimeType();


        $fileSize =
            $file->getSize();


        $directory =
            'contract-documents/' .
            $project->id .
            '/' .
            $contract->id;


        $filePath =
            $file->store(
                $directory,
                'public'
            );


        $validated['file_name'] =
            $fileName;


        $validated['file_path'] =
            $filePath;


        $validated['file_size'] =
            $fileSize;


        $validated['mime_type'] =
            $mimeType;


        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        $validated['uploaded_by'] =
            Auth::id();

        $validated['updated_by'] =
            Auth::id();


        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        ContractManagementDocument::create(
            $validated
        );


        return redirect()
            ->route(
                'admin.projects.contract-management.contracts.documents.index',
                [
                    $project,
                    $contract,
                ]
            )
            ->with(
                'success',
                'Contract document uploaded successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(
        Project $project,
        ContractManagementContract $contract,
        ContractManagementDocument $document
    ): View {

        $this->validateContract(
            $project,
            $contract
        );


        $this->validateDocument(
            $contract,
            $document
        );


        return view(
            'contract-management.documents.edit',
            compact(
                'project',
                'contract',
                'document'
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
        ContractManagementContract $contract,
        ContractManagementDocument $document
    ): RedirectResponse {

        $this->validateContract(
            $project,
            $contract
        );


        $this->validateDocument(
            $contract,
            $document
        );


        $validated = $request->validate([

            'document_title' =>
                'required|string|max:255',

            'document_type' =>
                'required|string|max:100',

            'document_date' =>
                'nullable|date',

            'document_version' =>
                'nullable|string|max:50',

            'description' =>
                'nullable|string',

            'status' =>
                'required|in:Active,Archived,Draft',

            'document_file' =>
                'nullable|file|max:51200|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Replace File
        |--------------------------------------------------------------------------
        */

        if (
            $request->hasFile(
                'document_file'
            )
        ) {

            /*
            |----------------------------------------------------------------------
            | Delete Old File
            |----------------------------------------------------------------------
            */

            if (
                $document->file_path
                &&
                Storage::disk('public')
                    ->exists(
                        $document->file_path
                    )
            ) {

                Storage::disk('public')
                    ->delete(
                        $document->file_path
                    );
            }


            $file =
                $request->file(
                    'document_file'
                );


            $directory =
                'contract-documents/' .
                $project->id .
                '/' .
                $contract->id;


            $filePath =
                $file->store(
                    $directory,
                    'public'
                );


            $validated['file_name'] =
                $file->getClientOriginalName();


            $validated['file_path'] =
                $filePath;


            $validated['file_size'] =
                $file->getSize();


            $validated['mime_type'] =
                $file->getClientMimeType();
        }


        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        $validated['updated_by'] =
            Auth::id();


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $document->update(
            $validated
        );


        return redirect()
            ->route(
                'admin.projects.contract-management.contracts.documents.index',
                [
                    $project,
                    $contract,
                ]
            )
            ->with(
                'success',
                'Contract document updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Download
    |--------------------------------------------------------------------------
    */

    public function download(
        Project $project,
        ContractManagementContract $contract,
        ContractManagementDocument $document
    ) {

        $this->validateContract(
            $project,
            $contract
        );


        $this->validateDocument(
            $contract,
            $document
        );


        if (
            !$document->file_path
            ||
            !Storage::disk('public')
                ->exists(
                    $document->file_path
                )
        ) {

            abort(
                404,
                'Document file not found.'
            );
        }


        return Storage::disk('public')
            ->download(
                $document->file_path,
                $document->file_name
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ContractManagementContract $contract,
        ContractManagementDocument $document
    ): RedirectResponse {

        $this->validateContract(
            $project,
            $contract
        );


        $this->validateDocument(
            $contract,
            $document
        );


        /*
        |--------------------------------------------------------------------------
        | Delete Physical File
        |--------------------------------------------------------------------------
        */

        if (
            $document->file_path
            &&
            Storage::disk('public')
                ->exists(
                    $document->file_path
                )
        ) {

            Storage::disk('public')
                ->delete(
                    $document->file_path
                );
        }


        $document->delete();


        return redirect()
            ->route(
                'admin.projects.contract-management.contracts.documents.index',
                [
                    $project,
                    $contract,
                ]
            )
            ->with(
                'success',
                'Contract document deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Document Number
    |--------------------------------------------------------------------------
    */

    protected function generateDocumentNumber(): string
    {
        $lastId =
            ContractManagementDocument::max('id')
            ??
            0;


        return 'DOC-' .
            str_pad(
                $lastId + 1,
                6,
                '0',
                STR_PAD_LEFT
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Contract
    |--------------------------------------------------------------------------
    */

    protected function validateContract(
        Project $project,
        ContractManagementContract $contract
    ): void {

        if (
            (int) $contract->project_id
            !==
            (int) $project->id
        ) {

            abort(
                404,
                'Contract does not belong to this project.'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Document
    |--------------------------------------------------------------------------
    */

    protected function validateDocument(
        ContractManagementContract $contract,
        ContractManagementDocument $document
    ): void {

        if (
            (int)
            $document->contract_management_contract_id
            !==
            (int) $contract->id
        ) {

            abort(
                404,
                'Document does not belong to this contract.'
            );
        }
    }
}