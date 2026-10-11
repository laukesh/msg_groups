<?php

use App\Http\Controllers\Admin\Feasibility\DemandSupplyAnalysisController;
use App\Http\Controllers\Admin\Feasibility\EnvironmentalFeasibilityController;
use App\Http\Controllers\Admin\Feasibility\FeasibilityAssessmentController;
use App\Http\Controllers\Admin\Feasibility\FeasibilityInvestmentController;
use App\Http\Controllers\Admin\Feasibility\FinancialFeasibilityController;
use App\Http\Controllers\Admin\Feasibility\InvestmentAnalysisController;
use App\Http\Controllers\Admin\Feasibility\InvestmentDecisionController;
use App\Http\Controllers\Admin\Feasibility\LegalRegulatoryFeasibilityController;
use App\Http\Controllers\Admin\Feasibility\LocationAnalysisController;
use App\Http\Controllers\Admin\Feasibility\MarketStudyController;
use App\Http\Controllers\Admin\Feasibility\RiskAssessmentController;
use App\Http\Controllers\Admin\Feasibility\TechnicalFeasibilityController;
use App\Http\Controllers\Admin\Land\LandAcquisitionCostController;
use App\Http\Controllers\Admin\Land\LandController;
use App\Http\Controllers\Admin\Land\LandDevelopmentRightController;
use App\Http\Controllers\Admin\Land\LandDocumentController;
use App\Http\Controllers\Admin\Land\LandEnvironmentalAssessmentController;
use App\Http\Controllers\Admin\Land\LandLegalDueDiligenceController;
use App\Http\Controllers\Admin\Land\LandOpportunityController;
use App\Http\Controllers\Admin\Land\LandOwnerController;
use App\Http\Controllers\Admin\Land\LandPlotController;
use App\Http\Controllers\Admin\Land\LandTechnicalDueDiligenceController;
use App\Http\Controllers\Admin\Land\LandZoningController;
use Illuminate\Support\Facades\Route;

Route::prefix('land')->name('land.')->group(function () {

    Route::resource('lands', LandController::class);
    Route::resource('opportunities', LandOpportunityController::class);

    /* Land children */
    Route::resource('lands.owners', LandOwnerController::class);
    Route::resource('lands.plots', LandPlotController::class);
    Route::resource('lands.zonings', LandZoningController::class);
    Route::resource('lands.development-rights', LandDevelopmentRightController::class);
    Route::resource('lands.acquisition-costs', LandAcquisitionCostController::class);
    Route::resource('lands.legal-due-diligences', LandLegalDueDiligenceController::class);

    Route::resource('lands.technical-due-diligences', LandTechnicalDueDiligenceController::class)
        ->parameters(['technical-due-diligences' => 'dueDiligence']);

    Route::resource('lands.environmental-assessments', LandEnvironmentalAssessmentController::class)
        ->parameters(['environmental-assessments' => 'dueDiligence']);

    Route::resource('lands.documents', LandDocumentController::class)
        ->parameters(['documents' => 'document'])
        ->only(['index', 'create', 'store', 'show', 'destroy']);

    Route::get('lands/{land}/documents/{document}/download', [LandDocumentController::class, 'download'])
        ->name('lands.documents.download');
    Route::get('lands/{land}/acquisition-review', [LandController::class, 'acquisitionReview'])
        ->name('lands.acquisition-review');

    /* Feasibility */
    Route::resource('lands.feasibility-assessments', FeasibilityAssessmentController::class)
        ->parameters(['feasibility-assessments' => 'feasibilityAssessment']);

    Route::scopeBindings()->group(function () {
        $children = [
            'market-studies'                 => [MarketStudyController::class, 'marketStudy'],
            'location-analyses'              => [LocationAnalysisController::class, 'locationAnalysis'],
            'demand-supply-analyses'         => [DemandSupplyAnalysisController::class, 'demandSupplyAnalysis'],
            'financial-feasibilities'        => [FinancialFeasibilityController::class, 'financialFeasibility'],
            'legal-regulatory-feasibilities' => [LegalRegulatoryFeasibilityController::class, 'legalRegulatoryFeasibility'],
            'technical-feasibilities'        => [TechnicalFeasibilityController::class, 'technicalFeasibility'],
            'environmental-feasibilities'    => [EnvironmentalFeasibilityController::class, 'environmentalFeasibility'],
            'risk-assessments'               => [RiskAssessmentController::class, 'riskAssessment'],
            'investment-analyses'            => [InvestmentAnalysisController::class, 'investmentAnalysis'],
            'investment-decisions'           => [InvestmentDecisionController::class, 'investmentDecision'],
        ];

        foreach ($children as $slug => [$controller, $param]) {
            Route::resource("lands.feasibility-assessments.$slug", $controller)->parameters([
                'lands'                   => 'land',
                'feasibility-assessments' => 'feasibilityAssessment',
                $slug                     => $param,
            ]);
        }
    });
});

Route::get('feasibility-investment', [FeasibilityInvestmentController::class, 'index'])
    ->name('feasibility-investment.index');
