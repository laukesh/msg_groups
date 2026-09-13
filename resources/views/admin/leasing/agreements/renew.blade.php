@extends('layouts.app')

@section('title', 'Renew Lease Agreement')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Renew Lease Agreement
            </h4>

            <div class="text-muted">

                {{ $agreement->agreement_no }}

            </div>

        </div>

        <a href="{{ route(
            'admin.leasing.agreements.show',
            $agreement->id
        ) }}"
           class="btn btn-secondary">

            <i class="fas fa-arrow-left me-1"></i>
            Back

        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Existing Agreement --}}

    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">

                <i class="fas fa-file-contract me-1"></i>

                Current Agreement

            </h5>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Agreement No.
                    </div>

                    <strong>
                        {{ $agreement->agreement_no }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Tenant
                    </div>

                    <strong>
                        {{ $agreement->tenant?->company_name ?? '-' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Current End Date
                    </div>

                    <strong>

                        {{ $agreement->lease_end_date?->format(
                            'd M Y'
                        ) ?? '-' }}

                    </strong>

                </div>

            </div>

        </div>

    </div>


    <form method="POST"
          action="{{ route(
              'admin.leasing.agreements.process-renewal',
              $agreement->id
          ) }}">

        @csrf


        {{-- New Lease Period --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-calendar-alt me-1"></i>

                    Renewal Period

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    <div class="col-md-4">

                        <label class="form-label">

                            Lease Start Date
                            <span class="text-danger">*</span>

                        </label>

                        <input type="date"
                               name="lease_start_date"
                               class="form-control"
                               value="{{ old(
                                   'lease_start_date',
                                   $agreement->lease_end_date
                                       ? $agreement->lease_end_date
                                           ->addDay()
                                           ->format('Y-m-d')
                                       : ''
                               ) }}"
                               required>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">

                            Lease End Date
                            <span class="text-danger">*</span>

                        </label>

                        <input type="date"
                               name="lease_end_date"
                               class="form-control"
                               value="{{ old(
                                   'lease_end_date'
                               ) }}"
                               required>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">

                            Lease Period (Months)

                        </label>

                        <input type="number"
                               name="lease_period_months"
                               class="form-control"
                               min="1"
                               value="{{ old(
                                   'lease_period_months',
                                   $agreement->lease_period_months
                               ) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">

                            Rent Start Date

                        </label>

                        <input type="date"
                               name="rent_start_date"
                               class="form-control"
                               value="{{ old(
                                   'rent_start_date',
                                   $agreement->rent_start_date?->format(
                                       'Y-m-d'
                                   )
                               ) }}">

                    </div>

                </div>

            </div>

        </div>


        {{-- Financial Details --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-money-bill-wave me-1"></i>

                    Renewal Financial Details

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    <div class="col-md-4">

                        <label class="form-label">

                            Monthly Rent
                            <span class="text-danger">*</span>

                        </label>

                        <input type="number"
                               step="0.01"
                               name="monthly_rent"
                               class="form-control"
                               value="{{ old(
                                   'monthly_rent',
                                   $agreement->monthly_rent
                               ) }}"
                               required>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">

                            CAM Amount

                        </label>

                        <input type="number"
                               step="0.01"
                               name="cam_amount"
                               class="form-control"
                               value="{{ old(
                                   'cam_amount',
                                   $agreement->cam_amount
                               ) }}">

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">

                            Security Deposit

                        </label>

                        <input type="number"
                               step="0.01"
                               name="security_deposit"
                               class="form-control"
                               value="{{ old(
                                   'security_deposit',
                                   $agreement->security_deposit
                               ) }}">

                    </div>

                </div>

            </div>

        </div>


        {{-- Remarks --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    Remarks

                </h5>

            </div>


            <div class="card-body">

                <textarea name="remarks"
                          class="form-control"
                          rows="4">{{ old(
                              'remarks',
                              $agreement->remarks
                          ) }}</textarea>

            </div>

        </div>


        {{-- Submit --}}

        <div class="card mb-4">

            <div class="card-body">

                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route(
                        'admin.leasing.agreements.show',
                        $agreement->id
                    ) }}"
                       class="btn btn-secondary">

                        Cancel

                    </a>


                    <button type="submit"
                            class="btn btn-warning"
                            onclick="return confirm(
                                'Are you sure you want to renew this lease agreement?'
                            );">

                        <i class="fas fa-sync-alt me-1"></i>

                        Renew Agreement

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection