@extends('layouts.app')

@section('content')

<div class="container-fluid">


    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">

                Incident:

                <strong>
                    {{ $incident->incident_number }}
                </strong>

            </div>


            <h3 class="mb-1">
                {{ $person->person_name }}
            </h3>


            <div class="text-muted">

                {{ $person->person_type }}

                @if($person->company_name)

                    <span class="mx-1">
                        •
                    </span>

                    {{ $person->company_name }}

                @endif

            </div>

        </div>


        <div class="d-flex gap-2">


            <a
                href="{{ route(
                    'admin.projects.construction.hse.incidents.persons.edit',
                    [
                        'project' => $project,
                        'incident' => $incident,
                        'person' => $person,
                    ]
                ) }}"
                class="btn btn-outline-primary"
            >

                <i class="bi bi-pencil me-1"></i>

                Edit

            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.hse.incidents.persons.index',
                    [
                        'project' => $project,
                        'incident' => $incident,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Persons
            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.hse.incidents.show',
                    [
                        'project' => $project,
                        'incident' => $incident,
                    ]
                ) }}"
                class="btn btn-secondary"
            >
                Incident
            </a>

        </div>

    </div>



    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif



    {{-- =========================================================
        PERSON INFORMATION
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Person Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">


                <div class="col-md-4">

                    <div class="text-muted small">
                        Person Name
                    </div>

                    <strong>
                        {{ $person->person_name }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Person Type
                    </div>

                    <strong>
                        {{ $person->person_type }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Employee / Worker Code
                    </div>

                    <strong>
                        {{ $person->employee_code ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Company
                    </div>

                    <strong>
                        {{ $person->company_name ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Designation
                    </div>

                    <strong>
                        {{ $person->designation ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Phone
                    </div>

                    <strong>
                        {{ $person->phone ?? '—' }}
                    </strong>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        INJURY INFORMATION
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Injury Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">


                <div class="col-md-3">

                    <div class="text-muted small">
                        Injury Occurred
                    </div>


                    @if($person->injury_occurred)

                        <span class="badge bg-danger">
                            Yes
                        </span>

                    @else

                        <span class="badge bg-success">
                            No
                        </span>

                    @endif

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Injury Type
                    </div>

                    <strong>
                        {{ $person->injury_type ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Body Part Affected
                    </div>

                    <strong>
                        {{ $person->body_part_affected ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Injury Severity
                    </div>

                    <strong>
                        {{ $person->injury_severity ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Treatment Type
                    </div>

                    <strong>
                        {{ $person->treatment_type ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Medical Facility
                    </div>

                    <strong>
                        {{ $person->medical_facility ?? '—' }}
                    </strong>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        HOSPITALIZATION
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Hospitalization & Work Status
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">


                <div class="col-md-3">

                    <div class="text-muted small">
                        Hospitalized
                    </div>


                    @if($person->hospitalized)

                        <span class="badge bg-danger">
                            Yes
                        </span>

                    @else

                        <span class="badge bg-secondary">
                            No
                        </span>

                    @endif

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Hospitalization Date
                    </div>

                    <strong>

                        {{ $person->hospitalization_date
                            ? $person->hospitalization_date->format('d-m-Y')
                            : '—'
                        }}

                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Lost Work Days
                    </div>

                    <strong>
                        {{ $person->lost_work_days ?? 0 }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Returned to Work
                    </div>


                    @if($person->returned_to_work)

                        <span class="badge bg-success">
                            Yes
                        </span>

                    @else

                        <span class="badge bg-warning text-dark">
                            No
                        </span>

                    @endif

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Return to Work Date
                    </div>

                    <strong>

                        {{ $person->return_to_work_date
                            ? $person->return_to_work_date->format('d-m-Y')
                            : '—'
                        }}

                    </strong>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        REMARKS
    ========================================================== --}}

    @if($person->remarks)

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Remarks
                </strong>

            </div>


            <div class="card-body">

                <div style="white-space: pre-line;">

                    {{ $person->remarks }}

                </div>

            </div>

        </div>

    @endif



    {{-- =========================================================
        AUDIT
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Record Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">


                <div class="col-md-3">

                    <div class="text-muted small">
                        Created By
                    </div>

                    <strong>
                        {{ $person->creator?->name ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Created At
                    </div>

                    <strong>

                        {{ $person->created_at
                            ? $person->created_at->format('d-m-Y H:i')
                            : '—'
                        }}

                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Updated By
                    </div>

                    <strong>
                        {{ $person->updater?->name ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Updated At
                    </div>

                    <strong>

                        {{ $person->updated_at
                            ? $person->updated_at->format('d-m-Y H:i')
                            : '—'
                        }}

                    </strong>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        DELETE
    ========================================================== --}}

    <div class="d-flex justify-content-end">

        <form
            method="POST"
            action="{{ route(
                'admin.projects.construction.hse.incidents.persons.destroy',
                [
                    'project' => $project,
                    'incident' => $incident,
                    'person' => $person,
                ]
            ) }}"
            onsubmit="return confirm(
                'Are you sure you want to delete this incident person?'
            );"
        >

            @csrf

            @method('DELETE')


            <button
                type="submit"
                class="btn btn-outline-danger"
            >

                <i class="bi bi-trash me-1"></i>

                Delete Person

            </button>

        </form>

    </div>

</div>

@endsection