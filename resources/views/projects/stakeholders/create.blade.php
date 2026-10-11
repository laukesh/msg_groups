@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project / Stakeholder Register
            </div>

            <h3 class="mb-1">
                Add Stakeholder
            </h3>

            <div class="text-muted">
                {{ $project->project_name }}

                @if($project->project_number)
                    · {{ $project->project_number }}
                @endif
            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.stakeholders.index',
                ['project' => $project->id]
            ) }}"
            class="btn btn-outline-secondary"
        >
            ← Stakeholder Register
        </a>

    </div>


    {{-- Validation Errors --}}

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


    <form
        method="POST"
        action="{{ route(
            'admin.projects.stakeholders.store',
            ['project' => $project->id]
        ) }}"
    >

        @csrf


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
                            name="stakeholder_number"
                            class="form-control"
                            value="{{ old(
                                'stakeholder_number',
                                $stakeholderNumber
                            ) }}"
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
                                'stakeholder_name'
                            ) }}"
                            placeholder="Enter stakeholder name"
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

                            <option value="">
                                Select Type
                            </option>


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
                                            'stakeholder_type'
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
                                'organization_name'
                            ) }}"
                            placeholder="Company / Department / Organization"
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
                            value="{{ old('role') }}"
                            placeholder="Stakeholder role"
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
                                'contact_person'
                            ) }}"
                            placeholder="Primary contact person"
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
                            value="{{ old('email') }}"
                            placeholder="email@example.com"
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
                            value="{{ old('phone') }}"
                            placeholder="Phone number"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- INFLUENCE / INTEREST --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Stakeholder Assessment
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Influence --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Influence Level

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <select
                            name="influence_level"
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
                                            'Medium'
                                        ) === $level
                                    )
                                >
                                    {{ $level }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Interest --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Interest Level

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <select
                            name="interest_level"
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
                                            'Medium'
                                        ) === $level
                                    )
                                >
                                    {{ $level }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Engagement --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Engagement Level

                            <span class="text-danger">
                                *
                            </span>

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
                                            'Neutral'
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

                            <span class="text-danger">
                                *
                            </span>

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
                                            'Medium'
                                        ) === $priority
                                    )
                                >
                                    {{ $priority }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Owner --}}

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
                                            'stakeholder_owner_id'
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
                                        'Active'
                                    ) === 'Active'
                                )
                            >
                                Active
                            </option>

                            <option
                                value="Inactive"
                                @selected(
                                    old(
                                        'status'
                                    ) === 'Inactive'
                                )
                            >
                                Inactive
                            </option>

                        </select>

                    </div>

                </div>


                {{-- Matrix Preview --}}

                <div
                    id="matrix-preview"
                    class="alert alert-light border mb-0"
                >

                    <strong>
                        Engagement Approach:
                    </strong>

                    <span id="matrix-message">
                        Select influence and interest levels.
                    </span>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- NEEDS / EXPECTATIONS --}}
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
                            placeholder="What does this stakeholder need from the project?"
                        >{{ old(
                            'stakeholder_needs'
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
                            placeholder="What does the stakeholder expect?"
                        >{{ old(
                            'expectations'
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
                            placeholder="What concerns or issues may they have?"
                        >{{ old(
                            'concerns'
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

                <div class="mb-3">

                    <label class="form-label">
                        Engagement Strategy
                    </label>

                    <textarea
                        name="engagement_strategy"
                        rows="6"
                        class="form-control"
                        placeholder="How will this stakeholder be managed and engaged?"
                    >{{ old(
                        'engagement_strategy'
                    ) }}</textarea>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Communication Requirements
                    </label>

                    <textarea
                        name="communication_requirements"
                        rows="5"
                        class="form-control"
                        placeholder="What information, reports or communications are required?"
                    >{{ old(
                        'communication_requirements'
                    ) }}</textarea>

                </div>


                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Communication Frequency
                            <span class="text-danger">
                                *
                            </span>
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
                                            'Monthly'
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
                >{{ old('remarks') }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- ACTIONS --}}
        {{-- ===================================================== --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route(
                    'admin.projects.stakeholders.index',
                    ['project' => $project->id]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Cancel
            </a>


            <button
                type="submit"
                class="btn btn-primary"
            >
                Save Stakeholder
            </button>

        </div>

    </form>

</div>


{{-- ============================================================= --}}
{{-- INFLUENCE / INTEREST JAVASCRIPT --}}
{{-- ============================================================= --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const influence =
            document.querySelector(
                '[name="influence_level"]'
            );

        const interest =
            document.querySelector(
                '[name="interest_level"]'
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


            if (
                !influenceValue ||
                !interestValue
            ) {

                message.textContent =
                    'Select influence and interest levels.';

                return;
            }


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


            let result;


            if (
                highInfluence &&
                highInterest
            ) {

                result =
                    'Manage Closely — maintain active engagement, frequent communication and direct attention.';

            } else if (
                highInfluence &&
                !highInterest
            ) {

                result =
                    'Keep Satisfied — maintain appropriate communication and monitor expectations.';

            } else if (
                !highInfluence &&
                highInterest
            ) {

                result =
                    'Keep Informed — provide relevant project information and maintain engagement.';

            } else {

                result =
                    'Monitor — maintain basic communication and review periodically.';

            }


            message.textContent =
                result;
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