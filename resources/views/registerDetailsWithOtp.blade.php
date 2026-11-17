{{-- ============================================================
     Applicant Login with OTP  (DBIM / GIGW 3.0, WCAG 2.1 AA)
     Same look as Officers Login, Search and Contact: grey band outside,
     ONE white card inside, main heading with the blue bar.
     Styles: section 50 (frame) + 52 (login form) + 53 (OTP parts) in public/css/dbim.css
     No inline styles, no autofocus, no jQuery. Works with JS off.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Login | RTI Online, Department of Information & Publicity')

@section('content')
@php
    $manual     = '../Applicant_Module_User_Manual.pdf';
    $privacy    = '#';          // route('privacy')
    $otpMinutes = null;         // e.g. 5. null = sentence hidden
    $timedOut   = request()->query('reason') === 'timeout';
    // server error key => field id (unknown keys link to the first field)
    $fieldIds = ['username' => 'username', 'email_id' => 'email_id',
                 'mobile_no' => 'mobile_no', 'otp' => 'otp', 'captcha' => 'captcha'];
@endphp

<div class="dbim-sd">
    <div class="dbim-sd">
    <div class="dbim-pb">
        <div class="dbim-pb-wrap">
            <nav aria-label="Breadcrumb">
                <ol class="dbim-pb-crumb">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li aria-current="page">Applicant Login</li>
                </ol>
            </nav>
            <h1 id="loginHeading" class="dbim-pb-title">Applicant Login</h1>
        </div>
    </div>

    <section class="dbim-sd-section" aria-labelledby="loginHeading">
        <div class="dbim-sd-wrap">
            <div class="dbim-sd-card dbim-lo-card">

                {{-- Status messages from the server --}}
                @if($timedOut)
                    <div class="alert alert-info" role="status">
                        <strong>Note:</strong> Your session has expired. Please log in again.
                    </div>
                @endif
                @if(session('warning'))
                    <div class="alert alert-warning" role="alert"><strong>Warning:</strong> {{ session('warning') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger" role="alert"><strong>Error:</strong> {{ session('error') }}</div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success" role="status"><strong>Success:</strong> {{ session('success') }}</div>
                @endif

                {{-- Error summary: filled by script or by the server; receives focus --}}
                <div id="errorSummary" class="alert alert-danger" role="alert" tabindex="-1"
                     @if(!$errors->any()) hidden @endif>
                    <p class="fw-bold mb-2">Please correct the following:</p>
                    <ul class="mb-0" id="errorSummaryList">
                        @foreach($errors->messages() as $key => $messages)
                            @foreach($messages as $message)
                                <li><a href="#{{ $fieldIds[$key] ?? 'username' }}">{{ $message }}</a></li>
                            @endforeach
                        @endforeach
                    </ul>
                </div>

                <form id="rtiRequestForm" class="dbim-lo-form" method="POST" action="{{ route('sessionForm.save') }}" novalidate>
                    @csrf

                    <p class="dbim-lo-required">
                        Fields marked with
                        (<span class="dbim-required" aria-hidden="true">*</span><span class="dbim-sr-only">asterisk</span>)
                        are required.
                    </p>

                    {{-- Name --}}
                    <div class="dbim-lo-field">
                        <label for="username" class="dbim-sd-label">
                            Name <span class="dbim-required" aria-hidden="true">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('username') is-invalid @enderror"
                               id="username" name="username" value="{{ old('username') }}"
                               placeholder="Enter your first and last name"
                               autocomplete="name" required aria-required="true"
                               aria-invalid="{{ $errors->has('username') ? 'true' : 'false' }}"
                               aria-describedby="usernameError">
                        <div id="usernameError" class="invalid-feedback @error('username') d-block @enderror">@error('username'){{ $message }}@enderror</div>
                    </div>

                    {{-- Email --}}
                    <div class="dbim-lo-field">
                        <label for="email_id" class="dbim-sd-label">
                            Email <span class="dbim-required" aria-hidden="true">*</span>
                        </label>
                        <input type="email"
                               class="form-control @error('email_id') is-invalid @enderror"
                               id="email_id" name="email_id" value="{{ old('email_id') }}"
                               placeholder="name@example.com"
                               autocomplete="email" required aria-required="true"
                               aria-invalid="{{ $errors->has('email_id') ? 'true' : 'false' }}"
                               aria-describedby="emailIdError">
                        <div id="emailIdError" class="invalid-feedback @error('email_id') d-block @enderror">@error('email_id'){{ $message }}@enderror</div>
                    </div>

                    {{-- Mobile number + Send OTP --}}
                    <div class="dbim-lo-field">
                        <label for="mobile_no" class="dbim-sd-label">
                            Mobile Number <span class="dbim-required" aria-hidden="true">*</span>
                        </label>
                        <div class="input-group">
                            <input type="tel"
                                   class="form-control @error('mobile_no') is-invalid @enderror"
                                   id="mobile_no" name="mobile_no" value="{{ old('mobile_no') }}"
                                   placeholder="10-digit mobile number"
                                   inputmode="numeric" maxlength="10" autocomplete="tel-national"
                                   required aria-required="true"
                                   aria-invalid="{{ $errors->has('mobile_no') ? 'true' : 'false' }}"
                                   aria-describedby="mobileNoHint mobileNoError">
                            <button type="button" id="sendOTP" class="dbim-lo-toggle dbim-lo-send">
                                Send OTP<span class="dbim-sr-only"> to this mobile number</span>
                            </button>
                        </div>
                        <div id="mobileNoHint" class="form-text">Enter 10 digits without +91 or 0. The OTP is sent by SMS.</div>
                        <div id="mobileNoError" class="invalid-feedback @error('mobile_no') d-block @enderror">@error('mobile_no'){{ $message }}@enderror</div>
                    </div>

                    {{-- OTP (never restored) --}}
                    <div class="dbim-lo-field">
                        <label for="otp" class="dbim-sd-label">
                            OTP <span class="dbim-required" aria-hidden="true">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('otp') is-invalid @enderror"
                               id="otp" name="otp"
                               placeholder="Enter the OTP"
                               inputmode="numeric" autocomplete="one-time-code" aria-required="true"
                               aria-invalid="{{ $errors->has('otp') ? 'true' : 'false' }}"
                               aria-describedby="otpHint otpError">
                        <div id="otpHint" class="form-text">Enter the OTP received on your mobile number.@if($otpMinutes) It is valid for {{ $otpMinutes }} minutes.@endif</div>
                        <div id="otpError" class="invalid-feedback @error('otp') d-block @enderror">@error('otp'){{ $message }}@enderror</div>
                    </div>

                    <x-captcha/>

                    <div class="dbim-lo-actions dbim-lo-actions--end">
                        <button type="submit" id="loginButton" class="dbim-sd-btn" title="Login">
                            Login
                        </button>
                    </div>
                    <span class="dbim-sr-only" id="lgBusy" role="status"></span>

                    <p class="dbim-lo-note">
                        Your details are used only to verify your identity and file your requests, as described in the
                        <a href="{{ $privacy }}">Privacy Policy</a>.
                    </p>
                </form>

                <hr class="dbim-sd-divider">

                <p class="dbim-lo-note dbim-lo-note--last">
                    Refer to the user manual if required:
                    <a href="{{ $manual }}" download>User Manual (PDF)</a>
                </p>

            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    var form     = document.getElementById('rtiRequestForm');
    var summary  = document.getElementById('errorSummary');
    var list     = document.getElementById('errorSummaryList');
    var sendBtn  = document.getElementById('sendOTP');
    var loginBtn = document.getElementById('loginButton');
    var busy     = document.getElementById('lgBusy');
    if (!form) { return; }

    var fields = {
        username:  { id: 'username',  errorId: 'usernameError' },
        email_id:  { id: 'email_id',  errorId: 'emailIdError' },
        mobile_no: { id: 'mobile_no', errorId: 'mobileNoError' },
        otp:       { id: 'otp',       errorId: 'otpError' }
    };

    function showError(f, message) {
        var input = document.getElementById(f.id), box = document.getElementById(f.errorId);
        input.classList.add('is-invalid');
        input.setAttribute('aria-invalid', 'true');
        box.textContent = message;
        box.classList.add('d-block');
    }
    function clearError(f) {
        var input = document.getElementById(f.id), box = document.getElementById(f.errorId);
        input.classList.remove('is-invalid');
        input.setAttribute('aria-invalid', 'false');
        box.textContent = '';
        box.classList.remove('d-block');
    }

    /* Message for one field, or '' when valid. mode: 'otp' (Send OTP) or 'login' */
    function check(key, mode) {
        var input = document.getElementById(fields[key].id), v = input.value.trim();
        if (key === 'username' && v === '') { return 'Enter your name.'; }
        if (key === 'email_id') {
            if (v === '') { return 'Enter your email address.'; }
            if (!input.validity.valid) { return 'Enter a valid email address, for example name@example.com.'; }
        }
        if (key === 'mobile_no') {
            if (v === '') { return 'Enter your mobile number.'; }
            if (!/^[0-9]{10}$/.test(v)) { return 'Enter a 10-digit mobile number, using digits only.'; }
        }
        if (key === 'otp' && mode === 'login' && v === '') { return 'Enter the OTP sent to your mobile number.'; }
        return '';
    }

    /* Inline errors + summary with links to fields. Returns true when valid. */
    function validate(mode) {
        var problems = [];
        Object.keys(fields).forEach(function (key) {
            var m = check(key, mode);
            if (m) { showError(fields[key], m); problems.push({ key: key, message: m }); }
            else { clearError(fields[key]); }
        });
        if (!problems.length) { summary.hidden = true; return true; }

        list.textContent = '';
        problems.forEach(function (p) {
            var li = document.createElement('li'), a = document.createElement('a');
            a.href = '#' + fields[p.key].id;
            a.textContent = p.message;
            a.addEventListener('click', function (e) {
                e.preventDefault();
                document.getElementById(fields[p.key].id).focus();
            });
            li.appendChild(a);
            list.appendChild(li);
        });
        summary.hidden = false;
        summary.focus();
        return false;
    }

    /* Login (button or Enter) */
    form.addEventListener('submit', function (e) {
        if (!validate('login')) { e.preventDefault(); return; }
        lock('Logging in…');
    });

    /* Send OTP: same form, flagged sendOtp=true */
    sendBtn.addEventListener('click', function () {
        if (!validate('otp')) { return; }
        var flag = document.createElement('input');
        flag.type = 'hidden'; flag.name = 'sendOtp'; flag.value = 'true';
        form.appendChild(flag);
        lock('Sending OTP…');
        form.submit();
    });

    /* Disable both buttons only once a request is really being sent */
    function lock(text) {
        setTimeout(function () {
            loginBtn.disabled = true;
            sendBtn.disabled = true;
            if (busy) { busy.textContent = text + ' Please wait.'; }
        }, 0);
    }
    /* Back button (bfcache) must not leave the buttons disabled */
    window.addEventListener('pageshow', function () {
        loginBtn.disabled = false;
        sendBtn.disabled = false;
        if (busy) { busy.textContent = ''; }
    });

    /* Clear an error as soon as the user fixes the field */
    Object.keys(fields).forEach(function (key) {
        document.getElementById(fields[key].id).addEventListener('input', function () {
            if (!check(key, 'login')) { clearError(fields[key]); }
        });
    });

    /* Errors sent back by the server: move focus to the summary */
    if (!summary.hidden) { summary.focus(); }
})();
</script>
@endpush