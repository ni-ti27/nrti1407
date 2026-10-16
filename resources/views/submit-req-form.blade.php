{{-- ============================================================
     RTI New Request Form  (DBIM / GIGW 3.0, keyboard accessible, four steps)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     No inline styles, no jQuery, no alert() boxes.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'RTI New Request | RTI Online, Government of Goa')

@section('content')
@php
    $star  = '<span class="dbim-required" aria-hidden="true">*</span>';
    $isBpl = old('is_bpl', 'no') === 'yes';
@endphp
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-6">

            @include('partials.flash-messages')

            <div class="card dbim-form-card">
                <h1 class="card-header h4 mb-0">RTI New Request</h1>

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
                            <strong>Note:</strong> Fill in this form in four steps. Use <strong>Next</strong> and
                            <strong>Back</strong> to move between steps; what you have entered is kept.
                        </p>
                        <p>
                            Only Indian citizens can file an RTI request. An RTI fee of Rs. 10 is payable unless
                            you are below the poverty line.
                        </p>
                        <p>
                            Fields marked with (<span class="dbim-required" aria-hidden="true">*</span>) are required.
                            The final button is enabled only when all required fields are complete.
                        </p>
                    </div>

                    <ol id="stepNav" class="dbim-stepper" aria-hidden="true"></ol>
                    <p id="stepCount" class="dbim-step-count" aria-live="polite"></p>

                    <form id="rtiRequestForm" method="post" enctype="multipart/form-data"
                          action="{{ route('validate.form') }}" novalidate>
                        @csrf

                        {{-- ===== Step 1: Public authority ===== --}}
                        <section data-step aria-labelledby="step1Title">
                            <h2 id="step1Title" class="h5 dbim-text-key mb-3" tabindex="-1">Public authority details</h2>

                            <div id="pioAlert" class="alert alert-danger" role="alert" hidden></div>

                            <div class="mb-3">
                                <label for="applied_dept" class="form-label fw-bold">Department or public authority {!! $star !!}</label>
                                <select id="applied_dept" name="applied_dept"
                                        class="form-select @error('applied_dept') is-invalid @enderror"
                                        data-validate data-required="true" data-msg="Select a department or public authority."
                                        required aria-required="true" aria-describedby="applied_deptError">
                                    <option value="">Select department</option>
                                    @foreach ($deptData as $department)
                                        <option value="{{ $department->dept_code }}" {{ (string) old('applied_dept') === (string) $department->dept_code ? 'selected' : '' }}>{{ $department->department }}</option>
                                    @endforeach
                                </select>
                                <div id="applied_deptError" class="invalid-feedback @error('applied_dept') d-block @enderror">@error('applied_dept'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="applied_subdept" class="form-label fw-bold">Office {!! $star !!}</label>
                                <select id="applied_subdept" name="applied_subdept"
                                        class="form-select @error('applied_subdept') is-invalid @enderror"
                                        data-validate data-required="true" data-msg="Select an office."
                                        required aria-required="true" aria-describedby="applied_subdeptError">
                                    <option value="">Select office</option>
                                </select>
                                <div id="applied_subdeptError" class="invalid-feedback @error('applied_subdept') d-block @enderror">@error('applied_subdept'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="applied_office" class="form-label fw-bold">Sub-office {!! $star !!}</label>
                                <select id="applied_office" name="applied_office"
                                        class="form-select @error('applied_office') is-invalid @enderror"
                                        data-validate data-required="true" data-msg="Select a sub-office."
                                        required aria-required="true" aria-describedby="applied_officeError">
                                    <option value="">Select sub-office</option>
                                </select>
                                <div id="applied_officeError" class="invalid-feedback @error('applied_office') d-block @enderror">@error('applied_office'){{ $message }}@enderror</div>
                            </div>
                        </section>

                        {{-- ===== Step 2: Applicant ===== --}}
                        <section data-step aria-labelledby="step2Title" hidden>
                            <h2 id="step2Title" class="h5 dbim-text-key mb-3" tabindex="-1">Your details</h2>
                            @include('UsersView.partials.applicant-fields', ['section' => 'personal'])
                        </section>

                        {{-- ===== Step 3: Address ===== --}}
                        <section data-step aria-labelledby="step3Title" hidden>
                            <h2 id="step3Title" class="h5 dbim-text-key mb-3" tabindex="-1">Your address</h2>
                            @include('UsersView.partials.applicant-fields', ['section' => 'address'])
                        </section>

                        {{-- ===== Step 4: Request ===== --}}
                        <section data-step aria-labelledby="step4Title" hidden>
                            <h2 id="step4Title" class="h5 dbim-text-key mb-3" tabindex="-1">Your request</h2>

                            <div class="mb-3">
                                <label for="request_text" class="form-label fw-bold">Request text {!! $star !!}</label>
                                <div id="request_textHelp" class="form-text mb-1">Up to 3000 characters. Use letters, numbers, spaces and these characters only: , . - _ ( ) / @ : &amp; \ %</div>
                                <textarea id="request_text" name="request_text" rows="6" maxlength="3000"
                                          class="form-control @error('request_text') is-invalid @enderror"
                                          data-validate data-required="true" data-msg="Enter your request text."
                                          required aria-required="true"
                                          aria-describedby="request_textHelp request_textCount request_textError">{{ old('request_text') }}</textarea>
                                <div id="request_textCount" class="form-text">0 of 3000 characters used</div>
                                <div id="request_textError" class="invalid-feedback @error('request_text') d-block @enderror">@error('request_text'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="document" class="form-label fw-bold">RTI application (PDF)</label>
                                <input type="file" id="document" name="document" accept="application/pdf,.pdf"
                                       class="form-control @error('document') is-invalid @enderror"
                                       data-validate aria-describedby="documentHelp documentError">
                                <div id="documentHelp" class="form-text">Optional. Only PDF files up to 5 MB are accepted.</div>
                                <div id="documentError" class="invalid-feedback @error('document') d-block @enderror">@error('document'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="origin_country" class="form-label fw-bold">Citizenship</label>
                                <input type="text" id="origin_country" name="origin_country" value="Indian" readonly aria-readonly="true"
                                       class="form-control @error('origin_country') is-invalid @enderror"
                                       aria-describedby="origin_countryHelp origin_countryError">
                                <div id="origin_countryHelp" class="form-text">Only Indian citizens can file an RTI request.</div>
                                <div id="origin_countryError" class="invalid-feedback @error('origin_country') d-block @enderror">@error('origin_country'){{ $message }}@enderror</div>
                            </div>

                            <fieldset class="mb-3" aria-describedby="is_bplHelp is_bplError">
                                <legend class="dbim-legend fw-bold">Is the applicant below the poverty line (BPL)?</legend>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_bpl" id="is_not_bpl" value="no" {{ $isBpl ? '' : 'checked' }}>
                                    <label class="form-check-label" for="is_not_bpl">No</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_bpl" id="is_bpl" value="yes" {{ $isBpl ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_bpl">Yes</label>
                                </div>
                                <div id="is_bplHelp" class="form-text">If you select No, an RTI fee of Rs. 10 is payable.</div>
                                <div id="is_bplError" class="invalid-feedback @error('is_bpl') d-block @enderror">@error('is_bpl'){{ $message }}@enderror</div>
                            </fieldset>

                            {{-- BPL details, shown only when BPL = Yes --}}
                            <div id="bplDetails" data-conditional {{ $isBpl ? '' : 'hidden' }}>
                                <div class="mb-3">
                                    <label for="card_no" class="form-label fw-bold">BPL card, yellow ration card or certificate number {!! $star !!}</label>
                                    <input type="text" id="card_no" name="card_no" value="{{ old('card_no') }}"
                                           class="form-control @error('card_no') is-invalid @enderror"
                                           data-validate data-required="true" data-msg="Enter the card or certificate number."
                                           required aria-required="true" aria-describedby="card_noHelp card_noError">
                                    <div id="card_noHelp" class="form-text">Issued by a local body or the Government (municipal corporation, municipal council, municipality or gram panchayat).</div>
                                    <div id="card_noError" class="invalid-feedback @error('card_no') d-block @enderror">@error('card_no'){{ $message }}@enderror</div>
                                </div>

                                <div class="mb-3 dbim-field-sm">
                                    <label for="issued_year" class="form-label fw-bold">Issued year {!! $star !!}</label>
                                    <input type="text" id="issued_year" name="issued_year" value="{{ old('issued_year') }}"
                                           maxlength="4" inputmode="numeric"
                                           class="form-control @error('issued_year') is-invalid @enderror"
                                           data-validate data-required="true" data-msg="Enter the year of issue."
                                           data-pattern="^\d{4}$" data-pattern-msg="Enter a 4-digit year."
                                           required aria-required="true" aria-describedby="issued_yearError">
                                    <div id="issued_yearError" class="invalid-feedback @error('issued_year') d-block @enderror">@error('issued_year'){{ $message }}@enderror</div>
                                </div>

                                <div class="mb-3">
                                    <label for="issuing_authority" class="form-label fw-bold">Issuing authority {!! $star !!}</label>
                                    <input type="text" id="issuing_authority" name="issuing_authority" value="{{ old('issuing_authority') }}"
                                           class="form-control @error('issuing_authority') is-invalid @enderror"
                                           data-validate data-required="true" data-msg="Enter the issuing authority."
                                           required aria-required="true" aria-describedby="issuing_authorityError">
                                    <div id="issuing_authorityError" class="invalid-feedback @error('issuing_authority') d-block @enderror">@error('issuing_authority'){{ $message }}@enderror</div>
                                </div>
                            </div>
                            <x-captcha />
                        </section>

                        {{-- ===== Actions ===== --}}
                        <div class="d-flex justify-content-end flex-wrap gap-2">
                            <button type="reset" class="download-outline-btn text-uppercase w-ft" title="Reset">Reset</button>
                            <button type="button" id="btnBack" class="download-outline-btn text-uppercase w-ft" title="Back" hidden>Back</button>
                            <button type="button" id="btnNext" class="download-btn text-uppercase w-ft" title="Next">Next</button>
                            <button type="submit" id="btnPay" name="send" value="" class="download-btn text-uppercase w-ft" title="Proceed to fee payment" hidden>Proceed to fee payment</button>
                            <button type="submit" id="btnSubmit" name="send" value="Submit" class="download-btn text-uppercase w-ft" title="Submit" hidden>Submit</button>
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
    var form = $('rtiRequestForm');
    var backBtn = $('btnBack'), nextBtn = $('btnNext'), payBtn = $('btnPay'), sendBtn = $('btnSubmit');
    var statusEl = $('submitStatus');
    var summary = $('errorSummary');
    var busy = false;

    /* ---------- field rules ---------- */
    $('request_text').dataCheck = function (el) {
        var allowed = /^[A-Za-z0-9\s,.\-_()\/\u0040:&\\%]+$/;
        return allowed.test(el.value)
            ? ''
            : 'The request text has characters that are not allowed. Use only letters, numbers, spaces and , . - _ ( ) / @ : & \\ %';
    };

    $('document').dataCheck = function (el) {
        var file = el.files && el.files[0];
        if (!file) { return ''; }
        if (!/\.pdf$/i.test(file.name)) { return 'Choose a PDF file.'; }
        if (file.size > 5 * 1024 * 1024) { return 'The file is larger than 5 MB. Choose a smaller PDF.'; }
        return '';
    };

    function updateCount() {
        $('request_textCount').textContent = $('request_text').value.length + ' of 3000 characters used';
    }
    $('request_text').addEventListener('input', updateCount);
    updateCount();

    /* ---------- authority dropdowns ---------- */
    var initialPending = {
        sub: @json((string) old('applied_subdept')),
        office: @json((string) old('applied_office'))
    };
    var pending = Object.assign({}, initialPending);

    function option(value, text) {
        var o = document.createElement('option');
        o.value = value;
        o.textContent = text;
        return o;
    }
    function fail(err) { console.error('Dropdown request failed', err); }

    function loadOffices(callback) {
        var deptId = $('applied_dept').value, subId = $('applied_subdept').value;
        var sel = $('applied_office');
        if (deptId && subId) {
            DbimForm.getJSON('/officeDropdown/' + deptId + '/' + subId).then(function (rows) {
                sel.textContent = '';
                Object.keys(rows).forEach(function (k) {
                    var o = option(rows[k].suboffice_code, rows[k].department);
                    if (String(rows[k].suboffice_code) === pending.office) { o.selected = true; }
                    sel.appendChild(o);
                });
                pending.office = '';
                syncButtons();
                if (callback) { callback(); }
            }).catch(fail);
        } else {
            sel.textContent = '';
            sel.appendChild(deptId ? option('0', 'Not applicable') : option('', 'Select sub-office'));
            syncButtons();
            if (callback) { callback(); }
        }
    }

    function loadSubDepts(callback) {
        var deptId = $('applied_dept').value, sel = $('applied_subdept');
        if (deptId) {
            DbimForm.getJSON('/subDeptDropdown/' + deptId).then(function (rows) {
                sel.textContent = '';
                Object.keys(rows).forEach(function (k) {
                    var o = option(rows[k].office_code, rows[k].department);
                    if (String(rows[k].office_code) === pending.sub) { o.selected = true; }
                    sel.appendChild(o);
                });
                pending.sub = '';
                loadOffices(callback);
            }).catch(fail);
        } else {
            sel.textContent = '';
            sel.appendChild(option('', 'Select office'));
            loadOffices(callback);
        }
    }

    function selectedText(id) {
        var sel = $(id);
        return sel.options[sel.selectedIndex] ? sel.options[sel.selectedIndex].textContent.trim() : '';
    }

    /* Is a PIO appointed at the chosen level? Shows a message instead of alert(). */
    function checkPioStatus(callback) {
        var deptId = $('applied_dept').value;
        var box = $('pioAlert');
        if (!deptId || deptId === '0') {
            box.hidden = true;
            if (callback) { callback(true); }
            return;
        }
        var url = '/check-pio-status/' + deptId + '/' + ($('applied_subdept').value || 0) + '/' + ($('applied_office').value || 0);
        DbimForm.getJSON(url).then(function (response) {
            if (response.inactive) {
                box.textContent = '';
                var head = document.createElement('p');
                head.className = 'fw-bold mb-1';
                head.textContent = 'PIO not appointed at this level.';
                var body = document.createElement('p');
                body.className = 'mb-0';
                body.textContent = 'Department: ' + selectedText('applied_dept') +
                    '. Office: ' + selectedText('applied_subdept') +
                    '. Sub-office: ' + selectedText('applied_office') +
                    '. Select a different office or sub-office.';
                box.appendChild(head);
                box.appendChild(body);
                box.hidden = false;
                if (callback) { callback(false); }
                return;
            }
            box.hidden = true;
            if (callback) { callback(true); }
        }).catch(function (err) {
            console.error('check-pio-status failed', err);
            if (callback) { callback(true); } /* fail-open, as before */
        });
    }

    $('applied_dept').addEventListener('change', function () { loadSubDepts(function () { checkPioStatus(); }); });
    $('applied_subdept').addEventListener('change', function () { loadOffices(function () { checkPioStatus(); }); });
    $('applied_office').addEventListener('change', function () { checkPioStatus(); });

    /* ---------- steps and buttons ---------- */
    var stepper = DbimForm.stepper(form, function () { syncButtons(); });

    function bplYes() {
        var checked = form.querySelector('input[name="is_bpl"]:checked');
        return !!checked && checked.value === 'yes';
    }

    function syncButtons() {
        var last = stepper.current === stepper.steps.length - 1;
        var yes = bplYes();
        $('bplDetails').hidden = !yes;
        backBtn.hidden = stepper.current === 0;
        nextBtn.hidden = last;
        payBtn.hidden = !last || yes;
        sendBtn.hidden = !last || !yes;

        if (busy) { return; }
        var remaining = DbimForm.validate(form, false).length;
        payBtn.disabled = sendBtn.disabled = remaining > 0;
        statusEl.textContent = !last ? '' :
            (remaining ? 'Complete all required fields to enable the button. ' + remaining + ' remaining.'
                       : 'All required fields are complete.');
    }

    function setBusy(on) {
        busy = on;
        [payBtn, sendBtn].forEach(function (b) {
            if (on) { b.dataset.label = b.textContent; b.textContent = 'Submitting…'; b.disabled = true; }
            else if (b.dataset.label) { b.textContent = b.dataset.label; }
        });
        if (on) { statusEl.textContent = 'Submitting your request. Please wait.'; }
        else { syncButtons(); }
    }

    nextBtn.addEventListener('click', function () {
        if (!stepper.validateCurrent()) { return; }
        if (stepper.current === 0) {
            checkPioStatus(function (ok) { if (ok) { stepper.show(1, true); } });
        } else {
            stepper.show(stepper.current + 1, true);
        }
    });

    backBtn.addEventListener('click', function () { stepper.show(stepper.current - 1, true); });

    form.addEventListener('change', function (e) {
        if (e.target.name === 'is_bpl') { syncButtons(); }
    });

    /* ---------- submit ---------- */
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        if (busy) { return; }

        /* Enter key on an earlier step moves on instead of submitting */
        if (stepper.current !== stepper.steps.length - 1) { nextBtn.click(); return; }

        var bad = DbimForm.validate(form, true);
        if (bad.length) {
            stepper.show(stepper.stepOf(bad[0]), false);
            DbimForm.summary(bad);
            return;
        }

        var file = $('document').files[0];
        if (!file && !window.confirm('Submit the request without attaching an application file?')) { return; }

        setBusy(true);
        checkPioStatus(function (ok) {
            if (ok) { form.submit(); return; }
            setBusy(false);
            stepper.show(0, true);
        });
    });

    /* ---------- reset (with warning) ---------- */
    form.addEventListener('reset', function (event) {
        if (!window.confirm('Clear everything you have entered and start again?')) {
            event.preventDefault();
            return;
        }
        setTimeout(function () {
            DbimForm.clearAll(form);
            $('pioAlert').hidden = true;
            pending = Object.assign({}, initialPending);
            DbimForm.resetLocation();
            loadSubDepts();
            updateCount();
            stepper.show(0, false);
        }, 0);
    });

    /* ---------- start ---------- */
    DbimForm.bind(form, syncButtons);
    loadSubDepts();

    /* After a server-side validation error, open the step that has it */
    var hadServerErrors = !summary.hidden;
    var firstError = form.querySelector('.is-invalid, .invalid-feedback.d-block');
    stepper.show(firstError ? stepper.stepOf(firstError) : 0, false);
    if (hadServerErrors) { summary.hidden = false; summary.focus(); }
})();
</script>
@endpush