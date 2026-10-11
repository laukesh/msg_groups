<?php

namespace App\Http\Controllers\Admin\DesignManagement;

use App\Http\Controllers\Admin\DesignManagement\Concerns\ManagesDesignWorkflow;
use App\Http\Controllers\Admin\DesignManagement\Concerns\ValidatesDesignProject;
use App\Http\Controllers\Controller;
use App\Models\DesignComment;
use App\Models\DesignReview;
use App\Models\Project;
use App\Support\DesignManagementOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DesignCommentController extends Controller
{
    use ManagesDesignWorkflow;
    use ValidatesDesignProject;

    protected function workflowModelClass(): string
    {
        return DesignComment::class;
    }

    protected function workflowRoutePrefix(): string
    {
        return 'comments';
    }

    protected function workflowEditRoute(Project $project, Model $record): string
    {
        return route('admin.projects.design-management.comments.edit', [$project, $record]);
    }

    public function index(Request $request, Project $project): View
    {
        $query = DesignComment::query()
            ->whereHas('review.submittal', function ($q) use ($project) {
                $q->where('project_id', $project->id);
            })
            ->with(['review.submittal']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        $comments = $query->latest('id')->get();

        return view('design-management.comments.index', [
            'project' => $project,
            'comments' => $comments,
            'statuses' => DesignManagementOptions::commentStatuses(),
            'severities' => DesignManagementOptions::commentSeverities(),
            'filters' => $request->only(['status', 'severity']),
        ]);
    }

    public function create(Request $request, Project $project): View
    {
        $reviews = DesignReview::query()
            ->whereHas('submittal', function ($q) use ($project) {
                $q->where('project_id', $project->id);
            })
            ->with('submittal')
            ->latest('id')
            ->get();

        return view('design-management.comments.create', [
            'project' => $project,
            'comment' => new DesignComment(),
            'reviews' => $reviews,
            'categories' => DesignManagementOptions::commentCategories(),
            'severities' => DesignManagementOptions::commentSeverities(),
            'selectedReviewId' => $request->input('review_id'),
        ]);
    }

    public function store(Request $request, Project $project): RedirectResponse
    {
        $validated = $this->validateComment($request);
        $review = DesignReview::findOrFail($validated['design_review_id']);
        $this->ensureReviewBelongsToProject($project, $review);

        $validated['response_required'] = $request->boolean('response_required');
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();
        $this->initializeWorkflowOnCreate($validated, DesignComment::class);

        if (blank($validated['comment_number'] ?? null)) {
            unset($validated['comment_number']);
        }

        $comment = DesignComment::create($validated);
        $comment->recordWorkflowHistory('Created', null, $comment->status, 'Comment added.');

        return redirect()
            ->route('admin.projects.design-management.comments.show', [$project, $comment])
            ->with('success', 'Comment added successfully.');
    }

    public function show(Project $project, DesignComment $comment): View
    {
        $this->ensureCommentBelongsToProject($project, $comment);
        $comment->load(['review.submittal', 'verifier', 'approver']);
        $this->loadWorkflowRelations($comment);

        return view('design-management.comments.show', array_merge(
            compact('project', 'comment'),
            $this->workflowViewData($project, $comment)
        ));
    }

    public function edit(Project $project, DesignComment $comment): View
    {
        $this->ensureCommentBelongsToProject($project, $comment);
        $this->ensureWorkflowEditable($comment);

        $reviews = DesignReview::query()
            ->whereHas('submittal', function ($q) use ($project) {
                $q->where('project_id', $project->id);
            })
            ->with('submittal')
            ->latest('id')
            ->get();

        return view('design-management.comments.edit', [
            'project' => $project,
            'comment' => $comment,
            'reviews' => $reviews,
            'categories' => DesignManagementOptions::commentCategories(),
            'severities' => DesignManagementOptions::commentSeverities(),
        ]);
    }

    public function update(Request $request, Project $project, DesignComment $comment): RedirectResponse
    {
        $this->ensureCommentBelongsToProject($project, $comment);
        $this->ensureWorkflowEditable($comment);

        $validated = $this->validateComment($request);
        $review = DesignReview::findOrFail($validated['design_review_id']);
        $this->ensureReviewBelongsToProject($project, $review);

        $validated['response_required'] = $request->boolean('response_required');
        $validated['updated_by'] = auth()->id();
        $comment->update($validated);

        return redirect()
            ->route('admin.projects.design-management.comments.show', [$project, $comment])
            ->with('success', 'Comment updated successfully.');
    }

    public function destroy(Project $project, DesignComment $comment): RedirectResponse
    {
        $this->ensureCommentBelongsToProject($project, $comment);
        $this->ensureWorkflowEditable($comment);
        $comment->delete();

        return redirect()
            ->route('admin.projects.design-management.comments.index', $project)
            ->with('success', 'Comment deleted successfully.');
    }

    public function submit(Project $project, DesignComment $comment): RedirectResponse
    {
        $this->ensureCommentBelongsToProject($project, $comment);

        return $this->submitWorkflow($project, $comment);
    }

    public function approve(Request $request, Project $project, DesignComment $comment): RedirectResponse
    {
        $this->ensureCommentBelongsToProject($project, $comment);

        return $this->approveWorkflow($request, $project, $comment);
    }

    public function reject(Request $request, Project $project, DesignComment $comment): RedirectResponse
    {
        $this->ensureCommentBelongsToProject($project, $comment);

        return $this->rejectWorkflow($request, $project, $comment);
    }

    protected function validateComment(Request $request): array
    {
        return $request->validate([
            'design_review_id' => 'required|exists:design_reviews,id',
            'comment_number' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
            'location_reference' => 'nullable|string|max:255',
            'comment_text' => 'required|string',
            'severity' => 'required|string|max:50',
            'consultant_response' => 'nullable|string',
            'response_date' => 'nullable|date',
            'resolved_date' => 'nullable|date',
            'remarks' => 'nullable|string',
        ]);
    }
}
