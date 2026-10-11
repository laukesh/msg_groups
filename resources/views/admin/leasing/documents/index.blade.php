@extends('layouts.app')

@section('title', 'Lease Documents')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">Lease Documents</h4>

            <div class="text-muted">
                Manage documents attached to lease agreements.
            </div>
        </div>

        <a href="{{ route('admin.leasing.documents.create') }}"
           class="btn btn-primary">

            <i class="fas fa-upload me-1"></i>
            Upload Document

        </a>

    </div>


    {{-- Success --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="fas fa-check-circle me-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Error --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="fas fa-exclamation-circle me-1"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    <div class="card">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover table-bordered mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="60">#</th>

                            <th>Agreement</th>

                            <th>Tenant</th>

                            <th>Document Type</th>

                            <th>Document Name</th>

                            <th>Version</th>

                            <th>Expiry</th>

                            <th>Status</th>

                            <th width="150">Action</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($documents as $document)

                            <tr>

                                <td>
                                    {{ $documents->firstItem() + $loop->index }}
                                </td>


                                {{-- Agreement --}}

                                <td>

                                    @if($document->agreement)

                                        <a href="{{ route(
                                            'admin.leasing.agreements.show',
                                            $document->agreement->id
                                        ) }}"
                                           class="fw-semibold text-decoration-none">

                                            {{ $document->agreement->agreement_no }}

                                        </a>

                                    @else

                                        -

                                    @endif

                                </td>


                                {{-- Tenant --}}

                                <td>

                                    {{ $document->agreement?->tenant?->company_name ?? '-' }}

                                </td>


                                {{-- Document Type --}}

                                <td>

                                    {{ $document->documentType?->document_name ?? '-' }}

                                </td>


                                {{-- Document Name --}}

                                <td>

                                    <div class="fw-semibold">

                                        {{ $document->document_name }}

                                    </div>


                                    @if($document->document_number)

                                        <small class="text-muted">

                                            No:
                                            {{ $document->document_number }}

                                        </small>

                                    @endif

                                </td>


                                {{-- Version --}}

                                <td>

                                    <span class="badge bg-secondary">

                                        v{{ $document->version_no ?? 1 }}

                                    </span>

                                </td>


                                {{-- Expiry --}}

                                <td>

                                    @if($document->expiry_date)

                                        @if($document->expiry_date->isPast())

                                            <span class="text-danger fw-semibold">

                                                {{ $document->expiry_date->format('d M Y') }}

                                            </span>

                                        @else

                                            {{ $document->expiry_date->format('d M Y') }}

                                        @endif

                                    @else

                                        -

                                    @endif

                                </td>


                                {{-- Verification Status --}}

                                <td>

                                    @if(
                                        $document->verification_status === 'Verified'
                                    )

                                        <span class="badge bg-success">

                                            Verified

                                        </span>

                                    @elseif(
                                        $document->verification_status === 'Rejected'
                                    )

                                        <span class="badge bg-danger">

                                            Rejected

                                        </span>

                                    @else

                                        <span class="badge bg-warning text-dark">

                                            Pending

                                        </span>

                                    @endif

                                </td>


                                {{-- Actions --}}

                                <td>

                                    <div class="d-flex gap-1">

                                        <a href="{{ route(
                                            'admin.leasing.documents.show',
                                            $document->id
                                        ) }}"
                                           class="btn btn-sm btn-info"
                                           title="View">

                                            <i class="fas fa-eye"></i>

                                        </a>


                                        <a href="{{ route(
                                            'admin.leasing.documents.edit',
                                            $document->id
                                        ) }}"
                                           class="btn btn-sm btn-primary"
                                           title="Edit">

                                            <i class="fas fa-edit"></i>

                                        </a>


                                        <form method="POST"
                                              action="{{ route(
                                                  'admin.leasing.documents.destroy',
                                                  $document->id
                                              ) }}"
                                              onsubmit="return confirm(
                                                  'Are you sure you want to delete this document?'
                                              );">

                                            @csrf

                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    title="Delete">

                                                <i class="fas fa-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9"
                                    class="text-center py-5">

                                    <i class="fas fa-file-alt fa-2x text-muted mb-2"></i>

                                    <div class="text-muted">

                                        No lease documents found.

                                    </div>


                                    <a href="{{ route(
                                        'admin.leasing.documents.create'
                                    ) }}"
                                       class="btn btn-primary btn-sm mt-3">

                                        <i class="fas fa-upload me-1"></i>

                                        Upload Document

                                    </a>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($documents->hasPages())

            <div class="card-footer">

                {{ $documents->links() }}

            </div>

        @endif

    </div>

</div>

@endsection