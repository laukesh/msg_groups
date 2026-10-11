@extends('layouts.app')

@section('content')

<div class="container-fluid py-3">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <h4 class="mb-1">
                <i class="ri-building-4-line me-1"></i>
                {{ $profile->profile_name ?: 'Industry Operating Profile' }}
            </h4>

            <p class="text-muted mb-0">
                Industry Operating Profile
                @if($profile->profile_code)
                    <span class="mx-1">•</span>
                    {{ $profile->profile_code }}
                @endif
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">

            {{-- EDIT --}}
            @can('industry_profiles.edit')
                    @if($profile->id)
        <a href="{{ route(
            'admin.assets.operations.industry-profiles.edit',
            ['industry_profile' => $profile->getKey()]
        ) }}"
           class="btn btn-sm btn-outline-primary"
           title="Edit profile">
            <i class="ri-edit-line"></i>
        </a>
    @endif
            @endcan
            @can('asset_reports.view')

    <a href="{{ route(
        'admin.assets.reports.industry-kpis.show',
        [
            'industry_profile' => $profile->getKey()
        ]
    ) }}"
       class="btn btn-outline-success">

        <i class="ri-bar-chart-grouped-line me-1"></i>

        Industry KPIs

    </a>

@endcan

            {{-- BACK --}}
            <a href="{{ route(
                'admin.assets.operations.industry-profiles.index'
            ) }}"
               class="btn btn-light border">

                <i class="ri-arrow-left-line me-1"></i>
                Back
            </a>

        </div>

    </div>


    {{-- =========================================================
        FLASH MESSAGES
    ========================================================== --}}

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show"
             role="alert">

            <i class="ri-check-line me-1"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>

        </div>
    @endif


    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

            <i class="ri-error-warning-line me-1"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>

        </div>
    @endif


    {{-- =========================================================
        PROFILE SUMMARY
    ========================================================== --}}

    <div class="row g-3 mb-4">

        {{-- PROFILE CODE --}}
        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center gap-2 mb-2">

                        <div class="text-primary">
                            <i class="ri-barcode-line fs-4"></i>
                        </div>

                        <small class="text-muted">
                            Profile Code
                        </small>

                    </div>

                    <h6 class="mb-0">
                        {{ $profile->profile_code ?: '—' }}
                    </h6>

                </div>

            </div>

        </div>


        {{-- INDUSTRY --}}
        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center gap-2 mb-2">

                        <div class="text-primary">
                            <i class="ri-building-line fs-4"></i>
                        </div>

                        <small class="text-muted">
                            Industry
                        </small>

                    </div>

                    <h6 class="mb-0">
                        {{ $profile->industry_type ?: '—' }}
                    </h6>

                </div>

            </div>

        </div>


        {{-- ASSIGNED ASSETS --}}
        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center gap-2 mb-2">

                        <div class="text-primary">
                            <i class="ri-building-4-line fs-4"></i>
                        </div>

                        <small class="text-muted">
                            Assigned Assets
                        </small>

                    </div>

                    <h6 class="mb-0">

                        {{ number_format(
                            (int) ($assetCount ?? $profile->asset_count ?? 0)
                        ) }}

                    </h6>

                </div>

            </div>

        </div>


        {{-- STATUS --}}
        <div class="col-xl-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center gap-2 mb-2">

                        <div class="text-primary">
                            <i class="ri-checkbox-circle-line fs-4"></i>
                        </div>

                        <small class="text-muted">
                            Status
                        </small>

                    </div>

                    @if($profile->is_active)

                        <span class="badge bg-success">
                            <i class="ri-checkbox-circle-line me-1"></i>
                            Active
                        </span>

                    @else

                        <span class="badge bg-secondary">
                            <i class="ri-close-circle-line me-1"></i>
                            Inactive
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        DESCRIPTION
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h6 class="mb-0">
                <i class="ri-file-text-line me-1"></i>
                Description
            </h6>

        </div>

        <div class="card-body">

            @if($profile->description)

                {!! nl2br(e($profile->description)) !!}

            @else

                <span class="text-muted">
                    No description available.
                </span>

            @endif

        </div>

    </div>


    {{-- =========================================================
        OCCUPANCY CONFIGURATION
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h6 class="mb-0">
                <i class="ri-home-gear-line me-1"></i>
                Occupancy Configuration
            </h6>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-6">

                    <small class="text-muted d-block mb-1">
                        Occupancy Target
                    </small>

                    <h5 class="mb-0">

                        @if($profile->occupancy_target !== null)

                            {{ number_format(
                                (float) $profile->occupancy_target,
                                2
                            ) }}%

                        @else
                            —
                        @endif

                    </h5>

                </div>


                <div class="col-md-6">

                    <small class="text-muted d-block mb-1">
                        Vacancy Threshold
                    </small>

                    <h5 class="mb-0">

                        @if($profile->vacancy_threshold !== null)

                            {{ number_format(
                                (float) $profile->vacancy_threshold,
                                2
                            ) }}%

                        @else
                            —
                        @endif

                    </h5>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        PERFORMANCE CONFIGURATION
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h6 class="mb-0">
                <i class="ri-line-chart-line me-1"></i>
                Performance Configuration
            </h6>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-xl-3 col-md-6">

                    <small class="text-muted d-block mb-1">
                        Performance Target
                    </small>

                    <h6 class="mb-0">

                        @if($profile->performance_target !== null)

                            {{ number_format(
                                (float) $profile->performance_target,
                                2
                            ) }}%

                        @else
                            —
                        @endif

                    </h6>

                </div>


                <div class="col-xl-3 col-md-6">

                    <small class="text-muted d-block mb-1">
                        Utilization Target
                    </small>

                    <h6 class="mb-0">

                        @if($profile->utilization_target !== null)

                            {{ number_format(
                                (float) $profile->utilization_target,
                                2
                            ) }}%

                        @else
                            —
                        @endif

                    </h6>

                </div>


                <div class="col-xl-3 col-md-6">

                    <small class="text-muted d-block mb-1">
                        Availability Target
                    </small>

                    <h6 class="mb-0">

                        @if($profile->availability_target !== null)

                            {{ number_format(
                                (float) $profile->availability_target,
                                2
                            ) }}%

                        @else
                            —
                        @endif

                    </h6>

                </div>


                <div class="col-xl-3 col-md-6">

                    <small class="text-muted d-block mb-1">
                        Efficiency Target
                    </small>

                    <h6 class="mb-0">

                        @if($profile->efficiency_target !== null)

                            {{ number_format(
                                (float) $profile->efficiency_target,
                                2
                            ) }}%

                        @else
                            —
                        @endif

                    </h6>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        CONDITION CONFIGURATION
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h6 class="mb-0">
                <i class="ri-tools-line me-1"></i>
                Condition Configuration
            </h6>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <small class="text-muted d-block mb-1">
                        Review Frequency
                    </small>

                    <h6 class="mb-0">

                        @if($profile->condition_review_frequency_months !== null)

                            {{ $profile->condition_review_frequency_months }}
                            months

                        @else
                            —
                        @endif

                    </h6>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block mb-1">
                        Warning Threshold
                    </small>

                    <h6 class="mb-0">

                        @if($profile->condition_warning_threshold !== null)

                            {{ number_format(
                                (float) $profile->condition_warning_threshold,
                                2
                            ) }}%

                        @else
                            —
                        @endif

                    </h6>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block mb-1">
                        Critical Threshold
                    </small>

                    <h6 class="mb-0">

                        @if($profile->condition_critical_threshold !== null)

                            {{ number_format(
                                (float) $profile->condition_critical_threshold,
                                2
                            ) }}%

                        @else
                            —
                        @endif

                    </h6>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FINANCIAL CONFIGURATION
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h6 class="mb-0">
                <i class="ri-money-rupee-circle-line me-1"></i>
                Financial Configuration
            </h6>

        </div>

        <div class="card-body">

            <div class="row g-4">

                {{-- REVENUE --}}
                <div class="col-md-4">

                    <small class="text-muted d-block mb-2">
                        Revenue Tracking
                    </small>

                    @if($profile->revenue_tracking)

                        <span class="badge bg-success">
                            <i class="ri-checkbox-circle-line me-1"></i>
                            Enabled
                        </span>

                    @else

                        <span class="badge bg-secondary">
                            <i class="ri-close-circle-line me-1"></i>
                            Disabled
                        </span>

                    @endif

                </div>


                {{-- OPERATING EXPENSE --}}
                <div class="col-md-4">

                    <small class="text-muted d-block mb-2">
                        Operating Expense Tracking
                    </small>

                    @if($profile->operating_expense_tracking)

                        <span class="badge bg-success">
                            <i class="ri-checkbox-circle-line me-1"></i>
                            Enabled
                        </span>

                    @else

                        <span class="badge bg-secondary">
                            <i class="ri-close-circle-line me-1"></i>
                            Disabled
                        </span>

                    @endif

                </div>


                {{-- CAPEX --}}
                <div class="col-md-4">

                    <small class="text-muted d-block mb-2">
                        CAPEX Tracking
                    </small>

                    @if($profile->capex_tracking)

                        <span class="badge bg-success">
                            <i class="ri-checkbox-circle-line me-1"></i>
                            Enabled
                        </span>

                    @else

                        <span class="badge bg-secondary">
                            <i class="ri-close-circle-line me-1"></i>
                            Disabled
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        LIFECYCLE CONFIGURATION
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h6 class="mb-0">
                <i class="ri-history-line me-1"></i>
                Lifecycle Configuration
            </h6>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-xl-3 col-md-6">

                    <small class="text-muted d-block mb-1">
                        Default Asset Life
                    </small>

                    <h6 class="mb-0">

                        @if($profile->default_asset_life_years !== null)

                            {{ number_format(
                                (float) $profile->default_asset_life_years,
                                2
                            ) }}
                            years

                        @else
                            —
                        @endif

                    </h6>

                </div>


                <div class="col-xl-3 col-md-6">

                    <small class="text-muted d-block mb-1">
                        Renewal Period
                    </small>

                    <h6 class="mb-0">

                        @if($profile->renewal_period_years !== null)

                            {{ number_format(
                                (float) $profile->renewal_period_years,
                                2
                            ) }}
                            years

                        @else
                            —
                        @endif

                    </h6>

                </div>


                <div class="col-xl-3 col-md-6">

                    <small class="text-muted d-block mb-1">
                        Replacement Threshold
                    </small>

                    <h6 class="mb-0">

                        @if($profile->replacement_threshold !== null)

                            {{ number_format(
                                (float) $profile->replacement_threshold,
                                2
                            ) }}%

                        @else
                            —
                        @endif

                    </h6>

                </div>


                <div class="col-xl-3 col-md-6">

                    <small class="text-muted d-block mb-1">
                        Review Frequency
                    </small>

                    <h6 class="mb-0">

                        @if($profile->lifecycle_review_frequency_months !== null)

                            {{ $profile->lifecycle_review_frequency_months }}
                            months

                        @else
                            —
                        @endif

                    </h6>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        INDUSTRY KPI
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h6 class="mb-0">
                <i class="ri-dashboard-line me-1"></i>
                Industry KPIs
            </h6>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-6">

                    <small class="text-muted d-block mb-1">
                        Primary KPI
                    </small>

                    <h6 class="mb-0">
                        {{ $profile->primary_kpi ?: '—' }}
                    </h6>

                </div>


                <div class="col-md-6">

                    <small class="text-muted d-block mb-1">
                        Secondary KPI
                    </small>

                    <h6 class="mb-0">
                        {{ $profile->secondary_kpi ?: '—' }}
                    </h6>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        SETTINGS
    ========================================================== --}}

    @if(!empty($profile->settings))

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h6 class="mb-0">
                    <i class="ri-settings-3-line me-1"></i>
                    Additional Settings
                </h6>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    @foreach($profile->settings as $key => $value)

                        <div class="col-md-6">

                            <div class="border rounded p-3 h-100">

                                <small class="text-muted d-block mb-1">
                                    {{ ucwords(str_replace('_', ' ', $key)) }}
                                </small>

                                <span class="fw-semibold">

                                    @if(is_array($value))
                                        {{ json_encode($value) }}
                                    @elseif(is_bool($value))
                                        {{ $value ? 'Yes' : 'No' }}
                                    @elseif($value !== null && $value !== '')
                                        {{ $value }}
                                    @else
                                        —
                                    @endif

                                </span>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
        REMARKS
    ========================================================== --}}

    @if($profile->remarks)

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h6 class="mb-0">
                    <i class="ri-sticky-note-line me-1"></i>
                    Remarks
                </h6>

            </div>

            <div class="card-body">

                {!! nl2br(e($profile->remarks)) !!}

            </div>

        </div>

    @endif


    {{-- =========================================================
        AUDIT INFORMATION
    ========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <h6 class="mb-0">
                <i class="ri-time-line me-1"></i>
                Record Information
            </h6>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-6">

                    <small class="text-muted d-block mb-1">
                        Created At
                    </small>

                    <span>
                        {{ $profile->created_at
                            ? $profile->created_at->format('d M Y, h:i A')
                            : '—' }}
                    </span>

                </div>


                <div class="col-md-6">

                    <small class="text-muted d-block mb-1">
                        Last Updated
                    </small>

                    <span>
                        {{ $profile->updated_at
                            ? $profile->updated_at->format('d M Y, h:i A')
                            : '—' }}
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        DELETE PROFILE
    ========================================================== --}}

    @can('industry_profiles.delete')

        @php
            $assignedAssetCount = (int) (
                $assetCount
                ?? $profile->asset_count
                ?? 0
            );
        @endphp

        @if($assignedAssetCount === 0)

            <div class="card border-danger shadow-sm mb-4">

                <div class="card-body">

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                        <div>

                            <h6 class="text-danger mb-1">
                                <i class="ri-delete-bin-line me-1"></i>
                                Delete Industry Profile
                            </h6>

                            <p class="text-muted small mb-0">
                                This action permanently removes this industry
                                operating profile.
                            </p>

                        </div>

                        <form method="POST"
                              action="{{ route(
                                  'admin.assets.operations.industry-profiles.destroy',
                                  ['industry_profile' => $profile->id]
                              ) }}"
                              onsubmit="return confirm('Are you sure you want to delete this industry profile?');">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn btn-outline-danger">

                                <i class="ri-delete-bin-line me-1"></i>
                                Delete Profile

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        @else

            <div class="alert alert-warning mb-4">

                <i class="ri-information-line me-1"></i>

                This profile cannot be deleted because it is currently
                assigned to
                <strong>{{ number_format($assignedAssetCount) }}</strong>
                {{ $assignedAssetCount === 1 ? 'asset' : 'assets' }}.

            </div>

        @endif

    @endcan


</div>

@endsection