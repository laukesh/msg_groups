@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Page Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="text-muted mb-1">
                Design Management
            </div>

            <h4 class="mb-1">
                Consultants
            </h4>

            <div class="text-muted">

                Project:

                <strong>
                    {{ $project->project_name ?? '—' }}
                </strong>

                @if(!empty($project->project_code))

                    <span class="ms-2">
                        ({{ $project->project_code }})
                    </span>

                @endif

            </div>

        </div>


        <div class="d-flex gap-2">

            @include(
                'design-management.partials.dashboard-link'
            )


            <a
                href="{{ route(
                    'admin.projects.design-management.consultants.create',
                    $project
                ) }}"
                class="btn btn-primary"
            >

                <i class="bi bi-plus-lg me-1"></i>

                Add Consultant

            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Alerts --}}
    {{-- ========================================================= --}}

    @include('design-management.partials.alerts')


    {{-- ========================================================= --}}
    {{-- Summary Cards --}}
    {{-- ========================================================= --}}

    <div class="row g-3 mb-4">


        {{-- Total --}}
        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Consultants
                    </div>

                    <div class="fs-4 fw-semibold mt-1">
                        {{ $summary['total'] }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Active --}}
        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Active
                    </div>

                    <div class="fs-4 fw-semibold mt-1">
                        {{ $summary['active'] }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Completed --}}
        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Completed
                    </div>

                    <div class="fs-4 fw-semibold mt-1">
                        {{ $summary['completed'] }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Contract Value --}}
        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Contract Value
                    </div>

                    <div class="fs-5 fw-semibold mt-1">

                        ${{ number_format(
                            $summary['total_value'],
                            2
                        ) }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Consultants Table --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm">

        <div class="card-body p-0">

            @if($consultants->count() > 0)

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    Code
                                </th>

                                <th>
                                    Company / Consultant
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Role
                                </th>

                                <th>
                                    Discipline
                                </th>

                                <th>
                                    Contact
                                </th>

                                <th class="text-center">
                                    Status
                                </th>

                                <th class="text-end">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($consultants as $consultant)

                                @php

                                    $statusClass = match (
                                        $consultant->status
                                    ) {

                                        'Active' =>
                                            'success',

                                        'Completed' =>
                                            'secondary',

                                        'Pending' =>
                                            'warning',

                                        'On Hold',
                                        'Suspended' =>
                                            'warning',

                                        'Terminated',
                                        'Cancelled' =>
                                            'danger',

                                        default =>
                                            'secondary',
                                    };

                                @endphp


                                <tr>


                                    {{-- Code --}}
                                    <td>

                                        <span class="fw-semibold">

                                            {{ $consultant->consultant_code ?? '—' }}

                                        </span>

                                    </td>


                                    {{-- Company --}}
                                    <td>

                                        <div class="fw-semibold">

                                            {{ $consultant->company_name }}

                                        </div>

                                        @if($consultant->consultant_name)

                                            <div class="small text-muted">

                                                {{ $consultant->consultant_name }}

                                            </div>

                                        @endif

                                        @if($consultant->specialization)

                                            <div class="small text-muted">

                                                {{ $consultant->specialization }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Type --}}
                                    <td>

                                        {{ $consultant->consultant_type ?? '—' }}

                                    </td>


                                    {{-- Role --}}
                                    <td>

                                        {{ $consultant->consultant_role ?? '—' }}

                                    </td>


                                    {{-- Discipline --}}
                                    <td>

                                        {{ $consultant->discipline ?? '—' }}

                                    </td>


                                    {{-- Contact --}}
                                    <td>

                                        @if($consultant->contact_person)

                                            <div class="fw-semibold">

                                                {{ $consultant->contact_person }}

                                            </div>

                                        @endif


                                        @if($consultant->email)

                                            <div class="small">

                                                <i class="bi bi-envelope me-1"></i>

                                                {{ $consultant->email }}

                                            </div>

                                        @endif


                                        @if($consultant->phone)

                                            <div class="small">

                                                <i class="bi bi-telephone me-1"></i>

                                                {{ $consultant->phone }}

                                            </div>

                                        @endif


                                        @if(
                                            !$consultant->contact_person &&
                                            !$consultant->email &&
                                            !$consultant->phone
                                        )

                                            —

                                        @endif

                                    </td>


                                    {{-- Status --}}
                                    <td class="text-center">

                                        <span
                                            class="badge bg-{{ $statusClass }}"
                                        >

                                            {{ $consultant->status }}

                                        </span>

                                    </td>


                                    {{-- Actions --}}
                                    <td class="text-end">

                                        <div class="d-inline-flex gap-1">


                                            <a
                                                href="{{ route(
                                                    'admin.projects.design-management.consultants.show',
                                                    [$project, $consultant]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                                title="View"
                                            >

                                                <i class="fa fa-eye"></i>

                                            </a>


                                            <a
                                                href="{{ route(
                                                    'admin.projects.design-management.consultants.edit',
                                                    [$project, $consultant]
                                                ) }}"
                                                class="btn btn-sm btn-outline-secondary"
                                                title="Edit"
                                            >

                                                <i class="fa fa-edit"></i>

                                            </a>


                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.projects.design-management.consultants.destroy',
                                                    [$project, $consultant]
                                                ) }}"
                                                class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this consultant?');"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Delete"
                                                >

                                                    <i class="fa fa-trash"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


            @else

                {{-- ================================================= --}}
                {{-- Empty State --}}
                {{-- ================================================= --}}

                <div class="text-center py-5">

                    <div class="mb-3">

                        <i
                            class="bi bi-people display-4 text-muted"
                        ></i>

                    </div>


                    <h5>
                        No Consultants Found
                    </h5>


                    <p class="text-muted mb-4">

                        No design consultants have been
                        appointed for this project yet.

                    </p>


                    <a
                        href="{{ route(
                            'admin.projects.design-management.consultants.create',
                            $project
                        ) }}"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-plus-lg me-1"></i>

                        Add First Consultant

                    </a>

                </div>

            @endif

        </div>

    </div>


    @if($consultants->count() > 0)

        <div class="text-muted small mt-3">

            Showing

            <strong>
                {{ $consultants->count() }}
            </strong>

            consultant(s).

        </div>

    @endif

</div>

@endsection