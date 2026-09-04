@extends('layouts.app')

@section('content')

<div class="container-fluid">


    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="text-muted">
                Contract Management
            </div>

            <h4 class="mb-1">
                Contract Register
            </h4>

            <div class="text-muted">

                Project:

                <strong>
                    {{ $project->project_name ?? '—' }}
                </strong>

                @if($project->project_code)

                    <span class="ms-2">
                        ({{ $project->project_code }})
                    </span>

                @endif

            </div>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.contract-management.index',
                $project
            ) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>

                Back to Projects

            </a>

            {{-- Sync Procurement Contracts --}}
            <form method="POST"
                  action="{{ route(
                      'admin.projects.contract-management.contracts.sync-procurement',
                      $project
                  ) }}">

                @csrf

                <button type="submit"
                        class="btn btn-outline-primary"
                        onclick="return confirm(
                            'Import existing Procurement Contracts into Contract Management?'
                        );">

                    <i class="bi bi-arrow-repeat me-1"></i>

                    Sync Procurement Contracts

                </button>

            </form>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Summary --}}
    {{-- ========================================================= --}}

    <div class="row g-3 mb-4">


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Total Contracts
                    </div>

                    <h3 class="mb-0">
                        {{ $summary['total_contracts'] ?? 0 }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Active Contracts
                    </div>

                    <h3 class="mb-0">
                        {{ $summary['active_contracts'] ?? 0 }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Completed
                    </div>

                    <h3 class="mb-0">
                        {{ $summary['completed_contracts'] ?? 0 }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small mb-1">
                        Total Contract Value
                    </div>

                    <h3 class="mb-0">

                        ₹{{ number_format(
                            (float) (
                                $summary['total_value'] ?? 0
                            ),
                            2
                        ) }}

                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Register --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Contract Register
            </h5>

            <small class="text-muted">
                Contracts associated with this project
            </small>

        </div>


        <div class="card-body p-0">


            @if($contracts->count())


                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="px-3">
                                    Contract Code
                                </th>

                                <th>
                                    Contract
                                </th>

                                <th>
                                    Party
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Value
                                </th>

                                <th>
                                    Start Date
                                </th>

                                <th>
                                    Completion
                                </th>

                                <th>
                                    Source
                                </th>

                                <th>
                                    Status
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($contracts as $contract)

                                @php

                                    $statusClass = match(
                                        $contract->status
                                    ) {

                                        'Active',
                                        'Approved',
                                        'In Progress'
                                            => 'success',

                                        'Completed',
                                        'Closed'
                                            => 'secondary',

                                        'Pending',
                                        'Draft'
                                            => 'warning',

                                        'Cancelled',
                                        'Terminated'
                                            => 'danger',

                                        default
                                            => 'secondary',
                                    };

                                @endphp


                                <tr>


                                    {{-- Code --}}
                                    <td class="px-3">

                                        <strong>
                                            <a href="{{ route(
    'admin.projects.contract-management.contracts.show',
    [$project, $contract]
) }}"
   class="fw-semibold text-decoration-none">

    {{ $contract->contract_code }}

</a>
                                        </strong>

                                    </td>


                                    {{-- Contract --}}
                                    <td>

                                        <div class="fw-semibold">

                                            {{ $contract->contract_title }}

                                        </div>


                                        @if($contract->contract_number)

                                            <div class="small text-muted">

                                                No:
                                                {{ $contract->contract_number }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Party --}}
                                    <td>

                                        <div class="fw-semibold">

                                            {{ $contract->party_name }}

                                        </div>

                                        <div class="small text-muted">

                                            {{ $contract->party_type }}

                                        </div>

                                    </td>


                                    {{-- Type --}}
                                    <td>

                                        {{ $contract->contract_type ?? '—' }}

                                    </td>


                                    {{-- Value --}}
                                    <td>

                                        <strong>

                                            {{ $contract->currency ?? 'INR' }}

                                            {{ number_format(
                                                (float)
                                                $contract->contract_value,
                                                2
                                            ) }}

                                        </strong>

                                    </td>


                                    {{-- Start --}}
                                    <td>

                                        {{ $contract->start_date
                                            ? $contract->start_date
                                                ->format('d M Y')
                                            : '—'
                                        }}

                                    </td>


                                    {{-- Completion --}}
                                    <td>

                                        {{ $contract->completion_date
                                            ? $contract->completion_date
                                                ->format('d M Y')
                                            : '—'
                                        }}

                                    </td>


                                    {{-- Source --}}
                                    <td>

                                        <span class="badge bg-light text-dark">

                                            {{ $contract->contract_source }}

                                        </span>

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        <span
                                            class="badge bg-{{ $statusClass }}"
                                        >

                                            {{ $contract->status }}

                                        </span>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


            @else


                <div class="text-center py-5">

                    <div class="mb-3">

                        <i class="bi bi-file-earmark-text display-5 text-muted"></i>

                    </div>

                    <h5>
                        No Contracts Found
                    </h5>

                    <p class="text-muted mb-0">

                        No contracts have been registered
                        for this project yet.

                    </p>

                </div>


            @endif

        </div>

    </div>

</div>

@endsection