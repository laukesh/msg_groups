@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4>Add Material</h4>

            <p class="text-muted mb-0">
                {{ $project->project_number }}
                -
                {{ $project->project_name }}
            </p>
        </div>

        <a href="{{ route(
            'admin.projects.construction.materials.master.index',
            $project
        ) }}"
           class="btn btn-secondary">

            ← Back to Material Master

        </a>

    </div>


    <div class="card shadow-sm">

        <div class="card-body">

            <form method="POST"
                  action="{{ route(
                      'admin.projects.construction.materials.master.store',
                      $project
                  ) }}">

                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Material Name <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="material_name"
                               value="{{ old('material_name') }}"
                               class="form-control @error('material_name') is-invalid @enderror"
                               required>

                        @error('material_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Category
                        </label>

                        <input type="text"
                               name="category"
                               value="{{ old('category') }}"
                               class="form-control"
                               placeholder="Cement, Steel, Electrical, Plumbing...">

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Specification
                        </label>

                        <input type="text"
                               name="specification"
                               value="{{ old('specification') }}"
                               class="form-control">

                    </div>


                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Unit
                        </label>

                        <input type="text"
                               name="unit"
                               value="{{ old('unit') }}"
                               class="form-control"
                               placeholder="Kg, Bag, Nos, MT">

                    </div>


                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Status <span class="text-danger">*</span>
                        </label>

                        <select name="status"
                                class="form-select"
                                required>

                            <option value="Active"
                                @selected(old('status', 'Active') === 'Active')>
                                Active
                            </option>

                            <option value="Inactive"
                                @selected(old('status') === 'Inactive')>
                                Inactive
                            </option>

                        </select>

                    </div>


                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea name="description"
                                  rows="4"
                                  class="form-control">{{ old('description') }}</textarea>

                    </div>

                </div>


                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route(
                        'admin.projects.construction.materials.master.index',
                        $project
                    ) }}"
                       class="btn btn-secondary">

                        Cancel

                    </a>

                    <button type="submit"
                            class="btn btn-primary">

                        Save Material

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection