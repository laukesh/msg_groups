<div class="row g-3">

    {{-- ============================================================
        PROCUREMENT CONTRACT
    ============================================================ --}}

    <div class="col-md-6">

        <label class="form-label">
            Procurement Contract
        </label>

        <select
            name="procurement_contract_id"
            class="form-select">

            <option value="">
                Select Procurement Contract
            </option>

            @foreach($contracts as $contract)

                <option
                    value="{{ $contract->id }}"
                    @selected(
                        old(
                            'procurement_contract_id',
                            $snag?->procurement_contract_id
                        ) == $contract->id
                    )>

                    {{ $contract->contract_number }}

                    @if($contract->contract_title)
                        -
                        {{ $contract->contract_title }}
                    @endif

                    @if($contract->bidder_name)
                        -
                        {{ $contract->bidder_name }}
                    @endif

                </option>

            @endforeach

        </select>

        <small class="text-muted">
            Contract is loaded from this project's procurement records.
        </small>

    </div>


    {{-- CATEGORY --}}

    <div class="col-md-3">

        <label class="form-label">
            Category
        </label>

        <input
            type="text"
            name="category"
            class="form-control"
            value="{{ old(
                'category',
                $snag?->category
            ) }}"
            placeholder="Civil, Electrical, HVAC...">

    </div>


    {{-- DISCIPLINE --}}

    <div class="col-md-3">

        <label class="form-label">
            Discipline
        </label>

        <input
            type="text"
            name="discipline"
            class="form-control"
            value="{{ old(
                'discipline',
                $snag?->discipline
            ) }}"
            placeholder="Architecture, MEP...">

    </div>


    {{-- TITLE --}}

    <div class="col-md-8">

        <label class="form-label">
            Title
            <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="title"
            class="form-control"
            value="{{ old(
                'title',
                $snag?->title
            ) }}"
            required>

    </div>


    {{-- PRIORITY --}}

    <div class="col-md-4">

        <label class="form-label">
            Priority
            <span class="text-danger">*</span>
        </label>

        <select
            name="priority"
            class="form-select"
            required>

            @foreach($priorities ?? [
                'Low',
                'Medium',
                'High',
                'Critical'
            ] as $priority)

                <option
                    value="{{ $priority }}"
                    @selected(
                        old(
                            'priority',
                            $snag?->priority ?? 'Medium'
                        ) === $priority
                    )>

                    {{ $priority }}

                </option>

            @endforeach

        </select>

    </div>


    {{-- DESCRIPTION --}}

    <div class="col-12">

        <label class="form-label">
            Description
        </label>

        <textarea
            name="description"
            class="form-control"
            rows="3">{{ old(
                'description',
                $snag?->description
            ) }}</textarea>

    </div>


    {{-- LOCATION --}}

    <div class="col-md-4">

        <label class="form-label">
            Location
        </label>

        <input
            type="text"
            name="location"
            class="form-control"
            value="{{ old(
                'location',
                $snag?->location
            ) }}"
            placeholder="Site / Area">

    </div>


    {{-- BUILDING --}}

    <div class="col-md-2">

        <label class="form-label">
            Building
        </label>

        <input
            type="text"
            name="building"
            class="form-control"
            value="{{ old(
                'building',
                $snag?->building
            ) }}">

    </div>


    {{-- FLOOR --}}

    <div class="col-md-2">

        <label class="form-label">
            Floor
        </label>

        <input
            type="text"
            name="floor"
            class="form-control"
            value="{{ old(
                'floor',
                $snag?->floor
            ) }}">

    </div>


    {{-- ZONE --}}

    <div class="col-md-2">

        <label class="form-label">
            Zone
        </label>

        <input
            type="text"
            name="zone"
            class="form-control"
            value="{{ old(
                'zone',
                $snag?->zone
            ) }}">

    </div>


    {{-- UNIT --}}

    <div class="col-md-2">

        <label class="form-label">
            Unit
        </label>

        <input
            type="text"
            name="unit"
            class="form-control"
            value="{{ old(
                'unit',
                $snag?->unit
            ) }}">

    </div>


    {{-- ASSIGNED USER --}}

    <div class="col-md-4">

        <label class="form-label">
            Assign To
        </label>

        <select
            name="assigned_to"
            class="form-select">

            <option value="">
                Not Assigned
            </option>

            @foreach($users as $user)

                <option
                    value="{{ $user->id }}"
                    @selected(
                        old(
                            'assigned_to',
                            $snag?->assigned_to
                        ) == $user->id
                    )>

                    {{ $user->name }}

                </option>

            @endforeach

        </select>

    </div>


    {{-- RAISED DATE --}}

    <div class="col-md-4">

        <label class="form-label">
            Raised Date
        </label>

        <input
            type="date"
            name="raised_date"
            class="form-control"
            value="{{ old(
                'raised_date',
                $snag?->raised_date?->format('Y-m-d')
                ?? now()->format('Y-m-d')
            ) }}">

    </div>


    {{-- DUE DATE --}}

    <div class="col-md-4">

        <label class="form-label">
            Due Date
        </label>

        <input
            type="date"
            name="due_date"
            class="form-control"
            value="{{ old(
                'due_date',
                $snag?->due_date?->format('Y-m-d')
            ) }}">

    </div>


    {{-- REMARKS --}}

    <div class="col-12">

        <label class="form-label">
            Remarks
        </label>

        <textarea
            name="remarks"
            class="form-control"
            rows="3">{{ old(
                'remarks',
                $snag?->remarks
            ) }}</textarea>

    </div>

</div>