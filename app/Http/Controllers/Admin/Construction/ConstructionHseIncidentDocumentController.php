<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionHseIncident;
use App\Models\ConstructionHseIncidentDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\Project;

class ConstructionHseIncidentDocumentController extends Controller
{
    public function index(
        Project $project,
        ConstructionHseIncident $incident
    ): View {
        $this->validateIncidentProject($project, $incident);

        $documents = $incident->documents()
            ->with('uploader')
            ->latest()
            ->get();

        return view(
            'construction.hse.incident-documents.index',
            compact(
                'project',
                'incident',
                'documents'
            )
        );
    }


    public function create(
        Project $project,
        ConstructionHseIncident $incident
    ): View {
        $this->validateIncidentProject($project, $incident);

        return view(
            'construction.hse.incident-documents.create',
            [
                'project' => $project,
                'incident' => $incident,
            ]
        );
    }


    public function store(
        Request $request,
        Project $project,
        ConstructionHseIncident $incident
    ): RedirectResponse {
        $this->validateIncidentProject($project, $incident);

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

            'description' => [
                'nullable',
                'string',
            ],

            'document_date' => [
                'nullable',
                'date',
            ],

            'is_evidence' => [
                'nullable',
                'boolean',
            ],

            'document' => [
                'required',
                'file',
                'max:51200',
                'mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx',
            ],
        ]);


        $file = $request->file('document');

        $path = $file->store(
            'construction/hse/incidents/' .
            $incident->id .
            '/documents',
            'public'
        );


        ConstructionHseIncidentDocument::create([
            'construction_hse_incident_id' =>
                $incident->id,

            'document_title' =>
                $validated['document_title'],

            'document_type' =>
                $validated['document_type'],

            'description' =>
                $validated['description'] ?? null,

            'file_name' =>
                $file->getClientOriginalName(),

            'file_path' =>
                $path,

            'file_type' =>
                $file->getClientMimeType(),

            'file_size' =>
                $file->getSize(),

            'is_evidence' =>
                $request->boolean('is_evidence'),

            'document_date' =>
                $validated['document_date'] ?? null,

            'uploaded_by' =>
                Auth::id(),
        ]);


        return redirect()
            ->route(
                'admin.projects.construction.hse.incidents.show',
                [
                    'project' => $project,
                    'incident' => $incident,
                ]
            )
            ->with(
                'success',
                'Incident document uploaded successfully.'
            );
    }


    public function show(
        Project $project,
        ConstructionHseIncident $incident,
        ConstructionHseIncidentDocument $document
    ) {
        $this->validateIncidentProject($project, $incident);

        $this->validateDocumentIncident(
            $incident,
            $document
        );

        abort_unless(
            Storage::disk('public')->exists(
                $document->file_path
            ),
            404
        );

        return Storage::disk('public')->download(
            $document->file_path,
            $document->file_name
        );
    }


    public function destroy(
        Project $project,
        ConstructionHseIncident $incident,
        ConstructionHseIncidentDocument $document
    ): RedirectResponse {
        $this->validateIncidentProject($project, $incident);

        $this->validateDocumentIncident(
            $incident,
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

        return back()->with(
            'success',
            'Incident document deleted successfully.'
        );
    }


    protected function validateIncidentProject(
        Project $project,
        ConstructionHseIncident $incident
    ): void {
        abort_unless(
            (int) $incident->project_id ===
            (int) $project->id,
            404
        );
    }


    protected function validateDocumentIncident(
        ConstructionHseIncident $incident,
        ConstructionHseIncidentDocument $document
    ): void {
        abort_unless(
            (int) $document->construction_hse_incident_id ===
            (int) $incident->id,
            404
        );
    }
}