<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\DevelopmentStrategy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DevelopmentStrategyController extends Controller
{
    /**
     * Display the development strategy.
     */
    public function index(Project $project)
    {
        $developmentStrategy =
            $project->developmentStrategy;

        return view(
            'projects.development-strategy.index',
            compact(
                'project',
                'developmentStrategy'
            )
        );
    }


    /**
     * Show create form.
     */
    public function create(Project $project)
	{
	    $project->load('developmentStrategy');

	    if ($project->developmentStrategy) {
	        return redirect()->route(
	            'admin.projects.development-strategy.show',
	            [
	                'project' => $project->id,
	                'developmentStrategy' =>
	                    $project->developmentStrategy->id,
	            ]
	        )->with(
	            'info',
	            'Development Strategy already exists for this project.'
	        );
	    }

	    return view(
	        'projects.development-strategy.create',
	        compact('project')
	    );
	}


    /**
     * Store development strategy.
     */
    public function store(
        Request $request,
        Project $project
    ) {
        /*
        |--------------------------------------------------------------------------
        | Prevent duplicate strategy
        |--------------------------------------------------------------------------
        */

        if ($project->developmentStrategy) {

            return redirect()
                ->route(
                    'admin.projects.development-strategy.show',
                    [
                        'project' =>
                            $project->id,

                        'developmentStrategy' =>
                            $project
                                ->developmentStrategy
                                ->id,
                    ]
                )
                ->with(
                    'error',
                    'Development Strategy already exists for this project.'
                );
        }


        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */

            'title' => [
                'required',
                'string',
                'max:255',
            ],


            /*
            |--------------------------------------------------------------------------
            | Development Vision & Concept
            |--------------------------------------------------------------------------
            */

            'development_vision' => [
                'nullable',
                'string',
            ],

            'development_concept' => [
                'nullable',
                'string',
            ],

            'development_objectives' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Development Approach
            |--------------------------------------------------------------------------
            */

            'development_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'development_model' => [
                'nullable',
                'string',
                'max:100',
            ],

            'development_approach' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Market Positioning
            |--------------------------------------------------------------------------
            */

            'target_market' => [
                'nullable',
                'string',
            ],

            'market_positioning' => [
                'nullable',
                'string',
            ],

            'competitive_strategy' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Development Mix
            |--------------------------------------------------------------------------
            */

            'development_mix' => [
                'nullable',
                'string',
            ],

            'planned_gla' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'planned_nla' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'planned_leasable_area' => [
                'nullable',
                'numeric',
                'min:0',
            ],


            /*
            |--------------------------------------------------------------------------
            | Strategic Considerations
            |--------------------------------------------------------------------------
            */

            'key_assumptions' => [
                'nullable',
                'string',
            ],

            'strategic_constraints' => [
                'nullable',
                'string',
            ],

            'key_opportunities' => [
                'nullable',
                'string',
            ],

            'key_challenges' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Recommended Strategy
            |--------------------------------------------------------------------------
            */

            'recommended_strategy' => [
                'nullable',
                'string',
            ],

            'strategic_rationale' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status' => [
                'required',
                'string',
                'max:50',
            ],

            'strategy_date' => [
                'nullable',
                'date',
            ],

            'approval_date' => [
                'nullable',
                'date',
            ],

            'approved_by' => [
                'nullable',
                'integer',
            ],


            /*
            |--------------------------------------------------------------------------
            | Remarks
            |--------------------------------------------------------------------------
            */

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Create Strategy
        |--------------------------------------------------------------------------
        */

        $developmentStrategy = DB::transaction(
            function () use (
                $validated,
                $project
            ) {

                $data = $validated;


                /*
                |--------------------------------------------------------------------------
                | Project Relationship
                |--------------------------------------------------------------------------
                */

                $data['project_id'] =
                    $project->id;


                /*
                |--------------------------------------------------------------------------
                | Strategy Number
                |--------------------------------------------------------------------------
                */

                $data['strategy_number'] =
                    'DS-' .
                    now()->format('YmdHis') .
                    '-' .
                    Str::upper(
                        Str::random(4)
                    );


                /*
                |--------------------------------------------------------------------------
                | Audit
                |--------------------------------------------------------------------------
                */

                $data['created_by'] =
                    auth()->id();


                return DevelopmentStrategy::create(
                    $data
                );
            }
        );


        return redirect()->route(
            'admin.projects.development-strategy.show',
            [
                'project' =>
                    $project->id,

                'developmentStrategy' =>
                    $developmentStrategy->id,
            ]
        )->with(
            'success',
            'Development Strategy created successfully.'
        );
    }


    /**
     * Display strategy.
     */
    public function show(
	    Project $project,
	    DevelopmentStrategy $developmentStrategy
	) {
	    $this->validateStrategy(
	        $project,
	        $developmentStrategy
	    );

	    $project->load('developmentStrategy');

	    return view(
	        'projects.development-strategy.show',
	        compact(
	            'project',
	            'developmentStrategy'
	        )
	    );
	}


    /**
     * Show edit form.
     */
    public function edit(
	    Project $project,
	    DevelopmentStrategy $developmentStrategy
	) {
	    $this->validateStrategy(
	        $project,
	        $developmentStrategy
	    );

	    return view(
	        'projects.development-strategy.edit',
	        compact(
	            'project',
	            'developmentStrategy'
	        )
	    );
	}


    /**
     * Update strategy.
     */
    public function update(
        Request $request,
        Project $project,
        DevelopmentStrategy $developmentStrategy
    ) {
        $this->validateStrategy(
            $project,
            $developmentStrategy
        );


        $validated = $request->validate([

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'development_vision' => [
                'nullable',
                'string',
            ],

            'development_concept' => [
                'nullable',
                'string',
            ],

            'development_objectives' => [
                'nullable',
                'string',
            ],

            'development_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'development_model' => [
                'nullable',
                'string',
                'max:100',
            ],

            'development_approach' => [
                'nullable',
                'string',
            ],

            'target_market' => [
                'nullable',
                'string',
            ],

            'market_positioning' => [
                'nullable',
                'string',
            ],

            'competitive_strategy' => [
                'nullable',
                'string',
            ],

            'development_mix' => [
                'nullable',
                'string',
            ],

            'planned_gla' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'planned_nla' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'planned_leasable_area' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'key_assumptions' => [
                'nullable',
                'string',
            ],

            'strategic_constraints' => [
                'nullable',
                'string',
            ],

            'key_opportunities' => [
                'nullable',
                'string',
            ],

            'key_challenges' => [
                'nullable',
                'string',
            ],

            'recommended_strategy' => [
                'nullable',
                'string',
            ],

            'strategic_rationale' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'string',
                'max:50',
            ],

            'strategy_date' => [
                'nullable',
                'date',
            ],

            'approval_date' => [
                'nullable',
                'date',
            ],

            'approved_by' => [
                'nullable',
                'integer',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        $validated['updated_by'] =
            auth()->id();


        /*
        |--------------------------------------------------------------------------
        | project_id cannot be changed
        |--------------------------------------------------------------------------
        */

        unset(
            $validated['project_id'],
            $validated['strategy_number']
        );


        $developmentStrategy->update(
            $validated
        );


        return redirect()->route(
            'admin.projects.development-strategy.show',
            [
                'project' =>
                    $project->id,

                'developmentStrategy' =>
                    $developmentStrategy->id,
            ]
        )->with(
            'success',
            'Development Strategy updated successfully.'
        );
    }


    /**
     * Delete strategy.
     */
    public function destroy(
        Project $project,
        DevelopmentStrategy $developmentStrategy
    ) {
        $this->validateStrategy(
            $project,
            $developmentStrategy
        );


        /*
        |--------------------------------------------------------------------------
        | Only Draft strategies can be deleted
        |--------------------------------------------------------------------------
        */

        if (
            $developmentStrategy->status !== 'Draft'
        ) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Only Draft Development Strategies can be deleted.'
                );
        }


        $developmentStrategy->delete();


        return redirect()->route(
            'admin.projects.development-strategy.index',
            [
                'project' =>
                    $project->id,
            ]
        )->with(
            'success',
            'Development Strategy deleted successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Relationship Validation
    |--------------------------------------------------------------------------
    */

    private function validateStrategy(
        Project $project,
        DevelopmentStrategy $developmentStrategy
    ): void {

        abort_unless(
            (int) $developmentStrategy->project_id ===
            (int) $project->id,
            404
        );
    }
}