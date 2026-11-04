{{-- ============================================================
     Officers Login  (DBIM / GIGW 3.0, WCAG 2.1 AA)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     No inline styles, no autofocus, no jQuery.
     Styles: sections 27 and 28 of public/css/dbim.css
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Officers Login | RTI Online, Government of Goa')

@section('content')
<div class="dbim-login">
    <section class="dbim-login__card" aria-labelledby="loginHeading">

        <div class="dbim-login__head">
            <h1 id="loginHeading" class="dbim-login__title">Officer's Login</h1>
        </div>

        <div class="dbim-login__body">

            {{-- Status message from the session (e.g. after password reset). Announced politely. --}}
            @if (session('status'))
                <div class="alert alert-success" role="status">
                    <strong>Success:</strong> {{ session('status') }}
                </div>
            @endif

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

            <form id="loginForm" method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                <p class="dbim-login__required">
                    Fields marked with
                    (<span class="dbim-required" aria-hidden="true">*</span><span class="dbim-sr-only">asterisk</span>)
                    are required.
                </p>

                {{-- Username --}}
                <div class="mb-3">
                    <label for="username" class="form-label fw-bold">
                        Username <span class="dbim-required" aria-hidden="true">*</span>
                    </label>
                    <input type="text"
                           class="form-control @error('username') is-invalid @enderror"
                           id="username" name="username" value="{{ old('username') }}"
                           placeholder="Enter your username"
                           autocomplete="username" required aria-required="true"
                           aria-invalid="{{ $errors->has('username') ? 'true' : 'false' }}"
                           aria-describedby="usernameError">
                    <div id="usernameError" class="invalid-feedback @error('username') d-block @enderror">@error('username'){{ $message }}@enderror</div>
                </div>

                {{-- System password (with show / hide button) --}}
                <div class="mb-3">
                    <label for="systemPass" class="form-label fw-bold">
                        Password <span class="dbim-required" aria-hidden="true">*</span>
                    </label>
                    <div class="input-group">
                        <input type="password"
                               class="form-control @error('systemPass') is-invalid @enderror"
                               id="systemPass" name="systemPass"
                               placeholder="Enter your system password"
                               autocomplete="off" required aria-required="true"
                               aria-invalid="{{ $errors->has('systemPass') ? 'true' : 'false' }}"
                               aria-describedby="systemPassError" value ="rTiGoA@#2005">
                        <button type="button" id="togglePassword" class="download-btn mt-0"
                                aria-controls="systemPass" aria-pressed="false"
                                aria-label="Show password" title="Show password">
                            <img id="eyeIcon" src="{{ asset('images/closeeye.svg') }}" alt="" width="24" height="24" data-a11y-keep>
                        </button>
                    </div>
                    <div id="systemPassError" class="invalid-feedback @error('systemPass') d-block @enderror">@error('systemPass'){{ $message }}@enderror</div>
                </div>

                {{-- Passcode --}}
                <div class="mb-3">
                    <label for="password" class="form-label fw-bold">
                        Passcode (Digit Code) <span class="dbim-required" aria-hidden="true">*</span>
                    </label>
                    <input type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           id="password" name="password"
                           placeholder="Enter your passcode"
                           inputmode="numeric" autocomplete="current-password"
                           required aria-required="true"
                           aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                           aria-describedby="passwordError">
                    <div id="passwordError" class="invalid-feedback @error('password') d-block @enderror">@error('password'){{ $message }}@enderror</div>
                </div>

                <x-captcha/>

                {{-- First submit button in the form: pressing Enter in a field logs in
                     instead of starting a password reset. Hidden from everyone; the
                     visible Login button below is the one people use. --}}
                <button type="submit" name="action" value="login" class="visually-hidden"
                        tabindex="-1" aria-hidden="true">Login</button>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <button type="submit" name="action" value="forgot_password" class="btn btn-link px-0" title="Forgot password">
                        Forgot Password
                    </button>
                    <button type="submit" name="action" value="login" class="download-btn text-uppercase w-ft" title="Login">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        var form        = document.getElementById('loginForm');
        var summary     = document.getElementById('errorSummary');
        var summaryList = document.getElementById('errorSummaryList');

        var fields = {
            username:   { id: 'username',   errorId: 'usernameError',   required: 'Enter your username.' },
            systemPass: { id: 'systemPass', errorId: 'systemPassError', required: 'Enter your system password.', onlyLogin: true },
            password:   { id: 'password',   errorId: 'passwordError',   required: 'Enter your passcode.',        onlyLogin: true }
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

        /* Forgot Password needs only the username; Login needs all three */
        function check(key, action) {
            var f = fields[key];
            if (f.onlyLogin && action !== 'login') { return ''; }
            return document.getElementById(f.id).value.trim() === '' ? f.required : '';
        }

        form.addEventListener('submit', function (event) {
            var action = (event.submitter && event.submitter.value) || 'login';
            var problems = [];

            Object.keys(fields).forEach(function (key) {
                var message = check(key, action);
                if (message) { showError(fields[key], message); problems.push({ key: key, message: message }); }
                else { clearError(fields[key]); }
            });

            if (problems.length === 0) { return; }   /* valid: normal submit, button value is sent */

            event.preventDefault();
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
            summary.focus();
        });

        /* Clear an error as soon as the user fixes the field */
        Object.keys(fields).forEach(function (key) {
            document.getElementById(fields[key].id).addEventListener('input', function () {
                if (!check(key, 'login')) { clearError(fields[key]); }
            });
        });

        /* Show / hide system password */
        var toggle = document.getElementById('togglePassword');
        var input  = document.getElementById('systemPass');
        var icon   = document.getElementById('eyeIcon');
        var eyeOpen   = "{{ asset('images/openeye.svg') }}";
        var eyeClosed = "{{ asset('images/closeeye.svg') }}";

        toggle.addEventListener('click', function () {
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            toggle.setAttribute('aria-pressed', show ? 'true' : 'false');
            var label = show ? 'Hide password' : 'Show password';
            toggle.setAttribute('aria-label', label);
            toggle.setAttribute('title', label);
            icon.src = show ? eyeOpen : eyeClosed;
        });

        /* Errors sent back by the server: move focus to the summary */
        if (!summary.hidden) { summary.focus(); }
    })();
</script>
@endpush