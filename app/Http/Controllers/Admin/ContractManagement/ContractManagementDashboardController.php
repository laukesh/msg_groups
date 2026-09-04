<?php

namespace App\Http\Controllers\Admin\ContractManagement;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ContractManagementContract;
use Illuminate\View\View;

class ContractManagementDashboardController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Contract Management Dashboard
    |--------------------------------------------------------------------------
    */

    public function index(): View
    {
        /*
        |--------------------------------------------------------------------------
        | Projects
        |--------------------------------------------------------------------------
        |
        | Contract Management is platform-level, but contracts remain
        | project-specific.
        |
        */

        $projects = Project::query()
            ->orderBy('project_name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Overall Contract Summary
        |--------------------------------------------------------------------------
        */

        $contracts = ContractManagementContract::query()
            ->get();


        $summary = [

            'total_contracts' =>
                $contracts->count(),

            'active_contracts' =>
                $contracts
                    ->whereIn(
                        'status',
                        [
                            'Active',
                            'Approved',
                            'In Progress',
                        ]
                    )
                    ->count(),

            'completed_contracts' =>
                $contracts
                    ->whereIn(
                        'status',
                        [
                            'Completed',
                            'Closed',
                        ]
                    )
                    ->count(),

            'draft_contracts' =>
                $contracts
                    ->where(
                        'status',
                        'Draft'
                    )
                    ->count(),

            'total_value' =>
                (float)
                $contracts->sum(
                    'contract_value'
                ),

        ];


        /*
        |--------------------------------------------------------------------------
        | Project-wise Contract Summary
        |--------------------------------------------------------------------------
        */

        $projectSummary =
            $contracts
                ->groupBy('project_id')
                ->map(
                    function ($projectContracts) {

                        return [

                            'contract_count' =>
                                $projectContracts->count(),

                            'active_count' =>
                                $projectContracts
                                    ->whereIn(
                                        'status',
                                        [
                                            'Active',
                                            'Approved',
                                            'In Progress',
                                        ]
                                    )
                                    ->count(),

                            'completed_count' =>
                                $projectContracts
                                    ->whereIn(
                                        'status',
                                        [
                                            'Completed',
                                            'Closed',
                                        ]
                                    )
                                    ->count(),

                            'contract_value' =>
                                (float)
                                $projectContracts->sum(
                                    'contract_value'
                                ),

                        ];
                    }
                );


        return view(
            'contract-management.dashboard.index',
            compact(
                'projects',
                'summary',
                'projectSummary'
            )
        );
    }
}