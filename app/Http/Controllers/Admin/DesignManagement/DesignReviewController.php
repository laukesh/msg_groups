<?php

namespace App\Http\Controllers\Admin\DesignManagement;

use App\Http\Controllers\Admin\DesignManagement\Concerns\LoadsDesignFormData;
use App\Http\Controllers\Admin\DesignManagement\Concerns\ManagesDesignWorkflow;
use App\Http\Controllers\Admin\DesignManagement\Concerns\ValidatesDesignProject;
use App\Http\Controllers\Controller;
use App\Models\DesignReview;
use App\Models\DesignSubmittal;
use App\Models\Project;
use App\Support\DesignManagementOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DesignReviewController extends Controller
{
    use LoadsDesignFormData;
    use ManagesDesignWorkflow;
    use ValidatesDesignProject;

    protected function workflowModelClass(): string
    {
        return DesignReview::class;
    }

    protected function workflowRoutePrefix(): string
    {
        return 'reviews';
    }

    protected function workflowEditRoute(Project $project, Model $record): string
    {
        return route('admin.projects.design-management.reviews.edit', [$project, $record]);
    }

    public function index(Request $request, Project $project): View
    {
        $query = DesignReview::query()
            ->whereHas('submittal', function ($q) use ($project) {
                $q->where('project_id', $project->id);
            })
            ->with(['submittal.discipline', 'submittal.consultant', 'reviewer']);

        if ($request->filled('status')) {
            $query->where('review_status', $request->status);
        }

        $reviews = $query->latest('id')->get();

        return view('design-management.reviews.index', [
            'project' => $project,
            'reviews' => $reviews,
            'statuses' => DesignManagementOptions::reviewStatuses(),
            'filters' => $request->only(['status']),
        ]);
    }

    public function create(Request $request, Project $project): View
    {
        return view('design-management.reviews.create', [
            'project' => $project,
            'review' => new DesignReview(),
            'submittals' => DesignSubmittal::where('project_id', $project->id)->orderBy('submittal_number')->get(),
            'users' => $this->users(),
            'decisions' => DesignManagementOptions::reviewDecisions(),
            'selectedSubmittalId' => $request->input('submittal_id'),
        ]);
    }

    public function store(Request $request, Project $project): RedirectResponse
    {
        $validated = $this->validateReview($request);
        $submittal = DesignSubmittal::findOrFail($validated['design_submittal_id']);
        $this->ensureBelongsToProject($project, $submittal);

        $validated['response_required'] = $request->boolean('response_required');
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();
        $this->initializeWorkflowOnCreate($validated, DesignReview::class);

        if (blank($validated['review_number'] ?? null)) {
            unset($validated['review_number']);
        }

        $review = DesignReview::create($validated);
        $review->recordWorkflowHistory('Created', null, $review->review_status, 'Review created.');

        return redirect()
            ->route('admin.projects.design-management.reviews.show', [$project, $review])
            ->with('success', 'Review created successfully.');
    }

    public function show(Project $project, DesignReview $review): View
    {
        $this->ensureReviewBelongsToProject($project, $review);
        $review->load(['submittal.discipline', 'submittal.consultant', 'reviewer', 'comments', 'approver']);
        $this->loadWorkflowRelations($review);

        return view('design-management.reviews.show', array_merge(
            compact('project', 'review'),
            $this->workflowViewData($project, $review)
        ));
    }

    public function edit(Project $project, DesignReview $review): View
    {
        $this->ensureReviewBelongsToProject($project, $review);
        $this->ensureWorkflowEditable($review);

        return view('design-management.reviews.edit', [
            'project' => $project,
            'review' => $review,
            'submittals' => DesignSubmittal::where('project_id', $project->id)->orderBy('submittal_number')->get(),
            'users' => $this->users(),
            'decisions' => DesignManagementOptions::reviewDecisions(),
        ]);
    }

    public function update(Request $request, Project $project, DesignReview $review): RedirectResponse
    {
        $this->ensureReviewBelongsToProject($project, $review);
        $this->ensureWorkflowEditable($review);

        $validated = $this->validateReview($request, $review);
        $submittal = DesignSubmittal::findOrFail($validated['design_submittal_id']);
        $this->ensureBelongsToProject($project, $submittal);

        $validated['response_required'] = $request->boolean('response_required');
        $validated['updated_by'] = auth()->id();
        $review->update($validated);

        return redirect()
            ->route('admin.projects.design-management.reviews.show', [$project, $review])
            ->with('success', 'Review updated successfully.');
    }

    public function destroy(Project $project, DesignReview $review): RedirectResponse
    {
        $this->ensureReviewBelongsToProject($project, $review);
        $this->ensureWorkflowEditable($review);
        $review->delete();

        return redirect()
            ->route('admin.projects.design-management.reviews.index', $project)
            ->with('success', 'Review deleted successfully.');
    }

    public function submit(Project $project, DesignReview $review): RedirectResponse
    {
        $this->ensureReviewBelongsToProject($project, $review);

        return $this->submitWorkflow($project, $review);
    }

    public function approve(Request $request, Project $project, DesignReview $review): RedirectResponse
    {
        $this->ensureReviewBelongsToProject($project, $review);

        return $this->approveWorkflow($request, $project, $review);
    }

    public function reject(Request $request, Project $project, DesignReview $review): RedirectResponse
    {
        $this->ensureReviewBelongsToProject($project, $review);

        return $this->rejectWorkflow($request, $project, $review);
    }

    protected function validateReview(Request $request, ?DesignReview $review = null): array
    {
        return $request->validate([
            'design_submittal_id' => 'required|exists:design_submittals,id',
            'review_number' => 'nullable|string|max:100',
            'review_date' => 'nullable|date',
            'reviewer_id' => 'nullable|exists:users,id',
            'decision' => 'nullable|string|max:100',
            'general_comments' => 'nullable|string',
            'response_due_date' => 'nullable|date',
            'responded_date' => 'nullable|date',
            'remarks' => 'nullable|string',
        ]);
    }
}
