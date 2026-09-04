@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                Development Projects
            </h3>

            <p class="text-muted mb-0">
                Project Setup & Development Planning
            </p>

        </div>


        <a
            href="{{ route('admin.projects.create') }}"
            class="btn btn-primary"
        >
            + Create Project
        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- Messages --}}
    {{-- ========================================================= --}}

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


    @if(session('info'))

        <div class="alert alert-info">
            {{ session('info') }}
        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- Projects --}}
    {{-- ========================================================= --}}

    <div class="card">

        <div class="card-header">

            <strong>
                Project Register
            </strong>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover mb-0 align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Project No.
                            </th>

                            <th>
                                Project Name
                            </th>

                            <th>
                                Land
                            </th>

                            <th>
                                Investment Decision
                            </th>

                            <th>
                                Stage
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Priority
                            </th>

                            <th>
                                Start Date
                            </th>

                            <th style="width:220px;">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($projects as $project)

                        <tr>

                            <td>

                                <strong>
                                    {{ $project->project_number }}
                                </strong>

                                @if($project->project_code)

                                    <div class="small text-muted">
                                        {{ $project->project_code }}
                                    </div>

                                @endif

                            </td>


                            <td>

                                <strong>
                                    {{ $project->project_name }}
                                </strong>

                                @if($project->project_type)

                                    <div class="small text-muted">
                                        {{ $project->project_type }}
                                    </div>

                                @endif

                            </td>


                            <td>

                                @if($project->land)

                                    {{ $project->land->land_name
                                        ?? $project->land->name
                                        ?? 'Land #' . $project->land_id }}

                                @else

                                    -

                                @endif

                            </td>


                            <td>

                                @if($project->investmentDecision)

                                    {{
                                        $project
                                            ->investmentDecision
                                            ->decision_number
                                        ?? '-'
                                    }}

                                @else

                                    -

                                @endif

                            </td>


                            <td>

                                <span class="badge bg-info text-dark">
                                    {{ $project->project_stage }}
                                </span>

                            </td>


                            <td>

                                @switch($project->project_status)

                                    @case('Draft')

                                        <span class="badge bg-secondary">
                                            Draft
                                        </span>

                                        @break

                                    @case('Active')

                                        <span class="badge bg-success">
                                            Active
                                        </span>

                                        @break

                                    @case('On Hold')

                                        <span class="badge bg-warning text-dark">
                                            On Hold
                                        </span>

                                        @break

                                    @case('Delayed')

                                        <span class="badge bg-danger">
                                            Delayed
                                        </span>

                                        @break

                                    @case('Completed')

                                        <span class="badge bg-success">
                                            Completed
                                        </span>

                                        @break

                                    @case('Cancelled')

                                        <span class="badge bg-danger">
                                            Cancelled
                                        </span>

                                        @break

                                    @default

                                        <span class="badge bg-secondary">
                                            {{ $project->project_status }}
                                        </span>

                                @endswitch

                            </td>


                            <td>

                                @if($project->project_priority)

                                    @if(
                                        $project->project_priority ===
                                        'High'
                                    )

                                        <span class="badge bg-danger">
                                            High
                                        </span>

                                    @elseif(
                                        $project->project_priority ===
                                        'Medium'
                                    )

                                        <span class="badge bg-warning text-dark">
                                            Medium
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            {{ $project->project_priority }}
                                        </span>

                                    @endif

                                @else

                                    -

                                @endif

                            </td>


                            <td>

                                @if($project->project_start_date)

                                    {{
                                        $project
                                            ->project_start_date
                                            ->format('d M Y')
                                    }}

                                @else

                                    -

                                @endif

                            </td>


                            <td>

                                <div class="d-flex gap-1">

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.dashboard',
                                            $project
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Construction
                                    </a>

                                    <a
                                        href="{{ route(
                                            'admin.projects.show',
                                            [
                                                'project' =>
                                                    $project->id,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        View
                                    </a>


                                    <a
                                        href="{{ route(
                                            'admin.projects.edit',
                                            [
                                                'project' =>
                                                    $project->id,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-secondary"
                                    >
                                        Edit
                                    </a>


                                    @if(
                                        in_array(
                                            $project->project_status,
                                            [
                                                'Draft',
                                                'Cancelled'
                                            ],
                                            true
                                        )
                                    )

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.projects.destroy',
                                                [
                                                    'project' =>
                                                        $project->id,
                                                ]
                                            ) }}"
                                            onsubmit="return confirm('Are you sure you want to delete this project?');"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                            >
                                                Delete
                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="9"
                                class="text-center py-5"
                            >

                                <div class="text-muted mb-3">
                                    No development projects found.
                                </div>

                                <a
                                    href="{{ route(
                                        'admin.projects.create'
                                    ) }}"
                                    class="btn btn-primary btn-sm"
                                >
                                    + Create Project
                                </a>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection