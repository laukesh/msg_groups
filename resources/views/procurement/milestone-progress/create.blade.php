@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
        HEADER
    ============================================================= --}}

    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">

        <div>

            <div class="text-muted small mb-1">

                Project:
                <strong>
                    {{ $project->name ?? $project->project_name ?? '—' }}
                </strong>

            </div>


            <div class="text-muted small mb-1">

                Contract:
                <strong>
                    {{ $contract->contract_number }}
                </strong>

            </div>


            <h4 class="mb-1 fw-bold">
                Add Progress Update
            </h4>


            <div class="text-muted">

                {{ $milestone->milestone_number }}
                -
                {{ $milestone->milestone_title }}

            </div>

        </div>


        <div class="d-flex flex-wrap gap-2">

            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.show',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                    ]
                ) }}"
                class="btn btn-outline-primary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back to Tender
            </a>


            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.contracts.show',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                        'contract' => $contract,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-file-earmark-text me-1"></i>
                Contract
            </a>


            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.contracts.milestones.show',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                        'contract' => $contract,
                        'milestone' => $milestone,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-flag me-1"></i>
                Milestone
            </a>

        </div>

    </div>


    {{-- ============================================================
        ERRORS
    ============================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger shadow-sm">

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


    {{-- ============================================================
        MILESTONE SUMMARY
    ============================================================= --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <small class="text-muted d-block mb-1">
                        Current Progress
                    </small>

                    <div class="d-flex align-items-center gap-3">

                        <h4 class="mb-0 fw-bold">
                            {{ $milestone->progress_percentage }}%
                        </h4>

                        <div
                            class="progress flex-grow-1"
                            style="height:8px;"
                        >
                            <div
                                class="progress-bar"
                                role="progressbar"
                                style="width: {{ min((float) $milestone->progress_percentage, 100) }}%;"
                            ></div>
                        </div>

                    </div>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block mb-1">
                        Planned End
                    </small>

                    <h6 class="mb-0">

                        {{
                            $milestone->planned_end_date
                                ?->format('d-m-Y')
                            ?? '—'
                        }}

                    </h6>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block mb-1">
                        Milestone Amount
                    </small>

                    <h6 class="mb-0">

                        {{
                            number_format(
                                (float) $milestone->milestone_amount,
                                2
                            )
                        }}

                        {{ $milestone->currency }}

                    </h6>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        FORM
    ============================================================= --}}

    <form
        method="POST"
        action="{{ route(
            'admin.projects.procurement.tenders.contracts.milestones.progress.store',
            [
                'project' => $project,
                'procurementTender' => $procurementTender,
                'contract' => $contract,
                'milestone' => $milestone,
            ]
        ) }}"
    >

        @csrf


        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white py-3">

                <strong>
                    Progress Details
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    {{-- Progress Date --}}
                    <div class="col-md-4">

                        <label
                            for="progress_date"
                            class="form-label"
                        >
                            Progress Date
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            id="progress_date"
                            name="progress_date"
                            class="form-control @error('progress_date') is-invalid @enderror"
                            value="{{ old(
                                'progress_date',
                                now()->format('Y-m-d')
                            ) }}"
                            required
                        >

                        @error('progress_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Progress Percentage --}}
                    <div class="col-md-4">

                        <label
                            for="progress_percentage"
                            class="form-label"
                        >
                            Overall Progress %
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="number"
                            id="progress_percentage"
                            name="progress_percentage"
                            class="form-control @error('progress_percentage') is-invalid @enderror"
                            min="{{ $milestone->progress_percentage }}"
                            max="100"
                            step="0.01"
                            value="{{ old(
                                'progress_percentage',
                                $milestone->progress_percentage
                            ) }}"
                            required
                        >

                        <div class="form-text">
                            Progress cannot be lower than
                            {{ $milestone->progress_percentage }}%.
                        </div>

                        @error('progress_percentage')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Status --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Submission Status
                        </label>

                        <input
                            type="text"
                            class="form-control bg-light"
                            value="Submitted"
                            readonly
                        >

                    </div>


                    {{-- Description --}}
                    <div class="col-12">

                        <label
                            for="progress_description"
                            class="form-label"
                        >
                            Progress Description
                        </label>

                        <textarea
                            id="progress_description"
                            name="progress_description"
                            class="form-control @error('progress_description') is-invalid @enderror"
                            rows="4"
                            placeholder="Describe the progress achieved..."
                        >{{ old('progress_description') }}</textarea>

                        @error('progress_description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Work Completed --}}
                    <div class="col-md-6">

                        <label
                            for="work_completed"
                            class="form-label"
                        >
                            Work Completed
                        </label>

                        <textarea
                            id="work_completed"
                            name="work_completed"
                            class="form-control @error('work_completed') is-invalid @enderror"
                            rows="5"
                            placeholder="Describe the work completed..."
                        >{{ old('work_completed') }}</textarea>

                        @error('work_completed')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Work Pending --}}
                    <div class="col-md-6">

                        <label
                            for="work_pending"
                            class="form-label"
                        >
                            Work Pending
                        </label>

                        <textarea
                            id="work_pending"
                            name="work_pending"
                            class="form-control @error('work_pending') is-invalid @enderror"
                            rows="5"
                            placeholder="Describe the remaining work..."
                        >{{ old('work_pending') }}</textarea>

                        @error('work_pending')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Issues --}}
                    <div class="col-md-6">

                        <label
                            for="issues"
                            class="form-label"
                        >
                            Issues
                        </label>

                        <textarea
                            id="issues"
                            name="issues"
                            class="form-control @error('issues') is-invalid @enderror"
                            rows="4"
                            placeholder="Record any issues or constraints..."
                        >{{ old('issues') }}</textarea>

                        @error('issues')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Corrective Action --}}
                    <div class="col-md-6">

                        <label
                            for="corrective_action"
                            class="form-label"
                        >
                            Corrective Action
                        </label>

                        <textarea
                            id="corrective_action"
                            name="corrective_action"
                            class="form-control @error('corrective_action') is-invalid @enderror"
                            rows="4"
                            placeholder="Record corrective action, if required..."
                        >{{ old('corrective_action') }}</textarea>

                        @error('corrective_action')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
            FOOTER
        ========================================================= --}}

        <div class="d-flex justify-content-end gap-2 mt-4">

            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.contracts.milestones.progress.index',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                        'contract' => $contract,
                        'milestone' => $milestone,
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
                <i class="bi bi-check-lg me-1"></i>
                Submit Progress
            </button>

        </div>

    </form>

</div>

@endsection