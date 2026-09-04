<form
    method="POST"
    action="{{ $action }}"
>

    @csrf

    @if($method)
        @method($method)
    @endif


    {{-- =========================================================
        BASIC INFORMATION
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Safety Observation Information</strong>
        </div>

        <div class="card-body">

            <div class="row g-3">

                {{-- Observation Number --}}

                <div class="col-md-4">

                    <label class="form-label">
                        Observation Number
                    </label>

                    @if($observation)

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $observation->observation_number }}"
                            readonly
                        >

                    @else

                        <input
                            type="text"
                            class="form-control"
                            value="Auto Generated"
                            readonly
                        >

                        <small class="text-muted">
                            Observation number will be generated automatically.
                        </small>

                    @endif

                </div>


                {{-- Date --}}

                <div class="col-md-4">

                    <label class="form-label">
                        Observation Date *
                    </label>

                    <input
                        type="date"
                        name="observation_date"
                        class="form-control"
                        value="{{ old(
                            'observation_date',
                            isset($observation->observation_date)
                                ? $observation->observation_date->format('Y-m-d')
                                : now()->format('Y-m-d')
                        ) }}"
                        required
                    >

                </div>


                {{-- Time --}}

                <div class="col-md-4">

                    <label class="form-label">
                        Observation Time
                    </label>

                    <input
                        type="time"
                        name="observation_time"
                        class="form-control"
                        value="{{ old(
                            'observation_time',
                            $observation->observation_time ?? ''
                        ) }}"
                    >

                </div>


                {{-- Location --}}

                <div class="col-md-6">

                    <label class="form-label">
                        Location *
                    </label>

                    <input
                        type="text"
                        name="location"
                        class="form-control"
                        value="{{ old(
                            'location',
                            $observation->location ?? ''
                        ) }}"
                        placeholder="Example: Block A - Basement"
                        required
                    >

                </div>


                {{-- Category --}}

                <div class="col-md-3">

                    <label class="form-label">
                        Category *
                    </label>

                    <select
                        name="category"
                        class="form-select"
                        required
                    >

                        <option value="">
                            Select Category
                        </option>

                        @foreach([
                            'PPE',
                            'Working at Height',
                            'Electrical Safety',
                            'Fire Safety',
                            'Scaffolding',
                            'Excavation',
                            'Housekeeping',
                            'Lifting & Material Handling',
                            'Machinery',
                            'Traffic / Vehicle',
                            'Environmental',
                            'Unsafe Act',
                            'Unsafe Condition',
                            'Other'
                        ] as $category)

                            <option
                                value="{{ $category }}"
                                @selected(
                                    old(
                                        'category',
                                        $observation->category ?? ''
                                    ) === $category
                                )
                            >
                                {{ $category }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Severity --}}

                <div class="col-md-3">

                    <label class="form-label">
                        Severity *
                    </label>

                    <select
                        name="severity"
                        class="form-select"
                        required
                    >

                        @foreach([
                            'Low',
                            'Medium',
                            'High',
                            'Critical'
                        ] as $severity)

                            <option
                                value="{{ $severity }}"
                                @selected(
                                    old(
                                        'severity',
                                        $observation->severity ?? 'Low'
                                    ) === $severity
                                )
                            >
                                {{ $severity }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        CONTRACTOR
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Contractor / Responsibility</strong>
        </div>

        <div class="card-body">

            <div class="row g-3">

                {{-- Contract --}}

                <div class="col-md-6">

                    <label class="form-label">
                        Contractor / Contract
                    </label>

                    <select
                        name="procurement_contract_id"
                        class="form-select"
                    >

                        <option value="">
                            Select Contractor / Contract
                        </option>

                        @foreach($contracts as $contract)

                            <option
                                value="{{ $contract->id }}"
                                @selected(
                                    (string) old(
                                        'procurement_contract_id',
                                        $observation->procurement_contract_id ?? ''
                                    ) === (string) $contract->id
                                )
                            >

                                {{ $contract->contract_number }}

                                -

                                {{ $contract->bidder_name ?? '—' }}

                            </option>

                        @endforeach

                    </select>

                    <small class="text-muted">
                        Contractor is linked through the procurement contract.
                    </small>

                </div>


                {{-- Reported By --}}

                <div class="col-md-3">

                    <label class="form-label">
                        Reported By
                    </label>

                    <select
                        name="reported_by"
                        class="form-select"
                    >

                        <option value="">
                            Select User
                        </option>

                        @foreach($users as $user)

                            <option
                                value="{{ $user->id }}"
                                @selected(
                                    (string) old(
                                        'reported_by',
                                        $observation->reported_by ?? ''
                                    ) === (string) $user->id
                                )
                            >
                                {{ $user->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Reporter Name --}}

                <div class="col-md-3">

                    <label class="form-label">
                        Reporter Name
                    </label>

                    <input
                        type="text"
                        name="reported_by_name"
                        class="form-control"
                        value="{{ old(
                            'reported_by_name',
                            $observation->reported_by_name ?? ''
                        ) }}"
                        placeholder="Optional"
                    >

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        OBSERVATION
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Observation Details</strong>
        </div>

        <div class="card-body">

            <label class="form-label">
                Description *
            </label>

            <textarea
                name="description"
                rows="6"
                class="form-control"
                required
                placeholder="Describe the unsafe act, unsafe condition or safety concern..."
            >{{ old(
                'description',
                $observation->description ?? ''
            ) }}</textarea>

        </div>

    </div>


    {{-- =========================================================
        ACTION
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Immediate & Corrective Action</strong>
        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-6">

                    <label class="form-label">
                        Immediate Action Taken
                    </label>

                    <textarea
                        name="immediate_action"
                        rows="5"
                        class="form-control"
                        placeholder="Describe immediate action taken..."
                    >{{ old(
                        'immediate_action',
                        $observation->immediate_action ?? ''
                    ) }}</textarea>

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Corrective Action
                    </label>

                    <textarea
                        name="corrective_action"
                        rows="5"
                        class="form-control"
                        placeholder="Describe corrective action required..."
                    >{{ old(
                        'corrective_action',
                        $observation->corrective_action ?? ''
                    ) }}</textarea>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        RESPONSIBILITY & STATUS
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Action Responsibility</strong>
        </div>

        <div class="card-body">

            <div class="row g-3">

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
                            isset($observation->due_date)
                                ? $observation->due_date->format('Y-m-d')
                                : ''
                        ) }}"
                    >

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Responsible User
                    </label>

                    <select
                        name="responsible_user_id"
                        class="form-select"
                    >

                        <option value="">
                            Select Responsible User
                        </option>

                        @foreach($users as $user)

                            <option
                                value="{{ $user->id }}"
                                @selected(
                                    (string) old(
                                        'responsible_user_id',
                                        $observation->responsible_user_id ?? ''
                                    ) === (string) $user->id
                                )
                            >
                                {{ $user->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Status *
                    </label>

                    <select
                        name="status"
                        class="form-select"
                        required
                    >

                        @foreach([
                            'Open',
                            'In Progress',
                            'Resolved',
                            'Verified',
                            'Closed',
                            'Rejected'
                        ] as $status)

                            <option
                                value="{{ $status }}"
                                @selected(
                                    old(
                                        'status',
                                        $observation->status ?? 'Open'
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
        CLOSURE
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Closure & Remarks</strong>
        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-4">

                    <label class="form-label">
                        Closed Date
                    </label>

                    <input
                        type="date"
                        name="closed_date"
                        class="form-control"
                        value="{{ old(
                            'closed_date',
                            isset($observation->closed_date)
                                ? $observation->closed_date->format('Y-m-d')
                                : ''
                        ) }}"
                    >

                </div>


                <div class="col-md-8">

                    <label class="form-label">
                        Closure Remarks
                    </label>

                    <textarea
                        name="closure_remarks"
                        rows="3"
                        class="form-control"
                    >{{ old(
                        'closure_remarks',
                        $observation->closure_remarks ?? ''
                    ) }}</textarea>

                </div>


                <div class="col-md-12">

                    <label class="form-label">
                        Remarks
                    </label>

                    <textarea
                        name="remarks"
                        rows="4"
                        class="form-control"
                    >{{ old(
                        'remarks',
                        $observation->remarks ?? ''
                    ) }}</textarea>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        BUTTONS
    ========================================================== --}}

    <div class="d-flex justify-content-end">

        <a
            href="{{ route(
                'admin.projects.construction.hse.observations.index',
                [
                    'project' => $project
                ]
            ) }}"
            class="btn btn-secondary me-2"
        >
            Cancel
        </a>


        <button
            type="submit"
            class="btn btn-primary"
        >

            @if($method)

                Update Observation

            @else

                Save Observation

            @endif

        </button>

    </div>

</form>