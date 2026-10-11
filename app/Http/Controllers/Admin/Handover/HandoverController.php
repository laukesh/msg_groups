<?php

namespace App\Http\Controllers\Admin\Handover;

use App\Http\Controllers\Controller;
use App\Models\HandoverProject;
use App\Models\Project;
use Illuminate\View\View;
use App\Services\HandoverReadinessService;
class HandoverController extends Controller
{
    /**
     * Project-specific Handover & Closeout dashboard.
     */
    public function index(Project $project,HandoverReadinessService $readinessService): View
    {
        /*
        |--------------------------------------------------------------------------
        | Find existing handover
        |--------------------------------------------------------------------------
        */

        $handover = HandoverProject::where(
            'project_id',
            $project->id
        )
        ->with([
            'requirements' => function ($query) {
                $query->orderBy('id');
            }
        ])
        ->latest('id')
        ->first();


        /*
        |--------------------------------------------------------------------------
        | Handover not started
        |--------------------------------------------------------------------------
        |
        | Do not automatically create a handover here.
        | Handover must be started from the Handover Management page.
        |
        */

        if (!$handover) {

            return view(
                'admin.handover.not-started',
                compact('project')
            );
        }

        $readinessService->sync($project);
        $handover->refresh();


        /*
        |--------------------------------------------------------------------------
        | Requirements
        |--------------------------------------------------------------------------
        */

        $requirements = $handover->requirements;


        /*
        |--------------------------------------------------------------------------
        | KPI
        |--------------------------------------------------------------------------
        */

        $totalRequirements = $requirements->count();

        $completedRequirements = $requirements
            ->where('status', 'Completed')
            ->count();

        $pendingRequirements = $requirements
            ->where('status', 'Pending')
            ->count();

        $inProgressRequirements = $requirements
            ->where('status', 'In Progress')
            ->count();

        $rejectedRequirements = $requirements
            ->where('status', 'Rejected')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Mandatory Requirements
        |--------------------------------------------------------------------------
        */

        $mandatoryRequirements = $requirements
            ->where('is_mandatory', true);

        $mandatoryTotal = $mandatoryRequirements->count();

        $mandatoryCompleted = $mandatoryRequirements
            ->whereIn('status', [
                'Completed',
                'Waived',
            ])
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Readiness Percentage
        |--------------------------------------------------------------------------
        */

        $readinessPercentage = $mandatoryTotal > 0
            ? round(
                ($mandatoryCompleted / $mandatoryTotal) * 100,
                2
            )
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Ready for Handover
        |--------------------------------------------------------------------------
        |
        | Handover is considered ready only when every mandatory
        | requirement is either Completed or Waived.
        |
        */

        $readyForHandover = $mandatoryTotal > 0
            && $mandatoryCompleted === $mandatoryTotal;


        /*
        |--------------------------------------------------------------------------
        | Update stored readiness percentage
        |--------------------------------------------------------------------------
        */

        if (
            (float) $handover->readiness_percentage
            !==
            (float) $readinessPercentage
        ) {

            $handover->update([
                'readiness_percentage' => $readinessPercentage,
                'updated_by' => auth()->id(),
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Return dashboard
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.handover.index',
            compact(
                'project',
                'handover',
                'requirements',
                'totalRequirements',
                'completedRequirements',
                'pendingRequirements',
                'inProgressRequirements',
                'rejectedRequirements',
                'mandatoryTotal',
                'mandatoryCompleted',
                'readinessPercentage',
                'readyForHandover'
            )
        );
    }
}