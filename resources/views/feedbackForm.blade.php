{{-- ============================================================
     Feedback Form  (DBIM / GIGW 3.0, keyboard accessible)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     Styles: .dbim-form-card (section 16). No inline styles or jQuery.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Feedback Form | RTI Online, Government of Goa')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-6">

            {{-- Success message (stays visible, no auto-hide) --}}
            @if(session('success'))
                <div class="alert alert-success" role="status">{{ session('success') }}</div>
            @endif

            <div class="card dbim-form-card">
                <h1 class="card-header h4 mb-0">Feedback Form</h1>

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

                    <div class="alert alert-info dbim-note" role="note">
                        <p>
                            <strong>Note:</strong> Your feedback helps us improve this portal.
                            Please do not enter sensitive personal details in the message.
                        </p>
                        <p>
                            Fields marked with (<span class="dbim-required" aria-hidden="true">*</span>) are required.
                        </p>
                    </div>

                    <form id="feedbackForm" method="post" action="{{ route('feedback.submit') }}" novalidate>
                        @csrf

                        {{-- Name --}}
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">
                                Name <span class="dbim-required" aria-hidden="true">*</span>
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}"
                                   placeholder="Enter your full name"
                                   autocomplete="name" required aria-required="true"
                                   aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
                                   aria-describedby="nameError">
                            <div id="nameError" class="invalid-feedback @error('name') d-block @enderror">@error('name'){{ $message }}@enderror</div>
                        </div>

                        {{-- Mobile --}}
                        <div class="mb-3">
                            <label for="mobile" class="form-label fw-bold">
                                Mobile Number <span class="dbim-required" aria-hidden="true">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text" aria-hidden="true">+91</span>
                                <input type="text" class="form-control @error('mobile') is-invalid @enderror"
                                       id="mobile" name="mobile" value="{{ old('mobile') }}"
                                       placeholder="Enter 10-digit mobile number"
                                       inputmode="numeric" maxlength="10" pattern="[0-9]{10}"
                                       autocomplete="tel-national" required aria-required="true"
                                       aria-invalid="{{ $errors->has('mobile') ? 'true' : 'false' }}"
                                       aria-describedby="mobileHint mobileError">
                            </div>
                            <div id="mobileHint" class="dbim-sr-only">Country code +91. Enter 10 digits, numbers only.</div>
                            <div id="mobileError" class="invalid-feedback @error('mobile') d-block @enderror">@error('mobile'){{ $message }}@enderror</div>
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">
                                Email <span class="dbim-required" aria-hidden="true">*</span>
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email') }}"
                                   placeholder="Enter your email ID, for example name@example.com"
                                   autocomplete="email" required aria-required="true"
                                   aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                                   aria-describedby="emailError">
                            <div id="emailError" class="invalid-feedback @error('email') d-block @enderror">@error('email'){{ $message }}@enderror</div>
                        </div>

                        {{-- Message --}}
                        <div class="mb-3">
                            <label for="message" class="form-label fw-bold">
                                Your Feedback <span class="dbim-required" aria-hidden="true">*</span>
                            </label>
                            <textarea class="form-control @error('message') is-invalid @enderror"
                                      id="message" name="message" rows="5"
                                      placeholder="Enter your feedback"
                                      required aria-required="true"
                                      aria-invalid="{{ $errors->has('message') ? 'true' : 'false' }}"
                                      aria-describedby="messageError">{{ old('message') }}</textarea>
                            <div id="messageError" class="invalid-feedback @error('message') d-block @enderror">@error('message'){{ $message }}@enderror</div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="reset" class="download-outline-btn text-uppercase w-ft" title="Reset">
                                Reset
                            </button>
                            <button type="submit" class="download-btn text-uppercase w-ft" title="Send Feedback">
                                Send Feedback
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
        var form        = document.getElementById('feedbackForm');
        var summary     = document.getElementById('errorSummary');
        var summaryList = document.getElementById('errorSummaryList');

        var fields = {
            name:    { id: 'name',    errorId: 'nameError',    required: 'Enter your name.' },
            mobile:  { id: 'mobile',  errorId: 'mobileError',  required: 'Enter your mobile number.',
                       invalid: 'Enter a valid 10-digit mobile number, numbers only.',
                       test: function (v) { return /^[0-9]{10}$/.test(v); } },
            email:   { id: 'email',   errorId: 'emailError',   required: 'Enter your email address.',
                       invalid: 'Enter a valid email address, for example name@example.com.' },
            message: { id: 'message', errorId: 'messageError', required: 'Enter your feedback.' }
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

        function check(f) {
            var input = document.getElementById(f.id);
            var value = input.value.trim();
            if (value === '') { return f.required; }
            if (f.test && !f.test(value)) { return f.invalid; }
            if (!f.test && f.invalid && !input.validity.valid) { return f.invalid; }
            return '';
        }

        form.addEventListener('submit', function (event) {
            var problems = [];
            Object.keys(fields).forEach(function (key) {
                var message = check(fields[key]);
                if (message) { showError(fields[key], message); problems.push({ key: key, message: message }); }
                else { clearError(fields[key]); }
            });

            if (problems.length === 0) { return; }

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

        /* Reset also clears messages */
        form.addEventListener('reset', function () {
            Object.keys(fields).forEach(function (key) { clearError(fields[key]); });
            summary.hidden = true;
        });

        /* Mobile: allow digits only */
        document.getElementById('mobile').addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        /* Clear an error as soon as the user fixes the field */
        Object.keys(fields).forEach(function (key) {
            document.getElementById(fields[key].id).addEventListener('input', function () {
                if (!check(fields[key])) { clearError(fields[key]); }
            });
        });

        if (!summary.hidden) { summary.focus(); }
    })();
</script>
@endpush