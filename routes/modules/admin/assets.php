<?php

use App\Http\Controllers\Admin\Assets\Areas\AreaController;
use App\Http\Controllers\Admin\Assets\Areas\AreaReportController;
use App\Http\Controllers\Admin\Assets\Areas\AreaRevisionController;
use App\Http\Controllers\Admin\Assets\Areas\AreaTypeController;
use App\Http\Controllers\Admin\Assets\AssetCapexController;
use App\Http\Controllers\Admin\Assets\AssetCategoryController;
use App\Http\Controllers\Admin\Assets\AssetController;
use App\Http\Controllers\Admin\Assets\AssetExpenseController;
use App\Http\Controllers\Admin\Assets\AssetIncomeController;
use App\Http\Controllers\Admin\Assets\AssetPerformanceController;
use App\Http\Controllers\Admin\Assets\BuildingController;
use App\Http\Controllers\Admin\Assets\Classification\AssetPurposeController;
use App\Http\Controllers\Admin\Assets\Classification\AssetTypeController;
use App\Http\Controllers\Admin\Assets\Classification\IndustryProfileController;
use App\Http\Controllers\Admin\Assets\Classification\OperatingModelController;
use App\Http\Controllers\Admin\Assets\Classification\OwnershipController;
use App\Http\Controllers\Admin\Assets\Classification\RevenueStatusController;
use App\Http\Controllers\Admin\Assets\DepartmentController;
use App\Http\Controllers\Admin\Assets\EconomicDashboardController;
use App\Http\Controllers\Admin\Assets\FloorController;
use App\Http\Controllers\Admin\Assets\MallController;
use App\Http\Controllers\Admin\Assets\Operations\AssetConditionController;
use App\Http\Controllers\Admin\Assets\Operations\AssetLifecycleController;
use App\Http\Controllers\Admin\Assets\Operations\AssetPerformanceController as OperationsAssetPerformanceController;
use App\Http\Controllers\Admin\Assets\Operations\IndustryOperatingProfileController;
use App\Http\Controllers\Admin\Assets\Operations\OccupancyController;
use App\Http\Controllers\Admin\Assets\Reports\AssetKpiController;
use App\Http\Controllers\Admin\Assets\Reports\IndustryKpiController;
use App\Http\Controllers\Admin\Assets\UnitController;
use App\Http\Controllers\Admin\Assets\UnitDocumentController;
use App\Http\Controllers\Admin\Assets\UnitStatusController;
use App\Http\Controllers\Admin\Assets\UnitTypeController;
use App\Http\Controllers\Admin\Assets\ZoneController;
use App\Support\RouteHelper as R;
use Illuminate\Support\Facades\Route;

