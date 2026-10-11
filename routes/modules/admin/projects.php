<?php

use App\Http\Controllers\Admin\Project\DevelopmentStrategyController;
use App\Http\Controllers\Admin\Project\MasterScheduleActivityController;
use App\Http\Controllers\Admin\Project\MasterScheduleController;
use App\Http\Controllers\Admin\Project\ProjectApprovalMatrixController;
use App\Http\Controllers\Admin\Project\ProjectBudgetCategoryController;
use App\Http\Controllers\Admin\Project\ProjectBudgetController;
use App\Http\Controllers\Admin\Project\ProjectBudgetItemController;
use App\Http\Controllers\Admin\Project\ProjectContractStrategyController;
use App\Http\Controllers\Admin\Project\ProjectController;
use App\Http\Controllers\Admin\Project\ProjectDashboardController;
use App\Http\Controllers\Admin\Project\ProjectDecisionRegisterController;
use App\Http\Controllers\Admin\Project\ProjectDeliveryStrategyController;
use App\Http\Controllers\Admin\Project\ProjectFundingCommitmentController;
use App\Http\Controllers\Admin\Project\ProjectFundingPlanController;
use App\Http\Controllers\Admin\Project\ProjectFundingSourceController;
use App\Http\Controllers\Admin\Project\ProjectFundingTrancheController;
use App\Http\Controllers\Admin\Project\ProjectGovernanceController;
use App\Http\Controllers\Admin\Project\ProjectGovernanceFollowUpController;
use App\Http\Controllers\Admin\Project\ProjectGovernanceMeetingActionItemController;
use App\Http\Controllers\Admin\Project\ProjectGovernanceMeetingAgendaItemController;
use App\Http\Controllers\Admin\Project\ProjectGovernanceMeetingAttendeeController;
use App\Http\Controllers\Admin\Project\ProjectGovernanceMeetingController;
use App\Http\Controllers\Admin\Project\ProjectGovernanceMeetingDecisionController;
use App\Http\Controllers\Admin\Project\ProjectGovernanceMeetingDocumentController;
use App\Http\Controllers\Admin\Project\ProjectGovernanceMeetingMinutesController;
use App\Http\Controllers\Admin\Project\ProjectProcurementStrategyController;
use App\Http\Controllers\Admin\Project\ProjectRiskController;
use App\Http\Controllers\Admin\Project\ProjectStakeholderController;
use App\Support\RouteHelper as R;
use Illuminate\Support\Facades\Route;

/* Project core + status workflow */
R::crud(
    'projects', 'projects.', ProjectController::class, 'project',
    actions: ['submit', 'approve', 'reject', 'start', 'hold', 'resume', 'delay', 'complete', 'cancel', 'close'],
    before: function () {
        Route::get('/{project}/dashboard', [ProjectDashboardController::class, 'index'])->name('dashboard');
        Route::post('/{project}/resolve-delay', [ProjectController::class, 'resolveDelay'])->name('resolve_delay');
    }
);

/* Project planning registers: all live under projects/{project}/<slug> and projects.<slug>.* */
$workflow = ['submit', 'approve', 'reject', 'revision'];
$status   = ['status' => 'changeStatus'];

$registers = [
    // slug                  controller                                   param               actions
    ['development-strategy', DevelopmentStrategyController::class,         'developmentStrategy', []],
    ['master-schedule',      MasterScheduleController::class,              'masterSchedule',      []],
    ['budget',               ProjectBudgetController::class,               'projectBudget',       ['revision' => 'createRevision', 'submit', 'approve', 'reject']],
    ['funding-plan',         ProjectFundingPlanController::class,          'fundingPlan',         ['revision' => 'createRevision', 'submit', 'approve', 'reject']],
    ['delivery-strategy',    ProjectDeliveryStrategyController::class,     'deliveryStrategy',    $workflow],
    ['procurement-strategy', ProjectProcurementStrategyController::class,  'procurementStrategy', $workflow],
    ['contract-strategy',    ProjectContractStrategyController::class,     'contractStrategy',    $workflow],
    ['risks',                ProjectRiskController::class,                 'risk',                $status],
    ['stakeholders',         ProjectStakeholderController::class,          'stakeholder',         $status],
    ['approval-matrix',      ProjectApprovalMatrixController::class,       'approvalMatrix',      $status],
    ['decision-register',    ProjectDecisionRegisterController::class,     'decision',            $status],
];

foreach ($registers as [$slug, $controller, $param, $actions]) {
    R::crud("projects/{project}/$slug", "projects.$slug.", $controller, $param, actions: $actions);
}

$childOnly = ['create', 'store', 'edit', 'update', 'destroy'];

/* Master schedule activities */
R::crud('projects/{project}/master-schedule/{masterSchedule}/activities', 'projects.master-schedule.activities.',
    MasterScheduleActivityController::class, 'activity', only: ['store', 'edit', 'update', 'destroy']);

/* Budget categories & items */
R::crud('projects/{project}/budget/{projectBudget}/categories', 'projects.budget.categories.',
    ProjectBudgetCategoryController::class, 'category', only: ['store', 'edit', 'update', 'destroy']);
R::crud('projects/{project}/budget/{projectBudget}/items', 'projects.budget.items.',
    ProjectBudgetItemController::class, 'item', only: ['store', 'edit', 'update', 'destroy']);

/* Funding plan: sources > commitments > tranches */
$fp = 'projects/{project}/funding-plan/{fundingPlan}';
R::crud("$fp/sources", 'projects.funding-plan.sources.',
    ProjectFundingSourceController::class, 'fundingSource', only: $childOnly);
R::crud("$fp/sources/{fundingSource}/commitments", 'projects.funding-plan.commitments.',
    ProjectFundingCommitmentController::class, 'fundingCommitment', only: $childOnly);
R::crud("$fp/sources/{fundingSource}/commitments/{fundingCommitment}/tranches", 'projects.funding-plan.tranches.',
    ProjectFundingTrancheController::class, 'fundingTranche', only: $childOnly);

/* Governance (follow-up must be registered before '/{governance}') */
R::crud('projects/{project}/governance', 'projects.governance.', ProjectGovernanceController::class, 'governance',
    actions: $status,
    before: fn () => Route::get('follow-up', [ProjectGovernanceFollowUpController::class, 'index'])->name('follow-up.index'));

/* Governance meetings + children */
R::crud('projects/{project}/governance-meetings', 'projects.governance-meetings.',
    ProjectGovernanceMeetingController::class, 'meeting', actions: $status);

$meeting = 'projects/{project}/governance-meetings/{meeting}';
$meetingChildren = [
    ['attendees',    ProjectGovernanceMeetingAttendeeController::class,   'attendee'],
    ['agenda-items', ProjectGovernanceMeetingAgendaItemController::class, 'agendaItem'],
    ['action-items', ProjectGovernanceMeetingActionItemController::class, 'actionItem'],
    ['decisions',    ProjectGovernanceMeetingDecisionController::class,   'decision'],
];
foreach ($meetingChildren as [$slug, $controller, $param]) {
    R::crud("$meeting/$slug", "projects.governance-meetings.$slug.", $controller, $param,
        only: R::NO_SHOW, actions: $status);
}

R::crud("$meeting/minutes", 'projects.governance-meetings.minutes.',
    ProjectGovernanceMeetingMinutesController::class, 'minutes',
    only: R::NO_SHOW, actions: ['submit', 'approve', 'reject']);

R::crud("$meeting/documents", 'projects.governance-meetings.documents.',
    ProjectGovernanceMeetingDocumentController::class, 'document',
    only: ['index', 'create', 'store', 'destroy'],
    before: function () {
        Route::get('/{document}/download', [ProjectGovernanceMeetingDocumentController::class, 'download'])->name('download');
        Route::get('/{document}/preview', [ProjectGovernanceMeetingDocumentController::class, 'preview'])->name('preview');
    });
