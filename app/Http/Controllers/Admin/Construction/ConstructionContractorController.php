<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProcurementContract;
use Illuminate\View\View;

class ConstructionContractorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Contractor Register
    |--------------------------------------------------------------------------
    */

    public function index(Project $project): View
    {
        /*
        |--------------------------------------------------------------------------
        | Contracts belonging to this project
        |--------------------------------------------------------------------------
        */

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
            ])
            ->orderBy(
                'contract_number'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Contractor Register
        |
        | One contractor may have multiple contracts.
        | Group contracts by bidder.
        |--------------------------------------------------------------------------
        */

        $contractors = $contracts
            ->groupBy(
                function ($contract) {

                    return $contract->procurement_bidder_id
                        ?: 'name:' . strtolower(
                            trim(
                                (string)
                                $contract->bidder_name
                            )
                        );
                }
            )
            ->map(
                function ($contracts) {

                    $firstContract =
                        $contracts->first();

                    return [

                        'bidder' =>
                            $firstContract->bidder,

                        'bidder_name' =>
                            $firstContract->bidder_name
                            ??
                            $firstContract
                                ->bidder
                                ?->company_name
                            ??
                            '—',

                        'bidder_code' =>
                            $firstContract
                                ->bidder
                                ?->bidder_code
                            ??
                            '—',

                        'contact_person' =>
                            $firstContract
                                ->bidder
                                ?->contact_person
                            ??
                            '—',

                        'email' =>
                            $firstContract
                                ->bidder
                                ?->email
                            ??
                            '—',

                        'phone' =>
                            $firstContract
                                ->bidder
                                ?->phone
                            ??
                            '—',

                        'city' =>
                            $firstContract
                                ->bidder
                                ?->city
                            ??
                            '—',

                        'state' =>
                            $firstContract
                                ->bidder
                                ?->state
                            ??
                            '—',

                        'contracts' =>
                            $contracts,

                        'contract_count' =>
                            $contracts->count(),

                        'total_contract_value' =>
                            (float)
                            $contracts->sum(
                                'contract_amount'
                            ),

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
                    ];
                }
            )
            ->values();


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $summary = [

            'total_contractors' =>
                $contractors->count(),

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

            'total_contract_value' =>
                (float)
                $contracts->sum(
                    'contract_amount'
                ),
        ];


        return view(
            'construction.contractors.index',
            compact(
                'project',
                'contractors',
                'summary'
            )
        );
    }
}