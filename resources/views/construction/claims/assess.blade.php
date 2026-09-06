@extends('layouts.app')

@section('title', 'Assess Claim')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Assess Claim
            </h4>

            <div class="text-muted">
                {{ $project->project_number ?? $project->project_code }}
                -
                {{ $project->project_name }}
            </div>

        </div>

        <div>

            <a href="{{ route('admin.projects.construction.claims.show', [$project, $claim]) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>
                Back to Claim

            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- SUCCESS MESSAGE --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- ERROR MESSAGE --}}
    {{-- ========================================================= --}}

    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-triangle me-1"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- VALIDATION ERRORS --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-semibold mb-2">

                <i class="bi bi-exclamation-triangle me-1"></i>

                Please correct the following errors:

            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- CLAIM SUMMARY --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Claim Summary
            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4">

                {{-- Claim Number --}}
                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Claim Number
                    </div>

                    <div class="fw-semibold">
                        {{ $claim->claim_number }}
                    </div>

                </div>


                {{-- Status --}}
                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Current Status
                    </div>

                    @php

                        $statusClass = match($claim->status) {

                            'Under Review' =>
                                'bg-warning text-dark',

                            'Under Assessment' =>
                                'bg-primary',

                            default =>
                                'bg-secondary',

                        };

                    @endphp

                    <span class="badge {{ $statusClass }}">

                        {{ $claim->status }}

                    </span>

                </div>


                {{-- Claim Type --}}
                <div class="col-md-4">

                    <div class="text-muted small mb-1">
                        Claim Type
                    </div>

                    <div>
                        {{ $claim->claim_type }}
                    </div>

                </div>


                {{-- Subject --}}
                <div class="col-md-6">

                    <div class="text-muted small mb-1">
                        Subject
                    </div>

                    <div class="fw-semibold">
                        {{ $claim->subject }}
                    </div>

                </div>


                {{-- Claimant --}}
                <div class="col-md-6">

                    <div class="text-muted small mb-1">
                        Claimant
                    </div>

                    <div>
                        {{ $claim->claimant_name ?? '-' }}
                    </div>

                </div>


                {{-- Contract --}}
                <div class="col-md-6">

                    <div class="text-muted small mb-1">
                        Procurement Contract
                    </div>

                    @if($claim->procurementContract)

                        <div class="fw-semibold">
                            {{ $claim->procurementContract->contract_number }}
                        </div>

                        @if($claim->procurementContract->bidder)

                            <div class="text-muted small">
                                {{ $claim->procurementContract->bidder->bidder_name }}
                            </div>

                        @endif

                    @else

                        <span class="text-muted">
                            Not linked
                        </span>

                    @endif

                </div>


                {{-- Work Order --}}
                <div class="col-md-6">

                    <div class="text-muted small mb-1">
                        Construction Work Order
                    </div>

                    @if($claim->workOrder)

                        <div class="fw-semibold">
                            {{ $claim->workOrder->work_order_number }}
                        </div>

                        @if($claim->workOrder->work_order_title)

                            <div class="text-muted small">
                                {{ $claim->workOrder->work_order_title }}
                            </div>

                        @endif

                    @else

                        <span class="text-muted">
                            Not linked
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- CLAIMED VALUES --}}
    {{-- ========================================================= --}}

    <div class="row g-3 mb-4">

        {{-- Claimed Amount --}}
        <div class="col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Claimed Amount
                    </div>

                    <h4 class="mb-0">

                        ${{ number_format(
                            (float) ($claim->claimed_amount ?? 0),
                            2
                        ) }}

                    </h4>

                </div>

            </div>

        </div>


        {{-- Claimed Days --}}
        <div class="col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Claimed Days
                    </div>

                    <h4 class="mb-0">

                        {{ $claim->claimed_days ?? 0 }}

                        <span class="fs-6 text-muted">
                            Days
                        </span>

                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- EXISTING ASSESSMENT --}}
    {{-- ========================================================= --}}

    @if($claim->status === 'Under Assessment')

        <div class="alert alert-primary">

            <i class="bi bi-info-circle me-1"></i>

            This claim has already been assessed.

            You may update the assessment before the claim is approved.

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- ASSESSMENT FORM --}}
    {{-- ========================================================= --}}

    <form method="POST"
          action="{{ route(
              'admin.projects.construction.claims.assess',
              [$project, $claim]
          ) }}">

        @csrf


        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Assessment
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    {{-- ================================================= --}}
                    {{-- ASSESSED AMOUNT --}}
                    {{-- ================================================= --}}

                    <div class="col-md-6">

                        <label class="form-label">

                            Assessed Amount ($)

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <input type="number"
                               name="assessed_amount"
                               id="assessed_amount"
                               step="0.01"
                               min="0"
                               class="form-control
                                      @error('assessed_amount')
                                          is-invalid
                                      @enderror"
                               value="{{ old(
                                   'assessed_amount',
                                   $claim->assessed_amount ?? ''
                               ) }}"
                               required>


                        @error('assessed_amount')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror


                        <small class="text-muted">

                            Maximum claimed amount:

                            ${{ number_format(
                                (float) ($claim->claimed_amount ?? 0),
                                2
                            ) }}

                        </small>

                    </div>


                    {{-- ================================================= --}}
                    {{-- ASSESSED DAYS --}}
                    {{-- ================================================= --}}

                    <div class="col-md-6">

                        <label class="form-label">

                            Assessed Days

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <input type="number"
                               name="assessed_days"
                               id="assessed_days"
                               min="0"
                               class="form-control
                                      @error('assessed_days')
                                          is-invalid
                                      @enderror"
                               value="{{ old(
                                   'assessed_days',
                                   $claim->assessed_days ?? ''
                               ) }}"
                               required>


                        @error('assessed_days')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror


                        <small class="text-muted">

                            Maximum claimed days:

                            {{ $claim->claimed_days ?? 0 }}

                        </small>

                    </div>


                    {{-- ================================================= --}}
                    {{-- ASSESSMENT REMARKS --}}
                    {{-- ================================================= --}}

                    <div class="col-12">

                        <label class="form-label">

                            Assessment Remarks

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <textarea name="assessment_remarks"
                                  id="assessment_remarks"
                                  rows="5"
                                  class="form-control
                                         @error('assessment_remarks')
                                             is-invalid
                                         @enderror"
                                  required>{{ old(
                                      'assessment_remarks',
                                      $claim->assessment_remarks ?? ''
                                  ) }}</textarea>


                        @error('assessment_remarks')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror


                        <small class="text-muted">

                            Mention the basis of assessment,
                            contractual entitlement,
                            supporting evidence and calculation.

                        </small>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- INFORMATION --}}
        {{-- ========================================================= --}}

        <div class="alert alert-info">

            <div class="d-flex">

                <div class="me-2">
                    <i class="bi bi-info-circle"></i>
                </div>

                <div>

                    <strong>Assessment Guidance</strong>

                    <div class="mt-1">

                        Review the claim details, contract entitlement,
                        supporting documents and actual impact before
                        entering the assessed amount and days.

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- BUTTONS --}}
        {{-- ========================================================= --}}

        <div class="d-flex justify-content-end gap-2 mb-4">

            <a href="{{ route(
                'admin.projects.construction.claims.show',
                [$project, $claim]
            ) }}"
               class="btn btn-light">

                Cancel

            </a>


            <button type="submit"
                    class="btn btn-primary">

                <i class="bi bi-calculator me-1"></i>

                {{ $claim->status === 'Under Assessment'
                    ? 'Update Assessment'
                    : 'Save Assessment'
                }}

            </button>

        </div>

    </form>

</div>

@endsection