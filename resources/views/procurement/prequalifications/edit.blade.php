@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="text-muted small mb-1">
                Procurement / Prequalification
            </div>

            <h4 class="mb-1">
                Edit Prequalification
            </h4>

            <div class="text-muted">

                {{ $prequalification->prequalification_no }}

                -

                {{
                    $prequalification
                        ->tenderBidder
                        ->bidder
                        ->company_name
                }}

            </div>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.procurement.tenders.prequalifications.show',
                [
                    'project' => $project,
                    'procurementTender' => $procurementTender,
                    'prequalification' => $prequalification,
                ]
            ) }}"
               class="btn btn-outline-secondary">

                Back

            </a>

        </div>

    </div>


    {{-- =========================================================
        MESSAGES
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
        PREQUALIFICATION INFORMATION
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Prequalification Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">

                {{-- Number --}}
                <div class="col-md-4">

                    <div class="text-muted small">
                        Prequalification No.
                    </div>

                    <div class="fw-semibold">

                        {{ $prequalification->prequalification_no }}

                    </div>

                </div>


                {{-- Tender --}}
                <div class="col-md-4">

                    <div class="text-muted small">
                        Tender
                    </div>

                    <div class="fw-semibold">

                        {{ $procurementTender->tender_number }}

                    </div>

                </div>


                {{-- Bidder --}}
                <div class="col-md-4">

                    <div class="text-muted small">
                        Bidder
                    </div>

                    <div class="fw-semibold">

                        {{
                            $prequalification
                                ->tenderBidder
                                ->bidder
                                ->company_name
                        }}

                    </div>

                    <div class="small text-muted">

                        {{
                            $prequalification
                                ->tenderBidder
                                ->bidder
                                ->bidder_code
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FORM
    ========================================================== --}}

    <form method="POST"
          action="{{ route(
              'admin.projects.procurement.tenders.prequalifications.update',
              [
                  'project' => $project,
                  'procurementTender' => $procurementTender,
                  'prequalification' => $prequalification,
              ]
          ) }}">

        @csrf

        @method('PUT')


        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Assessment Details
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-4">


                    {{-- Submission Date --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Submission Date
                        </label>

                        <input type="date"
                               name="submission_date"
                               class="form-control @error('submission_date') is-invalid @enderror"
                               value="{{ old(
                                   'submission_date',
                                   $prequalification->submission_date
                                       ? $prequalification->submission_date->format('Y-m-d')
                                       : ''
                               ) }}">

                        @error('submission_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Evaluation Date --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Evaluation Date
                        </label>

                        <input type="date"
                               name="evaluation_date"
                               class="form-control @error('evaluation_date') is-invalid @enderror"
                               value="{{ old(
                                   'evaluation_date',
                                   $prequalification->evaluation_date
                                       ? $prequalification->evaluation_date->format('Y-m-d')
                                       : ''
                               ) }}">

                        @error('evaluation_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Evaluator User --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Evaluator User ID
                        </label>

                        <input type="number"
                               name="evaluator_user_id"
                               class="form-control @error('evaluator_user_id') is-invalid @enderror"
                               value="{{ old(
                                   'evaluator_user_id',
                                   $prequalification->evaluator_user_id
                               ) }}">

                        @error('evaluator_user_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Evaluator Name --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Evaluator Name
                        </label>

                        <input type="text"
                               name="evaluator_name"
                               class="form-control @error('evaluator_name') is-invalid @enderror"
                               maxlength="255"
                               value="{{ old(
                                   'evaluator_name',
                                   $prequalification->evaluator_name
                               ) }}">

                        @error('evaluator_name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Status --}}
                    <div class="col-md-6">

                        <label class="form-label">

                            Status

                            <span class="text-danger">*</span>

                        </label>


                        <select name="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required>

                            <option value="Draft"
                                @selected(
                                    old(
                                        'status',
                                        $prequalification->status
                                    ) === 'Draft'
                                )>

                                Draft

                            </option>


                            <option value="Submitted"
                                @selected(
                                    old(
                                        'status',
                                        $prequalification->status
                                    ) === 'Submitted'
                                )>

                                Submitted

                            </option>


                            <option value="Under Evaluation"
                                @selected(
                                    old(
                                        'status',
                                        $prequalification->status
                                    ) === 'Under Evaluation'
                                )>

                                Under Evaluation

                            </option>

                        </select>


                        @error('status')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Evaluation Summary --}}
                    <div class="col-md-12">

                        <label class="form-label">
                            Evaluation Summary
                        </label>

                        <textarea name="evaluation_summary"
                                  rows="5"
                                  class="form-control @error('evaluation_summary') is-invalid @enderror">{{ old(
                                      'evaluation_summary',
                                      $prequalification->evaluation_summary
                                  ) }}</textarea>

                        @error('evaluation_summary')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Remarks --}}
                    <div class="col-md-12">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea name="remarks"
                                  rows="5"
                                  class="form-control @error('remarks') is-invalid @enderror">{{ old(
                                      'remarks',
                                      $prequalification->remarks
                                  ) }}</textarea>

                        @error('remarks')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            ACTIONS
        ====================================================== --}}

        <div class="d-flex justify-content-between">

            <a href="{{ route(
                'admin.projects.procurement.tenders.prequalifications.show',
                [
                    'project' => $project,
                    'procurementTender' => $procurementTender,
                    'prequalification' => $prequalification,
                ]
            ) }}"
               class="btn btn-outline-secondary">

                Cancel

            </a>


            <button type="submit"
                    class="btn btn-primary">

                Update Prequalification

            </button>

        </div>

    </form>

</div>

@endsection