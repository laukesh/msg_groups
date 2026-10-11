@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project / Risk Register
            </div>

            <h3>
                Edit Risk
            </h3>

            <div class="text-muted">

                {{ $project->project_name }}

                · {{ $risk->risk_number }}

            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.risks.show',
                [
                    'project' => $project->id,
                    'risk' => $risk->id,
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
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route(
            'admin.projects.risks.update',
            [
                'project' => $project->id,
                'risk' => $risk->id,
            ]
        ) }}"
    >

        @csrf
        @method('PUT')


        {{-- Risk Information --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Risk Information</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Risk Number
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $risk->risk_number }}"
                            readonly
                        >

                    </div>


                    <div class="col-md-8 mb-3">

                        <label class="form-label">
                            Risk Title
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="risk_title"
                            class="form-control"
                            value="{{ old(
                                'risk_title',
                                $risk->risk_title
                            ) }}"
                            required
                        >

                    </div>

                </div>


                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Risk Category
                        </label>

                        <select
                            name="risk_category"
                            class="form-select"
                            required
                        >

                            @foreach([
                                'Strategic',
                                'Financial',
                                'Commercial',
                                'Procurement',
                                'Contract',
                                'Design',
                                'Construction',
                                'Schedule',
                                'Cost',
                                'Quality',
                                'Safety',
                                'Environmental',
                                'Legal',
                                'Regulatory',
                                'Stakeholder',
                                'Operational',
                                'Technology',
                                'Other',
                            ] as $category)

                                <option
                                    value="{{ $category }}"
                                    @selected(
                                        old(
                                            'risk_category',
                                            $risk->risk_category
                                        ) === $category
                                    )
                                >
                                    {{ $category }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Identified Date
                        </label>

                        <input
                            type="date"
                            name="identified_date"
                            class="form-control"
                            value="{{ old(
                                'identified_date',
                                $risk->identified_date
                                    ? $risk->identified_date
                                        ->format('Y-m-d')
                                    : null
                            ) }}"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Target Date
                        </label>

                        <input
                            type="date"
                            name="target_date"
                            class="form-control"
                            value="{{ old(
                                'target_date',
                                $risk->target_date
                                    ? $risk->target_date
                                        ->format('Y-m-d')
                                    : null
                            ) }}"
                        >

                    </div>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Risk Description
                        <span class="text-danger">*</span>
                    </label>

                    <textarea
                        name="risk_description"
                        rows="5"
                        class="form-control"
                        required
                    >{{ old(
                        'risk_description',
                        $risk->risk_description
                    ) }}</textarea>

                </div>


                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Cause
                        </label>

                        <textarea
                            name="cause"
                            rows="5"
                            class="form-control"
                        >{{ old(
                            'cause',
                            $risk->cause
                        ) }}</textarea>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Consequence
                        </label>

                        <textarea
                            name="consequence"
                            rows="5"
                            class="form-control"
                        >{{ old(
                            'consequence',
                            $risk->consequence
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- Assessment --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Risk Assessment</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Probability
                        </label>

                        <select
                            name="probability"
                            id="probability"
                            class="form-select"
                            required
                        >

                            @foreach([
                                'Very Low',
                                'Low',
                                'Medium',
                                'High',
                                'Very High',
                            ] as $value)

                                <option
                                    value="{{ $value }}"
                                    @selected(
                                        old(
                                            'probability',
                                            $risk->probability
                                        ) === $value
                                    )
                                >
                                    {{ $value }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Impact
                        </label>

                        <select
                            name="impact"
                            id="impact"
                            class="form-select"
                            required
                        >

                            @foreach([
                                'Very Low',
                                'Low',
                                'Medium',
                                'High',
                                'Very High',
                            ] as $value)

                                <option
                                    value="{{ $value }}"
                                    @selected(
                                        old(
                                            'impact',
                                            $risk->impact
                                        ) === $value
                                    )
                                >
                                    {{ $value }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Current Risk
                        </label>

                        <div
                            id="risk-preview"
                            class="border rounded p-2 bg-light"
                        >

                            <strong id="risk-score">
                                {{ $risk->risk_score }}
                            </strong>

                            <span class="ms-2">
                                {{ $risk->risk_level }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Response --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Risk Response</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Response Strategy
                        </label>

                        <select
                            name="response_strategy"
                            class="form-select"
                            required
                        >

                            @foreach([
                                'Avoid',
                                'Mitigate',
                                'Transfer',
                                'Accept',
                                'Exploit',
                                'Enhance',
                                'Share',
                            ] as $strategy)

                                <option
                                    value="{{ $strategy }}"
                                    @selected(
                                        old(
                                            'response_strategy',
                                            $risk->response_strategy
                                        ) === $strategy
                                    )
                                >
                                    {{ $strategy }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Risk Owner
                        </label>

                        <select
                            name="risk_owner_id"
                            class="form-select"
                        >

                            <option value="">
                                Unassigned
                            </option>

                            @foreach($riskOwners as $owner)

                                <option
                                    value="{{ $owner->id }}"
                                    @selected(
                                        old(
                                            'risk_owner_id',
                                            $risk->risk_owner_id
                                        ) == $owner->id
                                    )
                                >
                                    {{ $owner->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            @foreach([
                                'Open',
                                'Monitoring',
                                'Mitigated',
                                'Closed',
                                'Occurred',
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        old(
                                            'status',
                                            $risk->status
                                        ) === $status
                                    )
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Mitigation Plan
                    </label>

                    <textarea
                        name="mitigation_plan"
                        rows="6"
                        class="form-control"
                    >{{ old(
                        'mitigation_plan',
                        $risk->mitigation_plan
                    ) }}</textarea>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Contingency Plan
                    </label>

                    <textarea
                        name="contingency_plan"
                        rows="6"
                        class="form-control"
                    >{{ old(
                        'contingency_plan',
                        $risk->contingency_plan
                    ) }}</textarea>

                </div>

            </div>

        </div>


        {{-- Residual --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>Residual Risk</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Residual Probability
                        </label>

                        <select
                            name="residual_probability"
                            class="form-select"
                        >

                            <option value="">
                                Not assessed
                            </option>

                            @foreach([
                                'Very Low',
                                'Low',
                                'Medium',
                                'High',
                                'Very High',
                            ] as $value)

                                <option
                                    value="{{ $value }}"
                                    @selected(
                                        old(
                                            'residual_probability',
                                            $risk->residual_probability
                                        ) === $value
                                    )
                                >
                                    {{ $value }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Residual Impact
                        </label>

                        <select
                            name="residual_impact"
                            class="form-select"
                        >

                            <option value="">
                                Not assessed
                            </option>

                            @foreach([
                                'Very Low',
                                'Low',
                                'Medium',
                                'High',
                                'Very High',
                            ] as $value)

                                <option
                                    value="{{ $value }}"
                                    @selected(
                                        old(
                                            'residual_impact',
                                            $risk->residual_impact
                                        ) === $value
                                    )
                                >
                                    {{ $value }}
                                </option>

                            @endforeach

                        </select>

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
                >{{ old(
                    'remarks',
                    $risk->remarks
                ) }}</textarea>

            </div>

        </div>


        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route(
                    'admin.projects.risks.show',
                    [
                        'project' => $project->id,
                        'risk' => $risk->id,
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
                Save Changes
            </button>

        </div>

    </form>

</div>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const probability =
            document.getElementById('probability');

        const impact =
            document.getElementById('impact');

        const score =
            document.getElementById('risk-score');

        const preview =
            document.getElementById('risk-preview');


        const values = {
            'Very Low': 1,
            'Low': 2,
            'Medium': 3,
            'High': 4,
            'Very High': 5
        };


        function updateRiskPreview()
        {
            const p =
                values[probability.value] || 0;

            const i =
                values[impact.value] || 0;

            const total = p * i;


            let level = 'Low';

            if (total >= 17) {

                level = 'Critical';

            } else if (total >= 10) {

                level = 'High';

            } else if (total >= 5) {

                level = 'Medium';

            }


            score.textContent = total;

            preview.querySelector(
                'span'
            ).textContent = level;
        }


        probability.addEventListener(
            'change',
            updateRiskPreview
        );

        impact.addEventListener(
            'change',
            updateRiskPreview
        );


        updateRiskPreview();

    }
);

</script>

@endsection