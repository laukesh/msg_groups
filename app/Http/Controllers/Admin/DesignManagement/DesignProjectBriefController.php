<?php

namespace App\Http\Controllers\Admin\DesignManagement;

use App\Http\Controllers\Admin\DesignManagement\Concerns\LoadsDesignFormData;
use App\Http\Controllers\Admin\DesignManagement\Concerns\ManagesDesignWorkflow;
use App\Http\Controllers\Admin\DesignManagement\Concerns\ValidatesDesignProject;
use App\Http\Controllers\Controller;
use App\Models\DesignProjectBrief;
use App\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DesignProjectBriefController extends Controller
{
    use LoadsDesignFormData;
    use ManagesDesignWorkflow;
    use ValidatesDesignProject;

    protected function workflowModelClass(): string
    {
        return DesignProjectBrief::class;
    }

    protected function workflowRoutePrefix(): string
    {
        return 'briefs';
    }

    protected function workflowEditRoute(Project $project, Model $record): string
    {
        return route('admin.projects.design-management.briefs.edit', [$project, $record]);
    }

    public function index(Project $project): View
    {
        $briefs = DesignProjectBrief::query()
            ->where('project_id', $project->id)
            ->with(['preparer', 'approver'])
            ->latest('id')
            ->get();

        return view('design-management.briefs.index', compact('project', 'briefs'));
    }

    public function create(Project $project): View
    {
        $nextVersion = (DesignProjectBrief::query()
            ->where('project_id', $project->id)
            ->max('version_number') ?? 0) + 1;

        return view('design-management.briefs.create', [
            'project' => $project,
            'brief' => new DesignProjectBrief([
                'version' => $nextVersion . '.0',
                'version_number' => $nextVersion,
                'status' => 'Draft',
            ]),
        ]);
    }

    public function store(Request $request, Project $project): RedirectResponse
    {
        $validated = $this->validateBrief($request);
        $validated['project_id'] = $project->id;
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();
        $this->initializeWorkflowOnCreate($validated, DesignProjectBrief::class);

        if (empty($validated['version_number'])) {
            $validated['version_number'] = (DesignProjectBrief::query()
                ->where('project_id', $project->id)
                ->max('version_number') ?? 0) + 1;
        }

        if (empty($validated['version'])) {
            $validated['version'] = $validated['version_number'] . '.0';
        }

        if (blank($validated['brief_code'] ?? null)) {
            unset($validated['brief_code']);
        }

        $brief = DesignProjectBrief::create($validated);
        $brief->recordWorkflowHistory('Created', null, $brief->status, 'Design brief created.');

        return redirect()
            ->route('admin.projects.design-management.briefs.show', [$project, $brief])
            ->with('success', 'Design brief created successfully.');
    }

    public function show(Project $project, DesignProjectBrief $brief): View
    {
        $this->ensureBelongsToProject($project, $brief);
        $this->loadWorkflowRelations($brief);
        $brief->load(['creator', 'approver']);

        return view('design-management.briefs.show', array_merge(
            compact('project', 'brief'),
            $this->workflowViewData($project, $brief)
        ));
    }

    public function edit(Project $project, DesignProjectBrief $brief): View
    {
        $this->ensureBelongsToProject($project, $brief);
        $this->ensureWorkflowEditable($brief);

        return view('design-management.briefs.edit', compact('project', 'brief'));
    }

    public function update(Request $request, Project $project, DesignProjectBrief $brief): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $brief);
        $this->ensureWorkflowEditable($brief);

        $validated = $this->validateBrief($request, $brief);
        $validated['updated_by'] = auth()->id();
        $brief->update($validated);

        return redirect()
            ->route('admin.projects.design-management.briefs.show', [$project, $brief])
            ->with('success', 'Design brief updated successfully.');
    }

    public function destroy(Project $project, DesignProjectBrief $brief): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $brief);
        $this->ensureWorkflowEditable($brief);
        $brief->delete();

        return redirect()
            ->route('admin.projects.design-management.briefs.index', $project)
            ->with('success', 'Design brief deleted successfully.');
    }

    public function submit(Project $project, DesignProjectBrief $brief): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $brief);

        return $this->submitWorkflow($project, $brief);
    }

    public function approve(Request $request, Project $project, DesignProjectBrief $brief): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $brief);

        return $this->approveWorkflow($request, $project, $brief);
    }

    public function reject(Request $request, Project $project, DesignProjectBrief $brief): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $brief);

        return $this->rejectWorkflow($request, $project, $brief);
    }

    public function revision(Project $project, DesignProjectBrief $brief): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $brief);

        return $this->revisionWorkflow($project, $brief);
    }

    protected function validateBrief(Request $request, ?DesignProjectBrief $brief = null): array
    {
        return $request->validate([
            'brief_code' => 'nullable|string|max:100|unique:design_project_briefs,brief_code,' . ($brief?->id ?? 'NULL'),
            'title' => 'required|string|max:255',
            'version' => 'nullable|string|max:50',
            'version_number' => 'nullable|integer|min:1',
            'project_requirements' => 'nullable|string',
            'design_objectives' => 'nullable|string',
            'functional_requirements' => 'nullable|string',
            'technical_requirements' => 'nullable|string',
            'design_standards' => 'nullable|string',
            'authority_requirements' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);
    }
}
