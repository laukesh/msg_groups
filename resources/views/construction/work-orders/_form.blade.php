<div class="row g-3">

    <div class="col-md-6">

        <label class="form-label">
            Contract
            <span class="text-danger">*</span>
        </label>

        <select
            name="procurement_contract_id"
            class="form-select"
            required
        >

            <option value="">
                Select Contract
            </option>

            @foreach($contracts as $contract)

                <option
                    value="{{ $contract->id }}"
                    @selected(
                        old(
                            'procurement_contract_id'
                        ) == $contract->id
                    )
                >

                    {{ $contract->contract_number }}

                    —
                    {{
                        $contract->bidder
                            ?->company_name
                        ??
                        $contract->bidder_name
                        ??
                        'Contractor'
                    }}

                </option>

            @endforeach

        </select>

        <div class="form-text">
            Only contracts belonging to this project are shown.
        </div>

    </div>

    {{-- Development Budget Link --}}

    <div class="col-md-6">

        <label class="form-label">
            Development Budget
        </label>

        <select
            name="project_budget_id"
            id="project_budget_id"
            class="form-select"
        >

            <option value="">
                Select Development Budget
            </option>

            @foreach($budgets as $budget)

                <option
                    value="{{ $budget->id }}"
                    @selected(
                        old('project_budget_id') == $budget->id
                    )
                >
                    {{ $budget->budget_number ?? ('Budget #' . $budget->id) }}
                    —
                    {{ $budget->budget_name ?? $budget->name ?? 'Development Budget' }}
                </option>

            @endforeach

        </select>

        <div class="form-text">
            Only approved budgets belonging to this project are shown.
        </div>

    </div>


    <div class="col-md-6">

        <label class="form-label">
            Development Budget Item
        </label>

        <select
            name="project_budget_item_id"
            id="project_budget_item_id"
            class="form-select"
        >

            <option value="">
                Select Budget Item
            </option>

            @foreach($budgetItems as $item)

                <option
                    value="{{ $item->id }}"
                    data-budget-id="{{ $item->project_budget_id ?? $item->budget_id }}"
                    data-approved-amount="{{ $item->estimated_amount ?? 0 }}"
                    data-committed-amount="{{ $item->total_committed_amount ?? 0 }}"
                    @selected(
                        old('project_budget_item_id') == $item->id
                    )
                >

                    {{ $item->item_code ?? ('Item #' . $item->id) }}
                    —
                    {{ $item->item_name ?? $item->name ?? 'Budget Item' }}

                    @if($item->category)
                        — {{ $item->category->name }}
                    @endif

                </option>

            @endforeach

        </select>

        <div class="form-text">
            Select a budget item related to this work order.
        </div>

    </div>


    <div class="col-md-12">

        <div
            id="budget-utilization-preview"
            class="alert alert-info d-none"
        >

            <strong>
                Budget Utilization Preview
            </strong>

            <div class="row mt-2">

                <div class="col-md-4">
                    <div class="small text-muted">
                        Approved Amount
                    </div>

                    <div id="budget-approved-amount" class="fw-semibold">
                        ₹0.00
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="small text-muted">
                        Already Committed
                    </div>

                    <div id="budget-committed-amount" class="fw-semibold">
                        ₹0.00
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="small text-muted">
                        Remaining Amount
                    </div>

                    <div id="budget-remaining-amount" class="fw-semibold">
                        ₹0.00
                    </div>
                </div>

            </div>

        </div>

    </div>


    <div class="col-md-6">

        <label class="form-label">
            Work Order Title
            <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="work_order_title"
            class="form-control"
            required
            value="{{ old('work_order_title') }}"
        >

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Work Order Type
        </label>

        <select
            name="work_order_type"
            class="form-select"
        >

            <option value="">
                Select Type
            </option>

            @foreach([
                'Main Work',
                'Civil Work',
                'Electrical Work',
                'MEP Work',
                'Finishing Work',
                'Sub Work',
                'Variation Work',
                'Other',
            ] as $type)

                <option
                    value="{{ $type }}"
                    @selected(
                        old('work_order_type') === $type
                    )
                >
                    {{ $type }}
                </option>

            @endforeach

        </select>

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Priority
        </label>

        <select
            name="priority"
            class="form-select"
        >

            @foreach([
                'Low',
                'Normal',
                'High',
                'Critical',
            ] as $priority)

                <option
                    value="{{ $priority }}"
                    @selected(
                        old(
                            'priority',
                            'Normal'
                        ) === $priority
                    )
                >
                    {{ $priority }}
                </option>

            @endforeach

        </select>

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Status
        </label>

        <select
            name="status"
            class="form-select"
        >

            @foreach([
                'Draft',
                'Issued',
                'In Progress',
                'Completed',
                'Cancelled',
            ] as $status)

                <option
                    value="{{ $status }}"
                    @selected(
                        old(
                            'status',
                            'Draft'
                        ) === $status
                    )
                >
                    {{ $status }}
                </option>

            @endforeach

        </select>

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Issue Date
        </label>

        <input
            type="date"
            name="issue_date"
            class="form-control"
            value="{{ old('issue_date') }}"
        >

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Start Date
        </label>

        <input
            type="date"
            name="start_date"
            class="form-control"
            value="{{ old('start_date') }}"
        >

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Expected Completion
        </label>

        <input
            type="date"
            name="expected_completion_date"
            class="form-control"
            value="{{ old(
                'expected_completion_date'
            ) }}"
        >

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Work Order Value
        </label>

        <input
            type="number"
            step="0.01"
            min="0"
            name="work_order_value"
            class="form-control"
            value="{{ old(
                'work_order_value',
                0
            ) }}"
        >

    </div>


    <div class="col-md-2">

        <label class="form-label">
            Currency
        </label>

        <input
            type="text"
            name="currency"
            class="form-control"
            value="{{ old(
                'currency',
                'USD'
            ) }}"
        >

    </div>


    <div class="col-md-6">

        <label class="form-label">
            Assigned To
        </label>

        <select
            name="assigned_to"
            class="form-select"
        >

            <option value="">
                Select User
            </option>

            @foreach($users as $user)

                <option
                    value="{{ $user->id }}"
                    @selected(
                        old(
                            'assigned_to'
                        ) == $user->id
                    )
                >
                    {{ $user->name }}
                </option>

            @endforeach

        </select>

    </div>


    <div class="col-md-12">

        <label class="form-label">
            Scope of Work
        </label>

        <textarea
            name="scope_of_work"
            rows="5"
            class="form-control"
        >{{ old('scope_of_work') }}</textarea>

    </div>


    <div class="col-md-12">

        <label class="form-label">
            Remarks
        </label>

        <textarea
            name="remarks"
            rows="3"
            class="form-control"
        >{{ old('remarks') }}</textarea>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const budgetSelect = document.getElementById('project_budget_id');
    const budgetItemSelect = document.getElementById('project_budget_item_id');

    const preview = document.getElementById('budget-utilization-preview');
    const approvedAmount = document.getElementById('budget-approved-amount');
    const committedAmount = document.getElementById('budget-committed-amount');
    const remainingAmount = document.getElementById('budget-remaining-amount');

    const originalOptions = Array.from(
        budgetItemSelect.options
    );

    function formatAmount(amount) {
        return '₹' + Number(amount || 0).toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function filterBudgetItems() {

        const selectedBudgetId = budgetSelect.value;
        const selectedItemId = budgetItemSelect.value;

        budgetItemSelect.innerHTML = '';

        const defaultOption = document.createElement('option');

        defaultOption.value = '';
        defaultOption.textContent = 'Select Budget Item';

        budgetItemSelect.appendChild(defaultOption);

        originalOptions.forEach(function (option) {

            if (!option.value) {
                return;
            }

            const optionBudgetId =
                option.getAttribute('data-budget-id');

            if (
                !selectedBudgetId ||
                optionBudgetId === selectedBudgetId
            ) {
                budgetItemSelect.appendChild(
                    option.cloneNode(true)
                );
            }

        });

        if (
            selectedItemId &&
            Array.from(budgetItemSelect.options).some(
                option => option.value === selectedItemId
            )
        ) {
            budgetItemSelect.value = selectedItemId;
        }

        updatePreview();
    }

    function updatePreview() {

        const selectedOption =
            budgetItemSelect.options[
                budgetItemSelect.selectedIndex
            ];

        if (
            !selectedOption ||
            !selectedOption.value
        ) {
            preview.classList.add('d-none');
            return;
        }

        const approved =
            parseFloat(
                selectedOption.getAttribute(
                    'data-approved-amount'
                )
            ) || 0;

        const committed =
            parseFloat(
                selectedOption.getAttribute(
                    'data-committed-amount'
                )
            ) || 0;

        const remaining =
            approved - committed;

        approvedAmount.textContent =
            formatAmount(approved);

        committedAmount.textContent =
            formatAmount(committed);

        remainingAmount.textContent =
            formatAmount(remaining);

        preview.classList.remove('d-none');

        if (remaining < 0) {
            preview.classList.remove('alert-info');
            preview.classList.add('alert-danger');
        } else {
            preview.classList.remove('alert-danger');
            preview.classList.add('alert-info');
        }
    }

    budgetSelect.addEventListener(
        'change',
        filterBudgetItems
    );

    budgetItemSelect.addEventListener(
        'change',
        updatePreview
    );

    filterBudgetItems();

});
</script>
