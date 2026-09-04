@extends('layouts.app')

@section('title', 'Unit Documents')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="fas fa-file-alt me-2"></i>
                Unit Documents
            </h4>

            <div class="text-muted">
                Manage unit documents.
            </div>
        </div>

        @can('unit_documents.create')

            <a
                href="{{ route('admin.assets.unit-documents.create') }}"
                class="btn btn-primary"
            >
                <i class="fas fa-plus me-1"></i>
                Add Unit Document
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


    <div class="card mb-4">

        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-filter me-1"></i>
                Search & Filter
            </h5>
        </div>

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.assets.unit-documents.index') }}"
            >

                <div class="row g-3">

                    <div class="col-md-7">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search document..."
                            value="{{ request('search') }}"
                        >

                    </div>

                    <div class="col-md-2 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            <i class="fas fa-search me-1"></i>
                            Search
                        </button>

                    </div>

                    <div class="col-md-2 d-flex align-items-end">

                        <a
                            href="{{ route('admin.assets.unit-documents.index') }}"
                            class="btn btn-secondary"
                        >
                            Clear
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    <div class="card border-0 shadow-sm">

        <div class="card-header d-flex justify-content-between">

            <h5 class="mb-0">
                <i class="fas fa-list me-1"></i>
                Unit Document List
            </h5>

            <span class="text-muted">
                Total: {{ $documents->total() }}
            </span>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th>ID</th>
                            <th>Unit</th>
                            <th>Document Type</th>
                            <th>Document Name</th>
                            <th>Document Number</th>
                            <th>Document Date</th>
                            <th>Expiry Date</th>
                            <th width="220">Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                    @forelse($documents as $document)

                        <tr>

                            <td>{{ $document->id }}</td>

                            <td>
                                {{ $document->unit?->unit_no ?? $document->unit_id }}
                            </td>

                            <td>
                                {{ $document->document_type }}
                            </td>

                            <td>
                                <strong>
                                    {{ $document->document_name }}
                                </strong>
                            </td>

                            <td>
                                {{ $document->document_number ?: '-' }}
                            </td>

                            <td>
                                {{ $document->document_date?->format('d M Y') ?? '-' }}
                            </td>

                            <td>
                                {{ $document->expiry_date?->format('d M Y') ?? '-' }}
                            </td>

                            <td>

                                <a
                                    href="{{ route(
                                        'admin.assets.unit-documents.show',
                                        $document->id
                                    ) }}"
                                    class="btn btn-sm btn-info"
                                >
                                    <i class="fas fa-eye"></i>
                                    View
                                </a>

                                @can('unit_documents.edit')

                                    <a
                                        href="{{ route(
                                            'admin.assets.unit-documents.edit',
                                            $document->id
                                        ) }}"
                                        class="btn btn-sm btn-primary"
                                    >
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </a>

                                @endcan

                                @can('unit_documents.delete')

                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'admin.assets.unit-documents.destroy',
                                            $document->id
                                        ) }}"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this document?')"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-danger"
                                        >
                                            <i class="fas fa-trash"></i>
                                            Delete
                                        </button>

                                    </form>

                                @endcan

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="text-center py-5"
                            >

                                <div class="text-muted">

                                    <i class="fas fa-file-alt fa-2x mb-2"></i>

                                    <div>
                                        No unit documents found.
                                    </div>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        @if($documents->hasPages())

            <div class="card-footer">

                {{ $documents->withQueryString()->links() }}

            </div>

        @endif

    </div>

</div>

@endsection