<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProcurementTender;
use App\Models\ProcurementTenderDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProcurementTenderDocumentController extends Controller
{
    /**
     * Ensure tender belongs to selected project.
     */
    private function ensureTenderBelongsToProject(
        Project $project,
        ProcurementTender $procurementTender
    ): void {
        abort_unless(
            (int) $procurementTender->project_id === (int) $project->id,
            404
        );
    }

    /**
     * Ensure document belongs to both project and tender.
     */
    private function ensureDocumentBelongsToProjectAndTender(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementTenderDocument $document
    ): void {
        abort_unless(
            (int) $procurementTender->project_id === (int) $project->id,
            404
        );

        abort_unless(
            (int) $document->project_id === (int) $project->id,
            404
        );

        abort_unless(
            (int) $document->procurement_tender_id ===
                (int) $procurementTender->id,
            404
        );
    }

    /**
     * Generate next document number for the tender.
     *
     * Format:
     * TND-004-DOC-001
     * TND-004-DOC-002
     * TND-004-DOC-003
     */
    private function generateDocumentNumber(
        Project $project,
        ProcurementTender $procurementTender
    ): string {
        /*
         * Lock the tender row so two users cannot generate
         * the same document number simultaneously.
         *
         * This method is called inside a DB transaction.
         */
        ProcurementTender::query()
            ->whereKey($procurementTender->id)
            ->lockForUpdate()
            ->firstOrFail();

        $documents = ProcurementTenderDocument::query()
            ->where('project_id', $project->id)
            ->where(
                'procurement_tender_id',
                $procurementTender->id
            )
            ->whereNotNull('document_number')
            ->pluck('document_number');

        $highestNumber = 0;

        foreach ($documents as $documentNumber) {

            if (
                preg_match(
                    '/-DOC-(\d+)$/',
                    $documentNumber,
                    $matches
                )
            ) {
                $number = (int) $matches[1];

                if ($number > $highestNumber) {
                    $highestNumber = $number;
                }
            }
        }

        $nextNumber = $highestNumber + 1;

        return sprintf(
            'TND-%03d-DOC-%03d',
            $procurementTender->id,
            $nextNumber
        );
    }

    /**
     * Display tender documents.
     */
    public function index(
        Project $project,
        ProcurementTender $procurementTender
    ): View {

        $this->ensureTenderBelongsToProject(
            $project,
            $procurementTender
        );

        $documents = ProcurementTenderDocument::query()
            ->where('project_id', $project->id)
            ->where(
                'procurement_tender_id',
                $procurementTender->id
            )
            ->latest()
            ->get();

        return view(
            'procurement.tender-documents.index',
            compact(
                'project',
                'procurementTender',
                'documents'
            )
        );
    }

    /**
     * Show create form.
     */
    public function create(
        Project $project,
        ProcurementTender $procurementTender
    ): View {

        $this->ensureTenderBelongsToProject(
            $project,
            $procurementTender
        );

        return view(
            'procurement.tender-documents.create',
            compact(
                'project',
                'procurementTender'
            )
        );
    }

    /**
     * Store tender document.
     */
    public function store(
        Request $request,
        Project $project,
        ProcurementTender $procurementTender
    ): RedirectResponse {

        $this->ensureTenderBelongsToProject(
            $project,
            $procurementTender
        );

        $validated = $request->validate([

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
                'required',
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

        DB::beginTransaction();

        try {

            /*
             * Generate document number while tender row
             * is locked.
             */
            $documentNumber = $this->generateDocumentNumber(
                $project,
                $procurementTender
            );

            /*
             * Store uploaded file.
             */
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

            /*
             * Create document.
             */
            ProcurementTenderDocument::create([

                'project_id' =>
                    $project->id,

                'procurement_tender_id' =>
                    $procurementTender->id,

                'document_number' =>
                    $documentNumber,

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

            DB::commit();

        } catch (\Throwable $e) {

            DB::rollBack();

            /*
             * If DB insert fails after file upload,
             * remove the uploaded file.
             */
            if (
                $filePath &&
                Storage::disk('public')->exists($filePath)
            ) {
                Storage::disk('public')->delete($filePath);
            }

            throw $e;
        }

        return redirect()
            ->route(
                'admin.projects.procurement.tenders.documents.index',
                [
                    'project' =>
                        $project,

                    'procurementTender' =>
                        $procurementTender,
                ]
            )
            ->with(
                'success',
                'Tender document ' .
                $documentNumber .
                ' uploaded successfully.'
            );
    }

    /**
     * Show tender document.
     */
    public function show(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementTenderDocument $document
    ): View {

        $this->ensureDocumentBelongsToProjectAndTender(
            $project,
            $procurementTender,
            $document
        );

        return view(
            'procurement.tender-documents.show',
            compact(
                'project',
                'procurementTender',
                'document'
            )
        );
    }

    /**
     * Show edit form.
     */
    public function edit(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementTenderDocument $document
    ): View {

        $this->ensureDocumentBelongsToProjectAndTender(
            $project,
            $procurementTender,
            $document
        );

        return view(
            'procurement.tender-documents.edit',
            compact(
                'project',
                'procurementTender',
                'document'
            )
        );
    }

    /**
     * Update tender document.
     *
     * Document number is intentionally NOT updated.
     */
    public function update(
        Request $request,
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementTenderDocument $document
    ): RedirectResponse {

        $this->ensureDocumentBelongsToProjectAndTender(
            $project,
            $procurementTender,
            $document
        );

        $validated = $request->validate([

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

        $oldFilePath = $document->file_path;

        $fileName = $document->file_name;
        $filePath = $document->file_path;

        $newFilePath = null;

        DB::beginTransaction();

        try {

            if ($request->hasFile('file')) {

                $file = $request->file('file');

                $fileName = $file->getClientOriginalName();

                $newFilePath = $file->store(
                    'procurement/tenders/' .
                    $procurementTender->id .
                    '/documents',
                    'public'
                );

                $filePath = $newFilePath;
            }

            $document->update([

                /*
                 * DO NOT update document_number.
                 */
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

            DB::commit();

        } catch (\Throwable $e) {

            DB::rollBack();

            if (
                $newFilePath &&
                Storage::disk('public')->exists($newFilePath)
            ) {
                Storage::disk('public')->delete($newFilePath);
            }

            throw $e;
        }

        /*
         * Delete old file after successful update.
         */
        if (
            $newFilePath &&
            $oldFilePath &&
            $oldFilePath !== $newFilePath &&
            Storage::disk('public')->exists($oldFilePath)
        ) {
            Storage::disk('public')->delete($oldFilePath);
        }

        return redirect()
            ->route(
                'admin.projects.procurement.tenders.documents.show',
                [
                    'project' =>
                        $project,

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

    /**
     * Delete tender document.
     */
    public function destroy(
        Project $project,
        ProcurementTender $procurementTender,
        ProcurementTenderDocument $document
    ): RedirectResponse {

        $this->ensureDocumentBelongsToProjectAndTender(
            $project,
            $procurementTender,
            $document
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
                'admin.projects.procurement.tenders.documents.index',
                [
                    'project' =>
                        $project,

                    'procurementTender' =>
                        $procurementTender,
                ]
            )
            ->with(
                'success',
                'Tender document deleted successfully.'
            );
    }
}