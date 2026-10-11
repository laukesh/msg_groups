@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Asset Condition Details
            </h4>

            <p class="text-muted mb-0">
                View complete asset condition inspection.
            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.assets.operations.conditions.edit',
                    $condition
                ) }}"
                class="btn btn-warning"
            >

                <i class="ri-edit-line"></i>

                Edit

            </a>


            <a
                href="{{ route(
                    'admin.assets.operations.conditions.index'
                ) }}"
                class="btn btn-secondary"
            >

                <i class="ri-arrow-left-line"></i>

                Back

            </a>

        </div>

    </div>


    <div class="row g-4">


        {{-- Main --}}
        <div class="col-lg-8">


            {{-- Inspection Information --}}
            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Inspection Information
                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-4">


                        {{-- Asset --}}
                        <div class="col-md-6">

                            <label class="text-muted small">
                                Asset
                            </label>

                            @if($condition->asset)

                                <div class="fw-semibold">

                                    {{ $condition->asset->asset_name }}

                                </div>

                                <small class="text-muted">

                                    {{ $condition->asset->asset_code }}

                                </small>

                            @else

                                -

                            @endif

                        </div>


                        {{-- Date --}}
                        <div class="col-md-6">

                            <label class="text-muted small">
                                Inspection Date
                            </label>

                            <div class="fw-semibold">

                                {{ optional(
                                    $condition->inspection_date
                                )->format('d-m-Y') }}

                            </div>

                        </div>


                        {{-- Overall Condition --}}
                        <div class="col-md-6">

                            <label class="text-muted small">
                                Overall Condition
                            </label>

                            <div class="mt-1">

                                @php

                                    $conditionClass = match(
                                        $condition->condition
                                    ) {

                                        'Excellent' =>
                                            'bg-success',

                                        'Good' =>
                                            'bg-primary',

                                        'Fair' =>
                                            'bg-warning text-dark',

                                        'Poor' =>
                                            'bg-danger',

                                        'Critical' =>
                                            'bg-dark',

                                        default =>
                                            'bg-secondary',

                                    };

                                @endphp


                                <span
                                    class="badge {{ $conditionClass }} fs-6"
                                >

                                    {{ $condition->condition }}

                                </span>

                            </div>

                        </div>


                        {{-- Score --}}
                        <div class="col-md-6">

                            <label class="text-muted small">
                                Condition Score
                            </label>

                            <div class="fw-semibold">

                                @if($condition->condition_score !== null)

                                    {{ number_format(
                                        $condition->condition_score,
                                        2
                                    ) }}%

                                @else

                                    -

                                @endif

                            </div>


                            @if($condition->condition_score !== null)

                                <div
                                    class="progress mt-2"
                                    style="height: 8px;"
                                >

                                    <div
                                        class="progress-bar"
                                        style="width: {{ $condition->condition_score }}%"
                                    ></div>

                                </div>

                            @endif

                        </div>


                        {{-- Inspector --}}
                        <div class="col-md-6">

                            <label class="text-muted small">
                                Inspector
                            </label>

                            <div class="fw-semibold">

                                {{ $condition->inspector->name ?? '-' }}

                            </div>

                        </div>


                        {{-- Repair --}}
                        <div class="col-md-6">

                            <label class="text-muted small">
                                Estimated Repair Cost
                            </label>

                            <div class="fw-semibold">

                                ${{ number_format(
                                    $condition->estimated_repair_cost ?? 0,
                                    2
                                ) }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Components --}}
            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Component Condition
                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-3">


                        @foreach([
                            'structural_condition' => 'Structural',
                            'electrical_condition' => 'Electrical',
                            'mechanical_condition' => 'Mechanical',
                            'safety_condition' => 'Safety'
                        ] as $field => $label)

                            <div class="col-md-6">

                                <div class="border rounded p-3">

                                    <div class="text-muted small">
                                        {{ $label }}
                                    </div>

                                    <div class="fw-semibold mt-1">

                                        {{ $condition->{$field} ?? '-' }}

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            </div>


            {{-- Recommendation --}}
            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Recommendation
                    </h5>

                </div>


                <div class="card-body">

                    <div class="mb-4">

                        <label class="text-muted small">
                            Recommended Action
                        </label>

                        <div class="border rounded p-3 mt-1">

                            @if($condition->recommended_action)

                                {!! nl2br(
                                    e(
                                        $condition->recommended_action
                                    )
                                ) !!}

                            @else

                                <span class="text-muted">
                                    No recommendation provided.
                                </span>

                            @endif

                        </div>

                    </div>


                    <div>

                        <label class="text-muted small">
                            Remarks
                        </label>

                        <div class="border rounded p-3 mt-1">

                            @if($condition->remarks)

                                {!! nl2br(
                                    e(
                                        $condition->remarks
                                    )
                                ) !!}

                            @else

                                <span class="text-muted">
                                    No remarks provided.
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Right Side --}}
        <div class="col-lg-4">


            {{-- Record Information --}}
            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Record Information
                    </h5>

                </div>


                <div class="card-body">


                    <div class="mb-3">

                        <label class="text-muted small">
                            Record ID
                        </label>

                        <div class="fw-semibold">

                            #{{ $condition->id }}

                        </div>

                    </div>


                    <div class="mb-3">

                        <label class="text-muted small">
                            Created By
                        </label>

                        <div class="fw-semibold">

                            {{ $condition->creator->name ?? '-' }}

                        </div>

                    </div>


                    <div class="mb-3">

                        <label class="text-muted small">
                            Created At
                        </label>

                        <div class="fw-semibold">

                            {{ optional(
                                $condition->created_at
                            )->format('d-m-Y H:i:s') }}

                        </div>

                    </div>


                    <div class="mb-3">

                        <label class="text-muted small">
                            Updated By
                        </label>

                        <div class="fw-semibold">

                            {{ $condition->updater->name ?? '-' }}

                        </div>

                    </div>


                    <div>

                        <label class="text-muted small">
                            Updated At
                        </label>

                        <div class="fw-semibold">

                            {{ optional(
                                $condition->updated_at
                            )->format('d-m-Y H:i:s') }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- Delete --}}
            <div class="card">

                <div class="card-body">

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.assets.operations.conditions.destroy',
                            $condition
                        ) }}"
                        onsubmit="return confirm(
                            'Are you sure you want to delete this condition record?'
                        );"
                    >

                        @csrf

                        @method('DELETE')

                        <button
                            type="submit"
                            class="btn btn-danger w-100"
                        >

                            <i class="ri-delete-bin-line"></i>

                            Delete Condition

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection