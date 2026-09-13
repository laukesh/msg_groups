<div class="row g-3">

    <div class="col-md-6">

        <label class="form-label">
            Work Order
        </label>

        <select
            name="construction_work_order_id"
            class="form-select"
        >

            <option value="">
                Select Work Order
            </option>

            @foreach($workOrders as $workOrder)

                <option
                    value="{{ $workOrder->id }}"
                    @selected(
                        old(
                            'construction_work_order_id',
                            $issue?->construction_work_order_id
                        ) == $workOrder->id
                    )
                >

                    {{ $workOrder->work_order_number }}
                    —
                    {{ $workOrder->work_order_title }}

                </option>

            @endforeach

        </select>

    </div>


    <div class="col-md-6">

        <label class="form-label">
            Related Progress Update
        </label>

        <select
            name="construction_progress_update_id"
            class="form-select"
        >

            <option value="">
                None
            </option>

            @foreach($progressUpdates as $progress)

                <option
                    value="{{ $progress->id }}"
                    @selected(
                        old(
                            'construction_progress_update_id',
                            $issue?->construction_progress_update_id
                        ) == $progress->id
                    )
                >

                    {{ $progress->progress_number }}
                    —
                    {{ $progress->progress_date?->format('d-m-Y') }}
                    —
                    {{ $progress->progress_percentage }}%

                </option>

            @endforeach

        </select>

    </div>


    <div class="col-md-3">

        <label class="form-label">
            Issue Date
            <span class="text-danger">*</span>
        </label>

        <input
            type="date"
            name="issue_date"
            class="form-control"
            required
            value="{{ old(
                'issue_date',
                $issue?->issue_date?->format('Y-m-d')
                ?? now()->format('Y-m-d')
            ) }}"
        >

    </div>


    <div class="col-md-3">

        <label class="form-label">
            Issue Type
            <span class="text-danger">*</span>
        </label>

        <select
            name="issue_type"
            class="form-select"
            required
        >

            @foreach([
                'Site Issue',
                'RFI',
                'Technical Query',
                'Quality Issue',
                'Safety Issue',
                'Material Issue',
                'Design Issue',
            ] as $type)

                <option
                    value="{{ $type }}"
                    @selected(
                        old(
                            'issue_type',
                            $issue?->issue_type
                            ?? 'Site Issue'
                        ) === $type
                    )
                >
                    {{ $type }}
                </option>

            @endforeach

        </select>

    </div>


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
                $issue?->category
            ) }}"
            placeholder="e.g. Civil / Electrical"
        >

    </div>


    <div class="col-md-3">

        <label class="form-label">
            Priority
            <span class="text-danger">*</span>
        </label>

        <select
            name="priority"
            class="form-select"
            required
        >

            @foreach([
                'Low',
                'Medium',
                'High',
                'Critical',
            ] as $priority)

                <option
                    value="{{ $priority }}"
                    @selected(
                        old(
                            'priority',
                            $issue?->priority
                            ?? 'Medium'
                        ) === $priority
                    )
                >
                    {{ $priority }}
                </option>

            @endforeach

        </select>

    </div>


    <div class="col-md-12">

        <label class="form-label">
            Title
            <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="title"
            class="form-control"
            required
            value="{{ old(
                'title',
                $issue?->title
            ) }}"
            placeholder="Enter issue / RFI title"
        >

    </div>


    <div class="col-md-12">

        <label class="form-label">
            Description
        </label>

        <textarea
            name="description"
            rows="4"
            class="form-control"
        >{{ old(
            'description',
            $issue?->description
        ) }}</textarea>

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Raised By
        </label>

        <select
            name="raised_by"
            class="form-select"
        >

            <option value="">
                Current User
            </option>

            @foreach($users as $user)

                <option
                    value="{{ $user->id }}"
                    @selected(
                        old(
                            'raised_by',
                            $issue?->raised_by
                        ) == $user->id
                    )
                >
                    {{ $user->name }}
                </option>

            @endforeach

        </select>

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Assigned To
        </label>

        <select
            name="assigned_to"
            class="form-select"
        >

            <option value="">
                Select User
            </option>

            @foreach($users as $user)

                <option
                    value="{{ $user->id }}"
                    @selected(
                        old(
                            'assigned_to',
                            $issue?->assigned_to
                        ) == $user->id
                    )
                >
                    {{ $user->name }}
                </option>

            @endforeach

        </select>

    </div>


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
                $issue?->due_date?->format('Y-m-d')
            ) }}"
        >

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Status
            <span class="text-danger">*</span>
        </label>

        <select
            name="status"
            class="form-select"
            required
        >

            @foreach([
                'Open',
                'In Progress',
                'Reopened',
                'Resolved',
                'Closed',
            ] as $status)

                <option
                    value="{{ $status }}"
                    @selected(
                        old(
                            'status',
                            $issue?->status
                            ?? 'Open'
                        ) === $status
                    )
                >
                    {{ $status }}
                </option>

            @endforeach

        </select>

    </div>


    <div class="col-md-8">

        <label class="form-label">
            Resolution Date
        </label>

        <input
            type="date"
            name="resolution_date"
            class="form-control"
            value="{{ old(
                'resolution_date',
                $issue?->resolution_date?->format('Y-m-d')
            ) }}"
        >

    </div>


    <div class="col-md-12">

        <label class="form-label">
            Corrective Action
        </label>

        <textarea
            name="corrective_action"
            rows="4"
            class="form-control"
        >{{ old(
            'corrective_action',
            $issue?->corrective_action
        ) }}</textarea>

    </div>


    <div class="col-md-12">

        <label class="form-label">
            Resolution
        </label>

        <textarea
            name="resolution"
            rows="4"
            class="form-control"
        >{{ old(
            'resolution',
            $issue?->resolution
        ) }}</textarea>

    </div>


    <div class="col-md-12">

        <label class="form-label">
            Remarks
        </label>

        <textarea
            name="remarks"
            rows="3"
            class="form-control"
        >{{ old(
            'remarks',
            $issue?->remarks
        ) }}</textarea>

    </div>

</div>