<?php

namespace App\Http\Controllers\Admin\DesignManagement;

use App\Http\Controllers\Controller;
use App\Models\DesignChange;
use App\Models\DesignComment;
use App\Models\DesignDrawing;
use App\Models\DesignPackage;
use App\Models\DesignProjectBrief;
use App\Models\DesignRfi;
use App\Models\DesignSubmittal;
use App\Models\Project;
use Illuminate\View\View;

class DesignDashboardController extends Controller
{
    public function index(Project $project): View
    {
        $dashboard = [
            'briefs' => DesignProjectBrief::where('project_id', $project->id)->count(),
            'packages' => DesignPackage::where('project_id', $project->id)->count(),
            'drawings' => DesignDrawing::where('project_id', $project->id)->count(),
            'submittals' => DesignSubmittal::where('project_id', $project->id)->count(),
            'open_rfis' => DesignRfi::where('project_id', $project->id)->where('status', 'Open')->count(),
            'changes' => DesignChange::where('project_id', $project->id)->count(),
            'pending_approvals' => DesignProjectBrief::where('project_id', $project->id)
                    ->whereIn('status', ['Under Review'])
                    ->count()
                + DesignChange::where('project_id', $project->id)
                    ->whereIn('status', ['Submitted', 'Under Review'])
                    ->count(),
            'open_comments' => DesignComment::whereHas('review.submittal', function ($q) use ($project) {
                $q->where('project_id', $project->id);
            })->where('status', 'Open')->count(),
        ];

        $recentSubmittals = DesignSubmittal::query()
            ->where('project_id', $project->id)
            ->with(['discipline', 'consultant'])
            ->latest('id')
            ->limit(5)
            ->get();

        $recentRfis = DesignRfi::query()
            ->where('project_id', $project->id)
            ->with(['discipline', 'consultant'])
            ->latest('id')
            ->limit(5)
            ->get();

        return view('design-management.dashboard.index', compact(
            'project',
            'dashboard',
            'recentSubmittals',
            'recentRfis'
        ));
    }
}
