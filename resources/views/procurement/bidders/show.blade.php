@extends('layouts.app')

@section('content')
<style type="text/css">
    .bg-white{
        color: black;
    }
</style>
<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="d-flex align-items-center gap-3 mb-2">

                <div
                    class="rounded-circle bg-primary bg-opacity-10
                           d-flex align-items-center justify-content-center"
                    style="width: 52px; height: 52px;"
                >
                    <i class="ri-building-4-line fs-3 text-primary"></i>
                </div>

                <div>

                    <h4 class="mb-1 fw-bold">
                        {{ $procurementBidder->company_name }}
                    </h4>

                    <div class="d-flex align-items-center gap-2">

                        <span class="text-muted">
                            Bidder Code:
                        </span>

                        <span class="badge bg-light text-dark border">
                            {{ $procurementBidder->bidder_code }}
                        </span>

                    </div>

                </div>

            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.procurement.bidders.edit',
                    $procurementBidder
                ) }}"
                class="btn btn-primary"
            >
                <i class="ri-edit-line me-1"></i>
                Edit
            </a>


            <a
                href="{{ route(
                    'admin.procurement.bidders.index'
                ) }}"
                class="btn btn-outline-secondary"
            >
                <i class="ri-arrow-left-line me-1"></i>
                Back
            </a>

        </div>

    </div>


    {{-- =========================================================
        FLASH MESSAGES
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="ri-checkbox-circle-line me-1"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="ri-error-warning-line me-1"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- =========================================================
        PROFILE SUMMARY
    ========================================================== --}}

    <div class="row g-3 mb-4">


        {{-- Status --}}
        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small mb-2">
                                Status
                            </div>

                            @php

                                $statusClass = match(
                                    $procurementBidder->status
                                ) {

                                    'Active' =>
                                        'bg-success',

                                    'Inactive' =>
                                        'bg-secondary',

                                    'Blacklisted' =>
                                        'bg-danger',

                                    default =>
                                        'bg-secondary',

                                };

                            @endphp


                            <span class="badge {{ $statusClass }} fs-6">
                                {{ $procurementBidder->status }}
                            </span>

                        </div>


                        <div
                            class="rounded-circle
                                   bg-success bg-opacity-10
                                   d-flex align-items-center
                                   justify-content-center"
                            style="width: 42px; height: 42px;"
                        >
                            <i class="ri-checkbox-circle-line
                                      text-success fs-5"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Contact Person --}}
        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small mb-2">
                                Contact Person
                            </div>

                            <div class="fw-semibold">
                                {{ $procurementBidder->contact_person ?: '—' }}
                            </div>

                        </div>


                        <div
                            class="rounded-circle
                                   bg-primary bg-opacity-10
                                   d-flex align-items-center
                                   justify-content-center"
                            style="width: 42px; height: 42px;"
                        >
                            <i class="ri-user-line text-primary fs-5"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Email --}}
        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small mb-2">
                                Email
                            </div>

                            <div
                                class="fw-semibold text-break"
                            >
                                {{ $procurementBidder->email ?: '—' }}
                            </div>

                        </div>


                        <div
                            class="rounded-circle
                                   bg-info bg-opacity-10
                                   d-flex align-items-center
                                   justify-content-center"
                            style="width: 42px; height: 42px;"
                        >
                            <i class="ri-mail-line text-info fs-5"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Phone --}}
        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small mb-2">
                                Phone
                            </div>

                            <div class="fw-semibold">
                                {{ $procurementBidder->phone ?: '—' }}
                            </div>

                        </div>


                        <div
                            class="rounded-circle
                                   bg-warning bg-opacity-10
                                   d-flex align-items-center
                                   justify-content-center"
                            style="width: 42px; height: 42px;"
                        >
                            <i class="ri-phone-line text-warning fs-5"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        MAIN CONTENT
    ========================================================== --}}

    <div class="row g-4">


        {{-- =====================================================
            LEFT COLUMN
        ====================================================== --}}

        <div class="col-lg-8">


            {{-- =================================================
                COMPANY INFORMATION
            ================================================== --}}

            <div class="card mb-4">

                <div class="card-header bg-white py-3">

                    <div class="d-flex align-items-center">

                        <div
                            class="rounded-circle
                                   bg-primary bg-opacity-10
                                   d-flex align-items-center
                                   justify-content-center me-2"
                            style="width: 36px; height: 36px;"
                        >
                            <i class="ri-building-line text-primary"></i>
                        </div>

                        <div>

                            <h6 class="mb-0 fw-bold">
                                Company Information
                            </h6>

                            <small class="text-muted">
                                Registered bidder details
                            </small>

                        </div>

                    </div>

                </div>


                <div class="card-body">

                    <div class="row g-4">


                        {{-- Company Name --}}
                        <div class="col-md-8">

                            <div class="text-muted small mb-1">
                                Company Name
                            </div>

                            <div class="fw-semibold fs-6">
                                {{ $procurementBidder->company_name }}
                            </div>

                        </div>


                        {{-- Bidder Code --}}
                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Bidder Code
                            </div>

                            <div>

                                <span class="badge bg-primary">
                                    {{ $procurementBidder->bidder_code }}
                                </span>

                            </div>

                        </div>


                        <div class="col-12">
                            <hr class="my-0">
                        </div>


                        {{-- Registration --}}
                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Registration No.
                            </div>

                            <div class="fw-medium">

                                {{
                                    $procurementBidder
                                        ->company_registration_no
                                    ?: '—'
                                }}

                            </div>

                        </div>


                        {{-- GST --}}
                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                GST Number
                            </div>

                            <div class="fw-medium">

                                {{
                                    $procurementBidder->gst_number
                                    ?: '—'
                                }}

                            </div>

                        </div>


                        {{-- PAN --}}
                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                PAN Number
                            </div>

                            <div class="fw-medium">

                                {{
                                    $procurementBidder->pan_number
                                    ?: '—'
                                }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                ADDRESS
            ================================================== --}}

            <div class="card mb-4">

                <div class="card-header bg-white py-3">

                    <div class="d-flex align-items-center">

                        <div
                            class="rounded-circle
                                   bg-info bg-opacity-10
                                   d-flex align-items-center
                                   justify-content-center me-2"
                            style="width: 36px; height: 36px;"
                        >
                            <i class="ri-map-pin-line text-info"></i>
                        </div>

                        <div>

                            <h6 class="mb-0 fw-bold">
                                Address
                            </h6>

                            <small class="text-muted">
                                Registered business address
                            </small>

                        </div>

                    </div>

                </div>


                <div class="card-body">


                    <div class="mb-4">

                        <div class="text-muted small mb-1">
                            Address
                        </div>

                        <div class="fw-medium">

                            {!! nl2br(
                                e(
                                    $procurementBidder->address
                                    ?: '—'
                                )
                            ) !!}

                        </div>

                    </div>


                    <div class="row g-4">

                        <div class="col-md-3">

                            <div class="text-muted small mb-1">
                                City
                            </div>

                            <div class="fw-medium">
                                {{ $procurementBidder->city ?: '—' }}
                            </div>

                        </div>


                        <div class="col-md-3">

                            <div class="text-muted small mb-1">
                                State
                            </div>

                            <div class="fw-medium">
                                {{ $procurementBidder->state ?: '—' }}
                            </div>

                        </div>


                        <div class="col-md-3">

                            <div class="text-muted small mb-1">
                                Country
                            </div>

                            <div class="fw-medium">
                                {{ $procurementBidder->country ?: '—' }}
                            </div>

                        </div>


                        <div class="col-md-3">

                            <div class="text-muted small mb-1">
                                Postal Code
                            </div>

                            <div class="fw-medium">
                                {{ $procurementBidder->postal_code ?: '—' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                CONTACT INFORMATION
            ================================================== --}}

            <div class="card mb-4">

                <div class="card-header bg-white py-3">

                    <div class="d-flex align-items-center">

                        <div
                            class="rounded-circle
                                   bg-success bg-opacity-10
                                   d-flex align-items-center
                                   justify-content-center me-2"
                            style="width: 36px; height: 36px;"
                        >
                            <i class="ri-contacts-line text-success"></i>
                        </div>

                        <div>

                            <h6 class="mb-0 fw-bold">
                                Contact Information
                            </h6>

                            <small class="text-muted">
                                Primary bidder contact details
                            </small>

                        </div>

                    </div>

                </div>


                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Contact Person
                            </div>

                            <div class="fw-medium">
                                {{
                                    $procurementBidder
                                        ->contact_person
                                    ?: '—'
                                }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Email
                            </div>

                            <div class="fw-medium text-break">
                                {{
                                    $procurementBidder->email
                                    ?: '—'
                                }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Phone
                            </div>

                            <div class="fw-medium">
                                {{
                                    $procurementBidder->phone
                                    ?: '—'
                                }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                TENDER PARTICIPATION
            ================================================== --}}

            <div class="card mb-4">

                <div class="card-header bg-white py-3">

                    <div
                        class="d-flex justify-content-between
                               align-items-center"
                    >

                        <div class="d-flex align-items-center">

                            <div
                                class="rounded-circle
                                       bg-warning bg-opacity-10
                                       d-flex align-items-center
                                       justify-content-center me-2"
                                style="width: 36px; height: 36px;"
                            >
                                <i class="ri-auction-line text-warning"></i>
                            </div>

                            <div>

                                <h6 class="mb-0 fw-bold">
                                    Tender Participation
                                </h6>

                                <small class="text-muted">
                                    Tenders in which this bidder
                                    has participated
                                </small>

                            </div>

                        </div>


                        <span class="badge bg-primary">

                            {{
                                $procurementBidder
                                    ->tenderBidders
                                    ->count()
                            }}

                            Tenders

                        </span>

                    </div>

                </div>


                <div class="card-body p-0">

                    @if(
                        $procurementBidder
                            ->tenderBidders
                            ->count()
                    )

                        <div class="table-responsive">

                            <table
                                class="table table-hover
                                       align-middle mb-0"
                            >

                                <thead class="table-light">

                                    <tr>

                                        <th class="ps-4">
                                            Tender
                                        </th>

                                        <th>
                                            Package
                                        </th>

                                        <th>
                                            Participation
                                        </th>

                                        <th>
                                            Registration Date
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach(
                                        $procurementBidder
                                            ->tenderBidders
                                        as $tenderBidder
                                    )

                                        <tr>

                                            <td class="ps-4">

                                                <a
                                                    href="{{ route(
                                                        'admin.procurement.tenders.show',
                                                        $tenderBidder->tender
                                                    ) }}"
                                                    class="fw-semibold
                                                           text-decoration-none"
                                                >

                                                    {{
                                                        $tenderBidder
                                                            ->tender
                                                            ->tender_number
                                                    }}

                                                </a>

                                                <div class="small text-muted">

                                                    {{
                                                        $tenderBidder
                                                            ->tender
                                                            ->tender_title
                                                    }}

                                                </div>

                                            </td>


                                            <td>

                                                <span
                                                    class="badge
                                                           bg-light
                                                           text-dark
                                                           border"
                                                >

                                                    {{
                                                        $tenderBidder
                                                            ->tender
                                                            ->procurementPackage
                                                            ->package_number
                                                    }}

                                                </span>

                                            </td>


                                            <td>

                                                @php

                                                    $participationClass =
                                                        match(
                                                            $tenderBidder
                                                                ->participation_status
                                                        ) {

                                                            'Invited',
                                                            'Registered' =>
                                                                'bg-info',

                                                            'Participated',
                                                            'Submitted' =>
                                                                'bg-primary',

                                                            'Qualified',
                                                            'Accepted' =>
                                                                'bg-success',

                                                            'Rejected' =>
                                                                'bg-danger',

                                                            default =>
                                                                'bg-secondary',

                                                        };

                                                @endphp


                                                <span
                                                    class="badge
                                                           {{ $participationClass }}"
                                                >

                                                    {{
                                                        $tenderBidder
                                                            ->participation_status
                                                    }}

                                                </span>

                                            </td>


                                            <td>

                                                <span class="text-muted">

                                                    {{
                                                        $tenderBidder
                                                            ->registration_date
                                                            ? $tenderBidder
                                                                ->registration_date
                                                                ->format('d M Y')
                                                            : '—'
                                                    }}

                                                </span>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="text-center py-5">

                            <div
                                class="rounded-circle
                                       bg-light
                                       d-inline-flex
                                       align-items-center
                                       justify-content-center
                                       mb-3"
                                style="width: 56px; height: 56px;"
                            >
                                <i class="ri-auction-line
                                          fs-4
                                          text-muted"></i>
                            </div>

                            <h6 class="mb-1">
                                No Tender Participation
                            </h6>

                            <p class="text-muted mb-0">
                                This bidder has not participated
                                in any Tender yet.
                            </p>

                        </div>

                    @endif

                </div>

            </div>


            {{-- =================================================
                REMARKS
            ================================================== --}}

            <div class="card">

                <div class="card-header bg-white py-3">

                    <div class="d-flex align-items-center">

                        <div
                            class="rounded-circle
                                   bg-secondary bg-opacity-10
                                   d-flex align-items-center
                                   justify-content-center me-2"
                            style="width: 36px; height: 36px;"
                        >
                            <i class="ri-file-text-line text-secondary"></i>
                        </div>

                        <div>

                            <h6 class="mb-0 fw-bold">
                                Remarks
                            </h6>

                            <small class="text-muted">
                                Additional bidder information
                            </small>

                        </div>

                    </div>

                </div>


                <div class="card-body">

                    @if($procurementBidder->remarks)

                        <div
                            class="bg-light rounded p-3"
                        >

                            {!! nl2br(
                                e(
                                    $procurementBidder->remarks
                                )
                            ) !!}

                        </div>

                    @else

                        <div class="text-muted">
                            No remarks have been added.
                        </div>

                    @endif

                </div>

            </div>


        </div>


        {{-- =====================================================
            RIGHT COLUMN
        ====================================================== --}}

        <div class="col-lg-4">


            {{-- =================================================
                BIDDER PROFILE
            ================================================== --}}

            <div class="card mb-4">

                <div class="card-header bg-white py-3">

                    <strong>
                        Bidder Profile
                    </strong>

                </div>


                <div class="card-body">

                    <div class="text-center mb-4">

                        <div
                            class="rounded-circle
                                   bg-primary
                                   bg-opacity-10
                                   d-inline-flex
                                   align-items-center
                                   justify-content-center
                                   mb-3"
                            style="width: 80px; height: 80px;"
                        >

                            <i
                                class="ri-building-4-line
                                       text-primary"
                                style="font-size: 36px;"
                            ></i>

                        </div>


                        <h5 class="mb-1">
                            {{ $procurementBidder->company_name }}
                        </h5>


                        <div class="text-muted">

                            {{
                                $procurementBidder->bidder_code
                            }}

                        </div>

                    </div>


                    <div class="border-top pt-3">

                        <div
                            class="d-flex justify-content-between
                                   mb-3"
                        >

                            <span class="text-muted">
                                Status
                            </span>

                            <span class="badge {{ $statusClass }}">
                                {{ $procurementBidder->status }}
                            </span>

                        </div>


                        <div
                            class="d-flex justify-content-between
                                   mb-3"
                        >

                            <span class="text-muted">
                                Tender Participation
                            </span>

                            <strong>

                                {{
                                    $procurementBidder
                                        ->tenderBidders
                                        ->count()
                                }}

                            </strong>

                        </div>


                        <div
                            class="d-flex justify-content-between"
                        >

                            <span class="text-muted">
                                Country
                            </span>

                            <strong>
                                {{
                                    $procurementBidder->country
                                    ?: '—'
                                }}
                            </strong>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                QUICK CONTACT
            ================================================== --}}

            <div class="card mb-4">

                <div class="card-header bg-white py-3">

                    <strong>
                        Quick Contact
                    </strong>

                </div>


                <div class="card-body">


                    @if($procurementBidder->email)

                        <div class="d-flex align-items-center mb-3">

                            <div
                                class="rounded-circle
                                       bg-primary bg-opacity-10
                                       d-flex align-items-center
                                       justify-content-center me-3"
                                style="width: 38px; height: 38px;"
                            >

                                <i class="ri-mail-line text-primary"></i>

                            </div>


                            <div>

                                <div class="text-muted small">
                                    Email
                                </div>

                                <div
                                    class="fw-medium text-break"
                                >
                                    {{ $procurementBidder->email }}
                                </div>

                            </div>

                        </div>

                    @endif


                    @if($procurementBidder->phone)

                        <div class="d-flex align-items-center">

                            <div
                                class="rounded-circle
                                       bg-success bg-opacity-10
                                       d-flex align-items-center
                                       justify-content-center me-3"
                                style="width: 38px; height: 38px;"
                            >

                                <i class="ri-phone-line text-success"></i>

                            </div>


                            <div>

                                <div class="text-muted small">
                                    Phone
                                </div>

                                <div class="fw-medium">
                                    {{ $procurementBidder->phone }}
                                </div>

                            </div>

                        </div>

                    @endif


                    @if(
                        !$procurementBidder->email &&
                        !$procurementBidder->phone
                    )

                        <div class="text-muted">
                            No contact information available.
                        </div>

                    @endif

                </div>

            </div>


            {{-- =================================================
                LOCATION
            ================================================== --}}

            <div class="card">

                <div class="card-header bg-white py-3">

                    <strong>
                        Location
                    </strong>

                </div>


                <div class="card-body">

                    <div class="d-flex">

                        <div
                            class="rounded-circle
                                   bg-info bg-opacity-10
                                   d-flex align-items-center
                                   justify-content-center me-3"
                            style="width: 42px; height: 42px;"
                        >

                            <i
                                class="ri-map-pin-line
                                       text-info fs-5"
                            ></i>

                        </div>


                        <div>

                            <div class="fw-semibold">

                                {{
                                    $procurementBidder->city
                                    ?: '—'
                                }}

                            </div>

                            <div class="text-muted">

                                {{
                                    $procurementBidder->state
                                    ?: '—'
                                }}

                            </div>

                            <div class="text-muted">

                                {{
                                    $procurementBidder->country
                                    ?: '—'
                                }}

                                @if(
                                    $procurementBidder->postal_code
                                )

                                    -
                                    {{
                                        $procurementBidder
                                            ->postal_code
                                    }}

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>


        </div>

    </div>

</div>

@endsection