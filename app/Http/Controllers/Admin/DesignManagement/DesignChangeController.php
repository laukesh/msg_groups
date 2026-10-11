<?php

namespace App\Http\Controllers\Admin\DesignManagement;

use App\Http\Controllers\Admin\DesignManagement\Concerns\LoadsDesignFormData;
use App\Http\Controllers\Admin\DesignManagement\Concerns\ManagesDesignWorkflow;
use App\Http\Controllers\Admin\DesignManagement\Concerns\ValidatesDesignProject;
use App\Http\Controllers\Controller;
use App\Models\DesignChange;
use App\Models\DesignChangeCostImpact;
use App\Models\Project;
use App\Support\DesignManagementOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DesignChangeController extends Controller
{
    use LoadsDesignFormData;
    use ManagesDesignWorkflow;
    use ValidatesDesignProject;

    protected function workflowModelClass(): string
    {
        return DesignChange::class;
    }

    protected function workflowRoutePrefix(): string
    {
        return 'changes';
    }

    protected function workflowEditRoute(Project $project, Model $record): string
    {
        return route('admin.projects.design-management.changes.edit', [$project, $record]);
    }

    public function index(Request $request, Project $project): View
    {
        $query = DesignChange::query()
            ->where('project_id', $project->id)
            ->with(['discipline', 'designPackage', 'costImpacts']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $changes = $query->latest('id')->get();

        return view('design-management.changes.index', [
            'project' => $project,
            'changes' => $changes,
            'statuses' => DesignManagementOptions::changeStatuses(),
            'filters' => $request->only(['status']),
        ]);
    }

    public function create(Project $project): View
    {
        $nextVersion = (DesignChange::query()->where('project_id', $project->id)->max('version_number') ?? 0) + 1;

        return view('design-management.changes.create', [
            'project' => $project,
            'change' => new DesignChange(['version_number' => $nextVersion]),
            'disciplines' => $this->disciplines(),
            'packages' => $this->projectPackages($project),
            'users' => $this->users(),
            'changeTypes' => DesignManagementOptions::changeTypes(),
            'currencies' => DesignManagementOptions::currencies(),
        ]);
    }

    public function store(Request $request, Project $project): RedirectResponse
    {
        $validated = $this->validateChange($request);
        $validated['project_id'] = $project->id;
        $validated['requested_by'] = $validated['requested_by'] ?? auth()->id();
        $validated['currency'] = $validated['currency'] ?? 'INR';
        $validated['cost_impact'] = $validated['cost_impact'] ?? 0;
        $validated['time_impact_days'] = $validated['time_impact_days'] ?? 0;
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();
        $this->initializeWorkflowOnCreate($validated, DesignChange::class);

        if (empty($validated['version_number'])) {
            $validated['version_number'] = (DesignChange::query()
                ->where('project_id', $project->id)
                ->max('version_number') ?? 0) + 1;
        }

        if (blank($validated['change_code'] ?? null)) {
            unset($validated['change_code']);
        }

        $change = DesignChange::create($validated);
        $change->recordWorkflowHistory('Created', null, $change->status, 'Design change created.');

        return redirect()
            ->route('admin.projects.design-management.changes.show', [$project, $change])
            ->with('success', 'Design change created successfully.');
    }

    public function show(Project $project, DesignChange $change): View
    {
        $this->ensureBelongsToProject($project, $change);
        $change->load(['discipline', 'designPackage', 'requester', 'approver', 'costImpacts.discipline']);
        $this->loadWorkflowRelations($change);

        return view('design-management.changes.show', array_merge(
            [
                'project' => $project,
                'change' => $change,
                'disciplines' => $this->disciplines(),
                'costCategories' => DesignManagementOptions::costCategories(),
                'currencies' => DesignManagementOptions::currencies(),
            ],
            $this->workflowViewData($project, $change)
        ));
    }

    public function edit(Project $project, DesignChange $change): View
    {
        $this->ensureBelongsToProject($project, $change);
        $this->ensureWorkflowEditable($change);

        return view('design-management.changes.edit', [
            'project' => $project,
            'change' => $change,
            'disciplines' => $this->disciplines(),
            'packages' => $this->projectPackages($project),
            'users' => $this->users(),
            'changeTypes' => DesignManagementOptions::changeTypes(),
            'currencies' => DesignManagementOptions::currencies(),
        ]);
    }

    public function update(Request $request, Project $project, DesignChange $change): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $change);
        $this->ensureWorkflowEditable($change);

        $validated = $this->validateChange($request, $change);
        $validated['updated_by'] = auth()->id();
        $change->update($validated);

        return redirect()
            ->route('admin.projects.design-management.changes.show', [$project, $change])
            ->with('success', 'Design change updated successfully.');
    }

    public function destroy(Project $project, DesignChange $change): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $change);
        $this->ensureWorkflowEditable($change);
        $change->delete();

        return redirect()
            ->route('admin.projects.design-management.changes.index', $project)
            ->with('success', 'Design change deleted successfully.');
    }

    public function submit(Project $project, DesignChange $change): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $change);

        return $this->submitWorkflow($project, $change);
    }

    public function approve(Request $request, Project $project, DesignChange $change): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $change);

        return $this->approveWorkflow($request, $project, $change);
    }

    public function reject(Request $request, Project $project, DesignChange $change): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $change);

        return $this->rejectWorkflow($request, $project, $change);
    }

    public function revision(Project $project, DesignChange $change): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $change);

        return $this->revisionWorkflow($project, $change);
    }

    public function storeCostImpact(Request $request, Project $project, DesignChange $change): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $change);

        $validated = $request->validate([
            'design_discipline_id' => 'nullable|exists:design_disciplines,id',
            'cost_category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'estimated_amount' => 'required|numeric|min:0',
            'approved_amount' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:10',
            'remarks' => 'nullable|string',
        ]);

        $validated['design_change_id'] = $change->id;
        $validated['approved_amount'] = $validated['approved_amount'] ?? 0;
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        DesignChangeCostImpact::create($validated);

        $change->update([
            'cost_impact' => $change->costImpacts()->sum('estimated_amount'),
            'updated_by' => auth()->id(),
        ]);

        return redirect()
            ->route('admin.projects.design-management.changes.show', [$project, $change])
            ->with('success', 'Cost impact line added successfully.');
    }

    public function destroyCostImpact(
        Project $project,
        DesignChange $change,
        DesignChangeCostImpact $costImpact
    ): RedirectResponse {
        $this->ensureBelongsToProject($project, $change);
        $this->ensureCostImpactBelongsToProject($project, $costImpact);

        if ((int) $costImpact->design_change_id !== (int) $change->id) {
            abort(404);
        }

        $costImpact->delete();

        $change->update([
            'cost_impact' => $change->costImpacts()->sum('estimated_amount'),
            'updated_by' => auth()->id(),
        ]);

        return redirect()
            ->route('admin.projects.design-management.changes.show', [$project, $change])
            ->with('success', 'Cost impact line removed successfully.');
    }

    protected function validateChange(Request $request, ?DesignChange $change = null): array
    {
        return $request->validate([
            'design_package_id' => 'nullable|exists:design_packages,id',
            'design_discipline_id' => 'nullable|exists:design_disciplines,id',
            'change_code' => 'nullable|string|max:100|unique:design_changes,change_code,' . ($change?->id ?? 'NULL'),
            'change_title' => 'required|string|max:255',
            'change_type' => 'nullable|string|max:100',
            'reason' => 'nullable|string',
            'description' => 'nullable|string',
            'requested_date' => 'nullable|date',
            'required_date' => 'nullable|date',
            'requested_by' => 'nullable|exists:users,id',
            'version_number' => 'nullable|integer|min:1',
            'cost_impact' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'time_impact_days' => 'nullable|integer|min:0',
            'implemented_date' => 'nullable|date',
            'remarks' => 'nullable|string',
        ]);
    }
}
