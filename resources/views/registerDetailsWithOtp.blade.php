{{-- ============================================================
     Applicant Login with OTP  (DBIM / GIGW 3.0, keyboard accessible)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     Hints are in the placeholders; messages only appear under a
     field when it has an error.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Applicant Login | RTI Online, Government of Goa')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-6">

            {{-- Messages from the server --}}
            @if(session('warning'))
                <div class="alert alert-warning text-center" role="alert">{{ session('warning') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger text-center" role="alert">{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="alert alert-success text-center" role="status">{{ session('success') }}</div>
            @endif

            <div class="card shadow-sm">
                <h1 class="card-header h4 mb-0">RTI Applicant Login</h1>

                <div class="card-body">

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

                    <form id="rtiRequestForm" method="post" enctype="multipart/form-data"
                          action="{{ route('sessionForm.save') }}" novalidate>
                        @csrf

                        <p class="text-end mb-4">Note: Field marked with (<span class="dbim-required">*</span>) are required.</p>

                        {{-- Name --}}
                        <div class="mb-3">
                            <label for="username" class="form-label fw-bold">
                                Name <span class="dbim-required" aria-hidden="true">*</span>
                            </label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                   id="username" name="username" value="{{ old('username') }}"
                                   placeholder="Enter your name (first name and last name)"
                                   autocomplete="name" required aria-required="true"
                                   aria-invalid="{{ $errors->has('username') ? 'true' : 'false' }}"
                                   aria-describedby="usernameError">
                            <div id="usernameError" class="invalid-feedback @error('username') d-block @enderror">@error('username'){{ $message }}@enderror</div>
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email_id" class="form-label fw-bold">
                                Email <span class="dbim-required" aria-hidden="true">*</span>
                            </label>
                            <input type="email" class="form-control @error('email_id') is-invalid @enderror"
                                   id="email_id" name="email_id" value="{{ old('email_id') }}"
                                   placeholder="Enter email, for example name@example.com"
                                   autocomplete="email" required aria-required="true"
                                   aria-invalid="{{ $errors->has('email_id') ? 'true' : 'false' }}"
                                   aria-describedby="emailIdError">
                            <div id="emailIdError" class="invalid-feedback @error('email_id') d-block @enderror">@error('email_id'){{ $message }}@enderror</div>
                        </div>

                        {{-- Mobile + Send OTP (button beside the field) --}}
                        <div class="mb-3">
                            <label for="mobile_no" class="form-label fw-bold">
                                Mobile <span class="dbim-required" aria-hidden="true">*</span>
                            </label>
                            <div class="input-group">
                                <input type="tel" class="form-control @error('mobile_no') is-invalid @enderror"
                                       id="mobile_no" name="mobile_no" value="{{ old('mobile_no') }}"
                                       placeholder="Enter 10 digit mobile"
                                       inputmode="numeric" maxlength="10" autocomplete="tel-national"
                                       required aria-required="true"
                                       aria-invalid="{{ $errors->has('mobile_no') ? 'true' : 'false' }}"
                                       aria-describedby="mobileNoError">
                                <button type="button" id="sendOTP" class="download-btn text-uppercase w-ft mt-0"
                                        title="Send OTP to this mobile number">
                                    Send OTP
                                </button>
                            </div>
                            <div id="mobileNoError" class="invalid-feedback @error('mobile_no') d-block @enderror">@error('mobile_no'){{ $message }}@enderror</div>
                        </div>

                        {{-- OTP --}}
                        <div class="mb-3">
                            <label for="otp" class="form-label fw-bold">
                                OTP <span class="dbim-required" aria-hidden="true">*</span>
                            </label>
                            <input type="text" class="form-control @error('otp') is-invalid @enderror"
                                   id="otp" name="otp"
                                   placeholder="Enter the OTP received on your mobile"
                                   inputmode="numeric" autocomplete="one-time-code" aria-required="true"
                                   aria-invalid="{{ $errors->has('otp') ? 'true' : 'false' }}"
                                   aria-describedby="otpError">
                            <div id="otpError" class="invalid-feedback @error('otp') d-block @enderror">@error('otp'){{ $message }}@enderror</div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" id="loginButton" class="download-btn text-uppercase w-ft" title="Log in">
                                Log In
                            </button>
                        </div>
                    </form>
                </div>

                <div class="card-footer bg-transparent">
                    <strong>Refer this User Manual if required:</strong>
                    <a href="../Applicant_Module_User_Manual.pdf" download>User Manual (PDF)</a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        var form        = document.getElementById('rtiRequestForm');
        var summary     = document.getElementById('errorSummary');
        var summaryList = document.getElementById('errorSummaryList');
        var sendBtn     = document.getElementById('sendOTP');

        var fields = {
            username:  { id: 'username',  errorId: 'usernameError' },
            email_id:  { id: 'email_id',  errorId: 'emailIdError' },
            mobile_no: { id: 'mobile_no', errorId: 'mobileNoError' },
            otp:       { id: 'otp',       errorId: 'otpError' }
        };

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

        /* Message for one field, or '' when valid. mode: 'otp' (send OTP) or 'login' */
        function check(key, mode) {
            var input = document.getElementById(fields[key].id);
            var v = input.value.trim();
            if (key === 'username' && v === '') { return 'Enter your name.'; }
            if (key === 'email_id') {
                if (v === '') { return 'Enter your email address.'; }
                if (!input.validity.valid) { return 'Enter a valid email address, for example name@example.com.'; }
            }
            if (key === 'mobile_no') {
                if (v === '') { return 'Enter your mobile number.'; }
                if (!/^[0-9]{10}$/.test(v)) { return 'Enter a 10-digit mobile number, using digits only.'; }
            }
            if (key === 'otp' && mode === 'login' && v === '') {
                return 'Enter the OTP sent to your mobile number.';
            }
            return '';
        }

        /* Validates, shows inline errors and the summary. Returns true when valid. */
        function validate(mode) {
            var problems = [];
            Object.keys(fields).forEach(function (key) {
                var message = check(key, mode);
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

        /* Log In (button or Enter key) */
        form.addEventListener('submit', function (event) {
            if (!validate('login')) { event.preventDefault(); }
        });

        /* Send OTP: same form, flagged with sendOtp=true (as before) */
        sendBtn.addEventListener('click', function () {
            if (!validate('otp')) { return; }
            sendBtn.disabled = true;
            var flag = document.createElement('input');
            flag.type  = 'hidden';
            flag.name  = 'sendOtp';
            flag.value = 'true';
            form.appendChild(flag);
            form.submit();
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