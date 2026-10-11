@extends('layouts.app')

@section('title', 'Edit Claim')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Edit Claim
            </h4>

            <div class="text-muted">
                {{ $project->project_number ?? $project->project_code }}
                -
                {{ $project->project_name }}
            </div>
        </div>

        <div>
            <a href="{{ route('admin.projects.construction.claims.show', [$project, $claim]) }}"
               class="btn btn-outline-primary me-2">
                <i class="bi bi-eye"></i>
                View Claim
            </a>

            <a href="{{ route('admin.projects.construction.claims.index', $project) }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>
        </div>

    </div>


    {{-- Validation Errors --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <form method="POST"
          action="{{ route('admin.projects.construction.claims.update', [$project, $claim]) }}">

        @csrf
        @method('PUT')


        {{-- Basic Information --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">
                <h5 class="mb-0">
                    Claim Information
                </h5>
            </div>

            <div class="card-body">

                <div class="row g-3">

                    {{-- Claim Number --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Claim Number
                        </label>

                        <input type="text"
                               class="form-control"
                               value="{{ $claim->claim_number }}"
                               readonly>

                    </div>


                    {{-- Claim Type --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Claim Type <span class="text-danger">*</span>
                        </label>

                        <select name="claim_type"
                                class="form-select @error('claim_type') is-invalid @enderror"
                                required>

                            <option value="">Select Claim Type</option>

                            @foreach([
                                'Variation',
                                'Delay',
                                'Extension of Time',
                                'Additional Cost',
                                'Price Escalation',
                                'Loss and Expense',
                                'Other'
                            ] as $type)

                                <option value="{{ $type }}"
                                    {{ old('claim_type', $claim->claim_type) == $type ? 'selected' : '' }}>
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


                    {{-- Priority --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Priority <span class="text-danger">*</span>
                        </label>

                        <select name="priority"
                                class="form-select @error('priority') is-invalid @enderror"
                                required>

                            @foreach([
                                'Low',
                                'Medium',
                                'High',
                                'Critical'
                            ] as $priority)

                                <option value="{{ $priority }}"
                                    {{ old('priority', $claim->priority) == $priority ? 'selected' : '' }}>
                                    {{ $priority }}
                                </option>

                            @endforeach

                        </select>

                        @error('priority')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Claim Date --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Claim Date <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="claim_date"
                               class="form-control @error('claim_date') is-invalid @enderror"
                               value="{{ old('claim_date', optional($claim->claim_date)->format('Y-m-d')) }}"
                               required>

                        @error('claim_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Event Date --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Event Date
                        </label>

                        <input type="date"
                               name="event_date"
                               class="form-control @error('event_date') is-invalid @enderror"
                               value="{{ old('event_date', optional($claim->event_date)->format('Y-m-d')) }}">

                        @error('event_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Claimant Type --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Claimant Type <span class="text-danger">*</span>
                        </label>

                        <select name="claimant_type"
                                class="form-select @error('claimant_type') is-invalid @enderror"
                                required>

                            @foreach([
                                'Contractor',
                                'Consultant',
                                'Client',
                                'Other'
                            ] as $type)

                                <option value="{{ $type }}"
                                    {{ old('claimant_type', $claim->claimant_type) == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>

                            @endforeach

                        </select>

                        @error('claimant_type')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Claimant Name --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Claimant Name <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="claimant_name"
                               class="form-control @error('claimant_name') is-invalid @enderror"
                               value="{{ old('claimant_name', $claim->claimant_name) }}"
                               required>

                        @error('claimant_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Subject --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Subject <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="subject"
                               class="form-control @error('subject') is-invalid @enderror"
                               value="{{ old('subject', $claim->subject) }}"
                               required>

                        @error('subject')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- Project / Contract --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">
                <h5 class="mb-0">
                    Project & Contract Reference
                </h5>
            </div>

            <div class="card-body">

                <div class="row g-3">

                    {{-- Work Order --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Construction Work Order
                        </label>

                        <select name="construction_work_order_id"
                                id="construction_work_order_id"
                                class="form-select @error('construction_work_order_id') is-invalid @enderror">

                            <option value="">
                                Select Work Order
                            </option>

                            @foreach($workOrders as $workOrder)

                                <option value="{{ $workOrder->id }}"
                                        data-contract="{{ $workOrder->procurement_contract_id }}"
                                    {{ old(
                                        'construction_work_order_id',
                                        $claim->construction_work_order_id
                                    ) == $workOrder->id ? 'selected' : '' }}>

                                    {{ $workOrder->work_order_number }}
                                    @if($workOrder->work_order_title)
                                        - {{ $workOrder->work_order_title }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                        @error('construction_work_order_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Procurement Contract --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Procurement Contract
                        </label>

                        <select name="procurement_contract_id"
                                id="procurement_contract_id"
                                class="form-select @error('procurement_contract_id') is-invalid @enderror">

                            <option value="">
                                Select Procurement Contract
                            </option>

                            @foreach($procurementContracts as $contract)

                                <option value="{{ $contract->id }}"
                                    {{ old(
                                        'procurement_contract_id',
                                        $claim->procurement_contract_id
                                    ) == $contract->id ? 'selected' : '' }}>

                                    {{ $contract->contract_number }}

                                    @if($contract->bidder)
                                        -
                                        {{ $contract->bidder->bidder_name }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                        @error('procurement_contract_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <small class="text-muted">
                            Contract can be selected automatically from the Work Order.
                        </small>

                    </div>

                </div>

            </div>

        </div>


        {{-- Claim Description --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">
                <h5 class="mb-0">
                    Claim Description
                </h5>
            </div>

            <div class="card-body">

                <div class="mb-3">

                    <label class="form-label">
                        Description <span class="text-danger">*</span>
                    </label>

                    <textarea name="description"
                              rows="4"
                              class="form-control @error('description') is-invalid @enderror"
                              required>{{ old('description', $claim->description) }}</textarea>

                    @error('description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Justification
                    </label>

                    <textarea name="justification"
                              rows="4"
                              class="form-control @error('justification') is-invalid @enderror">{{ old('justification', $claim->justification) }}</textarea>

                    @error('justification')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

        </div>


        {{-- Claim Amount --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">
                <h5 class="mb-0">
                    Claim Amount & Time
                </h5>
            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-3">

                        <label class="form-label">
                            Claimed Amount ($)
                        </label>

                        <input type="number"
                               step="0.01"
                               min="0"
                               name="claimed_amount"
                               class="form-control @error('claimed_amount') is-invalid @enderror"
                               value="{{ old('claimed_amount', $claim->claimed_amount) }}">

                        @error('claimed_amount')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Claimed Days
                        </label>

                        <input type="number"
                               min="0"
                               name="claimed_days"
                               class="form-control @error('claimed_days') is-invalid @enderror"
                               value="{{ old('claimed_days', $claim->claimed_days) }}">

                        @error('claimed_days')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Assessed Amount ($)
                        </label>

                        <input type="number"
                               class="form-control"
                               value="{{ $claim->assessed_amount !== null ? number_format($claim->assessed_amount, 2) : '' }}"
                               readonly>

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Assessed Days
                        </label>

                        <input type="number"
                               class="form-control"
                               value="{{ $claim->assessed_days }}"
                               readonly>

                    </div>

                </div>

                <div class="alert alert-info mt-3 mb-0">

                    <i class="bi bi-info-circle"></i>

                    Assessment and approval values are managed through the
                    claim workflow and cannot be changed from this page.

                </div>

            </div>

        </div>


        {{-- Remarks --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">
                <h5 class="mb-0">
                    Remarks
                </h5>
            </div>

            <div class="card-body">

                <textarea name="remarks"
                          rows="3"
                          class="form-control">{{ old('remarks', $claim->remarks) }}</textarea>

            </div>

        </div>


        {{-- Footer --}}
        <div class="d-flex justify-content-end gap-2 mb-4">

            <a href="{{ route('admin.projects.construction.claims.show', [$project, $claim]) }}"
               class="btn btn-light">
                Cancel
            </a>

            <button type="submit"
                    class="btn btn-primary">

                <i class="bi bi-check-lg"></i>
                Update Claim

            </button>

        </div>

    </form>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const workOrderSelect = document.getElementById('construction_work_order_id');
    const contractSelect = document.getElementById('procurement_contract_id');

    if (!workOrderSelect || !contractSelect) {
        return;
    }

    workOrderSelect.addEventListener('change', function () {

        const selectedOption =
            this.options[this.selectedIndex];

        const contractId =
            selectedOption.getAttribute('data-contract');

        if (contractId) {
            contractSelect.value = contractId;
        }

    });

});

</script>

@endsection