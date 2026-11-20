{{-- ============================================================
     Officers Login  (DBIM / GIGW 3.0, WCAG 2.1 AA)
     Page banner (breadcrumb + title) on top, ONE white card overlapping
     its bottom edge. Same look as the Applicant Login page.
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     No inline styles, no autofocus, no jQuery.
     Styles: section 50 (frame, label, button) + section 52 (login form)
             + section 60 (page banner) in public/css/dbim.css
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Officers Login | RTI Online, Government of Goa')

@section('content')
<div class="dbim-sd">

    {{-- Page banner: breadcrumb + the only <h1> of the page --}}
    <div class="dbim-pb">
        <div class="dbim-pb-wrap">
            <nav aria-label="Breadcrumb">
                <ol class="dbim-pb-crumb">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li aria-current="page">Officer's Login</li>
                </ol>
            </nav>
            <h1 id="loginHeading" class="dbim-pb-title">Officer's Login</h1>
        </div>
    </div>

    <section class="dbim-sd-section" aria-labelledby="loginHeading">
        <div class="dbim-sd-wrap">
            <div class="dbim-sd-card dbim-lo-card">

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

                <form id="loginForm" class="dbim-lo-form" method="POST" action="{{ route('login') }}" novalidate>
                    @csrf

                    <p class="dbim-lo-required">
                        Fields marked with
                        (<span class="dbim-required" aria-hidden="true">*</span><span class="dbim-sr-only">asterisk</span>)
                        are required.
                    </p>

                    {{-- Username --}}
                    <div class="dbim-lo-field">
                        <label for="username" class="dbim-sd-label">
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
                    <div class="dbim-lo-field">
                        <label for="systemPass" class="dbim-sd-label">
                            Password <span class="dbim-required" aria-hidden="true">*</span>
                        </label>
                        <div class="input-group">
                            <input type="password"
                                   class="form-control @error('systemPass') is-invalid @enderror"
                                   id="systemPass" name="systemPass"
                                   placeholder="Enter your system password"
                                   autocomplete="off" required aria-required="true"
                                   aria-invalid="{{ $errors->has('systemPass') ? 'true' : 'false' }}"
                                   aria-describedby="systemPassError" value="rTiGoA@#2005">
                            <button type="button" id="togglePassword" class="dbim-lo-toggle"
                                    aria-controls="systemPass" aria-pressed="false"
                                    aria-label="Show password" title="Show password">
                                <img id="eyeIcon" src="{{ asset('images/closeeye.svg') }}" alt="" width="24" height="24" data-a11y-keep>
                            </button>
                        </div>
                        <div id="systemPassError" class="invalid-feedback @error('systemPass') d-block @enderror">@error('systemPass'){{ $message }}@enderror</div>
                    </div>

                    {{-- Passcode --}}
                    <div class="dbim-lo-field">
                        <label for="password" class="dbim-sd-label">
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

                    <div class="dbim-lo-actions">
                        <button type="submit" name="action" value="forgot_password" class="dbim-lo-link" title="Forgot password">
                            Forgot Password
                        </button>
                        <button type="submit" name="action" value="login" class="dbim-sd-btn" title="Login">
                            Login
                        </button>
                    </div>
                </form>

            </div>
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