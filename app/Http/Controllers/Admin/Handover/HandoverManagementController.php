<?php

namespace App\Http\Controllers\Admin\Handover;

use App\Http\Controllers\Controller;
use App\Models\HandoverProject;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\HandoverRequirement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class HandoverManagementController extends Controller
{
    public function index(Request $request): View
    {
        $query = Project::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('project_name', 'like', "%{$search}%")
                    ->orWhere('project_code', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Load latest handover
        |--------------------------------------------------------------------------
        */
        $projects = $query
            ->with([
                'handoverProjects' => function ($q) {
                    $q->latest('id');
                }
            ])
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | KPI
        |--------------------------------------------------------------------------
        */
        $totalProjects = Project::count();

        $inProgress = HandoverProject::where('status', 'In Progress')
            ->count();

        $ready = HandoverProject::where('status', 'Ready for Handover')
            ->count();

        $completed = HandoverProject::where('status', 'Completed')
            ->count();

        return view('admin.handover.management.index', compact(
            'projects',
            'totalProjects',
            'inProgress',
            'ready',
            'completed'
        ));
    }

    public function start(Project $project): RedirectResponse
	{
	    /*
	    |--------------------------------------------------------------------------
	    | Check whether handover already exists
	    |--------------------------------------------------------------------------
	    */

	    $existingHandover = HandoverProject::where(
	        'project_id',
	        $project->id
	    )->first();

	    if ($existingHandover) {

	        return redirect()
	            ->route(
	                'admin.projects.handover.index',
	                $project
	            )
	            ->with(
	                'info',
	                'Handover has already been started for this project.'
	            );
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | Create Handover
	    |--------------------------------------------------------------------------
	    */

	    DB::transaction(function () use ($project, &$handover) {

	        $handover = HandoverProject::create([

	            'project_id' => $project->id,

	            'handover_no' => 'HO-' .
	                str_pad(
	                    $project->id,
	                    5,
	                    '0',
	                    STR_PAD_LEFT
	                ),

	            'title' =>
	                ($project->project_name ?? 'Project')
	                . ' - Handover & Closeout',

	            'status' => 'Draft',

	            'readiness_percentage' => 0,

	            'created_by' => auth()->id(),

	            'updated_by' => auth()->id(),

	        ]);


	        /*
	        |--------------------------------------------------------------------------
	        | Initial Requirements
	        |--------------------------------------------------------------------------
	        */

	        $requirements = [

			    [
			        'code' => 'CONST-COMP',
			        'type' => 'Construction',
			        'title' => 'Construction Completion',
			        'source_module' => 'Construction',
			        'source_type' => 'System',
			    ],

			    [
			        'code' => 'COMM-COMP',
			        'type' => 'Commissioning',
			        'title' => 'Commissioning Completion',
			        'source_module' => 'Commissioning',
			        'source_type' => 'System',
			    ],

			    [
			        'code' => 'SNAG-COMP',
			        'type' => 'Snagging',
			        'title' => 'Snagging / Punch List Completion',
			        'source_module' => 'Snagging',
			        'source_type' => 'System',
			    ],

			    [
			        'code' => 'DEFECT-COMP',
			        'type' => 'Defects',
			        'title' => 'Outstanding Defects Resolution',
			        'source_module' => 'Defects',
			        'source_type' => 'System',
			    ],

			    [
			        'code' => 'OM-MANUAL',
			        'type' => 'O&M',
			        'title' => 'O&M Manuals',
			        'source_module' => 'O&M',
			        'source_type' => 'System',
			    ],

			    [
			        'code' => 'AS-BUILT',
			        'type' => 'As-Built',
			        'title' => 'As-Built Drawings',
			        'source_module' => 'As-Built',
			        'source_type' => 'System',
			    ],

			    [
			        'code' => 'WARRANTY',
			        'type' => 'Warranty',
			        'title' => 'Warranty Documents',
			        'source_module' => 'Warranty',
			        'source_type' => 'System',
			    ],

			    [
			        'code' => 'AUTH-APPROVAL',
			        'type' => 'Authority Approval',
			        'title' => 'Authority Approvals',
			        'source_module' => 'Authority Approval',
			        'source_type' => 'System',
			    ],

			    [
			        'code' => 'TRAINING',
			        'type' => 'Training',
			        'title' => 'Operations Team Training',
			        'source_module' => 'Training',
			        'source_type' => 'System',
			    ],

			    [
			        'code' => 'ASSET-REG',
			        'type' => 'Asset',
			        'title' => 'Asset Register',
			        'source_module' => 'Asset',
			        'source_type' => 'System',
			    ],

			    [
			        'code' => 'FINAL-ACCOUNT',
			        'type' => 'Financial',
			        'title' => 'Final Account',
			        'source_module' => 'Financial',
			        'source_type' => 'System',
			    ],

			    [
			        'code' => 'FINAL-PAYMENT',
			        'type' => 'Financial',
			        'title' => 'Final Payment',
			        'source_module' => 'Financial',
			        'source_type' => 'System',
			    ],

			];


	        foreach ($requirements as $requirement) {

	            $handover->requirements()->create([

				    'project_id' => $project->id,

				    'requirement_code' =>
				        $requirement['code'],

				    'requirement_type' =>
				        $requirement['type'],

				    'title' =>
				        $requirement['title'],

				    'source_module' =>
				        $requirement['source_module'],

				    'source_type' =>
				        $requirement['source_type'],

				    'auto_sync' => true,

				    'is_mandatory' => true,

				    'status' => 'Pending',

				    'created_by' => Auth::id(),

				    'updated_by' => Auth::id(),

				]);
	        }
	    });


	    /*
	    |--------------------------------------------------------------------------
	    | Redirect
	    |--------------------------------------------------------------------------
	    */

	    return redirect()
	        ->route(
	            'admin.projects.handover.index',
	            $project
	        )
	        ->with(
	            'success',
	            'Handover process started successfully.'
	        );
	}
}