<div class="row">

    {{-- Department Code --}}
    <div class="col-lg-6 col-md-6 mb-3">

        <label
            for="department_code"
            class="form-label fw-semibold"
        >
            Department Code
            <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="department_code"
            id="department_code"
            class="form-control @error('department_code') is-invalid @enderror"
            value="{{ old(
                'department_code',
                $department->department_code ?? ''
            ) }}"
            placeholder="e.g. HR"
            maxlength="100"
            required
        >

        @error('department_code')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Department Name --}}
    <div class="col-lg-6 col-md-6 mb-3">

        <label
            for="department_name"
            class="form-label fw-semibold"
        >
            Department Name
            <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="department_name"
            id="department_name"
            class="form-control @error('department_name') is-invalid @enderror"
            value="{{ old(
                'department_name',
                $department->department_name ?? ''
            ) }}"
            placeholder="e.g. Human Resources"
            maxlength="150"
            required
        >

        @error('department_name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Parent Department --}}
    <div class="col-lg-6 col-md-6 mb-3">

        <label
            for="parent_department_id"
            class="form-label fw-semibold"
        >
            Parent Department
        </label>

        <select
            name="parent_department_id"
            id="parent_department_id"
            class="form-select @error('parent_department_id') is-invalid @enderror"
        >

            <option value="">
                No Parent Department
            </option>

            @foreach($departments ?? [] as $id => $name)

                <option
                    value="{{ $id }}"
                    {{ (string) old(
                        'parent_department_id',
                        $department->parent_department_id ?? ''
                    ) === (string) $id
                        ? 'selected'
                        : '' }}
                >
                    {{ $name }}
                </option>

            @endforeach

        </select>

        @error('parent_department_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Department Head --}}
    <div class="col-lg-6 col-md-6 mb-3">

        <label
            for="head_user_id"
            class="form-label fw-semibold"
        >
            Department Head
        </label>

        <select
            name="head_user_id"
            id="head_user_id"
            class="form-select @error('head_user_id') is-invalid @enderror"
        >

            <option value="">
                Select Department Head
            </option>

            @foreach($users ?? [] as $id => $name)

                <option
                    value="{{ $id }}"
                    {{ (string) old(
                        'head_user_id',
                        $department->head_user_id ?? ''
                    ) === (string) $id
                        ? 'selected'
                        : '' }}
                >
                    {{ $name }}
                </option>

            @endforeach

        </select>

        @error('head_user_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Status --}}
    <div class="col-lg-6 col-md-6 mb-3">

        <label
            for="status"
            class="form-label fw-semibold"
        >
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
                value="active"
                {{ old(
                    'status',
                    $department->status ?? 'active'
                ) === 'active'
                    ? 'selected'
                    : '' }}
            >
                Active
            </option>

            <option
                value="inactive"
                {{ old(
                    'status',
                    $department->status ?? 'active'
                ) === 'inactive'
                    ? 'selected'
                    : '' }}
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


    {{-- Description --}}
    <div class="col-12 mb-3">

        <label
            for="description"
            class="form-label fw-semibold"
        >
            Description
        </label>

        <textarea
            name="description"
            id="description"
            rows="4"
            maxlength="1000"
            class="form-control @error('description') is-invalid @enderror"
            placeholder="Enter department description..."
        >{{ old(
            'description',
            $department->description ?? ''
        ) }}</textarea>

        @error('description')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

</div>