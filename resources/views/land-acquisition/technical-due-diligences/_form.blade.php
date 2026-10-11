<form
    method="POST"
    action="{{ $action }}"
>

    @csrf

    @if(!empty($method))
        @method($method)
    @endif


    {{-- BASIC INFORMATION --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Technical Due Diligence Information</strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3">

                    <label class="form-label">
                        Due Diligence Type
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="Technical"
                        readonly
                    >

                </div>


                <div class="col-md-4 mb-3">

                    <label class="form-label">
                        Reference Number
                    </label>

                    <input
                        type="text"
                        name="reference_no"
                        class="form-control"
                        value="{{ old(
                            'reference_no',
                            $dueDiligence->reference_no ?? ''
                        ) }}"
                        placeholder="Example: TDD-0001"
                    >

                </div>


                <div class="col-md-4 mb-3">

                    <label class="form-label">
                        Assessment Date
                    </label>

                    <input
                        type="date"
                        name="assessment_date"
                        class="form-control"
                        value="{{ old(
                            'assessment_date',
                            isset($dueDiligence->assessment_date)
                                ? $dueDiligence->assessment_date->format('Y-m-d')
                                : ''
                        ) }}"
                    >

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Conducted By
                    </label>

                    <input
                        type="text"
                        name="conducted_by"
                        class="form-control"
                        value="{{ old(
                            'conducted_by',
                            $dueDiligence->conducted_by ?? ''
                        ) }}"
                        placeholder="Engineer / Consultant / Agency"
                    >

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Status *
                    </label>

                    <select
                        name="status"
                        class="form-select"
                        required
                    >

                        @foreach([
                            'Pending',
                            'Under Review',
                            'Completed',
                            'Approved',
                            'Rejected'
                        ] as $status)

                            <option
                                value="{{ $status }}"
                                @selected(
                                    old(
                                        'status',
                                        $dueDiligence->status ?? 'Pending'
                                    ) === $status
                                )
                            >
                                {{ $status }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>

        </div>

    </div>


    {{-- TECHNICAL ASSESSMENT --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Technical Assessment</strong>
        </div>

        <div class="card-body">

            <label class="form-label">
                Summary
            </label>

            <textarea
                name="summary"
                rows="5"
                class="form-control"
                placeholder="Provide overall technical assessment of the land..."
            >{{ old(
                'summary',
                $dueDiligence->summary ?? ''
            ) }}</textarea>

        </div>

    </div>


    {{-- TECHNICAL FINDINGS --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Technical Findings</strong>
        </div>

        <div class="card-body">

            <label class="form-label">
                Findings
            </label>

            <textarea
                name="findings"
                rows="10"
                class="form-control"
                placeholder="Enter technical findings such as site conditions, topography, access, utilities, infrastructure, soil/geotechnical observations, development constraints, etc."
            >{{ old(
                'findings',
                $dueDiligence->findings ?? ''
            ) }}</textarea>

        </div>

    </div>


    {{-- RECOMMENDATIONS --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Technical Recommendations</strong>
        </div>

        <div class="card-body">

            <label class="form-label">
                Recommendations
            </label>

            <textarea
                name="recommendations"
                rows="7"
                class="form-control"
                placeholder="Enter required technical actions, additional surveys, investigations, approvals, mitigation measures, etc."
            >{{ old(
                'recommendations',
                $dueDiligence->recommendations ?? ''
            ) }}</textarea>

        </div>

    </div>


    {{-- REMARKS --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Remarks</strong>
        </div>

        <div class="card-body">

            <textarea
                name="remarks"
                rows="4"
                class="form-control"
                placeholder="Enter additional remarks..."
            >{{ old(
                'remarks',
                $dueDiligence->remarks ?? ''
            ) }}</textarea>

        </div>

    </div>


    <div class="d-flex justify-content-end">

        <a
            href="{{ route(
                'admin.land.lands.show',
                $land
            ) }}"
            class="btn btn-secondary me-2"
        >
            Cancel
        </a>

        <button
            type="submit"
            class="btn btn-primary"
        >

            @if(!empty($method))
                Update Technical Due Diligence
            @else
                Save Technical Due Diligence
            @endif

        </button>

    </div>

</form>