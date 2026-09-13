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
                {{ $witness->witness_name }}
            </h3>


            <div class="text-muted">

                {{ $witness->witness_type }}

                @if($witness->company_name)

                    <span class="mx-1">
                        •
                    </span>

                    {{ $witness->company_name }}

                @endif

            </div>

        </div>


        <div class="d-flex gap-2">


            <a
                href="{{ route(
                    'admin.projects.construction.hse.incidents.witnesses.edit',
                    [
                        'project' => $project,
                        'incident' => $incident,
                        'witness' => $witness,
                    ]
                ) }}"
                class="btn btn-outline-primary"
            >

                <i class="bi bi-pencil me-1"></i>

                Edit

            </a>


            <a
                href="{{ route(
                    'admin.projects.construction.hse.incidents.witnesses.index',
                    [
                        'project' => $project,
                        'incident' => $incident,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Witnesses
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
        WITNESS INFORMATION
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Witness Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">


                <div class="col-md-4">

                    <div class="text-muted small">
                        Witness Name
                    </div>

                    <strong>
                        {{ $witness->witness_name }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Witness Type
                    </div>

                    <strong>
                        {{ $witness->witness_type }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Employee / Worker Code
                    </div>

                    <strong>
                        {{ $witness->employee_code ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Company
                    </div>

                    <strong>
                        {{ $witness->company_name ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Designation
                    </div>

                    <strong>
                        {{ $witness->designation ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Phone
                    </div>

                    <strong>
                        {{ $witness->phone ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Email
                    </div>

                    <strong>
                        {{ $witness->email ?? '—' }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        STATEMENT
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Witness Statement
            </strong>

        </div>


        <div class="card-body">


            <div class="row g-4">


                <div class="col-md-8">

                    <div class="text-muted small mb-2">
                        Statement
                    </div>


                    @if($witness->statement)

                        <div style="white-space: pre-line;">

                            {{ $witness->statement }}

                        </div>

                    @else

                        <span class="text-muted">
                            No statement recorded.
                        </span>

                    @endif

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Statement Date
                    </div>

                    <strong>

                        {{ $witness->statement_date
                            ? $witness->statement_date->format('d-m-Y')
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

    @if($witness->remarks)

        <div class="card mb-4">

            <div class="card-header">

                <strong>
                    Remarks
                </strong>

            </div>


            <div class="card-body">

                <div style="white-space: pre-line;">

                    {{ $witness->remarks }}

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
                        {{ $witness->creator?->name ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Created At
                    </div>

                    <strong>

                        {{ $witness->created_at
                            ? $witness->created_at->format('d-m-Y H:i')
                            : '—'
                        }}

                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Updated By
                    </div>

                    <strong>
                        {{ $witness->updater?->name ?? '—' }}
                    </strong>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Updated At
                    </div>

                    <strong>

                        {{ $witness->updated_at
                            ? $witness->updated_at->format('d-m-Y H:i')
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
                'admin.projects.construction.hse.incidents.witnesses.destroy',
                [
                    'project' => $project,
                    'incident' => $incident,
                    'witness' => $witness,
                ]
            ) }}"
            onsubmit="return confirm(
                'Are you sure you want to delete this witness?'
            );"
        >

            @csrf

            @method('DELETE')


            <button
                type="submit"
                class="btn btn-outline-danger"
            >

                <i class="bi bi-trash me-1"></i>

                Delete Witness

            </button>

        </form>

    </div>

</div>

@endsection