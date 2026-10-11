@php
    $meeting = $meeting ?? null;
    $users = $users ?? collect();
@endphp

<form
    method="POST"
    action="{{ $formAction }}"
>

    @csrf

    @if($formMethod)
        @method($formMethod)
    @endif


    <div class="card">

        <div class="card-header">
            <strong>Safety Meeting Details</strong>
        </div>


        <div class="card-body">

            <div class="row g-3">

                {{-- Meeting Number --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Meeting Number
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $meetingNumber ?? $meeting?->meeting_number }}"
                        readonly
                    >

                    <input
                        type="hidden"
                        name="meeting_number"
                        value="{{ $meetingNumber ?? $meeting?->meeting_number }}"
                    >

                </div>


                {{-- Meeting Date --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Meeting Date
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="date"
                        name="meeting_date"
                        class="form-control @error('meeting_date') is-invalid @enderror"
                        value="{{ old(
                            'meeting_date',
                            $meeting?->meeting_date?->format('Y-m-d')
                        ) }}"
                        required
                    >

                    @error('meeting_date')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Meeting Time --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Meeting Time
                    </label>

                    <input
                        type="time"
                        name="meeting_time"
                        class="form-control @error('meeting_time') is-invalid @enderror"
                        value="{{ old(
                            'meeting_time',
                            $meeting?->meeting_time
                        ) }}"
                    >

                    @error('meeting_time')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Meeting Type --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Meeting Type
                        <span class="text-danger">*</span>
                    </label>

                    <select
                        name="meeting_type"
                        class="form-select @error('meeting_type') is-invalid @enderror"
                        required
                    >

                        @foreach([
                            'Toolbox Talk',
                            'Safety Meeting',
                            'Pre-Start Meeting',
                            'Emergency Safety Meeting',
                            'Weekly Safety Meeting',
                            'Monthly Safety Meeting',
                            'Training Session',
                            'Other',
                        ] as $type)

                            <option
                                value="{{ $type }}"
                                @selected(
                                    old(
                                        'meeting_type',
                                        $meeting?->meeting_type
                                            ?? 'Toolbox Talk'
                                    ) === $type
                                )
                            >
                                {{ $type }}
                            </option>

                        @endforeach

                    </select>

                    @error('meeting_type')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Status --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Status
                        <span class="text-danger">*</span>
                    </label>

                    <select
                        name="status"
                        class="form-select @error('status') is-invalid @enderror"
                        required
                    >

                        @foreach([
                            'Draft',
                            'Scheduled',
                            'Completed',
                            'Cancelled',
                        ] as $status)

                            <option
                                value="{{ $status }}"
                                @selected(
                                    old(
                                        'status',
                                        $meeting?->status ?? 'Draft'
                                    ) === $status
                                )
                            >
                                {{ $status }}
                            </option>

                        @endforeach

                    </select>

                    @error('status')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Title --}}
                <div class="col-12">

                    <label class="form-label">
                        Topic / Title
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="title"
                        class="form-control @error('title') is-invalid @enderror"
                        value="{{ old(
                            'title',
                            $meeting?->title
                        ) }}"
                        placeholder="Enter safety meeting topic"
                        required
                    >

                    @error('title')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Location --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Location
                    </label>

                    <input
                        type="text"
                        name="location"
                        class="form-control @error('location') is-invalid @enderror"
                        value="{{ old(
                            'location',
                            $meeting?->location
                        ) }}"
                        placeholder="Site / work location"
                    >

                    @error('location')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Conducted By --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Conducted By
                    </label>

                    <select
                        name="conducted_by"
                        class="form-select @error('conducted_by') is-invalid @enderror"
                    >

                        <option value="">
                            -- Select User --
                        </option>

                        @foreach($users as $user)

                            <option
                                value="{{ $user->id }}"
                                @selected(
                                    old(
                                        'conducted_by',
                                        $meeting?->conducted_by
                                    ) == $user->id
                                )
                            >
                                {{ $user->name }}
                            </option>

                        @endforeach

                    </select>

                    @error('conducted_by')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Conducted By Name --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Conducted By Name
                    </label>

                    <input
                        type="text"
                        name="conducted_by_name"
                        class="form-control @error('conducted_by_name') is-invalid @enderror"
                        value="{{ old(
                            'conducted_by_name',
                            $meeting?->conducted_by_name
                        ) }}"
                        placeholder="Manual name if not selected above"
                    >

                    @error('conducted_by_name')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Next Meeting Date --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Next Meeting Date
                    </label>

                    <input
                        type="date"
                        name="next_meeting_date"
                        class="form-control @error('next_meeting_date') is-invalid @enderror"
                        value="{{ old(
                            'next_meeting_date',
                            $meeting?->next_meeting_date?->format('Y-m-d')
                        ) }}"
                    >

                    @error('next_meeting_date')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Meeting Objective --}}
                <div class="col-12">

                    <label class="form-label">
                        Meeting Objective
                    </label>

                    <textarea
                        name="meeting_objective"
                        rows="3"
                        class="form-control"
                        placeholder="Purpose and objective of the meeting"
                    >{{ old(
                        'meeting_objective',
                        $meeting?->meeting_objective
                    ) }}</textarea>

                </div>


                {{-- Agenda --}}
                <div class="col-12">

                    <label class="form-label">
                        Agenda
                    </label>

                    <textarea
                        name="agenda"
                        rows="4"
                        class="form-control"
                        placeholder="Meeting agenda / topics to be discussed"
                    >{{ old(
                        'agenda',
                        $meeting?->agenda
                    ) }}</textarea>

                </div>


                {{-- Discussion Points --}}
                <div class="col-12">

                    <label class="form-label">
                        Discussion Points
                    </label>

                    <textarea
                        name="discussion_points"
                        rows="5"
                        class="form-control"
                        placeholder="Key points discussed during the meeting"
                    >{{ old(
                        'discussion_points',
                        $meeting?->discussion_points
                    ) }}</textarea>

                </div>


                {{-- Safety Instructions --}}
                <div class="col-12">

                    <label class="form-label">
                        Safety Instructions
                    </label>

                    <textarea
                        name="safety_instructions"
                        rows="5"
                        class="form-control"
                        placeholder="Safety instructions communicated to participants"
                    >{{ old(
                        'safety_instructions',
                        $meeting?->safety_instructions
                    ) }}</textarea>

                </div>


                {{-- Actions / Commitments --}}
                <div class="col-12">

                    <label class="form-label">
                        Actions / Commitments
                    </label>

                    <textarea
                        name="actions_commitments"
                        rows="5"
                        class="form-control"
                        placeholder="Actions, commitments and responsibilities arising from the meeting"
                    >{{ old(
                        'actions_commitments',
                        $meeting?->actions_commitments
                    ) }}</textarea>

                </div>


                {{-- Remarks --}}
                <div class="col-12">

                    <label class="form-label">
                        Remarks
                    </label>

                    <textarea
                        name="remarks"
                        rows="3"
                        class="form-control"
                        placeholder="Additional remarks"
                    >{{ old(
                        'remarks',
                        $meeting?->remarks
                    ) }}</textarea>

                </div>

            </div>

        </div>


        <div class="card-footer d-flex justify-content-end gap-2">

            <a
                href="{{ $cancelUrl }}"
                class="btn btn-outline-secondary"
            >
                Cancel
            </a>


            <button
                type="submit"
                class="btn btn-primary"
            >

                <i class="bi bi-save me-1"></i>

                {{ $submitLabel }}

            </button>

        </div>

    </div>

</form>