<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\Assets\MallController;
use App\Http\Controllers\Admin\Assets\BuildingController;
use App\Http\Controllers\Admin\Assets\FloorController;
use App\Http\Controllers\Admin\Assets\ZoneController;
use App\Http\Controllers\Admin\Assets\UnitTypeController;
use App\Http\Controllers\Admin\Assets\AssetController;
use App\Http\Controllers\Admin\Assets\AssetCategoryController;
use App\Http\Controllers\Admin\Assets\UnitDocumentController;
use App\Http\Controllers\Admin\Assets\UnitStatusController;
use App\Http\Controllers\Admin\Assets\DepartmentController;
use App\Http\Controllers\Admin\Assets\AssetExpenseController;
use App\Http\Controllers\Admin\Assets\AssetIncomeController;
use App\Http\Controllers\Admin\Assets\EconomicDashboardController;
use App\Http\Controllers\Admin\Assets\AssetPerformanceController;



use App\Http\Controllers\Admin\Leasing\LeasingController;
use App\Http\Controllers\Admin\Leasing\LeaseProposalController;
use App\Http\Controllers\Admin\Leasing\LeaseAgreementController;
use App\Http\Controllers\Admin\Leasing\LeaseTermController;
use App\Http\Controllers\Admin\Leasing\LeaseDocumentController;
use App\Http\Controllers\Admin\Leasing\LeaseRenewalController;
use App\Http\Controllers\Admin\Leasing\LeaseEscalationController;
use App\Http\Controllers\Admin\Leasing\LeaseHistoryController;
use App\Http\Controllers\Admin\Leasing\LeaseTerminationController;
use App\Http\Controllers\Admin\Leasing\LeaseDashboardController;


use App\Http\Controllers\Admin\Tenant\TenantDashboardController;
use App\Http\Controllers\Admin\Tenant\TenantController;
use App\Http\Controllers\Admin\Tenant\TenantContactController;
use App\Http\Controllers\Admin\Tenant\TenantAddressController;
use App\Http\Controllers\Admin\Tenant\TenantBankAccountController;
use App\Http\Controllers\Admin\Tenant\TenantDocumentController;
use App\Http\Controllers\Admin\Tenant\TenantEmergencyContactController;
use App\Http\Controllers\Admin\Tenant\TenantNoteController;
use App\Http\Controllers\Admin\Tenant\TenantHistoryController;

use App\Http\Controllers\Admin\Revenue\TaxConfigurationController;
use App\Http\Controllers\Admin\Revenue\DepositController;
use App\Http\Controllers\Admin\Revenue\DepositReceiptController;
use App\Http\Controllers\Admin\Revenue\DepositRefundController;

use App\Http\Controllers\Admin\Revenue\RentScheduleController;
use App\Http\Controllers\Admin\Revenue\InvoiceController;
use App\Http\Controllers\Admin\Revenue\RentPaymentController;
use App\Http\Controllers\Admin\Revenue\RevenueDashboardController;
use App\Http\Controllers\Admin\Revenue\OutstandingController;
use App\Http\Controllers\Admin\Revenue\RevenueReportController;
use App\Http\Controllers\Admin\Revenue\ChargeTypeController;
use App\Http\Controllers\Admin\Revenue\ReconciliationController;
use App\Http\Controllers\Admin\Revenue\RevenueAuditLogController;


use App\Http\Controllers\Admin\Fitout\FitoutRequestController;
use App\Http\Controllers\Admin\Fitout\ContractorController;
use App\Http\Controllers\Admin\Fitout\FitoutStageController;
use App\Http\Controllers\Admin\Fitout\FitoutDocumentController;
use App\Http\Controllers\Admin\Fitout\FitoutApprovalController;
use App\Http\Controllers\Admin\Fitout\FitoutInspectionController;
use App\Http\Controllers\Admin\Fitout\SnagListController;
use App\Http\Controllers\Admin\Fitout\HandoverController;
use App\Http\Controllers\Admin\Fitout\FitoutDashboardController;

/*Land */

use App\Http\Controllers\Admin\Land\LandController;
use App\Http\Controllers\Admin\Land\LandOpportunityController;
use App\Http\Controllers\Admin\Land\LandOwnerController;
use App\Http\Controllers\Admin\Land\LandPlotController;
use App\Http\Controllers\Admin\Land\LandZoningController;
use App\Http\Controllers\Admin\Land\LandDevelopmentRightController;
use App\Http\Controllers\Admin\Land\LandAcquisitionCostController;
use App\Http\Controllers\Admin\Land\LandLegalDueDiligenceController;
use App\Http\Controllers\Admin\Land\LandTechnicalDueDiligenceController;
use App\Http\Controllers\Admin\Land\LandEnvironmentalAssessmentController;
use App\Http\Controllers\Admin\Land\LandDocumentController;

use App\Http\Controllers\Admin\Feasibility\FeasibilityInvestmentController;
use App\Http\Controllers\Admin\Feasibility\FeasibilityAssessmentController;
use App\Http\Controllers\Admin\Feasibility\MarketStudyController;
use App\Http\Controllers\Admin\Feasibility\LocationAnalysisController;
use App\Http\Controllers\Admin\Feasibility\DemandSupplyAnalysisController;
use App\Http\Controllers\Admin\Feasibility\FinancialFeasibilityController;
use App\Http\Controllers\Admin\Feasibility\LegalRegulatoryFeasibilityController;
use App\Http\Controllers\Admin\Feasibility\TechnicalFeasibilityController;
use App\Http\Controllers\Admin\Feasibility\EnvironmentalFeasibilityController;
use App\Http\Controllers\Admin\Feasibility\RiskAssessmentController;
use App\Http\Controllers\Admin\Feasibility\InvestmentAnalysisController;
use App\Http\Controllers\Admin\Feasibility\InvestmentDecisionController;

use App\Http\Controllers\Admin\Project\ProjectController;
use App\Http\Controllers\Admin\Project\ProjectDashboardController;
use App\Http\Controllers\Admin\Project\DevelopmentStrategyController;
use App\Http\Controllers\Admin\Project\MasterScheduleController;
use App\Http\Controllers\Admin\Project\MasterScheduleActivityController;
use App\Http\Controllers\Admin\Project\ProjectBudgetController;
use App\Http\Controllers\Admin\Project\ProjectBudgetCategoryController;
use App\Http\Controllers\Admin\Project\ProjectBudgetItemController;
use App\Http\Controllers\Admin\Project\ProjectFundingPlanController;
use App\Http\Controllers\Admin\Project\ProjectFundingSourceController;
use App\Http\Controllers\Admin\Project\ProjectFundingCommitmentController;
use App\Http\Controllers\Admin\Project\ProjectFundingTrancheController;
use App\Http\Controllers\Admin\Project\ProjectDeliveryStrategyController;
use App\Http\Controllers\Admin\Project\ProjectProcurementStrategyController;
use App\Http\Controllers\Admin\Project\ProjectContractStrategyController;
use App\Http\Controllers\Admin\Project\ProjectRiskController;
use App\Http\Controllers\Admin\Project\ProjectStakeholderController;
use App\Http\Controllers\Admin\Project\ProjectGovernanceController;
use App\Http\Controllers\Admin\Project\ProjectApprovalMatrixController;
use App\Http\Controllers\Admin\Project\ProjectDecisionRegisterController;
use App\Http\Controllers\Admin\Project\ProjectGovernanceMeetingController;
use App\Http\Controllers\Admin\Project\ProjectGovernanceMeetingAttendeeController;
use App\Http\Controllers\Admin\Project\ProjectGovernanceMeetingAgendaItemController;
use App\Http\Controllers\Admin\Project\ProjectGovernanceMeetingActionItemController;
use App\Http\Controllers\Admin\Project\ProjectGovernanceMeetingDecisionController;
use App\Http\Controllers\Admin\Project\ProjectGovernanceMeetingMinutesController;
use App\Http\Controllers\Admin\Project\ProjectGovernanceMeetingDocumentController;
use App\Http\Controllers\Admin\Project\ProjectGovernanceFollowUpController;


use App\Http\Controllers\Admin\Procurement\ProcurementPlanController;
use App\Http\Controllers\Admin\Procurement\ProcurementPackageController;
use App\Http\Controllers\Admin\Procurement\ProcurementTenderController;
use App\Http\Controllers\Admin\Procurement\ProcurementBidderController;
use App\Http\Controllers\Admin\Procurement\ProcurementTenderBidderController;
use App\Http\Controllers\Admin\Procurement\ProcurementPrequalificationController;
use App\Http\Controllers\Admin\Procurement\ProcurementPrequalificationCriterionController;

use App\Http\Controllers\Admin\Procurement\ProcurementTenderDocumentController;
use App\Http\Controllers\Admin\Procurement\ProcurementTenderSubmissionController;
use App\Http\Controllers\Admin\Procurement\ProcurementTechnicalEvaluationController;
use App\Http\Controllers\Admin\Procurement\ProcurementCommercialEvaluationController;
use App\Http\Controllers\Admin\Procurement\ProcurementBidComparisonController;
use App\Http\Controllers\Admin\Procurement\ProcurementNegotiationController;
use App\Http\Controllers\Admin\Procurement\ProcurementAwardController;
use App\Http\Controllers\Admin\Procurement\ProcurementContractController;
use App\Http\Controllers\Admin\Procurement\ProcurementContractMilestoneController;
use App\Http\Controllers\Admin\Procurement\ProcurementMilestoneProgressController;
use App\Http\Controllers\Admin\Procurement\ProcurementMilestoneDocumentController;

use App\Http\Controllers\Admin\Procurement\ProcurementContractInvoiceController;
use App\Http\Controllers\Admin\Procurement\ProcurementContractPaymentController;
use App\Http\Controllers\Admin\Procurement\ProcurementPurchaseOrderController;
use App\Http\Controllers\Admin\Procurement\ProcurementDeliveryController;
use App\Http\Controllers\Admin\Procurement\ProcurementMaterialTrackingController;
use App\Http\Controllers\Admin\Procurement\ProcurementPerformanceController;
use App\Http\Controllers\Admin\Procurement\ProcurementPlanPerformanceController;

use App\Http\Controllers\Admin\Construction\ConstructionManagementController;
use App\Http\Controllers\Admin\Construction\ConstructionDashboardController;
use App\Http\Controllers\Admin\Construction\ConstructionContractorController;
use App\Http\Controllers\Admin\Construction\ConstructionConsultantController;
use App\Http\Controllers\Admin\Construction\ConstructionContractController;
use App\Http\Controllers\Admin\Construction\ConstructionWorkOrderController;
use App\Http\Controllers\Admin\Construction\ConstructionProgressController;
use App\Http\Controllers\Admin\Construction\ConstructionSiteIssueController;
use App\Http\Controllers\Admin\Construction\ConstructionSiteReportController;
use App\Http\Controllers\Admin\Construction\ConstructionScheduleActivityController;
use App\Http\Controllers\Admin\Construction\ConstructionProgressEntryController;
use App\Http\Controllers\Admin\Construction\ConstructionOtherCostController;
use App\Http\Controllers\Admin\Construction\ConstructionCostControlController;
use App\Http\Controllers\Admin\Construction\ConstructionVariationController;
use App\Http\Controllers\Admin\Construction\SiteInstructionController;
use App\Http\Controllers\Admin\Construction\ConstructionSubmittalController;
use App\Http\Controllers\Admin\Construction\ConstructionInspectionController;
use App\Http\Controllers\Admin\Construction\ConstructionQualityItpController;
use App\Http\Controllers\Admin\Construction\ConstructionQualityNcrController;


use App\Http\Controllers\Admin\Construction\ConstructionHseController;
use App\Http\Controllers\Admin\Construction\ConstructionHseObservationController;
use App\Http\Controllers\Admin\Construction\ConstructionHseCorrectiveActionController;
use App\Http\Controllers\Admin\Construction\ConstructionHseIncidentController;
use App\Http\Controllers\Admin\Construction\ConstructionHseIncidentActionController;
use App\Http\Controllers\Admin\Construction\ConstructionHseIncidentDocumentController;
use App\Http\Controllers\Admin\Construction\ConstructionHseIncidentInvestigationController;

use App\Http\Controllers\Admin\Construction\ConstructionHseIncidentPersonController;
use App\Http\Controllers\Admin\Construction\ConstructionHseIncidentWitnessController;
use App\Http\Controllers\Admin\Construction\ConstructionHseInspectionController;
use App\Http\Controllers\Admin\Construction\ConstructionHseInspectionItemController;
use App\Http\Controllers\Admin\Construction\ConstructionHseInspectionFindingController;
use App\Http\Controllers\Admin\Construction\ConstructionHseInspectionActionController;
use App\Http\Controllers\Admin\Construction\ConstructionHseInspectionDocumentController;
use App\Http\Controllers\Admin\Construction\ConstructionHseSafetyMeetingController;
use App\Http\Controllers\Admin\Construction\ConstructionHseSafetyMeetingParticipantController;
use App\Http\Controllers\Admin\Construction\ConstructionHseSafetyMeetingDocumentController;
use App\Http\Controllers\Admin\Construction\ConstructionHseToolboxTalkController;
use App\Http\Controllers\Admin\Construction\ConstructionHseToolboxTalkParticipantController;

use App\Http\Controllers\Admin\Construction\ConstructionHseToolboxTalkDocumentController;
use App\Http\Controllers\Admin\Construction\ConstructionHseEnvironmentalRecordController;
use App\Http\Controllers\Admin\Construction\ConstructionHseEnvironmentalComplianceController;
use App\Http\Controllers\Admin\Construction\ConstructionHseEnvironmentalActionController;

// Contract Management

use App\Http\Controllers\Admin\ContractManagement\ContractManagementContractController;
use App\Http\Controllers\Admin\ContractManagement\ContractClaimController;
use App\Http\Controllers\Admin\ContractManagement\ContractExtensionOfTimeController;
use App\Http\Controllers\Admin\ContractManagement\ContractInsuranceController;
use App\Http\Controllers\Admin\ContractManagement\ContractPerformanceSecurityController;
use App\Http\Controllers\Admin\ContractManagement\ContractManagementRetentionController;
use App\Http\Controllers\Admin\ContractManagement\ContractManagementAdvancePaymentController;
use App\Http\Controllers\Admin\ContractManagement\ContractManagementDocumentController;
use App\Http\Controllers\Admin\ContractManagement\ContractManagementCorrespondenceController;

use App\Http\Controllers\Admin\ContractManagement\ContractManagementDashboardController;


use App\Http\Controllers\Admin\Construction\ConstructionMaterialController;
use App\Http\Controllers\Admin\Construction\ConstructionMaterialRequestController;

use App\Http\Controllers\Admin\Construction\ConstructionMaterialDeliveryController;

use App\Http\Controllers\Admin\Construction\ConstructionMaterialReceiptController;
use App\Http\Controllers\Admin\Construction\ConstructionMaterialStockController;
use App\Http\Controllers\Admin\Construction\ConstructionMaterialRequirementController;
use App\Http\Controllers\Admin\Construction\ConstructionEquipmentController;
use App\Http\Controllers\Admin\Construction\ConstructionEquipmentDeploymentController;
use App\Http\Controllers\Admin\Construction\ConstructionEquipmentUsageLogController;
use App\Http\Controllers\Admin\Construction\ConstructionEquipmentMaintenanceController;
use App\Http\Controllers\Admin\Construction\ConstructionManpowerController;
use App\Http\Controllers\Admin\Construction\ConstructionManpowerAssignmentController;
use App\Http\Controllers\Admin\Construction\ConstructionManpowerEntryController;
use App\Http\Controllers\Admin\Construction\ConstructionPaymentCertificateController;



use App\Http\Controllers\Admin\DesignManagement\DesignManagementController;
use App\Http\Controllers\Admin\DesignManagement\DesignDashboardController;
use App\Http\Controllers\Admin\DesignManagement\DesignProjectBriefController;
use App\Http\Controllers\Admin\DesignManagement\DesignConsultantController;
use App\Http\Controllers\Admin\DesignManagement\DesignPackageController;
use App\Http\Controllers\Admin\DesignManagement\DesignDrawingController;
use App\Http\Controllers\Admin\DesignManagement\DesignSubmittalController;
use App\Http\Controllers\Admin\DesignManagement\DesignReviewController;
use App\Http\Controllers\Admin\DesignManagement\DesignCommentController;
use App\Http\Controllers\Admin\DesignManagement\DesignRfiController;
use App\Http\Controllers\Admin\DesignManagement\DesignChangeController;
use App\Http\Controllers\Admin\DesignManagement\DesignApprovalController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', [AuthController::class, 'showLoginForm'])
    ->name('login.form');


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::controller(AuthController::class)->group(function () {

    Route::get('/login', 'showLoginForm')
        ->name('login.form');

    Route::post('/login', 'login')
        ->middleware('throttle:10,1')
        ->name('login');

    Route::get('/register', 'showRegisterForm')
        ->name('register.form');

    Route::post('/register', 'register')
        ->name('register');

    Route::get('/forgot-password', 'showForgotForm')
        ->name('forgot.form');

    Route::post('/forgot-password', 'forgotPassword')
        ->middleware('throttle:5,1')
        ->name('password.email');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->middleware('auth')
        ->name('logout');
});


/*
|--------------------------------------------------------------------------
| Authentication / Profile
|--------------------------------------------------------------------------
*/

    Route::middleware('auth')
        ->prefix('profile')
        ->name('profile.')
        ->group(function () {

            Route::get('/dashboard', [
                AuthController::class,
                'dashboard'
            ])->name('dashboard');

            Route::get('/', [
                AuthController::class,
                'profileForm'
            ])
                ->middleware('permission:profile.view')
                ->name('show');

            Route::get('/edit', [
                AuthController::class,
                'profileEditForm'
            ])
                ->middleware('permission:profile.view')
                ->name('edit');

            Route::post('/update', [
                AuthController::class,
                'updateProfile'
            ])
                ->middleware('permission:profile.update')
                ->name('update');

            Route::get('/change-password', [
                AuthController::class,
                'passwordForm'
            ])
                ->middleware('permission:profile.update')
                ->name('password');

            Route::post('/change-password', [
                AuthController::class,
                'changePassword'
            ])
                ->middleware('permission:profile.update')
                ->name('change-password');
        });
Route::middleware('auth')->group(function () {
 
     /*
    |--------------------------------------------------------------------------
    | ADMIN PANEL
    |--------------------------------------------------------------------------
    */

    Route::prefix('admin')
        ->name('admin.')
        ->group(function () {


            /*
            |--------------------------------------------------------------------------
            | Dashboard
            |--------------------------------------------------------------------------
            */

            Route::get('/dashboard', [
                DashboardController::class,
                'index'
            ])
                ->middleware('permission:dashboard.view')
                ->name('dashboard');


            /*
            |--------------------------------------------------------------------------
            | User Management
            |--------------------------------------------------------------------------
            */

            Route::prefix('users')
                ->name('users.')
                ->group(function () {

                    Route::get('/', [
                        UserManagementController::class,
                        'index'
                    ])
                        ->middleware('permission:users.view')
                        ->name('index');


                    Route::get('/create', [
                        UserManagementController::class,
                        'create'
                    ])
                        ->middleware('permission:users.create')
                        ->name('create');


                    Route::post('/', [
                        UserManagementController::class,
                        'store'
                    ])
                        ->middleware('permission:users.create')
                        ->name('store');


                    Route::get('/{user}', [
                        UserManagementController::class,
                        'show'
                    ])
                        ->middleware('permission:users.view')
                        ->name('show');


                    Route::get('/{user}/edit', [
                        UserManagementController::class,
                        'edit'
                    ])
                        ->middleware('permission:users.edit')
                        ->name('edit');


                    Route::put('/{user}', [
                        UserManagementController::class,
                        'update'
                    ])
                        ->middleware('permission:users.edit')
                        ->name('update');


                    Route::delete('/{user}', [
                        UserManagementController::class,
                        'destroy'
                    ])
                        ->middleware('permission:users.delete')
                        ->name('destroy');


                    Route::post('/{user}/assign-role', [
                        UserManagementController::class,
                        'assignRole'
                    ])
                        ->middleware('permission:users.edit')
                        ->name('assign-role');


                    Route::post('/{user}/revoke-role', [
                        UserManagementController::class,
                        'revokeRole'
                    ])
                        ->middleware('permission:users.edit')
                        ->name('revoke-role');


                    Route::post('/{user}/activate', [
                        UserManagementController::class,
                        'activate'
                    ])
                        ->middleware('permission:users.edit')
                        ->name('activate');


                    Route::post('/{user}/deactivate', [
                        UserManagementController::class,
                        'deactivate'
                    ])
                        ->middleware('permission:users.edit')
                        ->name('deactivate');


                    Route::get('/{user}/audits', [
                        UserManagementController::class,
                        'audits'
                    ])
                        ->middleware('permission:audit.view')
                        ->name('audits');
                });


            /*
            |--------------------------------------------------------------------------
            | Role Management
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'roles',
                RoleController::class
            )
                ->middleware([
                    'index'   => 'permission:roles.view',
                    'show'    => 'permission:roles.view',
                    'create'  => 'permission:roles.create',
                    'store'   => 'permission:roles.create',
                    'edit'    => 'permission:roles.edit',
                    'update'  => 'permission:roles.edit',
                    'destroy' => 'permission:roles.delete',
                ]);

  Route::prefix('assets')
    ->name('assets.')
    ->group(function () {
            /*
            |--------------------------------------------------------------------------
            | Mall Management
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'malls',
                MallController::class
            )
                ->middleware([
                    'index'   => 'permission:malls.view',
                    'show'    => 'permission:malls.view',
                    'create'  => 'permission:malls.create',
                    'store'   => 'permission:malls.create',
                    'edit'    => 'permission:malls.edit',
                    'update'  => 'permission:malls.edit',
                    'destroy' => 'permission:malls.delete',
                ]);


            /*
            |--------------------------------------------------------------------------
            | Building Management
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'buildings',
                BuildingController::class
            )
                ->middleware([
                    'index'   => 'permission:buildings.view',
                    'show'    => 'permission:buildings.view',
                    'create'  => 'permission:buildings.create',
                    'store'   => 'permission:buildings.create',
                    'edit'    => 'permission:buildings.edit',
                    'update'  => 'permission:buildings.edit',
                    'destroy' => 'permission:buildings.delete',
                ]);


            /*
            |--------------------------------------------------------------------------
            | Floor Management
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'floors',
                FloorController::class
            )
                ->middleware([
                    'index'   => 'permission:floors.view',
                    'show'    => 'permission:floors.view',
                    'create'  => 'permission:floors.create',
                    'store'   => 'permission:floors.create',
                    'edit'    => 'permission:floors.edit',
                    'update'  => 'permission:floors.edit',
                    'destroy' => 'permission:floors.delete',
                ]);


            /*
            |--------------------------------------------------------------------------
            | Zone Management
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'zones',
                ZoneController::class
            )
                ->middleware([
                    'index'   => 'permission:zones.view',
                    'show'    => 'permission:zones.view',
                    'create'  => 'permission:zones.create',
                    'store'   => 'permission:zones.create',
                    'edit'    => 'permission:zones.edit',
                    'update'  => 'permission:zones.edit',
                    'destroy' => 'permission:zones.delete',
                ]);


            /*
            |--------------------------------------------------------------------------
            | Unit Type Management
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'unit-types',
                UnitTypeController::class
            )
                ->middleware([
                    'index'   => 'permission:unit_types.view',
                    'show'    => 'permission:unit_types.view',
                    'create'  => 'permission:unit_types.create',
                    'store'   => 'permission:unit_types.create',
                    'edit'    => 'permission:unit_types.edit',
                    'update'  => 'permission:unit_types.edit',
                    'destroy' => 'permission:unit_types.delete',
                ]);

   
        // Units resource routes added by automated change
        Route::resource('units', App\Http\Controllers\Admin\Assets\UnitController::class)
            ->middleware([
                'index' => 'permission:units.view',
                'show' => 'permission:units.view',
                'create' => 'permission:units.create',
                'store' => 'permission:units.create',
                'edit' => 'permission:units.edit',
                'update' => 'permission:units.edit',
                'destroy' => 'permission:units.delete',
            ]);

             // DepartmentController resource routes added by automated change
            Route::resource('departments', DepartmentController::class)->middleware([
                'index'   => 'permission:departments.view',
                'show'    => 'permission:departments.view',
                'create'  => 'permission:departments.create',
                'store'   => 'permission:departments.create',
                'edit'    => 'permission:departments.edit',
                'update'  => 'permission:departments.edit',
                'destroy' => 'permission:departments.delete',
            ]);
             Route::resource('unit-statuses', UnitStatusController::class)->middleware([
                'index'   => 'permission:unit_statuses.view',
                'show'    => 'permission:unit_statuses.view',
                'create'  => 'permission:unit_statuses.create',
                'store'   => 'permission:unit_statuses.create',
                'edit'    => 'permission:unit_statuses.edit',
                'update'  => 'permission:unit_statuses.edit',
                'destroy' => 'permission:unit_statuses.delete',
            ]);

            Route::resource('assets', AssetController::class)->middleware([
                'index'   => 'permission:assets.view',
                'show'    => 'permission:assets.view',
                'create'  => 'permission:assets.create',
                'store'   => 'permission:assets.create',
                'edit'    => 'permission:assets.edit',
                'update'  => 'permission:assets.edit',
                'destroy' => 'permission:assets.delete',
            ]);

            Route::resource('asset-categories', AssetCategoryController::class)->middleware([
                'index'   => 'permission:asset_categories.view',
                'show'    => 'permission:asset_categories.view',
                'create'  => 'permission:asset_categories.create',
                'store'   => 'permission:asset_categories.create',
                'edit'    => 'permission:asset_categories.edit',
                'update'  => 'permission:asset_categories.edit',
                'destroy' => 'permission:asset_categories.delete',
            ]);

            Route::resource('unit-documents', UnitDocumentController::class)->middleware([
                'index'   => 'permission:unit_documents.view',
                'show'    => 'permission:unit_documents.view',
                'create'  => 'permission:unit_documents.create',
                'store'   => 'permission:unit_documents.create',
                'edit'    => 'permission:unit_documents.edit',
                'update'  => 'permission:unit_documents.edit',
                'destroy' => 'permission:unit_documents.delete',
            ]);

          
              /*expenses*/

            Route::get('expenses/{asset}', [AssetExpenseController::class, 'index'])
                ->middleware('permission:expenses.view')
                ->name('expenses.index');

            Route::get('expenses/{asset}/create', [AssetExpenseController::class, 'create'])
                ->middleware('permission:expenses.create')
                ->name('expenses.create');

            Route::post('expenses/{asset}', [AssetExpenseController::class, 'store'])
                ->middleware('permission:expenses.create')
                ->name('expenses.store');

            Route::get('expenses/{asset}/{expense}', [AssetExpenseController::class, 'show'])
                ->middleware('permission:expenses.view')
                ->name('expenses.show');

            Route::get('expenses/{asset}/{expense}/edit', [AssetExpenseController::class, 'edit'])
                ->middleware('permission:expenses.edit')
                ->name('expenses.edit');

            Route::put('expenses/{asset}/{expense}', [AssetExpenseController::class, 'update'])
                ->middleware('permission:expenses.edit')
                ->name('expenses.update');

            Route::patch('expenses/{asset}/{expense}', [AssetExpenseController::class, 'update'])
                ->middleware('permission:expenses.edit')
                ->name('expenses.update.patch');

            Route::delete('expenses/{asset}/{expense}', [AssetExpenseController::class, 'destroy'])
                ->middleware('permission:expenses.delete')
                ->name('expenses.destroy');

                 /*Incomes*/

            Route::get('incomes/{asset}', [AssetIncomeController::class, 'index'])
                ->middleware('permission:incomes.view')
                ->name('incomes.index');

            Route::get('incomes/{asset}/create', [AssetIncomeController::class, 'create'])
                ->middleware('permission:incomes.create')
                ->name('incomes.create');

            Route::post('incomes/{asset}', [AssetIncomeController::class, 'store'])
                ->middleware('permission:incomes.create')
                ->name('incomes.store');

            Route::get('incomes/{asset}/{income}', [AssetIncomeController::class, 'show'])
                ->middleware('permission:incomes.view')
                ->name('incomes.show');

            Route::get('incomes/{asset}/{income}/edit', [AssetIncomeController::class, 'edit'])
                ->middleware('permission:incomes.edit')
                ->name('incomes.edit');

            Route::put('incomes/{asset}/{income}', [AssetIncomeController::class, 'update'])
                ->middleware('permission:incomes.edit')
                ->name('incomes.update');

            Route::patch('incomes/{asset}/{income}', [AssetIncomeController::class, 'update'])
                ->middleware('permission:incomes.edit')
                ->name('incomes.update.patch');

            Route::delete('incomes/{asset}/{income}', [AssetIncomeController::class, 'destroy'])
                ->middleware('permission:incomes.delete')
                ->name('incomes.destroy');
                
            Route::get('/economic-dashboard', [
                        EconomicDashboardController::class,
                        'index'
                    ])
                    ->middleware('permission:economic_dashboard.view')
                    ->name('economic-dashboard');
                    Route::get(
                                'performance',
                                [AssetPerformanceController::class, 'index']
                            )
                                ->middleware('permission:performance.view')
                                ->name('performance.index');


                            Route::get(
                                'assets/{asset}/performance',
                                [AssetPerformanceController::class, 'show']
                            )
                                ->middleware('permission:performance.view')
                                ->name('performance.show');
               

        });
        Route::get(
            'activity-logs',
            [ActivityLogController::class, 'index']
        )
            ->name('activity-logs.index')
            ->middleware('permission:audit.view');

        Route::get(
            'activity-logs/{activityLog}',
            [ActivityLogController::class, 'show']
        )
            ->name('activity-logs.show')
            ->middleware('permission:audit.view');
    });
});

