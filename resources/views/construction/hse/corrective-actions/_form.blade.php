<form
    method="POST"
    action="{{ $action }}"
>

    @csrf

    @if(!empty($method))
        @method($method)
    @endif


    {{-- =========================================================
        ACTION INFORMATION
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Corrective Action Information</strong>
        </div>

        <div class="card-body">

            <div class="row">

                {{-- Observation --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Observation
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $observation->observation_number }}"
                        readonly
                    >

                </div>


                {{-- Action Number --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Action Number
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $correctiveAction->action_number ?? 'Auto Generated' }}"
                        readonly
                    >

                    <div class="form-text">
                        Action number will be generated automatically.
                    </div>

                </div>


                {{-- Action Description --}}

                <div class="col-md-12 mb-3">

                    <label class="form-label">
                        Corrective Action *
                    </label>

                    <textarea
                        name="action_description"
                        rows="5"
                        class="form-control @error('action_description') is-invalid @enderror"
                        required
                        placeholder="Describe the corrective action required..."
                    >{{ old(
                        'action_description',
                        $correctiveAction->action_description ?? ''
                    ) }}</textarea>

                    @error('action_description')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        RESPONSIBILITY
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Responsibility</strong>
        </div>

        <div class="card-body">

            <div class="row">

                {{-- Responsible User --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Responsible User
                    </label>

                    <select
                        name="responsible_user_id"
                        class="form-select @error('responsible_user_id') is-invalid @enderror"
                    >

                        <option value="">
                            -- Select User --
                        </option>

                        @foreach($users as $user)

                            <option
                                value="{{ $user->id }}"
                                @selected(
                                    (string) old(
                                        'responsible_user_id',
                                        $correctiveAction->responsible_user_id ?? ''
                                    )
                                    ===
                                    (string) $user->id
                                )
                            >
                                {{ $user->name }}

                                @if(!empty($user->email))
                                    - {{ $user->email }}
                                @endif

                            </option>

                        @endforeach

                    </select>

                    @error('responsible_user_id')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Responsible Name --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Responsible Person / Contractor
                    </label>

                    <input
                        type="text"
                        name="responsible_name"
                        class="form-control @error('responsible_name') is-invalid @enderror"
                        value="{{ old(
                            'responsible_name',
                            $correctiveAction->responsible_name ?? ''
                        ) }}"
                        placeholder="Enter responsible person or contractor"
                    >

                    @error('responsible_name')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        DUE DATE
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Target Date</strong>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Due Date
                    </label>

                    <input
                        type="date"
                        name="due_date"
                        class="form-control @error('due_date') is-invalid @enderror"
                        value="{{ old(
                            'due_date',
                            isset($correctiveAction->due_date)
                                ? $correctiveAction->due_date->format('Y-m-d')
                                : ''
                        ) }}"
                    >

                    @error('due_date')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                @if(!empty($correctiveAction))

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Current Status
                        </label>

                        <div class="mt-2">

                            @switch($correctiveAction->status)

                                @case('Open')

                                    <span class="badge bg-secondary">
                                        Open
                                    </span>

                                    @break

                                @case('In Progress')

                                    <span class="badge bg-warning text-dark">
                                        In Progress
                                    </span>

                                    @break

                                @case('Resolved')

                                    <span class="badge bg-primary">
                                        Resolved
                                    </span>

                                    @break

                                @case('Verified')

                                    <span class="badge bg-success">
                                        Verified
                                    </span>

                                    @break

                                @case('Closed')

                                    <span class="badge bg-dark">
                                        Closed
                                    </span>

                                    @break

                                @default

                                    <span class="badge bg-secondary">
                                        {{ $correctiveAction->status }}
                                    </span>

                            @endswitch

                        </div>

                        <div class="form-text">
                            Status is controlled by the workflow.
                        </div>

                    </div>

                @endif

            </div>

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
                class="form-control @error('remarks') is-invalid @enderror"
                placeholder="Enter any additional remarks..."
            >{{ old(
                'remarks',
                $correctiveAction->remarks ?? ''
            ) }}</textarea>

            @error('remarks')

                <div class="invalid-feedback">
                    {{ $message }}
                </div>

            @enderror

        </div>

    </div>


    {{-- =========================================================
        BUTTONS
    ========================================================== --}}

    <div class="d-flex justify-content-end">

        <a
            href="{{ route(
                'admin.projects.construction.hse.observations.corrective-actions.index',
                [
                    'project' => $project,
                    'observation' => $observation,
                ]
            ) }}"
            class="btn btn-secondary me-2"
        >
            <i class="bi bi-x-lg me-1"></i>
            Cancel
        </a>


        <button
            type="submit"
            class="btn btn-primary"
        >

            @if(!empty($method))

                <i class="bi bi-check-lg me-1"></i>
                Update Corrective Action

            @else

                <i class="bi bi-plus-lg me-1"></i>
                Create Corrective Action

            @endif

        </button>

    </div>

</form>