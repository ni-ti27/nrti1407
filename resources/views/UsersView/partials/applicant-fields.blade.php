{{-- resources/views/UsersView/partials/applicant-fields.blade.php
     @include('UsersView.partials.applicant-fields', ['section' => 'personal', 'profileMode' => false])
     @include('UsersView.partials.applicant-fields', ['section' => 'address'])
     section      personal = name, gender, mobile, email, landline, education | address = address, location
     profileMode  true: name/mobile/email fall back to the session; mobile and email are read-only --}}
@php
    $profileMode = $profileMode ?? false;

    $nameValue   = old('name', $profileData->name ?? '');
    $mobileValue = old('mobile_no', $profileData->mobile_no ?? '');
    $emailValue  = old('email_id', $profileData->email_id ?? '');
    if ($profileMode) {
        $nameValue   = $nameValue ?: session('username');
        $mobileValue = $mobileValue ?: session('mobile_no');
        $emailValue  = $emailValue ?: session('email_id');
    }

    $genderValue = (string) old('gender', $profileData->gender ?? '');
    $stateValue  = (string) old('state', $profileData->state ?? '');
    $qualValue   = (string) old('qualification', $profileData->qualification ?? '');

    $eduRaw     = old('edu_status', $profileData->edu_status ?? '');
    $isEducated = in_array($eduRaw, ['educated', 1, '1'], true);
    $isNonEdu   = in_array($eduRaw, ['non_educated', 0, '0'], true);

    $star = '<span class="dbim-required" aria-hidden="true">*</span>';
    $sr   = '<span class="dbim-sr-only"> (required)</span>';
@endphp

