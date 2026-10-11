<div class="row g-3">

    {{-- Basic --}}
    <div class="col-md-4">

        <label class="form-label">
            Submittal Date <span class="text-danger">*</span>
        </label>

        <input
            type="date"
            name="submittal_date"
            value="{{ old(
                'submittal_date',
                optional($submittal ?? null)->submittal_date?->format('Y-m-d')
                    ?? now()->format('Y-m-d')
            ) }}"
            class="form-control @error('submittal_date') is-invalid @enderror"
            required
        >

        @error('submittal_date')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Submittal Type
        </label>

        <select
            name="submittal_type"
            class="form-select"
        >

            <option value="">
                Select Type
            </option>

            @foreach([
                'Material',
                'Shop Drawing',
                'Product Data',
                'Method Statement',
                'Sample',
                'Equipment',
                'Technical Document',
                'Other'
            ] as $type)

                <option
                    value="{{ $type }}"
                    @selected(
                        old(
                            'submittal_type',
                            $submittal->submittal_type ?? ''
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
            Priority <span class="text-danger">*</span>
        </label>

        <select
            name="priority"
            class="form-select"
            required
        >

            @foreach([
                'Low',
                'Normal',
                'High',
                'Critical'
            ] as $priority)

                <option
                    value="{{ $priority }}"
                    @selected(
                        old(
                            'priority',
                            $submittal->priority ?? 'Normal'
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
            Title <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="title"
            value="{{ old(
                'title',
                $submittal->title ?? ''
            ) }}"
            class="form-control @error('title') is-invalid @enderror"
            required
        >

        @error('title')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

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
            $submittal->description ?? ''
        ) }}</textarea>

    </div>

</div>


<hr class="my-4">


<h6 class="fw-bold mb-3">
    Construction Details
</h6>


<div class="row g-3">

    {{-- Contract --}}
    <div class="col-md-6">

        <label class="form-label">
            Procurement Contract
        </label>

        <select
            name="procurement_contract_id"
            class="form-select"
        >

            <option value="">
                Select Contract
            </option>

            @foreach($contracts as $contract)

                <option
                    value="{{ $contract->id }}"
                    @selected(
                        old(
                            'procurement_contract_id',
                            $submittal->procurement_contract_id ?? ''
                        ) == $contract->id
                    )
                >

                    {{ $contract->contract_number }}

                    @if($contract->contract_title)
                        - {{ $contract->contract_title }}
                    @endif

                    @if($contract->bidder)
                        - {{ $contract->bidder->company_name }}
                    @endif

                </option>

            @endforeach

        </select>

    </div>


    {{-- Work Order --}}
    <div class="col-md-6">

        <label class="form-label">
            Work Order
        </label>

        <select
            name="work_order_id"
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
                            'work_order_id',
                            $submittal->work_order_id ?? ''
                        ) == $workOrder->id
                    )
                >

                    {{ $workOrder->work_order_number }}

                    @if($workOrder->work_order_title)
                        - {{ $workOrder->work_order_title }}
                    @endif

                </option>

            @endforeach

        </select>

    </div>


    {{-- Consultant --}}
    <div class="col-md-6">

        <label class="form-label">
            Consultant
        </label>

        <select
            name="consultant_id"
            class="form-select @error('consultant_id') is-invalid @enderror"
        >

            <option value="">
                Select Consultant
            </option>

            @foreach($consultants as $consultant)

                <option
                    value="{{ $consultant->id }}"
                    @selected(
                        old(
                            'consultant_id',
                            $submittal->consultant_id ?? ''
                        ) == $consultant->id
                    )
                >

                    {{ $consultant->company_name }}

                    @if($consultant->consultant_name)
                        - {{ $consultant->consultant_name }}
                    @endif

                </option>

            @endforeach

        </select>

        @error('consultant_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Schedule Activity --}}
    <div class="col-md-6">

        <label class="form-label">
            Schedule Activity
        </label>

        <select
            name="schedule_activity_id"
            class="form-select"
        >

            <option value="">
                Select Activity
            </option>

            @foreach($scheduleActivities as $activity)

                <option
                    value="{{ $activity->id }}"
                    @selected(
                        old(
                            'schedule_activity_id',
                            $submittal->schedule_activity_id ?? ''
                        ) == $activity->id
                    )
                >

                    {{ $activity->activity_name
                        ?? $activity->name
                        ?? ('Activity #' . $activity->id)
                    }}

                </option>

            @endforeach

        </select>

    </div>


    <div class="col-md-12">

        <label class="form-label">
            Location
        </label>

        <input
            type="text"
            name="location"
            value="{{ old(
                'location',
                $submittal->location ?? ''
            ) }}"
            class="form-control"
        >

    </div>

