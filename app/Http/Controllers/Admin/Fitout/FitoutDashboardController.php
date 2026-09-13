<?php

namespace App\Http\Controllers\Admin\Fitout;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class FitoutDashboardController extends Controller
{
    public function index(Request $request)
	{
	    /*
	    |--------------------------------------------------------------------------
	    | FILTERS
	    |--------------------------------------------------------------------------
	    */

	    $filters = [
	        'status'        => $request->get('status'),
	        'floor_id'      => $request->get('floor_id'),
	        'unit_id'       => $request->get('unit_id'),
	        'tenant_id'     => $request->get('tenant_id'),
	        'contractor_id' => $request->get('contractor_id'),
	        'fitout_type'   => $request->get('fitout_type'),
	        'date_from'     => $request->get('date_from'),
	        'date_to'       => $request->get('date_to'),
	        'pipeline'      => $request->get('pipeline'),
	    ];


	    /*
	    |--------------------------------------------------------------------------
	    | FILTER OPTIONS
	    |--------------------------------------------------------------------------
	    */

	    $floors = DB::table('floors')
	        ->where('status', 1)
	        ->orderBy('floor_number')
	        ->get([
	            'id',
	            'floor_code',
	            'floor_name',
	        ]);


	    $units = DB::table('units')
	        ->whereNull('deleted_at')
	        ->orderBy('unit_no')
	        ->get([
	            'id',
	            'unit_no',
	            'floor_id',
	        ]);


	    /*
	    |--------------------------------------------------------------------------
	    | TENANTS
	    |--------------------------------------------------------------------------
	    |
	    | If your tenant table/model has a different name, adjust here.
	    |
	    */

	    $tenants = DB::table('tenants')
	        ->orderBy('id', 'desc')
	        ->get([
	            'id',
	            'company_name',
	        ]);


	    /*
	    |--------------------------------------------------------------------------
	    | CONTRACTORS
	    |--------------------------------------------------------------------------
	    */

	    $contractors = DB::table('fitout_contractors')
	        ->orderBy('id', 'desc')
	        ->get([
	            'id',
	            'contractor_name',
	        ]);


	    /*
	    |--------------------------------------------------------------------------
	    | BASE FIT-OUT REQUEST QUERY
	    |--------------------------------------------------------------------------
	    */

	    $baseQuery = DB::table('fitout_requests')
	        ->whereNull('fitout_requests.deleted_at');


	    /*
	    |--------------------------------------------------------------------------
	    | STATUS FILTER
	    |--------------------------------------------------------------------------
	    */

	    if (!empty($filters['status'])) {

	        $baseQuery->where(
	            'fitout_requests.fitout_status',
	            $filters['status']
	        );
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | FLOOR FILTER
	    |--------------------------------------------------------------------------
	    */

	    if (!empty($filters['floor_id'])) {

	        $baseQuery->whereIn(
	            'fitout_requests.unit_id',
	            function ($query) use ($filters) {

	                $query->select('id')
	                    ->from('units')
	                    ->where('floor_id', $filters['floor_id'])
	                    ->whereNull('deleted_at');
	            }
	        );
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | UNIT FILTER
	    |--------------------------------------------------------------------------
	    */

	    if (!empty($filters['unit_id'])) {

	        $baseQuery->where(
	            'fitout_requests.unit_id',
	            $filters['unit_id']
	        );
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | TENANT FILTER
	    |--------------------------------------------------------------------------
	    */

	    if (!empty($filters['tenant_id'])) {

	        $baseQuery->where(
	            'fitout_requests.tenant_id',
	            $filters['tenant_id']
	        );
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | CONTRACTOR FILTER
	    |--------------------------------------------------------------------------
	    */

	    if (!empty($filters['contractor_id'])) {

	        $baseQuery->where(
	            'fitout_requests.contractor_id',
	            $filters['contractor_id']
	        );
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | FIT-OUT TYPE
	    |--------------------------------------------------------------------------
	    */

	    if (!empty($filters['fitout_type'])) {

	        $baseQuery->where(
	            'fitout_requests.fitout_type',
	            $filters['fitout_type']
	        );
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | DATE FILTER
	    |--------------------------------------------------------------------------
	    */

	    if (!empty($filters['date_from'])) {

	        $baseQuery->whereDate(
	            'fitout_requests.proposed_start_date',
	            '>=',
	            $filters['date_from']
	        );
	    }


	    if (!empty($filters['date_to'])) {

	        $baseQuery->whereDate(
	            'fitout_requests.proposed_start_date',
	            '<=',
	            $filters['date_to']
	        );
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | PIPELINE FILTER
	    |--------------------------------------------------------------------------
	    */

	    if ($filters['pipeline']) {

	        switch ($filters['pipeline']) {

	            case 'start':

	                $baseQuery->whereIn(
	                    'fitout_requests.fitout_status',
	                    [
	                        'Draft',
	                        'Submitted',
	                    ]
	                );

	                break;


	            case 'approval':

	                $baseQuery->where(
	                    'fitout_requests.fitout_status',
	                    'Under Review'
	                );

	                break;


	            case 'fitout':

	                $baseQuery->whereIn(
	                    'fitout_requests.fitout_status',
	                    [
	                        'Approved',
	                        'In Progress',
	                    ]
	                );

	                break;


	            case 'inspection':

	                $baseQuery->whereIn(
	                    'fitout_requests.id',
	                    function ($query) {

	                        $query->select(
	                            'fitout_request_id'
	                        )
	                        ->from('inspections')
	                        ->whereNull('deleted_at')
	                        ->whereIn(
	                            'status',
	                            [
	                                'Scheduled',
	                                'In Progress',
	                                'Completed',
	                            ]
	                        );
	                    }
	                );

	                break;


	            case 'snag':

	                $baseQuery->whereIn(
	                    'fitout_requests.id',
	                    function ($query) {

	                        $query->select(
	                            'fitout_request_id'
	                        )
	                        ->from('snag_lists')
	                        ->whereNull('deleted_at')
	                        ->whereIn(
	                            'status',
	                            [
	                                'Open',
	                                'Assigned',
	                                'In Progress',
	                                'Under Verification',
	                                'Reopened',
	                            ]
	                        );
	                    }
	                );

	                break;


	            case 'handover':

	                $baseQuery->whereIn(
	                    'fitout_requests.id',
	                    function ($query) {

	                        $query->select(
	                            'fitout_request_id'
	                        )
	                        ->from('handovers')
	                        ->whereNull('deleted_at')
	                        ->whereIn(
	                            'status',
	                            [
	                                'Pending',
	                                'Scheduled',
	                                'In Progress',
	                                'Accepted',
	                                'Completed',
	                            ]
	                        );
	                    }
	                );

	                break;

	        }
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | FILTERED REQUESTS
	    |--------------------------------------------------------------------------
	    */

	    $fitouts = (clone $baseQuery)
	        ->orderByDesc('fitout_requests.id')
	        ->paginate(20)
	        ->withQueryString();


	    /*
	    |--------------------------------------------------------------------------
	    | KPI BASE QUERY
	    |--------------------------------------------------------------------------
	    */

	    $totalFitouts = (clone $baseQuery)->count();


	    $approvedFitouts = (clone $baseQuery)
	        ->where(
	            'fitout_requests.fitout_status',
	            'Approved'
	        )
	        ->count();


	    $inFitout = (clone $baseQuery)
	        ->where(
	            'fitout_requests.fitout_status',
	            'In Progress'
	        )
	        ->count();


	    /*
	    |--------------------------------------------------------------------------
	    | INSPECTIONS
	    |--------------------------------------------------------------------------
	    */

	    $inspectionQuery = DB::table('inspections')
	        ->whereNull('inspections.deleted_at');


	    if (!empty($filters['date_from'])) {

	        $inspectionQuery->whereDate(
	            'scheduled_date',
	            '>=',
	            $filters['date_from']
	        );
	    }


	    if (!empty($filters['date_to'])) {

	        $inspectionQuery->whereDate(
	            'scheduled_date',
	            '<=',
	            $filters['date_to']
	        );
	    }


	    $inspectionsDue = (clone $inspectionQuery)
	        ->whereIn(
	            'status',
	            [
	                'Scheduled',
	                'In Progress',
	            ]
	        )
	        ->count();


	    /*
	    |--------------------------------------------------------------------------
	    | SNAGS
	    |--------------------------------------------------------------------------
	    */

	    $openSnags = DB::table('snag_lists')
	        ->whereNull('deleted_at')
	        ->whereIn(
	            'status',
	            [
	                'Open',
	                'Assigned',
	                'In Progress',
	                'Under Verification',
	                'Reopened',
	            ]
	        )
	        ->count();


	    $criticalSnags = DB::table('snag_lists')
	        ->whereNull('deleted_at')
	        ->where(
	            'priority',
	            'Critical'
	        )
	        ->whereNotIn(
	            'status',
	            [
	                'Closed',
	            ]
	        )
	        ->count();


	    /*
	    |--------------------------------------------------------------------------
	    | HANDOVERS
	    |--------------------------------------------------------------------------
	    */

	    $completedHandovers = DB::table('handovers')
	        ->whereNull('deleted_at')
	        ->where(
	            'status',
	            'Completed'
	        )
	        ->count();


	    /*
	    |--------------------------------------------------------------------------
	    | PIPELINE COUNTS
	    |--------------------------------------------------------------------------
	    */

	    $pipelineStart = (clone $baseQuery)
	        ->whereIn(
	            'fitout_requests.fitout_status',
	            [
	                'Draft',
	                'Submitted',
	            ]
	        )
	        ->count();


	    $pipelineApproval = (clone $baseQuery)
	        ->where(
	            'fitout_requests.fitout_status',
	            'Under Review'
	        )
	        ->count();


	    $pipelineFitout = (clone $baseQuery)
	        ->whereIn(
	            'fitout_requests.fitout_status',
	            [
	                'Approved',
	                'In Progress',
	            ]
	        )
	        ->count();


	    $pipelineInspection = (clone $baseQuery)
	        ->whereIn(
	            'fitout_requests.id',
	            function ($query) {

	                $query->select(
	                    'fitout_request_id'
	                )
	                ->from('inspections')
	                ->whereNull('deleted_at')
	                ->whereIn(
	                    'status',
	                    [
	                        'Scheduled',
	                        'In Progress',
	                        'Completed',
	                    ]
	                );
	            }
	        )
	        ->count();


	    $pipelineSnag = (clone $baseQuery)
	        ->whereIn(
	            'fitout_requests.id',
	            function ($query) {

	                $query->select(
	                    'fitout_request_id'
	                )
	                ->from('snag_lists')
	                ->whereNull('deleted_at')
	                ->whereIn(
	                    'status',
	                    [
	                        'Open',
	                        'Assigned',
	                        'In Progress',
	                        'Under Verification',
	                        'Reopened',
	                    ]
	                );
	            }
	        )
	        ->count();


	    $pipelineHandover = (clone $baseQuery)
	        ->whereIn(
	            'fitout_requests.id',
	            function ($query) {

	                $query->select(
	                    'fitout_request_id'
	                )
	                ->from('handovers')
	                ->whereNull('deleted_at')
	                ->whereIn(
	                    'status',
	                    [
	                        'Pending',
	                        'Scheduled',
	                        'In Progress',
	                        'Accepted',
	                        'Completed',
	                    ]
	                );
	            }
	        )
	        ->count();


	    /*
	    |--------------------------------------------------------------------------
	    | STAGE PROGRESS
	    |--------------------------------------------------------------------------
	    */

	    $stageProgress = DB::table('fitout_stages')
	        ->whereNull('deleted_at')
	        ->select(
	            'stage_name',
	            DB::raw(
	                'ROUND(AVG(completion_percentage), 0) as progress'
	            ),
	            DB::raw(
	                'COUNT(*) as total'
	            )
	        )
	        ->groupBy('stage_name')
	        ->orderBy('stage_name')
	        ->get();


	    $overallProgress = DB::table('fitout_stages')
	        ->whereNull('deleted_at')
	        ->avg('completion_percentage');

	    $overallProgress = round(
	        $overallProgress ?? 0
	    );


	    $stageStatus = DB::table('fitout_stages')
	        ->whereNull('deleted_at')
	        ->select(
	            'stage_status',
	            DB::raw('COUNT(*) as total')
	        )
	        ->groupBy('stage_status')
	        ->pluck(
	            'total',
	            'stage_status'
	        );


	    /*
	    |--------------------------------------------------------------------------
	    | FLOORS
	    |--------------------------------------------------------------------------
	    */

	    $unitsByFloor = DB::table('floors')
	        ->join(
	            'units',
	            'units.floor_id',
	            '=',
	            'floors.id'
	        )
	        ->where(
	            'floors.status',
	            1
	        )
	        ->whereNull(
	            'units.deleted_at'
	        )
	        ->select(
	            'floors.id',
	            'floors.floor_code',
	            'floors.floor_name',
	            'floors.floor_number',
	            DB::raw(
	                'COUNT(units.id) as total_units'
	            )
	        )
	        ->groupBy(
	            'floors.id',
	            'floors.floor_code',
	            'floors.floor_name',
	            'floors.floor_number'
	        )
	        ->orderBy(
	            'floors.floor_number'
	        )
	        ->get();


	    /*
	    |--------------------------------------------------------------------------
	    | ACTIVE UNITS
	    |--------------------------------------------------------------------------
	    */

	    $activeUnitIds = DB::table('fitout_requests')
	        ->whereNull('deleted_at')
	        ->whereIn(
	            'fitout_status',
	            [
	                'Approved',
	                'In Progress',
	                'Completed',
	            ]
	        )
	        ->pluck('unit_id')
	        ->unique();


	    $activeUnitsByFloor = DB::table('units')
	        ->join(
	            'floors',
	            'floors.id',
	            '=',
	            'units.floor_id'
	        )
	        ->whereNull(
	            'units.deleted_at'
	        )
	        ->where(
	            'floors.status',
	            1
	        )
	        ->whereIn(
	            'units.id',
	            $activeUnitIds
	        )
	        ->select(
	            'floors.id',
	            DB::raw(
	                'COUNT(units.id) as active_units'
	            )
	        )
	        ->groupBy(
	            'floors.id'
	        )
	        ->pluck(
	            'active_units',
	            'id'
	        );


	    /*
	    |--------------------------------------------------------------------------
	    | ATTENTION
	    |--------------------------------------------------------------------------
	    */

	    $attentionSnags = DB::table('snag_lists')
	        ->join(
	            'fitout_requests',
	            'fitout_requests.id',
	            '=',
	            'snag_lists.fitout_request_id'
	        )
	        ->whereNull(
	            'snag_lists.deleted_at'
	        )
	        ->whereNull(
	            'fitout_requests.deleted_at'
	        )
	        ->whereIn(
	            'snag_lists.priority',
	            [
	                'Critical',
	                'High',
	            ]
	        )
	        ->whereNotIn(
	            'snag_lists.status',
	            [
	                'Closed',
	            ]
	        )
	        ->select(
	            'snag_lists.*',
	            'fitout_requests.request_no'
	        )
	        ->orderByRaw("
	            CASE snag_lists.priority
	                WHEN 'Critical' THEN 1
	                WHEN 'High' THEN 2
	                ELSE 3
	            END
	        ")
	        ->limit(5)
	        ->get();


	    /*
	    |--------------------------------------------------------------------------
	    | DELAYED
	    |--------------------------------------------------------------------------
	    */

	    $today = now()->toDateString();

	    $delayedFitouts = (clone $baseQuery)
	        ->whereDate(
	            'fitout_requests.proposed_end_date',
	            '<',
	            $today
	        )
	        ->whereNotIn(
	            'fitout_requests.fitout_status',
	            [
	                'Completed',
	                'Closed',
	            ]
	        )
	        ->orderBy(
	            'fitout_requests.proposed_end_date'
	        )
	        ->limit(5)
	        ->get();


	    /*
	    |--------------------------------------------------------------------------
	    | UPCOMING INSPECTIONS
	    |--------------------------------------------------------------------------
	    */

	    $upcomingInspections = DB::table('inspections')
	        ->join(
	            'fitout_requests',
	            'fitout_requests.id',
	            '=',
	            'inspections.fitout_request_id'
	        )
	        ->whereNull(
	            'inspections.deleted_at'
	        )
	        ->whereNull(
	            'fitout_requests.deleted_at'
	        )
	        ->whereDate(
	            'inspections.scheduled_date',
	            '>=',
	            $today
	        )
	        ->whereIn(
	            'inspections.status',
	            [
	                'Scheduled',
	                'In Progress',
	            ]
	        )
	        ->select(
	            'inspections.*',
	            'fitout_requests.request_no'
	        )
	        ->orderBy(
	            'inspections.scheduled_date'
	        )
	        ->limit(5)
	        ->get();


	    /*
	    |--------------------------------------------------------------------------
	    | VIEW
	    |--------------------------------------------------------------------------
	    */

	    return view(
	        'admin.fitout.dashboard',
	        compact(
	            'filters',
	            'floors',
	            'units',
	            'tenants',
	            'contractors',

	            'fitouts',

	            'totalFitouts',
	            'approvedFitouts',
	            'inFitout',
	            'inspectionsDue',
	            'openSnags',
	            'criticalSnags',
	            'completedHandovers',

	            'pipelineStart',
	            'pipelineApproval',
	            'pipelineFitout',
	            'pipelineInspection',
	            'pipelineSnag',
	            'pipelineHandover',

	            'overallProgress',
	            'stageProgress',
	            'stageStatus',

	            'unitsByFloor',
	            'activeUnitsByFloor',

	            'attentionSnags',
	            'delayedFitouts',
	            'upcomingInspections'
	        )
	    );
	}
}