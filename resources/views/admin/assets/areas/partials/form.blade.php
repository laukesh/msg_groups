@php
    $isEdit = isset($area) && $area?->exists;

    $action = $action
        ?? ($isEdit
            ? route('admin.assets.areas.update', $area)
            : route('admin.assets.areas.store'));

    $method = $method
        ?? ($isEdit ? 'PUT' : 'POST');
@endphp

<form action="{{ $action }}" method="POST">
    @csrf

    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="row g-4">

        {{-- Property Location --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Property Location</h5>
                </div>

                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label">
                                Mall <span class="text-danger">*</span>
                            </label>

                            <select name="mall_id"
                                    class="form-select @error('mall_id') is-invalid @enderror"
                                    required>
                                <option value="">Select Mall</option>

                                @foreach($malls ?? [] as $mall)
                                    <option value="{{ $mall->id }}"
                                        @selected(old('mall_id', $area?->mall_id) == $mall->id)>
                                        {{ $mall->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('mall_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Building</label>

                            <select name="building_id"
                                    class="form-select @error('building_id') is-invalid @enderror">
                                <option value="">Select Building</option>

                                @foreach($buildings ?? [] as $building)
                                    <option value="{{ $building->id }}"
                                        @selected(old('building_id', $area?->building_id) == $building->id)>
                                        {{ $building->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('building_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Floor</label>

                            <select name="floor_id"
                                    class="form-select @error('floor_id') is-invalid @enderror">
                                <option value="">Select Floor</option>

                                @foreach($floors ?? [] as $floor)
                                    <option value="{{ $floor->id }}"
                                        @selected(old('floor_id', $area?->floor_id) == $floor->id)>
                                        {{ $floor->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('floor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Zone</label>

                            <select name="zone_id"
                                    class="form-select @error('zone_id') is-invalid @enderror">
                                <option value="">Select Zone</option>

                                @foreach($zones ?? [] as $zone)
                                    <option value="{{ $zone->id }}"
                                        @selected(old('zone_id', $area?->zone_id) == $zone->id)>
                                        {{ $zone->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('zone_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Unit</label>

                            <select name="unit_id"
                                    class="form-select @error('unit_id') is-invalid @enderror">
                                <option value="">Select Unit</option>

                                @foreach($units ?? [] as $unit)
                                    <option value="{{ $unit->id }}"
                                        @selected(old('unit_id', $area?->unit_id) == $unit->id)>
                                        {{ $unit->code ?? $unit->name ?? $unit->id }}
                                    </option>
                                @endforeach
                            </select>

                            @error('unit_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- Area Classification --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Area Classification</h5>
                </div>

                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label">
                                Area Type <span class="text-danger">*</span>
                            </label>

                            <select name="area_type"
                                    class="form-select @error('area_type') is-invalid @enderror"
                                    required>
                                <option value="">Select Area Type</option>

                                @foreach($areaTypes ?? [] as $type)
                                    <option value="{{ $type->name }}"
                                        @selected(old('area_type', $area?->area_type) == $type->name)>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('area_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Area Category</label>

                            <input type="text"
                                   name="area_category"
                                   value="{{ old('area_category', $area?->area_category) }}"
                                   class="form-control @error('area_category') is-invalid @enderror"
                                   maxlength="50">

                            @error('area_category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">
                                Measurement Unit <span class="text-danger">*</span>
                            </label>

                            <select name="measurement_unit"
                                    class="form-select @error('measurement_unit') is-invalid @enderror"
                                    required>
                                @foreach(['SQFT', 'SQM', 'SQYD'] as $unit)
                                    <option value="{{ $unit }}"
                                        @selected(old('measurement_unit', $area?->measurement_unit ?? 'SQFT') === $unit)>
                                        {{ $unit }}
                                    </option>
                                @endforeach
                            </select>

                            @error('measurement_unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- Area Measurements --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Area Measurements</h5>
                </div>

                <div class="card-body">
                    <div class="row g-3">

                        @foreach([
                            'gross_area'      => 'Gross Area',
                            'net_area'        => 'Net Area',
                            'usable_area'     => 'Usable Area',
                            'rentable_area'   => 'Rentable Area',
                            'chargeable_area' => 'Chargeable Area',
                            'common_area'     => 'Common Area',
                            'service_area'    => 'Service Area',
                        ] as $field => $label)

                            <div class="col-md-3">
                                <label class="form-label">
                                    {{ $label }}
                                </label>

                                <input type="number"
                                       step="0.0001"
                                       min="0"
                                       name="{{ $field }}"
                                       value="{{ old($field, $area?->{$field}) }}"
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

        {{-- Measurement Details --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Measurement Details</h5>
                </div>

                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label">Measurement Date</label>

                            <input type="date"
                                   name="measurement_date"
                                   value="{{ old('measurement_date', $area?->measurement_date?->format('Y-m-d')) }}"
                                   class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Effective From</label>

                            <input type="date"
                                   name="effective_from"
                                   value="{{ old('effective_from', $area?->effective_from?->format('Y-m-d')) }}"
                                   class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Effective To</label>

                            <input type="date"
                                   name="effective_to"
                                   value="{{ old('effective_to', $area?->effective_to?->format('Y-m-d')) }}"
                                   class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Measurement Method</label>

                            <input type="text"
                                   name="measurement_method"
                                   value="{{ old('measurement_method', $area?->measurement_method) }}"
                                   class="form-control"
                                   maxlength="100">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Measurement Standard</label>

                            <input type="text"
                                   name="measurement_standard"
                                   value="{{ old('measurement_standard', $area?->measurement_standard) }}"
                                   class="form-control"
                                   maxlength="100">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Remarks</label>

                            <textarea name="remarks"
                                      rows="4"
                                      class="form-control">{{ old('remarks', $area?->remarks) }}</textarea>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="col-12">
            <div class="d-flex justify-content-end gap-2">

                <a href="{{ route('admin.assets.areas.index') }}"
                   class="btn btn-outline-secondary">
                    Cancel
                </a>

                <button type="submit" class="btn btn-primary">
                    {{ $isEdit ? 'Update Area' : 'Create Area' }}
                </button>

            </div>
        </div>

    </div>
</form>
