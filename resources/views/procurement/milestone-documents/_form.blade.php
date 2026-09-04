<div class="row g-3">

    {{-- Document Title --}}
    <div class="col-md-6">

        <label class="form-label">
            Document Title
            <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="document_title"
            class="form-control @error('document_title') is-invalid @enderror"
            value="{{ old('document_title') }}"
            placeholder="Site Mobilization Report"
            required
        >

        @error('document_title')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Document Number --}}
    <div class="col-md-3">

        <label class="form-label">
            Document Number
        </label>

        <input
            type="text"
            class="form-control"
            value="Auto Generated"
            readonly
        >

        <div class="form-text">
            Generated automatically after upload.
        </div>

    </div>


    {{-- Document Type --}}
    <div class="col-md-3">

        <label class="form-label">
            Document Type
        </label>

        <select
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


    {{-- Related Progress --}}
    <div class="col-md-6">

        <label class="form-label">
            Related Progress Update
        </label>

        <select
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

                    {{
                        $progress->progress_date
                            ?->format('d-m-Y')
                    }}

                    -

                    {{
                        $progress->progress_percentage
                    }}%

                </option>

            @endforeach

        </select>

        <div class="form-text">
            Optionally link this document to a specific
            progress update.
        </div>

        @error('procurement_milestone_progress_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Document File --}}
    <div class="col-md-6">

        <label class="form-label">
            Document File
            <span class="text-danger">*</span>
        </label>

        <input
            type="file"
            name="document"
            class="form-control @error('document') is-invalid @enderror"
            accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
            required
        >

        <div class="form-text">
            Allowed: PDF, DOC, DOCX, XLS, XLSX,
            JPG, JPEG, PNG.
            Maximum size: 50 MB.
        </div>

        @error('document')
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