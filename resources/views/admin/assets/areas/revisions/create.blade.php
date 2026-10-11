@extends('layouts.app')

@section('title', 'Create Area Revision')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                <i class="ri-edit-box-line me-1"></i>
                Create Area Revision
            </h4>

            <div class="text-muted">
                {{ $area->area_code ?? 'Area' }}
                -
                {{ $area->name ?? $area->area_name ?? 'Property Area' }}
            </div>

        </div>


        <a href="{{ route(
            'admin.assets.revisions.index',
            $area
        ) }}"
           class="btn btn-outline-secondary">

            <i class="ri-arrow-left-line me-1"></i>
            Back to Revisions

        </a>

    </div>


    {{-- CURRENT AREA --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Current Area
            </h5>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Gross Area
                    </div>

                    <div class="fs-5 fw-semibold">
                        {{ number_format($area->gross_area ?? 0, 2) }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Net Area
                    </div>

                    <div class="fs-5 fw-semibold">
                        {{ number_format($area->net_area ?? 0, 2) }}
                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Rentable Area
                    </div>

                    <div class="fs-5 fw-semibold">
                        {{ number_format($area->rentable_area ?? 0, 2) }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- FORM --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                New Area Values
            </h5>

        </div>


        <div class="card-body">

            <form method="POST"
                  action="{{ route(
                      'admin.assets.revisions.store',
                      $area
                  ) }}">

                @csrf


                {{-- =================================================
                    AREA VALUES
                ================================================== --}}
                <div class="row g-3 mb-4">

                    <div class="col-md-4">

                        <label class="form-label">
                            New Gross Area
                        </label>

                        <input type="number"
                               name="new_gross_area"
                               value="{{ old(
                                   'new_gross_area',
                                   $area->gross_area
                               ) }}"
                               step="0.01"
                               min="0"
                               class="form-control @error('new_gross_area') is-invalid @enderror">

                        @error('new_gross_area')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            New Net Area
                        </label>

                        <input type="number"
                               name="new_net_area"
                               value="{{ old(
                                   'new_net_area',
                                   $area->net_area
                               ) }}"
                               step="0.01"
                               min="0"
                               class="form-control @error('new_net_area') is-invalid @enderror">

                        @error('new_net_area')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            New Rentable Area
                        </label>

                        <input type="number"
                               name="new_rentable_area"
                               value="{{ old(
                                   'new_rentable_area',
                                   $area->rentable_area
                               ) }}"
                               step="0.01"
                               min="0"
                               class="form-control @error('new_rentable_area') is-invalid @enderror">

                        @error('new_rentable_area')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                {{-- =================================================
                    REVISION INFORMATION
                ================================================== --}}
                <div class="row g-3 mb-4">

                    <div class="col-md-6">

                        <label class="form-label">
                            Reason <span class="text-danger">*</span>
                        </label>

                        <textarea
                            name="reason"
                            rows="4"
                            required
                            class="form-control @error('reason') is-invalid @enderror"
                            placeholder="Explain why the area is being revised...">{{ old('reason') }}</textarea>

                        @error('reason')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            rows="4"
                            class="form-control @error('remarks') is-invalid @enderror"
                            placeholder="Additional remarks...">{{ old('remarks') }}</textarea>

                        @error('remarks')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                <div class="row g-3 mb-4">

                    <div class="col-md-6">

                        <label class="form-label">
                            Revision Date <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="revision_date"
                               value="{{ old(
                                   'revision_date',
                                   now()->format('Y-m-d')
                               ) }}"
                               required
                               class="form-control @error('revision_date') is-invalid @enderror">

                        @error('revision_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Effective From
                        </label>

                        <input type="date"
                               name="effective_from"
                               value="{{ old('effective_from') }}"
                               class="form-control @error('effective_from') is-invalid @enderror">

                        @error('effective_from')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                {{-- WARNING --}}
                <div class="alert alert-warning">

                    <i class="ri-information-line me-1"></i>

                    The revision will initially be saved as
                    <strong>DRAFT</strong>.

                    The current area values will only change after the
                    revision is submitted and approved.

                </div>


                {{-- BUTTONS --}}
                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route(
                        'admin.assets.revisions.index',
                        $area
                    ) }}"
                       class="btn btn-secondary">

                        Cancel

                    </a>


                    <button type="submit"
                            class="btn btn-primary">

                        <i class="ri-save-line me-1"></i>

                        Create Revision

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection