@extends('layouts.app')

@section('title', 'Leasing Pipeline')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-semibold">
                <i class="ri-flow-chart me-2"></i>
                Leasing Pipeline
            </h4>

            <p class="text-muted mb-0">
                Track leasing opportunities from prospect to opening.
            </p>
        </div>

        <div class="d-flex gap-2 mt-3 mt-md-0">

            <a href="{{ route('admin.leasing.pipeline.index') }}"
               class="btn btn-light border">
                <i class="ri-refresh-line me-1"></i>
                Refresh
            </a>

            @can('pipeline.create')
                <a href="{{ route('admin.leasing.pipeline.create') }}"
                   class="btn btn-primary">
                    <i class="ri-add-line me-1"></i>
                    New Opportunity
                </a>
            @endcan

        </div>
    </div>


    {{-- =========================================================
        SUMMARY CARDS
    ========================================================== --}}
    <div class="row g-3 mb-4">

        {{-- Total --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>
                            <div class="text-muted small mb-1">
                                Total Opportunities
                            </div>

                            <h3 class="fw-bold mb-0">
                                {{ $total ?? $pipelines->total() ?? 0 }}
                            </h3>
                        </div>

                        <div class="rounded-circle bg-primary-subtle p-3">
                            <i class="ri-flow-chart text-primary fs-4"></i>
                        </div>

                    </div>

                    <div class="mt-3 small text-muted">
                        All leasing opportunities
                    </div>

                </div>
            </div>
        </div>


        {{-- Active --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>
                            <div class="text-muted small mb-1">
                                Active Pipeline
                            </div>

                            <h3 class="fw-bold mb-0">
                                {{ $activeCount ?? 0 }}
                            </h3>
                        </div>

                        <div class="rounded-circle bg-success-subtle p-3">
                            <i class="ri-loader-4-line text-success fs-4"></i>
                        </div>

                    </div>

                    <div class="mt-3 small text-muted">
                        Currently progressing
                    </div>

                </div>
            </div>
        </div>


        {{-- Expected Rent --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>
                            <div class="text-muted small mb-1">
                                Forecast Rent
                            </div>

                            <h3 class="fw-bold mb-0">
                                {{ number_format($forecastRent ?? 0, 2) }}
                            </h3>
                        </div>

                        <div class="rounded-circle bg-info-subtle p-3">
                            <i class="ri-money-dollar-circle-line text-info fs-4"></i>
                        </div>

                    </div>

                    <div class="mt-3 small text-muted">
                        Expected rental value
                    </div>

                </div>
            </div>
        </div>


        {{-- Conversion --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>
                            <div class="text-muted small mb-1">
                                Conversion Rate
                            </div>

                            <h3 class="fw-bold mb-0">
                                {{ number_format($conversionRate ?? 0, 1) }}%
                            </h3>
                        </div>

                        <div class="rounded-circle bg-warning-subtle p-3">
                            <i class="ri-percent-line text-warning fs-4"></i>
                        </div>

                    </div>

                    <div class="mt-3 small text-muted">
                        Pipeline conversion
                    </div>

                </div>
            </div>
        </div>

    </div>


    {{-- =========================================================
        PIPELINE STAGE SUMMARY
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center">

                <h6 class="mb-0 fw-semibold">
                    <i class="ri-bar-chart-grouped-line me-2"></i>
                    Pipeline by Stage
                </h6>

                <span class="text-muted small">
                    Current opportunities
                </span>

            </div>

        </div>

        <div class="card-body">

            <div class="row g-3">

                @php
                    $stages = [
                        'lead' => [
                            'label' => 'Lead',
                            'icon' => 'ri-user-search-line',
                        ],
                        'prospect' => [
                            'label' => 'Prospect',
                            'icon' => 'ri-user-follow-line',
                        ],
                        'negotiation' => [
                            'label' => 'Negotiation',
                            'icon' => 'ri-discuss-line',
                        ],
                        'agreement' => [
                            'label' => 'Agreement',
                            'icon' => 'ri-file-text-line',
                        ],
                        'opening' => [
                            'label' => 'Opening',
                            'icon' => 'ri-store-2-line',
                        ],
                        'won' => [
                            'label' => 'Won',
                            'icon' => 'ri-checkbox-circle-line',
                        ],
                    ];
                @endphp

                @foreach($stages as $key => $stage)

                    <div class="col-xl-2 col-md-4 col-6">

                        <div class="border rounded-3 p-3 h-100">

                            <div class="d-flex align-items-center mb-2">

                                <i class="{{ $stage['icon'] }} fs-4 me-2 text-primary"></i>

                                <span class="small fw-semibold">
                                    {{ $stage['label'] }}
                                </span>

                            </div>

                            <h4 class="mb-0 fw-bold">
                                {{ $stageCounts[$key] ?? 0 }}
                            </h4>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </div>


    {{-- =========================================================
        FILTERS
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.leasing.pipeline.index') }}">

                <div class="row g-3 align-items-end">

                    {{-- Search --}}
                    <div class="col-xl-3 col-md-6">

                        <label class="form-label fw-semibold">
                            Search
                        </label>

                        <div class="input-group">

                            <span class="input-group-text bg-white">
                                <i class="ri-search-line"></i>
                            </span>

                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   class="form-control"
                                   placeholder="Prospect, unit, owner...">

                        </div>

                    </div>


                    {{-- Stage --}}
                    <div class="col-xl-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Stage
                        </label>

                        <select name="stage"
                                class="form-select">

                            <option value="">
                                All Stages
                            </option>

                            @foreach($stages as $key => $stage)

                                <option value="{{ $key }}"
                                    @selected(request('stage') === $key)>
                                    {{ $stage['label'] }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Probability --}}
                    <div class="col-xl-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Probability
                        </label>

                        <select name="probability"
                                class="form-select">

                            <option value="">
                                All
                            </option>

                            <option value="high"
                                @selected(request('probability') === 'high')}>
                                High
                            </option>

                            <option value="medium"
                                @selected(request('probability') === 'medium')}>
                                Medium
                            </option>

                            <option value="low"
                                @selected(request('probability') === 'low')}>
                                Low
                            </option>

                        </select>

                    </div>


                    {{-- Owner --}}
                    <div class="col-xl-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Owner
                        </label>

                        <select name="owner_id"
                                class="form-select">

                            <option value="">
                                All Owners
                            </option>

                            @foreach(($owners ?? []) as $owner)

                                <option value="{{ $owner->id }}"
                                    @selected(request('owner_id') == $owner->id)>
                                    {{ $owner->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Date --}}
                    <div class="col-xl-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Opening
                        </label>

                        <select name="opening"
                                class="form-select">

                            <option value="">
                                All Dates
                            </option>

                            <option value="overdue"
                                @selected(request('opening') === 'overdue')}>
                                Overdue
                            </option>

                            <option value="30"
                                @selected(request('opening') === '30')}>
                                Next 30 Days
                            </option>

                            <option value="90"
                                @selected(request('opening') === '90')}>
                                Next 90 Days
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div class="col-xl-1 col-md-6">

                        <button type="submit"
                                class="btn btn-primary w-100">
                            <i class="ri-filter-3-line"></i>
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        PIPELINE TABLE
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center">

                <div>
                    <h6 class="mb-1 fw-semibold">
                        Leasing Opportunities
                    </h6>

                    <small class="text-muted">
                        Opportunities currently in the leasing pipeline
                    </small>
                </div>

                <div class="mt-2 mt-md-0">

                    <span class="badge bg-light text-dark border">
                        {{ $pipelines->total() ?? 0 }} Records
                    </span>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>


                            <th>
                                Prospect
                            </th>

                            <th>
                                Unit
                            </th>

                            <th>
                                Area
                            </th>

                            <th>
                                Expected Rent
                            </th>

                            <th>
                                Probability
                            </th>

                            <th>
                                Stage
                            </th>

                            <th>
                                Next Action
                            </th>

                            <th>
                                Owner
                            </th>

                            <th>
                                Aging
                            </th>

                            <th>
                                Forecast Opening
                            </th>

                            <th class="text-end pe-4">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($pipelines as $pipeline)

                        @php

                            $stage = strtolower(
                                $pipeline->stage ?? 'lead'
                            );

                            $stageClasses = [
                                'lead' => 'bg-secondary-subtle text-secondary',
                                'prospect' => 'bg-info-subtle text-info',
                                'negotiation' => 'bg-warning-subtle text-warning',
                                'agreement' => 'bg-primary-subtle text-primary',
                                'opening' => 'bg-success-subtle text-success',
                                'won' => 'bg-success-subtle text-success',
                                'lost' => 'bg-danger-subtle text-danger',
                            ];

                            $stageClass =
                                $stageClasses[$stage]
                                ?? 'bg-light text-dark';

                            $probability =
                                $pipeline->probability ?? 0;

                            if ($probability >= 70) {
                                $probabilityClass = 'success';
                            } elseif ($probability >= 40) {
                                $probabilityClass = 'warning';
                            } else {
                                $probabilityClass = 'danger';
                            }

                        @endphp

                        <tr>

                            {{-- ID --}}
                            <!-- <td class="ps-4">

                                <span class="text-muted">
                                    {{ $pipeline->id }}
                                </span>

                            </td> -->


                            {{-- Prospect --}}
                            <td>

                                <div class="fw-semibold">

                                    {{ $pipeline->prospect_name
                                        ?? $pipeline->prospect->name
                                        ?? $pipeline->prospect
                                        ?? '—' }}

                                </div>

                                @if(!empty($pipeline->company_name))

                                    <small class="text-muted">
                                        {{ $pipeline->company_name }}
                                    </small>

                                @endif

                            </td>


                            {{-- Unit --}}
                            <td>

                                @if(isset($pipeline->unit))

                                    <a href="#"
                                       class="text-decoration-none fw-semibold">

                                        {{ $pipeline->unit->unit_no}}

                                    </a>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Area --}}
                            <td>

                                @if($pipeline->area !== null)

                                    {{ number_format($pipeline->area, 2) }}
                                    <small class="text-muted">sq.ft</small>

                                @elseif(isset($pipeline->unit))

                                    {{ number_format(
                                        $pipeline->unit->area ?? 0,
                                        2
                                    ) }}

                                    <small class="text-muted">
                                        sq.ft
                                    </small>

                                @else

                                    —

                                @endif

                            </td>


                            {{-- Rent --}}
                            <td>

                                <span class="fw-semibold">

                                    {{ number_format(
                                        $pipeline->expected_rent ?? 0,
                                        2
                                    ) }}

                                </span>

                                @if(!empty($pipeline->rent_period))

                                    <small class="text-muted">
                                        / {{ $pipeline->rent_period }}
                                    </small>

                                @endif

                            </td>


                            {{-- Probability --}}
                            <td>

                                <div class="d-flex align-items-center gap-2">

                                    <div class="progress"
                                         style="width:60px;height:6px;">

                                        <div class="progress-bar bg-{{ $probabilityClass }}"
                                             style="width:{{ min((float)$probability, 100) }}%;">
                                        </div>

                                    </div>

                                    <small>
                                        {{ number_format($probability, 0) }}%
                                    </small>

                                </div>

                            </td>


                            {{-- Stage --}}
                            <td>

                                <span class="badge {{ $stageClass }} px-2 py-1">

                                    {{ ucfirst(str_replace(
                                        ['_', '-'],
                                        ' ',
                                        $stage
                                    )) }}

                                </span>

                            </td>


                            {{-- Next Action --}}
                            <td>

                                @if(!empty($pipeline->next_action))

                                    <span title="{{ $pipeline->next_action }}">
                                        {{ \Illuminate\Support\Str::limit(
                                            $pipeline->next_action,
                                            35
                                        ) }}
                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Owner --}}
                            <td>

                                @if(isset($pipeline->owner))

                                    <div class="d-flex align-items-center">

                                        <div class="rounded-circle bg-primary-subtle
                                                    text-primary
                                                    d-flex align-items-center
                                                    justify-content-center me-2"
                                             style="width:32px;height:32px;">

                                            {{ strtoupper(
                                                substr(
                                                    $pipeline->owner->name,
                                                    0,
                                                    1
                                                )
                                            ) }}

                                        </div>

                                        <span>
                                            {{ $pipeline->owner->name }}
                                        </span>

                                    </div>

                                @else

                                    <span class="text-muted">
                                        Unassigned
                                    </span>

                                @endif

                            </td>


                            {{-- Aging --}}
                            <td>

                                @php

                                    $aging = $pipeline->aging_days;

                                    if ($aging === null && $pipeline->created_at) {
                                        $aging =
                                            $pipeline->created_at
                                                ->diffInDays(now());
                                    }

                                @endphp

                                <span class="
                                    {{ ($aging ?? 0) > 90
                                        ? 'text-danger'
                                        : (($aging ?? 0) > 60
                                            ? 'text-warning'
                                            : 'text-muted') }}
                                ">

                                    {{ $aging ?? 0 }} days

                                </span>

                            </td>


                            {{-- Opening --}}
                            <td>

                                @if(!empty($pipeline->forecast_opening))

                                    {{ \Carbon\Carbon::parse(
                                        $pipeline->forecast_opening
                                    )->format('d M Y') }}

                                @elseif(!empty($pipeline->expected_opening_date))

                                    {{ \Carbon\Carbon::parse(
                                        $pipeline->expected_opening_date
                                    )->format('d M Y') }}

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td >

                                <div class="dropdown1 d-flex justify-content-end gap-2 pe-4">

                                   


                                    

                                        @can('pipeline.view')

                                        <a class="btn btn-sm btn-success"
                                        href="{{ route('admin.leasing.pipeline.show',$pipeline->id) }}"
                                        title="View">

                                            <i class="ri-eye-line me-2"></i>
                                            

                                        </a>
                                            

                                        @endcan


                                        @can('pipeline.edit')

                                           
                                                <a class="btn btn-sm btn-primary "
                                                   href="{{ route(
                                                       'admin.leasing.pipeline.edit',
                                                       $pipeline->id
                                                   ) }}">

                                                    <i class="ri-edit-line me-2"></i>
                                                    

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
                                                            class="btn btn-sm btn-danger ">

                                                        <i class="ri-delete-bin-line me-2"></i>
                                                        

                                                    </button>

                                                </form>

                                      

                                        @endcan

                                  

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="12"
                                class="text-center py-5">

                                <div class="mb-3">

                                    <i class="ri-flow-chart fs-1 text-muted"></i>

                                </div>

                                <h6 class="fw-semibold">
                                    No Leasing Opportunities Found
                                </h6>

                                <p class="text-muted mb-3">
                                    Start building your leasing pipeline
                                    by creating a new opportunity.
                                </p>

                                @can('pipeline.create')

                                    <a href="{{ route(
                                        'admin.leasing.pipeline.create'
                                    ) }}"
                                       class="btn btn-primary">

                                        <i class="ri-add-line me-1"></i>
                                        Add Opportunity

                                    </a>

                                @endcan

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =====================================================
            PAGINATION
        ====================================================== --}}
        @if(method_exists($pipelines, 'links'))

            <div class="card-footer bg-white border-0">

                <div class="d-flex flex-wrap justify-content-between
                            align-items-center">

                    <div class="text-muted small">

                        Showing
                        {{ $pipelines->firstItem() ?? 0 }}
                        to
                        {{ $pipelines->lastItem() ?? 0 }}
                        of
                        {{ $pipelines->total() }}
                        opportunities

                    </div>

                    <div>
                        {{ $pipelines->withQueryString()->links() }}
                    </div>

                </div>

            </div>

        @endif

    </div>

</div>

@endsection