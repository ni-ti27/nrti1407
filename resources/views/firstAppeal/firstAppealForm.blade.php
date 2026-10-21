{{-- ============================================================
     First Appeal Request form  (DBIM / GIGW 3.0, keyboard accessible, five steps)
     Uses layouts/app.blade.php for <head>, header, <main>, footer
     (the footer comes from the layout, UsersView.footer).
     Same pattern as the RTI New Request and Second Appeal (Offline) forms:
     dbim-form-card, error summary, stepper, UsersView.partials.dbim-form-scripts.
     No inline styles, no jQuery.
     Details that are already known (authority, applicant, RTI application)
     are shown in read-only boxes; hidden fields carry the same values and
     names the form sent before.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'First Appeal Request | RTI Online, Government of Goa')

@section('content')
@php
    $star = '<span class="dbim-required" aria-hidden="true">*</span>';
    $s    = $data['submitRequest'] ?? null;

    $genderText = ['male' => 'Male', 'female' => 'Female', 'other' => 'Other'];

    /* [label, field name, submitted value, text shown] */
    $authorityFields = [
        ['Select Department / Public Authority', 'applied_dept',    $data['deptDropdownKeys'][0], $data['deptDropdownValues'][0]],
        ['Select Office',                        'applied_subdept', $data['deptDropdownKeys'][1], $data['deptDropdownValues'][1]],
        ['Select Sub Office',                    'applied_office',  $data['deptDropdownKeys'][2], $data['deptDropdownValues'][2]],
    ];

    $personalTop = [
        ['Applicant Name', 'name',   $s->name ?? '',   $s->name ?? ''],
        ['Gender',         'gender', $s->gender ?? '', $genderText[$s->gender ?? ''] ?? ($s->gender ?? '')],
    ];

    $personalBottom = [
        ['Pincode', 'pincode', $s->pincode ?? '', $s->pincode ?? ''],
        ['Country', 'country', '1', 'India'],
        ['State',   'state',   $data['stateData']->id, $data['stateData']->name],
        ['District', 'district', $data['district'] == null ? '0' : $data['district']->id, $data['district'] == null ? 'Others' : $data['district']->name],
        ['Taluka',   'taluka',   $data['taluka'] == null ? '0' : $data['taluka']->id,     $data['taluka'] == null ? 'Others' : $data['taluka']->name],
        ['Village',  'village',  $data['village'] == null ? '0' : $data['village']->id,   $data['village'] == null ? 'Others' : $data['village']->name],
        ['Phone Number',   'phone_no',   $s->phone_no ?? '',   $s->phone_no ?? ''],
        ['Mobile Number',  'mobile_no',  $s->mobile_no ?? '',  $s->mobile_no ?? ''],
        ['Email Address',  'email_id',   $s->email_id ?? '',   $s->email_id ?? ''],
        ['Citizenship',    'origin_country', 'Indian', 'Indian'],
        ['Education',      'edu_status', $s->edu_status ?? '', isset($s->edu_status) ? ($s->edu_status == 1 ? 'Educated' : 'Non-Educated') : ''],
        ['Is Applicant Below Poverty Line?', 'is_bpl', $s->is_bpl ?? '', isset($s->is_bpl) ? ($s->is_bpl == 1 ? 'Yes' : 'No') : ''],
    ];

    $rtiFields = [
        ['RTI Request Registration Number', 'reg_no', $s->reg_no ?? '', $s->reg_no ?? ''],
    ];
@endphp

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-6">

            @include('partials.flash-messages')

            <div class="card dbim-form-card">
                <h1 class="card-header h4 mb-0">First Appeal Request</h1>

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
                            <strong>Note:</strong> Fill in this form in five steps. Use <strong>Next</strong> and
                            <strong>Back</strong> to move between steps; what you have entered is kept.
                        </p>
                        <p>
                            Your details and the RTI application details are filled in for you and cannot be changed.
                            Fields marked with (<span class="dbim-required" aria-hidden="true">*</span>) are required.
                            The Submit button is enabled only when all required fields are complete.
                        </p>
                    </div>

                    <ol id="stepNav" class="dbim-stepper" aria-hidden="true"></ol>
                    <p id="stepCount" class="dbim-step-count" aria-live="polite"></p>

                    <form id="firstAppealForm" method="post" enctype="multipart/form-data"
                          action="{{ route('firstAppealSave') }}" novalidate>
                        @csrf

                        {{-- ===== Step 1: Public Authority Details ===== --}}
                        <section data-step aria-labelledby="step1Title">
                            <h2 id="step1Title" class="h5 dbim-text-key mb-3" tabindex="-1">Public authority details</h2>

                            @foreach($authorityFields as [$label, $name, $value, $text])
                                <div class="mb-3">
                                    <label for="{{ $name }}_view" class="form-label fw-bold">{{ $label }}</label>
                                    <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                                    <input type="text" id="{{ $name }}_view" value="{{ $text }}" readonly aria-readonly="true"
                                           class="form-control @error($name) is-invalid @enderror"
                                           aria-describedby="{{ $name }}_viewError">
                                    <div id="{{ $name }}_viewError" class="invalid-feedback @error($name) d-block @enderror">@error($name){{ $message }}@enderror</div>
                                </div>
                            @endforeach
                        </section>

                        {{-- ===== Step 2: Personal Details of RTI Applicant ===== --}}
                        <section data-step aria-labelledby="step2Title" hidden>
                            <h2 id="step2Title" class="h5 dbim-text-key mb-3" tabindex="-1">Personal details of RTI applicant</h2>

                            @foreach($personalTop as [$label, $name, $value, $text])
                                <div class="mb-3">
                                    <label for="{{ $name }}_view" class="form-label fw-bold">{{ $label }}</label>
                                    <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                                    <input type="text" id="{{ $name }}_view" value="{{ $text }}" readonly aria-readonly="true"
                                           class="form-control @error($name) is-invalid @enderror"
                                           aria-describedby="{{ $name }}_viewError">
                                    <div id="{{ $name }}_viewError" class="invalid-feedback @error($name) d-block @enderror">@error($name){{ $message }}@enderror</div>
                                </div>
                            @endforeach

                            <fieldset class="mb-3">
                                <legend class="dbim-legend fw-bold">Address</legend>
                                @foreach([1, 2, 3] as $n)
                                    @php($field = 'address_line' . $n)
                                    <div class="{{ $n < 3 ? 'mb-2' : 'mb-0' }}">
                                        <label for="{{ $field }}_view" class="visually-hidden">Address Line {{ $n }}</label>
                                        <input type="hidden" name="{{ $field }}" value="{{ $s->$field ?? '' }}">
                                        <input type="text" id="{{ $field }}_view" value="{{ $s->$field ?? '' }}"
                                               placeholder="Address Line {{ $n }}" readonly aria-readonly="true"
                                               class="form-control @error($field) is-invalid @enderror"
                                               aria-describedby="{{ $field }}_viewError">
                                        <div id="{{ $field }}_viewError" class="invalid-feedback @error($field) d-block @enderror">@error($field){{ $message }}@enderror</div>
                                    </div>
                                @endforeach
                            </fieldset>

                            @foreach($personalBottom as [$label, $name, $value, $text])
                                <div class="mb-3">
                                    <label for="{{ $name }}_view" class="form-label fw-bold">{{ $label }}</label>
                                    <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                                    <input type="text" id="{{ $name }}_view" value="{{ $text }}" readonly aria-readonly="true"
                                           class="form-control @error($name) is-invalid @enderror"
                                           aria-describedby="{{ $name }}_viewError">
                                    <div id="{{ $name }}_viewError" class="invalid-feedback @error($name) d-block @enderror">@error($name){{ $message }}@enderror</div>
                                </div>
                            @endforeach
                        </section>

                        {{-- ===== Step 3: Details of RTI Application ===== --}}
                        <section data-step aria-labelledby="step3Title" hidden>
                            <h2 id="step3Title" class="h5 dbim-text-key mb-3" tabindex="-1">Details of RTI application</h2>

                            @foreach($rtiFields as [$label, $name, $value, $text])
                                <div class="mb-3">
                                    <label for="{{ $name }}_view" class="form-label fw-bold">{{ $label }}</label>
                                    <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                                    <input type="text" id="{{ $name }}_view" value="{{ $text }}"
                                           placeholder="Requested Registration Number" readonly aria-readonly="true"
                                           class="form-control @error($name) is-invalid @enderror"
                                           aria-describedby="{{ $name }}_viewError">
                                    <div id="{{ $name }}_viewError" class="invalid-feedback @error($name) d-block @enderror">@error($name){{ $message }}@enderror</div>
                                </div>
                            @endforeach

                            <div class="mb-3">
                                <label for="reg_date_view" class="form-label fw-bold">RTI Request Filed on (Date)</label>
                                <input type="text" id="reg_date_view" readonly aria-readonly="true" class="form-control"
                                       value="{{ isset($s->created_at) ? $s->created_at->format('d/m/Y') : '' }}">
                            </div>

                            <div class="mb-3">
                                <span class="form-label fw-bold d-block">Copy of the RTI Application (Only Pdf)</span>
                                <a href="{{ asset($data['filePath']) }}" target="_blank" rel="noopener">
                                    {{ $s->document_name_original }}<span class="visually-hidden"> (opens in a new tab)</span>
                                </a>
                                @error('applicant_upload')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </section>

                        {{-- ===== Step 4: PIO Details and PIO Response ===== --}}
                        <section data-step aria-labelledby="step4Title" hidden>
                            <h2 id="step4Title" class="h5 dbim-text-key mb-3" tabindex="-1">PIO details and response</h2>

                            <div class="mb-3">
                                <label for="pioname" class="form-label fw-bold">PIO Name {!! $star !!}</label>
                                <input type="text" id="pioname" name="pioname" value="{{ old('pioname', $data['pioDetails']->name ?? '') }}"
                                       placeholder="PIO Name"
                                       class="form-control @error('pioname') is-invalid @enderror"
                                       data-validate data-required="true" data-msg="Enter the PIO name."
                                       required aria-required="true" aria-describedby="pionameError">
                                <div id="pionameError" class="invalid-feedback @error('pioname') d-block @enderror">@error('pioname'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="pioaddr" class="form-label fw-bold">PIO Address {!! $star !!}</label>
                                <input type="text" id="pioaddr" name="pioaddr" value="{{ old('pioaddr', $data['pioDetails']->address1 ?? '') }}"
                                       placeholder="PIO Address"
                                       class="form-control @error('pioaddr') is-invalid @enderror"
                                       data-validate data-required="true" data-msg="Enter the PIO address."
                                       required aria-required="true" aria-describedby="pioaddrError">
                                <div id="pioaddrError" class="invalid-feedback @error('pioaddr') d-block @enderror">@error('pioaddr'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="pio_resp_dropdown" class="form-label fw-bold">Nature of Response of PIO (Ground for Appeal) {!! $star !!}</label>
                                <select id="pio_resp_dropdown" name="pio_resp_dropdown"
                                        class="form-select @error('pio_resp_dropdown') is-invalid @enderror"
                                        data-validate data-required="true" data-msg="Select a response from the list."
                                        required aria-required="true" aria-describedby="pio_resp_dropdownError">
                                    <option value="">Select Response</option>
                                    <option value="1" {{ old('pio_resp_dropdown') == '1' ? 'selected' : '' }}>Refused access to the Information Requested</option>
                                    <option value="2" {{ old('pio_resp_dropdown') == '2' ? 'selected' : '' }}>No Response within the Time Limit</option>
                                    <option value="3" {{ old('pio_resp_dropdown') == '3' ? 'selected' : '' }}>Unreasonable Amount of Fee is Required to Pay</option>
                                    <option value="4" {{ old('pio_resp_dropdown') == '4' ? 'selected' : '' }}>Provided Incomplete, Misleading or False Information</option>
                                    <option value="5" {{ old('pio_resp_dropdown') == '5' ? 'selected' : '' }}>Any Other Ground</option>
                                </select>
                                <div id="pio_resp_dropdownError" class="invalid-feedback @error('pio_resp_dropdown') d-block @enderror">@error('pio_resp_dropdown'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="pio_reply_document" class="form-label fw-bold">Copy of Reply of PIO (Only Pdf)</label>
                                <input type="file" id="pio_reply_document" name="pio_reply_document" accept="application/pdf,.pdf"
                                       class="form-control @error('pio_reply_document') is-invalid @enderror"
                                       data-validate aria-describedby="pio_reply_documentHelp pio_reply_documentError">
                                <div id="pio_reply_documentHelp" class="form-text">Only PDF files up to 5 MB are accepted.</div>
                                <div id="pio_reply_documentError" class="invalid-feedback @error('pio_reply_document') d-block @enderror">@error('pio_reply_document'){{ $message }}@enderror</div>
                            </div>
                        </section>

                        {{-- ===== Step 5: Request text, supporting document, captcha ===== --}}
                        <section data-step aria-labelledby="step5Title" hidden>
                            <h2 id="step5Title" class="h5 dbim-text-key mb-3" tabindex="-1">Your first appeal</h2>

                            <div class="mb-3">
                                <label for="request_text" class="form-label fw-bold">Text for RTI first appeal application {!! $star !!}</label>
                                <div id="request_textHelp" class="form-text mb-1">Enter text for RTI Request application up to 3000 characters. Only alphabets A-Z a-z, numbers 0-9 and special characters , . - _ ( ) / @ : &amp; \ % are allowed.</div>
                                <textarea id="request_text" name="request_text" rows="6" maxlength="3000"
                                          class="form-control @error('request_text') is-invalid @enderror"
                                          data-validate data-required="true" data-msg="Enter the text for the first appeal application."
                                          required aria-required="true"
                                          aria-describedby="request_textHelp request_textCount request_textError">{{ old('request_text') }}</textarea>
                                <div id="request_textCount" class="form-text">0 of 3000 characters used</div>
                                <div id="request_textError" class="invalid-feedback @error('request_text') d-block @enderror">@error('request_text'){{ $message }}@enderror</div>
                            </div>

                            <h3 class="h6 dbim-text-key mt-4 mb-3">Supporting document</h3>

                            <div class="mb-3">
                                <label for="doc_name" class="form-label fw-bold">Document Name</label>
                                <input type="text" id="doc_name" name="doc_name" value="{{ old('doc_name') }}"
                                       placeholder="Enter Text" class="form-control @error('doc_name') is-invalid @enderror"
                                       aria-describedby="doc_nameError">
                                <div id="doc_nameError" class="invalid-feedback @error('doc_name') d-block @enderror">@error('doc_name'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="document" class="form-label fw-bold">Upload Supporting Document - Optional</label>
                                <input type="file" id="document" name="document"
                                       class="form-control @error('document') is-invalid @enderror"
                                       data-validate aria-describedby="documentHelp documentError">
                                <div id="documentHelp" class="form-text">Optional. Files up to 5 MB are accepted.</div>
                                <div id="documentError" class="invalid-feedback @error('document') d-block @enderror">@error('document'){{ $message }}@enderror</div>
                            </div>

                            <x-captcha />
                        </section>

                        {{-- ===== Actions ===== --}}
                        <div class="d-flex justify-content-end flex-wrap gap-2">
                            <button type="button" id="btnBack" class="download-outline-btn text-uppercase w-ft" title="Back" hidden>Back</button>
                            <button type="button" id="btnNext" class="download-btn text-uppercase w-ft" title="Next">Next</button>
                            <button type="submit" id="btnSubmit" class="download-btn text-uppercase w-ft" title="Submit" hidden>Submit</button>
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
    var form = $('firstAppealForm');
    var backBtn = $('btnBack'), nextBtn = $('btnNext'), submitBtn = $('btnSubmit');
    var statusEl = $('submitStatus');
    var summary = $('errorSummary');
    var busy = false;

    /* ---------- field rules ---------- */
    var TEXT_ALLOWED = /^[A-Za-z0-9\s,.\-_()\/\u0040:&\\%]+$/;

    $('request_text').dataCheck = function (el) {
        return TEXT_ALLOWED.test(el.value)
            ? ''
            : 'The text has characters that are not allowed. Use only letters, numbers, spaces and , . - _ ( ) / @ : & \\ %';
    };

    $('pio_reply_document').dataCheck = function (el) {
        var file = el.files && el.files[0];
        if (!file) { return ''; }
        if (!/\.pdf$/i.test(file.name)) { return 'Only PDF files are allowed.'; }
        if (file.size > 5 * 1024 * 1024) { return 'File size should not exceed 5MB.'; }
        return '';
    };

    $('document').dataCheck = function (el) {
        var file = el.files && el.files[0];
        if (file && file.size > 5 * 1024 * 1024) { return 'File size should not exceed 5MB.'; }
        return '';
    };

    function updateCount() {
        $('request_textCount').textContent = $('request_text').value.length + ' of 3000 characters used';
    }
    $('request_text').addEventListener('input', updateCount);
    updateCount();

    /* ---------- steps and buttons ---------- */
    var stepper = DbimForm.stepper(form, function () { syncButtons(); });

    function syncButtons() {
        var last = stepper.current === stepper.steps.length - 1;
        backBtn.hidden = stepper.current === 0;
        nextBtn.hidden = last;
        submitBtn.hidden = !last;

        if (busy) { return; }
        var remaining = DbimForm.validate(form, false).length;
        submitBtn.disabled = remaining > 0;
        statusEl.textContent = !last ? '' :
            (remaining ? 'Complete all required fields to enable the Submit button. ' + remaining + ' remaining.'
                       : 'All required fields are complete.');
    }

    function setBusy(on) {
        busy = on;
        if (on) {
            submitBtn.dataset.label = submitBtn.textContent;
            submitBtn.textContent = 'Submitting…';
            submitBtn.disabled = true;
            statusEl.textContent = 'Submitting your appeal. Please wait.';
        } else {
            if (submitBtn.dataset.label) { submitBtn.textContent = submitBtn.dataset.label; }
            syncButtons();
        }
    }

    nextBtn.addEventListener('click', function () {
        if (!stepper.validateCurrent()) { return; }
        stepper.show(stepper.current + 1, true);
    });

    backBtn.addEventListener('click', function () { stepper.show(stepper.current - 1, true); });

    /* ---------- submit ---------- */
    form.addEventListener('submit', function (event) {
        if (busy) { event.preventDefault(); return; }

        /* Enter key on an earlier step moves on instead of submitting */
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

        setBusy(true);   /* the form is then submitted normally */
    });

    /* ---------- start ---------- */
    DbimForm.bind(form, syncButtons);

    /* After a server-side validation error, open the step that has it */
    var hadServerErrors = !summary.hidden;
    var firstError = form.querySelector('.is-invalid, .invalid-feedback.d-block');
    stepper.show(firstError ? stepper.stepOf(firstError) : 0, false);
    if (hadServerErrors) { summary.hidden = false; summary.focus(); }
})();
</script>
@endpush