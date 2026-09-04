<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionProgressUpdate;
use App\Models\ConstructionWorkOrder;
use App\Models\Project;
use Illuminate\Http\Request;

class ConstructionManagementController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Construction Project Query
        |--------------------------------------------------------------------------
        |
        | A project belongs in Construction Management if it has at least
        | one construction-related record.
        |
        */

        $constructionProjectQuery = Project::query()
            ->where(function ($query) {

                $query
                    ->whereHas('constructionWorkOrders')

                    ->orWhereHas(
                        'constructionScheduleActivities'
                    )

                    ->orWhereHas(
                        'constructionProgressUpdates'
                    )

                    ->orWhereHas(
                        'constructionSiteReports'
                    );
            });


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $totalProjects =
            (clone $constructionProjectQuery)
                ->count();


        $activeProjects =
            (clone $constructionProjectQuery)
                ->whereIn(
                    'project_status',
                    [
                        'Active',
                        'In Progress',
                        'Construction',
                    ]
                )
                ->count();


        $delayedProjects =
            (clone $constructionProjectQuery)
                ->where(
                    'project_status',
                    'Delayed'
                )
                ->count();


        $completedProjects =
            (clone $constructionProjectQuery)
                ->where(
                    'project_status',
                    'Completed'
                )
                ->count();

        /*
		|--------------------------------------------------------------------------
		| Average Construction Progress
		|--------------------------------------------------------------------------
		| Average ONLY the latest progress percentage of each project.
		*/

		$progressUpdates = ConstructionProgressUpdate::query()
		    ->whereIn(
		        'project_id',
		        (clone $constructionProjectQuery)
		            ->select('projects.id')
		    )
		    ->orderBy('project_id')
		    ->orderByDesc('progress_date')
		    ->orderByDesc('id')
		    ->get([
		        'project_id',
		        'progress_percentage',
		        'progress_date',
		        'id',
		    ]);

		$latestProgressByProject = $progressUpdates
		    ->groupBy('project_id')
		    ->map(function ($updates) {
		        return $updates->first();
		    });

		$averageProgress = round(
		    $latestProgressByProject->avg('progress_percentage') ?? 0,
		    2
		);


        /*
        |--------------------------------------------------------------------------
        | Work Orders
        |--------------------------------------------------------------------------
        */

        $totalWorkOrders =
            ConstructionWorkOrder::query()
                ->whereHas(
                    'project',
                    function ($query) {

                        $query->where(function ($query) {

                            $query
                                ->whereHas(
                                    'constructionWorkOrders'
                                )
                                ->orWhereHas(
                                    'constructionScheduleActivities'
                                )
                                ->orWhereHas(
                                    'constructionProgressUpdates'
                                )
                                ->orWhereHas(
                                    'constructionSiteReports'
                                );

                        });

                    }
                )
                ->count();


        $activeWorkOrders =
            ConstructionWorkOrder::query()
                ->whereIn(
                    'status',
                    [
                        'Active',
                        'In Progress',
                    ]
                )
                ->count();


        /*
        |--------------------------------------------------------------------------
        | Status List
        |--------------------------------------------------------------------------
        */

        $projectStatuses =
            Project::query()
                ->whereNotNull(
                    'project_status'
                )
                ->where(
                    'project_status',
                    '!=',
                    ''
                )
                ->distinct()
                ->orderBy(
                    'project_status'
                )
                ->pluck(
                    'project_status'
                );


        /*
        |--------------------------------------------------------------------------
        | Construction Projects
        |--------------------------------------------------------------------------
        */

        $search = trim(
            (string) $request->input(
                'search',
                ''
            )
        );

        $status =
            $request->input('status');


        $projectsQuery =
            Project::query()
                ->where(function ($query) {

                    $query
                        ->whereHas(
                            'constructionWorkOrders'
                        )
                        ->orWhereHas(
                            'constructionScheduleActivities'
                        )
                        ->orWhereHas(
                            'constructionProgressUpdates'
                        )
                        ->orWhereHas(
                            'constructionSiteReports'
                        );

                })
                ->with([
                    'land',

                    'constructionProgressUpdates' =>
                        function ($query) {

                            $query
                                ->latest(
                                    'progress_date'
                                )
                                ->latest('id')
                                ->limit(1);
                        },
                ])
                ->withCount(
                    'constructionWorkOrders'
                );


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {

            $projectsQuery->where(
                function ($query) use ($search) {

                    $query
                        ->where(
                            'project_number',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'project_code',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'project_name',
                            'like',
                            '%' . $search . '%'
                        );

                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if (
            $status !== null &&
            $status !== ''
        ) {

            $projectsQuery->where(
                'project_status',
                $status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Paginate
        |--------------------------------------------------------------------------
        */

        $projects =
            $projectsQuery
                ->latest('id')
                ->paginate(15)
                ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Progress Overview
        |--------------------------------------------------------------------------
        */

        $progressProjects =
            (clone $constructionProjectQuery)
                ->with([
                    'constructionProgressUpdates' =>
                        function ($query) {

                            $query
                                ->latest(
                                    'progress_date'
                                )
                                ->latest('id')
                                ->limit(1);
                        }
                ])
                ->latest('id')
                ->limit(10)
                ->get();


        /*
        |--------------------------------------------------------------------------
        | Recent Progress
        |--------------------------------------------------------------------------
        */

        $recentProgress =
            ConstructionProgressUpdate::query()
                ->with('project')
                ->latest('progress_date')
                ->latest('id')
                ->limit(5)
                ->get();


        return view(
            'construction.management.index',
            compact(
                'projects',
                'progressProjects',
                'recentProgress',

                'totalProjects',
                'activeProjects',
                'delayedProjects',
                'completedProjects',

                'averageProgress',

                'totalWorkOrders',
                'activeWorkOrders',

                'projectStatuses',

                'search',
                'status'
            )
        );
    }
}