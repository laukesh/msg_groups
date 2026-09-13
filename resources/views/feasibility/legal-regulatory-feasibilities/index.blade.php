@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                Legal & Regulatory Feasibility
            </h3>

            <p class="text-muted mb-0">

                {{ $feasibilityAssessment->assessment_number }}
                -
                {{ $feasibilityAssessment->title }}

            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.show',
                    [
                        'land' => $land->id,

                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                    ]
                ) }}"
                class="btn btn-secondary"
            >
                ← Back
            </a>


            <a
                href="{{ route(
                    'admin.land.lands.feasibility-assessments.legal-regulatory-feasibilities.create',
                    [
                        'land' => $land->id,

                        'feasibilityAssessment' =>
                            $feasibilityAssessment->id,
                    ]
                ) }}"
                class="btn btn-primary"
            >
                + New Legal Analysis
            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Success --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- Table --}}
    {{-- ========================================================= --}}

    <div class="card">

        <div class="card-header">

            <strong>
                Legal & Regulatory Assessments
            </strong>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover table-bordered mb-0 align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Analysis Number
                            </th>

                            <th>
                                Title
                            </th>

                            <th>
                                Ownership
                            </th>

                            <th>
                                Title Verification
                            </th>

                            <th>
                                Zoning
                            </th>

                            <th>
                                Compliance
                            </th>

                            <th>
                                Legal Score
                            </th>

                            <th>
                                Status
                            </th>

                            <th style="width: 210px;">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse(
                        $legalRegulatoryFeasibilities
                        as $legal
                    )

                        <tr>

                            {{-- Analysis Number --}}
                            <td>

                                <strong>
                                    {{ $legal->analysis_number }}
                                </strong>

                            </td>


                            {{-- Title --}}
                            <td>
                                {{ $legal->title }}
                            </td>


                            {{-- Ownership --}}
                            <td>

                                @if(
                                    $legal->ownership_status
                                )

                                    <span
                                        class="badge
                                        {{
                                            strtolower(
                                                $legal->ownership_status
                                            ) === 'clear'
                                                ? 'bg-success'
                                                : 'bg-warning text-dark'
                                        }}"
                                    >
                                        {{ $legal->ownership_status }}
                                    </span>

                                @else

                                    -

                                @endif

                            </td>


                            {{-- Title Verification --}}
                            <td>

                                @if(
                                    $legal
                                        ->title_verification_status
                                )

                                    <span class="badge bg-secondary">

                                        {{
                                            $legal
                                                ->title_verification_status
                                        }}

                                    </span>

                                @else

                                    -

                                @endif

                            </td>


                            {{-- Zoning --}}
                            <td>

                                {{ $legal->zoning_type ?? '-' }}

                                @if(
                                    $legal->zoning_status
                                )

                                    <div class="small text-muted">

                                        {{ $legal->zoning_status }}

                                    </div>

                                @endif

                            </td>


                            {{-- Compliance --}}
                            <td>

                                @if(
                                    $legal->compliance_status
                                )

                                    <span class="badge bg-secondary">

                                        {{
                                            $legal->compliance_status
                                        }}

                                    </span>

                                @else

                                    -

                                @endif

                            </td>


                            {{-- Score --}}
                            <td>

                                @if(
                                    $legal
                                        ->overall_legal_score
                                        !== null
                                )

                                    <strong>

                                        {{
                                            number_format(
                                                $legal
                                                    ->overall_legal_score,
                                                2
                                            )
                                        }}

                                    </strong>

                                    <span class="text-muted">
                                        / 100
                                    </span>

                                @else

                                    -

                                @endif

                            </td>


                            {{-- Status --}}
                            <td>

                                @if(
                                    $legal->status === 'Draft'
                                )

                                    <span class="badge bg-secondary">
                                        Draft
                                    </span>

                                @elseif(
                                    $legal->status === 'Submitted'
                                )

                                    <span class="badge bg-warning text-dark">
                                        Submitted
                                    </span>

                                @elseif(
                                    $legal->status === 'Approved'
                                )

                                    <span class="badge bg-success">
                                        Approved
                                    </span>

                                @elseif(
                                    $legal->status === 'Rejected'
                                )

                                    <span class="badge bg-danger">
                                        Rejected
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        {{ $legal->status ?? 'N/A' }}
                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td>

                                <div class="d-flex gap-1">

                                    {{-- View --}}
                                    <a
                                        href="{{ route(
                                            'admin.land.lands.feasibility-assessments.legal-regulatory-feasibilities.show',
                                            [
                                                'land' => $land->id,

                                                'feasibilityAssessment' =>
                                                    $feasibilityAssessment->id,

                                                'legalRegulatoryFeasibility' =>
                                                    $legal->id,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        View
                                    </a>


                                    {{-- Edit --}}
                                    <a
                                        href="{{ route(
                                            'admin.land.lands.feasibility-assessments.legal-regulatory-feasibilities.edit',
                                            [
                                                'land' => $land->id,

                                                'feasibilityAssessment' =>
                                                    $feasibilityAssessment->id,

                                                'legalRegulatoryFeasibility' =>
                                                    $legal->id,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-secondary"
                                    >
                                        Edit
                                    </a>


                                    {{-- Delete --}}
                                    <form
                                        action="{{ route(
                                            'admin.land.lands.feasibility-assessments.legal-regulatory-feasibilities.destroy',
                                            [
                                                'land' => $land->id,

                                                'feasibilityAssessment' =>
                                                    $feasibilityAssessment->id,

                                                'legalRegulatoryFeasibility' =>
                                                    $legal->id,
                                            ]
                                        ) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this Legal & Regulatory Feasibility?');"
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

                                    No Legal & Regulatory
                                    Feasibility records found.

                                </div>


                                <a
                                    href="{{ route(
                                        'admin.land.lands.feasibility-assessments.legal-regulatory-feasibilities.create',
                                        [
                                            'land' => $land->id,

                                            'feasibilityAssessment' =>
                                                $feasibilityAssessment->id,
                                        ]
                                    ) }}"
                                    class="btn btn-sm btn-primary"
                                >
                                    + Create Legal Analysis
                                </a>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if(
            $legalRegulatoryFeasibilities->hasPages()
        )

            <div class="card-footer">

                {{
                    $legalRegulatoryFeasibilities->links()
                }}

            </div>

        @endif

    </div>

</div>

@endsection