{{-- ============================================================
     Feedback Form  (DBIM / GIGW 3.0, WCAG 2.1 AA)
     Same look as Applicant Login: blue banner with breadcrumb,
     grey band outside, ONE white card inside.
     Styles: section 50 (frame) + 52 (form) + 53 (hints, notes) + 60 (banner)
     No inline styles, no autofocus, no jQuery. Works with JS off.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Feedback Form | RTI Online, Government of Goa')

@section('content')
@php
    $privacy  = '#';   // route('privacy')
    // server error key => field id
    $fieldIds = ['name' => 'name', 'mobile' => 'mobile', 'email' => 'email', 'message' => 'message'];
@endphp

<div class="dbim-sd">
    <div class="dbim-pb">
        <div class="dbim-pb-wrap">
            <nav aria-label="Breadcrumb">
                <ol class="dbim-pb-crumb">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li aria-current="page">Feedback</li>
                </ol>
            </nav>
            <h1 id="feedbackHeading" class="dbim-pb-title">Feedback Form</h1>
        </div>
    </div>

    <section class="dbim-sd-section" aria-labelledby="feedbackHeading">
        <div class="dbim-sd-wrap">
            <div class="dbim-sd-card dbim-lo-card">

                {{-- Success message (stays visible, no auto-hide) --}}
                @if(session('success'))
                    <div class="alert alert-success" role="status"><strong>Success:</strong> {{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger" role="alert"><strong>Error:</strong> {{ session('error') }}</div>
                @endif

                {{-- Error summary: filled by script or by the server; receives focus --}}
                <div id="errorSummary" class="alert alert-danger" role="alert" tabindex="-1"
                     @if(!$errors->any()) hidden @endif>
                    <p class="fw-bold mb-2">Please correct the following:</p>
                    <ul class="mb-0" id="errorSummaryList">
                        @foreach($errors->messages() as $key => $messages)
                            @foreach($messages as $message)
                                <li><a href="#{{ $fieldIds[$key] ?? 'name' }}">{{ $message }}</a></li>
                            @endforeach
                        @endforeach
                    </ul>
                </div>

                <form id="feedbackForm" class="dbim-lo-form" method="post" action="{{ route('feedback.submit') }}" novalidate>
                    @csrf

               <p class="dbim-lo-required dbim-lo-required--note">
                    Your feedback helps us improve this portal. Please do not enter sensitive personal details in the message.
                    Fields marked with
                    (<span class="dbim-required" aria-hidden="true">*</span><span class="dbim-sr-only">asterisk</span>)
                    are required.
                </p>

                    {{-- Name --}}
                    <div class="dbim-lo-field">
                        <label for="name" class="dbim-sd-label">
                            Name <span class="dbim-required" aria-hidden="true">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name') }}"
                               placeholder="Enter your full name"
                               autocomplete="name" required aria-required="true"
                               aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
                               aria-describedby="nameError">
                        <div id="nameError" class="invalid-feedback @error('name') d-block @enderror">@error('name'){{ $message }}@enderror</div>
                    </div>

                    {{-- Mobile --}}
                    <div class="dbim-lo-field">
                        <label for="mobile" class="dbim-sd-label">
                            Mobile Number <span class="dbim-required" aria-hidden="true">*</span>
                        </label>
                        <input type="tel"
                               class="form-control @error('mobile') is-invalid @enderror"
                               id="mobile" name="mobile" value="{{ old('mobile') }}"
                               placeholder="10-digit mobile number"
                               inputmode="numeric" maxlength="10" pattern="[0-9]{10}"
                               autocomplete="tel-national" required aria-required="true"
                               aria-invalid="{{ $errors->has('mobile') ? 'true' : 'false' }}"
                               aria-describedby="mobileHint mobileError">
                        <div id="mobileHint" class="form-text">Enter 10 digits without +91 or 0.</div>
                        <div id="mobileError" class="invalid-feedback @error('mobile') d-block @enderror">@error('mobile'){{ $message }}@enderror</div>
                    </div>

                    {{-- Email --}}
                    <div class="dbim-lo-field">
                        <label for="email" class="dbim-sd-label">
                            Email <span class="dbim-required" aria-hidden="true">*</span>
                        </label>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}"
                               placeholder="name@example.com"
                               autocomplete="email" required aria-required="true"
                               aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                               aria-describedby="emailError">
                        <div id="emailError" class="invalid-feedback @error('email') d-block @enderror">@error('email'){{ $message }}@enderror</div>
                    </div>

                    {{-- Message --}}
                    <div class="dbim-lo-field">
                        <label for="message" class="dbim-sd-label">
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

                    <div class="dbim-lo-actions dbim-lo-actions--end">
                        <button type="reset" class="dbim-pv-clear" title="Reset">Reset</button>
                        <button type="submit" id="feedbackButton" class="dbim-sd-btn" title="Send Feedback">
                            Send Feedback
                        </button>
                    </div>
                    <span class="dbim-sr-only" id="fbBusy" role="status"></span>

                    <p class="dbim-lo-note">
                        Your details are used only to respond to your feedback, as described in the
                        <a href="{{ $privacy }}">Privacy Policy</a>.
                    </p>
                </form>

            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    var form        = document.getElementById('feedbackForm');
    var summary     = document.getElementById('errorSummary');
    var summaryList = document.getElementById('errorSummaryList');
    var submitBtn   = document.getElementById('feedbackButton');
    var busy        = document.getElementById('fbBusy');
    if (!form) { return; }

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
    function check(f) {
        var input = document.getElementById(f.id), value = input.value.trim();
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

        if (!problems.length) {
            summary.hidden = true;
            /* lock the button only once the request is really being sent */
            setTimeout(function () {
                submitBtn.disabled = true;
                if (busy) { busy.textContent = 'Sending feedback. Please wait.'; }
            }, 0);
            return;
        }

        event.preventDefault();
        summaryList.textContent = '';
        problems.forEach(function (p) {
            var li = document.createElement('li'), a = document.createElement('a');
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

    /* Mobile: digits only */
    document.getElementById('mobile').addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    /* Clear an error as soon as the user fixes the field */
    Object.keys(fields).forEach(function (key) {
        document.getElementById(fields[key].id).addEventListener('input', function () {
            if (!check(fields[key])) { clearError(fields[key]); }
        });
    });

    /* Back button (bfcache) must not leave the button disabled */
    window.addEventListener('pageshow', function () {
        submitBtn.disabled = false;
        if (busy) { busy.textContent = ''; }
    });

    /* Errors sent back by the server: move focus to the summary */
    if (!summary.hidden) { summary.focus(); }
})();
</script>
@endpush