@if ($section === 'personal')

    {{-- Name --}}
    <div class="mb-3">
        <label for="name" class="form-label fw-bold">Name {!! $star !!}</label>
        <input type="text" id="name" name="name" value="{{ $nameValue }}" autocomplete="name"
               class="form-control @error('name') is-invalid @enderror"
               data-validate data-required="true" data-msg="Enter your name."
               required aria-required="true" aria-describedby="nameError">
        <div id="nameError" class="invalid-feedback @error('name') d-block @enderror">@error('name'){{ $message }}@enderror</div>
    </div>

    {{-- Gender --}}
    <fieldset id="gender" class="mb-3" data-validate data-required="true"
              data-msg="Select your gender." aria-describedby="genderError">
        <legend class="dbim-legend fw-bold">Gender {!! $star !!}{!! $sr !!}</legend>
        @foreach (['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $value => $label)
            <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" id="gender_{{ $value }}"
                       value="{{ $value }}" {{ $genderValue === $value ? 'checked' : '' }}>
                <label class="form-check-label" for="gender_{{ $value }}">{{ $label }}</label>
            </div>
        @endforeach
        <div id="genderError" class="invalid-feedback @error('gender') d-block @enderror">@error('gender'){{ $message }}@enderror</div>
    </fieldset>

    {{-- Mobile --}}
    <div class="mb-3 dbim-field-sm">
        <label for="mobile_no" class="form-label fw-bold">Mobile number {!! $star !!}</label>
        <input type="text" id="mobile_no" name="mobile_no" value="{{ $mobileValue }}"
               maxlength="10" inputmode="numeric" autocomplete="tel-national"
               {{ $profileMode ? 'readonly aria-readonly=true' : '' }}
               class="form-control @error('mobile_no') is-invalid @enderror"
               data-validate data-required="true" data-msg="Enter your mobile number."
               data-pattern="^\d{10}$" data-pattern-msg="Enter a valid 10-digit mobile number."
               required aria-required="true" aria-describedby="mobile_noHelp mobile_noError">
        <div id="mobile_noHelp" class="form-text">10 digits. SMS alerts are sent to this number.</div>
        <div id="mobile_noError" class="invalid-feedback @error('mobile_no') d-block @enderror">@error('mobile_no'){{ $message }}@enderror</div>
    </div>

    {{-- Email --}}
    <div class="mb-3">
        <label for="email_id" class="form-label fw-bold">Email address {!! $star !!}</label>
        <input type="email" id="email_id" name="email_id" value="{{ $emailValue }}" autocomplete="email"
               {{ $profileMode ? 'readonly aria-readonly=true' : '' }}
               class="form-control @error('email_id') is-invalid @enderror"
               data-validate data-required="true" data-msg="Enter your email address."
               data-pattern-msg="Enter a valid email address, for example name@example.com."
               required aria-required="true" aria-describedby="email_idError">
        <div id="email_idError" class="invalid-feedback @error('email_id') d-block @enderror">@error('email_id'){{ $message }}@enderror</div>
    </div>

    {{-- Landline (optional) --}}
    <div class="mb-3">
        <label for="phone_no" class="form-label fw-bold">Landline number (optional)</label>
        <input type="tel" id="phone_no" name="phone_no" value="{{ old('phone_no', $profileData->phone_no ?? '') }}"
               autocomplete="tel" class="form-control @error('phone_no') is-invalid @enderror"
               aria-describedby="phone_noHelp phone_noError">
        <div id="phone_noHelp" class="form-text">Include the STD code, for example 0832 2410816.</div>
        <div id="phone_noError" class="invalid-feedback @error('phone_no') d-block @enderror">@error('phone_no'){{ $message }}@enderror</div>
    </div>

    {{-- Educational status --}}
    <fieldset id="edu_status" class="mb-3" data-validate data-required="true"
              data-msg="Select your educational status." aria-describedby="edu_statusError">
        <legend class="dbim-legend fw-bold">Educational status {!! $star !!}{!! $sr !!}</legend>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="edu_status" id="edu_status1" value="educated" {{ $isEducated ? 'checked' : '' }}>
            <label class="form-check-label" for="edu_status1">Educated</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="edu_status" id="edu_status2" value="non_educated" {{ $isNonEdu ? 'checked' : '' }}>
            <label class="form-check-label" for="edu_status2">Non-educated</label>
        </div>
        <div id="edu_statusError" class="invalid-feedback @error('edu_status') d-block @enderror">@error('edu_status'){{ $message }}@enderror</div>
    </fieldset>

    {{-- Qualification (only when educated) --}}
    <div id="qualification-wrap" data-conditional {{ $isEducated ? '' : 'hidden' }}>
        <fieldset id="qualification" class="mb-3" data-validate data-required="true"
                  data-msg="Select your qualification." aria-describedby="qualificationError">
            <legend class="dbim-legend fw-bold">Qualification {!! $star !!}{!! $sr !!}</legend>
            @foreach (['1' => '10th standard', '2' => '12th standard', '3' => 'Graduate', '4' => 'Post graduate'] as $value => $label)
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="qualification" id="qualification{{ $value }}"
                           value="{{ $value }}" {{ $qualValue === (string) $value ? 'checked' : '' }}>
                    <label class="form-check-label" for="qualification{{ $value }}">{{ $label }}</label>
                </div>
            @endforeach
            <div id="qualificationError" class="invalid-feedback @error('qualification') d-block @enderror">@error('qualification'){{ $message }}@enderror</div>
        </fieldset>
    </div>

@endif

@if ($section === 'address')

    @foreach ([1, 2, 3] as $n)
        @php $field = 'address_line' . $n; @endphp
        <div class="mb-3">
            <label for="{{ $field }}" class="form-label fw-bold">Address line {{ $n }} {!! $star !!}</label>
            <input type="text" id="{{ $field }}" name="{{ $field }}"
                   value="{{ old($field, $profileData->$field ?? '') }}" autocomplete="address-line{{ $n }}"
                   class="form-control @error($field) is-invalid @enderror"
                   data-validate data-required="true" data-msg="Enter address line {{ $n }}."
                   required aria-required="true" aria-describedby="{{ $field }}Error">
            <div id="{{ $field }}Error" class="invalid-feedback @error($field) d-block @enderror">@error($field){{ $message }}@enderror</div>
        </div>
    @endforeach

    <div class="mb-3 dbim-field-sm">
        <label for="pincode" class="form-label fw-bold">Pincode {!! $star !!}</label>
        <input type="text" id="pincode" name="pincode" value="{{ old('pincode', $profileData->pincode ?? '') }}"
               maxlength="6" inputmode="numeric" autocomplete="postal-code"
               class="form-control @error('pincode') is-invalid @enderror"
               data-validate data-required="true" data-msg="Enter your pincode."
               data-pattern="^\d{6}$" data-pattern-msg="Enter a 6-digit pincode."
               required aria-required="true" aria-describedby="pincodeError">
        <div id="pincodeError" class="invalid-feedback @error('pincode') d-block @enderror">@error('pincode'){{ $message }}@enderror</div>
    </div>

    <div class="mb-3">
        <label for="state" class="form-label fw-bold">State {!! $star !!}</label>
        <select id="state" name="state" class="form-select @error('state') is-invalid @enderror"
                data-validate data-required="true" data-msg="Select your state."
                required aria-required="true" aria-describedby="stateError">
            <option value="">Select state</option>
            <option value="0" {{ $stateValue === '0' ? 'selected' : '' }}>Others</option>
            @foreach ($stateData as $state)
                <option value="{{ $state->id }}" {{ $stateValue === (string) $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
            @endforeach
        </select>
        <div id="stateError" class="invalid-feedback @error('state') d-block @enderror">@error('state'){{ $message }}@enderror</div>
    </div>

    {{-- Only India is offered, so it is shown read-only --}}
    <div class="mb-3">
        <label for="country" class="form-label fw-bold">Country</label>
        <input type="text" id="country" value="India" readonly aria-readonly="true" class="form-control">
        <input type="hidden" name="country" value="1">
    </div>

    @foreach (['district' => 'District', 'taluka' => 'Taluka', 'village' => 'Village'] as $field => $label)
        <div class="mb-3">
            <label for="{{ $field }}" class="form-label fw-bold">{{ $label }} {!! $star !!}</label>
            <select id="{{ $field }}" name="{{ $field }}" class="form-select @error($field) is-invalid @enderror"
                    data-validate data-required="true" data-msg="Select your {{ $field }}."
                    required aria-required="true" aria-describedby="{{ $field }}Error">
                <option value="">Select {{ $field }}</option>
            </select>
            <div id="{{ $field }}Error" class="invalid-feedback @error($field) d-block @enderror">@error($field){{ $message }}@enderror</div>
        </div>
    @endforeach

@endif