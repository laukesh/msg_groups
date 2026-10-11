<?php

use App\Http\Controllers\Admin\Construction\ConstructionHseController;
use App\Http\Controllers\Admin\Construction\ConstructionHseCorrectiveActionController;
use App\Http\Controllers\Admin\Construction\ConstructionHseEnvironmentalActionController;
use App\Http\Controllers\Admin\Construction\ConstructionHseEnvironmentalComplianceController;
use App\Http\Controllers\Admin\Construction\ConstructionHseEnvironmentalRecordController;
use App\Http\Controllers\Admin\Construction\ConstructionHseIncidentActionController;
use App\Http\Controllers\Admin\Construction\ConstructionHseIncidentController;
use App\Http\Controllers\Admin\Construction\ConstructionHseIncidentDocumentController;
use App\Http\Controllers\Admin\Construction\ConstructionHseIncidentInvestigationController;
use App\Http\Controllers\Admin\Construction\ConstructionHseIncidentPersonController;
use App\Http\Controllers\Admin\Construction\ConstructionHseIncidentWitnessController;
use App\Http\Controllers\Admin\Construction\ConstructionHseInspectionActionController;
use App\Http\Controllers\Admin\Construction\ConstructionHseInspectionController;
use App\Http\Controllers\Admin\Construction\ConstructionHseInspectionDocumentController;
use App\Http\Controllers\Admin\Construction\ConstructionHseInspectionFindingController;
use App\Http\Controllers\Admin\Construction\ConstructionHseInspectionItemController;
use App\Http\Controllers\Admin\Construction\ConstructionHseObservationController;
use App\Http\Controllers\Admin\Construction\ConstructionHseSafetyMeetingController;
use App\Http\Controllers\Admin\Construction\ConstructionHseSafetyMeetingDocumentController;
use App\Http\Controllers\Admin\Construction\ConstructionHseSafetyMeetingParticipantController;
use App\Http\Controllers\Admin\Construction\ConstructionHseToolboxTalkController;
use App\Http\Controllers\Admin\Construction\ConstructionHseToolboxTalkDocumentController;
use App\Http\Controllers\Admin\Construction\ConstructionHseToolboxTalkParticipantController;
use App\Support\RouteHelper as R;
use Illuminate\Support\Facades\Route;

$hse  = 'projects/{project}/construction/hse';
$hseN = 'projects.construction.hse.';

/* Documents with a download link: index/create/store/show/destroy + /{document}/download */
$withDownload = function (string $controller) {
    return fn () => Route::get('/{document}/download', [$controller, 'download'])->name('download');
};

Route::get($hse, [ConstructionHseController::class, 'index'])->name("{$hseN}index");

/* Observations + corrective actions */
R::crud("$hse/observations", "{$hseN}observations.", ConstructionHseObservationController::class, 'observation',
    actions: ['start', 'verify', 'close', 'reopen']);

R::crud("$hse/observations/{observation}/corrective-actions", "{$hseN}observations.corrective-actions.",
    ConstructionHseCorrectiveActionController::class, 'correctiveAction',
    actions: ['start', 'resolve', 'verify', 'reject-verification', 'close']);

/* Incidents */
R::crud("$hse/incidents", "{$hseN}incidents.", ConstructionHseIncidentController::class, 'incident',
    actions: ['start' => 'startInvestigation', 'verify', 'close']);

$incident = "$hse/incidents/{incident}";

R::crud("$incident/documents", "{$hseN}incidents.documents.", ConstructionHseIncidentDocumentController::class, 'document',
    only: R::DOCS);

R::crud("$incident/investigations", "{$hseN}incidents.investigations.", ConstructionHseIncidentInvestigationController::class, 'investigation',
    actions: ['submit', 'approve', 'reject']);

R::crud("$incident/actions", "{$hseN}incidents.actions.", ConstructionHseIncidentActionController::class, 'action',
    actions: ['start', 'complete', 'verify', 'reject-verification']);

R::crud("$incident/persons", "{$hseN}incidents.persons.", ConstructionHseIncidentPersonController::class, 'person');
R::crud("$incident/witnesses", "{$hseN}incidents.witnesses.", ConstructionHseIncidentWitnessController::class, 'witness');

/* Inspections */
R::crud("$hse/inspections", "{$hseN}inspections.", ConstructionHseInspectionController::class, 'inspection');

$inspection = "$hse/inspections/{inspection}";

R::crud("$inspection/items", "{$hseN}inspections.items.", ConstructionHseInspectionItemController::class, 'item');
R::crud("$inspection/findings", "{$hseN}inspections.findings.", ConstructionHseInspectionFindingController::class, 'finding');
R::crud("$inspection/findings/{finding}/actions", "{$hseN}inspections.findings.actions.",
    ConstructionHseInspectionActionController::class, 'action');

Route::get("$inspection/actions", [ConstructionHseInspectionActionController::class, 'inspectionIndex'])
    ->name("{$hseN}inspections.actions.index");

R::crud("$inspection/documents", "{$hseN}inspections.documents.", ConstructionHseInspectionDocumentController::class, 'document',
    only: R::DOCS, before: $withDownload(ConstructionHseInspectionDocumentController::class));

/* Safety meetings */
R::crud("$hse/safety-meetings", "{$hseN}safety-meetings.", ConstructionHseSafetyMeetingController::class, 'meeting');
R::crud("$hse/safety-meetings/{meeting}/participants", "{$hseN}safety-meetings.participants.",
    ConstructionHseSafetyMeetingParticipantController::class, 'participant');
R::crud("$hse/safety-meetings/{meeting}/documents", "{$hseN}safety-meetings.documents.",
    ConstructionHseSafetyMeetingDocumentController::class, 'document',
    only: R::DOCS, before: $withDownload(ConstructionHseSafetyMeetingDocumentController::class));

/* Toolbox talks */
R::crud("$hse/toolbox-talks", "{$hseN}toolbox-talks.", ConstructionHseToolboxTalkController::class, 'toolboxTalk');
R::crud("$hse/toolbox-talks/{toolboxTalk}/participants", "{$hseN}toolbox-talks.participants.",
    ConstructionHseToolboxTalkParticipantController::class, 'participant');
R::crud("$hse/toolbox-talks/{toolboxTalk}/documents", "{$hseN}toolbox-talks.documents.",
    ConstructionHseToolboxTalkDocumentController::class, 'document',
    only: R::DOCS, before: $withDownload(ConstructionHseToolboxTalkDocumentController::class));

/* Environmental */
R::crud("$hse/environmental/records", "{$hseN}environmental.records.", ConstructionHseEnvironmentalRecordController::class, 'record');
R::crud("$hse/environmental/compliances", "{$hseN}environmental.compliances.", ConstructionHseEnvironmentalComplianceController::class, 'compliance');
R::crud("$hse/environmental/actions", "{$hseN}environmental.actions.", ConstructionHseEnvironmentalActionController::class, 'action');
