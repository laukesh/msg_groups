@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Project:
                <strong>
                    {{ $project->project_code ?? '—' }}
                </strong>
            </div>

            <h3 class="mb-1">
                Toolbox Talks
            </h3>

            <div class="text-muted">
                Health, safety and toolbox talk records
            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.construction.hse.toolbox-talks.create',
                [
                    'project' => $project,
                ]
            ) }}"
            class="btn btn-primary"
        >
            <i class="bi bi-plus-lg me-1"></i>
            New Toolbox Talk
        </a>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    <div class="card">

        <div class="card-header">

            <strong>
                Toolbox Talk Register
            </strong>

            <span class="badge bg-primary ms-2">
                {{ $talks->count() }}
            </span>

        </div>


        <div class="card-body p-0">

            @if($talks->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>
                                Talk Number
                            </th>

                            <th>
                                Title
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Topic
                            </th>

                            <th>
                                Conducted By
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @foreach($talks as $talk)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                <td>

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.toolbox-talks.show',
                                            [
                                                'project' => $project,
                                                'toolboxTalk' => $talk,
                                            ]
                                        ) }}"
                                        class="fw-semibold text-decoration-none"
                                    >
                                        {{ $talk->toolbox_talk_number }}
                                    </a>

                                </td>


                                <td>
                                    {{ $talk->title }}
                                </td>


                                <td>

                                    {{ $talk->talk_date
                                        ? $talk->talk_date->format('d-m-Y')
                                        : '—'
                                    }}

                                </td>


                                <td>
                                    {{ $talk->topic ?? '—' }}
                                </td>


                                <td>

                                    {{ $talk->conductedBy?->name
                                        ?? $talk->conducted_by_name
                                        ?? '—'
                                    }}

                                </td>


                                <td>

                                    @php

                                        $statusClass = match(
                                            $talk->status
                                        ) {

                                            'Draft' =>
                                                'bg-secondary',

                                            'Completed' =>
                                                'bg-success',

                                            'Cancelled' =>
                                                'bg-danger',

                                            default =>
                                                'bg-secondary',

                                        };

                                    @endphp

                                    <span
                                        class="badge {{ $statusClass }}"
                                    >
                                        {{ $talk->status }}
                                    </span>

                                </td>


                                <td class="text-end">

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.toolbox-talks.show',
                                            [
                                                'project' => $project,
                                                'toolboxTalk' => $talk,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        View
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <i
                        class="bi bi-shield-check"
                        style="font-size:42px;"
                    ></i>

                    <h6 class="mt-3">
                        No Toolbox Talks Found
                    </h6>

                    <p class="text-muted mb-3">
                        Create the first toolbox talk for this project.
                    </p>

                    <a
                        href="{{ route(
                            'admin.projects.construction.hse.toolbox-talks.create',
                            [
                                'project' => $project,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-plus-lg me-1"></i>
                        Create Toolbox Talk
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection