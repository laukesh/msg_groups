@extends('layouts.app')

@section('title', 'Edit Asset Expense')

@section('content')

<div class="container-fluid py-3">

    <div class="d-flex justify-content-between
                align-items-center mb-4">

        <div>

            <h4 class="mb-1 fw-bold">

                <i class="fas fa-edit text-primary me-2"></i>

                Edit Asset Expense

            </h4>

            <div class="text-muted">

                {{ $asset->asset_code ?? 'N/A' }}
                -
                {{ $asset->asset_name ?? 'Unnamed Asset' }}

            </div>

        </div>


        <a href="{{ route(
            'admin.assets.expenses.index',
            $asset->id
        ) }}"
           class="btn btn-outline-secondary">

            <i class="fas fa-arrow-left me-1"></i>
            Back

        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-bold">
                Expense Information
            </h5>

        </div>


        <div class="card-body">

            <form method="POST"
                  action="{{ route(
                      'admin.assets.expenses.update',
                      [
                          $asset->id,
                          $expense->id
                      ]
                  ) }}">

                @csrf

                @method('PUT')

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Expense Date
                        </label>

                        <input type="date"
                               name="expense_date"
                               class="form-control"
                               value="{{ old(
                                   'expense_date',
                                   $expense->expense_date?->format('Y-m-d')
                               ) }}"
                               required>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Expense Type
                        </label>

                        <input type="text"
                               name="expense_type"
                               class="form-control"
                               value="{{ old(
                                   'expense_type',
                                   $expense->expense_type
                               ) }}"
                               required>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Vendor
                        </label>

                        <input type="text" readonly
                               name="vendor_name"
                               class="form-control"
                               value="{{ old(
                                   'vendor_name',
                                   $expense->vendor_name
                               ) }}">

                    </div>


                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Amount
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                $
                            </span>

                            <input type="number"
                                   name="amount"
                                   class="form-control"
                                   step="0.01"
                                   min="0"
                                   value="{{ old(
                                       'amount',
                                       $expense->amount
                                   ) }}"
                                   required>

                        </div>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select name="status"
                                class="form-select"
                                required>

                            @foreach([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'active' => 'Active',
                                'cancelled' => 'Cancelled'
                            ] as $value => $label)

                                <option value="{{ $value }}"
                                    @selected(
                                        old(
                                            'status',
                                            $expense->status
                                        ) === $value
                                    )>

                                    {{ $label }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Expense Classification
                        </label>

                        <div class="form-check form-switch mt-2">

                            <input type="hidden"
                                   name="is_operating_expense"
                                   value="0">

                            <input class="form-check-input"
                                   type="checkbox"
                                   name="is_operating_expense"
                                   value="1"
                                   @checked(
                                       old(
                                           'is_operating_expense',
                                           $expense->is_operating_expense
                                       )
                                   )>

                            <label class="form-check-label">
                                Operating Expense
                            </label>

                        </div>

                    </div>


                    <div class="col-12">

                        <label class="form-label fw-semibold">
                            Description
                        </label>

                        <textarea name="description"
                                  rows="4"
                                  class="form-control">{{ old(
                                      'description',
                                      $expense->description
                                  ) }}</textarea>

                    </div>

                </div>


                <hr class="my-4">


                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route(
                        'admin.assets.expenses.index',
                        $asset->id
                    ) }}"
                       class="btn btn-outline-secondary">

                        Cancel

                    </a>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="fas fa-save me-1"></i>

                        Update Expense

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection