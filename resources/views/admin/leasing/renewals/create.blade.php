@extends('layouts.app')

@section('title', 'Create Lease Renewal')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Create Lease Renewal</h4>

            <div class="text-muted">
                Create a renewal request for an active lease agreement.
            </div>
        </div>

        <a href="{{ route('admin.leasing.renewals.index') }}"
           class="btn btn-secondary">

            <i class="fas fa-arrow-left me-1"></i>
            Back

        </a>

    </div>


    {{-- Errors --}}
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


    <form method="POST"
          action="{{ route('admin.leasing.renewals.store') }}">

        @csrf


        {{-- Agreement Selection --}}
        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-file-contract me-1"></i>

                    Lease Agreement

                </h5>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-8">

                        <label class="form-label">

                            Lease Agreement
                            <span class="text-danger">*</span>

                        </label>

                        <select name="lease_agreement_id"
                                id="lease_agreement_id"
                                class="form-select"
                                required>

                            <option value="">
                                -- Select Active Agreement --
                            </option>


                            @foreach($agreements as $item)

                                <option value="{{ $item->id }}"
                                    {{ old(
                                        'lease_agreement_id',
                                        $agreement?->id
                                    ) == $item->id
                                        ? 'selected'
                                        : '' }}>

                                    {{ $item->agreement_no }}

                                    @if($item->tenant)

                                        -
                                        {{ $item->tenant->company_name }}

                                    @endif

                                </option>

                            @endforeach

                        </select>

                        <div class="form-text">

                            Only active lease agreements can be renewed.

                        </div>

                    </div>

                </div>


                {{-- Selected Agreement Information --}}

                @if($agreement)

                    <div class="alert alert-info mt-3 mb-0">

                        <div class="row">

                            <div class="col-md-3">

                                <small class="text-muted">
                                    Agreement No.
                                </small>

                                <div class="fw-semibold">
                                    {{ $agreement->agreement_no }}
                                </div>

                            </div>


                            <div class="col-md-3">

                                <small class="text-muted">
                                    Tenant
                                </small>

                                <div class="fw-semibold">

                                    {{ $agreement->tenant?->company_name ?? '-' }}

                                </div>

                            </div>


                            <div class="col-md-3">

                                <small class="text-muted">
                                    Current Expiry
                                </small>

                                <div class="fw-semibold">

                                    {{ $agreement->lease_end_date?->format(
                                        'd M Y'
                                    ) ?? '-' }}

                                </div>

                            </div>


                            <div class="col-md-3">

                                <small class="text-muted">
                                    Current Rent
                                </small>

                                <div class="fw-semibold">

                                    ${{ number_format(
                                        $agreement->monthly_rent ?? 0,
                                        2
                                    ) }}

                                </div>

                            </div>

                        </div>

                    </div>

                @endif

            </div>

        </div>


        {{-- Renewal Request --}}
        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-calendar-alt me-1"></i>

                    Renewal Period

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- Request Date --}}
                    <div class="col-md-4">

                        <label class="form-label">

                            Request Date
                            <span class="text-danger">*</span>

                        </label>

                        <input type="date"
                               name="request_date"
                               class="form-control"
                               value="{{ old(
                                   'request_date',
                                   now()->format('Y-m-d')
                               ) }}"
                               required>

                    </div>


                    {{-- Proposed Start --}}
                    <div class="col-md-4">

                        <label class="form-label">

                            Proposed Start Date
                            <span class="text-danger">*</span>

                        </label>

                        <input type="date"
                               name="proposed_start_date"
                               id="proposed_start_date"
                               class="form-control"
                               value="{{ old(
                                   'proposed_start_date',
                                   $agreement?->lease_end_date
                                       ? $agreement->lease_end_date
                                           ->copy()
                                           ->addDay()
                                           ->format('Y-m-d')
                                       : ''
                               ) }}"
                               required>

                    </div>


                    {{-- Proposed End --}}
                    <div class="col-md-4">

                        <label class="form-label">

                            Proposed End Date
                            <span class="text-danger">*</span>

                        </label>

                        <input type="date"
                               name="proposed_end_date"
                               id="proposed_end_date"
                               class="form-control"
                               value="{{ old(
                                   'proposed_end_date'
                               ) }}"
                               required>

                    </div>


                    {{-- Renewal Period --}}
                    <div class="col-md-4">

                        <label class="form-label">

                            Renewal Period (Months)

                        </label>

                        <input type="number"
                               name="renewal_period_months"
                               id="renewal_period_months"
                               class="form-control"
                               min="1"
                               value="{{ old(
                                   'renewal_period_months'
                               ) }}"
                               placeholder="e.g. 12">

                    </div>

                </div>

            </div>

        </div>


        {{-- Financial Details --}}
        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-money-bill-wave me-1"></i>

                    Financial Details

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- Current Rent --}}
                    <div class="col-md-4">

                        <label class="form-label">

                            Current Rent

                        </label>

                        <input type="text"
                               class="form-control"
                               value="{{ $agreement
                                   ? number_format(
                                       $agreement->monthly_rent ?? 0,
                                       2
                                   )
                                   : ''
                               }}"
                               readonly>

                        <div class="form-text">

                            Current rent from lease agreement.

                        </div>

                    </div>


                    {{-- Proposed Rent --}}
                    <div class="col-md-4">

                        <label class="form-label">

                            Proposed Rent
                            <span class="text-danger">*</span>

                        </label>

                        <input type="number"
                               step="0.01"
                               min="0"
                               name="proposed_rent"
                               id="proposed_rent"
                               class="form-control"
                               value="{{ old(
                                   'proposed_rent',
                                   $agreement?->monthly_rent
                               ) }}"
                               required>

                    </div>


                    {{-- Security Deposit --}}
                    <div class="col-md-4">

                        <label class="form-label">

                            Proposed Security Deposit

                        </label>

                        <input type="number"
                               step="0.01"
                               min="0"
                               name="proposed_security_deposit"
                               class="form-control"
                               value="{{ old(
                                   'proposed_security_deposit',
                                   $agreement?->security_deposit
                               ) }}">

                    </div>


                    {{-- Escalation --}}
                    <div class="col-md-4">

                        <label class="form-label">

                            Escalation (%)

                        </label>

                        <input type="number"
                               step="0.01"
                               min="0"
                               max="100"
                               name="escalation_percentage"
                               id="escalation_percentage"
                               class="form-control"
                               value="{{ old(
                                   'escalation_percentage'
                               ) }}"
                               placeholder="e.g. 10">

                    </div>

                </div>

            </div>

        </div>


        {{-- Negotiation --}}
        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fas fa-comments me-1"></i>

                    Negotiation & Remarks

                </h5>

            </div>


            <div class="card-body">

                <div class="mb-3">

                    <label class="form-label">

                        Negotiation Notes

                    </label>

                    <textarea name="negotiation_notes"
                              class="form-control"
                              rows="4"
                              placeholder="Enter negotiation details...">{{ old(
                                  'negotiation_notes'
                              ) }}</textarea>

                </div>


                <div>

                    <label class="form-label">

                        Remarks

                    </label>

                    <textarea name="remarks"
                              class="form-control"
                              rows="3"
                              placeholder="Enter additional remarks...">{{ old(
                                  'remarks'
                              ) }}</textarea>

                </div>

            </div>

        </div>


        {{-- Information --}}
        <div class="alert alert-warning">

            <i class="fas fa-info-circle me-1"></i>

            This will create a <strong>Draft</strong> renewal request.
            The current lease agreement will not be changed until
            the renewal is approved.

        </div>


        {{-- Actions --}}
        <div class="card mb-4">

            <div class="card-body">

                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route(
                        'admin.leasing.renewals.index'
                    ) }}"
                       class="btn btn-secondary">

                        Cancel

                    </a>


                    <button type="submit"
                            class="btn btn-primary">

                        <i class="fas fa-save me-1"></i>

                        Save Renewal Draft

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection