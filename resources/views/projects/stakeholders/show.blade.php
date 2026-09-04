@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ===================================================== --}}
    {{-- HEADER --}}
    {{-- ===================================================== --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project / Stakeholder Register
            </div>

            <h3 class="mb-1">
                {{ $stakeholder->stakeholder_name }}
            </h3>

            <div class="text-muted">

                {{ $stakeholder->stakeholder_number }}

                ·

                {{ $project->project_name }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.stakeholders.index',
                    [
                        'project' => $project->id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Stakeholder Register
            </a>


            <a
                href="{{ route(
                    'admin.projects.stakeholders.edit',
                    [
                        'project' => $project->id,
                        'stakeholder' => $stakeholder->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                Edit
            </a>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- SUCCESS MESSAGE --}}
    {{-- ===================================================== --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- ===================================================== --}}
    {{-- STATUS / PRIORITY SUMMARY --}}
    {{-- ===================================================== --}}

    @php

        $priorityClass =
            match($stakeholder->priority) {

                'Critical'
                    => 'bg-danger',

                'High'
                    => 'bg-warning text-dark',

                'Medium'
                    => 'bg-info text-dark',

                default
                    => 'bg-success',

            };


        $statusClass =
            $stakeholder->status === 'Active'
                ? 'bg-success'
                : 'bg-secondary';


        $highInfluence =
            in_array(
                $stakeholder->influence_level,
                ['High', 'Very High']
            );


        $highInterest =
            in_array(
                $stakeholder->interest_level,
                ['High', 'Very High']
            );


        if (
            $highInfluence &&
            $highInterest
        ) {

            $managementApproach =
                'Manage Closely';

        } elseif (
            $highInfluence &&
            !$highInterest
        ) {

            $managementApproach =
                'Keep Satisfied';

        } elseif (
            !$highInfluence &&
            $highInterest
        ) {

            $managementApproach =
                'Keep Informed';

        } else {

            $managementApproach =
                'Monitor';

        }

    @endphp


    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Priority
                    </div>

                    <div class="mt-2">

                        <span
                            class="badge {{ $priorityClass }} fs-6"
                        >
                            {{ $stakeholder->priority }}
                        </span>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Influence
                    </div>

                    <div class="fw-semibold fs-5 mt-1">
                        {{ $stakeholder->influence_level }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Interest
                    </div>

                    <div class="fw-semibold fs-5 mt-1">
                        {{ $stakeholder->interest_level }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Status
                    </div>

                    <div class="mt-2">

                        <span
                            class="badge {{ $statusClass }} fs-6"
                        >
                            {{ $stakeholder->status }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


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

                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Stakeholder Number
                    </div>

                    <div class="fw-semibold">
                        {{ $stakeholder->stakeholder_number }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Stakeholder Name
                    </div>

                    <div class="fw-semibold">
                        {{ $stakeholder->stakeholder_name }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Stakeholder Type
                    </div>

                    <div class="fw-semibold">
                        {{ $stakeholder->stakeholder_type }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Organization
                    </div>

                    <div class="fw-semibold">
                        {{ $stakeholder->organization_name ?? '—' }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Role
                    </div>

                    <div class="fw-semibold">
                        {{ $stakeholder->role ?? '—' }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Stakeholder Owner
                    </div>

                    <div class="fw-semibold">

                        @if($stakeholder->stakeholderOwner)

                            {{ $stakeholder->stakeholderOwner->name }}

                        @else

                            Unassigned

                        @endif

                    </div>

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

                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Contact Person
                    </div>

                    <div class="fw-semibold">
                        {{ $stakeholder->contact_person ?? '—' }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Email
                    </div>

                    <div class="fw-semibold">

                        @if($stakeholder->email)

                            <a
                                href="mailto:{{ $stakeholder->email }}"
                            >
                                {{ $stakeholder->email }}
                            </a>

                        @else

                            —

                        @endif

                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <div class="text-muted small">
                        Phone
                    </div>

                    <div class="fw-semibold">
                        {{ $stakeholder->phone ?? '—' }}
                    </div>

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

                <div class="col-md-3 mb-3">

                    <div class="text-muted small">
                        Influence Level
                    </div>

                    <div class="fw-semibold">
                        {{ $stakeholder->influence_level }}
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <div class="text-muted small">
                        Interest Level
                    </div>

                    <div class="fw-semibold">
                        {{ $stakeholder->interest_level }}
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <div class="text-muted small">
                        Engagement Level
                    </div>

                    <div class="fw-semibold">
                        {{ $stakeholder->engagement_level }}
                    </div>

                </div>


                <div class="col-md-3 mb-3">

                    <div class="text-muted small">
                        Management Approach
                    </div>

                    <div class="fw-semibold">
                        {{ $managementApproach }}
                    </div>

                </div>

            </div>


            {{-- Matrix explanation --}}

            <div class="alert alert-light border mb-0">

                @if($managementApproach === 'Manage Closely')

                    <strong>Manage Closely:</strong>

                    This stakeholder has high influence and high
                    interest. Maintain active engagement,
                    frequent communication and direct attention.

                @elseif($managementApproach === 'Keep Satisfied')

                    <strong>Keep Satisfied:</strong>

                    This stakeholder has high influence but lower
                    interest. Maintain appropriate communication
                    and ensure expectations are managed.

                @elseif($managementApproach === 'Keep Informed')

                    <strong>Keep Informed:</strong>

                    This stakeholder has lower influence but high
                    interest. Provide relevant project information
                    and maintain engagement.

                @else

                    <strong>Monitor:</strong>

                    Maintain basic communication and review the
                    stakeholder periodically.

                @endif

            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- NEEDS / EXPECTATIONS / CONCERNS --}}
    {{-- ===================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Needs, Expectations & Concerns
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <div class="text-muted small mb-2">
                        Stakeholder Needs
                    </div>

                    <div>
                        {!! nl2br(
                            e(
                                $stakeholder->stakeholder_needs
                                ?? 'No information provided.'
                            )
                        ) !!}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <div class="text-muted small mb-2">
                        Expectations
                    </div>

                    <div>
                        {!! nl2br(
                            e(
                                $stakeholder->expectations
                                ?? 'No information provided.'
                            )
                        ) !!}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <div class="text-muted small mb-2">
                        Concerns
                    </div>

                    <div>
                        {!! nl2br(
                            e(
                                $stakeholder->concerns
                                ?? 'No information provided.'
                            )
                        ) !!}
                    </div>

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
                Engagement & Communication
            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-8 mb-4">

                    <div class="text-muted small mb-2">
                        Engagement Strategy
                    </div>

                    <div>
                        {!! nl2br(
                            e(
                                $stakeholder->engagement_strategy
                                ?? 'No strategy defined.'
                            )
                        ) !!}
                    </div>

                </div>


                <div class="col-md-4 mb-4">

                    <div class="text-muted small mb-2">
                        Communication Frequency
                    </div>

                    <div class="fw-semibold">
                        {{ $stakeholder->communication_frequency }}
                    </div>

                </div>


                <div class="col-md-8">

                    <div class="text-muted small mb-2">
                        Communication Requirements
                    </div>

                    <div>
                        {!! nl2br(
                            e(
                                $stakeholder
                                    ->communication_requirements
                                ?? 'No requirements defined.'
                            )
                        ) !!}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- REMARKS --}}
    {{-- ===================================================== --}}

    @if($stakeholder->remarks)

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Remarks
                </strong>

            </div>


            <div class="card-body">

                {!! nl2br(
                    e(
                        $stakeholder->remarks
                    )
                ) !!}

            </div>

        </div>

    @endif


    {{-- ===================================================== --}}
    {{-- STATUS ACTION --}}
    {{-- ===================================================== --}}

    <div class="card mb-5">

        <div class="card-header">

            <strong>
                Stakeholder Status
            </strong>

        </div>


        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    Current Status:

                    <span
                        class="badge {{ $statusClass }}"
                    >
                        {{ $stakeholder->status }}
                    </span>

                </div>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.stakeholders.status',
                        [
                            'project' => $project->id,
                            'stakeholder' => $stakeholder->id,
                        ]
                    ) }}"
                >

                    @csrf


                    @if($stakeholder->status === 'Active')

                        <input
                            type="hidden"
                            name="status"
                            value="Inactive"
                        >

                        <button
                            type="submit"
                            class="btn btn-outline-secondary"
                        >
                            Mark Inactive
                        </button>

                    @else

                        <input
                            type="hidden"
                            name="status"
                            value="Active"
                        >

                        <button
                            type="submit"
                            class="btn btn-success"
                        >
                            Mark Active
                        </button>

                    @endif

                </form>

            </div>

        </div>

    </div>

</div>

@endsection