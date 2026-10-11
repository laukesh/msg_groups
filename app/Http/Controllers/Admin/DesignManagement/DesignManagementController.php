<?php

namespace App\Http\Controllers\Admin\DesignManagement;

use App\Http\Controllers\Controller;
use App\Models\DesignChange;
use App\Models\DesignDrawing;
use App\Models\DesignPackage;
use App\Models\DesignProjectBrief;
use App\Models\DesignRfi;
use App\Models\DesignSubmittal;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DesignManagementController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search', ''));
        $status = $request->input('status');

        $designProjectQuery = Project::query();

        $totalProjects = (clone $designProjectQuery)->count();

        $activeProjects = (clone $designProjectQuery)
            ->whereIn('project_status', ['Active', 'In Progress', 'Construction'])
            ->count();

        $summary = [
            'total_packages' => DesignPackage::count(),
            'total_drawings' => DesignDrawing::count(),
            'total_submittals' => DesignSubmittal::count(),
            'total_rfis' => DesignRfi::where('status', 'Open')->count(),
            'total_changes' => DesignChange::count(),
            'pending_briefs' => DesignProjectBrief::whereIn('status', ['Draft', 'Under Review'])->count(),
        ];

        $projectStatuses = Project::query()
            ->whereNotNull('project_status')
            ->where('project_status', '!=', '')
            ->distinct()
            ->orderBy('project_status')
            ->pluck('project_status');

        $projectsQuery = Project::query()
            ->with('land')
            ->withCount([
                'designPackages',
                'designDrawings',
                'designSubmittals',
                'designRfis',
                'designChanges',
            ]);

        if ($search !== '') {
            $projectsQuery->where(function ($query) use ($search) {
                $query
                    ->where('project_number', 'like', '%' . $search . '%')
                    ->orWhere('project_code', 'like', '%' . $search . '%')
                    ->orWhere('project_name', 'like', '%' . $search . '%');
            });
        }

        if ($status !== null && $status !== '') {
            $projectsQuery->where('project_status', $status);
        }

        $projects = $projectsQuery
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('design-management.management.index', compact(
            'projects',
            'totalProjects',
            'activeProjects',
            'summary',
            'projectStatuses',
            'search',
            'status'
        ));
    }
}
