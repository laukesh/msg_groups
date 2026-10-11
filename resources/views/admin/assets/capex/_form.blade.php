@csrf

<div class="row g-3">

    {{-- Asset --}}

    <div class="col-md-6">

        <label class="form-label">
            Asset <span class="text-danger">*</span>
        </label>

        <select name="asset_id"
                class="form-select @error('asset_id') is-invalid @enderror"
                required>

            <option value="">
                Select Asset
            </option>

            @foreach($assets as $asset)

                <option value="{{ $asset->id }}"
                    @selected(old(
                        'asset_id',
                        $capex->asset_id ?? ''
                    ) == $asset->id)>

                    {{ $asset->asset_code }}
                    -
                    {{ $asset->asset_name }}

                </option>

            @endforeach

        </select>

        @error('asset_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

    {{-- CAPEX Code --}}

    <div class="col-md-6">

        <label class="form-label">
            CAPEX Code
        </label>

        <input type="text"
               name="capex_code"
               class="form-control @error('capex_code') is-invalid @enderror"
               value="{{ old('capex_code', $capex->capex_code ?? '') }}"
               placeholder="Example: CAPEX-2026-001">

        @error('capex_code')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

    {{-- Title --}}

    <div class="col-md-12">

        <label class="form-label">
            Title <span class="text-danger">*</span>
        </label>

        <input type="text"
               name="title"
               class="form-control @error('title') is-invalid @enderror"
               value="{{ old('title', $capex->title ?? '') }}"
               required>

        @error('title')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

    {{-- Description --}}

    <div class="col-md-12">

        <label class="form-label">
            Description
        </label>

        <textarea name="description"
                  rows="4"
                  class="form-control">{{ old(
                      'description',
                      $capex->description ?? ''
                  ) }}</textarea>

    </div>

    {{-- Category --}}

    <div class="col-md-4">

        <label class="form-label">
            Category
        </label>

        <input type="text"
               name="category"
               class="form-control"
               value="{{ old(
                   'category',
                   $capex->category ?? ''
               ) }}"
               placeholder="Renovation">

    </div>

    {{-- Priority --}}

    <div class="col-md-4">

        <label class="form-label">
            Priority <span class="text-danger">*</span>
        </label>

        <select name="priority"
                class="form-select"
                required>

            @foreach([
                'low',
                'medium',
                'high',
                'critical'
            ] as $priority)

                <option value="{{ $priority }}"
                    @selected(old(
                        'priority',
                        $capex->priority ?? 'medium'
                    ) === $priority)>

                    {{ ucfirst($priority) }}

                </option>

            @endforeach

        </select>

    </div>

    {{-- Status --}}

    <div class="col-md-4">

        <label class="form-label">
            Status <span class="text-danger">*</span>
        </label>

        <select name="status"
                class="form-select"
                required>

            @foreach([
                'planned',
                'approved',
                'in_progress',
                'completed',
                'cancelled'
            ] as $status)

                <option value="{{ $status }}"
                    @selected(old(
                        'status',
                        $capex->status ?? 'planned'
                    ) === $status)>

                    {{ ucwords(
                        str_replace('_', ' ', $status)
                    ) }}

                </option>

            @endforeach

        </select>

    </div>

    {{-- Planned Date --}}

    <div class="col-md-4">

        <label class="form-label">
            Planned Date
        </label>

        <input type="date"
               name="planned_date"
               class="form-control"
               value="{{ old(
                   'planned_date',
                   isset($capex->planned_date)
                       ? $capex->planned_date->format('Y-m-d')
                       : ''
               ) }}">

    </div>

    {{-- Start Date --}}

    <div class="col-md-4">

        <label class="form-label">
            Start Date
        </label>

        <input type="date"
               name="start_date"
               class="form-control"
               value="{{ old(
                   'start_date',
                   isset($capex->start_date)
                       ? $capex->start_date->format('Y-m-d')
                       : ''
               ) }}">

    </div>

    {{-- Completion Date --}}

    <div class="col-md-4">

        <label class="form-label">
            Completion Date
        </label>

        <input type="date"
               name="completion_date"
               class="form-control"
               value="{{ old(
                   'completion_date',
                   isset($capex->completion_date)
                       ? $capex->completion_date->format('Y-m-d')
                       : ''
               ) }}">

    </div>

    {{-- Estimated Cost --}}

    <div class="col-md-4">

        <label class="form-label">
            Estimated Cost
        </label>

        <input type="number"
               step="0.01"
               min="0"
               name="estimated_cost"
               class="form-control"
               value="{{ old(
                   'estimated_cost',
                   $capex->estimated_cost ?? 0
               ) }}">

    </div>

    {{-- Approved Budget --}}

    <div class="col-md-4">

        <label class="form-label">
            Approved Budget
        </label>

        <input type="number"
               step="0.01"
               min="0"
               name="approved_budget"
               class="form-control"
               value="{{ old(
                   'approved_budget',
                   $capex->approved_budget ?? 0
               ) }}">

    </div>

    {{-- Actual Cost --}}

    <div class="col-md-4">

        <label class="form-label">
            Actual Cost
        </label>

        <input type="number"
               step="0.01"
               min="0"
               name="actual_cost"
               class="form-control"
               value="{{ old(
                   'actual_cost',
                   $capex->actual_cost ?? 0
               ) }}">

    </div>

    {{-- Vendor/User --}}

    <div class="col-md-6">

        <label class="form-label">
            Vendor / Responsible User
        </label>

        <select name="vendor_id"
                class="form-select">

            <option value="">
                Select User
            </option>

            @foreach($users as $user)

                <option value="{{ $user->id }}"
                    @selected(old(
                        'vendor_id',
                        $capex->vendor_id ?? ''
                    ) == $user->id)>

                    {{ $user->name }}
                    @if($user->email)
                        - {{ $user->email }}
                    @endif

                </option>

            @endforeach

        </select>

    </div>

    {{-- Remarks --}}

    <div class="col-md-12">

        <label class="form-label">
            Remarks
        </label>

        <textarea name="remarks"
                  rows="4"
                  class="form-control">{{ old(
                      'remarks',
                      $capex->remarks ?? ''
                  ) }}</textarea>

    </div>

</div>