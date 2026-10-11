@extends('layouts.app')

@section('title', 'Create Construction Claim')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Create Construction Claim
            </h4>

            <div class="text-muted">
                {{ $project->project_number }}
                -
                {{ $project->project_name }}
            </div>

        </div>

        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.projects.construction.claims.index',
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


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following:
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


    <form method="POST"
          action="{{ route(
              'admin.projects.construction.claims.store',
              $project
          ) }}">

        @csrf


        <div class="row">

            <div class="col-lg-8">

                {{-- Claim Details --}}
                <div class="card shadow-sm mb-4">

                    <div class="card-header bg-white">
                        <strong>Claim Details</strong>
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

                                    @foreach($workOrders as $workOrder)

                                        <option
                                            value="{{ $workOrder->id }}"
                                            data-contract="{{ $workOrder->procurement_contract_id }}"
                                            {{ old('construction_work_order_id') == $workOrder->id ? 'selected' : '' }}>

                                            {{ $workOrder->work_order_number }}
                                            -
                                            {{ $workOrder->work_order_title }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Contract --}}
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
                                            {{ old('procurement_contract_id') == $contract->id ? 'selected' : '' }}>

                                            {{ $contract->contract_number }}

                                            @if($contract->bidder_name)
                                                - {{ $contract->bidder_name }}
                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Claim Type --}}
                            <div class="col-md-4">

                                <label class="form-label">
                                    Claim Type
                                </label>

                                <select
                                    name="claim_type"
                                    class="form-select"
                                    required>

                                    <option value="">
                                        Select Claim Type
                                    </option>

                                    @foreach([
                                        'Variation',
                                        'Delay',
                                        'Extension of Time',
                                        'Additional Cost',
                                        'Price Escalation',
                                        'Loss and Expense',
                                        'Other'
                                    ] as $type)

                                        <option
                                            value="{{ $type }}"
                                            {{ old('claim_type') == $type ? 'selected' : '' }}>

                                            {{ $type }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Priority --}}
                            <div class="col-md-4">

                                <label class="form-label">
                                    Priority
                                </label>

                                <select
                                    name="priority"
                                    class="form-select"
                                    required>

                                    @foreach([
                                        'Low',
                                        'Medium',
                                        'High',
                                        'Critical'
                                    ] as $priority)

                                        <option
                                            value="{{ $priority }}"
                                            {{ old('priority', 'Medium') == $priority ? 'selected' : '' }}>

                                            {{ $priority }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Claim Date --}}
                            <div class="col-md-4">

                                <label class="form-label">
                                    Claim Date
                                </label>

                                <input
                                    type="date"
                                    name="claim_date"
                                    class="form-control"
                                    value="{{ old(
                                        'claim_date',
                                        now()->format('Y-m-d')
                                    ) }}"
                                    required>

                            </div>


                            {{-- Event Date --}}
                            <div class="col-md-4">

                                <label class="form-label">
                                    Event Date
                                </label>

                                <input
                                    type="date"
                                    name="event_date"
                                    class="form-control"
                                    value="{{ old('event_date') }}">

                            </div>


                            {{-- Claimant Type --}}
                            <div class="col-md-4">

                                <label class="form-label">
                                    Claimant Type
                                </label>

                                <select
                                    name="claimant_type"
                                    class="form-select"
                                    required>

                                    @foreach([
                                        'Contractor',
                                        'Consultant',
                                        'Client',
                                        'Other'
                                    ] as $type)

                                        <option
                                            value="{{ $type }}"
                                            {{ old('claimant_type', 'Contractor') == $type ? 'selected' : '' }}>

                                            {{ $type }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Claimant --}}
                            <div class="col-md-4">

                                <label class="form-label">
                                    Claimant Name
                                </label>

                                <input
                                    type="text"
                                    name="claimant_name"
                                    class="form-control"
                                    value="{{ old('claimant_name') }}"
                                    placeholder="Contractor / Consultant">

                            </div>


                            {{-- Subject --}}
                            <div class="col-12">

                                <label class="form-label">
                                    Claim Subject
                                </label>

                                <input
                                    type="text"
                                    name="subject"
                                    class="form-control"
                                    value="{{ old('subject') }}"
                                    placeholder="Enter claim subject"
                                    required>

                            </div>


                            {{-- Description --}}
                            <div class="col-12">

                                <label class="form-label">
                                    Description
                                </label>

                                <textarea
                                    name="description"
                                    class="form-control"
                                    rows="4"
                                    placeholder="Describe the claim event and impact">{{ old('description') }}</textarea>

                            </div>


                            {{-- Justification --}}
                            <div class="col-12">

                                <label class="form-label">
                                    Justification
                                </label>

                                <textarea
                                    name="justification"
                                    class="form-control"
                                    rows="4"
                                    placeholder="Explain the contractual justification">{{ old('justification') }}</textarea>

                            </div>


                            {{-- Claimed Amount --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Claimed Amount
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        $
                                    </span>

                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        name="claimed_amount"
                                        class="form-control"
                                        value="{{ old('claimed_amount', 0) }}">

                                </div>

                            </div>


                            {{-- Claimed Days --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Claimed Extension / Delay Days
                                </label>

                                <input
                                    type="number"
                                    min="0"
                                    name="claimed_days"
                                    class="form-control"
                                    value="{{ old('claimed_days', 0) }}">

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


            <div class="col-lg-4">

                <div class="card shadow-sm mb-4">

                    <div class="card-header bg-white">
                        <strong>Claim Summary</strong>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <div class="text-muted small">
                                Claimed Amount
                            </div>

                            <div class="fs-4 fw-bold">
                                $
                                <span id="claimed_amount_display">
                                    0.00
                                </span>
                            </div>

                        </div>


                        <div class="mb-3">

                            <div class="text-muted small">
                                Claimed Days
                            </div>

                            <div class="fs-4 fw-bold">
                                <span id="claimed_days_display">
                                    0
                                </span>
                                days
                            </div>

                        </div>

                    </div>

                </div>


                <div class="card shadow-sm">

                    <div class="card-body">

                        <button
                            type="submit"
                            class="btn btn-primary w-100">

                            <i class="bi bi-check-lg"></i>
                            Create Claim

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>


<script>

document
    .querySelector('[name="claimed_amount"]')
    .addEventListener('input', function () {

        let value =
            parseFloat(this.value) || 0;

        document.getElementById(
            'claimed_amount_display'
        ).innerText =
            value.toFixed(2);

    });


document
    .querySelector('[name="claimed_days"]')
    .addEventListener('input', function () {

        let value =
            parseInt(this.value) || 0;

        document.getElementById(
            'claimed_days_display'
        ).innerText =
            value;

    });


/*
|--------------------------------------------------------------------------
| Work Order → Contract
|--------------------------------------------------------------------------
*/

document
    .getElementById('work_order_id')
    .addEventListener('change', function () {

        let option =
            this.options[this.selectedIndex];

        let contractId =
            option.getAttribute('data-contract');

        if (!contractId) {
            return;
        }

        let contractSelect =
            document.getElementById(
                'procurement_contract_id'
            );

        let exists =
            Array.from(
                contractSelect.options
            ).some(function (item) {

                return item.value == contractId;

            });

        if (exists) {

            contractSelect.value =
                contractId;

        }

    });

</script>

@endsection