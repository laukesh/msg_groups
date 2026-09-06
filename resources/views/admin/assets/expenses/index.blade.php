@extends('layouts.app')

@section('title', 'Asset Expenses')

@section('content')

<div class="container-fluid py-3">

    {{-- ==========================================================
        PAGE HEADER
    =========================================================== --}}
    <div class="d-flex flex-column flex-lg-row
                justify-content-between
                align-items-lg-center
                gap-3 mb-4">

        <div>

            <div class="d-flex align-items-center gap-2">

                <div class="bg-danger text-white rounded-circle
                            d-flex align-items-center justify-content-center"
                     style="width:45px;height:45px;">

                    <i class="fas fa-file-invoice-dollar"></i>

                </div>

                <div>

                    <h4 class="mb-0 fw-bold">
                        Asset Expenses
                    </h4>

                    <div class="text-muted small">

                        <strong>
                            {{ $asset->asset_code ?? 'N/A' }}
                        </strong>

                        -

                        {{ $asset->asset_name ?? 'Unnamed Asset' }}

                    </div>

                </div>

            </div>

        </div>


        <div class="d-flex flex-wrap gap-2">

            <a href="{{ route(
                'admin.assets.assets.show',
                $asset->id
            ) }}"
               class="btn btn-outline-secondary">

                <i class="fas fa-arrow-left me-1"></i>

                Back

            </a>


            <a href="{{ route(
                'admin.assets.expenses.create',
                $asset->id
            ) }}"
               class="btn btn-danger">

                <i class="fas fa-plus me-1"></i>

                Add Expense

            </a>

        </div>

    </div>


    {{-- ==========================================================
        SUCCESS MESSAGE
    =========================================================== --}}
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


    {{-- ==========================================================
        FILTERS
    =========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route(
                      'admin.assets.expenses.index',
                      $asset->id
                  ) }}">

                <div class="row g-3">

                    <div class="col-lg-4">

                        <label class="form-label fw-semibold">
                            Search
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>

                            <input type="text"
                                   name="search"
                                   class="form-control"
                                   value="{{ request('search') }}"
                                   placeholder="Expense, vendor or description">

                        </div>

                    </div>


                    <div class="col-lg-2">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option value="active"
                                @selected(request('status') === 'active')>
                                Active
                            </option>

                            <option value="paid"
                                @selected(request('status') === 'paid')>
                                Paid
                            </option>

                            <option value="pending"
                                @selected(request('status') === 'pending')>
                                Pending
                            </option>

                            <option value="cancelled"
                                @selected(request('status') === 'cancelled')>
                                Cancelled
                            </option>

                        </select>

                    </div>


                    <div class="col-lg-2">

                        <label class="form-label fw-semibold">
                            Operating
                        </label>

                        <select name="is_operating_expense"
                                class="form-select">

                            <option value="">
                                All
                            </option>

                            <option value="1"
                                @selected(request('is_operating_expense') === '1')>
                                Yes
                            </option>

                            <option value="0"
                                @selected(request('is_operating_expense') === '0')>
                                No
                            </option>

                        </select>

                    </div>


                    <div class="col-lg-2">

                        <label class="form-label fw-semibold">
                            From
                        </label>

                        <input type="date"
                               name="date_from"
                               class="form-control"
                               value="{{ request('date_from') }}">

                    </div>


                    <div class="col-lg-2">

                        <label class="form-label fw-semibold">
                            To
                        </label>

                        <input type="date"
                               name="date_to"
                               class="form-control"
                               value="{{ request('date_to') }}">

                    </div>

                </div>


                <div class="mt-3 d-flex gap-2">

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="fas fa-filter me-1"></i>

                        Filter

                    </button>


                    <a href="{{ route(
                        'admin.assets.expenses.index',
                        $asset->id
                    ) }}"
                       class="btn btn-outline-secondary">

                        <i class="fas fa-redo me-1"></i>

                        Reset

                    </a>

                </div>

            </form>

        </div>

    </div>


    {{-- ==========================================================
        EXPENSE TABLE
    =========================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white
                    d-flex justify-content-between
                    align-items-center">

            <div>

                <h5 class="mb-0 fw-bold">

                    <i class="fas fa-history text-danger me-2"></i>

                    Expense History

                </h5>

                <small class="text-muted">
                    {{ $expenses->total() }} records found
                </small>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered
                              table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="60">
                                #
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Expense Type
                            </th>

                            <th>
                                Vendor
                            </th>

                            <th class="text-end">
                                Amount
                            </th>

                            <th class="text-center">
                                Operating
                            </th>

                            <th class="text-center">
                                Status
                            </th>

                            <th width="130"
                                class="text-center">

                                Actions

                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($expenses as $expense)

                        <tr>

                            <td class="fw-semibold">

                                {{ $expense->id }}

                            </td>


                            <td>

                                {{ $expense->expense_date?->format('d M Y') ?? '-' }}

                            </td>


                            <td>

                                <span class="fw-semibold">

                                    {{ $expense->expense_type }}

                                </span>

                                @if($expense->description)

                                    <div class="small text-muted">

                                        {{ Str::limit(
                                            $expense->description,
                                            50
                                        ) }}

                                    </div>

                                @endif

                            </td>


                            <td>

                                {{ $expense->vendor_name ?? '-' }}

                            </td>


                            <td class="text-end fw-bold">

                                ${{ number_format(
                                    (float) $expense->amount,
                                    2
                                ) }}

                            </td>


                            <td class="text-center">

                                @if($expense->is_operating_expense)

                                    <span class="badge bg-success">
                                        Yes
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        No
                                    </span>

                                @endif

                            </td>


                            <td class="text-center">

                                @php

                                    $statusClass = match(
                                        strtolower(
                                            $expense->status ?? ''
                                        )
                                    ) {

                                        'active',
                                        'paid',
                                        'completed'
                                            => 'success',

                                        'pending'
                                            => 'warning',

                                        'cancelled',
                                        'rejected'
                                            => 'danger',

                                        default
                                            => 'secondary',

                                    };

                                @endphp

                                <span class="badge bg-{{ $statusClass }}">

                                    {{ ucfirst(
                                        $expense->status ?? 'Unknown'
                                    ) }}

                                </span>

                            </td>


                            <td>

                                <div class="d-flex
                                            justify-content-center
                                            gap-1">

                                    <a href="{{ route(
                                        'admin.assets.expenses.show',
                                        [
                                            $asset->id,
                                            $expense->id
                                        ]
                                    ) }}"
                                       class="btn btn-sm
                                              btn-outline-info"
                                       title="View">

                                        <i class="fas fa-eye"></i>

                                    </a>


                                    <a href="{{ route(
                                        'admin.assets.expenses.edit',
                                        [
                                            $asset->id,
                                            $expense->id
                                        ]
                                    ) }}"
                                       class="btn btn-sm
                                              btn-outline-primary"
                                       title="Edit">

                                        <i class="fas fa-pen"></i>

                                    </a>


                                    <form method="POST"
                                          action="{{ route(
                                              'admin.assets.expenses.destroy',
                                              [
                                                  $asset->id,
                                                  $expense->id
                                              ]
                                          ) }}"
                                          onsubmit="return confirm(
                                              'Delete this expense record?'
                                          );">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm
                                                       btn-outline-danger"
                                                title="Delete">

                                            <i class="fas fa-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8"
                                class="text-center py-5">

                                <div class="text-muted">

                                    <i class="fas fa-file-invoice-dollar
                                              fa-3x mb-3"></i>

                                    <h5>
                                        No Expense Records
                                    </h5>

                                    <p class="mb-3">
                                        No expenses have been recorded
                                        for this asset.
                                    </p>

                                    <a href="{{ route(
                                        'admin.assets.expenses.create',
                                        $asset->id
                                    ) }}"
                                       class="btn btn-danger">

                                        <i class="fas fa-plus me-1"></i>

                                        Add First Expense

                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($expenses->hasPages())

            <div class="card-footer bg-white">

                {{ $expenses->links() }}

            </div>

        @endif

    </div>

</div>

@endsection