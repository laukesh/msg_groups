@extends('layouts.app')

@section('title', 'Asset Income')

@section('content')

<div class="container-fluid">
{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h4 class="mb-1">
            <i class="fas fa-money-bill-wave me-2"></i>
            Asset Income
        </h4>

        @if($asset)
            <div class="text-muted">
                {{ $asset->asset_code }}
                -
                {{ $asset->asset_name }}
            </div>
        @else
            <div class="text-muted">
                All Asset Income Records
            </div>
        @endif

    </div>

    <div class="d-flex gap-2">

        @if($asset)

            <a href="{{ route(
                'admin.assets.assets.show',
                $asset->id
            ) }}"
               class="btn btn-secondary">

                <i class="fas fa-arrow-left me-1"></i>
                Back

            </a>

            <a href="{{ route(
                'admin.assets.incomes.create',
                $asset->id
            ) }}"
               class="btn btn-success">

                <i class="fas fa-plus me-1"></i>
                Add Income

            </a>

        @else

            <a href="{{ route('admin.assets.incomes.index') }}"
               class="btn btn-secondary">

                <i class="fas fa-arrow-left me-1"></i>
                Back to Assets

            </a>

        @endif

    </div>

</div>


{{-- Success Message --}}
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


{{-- Income Card --}}
<div class="card shadow-sm">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">

            <i class="fas fa-money-bill-wave me-1"></i>

            @if($asset)
                Income History
            @else
                All Income Records
            @endif

        </h5>

        <span class="badge bg-primary">

            {{ $incomes->total() }}
            {{ $incomes->total() == 1 ? 'Record' : 'Records' }}

        </span>

    </div>


    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th>#</th>

                        @if(!$asset)
                            <th>Asset</th>
                        @endif

                        <th>Date</th>

                        <th>Type</th>

                        <th>Period</th>

                        <th>Amount</th>

                        <th>Status</th>

                        <th width="130">Actions</th>

                    </tr>

                </thead>


                <tbody>

                @forelse($incomes as $income)

                    <tr>

                        {{-- ID --}}
                        <td>
                            {{ $income->id }}
                        </td>


                        {{-- Asset --}}
                        @if(!$asset)

                            <td>

                                @if($income->asset)

                                    <div class="fw-semibold">
                                        {{ $income->asset->asset_code }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $income->asset->asset_name }}
                                    </small>

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </td>

                        @endif


                        {{-- Income Date --}}
                        <td>

                            {{ $income->income_date?->format('d M Y') ?? '-' }}

                        </td>


                        {{-- Income Type --}}
                        <td>

                            <span class="fw-semibold">
                                {{ $income->income_type }}
                            </span>

                        </td>


                        {{-- Billing Period --}}
                        <td>

                            @if($income->billing_period_from)

                                {{ $income->billing_period_from->format('d M Y') }}

                            @else

                                -

                            @endif

                            <br>

                            <small class="text-muted">

                                to

                                {{ $income->billing_period_to?->format('d M Y') ?? '-' }}

                            </small>

                        </td>


                        {{-- Amount --}}
                        <td>

                            <span class="fw-semibold text-success">

                                ${{ number_format(
                                    $income->amount,
                                    2
                                ) }}

                            </span>

                        </td>


                        {{-- Status --}}
                        <td>

                            @php
                                $statusClass = match(strtolower($income->status ?? '')) {
                                    'paid',
                                    'received',
                                    'active',
                                    'approved' => 'success',

                                    'pending',
                                    'draft' => 'warning',

                                    'cancelled',
                                    'rejected',
                                    'inactive' => 'danger',

                                    default => 'secondary',
                                };
                            @endphp

                            <span class="badge bg-{{ $statusClass }}">

                                {{ $income->status ?? '-' }}

                            </span>

                        </td>


                        {{-- Actions --}}
                        <td>

                            <div class="d-flex gap-1">

                                @php
                                    $incomeAssetId = $asset?->id ?? $income->asset_id;
                                @endphp

                                @if($incomeAssetId)

                                    <a href="{{ route(
                                        'admin.assets.incomes.edit',
                                        [
                                            $incomeAssetId,
                                            $income->id
                                        ]
                                    ) }}"
                                       class="btn btn-sm btn-primary"
                                       title="Edit">

                                        <i class="fas fa-pen"></i>

                                    </a>


                                    <form method="POST"
                                          action="{{ route(
                                              'admin.assets.incomes.destroy',
                                              [
                                                  $incomeAssetId,
                                                  $income->id
                                              ]
                                          ) }}"
                                          onsubmit="return confirm(
                                              'Delete this income record?'
                                          );">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                title="Delete">

                                            <i class="fas fa-trash"></i>

                                        </button>

                                    </form>

                                @endif

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="{{ $asset ? 7 : 8 }}"
                            class="text-center py-5">

                            <div class="text-muted">

                                <i class="fas fa-money-bill-wave fa-3x mb-3"></i>

                                <h5 class="mb-2">
                                    No Income Records
                                </h5>

                                @if($asset)

                                    <p class="mb-3">
                                        No income has been recorded for this asset yet.
                                    </p>

                                    <a href="{{ route(
                                        'admin.assets.incomes.create',
                                        $asset->id
                                    ) }}"
                                       class="btn btn-success">

                                        <i class="fas fa-plus me-1"></i>
                                        Add Income

                                    </a>

                                @else

                                    <p class="mb-0">
                                        No asset income records are available.
                                    </p>

                                @endif

                            </div>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- Pagination --}}
    @if($incomes->hasPages())

        <div class="card-footer">

            {{ $incomes->links() }}

        </div>

    @endif

</div>

</div>

@endsection
