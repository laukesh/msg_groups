{{-- Basic Information --}}

<div class="card shadow-sm mb-4">

    <div class="card-header">

        <h6 class="mb-0">
            <i class="ri-building-4-line me-1"></i>
            Profile Information
        </h6>

    </div>

    <div class="card-body">

        <div class="row g-3">

            <div class="col-md-4">

                <label class="form-label">
                    Profile Code <span class="text-danger">*</span>
                </label>

                <input type="text"
                       name="profile_code"
                       class="form-control @error('profile_code') is-invalid @enderror"
                       value="{{ old('profile_code', $profile->profile_code ?? '') }}"
                       placeholder="e.g. COMMERCIAL_RETAIL"
                       required>

                @error('profile_code')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            <div class="col-md-4">

                <label class="form-label">
                    Profile Name <span class="text-danger">*</span>
                </label>

                <input type="text"
                       name="profile_name"
                       class="form-control"
                       value="{{ old('profile_name', $profile->profile_name ?? '') }}"
                       placeholder="Commercial / Retail"
                       required>

            </div>


            <div class="col-md-4">

                <label class="form-label">
                    Industry Type <span class="text-danger">*</span>
                </label>

                <select name="industry_type"
                        class="form-select"
                        required>

                    <option value="">
                        Select Industry
                    </option>

                    @foreach([
                        'Commercial / Retail',
                        'Hospitality',
                        'Residential / Housing',
                        'Industrial / Logistics',
                        'Community / Institutional'
                    ] as $type)

                        <option value="{{ $type }}"
                            @selected(old(
                                'industry_type',
                                $profile->industry_type ?? ''
                            ) === $type)>

                            {{ $type }}

                        </option>

                    @endforeach

                </select>

            </div>


            <div class="col-12">

                <label class="form-label">
                    Description
                </label>

                <textarea name="description"
                          rows="4"
                          class="form-control"
                          placeholder="Describe the operating profile...">{{ old('description', $profile->description ?? '') }}</textarea>

            </div>


            <div class="col-md-3">

                <div class="form-check form-switch mt-4">

                    <input type="hidden"
                           name="is_active"
                           value="0">

                    <input class="form-check-input"
                           type="checkbox"
                           name="is_active"
                           value="1"
                           id="is_active"
                           @checked(old(
                               'is_active',
                               $profile->is_active ?? true
                           ))>

                    <label class="form-check-label"
                           for="is_active">

                        Active Profile

                    </label>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- Occupancy --}}

<div class="card shadow-sm mb-4">

    <div class="card-header">

        <h6 class="mb-0">
            <i class="ri-home-gear-line me-1"></i>
            Occupancy Configuration
        </h6>

    </div>

    <div class="card-body">

        <div class="row g-3">

            <div class="col-md-4">

                <label class="form-label">
                    Occupancy Target %
                </label>

                <input type="number"
                       name="occupancy_target"
                       class="form-control"
                       min="0"
                       max="100"
                       step="0.01"
                       value="{{ old('occupancy_target', $profile->occupancy_target ?? '') }}">

            </div>


            <div class="col-md-4">

                <label class="form-label">
                    Vacancy Threshold %
                </label>

                <input type="number"
                       name="vacancy_threshold"
                       class="form-control"
                       min="0"
                       max="100"
                       step="0.01"
                       value="{{ old('vacancy_threshold', $profile->vacancy_threshold ?? '') }}">

            </div>

        </div>

    </div>

</div>


{{-- Performance --}}

<div class="card shadow-sm mb-4">

    <div class="card-header">

        <h6 class="mb-0">
            <i class="ri-line-chart-line me-1"></i>
            Performance Configuration
        </h6>

    </div>

    <div class="card-body">

        <div class="row g-3">

            <div class="col-md-3">

                <label class="form-label">
                    Performance Target %
                </label>

                <input type="number"
                       name="performance_target"
                       class="form-control"
                       min="0"
                       max="100"
                       step="0.01"
                       value="{{ old('performance_target', $profile->performance_target ?? '') }}">

            </div>


            <div class="col-md-3">

                <label class="form-label">
                    Utilization Target %
                </label>

                <input type="number"
                       name="utilization_target"
                       class="form-control"
                       min="0"
                       max="100"
                       step="0.01"
                       value="{{ old('utilization_target', $profile->utilization_target ?? '') }}">

            </div>


            <div class="col-md-3">

                <label class="form-label">
                    Availability Target %
                </label>

                <input type="number"
                       name="availability_target"
                       class="form-control"
                       min="0"
                       max="100"
                       step="0.01"
                       value="{{ old('availability_target', $profile->availability_target ?? '') }}">

            </div>


            <div class="col-md-3">

                <label class="form-label">
                    Efficiency Target %
                </label>

                <input type="number"
                       name="efficiency_target"
                       class="form-control"
                       min="0"
                       max="100"
                       step="0.01"
                       value="{{ old('efficiency_target', $profile->efficiency_target ?? '') }}">

            </div>

        </div>

    </div>

</div>


{{-- Condition --}}

<div class="card shadow-sm mb-4">

    <div class="card-header">

        <h6 class="mb-0">
            <i class="ri-tools-line me-1"></i>
            Asset Condition Configuration
        </h6>

    </div>

    <div class="card-body">

        <div class="row g-3">

            <div class="col-md-4">

                <label class="form-label">
                    Review Frequency (Months)
                </label>

                <input type="number"
                       name="condition_review_frequency_months"
                       class="form-control"
                       min="1"
                       value="{{ old('condition_review_frequency_months', $profile->condition_review_frequency_months ?? '') }}">

            </div>


            <div class="col-md-4">

                <label class="form-label">
                    Warning Threshold %
                </label>

                <input type="number"
                       name="condition_warning_threshold"
                       class="form-control"
                       min="0"
                       max="100"
                       step="0.01"
                       value="{{ old('condition_warning_threshold', $profile->condition_warning_threshold ?? '') }}">

            </div>


            <div class="col-md-4">

                <label class="form-label">
                    Critical Threshold %
                </label>

                <input type="number"
                       name="condition_critical_threshold"
                       class="form-control"
                       min="0"
                       max="100"
                       step="0.01"
                       value="{{ old('condition_critical_threshold', $profile->condition_critical_threshold ?? '') }}">

            </div>

        </div>

    </div>

</div>


{{-- Financial --}}

<div class="card shadow-sm mb-4">

    <div class="card-header">

        <h6 class="mb-0">
            <i class="ri-money-rupee-circle-line me-1"></i>
            Financial Configuration
        </h6>

    </div>

    <div class="card-body">

        <div class="row g-4">

            <div class="col-md-4">

                <div class="form-check form-switch">

                    <input type="hidden"
                           name="revenue_tracking"
                           value="0">

                    <input class="form-check-input"
                           type="checkbox"
                           name="revenue_tracking"
                           value="1"
                           id="revenue_tracking"
                           @checked(old(
                               'revenue_tracking',
                               $profile->revenue_tracking ?? true
                           ))>

                    <label class="form-check-label"
                           for="revenue_tracking">

                        Revenue Tracking

                    </label>

                </div>

            </div>


            <div class="col-md-4">

                <div class="form-check form-switch">

                    <input type="hidden"
                           name="operating_expense_tracking"
                           value="0">

                    <input class="form-check-input"
                           type="checkbox"
                           name="operating_expense_tracking"
                           value="1"
                           id="operating_expense_tracking"
                           @checked(old(
                               'operating_expense_tracking',
                               $profile->operating_expense_tracking ?? true
                           ))>

                    <label class="form-check-label"
                           for="operating_expense_tracking">

                        Operating Expense Tracking

                    </label>

                </div>

            </div>


            <div class="col-md-4">

                <div class="form-check form-switch">

                    <input type="hidden"
                           name="capex_tracking"
                           value="0">

                    <input class="form-check-input"
                           type="checkbox"
                           name="capex_tracking"
                           value="1"
                           id="capex_tracking"
                           @checked(old(
                               'capex_tracking',
                               $profile->capex_tracking ?? true
                           ))>

                    <label class="form-check-label"
                           for="capex_tracking">

                        CAPEX Tracking

                    </label>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- Lifecycle --}}

<div class="card shadow-sm mb-4">

    <div class="card-header">

        <h6 class="mb-0">
            <i class="ri-history-line me-1"></i>
            Lifecycle Configuration
        </h6>

    </div>

    <div class="card-body">

        <div class="row g-3">

            <div class="col-md-3">

                <label class="form-label">
                    Default Asset Life (Years)
                </label>

                <input type="number"
                       name="default_asset_life_years"
                       class="form-control"
                       min="0"
                       step="0.01"
                       value="{{ old('default_asset_life_years', $profile->default_asset_life_years ?? '') }}">

            </div>


            <div class="col-md-3">

                <label class="form-label">
                    Renewal Period (Years)
                </label>

                <input type="number"
                       name="renewal_period_years"
                       class="form-control"
                       min="0"
                       step="0.01"
                       value="{{ old('renewal_period_years', $profile->renewal_period_years ?? '') }}">

            </div>


            <div class="col-md-3">

                <label class="form-label">
                    Replacement Threshold %
                </label>

                <input type="number"
                       name="replacement_threshold"
                       class="form-control"
                       min="0"
                       max="100"
                       step="0.01"
                       value="{{ old('replacement_threshold', $profile->replacement_threshold ?? '') }}">

            </div>


            <div class="col-md-3">

                <label class="form-label">
                    Lifecycle Review (Months)
                </label>

                <input type="number"
                       name="lifecycle_review_frequency_months"
                       class="form-control"
                       min="1"
                       value="{{ old('lifecycle_review_frequency_months', $profile->lifecycle_review_frequency_months ?? '') }}">

            </div>

        </div>

    </div>

</div>


{{-- KPI --}}

<div class="card shadow-sm mb-4">

    <div class="card-header">

        <h6 class="mb-0">
            <i class="ri-dashboard-line me-1"></i>
            Industry KPI Configuration
        </h6>

    </div>

    <div class="card-body">

        <div class="row g-3">

            <div class="col-md-6">

                <label class="form-label">
                    Primary KPI
                </label>

                <input type="text"
                       name="primary_kpi"
                       class="form-control"
                       value="{{ old('primary_kpi', $profile->primary_kpi ?? '') }}"
                       placeholder="e.g. Occupancy">

            </div>


            <div class="col-md-6">

                <label class="form-label">
                    Secondary KPI
                </label>

                <input type="text"
                       name="secondary_kpi"
                       class="form-control"
                       value="{{ old('secondary_kpi', $profile->secondary_kpi ?? '') }}"
                       placeholder="e.g. Revenue">

            </div>


            <div class="col-12">

                <label class="form-label">
                    Remarks
                </label>

                <textarea name="remarks"
                          rows="4"
                          class="form-control"
                          placeholder="Additional operating profile notes...">{{ old('remarks', $profile->remarks ?? '') }}</textarea>

            </div>

        </div>

    </div>

</div>