<?php

namespace App\Http\Controllers\Admin\Handover;

use App\Http\Controllers\Controller;
use App\Models\HandoverAssetHandover;
use App\Models\HandoverAssetItem;
use App\Models\HandoverCertificate;
use App\Models\HandoverProject;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HandoverAssetHandoverController extends Controller
{
    /**
     * Asset Handover dashboard.
     */
    public function index(Project $project): View
    {
        $handover = $this->getHandover($project);

        $certificate = HandoverCertificate::where(
            'handover_project_id',
            $handover->id
        )
            ->with('approvedBy')
            ->first();

        $assetHandover = HandoverAssetHandover::where(
            'handover_project_id',
            $handover->id
        )
            ->with([
                'certificate',
                'items',
                'preparedBy',
                'submittedBy',
                'reviewedBy',
                'approvedBy',
            ])
            ->first();

        $certificateApproved =
            $certificate &&
            $certificate->status === 'Approved';

        $items = $assetHandover
            ? $assetHandover->items
            : collect();

        $totalItems = $items->count();

        $pendingItems = $items
            ->where('status', 'Pending')
            ->count();

        $verifiedItems = $items
            ->where('status', 'Verified')
            ->count();

        $acceptedItems = $items
            ->where('status', 'Accepted')
            ->count();

        $rejectedItems = $items
            ->where('status', 'Rejected')
            ->count();

        $itemsReady =
            $totalItems > 0 &&
            $acceptedItems === $totalItems;

        $assetHandoverApproved =
            $assetHandover &&
            $assetHandover->status === 'Approved';

        /*
         * Keep asset count synchronized.
         */
        if ($assetHandover) {
            $actualAssetCount = $totalItems;

            if ((int) $assetHandover->asset_count !== $actualAssetCount) {
                $assetHandover->update([
                    'asset_count' => $actualAssetCount,
                    'updated_by' => auth()->id(),
                ]);
            }
        }

        return view(
            'admin.handover.asset-handover.index',
            compact(
                'project',
                'handover',
                'certificate',
                'assetHandover',
                'certificateApproved',
                'items',
                'totalItems',
                'pendingItems',
                'verifiedItems',
                'acceptedItems',
                'rejectedItems',
                'itemsReady',
                'assetHandoverApproved'
            )
        );
    }

    /**
     * Create Asset Handover.
     */
    public function store(
        Project $project,
        Request $request
    ): RedirectResponse {
        $handover = $this->getHandover($project);

        $certificate = HandoverCertificate::where(
            'handover_project_id',
            $handover->id
        )->first();

        if (!$certificate) {
            return back()->with(
                'error',
                'Handover Certificate has not been created yet.'
            );
        }

        if ($certificate->status !== 'Approved') {
            return back()->with(
                'error',
                'Asset Handover can only be created after the Handover Certificate is Approved.'
            );
        }

        $existing = HandoverAssetHandover::where(
            'handover_project_id',
            $handover->id
        )->first();

        if ($existing) {
            return back()->with(
                'error',
                'Asset Handover already exists for this handover.'
            );
        }

        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'handover_date' => [
                'nullable',
                'date',
            ],
            'handover_statement' => [
                'nullable',
                'string',
            ],
            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        $handoverNo = $this->generateHandoverNo($project);

        $assetHandover = HandoverAssetHandover::create([
            'project_id' => $project->id,

            'handover_project_id' =>
                $handover->id,

            'certificate_id' =>
                $certificate->id,

            'handover_no' =>
                $handoverNo,

            'title' =>
                $validated['title'],

            'description' =>
                $validated['description'] ?? null,

            'handover_date' =>
                $validated['handover_date'] ?? null,

            'status' => 'Draft',

            'asset_count' => 0,

            'handover_statement' =>
                $validated['handover_statement'] ?? null,

            'remarks' =>
                $validated['remarks'] ?? null,

            'created_by' =>
                auth()->id(),

            'updated_by' =>
                auth()->id(),
        ]);

        return redirect()
            ->route(
                'admin.projects.handover.asset-handover.index',
                $project
            )
            ->with(
                'success',
                "Asset Handover {$assetHandover->handover_no} created successfully."
            );
    }

    /**
     * Update Asset Handover header.
     */
    public function update(
        Project $project,
        Request $request,
        HandoverAssetHandover $assetHandover
    ): RedirectResponse {
        $this->validateAssetHandoverProject(
            $project,
            $assetHandover
        );

        if (!in_array($assetHandover->status, [
            'Draft',
            'Prepared',
            'Rejected',
        ])) {
            return back()->with(
                'error',
                'Asset Handover can only be edited in Draft, Prepared or Rejected status.'
            );
        }

        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'handover_date' => [
                'nullable',
                'date',
            ],
            'handover_statement' => [
                'nullable',
                'string',
            ],
            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        $data = [
            'title' =>
                $validated['title'],

            'description' =>
                $validated['description'] ?? null,

            'handover_date' =>
                $validated['handover_date'] ?? null,

            'handover_statement' =>
                $validated['handover_statement'] ?? null,

            'remarks' =>
                $validated['remarks'] ?? null,

            'asset_count' =>
                $assetHandover->items()->count(),

            'updated_by' =>
                auth()->id(),
        ];

        /*
         * Rejected → Prepared.
         */
        if ($assetHandover->status === 'Rejected') {
            $data['status'] = 'Prepared';
            $data['rejection_reason'] = null;
        }

        $assetHandover->update($data);

        return back()->with(
            'success',
            'Asset Handover details updated successfully.'
        );
    }

    /**
     * Add Asset Handover Item.
     */
    public function addItem(
        Project $project,
        Request $request,
        HandoverAssetHandover $assetHandover
    ): RedirectResponse {
        $this->validateAssetHandoverProject(
            $project,
            $assetHandover
        );

        if (!in_array($assetHandover->status, [
            'Draft',
            'Prepared',
            'Rejected',
        ])) {
            return back()->with(
                'error',
                'Assets can only be added while Asset Handover is editable.'
            );
        }

        $validated = $this->validateItem(
            $request,
            $project
        );

        $item = HandoverAssetItem::create([
            'asset_handover_id' =>
                $assetHandover->id,

            'project_id' =>
                $project->id,

            'handover_project_id' =>
                $assetHandover->handover_project_id,

            'asset_id' =>
                $validated['asset_id'] ?? null,

            'asset_code' =>
                $validated['asset_code'] ?? null,

            'asset_name' =>
                $validated['asset_name'],

            'asset_category' =>
                $validated['asset_category'] ?? null,

            'asset_type' =>
                $validated['asset_type'] ?? null,

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

            'quantity' =>
                $validated['quantity'] ?? 1,

            'condition_status' =>
                $validated['condition_status'],

            'commissioning_status' =>
                $validated['commissioning_status'],

            'warranty_available' =>
                $request->boolean('warranty_available'),

            'warranty_expiry_date' =>
                $validated['warranty_expiry_date'] ?? null,

            'documents_available' =>
                $request->boolean('documents_available'),

            'status' => 'Pending',

            'remarks' =>
                $validated['remarks'] ?? null,

            'created_by' =>
                auth()->id(),

            'updated_by' =>
                auth()->id(),
        ]);

        $this->syncAssetCount($assetHandover);

        return back()->with(
            'success',
            "Asset item {$item->asset_name} added successfully."
        );
    }

    /**
     * Update Asset Handover Item.
     */
    public function updateItem(
        Project $project,
        Request $request,
        HandoverAssetHandover $assetHandover,
        HandoverAssetItem $item
    ): RedirectResponse {
        $this->validateAssetHandoverProject(
            $project,
            $assetHandover
        );

        $this->validateItemHandover(
            $assetHandover,
            $item
        );

        if (!in_array($assetHandover->status, [
            'Draft',
            'Prepared',
            'Rejected',
        ])) {
            return back()->with(
                'error',
                'Asset items cannot be edited after submission.'
            );
        }

        if ($item->status === 'Accepted') {
            return back()->with(
                'error',
                'Accepted asset items cannot be edited.'
            );
        }

        $validated = $this->validateItem(
            $request,
            $project
        );

        $item->update([
            'asset_id' =>
                $validated['asset_id'] ?? null,

            'asset_code' =>
                $validated['asset_code'] ?? null,

            'asset_name' =>
                $validated['asset_name'],

            'asset_category' =>
                $validated['asset_category'] ?? null,

            'asset_type' =>
                $validated['asset_type'] ?? null,

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

            'quantity' =>
                $validated['quantity'] ?? 1,

            'condition_status' =>
                $validated['condition_status'],

            'commissioning_status' =>
                $validated['commissioning_status'],

            'warranty_available' =>
                $request->boolean('warranty_available'),

            'warranty_expiry_date' =>
                $validated['warranty_expiry_date'] ?? null,

            'documents_available' =>
                $request->boolean('documents_available'),

            'remarks' =>
                $validated['remarks'] ?? null,

            /*
             * Editing a rejected item starts verification again.
             */
            'status' => 'Pending',

            'verified_by' => null,
            'verified_at' => null,

            'updated_by' =>
                auth()->id(),
        ]);

        return back()->with(
            'success',
            'Asset item updated successfully and returned to Pending verification.'
        );
    }

    /**
     * Verify asset item.
     */
    public function verifyItem(
        Project $project,
        HandoverAssetHandover $assetHandover,
        HandoverAssetItem $item
    ): RedirectResponse {
        $this->validateAssetHandoverProject(
            $project,
            $assetHandover
        );

        $this->validateItemHandover(
            $assetHandover,
            $item
        );

        if (!in_array($item->status, [
            'Pending',
            'Rejected',
        ])) {
            return back()->with(
                'error',
                'Only Pending or Rejected asset items can be verified.'
            );
        }

        $validationError = $this->itemReadinessError($item);

        if ($validationError) {
            return back()->with(
                'error',
                $validationError
            );
        }

        $item->update([
            'status' => 'Verified',

            'verified_by' =>
                auth()->id(),

            'verified_at' =>
                now(),

            'updated_by' =>
                auth()->id(),
        ]);

        return back()->with(
            'success',
            'Asset item verified successfully.'
        );
    }

    /**
     * Accept verified asset item.
     */
    public function acceptItem(
        Project $project,
        HandoverAssetHandover $assetHandover,
        HandoverAssetItem $item
    ): RedirectResponse {
        $this->validateAssetHandoverProject(
            $project,
            $assetHandover
        );

        $this->validateItemHandover(
            $assetHandover,
            $item
        );

        if ($item->status !== 'Verified') {
            return back()->with(
                'error',
                'Only Verified asset items can be accepted.'
            );
        }

        $item->update([
            'status' => 'Accepted',
            'updated_by' => auth()->id(),
        ]);

        return back()->with(
            'success',
            'Asset item accepted successfully.'
        );
    }

    /**
     * Reject asset item.
     */
    public function rejectItem(
        Project $project,
        Request $request,
        HandoverAssetHandover $assetHandover,
        HandoverAssetItem $item
    ): RedirectResponse {
        $this->validateAssetHandoverProject(
            $project,
            $assetHandover
        );

        $this->validateItemHandover(
            $assetHandover,
            $item
        );

        if ($item->status !== 'Verified') {
            return back()->with(
                'error',
                'Only Verified asset items can be rejected.'
            );
        }

        $validated = $request->validate([
            'rejection_reason' => [
                'required',
                'string',
                'max:5000',
            ],
        ]);

        $item->update([
            'status' => 'Rejected',

            'remarks' => trim(
                ($item->remarks ? $item->remarks . "\n\n" : '') .
                'Rejection Reason: ' .
                $validated['rejection_reason']
            ),

            'updated_by' =>
                auth()->id(),
        ]);

        return back()->with(
            'success',
            'Asset item rejected.'
        );
    }

    /**
     * Remove asset item.
     */
    public function removeItem(
        Project $project,
        HandoverAssetHandover $assetHandover,
        HandoverAssetItem $item
    ): RedirectResponse {
        $this->validateAssetHandoverProject(
            $project,
            $assetHandover
        );

        $this->validateItemHandover(
            $assetHandover,
            $item
        );

        if (!in_array($assetHandover->status, [
            'Draft',
            'Prepared',
            'Rejected',
        ])) {
            return back()->with(
                'error',
                'Asset items cannot be removed after submission.'
            );
        }

        if (in_array($item->status, [
            'Verified',
            'Accepted',
        ])) {
            return back()->with(
                'error',
                'Verified or Accepted asset items cannot be removed.'
            );
        }

        $item->delete();

        $this->syncAssetCount($assetHandover);

        return back()->with(
            'success',
            'Asset item removed successfully.'
        );
    }

    /**
     * Submit Asset Handover.
     */
    public function submit(
        Project $project,
        HandoverAssetHandover $assetHandover
    ): RedirectResponse {
        $this->validateAssetHandoverProject(
            $project,
            $assetHandover
        );

        if (!in_array($assetHandover->status, [
            'Draft',
            'Prepared',
            'Rejected',
        ])) {
            return back()->with(
                'error',
                'Only Draft, Prepared or Rejected Asset Handovers can be submitted.'
            );
        }

        $certificate = HandoverCertificate::where(
            'handover_project_id',
            $assetHandover->handover_project_id
        )->first();

        if (!$certificate || $certificate->status !== 'Approved') {
            return back()->with(
                'error',
                'Approved Handover Certificate is required.'
            );
        }

        $items = $assetHandover->items()->get();

        if ($items->isEmpty()) {
            return back()->with(
                'error',
                'At least one asset item is required before submission.'
            );
        }

        $notAccepted = $items->where(
            'status',
            '!=',
            'Accepted'
        )->count();

        if ($notAccepted > 0) {
            return back()->with(
                'error',
                "{$notAccepted} asset item(s) are not yet accepted."
            );
        }

        $assetHandover->update([
            'status' => 'Submitted',

            'asset_count' =>
                $items->count(),

            'submitted_by' =>
                auth()->id(),

            'submitted_at' =>
                now(),

            'updated_by' =>
                auth()->id(),
        ]);

        return back()->with(
            'success',
            'Asset Handover submitted successfully.'
        );
    }

    /**
     * Start review.
     */
    public function review(
        Project $project,
        HandoverAssetHandover $assetHandover
    ): RedirectResponse {
        $this->validateAssetHandoverProject(
            $project,
            $assetHandover
        );

        if ($assetHandover->status !== 'Submitted') {
            return back()->with(
                'error',
                'Only Submitted Asset Handovers can be moved to review.'
            );
        }

        $assetHandover->update([
            'status' => 'Under Review',

            'reviewed_by' =>
                auth()->id(),

            'reviewed_at' =>
                now(),

            'updated_by' =>
                auth()->id(),
        ]);

        return back()->with(
            'success',
            'Asset Handover moved to Under Review.'
        );
    }

    /**
     * Approve Asset Handover.
     */
    public function approve(
        Project $project,
        HandoverAssetHandover $assetHandover
    ): RedirectResponse {
        $this->validateAssetHandoverProject(
            $project,
            $assetHandover
        );

        if ($assetHandover->status !== 'Under Review') {
            return back()->with(
                'error',
                'Only Asset Handovers Under Review can be approved.'
            );
        }

        $certificate = HandoverCertificate::where(
            'handover_project_id',
            $assetHandover->handover_project_id
        )->first();

        if (!$certificate || $certificate->status !== 'Approved') {
            return back()->with(
                'error',
                'Approved Handover Certificate is required.'
            );
        }

        $items = $assetHandover->items()->get();

        if ($items->isEmpty()) {
            return back()->with(
                'error',
                'No asset items are available for approval.'
            );
        }

        $notAccepted = $items
            ->where('status', '!=', 'Accepted')
            ->count();

        if ($notAccepted > 0) {
            return back()->with(
                'error',
                "{$notAccepted} asset item(s) must be accepted before Asset Handover approval."
            );
        }

        DB::transaction(function () use (
            $assetHandover,
            $items
        ) {
            /*
             * IMPORTANT:
             *
             * Do not create duplicate Asset records here until
             * the existing Asset module schema/model has been
             * confirmed.
             *
             * For now Asset Handover is formally approved and
             * asset_id values are preserved for existing assets.
             */
            $assetHandover->update([
                'status' => 'Approved',

                'asset_count' =>
                    $items->count(),

                'approved_by' =>
                    auth()->id(),

                'approved_at' =>
                    now(),

                'updated_by' =>
                    auth()->id(),
            ]);
        });

        return back()->with(
            'success',
            'Asset Handover approved successfully.'
        );
    }

    /**
     * Reject Asset Handover.
     */
    public function reject(
        Project $project,
        Request $request,
        HandoverAssetHandover $assetHandover
    ): RedirectResponse {
        $this->validateAssetHandoverProject(
            $project,
            $assetHandover
        );

        if ($assetHandover->status !== 'Under Review') {
            return back()->with(
                'error',
                'Only Asset Handovers Under Review can be rejected.'
            );
        }

        $validated = $request->validate([
            'rejection_reason' => [
                'required',
                'string',
                'max:5000',
            ],
        ]);

        $assetHandover->update([
            'status' => 'Rejected',

            'rejection_reason' =>
                $validated['rejection_reason'],

            'updated_by' =>
                auth()->id(),
        ]);

        return back()->with(
            'success',
            'Asset Handover rejected. It can be corrected and resubmitted.'
        );
    }

    /**
     * Validate asset item.
     */
    protected function validateItem(
        Request $request,
        Project $project
    ): array {
        return $request->validate([
            'asset_id' => [
                'nullable',
                'integer',
            ],

            'asset_code' => [
                'nullable',
                'string',
                'max:100',
            ],

            'asset_name' => [
                'required',
                'string',
                'max:255',
            ],

            'asset_category' => [
                'nullable',
                'string',
                'max:150',
            ],

            'asset_type' => [
                'nullable',
                'string',
                'max:150',
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

            'quantity' => [
                'required',
                'numeric',
                'min:0.001',
            ],

            'condition_status' => [
                'required',
                'in:New,Good,Fair,Damaged,Requires Attention',
            ],

            'commissioning_status' => [
                'required',
                'in:Not Applicable,Pending,Completed',
            ],

            'warranty_expiry_date' => [
                'nullable',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);
    }

    /**
     * Basic item readiness validation.
     */
    protected function itemReadinessError(
        HandoverAssetItem $item
    ): ?string {
        if (!$item->asset_name) {
            return 'Asset name is required.';
        }

        if ((float) $item->quantity <= 0) {
            return 'Asset quantity must be greater than zero.';
        }

        if (
            $item->commissioning_status === 'Pending'
        ) {
            return 'Asset commissioning is still pending.';
        }

        if (
            $item->warranty_available &&
            !$item->warranty_expiry_date
        ) {
            return 'Warranty expiry date is required when warranty is available.';
        }

        if (
            $item->condition_status === 'Damaged'
        ) {
            return 'Damaged assets cannot be verified for handover.';
        }

        return null;
    }

    /**
     * Validate Asset Handover belongs to project.
     */
    protected function validateAssetHandoverProject(
        Project $project,
        HandoverAssetHandover $assetHandover
    ): void {
        if (
            (int) $assetHandover->project_id !==
            (int) $project->id
        ) {
            abort(404);
        }
    }

    /**
     * Validate item belongs to Asset Handover.
     */
    protected function validateItemHandover(
        HandoverAssetHandover $assetHandover,
        HandoverAssetItem $item
    ): void {
        if (
            (int) $item->asset_handover_id !==
            (int) $assetHandover->id
        ) {
            abort(404);
        }

        if (
            (int) $item->project_id !==
            (int) $assetHandover->project_id
        ) {
            abort(404);
        }
    }

    /**
     * Sync item count.
     */
    protected function syncAssetCount(
        HandoverAssetHandover $assetHandover
    ): void {
        $assetHandover->update([
            'asset_count' =>
                $assetHandover->items()->count(),

            'updated_by' =>
                auth()->id(),
        ]);
    }

    /**
     * Get current handover.
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

        if (!$handover) {
            abort(
                404,
                'Handover process has not been started for this project.'
            );
        }

        return $handover;
    }

    /**
     * Generate Asset Handover number.
     */
    protected function generateHandoverNo(
        Project $project
    ): string {
        $count = HandoverAssetHandover::where(
            'project_id',
            $project->id
        )->count() + 1;

        do {
            $handoverNo =
                'AH-' .
                str_pad(
                    (string) $project->id,
                    4,
                    '0',
                    STR_PAD_LEFT
                ) .
                '-' .
                str_pad(
                    (string) $count,
                    4,
                    '0',
                    STR_PAD_LEFT
                );

            $exists = HandoverAssetHandover::where(
                'project_id',
                $project->id
            )
                ->where(
                    'handover_no',
                    $handoverNo
                )
                ->exists();

            if ($exists) {
                $count++;
            }
        } while ($exists);

        return $handoverNo;
    }
}