<div class="row">

    <div class="col-lg-6 col-md-12 mb-3">

        <label for="category_name" class="form-label fw-semibold">
            Category Name
            <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="category_name"
            id="category_name"
            class="form-control @error('category_name') is-invalid @enderror"
            value="{{ old('category_name', $category->category_name ?? '') }}"
            placeholder="e.g. Electrical Equipment"
            maxlength="150"
            required
        >

        @error('category_name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    <div class="col-lg-6 col-md-12 mb-3">

        <label for="is_active" class="form-label fw-semibold">
            Status
            <span class="text-danger">*</span>
        </label>

        <select
            name="is_active"
            id="is_active"
            class="form-select @error('is_active') is-invalid @enderror"
            required
        >

            <option value="1"
                {{ old('is_active', $category->is_active ?? 1) == 1 ? 'selected' : '' }}>
                Active
            </option>

            <option value="0"
                {{ old('is_active', $category->is_active ?? 1) == 0 ? 'selected' : '' }}>
                Inactive
            </option>

        </select>

        @error('is_active')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    <div class="col-12 mb-3">

        <label for="description" class="form-label fw-semibold">
            Description
        </label>

        <textarea
            name="description"
            id="description"
            rows="4"
            class="form-control @error('description') is-invalid @enderror"
            placeholder="Enter category description..."
            maxlength="1000"
        >{{ old('description', $category->description ?? '') }}</textarea>

        @error('description')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

</div>