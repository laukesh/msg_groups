<?php

use App\Http\Controllers\Admin\Construction\ConstructionClaimController;
use App\Http\Controllers\Admin\Construction\ConstructionClaimDocumentController;
use App\Http\Controllers\Admin\Construction\ConstructionCorrespondenceController;
use App\Http\Controllers\Admin\Construction\ConstructionCorrespondenceDocumentController;
use App\Http\Controllers\Admin\Construction\ConstructionDelayController;
use App\Http\Controllers\Admin\Construction\ConstructionDelayDocumentController;
use App\Http\Controllers\Admin\Construction\ConstructionRiskActionController;
use App\Http\Controllers\Admin\Construction\ConstructionRiskController;
use App\Http\Controllers\Admin\Construction\ConstructionRiskDocumentController;
use App\Support\RouteHelper as R;
use Illuminate\Support\Facades\Route;

$base = 'projects/{project}/construction';
$name = 'projects.construction.';

/* Documents with view + download: index/create/store/destroy + /{document}/view|download */
$viewDownload = function (string $controller) {
    return function () use ($controller) {
        Route::get('/{document}/view', [$controller, 'view'])->name('view');
        Route::get('/{document}/download', [$controller, 'download'])->name('download');
    };
};
$docOnly = ['index', 'create', 'store', 'destroy'];

/* Claims & delays share the same assess / approve / reject workflow */
foreach ([
    'claims' => [ConstructionClaimController::class, ConstructionClaimDocumentController::class, 'claim'],
    'delays' => [ConstructionDelayController::class, ConstructionDelayDocumentController::class, 'delay'],
] as $slug => [$controller, $docController, $param]) {
    $p = '{' . $param . '}';

    R::crud("$base/$slug", "{$name}$slug.", $controller, $param,
        actions: ['submit', 'review', 'close'],
        where: R::num($param),
        before: function () use ($controller, $p, $param) {
            Route::get("/$p/assess", [$controller, 'assessment'])->name('assessment');
            Route::post("/$p/assess", [$controller, 'assess'])->name('assess');
            Route::get("/$p/approve", [$controller, 'approval'])->name('approval');
            Route::post("/$p/approve", [$controller, 'approve'])->name('approve');
            Route::get("/$p/reject", [$controller, 'rejection'])->name('rejection');
            Route::post("/$p/reject", [$controller, 'reject'])->name('reject');
        });

    R::crud("$base/$slug/$p/documents", "{$name}$slug.documents.", $docController, 'document',
        only: $docOnly, where: R::num($param, 'document'), before: $viewDownload($docController));
}

/* Risks */
R::crud("$base/risks", "{$name}risks.", ConstructionRiskController::class, 'risk',
    actions: ['submit', 'assess', 'mitigation', 'monitor', 'escalate', 'accept', 'close'],
    where: R::num('risk'),
    before: fn () => Route::patch('/{risk}', [ConstructionRiskController::class, 'update']));

R::crud("$base/risks/{risk}/actions", "{$name}risks.actions.", ConstructionRiskActionController::class, 'action',
    only: R::NO_SHOW, where: R::num('risk', 'action'));

R::crud("$base/risks/{risk}/documents", "{$name}risks.documents.", ConstructionRiskDocumentController::class, 'document',
    only: $docOnly, where: R::num('risk', 'document'), before: $viewDownload(ConstructionRiskDocumentController::class));

/* Correspondence */
R::crud("$base/correspondence", "{$name}correspondence.", ConstructionCorrespondenceController::class, 'correspondence',
    actions: ['register', 'review', 'respond', 'close', 'archive'],
    where: R::num('correspondence'),
    before: function () {
        $c = ConstructionCorrespondenceController::class;
        // route name uses an underscore (kept as before)
        Route::post('/{correspondence}/action-required', [$c, 'actionRequired'])->name('action_required');
        Route::patch('/{correspondence}', [$c, 'update']);
    });

R::crud("$base/correspondence/{correspondence}/documents", "{$name}correspondence.documents.",
    ConstructionCorrespondenceDocumentController::class, 'document',
    only: $docOnly, where: R::num('correspondence', 'document'),
    before: $viewDownload(ConstructionCorrespondenceDocumentController::class));
