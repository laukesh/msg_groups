@extends('layouts.app')

@section('title', 'Expense Details')

@section('content')

<div class="container-fluid py-3">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="d-flex flex-column flex-md-row
                justify-content-between
                align-items-md-center
                gap-3 mb-4">

        <div>
            <h4 class="mb-1 fw-bold">
                <i class="fas fa-file-invoice-dollar text-danger me-2"></i>
                Expense Details
            </h4>

            <div class="text-muted">
                {{ $asset->asset_code ?? 'N/A' }}
                -
                {{ $asset->asset_name ?? 'Unnamed Asset' }}
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">

            {{-- Back --}}
            <a href="{{ route('admin.assets.expenses.index', [
                'asset' => $asset->id
            ]) }}"
               class="btn btn-outline-secondary">

                <i class="fas fa-arrow-left me-1"></i>
                Back
            </a>

            {{-- Edit --}}
            <a href="{{ route('admin.assets.expenses.edit', [
                'asset'   => $asset->id,
                'expense' => $expense->id
            ]) }}"
               class="btn btn-primary">

                <i class="fas fa-edit me-1"></i>
                Edit
            </a>

        </div>
    </div>


    {{-- =========================================================
        CONTENT
    ========================================================== --}}
    <div class="row g-4">

        {{-- =====================================================
            EXPENSE INFORMATION
        ====================================================== --}}
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-bottom py-3">

                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Expense Information
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        {{-- Expense ID --}}
                        <div class="col-md-6">

                            <small class="text-muted d-block mb-1">
                                Expense ID
                            </small>

                            <div class="fw-bold">
                                #{{ $expense->id }}
                            </div>

                        </div>


                        {{-- Expense Date --}}
                        <div class="col-md-6">

                            <small class="text-muted d-block mb-1">
                                Expense Date
                            </small>

                            <div class="fw-bold">

                                @if($expense->expense_date)
                                    {{ \Carbon\Carbon::parse(
                                        $expense->expense_date
                                    )->format('d M Y') }}
                                @else
                                    -
                                @endif

                            </div>

                        </div>


                        {{-- Expense Type --}}
                        <div class="col-md-6">

                            <small class="text-muted d-block mb-1">
                                Expense Type
                            </small>

                            <div class="fw-bold">
                                {{ $expense->expense_type ?? '-' }}
                            </div>

                        </div>


                        {{-- Vendor --}}
                        <div class="col-md-6">

                            <small class="text-muted d-block mb-1">
                                Vendor
                            </small>

                            <div class="fw-bold">
                                {{ $expense->vendor_name ?? '-' }}
                            </div>

                        </div>


                        {{-- Amount --}}
                        <div class="col-md-6">

                            <small class="text-muted d-block mb-1">
                                Amount
                            </small>

                            <div class="fs-4 fw-bold text-danger">

                                ${{ number_format(
                                    (float) ($expense->amount ?? 0),
                                    2
                                ) }}

                            </div>

                        </div>


                        {{-- Status --}}
                        <div class="col-md-6">

                            <small class="text-muted d-block mb-1">
                                Status
                            </small>

                            @php
                                $status = strtolower(
                                    $expense->status ?? 'unknown'
                                );

                                $statusClass = match ($status) {
                                    'paid',
                                    'approved',
                                    'active',
                                    'completed' => 'bg-success',

                                    'pending',
                                    'processing' => 'bg-warning text-dark',

                                    'cancelled',
                                    'rejected',
                                    'inactive' => 'bg-danger',

                                    default => 'bg-secondary',
                                };
                            @endphp

                            <span class="badge {{ $statusClass }}">
                                {{ ucfirst($status) }}
                            </span>

                        </div>


                        {{-- Operating Expense --}}
                        <div class="col-md-6">

                            <small class="text-muted d-block mb-1">
                                Operating Expense
                            </small>

                            @if($expense->is_operating_expense)

                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>
                                    Yes
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    <i class="fas fa-times me-1"></i>
                                    No
                                </span>

                            @endif

                        </div>


                        {{-- Description --}}
                        <div class="col-12">

                            <small class="text-muted d-block mb-1">
                                Description
                            </small>

                            <div class="bg-light rounded p-3">

                                {{ $expense->description
                                    ?? 'No description provided.' }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            ASSET INFORMATION
        ====================================================== --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-bottom py-3">

                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-building text-primary me-2"></i>
                        Asset
                    </h5>

                </div>

                <div class="card-body">

                    {{-- Asset Code --}}
                    <div class="mb-3">

                        <small class="text-muted d-block mb-1">
                            Asset Code
                        </small>

                        <div class="fw-bold">
                            {{ $asset->asset_code ?? '-' }}
                        </div>

                    </div>


                    {{-- Asset Name --}}
                    <div class="mb-3">

                        <small class="text-muted d-block mb-1">
                            Asset Name
                        </small>

                        <div class="fw-bold">
                            {{ $asset->asset_name ?? '-' }}
                        </div>

                    </div>


                    {{-- Asset ID --}}
                    <div class="mb-3">

                        <small class="text-muted d-block mb-1">
                            Asset ID
                        </small>

                        <div class="fw-bold">
                            #{{ $asset->id }}
                        </div>

                    </div>


                    {{-- View Asset --}}
                    <a href="{{ route('admin.assets.assets.show', [
                        'asset' => $asset->id 
                    ]) }}"
                       class="btn btn-outline-primary w-100">

                        <i class="fas fa-building me-1"></i>
                        View Asset

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection