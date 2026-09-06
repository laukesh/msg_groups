<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionClaim;
use App\Models\ConstructionDelay;
use App\Models\ConstructionDelayHistory;
use App\Models\ConstructionScheduleActivity;
use App\Models\ConstructionWorkOrder;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ConstructionDelayController extends Controller
{
    /**
     * Delay listing.
     */
    public function index(Project $project): View
    {
        $delays = ConstructionDelay::query()
            ->where('project_id', $project->id)
            ->with([
                'workOrder',
                'scheduleActivity',
                'claim',
            ])
            ->withCount('documents')
            ->latest('delay_date')
            ->latest('id')
            ->paginate(20);

        return view(
            'construction.delays.index',
            compact(
                'project',
                'delays'
            )
        );
    }

    /**
     * Create form.
     */
    public function create(Project $project): View
    {
        $workOrders = ConstructionWorkOrder::query()
            ->where('project_id', $project->id)
            ->orderBy('work_order_number')
            ->get();

        $scheduleActivities = ConstructionScheduleActivity::query()
            ->where('project_id', $project->id)
            ->orderBy('activity_code')
            ->get();

        $claims = ConstructionClaim::query()
            ->where('project_id', $project->id)
            ->orderByDesc('claim_date')
            ->get();

        return view(
            'construction.delays.create',
            compact(
                'project',
                'workOrders',
                'scheduleActivities',
                'claims'
            )
        );
    }

    /**
     * Store delay.
     */
    public function store(
        Request $request,
        Project $project
    ): RedirectResponse {
        $validated = $this->validateDelay(
            $request,
            $project
        );

       $test=  $this->validateRelationships(
            $validated,
            $project
        );
       //echo "<pre>";print_r($validated);die();

        DB::transaction(function () use (
            $validated,
            $project
        ) {
            $delay = ConstructionDelay::create([
                ...$validated,

                'project_id' => $project->id,

                'delay_number' =>
                    $this->generateDelayNumber(),

                'status' => 'Draft',

                'created_by' => Auth::id(),

                'updated_by' => Auth::id(),

                'reported_by' => Auth::id(),
            ]);

            $this->addHistory(
                $delay,
                'Created',
                null,
                'Draft',
                'Delay created.'
            );
        });

        return redirect()
            ->route(
                'admin.projects.construction.delays.index',
                $project
            )
            ->with(
                'success',
                'Delay created successfully.'
            );
    }

    /**
     * Show delay.
     */
    public function show(
        Project $project,
        ConstructionDelay $delay
    ): View {
        $this->checkProject(
            $project,
            $delay
        );

        $delay->load([
            'project',
            'workOrder',
            'scheduleActivity',
            'claim',
            'documents.uploadedBy',
            'history.performedBy',
            'reportedBy',
            'assessedBy',
            'approvedBy',
            'rejectedBy',
            'closedBy',
            'creator',
            'updater',
        ]);

        return view(
            'construction.delays.show',
            compact(
                'project',
                'delay'
            )
        );
    }

    /**
     * Edit form.
     */
    public function edit(
        Project $project,
        ConstructionDelay $delay
    ): View {
        $this->checkProject(
            $project,
            $delay
        );

        if (!in_array($delay->status, [
            'Draft',
            'Rejected',
        ])) {
            abort(
                422,
                'Only draft or rejected delays can be edited.'
            );
        }

        $workOrders = ConstructionWorkOrder::query()
            ->where('project_id', $project->id)
            ->orderBy('work_order_number')
            ->get();

        $scheduleActivities = ConstructionScheduleActivity::query()
            ->where('project_id', $project->id)
            ->orderBy('activity_code')
            ->get();

        $claims = ConstructionClaim::query()
            ->where('project_id', $project->id)
            ->orderByDesc('claim_date')
            ->get();

        return view(
            'construction.delays.edit',
            compact(
                'project',
                'delay',
                'workOrders',
                'scheduleActivities',
                'claims'
            )
        );
    }

    /**
     * Update delay.
     */
    public function update(
        Request $request,
        Project $project,
        ConstructionDelay $delay
    ): RedirectResponse {
        $this->checkProject(
            $project,
            $delay
        );

        if (!in_array($delay->status, [
            'Draft',
            'Rejected',
        ])) {
            abort(
                422,
                'Only draft or rejected delays can be edited.'
            );
        }

        $validated = $this->validateDelay(
            $request,
            $project
        );

        $this->validateRelationships(
            $validated,
            $project
        );

        $delay->update([
            ...$validated,

            'status' => 'Draft',

            'assessed_days' => null,
            'approved_days' => null,

            'excusable_days' => null,
            'compensable_days' => null,

            'assessed_cost_impact' => null,
            'approved_cost_impact' => null,

            'eot_assessed_days' => null,
            'eot_approved_days' => null,

            'assessment_remarks' => null,
            'approval_remarks' => null,
            'rejection_remarks' => null,

            'updated_by' => Auth::id(),
        ]);

        $this->addHistory(
            $delay,
            'Updated',
            'Rejected',
            'Draft',
            'Delay updated.'
        );

        return redirect()
            ->route(
                'admin.projects.construction.delays.show',
                [
                    'project' => $project,
                    'delay' => $delay,
                ]
            )
            ->with(
                'success',
                'Delay updated successfully.'
            );
    }

    /**
     * Submit delay.
     */
    public function submit(
        Project $project,
        ConstructionDelay $delay
    ): RedirectResponse {
        $this->checkProject(
            $project,
            $delay
        );

        if ($delay->status !== 'Draft') {
            return back()->withErrors([
                'error' =>
                    'Only draft delays can be submitted.',
            ]);
        }

        $oldStatus = $delay->status;

        $delay->update([
            'status' => 'Submitted',
            'updated_by' => Auth::id(),
        ]);

        $this->addHistory(
            $delay,
            'Submitted',
            $oldStatus,
            'Submitted',
            'Delay submitted for review.'
        );

        return back()->with(
            'success',
            'Delay submitted successfully.'
        );
    }

    /**
     * Review delay.
     */
    public function review(
        Project $project,
        ConstructionDelay $delay
    ): RedirectResponse {
        $this->checkProject(
            $project,
            $delay
        );

        if ($delay->status !== 'Submitted') {
            return back()->withErrors([
                'error' =>
                    'Only submitted delays can be reviewed.',
            ]);
        }

        $oldStatus = $delay->status;

        $delay->update([
            'status' => 'Under Review',
            'updated_by' => Auth::id(),
        ]);

        $this->addHistory(
            $delay,
            'Reviewed',
            $oldStatus,
            'Under Review',
            'Delay moved to review.'
        );

        return back()->with(
            'success',
            'Delay moved to review.'
        );
    }

    /**
     * Assessment form.
     */
    public function assessment(
        Project $project,
        ConstructionDelay $delay
    ): View {
        $this->checkProject(
            $project,
            $delay
        );

        if (!in_array($delay->status, [
            'Under Review',
            'Under Assessment',
        ])) {
            abort(
                422,
                'This delay cannot be assessed in its current status.'
            );
        }

        return view(
            'construction.delays.assessment',
            compact(
                'project',
                'delay'
            )
        );
    }

    /**
     * Assess delay.
     */
    public function assess(
        Request $request,
        Project $project,
        ConstructionDelay $delay
    ): RedirectResponse {
        $this->checkProject(
            $project,
            $delay
        );

        if (!in_array($delay->status, [
            'Under Review',
            'Under Assessment',
        ])) {
            return back()->withErrors([
                'error' =>
                    'This delay cannot be assessed in its current status.',
            ]);
        }

        $validated = $request->validate([
            'assessed_days' => [
                'required',
                'integer',
                'min:0',
            ],

            'excusable_days' => [
                'required',
                'integer',
                'min:0',
            ],

            'compensable_days' => [
                'required',
                'integer',
                'min:0',
            ],

            'assessed_cost_impact' => [
                'required',
                'numeric',
                'min:0',
            ],

            'eot_assessed_days' => [
                'required',
                'integer',
                'min:0',
            ],

            'assessment_remarks' => [
                'required',
                'string',
                'max:5000',
            ],

            'is_excusable' => [
                'nullable',
                'boolean',
            ],

            'is_compensable' => [
                'nullable',
                'boolean',
            ],
        ]);

        if (
            $validated['excusable_days'] >
            $validated['assessed_days']
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'excusable_days' =>
                        'Excusable days cannot exceed assessed days.',
                ]);
        }

        if (
            $validated['compensable_days'] >
            $validated['assessed_days']
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'compensable_days' =>
                        'Compensable days cannot exceed assessed days.',
                ]);
        }

        $oldStatus = $delay->status;

        $delay->update([
            'assessed_days' =>
                $validated['assessed_days'],

            'excusable_days' =>
                $validated['excusable_days'],

            'compensable_days' =>
                $validated['compensable_days'],

            'assessed_cost_impact' =>
                $validated['assessed_cost_impact'],

            'eot_assessed_days' =>
                $validated['eot_assessed_days'],

            'assessment_remarks' =>
                $validated['assessment_remarks'],

            'is_excusable' =>
                $request->boolean('is_excusable'),

            'is_compensable' =>
                $request->boolean('is_compensable'),

            'assessed_by' =>
                Auth::id(),

            'assessment_date' =>
                now(),

            'status' =>
                'Under Assessment',

            'updated_by' =>
                Auth::id(),
        ]);

        $this->addHistory(
            $delay,
            'Assessed',
            $oldStatus,
            'Under Assessment',
            $validated['assessment_remarks']
        );

        return redirect()
            ->route(
                'admin.projects.construction.delays.show',
                [
                    'project' => $project,
                    'delay' => $delay,
                ]
            )
            ->with(
                'success',
                'Delay assessment saved successfully.'
            );
    }

    /**
     * Approval form.
     */
    public function approval(
        Project $project,
        ConstructionDelay $delay
    ): View {
        $this->checkProject(
            $project,
            $delay
        );

        if ($delay->status !== 'Under Assessment') {
            abort(
                422,
                'Only delays under assessment can be approved.'
            );
        }

        return view(
            'construction.delays.approval',
            compact(
                'project',
                'delay'
            )
        );
    }

    /**
     * Approve delay.
     */
    public function approve(
        Request $request,
        Project $project,
        ConstructionDelay $delay
    ): RedirectResponse {
        $this->checkProject(
            $project,
            $delay
        );

        if ($delay->status !== 'Under Assessment') {
            return back()->withErrors([
                'error' =>
                    'Only delays under assessment can be approved.',
            ]);
        }

        $validated = $request->validate([
            'approved_days' => [
                'required',
                'integer',
                'min:0',
            ],

            'approved_cost_impact' => [
                'required',
                'numeric',
                'min:0',
            ],

            'eot_approved_days' => [
                'required',
                'integer',
                'min:0',
            ],

            'approval_remarks' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        if (
            $validated['approved_days'] >
            ($delay->assessed_days ?? 0)
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'approved_days' =>
                        'Approved days cannot exceed assessed days.',
                ]);
        }

        if (
            $validated['approved_cost_impact'] >
            ($delay->assessed_cost_impact ?? 0)
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'approved_cost_impact' =>
                        'Approved cost impact cannot exceed assessed cost impact.',
                ]);
        }

        if (
            $validated['eot_approved_days'] >
            ($delay->eot_assessed_days ?? 0)
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'eot_approved_days' =>
                        'Approved EOT cannot exceed assessed EOT.',
                ]);
        }

        $isPartial =
            $validated['approved_days'] <
                ($delay->assessed_days ?? 0)
            ||
            (float) $validated['approved_cost_impact'] <
                (float) ($delay->assessed_cost_impact ?? 0)
            ||
            $validated['eot_approved_days'] <
                ($delay->eot_assessed_days ?? 0);

        $oldStatus = $delay->status;

        $newStatus = $isPartial
            ? 'Partially Approved'
            : 'Approved';

        $delay->update([
            'approved_days' =>
                $validated['approved_days'],

            'approved_cost_impact' =>
                $validated['approved_cost_impact'],

            'eot_approved_days' =>
                $validated['eot_approved_days'],

            'approval_remarks' =>
                $validated['approval_remarks'] ?? null,

            'approved_by' =>
                Auth::id(),

            'approval_date' =>
                now(),

            'status' =>
                $newStatus,

            'updated_by' =>
                Auth::id(),
        ]);

        $this->addHistory(
            $delay,
            'Approved',
            $oldStatus,
            $newStatus,
            $validated['approval_remarks'] ?? null
        );

        return redirect()
            ->route(
                'admin.projects.construction.delays.show',
                [
                    'project' => $project,
                    'delay' => $delay,
                ]
            )
            ->with(
                'success',
                $newStatus === 'Partially Approved'
                    ? 'Delay partially approved successfully.'
                    : 'Delay approved successfully.'
            );
    }

    /**
     * Rejection form.
     */
    public function rejection(
        Project $project,
        ConstructionDelay $delay
    ): View {
        $this->checkProject(
            $project,
            $delay
        );

        if (!in_array($delay->status, [
            'Submitted',
            'Under Review',
            'Under Assessment',
        ])) {
            abort(
                422,
                'This delay cannot be rejected in its current status.'
            );
        }

        return view(
            'construction.delays.rejection',
            compact(
                'project',
                'delay'
            )
        );
    }

    /**
     * Reject delay.
     */
    public function reject(
        Request $request,
        Project $project,
        ConstructionDelay $delay
    ): RedirectResponse {
        $this->checkProject(
            $project,
            $delay
        );

        if (!in_array($delay->status, [
            'Submitted',
            'Under Review',
            'Under Assessment',
        ])) {
            return back()->withErrors([
                'error' =>
                    'This delay cannot be rejected in its current status.',
            ]);
        }

        $validated = $request->validate([
            'rejection_remarks' => [
                'required',
                'string',
                'max:5000',
            ],
        ]);

        $oldStatus = $delay->status;

        $delay->update([
            'status' => 'Rejected',

            'rejection_remarks' =>
                $validated['rejection_remarks'],

            'rejected_by' =>
                Auth::id(),

            'rejection_date' =>
                now(),

            'updated_by' =>
                Auth::id(),
        ]);

        $this->addHistory(
            $delay,
            'Rejected',
            $oldStatus,
            'Rejected',
            $validated['rejection_remarks']
        );

        return redirect()
            ->route(
                'admin.projects.construction.delays.show',
                [
                    'project' => $project,
                    'delay' => $delay,
                ]
            )
            ->with(
                'success',
                'Delay rejected successfully.'
            );
    }

    /**
     * Close delay.
     */
    public function close(
        Project $project,
        ConstructionDelay $delay
    ): RedirectResponse {
        $this->checkProject(
            $project,
            $delay
        );

        if (!in_array($delay->status, [
            'Approved',
            'Partially Approved',
        ])) {
            return back()->withErrors([
                'error' =>
                    'Only approved delays can be closed.',
            ]);
        }

        $oldStatus = $delay->status;

        $delay->update([
            'status' => 'Closed',

            'closed_by' =>
                Auth::id(),

            'closed_date' =>
                now(),

            'updated_by' =>
                Auth::id(),
        ]);

        $this->addHistory(
            $delay,
            'Closed',
            $oldStatus,
            'Closed',
            'Delay closed.'
        );

        return back()->with(
            'success',
            'Delay closed successfully.'
        );
    }

    /**
     * Delete delay.
     */
    public function destroy(
        Project $project,
        ConstructionDelay $delay
    ): RedirectResponse {
        $this->checkProject(
            $project,
            $delay
        );

        if (!in_array($delay->status, [
            'Draft',
            'Rejected',
        ])) {
            return back()->withErrors([
                'error' =>
                    'Only draft or rejected delays can be deleted.',
            ]);
        }

        $delay->delete();

        return redirect()
            ->route(
                'admin.projects.construction.delays.index',
                $project
            )
            ->with(
                'success',
                'Delay deleted successfully.'
            );
    }

    /**
     * Validate delay.
     */
    protected function validateDelay(
	    Request $request,
	    Project $project
	): array {
	    return $request->validate([

	        'construction_work_order_id' => [
	            'nullable',
	            'integer',
	            'exists:construction_work_orders,id',
	        ],

	        'construction_schedule_activity_id' => [
	            'required',
	            'integer',
	            'exists:construction_schedule_activities,id',
	        ],

	        'construction_claim_id' => [
	            'nullable',
	            'integer',
	            'exists:construction_claims,id',
	        ],

	        'delay_type' => [
	            'required',
	            'in:Design,Client,Consultant,Contractor,Material,Equipment,Manpower,Weather,Authority,Site Condition,Financial,Procurement,Force Majeure,Other',
	        ],

	        'delay_title' => [
	            'required',
	            'string',
	            'max:255',
	        ],

	        'delay_date' => [
	            'required',
	            'date',
	        ],

	        'start_date' => [
	            'nullable',
	            'date',
	        ],

	        'end_date' => [
	            'nullable',
	            'date',
	            'after_or_equal:start_date',
	        ],

	        'reported_days' => [
	            'required',
	            'integer',
	            'min:0',
	        ],

	        'claimant_type' => [
	            'nullable',
	            'in:Contractor,Consultant,Client,Supplier,Other',
	        ],

	        'claimant_name' => [
	            'nullable',
	            'string',
	            'max:255',
	        ],

	        'responsible_party_type' => [
	            'nullable',
	            'in:Client,Consultant,Contractor,Supplier,Authority,Third Party,Force Majeure,Other,Unknown',
	        ],

	        'responsible_party_name' => [
	            'nullable',
	            'string',
	            'max:255',
	        ],

	        'description' => [
	            'nullable',
	            'string',
	        ],

	        'cause' => [
	            'nullable',
	            'string',
	        ],

	        'impact_description' => [
	            'nullable',
	            'string',
	        ],

	        'schedule_impact' => [
	            'nullable',
	            'string',
	        ],

	        'cost_impact' => [
	            'nullable',
	            'numeric',
	            'min:0',
	        ],

	        'eot_requested_days' => [
	            'nullable',
	            'integer',
	            'min:0',
	        ],

	        'priority' => [
	            'required',
	            'in:Low,Medium,High,Critical',
	        ],

	        'remarks' => [
	            'nullable',
	            'string',
	        ],
	    ]);
	}

    /**
     * Validate project relationships.
     */
    protected function validateRelationships(
	    array $data,
	    Project $project
	): void {

	    /*
	    |--------------------------------------------------------------------------
	    | Work Order
	    |--------------------------------------------------------------------------
	    */

	    $workOrder = null;

	    if (!empty($data['construction_work_order_id'])) {

	        $workOrder = ConstructionWorkOrder::query()
	            ->where('id', $data['construction_work_order_id'])
	            ->where('project_id', $project->id)
	            ->first();

	        if (!$workOrder) {

	            abort(
	                422,
	                'Selected work order does not belong to this project.'
	            );
	        }
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | Schedule Activity
	    |--------------------------------------------------------------------------
	    */

	    $activity = ConstructionScheduleActivity::query()
	        ->where('id', $data['construction_schedule_activity_id'])
	        ->where('project_id', $project->id)
	        ->first();

	    if (!$activity) {

	        abort(
	            422,
	            'Selected schedule activity does not belong to this project.'
	        );
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | Schedule Activity -> Work Order
	    |--------------------------------------------------------------------------
	    */

	    if ($workOrder) {

	        if (
	            empty($activity->construction_work_order_id)
	            ||
	            (int) $activity->construction_work_order_id !==
	            (int) $workOrder->id
	        ) {

	            abort(
	                422,
	                'Selected schedule activity does not belong to the selected work order.'
	            );
	        }
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | Claim
	    |--------------------------------------------------------------------------
	    */

	    if (!empty($data['construction_claim_id'])) {

	        $claimExists = ConstructionClaim::query()
	            ->where('id', $data['construction_claim_id'])
	            ->where('project_id', $project->id)
	            ->exists();

	        if (!$claimExists) {

	            abort(
	                422,
	                'Selected claim does not belong to this project.'
	            );
	        }
	    }
	}

    /**
     * Check project.
     */
    protected function checkProject(
        Project $project,
        ConstructionDelay $delay
    ): void {
        if (
            (int) $delay->project_id !==
            (int) $project->id
        ) {
            abort(404);
        }
    }

    /**
     * Add history.
     */
    protected function addHistory(
        ConstructionDelay $delay,
        string $action,
        ?string $oldStatus,
        ?string $newStatus,
        ?string $remarks = null
    ): void {
        ConstructionDelayHistory::create([
            'construction_delay_id' =>
                $delay->id,

            'action' =>
                $action,

            'old_status' =>
                $oldStatus,

            'new_status' =>
                $newStatus,

            'remarks' =>
                $remarks,

            'performed_by' =>
                Auth::id(),

            'performed_at' =>
                now(),
        ]);
    }

    /**
     * Generate delay number.
     */
    protected function generateDelayNumber(): string
    {
        $year = now()->year;

        $last = ConstructionDelay::withTrashed()
            ->where(
                'delay_number',
                'like',
                "DLY-{$year}-%"
            )
            ->orderByDesc('id')
            ->first();

        $next = 1;

        if ($last) {
            $next =
                ((int) substr(
                    $last->delay_number,
                    -6
                )) + 1;
        }

        return sprintf(
            'DLY-%d-%06d',
            $year,
            $next
        );
    }
}