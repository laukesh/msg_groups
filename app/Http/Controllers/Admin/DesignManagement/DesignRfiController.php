<?php

namespace App\Http\Controllers\Admin\DesignManagement;

use App\Http\Controllers\Admin\DesignManagement\Concerns\LoadsDesignFormData;
use App\Http\Controllers\Admin\DesignManagement\Concerns\ManagesDesignWorkflow;
use App\Http\Controllers\Admin\DesignManagement\Concerns\ValidatesDesignProject;
use App\Http\Controllers\Controller;
use App\Models\DesignRfi;
use App\Models\Project;
use App\Support\DesignManagementOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DesignRfiController extends Controller
{
    use LoadsDesignFormData;
    use ManagesDesignWorkflow;
    use ValidatesDesignProject;

    protected function workflowModelClass(): string
    {
        return DesignRfi::class;
    }

    protected function workflowRoutePrefix(): string
    {
        return 'rfis';
    }

    protected function workflowEditRoute(Project $project, Model $record): string
    {
        return route('admin.projects.design-management.rfis.edit', [$project, $record]);
    }

    public function index(Request $request, Project $project): View
    {
        $query = DesignRfi::query()
            ->where('project_id', $project->id)
            ->with(['discipline', 'consultant']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $rfis = $query->latest('id')->get();

        return view('design-management.rfis.index', [
            'project' => $project,
            'rfis' => $rfis,
            'statuses' => DesignManagementOptions::rfiStatuses(),
            'priorities' => DesignManagementOptions::rfiPriorities(),
            'filters' => $request->only(['status', 'priority']),
        ]);
    }

    public function create(Project $project): View
    {
        return view('design-management.rfis.create', [
            'project' => $project,
            'rfi' => new DesignRfi(),
            'disciplines' => $this->disciplines(),
            'consultants' => $this->projectConsultants($project),
            'priorities' => DesignManagementOptions::rfiPriorities(),
        ]);
    }

    public function store(Request $request, Project $project): RedirectResponse
    {
        $validated = $this->validateRfi($request);
        $validated['project_id'] = $project->id;
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();
        $this->initializeWorkflowOnCreate($validated, DesignRfi::class);

        if (blank($validated['rfi_number'] ?? null)) {
            unset($validated['rfi_number']);
        }

        $rfi = DesignRfi::create($validated);
        $rfi->recordWorkflowHistory('Created', null, $rfi->status, 'RFI created.');

        return redirect()
            ->route('admin.projects.design-management.rfis.show', [$project, $rfi])
            ->with('success', 'RFI created successfully.');
    }

    public function show(Project $project, DesignRfi $rfi): View
    {
        $this->ensureBelongsToProject($project, $rfi);
        $rfi->load(['discipline', 'consultant', 'responder', 'approver']);
        $this->loadWorkflowRelations($rfi);

        return view('design-management.rfis.show', array_merge(
            compact('project', 'rfi'),
            $this->workflowViewData($project, $rfi)
        ));
    }

    public function edit(Project $project, DesignRfi $rfi): View
    {
        $this->ensureBelongsToProject($project, $rfi);
        $this->ensureWorkflowEditable($rfi);

        return view('design-management.rfis.edit', [
            'project' => $project,
            'rfi' => $rfi,
            'disciplines' => $this->disciplines(),
            'consultants' => $this->projectConsultants($project),
            'users' => $this->users(),
            'priorities' => DesignManagementOptions::rfiPriorities(),
        ]);
    }

    public function update(Request $request, Project $project, DesignRfi $rfi): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $rfi);
        $this->ensureWorkflowEditable($rfi);

        $validated = $this->validateRfi($request, $rfi);
        $validated['updated_by'] = auth()->id();
        $rfi->update($validated);

        return redirect()
            ->route('admin.projects.design-management.rfis.show', [$project, $rfi])
            ->with('success', 'RFI updated successfully.');
    }

    public function destroy(Project $project, DesignRfi $rfi): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $rfi);
        $this->ensureWorkflowEditable($rfi);
        $rfi->delete();

        return redirect()
            ->route('admin.projects.design-management.rfis.index', $project)
            ->with('success', 'RFI deleted successfully.');
    }

    public function submit(Project $project, DesignRfi $rfi): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $rfi);

        return $this->submitWorkflow($project, $rfi);
    }

    public function approve(Request $request, Project $project, DesignRfi $rfi): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $rfi);

        return $this->approveWorkflow($request, $project, $rfi);
    }

    public function reject(Request $request, Project $project, DesignRfi $rfi): RedirectResponse
    {
        $this->ensureBelongsToProject($project, $rfi);

        return $this->rejectWorkflow($request, $project, $rfi);
    }

    protected function validateRfi(Request $request, ?DesignRfi $rfi = null): array
    {
        return $request->validate([
            'design_discipline_id' => 'nullable|exists:design_disciplines,id',
            'consultant_id' => 'nullable|exists:project_consultants,id',
            'rfi_number' => 'nullable|string|max:100|unique:design_rfis,rfi_number,' . ($rfi?->id ?? 'NULL'),
            'subject' => 'required|string|max:255',
            'question' => 'required|string',
            'reference_document' => 'nullable|string|max:255',
            'reference_drawing' => 'nullable|string|max:255',
            'raised_date' => 'nullable|date',
            'required_response_date' => 'nullable|date',
            'response' => 'nullable|string',
            'response_date' => 'nullable|date',
            'responded_by' => 'nullable|exists:users,id',
            'priority' => 'required|string|max:50',
            'remarks' => 'nullable|string',
        ]);
    }
}
