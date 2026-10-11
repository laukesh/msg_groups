<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class PortfolioInvestmentController extends Controller
{

	public function portfolioOverview()
{
    /*
    |--------------------------------------------------------------------------
    | Portfolio & Investment KPIs
    |--------------------------------------------------------------------------
    */

    $stats = [

        /*
        |--------------------------------------------------------------------------
        | Row 1: Portfolio Snapshot
        |--------------------------------------------------------------------------
        */

        'total_portfolio_assets' => DB::table('lands')
            ->whereNull('deleted_at')
            ->count(),

        'total_land_area' => DB::table('lands')
            ->whereNull('deleted_at')
            ->sum('total_area'),

        // Asset valuation table/columns are not confirmed yet.
        'total_asset_value' => null,

        'total_acquisition_cost' => DB::table('land_acquisition_costs')
            ->whereNull('deleted_at')
            ->sum('total_amount'),


        /*
        |--------------------------------------------------------------------------
        | Row 2: Acquisition & Investment Pipeline
        |--------------------------------------------------------------------------
        */

        // Pipeline value source/column is not confirmed yet.
        'pipeline_value' => null,

        'active_opportunities' => DB::table('land_opportunities')
            ->whereNull('deleted_at')
            ->count(),

        'assets_under_due_diligence' => DB::table('land_due_diligences')
            ->whereNull('deleted_at')
            ->whereIn('status', [
                'Pending',
                'In Progress',
            ])
            ->distinct()
            ->count('land_id'),

        'pending_investment_decisions' => DB::table('investment_decisions')
            ->whereIn('status', [
                'Draft',
                'Pending',
                'Under Review',
            ])
            ->count(),


        /*
        |--------------------------------------------------------------------------
        | Row 3: Financial Outlook
        |--------------------------------------------------------------------------
        */

        // These sources/columns need to be confirmed from your schema.
        'total_development_cost' => null,

        'forecast_revenue' => null,

        'forecast_noi' => null,

        'planned_capital' => null,


        /*
        |--------------------------------------------------------------------------
        | Row 4: Investment Returns
        |--------------------------------------------------------------------------
        */

        'irr' => null,

        'roi' => null,

        'npv' => null,

        'yield' => null,

        'payback_period' => null,


        /*
        |--------------------------------------------------------------------------
        | Existing Investment Summary
        |--------------------------------------------------------------------------
        */

        'total_feasibility' => DB::table('feasibility_assessments')
            ->whereNull('deleted_at')
            ->count(),

        'total_investment_analyses' => DB::table('investment_analyses')
            ->count(),

        'recommended_investment' => DB::table('investment_analyses')
            ->sum('recommended_investment'),

        'approved_investment' => DB::table('investment_decisions')
            ->sum('approved_investment'),
    ];


    /*
    |--------------------------------------------------------------------------
    | Acquisition Pipeline
    |--------------------------------------------------------------------------
    */

    $acquisitionPipeline = DB::table('lands')
        ->select(
            'acquisition_status',
            DB::raw('COUNT(*) as total')
        )
        ->whereNull('deleted_at')
        ->groupBy('acquisition_status')
        ->orderBy('acquisition_status')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Land Type Summary
    |--------------------------------------------------------------------------
    */

    $landTypeSummary = DB::table('lands')
        ->select(
            'land_type',
            DB::raw('COUNT(*) as total'),
            DB::raw('COALESCE(SUM(total_area), 0) as total_area')
        )
        ->whereNull('deleted_at')
        ->groupBy('land_type')
        ->orderByDesc('total')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Recent Investment Decisions
    |--------------------------------------------------------------------------
    */

    $recentDecisions = DB::table('investment_decisions as decisions')
        ->leftJoin(
            'feasibility_assessments as assessments',
            'assessments.id',
            '=',
            'decisions.feasibility_assessment_id'
        )
        ->leftJoin(
            'lands',
            'lands.id',
            '=',
            'assessments.land_id'
        )
        ->select([
            'decisions.id',
            'decisions.decision_number',
            'decisions.title',
            'decisions.status',
            'decisions.decision',
            'decisions.decision_date',
            'decisions.approved_investment',
            'lands.land_name',
            'lands.land_code',
        ])
        ->latest('decisions.created_at')
        ->limit(10)
        ->get();


    return view(
        'admin.portfolio-investment.overview',
        compact(
            'stats',
            'acquisitionPipeline',
            'landTypeSummary',
            'recentDecisions'
        )
    );
}


	public function acquisitionPipeline()
	{
	    $pipeline = DB::table('lands as l')
	        ->leftJoin('land_opportunities as o', 'o.id', '=', 'l.opportunity_id')

	        ->leftJoinSub(
	            DB::table('land_due_diligences')
	                ->select(
	                    'land_id',
	                    DB::raw('COUNT(*) as due_diligence_count'),
	                    DB::raw("SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) as completed_due_diligence")
	                )
	                ->whereNull('deleted_at')
	                ->groupBy('land_id'),
	            'dd',
	            'dd.land_id',
	            '=',
	            'l.id'
	        )

	        ->leftJoinSub(
	            DB::table('feasibility_assessments')
	                ->select(
	                    'land_id',
	                    DB::raw('COUNT(*) as feasibility_count')
	                )
	                ->whereNull('deleted_at')
	                ->groupBy('land_id'),
	            'fa',
	            'fa.land_id',
	            '=',
	            'l.id'
	        )

	        ->leftJoinSub(
	            DB::table('feasibility_assessments as f')
	                ->join('investment_analyses as ia', 'ia.feasibility_assessment_id', '=', 'f.id')
	                ->select(
	                    'f.land_id',
	                    DB::raw('COUNT(ia.id) as investment_analysis_count')
	                )
	                ->whereNull('f.deleted_at')
	                ->groupBy('f.land_id'),
	            'ia_count',
	            'ia_count.land_id',
	            '=',
	            'l.id'
	        )

	        ->leftJoinSub(
	            DB::table('feasibility_assessments as f')
	                ->join('investment_decisions as idc', 'idc.feasibility_assessment_id', '=', 'f.id')
	                ->select(
	                    'f.land_id',
	                    DB::raw('COUNT(idc.id) as investment_decision_count')
	                )
	                ->whereNull('f.deleted_at')
	                ->groupBy('f.land_id'),
	            'decision_count',
	            'decision_count.land_id',
	            '=',
	            'l.id'
	        )

	        ->whereNull('l.deleted_at')
	        ->select(
	            'l.id',
	            'l.land_code',
	            'l.land_name',
	            'l.acquisition_status',
	            'l.total_area',
	            'l.area_unit',
	            'l.city',
	            'l.state',
	            'l.acquisition_date',
	            'o.opportunity_name',
	            'o.opportunity_no',
	            'o.estimated_acquisition_cost',
	            DB::raw('COALESCE(dd.due_diligence_count, 0) as due_diligence_count'),
	            DB::raw('COALESCE(dd.completed_due_diligence, 0) as completed_due_diligence'),
	            DB::raw('COALESCE(fa.feasibility_count, 0) as feasibility_count'),
	            DB::raw('COALESCE(ia_count.investment_analysis_count, 0) as investment_analysis_count'),
	            DB::raw('COALESCE(decision_count.investment_decision_count, 0) as investment_decision_count')
	        )
	        ->orderByDesc('l.created_at')
	        ->get();

	    foreach ($pipeline as $item) {
	        if (in_array($item->acquisition_status, ['Acquired', 'Completed', 'Registered'])) {
	            $item->pipeline_stage = 'Acquired';
	        } elseif ($item->investment_decision_count > 0) {
	            $item->pipeline_stage = 'Investment Decision';
	        } elseif ($item->investment_analysis_count > 0) {
	            $item->pipeline_stage = 'Investment Analysis';
	        } elseif ($item->feasibility_count > 0) {
	            $item->pipeline_stage = 'Feasibility Study';
	        } elseif ($item->due_diligence_count > 0) {
	            $item->pipeline_stage = 'Due Diligence';
	        } else {
	            $item->pipeline_stage = 'Land Evaluation';
	        }
	    }

	    $stageSummary = $pipeline
	        ->groupBy('pipeline_stage')
	        ->map(fn ($items) => $items->count());

	    return view(
	        'admin.portfolio-investment.acquisition-pipeline',
	        compact('pipeline', 'stageSummary')
	    );
	}

	public function developmentAppraisal()
	{
	    $appraisals = DB::table('investment_analyses as ia')
	        ->join(
	            'feasibility_assessments as fa',
	            'fa.id',
	            '=',
	            'ia.feasibility_assessment_id'
	        )
	        ->join('lands as l', 'l.id', '=', 'fa.land_id')
	        ->select(
	            'ia.id',
	            'ia.analysis_number',
	            'ia.title',
	            'ia.status',
	            'ia.total_investment',
	            'ia.expected_revenue',
	            'ia.expected_profit',
	            'ia.roi',
	            'ia.irr',
	            'ia.npv',
	            'ia.payback_period',
	            'ia.project_valuation',
	            'ia.investment_valuation',
	            'ia.exit_valuation',
	            'ia.base_case_irr',
	            'ia.optimistic_case_irr',
	            'ia.pessimistic_case_irr',
	            'ia.investment_risk_rating',
	            'ia.investment_attractiveness',
	            'fa.assessment_number',
	            'fa.title as feasibility_title',
	            'l.id as land_id',
	            'l.land_code',
	            'l.land_name'
	        )
	        ->whereNull('fa.deleted_at')
	        ->whereNull('l.deleted_at')
	        ->orderByDesc('ia.created_at')
	        ->get();

	    $summary = [
	        'total_appraisals' => $appraisals->count(),

	        'total_investment' => $appraisals->sum(
	            fn ($item) => (float) ($item->total_investment ?? 0)
	        ),

	        'expected_revenue' => $appraisals->sum(
	            fn ($item) => (float) ($item->expected_revenue ?? 0)
	        ),

	        'expected_profit' => $appraisals->sum(
	            fn ($item) => (float) ($item->expected_profit ?? 0)
	        ),

	        'average_roi' => $appraisals
	            ->whereNotNull('roi')
	            ->avg('roi'),

	        'average_irr' => $appraisals
	            ->whereNotNull('irr')
	            ->avg('irr'),
	    ];

	    return view(
	        'admin.portfolio-investment.development-appraisal',
	        compact('appraisals', 'summary')
	    );
	}

	public function scenarioSensitivity()
	{
	    $analyses = DB::table('investment_analyses as ia')
	        ->join(
	            'feasibility_assessments as fa',
	            'fa.id',
	            '=',
	            'ia.feasibility_assessment_id'
	        )
	        ->join('lands as l', 'l.id', '=', 'fa.land_id')
	        ->select(
	            'ia.id',
	            'ia.analysis_number',
	            'ia.title',
	            'ia.status',
	            'ia.base_case_return',
	            'ia.optimistic_case_return',
	            'ia.pessimistic_case_return',
	            'ia.base_case_irr',
	            'ia.optimistic_case_irr',
	            'ia.pessimistic_case_irr',
	            'ia.revenue_sensitivity',
	            'ia.cost_sensitivity',
	            'ia.price_sensitivity',
	            'ia.interest_rate_sensitivity',
	            'ia.investment_risk_rating',
	            'ia.investment_attractiveness',
	            'ia.recommendation',
	            'fa.title as feasibility_title',
	            'l.id as land_id',
	            'l.land_code',
	            'l.land_name'
	        )
	        ->whereNull('fa.deleted_at')
	        ->whereNull('l.deleted_at')
	        ->orderByDesc('ia.created_at')
	        ->get();

	    $summary = [
	        'total_analyses' => $analyses->count(),

	        'average_base_irr' => $analyses
	            ->whereNotNull('base_case_irr')
	            ->avg('base_case_irr'),

	        'average_optimistic_irr' => $analyses
	            ->whereNotNull('optimistic_case_irr')
	            ->avg('optimistic_case_irr'),

	        'average_pessimistic_irr' => $analyses
	            ->whereNotNull('pessimistic_case_irr')
	            ->avg('pessimistic_case_irr'),

	        'high_risk_count' => $analyses
	            ->filter(function ($item) {
	                return in_array(
	                    strtolower((string) $item->investment_risk_rating),
	                    ['high', 'very high', 'critical']
	                );
	            })
	            ->count(),
	    ];

	    return view(
	        'admin.portfolio-investment.scenario-sensitivity',
	        compact('analyses', 'summary')
	    );
	}

	public function investmentCommittee()
	{
	    $decisions = DB::table('investment_decisions as idc')
	        ->join(
	            'feasibility_assessments as fa',
	            'fa.id',
	            '=',
	            'idc.feasibility_assessment_id'
	        )
	        ->join('lands as l', 'l.id', '=', 'fa.land_id')
	        ->select(
	            'idc.id',
	            'idc.decision_number',
	            'idc.title',
	            'idc.status',
	            'idc.decision',
	            'idc.decision_date',
	            'idc.decision_by',
	            'idc.committee_name',
	            'idc.committee_members',
	            'idc.committee_notes',
	            'idc.investment_recommendation',
	            'idc.investment_priority',
	            'idc.recommended_investment',
	            'idc.approved_investment',
	            'idc.expected_roi',
	            'idc.expected_irr',
	            'idc.expected_npv',
	            'idc.expected_payback_period',
	            'idc.overall_score',
	            'idc.approval_conditions',
	            'idc.final_recommendation',
	            'idc.management_comments',
	            'fa.assessment_number',
	            'fa.title as feasibility_title',
	            'l.id as land_id',
	            'l.land_code',
	            'l.land_name'
	        )
	        ->whereNull('fa.deleted_at')
	        ->whereNull('l.deleted_at')
	        ->orderByDesc('idc.created_at')
	        ->get();

	    $summary = [
	        'total_decisions' => $decisions->count(),

	        'pending_decisions' => $decisions
	            ->filter(function ($item) {
	                return in_array(
	                    strtolower((string) $item->status),
	                    ['draft', 'pending', 'under review', 'in progress']
	                );
	            })
	            ->count(),

	        'approved_decisions' => $decisions
	            ->filter(function ($item) {
	                return in_array(
	                    strtolower((string) $item->status),
	                    ['approved', 'approved with conditions']
	                );
	            })
	            ->count(),

	        'rejected_decisions' => $decisions
	            ->filter(function ($item) {
	                return in_array(
	                    strtolower((string) $item->status),
	                    ['rejected', 'declined']
	                );
	            })
	            ->count(),

	        'total_approved_investment' => $decisions->sum(
	            fn ($item) => (float) ($item->approved_investment ?? 0)
	        ),

	        'average_score' => $decisions
	            ->whereNotNull('overall_score')
	            ->avg('overall_score'),
	    ];

	    return view(
	        'admin.portfolio-investment.investment-committee',
	        compact('decisions', 'summary')
	    );
	}

	public function capitalPlanning()
	{
	    $capitalPlans = DB::table('investment_decisions as idc')
	        ->join(
	            'feasibility_assessments as fa',
	            'fa.id',
	            '=',
	            'idc.feasibility_assessment_id'
	        )
	        ->join('lands as l', 'l.id', '=', 'fa.land_id')
	        ->select(
	            'idc.id',
	            'idc.decision_number',
	            'idc.title',
	            'idc.status',
	            'idc.investment_priority',
	            'idc.recommended_investment',
	            'idc.approved_investment',
	            'idc.expected_roi',
	            'idc.expected_irr',
	            'idc.expected_npv',
	            'idc.decision_date',
	            'idc.investment_recommendation',
	            'fa.title as feasibility_title',
	            'l.id as land_id',
	            'l.land_code',
	            'l.land_name'
	        )
	        ->whereNull('fa.deleted_at')
	        ->whereNull('l.deleted_at')
	        ->orderByDesc('idc.created_at')
	        ->get();

	    $summary = [
	        'total_plans' => $capitalPlans->count(),

	        'total_recommended' => $capitalPlans->sum(
	            fn ($item) => (float) ($item->recommended_investment ?? 0)
	        ),

	        'total_approved' => $capitalPlans->sum(
	            fn ($item) => (float) ($item->approved_investment ?? 0)
	        ),

	        'pending_capital' => $capitalPlans->sum(
	            fn ($item) => max(
	                0,
	                (float) ($item->recommended_investment ?? 0)
	                - (float) ($item->approved_investment ?? 0)
	            )
	        ),

	        'high_priority' => $capitalPlans
	            ->filter(function ($item) {
	                return in_array(
	                    strtolower((string) $item->investment_priority),
	                    ['high', 'critical', 'urgent']
	                );
	            })
	            ->count(),

	        'average_irr' => $capitalPlans
	            ->whereNotNull('expected_irr')
	            ->avg('expected_irr'),
	    ];

	    $prioritySummary = $capitalPlans
	        ->groupBy(function ($item) {
	            return $item->investment_priority ?: 'Not Set';
	        })
	        ->map(fn ($items) => [
	            'count' => $items->count(),
	            'recommended' => $items->sum(
	                fn ($item) => (float) ($item->recommended_investment ?? 0)
	            ),
	            'approved' => $items->sum(
	                fn ($item) => (float) ($item->approved_investment ?? 0)
	            ),
	        ]);

	    return view(
	        'admin.portfolio-investment.capital-planning',
	        compact('capitalPlans', 'summary', 'prioritySummary')
	    );
	}

	public function valuation()
	{
	    $valuations = DB::table('investment_analyses as ia')
	        ->join(
	            'feasibility_assessments as fa',
	            'fa.id',
	            '=',
	            'ia.feasibility_assessment_id'
	        )
	        ->join('lands as l', 'l.id', '=', 'fa.land_id')
	        ->select(
	            'ia.id',
	            'ia.analysis_number',
	            'ia.title',
	            'ia.status',
	            'ia.project_valuation',
	            'ia.investment_valuation',
	            'ia.exit_valuation',
	            'ia.expected_exit_value',
	            'ia.exit_strategy',
	            'ia.exit_period',
	            'ia.base_case_return',
	            'ia.optimistic_case_return',
	            'ia.pessimistic_case_return',
	            'ia.investment_risk_rating',
	            'fa.title as feasibility_title',
	            'l.id as land_id',
	            'l.land_code',
	            'l.land_name'
	        )
	        ->whereNull('fa.deleted_at')
	        ->whereNull('l.deleted_at')
	        ->orderByDesc('ia.created_at')
	        ->get();

	    $summary = [
	        'total_valuations' => $valuations->count(),

	        'total_project_valuation' => $valuations->sum(
	            fn ($item) => (float) ($item->project_valuation ?? 0)
	        ),

	        'total_investment_valuation' => $valuations->sum(
	            fn ($item) => (float) ($item->investment_valuation ?? 0)
	        ),

	        'total_exit_valuation' => $valuations->sum(
	            fn ($item) => (float) ($item->exit_valuation ?? 0)
	        ),

	        'total_expected_exit_value' => $valuations->sum(
	            fn ($item) => (float) ($item->expected_exit_value ?? 0)
	        ),
	    ];

	    return view(
	        'admin.portfolio-investment.valuation',
	        compact('valuations', 'summary')
	    );
	}

	public function assetStrategy()
	{
	    /*
	    |--------------------------------------------------------------------------
	    | Latest Investment Analysis Per Feasibility Assessment
	    |--------------------------------------------------------------------------
	    */

	    $latestAnalysis = DB::table('investment_analyses')
	        ->select(
	            'feasibility_assessment_id',
	            DB::raw('MAX(id) as latest_analysis_id')
	        )
	        ->groupBy('feasibility_assessment_id');


	    /*
	    |--------------------------------------------------------------------------
	    | Latest Investment Decision Per Feasibility Assessment
	    |--------------------------------------------------------------------------
	    */

	    $latestDecision = DB::table('investment_decisions')
	        ->select(
	            'feasibility_assessment_id',
	            DB::raw('MAX(id) as latest_decision_id')
	        )
	        ->groupBy('feasibility_assessment_id');


	    /*
	    |--------------------------------------------------------------------------
	    | Strategic Asset / Business Plan Data
	    |--------------------------------------------------------------------------
	    */

	    $plans = DB::table('feasibility_assessments as fa')
	        ->join('lands as l', 'l.id', '=', 'fa.land_id')

	        ->leftJoinSub(
	            $latestAnalysis,
	            'latest_ia',
	            function ($join) {
	                $join->on(
	                    'latest_ia.feasibility_assessment_id',
	                    '=',
	                    'fa.id'
	                );
	            }
	        )

	        ->leftJoin(
	            'investment_analyses as ia',
	            'ia.id',
	            '=',
	            'latest_ia.latest_analysis_id'
	        )

	        ->leftJoinSub(
	            $latestDecision,
	            'latest_id',
	            function ($join) {
	                $join->on(
	                    'latest_id.feasibility_assessment_id',
	                    '=',
	                    'fa.id'
	                );
	            }
	        )

	        ->leftJoin(
	            'investment_decisions as idc',
	            'idc.id',
	            '=',
	            'latest_id.latest_decision_id'
	        )

	        ->whereNull('fa.deleted_at')
	        ->whereNull('l.deleted_at')

	        ->select([
	            'fa.id',
	            'fa.assessment_number',
	            'fa.title',
	            'fa.project_concept',
	            'fa.development_type',
	            'fa.status as feasibility_status',
	            'fa.assessment_date',
	            'fa.target_completion_date',
	            'fa.summary',
	            'fa.key_assumptions',
	            'fa.key_risks',
	            'fa.recommendation as feasibility_recommendation',

	            'l.id as land_id',
	            'l.land_code',
	            'l.land_name',
	            'l.acquisition_status',

	            'ia.analysis_number',
	            'ia.title as analysis_title',
	            'ia.expected_revenue',
	            'ia.expected_profit',
	            'ia.expected_cash_flow',
	            'ia.project_valuation',
	            'ia.exit_strategy',
	            'ia.exit_period',
	            'ia.investment_risk_rating',
	            'ia.investment_attractiveness',
	            'ia.recommendation as analysis_recommendation',

	            'idc.decision_number',
	            'idc.status as decision_status',
	            'idc.investment_recommendation',
	            'idc.investment_priority',
	            'idc.approved_investment',
	            'idc.final_recommendation',
	        ])

	        ->orderByDesc('fa.id')
	        ->get();


	    /*
	    |--------------------------------------------------------------------------
	    | Dashboard Statistics
	    |--------------------------------------------------------------------------
	    */

	    $stats = [
	        'total_plans' => $plans->count(),

	        'active_plans' => $plans
	            ->whereIn('feasibility_status', [
	                'In Progress',
	                'Under Review',
	                'Approved',
	                'Completed',
	            ])
	            ->count(),

	        'approved_plans' => $plans
	            ->filter(function ($plan) {
	                return in_array(
	                    strtolower((string) $plan->decision_status),
	                    ['approved', 'approve']
	                );
	            })
	            ->count(),

	        'high_risk_plans' => $plans
	            ->filter(function ($plan) {
	                return in_array(
	                    strtolower((string) $plan->investment_risk_rating),
	                    ['high', 'very high', 'critical']
	                );
	            })
	            ->count(),

	        'total_expected_revenue' => $plans->sum(function ($plan) {
	            return (float) ($plan->expected_revenue ?? 0);
	        }),

	        'total_expected_profit' => $plans->sum(function ($plan) {
	            return (float) ($plan->expected_profit ?? 0);
	        }),
	    ];


	    return view(
	        'admin.portfolio-investment.asset-strategy',
	        compact('plans', 'stats')
	    );
	}

	public function performanceMonitoring()
	{
	    /*
	    |--------------------------------------------------------------------------
	    | Latest Investment Analysis
	    |--------------------------------------------------------------------------
	    */

	    $latestAnalysis = DB::table('investment_analyses')
	        ->select(
	            'feasibility_assessment_id',
	            DB::raw('MAX(id) as latest_analysis_id')
	        )
	        ->groupBy('feasibility_assessment_id');


	    /*
	    |--------------------------------------------------------------------------
	    | Latest Investment Decision
	    |--------------------------------------------------------------------------
	    */

	    $latestDecision = DB::table('investment_decisions')
	        ->select(
	            'feasibility_assessment_id',
	            DB::raw('MAX(id) as latest_decision_id')
	        )
	        ->groupBy('feasibility_assessment_id');


	    /*
	    |--------------------------------------------------------------------------
	    | Construction Progress
	    |--------------------------------------------------------------------------
	    */

	    $latestProgress = DB::table('construction_progress_updates as cpu1')
	        ->select(
	            'cpu1.project_id',
	            'cpu1.progress_percentage'
	        )
	        ->whereRaw('
	            cpu1.id = (
	                SELECT MAX(cpu2.id)
	                FROM construction_progress_updates cpu2
	                WHERE cpu2.project_id = cpu1.project_id
	            )
	        ');


	    /*
	    |--------------------------------------------------------------------------
	    | Portfolio Performance Data
	    |--------------------------------------------------------------------------
	    */

	    $performance = DB::table('feasibility_assessments as fa')
	        ->join('lands as l', 'l.id', '=', 'fa.land_id')

	        ->leftJoinSub(
	            $latestAnalysis,
	            'latest_ia',
	            function ($join) {
	                $join->on(
	                    'latest_ia.feasibility_assessment_id',
	                    '=',
	                    'fa.id'
	                );
	            }
	        )

	        ->leftJoin(
	            'investment_analyses as ia',
	            'ia.id',
	            '=',
	            'latest_ia.latest_analysis_id'
	        )

	        ->leftJoinSub(
	            $latestDecision,
	            'latest_id',
	            function ($join) {
	                $join->on(
	                    'latest_id.feasibility_assessment_id',
	                    '=',
	                    'fa.id'
	                );
	            }
	        )

	        ->leftJoin(
	            'investment_decisions as idc',
	            'idc.id',
	            '=',
	            'latest_id.latest_decision_id'
	        )

	        ->leftJoin(
	            'projects as p',
	            'p.land_id',
	            '=',
	            'l.id'
	        )

	        ->leftJoinSub(
	            $latestProgress,
	            'lp',
	            function ($join) {
	                $join->on('lp.project_id', '=', 'p.id');
	            }
	        )

	        ->whereNull('fa.deleted_at')
	        ->whereNull('l.deleted_at')

	        ->select([
	            'fa.id',
	            'fa.assessment_number',
	            'fa.title',
	            'fa.development_type',
	            'fa.status as feasibility_status',
	            'fa.target_completion_date',

	            'l.land_code',
	            'l.land_name',

	            'ia.expected_revenue',
	            'ia.expected_profit',
	            'ia.project_valuation',
	            'ia.investment_risk_rating',
	            'ia.investment_attractiveness',

	            'idc.status as decision_status',
	            'idc.approved_investment',
	            'idc.investment_priority',

	            'p.id as project_id',
	            'p.project_code',
	            'p.project_name',
	            'p.project_status as project_status',

	            'lp.progress_percentage',
	        ])

	        ->orderByDesc('fa.id')
	        ->get();


	    /*
	    |--------------------------------------------------------------------------
	    | Statistics
	    |--------------------------------------------------------------------------
	    */

	    $stats = [
	        'total_assets' => $performance->count(),

	        'approved_assets' => $performance
	            ->filter(function ($item) {
	                return in_array(
	                    strtolower((string) $item->decision_status),
	                    ['approved', 'approve']
	                );
	            })
	            ->count(),

	        'projects_under_execution' => $performance
	            ->filter(function ($item) {
	                return !empty($item->project_id);
	            })
	            ->count(),

	        'completed_projects' => $performance
	            ->filter(function ($item) {
	                return strtolower((string) $item->project_status) === 'completed';
	            })
	            ->count(),

	        'average_progress' => round(
	            $performance
	                ->filter(function ($item) {
	                    return $item->progress_percentage !== null;
	                })
	                ->avg('progress_percentage') ?? 0,
	            2
	        ),

	        'total_expected_revenue' => $performance->sum(function ($item) {
	            return (float) ($item->expected_revenue ?? 0);
	        }),

	        'total_expected_profit' => $performance->sum(function ($item) {
	            return (float) ($item->expected_profit ?? 0);
	        }),
	    ];


	    return view(
	        'admin.portfolio-investment.performance-monitoring',
	        compact('performance', 'stats')
	    );
	}

	public function dueDiligence()
	{
	    $dueDiligences = DB::table('land_due_diligences as ldd')
	        ->join('lands as l', 'l.id', '=', 'ldd.land_id')
	        ->select([
	            'ldd.id',
	            'ldd.land_id',
	            'ldd.type',
	            'ldd.reference_no',
	            'ldd.assessment_date',
	            'ldd.conducted_by',
	            'ldd.status',
	            'ldd.summary',
	            'ldd.findings',
	            'ldd.recommendations',
	            'ldd.remarks',

	            'l.land_code',
	            'l.land_name',
	            'l.acquisition_status',
	        ])
	        ->whereNull('l.deleted_at')
	        ->orderByDesc('ldd.id')
	        ->get();


	    $stats = [
	        'total' => $dueDiligences->count(),

	        'completed' => $dueDiligences
	            ->filter(function ($item) {
	                return in_array(
	                    strtolower((string) $item->status),
	                    ['completed', 'complete', 'approved']
	                );
	            })
	            ->count(),

	        'in_progress' => $dueDiligences
	            ->filter(function ($item) {
	                return in_array(
	                    strtolower((string) $item->status),
	                    ['in progress', 'under review', 'pending']
	                );
	            })
	            ->count(),

	        'pending' => $dueDiligences
	            ->filter(function ($item) {
	                return in_array(
	                    strtolower((string) $item->status),
	                    ['pending', 'not started', 'draft']
	                );
	            })
	            ->count(),
	    ];


	    return view(
	        'admin.portfolio-investment.due-diligence',
	        compact('dueDiligences', 'stats')
	    );
	}

	public function feasibilityStudies()
	{
	    $feasibilityStudies = DB::table('feasibility_assessments as fa')
	        ->join('lands as l', 'l.id', '=', 'fa.land_id')
	        ->leftJoin('investment_analyses as ia', 'ia.feasibility_assessment_id', '=', 'fa.id')
	        ->leftJoin('investment_decisions as idc', 'idc.feasibility_assessment_id', '=', 'fa.id')
	        ->select([
	            'fa.id',
	            'fa.land_id',
	            'fa.assessment_number',
	            'fa.title',
	            'fa.project_concept',
	            'fa.development_type',
	            'fa.status',
	            'fa.assessment_date',
	            'fa.target_completion_date',
	            'fa.summary',
	            'fa.recommendation',

	            'l.land_code',
	            'l.land_name',

	            DB::raw('COUNT(DISTINCT ia.id) as investment_analysis_count'),
	            DB::raw('COUNT(DISTINCT idc.id) as investment_decision_count'),
	        ])
	        ->whereNull('fa.deleted_at')
	        ->whereNull('l.deleted_at')
	        ->groupBy([
	            'fa.id',
	            'fa.land_id',
	            'fa.assessment_number',
	            'fa.title',
	            'fa.project_concept',
	            'fa.development_type',
	            'fa.status',
	            'fa.assessment_date',
	            'fa.target_completion_date',
	            'fa.summary',
	            'fa.recommendation',
	            'l.land_code',
	            'l.land_name',
	        ])
	        ->orderByDesc('fa.id')
	        ->get();

	    $stats = [
	        'total' => $feasibilityStudies->count(),

	        'completed' => $feasibilityStudies->filter(function ($item) {
	            return in_array(strtolower((string) $item->status), [
	                'completed',
	                'complete',
	                'approved',
	            ]);
	        })->count(),

	        'in_progress' => $feasibilityStudies->filter(function ($item) {
	            return in_array(strtolower((string) $item->status), [
	                'in progress',
	                'under review',
	                'ongoing',
	            ]);
	        })->count(),

	        'pending' => $feasibilityStudies->filter(function ($item) {
	            return in_array(strtolower((string) $item->status), [
	                'pending',
	                'draft',
	                'not started',
	            ]);
	        })->count(),
	    ];

	    return view(
	        'admin.portfolio-investment.feasibility-studies',
	        compact('feasibilityStudies', 'stats')
	    );
	}

	public function acquisitionDisposal()
	{
	    $lands = DB::table('lands as l')
		    ->leftJoin('land_acquisition_costs as lac', function ($join) {
		        $join->on('lac.land_id', '=', 'l.id')
		             ->whereNull('lac.deleted_at');
		    })
		    ->select([
		        'l.id',
		        'l.land_code',
		        'l.land_name',
		        'l.land_type',
		        'l.total_area',
		        'l.area_unit',
		        'l.acquisition_status',
		        'l.acquisition_date',

		        'l.address_line1',
		        'l.address_line2',
		        'l.locality',
		        'l.city',
		        'l.state',
		        'l.country',
		        'l.postal_code',

		        DB::raw('COALESCE(SUM(lac.total_amount), 0) as total_acquisition_cost'),
		    ])
		    ->whereNull('l.deleted_at')
		    ->groupBy([
		        'l.id',
		        'l.land_code',
		        'l.land_name',
		        'l.land_type',
		        'l.total_area',
		        'l.area_unit',
		        'l.acquisition_status',
		        'l.acquisition_date',

		        'l.address_line1',
		        'l.address_line2',
		        'l.locality',
		        'l.city',
		        'l.state',
		        'l.country',
		        'l.postal_code',
		    ])
		    ->orderByDesc('l.id')
		    ->get();

	    $stats = [
	        'total' => $lands->count(),

	        'acquired' => $lands->filter(function ($land) {
	            return in_array(strtolower((string) $land->acquisition_status), [
	                'acquired',
	                'completed',
	                'registered',
	            ]);
	        })->count(),

	        'in_progress' => $lands->filter(function ($land) {
	            return in_array(strtolower((string) $land->acquisition_status), [
	                'in progress',
	                'under acquisition',
	                'pending',
	            ]);
	        })->count(),

	        'available' => $lands->filter(function ($land) {
	            return in_array(strtolower((string) $land->acquisition_status), [
	                'available',
	                'open',
	                'identified',
	            ]);
	        })->count(),
	    ];

	    return view(
	        'admin.portfolio-investment.acquisition-disposal',
	        compact('lands', 'stats')
	    );
	}

	public function investmentReports()
	{
	    /*
	    |--------------------------------------------------------------------------
	    | Portfolio Summary
	    |--------------------------------------------------------------------------
	    */

	    $totalLands = DB::table('lands')
	        ->whereNull('deleted_at')
	        ->count();

	    $totalLandArea = DB::table('lands')
	        ->whereNull('deleted_at')
	        ->sum('total_area');

	    $totalAcquisitionCost = DB::table('land_acquisition_costs')
	        ->whereNull('deleted_at')
	        ->sum('total_amount');

	    /*
	    |--------------------------------------------------------------------------
	    | Feasibility & Investment Summary
	    |--------------------------------------------------------------------------
	    */

	    $totalFeasibilityStudies = DB::table('feasibility_assessments')
	        ->count();

	    $totalInvestmentAnalyses = DB::table('investment_analyses')
	        ->count();

	    $totalInvestmentDecisions = DB::table('investment_decisions')
	        ->count();

	    /*
	    |--------------------------------------------------------------------------
	    | Land Acquisition Status
	    |--------------------------------------------------------------------------
	    */

	    $acquisitionStatus = DB::table('lands')
	        ->select(
	            'acquisition_status',
	            DB::raw('COUNT(*) as total')
	        )
	        ->whereNull('deleted_at')
	        ->groupBy('acquisition_status')
	        ->orderByDesc('total')
	        ->get();

	    /*
	    |--------------------------------------------------------------------------
	    | Land Type Summary
	    |--------------------------------------------------------------------------
	    */

	    $landTypeSummary = DB::table('lands')
	        ->select(
	            'land_type',
	            DB::raw('COUNT(*) as total'),
	            DB::raw('COALESCE(SUM(total_area), 0) as total_area')
	        )
	        ->whereNull('deleted_at')
	        ->groupBy('land_type')
	        ->orderByDesc('total')
	        ->get();

	    /*
	    |--------------------------------------------------------------------------
	    | Recent Investment Decisions
	    |--------------------------------------------------------------------------
	    */

	    $recentInvestmentDecisions = DB::table('investment_decisions as idc')
		    ->leftJoin(
		        'feasibility_assessments as fa',
		        'fa.id',
		        '=',
		        'idc.feasibility_assessment_id'
		    )
		    ->leftJoin(
		        'lands as l',
		        'l.id',
		        '=',
		        'fa.land_id'
		    )
		    ->select([
		        'idc.*',
		        'fa.assessment_number',
		        'fa.title as assessment_name',
		        'fa.status as feasibility_status',
		        'fa.assessment_date',
		        'l.id as land_id',
		        'l.land_code',
		        'l.land_name',
		    ])
		    ->whereNull('fa.deleted_at')
		    ->whereNull('l.deleted_at')
		    ->orderByDesc('idc.id')
		    ->limit(10)
		    ->get();

	    return view(
	        'admin.portfolio-investment.investment-reports',
	        compact(
	            'totalLands',
	            'totalLandArea',
	            'totalAcquisitionCost',
	            'totalFeasibilityStudies',
	            'totalInvestmentAnalyses',
	            'totalInvestmentDecisions',
	            'acquisitionStatus',
	            'landTypeSummary',
	            'recentInvestmentDecisions'
	        )
	    );
	}

	public function ownershipUseStrategy()
	{
	    /*
	    |--------------------------------------------------------------------------
	    | Ownership Summary
	    |--------------------------------------------------------------------------
	    */

	    $totalOwners = DB::table('land_owners')
	        ->whereNull('deleted_at')
	        ->count();

	    $totalOwnedLands = DB::table('land_owners')
	        ->whereNull('deleted_at')
	        ->distinct('land_id')
	        ->count('land_id');

	    $ownerTypeSummary = DB::table('land_owners')
	        ->select(
	            'owner_type',
	            DB::raw('COUNT(*) as total_owners'),
	            DB::raw('COUNT(DISTINCT land_id) as total_lands')
	        )
	        ->whereNull('deleted_at')
	        ->groupBy('owner_type')
	        ->orderByDesc('total_owners')
	        ->get();

	    /*
	    |--------------------------------------------------------------------------
	    | Ownership Records
	    |--------------------------------------------------------------------------
	    */

	    $ownershipRecords = DB::table('land_owners as lo')
	        ->join('lands as l', 'l.id', '=', 'lo.land_id')
	        ->select([
	            'lo.id',
	            'lo.land_id',
	            'lo.owner_type',
	            'lo.owner_name',
	            'lo.ownership_percentage',
	            'lo.ownership_start_date',
	            'lo.ownership_end_date',
	            'lo.title_reference',
	            'lo.remarks',
	            'l.land_code',
	            'l.land_name',
	            'l.land_type',
	            'l.city',
	            'l.state',
	            'l.total_area',
	            'l.area_unit',
	        ])
	        ->whereNull('lo.deleted_at')
	        ->whereNull('l.deleted_at')
	        ->orderByDesc('lo.id')
	        ->get();

	    return view(
	        'admin.portfolio-investment.ownership-use-strategy',
	        compact(
	            'totalOwners',
	            'totalOwnedLands',
	            'ownerTypeSummary',
	            'ownershipRecords'
	        )
	    );
	}

}
