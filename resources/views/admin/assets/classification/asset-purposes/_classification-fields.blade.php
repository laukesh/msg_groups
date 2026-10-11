<div class="row g-3">

    {{-- Asset Type --}}
    <div class="col-md-4">
        <label class="form-label">
            Asset Type
        </label>

        <select name="asset_type_id" class="form-select">
            <option value="">Select Asset Type</option>

            @foreach($assetTypes as $type)
                <option
                    value="{{ $type->id }}"
                    @selected(
                        old(
                            'asset_type_id',
                            $asset->asset_type_id ?? ''
                        ) == $type->id
                    )
                >
                    {{ $type->name }}
                </option>
            @endforeach
        </select>

        @error('asset_type_id')
            <div class="text-danger small">
                {{ $message }}
            </div>
        @enderror
    </div>


    {{-- Asset Purpose --}}
    <div class="col-md-4">
        <label class="form-label">
            Asset Purpose
        </label>

        <select name="asset_purpose_id" class="form-select">
            <option value="">Select Asset Purpose</option>

            @foreach($assetPurposes as $purpose)
                <option
                    value="{{ $purpose->id }}"
                    @selected(
                        old(
                            'asset_purpose_id',
                            $asset->asset_purpose_id ?? ''
                        ) == $purpose->id
                    )
                >
                    {{ $purpose->name }}
                </option>
            @endforeach
        </select>
    </div>


    {{-- Industry Profile --}}
    <div class="col-md-4">
        <label class="form-label">
            Industry Profile
        </label>

        <select name="industry_profile_id" class="form-select">
            <option value="">Select Industry Profile</option>

            @foreach($industryProfiles as $profile)
                <option
                    value="{{ $profile->id }}"
                    @selected(
                        old(
                            'industry_profile_id',
                            $asset->industry_profile_id ?? ''
                        ) == $profile->id
                    )
                >
                    {{ $profile->name }}
                </option>
            @endforeach
        </select>
    </div>


    {{-- Operating Model --}}
    <div class="col-md-4">
        <label class="form-label">
            Operating Model
        </label>

        <select name="operating_model_id" class="form-select">
            <option value="">Select Operating Model</option>

            @foreach($operatingModels as $model)
                <option
                    value="{{ $model->id }}"
                    @selected(
                        old(
                            'operating_model_id',
                            $asset->operating_model_id ?? ''
                        ) == $model->id
                    )
                >
                    {{ $model->name }}
                </option>
            @endforeach
        </select>
    </div>


    {{-- Ownership --}}
    <div class="col-md-4">
        <label class="form-label">
            Ownership
        </label>

        <select name="ownership_id" class="form-select">
            <option value="">Select Ownership</option>

            @foreach($ownerships as $ownership)
                <option
                    value="{{ $ownership->id }}"
                    @selected(
                        old(
                            'ownership_id',
                            $asset->ownership_id ?? ''
                        ) == $ownership->id
                    )
                >
                    {{ $ownership->name }}
                </option>
            @endforeach
        </select>
    </div>


    {{-- Revenue Status --}}
    <div class="col-md-4">
        <label class="form-label">
            Revenue / Non-Revenue Status
        </label>

        <select name="revenue_status_id" class="form-select">
            <option value="">
                Select Revenue Status
            </option>

            @foreach($revenueStatuses as $status)
                <option
                    value="{{ $status->id }}"
                    @selected(
                        old(
                            'revenue_status_id',
                            $asset->revenue_status_id ?? ''
                        ) == $status->id
                    )
                >
                    {{ $status->name }}
                </option>
            @endforeach
        </select>
    </div>

</div>