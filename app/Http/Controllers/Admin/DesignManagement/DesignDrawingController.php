<?php

namespace App\Http\Controllers\Admin\DesignManagement;

use App\Http\Controllers\Admin\DesignManagement\Concerns\LoadsDesignFormData;
use App\Http\Controllers\Admin\DesignManagement\Concerns\ManagesDesignWorkflow;
use App\Http\Controllers\Admin\DesignManagement\Concerns\ValidatesDesignProject;
use App\Http\Controllers\Controller;
use App\Models\DesignDrawing;
use App\Models\Project;
use App\Support\DesignManagementOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DesignDrawingController extends Controller
{
    use LoadsDesignFormData;
    use ManagesDesignWorkflow;
    use ValidatesDesignProject;

    protected function workflowModelClass(): string
    {
        return DesignDrawing::class;
    }

    protected function workflowRoutePrefix(): string
    {
        return 'drawings';
    }

    protected function workflowEditRoute(Project $project, Model $record): string
    {
        return route('admin.projects.design-management.drawings.edit', [$project, $record]);
    }

    public function index(Request $request, Project $project): View
    {
        $query = DesignDrawing::query()
            ->where('project_id', $project->id)
            ->with(['discipline', 'designPackage', 'preparedByConsultant']);

        if ($request->filled('discipline_id')) {
            $query->where('design_discipline_id', $request->discipline_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('consultant_id')) {
            $query->where('prepared_by_consultant_id', $request->consultant_id);
        }

        $drawings = $query->latest('id')->get();

        return view('design-management.drawings.index', [
            'project' => $project,
            'drawings' => $drawings,
            'disciplines' => $this->disciplines(),
            'consultants' => $this->projectConsultants($project),
            'statuses' => DesignManagementOptions::drawingStatuses(),
            'filters' => $request->only(['discipline_id', 'status', 'consultant_id']),
        ]);
    }

    public function create(Project $project): View
    {
        $nextVersion = (DesignDrawing::query()->where('project_id', $project->id)->max('version_number') ?? 0) + 1;

        return view('design-management.drawings.create', [
            'project' => $project,
            'drawing' => new DesignDrawing([
                'revision' => 'R00',
                'version_number' => $nextVersion,
            ]),
            'disciplines' => $this->disciplines(),
            'packages' => $this->projectPackages($project),
            'consultants' => $this->projectConsultants($project),
            'drawingTypes' => DesignManagementOptions::drawingTypes(),
        ]);
    }

    public function store(Request $request, Project $project): RedirectResponse
    {
        $validated = $this->validateDrawing($request);
        $validated['project_id'] = $project->id;
        $validated['revision'] = $validated['revision'] ?? 'R00';
        $validated['current_revision'] = $request->boolean('current_revision');
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();
        $this->initializeWorkflowOnCreate($validated, DesignDrawing::class);

        if (empty($validated['version_number'])) {
            $validated['version_number'] = (DesignDrawing::query()
                ->where('project_id', $project->id)
                ->max('version_number') ?? 0) + 1;
        }

        if ($request->hasFile('drawing_file')) {
            $file = $request->file('drawing_file');
            $path = $file->store('design-drawings/' . $project->id, 'public');
            $validated['file_name'] = $file->getClientOriginalName();
            $validated['file_path'] = $path;
        }

        $drawing = DesignDrawing::create($validated);
        $drawing->recordWorkflowHistory('Created', null, $drawing->status, 'Drawing registered.');

        return redirect()
            ->route('admin.projects.design-management.drawings.show', [$project, $drawing])
            ->with('success', 'Drawing registered successfully.');
    }

    public function show(Project $project, DesignDrawing $drawing): View
    {
        $this->ensureBelongsToProject($project, $drawing);
        $drawing->load(['discipline', 'designPackage', 'preparedByConsultant', 'approver']);
        $this->loadWorkflowRelations($drawing);

        return view('design-management.drawings.show', array_merge(
            compact('project', 'drawing'),
            $this->workflowViewData($project, $drawing)
        ));
    }

    public function edit(Project $project, DesignDrawing $drawing): View
    {
        $this->ensureBelongsToProject($project, $drawing);
        $this->ensureWorkflowEditable($drawing);

        return view('design-management.drawings.edit', [
            'project' => $project,
            'drawing' => $drawing,
            'disciplines' => $this->disciplines(),
            'packages' => $this->projectPackages($project),
            'consultants' => $this->projectConsultants($project),
            'drawingTypes' => DesignManagementOptions::drawingTypes(),
        ]);
    }

    public function update(Request $request, Project $project, DesignDrawing $drawing): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $drawing);
        $this->ensureWorkflowEditable($drawing);

        $validated = $this->validateDrawing($request);
        $validated['current_revision'] = $request->boolean('current_revision');
        $validated['updated_by'] = auth()->id();

        if ($request->hasFile('drawing_file')) {
            if ($drawing->file_path) {
                Storage::disk('public')->delete($drawing->file_path);
            }

            $file = $request->file('drawing_file');
            $path = $file->store('design-drawings/' . $project->id, 'public');
            $validated['file_name'] = $file->getClientOriginalName();
            $validated['file_path'] = $path;
        }

        $drawing->update($validated);

        return redirect()
            ->route('admin.projects.design-management.drawings.show', [$project, $drawing])
            ->with('success', 'Drawing updated successfully.');
    }

    public function destroy(Project $project, DesignDrawing $drawing): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $drawing);
        $this->ensureWorkflowEditable($drawing);

        if ($drawing->file_path) {
            Storage::disk('public')->delete($drawing->file_path);
        }

        $drawing->delete();

        return redirect()
            ->route('admin.projects.design-management.drawings.index', $project)
            ->with('success', 'Drawing deleted successfully.');
    }

    public function submit(Project $project, DesignDrawing $drawing): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $drawing);

        return $this->submitWorkflow($project, $drawing);
    }

    public function approve(Request $request, Project $project, DesignDrawing $drawing): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $drawing);

        return $this->approveWorkflow($request, $project, $drawing);
    }

    public function reject(Request $request, Project $project, DesignDrawing $drawing): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $drawing);

        return $this->rejectWorkflow($request, $project, $drawing);
    }

    public function revision(Project $project, DesignDrawing $drawing): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $drawing);

        return $this->revisionWorkflow($project, $drawing);
    }

    protected function validateDrawing(Request $request): array
    {
        return $request->validate([
            'design_package_id' => 'nullable|exists:design_packages,id',
            'design_discipline_id' => 'nullable|exists:design_disciplines,id',
            'drawing_number' => 'required|string|max:100',
            'drawing_title' => 'required|string|max:255',
            'drawing_type' => 'nullable|string|max:100',
            'revision' => 'nullable|string|max:50',
            'version_number' => 'nullable|integer|min:1',
            'revision_date' => 'nullable|date',
            'prepared_by_consultant_id' => 'nullable|exists:project_consultants,id',
            'drawing_file' => 'nullable|file|max:20480',
            'planned_date' => 'nullable|date',
            'submitted_date' => 'nullable|date',
            'approved_date' => 'nullable|date',
            'remarks' => 'nullable|string',
        ]);
    }
}
