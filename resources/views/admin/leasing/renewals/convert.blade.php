@extends('layouts.app')

@section('title', 'Create Renewed Agreement')

@section('content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Create Renewed Agreement
            </h4>

            <div class="text-muted">
                Renewal {{ $renewal->renewal_no }}
            </div>

        </div>


        <a href="{{ route(
            'admin.leasing.renewals.show',
            $renewal->id
        ) }}"
           class="btn btn-secondary">

            <i class="fas fa-arrow-left me-1"></i>

            Back

        </a>

    </div>


    {{-- Warning --}}

    <div class="alert alert-warning">

        <i class="fas fa-exclamation-triangle me-1"></i>

        You are about to create a new lease agreement from this
        approved renewal.

        The existing agreement will remain in the system and will be
        marked as <strong>Renewed</strong>.

    </div>


    {{-- Existing Agreement --}}

    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">

                <i class="fas fa-file-contract me-1"></i>

                Existing Agreement

            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4">


                <div class="col-md-3">

                    <div class="text-muted small">
                        Agreement No.
                    </div>

                    <div class="fw-semibold">

                        {{ $agreement->agreement_no }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Tenant
                    </div>

                    <div class="fw-semibold">

                        {{ $agreement->tenant?->company_name ?? '-' }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Current Start
                    </div>

                    <div class="fw-semibold">

                        {{ $agreement->lease_start_date
                            ? $agreement->lease_start_date
                                ->format('d M Y')
                            : '-' }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Current End
                    </div>

                    <div class="fw-semibold text-danger">

                        {{ $agreement->lease_end_date
                            ? $agreement->lease_end_date
                                ->format('d M Y')
                            : '-' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Renewal Information --}}

    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">

                <i class="fas fa-sync-alt me-1"></i>

                Approved Renewal

            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4">


                <div class="col-md-3">

                    <div class="text-muted small">
                        Renewal No.
                    </div>

                    <div class="fw-semibold">

                        {{ $renewal->renewal_no }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Proposed Start
                    </div>

                    <div class="fw-semibold">

                        {{ $renewal->proposed_start_date
                            ? $renewal->proposed_start_date
                                ->format('d M Y')
                            : '-' }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Proposed End
                    </div>

                    <div class="fw-semibold">

                        {{ $renewal->proposed_end_date
                            ? $renewal->proposed_end_date
                                ->format('d M Y')
                            : '-' }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Period
                    </div>

                    <div class="fw-semibold">

                        {{ $renewal->renewal_period_months ?? '-' }}

                        @if($renewal->renewal_period_months)
                            Months
                        @endif

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Current Rent
                    </div>

                    <div>

                        ${{ number_format(
                            $renewal->current_rent ?? 0,
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Proposed Rent
                    </div>

                    <div class="fw-semibold text-success">

                        ${{ number_format(
                            $renewal->proposed_rent ?? 0,
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Proposed Security Deposit
                    </div>

                    <div class="fw-semibold">

                        ${{ number_format(
                            $renewal->proposed_security_deposit ?? 0,
                            2
                        ) }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Confirmation --}}

    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">

                <i class="fas fa-check-circle me-1"></i>

                Confirmation

            </h5>

        </div>


        <div class="card-body">

            <p class="mb-3">

                The following actions will be performed:

            </p>

            <ul>

                <li>
                    A new lease agreement will be created.
                </li>

                <li>
                    The new agreement will use the approved renewal dates.
                </li>

                <li>
                    The proposed rent will become the new monthly rent.
                </li>

                <li>
                    The proposed security deposit will become the new
                    security deposit.
                </li>

                <li>
                    The existing agreement will be marked as
                    <strong>Renewed</strong>.
                </li>

                <li>
                    The new agreement will initially be created as
                    <strong>Active</strong>.
                </li>

            </ul>


            <div class="alert alert-info">

                <i class="fas fa-info-circle me-1"></i>

                No existing agreement data will be deleted.

            </div>


            <form method="POST"
                  action="{{ route(
                      'admin.leasing.renewals.convert.store',
                      $renewal->id
                  ) }}">

                @csrf


                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route(
                        'admin.leasing.renewals.show',
                        $renewal->id
                    ) }}"
                       class="btn btn-secondary">

                        Cancel

                    </a>


                    <button type="submit"
                            class="btn btn-success"
                            onclick="return confirm(
                                'Create the renewed lease agreement?'
                            );">

                        <i class="fas fa-file-contract me-1"></i>

                        Create Renewed Agreement

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection