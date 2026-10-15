{{-- ============================================================
     User Profile Form  (DBIM / GIGW 3.0, keyboard accessible, two steps)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     No inline styles, no jQuery, no alert() boxes.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'User Profile | RTI Online, Government of Goa')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-6">

            @include('partials.flash-messages')

            <div class="card dbim-form-card">
                <h1 class="card-header h4 mb-0">User Profile</h1>

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
                            <strong>Note:</strong> Fill in this form in two steps. Use <strong>Next</strong> and
                            <strong>Back</strong> to move between steps; what you have entered is kept.
                            Your mobile number and email address come from your login and cannot be changed here.
                        </p>
                        <p>
                            Fields marked with (<span class="dbim-required" aria-hidden="true">*</span>) are required.
                            The <strong>Update</strong> button is enabled only when all required fields are complete.
                            @if (($show_skip ?? 0) == 1)
                                You can use <strong>Skip</strong> to complete your profile later.
                            @endif
                        </p>
                    </div>

                    <ol id="stepNav" class="dbim-stepper" aria-hidden="true"></ol>
                    <p id="stepCount" class="dbim-step-count" aria-live="polite"></p>

                    <form id="profileForm" method="post" enctype="multipart/form-data"
                          action="{{ route('store-profile') }}" novalidate>
                        @csrf

                        <section data-step aria-labelledby="step1Title">
                            <h2 id="step1Title" class="h5 dbim-text-key mb-3" tabindex="-1">Your details</h2>
                            @include('UsersView.partials.applicant-fields', ['section' => 'personal', 'profileMode' => true])
                        </section>

                        <section data-step aria-labelledby="step2Title" hidden>
                            <h2 id="step2Title" class="h5 dbim-text-key mb-3" tabindex="-1">Your address</h2>
                            @include('UsersView.partials.applicant-fields', ['section' => 'address', 'profileMode' => true])
                        </section>

                        <div class="d-flex justify-content-end flex-wrap gap-2">
                            <button type="reset" class="download-outline-btn text-uppercase w-ft" title="Reset">Reset</button>
                            @if (($show_skip ?? 0) == 1)
                                <button type="submit" id="btnSkip" name="skip" value="1" class="download-outline-btn text-uppercase w-ft" title="Skip">Skip</button>
                            @endif
                            <button type="button" id="btnBack" class="download-outline-btn text-uppercase w-ft" title="Back" hidden>Back</button>
                            <button type="button" id="btnNext" class="download-btn text-uppercase w-ft" title="Next">Next</button>
                            <button type="submit" id="btnUpdate" name="send" class="download-btn text-uppercase w-ft" title="Update" hidden>Update</button>
                        </div>
                        <p id="submitStatus" class="text-p2 text-end mt-2 mb-0" role="status"></p>
                    </form>

                </div>
            </div>

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