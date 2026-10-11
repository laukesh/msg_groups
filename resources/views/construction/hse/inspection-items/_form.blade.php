@php

    $item = $item ?? null;

@endphp


<form
    method="POST"
    action="{{ $action }}"
>

    @csrf

    @if($method)

        @method($method)

    @endif


    <div class="card">

        <div class="card-header">
            <strong>Checklist Item Details</strong>
        </div>


        <div class="card-body">

            <div class="row g-3">


                {{-- Item Number --}}

                <div class="col-md-4">

                    <label class="form-label">
                        Item Number
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $itemNumber ?? $item?->item_number }}"
                        readonly
                    >

                </div>


                {{-- Category --}}

                <div class="col-md-8">

                    <label class="form-label">
                        Checklist Category
                    </label>

                    <input
                        type="text"
                        name="checklist_category"
                        class="form-control @error('checklist_category') is-invalid @enderror"
                        value="{{ old(
                            'checklist_category',
                            $item?->checklist_category
                        ) }}"
                        placeholder="e.g. Fire Safety, PPE, Electrical Safety"
                    >

                    @error('checklist_category')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Question --}}

                <div class="col-12">

                    <label class="form-label">

                        Checklist Question

                        <span class="text-danger">*</span>

                    </label>

                    <textarea
                        name="checklist_question"
                        rows="4"
                        class="form-control @error('checklist_question') is-invalid @enderror"
                        placeholder="Enter the inspection checklist question..."
                        required
                    >{{ old(
                        'checklist_question',
                        $item?->checklist_question
                    ) }}</textarea>

                    @error('checklist_question')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Response --}}

                <div class="col-md-4">

                    <label class="form-label">
                        Response
                    </label>

                    <select
                        name="response"
                        class="form-select @error('response') is-invalid @enderror"
                    >

                        <option value="">
                            -- Select Response --
                        </option>

                        @foreach([
                            'Compliant',
                            'Non-Compliant',
                            'Partially Compliant',
                            'Not Applicable',
                        ] as $response)

                            <option
                                value="{{ $response }}"
                                @selected(
                                    old(
                                        'response',
                                        $item?->response
                                    ) === $response
                                )
                            >
                                {{ $response }}
                            </option>

                        @endforeach

                    </select>

                    @error('response')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Severity --}}

                <div class="col-md-4">

                    <label class="form-label">
                        Severity
                    </label>

                    <select
                        name="severity"
                        class="form-select @error('severity') is-invalid @enderror"
                    >

                        <option value="">
                            -- Select Severity --
                        </option>

                        @foreach([
                            'Low',
                            'Medium',
                            'High',
                            'Critical',
                        ] as $severity)

                            <option
                                value="{{ $severity }}"
                                @selected(
                                    old(
                                        'severity',
                                        $item?->severity
                                    ) === $severity
                                )
                            >
                                {{ $severity }}
                            </option>

                        @endforeach

                    </select>

                    @error('severity')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Corrective Required --}}

                <div class="col-md-4">

                    <label class="form-label d-block">
                        Corrective Action Required
                    </label>

                    <div class="form-check form-switch mt-2">

                        <input
                            type="hidden"
                            name="corrective_required"
                            value="0"
                        >

                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="corrective_required"
                            value="1"
                            id="corrective_required"
                            @checked(
                                old(
                                    'corrective_required',
                                    $item?->corrective_required ?? false
                                )
                            )
                        >

                        <label
                            class="form-check-label"
                            for="corrective_required"
                        >
                            Yes, corrective action is required
                        </label>

                    </div>

                </div>


                {{-- Observation --}}

                <div class="col-12">

                    <label class="form-label">
                        Observation / Finding
                    </label>

                    <textarea
                        name="observation"
                        rows="4"
                        class="form-control @error('observation') is-invalid @enderror"
                        placeholder="Record what was observed during inspection..."
                    >{{ old(
                        'observation',
                        $item?->observation
                    ) }}</textarea>

                    @error('observation')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Remarks --}}

                <div class="col-12">

                    <label class="form-label">
                        Remarks
                    </label>

                    <textarea
                        name="remarks"
                        rows="3"
                        class="form-control @error('remarks') is-invalid @enderror"
                        placeholder="Additional remarks..."
                    >{{ old(
                        'remarks',
                        $item?->remarks
                    ) }}</textarea>

                    @error('remarks')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

            </div>

        </div>


        <div class="card-footer d-flex justify-content-end gap-2">

            <a
                href="{{ $cancelUrl }}"
                class="btn btn-outline-secondary"
            >
                Cancel
            </a>


            <button
                type="submit"
                class="btn btn-primary"
            >

                <i class="bi bi-save me-1"></i>

                {{ $submitLabel }}

            </button>

        </div>

    </div>

</form>