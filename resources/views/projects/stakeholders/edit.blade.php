@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project / Stakeholder Register
            </div>

            <h3 class="mb-1">
                Edit Stakeholder
            </h3>

            <div class="text-muted">

                {{ $project->project_name }}

                @if($project->project_number)
                    · {{ $project->project_number }}
                @endif

                · {{ $stakeholder->stakeholder_number }}

            </div>

        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.stakeholders.show',
                    [
                        'project' => $project->id,
                        'stakeholder' => $stakeholder->id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Back
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- VALIDATION ERRORS --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- UPDATE FORM --}}
    {{-- ========================================================= --}}

    <form
        method="POST"
        action="{{ route(
            'admin.projects.stakeholders.update',
            [
                'project' => $project->id,
                'stakeholder' => $stakeholder->id,
            ]
        ) }}"
    >

        @csrf
        @method('PUT')


        {{-- ===================================================== --}}
        {{-- BASIC INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>
                    Basic Information
                </strong>
            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Stakeholder Number --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Stakeholder Number
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $stakeholder->stakeholder_number }}"
                            readonly
                        >

                    </div>


                    {{-- Stakeholder Name --}}

                    <div class="col-md-8 mb-3">

                        <label class="form-label">

                            Stakeholder Name

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="stakeholder_name"
                            class="form-control"
                            value="{{ old(
                                'stakeholder_name',
                                $stakeholder->stakeholder_name
                            ) }}"
                            required
                        >

                    </div>

                </div>


                <div class="row">

                    {{-- Stakeholder Type --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Stakeholder Type

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="stakeholder_type"
                            class="form-select"
                            required
                        >

                            @foreach([
                                'Internal',
                                'External',
                                'Government',
                                'Regulatory',
                                'Investor',
                                'Lender',
                                'Landowner',
                                'Customer',
                                'Community',
                                'Contractor',
                                'Consultant',
                                'Supplier',
                                'Partner',
                                'Other',
                            ] as $type)

                                <option
                                    value="{{ $type }}"
                                    @selected(
                                        old(
                                            'stakeholder_type',
                                            $stakeholder->stakeholder_type
                                        ) === $type
                                    )
                                >
                                    {{ $type }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Organization --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Organization
                        </label>

                        <input
                            type="text"
                            name="organization_name"
                            class="form-control"
                            value="{{ old(
                                'organization_name',
                                $stakeholder->organization_name
                            ) }}"
                        >

                    </div>


                    {{-- Role --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Role
                        </label>

                        <input
                            type="text"
                            name="role"
                            class="form-control"
                            value="{{ old(
                                'role',
                                $stakeholder->role
                            ) }}"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- CONTACT INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Contact Information
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Contact Person --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Contact Person
                        </label>

                        <input
                            type="text"
                            name="contact_person"
                            class="form-control"
                            value="{{ old(
                                'contact_person',
                                $stakeholder->contact_person
                            ) }}"
                        >

                    </div>


                    {{-- Email --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="{{ old(
                                'email',
                                $stakeholder->email
                            ) }}"
                        >

                    </div>


                    {{-- Phone --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Phone
                        </label>

                        <input
                            type="text"
                            name="phone"
                            class="form-control"
                            value="{{ old(
                                'phone',
                                $stakeholder->phone
                            ) }}"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- STAKEHOLDER ASSESSMENT --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Stakeholder Assessment
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Influence Level --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Influence Level

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="influence_level"
                            id="influence_level"
                            class="form-select"
                            required
                        >

                            @foreach([
                                'Very Low',
                                'Low',
                                'Medium',
                                'High',
                                'Very High',
                            ] as $level)

                                <option
                                    value="{{ $level }}"
                                    @selected(
                                        old(
                                            'influence_level',
                                            $stakeholder->influence_level
                                        ) === $level
                                    )
                                >
                                    {{ $level }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Interest Level --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Interest Level

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="interest_level"
                            id="interest_level"
                            class="form-select"
                            required
                        >

                            @foreach([
                                'Very Low',
                                'Low',
                                'Medium',
                                'High',
                                'Very High',
                            ] as $level)

                                <option
                                    value="{{ $level }}"
                                    @selected(
                                        old(
                                            'interest_level',
                                            $stakeholder->interest_level
                                        ) === $level
                                    )
                                >
                                    {{ $level }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Engagement Level --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Engagement Level

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="engagement_level"
                            class="form-select"
                            required
                        >

                            @foreach([
                                'Unaware',
                                'Resistant',
                                'Neutral',
                                'Supportive',
                                'Leading',
                            ] as $level)

                                <option
                                    value="{{ $level }}"
                                    @selected(
                                        old(
                                            'engagement_level',
                                            $stakeholder->engagement_level
                                        ) === $level
                                    )
                                >
                                    {{ $level }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                <div class="row">

                    {{-- Priority --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Priority

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="priority"
                            class="form-select"
                            required
                        >

                            @foreach([
                                'Low',
                                'Medium',
                                'High',
                                'Critical',
                            ] as $priority)

                                <option
                                    value="{{ $priority }}"
                                    @selected(
                                        old(
                                            'priority',
                                            $stakeholder->priority
                                        ) === $priority
                                    )
                                >
                                    {{ $priority }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Stakeholder Owner --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Stakeholder Owner
                        </label>

                        <select
                            name="stakeholder_owner_id"
                            class="form-select"
                        >

                            <option value="">
                                Unassigned
                            </option>


                            @foreach($stakeholderOwners as $owner)

                                <option
                                    value="{{ $owner->id }}"
                                    @selected(
                                        old(
                                            'stakeholder_owner_id',
                                            $stakeholder->stakeholder_owner_id
                                        ) == $owner->id
                                    )
                                >
                                    {{ $owner->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Status --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option
                                value="Active"
                                @selected(
                                    old(
                                        'status',
                                        $stakeholder->status
                                    ) === 'Active'
                                )
                            >
                                Active
                            </option>

                            <option
                                value="Inactive"
                                @selected(
                                    old(
                                        'status',
                                        $stakeholder->status
                                    ) === 'Inactive'
                                )
                            >
                                Inactive
                            </option>

                        </select>

                    </div>

                </div>


                {{-- Engagement Approach Preview --}}

                <div
                    id="matrix-preview"
                    class="alert alert-light border mb-0"
                >

                    <strong>
                        Engagement Approach:
                    </strong>

                    <span id="matrix-message"></span>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- NEEDS / EXPECTATIONS / CONCERNS --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Stakeholder Needs & Expectations
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Needs --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Stakeholder Needs
                        </label>

                        <textarea
                            name="stakeholder_needs"
                            rows="6"
                            class="form-control"
                        >{{ old(
                            'stakeholder_needs',
                            $stakeholder->stakeholder_needs
                        ) }}</textarea>

                    </div>


                    {{-- Expectations --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Expectations
                        </label>

                        <textarea
                            name="expectations"
                            rows="6"
                            class="form-control"
                        >{{ old(
                            'expectations',
                            $stakeholder->expectations
                        ) }}</textarea>

                    </div>


                    {{-- Concerns --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Concerns
                        </label>

                        <textarea
                            name="concerns"
                            rows="6"
                            class="form-control"
                        >{{ old(
                            'concerns',
                            $stakeholder->concerns
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- ENGAGEMENT STRATEGY --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Engagement Strategy
                </strong>

            </div>


            <div class="card-body">

                {{-- Strategy --}}

                <div class="mb-3">

                    <label class="form-label">
                        Engagement Strategy
                    </label>

                    <textarea
                        name="engagement_strategy"
                        rows="6"
                        class="form-control"
                    >{{ old(
                        'engagement_strategy',
                        $stakeholder->engagement_strategy
                    ) }}</textarea>

                </div>


                {{-- Communication Requirements --}}

                <div class="mb-3">

                    <label class="form-label">
                        Communication Requirements
                    </label>

                    <textarea
                        name="communication_requirements"
                        rows="5"
                        class="form-control"
                    >{{ old(
                        'communication_requirements',
                        $stakeholder->communication_requirements
                    ) }}</textarea>

                </div>


                {{-- Communication Frequency --}}

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Communication Frequency

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="communication_frequency"
                            class="form-select"
                            required
                        >

                            @foreach([
                                'As Required',
                                'Weekly',
                                'Fortnightly',
                                'Monthly',
                                'Quarterly',
                            ] as $frequency)

                                <option
                                    value="{{ $frequency }}"
                                    @selected(
                                        old(
                                            'communication_frequency',
                                            $stakeholder->communication_frequency
                                        ) === $frequency
                                    )
                                >
                                    {{ $frequency }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- REMARKS --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Remarks
                </strong>

            </div>

            <div class="card-body">

                <textarea
                    name="remarks"
                    rows="5"
                    class="form-control"
                    placeholder="Additional notes..."
                >{{ old(
                    'remarks',
                    $stakeholder->remarks
                ) }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- UPDATE ACTIONS --}}
        {{-- ===================================================== --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route(
                    'admin.projects.stakeholders.show',
                    [
                        'project' => $project->id,
                        'stakeholder' => $stakeholder->id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Cancel
            </a>


            <button
                type="submit"
                class="btn btn-primary"
            >
                Save Changes
            </button>

        </div>

    </form>


    {{-- ========================================================= --}}
    {{-- DELETE FORM --}}
    {{-- IMPORTANT: OUTSIDE UPDATE FORM --}}
    {{-- ========================================================= --}}

    <div class="card border-danger mb-5">

        <div class="card-header text-danger">

            <strong>
                Danger Zone
            </strong>

        </div>


        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <div class="fw-semibold">
                        Delete Stakeholder
                    </div>

                    <div class="text-muted small">
                        This action cannot be undone.
                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.stakeholders.destroy',
                        [
                            'project' => $project->id,
                            'stakeholder' => $stakeholder->id,
                        ]
                    ) }}"
                    onsubmit="return confirm(
                        'Are you sure you want to delete this stakeholder?'
                    );"
                >

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-outline-danger"
                    >
                        Delete Stakeholder
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>


{{-- ============================================================= --}}
{{-- INFLUENCE × INTEREST JAVASCRIPT --}}
{{-- ============================================================= --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const influence =
            document.getElementById(
                'influence_level'
            );

        const interest =
            document.getElementById(
                'interest_level'
            );

        const message =
            document.getElementById(
                'matrix-message'
            );


        function updateMatrix()
        {
            const influenceValue =
                influence.value;

            const interestValue =
                interest.value;


            const highInfluence =
                [
                    'High',
                    'Very High'
                ].includes(
                    influenceValue
                );


            const highInterest =
                [
                    'High',
                    'Very High'
                ].includes(
                    interestValue
                );


            if (
                highInfluence &&
                highInterest
            ) {

                message.textContent =
                    'Manage Closely — maintain active engagement, frequent communication and direct attention.';

            } else if (
                highInfluence &&
                !highInterest
            ) {

                message.textContent =
                    'Keep Satisfied — maintain appropriate communication and monitor expectations.';

            } else if (
                !highInfluence &&
                highInterest
            ) {

                message.textContent =
                    'Keep Informed — provide relevant project information and maintain engagement.';

            } else {

                message.textContent =
                    'Monitor — maintain basic communication and review periodically.';

            }
        }


        influence.addEventListener(
            'change',
            updateMatrix
        );

        interest.addEventListener(
            'change',
            updateMatrix
        );


        updateMatrix();

    }
);

</script>

@endsection