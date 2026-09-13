@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <div class="text-muted small mb-1">
                Project / Budget / Category
            </div>

            <h3>
                Edit Budget Category
            </h3>

            <div class="text-muted">
                {{ $projectBudget->budget_number }}
                ·
                {{ $projectBudget->title }}
            </div>

        </div>


        <a
            href="{{ route(
                'admin.projects.budget.show',
                [
                    'project' =>
                        $project->id,

                    'projectBudget' =>
                        $projectBudget->id,
                ]
            ) }}"
            class="btn btn-outline-secondary"
        >
            ← Back to Budget
        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    <div class="card">

        <div class="card-header">

            <strong>
                Category Information
            </strong>

        </div>


        <div class="card-body">

            <form
                method="POST"
                action="{{ route(
                    'admin.projects.budget.categories.update',
                    [
                        'project' =>
                            $project->id,

                        'projectBudget' =>
                            $projectBudget->id,

                        'category' =>
                            $category->id,
                    ]
                ) }}"
            >

                @csrf

                @method('PUT')


                <div class="row">

                    <div class="col-md-3 mb-3">

                        <label
                            for="category_code"
                            class="form-label"
                        >
                            Category Code
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="category_code"
                            id="category_code"
                            class="form-control"
                            value="{{ old(
                                'category_code',
                                $category->category_code
                            ) }}"
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
                            value="{{ old(
                                'category_name',
                                $category->category_name
                            ) }}"
                            required
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label
                            for="sequence"
                            class="form-label"
                        >
                            Sequence
                        </label>

                        <input
                            type="number"
                            name="sequence"
                            id="sequence"
                            class="form-control"
                            min="0"
                            value="{{ old(
                                'sequence',
                                $category->sequence
                            ) }}"
                        >

                    </div>

                </div>


                <div class="mb-4">

                    <label
                        for="description"
                        class="form-label"
                    >
                        Description
                    </label>

                    <textarea
                        name="description"
                        id="description"
                        rows="4"
                        class="form-control"
                    >{{ old(
                        'description',
                        $category->description
                    ) }}</textarea>

                </div>


                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route(
                            'admin.projects.budget.show',
                            [
                                'project' =>
                                    $project->id,

                                'projectBudget' =>
                                    $projectBudget->id,
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
                        Update Category
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection