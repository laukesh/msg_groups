<?php

namespace App\Http\Controllers\Admin\Handover;

use App\Http\Controllers\Controller;
use App\Models\HandoverProject;
use App\Models\HandoverSnag;
use App\Models\Project;
use App\Models\ProcurementContract;
use App\Models\User;
use App\Services\CommissioningAuditLogService;
use App\Services\HandoverReadinessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HandoverSnagController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        Project $project,
        Request $request
    ): View {

        $handover = HandoverProject::where(
            'project_id',
            $project->id
        )
            ->latest('id')
            ->first();

        if (!$handover) {
            return view(
                'admin.handover.not-started',
                compact('project')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Snag Query
        |--------------------------------------------------------------------------
        */

        $query = HandoverSnag::query()
            ->where('project_id', $project->id)
            ->where('handover_project_id', $handover->id)
            ->with([
                'raisedBy',
                'assignedTo',
                'rectifiedBy',
                'verifiedBy',

                'procurementContract.bidder',
                'procurementContract.tender.package.procurementPlan',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('snag_no', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('building', 'like', "%{$search}%")
                    ->orWhere('floor', 'like', "%{$search}%")
                    ->orWhere('zone', 'like', "%{$search}%")
                    ->orWhere('unit', 'like', "%{$search}%");

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        if ($request->filled('priority')) {
            $query->where(
                'priority',
                $request->priority
            );
        }

        if ($request->filled('discipline')) {
            $query->where(
                'discipline',
                $request->discipline
            );
        }

        if ($request->filled('category')) {
            $query->where(
                'category',
                $request->category
            );
        }

        if ($request->status === 'Overdue') {

            $query->whereNotNull('due_date')
                ->whereDate(
                    'due_date',
                    '<',
                    now()->toDateString()
                )
                ->whereNotIn(
                    'status',
                    ['Closed']
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $snags = $query
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | KPIs
        |--------------------------------------------------------------------------
        */

        $allSnags = HandoverSnag::where(
            'project_id',
            $project->id
        )
            ->where(
                'handover_project_id',
                $handover->id
            )
            ->get();

        $totalSnags = $allSnags->count();

        $openSnags = $allSnags
            ->whereIn('status', [
                'Open',
                'Assigned',
                'In Progress',
                'Rectification Submitted',
                'Under Verification',
                'Rejected',
            ])
            ->count();

        $closedSnags = $allSnags
            ->where('status', 'Closed')
            ->count();

        $criticalSnags = $allSnags
            ->where('priority', 'Critical')
            ->where('status', '!=', 'Closed')
            ->count();

        $verificationSnags = $allSnags
            ->where('status', 'Under Verification')
            ->count();

        $rejectedSnags = $allSnags
            ->where('status', 'Rejected')
            ->count();

        $overdueSnags = $allSnags
            ->filter(function ($snag) {

                return $snag->due_date
                    && $snag->due_date->isPast()
                    && $snag->status !== 'Closed';

            })
            ->count();

        $closurePercentage = $totalSnags > 0
            ? round(
                ($closedSnags / $totalSnags) * 100,
                2
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */

        $users = User::query()
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Procurement Contracts
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Procurement contracts do not have project_id.
        |
        | Project
        |   -> ProcurementPlan
        |   -> ProcurementPackage
        |   -> ProcurementTender
        |   -> ProcurementContract
        |
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
            ->orderByDesc('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Filter Options
        |--------------------------------------------------------------------------
        */

        $categories = HandoverSnag::query()
            ->where('project_id', $project->id)
            ->where(
                'handover_project_id',
                $handover->id
            )
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $disciplines = HandoverSnag::query()
            ->where('project_id', $project->id)
            ->where(
                'handover_project_id',
                $handover->id
            )
            ->whereNotNull('discipline')
            ->where('discipline', '!=', '')
            ->distinct()
            ->orderBy('discipline')
            ->pluck('discipline');

        $statuses = [
            'Open',
            'Assigned',
            'In Progress',
            'Rectification Submitted',
            'Under Verification',
            'Rejected',
            'Closed',
        ];

        $priorities = [
            'Low',
            'Medium',
            'High',
            'Critical',
        ];

        return view(
            'admin.handover.snags.index',
            compact(
                'project',
                'handover',
                'snags',

                'totalSnags',
                'openSnags',
                'closedSnags',
                'criticalSnags',
                'verificationSnags',
                'rejectedSnags',
                'overdueSnags',
                'closurePercentage',

                'users',
                'contracts',

                'categories',
                'disciplines',
                'statuses',
                'priorities'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        Project $project,
        Request $request
    ): RedirectResponse {

        $handover = $this->getHandover($project);

        $validated = $request->validate([
            'procurement_contract_id' => [
                'nullable',
                'exists:procurement_contracts,id',
            ],

            'category' => [
                'nullable',
                'string',
                'max:150',
            ],

            'discipline' => [
                'nullable',
                'string',
                'max:150',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'building' => [
                'nullable',
                'string',
                'max:150',
            ],

            'floor' => [
                'nullable',
                'string',
                'max:150',
            ],

            'zone' => [
                'nullable',
                'string',
                'max:150',
            ],

            'unit' => [
                'nullable',
                'string',
                'max:150',
            ],

            'priority' => [
                'required',
                'in:Low,Medium,High,Critical',
            ],

            'assigned_to' => [
                'nullable',
                'exists:users,id',
            ],

            'raised_date' => [
                'nullable',
                'date',
            ],

            'due_date' => [
                'nullable',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validate Procurement Contract Belongs To Project
        |--------------------------------------------------------------------------
        */

        $this->validateProcurementContract(
            $project,
            $validated['procurement_contract_id'] ?? null
        );

        DB::beginTransaction();

        try {

            $nextNumber = HandoverSnag::where(
                'project_id',
                $project->id
            )->count() + 1;

            $snagNo =
                'SNAG-' .
                str_pad(
                    $project->id,
                    5,
                    '0',
                    STR_PAD_LEFT
                ) .
                '-' .
                str_pad(
                    $nextNumber,
                    4,
                    '0',
                    STR_PAD_LEFT
                );

            /*
             * Make absolutely sure the generated number is unique.
             */

            while (
                HandoverSnag::where(
                    'project_id',
                    $project->id
                )
                    ->where(
                        'snag_no',
                        $snagNo
                    )
                    ->exists()
            ) {

                $nextNumber++;

                $snagNo =
                    'SNAG-' .
                    str_pad(
                        $project->id,
                        5,
                        '0',
                        STR_PAD_LEFT
                    ) .
                    '-' .
                    str_pad(
                        $nextNumber,
                        4,
                        '0',
                        STR_PAD_LEFT
                    );
            }

            $snag = HandoverSnag::create([
                'project_id' => $project->id,
                'handover_project_id' => $handover->id,

                'procurement_contract_id' =>
                    $validated['procurement_contract_id'] ?? null,

                'snag_no' => $snagNo,

                'category' =>
                    $validated['category'] ?? null,

                'discipline' =>
                    $validated['discipline'] ?? null,

                'title' =>
                    $validated['title'],

                'description' =>
                    $validated['description'] ?? null,

                'location' =>
                    $validated['location'] ?? null,

                'building' =>
                    $validated['building'] ?? null,

                'floor' =>
                    $validated['floor'] ?? null,

                'zone' =>
                    $validated['zone'] ?? null,

                'unit' =>
                    $validated['unit'] ?? null,

                'priority' =>
                    $validated['priority'],

                'assigned_to' =>
                    $validated['assigned_to'] ?? null,

                'raised_date' =>
                    $validated['raised_date']
                    ?? now()->toDateString(),

                'due_date' =>
                    $validated['due_date'] ?? null,

                'status' => 'Open',

                'remarks' =>
                    $validated['remarks'] ?? null,

                'raised_by' =>
                    auth()->id(),

                'created_by' =>
                    auth()->id(),

                'updated_by' =>
                    auth()->id(),
            ]);

            CommissioningAuditLogService::action(
                'snag_created',
                $snag,
                "Handover snag {$snag->snag_no} created."
            );

            DB::commit();

            return back()->with(
                'success',
                'Snag / punch list item created successfully.'
            );

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to create snag.'
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Project $project,
        Request $request,
        HandoverSnag $snag
    ): RedirectResponse {

        $this->validateSnagProject(
            $project,
            $snag
        );

        if (!in_array($snag->status, [
            'Open',
            'Assigned',
            'Rejected',
        ])) {

            return back()->with(
                'error',
                'This snag cannot be edited in its current status.'
            );
        }

        $validated = $request->validate([
            'procurement_contract_id' => [
                'nullable',
                'exists:procurement_contracts,id',
            ],

            'category' => [
                'nullable',
                'string',
                'max:150',
            ],

            'discipline' => [
                'nullable',
                'string',
                'max:150',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'building' => [
                'nullable',
                'string',
                'max:150',
            ],

            'floor' => [
                'nullable',
                'string',
                'max:150',
            ],

            'zone' => [
                'nullable',
                'string',
                'max:150',
            ],

            'unit' => [
                'nullable',
                'string',
                'max:150',
            ],

            'priority' => [
                'required',
                'in:Low,Medium,High,Critical',
            ],

            'assigned_to' => [
                'nullable',
                'exists:users,id',
            ],

            'raised_date' => [
                'nullable',
                'date',
            ],

            'due_date' => [
                'nullable',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        $this->validateProcurementContract(
            $project,
            $validated['procurement_contract_id'] ?? null
        );

        $oldValues = $snag->getAttributes();

        $snag->update([
            'procurement_contract_id' =>
                $validated['procurement_contract_id'] ?? null,

            'category' =>
                $validated['category'] ?? null,

            'discipline' =>
                $validated['discipline'] ?? null,

            'title' =>
                $validated['title'],

            'description' =>
                $validated['description'] ?? null,

            'location' =>
                $validated['location'] ?? null,

            'building' =>
                $validated['building'] ?? null,

            'floor' =>
                $validated['floor'] ?? null,

            'zone' =>
                $validated['zone'] ?? null,

            'unit' =>
                $validated['unit'] ?? null,

            'priority' =>
                $validated['priority'],

            'assigned_to' =>
                $validated['assigned_to'] ?? null,

            'raised_date' =>
                $validated['raised_date'] ?? null,

            'due_date' =>
                $validated['due_date'] ?? null,

            'remarks' =>
                $validated['remarks'] ?? null,

            'updated_by' =>
                auth()->id(),
        ]);

        CommissioningAuditLogService::action(
            'snag_updated',
            $snag,
            "Handover snag {$snag->snag_no} updated.",
            $oldValues,
            $snag->getAttributes()
        );

        return back()->with(
            'success',
            'Snag updated successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        HandoverSnag $snag
    ): RedirectResponse {

        $this->validateSnagProject(
            $project,
            $snag
        );

        if (!in_array($snag->status, [
            'Open',
            'Assigned',
            'Rejected',
        ])) {

            return back()->with(
                'error',
                'This snag cannot be deleted in its current status.'
            );
        }

        $oldValues = $snag->getAttributes();

        $snag->delete();

        CommissioningAuditLogService::action(
            'snag_deleted',
            $snag,
            "Handover snag {$snag->snag_no} deleted.",
            $oldValues
        );

        return back()->with(
            'success',
            'Snag deleted successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ASSIGN
    |--------------------------------------------------------------------------
    */

    public function assign(
        Project $project,
        Request $request,
        HandoverSnag $snag
    ): RedirectResponse {

        $this->validateSnagProject(
            $project,
            $snag
        );

        if (!in_array($snag->status, [
            'Open',
            'Rejected',
        ])) {

            return back()->with(
                'error',
                'This snag cannot be assigned in its current status.'
            );
        }

        $validated = $request->validate([
            'assigned_to' => [
                'required',
                'exists:users,id',
            ],
        ]);

        $oldValues = $snag->getAttributes();

        $snag->update([
            'assigned_to' =>
                $validated['assigned_to'],

            'status' =>
                'Assigned',

            'updated_by' =>
                auth()->id(),
        ]);

        CommissioningAuditLogService::action(
            'snag_assigned',
            $snag,
            "Handover snag {$snag->snag_no} assigned.",
            $oldValues,
            $snag->getAttributes()
        );

        return back()->with(
            'success',
            'Snag assigned successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | START
    |--------------------------------------------------------------------------
    */

    public function start(
        Project $project,
        HandoverSnag $snag
    ): RedirectResponse {

        $this->validateSnagProject(
            $project,
            $snag
        );

        if ($snag->status !== 'Assigned') {

            return back()->with(
                'error',
                'Only assigned snags can be started.'
            );
        }

        $oldValues = $snag->getAttributes();

        $snag->update([
            'status' => 'In Progress',
            'updated_by' => auth()->id(),
        ]);

        CommissioningAuditLogService::action(
            'snag_started',
            $snag,
            "Rectification started for snag {$snag->snag_no}.",
            $oldValues,
            $snag->getAttributes()
        );

        return back()->with(
            'success',
            'Snag rectification started.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SUBMIT RECTIFICATION
    |--------------------------------------------------------------------------
    */

    public function submitRectification(
        Project $project,
        Request $request,
        HandoverSnag $snag
    ): RedirectResponse {

        $this->validateSnagProject(
            $project,
            $snag
        );

        if ($snag->status !== 'In Progress') {

            return back()->with(
                'error',
                'Only in-progress snags can be submitted for verification.'
            );
        }

        $validated = $request->validate([
            'rectification_details' => [
                'required',
                'string',
            ],

            'attachment_path' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);

        $oldValues = $snag->getAttributes();

        $snag->update([
            'rectification_details' =>
                $validated['rectification_details'],

            'attachment_path' =>
                $validated['attachment_path'] ?? $snag->attachment_path,

            'status' =>
                'Rectification Submitted',

            'rectified_by' =>
                auth()->id(),

            'rectified_at' =>
                now(),

            'updated_by' =>
                auth()->id(),
        ]);

        CommissioningAuditLogService::action(
            'snag_rectification_submitted',
            $snag,
            "Rectification submitted for snag {$snag->snag_no}.",
            $oldValues,
            $snag->getAttributes()
        );

        return back()->with(
            'success',
            'Rectification submitted for verification.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VERIFY
    |--------------------------------------------------------------------------
    */

    public function verify(
        Project $project,
        HandoverSnag $snag
    ): RedirectResponse {

        $this->validateSnagProject(
            $project,
            $snag
        );

        if ($snag->status !== 'Rectification Submitted') {

            return back()->with(
                'error',
                'Only submitted rectifications can be sent for verification.'
            );
        }

        $oldValues = $snag->getAttributes();

        $snag->update([
            'status' =>
                'Under Verification',

            'updated_by' =>
                auth()->id(),
        ]);

        CommissioningAuditLogService::action(
            'snag_verification_started',
            $snag,
            "Verification started for snag {$snag->snag_no}.",
            $oldValues,
            $snag->getAttributes()
        );

        return back()->with(
            'success',
            'Snag moved to verification.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REJECT
    |--------------------------------------------------------------------------
    */

    public function reject(
        Project $project,
        Request $request,
        HandoverSnag $snag
    ): RedirectResponse {

        $this->validateSnagProject(
            $project,
            $snag
        );

        if ($snag->status !== 'Under Verification') {

            return back()->with(
                'error',
                'Only snags under verification can be rejected.'
            );
        }

        $validated = $request->validate([
            'rejection_reason' => [
                'required',
                'string',
            ],
        ]);

        $oldValues = $snag->getAttributes();

        $snag->update([
            'status' =>
                'Rejected',

            'rejection_reason' =>
                $validated['rejection_reason'],

            'remarks' =>
                trim(
                    ($snag->remarks
                        ? $snag->remarks . PHP_EOL . PHP_EOL
                        : '') .
                    'Verification Rejection: ' .
                    $validated['rejection_reason']
                ),

            'updated_by' =>
                auth()->id(),
        ]);

        CommissioningAuditLogService::action(
            'snag_rejected',
            $snag,
            "Snag {$snag->snag_no} rejected during verification.",
            $oldValues,
            $snag->getAttributes()
        );

        return back()->with(
            'success',
            'Snag rejected and returned for rectification.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CLOSE
    |--------------------------------------------------------------------------
    */

    public function close(
        Project $project,
        HandoverSnag $snag,
        HandoverReadinessService $readinessService
    ): RedirectResponse {

        $this->validateSnagProject(
            $project,
            $snag
        );

        if ($snag->status !== 'Under Verification') {

            return back()->with(
                'error',
                'Only snags under verification can be closed.'
            );
        }

        $oldValues = $snag->getAttributes();

        $snag->update([
            'status' =>
                'Closed',

            'verified_by' =>
                auth()->id(),

            'verified_at' =>
                now(),

            'updated_by' =>
                auth()->id(),
        ]);

        CommissioningAuditLogService::action(
            'snag_closed',
            $snag,
            "Handover snag {$snag->snag_no} verified and closed.",
            $oldValues,
            $snag->getAttributes()
        );

        /*
        |--------------------------------------------------------------------------
        | Recalculate Handover Readiness
        |--------------------------------------------------------------------------
        */

        $readinessService->sync($project);

        return back()->with(
            'success',
            'Snag verified and closed successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GET HANDOVER
    |--------------------------------------------------------------------------
    */

    protected function getHandover(
        Project $project
    ): HandoverProject {

        $handover = HandoverProject::where(
            'project_id',
            $project->id
        )
            ->latest('id')
            ->first();

        abort_if(
            !$handover,
            404,
            'Handover has not been started for this project.'
        );

        return $handover;
    }


    /*
    |--------------------------------------------------------------------------
    | PROJECT VALIDATION
    |--------------------------------------------------------------------------
    */

    protected function validateSnagProject(
        Project $project,
        HandoverSnag $snag
    ): void {

        abort_if(
            (int) $snag->project_id !== (int) $project->id,
            404
        );

        $handoverExists = HandoverProject::where(
            'id',
            $snag->handover_project_id
        )
            ->where(
                'project_id',
                $project->id
            )
            ->exists();

        abort_if(
            !$handoverExists,
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PROCUREMENT CONTRACT VALIDATION
    |--------------------------------------------------------------------------
    */

    protected function validateProcurementContract(
        Project $project,
        $contractId
    ): void {

        if (empty($contractId)) {
            return;
        }

        $contractExists = ProcurementContract::query()
            ->where(
                'id',
                $contractId
            )
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
            ->exists();

        abort_unless(
            $contractExists,
            422,
            'Selected procurement contract does not belong to this project.'
        );
    }
}