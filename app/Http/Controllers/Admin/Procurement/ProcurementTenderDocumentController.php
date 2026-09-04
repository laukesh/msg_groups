<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\ProcurementTender;
use App\Models\ProcurementTenderDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProcurementTenderDocumentController extends Controller
{
    public function index(
        ProcurementTender $procurementTender
    ): View {

        $documents = $procurementTender
            ->documents()
            ->latest()
            ->get();

        return view(
            'procurement.tender-documents.index',
            compact(
                'procurementTender',
                'documents'
            )
        );
    }


    public function create(
        ProcurementTender $procurementTender
    ): View {

        return view(
            'procurement.tender-documents.create',
            compact('procurementTender')
        );
    }


    public function store(
        Request $request,
        ProcurementTender $procurementTender
    ): RedirectResponse {

        $validated = $request->validate([

            'document_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'document_title' => [
                'required',
                'string',
                'max:255',
            ],

            'document_type' => [
                'required',
                'string',
                'max:100',
            ],

            'version' => [
                'required',
                'string',
                'max:50',
            ],

            'issue_date' => [
                'nullable',
                'date',
            ],

            'file' => [
                'nullable',
                'file',
                'max:51200',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:Draft,Published,Superseded,Cancelled',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        $fileName = null;
        $filePath = null;


        if ($request->hasFile('file')) {

            $file = $request->file('file');

            $fileName = $file->getClientOriginalName();

            $filePath = $file->store(
                'procurement/tenders/' .
                $procurementTender->id .
                '/documents',
                'public'
            );
        }


        $procurementTender->documents()->create([

            'document_number' =>
                $validated['document_number'] ?? null,

            'document_title' =>
                $validated['document_title'],

            'document_type' =>
                $validated['document_type'],

            'version' =>
                $validated['version'],

            'issue_date' =>
                $validated['issue_date'] ?? null,

            'file_name' =>
                $fileName,

            'file_path' =>
                $filePath,

            'description' =>
                $validated['description'] ?? null,

            'status' =>
                $validated['status'],

            'uploaded_by' =>
                auth()->id(),

            'uploaded_by_name' =>
                auth()->user()?->name,

            'remarks' =>
                $validated['remarks'] ?? null,

            'created_by' =>
                auth()->id(),
        ]);


        return redirect()
            ->route(
                'admin.procurement.tenders.documents.index',
                $procurementTender
            )
            ->with(
                'success',
                'Tender document uploaded successfully.'
            );
    }


    public function show(
        ProcurementTender $procurementTender,
        ProcurementTenderDocument $document
    ): View {

        abort_unless(
            $document->procurement_tender_id
                === $procurementTender->id,
            404
        );


        return view(
            'procurement.tender-documents.show',
            compact(
                'procurementTender',
                'document'
            )
        );
    }


    public function edit(
        ProcurementTender $procurementTender,
        ProcurementTenderDocument $document
    ): View {

        abort_unless(
            $document->procurement_tender_id
                === $procurementTender->id,
            404
        );


        return view(
            'procurement.tender-documents.edit',
            compact(
                'procurementTender',
                'document'
            )
        );
    }


    public function update(
        Request $request,
        ProcurementTender $procurementTender,
        ProcurementTenderDocument $document
    ): RedirectResponse {

        abort_unless(
            $document->procurement_tender_id
                === $procurementTender->id,
            404
        );


        $validated = $request->validate([

            'document_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'document_title' => [
                'required',
                'string',
                'max:255',
            ],

            'document_type' => [
                'required',
                'string',
                'max:100',
            ],

            'version' => [
                'required',
                'string',
                'max:50',
            ],

            'issue_date' => [
                'nullable',
                'date',
            ],

            'file' => [
                'nullable',
                'file',
                'max:51200',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:Draft,Published,Superseded,Cancelled',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);


        $fileName = $document->file_name;
        $filePath = $document->file_path;


        if ($request->hasFile('file')) {

            if (
                $filePath &&
                Storage::disk('public')->exists($filePath)
            ) {
                Storage::disk('public')->delete($filePath);
            }


            $file = $request->file('file');

            $fileName = $file->getClientOriginalName();

            $filePath = $file->store(
                'procurement/tenders/' .
                $procurementTender->id .
                '/documents',
                'public'
            );
        }


        $document->update([

            'document_number' =>
                $validated['document_number'] ?? null,

            'document_title' =>
                $validated['document_title'],

            'document_type' =>
                $validated['document_type'],

            'version' =>
                $validated['version'],

            'issue_date' =>
                $validated['issue_date'] ?? null,

            'file_name' =>
                $fileName,

            'file_path' =>
                $filePath,

            'description' =>
                $validated['description'] ?? null,

            'status' =>
                $validated['status'],

            'remarks' =>
                $validated['remarks'] ?? null,

            'updated_by' =>
                auth()->id(),
        ]);


        return redirect()
            ->route(
                'admin.procurement.tenders.documents.show',
                [
                    'procurementTender' =>
                        $procurementTender,

                    'document' =>
                        $document,
                ]
            )
            ->with(
                'success',
                'Tender document updated successfully.'
            );
    }


    public function destroy(
        ProcurementTender $procurementTender,
        ProcurementTenderDocument $document
    ): RedirectResponse {

        abort_unless(
            $document->procurement_tender_id
                === $procurementTender->id,
            404
        );


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
                'admin.procurement.tenders.documents.index',
                $procurementTender
            )
            ->with(
                'success',
                'Tender document deleted successfully.'
            );
    }
}