@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small mb-1">
                Procurement Management
            </div>

            <h3 class="mb-1">
                Edit Tender
            </h3>

            <div class="text-muted">
                {{ $procurementTender->tender_number }}
                —
                {{ $procurementTender->tender_title }}
            </div>

            <div class="small text-muted mt-1">
                Project:
                <strong>
                    {{ $project->project_name }}
                </strong>

                @if($project->project_code)
                    ({{ $project->project_code }})
                @endif
            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.show',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Back to Tender
            </a>

        </div>

    </div>


    {{-- =========================================================
        ALERTS
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


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following errors:
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
        FORM
    ========================================================== --}}

    <form
        method="POST"
        action="{{ route(
            'admin.projects.procurement.tenders.update',
            [
                'project' => $project,
                'procurementTender' => $procurementTender,
            ]
        ) }}"
    >

        @csrf

        @method('PUT')


        {{-- =====================================================
            PROJECT CONTEXT
        ====================================================== --}}

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white">

                <strong>
                    Project Context
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-5">

                        <label class="form-label">
                            Project
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $project->project_name }}"
                            readonly
                        >

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Project Code
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $project->project_code ?: '—' }}"
                            readonly
                        >

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            Tender Number
                        </label>

                        <input
                            type="text"
                            class="form-control fw-semibold"
                            value="{{ $procurementTender->tender_number }}"
                            readonly
                        >

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            Status
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $procurementTender->status }}"
                            readonly
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            PROCUREMENT HIERARCHY
        ====================================================== --}}

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white">

                <strong>
                    Procurement Hierarchy
                </strong>

            </div>


            <div class="card-body">

                <div class="alert alert-info mb-3">

                    <strong>
                        Project → Procurement Plan → Procurement Package → Tender
                    </strong>

                    <div class="small mt-1">
                        Only Procurement Packages belonging to this
                        Project are available.
                    </div>

                </div>


                <div class="row g-3">

                    <div class="col-md-12">

                        <label class="form-label">

                            Procurement Package

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <select
                            name="procurement_package_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select Procurement Package
                            </option>


                            @foreach($packages as $package)

                                <option
                                    value="{{ $package->id }}"
                                    @selected(
                                        old(
                                            'procurement_package_id',
                                            $procurementTender->procurement_package_id
                                        ) == $package->id
                                    )
                                >

                                    {{ $package->package_number }}

                                    @if($package->package_title)

                                        -
                                        {{ $package->package_title }}

                                    @endif

                                </option>

                            @endforeach

                        </select>


                        <div class="form-text">

                            Changing the package is allowed only
                            within the same Project.

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            TENDER DETAILS
        ====================================================== --}}

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white">

                <strong>
                    Tender Details
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    {{-- Tender Title --}}

                    <div class="col-md-6">

                        <label class="form-label">

                            Tender Title

                            <span class="text-danger">
                                *
                            </span>

                        </label>

                        <input
                            type="text"
                            name="tender_title"
                            class="form-control"
                            maxlength="255"
                            value="{{ old(
                                'tender_title',
                                $procurementTender->tender_title
                            ) }}"
                            required
                        >

                    </div>


                    {{-- Tender Type --}}

                    <div class="col-md-3">

                        <label class="form-label">
                            Tender Type
                        </label>

                        <select
                            name="tender_type"
                            class="form-select"
                        >

                            <option value="">
                                Select Type
                            </option>

                            @foreach([
                                'Open Tender',
                                'Limited Tender',
                                'Request for Quotation',
                                'Single Source',
                                'Two Stage Tender',
                                'Other'
                            ] as $type)

                                <option
                                    value="{{ $type }}"
                                    @selected(
                                        old(
                                            'tender_type',
                                            $procurementTender->tender_type
                                        ) === $type
                                    )
                                >
                                    {{ $type }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Procurement Method --}}

                    <div class="col-md-3">

                        <label class="form-label">
                            Procurement Method
                        </label>

                        <input
                            type="text"
                            name="procurement_method"
                            class="form-control"
                            maxlength="100"
                            value="{{ old(
                                'procurement_method',
                                $procurementTender->procurement_method
                            ) }}"
                        >

                    </div>


                    {{-- Estimated Value --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Estimated Value
                        </label>

                        <input
                            type="number"
                            name="estimated_value"
                            class="form-control"
                            step="0.01"
                            min="0"
                            value="{{ old(
                                'estimated_value',
                                $procurementTender->estimated_value ?? 0
                            ) }}"
                        >

                    </div>


                    {{-- Currency --}}

                    <div class="col-md-4">

                        <label class="form-label">

                            Currency

                            <span class="text-danger">
                                *
                            </span>

                        </label>

                        <input
                            type="text"
                            name="currency"
                            class="form-control"
                            maxlength="10"
                            value="{{ old(
                                'currency',
                                $procurementTender->currency ?? 'USD'
                            ) }}"
                            required
                        >

                    </div>


                    {{-- Tender Fee --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Tender Fee
                        </label>

                        <input
                            type="number"
                            name="tender_fee"
                            class="form-control"
                            step="0.01"
                            min="0"
                            value="{{ old(
                                'tender_fee',
                                $procurementTender->tender_fee ?? 0
                            ) }}"
                        >

                    </div>


                    {{-- EMD --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            EMD Amount
                        </label>

                        <input
                            type="number"
                            name="emd_amount"
                            class="form-control"
                            step="0.01"
                            min="0"
                            value="{{ old(
                                'emd_amount',
                                $procurementTender->emd_amount ?? 0
                            ) }}"
                        >

                    </div>


                    {{-- Responsible User --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Responsible User
                        </label>

                        <select
                            name="responsible_user_id"
                            class="form-select"
                        >

                            <option value="">
                                Select User
                            </option>

                            @foreach($users as $user)

                                <option
                                    value="{{ $user->id }}"
                                    @selected(
                                        old(
                                            'responsible_user_id',
                                            $procurementTender->responsible_user_id
                                        ) == $user->id
                                    )
                                >
                                    {{ $user->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Responsible Name --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Responsible Name
                        </label>

                        <input
                            type="text"
                            name="responsible_name"
                            class="form-control"
                            maxlength="255"
                            value="{{ old(
                                'responsible_name',
                                $procurementTender->responsible_name
                            ) }}"
                        >

                    </div>


                    {{-- Prequalification --}}

                    <div class="col-md-4 d-flex align-items-end">

                        <div class="form-check mb-2">

                            <input
                                type="checkbox"
                                name="prequalification_required"
                                value="1"
                                class="form-check-input"
                                id="prequalification_required"
                                @checked(
                                    old(
                                        'prequalification_required',
                                        $procurementTender->prequalification_required
                                    )
                                )
                            >

                            <label
                                class="form-check-label"
                                for="prequalification_required"
                            >
                                Prequalification Required
                            </label>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            TENDER TIMELINE
        ====================================================== --}}

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white">

                <strong>
                    Tender Timeline
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-3">

                        <label class="form-label">
                            Issue Date
                        </label>

                        <input
                            type="date"
                            name="issue_date"
                            class="form-control"
                            value="{{ old(
                                'issue_date',
                                optional(
                                    $procurementTender->issue_date
                                )->format('Y-m-d')
                            ) }}"
                        >

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Submission Start Date
                        </label>

                        <input
                            type="date"
                            name="submission_start_date"
                            class="form-control"
                            value="{{ old(
                                'submission_start_date',
                                optional(
                                    $procurementTender->submission_start_date
                                )->format('Y-m-d')
                            ) }}"
                        >

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Submission Deadline
                        </label>

                        <input
                            type="date"
                            name="submission_deadline"
                            class="form-control"
                            value="{{ old(
                                'submission_deadline',
                                optional(
                                    $procurementTender->submission_deadline
                                )->format('Y-m-d')
                            ) }}"
                        >

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Opening Date
                        </label>

                        <input
                            type="date"
                            name="opening_date"
                            class="form-control"
                            value="{{ old(
                                'opening_date',
                                optional(
                                    $procurementTender->opening_date
                                )->format('Y-m-d')
                            ) }}"
                        >

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Technical Evaluation Date
                        </label>

                        <input
                            type="date"
                            name="technical_evaluation_date"
                            class="form-control"
                            value="{{ old(
                                'technical_evaluation_date',
                                optional(
                                    $procurementTender->technical_evaluation_date
                                )->format('Y-m-d')
                            ) }}"
                        >

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Commercial Evaluation Date
                        </label>

                        <input
                            type="date"
                            name="commercial_evaluation_date"
                            class="form-control"
                            value="{{ old(
                                'commercial_evaluation_date',
                                optional(
                                    $procurementTender->commercial_evaluation_date
                                )->format('Y-m-d')
                            ) }}"
                        >

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Planned Award Date
                        </label>

                        <input
                            type="date"
                            name="planned_award_date"
                            class="form-control"
                            value="{{ old(
                                'planned_award_date',
                                optional(
                                    $procurementTender->planned_award_date
                                )->format('Y-m-d')
                            ) }}"
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            DESCRIPTION & SCOPE
        ====================================================== --}}

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white">

                <strong>
                    Scope & Conditions
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-12">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea
                            name="description"
                            rows="4"
                            class="form-control"
                        >{{ old(
                            'description',
                            $procurementTender->description
                        ) }}</textarea>

                    </div>


                    <div class="col-md-12">

                        <label class="form-label">
                            Scope of Work
                        </label>

                        <textarea
                            name="scope_of_work"
                            rows="5"
                            class="form-control"
                        >{{ old(
                            'scope_of_work',
                            $procurementTender->scope_of_work
                        ) }}</textarea>

                    </div>


                    <div class="col-md-12">

                        <label class="form-label">
                            Terms & Conditions
                        </label>

                        <textarea
                            name="terms_and_conditions"
                            rows="5"
                            class="form-control"
                        >{{ old(
                            'terms_and_conditions',
                            $procurementTender->terms_and_conditions
                        ) }}</textarea>

                    </div>


                    <div class="col-md-12">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            rows="3"
                            class="form-control"
                        >{{ old(
                            'remarks',
                            $procurementTender->remarks
                        ) }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            ACTIONS
        ====================================================== --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a
                href="{{ route(
                    'admin.projects.procurement.tenders.show',
                    [
                        'project' => $project,
                        'procurementTender' => $procurementTender,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Cancel
            </a>


            <button
                type="submit"
                class="btn btn-primary"
            >
                <i class="ri-save-line me-1"></i>
                Update Tender
            </button>

        </div>

    </form>

</div>

@endsection