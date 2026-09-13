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
                Edit Retention Entry
            </h4>

            <div class="text-muted">

                {{ $retention->retention_number }}

                <span class="mx-1">|</span>

                {{ $contract->contract_code }}

                <span class="mx-1">|</span>

                {{ $contract->contract_title }}

            </div>

        </div>


        <a href="{{ route(
            'admin.projects.contract-management.contracts.retentions.index',
            [$project, $contract]
        ) }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>

            Back

        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- Errors --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- Contract Terms --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Contract Retention Terms
            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">

                    <div class="text-muted small">
                        Retention Required
                    </div>

                    <div class="fs-5 fw-semibold">

                        @if($contract->retention_required)

                            <span class="text-success">
                                Yes
                            </span>

                        @else

                            <span class="text-secondary">
                                No
                            </span>

                        @endif

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Contract Retention %
                    </div>

                    <div class="fs-5 fw-semibold text-primary">

                        {{ number_format(
                            $contract->retention_percentage,
                            2
                        ) }}%

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Currency
                    </div>

                    <div class="fs-5 fw-semibold">

                        {{ $contract->currency ?? 'USD' }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Retention Number
                    </div>

                    <div class="fs-5 fw-semibold">
                        {{ $retention->retention_number }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    <form method="POST"
          action="{{ route(
              'admin.projects.contract-management.contracts.retentions.update',
              [$project, $contract, $retention]
          ) }}">

        @csrf

        @method('PUT')


        {{-- ===================================================== --}}
        {{-- Retention Details --}}
        {{-- ===================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Retention Details
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- Retention Number --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Retention Number
                        </label>

                        <input type="text"
                               value="{{ $retention->retention_number }}"
                               class="form-control"
                               readonly>

                    </div>


                    {{-- Date --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Retention Date
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="retention_date"
                               value="{{ old(
                                   'retention_date',
                                   $retention->retention_date?->format('Y-m-d')
                               ) }}"
                               class="form-control"
                               required>

                    </div>


                    {{-- Invoice --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Invoice Number
                        </label>

                        <input type="text"
                               name="invoice_number"
                               value="{{ old(
                                   'invoice_number',
                                   $retention->invoice_number
                               ) }}"
                               class="form-control">

                    </div>


                    {{-- Payment Reference --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Payment Reference
                        </label>

                        <input type="text"
                               name="payment_reference"
                               value="{{ old(
                                   'payment_reference',
                                   $retention->payment_reference
                               ) }}"
                               class="form-control">

                    </div>


                    {{-- Certified Amount --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Certified Amount
                            <span class="text-danger">*</span>
                        </label>

                        <input type="number"
                               name="certified_amount"
                               id="certified_amount"
                               value="{{ old(
                                   'certified_amount',
                                   $retention->certified_amount
                               ) }}"
                               min="0"
                               step="0.01"
                               class="form-control"
                               required>

                    </div>


                    {{-- Percentage --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Retention Percentage
                            <span class="text-danger">*</span>
                        </label>

                        <input type="number"
                               name="retention_percentage"
                               id="retention_percentage"
                               value="{{ old(
                                   'retention_percentage',
                                   $retention->retention_percentage
                               ) }}"
                               min="0"
                               max="100"
                               step="0.01"
                               class="form-control"
                               required>

                    </div>


                    {{-- Retention Amount --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Retention Amount
                        </label>

                        <input type="text"
                               id="retention_amount_display"
                               value="{{ number_format(
                                   $retention->retention_amount,
                                   2
                               ) }}"
                               class="form-control"
                               readonly>

                    </div>


                    {{-- Released --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Released Amount
                        </label>

                        <input type="number"
                               name="released_amount"
                               id="released_amount"
                               value="{{ old(
                                   'released_amount',
                                   $retention->released_amount
                               ) }}"
                               min="0"
                               step="0.01"
                               class="form-control">

                    </div>


                    {{-- Balance --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Balance Amount
                        </label>

                        <input type="text"
                               id="balance_amount_display"
                               value="{{ number_format(
                                   $retention->balance_amount,
                                   2
                               ) }}"
                               class="form-control"
                               readonly>

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
                                   $retention->currency
                               ) }}"
                               maxlength="10"
                               class="form-control">

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Release Details --}}
        {{-- ===================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Release Details
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- Expected Release --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Expected Release Date
                        </label>

                        <input type="date"
                               name="expected_release_date"
                               value="{{ old(
                                   'expected_release_date',
                                   $retention->expected_release_date?->format('Y-m-d')
                               ) }}"
                               class="form-control">

                    </div>


                    {{-- Release Date --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Release Date
                        </label>

                        <input type="date"
                               name="release_date"
                               value="{{ old(
                                   'release_date',
                                   $retention->release_date?->format('Y-m-d')
                               ) }}"
                               class="form-control">

                    </div>


                    {{-- Status --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            @foreach([
                                'Retained',
                                'Partially Released',
                                'Fully Released',
                                'Disputed',
                                'Cancelled',
                            ] as $status)

                                <option value="{{ $status }}"
                                    @selected(
                                        old(
                                            'status',
                                            $retention->status
                                        ) === $status
                                    )>

                                    {{ $status }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Release Remarks --}}

                    <div class="col-md-12">

                        <label class="form-label">
                            Release Remarks
                        </label>

                        <textarea name="release_remarks"
                                  rows="4"
                                  class="form-control">{{ old(
                                      'release_remarks',
                                      $retention->release_remarks
                                  ) }}</textarea>

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

                <textarea name="remarks"
                          rows="5"
                          class="form-control">{{ old(
                              'remarks',
                              $retention->remarks
                          ) }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Record Information --}}
        {{-- ===================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Record Information
                </h5>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">

                        <div class="text-muted small">
                            Created
                        </div>

                        <div>

                            {{
                                $retention->created_at
                                ?->format('d M Y H:i')
                                ?? '—'
                            }}

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="text-muted small">
                            Last Updated
                        </div>

                        <div>

                            {{
                                $retention->updated_at
                                ?->format('d M Y H:i')
                                ?? '—'
                            }}

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Actions --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a href="{{ route(
                'admin.projects.contract-management.contracts.retentions.index',
                [$project, $contract]
            ) }}"
               class="btn btn-outline-secondary">

                Cancel

            </a>


            <button type="submit"
                    class="btn btn-primary">

                <i class="bi bi-check-lg me-1"></i>

                Update Retention

            </button>

        </div>

    </form>

</div>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const certifiedAmount =
            document.getElementById(
                'certified_amount'
            );

        const percentage =
            document.getElementById(
                'retention_percentage'
            );

        const released =
            document.getElementById(
                'released_amount'
            );

        const retentionDisplay =
            document.getElementById(
                'retention_amount_display'
            );

        const balanceDisplay =
            document.getElementById(
                'balance_amount_display'
            );


        function calculate()
        {
            const certified =
                parseFloat(
                    certifiedAmount.value
                ) || 0;


            const rate =
                parseFloat(
                    percentage.value
                ) || 0;


            const releasedAmount =
                parseFloat(
                    released.value
                ) || 0;


            const retention =
                certified * rate / 100;


            const balance =
                Math.max(
                    0,
                    retention - releasedAmount
                );


            retentionDisplay.value =
                retention.toFixed(2);


            balanceDisplay.value =
                balance.toFixed(2);
        }


        certifiedAmount.addEventListener(
            'input',
            calculate
        );


        percentage.addEventListener(
            'input',
            calculate
        );


        released.addEventListener(
            'input',
            calculate
        );


        calculate();

    }
);

</script>

@endsection