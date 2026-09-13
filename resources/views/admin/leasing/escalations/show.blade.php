@extends('layouts.app')

@section('title', 'Escalation Details')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Lease Escalation
            </h4>

            <div class="text-muted">
                Escalation #{{ $escalation->escalation_no }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.leasing.escalations.index'
            ) }}"
               class="btn btn-secondary">

                <i class="fas fa-arrow-left me-1"></i>

                Back

            </a>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger">

            {{ session('error') }}

        </div>

    @endif


    {{-- Status --}}

    <div class="card mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <div class="text-muted small">
                        Status
                    </div>

                    @if($escalation->status === 'Pending')

                        <span class="badge bg-warning text-dark fs-6">
                            Pending
                        </span>

                    @elseif($escalation->status === 'Applied')

                        <span class="badge bg-success fs-6">
                            Applied
                        </span>

                    @else

                        <span class="badge bg-secondary fs-6">
                            Cancelled
                        </span>

                    @endif

                </div>


                @if($escalation->status === 'Pending')

                    <div class="d-flex gap-2">


                        <form method="POST"
                              action="{{ route(
                                  'admin.leasing.escalations.approve',
                                  $escalation->id
                              ) }}"
                              onsubmit="return confirm(
                                  'Apply this rent escalation to the lease agreement?'
                              );">

                            @csrf

                            <button type="submit"
                                    class="btn btn-success">

                                <i class="fas fa-check-circle me-1"></i>

                                Apply Escalation

                            </button>

                        </form>


                        <form method="POST"
                              action="{{ route(
                                  'admin.leasing.escalations.cancel',
                                  $escalation->id
                              ) }}"
                              onsubmit="return confirm(
                                  'Are you sure you want to cancel this escalation?'
                              );">

                            @csrf

                            <button type="submit"
                                    class="btn btn-danger">

                                <i class="fas fa-times-circle me-1"></i>

                                Cancel

                            </button>

                        </form>

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- Agreement Information --}}

    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">
                Lease Agreement
            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">

                    <div class="text-muted small">
                        Agreement No.
                    </div>

                    <div class="fw-semibold">

                        {{ $escalation->agreement?->agreement_no ?? '-' }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Tenant
                    </div>

                    <div class="fw-semibold">

                        {{ $escalation->agreement?->tenant?->company_name ?? '-' }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Lease Start
                    </div>

                    <div>

                        {{ $escalation->agreement?->lease_start_date
                            ? $escalation->agreement->lease_start_date->format('d M Y')
                            : '-' }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Lease End
                    </div>

                    <div>

                        {{ $escalation->agreement?->lease_end_date
                            ? $escalation->agreement->lease_end_date->format('d M Y')
                            : '-' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Escalation Details --}}

    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">
                Escalation Details
            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4">


                <div class="col-md-3">

                    <div class="text-muted small">
                        Escalation No.
                    </div>

                    <div class="fw-semibold">

                        {{ $escalation->escalation_no }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Effective From
                    </div>

                    <div class="fw-semibold">

                        {{ $escalation->effective_from
                            ? $escalation->effective_from->format('d M Y')
                            : '-' }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Escalation Type
                    </div>

                    <div>

                        {{ $escalation->escalation_type }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Escalation Value
                    </div>

                    <div>

                        @if($escalation->escalation_type === 'Percentage')

                            {{ number_format(
                                $escalation->escalation_value,
                                2
                            ) }}%

                        @else

                            ${{ number_format(
                                $escalation->escalation_value,
                                2
                            ) }}

                        @endif

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Previous Monthly Rent
                    </div>

                    <div class="fs-5">

                        ${{ number_format(
                            $escalation->previous_rent ?? 0,
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Revised Monthly Rent
                    </div>

                    <div class="fs-5 fw-bold text-success">

                        ${{ number_format(
                            $escalation->revised_rent ?? 0,
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Increase
                    </div>

                    <div class="fs-5 fw-bold">

                        ${{ number_format(
                            ($escalation->revised_rent ?? 0)
                            -
                            ($escalation->previous_rent ?? 0),
                            2
                        ) }}

                    </div>

                </div>


                @if($escalation->remarks)

                    <div class="col-md-12">

                        <div class="text-muted small">
                            Remarks
                        </div>

                        <div>

                            {{ $escalation->remarks }}

                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- Approval Information --}}

    @if($escalation->status === 'Applied')

        <div class="card">

            <div class="card-header">

                <h5 class="mb-0">
                    Application Information
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-4">

                    <div class="col-md-4">

                        <div class="text-muted small">
                            Applied By
                        </div>

                        <div>

                            {{ $escalation->approver?->name ?? '-' }}

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="text-muted small">
                            Applied At
                        </div>

                        <div>

                            {{ $escalation->approved_at
                                ? $escalation->approved_at->format('d M Y H:i')
                                : '-' }}

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="text-muted small">
                            New Agreement Rent
                        </div>

                        <div class="fw-bold text-success">

                            ${{ number_format(
                                $escalation->agreement?->monthly_rent ?? 0,
                                2
                            ) }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endif

</div>

@endsection