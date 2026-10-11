@extends('layouts.app')

@section('title', $department->department_name)

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">

                <i class="fas fa-sitemap me-2"></i>

                {{ $department->department_name }}

            </h4>

            <div class="text-muted">

                Department #{{ $department->id }}

            </div>

        </div>

        <div class="d-flex gap-2">

            @can('departments.edit')

                <a
                    href="{{ route(
                        'admin.assets.departments.edit',
                        $department->id
                    ) }}"
                    class="btn btn-primary"
                >

                    <i class="fas fa-edit me-1"></i>

                    Edit

                </a>

            @endcan

            <a
                href="{{ route(
                    'admin.assets.departments.index'
                ) }}"
                class="btn btn-outline-secondary"
            >

                <i class="fas fa-arrow-left me-1"></i>

                Back

            </a>

        </div>

    </div>


    {{-- Department Information --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">

                <i class="fas fa-info-circle me-2"></i>

                Department Information

            </h5>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-lg-4 col-md-6 mb-3">

                    <strong>Department Code</strong>

                    <div>
                        {{ $department->department_code }}
                    </div>

                </div>


                <div class="col-lg-4 col-md-6 mb-3">

                    <strong>Department Name</strong>

                    <div>
                        {{ $department->department_name }}
                    </div>

                </div>


                <div class="col-lg-4 col-md-6 mb-3">

                    <strong>Status</strong>

                    <div>

                        @if(
                            strtolower(
                                (string) $department->status
                            ) === 'active'
                        )

                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>
                                Active
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                <i class="fas fa-times-circle me-1"></i>
                                {{ ucfirst(
                                    $department->status
                                ) }}
                            </span>

                        @endif

                    </div>

                </div>


                <div class="col-lg-4 col-md-6 mb-3">

                    <strong>Parent Department</strong>

                    <div>

                        {{ $department
                            ->parentDepartment
                            ?->department_name
                            ?? '-' }}

                    </div>

                </div>


                <div class="col-lg-4 col-md-6 mb-3">

                    <strong>Department Head</strong>

                    <div>

                        {{ $department
                            ->headUser
                            ?->name
                            ?? '-' }}

                    </div>

                </div>


                <div class="col-12 mb-3">

                    <strong>Description</strong>

                    <div>

                        {{ $department->description ?: '-' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Child Departments --}}
    @if($department->childDepartments->count())

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">

                    <i class="fas fa-sitemap me-2"></i>

                    Child Departments

                </h5>

            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-bordered mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>ID</th>

                                <th>Code</th>

                                <th>Department Name</th>

                                <th>Status</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach(
                                $department->childDepartments
                                as $child
                            )

                                <tr>

                                    <td>
                                        {{ $child->id }}
                                    </td>

                                    <td>
                                        {{ $child->department_code }}
                                    </td>

                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.assets.departments.show',
                                                $child->id
                                            ) }}"
                                        >
                                            {{ $child->department_name }}
                                        </a>

                                    </td>

                                    <td>

                                        @if(
                                            strtolower(
                                                (string) $child->status
                                            ) === 'active'
                                        )

                                            <span
                                                class="badge bg-success"
                                            >
                                                Active
                                            </span>

                                        @else

                                            <span
                                                class="badge bg-secondary"
                                            >
                                                {{ ucfirst(
                                                    $child->status
                                                ) }}
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    @endif


    {{-- Audit Information --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0">

                <i class="fas fa-history me-2"></i>

                Audit Information

            </h5>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-lg-3 col-md-6 mb-3">

                    <strong>Created By</strong>

                    <div>

                        {{ $department
                            ->creator
                            ?->name
                            ?? '-' }}

                    </div>

                </div>


                <div class="col-lg-3 col-md-6 mb-3">

                    <strong>Updated By</strong>

                    <div>

                        {{ $department
                            ->updater
                            ?->name
                            ?? '-' }}

                    </div>

                </div>


                <div class="col-lg-3 col-md-6 mb-3">

                    <strong>Created At</strong>

                    <div>

                        {{ $department->created_at?->format(
                            'd M Y H:i'
                        ) ?? '-' }}

                    </div>

                </div>


                <div class="col-lg-3 col-md-6 mb-3">

                    <strong>Updated At</strong>

                    <div>

                        {{ $department->updated_at?->format(
                            'd M Y H:i'
                        ) ?? '-' }}

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection