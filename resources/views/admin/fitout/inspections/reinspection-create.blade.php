@extends('layouts.app')

@section('title', 'Create Re-Inspection')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Create Re-Inspection
            </h4>

            <p class="text-muted mb-0">
                Follow-up inspection for
                <strong>
                    {{ $inspection->inspection_number }}
                </strong>
            </p>

        </div>

        <a
            href="{{ route(
                'admin.fitout.inspections.show',
                $inspection->id
            ) }}"
            class="btn btn-secondary"
        >

            <i class="bi bi-arrow-left me-1"></i>

            Back

        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following errors:
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


    <div class="row">

        {{-- Parent Inspection --}}
        <div class="col-lg-4">

            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Previous Inspection
                    </h5>

                </div>

                <div class="card-body">

                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Inspection Number
                        </small>

                        <strong>
                            {{ $inspection->inspection_number }}
                        </strong>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Inspection Type
                        </small>

                        <strong>
                            {{ $inspection->inspection_type }}
                        </strong>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Result
                        </small>

                        <span class="badge bg-danger">
                            {{ $inspection->result }}
                        </span>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Inspection Date
                        </small>

                        <strong>

                            {{ $inspection->inspection_date
                                ? $inspection->inspection_date->format('d M Y')
                                : '-' }}

                        </strong>

                    </div>


                    <div>

                        <small class="text-muted d-block">
                            Re-Inspection
                        </small>

                        <span class="badge bg-warning text-dark">
                            Required
                        </span>

                    </div>

                </div>

            </div>


            @if($inspection->fitoutRequest)

                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Fit-Out Request
                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <small class="text-muted d-block">
                                Request No.
                            </small>

                            <strong>
                                {{ $inspection->fitoutRequest->request_no }}
                            </strong>

                        </div>


                        <div class="mb-3">

                            <small class="text-muted d-block">
                                Tenant
                            </small>

                            <strong>

                                {{
                                    $inspection->fitoutRequest->tenant->company_name
                                    ??
                                    $inspection->fitoutRequest->tenant->company_name
                                    ??
                                    '-'
                                }}

                            </strong>

                        </div>


                        <div>

                            <small class="text-muted d-block">
                                Unit
                            </small>

                            <strong>

                                {{
                                    $inspection->fitoutRequest->unit->unit_no
                                    ??
                                    $inspection->fitoutRequest->unit->name
                                    ??
                                    '-'
                                }}

                            </strong>

                        </div>

                    </div>

                </div>

            @endif

        </div>


        {{-- Form --}}
        <div class="col-lg-8">

            <form
                action="{{ route(
                    'admin.fitout.inspections.reinspection.store',
                    $inspection->id
                ) }}"
                method="POST"
            >

                @csrf


                <div class="card mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            Re-Inspection Schedule
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row">


                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Inspection Type

                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    value="Re-Inspection"
                                    readonly
                                >

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Fit-Out Stage

                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{
                                        $inspection->fitoutStage->stage_name
                                        ?? 'Not Assigned'
                                    }}"
                                    readonly
                                >

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Scheduled Date
                                    <span class="text-danger">*</span>

                                </label>

                                <input
                                    type="date"
                                    name="scheduled_date"
                                    class="form-control"
                                    min="{{ date('Y-m-d') }}"
                                    value="{{ old('scheduled_date') }}"
                                    required
                                >

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Scheduled Time

                                </label>

                                <input
                                    type="time"
                                    name="scheduled_time"
                                    class="form-control"
                                    value="{{ old('scheduled_time') }}"
                                >

                            </div>


                            <div class="col-md-12 mb-3">

                                <label class="form-label">

                                    Inspector

                                </label>

                                <select
                                    name="inspector_id"
                                    class="form-select"
                                >

                                    <option value="">
                                        Select Inspector
                                    </option>

                                    @foreach($inspectors as $inspector)

                                        <option
                                            value="{{ $inspector->id }}"
                                            @selected(
                                                old('inspector_id') == $inspector->id
                                            )
                                        >

                                            {{ $inspector->name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            <div class="col-md-12 mb-3">

                                <label class="form-label">

                                    Observations

                                </label>

                                <textarea
                                    name="observations"
                                    class="form-control"
                                    rows="4"
                                    placeholder="Enter previous issues or points that need to be rechecked..."
                                >{{ old('observations') }}</textarea>

                            </div>


                            <div class="col-md-12 mb-3">

                                <label class="form-label">

                                    Recommendations

                                </label>

                                <textarea
                                    name="recommendations"
                                    class="form-control"
                                    rows="4"
                                    placeholder="Enter recommendations for the re-inspection..."
                                >{{ old('recommendations') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="card">

                    <div class="card-body">

                        <div class="d-flex justify-content-end gap-2">

                            <a
                                href="{{ route(
                                    'admin.fitout.inspections.show',
                                    $inspection->id
                                ) }}"
                                class="btn btn-secondary"
                            >

                                Cancel

                            </a>


                            <button
                                type="submit"
                                class="btn btn-warning"
                            >

                                <i class="bi bi-arrow-repeat me-1"></i>

                                Schedule Re-Inspection

                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection