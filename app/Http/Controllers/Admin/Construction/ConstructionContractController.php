<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProcurementContract;
use Illuminate\View\View;

class ConstructionContractController extends Controller
{
    public function index(Project $project): View
    {
        $contracts = ProcurementContract::query()
            ->whereHas(
                'tender',
                function ($query) use ($project) {

                    $query->whereHas(
                        'package',
                        function ($query) use ($project) {

                            $query->whereHas(
                                'procurementPlan',
                                function ($query) use ($project) {

                                    $query->where(
                                        'project_id',
                                        $project->id
                                    );
                                }
                            );
                        }
                    );
                }
            )
            ->with([
                'bidder',
                'tender.package.procurementPlan',
                'payments',
                'invoices',
            ])
            ->orderByDesc('contract_start_date')
            ->get();


        $summary = [

            'total_contracts' =>
                $contracts->count(),

            'active_contracts' =>
                $contracts->filter(
                    function ($contract) {

                        return in_array(
                            $contract->status,
                            [
                                'Active',
                                'Approved',
                                'In Progress',
                            ],
                            true
                        );
                    }
                )->count(),

            'completed_contracts' =>
                $contracts->filter(
                    function ($contract) {

                        return in_array(
                            $contract->status,
                            [
                                'Completed',
                                'Closed',
                            ],
                            true
                        );
                    }
                )->count(),

            'total_value' =>
                (float) $contracts->sum(
                    'contract_amount'
                ),
        ];


        return view(
            'construction.contracts.index',
            compact(
                'project',
                'contracts',
                'summary'
            )
        );
    }
}