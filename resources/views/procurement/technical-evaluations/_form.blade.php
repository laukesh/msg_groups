@php
    $evaluation = $evaluation ?? null;
@endphp

<div class="row g-3">

    @php
    $evaluation = $evaluation ?? null;
@endphp

<div class="row g-3">

    {{-- Evaluation Number --}}
    @if($evaluation)

        <div class="col-md-6">

            <label class="form-label">
                Evaluation Number
            </label>

            <input
                type="text"
                class="form-control"
                value="{{ $evaluation->evaluation_number }}"
                readonly
            >

            <div class="form-text">
                Evaluation number is system generated.
            </div>

        </div>

    @else

        <div class="col-md-6">

            <label class="form-label">
                Evaluation Number
            </label>

            <input
                type="text"
                class="form-control"
                value="Will be generated automatically"
                readonly
            >

            <div class="form-text">
                Evaluation number will be generated when saved.
            </div>

        </div>

    @endif


    {{-- Evaluation Date --}}
    <div class="col-md-6">

        <label class="form-label">
            Evaluation Date
        </label>

        <input
            type="date"
            name="evaluation_date"
            class="form-control @error('evaluation_date') is-invalid @enderror"
            value="{{ old(
                'evaluation_date',
                $evaluation?->evaluation_date?->format('Y-m-d')
            ) }}"
        >

        @error('evaluation_date')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Evaluator --}}
    <div class="col-md-6">

        <label class="form-label">
            Evaluator
        </label>

        <input
            type="text"
            name="evaluator_name"
            class="form-control @error('evaluator_name') is-invalid @enderror"
            value="{{ old(
                'evaluator_name',
                $evaluation?->evaluator_name
                    ?? auth()->user()?->name
            ) }}"
            maxlength="255"
            placeholder="Evaluator name"
        >

        @error('evaluator_name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Technical Score --}}
    <div class="col-md-4">

        <label class="form-label">
            Technical Score
            <span class="text-danger">*</span>
        </label>

        <input
            type="number"
            name="technical_score"
            id="technical_score"
            class="form-control @error('technical_score') is-invalid @enderror"
            value="{{ old(
                'technical_score',
                $evaluation?->technical_score ?? 0
            ) }}"
            min="0"
            step="0.01"
            required
        >

        @error('technical_score')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Maximum Score --}}
    <div class="col-md-4">

        <label class="form-label">
            Maximum Score
            <span class="text-danger">*</span>
        </label>

        <input
            type="number"
            name="maximum_score"
            id="maximum_score"
            class="form-control @error('maximum_score') is-invalid @enderror"
            value="{{ old(
                'maximum_score',
                $evaluation?->maximum_score ?? 100
            ) }}"
            min="0.01"
            step="0.01"
            required
        >

        @error('maximum_score')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Passing Score --}}
    <div class="col-md-4">

        <label class="form-label">
            Passing Score
            <span class="text-danger">*</span>
        </label>

        <input
            type="number"
            name="passing_score"
            id="passing_score"
            class="form-control @error('passing_score') is-invalid @enderror"
            value="{{ old(
                'passing_score',
                $evaluation?->passing_score ?? 60
            ) }}"
            min="0"
            step="0.01"
            required
        >

        @error('passing_score')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Technical Compliance --}}
    <div class="col-md-6">

        <label class="form-label">
            Technical Compliance
            <span class="text-danger">*</span>
        </label>

        <select
            name="technical_compliance"
            class="form-select @error('technical_compliance') is-invalid @enderror"
            required
        >

            @foreach([
                'Pending',
                'Compliant',
                'Partially Compliant',
                'Non-Compliant',
            ] as $compliance)

                <option
                    value="{{ $compliance }}"
                    @selected(
                        old(
                            'technical_compliance',
                            $evaluation?->technical_compliance
                                ?? 'Pending'
                        ) === $compliance
                    )
                >
                    {{ $compliance }}
                </option>

            @endforeach

        </select>

        @error('technical_compliance')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Status --}}
    <div class="col-md-6">

        <label class="form-label">
            Evaluation Status
            <span class="text-danger">*</span>
        </label>

        <select
            name="status"
            class="form-select @error('status') is-invalid @enderror"
            required
        >

            @foreach([
                'Draft',
                'Under Evaluation',
                'Completed',
                'Approved',
                'Rejected',
            ] as $status)

                <option
                    value="{{ $status }}"
                    @selected(
                        old(
                            'status',
                            $evaluation?->status ?? 'Draft'
                        ) === $status
                    )
                >
                    {{ $status }}
                </option>

            @endforeach

        </select>

        @error('status')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Strengths --}}
    <div class="col-md-6">

        <label class="form-label">
            Strengths
        </label>

        <textarea
            name="strengths"
            rows="5"
            class="form-control @error('strengths') is-invalid @enderror"
            placeholder="Enter technical strengths"
        >{{ old(
            'strengths',
            $evaluation?->strengths
        ) }}</textarea>

        @error('strengths')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Weaknesses --}}
    <div class="col-md-6">

        <label class="form-label">
            Weaknesses
        </label>

        <textarea
            name="weaknesses"
            rows="5"
            class="form-control @error('weaknesses') is-invalid @enderror"
            placeholder="Enter technical weaknesses"
        >{{ old(
            'weaknesses',
            $evaluation?->weaknesses
        ) }}</textarea>

        @error('weaknesses')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Evaluation Summary --}}
    <div class="col-12">

        <label class="form-label">
            Evaluation Summary
        </label>

        <textarea
            name="evaluation_summary"
            rows="5"
            class="form-control @error('evaluation_summary') is-invalid @enderror"
            placeholder="Enter overall technical evaluation summary"
        >{{ old(
            'evaluation_summary',
            $evaluation?->evaluation_summary
        ) }}</textarea>

        @error('evaluation_summary')
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
            placeholder="Additional remarks"
        >{{ old(
            'remarks',
            $evaluation?->remarks
        ) }}</textarea>

        @error('remarks')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

</div>


{{-- Score Guidance --}}
<div class="alert alert-info mt-4 mb-0">

    <strong>Evaluation Rule:</strong>

    A submission is

    <strong>Qualified</strong>

    only when:

    <ul class="mb-0 mt-2">

        <li>
            Technical Compliance is
            <strong>Compliant</strong>
        </li>

        <li>
            Technical Score is greater than or equal to
            Passing Score
        </li>

    </ul>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const score = document.getElementById('technical_score');
    const maximum = document.getElementById('maximum_score');

    if (!score || !maximum) {
        return;
    }

    score.addEventListener('input', function () {

        if (
            parseFloat(score.value || 0)
            >
            parseFloat(maximum.value || 0)
        ) {

            score.setCustomValidity(
                'Technical score cannot exceed maximum score.'
            );

        } else {

            score.setCustomValidity('');

        }

    });

});

</script>