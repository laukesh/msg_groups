@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted">
                Contract Management
            </div>

            <h4 class="mb-1">
                Add Contract Claim
            </h4>

            <div class="text-muted">

                {{ $contract->contract_code }}

                <span class="mx-1">|</span>

                {{ $contract->contract_title }}

            </div>

        </div>


        <a href="{{ route(
            'admin.projects.contract-management.contracts.claims.index',
            [$project, $contract]
        ) }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>

            Back to Claims

        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- Validation Errors --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-semibold mb-2">
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
    {{-- Claim Form --}}
    {{-- ========================================================= --}}

    <form method="POST"
          action="{{ route(
              'admin.projects.contract-management.contracts.claims.store',
              [$project, $contract]
          ) }}">

        @csrf


        {{-- ===================================================== --}}
        {{-- Claim Information --}}
        {{-- ===================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Claim Information
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- Claim Date --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Claim Date
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="claim_date"
                               value="{{ old(
                                   'claim_date',
                                   now()->format('Y-m-d')
                               ) }}"
                               class="form-control @error('claim_date') is-invalid @enderror"
                               required>

                        @error('claim_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Claim Type --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Claim Type
                            <span class="text-danger">*</span>
                        </label>

                        <select name="claim_type"
                                class="form-select @error('claim_type') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Claim Type
                            </option>

                            @foreach([
                                'Additional Cost',
                                'Delay',
                                'Extension Related',
                                'Price Escalation',
                                'Unforeseen Conditions',
                                'Design Change',
                                'Loss & Expense',
                                'Payment Related',
                                'Material Escalation',
                                'Other',
                            ] as $type)

                                <option value="{{ $type }}"
                                    @selected(
                                        old('claim_type') === $type
                                    )>

                                    {{ $type }}

                                </option>

                            @endforeach

                        </select>

                        @error('claim_type')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Status --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Status
                            <span class="text-danger">*</span>
                        </label>

                        <select name="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required>

                            @foreach([
                                'Draft',
                                'Submitted',
                                'Under Review',
                                'Under Negotiation',
                                'Partially Approved',
                                'Approved',
                                'Rejected',
                                'Withdrawn',
                                'Closed',
                            ] as $status)

                                <option value="{{ $status }}"
                                    @selected(
                                        old(
                                            'status',
                                            'Submitted'
                                        ) === $status
                                    )>

                                    {{ $status }}

                                </option>

                            @endforeach

                        </select>

                        @error('status')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Title --}}

                    <div class="col-12">

                        <label class="form-label">
                            Claim Title
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="title"
                               value="{{ old('title') }}"
                               class="form-control @error('title') is-invalid @enderror"
                               placeholder="Enter claim title"
                               required>

                        @error('title')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Description --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Claim Description
                        </label>

                        <textarea name="description"
                                  rows="5"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Describe the claim...">{{ old('description') }}</textarea>

                        @error('description')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Reason --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Reason / Basis of Claim
                        </label>

                        <textarea name="reason"
                                  rows="5"
                                  class="form-control @error('reason') is-invalid @enderror"
                                  placeholder="Explain the contractual reason or basis...">{{ old('reason') }}</textarea>

                        @error('reason')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Financial Information --}}
        {{-- ===================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Financial Information
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- Claimed Amount --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Claimed Amount
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                {{ $contract->currency ?? 'INR' }}
                            </span>

                            <input type="number"
                                   name="claimed_amount"
                                   value="{{ old(
                                       'claimed_amount',
                                       0
                                   ) }}"
                                   class="form-control @error('claimed_amount') is-invalid @enderror"
                                   min="0"
                                   step="0.01"
                                   required>

                        </div>

                        @error('claimed_amount')

                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Currency --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Currency
                        </label>

                        <input type="text"
                               name="currency"
                               value="{{ old(
                                   'currency',
                                   $contract->currency ?? 'INR'
                               ) }}"
                               class="form-control @error('currency') is-invalid @enderror"
                               maxlength="10">

                        @error('currency')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Submitted By --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Submitted By Party
                        </label>

                        <input type="text"
                               name="submitted_by_party"
                               value="{{ old(
                                   'submitted_by_party',
                                   $contract->party_name
                               ) }}"
                               class="form-control @error('submitted_by_party') is-invalid @enderror">

                        @error('submitted_by_party')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Timeline --}}
        {{-- ===================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Claim Timeline
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- Submission Date --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Submission Date
                        </label>

                        <input type="date"
                               name="submission_date"
                               value="{{ old('submission_date') }}"
                               class="form-control @error('submission_date') is-invalid @enderror">

                        @error('submission_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Response Due Date --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Response Due Date
                        </label>

                        <input type="date"
                               name="response_due_date"
                               value="{{ old('response_due_date') }}"
                               class="form-control @error('response_due_date') is-invalid @enderror">

                        @error('response_due_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Remarks --}}
        {{-- ===================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Remarks
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Review Remarks
                        </label>

                        <textarea name="review_remarks"
                                  rows="4"
                                  class="form-control @error('review_remarks') is-invalid @enderror">{{ old('review_remarks') }}</textarea>

                        @error('review_remarks')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Resolution Remarks
                        </label>

                        <textarea name="resolution_remarks"
                                  rows="4"
                                  class="form-control @error('resolution_remarks') is-invalid @enderror">{{ old('resolution_remarks') }}</textarea>

                        @error('resolution_remarks')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Actions --}}
        {{-- ===================================================== --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a href="{{ route(
                'admin.projects.contract-management.contracts.claims.index',
                [$project, $contract]
            ) }}"
               class="btn btn-outline-secondary">

                Cancel

            </a>


            <button type="submit"
                    class="btn btn-primary">

                <i class="bi bi-check-lg me-1"></i>

                Create Claim

            </button>

        </div>

    </form>

</div>

@endsection