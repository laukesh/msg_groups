@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Page Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

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


        <a href="{{ route(
            'admin.projects.construction.consultants.create',
            $project
        ) }}"
           class="btn btn-primary">

            <i class="fa fas-plus-lg me-1"></i>

            Add Consultant

        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- Success Message --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show"
             role="alert">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- Error Message --}}
    {{-- ========================================================= --}}

    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

            <i class="bi bi-exclamation-triangle me-2"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- Summary Cards --}}
    {{-- ========================================================= --}}

    <div class="row g-3 mb-4">


        {{-- Total --}}
        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <div class="text-muted small mb-1">
                                Total Consultants
                            </div>

                            <h3 class="mb-0">

                                {{ $summary['total'] ?? 0 }}

                            </h3>

                        </div>


                        <div class="bg-primary bg-opacity-10
                                    text-primary rounded p-2">

                            <i class="bi bi-people fs-5"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Active --}}
        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <div class="text-muted small mb-1">
                                Active Consultants
                            </div>

                            <h3 class="mb-0">

                                {{ $summary['active'] ?? 0 }}

                            </h3>

                        </div>


                        <div class="bg-success bg-opacity-10
                                    text-success rounded p-2">

                            <i class="bi bi-person-check fs-5"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Completed --}}
        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <div class="text-muted small mb-1">
                                Completed
                            </div>

                            <h3 class="mb-0">

                                {{ $summary['completed'] ?? 0 }}

                            </h3>

                        </div>


                        <div class="bg-secondary bg-opacity-10
                                    text-secondary rounded p-2">

                            <i class="bi bi-check2-circle fs-5"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Contract Value --}}
        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <div class="text-muted small mb-1">
                                Total Contract Value
                            </div>

                            <h3 class="mb-0">

                                ${{ number_format(
                                    (float) ($summary['total_value'] ?? 0),
                                    2
                                ) }}

                            </h3>

                        </div>


                        <div class="bg-warning bg-opacity-10
                                    text-warning rounded p-2">

                            <i class="bi bi-currency-rupee fs-5"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Consultant Register --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="mb-0">
                        Consultant Register
                    </h5>

                    <small class="text-muted">
                        Consultants appointed for this project
                    </small>

                </div>

            </div>

        </div>


        <div class="card-body p-0">


            @if($consultants->count() > 0)

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="px-3">
                                    Code
                                </th>

                                <th>
                                    Consultant
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

                                <th>
                                    Appointment
                                </th>

                                <th class="text-end">
                                    Contract Value
                                </th>

                                <th class="text-center">
                                    Status
                                </th>

                                <th class="text-center">
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

                                        'Active'
                                            => 'success',

                                        'Completed'
                                            => 'secondary',

                                        'Pending'
                                            => 'warning',

                                        'On Hold',
                                        'Suspended'
                                            => 'warning',

                                        'Terminated',
                                        'Cancelled'
                                            => 'danger',

                                        default
                                            => 'secondary',

                                    };


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Appointment Expiry
                                    |--------------------------------------------------------------------------
                                    */

                                    $expiryWarning = false;

                                    if (
                                        $consultant->end_date
                                        &&
                                        $consultant->status === 'Active'
                                    ) {

                                        $daysRemaining =
                                            now()->startOfDay()
                                                ->diffInDays(
                                                    $consultant->end_date,
                                                    false
                                                );

                                        $expiryWarning =
                                            $daysRemaining >= 0
                                            &&
                                            $daysRemaining <= 30;

                                    }

                                @endphp


                                <tr>


                                    {{-- ================================================= --}}
                                    {{-- Code --}}
                                    {{-- ================================================= --}}

                                    <td class="px-3">

                                        <span class="fw-semibold">

                                            {{ $consultant->consultant_code ?? '—' }}

                                        </span>

                                    </td>


                                    {{-- ================================================= --}}
                                    {{-- Consultant --}}
                                    {{-- ================================================= --}}

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


                                    {{-- ================================================= --}}
                                    {{-- Type --}}
                                    {{-- ================================================= --}}

                                    <td>

                                        {{ $consultant->consultant_type ?? '—' }}

                                    </td>


                                    {{-- ================================================= --}}
                                    {{-- Role --}}
                                    {{-- ================================================= --}}

                                    <td>

                                        {{ $consultant->consultant_role ?? '—' }}

                                    </td>


                                    {{-- ================================================= --}}
                                    {{-- Discipline --}}
                                    {{-- ================================================= --}}

                                    <td>

                                        {{ $consultant->discipline ?? '—' }}

                                    </td>


                                    {{-- ================================================= --}}
                                    {{-- Contact --}}
                                    {{-- ================================================= --}}

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

                                            <div class="small text-muted">

                                                <i class="bi bi-telephone me-1"></i>

                                                {{ $consultant->phone }}

                                            </div>

                                        @endif


                                        @if(
                                            !$consultant->contact_person
                                            &&
                                            !$consultant->email
                                            &&
                                            !$consultant->phone
                                        )

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- ================================================= --}}
                                    {{-- Appointment --}}
                                    {{-- ================================================= --}}

                                    <td>

                                        @if($consultant->start_date)

                                            <div>

                                                {{ $consultant->start_date->format('d M Y') }}

                                            </div>

                                        @else

                                            <div class="text-muted">
                                                —
                                            </div>

                                        @endif


                                        @if($consultant->end_date)

                                            <div class="small text-muted">

                                                to
                                                {{ $consultant->end_date->format('d M Y') }}

                                            </div>


                                            @if($expiryWarning)

                                                <div class="mt-1">

                                                    <span class="badge bg-warning text-dark">

                                                        <i class="bi bi-exclamation-triangle me-1"></i>

                                                        Expiring Soon

                                                    </span>

                                                </div>

                                            @endif

                                        @endif

                                    </td>


                                    {{-- ================================================= --}}
                                    {{-- Contract Value --}}
                                    {{-- ================================================= --}}

                                    <td class="text-end">

                                        @if(
                                            $consultant->contract_value !== null
                                            &&
                                            (float) $consultant->contract_value > 0
                                        )

                                            <span class="fw-semibold">

                                                {{ $consultant->currency ?? 'USD' }}

                                                {{ number_format(
                                                    (float) $consultant->contract_value,
                                                    2
                                                ) }}

                                            </span>

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- ================================================= --}}
                                    {{-- Status --}}
                                    {{-- ================================================= --}}

                                    <td class="text-center">

                                        <span class="badge bg-{{ $statusClass }}">

                                            {{ $consultant->status }}

                                        </span>

                                    </td>


                                    {{-- ================================================= --}}
                                    {{-- Actions --}}
                                    {{-- ================================================= --}}

                                    <td class="text-center">

                                        <div class="d-inline-flex gap-1">


                                            {{-- View --}}
                                            <a href="{{ route(
                                                'admin.projects.construction.consultants.show',
                                                [$project, $consultant]
                                            ) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="View Consultant">

                                                <i class="fa fa-eye"></i>

                                            </a>


                                            {{-- Edit --}}
                                            <a href="{{ route(
                                                'admin.projects.construction.consultants.edit',
                                                [$project, $consultant]
                                            ) }}"
                                               class="btn btn-sm btn-outline-secondary"
                                               title="Edit Consultant">

                                                <i class="fa fa-edit"></i>

                                            </a>


                                            {{-- Delete --}}
                                            <form method="POST"
                                                  action="{{ route(
                                                      'admin.projects.construction.consultants.destroy',
                                                      [$project, $consultant]
                                                  ) }}"
                                                  class="d-inline"
                                                  onsubmit="return confirm(
                                                      'Are you sure you want to delete this consultant?'
                                                  );">

                                                @csrf

                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Delete Consultant">

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

                        <i class="bi bi-people display-4 text-muted"></i>

                    </div>


                    <h5>
                        No Consultants Found
                    </h5>


                    <p class="text-muted mb-4">

                        No consultants have been appointed
                        for this project yet.

                    </p>


                    <a href="{{ route(
                        'admin.projects.construction.consultants.create',
                        $project
                    ) }}"
                       class="btn btn-primary">

                        <i class="bi bi-plus-lg me-1"></i>

                        Add First Consultant

                    </a>

                </div>


            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Footer --}}
    {{-- ========================================================= --}}

    @if($consultants->count() > 0)

        <div class="text-muted small mt-3">

            Showing
            <strong>{{ $consultants->count() }}</strong>
            consultant(s).

        </div>

    @endif

</div>

@endsection