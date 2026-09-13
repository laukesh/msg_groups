<form
    method="POST"
    action="{{ $action }}"
>

    @csrf

    @if(!empty($method))
        @method($method)
    @endif


    {{-- =========================================================
        BASIC INFORMATION
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Environmental Assessment Information</strong>
        </div>

        <div class="card-body">

            <div class="row">


                <div class="col-md-4 mb-3">

                    <label class="form-label">
                        Assessment Type
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="Environmental"
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
                        placeholder="Example: EIA-0001"
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
                        placeholder="Environmental Consultant / Agency"
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


    {{-- =========================================================
        ENVIRONMENTAL ASSESSMENT
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Environmental Assessment</strong>
        </div>

        <div class="card-body">

            <label class="form-label">
                Summary
            </label>

            <textarea
                name="summary"
                rows="6"
                class="form-control"
                placeholder="Provide the overall environmental assessment of the land..."
            >{{ old(
                'summary',
                $dueDiligence->summary ?? ''
            ) }}</textarea>

        </div>

    </div>


    {{-- =========================================================
        ENVIRONMENTAL FINDINGS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Environmental Findings</strong>
        </div>

        <div class="card-body">

            <label class="form-label">
                Findings
            </label>

            <textarea
                name="findings"
                rows="10"
                class="form-control"
                placeholder="Enter environmental findings, site contamination, ecological constraints, protected areas, drainage, flood risks, pollution, environmental restrictions, etc."
            >{{ old(
                'findings',
                $dueDiligence->findings ?? ''
            ) }}</textarea>

        </div>

    </div>


    {{-- =========================================================
        RECOMMENDATIONS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Environmental Recommendations</strong>
        </div>

        <div class="card-body">

            <label class="form-label">
                Recommendations
            </label>

            <textarea
                name="recommendations"
                rows="7"
                class="form-control"
                placeholder="Enter environmental recommendations, mitigation measures, required approvals, further studies, etc."
            >{{ old(
                'recommendations',
                $dueDiligence->recommendations ?? ''
            ) }}</textarea>

        </div>

    </div>


    {{-- =========================================================
        REMARKS
    ========================================================== --}}

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


    {{-- BUTTONS --}}

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

                Update Environmental Assessment

            @else

                Save Environmental Assessment

            @endif

        </button>

    </div>

</form>