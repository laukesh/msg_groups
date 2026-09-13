@extends('layouts.app')

@section('title', 'Asset Details')

@section('content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">

                <i class="fas fa-chart-line me-2"></i>

                {{ $asset->asset_name }}

            </h4>

            <div class="text-muted">

                Asset Code:
                <strong>{{ $asset->asset_code }}</strong>

            </div>

        </div>


        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.assets.edit',
                $asset->id
            ) }}"
               class="btn btn-primary">

                <i class="fas fa-pen"></i>
                Edit

            </a>

            <a href="{{ route('admin.assets.index') }}"
               class="btn btn-secondary">

                <i class="fas fa-arrow-left"></i>
                Back

            </a>

        </div>

    </div>


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


    {{-- Economic Summary --}}

    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Rental / Total Income
                    </div>

                    <h4 class="mb-0 mt-2">

                        ${{ number_format(
                            $summary['income'],
                            2
                        ) }}

                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Operating Expenses
                    </div>

                    <h4 class="mb-0 mt-2">

                        ${{ number_format(
                            $summary['operating_expenses'],
                            2
                        ) }}

                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        Net Operating Income
                    </div>

                    <h4 class="mb-0 mt-2">

                        ${{ number_format(
                            $summary['noi'],
                            2
                        ) }}

                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">
                        ROI
                    </div>

                    <h4 class="mb-0 mt-2">

                        {{ number_format(
                            $summary['roi'],
                            2
                        ) }}%

                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- Asset Information --}}

    <div class="card mb-4">

        <div class="card-header">

            <h5 class="mb-0">
                <i class="fas fa-info-circle me-1"></i>
                Asset Information
            </h5>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <strong>Asset Code</strong>

                    <div>
                        {{ $asset->asset_code }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <strong>Asset Type</strong>

                    <div>
                        {{ $asset->asset_type ?? '-' }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <strong>Status</strong>

                    <div>
                        {{ $asset->status }}
                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <strong>Purchase Date</strong>

                    <div>

                        {{ $asset->purchase_date
                            ? $asset->purchase_date->format('d M Y')
                            : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <strong>Purchase Cost</strong>

                    <div>

                        ${{ number_format(
                            $asset->purchase_cost,
                            2
                        ) }}

                    </div>

                </div>


                <div class="col-md-4 mb-3">

                    <strong>Current Value</strong>

                    <div>

                        ${{ number_format(
                            $asset->current_value,
                            2
                        ) }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Management Actions --}}

    <div class="card">

        <div class="card-header">

            <h5 class="mb-0">
                Economic Management
            </h5>

        </div>

        <div class="card-body">

            <div class="d-flex gap-2">

                <a href="{{ route(
                    'admin.assets.incomes.index',
                    $asset->id
                ) }}"
                   class="btn btn-success">

                    <i class="fas fa-money-bill-wave"></i>

                    Manage Income

                </a>


                <a href="{{ route(
                    'admin.assets.expenses.index',
                    $asset->id
                ) }}"
                   class="btn btn-danger">

                    <i class="fas fa-file-invoice-dollar"></i>

                    Manage Expenses

                </a>

            </div>

        </div>

    </div>

</div>

@endsection