{{-- ========================================================= --}}
{{-- ATTENDEES --}}
{{-- ========================================================= --}}

<div class="col-md-4">

    <div class="card h-100">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-start">

                <h6 class="fw-semibold mb-0">
                    Attendees
                </h6>

                @if(isset($meeting->attendees_count))

                    <span class="badge bg-primary">
                        {{ $meeting->attendees_count }}
                    </span>

                @endif

            </div>


            <div class="text-muted small mb-3 mt-2">

                Manage meeting participants and attendance.

            </div>


            <div class="d-flex gap-2">

                <a
                    href="{{ route(
                        'admin.projects.governance-meetings.attendees.index',
                        [
                            'project' => $project->id,
                            'meeting' => $meeting->id,
                        ]
                    ) }}"
                    class="btn btn-sm btn-outline-primary"
                >
                    Manage Attendees
                </a>


                <a
                    href="{{ route(
                        'admin.projects.governance-meetings.attendees.create',
                        [
                            'project' => $project->id,
                            'meeting' => $meeting->id,
                        ]
                    ) }}"
                    class="btn btn-sm btn-primary"
                >
                    + Add
                </a>

            </div>

        </div>

    </div>

</div>