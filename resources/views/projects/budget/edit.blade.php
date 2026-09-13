@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small mb-1">
                Project / Budget
            </div>

            <h3 class="mb-1">
                Edit Project Budget
            </h3>

            <div class="text-muted">
                {{ $project->project_name }}
                ·
                {{ $project->project_number }}
            </div>

            <div class="text-muted small mt-1">
                {{ $projectBudget->budget_number }}
                ·
                Version V{{ $projectBudget->version_number }}
            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.budget.show',
                [
                    'project' => $project->id,
                    'projectBudget' => $projectBudget->id,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >
            ← Back to Budget
        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- Messages --}}
    {{-- ========================================================= --}}

    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following errors:
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


    {{-- ========================================================= --}}
    {{-- Approved Budget Protection --}}
    {{-- ========================================================= --}}

    @if($projectBudget->status === 'Approved')

        <div class="alert alert-warning">

            <strong>
                This budget is approved.
            </strong>

            Approved budgets cannot be edited.

            Create a new revision instead.

        </div>


        <div class="text-end">

            <form
                method="POST"
                action="{{ route(
                    'admin.projects.budget.revision',
                    [
                        'project' => $project->id,
                        'projectBudget' => $projectBudget->id,
                    ]
                ) }}"
                class="d-inline"
                onsubmit="return confirm(
                    'Create a new revision from this approved budget?'
                );"
            >

                @csrf

                <button
                    type="submit"
                    class="btn btn-warning"
                >
                    Create Revision
                </button>

            </form>

        </div>

    @else


        {{-- ===================================================== --}}
        {{-- Edit Form --}}
        {{-- ===================================================== --}}

        <form
            method="POST"
            action="{{ route(
                'admin.projects.budget.update',
                [
                    'project' => $project->id,
                    'projectBudget' => $projectBudget->id,
                ]
            ) }}"
        >

            @csrf

            @method('PUT')


            {{-- ================================================= --}}
            {{-- Budget Identification --}}
            {{-- ================================================= --}}

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Budget Identification</strong>
                </div>

                <div class="card-body">

                    <div class="row">

                        {{-- Budget Number --}}

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Budget Number
                            </label>

                            <input
                                type="text"
                                class="form-control bg-light"
                                value="{{ $projectBudget->budget_number }}"
                                readonly
                            >

                        </div>


                        {{-- Version --}}

                        <div class="col-md-2 mb-3">

                            <label class="form-label">
                                Version
                            </label>

                            <input
                                type="text"
                                class="form-control bg-light"
                                value="V{{ $projectBudget->version_number }}"
                                readonly
                            >

                        </div>


                        {{-- Status --}}

                        <div class="col-md-3 mb-3">

                            <label
                                for="status"
                                class="form-label"
                            >
                                Status
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="status"
                                id="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required
                            >

                                @foreach([
                                    'Draft',
                                    'Under Review',
                                    'Submitted',
                                    'Approved',
                                    'Rejected'
                                ] as $status)

                                    <option
                                        value="{{ $status }}"
                                        {{ old(
                                            'status',
                                            $projectBudget->status
                                        ) === $status
                                            ? 'selected'
                                            : ''
                                        }}
                                    >
                                        {{ $status }}
                                    </option>

                                @endforeach

                            </select>

                            @error('status')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Currency --}}

                        <div class="col-md-3 mb-3">

                            <label
                                for="currency"
                                class="form-label"
                            >
                                Currency
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="currency"
                                id="currency"
                                class="form-select @error('currency') is-invalid @enderror"
                                required
                            >

                                @foreach([
                                    'INR' => 'INR - Indian Rupee',
                                    'USD' => 'USD - US Dollar',
                                    'EUR' => 'EUR - Euro',
                                ] as $code => $label)

                                    <option
                                        value="{{ $code }}"
                                        {{ old(
                                            'currency',
                                            $projectBudget->currency
                                        ) === $code
                                            ? 'selected'
                                            : ''
                                        }}
                                    >
                                        {{ $label }}
                                    </option>

                                @endforeach

                            </select>

                            @error('currency')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    <div class="row">

                        {{-- Title --}}

                        <div class="col-md-8 mb-3">

                            <label
                                for="title"
                                class="form-label"
                            >
                                Budget Title
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="title"
                                id="title"
                                class="form-control @error('title') is-invalid @enderror"
                                value="{{ old(
                                    'title',
                                    $projectBudget->title
                                ) }}"
                                required
                            >

                            @error('title')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Type --}}

                        <div class="col-md-4 mb-3">

                            <label
                                for="budget_type"
                                class="form-label"
                            >
                                Budget Type
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="budget_type"
                                id="budget_type"
                                class="form-select @error('budget_type') is-invalid @enderror"
                                required
                            >

                                @foreach([
                                    'Project Budget',
                                    'Initial Budget',
                                    'Revised Budget',
                                    'Approved Budget',
                                    'Forecast Budget'
                                ] as $type)

                                    <option
                                        value="{{ $type }}"
                                        {{ old(
                                            'budget_type',
                                            $projectBudget->budget_type
                                        ) === $type
                                            ? 'selected'
                                            : ''
                                        }}
                                    >
                                        {{ $type }}
                                    </option>

                                @endforeach

                            </select>

                            @error('budget_type')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- Budget Period --}}
            {{-- ================================================= --}}

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Budget Period</strong>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label
                                for="budget_start_date"
                                class="form-label"
                            >
                                Start Date
                            </label>

                            <input
                                type="date"
                                name="budget_start_date"
                                id="budget_start_date"
                                class="form-control @error('budget_start_date') is-invalid @enderror"
                                value="{{ old(
                                    'budget_start_date',
                                    optional(
                                        $projectBudget->budget_start_date
                                    )->format('Y-m-d')
                                ) }}"
                            >

                            @error('budget_start_date')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <div class="col-md-6 mb-3">

                            <label
                                for="budget_end_date"
                                class="form-label"
                            >
                                End Date
                            </label>

                            <input
                                type="date"
                                name="budget_end_date"
                                id="budget_end_date"
                                class="form-control @error('budget_end_date') is-invalid @enderror"
                                value="{{ old(
                                    'budget_end_date',
                                    optional(
                                        $projectBudget->budget_end_date
                                    )->format('Y-m-d')
                                ) }}"
                            >

                            @error('budget_end_date')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- Financial Summary --}}
            {{-- ================================================= --}}

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Budget Summary</strong>
                </div>

                <div class="card-body">

                    <div class="alert alert-info small">

                        These values are normally calculated from
                        budget line items. If line items exist,
                        their calculated totals should be treated
                        as authoritative.

                    </div>


                    <div class="row">

                        <div class="col-md-4 mb-3">

                            <label
                                for="direct_cost"
                                class="form-label"
                            >
                                Direct Cost
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    {{ $projectBudget->currency }}
                                </span>

                                <input
                                    type="number"
                                    name="direct_cost"
                                    id="direct_cost"
                                    class="form-control @error('direct_cost') is-invalid @enderror"
                                    value="{{ old(
                                        'direct_cost',
                                        $projectBudget->direct_cost
                                    ) }}"
                                    min="0"
                                    step="0.01"
                                >

                            </div>

                            @error('direct_cost')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <div class="col-md-4 mb-3">

                            <label
                                for="indirect_cost"
                                class="form-label"
                            >
                                Indirect Cost
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    {{ $projectBudget->currency }}
                                </span>

                                <input
                                    type="number"
                                    name="indirect_cost"
                                    id="indirect_cost"
                                    class="form-control @error('indirect_cost') is-invalid @enderror"
                                    value="{{ old(
                                        'indirect_cost',
                                        $projectBudget->indirect_cost
                                    ) }}"
                                    min="0"
                                    step="0.01"
                                >

                            </div>

                            @error('indirect_cost')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <div class="col-md-4 mb-3">

                            <label
                                for="contingency_amount"
                                class="form-label"
                            >
                                Contingency
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    {{ $projectBudget->currency }}
                                </span>

                                <input
                                    type="number"
                                    name="contingency_amount"
                                    id="contingency_amount"
                                    class="form-control @error('contingency_amount') is-invalid @enderror"
                                    value="{{ old(
                                        'contingency_amount',
                                        $projectBudget->contingency_amount
                                    ) }}"
                                    min="0"
                                    step="0.01"
                                >

                            </div>

                            @error('contingency_amount')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-4 offset-md-8">

                            <div class="border rounded p-3">

                                <div class="text-muted small">
                                    Total Budget
                                </div>

                                <div
                                    class="fs-4 fw-semibold"
                                    id="total_budget_preview"
                                >
                                    {{ $projectBudget->currency }}
                                    {{
                                        number_format(
                                            $projectBudget->total_budget,
                                            2
                                        )
                                    }}
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- Approval --}}
            {{-- ================================================= --}}

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Approval Information</strong>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label
                                for="approved_date"
                                class="form-label"
                            >
                                Approved Date
                            </label>

                            <input
                                type="date"
                                name="approved_date"
                                id="approved_date"
                                class="form-control @error('approved_date') is-invalid @enderror"
                                value="{{ old(
                                    'approved_date',
                                    optional(
                                        $projectBudget->approved_date
                                    )->format('Y-m-d')
                                ) }}"
                            >

                            @error('approved_date')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <div class="col-md-6 mb-3">

                            <label
                                for="approved_by"
                                class="form-label"
                            >
                                Approved By ID
                            </label>

                            <input
                                type="number"
                                name="approved_by"
                                id="approved_by"
                                class="form-control @error('approved_by') is-invalid @enderror"
                                value="{{ old(
                                    'approved_by',
                                    $projectBudget->approved_by
                                ) }}"
                            >

                            @error('approved_by')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- Remarks --}}
            {{-- ================================================= --}}

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Remarks</strong>
                </div>

                <div class="card-body">

                    <textarea
                        name="remarks"
                        id="remarks"
                        rows="4"
                        class="form-control @error('remarks') is-invalid @enderror"
                    >{{ old(
                        'remarks',
                        $projectBudget->remarks
                    ) }}</textarea>

                    @error('remarks')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- Actions --}}
            {{-- ================================================= --}}

            <div class="d-flex justify-content-end gap-2 mb-5">

                <a
                    href="{{ route(
                        'admin.projects.budget.show',
                        [
                            'project' =>
                                $project->id,

                            'projectBudget' =>
                                $projectBudget->id,
                        ]
                    ) }}"
                    class="btn btn-outline-secondary"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Update Budget
                </button>

            </div>


        </form>

    @endif

