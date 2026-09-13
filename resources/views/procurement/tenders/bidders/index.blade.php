@extends('layouts.app')

@section('content')

<div class="container-fluid">


    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="text-muted small mb-1">
                Procurement Tender
            </div>

            <h4 class="mb-1">
                Tender Bidders
            </h4>

            <div class="text-muted">

                {{ $procurementTender->tender_number }}

                @if($procurementTender->tender_title)

                    -
                    {{ $procurementTender->tender_title }}

                @endif

            </div>

        </div>


        <div class="d-flex gap-2">


            {{-- Back to Tender --}}
            <a
                href="{{ route(
                    'admin.procurement.tenders.show',
                    $procurementTender
                ) }}"
                class="btn btn-outline-primary"
            >

                <i class="ri-arrow-left-line me-1"></i>

                Back to Tender

            </a>


            {{-- Prequalification --}}
            <a
                href="{{ route(
                    'admin.procurement.tenders.prequalifications.index',
                    $procurementTender
                ) }}"
                class="btn btn-outline-primary"
            >

                <i class="ri-file-check-line me-1"></i>

                Prequalification

            </a>


            {{-- Back --}}
            <a
                href="{{ route(
                    'admin.procurement.tenders.index'
                ) }}"
                class="btn btn-outline-secondary"
            >

                Back

            </a>

        </div>

    </div>



    {{-- =========================================================
        MESSAGES
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

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

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following:
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



    {{-- =========================================================
        1. TENDER INFORMATION
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <div class="d-flex align-items-center">

                <i class="ri-file-list-3-line me-2 text-primary"></i>

                <strong>
                    Tender Information
                </strong>

            </div>

        </div>


        <div class="card-body">

            <div class="row g-4">


                {{-- Tender Number --}}
                <div class="col-md-3">

                    <div class="text-muted small mb-1">
                        Tender Number
                    </div>

                    <div class="fw-semibold">
                        {{ $procurementTender->tender_number }}
                    </div>

                </div>


                {{-- Tender Title --}}
                <div class="col-md-5">

                    <div class="text-muted small mb-1">
                        Tender Title
                    </div>

                    <div class="fw-semibold">
                        {{ $procurementTender->tender_title }}
                    </div>

                </div>


                {{-- Tender Type --}}
                <div class="col-md-2">

                    <div class="text-muted small mb-1">
                        Tender Type
                    </div>

                    <div>
                        {{ $procurementTender->tender_type ?? '—' }}
                    </div>

                </div>


                {{-- Status --}}
                <div class="col-md-2">

                    <div class="text-muted small mb-1">
                        Status
                    </div>

                    <span class="badge bg-secondary">

                        {{ $procurementTender->status ?? '—' }}

                    </span>

                </div>


            </div>

        </div>

    </div>



    {{-- =========================================================
        2. ADD BIDDER
    ========================================================== --}}

    <div class="card mb-4">

        <div class="card-header">

            <div>

                <strong>
                    Add Bidder
                </strong>

                <div class="small text-muted mt-1">
                    Assign a bidder to this Tender
                </div>

            </div>

        </div>


        <div class="card-body">

            @if($availableBidders->count())


                <form
                    method="POST"
                    action="{{ route(
                        'admin.procurement.tenders.bidders.store',
                        $procurementTender
                    ) }}"
                >

                    @csrf


                    <div class="row g-3">


                        {{-- =================================================
                            BIDDER
                        ================================================== --}}

                        <div class="col-md-6">

                            <label class="form-label">

                                Bidder

                                <span class="text-danger">
                                    *
                                </span>

                            </label>


                            <select
                                name="procurement_bidder_id"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    -- Select Bidder --
                                </option>


                                @foreach(
                                    $availableBidders
                                    as $bidder
                                )

                                    <option
                                        value="{{ $bidder->id }}"
                                        @selected(
                                            old(
                                                'procurement_bidder_id'
                                            ) == $bidder->id
                                        )
                                    >

                                        {{ $bidder->company_name }}

                                        -

                                        {{ $bidder->bidder_code }}

                                    </option>

                                @endforeach

                            </select>

                        </div>



                        {{-- =================================================
                            REFERENCE
                        ================================================== --}}

                        <div class="col-md-3">

                            <label class="form-label">

                                Bidder Reference No.

                            </label>


                            <input
                                type="text"
                                class="form-control"
                                value="Auto-generated"
                                readonly
                            >


                            <div class="form-text">

                                Generated automatically.

                            </div>

                        </div>



                        {{-- =================================================
                            INVITATION DATE
                        ================================================== --}}

                        <div class="col-md-3">

                            <label class="form-label">

                                Invitation Date

                            </label>


                            <input
                                type="date"
                                name="invitation_date"
                                class="form-control"
                                value="{{ old(
                                    'invitation_date'
                                ) }}"
                            >

                        </div>



                        {{-- =================================================
                            REGISTRATION DATE
                        ================================================== --}}

                        <div class="col-md-3">

                            <label class="form-label">

                                Registration Date

                            </label>


                            <input
                                type="date"
                                name="registration_date"
                                class="form-control"
                                value="{{ old(
                                    'registration_date'
                                ) }}"
                            >

                        </div>



                        {{-- =================================================
                            PARTICIPATION STATUS
                        ================================================== --}}

                        <div class="col-md-3">

                            <label class="form-label">

                                Participation Status

                                <span class="text-danger">
                                    *
                                </span>

                            </label>


                            <select
                                name="participation_status"
                                class="form-select"
                                required
                            >

                                @foreach([
                                    'Invited',
                                    'Registered',
                                    'Participating',
                                    'Withdrawn',
                                    'Disqualified',
                                    'Awarded',
                                ] as $status)

                                    <option
                                        value="{{ $status }}"
                                        @selected(
                                            old(
                                                'participation_status',
                                                'Invited'
                                            ) === $status
                                        )
                                    >

                                        {{ $status }}

                                    </option>

                                @endforeach

                            </select>

                        </div>



                        {{-- =================================================
                            PREQUALIFICATION
                        ================================================== --}}

                        <div class="col-md-3">

                            <label class="form-label d-block">

                                Prequalification

                            </label>


                            <div class="form-check form-switch mt-2">

                                <input
                                    type="checkbox"
                                    name="prequalification_required"
                                    value="1"
                                    class="form-check-input"
                                    id="prequalification_required"
                                    @checked(
                                        old(
                                            'prequalification_required'
                                        )
                                    )
                                >


                                <label
                                    class="form-check-label"
                                    for="prequalification_required"
                                >

                                    Required

                                </label>

                            </div>

                        </div>



                        {{-- =================================================
                            REMARKS
                        ================================================== --}}

                        <div class="col-md-6">

                            <label class="form-label">

                                Remarks

                            </label>


                            <textarea
                                name="remarks"
                                rows="2"
                                class="form-control"
                                placeholder="Additional remarks"
                            >{{ old('remarks') }}</textarea>

                        </div>



                        {{-- =================================================
                            BUTTON
                        ================================================== --}}

                        <div class="col-md-3 d-flex align-items-end">

                            <button
                                type="submit"
                                class="btn btn-primary w-100"
                            >

                                <i class="ri-user-add-line me-1"></i>

                                Add Bidder

                            </button>

                        </div>


                    </div>

                </form>


            @else


                <div class="text-center py-4">

                    <div class="mb-3">

                        <i
                            class="ri-user-search-line text-muted"
                            style="font-size:42px;"
                        ></i>

                    </div>


                    <h6 class="mb-1">

                        No Active Bidders Available

                    </h6>


                    <div class="text-muted mb-3">

                        All available bidders may already be
                        assigned to this Tender.

                    </div>


                    <a
                        href="{{ route(
                            'admin.procurement.bidders.create'
                        ) }}"
                        class="btn btn-outline-primary"
                    >

                        <i class="ri-user-add-line me-1"></i>

                        Create Bidder

                    </a>

                </div>

            @endif

        </div>

    </div>



    {{-- =========================================================
        3. ASSIGNED BIDDERS
    ========================================================== --}}

    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Assigned Bidders
                    </strong>

                    <div class="small text-muted mt-1">
                        Bidders currently assigned to this Tender
                    </div>

                </div>


                <div class="d-flex align-items-center gap-2">

                    <span class="text-muted small">
                        Total
                    </span>

                    <span class="badge bg-primary rounded-pill px-3">

                        {{
                            $procurementTender
                                ->tenderBidders
                                ->count()
                        }}

                    </span>

                </div>

            </div>

        </div>


        <div class="card-body p-0">


            @if(
                $procurementTender
                    ->tenderBidders
                    ->count()
            )


                <div class="table-responsive">

                    <table
                        class="table table-hover align-middle mb-0"
                    >

                        <thead class="table-light">

                        <tr>

                            <th
                                class="ps-4"
                                style="width:60px;"
                            >
                                #
                            </th>

                            <th>
                                Bidder
                            </th>

                            <th>
                                Reference No.
                            </th>

                            <th>
                                Participation
                            </th>

                            <th>
                                Prequalification
                            </th>

                            <th>
                                Registration
                            </th>

                            <th
                                class="text-end pe-4"
                            >
                                Action
                            </th>

                        </tr>

                        </thead>


                        <tbody>

                        @foreach(
                            $procurementTender->tenderBidders
                            as $tenderBidder
                        )

                            <tr>


                                {{-- =================================================
                                    #
                                ================================================== --}}

                                <td class="ps-4">

                                    <span class="text-muted">

                                        {{ $loop->iteration }}

                                    </span>

                                </td>



                                {{-- =================================================
                                    BIDDER
                                ================================================== --}}

                                <td>

                                    <div class="d-flex align-items-center">

                                        <div
                                            class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2"
                                            style="width:38px;height:38px;"
                                        >

                                            <i
                                                class="ri-building-line text-primary"
                                            ></i>

                                        </div>


                                        <div>

                                            <div class="fw-semibold">

                                                {{
                                                    $tenderBidder
                                                        ->bidder
                                                        ->company_name
                                                }}

                                            </div>


                                            <div class="small text-muted">

                                                {{
                                                    $tenderBidder
                                                        ->bidder
                                                        ->bidder_code
                                                }}

                                            </div>

                                        </div>

                                    </div>

                                </td>



                                {{-- =================================================
                                    REFERENCE
                                ================================================== --}}

                                <td>

                                    <span class="fw-medium">

                                        {{
                                            $tenderBidder
                                                ->bidder_reference_no
                                            ?: '—'
                                        }}

                                    </span>

                                </td>



                                {{-- =================================================
                                    PARTICIPATION
                                ================================================== --}}

                                <td>

                                    @php

                                        $participationClass =
                                            match(
                                                $tenderBidder
                                                    ->participation_status
                                            ) {

                                                'Awarded'
                                                    => 'bg-success',

                                                'Disqualified'
                                                    => 'bg-danger',

                                                'Withdrawn'
                                                    => 'bg-secondary',

                                                'Participating'
                                                    => 'bg-primary',

                                                'Registered'
                                                    => 'bg-info',

                                                default
                                                    => 'bg-warning text-dark',

                                            };

                                    @endphp


                                    <span
                                        class="badge {{ $participationClass }}"
                                    >

                                        {{
                                            $tenderBidder
                                                ->participation_status
                                        }}

                                    </span>

                                </td>



                                {{-- =================================================
                                    PREQUALIFICATION
                                ================================================== --}}

                                <td>

                                    @if(
                                        $tenderBidder
                                            ->prequalification_required
                                    )


                                        <a
                                            href="{{ route(
                                                'admin.procurement.tenders.prequalifications.create',
                                                [
                                                    'procurementTender'
                                                        => $procurementTender,

                                                    'tender_bidder_id'
                                                        => $tenderBidder->id,
                                                ]
                                            ) }}"
                                            class="btn btn-sm btn-outline-warning"
                                        >

                                            <i
                                                class="ri-file-check-line me-1"
                                            ></i>

                                            Start Prequalification

                                        </a>


                                    @else

                                        <span
                                            class="badge bg-secondary"
                                        >

                                            Not Required

                                        </span>

                                    @endif

                                </td>



                                {{-- =================================================
                                    REGISTRATION
                                ================================================== --}}

                                <td>

                                    <span class="small">

                                        {{
                                            $tenderBidder
                                                ->registration_date
                                                ? $tenderBidder
                                                    ->registration_date
                                                    ->format('d-m-Y')
                                                : '—'
                                        }}

                                    </span>

                                </td>



                                {{-- =================================================
                                    ACTION
                                ================================================== --}}

                                <td class="text-end pe-4">

                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'admin.procurement.tenders.bidders.destroy',
                                            [
                                                'procurementTender'
                                                    => $procurementTender,

                                                'tenderBidder'
                                                    => $tenderBidder,
                                            ]
                                        ) }}"
                                    >

                                        @csrf

                                        @method('DELETE')


                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm(
                                                'Remove this bidder from the Tender?'
                                            )"
                                        >

                                            <i
                                                class="ri-delete-bin-line me-1"
                                            ></i>

                                            Remove

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>


            @else


                {{-- =====================================================
                    EMPTY STATE
                ====================================================== --}}

                <div class="text-center py-5">

                    <div class="mb-3">

                        <i
                            class="ri-team-line text-muted"
                            style="font-size:48px;"
                        ></i>

                    </div>


                    <h6 class="mb-1">

                        No Bidders Assigned

                    </h6>


                    <div class="text-muted">

                        Add a bidder using the form above
                        to assign them to this Tender.

                    </div>

                </div>


            @endif

        </div>

    </div>

</div>

@endsection