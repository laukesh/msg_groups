<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectRisk;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectRiskController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Project $project): View
    {
        $risks = ProjectRisk::where(
            'project_id',
            $project->id
        )
            ->with('riskOwner')
            ->orderByRaw("
                CASE risk_level
                    WHEN 'Critical' THEN 1
                    WHEN 'High' THEN 2
                    WHEN 'Medium' THEN 3
                    WHEN 'Low' THEN 4
                    ELSE 5
                END
            ")
            ->orderByDesc('risk_score')
            ->orderByDesc('id')
            ->get();

        $summary = [
            'total' => $risks->count(),

            'open' => $risks
                ->where('status', 'Open')
                ->count(),

            'monitoring' => $risks
                ->where('status', 'Monitoring')
                ->count(),

            'mitigated' => $risks
                ->where('status', 'Mitigated')
                ->count(),

            'closed' => $risks
                ->where('status', 'Closed')
                ->count(),

            'occurred' => $risks
                ->where('status', 'Occurred')
                ->count(),

            'critical' => $risks
                ->where('risk_level', 'Critical')
                ->count(),

            'high' => $risks
                ->where('risk_level', 'High')
                ->count(),
        ];

        return view(
            'projects.risks.index',
            compact(
                'project',
                'risks',
                'summary'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(Project $project): View
    {
        $lastRiskNumber = ProjectRisk::where(
            'project_id',
            $project->id
        )
            ->orderByDesc('id')
            ->value('risk_number');

        if ($lastRiskNumber) {

            preg_match(
                '/(\d+)$/',
                $lastRiskNumber,
                $matches
            );

            $nextNumber =
                isset($matches[1])
                    ? ((int) $matches[1]) + 1
                    : 1;

        } else {

            $nextNumber = 1;
        }


        $riskNumber =
            'RISK-' .
            $project->id .
            '-' .
            str_pad(
                $nextNumber,
                3,
                '0',
                STR_PAD_LEFT
            );


        $riskOwners = User::query()
            ->orderBy('name')
            ->get();


        return view(
            'projects.risks.create',
            compact(
                'project',
                'riskNumber',
                'riskOwners'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Project $project
    ): RedirectResponse {

        $validated = $request->validate([

            'risk_number' => [
                'required',
                'string',
                'max:100',
                'unique:project_risks,risk_number',
            ],

            'risk_title' => [
                'required',
                'string',
                'max:255',
            ],

            'risk_category' => [
                'required',
                'in:Strategic,Financial,Commercial,Procurement,Contract,Design,Construction,Schedule,Cost,Quality,Safety,Environmental,Legal,Regulatory,Stakeholder,Operational,Technology,Other',
            ],

            'risk_description' => [
                'required',
                'string',
            ],

            'cause' => [
                'nullable',
                'string',
            ],

            'consequence' => [
                'nullable',
                'string',
            ],

            'probability' => [
                'required',
                'in:Very Low,Low,Medium,High,Very High',
            ],

            'impact' => [
                'required',
                'in:Very Low,Low,Medium,High,Very High',
            ],

            'response_strategy' => [
                'required',
                'in:Avoid,Mitigate,Transfer,Accept,Exploit,Enhance,Share',
            ],

            'mitigation_plan' => [
                'nullable',
                'string',
            ],

            'contingency_plan' => [
                'nullable',
                'string',
            ],

            'risk_owner_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'target_date' => [
                'nullable',
                'date',
            ],

            'status' => [
                'required',
                'in:Open,Monitoring,Mitigated,Closed,Occurred',
            ],

            'residual_probability' => [
                'nullable',
                'in:Very Low,Low,Medium,High,Very High',
            ],

            'residual_impact' => [
                'nullable',
                'in:Very Low,Low,Medium,High,Very High',
            ],

            'identified_date' => [
                'nullable',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        $riskScore = $this->calculateScore(
            $validated['probability'],
            $validated['impact']
        );


        $riskLevel = $this->calculateRiskLevel(
            $riskScore
        );


        $residualScore = 0;

        $residualRiskLevel = null;


        if (
            !empty($validated['residual_probability']) &&
            !empty($validated['residual_impact'])
        ) {

            $residualScore =
                $this->calculateScore(
                    $validated['residual_probability'],
                    $validated['residual_impact']
                );

            $residualRiskLevel =
                $this->calculateRiskLevel(
                    $residualScore
                );
        }


        $risk = ProjectRisk::create([

            'project_id' =>
                $project->id,

            'risk_number' =>
                $validated['risk_number'],

            'risk_title' =>
                $validated['risk_title'],

            'risk_category' =>
                $validated['risk_category'],

            'risk_description' =>
                $validated['risk_description'],

            'cause' =>
                $validated['cause'] ?? null,

            'consequence' =>
                $validated['consequence'] ?? null,

            'probability' =>
                $validated['probability'],

            'impact' =>
                $validated['impact'],

            'risk_score' =>
                $riskScore,

            'risk_level' =>
                $riskLevel,

            'response_strategy' =>
                $validated['response_strategy'],

            'mitigation_plan' =>
                $validated['mitigation_plan'] ?? null,

            'contingency_plan' =>
                $validated['contingency_plan'] ?? null,

            'risk_owner_id' =>
                $validated['risk_owner_id'] ?? null,

            'target_date' =>
                $validated['target_date'] ?? null,

            'status' =>
                $validated['status'],

            'residual_probability' =>
                $validated['residual_probability'] ?? null,

            'residual_impact' =>
                $validated['residual_impact'] ?? null,

            'residual_score' =>
                $residualScore,

            'residual_risk_level' =>
                $residualRiskLevel,

            'identified_date' =>
                $validated['identified_date'] ?? now()->toDateString(),

            'closed_date' =>
                $validated['status'] === 'Closed'
                    ? now()->toDateString()
                    : null,

            'remarks' =>
                $validated['remarks'] ?? null,

            'created_by' =>
                auth()->id(),

            'updated_by' =>
                auth()->id(),

        ]);


        return redirect()
            ->route(
                'admin.projects.risks.show',
                [
                    'project' => $project->id,
                    'risk' => $risk->id,
                ]
            )
            ->with(
                'success',
                'Project risk created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ProjectRisk $risk
    ): View {

        $this->validateOwnership(
            $project,
            $risk
        );

        $risk->load('riskOwner');

        return view(
            'projects.risks.show',
            compact(
                'project',
                'risk'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        Project $project,
        ProjectRisk $risk
    ): View {

        $this->validateOwnership(
            $project,
            $risk
        );

        $riskOwners = User::query()
            ->orderBy('name')
            ->get();

        return view(
            'projects.risks.edit',
            compact(
                'project',
                'risk',
                'riskOwners'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Project $project,
        ProjectRisk $risk
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $risk
        );


        $validated = $request->validate([

            'risk_title' => [
                'required',
                'string',
                'max:255',
            ],

            'risk_category' => [
                'required',
                'in:Strategic,Financial,Commercial,Procurement,Contract,Design,Construction,Schedule,Cost,Quality,Safety,Environmental,Legal,Regulatory,Stakeholder,Operational,Technology,Other',
            ],

            'risk_description' => [
                'required',
                'string',
            ],

            'cause' => [
                'nullable',
                'string',
            ],

            'consequence' => [
                'nullable',
                'string',
            ],

            'probability' => [
                'required',
                'in:Very Low,Low,Medium,High,Very High',
            ],

            'impact' => [
                'required',
                'in:Very Low,Low,Medium,High,Very High',
            ],

            'response_strategy' => [
                'required',
                'in:Avoid,Mitigate,Transfer,Accept,Exploit,Enhance,Share',
            ],

            'mitigation_plan' => [
                'nullable',
                'string',
            ],

            'contingency_plan' => [
                'nullable',
                'string',
            ],

            'risk_owner_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'target_date' => [
                'nullable',
                'date',
            ],

            'status' => [
                'required',
                'in:Open,Monitoring,Mitigated,Closed,Occurred',
            ],

            'residual_probability' => [
                'nullable',
                'in:Very Low,Low,Medium,High,Very High',
            ],

            'residual_impact' => [
                'nullable',
                'in:Very Low,Low,Medium,High,Very High',
            ],

            'identified_date' => [
                'nullable',
                'date',
            ],

            'closed_date' => [
                'nullable',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        $riskScore = $this->calculateScore(
            $validated['probability'],
            $validated['impact']
        );


        $riskLevel = $this->calculateRiskLevel(
            $riskScore
        );


        $residualScore = 0;

        $residualRiskLevel = null;


        if (
            !empty($validated['residual_probability']) &&
            !empty($validated['residual_impact'])
        ) {

            $residualScore =
                $this->calculateScore(
                    $validated['residual_probability'],
                    $validated['residual_impact']
                );

            $residualRiskLevel =
                $this->calculateRiskLevel(
                    $residualScore
                );
        }


        $closedDate =
            $validated['status'] === 'Closed'
                ? (
                    $validated['closed_date']
                    ?? $risk->closed_date
                    ?? now()->toDateString()
                )
                : null;


        $risk->update([

            'risk_title' =>
                $validated['risk_title'],

            'risk_category' =>
                $validated['risk_category'],

            'risk_description' =>
                $validated['risk_description'],

            'cause' =>
                $validated['cause'] ?? null,

            'consequence' =>
                $validated['consequence'] ?? null,

            'probability' =>
                $validated['probability'],

            'impact' =>
                $validated['impact'],

            'risk_score' =>
                $riskScore,

            'risk_level' =>
                $riskLevel,

            'response_strategy' =>
                $validated['response_strategy'],

            'mitigation_plan' =>
                $validated['mitigation_plan'] ?? null,

            'contingency_plan' =>
                $validated['contingency_plan'] ?? null,

            'risk_owner_id' =>
                $validated['risk_owner_id'] ?? null,

            'target_date' =>
                $validated['target_date'] ?? null,

            'status' =>
                $validated['status'],

            'residual_probability' =>
                $validated['residual_probability'] ?? null,

            'residual_impact' =>
                $validated['residual_impact'] ?? null,

            'residual_score' =>
                $residualScore,

            'residual_risk_level' =>
                $residualRiskLevel,

            'identified_date' =>
                $validated['identified_date'] ?? null,

            'closed_date' =>
                $closedDate,

            'remarks' =>
                $validated['remarks'] ?? null,

            'updated_by' =>
                auth()->id(),

        ]);


        return redirect()
            ->route(
                'admin.projects.risks.show',
                [
                    'project' => $project->id,
                    'risk' => $risk->id,
                ]
            )
            ->with(
                'success',
                'Project risk updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | CHANGE STATUS
    |--------------------------------------------------------------------------
    */

    public function changeStatus(
        Request $request,
        Project $project,
        ProjectRisk $risk
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $risk
        );


        $validated = $request->validate([

            'status' => [
                'required',
                'in:Open,Monitoring,Mitigated,Closed,Occurred',
            ],

        ]);


        $risk->status =
            $validated['status'];


        if (
            $validated['status'] === 'Closed'
        ) {

            $risk->closed_date =
                $risk->closed_date
                ?? now()->toDateString();

        } else {

            $risk->closed_date = null;

        }


        $risk->updated_by =
            auth()->id();


        $risk->save();


        return back()
            ->with(
                'success',
                'Risk status updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ProjectRisk $risk
    ): RedirectResponse {

        $this->validateOwnership(
            $project,
            $risk
        );


        $risk->delete();


        return redirect()
            ->route(
                'admin.projects.risks.index',
                [
                    'project' => $project->id,
                ]
            )
            ->with(
                'success',
                'Project risk deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SCORE
    |--------------------------------------------------------------------------
    */

    protected function calculateScore(
        string $probability,
        string $impact
    ): int {

        $values = [

            'Very Low' => 1,

            'Low' => 2,

            'Medium' => 3,

            'High' => 4,

            'Very High' => 5,

        ];


        return
            ($values[$probability] ?? 0)
            *
            ($values[$impact] ?? 0);
    }


    /*
    |--------------------------------------------------------------------------
    | RISK LEVEL
    |--------------------------------------------------------------------------
    */

    protected function calculateRiskLevel(
        int $score
    ): string {

        return match (true) {

            $score >= 17 =>
                'Critical',

            $score >= 10 =>
                'High',

            $score >= 5 =>
                'Medium',

            default =>
                'Low',

        };
    }


    /*
    |--------------------------------------------------------------------------
    | OWNERSHIP
    |--------------------------------------------------------------------------
    */

    protected function validateOwnership(
        Project $project,
        ProjectRisk $risk
    ): void {

        abort_unless(
            (int) $risk->project_id ===
            (int) $project->id,
            404
        );
    }
}