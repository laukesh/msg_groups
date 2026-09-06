<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionDelay;
use App\Models\ConstructionDelayDocument;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ConstructionDelayDocumentController extends Controller
{
    public function index(
        Project $project,
        ConstructionDelay $delay
    ): View {
        $this->checkProject(
            $project,
            $delay
        );

        $documents = $delay->documents()
                            ->with('uploadedBy')
                            ->latest()
                            ->paginate(20);

        return view(
            'construction.delays.documents.index',
            compact(
                'project',
                'delay',
                'documents'
            )
        );
    }

    public function create(
        Project $project,
        ConstructionDelay $delay
    ): View {
        $this->checkProject(
            $project,
            $delay
        );

        return view(
            'construction.delays.documents.create',
            compact(
                'project',
                'delay'
            )
        );
    }

    public function store(
        Request $request,
        Project $project,
        ConstructionDelay $delay
    ): RedirectResponse {
        $this->checkProject(
            $project,
            $delay
        );

        $validated = $request->validate([
            'document_type' => [
                'required',
                'in:Delay Notice,Schedule,Progress Report,Site Report,Correspondence,Drawing,RFI,Meeting Minutes,Site Photo,Engineer Report,Supporting Evidence,Other',
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

        $directory =
            'construction/delays/' .
            $delay->id .
            '/documents';

        $filePath = $file->store(
            $directory,
            'public'
        );

        ConstructionDelayDocument::create([
            'construction_delay_id' =>
                $delay->id,

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
                Auth::id(),
        ]);

        return redirect()
            ->route(
                'admin.projects.construction.delays.documents.index',
                [
                    'project' => $project,
                    'delay' => $delay,
                ]
            )
            ->with(
                'success',
                'Delay document uploaded successfully.'
            );
    }

    public function view(
        Project $project,
        ConstructionDelay $delay,
        ConstructionDelayDocument $document
    ) {
        $this->checkDocument(
            $project,
            $delay,
            $document
        );

        if (
            !Storage::disk('public')
                ->exists($document->file_path)
        ) {
            abort(404, 'Document file not found.');
        }

        return response()->file(
            Storage::disk('public')
                ->path($document->file_path),
            [
                'Content-Type' =>
                    $document->mime_type
                    ?: Storage::disk('public')
                        ->mimeType($document->file_path),

                'Content-Disposition' =>
                    'inline; filename="' .
                    addslashes($document->file_name) .
                    '"',
            ]
        );
    }

    public function download(
        Project $project,
        ConstructionDelay $delay,
        ConstructionDelayDocument $document
    ) {
        $this->checkDocument(
            $project,
            $delay,
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

    public function destroy(
        Project $project,
        ConstructionDelay $delay,
        ConstructionDelayDocument $document
    ): RedirectResponse {
        $this->checkDocument(
            $project,
            $delay,
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

        return back()->with(
            'success',
            'Delay document deleted successfully.'
        );
    }

    protected function checkProject(
        Project $project,
        ConstructionDelay $delay
    ): void {
        if (
            (int) $delay->project_id !==
            (int) $project->id
        ) {
            abort(404);
        }
    }

    protected function checkDocument(
        Project $project,
        ConstructionDelay $delay,
        ConstructionDelayDocument $document
    ): void {
        $this->checkProject(
            $project,
            $delay
        );

        if (
            (int) $document->construction_delay_id !==
            (int) $delay->id
        ) {
            abort(404);
        }
    }
}