@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                Create Procurement Package
            </h4>

            <div class="text-muted">
                Add a package to a Procurement Plan.
            </div>

        </div>


        <a
            href="{{ route(
                'admin.procurement.packages.index'
            ) }}"
            class="btn btn-outline-secondary"
        >
            Back
        </a>

    </div>


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


    <form
        method="POST"
        action="{{ route(
            'admin.procurement.packages.store'
        ) }}"
    >

        @csrf


        <div class="card">

            <div class="card-header">

                <strong>
                    Package Details
                </strong>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- Procurement Plan --}}

                    <div class="col-md-6">

                        <label class="form-label">

                            Procurement Plan

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <select
                            name="procurement_plan_id"
                            id="procurement_plan_id"
                            class="form-select @error('procurement_plan_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select Procurement Plan
                            </option>


                            @foreach(
                                $procurementPlans
                                as $plan
                            )

                                <option
                                    value="{{ $plan->id }}"
                                    @selected(
                                        old(
                                            'procurement_plan_id',
                                            $selectedPlanId
                                        ) == $plan->id
                                    )
                                >

                                    {{ $plan->plan_number }}

                                    -

                                    {{ $plan->plan_title }}

                                    @if($plan->project)

                                        |

                                        {{ $plan->project->project_name }}

                                    @endif

                                </option>

                            @endforeach

                        </select>


                        @error('procurement_plan_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Project --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Project
                        </label>

                        <input
                            type="text"
                            id="project_name"
                            class="form-control"
                            value=""
                            readonly
                            placeholder="Project will be selected automatically"
                        >

                    </div>


                    {{-- Project Budget --}}

                    <div class="col-md-6">

                        <label class="form-label">

                            Project Budget

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <select
                            name="project_budget_id"
                            id="project_budget_id"
                            class="form-select @error('project_budget_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select Budget
                            </option>


                            @foreach($budgets as $budget)

                                <option
                                    value="{{ $budget->id }}"
                                    @selected(
                                        old(
                                            'project_budget_id'
                                        ) == $budget->id
                                    )
                                >

                                    {{ $budget->budget_number }}

                                    -

                                    {{ $budget->title }}

                                    |

                                    {{ $budget->currency }}

                                    {{ number_format(
                                        $budget->total_budget,
                                        2
                                    ) }}

                                    |

                                    Version
                                    {{ $budget->version_number }}

                                </option>

                            @endforeach

                        </select>


                        @error('project_budget_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Package Number --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Package Number
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="Will be generated automatically"
                            readonly
                        >

                        <div class="form-text">
                            Package number will be generated automatically
                            after saving.
                        </div>

                    </div>


                    {{-- Package Type --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Package Type
                        </label>

                        <select
                            name="package_type"
                            class="form-select"
                        >

                            <option value="">
                                Select Type
                            </option>

                            @foreach([
                                'Works',
                                'Goods',
                                'Services',
                                'Consultancy',
                                'Mixed',
                            ] as $type)

                                <option
                                    value="{{ $type }}"
                                    @selected(
                                        old('package_type')
                                        === $type
                                    )
                                >
                                    {{ $type }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Procurement Method --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Procurement Method
                        </label>

                        <input
                            type="text"
                            name="procurement_method"
                            class="form-control"
                            value="{{ old(
                                'procurement_method'
                            ) }}"
                            maxlength="100"
                            placeholder="e.g. Open Tender"
                        >

                    </div>


                    {{-- Title --}}

                    <div class="col-12">

                        <label class="form-label">

                            Package Title

                            <span class="text-danger">
                                *
                            </span>

                        </label>

                        <input
                            type="text"
                            name="package_title"
                            class="form-control"
                            value="{{ old(
                                'package_title'
                            ) }}"
                            maxlength="255"
                            required
                        >

                    </div>


                    {{-- Description --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea
                            name="description"
                            rows="5"
                            class="form-control"
                        >{{ old('description') }}</textarea>

                    </div>


                    {{-- Scope --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Scope of Work
                        </label>

                        <textarea
                            name="scope_of_work"
                            rows="5"
                            class="form-control"
                        >{{ old('scope_of_work') }}</textarea>

                    </div>


                    {{-- Estimated Value --}}

                    <div class="col-md-6">

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
                                '0.00'
                            ) }}"
                        >

                    </div>


                    {{-- Currency --}}

                    <div class="col-md-6">

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
                                'USD'
                            ) }}"
                            required
                        >

                    </div>


                    {{-- Planned Tender Date --}}

                    <div class="col-md-3">

                        <label class="form-label">
                            Planned Tender Date
                        </label>

                        <input
                            type="date"
                            name="planned_tender_date"
                            class="form-control"
                            value="{{ old(
                                'planned_tender_date'
                            ) }}"
                        >

                    </div>


                    {{-- Planned Award Date --}}

                    <div class="col-md-3">

                        <label class="form-label">
                            Planned Award Date
                        </label>

                        <input
                            type="date"
                            name="planned_award_date"
                            class="form-control"
                            value="{{ old(
                                'planned_award_date'
                            ) }}"
                        >

                    </div>


                    {{-- Planned Start Date --}}

                    <div class="col-md-3">

                        <label class="form-label">
                            Planned Start Date
                        </label>

                        <input
                            type="date"
                            name="planned_start_date"
                            class="form-control"
                            value="{{ old(
                                'planned_start_date'
                            ) }}"
                        >

                    </div>


                    {{-- Planned Completion Date --}}

                    <div class="col-md-3">

                        <label class="form-label">
                            Planned Completion Date
                        </label>

                        <input
                            type="date"
                            name="planned_completion_date"
                            class="form-control"
                            value="{{ old(
                                'planned_completion_date'
                            ) }}"
                        >

                    </div>


                    {{-- Responsible User --}}

                    <div class="col-md-6">

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
                                            'responsible_user_id'
                                        ) == $user->id
                                    )
                                >
                                    {{ $user->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Responsible Name --}}

                    <div class="col-md-6">

                        <label class="form-label">
                            Responsible Name
                        </label>

                        <input
                            type="text"
                            name="responsible_name"
                            class="form-control"
                            value="{{ old(
                                'responsible_name'
                            ) }}"
                            maxlength="255"
                        >

                    </div>


                    {{-- Remarks --}}

                    <div class="col-12">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            rows="4"
                            class="form-control"
                        >{{ old('remarks') }}</textarea>

                    </div>


                </div>

            </div>


            <div class="card-footer d-flex justify-content-end gap-2">

                <a
                    href="{{ route(
                        'admin.procurement.packages.index'
                    ) }}"
                    class="btn btn-outline-secondary"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Create Package
                </button>

            </div>

        </div>

    </form>

