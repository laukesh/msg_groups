<?php

namespace App\Http\Controllers\Admin\DesignManagement;

use App\Http\Controllers\Controller;
use App\Models\DesignChange;
use App\Models\DesignProjectBrief;
use App\Models\DesignSubmittal;
use App\Models\Project;
use Illuminate\View\View;

class DesignApprovalController extends Controller
{
    public function index(Project $project): View
    {
        $pendingBriefs = DesignProjectBrief::query()
            ->where('project_id', $project->id)
            ->whereIn('status', ['Under Review'])
            ->latest('id')
            ->get();

        $pendingSubmittals = DesignSubmittal::query()
            ->where('project_id', $project->id)
            ->whereIn('status', ['Submitted', 'Under Review'])
            ->with(['discipline', 'consultant'])
            ->latest('id')
            ->get();

        $pendingChanges = DesignChange::query()
            ->where('project_id', $project->id)
            ->whereIn('status', ['Submitted', 'Under Review'])
            ->with(['discipline', 'designPackage'])
            ->latest('id')
            ->get();

        $summary = [
            'pending_briefs' => $pendingBriefs->count(),
            'pending_submittals' => $pendingSubmittals->count(),
            'pending_changes' => $pendingChanges->count(),
        ];

        return view('design-management.approvals.index', compact(
            'project',
            'pendingBriefs',
            'pendingSubmittals',
            'pendingChanges',
            'summary'
        ));
    }
}
