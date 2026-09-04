<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\ConstructionHseToolboxTalk;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ConstructionHseToolboxTalkController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Project $project): View
    {
        $talks = ConstructionHseToolboxTalk::query()
            ->where('project_id', $project->id)
            ->with([
                'conductedBy',
                'creator',
                'updater',
            ])
            ->latest('id')
            ->get();

        return view(
            'construction.hse.toolbox-talks.index',
            [
                'project' => $project,
                'talks' => $talks,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(Project $project): View
    {
        $users = User::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);

        $toolboxTalkNumber =
            $this->generateToolboxTalkNumber();

        return view(
            'construction.hse.toolbox-talks.create',
            [
                'project' => $project,
                'users' => $users,
                'toolboxTalkNumber' =>
                    $toolboxTalkNumber,
            ]
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

            'toolbox_talk_number' => [
                'required',
                'string',
                'max:100',
                'unique:construction_hse_toolbox_talks,toolbox_talk_number',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'talk_date' => [
                'required',
                'date',
            ],

            'talk_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'topic' => [
                'nullable',
                'string',
                'max:255',
            ],

            'conducted_by' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'conducted_by_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'objectives' => [
                'nullable',
                'string',
            ],

            'discussion_points' => [
                'nullable',
                'string',
            ],

            'safety_instructions' => [
                'nullable',
                'string',
            ],

            'hazards_discussed' => [
                'nullable',
                'string',
            ],

            'precautions' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:Draft,Completed,Cancelled',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        $talk =
            ConstructionHseToolboxTalk::create([

                'project_id' =>
                    $project->id,

                'toolbox_talk_number' =>
                    $validated['toolbox_talk_number'],

                'title' =>
                    $validated['title'],

                'talk_date' =>
                    $validated['talk_date'],

                'talk_time' =>
                    $validated['talk_time'] ?? null,

                'location' =>
                    $validated['location'] ?? null,

                'topic' =>
                    $validated['topic'] ?? null,

                'conducted_by' =>
                    $validated['conducted_by'] ?? null,

                'conducted_by_name' =>
                    $validated['conducted_by_name'] ?? null,

                'objectives' =>
                    $validated['objectives'] ?? null,

                'discussion_points' =>
                    $validated['discussion_points'] ?? null,

                'safety_instructions' =>
                    $validated['safety_instructions'] ?? null,

                'hazards_discussed' =>
                    $validated['hazards_discussed'] ?? null,

                'precautions' =>
                    $validated['precautions'] ?? null,

                'status' =>
                    $validated['status'],

                'remarks' =>
                    $validated['remarks'] ?? null,

                'created_by' =>
                    Auth::id(),

                'updated_by' =>
                    Auth::id(),

            ]);


        return redirect()
            ->route(
                'admin.projects.construction.hse.toolbox-talks.show',
                [
                    'project' => $project,
                    'toolboxTalk' => $talk,
                ]
            )
            ->with(
                'success',
                'Toolbox Talk created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ConstructionHseToolboxTalk $toolboxTalk
    ): View {

        $this->validateProjectRelation(
            $project,
            $toolboxTalk
        );

        $toolboxTalk->load([
            'conductedBy',
            'creator',
            'updater',
        ]);

        return view(
            'construction.hse.toolbox-talks.show',
            [
                'project' => $project,
                'toolboxTalk' => $toolboxTalk,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        Project $project,
        ConstructionHseToolboxTalk $toolboxTalk
    ): View {

        $this->validateProjectRelation(
            $project,
            $toolboxTalk
        );

        $users = User::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);

        return view(
            'construction.hse.toolbox-talks.edit',
            [
                'project' => $project,
                'toolboxTalk' => $toolboxTalk,
                'users' => $users,
            ]
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
        ConstructionHseToolboxTalk $toolboxTalk
    ): RedirectResponse {

        $this->validateProjectRelation(
            $project,
            $toolboxTalk
        );


        $validated = $request->validate([

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'talk_date' => [
                'required',
                'date',
            ],

            'talk_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'topic' => [
                'nullable',
                'string',
                'max:255',
            ],

            'conducted_by' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'conducted_by_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'objectives' => [
                'nullable',
                'string',
            ],

            'discussion_points' => [
                'nullable',
                'string',
            ],

            'safety_instructions' => [
                'nullable',
                'string',
            ],

            'hazards_discussed' => [
                'nullable',
                'string',
            ],

            'precautions' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:Draft,Completed,Cancelled',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],

        ]);


        $toolboxTalk->update([

            'title' =>
                $validated['title'],

            'talk_date' =>
                $validated['talk_date'],

            'talk_time' =>
                $validated['talk_time'] ?? null,

            'location' =>
                $validated['location'] ?? null,

            'topic' =>
                $validated['topic'] ?? null,

            'conducted_by' =>
                $validated['conducted_by'] ?? null,

            'conducted_by_name' =>
                $validated['conducted_by_name'] ?? null,

            'objectives' =>
                $validated['objectives'] ?? null,

            'discussion_points' =>
                $validated['discussion_points'] ?? null,

            'safety_instructions' =>
                $validated['safety_instructions'] ?? null,

            'hazards_discussed' =>
                $validated['hazards_discussed'] ?? null,

            'precautions' =>
                $validated['precautions'] ?? null,

            'status' =>
                $validated['status'],

            'remarks' =>
                $validated['remarks'] ?? null,

            'updated_by' =>
                Auth::id(),

        ]);


        return redirect()
            ->route(
                'admin.projects.construction.hse.toolbox-talks.show',
                [
                    'project' => $project,
                    'toolboxTalk' => $toolboxTalk,
                ]
            )
            ->with(
                'success',
                'Toolbox Talk updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ConstructionHseToolboxTalk $toolboxTalk
    ): RedirectResponse {

        $this->validateProjectRelation(
            $project,
            $toolboxTalk
        );


        $toolboxTalk->delete();


        return redirect()
            ->route(
                'admin.projects.construction.hse.toolbox-talks.index',
                [
                    'project' => $project,
                ]
            )
            ->with(
                'success',
                'Toolbox Talk deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE PROJECT RELATION
    |--------------------------------------------------------------------------
    */

    protected function validateProjectRelation(
        Project $project,
        ConstructionHseToolboxTalk $toolboxTalk
    ): void {

        abort_unless(
            (int) $toolboxTalk->project_id ===
            (int) $project->id,
            404
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE NUMBER
    |--------------------------------------------------------------------------
    */

    protected function generateToolboxTalkNumber(): string
    {
        $lastId =
            ConstructionHseToolboxTalk::max('id')
            ?? 0;

        return 'HSE-TBT-' .
            str_pad(
                $lastId + 1,
                6,
                '0',
                STR_PAD_LEFT
            );
    }
}