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
                Add Decision / Resolution
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
    {{-- FORM --}}
    {{-- ========================================================= --}}

    <form
        method="POST"
        action="{{ route(
            'admin.projects.governance-meetings.decisions.store',
            [
                'project' => $project->id,
                'meeting' => $meeting->id,
            ]
        ) }}"
    >

        @csrf


        {{-- ===================================================== --}}
        {{-- DECISION INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Decision Information
                </strong>

                <div class="text-muted small mt-1">
                    Record the formal decision, resolution, direction
                    or recommendation arising from the meeting.
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
                                $nextDecisionNo
                            ) }}"
                            min="1"
                            required
                        >

                        <div class="form-text">
                            Next available number:
                            {{ $nextDecisionNo }}
                        </div>

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
                                            'project_governance_meeting_agenda_item_id'
                                        ) == $agendaItem->id
                                    )
                                >

                                    Item {{ $agendaItem->item_no }}
                                    —
                                    {{ $agendaItem->subject }}

                                </option>

                            @endforeach

                        </select>

                        <div class="form-text">
                            Link the decision to the agenda item
                            from which it originated.
                        </div>

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
                                            'Resolution'
                                        ) === $type
                                    )
                                >
                                    {{ $type }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Decision Status --}}

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
                                            'Draft'
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
                        value="{{ old('decision_title') }}"
                        maxlength="255"
                        placeholder="Enter a concise title for the decision..."
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
                        placeholder="Record the formal decision, resolution, direction or recommendation..."
                        required
                    >{{ old('decision_text') }}</textarea>

                    <div class="form-text">
                        Use clear and definitive language because this
                        record may be referenced in later governance reviews.
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
                    Record who approved the decision and when it becomes
                    effective.
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
                                            'approved_by'
                                        ) == $user->id
                                    )
                                >

                                    {{ $user->name }}

                                </option>

                            @endforeach

                        </select>

                        <div class="form-text">
                            Required when the decision status is Approved.
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
                                'approval_date'
                            ) }}"
                        >

                        <div class="form-text">
                            If blank for an approved decision,
                            the system records today's date.
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
                                'effective_date'
                            ) }}"
                        >

                        <div class="form-text">
                            Date from which the decision takes effect.
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
                    >
                        This decision is currently being prepared.
                    </div>

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

                    {{-- Approval --}}

                    <div class="col-md-6 col-lg">

                        <div class="border rounded p-3 h-100">

                            <span class="badge bg-primary mb-2">
                                Approval
                            </span>

                            <div class="small text-muted">
                                Used when the committee formally approves
                                a proposal, budget, plan or other submission.
                            </div>

                        </div>

                    </div>


                    {{-- Direction --}}

                    <div class="col-md-6 col-lg">

                        <div class="border rounded p-3 h-100">

                            <span class="badge bg-warning text-dark mb-2">
                                Direction
                            </span>

                            <div class="small text-muted">
                                Used when the committee instructs a person,
                                contractor, consultant or team to take action.
                            </div>

                        </div>

                    </div>


                    {{-- Resolution --}}

                    <div class="col-md-6 col-lg">

                        <div class="border rounded p-3 h-100">

                            <span class="badge bg-info text-dark mb-2">
                                Resolution
                            </span>

                            <div class="small text-muted">
                                Used for a formal governance resolution
                                adopted by the committee.
                            </div>

                        </div>

                    </div>


                    {{-- Recommendation --}}

                    <div class="col-md-6 col-lg">

                        <div class="border rounded p-3 h-100">

                            <span class="badge bg-secondary mb-2">
                                Recommendation
                            </span>

                            <div class="small text-muted">
                                Used when the committee recommends a matter
                                for approval or further action elsewhere.
                            </div>

                        </div>

                    </div>


                    {{-- Information --}}

                    <div class="col-md-6 col-lg">

                        <div class="border rounded p-3 h-100">

                            <span class="badge bg-light text-dark border mb-2">
                                Information
                            </span>

                            <div class="small text-muted">
                                Used when the matter is formally recorded
                                for information without a decision.
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
                >{{ old('remarks') }}</textarea>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="alert alert-light border mb-4">

            <strong>
                Governance Record
            </strong>

            <div class="text-muted small mt-2">

                A formal decision can be linked to a source agenda item.
                Once approved, it can be referenced by subsequent action
                items and governance reviews.

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- ACTIONS --}}
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
                Add Decision
            </button>

        </div>

    </form>

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
                        'If the approval date is left blank, ' +
                        'the system will use today\'s date.';

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