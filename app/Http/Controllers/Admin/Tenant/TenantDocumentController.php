<?php

namespace App\Http\Controllers\Admin\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\TenantDocument;
use App\Models\DocumentType;
use App\Models\TenantHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TenantDocumentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Document Index
    |--------------------------------------------------------------------------
    */

    public function index($tenantId)
    {
        $tenant = Tenant::findOrFail($tenantId);

        $documents = TenantDocument::with('documentType')
            ->where('tenant_id', $tenant->id)
            ->latest('id')
            ->get();

        $documentTypes = DocumentType::orderBy('document_name')
            ->get();

        return view(
            'admin.tenants.documents.index',
            compact(
                'tenant',
                'documents',
                'documentTypes'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store Document
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        $tenantId
    ) {
        $tenant = Tenant::findOrFail($tenantId);

        $validated = $request->validate([

            'document_type_id' => [
                'required',
                'exists:document_types,id',
            ],

            'document_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'file' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png,doc,docx',
                'max:5120',
            ],

            'issue_date' => [
                'nullable',
                'date',
            ],

            'expiry_date' => [
                'nullable',
                'date',
                'after_or_equal:issue_date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        DB::transaction(function () use (
            $tenant,
            $validated,
            $request
        ) {

            /*
            |--------------------------------------------------------------------------
            | Upload File
            |--------------------------------------------------------------------------
            */

            $file = $request->file('file');

            $fileName = $file->getClientOriginalName();

            $filePath = $file->store(
                'tenant_documents/' . $tenant->id,
                'public'
            );


            /*
            |--------------------------------------------------------------------------
            | Create Document
            |--------------------------------------------------------------------------
            */

            $document = TenantDocument::create([

                'tenant_id' =>
                    $tenant->id,

                'document_type_id' =>
                    $validated['document_type_id'],

                'document_number' =>
                    $validated['document_number'] ?? null,

                'file_name' =>
                    $fileName,

                'file_path' =>
                    $filePath,

                'issue_date' =>
                    $validated['issue_date'] ?? null,

                'expiry_date' =>
                    $validated['expiry_date'] ?? null,

                'verification_status' =>
                    'Pending',

                'remarks' =>
                    $validated['remarks'] ?? null,

                'created_by' =>
                    auth()->id(),

                'updated_by' =>
                    auth()->id(),
            ]);

            TenantHistory::create([
                'tenant_id' => $tenant->id,
                'activity_type' => 'Document Uploaded',
                'reference_module' => 'Tenant Document',
                'reference_id' => $document->id,
                'description' => 'Tenant document was uploaded.',
                'activity_date' => now(),
                'performed_by' => auth()->id(),
            ]);


        });


        return redirect()
            ->route(
                'admin.tenants.documents.index',
                $tenant->id
            )
            ->with(
                'success',
                'Tenant document uploaded successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit Document
    |--------------------------------------------------------------------------
    */

    public function edit(
        $tenantId,
        $documentId
    ) {
        $tenant = Tenant::findOrFail($tenantId);

        $document = TenantDocument::where(
            'tenant_id',
            $tenant->id
        )->findOrFail($documentId);

        $documentTypes = DocumentType::orderBy('document_name')
            ->get();

        return view(
            'admin.tenants.documents.edit',
            compact(
                'tenant',
                'document',
                'documentTypes'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Document
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $tenantId,
        $documentId
    ) {
        $tenant = Tenant::findOrFail($tenantId);

        $document = TenantDocument::where(
            'tenant_id',
            $tenant->id
        )->findOrFail($documentId);

        $validated = $request->validate([

            'document_type_id' => [
                'required',
                'exists:document_types,id',
            ],

            'document_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'file' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png,doc,docx',
                'max:5120',
            ],

            'issue_date' => [
                'nullable',
                'date',
            ],

            'expiry_date' => [
                'nullable',
                'date',
                'after_or_equal:issue_date',
            ],

            'verification_status' => [
                'required',
                'in:Pending,Verified,Rejected',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        DB::transaction(function () use (
            $request,
            $validated,
            $document
        ) {

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
                    'tenant_documents/' .
                    $document->tenant_id,
                    'public'
                );

                $document->file_name =
                    $file->getClientOriginalName();

                $document->file_path =
                    $filePath;
            }


            /*
            |--------------------------------------------------------------------------
            | Update Document
            |--------------------------------------------------------------------------
            */

            $document->document_type_id =
                $validated['document_type_id'];

            $document->document_number =
                $validated['document_number'] ?? null;

            $document->issue_date =
                $validated['issue_date'] ?? null;

            $document->expiry_date =
                $validated['expiry_date'] ?? null;

            $document->verification_status =
                $validated['verification_status'];

            $document->remarks =
                $validated['remarks'] ?? null;

            $document->updated_by =
                auth()->id();

            $document->save();
        });


        return redirect()
            ->route(
                'admin.tenants.documents.index',
                $tenant->id
            )
            ->with(
                'success',
                'Tenant document updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Document
    |--------------------------------------------------------------------------
    */

    public function destroy(
        $tenantId,
        $documentId
    ) {
        $tenant = Tenant::findOrFail($tenantId);

        $document = TenantDocument::where(
            'tenant_id',
            $tenant->id
        )->findOrFail($documentId);


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
        | Soft Delete Database Record
        |--------------------------------------------------------------------------
        */

        $document->delete();


        return redirect()
            ->route(
                'admin.tenants.documents.index',
                $tenant->id
            )
            ->with(
                'success',
                'Tenant document deleted successfully.'
            );
    }
}