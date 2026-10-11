@extends('layouts.app')

@section('title', 'Leasing Opportunity Details')

@section('content')

<div class="container-fluid py-4">

    @php

        $stage = strtolower(
            $pipeline->stage ?? 'lead'
        );

        $stageLabels = [
            'lead' => 'Lead',
            'prospect' => 'Prospect',
            'negotiation' => 'Negotiation',
            'agreement' => 'Agreement',
            'opening' => 'Opening',
            'won' => 'Won',
            'lost' => 'Lost',
        ];

        $stageClasses = [
            'lead' => 'bg-secondary-subtle text-secondary',
            'prospect' => 'bg-info-subtle text-info',
            'negotiation' => 'bg-warning-subtle text-warning',
            'agreement' => 'bg-primary-subtle text-primary',
            'opening' => 'bg-success-subtle text-success',
            'won' => 'bg-success-subtle text-success',
            'lost' => 'bg-danger-subtle text-danger',
        ];

        $pipelineStages = [
            'lead',
            'prospect',
            'negotiation',
            'agreement',
            'opening',
            'won',
        ];

        $currentStageIndex = array_search(
            $stage,
            $pipelineStages
        );

        if ($currentStageIndex === false) {
            $currentStageIndex = 0;
        }

        $probability = (float) (
            $pipeline->probability ?? 0
        );

        $aging = $pipeline->aging_days;

        if ($aging === null && $pipeline->created_at) {
            $aging = $pipeline->created_at->diffInDays(now());
        }

    @endphp


    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>

            <div class="d-flex align-items-center gap-2 mb-2">

                <h4 class="mb-0 fw-semibold">
                    Leasing Opportunity #{{ $pipeline->id }}
                </h4>

                <span class="badge px-3 py-2
                    {{ $stageClasses[$stage] ?? 'bg-light text-dark' }}">

                    {{ $stageLabels[$stage] ?? ucfirst($stage) }}

                </span>

            </div>

            <p class="text-muted mb-0">

                {{ $pipeline->prospect_name
                    ?? $pipeline->prospect->name
                    ?? 'Unnamed Prospect' }}

                @if($pipeline->company_name)
                    · {{ $pipeline->company_name }}
                @endif

            </p>

        </div>


        <div class="d-flex gap-2 mt-3 mt-md-0">

            @can('pipeline.edit')

                <a href="{{ route(
                    'admin.leasing.pipeline.edit',
                    $pipeline->id
                ) }}"
                   class="btn btn-primary">

                    <i class="ri-edit-line me-1"></i>
                    Edit

                </a>

            @endcan


            <a href="{{ route(
                'admin.leasing.pipeline.index'
            ) }}"
               class="btn btn-light border">

                <i class="ri-arrow-left-line me-1"></i>
                Back

            </a>

        </div>

    </div>


    {{-- =========================================================
        PIPELINE PROGRESS
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-0 py-3">

            <h6 class="mb-0 fw-semibold">

                <i class="ri-route-line me-2"></i>
                Pipeline Progress

            </h6>

        </div>


        <div class="card-body">

            <div class="row g-0">

                @foreach($pipelineStages as $index => $pipelineStage)

                    @php

                        $completed =
                            $index <= $currentStageIndex;

                        $isCurrent =
                            $index === $currentStageIndex;

                    @endphp

                    <div class="col">

                        <div class="text-center position-relative">

                            @if($index > 0)

                                <div class="position-absolute top-50 start-0 w-100"
                                     style="
                                        height:3px;
                                        transform:translateY(-50%);
                                        z-index:0;
                                        background:
                                        {{ $index <= $currentStageIndex
                                            ? 'var(--bs-primary)'
                                            : '#dee2e6' }};
                                     ">
                                </div>

                            @endif


                            <div class="position-relative d-inline-flex
                                        align-items-center
                                        justify-content-center
                                        rounded-circle
                                        {{ $completed
                                            ? 'bg-primary text-white'
                                            : 'bg-light text-muted border' }}"
                                 style="
                                    width:42px;
                                    height:42px;
                                    z-index:1;
                                 ">

                                @if($completed)

                                    <i class="ri-check-line"></i>

                                @else

                                    {{ $index + 1 }}

                                @endif

                            </div>


                            <div class="small mt-2
                                        {{ $isCurrent
                                            ? 'fw-bold text-primary'
                                            : 'text-muted' }}">

                                {{ $stageLabels[$pipelineStage] }}

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </div>


    <div class="row g-4">

        {{-- =====================================================
            LEFT
        ====================================================== --}}
        <div class="col-xl-8">


            {{-- =================================================
                OPPORTUNITY INFORMATION
            ================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <h6 class="mb-0 fw-semibold">

                        <i class="ri-building-line me-2"></i>
                        Opportunity Information

                    </h6>

                </div>


                <div class="card-body">

                    <div class="row g-4">

                        {{-- Prospect --}}
                        <div class="col-md-6">

                            <small class="text-muted d-block mb-1">
                                Prospect
                            </small>

                            <div class="fw-semibold fs-6">

                                {{ $pipeline->prospect_name
                                    ?? $pipeline->prospect->name
                                    ?? '—' }}

                            </div>

                        </div>


                        {{-- Company --}}
                        <div class="col-md-6">

                            <small class="text-muted d-block mb-1">
                                Company
                            </small>

                            <div class="fw-semibold">

                                {{ $pipeline->company_name ?? '—' }}

                            </div>

                        </div>


                        {{-- Unit --}}
                        <div class="col-md-6">

                            <small class="text-muted d-block mb-1">
                                Unit
                            </small>

                            @if(isset($pipeline->unit))

                                <div class="fw-semibold">

                                    {{ $pipeline->unit->unit_code
                                        ?? $pipeline->unit->name
                                        ?? 'Unit #' . $pipeline->unit->id }}

                                </div>

                            @else

                                <span class="text-muted">
                                    Not assigned
                                </span>

                            @endif

                        </div>


                        {{-- Area --}}
                        <div class="col-md-6">

                            <small class="text-muted d-block mb-1">
                                Area
                            </small>

                            <div class="fw-semibold">

                                @if($pipeline->area !== null)

                                    {{ number_format(
                                        $pipeline->area,
                                        2
                                    ) }}
                                    sq.ft

                                @else

                                    —

                                @endif

                            </div>

                        </div>


                        {{-- Expected Rent --}}
                        <div class="col-md-6">

                            <small class="text-muted d-block mb-1">
                                Expected Rent
                            </small>

                            <div class="fw-semibold fs-5">

                                {{ number_format(
                                    $pipeline->expected_rent ?? 0,
                                    2
                                ) }}

                                @if($pipeline->rent_period)

                                    <small class="text-muted fs-6">
                                        / {{ ucfirst(
                                            $pipeline->rent_period
                                        ) }}
                                    </small>

                                @endif

                            </div>

                        </div>


                        {{-- Probability --}}
                        <div class="col-md-6">

                            <small class="text-muted d-block mb-1">
                                Probability
                            </small>

                            <div class="d-flex align-items-center gap-3">

                                <div class="progress flex-grow-1"
                                     style="height:8px;">

                                    <div class="progress-bar"
                                         role="progressbar"
                                         style="width:{{ min($probability, 100) }}%;">
                                    </div>

                                </div>

                                <strong>
                                    {{ number_format(
                                        $probability,
                                        0
                                    ) }}%
                                </strong>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                ACTION & FORECAST
            ================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <h6 class="mb-0 fw-semibold">

                        <i class="ri-calendar-check-line me-2"></i>
                        Action & Forecast

                    </h6>

                </div>


                <div class="card-body">

                    <div class="row g-4">

                        {{-- Next Action --}}
                        <div class="col-md-6">

                            <small class="text-muted d-block mb-1">
                                Next Action
                            </small>

                            <div class="fw-semibold">

                                {{ $pipeline->next_action ?? 'No action defined' }}

                            </div>

                        </div>


                        {{-- Owner --}}
                        <div class="col-md-6">

                            <small class="text-muted d-block mb-1">
                                Pipeline Owner
                            </small>

                            @if(isset($pipeline->owner))

                                <div class="d-flex align-items-center">

                                    <div class="rounded-circle bg-primary-subtle
                                                text-primary
                                                d-flex align-items-center
                                                justify-content-center me-2"
                                         style="width:38px;height:38px;">

                                        {{ strtoupper(
                                            substr(
                                                $pipeline->owner->name,
                                                0,
                                                1
                                            )
                                        ) }}

                                    </div>

                                    <strong>
                                        {{ $pipeline->owner->name }}
                                    </strong>

                                </div>

                            @else

                                <span class="text-muted">
                                    Unassigned
                                </span>

                            @endif

                        </div>


                        {{-- Forecast Opening --}}
                        <div class="col-md-6">

                            <small class="text-muted d-block mb-1">
                                Forecast Opening
                            </small>

                            <div class="fw-semibold">

                                @if($pipeline->forecast_opening)

                                    {{ \Carbon\Carbon::parse(
                                        $pipeline->forecast_opening
                                    )->format('d M Y') }}

                                @else

                                    —

                                @endif

                            </div>

                        </div>


                        {{-- Expected Opening --}}
                        <div class="col-md-6">

                            <small class="text-muted d-block mb-1">
                                Expected Opening
                            </small>

                            <div class="fw-semibold">

                                @if($pipeline->expected_opening_date)

                                    {{ \Carbon\Carbon::parse(
                                        $pipeline->expected_opening_date
                                    )->format('d M Y') }}

                                @else

                                    —

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                NOTES
            ================================================== --}}
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-0 py-3">

                    <h6 class="mb-0 fw-semibold">

                        <i class="ri-sticky-note-line me-2"></i>
                        Notes

                    </h6>

                </div>


                <div class="card-body">

                    @if($pipeline->notes)

                        <div class="text-muted"
                             style="white-space:pre-line;">

                            {{ $pipeline->notes }}

                        </div>

                    @else

                        <div class="text-muted">
                            No notes have been added.
                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- =====================================================
            RIGHT
        ====================================================== --}}
        <div class="col-xl-4">


            {{-- =================================================
                KPI
            ================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <h6 class="mb-0 fw-semibold">

                        <i class="ri-dashboard-line me-2"></i>
                        Opportunity KPIs

                    </h6>

                </div>


                <div class="card-body">

                    <div class="d-flex justify-content-between
                                align-items-center
                                py-2
                                border-bottom">

                        <span class="text-muted">
                            Stage
                        </span>

                        <span class="badge
                            {{ $stageClasses[$stage] ?? 'bg-light text-dark' }}">

                            {{ $stageLabels[$stage] ?? ucfirst($stage) }}

                        </span>

                    </div>


                    <div class="d-flex justify-content-between
                                align-items-center
                                py-3
                                border-bottom">

                        <span class="text-muted">
                            Probability
                        </span>

                        <strong>
                            {{ number_format($probability, 0) }}%
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between
                                align-items-center
                                py-3
                                border-bottom">

                        <span class="text-muted">
                            Aging
                        </span>

                        <strong class="
                            {{ ($aging ?? 0) > 90
                                ? 'text-danger'
                                : (($aging ?? 0) > 60
                                    ? 'text-warning'
                                    : '') }}">

                            {{ $aging ?? 0 }} days

                        </strong>

                    </div>


                    <div class="d-flex justify-content-between
                                align-items-center
                                py-3">

                        <span class="text-muted">
                            Expected Rent
                        </span>

                        <strong>

                            {{ number_format(
                                $pipeline->expected_rent ?? 0,
                                2
                            ) }}

                        </strong>

                    </div>

                </div>

            </div>


            {{-- =================================================
                RECORD INFORMATION
            ================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <h6 class="mb-0 fw-semibold">

                        <i class="ri-information-line me-2"></i>
                        Record Information

                    </h6>

                </div>


                <div class="card-body">

                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Opportunity ID
                        </small>

                        <strong>
                            #{{ $pipeline->id }}
                        </strong>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Created
                        </small>

                        <strong>

                            {{ optional(
                                $pipeline->created_at
                            )->format('d M Y H:i') }}

                        </strong>

                    </div>


                    <div>

                        <small class="text-muted d-block">
                            Last Updated
                        </small>

                        <strong>

                            {{ optional(
                                $pipeline->updated_at
                            )->format('d M Y H:i') }}

                        </strong>

                    </div>

                </div>

            </div>


            {{-- =================================================
                ACTIONS
            ================================================== --}}
            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    @can('pipeline.edit')

                        <a href="{{ route(
                            'admin.leasing.pipeline.edit',
                            $pipeline->id
                        ) }}"
                           class="btn btn-primary w-100 mb-2">

                            <i class="ri-edit-line me-1"></i>
                            Edit Opportunity

                        </a>

                    @endcan


                    @can('pipeline.delete')

                        <form method="POST"
                              action="{{ route(
                                  'admin.leasing.pipeline.destroy',
                                  $pipeline->id
                              ) }}"
                              onsubmit="return confirm(
                                  'Are you sure you want to delete this opportunity?'
                              );">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn btn-outline-danger w-100 mb-2">

                                <i class="ri-delete-bin-line me-1"></i>
                                Delete Opportunity

                            </button>

                        </form>

                    @endcan


                    <a href="{{ route(
                        'admin.leasing.pipeline.index'
                    ) }}"
                       class="btn btn-light border w-100">

                        <i class="ri-list-check-2 me-1"></i>
                        All Opportunities

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection