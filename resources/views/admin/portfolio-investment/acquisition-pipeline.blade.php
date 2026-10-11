@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Acquisition Pipeline</h4>
            <p class="text-muted mb-0">
                Track land opportunities from evaluation to acquisition.
            </p>
        </div>

        <a href="{{ route('admin.portfolio-investment.overview') }}"
           class="btn btn-outline-secondary">
            <i class="ri-arrow-left-line"></i>
            Portfolio Overview
        </a>
    </div>

    {{-- Stage Summary --}}
    <div class="row g-3 mb-4">

        @php
            $stages = [
                'Land Evaluation' => ['icon' => 'ri-map-pin-line', 'color' => 'secondary'],
                'Due Diligence' => ['icon' => 'ri-file-search-line', 'color' => 'warning'],
                'Feasibility Study' => ['icon' => 'ri-file-chart-line', 'color' => 'info'],
                'Investment Analysis' => ['icon' => 'ri-line-chart-line', 'color' => 'primary'],
                'Investment Decision' => ['icon' => 'ri-government-line', 'color' => 'dark'],
                'Acquired' => ['icon' => 'ri-checkbox-circle-line', 'color' => 'success'],
            ];
        @endphp

        @foreach($stages as $stage => $config)
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small">
                                    {{ $stage }}
                                </div>

                                <h3 class="mb-0 mt-2">
                                    {{ $stageSummary[$stage] ?? 0 }}
                                </h3>
                            </div>

                            <div class="text-{{ $config['color'] }}">
                                <i class="{{ $config['icon'] }} fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

    </div>

    {{-- Pipeline Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Land Acquisition Pipeline</h5>

            <span class="badge bg-primary">
                {{ $pipeline->count() }} Records
            </span>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Land Code</th>
                            <th>Land Name</th>
                            <th>Opportunity</th>
                            <th>Location</th>
                            <th>Area</th>
                            <th>Estimated Cost</th>
                            <th>Pipeline Stage</th>
                            <th>Due Diligence</th>
                            <th>Feasibility</th>
                            <th>Decisions</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($pipeline as $index => $item)

                            @php
                                $stageClass = match($item->pipeline_stage) {
                                    'Acquired' => 'success',
                                    'Investment Decision' => 'dark',
                                    'Investment Analysis' => 'primary',
                                    'Feasibility Study' => 'info',
                                    'Due Diligence' => 'warning',
                                    default => 'secondary',
                                };
                            @endphp

                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    <strong>{{ $item->land_code }}</strong>
                                </td>

                                <td>{{ $item->land_name }}</td>

                                <td>
                                    {{ $item->opportunity_name ?? '-' }}

                                    @if($item->opportunity_no)
                                        <br>
                                        <small class="text-muted">
                                            {{ $item->opportunity_no }}
                                        </small>
                                    @endif
                                </td>

                                <td>
                                    {{ $item->city ?? '-' }}

                                    @if($item->state)
                                        <br>
                                        <small class="text-muted">
                                            {{ $item->state }}
                                        </small>
                                    @endif
                                </td>

                                <td>
                                    {{ $item->total_area ?? '-' }}
                                    {{ $item->area_unit ?? '' }}
                                </td>

                                <td>
                                    @if($item->estimated_acquisition_cost !== null)
                                        ₹ {{ number_format($item->estimated_acquisition_cost, 2) }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    <span class="badge bg-{{ $stageClass }}">
                                        {{ $item->pipeline_stage }}
                                    </span>
                                </td>

                                <td>
                                    {{ $item->completed_due_diligence }}
                                    /
                                    {{ $item->due_diligence_count }}
                                </td>

                                <td>
                                    {{ $item->feasibility_count }}
                                </td>

                                <td>
                                    {{ $item->investment_decision_count }}
                                </td>

                                <td>
                                    <a href="{{ route('admin.land.lands.show', $item->id) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="View Land">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="12" class="text-center text-muted py-4">
                                    No acquisition pipeline records found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

@endsection