@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Construction Management
            </div>

            <h4 class="mb-1">
                Cost Control Management Report
            </h4>

            <div class="text-muted">
                {{ $project->project_name }}
            </div>

        </div>


        <div class="d-flex gap-2">

            <button
                type="button"
                onclick="window.print()"
                class="btn btn-outline-primary"
            >
                Print Report
            </button>


            <a
                href="{{ route(
                    'admin.projects.construction.cost-control.index',
                    $project
                ) }}"
                class="btn btn-outline-secondary"
            >
                Back
            </a>

        </div>

    </div>


    {{-- Report Summary --}}

    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Approved Budget
                    </div>

                    <div class="fs-4 fw-semibold">

                        {{
                            number_format(
                                $summary['approved_budget'],
                                2
                            )
                        }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Revised Commitment
                    </div>

                    <div class="fs-4 fw-semibold">

                        {{
                            number_format(
                                $summary['revised_commitment'],
                                2
                            )
                        }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Paid
                    </div>

                    <div class="fs-4 fw-semibold text-success">

                        {{
                            number_format(
                                $summary['paid'],
                                2
                            )
                        }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Outstanding
                    </div>

                    <div class="fs-4 fw-semibold">

                        {{
                            number_format(
                                $summary['outstanding'],
                                2
                            )
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Financial Position --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Financial Position
            </strong>

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered align-middle mb-0">

                    <tbody>

                        <tr>

                            <th width="50%">
                                Approved Project Budget
                            </th>

                            <td class="text-end">

                                {{
                                    number_format(
                                        $summary['approved_budget'],
                                        2
                                    )
                                }}

                            </td>

                        </tr>


                        <tr>

                            <th>
                                Original Contract Commitments
                            </th>

                            <td class="text-end">

                                {{
                                    number_format(
                                        $summary['contracted'],
                                        2
                                    )
                                }}

                            </td>

                        </tr>


                        <tr>

                            <th>
                                Approved Variations
                            </th>

                            <td class="text-end">

                                {{
                                    number_format(
                                        $summary['variations'],
                                        2
                                    )
                                }}

                            </td>

                        </tr>


                        <tr>

                            <th>
                                Approved Other Costs
                            </th>

                            <td class="text-end">

                                {{
                                    number_format(
                                        $summary['other_costs'],
                                        2
                                    )
                                }}

                            </td>

                        </tr>


                        <tr class="table-light">

                            <th>
                                Revised Commitment
                            </th>

                            <td class="text-end fw-bold">

                                {{
                                    number_format(
                                        $summary['revised_commitment'],
                                        2
                                    )
                                }}

                            </td>

                        </tr>


                        <tr>

                            <th>
                                Invoiced
                            </th>

                            <td class="text-end">

                                {{
                                    number_format(
                                        $summary['invoiced'],
                                        2
                                    )
                                }}

                            </td>

                        </tr>


                        <tr>

                            <th>
                                Processed Payments
                            </th>

                            <td class="text-end">

                                {{
                                    number_format(
                                        $summary['paid'],
                                        2
                                    )
                                }}

                            </td>

                        </tr>


                        <tr>

                            <th>
                                Outstanding
                            </th>

                            <td class="text-end">

                                {{
                                    number_format(
                                        $summary['outstanding'],
                                        2
                                    )
                                }}

                            </td>

                        </tr>


                        <tr class="table-light">

                            <th>
                                Remaining Budget
                            </th>

                            <td class="text-end fw-bold">

                                {{
                                    number_format(
                                        $summary['remaining_budget'],
                                        2
                                    )
                                }}

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Forecast --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Forecast / Estimate at Completion
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Estimated At Completion
                    </div>

                    <div class="fs-4 fw-semibold">

                        {{
                            number_format(
                                $summary['estimated_at_completion'],
                                2
                            )
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Forecast Variance
                    </div>

                    <div
                        class="
                            fs-4
                            fw-semibold
                            {{
                                $summary['forecast_variance'] < 0
                                    ? 'text-danger'
                                    : 'text-success'
                            }}
                        "
                    >

                        {{
                            number_format(
                                abs(
                                    $summary['forecast_variance']
                                ),
                                2
                            )
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Forecast Status
                    </div>

                    <div class="mt-2">

                        @if(
                            $summary['forecast_status']
                            === 'Under Budget'
                        )

                            <span class="badge bg-success fs-6">
                                Under Budget
                            </span>

                        @elseif(
                            $summary['forecast_status']
                            === 'Over Budget'
                        )

                            <span class="badge bg-danger fs-6">
                                Over Budget
                            </span>

                        @else

                            <span class="badge bg-secondary fs-6">
                                On Budget
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Contract Summary --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Contract Summary
            </strong>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>
                                Contract
                            </th>

                            <th>
                                Contractor
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end">
                                Amount
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($contracts as $contract)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    {{ $contract->contract_number }}
                                </td>

                                <td>
                                    {{ $contract->bidder_name ?? '—' }}
                                </td>

                                <td>
                                    {{ $contract->status }}
                                </td>

                                <td class="text-end">

                                    {{
                                        number_format(
                                            (float)
                                            $contract->contract_amount,
                                            2
                                        )
                                    }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center py-4 text-muted"
                                >
                                    No contracts found.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Variations --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Approved Variations
            </strong>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>
                                Variation Number
                            </th>

                            <th>
                                Title
                            </th>

                            <th>
                                Date
                            </th>

                            <th class="text-end">
                                Amount
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($variations as $variation)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    {{ $variation->variation_number }}
                                </td>

                                <td>
                                    {{ $variation->title }}
                                </td>

                                <td>

                                    {{
                                        $variation
                                            ->variation_date
                                            ?->format('d-m-Y')
                                        ?? '—'
                                    }}

                                </td>

                                <td class="text-end">

                                    {{
                                        number_format(
                                            (float)
                                            $variation->amount,
                                            2
                                        )
                                    }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center py-4 text-muted"
                                >
                                    No approved variations.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Report Footer --}}

    <div class="text-muted small mt-4">

        Report generated:
        {{ now()->format('d-m-Y H:i') }}

    </div>

</div>


<style>

@media print {

    .navbar,
    .sidebar,
    .btn,
    nav {
        display: none !important;
    }

    body {
        background: #fff !important;
    }

    .card {
        border: 1px solid #ddd !important;
        box-shadow: none !important;
    }

}

</style>

@endsection