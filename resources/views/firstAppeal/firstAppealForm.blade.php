{{-- ============================================================
     First Appeal Request form  (DBIM / GIGW 3.0, keyboard accessible, five steps)
     Same layout as the RTI New Request form: note on the left, form card on the right.
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     Styles: section 65 / 65b in public/css/dbim.css (.dbim-fa-*)
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
        ['Department or public authority', 'applied_dept',    $data['deptDropdownKeys'][0], $data['deptDropdownValues'][0]],
        ['Office',                         'applied_subdept', $data['deptDropdownKeys'][1], $data['deptDropdownValues'][1]],
        ['Sub-office',                     'applied_office',  $data['deptDropdownKeys'][2], $data['deptDropdownValues'][2]],
    ];

    $personalTop = [
        ['Applicant name', 'name',   $s->name ?? '',   $s->name ?? ''],
        ['Gender',         'gender', $s->gender ?? '', $genderText[$s->gender ?? ''] ?? ($s->gender ?? '')],
    ];

    $personalBottom = [
        ['Pincode', 'pincode', $s->pincode ?? '', $s->pincode ?? ''],
        ['Country', 'country', '1', 'India'],
        ['State',   'state',   $data['stateData']->id, $data['stateData']->name],
        ['District', 'district', $data['district'] == null ? '0' : $data['district']->id, $data['district'] == null ? 'Others' : $data['district']->name],
        ['Taluka',   'taluka',   $data['taluka'] == null ? '0' : $data['taluka']->id,     $data['taluka'] == null ? 'Others' : $data['taluka']->name],
        ['Village',  'village',  $data['village'] == null ? '0' : $data['village']->id,   $data['village'] == null ? 'Others' : $data['village']->name],
        ['Phone number',   'phone_no',   $s->phone_no ?? '',   $s->phone_no ?? ''],
        ['Mobile number',  'mobile_no',  $s->mobile_no ?? '',  $s->mobile_no ?? ''],
        ['Email address',  'email_id',   $s->email_id ?? '',   $s->email_id ?? ''],
        ['Citizenship',    'origin_country', 'Indian', 'Indian'],
        ['Education',      'edu_status', $s->edu_status ?? '', isset($s->edu_status) ? ($s->edu_status == 1 ? 'Educated' : 'Non-Educated') : ''],
        ['Is the applicant below the poverty line (BPL)?', 'is_bpl', $s->is_bpl ?? '', isset($s->is_bpl) ? ($s->is_bpl == 1 ? 'Yes' : 'No') : ''],
    ];

    $rtiFields = [
        ['RTI request registration number', 'reg_no', $s->reg_no ?? '', $s->reg_no ?? ''],
    ];
@endphp

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
                        <li>Fill in this form in five steps. Use <strong>Next</strong> and <strong>Back</strong> to move between steps; what you have entered is kept.</li>
                        <li>Your details and the RTI application details are filled in for you and cannot be changed.</li>
                        <li>The Submit button is enabled only when all required fields are complete.</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Right column: form card --}}
        <div class="col-12 col-lg-8">
            <section class="dbim-fa-card" aria-labelledby="pageTitle">

                <h1 id="pageTitle" class="dbim-fa-title">First Appeal Request</h1>
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

                <form id="firstAppealForm" method="post" enctype="multipart/form-data"
                      action="{{ route('firstAppealSave') }}" novalidate>
                    @csrf

                    {{-- ===== Step 1: Public Authority Details ===== --}}
                    <section data-step aria-labelledby="step1Title">
                        <h2 id="step1Title" class="dbim-fa-card__title" tabindex="-1">Public authority details</h2>

                        @foreach($authorityFields as [$label, $name, $value, $text])
                            <div class="dbim-fa-field">
                                <label for="{{ $name }}_view" class="dbim-fa-label">{{ $label }}</label>
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
                        <h2 id="step2Title" class="dbim-fa-card__title" tabindex="-1">Personal details of RTI applicant</h2>

                        @foreach($personalTop as [$label, $name, $value, $text])
                            <div class="dbim-fa-field">
                                <label for="{{ $name }}_view" class="dbim-fa-label">{{ $label }}</label>
                                <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                                <input type="text" id="{{ $name }}_view" value="{{ $text }}" readonly aria-readonly="true"
                                       class="form-control @error($name) is-invalid @enderror"
                                       aria-describedby="{{ $name }}_viewError">
                                <div id="{{ $name }}_viewError" class="invalid-feedback @error($name) d-block @enderror">@error($name){{ $message }}@enderror</div>
                            </div>
                        @endforeach

                        <fieldset class="dbim-fa-field">
                            <legend class="dbim-fa-label dbim-fa-legend-label">Address</legend>
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
                            <div class="dbim-fa-field">
                                <label for="{{ $name }}_view" class="dbim-fa-label">{{ $label }}</label>
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
                        <h2 id="step3Title" class="dbim-fa-card__title" tabindex="-1">Details of RTI application</h2>

                        @foreach($rtiFields as [$label, $name, $value, $text])
                            <div class="dbim-fa-field">
                                <label for="{{ $name }}_view" class="dbim-fa-label">{{ $label }}</label>
                                <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                                <input type="text" id="{{ $name }}_view" value="{{ $text }}"
                                       placeholder="Requested Registration Number" readonly aria-readonly="true"
                                       class="form-control @error($name) is-invalid @enderror"
                                       aria-describedby="{{ $name }}_viewError">
                                <div id="{{ $name }}_viewError" class="invalid-feedback @error($name) d-block @enderror">@error($name){{ $message }}@enderror</div>
                            </div>
                        @endforeach

                        <div class="dbim-fa-field">
                            <label for="reg_date_view" class="dbim-fa-label">RTI request filed on (date)</label>
                            <input type="text" id="reg_date_view" readonly aria-readonly="true" class="form-control"
                                   value="{{ isset($s->created_at) ? $s->created_at->format('d/m/Y') : '' }}">
                        </div>

                        <div class="dbim-fa-field">
                            <span class="dbim-fa-label d-block">Copy of the RTI application (PDF)</span>
                            <a href="{{ asset($data['filePath']) }}" target="_blank" rel="noopener">
                                {{ $s->document_name_original }}<span class="dbim-sr-only"> (opens in a new tab)</span>
                            </a>
                            @error('applicant_upload')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </section>

                    {{-- ===== Step 4: PIO Details and PIO Response ===== --}}
                    <section data-step aria-labelledby="step4Title" hidden>
                        <h2 id="step4Title" class="dbim-fa-card__title" tabindex="-1">PIO details and response</h2>

                        <div class="dbim-fa-field">
                            <label for="pioname" class="dbim-fa-label">PIO name {!! $star !!}</label>
                            <input type="text" id="pioname" name="pioname" value="{{ old('pioname', $data['pioDetails']->name ?? '') }}"
                                   placeholder="PIO Name"
                                   class="form-control @error('pioname') is-invalid @enderror"
                                   data-validate data-required="true" data-msg="Enter the PIO name."
                                   required aria-required="true" aria-describedby="pionameError">
                            <div id="pionameError" class="invalid-feedback @error('pioname') d-block @enderror">@error('pioname'){{ $message }}@enderror</div>
                        </div>

                        <div class="dbim-fa-field">
                            <label for="pioaddr" class="dbim-fa-label">PIO address {!! $star !!}</label>
                            <input type="text" id="pioaddr" name="pioaddr" value="{{ old('pioaddr', $data['pioDetails']->address1 ?? '') }}"
                                   placeholder="PIO Address"
                                   class="form-control @error('pioaddr') is-invalid @enderror"
                                   data-validate data-required="true" data-msg="Enter the PIO address."
                                   required aria-required="true" aria-describedby="pioaddrError">
                            <div id="pioaddrError" class="invalid-feedback @error('pioaddr') d-block @enderror">@error('pioaddr'){{ $message }}@enderror</div>
                        </div>

                        <div class="dbim-fa-field">
                            <label for="pio_resp_dropdown" class="dbim-fa-label">Nature of response of PIO (ground for appeal) {!! $star !!}</label>
                            <select id="pio_resp_dropdown" name="pio_resp_dropdown"
                                    class="form-select @error('pio_resp_dropdown') is-invalid @enderror"
                                    data-validate data-required="true" data-msg="Select a response from the list."
                                    required aria-required="true" aria-describedby="pio_resp_dropdownError">
                                <option value="">Select response</option>
                                <option value="1" {{ old('pio_resp_dropdown') == '1' ? 'selected' : '' }}>Refused access to the Information Requested</option>
                                <option value="2" {{ old('pio_resp_dropdown') == '2' ? 'selected' : '' }}>No Response within the Time Limit</option>
                                <option value="3" {{ old('pio_resp_dropdown') == '3' ? 'selected' : '' }}>Unreasonable Amount of Fee is Required to Pay</option>
                                <option value="4" {{ old('pio_resp_dropdown') == '4' ? 'selected' : '' }}>Provided Incomplete, Misleading or False Information</option>
                                <option value="5" {{ old('pio_resp_dropdown') == '5' ? 'selected' : '' }}>Any Other Ground</option>
                            </select>
                            <div id="pio_resp_dropdownError" class="invalid-feedback @error('pio_resp_dropdown') d-block @enderror">@error('pio_resp_dropdown'){{ $message }}@enderror</div>
                        </div>

                        <div class="dbim-fa-field">
                            <label for="pio_reply_document" class="dbim-fa-label">Copy of reply of PIO (PDF)</label>
                            <input type="file" id="pio_reply_document" name="pio_reply_document" accept="application/pdf,.pdf"
                                   class="form-control @error('pio_reply_document') is-invalid @enderror"
                                   data-validate aria-describedby="pio_reply_documentHelp pio_reply_documentError">
                            <div id="pio_reply_documentHelp" class="form-text">Only PDF files up to 5 MB are accepted.</div>
                            <div id="pio_reply_documentError" class="invalid-feedback @error('pio_reply_document') d-block @enderror">@error('pio_reply_document'){{ $message }}@enderror</div>
                        </div>
                    </section>

                    {{-- ===== Step 5: Request text, supporting document, captcha ===== --}}
                    <section data-step aria-labelledby="step5Title" hidden>
                        <h2 id="step5Title" class="dbim-fa-card__title" tabindex="-1">Your first appeal</h2>

                        <div class="dbim-fa-field">
                            <label for="request_text" class="dbim-fa-label">Text for RTI first appeal application {!! $star !!}</label>
                            <div id="request_textHelp" class="form-text mb-1">Enter text for RTI Request application up to 3000 characters. Only alphabets A-Z a-z, numbers 0-9 and special characters , . - _ ( ) / @ : &amp; \ % are allowed.</div>
                            <textarea id="request_text" name="request_text" rows="6" maxlength="3000"
                                      class="form-control @error('request_text') is-invalid @enderror"
                                      data-validate data-required="true" data-msg="Enter the text for the first appeal application."
                                      required aria-required="true"
                                      aria-describedby="request_textHelp request_textCount request_textError">{{ old('request_text') }}</textarea>
                            <div id="request_textCount" class="form-text">0 of 3000 characters used</div>
                            <div id="request_textError" class="invalid-feedback @error('request_text') d-block @enderror">@error('request_text'){{ $message }}@enderror</div>
                        </div>

                        <h3 class="dbim-fa-card__title">Supporting document</h3>

                        <div class="dbim-fa-field">
                            <label for="doc_name" class="dbim-fa-label">Document name</label>
                            <input type="text" id="doc_name" name="doc_name" value="{{ old('doc_name') }}"
                                   placeholder="Enter Text" class="form-control @error('doc_name') is-invalid @enderror"
                                   aria-describedby="doc_nameError">
                            <div id="doc_nameError" class="invalid-feedback @error('doc_name') d-block @enderror">@error('doc_name'){{ $message }}@enderror</div>
                        </div>

                        <div class="dbim-fa-field">
                            <label for="document" class="dbim-fa-label">Upload supporting document (optional)</label>
                            <input type="file" id="document" name="document"
                                   class="form-control @error('document') is-invalid @enderror"
                                   data-validate aria-describedby="documentHelp documentError">
                            <div id="documentHelp" class="form-text">Optional. Files up to 5 MB are accepted.</div>
                            <div id="documentError" class="invalid-feedback @error('document') d-block @enderror">@error('document'){{ $message }}@enderror</div>
                        </div>

                        <x-captcha />
                    </section>

                    {{-- ===== Actions ===== --}}
                    <div class="dbim-fa-actions">
                        <button type="button" id="btnBack" class="dbim-fa-btn dbim-fa-btn--outline" hidden>Back</button>
                        <button type="button" id="btnNext" class="dbim-fa-btn">Next</button>
                        <button type="submit" id="btnSubmit" class="dbim-fa-btn" hidden>Submit</button>
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