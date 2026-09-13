@php
    $document = $document ?? null;
@endphp

<form
    method="POST"
    action="{{ $formAction }}"
    enctype="multipart/form-data"
>

    @csrf

    @if($formMethod)
        @method($formMethod)
    @endif

    <div class="card">

        <div class="card-header">
            <strong>Document Details</strong>
        </div>

        <div class="card-body">

            <div class="row g-3">

                {{-- Document Number --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Document Number
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $documentNumber ?? $document?->document_number }}"
                        readonly
                    >

                    <input
                        type="hidden"
                        name="document_number"
                        value="{{ $documentNumber ?? $document?->document_number }}"
                    >

                </div>


                {{-- Document Name --}}
                <div class="col-md-8">

                    <label class="form-label">
                        Document Name
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="document_name"
                        class="form-control @error('document_name') is-invalid @enderror"
                        value="{{ old(
                            'document_name',
                            $document?->document_name
                        ) }}"
                        placeholder="Enter document name"
                        required
                    >

                    @error('document_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Document Type --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Document Type
                    </label>

                    <select
                        name="document_type"
                        class="form-select @error('document_type') is-invalid @enderror"
                    >

                        <option value="">
                            -- Select Type --
                        </option>

                        @foreach([
                            'Attendance Sheet',
                            'Presentation',
                            'Training Material',
                            'Safety Circular',
                            'Photo',
                            'Video',
                            'Certificate',
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


                {{-- Document Date --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Document Date
                    </label>

                    <input
                        type="date"
                        name="document_date"
                        class="form-control @error('document_date') is-invalid @enderror"
                        value="{{ old(
                            'document_date',
                            $document?->document_date?->format('Y-m-d')
                        ) }}"
                    >

                    @error('document_date')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- File --}}
                <div class="col-md-4">

                    <label class="form-label">
                        File
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="file"
                        name="file"
                        class="form-control @error('file') is-invalid @enderror"
                        required
                    >

                    <div class="form-text">
                        Maximum file size: 50 MB
                    </div>

                    @error('file')
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
                        placeholder="Document description"
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

                <i class="bi bi-upload me-1"></i>

                {{ $submitLabel }}

            </button>

        </div>

    </div>

</form>