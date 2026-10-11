@extends('layouts.app')

@section('title', 'Area Summary')

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <h1 class="h3 mb-1">Area Summary</h1>
        <p class="text-muted">
            Consolidated property area overview.
        </p>
    </div>

    <div class="row g-4">

        @foreach([
            'Total Properties' => $summary->total_properties ?? 0,
            'Gross Area' => $summary->total_gross_area ?? 0,
            'Net Area' => $summary->total_net_area ?? 0,
            'Usable Area' => $summary->total_usable_area ?? 0,
            'Rentable Area' => $summary->total_rentable_area ?? 0,
            'Chargeable Area' => $summary->total_chargeable_area ?? 0,
            'Common Area' => $summary->total_common_area ?? 0,
            'Service Area' => $summary->total_service_area ?? 0,
        ] as $label => $value)

            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted small">
                            {{ $label }}
                        </div>

                        <div class="fs-4 fw-semibold mt-1">
                            {{ is_numeric($value)
                                ? number_format($value, 2)
                                : $value }}
                        </div>
                    </div>
                </div>
            </div>

        @endforeach

    </div>

</div>
@endsection
