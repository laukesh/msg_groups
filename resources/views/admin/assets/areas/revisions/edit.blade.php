@extends('layouts.app')

@section('title', 'Edit Area Revision')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="ri-edit-line me-1"></i>
                Edit Area Revision
            </h4>

            <div class="text-muted">
                {{ $area->area_code ?? 'Area' }}
                -
                {{ $area->name ?? $area->area_name ?? 'Property Area' }}

                <span class="mx-2">/</span>

                Revision
                REV-{{
                    str_pad(
                        $revision->revision_number ?? 0,
                        3,
                        '0',
                        STR_PAD_LEFT
                    )
                }}
            </div>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.assets.revisions.show',
                [
                    'area' => $area->id,
                    'revision' => $revision->id,
                ]
            ) }}"
               class="btn btn-outline-primary">

                <i class="ri-eye-line me-1"></i>
                View

            </a>

            <a href="{{ route(
                'admin.assets.revisions.index',
                [
                    'area' => $area->id,
                ]
            ) }}"
               class="btn btn-outline-secondary">

                <i class="ri-arrow-left-line me-1"></i>
                Back

            </a>

        </div>

    </div>


    {{-- =========================================================
        ALERTS
    ========================================================== --}}

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


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="ri-error-warning-line me-1"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-semibold mb-2">
                <i class="ri-error-warning-line me-1"></i>
                Please correct the following errors:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
        CURRENT REVISION INFORMATION
    ========================================================== --}}

    <div class="row g-3 mb-4">

        {{-- Revision --}}
        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Revision
                    </div>

                    <h5 class="mb-0 mt-1">

                        REV-{{
                            str_pad(
                                $revision->revision_number ?? 0,
                                3,
                                '0',
                                STR_PAD_LEFT
                            )
                        }}

                    </h5>

                </div>

            </div>

        </div>


        {{-- Status --}}
        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Status
                    </div>

                    @php
                        $status = strtoupper(
                            (string) ($revision->status ?? 'DRAFT')
                        );

                        $statusClass = match($status) {
                            'DRAFT' => 'bg-secondary',
                            'SUBMITTED' => 'bg-warning text-dark',
                            'APPROVED' => 'bg-success',
                            'REJECTED' => 'bg-danger',
                            default => 'bg-dark',
                        };
                    @endphp

                    <div class="mt-2">

                        <span class="badge {{ $statusClass }}">
                            {{ $status }}
                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- Current Gross Area --}}
        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Current Gross Area
                    </div>

                    <h5 class="mb-0 mt-1">

                        {{ number_format(
                            $area->gross_area ?? 0,
                            2
                        ) }}

                    </h5>

                </div>

            </div>

        </div>


        {{-- Current Net Area --}}
        <div class="col-md-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Current Net Area
                    </div>

                    <h5 class="mb-0 mt-1">

                        {{ number_format(
                            $area->net_area ?? 0,
                            2
                        ) }}

                    </h5>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        EDIT FORM
    ========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="mb-0">
                        <i class="ri-ruler-line me-1"></i>
                        Area Revision Details
                    </h5>

                    <small class="text-muted">
                        Update the proposed area values and revision details.
                    </small>

                </div>

                <span class="badge bg-secondary">
                    Draft
                </span>

            </div>

        </div>


        {{-- =====================================================
            IMPORTANT:
            UPDATE ROUTE REQUIRES BOTH AREA AND REVISION
        ====================================================== --}}

        <form method="POST"
              action="{{ route(
                  'admin.assets.revisions.update',
                  [
                      'area' => $area->id,
                      'revision' => $revision->id,
                  ]
              ) }}">

            @csrf

            @method('PUT')


            <div class="card-body">

                {{-- =================================================
                    PROPOSED AREA VALUES
                ================================================== --}}

                <h6 class="fw-semibold mb-3">

                    <i class="ri-ruler-2-line me-1"></i>
                    Proposed Area Values

                </h6>


                <div class="row g-3 mb-4">

                    {{-- Gross Area --}}
                    <div class="col-md-4">

                        <label for="new_gross_area"
                               class="form-label">

                            New Gross Area

                            <span class="text-danger">*</span>

                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="ri-building-line"></i>
                            </span>

                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   name="new_gross_area"
                                   id="new_gross_area"
                                   class="form-control @error('new_gross_area') is-invalid @enderror"
                                   value="{{ old(
                                       'new_gross_area',
                                       $revision->new_gross_area
                                   ) }}"
                                   placeholder="Enter gross area">

                            @error('new_gross_area')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                        <small class="text-muted">
                            Previous:
                            {{ number_format(
                                $revision->previous_gross_area ?? 0,
                                2
                            ) }}
                        </small>

                    </div>


                    {{-- Net Area --}}
                    <div class="col-md-4">

                        <label for="new_net_area"
                               class="form-label">

                            New Net Area

                            <span class="text-danger">*</span>

                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="ri-layout-grid-line"></i>
                            </span>

                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   name="new_net_area"
                                   id="new_net_area"
                                   class="form-control @error('new_net_area') is-invalid @enderror"
                                   value="{{ old(
                                       'new_net_area',
                                       $revision->new_net_area
                                   ) }}"
                                   placeholder="Enter net area">

                            @error('new_net_area')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                        <small class="text-muted">
                            Previous:
                            {{ number_format(
                                $revision->previous_net_area ?? 0,
                                2
                            ) }}
                        </small>

                    </div>


                    {{-- Rentable Area --}}
                    <div class="col-md-4">

                        <label for="new_rentable_area"
                               class="form-label">

                            New Rentable Area

                            <span class="text-danger">*</span>

                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="ri-ruler-line"></i>
                            </span>

                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   name="new_rentable_area"
                                   id="new_rentable_area"
                                   class="form-control @error('new_rentable_area') is-invalid @enderror"
                                   value="{{ old(
                                       'new_rentable_area',
                                       $revision->new_rentable_area
                                   ) }}"
                                   placeholder="Enter rentable area">

                            @error('new_rentable_area')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                        <small class="text-muted">
                            Previous:
                            {{ number_format(
                                $revision->previous_rentable_area ?? 0,
                                2
                            ) }}
                        </small>

                    </div>

                </div>


                <hr class="my-4">


                {{-- =================================================
                    REVISION INFORMATION
                ================================================== --}}

                <h6 class="fw-semibold mb-3">

                    <i class="ri-file-edit-line me-1"></i>
                    Revision Information

                </h6>


                <div class="row g-3">

                    {{-- Reason --}}
                    <div class="col-md-12">

                        <label for="reason"
                               class="form-label">

                            Reason for Revision

                            <span class="text-danger">*</span>

                        </label>

                        <textarea name="reason"
                                  id="reason"
                                  rows="3"
                                  class="form-control @error('reason') is-invalid @enderror"
                                  placeholder="Enter reason for changing the area values...">{{ old(
                                      'reason',
                                      $revision->reason
                                  ) }}</textarea>

                        @error('reason')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Revision Date --}}
                    <div class="col-md-6">

                        <label for="revision_date"
                               class="form-label">

                            Revision Date

                            <span class="text-danger">*</span>

                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="ri-calendar-line"></i>
                            </span>

                            <input type="date"
                                   name="revision_date"
                                   id="revision_date"
                                   class="form-control @error('revision_date') is-invalid @enderror"
                                   value="{{ old(
                                       'revision_date',
                                       $revision->revision_date
                                           ? \Carbon\Carbon::parse(
                                               $revision->revision_date
                                           )->format('Y-m-d')
                                           : ''
                                   ) }}">

                            @error('revision_date')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    {{-- Effective From --}}
                    <div class="col-md-6">

                        <label for="effective_from"
                               class="form-label">

                            Effective From

                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="ri-calendar-check-line"></i>
                            </span>

                            <input type="date"
                                   name="effective_from"
                                   id="effective_from"
                                   class="form-control @error('effective_from') is-invalid @enderror"
                                   value="{{ old(
                                       'effective_from',
                                       $revision->effective_from
                                           ? \Carbon\Carbon::parse(
                                               $revision->effective_from
                                           )->format('Y-m-d')
                                           : ''
                                   ) }}">

                            @error('effective_from')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    {{-- Remarks --}}
                    <div class="col-md-12">

                        <label for="remarks"
                               class="form-label">

                            Remarks

                        </label>

                        <textarea name="remarks"
                                  id="remarks"
                                  rows="4"
                                  class="form-control @error('remarks') is-invalid @enderror"
                                  placeholder="Enter additional remarks...">{{ old(
                                      'remarks',
                                      $revision->remarks
                                  ) }}</textarea>

                        @error('remarks')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>


            {{-- =====================================================
                FORM FOOTER
            ====================================================== --}}

            <div class="card-footer bg-white border-top py-3">

                <div class="d-flex justify-content-between">

                    <a href="{{ route(
                        'admin.assets.revisions.show',
                        [
                            'area' => $area->id,
                            'revision' => $revision->id,
                        ]
                    ) }}"
                       class="btn btn-outline-secondary">

                        <i class="ri-close-line me-1"></i>
                        Cancel

                    </a>


                    <div class="d-flex gap-2">

                        <button type="reset"
                                class="btn btn-outline-secondary">

                            <i class="ri-refresh-line me-1"></i>
                            Reset

                        </button>


                        <button type="submit"
                                class="btn btn-primary">

                            <i class="ri-save-line me-1"></i>
                            Update Revision

                        </button>

                    </div>

                </div>

            </div>

        </form>

    </div>


    {{-- =========================================================
        REJECTED REVISION NOTICE
    ========================================================== --}}

    @if($status === 'REJECTED')

        <div class="alert alert-danger mt-4">

            <div class="d-flex">

                <i class="ri-error-warning-line fs-4 me-2"></i>

                <div>

                    <strong>
                        This revision was rejected.
                    </strong>

                    @if($revision->remarks)

                        <div class="mt-1">
                            {{ $revision->remarks }}
                        </div>

                    @endif

                    <div class="small mt-2">
                        You can update the revision and submit it
                        again for approval.
                    </div>

                </div>

            </div>

        </div>

    @endif

</div>

@endsection