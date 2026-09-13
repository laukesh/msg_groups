@extends('layouts.app')

@section('title', 'Add Asset Expense')

@section('content')

<div class="container-fluid py-3">

    <div class="d-flex justify-content-between
                align-items-center mb-4">

        <div>

            <h4 class="mb-1 fw-bold">
                <i class="fas fa-plus-circle text-danger me-2"></i>
                Add Asset Expense
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

            <strong>
                Please fix the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0 fw-bold">

                <i class="fas fa-file-invoice-dollar
                          text-danger me-2"></i>

                Expense Information

            </h5>

        </div>


        <div class="card-body">

            <form method="POST"
                  action="{{ route(
                      'admin.assets.expenses.store',
                      $asset->id
                  ) }}">

                @csrf

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Expense Date
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="expense_date"
                               class="form-control"
                               value="{{ old(
                                   'expense_date',
                                   now()->format('Y-m-d')
                               ) }}"
                               required>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Expense Type
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="expense_type"
                               class="form-control"
                               value="{{ old('expense_type') }}"
                               placeholder="Maintenance, Electricity, Repair..."
                               required>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Vendor
                        </label>

                       <input type="text" readonly
                            name="vendor_name"
                            class="form-control"
                            value="{{ old('vendor_name') ?? $asset->vendor?->name }}"
                            placeholder="Vendor name">

                    </div>


                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Amount
                            <span class="text-danger">*</span>
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
                                   value="{{ old('amount') }}"
                                   placeholder="0.00"
                                   required>

                        </div>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Status
                            <span class="text-danger">*</span>
                        </label>

                        <select name="status"
                                class="form-select"
                                required>

                            <option value="">
                                Select Status
                            </option>

                            <option value="pending"
                                @selected(
                                    old('status') === 'pending'
                                )>
                                Pending
                            </option>

                            <option value="paid"
                                @selected(
                                    old('status') === 'paid'
                                )>
                                Paid
                            </option>

                            <option value="active"
                                @selected(
                                    old('status') === 'active'
                                )>
                                Active
                            </option>

                            <option value="cancelled"
                                @selected(
                                    old('status') === 'cancelled'
                                )>
                                Cancelled
                            </option>

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
                                   id="operatingExpense"
                                   @checked(
                                       old(
                                           'is_operating_expense',
                                           true
                                       )
                                   )>

                            <label class="form-check-label"
                                   for="operatingExpense">

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
                                  class="form-control"
                                  placeholder="Enter expense details...">{{ old('description') }}</textarea>

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
                            class="btn btn-danger">

                        <i class="fas fa-save me-1"></i>

                        Save Expense

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection