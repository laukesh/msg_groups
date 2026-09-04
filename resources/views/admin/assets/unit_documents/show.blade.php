@extends('layouts.app')

@section('title', 'Unit Document')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                <i class="fas fa-file-alt me-2"></i>
                {{ $document->document_name }}
            </h4>

            <div class="text-muted">
                Unit Document #{{ $document->id }}
            </div>

        </div>

        <div>

            @can('unit_documents.edit')

                <a
                    href="{{ route(
                        'admin.assets.unit-documents.edit',
                        $document->id
                    ) }}"
                    class="btn btn-primary"
                >
                    <i class="fas fa-edit me-1"></i>
                    Edit
                </a>

            @endcan

            <a
                href="{{ route('admin.assets.unit-documents.index') }}"
                class="btn btn-secondary"
            >
                <i class="fas fa-arrow-left me-1"></i>
                Back
            </a>

        </div>

    </div>


    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                <i class="fas fa-info-circle me-2"></i>
                Document Information
            </h5>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <strong>Unit</strong>

                    <div>
                        {{ $document->unit?->unit_no ?? $document->unit_id }}
                    </div>

                </div>

                <div class="col-md-4 mb-3">

                    <strong>Document Type</strong>

                    <div>
                        {{ $document->document_type }}
                    </div>

                </div>

                <div class="col-md-4 mb-3">

                    <strong>Document Name</strong>

                    <div>
                        {{ $document->document_name }}
                    </div>

                </div>

                <div class="col-md-4 mb-3">

                    <strong>Document Number</strong>

                    <div>
                        {{ $document->document_number ?: '-' }}
                    </div>

                </div>

                <div class="col-md-4 mb-3">

                    <strong>Document Date</strong>

                    <div>
                        {{ $document->document_date?->format('d M Y') ?? '-' }}
                    </div>

                </div>

                <div class="col-md-4 mb-3">

                    <strong>Expiry Date</strong>

                    <div>
                        {{ $document->expiry_date?->format('d M Y') ?? '-' }}
                    </div>

                </div>

                <div class="col-12 mb-3">

                    <strong>Document Path</strong>

                    <div>
                        {{ $document->document_path ?: '-' }}
                    </div>

                </div>

                <div class="col-12">

                    <strong>Remarks</strong>

                    <div>
                        {{ $document->remarks ?: '-' }}
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection