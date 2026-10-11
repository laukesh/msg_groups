@php
    $isEdit = isset($revision) && $revision?->exists;
@endphp

<form action="{{ $action }}" method="POST">
    @csrf

    @if(isset($method) && $method !== 'POST')
        @method($method)
    @endif

    <div class="row g-4">

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Current Area</h5>
                </div>

                <div class="card-body">
                    <div class="row">

                        <div class="col-md-4">
                            <span class="text-muted">Gross Area</span>
                            <div class="fw-semibold">
                                {{ number_format($area->gross_area ?? 0, 4) }}
                                {{ $area->measurement_unit }}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <span class="text-muted">Net Area</span>
                            <div class="fw-semibold">
                                {{ number_format($area->net_area ?? 0, 4) }}
                                {{ $area->measurement_unit }}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <span class="text-muted">Rentable Area</span>
                            <div class="fw-semibold">
                                {{ number_format($area->rentable_area ?? 0, 4) }}
                                {{ $area->measurement_unit }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Revised Measurements</h5>
                </div>

                <div class="card-body">
                    <div class="row g-3">

                        @foreach([
                            'new_gross_area'    => 'New Gross Area',
                            'new_net_area'      => 'New Net Area',
                            'new_rentable_area' => 'New Rentable Area',
                        ] as $field => $label)

                            <div class="col-md-4">
                                <label class="form-label">
                                    {{ $label }}
                                </label>

                                <input type="number"
                                       name="{{ $field }}"
                                       step="0.0001"
                                       min="0"
                                       value="{{ old($field, $revision?->{$field} ?? $area->{str_replace('new_', '', $field)}) }}"
                                       class="form-control @error($field) is-invalid @enderror">

                                @error($field)
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        @endforeach

                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Revision Details</h5>
                </div>

                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">
                                Reason <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="reason"
                                   value="{{ old('reason', $revision?->reason) }}"
                                   class="form-control @error('reason') is-invalid @enderror"
                                   maxlength="500"
                                   required>

                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">
                                Revision Date <span class="text-danger">*</span>
                            </label>

                            <input type="date"
                                   name="revision_date"
                                   value="{{ old('revision_date', $revision?->revision_date?->format('Y-m-d')) }}"
                                   class="form-control @error('revision_date') is-invalid @enderror"
                                   required>

                            @error('revision_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Effective From</label>

                            <input type="date"
                                   name="effective_from"
                                   value="{{ old('effective_from', $revision?->effective_from?->format('Y-m-d')) }}"
                                   class="form-control @error('effective_from') is-invalid @enderror">

                            @error('effective_from')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Remarks</label>

                            <textarea name="remarks"
                                      rows="4"
                                      class="form-control">{{ old('remarks', $revision?->remarks) }}</textarea>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="d-flex justify-content-end gap-2">

                <a href="{{ url()->previous() }}"
                   class="btn btn-outline-secondary">
                    Cancel
                </a>

                <button type="submit" class="btn btn-primary">
                    {{ $isEdit ? 'Update Revision' : 'Save Revision' }}
                </button>

            </div>
        </div>

    </div>
</form>