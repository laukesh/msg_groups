<div class="row">

    {{-- Proposal --}}
    <div class="col-md-6 mb-3">

        <label for="proposal_id" class="form-label">
            Proposal
            <span class="text-danger">*</span>
        </label>

        <select
            name="proposal_id"
            id="proposal_id"
            class="form-select @error('proposal_id') is-invalid @enderror"
            required
        >

            <option value="">
                Select Proposal
            </option>

            @foreach($proposals ?? [] as $id => $title)

                <option
                    value="{{ $id }}"
                    {{ old('proposal_id', $item->proposal_id ?? '') == $id ? 'selected' : '' }}
                >
                    {{ $title }}
                </option>

            @endforeach

        </select>

        @error('proposal_id')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Unit --}}
    <div class="col-md-6 mb-3">

        <label for="unit_id" class="form-label">
            Unit
            <span class="text-danger">*</span>
        </label>

        <select
            name="unit_id"
            id="unit_id"
            class="form-select @error('unit_id') is-invalid @enderror"
            required
        >

            <option value="">
                Select Unit
            </option>

            @foreach($units ?? [] as $id => $no)

                <option
                    value="{{ $id }}"
                    {{ old('unit_id', $item->unit_id ?? '') == $id ? 'selected' : '' }}
                >
                    {{ $no }}
                </option>

            @endforeach

        </select>

        @error('unit_id')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Proposed Rent --}}
    <div class="col-md-4 mb-3">

        <label for="proposed_rent" class="form-label">
            Proposed Rent
        </label>

        <input
            type="number"
            name="proposed_rent"
            id="proposed_rent"
            class="form-control @error('proposed_rent') is-invalid @enderror"
            value="{{ old('proposed_rent', $item->proposed_rent ?? '') }}"
            step="0.01"
            min="0"
            placeholder="0.00"
        >

        @error('proposed_rent')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Proposed CAM Rate --}}
    <div class="col-md-4 mb-3">

        <label for="proposed_cam_rate" class="form-label">
            Proposed CAM Rate
        </label>

        <input
            type="number"
            name="proposed_cam_rate"
            id="proposed_cam_rate"
            class="form-control @error('proposed_cam_rate') is-invalid @enderror"
            value="{{ old('proposed_cam_rate', $item->proposed_cam_rate ?? '') }}"
            step="0.01"
            min="0"
            placeholder="0.00"
        >

        @error('proposed_cam_rate')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Proposed Security Deposit --}}
    <div class="col-md-4 mb-3">

        <label for="proposed_security_deposit" class="form-label">
            Proposed Security Deposit
        </label>

        <input
            type="number"
            name="proposed_security_deposit"
            id="proposed_security_deposit"
            class="form-control @error('proposed_security_deposit') is-invalid @enderror"
            value="{{ old('proposed_security_deposit', $item->proposed_security_deposit ?? '') }}"
            step="0.01"
            min="0"
            placeholder="0.00"
        >

        @error('proposed_security_deposit')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Rent Free Days --}}
    <div class="col-md-6 mb-3">

        <label for="rent_free_days" class="form-label">
            Rent Free Days
        </label>

        <input
            type="number"
            name="rent_free_days"
            id="rent_free_days"
            class="form-control @error('rent_free_days') is-invalid @enderror"
            value="{{ old('rent_free_days', $item->rent_free_days ?? 0) }}"
            min="0"
            placeholder="0"
        >

        @error('rent_free_days')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Fitout Period --}}
    <div class="col-md-6 mb-3">

        <label for="fitout_period_days" class="form-label">
            Fitout Period
        </label>

        <div class="input-group">

            <input
                type="number"
                name="fitout_period_days"
                id="fitout_period_days"
                class="form-control @error('fitout_period_days') is-invalid @enderror"
                value="{{ old('fitout_period_days', $item->fitout_period_days ?? 0) }}"
                min="0"
                placeholder="0"
            >

            <span class="input-group-text">
                Days
            </span>

        </div>

        @error('fitout_period_days')

            <div class="text-danger small mt-1">
                {{ $message }}
            </div>

        @enderror

    </div>


    {{-- Remarks --}}
    <div class="col-md-12 mb-3">

        <label for="remarks" class="form-label">
            Remarks
        </label>

        <textarea
            name="remarks"
            id="remarks"
            rows="4"
            class="form-control @error('remarks') is-invalid @enderror"
            placeholder="Enter remarks..."
        >{{ old('remarks', $item->remarks ?? '') }}</textarea>

        @error('remarks')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>

</div>