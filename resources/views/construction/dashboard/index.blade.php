@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ================================================================
         HEADER
    ================================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Construction Management
            </div>

            <h4 class="mb-1">
                {{ $project->project_name ?? 'Project' }}
            </h4>

            @if(!empty($project->project_code))

                <div class="text-muted">
                    {{ $project->project_code }}
                </div>

            @endif

        </div>

        <div class="d-flex gap-2">
            <a
                href="{{ route('admin.construction.index') }}"
                class="btn btn-outline-secondary"
            >
                Back
            </a>
        </div>

    </div>


    {{-- ================================================================
         SUMMARY CARDS
    ================================================================= --}}

    <div class="row g-3 mb-4">


        {{-- Packages --}}

        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Procurement Packages
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $dashboard['total_packages'] }}
                    </div>

                    <div class="small text-muted">
                        {{ $dashboard['active_packages'] }}
                        active
                    </div>

                </div>

            </div>

        </div>


        {{-- Contracts --}}

        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Contracts
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $dashboard['total_contracts'] }}
                    </div>

                    <div class="small text-muted">
                        {{ $dashboard['active_contracts'] }}
                        active
                    </div>

                </div>

            </div>

        </div>


        {{-- Contract Value --}}

        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Contract Value
                    </div>

                    <div class="fs-4 fw-semibold">

                        ${{
                            number_format(
                                $dashboard['total_contract_value'],
                                2
                            )
                        }}

                    </div>

                </div>

            </div>

        </div>


        {{-- Progress --}}

        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Overall Progress
                    </div>

                    <div class="fs-3 fw-semibold">
                        {{ $dashboard['overall_progress'] }}%
                    </div>

                    <div class="progress mt-2">

                        <div
                            class="progress-bar"
                            role="progressbar"
                            style="
                                width:
                                {{ $dashboard['overall_progress'] }}%
                            "
                        ></div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================
         FINANCIAL SUMMARY
    ================================================================= --}}

    <div class="row g-3 mb-4">


        <div class="col-xl-4 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Invoice Value
                    </div>

                    <div class="fs-4 fw-semibold">

                        ${{
                            number_format(
                                $dashboard['total_invoice_amount'],
                                2
                            )
                        }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-4 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Paid Amount
                    </div>

                    <div class="fs-4 fw-semibold">

                        ${{
                            number_format(
                                $dashboard['total_paid_amount'],
                                2
                            )
                        }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-4 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Outstanding Amount
                    </div>

                    <div class="fs-4 fw-semibold">

                        ${{
                            number_format(
                                $dashboard['outstanding_amount'],
                                2
                            )
                        }}

                    </div>

                    <div class="small text-muted">

                        {{ $dashboard['pending_payments'] }}
                        pending payments

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================
         CONSTRUCTION MODULES
    ================================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Construction Management
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-3">


                @php

                    $modules = [

                        [
                            'title' =>
                                'Project Dashboard',

                            'description' =>
                                'Overall construction project monitoring.',

                            'active' =>
                                true,
                        ],

                        [
                            'title' =>
                                'Contractors',

                            'description' =>
                                'Contractor and supplier management.',

                            'active' =>
                                true,
                        ],

                        [
                            'title' =>
                                'Consultants',

                            'description' =>
                                'Consultant management and assignments.',

                            'active' =>
                                true,
                        ],

                        [
                            'title' =>
                                'Contracts',

                            'description' =>
                                'Construction contract monitoring.',

                            'active' =>
                                true,
                        ],

                        [
                            'title' =>
                                'Work Orders',

                            'description' =>
                                'Work order planning and site execution assignments.',

                            'active' =>
                                true,
                        ],
                        [
                            'title' =>
                                'Progress / Site Execution',

                            'description' =>
                                'Track physical construction progress and site execution.',

                            'active' =>
                                true,
                        ],

                        [
                            'title' =>
                                'Site Issues / RFI',

                            'description' =>
                                'Site issues, RFIs, technical queries, corrective actions and resolutions.',

                            'active' =>
                                true,
                        ],

                        [
                            'title' =>
                                'Schedule',

                            'description' =>
                                'Project activities, milestones and timelines.',

                            'active' =>
                                true,
                        ],

                        [
                            'title' =>
                                'Cost Control',

                            'description' =>
                                'Construction cost monitoring.',

                            'active' =>
                                true,
                        ],

                        [
                            'title' =>
                                'Site Instructions',

                            'description' =>
                                'Instructions issued to site teams and contractors.',

                            'active' =>
                                true,
                        ],
                        [
                            'title' =>
                                'Submittals',

                            'description' =>
                                'Technical and material submittal management.',

                            'active' =>
                                true,
                        ],

                        [
                            'title' =>
                                'Inspections',

                            'description' =>
                                'Construction inspection management.',

                            'active' =>
                                true,
                        ],

                        [
                            'title' =>
                                'Quality',

                            'description' =>
                                'Quality control and quality assurance.',

                            'active' =>
                                true,
                        ],

                        [
                            'title' =>
                                'HSE',

                            'description' =>
                                'Health, safety and environmental management.',

                            'active' =>
                                true,
                        ],

                        [
                            'title' =>
                                'Materials',

                            'description' =>
                                'Material planning, receipts and consumption.',

                            'active' =>
                                true,
                        ],

                        [
                            'title' =>
                                'Equipment',

                            'description' =>
                                'Construction equipment tracking.',

                            'active' =>
                                true,
                        ],

                        [
                            'title' =>
                                'Manpower',

                            'description' =>
                                'Site manpower tracking.',

                            'active' =>
                                true,
                        ],

                        [
                            'title' =>
                                'Variations',

                            'description' =>
                                'Contract variations and change orders.',

                            'active' =>
                                true,
                        ],

                        [
                            'title' =>
                                'Claims',

                            'description' =>
                                'Contractor claims and resolutions.',

                            'active' =>
                                true,
                        ],

                        [
                            'title' =>
                                'Payment Certificates',

                            'description' =>
                                'Construction payment certification.',

                            'active' =>
                                true,
                        ],

                        [
                            'title' =>
                                'Delays',

                            'description' =>
                                'Delay events and impact tracking.',

                            'active' =>
                                true,
                        ],

                        [
                            'title' =>
                                'Risks',

                            'description' =>
                                'Construction risk management.',

                            'active' =>
                                true,
                        ],                        

                        [
                            'title' =>
                                'Correspondence',

                            'description' =>
                                'Project communication and correspondence.',

                            'active' =>
                                true,
                        ],

                        [
                            'title' =>
                                'Site Reports',

                            'description' =>
                                'Daily and periodic site reporting.',

                            'active' =>
                                true,
                        ],

                    ];

                @endphp


                @foreach($modules as $module)

                    <div class="col-xl-3 col-lg-4 col-md-6">

                        <div class="border rounded p-3 h-100">

                            <div class="fw-semibold mb-1">
                                {{ $module['title'] }}
                            </div>

                            <div class="small text-muted mb-3">
                                {{ $module['description'] }}
                            </div>


                            @if($module['active'])

                                @if($module['title'] === 'Contractors')

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.contractors.index',
                                            $project
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Open Module
                                    </a>
                                @elseif($module['title'] === 'Project Dashboard')

                                    <a
                                        href="{{ url('/admin/projects/' . $project->id) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Open Module
                                </a>
                                @elseif($module['title'] === 'Consultants')

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.consultants.index',
                                            $project
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Open Module
                                </a>

                                @elseif($module['title'] === 'Contracts')

                                <a
                                    href="{{ route(
                                        'admin.projects.construction.contracts.index',
                                        $project
                                    ) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    Open Module
                                </a>
                                @elseif($module['title'] === 'Work Orders')

                                <a
                                    href="{{ route(
                                        'admin.projects.construction.work-orders.index',
                                        $project
                                    ) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    Open Module
                                </a>

                                @elseif(
                                    $module['title'] === 'Progress / Site Execution'
                                )

                                <a
                                    href="{{ route(
                                        'admin.projects.construction.progress.index',
                                        $project
                                    ) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    Open Module
                                </a>

                                @elseif(
                                    $module['title'] === 'Site Issues / RFI'
                                )

                                <a
                                    href="{{ route(
                                        'admin.projects.construction.site-issues.index',
                                        $project
                                    ) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    Open Module
                                </a>
                                @elseif(
                                    $module['title'] === 'Site Reports'
                                )

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.site-reports.index',
                                            $project
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Open Module
                                </a>
                                @elseif(
                                    $module['title'] === 'Schedule'
                                )

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.schedule.index',
                                            $project
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Open Module
                                </a>

                                @elseif(
                                    $module['title'] === 'Cost Control'
                                )

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.cost-control.index',
                                            $project
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Open Module
                                </a>

                                @elseif(
                                    $module['title'] === 'Variations'
                                )

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.variations.index',
                                            $project
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Open Module
                                </a>
                                @elseif(
                                    $module['title'] === 'Site Instructions'
                                )

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.site-instructions.index',
                                            $project
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Open Module
                                </a>

                                @elseif(
                                    $module['title'] === 'Submittals'
                                )

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.submittals.index',
                                            $project
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Open Module
                                </a>

                                @elseif(
                                    $module['title'] === 'Inspections'
                                )

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.inspections.index',
                                            $project
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Open Module
                                </a>

                                @elseif(
                                    $module['title'] === 'HSE'
                                )

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.hse.index',
                                            [
                                                'project' => $project,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Open Module
                                </a>
                                @elseif(
                                    $module['title'] === 'Materials'
                                )

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.materials.index',
                                            [
                                                'project' => $project,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Open Module
                                </a>

                                @elseif(
                                    $module['title'] === 'Equipment'
                                )

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.equipment.index',
                                            [
                                                'project' => $project,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Open Module
                                </a>

                                @elseif(
                                    $module['title'] === 'Manpower'
                                )

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.manpower.index',
                                            [
                                                'project' => $project,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Open Module
                                </a>
                                
                                @elseif(
                                    $module['title'] === 'Payment Certificates'
                                )

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.payment-certificates.index',
                                            [
                                                'project' => $project,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Open Module
                                </a>
                                @elseif(
                                    $module['title'] === 'Claims'
                                )

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.claims.index',
                                            [
                                                'project' => $project,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Open Module
                                </a>
                                @elseif(
                                    $module['title'] === 'Delays'
                                )

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.delays.index',
                                            [
                                                'project' => $project,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Open Module
                                </a>
                                @elseif(
                                    $module['title'] === 'Risks'
                                )

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.risks.index',
                                            [
                                                'project' => $project,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Open Module
                                </a>

                                @elseif(
                                    $module['title'] === 'Correspondence'
                                )

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.correspondence.index',
                                            [
                                                'project' => $project,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Open Module
                                </a>

                                @elseif(
                                    $module['title'] === 'Quality'
                                )

                                <div class="d-flex gap-2">

                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.quality.itps.index',
                                            $project
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        ITPs
                                    </a>


                                    <a
                                        href="{{ route(
                                            'admin.projects.construction.quality.ncrs.index',
                                            $project
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        NCRs
                                    </a>

                                </div>

                                @else

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Open Module
                                    </button>

                                @endif

                            @else

                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-secondary"
                                    disabled
                                >
                                    Coming Soon
                                </button>

                            @endif

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </div>


    {{-- ================================================================
         ACTIVE CONTRACTS
    ================================================================= --}}

    <div class="card">

        <div class="card-header">

            <strong>
                Construction Contracts
            </strong>

        </div>


        <div class="card-body p-0">

            @if($contracts->isNotEmpty())

                <div class="table-responsive">

                    <table
                        class="table table-hover align-middle mb-0"
                    >

                        <thead class="table-light">

                            <tr>

                                <th>
                                    Contract
                                </th>

                                <th>
                                    Contractor / Supplier
                                </th>

                                <th>
                                    Amount
                                </th>

                                <th>
                                    Start
                                </th>

                                <th>
                                    End
                                </th>

                                <th>
                                    Status
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($contracts as $contract)

                                <tr>

                                    <td>

                                        <div class="fw-semibold">

                                            {{
                                                $contract
                                                    ->contract_number
                                            }}

                                        </div>

                                        <div class="small text-muted">

                                            {{
                                                $contract
                                                    ->contract_title
                                            }}

                                        </div>

                                    </td>


                                    <td>

                                        {{
                                            $contract
                                                ->bidder_name
                                            ??
                                            $contract
                                                ->bidder
                                                ?->company_name
                                            ??
                                            '—'
                                        }}

                                    </td>


                                    <td>

                                        ${{
                                            number_format(
                                                (float)
                                                $contract
                                                    ->contract_amount,
                                                2
                                            )
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            $contract
                                                ->contract_start_date
                                                ?->format('d-m-Y')
                                            ??
                                            '—'
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            $contract
                                                ->contract_end_date
                                                ?->format('d-m-Y')
                                            ??
                                            '—'
                                        }}

                                    </td>


                                    <td>

                                        <span class="badge bg-secondary">

                                            {{
                                                $contract->status
                                            }}

                                        </span>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5 text-muted">

                    No procurement contracts found
                    for this project.

                </div>

            @endif

        </div>

    </div>

</div>

@endsection