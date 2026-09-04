<div class="row g-3">

    {{-- Milestone Number --}}
    <div class="col-md-4">

        <label class="form-label">
            Milestone Number
        </label>

        <input
            type="text"
            class="form-control"
            value="Auto Generated"
            readonly
        >

        <div class="form-text">
            Milestone number will be generated automatically.
        </div>

    </div>


    {{-- Milestone Title --}}
    <div class="col-md-8">

        <label class="form-label">
            Milestone Title
            <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="milestone_title"
            class="form-control @error('milestone_title') is-invalid @enderror"
            value="{{ old('milestone_title') }}"
            placeholder="e.g. Site Mobilization"
            maxlength="255"
            required
        >

        @error('milestone_title')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Description --}}
    <div class="col-12">

        <label class="form-label">
            Description
        </label>

        <textarea
            name="description"
            rows="3"
            class="form-control @error('description') is-invalid @enderror"
            placeholder="Enter milestone description"
        >{{ old('description') }}</textarea>

        @error('description')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Planned Start Date --}}
    <div class="col-md-4">

        <label class="form-label">
            Planned Start Date
        </label>

        <input
            type="date"
            name="planned_start_date"
            class="form-control @error('planned_start_date') is-invalid @enderror"
            value="{{ old('planned_start_date') }}"
        >

        @error('planned_start_date')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Planned End Date --}}
    <div class="col-md-4">

        <label class="form-label">
            Planned End Date
        </label>

        <input
            type="date"
            name="planned_end_date"
            class="form-control @error('planned_end_date') is-invalid @enderror"
            value="{{ old('planned_end_date') }}"
        >

        @error('planned_end_date')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Milestone Amount --}}
    <div class="col-md-4">

        <label class="form-label">
            Milestone Amount
            <span class="text-danger">*</span>
        </label>

        <input
            type="number"
            name="milestone_amount"
            class="form-control @error('milestone_amount') is-invalid @enderror"
            step="0.01"
            min="0"
            value="{{ old('milestone_amount', 0) }}"
            required
        >

        <div class="form-text">
            Contract Currency:
            <strong>{{ $contract->currency }}</strong>
        </div>

        @error('milestone_amount')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Initial Progress --}}
    <div class="col-md-4">

        <label class="form-label">
            Initial Progress %
        </label>

        <input
            type="number"
            name="progress_percentage"
            class="form-control @error('progress_percentage') is-invalid @enderror"
            step="0.01"
            min="0"
            max="100"
            value="{{ old('progress_percentage', 0) }}"
        >

        @error('progress_percentage')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Deliverable Required --}}
    <div class="col-md-4">

        <label class="form-label">
            Deliverable Required?
        </label>

        <select
            name="deliverable_required"
            class="form-select @error('deliverable_required') is-invalid @enderror"
        >

            <option
                value="0"
                @selected(
                    old('deliverable_required', '0') == '0'
                )
            >
                No
            </option>

            <option
                value="1"
                @selected(
                    old('deliverable_required') == '1'
                )
            >
                Yes
            </option>

        </select>

        @error('deliverable_required')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Responsible User --}}
    <div class="col-md-4">

        <label class="form-label">
            Responsible User ID
        </label>

        <input
            type="number"
            name="responsible_user_id"
            class="form-control @error('responsible_user_id') is-invalid @enderror"
            value="{{ old('responsible_user_id') }}"
            min="1"
        >

        @error('responsible_user_id')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Deliverable Description --}}
    <div class="col-12">

        <label class="form-label">
            Deliverable Description
        </label>

        <textarea
            name="deliverable_description"
            rows="3"
            class="form-control @error('deliverable_description') is-invalid @enderror"
            placeholder="Describe the expected deliverable"
        >{{ old('deliverable_description') }}</textarea>

        @error('deliverable_description')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Remarks --}}
    <div class="col-12">

        <label class="form-label">
            Remarks
        </label>

        <textarea
            name="remarks"
            rows="3"
            class="form-control @error('remarks') is-invalid @enderror"
            placeholder="Additional remarks"
        >{{ old('remarks') }}</textarea>

        @error('remarks')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>

</div>