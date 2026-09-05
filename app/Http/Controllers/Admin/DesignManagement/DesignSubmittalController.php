<?php

namespace App\Http\Controllers\Admin\DesignManagement;

use App\Http\Controllers\Admin\DesignManagement\Concerns\LoadsDesignFormData;
use App\Http\Controllers\Admin\DesignManagement\Concerns\ManagesDesignWorkflow;
use App\Http\Controllers\Admin\DesignManagement\Concerns\ValidatesDesignProject;
use App\Http\Controllers\Controller;
use App\Models\DesignSubmittal;
use App\Models\Project;
use App\Support\DesignManagementOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DesignSubmittalController extends Controller
{
    use LoadsDesignFormData;
    use ManagesDesignWorkflow;
    use ValidatesDesignProject;

    protected function workflowModelClass(): string
    {
        return DesignSubmittal::class;
    }

    protected function workflowRoutePrefix(): string
    {
        return 'submittals';
    }

    protected function workflowEditRoute(Project $project, Model $record): string
    {
        return route('admin.projects.design-management.submittals.edit', [$project, $record]);
    }

    public function index(Request $request, Project $project): View
    {
        $query = DesignSubmittal::query()
            ->where('project_id', $project->id)
            ->with(['discipline', 'consultant', 'designPackage']);

        if ($request->filled('discipline_id')) {
            $query->where('design_discipline_id', $request->discipline_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $submittals = $query->latest('id')->get();

        return view('design-management.submittals.index', [
            'project' => $project,
            'submittals' => $submittals,
            'disciplines' => $this->disciplines(),
            'statuses' => DesignManagementOptions::submittalStatuses(),
            'filters' => $request->only(['discipline_id', 'status']),
        ]);
    }

    public function create(Project $project): View
    {
        $nextVersion = (DesignSubmittal::query()->where('project_id', $project->id)->max('version_number') ?? 0) + 1;

        return view('design-management.submittals.create', [
            'project' => $project,
            'submittal' => new DesignSubmittal(['version_number' => $nextVersion]),
            'disciplines' => $this->disciplines(),
            'packages' => $this->projectPackages($project),
            'consultants' => $this->projectConsultants($project),
            'decisions' => DesignManagementOptions::submittalDecisions(),
        ]);
    }

    public function store(Request $request, Project $project): RedirectResponse
    {
        $validated = $this->validateSubmittal($request);
        $validated['project_id'] = $project->id;
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();
        $this->initializeWorkflowOnCreate($validated, DesignSubmittal::class);

        if (empty($validated['version_number'])) {
            $validated['version_number'] = (DesignSubmittal::query()
                ->where('project_id', $project->id)
                ->max('version_number') ?? 0) + 1;
        }

        if (blank($validated['submittal_number'] ?? null)) {
            unset($validated['submittal_number']);
        }

        $submittal = DesignSubmittal::create($validated);
        $submittal->recordWorkflowHistory('Created', null, $submittal->status, 'Submittal created.');

        return redirect()
            ->route('admin.projects.design-management.submittals.show', [$project, $submittal])
            ->with('success', 'Submittal created successfully.');
    }

    public function show(Project $project, DesignSubmittal $submittal): View
    {
        $this->ensureBelongsToProject($project, $submittal);
        $submittal->load(['discipline', 'consultant', 'designPackage', 'reviews.reviewer', 'reviews.comments', 'approver']);
        $this->loadWorkflowRelations($submittal);

        return view('design-management.submittals.show', array_merge(
            compact('project', 'submittal'),
            $this->workflowViewData($project, $submittal)
        ));
    }

    public function edit(Project $project, DesignSubmittal $submittal): View
    {
        $this->ensureBelongsToProject($project, $submittal);
        $this->ensureWorkflowEditable($submittal);

        return view('design-management.submittals.edit', [
            'project' => $project,
            'submittal' => $submittal,
            'disciplines' => $this->disciplines(),
            'packages' => $this->projectPackages($project),
            'consultants' => $this->projectConsultants($project),
            'decisions' => DesignManagementOptions::submittalDecisions(),
        ]);
    }

    public function update(Request $request, Project $project, DesignSubmittal $submittal): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $submittal);
        $this->ensureWorkflowEditable($submittal);

        $validated = $this->validateSubmittal($request, $submittal);
        $validated['updated_by'] = auth()->id();
        $submittal->update($validated);

        return redirect()
            ->route('admin.projects.design-management.submittals.show', [$project, $submittal])
            ->with('success', 'Submittal updated successfully.');
    }

    public function destroy(Project $project, DesignSubmittal $submittal): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $submittal);
        $this->ensureWorkflowEditable($submittal);
        $submittal->delete();

        return redirect()
            ->route('admin.projects.design-management.submittals.index', $project)
            ->with('success', 'Submittal deleted successfully.');
    }

    public function submit(Project $project, DesignSubmittal $submittal): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $submittal);

        return $this->submitWorkflow($project, $submittal);
    }

    public function approve(Request $request, Project $project, DesignSubmittal $submittal): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $submittal);

        return $this->approveWorkflow($request, $project, $submittal);
    }

    public function reject(Request $request, Project $project, DesignSubmittal $submittal): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $submittal);

        return $this->rejectWorkflow($request, $project, $submittal);
    }

    public function revision(Project $project, DesignSubmittal $submittal): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $submittal);

        return $this->revisionWorkflow($project, $submittal);
    }

    protected function validateSubmittal(Request $request, ?DesignSubmittal $submittal = null): array
    {
        return $request->validate([
            'design_package_id' => 'nullable|exists:design_packages,id',
            'design_discipline_id' => 'nullable|exists:design_disciplines,id',
            'consultant_id' => 'nullable|exists:project_consultants,id',
            'submittal_number' => 'nullable|string|max:100|unique:design_submittals,submittal_number,' . ($submittal?->id ?? 'NULL'),
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
            'submission_date' => 'nullable|date',
            'revision' => 'nullable|string|max:50',
            'version_number' => 'nullable|integer|min:1',
            'due_date' => 'nullable|date',
            'reviewed_date' => 'nullable|date',
            'final_decision' => 'nullable|string|max:50',
            'decision_remarks' => 'nullable|string',
        ]);
    }
}
