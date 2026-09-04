<?php

namespace App\Http\Controllers\Admin\Feasibility;

use App\Http\Controllers\Controller;
use App\Models\FinancialFeasibility;
use App\Models\FeasibilityAssessment;
use App\Models\Land;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FinancialFeasibilityController extends Controller
{
    public function index(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment
    ) {
        $financialFeasibilities = $feasibilityAssessment
            ->financialFeasibilities()
            ->latest('id')
            ->paginate(15);

        return view(
            'feasibility.financial-feasibilities.index',
            compact(
                'land',
                'feasibilityAssessment',
                'financialFeasibilities'
            )
        );
    }


    public function create(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment
    ) {
        return view(
            'feasibility.financial-feasibilities.create',
            compact(
                'land',
                'feasibilityAssessment'
            )
        );
    }


    public function store(
        Request $request,
        Land $land,
        FeasibilityAssessment $feasibilityAssessment
    ) {
        $validated = $request->validate([

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            // Project Cost
            'land_cost' => ['nullable', 'numeric', 'min:0'],
            'construction_cost' => ['nullable', 'numeric', 'min:0'],
            'development_cost' => ['nullable', 'numeric', 'min:0'],
            'infrastructure_cost' => ['nullable', 'numeric', 'min:0'],
            'professional_fee' => ['nullable', 'numeric', 'min:0'],
            'approval_cost' => ['nullable', 'numeric', 'min:0'],
            'marketing_cost' => ['nullable', 'numeric', 'min:0'],
            'financing_cost' => ['nullable', 'numeric', 'min:0'],
            'contingency_cost' => ['nullable', 'numeric', 'min:0'],
            'other_project_cost' => ['nullable', 'numeric', 'min:0'],

            // Revenue
            'sales_revenue' => ['nullable', 'numeric', 'min:0'],
            'rental_revenue' => ['nullable', 'numeric', 'min:0'],
            'other_revenue' => ['nullable', 'numeric', 'min:0'],

            // Operating Expenses
            'operating_expenses' => ['nullable', 'numeric', 'min:0'],
            'maintenance_cost' => ['nullable', 'numeric', 'min:0'],
            'administrative_cost' => ['nullable', 'numeric', 'min:0'],
            'other_operating_cost' => ['nullable', 'numeric', 'min:0'],

            // Financial Metrics
            'roi' => ['nullable', 'numeric'],
            'irr' => ['nullable', 'numeric'],
            'npv' => ['nullable', 'numeric'],
            'payback_period' => ['nullable', 'numeric', 'min:0'],
            'dscr' => ['nullable', 'numeric', 'min:0'],

            // Financing
            'equity_contribution' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'debt_financing' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'interest_rate' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'loan_tenure' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            // Analysis
            'financial_assumptions' => [
                'nullable',
                'string'
            ],

            'cash_flow_summary' => [
                'nullable',
                'string'
            ],

            'sensitivity_analysis' => [
                'nullable',
                'string'
            ],

            'key_financial_findings' => [
                'nullable',
                'string'
            ],

            'financial_risks' => [
                'nullable',
                'string'
            ],

            'recommendation' => [
                'nullable',
                'string'
            ],

            'overall_financial_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Project Cost Calculation
        |--------------------------------------------------------------------------
        */

        $projectCostFields = [

            'land_cost',
            'construction_cost',
            'development_cost',
            'infrastructure_cost',
            'professional_fee',
            'approval_cost',
            'marketing_cost',
            'financing_cost',
            'contingency_cost',
            'other_project_cost',

        ];


        $totalProjectCost = 0;


        foreach ($projectCostFields as $field) {

            $totalProjectCost +=
                (float) ($validated[$field] ?? 0);

        }


        $validated['total_project_cost'] =
            round(
                $totalProjectCost,
                2
            );


        /*
        |--------------------------------------------------------------------------
        | Revenue Calculation
        |--------------------------------------------------------------------------
        */

        $revenueFields = [

            'sales_revenue',
            'rental_revenue',
            'other_revenue',

        ];


        $totalRevenue = 0;


        foreach ($revenueFields as $field) {

            $totalRevenue +=
                (float) ($validated[$field] ?? 0);

        }


        $validated['total_revenue'] =
            round(
                $totalRevenue,
                2
            );


        /*
        |--------------------------------------------------------------------------
        | Operating Expenses
        |--------------------------------------------------------------------------
        */

        $expenseFields = [

            'operating_expenses',
            'maintenance_cost',
            'administrative_cost',
            'other_operating_cost',

        ];


        $totalOperatingExpenses = 0;


        foreach ($expenseFields as $field) {

            $totalOperatingExpenses +=
                (float) ($validated[$field] ?? 0);

        }


        /*
        |--------------------------------------------------------------------------
        | Net Operating Income
        |--------------------------------------------------------------------------
        */

        $validated['net_operating_income'] =
            round(
                $validated['total_revenue']
                -
                $totalOperatingExpenses,
                2
            );


        /*
        |--------------------------------------------------------------------------
        | Gross Profit
        |--------------------------------------------------------------------------
        */

        $validated['gross_profit'] =
            round(
                $validated['total_revenue']
                -
                $validated['total_project_cost'],
                2
            );


        /*
        |--------------------------------------------------------------------------
        | Net Profit
        |--------------------------------------------------------------------------
        */

        $validated['net_profit'] =
            round(
                $validated['gross_profit']
                -
                $totalOperatingExpenses,
                2
            );


        /*
        |--------------------------------------------------------------------------
        | Profit Margin
        |--------------------------------------------------------------------------
        */

        if ($validated['total_revenue'] > 0) {

            $validated['profit_margin'] =
                round(
                    (
                        $validated['net_profit']
                        /
                        $validated['total_revenue']
                    ) * 100,
                    2
                );

        } else {

            $validated['profit_margin'] = 0;

        }


        /*
        |--------------------------------------------------------------------------
        | Assessment
        |--------------------------------------------------------------------------
        */

        $validated['feasibility_assessment_id'] =
            $feasibilityAssessment->id;


        /*
        |--------------------------------------------------------------------------
        | Analysis Number
        |--------------------------------------------------------------------------
        */

        $validated['analysis_number'] =
            'FF-' .
            now()->format('YmdHis') .
            '-' .
            Str::upper(
                Str::random(4)
            );


        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        $validated['status'] = 'Draft';


        /*
        |--------------------------------------------------------------------------
        | Created By
        |--------------------------------------------------------------------------
        */

        $validated['created_by'] =
            auth()->id();


        /*
        |--------------------------------------------------------------------------
        | Create Record
        |--------------------------------------------------------------------------
        */

        $financialFeasibility =
            FinancialFeasibility::create(
                $validated
            );


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()->route(
            'admin.land.lands.feasibility-assessments.financial-feasibilities.show',
            [
                'land' =>
                    $land->id,

                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,

                'financialFeasibility' =>
                    $financialFeasibility->id,
            ]
        )->with(
            'success',
            'Financial feasibility created successfully.'
        );
    }


    public function show(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment,
        FinancialFeasibility $financialFeasibility
    ) {
        return view(
            'feasibility.financial-feasibilities.show',
            compact(
                'land',
                'feasibilityAssessment',
                'financialFeasibility'
            )
        );
    }


    public function edit(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment,
        FinancialFeasibility $financialFeasibility
    ) {
        return view(
            'feasibility.financial-feasibilities.edit',
            compact(
                'land',
                'feasibilityAssessment',
                'financialFeasibility'
            )
        );
    }


    public function update(
        Request $request,
        Land $land,
        FeasibilityAssessment $feasibilityAssessment,
        FinancialFeasibility $financialFeasibility
    ) {
        $validated = $request->validate([

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            // Project Cost
            'land_cost' => ['nullable', 'numeric', 'min:0'],
            'construction_cost' => ['nullable', 'numeric', 'min:0'],
            'development_cost' => ['nullable', 'numeric', 'min:0'],
            'infrastructure_cost' => ['nullable', 'numeric', 'min:0'],
            'professional_fee' => ['nullable', 'numeric', 'min:0'],
            'approval_cost' => ['nullable', 'numeric', 'min:0'],
            'marketing_cost' => ['nullable', 'numeric', 'min:0'],
            'financing_cost' => ['nullable', 'numeric', 'min:0'],
            'contingency_cost' => ['nullable', 'numeric', 'min:0'],
            'other_project_cost' => ['nullable', 'numeric', 'min:0'],

            // Revenue
            'sales_revenue' => ['nullable', 'numeric', 'min:0'],
            'rental_revenue' => ['nullable', 'numeric', 'min:0'],
            'other_revenue' => ['nullable', 'numeric', 'min:0'],

            // Operating Expenses
            'operating_expenses' => ['nullable', 'numeric', 'min:0'],
            'maintenance_cost' => ['nullable', 'numeric', 'min:0'],
            'administrative_cost' => ['nullable', 'numeric', 'min:0'],
            'other_operating_cost' => ['nullable', 'numeric', 'min:0'],

            // Financial Metrics
            'roi' => ['nullable', 'numeric'],
            'irr' => ['nullable', 'numeric'],
            'npv' => ['nullable', 'numeric'],
            'payback_period' => ['nullable', 'numeric', 'min:0'],
            'dscr' => ['nullable', 'numeric', 'min:0'],

            // Financing
            'equity_contribution' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'debt_financing' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'interest_rate' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'loan_tenure' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            // Analysis
            'financial_assumptions' => [
                'nullable',
                'string'
            ],

            'cash_flow_summary' => [
                'nullable',
                'string'
            ],

            'sensitivity_analysis' => [
                'nullable',
                'string'
            ],

            'key_financial_findings' => [
                'nullable',
                'string'
            ],

            'financial_risks' => [
                'nullable',
                'string'
            ],

            'recommendation' => [
                'nullable',
                'string'
            ],

            'overall_financial_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'status' => [
                'required',
                'string',
                'max:50',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Project Cost Calculation
        |--------------------------------------------------------------------------
        */

        $projectCostFields = [

            'land_cost',
            'construction_cost',
            'development_cost',
            'infrastructure_cost',
            'professional_fee',
            'approval_cost',
            'marketing_cost',
            'financing_cost',
            'contingency_cost',
            'other_project_cost',

        ];


        $totalProjectCost = 0;


        foreach ($projectCostFields as $field) {

            $totalProjectCost +=
                (float) ($validated[$field] ?? 0);

        }


        $validated['total_project_cost'] =
            round(
                $totalProjectCost,
                2
            );


        /*
        |--------------------------------------------------------------------------
        | Revenue Calculation
        |--------------------------------------------------------------------------
        */

        $revenueFields = [

            'sales_revenue',
            'rental_revenue',
            'other_revenue',

        ];


        $totalRevenue = 0;


        foreach ($revenueFields as $field) {

            $totalRevenue +=
                (float) ($validated[$field] ?? 0);

        }


        $validated['total_revenue'] =
            round(
                $totalRevenue,
                2
            );


        /*
        |--------------------------------------------------------------------------
        | Operating Expenses
        |--------------------------------------------------------------------------
        */

        $expenseFields = [

            'operating_expenses',
            'maintenance_cost',
            'administrative_cost',
            'other_operating_cost',

        ];


        $totalOperatingExpenses = 0;


        foreach ($expenseFields as $field) {

            $totalOperatingExpenses +=
                (float) ($validated[$field] ?? 0);

        }


        /*
        |--------------------------------------------------------------------------
        | Net Operating Income
        |--------------------------------------------------------------------------
        */

        $validated['net_operating_income'] =
            round(
                $validated['total_revenue']
                -
                $totalOperatingExpenses,
                2
            );


        /*
        |--------------------------------------------------------------------------
        | Gross Profit
        |--------------------------------------------------------------------------
        */

        $validated['gross_profit'] =
            round(
                $validated['total_revenue']
                -
                $validated['total_project_cost'],
                2
            );


        /*
        |--------------------------------------------------------------------------
        | Net Profit
        |--------------------------------------------------------------------------
        */

        $validated['net_profit'] =
            round(
                $validated['gross_profit']
                -
                $totalOperatingExpenses,
                2
            );


        /*
        |--------------------------------------------------------------------------
        | Profit Margin
        |--------------------------------------------------------------------------
        */

        if ($validated['total_revenue'] > 0) {

            $validated['profit_margin'] =
                round(
                    (
                        $validated['net_profit']
                        /
                        $validated['total_revenue']
                    ) * 100,
                    2
                );

        } else {

            $validated['profit_margin'] = 0;

        }


        /*
        |--------------------------------------------------------------------------
        | Updated By
        |--------------------------------------------------------------------------
        */

        $validated['updated_by'] =
            auth()->id();


        /*
        |--------------------------------------------------------------------------
        | Update Record
        |--------------------------------------------------------------------------
        */

        $financialFeasibility->update(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()->route(
            'admin.land.lands.feasibility-assessments.financial-feasibilities.show',
            [
                'land' =>
                    $land->id,

                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,

                'financialFeasibility' =>
                    $financialFeasibility->id,
            ]
        )->with(
            'success',
            'Financial feasibility updated successfully.'
        );
    }


    public function destroy(
        Land $land,
        FeasibilityAssessment $feasibilityAssessment,
        FinancialFeasibility $financialFeasibility
    ) {

        $financialFeasibility->delete();


        return redirect()->route(
            'admin.land.lands.feasibility-assessments.financial-feasibilities.index',
            [
                'land' => $land->id,

                'feasibilityAssessment' =>
                    $feasibilityAssessment->id,
            ]
        )->with(
            'success',
            'Financial feasibility deleted successfully.'
        );
    }

    private function validateBelongsToAssessment(
        FeasibilityAssessment $feasibilityAssessment,
        FinancialFeasibility $financialFeasibility
    ): void {
        abort_unless(
            (int) $financialFeasibility->feasibility_assessment_id ===
            (int) $feasibilityAssessment->id,
            404
        );
    }
}