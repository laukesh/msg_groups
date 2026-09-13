@extends('layouts.app')

@section('title', 'Create Payment Certificate')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Create Payment Certificate
            </h4>

            <div class="text-muted">
                {{ $project->project_number }}
                -
                {{ $project->project_name }}
            </div>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.construction.payment-certificates.index',
                $project
            ) }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left"></i>
                Back

            </a>

            <a href="{{ route(
                'admin.projects.construction.dashboard',
                $project
            ) }}"
               class="btn btn-outline-primary">

                <i class="bi bi-speedometer2"></i>
                Construction Dashboard

            </a>

        </div>

    </div>


    {{-- Validation Errors --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form method="POST"
          action="{{ route(
              'admin.projects.construction.payment-certificates.store',
              $project
          ) }}">

        @csrf


        <div class="row">

            {{-- LEFT --}}
            <div class="col-lg-8">

                {{-- Certificate Details --}}
                <div class="card shadow-sm mb-4">

                    <div class="card-header bg-white">
                        <strong>Certificate Details</strong>
                    </div>

                    <div class="card-body">

                        <div class="row g-3">


                            {{-- Work Order --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Work Order
                                </label>

                                <select
                                    name="construction_work_order_id"
                                    id="work_order_id"
                                    class="form-select">

                                    <option value="">
                                        Select Work Order
                                    </option>

                                    @foreach ($workOrders as $workOrder)

                                        <option
                                            value="{{ $workOrder->id }}"
                                            data-contract="{{ optional($workOrder->procurementContract)->id }}"
                                            data-value="{{ $workOrder->work_order_value }}"
                                            {{ old('construction_work_order_id') == $workOrder->id ? 'selected' : '' }}>

                                            {{ $workOrder->work_order_number }}
                                            -
                                            {{ $workOrder->work_order_title }}

                                        </option>

                                    @endforeach

                                </select>

                                <div class="form-text">
                                    Selecting a Work Order will automatically
                                    select its Procurement Contract.
                                </div>

                            </div>


                            {{-- Procurement Contract --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Procurement Contract
                                </label>

                                <select
                                    name="procurement_contract_id"
                                    id="procurement_contract_id"
                                    class="form-select">

                                    <option value="">
                                        Select Procurement Contract
                                    </option>

                                    @foreach ($contracts as $contract)

                                        <option
                                            value="{{ $contract->id }}"
                                            {{ old('procurement_contract_id') == $contract->id ? 'selected' : '' }}>

                                            {{ $contract->contract_number }}

                                            @if($contract->bidder_name)
                                                - {{ $contract->bidder_name }}
                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                                <div class="form-text">
                                    Contract must belong to this project.
                                </div>

                            </div>


                            {{-- Certificate Date --}}
                            <div class="col-md-4">

                                <label class="form-label">
                                    Certificate Date
                                </label>

                                <input
                                    type="date"
                                    name="certificate_date"
                                    class="form-control"
                                    value="{{ old(
                                        'certificate_date',
                                        now()->format('Y-m-d')
                                    ) }}"
                                    required>

                            </div>


                            {{-- Period From --}}
                            <div class="col-md-4">

                                <label class="form-label">
                                    Period From
                                </label>

                                <input
                                    type="date"
                                    name="period_from"
                                    class="form-control"
                                    value="{{ old('period_from') }}">

                            </div>


                            {{-- Period To --}}
                            <div class="col-md-4">

                                <label class="form-label">
                                    Period To
                                </label>

                                <input
                                    type="date"
                                    name="period_to"
                                    class="form-control"
                                    value="{{ old('period_to') }}">

                            </div>


                            {{-- Gross Amount --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Gross Amount
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        $
                                    </span>

                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        name="gross_amount"
                                        id="gross_amount"
                                        class="form-control"
                                        value="{{ old('gross_amount', 0) }}"
                                        required>

                                </div>

                            </div>


                            {{-- Previous Certified --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Previous Certified Amount
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        $
                                    </span>

                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        name="previous_certified_amount"
                                        class="form-control"
                                        value="{{ old(
                                            'previous_certified_amount',
                                            0
                                        ) }}"
                                        required>

                                </div>

                            </div>


                            {{-- Current Certified --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Current Certified Amount
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        $
                                    </span>

                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        name="current_certified_amount"
                                        id="current_certified_amount"
                                        class="form-control"
                                        value="{{ old(
                                            'current_certified_amount',
                                            0
                                        ) }}"
                                        required>

                                </div>

                            </div>


                            {{-- Retention --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Retention
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        $
                                    </span>

                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        name="retention_amount"
                                        id="retention_amount"
                                        class="form-control"
                                        value="{{ old(
                                            'retention_amount',
                                            0
                                        ) }}">

                                </div>

                            </div>


                            {{-- Advance Recovery --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Advance Recovery
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        $
                                    </span>

                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        name="advance_recovery"
                                        id="advance_recovery"
                                        class="form-control"
                                        value="{{ old(
                                            'advance_recovery',
                                            0
                                        ) }}">

                                </div>

                            </div>


                            {{-- Other Deductions --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Other Deductions
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        $
                                    </span>

                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        name="other_deductions"
                                        id="other_deductions"
                                        class="form-control"
                                        value="{{ old(
                                            'other_deductions',
                                            0
                                        ) }}">

                                </div>

                            </div>


                            {{-- Remarks --}}
                            <div class="col-12">

                                <label class="form-label">
                                    Remarks
                                </label>

                                <textarea
                                    name="remarks"
                                    class="form-control"
                                    rows="3">{{ old('remarks') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- RIGHT --}}
            <div class="col-lg-4">

                {{-- Summary --}}
                <div class="card shadow-sm mb-4">

                    <div class="card-header bg-white">
                        <strong>Certificate Summary</strong>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <div class="text-muted small">
                                Current Certified
                            </div>

                            <div class="fs-4 fw-bold">
                                $
                                <span id="current_display">
                                    0.00
                                </span>
                            </div>

                        </div>


                        <div class="mb-3">

                            <div class="text-muted small">
                                Total Deductions
                            </div>

                            <div class="fs-5 fw-semibold">
                                $
                                <span id="deduction_display">
                                    0.00
                                </span>
                            </div>

                        </div>


                        <hr>


                        <div>

                            <div class="text-muted small">
                                Net Certified Amount
                            </div>

                            <div class="fs-3 fw-bold text-success">
                                $
                                <span id="net_display">
                                    0.00
                                </span>
                            </div>

                        </div>

                    </div>

                </div>


                {{-- Submit --}}
                <div class="card shadow-sm">

                    <div class="card-body">

                        <button
                            type="submit"
                            class="btn btn-primary w-100">

                            <i class="bi bi-check-lg"></i>
                            Create Certificate

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>


