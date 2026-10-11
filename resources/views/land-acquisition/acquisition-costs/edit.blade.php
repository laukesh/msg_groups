@extends('layouts.app')

@section('content')

<div class="container-fluid">


    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Edit Acquisition Cost
            </h3>

            <p class="text-muted mb-0">

                {{ $land->land_code }}
                -
                {{ $land->land_name }}

            </p>

        </div>


        <a
            href="{{ route(
                'admin.land.lands.acquisition-costs.show',
                [
                    $land,
                    $acquisitionCost
                ]
            ) }}"
            class="btn btn-outline-primary">

            View

        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route(
            'admin.land.lands.acquisition-costs.update',
            [
                $land,
                $acquisitionCost
            ]
        ) }}">

        @csrf

        @method('PUT')


        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Cost Information
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Development Budget Allocation --}}
                    <div class="row mb-3">

                        <div class="col-md-6">
                            <label for="project_budget_id" class="form-label">
                                Development Budget
                            </label>

                            @if(isset($project) && $project && $budgets->count() > 0)

                                <select
                                    name="project_budget_id"
                                    id="project_budget_id"
                                    class="form-select @error('project_budget_id') is-invalid @enderror"
                                >
                                    <option value="">-- Select Development Budget --</option>

                                    @foreach($budgets as $budget)
                                        <option
                                            value="{{ $budget->id }}"
                                            {{ old('project_budget_id', $acquisitionCost->project_budget_id) == $budget->id ? 'selected' : '' }}
                                        >
                                            {{ $budget->title }}

                                            @if($budget->budget_number)
                                                ({{ $budget->budget_number }})
                                            @endif

                                            - {{ $budget->currency }}
                                            {{ number_format($budget->total_budget, 2) }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('project_budget_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            @elseif(isset($project) && $project)

                                <select class="form-select" disabled>
                                    <option>No approved development budget available</option>
                                </select>

                                <small class="text-muted">
                                    No approved budget is available for this project.
                                </small>

                            @else

                                <select class="form-select" disabled>
                                    <option>Project not linked with this land</option>
                                </select>

                                <small class="text-muted">
                                    Convert an investment decision into a project before allocating a budget.
                                </small>

                            @endif
                        </div>


                        <div class="col-md-6">
                            <label for="project_budget_item_id" class="form-label">
                                Budget Item
                            </label>

                            <select
                                name="project_budget_item_id"
                                id="project_budget_item_id"
                                class="form-select @error('project_budget_item_id') is-invalid @enderror"
                            >
                                <option value="">-- Select Budget Item --</option>

                                @foreach($budgets as $budget)
                                    @foreach($budget->items as $item)

                                        <option
                                            value="{{ $item->id }}"
                                            data-budget-id="{{ $budget->id }}"
                                            {{ old('project_budget_item_id', $acquisitionCost->project_budget_item_id) == $item->id ? 'selected' : '' }}
                                        >
                                            {{ $item->item_code ? $item->item_code . ' - ' : '' }}
                                            {{ $item->item_name }}
                                            | {{ $budget->currency }}
                                            {{ number_format($item->estimated_amount, 2) }}
                                        </option>

                                    @endforeach
                                @endforeach
                            </select>

                            @error('project_budget_item_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <small class="text-muted">
                                Select a budget first to filter the available budget items.
                            </small>
                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Cost Category *
                        </label>

                        <select
                            name="cost_category"
                            class="form-select"
                            required>

                            @foreach([
                                'Land Purchase Price',
                                'Stamp Duty',
                                'Registration Charges',
                                'Legal Fees',
                                'Brokerage',
                                'Due Diligence Cost',
                                'Survey Cost',
                                'Consultancy Cost',
                                'Other'
                            ] as $category)

                                <option
                                    value="{{ $category }}"
                                    @selected(
                                        old(
                                            'cost_category',
                                            $acquisitionCost
                                                ->cost_category
                                        ) === $category
                                    )>

                                    {{ $category }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-8 mb-3">

                        <label class="form-label">
                            Description
                        </label>

                        <input
                            type="text"
                            name="cost_description"
                            class="form-control"
                            value="{{ old(
                                'cost_description',
                                $acquisitionCost
                                    ->cost_description
                            ) }}"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Amount *
                        </label>

                        <input
                            type="number"
                            name="amount"
                            class="form-control"
                            step="0.01"
                            min="0"
                            value="{{ old(
                                'amount',
                                $acquisitionCost->amount
                            ) }}"
                            required
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Tax Amount
                        </label>

                        <input
                            type="number"
                            name="tax_amount"
                            class="form-control"
                            step="0.01"
                            min="0"
                            value="{{ old(
                                'tax_amount',
                                $acquisitionCost->tax_amount
                            ) }}"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Currency *
                        </label>

                        <select
                            name="currency"
                            class="form-select"
                            required>

                            <option
                                value="INR"
                                @selected(
                                    old(
                                        'currency',
                                        $acquisitionCost->currency
                                    ) === 'INR'
                                )>

                                INR - Indian Rupee

                            </option>

                            <option
                                value="USD"
                                @selected(
                                    old(
                                        'currency',
                                        $acquisitionCost->currency
                                    ) === 'USD'
                                )>

                                USD - US Dollar

                            </option>

                        </select>

                    </div>

                </div>

            </div>

        </div>


        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Payment Information
                </strong>

            </div>


            <div class="card-body">

                <div class="row">


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Cost Date
                        </label>

                        <input
                            type="date"
                            name="cost_date"
                            class="form-control"
                            value="{{ old(
                                'cost_date',
                                $acquisitionCost->cost_date
                                    ? $acquisitionCost
                                        ->cost_date
                                        ->format('Y-m-d')
                                    : ''
                            ) }}"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Payment Status *
                        </label>

                        <select
                            name="payment_status"
                            class="form-select"
                            required>

                            @foreach([
                                'Pending',
                                'Partially Paid',
                                'Paid'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        old(
                                            'payment_status',
                                            $acquisitionCost
                                                ->payment_status
                                        ) === $status
                                    )>

                                    {{ $status }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Paid Date
                        </label>

                        <input
                            type="date"
                            name="paid_date"
                            class="form-control"
                            value="{{ old(
                                'paid_date',
                                $acquisitionCost->paid_date
                                    ? $acquisitionCost
                                        ->paid_date
                                        ->format('Y-m-d')
                                    : ''
                            ) }}"
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Reference Number
                        </label>

                        <input
                            type="text"
                            name="reference_number"
                            class="form-control"
                            value="{{ old(
                                'reference_number',
                                $acquisitionCost
                                    ->reference_number
                            ) }}"
                        >

                    </div>

                </div>

            </div>

        </div>


        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Remarks
                </strong>

            </div>


            <div class="card-body">

                <textarea
                    name="remarks"
                    rows="4"
                    class="form-control"
                >{{ old(
                    'remarks',
                    $acquisitionCost->remarks
                ) }}</textarea>

            </div>

        </div>


        <div class="d-flex justify-content-end">

            <a
                href="{{ route(
                    'admin.land.lands.show',
                    $land
                ) }}"
                class="btn btn-secondary me-2">

                Cancel

            </a>


            <button
                type="submit"
                class="btn btn-primary">

                Update Cost

            </button>

        </div>

    </form>

</div>

<script>
    $(document).ready(function () {

        const budgetSelect = $('#project_budget_id');
        const budgetItemSelect = $('#project_budget_item_id');

        const initialBudgetItemId = @json(
            old('project_budget_item_id', $acquisitionCost->project_budget_item_id)
        );

        function filterBudgetItems(clearInvalidSelection = false) {

            const selectedBudgetId = budgetSelect.val();

            budgetItemSelect.find('option').each(function () {

                const option = $(this);
                const optionBudgetId = option.data('budget-id');

                if (!option.val()) {
                    option.show();
                    return;
                }

                const belongsToSelectedBudget =
                    selectedBudgetId &&
                    String(optionBudgetId) === String(selectedBudgetId);

                if (belongsToSelectedBudget) {
                    option.show();
                } else {
                    option.hide();

                    if (option.is(':selected')) {
                        option.prop('selected', false);
                    }
                }
            });

            if (!selectedBudgetId) {
                budgetItemSelect.val('');
            }
        }

        budgetSelect.on('change', function () {
            filterBudgetItems(true);
        });

        filterBudgetItems();

    });
</script>

@endsection