Route::prefix('assets')->name('assets.')->group(function () {

    /* Permission-protected resources: uri => [controller, permission prefix] */
    $resources = [
        'malls'             => [MallController::class, 'malls'],
        'buildings'         => [BuildingController::class, 'buildings'],
        'floors'            => [FloorController::class, 'floors'],
        'zones'             => [ZoneController::class, 'zones'],
        'unit-types'        => [UnitTypeController::class, 'unit_types'],
        'units'             => [UnitController::class, 'units'],
        'departments'       => [DepartmentController::class, 'departments'],
        'unit-statuses'     => [UnitStatusController::class, 'unit_statuses'],
        'assets'            => [AssetController::class, 'assets'],
        'asset-categories'  => [AssetCategoryController::class, 'asset_categories'],
        'unit-documents'    => [UnitDocumentController::class, 'unit_documents'],
        'purposes'          => [AssetPurposeController::class, 'purposes'],
        'industry-profiles' => [IndustryProfileController::class, 'industry_profiles'],
        'operating-models'  => [OperatingModelController::class, 'operating_models'],
        'ownerships'        => [OwnershipController::class, 'ownerships'],
        'revenue-statuses'  => [RevenueStatusController::class, 'revenue_statuses'],
        'types'             => [AssetTypeController::class, 'types'],
        'capex'             => [AssetCapexController::class, 'capex'],
    ];
    foreach ($resources as $uri => [$controller, $perm]) {
        R::permissions(Route::resource($uri, $controller), $perm);
    }

    /* Operations */
    Route::prefix('operations')->name('operations.')->group(function () {
        $ops = [
            'occupancy'         => [OccupancyController::class, 'occupancy'],
            'conditions'        => [AssetConditionController::class, 'conditions'],
            'performance'       => [OperationsAssetPerformanceController::class, 'performance'],
            'lifecycle'         => [AssetLifecycleController::class, 'lifecycle'],
            'industry-profiles' => [IndustryOperatingProfileController::class, 'industry_profiles'],
        ];
        foreach ($ops as $uri => [$controller, $perm]) {
            R::permissions(Route::resource($uri, $controller), $perm);
        }
    });

    /* Expenses & Incomes (scoped by asset) */
    foreach ([
        ['expenses', AssetExpenseController::class, 'expense'],
        ['incomes', AssetIncomeController::class, 'income'],
    ] as [$p, $c, $m]) {
        $item = '{' . $m . '}';
        Route::get("$p/{asset}", [$c, 'index'])->middleware("permission:$p.view")->name("$p.index");
        Route::get("$p/{asset}/create", [$c, 'create'])->middleware("permission:$p.create")->name("$p.create");
        Route::post("$p/{asset}", [$c, 'store'])->middleware("permission:$p.create")->name("$p.store");
        Route::get("$p/{asset}/$item", [$c, 'show'])->middleware("permission:$p.view")->name("$p.show");
        Route::get("$p/{asset}/$item/edit", [$c, 'edit'])->middleware("permission:$p.edit")->name("$p.edit");
        Route::put("$p/{asset}/$item", [$c, 'update'])->middleware("permission:$p.edit")->name("$p.update");
        Route::patch("$p/{asset}/$item", [$c, 'update'])->middleware("permission:$p.edit")->name("$p.update.patch");
        Route::delete("$p/{asset}/$item", [$c, 'destroy'])->middleware("permission:$p.delete")->name("$p.destroy");
    }

    /* Economic dashboard & performance */
    Route::get('/economic-dashboard', [EconomicDashboardController::class, 'index'])
        ->middleware('permission:economic_dashboard.view')->name('economic-dashboard');
    Route::get('performance', [AssetPerformanceController::class, 'index'])
        ->middleware('permission:performance.view')->name('performance.index');
    Route::get('assets/{asset}/performance', [AssetPerformanceController::class, 'show'])
        ->middleware('permission:performance.view')->name('performance.show');

    /* KPI reports */
    Route::prefix('reports')->name('reports.')->middleware('permission:asset_reports.view')->group(function () {
        Route::get('core-kpis', [AssetKpiController::class, 'index'])->name('core-kpis.index');
        foreach (['assets', 'occupancy', 'condition', 'performance', 'financial', 'lifecycle'] as $m) {
            Route::get("core-kpis/$m", [AssetKpiController::class, $m])->name("core-kpis.$m");
        }
        Route::get('industry-kpis', [IndustryKpiController::class, 'index'])->name('industry-kpis.index');
        Route::get('industry-kpis/{industry_profile}', [IndustryKpiController::class, 'show'])->name('industry-kpis.show');
    });

    /* Property Areas */
    R::crud('areas', 'areas.', AreaController::class, 'area', where: R::num('area'));

    Route::prefix('reports')->name('reports.')->controller(AreaReportController::class)->group(function () {
        Route::get('/gla', 'gla')->name('gla');
        Route::get('/nla', 'nla')->name('nla');
        Route::get('/occupancy', 'occupancy')->name('occupancy');
        Route::get('/summary', 'summary')->name('summary');
    });

    R::crud('types', 'types.', AreaTypeController::class, 'areaType', only: R::NO_SHOW, where: R::num('areaType'));

    /* Area measurements */
    Route::prefix('measurements')->name('measurements.')->controller(AreaController::class)->group(function () {
        Route::get('/', 'measurements')->name('index');
        Route::get('/create', 'createMeasurement')->name('create');
        Route::post('/', 'storeMeasurement')->name('store');
        Route::get('/{measurement}/edit', 'editMeasurement')->whereNumber('measurement')->name('edit');
        Route::put('/{measurement}', 'updateMeasurement')->whereNumber('measurement')->name('update');
        Route::delete('/{measurement}', 'destroyMeasurement')->whereNumber('measurement')->name('destroy');
    });

    /* Area approvals */
    Route::prefix('approvals')->name('approvals.')->controller(AreaRevisionController::class)->group(function () {
        Route::get('/', 'approvals')->name('index');
        Route::get('/{revision}', 'approvalShow')->whereNumber('revision')->name('show');
        Route::post('/{revision}/approve', 'approve')->whereNumber('revision')->name('approve');
        Route::post('/{revision}/reject', 'reject')->whereNumber('revision')->name('reject');
    });

    /* Area revisions (route names kept exactly as before, incl. 'revisions.revisions.*') */
    R::crud(
        'area/{area}/revisions', 'revisions.', AreaRevisionController::class, 'revision',
        actions: ['submit'],
        where: R::num('area', 'revision'),
        before: function () {
            Route::post('/{revision}/approve', [AreaRevisionController::class, 'approve'])->name('revisions.approve');
            Route::post('/{revision}/reject', [AreaRevisionController::class, 'reject'])->name('revisions.reject');
        }
    );
});
