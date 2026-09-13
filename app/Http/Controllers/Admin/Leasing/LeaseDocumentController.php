<?php

namespace App\Http\Controllers\Admin\Leasing;

use App\Http\Controllers\Controller;
use App\Models\LeaseDocument;
use App\Models\LeaseAgreement;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LeaseDocumentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $documents = LeaseDocument::with([
            'agreement.tenant',
            'documentType',
            'verifiedBy'
        ])
        ->latest('id')
        ->paginate(20);

        return view(
            'admin.leasing.documents.index',
            compact('documents')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(Request $request)
    {
        $agreements = LeaseAgreement::with('tenant')
            ->orderBy('agreement_no')
            ->get();

        $documentTypes = DocumentType::where('status', 1)
								    ->orderBy('document_name')
								    ->get();

        $selectedAgreementId =
            $request->get('lease_agreement_id');

        return view(
            'admin.leasing.documents.create',
            compact(
                'agreements',
                'documentTypes',
                'selectedAgreementId'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'lease_agreement_id' => [
                'required',
                'integer',
                'exists:lease_agreements,id'
            ],

            'document_type_id' => [
                'required',
                'integer',
                'exists:document_types,id'
            ],

            'document_name' => [
                'required',
                'string',
                'max:200'
            ],

            'document_number' => [
                'nullable',
                'string',
                'max:100'
            ],

            'version_no' => [
                'nullable',
                'integer',
                'min:1'
            ],

            'file' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx',
                'max:10240'
            ],

            'issue_date' => [
                'nullable',
                'date'
            ],

            'expiry_date' => [
                'nullable',
                'date',
                'after_or_equal:issue_date'
            ],

            'remarks' => [
                'nullable',
                'string'
            ],

        ]);


        $document = new LeaseDocument();

        $document->lease_agreement_id =
            $validated['lease_agreement_id'];

        $document->document_type_id =
            $validated['document_type_id'];

        $document->document_name =
            $validated['document_name'];

        $document->document_number =
            $validated['document_number'] ?? null;

        $document->version_no =
            $validated['version_no'] ?? 1;

        $document->issue_date =
            $validated['issue_date'] ?? null;

        $document->expiry_date =
            $validated['expiry_date'] ?? null;

        $document->verification_status =
            'Pending';

        $document->remarks =
            $validated['remarks'] ?? null;

        $document->created_by =
            Auth::id();


        /*
        |--------------------------------------------------------------------------
        | File Upload
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('file')) {

            $file = $request->file('file');

            $path = $file->store(
                'lease_documents',
                'public'
            );

            $document->file_name =
                $file->getClientOriginalName();

            $document->file_path =
                $path;

            $document->file_size =
                $file->getSize();

            $document->file_extension =
                strtolower(
                    $file->getClientOriginalExtension()
                );
        }


        $document->save();


        return redirect()
            ->route(
                'admin.leasing.documents.show',
                $document->id
            )
            ->with(
                'success',
                'Lease document uploaded successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(LeaseDocument $document)
    {
        $document->load([
            'agreement.tenant',
            'documentType',
            'verifiedBy',
            'createdBy',
            'updatedBy'
        ]);

        return view(
            'admin.leasing.documents.show',
            compact('document')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(LeaseDocument $document)
    {
        $agreements = LeaseAgreement::with('tenant')
            ->orderBy('agreement_no')
            ->get();

        $documentTypes = DocumentType::where('status', 1)
        ->orderBy('document_name')
        ->get();

        return view(
            'admin.leasing.documents.edit',
            compact(
                'document',
                'agreements',
                'documentTypes'
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
        LeaseDocument $document
    ) {

        $validated = $request->validate([

            'lease_agreement_id' => [
                'required',
                'integer',
                'exists:lease_agreements,id'
            ],

            'document_type_id' => [
                'required',
                'integer',
                'exists:document_types,id'
            ],

            'document_name' => [
                'required',
                'string',
                'max:200'
            ],

            'document_number' => [
                'nullable',
                'string',
                'max:100'
            ],

            'version_no' => [
                'nullable',
                'integer',
                'min:1'
            ],

            'file' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx',
                'max:10240'
            ],

            'issue_date' => [
                'nullable',
                'date'
            ],

            'expiry_date' => [
                'nullable',
                'date',
                'after_or_equal:issue_date'
            ],

            'remarks' => [
                'nullable',
                'string'
            ],

        ]);


        $document->lease_agreement_id =
            $validated['lease_agreement_id'];

        $document->document_type_id =
            $validated['document_type_id'];

        $document->document_name =
            $validated['document_name'];

        $document->document_number =
            $validated['document_number'] ?? null;

        $document->version_no =
            $validated['version_no'] ?? 1;

        $document->issue_date =
            $validated['issue_date'] ?? null;

        $document->expiry_date =
            $validated['expiry_date'] ?? null;

        $document->remarks =
            $validated['remarks'] ?? null;

        $document->updated_by =
            Auth::id();


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

            $path = $file->store(
                'lease_documents',
                'public'
            );

            $document->file_name =
                $file->getClientOriginalName();

            $document->file_path =
                $path;

            $document->file_size =
                $file->getSize();

            $document->file_extension =
                strtolower(
                    $file->getClientOriginalExtension()
                );
        }


        $document->save();


        return redirect()
            ->route(
                'admin.leasing.documents.show',
                $document->id
            )
            ->with(
                'success',
                'Lease document updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function destroy(
        LeaseDocument $document
    ) {

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


        $document->updated_by =
            Auth::id();

        $document->save();

        $document->delete();


        return redirect()
            ->route(
                'admin.leasing.documents.index'
            )
            ->with(
                'success',
                'Lease document deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Verify
    |--------------------------------------------------------------------------
    */

    public function verify(
        LeaseDocument $document
    ) {

        $document->update([

            'verification_status' => 'Verified',

            'verified_by' => Auth::id(),

            'verified_at' => now(),

            'updated_by' => Auth::id(),

        ]);


        return back()->with(
            'success',
            'Document verified successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Reject
    |--------------------------------------------------------------------------
    */

    public function reject(
        Request $request,
        LeaseDocument $document
    ) {

        $request->validate([

            'remarks' => [
                'required',
                'string'
            ],

        ]);


        $document->update([

            'verification_status' => 'Rejected',

            'verified_by' => Auth::id(),

            'verified_at' => now(),

            'remarks' => $request->remarks,

            'updated_by' => Auth::id(),

        ]);


        return back()->with(
            'success',
            'Document rejected.'
        );
    }
}