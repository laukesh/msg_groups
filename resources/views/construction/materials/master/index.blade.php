@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Material Master
            </h4>

            <p class="text-muted mb-0">
                {{ $project->project_number }}
                -
                {{ $project->project_name }}
            </p>
        </div>

        <div>

            <a href="{{ route(
                'admin.projects.construction.materials.index',
                $project
            ) }}"
               class="btn btn-secondary">

                ← Back to Materials

            </a>

            <a href="{{ route(
                'admin.projects.construction.materials.master.create',
                $project
            ) }}"
               class="btn btn-primary">

                + Add Material

            </a>

        </div>

    </div>


    {{-- Filters --}}

    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row">

                    <div class="col-md-5">

                        <label class="form-label">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-control"
                               placeholder="Material code, name or category">

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All
                            </option>

                            <option value="Active"
                                @selected(request('status') === 'Active')>
                                Active
                            </option>

                            <option value="Inactive"
                                @selected(request('status') === 'Inactive')>
                                Inactive
                            </option>

                        </select>

                    </div>


                    <div class="col-md-4 d-flex align-items-end">

                        <button type="submit"
                                class="btn btn-primary me-2">

                            Search

                        </button>

                        <a href="{{ route(
                            'admin.projects.construction.materials.master.index',
                            $project
                        ) }}"
                           class="btn btn-secondary">

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Table --}}

    <div class="card shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead>

                        <tr>

                            <th width="80">
                                #
                            </th>

                            <th>
                                Material Code
                            </th>

                            <th>
                                Material Name
                            </th>

                            <th>
                                Category
                            </th>

                            <th>
                                Specification
                            </th>

                            <th>
                                Unit
                            </th>

                            <th>
                                Status
                            </th>

                            <th width="180">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($materials as $material)

                            <tr>

                                <td>
                                    {{ $materials->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $material->material_code }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $material->material_name }}
                                </td>

                                <td>
                                    {{ $material->category ?: '-' }}
                                </td>

                                <td>
                                    {{ $material->specification ?: '-' }}
                                </td>

                                <td>
                                    {{ $material->unit ?: '-' }}
                                </td>

                                <td>

                                    @if($material->status === 'Active')

                                        <span class="badge bg-success">
                                            Active
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            Inactive
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <a href="{{ route(
                                        'admin.projects.construction.materials.master.show',
                                        [
                                            'project' => $project->id,
                                            'material' => $material->id,
                                        ]
                                    ) }}"
                                       class="btn btn-sm btn-info">

                                        View

                                    </a>

                                    <a href="{{ route(
                                        'admin.projects.construction.materials.master.edit',
                                        [
                                            'project' => $project->id,
                                            'material' => $material->id,
                                        ]
                                    ) }}"
                                       class="btn btn-sm btn-warning">

                                        Edit

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="8"
                                    class="text-center text-muted py-4">

                                    No materials found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            <div class="mt-3">

                {{ $materials->links() }}

            </div>

        </div>

    </div>

</div>

@endsection