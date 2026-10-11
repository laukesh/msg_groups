@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Create Technical Evaluation
            </h4>

            <div class="text-muted">
                Tender:
                <strong>
                    {{ $procurementTender->tender_number ?? $procurementTender->id }}
                </strong>
            </div>
        </div>

        <a href="{{ route(
            'admin.projects.procurement.tenders.technical-evaluations.index',
            [
                'project' => $project,
                'procurementTender' => $procurementTender,
            ]
        ) }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Evaluations

        </a>

    </div>


    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>
    @endif


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- Create Form --}}
    <div class="card shadow-sm">

        <div class="card-header">

            <h5 class="mb-0">
                Technical Evaluation Details
            </h5>

        </div>


        <div class="card-body">

            <form method="POST"
                  action="{{ route(
                      'admin.projects.procurement.tenders.technical-evaluations.store',
                      [
                          'project' => $project,
                          'procurementTender' => $procurementTender,
                      ]
                  ) }}">

                @csrf


                {{-- Tender Information --}}
                <div class="row mb-4">

                    <div class="col-md-6">

                        <label class="form-label">
                            Tender
                        </label>

                        <input type="text"
                               class="form-control"
                               value="{{ $procurementTender->tender_number ?? ('Tender #' . $procurementTender->id) }}"
                               readonly>

                    </div>


                    <div class="col-md-6">

                        <label for="procurement_tender_submission_id"
                               class="form-label">

                            Tender Submission
                            <span class="text-danger">*</span>

                        </label>

                        <select name="procurement_tender_submission_id"
                                id="procurement_tender_submission_id"
                                class="form-select @error('procurement_tender_submission_id') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Tender Submission
                            </option>

                            @foreach($availableSubmissions as $submission)

                                @php
                                    $bidder = optional(
                                        optional($submission->tenderBidder)->bidder
                                    );

                                    $bidderName =
                                        $bidder->name
                                        ?? $bidder->company_name
                                        ?? 'Unknown Bidder';
                                @endphp

                                <option value="{{ $submission->id }}"
                                    {{ old('procurement_tender_submission_id') == $submission->id ? 'selected' : '' }}>

                                    {{ $submission->submission_number
                                        ?? ('Submission #' . $submission->id) }}

                                    -
                                    {{ $bidderName }}

                                </option>

                            @endforeach

                        </select>

                        @error('procurement_tender_submission_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        @if($availableSubmissions->isEmpty())

                            <div class="form-text text-danger">
                                No eligible tender submissions are available for technical evaluation.
                            </div>

                        @endif

                    </div>

                </div>


                {{-- Evaluation Information --}}
                <div class="row mb-4">

                    <div class="col-md-4">

                        <label for="evaluation_number"
                               class="form-label">

                            Evaluation Number

                        </label>

                        <input type="text"
                               name="evaluation_number"
                               id="evaluation_number"
                               class="form-control @error('evaluation_number') is-invalid @enderror"
                               value="{{ old('evaluation_number') }}"
                               placeholder="Auto-generated if blank">

                        @error('evaluation_number')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label for="evaluation_date"
                               class="form-label">

                            Evaluation Date
                            <span class="text-danger">*</span>

                        </label>

                        <input type="date"
                               name="evaluation_date"
                               id="evaluation_date"
                               class="form-control @error('evaluation_date') is-invalid @enderror"
                               value="{{ old('evaluation_date', now()->format('Y-m-d')) }}"
                               required>

                        @error('evaluation_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label for="evaluator_name"
                               class="form-label">

                            Evaluator Name

                        </label>

                        <input type="text"
                               name="evaluator_name"
                               id="evaluator_name"
                               class="form-control @error('evaluator_name') is-invalid @enderror"
                               value="{{ old('evaluator_name') }}"
                               maxlength="255">

                        @error('evaluator_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                {{-- Score --}}
                <div class="row mb-4">

                    <div class="col-md-4">

                        <label for="technical_score"
                               class="form-label">

                            Technical Score
                            <span class="text-danger">*</span>

                        </label>

                        <input type="number"
                               name="technical_score"
                               id="technical_score"
                               class="form-control @error('technical_score') is-invalid @enderror"
                               value="{{ old('technical_score') }}"
                               min="0"
                               step="0.01"
                               required>

                        @error('technical_score')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label for="maximum_score"
                               class="form-label">

                            Maximum Score
                            <span class="text-danger">*</span>

                        </label>

                        <input type="number"
                               name="maximum_score"
                               id="maximum_score"
                               class="form-control @error('maximum_score') is-invalid @enderror"
                               value="{{ old('maximum_score', 100) }}"
                               min="0.01"
                               step="0.01"
                               required>

                        @error('maximum_score')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label for="passing_score"
                               class="form-label">

                            Passing Score
                            <span class="text-danger">*</span>

                        </label>

                        <input type="number"
                               name="passing_score"
                               id="passing_score"
                               class="form-control @error('passing_score') is-invalid @enderror"
                               value="{{ old('passing_score', 60) }}"
                               min="0"
                               step="0.01"
                               required>

                        @error('passing_score')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                {{-- Compliance / Status --}}
                <div class="row mb-4">

                    <div class="col-md-6">

                        <label for="technical_compliance"
                               class="form-label">

                            Technical Compliance

                        </label>

                        <select name="technical_compliance"
                                id="technical_compliance"
                                class="form-select @error('technical_compliance') is-invalid @enderror">

                            <option value="">
                                Select Compliance
                            </option>

                            <option value="Compliant"
                                {{ old('technical_compliance') === 'Compliant' ? 'selected' : '' }}>
                                Compliant
                            </option>

                            <option value="Partially Compliant"
                                {{ old('technical_compliance') === 'Partially Compliant' ? 'selected' : '' }}>
                                Partially Compliant
                            </option>

                            <option value="Non-Compliant"
                                {{ old('technical_compliance') === 'Non-Compliant' ? 'selected' : '' }}>
                                Non-Compliant
                            </option>

                        </select>

                        @error('technical_compliance')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="col-md-6">

                        <label for="status"
                               class="form-label">

                            Status
                            <span class="text-danger">*</span>

                        </label>

                        <select name="status"
                                id="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Status
                            </option>

                            <option value="Draft"
                                {{ old('status') === 'Draft' ? 'selected' : '' }}>
                                Draft
                            </option>

                            <option value="Under Review"
                                {{ old('status', 'Under Review') === 'Under Review' ? 'selected' : '' }}>
                                Under Review
                            </option>

                            <option value="Completed"
                                {{ old('status') === 'Completed' ? 'selected' : '' }}>
                                Completed
                            </option>

                            <option value="Rejected"
                                {{ old('status') === 'Rejected' ? 'selected' : '' }}>
                                Rejected
                            </option>

                        </select>

                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                {{-- Strengths / Weaknesses --}}
                <div class="row mb-4">

                    <div class="col-md-6">

                        <label for="strengths"
                               class="form-label">

                            Strengths

                        </label>

                        <textarea name="strengths"
                                  id="strengths"
                                  rows="5"
                                  class="form-control @error('strengths') is-invalid @enderror">{{ old('strengths') }}</textarea>

                        @error('strengths')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="col-md-6">

                        <label for="weaknesses"
                               class="form-label">

                            Weaknesses

                        </label>

                        <textarea name="weaknesses"
                                  id="weaknesses"
                                  rows="5"
                                  class="form-control @error('weaknesses') is-invalid @enderror">{{ old('weaknesses') }}</textarea>

                        @error('weaknesses')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                {{-- Summary --}}
                <div class="mb-4">

                    <label for="evaluation_summary"
                           class="form-label">

                        Evaluation Summary

                    </label>

                    <textarea name="evaluation_summary"
                              id="evaluation_summary"
                              rows="5"
                              class="form-control @error('evaluation_summary') is-invalid @enderror">{{ old('evaluation_summary') }}</textarea>

                    @error('evaluation_summary')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Remarks --}}
                <div class="mb-4">

                    <label for="remarks"
                           class="form-label">

                        Remarks

                    </label>

                    <textarea name="remarks"
                              id="remarks"
                              rows="4"
                              class="form-control @error('remarks') is-invalid @enderror">{{ old('remarks') }}</textarea>

                    @error('remarks')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Actions --}}
                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route(
                        'admin.projects.procurement.tenders.technical-evaluations.index',
                        [
                            'project' => $project,
                            'procurementTender' => $procurementTender,
                        ]
                    ) }}"
                       class="btn btn-outline-secondary">

                        Cancel

                    </a>

                    <button type="submit"
                            class="btn btn-primary"
                            {{ $availableSubmissions->isEmpty() ? 'disabled' : '' }}>

                        <i class="bi bi-check-circle me-1"></i>
                        Create Technical Evaluation

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection