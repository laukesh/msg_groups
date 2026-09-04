@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small mb-1">
                Project / Funding Plan
            </div>

            <h3 class="mb-1">
                Create Funding Plan
            </h3>

            <div class="text-muted">
                {{ $project->project_name }}

                @if($project->project_number)
                    · {{ $project->project_number }}
                @endif
            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.funding-plan.index',
                ['project' => $project->id]
            ) }}"
            class="btn btn-outline-secondary"
        >
            ← Back to Funding Plans
        </a>

    </div>


    {{-- Validation --}}
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


    {{-- No Approved Budget --}}
    @if(!$approvedBudget)

        <div class="alert alert-warning">

            <strong>
                No approved budget is available.
            </strong>

            <div class="mt-1">
                A Funding Plan must be based on an approved
                Project Budget.
            </div>

        </div>


        <div>

            <a
                href="{{ route(
                    'admin.projects.budget.index',
                    ['project' => $project->id]
                ) }}"
                class="btn btn-primary"
            >
                Go to Project Budget
            </a>

        </div>

    @else


        <form
            method="POST"
            action="{{ route(
                'admin.projects.funding-plan.store',
                ['project' => $project->id]
            ) }}"
        >

            @csrf


            {{-- ================================================= --}}
            {{-- Funding Plan Information --}}
            {{-- ================================================= --}}

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Funding Plan Information</strong>
                </div>


                <div class="card-body">

                    <div class="row">

                        {{-- Title --}}

                        <div class="col-md-8 mb-3">

                            <label
                                for="title"
                                class="form-label"
                            >
                                Funding Plan Title
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="title"
                                id="title"
                                class="form-control @error('title') is-invalid @enderror"
                                value="{{ old(
                                    'title',
                                    'Project Funding Plan'
                                ) }}"
                                required
                            >

                            @error('title')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Currency --}}

                        <div class="col-md-4 mb-3">

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
                                    {{ old(
                                        'currency',
                                        $approvedBudget->currency
                                    ) === 'INR'
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

                    </div>


                    <div class="row">

                        {{-- Basis Budget --}}

                        <div class="col-md-8 mb-3">

                            <label
                                for="basis_budget_id"
                                class="form-label"
                            >
                                Basis Budget
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="basis_budget_id"
                                id="basis_budget_id"
                                class="form-select"
                                required
                            >

                                <option
                                    value="{{ $approvedBudget->id }}"
                                >
                                    {{ $approvedBudget->budget_number }}
                                    -
                                    {{ $approvedBudget->title }}
                                    -
                                    V{{ $approvedBudget->version_number }}
                                </option>

                            </select>

                            <div class="form-text">
                                Funding requirement is taken from
                                the approved budget.
                            </div>

                        </div>


                        {{-- Effective Date --}}

                        <div class="col-md-4 mb-3">

                            <label
                                for="effective_date"
                                class="form-label"
                            >
                                Effective Date
                            </label>

                            <input
                                type="date"
                                name="effective_date"
                                id="effective_date"
                                class="form-control @error('effective_date') is-invalid @enderror"
                                value="{{ old(
                                    'effective_date'
                                ) }}"
                            >

                            @error('effective_date')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- Budget Basis --}}
            {{-- ================================================= --}}

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Funding Requirement</strong>
                </div>


                <div class="card-body">

                    <div class="row">

                        <div class="col-md-4">

                            <div class="border rounded p-3">

                                <div class="text-muted small">
                                    Basis Budget
                                </div>

                                <div class="fw-semibold">
                                    {{ $approvedBudget->budget_number }}
                                </div>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="border rounded p-3">

                                <div class="text-muted small">
                                    Budget Version
                                </div>

                                <div class="fw-semibold">
                                    V{{ $approvedBudget->version_number }}
                                </div>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="border rounded p-3">

                                <div class="text-muted small">
                                    Total Funding Requirement
                                </div>

                                <div class="fs-5 fw-semibold">

                                    {{ $approvedBudget->currency }}

                                    {{
                                        number_format(
                                            $approvedBudget->total_budget,
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
            {{-- Initial Funding Position --}}
            {{-- ================================================= --}}

            <div class="card mb-4">

                <div class="card-header">
                    <strong>Initial Funding Position</strong>
                </div>


                <div class="card-body">

                    <div class="alert alert-info mb-0">

                        No funding sources have been added yet.

                        <br>

                        After creating this Funding Plan, you can
                        add funding sources, commitments and
                        funding tranches.

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
                        placeholder="Enter any funding plan remarks..."
                    >{{ old('remarks') }}</textarea>

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
                        'admin.projects.funding-plan.index',
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
                    Create Funding Plan
                </button>

            </div>


        </form>

    @endif

</div>

@endsection