</div>


{{-- ============================================================= --}}
{{-- Total Preview --}}
{{-- ============================================================= --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const direct =
            document.getElementById(
                'direct_cost'
            );

        const indirect =
            document.getElementById(
                'indirect_cost'
            );

        const contingency =
            document.getElementById(
                'contingency_amount'
            );

        const currency =
            document.getElementById(
                'currency'
            );

        const preview =
            document.getElementById(
                'total_budget_preview'
            );


        function calculateTotal()
        {
            if (
                !direct ||
                !indirect ||
                !contingency ||
                !preview
            ) {
                return;
            }


            const directValue =
                parseFloat(direct.value) || 0;

            const indirectValue =
                parseFloat(indirect.value) || 0;

            const contingencyValue =
                parseFloat(
                    contingency.value
                ) || 0;


            const total =
                directValue +
                indirectValue +
                contingencyValue;


            const currencyValue =
                currency
                    ? currency.value
                    : '{{ $projectBudget->currency }}';


            preview.textContent =
                currencyValue +
                ' ' +
                total.toLocaleString(
                    'en-IN',
                    {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }
                );
        }


        if (direct) {
            direct.addEventListener(
                'input',
                calculateTotal
            );
        }

        if (indirect) {
            indirect.addEventListener(
                'input',
                calculateTotal
            );
        }

        if (contingency) {
            contingency.addEventListener(
                'input',
                calculateTotal
            );
        }

        if (currency) {
            currency.addEventListener(
                'change',
                calculateTotal
            );
        }


        calculateTotal();

    }
);

</script>

@endsection