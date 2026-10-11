<?php

use App\Http\Controllers\Admin\DevelopmentManagementController;
use App\Http\Controllers\Admin\PortfolioInvestmentController;
use App\Http\Controllers\Admin\Project\DevelopmentBriefController;
use App\Support\RouteHelper as R;
use Illuminate\Support\Facades\Route;

/* Portfolio investment: uri => controller method */
$portfolio = [
    'overview'              => 'portfolioOverview',
    'acquisition-pipeline'  => 'acquisitionPipeline',
    'development-appraisal' => 'developmentAppraisal',
    'scenario-sensitivity'  => 'scenarioSensitivity',
    'investment-committee'  => 'investmentCommittee',
    'capital-planning'      => 'capitalPlanning',
    'valuation'             => 'valuation',
    'asset-strategy'        => 'assetStrategy',
    'performance-monitoring' => 'performanceMonitoring',
    'due-diligence'         => 'dueDiligence',
    'feasibility-studies'   => 'feasibilityStudies',
    'acquisition-disposal'  => 'acquisitionDisposal',
    'investment-reports'    => 'investmentReports',
    'ownership-use-strategy' => 'ownershipUseStrategy',
];
foreach ($portfolio as $uri => $method) {
    Route::get("portfolio-investment/$uri", [PortfolioInvestmentController::class, $method])
        ->name("portfolio-investment.$uri");
}

/* Development management */
Route::get('development/overview', [DevelopmentManagementController::class, 'overview'])->name('development.overview');

/* Development brief – project scoped */
R::crud('projects/{project}/development-brief', 'projects.development-brief.', DevelopmentBriefController::class, 'developmentBrief',
    before: function () {
        $c = DevelopmentBriefController::class;
        $b = '/{developmentBrief}';

        Route::get("$b/documents", [$c, 'documents'])->name('documents');
        Route::post("$b/documents", [$c, 'storeDocument'])->name('documents.store');
        Route::get("$b/documents/{document}/download", [$c, 'downloadDocument'])->name('documents.download');
        Route::delete("$b/documents/{document}", [$c, 'destroyDocument'])->name('documents.destroy');

        Route::get("$b/comments", [$c, 'comments'])->name('comments');
        Route::post("$b/comments", [$c, 'storeComment'])->name('comments.store');
        Route::patch("$b/comments/{comment}/status", [$c, 'updateCommentStatus'])->name('comments.status');
        Route::delete("$b/comments/{comment}", [$c, 'destroyComment'])->name('comments.destroy');

        Route::post("$b/workflow/{action}", [$c, 'workflowAction'])->name('workflow.action');

        Route::get("$b/versions", [$c, 'versions'])->name('versions');
        Route::post("$b/versions", [$c, 'storeVersion'])->name('versions.store');
        Route::get("$b/versions/{version}", [$c, 'showVersion'])->name('versions.show');
        Route::get("$b/workflow-history", [$c, 'workflowHistory'])->name('workflow-history');
    });

/* Development brief – global (controller methods are globalIndex, globalCreate, ...) */
R::crud('development-briefs', 'development-briefs.', DevelopmentBriefController::class, 'developmentBrief',
    methodPrefix: 'global');
