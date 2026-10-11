<?php

namespace App\Http\Controllers\Admin\Fitout;

use App\Http\Controllers\Controller;
use App\Models\FitoutDocument;
use App\Models\FitoutRequest;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FitoutDocumentController extends Controller
{
    public function index()
    {
        $documents = FitoutDocument::with([
            'fitoutRequest',
            'documentType',
            'submittedBy',
        ])
        ->latest('id')
        ->paginate(20);

        return view(
            'admin.fitout.documents.index',
            compact('documents')
        );
    }

    public function create()
	{
	    $fitoutRequests = FitoutRequest::with([
	        'tenant',
	        'unit',
	    ])
	    ->whereNotIn('fitout_status', [
	        'Closed',
	        'Rejected',
	    ])
	    ->latest('id')
	    ->get();

	    $documentTypes = DocumentType::where('status', 1)
	        ->orderBy('document_name')
	        ->get();

	    return view(
	        'admin.fitout.documents.create',
	        compact(
	            'fitoutRequests',
	            'documentTypes'
	        )
	    );
	}

	public function store(Request $request)
	{
	    $validated = $request->validate([
	        'fitout_request_id' => [
	            'required',
	            'exists:fitout_requests,id',
	        ],

	        'document_type_id' => [
	            'required',
	            'exists:document_types,id',
	        ],

	        'document_title' => [
	            'required',
	            'string',
	            'max:200',
	        ],

	        'document_number' => [
	            'nullable',
	            'string',
	            'max:100',
	        ],

	        'version_no' => [
	            'nullable',
	            'string',
	            'max:20',
	        ],

	        'file' => [
	            'required',
	            'file',
	            'max:51200', // 50 MB
	            'mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx',
	        ],

	        'remarks' => [
	            'nullable',
	            'string',
	        ],
	    ]);

	    $file = $request->file('file');

	    /*
	    |--------------------------------------------------------------------------
	    | Generate Version
	    |--------------------------------------------------------------------------
	    */

	    $latestVersion = FitoutDocument::where(
	        'fitout_request_id',
	        $validated['fitout_request_id']
	    )
	    ->where(
	        'document_type_id',
	        $validated['document_type_id']
	    )
	    ->orderByDesc('id')
	    ->value('version_no');

	    if ($latestVersion) {

	        $version = (float) $latestVersion;

	        $versionNo = number_format(
	            $version + 1,
	            1,
	            '.',
	            ''
	        );

	    } else {

	        $versionNo = '1.0';
	    }

	    /*
	    |--------------------------------------------------------------------------
	    | Upload File
	    |--------------------------------------------------------------------------
	    */

	    $extension = strtolower(
	        $file->getClientOriginalExtension()
	    );

	    $originalName = $file->getClientOriginalName();

	    $fileName = time()
	        . '_'
	        . Str::random(10)
	        . '.'
	        . $extension;

	    $filePath = $file->storeAs(
	        'fitout/documents',
	        $fileName,
	        'public'
	    );

	    /*
	    |--------------------------------------------------------------------------
	    | Save Document
	    |--------------------------------------------------------------------------
	    */

	    FitoutDocument::create([
	        'uuid' => (string) Str::uuid(),

	        'fitout_request_id' =>
	            $validated['fitout_request_id'],

	        'document_type_id' =>
	            $validated['document_type_id'],

	        'document_title' =>
	            $validated['document_title'],

	        'document_number' =>
	            $validated['document_number'] ?? null,

	        'version_no' => $versionNo,

	        'file_name' => $originalName,

	        'file_path' => $filePath,

	        'file_extension' => $extension,

	        'file_size' => $file->getSize(),

	        'submitted_by' => auth()->id(),

	        'submitted_at' => now(),

	        'approval_status' => 'Pending',

	        'remarks' =>
	            $validated['remarks'] ?? null,

	        'created_by' => auth()->id(),

	        'updated_by' => auth()->id(),
	    ]);

	    return redirect()
	        ->route(
	            'admin.fitout.documents.index'
	        )
	        ->with(
	            'success',
	            'Fit-out document uploaded successfully.'
	        );
	}

	public function show($id)
	{
	    $document = FitoutDocument::with([
	        'fitoutRequest.tenant',
	        'fitoutRequest.unit',
	        'fitoutRequest.contractor',
	        'documentType',
	        'submittedBy',
	        'approvedBy',
	    ])->findOrFail($id);

	    /*
	    |--------------------------------------------------------------------------
	    | Version History
	    |--------------------------------------------------------------------------
	    */

	    $versions = FitoutDocument::where(
	        'fitout_request_id',
	        $document->fitout_request_id
	    )
	    ->where(
	        'document_type_id',
	        $document->document_type_id
	    )
	    ->orderByDesc('id')
	    ->get();

	    return view(
	        'admin.fitout.documents.show',
	        compact(
	            'document',
	            'versions'
	        )
	    );
	}

	public function review($id)
	{
	    $document = FitoutDocument::with([
	        'fitoutRequest.tenant',
	        'fitoutRequest.unit',
	        'fitoutRequest.contractor',
	        'documentType',
	        'submittedBy',
	    ])->findOrFail($id);

	    return view(
	        'admin.fitout.documents.review',
	        compact('document')
	    );
	}

	public function startReview($id)
	{
	    $document = FitoutDocument::findOrFail($id);

	    if ($document->approval_status !== 'Pending') {
	        return back()->with(
	            'error',
	            'This document is not available for review.'
	        );
	    }

	    $document->approval_status = 'Under Review';
	    $document->reviewed_by = auth()->id();
	    $document->reviewed_at = now();

	    $document->save();

	    return redirect()
	        ->route(
	            'admin.fitout.documents.review',
	            $document->id
	        )
	        ->with(
	            'success',
	            'Document moved to Under Review.'
	        );
	}

	public function approve($id)
	{
	    $document = FitoutDocument::findOrFail($id);

	    if (!in_array(
	        $document->approval_status,
	        ['Pending', 'Under Review']
	    )) {
	        return back()->with(
	            'error',
	            'This document cannot be approved.'
	        );
	    }

	    $document->approval_status = 'Approved';
	    $document->approved_by = auth()->id();
	    $document->approved_at = now();

	    $document->rejection_reason = null;

	    $document->save();

	    return redirect()
	        ->route(
	            'admin.fitout.documents.show',
	            $document->id
	        )
	        ->with(
	            'success',
	            'Document approved successfully.'
	        );
	}

	public function reject(Request $request, $id)
	{
	    $request->validate([
	        'rejection_reason' => [
	            'required',
	            'string',
	            'max:1000',
	        ],
	    ]);

	    $document = FitoutDocument::findOrFail($id);

	    if (!in_array(
	        $document->approval_status,
	        ['Pending', 'Under Review']
	    )) {
	        return back()->with(
	            'error',
	            'This document cannot be rejected.'
	        );
	    }

	    $document->approval_status = 'Rejected';
	    $document->rejection_reason =
	        $request->rejection_reason;

	    $document->reviewed_by = auth()->id();
	    $document->reviewed_at = now();

	    $document->save();

	    return redirect()
	        ->route(
	            'admin.fitout.documents.show',
	            $document->id
	        )
	        ->with(
	            'success',
	            'Document rejected successfully.'
	        );
	}

}