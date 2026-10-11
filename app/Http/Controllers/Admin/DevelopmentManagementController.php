<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DevelopmentManagementController extends Controller
{
    public function overview()
    {
        /*
        |--------------------------------------------------------------------------
        | Project Summary
        |--------------------------------------------------------------------------
        */

        $totalProjects = DB::table('projects')->count();

        $projectStatusSummary = DB::table('projects')
            ->select(
                'project_status',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('project_status')
            ->orderByDesc('total')
            ->get();

        $projectStageSummary = DB::table('projects')
            ->select(
                'project_stage',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('project_stage')
            ->orderByDesc('total')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Budget Summary
        |--------------------------------------------------------------------------
        */

        $budgetSummary = DB::table('project_budgets')
            ->selectRaw('
                COUNT(*) as total_budgets,
                COALESCE(SUM(total_budget), 0) as total_budget_amount,
                COALESCE(SUM(CASE
                    WHEN status = "Approved"
                    THEN total_budget
                    ELSE 0
                END), 0) as approved_budget_amount
            ')
            ->first();

        $budgetStatusSummary = DB::table('project_budgets')
            ->select(
                'status',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('status')
            ->orderByDesc('total')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Master Schedule Summary
        |--------------------------------------------------------------------------
        */

        $scheduleSummary = DB::table('master_schedules')
            ->selectRaw('
                COUNT(*) as total_schedules,
                COALESCE(AVG(planned_progress), 0) as average_planned_progress,
                COALESCE(AVG(actual_progress), 0) as average_actual_progress
            ')
            ->first();

        $totalScheduleActivities = DB::table('master_schedule_activities')
            ->count();

        $scheduleStatusSummary = DB::table('master_schedules')
            ->select(
                'status',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('status')
            ->orderByDesc('total')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Procurement Summary
        |--------------------------------------------------------------------------
        */

        $procurementSummary = DB::table('procurement_packages')
            ->selectRaw('
                COUNT(*) as total_packages,
                COALESCE(SUM(estimated_value), 0) as total_estimated_value
            ')
            ->first();

        $totalTenders = DB::table('procurement_tenders')->count();

        $totalProcurementContracts = DB::table('procurement_contracts')->count();

        $totalContractValue = DB::table('procurement_contracts')
            ->sum('contract_amount');

        $procurementStatusSummary = DB::table('procurement_packages')
            ->select(
                'status',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('status')
            ->orderByDesc('total')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Construction Summary
        |--------------------------------------------------------------------------
        */

        $totalConstructionWorkOrders = DB::table('construction_work_orders')
            ->count();

        $totalConstructionProgressUpdates = DB::table('construction_progress_updates')
            ->count();

        /*
        | Latest progress for each project
        | Prevents averaging every historical progress update.
        */

        $latestProgressSubquery = DB::table('construction_progress_updates as cpu')
            ->select(
                'cpu.project_id',
                DB::raw('MAX(cpu.id) as latest_id')
            )
            ->groupBy('cpu.project_id');

        $constructionProgressSummary = DB::table(
                'construction_progress_updates as progress'
            )
            ->joinSub(
                $latestProgressSubquery,
                'latest_progress',
                function ($join) {
                    $join->on(
                        'progress.id',
                        '=',
                        'latest_progress.latest_id'
                    );
                }
            )
            ->selectRaw('
                COUNT(*) as projects_with_progress,
                COALESCE(AVG(progress.progress_percentage), 0)
                    as average_actual_progress
            ')
            ->first();


        /*
        |--------------------------------------------------------------------------
        | Approval Configuration Summary
        |--------------------------------------------------------------------------
        |
        | project_approval_matrix stores approval rules/configuration.
        | It does not contain a transactional approval status such as
        | Pending or Approved.
        |--------------------------------------------------------------------------
        */

        $approvalMatrixSummary = DB::table('project_approval_matrix')
            ->select(
                'status',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('status')
            ->orderByDesc('total')
            ->get();

        $totalApprovalRules = DB::table('project_approval_matrix')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Risk Summary
        |--------------------------------------------------------------------------
        */

        $riskSummary = DB::table('project_risks')
            ->selectRaw('
                COUNT(*) as total_risks,

                SUM(CASE
                    WHEN status IN ("Open", "Monitoring")
                    THEN 1
                    ELSE 0
                END) as open_risks,

                SUM(CASE
                    WHEN risk_level = "Critical"
                    AND status NOT IN ("Closed", "Mitigated")
                    THEN 1
                    ELSE 0
                END) as critical_risks,

                SUM(CASE
                    WHEN status IN ("Closed", "Mitigated")
                    THEN 1
                    ELSE 0
                END) as resolved_risks
            ')
            ->first();

        $riskLevelSummary = DB::table('project_risks')
            ->select(
                'risk_level',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('risk_level')
            ->orderByDesc('total')
            ->get();

        $riskStatusSummary = DB::table('project_risks')
            ->select(
                'status',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('status')
            ->orderByDesc('total')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Recent Projects
        |--------------------------------------------------------------------------
        */

        $recentProjects = DB::table('projects')
            ->select(
                'id',
                'project_number',
                'project_code',
                'project_name',
                'project_stage',
                'project_status',
                'planned_completion_date',
                'created_at'
            )
            ->latest('id')
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Return Dashboard
        |--------------------------------------------------------------------------
        */

        return view('admin.development.overview', compact(
            'totalProjects',
            'projectStatusSummary',
            'projectStageSummary',

            'budgetSummary',
            'budgetStatusSummary',

            'scheduleSummary',
            'totalScheduleActivities',
            'scheduleStatusSummary',

            'procurementSummary',
            'totalTenders',
            'totalProcurementContracts',
            'totalContractValue',
            'procurementStatusSummary',

            'totalConstructionWorkOrders',
            'totalConstructionProgressUpdates',
            'constructionProgressSummary',

            'approvalMatrixSummary',
            'totalApprovalRules',

            'riskSummary',
            'riskLevelSummary',
            'riskStatusSummary',

            'recentProjects'
        ));
    }
}