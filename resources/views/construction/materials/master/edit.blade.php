@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4>
                Edit Material
            </h4>

            <p class="text-muted mb-0">
                {{ $project->project_number }}
                -
                {{ $project->project_name }}
            </p>
        </div>

        <a href="{{ route(
            'admin.projects.construction.materials.master.show',
            [
                'project' => $project->id,
                'material' => $material->id,
            ]
        ) }}"
           class="btn btn-secondary">

            ← Back to Material

        </a>

    </div>


    <div class="card shadow-sm">

        <div class="card-body">

            <form method="POST"
                  action="{{ route(
                      'admin.projects.construction.materials.master.update',
                      [
                          'project' => $project->id,
                          'material' => $material->id,
                      ]
                  ) }}">

                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Material Code
                        </label>

                        <input type="text"
                               value="{{ $material->material_code }}"
                               class="form-control"
                               readonly>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Material Name
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="material_name"
                               value="{{ old(
                                   'material_name',
                                   $material->material_name
                               ) }}"
                               class="form-control"
                               required>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Category
                        </label>

                        <input type="text"
                               name="category"
                               value="{{ old(
                                   'category',
                                   $material->category
                               ) }}"
                               class="form-control">

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Specification
                        </label>

                        <input type="text"
                               name="specification"
                               value="{{ old(
                                   'specification',
                                   $material->specification
                               ) }}"
                               class="form-control">

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Unit
                        </label>

                        <input type="text"
                               name="unit"
                               value="{{ old(
                                   'unit',
                                   $material->unit
                               ) }}"
                               class="form-control">

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Status
                            <span class="text-danger">*</span>
                        </label>

                        <select name="status"
                                class="form-select"
                                required>

                            <option value="Active"
                                @selected(
                                    old(
                                        'status',
                                        $material->status
                                    ) === 'Active'
                                )>
                                Active
                            </option>

                            <option value="Inactive"
                                @selected(
                                    old(
                                        'status',
                                        $material->status
                                    ) === 'Inactive'
                                )>
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
                                  class="form-control">{{ old(
                                      'description',
                                      $material->description
                                  ) }}</textarea>

                    </div>

                </div>


                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route(
                        'admin.projects.construction.materials.master.show',
                        [
                            'project' => $project->id,
                            'material' => $material->id,
                        ]
                    ) }}"
                       class="btn btn-secondary">

                        Cancel

                    </a>

                    <button type="submit"
                            class="btn btn-primary">

                        Update Material

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection