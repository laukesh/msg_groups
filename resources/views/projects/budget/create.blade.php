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
                Create Project Budget
            </h3>

            <div class="text-muted">
                {{ $project->project_name }}
                · {{ $project->project_number }}
            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.budget.index',
                ['project' => $project->id]
            ) }}"
            class="btn btn-outline-secondary"
        >
            ← Back
        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- Validation --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route(
            'admin.projects.budget.store',
            ['project' => $project->id]
        ) }}"
    >

        @csrf


        {{-- ===================================================== --}}
        {{-- Project Context --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Project Context</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">

                        <label class="form-label">
                            Project
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $project->project_name }}"
                            readonly
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Project Number
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $project->project_number }}"
                            readonly
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Budget Version
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="V{{ $nextVersion }}"
                            readonly
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Budget Identification --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Budget Identification</strong>
            </div>

            <div class="card-body">

                <div class="alert alert-info small">

                    Budget Number and Version are generated
                    automatically by the system.

                </div>


                <div class="row">

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
                            value="{{ old('title') }}"
                            placeholder="e.g. Initial Project Development Budget"
                            required
                        >

                        @error('title')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


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
                                        'Project Budget'
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


                <div class="row">

                    <div class="col-md-6 mb-3">

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

                            <option
                                value="INR"
                                {{ old('currency', 'INR') === 'INR'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                INR - Indian Rupee
                            </option>

                            <option
                                value="USD"
                                {{ old('currency') === 'USD'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                USD - US Dollar
                            </option>

                            <option
                                value="EUR"
                                {{ old('currency') === 'EUR'
                                    ? 'selected'
                                    : ''
                                }}
                            >
                                EUR - Euro
                            </option>

                        </select>

                        @error('currency')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="col-md-6 mb-3">

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
                                        'Draft'
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

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Budget Period --}}
        {{-- ===================================================== --}}

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
                            Budget Start Date
                        </label>

                        <input
                            type="date"
                            name="budget_start_date"
                            id="budget_start_date"
                            class="form-control @error('budget_start_date') is-invalid @enderror"
                            value="{{ old('budget_start_date') }}"
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
                            Budget End Date
                        </label>

                        <input
                            type="date"
                            name="budget_end_date"
                            id="budget_end_date"
                            class="form-control @error('budget_end_date') is-invalid @enderror"
                            value="{{ old('budget_end_date') }}"
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


        {{-- ===================================================== --}}
        {{-- Initial Budget Summary --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Initial Budget Summary</strong>
            </div>

            <div class="card-body">

                <div class="alert alert-secondary small">

                    These values provide the initial budget summary.
                    Detailed category and line-item costs can be
                    entered after the budget is created.

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

                            <span
                                class="input-group-text"
                                id="currency_direct"
                            >
                                USD
                            </span>

                            <input
                                type="number"
                                name="direct_cost"
                                id="direct_cost"
                                class="form-control @error('direct_cost') is-invalid @enderror"
                                value="{{ old(
                                    'direct_cost',
                                    0
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

                            <span
                                class="input-group-text"
                                id="currency_indirect"
                            >
                                USD
                            </span>

                            <input
                                type="number"
                                name="indirect_cost"
                                id="indirect_cost"
                                class="form-control @error('indirect_cost') is-invalid @enderror"
                                value="{{ old(
                                    'indirect_cost',
                                    0
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

                            <span
                                class="input-group-text"
                                id="currency_contingency"
                            >
                                USD
                            </span>

                            <input
                                type="number"
                                name="contingency_amount"
                                id="contingency_amount"
                                class="form-control @error('contingency_amount') is-invalid @enderror"
                                value="{{ old(
                                    'contingency_amount',
                                    0
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


                {{-- Total Preview --}}

                <div class="row mt-2">

                    <div class="col-md-4 offset-md-8">

                        <div class="border rounded p-3">

                            <div class="text-muted small">
                                Total Budget
                            </div>

                            <div
                                class="fs-4 fw-semibold"
                                id="total_budget_preview"
                            >
                                USD 0.00
                            </div>

                            <div class="text-muted small mt-1">
                                Automatically calculated
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Approval --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Approval Information</strong>
            </div>

            <div class="card-body">

                <div class="alert alert-warning small">

                    Approval information should normally be completed
                    only when the budget reaches the Approved stage.

                </div>


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
                            value="{{ old('approved_date') }}"
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
                            value="{{ old('approved_by') }}"
                        >

                        <div class="form-text">
                            Will later be connected to the
                            project's user/employee master.
                        </div>

                        @error('approved_by')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Remarks --}}
        {{-- ===================================================== --}}

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
                    placeholder="Budget remarks"
                >{{ old('remarks') }}</textarea>

                @error('remarks')

                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>

                @enderror

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Actions --}}
        {{-- ===================================================== --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route(
                    'admin.projects.budget.index',
                    ['project' => $project->id]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Cancel
            </a>


            <button
                type="submit"
                class="btn btn-primary"
            >
                Create Budget
            </button>

        </div>

    </form>

</div>


{{-- ============================================================= --}}
{{-- Total Calculation --}}
{{-- ============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const directCost =
        document.getElementById('direct_cost');

    const indirectCost =
        document.getElementById('indirect_cost');

    const contingency =
        document.getElementById('contingency_amount');

    const totalPreview =
        document.getElementById('total_budget_preview');

    const currency =
        document.getElementById('currency');


    function calculateTotal()
    {
        const direct =
            parseFloat(directCost.value) || 0;

        const indirect =
            parseFloat(indirectCost.value) || 0;

        const contingencyValue =
            parseFloat(contingency.value) || 0;


        const total =
            direct +
            indirect +
            contingencyValue;


        totalPreview.textContent =
            currency.value +
            ' ' +
            total.toLocaleString(
                'en-IN',
                {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }
            );
    }


    directCost.addEventListener(
        'input',
        calculateTotal
    );

    indirectCost.addEventListener(
        'input',
        calculateTotal
    );

    contingency.addEventListener(
        'input',
        calculateTotal
    );

    currency.addEventListener(
        'change',
        calculateTotal
    );


    calculateTotal();

});

</script>

@endsection