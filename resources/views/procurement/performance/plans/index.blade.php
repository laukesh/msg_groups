@extends('layouts.app')

@section('title', 'Procurement Plan Performance')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Procurement Plan Performance
            </h4>

            <div class="text-muted">
                {{ $project->project_number }}
                -
                {{ $project->project_name }}
            </div>
        </div>

        <a href="{{ route(
            'admin.projects.procurement.performance',
            $project
        ) }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left"></i>
            Performance Dashboard

        </a>

    </div>


    {{-- Summary --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <small class="text-muted">
                        Procurement Plans
                    </small>

                    <h3 class="mb-0">
                        {{ $planData->count() }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <small class="text-muted">
                        Packages
                    </small>

                    <h3 class="mb-0">
                        {{ $planData->sum('package_count') }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <small class="text-muted">
                        Tenders
                    </small>

                    <h3 class="mb-0">
                        {{ $planData->sum('tender_count') }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <small class="text-muted">
                        Contracts
                    </small>

                    <h3 class="mb-0">
                        {{ $planData->sum('contract_count') }}
                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- Plans --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <h6 class="mb-0">
                Plan-wise Performance
            </h6>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Plan Number</th>

                            <th>Plan Title</th>

                            <th>Year</th>

                            <th>Status</th>

                            <th>Estimated Value</th>

                            <th>Packages</th>

                            <th>Tenders</th>

                            <th>Awards</th>

                            <th>Contracts</th>

                            <th>Award Value</th>

                            <th>Contract Value</th>

                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($planData as $index => $data)

                            @php
                                $plan = $data['plan'];
                            @endphp

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $plan->plan_number }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $plan->plan_title }}
                                </td>

                                <td>
                                    {{ $plan->procurement_year ?? '-' }}
                                </td>

                                <td>

                                    <span class="badge bg-secondary">
                                        {{ $plan->status }}
                                    </span>

                                </td>

                                <td>
                                    ${{ number_format(
                                        $data['estimated_value'],
                                        2
                                    ) }}
                                </td>

                                <td>
                                    {{ $data['package_count'] }}
                                </td>

                                <td>
                                    {{ $data['tender_count'] }}
                                </td>

                                <td>
                                    {{ $data['award_count'] }}
                                </td>

                                <td>
                                    {{ $data['contract_count'] }}
                                </td>

                                <td>
                                    ${{ number_format(
                                        $data['award_value'],
                                        2
                                    ) }}
                                </td>

                                <td>
                                    ${{ number_format(
                                        $data['contract_value'],
                                        2
                                    ) }}
                                </td>

                                <td>

                                    <a href="#"
                                       class="btn btn-sm btn-outline-primary">

                                        View

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="13"
                                    class="text-center text-muted py-4">

                                    No procurement plans found
                                    for this project.

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