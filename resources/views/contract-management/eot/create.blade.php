@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted">
                Contract Management
            </div>

            <h4 class="mb-1">
                Add Extension of Time
            </h4>

            <div class="text-muted">

                {{ $contract->contract_code }}

                <span class="mx-1">|</span>

                {{ $contract->contract_title }}

            </div>

        </div>


        <a href="{{ route(
            'admin.projects.contract-management.contracts.eot.index',
            [$project, $contract]
        ) }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>

            Back to EOT

        </a>

    </div>


    {{-- Validation Errors --}}

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


    <form method="POST"
          action="{{ route(
              'admin.projects.contract-management.contracts.eot.store',
              [$project, $contract]
          ) }}">

        @csrf


        {{-- ===================================================== --}}
        {{-- EOT Information --}}
        {{-- ===================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    EOT Request Information
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
                               value="{{ old(
                                   'request_date',
                                   now()->format('Y-m-d')
                               ) }}"
                               class="form-control @error('request_date') is-invalid @enderror"
                               required>

                        @error('request_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Reason --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Delay Reason
                            <span class="text-danger">*</span>
                        </label>

                        <select name="reason_type"
                                class="form-select @error('reason_type') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Reason
                            </option>

                            @foreach([
                                'Employer Delay',
                                'Design Delay',
                                'Approval Delay',
                                'Site Condition',
                                'Force Majeure',
                                'Material Delay',
                                'Procurement Delay',
                                'Contractor Related',
                                'Utility Delay',
                                'Weather',
                                'Statutory / Authority',
                                'Other',
                            ] as $reason)

                                <option value="{{ $reason }}"
                                    @selected(
                                        old('reason_type') === $reason
                                    )>

                                    {{ $reason }}

                                </option>

                            @endforeach

                        </select>

                        @error('reason_type')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Requested Days --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Requested Extension
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">

                            <input type="number"
                                   name="requested_days"
                                   value="{{ old(
                                       'requested_days',
                                       0
                                   ) }}"
                                   min="0"
                                   class="form-control @error('requested_days') is-invalid @enderror"
                                   required>

                            <span class="input-group-text">
                                Days
                            </span>

                        </div>

                        @error('requested_days')

                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Title --}}

                    <div class="col-12">

                        <label class="form-label">
                            EOT Title
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="title"
                               value="{{ old('title') }}"
                               class="form-control @error('title') is-invalid @enderror"
                               placeholder="Enter EOT request title"
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
                            Delay Description
                        </label>

                        <textarea name="description"
                                  rows="5"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Describe the delay event...">{{ old('description') }}</textarea>

                        @error('description')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Submitted By --}}

                    <div class="col-md-6">

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
        {{-- Delay Period --}}
        {{-- ===================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Delay Period
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    <div class="col-md-4">

                        <label class="form-label">
                            Delay Start Date
                        </label>

                        <input type="date"
                               name="delay_start_date"
                               value="{{ old('delay_start_date') }}"
                               class="form-control @error('delay_start_date') is-invalid @enderror">

                        @error('delay_start_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Delay End Date
                        </label>

                        <input type="date"
                               name="delay_end_date"
                               value="{{ old('delay_end_date') }}"
                               class="form-control @error('delay_end_date') is-invalid @enderror">

                        @error('delay_end_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Submission Date
                        </label>

                        <input type="date"
                               name="submission_date"
                               value="{{ old(
                                   'submission_date',
                                   now()->format('Y-m-d')
                               ) }}"
                               class="form-control @error('submission_date') is-invalid @enderror">

                        @error('submission_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Review --}}
        {{-- ===================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Review Information
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


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
                                  rows="5"
                                  class="form-control @error('review_remarks') is-invalid @enderror">{{ old('review_remarks') }}</textarea>

                        @error('review_remarks')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Decision Remarks
                        </label>

                        <textarea name="decision_remarks"
                                  rows="5"
                                  class="form-control @error('decision_remarks') is-invalid @enderror">{{ old('decision_remarks') }}</textarea>

                        @error('decision_remarks')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- Actions --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a href="{{ route(
                'admin.projects.contract-management.contracts.eot.index',
                [$project, $contract]
            ) }}"
               class="btn btn-outline-secondary">

                Cancel

            </a>


            <button type="submit"
                    class="btn btn-primary">

                <i class="bi bi-check-lg me-1"></i>

                Create EOT Request

            </button>

        </div>

    </form>

</div>

@endsection