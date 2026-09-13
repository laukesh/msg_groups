@extends('layouts.app')

@section('content')

<style type="text/css">
    .bg-white{
        color: #000;
    }
</style>

<div class="container-fluid">

    {{-- ================================================================
         HEADER
    ================================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small">
                Development Management
            </div>

            <h4 class="mb-1">
                Feasibility & Investment
            </h4>

            <div class="text-muted">
                Evaluate registered lands for feasibility and investment
                potential.
            </div>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route('admin.land.lands.index') }}"
                class="btn btn-outline-secondary"
            >
                Land Registration
            </a>

        </div>

    </div>


    {{-- ================================================================
         SUCCESS MESSAGE
    ================================================================= --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- ================================================================
         SUMMARY CARDS
    ================================================================= --}}

    <div class="row g-3 mb-4">

        {{-- Total Lands --}}

        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Lands
                    </div>

                    <div class="fs-3 fw-semibold mt-2">
                        {{ number_format($totalLands) }}
                    </div>

                    <div class="small text-muted mt-1">
                        Registered lands
                    </div>

                </div>

            </div>

        </div>


        {{-- Feasibility Studies --}}

        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Feasibility Studies
                    </div>

                    <div class="fs-3 fw-semibold mt-2">
                        {{ number_format($totalFeasibilities) }}
                    </div>

                    <div class="small text-muted mt-1">
                        Total assessments
                    </div>

                </div>

            </div>

        </div>


        {{-- Pending --}}

        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Pending Assessment
                    </div>

                    <div class="fs-3 fw-semibold mt-2">
                        {{ number_format($pendingAssessments) }}
                    </div>

                    <div class="small text-muted mt-1">
                        Draft / in progress
                    </div>

                </div>

            </div>

        </div>


        {{-- Completed --}}

        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Completed
                    </div>

                    <div class="fs-3 fw-semibold mt-2">
                        {{ number_format($completedAssessments) }}
                    </div>

                    <div class="small text-muted mt-1">
                        Completed assessments
                    </div>

                </div>

            </div>

        </div>


        {{-- Investment Analysis --}}

        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Investment Analysis
                    </div>

                    <div class="fs-3 fw-semibold mt-2">
                        {{ number_format($totalInvestmentAnalyses) }}
                    </div>

                    <div class="small text-muted mt-1">
                        Analyses prepared
                    </div>

                </div>

            </div>

        </div>


        {{-- Investment Decisions --}}

        <div class="col-xl-2 col-md-4 col-sm-6">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Investment Decisions
                    </div>

                    <div class="fs-3 fw-semibold mt-2">
                        {{ number_format($totalInvestmentDecisions) }}
                    </div>

                    <div class="small text-muted mt-1">
                        Decision records
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================
         FEASIBILITY PIPELINE
    ================================================================= --}}

    <div class="card mb-4">

        <div class="card-header bg-white">

            <div class="fw-semibold">
                Feasibility & Investment Pipeline
            </div>

            <div class="text-muted small">
                Current progress from registered land to investment decision.
            </div>

        </div>


        <div class="card-body">

            <div class="row align-items-center text-center g-3">

                {{-- Lands --}}

                <div class="col-md-2">

                    <div class="border rounded p-3">

                        <div class="text-muted small">
                            Registered Lands
                        </div>

                        <div class="fs-4 fw-semibold">
                            {{ number_format($totalLands) }}
                        </div>

                    </div>

                </div>


                <div class="col-md-1 d-none d-md-flex justify-content-center">

                    <span class="fs-4 text-muted">
                        →
                    </span>

                </div>


                {{-- Assessments --}}

                <div class="col-md-2">

                    <div class="border rounded p-3">

                        <div class="text-muted small">
                            Feasibility
                        </div>

                        <div class="fs-4 fw-semibold">
                            {{ number_format($totalFeasibilities) }}
                        </div>

                    </div>

                </div>


                <div class="col-md-1 d-none d-md-flex justify-content-center">

                    <span class="fs-4 text-muted">
                        →
                    </span>

                </div>


                {{-- Investment Analysis --}}

                <div class="col-md-2">

                    <div class="border rounded p-3">

                        <div class="text-muted small">
                            Investment Analysis
                        </div>

                        <div class="fs-4 fw-semibold">
                            {{ number_format($totalInvestmentAnalyses) }}
                        </div>

                    </div>

                </div>


                <div class="col-md-1 d-none d-md-flex justify-content-center">

                    <span class="fs-4 text-muted">
                        →
                    </span>

                </div>


                {{-- Decision --}}

                <div class="col-md-2">

                    <div class="border rounded p-3">

                        <div class="text-muted small">
                            Investment Decisions
                        </div>

                        <div class="fs-4 fw-semibold">
                            {{ number_format($totalInvestmentDecisions) }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================
         STATUS + ATTENTION
    ================================================================= --}}

    <div class="row g-4 mb-4">

        {{-- Assessment Status --}}

        <div class="col-lg-6">

            <div class="card h-100">

                <div class="card-header bg-white">

                    <div class="fw-semibold">
                        Feasibility Assessment Status
                    </div>

                </div>


                <div class="card-body">

                    @forelse($assessmentStatusSummary as $item)

                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <div>

                                <span class="fw-semibold">
                                    {{ $item->status ?: 'Not Set' }}
                                </span>

                            </div>

                            <span class="badge bg-secondary">
                                {{ number_format($item->total) }}
                            </span>

                        </div>

                    @empty

                        <div class="text-muted text-center py-4">
                            No feasibility assessments found.
                        </div>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- Needs Attention --}}

        <div class="col-lg-6">

            <div class="card h-100">

                <div class="card-header bg-white">

                    <div class="fw-semibold">
                        Needs Attention
                    </div>

                    <div class="text-muted small">
                        Lands requiring feasibility action.
                    </div>

                </div>


                <div class="card-body p-0">

                    @forelse($attentionLands as $land)

                        @php
                            $latest =
                                $land->feasibilityAssessments->first();

                            $hasFeasibility =
                                $land->feasibility_assessments_count > 0;
                        @endphp


                        <div class="px-3 py-3 border-bottom">

                            <div class="d-flex justify-content-between align-items-start">

                                <div>

                                    <div class="fw-semibold">
                                        {{ $land->land_name }}
                                    </div>

                                    <div class="small text-muted">
                                        {{ $land->land_code }}
                                    </div>

                                    @if(!$hasFeasibility)

                                        <div class="small text-danger mt-1">
                                            No feasibility assessment registered.
                                        </div>

                                    @elseif($latest)

                                        <div class="small text-warning mt-1">

                                            Latest assessment:
                                            {{ $latest->status }}

                                        </div>

                                    @endif

                                </div>


                                <div>

                                    @if(!$hasFeasibility)

                                        <a
                                            href="{{ route(
                                                'admin.land.lands.feasibility-assessments.create',
                                                ['land' => $land]
                                            ) }}"
                                            class="btn btn-sm btn-primary"
                                        >
                                            Register
                                        </a>

                                    @elseif($latest)

                                        <a
                                            href="{{ route(
                                                'admin.land.lands.feasibility-assessments.show',
                                                [
                                                    'land' =>
                                                        $land,
                                                    'feasibilityAssessment' =>
                                                        $latest,
                                                ]
                                            ) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            Review
                                        </a>

                                    @endif

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="text-center text-muted py-5">

                            <div class="mb-2">
                                ✓
                            </div>

                            No lands currently require attention.

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================
         RECENT ASSESSMENTS
    ================================================================= --}}

    <div class="card mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <div class="fw-semibold">
                        Recent Feasibility Assessments
                    </div>

                    <div class="text-muted small">
                        Latest feasibility activities.
                    </div>

                </div>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th>
                            Assessment No.
                        </th>

                        <th>
                            Land
                        </th>

                        <th>
                            Title
                        </th>

                        <th>
                            Assessment Date
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

                    @forelse($recentAssessments as $assessment)

                        <tr>

                            <td>

                                <div class="fw-semibold">
                                    {{ $assessment->assessment_number }}
                                </div>

                            </td>


                            <td>

                                @if($assessment->land)

                                    <div class="fw-semibold">
                                        {{ $assessment->land->land_name }}
                                    </div>

                                    <div class="small text-muted">
                                        {{ $assessment->land->land_code }}
                                    </div>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            <td>
                                {{ $assessment->title }}
                            </td>


                            <td>

                                @if($assessment->assessment_date)

                                    {{ $assessment->assessment_date->format('d-m-Y') }}

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            <td>

                                <span class="badge bg-secondary">

                                    {{ $assessment->status }}

                                </span>

                            </td>


                            <td class="text-end">

                                @if($assessment->land)

                                    <a
                                        href="{{ route(
                                            'admin.land.lands.feasibility-assessments.show',
                                            [
                                                'land' =>
                                                    $assessment->land,
                                                'feasibilityAssessment' =>
                                                    $assessment,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        View
                                    </a>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center text-muted py-5"
                            >

                                No feasibility assessments found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- ================================================================
         REGISTERED LANDS
    ================================================================= --}}

    <div class="card">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <div class="fw-semibold">
                        Registered Lands
                    </div>

                    <div class="text-muted small">
                        View feasibility status and register assessments
                        directly against a land.
                    </div>

                </div>

            </div>

        </div>


        {{-- ============================================================
             SEARCH
        ============================================================= --}}

        <div class="card-body border-bottom">

            <form
                method="GET"
                action="{{ route(
                    'admin.feasibility-investment.index'
                ) }}"
            >

                <div class="row g-3 align-items-end">

                    {{-- Search --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Search Land
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ $search }}"
                            placeholder="Land code, name, city..."
                        >

                    </div>


                    {{-- Status --}}

                    <div class="col-md-3">

                        <label class="form-label">
                            Acquisition Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All
                            </option>

                            @foreach($statuses as $item)

                                <option
                                    value="{{ $item }}"
                                    @selected($status === $item)
                                >
                                    {{ $item }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Buttons --}}

                    <div class="col-md-3">

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Search
                            </button>

                            <a
                                href="{{ route(
                                    'admin.feasibility-investment.index'
                                ) }}"
                                class="btn btn-outline-secondary"
                            >
                                Reset
                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>


        {{-- ============================================================
             LAND TABLE
        ============================================================= --}}

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th>
                            Land Code
                        </th>

                        <th>
                            Land Name
                        </th>

                        <th>
                            Area
                        </th>

                        <th>
                            Land Status
                        </th>

                        <th>
                            Feasibility
                        </th>

                        <th>
                            Latest Assessment
                        </th>

                        <th class="text-end">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($lands as $land)

                        @php

                            $latestFeasibility =
                                $land->feasibilityAssessments->first();

                            $feasibilityCount =
                                $land->feasibility_assessments_count;

                        @endphp


                        <tr>

                            {{-- Land Code --}}

                            <td>

                                <div class="fw-semibold">
                                    {{ $land->land_code }}
                                </div>

                            </td>


                            {{-- Land Name --}}

                            <td>

                                <div class="fw-semibold">
                                    {{ $land->land_name }}
                                </div>

                                @if(
                                    $land->city ||
                                    $land->state
                                )

                                    <div class="small text-muted">

                                        {{ $land->city }}

                                        @if(
                                            $land->city &&
                                            $land->state
                                        )
                                            ,
                                        @endif

                                        {{ $land->state }}

                                    </div>

                                @endif

                            </td>


                            {{-- Area --}}

                            <td>

                                {{ number_format(
                                    (float) $land->total_area,
                                    4
                                ) }}

                                {{ $land->area_unit }}

                            </td>


                            {{-- Land Status --}}

                            <td>

                                @if($land->acquisition_status)

                                    <span class="badge bg-secondary">

                                        {{ $land->acquisition_status }}

                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Feasibility Count --}}

                            <td>

                                @if($feasibilityCount > 0)

                                    <a
                                        href="{{ route(
                                            'admin.land.lands.feasibility-assessments.index',
                                            [
                                                'land' => $land,
                                            ]
                                        ) }}"
                                        class="text-decoration-none"
                                    >

                                        <span class="badge bg-primary">

                                            {{ $feasibilityCount }}

                                        </span>

                                        <span class="small ms-1">

                                            {{ $feasibilityCount === 1
                                                ? 'Study'
                                                : 'Studies'
                                            }}

                                        </span>

                                    </a>

                                @else

                                    <span class="badge bg-light text-dark border">

                                        Not Started

                                    </span>

                                @endif

                            </td>


                            {{-- Latest Assessment --}}

                            <td>

                                @if($latestFeasibility)

                                    <div class="fw-semibold">

                                        {{ $latestFeasibility->assessment_number }}

                                    </div>

                                    <div class="small text-muted">

                                        {{ $latestFeasibility->title }}

                                    </div>

                                    <div class="mt-1">

                                        <span class="badge bg-secondary">

                                            {{ $latestFeasibility->status }}

                                        </span>

                                    </div>

                                @else

                                    <span class="text-muted">
                                        No assessment
                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}

                            <td class="text-end">

                                <div class="d-flex justify-content-end gap-2">

                                    <a
                                        href="{{ route(
                                            'admin.land.lands.feasibility-assessments.index',
                                            [
                                                'land' => $land,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Feasibility
                                    </a>


                                    <a
                                        href="{{ route(
                                            'admin.land.lands.feasibility-assessments.create',
                                            [
                                                'land' => $land,
                                            ]
                                        ) }}"
                                        class="btn btn-sm btn-primary"
                                    >
                                        Register
                                    </a>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-5"
                            >

                                <div class="text-muted">
                                    No registered lands found.
                                </div>

                                <a
                                    href="{{ route(
                                        'admin.land.lands.index'
                                    ) }}"
                                    class="btn btn-sm btn-outline-primary mt-3"
                                >
                                    Go to Land Registration
                                </a>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- ============================================================
             PAGINATION
        ============================================================= --}}

        @if($lands->hasPages())

            <div class="card-footer bg-white">

                {{ $lands->links() }}

            </div>

        @endif

    </div>

</div>

@endsection