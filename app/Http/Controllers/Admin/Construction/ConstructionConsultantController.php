<?php

namespace App\Http\Controllers\Admin\Construction;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectConsultant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConstructionConsultantController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(Project $project): View
    {
        $consultants = ProjectConsultant::query()
            ->where(
                'project_id',
                $project->id
            )
            ->orderBy(
                'company_name'
            )
            ->orderBy(
                'consultant_name'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $summary = [

            'total' =>
                $consultants->count(),

            'active' =>
                $consultants
                    ->where(
                        'status',
                        'Active'
                    )
                    ->count(),

            'completed' =>
                $consultants
                    ->where(
                        'status',
                        'Completed'
                    )
                    ->count(),

            'total_value' =>
                (float) $consultants->sum(
                    'contract_value'
                ),

        ];


        return view(
            'construction.consultants.index',
            compact(
                'project',
                'consultants',
                'summary'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(
        Project $project
    ): View {

        return view(
            'construction.consultants.create',
            compact(
                'project'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Project $project
    ): RedirectResponse {

        $validated = $this->validateConsultant(
            $request
        );


        /*
        |--------------------------------------------------------------------------
        | Project
        |--------------------------------------------------------------------------
        */

        $validated['project_id'] =
            $project->id;


        /*
        |--------------------------------------------------------------------------
        | Defaults
        |--------------------------------------------------------------------------
        */

        $validated['country'] =
            $validated['country']
            ??
            'India';

        $validated['currency'] =
            $validated['currency']
            ??
            'INR';

        $validated['contract_value'] =
            $validated['contract_value']
            ??
            0;


        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        $validated['created_by'] =
            auth()->id();

        $validated['updated_by'] =
            auth()->id();


        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        $consultant =
            ProjectConsultant::create(
                $validated
            );


        /*
        |--------------------------------------------------------------------------
        | Consultant Code
        |--------------------------------------------------------------------------
        */

        $consultant->update([

            'consultant_code' =>
                'CONS-' .
                str_pad(
                    $consultant->id,
                    6,
                    '0',
                    STR_PAD_LEFT
                ),

        ]);


        return redirect()
            ->route(
                'admin.projects.construction.consultants.index',
                $project
            )
            ->with(
                'success',
                'Consultant added successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(
        Project $project,
        ProjectConsultant $consultant
    ): View {

        $this->validateProjectConsultant(
            $project,
            $consultant
        );


        return view(
            'construction.consultants.show',
            compact(
                'project',
                'consultant'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(
        Project $project,
        ProjectConsultant $consultant
    ): View {

        $this->validateProjectConsultant(
            $project,
            $consultant
        );


        return view(
            'construction.consultants.edit',
            compact(
                'project',
                'consultant'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Project $project,
        ProjectConsultant $consultant
    ): RedirectResponse {

        $this->validateProjectConsultant(
            $project,
            $consultant
        );


        $validated = $this->validateConsultant(
            $request
        );


        /*
        |--------------------------------------------------------------------------
        | Defaults
        |--------------------------------------------------------------------------
        */

        $validated['country'] =
            $validated['country']
            ??
            'India';

        $validated['currency'] =
            $validated['currency']
            ??
            'INR';

        $validated['contract_value'] =
            $validated['contract_value']
            ??
            0;


        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        $validated['updated_by'] =
            auth()->id();


        /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |--------------------------------------------------------------------------
        |
        | Do NOT update:
        |
        | project_id
        | consultant_code
        |
        |--------------------------------------------------------------------------
        */

        $consultant->update(
            $validated
        );


        return redirect()
            ->route(
                'admin.projects.construction.consultants.index',
                $project
            )
            ->with(
                'success',
                'Consultant updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Project $project,
        ProjectConsultant $consultant
    ): RedirectResponse {

        $this->validateProjectConsultant(
            $project,
            $consultant
        );


        $consultant->delete();


        return redirect()
            ->route(
                'admin.projects.construction.consultants.index',
                $project
            )
            ->with(
                'success',
                'Consultant deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    protected function validateConsultant(
        Request $request
    ): array {

        return $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Consultant Identification
            |--------------------------------------------------------------------------
            */

            'consultant_type' =>
                'nullable|string|max:100',

            'consultant_role' =>
                'nullable|string|max:100',

            'appointment_type' =>
                'nullable|string|max:100',

            'discipline' =>
                'nullable|string|max:100',

            'specialization' =>
                'nullable|string|max:255',

            'company_name' =>
                'required|string|max:255',

            'consultant_name' =>
                'nullable|string|max:255',


            /*
            |--------------------------------------------------------------------------
            | Professional Information
            |--------------------------------------------------------------------------
            */

            'registration_no' =>
                'nullable|string|max:150',

            'gst_number' =>
                'nullable|string|max:50',

            'pan_number' =>
                'nullable|string|max:50',


            /*
            |--------------------------------------------------------------------------
            | Contact
            |--------------------------------------------------------------------------
            */

            'contact_person' =>
                'nullable|string|max:255',

            'contact_designation' =>
                'nullable|string|max:150',

            'email' =>
                'nullable|email|max:255',

            'phone' =>
                'nullable|string|max:50',

            'alternate_phone' =>
                'nullable|string|max:50',

            'website' =>
                'nullable|url|max:255',


            /*
            |--------------------------------------------------------------------------
            | Address
            |--------------------------------------------------------------------------
            */

            'address' =>
                'nullable|string',

            'city' =>
                'nullable|string|max:100',

            'state' =>
                'nullable|string|max:100',

            'country' =>
                'nullable|string|max:100',

            'postal_code' =>
                'nullable|string|max:20',


            /*
            |--------------------------------------------------------------------------
            | Appointment
            |--------------------------------------------------------------------------
            */

            'appointment_date' =>
                'nullable|date',

            'start_date' =>
                'nullable|date',

            'end_date' =>
                'nullable|date|after_or_equal:start_date',


            /*
            |--------------------------------------------------------------------------
            | Scope
            |--------------------------------------------------------------------------
            */

            'scope_of_services' =>
                'nullable|string',

            'responsibilities' =>
                'nullable|string',


            /*
            |--------------------------------------------------------------------------
            | Contract Summary
            |--------------------------------------------------------------------------
            */

            'contract_value' =>
                'nullable|numeric|min:0',

            'currency' =>
                'nullable|string|max:10',


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status' =>
                'required|string|max:50',

            'remarks' =>
                'nullable|string',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Project Consultant
    |--------------------------------------------------------------------------
    */

    protected function validateProjectConsultant(
        Project $project,
        ProjectConsultant $consultant
    ): void {

        if (
            (int) $consultant->project_id
            !==
            (int) $project->id
        ) {

            abort(
                404,
                'Consultant does not belong to this project.'
            );
        }
    }
}