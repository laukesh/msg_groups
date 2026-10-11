<?php

namespace App\Http\Controllers\Admin\Handover;

use App\Http\Controllers\Controller;
use App\Models\HandoverDefect;
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
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HandoverDefectController extends Controller
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

        $handover = $this->getHandover($project);

        /*
        |--------------------------------------------------------------------------
        | Defect Query
        |--------------------------------------------------------------------------
        */

        $query = HandoverDefect::query()
            ->where('project_id', $project->id)
            ->where(
                'handover_project_id',
                $handover->id
            )
            ->with([
                'snag',
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

                $q->where(
                    'defect_no',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'title',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'description',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'location',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'building',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'floor',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'zone',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'unit',
                    'like',
                    "%{$search}%"
                );

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Priority Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('priority')) {

            $query->where(
                'priority',
                $request->priority
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Discipline Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('discipline')) {

            $query->where(
                'discipline',
                $request->discipline
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Category Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('category')) {

            $query->where(
                'category',
                $request->category
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Warranty Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('warranty_related')) {

            $query->where(
                'warranty_related',
                $request->warranty_related
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Overdue Filter
        |--------------------------------------------------------------------------
        */

        if ($request->status === 'Overdue') {

            $query->whereNotNull('due_date')
                ->whereDate(
                    'due_date',
                    '<',
                    now()->toDateString()
                )
                ->where(
                    'status',
                    '!=',
                    'Closed'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $defects = $query
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | KPI DATA
        |--------------------------------------------------------------------------
        */

        $allDefects = HandoverDefect::query()
            ->where(
                'project_id',
                $project->id
            )
            ->where(
                'handover_project_id',
                $handover->id
            )
            ->get();

        $totalDefects = $allDefects->count();

        $openDefects = $allDefects
            ->whereIn('status', [
                'Open',
                'Assigned',
                'In Progress',
                'Rectification Submitted',
                'Under Verification',
                'Rejected',
            ])
            ->count();

        $closedDefects = $allDefects
            ->where(
                'status',
                'Closed'
            )
            ->count();

        $criticalDefects = $allDefects
            ->where(
                'priority',
                'Critical'
            )
            ->where(
                'status',
                '!=',
                'Closed'
            )
            ->count();

        $verificationDefects = $allDefects
            ->where(
                'status',
                'Under Verification'
            )
            ->count();

        $rejectedDefects = $allDefects
            ->where(
                'status',
                'Rejected'
            )
            ->count();

        $warrantyDefects = $allDefects
            ->where(
                'warranty_related',
                true
            )
            ->where(
                'status',
                '!=',
                'Closed'
            )
            ->count();

        $overdueDefects = $allDefects
            ->filter(function ($defect) {

                return $defect->due_date
                    && $defect->due_date->isPast()
                    && $defect->status !== 'Closed';

            })
            ->count();

        $closurePercentage = $totalDefects > 0
            ? round(
                ($closedDefects / $totalDefects) * 100,
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
        | Project
        |   ↓
        | ProcurementPlan
        |   ↓
        | ProcurementPackage
        |   ↓
        | ProcurementTender
        |   ↓
        | ProcurementContract
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
        | Existing Snags
        |--------------------------------------------------------------------------
        */

        $snags = HandoverSnag::query()
            ->where(
                'project_id',
                $project->id
            )
            ->where(
                'handover_project_id',
                $handover->id
            )
            ->orderByDesc('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Filter Options
        |--------------------------------------------------------------------------
        */

        $categories = HandoverDefect::query()
            ->where(
                'project_id',
                $project->id
            )
            ->where(
                'handover_project_id',
                $handover->id
            )
            ->whereNotNull('category')
            ->where(
                'category',
                '!=',
                ''
            )
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $disciplines = HandoverDefect::query()
            ->where(
                'project_id',
                $project->id
            )
            ->where(
                'handover_project_id',
                $handover->id
            )
            ->whereNotNull('discipline')
            ->where(
                'discipline',
                '!=',
                ''
            )
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
            'admin.handover.defects.index',
            compact(
                'project',
                'handover',
                'defects',

                'totalDefects',
                'openDefects',
                'closedDefects',
                'criticalDefects',
                'verificationDefects',
                'rejectedDefects',
                'warrantyDefects',
                'overdueDefects',
                'closurePercentage',

                'users',
                'contracts',
                'snags',

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

            'snag_id' => [
                'nullable',
                'exists:handover_snags,id',
            ],

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

            'warranty_related' => [
                'nullable',
                'boolean',
            ],

            'reported_date' => [
                'nullable',
                'date',
            ],

            'due_date' => [
                'nullable',
                'date',
            ],

            'assigned_to' => [
                'nullable',
                'exists:users,id',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

            'attachment' => [
                'nullable',
                'file',
                'max:51200',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validate Snag
        |--------------------------------------------------------------------------
        */

        $this->validateSnag(
            $project,
            $handover,
            $validated['snag_id'] ?? null
        );

        /*
        |--------------------------------------------------------------------------
        | Validate Procurement Contract
        |--------------------------------------------------------------------------
        */

        $this->validateProcurementContract(
            $project,
            $validated['procurement_contract_id'] ?? null
        );

        DB::beginTransaction();

        try {

            $nextNumber = HandoverDefect::where(
                'project_id',
                $project->id
            )->count() + 1;

            $defectNo =
                'DEF-' .
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
            |--------------------------------------------------------------------------
            | Ensure Unique Number
            |--------------------------------------------------------------------------
            */

            while (
                HandoverDefect::where(
                    'project_id',
                    $project->id
                )
                    ->where(
                        'defect_no',
                        $defectNo
                    )
                    ->exists()
            ) {

                $nextNumber++;

                $defectNo =
                    'DEF-' .
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

            /*
            |--------------------------------------------------------------------------
            | Attachment
            |--------------------------------------------------------------------------
            */

            $attachmentPath = null;

            if ($request->hasFile('attachment')) {

                $attachmentPath =
                    $request->file('attachment')->store(
                        'handover-defects/' . $project->id,
                        'public'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Create Defect
            |--------------------------------------------------------------------------
            */

            $defect = HandoverDefect::create([

                'project_id' =>
                    $project->id,

                'handover_project_id' =>
                    $handover->id,

                'defect_no' =>
                    $defectNo,

                'snag_id' =>
                    $validated['snag_id'] ?? null,

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

                'warranty_related' =>
                    $request->boolean('warranty_related'),

                'reported_date' =>
                    $validated['reported_date']
                    ?? now()->toDateString(),

                'due_date' =>
                    $validated['due_date'] ?? null,

                'raised_by' =>
                    auth()->id(),

                'status' =>
                    'Open',

                'remarks' =>
                    $validated['remarks'] ?? null,

                'attachment_path' =>
                    $attachmentPath,

                'created_by' =>
                    auth()->id(),

                'updated_by' =>
                    auth()->id(),
            ]);

            CommissioningAuditLogService::action(
                'handover_defect_created',
                $defect,
                "Handover defect {$defect->defect_no} created."
            );

            DB::commit();

            return back()->with(
                'success',
                'Defect created successfully.'
            );

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to create defect.'
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
        HandoverDefect $defect
    ): RedirectResponse {

        $this->validateDefectProject(
            $project,
            $defect
        );

        if (!in_array($defect->status, [
            'Open',
            'Assigned',
            'Rejected',
        ])) {

            return back()->with(
                'error',
                'This defect cannot be edited in its current status.'
            );
        }

        $validated = $request->validate([

            'snag_id' => [
                'nullable',
                'exists:handover_snags,id',
            ],

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

            'warranty_related' => [
                'nullable',
                'boolean',
            ],

            'reported_date' => [
                'nullable',
                'date',
            ],

            'due_date' => [
                'nullable',
                'date',
            ],

            'assigned_to' => [
                'nullable',
                'exists:users,id',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

            'attachment' => [
                'nullable',
                'file',
                'max:51200',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png',
            ],
        ]);

        $this->validateSnag(
            $project,
            $defect->handover,
            $validated['snag_id'] ?? null
        );

        $this->validateProcurementContract(
            $project,
            $validated['procurement_contract_id'] ?? null
        );

        $oldValues = $defect->getAttributes();

        $data = [

            'snag_id' =>
                $validated['snag_id'] ?? null,

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

            'warranty_related' =>
                $request->boolean('warranty_related'),

            'reported_date' =>
                $validated['reported_date'] ?? null,

            'due_date' =>
                $validated['due_date'] ?? null,

            'assigned_to' =>
                $validated['assigned_to'] ?? null,

            'remarks' =>
                $validated['remarks'] ?? null,

            'updated_by' =>
                auth()->id(),
        ];

        /*
        |--------------------------------------------------------------------------
        | Replace Attachment
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('attachment')) {

            if (
                $defect->attachment_path &&
                Storage::disk('public')->exists(
                    $defect->attachment_path
                )
            ) {

                Storage::disk('public')->delete(
                    $defect->attachment_path
                );
            }

            $attachmentPath =
                $request->file('attachment')->store(
                    'handover-defects/' . $project->id,
                    'public'
                );

            $data['attachment_path'] =
                $attachmentPath;
        }

        /*
        |--------------------------------------------------------------------------
        | Rejected → In Progress
        |--------------------------------------------------------------------------
        */

        if ($defect->status === 'Rejected') {

            $data['status'] =
                'In Progress';

            $data['rejection_reason'] =
                null;
        }

        $defect->update($data);

        CommissioningAuditLogService::action(
            'handover_defect_updated',
            $defect,
            "Handover defect {$defect->defect_no} updated.",
            $oldValues,
            $defect->getAttributes()
        );

        return back()->with(
            'success',
            'Defect updated successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        HandoverDefect $defect
    ): RedirectResponse {

        $this->validateDefectProject(
            $project,
            $defect
        );

        if (!in_array($defect->status, [
            'Open',
            'Assigned',
            'Rejected',
        ])) {

            return back()->with(
                'error',
                'Only Open, Assigned or Rejected defects can be deleted.'
            );
        }

        $oldValues = $defect->getAttributes();

        if (
            $defect->attachment_path &&
            Storage::disk('public')->exists(
                $defect->attachment_path
            )
        ) {

            Storage::disk('public')->delete(
                $defect->attachment_path
            );
        }

        $defect->delete();

        CommissioningAuditLogService::action(
            'handover_defect_deleted',
            $defect,
            "Handover defect {$defect->defect_no} deleted.",
            $oldValues
        );

        return back()->with(
            'success',
            'Defect deleted successfully.'
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
        HandoverDefect $defect
    ): RedirectResponse {

        $this->validateDefectProject(
            $project,
            $defect
        );

        if (!in_array($defect->status, [
            'Open',
            'Rejected',
        ])) {

            return back()->with(
                'error',
                'This defect cannot be assigned in its current status.'
            );
        }

        $validated = $request->validate([
            'assigned_to' => [
                'required',
                'exists:users,id',
            ],
        ]);

        $oldValues = $defect->getAttributes();

        $defect->update([
            'assigned_to' =>
                $validated['assigned_to'],

            'status' =>
                'Assigned',

            'updated_by' =>
                auth()->id(),
        ]);

        CommissioningAuditLogService::action(
            'handover_defect_assigned',
            $defect,
            "Handover defect {$defect->defect_no} assigned.",
            $oldValues,
            $defect->getAttributes()
        );

        return back()->with(
            'success',
            'Defect assigned successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | START RECTIFICATION
    |--------------------------------------------------------------------------
    */

    public function start(
        Project $project,
        HandoverDefect $defect
    ): RedirectResponse {

        $this->validateDefectProject(
            $project,
            $defect
        );

        if ($defect->status !== 'Assigned') {

            return back()->with(
                'error',
                'Only assigned defects can be started.'
            );
        }

        $oldValues = $defect->getAttributes();

        $defect->update([
            'status' =>
                'In Progress',

            'updated_by' =>
                auth()->id(),
        ]);

        CommissioningAuditLogService::action(
            'handover_defect_started',
            $defect,
            "Rectification started for defect {$defect->defect_no}.",
            $oldValues,
            $defect->getAttributes()
        );

        return back()->with(
            'success',
            'Defect rectification started.'
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
        HandoverDefect $defect
    ): RedirectResponse {

        $this->validateDefectProject(
            $project,
            $defect
        );

        if ($defect->status !== 'In Progress') {

            return back()->with(
                'error',
                'Only in-progress defects can be submitted for verification.'
            );
        }

        $validated = $request->validate([

            'rectification_details' => [
                'required',
                'string',
            ],

            'attachment' => [
                'nullable',
                'file',
                'max:51200',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png',
            ],
        ]);

        $oldValues = $defect->getAttributes();

        $data = [

            'rectification_details' =>
                $validated['rectification_details'],

            'status' =>
                'Rectification Submitted',

            'rectified_by' =>
                auth()->id(),

            'rectified_at' =>
                now(),

            'updated_by' =>
                auth()->id(),
        ];

        /*
        |--------------------------------------------------------------------------
        | Rectification Attachment
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('attachment')) {

            if (
                $defect->attachment_path &&
                Storage::disk('public')->exists(
                    $defect->attachment_path
                )
            ) {

                Storage::disk('public')->delete(
                    $defect->attachment_path
                );
            }

            $data['attachment_path'] =
                $request->file('attachment')->store(
                    'handover-defects/' . $project->id,
                    'public'
                );
        }

        $defect->update($data);

        CommissioningAuditLogService::action(
            'handover_defect_rectification_submitted',
            $defect,
            "Rectification submitted for defect {$defect->defect_no}.",
            $oldValues,
            $defect->getAttributes()
        );

        return back()->with(
            'success',
            'Rectification submitted for verification.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | START VERIFICATION
    |--------------------------------------------------------------------------
    */

    public function verify(
        Project $project,
        HandoverDefect $defect
    ): RedirectResponse {

        $this->validateDefectProject(
            $project,
            $defect
        );

        if ($defect->status !== 'Rectification Submitted') {

            return back()->with(
                'error',
                'Only submitted rectifications can be verified.'
            );
        }

        $oldValues = $defect->getAttributes();

        $defect->update([
            'status' =>
                'Under Verification',

            'updated_by' =>
                auth()->id(),
        ]);

        CommissioningAuditLogService::action(
            'handover_defect_verification_started',
            $defect,
            "Verification started for defect {$defect->defect_no}.",
            $oldValues,
            $defect->getAttributes()
        );

        return back()->with(
            'success',
            'Defect moved to verification.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REJECT VERIFICATION
    |--------------------------------------------------------------------------
    */

    public function reject(
        Project $project,
        Request $request,
        HandoverDefect $defect
    ): RedirectResponse {

        $this->validateDefectProject(
            $project,
            $defect
        );

        if ($defect->status !== 'Under Verification') {

            return back()->with(
                'error',
                'Only defects under verification can be rejected.'
            );
        }

        $validated = $request->validate([
            'rejection_reason' => [
                'required',
                'string',
            ],
        ]);

        $oldValues = $defect->getAttributes();

        $defect->update([

            'status' =>
                'Rejected',

            'rejection_reason' =>
                $validated['rejection_reason'],

            'remarks' =>
                trim(
                    ($defect->remarks
                        ? $defect->remarks .
                          PHP_EOL . PHP_EOL
                        : '') .
                    'Verification Rejection: ' .
                    $validated['rejection_reason']
                ),

            'updated_by' =>
                auth()->id(),
        ]);

        CommissioningAuditLogService::action(
            'handover_defect_rejected',
            $defect,
            "Defect {$defect->defect_no} rejected during verification.",
            $oldValues,
            $defect->getAttributes()
        );

        return back()->with(
            'success',
            'Defect rejected and returned for rectification.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CLOSE / VERIFY
    |--------------------------------------------------------------------------
    */

    public function close(
        Project $project,
        HandoverDefect $defect,
        HandoverReadinessService $readinessService
    ): RedirectResponse {

        $this->validateDefectProject(
            $project,
            $defect
        );

        if ($defect->status !== 'Under Verification') {

            return back()->with(
                'error',
                'Only defects under verification can be closed.'
            );
        }

        $oldValues = $defect->getAttributes();

        $defect->update([

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
            'handover_defect_closed',
            $defect,
            "Defect {$defect->defect_no} verified and closed.",
            $oldValues,
            $defect->getAttributes()
        );

        /*
        |--------------------------------------------------------------------------
        | Recalculate Handover Readiness
        |--------------------------------------------------------------------------
        */

        $readinessService->sync($project);

        return back()->with(
            'success',
            'Defect verified and closed successfully.'
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

        $handover = HandoverProject::query()
            ->where(
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
    | VALIDATE DEFECT PROJECT
    |--------------------------------------------------------------------------
    */

    protected function validateDefectProject(
        Project $project,
        HandoverDefect $defect
    ): void {

        abort_if(
            (int) $defect->project_id !==
            (int) $project->id,
            404
        );

        abort_if(
            !HandoverProject::query()
                ->where(
                    'id',
                    $defect->handover_project_id
                )
                ->where(
                    'project_id',
                    $project->id
                )
                ->exists(),
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE SNAG
    |--------------------------------------------------------------------------
    */

    protected function validateSnag(
        Project $project,
        HandoverProject $handover,
        $snagId
    ): void {

        if (!$snagId) {
            return;
        }

        $exists = HandoverSnag::query()
            ->where(
                'id',
                $snagId
            )
            ->where(
                'project_id',
                $project->id
            )
            ->where(
                'handover_project_id',
                $handover->id
            )
            ->exists();

        abort_unless(
            $exists,
            422,
            'Selected snag does not belong to this project handover.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE PROCUREMENT CONTRACT
    |--------------------------------------------------------------------------
    */

    protected function validateProcurementContract(
        Project $project,
        $contractId
    ): void {

        if (!$contractId) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Project
        |    ↓
        | Procurement Plan
        |    ↓
        | Procurement Package
        |    ↓
        | Procurement Tender
        |    ↓
        | Procurement Contract
        |--------------------------------------------------------------------------
        */

        $exists = ProcurementContract::query()
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
            $exists,
            422,
            'Selected procurement contract does not belong to this project.'
        );
    }
}