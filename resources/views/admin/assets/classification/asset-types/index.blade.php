@extends('layouts.app')

@section('title', 'Asset Types')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h4 class="mb-1">
                <i class="ri-folder-line me-2"></i>
                Asset Types
            </h4>

            <div class="text-muted small">
                Manage asset type classification.
            </div>
        </div>

        @can('types.create')
            <a
                href="{{ route('admin.assets.types.create') }}"
                class="btn btn-primary"
            >
                <i class="ri-add-line me-1"></i>
                Add Asset Type
            </a>
        @endcan

    </div>


    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <form method="GET" class="row g-2 mb-3">

                <div class="col-md-6">

                    <input
                        type="search"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control"
                        placeholder="Search by name or code..."
                    >

                </div>

                <div class="col-md-3">

                    <select
                        name="status"
                        class="form-select"
                    >

                        <option value="">
                            All Status
                        </option>

                        <option
                            value="Active"
                            @selected(request('status') === 'Active')
                        >
                            Active
                        </option>

                        <option
                            value="Inactive"
                            @selected(request('status') === 'Inactive')
                        >
                            Inactive
                        </option>

                    </select>

                </div>

                <div class="col-md-3 d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-outline-primary flex-fill"
                    >
                        <i class="ri-search-line"></i>
                        Search
                    </button>

                    <a
                        href="{{ route('admin.assets.types.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        Reset
                    </a>

                </div>

            </form>


            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>

                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Status</th>
                        <th>Sort Order</th>
                        <th width="180">Action</th>
                    </tr>

                    </thead>

                    <tbody>

                    @forelse($items as $item)

                        <tr>

                            <td>
                                {{ $items->firstItem() + $loop->index }}
                            </td>

                            <td class="fw-semibold">
                                {{ $item->name }}
                            </td>

                            <td>
                                {{ $item->code ?: '—' }}
                            </td>

                            <td>

                                <span
                                    class="badge {{
                                        $item->status === 'Active'
                                            ? 'bg-success'
                                            : 'bg-secondary'
                                    }}"
                                >
                                    {{ $item->status }}
                                </span>

                            </td>

                            <td>
                                {{ $item->sort_order }}
                            </td>

                            <td>

                                <div class="d-flex gap-1">

                                    @can('types.view')
                                        <a
                                            href="{{ route(
                                                'admin.assets.types.show',
                                                $item->id
                                            ) }}"
                                            class="btn btn-sm btn-outline-info"
                                        >
                                            <i class="ri-eye-line"></i>
                                        </a>
                                    @endcan


                                    @can('types.edit')
                                        <a
                                            href="{{ route(
                                                'admin.assets.types.edit',
                                                $item->id
                                            ) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            <i class="ri-edit-line"></i>
                                        </a>
                                    @endcan


                                    @can('types.delete')

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.assets.types.destroy',
                                                $item->id
                                            ) }}"
                                            onsubmit="return confirm(
                                                'Are you sure you want to delete this record?'
                                            )"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                            >
                                                <i class="ri-delete-bin-line"></i>
                                            </button>

                                        </form>

                                    @endcan

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center text-muted py-5"
                            >
                                No records found.
                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>


            <div class="mt-3">
                {{ $items->links() }}
            </div>

        </div>

    </div>

</div>

@endsection