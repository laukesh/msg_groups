@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project / Contract Strategy
            </div>

            <h3>
                Create Contract Strategy
            </h3>

            <div class="text-muted">
                {{ $project->project_name }}
            </div>

        </div>

        <a
            href="{{ route(
                'admin.projects.contract-strategy.index',
                ['project' => $project->id]
            ) }}"
            class="btn btn-outline-secondary"
        >
            ← Back
        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route(
            'admin.projects.contract-strategy.store',
            ['project' => $project->id]
        ) }}"
    >

        @csrf


        {{-- Basic Information --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Strategy Information</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Strategy Number
                        </label>

                        <input
                            type="text"
                            name="strategy_number"
                            class="form-control"
                            value="{{ old(
                                'strategy_number',
                                $strategyNumber
                            ) }}"
                            readonly
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Version
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="V{{ $nextVersion }}"
                            readonly
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Status
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="Draft"
                            readonly
                        >

                    </div>

                </div>


                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Title
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="title"
                            class="form-control"
                            value="{{ old('title') }}"
                            required
                        >

                    </div>


                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Contracting Model
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="contracting_model"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select
                            </option>

                            @foreach([
                                'Single Main Contract',
                                'Multiple Package Contracts',
                                'EPC',
                                'EPCM',
                                'Design-Build',
                                'Design-Bid-Build',
                                'Turnkey',
                                'Management Contract',
                                'Framework Agreement',
                                'Other',
                            ] as $model)

                                <option
                                    value="{{ $model }}"
                                    @selected(
                                        old('contracting_model') === $model
                                    )
                                >
                                    {{ $model }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Contract Type
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="contract_type"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select
                            </option>

                            @foreach([
                                'Lump Sum',
                                'Item Rate',
                                'Cost Plus',
                                'Time and Material',
                                'Target Cost',
                                'Guaranteed Maximum Price',
                                'Hybrid',
                                'Other',
                            ] as $type)

                                <option
                                    value="{{ $type }}"
                                    @selected(
                                        old('contract_type') === $type
                                    )
                                >
                                    {{ $type }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Effective Date
                        </label>

                        <input
                            type="date"
                            name="effective_date"
                            class="form-control"
                            value="{{ old('effective_date') }}"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- Commercial Model --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Commercial Model</strong>
            </div>

            <div class="card-body">

                <textarea
                    name="commercial_model"
                    rows="5"
                    class="form-control"
                    placeholder="Define the strategic commercial model..."
                >{{ old('commercial_model') }}</textarea>

            </div>

        </div>


        {{-- Contract Packaging --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Contract Packaging</strong>
            </div>

            <div class="card-body">

                <textarea
                    name="contract_packaging"
                    rows="6"
                    class="form-control"
                    placeholder="Define how contracts will be packaged..."
                >{{ old('contract_packaging') }}</textarea>

                <div class="form-text">
                    This defines the strategic contract packaging approach.
                    Actual contracts will be managed later in the Contracts domain.
                </div>

            </div>

        </div>


        {{-- Payment Strategy --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Payment Strategy</strong>
            </div>

            <div class="card-body">

                <textarea
                    name="payment_strategy"
                    rows="5"
                    class="form-control"
                    placeholder="Define the strategic payment approach..."
                >{{ old('payment_strategy') }}</textarea>

            </div>

        </div>


        {{-- Risk Allocation --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Risk Allocation Strategy</strong>
            </div>

            <div class="card-body">

                <textarea
                    name="risk_allocation_strategy"
                    rows="6"
                    class="form-control"
                    placeholder="Define allocation of major contractual risks..."
                >{{ old('risk_allocation_strategy') }}</textarea>

            </div>

        </div>


        {{-- Security / Retention / LD --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Contract Protections</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Performance Security
                        </label>

                        <textarea
                            name="performance_security_strategy"
                            rows="5"
                            class="form-control"
                        >{{ old(
                            'performance_security_strategy'
                        ) }}</textarea>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Retention
                        </label>

                        <textarea
                            name="retention_strategy"
                            rows="5"
                            class="form-control"
                        >{{ old(
                            'retention_strategy'
                        ) }}</textarea>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Liquidated Damages
                        </label>

                        <textarea
                            name="liquidated_damages_strategy"
                            rows="5"
                            class="form-control"
                        >{{ old(
                            'liquidated_damages_strategy'
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- Insurance --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Insurance Strategy</strong>
            </div>

            <div class="card-body">

                <textarea
                    name="insurance_strategy"
                    rows="5"
                    class="form-control"
                    placeholder="Define required insurance coverage..."
                >{{ old('insurance_strategy') }}</textarea>

            </div>

        </div>


        {{-- Variations / Claims / Disputes --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Change, Claims & Dispute Strategy</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Variation / Change Strategy
                        </label>

                        <textarea
                            name="variation_change_strategy"
                            rows="6"
                            class="form-control"
                        >{{ old(
                            'variation_change_strategy'
                        ) }}</textarea>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Claims Strategy
                        </label>

                        <textarea
                            name="claims_strategy"
                            rows="6"
                            class="form-control"
                        >{{ old('claims_strategy') }}</textarea>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Dispute Resolution Strategy
                        </label>

                        <textarea
                            name="dispute_resolution_strategy"
                            rows="6"
                            class="form-control"
                        >{{ old(
                            'dispute_resolution_strategy'
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- Defect Liability --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Defect Liability Strategy</strong>
            </div>

            <div class="card-body">

                <textarea
                    name="defect_liability_strategy"
                    rows="5"
                    class="form-control"
                    placeholder="Define the strategic defect liability approach..."
                >{{ old(
                    'defect_liability_strategy'
                ) }}</textarea>

            </div>

        </div>


        {{-- Assumptions / Constraints --}}

        <div class="row">

            <div class="col-md-6">

                <div class="card mb-4">

                    <div class="card-header">
                        <strong>Assumptions</strong>
                    </div>

                    <div class="card-body">

                        <textarea
                            name="assumptions"
                            rows="6"
                            class="form-control"
                        >{{ old('assumptions') }}</textarea>

                    </div>

                </div>

            </div>


            <div class="col-md-6">

                <div class="card mb-4">

                    <div class="card-header">
                        <strong>Constraints</strong>
                    </div>

                    <div class="card-body">

                        <textarea
                            name="constraints"
                            rows="6"
                            class="form-control"
                        >{{ old('constraints') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- Remarks --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Remarks</strong>
            </div>

            <div class="card-body">

                <textarea
                    name="remarks"
                    rows="4"
                    class="form-control"
                >{{ old('remarks') }}</textarea>

            </div>

        </div>


        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route(
                    'admin.projects.contract-strategy.index',
                    ['project' => $project->id]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Create Contract Strategy
            </button>

        </div>

    </form>

</div>

@endsection