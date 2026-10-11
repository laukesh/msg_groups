<div class="row g-3">

    {{-- =========================================================
        LOCATION
    ========================================================== --}}

    {{-- Mall --}}
    <div class="col-lg-6 col-md-6 col-12">

        <label for="mall_id" class="form-label">
            Mall <span class="text-danger">*</span>
        </label>

        <select
            id="mall_id"
            name="mall_id"
            class="form-select @error('mall_id') is-invalid @enderror"
            required
        >
            <option value="">Select Mall</option>

            @foreach($malls as $id => $name)

                <option
                    value="{{ $id }}"
                    {{ old('mall_id', $unit->mall_id ?? '') == $id ? 'selected' : '' }}
                >
                    {{ $name }}
                </option>

            @endforeach

        </select>

        @error('mall_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Building --}}
    <div class="col-lg-6 col-md-6 col-12">

        <label for="building_id" class="form-label">
            Building <span class="text-danger">*</span>
        </label>

        <select
            id="building_id"
            name="building_id"
            class="form-select @error('building_id') is-invalid @enderror"
            required
        >
            <option value="">Select Building</option>

            @foreach($buildings as $id => $name)

                <option
                    value="{{ $id }}"
                    {{ old('building_id', $unit->building_id ?? '') == $id ? 'selected' : '' }}
                >
                    {{ $name }}
                </option>

            @endforeach

        </select>

        @error('building_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Floor --}}
    <div class="col-lg-6 col-md-6 col-12">

        <label for="floor_id" class="form-label">
            Floor
        </label>

        <select
            id="floor_id"
            name="floor_id"
            class="form-select @error('floor_id') is-invalid @enderror"
        >
            <option value="">Select Floor</option>

            @foreach($floors as $id => $name)

                <option
                    value="{{ $id }}"
                    {{ old('floor_id', $unit->floor_id ?? '') == $id ? 'selected' : '' }}
                >
                    {{ $name }}
                </option>

            @endforeach

        </select>

        @error('floor_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Zone --}}
    <div class="col-lg-6 col-md-6 col-12">

        <label for="zone_id" class="form-label">
            Zone
        </label>

        <select
            id="zone_id"
            name="zone_id"
            class="form-select @error('zone_id') is-invalid @enderror"
        >
            <option value="">Select Zone</option>

            @foreach($zones as $id => $name)

                <option
                    value="{{ $id }}"
                    {{ old('zone_id', $unit->zone_id ?? '') == $id ? 'selected' : '' }}
                >
                    {{ $name }}
                </option>

            @endforeach

        </select>

        @error('zone_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- =========================================================
        UNIT CLASSIFICATION
    ========================================================== --}}

    {{-- Unit Type --}}
    <div class="col-lg-6 col-md-6 col-12">

        <label for="unit_type_id" class="form-label">
            Unit Type
        </label>

        <select
            id="unit_type_id"
            name="unit_type_id"
            class="form-select @error('unit_type_id') is-invalid @enderror"
        >
            <option value="">Select Unit Type</option>

            @foreach($unitTypes as $id => $name)

                <option
                    value="{{ $id }}"
                    {{ old('unit_type_id', $unit->unit_type_id ?? '') == $id ? 'selected' : '' }}
                >
                    {{ $name }}
                </option>

            @endforeach

        </select>

        @error('unit_type_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Unit Status --}}
    <div class="col-lg-6 col-md-6 col-12">

        <label for="unit_status_id" class="form-label">
            Unit Status
        </label>

        <select
            id="unit_status_id"
            name="unit_status_id"
            class="form-select @error('unit_status_id') is-invalid @enderror"
        >
            <option value="">Select Unit Status</option>

            @foreach($unitStatuses as $id => $name)

                <option
                    value="{{ $id }}"
                    {{ old('unit_status_id', $unit->unit_status_id ?? '') == $id ? 'selected' : '' }}
                >
                    {{ $name }}
                </option>

            @endforeach

        </select>

        @error('unit_status_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- =========================================================
        UNIT DETAILS
    ========================================================== --}}

    {{-- Unit No --}}
    <div class="col-lg-6 col-md-6 col-12">

        <label for="unit_no" class="form-label">
            Unit No <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            id="unit_no"
            name="unit_no"
            class="form-control @error('unit_no') is-invalid @enderror"
            value="{{ old('unit_no', $unit->unit_no ?? '') }}"
            placeholder="e.g. A-101"
            required
        >

        @error('unit_no')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Shop Name --}}
    <div class="col-lg-6 col-md-6 col-12">

        <label for="shop_name" class="form-label">
            Shop Name
        </label>

        <input
            type="text"
            id="shop_name"
            name="shop_name"
            class="form-control @error('shop_name') is-invalid @enderror"
            value="{{ old('shop_name', $unit->shop_name ?? '') }}"
            placeholder="Enter shop name"
        >

        @error('shop_name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- =========================================================
        AREA DETAILS
    ========================================================== --}}

    {{-- Carpet Area --}}
    <div class="col-lg-4 col-md-6 col-12">

        <label for="carpet_area" class="form-label">
            Carpet Area
        </label>

        <div class="input-group">

            <input
                type="number"
                step="0.01"
                min="0"
                id="carpet_area"
                name="carpet_area"
                class="form-control @error('carpet_area') is-invalid @enderror"
                value="{{ old('carpet_area', $unit->carpet_area ?? '') }}"
                placeholder="0.00"
            >

            <span class="input-group-text">
                sq.ft.
            </span>

        </div>

        @error('carpet_area')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Built-up Area --}}
    <div class="col-lg-4 col-md-6 col-12">

        <label for="builtup_area" class="form-label">
            Built-up Area
        </label>

        <div class="input-group">

            <input
                type="number"
                step="0.01"
                min="0"
                id="builtup_area"
                name="builtup_area"
                class="form-control @error('builtup_area') is-invalid @enderror"
                value="{{ old('builtup_area', $unit->builtup_area ?? '') }}"
                placeholder="0.00"
            >

            <span class="input-group-text">
                sq.ft.
            </span>

        </div>

        @error('builtup_area')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Frontage --}}
    <div class="col-lg-4 col-md-6 col-12">

        <label for="frontage" class="form-label">
            Frontage
        </label>

        <div class="input-group">

            <input
                type="number"
                step="0.01"
                min="0"
                id="frontage"
                name="frontage"
                class="form-control @error('frontage') is-invalid @enderror"
                value="{{ old('frontage', $unit->frontage ?? '') }}"
                placeholder="0.00"
            >

            <span class="input-group-text">
                ft.
            </span>

        </div>

        @error('frontage')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- =========================================================
        FINANCIAL DETAILS
    ========================================================== --}}

    {{-- Monthly Rent --}}
    <div class="col-lg-4 col-md-6 col-12">

        <label for="monthly_rent" class="form-label">
            Monthly Rent
        </label>

        <div class="input-group">

            <span class="input-group-text">
                $
            </span>

            <input
                type="number"
                step="0.01"
                min="0"
                id="monthly_rent"
                name="monthly_rent"
                class="form-control @error('monthly_rent') is-invalid @enderror"
                value="{{ old('monthly_rent', $unit->monthly_rent ?? '') }}"
                placeholder="0.00"
            >

        </div>

        @error('monthly_rent')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Security Deposit --}}
    <div class="col-lg-4 col-md-6 col-12">

        <label for="security_deposit" class="form-label">
            Security Deposit
        </label>

        <div class="input-group">

            <span class="input-group-text">
                $
            </span>

            <input
                type="number"
                step="0.01"
                min="0"
                id="security_deposit"
                name="security_deposit"
                class="form-control @error('security_deposit') is-invalid @enderror"
                value="{{ old('security_deposit', $unit->security_deposit ?? '') }}"
                placeholder="0.00"
            >

        </div>

        @error('security_deposit')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- =========================================================
        REMARKS
    ========================================================== --}}

    <div class="col-12">

        <label for="remarks" class="form-label">
            Remarks
        </label>

        <textarea
            id="remarks"
            name="remarks"
            rows="4"
            class="form-control @error('remarks') is-invalid @enderror"
            placeholder="Enter any additional remarks..."
        >{{ old('remarks', $unit->remarks ?? '') }}</textarea>

        @error('remarks')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- =========================================================
        RECORD STATUS
    ========================================================== --}}

    <div class="col-lg-3 col-md-6 col-12">

        <label for="status" class="form-label">
            Status
        </label>

        <select
            id="status"
            name="status"
            class="form-select @error('status') is-invalid @enderror"
        >

            <option
                value="1"
                {{ old('status', $unit->status ?? '1') == '1' ? 'selected' : '' }}
            >
                Active
            </option>

            <option
                value="0"
                {{ old('status', $unit->status ?? '') == '0' ? 'selected' : '' }}
            >
                Inactive
            </option>

        </select>

        @error('status')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

</div>