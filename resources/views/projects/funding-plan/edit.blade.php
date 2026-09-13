@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small mb-1">
                Projects / Funding Plan
            </div>

            <h3 class="mb-1">
                Edit Funding Plan
            </h3>

            <div class="text-muted">
                {{ $project->project_name }}
                · {{ $fundingPlan->funding_plan_number }}
                · Version {{ $fundingPlan->version_number }}
            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.funding-plan.show',
                [
                    'project' => $project->id,
                    'fundingPlan' => $fundingPlan->id,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >
            ← Back
        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following:</strong>

            <ul class="mb-0 mt-2">

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
            'admin.projects.funding-plan.update',
            [
                'project' => $project->id,
                'fundingPlan' => $fundingPlan->id,
            ]
        ) }}"
    >

        @csrf
        @method('PUT')

        <input
            type="hidden"
            name="basis_budget_id"
            value="{{ $fundingPlan->basis_budget_id }}"
        >


        {{-- ===================================================== --}}
        {{-- Basic Information --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Funding Plan Information</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Funding Plan Number
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $fundingPlan->funding_plan_number }}"
                            readonly
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Version
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="V{{ $fundingPlan->version_number }}"
                            readonly
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Status
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $fundingPlan->status }}"
                            readonly
                        >

                    </div>

                </div>


                <div class="row">

                    <div class="col-md-8 mb-3">

                        <label
                            for="title"
                            class="form-label"
                        >
                            Title
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="title"
                            id="title"
                            class="form-control @error('title') is-invalid @enderror"
                            value="{{ old(
                                'title',
                                $fundingPlan->title
                            ) }}"
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
                            for="currency"
                            class="form-label"
                        >
                            Currency
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="currency"
                            id="currency"
                            class="form-control @error('currency') is-invalid @enderror"
                            value="{{ old(
                                'currency',
                                $fundingPlan->currency
                            ) }}"
                            maxlength="10"
                            required
                        >

                        @error('currency')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label
                            for="total_funding_requirement"
                            class="form-label"
                        >
                            Total Funding Requirement
                        </label>

                        <input
                            type="number"
                            name="total_funding_requirement"
                            id="total_funding_requirement"
                            class="form-control"
                            value="{{ old(
                                'total_funding_requirement',
                                $fundingPlan->total_funding_requirement
                            ) }}"
                            min="0"
                            step="0.01"
                        >

                    </div>


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
                            class="form-control"
                            value="{{ old(
                                'effective_date',
                                optional(
                                    $fundingPlan->effective_date
                                )->format('Y-m-d')
                            ) }}"
                        >

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
                    rows="5"
                    class="form-control @error('remarks') is-invalid @enderror"
                    placeholder="Enter remarks..."
                >{{ old(
                    'remarks',
                    $fundingPlan->remarks
                ) }}</textarea>

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
                    'admin.projects.funding-plan.show',
                    [
                        'project' => $project->id,
                        'fundingPlan' => $fundingPlan->id,
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
                Save Funding Plan
            </button>

        </div>

    </form>

</div>

@endsection