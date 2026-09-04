@php
    $toolboxTalk = $toolboxTalk ?? null;
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
            <strong>Toolbox Talk Details</strong>
        </div>

        <div class="card-body">

            <div class="row g-3">

                {{-- Toolbox Talk Number --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Toolbox Talk Number
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $toolboxTalkNumber ?? $toolboxTalk?->toolbox_talk_number }}"
                        readonly
                    >

                    <input
                        type="hidden"
                        name="toolbox_talk_number"
                        value="{{ $toolboxTalkNumber ?? $toolboxTalk?->toolbox_talk_number }}"
                    >

                </div>


                {{-- Title --}}
                <div class="col-md-8">

                    <label class="form-label">
                        Title
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="title"
                        class="form-control @error('title') is-invalid @enderror"
                        value="{{ old(
                            'title',
                            $toolboxTalk?->title
                        ) }}"
                        placeholder="Enter toolbox talk title"
                        required
                    >

                    @error('title')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Talk Date --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Talk Date
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="date"
                        name="talk_date"
                        class="form-control @error('talk_date') is-invalid @enderror"
                        value="{{ old(
                            'talk_date',
                            $toolboxTalk?->talk_date?->format('Y-m-d')
                        ) }}"
                        required
                    >

                    @error('talk_date')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Talk Time --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Talk Time
                    </label>

                    <input
                        type="time"
                        name="talk_time"
                        class="form-control @error('talk_time') is-invalid @enderror"
                        value="{{ old(
                            'talk_time',
                            $toolboxTalk?->talk_time
                        ) }}"
                    >

                    @error('talk_time')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Location --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Location
                    </label>

                    <input
                        type="text"
                        name="location"
                        class="form-control @error('location') is-invalid @enderror"
                        value="{{ old(
                            'location',
                            $toolboxTalk?->location
                        ) }}"
                        placeholder="Site / location"
                    >

                    @error('location')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Topic --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Topic
                    </label>

                    <input
                        type="text"
                        name="topic"
                        class="form-control @error('topic') is-invalid @enderror"
                        value="{{ old(
                            'topic',
                            $toolboxTalk?->topic
                        ) }}"
                        placeholder="Safety topic discussed"
                    >

                    @error('topic')
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
                            'Completed',
                            'Cancelled',
                        ] as $status)

                            <option
                                value="{{ $status }}"
                                @selected(
                                    old(
                                        'status',
                                        $toolboxTalk?->status ?? 'Draft'
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
                                        $toolboxTalk?->conducted_by
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
                            $toolboxTalk?->conducted_by_name
                        ) }}"
                        placeholder="External trainer / other person"
                    >

                    @error('conducted_by_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Objectives --}}
                <div class="col-12">

                    <label class="form-label">
                        Objectives
                    </label>

                    <textarea
                        name="objectives"
                        rows="4"
                        class="form-control @error('objectives') is-invalid @enderror"
                        placeholder="Training objectives"
                    >{{ old(
                        'objectives',
                        $toolboxTalk?->objectives
                    ) }}</textarea>

                    @error('objectives')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Discussion Points --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Discussion Points
                    </label>

                    <textarea
                        name="discussion_points"
                        rows="5"
                        class="form-control @error('discussion_points') is-invalid @enderror"
                        placeholder="Points discussed during the toolbox talk"
                    >{{ old(
                        'discussion_points',
                        $toolboxTalk?->discussion_points
                    ) }}</textarea>

                    @error('discussion_points')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Safety Instructions --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Safety Instructions
                    </label>

                    <textarea
                        name="safety_instructions"
                        rows="5"
                        class="form-control @error('safety_instructions') is-invalid @enderror"
                        placeholder="Safety instructions communicated"
                    >{{ old(
                        'safety_instructions',
                        $toolboxTalk?->safety_instructions
                    ) }}</textarea>

                    @error('safety_instructions')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Hazards Discussed --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Hazards Discussed
                    </label>

                    <textarea
                        name="hazards_discussed"
                        rows="4"
                        class="form-control @error('hazards_discussed') is-invalid @enderror"
                        placeholder="Hazards discussed"
                    >{{ old(
                        'hazards_discussed',
                        $toolboxTalk?->hazards_discussed
                    ) }}</textarea>

                    @error('hazards_discussed')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Precautions --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Precautions
                    </label>

                    <textarea
                        name="precautions"
                        rows="4"
                        class="form-control @error('precautions') is-invalid @enderror"
                        placeholder="Precautions / control measures"
                    >{{ old(
                        'precautions',
                        $toolboxTalk?->precautions
                    ) }}</textarea>

                    @error('precautions')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Remarks --}}
                <div class="col-12">

                    <label class="form-label">
                        Remarks
                    </label>

                    <textarea
                        name="remarks"
                        rows="3"
                        class="form-control @error('remarks') is-invalid @enderror"
                        placeholder="Additional remarks"
                    >{{ old(
                        'remarks',
                        $toolboxTalk?->remarks
                    ) }}</textarea>

                    @error('remarks')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

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