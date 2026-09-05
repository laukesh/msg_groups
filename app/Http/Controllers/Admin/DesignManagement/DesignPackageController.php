<?php

namespace App\Http\Controllers\Admin\DesignManagement;

use App\Http\Controllers\Admin\DesignManagement\Concerns\LoadsDesignFormData;
use App\Http\Controllers\Admin\DesignManagement\Concerns\ManagesDesignWorkflow;
use App\Http\Controllers\Admin\DesignManagement\Concerns\ValidatesDesignProject;
use App\Http\Controllers\Controller;
use App\Models\DesignPackage;
use App\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DesignPackageController extends Controller
{
    use LoadsDesignFormData;
    use ManagesDesignWorkflow;
    use ValidatesDesignProject;

    protected function workflowModelClass(): string
    {
        return DesignPackage::class;
    }

    protected function workflowRoutePrefix(): string
    {
        return 'packages';
    }

    protected function workflowEditRoute(Project $project, Model $record): string
    {
        return route('admin.projects.design-management.packages.edit', [$project, $record]);
    }

    public function index(Request $request, Project $project): View
    {
        $query = DesignPackage::query()
            ->where('project_id', $project->id)
            ->with(['discipline', 'responsibleConsultant']);

        if ($request->filled('discipline_id')) {
            $query->where('design_discipline_id', $request->discipline_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $packages = $query->latest('id')->get();

        return view('design-management.packages.index', [
            'project' => $project,
            'packages' => $packages,
            'disciplines' => $this->disciplines(),
            'statuses' => \App\Support\DesignManagementOptions::packageStatuses(),
            'filters' => $request->only(['discipline_id', 'status']),
        ]);
    }

    public function create(Project $project): View
    {
        $nextVersion = (DesignPackage::query()->where('project_id', $project->id)->max('version_number') ?? 0) + 1;

        return view('design-management.packages.create', [
            'project' => $project,
            'package' => new DesignPackage(['version' => $nextVersion . '.0', 'version_number' => $nextVersion]),
            'disciplines' => $this->disciplines(),
            'consultants' => $this->projectConsultants($project),
        ]);
    }

    public function store(Request $request, Project $project): RedirectResponse
    {
        $validated = $this->validatePackage($request);
        $validated['project_id'] = $project->id;
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();
        $this->initializeWorkflowOnCreate($validated, DesignPackage::class);

        if (blank($validated['package_code'] ?? null)) {
            unset($validated['package_code']);
        }

        $package = DesignPackage::create($validated);
        $package->recordWorkflowHistory('Created', null, $package->status, 'Design package created.');

        return redirect()
            ->route('admin.projects.design-management.packages.show', [$project, $package])
            ->with('success', 'Design package created successfully.');
    }

    public function show(Project $project, DesignPackage $package): View
    {
        $this->ensureBelongsToProject($project, $package);
        $package->load(['discipline', 'responsibleConsultant', 'drawings', 'submittals', 'approver']);
        $this->loadWorkflowRelations($package);

        return view('design-management.packages.show', array_merge(
            compact('project', 'package'),
            $this->workflowViewData($project, $package)
        ));
    }

    public function edit(Project $project, DesignPackage $package): View
    {
        $this->ensureBelongsToProject($project, $package);
        $this->ensureWorkflowEditable($package);

        return view('design-management.packages.edit', [
            'project' => $project,
            'package' => $package,
            'disciplines' => $this->disciplines(),
            'consultants' => $this->projectConsultants($project),
        ]);
    }

    public function update(Request $request, Project $project, DesignPackage $package): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $package);
        $this->ensureWorkflowEditable($package);

        $validated = $this->validatePackage($request, $package);
        $validated['updated_by'] = auth()->id();
        $package->update($validated);

        return redirect()
            ->route('admin.projects.design-management.packages.show', [$project, $package])
            ->with('success', 'Design package updated successfully.');
    }

    public function destroy(Project $project, DesignPackage $package): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $package);
        $this->ensureWorkflowEditable($package);
        $package->delete();

        return redirect()
            ->route('admin.projects.design-management.packages.index', $project)
            ->with('success', 'Design package deleted successfully.');
    }

    public function submit(Project $project, DesignPackage $package): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $package);

        return $this->submitWorkflow($project, $package);
    }

    public function approve(Request $request, Project $project, DesignPackage $package): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $package);

        return $this->approveWorkflow($request, $project, $package);
    }

    public function reject(Request $request, Project $project, DesignPackage $package): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $package);

        return $this->rejectWorkflow($request, $project, $package);
    }

    public function revision(Project $project, DesignPackage $package): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $package);

        return $this->revisionWorkflow($project, $package);
    }

    protected function validatePackage(Request $request, ?DesignPackage $package = null): array
    {
        return $request->validate([
            'design_discipline_id' => 'nullable|exists:design_disciplines,id',
            'package_code' => 'nullable|string|max:100|unique:design_packages,package_code,' . ($package?->id ?? 'NULL'),
            'package_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'planned_submission_date' => 'nullable|date',
            'actual_submission_date' => 'nullable|date',
            'responsible_consultant_id' => 'nullable|exists:project_consultants,id',
            'version' => 'nullable|string|max:50',
            'remarks' => 'nullable|string',
        ]);
    }
}
