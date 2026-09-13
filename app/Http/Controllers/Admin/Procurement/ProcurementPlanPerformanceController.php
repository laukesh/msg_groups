<?php

namespace App\Http\Controllers\Admin\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProcurementPlan;

class ProcurementPlanPerformanceController extends Controller
{
    public function index(Project $project)
    {
        $plans = ProcurementPlan::query()
            ->where('project_id', $project->id)
            ->withCount([
                'packages',
            ])
            ->with([
                'packages.tenders',
                'packages.tenders.awards',
                'packages.tenders.contracts',
            ])
            ->latest('id')
            ->get();

        $planData = $plans->map(function ($plan) {

            $packages = $plan->packages;

            $tenders = $packages->flatMap(
                fn ($package) => $package->tenders
            );

            $awards = $tenders->flatMap(
                fn ($tender) => $tender->awards
            );

            $contracts = $tenders->flatMap(
                fn ($tender) => $tender->contracts
            );

            $awardValue = $awards->sum(
                fn ($award) => (float) $award->awarded_amount
            );

            $contractValue = $contracts->sum(
                fn ($contract) => (float) $contract->contract_amount
            );

            return [
                'plan' => $plan,

                'package_count' => $packages->count(),

                'tender_count' => $tenders->count(),

                'award_count' => $awards->count(),

                'contract_count' => $contracts->count(),

                'estimated_value' =>
                    (float) $plan->total_estimated_value,

                'award_value' =>
                    $awardValue,

                'contract_value' =>
                    $contractValue,
            ];
        });

        return view(
            'procurement.performance.plans.index',
            compact(
                'project',
                'planData'
            )
        );
    }
}