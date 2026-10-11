<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionRisk;
use App\Models\ConstructionRiskAction;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConstructionRiskActionController extends Controller
{
    /**
     * List risk actions.
     */
    public function index(
        Project $project,
        ConstructionRisk $risk
    ): View {
        $this->validateRiskProject($project, $risk);

        $actions = $risk->actions()
            ->with([
                'assignedTo',
                'creator',
                'updater',
            ])
            ->latest()
            ->paginate(20);

        return view(
            'construction.risks.actions.index',
            compact(
                'project',
                'risk',
                'actions'
            )
        );
    }

    /**
     * Create action form.
     */
    public function create(
        Project $project,
        ConstructionRisk $risk
    ): View {
        $this->validateRiskProject($project, $risk);

        $users = User::query()
            ->orderBy('name')
            ->get();

        return view(
            'construction.risks.actions.create',
            compact(
                'project',
                'risk',
                'users'
            )
        );
    }

    /**
     * Store action.
     */
    public function store(
        Request $request,
        Project $project,
        ConstructionRisk $risk
    ): RedirectResponse {
        $this->validateRiskProject($project, $risk);

        $validated = $request->validate([
            'action_title' => [
                'required',
                'string',
                'max:255',
            ],

            'action_description' => [
                'nullable',
                'string',
            ],

            'action_type' => [
                'required',
                'in:Preventive,Mitigation,Corrective,Contingency,Monitoring',
            ],

            'assigned_to' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'assigned_to_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'target_date' => [
                'nullable',
                'date',
            ],

            'completion_date' => [
                'nullable',
                'date',
                'after_or_equal:target_date',
            ],

            'status' => [
                'required',
                'in:Open,In Progress,Completed,Overdue,Cancelled',
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

        $validated['construction_risk_id'] = $risk->id;
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        ConstructionRiskAction::create($validated);

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
                'Risk action added successfully.'
            );
    }

    /**
     * Edit action.
     */
    public function edit(
        Project $project,
        ConstructionRisk $risk,
        ConstructionRiskAction $action
    ): View {
        $this->validateRiskProject($project, $risk);
        $this->validateActionRisk($risk, $action);

        $users = User::query()
            ->orderBy('name')
            ->get();

        return view(
            'construction.risks.actions.edit',
            compact(
                'project',
                'risk',
                'action',
                'users'
            )
        );
    }

    /**
     * Update action.
     */
    public function update(
        Request $request,
        Project $project,
        ConstructionRisk $risk,
        ConstructionRiskAction $action
    ): RedirectResponse {
        $this->validateRiskProject($project, $risk);
        $this->validateActionRisk($risk, $action);

        $validated = $request->validate([
            'action_title' => [
                'required',
                'string',
                'max:255',
            ],

            'action_description' => [
                'nullable',
                'string',
            ],

            'action_type' => [
                'required',
                'in:Preventive,Mitigation,Corrective,Contingency,Monitoring',
            ],

            'assigned_to' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'assigned_to_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'target_date' => [
                'nullable',
                'date',
            ],

            'completion_date' => [
                'nullable',
                'date',
                'after_or_equal:target_date',
            ],

            'status' => [
                'required',
                'in:Open,In Progress,Completed,Overdue,Cancelled',
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

        $validated['updated_by'] = auth()->id();

        $action->update($validated);

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
                'Risk action updated successfully.'
            );
    }

    /**
     * Delete action.
     */
    public function destroy(
        Project $project,
        ConstructionRisk $risk,
        ConstructionRiskAction $action
    ): RedirectResponse {
        $this->validateRiskProject($project, $risk);
        $this->validateActionRisk($risk, $action);

        $action->delete();

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
                'Risk action deleted successfully.'
            );
    }

    /**
     * Validate risk belongs to project.
     */
    protected function validateRiskProject(
        Project $project,
        ConstructionRisk $risk
    ): void {
        if ((int) $risk->project_id !== (int) $project->id) {
            abort(404);
        }
    }

    /**
     * Validate action belongs to risk.
     */
    protected function validateActionRisk(
        ConstructionRisk $risk,
        ConstructionRiskAction $action
    ): void {
        if (
            (int) $action->construction_risk_id !==
            (int) $risk->id
        ) {
            abort(404);
        }
    }
}