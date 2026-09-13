@extends('layouts.app')

@section('content')

<style type="text/css">
    .bg-white{
        color: #000;
    }
</style>

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted mb-1">
                Consultant
            </div>

            <h4 class="mb-1">

                {{ $consultant->company_name }}

            </h4>

            <div class="text-muted">

                {{ $consultant->consultant_code ?? '—' }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.construction.consultants.edit',
                [$project, $consultant]
            ) }}"
               class="btn btn-primary">

                <i class="bi bi-pencil me-1"></i>
                Edit

            </a>


            <a href="{{ route(
                'admin.projects.construction.consultants.index',
                $project
            ) }}"
               class="btn btn-outline-secondary">

                Back

            </a>

        </div>

    </div>


    {{-- Status --}}
    <div class="mb-4">

        @php

            $statusClass = match ($consultant->status) {

                'Active' => 'success',

                'Completed' => 'secondary',

                'Pending' => 'warning',

                'On Hold',
                'Suspended' => 'warning',

                'Terminated',
                'Cancelled' => 'danger',

                default => 'secondary',

            };

        @endphp


        <span class="badge bg-{{ $statusClass }} px-3 py-2">

            {{ $consultant->status }}

        </span>

    </div>


    {{-- ========================================================= --}}
    {{-- Summary Cards --}}
    {{-- ========================================================= --}}

    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Consultant Type
                    </div>

                    <div class="fw-semibold mt-1">

                        {{ $consultant->consultant_type ?? '—' }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Role
                    </div>

                    <div class="fw-semibold mt-1">

                        {{ $consultant->consultant_role ?? '—' }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Discipline
                    </div>

                    <div class="fw-semibold mt-1">

                        {{ $consultant->discipline ?? '—' }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Contract Value
                    </div>

                    <div class="fw-semibold mt-1">

                        {{ $consultant->currency ?? 'USD' }}
                        {{ number_format((float) $consultant->contract_value, 2) }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Company / Professional --}}
    {{-- ========================================================= --}}

    <div class="row g-4 mb-4">

        <div class="col-lg-6">

            <div class="card shadow-sm h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Consultant Information
                    </h5>

                </div>


                <div class="card-body">

                    <dl class="row mb-0">

                        <dt class="col-sm-5">
                            Company
                        </dt>

                        <dd class="col-sm-7">
                            {{ $consultant->company_name }}
                        </dd>


                        <dt class="col-sm-5">
                            Consultant / Lead
                        </dt>

                        <dd class="col-sm-7">
                            {{ $consultant->consultant_name ?? '—' }}
                        </dd>


                        <dt class="col-sm-5">
                            Type
                        </dt>

                        <dd class="col-sm-7">
                            {{ $consultant->consultant_type ?? '—' }}
                        </dd>


                        <dt class="col-sm-5">
                            Role
                        </dt>

                        <dd class="col-sm-7">
                            {{ $consultant->consultant_role ?? '—' }}
                        </dd>


                        <dt class="col-sm-5">
                            Discipline
                        </dt>

                        <dd class="col-sm-7">
                            {{ $consultant->discipline ?? '—' }}
                        </dd>


                        <dt class="col-sm-5">
                            Specialization
                        </dt>

                        <dd class="col-sm-7">
                            {{ $consultant->specialization ?? '—' }}
                        </dd>

                    </dl>

                </div>

            </div>

        </div>


        <div class="col-lg-6">

            <div class="card shadow-sm h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Professional Information
                    </h5>

                </div>


                <div class="card-body">

                    <dl class="row mb-0">

                        <dt class="col-sm-5">
                            Registration No.
                        </dt>

                        <dd class="col-sm-7">
                            {{ $consultant->registration_no ?? '—' }}
                        </dd>


                        <dt class="col-sm-5">
                            GST Number
                        </dt>

                        <dd class="col-sm-7">
                            {{ $consultant->gst_number ?? '—' }}
                        </dd>


                        <dt class="col-sm-5">
                            PAN Number
                        </dt>

                        <dd class="col-sm-7">
                            {{ $consultant->pan_number ?? '—' }}
                        </dd>


                        <dt class="col-sm-5">
                            Appointment Type
                        </dt>

                        <dd class="col-sm-7">
                            {{ $consultant->appointment_type ?? '—' }}
                        </dd>

                    </dl>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Contact / Address --}}
    {{-- ========================================================= --}}

    <div class="row g-4 mb-4">

        <div class="col-lg-6">

            <div class="card shadow-sm h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Contact Information
                    </h5>

                </div>


                <div class="card-body">

                    <dl class="row mb-0">

                        <dt class="col-sm-5">
                            Contact Person
                        </dt>

                        <dd class="col-sm-7">
                            {{ $consultant->contact_person ?? '—' }}
                        </dd>


                        <dt class="col-sm-5">
                            Designation
                        </dt>

                        <dd class="col-sm-7">
                            {{ $consultant->contact_designation ?? '—' }}
                        </dd>


                        <dt class="col-sm-5">
                            Email
                        </dt>

                        <dd class="col-sm-7">
                            {{ $consultant->email ?? '—' }}
                        </dd>


                        <dt class="col-sm-5">
                            Phone
                        </dt>

                        <dd class="col-sm-7">
                            {{ $consultant->phone ?? '—' }}
                        </dd>


                        <dt class="col-sm-5">
                            Alternate Phone
                        </dt>

                        <dd class="col-sm-7">
                            {{ $consultant->alternate_phone ?? '—' }}
                        </dd>


                        <dt class="col-sm-5">
                            Website
                        </dt>

                        <dd class="col-sm-7">

                            @if($consultant->website)

                                <a href="{{ $consultant->website }}"
                                   target="_blank"
                                   rel="noopener">

                                    {{ $consultant->website }}

                                </a>

                            @else

                                —

                            @endif

                        </dd>

                    </dl>

                </div>

            </div>

        </div>


        <div class="col-lg-6">

            <div class="card shadow-sm h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Address
                    </h5>

                </div>


                <div class="card-body">

                    <address class="mb-0">

                        {{ $consultant->address ?? '—' }}

                        @if($consultant->city)
                            <br>{{ $consultant->city }}
                        @endif

                        @if($consultant->state)
                            <br>{{ $consultant->state }}
                        @endif

                        @if($consultant->postal_code)
                            - {{ $consultant->postal_code }}
                        @endif

                        @if($consultant->country)
                            <br>{{ $consultant->country }}
                        @endif

                    </address>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Appointment --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Project Appointment
            </h5>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-3">

                    <div class="text-muted small">
                        Appointment Date
                    </div>

                    <div class="fw-semibold">

                        {{ $consultant->appointment_date?->format('d M Y') ?? '—' }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Start Date
                    </div>

                    <div class="fw-semibold">

                        {{ $consultant->start_date?->format('d M Y') ?? '—' }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        End Date
                    </div>

                    <div class="fw-semibold">

                        {{ $consultant->end_date?->format('d M Y') ?? '—' }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Currency
                    </div>

                    <div class="fw-semibold">

                        {{ $consultant->currency ?? 'USD' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Scope --}}
    {{-- ========================================================= --}}

    <div class="row g-4 mb-4">

        <div class="col-lg-6">

            <div class="card shadow-sm h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Scope of Services
                    </h5>

                </div>

                <div class="card-body">

                    @if($consultant->scope_of_services)

                        <div style="white-space: pre-line;">
                            {{ $consultant->scope_of_services }}
                        </div>

                    @else

                        <span class="text-muted">
                            No scope information provided.
                        </span>

                    @endif

                </div>

            </div>

        </div>


        <div class="col-lg-6">

            <div class="card shadow-sm h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Responsibilities
                    </h5>

                </div>

                <div class="card-body">

                    @if($consultant->responsibilities)

                        <div style="white-space: pre-line;">
                            {{ $consultant->responsibilities }}
                        </div>

                    @else

                        <span class="text-muted">
                            No responsibilities provided.
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Contract --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Contract Summary
            </h5>

        </div>


        <div class="card-body">

            <div class="alert alert-info mb-0">

                <strong>Contract Value:</strong>

                {{ $consultant->currency ?? 'USD' }}
                {{ number_format((float) $consultant->contract_value, 2) }}

                <div class="small mt-1">

                    Detailed contractual information such as
                    security, retention, payments, variations,
                    claims and closeout should be managed through
                    the shared Contract Management module.

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Remarks --}}
    {{-- ========================================================= --}}

    @if($consultant->remarks)

        <div class="card shadow-sm mb-5">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Remarks
                </h5>

            </div>

            <div class="card-body">

                <div style="white-space: pre-line;">
                    {{ $consultant->remarks }}
                </div>

            </div>

        </div>

    @endif

</div>

@endsection