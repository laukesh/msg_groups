<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionRisk;
use App\Models\ConstructionRiskHistory;
use App\Models\ConstructionScheduleActivity;
use App\Models\ConstructionWorkOrder;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ConstructionRiskController extends Controller
{
    /**
     * Risk register.
     */
    public function index(Project $project): View
    {
        $risks = ConstructionRisk::query()
            ->where('project_id', $project->id)
            ->with([
                'workOrder',
                'scheduleActivity',
                'identifiedBy',
            ])
            ->withCount('actions')
            ->withCount('documents')
            ->latest('risk_date')
            ->latest('id')
            ->paginate(20);

        return view(
            'construction.risks.index',
            compact(
                'project',
                'risks'
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

        return view(
            'construction.risks.create',
            compact(
                'project',
                'workOrders',
                'scheduleActivities'
            )
        );
    }


    /**
     * Store risk.
     */
    public function store(
        Request $request,
        Project $project
    ): RedirectResponse {

        $validated = $this->validateRisk(
            $request,
            $project
        );

        $this->validateRelationships(
            $validated,
            $project
        );

        /*
        |--------------------------------------------------------------------------
        | Calculate Initial Risk Score
        |--------------------------------------------------------------------------
        */

        $riskScore = $this->calculateRiskScore(
            $validated['probability'],
            $validated['impact_level']
        );

        $riskRating = $this->calculateRiskRating(
            $riskScore
        );


        DB::transaction(function () use (
            $validated,
            $project,
            $riskScore,
            $riskRating
        ) {

            $risk = ConstructionRisk::create([

                ...$validated,

                'project_id' => $project->id,

                'risk_number' =>
                    $this->generateRiskNumber(),

                'risk_score' =>
                    $riskScore,

                'risk_rating' =>
                    $riskRating,

                'status' =>
                    'Draft',

                'created_by' =>
                    Auth::id(),

                'updated_by' =>
                    Auth::id(),

                'identified_by' =>
                    Auth::id(),
            ]);


            $this->addHistory(
                $risk,
                'Created',
                null,
                'Draft',
                'Risk created.'
            );
        });


        return redirect()
            ->route(
                'admin.projects.construction.risks.index',
                $project
            )
            ->with(
                'success',
                'Risk created successfully.'
            );
    }


    /**
     * Show risk.
     */
    public function show(
        Project $project,
        ConstructionRisk $risk
    ): View {

        $this->checkProject(
            $project,
            $risk
        );

        $risk->load([
            'project',
            'workOrder',
            'scheduleActivity',
            'identifiedBy',
            'closedBy',
            'creator',
            'updater',
            'actions.assignedTo',
            'actions.creator',
            'actions.updater',
            'documents.uploadedBy',
            'history.performedBy',
        ]);

        return view(
            'construction.risks.show',
            compact(
                'project',
                'risk'
            )
        );
    }


    /**
     * Edit form.
     */
    public function edit(
        Project $project,
        ConstructionRisk $risk
    ): View {

        $this->checkProject(
            $project,
            $risk
        );

        if (!in_array($risk->status, [
            'Draft',
            'Identified',
            'Rejected',
        ])) {

            abort(
                422,
                'Only draft, identified or rejected risks can be edited.'
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


        return view(
            'construction.risks.edit',
            compact(
                'project',
                'risk',
                'workOrders',
                'scheduleActivities'
            )
        );
    }


    /**
     * Update risk.
     */
    public function update(
        Request $request,
        Project $project,
        ConstructionRisk $risk
    ): RedirectResponse {

        $this->checkProject(
            $project,
            $risk
        );

        if (!in_array($risk->status, [
            'Draft',
            'Identified',
            'Rejected',
        ])) {

            abort(
                422,
                'Only draft, identified or rejected risks can be edited.'
            );
        }


        $oldStatus = $risk->status;


        $validated = $this->validateRisk(
            $request,
            $project
        );

        $this->validateRelationships(
            $validated,
            $project
        );


        /*
        |--------------------------------------------------------------------------
        | Recalculate Initial Risk
        |--------------------------------------------------------------------------
        */

        $riskScore = $this->calculateRiskScore(
            $validated['probability'],
            $validated['impact_level']
        );

        $riskRating = $this->calculateRiskRating(
            $riskScore
        );


        $risk->update([

            ...$validated,

            'risk_score' =>
                $riskScore,

            'risk_rating' =>
                $riskRating,

            'status' =>
                'Draft',

            'residual_risk_score' =>
                0,

            'residual_risk_rating' =>
                null,

            'updated_by' =>
                Auth::id(),
        ]);


        $this->addHistory(
            $risk,
            'Updated',
            $oldStatus,
            'Draft',
            'Risk updated.'
        );


        return redirect()
            ->route(
                'admin.projects.construction.risks.show',
                [
                    'project' => $project,
                    'risk' => $risk,
                ]
            )
            ->with(
                'success',
                'Risk updated successfully.'
            );
    }


    /**
     * Submit risk.
     */
    public function submit(
        Project $project,
        ConstructionRisk $risk
    ): RedirectResponse {

        $this->checkProject(
            $project,
            $risk
        );


        if ($risk->status !== 'Draft') {

            return back()->withErrors([
                'error' =>
                    'Only draft risks can be submitted.',
            ]);
        }


        $oldStatus = $risk->status;


        $risk->update([
            'status' => 'Identified',
            'updated_by' => Auth::id(),
        ]);


        $this->addHistory(
            $risk,
            'Submitted',
            $oldStatus,
            'Identified',
            'Risk submitted for assessment.'
        );


        return back()->with(
            'success',
            'Risk submitted successfully.'
        );
    }


    /**
     * Start risk assessment.
     */
    public function assess(
        Project $project,
        ConstructionRisk $risk
    ): RedirectResponse {

        $this->checkProject(
            $project,
            $risk
        );


        if ($risk->status !== 'Identified') {

            return back()->withErrors([
                'error' =>
                    'Only identified risks can be assessed.',
            ]);
        }


        $oldStatus = $risk->status;


        $risk->update([
            'status' => 'Under Assessment',
            'updated_by' => Auth::id(),
        ]);


        $this->addHistory(
            $risk,
            'Assessment Started',
            $oldStatus,
            'Under Assessment',
            'Risk assessment started.'
        );


        return back()->with(
            'success',
            'Risk moved to assessment.'
        );
    }


    /**
     * Mitigation planning.
     */
    public function mitigation(
        Project $project,
        ConstructionRisk $risk
    ): RedirectResponse {

        $this->checkProject(
            $project,
            $risk
        );


        if ($risk->status !== 'Under Assessment') {

            return back()->withErrors([
                'error' =>
                    'Only risks under assessment can move to mitigation planning.',
            ]);
        }


        $oldStatus = $risk->status;


        $risk->update([
            'status' => 'Mitigation Planned',
            'updated_by' => Auth::id(),
        ]);


        $this->addHistory(
            $risk,
            'Mitigation Planned',
            $oldStatus,
            'Mitigation Planned',
            'Risk mitigation planning completed.'
        );


        return back()->with(
            'success',
            'Risk moved to mitigation planning.'
        );
    }


    /**
     * Start monitoring.
     */
    public function monitor(
        Project $project,
        ConstructionRisk $risk
    ): RedirectResponse {

        $this->checkProject(
            $project,
            $risk
        );


        if ($risk->status !== 'Mitigation Planned') {

            return back()->withErrors([
                'error' =>
                    'Only risks with mitigation plans can be monitored.',
            ]);
        }


        $oldStatus = $risk->status;


        $risk->update([
            'status' => 'Monitoring',
            'updated_by' => Auth::id(),
        ]);


        $this->addHistory(
            $risk,
            'Monitoring Started',
            $oldStatus,
            'Monitoring',
            'Risk moved to monitoring.'
        );


        return back()->with(
            'success',
            'Risk moved to monitoring.'
        );
    }


    /**
     * Escalate risk.
     */
    public function escalate(
        Project $project,
        ConstructionRisk $risk
    ): RedirectResponse {

        $this->checkProject(
            $project,
            $risk
        );


        if (!in_array($risk->status, [
            'Identified',
            'Under Assessment',
            'Mitigation Planned',
            'Monitoring',
        ])) {

            return back()->withErrors([
                'error' =>
                    'This risk cannot be escalated in its current status.',
            ]);
        }


        $oldStatus = $risk->status;


        $risk->update([
            'status' => 'Escalated',
            'priority' => 'Critical',
            'updated_by' => Auth::id(),
        ]);


        $this->addHistory(
            $risk,
            'Escalated',
            $oldStatus,
            'Escalated',
            'Risk escalated as critical.'
        );


        return back()->with(
            'success',
            'Risk escalated successfully.'
        );
    }


    /**
     * Accept risk.
     */
    public function accept(
        Project $project,
        ConstructionRisk $risk
    ): RedirectResponse {

        $this->checkProject(
            $project,
            $risk
        );


        if (!in_array($risk->status, [
            'Identified',
            'Under Assessment',
            'Mitigation Planned',
            'Monitoring',
            'Escalated',
        ])) {

            return back()->withErrors([
                'error' =>
                    'This risk cannot be accepted in its current status.',
            ]);
        }


        $oldStatus = $risk->status;


        $risk->update([
            'status' => 'Accepted',
            'response_strategy' => 'Accept',
            'updated_by' => Auth::id(),
        ]);


        $this->addHistory(
            $risk,
            'Accepted',
            $oldStatus,
            'Accepted',
            'Risk accepted.'
        );


        return back()->with(
            'success',
            'Risk accepted successfully.'
        );
    }


    /**
     * Close risk.
     */
    public function close(
        Project $project,
        ConstructionRisk $risk
    ): RedirectResponse {

        $this->checkProject(
            $project,
            $risk
        );


        if (!in_array($risk->status, [
            'Monitoring',
            'Accepted',
        ])) {

            return back()->withErrors([
                'error' =>
                    'Only monitored or accepted risks can be closed.',
            ]);
        }


        $oldStatus = $risk->status;


        $risk->update([
            'status' => 'Closed',
            'closed_by' => Auth::id(),
            'closed_date' => now(),
            'updated_by' => Auth::id(),
        ]);


        $this->addHistory(
            $risk,
            'Closed',
            $oldStatus,
            'Closed',
            'Risk closed.'
        );


        return back()->with(
            'success',
            'Risk closed successfully.'
        );
    }


    /**
     * Delete risk.
     */
    public function destroy(
        Project $project,
        ConstructionRisk $risk
    ): RedirectResponse {

        $this->checkProject(
            $project,
            $risk
        );


        if (!in_array($risk->status, [
            'Draft',
            'Identified',
            'Rejected',
        ])) {

            return back()->withErrors([
                'error' =>
                    'Only draft, identified or rejected risks can be deleted.',
            ]);
        }


        $risk->delete();


        return redirect()
            ->route(
                'admin.projects.construction.risks.index',
                $project
            )
            ->with(
                'success',
                'Risk deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    protected function validateRisk(
        Request $request,
        Project $project
    ): array {

        return $request->validate([

            'construction_work_order_id' => [
                'nullable',
                'integer',
            ],

            'construction_schedule_activity_id' => [
                'nullable',
                'integer',
            ],

            'risk_title' => [
                'required',
                'string',
                'max:255',
            ],

            'risk_category' => [
                'required',
                'in:Design,Technical,Construction,Procurement,Material,Equipment,Manpower,Financial,Commercial,Contract,Schedule,Quality,HSE,Environmental,Regulatory,Authority,Stakeholder,Site Condition,External,Other',
            ],

            'risk_date' => [
                'required',
                'date',
            ],

            'risk_description' => [
                'nullable',
                'string',
            ],

            'risk_cause' => [
                'nullable',
                'string',
            ],

            'potential_impact' => [
                'nullable',
                'string',
            ],

            'potential_cost_impact' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'potential_delay_days' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'probability' => [
                'required',
                'in:Rare,Unlikely,Possible,Likely,Almost Certain',
            ],

            'impact_level' => [
                'required',
                'in:Insignificant,Minor,Moderate,Major,Severe',
            ],

            'response_strategy' => [
                'nullable',
                'in:Avoid,Mitigate,Transfer,Accept,Escalate',
            ],

            'response_plan' => [
                'nullable',
                'string',
            ],

            'owner_type' => [
                'nullable',
                'in:Client,Consultant,Contractor,Supplier,Project Team,Other',
            ],

            'owner_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'target_resolution_date' => [
                'nullable',
                'date',
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


    /*
    |--------------------------------------------------------------------------
    | Relationship Validation
    |--------------------------------------------------------------------------
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
                ->where(
                    'id',
                    $data['construction_work_order_id']
                )
                ->where(
                    'project_id',
                    $project->id
                )
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

        if (!empty($data['construction_schedule_activity_id'])) {

            $activity = ConstructionScheduleActivity::query()
                ->where(
                    'id',
                    $data['construction_schedule_activity_id']
                )
                ->where(
                    'project_id',
                    $project->id
                )
                ->first();

            if (!$activity) {

                abort(
                    422,
                    'Selected schedule activity does not belong to this project.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Activity -> Work Order
            |--------------------------------------------------------------------------
            */

            if (
                $workOrder
                &&
                (
                    empty($activity->construction_work_order_id)
                    ||
                    (int) $activity->construction_work_order_id !==
                    (int) $workOrder->id
                )
            ) {

                abort(
                    422,
                    'Selected schedule activity does not belong to the selected work order.'
                );
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Project Check
    |--------------------------------------------------------------------------
    */

    protected function checkProject(
        Project $project,
        ConstructionRisk $risk
    ): void {

        if (
            (int) $risk->project_id !==
            (int) $project->id
        ) {

            abort(404);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Risk Score
    |--------------------------------------------------------------------------
    */

    protected function calculateRiskScore(
        string $probability,
        string $impact
    ): int {

        $probabilityScores = [

            'Rare' => 1,

            'Unlikely' => 2,

            'Possible' => 3,

            'Likely' => 4,

            'Almost Certain' => 5,
        ];


        $impactScores = [

            'Insignificant' => 1,

            'Minor' => 2,

            'Moderate' => 3,

            'Major' => 4,

            'Severe' => 5,
        ];


        return
            $probabilityScores[$probability]
            *
            $impactScores[$impact];
    }


    /*
    |--------------------------------------------------------------------------
    | Risk Rating
    |--------------------------------------------------------------------------
    */

    protected function calculateRiskRating(
        int $score
    ): string {

        if ($score >= 17) {

            return 'Critical';
        }

        if ($score >= 10) {

            return 'High';
        }

        if ($score >= 5) {

            return 'Medium';
        }

        return 'Low';
    }


    /*
    |--------------------------------------------------------------------------
    | History
    |--------------------------------------------------------------------------
    */

    protected function addHistory(
        ConstructionRisk $risk,
        string $action,
        ?string $oldStatus,
        ?string $newStatus,
        ?string $remarks = null
    ): void {

        ConstructionRiskHistory::create([

            'construction_risk_id' =>
                $risk->id,

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


    /*
    |--------------------------------------------------------------------------
    | Generate Risk Number
    |--------------------------------------------------------------------------
    */

    protected function generateRiskNumber(): string
    {
        $year = now()->year;


        $last = ConstructionRisk::withTrashed()
            ->where(
                'risk_number',
                'like',
                "RSK-{$year}-%"
            )
            ->orderByDesc('id')
            ->first();


        $next = 1;


        if ($last) {

            $next =
                ((int) substr(
                    $last->risk_number,
                    -6
                )) + 1;
        }


        return sprintf(
            'RSK-%d-%06d',
            $year,
            $next
        );
    }
}