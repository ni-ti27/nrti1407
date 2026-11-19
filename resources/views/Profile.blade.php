{{-- ============================================================
     User Profile Form  (DBIM / GIGW 3.0, keyboard accessible, two steps)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     No inline styles, no jQuery, no alert() boxes.
     Styles: section 65 in public/css/dbim.css (.dbim-fa-*)
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'User Profile | RTI Online, Government of Goa')

@section('content')
<div class="container pt-4 pb-5">

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
                    <ul>
                        <li>Fill in this form in two steps. Use <strong>Next</strong> and <strong>Back</strong> to move between steps; what you have entered is kept.</li>
                        <li>Your mobile number and email address come from your login and cannot be changed here.</li>
                        <li>The <strong>Update</strong> button is enabled only when all required fields are complete.</li>
                        @if (($show_skip ?? 0) == 1)
                            <li>You can use <strong>Skip</strong> to complete your profile later.</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        {{-- Right column: form card --}}
        <div class="col-12 col-lg-8">
            <section class="dbim-fa-card" aria-labelledby="pageTitle">

                <h1 id="pageTitle" class="dbim-fa-title">User Profile</h1>
                <p class="dbim-fa-legend">
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

                <ol id="stepNav" class="dbim-stepper" aria-hidden="true"></ol>
                <p id="stepCount" class="dbim-step-count" aria-live="polite"></p>

                <form id="profileForm" method="post" enctype="multipart/form-data"
                      action="{{ route('store-profile') }}" novalidate>
                    @csrf

                    <section data-step aria-labelledby="step1Title">
                        <h2 id="step1Title" class="dbim-fa-card__title" tabindex="-1">Your details</h2>
                        @include('UsersView.partials.applicant-fields', ['section' => 'personal', 'profileMode' => true])
                    </section>

                    <section data-step aria-labelledby="step2Title" hidden>
                        <h2 id="step2Title" class="dbim-fa-card__title" tabindex="-1">Your address</h2>
                        @include('UsersView.partials.applicant-fields', ['section' => 'address', 'profileMode' => true])
                    </section>

                    <div class="dbim-fa-actions">
                        <button type="reset" class="dbim-fa-btn dbim-fa-btn--outline">Reset</button>
                        @if (($show_skip ?? 0) == 1)
                            <button type="submit" id="btnSkip" name="skip" value="1" class="dbim-fa-btn dbim-fa-btn--outline">Skip</button>
                        @endif
                        <button type="button" id="btnBack" class="dbim-fa-btn dbim-fa-btn--outline" hidden>Back</button>
                        <button type="button" id="btnNext" class="dbim-fa-btn">Next</button>
                        <button type="submit" id="btnUpdate" name="send" class="dbim-fa-btn" hidden>Update</button>
                    </div>
                    <p id="submitStatus" class="text-p2 text-end mt-2 mb-0" role="status"></p>
                </form>

            </section>
        </div>

    </div>
</div>
@endsection

@push('scripts')
@include('UsersView.partials.dbim-form-scripts')
<script>
(function () {
    'use strict';

    var $ = function (id) { return document.getElementById(id); };
    var form = $('profileForm');
    var backBtn = $('btnBack'), nextBtn = $('btnNext'), updateBtn = $('btnUpdate'), skipBtn = $('btnSkip');
    var statusEl = $('submitStatus');
    var summary = $('errorSummary');
    var busy = false;

    var stepper = DbimForm.stepper(form, function () { syncButtons(); });

    function syncButtons() {
        var last = stepper.current === stepper.steps.length - 1;
        backBtn.hidden = stepper.current === 0;
        nextBtn.hidden = last;
        updateBtn.hidden = !last;

        if (busy) { return; }
        var remaining = DbimForm.validate(form, false).length;
        updateBtn.disabled = remaining > 0;
        statusEl.textContent = !last ? '' :
            (remaining ? 'Complete all required fields to enable the Update button. ' + remaining + ' remaining.'
                       : 'All required fields are complete.');
    }

    nextBtn.addEventListener('click', function () {
        if (stepper.validateCurrent()) { stepper.show(stepper.current + 1, true); }
    });
    backBtn.addEventListener('click', function () { stepper.show(stepper.current - 1, true); });

    form.addEventListener('submit', function (event) {
        var submitter = event.submitter || document.activeElement;

        /* Skip never validates */
        if (submitter && submitter.name === 'skip') { return; }

        /* Enter key on the first step moves on instead of submitting */
        if (stepper.current !== stepper.steps.length - 1) {
            event.preventDefault();
            nextBtn.click();
            return;
        }

        var bad = DbimForm.validate(form, true);
        if (bad.length) {
            event.preventDefault();
            stepper.show(stepper.stepOf(bad[0]), false);
            DbimForm.summary(bad);
            return;
        }
        if (!window.confirm('Are you sure you want to update your profile?')) {
            event.preventDefault();
            return;
        }

        /* Prevent a second click while the request is on its way */
        busy = true;
        statusEl.textContent = 'Saving your profile. Please wait.';
        setTimeout(function () {
            updateBtn.disabled = true;
            updateBtn.textContent = 'Updating…';
            if (skipBtn) { skipBtn.disabled = true; }
        }, 0);
    });

    /* Reset (with warning) */
    form.addEventListener('reset', function (event) {
        if (!window.confirm('Clear your changes and go back to the saved details?')) {
            event.preventDefault();
            return;
        }
        setTimeout(function () {
            DbimForm.clearAll(form);
            DbimForm.resetLocation();
            stepper.show(0, false);
        }, 0);
    });

    DbimForm.bind(form, syncButtons);

    /* After a server-side validation error, open the step that has it */
    var hadServerErrors = !summary.hidden;
    var firstError = form.querySelector('.is-invalid, .invalid-feedback.d-block');
    stepper.show(firstError ? stepper.stepOf(firstError) : 0, false);
    if (hadServerErrors) { summary.hidden = false; summary.focus(); }
})();
</script>
@endpush