</div>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const planSelect =
            document.getElementById(
                'procurement_plan_id'
            );

        const budgetSelect =
            document.getElementById(
                'project_budget_id'
            );

        const projectName =
            document.getElementById(
                'project_name'
            );

        const oldBudgetId =
            @json(old('project_budget_id'));


        const plans = @json(
            $procurementPlans->mapWithKeys(
                function ($plan) {

                    return [

                        $plan->id => [

                            'project_name' =>
                                $plan->project?->project_name,

                            'project_id' =>
                                $plan->project_id,

                        ]

                    ];

                }
            )
        );


        function setProject() {

            const planId =
                planSelect.value;

            projectName.value =
                plans[planId]?.project_name
                ?? '';
        }


        function loadBudgets(
            planId,
            selectedBudgetId = null
        ) {

            budgetSelect.innerHTML =
                '<option value="">Loading budgets...</option>';

            budgetSelect.disabled = true;


            if (!planId) {

                budgetSelect.innerHTML =
                    '<option value="">Select Budget</option>';

                budgetSelect.disabled = false;

                return;
            }


            fetch(
                "{{ url('/admin/procurement/packages/budgets') }}/"
                + planId
            )
            .then(response => {

                if (!response.ok) {

                    throw new Error(
                        'Unable to load budgets.'
                    );
                }

                return response.json();

            })
            .then(budgets => {

                budgetSelect.innerHTML =
                    '<option value="">Select Budget</option>';


                budgets.forEach(
                    budget => {

                        const option =
                            document.createElement(
                                'option'
                            );

                        option.value =
                            budget.id;

                        option.textContent =
                            budget.budget_number
                            + ' - '
                            + budget.title
                            + ' ('
                            + budget.currency
                            + ' '
                            + Number(
                                budget.total_budget
                            ).toLocaleString(
                                'en-IN',
                                {
                                    minimumFractionDigits: 2
                                }
                            )
                            + ') Version '
                            + budget.version_number;


                        if (
                            selectedBudgetId &&
                            String(
                                selectedBudgetId
                            ) === String(
                                budget.id
                            )
                        ) {

                            option.selected =
                                true;
                        }


                        budgetSelect.appendChild(
                            option
                        );

                    }
                );


                budgetSelect.disabled =
                    false;

            })
            .catch(error => {

                console.error(error);

                budgetSelect.innerHTML =
                    '<option value="">Unable to load budgets</option>';

                budgetSelect.disabled =
                    false;
            });
        }


        planSelect.addEventListener(
            'change',
            function () {

                setProject();

                loadBudgets(
                    this.value
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Initial Load
        |--------------------------------------------------------------------------
        */

        setProject();


        if (planSelect.value) {

            loadBudgets(
                planSelect.value,
                oldBudgetId
            );
        }

    }
);

</script>

@endsection