<script>

function calculateCertificate()
{
    let current =
        parseFloat(
            document.getElementById(
                'current_certified_amount'
            ).value
        ) || 0;

    let retention =
        parseFloat(
            document.getElementById(
                'retention_amount'
            ).value
        ) || 0;

    let advance =
        parseFloat(
            document.getElementById(
                'advance_recovery'
            ).value
        ) || 0;

    let other =
        parseFloat(
            document.getElementById(
                'other_deductions'
            ).value
        ) || 0;


    let deductions =
        retention +
        advance +
        other;


    let net =
        current -
        deductions;


    document.getElementById(
        'current_display'
    ).innerText =
        current.toFixed(2);


    document.getElementById(
        'deduction_display'
    ).innerText =
        deductions.toFixed(2);


    document.getElementById(
        'net_display'
    ).innerText =
        net.toFixed(2);
}


document.querySelectorAll(
    '#current_certified_amount, ' +
    '#retention_amount, ' +
    '#advance_recovery, ' +
    '#other_deductions'
).forEach(function(element) {

    element.addEventListener(
        'input',
        calculateCertificate
    );

});


/*
|--------------------------------------------------------------------------
| WORK ORDER → PROCUREMENT CONTRACT
|--------------------------------------------------------------------------
|
| When a Work Order is selected, automatically select
| the Procurement Contract attached to that Work Order.
|
*/

document.getElementById('work_order_id')
    .addEventListener('change', function() {

        let option =
            this.options[this.selectedIndex];


        let contractId =
            option.getAttribute('data-contract');


        if (contractId) {

            let contractSelect =
                document.getElementById(
                    'procurement_contract_id'
                );


            let exists =
                Array.from(
                    contractSelect.options
                ).some(function(item) {

                    return item.value == contractId;

                });


            if (exists) {

                contractSelect.value =
                    contractId;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | WORK ORDER VALUE → GROSS AMOUNT
        |--------------------------------------------------------------------------
        */

        let workOrderValue =
            parseFloat(
                option.getAttribute('data-value')
            ) || 0;


        if (
            workOrderValue > 0 &&
            parseFloat(
                document.getElementById(
                    'gross_amount'
                ).value
            ) === 0
        ) {

            document.getElementById(
                'gross_amount'
            ).value =
                workOrderValue.toFixed(2);

        }

    });


calculateCertificate();

</script>

@endsection