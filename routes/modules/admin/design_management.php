<?php

use App\Http\Controllers\Admin\DesignManagement\DesignApprovalController;
use App\Http\Controllers\Admin\DesignManagement\DesignChangeController;
use App\Http\Controllers\Admin\DesignManagement\DesignCommentController;
use App\Http\Controllers\Admin\DesignManagement\DesignConsultantController;
use App\Http\Controllers\Admin\DesignManagement\DesignDashboardController;
use App\Http\Controllers\Admin\DesignManagement\DesignDrawingController;
use App\Http\Controllers\Admin\DesignManagement\DesignManagementController;
use App\Http\Controllers\Admin\DesignManagement\DesignPackageController;
use App\Http\Controllers\Admin\DesignManagement\DesignProjectBriefController;
use App\Http\Controllers\Admin\DesignManagement\DesignReviewController;
use App\Http\Controllers\Admin\DesignManagement\DesignRfiController;
use App\Http\Controllers\Admin\DesignManagement\DesignSubmittalController;
use App\Support\RouteHelper as R;
use Illuminate\Support\Facades\Route;

Route::get('design-management', [DesignManagementController::class, 'index'])->name('design-management.index');

$base = 'projects/{project}/design-management';
$name = 'projects.design-management.';

Route::get($base, [DesignDashboardController::class, 'index'])->name("{$name}dashboard");

$review   = ['submit', 'approve', 'reject'];
$revision = ['submit', 'approve', 'reject', 'revision'];

$registers = [
    // slug          controller                              param        actions
    'briefs'      => [DesignProjectBriefController::class, 'brief',      $revision],
    'consultants' => [DesignConsultantController::class,   'consultant', []],
    'packages'    => [DesignPackageController::class,      'package',    $revision],
    'drawings'    => [DesignDrawingController::class,      'drawing',    $revision],
    'submittals'  => [DesignSubmittalController::class,    'submittal',  $revision],
    'reviews'     => [DesignReviewController::class,       'review',     $review],
    'comments'    => [DesignCommentController::class,      'comment',    $review],
    'rfis'        => [DesignRfiController::class,          'rfi',        $review],
    'changes'     => [DesignChangeController::class,       'change',     $revision],
];

foreach ($registers as $slug => [$controller, $param, $actions]) {
    R::crud("$base/$slug", "$name$slug.", $controller, $param, actions: $actions);
}

/* Change cost impacts */
Route::post("$base/changes/{change}/cost-impacts", [DesignChangeController::class, 'storeCostImpact'])
    ->name("{$name}changes.cost-impacts.store");
Route::delete("$base/changes/{change}/cost-impacts/{costImpact}", [DesignChangeController::class, 'destroyCostImpact'])
    ->name("{$name}changes.cost-impacts.destroy");

Route::get("$base/approvals", [DesignApprovalController::class, 'index'])->name("{$name}approvals.index");
