@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>
            <h4 class="mb-1">
                Construction Submittals
            </h4>

            <div class="text-muted">
                Project: {{ $project->name ?? $project->project_name ?? 'Project' }}
            </div>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.construction.dashboard',
                    $project
                ) }}"
                class="btn btn-outline-secondary"
            >
                Construction Dashboard
            </a>
            
            <a
                href="{{ route(
                    'admin.projects.construction.submittals.create',
                    $project
                ) }}"
                class="btn btn-primary"
            >
                <i class="bi bi-plus-lg"></i>
                New Submittal
            </a>
            
        </div>

        

    </div>


    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3 col-lg">
            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total
                    </div>

                    <div class="fs-3 fw-bold">
                        {{ $summary['total'] }}
                    </div>

                </div>

            </div>
        </div>


        <div class="col-md-3 col-lg">
            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Draft
                    </div>

                    <div class="fs-3 fw-bold">
                        {{ $summary['draft'] }}
                    </div>

                </div>

            </div>
        </div>


        <div class="col-md-3 col-lg">
            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Submitted
                    </div>

                    <div class="fs-3 fw-bold">
                        {{ $summary['submitted'] }}
                    </div>

                </div>

            </div>
        </div>


        <div class="col-md-3 col-lg">
            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Under Review
                    </div>

                    <div class="fs-3 fw-bold">
                        {{ $summary['under_review'] }}
                    </div>

                </div>

            </div>
        </div>


        <div class="col-md-3 col-lg">
            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Approved
                    </div>

                    <div class="fs-3 fw-bold text-success">
                        {{ $summary['approved'] }}
                    </div>

                </div>

            </div>
        </div>


        <div class="col-md-3 col-lg">
            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Revise
                    </div>

                    <div class="fs-3 fw-bold text-warning">
                        {{ $summary['revise'] }}
                    </div>

                </div>

            </div>
        </div>


        <div class="col-md-3 col-lg">
            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Rejected
                    </div>

                    <div class="fs-3 fw-bold text-danger">
                        {{ $summary['rejected'] }}
                    </div>

                </div>

            </div>
        </div>


        <div class="col-md-3 col-lg">
            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Overdue
                    </div>

                    <div class="fs-3 fw-bold text-danger">
                        {{ $summary['overdue'] }}
                    </div>

                </div>

            </div>
        </div>

    </div>


    {{-- Flash Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Submittals Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    Submittal Register
                </h5>

                <span class="text-muted small">
                    {{ $submittals->count() }} records
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                Submittal
                            </th>

                            <th>
                                Title
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Contractor
                            </th>

                            <th>
                                Consultant
                            </th>

                            <th>
                                Submission Date
                            </th>

                            <th>
                                Review Due
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($submittals as $submittal)

                            <tr>

                                {{-- ID --}}
                                <td>
                                    {{ $submittal->id }}
                                </td>


                                {{-- Number --}}
                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.submittals.show',
                                            [
                                                'project' => $project,
                                                'submittal' => $submittal,
                                            ]
                                        ) }}"
                                        class="fw-semibold text-decoration-none"
                                    >

                                        {{ $submittal->submittal_number }}

                                    </a>

                                    <div class="small text-muted">

                                        {{ optional(
                                            $submittal->submittal_date
                                        )->format('d M Y') }}

                                    </div>

                                </td>


                                {{-- Title --}}
                                <td>

                                    <div class="fw-semibold">
                                        {{ $submittal->title }}
                                    </div>

                                    @if($submittal->location)

                                        <div class="small text-muted">

                                            {{ $submittal->location }}

                                        </div>

                                    @endif

                                </td>


                                {{-- Type --}}
                                <td>

                                    {{ $submittal->submittal_type ?? '—' }}

                                </td>


                                {{-- Contractor --}}
                                <td>

                                    @if($submittal->contract?->bidder)

                                        {{ $submittal->contract->bidder->company_name }}

                                    @elseif($submittal->contract?->bidder_name)

                                        {{ $submittal->contract->bidder_name }}

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- Consultant --}}
                                <td>

                                    @if($submittal->consultant)

                                        <div>
                                            {{ $submittal->consultant->company_name }}
                                        </div>

                                        @if($submittal->consultant->consultant_name)

                                            <div class="small text-muted">

                                                {{ $submittal->consultant->consultant_name }}

                                            </div>

                                        @endif

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- Submission Date --}}
                                <td>

                                    {{ optional(
                                        $submittal->submission_date
                                    )->format('d M Y') ?? '—' }}

                                </td>


                                {{-- Review Due --}}
                                <td>

                                    @if($submittal->review_due_date)

                                        @php
                                            $isOverdue =
                                                $submittal->review_due_date->isPast()
                                                &&
                                                !in_array(
                                                    $submittal->status,
                                                    [
                                                        'Approved',
                                                        'Rejected',
                                                        'Closed',
                                                        'Cancelled'
                                                    ],
                                                    true
                                                );
                                        @endphp

                                        <span
                                            class="{{ $isOverdue ? 'text-danger fw-bold' : '' }}"
                                        >

                                            {{ $submittal->review_due_date->format('d M Y') }}

                                        </span>

                                        @if($isOverdue)

                                            <div class="small text-danger">
                                                Overdue
                                            </div>

                                        @endif

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td>

                                    @php

                                        $statusClass = match(
                                            $submittal->status
                                        ) {

                                            'Approved' =>
                                                'bg-success',

                                            'Approved With Comments' =>
                                                'bg-info text-dark',

                                            'Submitted' =>
                                                'bg-primary',

                                            'Under Review' =>
                                                'bg-warning text-dark',

                                            'Revise & Resubmit' =>
                                                'bg-warning text-dark',

                                            'Rejected' =>
                                                'bg-danger',

                                            'Closed' =>
                                                'bg-secondary',

                                            'Cancelled' =>
                                                'bg-dark',

                                            default =>
                                                'bg-light text-dark',
                                        };

                                    @endphp


                                    <span
                                        class="badge {{ $statusClass }}"
                                    >

                                        {{ $submittal->status }}

                                    </span>

                                </td>


                                {{-- Action --}}
                                <td class="text-end">

                                    <div class="dropdown">

                                        <button
                                            class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                            type="button"
                                            data-bs-toggle="dropdown"
                                        >
                                            Action
                                        </button>


                                        <ul class="dropdown-menu dropdown-menu-end">

                                            <li>

                                                <a
                                                    class="dropdown-item"
                                                    href="{{ route(
                                                        'admin.projects.construction.submittals.show',
                                                        [
                                                            'project' => $project,
                                                            'submittal' => $submittal,
                                                        ]
                                                    ) }}"
                                                >
                                                    View
                                                </a>

                                            </li>


                                            @if(
                                                in_array(
                                                    $submittal->status,
                                                    [
                                                        'Draft',
                                                        'Revise & Resubmit',
                                                    ],
                                                    true
                                                )
                                            )

                                                <li>

                                                    <a
                                                        class="dropdown-item"
                                                        href="{{ route(
                                                            'admin.projects.construction.submittals.edit',
                                                            [
                                                                'project' => $project,
                                                                'submittal' => $submittal,
                                                            ]
                                                        ) }}"
                                                    >
                                                        Edit
                                                    </a>

                                                </li>


                                                <li>

                                                    <form
                                                        method="POST"
                                                        action="{{ route(
                                                            'admin.projects.construction.submittals.destroy',
                                                            [
                                                                'project' => $project,
                                                                'submittal' => $submittal,
                                                            ]
                                                        ) }}"
                                                        onsubmit="return confirm('Are you sure you want to delete this submittal?');"
                                                    >

                                                        @csrf

                                                        @method('DELETE')

                                                        <button
                                                            type="submit"
                                                            class="dropdown-item text-danger"
                                                        >
                                                            Delete
                                                        </button>

                                                    </form>

                                                </li>

                                            @endif

                                        </ul>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="10"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted">

                                        No submittals found for this project.

                                    </div>

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.submittals.create',
                                            $project
                                        ) }}"
                                        class="btn btn-primary btn-sm mt-3"
                                    >
                                        Create First Submittal
                                    </a>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection