<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionCorrespondence;
use App\Models\ConstructionCorrespondenceDocument;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ConstructionCorrespondenceDocumentController extends Controller
{
    /**
     * List documents.
     */
    public function index(
        Project $project,
        ConstructionCorrespondence $correspondence
    ): View {
        $this->validateCorrespondenceProject(
            $project,
            $correspondence
        );

        $documents = $correspondence->documents()
            ->with('uploadedBy')
            ->latest()
            ->paginate(20);

        return view(
            'construction.correspondence.documents.index',
            compact(
                'project',
                'correspondence',
                'documents'
            )
        );
    }


    /**
     * Show upload form.
     */
    public function create(
        Project $project,
        ConstructionCorrespondence $correspondence
    ): View {
        $this->validateCorrespondenceProject(
            $project,
            $correspondence
        );

        return view(
            'construction.correspondence.documents.create',
            compact(
                'project',
                'correspondence'
            )
        );
    }


    /**
     * Upload document.
     */
    public function store(
        Request $request,
        Project $project,
        ConstructionCorrespondence $correspondence
    ): RedirectResponse {
        $this->validateCorrespondenceProject(
            $project,
            $correspondence
        );

        $validated = $request->validate([
            'document_type' => [
                'required',
                'in:Letter,Email,Notice,Instruction,Drawing,Report,Meeting Minutes,Site Photo,Supporting Evidence,Other',
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

        $directory =
            'construction/correspondence/' .
            $correspondence->id .
            '/documents';

        $extension = $file->getClientOriginalExtension();

        $storedFileName =
            Str::uuid() .
            ($extension ? '.' . $extension : '');

        $filePath = $file->storeAs(
            $directory,
            $storedFileName,
            'public'
        );

        ConstructionCorrespondenceDocument::create([
            'construction_correspondence_id' =>
                $correspondence->id,

            'document_type' =>
                $validated['document_type'],

            'document_title' =>
                $validated['document_title'],

            'file_path' =>
                $filePath,

            'file_name' =>
                $file->getClientOriginalName(),

            'file_size' =>
                $file->getSize(),

            'mime_type' =>
                $file->getMimeType(),

            'description' =>
                $validated['description'] ?? null,

            'uploaded_by' =>
                auth()->id(),
        ]);

        return redirect()
            ->route(
                'admin.projects.construction.correspondence.show',
                [
                    'project' => $project,
                    'correspondence' => $correspondence,
                ]
            )
            ->with(
                'success',
                'Correspondence document uploaded successfully.'
            );
    }


    /**
     * View document.
     */
    public function view(
        Project $project,
        ConstructionCorrespondence $correspondence,
        ConstructionCorrespondenceDocument $document
    ) {
        $this->validateCorrespondenceProject(
            $project,
            $correspondence
        );

        $this->validateDocument(
            $correspondence,
            $document
        );

        if (
            !Storage::disk('public')
                ->exists($document->file_path)
        ) {
            abort(
                404,
                'Document file not found.'
            );
        }

        return Storage::disk('public')
            ->response(
                $document->file_path
            );
    }


    /**
     * Download document.
     */
    public function download(
        Project $project,
        ConstructionCorrespondence $correspondence,
        ConstructionCorrespondenceDocument $document
    ) {
        $this->validateCorrespondenceProject(
            $project,
            $correspondence
        );

        $this->validateDocument(
            $correspondence,
            $document
        );

        if (
            !Storage::disk('public')
                ->exists($document->file_path)
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


    /**
     * Delete document.
     */
    public function destroy(
        Project $project,
        ConstructionCorrespondence $correspondence,
        ConstructionCorrespondenceDocument $document
    ): RedirectResponse {
        $this->validateCorrespondenceProject(
            $project,
            $correspondence
        );

        $this->validateDocument(
            $correspondence,
            $document
        );

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
                'admin.projects.construction.correspondence.show',
                [
                    'project' => $project,
                    'correspondence' => $correspondence,
                ]
            )
            ->with(
                'success',
                'Correspondence document deleted successfully.'
            );
    }


    /**
     * Validate correspondence belongs to project.
     */
    protected function validateCorrespondenceProject(
        Project $project,
        ConstructionCorrespondence $correspondence
    ): void {
        if (
            (int) $correspondence->project_id !==
            (int) $project->id
        ) {
            abort(404);
        }
    }


    /**
     * Validate document belongs to correspondence.
     */
    protected function validateDocument(
        ConstructionCorrespondence $correspondence,
        ConstructionCorrespondenceDocument $document
    ): void {
        if (
            (int) $document->construction_correspondence_id !==
            (int) $correspondence->id
        ) {
            abort(404);
        }
    }
}