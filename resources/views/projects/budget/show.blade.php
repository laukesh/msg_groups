@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- Header --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small mb-1">
                Project / Budget
            </div>

            <h3 class="mb-1">
                {{ $projectBudget->title }}
            </h3>

            <div class="text-muted">

                {{ $projectBudget->budget_number }}

                ·

                {{ $project->project_name }}

                ·

                {{ $project->project_number }}

            </div>

        </div>


        <div class="d-flex gap-2">

            @if($projectBudget->status !== 'Approved')

                <a
                    href="{{ route(
                        'admin.projects.budget.edit',
                        [
                            'project' =>
                                $project->id,

                            'projectBudget' =>
                                $projectBudget->id,
                        ]
                    ) }}"
                    class="btn btn-outline-primary"
                >
                    Edit Budget
                </a>

            @endif

            @if($projectBudget->status === 'Approved')

                <form
                    method="POST"
                    action="{{ route(
                        'admin.projects.budget.revision',
                        [
                            'project' =>
                                $project->id,

                            'projectBudget' =>
                                $projectBudget->id,
                        ]
                    ) }}"
                    onsubmit="return confirm(
                        'Create a new budget revision from this approved budget?'
                    );"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-warning"
                    >
                        Create Revision
                    </button>

                </form>

            @endif


            <a
                href="{{ route(
                    'admin.projects.budget.index',
                    ['project' => $project->id]
                ) }}"
                class="btn btn-outline-secondary"
            >
                ← Budgets
            </a>

        </div>

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
    {{-- Budget Status --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-body">

            <div class="row g-4">

                {{-- Version --}}

                <div class="col-md-2">

                    <div class="text-muted small">
                        Version
                    </div>

                    <div class="fs-5 fw-semibold mt-1">
                        V{{ $projectBudget->version_number }}
                    </div>

                </div>


                {{-- Budget Number --}}

                <div class="col-md-3">

                    <div class="text-muted small">
                        Budget Number
                    </div>

                    <div class="fw-semibold mt-1">
                        {{ $projectBudget->budget_number }}
                    </div>

                </div>


                {{-- Type --}}

                <div class="col-md-2">

                    <div class="text-muted small">
                        Type
                    </div>

                    <div class="fw-semibold mt-1">
                        {{ $projectBudget->budget_type }}
                    </div>

                </div>


                {{-- Currency --}}

                <div class="col-md-2">

                    <div class="text-muted small">
                        Currency
                    </div>

                    <div class="fw-semibold mt-1">
                        {{ $projectBudget->currency }}
                    </div>

                </div>


                {{-- Status --}}

                <div class="col-md-3">

                    <div class="text-muted small">
                        Status
                    </div>

                    <div class="mt-1">

                        @switch($projectBudget->status)

                            @case('Approved')

                                <span class="badge bg-success fs-6">
                                    Approved
                                </span>

                                @break

                            @case('Submitted')

                                <span class="badge bg-info text-dark fs-6">
                                    Submitted
                                </span>

                                @break

                            @case('Under Review')

                                <span class="badge bg-warning text-dark fs-6">
                                    Under Review
                                </span>

                                @break

                            @case('Rejected')

                                <span class="badge bg-danger fs-6">
                                    Rejected
                                </span>

                                @break

                            @default

                                <span class="badge bg-secondary fs-6">
                                    {{ $projectBudget->status }}
                                </span>

                        @endswitch

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Financial Summary --}}
    {{-- ========================================================= --}}

    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Direct Cost
                    </div>

                    <div class="fs-5 fw-semibold mt-1">

                        {{ $projectBudget->currency }}

                        {{
                            number_format(
                                $projectBudget->direct_cost,
                                2
                            )
                        }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Indirect Cost
                    </div>

                    <div class="fs-5 fw-semibold mt-1">

                        {{ $projectBudget->currency }}

                        {{
                            number_format(
                                $projectBudget->indirect_cost,
                                2
                            )
                        }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Contingency
                    </div>

                    <div class="fs-5 fw-semibold mt-1">

                        {{ $projectBudget->currency }}

                        {{
                            number_format(
                                $projectBudget->contingency_amount,
                                2
                            )
                        }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card border-primary h-100">

                <div class="card-body">

                    <div class="text-muted small">
                        Total Budget
                    </div>

                    <div class="fs-4 fw-semibold mt-1">

                        {{ $projectBudget->currency }}

                        {{
                            number_format(
                                $projectBudget->total_budget,
                                2
                            )
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Budget Period --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>Budget Period & Approval</strong>
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-3">

                    <div class="text-muted small">
                        Budget Start
                    </div>

                    <div class="fw-semibold">

                        {{
                            $projectBudget->budget_start_date
                                ? $projectBudget
                                    ->budget_start_date
                                    ->format('d M Y')
                                : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Budget End
                    </div>

                    <div class="fw-semibold">

                        {{
                            $projectBudget->budget_end_date
                                ? $projectBudget
                                    ->budget_end_date
                                    ->format('d M Y')
                                : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Approved Date
                    </div>

                    <div class="fw-semibold">

                        {{
                            $projectBudget->approved_date
                                ? $projectBudget
                                    ->approved_date
                                    ->format('d M Y')
                                : '-'
                        }}

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="text-muted small">
                        Approved By
                    </div>

                    <div class="fw-semibold">

                        {{
                            $projectBudget->approved_by
                                ?? '-'
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Categories --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Budget Categories
                    </strong>

                    <div class="text-muted small">
                        Major cost groupings
                    </div>

                </div>

                <span class="badge bg-secondary">
                    {{ $projectBudget->categories->count() }}
                </span>

            </div>

        </div>


        <div class="card-body">

            @if($projectBudget->status !== 'Approved')

                <div class="border rounded p-3 mb-4">

                    <h6 class="mb-3">
                        Add Budget Category
                    </h6>


                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.budget.categories.store',
                            [
                                'project' =>
                                    $project->id,

                                'projectBudget' =>
                                    $projectBudget->id,
                            ]
                        ) }}"
                    >

                        @csrf


                        <div class="row">

                            <div class="col-md-2 mb-3">

                                <label
                                    for="category_code"
                                    class="form-label"
                                >
                                    Code
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="category_code"
                                    id="category_code"
                                    class="form-control"
                                    placeholder="C01"
                                    required
                                >

                            </div>


                            <div class="col-md-5 mb-3">

                                <label
                                    for="category_name"
                                    class="form-label"
                                >
                                    Category Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="category_name"
                                    id="category_name"
                                    class="form-control"
                                    placeholder="Construction"
                                    required
                                >

                            </div>


                            <div class="col-md-2 mb-3">

                                <label
                                    for="category_sequence"
                                    class="form-label"
                                >
                                    Sequence
                                </label>

                                <input
                                    type="number"
                                    name="sequence"
                                    id="category_sequence"
                                    class="form-control"
                                    value="{{
                                        ($projectBudget
                                            ->categories
                                            ->max('sequence')
                                            ?? 0) + 1
                                    }}"
                                    min="0"
                                >

                            </div>


                            <div class="col-md-3 mb-3 d-flex align-items-end">

                                <button
                                    type="submit"
                                    class="btn btn-primary w-100"
                                >
                                    + Add Category
                                </button>

                            </div>

                        </div>


                        <div>

                            <label
                                for="category_description"
                                class="form-label"
                            >
                                Description
                            </label>

                            <textarea
                                name="description"
                                id="category_description"
                                rows="2"
                                class="form-control"
                            ></textarea>

                        </div>

                    </form>

                </div>

            @endif


            @if(
                $projectBudget->categories->count()
            )

                <div class="table-responsive">

                    <table
                        class="table table-bordered table-hover align-middle mb-0"
                    >

                        <thead>

                            <tr>

                                <th style="width:80px;">
                                    #
                                </th>

                                <th style="width:120px;">
                                    Code
                                </th>

                                <th>
                                    Category
                                </th>

                                <th>
                                    Description
                                </th>

                                <th>
                                    Items
                                </th>

                                @if(
                                    $projectBudget->status !== 'Approved'
                                )

                                    <th style="width:160px;">
                                        Action
                                    </th>

                                @endif

                            </tr>

                        </thead>


                        <tbody>

                            @foreach(
                                $projectBudget->categories
                                as $category
                            )

                                <tr>

                                    <td>
                                        {{ $category->sequence }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $category->category_code }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $category->category_name }}
                                    </td>

                                    <td>
                                        {{ $category->description ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $category->items->count() }}
                                    </td>


                                    @if(
                                        $projectBudget->status !== 'Approved'
                                    )

                                        <td>

                                            <a
                                                href="{{ route(
                                                    'admin.projects.budget.categories.edit',
                                                    [
                                                        'project' =>
                                                            $project->id,

                                                        'projectBudget' =>
                                                            $projectBudget->id,

                                                        'category' =>
                                                            $category->id,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                Edit
                                            </a>


                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.projects.budget.categories.destroy',
                                                    [
                                                        'project' =>
                                                            $project->id,

                                                        'projectBudget' =>
                                                            $projectBudget->id,

                                                        'category' =>
                                                            $category->id,
                                                    ]
                                                ) }}"
                                                class="d-inline"
                                                onsubmit="return confirm('Delete this budget category?');"
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

                                        </td>

                                    @endif

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-4 text-muted">

                    No budget categories have been added yet.

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Budget Items --}}
    {{-- ========================================================= --}}

    <div class="card mb-4">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Budget Line Items
                    </strong>

                    <div class="text-muted small">
                        Detailed project cost breakdown
                    </div>

                </div>

                <span class="badge bg-secondary">
                    {{ $projectBudget->items->count() }}
                </span>

            </div>

        </div>


        <div class="card-body">

            @if($projectBudget->status !== 'Approved')

                <div class="border rounded p-3 mb-4">

                    <h6 class="mb-3">
                        Add Budget Item
                    </h6>


                    <form
                        method="POST"
                        action="{{ route(
                            'admin.projects.budget.items.store',
                            [
                                'project' =>
                                    $project->id,

                                'projectBudget' =>
                                    $projectBudget->id,
                            ]
                        ) }}"
                    >

                        @csrf


                        <div class="row">

                            {{-- Code --}}

                            <div class="col-md-2 mb-3">

                                <label
                                    for="item_code"
                                    class="form-label"
                                >
                                    Item Code
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="item_code"
                                    id="item_code"
                                    class="form-control"
                                    placeholder="C01.01"
                                    required
                                >

                            </div>


                            {{-- Name --}}

                            <div class="col-md-4 mb-3">

                                <label
                                    for="item_name"
                                    class="form-label"
                                >
                                    Item Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="item_name"
                                    id="item_name"
                                    class="form-control"
                                    placeholder="Civil Works"
                                    required
                                >

                            </div>


                            {{-- Category --}}

                            <div class="col-md-3 mb-3">

                                <label
                                    for="project_budget_category_id"
                                    class="form-label"
                                >
                                    Category
                                </label>

                                <select
                                    name="project_budget_category_id"
                                    id="project_budget_category_id"
                                    class="form-select"
                                >

                                    <option value="">
                                        -- Select Category --
                                    </option>

                                    @foreach(
                                        $projectBudget->categories
                                        as $category
                                    )

                                        <option
                                            value="{{ $category->id }}"
                                        >
                                            {{ $category->category_code }}
                                            -
                                            {{ $category->category_name }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Cost Type --}}

                            <div class="col-md-3 mb-3">

                                <label
                                    for="cost_type"
                                    class="form-label"
                                >
                                    Cost Type
                                </label>

                                <select
                                    name="cost_type"
                                    id="cost_type"
                                    class="form-select"
                                >

                                    <option value="">
                                        -- Select --
                                    </option>

                                    <option value="Direct">
                                        Direct
                                    </option>

                                    <option value="Indirect">
                                        Indirect
                                    </option>

                                    <option value="Contingency">
                                        Contingency
                                    </option>

                                    <option value="Other">
                                        Other
                                    </option>

                                </select>

                            </div>

                        </div>


                        <div class="row">

                            {{-- Parent --}}

                            <div class="col-md-4 mb-3">

                                <label
                                    for="parent_item_id"
                                    class="form-label"
                                >
                                    Parent Item
                                </label>

                                <select
                                    name="parent_item_id"
                                    id="parent_item_id"
                                    class="form-select"
                                >

                                    <option value="">
                                        -- Top Level --
                                    </option>

                                    @foreach(
                                        $projectBudget->items
                                        as $parentItem
                                    )

                                        <option
                                            value="{{ $parentItem->id }}"
                                        >
                                            {{ $parentItem->item_code }}
                                            -
                                            {{ $parentItem->item_name }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Quantity --}}

                            <div class="col-md-2 mb-3">

                                <label
                                    for="quantity"
                                    class="form-label"
                                >
                                    Quantity
                                </label>

                                <input
                                    type="number"
                                    name="quantity"
                                    id="quantity"
                                    class="form-control"
                                    min="0"
                                    step="0.0001"
                                    value="0"
                                >

                            </div>


                            {{-- Unit --}}

                            <div class="col-md-2 mb-3">

                                <label
                                    for="unit"
                                    class="form-label"
                                >
                                    Unit
                                </label>

                                <input
                                    type="text"
                                    name="unit"
                                    id="unit"
                                    class="form-control"
                                    placeholder="m²"
                                >

                            </div>


                            {{-- Rate --}}

                            <div class="col-md-2 mb-3">

                                <label
                                    for="unit_rate"
                                    class="form-label"
                                >
                                    Unit Rate
                                </label>

                                <input
                                    type="number"
                                    name="unit_rate"
                                    id="unit_rate"
                                    class="form-control"
                                    min="0"
                                    step="0.01"
                                    value="0"
                                >

                            </div>


                            {{-- Amount --}}

                            <div class="col-md-2 mb-3">

                                <label class="form-label">
                                    Amount
                                </label>

                                <input
                                    type="text"
                                    id="estimated_amount_preview"
                                    class="form-control bg-light"
                                    value="0.00"
                                    readonly
                                >

                            </div>

                        </div>


                        <div class="row">

                            <div class="col-md-2 mb-3">

                                <label
                                    for="item_sequence"
                                    class="form-label"
                                >
                                    Sequence
                                </label>

                                <input
                                    type="number"
                                    name="sequence"
                                    id="item_sequence"
                                    class="form-control"
                                    value="{{
                                        ($projectBudget
                                            ->items
                                            ->max('sequence')
                                            ?? 0) + 1
                                    }}"
                                    min="0"
                                >

                            </div>


                            <div class="col-md-10 mb-3">

                                <label
                                    for="item_remarks"
                                    class="form-label"
                                >
                                    Remarks
                                </label>

                                <input
                                    type="text"
                                    name="remarks"
                                    id="item_remarks"
                                    class="form-control"
                                >

                            </div>

                        </div>


                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            + Add Budget Item
                        </button>

                    </form>

                </div>

            @endif


            {{-- ================================================= --}}
            {{-- Items Table --}}
            {{-- ================================================= --}}

            @if(
                $projectBudget->items->count()
            )

                <div class="table-responsive">

                    <table
                        class="table table-bordered table-hover align-middle mb-0"
                    >

                        <thead>

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Code
                                </th>

                                <th>
                                    Budget Item
                                </th>

                                <th>
                                    Category
                                </th>

                                <th>
                                    Quantity
                                </th>

                                <th>
                                    Unit
                                </th>

                                <th>
                                    Unit Rate
                                </th>

                                <th>
                                    Amount
                                </th>

                                <th>
                                    Cost Type
                                </th>

                                @if(
                                    $projectBudget->status !== 'Approved'
                                )

                                    <th>
                                        Action
                                    </th>

                                @endif

                            </tr>

                        </thead>


                        <tbody>

                            @foreach(
                                $projectBudget->items
                                as $item
                            )

                                <tr>

                                    <td>
                                        {{ $item->sequence }}
                                    </td>


                                    <td>
                                        <strong>
                                            {{ $item->item_code }}
                                        </strong>
                                    </td>


                                    <td>

                                        <div
                                            style="
                                                padding-left:
                                                {{ $item->parent_item_id
                                                    ? '20px'
                                                    : '0'
                                                }};
                                            "
                                        >

                                            @if(
                                                $item->parent_item_id
                                            )

                                                <span class="text-muted">
                                                    ↳
                                                </span>

                                            @endif

                                            {{ $item->item_name }}

                                        </div>

                                    </td>


                                    <td>

                                        @if($item->category)

                                            {{ $item->category->category_code }}
                                            -
                                            {{ $item->category->category_name }}

                                        @else

                                            <span class="text-muted">
                                                -
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        @if(
                                            $item->quantity !== null
                                        )

                                            {{
                                                rtrim(
                                                    rtrim(
                                                        number_format(
                                                            $item->quantity,
                                                            4,
                                                            '.',
                                                            ''
                                                        ),
                                                        '0'
                                                    ),
                                                    '.'
                                                )
                                            }}

                                        @else

                                            -

                                        @endif

                                    </td>


                                    <td>
                                        {{ $item->unit ?? '-' }}
                                    </td>


                                    <td>

                                        {{
                                            number_format(
                                                $item->unit_rate ?? 0,
                                                2
                                            )
                                        }}

                                    </td>


                                    <td>

                                        <strong>

                                            {{
                                                number_format(
                                                    $item->estimated_amount,
                                                    2
                                                )
                                            }}

                                        </strong>

                                    </td>


                                    <td>
                                        {{ $item->cost_type ?? '-' }}
                                    </td>


                                    @if(
                                        $projectBudget->status !== 'Approved'
                                    )

                                        <td>

                                            <a
                                                href="{{ route(
                                                    'admin.projects.budget.items.edit',
                                                    [
                                                        'project' =>
                                                            $project->id,

                                                        'projectBudget' =>
                                                            $projectBudget->id,

                                                        'item' =>
                                                            $item->id,
                                                    ]
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                Edit
                                            </a>


                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.projects.budget.items.destroy',
                                                    [
                                                        'project' =>
                                                            $project->id,

                                                        'projectBudget' =>
                                                            $projectBudget->id,

                                                        'item' =>
                                                            $item->id,
                                                    ]
                                                ) }}"
                                                class="d-inline"
                                                onsubmit="return confirm('Delete this budget item?');"
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

                                        </td>

                                    @endif

                                </tr>

                            @endforeach

                        </tbody>


                        {{-- ================================================= --}}
                        {{-- Items Total --}}
                        {{-- ================================================= --}}

                        <tfoot>

                            <tr>

                                <th
                                    colspan="7"
                                    class="text-end"
                                >
                                    Line Item Total
                                </th>

                                <th>

                                    {{
                                        number_format(
                                            $projectBudget
                                                ->items
                                                ->sum(
                                                    'estimated_amount'
                                                ),
                                            2
                                        )
                                    }}

                                </th>

                                @if(
                                    $projectBudget->status !== 'Approved'
                                )

                                    <th colspan="2"></th>

                                @else

                                    <th></th>

                                @endif

                            </tr>

                        </tfoot>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <h6>
                        No Budget Items
                    </h6>

                    <p class="text-muted mb-0">

                        Add detailed budget items above.

                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Remarks --}}
    {{-- ========================================================= --}}

    @if($projectBudget->remarks)

        <div class="card mb-5">

            <div class="card-header">
                <strong>Remarks</strong>
            </div>

            <div class="card-body">

                {!! nl2br(
                    e($projectBudget->remarks)
                ) !!}

            </div>

        </div>

    @endif

</div>


{{-- ============================================================= --}}
{{-- Line Item Calculation --}}
{{-- ============================================================= --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const quantity =
            document.getElementById('quantity');

        const unitRate =
            document.getElementById('unit_rate');

        const preview =
            document.getElementById(
                'estimated_amount_preview'
            );


        function calculateItemAmount()
        {
            const qty =
                parseFloat(quantity.value) || 0;

            const rate =
                parseFloat(unitRate.value) || 0;

            const amount =
                qty * rate;


            preview.value =
                amount.toLocaleString(
                    'en-IN',
                    {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }
                );
        }


        if (
            quantity &&
            unitRate &&
            preview
        ) {

            quantity.addEventListener(
                'input',
                calculateItemAmount
            );

            unitRate.addEventListener(
                'input',
                calculateItemAmount
            );

            calculateItemAmount();

        }

    }
);

</script>

@endsection