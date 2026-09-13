@extends('layouts.app')

@section('title', $status->status_name)

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                <i class="fas fa-toggle-on me-2"></i>
                {{ $status->status_name }}
            </h4>

            <div class="text-muted">
                Unit Status #{{ $status->id }}
            </div>

        </div>


        <div class="d-flex gap-2">

            @can('unit_statuses.edit')

                <a
                    href="{{ route(
                        'admin.assets.unit-statuses.edit',
                        $status->id
                    ) }}"
                    class="btn btn-primary"
                >
                    <i class="fas fa-edit me-1"></i>
                    Edit
                </a>

            @endcan


            <a
                href="{{ route(
                    'admin.assets.unit-statuses.index'
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="fas fa-arrow-left me-1"></i>
                Back
            </a>

        </div>

    </div>


    {{-- =========================================================
        UNIT STATUS INFORMATION
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">

                <i class="fas fa-info-circle me-2"></i>
                Unit Status Information

            </h5>

        </div>


        <div class="card-body">

            <div class="row">

                {{-- ID --}}
                <div class="col-lg-4 col-md-6 mb-3">

                    <strong>
                        ID
                    </strong>

                    <div class="mt-1">
                        {{ $status->id }}
                    </div>

                </div>


                {{-- Status Name --}}
                <div class="col-lg-4 col-md-6 mb-3">

                    <strong>
                        Status Name
                    </strong>

                    <div class="mt-1">
                        {{ $status->status_name }}
                    </div>

                </div>


                {{-- Active --}}
                <div class="col-lg-4 col-md-6 mb-3">

                    <strong>
                        Status
                    </strong>

                    <div class="mt-1">

                        @if($status->is_active)

                            <span class="badge bg-success">

                                <i class="fas fa-check-circle me-1"></i>
                                Active

                            </span>

                        @else

                            <span class="badge bg-secondary">

                                <i class="fas fa-times-circle me-1"></i>
                                Inactive

                            </span>

                        @endif

                    </div>

                </div>


                {{-- Color --}}
                <div class="col-lg-6 col-md-6 mb-3">

                    <strong>
                        Color
                    </strong>

                    <div class="d-flex align-items-center mt-2">

                        @if($status->color_code)

                            <span
                                class="me-2"
                                style="
                                    display:inline-block;
                                    width:32px;
                                    height:32px;
                                    background-color: {{ $status->color_code }};
                                    border:1px solid #ced4da;
                                    border-radius:6px;
                                "
                                title="{{ $status->color_code }}"
                            ></span>

                            <code>
                                {{ $status->color_code }}
                            </code>

                        @else

                            <span class="text-muted">
                                Not specified
                            </span>

                        @endif

                    </div>

                </div>


                {{-- Sort Order --}}
                <div class="col-lg-6 col-md-6 mb-3">

                    <strong>
                        Sort Order
                    </strong>

                    <div class="mt-2">

                        <span class="badge bg-light text-dark border">

                            {{ $status->sort_order ?? 0 }}

                        </span>

                    </div>

                </div>


                {{-- Description --}}
                <div class="col-12 mb-3">

                    <strong>
                        Description
                    </strong>

                    <div class="border rounded bg-light p-3 mt-2">

                        {{ $status->description ?: 'No description available.' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        AUDIT INFORMATION
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0">

                <i class="fas fa-history me-2"></i>
                Audit Information

            </h5>

        </div>


        <div class="card-body">

            <div class="row">

                {{-- Created By --}}
                <div class="col-lg-3 col-md-6 mb-3">

                    <strong>
                        Created By
                    </strong>

                    <div class="mt-1">

                        {{ $status->creator?->name ?? '-' }}

                    </div>

                </div>


                {{-- Updated By --}}
                <div class="col-lg-3 col-md-6 mb-3">

                    <strong>
                        Updated By
                    </strong>

                    <div class="mt-1">

                        {{ $status->updater?->name ?? '-' }}

                    </div>

                </div>


                {{-- Created At --}}
                <div class="col-lg-3 col-md-6 mb-3">

                    <strong>
                        Created At
                    </strong>

                    <div class="mt-1">

                        {{ $status->created_at?->format(
                            'd M Y H:i'
                        ) ?? '-' }}

                    </div>

                </div>


                {{-- Updated At --}}
                <div class="col-lg-3 col-md-6 mb-3">

                    <strong>
                        Updated At
                    </strong>

                    <div class="mt-1">

                        {{ $status->updated_at?->format(
                            'd M Y H:i'
                        ) ?? '-' }}

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection