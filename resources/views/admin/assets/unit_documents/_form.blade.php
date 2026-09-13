<div class="row">

    <div class="col-lg-6 col-md-12 mb-3">

        <label for="unit_id" class="form-label fw-semibold">
            Unit
            <span class="text-danger">*</span>
        </label>

        <select
            name="unit_id"
            id="unit_id"
            class="form-select @error('unit_id') is-invalid @enderror"
            required
        >

            <option value="">
                Select Unit
            </option>

            @foreach($units as $id => $unitNo)

                <option
                    value="{{ $id }}"
                    {{ old(
                        'unit_id',
                        $document->unit_id ?? ''
                    ) == $id ? 'selected' : '' }}
                >
                    {{ $unitNo }}
                </option>

            @endforeach

        </select>

        @error('unit_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    <div class="col-lg-6 col-md-12 mb-3">

        <label for="document_type" class="form-label fw-semibold">
            Document Type
            <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="document_type"
            id="document_type"
            class="form-control @error('document_type') is-invalid @enderror"
            value="{{ old(
                'document_type',
                $document->document_type ?? ''
            ) }}"
            placeholder="e.g. Lease Agreement"
            maxlength="100"
            required
        >

        @error('document_type')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    <div class="col-lg-6 mb-3">

        <label for="document_name" class="form-label fw-semibold">
            Document Name
            <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="document_name"
            id="document_name"
            class="form-control @error('document_name') is-invalid @enderror"
            value="{{ old(
                'document_name',
                $document->document_name ?? ''
            ) }}"
            placeholder="Document name"
            maxlength="255"
            required
        >

        @error('document_name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    <div class="col-lg-6 mb-3">

        <label for="document_number" class="form-label fw-semibold">
            Document Number
        </label>

        <input
            type="text"
            name="document_number"
            id="document_number"
            class="form-control @error('document_number') is-invalid @enderror"
            value="{{ old(
                'document_number',
                $document->document_number ?? ''
            ) }}"
            placeholder="Document number"
            maxlength="100"
        >

        @error('document_number')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    <div class="col-lg-6 mb-3">

        <label for="document_date" class="form-label fw-semibold">
            Document Date
        </label>

        <input
            type="date"
            name="document_date"
            id="document_date"
            class="form-control"
            value="{{ old(
                'document_date',
                isset($document->document_date)
                    ? $document->document_date->format('Y-m-d')
                    : ''
            ) }}"
        >

    </div>


    <div class="col-lg-6 mb-3">

        <label for="expiry_date" class="form-label fw-semibold">
            Expiry Date
        </label>

        <input
            type="date"
            name="expiry_date"
            id="expiry_date"
            class="form-control"
            value="{{ old(
                'expiry_date',
                isset($document->expiry_date)
                    ? $document->expiry_date->format('Y-m-d')
                    : ''
            ) }}"
        >

    </div>


    <div class="col-12 mb-3">

        <label for="document_path" class="form-label fw-semibold">
            Document Path
        </label>

        <input
            type="text"
            name="document_path"
            id="document_path"
            class="form-control"
            value="{{ old(
                'document_path',
                $document->document_path ?? ''
            ) }}"
            placeholder="storage/documents/example.pdf"
        >

    </div>


    <div class="col-12 mb-3">

        <label for="remarks" class="form-label fw-semibold">
            Remarks
        </label>

        <textarea
            name="remarks"
            id="remarks"
            rows="4"
            class="form-control"
            maxlength="1000"
        >{{ old('remarks', $document->remarks ?? '') }}</textarea>

    </div>

</div>