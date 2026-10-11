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
            <strong>Legal Due Diligence Information</strong>
        </div>

        <div class="card-body">

            <div class="row">


                {{-- Type --}}

                <div class="col-md-4 mb-3">

                    <label class="form-label">
                        Due Diligence Type
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="Legal"
                        readonly
                    >

                </div>


                {{-- Reference Number --}}

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
                        placeholder="Example: LDD-0001"
                    >

                </div>


                {{-- Assessment Date --}}

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
                            isset($dueDiligence)
                            && $dueDiligence->assessment_date
                                ? $dueDiligence->assessment_date->format('Y-m-d')
                                : ''
                        ) }}"
                    >

                </div>


                {{-- Conducted By --}}

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
                        placeholder="Lawyer / Legal Consultant / Agency"
                    >

                </div>


                {{-- Status --}}

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
        SUMMARY
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Legal Assessment Summary</strong>
        </div>

        <div class="card-body">

            <label class="form-label">
                Summary
            </label>

            <textarea
                name="summary"
                rows="5"
                class="form-control"
                placeholder="Provide a summary of the legal due diligence..."
            >{{ old(
                'summary',
                $dueDiligence->summary ?? ''
            ) }}</textarea>

        </div>

    </div>


    {{-- =========================================================
        FINDINGS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Legal Findings</strong>
        </div>

        <div class="card-body">

            <label class="form-label">
                Findings
            </label>

            <textarea
                name="findings"
                rows="8"
                class="form-control"
                placeholder="Enter title verification, ownership issues, encumbrances, litigation, legal restrictions, etc."
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
            <strong>Recommendations</strong>
        </div>

        <div class="card-body">

            <label class="form-label">
                Recommendations
            </label>

            <textarea
                name="recommendations"
                rows="6"
                class="form-control"
                placeholder="Enter legal recommendations and required actions..."
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


    {{-- =========================================================
        BUTTONS
    ========================================================== --}}

    <div class="d-flex justify-content-end">

        <a
            href="{{ route(
                'admin.land.lands.legal-due-diligences.index',
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

                Update Legal Due Diligence

            @else

                Save Legal Due Diligence

            @endif

        </button>

    </div>

</form>