@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="mb-1">

                <a
                    href="{{ route(
                        'admin.projects.procurement.plans.show',
                        [
                            'project' => $project->id,
                            'procurementPlan' =>
                                $procurementPackage->procurement_plan_id,
                        ]
                    ) }}"
                    class="text-decoration-none"
                >

                    Procurement Plan:
                    {{ $procurementPackage->procurementPlan->plan_number }}

                </a>

            </div>


            <h4 class="mb-1">

                {{ $procurementPackage->package_title }}

            </h4>


            <div class="text-muted">

                {{ $procurementPackage->package_number }}

            </div>

        </div>


        <div class="d-flex gap-2">


            @if(
                in_array(
                    $procurementPackage->status,
                    ['Draft', 'Planned'],
                    true
                )
            )

                <a
                    href="{{ route(
                        'admin.projects.procurement.packages.edit',
                        [
                            'project' => $project->id,
                            'procurementPackage' =>
                                $procurementPackage->id,
                        ]
                    ) }}"
                    class="btn btn-primary"
                >

                    Edit

                </a>

            @endif


            <a
                href="{{ route(
                    'admin.projects.procurement.plans.show',
                    [
                        'project' => $project->id,
                        'procurementPlan' =>
                            $procurementPackage->procurement_plan_id,
                    ]
                ) }}"
                class="btn btn-outline-secondary"
            >

                Back to Plan

            </a>


            <a
                href="{{ route('admin.projects.procurement.tenders.index', [
                        'project' => $project->id,
                    ]) }}"
                class="btn btn-success"
            >

                <i class="ri-auction-line me-1"></i>

                Tenders

                @if($procurementPackage->tenders->count())

                    <span class="badge bg-light text-success ms-1">

                        {{ $procurementPackage->tenders->count() }}

                    </span>

                @endif

            </a>

        </div>

    </div>


    {{-- Project Context --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Project Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">


                <div class="col-md-4">

                    <div class="text-muted small">
                        Project
                    </div>

                    <div class="fw-semibold">

                        {{ $project->project_name }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Project Code
                    </div>

                    <div class="fw-semibold">

                        {{ $project->project_code ?: '—' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Project ID
                    </div>

                    <div class="fw-semibold">

                        {{ $procurementPackage->project_id }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Package Information --}}

    <div class="card mb-4">

        <div class="card-header">

            <strong>
                Procurement Package Information
            </strong>

        </div>


        <div class="card-body">

            <div class="row g-4">


                <div class="col-md-3">

                    <div class="text-muted small">
                        Package Number
                    </div>

                    <div class="fw-semibold">

                        {{ $procurementPackage->package_number }}

                    </div>

                </div>


                <div class="col-md-5">

                    <div class="text-muted small">
                        Package Title
                    </div>

                    <div class="fw-semibold">

                        {{ $procurementPackage->package_title }}

                    </div>

                </div>


                <div class="col-md-2">

                    <div class="text-muted small">
                        Package Type
                    </div>

                    <div>

                        {{ $procurementPackage->package_type ?: '—' }}

                    </div>

                </div>


                <div class="col-md-2">

                    <div class="text-muted small">
                        Status
                    </div>

                    <span class="badge bg-secondary">

                        {{ $procurementPackage->status }}

                    </span>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Estimated Value
                    </div>

                    <div class="fw-semibold">

                        {{ $procurementPackage->currency }}

                        {{ number_format(
                            $procurementPackage->estimated_value,
                            2
                        ) }}

                    </div>

                </div>

                {{-- Project Budget --}}

                <div class="col-md-4">

                    <div class="text-muted small">
                        Project Budget
                    </div>

                    <div class="fw-semibold">

                        @if($procurementPackage->projectBudget)

                            {{ $procurementPackage->projectBudget->budget_name
                                ?? $procurementPackage->projectBudget->name
                                ?? '—' }}

                        @else

                            <span class="text-muted">
                                Not linked
                            </span>

                        @endif

                    </div>

                </div>


                {{-- Budget Item --}}

                <div class="col-md-8">

                    <div class="text-muted small">
                        Budget Item
                    </div>

                    <div class="fw-semibold">

                        @if($procurementPackage->budgetItem)

                            @if(!empty($procurementPackage->budgetItem->item_code))
                                {{ $procurementPackage->budgetItem->item_code }} -
                            @endif

                            {{ $procurementPackage->budgetItem->item_name
                                ?? $procurementPackage->budgetItem->name
                                ?? '—' }}

                            @if($procurementPackage->budgetItem->category)

                                <div class="small text-muted mt-1">

                                    Category:
                                    {{ $procurementPackage->budgetItem->category->name
                                        ?? $procurementPackage->budgetItem->category->category_name
                                        ?? '—' }}

                                </div>

                            @endif

                        @else

                            <span class="text-muted">
                                Not linked
                            </span>

                        @endif

                    </div>

                </div>


                {{-- Approved Budget Amount --}}

                <div class="col-md-4">

                    <div class="text-muted small">
                        Approved Budget Amount
                    </div>

                    <div class="fw-semibold">

                        @if($procurementPackage->budgetItem)

                            {{ $procurementPackage->currency }}

                            {{ number_format(
                                (float) (
                                    $procurementPackage->budgetItem->approved_amount
                                    ?? $procurementPackage->budgetItem->budget_amount
                                    ?? 0
                                ),
                                2
                            ) }}

                        @else

                            —

                        @endif

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Procurement Method
                    </div>

                    <div>

                        {{
                            $procurementPackage->procurement_method
                            ?: '—'
                        }}

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Responsible Person
                    </div>

                    <div>

                        {{
                            $procurementPackage->responsible_name
                            ?: '—'
                        }}

                    </div>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Planned Tender Date
                    </div>

                    <div>

                        {{
                            $procurementPackage->planned_tender_date
                                ? $procurementPackage
                                    ->planned_tender_date
                                    ->format('d-m-Y')
                                : '—'
                        }}

                    </div>

                </div>


                <div class="col-md-6">

                    <div class="text-muted small">
                        Planned Award Date
                    </div>

                    <div>

                        {{
                            $procurementPackage->planned_award_date
                                ? $procurementPackage
                                    ->planned_award_date
                                    ->format('d-m-Y')
                                : '—'
                        }}

                    </div>

                </div>


                <div class="col-md-12">

                    <div class="text-muted small">
                        Planned Start Date
                    </div>

                    <div>

                        {{
                            $procurementPackage->planned_start_date
                                ? $procurementPackage
                                    ->planned_start_date
                                    ->format('d-m-Y')
                                : '—'
                        }}

                    </div>

                </div>


                <div class="col-md-12">

                    <div class="text-muted small">
                        Planned Completion Date
                    </div>

                    <div>

                        {{
                            $procurementPackage->planned_completion_date
                                ? $procurementPackage
                                    ->planned_completion_date
                                    ->format('d-m-Y')
                                : '—'
                        }}

                    </div>

                </div>


                <div class="col-md-12">

                    <div class="text-muted small">
                        Scope of Work
                    </div>

                    <div>

                        {!! nl2br(
                            e(
                                $procurementPackage->scope_of_work
                                ?: '—'
                            )
                        ) !!}

                    </div>

                </div>


                <div class="col-md-12">

                    <div class="text-muted small">
                        Description
                    </div>

                    <div>

                        {!! nl2br(
                            e(
                                $procurementPackage->description
                                ?: '—'
                            )
                        ) !!}

                    </div>

                </div>


                <div class="col-md-12">

                    <div class="text-muted small">
                        Remarks
                    </div>

                    <div>

                        {!! nl2br(
                            e(
                                $procurementPackage->remarks
                                ?: '—'
                            )
                        ) !!}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Tenders --}}

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <div>

                <strong>
                    Tenders
                </strong>

                <span class="badge bg-primary ms-2">

                    {{ $procurementPackage->tenders->count() }}

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($procurementPackage->tenders->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>
                                    Tender Number
                                </th>

                                <th>
                                    Tender Title
                                </th>

                                <th>
                                    Method
                                </th>

                                <th>
                                    Estimated Value
                                </th>

                                <th>
                                    Submission Deadline
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

                            @foreach(
                                $procurementPackage->tenders
                                as $tender
                            )

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.projects.procurement.tenders.show',
                                                [
                                                    'project' =>
                                                        $project->id,

                                                    'procurementPackage' =>
                                                        $procurementPackage->id,

                                                    'procurementTender' =>
                                                        $tender->id,
                                                ]
                                            ) }}"
                                            class="fw-semibold text-decoration-none"
                                        >

                                            {{ $tender->tender_number }}

                                        </a>

                                    </td>


                                    <td>

                                        {{ $tender->tender_title }}

                                    </td>


                                    <td>

                                        {{ $tender->procurement_method ?: '—' }}

                                    </td>


                                    <td>

                                        {{ $tender->currency }}

                                        {{
                                            number_format(
                                                $tender->estimated_value,
                                                2
                                            )
                                        }}

                                    </td>


                                    <td>

                                        {{
                                            $tender->submission_deadline
                                                ? $tender
                                                    ->submission_deadline
                                                    ->format('d-m-Y')
                                                : '—'
                                        }}

                                    </td>


                                    <td>

                                        <span class="badge bg-secondary">

                                            {{ $tender->status }}

                                        </span>

                                    </td>


                                    <td class="text-end">

                                        <a
                                            href="{{ route(
                                                'admin.projects.procurement.tenders.show',
                                                [
                                                    'project' =>
                                                        $project->id,

                                                    'procurementPackage' =>
                                                        $procurementPackage->id,

                                                    'procurementTender' =>
                                                        $tender->id,
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

                    <div class="text-muted mb-3">

                        No tenders have been created
                        for this package yet.

                    </div>


                    <a
                        href="{{ route(
                            'admin.projects.procurement.tenders.create',
                            [
                                'project' => $project->id,
                                'procurementPackage' =>
                                    $procurementPackage->id,
                            ]
                        ) }}"
                        class="btn btn-primary"
                    >

                        <i class="ri-add-line me-1"></i>

                        Add Tender

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection