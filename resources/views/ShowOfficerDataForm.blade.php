{{-- ============================================================
     Officer Profile Update  (DBIM / GIGW 3.0, keyboard accessible)
     Same layout as the RTI New Request form: note on the left, form card on the right.
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     Step 1: verify mobile number with OTP.  Step 2: update profile.
     Styles: section 65 / 65b in public/css/dbim.css (.dbim-fa-*)
     Hints are in the placeholders; messages only appear under a
     field when it has an error.
     No inline styles, no inline event handlers.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Officer Profile Update | RTI Online, Government of Goa')

@section('content')
@php
    // "Ask Later" goes to the dashboard of the officer's role
    $askLaterUrl = match ((int) session('role')) {
        4       => '/RTIMIS/dashboard/{new_FA}',
        5, 6, 7 => '/RTIMIS/dashboard/{new_SA}',
        default => '/RTIMIS/dashboard/',
    };

    $retirementDate = '';
    if (!empty($getUserDetails->service_retirement_dt ?? null)) {
        $retirementDate = \Carbon\Carbon::parse($getUserDetails->service_retirement_dt)->format('d-m-Y');
    }

    $verified = session('VerifiedOTP') == true;
    $star = '<span class="dbim-required" aria-hidden="true">*</span>';
@endphp

<div class="container pt-4 pb-5">

    {{-- Messages from the server --}}
    @include('partials.flash-messages')

    <div class="row g-4 align-items-start">

        {{-- Left column: note --}}
        <div class="col-12 col-lg-4">
            <div class="dbim-fa-note" role="note">
                <svg class="dbim-fa-icon" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" focusable="false">
                    <circle cx="12" cy="12" r="10"/><path d="M12 16v-5"/><path d="M12 8h.01"/>
                </svg>
                <div class="dbim-fa-note__body">
                    <p><strong>Note:</strong></p>
                    @if(!$verified)
                        <ul>
                            <li>Step 1 of 2: enter your registered mobile number and request an OTP, then enter the OTP to verify it.</li>
                            <li>You can choose <strong>Ask Later</strong> to go to your dashboard and update your profile another time.</li>
                        </ul>
                    @else
                        <ul>
                            <li>Step 2 of 2: check your details and update them where needed.</li>
                            <li>Department details, username, role and the verified mobile number cannot be changed here.</li>
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right column: form card --}}
        <div class="col-12 col-lg-8">
            <section class="dbim-fa-card" aria-labelledby="pageTitle">

                <h1 id="pageTitle" class="dbim-fa-title">Officer's Profile Update</h1>
                <p class="dbim-fa-legend">
                    {{ $verified ? 'Step 2 of 2: Update your profile details.' : 'Step 1 of 2: Verify your registered mobile number.' }}
                    Fields marked with (<span class="dbim-required" aria-hidden="true">*</span><span class="dbim-sr-only">asterisk</span>) are required.
                </p>

                {{-- Error summary: filled by script or by the server; receives focus --}}
                <div id="errorSummary" class="alert alert-danger" role="alert" tabindex="-1"
                     @if(!$errors->any()) hidden @endif>
                    <p class="fw-bold mb-2">Please correct the following:</p>
                    <ul class="mb-0" id="errorSummaryList">
                        @foreach($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>

                {{-- =====================================================
                     STEP 1: Verify registered mobile number
                     ===================================================== --}}
                @if(!$verified)
                    <form id="sendOTPForm" name="sendOTPForm" method="POST"
                          action="{{ route('officerVerifyOtp') }}" novalidate>
                        @csrf

                        {{-- Mobile + Send OTP --}}
                        <div class="dbim-fa-field">
                            <label for="mobile_no" class="dbim-fa-label">Officer mobile number {!! $star !!}</label>
                            <div class="input-group">
                                <input type="tel" class="form-control @error('mobile_no') is-invalid @enderror"
                                       id="mobile_no" name="mobile_no"
                                       value="{{ old('mobile_no', $mobileTest ?? '') }}"
                                       placeholder="Enter 10 digit mobile (for receiving SMS alerts)"
                                       inputmode="numeric" maxlength="10" autocomplete="tel-national"
                                       required aria-required="true"
                                       aria-invalid="{{ $errors->has('mobile_no') ? 'true' : 'false' }}"
                                       aria-describedby="mobileNoError">
                                <button type="button" id="sendOTP" class="dbim-fa-btn dbim-fa-btn--outline"
                                        title="Send OTP to this mobile number">Send OTP</button>
                            </div>
                            <div id="mobileNoError" class="invalid-feedback @error('mobile_no') d-block @enderror">@error('mobile_no'){{ $message }}@enderror</div>
                        </div>

                        {{-- OTP + Verify --}}
                        <div class="dbim-fa-field">
                            <label for="otp" class="dbim-fa-label">OTP {!! $star !!}</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('otp') is-invalid @enderror"
                                       id="otp" name="otp"
                                       placeholder="Enter the OTP received on your mobile"
                                       inputmode="numeric" autocomplete="one-time-code" aria-required="true"
                                       aria-invalid="{{ $errors->has('otp') ? 'true' : 'false' }}"
                                       aria-describedby="otpError">
                                <button type="button" id="verifyOtp" class="dbim-fa-btn"
                                        title="Verify the OTP">Verify</button>
                            </div>
                            <div id="otpError" class="invalid-feedback @error('otp') d-block @enderror">@error('otp'){{ $message }}@enderror</div>
                        </div>

                        <div class="dbim-fa-actions">
                            <a href="{{ $askLaterUrl }}" class="dbim-fa-btn dbim-fa-btn--outline d-inline-flex align-items-center text-decoration-none">Ask Later</a>
                        </div>
                    </form>
                @endif

                {{-- =====================================================
                     STEP 2: Profile form (after OTP is verified)
                     ===================================================== --}}
                @if($verified)
                    <form id="profileUpdate" name="profileUpdate" method="POST"
                          action="{{ route('updateOfficerData') }}" novalidate>
                        @csrf

                        {{-- Department details (read only) --}}
                        <h2 class="dbim-fa-card__title">Department details</h2>
                        <dl class="row dbim-detail mb-4">
                            <dt class="col-sm-4">Department</dt>
                            <dd class="col-sm-8">{{ Session::get('department_name') }}</dd>
                            <dt class="col-sm-4">Office</dt>
                            <dd class="col-sm-8">{{ Session::get('subDepartment_name') }}</dd>
                            <dt class="col-sm-4">Sub-office</dt>
                            <dd class="col-sm-8 mb-0">{{ Session::get('officeDepartment_name') }}</dd>
                        </dl>

                        {{-- Personal details --}}
                        <h2 class="dbim-fa-card__title">Personal details</h2>
                        <dl class="row dbim-detail mb-4">
                            <dt class="col-sm-4">Username</dt>
                            <dd class="col-sm-8">{{ Session::get('username') }}</dd>
                            <dt class="col-sm-4">Role</dt>
                            <dd class="col-sm-8 mb-0">{{ Session::get('roleName') }}</dd>
                        </dl>

                        <div class="dbim-fa-field">
                            <label for="officerName" class="dbim-fa-label">Officer name {!! $star !!}</label>
                            <input type="text" class="form-control @error('officerName') is-invalid @enderror"
                                   id="officerName" name="officerName"
                                   value="{{ old('officerName', $getUserDetails->name ?? '') }}"
                                   placeholder="Enter your name" autocomplete="name"
                                   required aria-required="true"
                                   aria-invalid="{{ $errors->has('officerName') ? 'true' : 'false' }}"
                                   aria-describedby="officerNameError">
                            <div id="officerNameError" class="invalid-feedback @error('officerName') d-block @enderror">@error('officerName'){{ $message }}@enderror</div>
                        </div>

                        <div class="dbim-fa-field">
                            <label for="gender" class="dbim-fa-label">Gender</label>
                            <select class="form-select @error('gender') is-invalid @enderror"
                                    id="gender" name="gender"
                                    aria-invalid="{{ $errors->has('gender') ? 'true' : 'false' }}"
                                    aria-describedby="genderError">
                                @php $genderValue = old('gender', $getUserDetails->gender ?? ''); @endphp
                                <option value="M" @selected($genderValue == 'M')>Male</option>
                                <option value="F" @selected($genderValue == 'F')>Female</option>
                                <option value="O" @selected($genderValue == 'O')>Other</option>
                            </select>
                            <div id="genderError" class="invalid-feedback @error('gender') d-block @enderror">@error('gender'){{ $message }}@enderror</div>
                        </div>

                        <div class="dbim-fa-field">
                            <label for="designation" class="dbim-fa-label">Official designation {!! $star !!}</label>
                            <input type="text" class="form-control @error('designation') is-invalid @enderror"
                                   id="designation" name="designation"
                                   value="{{ old('designation', $getUserDetails->designation ?? '') }}"
                                   placeholder="Enter your official designation" autocomplete="organization-title"
                                   required aria-required="true"
                                   aria-invalid="{{ $errors->has('designation') ? 'true' : 'false' }}"
                                   aria-describedby="designationError">
                            <div id="designationError" class="invalid-feedback @error('designation') d-block @enderror">@error('designation'){{ $message }}@enderror</div>
                        </div>

                        <div class="dbim-fa-field">
                            <label for="email_address" class="dbim-fa-label">Email address {!! $star !!}</label>
                            <input type="email" class="form-control @error('email_address') is-invalid @enderror"
                                   id="email_address" name="email_address"
                                   value="{{ old('email_address', $getUserDetails->email ?? '') }}"
                                   placeholder="Enter email, for example name@example.com (for alerts)"
                                   autocomplete="email"
                                   required aria-required="true"
                                   aria-invalid="{{ $errors->has('email_address') ? 'true' : 'false' }}"
                                   aria-describedby="emailAddressError">
                            <div id="emailAddressError" class="invalid-feedback @error('email_address') d-block @enderror">@error('email_address'){{ $message }}@enderror</div>
                        </div>

                        {{-- Mobile (verified, read only) --}}
                        <div class="dbim-fa-field">
                            <label for="mobile_no" class="dbim-fa-label">Officer mobile number</label>
                            <input type="tel" class="form-control"
                                   id="mobile_no" name="mobile_no"
                                   value="{{ session('officerMobileNumber') }}"
                                   placeholder="Verified mobile number (for receiving SMS alerts)"
                                   readonly aria-readonly="true">
                        </div>

                        {{-- Additional details --}}
                        <h2 class="dbim-fa-card__title">Additional details</h2>

                        <div class="dbim-fa-field">
                            <label for="address1" class="dbim-fa-label">Official address line 1</label>
                            <input type="text" class="form-control @error('address1') is-invalid @enderror"
                                   id="address1" name="address1"
                                   value="{{ old('address1', $getUserDetails->address1 ?? '') }}"
                                   placeholder="Enter official address line 1"
                                   autocomplete="address-line1"
                                   aria-invalid="{{ $errors->has('address1') ? 'true' : 'false' }}"
                                   aria-describedby="address1Error">
                            <div id="address1Error" class="invalid-feedback @error('address1') d-block @enderror">@error('address1'){{ $message }}@enderror</div>
                        </div>

                        <div class="dbim-fa-field">
                            <label for="address2" class="dbim-fa-label">Official address line 2</label>
                            <input type="text" class="form-control @error('address2') is-invalid @enderror"
                                   id="address2" name="address2"
                                   value="{{ old('address2', $getUserDetails->address2 ?? '') }}"
                                   placeholder="Enter official address line 2"
                                   autocomplete="address-line2"
                                   aria-invalid="{{ $errors->has('address2') ? 'true' : 'false' }}"
                                   aria-describedby="address2Error">
                            <div id="address2Error" class="invalid-feedback @error('address2') d-block @enderror">@error('address2'){{ $message }}@enderror</div>
                        </div>

                        <div class="dbim-fa-field">
                            <label for="address3" class="dbim-fa-label">Official address line 3</label>
                            <input type="text" class="form-control @error('address3') is-invalid @enderror"
                                   id="address3" name="address3"
                                   value="{{ old('address3', $getUserDetails->address3 ?? '') }}"
                                   placeholder="Enter official address line 3"
                                   autocomplete="address-line3"
                                   aria-invalid="{{ $errors->has('address3') ? 'true' : 'false' }}"
                                   aria-describedby="address3Error">
                            <div id="address3Error" class="invalid-feedback @error('address3') d-block @enderror">@error('address3'){{ $message }}@enderror</div>
                        </div>

                        <div class="dbim-fa-field dbim-field-sm">
                            <label for="service_retirement_dt" class="dbim-fa-label">Service retirement date</label>
                            <input type="text" class="form-control @error('service_retirement_dt') is-invalid @enderror"
                                   id="service_retirement_dt" name="service_retirement_dt"
                                   value="{{ old('service_retirement_dt', $retirementDate) }}"
                                   placeholder="dd-mm-yyyy"
                                   inputmode="numeric" maxlength="10" autocomplete="off"
                                   aria-invalid="{{ $errors->has('service_retirement_dt') ? 'true' : 'false' }}"
                                   aria-describedby="serviceRetirementDtError">
                            <div id="serviceRetirementDtError" class="invalid-feedback @error('service_retirement_dt') d-block @enderror">@error('service_retirement_dt'){{ $message }}@enderror</div>
                        </div>

                        <div class="dbim-fa-field">
                            <label for="phone_no" class="dbim-fa-label">Office phone number</label>
                            <input type="tel" class="form-control @error('phone_no') is-invalid @enderror"
                                   id="phone_no" name="phone_no"
                                   value="{{ old('phone_no', $getUserDetails->phone_no ?? '') }}"
                                   placeholder="Enter office phone number"
                                   autocomplete="tel"
                                   aria-invalid="{{ $errors->has('phone_no') ? 'true' : 'false' }}"
                                   aria-describedby="phoneNoError">
                            <div id="phoneNoError" class="invalid-feedback @error('phone_no') d-block @enderror">@error('phone_no'){{ $message }}@enderror</div>
                        </div>

                        {{-- State: fixed to Goa, shown read only, value sent by the hidden field --}}
                        <div class="dbim-fa-field">
                            <label for="state_display" class="dbim-fa-label">State</label>
                            <select class="form-select @error('state') is-invalid @enderror"
                                    id="state_display" disabled aria-describedby="stateError">
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}" @selected($state->id == $goaId)>{{ $state->name }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="state" value="{{ $goaId }}">
                            <div id="stateError" class="invalid-feedback @error('state') d-block @enderror">@error('state'){{ $message }}@enderror</div>
                        </div>

                        <div class="dbim-fa-actions">
                            <button type="submit" id="submitData" name="submitData" class="dbim-fa-btn"
                                    title="Submit profile">Update profile</button>
                        </div>
                    </form>
                @endif

            </section>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        var summary     = document.getElementById('errorSummary');
        var summaryList = document.getElementById('errorSummaryList');

        function showError(f, message) {
            var input = document.getElementById(f.id);
            var box   = document.getElementById(f.errorId);
            input.classList.add('is-invalid');
            input.setAttribute('aria-invalid', 'true');
            box.textContent = message;
            box.classList.add('d-block');
        }

        function clearError(f) {
            var input = document.getElementById(f.id);
            var box   = document.getElementById(f.errorId);
            input.classList.remove('is-invalid');
            input.setAttribute('aria-invalid', 'false');
            box.textContent = '';
            box.classList.remove('d-block');
        }

        /* Validates the given fields, shows inline errors and the summary.
           Returns true when all are valid. */
        function validate(fields, keys) {
            var problems = [];
            keys.forEach(function (key) {
                var message = fields[key].check();
                if (message) { showError(fields[key], message); problems.push({ key: key, message: message }); }
                else { clearError(fields[key]); }
            });

            if (problems.length === 0) { summary.hidden = true; return true; }

            summaryList.textContent = '';
            problems.forEach(function (p) {
                var li = document.createElement('li');
                var a  = document.createElement('a');
                a.href = '#' + fields[p.key].id;
                a.textContent = p.message;
                a.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.getElementById(fields[p.key].id).focus();
                });
                li.appendChild(a);
                summaryList.appendChild(li);
            });
            summary.hidden = false;
            summary.focus();             /* announced by screen readers; keyboard users start here */
            return false;
        }

        function value(id) { return document.getElementById(id).value.trim(); }

        function mobileMessage() {
            var v = value('mobile_no');
            if (v === '') { return 'Enter your mobile number.'; }
            if (!/^[0-9]{10}$/.test(v)) { return 'Enter a 10-digit mobile number, using digits only.'; }
            return '';
        }

        function clearOnInput(fields) {
            Object.keys(fields).forEach(function (key) {
                document.getElementById(fields[key].id).addEventListener('input', function () {
                    if (!fields[key].check()) { clearError(fields[key]); }
                });
            });
        }

        /* ---------- Step 1: mobile + OTP ---------- */
        var otpForm = document.getElementById('sendOTPForm');
        if (otpForm) {
            var otpFields = {
                mobile_no: { id: 'mobile_no', errorId: 'mobileNoError', check: mobileMessage },
                otp: { id: 'otp', errorId: 'otpError', check: function () {
                    return value('otp') === '' ? 'Enter the OTP sent to your mobile number.' : '';
                } }
            };

            document.getElementById('sendOTP').addEventListener('click', function () {
                if (validate(otpFields, ['mobile_no'])) { otpForm.submit(); }
            });
            document.getElementById('verifyOtp').addEventListener('click', function () {
                if (validate(otpFields, ['mobile_no', 'otp'])) { otpForm.submit(); }
            });
            /* Enter key = verify */
            otpForm.addEventListener('submit', function (event) {
                event.preventDefault();
                if (validate(otpFields, ['mobile_no', 'otp'])) { otpForm.submit(); }
            });
            clearOnInput(otpFields);
        }

        /* ---------- Step 2: profile form ---------- */
        var profileForm = document.getElementById('profileUpdate');
        if (profileForm) {
            var profileFields = {
                officerName: { id: 'officerName', errorId: 'officerNameError', check: function () {
                    return value('officerName') === '' ? 'Enter the officer name.' : '';
                } },
                designation: { id: 'designation', errorId: 'designationError', check: function () {
                    return value('designation') === '' ? 'Enter the official designation.' : '';
                } },
                email_address: { id: 'email_address', errorId: 'emailAddressError', check: function () {
                    var input = document.getElementById('email_address');
                    if (input.value.trim() === '') { return 'Enter your email address.'; }
                    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value.trim())) {
                        return 'Enter a valid email address, for example name@example.com.';
                    }
                    return '';
                } },
                service_retirement_dt: { id: 'service_retirement_dt', errorId: 'serviceRetirementDtError', check: function () {
                    var v = value('service_retirement_dt');
                    if (v === '') { return ''; }                      /* optional */
                    var m = /^(\d{2})-(\d{2})-(\d{4})$/.exec(v);
                    if (!m) { return 'Enter the date as dd-mm-yyyy, for example 31-03-2035.'; }
                    var d = new Date(+m[3], +m[2] - 1, +m[1]);
                    if (d.getFullYear() !== +m[3] || d.getMonth() !== +m[2] - 1 || d.getDate() !== +m[1]) {
                        return 'Enter a valid date as dd-mm-yyyy.';
                    }
                    return '';
                } }
            };
            var profileKeys = Object.keys(profileFields);

            profileForm.addEventListener('submit', function (event) {
                if (!validate(profileFields, profileKeys)) { event.preventDefault(); }
            });
            clearOnInput(profileFields);
        }

        /* Errors sent back by the server: move focus to the summary */
        if (!summary.hidden) { summary.focus(); }
    })();
</script>
@endpush