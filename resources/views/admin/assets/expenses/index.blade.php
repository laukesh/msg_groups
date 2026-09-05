@extends('layouts.app')

@section('title', 'Asset Expenses')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                <i class="fas fa-file-invoice-dollar me-2"></i>
                Asset Expenses
            </h4>

            <div class="text-muted">

                {{ $asset->asset_code }}
                -
                {{ $asset->asset_name }}

            </div>

        </div>

        <div class="d-flex gap-2">

            <a href="{{ route(
                'admin.assets.show',
                $asset->id
            ) }}"
               class="btn btn-secondary">

                <i class="fas fa-arrow-left"></i>
                Back

            </a>

            <a href="{{ route(
                'admin.assets.expenses.create',
                $asset->id
            ) }}"
               class="btn btn-danger">

                <i class="fas fa-plus"></i>
                Add Expense

            </a>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">

            <i class="fas fa-check-circle me-1"></i>

            {{ session('success') }}

        </div>

    @endif


    <div class="card">

        <div class="card-header">

            <h5 class="mb-0">

                <i class="fas fa-file-invoice-dollar me-1"></i>

                Expense History

            </h5>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>
                            <th>Date</th>
                            <th>Expense Type</th>
                            <th>Vendor</th>
                            <th>Amount</th>
                            <th>Operating</th>
                            <th>Status</th>
                            <th width="130">Actions</th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($expenses as $expense)

                        <tr>

                            <td>
                                {{ $expense->id }}
                            </td>

                            <td>
                                {{ $expense->expense_date?->format('d M Y') }}
                            </td>

                            <td>
                                {{ $expense->expense_type }}
                            </td>

                            <td>
                                {{ $expense->vendor_name ?? '-' }}
                            </td>

                            <td>

                                ${{ number_format(
                                    $expense->amount,
                                    2
                                ) }}

                            </td>

                            <td>

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

                            <td>
                                {{ $expense->status }}
                            </td>

                            <td>

                                <div class="d-flex gap-1">

                                    <a href="{{ route(
                                        'admin.assets.expenses.edit',
                                        [
                                            $asset->id,
                                            $expense->id
                                        ]
                                    ) }}"
                                       class="btn btn-sm btn-primary">

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

                                        <button class="btn btn-sm btn-danger">

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

                                <i class="fas fa-file-invoice-dollar fa-3x text-muted mb-3"></i>

                                <h5>
                                    No Expense Records
                                </h5>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($expenses->hasPages())

            <div class="card-footer">

                {{ $expenses->links() }}

            </div>

        @endif

    </div>

</div>

@endsection