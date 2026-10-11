@extends('layouts.admin')

@section('title', 'Area Types')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Area Types</h1>
            <p class="text-muted mb-0">
                Manage area types and categories.
            </p>
        </div>

        <a href="{{ route('admin.assets.areas.types.create') }}"
           class="btn btn-primary">
            Add Area Type
        </a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>

                <tbody>
                @forelse($areaTypes as $type)
                    <tr>

                        <td class="fw-semibold">
                            {{ $type->name }}
                        </td>

                        <td>
                            {{ $type->category ?? '-' }}
                        </td>

                        <td>
                            {{ \Illuminate\Support\Str::limit(
                                $type->description,
                                80
                            ) }}
                        </td>

                        <td>
                            <span class="badge
                                {{ $type->status === 'ACTIVE'
                                    ? 'bg-success'
                                    : 'bg-secondary' }}">
                                {{ $type->status }}
                            </span>
                        </td>

                        <td class="text-end">
                            <a href="{{ route(
                                'admin.assets.areas.types.edit',
                                $type
                            ) }}"
                               class="btn btn-sm btn-outline-secondary">
                                Edit
                            </a>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5"
                            class="text-center text-muted py-5">
                            No area types found.
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>

        @if(method_exists($areaTypes, 'hasPages') && $areaTypes->hasPages())
            <div class="card-footer">
                {{ $areaTypes->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
