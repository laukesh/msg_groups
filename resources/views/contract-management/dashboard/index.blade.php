@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ============================================================= --}}
    {{-- Header                                                        --}}
    {{-- ============================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Contract Management
            </h4>

            <div class="text-muted">
                Centralized contract management across all projects
            </div>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- Summary Cards                                                  --}}
    {{-- ============================================================= --}}

    <div class="row g-3 mb-4">


        {{-- Total Contracts --}}

        <div class="col-xl col-md-6">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Total Contracts
                            </div>

                            <div class="fs-3 fw-semibold">
                                {{ $summary['total_contracts'] }}
                            </div>

                        </div>

                        <div class="text-primary fs-3">

                            <i class="bi bi-file-earmark-text"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Active --}}

        <div class="col-xl col-md-6">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Active Contracts
                            </div>

                            <div class="fs-3 fw-semibold">
                                {{ $summary['active_contracts'] }}
                            </div>

                        </div>

                        <div class="text-success fs-3">

                            <i class="bi bi-check-circle"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Completed --}}

        <div class="col-xl col-md-6">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Completed Contracts
                            </div>

                            <div class="fs-3 fw-semibold">
                                {{ $summary['completed_contracts'] }}
                            </div>

                        </div>

                        <div class="text-info fs-3">

                            <i class="bi bi-check2-square"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Draft --}}

        <div class="col-xl col-md-6">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Draft Contracts
                            </div>

                            <div class="fs-3 fw-semibold">
                                {{ $summary['draft_contracts'] }}
                            </div>

                        </div>

                        <div class="text-secondary fs-3">

                            <i class="bi bi-pencil-square"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Total Value --}}

        <div class="col-xl col-md-6">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Contract Value
                    </div>

                    <div class="fs-3 fw-semibold">

                        ₹{{ number_format(
                            $summary['total_value'],
                            2
                        ) }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================= --}}
    {{-- Project Selection                                              --}}
    {{-- ============================================================= --}}

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <div>

                <h5 class="mb-1">
                    Projects
                </h5>

                <div class="small text-muted">
                    Select a project to manage its contracts
                </div>

            </div>

        </div>


        <div class="card-body">


            @if($projects->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    Project
                                </th>

                                <th>
                                    Contracts
                                </th>

                                <th>
                                    Active
                                </th>

                                <th>
                                    Completed
                                </th>

                                <th>
                                    Contract Value
                                </th>

                                <th class="text-end">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($projects as $project)

                                @php

                                    $stats =
                                        $projectSummary
                                            ->get(
                                                $project->id
                                            );

                                @endphp


                                <tr>

                                    {{-- Project --}}

                                    <td>

                                        <div class="fw-semibold">

                                            {{ $project->project_name }}

                                        </div>

                                        @if(
                                            !empty(
                                                $project->project_code
                                            )
                                        )

                                            <div class="small text-muted">

                                                {{ $project->project_code }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Contracts --}}

                                    <td>

                                        {{ $stats['contract_count'] ?? 0 }}

                                    </td>


                                    {{-- Active --}}

                                    <td>

                                        @if(
                                            ($stats['active_count'] ?? 0)
                                            > 0
                                        )

                                            <span class="badge bg-success">

                                                {{
                                                    $stats['active_count']
                                                }}

                                            </span>

                                        @else

                                            <span class="text-muted">
                                                0
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Completed --}}

                                    <td>

                                        {{ $stats['completed_count'] ?? 0 }}

                                    </td>


                                    {{-- Value --}}

                                    <td>

                                        ₹{{
                                            number_format(
                                                $stats['contract_value'] ?? 0,
                                                2
                                            )
                                        }}

                                    </td>


                                    {{-- Action --}}

                                    <td class="text-end">

                                        <a href="{{ route(
                                            'admin.projects.contract-management.contracts.index',
                                            $project
                                        ) }}"
                                           class="btn btn-sm btn-primary">

                                            <i class="bi bi-arrow-right me-1"></i>

                                            Manage Contracts

                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <i class="bi bi-building fs-1 text-muted"></i>

                    <h6 class="mt-3">
                        No Projects Found
                    </h6>

                    <p class="text-muted mb-0">
                        Create a project first to manage its contracts.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection