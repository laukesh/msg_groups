<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProcurementContract;
use App\Models\ProcurementContractMilestone;
use App\Models\ProcurementMilestoneDocument;
use App\Models\ProcurementMilestoneProgress;
use App\Models\ProcurementTender;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractMilestone $milestone
    ): View {

        $this->validateMilestone(
            $project,
            $procurementTender,
            $contract,
            $milestone
        );

        $documents = ProcurementMilestoneDocument::query()
            ->where('project_id', $project->id)
            ->where(
                'procurement_contract_id',
                $contract->id
            )
            ->where(
                'procurement_contract_milestone_id',
                $milestone->id
            )
            ->with('progress')
            ->latest('id')
            ->get();

        return view(
            'procurement.milestone-documents.index',
            compact(
                'project',
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
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractMilestone $milestone
    ): View {

        $this->validateMilestone(
            $project,
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
                'project',
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
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractMilestone $milestone
    ): RedirectResponse {

        $this->validateMilestone(
            $project,
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
        | Validate Progress
        |--------------------------------------------------------------------------
        */

        $progressId =
            $validated[
                'procurement_milestone_progress_id'
            ] ?? null;


        if ($progressId) {

            $progress = ProcurementMilestoneProgress::query()
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
        | File
        |--------------------------------------------------------------------------
        */

        $file = $request->file('document');

        $directory =
            'procurement/contracts/'
            . $contract->id
            . '/milestones/'
            . $milestone->id;


        $path = null;


        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Lock Project
            |--------------------------------------------------------------------------
            |
            | Prevent two simultaneous uploads from generating
            | the same document number.
            |
            */

            Project::query()
                ->whereKey($project->id)
                ->lockForUpdate()
                ->firstOrFail();


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
            | Numbering is now project scoped.
            |
            */

            $year = now()->format('Y');

            $lastDocument = ProcurementMilestoneDocument::query()
                ->where('project_id', $project->id)
                ->where(
                    'document_number',
                    'like',
                    'DOC-' . $year . '-%'
                )
                ->orderByDesc('id')
                ->first();


            $nextNumber = 1;


            if (
                $lastDocument &&
                $lastDocument->document_number
            ) {

                if (
                    preg_match(
                        '/^DOC-' . $year . '-(\d+)$/',
                        $lastDocument->document_number,
                        $matches
                    )
                ) {

                    $nextNumber =
                        ((int) $matches[1]) + 1;
                }
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
            | Upload File
            |--------------------------------------------------------------------------
            */

            $path = $file->store(
                $directory,
                'public'
            );


            /*
            |--------------------------------------------------------------------------
            | Create Document
            |--------------------------------------------------------------------------
            */

            ProcurementMilestoneDocument::create([

                'project_id' =>
                    $project->id,

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


            DB::commit();

        } catch (\Throwable $e) {

            DB::rollBack();

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
                'admin.projects.procurement.tenders.contracts.milestones.documents.index',
                [
                    'project' =>
                        $project,

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
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractMilestone $milestone,
        ProcurementMilestoneDocument $document
    ): RedirectResponse {

        $this->validateDocument(
            $project,
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
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractMilestone $milestone,
        ProcurementMilestoneDocument $document
    ): RedirectResponse {

        $this->validateDocument(
            $project,
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
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractMilestone $milestone,
        ProcurementMilestoneDocument $document
    ): RedirectResponse {

        $this->validateDocument(
            $project,
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
    | VALIDATE MILESTONE
    |--------------------------------------------------------------------------
    */

    private function validateMilestone(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractMilestone $milestone
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Tender → Project
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $procurementTender->project_id ===
                (int) $project->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Contract → Project
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $contract->project_id ===
                (int) $project->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Contract → Tender
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $contract->procurement_tender_id ===
                (int) $procurementTender->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Milestone → Contract
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $milestone->procurement_contract_id ===
                (int) $contract->id,
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE DOCUMENT
    |--------------------------------------------------------------------------
    */

    private function validateDocument(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementContract $contract,
        ProcurementContractMilestone $milestone,
        ProcurementMilestoneDocument $document
    ): void {

        $this->validateMilestone(
            $project,
            $procurementTender,
            $contract,
            $milestone
        );


        /*
        |--------------------------------------------------------------------------
        | Document → Project
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $document->project_id ===
                (int) $project->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Document → Contract
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $document->procurement_contract_id ===
                (int) $contract->id,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Document → Milestone
        |--------------------------------------------------------------------------
        */

        abort_unless(
            (int) $document->procurement_contract_milestone_id ===
                (int) $milestone->id,
            404
        );
    }
}