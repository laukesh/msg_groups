<div class="row g-3">

    {{-- Name --}}
    <div class="col-md-6">

        <label for="name" class="form-label">
            Operating Model Name
            <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="name"
            id="name"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $operatingModel->name ?? '') }}"
            placeholder="Enter operating model name"
            maxlength="150"
            required
        >

        @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Code --}}
    <div class="col-md-6">

        <label for="code" class="form-label">
            Code
        </label>

        <input
            type="text"
            name="code"
            id="code"
            class="form-control @error('code') is-invalid @enderror"
            value="{{ old('code', $operatingModel->code ?? '') }}"
            placeholder="e.g. OWNER_OP"
            maxlength="50"
        >

        @error('code')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Status --}}
    <div class="col-md-6">

        <label for="status" class="form-label">
            Status
            <span class="text-danger">*</span>
        </label>

        <select
            name="status"
            id="status"
            class="form-select @error('status') is-invalid @enderror"
            required
        >

            <option value="">
                Select Status
            </option>

            <option
                value="Active"
                {{ old('status', $operatingModel->status ?? 'Active') === 'Active' ? 'selected' : '' }}
            >
                Active
            </option>

            <option
                value="Inactive"
                {{ old('status', $operatingModel->status ?? '') === 'Inactive' ? 'selected' : '' }}
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


    {{-- Sort Order --}}
    <div class="col-md-6">

        <label for="sort_order" class="form-label">
            Sort Order
        </label>

        <input
            type="number"
            name="sort_order"
            id="sort_order"
            class="form-control @error('sort_order') is-invalid @enderror"
            value="{{ old('sort_order', $operatingModel->sort_order ?? 0) }}"
            min="0"
            placeholder="0"
        >

        @error('sort_order')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Description --}}
    <div class="col-12">

        <label for="description" class="form-label">
            Description
        </label>

        <textarea
            name="description"
            id="description"
            rows="4"
            class="form-control @error('description') is-invalid @enderror"
            placeholder="Enter operating model description"
        >{{ old('description', $operatingModel->description ?? '') }}</textarea>

        @error('description')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

</div>