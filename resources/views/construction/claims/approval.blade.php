@extends('layouts.app')

@section('title', 'Approve Claim')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Approve Claim
            </h4>

            <div class="text-muted">
                {{ $claim->claim_number }}
                -
                {{ $claim->subject }}
            </div>

        </div>


        <a href="{{ route(
            'admin.projects.construction.claims.show',
            [
                'project' => $project,
                'claim' => $claim,
            ]
        ) }}"
           class="btn btn-light border">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Claim

        </a>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-semibold mb-2">
                Please fix the following errors:
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


    <div class="row">

        {{-- Approval Form --}}
        <div class="col-lg-8">

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Approval Details
                    </h5>

                </div>


                <div class="card-body">

                    <form method="POST"
                          action="{{ route(
                              'admin.projects.construction.claims.approve',
                              [
                                  'project' => $project,
                                  'claim' => $claim,
                              ]
                          ) }}">

                        @csrf


                        {{-- Assessed Amount --}}
                        <div class="mb-4">

                            <label class="form-label">
                                Assessed Amount
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    $
                                </span>

                                <input type="text"
                                       class="form-control"
                                       value="{{ number_format(
                                           (float) ($claim->assessed_amount ?? 0),
                                           2
                                       ) }}"
                                       readonly>

                            </div>

                        </div>


                        {{-- Approved Amount --}}
                        <div class="mb-4">

                            <label class="form-label">

                                Approved Amount

                                <span class="text-danger">
                                    *
                                </span>

                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    $
                                </span>

                                <input type="number"
                                       name="approved_amount"
                                       value="{{ old(
                                           'approved_amount',
                                           $claim->approved_amount
                                       ) }}"
                                       class="form-control @error('approved_amount') is-invalid @enderror"
                                       min="0"
                                       step="0.01"
                                       max="{{ $claim->assessed_amount }}"
                                       required>

                                @error('approved_amount')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                            <small class="text-muted">
                                Maximum allowed:
                                ${{ number_format(
                                    (float) ($claim->assessed_amount ?? 0),
                                    2
                                ) }}
                            </small>

                        </div>


                        {{-- Assessed Days --}}
                        <div class="mb-4">

                            <label class="form-label">
                                Assessed Days
                            </label>

                            <div class="input-group">

                                <input type="number"
                                       class="form-control"
                                       value="{{ $claim->assessed_days ?? 0 }}"
                                       readonly>

                                <span class="input-group-text">
                                    Days
                                </span>

                            </div>

                        </div>


                        {{-- Approved Days --}}
                        <div class="mb-4">

                            <label class="form-label">

                                Approved Days

                                <span class="text-danger">
                                    *
                                </span>

                            </label>

                            <div class="input-group">

                                <input type="number"
                                       name="approved_days"
                                       value="{{ old(
                                           'approved_days',
                                           $claim->approved_days
                                       ) }}"
                                       class="form-control @error('approved_days') is-invalid @enderror"
                                       min="0"
                                       max="{{ $claim->assessed_days }}"
                                       required>

                                <span class="input-group-text">
                                    Days
                                </span>

                                @error('approved_days')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                            <small class="text-muted">
                                Maximum allowed:
                                {{ $claim->assessed_days ?? 0 }} days
                            </small>

                        </div>


                        {{-- Approval Remarks --}}
                        <div class="mb-4">

                            <label class="form-label">
                                Approval Remarks
                            </label>

                            <textarea name="approval_remarks"
                                      rows="4"
                                      class="form-control @error('approval_remarks') is-invalid @enderror"
                                      placeholder="Enter approval remarks">{{ old(
                                          'approval_remarks',
                                          $claim->approval_remarks
                                      ) }}</textarea>

                            @error('approval_remarks')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Buttons --}}
                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route(
                                'admin.projects.construction.claims.show',
                                [
                                    'project' => $project,
                                    'claim' => $claim,
                                ]
                            ) }}"
                               class="btn btn-light border">

                                Cancel

                            </a>


                            <button type="submit"
                                    class="btn btn-success">

                                <i class="bi bi-check-circle me-1"></i>
                                Approve Claim

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>


        {{-- Claim Summary --}}
        <div class="col-lg-4">

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white">

                    <h6 class="mb-0">
                        Claim Summary
                    </h6>

                </div>


                <div class="card-body">

                    <div class="mb-3">

                        <small class="text-muted">
                            Claim Number
                        </small>

                        <div class="fw-semibold">
                            {{ $claim->claim_number }}
                        </div>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted">
                            Claim Type
                        </small>

                        <div>
                            {{ $claim->claim_type }}
                        </div>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted">
                            Claimant
                        </small>

                        <div>
                            {{ $claim->claimant_name ?: '-' }}
                        </div>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted">
                            Claimed Amount
                        </small>

                        <div class="fw-semibold">
                            ${{ number_format(
                                (float) ($claim->claimed_amount ?? 0),
                                2
                            ) }}
                        </div>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted">
                            Assessed Amount
                        </small>

                        <div class="fw-semibold">
                            ${{ number_format(
                                (float) ($claim->assessed_amount ?? 0),
                                2
                            ) }}
                        </div>

                    </div>


                    <div>

                        <small class="text-muted">
                            Status
                        </small>

                        <div class="mt-1">

                            <span class="badge bg-warning text-dark">
                                {{ $claim->status }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Approval Information --}}
            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <div class="d-flex">

                        <i class="bi bi-info-circle text-primary me-2"></i>

                        <div class="small text-muted">

                            The approved amount and approved days
                            cannot exceed the assessed values.

                            If the approved values are lower than
                            the assessed values, the claim will be
                            marked as <strong>Partially Approved</strong>.

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection