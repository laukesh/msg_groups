@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>
            <div class="text-muted small">
                Project / Procurement Strategy
            </div>

            <h3>
                Create Procurement Strategy
            </h3>

            <div class="text-muted">
                {{ $project->project_name }}
            </div>
        </div>

        <a
            href="{{ route(
                'admin.projects.procurement-strategy.index',
                ['project' => $project->id]
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
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route(
            'admin.projects.procurement-strategy.store',
            ['project' => $project->id]
        ) }}"
    >

        @csrf


        {{-- Strategy Information --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Strategy Information</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Strategy Number
                        </label>

                        <input
                            type="text"
                            name="strategy_number"
                            class="form-control"
                            value="{{ old(
                                'strategy_number',
                                $strategyNumber
                            ) }}"
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
                            value="V{{ $nextVersion }}"
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
                            value="Draft"
                            readonly
                        >

                    </div>

                </div>


                <div class="row">

                    <div class="col-md-8 mb-3">

                        <label class="form-label">
                            Title <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="title"
                            class="form-control"
                            value="{{ old('title') }}"
                            required
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Procurement Model
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="procurement_model"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select Procurement Model
                            </option>

                            @foreach([
                                'Traditional',
                                'Design-Bid-Build',
                                'Design-Build',
                                'EPC',
                                'EPCM',
                                'Turnkey',
                                'Framework Agreement',
                                'Direct Procurement',
                                'Competitive Tender',
                                'Negotiated Procurement',
                                'Other',
                            ] as $model)

                                <option
                                    value="{{ $model }}"
                                    @selected(
                                        old('procurement_model') === $model
                                    )
                                >
                                    {{ $model }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Effective Date
                        </label>

                        <input
                            type="date"
                            name="effective_date"
                            class="form-control"
                            value="{{ old('effective_date') }}"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- Procurement Approach --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Procurement Approach</strong>
            </div>

            <div class="card-body">

                <textarea
                    name="procurement_approach"
                    rows="5"
                    class="form-control"
                    placeholder="Describe the overall procurement approach..."
                >{{ old('procurement_approach') }}</textarea>

            </div>

        </div>


        {{-- Procurement Packages --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Procurement Packages</strong>
            </div>

            <div class="card-body">

                <textarea
                    name="procurement_packages"
                    rows="6"
                    class="form-control"
                    placeholder="Define the major procurement packages planned for the project..."
                >{{ old('procurement_packages') }}</textarea>

                <div class="form-text">
                    This is strategic package planning only.
                    Actual procurement packages will be handled in the Procurement domain.
                </div>

            </div>

        </div>


        {{-- Sourcing Strategy --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Sourcing Strategy</strong>
            </div>

            <div class="card-body">

                <textarea
                    name="sourcing_strategy"
                    rows="6"
                    class="form-control"
                    placeholder="Describe sourcing strategy, market approach and supplier sourcing..."
                >{{ old('sourcing_strategy') }}</textarea>

            </div>

        </div>


        {{-- Tendering Strategy --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Tendering Strategy</strong>
            </div>

            <div class="card-body">

                <textarea
                    name="tendering_strategy"
                    rows="6"
                    class="form-control"
                    placeholder="Describe planned tendering and bid invitation approach..."
                >{{ old('tendering_strategy') }}</textarea>

            </div>

        </div>


        {{-- Vendor Selection Criteria --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Vendor Selection Criteria</strong>
            </div>

            <div class="card-body">

                <textarea
                    name="vendor_selection_criteria"
                    rows="6"
                    class="form-control"
                    placeholder="Define strategic vendor qualification and selection criteria..."
                >{{ old('vendor_selection_criteria') }}</textarea>

                <div class="form-text">
                    Actual vendor qualification, quotation evaluation
                    and vendor selection will be handled in Procurement.
                </div>

            </div>

        </div>


        {{-- Procurement Schedule --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Procurement Schedule</strong>
            </div>

            <div class="card-body">

                <textarea
                    name="procurement_schedule"
                    rows="6"
                    class="form-control"
                    placeholder="Describe strategic procurement timing, sequencing and major procurement milestones..."
                >{{ old('procurement_schedule') }}</textarea>

            </div>

        </div>


        {{-- Assumptions / Constraints --}}

        <div class="row">

            <div class="col-md-6">

                <div class="card mb-4">

                    <div class="card-header">
                        <strong>Assumptions</strong>
                    </div>

                    <div class="card-body">

                        <textarea
                            name="assumptions"
                            rows="6"
                            class="form-control"
                            placeholder="Enter procurement assumptions..."
                        >{{ old('assumptions') }}</textarea>

                    </div>

                </div>

            </div>


            <div class="col-md-6">

                <div class="card mb-4">

                    <div class="card-header">
                        <strong>Constraints</strong>
                    </div>

                    <div class="card-body">

                        <textarea
                            name="constraints"
                            rows="6"
                            class="form-control"
                            placeholder="Enter procurement constraints..."
                        >{{ old('constraints') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- Remarks --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Remarks</strong>
            </div>

            <div class="card-body">

                <textarea
                    name="remarks"
                    rows="4"
                    class="form-control"
                    placeholder="Enter remarks..."
                >{{ old('remarks') }}</textarea>

            </div>

        </div>


        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route(
                    'admin.projects.procurement-strategy.index',
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
                Create Procurement Strategy
            </button>

        </div>

    </form>

</div>

@endsection