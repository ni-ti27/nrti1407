{{-- ============================================================
     Applicant Login  (DBIM / GIGW 3.0, keyboard accessible)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Applicant Login | RTI Online, Government of Goa')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">

            <div class="card">
                <h1 class="card-header h4 mb-0">RTI Applicant Login</h1>

                <div class="card-body">

                    {{-- Error summary: shown by script or by the server; receives focus --}}
                    <div id="errorSummary" class="alert alert-danger" role="alert" tabindex="-1"
                         @if(!$errors->any()) hidden @endif>
                        <p class="fw-bold mb-2" id="errorSummaryTitle">Please correct the following:</p>
                        <ul class="mb-0" id="errorSummaryList">
                            @foreach($errors->all() as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <form id="rtiRequestForm" method="post" enctype="multipart/form-data"
                          action="{{ route('sessionForm.save') }}" novalidate>
                        @csrf

                        <p class="form-text mb-4">
                            Fields marked <span class="dbim-required" aria-hidden="true">*</span>
                            <span class="visually-hidden">with an asterisk</span> are mandatory.
                        </p>

                        <div class="row justify-content-center mb-3">
                            <div class="col-md-6">
                                <label for="username" class="form-label">
                                    Name <span class="dbim-required" aria-hidden="true">*</span>
                                </label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror"
                                       id="username" name="username" value="{{ old('username') }}"
                                       autocomplete="name" required aria-required="true"
                                       aria-invalid="{{ $errors->has('username') ? 'true' : 'false' }}"
                                       aria-describedby="usernameHelp usernameError">
                                <div id="usernameHelp" class="form-text">Enter your first name and last name.</div>
                                <div id="usernameError" class="invalid-feedback">@error('username'){{ $message }}@enderror</div>
                            </div>
                        </div>

                        <div class="row justify-content-center mb-3">
                            <div class="col-md-6">
                                <label for="mobile_no" class="form-label">
                                    Mobile Number <span class="dbim-required" aria-hidden="true">*</span>
                                </label>
                                <input type="tel" class="form-control @error('mobile_no') is-invalid @enderror"
                                       id="mobile_no" name="mobile_no" value="{{ old('mobile_no') }}"
                                       inputmode="tel" autocomplete="tel" required aria-required="true"
                                       aria-invalid="{{ $errors->has('mobile_no') ? 'true' : 'false' }}"
                                       aria-describedby="mobileNoHelp mobileNoError">
                                <div id="mobileNoHelp" class="form-text">Enter your mobile number.</div>
                                <div id="mobileNoError" class="invalid-feedback">@error('mobile_no'){{ $message }}@enderror</div>
                            </div>
                        </div>

                        <div class="row justify-content-center mb-3">
                            <div class="col-md-6">
                                <label for="email_id" class="form-label">
                                    Email Id <span class="dbim-required" aria-hidden="true">*</span>
                                </label>
                                <input type="email" class="form-control @error('email_id') is-invalid @enderror"
                                       id="email_id" name="email_id" value="{{ old('email_id') }}"
                                       autocomplete="email" required aria-required="true"
                                       aria-invalid="{{ $errors->has('email_id') ? 'true' : 'false' }}"
                                       aria-describedby="emailIdHelp emailIdError">
                                <div id="emailIdHelp" class="form-text">For example: name@example.com</div>
                                <div id="emailIdError" class="invalid-feedback">@error('email_id'){{ $message }}@enderror</div>
                            </div>
                        </div>

                        <div class="row justify-content-center mt-4">
                            <div class="col-md-6 d-flex justify-content-end">
                                <button type="submit" class="download-btn text-uppercase w-ft" title="Submit">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-footer bg-transparent">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <strong>Refer this User Manual if required:</strong>
                            <a href="../Applicant_Module_User_Manual.pdf" download>User Manual (PDF)</a>
                        </div>
                    </div>
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

        var fields = [
            { id: 'username',  errorId: 'usernameError', required: 'Enter your name.' },
            { id: 'mobile_no', errorId: 'mobileNoError', required: 'Enter your mobile number.' },
            { id: 'email_id',  errorId: 'emailIdError',  required: 'Enter your email address.',
              invalid: 'Enter a valid email address, for example name@example.com.' }
        ];

        function showError(f, message) {
            var input = document.getElementById(f.id);
            input.classList.add('is-invalid');
            input.setAttribute('aria-invalid', 'true');
            document.getElementById(f.errorId).textContent = message;
        }

        function clearError(f) {
            var input = document.getElementById(f.id);
            input.classList.remove('is-invalid');
            input.setAttribute('aria-invalid', 'false');
            document.getElementById(f.errorId).textContent = '';
        }

        /* Returns an error message, or '' when the field is valid */
        function check(f) {
            var input = document.getElementById(f.id);
            if (input.value.trim() === '') { return f.required; }
            if (f.invalid && !input.validity.valid) { return f.invalid; }
            return '';
        }

        form.addEventListener('submit', function (event) {
            var problems = [];
            fields.forEach(function (f) {
                var message = check(f);
                if (message) { showError(f, message); problems.push({ field: f, message: message }); }
                else { clearError(f); }
            });

            if (problems.length === 0) { return; }   /* valid: let the form submit */

            event.preventDefault();
            summaryList.textContent = '';
            problems.forEach(function (p) {
                var li = document.createElement('li');
                var a  = document.createElement('a');
                a.href = '#' + p.field.id;
                a.textContent = p.message;
                a.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.getElementById(p.field.id).focus();
                });
                li.appendChild(a);
                summaryList.appendChild(li);
            });
            summary.hidden = false;
            summary.focus();            /* screen readers announce it; keyboard users start here */
        });

        /* Remove an error as soon as the user fixes the field */
        fields.forEach(function (f) {
            document.getElementById(f.id).addEventListener('input', function () {
                if (!check(f)) { clearError(f); }
            });
        });

        /* Errors sent back by the server: move focus to the summary */
        if (!summary.hidden) { summary.focus(); }
    })();
</script>
@endpush