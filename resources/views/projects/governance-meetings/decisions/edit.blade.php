@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project / Governance / Meetings / Decisions
            </div>

            <h3 class="mb-1">
                Edit Decision / Resolution
            </h3>

            <div class="text-muted">

                {{ $meeting->meeting_number }}

                ·

                {{ $meeting->committee_name }}

                ·

                {{ $project->project_name }}

            </div>

        </div>


        <div>

            <a
                href="{{ route(
                    'admin.projects.governance-meetings.decisions.index',
                    [
                        'project' => $project->id,
                        'meeting' => $meeting->id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Decision Register
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- VALIDATION ERRORS --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- SUCCESS --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- UPDATE FORM --}}
    {{-- ========================================================= --}}

    <form
        method="POST"
        action="{{ route(
            'admin.projects.governance-meetings.decisions.update',
            [
                'project' => $project->id,
                'meeting' => $meeting->id,
                'decision' => $decision->id,
            ]
        ) }}"
    >

        @csrf
        @method('PUT')


        {{-- ===================================================== --}}
        {{-- DECISION INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Decision Information
                </strong>

                <div class="text-muted small mt-1">
                    Update the formal decision, resolution, direction
                    or recommendation.
                </div>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Decision Number --}}

                    <div class="col-md-3 mb-3">

                        <label class="form-label">

                            Decision No.

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="number"
                            name="decision_no"
                            class="form-control"
                            value="{{ old(
                                'decision_no',
                                $decision->decision_no
                            ) }}"
                            min="1"
                            required
                        >

                    </div>


                    {{-- Source Agenda --}}

                    <div class="col-md-5 mb-3">

                        <label class="form-label">
                            Source Agenda Item
                        </label>

                        <select
                            name="project_governance_meeting_agenda_item_id"
                            class="form-select"
                        >

                            <option value="">
                                — Not linked to an agenda item —
                            </option>


                            @foreach($agendaItems as $agendaItem)

                                <option
                                    value="{{ $agendaItem->id }}"
                                    @selected(
                                        old(
                                            'project_governance_meeting_agenda_item_id',
                                            $decision->project_governance_meeting_agenda_item_id
                                        ) == $agendaItem->id
                                    )
                                >

                                    Item {{ $agendaItem->item_no }}
                                    —
                                    {{ $agendaItem->subject }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Decision Type --}}

                    <div class="col-md-2 mb-3">

                        <label class="form-label">

                            Type

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="decision_type"
                            class="form-select"
                            required
                        >

                            @foreach([
                                'Approval',
                                'Direction',
                                'Resolution',
                                'Recommendation',
                                'Information',
                            ] as $type)

                                <option
                                    value="{{ $type }}"
                                    @selected(
                                        old(
                                            'decision_type',
                                            $decision->decision_type
                                        ) === $type
                                    )
                                >
                                    {{ $type }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Status --}}

                    <div class="col-md-2 mb-3">

                        <label class="form-label">

                            Status

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="decision_status"
                            id="decision_status"
                            class="form-select"
                            required
                        >

                            @foreach([
                                'Draft',
                                'Approved',
                                'Rejected',
                                'Deferred',
                                'Superseded',
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(
                                        old(
                                            'decision_status',
                                            $decision->decision_status
                                        ) === $status
                                    )
                                >
                                    {{ $status }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- Decision Title --}}

                <div class="mb-3">

                    <label class="form-label">

                        Decision Title

                        <span class="text-danger">*</span>

                    </label>

                    <input
                        type="text"
                        name="decision_title"
                        class="form-control"
                        value="{{ old(
                            'decision_title',
                            $decision->decision_title
                        ) }}"
                        maxlength="255"
                        required
                    >

                </div>


                {{-- Decision Text --}}

                <div>

                    <label class="form-label">

                        Decision / Resolution Text

                        <span class="text-danger">*</span>

                    </label>

                    <textarea
                        name="decision_text"
                        rows="9"
                        class="form-control"
                        required
                    >{{ old(
                        'decision_text',
                        $decision->decision_text
                    ) }}</textarea>

                    <div class="form-text">
                        Use clear and definitive language for formal
                        governance records.
                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- APPROVAL --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Approval & Effectiveness
                </strong>

                <div class="text-muted small mt-1">
                    Record approval authority and effective dates.
                </div>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Approved By --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Approved By
                        </label>

                        <select
                            name="approved_by"
                            id="approved_by"
                            class="form-select"
                        >

                            <option value="">
                                — Select Approver —
                            </option>


                            @foreach($users as $user)

                                <option
                                    value="{{ $user->id }}"
                                    @selected(
                                        old(
                                            'approved_by',
                                            $decision->approved_by
                                        ) == $user->id
                                    )
                                >

                                    {{ $user->name }}

                                </option>

                            @endforeach

                        </select>

                        <div class="form-text">
                            Required when status is Approved.
                        </div>

                    </div>


                    {{-- Approval Date --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Approval Date
                        </label>

                        <input
                            type="date"
                            name="approval_date"
                            id="approval_date"
                            class="form-control"
                            value="{{ old(
                                'approval_date',
                                optional(
                                    $decision->approval_date
                                )->format('Y-m-d')
                            ) }}"
                        >

                        <div class="form-text">
                            Automatically set by the controller when
                            approving without a date.
                        </div>

                    </div>


                    {{-- Effective Date --}}

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Effective Date
                        </label>

                        <input
                            type="date"
                            name="effective_date"
                            class="form-control"
                            value="{{ old(
                                'effective_date',
                                optional(
                                    $decision->effective_date
                                )->format('Y-m-d')
                            ) }}"
                        >

                        <div class="form-text">
                            Date from which this decision takes effect.
                        </div>

                    </div>

                </div>


                {{-- Approval Notice --}}

                <div
                    id="approvalNotice"
                    class="alert alert-light border mb-0"
                >

                    <strong>
                        Approval Status
                    </strong>

                    <div
                        id="approvalNoticeText"
                        class="text-muted small mt-1"
                    ></div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- DECISION TYPE GUIDE --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Decision Type Guide
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-6 col-lg">

                        <div class="border rounded p-3 h-100">

                            <span class="badge bg-primary mb-2">
                                Approval
                            </span>

                            <div class="small text-muted">
                                Formal approval of a proposal,
                                plan, budget or submission.
                            </div>

                        </div>

                    </div>


                    <div class="col-md-6 col-lg">

                        <div class="border rounded p-3 h-100">

                            <span class="badge bg-warning text-dark mb-2">
                                Direction
                            </span>

                            <div class="small text-muted">
                                Instruction given to a responsible
                                person, contractor, consultant or team.
                            </div>

                        </div>

                    </div>


                    <div class="col-md-6 col-lg">

                        <div class="border rounded p-3 h-100">

                            <span class="badge bg-info text-dark mb-2">
                                Resolution
                            </span>

                            <div class="small text-muted">
                                Formal resolution adopted by the
                                governance committee.
                            </div>

                        </div>

                    </div>


                    <div class="col-md-6 col-lg">

                        <div class="border rounded p-3 h-100">

                            <span class="badge bg-secondary mb-2">
                                Recommendation
                            </span>

                            <div class="small text-muted">
                                Recommendation requiring action or
                                approval elsewhere.
                            </div>

                        </div>

                    </div>


                    <div class="col-md-6 col-lg">

                        <div class="border rounded p-3 h-100">

                            <span class="badge bg-light text-dark border mb-2">
                                Information
                            </span>

                            <div class="small text-muted">
                                Formal recording of a matter for
                                information.
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- REMARKS --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Remarks
                </strong>

            </div>


            <div class="card-body">

                <textarea
                    name="remarks"
                    rows="5"
                    class="form-control"
                    placeholder="Additional notes, references or governance remarks..."
                >{{ old(
                    'remarks',
                    $decision->remarks
                ) }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- RECORD INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Record Information
                </strong>

            </div>


            <div class="card-body">

                <div class="row">

                    <div class="col-md-3">

                        <div class="text-muted small">
                            Decision ID
                        </div>

                        <div class="fw-semibold">
                            #{{ $decision->id }}
                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="text-muted small">
                            Decision Number
                        </div>

                        <div class="fw-semibold">

                            D-{{
                                str_pad(
                                    $decision->decision_no,
                                    3,
                                    '0',
                                    STR_PAD_LEFT
                                )
                            }}

                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="text-muted small">
                            Created
                        </div>

                        <div class="fw-semibold">

                            @if($decision->created_at)

                                {{
                                    $decision->created_at
                                        ->format('d-m-Y H:i')
                                }}

                            @else

                                —

                            @endif

                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="text-muted small">
                            Last Updated
                        </div>

                        <div class="fw-semibold">

                            @if($decision->updated_at)

                                {{
                                    $decision->updated_at
                                        ->format('d-m-Y H:i')
                                }}

                            @else

                                —

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- UPDATE ACTIONS --}}
        {{-- ===================================================== --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route(
                    'admin.projects.governance-meetings.decisions.index',
                    [
                        'project' => $project->id,
                        'meeting' => $meeting->id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Cancel
            </a>


            <button
                type="submit"
                class="btn btn-primary"
            >
                Save Changes
            </button>

        </div>

    </form>


    {{-- ========================================================= --}}
    {{-- DELETE --}}
    {{-- ========================================================= --}}

    <div class="card border-danger mb-5">

        <div class="card-header text-danger">

            <strong>
                Danger Zone
            </strong>

        </div>


        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <div class="fw-semibold">
                        Delete Decision / Resolution
                    </div>

                    <div class="text-muted small">
                        This action cannot be undone.
                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.governance-meetings.decisions.destroy',
                        [
                            'project' => $project->id,
                            'meeting' => $meeting->id,
                            'decision' => $decision->id,
                        ]
                    ) }}"
                    onsubmit="return confirm(
                        'Are you sure you want to delete this decision?'
                    );"
                >

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-outline-danger"
                    >
                        Delete Decision
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>


