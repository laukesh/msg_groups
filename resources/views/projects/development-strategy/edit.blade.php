@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small mb-1">
                Project / Development Strategy
            </div>

            <h3 class="mb-1">
                Edit Development Strategy
            </h3>

            <div class="text-muted">
                {{ $developmentStrategy->strategy_number }}
                · {{ $project->project_name }}
                · {{ $project->project_number }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.development-strategy.show',
                    [
                        'project' => $project->id,
                        'developmentStrategy' =>
                            $developmentStrategy->id,
                    ]
                ) }}"
                class="btn btn-outline-primary"
            >
                View Strategy
            </a>

            <a
                href="{{ route(
                    'admin.projects.development-strategy.index',
                    ['project' => $project->id]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Back
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Validation Errors --}}
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
            'admin.projects.development-strategy.update',
            [
                'project' => $project->id,
                'developmentStrategy' =>
                    $developmentStrategy->id,
            ]
        ) }}"
    >

        @csrf

        @method('PUT')


        {{-- ===================================================== --}}
        {{-- Project Context --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Project Context</strong>
            </div>

            <div class="card-body">

                <div class="alert alert-info small">

                    Project relationship is locked. This strategy
                    belongs to the current project and cannot be
                    reassigned from this form.

                </div>


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
                            Strategy Number
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $developmentStrategy->strategy_number }}"
                            readonly
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Strategy Identification --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Strategy Identification</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-8 mb-3">

                        <label
                            for="title"
                            class="form-label"
                        >
                            Strategy Title
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="title"
                            id="title"
                            class="form-control @error('title') is-invalid @enderror"
                            value="{{ old(
                                'title',
                                $developmentStrategy->title
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
                            for="strategy_date"
                            class="form-label"
                        >
                            Strategy Date
                        </label>

                        <input
                            type="date"
                            name="strategy_date"
                            id="strategy_date"
                            class="form-control @error('strategy_date') is-invalid @enderror"
                            value="{{ old(
                                'strategy_date',
                                optional(
                                    $developmentStrategy->strategy_date
                                )->format('Y-m-d')
                            ) }}"
                        >

                        @error('strategy_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Development Vision & Concept --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Development Vision & Concept</strong>
            </div>

            <div class="card-body">

                <div class="mb-3">

                    <label
                        for="development_vision"
                        class="form-label"
                    >
                        Development Vision
                    </label>

                    <textarea
                        name="development_vision"
                        id="development_vision"
                        rows="4"
                        class="form-control"
                    >{{ old(
                        'development_vision',
                        $developmentStrategy->development_vision
                    ) }}</textarea>

                </div>


                <div class="mb-3">

                    <label
                        for="development_concept"
                        class="form-label"
                    >
                        Development Concept
                    </label>

                    <textarea
                        name="development_concept"
                        id="development_concept"
                        rows="4"
                        class="form-control"
                    >{{ old(
                        'development_concept',
                        $developmentStrategy->development_concept
                    ) }}</textarea>

                </div>


                <div>

                    <label
                        for="development_objectives"
                        class="form-label"
                    >
                        Development Objectives
                    </label>

                    <textarea
                        name="development_objectives"
                        id="development_objectives"
                        rows="5"
                        class="form-control"
                    >{{ old(
                        'development_objectives',
                        $developmentStrategy->development_objectives
                    ) }}</textarea>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Development Approach --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Development Approach</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label
                            for="development_type"
                            class="form-label"
                        >
                            Development Type
                        </label>

                        <select
                            name="development_type"
                            id="development_type"
                            class="form-select"
                        >

                            <option value="">
                                -- Select --
                            </option>

                            @foreach([
                                'Commercial',
                                'Residential',
                                'Mixed Use',
                                'Retail',
                                'Hospitality',
                                'Industrial',
                                'Institutional',
                                'Other'
                            ] as $type)

                                <option
                                    value="{{ $type }}"
                                    {{ old(
                                        'development_type',
                                        $developmentStrategy->development_type
                                    ) === $type
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    {{ $type }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label
                            for="development_model"
                            class="form-label"
                        >
                            Development Model
                        </label>

                        <select
                            name="development_model"
                            id="development_model"
                            class="form-select"
                        >

                            <option value="">
                                -- Select --
                            </option>

                            @foreach([
                                'Self Development',
                                'Joint Venture',
                                'Development Partnership',
                                'Public Private Partnership',
                                'Lease Development',
                                'Other'
                            ] as $model)

                                <option
                                    value="{{ $model }}"
                                    {{ old(
                                        'development_model',
                                        $developmentStrategy->development_model
                                    ) === $model
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    {{ $model }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4 mb-3">

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
                            class="form-select"
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
                                        $developmentStrategy->status
                                    ) === $status
                                        ? 'selected'
                                        : ''
                                    }}
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                <div>

                    <label
                        for="development_approach"
                        class="form-label"
                    >
                        Development Approach
                    </label>

                    <textarea
                        name="development_approach"
                        id="development_approach"
                        rows="5"
                        class="form-control"
                    >{{ old(
                        'development_approach',
                        $developmentStrategy->development_approach
                    ) }}</textarea>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Market Positioning --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Market Positioning</strong>
            </div>

            <div class="card-body">

                <div class="mb-3">

                    <label
                        for="target_market"
                        class="form-label"
                    >
                        Target Market
                    </label>

                    <textarea
                        name="target_market"
                        id="target_market"
                        rows="4"
                        class="form-control"
                    >{{ old(
                        'target_market',
                        $developmentStrategy->target_market
                    ) }}</textarea>

                </div>


                <div class="mb-3">

                    <label
                        for="market_positioning"
                        class="form-label"
                    >
                        Market Positioning
                    </label>

                    <textarea
                        name="market_positioning"
                        id="market_positioning"
                        rows="4"
                        class="form-control"
                    >{{ old(
                        'market_positioning',
                        $developmentStrategy->market_positioning
                    ) }}</textarea>

                </div>


                <div>

                    <label
                        for="competitive_strategy"
                        class="form-label"
                    >
                        Competitive Strategy
                    </label>

                    <textarea
                        name="competitive_strategy"
                        id="competitive_strategy"
                        rows="4"
                        class="form-control"
                    >{{ old(
                        'competitive_strategy',
                        $developmentStrategy->competitive_strategy
                    ) }}</textarea>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Development Mix --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Development Mix & Area</strong>
            </div>

            <div class="card-body">

                <div class="mb-4">

                    <label
                        for="development_mix"
                        class="form-label"
                    >
                        Development Mix
                    </label>

                    <textarea
                        name="development_mix"
                        id="development_mix"
                        rows="4"
                        class="form-control"
                    >{{ old(
                        'development_mix',
                        $developmentStrategy->development_mix
                    ) }}</textarea>

                </div>


                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label
                            for="planned_gla"
                            class="form-label"
                        >
                            Planned GLA
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="planned_gla"
                            id="planned_gla"
                            class="form-control"
                            value="{{ old(
                                'planned_gla',
                                $developmentStrategy->planned_gla
                            ) }}"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label
                            for="planned_nla"
                            class="form-label"
                        >
                            Planned NLA
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="planned_nla"
                            id="planned_nla"
                            class="form-control"
                            value="{{ old(
                                'planned_nla',
                                $developmentStrategy->planned_nla
                            ) }}"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label
                            for="planned_leasable_area"
                            class="form-label"
                        >
                            Planned Leasable Area
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="planned_leasable_area"
                            id="planned_leasable_area"
                            class="form-control"
                            value="{{ old(
                                'planned_leasable_area',
                                $developmentStrategy->planned_leasable_area
                            ) }}"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Strategic Considerations --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Strategic Considerations</strong>
            </div>

            <div class="card-body">

                <div class="mb-3">

                    <label
                        for="key_assumptions"
                        class="form-label"
                    >
                        Key Assumptions
                    </label>

                    <textarea
                        name="key_assumptions"
                        id="key_assumptions"
                        rows="4"
                        class="form-control"
                    >{{ old(
                        'key_assumptions',
                        $developmentStrategy->key_assumptions
                    ) }}</textarea>

                </div>


                <div class="mb-3">

                    <label
                        for="strategic_constraints"
                        class="form-label"
                    >
                        Strategic Constraints
                    </label>

                    <textarea
                        name="strategic_constraints"
                        id="strategic_constraints"
                        rows="4"
                        class="form-control"
                    >{{ old(
                        'strategic_constraints',
                        $developmentStrategy->strategic_constraints
                    ) }}</textarea>

                </div>


                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label
                            for="key_opportunities"
                            class="form-label"
                        >
                            Key Opportunities
                        </label>

                        <textarea
                            name="key_opportunities"
                            id="key_opportunities"
                            rows="5"
                            class="form-control"
                        >{{ old(
                            'key_opportunities',
                            $developmentStrategy->key_opportunities
                        ) }}</textarea>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label
                            for="key_challenges"
                            class="form-label"
                        >
                            Key Challenges
                        </label>

                        <textarea
                            name="key_challenges"
                            id="key_challenges"
                            rows="5"
                            class="form-control"
                        >{{ old(
                            'key_challenges',
                            $developmentStrategy->key_challenges
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Recommended Strategy --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Recommended Development Strategy</strong>
            </div>

            <div class="card-body">

                <div class="mb-3">

                    <label
                        for="recommended_strategy"
                        class="form-label"
                    >
                        Recommended Strategy
                    </label>

                    <textarea
                        name="recommended_strategy"
                        id="recommended_strategy"
                        rows="5"
                        class="form-control"
                    >{{ old(
                        'recommended_strategy',
                        $developmentStrategy->recommended_strategy
                    ) }}</textarea>

                </div>


                <div>

                    <label
                        for="strategic_rationale"
                        class="form-label"
                    >
                        Strategic Rationale
                    </label>

                    <textarea
                        name="strategic_rationale"
                        id="strategic_rationale"
                        rows="5"
                        class="form-control"
                    >{{ old(
                        'strategic_rationale',
                        $developmentStrategy->strategic_rationale
                    ) }}</textarea>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Approval Information --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Approval Information</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label
                            for="approval_date"
                            class="form-label"
                        >
                            Approval Date
                        </label>

                        <input
                            type="date"
                            name="approval_date"
                            id="approval_date"
                            class="form-control"
                            value="{{ old(
                                'approval_date',
                                optional(
                                    $developmentStrategy->approval_date
                                )->format('Y-m-d')
                            ) }}"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

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
                            class="form-control"
                            value="{{ old(
                                'approved_by',
                                $developmentStrategy->approved_by
                            ) }}"
                        >

                        <div class="form-text">
                            Existing user/employee master can be
                            connected later.
                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Current Status
                        </label>

                        <div class="mt-2">

                            <span class="badge bg-secondary fs-6">
                                {{ $developmentStrategy->status }}
                            </span>

                        </div>

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
                    class="form-control"
                >{{ old(
                    'remarks',
                    $developmentStrategy->remarks
                ) }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- Actions --}}
        {{-- ===================================================== --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route(
                    'admin.projects.development-strategy.show',
                    [
                        'project' => $project->id,
                        'developmentStrategy' =>
                            $developmentStrategy->id,
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
                Update Development Strategy
            </button>

        </div>

    </form>

</div>

@endsection