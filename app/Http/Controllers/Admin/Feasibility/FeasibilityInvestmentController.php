<?php

namespace App\Http\Controllers\Admin\Feasibility;

use App\Http\Controllers\Controller;
use App\Models\FeasibilityAssessment;
use App\Models\InvestmentAnalysis;
use App\Models\InvestmentDecision;
use App\Models\Land;
use Illuminate\Http\Request;

class FeasibilityInvestmentController extends Controller
{
    /**
     * Feasibility & Investment Dashboard
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Search / Filter
        |--------------------------------------------------------------------------
        */

        $search = trim(
            (string) $request->input('search', '')
        );

        $status = $request->input('status');


        /*
        |--------------------------------------------------------------------------
        | Basic Counts
        |--------------------------------------------------------------------------
        */

        $totalLands = Land::query()
            ->count();


        $totalFeasibilities = FeasibilityAssessment::query()
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Pending Assessments
        |
        | Your create method creates assessments with Draft status.
        | We consider Draft / In Progress / Pending as pending.
        |--------------------------------------------------------------------------
        */

        $pendingAssessments = FeasibilityAssessment::query()
            ->whereIn(
                'status',
                [
                    'Draft',
                    'In Progress',
                    'Pending',
                ]
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Completed Assessments
        |--------------------------------------------------------------------------
        */

        $completedAssessments = FeasibilityAssessment::query()
            ->where(
                'status',
                'Completed'
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Investment Counts
        |--------------------------------------------------------------------------
        */

        $totalInvestmentAnalyses = InvestmentAnalysis::query()
            ->count();


        $totalInvestmentDecisions = InvestmentDecision::query()
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Assessment Status Summary
        |
        | We don't hard-code all possible statuses.
        | This keeps the dashboard compatible with the statuses
        | already stored in your database.
        |--------------------------------------------------------------------------
        */

        $assessmentStatusSummary = FeasibilityAssessment::query()
            ->selectRaw(
                'status, COUNT(*) as total'
            )
            ->groupBy('status')
            ->orderByDesc('total')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Acquisition Statuses
        |--------------------------------------------------------------------------
        */

        $statuses = Land::query()
            ->whereNotNull('acquisition_status')
            ->where(
                'acquisition_status',
                '!=',
                ''
            )
            ->distinct()
            ->orderBy('acquisition_status')
            ->pluck('acquisition_status');


        /*
        |--------------------------------------------------------------------------
        | Registered Lands
        |--------------------------------------------------------------------------
        */

        $landsQuery = Land::query()
            ->withCount(
                'feasibilityAssessments'
            )
            ->with([
                'feasibilityAssessments' => function ($query) {

                    $query
                        ->latest('id')
                        ->select([
                            'id',
                            'land_id',
                            'assessment_number',
                            'title',
                            'status',
                            'assessment_date',
                            'target_completion_date',
                        ]);
                }
            ]);


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {

            $landsQuery->where(function ($query) use ($search) {

                $query
                    ->where(
                        'land_code',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'land_name',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'city',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'state',
                        'like',
                        '%' . $search . '%'
                    );
            });
        }


        /*
        |--------------------------------------------------------------------------
        | Acquisition Status Filter
        |--------------------------------------------------------------------------
        */

        if (
            $status !== null &&
            $status !== ''
        ) {

            $landsQuery->where(
                'acquisition_status',
                $status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Paginated Lands
        |--------------------------------------------------------------------------
        */

        $lands = $landsQuery
            ->latest('id')
            ->paginate(15)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Recent Feasibility Assessments
        |--------------------------------------------------------------------------
        */

        $recentAssessments = FeasibilityAssessment::query()
            ->with('land')
            ->latest('id')
            ->limit(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Lands Requiring Attention
        |--------------------------------------------------------------------------
        |
        | We calculate this from the existing feasibility data.
        |
        | Priority:
        |
        | 1. No feasibility registered
        | 2. Latest feasibility is Draft
        | 3. Latest feasibility is In Progress
        | 4. Latest feasibility is Pending
        |--------------------------------------------------------------------------
        */

        $attentionLands = Land::query()
            ->with([
                'feasibilityAssessments' => function ($query) {

                    $query
                        ->latest('id')
                        ->limit(1);
                }
            ])
            ->withCount(
                'feasibilityAssessments'
            )
            ->latest('id')
            ->get()
            ->filter(function ($land) {

                if (
                    $land->feasibility_assessments_count === 0
                ) {
                    return true;
                }

                $latest =
                    $land->feasibilityAssessments->first();

                if (!$latest) {
                    return true;
                }

                return in_array(
                    $latest->status,
                    [
                        'Draft',
                        'In Progress',
                        'Pending',
                    ],
                    true
                );
            })
            ->take(5);


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'feasibility.index',
            compact(
                'lands',
                'recentAssessments',
                'attentionLands',
                'totalLands',
                'totalFeasibilities',
                'pendingAssessments',
                'completedAssessments',
                'totalInvestmentAnalyses',
                'totalInvestmentDecisions',
                'assessmentStatusSummary',
                'search',
                'status',
                'statuses'
            )
        );
    }
}