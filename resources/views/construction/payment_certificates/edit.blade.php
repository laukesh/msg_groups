@extends('layouts.app')

@section('title', 'Edit Payment Certificate')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Edit Payment Certificate
            </h4>

            <div class="text-muted">

                {{ $payment_certificate->certificate_number }}
                |
                {{ $project->project_number }}

            </div>

        </div>


        <a href="{{ route(
            'admin.projects.construction.payment-certificates.show',
            [
                'project' => $project,
                'payment_certificate' => $payment_certificate
            ]
        ) }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left"></i>
            Back

        </a>

    </div>


    @if ($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form method="POST"
          action="{{ route(
              'admin.projects.construction.payment-certificates.update',
              [
                  'project' => $project,
                  'payment_certificate' => $payment_certificate
              ]
          ) }}">

        @csrf
        @method('PUT')


        <div class="row">

            <div class="col-lg-8">

                <div class="card shadow-sm mb-4">

                    <div class="card-header bg-white">
                        <strong>Certificate Details</strong>
                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="form-label">
                                    Certificate Number
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ $payment_certificate->certificate_number }}"
                                    readonly>

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    Certificate Date
                                </label>

                                <input
                                    type="date"
                                    name="certificate_date"
                                    class="form-control"
                                    value="{{ old(
                                        'certificate_date',
                                        optional(
                                            $payment_certificate->certificate_date
                                        )->format('Y-m-d')
                                    ) }}"
                                    required>

                            </div>


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

                                    @foreach($workOrders as $workOrder)

                                        <option
                                            value="{{ $workOrder->id }}"
                                            {{ old(
                                                'construction_work_order_id',
                                                $payment_certificate->construction_work_order_id
                                            ) == $workOrder->id ? 'selected' : '' }}>

                                            {{ $workOrder->work_order_number }}
                                            -
                                            {{ $workOrder->work_order_title }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


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

                                    @foreach($contracts as $contract)

                                        <option
                                            value="{{ $contract->id }}"
                                            {{ old(
                                                'procurement_contract_id',
                                                $payment_certificate->procurement_contract_id
                                            ) == $contract->id ? 'selected' : '' }}>

                                            {{ $contract->contract_number }}

                                            @if($contract->bidder_name)
                                                - {{ $contract->bidder_name }}
                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    Period From
                                </label>

                                <input
                                    type="date"
                                    name="period_from"
                                    class="form-control"
                                    value="{{ old(
                                        'period_from',
                                        optional(
                                            $payment_certificate->period_from
                                        )->format('Y-m-d')
                                    ) }}">

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    Period To
                                </label>

                                <input
                                    type="date"
                                    name="period_to"
                                    class="form-control"
                                    value="{{ old(
                                        'period_to',
                                        optional(
                                            $payment_certificate->period_to
                                        )->format('Y-m-d')
                                    ) }}">

                            </div>


                            @php
                                $amountFields = [
                                    [
                                        'name' => 'gross_amount',
                                        'label' => 'Gross Amount'
                                    ],
                                    [
                                        'name' => 'previous_certified_amount',
                                        'label' => 'Previous Certified Amount'
                                    ],
                                    [
                                        'name' => 'current_certified_amount',
                                        'label' => 'Current Certified Amount'
                                    ],
                                    [
                                        'name' => 'retention_amount',
                                        'label' => 'Retention'
                                    ],
                                    [
                                        'name' => 'advance_recovery',
                                        'label' => 'Advance Recovery'
                                    ],
                                    [
                                        'name' => 'other_deductions',
                                        'label' => 'Other Deductions'
                                    ],
                                ];
                            @endphp


                            @foreach($amountFields as $field)

                                <div class="col-md-6">

                                    <label class="form-label">
                                        {{ $field['label'] }}
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            $
                                        </span>

                                        <input
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            name="{{ $field['name'] }}"
                                            class="form-control amount-field"
                                            value="{{ old(
                                                $field['name'],
                                                $payment_certificate->{$field['name']}
                                            ) }}"
                                            required>

                                    </div>

                                </div>

                            @endforeach


                            <div class="col-12">

                                <label class="form-label">
                                    Remarks
                                </label>

                                <textarea
                                    name="remarks"
                                    class="form-control"
                                    rows="4">{{ old(
                                        'remarks',
                                        $payment_certificate->remarks
                                    ) }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-lg-4">

                <div class="card shadow-sm mb-4">

                    <div class="card-header bg-white">
                        <strong>Net Amount</strong>
                    </div>

                    <div class="card-body">

                        <div class="text-muted small">
                            Net Certified Amount
                        </div>

                        <div class="fs-2 fw-bold text-success">

                            $ <span id="net_display">0.00</span>

                        </div>

                    </div>

                </div>


                <button
                    type="submit"
                    class="btn btn-primary w-100">

                    <i class="bi bi-check-lg"></i>
                    Update Certificate

                </button>

            </div>

        </div>

    </form>

</div>


<script>

function calculateNet()
{
    let current =
        parseFloat(
            document.querySelector(
                '[name="current_certified_amount"]'
            ).value
        ) || 0;

    let retention =
        parseFloat(
            document.querySelector(
                '[name="retention_amount"]'
            ).value
        ) || 0;

    let advance =
        parseFloat(
            document.querySelector(
                '[name="advance_recovery"]'
            ).value
        ) || 0;

    let other =
        parseFloat(
            document.querySelector(
                '[name="other_deductions"]'
            ).value
        ) || 0;

    let net =
        current - retention - advance - other;

    document.getElementById('net_display')
        .innerText = net.toFixed(2);
}


document.querySelectorAll('.amount-field')
    .forEach(function(input) {

        input.addEventListener(
            'input',
            calculateNet
        );

    });


calculateNet();

</script>

@endsection