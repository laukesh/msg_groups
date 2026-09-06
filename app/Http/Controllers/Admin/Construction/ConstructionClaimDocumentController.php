<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionClaim;
use App\Models\ConstructionClaimDocument;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ConstructionClaimDocumentController extends Controller
{
    /**
     * Display documents of a claim.
     */
    public function index(
        Project $project,
        ConstructionClaim $claim
    ): View {
        $this->checkProject($project, $claim);

        $documents = $claim->documents()
            ->with('uploadedBy')
            ->latest()
            ->get();

        return view(
            'construction.claims.documents.index',
            compact(
                'project',
                'claim',
                'documents'
            )
        );
    }

    /**
     * Show upload form.
     */
    public function create(
        Project $project,
        ConstructionClaim $claim
    ): View {
        $this->checkProject($project, $claim);

        return view(
            'construction.claims.documents.create',
            compact(
                'project',
                'claim'
            )
        );
    }

    /**
     * Store uploaded document.
     */
    public function store(
        Request $request,
        Project $project,
        ConstructionClaim $claim
    ): RedirectResponse {
        $this->checkProject($project, $claim);

        $validated = $request->validate([
            'document_type' => [
                'required',
                'in:Claim Letter,Cost Calculation,Site Photo,Drawing,Correspondence,Engineer Report,Supporting Evidence,Other',
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
                'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,webp,txt',
            ],

            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $file = $request->file('document');

        /*
        |--------------------------------------------------------------------------
        | Store file
        |--------------------------------------------------------------------------
        */

        $directory = 'construction/claims/'
            . $claim->id
            . '/documents';

        $filePath = $file->store(
            $directory,
            'public'
        );

        /*
        |--------------------------------------------------------------------------
        | Save document record
        |--------------------------------------------------------------------------
        */

        ConstructionClaimDocument::create([
            'construction_claim_id' => $claim->id,

            'document_type' => $validated['document_type'],

            'document_title' => $validated['document_title'],

            'file_path' => $filePath,

            'file_name' => $file->getClientOriginalName(),

            'file_size' => $file->getSize(),

            'mime_type' => $file->getMimeType(),

            'description' => $validated['description'] ?? null,

            'uploaded_by' => Auth::id(),
        ]);

        return redirect()
            ->route(
                'admin.projects.construction.claims.documents.index',
                [
                    'project' => $project,
                    'claim' => $claim,
                ]
            )
            ->with(
                'success',
                'Claim document uploaded successfully.'
            );
    }

    /**
     * Download document.
     */
    public function download(
        Project $project,
        ConstructionClaim $claim,
        ConstructionClaimDocument $document
    ) {
        $this->checkDocument(
            $project,
            $claim,
            $document
        );

        if (
            !Storage::disk('public')
                ->exists($document->file_path)
        ) {
            abort(404, 'Document file not found.');
        }

        return Storage::disk('public')->download(
            $document->file_path,
            $document->file_name
        );
    }

    /**
     * Open document in browser.
     */
    public function view(
        Project $project,
        ConstructionClaim $claim,
        ConstructionClaimDocument $document
    ) {
        $this->checkDocument(
            $project,
            $claim,
            $document
        );

        if (
            !Storage::disk('public')
                ->exists($document->file_path)
        ) {
            abort(404, 'Document file not found.');
        }

        $mimeType = $document->mime_type
            ?: Storage::disk('public')
                ->mimeType($document->file_path);

        return response()->file(
            Storage::disk('public')
                ->path($document->file_path),
            [
                'Content-Type' => $mimeType,
                'Content-Disposition' =>
                    'inline; filename="' .
                    addslashes($document->file_name) .
                    '"',
            ]
        );
    }

    /**
     * Delete document.
     */
    public function destroy(
        Project $project,
        ConstructionClaim $claim,
        ConstructionClaimDocument $document
    ): RedirectResponse {
        $this->checkDocument(
            $project,
            $claim,
            $document
        );

        /*
        |--------------------------------------------------------------------------
        | Delete physical file
        |--------------------------------------------------------------------------
        */

        if (
            $document->file_path &&
            Storage::disk('public')
                ->exists($document->file_path)
        ) {
            Storage::disk('public')
                ->delete($document->file_path);
        }

        /*
        |--------------------------------------------------------------------------
        | Soft delete database record
        |--------------------------------------------------------------------------
        */

        $document->delete();

        return redirect()
            ->route(
                'admin.projects.construction.claims.documents.index',
                [
                    'project' => $project,
                    'claim' => $claim,
                ]
            )
            ->with(
                'success',
                'Claim document deleted successfully.'
            );
    }

    /**
     * Verify project/claim relationship.
     */
    protected function checkProject(
        Project $project,
        ConstructionClaim $claim
    ): void {
        if ((int) $claim->project_id !== (int) $project->id) {
            abort(404);
        }
    }

    /**
     * Verify project/claim/document relationship.
     */
    protected function checkDocument(
        Project $project,
        ConstructionClaim $claim,
        ConstructionClaimDocument $document
    ): void {
        $this->checkProject(
            $project,
            $claim
        );

        if (
            (int) $document->construction_claim_id !==
            (int) $claim->id
        ) {
            abort(404);
        }
    }
}