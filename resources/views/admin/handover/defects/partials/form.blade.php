<div class="row g-3">

    {{-- =========================================================
         SOURCE SNAG
    ========================================================== --}}

    <div class="col-md-6">

        <label class="form-label">
            Source Snag
        </label>

        <select
            name="snag_id"
            class="form-select">

            <option value="">
                Select Existing Snag
            </option>

            @foreach($snags as $snag)

                <option
                    value="{{ $snag->id }}"
                    @selected(
                        old(
                            'snag_id',
                            $defect?->snag_id
                        ) == $snag->id
                    )>

                    {{ $snag->snag_no }}
                    -
                    {{ $snag->title }}

                </option>

            @endforeach

        </select>

        <small class="text-muted">
            Optional. Link this defect to an existing handover snag.
        </small>

    </div>


    {{-- =========================================================
         PROCUREMENT CONTRACT
    ========================================================== --}}

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
                            $defect?->procurement_contract_id
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
            Contracts are loaded from this project's Procurement chain.
        </small>

    </div>


    {{-- =========================================================
         CATEGORY
    ========================================================== --}}

    <div class="col-md-4">

        <label class="form-label">
            Category
        </label>

        <input
            type="text"
            name="category"
            class="form-control"
            value="{{ old(
                'category',
                $defect?->category
            ) }}"
            placeholder="Example: Finishing">

    </div>


    {{-- =========================================================
         DISCIPLINE
    ========================================================== --}}

    <div class="col-md-4">

        <label class="form-label">
            Discipline
        </label>

        <input
            type="text"
            name="discipline"
            class="form-control"
            value="{{ old(
                'discipline',
                $defect?->discipline
            ) }}"
            placeholder="Civil / MEP / Electrical">

    </div>


    {{-- =========================================================
         PRIORITY
    ========================================================== --}}

    <div class="col-md-4">

        <label class="form-label">

            Priority

            <span class="text-danger">
                *
            </span>

        </label>

        <select
            name="priority"
            class="form-select"
            required>

            @foreach($priorities as $priority)

                <option
                    value="{{ $priority }}"
                    @selected(
                        old(
                            'priority',
                            $defect?->priority ?? 'Medium'
                        ) === $priority
                    )>

                    {{ $priority }}

                </option>

            @endforeach

        </select>

    </div>


    {{-- =========================================================
         TITLE
    ========================================================== --}}

    <div class="col-12">

        <label class="form-label">

            Defect Title

            <span class="text-danger">
                *
            </span>

        </label>

        <input
            type="text"
            name="title"
            class="form-control"
            value="{{ old(
                'title',
                $defect?->title
            ) }}"
            placeholder="Enter defect title"
            required>

    </div>


    {{-- =========================================================
         DESCRIPTION
    ========================================================== --}}

    <div class="col-12">

        <label class="form-label">
            Description
        </label>

        <textarea
            name="description"
            class="form-control"
            rows="4"
            placeholder="Describe the defect in detail...">{{ old(
                'description',
                $defect?->description
            ) }}</textarea>

    </div>


    {{-- =========================================================
         LOCATION
    ========================================================== --}}

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
                $defect?->location
            ) }}"
            placeholder="Example: Main Lobby">

    </div>


    {{-- =========================================================
         BUILDING
    ========================================================== --}}

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
                $defect?->building
            ) }}">

    </div>


    {{-- =========================================================
         FLOOR
    ========================================================== --}}

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
                $defect?->floor
            ) }}">

    </div>


    {{-- =========================================================
         ZONE
    ========================================================== --}}

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
                $defect?->zone
            ) }}">

    </div>


    {{-- =========================================================
         UNIT
    ========================================================== --}}

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
                $defect?->unit
            ) }}">

    </div>


    {{-- =========================================================
         REPORTED DATE
    ========================================================== --}}

    <div class="col-md-4">

        <label class="form-label">
            Reported Date
        </label>

        <input
            type="date"
            name="reported_date"
            class="form-control"
            value="{{ old(
                'reported_date',
                $defect?->reported_date?->format('Y-m-d')
                ?? now()->format('Y-m-d')
            ) }}">

    </div>


    {{-- =========================================================
         DUE DATE
    ========================================================== --}}

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
                $defect?->due_date?->format('Y-m-d')
            ) }}">

    </div>


    {{-- =========================================================
         ASSIGN USER
    ========================================================== --}}

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
                            $defect?->assigned_to
                        ) == $user->id
                    )>

                    {{ $user->name }}

                </option>

            @endforeach

        </select>

    </div>


    {{-- =========================================================
         WARRANTY
    ========================================================== --}}

    <div class="col-md-4">

        <div class="form-check mt-4 pt-2">

            <input
                type="checkbox"
                name="warranty_related"
                value="1"
                class="form-check-input"
                id="warranty{{ $defect?->id ?? 'new' }}"
                @checked(
                    old(
                        'warranty_related',
                        $defect?->warranty_related ?? false
                    )
                )>

            <label
                class="form-check-label"
                for="warranty{{ $defect?->id ?? 'new' }}">

                Warranty Related

            </label>

        </div>

    </div>


    {{-- =========================================================
         ATTACHMENT
    ========================================================== --}}

    <div class="col-md-8">

        <label class="form-label">
            Attachment
        </label>

        <input
            type="file"
            name="attachment"
            class="form-control">

        <small class="text-muted">

            PDF, Word, Excel, PowerPoint or image.
            Maximum 50 MB.

        </small>

        @if($defect?->attachment_path)

            <div class="mt-2">

                <a
                    href="{{ asset(
                        'storage/' .
                        $defect->attachment_path
                    ) }}"
                    target="_blank">

                    <i class="ri-attachment-line"></i>

                    View Current Attachment

                </a>

            </div>

        @endif

    </div>


    {{-- =========================================================
         REMARKS
    ========================================================== --}}

    <div class="col-12">

        <label class="form-label">
            Remarks
        </label>

        <textarea
            name="remarks"
            class="form-control"
            rows="3"
            placeholder="Additional remarks...">{{ old(
                'remarks',
                $defect?->remarks
            ) }}</textarea>

    </div>

</div>