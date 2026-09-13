@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project / Contract Strategy
            </div>

            <h3>
                Edit Contract Strategy
            </h3>

            <div class="text-muted">

                {{ $project->project_name }}

                · {{ $contractStrategy->strategy_number }}

                · V{{ $contractStrategy->version_number }}

            </div>

        </div>

        <a
            href="{{ route(
                'admin.projects.contract-strategy.show',
                [
                    'project' => $project->id,
                    'contractStrategy' =>
                        $contractStrategy->id,
                ]
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
            'admin.projects.contract-strategy.update',
            [
                'project' => $project->id,
                'contractStrategy' =>
                    $contractStrategy->id,
            ]
        ) }}"
    >

        @csrf
        @method('PUT')


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
                            class="form-control"
                            value="{{ $contractStrategy->strategy_number }}"
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
                            value="V{{ $contractStrategy->version_number }}"
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
                            value="{{ $contractStrategy->status }}"
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
                            value="{{ old(
                                'title',
                                $contractStrategy->title
                            ) }}"
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
                                        old(
                                            'contracting_model',
                                            $contractStrategy->contracting_model
                                        ) === $model
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
                                        old(
                                            'contract_type',
                                            $contractStrategy->contract_type
                                        ) === $type
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
                            value="{{ old(
                                'effective_date',
                                $contractStrategy->effective_date
                                    ? $contractStrategy->effective_date
                                        ->format('Y-m-d')
                                    : null
                            ) }}"
                        >

                    </div>

                </div>

            </div>

        </div>


        @php

            $fields = [

                [
                    'name' => 'commercial_model',
                    'title' => 'Commercial Model',
                    'value' => $contractStrategy->commercial_model,
                    'placeholder' =>
                        'Define the strategic commercial model...',
                ],

                [
                    'name' => 'contract_packaging',
                    'title' => 'Contract Packaging',
                    'value' => $contractStrategy->contract_packaging,
                    'placeholder' =>
                        'Define how contracts will be packaged...',
                ],

                [
                    'name' => 'payment_strategy',
                    'title' => 'Payment Strategy',
                    'value' => $contractStrategy->payment_strategy,
                    'placeholder' =>
                        'Define the strategic payment approach...',
                ],

                [
                    'name' => 'risk_allocation_strategy',
                    'title' => 'Risk Allocation Strategy',
                    'value' => $contractStrategy->risk_allocation_strategy,
                    'placeholder' =>
                        'Define allocation of major contractual risks...',
                ],

                [
                    'name' => 'insurance_strategy',
                    'title' => 'Insurance Strategy',
                    'value' => $contractStrategy->insurance_strategy,
                    'placeholder' =>
                        'Define required insurance coverage...',
                ],

                [
                    'name' => 'defect_liability_strategy',
                    'title' => 'Defect Liability Strategy',
                    'value' => $contractStrategy->defect_liability_strategy,
                    'placeholder' =>
                        'Define the strategic defect liability approach...',
                ],

            ];

        @endphp


        @foreach($fields as $field)

            <div class="card mb-4">

                <div class="card-header">

                    <strong>
                        {{ $field['title'] }}
                    </strong>

                </div>

                <div class="card-body">

                    <textarea
                        name="{{ $field['name'] }}"
                        rows="6"
                        class="form-control"
                        placeholder="{{ $field['placeholder'] }}"
                    >{{ old(
                        $field['name'],
                        $field['value']
                    ) }}</textarea>

                </div>

            </div>

        @endforeach


        {{-- Contract Protections --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Contract Protections</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    @foreach([
                        [
                            'name' => 'performance_security_strategy',
                            'title' => 'Performance Security',
                        ],
                        [
                            'name' => 'retention_strategy',
                            'title' => 'Retention',
                        ],
                        [
                            'name' => 'liquidated_damages_strategy',
                            'title' => 'Liquidated Damages',
                        ],
                    ] as $field)

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                {{ $field['title'] }}
                            </label>

                            <textarea
                                name="{{ $field['name'] }}"
                                rows="6"
                                class="form-control"
                            >{{ old(
                                $field['name'],
                                $contractStrategy->{$field['name']}
                            ) }}</textarea>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>


        {{-- Change / Claims / Disputes --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Change, Claims & Dispute Strategy</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    @foreach([
                        [
                            'name' => 'variation_change_strategy',
                            'title' => 'Variation / Change Strategy',
                        ],
                        [
                            'name' => 'claims_strategy',
                            'title' => 'Claims Strategy',
                        ],
                        [
                            'name' => 'dispute_resolution_strategy',
                            'title' => 'Dispute Resolution Strategy',
                        ],
                    ] as $field)

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                {{ $field['title'] }}
                            </label>

                            <textarea
                                name="{{ $field['name'] }}"
                                rows="6"
                                class="form-control"
                            >{{ old(
                                $field['name'],
                                $contractStrategy->{$field['name']}
                            ) }}</textarea>

                        </div>

                    @endforeach

                </div>

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
                        >{{ old(
                            'assumptions',
                            $contractStrategy->assumptions
                        ) }}</textarea>

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
                        >{{ old(
                            'constraints',
                            $contractStrategy->constraints
                        ) }}</textarea>

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
                >{{ old(
                    'remarks',
                    $contractStrategy->remarks
                ) }}</textarea>

            </div>

        </div>


        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route(
                    'admin.projects.contract-strategy.show',
                    [
                        'project' => $project->id,
                        'contractStrategy' =>
                            $contractStrategy->id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Save Changes
            </button>

        </div>

    </form>

</div>

@endsection