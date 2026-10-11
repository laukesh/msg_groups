@php
    $bidder = $procurementBidder ?? null;
@endphp

<div class="row g-3">

    {{-- Bidder Code --}}
    <div class="col-md-4">

        <label class="form-label">
            Bidder Code
        </label>

        @if($bidder)

            <input
                type="text"
                class="form-control"
                value="{{ $bidder->bidder_code }}"
                readonly
            >

            <div class="form-text">
                Bidder code cannot be changed.
            </div>

        @else

            <input
                type="text"
                class="form-control"
                value="Auto-generated"
                readonly
            >

            <div class="form-text">
                Bidder code will be generated automatically.
            </div>

        @endif

    </div>


    {{-- Company Name --}}
    <div class="col-md-8">

        <label class="form-label">
            Company Name
            <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            name="company_name"
            class="form-control @error('company_name') is-invalid @enderror"
            maxlength="255"
            value="{{ old(
                'company_name',
                $bidder?->company_name
            ) }}"
            required
        >

        @error('company_name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Company Registration Number --}}
    <div class="col-md-4">

        <label class="form-label">
            Company Registration No.
        </label>

        <input
            type="text"
            name="company_registration_no"
            class="form-control @error('company_registration_no') is-invalid @enderror"
            maxlength="100"
            value="{{ old(
                'company_registration_no',
                $bidder?->company_registration_no
            ) }}"
        >

        @error('company_registration_no')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- GST --}}
    <div class="col-md-4">

        <label class="form-label">
            GST Number
        </label>

        <input
            type="text"
            name="gst_number"
            class="form-control @error('gst_number') is-invalid @enderror"
            maxlength="50"
            value="{{ old(
                'gst_number',
                $bidder?->gst_number
            ) }}"
        >

        @error('gst_number')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- PAN --}}
    <div class="col-md-4">

        <label class="form-label">
            PAN Number
        </label>

        <input
            type="text"
            name="pan_number"
            class="form-control @error('pan_number') is-invalid @enderror"
            maxlength="50"
            value="{{ old(
                'pan_number',
                $bidder?->pan_number
            ) }}"
        >

        @error('pan_number')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Contact Person --}}
    <div class="col-md-6">

        <label class="form-label">
            Contact Person
        </label>

        <input
            type="text"
            name="contact_person"
            class="form-control @error('contact_person') is-invalid @enderror"
            maxlength="255"
            value="{{ old(
                'contact_person',
                $bidder?->contact_person
            ) }}"
        >

        @error('contact_person')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Email --}}
    <div class="col-md-3">

        <label class="form-label">
            Email
        </label>

        <input
            type="email"
            name="email"
            class="form-control @error('email') is-invalid @enderror"
            maxlength="255"
            value="{{ old(
                'email',
                $bidder?->email
            ) }}"
        >

        @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Phone --}}
    <div class="col-md-3">

        <label class="form-label">
            Phone
        </label>

        <input
            type="text"
            name="phone"
            class="form-control @error('phone') is-invalid @enderror"
            maxlength="50"
            value="{{ old(
                'phone',
                $bidder?->phone
            ) }}"
        >

        @error('phone')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Address --}}
    <div class="col-12">

        <label class="form-label">
            Address
        </label>

        <textarea
            name="address"
            rows="3"
            class="form-control @error('address') is-invalid @enderror"
        >{{ old(
            'address',
            $bidder?->address
        ) }}</textarea>

        @error('address')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- City --}}
    <div class="col-md-3">

        <label class="form-label">
            City
        </label>

        <input
            type="text"
            name="city"
            class="form-control @error('city') is-invalid @enderror"
            maxlength="100"
            value="{{ old(
                'city',
                $bidder?->city
            ) }}"
        >

        @error('city')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- State --}}
    <div class="col-md-3">

        <label class="form-label">
            State
        </label>

        <input
            type="text"
            name="state"
            class="form-control @error('state') is-invalid @enderror"
            maxlength="100"
            value="{{ old(
                'state',
                $bidder?->state
            ) }}"
        >

        @error('state')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Country --}}
    <div class="col-md-3">

        <label class="form-label">
            Country
        </label>

        <input
            type="text"
            name="country"
            class="form-control @error('country') is-invalid @enderror"
            maxlength="100"
            value="{{ old(
                'country',
                $bidder?->country ?? 'India'
            ) }}"
        >

        @error('country')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>


    {{-- Postal Code --}}
    <div class="col-md-3">

        <label class="form-label">
            Postal Code
        </label>

        <input
            type="text"
            name="postal_code"
            class="form-control @error('postal_code') is-invalid @enderror"
            maxlength="20"
            value="{{ old(
                'postal_code',
                $bidder?->postal_code
            ) }}"
        >

        @error('postal_code')
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
                'Active',
                'Inactive',
                'Blacklisted',
            ] as $status)

                <option
                    value="{{ $status }}"
                    @selected(
                        old(
                            'status',
                            $bidder?->status ?? 'Active'
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


    {{-- Remarks --}}
    <div class="col-12">

        <label class="form-label">
            Remarks
        </label>

        <textarea
            name="remarks"
            rows="4"
            class="form-control @error('remarks') is-invalid @enderror"
        >{{ old(
            'remarks',
            $bidder?->remarks
        ) }}</textarea>

        @error('remarks')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>

</div>