@csrf

<div class="row g-3">

    <div class="col-md-8">
        <label class="form-label">
            Name <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="name"
            value="{{ old('name', $item->name ?? '') }}"
            class="form-control @error('name') is-invalid @enderror"
            maxlength="150"
            required
        >

        @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>


    <div class="col-md-4">
        <label class="form-label">
            Code
        </label>

        <input
            type="text"
            name="code"
            value="{{ old('code', $item->code ?? '') }}"
            class="form-control @error('code') is-invalid @enderror"
            maxlength="50"
        >

        @error('code')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>


    <div class="col-md-8">
        <label class="form-label">
            Description
        </label>

        <textarea
            name="description"
            rows="4"
            class="form-control @error('description') is-invalid @enderror"
        >{{ old('description', $item->description ?? '') }}</textarea>

        @error('description')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>


    <div class="col-md-2">
        <label class="form-label">
            Sort Order
        </label>

        <input
            type="number"
            name="sort_order"
            min="0"
            value="{{ old('sort_order', $item->sort_order ?? 0) }}"
            class="form-control"
        >
    </div>


    <div class="col-md-2">
        <label class="form-label">
            Status
        </label>

        <select name="status" class="form-select">

            <option
                value="Active"
                @selected(
                    old('status', $item->status ?? 'Active') === 'Active'
                )
            >
                Active
            </option>

            <option
                value="Inactive"
                @selected(
                    old('status', $item->status ?? '') === 'Inactive'
                )
            >
                Inactive
            </option>

        </select>
    </div>

</div>

<div class="mt-4 d-flex gap-2">

    <button class="btn btn-primary">
        <i class="ri-save-line me-1"></i>
        Save
    </button>

    <a
        href="{{ route('admin.assets.types.index') }}"
        class="btn btn-light border"
    >
        Cancel
    </a>

</div>