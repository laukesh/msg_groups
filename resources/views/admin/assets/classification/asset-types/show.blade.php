@extends('layouts.app')

@section('title', 'Asset Type Details')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>

            <h4 class="mb-1">
                Asset Type Details
            </h4>

            <div class="text-muted small">
                Asset classification master information.
            </div>

        </div>

        <div class="d-flex gap-2">

            @can('asset_types.edit')

                <a
                    href="{{ route(
                        'admin.assets.types.edit',
                        $item->id
                    ) }}"
                    class="btn btn-primary"
                >
                    <i class="ri-edit-line me-1"></i>
                    Edit
                </a>

            @endcan

            <a
                href="{{ route('admin.assets.types.index') }}"
                class="btn btn-light border"
            >
                Back
            </a>

        </div>

    </div>


    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <dl class="row mb-0">

                <dt class="col-md-3">
                    Name
                </dt>

                <dd class="col-md-9">
                    {{ $item->name }}
                </dd>


                <dt class="col-md-3">
                    Code
                </dt>

                <dd class="col-md-9">
                    {{ $item->code ?: '—' }}
                </dd>


                <dt class="col-md-3">
                    Description
                </dt>

                <dd class="col-md-9">
                    {!! nl2br(e($item->description ?: '—')) !!}
                </dd>


                <dt class="col-md-3">
                    Status
                </dt>

                <dd class="col-md-9">
                    {{ $item->status }}
                </dd>


                <dt class="col-md-3">
                    Sort Order
                </dt>

                <dd class="col-md-9">
                    {{ $item->sort_order }}
                </dd>


                <dt class="col-md-3">
                    Created By
                </dt>

                <dd class="col-md-9">
                    {{ optional($item->creator)->name ?: 'System' }}
                </dd>


                <dt class="col-md-3">
                    Updated By
                </dt>

                <dd class="col-md-9">
                    {{ optional($item->updater)->name ?: '—' }}
                </dd>

            </dl>

        </div>

    </div>

</div>

@endsection