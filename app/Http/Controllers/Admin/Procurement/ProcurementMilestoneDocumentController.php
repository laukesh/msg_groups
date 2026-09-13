<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\ProcurementContract;
use App\Models\ProcurementContractMilestone;
use App\Models\ProcurementMilestoneDocument;
use App\Models\ProcurementMilestoneProgress;
use App\Models\ProcurementTender;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProcurementMilestoneDocumentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractMilestone $milestone
    ): View {

        $this->validateMilestone(
            $procurementTender,
            $contract,
            $milestone
        );

        $documents = $milestone
            ->documents()
            ->with([
                'progress',
            ])
            ->latest('id')
            ->get();

        return view(
            'procurement.milestone-documents.index',
            compact(
                'procurementTender',
                'contract',
                'milestone',
                'documents'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractMilestone $milestone
    ): View {

        $this->validateMilestone(
            $procurementTender,
            $contract,
            $milestone
        );

        if ($contract->status !== 'Active') {

            abort(
                403,
                'Documents can only be uploaded for an Active Contract.'
            );
        }

        $progressUpdates = $milestone
            ->progressUpdates()
            ->latest('progress_date')
            ->latest('id')
            ->get();

        return view(
            'procurement.milestone-documents.create',
            compact(
                'procurementTender',
                'contract',
                'milestone',
                'progressUpdates'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractMilestone $milestone
    ): RedirectResponse {

        $this->validateMilestone(
            $procurementTender,
            $contract,
            $milestone
        );

        if ($contract->status !== 'Active') {

            return back()
                ->withInput()
                ->withErrors([
                    'document' =>
                        'Documents can only be uploaded for an Active Contract.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Request
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'document_title' => [
                'required',
                'string',
                'max:255',
            ],

            'document_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'procurement_milestone_progress_id' => [
                'nullable',
                'integer',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'document' => [
                'required',
                'file',
                'max:51200',
                'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate Progress Belongs To Milestone
        |--------------------------------------------------------------------------
        */

        $progressId =
            $validated[
                'procurement_milestone_progress_id'
            ] ?? null;


        if ($progressId) {

            $progress =
                ProcurementMilestoneProgress::query()
                    ->where('id', $progressId)
                    ->where(
                        'procurement_contract_id',
                        $contract->id
                    )
                    ->where(
                        'procurement_contract_milestone_id',
                        $milestone->id
                    )
                    ->first();


            if (!$progress) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'procurement_milestone_progress_id' =>
                            'Selected progress update does not belong to this milestone.',
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Upload File
        |--------------------------------------------------------------------------
        */

        $file = $request->file('document');


        $directory =
            'procurement/contracts/'
            . $contract->id
            . '/milestones/'
            . $milestone->id;


        try {

            $path = $file->store(
                $directory,
                'public'
            );

        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->withErrors([
                    'document' =>
                        'Unable to upload the document. Please try again.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Generate Document Number
        |--------------------------------------------------------------------------
        |
        | Format:
        |
        | DOC-2026-0001
        | DOC-2026-0002
        | DOC-2026-0003
        |
        */

        $year = now()->format('Y');

        $lastDocument =
            ProcurementMilestoneDocument::query()
                ->where(
                    'document_number',
                    'like',
                    'DOC-' . $year . '-%'
                )
                ->orderByDesc('id')
                ->first();


        if ($lastDocument && $lastDocument->document_number) {

            $lastNumber =
                (int) substr(
                    $lastDocument->document_number,
                    -4
                );

            $nextNumber =
                $lastNumber + 1;

        } else {

            $nextNumber = 1;
        }


        $documentNumber =
            'DOC-'
            . $year
            . '-'
            . str_pad(
                $nextNumber,
                4,
                '0',
                STR_PAD_LEFT
            );


        /*
        |--------------------------------------------------------------------------
        | Create Document Record
        |--------------------------------------------------------------------------
        */

        try {

            ProcurementMilestoneDocument::create([

                'procurement_contract_id' =>
                    $contract->id,

                'procurement_contract_milestone_id' =>
                    $milestone->id,

                'procurement_milestone_progress_id' =>
                    $progressId,

                'document_number' =>
                    $documentNumber,

                'document_title' =>
                    $validated['document_title'],

                'document_type' =>
                    $validated['document_type']
                    ?? null,

                'file_name' =>
                    $file->getClientOriginalName(),

                'file_path' =>
                    $path,

                'file_extension' =>
                    strtolower(
                        $file->getClientOriginalExtension()
                    ),

                'mime_type' =>
                    $file->getMimeType(),

                'file_size' =>
                    $file->getSize(),

                'description' =>
                    $validated['description']
                    ?? null,

                'status' =>
                    'Submitted',

                'uploaded_by' =>
                    auth()->id(),

                'uploaded_at' =>
                    now(),

                'created_by' =>
                    auth()->id(),
            ]);

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Delete Uploaded File If Database Insert Fails
            |--------------------------------------------------------------------------
            */

            if ($path) {

                Storage::disk('public')->delete(
                    $path
                );
            }


            return back()
                ->withInput()
                ->withErrors([
                    'document' =>
                        'Unable to save the document. Please try again.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.procurement.tenders.contracts.milestones.documents.index',
                [
                    'procurementTender' =>
                        $procurementTender,

                    'contract' =>
                        $contract,

                    'milestone' =>
                        $milestone,
                ]
            )
            ->with(
                'success',
                'Milestone document '
                . $documentNumber
                . ' uploaded successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | VERIFY
    |--------------------------------------------------------------------------
    */

    public function verify(
        Request $request,
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractMilestone $milestone,
        ProcurementMilestoneDocument $document
    ): RedirectResponse {

        $this->validateDocument(
            $procurementTender,
            $contract,
            $milestone,
            $document
        );


        if ($document->status !== 'Submitted') {

            return back()->with(
                'error',
                'Only Submitted documents can be verified.'
            );
        }


        $validated = $request->validate([
            'verification_remarks' => [
                'nullable',
                'string',
            ],
        ]);


        $document->update([

            'status' =>
                'Verified',

            'verified_by' =>
                auth()->id(),

            'verified_at' =>
                now(),

            'verification_remarks' =>
                $validated['verification_remarks']
                ?? null,

            'updated_by' =>
                auth()->id(),
        ]);


        return back()->with(
            'success',
            'Document verified successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REJECT
    |--------------------------------------------------------------------------
    */

    public function reject(
        Request $request,
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractMilestone $milestone,
        ProcurementMilestoneDocument $document
    ): RedirectResponse {

        $this->validateDocument(
            $procurementTender,
            $contract,
            $milestone,
            $document
        );


        if ($document->status !== 'Submitted') {

            return back()->with(
                'error',
                'Only Submitted documents can be rejected.'
            );
        }


        $validated = $request->validate([
            'verification_remarks' => [
                'required',
                'string',
            ],
        ]);


        $document->update([

            'status' =>
                'Rejected',

            'verified_by' =>
                auth()->id(),

            'verified_at' =>
                now(),

            'verification_remarks' =>
                $validated['verification_remarks'],

            'updated_by' =>
                auth()->id(),
        ]);


        return back()->with(
            'success',
            'Document rejected successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractMilestone $milestone,
        ProcurementMilestoneDocument $document
    ): RedirectResponse {

        $this->validateDocument(
            $procurementTender,
            $contract,
            $milestone,
            $document
        );


        if ($document->status === 'Verified') {

            return back()->with(
                'error',
                'Verified documents cannot be deleted.'
            );
        }


        if ($document->file_path) {

            Storage::disk('public')->delete(
                $document->file_path
            );
        }


        $document->delete();


        return back()->with(
            'success',
            'Document deleted successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Milestone
    |--------------------------------------------------------------------------
    */

    private function validateMilestone(
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractMilestone $milestone
    ): void {

        abort_unless(
            $contract->procurement_tender_id
                === $procurementTender->id,
            404
        );


        abort_unless(
            $milestone->procurement_contract_id
                === $contract->id,
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Document
    |--------------------------------------------------------------------------
    */

    private function validateDocument(
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractMilestone $milestone,
        ProcurementMilestoneDocument $document
    ): void {

        $this->validateMilestone(
            $procurementTender,
            $contract,
            $milestone
        );


        abort_unless(
            $document->procurement_contract_id
                === $contract->id,
            404
        );


        abort_unless(
            $document->procurement_contract_milestone_id
                === $milestone->id,
            404
        );
    }
}