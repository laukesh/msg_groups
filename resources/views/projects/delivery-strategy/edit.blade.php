@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project / Delivery Strategy
            </div>

            <h3>
                Edit Delivery Strategy
            </h3>

            <div class="text-muted">

                {{ $project->project_name }}

                · {{ $deliveryStrategy->strategy_number }}

                · V{{ $deliveryStrategy->version_number }}

            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.delivery-strategy.show',
                [
                    'project' => $project->id,
                    'deliveryStrategy' =>
                        $deliveryStrategy->id,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >
            ← Back
        </a>

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


    <form
        method="POST"
        action="{{ route(
            'admin.projects.delivery-strategy.update',
            [
                'project' => $project->id,
                'deliveryStrategy' =>
                    $deliveryStrategy->id,
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
                            value="{{ $deliveryStrategy->strategy_number }}"
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
                            value="V{{ $deliveryStrategy->version_number }}"
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
                            value="{{ $deliveryStrategy->status }}"
                            readonly
                        >

                    </div>

                </div>


                <div class="row">

                    <div class="col-md-8 mb-3">

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
                                $deliveryStrategy->title
                            ) }}"
                            required
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Delivery Model
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="delivery_model"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select Delivery Model
                            </option>

                            @foreach([
                                'Design-Bid-Build',
                                'Design-Build',
                                'EPC',
                                'EPCM',
                                'PMC',
                                'Turnkey',
                                'Management Contract',
                                'Other',
                            ] as $model)

                                <option
                                    value="{{ $model }}"
                                    @selected(
                                        old(
                                            'delivery_model',
                                            $deliveryStrategy->delivery_model
                                        ) === $model
                                    )
                                >
                                    {{ $model }}
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
                                $deliveryStrategy->effective_date
                                    ? $deliveryStrategy
                                        ->effective_date
                                        ->format('Y-m-d')
                                    : null
                            ) }}"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- Delivery Approach --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Delivery Approach</strong>
            </div>

            <div class="card-body">

                <textarea
                    name="delivery_approach"
                    rows="5"
                    class="form-control"
                >{{ old(
                    'delivery_approach',
                    $deliveryStrategy->delivery_approach
                ) }}</textarea>

            </div>

        </div>


        {{-- Implementation --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Implementation Strategy</strong>
            </div>

            <div class="card-body">

                <textarea
                    name="implementation_strategy"
                    rows="5"
                    class="form-control"
                >{{ old(
                    'implementation_strategy',
                    $deliveryStrategy->implementation_strategy
                ) }}</textarea>

            </div>

        </div>


        {{-- Packaging --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Project Packaging Strategy</strong>
            </div>

            <div class="card-body">

                <textarea
                    name="project_packaging_strategy"
                    rows="5"
                    class="form-control"
                >{{ old(
                    'project_packaging_strategy',
                    $deliveryStrategy->project_packaging_strategy
                ) }}</textarea>

            </div>

        </div>


        {{-- Responsibility --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Responsibility Matrix</strong>
            </div>

            <div class="card-body">

                <textarea
                    name="responsibility_matrix"
                    rows="6"
                    class="form-control"
                >{{ old(
                    'responsibility_matrix',
                    $deliveryStrategy->responsibility_matrix
                ) }}</textarea>

            </div>

        </div>


        {{-- Milestones --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Key Milestones</strong>
            </div>

            <div class="card-body">

                <textarea
                    name="key_milestones"
                    rows="6"
                    class="form-control"
                >{{ old(
                    'key_milestones',
                    $deliveryStrategy->key_milestones
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
                        >{{ old(
                            'assumptions',
                            $deliveryStrategy->assumptions
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
                            $deliveryStrategy->constraints
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
                    $deliveryStrategy->remarks
                ) }}</textarea>

            </div>

        </div>


        {{-- Actions --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route(
                    'admin.projects.delivery-strategy.show',
                    [
                        'project' => $project->id,
                        'deliveryStrategy' =>
                            $deliveryStrategy->id,
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