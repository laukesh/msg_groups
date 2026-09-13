<?php

namespace App\Http\Controllers\Admin\DesignManagement\Concerns;

use App\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ValidatesDesignProject
{
    protected function ensureBelongsToProject(
        Project $project,
        Model $model,
        string $foreignKey = 'project_id'
    ): void {
        if ((int) $model->{$foreignKey} !== (int) $project->id) {
            throw new NotFoundHttpException();
        }
    }

    protected function ensureSubmittalBelongsToProject(
        Project $project,
        $submittal
    ): void {
        $this->ensureBelongsToProject($project, $submittal);
    }

    protected function ensureReviewBelongsToProject(
        Project $project,
        $review
    ): void {
        $review->loadMissing('submittal');

        if (
            ! $review->submittal ||
            (int) $review->submittal->project_id !== (int) $project->id
        ) {
            throw new NotFoundHttpException();
        }
    }

    protected function ensureCommentBelongsToProject(
        Project $project,
        $comment
    ): void {
        $comment->loadMissing('review.submittal');

        if (
            ! $comment->review?->submittal ||
            (int) $comment->review->submittal->project_id !== (int) $project->id
        ) {
            throw new NotFoundHttpException();
        }
    }

    protected function ensureCostImpactBelongsToProject(
        Project $project,
        $costImpact
    ): void {
        $costImpact->loadMissing('designChange');

        if (
            ! $costImpact->designChange ||
            (int) $costImpact->designChange->project_id !== (int) $project->id
        ) {
            throw new NotFoundHttpException();
        }
    }
}
