@php
    $isEdit = isset($areaType) && $areaType?->exists;
@endphp

<form action="{{ $action }}" method="POST">
    @csrf

    @if(isset($method) && $method !== 'POST')
        @method($method)
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                {{ $isEdit ? 'Edit Area Type' : 'Create Area Type' }}
            </h5>
        </div>

        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">
                        Name <span class="text-danger">*</span>
                    </label>

                    <input type="text"
                           name="name"
                           value="{{ old('name', $areaType?->name) }}"
                           class="form-control @error('name') is-invalid @enderror"
                           maxlength="50"
                           required>

                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Category</label>

                    <input type="text"
                           name="category"
                           value="{{ old('category', $areaType?->category) }}"
                           class="form-control @error('category') is-invalid @enderror"
                           maxlength="50">

                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Description</label>

                    <textarea name="description"
                              rows="4"
                              class="form-control @error('description') is-invalid @enderror">{{ old('description', $areaType?->description) }}</textarea>

                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Status</label>

                    <select name="status"
                            class="form-select">
                        <option value="ACTIVE"
                            @selected(old('status', $areaType?->status ?? 'ACTIVE') === 'ACTIVE')>
                            Active
                        </option>

                        <option value="INACTIVE"
                            @selected(old('status', $areaType?->status) === 'INACTIVE')>
                            Inactive
                        </option>
                    </select>
                </div>

            </div>
        </div>

        <div class="card-footer d-flex justify-content-end gap-2">

            <a href="{{ route('admin.assets.areas.types.index') }}"
               class="btn btn-outline-secondary">
                Cancel
            </a>

            <button type="submit" class="btn btn-primary">
                {{ $isEdit ? 'Update Type' : 'Create Type' }}
            </button>

        </div>
    </div>
</form>
