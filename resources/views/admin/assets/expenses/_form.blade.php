<div class="card">

    <div class="card-header">

        <h5 class="mb-0">
            Expense Details
        </h5>

    </div>

    <div class="card-body">

        <form method="POST"
              action="{{ $action }}">

            @csrf

            @if($method !== 'POST')

                @method($method)

            @endif


            <div class="row g-3">

                <div class="col-md-6">

                    <label class="form-label">
                        Expense Type *
                    </label>

                    <input type="text"
                           name="expense_type"
                           class="form-control"
                           value="{{ old(
                               'expense_type',
                               $expense?->expense_type
                           ) }}"
                           placeholder="Maintenance, Security, Electricity..."
                           required>

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Expense Date *
                    </label>

                    <input type="date"
                           name="expense_date"
                           class="form-control"
                           value="{{ old(
                               'expense_date',
                               $expense?->expense_date?->format('Y-m-d')
                           ) }}"
                           required>

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Vendor
                    </label>

                    <input type="text"
                           name="vendor_name"
                           class="form-control"
                           value="{{ old(
                               'vendor_name',
                               $expense?->vendor_name
                           ) }}">

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Amount *
                    </label>

                    <input type="number"
                           step="0.01"
                           min="0"
                           name="amount"
                           class="form-control"
                           value="{{ old(
                               'amount',
                               $expense?->amount ?? 0
                           ) }}"
                           required>

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Status *
                    </label>

                    <select name="status"
                            class="form-select">

                        @foreach([
                            'Paid',
                            'Pending',
                            'Cancelled'
                        ] as $status)

                            <option value="{{ $status }}"
                                @selected(
                                    old(
                                        'status',
                                        $expense?->status ?? 'Paid'
                                    ) === $status
                                )>

                                {{ $status }}

                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-md-6">

                    <label class="form-label d-block">
                        Expense Classification
                    </label>

                    <div class="form-check mt-2">

                        <input type="checkbox"
                               class="form-check-input"
                               id="is_operating_expense"
                               name="is_operating_expense"
                               value="1"
                               @checked(
                                   old(
                                       'is_operating_expense',
                                       $expense?->is_operating_expense ?? true
                                   )
                               )>

                        <label class="form-check-label"
                               for="is_operating_expense">

                            Operating Expense

                        </label>

                    </div>

                </div>


                <div class="col-12">

                    <label class="form-label">
                        Remarks
                    </label>

                    <textarea name="remarks"
                              rows="3"
                              class="form-control">{{ old(
                                  'remarks',
                                  $expense?->remarks
                              ) }}</textarea>

                </div>

            </div>


            <div class="d-flex justify-content-end gap-2 mt-4">

                <a href="{{ route(
                    'admin.assets.expenses.index',
                    $asset->id
                ) }}"
                   class="btn btn-secondary">

                    Cancel

                </a>

                <button type="submit"
                        class="btn btn-danger">

                    <i class="fas fa-save"></i>

                    {{ $method === 'POST'
                        ? 'Add Expense'
                        : 'Update Expense'
                    }}

                </button>

            </div>

        </form>

    </div>

</div>