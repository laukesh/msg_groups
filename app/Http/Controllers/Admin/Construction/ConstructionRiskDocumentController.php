<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionRisk;
use App\Models\ConstructionRiskDocument;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ConstructionRiskDocumentController extends Controller
{
    /**
     * List documents.
     */
    public function index(
        Project $project,
        ConstructionRisk $risk
    ): View {
        $this->validateRiskProject($project, $risk);

        $documents = $risk->documents()
            ->with('uploadedBy')
            ->latest()
            ->paginate(20);

        return view(
            'construction.risks.documents.index',
            compact(
                'project',
                'risk',
                'documents'
            )
        );
    }

    /**
     * Create document form.
     */
    public function create(
        Project $project,
        ConstructionRisk $risk
    ): View {
        $this->validateRiskProject($project, $risk);

        return view(
            'construction.risks.documents.create',
            compact(
                'project',
                'risk'
            )
        );
    }

    /**
     * Store document.
     */
    public function store(
        Request $request,
        Project $project,
        ConstructionRisk $risk
    ): RedirectResponse {
        $this->validateRiskProject($project, $risk);

        $validated = $request->validate([
            'document_type' => [
                'required',
                'in:Risk Assessment,Risk Register,Risk Analysis,Mitigation Plan,Schedule,Cost Estimate,Correspondence,Drawing,RFI,Meeting Minutes,Site Photo,Report,Supporting Evidence,Other',
            ],

            'document_title' => [
                'required',
                'string',
                'max:255',
            ],

            'document' => [
                'required',
                'file',
                'max:51200',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ]);

        $file = $request->file('document');

        $directory = 'construction/risks/' .
            $risk->id .
            '/documents';

        $extension = $file->getClientOriginalExtension();

        $storedFileName = Str::uuid() .
            ($extension ? '.' . $extension : '');

        $filePath = $file->storeAs(
            $directory,
            $storedFileName,
            'public'
        );

        ConstructionRiskDocument::create([
            'construction_risk_id' => $risk->id,
            'document_type' => $validated['document_type'],
            'document_title' => $validated['document_title'],
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'description' => $validated['description'] ?? null,
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()
            ->route(
                'admin.projects.construction.risks.show',
                [
                    'project' => $project,
                    'risk' => $risk,
                ]
            )
            ->with(
                'success',
                'Risk document uploaded successfully.'
            );
    }

    /**
     * View document.
     */
    public function view(
        Project $project,
        ConstructionRisk $risk,
        ConstructionRiskDocument $document
    ) {
        $this->validateRiskProject($project, $risk);
        $this->validateDocumentRisk($risk, $document);

        if (
            !Storage::disk('public')
                ->exists($document->file_path)
        ) {
            abort(404, 'Document file not found.');
        }

        return Storage::disk('public')
            ->response($document->file_path);
    }

    /**
     * Download document.
     */
    public function download(
        Project $project,
        ConstructionRisk $risk,
        ConstructionRiskDocument $document
    ) {
        $this->validateRiskProject($project, $risk);
        $this->validateDocumentRisk($risk, $document);

        if (
            !Storage::disk('public')
                ->exists($document->file_path)
        ) {
            abort(404, 'Document file not found.');
        }

        return Storage::disk('public')
            ->download(
                $document->file_path,
                $document->file_name
            );
    }

    /**
     * Delete document.
     */
    public function destroy(
        Project $project,
        ConstructionRisk $risk,
        ConstructionRiskDocument $document
    ): RedirectResponse {
        $this->validateRiskProject($project, $risk);
        $this->validateDocumentRisk($risk, $document);

        if (
            Storage::disk('public')
                ->exists($document->file_path)
        ) {
            Storage::disk('public')
                ->delete($document->file_path);
        }

        $document->delete();

        return redirect()
            ->route(
                'admin.projects.construction.risks.show',
                [
                    'project' => $project,
                    'risk' => $risk,
                ]
            )
            ->with(
                'success',
                'Risk document deleted successfully.'
            );
    }

    /**
     * Validate risk belongs to project.
     */
    protected function validateRiskProject(
        Project $project,
        ConstructionRisk $risk
    ): void {
        if ((int) $risk->project_id !== (int) $project->id) {
            abort(404);
        }
    }

    /**
     * Validate document belongs to risk.
     */
    protected function validateDocumentRisk(
        ConstructionRisk $risk,
        ConstructionRiskDocument $document
    ): void {
        if (
            (int) $document->construction_risk_id !==
            (int) $risk->id
        ) {
            abort(404);
        }
    }
}