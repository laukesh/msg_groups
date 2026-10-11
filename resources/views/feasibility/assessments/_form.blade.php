<form
    method="POST"
    action="{{ $action }}"
>

    @csrf

    @if(!empty($method))
        @method($method)
    @endif


    <div class="card mb-4">

        <div class="card-header">
            <strong>Feasibility Assessment</strong>
        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Assessment Title *
                    </label>

                    <input
                        type="text"
                        name="title"
                        class="form-control"
                        value="{{ old(
                            'title',
                            $feasibilityAssessment->title ?? ''
                        ) }}"
                        placeholder="Example: Mixed Use Development Feasibility"
                        required
                    >

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Development Type
                    </label>

                    <select
                        name="development_type"
                        class="form-select"
                    >

                        <option value="">
                            Select Development Type
                        </option>

                        @foreach([
                            'Retail',
                            'Office',
                            'Residential',
                            'Mixed Use',
                            'Hospitality',
                            'Industrial',
                            'Warehouse',
                            'Commercial',
                            'Other'
                        ] as $type)

                            <option
                                value="{{ $type }}"
                                @selected(
                                    old(
                                        'development_type',
                                        $feasibilityAssessment->development_type ?? ''
                                    ) === $type
                                )
                            >
                                {{ $type }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Assessment Date
                    </label>

                    <input
                        type="date"
                        name="assessment_date"
                        class="form-control"
                        value="{{ old(
                            'assessment_date',
                            isset($feasibilityAssessment->assessment_date)
                                ? $feasibilityAssessment
                                    ->assessment_date
                                    ->format('Y-m-d')
                                : ''
                        ) }}"
                    >

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Target Completion Date
                    </label>

                    <input
                        type="date"
                        name="target_completion_date"
                        class="form-control"
                        value="{{ old(
                            'target_completion_date',
                            isset($feasibilityAssessment->target_completion_date)
                                ? $feasibilityAssessment
                                    ->target_completion_date
                                    ->format('Y-m-d')
                                : ''
                        ) }}"
                    >

                </div>


                <div class="col-md-12 mb-3">

                    <label class="form-label">
                        Project Concept
                    </label>

                    <textarea
                        name="project_concept"
                        rows="6"
                        class="form-control"
                        placeholder="Describe the proposed development concept..."
                    >{{ old(
                        'project_concept',
                        $feasibilityAssessment->project_concept ?? ''
                    ) }}</textarea>

                </div>


                <div class="col-md-12 mb-3">

                    <label class="form-label">
                        Summary
                    </label>

                    <textarea
                        name="summary"
                        rows="5"
                        class="form-control"
                    >{{ old(
                        'summary',
                        $feasibilityAssessment->summary ?? ''
                    ) }}</textarea>

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Key Assumptions
                    </label>

                    <textarea
                        name="key_assumptions"
                        rows="6"
                        class="form-control"
                    >{{ old(
                        'key_assumptions',
                        $feasibilityAssessment->key_assumptions ?? ''
                    ) }}</textarea>

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Key Risks
                    </label>

                    <textarea
                        name="key_risks"
                        rows="6"
                        class="form-control"
                    >{{ old(
                        'key_risks',
                        $feasibilityAssessment->key_risks ?? ''
                    ) }}</textarea>

                </div>


                <div class="col-md-12 mb-3">

                    <label class="form-label">
                        Recommendation
                    </label>

                    <textarea
                        name="recommendation"
                        rows="5"
                        class="form-control"
                        placeholder="Initial recommendation regarding the feasibility..."
                    >{{ old(
                        'recommendation',
                        $feasibilityAssessment->recommendation ?? ''
                    ) }}</textarea>

                </div>

            </div>

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

            {{ !empty($method)
                ? 'Update Assessment'
                : 'Create Assessment'
            }}

        </button>

    </div>

</form>