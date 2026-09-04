<div class="row g-3">

    <div class="col-md-6">

        <label class="form-label">
            Activity <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="activity"
            class="form-control"
            value="{{ old('activity', $item->activity ?? '') }}"
            placeholder="e.g. Reinforcement Work"
            required
        >

    </div>


    <div class="col-md-6">

        <label class="form-label">
            Inspection / Test <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="inspection_test"
            class="form-control"
            value="{{ old('inspection_test', $item->inspection_test ?? '') }}"
            placeholder="e.g. Reinforcement Inspection"
            required
        >

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Stage
        </label>

        <select
            name="stage"
            class="form-select"
        >

            <option value="">
                Select Stage
            </option>

            @foreach([
                'Pre-Work',
                'During Work',
                'Post-Work',
                'Final',
            ] as $stage)

                <option
                    value="{{ $stage }}"
                    @selected(
                        old(
                            'stage',
                            $item->stage ?? ''
                        ) === $stage
                    )
                >
                    {{ $stage }}
                </option>

            @endforeach

        </select>

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Inspection Type
        </label>

        <select
            name="inspection_type"
            class="form-select"
        >

            <option value="">
                Select Type
            </option>

            @foreach([
                'Inspection',
                'Test',
                'Inspection & Test',
                'Review',
            ] as $type)

                <option
                    value="{{ $type }}"
                    @selected(
                        old(
                            'inspection_type',
                            $item->inspection_type ?? ''
                        ) === $type
                    )
                >
                    {{ $type }}
                </option>

            @endforeach

        </select>

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Responsible Party
        </label>

        <select
            name="responsible_party"
            class="form-select"
        >

            <option value="">
                Select Party
            </option>

            @foreach([
                'Contractor',
                'Consultant',
                'Client',
                'QA/QC',
                'HSE',
                'Third Party',
            ] as $party)

                <option
                    value="{{ $party }}"
                    @selected(
                        old(
                            'responsible_party',
                            $item->responsible_party ?? ''
                        ) === $party
                    )
                >
                    {{ $party }}
                </option>

            @endforeach

        </select>

    </div>


    <div class="col-md-12">

        <label class="form-label">
            Acceptance Criteria
        </label>

        <textarea
            name="acceptance_criteria"
            rows="4"
            class="form-control"
            placeholder="Define the acceptance criteria..."
        >{{ old(
            'acceptance_criteria',
            $item->acceptance_criteria ?? ''
        ) }}</textarea>

    </div>


    <div class="col-md-12">

        <label class="form-label">
            Reference Standard
        </label>

        <input
            type="text"
            name="reference_standard"
            class="form-control"
            value="{{ old(
                'reference_standard',
                $item->reference_standard ?? ''
            ) }}"
            placeholder="e.g. IS 456, Approved Drawing No. ..."
        >

    </div>


    <div class="col-md-4">

        <div class="form-check mt-2">

            <input
                type="checkbox"
                name="hold_point"
                value="1"
                class="form-check-input"
                id="holdPoint{{ $item->id ?? 'new' }}"
                @checked(
                    old(
                        'hold_point',
                        $item->hold_point ?? false
                    )
                )
            >

            <label
                class="form-check-label"
                for="holdPoint{{ $item->id ?? 'new' }}"
            >
                Hold Point
            </label>

        </div>

        <div class="text-muted small mt-1">
            Work cannot proceed until this point is cleared.
        </div>

    </div>


    <div class="col-md-4">

        <div class="form-check mt-2">

            <input
                type="checkbox"
                name="witness_point"
                value="1"
                class="form-check-input"
                id="witnessPoint{{ $item->id ?? 'new' }}"
                @checked(
                    old(
                        'witness_point',
                        $item->witness_point ?? false
                    )
                )
            >

            <label
                class="form-check-label"
                for="witnessPoint{{ $item->id ?? 'new' }}"
            >
                Witness Point
            </label>

        </div>

        <div class="text-muted small mt-1">
            Relevant party gets an opportunity to witness.
        </div>

    </div>


    <div class="col-md-4">

        <div class="form-check mt-2">

            <input
                type="checkbox"
                name="required"
                value="1"
                class="form-check-input"
                id="required{{ $item->id ?? 'new' }}"
                @checked(
                    old(
                        'required',
                        $item->required ?? true
                    )
                )
            >

            <label
                class="form-check-label"
                for="required{{ $item->id ?? 'new' }}"
            >
                Required
            </label>

        </div>

    </div>


    <div class="col-md-12">

        <label class="form-label">
            Remarks
        </label>

        <textarea
            name="remarks"
            rows="3"
            class="form-control"
            placeholder="Additional remarks..."
        >{{ old(
            'remarks',
            $item->remarks ?? ''
        ) }}</textarea>

    </div>

</div>