</div>


<hr class="my-4">


<h6 class="fw-bold mb-3">
    Submission & Document Details
</h6>


<div class="row g-3">

    <div class="col-md-4">

        <label class="form-label">
            Submitted By
        </label>

        <select
            name="submitted_by"
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
                            'submitted_by',
                            $submittal->submitted_by ?? ''
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
            Submitted To
        </label>

        <select
            name="submitted_to"
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
                            'submitted_to',
                            $submittal->submitted_to ?? ''
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
            Revision Number
        </label>

        <input
            type="text"
            name="revision_number"
            value="{{ old(
                'revision_number',
                $submittal->revision_number ?? 'Rev-00'
            ) }}"
            class="form-control"
        >

    </div>


    <div class="col-md-12">

        <label class="form-label">
            Document Reference
        </label>

        <input
            type="text"
            name="document_reference"
            value="{{ old(
                'document_reference',
                $submittal->document_reference ?? ''
            ) }}"
            class="form-control"
        >

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Submission Date
        </label>

        <input
            type="date"
            name="submission_date"
            value="{{ old(
                'submission_date',
                optional($submittal ?? null)->submission_date?->format('Y-m-d')
            ) }}"
            class="form-control"
        >

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Review Due Date
        </label>

        <input
            type="date"
            name="review_due_date"
            value="{{ old(
                'review_due_date',
                optional($submittal ?? null)->review_due_date?->format('Y-m-d')
            ) }}"
            class="form-control"
        >

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Review Date
        </label>

        <input
            type="date"
            name="review_date"
            value="{{ old(
                'review_date',
                optional($submittal ?? null)->review_date?->format('Y-m-d')
            ) }}"
            class="form-control"
        >

    </div>

</div>


<hr class="my-4">


<h6 class="fw-bold mb-3">
    Review Information
</h6>


<div class="row g-3">

    <div class="col-md-12">

        <label class="form-label">
            Review Comments
        </label>

        <textarea
            name="review_comments"
            rows="4"
            class="form-control"
        >{{ old(
            'review_comments',
            $submittal->review_comments ?? ''
        ) }}</textarea>

    </div>


    <div class="col-md-12">

        <label class="form-label">
            Response
        </label>

        <textarea
            name="response"
            rows="4"
            class="form-control"
        >{{ old(
            'response',
            $submittal->response ?? ''
        ) }}</textarea>

    </div>


    <div class="col-md-6">

        <label class="form-label">
            Approval Date
        </label>

        <input
            type="date"
            name="approval_date"
            value="{{ old(
                'approval_date',
                optional($submittal ?? null)->approval_date?->format('Y-m-d')
            ) }}"
            class="form-control"
        >

    </div>


    <div class="col-md-6">

        <label class="form-label">
            Approved By
        </label>

        <select
            name="approved_by"
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
                            'approved_by',
                            $submittal->approved_by ?? ''
                        ) == $user->id
                    )
                >
                    {{ $user->name }}
                </option>

            @endforeach

        </select>

    </div>

</div>


<hr class="my-4">


<div>

    <label class="form-label">
        Remarks
    </label>

    <textarea
        name="remarks"
        rows="4"
        class="form-control"
    >{{ old(
        'remarks',
        $submittal->remarks ?? ''
    ) }}</textarea>

</div>