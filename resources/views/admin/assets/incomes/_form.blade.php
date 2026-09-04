<div class="card">

    <div class="card-header">

        <h5 class="mb-0">
            Income Details
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
                        Income Type *
                    </label>

                    <input type="text"
                           name="income_type"
                           class="form-control"
                           value="{{ old(
                               'income_type',
                               $income?->income_type ?? 'Rental'
                           ) }}"
                           required>

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Income Date *
                    </label>

                    <input type="date"
                           name="income_date"
                           class="form-control"
                           value="{{ old(
                               'income_date',
                               $income?->income_date?->format('Y-m-d')
                           ) }}"
                           required>

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Billing From
                    </label>

                    <input type="date"
                           name="billing_period_from"
                           class="form-control"
                           value="{{ old(
                               'billing_period_from',
                               $income?->billing_period_from?->format('Y-m-d')
                           ) }}">

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Billing To
                    </label>

                    <input type="date"
                           name="billing_period_to"
                           class="form-control"
                           value="{{ old(
                               'billing_period_to',
                               $income?->billing_period_to?->format('Y-m-d')
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
                               $income?->amount ?? 0
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
                            'Received',
                            'Pending',
                            'Cancelled'
                        ] as $status)

                            <option value="{{ $status }}"
                                @selected(
                                    old(
                                        'status',
                                        $income?->status ?? 'Received'
                                    ) === $status
                                )>

                                {{ $status }}

                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-12">

                    <label class="form-label">
                        Remarks
                    </label>

                    <textarea name="remarks"
                              rows="3"
                              class="form-control">{{ old(
                                  'remarks',
                                  $income?->remarks
                              ) }}</textarea>

                </div>

            </div>


            <div class="d-flex justify-content-end gap-2 mt-4">

                <a href="{{ route(
                    'admin.assets.incomes.index',
                    $asset->id
                ) }}"
                   class="btn btn-secondary">

                    Cancel

                </a>

                <button class="btn btn-success">

                    <i class="fas fa-save"></i>

                    {{ $method === 'POST'
                        ? 'Add Income'
                        : 'Update Income'
                    }}

                </button>

            </div>

        </form>

    </div>

</div>