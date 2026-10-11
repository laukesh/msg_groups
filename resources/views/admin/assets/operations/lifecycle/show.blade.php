@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                <i class="ri-history-line me-1"></i>
                Asset Lifecycle Details
            </h4>

            <p class="text-muted mb-0">
                Lifecycle planning and replacement information.
            </p>

        </div>

        <div class="d-flex gap-2">

            @can('lifecycle.edit')

                <a href="{{ route('admin.assets.operations.lifecycle.edit', $lifecycle) }}"
                   class="btn btn-primary">

                    <i class="ri-edit-line me-1"></i>
                    Edit

                </a>

            @endcan

            <a href="{{ route('admin.assets.operations.lifecycle.index') }}"
               class="btn btn-light">

                <i class="ri-arrow-left-line me-1"></i>
                Back

            </a>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="ri-check-line me-1"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Asset --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header">

            <h6 class="mb-0">
                <i class="ri-building-line me-1"></i>
                Asset Information
            </h6>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-3">

                    <small class="text-muted">
                        Asset Code
                    </small>

                    <div class="fw-semibold">
                        {{ $lifecycle->asset?->asset_code ?? '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        Asset Name
                    </small>

                    <div class="fw-semibold">
                        {{ $lifecycle->asset?->asset_name ?? '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        Category
                    </small>

                    <div>
                        {{ $lifecycle->asset?->asset_category ?? '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        Asset Type
                    </small>

                    <div>
                        {{ $lifecycle->asset?->asset_type ?? '—' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Lifecycle Summary --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Lifecycle Stage
                    </small>

                    <h5 class="mt-2">

                        <span class="badge bg-primary">
                            {{ $lifecycle->lifecycle_stage }}
                        </span>

                    </h5>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Lifecycle Status
                    </small>

                    <h5 class="mt-2">

                        <span class="badge bg-success">
                            {{ $lifecycle->lifecycle_status }}
                        </span>

                    </h5>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Priority
                    </small>

                    <h5 class="mt-2">

                        <span class="badge bg-warning text-dark">
                            {{ $lifecycle->priority }}
                        </span>

                    </h5>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Lifecycle Score
                    </small>

                    <h5 class="mt-2">

                        @if($lifecycle->lifecycle_score !== null)

                            {{ number_format($lifecycle->lifecycle_score, 2) }}
                            / 100

                        @else

                            —

                        @endif

                    </h5>

                </div>

            </div>

        </div>

    </div>


    {{-- Useful Life --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header">

            <h6 class="mb-0">
                <i class="ri-time-line me-1"></i>
                Useful Life & Dates
            </h6>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">

                    <small class="text-muted">
                        Acquisition Date
                    </small>

                    <div>
                        {{ $lifecycle->acquisition_date?->format('d M Y') ?? '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        Commissioning Date
                    </small>

                    <div>
                        {{ $lifecycle->commissioning_date?->format('d M Y') ?? '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        Expected Life
                    </small>

                    <div>
                        {{ $lifecycle->expected_life_years !== null
                            ? number_format($lifecycle->expected_life_years, 2) . ' years'
                            : '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        Remaining Life
                    </small>

                    <div>
                        {{ $lifecycle->remaining_life_years !== null
                            ? number_format($lifecycle->remaining_life_years, 2) . ' years'
                            : '—' }}
                    </div>

                </div>


                <div class="col-md-4">

                    <small class="text-muted">
                        Planned Renewal
                    </small>

                    <div>
                        {{ $lifecycle->planned_renewal_date?->format('d M Y') ?? '—' }}
                    </div>

                </div>


                <div class="col-md-4">

                    <small class="text-muted">
                        Planned Replacement
                    </small>

                    <div class="fw-semibold">
                        {{ $lifecycle->planned_replacement_date?->format('d M Y') ?? '—' }}
                    </div>

                </div>


                <div class="col-md-4">

                    <small class="text-muted">
                        Actual Replacement
                    </small>

                    <div>
                        {{ $lifecycle->actual_replacement_date?->format('d M Y') ?? '—' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Financial Information --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header">

            <h6 class="mb-0">
                <i class="ri-money-rupee-circle-line me-1"></i>
                Financial Information
            </h6>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">

                    <small class="text-muted">
                        Acquisition Cost
                    </small>

                    <div class="fw-semibold">
                        $ {{ number_format($lifecycle->acquisition_cost ?? 0, 2) }}
                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        Current Book Value
                    </small>

                    <div class="fw-semibold">
                        $ {{ number_format($lifecycle->current_book_value ?? 0, 2) }}
                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        Replacement Cost
                    </small>

                    <div class="fw-semibold">
                        $ {{ number_format($lifecycle->estimated_replacement_cost ?? 0, 2) }}
                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        Residual Value
                    </small>

                    <div class="fw-semibold">
                        $ {{ number_format($lifecycle->residual_value ?? 0, 2) }}
                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        Depreciation
                    </small>

                    <div>
                        {{ number_format($lifecycle->depreciation_percentage ?? 0, 2) }}%
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Recommendation --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header">

            <h6 class="mb-0">
                <i class="ri-lightbulb-line me-1"></i>
                Lifecycle Recommendation
            </h6>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <small class="text-muted">
                        Recommended Action
                    </small>

                    <div class="mt-1">

                        @if($lifecycle->recommended_action)

                            <span class="badge bg-primary">
                                {{ $lifecycle->recommended_action }}
                            </span>

                        @else

                            —

                        @endif

                    </div>

                </div>


                <div class="col-md-4">

                    <small class="text-muted">
                        Responsible User
                    </small>

                    <div>
                        {{ $lifecycle->responsibleUser?->name ?? '—' }}
                    </div>

                </div>


                <div class="col-md-4">

                    <small class="text-muted">
                        Next Review
                    </small>

                    <div>
                        {{ $lifecycle->next_review_date?->format('d M Y') ?? '—' }}
                    </div>

                </div>


                <div class="col-md-4">

                    <small class="text-muted">
                        Last Reviewed
                    </small>

                    <div>
                        {{ $lifecycle->last_reviewed_at?->format('d M Y') ?? '—' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Remarks --}}
    @if($lifecycle->remarks)

        <div class="card shadow-sm mb-4">

            <div class="card-header">

                <h6 class="mb-0">
                    <i class="ri-file-text-line me-1"></i>
                    Remarks
                </h6>

            </div>

            <div class="card-body">

                {!! nl2br(e($lifecycle->remarks)) !!}

            </div>

        </div>

    @endif


    {{-- Audit Information --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header">

            <h6 class="mb-0">
                <i class="ri-history-line me-1"></i>
                Record Information
            </h6>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">

                    <small class="text-muted">
                        Created By
                    </small>

                    <div>
                        {{ $lifecycle->creator?->name ?? 'System' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        Created At
                    </small>

                    <div>
                        {{ $lifecycle->created_at?->format('d M Y H:i') ?? '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        Updated By
                    </small>

                    <div>
                        {{ $lifecycle->updater?->name ?? '—' }}
                    </div>

                </div>


                <div class="col-md-3">

                    <small class="text-muted">
                        Updated At
                    </small>

                    <div>
                        {{ $lifecycle->updated_at?->format('d M Y H:i') ?? '—' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Delete --}}
    @can('lifecycle.delete')

        <div class="d-flex justify-content-end mb-4">

            <form method="POST"
                  action="{{ route('admin.assets.operations.lifecycle.destroy', $lifecycle) }}"
                  onsubmit="return confirm('Are you sure you want to delete this lifecycle record?');">

                @csrf
                @method('DELETE')

                <button type="submit"
                        class="btn btn-outline-danger">

                    <i class="ri-delete-bin-line me-1"></i>
                    Delete Lifecycle Record

                </button>

            </form>

        </div>

    @endcan

</div>

@endsection