/*Leasing*/


    Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth'])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Leasing
        |--------------------------------------------------------------------------
        */

        Route::prefix('leasing')
            ->name('leasing.')->middleware('permission:leasing.view')
            ->group(function () {

                /*
                |--------------------------------------------------------------------------
                | Lease Proposals
                |--------------------------------------------------------------------------
                */

                Route::resource(
                    'proposals',
                    LeaseProposalController::class
                );

                Route::post(
                    'proposals/{id}/submit',
                    [
                        LeaseProposalController::class,
                        'submit'
                    ]
                )->name('proposals.submit');

                Route::post(
                    'proposals/{id}/approve',
                    [
                        LeaseProposalController::class,
                        'approve'
                    ]
                )->name('proposals.approve');

                Route::post(
                    'proposals/{id}/reject',
                    [
                        LeaseProposalController::class,
                        'reject'
                    ]
                )->name('proposals.reject');

                Route::post(
                    'agreements/{id}/activate',
                    [
                        LeaseAgreementController::class,
                        'activate'
                    ]
                )->name('agreements.activate');


                /*
                |--------------------------------------------------------------------------
                | Lease Agreements
                |--------------------------------------------------------------------------
                */

                Route::resource(
                    'agreements',
                    LeaseAgreementController::class
                );

                Route::resource(
                    'terms',
                    LeaseTermController::class
                );


                Route::resource(
                    'documents',
                    LeaseDocumentController::class
                );

                Route::post(
                    'documents/{document}/verify',
                    [
                        LeaseDocumentController::class,
                        'verify'
                    ]
                )->name('documents.verify');

                Route::post(
                    'documents/{document}/reject',
                    [
                        LeaseDocumentController::class,
                        'reject'
                    ]
                )->name('documents.reject');

                Route::get(
                    'agreements/{agreement}/renew',
                    [
                        LeaseAgreementController::class,
                        'renew'
                    ]
                )->name('agreements.renew');

                Route::post(
                    'agreements/{agreement}/renew',
                    [
                        LeaseAgreementController::class,
                        'processRenewal'
                    ]
                )->name('agreements.process-renewal');

                /*Route::resource(
                    'renewals',
                    LeaseRenewalController::class
                )->only([
                    'index',
                    'create',
                    'store',
                    'show',
                ]);*/

                Route::resource(
                    'renewals',
                    LeaseRenewalController::class
                )->only([
                    'index',
                    'create',
                    'store',
                    'show',
                    'edit',
                    'update',
                ]);

                Route::post(
                    'renewals/{renewal}/submit',
                    [LeaseRenewalController::class, 'submit']
                )->name('renewals.submit');


                Route::post(
                    'renewals/{renewal}/approve',
                    [LeaseRenewalController::class, 'approve']
                )->name('renewals.approve');


                Route::post(
                    'renewals/{renewal}/reject',
                    [LeaseRenewalController::class, 'reject']
                )->name('renewals.reject');


                Route::post(
                    'renewals/{renewal}/cancel',
                    [LeaseRenewalController::class, 'cancel']
                )->name('renewals.cancel');

                Route::get(
                    'renewals/{renewal}/convert',
                    [LeaseRenewalController::class, 'convert']
                )->name('renewals.convert');

                Route::post(
                    'renewals/{renewal}/convert',
                    [LeaseRenewalController::class, 'convertStore']
                )->name('renewals.convert.store');


                Route::resource(
                    'escalations',
                    LeaseEscalationController::class
                )->only([
                    'index',
                    'create',
                    'store',
                    'show',
                ]);


                Route::post(
                    'escalations/{escalation}/approve',
                    [
                        LeaseEscalationController::class,
                        'approve'
                    ]
                )->name('escalations.approve');


                Route::post(
                    'escalations/{escalation}/cancel',
                    [
                        LeaseEscalationController::class,
                        'cancel'
                    ]
                )->name('escalations.cancel');

                Route::get(
                    'history',
                    [
                        LeaseHistoryController::class,
                        'index'
                    ]
                )->name('history.index');


                Route::resource(
                    'terminations',
                    LeaseTerminationController::class
                );

                Route::post(
                    'terminations/{id}/submit',
                    [
                        LeaseTerminationController::class,
                        'submit'
                    ]
                )->name('terminations.submit');

                Route::post(
                    'terminations/{id}/approve',
                    [
                        LeaseTerminationController::class,
                        'approve'
                    ]
                )->name('terminations.approve');

                Route::post(
                    'terminations/{id}/cancel',
                    [
                        LeaseTerminationController::class,
                        'cancel'
                    ]
                )->name('terminations.cancel');


                Route::post(
                    'terminations/{id}/complete-inspection',
                    [LeaseTerminationController::class, 'completeInspection']
                )->name('terminations.completeInspection');

                Route::post(
                    'terminations/{id}/complete-handover',
                    [LeaseTerminationController::class, 'completeHandover']
                )->name('terminations.completeHandover');

                Route::post(
                    'terminations/{id}/complete',
                    [LeaseTerminationController::class, 'complete']
                )->name('terminations.complete');

                Route::get(
                    'dashboard',
                    [
                        LeaseDashboardController::class,
                        'index'
                    ]
                )->name('dashboard');

                Route::get('/', [
                    LeasingController::class,
                    'index'
                ])->name('index');

                Route::get('/{agreement}', [
                    LeasingController::class,
                    'show'
                ])->name('show');


            });


            /*Route::prefix('tenants')
            ->name('tenants.')
            ->group(function () {

                Route::get(
                    'dashboard',
                    [
                        TenantDashboardController::class,
                        'index'
                    ]
                )->name('dashboard');

            });*/

            Route::get(
                'tenants/dashboard',
                [
                    TenantDashboardController::class,
                    'index'
                ]
            )->name('tenants.dashboard');


            /*
            |--------------------------------------------------------------------------
            | Tenant CRUD
            |--------------------------------------------------------------------------
            */

            Route::resource(
                'tenants',
                TenantController::class
            );

            /*
            |--------------------------------------------------------------------------
            | Tenant Contacts
            |--------------------------------------------------------------------------
            */

            Route::get(
                'tenants/{tenant}/contacts',
                [
                    TenantContactController::class,
                    'index'
                ]
            )->name('tenants.contacts.index');

            Route::post(
                'tenants/{tenant}/contacts',
                [
                    TenantContactController::class,
                    'store'
                ]
            )->name('tenants.contacts.store');

            Route::get(
                'tenants/{tenant}/contacts/{contact}/edit',
                [
                    TenantContactController::class,
                    'edit'
                ]
            )->name('tenants.contacts.edit');

            Route::put(
                'tenants/{tenant}/contacts/{contact}',
                [
                    TenantContactController::class,
                    'update'
                ]
            )->name('tenants.contacts.update');

            Route::delete(
                'tenants/{tenant}/contacts/{contact}',
                [
                    TenantContactController::class,
                    'destroy'
                ]
            )->name('tenants.contacts.destroy');

            /*
            |--------------------------------------------------------------------------
            | Tenant Addresses
            |--------------------------------------------------------------------------
            */

            Route::get(
                'tenants/{tenant}/addresses',
                [
                    TenantAddressController::class,
                    'index'
                ]
            )->name('tenants.addresses.index');

            Route::post(
                'tenants/{tenant}/addresses',
                [
                    TenantAddressController::class,
                    'store'
                ]
            )->name('tenants.addresses.store');

            Route::get(
                'tenants/{tenant}/addresses/{address}/edit',
                [
                    TenantAddressController::class,
                    'edit'
                ]
            )->name('tenants.addresses.edit');

            Route::put(
                'tenants/{tenant}/addresses/{address}',
                [
                    TenantAddressController::class,
                    'update'
                ]
            )->name('tenants.addresses.update');

            Route::delete(
                'tenants/{tenant}/addresses/{address}',
                [
                    TenantAddressController::class,
                    'destroy'
                ]
            )->name('tenants.addresses.destroy');

            /*
            |--------------------------------------------------------------------------
            | Tenant Bank Accounts
            |--------------------------------------------------------------------------
            */

            Route::get(
                'tenants/{tenant}/bank-accounts',
                [
                    TenantBankAccountController::class,
                    'index'
                ]
            )->name('tenants.bank-accounts.index');


            Route::post(
                'tenants/{tenant}/bank-accounts',
                [
                    TenantBankAccountController::class,
                    'store'
                ]
            )->name('tenants.bank-accounts.store');


            Route::get(
                'tenants/{tenant}/bank-accounts/{account}/edit',
                [
                    TenantBankAccountController::class,
                    'edit'
                ]
            )->name('tenants.bank-accounts.edit');


            Route::put(
                'tenants/{tenant}/bank-accounts/{account}',
                [
                    TenantBankAccountController::class,
                    'update'
                ]
            )->name('tenants.bank-accounts.update');


            Route::delete(
                'tenants/{tenant}/bank-accounts/{account}',
                [
                    TenantBankAccountController::class,
                    'destroy'
                ]
            )->name('tenants.bank-accounts.destroy');

            /*
            |--------------------------------------------------------------------------
            | Tenant Documents
            |--------------------------------------------------------------------------
            */

            Route::get(
                'tenants/{tenant}/documents',
                [
                    TenantDocumentController::class,
                    'index'
                ]
            )->name('tenants.documents.index');


            Route::post(
                'tenants/{tenant}/documents',
                [
                    TenantDocumentController::class,
                    'store'
                ]
            )->name('tenants.documents.store');


            Route::get(
                'tenants/{tenant}/documents/{document}/edit',
                [
                    TenantDocumentController::class,
                    'edit'
                ]
            )->name('tenants.documents.edit');


            Route::put(
                'tenants/{tenant}/documents/{document}',
                [
                    TenantDocumentController::class,
                    'update'
                ]
            )->name('tenants.documents.update');


            Route::delete(
                'tenants/{tenant}/documents/{document}',
                [
                    TenantDocumentController::class,
                    'destroy'
                ]
            )->name('tenants.documents.destroy');

            /*
            |--------------------------------------------------------------------------
            | Tenant Emergency Contacts
            |--------------------------------------------------------------------------
            */

            Route::get(
                'tenants/{tenant}/emergency-contacts',
                [
                    TenantEmergencyContactController::class,
                    'index'
                ]
            )->name('tenants.emergency-contacts.index');


            Route::post(
                'tenants/{tenant}/emergency-contacts',
                [
                    TenantEmergencyContactController::class,
                    'store'
                ]
            )->name('tenants.emergency-contacts.store');


            Route::get(
                'tenants/{tenant}/emergency-contacts/{contact}/edit',
                [
                    TenantEmergencyContactController::class,
                    'edit'
                ]
            )->name('tenants.emergency-contacts.edit');


            Route::put(
                'tenants/{tenant}/emergency-contacts/{contact}',
                [
                    TenantEmergencyContactController::class,
                    'update'
                ]
            )->name('tenants.emergency-contacts.update');


            Route::delete(
                'tenants/{tenant}/emergency-contacts/{contact}',
                [
                    TenantEmergencyContactController::class,
                    'destroy'
                ]
            )->name('tenants.emergency-contacts.destroy');

            /*
            |--------------------------------------------------------------------------
            | Tenant Notes
            |--------------------------------------------------------------------------
            */

            Route::get(
                'tenants/{tenant}/notes',
                [
                    TenantNoteController::class,
                    'index'
                ]
            )->name('tenants.notes.index');


            Route::post(
                'tenants/{tenant}/notes',
                [
                    TenantNoteController::class,
                    'store'
                ]
            )->name('tenants.notes.store');


            Route::get(
                'tenants/{tenant}/notes/{note}/edit',
                [
                    TenantNoteController::class,
                    'edit'
                ]
            )->name('tenants.notes.edit');


            Route::put(
                'tenants/{tenant}/notes/{note}',
                [
                    TenantNoteController::class,
                    'update'
                ]
            )->name('tenants.notes.update');


            Route::delete(
                'tenants/{tenant}/notes/{note}',
                [
                    TenantNoteController::class,
                    'destroy'
                ]
            )->name('tenants.notes.destroy');

            /*
            |--------------------------------------------------------------------------
            | Tenant History
            |--------------------------------------------------------------------------
            */

            Route::get(
                'tenants/{tenant}/history',
                [
                    TenantHistoryController::class,
                    'index'
                ]
            )->name('tenants.history.index');


            Route::prefix('revenue')
                ->name('revenue.')
                ->group(function () {

                    Route::get(
                        'tax-configurations',
                        [
                            TaxConfigurationController::class,
                            'index'
                        ]
                    )->name(
                        'tax-configurations.index'
                    );

                    Route::post(
                        'tax-configurations',
                        [
                            TaxConfigurationController::class,
                            'store'
                        ]
                    )->name(
                        'tax-configurations.store'
                    );

                    Route::get(
                        'tax-configurations/{id}/edit',
                        [
                            TaxConfigurationController::class,
                            'edit'
                        ]
                    )->name(
                        'tax-configurations.edit'
                    );

                    Route::put(
                        'tax-configurations/{id}',
                        [
                            TaxConfigurationController::class,
                            'update'
                        ]
                    )->name(
                        'tax-configurations.update'
                    );

                    Route::delete(
                        'tax-configurations/{id}',
                        [
                            TaxConfigurationController::class,
                            'destroy'
                        ]
                    )->name(
                        'tax-configurations.destroy'
                    );

            });

            Route::prefix('revenue')
                ->name('revenue.')
                ->group(function () {

                    /*
                    |--------------------------------------------------------------------------
                    | Deposits
                    |--------------------------------------------------------------------------
                    */

                    Route::get(
                        'deposits',
                        [
                            DepositController::class,
                            'index'
                        ]
                    )->name(
                        'deposits.index'
                    );

                    Route::post(
                        'deposits',
                        [
                            DepositController::class,
                            'store'
                        ]
                    )->name(
                        'deposits.store'
                    );

                    Route::get(
                        'deposits/{id}/edit',
                        [
                            DepositController::class,
                            'edit'
                        ]
                    )->name(
                        'deposits.edit'
                    );

                    Route::put(
                        'deposits/{id}',
                        [
                            DepositController::class,
                            'update'
                        ]
                    )->name(
                        'deposits.update'
                    );

                    Route::delete(
                        'deposits/{id}',
                        [
                            DepositController::class,
                            'destroy'
                        ]
                    )->name(
                        'deposits.destroy'
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Deposit Receipts
                    |--------------------------------------------------------------------------
                    */

                    Route::get(
                        'deposit-receipts',
                        [
                            DepositReceiptController::class,
                            'index'
                        ]
                    )->name(
                        'deposit-receipts.index'
                    );

                    Route::get(
                        'deposits/{deposit}/receipts',
                        [
                            DepositReceiptController::class,
                            'index'
                        ]
                    )->name(
                        'deposits.receipts'
                    );

                    Route::post(
                        'deposit-receipts',
                        [
                            DepositReceiptController::class,
                            'store'
                        ]
                    )->name(
                        'deposit-receipts.store'
                    );

                    Route::get(
                        'deposit-receipts/{id}/edit',
                        [
                            DepositReceiptController::class,
                            'edit'
                        ]
                    )->name(
                        'deposit-receipts.edit'
                    );

                    Route::put(
                        'deposit-receipts/{id}',
                        [
                            DepositReceiptController::class,
                            'update'
                        ]
                    )->name(
                        'deposit-receipts.update'
                    );

                    Route::delete(
                        'deposit-receipts/{id}',
                        [
                            DepositReceiptController::class,
                            'destroy'
                        ]
                    )->name(
                        'deposit-receipts.destroy'
                    );

                    Route::post(
                        'deposit-receipts/{id}/reverse',
                        [
                            DepositReceiptController::class,
                            'reverse'
                        ]
                    )->name(
                        'deposit-receipts.reverse'
                    );

                    Route::post(
                        'deposit-receipts/{id}/confirm',
                        [DepositReceiptController::class, 'confirm']
                    )->name('admin.revenue.deposit-receipts.confirm');


                    /*
                    |--------------------------------------------------------------------------
                    | Deposit Refunds
                    |--------------------------------------------------------------------------
                    */

                    Route::get(
                        'deposit-refunds',
                        [
                            DepositRefundController::class,
                            'index'
                        ]
                    )->name(
                        'deposit-refunds.index'
                    );

                    Route::post(
                        'deposit-refunds',
                        [
                            DepositRefundController::class,
                            'store'
                        ]
                    )->name(
                        'deposit-refunds.store'
                    );

                    Route::post(
                        'deposit-refunds/{id}/approve',
                        [
                            DepositRefundController::class,
                            'approve'
                        ]
                    )->name(
                        'deposit-refunds.approve'
                    );

                    Route::post(
                        'deposit-refunds/{id}/process',
                        [
                            DepositRefundController::class,
                            'process'
                        ]
                    )->name(
                        'deposit-refunds.process'
                    );

                    Route::post(
                        'deposit-refunds/{id}/cancel',
                        [
                            DepositRefundController::class,
                            'cancel'
                        ]
                    )->name(
                        'deposit-refunds.cancel'
                    );


                    Route::get(
                        '/rent-schedules',
                        [RentScheduleController::class, 'index']
                    )->name('rent-schedules.index');

                    Route::post(
                        '/rent-schedules/generate/{agreementId}',
                        [RentScheduleController::class, 'generate']
                    )->name('rent-schedules.generate');

                    Route::post(
                        '/rent-schedules/{id}/generate-invoice',
                        [RentScheduleController::class, 'generateInvoice']
                    )->name('rent-schedules.generate-invoice');

                    Route::get(
                        '/rent-schedules/{id}',
                        [RentScheduleController::class, 'show']
                    )->name('rent-schedules.show');

                    Route::get('/invoices', [InvoiceController::class, 'index'])
                        ->name('invoices.index');

                    Route::get('/invoices/{id}', [InvoiceController::class, 'show'])
                        ->name('invoices.show');

                    Route::get('/invoices/{id}/payment/create', [
                        RentPaymentController::class,
                        'create'
                    ])->name('payments.create');

                    Route::post('/invoices/{id}/payment', [
                        RentPaymentController::class,
                        'store'
                    ])->name('payments.store');

                    Route::get(
                        '/invoices/{id}/print',
                        [InvoiceController::class, 'print']
                    )->name('invoices.print');

                    Route::get('/payments', [
                        RentPaymentController::class,
                        'index'
                    ])->name('payments.index');

                    Route::get('/payments/{id}', [
                        RentPaymentController::class,
                        'show'
                    ])->name('payments.show');

                    Route::post('/payments/{id}/confirm', [
                        RentPaymentController::class,
                        'confirm'
                    ])->name('payments.confirm');

                    Route::post(
                        '/payments/{id}/reverse',
                        [RentPaymentController::class, 'reverse']
                    )->name('payments.reverse');

                    Route::post(
                        '/payments/{id}/reconcile',
                        [RentPaymentController::class, 'reconcile']
                    )->name('payments.reconcile');

                    Route::get(
                        '/payments/{id}/receipt',
                        [RentPaymentController::class, 'receipt']
                    )->name('payments.receipt');

                    Route::get(
                        '/dashboard',
                        [RevenueDashboardController::class, 'index']
                    )->name('dashboard');

                    Route::get(
                        '/outstanding',
                        [OutstandingController::class, 'index']
                    )->name('outstanding.index');

                    Route::get(
                        '/outstanding/overdue',
                        [OutstandingController::class, 'overdue']
                    )->name('outstanding.overdue');

                    Route::get(
                        '/outstanding/tenants',
                        [OutstandingController::class, 'tenants']
                    )->name('outstanding.tenants');

                    Route::get(
                        '/reports/revenue',
                        [RevenueReportController::class, 'index']
                    )->name('reports.revenue');

                    Route::get(
                        '/reports/collections',
                        [RevenueReportController::class, 'collections']
                    )->name('reports.collections');

                    Route::get(
                        '/reports/charge-wise',
                        [RevenueReportController::class, 'chargeWise']
                    )->name('reports.charge-wise');

                    Route::get(
                        '/reports/tenant-wise',
                        [RevenueReportController::class, 'tenantWise']
                    )->name('reports.tenant-wise');

                    Route::get(
                        '/reports/aging',
                        [RevenueReportController::class, 'aging']
                    )->name('reports.aging');

                    Route::prefix('settings/charge-types')
                        ->name('settings.charge-types.')
                        ->group(function () {

                            Route::get(
                                '/',
                                [ChargeTypeController::class, 'index']
                            )->name('index');

                            Route::get(
                                '/create',
                                [ChargeTypeController::class, 'create']
                            )->name('create');

                            Route::post(
                                '/',
                                [ChargeTypeController::class, 'store']
                            )->name('store');

                            Route::get(
                                '/{id}/edit',
                                [ChargeTypeController::class, 'edit']
                            )->name('edit');

                            Route::put(
                                '/{id}',
                                [ChargeTypeController::class, 'update']
                            )->name('update');

                            Route::delete(
                                '/{id}',
                                [ChargeTypeController::class, 'destroy']
                            )->name('destroy');

                    });

                    Route::prefix('reconciliation')
                    ->name('reconciliation.')
                    ->group(function () {

                        Route::get(
                            '/',
                            [ReconciliationController::class, 'index']
                        )->name('index');

                        Route::post(
                            '/{id}/reconcile',
                            [ReconciliationController::class, 'reconcile']
                        )->name('reconcile');

                    });

                    Route::get(
                        '/audit-log',
                        [RevenueAuditLogController::class, 'index']
                    )->name('audit.index');

            });

            Route::prefix('fitout')
                ->name('fitout.')
                ->group(function () {

                    Route::get(
                        '/dashboard',
                        [FitoutDashboardController::class, 'index']
                    )->name('dashboard');

                    Route::get(
                        'requests',
                        [FitoutRequestController::class, 'index']
                    )->name('requests.index');

                    Route::get(
                        'requests/create',
                        [FitoutRequestController::class, 'create']
                    )->name('requests.create');

                    Route::post(
                        'requests',
                        [FitoutRequestController::class, 'store']
                    )->name('requests.store');

                    Route::get(
                        'requests/{id}',
                        [FitoutRequestController::class, 'show']
                    )->name('requests.show');

                    Route::post(
                        'requests/{id}/submit',
                        [FitoutRequestController::class, 'submit']
                    )->name('requests.submit');

                    Route::post(
                        'requests/{id}/start-review',
                        [FitoutRequestController::class, 'startReview']
                    )->name('requests.startReview');

                    Route::post(
                        'requests/{id}/approve',
                        [FitoutRequestController::class, 'approve']
                    )->name('requests.approve');

                    Route::post(
                        'requests/{id}/reject',
                        [FitoutRequestController::class, 'reject']
                    )->name('requests.reject');

                    Route::post(
                        'requests/{id}/start',
                        [FitoutRequestController::class, 'start']
                    )->name('requests.start');

                    Route::post(
                        'requests/{id}/complete',
                        [FitoutRequestController::class, 'complete']
                    )->name('requests.complete');

                    Route::post(
                        'requests/{id}/close',
                        [FitoutRequestController::class, 'close']
                    )->name('requests.close');

                    Route::post(
                        '/requests/{id}/generate-approval',
                        [
                            FitoutRequestController::class,
                            'generateApproval'
                        ]
                    )->name('requests.generate-approval');

                    /*Route::get(
                        'requests/{id}/edit',
                        [FitoutRequestController::class, 'edit']
                    )->name('requests.edit');

                    Route::put(
                        'requests/{id}',
                        [FitoutRequestController::class, 'update']
                    )->name('requests.update');

                    Route::post(
                        'requests/{id}/submit',
                        [FitoutRequestController::class, 'submit']
                    )->name('requests.submit');

                    Route::delete(
                        'requests/{id}',
                        [FitoutRequestController::class, 'destroy']
                    )->name('requests.destroy');

                    Route::get(
                        '/agreements/{id}/details',
                        [FitoutRequestController::class, 'agreementDetails']
                    )->name('agreements.details');*/


                    /*Contractor Route*/
                    Route::get(
                        'contractors',
                        [ContractorController::class, 'index']
                    )->name('contractors.index');

                    Route::get(
                        'contractors/create',
                        [ContractorController::class, 'create']
                    )->name('contractors.create');

                    Route::post(
                        'contractors',
                        [ContractorController::class, 'store']
                    )->name('contractors.store');

                    Route::get(
                        'contractors/{id}',
                        [ContractorController::class, 'show']
                    )->name('contractors.show');

                    Route::get(
                        'contractors/{id}/edit',
                        [ContractorController::class, 'edit']
                    )->name('contractors.edit');
                    Route::put(
                        'contractors/{id}',
                        [ContractorController::class, 'update']
                    )->name('contractors.update');
                    Route::post(
                        'contractors/{id}/approve',
                        [ContractorController::class, 'approve']
                    )->name('contractors.approve');

                    Route::post(
                        'contractors/{id}/reject',
                        [ContractorController::class, 'reject']
                    )->name('contractors.reject');

                    Route::post(
                        'contractors/{id}/suspend',
                        [ContractorController::class, 'suspend']
                    )->name('contractors.suspend');

                    /*
                    |--------------------------------------------------------------------------
                    | FIT-OUT STAGES
                    |--------------------------------------------------------------------------
                    */

                    Route::get(
                        'requests/{fitoutRequestId}/stages',
                        [FitoutStageController::class, 'index']
                    )->name('stages.index');

                    Route::get(
                        'stages/{id}',
                        [FitoutStageController::class, 'show']
                    )->name('stages.show');

                    Route::post(
                        'stages/{id}/start',
                        [FitoutStageController::class, 'start']
                    )->name('stages.start');

                    Route::post(
                        'stages/{id}/progress',
                        [FitoutStageController::class, 'updateProgress']
                    )->name('stages.progress');

                    Route::post(
                        'stages/{id}/complete',
                        [FitoutStageController::class, 'complete']
                    )->name('stages.complete');

                    Route::post(
                        'stages/{id}/hold',
                        [FitoutStageController::class, 'hold']
                    )->name('stages.hold');

                    Route::post(
                        'stages/{id}/resume',
                        [FitoutStageController::class, 'resume']
                    )->name('stages.resume');

                    Route::get(
                        'stages/{id}/edit',
                        [FitoutStageController::class, 'edit']
                    )->name('stages.edit');

                    Route::put(
                        'stages/{id}',
                        [FitoutStageController::class, 'update']
                    )->name('stages.update');

                    /*Documents*/

                    Route::resource(
                        'documents',
                        FitoutDocumentController::class
                    )->names('documents');


                    Route::get('/documents/{id}/review', [
                        FitoutDocumentController::class,
                        'review'
                    ])->name('documents.review');

                    Route::post('/documents/{id}/start-review', [
                        FitoutDocumentController::class,
                        'startReview'
                    ])->name('documents.start-review');

                    Route::post('/documents/{id}/approve', [
                        FitoutDocumentController::class,
                        'approve'
                    ])->name('documents.approve');

                    Route::post('/documents/{id}/reject', [
                        FitoutDocumentController::class,
                        'reject'
                    ])->name('documents.reject');

                    /*Approvals*/
                    Route::get('/approvals', [
                        FitoutApprovalController::class,
                        'index'
                    ])->name('approvals.index');

                    Route::get('/approvals/pending', [
                        FitoutApprovalController::class,
                        'pending'
                    ])->name('approvals.pending');

                    Route::get('/approvals/{id}', [
                        FitoutApprovalController::class,
                        'show'
                    ])->name('approvals.show');

                    Route::post('/approvals/generate/{fitoutRequestId}', [
                        FitoutApprovalController::class,
                        'generate'
                    ])->name('approvals.generate');

                    Route::post('/approvals/{id}/approve', [
                        FitoutApprovalController::class,
                        'approve'
                    ])->name('approvals.approve');

                    Route::post('/approvals/{id}/reject', [
                        FitoutApprovalController::class,
                        'reject'
                    ])->name('approvals.reject');

                    /*insepections*/

                     Route::get('/inspections', [
                        FitoutInspectionController::class,
                        'index'
                    ])->name('inspections.index');

                    Route::get('/inspections/create', [
                        FitoutInspectionController::class,
                        'create'
                    ])->name('inspections.create');

                    Route::post('/inspections', [
                        FitoutInspectionController::class,
                        'store'
                    ])->name('inspections.store');

                    Route::get('/inspections/{id}', [
                        FitoutInspectionController::class,
                        'show'
                    ])->name('inspections.show');

                    Route::get('/inspections/{id}/edit', [
                        FitoutInspectionController::class,
                        'edit'
                    ])->name('inspections.edit');

                    Route::put('/inspections/{id}', [
                        FitoutInspectionController::class,
                        'update'
                    ])->name('inspections.update');

                    Route::post('/inspections/{id}/start', [
                        FitoutInspectionController::class,
                        'start'
                    ])->name('inspections.start');

                    Route::post('/inspections/{id}/complete', [
                        FitoutInspectionController::class,
                        'complete'
                    ])->name('inspections.complete');

                    Route::post('/inspections/{id}/cancel', [
                        FitoutInspectionController::class,
                        'cancel'
                    ])->name('inspections.cancel');

                    Route::post('/inspections/{id}/reschedule', [
                        FitoutInspectionController::class,
                        'reschedule'
                    ])->name('inspections.reschedule');

                    Route::get('/inspections/{id}/reinspection', [
                        FitoutInspectionController::class,
                        'createReinspection'
                    ])->name('inspections.reinspection.create');

                    Route::post('/inspections/{id}/reinspection', [
                        FitoutInspectionController::class,
                        'storeReinspection'
                    ])->name('inspections.reinspection.store');

                    Route::resource(
                        'snags',
                        SnagListController::class
                    )->names('snags');

                    Route::post(
                        'snags/{id}/resolve',
                        [SnagListController::class, 'resolve']
                    )->name('snags.resolve');

                    Route::post(
                        'snags/{id}/verify',
                        [SnagListController::class, 'verify']
                    )->name('snags.verify');

                    Route::post(
                        'snags/{id}/start-verification',
                        [SnagListController::class, 'startVerification']
                    )->name('snags.start-verification');

                    /*Handover*/
                    Route::resource(
                        'handovers',
                        HandoverController::class
                    )->names('handovers');

                    Route::post(
                        'handovers/{handover}/schedule',
                        [HandoverController::class, 'schedule']
                    )->name('handovers.schedule');


                    Route::post(
                        'handovers/{handover}/start',
                        [HandoverController::class, 'start']
                    )->name('handovers.start');


                    Route::post(
                        'handovers/{handover}/tenant-accept',
                        [HandoverController::class, 'tenantAccept']
                    )->name('handovers.tenant-accept');


                    Route::post(
                        'handovers/{handover}/contractor-accept',
                        [HandoverController::class, 'contractorAccept']
                    )->name('handovers.contractor-accept');


                    Route::post(
                        'handovers/{handover}/approve',
                        [HandoverController::class, 'approve']
                    )->name('handovers.approve');


                    Route::post(
                        'handovers/{handover}/complete',
                        [HandoverController::class, 'complete']
                    )->name('handovers.complete');

                    Route::get(
                        'handovers/{handover}/certificate',
                        [HandoverController::class, 'certificate']
                    )->name('handovers.certificate');



            });

            Route::prefix('land')
                ->name('land.')
                ->group(function () {
                    Route::resource(
                        'lands',
                        LandController::class
                    );

                    Route::resource(
                        'opportunities',
                        LandOpportunityController::class
                    );

                    Route::resource(
                        'lands.owners',
                        LandOwnerController::class
                    );
                    Route::resource(
                        'lands.plots',
                        LandPlotController::class
                    );

                    Route::resource(
                        'lands.zonings',
                        LandZoningController::class
                    );

                    Route::resource(
                        'lands.development-rights',
                        LandDevelopmentRightController::class
                    );
                    Route::resource(
                        'lands.acquisition-costs',
                        LandAcquisitionCostController::class
                    );
                    Route::resource(
                        'lands.legal-due-diligences',
                        LandLegalDueDiligenceController::class
                    );

                    Route::resource(
                        'lands.technical-due-diligences',
                        LandTechnicalDueDiligenceController::class
                    )->parameters([
                        'technical-due-diligences' => 'dueDiligence'
                    ]);

                    Route::resource(
                        'lands.environmental-assessments',
                        LandEnvironmentalAssessmentController::class
                    )->parameters([
                        'environmental-assessments' => 'dueDiligence'
                    ]);

                    Route::resource(
                        'lands.documents',
                        LandDocumentController::class
                    )->parameters([
                        'documents' => 'document'
                    ])->only([
                        'index',
                        'create',
                        'store',
                        'show',
                        'destroy'
                    ]);

                    Route::get(
                        'lands/{land}/documents/{document}/download',
                        [
                            LandDocumentController::class,
                            'download'
                        ]
                    )->name(
                        'lands.documents.download'
                    );
                    Route::get(
                        'lands/{land}/acquisition-review',
                        [
                            LandController::class,
                            'acquisitionReview'
                        ]
                    )->name(
                        'lands.acquisition-review'
                    );

                    Route::resource(
                        'lands.feasibility-assessments',
                        FeasibilityAssessmentController::class
                    )->parameters([
                        'feasibility-assessments' => 'feasibilityAssessment'
                    ]);

                    /*Route::resource(
                        'lands.feasibility-assessments.market-studies',
                        MarketStudyController::class
                    )->parameters([
                        'feasibility-assessments' => 'feasibilityAssessment',
                        'market-studies' => 'marketStudy',
                    ]);*/

                    Route::scopeBindings()->group(function () {

                        Route::resource(
                            'lands.feasibility-assessments.market-studies',
                            MarketStudyController::class
                        )->parameters([
                            'lands' => 'land',
                            'feasibility-assessments' => 'feasibilityAssessment',
                            'market-studies' => 'marketStudy',
                        ]);

                        Route::resource(
                            'lands.feasibility-assessments.location-analyses',
                            LocationAnalysisController::class
                        )->parameters([
                            'lands' => 'land',
                            'feasibility-assessments' => 'feasibilityAssessment',
                            'location-analyses' => 'locationAnalysis',
                        ]);

                        Route::resource(
                            'lands.feasibility-assessments.demand-supply-analyses',
                            DemandSupplyAnalysisController::class
                        )->parameters([
                            'lands' => 'land',
                            'feasibility-assessments' => 'feasibilityAssessment',
                            'demand-supply-analyses' => 'demandSupplyAnalysis',
                        ]);

                        Route::resource(
                            'lands.feasibility-assessments.financial-feasibilities',
                            FinancialFeasibilityController::class
                        )->parameters([
                            'lands' => 'land',
                            'feasibility-assessments' => 'feasibilityAssessment',
                            'financial-feasibilities' => 'financialFeasibility',
                        ]);

                        Route::resource(
                            'lands.feasibility-assessments.legal-regulatory-feasibilities',
                            LegalRegulatoryFeasibilityController::class
                        )->parameters([
                            'lands' => 'land',

                            'feasibility-assessments' =>
                                'feasibilityAssessment',

                            'legal-regulatory-feasibilities' =>
                                'legalRegulatoryFeasibility',
                        ]);

                        Route::resource(
                            'lands.feasibility-assessments.technical-feasibilities',
                            TechnicalFeasibilityController::class
                        )->parameters([
                            'lands' => 'land',

                            'feasibility-assessments' =>
                                'feasibilityAssessment',

                            'technical-feasibilities' =>
                                'technicalFeasibility',
                        ]);

                        Route::resource(
                            'lands.feasibility-assessments.environmental-feasibilities',
                            EnvironmentalFeasibilityController::class
                        )->parameters([
                            'lands' => 'land',

                            'feasibility-assessments' =>
                                'feasibilityAssessment',

                            'environmental-feasibilities' =>
                                'environmentalFeasibility',
                        ]);

                        Route::resource(
                            'lands.feasibility-assessments.risk-assessments',
                            RiskAssessmentController::class
                        )->parameters([
                            'lands' => 'land',

                            'feasibility-assessments' =>
                                'feasibilityAssessment',

                            'risk-assessments' =>
                                'riskAssessment',
                        ]);

                        Route::resource(
                            'lands.feasibility-assessments.investment-analyses',
                            InvestmentAnalysisController::class
                        )->parameters([
                            'lands' => 'land',

                            'feasibility-assessments' =>
                                'feasibilityAssessment',

                            'investment-analyses' =>
                                'investmentAnalysis',
                        ]);
                        Route::resource(
                            'lands.feasibility-assessments.investment-decisions',
                            InvestmentDecisionController::class
                        )->parameters([
                            'lands' => 'land',

                            'feasibility-assessments' =>
                                'feasibilityAssessment',

                            'investment-decisions' =>
                                'investmentDecision',
                        ]);


                    });

            });

            Route::prefix('projects')
                ->name('projects.')
                ->group(function () {

                    Route::get('/', [
                        ProjectController::class,
                        'index'
                    ])->name('index');


                    Route::get('/create', [
                        ProjectController::class,
                        'create'
                    ])->name('create');


                    Route::post('/', [
                        ProjectController::class,
                        'store'
                    ])->name('store');


                    Route::get('/{project}', [
                        ProjectController::class,
                        'show'
                    ])->name('show');

                    Route::get(
                        '/{project}/dashboard',
                        [ProjectDashboardController::class, 'index']
                    )->name('dashboard');


                    Route::get('/{project}/edit', [
                        ProjectController::class,
                        'edit'
                    ])->name('edit');


                    Route::put('/{project}', [
                        ProjectController::class,
                        'update'
                    ])->name('update');


                    Route::delete('/{project}', [
                        ProjectController::class,
                        'destroy'
                    ])->name('destroy');


                    /*
                    |--------------------------------------------------------------------------
                    | Development Strategy
                    |--------------------------------------------------------------------------
                    */

                    Route::prefix('{project}/development-strategy')
                        ->name('development-strategy.')
                        ->group(function () {

                            Route::get('/', [
                                DevelopmentStrategyController::class,
                                'index'
                            ])->name('index');

                            Route::get('/create', [
                                DevelopmentStrategyController::class,
                                'create'
                            ])->name('create');

                            Route::post('/', [
                                DevelopmentStrategyController::class,
                                'store'
                            ])->name('store');

                            Route::get('/{developmentStrategy}', [
                                DevelopmentStrategyController::class,
                                'show'
                            ])->name('show');

                            Route::get('/{developmentStrategy}/edit', [
                                DevelopmentStrategyController::class,
                                'edit'
                            ])->name('edit');

                            Route::put('/{developmentStrategy}', [
                                DevelopmentStrategyController::class,
                                'update'
                            ])->name('update');

                            Route::delete('/{developmentStrategy}', [
                                DevelopmentStrategyController::class,
                                'destroy'
                            ])->name('destroy');

                        });

                        /*
                        |--------------------------------------------------------------------------
                        | Master Schedule
                        |--------------------------------------------------------------------------
                        */

                        Route::prefix('{project}/master-schedule')
                            ->name('master-schedule.')
                            ->group(function () {

                                /*
                                |--------------------------------------------------------------------------
                                | Master Schedule
                                |--------------------------------------------------------------------------
                                */

                                Route::get('/', [
                                    MasterScheduleController::class,
                                    'index'
                                ])->name('index');


                                Route::get('/create', [
                                    MasterScheduleController::class,
                                    'create'
                                ])->name('create');


                                Route::post('/', [
                                    MasterScheduleController::class,
                                    'store'
                                ])->name('store');


                                Route::get('/{masterSchedule}', [
                                    MasterScheduleController::class,
                                    'show'
                                ])->name('show');


                                Route::get('/{masterSchedule}/edit', [
                                    MasterScheduleController::class,
                                    'edit'
                                ])->name('edit');


                                Route::put('/{masterSchedule}', [
                                    MasterScheduleController::class,
                                    'update'
                                ])->name('update');


                                Route::delete('/{masterSchedule}', [
                                    MasterScheduleController::class,
                                    'destroy'
                                ])->name('destroy');


                                /*
                                |--------------------------------------------------------------------------
                                | Schedule Activities
                                |--------------------------------------------------------------------------
                                */

                                Route::prefix('{masterSchedule}/activities')
                                    ->name('activities.')
                                    ->group(function () {

                                        Route::post('/', [
                                            MasterScheduleActivityController::class,
                                            'store'
                                        ])->name('store');


                                        Route::get('/{activity}/edit', [
                                            MasterScheduleActivityController::class,
                                            'edit'
                                        ])->name('edit');


                                        Route::put('/{activity}', [
                                            MasterScheduleActivityController::class,
                                            'update'
                                        ])->name('update');


                                        Route::delete('/{activity}', [
                                            MasterScheduleActivityController::class,
                                            'destroy'
                                        ])->name('destroy');

                                    });

                            });

                            /*
                            |--------------------------------------------------------------------------
                            | Budget
                            |--------------------------------------------------------------------------
                            */

                            Route::prefix('{project}/budget')
                                ->name('budget.')
                                ->group(function () {

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Budget
                                    |--------------------------------------------------------------------------
                                    */

                                    Route::get('/', [
                                        ProjectBudgetController::class,
                                        'index'
                                    ])->name('index');


                                    Route::get('/create', [
                                        ProjectBudgetController::class,
                                        'create'
                                    ])->name('create');


                                    Route::post('/', [
                                        ProjectBudgetController::class,
                                        'store'
                                    ])->name('store');


                                    Route::get('/{projectBudget}', [
                                        ProjectBudgetController::class,
                                        'show'
                                    ])->name('show');


                                    Route::get('/{projectBudget}/edit', [
                                        ProjectBudgetController::class,
                                        'edit'
                                    ])->name('edit');


                                    Route::put('/{projectBudget}', [
                                        ProjectBudgetController::class,
                                        'update'
                                    ])->name('update');


                                    Route::delete('/{projectBudget}', [
                                        ProjectBudgetController::class,
                                        'destroy'
                                    ])->name('destroy');

                                    Route::post('/{projectBudget}/revision', [
                                        ProjectBudgetController::class,
                                        'createRevision'
                                    ])->name('revision');


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Budget Categories
                                    |--------------------------------------------------------------------------
                                    */

                                    Route::prefix('{projectBudget}/categories')
                                        ->name('categories.')
                                        ->group(function () {

                                            Route::post('/', [
                                                ProjectBudgetCategoryController::class,
                                                'store'
                                            ])->name('store');


                                            Route::get('/{category}/edit', [
                                                ProjectBudgetCategoryController::class,
                                                'edit'
                                            ])->name('edit');


                                            Route::put('/{category}', [
                                                ProjectBudgetCategoryController::class,
                                                'update'
                                            ])->name('update');


                                            Route::delete('/{category}', [
                                                ProjectBudgetCategoryController::class,
                                                'destroy'
                                            ])->name('destroy');

                                        });


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Budget Items
                                    |--------------------------------------------------------------------------
                                    */

                                    Route::prefix('{projectBudget}/items')
                                        ->name('items.')
                                        ->group(function () {

                                            Route::post('/', [
                                                ProjectBudgetItemController::class,
                                                'store'
                                            ])->name('store');


                                            Route::get('/{item}/edit', [
                                                ProjectBudgetItemController::class,
                                                'edit'
                                            ])->name('edit');


                                            Route::put('/{item}', [
                                                ProjectBudgetItemController::class,
                                                'update'
                                            ])->name('update');


                                            Route::delete('/{item}', [
                                                ProjectBudgetItemController::class,
                                                'destroy'
                                            ])->name('destroy');

                                        });

                                });

            });

            Route::prefix('projects/{project}/funding-plan')
                ->name('projects.funding-plan.')
                ->group(function () {

                    Route::get('/', [
                        ProjectFundingPlanController::class,
                        'index'
                    ])->name('index');

                    Route::get('/create', [
                        ProjectFundingPlanController::class,
                        'create'
                    ])->name('create');

                    Route::post('/', [
                        ProjectFundingPlanController::class,
                        'store'
                    ])->name('store');

                    Route::get('/{fundingPlan}', [
                        ProjectFundingPlanController::class,
                        'show'
                    ])->name('show');

                    Route::get('/{fundingPlan}/edit', [
                        ProjectFundingPlanController::class,
                        'edit'
                    ])->name('edit');

                    Route::put('/{fundingPlan}', [
                        ProjectFundingPlanController::class,
                        'update'
                    ])->name('update');

                    Route::post('/{fundingPlan}/revision', [
                        ProjectFundingPlanController::class,
                        'createRevision'
                    ])->name('revision');

                    Route::delete('/{fundingPlan}', [
                        ProjectFundingPlanController::class,
                        'destroy'
                    ])->name('destroy');

                });
                Route::post(
                    'projects/{project}/funding-plan/{fundingPlan}/submit',
                    [
                        ProjectFundingPlanController::class,
                        'submit'
                    ]
                )->name('projects.funding-plan.submit');


                Route::post(
                    'projects/{project}/funding-plan/{fundingPlan}/approve',
                    [
                        ProjectFundingPlanController::class,
                        'approve'
                    ]
                )->name('projects.funding-plan.approve');


                Route::post(
                    'projects/{project}/funding-plan/{fundingPlan}/reject',
                    [
                        ProjectFundingPlanController::class,
                        'reject'
                    ]
                )->name('projects.funding-plan.reject');


                Route::post(
                    'projects/{project}/funding-plan/{fundingPlan}/revision',
                    [
                        ProjectFundingPlanController::class,
                        'revision'
                    ]
                )->name('projects.funding-plan.revision');

                Route::prefix(
                    'projects/{project}/funding-plan/{fundingPlan}/sources'
                )
                ->name('projects.funding-plan.sources.')
                ->group(function () {

                    Route::get('/create', [
                        ProjectFundingSourceController::class,
                        'create'
                    ])->name('create');

                    Route::post('/', [
                        ProjectFundingSourceController::class,
                        'store'
                    ])->name('store');

                    Route::get('/{fundingSource}/edit', [
                        ProjectFundingSourceController::class,
                        'edit'
                    ])->name('edit');

                    Route::put('/{fundingSource}', [
                        ProjectFundingSourceController::class,
                        'update'
                    ])->name('update');

                    Route::delete('/{fundingSource}', [
                        ProjectFundingSourceController::class,
                        'destroy'
                    ])->name('destroy');

                });

                Route::prefix(
                    'projects/{project}/funding-plan/{fundingPlan}/sources/{fundingSource}/commitments'
                )
                ->name('projects.funding-plan.commitments.')
                ->group(function () {

                    Route::get('/create', [
                        ProjectFundingCommitmentController::class,
                        'create'
                    ])->name('create');

                    Route::post('/', [
                        ProjectFundingCommitmentController::class,
                        'store'
                    ])->name('store');

                    Route::get('/{fundingCommitment}/edit', [
                        ProjectFundingCommitmentController::class,
                        'edit'
                    ])->name('edit');

                    Route::put('/{fundingCommitment}', [
                        ProjectFundingCommitmentController::class,
                        'update'
                    ])->name('update');

                    Route::delete('/{fundingCommitment}', [
                        ProjectFundingCommitmentController::class,
                        'destroy'
                    ])->name('destroy');

                });

                Route::prefix(
                    'projects/{project}/funding-plan/{fundingPlan}/sources/{fundingSource}/commitments/{fundingCommitment}/tranches'
                )
                ->name('projects.funding-plan.tranches.')
                ->group(function () {

                    Route::get('/create', [
                        ProjectFundingTrancheController::class,
                        'create'
                    ])->name('create');

                    Route::post('/', [
                        ProjectFundingTrancheController::class,
                        'store'
                    ])->name('store');

                    Route::get('/{fundingTranche}/edit', [
                        ProjectFundingTrancheController::class,
                        'edit'
                    ])->name('edit');

                    Route::put('/{fundingTranche}', [
                        ProjectFundingTrancheController::class,
                        'update'
                    ])->name('update');

                    Route::delete('/{fundingTranche}', [
                        ProjectFundingTrancheController::class,
                        'destroy'
                    ])->name('destroy');

                });

                Route::prefix('projects/{project}/delivery-strategy')
                    ->name('projects.delivery-strategy.')
                    ->group(function () {

                    Route::get(
                        '/',
                        [
                            ProjectDeliveryStrategyController::class,
                            'index'
                        ]
                    )->name('index');


                    Route::get(
                        '/create',
                        [
                            ProjectDeliveryStrategyController::class,
                            'create'
                        ]
                    )->name('create');


                    Route::post(
                        '/',
                        [
                            ProjectDeliveryStrategyController::class,
                            'store'
                        ]
                    )->name('store');


                    Route::get(
                        '/{deliveryStrategy}',
                        [
                            ProjectDeliveryStrategyController::class,
                            'show'
                        ]
                    )->name('show');


                    Route::get(
                        '/{deliveryStrategy}/edit',
                        [
                            ProjectDeliveryStrategyController::class,
                            'edit'
                        ]
                    )->name('edit');


                    Route::put(
                        '/{deliveryStrategy}',
                        [
                            ProjectDeliveryStrategyController::class,
                            'update'
                        ]
                    )->name('update');


                    Route::post(
                        '/{deliveryStrategy}/submit',
                        [
                            ProjectDeliveryStrategyController::class,
                            'submit'
                        ]
                    )->name('submit');


                    Route::post(
                        '/{deliveryStrategy}/approve',
                        [
                            ProjectDeliveryStrategyController::class,
                            'approve'
                        ]
                    )->name('approve');


                    Route::post(
                        '/{deliveryStrategy}/reject',
                        [
                            ProjectDeliveryStrategyController::class,
                            'reject'
                        ]
                    )->name('reject');


                    Route::post(
                        '/{deliveryStrategy}/revision',
                        [
                            ProjectDeliveryStrategyController::class,
                            'revision'
                        ]
                    )->name('revision');


                    Route::delete(
                        '/{deliveryStrategy}',
                        [
                            ProjectDeliveryStrategyController::class,
                            'destroy'
                        ]
                    )->name('destroy');

                });

                Route::prefix('projects/{project}/procurement-strategy')
                ->name('projects.procurement-strategy.')
                ->group(function () {

                    Route::get(
                        '/',
                        [
                            ProjectProcurementStrategyController::class,
                            'index'
                        ]
                    )->name('index');

                    Route::get(
                        '/create',
                        [
                            ProjectProcurementStrategyController::class,
                            'create'
                        ]
                    )->name('create');

                    Route::post(
                        '/',
                        [
                            ProjectProcurementStrategyController::class,
                            'store'
                        ]
                    )->name('store');

                    Route::get(
                        '/{procurementStrategy}',
                        [
                            ProjectProcurementStrategyController::class,
                            'show'
                        ]
                    )->name('show');

                    Route::get(
                        '/{procurementStrategy}/edit',
                        [
                            ProjectProcurementStrategyController::class,
                            'edit'
                        ]
                    )->name('edit');

                    Route::put(
                        '/{procurementStrategy}',
                        [
                            ProjectProcurementStrategyController::class,
                            'update'
                        ]
                    )->name('update');

                    Route::post(
                        '/{procurementStrategy}/submit',
                        [
                            ProjectProcurementStrategyController::class,
                            'submit'
                        ]
                    )->name('submit');

                    Route::post(
                        '/{procurementStrategy}/approve',
                        [
                            ProjectProcurementStrategyController::class,
                            'approve'
                        ]
                    )->name('approve');

                    Route::post(
                        '/{procurementStrategy}/reject',
                        [
                            ProjectProcurementStrategyController::class,
                            'reject'
                        ]
                    )->name('reject');

                    Route::post(
                        '/{procurementStrategy}/revision',
                        [
                            ProjectProcurementStrategyController::class,
                            'revision'
                        ]
                    )->name('revision');

                    Route::delete(
                        '/{procurementStrategy}',
                        [
                            ProjectProcurementStrategyController::class,
                            'destroy'
                        ]
                    )->name('destroy');

                });

                Route::prefix('projects/{project}/contract-strategy')
                ->name('projects.contract-strategy.')
                ->group(function () {

                    Route::get(
                        '/',
                        [
                            ProjectContractStrategyController::class,
                            'index'
                        ]
                    )->name('index');

                    Route::get(
                        '/create',
                        [
                            ProjectContractStrategyController::class,
                            'create'
                        ]
                    )->name('create');

                    Route::post(
                        '/',
                        [
                            ProjectContractStrategyController::class,
                            'store'
                        ]
                    )->name('store');

                    Route::get(
                        '/{contractStrategy}',
                        [
                            ProjectContractStrategyController::class,
                            'show'
                        ]
                    )->name('show');

                    Route::get(
                        '/{contractStrategy}/edit',
                        [
                            ProjectContractStrategyController::class,
                            'edit'
                        ]
                    )->name('edit');

                    Route::put(
                        '/{contractStrategy}',
                        [
                            ProjectContractStrategyController::class,
                            'update'
                        ]
                    )->name('update');

                    Route::post(
                        '/{contractStrategy}/submit',
                        [
                            ProjectContractStrategyController::class,
                            'submit'
                        ]
                    )->name('submit');

                    Route::post(
                        '/{contractStrategy}/approve',
                        [
                            ProjectContractStrategyController::class,
                            'approve'
                        ]
                    )->name('approve');

                    Route::post(
                        '/{contractStrategy}/reject',
                        [
                            ProjectContractStrategyController::class,
                            'reject'
                        ]
                    )->name('reject');

                    Route::post(
                        '/{contractStrategy}/revision',
                        [
                            ProjectContractStrategyController::class,
                            'revision'
                        ]
                    )->name('revision');

                    Route::delete(
                        '/{contractStrategy}',
                        [
                            ProjectContractStrategyController::class,
                            'destroy'
                        ]
                    )->name('destroy');
                });

                Route::prefix('projects/{project}/risks')
                ->name('projects.risks.')
                ->group(function () {

                    Route::get(
                        '/',
                        [
                            ProjectRiskController::class,
                            'index'
                        ]
                    )->name('index');

                    Route::get(
                        '/create',
                        [
                            ProjectRiskController::class,
                            'create'
                        ]
                    )->name('create');

                    Route::post(
                        '/',
                        [
                            ProjectRiskController::class,
                            'store'
                        ]
                    )->name('store');

                    Route::get(
                        '/{risk}',
                        [
                            ProjectRiskController::class,
                            'show'
                        ]
                    )->name('show');

                    Route::get(
                        '/{risk}/edit',
                        [
                            ProjectRiskController::class,
                            'edit'
                        ]
                    )->name('edit');

                    Route::put(
                        '/{risk}',
                        [
                            ProjectRiskController::class,
                            'update'
                        ]
                    )->name('update');

                    Route::post(
                        '/{risk}/status',
                        [
                            ProjectRiskController::class,
                            'changeStatus'
                        ]
                    )->name('status');

                    Route::delete(
                        '/{risk}',
                        [
                            ProjectRiskController::class,
                            'destroy'
                        ]
                    )->name('destroy');
                });

                Route::prefix('projects/{project}/stakeholders')
                ->name('projects.stakeholders.')
                ->group(function () {

                    Route::get(
                        '/',
                        [
                            ProjectStakeholderController::class,
                            'index'
                        ]
                    )->name('index');

                    Route::get(
                        '/create',
                        [
                            ProjectStakeholderController::class,
                            'create'
                        ]
                    )->name('create');

                    Route::post(
                        '/',
                        [
                            ProjectStakeholderController::class,
                            'store'
                        ]
                    )->name('store');

                    Route::get(
                        '/{stakeholder}',
                        [
                            ProjectStakeholderController::class,
                            'show'
                        ]
                    )->name('show');

                    Route::get(
                        '/{stakeholder}/edit',
                        [
                            ProjectStakeholderController::class,
                            'edit'
                        ]
                    )->name('edit');

                    Route::put(
                        '/{stakeholder}',
                        [
                            ProjectStakeholderController::class,
                            'update'
                        ]
                    )->name('update');

                    Route::post(
                        '/{stakeholder}/status',
                        [
                            ProjectStakeholderController::class,
                            'changeStatus'
                        ]
                    )->name('status');

                    Route::delete(
                        '/{stakeholder}',
                        [
                            ProjectStakeholderController::class,
                            'destroy'
                        ]
                    )->name('destroy');
                });

                Route::prefix('projects/{project}/governance')
                ->name('projects.governance.')
                ->group(function () {

                    Route::get(
                        'follow-up',
                        [
                            ProjectGovernanceFollowUpController::class,
                            'index'
                        ]
                    )->name(
                        'follow-up.index'
                    );

                    Route::get(
                        '/',
                        [
                            ProjectGovernanceController::class,
                            'index'
                        ]
                    )->name('index');

                    Route::get(
                        '/create',
                        [
                            ProjectGovernanceController::class,
                            'create'
                        ]
                    )->name('create');

                    Route::post(
                        '/',
                        [
                            ProjectGovernanceController::class,
                            'store'
                        ]
                    )->name('store');

                    Route::get(
                        '/{governance}',
                        [
                            ProjectGovernanceController::class,
                            'show'
                        ]
                    )->name('show');

                    Route::get(
                        '/{governance}/edit',
                        [
                            ProjectGovernanceController::class,
                            'edit'
                        ]
                    )->name('edit');

                    Route::put(
                        '/{governance}',
                        [
                            ProjectGovernanceController::class,
                            'update'
                        ]
                    )->name('update');

                    Route::post(
                        '/{governance}/status',
                        [
                            ProjectGovernanceController::class,
                            'changeStatus'
                        ]
                    )->name('status');

                    Route::delete(
                        '/{governance}',
                        [
                            ProjectGovernanceController::class,
                            'destroy'
                        ]
                    )->name('destroy');

                    

                });

                Route::prefix('projects/{project}/approval-matrix')
                ->name('projects.approval-matrix.')
                ->group(function () {

                    Route::get(
                        '/',
                        [
                            ProjectApprovalMatrixController::class,
                            'index'
                        ]
                    )->name('index');

                    Route::get(
                        '/create',
                        [
                            ProjectApprovalMatrixController::class,
                            'create'
                        ]
                    )->name('create');

                    Route::post(
                        '/',
                        [
                            ProjectApprovalMatrixController::class,
                            'store'
                        ]
                    )->name('store');

                    Route::get(
                        '/{approvalMatrix}',
                        [
                            ProjectApprovalMatrixController::class,
                            'show'
                        ]
                    )->name('show');

                    Route::get(
                        '/{approvalMatrix}/edit',
                        [
                            ProjectApprovalMatrixController::class,
                            'edit'
                        ]
                    )->name('edit');

                    Route::put(
                        '/{approvalMatrix}',
                        [
                            ProjectApprovalMatrixController::class,
                            'update'
                        ]
                    )->name('update');

                    Route::post(
                        '/{approvalMatrix}/status',
                        [
                            ProjectApprovalMatrixController::class,
                            'changeStatus'
                        ]
                    )->name('status');

                    Route::delete(
                        '/{approvalMatrix}',
                        [
                            ProjectApprovalMatrixController::class,
                            'destroy'
                        ]
                    )->name('destroy');
                });

                Route::prefix('projects/{project}/decision-register')
                ->name('projects.decision-register.')
                ->group(function () {

                    Route::get(
                        '/',
                        [
                            ProjectDecisionRegisterController::class,
                            'index'
                        ]
                    )->name('index');

                    Route::get(
                        '/create',
                        [
                            ProjectDecisionRegisterController::class,
                            'create'
                        ]
                    )->name('create');

                    Route::post(
                        '/',
                        [
                            ProjectDecisionRegisterController::class,
                            'store'
                        ]
                    )->name('store');

                    Route::get(
                        '/{decision}',
                        [
                            ProjectDecisionRegisterController::class,
                            'show'
                        ]
                    )->name('show');

                    Route::get(
                        '/{decision}/edit',
                        [
                            ProjectDecisionRegisterController::class,
                            'edit'
                        ]
                    )->name('edit');

                    Route::put(
                        '/{decision}',
                        [
                            ProjectDecisionRegisterController::class,
                            'update'
                        ]
                    )->name('update');

                    Route::post(
                        '/{decision}/status',
                        [
                            ProjectDecisionRegisterController::class,
                            'changeStatus'
                        ]
                    )->name('status');

                    Route::delete(
                        '/{decision}',
                        [
                            ProjectDecisionRegisterController::class,
                            'destroy'
                        ]
                    )->name('destroy');
                });

                Route::prefix('projects/{project}/governance-meetings')
                ->name('projects.governance-meetings.')
                ->group(function () {

                    Route::get(
                        '/',
                        [
                            ProjectGovernanceMeetingController::class,
                            'index'
                        ]
                    )->name('index');

                    Route::get(
                        '/create',
                        [
                            ProjectGovernanceMeetingController::class,
                            'create'
                        ]
                    )->name('create');

                    Route::post(
                        '/',
                        [
                            ProjectGovernanceMeetingController::class,
                            'store'
                        ]
                    )->name('store');

                    Route::get(
                        '/{meeting}',
                        [
                            ProjectGovernanceMeetingController::class,
                            'show'
                        ]
                    )->name('show');

                    Route::get(
                        '/{meeting}/edit',
                        [
                            ProjectGovernanceMeetingController::class,
                            'edit'
                        ]
                    )->name('edit');

                    Route::put(
                        '/{meeting}',
                        [
                            ProjectGovernanceMeetingController::class,
                            'update'
                        ]
                    )->name('update');

                    Route::post(
                        '/{meeting}/status',
                        [
                            ProjectGovernanceMeetingController::class,
                            'changeStatus'
                        ]
                    )->name('status');

                    Route::delete(
                        '/{meeting}',
                        [
                            ProjectGovernanceMeetingController::class,
                            'destroy'
                        ]
                    )->name('destroy');
                });

                Route::prefix(
                    'projects/{project}/governance-meetings/{meeting}/attendees'
                )
                    ->name('projects.governance-meetings.attendees.')
                    ->group(function () {

                        Route::get(
                            '/',
                            [
                                ProjectGovernanceMeetingAttendeeController::class,
                                'index'
                            ]
                        )->name('index');

                        Route::get(
                            '/create',
                            [
                                ProjectGovernanceMeetingAttendeeController::class,
                                'create'
                            ]
                        )->name('create');

                        Route::post(
                            '/',
                            [
                                ProjectGovernanceMeetingAttendeeController::class,
                                'store'
                            ]
                        )->name('store');

                        Route::get(
                            '/{attendee}/edit',
                            [
                                ProjectGovernanceMeetingAttendeeController::class,
                                'edit'
                            ]
                        )->name('edit');

                        Route::put(
                            '/{attendee}',
                            [
                                ProjectGovernanceMeetingAttendeeController::class,
                                'update'
                            ]
                        )->name('update');

                        Route::post(
                            '/{attendee}/status',
                            [
                                ProjectGovernanceMeetingAttendeeController::class,
                                'changeStatus'
                            ]
                        )->name('status');

                        Route::delete(
                            '/{attendee}',
                            [
                                ProjectGovernanceMeetingAttendeeController::class,
                                'destroy'
                            ]
                        )->name('destroy');
                });

                Route::prefix(
                    'projects/{project}/governance-meetings/{meeting}/agenda-items'
                )
                    ->name('projects.governance-meetings.agenda-items.')
                    ->group(function () {

                        Route::get(
                            '/',
                            [
                                ProjectGovernanceMeetingAgendaItemController::class,
                                'index'
                            ]
                        )->name('index');

                        Route::get(
                            '/create',
                            [
                                ProjectGovernanceMeetingAgendaItemController::class,
                                'create'
                            ]
                        )->name('create');

                        Route::post(
                            '/',
                            [
                                ProjectGovernanceMeetingAgendaItemController::class,
                                'store'
                            ]
                        )->name('store');

                        Route::get(
                            '/{agendaItem}/edit',
                            [
                                ProjectGovernanceMeetingAgendaItemController::class,
                                'edit'
                            ]
                        )->name('edit');

                        Route::put(
                            '/{agendaItem}',
                            [
                                ProjectGovernanceMeetingAgendaItemController::class,
                                'update'
                            ]
                        )->name('update');

                        Route::post(
                            '/{agendaItem}/status',
                            [
                                ProjectGovernanceMeetingAgendaItemController::class,
                                'changeStatus'
                            ]
                        )->name('status');

                        Route::delete(
                            '/{agendaItem}',
                            [
                                ProjectGovernanceMeetingAgendaItemController::class,
                                'destroy'
                            ]
                        )->name('destroy');
                });

                Route::prefix(
                    'projects/{project}/governance-meetings/{meeting}/action-items'
                )
                    ->name('projects.governance-meetings.action-items.')
                    ->group(function () {

                        Route::get(
                            '/',
                            [
                                ProjectGovernanceMeetingActionItemController::class,
                                'index'
                            ]
                        )->name('index');

                        Route::get(
                            '/create',
                            [
                                ProjectGovernanceMeetingActionItemController::class,
                                'create'
                            ]
                        )->name('create');

                        Route::post(
                            '/',
                            [
                                ProjectGovernanceMeetingActionItemController::class,
                                'store'
                            ]
                        )->name('store');

                        Route::get(
                            '/{actionItem}/edit',
                            [
                                ProjectGovernanceMeetingActionItemController::class,
                                'edit'
                            ]
                        )->name('edit');

                        Route::put(
                            '/{actionItem}',
                            [
                                ProjectGovernanceMeetingActionItemController::class,
                                'update'
                            ]
                        )->name('update');

                        Route::post(
                            '/{actionItem}/status',
                            [
                                ProjectGovernanceMeetingActionItemController::class,
                                'changeStatus'
                            ]
                        )->name('status');

                        Route::delete(
                            '/{actionItem}',
                            [
                                ProjectGovernanceMeetingActionItemController::class,
                                'destroy'
                            ]
                        )->name('destroy');
                });

                Route::prefix(
                    'projects/{project}/governance-meetings/{meeting}/decisions'
                )
                    ->name('projects.governance-meetings.decisions.')
                    ->group(function () {

                        Route::get(
                            '/',
                            [
                                ProjectGovernanceMeetingDecisionController::class,
                                'index'
                            ]
                        )->name('index');

                        Route::get(
                            '/create',
                            [
                                ProjectGovernanceMeetingDecisionController::class,
                                'create'
                            ]
                        )->name('create');

                        Route::post(
                            '/',
                            [
                                ProjectGovernanceMeetingDecisionController::class,
                                'store'
                            ]
                        )->name('store');

                        Route::get(
                            '/{decision}/edit',
                            [
                                ProjectGovernanceMeetingDecisionController::class,
                                'edit'
                            ]
                        )->name('edit');

                        Route::put(
                            '/{decision}',
                            [
                                ProjectGovernanceMeetingDecisionController::class,
                                'update'
                            ]
                        )->name('update');

                        Route::post(
                            '/{decision}/status',
                            [
                                ProjectGovernanceMeetingDecisionController::class,
                                'changeStatus'
                            ]
                        )->name('status');

                        Route::delete(
                            '/{decision}',
                            [
                                ProjectGovernanceMeetingDecisionController::class,
                                'destroy'
                            ]
                        )->name('destroy');
                });

                Route::prefix(
                    'projects/{project}/governance-meetings/{meeting}/minutes'
                )
                    ->name('projects.governance-meetings.minutes.')
                    ->group(function () {

                        Route::get(
                            '/',
                            [
                                ProjectGovernanceMeetingMinutesController::class,
                                'index'
                            ]
                        )->name('index');

                        Route::get(
                            '/create',
                            [
                                ProjectGovernanceMeetingMinutesController::class,
                                'create'
                            ]
                        )->name('create');

                        Route::post(
                            '/',
                            [
                                ProjectGovernanceMeetingMinutesController::class,
                                'store'
                            ]
                        )->name('store');

                        Route::get(
                            '/{minutes}/edit',
                            [
                                ProjectGovernanceMeetingMinutesController::class,
                                'edit'
                            ]
                        )->name('edit');

                        Route::put(
                            '/{minutes}',
                            [
                                ProjectGovernanceMeetingMinutesController::class,
                                'update'
                            ]
                        )->name('update');

                        Route::post(
                            '/{minutes}/submit',
                            [
                                ProjectGovernanceMeetingMinutesController::class,
                                'submit'
                            ]
                        )->name('submit');

                        Route::post(
                            '/{minutes}/approve',
                            [
                                ProjectGovernanceMeetingMinutesController::class,
                                'approve'
                            ]
                        )->name('approve');

                        Route::post(
                            '/{minutes}/reject',
                            [
                                ProjectGovernanceMeetingMinutesController::class,
                                'reject'
                            ]
                        )->name('reject');

                        Route::delete(
                            '/{minutes}',
                            [
                                ProjectGovernanceMeetingMinutesController::class,
                                'destroy'
                            ]
                        )->name('destroy');
                });

                Route::prefix(
                    'projects/{project}/governance-meetings/{meeting}/documents'
                )
                    ->name(
                        'projects.governance-meetings.documents.'
                    )
                    ->group(function () {

                        Route::get(
                            '/',
                            [
                                ProjectGovernanceMeetingDocumentController::class,
                                'index'
                            ]
                        )->name('index');


                        Route::get(
                            '/create',
                            [
                                ProjectGovernanceMeetingDocumentController::class,
                                'create'
                            ]
                        )->name('create');


                        Route::post(
                            '/',
                            [
                                ProjectGovernanceMeetingDocumentController::class,
                                'store'
                            ]
                        )->name('store');


                        Route::get(
                            '/{document}/download',
                            [
                                ProjectGovernanceMeetingDocumentController::class,
                                'download'
                            ]
                        )->name('download');


                        Route::delete(
                            '/{document}',
                            [
                                ProjectGovernanceMeetingDocumentController::class,
                                'destroy'
                            ]
                        )->name('destroy');

                        Route::get(
                            '/{document}/preview',
                            [
                                ProjectGovernanceMeetingDocumentController::class,
                                'preview'
                            ]
                        )->name('preview');

                });

                Route::prefix('procurement')
                ->name('procurement.')
                ->group(function () {

                    Route::get(
                        '/plans',
                        [
                            ProcurementPlanController::class,
                            'index',
                        ]
                    )->name('plans.index');


                    Route::get(
                        '/plans/create',
                        [
                            ProcurementPlanController::class,
                            'create',
                        ]
                    )->name('plans.create');


                    Route::post(
                        '/plans',
                        [
                            ProcurementPlanController::class,
                            'store',
                        ]
                    )->name('plans.store');


                    Route::get(
                        '/plans/{procurementPlan}',
                        [
                            ProcurementPlanController::class,
                            'show',
                        ]
                    )->name('plans.show');


                    Route::get(
                        '/plans/{procurementPlan}/edit',
                        [
                            ProcurementPlanController::class,
                            'edit',
                        ]
                    )->name('plans.edit');


                    Route::put(
                        '/plans/{procurementPlan}',
                        [
                            ProcurementPlanController::class,
                            'update',
                        ]
                    )->name('plans.update');


                    Route::delete(
                        '/plans/{procurementPlan}',
                        [
                            ProcurementPlanController::class,
                            'destroy',
                        ]
                    )->name('plans.destroy');

                    Route::get(
                        '/plans/{procurementPlan}/tenders',
                        [
                            ProcurementTenderController::class,
                            'planTenders',
                        ]
                    )->name('plans.tenders.index');

                    Route::get(
                        '/projects/{project}/strategies',
                        [
                            ProcurementPlanController::class,
                            'strategies',
                        ]
                    )->name('projects.strategies');

                });

                Route::prefix('procurement')
                ->name('procurement.')
                ->group(function () {

                    /*
                     * Procurement Plans
                     *
                     * Keep your existing Procurement Plan routes here.
                     */


                    /*
                     * Procurement Packages
                     */
                    Route::get(
                        '/packages',
                        [
                            ProcurementPackageController::class,
                            'index',
                        ]
                    )->name('packages.index');


                    Route::get(
                        '/packages/create',
                        [
                            ProcurementPackageController::class,
                            'create',
                        ]
                    )->name('packages.create');


                    Route::post(
                        '/packages',
                        [
                            ProcurementPackageController::class,
                            'store',
                        ]
                    )->name('packages.store');


                    Route::get(
                        '/packages/{procurementPackage}',
                        [
                            ProcurementPackageController::class,
                            'show',
                        ]
                    )->name('packages.show');


                    Route::get(
                        '/packages/{procurementPackage}/edit',
                        [
                            ProcurementPackageController::class,
                            'edit',
                        ]
                    )->name('packages.edit');


                    Route::put(
                        '/packages/{procurementPackage}',
                        [
                            ProcurementPackageController::class,
                            'update',
                        ]
                    )->name('packages.update');


                    Route::delete(
                        '/packages/{procurementPackage}',
                        [
                            ProcurementPackageController::class,
                            'destroy',
                        ]
                    )->name('packages.destroy');

                    /*
                    |--------------------------------------------------------------------------
                    | Package Tenders
                    |--------------------------------------------------------------------------
                    */

                    Route::get(
                        '/packages/{procurementPackage}/tenders',
                        [
                            ProcurementTenderController::class,
                            'packageTenders',
                        ]
                    )->name('packages.tenders.index');

                    Route::get(
                        '/tenders',
                        [
                            ProcurementTenderController::class,
                            'index',
                        ]
                    )->name('tenders.index');

                    Route::get(
                        '/tenders/create',
                        [
                            ProcurementTenderController::class,
                            'create',
                        ]
                    )->name('tenders.create');

                    Route::post(
                        '/tenders',
                        [
                            ProcurementTenderController::class,
                            'store',
                        ]
                    )->name('tenders.store');

                    Route::get(
                        '/tenders/{procurementTender}',
                        [
                            ProcurementTenderController::class,
                            'show',
                        ]
                    )->name('tenders.show');

                    Route::get(
                        '/tenders/{procurementTender}/edit',
                        [
                            ProcurementTenderController::class,
                            'edit',
                        ]
                    )->name('tenders.edit');

                    Route::put(
                        '/tenders/{procurementTender}',
                        [
                            ProcurementTenderController::class,
                            'update',
                        ]
                    )->name('tenders.update');

                    Route::delete(
                        '/tenders/{procurementTender}',
                        [
                            ProcurementTenderController::class,
                            'destroy',
                        ]
                    )->name('tenders.destroy');


                    Route::get(
                        '/bidders',
                        [
                            ProcurementBidderController::class,
                            'index',
                        ]
                    )->name('bidders.index');

                    Route::get(
                        '/bidders/create',
                        [
                            ProcurementBidderController::class,
                            'create',
                        ]
                    )->name('bidders.create');

                    Route::post(
                        '/bidders',
                        [
                            ProcurementBidderController::class,
                            'store',
                        ]
                    )->name('bidders.store');

                    Route::get(
                        '/bidders/{procurementBidder}',
                        [
                            ProcurementBidderController::class,
                            'show',
                        ]
                    )->name('bidders.show');

                    Route::get(
                        '/bidders/{procurementBidder}/edit',
                        [
                            ProcurementBidderController::class,
                            'edit',
                        ]
                    )->name('bidders.edit');

                    Route::put(
                        '/bidders/{procurementBidder}',
                        [
                            ProcurementBidderController::class,
                            'update',
                        ]
                    )->name('bidders.update');

                    Route::delete(
                        '/bidders/{procurementBidder}',
                        [
                            ProcurementBidderController::class,
                            'destroy',
                        ]
                    )->name('bidders.destroy');

                    Route::get(
                        '/tenders/{procurementTender}/bidders',
                        [
                            ProcurementTenderBidderController::class,
                            'index',
                        ]
                    )->name('tenders.bidders.index');

                    Route::post(
                        '/tenders/{procurementTender}/bidders',
                        [
                            ProcurementTenderBidderController::class,
                            'store',
                        ]
                    )->name('tenders.bidders.store');

                    Route::delete(
                        '/tenders/{procurementTender}/bidders/{tenderBidder}',
                        [
                            ProcurementTenderBidderController::class,
                            'destroy',
                        ]
                    )->name('tenders.bidders.destroy');

                    Route::put(
                        '/tenders/{procurementTender}/bidders/{tenderBidder}',
                        [
                            ProcurementTenderBidderController::class,
                            'update',
                        ]
                    )->name('tenders.bidders.update');

                    Route::get(
                        '/tenders/{procurementTender}/prequalifications',
                        [
                            ProcurementPrequalificationController::class,
                            'index',
                        ]
                    )->name('tenders.prequalifications.index');


                    Route::get(
                        '/tenders/{procurementTender}/prequalifications/create',
                        [
                            ProcurementPrequalificationController::class,
                            'create',
                        ]
                    )->name('tenders.prequalifications.create');


                    Route::post(
                        '/tenders/{procurementTender}/prequalifications',
                        [
                            ProcurementPrequalificationController::class,
                            'store',
                        ]
                    )->name('tenders.prequalifications.store');


                    Route::get(
                        '/tenders/{procurementTender}/prequalifications/{prequalification}',
                        [
                            ProcurementPrequalificationController::class,
                            'show',
                        ]
                    )->name('tenders.prequalifications.show');


                    Route::get(
                        '/tenders/{procurementTender}/prequalifications/{prequalification}/edit',
                        [
                            ProcurementPrequalificationController::class,
                            'edit',
                        ]
                    )->name('tenders.prequalifications.edit');


                    Route::put(
                        '/tenders/{procurementTender}/prequalifications/{prequalification}',
                        [
                            ProcurementPrequalificationController::class,
                            'update',
                        ]
                    )->name('tenders.prequalifications.update');


                    Route::delete(
                        '/tenders/{procurementTender}/prequalifications/{prequalification}',
                        [
                            ProcurementPrequalificationController::class,
                            'destroy',
                        ]
                    )->name('tenders.prequalifications.destroy');

                    Route::post(
                        '/prequalifications/{prequalification}/criteria',
                        [
                            ProcurementPrequalificationCriterionController::class,
                            'store',
                        ]
                    )->name('prequalifications.criteria.store');


                    Route::put(
                        '/prequalifications/{prequalification}/criteria/{criterion}',
                        [
                            ProcurementPrequalificationCriterionController::class,
                            'update',
                        ]
                    )->name('prequalifications.criteria.update');


                    Route::delete(
                        '/prequalifications/{prequalification}/criteria/{criterion}',
                        [
                            ProcurementPrequalificationCriterionController::class,
                            'destroy',
                        ]
                    )->name('prequalifications.criteria.destroy');

                    Route::get(
                        '/tenders/{procurementTender}/documents',
                        [
                            ProcurementTenderDocumentController::class,
                            'index',
                        ]
                    )->name('tenders.documents.index');


                    Route::get(
                        '/tenders/{procurementTender}/documents/create',
                        [
                            ProcurementTenderDocumentController::class,
                            'create',
                        ]
                    )->name('tenders.documents.create');


                    Route::post(
                        '/tenders/{procurementTender}/documents',
                        [
                            ProcurementTenderDocumentController::class,
                            'store',
                        ]
                    )->name('tenders.documents.store');


                    Route::get(
                        '/tenders/{procurementTender}/documents/{document}',
                        [
                            ProcurementTenderDocumentController::class,
                            'show',
                        ]
                    )->name('tenders.documents.show');


                    Route::get(
                        '/tenders/{procurementTender}/documents/{document}/edit',
                        [
                            ProcurementTenderDocumentController::class,
                            'edit',
                        ]
                    )->name('tenders.documents.edit');


                    Route::put(
                        '/tenders/{procurementTender}/documents/{document}',
                        [
                            ProcurementTenderDocumentController::class,
                            'update',
                        ]
                    )->name('tenders.documents.update');


                    Route::delete(
                        '/tenders/{procurementTender}/documents/{document}',
                        [
                            ProcurementTenderDocumentController::class,
                            'destroy',
                        ]
                    )->name('tenders.documents.destroy');

                    Route::get(
                        '/tenders/{procurementTender}/submissions',
                        [
                            ProcurementTenderSubmissionController::class,
                            'index',
                        ]
                    )->name('tenders.submissions.index');


                    Route::get(
                        '/tenders/{procurementTender}/submissions/create',
                        [
                            ProcurementTenderSubmissionController::class,
                            'create',
                        ]
                    )->name('tenders.submissions.create');


                    Route::post(
                        '/tenders/{procurementTender}/submissions',
                        [
                            ProcurementTenderSubmissionController::class,
                            'store',
                        ]
                    )->name('tenders.submissions.store');


                    Route::get(
                        '/tenders/{procurementTender}/submissions/{submission}',
                        [
                            ProcurementTenderSubmissionController::class,
                            'show',
                        ]
                    )->name('tenders.submissions.show');


                    Route::get(
                        '/tenders/{procurementTender}/submissions/{submission}/edit',
                        [
                            ProcurementTenderSubmissionController::class,
                            'edit',
                        ]
                    )->name('tenders.submissions.edit');


                    Route::put(
                        '/tenders/{procurementTender}/submissions/{submission}',
                        [
                            ProcurementTenderSubmissionController::class,
                            'update',
                        ]
                    )->name('tenders.submissions.update');


                    Route::delete(
                        '/tenders/{procurementTender}/submissions/{submission}',
                        [
                            ProcurementTenderSubmissionController::class,
                            'destroy',
                        ]
                    )->name('tenders.submissions.destroy');

                    Route::get(
                        '/tenders/{procurementTender}/technical-evaluations',
                        [
                            ProcurementTechnicalEvaluationController::class,
                            'index',
                        ]
                    )->name('tenders.technical-evaluations.index');


                    Route::get(
                        '/tenders/{procurementTender}/technical-evaluations/create',
                        [
                            ProcurementTechnicalEvaluationController::class,
                            'create',
                        ]
                    )->name('tenders.technical-evaluations.create');


                    Route::post(
                        '/tenders/{procurementTender}/technical-evaluations',
                        [
                            ProcurementTechnicalEvaluationController::class,
                            'store',
                        ]
                    )->name('tenders.technical-evaluations.store');


                    Route::get(
                        '/tenders/{procurementTender}/technical-evaluations/{evaluation}',
                        [
                            ProcurementTechnicalEvaluationController::class,
                            'show',
                        ]
                    )->name('tenders.technical-evaluations.show');


                    Route::get(
                        '/tenders/{procurementTender}/technical-evaluations/{evaluation}/edit',
                        [
                            ProcurementTechnicalEvaluationController::class,
                            'edit',
                        ]
                    )->name('tenders.technical-evaluations.edit');


                    Route::put(
                        '/tenders/{procurementTender}/technical-evaluations/{evaluation}',
                        [
                            ProcurementTechnicalEvaluationController::class,
                            'update',
                        ]
                    )->name('tenders.technical-evaluations.update');


                    Route::delete(
                        '/tenders/{procurementTender}/technical-evaluations/{evaluation}',
                        [
                            ProcurementTechnicalEvaluationController::class,
                            'destroy',
                        ]
                    )->name('tenders.technical-evaluations.destroy');

                    Route::get(
                        '/tenders/{procurementTender}/commercial-evaluations',
                        [
                            ProcurementCommercialEvaluationController::class,
                            'index',
                        ]
                    )->name('tenders.commercial-evaluations.index');


                    Route::get(
                        '/tenders/{procurementTender}/commercial-evaluations/create',
                        [
                            ProcurementCommercialEvaluationController::class,
                            'create',
                        ]
                    )->name('tenders.commercial-evaluations.create');


                    Route::post(
                        '/tenders/{procurementTender}/commercial-evaluations',
                        [
                            ProcurementCommercialEvaluationController::class,
                            'store',
                        ]
                    )->name('tenders.commercial-evaluations.store');


                    Route::get(
                        '/tenders/{procurementTender}/commercial-evaluations/{evaluation}',
                        [
                            ProcurementCommercialEvaluationController::class,
                            'show',
                        ]
                    )->name('tenders.commercial-evaluations.show');


                    Route::get(
                        '/tenders/{procurementTender}/commercial-evaluations/{evaluation}/edit',
                        [
                            ProcurementCommercialEvaluationController::class,
                            'edit',
                        ]
                    )->name('tenders.commercial-evaluations.edit');


                    Route::put(
                        '/tenders/{procurementTender}/commercial-evaluations/{evaluation}',
                        [
                            ProcurementCommercialEvaluationController::class,
                            'update',
                        ]
                    )->name('tenders.commercial-evaluations.update');


                    Route::delete(
                        '/tenders/{procurementTender}/commercial-evaluations/{evaluation}',
                        [
                            ProcurementCommercialEvaluationController::class,
                            'destroy',
                        ]
                    )->name('tenders.commercial-evaluations.destroy');

                    Route::get(
                        '/tenders/{procurementTender}/bid-comparisons',
                        [
                            ProcurementBidComparisonController::class,
                            'index',
                        ]
                    )->name('tenders.bid-comparisons.index');


                    Route::get(
                        '/tenders/{procurementTender}/bid-comparisons/create',
                        [
                            ProcurementBidComparisonController::class,
                            'create',
                        ]
                    )->name('tenders.bid-comparisons.create');


                    Route::post(
                        '/tenders/{procurementTender}/bid-comparisons',
                        [
                            ProcurementBidComparisonController::class,
                            'store',
                        ]
                    )->name('tenders.bid-comparisons.store');


                    Route::get(
                        '/tenders/{procurementTender}/bid-comparisons/{comparison}',
                        [
                            ProcurementBidComparisonController::class,
                            'show',
                        ]
                    )->name('tenders.bid-comparisons.show');


                    Route::get(
                        '/tenders/{procurementTender}/bid-comparisons/{comparison}/edit',
                        [
                            ProcurementBidComparisonController::class,
                            'edit',
                        ]
                    )->name('tenders.bid-comparisons.edit');


                    Route::put(
                        '/tenders/{procurementTender}/bid-comparisons/{comparison}',
                        [
                            ProcurementBidComparisonController::class,
                            'update',
                        ]
                    )->name('tenders.bid-comparisons.update');


                    Route::delete(
                        '/tenders/{procurementTender}/bid-comparisons/{comparison}',
                        [
                            ProcurementBidComparisonController::class,
                            'destroy',
                        ]
                    )->name('tenders.bid-comparisons.destroy');

                    Route::get(
                        '/tenders/{procurementTender}/negotiations',
                        [ProcurementNegotiationController::class, 'index']
                    )->name('tenders.negotiations.index');

                    Route::get(
                        '/tenders/{procurementTender}/negotiations/create',
                        [ProcurementNegotiationController::class, 'create']
                    )->name('tenders.negotiations.create');

                    Route::post(
                        '/tenders/{procurementTender}/negotiations',
                        [ProcurementNegotiationController::class, 'store']
                    )->name('tenders.negotiations.store');

                    Route::get(
                        'tenders/{procurementTender}/negotiations/{negotiation}/edit',
                        [ProcurementNegotiationController::class, 'edit']
                    )->name(
                        'tenders.negotiations.edit'
                    );

                    Route::put(
                        'tenders/{procurementTender}/negotiations/{negotiation}',
                        [ProcurementNegotiationController::class, 'update']
                    )->name(
                        'tenders.negotiations.update'
                    );

                    Route::get(
                        '/tenders/{procurementTender}/negotiations/{negotiation}',
                        [ProcurementNegotiationController::class, 'show']
                    )->name('tenders.negotiations.show');

                    /*
                    |--------------------------------------------------------------------------
                    | Negotiation Rounds
                    |--------------------------------------------------------------------------
                    */

                    Route::get(
                        '/tenders/{procurementTender}/negotiations/{negotiation}/rounds/create',
                        [ProcurementNegotiationController::class, 'createRound']
                    )->name('tenders.negotiations.rounds.create');

                    Route::post(
                        '/tenders/{procurementTender}/negotiations/{negotiation}/rounds',
                        [ProcurementNegotiationController::class, 'storeRound']
                    )->name('tenders.negotiations.rounds.store');

                    Route::get(
                        'tenders/{procurementTender}/negotiations/{negotiation}/rounds/{item}/edit',
                        [
                            ProcurementNegotiationController::class,
                            'editRound',
                        ]
                    )->name(
                        'tenders.negotiations.rounds.edit'
                    );


                    Route::put(
                        'tenders/{procurementTender}/negotiations/{negotiation}/rounds/{item}',
                        [
                            ProcurementNegotiationController::class,
                            'updateRound',
                        ]
                    )->name(
                        'tenders.negotiations.rounds.update'
                    );

                    Route::post(
                        '/tenders/{procurementTender}/negotiations/{negotiation}/finalize',
                        [ProcurementNegotiationController::class, 'finalize']
                    )->name('tenders.negotiations.finalize');

                    Route::post(
                        '/tenders/{procurementTender}/negotiations/{negotiation}/approve',
                        [ProcurementNegotiationController::class, 'approve']
                    )->name('tenders.negotiations.approve');

                    Route::get(
                        '/tenders/{procurementTender}/awards',
                        [ProcurementAwardController::class, 'index']
                    )->name('tenders.awards.index');

                    Route::get(
                        '/tenders/{procurementTender}/awards/create',
                        [ProcurementAwardController::class, 'create']
                    )->name('tenders.awards.create');

                    Route::post(
                        '/tenders/{procurementTender}/awards',
                        [ProcurementAwardController::class, 'store']
                    )->name('tenders.awards.store');

                    Route::get(
                        '/tenders/{procurementTender}/awards/{award}',
                        [ProcurementAwardController::class, 'show']
                    )->name('tenders.awards.show');

                    Route::post(
                        '/tenders/{procurementTender}/awards/{award}/submit',
                        [ProcurementAwardController::class, 'submit']
                    )->name('tenders.awards.submit');

                    Route::post(
                        '/tenders/{procurementTender}/awards/{award}/approve',
                        [ProcurementAwardController::class, 'approve']
                    )->name('tenders.awards.approve');

                    Route::post(
                        '/tenders/{procurementTender}/awards/{award}/issue-loa',
                        [ProcurementAwardController::class, 'issueLoa']
                    )->name('tenders.awards.issue-loa');

                    Route::get(
                        '/tenders/{procurementTender}/contracts',
                        [ProcurementContractController::class, 'index']
                    )->name('tenders.contracts.index');

                    Route::get(
                        '/tenders/{procurementTender}/contracts/create',
                        [ProcurementContractController::class, 'create']
                    )->name('tenders.contracts.create');

                    Route::post(
                        '/tenders/{procurementTender}/contracts',
                        [ProcurementContractController::class, 'store']
                    )->name('tenders.contracts.store');

                    Route::get(
                        '/tenders/{procurementTender}/contracts/{contract}',
                        [ProcurementContractController::class, 'show']
                    )->name('tenders.contracts.show');

                    Route::post(
                        '/tenders/{procurementTender}/contracts/{contract}/submit',
                        [ProcurementContractController::class, 'submit']
                    )->name('tenders.contracts.submit');

                    Route::post(
                        '/tenders/{procurementTender}/contracts/{contract}/approve',
                        [ProcurementContractController::class, 'approve']
                    )->name('tenders.contracts.approve');

                    Route::post(
                        '/tenders/{procurementTender}/contracts/{contract}/activate',
                        [ProcurementContractController::class, 'activate']
                    )->name('tenders.contracts.activate');

                    Route::post(
                        '/tenders/{procurementTender}/contracts/{contract}/close',
                        [ProcurementContractController::class, 'close']
                    )->name('tenders.contracts.close');

                    Route::get(
                        '/tenders/{procurementTender}/contracts/{contract}/milestones',
                        [ProcurementContractMilestoneController::class, 'index']
                    )->name('tenders.contracts.milestones.index');

                    Route::get(
                        '/tenders/{procurementTender}/contracts/{contract}/milestones/create',
                        [ProcurementContractMilestoneController::class, 'create']
                    )->name('tenders.contracts.milestones.create');

                    Route::post(
                        '/tenders/{procurementTender}/contracts/{contract}/milestones',
                        [ProcurementContractMilestoneController::class, 'store']
                    )->name('tenders.contracts.milestones.store');

                    Route::get(
                        '/tenders/{procurementTender}/contracts/{contract}/milestones/{milestone}',
                        [ProcurementContractMilestoneController::class, 'show']
                    )->name('tenders.contracts.milestones.show');

                    Route::post(
                        '/tenders/{procurementTender}/contracts/{contract}/milestones/{milestone}/start',
                        [ProcurementContractMilestoneController::class, 'start']
                    )->name('tenders.contracts.milestones.start');

                    Route::post(
                        '/tenders/{procurementTender}/contracts/{contract}/milestones/{milestone}/complete',
                        [ProcurementContractMilestoneController::class, 'complete']
                    )->name('tenders.contracts.milestones.complete');

                    Route::get(
                        '/tenders/{procurementTender}/contracts/{contract}/milestones/{milestone}/progress',
                        [ProcurementMilestoneProgressController::class, 'index']
                    )->name('tenders.contracts.milestones.progress.index');

                    Route::get(
                        '/tenders/{procurementTender}/contracts/{contract}/milestones/{milestone}/progress/create',
                        [ProcurementMilestoneProgressController::class, 'create']
                    )->name('tenders.contracts.milestones.progress.create');

                    Route::post(
                        '/tenders/{procurementTender}/contracts/{contract}/milestones/{milestone}/progress',
                        [ProcurementMilestoneProgressController::class, 'store']
                    )->name('tenders.contracts.milestones.progress.store');

                    Route::get(
                        '/tenders/{procurementTender}/contracts/{contract}/milestones/{milestone}/documents',
                        [ProcurementMilestoneDocumentController::class, 'index']
                    )->name('tenders.contracts.milestones.documents.index');


                    Route::get(
                        '/tenders/{procurementTender}/contracts/{contract}/milestones/{milestone}/documents/create',
                        [ProcurementMilestoneDocumentController::class, 'create']
                    )->name('tenders.contracts.milestones.documents.create');


                    Route::post(
                        '/tenders/{procurementTender}/contracts/{contract}/milestones/{milestone}/documents',
                        [ProcurementMilestoneDocumentController::class, 'store']
                    )->name('tenders.contracts.milestones.documents.store');


                    Route::post(
                        '/tenders/{procurementTender}/contracts/{contract}/milestones/{milestone}/documents/{document}/verify',
                        [ProcurementMilestoneDocumentController::class, 'verify']
                    )->name('tenders.contracts.milestones.documents.verify');


                    Route::post(
                        '/tenders/{procurementTender}/contracts/{contract}/milestones/{milestone}/documents/{document}/reject',
                        [ProcurementMilestoneDocumentController::class, 'reject']
                    )->name('tenders.contracts.milestones.documents.reject');


                    Route::delete(
                        '/tenders/{procurementTender}/contracts/{contract}/milestones/{milestone}/documents/{document}',
                        [ProcurementMilestoneDocumentController::class, 'destroy']
                    )->name('tenders.contracts.milestones.documents.destroy');

                    Route::get(
                        '/tenders/{procurementTender}/contracts/{contract}/invoices',
                        [ProcurementContractInvoiceController::class, 'index']
                    )->name('tenders.contracts.invoices.index');


                    Route::get(
                        '/tenders/{procurementTender}/contracts/{contract}/milestones/{milestone}/invoice/create',
                        [ProcurementContractInvoiceController::class, 'create']
                    )->name('tenders.contracts.milestones.invoice.create');


                    Route::post(
                        '/tenders/{procurementTender}/contracts/{contract}/milestones/{milestone}/invoice',
                        [ProcurementContractInvoiceController::class, 'store']
                    )->name('tenders.contracts.milestones.invoice.store');


                    Route::get(
                        '/tenders/{procurementTender}/contracts/{contract}/invoices/{invoice}',
                        [ProcurementContractInvoiceController::class, 'show']
                    )->name('tenders.contracts.invoices.show');


                    Route::post(
                        '/tenders/{procurementTender}/contracts/{contract}/invoices/{invoice}/submit',
                        [ProcurementContractInvoiceController::class, 'submit']
                    )->name('tenders.contracts.invoices.submit');


                    Route::post(
                        '/tenders/{procurementTender}/contracts/{contract}/invoices/{invoice}/approve',
                        [ProcurementContractInvoiceController::class, 'approve']
                    )->name('tenders.contracts.invoices.approve');


                    Route::post(
                        '/tenders/{procurementTender}/contracts/{contract}/invoices/{invoice}/reject',
                        [ProcurementContractInvoiceController::class, 'reject']
                    )->name('tenders.contracts.invoices.reject');


                    Route::get(
                        '/tenders/{procurementTender}/contracts/{contract}/payments',
                        [ProcurementContractPaymentController::class, 'index']
                    )->name('tenders.contracts.payments.index');


                    Route::get(
                        '/tenders/{procurementTender}/contracts/{contract}/invoices/{invoice}/payment/create',
                        [ProcurementContractPaymentController::class, 'create']
                    )->name('tenders.contracts.invoices.payment.create');


                    Route::post(
                        '/tenders/{procurementTender}/contracts/{contract}/invoices/{invoice}/payment',
                        [ProcurementContractPaymentController::class, 'store']
                    )->name('tenders.contracts.invoices.payment.store');


                    Route::get(
                        '/tenders/{procurementTender}/contracts/{contract}/payments/{payment}',
                        [ProcurementContractPaymentController::class, 'show']
                    )->name('tenders.contracts.payments.show');


                    Route::post(
                        '/tenders/{procurementTender}/contracts/{contract}/payments/{payment}/submit',
                        [ProcurementContractPaymentController::class, 'submit']
                    )->name('tenders.contracts.payments.submit');


                    Route::post(
                        '/tenders/{procurementTender}/contracts/{contract}/payments/{payment}/approve',
                        [ProcurementContractPaymentController::class, 'approve']
                    )->name('tenders.contracts.payments.approve');


                    Route::post(
                        '/tenders/{procurementTender}/contracts/{contract}/payments/{payment}/reject',
                        [ProcurementContractPaymentController::class, 'reject']
                    )->name('tenders.contracts.payments.reject');


                    Route::post(
                        '/tenders/{procurementTender}/contracts/{contract}/payments/{payment}/process',
                        [ProcurementContractPaymentController::class, 'process']
                    )->name('tenders.contracts.payments.process');


                    /*
                    |--------------------------------------------------------------------------
                    | Purchase Orders
                    |--------------------------------------------------------------------------
                    */

                    Route::prefix('tenders/{procurementTender}/purchase-orders')
                        ->name('tenders.purchase-orders.')
                        ->group(function () {

                            Route::get(
                                '/',
                                [ProcurementPurchaseOrderController::class, 'index']
                            )->name('index');

                            Route::get(
                                '/create',
                                [ProcurementPurchaseOrderController::class, 'create']
                            )->name('create');

                            Route::post(
                                '/',
                                [ProcurementPurchaseOrderController::class, 'store']
                            )->name('store');

                            Route::get(
                                '/{purchaseOrder}',
                                [ProcurementPurchaseOrderController::class, 'show']
                            )->name('show');

                            Route::post(
                                '/{purchaseOrder}/submit',
                                [ProcurementPurchaseOrderController::class, 'submit']
                            )->name('submit');

                            Route::post(
                                '/{purchaseOrder}/approve',
                                [ProcurementPurchaseOrderController::class, 'approve']
                            )->name('approve');

                            Route::post(
                                '/{purchaseOrder}/issue',
                                [ProcurementPurchaseOrderController::class, 'issue']
                            )->name('issue');

                            Route::delete(
                                '/{purchaseOrder}',
                                [ProcurementPurchaseOrderController::class, 'destroy']
                            )->name('destroy');
                        });

                        /*
                        |--------------------------------------------------------------------------
                        | Procurement Deliveries
                        |--------------------------------------------------------------------------
                        */

                        Route::prefix('tenders/{procurementTender}/purchase-orders/{purchaseOrder}/deliveries')
                            ->name('tenders.purchase-orders.deliveries.')
                            ->group(function () {

                                Route::get(
                                    '/',
                                    [ProcurementDeliveryController::class, 'index']
                                )->name('index');

                                Route::get(
                                    '/create',
                                    [ProcurementDeliveryController::class, 'create']
                                )->name('create');

                                Route::post(
                                    '/',
                                    [ProcurementDeliveryController::class, 'store']
                                )->name('store');

                                Route::get(
                                    '/{delivery}',
                                    [ProcurementDeliveryController::class, 'show']
                                )->name('show');

                        });

                            /*
                            |--------------------------------------------------------------------------
                            | Procurement Material Tracking
                            |--------------------------------------------------------------------------
                            */

                        Route::prefix(
                            'tenders/{procurementTender}/purchase-orders/{purchaseOrder}/material-trackings'
                        )
                            ->name(
                                'tenders.purchase-orders.material-trackings.'
                            )
                            ->group(function () {

                                /*
                                |--------------------------------------------------------------------------
                                | Material Tracking List
                                |--------------------------------------------------------------------------
                                */

                                Route::get(
                                    '/',
                                    [
                                        ProcurementMaterialTrackingController::class,
                                        'index'
                                    ]
                                )->name('index');


                                /*
                                |--------------------------------------------------------------------------
                                | Create Material Tracking
                                |--------------------------------------------------------------------------
                                */

                            Route::get(
                                    '/create',
                                    [
                                        ProcurementMaterialTrackingController::class,
                                        'create'
                                    ]
                                )->name('create');


                                /*
                                |--------------------------------------------------------------------------
                                | Store Material Tracking
                                |--------------------------------------------------------------------------
                                */

                                Route::post(
                                    '/',
                                    [
                                        ProcurementMaterialTrackingController::class,
                                        'store'
                                    ]
                                )->name('store');


                                /*
                                |--------------------------------------------------------------------------
                                | View Material Tracking
                                |--------------------------------------------------------------------------
                                */

                                Route::get(
                                    '/{materialTracking}',
                                    [
                                        ProcurementMaterialTrackingController::class,
                                        'show'
                                    ]
                                )->name('show');

                        });


                });

                Route::prefix('projects/{project}/procurement')
                ->name('projects.procurement.')
                ->group(function () {

                    Route::get(
                        '/performance',
                        [ProcurementPerformanceController::class, 'index']
                    )->name('performance');

                    Route::get(
                        '/performance/plans',
                        [ProcurementPlanPerformanceController::class, 'index']
                    )->name('performance.plans');

                });

                Route::get(
                    'procurement/packages/budgets/{procurementPlan}',
                    [
                        ProcurementPackageController::class,
                        'getBudgetsByPlan'
                    ]
                )->name(
                    'admin.procurement.packages.budgets'
                );

                Route::post(
                    'procurement/tenders/{procurementTender}/contracts/{contract}/payments',
                    [ProcurementContractPaymentController::class, 'store']
                )->name(
                    'procurement.tenders.contracts.payments.store'
                );

                Route::get(
                    'projects/{project}/construction',
                    [ConstructionDashboardController::class, 'index']
                )->name(
                    'projects.construction.dashboard'
                );

                Route::get(
                    'projects/{project}/construction/contractors',
                    [
                        ConstructionContractorController::class,
                        'index',
                    ]
                )->name(
                    'projects.construction.contractors.index'
                );


                Route::prefix(
                    'projects/{project}/construction/consultants'
                )
                ->name(
                    'projects.construction.consultants.'
                )
                ->group(function () {

                    Route::get(
                        '/',
                        [
                            ConstructionConsultantController::class,
                            'index',
                        ]
                    )->name('index');


                    Route::get(
                        '/create',
                        [
                            ConstructionConsultantController::class,
                            'create',
                        ]
                    )->name('create');


                    Route::post(
                        '/',
                        [
                            ConstructionConsultantController::class,
                            'store',
                        ]
                    )->name('store');


                    Route::get(
                        '/{consultant}/edit',
                        [
                            ConstructionConsultantController::class,
                            'edit',
                        ]
                    )->name('edit');


                    Route::put(
                        '/{consultant}',
                        [
                            ConstructionConsultantController::class,
                            'update',
                        ]
                    )->name('update');


                    Route::delete(
                        '/{consultant}',
                        [
                            ConstructionConsultantController::class,
                            'destroy',
                        ]
                    )->name('destroy');

                    Route::get(
                        '/{consultant}',
                        [ConstructionConsultantController::class, 'show']
                    )->name(
                        'show'
                    );

                });

                Route::get(
                    'projects/{project}/construction/contracts',
                    [
                        ConstructionContractController::class,
                        'index',
                    ]
                )->name(
                    'projects.construction.contracts.index'
                );

                Route::prefix(
                    'projects/{project}/construction/work-orders'
                )
                ->name(
                    'projects.construction.work-orders.'
                )
                ->group(function () {

                    Route::get(
                        '/',
                        [
                            ConstructionWorkOrderController::class,
                            'index',
                        ]
                    )->name('index');


                    Route::get(
                        '/create',
                        [
                            ConstructionWorkOrderController::class,
                            'create',
                        ]
                    )->name('create');


                    Route::post(
                        '/',
                        [
                            ConstructionWorkOrderController::class,
                            'store',
                        ]
                    )->name('store');

                });

                Route::prefix(
                    'projects/{project}/construction/progress'
                )
                ->name(
                    'projects.construction.progress.'
                )
                ->group(function () {

                    Route::get(
                        '/',
                        [
                            ConstructionProgressController::class,
                            'index',
                        ]
                    )->name('index');


                    Route::get(
                        '/create',
                        [
                            ConstructionProgressController::class,
                            'create',
                        ]
                    )->name('create');


                    Route::post(
                        '/',
                        [
                            ConstructionProgressController::class,
                            'store',
                        ]
                    )->name('store');


                    Route::get(
                        '/{progress}',
                        [
                            ConstructionProgressController::class,
                            'show',
                        ]
                    )->name('show');


                    Route::get(
                        '/{progress}/edit',
                        [
                            ConstructionProgressController::class,
                            'edit',
                        ]
                    )->name('edit');


                    Route::put(
                        '/{progress}',
                        [
                            ConstructionProgressController::class,
                            'update',
                        ]
                    )->name('update');


                    Route::delete(
                        '/{progress}',
                        [
                            ConstructionProgressController::class,
                            'destroy',
                        ]
                    )->name('destroy');

                });

                Route::prefix(
                    'projects/{project}/construction/site-issues'
                )
                ->name(
                    'projects.construction.site-issues.'
                )
                ->group(function () {

                    Route::get(
                        '/',
                        [
                            ConstructionSiteIssueController::class,
                            'index',
                        ]
                    )->name('index');


                    Route::get(
                        '/create',
                        [
                            ConstructionSiteIssueController::class,
                            'create',
                        ]
                    )->name('create');


                    Route::post(
                        '/',
                        [
                            ConstructionSiteIssueController::class,
                            'store',
                        ]
                    )->name('store');


                    Route::get(
                        '/{issue}',
                        [
                            ConstructionSiteIssueController::class,
                            'show',
                        ]
                    )->name('show');


                    Route::get(
                        '/{issue}/edit',
                        [
                            ConstructionSiteIssueController::class,
                            'edit',
                        ]
                    )->name('edit');


                    Route::put(
                        '/{issue}',
                        [
                            ConstructionSiteIssueController::class,
                            'update',
                        ]
                    )->name('update');


                    Route::delete(
                        '/{issue}',
                        [
                            ConstructionSiteIssueController::class,
                            'destroy',
                        ]
                    )->name('destroy');
                });

                Route::prefix(
                    'projects/{project}/construction/site-reports'
                )
                ->name(
                    'projects.construction.site-reports.'
                )
                ->group(function () {

                    Route::get(
                        '/',
                        [
                            ConstructionSiteReportController::class,
                            'index',
                        ]
                    )->name('index');


                    Route::get(
                        '/create',
                        [
                            ConstructionSiteReportController::class,
                            'create',
                        ]
                    )->name('create');


                    Route::post(
                        '/',
                        [
                            ConstructionSiteReportController::class,
                            'store',
                        ]
                    )->name('store');


                    Route::get(
                        '/{report}',
                        [
                            ConstructionSiteReportController::class,
                            'show',
                        ]
                    )->name('show');


                    Route::get(
                        '/{report}/edit',
                        [
                            ConstructionSiteReportController::class,
                            'edit',
                        ]
                    )->name('edit');


                    Route::put(
                        '/{report}',
                        [
                            ConstructionSiteReportController::class,
                            'update',
                        ]
                    )->name('update');


                    Route::delete(
                        '/{report}',
                        [
                            ConstructionSiteReportController::class,
                            'destroy',
                        ]
                    )->name('destroy');


                    Route::post(
                        '/{report}/submit',
                        [
                            ConstructionSiteReportController::class,
                            'submit',
                        ]
                    )->name('submit');
                });

                Route::prefix(
                    'projects/{project}/construction/schedule'
                )
                ->name(
                    'projects.construction.schedule.'
                )
                ->group(function () {

                    Route::get(
                        '/',
                        [
                            ConstructionScheduleActivityController::class,
                            'index',
                        ]
                    )->name('index');


                    Route::get(
                        '/create',
                        [
                            ConstructionScheduleActivityController::class,
                            'create',
                        ]
                    )->name('create');


                    Route::post(
                        '/',
                        [
                            ConstructionScheduleActivityController::class,
                            'store',
                        ]
                    )->name('store');


                    Route::get(
                        '/{activity}',
                        [
                            ConstructionScheduleActivityController::class,
                            'show',
                        ]
                    )->name('show');


                    Route::get(
                        '/{activity}/edit',
                        [
                            ConstructionScheduleActivityController::class,
                            'edit',
                        ]
                    )->name('edit');


                    Route::put(
                        '/{activity}',
                        [
                            ConstructionScheduleActivityController::class,
                            'update',
                        ]
                    )->name('update');


                    Route::delete(
                        '/{activity}',
                        [
                            ConstructionScheduleActivityController::class,
                            'destroy',
                        ]
                    )->name('destroy');
                });

                /*Route::prefix(
                    'projects/{project}/construction/progress'
                )
                ->name(
                    'projects.construction.progress.'
                )
                ->group(function () {

                    Route::get(
                        '/',
                        [
                            ConstructionProgressEntryController::class,
                            'index',
                        ]
                    )->name('index');


                    Route::get(
                        '/create',
                        [
                            ConstructionProgressEntryController::class,
                            'create',
                        ]
                    )->name('create');


                    Route::post(
                        '/',
                        [
                            ConstructionProgressEntryController::class,
                            'store',
                        ]
                    )->name('store');


                    Route::get(
                        '/{progress}',
                        [
                            ConstructionProgressEntryController::class,
                            'show',
                        ]
                    )->name('show');


                    Route::get(
                        '/{progress}/edit',
                        [
                            ConstructionProgressEntryController::class,
                            'edit',
                        ]
                    )->name('edit');


                    Route::put(
                        '/{progress}',
                        [
                            ConstructionProgressEntryController::class,
                            'update',
                        ]
                    )->name('update');


                    Route::delete(
                        '/{progress}',
                        [
                            ConstructionProgressEntryController::class,
                            'destroy',
                        ]
                    )->name('destroy');
                });*/

                Route::prefix(
                    'projects/{project}/construction/other-costs'
                )
                ->name(
                    'projects.construction.other-costs.'
                )
                ->group(function () {

                    Route::get(
                        '/',
                        [
                            ConstructionOtherCostController::class,
                            'index',
                        ]
                    )->name('index');


                    Route::get(
                        '/create',
                        [
                            ConstructionOtherCostController::class,
                            'create',
                        ]
                    )->name('create');


                    Route::post(
                        '/',
                        [
                            ConstructionOtherCostController::class,
                            'store',
                        ]
                    )->name('store');


                    Route::get(
                        '/{cost}',
                        [
                            ConstructionOtherCostController::class,
                            'show',
                        ]
                    )->name('show');


                    Route::get(
                        '/{cost}/edit',
                        [
                            ConstructionOtherCostController::class,
                            'edit',
                        ]
                    )->name('edit');


                    Route::put(
                        '/{cost}',
                        [
                            ConstructionOtherCostController::class,
                            'update',
                        ]
                    )->name('update');


                    Route::delete(
                        '/{cost}',
                        [
                            ConstructionOtherCostController::class,
                            'destroy',
                        ]
                    )->name('destroy');
                });

                Route::prefix(
                    'projects/{project}/construction/cost-control'
                )
                ->name(
                    'projects.construction.cost-control.'
                )
                ->group(function () {

                    Route::get(
                        '/',
                        [
                            ConstructionCostControlController::class,
                            'index',
                        ]
                    )->name('index');

                     /*
                    |--------------------------------------------------------------------------
                    | Drill Down
                    |--------------------------------------------------------------------------
                    */

                    Route::get(
                        '/contracts',
                        [
                            ConstructionCostControlController::class,
                            'contracts'
                        ]
                    )->name('contracts');


                    Route::get(
                        '/variations',
                        [
                            ConstructionCostControlController::class,
                            'variations'
                        ]
                    )->name('variations');


                    Route::get(
                        '/invoices',
                        [
                            ConstructionCostControlController::class,
                            'invoices'
                        ]
                    )->name('invoices');


                    Route::get(
                        '/payments',
                        [
                            ConstructionCostControlController::class,
                            'payments'
                        ]
                    )->name('payments');


                    Route::get(
                        '/other-costs',
                        [
                            ConstructionCostControlController::class,
                            'otherCosts'
                        ]
                    )->name('other-costs');

                    Route::get(
                        '/report',
                        [
                            ConstructionCostControlController::class,
                            'report',
                        ]
                    )->name('report');

                });

                Route::prefix(
                    'projects/{project}/construction/variations'
                )
                ->name(
                    'projects.construction.variations.'
                )
                ->group(function () {

                    Route::get(
                        '/',
                        [
                            ConstructionVariationController::class,
                            'index',
                        ]
                    )->name('index');


                    Route::get(
                        '/create',
                        [
                            ConstructionVariationController::class,
                            'create',
                        ]
                    )->name('create');


                    Route::post(
                        '/',
                        [
                            ConstructionVariationController::class,
                            'store',
                        ]
                    )->name('store');


                    Route::get(
                        '/{variation}',
                        [
                            ConstructionVariationController::class,
                            'show',
                        ]
                    )->name('show');


                    Route::get(
                        '/{variation}/edit',
                        [
                            ConstructionVariationController::class,
                            'edit',
                        ]
                    )->name('edit');


                    Route::put(
                        '/{variation}',
                        [
                            ConstructionVariationController::class,
                            'update',
                        ]
                    )->name('update');


                    Route::delete(
                        '/{variation}',
                        [
                            ConstructionVariationController::class,
                            'destroy',
                        ]
                    )->name('destroy');

                    Route::post(
                        '/{variation}/submit',
                        [
                            ConstructionVariationController::class,
                            'submit',
                        ]
                    )->name('submit');


                    Route::post(
                        '/{variation}/approve',
                        [
                            ConstructionVariationController::class,
                            'approve',
                        ]
                    )->name('approve');


                    Route::post(
                        '/{variation}/reject',
                        [
                            ConstructionVariationController::class,
                            'reject',
                        ]
                    )->name('reject');

                });

                Route::prefix(
                    'projects/{project}/construction/site-instructions'
                )
                ->name(
                    'projects.construction.site-instructions.'
                )
                ->group(function () {

                    Route::get(
                        '/',
                        [
                            SiteInstructionController::class,
                            'index',
                        ]
                    )->name('index');


                    Route::get(
                        '/create',
                        [
                            SiteInstructionController::class,
                            'create',
                        ]
                    )->name('create');


                    Route::post(
                        '/',
                        [
                            SiteInstructionController::class,
                            'store',
                        ]
                    )->name('store');


                    Route::get(
                        '/{siteInstruction}',
                        [
                            SiteInstructionController::class,
                            'show',
                        ]
                    )->name('show');


                    Route::get(
                        '/{siteInstruction}/edit',
                        [
                            SiteInstructionController::class,
                            'edit',
                        ]
                    )->name('edit');


                    Route::put(
                        '/{siteInstruction}',
                        [
                            SiteInstructionController::class,
                            'update',
                        ]
                    )->name('update');


                    Route::post(
                        '/{siteInstruction}/issue',
                        [
                            SiteInstructionController::class,
                            'issue',
                        ]
                    )->name('issue');


                    Route::post(
                        '/{siteInstruction}/acknowledge',
                        [
                            SiteInstructionController::class,
                            'acknowledge',
                        ]
                    )->name('acknowledge');


                    Route::post(
                        '/{siteInstruction}/start',
                        [
                            SiteInstructionController::class,
                            'start',
                        ]
                    )->name('start');


                    Route::post(
                        '/{siteInstruction}/comply',
                        [
                            SiteInstructionController::class,
                            'comply',
                        ]
                    )->name('comply');


                    Route::post(
                        '/{siteInstruction}/close',
                        [
                            SiteInstructionController::class,
                            'close',
                        ]
                    )->name('close');


                    Route::post(
                        '/{siteInstruction}/cancel',
                        [
                            SiteInstructionController::class,
                            'cancel',
                        ]
                    )->name('cancel');


                    Route::delete(
                        '/{siteInstruction}',
                        [
                            SiteInstructionController::class,
                            'destroy',
                        ]
                    )->name('destroy');

                });

                Route::prefix('projects/{project}/construction')
                ->name('projects.construction.')
                ->group(function () {

                    Route::resource(
                        'submittals',
                        ConstructionSubmittalController::class
                    );
                    Route::post(
                        'submittals/{submittal}/submit',
                        [ConstructionSubmittalController::class, 'submit']
                    )->name(
                        'submittals.submit'
                    );


                    Route::post(
                        'submittals/{submittal}/start-review',
                        [ConstructionSubmittalController::class, 'startReview']
                    )->name(
                        'submittals.start-review'
                    );


                    Route::post(
                        'submittals/{submittal}/approve',
                        [ConstructionSubmittalController::class, 'approve']
                    )->name(
                        'submittals.approve'
                    );


                    Route::post(
                        'submittals/{submittal}/approve-with-comments',
                        [ConstructionSubmittalController::class, 'approveWithComments']
                    )->name(
                        'submittals.approve-with-comments'
                    );


                    Route::post(
                        'submittals/{submittal}/revise',
                        [ConstructionSubmittalController::class, 'revise']
                    )->name(
                        'submittals.revise'
                    );


                    Route::post(
                        'submittals/{submittal}/reject',
                        [ConstructionSubmittalController::class, 'reject']
                    )->name(
                        'submittals.reject'
                    );

                    Route::resource(
                        'inspections',
                        ConstructionInspectionController::class
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Inspection Workflow
                    |--------------------------------------------------------------------------
                    */

                    Route::post(
                        'inspections/{inspection}/schedule',
                        [ConstructionInspectionController::class, 'schedule']
                    )->name(
                        'inspections.schedule'
                    );


                    Route::post(
                        'inspections/{inspection}/conduct',
                        [ConstructionInspectionController::class, 'conduct']
                    )->name(
                        'inspections.conduct'
                    );


                    Route::post(
                        'inspections/{inspection}/pass',
                        [ConstructionInspectionController::class, 'pass']
                    )->name(
                        'inspections.pass'
                    );


                    Route::post(
                        'inspections/{inspection}/fail',
                        [ConstructionInspectionController::class, 'fail']
                    )->name(
                        'inspections.fail'
                    );


                    Route::post(
                        'inspections/{inspection}/corrective-action',
                        [ConstructionInspectionController::class, 'correctiveAction']
                    )->name(
                        'inspections.corrective-action'
                    );


                    Route::post(
                        'inspections/{inspection}/reinspection',
                        [ConstructionInspectionController::class, 'reinspection']
                    )->name(
                        'inspections.reinspection'
                    );


                    Route::post(
                        'inspections/{inspection}/close',
                        [ConstructionInspectionController::class, 'close']
                    )->name(
                        'inspections.close'
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Quality - ITP
                    |--------------------------------------------------------------------------
                    */

                    Route::resource(
                        'quality/itps',
                        ConstructionQualityItpController::class
                    )->names([
                        'index'   => 'quality.itps.index',
                        'create'  => 'quality.itps.create',
                        'store'   => 'quality.itps.store',
                        'show'    => 'quality.itps.show',
                        'edit'    => 'quality.itps.edit',
                        'update'  => 'quality.itps.update',
                        'destroy' => 'quality.itps.destroy',
                    ])
                    ->parameters([
                        'itps' => 'itp',
                    ]);


                    /*
                    |--------------------------------------------------------------------------
                    | ITP Workflow
                    |--------------------------------------------------------------------------
                    */

                    Route::post(
                        'quality/itps/{itp}/submit',
                        [ConstructionQualityItpController::class, 'submit']
                    )->name(
                        'quality.itps.submit'
                    );


                    Route::post(
                        'quality/itps/{itp}/start-review',
                        [ConstructionQualityItpController::class, 'startReview']
                    )->name(
                        'quality.itps.start-review'
                    );


                    Route::post(
                        'quality/itps/{itp}/approve',
                        [ConstructionQualityItpController::class, 'approve']
                    )->name(
                        'quality.itps.approve'
                    );


                    Route::post(
                        'quality/itps/{itp}/reject',
                        [ConstructionQualityItpController::class, 'reject']
                    )->name(
                        'quality.itps.reject'
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | ITP Items
                    |--------------------------------------------------------------------------
                    */

                    Route::post(
                        'quality/itps/{itp}/items',
                        [ConstructionQualityItpController::class, 'addItem']
                    )->name(
                        'quality.itps.items.store'
                    );


                    Route::put(
                        'quality/itps/{itp}/items/{item}',
                        [ConstructionQualityItpController::class, 'updateItem']
                    )->name(
                        'quality.itps.items.update'
                    );


                    Route::delete(
                        'quality/itps/{itp}/items/{item}',
                        [ConstructionQualityItpController::class, 'deleteItem']
                    )->name(
                        'quality.itps.items.destroy'
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Quality - NCR
                    |--------------------------------------------------------------------------
                    */

                    Route::resource(
                        'quality/ncrs',
                        ConstructionQualityNcrController::class
                    )
                    ->names([
                        'index'   => 'quality.ncrs.index',
                        'create'  => 'quality.ncrs.create',
                        'store'   => 'quality.ncrs.store',
                        'show'    => 'quality.ncrs.show',
                        'edit'    => 'quality.ncrs.edit',
                        'update'  => 'quality.ncrs.update',
                        'destroy' => 'quality.ncrs.destroy',
                    ])
                    ->parameters([
                        'ncrs' => 'ncr',
                    ]);


                    /*
                    |--------------------------------------------------------------------------
                    | NCR Workflow
                    |--------------------------------------------------------------------------
                    */

                    Route::post(
                        'quality/ncrs/{ncr}/submit',
                        [ConstructionQualityNcrController::class, 'submit']
                    )->name(
                        'quality.ncrs.submit'
                    );


                    Route::post(
                        'quality/ncrs/{ncr}/start-review',
                        [ConstructionQualityNcrController::class, 'startReview']
                    )->name(
                        'quality.ncrs.start-review'
                    );


                    Route::post(
                        'quality/ncrs/{ncr}/require-corrective-action',
                        [ConstructionQualityNcrController::class, 'requireCorrectiveAction']
                    )->name(
                        'quality.ncrs.require-corrective-action'
                    );


                    Route::post(
                        'quality/ncrs/{ncr}/start-verification',
                        [ConstructionQualityNcrController::class, 'startVerification']
                    )->name(
                        'quality.ncrs.start-verification'
                    );


                    Route::post(
                        'quality/ncrs/{ncr}/verify',
                        [ConstructionQualityNcrController::class, 'verify']
                    )->name(
                        'quality.ncrs.verify'
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | NCR Corrective Action
                    |--------------------------------------------------------------------------
                    */

                    Route::post(
                        'quality/ncrs/{ncr}/submit-corrective-action',
                        [
                            ConstructionQualityNcrController::class,
                            'submitCorrectiveAction'
                        ]
                    )->name(
                        'quality.ncrs.submit-corrective-action'
                    );


                    Route::post(
                        'quality/ncrs/{ncr}/return-for-correction',
                        [
                            ConstructionQualityNcrController::class,
                            'returnForCorrection'
                        ]
                    )->name(
                        'quality.ncrs.return-for-correction'
                    );



                });

                Route::prefix('projects/{project}/construction/hse')
                ->name('projects.construction.hse.')
                ->group(function () {

                    Route::get(
                        '/',
                        [ConstructionHseController::class, 'index']
                    )->name(
                        'index'
                    );

                    Route::resource(
                        'observations',
                        ConstructionHseObservationController::class
                    );
                    Route::post(
                        'observations/{observation}/start',
                        [
                            ConstructionHseObservationController::class,
                            'start'
                        ]
                    )->name('observations.start');


                    Route::post(
                        'observations/{observation}/verify',
                        [
                            ConstructionHseObservationController::class,
                            'verify'
                        ]
                    )->name('observations.verify');


                    Route::post(
                        'observations/{observation}/close',
                        [
                            ConstructionHseObservationController::class,
                            'close'
                        ]
                    )->name('observations.close');


                    Route::post(
                        'observations/{observation}/reopen',
                        [
                            ConstructionHseObservationController::class,
                            'reopen'
                        ]
                    )->name('observations.reopen');

                });

                /*
                |--------------------------------------------------------------------------
                | HSE Corrective Actions
                |--------------------------------------------------------------------------
                */

                Route::prefix('projects/{project}/construction/hse/observations/{observation}/corrective-actions')
                    ->name('projects.construction.hse.observations.corrective-actions.')
                    ->group(function () {

                        Route::get(
                            '/',
                            [
                                ConstructionHseCorrectiveActionController::class,
                                'index'
                            ]
                        )->name('index');

                        Route::get(
                            '/create',
                            [
                                ConstructionHseCorrectiveActionController::class,
                                'create'
                            ]
                        )->name('create');

                        Route::post(
                            '/',
                            [
                                ConstructionHseCorrectiveActionController::class,
                                'store'
                            ]
                        )->name('store');

                        Route::get(
                            '/{correctiveAction}',
                            [
                                ConstructionHseCorrectiveActionController::class,
                                'show'
                            ]
                        )->name('show');

                        Route::get(
                            '/{correctiveAction}/edit',
                            [
                                ConstructionHseCorrectiveActionController::class,
                                'edit'
                            ]
                        )->name('edit');

                        Route::put(
                            '/{correctiveAction}',
                            [
                                ConstructionHseCorrectiveActionController::class,
                                'update'
                            ]
                        )->name('update');

                        Route::delete(
                            '/{correctiveAction}',
                            [
                                ConstructionHseCorrectiveActionController::class,
                                'destroy'
                            ]
                        )->name('destroy');

                        Route::post(
                            '/{correctiveAction}/start',
                            [
                                ConstructionHseCorrectiveActionController::class,
                                'start'
                            ]
                        )->name('start');

                        Route::post(
                            '/{correctiveAction}/resolve',
                            [
                                ConstructionHseCorrectiveActionController::class,
                                'resolve'
                            ]
                        )->name('resolve');

                        Route::post(
                            '/{correctiveAction}/verify',
                            [
                                ConstructionHseCorrectiveActionController::class,
                                'verify'
                            ]
                        )->name('verify');

                        Route::post(
                            '/{correctiveAction}/reject-verification',
                            [
                                ConstructionHseCorrectiveActionController::class,
                                'rejectVerification'
                            ]
                        )->name('reject-verification');

                        Route::post(
                            '/{correctiveAction}/close',
                            [
                                ConstructionHseCorrectiveActionController::class,
                                'close'
                            ]
                        )->name('close');

                    });

                    Route::prefix('projects/{project}/construction/hse')
                    ->name('projects.construction.hse.')
                    ->group(function () {

                        Route::resource(
                            'incidents',
                            ConstructionHseIncidentController::class
                        );

                        Route::post(
                            'incidents/{incident}/start',
                            [ConstructionHseIncidentController::class, 'startInvestigation']
                        )->name('incidents.start');

                        Route::post(
                            'incidents/{incident}/verify',
                            [ConstructionHseIncidentController::class, 'verify']
                        )->name('incidents.verify');

                        Route::post(
                            'incidents/{incident}/close',
                            [ConstructionHseIncidentController::class, 'close']
                        )->name('incidents.close');

                        Route::get(
                            'incidents/{incident}/documents',
                            [
                                ConstructionHseIncidentDocumentController::class,
                                'index'
                            ]
                        )->name('incidents.documents.index');

                        Route::get(
                            'incidents/{incident}/documents/create',
                            [
                                ConstructionHseIncidentDocumentController::class,
                                'create'
                            ]
                        )->name('incidents.documents.create');

                        Route::post(
                            'incidents/{incident}/documents',
                            [
                                ConstructionHseIncidentDocumentController::class,
                                'store'
                            ]
                        )->name('incidents.documents.store');

                        Route::get(
                            'incidents/{incident}/documents/{document}',
                            [
                                ConstructionHseIncidentDocumentController::class,
                                'show'
                            ]
                        )->name('incidents.documents.show');

                        Route::delete(
                            'incidents/{incident}/documents/{document}',
                            [
                                ConstructionHseIncidentDocumentController::class,
                                'destroy'
                            ]
                        )->name('incidents.documents.destroy');

                        Route::get(
                            'incidents/{incident}/investigations',
                            [
                                ConstructionHseIncidentInvestigationController::class,
                                'index'
                            ]
                        )->name('incidents.investigations.index');


                        Route::get(
                            'incidents/{incident}/investigations/create',
                            [
                                ConstructionHseIncidentInvestigationController::class,
                                'create'
                            ]
                        )->name('incidents.investigations.create');


                        Route::post(
                            'incidents/{incident}/investigations',
                            [
                                ConstructionHseIncidentInvestigationController::class,
                                'store'
                            ]
                        )->name('incidents.investigations.store');


                        Route::get(
                            'incidents/{incident}/investigations/{investigation}',
                            [
                                ConstructionHseIncidentInvestigationController::class,
                                'show'
                            ]
                        )->name('incidents.investigations.show');


                        Route::get(
                            'incidents/{incident}/investigations/{investigation}/edit',
                            [
                                ConstructionHseIncidentInvestigationController::class,
                                'edit'
                            ]
                        )->name('incidents.investigations.edit');


                        Route::PUT(
                            'incidents/{incident}/investigations/{investigation}',
                            [
                                ConstructionHseIncidentInvestigationController::class,
                                'update'
                            ]
                        )->name('incidents.investigations.update');


                        Route::DELETE(
                            'incidents/{incident}/investigations/{investigation}',
                            [
                                ConstructionHseIncidentInvestigationController::class,
                                'destroy'
                            ]
                        )->name('incidents.investigations.destroy');


                        Route::POST(
                            'incidents/{incident}/investigations/{investigation}/submit',
                            [
                                ConstructionHseIncidentInvestigationController::class,
                                'submit'
                            ]
                        )->name('incidents.investigations.submit');


                        Route::POST(
                            'incidents/{incident}/investigations/{investigation}/approve',
                            [
                                ConstructionHseIncidentInvestigationController::class,
                                'approve'
                            ]
                        )->name('incidents.investigations.approve');


                        Route::POST(
                            'incidents/{incident}/investigations/{investigation}/reject',
                            [
                                ConstructionHseIncidentInvestigationController::class,
                                'reject'
                            ]
                        )->name('incidents.investigations.reject');

                    });

                    Route::prefix(
                        'projects/{project}/construction/hse/incidents/{incident}/actions'
                    )
                        ->name(
                            'projects.construction.hse.incidents.actions.'
                        )
                        ->controller(
                            ConstructionHseIncidentActionController::class
                        )
                        ->group(function () {

                            Route::get(
                                '/',
                                'index'
                            )->name('index');

                            Route::get(
                                '/create',
                                'create'
                            )->name('create');

                            Route::post(
                                '/',
                                'store'
                            )->name('store');

                            Route::get(
                                '/{action}',
                                'show'
                            )->name('show');

                            Route::get(
                                '/{action}/edit',
                                'edit'
                            )->name('edit');

                            Route::put(
                                '/{action}',
                                'update'
                            )->name('update');

                            Route::delete(
                                '/{action}',
                                'destroy'
                            )->name('destroy');

                            Route::post(
                                '/{action}/start',
                                'start'
                            )->name('start');

                            Route::post(
                                '/{action}/complete',
                                'complete'
                            )->name('complete');

                            Route::post(
                                '/{action}/verify',
                                'verify'
                            )->name('verify');

                            Route::post(
                                '/{action}/reject-verification',
                                'rejectVerification'
                            )->name('reject-verification');
                    });


                    Route::prefix(
                        'projects/{project}/construction/hse/incidents/{incident}/persons'
                    )
                    ->name(
                        'projects.construction.hse.incidents.persons.'
                    )
                    ->controller(
                        ConstructionHseIncidentPersonController::class
                    )
                    ->group(function () {

                        Route::get(
                            '/',
                            'index'
                        )->name('index');

                        Route::get(
                            '/create',
                            'create'
                        )->name('create');

                        Route::post(
                            '/',
                            'store'
                        )->name('store');

                        Route::get(
                            '/{person}',
                            'show'
                        )->name('show');

                        Route::get(
                            '/{person}/edit',
                            'edit'
                        )->name('edit');

                        Route::put(
                            '/{person}',
                            'update'
                        )->name('update');

                        Route::delete(
                            '/{person}',
                            'destroy'
                        )->name('destroy');

                    });

                    Route::prefix(
                        'projects/{project}/construction/hse/incidents/{incident}/witnesses'
                    )
                        ->name(
                            'projects.construction.hse.incidents.witnesses.'
                        )
                        ->controller(
                            ConstructionHseIncidentWitnessController::class
                        )
                        ->group(function () {

                            Route::get(
                                '/',
                                'index'
                            )->name('index');

                            Route::get(
                                '/create',
                                'create'
                            )->name('create');

                            Route::post(
                                '/',
                                'store'
                            )->name('store');

                            Route::get(
                                '/{witness}',
                                'show'
                            )->name('show');

                            Route::get(
                                '/{witness}/edit',
                                'edit'
                            )->name('edit');

                            Route::put(
                                '/{witness}',
                                'update'
                            )->name('update');

                            Route::delete(
                                '/{witness}',
                                'destroy'
                            )->name('destroy');

                    });

                    Route::prefix(
                        'projects/{project}/construction/hse/inspections'
                    )
                        ->name(
                            'projects.construction.hse.inspections.'
                        )
                        ->controller(
                            ConstructionHseInspectionController::class
                        )
                        ->group(function () {

                            Route::get(
                                '/',
                                'index'
                            )->name('index');

                            Route::get(
                                '/create',
                                'create'
                            )->name('create');

                            Route::post(
                                '/',
                                'store'
                            )->name('store');

                            Route::get(
                                '/{inspection}',
                                'show'
                            )->name('show');

                            Route::get(
                                '/{inspection}/edit',
                                'edit'
                            )->name('edit');

                            Route::put(
                                '/{inspection}',
                                'update'
                            )->name('update');

                            Route::delete(
                                '/{inspection}',
                                'destroy'
                            )->name('destroy');

                    });

                    Route::prefix(
                        'projects/{project}/construction/hse/inspections/{inspection}/items'
                    )
                    ->name(
                        'projects.construction.hse.inspections.items.'
                    )
                    ->controller(
                        ConstructionHseInspectionItemController::class
                    )
                    ->group(function () {

                        Route::get(
                            '/',
                            'index'
                        )->name('index');

                        Route::get(
                            '/create',
                            'create'
                        )->name('create');

                        Route::post(
                            '/',
                            'store'
                        )->name('store');

                        Route::get(
                            '/{item}',
                            'show'
                        )->name('show');

                        Route::get(
                            '/{item}/edit',
                            'edit'
                        )->name('edit');

                        Route::put(
                            '/{item}',
                            'update'
                        )->name('update');

                        Route::delete(
                            '/{item}',
                            'destroy'
                        )->name('destroy');

                    });

                    Route::prefix(
                        'projects/{project}/construction/hse/inspections/{inspection}/findings'
                    )
                    ->name(
                        'projects.construction.hse.inspections.findings.'
                    )
                    ->controller(
                        ConstructionHseInspectionFindingController::class
                    )
                    ->group(function () {

                        Route::get(
                            '/',
                            'index'
                        )->name('index');

                        Route::get(
                            '/create',
                            'create'
                        )->name('create');

                        Route::post(
                            '/',
                            'store'
                        )->name('store');

                        Route::get(
                            '/{finding}',
                            'show'
                        )->name('show');

                        Route::get(
                            '/{finding}/edit',
                            'edit'
                        )->name('edit');

                        Route::put(
                            '/{finding}',
                            'update'
                        )->name('update');

                        Route::delete(
                            '/{finding}',
                            'destroy'
                        )->name('destroy');

                    });

                    Route::prefix(
                        'projects/{project}/construction/hse/inspections/{inspection}/findings/{finding}/actions'
                    )
                        ->name(
                            'projects.construction.hse.inspections.findings.actions.'
                        )
                        ->controller(
                            ConstructionHseInspectionActionController::class
                        )
                        ->group(function () {

                            Route::get(
                                '/',
                                'index'
                            )->name('index');

                            Route::get(
                                '/create',
                                'create'
                            )->name('create');

                            Route::post(
                                '/',
                                'store'
                            )->name('store');

                            Route::get(
                                '/{action}',
                                'show'
                            )->name('show');

                            Route::get(
                                '/{action}/edit',
                                'edit'
                            )->name('edit');

                            Route::put(
                                '/{action}',
                                'update'
                            )->name('update');

                            Route::delete(
                                '/{action}',
                                'destroy'
                            )->name('destroy');

                    });

                    Route::prefix(
                        'projects/{project}/construction/hse/inspections/{inspection}/actions'
                    )
                        ->name(
                            'projects.construction.hse.inspections.actions.'
                        )
                        ->controller(
                            ConstructionHseInspectionActionController::class
                        )
                        ->group(function () {

                            Route::get(
                                '/',
                                'inspectionIndex'
                            )->name('index');

                    });

                    Route::prefix(
                        'projects/{project}/construction/hse/inspections/{inspection}/documents'
                    )
                        ->name(
                            'projects.construction.hse.inspections.documents.'
                        )
                        ->controller(
                            ConstructionHseInspectionDocumentController::class
                        )
                        ->group(function () {

                            Route::get(
                                '/',
                                'index'
                            )->name('index');

                            Route::get(
                                '/create',
                                'create'
                            )->name('create');

                            Route::post(
                                '/',
                                'store'
                            )->name('store');

                            Route::get(
                                '/{document}',
                                'show'
                            )->name('show');

                            Route::get(
                                '/{document}/download',
                                'download'
                            )->name('download');

                            Route::delete(
                                '/{document}',
                                'destroy'
                            )->name('destroy');

                    });

                    Route::prefix(
                        'projects/{project}/construction/hse/safety-meetings'
                    )
                        ->name(
                            'projects.construction.hse.safety-meetings.'
                        )
                        ->controller(
                            ConstructionHseSafetyMeetingController::class
                        )
                        ->group(function () {

                            Route::get(
                                '/',
                                'index'
                            )->name('index');

                            Route::get(
                                '/create',
                                'create'
                            )->name('create');

                            Route::post(
                                '/',
                                'store'
                            )->name('store');

                            Route::get(
                                '/{meeting}/edit',
                                'edit'
                            )->name('edit');

                            Route::get(
                                '/{meeting}',
                                'show'
                            )->name('show');

                            Route::put(
                                '/{meeting}',
                                'update'
                            )->name('update');

                            Route::delete(
                                '/{meeting}',
                                'destroy'
                            )->name('destroy');

                    });

                    Route::prefix(
                        'projects/{project}/construction/hse/safety-meetings/{meeting}/participants'
                    )
                        ->name(
                            'projects.construction.hse.safety-meetings.participants.'
                        )
                        ->controller(
                            ConstructionHseSafetyMeetingParticipantController::class
                        )
                        ->group(function () {

                            Route::get(
                                '/',
                                'index'
                            )->name('index');

                            Route::get(
                                '/create',
                                'create'
                            )->name('create');

                            Route::post(
                                '/',
                                'store'
                            )->name('store');

                            Route::get(
                                '/{participant}/edit',
                                'edit'
                            )->name('edit');

                            Route::get(
                                '/{participant}',
                                'show'
                            )->name('show');

                            Route::put(
                                '/{participant}',
                                'update'
                            )->name('update');

                            Route::delete(
                                '/{participant}',
                                'destroy'
                            )->name('destroy');

                    });

                    Route::prefix(
                        'projects/{project}/construction/hse/safety-meetings/{meeting}/documents'
                    )
                        ->name(
                            'projects.construction.hse.safety-meetings.documents.'
                        )
                        ->controller(
                            ConstructionHseSafetyMeetingDocumentController::class
                        )
                        ->group(function () {

                            Route::get(
                                '/',
                                'index'
                            )->name('index');

                            Route::get(
                                '/create',
                                'create'
                            )->name('create');

                            Route::post(
                                '/',
                                'store'
                            )->name('store');

                            Route::get(
                                '/{document}/download',
                                'download'
                            )->name('download');

                            Route::get(
                                '/{document}',
                                'show'
                            )->name('show');

                            Route::delete(
                                '/{document}',
                                'destroy'
                            )->name('destroy');

                    });
                    Route::prefix(
                        'projects/{project}/construction/hse/toolbox-talks'
                    )
                        ->name(
                            'projects.construction.hse.toolbox-talks.'
                        )
                        ->controller(
                            ConstructionHseToolboxTalkController::class
                        )
                        ->group(function () {

                            Route::get(
                                '/',
                                'index'
                            )->name('index');

                            Route::get(
                                '/create',
                                'create'
                            )->name('create');

                            Route::post(
                                '/',
                                'store'
                            )->name('store');

                            Route::get(
                                '/{toolboxTalk}/edit',
                                'edit'
                            )->name('edit');

                            Route::get(
                                '/{toolboxTalk}',
                                'show'
                            )->name('show');

                            Route::put(
                                '/{toolboxTalk}',
                                'update'
                            )->name('update');

                            Route::delete(
                                '/{toolboxTalk}',
                                'destroy'
                            )->name('destroy');

                    });
                    Route::prefix(
                        'projects/{project}/construction/hse/toolbox-talks/{toolboxTalk}/participants'
                    )
                        ->name(
                            'projects.construction.hse.toolbox-talks.participants.'
                        )
                        ->controller(
                            ConstructionHseToolboxTalkParticipantController::class
                        )
                        ->group(function () {

                            Route::get(
                                '/',
                                'index'
                            )->name('index');

                            Route::get(
                                '/create',
                                'create'
                            )->name('create');

                            Route::post(
                                '/',
                                'store'
                            )->name('store');

                            Route::get(
                                '/{participant}/edit',
                                'edit'
                            )->name('edit');

                            Route::get(
                                '/{participant}',
                                'show'
                            )->name('show');

                            Route::put(
                                '/{participant}',
                                'update'
                            )->name('update');

                            Route::delete(
                                '/{participant}',
                                'destroy'
                            )->name('destroy');

                    });

                    Route::prefix(
                        'projects/{project}/construction/hse/toolbox-talks/{toolboxTalk}/documents'
                    )
                        ->name(
                            'projects.construction.hse.toolbox-talks.documents.'
                        )
                        ->controller(
                            ConstructionHseToolboxTalkDocumentController::class
                        )
                        ->group(function () {

                            Route::get(
                                '/',
                                'index'
                            )->name('index');

                            Route::get(
                                '/create',
                                'create'
                            )->name('create');

                            Route::post(
                                '/',
                                'store'
                            )->name('store');

                            Route::get(
                                '/{document}/download',
                                'download'
                            )->name('download');

                            Route::get(
                                '/{document}',
                                'show'
                            )->name('show');

                            Route::delete(
                                '/{document}',
                                'destroy'
                            )->name('destroy');

                    });

                    Route::prefix(
                        'projects/{project}/construction/hse/environmental/records'
                    )
                        ->name(
                            'projects.construction.hse.environmental.records.'
                        )
                        ->controller(
                            ConstructionHseEnvironmentalRecordController::class
                        )
                        ->group(function () {

                            Route::get(
                                '/',
                                'index'
                            )->name('index');

                            Route::get(
                                '/create',
                                'create'
                            )->name('create');

                            Route::post(
                                '/',
                                'store'
                            )->name('store');

                            Route::get(
                                '/{record}/edit',
                                'edit'
                            )->name('edit');

                            Route::get(
                                '/{record}',
                                'show'
                            )->name('show');

                            Route::put(
                                '/{record}',
                                'update'
                            )->name('update');

                            Route::delete(
                                '/{record}',
                                'destroy'
                            )->name('destroy');

                    });
                    Route::prefix(
                        'projects/{project}/construction/hse/environmental/compliances'
                    )
                        ->name(
                            'projects.construction.hse.environmental.compliances.'
                        )
                        ->controller(
                            ConstructionHseEnvironmentalComplianceController::class
                        )
                        ->group(function () {

                            Route::get(
                                '/',
                                'index'
                            )->name('index');

                            Route::get(
                                '/create',
                                'create'
                            )->name('create');

                            Route::post(
                                '/',
                                'store'
                            )->name('store');

                            Route::get(
                                '/{compliance}/edit',
                                'edit'
                            )->name('edit');

                            Route::get(
                                '/{compliance}',
                                'show'
                            )->name('show');

                            Route::put(
                                '/{compliance}',
                                'update'
                            )->name('update');

                            Route::delete(
                                '/{compliance}',
                                'destroy'
                            )->name('destroy');

                    });
                    Route::prefix(
                        'projects/{project}/construction/hse/environmental/actions'
                    )
                        ->name(
                            'projects.construction.hse.environmental.actions.'
                        )
                        ->controller(
                            ConstructionHseEnvironmentalActionController::class
                        )
                        ->group(function () {

                            Route::get(
                                '/',
                                'index'
                            )->name('index');

                            Route::get(
                                '/create',
                                'create'
                            )->name('create');

                            Route::post(
                                '/',
                                'store'
                            )->name('store');

                            Route::get(
                                '/{action}/edit',
                                'edit'
                            )->name('edit');

                            Route::get(
                                '/{action}',
                                'show'
                            )->name('show');

                            Route::put(
                                '/{action}',
                                'update'
                            )->name('update');

                            Route::delete(
                                '/{action}',
                                'destroy'
                            )->name('destroy');

                    });

                    Route::prefix('feasibility-investment')
                    ->name('feasibility-investment.')
                    ->group(function () {

                        Route::get(
                            '/',
                            [
                                FeasibilityInvestmentController::class,
                                'index'
                            ]
                        )->name('index');

                    });
                    
                    Route::get(
                        'construction',
                        [
                            ConstructionManagementController::class,
                            'index'
                        ]
                    )->name('construction.index');

                Route::get(
                    '/projects/{project}/contract-management/contracts',
                    [
                        ContractManagementContractController::class,
                        'index'
                    ]
                )->name(
                    'projects.contract-management.contracts.index'
                );

                Route::post(
                    '/projects/{project}/contract-management/contracts/sync-procurement',
                    [
                        ContractManagementContractController::class,
                        'syncProcurementContracts'
                    ]
                )->name(
                    'projects.contract-management.contracts.sync-procurement'
                );

                Route::get(
                    '/projects/{project}/contract-management/contracts/{contract}',
                    [
                        ContractManagementContractController::class,
                        'show'
                    ]
                )->name(
                    'projects.contract-management.contracts.show'
                );

                Route::prefix(
                    '/projects/{project}/contract-management/contracts/{contract}/claims'
                )->name(
                    'projects.contract-management.contracts.claims.'
                )->group(function () {

                    Route::get(
                        '/',
                        [
                            ContractClaimController::class,
                            'index'
                        ]
                    )->name('index');


                    Route::get(
                        '/create',
                        [
                            ContractClaimController::class,
                            'create'
                        ]
                    )->name('create');


                    Route::post(
                        '/',
                        [
                            ContractClaimController::class,
                            'store'
                        ]
                    )->name('store');


                    Route::get(
                        '/{claim}/edit',
                        [
                            ContractClaimController::class,
                            'edit'
                        ]
                    )->name('edit');


                    Route::put(
                        '/{claim}',
                        [
                            ContractClaimController::class,
                            'update'
                        ]
                    )->name('update');


                    Route::delete(
                        '/{claim}',
                        [
                            ContractClaimController::class,
                            'destroy'
                        ]
                    )->name('destroy');

                });
                Route::prefix(
                    '/projects/{project}/contract-management/contracts/{contract}/eot'
                )->name(
                    'projects.contract-management.contracts.eot.'
                )->group(function () {

                    Route::get(
                        '/',
                        [
                            ContractExtensionOfTimeController::class,
                            'index'
                        ]
                    )->name('index');


                    Route::get(
                        '/create',
                        [
                            ContractExtensionOfTimeController::class,
                            'create'
                        ]
                    )->name('create');


                    Route::post(
                        '/',
                        [
                            ContractExtensionOfTimeController::class,
                            'store'
                        ]
                    )->name('store');


                    Route::get(
                        '/{eot}/edit',
                        [
                            ContractExtensionOfTimeController::class,
                            'edit'
                        ]
                    )->name('edit');


                    Route::put(
                        '/{eot}',
                        [
                            ContractExtensionOfTimeController::class,
                            'update'
                        ]
                    )->name('update');


                    Route::delete(
                        '/{eot}',
                        [
                            ContractExtensionOfTimeController::class,
                            'destroy'
                        ]
                    )->name('destroy');

                });

                Route::prefix(
                    '/projects/{project}/contract-management/contracts/{contract}/insurances'
                )->name(
                    'projects.contract-management.contracts.insurances.'
                )->group(function () {

                    Route::get(
                        '/',
                        [
                            ContractInsuranceController::class,
                            'index'
                        ]
                    )->name('index');


                    Route::get(
                        '/create',
                        [
                            ContractInsuranceController::class,
                            'create'
                        ]
                    )->name('create');


                    Route::post(
                        '/',
                        [
                            ContractInsuranceController::class,
                            'store'
                        ]
                    )->name('store');


                    Route::get(
                        '/{insurance}/edit',
                        [
                            ContractInsuranceController::class,
                            'edit'
                        ]
                    )->name('edit');


                    Route::put(
                        '/{insurance}',
                        [
                            ContractInsuranceController::class,
                            'update'
                        ]
                    )->name('update');


                    Route::delete(
                        '/{insurance}',
                        [
                            ContractInsuranceController::class,
                            'destroy'
                        ]
                    )->name('destroy');

                });

                Route::prefix(
                    '/projects/{project}/contract-management/contracts/{contract}/performance-securities'
                )->name(
                    'projects.contract-management.contracts.performance-securities.'
                )->group(function () {

                    Route::get(
                        '/',
                        [
                            ContractPerformanceSecurityController::class,
                            'index'
                        ]
                    )->name('index');


                    Route::get(
                        '/create',
                        [
                            ContractPerformanceSecurityController::class,
                            'create'
                        ]
                    )->name('create');


                    Route::post(
                        '/',
                        [
                            ContractPerformanceSecurityController::class,
                            'store'
                        ]
                    )->name('store');


                    Route::get(
                        '/{security}/edit',
                        [
                            ContractPerformanceSecurityController::class,
                            'edit'
                        ]
                    )->name('edit');


                    Route::put(
                        '/{security}',
                        [
                            ContractPerformanceSecurityController::class,
                            'update'
                        ]
                    )->name('update');


                    Route::delete(
                        '/{security}',
                        [
                            ContractPerformanceSecurityController::class,
                            'destroy'
                        ]
                    )->name('destroy');

                });

                Route::prefix(
                    '/projects/{project}/contract-management/contracts/{contract}/retentions'
                )->name(
                    'projects.contract-management.contracts.retentions.'
                )->group(function () {

                    Route::get(
                        '/',
                        [
                            ContractManagementRetentionController::class,
                            'index'
                        ]
                    )->name('index');


                    Route::get(
                        '/create',
                        [
                            ContractManagementRetentionController::class,
                            'create'
                        ]
                    )->name('create');


                    Route::post(
                        '/',
                        [
                            ContractManagementRetentionController::class,
                            'store'
                        ]
                    )->name('store');


                    Route::get(
                        '/{retention}/edit',
                        [
                            ContractManagementRetentionController::class,
                            'edit'
                        ]
                    )->name('edit');


                    Route::put(
                        '/{retention}',
                        [
                            ContractManagementRetentionController::class,
                            'update'
                        ]
                    )->name('update');


                    Route::delete(
                        '/{retention}',
                        [
                            ContractManagementRetentionController::class,
                            'destroy'
                        ]
                    )->name('destroy');

                });

                Route::prefix(
                    '/projects/{project}/contract-management/contracts/{contract}/advance-payments'
                )->name(
                    'projects.contract-management.contracts.advance-payments.'
                )->group(function () {

                    Route::get(
                        '/',
                        [
                            ContractManagementAdvancePaymentController::class,
                            'index'
                        ]
                    )->name('index');


                    Route::get(
                        '/create',
                        [
                            ContractManagementAdvancePaymentController::class,
                            'create'
                        ]
                    )->name('create');


                    Route::post(
                        '/',
                        [
                            ContractManagementAdvancePaymentController::class,
                            'store'
                        ]
                    )->name('store');


                    Route::get(
                        '/{advancePayment}/edit',
                        [
                            ContractManagementAdvancePaymentController::class,
                            'edit'
                        ]
                    )->name('edit');


                    Route::put(
                        '/{advancePayment}',
                        [
                            ContractManagementAdvancePaymentController::class,
                            'update'
                        ]
                    )->name('update');


                    Route::delete(
                        '/{advancePayment}',
                        [
                            ContractManagementAdvancePaymentController::class,
                            'destroy'
                        ]
                    )->name('destroy');

                });

                Route::prefix(
                    '/projects/{project}/contract-management/contracts/{contract}/documents'
                )->name(
                    'projects.contract-management.contracts.documents.'
                )->group(function () {

                    Route::get(
                        '/',
                        [
                            ContractManagementDocumentController::class,
                            'index'
                        ]
                    )->name('index');


                    Route::get(
                        '/create',
                        [
                            ContractManagementDocumentController::class,
                            'create'
                        ]
                    )->name('create');


                    Route::post(
                        '/',
                        [
                            ContractManagementDocumentController::class,
                            'store'
                        ]
                    )->name('store');


                    Route::get(
                        '/{document}/edit',
                        [
                            ContractManagementDocumentController::class,
                            'edit'
                        ]
                    )->name('edit');


                    Route::put(
                        '/{document}',
                        [
                            ContractManagementDocumentController::class,
                            'update'
                        ]
                    )->name('update');


                    Route::get(
                        '/{document}/download',
                        [
                            ContractManagementDocumentController::class,
                            'download'
                        ]
                    )->name('download');


                    Route::delete(
                        '/{document}',
                        [
                            ContractManagementDocumentController::class,
                            'destroy'
                        ]
                    )->name('destroy');

                });

                Route::prefix(
                    '/projects/{project}/contract-management/contracts/{contract}/correspondence'
                )->name(
                    'projects.contract-management.contracts.correspondence.'
                )->group(function () {

                    Route::get(
                        '/',
                        [
                            ContractManagementCorrespondenceController::class,
                            'index'
                        ]
                    )->name('index');


                    Route::get(
                        '/create',
                        [
                            ContractManagementCorrespondenceController::class,
                            'create'
                        ]
                    )->name('create');


                    Route::post(
                        '/',
                        [
                            ContractManagementCorrespondenceController::class,
                            'store'
                        ]
                    )->name('store');


                    Route::get(
                        '/{correspondence}/edit',
                        [
                            ContractManagementCorrespondenceController::class,
                            'edit'
                        ]
                    )->name('edit');


                    Route::put(
                        '/{correspondence}',
                        [
                            ContractManagementCorrespondenceController::class,
                            'update'
                        ]
                    )->name('update');


                    Route::get(
                        '/{correspondence}/download',
                        [
                            ContractManagementCorrespondenceController::class,
                            'download'
                        ]
                    )->name('download');


                    Route::delete(
                        '/{correspondence}',
                        [
                            ContractManagementCorrespondenceController::class,
                            'destroy'
                        ]
                    )->name('destroy');

                });

                Route::get(
                    '/contract-management',
                    [
                        ContractManagementDashboardController::class,
                        'index'
                    ]
                )->name(
                    'contract-management.index'
                );


                Route::prefix('projects/{project}/construction/materials')
                ->name('projects.construction.materials.')
                ->group(function () {

                    /*
                    |--------------------------------------------------------------------------
                    | Materials Dashboard
                    |--------------------------------------------------------------------------
                    */

                    Route::get(
                        '/',
                        [ConstructionMaterialController::class, 'index']
                    )->name('index');


                    /*
                    |--------------------------------------------------------------------------
                    | Material Master
                    |--------------------------------------------------------------------------
                    */

                    Route::get(
                        '/master',
                        [ConstructionMaterialController::class, 'master']
                    )->name('master.index');

                    Route::get(
                        '/master/create',
                        [ConstructionMaterialController::class, 'create']
                    )->name('master.create');

                    Route::post(
                        '/master',
                        [ConstructionMaterialController::class, 'store']
                    )->name('master.store');

                    Route::get(
                        '/master/{material}',
                        [ConstructionMaterialController::class, 'show']
                    )->name('master.show');

                    Route::get(
                        '/master/{material}/edit',
                        [ConstructionMaterialController::class, 'edit']
                    )->name('master.edit');

                    Route::put(
                        '/master/{material}',
                        [ConstructionMaterialController::class, 'update']
                    )->name('master.update');

                    Route::delete(
                        '/master/{material}',
                        [ConstructionMaterialController::class, 'destroy']
                    )->name('master.destroy');
                });

                Route::prefix('projects/{project}/construction/materials/requests')
                ->name('projects.construction.materials.requests.')
                ->group(function () {

                    Route::get(
                        '/',
                        [
                            ConstructionMaterialRequestController::class,
                            'index'
                        ]
                    )->name('index');

                    Route::get(
                        '/create',
                        [
                            ConstructionMaterialRequestController::class,
                            'create'
                        ]
                    )->name('create');

                    Route::post(
                        '/',
                        [
                            ConstructionMaterialRequestController::class,
                            'store'
                        ]
                    )->name('store');

                    Route::get(
                        '/{materialRequest}',
                        [
                            ConstructionMaterialRequestController::class,
                            'show'
                        ]
                    )->name('show');

                    Route::get(
                        '/{materialRequest}/edit',
                        [
                            ConstructionMaterialRequestController::class,
                            'edit'
                        ]
                    )->name('edit');

                    Route::put(
                        '/{materialRequest}',
                        [
                            ConstructionMaterialRequestController::class,
                            'update'
                        ]
                    )->name('update');

                    Route::post(
                        '/{materialRequest}/submit',
                        [
                            ConstructionMaterialRequestController::class,
                            'submit'
                        ]
                    )->name('submit');

                    Route::post(
                        '/{materialRequest}/review',
                        [
                            ConstructionMaterialRequestController::class,
                            'review'
                        ]
                    )->name('review');

                    Route::post(
                        '/{materialRequest}/approve',
                        [
                            ConstructionMaterialRequestController::class,
                            'approve'
                        ]
                    )->name('approve');

                    Route::post(
                        '/{materialRequest}/request-changes',
                        [
                            ConstructionMaterialRequestController::class,
                            'requestChanges'
                        ]
                    )->name('request-changes');

                    Route::post(
                        '/{materialRequest}/reject',
                        [
                            ConstructionMaterialRequestController::class,
                            'reject'
                        ]
                    )->name('reject');

                    Route::post(
                        '/{materialRequest}/cancel',
                        [
                            ConstructionMaterialRequestController::class,
                            'cancel'
                        ]
                    )->name('cancel');
                });

                Route::prefix('projects/{project}/construction/materials/deliveries')
                ->name('projects.construction.materials.deliveries.')
                ->group(function () {

                    Route::get(
                        '/',
                        [ConstructionMaterialDeliveryController::class, 'index']
                    )->name('index');

                    /*
                    |--------------------------------------------------------------------------
                    | Create from approved Material Request
                    |--------------------------------------------------------------------------
                    */

                    Route::get(
                        '/create/{materialRequest}',
                        [ConstructionMaterialDeliveryController::class, 'create']
                    )->name('create');

                    Route::post(
                        '/',
                        [ConstructionMaterialDeliveryController::class, 'store']
                    )->name('store');

                    Route::get(
                        '/{materialDelivery}',
                        [ConstructionMaterialDeliveryController::class, 'show']
                    )->name('show');

                    Route::get(
                        '/{materialDelivery}/edit',
                        [ConstructionMaterialDeliveryController::class, 'edit']
                    )->name('edit');

                    Route::put(
                        '/{materialDelivery}',
                        [ConstructionMaterialDeliveryController::class, 'update']
                    )->name('update');

                    Route::post(
                        '/{materialDelivery}/receive',
                        [ConstructionMaterialDeliveryController::class, 'receive']
                    )->name('receive');

                    Route::post(
                        '/{materialDelivery}/cancel',
                        [ConstructionMaterialDeliveryController::class, 'cancel']
                    )->name('cancel');
                });

                Route::prefix('projects/{project}/construction/materials/receipts')
                ->name('projects.construction.materials.receipts.')
                ->group(function () {

                    Route::get(
                        '/',
                        [ConstructionMaterialReceiptController::class, 'index']
                    )->name('index');

                    /*
                    |--------------------------------------------------------------------------
                    | Create from Delivery
                    |--------------------------------------------------------------------------
                    */

                    Route::get(
                        '/create/{materialDelivery}',
                        [ConstructionMaterialReceiptController::class, 'create']
                    )->name('create');

                    Route::post(
                        '/',
                        [ConstructionMaterialReceiptController::class, 'store']
                    )->name('store');

                    Route::get(
                        '/{materialReceipt}',
                        [ConstructionMaterialReceiptController::class, 'show']
                    )->name('show');

                    Route::post(
                        '/{materialReceipt}/inspect',
                        [ConstructionMaterialReceiptController::class, 'inspect']
                    )->name('inspect');

                    Route::post(
                        '/{materialReceipt}/cancel',
                        [ConstructionMaterialReceiptController::class, 'cancel']
                    )->name('cancel');
                });

                Route::prefix('projects/{project}/construction/materials/stock')
                ->name('projects.construction.materials.stock.')
                ->group(function () {

                    Route::get(
                        '/',
                        [ConstructionMaterialStockController::class, 'index']
                    )->name('index');

                    Route::get(
                        '/transactions',
                        [ConstructionMaterialStockController::class, 'transactions']
                    )->name('transactions');

                    Route::get(
                        '/transactions/{transaction}',
                        [ConstructionMaterialStockController::class, 'transactionShow']
                    )->name('transactions.show');

                    Route::get(
                        '/{stock}',
                        [ConstructionMaterialStockController::class, 'show']
                    )->name('show');
                });

                Route::prefix('projects/{project}/construction/materials/requirements')
                ->name('projects.construction.materials.requirements.')
                ->group(function () {

                    Route::get(
                        '/',
                        [
                            ConstructionMaterialRequirementController::class,
                            'index'
                        ]
                    )->name('index');

                    Route::get(
                        '/create',
                        [
                            ConstructionMaterialRequirementController::class,
                            'create'
                        ]
                    )->name('create');

                    Route::post(
                        '/',
                        [
                            ConstructionMaterialRequirementController::class,
                            'store'
                        ]
                    )->name('store');

                    Route::get(
                        '/{requirement}',
                        [
                            ConstructionMaterialRequirementController::class,
                            'show'
                        ]
                    )->name('show');

                    Route::get(
                        '/{requirement}/edit',
                        [
                            ConstructionMaterialRequirementController::class,
                            'edit'
                        ]
                    )->name('edit');

                    Route::put(
                        '/{requirement}',
                        [
                            ConstructionMaterialRequirementController::class,
                            'update'
                        ]
                    )->name('update');

                    Route::post(
                        '/{requirement}/request',
                        [
                            ConstructionMaterialRequirementController::class,
                            'request'
                        ]
                    )->name('request');

                    Route::post(
                        '/{requirement}/cancel',
                        [
                            ConstructionMaterialRequirementController::class,
                            'cancel'
                        ]
                    )->name('cancel');
                });

                Route::prefix(
                    'projects/{project}/construction/equipment'
                )
                ->name(
                    'projects.construction.equipment.'
                )
                ->group(function () {

                    /*
                    |--------------------------------------------------------------------------
                    | Equipment List
                    |--------------------------------------------------------------------------
                    */

                    Route::get(
                        '/',
                        [ConstructionEquipmentController::class, 'index']
                    )->name('index');


                    /*
                    |--------------------------------------------------------------------------
                    | Create Equipment
                    |--------------------------------------------------------------------------
                    */

                    Route::get(
                        '/create',
                        [ConstructionEquipmentController::class, 'create']
                    )->name('create');


                    /*
                    |--------------------------------------------------------------------------
                    | Store Equipment
                    |--------------------------------------------------------------------------
                    */

                    Route::post(
                        '/',
                        [ConstructionEquipmentController::class, 'store']
                    )->name('store');


                    /*
                    |--------------------------------------------------------------------------
                    | Equipment Detail
                    |--------------------------------------------------------------------------
                    */

                    Route::get(
                        '/{equipment}',
                        [ConstructionEquipmentController::class, 'show']
                    )
                    ->whereNumber('equipment')
                    ->name('show');


                    /*
                    |--------------------------------------------------------------------------
                    | Equipment Edit
                    |--------------------------------------------------------------------------
                    */

                    Route::get(
                        '/{equipment}/edit',
                        [ConstructionEquipmentController::class, 'edit']
                    )
                    ->whereNumber('equipment')
                    ->name('edit');


                    /*
                    |--------------------------------------------------------------------------
                    | Equipment Update
                    |--------------------------------------------------------------------------
                    */

                    Route::put(
                        '/{equipment}',
                        [ConstructionEquipmentController::class, 'update']
                    )
                    ->whereNumber('equipment')
                    ->name('update');


                    /*
                    |--------------------------------------------------------------------------
                    | Equipment Delete
                    |--------------------------------------------------------------------------
                    */

                    Route::delete(
                        '/{equipment}',
                        [ConstructionEquipmentController::class, 'destroy']
                    )
                    ->whereNumber('equipment')
                    ->name('destroy');
                });

                Route::prefix(
                    'projects/{project}/construction/equipment/deployments'
                )
                ->name(
                    'projects.construction.equipment.deployments.'
                )
                ->group(function () {

                    Route::get(
                        '/',
                        [
                            ConstructionEquipmentDeploymentController::class,
                            'index'
                        ]
                    )->name('index');

                    Route::get(
                        '/create',
                        [
                            ConstructionEquipmentDeploymentController::class,
                            'create'
                        ]
                    )->name('create');

                    Route::post(
                        '/',
                        [
                            ConstructionEquipmentDeploymentController::class,
                            'store'
                        ]
                    )->name('store');

                    Route::get(
                        '/{deployment}',
                        [
                            ConstructionEquipmentDeploymentController::class,
                            'show'
                        ]
                    )->name('show');

                    Route::post(
                        '/{deployment}/deploy',
                        [
                            ConstructionEquipmentDeploymentController::class,
                            'deploy'
                        ]
                    )->name('deploy');

                    Route::post(
                        '/{deployment}/return',
                        [
                            ConstructionEquipmentDeploymentController::class,
                            'returnEquipment'
                        ]
                    )->name('return');

                    Route::post(
                        '/{deployment}/cancel',
                        [
                            ConstructionEquipmentDeploymentController::class,
                            'cancel'
                        ]
                    )->name('cancel');
                });

                Route::prefix(
                    'projects/{project}/construction/equipment/usage'
                )
                ->name(
                    'projects.construction.equipment.usage.'
                )
                ->group(function () {
                    Route::get(
                        '/',
                        [
                            ConstructionEquipmentUsageLogController::class,
                            'index'
                        ]
                    )->name('index');

                    Route::get(
                        '/create',
                        [
                            ConstructionEquipmentUsageLogController::class,
                            'create'
                        ]
                    )->name('create');

                    Route::post(
                        '/',
                        [
                            ConstructionEquipmentUsageLogController::class,
                            'store'
                        ]
                    )->name('store');

                    Route::get(
                        '/{usageLog}',
                        [
                            ConstructionEquipmentUsageLogController::class,
                            'show'
                        ]
                    )
                    ->whereNumber('usageLog')
                    ->name('show');

                    Route::delete(
                        '/{usageLog}',
                        [
                            ConstructionEquipmentUsageLogController::class,
                            'destroy'
                        ]
                    )
                    ->whereNumber('usageLog')
                    ->name('destroy');
                });

                Route::prefix(
                    'projects/{project}/construction/equipment/maintenance'
                )
                ->name(
                    'projects.construction.equipment.maintenance.'
                )
                ->group(function () {

                    Route::get(
                        '/',
                        [
                            ConstructionEquipmentMaintenanceController::class,
                            'index'
                        ]
                    )->name('index');


                    Route::get(
                        '/create',
                        [
                            ConstructionEquipmentMaintenanceController::class,
                            'create'
                        ]
                    )->name('create');


                    Route::post(
                        '/',
                        [
                            ConstructionEquipmentMaintenanceController::class,
                            'store'
                        ]
                    )->name('store');


                    Route::get(
                        '/{maintenance}',
                        [
                            ConstructionEquipmentMaintenanceController::class,
                            'show'
                        ]
                    )
                    ->whereNumber('maintenance')
                    ->name('show');


                    Route::post(
                        '/{maintenance}/start',
                        [
                            ConstructionEquipmentMaintenanceController::class,
                            'start'
                        ]
                    )
                    ->whereNumber('maintenance')
                    ->name('start');


                    Route::post(
                        '/{maintenance}/complete',
                        [
                            ConstructionEquipmentMaintenanceController::class,
                            'complete'
                        ]
                    )
                    ->whereNumber('maintenance')
                    ->name('complete');


                    Route::post(
                        '/{maintenance}/cancel',
                        [
                            ConstructionEquipmentMaintenanceController::class,
                            'cancel'
                        ]
                    )
                    ->whereNumber('maintenance')
                    ->name('cancel');


                    Route::delete(
                        '/{maintenance}',
                        [
                            ConstructionEquipmentMaintenanceController::class,
                            'destroy'
                        ]
                    )
                    ->whereNumber('maintenance')
                    ->name('destroy');
                });

                Route::prefix('projects/{project}/construction/manpower')
                ->name('projects.construction.manpower.')
                ->group(function () {

                    /*
                    |--------------------------------------------------------------------------
                    | Manpower Master
                    |--------------------------------------------------------------------------
                    */

                    Route::get('/', [
                        ConstructionManpowerController::class,
                        'index'
                    ])->name('index');

                    Route::get('/create', [
                        ConstructionManpowerController::class,
                        'create'
                    ])->name('create');

                    Route::post('/', [
                        ConstructionManpowerController::class,
                        'store'
                    ])->name('store');


                    /*
                    |--------------------------------------------------------------------------
                    | Assignments
                    |--------------------------------------------------------------------------
                    */

                    Route::get('/assignments', [
                        ConstructionManpowerAssignmentController::class,
                        'index'
                    ])->name('assignments.index');

                    Route::get('/assignments/create', [
                        ConstructionManpowerAssignmentController::class,
                        'create'
                    ])->name('assignments.create');

                    Route::post('/assignments', [
                        ConstructionManpowerAssignmentController::class,
                        'store'
                    ])->name('assignments.store');

                    Route::get('/assignments/{assignment}', [
                        ConstructionManpowerAssignmentController::class,
                        'show'
                    ])
                    ->whereNumber('assignment')
                    ->name('assignments.show');

                    Route::get('/assignments/{assignment}/edit', [
                        ConstructionManpowerAssignmentController::class,
                        'edit'
                    ])
                    ->whereNumber('assignment')
                    ->name('assignments.edit');

                    Route::put('/assignments/{assignment}', [
                        ConstructionManpowerAssignmentController::class,
                        'update'
                    ])
                    ->whereNumber('assignment')
                    ->name('assignments.update');

                    Route::post('/assignments/{assignment}/activate', [
                        ConstructionManpowerAssignmentController::class,
                        'activate'
                    ])
                    ->whereNumber('assignment')
                    ->name('assignments.activate');

                    Route::post('/assignments/{assignment}/release', [
                        ConstructionManpowerAssignmentController::class,
                        'release'
                    ])
                    ->whereNumber('assignment')
                    ->name('assignments.release');

                    Route::post('/assignments/{assignment}/cancel', [
                        ConstructionManpowerAssignmentController::class,
                        'cancel'
                    ])
                    ->whereNumber('assignment')
                    ->name('assignments.cancel');

                    Route::delete('/assignments/{assignment}', [
                        ConstructionManpowerAssignmentController::class,
                        'destroy'
                    ])
                    ->whereNumber('assignment')
                    ->name('assignments.destroy');


                    /*
                    |--------------------------------------------------------------------------
                    | Manpower Dynamic Routes
                    |--------------------------------------------------------------------------
                    | Keep these AFTER assignments.
                    |--------------------------------------------------------------------------
                    */

                    Route::get('/{manpower}/edit', [
                        ConstructionManpowerController::class,
                        'edit'
                    ])
                    ->whereNumber('manpower')
                    ->name('edit');

                    Route::get('/{manpower}', [
                        ConstructionManpowerController::class,
                        'show'
                    ])
                    ->whereNumber('manpower')
                    ->name('show');

                    Route::put('/{manpower}', [
                        ConstructionManpowerController::class,
                        'update'
                    ])
                    ->whereNumber('manpower')
                    ->name('update');

                    Route::delete('/{manpower}', [
                        ConstructionManpowerController::class,
                        'destroy'
                    ])
                    ->whereNumber('manpower')
                    ->name('destroy');
                });

                /*
                |--------------------------------------------------------------------------
                | Daily Manpower Entries
                |--------------------------------------------------------------------------
                */

                Route::prefix('projects/{project}/construction/manpower/entries')
                    ->name('projects.construction.manpower.entries.')
                    ->group(function () {

                        Route::get('/', [
                            ConstructionManpowerEntryController::class,
                            'index'
                        ])->name('index');

                        Route::get('/create', [
                            ConstructionManpowerEntryController::class,
                            'create'
                        ])->name('create');

                        Route::post('/', [
                            ConstructionManpowerEntryController::class,
                            'store'
                        ])->name('store');

                        Route::get('/{entry}', [
                            ConstructionManpowerEntryController::class,
                            'show'
                        ])
                        ->whereNumber('entry')
                        ->name('show');

                        Route::get('/{entry}/edit', [
                            ConstructionManpowerEntryController::class,
                            'edit'
                        ])
                        ->whereNumber('entry')
                        ->name('edit');

                        Route::put('/{entry}', [
                            ConstructionManpowerEntryController::class,
                            'update'
                        ])
                        ->whereNumber('entry')
                        ->name('update');

                        Route::delete('/{entry}', [
                            ConstructionManpowerEntryController::class,
                            'destroy'
                        ])
                        ->whereNumber('entry')
                        ->name('destroy');

                });

                Route::prefix('projects/{project}/construction/payment-certificates')
                ->name('projects.construction.payment-certificates.')
                ->group(function () {

                    Route::get(
                        '/',
                        [ConstructionPaymentCertificateController::class, 'index']
                    )->name('index');

                    Route::get(
                        '/create',
                        [ConstructionPaymentCertificateController::class, 'create']
                    )->name('create');

                    Route::post(
                        '/',
                        [ConstructionPaymentCertificateController::class, 'store']
                    )->name('store');

                    Route::get(
                        '/{payment_certificate}',
                        [ConstructionPaymentCertificateController::class, 'show']
                    )
                        ->whereNumber('payment_certificate')
                        ->name('show');

                    Route::get(
                        '/{payment_certificate}/edit',
                        [ConstructionPaymentCertificateController::class, 'edit']
                    )
                        ->whereNumber('payment_certificate')
                        ->name('edit');

                    Route::put(
                        '/{payment_certificate}',
                        [ConstructionPaymentCertificateController::class, 'update']
                    )
                        ->whereNumber('payment_certificate')
                        ->name('update');

                    Route::post(
                        '/{payment_certificate}/submit',
                        [ConstructionPaymentCertificateController::class, 'submit']
                    )
                        ->whereNumber('payment_certificate')
                        ->name('submit');

                    Route::post(
                        '/{payment_certificate}/review',
                        [ConstructionPaymentCertificateController::class, 'review']
                    )
                        ->whereNumber('payment_certificate')
                        ->name('review');

                    Route::post(
                        '/{payment_certificate}/approve',
                        [ConstructionPaymentCertificateController::class, 'approve']
                    )
                        ->whereNumber('payment_certificate')
                        ->name('approve');

                    Route::post(
                        '/{payment_certificate}/reject',
                        [ConstructionPaymentCertificateController::class, 'reject']
                    )
                        ->whereNumber('payment_certificate')
                        ->name('reject');

                    Route::post(
                        '/{payment_certificate}/paid',
                        [ConstructionPaymentCertificateController::class, 'markPaid']
                    )
                        ->whereNumber('payment_certificate')
                        ->name('paid');

                    Route::delete(
                        '/{payment_certificate}',
                        [ConstructionPaymentCertificateController::class, 'destroy']
                    )
                        ->whereNumber('payment_certificate')
                        ->name('destroy');
                });


                Route::get(
                    'design-management',
                    [DesignManagementController::class, 'index']
                )->name('design-management.index');

                Route::prefix('projects/{project}/design-management')
                    ->name('projects.design-management.')
                    ->group(function () {

                    Route::get('/', [DesignDashboardController::class, 'index'])
                        ->name('dashboard');

                    Route::get('briefs', [DesignProjectBriefController::class, 'index'])
                        ->name('briefs.index');
                    Route::get('briefs/create', [DesignProjectBriefController::class, 'create'])
                        ->name('briefs.create');
                    Route::post('briefs', [DesignProjectBriefController::class, 'store'])
                        ->name('briefs.store');
                    Route::get('briefs/{brief}', [DesignProjectBriefController::class, 'show'])
                        ->name('briefs.show');
                    Route::get('briefs/{brief}/edit', [DesignProjectBriefController::class, 'edit'])
                        ->name('briefs.edit');
                    Route::put('briefs/{brief}', [DesignProjectBriefController::class, 'update'])
                        ->name('briefs.update');
                    Route::delete('briefs/{brief}', [DesignProjectBriefController::class, 'destroy'])
                        ->name('briefs.destroy');
                    Route::post('briefs/{brief}/submit', [DesignProjectBriefController::class, 'submit'])
                        ->name('briefs.submit');
                    Route::post('briefs/{brief}/approve', [DesignProjectBriefController::class, 'approve'])
                        ->name('briefs.approve');
                    Route::post('briefs/{brief}/reject', [DesignProjectBriefController::class, 'reject'])
                        ->name('briefs.reject');
                    Route::post('briefs/{brief}/revision', [DesignProjectBriefController::class, 'revision'])
                        ->name('briefs.revision');

                    Route::resource('consultants', DesignConsultantController::class);

                    Route::get('packages', [DesignPackageController::class, 'index'])
                        ->name('packages.index');
                    Route::get('packages/create', [DesignPackageController::class, 'create'])
                        ->name('packages.create');
                    Route::post('packages', [DesignPackageController::class, 'store'])
                        ->name('packages.store');
                    Route::get('packages/{package}', [DesignPackageController::class, 'show'])
                        ->name('packages.show');
                    Route::get('packages/{package}/edit', [DesignPackageController::class, 'edit'])
                        ->name('packages.edit');
                    Route::put('packages/{package}', [DesignPackageController::class, 'update'])
                        ->name('packages.update');
                    Route::delete('packages/{package}', [DesignPackageController::class, 'destroy'])
                        ->name('packages.destroy');
                    Route::post('packages/{package}/submit', [DesignPackageController::class, 'submit'])
                        ->name('packages.submit');
                    Route::post('packages/{package}/approve', [DesignPackageController::class, 'approve'])
                        ->name('packages.approve');
                    Route::post('packages/{package}/reject', [DesignPackageController::class, 'reject'])
                        ->name('packages.reject');
                    Route::post('packages/{package}/revision', [DesignPackageController::class, 'revision'])
                        ->name('packages.revision');

                    Route::get('drawings', [DesignDrawingController::class, 'index'])
                        ->name('drawings.index');
                    Route::get('drawings/create', [DesignDrawingController::class, 'create'])
                        ->name('drawings.create');
                    Route::post('drawings', [DesignDrawingController::class, 'store'])
                        ->name('drawings.store');
                    Route::get('drawings/{drawing}', [DesignDrawingController::class, 'show'])
                        ->name('drawings.show');
                    Route::get('drawings/{drawing}/edit', [DesignDrawingController::class, 'edit'])
                        ->name('drawings.edit');
                    Route::put('drawings/{drawing}', [DesignDrawingController::class, 'update'])
                        ->name('drawings.update');
                    Route::delete('drawings/{drawing}', [DesignDrawingController::class, 'destroy'])
                        ->name('drawings.destroy');
                    Route::post('drawings/{drawing}/submit', [DesignDrawingController::class, 'submit'])
                        ->name('drawings.submit');
                    Route::post('drawings/{drawing}/approve', [DesignDrawingController::class, 'approve'])
                        ->name('drawings.approve');
                    Route::post('drawings/{drawing}/reject', [DesignDrawingController::class, 'reject'])
                        ->name('drawings.reject');
                    Route::post('drawings/{drawing}/revision', [DesignDrawingController::class, 'revision'])
                        ->name('drawings.revision');

                    Route::get('submittals', [DesignSubmittalController::class, 'index'])
                        ->name('submittals.index');
                    Route::get('submittals/create', [DesignSubmittalController::class, 'create'])
                        ->name('submittals.create');
                    Route::post('submittals', [DesignSubmittalController::class, 'store'])
                        ->name('submittals.store');
                    Route::get('submittals/{submittal}', [DesignSubmittalController::class, 'show'])
                        ->name('submittals.show');
                    Route::get('submittals/{submittal}/edit', [DesignSubmittalController::class, 'edit'])
                        ->name('submittals.edit');
                    Route::put('submittals/{submittal}', [DesignSubmittalController::class, 'update'])
                        ->name('submittals.update');
                    Route::delete('submittals/{submittal}', [DesignSubmittalController::class, 'destroy'])
                        ->name('submittals.destroy');
                    Route::post('submittals/{submittal}/submit', [DesignSubmittalController::class, 'submit'])
                        ->name('submittals.submit');
                    Route::post('submittals/{submittal}/approve', [DesignSubmittalController::class, 'approve'])
                        ->name('submittals.approve');
                    Route::post('submittals/{submittal}/reject', [DesignSubmittalController::class, 'reject'])
                        ->name('submittals.reject');
                    Route::post('submittals/{submittal}/revision', [DesignSubmittalController::class, 'revision'])
                        ->name('submittals.revision');

                    Route::get('reviews', [DesignReviewController::class, 'index'])
                        ->name('reviews.index');
                    Route::get('reviews/create', [DesignReviewController::class, 'create'])
                        ->name('reviews.create');
                    Route::post('reviews', [DesignReviewController::class, 'store'])
                        ->name('reviews.store');
                    Route::get('reviews/{review}', [DesignReviewController::class, 'show'])
                        ->name('reviews.show');
                    Route::get('reviews/{review}/edit', [DesignReviewController::class, 'edit'])
                        ->name('reviews.edit');
                    Route::put('reviews/{review}', [DesignReviewController::class, 'update'])
                        ->name('reviews.update');
                    Route::delete('reviews/{review}', [DesignReviewController::class, 'destroy'])
                        ->name('reviews.destroy');
                    Route::post('reviews/{review}/submit', [DesignReviewController::class, 'submit'])
                        ->name('reviews.submit');
                    Route::post('reviews/{review}/approve', [DesignReviewController::class, 'approve'])
                        ->name('reviews.approve');
                    Route::post('reviews/{review}/reject', [DesignReviewController::class, 'reject'])
                        ->name('reviews.reject');

                    Route::get('comments', [DesignCommentController::class, 'index'])
                        ->name('comments.index');
                    Route::get('comments/create', [DesignCommentController::class, 'create'])
                        ->name('comments.create');
                    Route::post('comments', [DesignCommentController::class, 'store'])
                        ->name('comments.store');
                    Route::get('comments/{comment}', [DesignCommentController::class, 'show'])
                        ->name('comments.show');
                    Route::get('comments/{comment}/edit', [DesignCommentController::class, 'edit'])
                        ->name('comments.edit');
                    Route::put('comments/{comment}', [DesignCommentController::class, 'update'])
                        ->name('comments.update');
                    Route::delete('comments/{comment}', [DesignCommentController::class, 'destroy'])
                        ->name('comments.destroy');
                    Route::post('comments/{comment}/submit', [DesignCommentController::class, 'submit'])
                        ->name('comments.submit');
                    Route::post('comments/{comment}/approve', [DesignCommentController::class, 'approve'])
                        ->name('comments.approve');
                    Route::post('comments/{comment}/reject', [DesignCommentController::class, 'reject'])
                        ->name('comments.reject');

                    Route::get('rfis', [DesignRfiController::class, 'index'])
                        ->name('rfis.index');
                    Route::get('rfis/create', [DesignRfiController::class, 'create'])
                        ->name('rfis.create');
                    Route::post('rfis', [DesignRfiController::class, 'store'])
                        ->name('rfis.store');
                    Route::get('rfis/{rfi}', [DesignRfiController::class, 'show'])
                        ->name('rfis.show');
                    Route::get('rfis/{rfi}/edit', [DesignRfiController::class, 'edit'])
                        ->name('rfis.edit');
                    Route::put('rfis/{rfi}', [DesignRfiController::class, 'update'])
                        ->name('rfis.update');
                    Route::delete('rfis/{rfi}', [DesignRfiController::class, 'destroy'])
                        ->name('rfis.destroy');
                    Route::post('rfis/{rfi}/submit', [DesignRfiController::class, 'submit'])
                        ->name('rfis.submit');
                    Route::post('rfis/{rfi}/approve', [DesignRfiController::class, 'approve'])
                        ->name('rfis.approve');
                    Route::post('rfis/{rfi}/reject', [DesignRfiController::class, 'reject'])
                        ->name('rfis.reject');

                    Route::get('changes', [DesignChangeController::class, 'index'])
                        ->name('changes.index');
                    Route::get('changes/create', [DesignChangeController::class, 'create'])
                        ->name('changes.create');
                    Route::post('changes', [DesignChangeController::class, 'store'])
                        ->name('changes.store');
                    Route::get('changes/{change}', [DesignChangeController::class, 'show'])
                        ->name('changes.show');
                    Route::get('changes/{change}/edit', [DesignChangeController::class, 'edit'])
                        ->name('changes.edit');
                    Route::put('changes/{change}', [DesignChangeController::class, 'update'])
                        ->name('changes.update');
                    Route::delete('changes/{change}', [DesignChangeController::class, 'destroy'])
                        ->name('changes.destroy');
                    Route::post('changes/{change}/submit', [DesignChangeController::class, 'submit'])
                        ->name('changes.submit');
                    Route::post('changes/{change}/approve', [DesignChangeController::class, 'approve'])
                        ->name('changes.approve');
                    Route::post('changes/{change}/reject', [DesignChangeController::class, 'reject'])
                        ->name('changes.reject');
                    Route::post('changes/{change}/revision', [DesignChangeController::class, 'revision'])
                        ->name('changes.revision');
                    Route::post('changes/{change}/cost-impacts', [DesignChangeController::class, 'storeCostImpact'])
                        ->name('changes.cost-impacts.store');
                    Route::delete('changes/{change}/cost-impacts/{costImpact}', [DesignChangeController::class, 'destroyCostImpact'])
                        ->name('changes.cost-impacts.destroy');

                    Route::get('approvals', [DesignApprovalController::class, 'index'])
                        ->name('approvals.index');
                });



    });