{{-- ============================================================= --}}
{{-- APPROVAL STATUS SCRIPT --}}
{{-- ============================================================= --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const statusSelect =
            document.getElementById(
                'decision_status'
            );

        const approverSelect =
            document.getElementById(
                'approved_by'
            );

        const approvalDate =
            document.getElementById(
                'approval_date'
            );

        const notice =
            document.getElementById(
                'approvalNotice'
            );

        const noticeText =
            document.getElementById(
                'approvalNoticeText'
            );


        if (!statusSelect) {
            return;
        }


        function updateApprovalState()
        {
            const status =
                statusSelect.value;


            if (status === 'Approved') {

                if (notice) {

                    notice.classList.remove(
                        'alert-light'
                    );

                    notice.classList.add(
                        'alert-success'
                    );

                }


                if (noticeText) {

                    noticeText.textContent =
                        'This decision is approved. ' +
                        'An approver is required. ' +
                        'If the approval date is blank, ' +
                        'the controller will use today\'s date.';

                }


                if (approverSelect) {

                    approverSelect.required =
                        true;

                }

            } else {

                if (notice) {

                    notice.classList.remove(
                        'alert-success'
                    );

                    notice.classList.add(
                        'alert-light'
                    );

                }


                if (noticeText) {

                    noticeText.textContent =
                        'Approval information is only applicable ' +
                        'when the decision status is Approved.';

                }


                if (approverSelect) {

                    approverSelect.required =
                        false;

                }

            }
        }


        statusSelect.addEventListener(
            'change',
            updateApprovalState
        );


        updateApprovalState();

    }
);

</script>

@endsection