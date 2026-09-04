@php

    $investigation = $investigation ?? null;

    $users = $users ?? collect();

@endphp


<form
    method="POST"
    action="{{ $action }}"
>

    @csrf

    @if($method)

        @method($method)

    @endif


    {{-- =========================================================
        INVESTIGATION DETAILS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Investigation Details
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-3">


                {{-- =================================================
                    INVESTIGATION NUMBER
                ================================================== --}}

                <div class="col-md-4">

                    <label class="form-label">
                        Investigation Number
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $investigationNumber ?? $investigation?->investigation_number ?? '—' }}"
                        readonly
                    >

                </div>


                {{-- =================================================
                    INVESTIGATION DATE
                ================================================== --}}

                <div class="col-md-4">

                    <label class="form-label">

                        Investigation Date

                        <span class="text-danger">
                            *
                        </span>

                    </label>

                    <input
                        type="date"
                        name="investigation_date"
                        class="form-control @error('investigation_date') is-invalid @enderror"
                        value="{{ old(
                            'investigation_date',
                            $investigation?->investigation_date?->format('Y-m-d')
                        ) }}"
                        required
                    >

                    @error('investigation_date')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- =================================================
                    LEAD INVESTIGATOR
                ================================================== --}}

                <div class="col-md-4">

                    <label class="form-label">
                        Lead Investigator
                    </label>

                    <select
                        name="lead_investigator_id"
                        class="form-select @error('lead_investigator_id') is-invalid @enderror"
                    >

                        <option value="">
                            -- Select Investigator --
                        </option>


                        @foreach($users as $user)

                            <option
                                value="{{ $user->id }}"
                                @selected(
                                    (string) old(
                                        'lead_investigator_id',
                                        $investigation?->lead_investigator_id
                                    ) === (string) $user->id
                                )
                            >

                                {{ $user->name }}

                            </option>

                        @endforeach

                    </select>


                    @error('lead_investigator_id')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- =================================================
                    INVESTIGATION TEAM
                ================================================== --}}

                <div class="col-12">

                    <label class="form-label">
                        Investigation Team
                    </label>

                    <textarea
                        name="investigation_team"
                        rows="3"
                        class="form-control @error('investigation_team') is-invalid @enderror"
                        placeholder="Enter investigation team members"
                    >{{ old(
                        'investigation_team',
                        $investigation?->investigation_team
                    ) }}</textarea>


                    @error('investigation_team')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        CAUSE ANALYSIS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Cause Analysis
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-3">


                {{-- Immediate Cause --}}

                <div class="col-md-6">

                    <label class="form-label">
                        Immediate Cause
                    </label>

                    <textarea
                        name="immediate_cause"
                        rows="4"
                        class="form-control @error('immediate_cause') is-invalid @enderror"
                        placeholder="Describe the immediate cause"
                    >{{ old(
                        'immediate_cause',
                        $investigation?->immediate_cause
                    ) }}</textarea>


                    @error('immediate_cause')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Root Cause --}}

                <div class="col-md-6">

                    <label class="form-label">
                        Root Cause
                    </label>

                    <textarea
                        name="root_cause"
                        rows="4"
                        class="form-control @error('root_cause') is-invalid @enderror"
                        placeholder="Describe the root cause"
                    >{{ old(
                        'root_cause',
                        $investigation?->root_cause
                    ) }}</textarea>


                    @error('root_cause')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Contributing Factors --}}

                <div class="col-12">

                    <label class="form-label">
                        Contributing Factors
                    </label>

                    <textarea
                        name="contributing_factors"
                        rows="3"
                        class="form-control @error('contributing_factors') is-invalid @enderror"
                        placeholder="Describe factors that contributed to the incident"
                    >{{ old(
                        'contributing_factors',
                        $investigation?->contributing_factors
                    ) }}</textarea>


                    @error('contributing_factors')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        UNSAFE ACT / CONDITION
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Unsafe Act / Condition
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-3">


                {{-- Unsafe Act --}}

                <div class="col-md-6">

                    <label class="form-label">
                        Unsafe Act
                    </label>

                    <textarea
                        name="unsafe_act"
                        rows="4"
                        class="form-control @error('unsafe_act') is-invalid @enderror"
                        placeholder="Describe any unsafe act"
                    >{{ old(
                        'unsafe_act',
                        $investigation?->unsafe_act
                    ) }}</textarea>


                    @error('unsafe_act')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Unsafe Condition --}}

                <div class="col-md-6">

                    <label class="form-label">
                        Unsafe Condition
                    </label>

                    <textarea
                        name="unsafe_condition"
                        rows="4"
                        class="form-control @error('unsafe_condition') is-invalid @enderror"
                        placeholder="Describe any unsafe condition"
                    >{{ old(
                        'unsafe_condition',
                        $investigation?->unsafe_condition
                    ) }}</textarea>


                    @error('unsafe_condition')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        FINDINGS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Investigation Findings
            </strong>

        </div>


        <div class="card-body">

            <textarea
                name="findings"
                rows="6"
                class="form-control @error('findings') is-invalid @enderror"
                placeholder="Document the investigation findings"
            >{{ old(
                'findings',
                $investigation?->findings
            ) }}</textarea>


            @error('findings')

                <div class="invalid-feedback">
                    {{ $message }}
                </div>

            @enderror

        </div>

    </div>



    {{-- =========================================================
        CONCLUSION / RECOMMENDATIONS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Conclusion & Recommendations
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-3">


                {{-- Conclusion --}}

                <div class="col-md-6">

                    <label class="form-label">
                        Conclusion
                    </label>

                    <textarea
                        name="conclusion"
                        rows="4"
                        class="form-control @error('conclusion') is-invalid @enderror"
                        placeholder="Enter investigation conclusion"
                    >{{ old(
                        'conclusion',
                        $investigation?->conclusion
                    ) }}</textarea>


                    @error('conclusion')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Recommendations --}}

                <div class="col-md-6">

                    <label class="form-label">
                        Recommendations
                    </label>

                    <textarea
                        name="recommendations"
                        rows="4"
                        class="form-control @error('recommendations') is-invalid @enderror"
                        placeholder="Enter recommended actions"
                    >{{ old(
                        'recommendations',
                        $investigation?->recommendations
                    ) }}</textarea>


                    @error('recommendations')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        REMARKS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Remarks
            </strong>

        </div>


        <div class="card-body">

            <textarea
                name="remarks"
                rows="3"
                class="form-control @error('remarks') is-invalid @enderror"
                placeholder="Additional remarks"
            >{{ old(
                'remarks',
                $investigation?->remarks
            ) }}</textarea>


            @error('remarks')

                <div class="invalid-feedback">
                    {{ $message }}
                </div>

            @enderror

        </div>

    </div>



    {{-- =========================================================
        FORM ACTIONS
    ========================================================== --}}

    <div class="card">

        <div class="card-body">

            <div class="d-flex justify-content-end gap-2">

                <a
                    href="{{ route(
                        'admin.projects.construction.hse.incidents.show',
                        [
                            'project' => $project,
                            'incident' => $incident,
                        ]
                    ) }}"
                    class="btn btn-outline-secondary"
                >

                    <i class="bi bi-x-lg me-1"></i>

                    Cancel

                </a>


                <button
                    type="submit"
                    class="btn btn-primary"
                >

                    <i class="bi bi-save me-1"></i>

                    {{ $investigation
                        ? 'Update Investigation'
                        : 'Save Investigation'
                    }}

                </button>

            </div>

        </div>

    </div>

</form>