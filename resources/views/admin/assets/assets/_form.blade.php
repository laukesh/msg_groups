@php
    $asset = $asset ?? null;
@endphp

<div class="row">

    <div class="col-12">
        <h6 class="fw-semibold border-bottom pb-2 mb-3">
            <i class="fas fa-info-circle me-1"></i> Basic Information
        </h6>
    </div>

    <div class="col-lg-4 col-md-6 mb-3">
        <label for="asset_code" class="form-label fw-semibold">Asset Code <span class="text-danger">*</span></label>
        <input type="text" id="asset_code" name="asset_code"
               class="form-control @error('asset_code') is-invalid @enderror"
               value="{{ old('asset_code', $asset->asset_code ?? '') }}"
               maxlength="100" required>
        @error('asset_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-lg-4 col-md-6 mb-3">
        <label for="asset_name" class="form-label fw-semibold">Asset Name <span class="text-danger">*</span></label>
        <input type="text" id="asset_name" name="asset_name"
               class="form-control @error('asset_name') is-invalid @enderror"
               value="{{ old('asset_name', $asset->asset_name ?? '') }}"
               maxlength="150" required>
        @error('asset_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-lg-4 col-md-6 mb-3">
        <label for="asset_category" class="form-label fw-semibold">Asset Category</label>
        <select id="asset_category" name="asset_category" class="form-select @error('asset_category') is-invalid @enderror">
            <option value="">Select Category</option>
            @foreach($assetCategories ?? [] as $id => $name)
                <option value="{{ $id }}" {{ (string)old('asset_category', $asset->asset_category ?? '') === (string)$id ? 'selected' : '' }}>
                    {{ $name }}
                </option>
            @endforeach
        </select>
        @error('asset_category')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-lg-4 col-md-6 mb-3">
        <label for="asset_type" class="form-label fw-semibold">Asset Type</label>
        <input type="text" id="asset_type" name="asset_type"
               class="form-control @error('asset_type') is-invalid @enderror"
               value="{{ old('asset_type', $asset->asset_type ?? '') }}"
               maxlength="100">
        @error('asset_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-lg-4 col-md-6 mb-3">
        <label for="serial_number" class="form-label fw-semibold">Serial Number</label>
        <input type="text" id="serial_number" name="serial_number"
               class="form-control @error('serial_number') is-invalid @enderror"
               value="{{ old('serial_number', $asset->serial_number ?? '') }}"
               maxlength="150">
        @error('serial_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-lg-4 col-md-6 mb-3">
        <label for="model_number" class="form-label fw-semibold">Model Number</label>
        <input type="text" id="model_number" name="model_number"
               class="form-control @error('model_number') is-invalid @enderror"
               value="{{ old('model_number', $asset->model_number ?? '') }}"
               maxlength="150">
        @error('model_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-lg-4 col-md-6 mb-3">
        <label for="manufacturer" class="form-label fw-semibold">Manufacturer</label>
        <input type="text" id="manufacturer" name="manufacturer"
               class="form-control @error('manufacturer') is-invalid @enderror"
               value="{{ old('manufacturer', $asset->manufacturer ?? '') }}"
               maxlength="150">
        @error('manufacturer')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 mt-2">
        <h6 class="fw-semibold border-bottom pb-2 mb-3">
            <i class="fas fa-map-marker-alt me-1"></i> Location & Assignment
        </h6>
    </div>

    @foreach([
        'unit_id' => ['label' => 'Unit', 'data' => $units ?? []],
        'building_id' => ['label' => 'Building', 'data' => $buildings ?? []],
        'floor_id' => ['label' => 'Floor', 'data' => $floors ?? []],
        'zone_id' => ['label' => 'Zone', 'data' => $zones ?? []],
        'department_id' => ['label' => 'Department', 'data' => $departments ?? []],
        'assigned_to' => ['label' => 'Assigned To', 'data' => $users ?? []],
        'vendor_id' => ['label' => 'Vendor', 'data' => $vendors ?? []],
    ] as $field => $config)
        <div class="col-lg-4 col-md-6 mb-3">
            <label for="{{ $field }}" class="form-label fw-semibold">{{ $config['label'] }}</label>
            <select id="{{ $field }}" name="{{ $field }}"
                    class="form-select @error($field) is-invalid @enderror">
                <option value="">Select {{ $config['label'] }}</option>
                @foreach($config['data'] as $id => $name)
                    <option value="{{ $id }}" {{ (string)old($field, $asset->{$field} ?? '') === (string)$id ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
            @error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    @endforeach

    <div class="col-12 mb-3">
        <label for="location_description" class="form-label fw-semibold">Location Description</label>
        <textarea id="location_description" name="location_description" rows="2"
                  class="form-control @error('location_description') is-invalid @enderror"
                  maxlength="500">{{ old('location_description', $asset->location_description ?? '') }}</textarea>
        @error('location_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 mt-2">
        <h6 class="fw-semibold border-bottom pb-2 mb-3">
            <i class="fas fa-calendar-alt me-1"></i> Purchase & Warranty
        </h6>
    </div>

    @foreach([
    'purchase_date'       => 'Purchase Date',
    'installation_date'   => 'Installation Date',
    'warranty_start_date' => 'Warranty Start Date',
    'warranty_end_date'   => 'Warranty End Date',
] as $field => $label)

    <div class="col-lg-3 col-md-6 mb-3">

        <label for="{{ $field }}" class="form-label fw-semibold">
            {{ $label }}
        </label>

        <input type="date"
               id="{{ $field }}"
               name="{{ $field }}"
               class="form-control @error($field) is-invalid @enderror"
               value="{{ old(
                   $field,
                   !empty($asset->{$field})
                       ? \Carbon\Carbon::parse($asset->{$field})->format('Y-m-d')
                       : ''
               ) }}">

        @error($field)
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

@endforeach

    <div class="col-lg-4 col-md-6 mb-3">
        <label for="purchase_cost" class="form-label fw-semibold">Purchase Cost</label>
        <input type="number" id="purchase_cost" name="purchase_cost" step="0.01" min="0"
               class="form-control @error('purchase_cost') is-invalid @enderror"
               value="{{ old('purchase_cost', $asset->purchase_cost ?? '') }}">
        @error('purchase_cost')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-lg-4 col-md-6 mb-3">
        <label for="useful_life_years" class="form-label fw-semibold">Useful Life (Years)</label>
        <input type="number" id="useful_life_years" name="useful_life_years" min="0"
               class="form-control @error('useful_life_years') is-invalid @enderror"
               value="{{ old('useful_life_years', $asset->useful_life_years ?? '') }}">
        @error('useful_life_years')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

   <div class="col-lg-4 col-md-6 mb-3">
    <label for="status" class="form-label fw-semibold">
        Status <span class="text-danger">*</span>
    </label>

    <select id="status"
            name="status"
            class="form-select @error('status') is-invalid @enderror">

        <option value="">Select Status</option>

        <option value="1"
            {{ old('status', $asset->status ?? '') == '1' ? 'selected' : '' }}>
            Active
        </option>

        <option value="0"
            {{ old('status', $asset->status ?? '') == '0' ? 'selected' : '' }}>
            Inactive
        </option>

    </select>

    @error('status')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

  <div class="col-lg-6 mb-3">
    <label for="conditions" class="form-label fw-semibold">
        Condition <span class="text-danger">*</span>
    </label>

    <select id="conditions"
            name="conditions"
            class="form-select @error('conditions') is-invalid @enderror">

        <option value="">Select Condition</option>

        <option value="Excellent"
            {{ old('conditions', $asset->conditions ?? '') == 'Excellent' ? 'selected' : '' }}>
            Excellent
        </option>

        <option value="Good"
            {{ old('conditions', $asset->conditions ?? '') == 'Good' ? 'selected' : '' }}>
            Good
        </option>

        <option value="Fair"
            {{ old('conditions', $asset->conditions ?? '') == 'Fair' ? 'selected' : '' }}>
            Fair
        </option>

        <option value="Poor"
            {{ old('conditions', $asset->conditions ?? '') == 'Poor' ? 'selected' : '' }}>
            Poor
        </option>

        <option value="Critical"
            {{ old('conditions', $asset->conditions ?? '') == 'Critical' ? 'selected' : '' }}>
            Critical
        </option>

    </select>

    @error('conditions')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
    <div class="col-lg-6 mb-3">
        <label for="remarks" class="form-label fw-semibold">Remarks</label>
        <textarea id="remarks" name="remarks" rows="3"
                  class="form-control @error('remarks') is-invalid @enderror"
                  maxlength="1000">{{ old('remarks', $asset->remarks ?? '') }}</textarea>
        @error('remarks')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

</div>
