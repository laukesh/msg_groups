@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Contract:
                {{ $contract->contract_number }}
            </div>

            <h4>
                Add Progress Update
            </h4>

            <div class="text-muted">
                {{ $milestone->milestone_number }}
                -
                {{ $milestone->milestone_title }}
            </div>

        </div>


        <a
            href="{{ route(
                'admin.procurement.tenders.contracts.milestones.progress.index',
                [
                    'procurementTender' => $procurementTender,
                    'contract' => $contract,
                    'milestone' => $milestone,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >
            Back
        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="card mb-4">

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Current Progress
                    </small>

                    <h5>
                        {{ $milestone->progress_percentage }}%
                    </h5>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Planned End
                    </small>

                    <h6>

                        {{
                            $milestone->planned_end_date
                                ?->format('d-m-Y')
                            ?? '—'
                        }}

                    </h6>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Milestone Amount
                    </small>

                    <h6>

                        {{
                            number_format(
                                (float)
                                $milestone->milestone_amount,
                                2
                            )
                        }}

                        {{ $milestone->currency }}

                    </h6>

                </div>

            </div>

        </div>

    </div>


    <form
        method="POST"
        action="{{ route(
            'admin.procurement.tenders.contracts.milestones.progress.store',
            [
                'procurementTender' => $procurementTender,
                'contract' => $contract,
                'milestone' => $milestone,
            ]
        ) }}"
    >

        @csrf


        <div class="card">

            <div class="card-header">

                <strong>
                    Progress Details
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Progress Date
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            name="progress_date"
                            class="form-control"
                            value="{{ old(
                                'progress_date',
                                now()->format('Y-m-d')
                            ) }}"
                            required
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Overall Progress %
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="number"
                            name="progress_percentage"
                            class="form-control"
                            min="0"
                            max="100"
                            step="0.01"
                            value="{{ old(
                                'progress_percentage',
                                $milestone->progress_percentage
                            ) }}"
                            required
                        >

                        <div class="form-text">
                            Cannot be lower than
                            {{ $milestone->progress_percentage }}%.
                        </div>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Status
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="Submitted"
                            readonly
                        >

                    </div>


                    <div class="col-12">

                        <label class="form-label">
                            Progress Description
                        </label>

                        <textarea
                            name="progress_description"
                            class="form-control"
                            rows="4"
                        >{{ old('progress_description') }}</textarea>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Work Completed
                        </label>

                        <textarea
                            name="work_completed"
                            class="form-control"
                            rows="5"
                        >{{ old('work_completed') }}</textarea>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Work Pending
                        </label>

                        <textarea
                            name="work_pending"
                            class="form-control"
                            rows="5"
                        >{{ old('work_pending') }}</textarea>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Issues
                        </label>

                        <textarea
                            name="issues"
                            class="form-control"
                            rows="4"
                        >{{ old('issues') }}</textarea>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Corrective Action
                        </label>

                        <textarea
                            name="corrective_action"
                            class="form-control"
                            rows="4"
                        >{{ old('corrective_action') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        <div class="d-flex justify-content-end gap-2 mt-4">

            <a
                href="{{ route(
                    'admin.procurement.tenders.contracts.milestones.progress.index',
                    [
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
                Submit Progress
            </button>

        </div>

    </form>

</div>

@endsection