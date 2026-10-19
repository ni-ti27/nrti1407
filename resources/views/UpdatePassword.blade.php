{{-- ============================================================
     Change Password  (DBIM / GIGW 3.0, keyboard accessible)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     Hints are in the placeholders; messages only appear under a
     field when it has an error.
     No inline styles, no inline event handlers.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Change Password | RTI Online, Government of Goa')

@section('content')
@php
    // "Continue" goes to the dashboard of the officer's role
    $continueUrl = match ((int) session('role')) {
        4       => '/RTIMIS/dashboard/{new_FA}',
        5, 6, 7 => '/RTIMIS/dashboard/{new_SA}',
        default => '/RTIMIS/dashboard/',
    };
@endphp

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-6">

            {{-- Messages from the server --}}
            @include('partials.flash-messages')

            <div class="card shadow-sm">
                <h1 class="card-header h4 mb-0">Change Password</h1>

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

                    <form id="changePasswordForm" method="POST" action="{{ route('changePassword') }}" novalidate>
                        @csrf

                        <div class="alert alert-info dbim-note" role="note">
                            <p>
                                <strong>Note:</strong> Fields marked with (<span class="dbim-required" aria-hidden="true">*</span>) are required.
                            </p>
                        </div>

                        {{-- Username (read only) --}}
                        <div class="mb-3">
                            <label for="username" class="form-label fw-bold">Username</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                   id="username" name="username"
                                   value="{{ old('username') ? old('username') : session('username') }}"
                                   autocomplete="username" readonly
                                   aria-invalid="{{ $errors->has('username') ? 'true' : 'false' }}"
                                   aria-describedby="usernameError">
                            <div id="usernameError" class="invalid-feedback @error('username') d-block @enderror">@error('username'){{ $message }}@enderror</div>
                        </div>

                        {{-- Previous password --}}
                        <div class="mb-3">
                            <label for="oldPass" class="form-label fw-bold">
                                Previous Password <span class="dbim-required" aria-hidden="true">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('oldPass') is-invalid @enderror"
                                       id="oldPass" name="oldPass"
                                       placeholder="Enter your previous password"
                                       autocomplete="current-password" required aria-required="true"
                                       aria-invalid="{{ $errors->has('oldPass') ? 'true' : 'false' }}"
                                       aria-describedby="oldPassError">
                                <button type="button" class="download-outline-btn mt-0" data-toggle-password="oldPass"
                                        aria-label="Show previous password" aria-pressed="false">
                                    <img src="{{ asset('/images/closeeye.svg') }}" alt="" width="24" height="24">
                                </button>
                            </div>
                            <div id="oldPassError" class="invalid-feedback @error('oldPass') d-block @enderror">@error('oldPass'){{ $message }}@enderror</div>
                        </div>

                        {{-- New password --}}
                        <div class="mb-3">
                            <label for="newPassword1" class="form-label fw-bold">
                                New Password <span class="dbim-required" aria-hidden="true">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('newPassword1') is-invalid @enderror"
                                       id="newPassword1" name="newPassword1"
                                       placeholder="Enter 8 to 20 characters, as per the rules below"
                                       autocomplete="new-password" minlength="8" maxlength="20"
                                       required aria-required="true"
                                       aria-invalid="{{ $errors->has('newPassword1') ? 'true' : 'false' }}"
                                       aria-describedby="passwordRules newPassword1Error">
                                <button type="button" class="download-outline-btn mt-0" data-toggle-password="newPassword1"
                                        aria-label="Show new password" aria-pressed="false">
                                    <img src="{{ asset('/images/closeeye.svg') }}" alt="" width="24" height="24">
                                </button>
                            </div>
                            <div id="newPassword1Error" class="invalid-feedback @error('newPassword1') d-block @enderror">@error('newPassword1'){{ $message }}@enderror</div>
                        </div>

                        {{-- Confirm password --}}
                        <div class="mb-3">
                            <label for="newPassword2" class="form-label fw-bold">
                                Confirm Password <span class="dbim-required" aria-hidden="true">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('newPassword2') is-invalid @enderror"
                                       id="newPassword2" name="newPassword2"
                                       placeholder="Enter the new password again"
                                       autocomplete="new-password" minlength="8" maxlength="20"
                                       required aria-required="true"
                                       aria-invalid="{{ $errors->has('newPassword2') ? 'true' : 'false' }}"
                                       aria-describedby="newPassword2Error">
                                <button type="button" class="download-outline-btn mt-0" data-toggle-password="newPassword2"
                                        aria-label="Show confirm password" aria-pressed="false">
                                    <img src="{{ asset('/images/closeeye.svg') }}" alt="" width="24" height="24">
                                </button>
                            </div>
                            <div id="newPassword2Error" class="invalid-feedback @error('newPassword2') d-block @enderror">@error('newPassword2'){{ $message }}@enderror</div>
                        </div>

                        {{-- Rules --}}
                        <div id="passwordRules" class="mb-4">
                            <h2 class="h6 fw-bold">Rules for creating a new password</h2>
                            <ul class="mb-0">
                                <li>Should contain minimum 8 characters</li>
                                <li>Should contain maximum 20 characters</li>
                                <li>Should contain minimum 1 upper case character</li>
                                <li>Should contain minimum 1 lower case character</li>
                                <li>Should contain 1 special character</li>
                                <li>Should contain minimum 1 number</li>
                                <li>Should not contain consecutive numbers</li>
                            </ul>
                        </div>

                        <div class="d-flex flex-wrap justify-content-end gap-2">
                            <a href="{{ $continueUrl }}" class="download-outline-btn text-uppercase w-ft">Continue</a>
                            <button type="submit" id="validateDetails" class="download-btn text-uppercase w-ft" title="Change password">
                                Change
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        var form        = document.getElementById('changePasswordForm');
        var summary     = document.getElementById('errorSummary');
        var summaryList = document.getElementById('errorSummaryList');

        var iconOpen  = @json(asset('/images/openeye.svg'));
        var iconClose = @json(asset('/images/closeeye.svg'));

        var fields = {
            oldPass:      { id: 'oldPass',      errorId: 'oldPassError' },
            newPassword1: { id: 'newPassword1', errorId: 'newPassword1Error' },
            newPassword2: { id: 'newPassword2', errorId: 'newPassword2Error' }
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

        /* Message for one field, or '' when valid */
        function check(key) {
            var v = document.getElementById(fields[key].id).value;

            if (key === 'oldPass' && v === '') { return 'Enter your previous password.'; }

            if (key === 'newPassword1') {
                if (v === '')                          { return 'Enter the new password.'; }
                if (v.length < 8 || v.length > 20)     { return 'Password must be between 8 and 20 characters.'; }
                if (!/[A-Z]/.test(v))                  { return 'Password must contain at least one uppercase letter.'; }
                if (!/[a-z]/.test(v))                  { return 'Password must contain at least one lowercase letter.'; }
                if (!/[0-9]/.test(v))                  { return 'Password must contain at least one number.'; }
                if (!/[!@#$&().?:{}|<>]/.test(v))      { return 'Password must contain at least one special character.'; }
                if (/(012|123|234|345|456|567|678|789)/.test(v)) { return 'Password cannot contain consecutive numbers.'; }
            }

            if (key === 'newPassword2') {
                if (v === '') { return 'Enter the new password again.'; }
                if (v !== document.getElementById('newPassword1').value) {
                    return 'Confirm password does not match the new password.';
                }
            }
            return '';
        }

        /* Validates, shows inline errors and the summary. Returns true when valid. */
        function validate() {
            var problems = [];
            Object.keys(fields).forEach(function (key) {
                var message = check(key);
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

        form.addEventListener('submit', function (event) {
            if (!validate()) { event.preventDefault(); }
        });

        /* Clear an error as soon as the user fixes the field */
        Object.keys(fields).forEach(function (key) {
            document.getElementById(fields[key].id).addEventListener('input', function () {
                if (!check(key)) { clearError(fields[key]); }
            });
        });

        /* Show / hide password: one handler for all three buttons */
        document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
            var input = document.getElementById(btn.getAttribute('data-toggle-password'));
            var img   = btn.querySelector('img');
            var label = btn.getAttribute('aria-label').replace(/^Show /, '');

            btn.addEventListener('click', function () {
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                img.src = show ? iconOpen : iconClose;
                btn.setAttribute('aria-pressed', show ? 'true' : 'false');
                btn.setAttribute('aria-label', (show ? 'Hide ' : 'Show ') + label);
            });
        });

        /* Errors sent back by the server: move focus to the summary */
        if (!summary.hidden) { summary.focus(); }
    })();
</script>
@endpush