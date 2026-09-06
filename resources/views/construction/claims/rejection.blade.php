@extends('layouts.app')

@section('title', 'Reject Claim')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Reject Claim
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

        {{-- Rejection Form --}}
        <div class="col-lg-8">

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Rejection Details
                    </h5>

                </div>


                <div class="card-body">

                    <div class="alert alert-warning">

                        <div class="d-flex">

                            <i class="bi bi-exclamation-triangle me-2"></i>

                            <div>

                                <strong>Important:</strong>

                                Once rejected, the claim will move to
                                <strong>Rejected</strong> status.

                                The rejection reason will be recorded
                                in the claim history.

                            </div>

                        </div>

                    </div>


                    <form method="POST"
                          action="{{ route(
                              'admin.projects.construction.claims.reject',
                              [
                                  'project' => $project,
                                  'claim' => $claim,
                              ]
                          ) }}">

                        @csrf


                        {{-- Rejection Remarks --}}
                        <div class="mb-4">

                            <label class="form-label">

                                Rejection Remarks

                                <span class="text-danger">
                                    *
                                </span>

                            </label>

                            <textarea name="rejection_remarks"
                                      rows="6"
                                      class="form-control @error('rejection_remarks') is-invalid @enderror"
                                      placeholder="Enter the reason for rejecting this claim..."
                                      required>{{ old(
                                          'rejection_remarks',
                                          $claim->rejection_remarks
                                      ) }}</textarea>

                            @error('rejection_remarks')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                            <small class="text-muted">
                                Please provide a clear reason for rejection.
                            </small>

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
                                    class="btn btn-danger">

                                <i class="bi bi-x-circle me-1"></i>
                                Reject Claim

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
                            Current Status
                        </small>

                        <div class="mt-1">

                            <span class="badge bg-warning text-dark">
                                {{ $claim->status }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Rejection Information --}}
            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <div class="d-flex">

                        <i class="bi bi-info-circle text-primary me-2"></i>

                        <div class="small text-muted">

                            Rejection remarks are mandatory and will be
                            permanently recorded in the claim history
                            for audit purposes.

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection