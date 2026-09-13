<div class="row g-3">

    {{-- REQUIREMENT --}}

    <div class="col-md-6">

        <label class="form-label">
            Handover Requirement
        </label>

        <select
            name="requirement_id"
            class="form-select">

            <option value="">
                Select Requirement
            </option>

            @foreach($requirements as $requirement)

                <option
                    value="{{ $requirement->id }}"
                    @selected(
                        old(
                            'requirement_id',
                            $document?->requirement_id
                        ) == $requirement->id
                    )>

                    {{ $requirement->requirement_code }}
                    -
                    {{ $requirement->title }}

                </option>

            @endforeach

        </select>

        <small class="text-muted">
            Linking a document to a requirement allows automatic readiness update after approval.
        </small>

    </div>


    {{-- DOCUMENT TYPE --}}

    <div class="col-md-6">

        <label class="form-label">
            Document Type
            <span class="text-danger">*</span>
        </label>

        <select
            name="document_type"
            class="form-select"
            required>

            <option value="">
                Select Document Type
            </option>

            @foreach($documentTypes as $type)

                <option
                    value="{{ $type }}"
                    @selected(
                        old(
                            'document_type',
                            $document?->document_type
                        ) === $type
                    )>

                    {{ $type }}

                </option>

            @endforeach

        </select>

    </div>


    {{-- TITLE --}}

    <div class="col-12">

        <label class="form-label">
            Document Title
            <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="title"
            class="form-control"
            value="{{ old(
                'title',
                $document?->title
            ) }}"
            placeholder="Example: HVAC O&M Manual"
            required>

    </div>


    {{-- VERSION --}}

    <div class="col-md-4">

        <label class="form-label">
            Document Version
        </label>

        <input
            type="text"
            name="document_version"
            class="form-control"
            value="{{ old(
                'document_version',
                $document?->document_version ?? '1.0'
            ) }}"
            placeholder="1.0">

    </div>


    {{-- FILE --}}

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
            class="form-control"
            {{ !$document ? 'required' : '' }}>

        <small class="text-muted">

            PDF, Word, Excel, PowerPoint or image.
            Maximum 50 MB.

        </small>

        @if($document?->file_name)

            <div class="mt-2">

                <i class="ri-file-line"></i>

                Current:
                <strong>
                    {{ $document->file_name }}
                </strong>

            </div>

        @endif

    </div>


    {{-- DESCRIPTION --}}

    <div class="col-12">

        <label class="form-label">
            Description
        </label>

        <textarea
            name="description"
            class="form-control"
            rows="4"
            placeholder="Document description...">{{ old(
                'description',
                $document?->description
            ) }}</textarea>

    </div>


    {{-- REMARKS --}}

    <div class="col-12">

        <label class="form-label">
            Remarks
        </label>

        <textarea
            name="remarks"
            class="form-control"
            rows="3">{{ old(
                'remarks',
                $document?->remarks
            ) }}</textarea>

    </div>

</div>