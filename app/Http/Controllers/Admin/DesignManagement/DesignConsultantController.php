<?php

namespace App\Http\Controllers\Admin\DesignManagement;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectConsultant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DesignConsultantController extends Controller
{
    /**
     * Consultant List
     */
    public function index(Project $project): View
    {
        $consultants = ProjectConsultant::query()
            ->where('project_id', $project->id)
            ->orderBy('company_name')
            ->get();

        $summary = [
            'total' => $consultants->count(),
            'active' => $consultants->where('status', 'Active')->count(),
            'completed' => $consultants->where('status', 'Completed')->count(),
            'total_value' => (float) $consultants->sum('contract_value'),
        ];

        return view(
            'design-management.consultants.index',
            compact('project', 'consultants', 'summary')
        );
    }


    /**
     * Create Consultant
     */
    public function create(Project $project): View
    {
        return view('design-management.consultants.create', [
            'project' => $project,
            'consultant' => new ProjectConsultant(),
        ]);
    }


    /**
     * Store Consultant
     */
    public function store(
        Request $request,
        Project $project
    ): RedirectResponse {

        $validated = $this->validateConsultant($request);

        $validated['project_id'] = $project->id;

        $validated['country'] = $validated['country'] ?? 'India';

        $validated['currency'] = $validated['currency'] ?? 'INR';

        $validated['contract_value'] =
            $validated['contract_value'] ?? 0;

        $validated['created_by'] = auth()->id();

        $validated['updated_by'] = auth()->id();

        $consultant = ProjectConsultant::create($validated);

        /*
         * System generated consultant code
         */
        $consultant->update([
            'consultant_code' =>
                'CONS-' .
                str_pad(
                    (string) $consultant->id,
                    6,
                    '0',
                    STR_PAD_LEFT
                ),
        ]);

        return redirect()
            ->route(
                'admin.projects.design-management.consultants.index',
                $project
            )
            ->with(
                'success',
                'Consultant added successfully.'
            );
    }


    /**
     * Show Consultant
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
            'design-management.consultants.show',
            compact('project', 'consultant')
        );
    }


    /**
     * Edit Consultant
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
            'design-management.consultants.edit',
            compact('project', 'consultant')
        );
    }


    /**
     * Update Consultant
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

        $validated = $this->validateConsultant($request);

        $validated['country'] =
            $validated['country'] ?? 'India';

        $validated['currency'] =
            $validated['currency'] ?? 'INR';

        $validated['contract_value'] =
            $validated['contract_value'] ?? 0;

        $validated['updated_by'] =
            auth()->id();

        $consultant->update($validated);

        return redirect()
            ->route(
                'admin.projects.design-management.consultants.index',
                $project
            )
            ->with(
                'success',
                'Consultant updated successfully.'
            );
    }


    /**
     * Delete Consultant
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
                'admin.projects.design-management.consultants.index',
                $project
            )
            ->with(
                'success',
                'Consultant deleted successfully.'
            );
    }


    /**
     * Validation
     */
    protected function validateConsultant(
        Request $request
    ): array {

        return $request->validate([

            /*
             * Consultant Information
             */
            'consultant_type' =>
                'nullable|string|max:100',

            'consultant_role' =>
                'nullable|string|max:100',

            'discipline' =>
                'nullable|string|max:100',

            'appointment_type' =>
                'nullable|string|max:100',

            'specialization' =>
                'nullable|string|max:255',

            'company_name' =>
                'required|string|max:255',

            'consultant_name' =>
                'nullable|string|max:255',


            /*
             * Professional Information
             */
            'registration_no' =>
                'nullable|string|max:150',

            'gst_number' =>
                'nullable|string|max:50',

            'pan_number' =>
                'nullable|string|max:50',


            /*
             * Contact Information
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
             * Address
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
             * Appointment
             */
            'appointment_date' =>
                'nullable|date',

            'start_date' =>
                'nullable|date',

            'end_date' =>
                'nullable|date|after_or_equal:start_date',


            /*
             * Scope
             */
            'scope_of_services' =>
                'nullable|string',

            'responsibilities' =>
                'nullable|string',


            /*
             * Contract
             */
            'contract_value' =>
                'nullable|numeric|min:0',

            'currency' =>
                'nullable|string|max:10',


            /*
             * Status
             */
            'status' =>
                'required|string|max:50',

            /*
             * Remarks
             */
            'remarks' =>
                'nullable|string',
        ]);
    }


    /**
     * Make sure consultant belongs to project
     */
    protected function validateProjectConsultant(
        Project $project,
        ProjectConsultant $consultant
    ): void {

        if (
            (int) $consultant->project_id !==
            (int) $project->id
        ) {
            abort(404);
        }
    }
}