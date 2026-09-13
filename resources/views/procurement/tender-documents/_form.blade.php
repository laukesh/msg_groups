@php
    $document = $document ?? null;
@endphp

<div class="row g-3">

    {{-- Document Number --}}
    <div class="col-md-4">

        <label class="form-label">
            Document Number
        </label>

        <input
            type="text"
            name="document_number"
            class="form-control @error('document_number') is-invalid @enderror"
            value="{{ old(
                'document_number',
                $document?->document_number
            ) }}"
            maxlength="100"
            placeholder="e.g. DOC-TND-001"
        >

        @error('document_number')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Document Title --}}
    <div class="col-md-8">

        <label class="form-label">
            Document Title
            <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="document_title"
            class="form-control @error('document_title') is-invalid @enderror"
            value="{{ old(
                'document_title',
                $document?->document_title
            ) }}"
            maxlength="255"
            required
            placeholder="Enter document title"
        >

        @error('document_title')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Document Type --}}
    <div class="col-md-4">

        <label class="form-label">
            Document Type
            <span class="text-danger">*</span>
        </label>

        <select
            name="document_type"
            class="form-select @error('document_type') is-invalid @enderror"
            required
        >

            <option value="">
                -- Select Document Type --
            </option>

            @foreach([
                'Tender Notice',
                'Tender Conditions',
                'Scope of Work',
                'Technical Specification',
                'BOQ',
                'Drawing',
                'Commercial Terms',
                'Terms & Conditions',
                'Addendum',
                'Corrigendum',
                'Other',
            ] as $type)

                <option
                    value="{{ $type }}"
                    @selected(
                        old(
                            'document_type',
                            $document?->document_type
                        ) === $type
                    )
                >
                    {{ $type }}
                </option>

            @endforeach

        </select>

        @error('document_type')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Version --}}
    <div class="col-md-4">

        <label class="form-label">
            Version
            <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="version"
            class="form-control @error('version') is-invalid @enderror"
            value="{{ old(
                'version',
                $document?->version ?? '1.0'
            ) }}"
            maxlength="50"
            required
        >

        @error('version')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Issue Date --}}
    <div class="col-md-4">

        <label class="form-label">
            Issue Date
        </label>

        <input
            type="date"
            name="issue_date"
            class="form-control @error('issue_date') is-invalid @enderror"
            value="{{ old(
                'issue_date',
                $document?->issue_date?->format('Y-m-d')
            ) }}"
        >

        @error('issue_date')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- File --}}
    <div class="col-md-8">

        <label class="form-label">
            Document File
            @if(!$document)
                <span class="text-danger">*</span>
            @endif
        </label>

        <input
            type="file"
            name="file"
            class="form-control @error('file') is-invalid @enderror"
        >

        @if($document?->file_name)

            <div class="form-text">

                Current file:

                <strong>
                    {{ $document->file_name }}
                </strong>

            </div>

        @else

            <div class="form-text">
                Maximum file size: 50 MB.
            </div>

        @endif

        @error('file')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Status --}}
    <div class="col-md-4">

        <label class="form-label">
            Status
            <span class="text-danger">*</span>
        </label>

        <select
            name="status"
            class="form-select @error('status') is-invalid @enderror"
            required
        >

            @foreach([
                'Draft',
                'Published',
                'Superseded',
                'Cancelled',
            ] as $status)

                <option
                    value="{{ $status }}"
                    @selected(
                        old(
                            'status',
                            $document?->status ?? 'Draft'
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


    {{-- Description --}}
    <div class="col-12">

        <label class="form-label">
            Description
        </label>

        <textarea
            name="description"
            rows="4"
            class="form-control @error('description') is-invalid @enderror"
            placeholder="Enter document description"
        >{{ old(
            'description',
            $document?->description
        ) }}</textarea>

        @error('description')
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
            $document?->remarks
        ) }}</textarea>

        @error('remarks')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

</div>