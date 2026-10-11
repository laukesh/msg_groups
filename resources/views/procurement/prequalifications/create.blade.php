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
                Create Prequalification
            </h4>

            <div class="text-muted">

                {{ $procurementTender->tender_number }}
                -
                {{ $procurementTender->tender_title }}

            </div>

        </div>


        <a href="{{ route(
            'admin.projects.procurement.tenders.prequalifications.index',
            [
                'project' => $project,
                'procurementTender' => $procurementTender,
            ]
        ) }}"
           class="btn btn-outline-secondary">

            Back

        </a>

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
        TENDER INFORMATION
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Tender Information</strong>
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Tender Number
                    </div>

                    <div class="fw-semibold">
                        {{ $procurementTender->tender_number }}
                    </div>

                </div>


                <div class="col-md-5">

                    <div class="text-muted small">
                        Tender Title
                    </div>

                    <div class="fw-semibold">
                        {{ $procurementTender->tender_title }}
                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Package
                    </div>

                    <div class="fw-semibold">

                        {{ $procurementTender->procurementPackage->package_number ?? '—' }}

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
              'admin.projects.procurement.tenders.prequalifications.store',
              [
                  'project' => $project,
                  'procurementTender' => $procurementTender,
              ]
          ) }}">

        @csrf


        {{-- =====================================================
            PREQUALIFICATION DETAILS
        ====================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Prequalification Assessment</strong>
            </div>


            <div class="card-body">

                <div class="row g-4">


                    {{-- Bidder --}}
                    <div class="col-md-6">

                        <label class="form-label">

                            Bidder

                            <span class="text-danger">*</span>

                        </label>


                        @if($selectedTenderBidder)

                            <input type="hidden"
                                   name="procurement_tender_bidder_id"
                                   value="{{ $selectedTenderBidder->id }}">


                            <div class="form-control bg-light">

                                <strong>

                                    {{
                                        $selectedTenderBidder
                                            ->bidder
                                            ->company_name
                                    }}

                                </strong>

                                <span class="text-muted ms-2">

                                    {{
                                        $selectedTenderBidder
                                            ->bidder
                                            ->bidder_code
                                    }}

                                </span>

                            </div>

                        @else

                            <select name="procurement_tender_bidder_id"
                                    class="form-select @error('procurement_tender_bidder_id') is-invalid @enderror"
                                    required>

                                <option value="">
                                    Select Bidder
                                </option>


                                @foreach($availableBidders as $tenderBidder)

                                    <option
                                        value="{{ $tenderBidder->id }}"
                                        @selected(
                                            old(
                                                'procurement_tender_bidder_id'
                                            ) == $tenderBidder->id
                                        )
                                    >

                                        {{
                                            $tenderBidder
                                                ->bidder
                                                ->company_name
                                        }}

                                        -
                                        {{
                                            $tenderBidder
                                                ->bidder
                                                ->bidder_code
                                        }}

                                    </option>

                                @endforeach

                            </select>


                            @error('procurement_tender_bidder_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        @endif

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
                                    old('status', 'Draft') === 'Draft'
                                )>

                                Draft

                            </option>


                            <option value="Submitted"
                                @selected(
                                    old('status') === 'Submitted'
                                )>

                                Submitted

                            </option>


                            <option value="Under Evaluation"
                                @selected(
                                    old('status') === 'Under Evaluation'
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


                    {{-- Submission Date --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Submission Date
                        </label>

                        <input type="date"
                               name="submission_date"
                               class="form-control @error('submission_date') is-invalid @enderror"
                               value="{{ old('submission_date') }}">

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
                               value="{{ old('evaluation_date') }}">

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
                               value="{{ old('evaluator_user_id') }}">

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
                               value="{{ old('evaluator_name') }}"
                               maxlength="255">

                        @error('evaluator_name')

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
                                  rows="4"
                                  class="form-control @error('evaluation_summary') is-invalid @enderror">{{ old('evaluation_summary') }}</textarea>

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
                                  rows="4"
                                  class="form-control @error('remarks') is-invalid @enderror">{{ old('remarks') }}</textarea>

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

        <div class="d-flex justify-content-end gap-2">

            <a href="{{ route(
                'admin.projects.procurement.tenders.prequalifications.index',
                [
                    'project' => $project,
                    'procurementTender' => $procurementTender,
                ]
            ) }}"
               class="btn btn-outline-secondary">

                Cancel

            </a>


            <button type="submit"
                    class="btn btn-primary">

                Create Prequalification

            </button>

        </div>

    </form>

</div>

@endsection