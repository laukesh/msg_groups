<div class="row g-3">

    {{-- ============================================================
        DOCUMENT TITLE
    ============================================================= --}}

    <div class="col-md-6">

        <label
            for="document_title"
            class="form-label"
        >
            Document Title
            <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            id="document_title"
            name="document_title"
            class="form-control @error('document_title') is-invalid @enderror"
            value="{{ old('document_title') }}"
            placeholder="Site Mobilization Report"
            maxlength="255"
            required
        >

        @error('document_title')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- ============================================================
        DOCUMENT NUMBER
    ============================================================= --}}

    <div class="col-md-3">

        <label class="form-label">
            Document Number
        </label>

        <input
            type="text"
            class="form-control bg-light"
            value="Auto Generated"
            readonly
        >

        <div class="form-text">
            Generated automatically after upload.
        </div>

    </div>


    {{-- ============================================================
        DOCUMENT TYPE
    ============================================================= --}}

    <div class="col-md-3">

        <label
            for="document_type"
            class="form-label"
        >
            Document Type
        </label>

        <select
            id="document_type"
            name="document_type"
            class="form-select @error('document_type') is-invalid @enderror"
        >

            <option value="">
                -- Select --
            </option>

            @foreach([
                'Completion Report',
                'Inspection Report',
                'Certificate',
                'Drawing',
                'Photograph',
                'Test Report',
                'Measurement',
                'Quality Document',
                'HSE Document',
                'Other',
            ] as $type)

                <option
                    value="{{ $type }}"
                    @selected(
                        old('document_type') === $type
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


    {{-- ============================================================
        RELATED PROGRESS
    ============================================================= --}}

    <div class="col-md-6">

        <label
            for="procurement_milestone_progress_id"
            class="form-label"
        >
            Related Progress Update
        </label>

        <select
            id="procurement_milestone_progress_id"
            name="procurement_milestone_progress_id"
            class="form-select @error('procurement_milestone_progress_id') is-invalid @enderror"
        >

            <option value="">
                -- Not Linked --
            </option>

            @foreach($progressUpdates as $progress)

                <option
                    value="{{ $progress->id }}"
                    @selected(
                        old(
                            'procurement_milestone_progress_id'
                        ) == $progress->id
                    )
                >

                    {{ $progress->progress_date?->format('d-m-Y') }}

                    -

                    {{ $progress->progress_percentage }}%

                </option>

            @endforeach

        </select>

        <div class="form-text">
            Optionally link this document to a specific
            milestone progress update.
        </div>

        @error('procurement_milestone_progress_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- ============================================================
        DOCUMENT FILE
    ============================================================= --}}

    <div class="col-md-6">

        <label
            for="document"
            class="form-label"
        >
            Document File
            <span class="text-danger">*</span>
        </label>

        <input
            type="file"
            id="document"
            name="document"
            class="form-control @error('document') is-invalid @enderror"
            accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
            required
        >

        <div class="form-text">
            PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG.
            Maximum size: 50 MB.
        </div>

        @error('document')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- ============================================================
        DESCRIPTION
    ============================================================= --}}

    <div class="col-12">

        <label
            for="description"
            class="form-label"
        >
            Description
        </label>

        <textarea
            id="description"
            name="description"
            class="form-control @error('description') is-invalid @enderror"
            rows="5"
            placeholder="Describe this deliverable document..."
        >{{ old('description') }}</textarea>

        @error('description')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

</div>