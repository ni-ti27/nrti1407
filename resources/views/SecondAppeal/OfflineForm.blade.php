{{-- ============================================================
     Second Appeal Form (Offline Request)  (DBIM / GIGW 3.0, keyboard accessible, six steps)
     Uses layouts/app.blade.php for <head>, header, <main>, footer
     (the footer now comes from the layout, UsersView.footer).
     Same pattern as the RTI New Request form: dbim-form-card, error summary,
     stepper, UsersView.partials.dbim-form-scripts. No inline styles, no jQuery.
     All original fields, names and ids are kept.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Second Appeal (Offline Request) | RTI Online, Government of Goa')

@section('content')
@php
    $star = '<span class="dbim-required" aria-hidden="true">*</span>';
@endphp
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-6">

            @include('partials.flash-messages')

            <div class="card dbim-form-card">
                <h1 class="card-header h4 mb-0">Second Appeal Form (Offline Request)</h1>

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
                            <strong>Note:</strong> Fill in this form in six steps. Use <strong>Next</strong> and
                            <strong>Back</strong> to move between steps; what you have entered is kept.
                        </p>
                        <p>
                            Fields marked with (<span class="dbim-required" aria-hidden="true">*</span>) are required.
                            The Submit button is enabled only when all required fields are complete.
                        </p>
                    </div>

                    <ol id="stepNav" class="dbim-stepper" aria-hidden="true"></ol>
                    <p id="stepCount" class="dbim-step-count" aria-live="polite"></p>

                    <form id="secondAppealOfflineForm" method="post" enctype="multipart/form-data"
                          action="{{ route('secondAppealOfflineSave') }}" novalidate>
                        @csrf

                        {{-- ===== Step 1: Public Authority Details ===== --}}
                        <section data-step aria-labelledby="step1Title">
                            <h2 id="step1Title" class="h5 dbim-text-key mb-3" tabindex="-1">Public authority details</h2>

                            <div class="mb-3">
                                <label for="applied_dept" class="form-label fw-bold">Select Department/Public Authority {!! $star !!}</label>
                                <select id="applied_dept" name="applied_dept"
                                        class="form-select @error('applied_dept') is-invalid @enderror"
                                        data-validate data-required="true" data-msg="Select a department or public authority."
                                        required aria-required="true" aria-describedby="applied_deptError">
                                    <option value="">Select Department</option>
                                    @foreach ($deptData as $id => $department)
                                        <option value="{{ $department->dept_code }}" {{ (string) old('applied_dept') === (string) $department->dept_code ? 'selected' : '' }}>{{ $department->department }}</option>
                                    @endforeach
                                </select>
                                <div id="applied_deptError" class="invalid-feedback @error('applied_dept') d-block @enderror">@error('applied_dept'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="applied_subdept" class="form-label fw-bold">Select Office {!! $star !!}</label>
                                <select id="applied_subdept" name="applied_subdept"
                                        class="form-select @error('applied_subdept') is-invalid @enderror"
                                        data-validate data-required="true" data-msg="Select an office."
                                        required aria-required="true" aria-describedby="applied_subdeptError">
                                    <option value="">Select Office</option>
                                </select>
                                <div id="applied_subdeptError" class="invalid-feedback @error('applied_subdept') d-block @enderror">@error('applied_subdept'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="applied_office" class="form-label fw-bold">Select Sub Office {!! $star !!}</label>
                                <select id="applied_office" name="applied_office"
                                        class="form-select @error('applied_office') is-invalid @enderror"
                                        data-validate data-required="true" data-msg="Select a sub office."
                                        required aria-required="true" aria-describedby="applied_officeError">
                                    <option value="">Select Sub Office</option>
                                </select>
                                <div id="applied_officeError" class="invalid-feedback @error('applied_office') d-block @enderror">@error('applied_office'){{ $message }}@enderror</div>
                            </div>
                        </section>

                        {{-- ===== Step 2: Personal Details of RTI Applicant ===== --}}
                        <section data-step aria-labelledby="step2Title" hidden>
                            <h2 id="step2Title" class="h5 dbim-text-key mb-3" tabindex="-1">Personal details of RTI applicant</h2>

                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">Applicant Name {!! $star !!}</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                       placeholder="Firstname Lastname" autocomplete="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       data-validate data-required="true" data-msg="Enter the applicant name."
                                       required aria-required="true" aria-describedby="nameError">
                                <div id="nameError" class="invalid-feedback @error('name') d-block @enderror">@error('name'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="gender" class="form-label fw-bold">Gender {!! $star !!}</label>
                                <select id="gender" name="gender"
                                        class="form-select @error('gender') is-invalid @enderror"
                                        required aria-required="true" aria-describedby="genderError">
                                    <option value="male" {{ old('gender', 'male') === 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                <div id="genderError" class="invalid-feedback @error('gender') d-block @enderror">@error('gender'){{ $message }}@enderror</div>
                            </div>

                            <fieldset class="mb-3">
                                <legend class="dbim-legend fw-bold">Address {!! $star !!}</legend>

                                <div class="mb-2">
                                    <label for="address_line1" class="visually-hidden">Address Line 1</label>
                                    <input type="text" id="address_line1" name="address_line1" value="{{ old('address_line1') }}"
                                           placeholder="Address Line 1" autocomplete="address-line1"
                                           class="form-control @error('address_line1') is-invalid @enderror"
                                           data-validate data-required="true" data-msg="Enter Address Line 1."
                                           required aria-required="true" aria-describedby="address_line1Error">
                                    <div id="address_line1Error" class="invalid-feedback @error('address_line1') d-block @enderror">@error('address_line1'){{ $message }}@enderror</div>
                                </div>
                                <div class="mb-2">
                                    <label for="address_line2" class="visually-hidden">Address Line 2</label>
                                    <input type="text" id="address_line2" name="address_line2" value="{{ old('address_line2') }}"
                                           placeholder="Address Line 2" autocomplete="address-line2"
                                           class="form-control @error('address_line2') is-invalid @enderror"
                                           data-validate data-required="true" data-msg="Enter Address Line 2."
                                           required aria-required="true" aria-describedby="address_line2Error">
                                    <div id="address_line2Error" class="invalid-feedback @error('address_line2') d-block @enderror">@error('address_line2'){{ $message }}@enderror</div>
                                </div>
                                <div class="mb-0">
                                    <label for="address_line3" class="visually-hidden">Address Line 3</label>
                                    <input type="text" id="address_line3" name="address_line3" value="{{ old('address_line3') }}"
                                           placeholder="Address Line 3" autocomplete="address-line3"
                                           class="form-control @error('address_line3') is-invalid @enderror"
                                           data-validate data-required="true" data-msg="Enter Address Line 3."
                                           required aria-required="true" aria-describedby="address_line3Error">
                                    <div id="address_line3Error" class="invalid-feedback @error('address_line3') d-block @enderror">@error('address_line3'){{ $message }}@enderror</div>
                                </div>
                            </fieldset>

                            <div class="mb-3">
                                <label for="pincode" class="form-label fw-bold">Pincode {!! $star !!}</label>
                                <input type="text" id="pincode" name="pincode" value="{{ old('pincode') }}"
                                       placeholder="Pin Code" inputmode="numeric" autocomplete="postal-code"
                                       class="form-control @error('pincode') is-invalid @enderror"
                                       data-validate data-required="true" data-msg="Enter the pincode."
                                       required aria-required="true" aria-describedby="pincodeError">
                                <div id="pincodeError" class="invalid-feedback @error('pincode') d-block @enderror">@error('pincode'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="country" class="form-label fw-bold">Country</label>
                                <select id="country" name="country" class="form-select @error('country') is-invalid @enderror"
                                        aria-describedby="countryError">
                                    <option value="1">India</option>
                                </select>
                                <div id="countryError" class="invalid-feedback @error('country') d-block @enderror">@error('country'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="state" class="form-label fw-bold">State {!! $star !!}</label>
                                <select id="state" name="state" class="form-select @error('state') is-invalid @enderror"
                                        required aria-required="true" aria-describedby="stateError">
                                    <option value="6" {{ (string) old('state', '6') === '6' ? 'selected' : '' }}>Goa</option>
                                    <option value="0" {{ (string) old('state') === '0' ? 'selected' : '' }}>Other</option>
                                </select>
                                <div id="stateError" class="invalid-feedback @error('state') d-block @enderror">@error('state'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="district" class="form-label fw-bold">District {!! $star !!}</label>
                                <select id="district" name="district" class="form-select @error('district') is-invalid @enderror"
                                        data-validate data-required="true" data-msg="Select a district."
                                        required aria-required="true" aria-describedby="districtError">
                                    <option value="">Select District</option>
                                </select>
                                <div id="districtError" class="invalid-feedback @error('district') d-block @enderror">@error('district'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="taluka" class="form-label fw-bold">Taluka {!! $star !!}</label>
                                <select id="taluka" name="taluka" class="form-select @error('taluka') is-invalid @enderror"
                                        data-validate data-required="true" data-msg="Select a taluka."
                                        required aria-required="true" aria-describedby="talukaError">
                                    <option value="">Select Taluka</option>
                                </select>
                                <div id="talukaError" class="invalid-feedback @error('taluka') d-block @enderror">@error('taluka'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="village" class="form-label fw-bold">Village {!! $star !!}</label>
                                <select id="village" name="village" class="form-select @error('village') is-invalid @enderror"
                                        data-validate data-required="true" data-msg="Select a village."
                                        required aria-required="true" aria-describedby="villageError">
                                    <option value="">Select Village</option>
                                </select>
                                <div id="villageError" class="invalid-feedback @error('village') d-block @enderror">@error('village'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="phone_no" class="form-label fw-bold">Phone Number</label>
                                <input type="tel" id="phone_no" name="phone_no" value="{{ old('phone_no') }}"
                                       placeholder="0832 2347874" autocomplete="tel"
                                       class="form-control @error('phone_no') is-invalid @enderror"
                                       aria-describedby="phone_noError">
                                <div id="phone_noError" class="invalid-feedback @error('phone_no') d-block @enderror">@error('phone_no'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="mobile_no" class="form-label fw-bold">Mobile Number {!! $star !!}</label>
                                <input type="tel" id="mobile_no" name="mobile_no" value="{{ old('mobile_no') }}"
                                       placeholder="+91 9865478864" autocomplete="tel"
                                       class="form-control @error('mobile_no') is-invalid @enderror"
                                       data-validate data-required="true" data-msg="Enter the mobile number."
                                       required aria-required="true" aria-describedby="mobile_noError">
                                <div id="mobile_noError" class="invalid-feedback @error('mobile_no') d-block @enderror">@error('mobile_no'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="email_id" class="form-label fw-bold">Email Address {!! $star !!}</label>
                                <input type="email" id="email_id" name="email_id" value="{{ old('email_id') }}"
                                       placeholder="name@example.com" autocomplete="email"
                                       class="form-control @error('email_id') is-invalid @enderror"
                                       data-validate data-required="true" data-msg="Enter the email address."
                                       required aria-required="true" aria-describedby="email_idError">
                                <div id="email_idError" class="invalid-feedback @error('email_id') d-block @enderror">@error('email_id'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="origin_country" class="form-label fw-bold">Citizenship</label>
                                <input type="text" id="origin_country" name="origin_country" value="Indian"
                                       readonly aria-readonly="true" class="form-control">
                            </div>

                            <fieldset class="mb-3">
                                <legend class="dbim-legend fw-bold">Education {!! $star !!}</legend>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="edu_status" id="edu_status" value="1"
                                           data-validate aria-describedby="edu_statusError" {{ (string) old('edu_status') === '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="edu_status">Educated</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="edu_status" id="edu_status2" value="0"
                                           {{ (string) old('edu_status') === '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="edu_status2">Non-Educated</label>
                                </div>
                                <div id="edu_statusError" class="invalid-feedback @error('edu_status') d-block @enderror">@error('edu_status'){{ $message }}@enderror</div>
                            </fieldset>

                            <fieldset class="mb-3">
                                <legend class="dbim-legend fw-bold">Is Applicant Below Poverty Line? {!! $star !!}</legend>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_bpl" id="is_not_bpl" value="0"
                                           data-validate aria-describedby="is_not_bplError" {{ (string) old('is_bpl') === '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_not_bpl">No</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_bpl" id="is_bpl" value="1"
                                           {{ (string) old('is_bpl') === '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_bpl">Yes</label>
                                </div>
                                <div id="is_not_bplError" class="invalid-feedback @error('is_bpl') d-block @enderror">@error('is_bpl'){{ $message }}@enderror</div>
                            </fieldset>
                        </section>

                        {{-- ===== Step 3: Details of RTI Application ===== --}}
                        <section data-step aria-labelledby="step3Title" hidden>
                            <h2 id="step3Title" class="h5 dbim-text-key mb-3" tabindex="-1">Details of RTI application</h2>

                            <div class="mb-3">
                                <label for="reg_no" class="form-label fw-bold">RTI Request Registration Number {!! $star !!}</label>
                                <input type="text" id="reg_no" name="reg_no" value="{{ old('reg_no') }}"
                                       placeholder="Requested Registration Number"
                                       class="form-control @error('reg_no') is-invalid @enderror"
                                       data-validate data-required="true" data-msg="Enter the RTI request registration number."
                                       required aria-required="true" aria-describedby="reg_noError">
                                <div id="reg_noError" class="invalid-feedback @error('reg_no') d-block @enderror">@error('reg_no'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="reg_date" class="form-label fw-bold">RTI Request Filed on (Date) {!! $star !!}</label>
                                <input type="date" id="reg_date" name="reg_date" value="{{ old('reg_date') }}" max="{{ date('Y-m-d') }}"
                                       class="form-control @error('reg_date') is-invalid @enderror"
                                       data-validate data-required="true" data-msg="Enter the date the RTI request was filed."
                                       required aria-required="true" aria-describedby="reg_dateError">
                                <div id="reg_dateError" class="invalid-feedback @error('reg_date') d-block @enderror">@error('reg_date'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="pio_name" class="form-label fw-bold">PIO Name {!! $star !!}</label>
                                {{-- Shown as a read-only box; the id the server expects goes in the hidden field --}}
                                <input type="hidden" id="pio_name_value" name="pio_name" value="{{ old('pio_name') }}">
                                <input type="text" id="pio_name" readonly aria-readonly="true"
                                       placeholder="Filled in automatically from the selected office"
                                       class="form-control @error('pio_name') is-invalid @enderror"
                                       data-validate data-required="true" data-msg="No PIO found for the selected office."
                                       required aria-required="true" aria-describedby="pio_nameError">
                                <div id="pio_nameError" class="invalid-feedback @error('pio_name') d-block @enderror">@error('pio_name'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="pio_address" class="form-label fw-bold">PIO Address</label>
                                <input type="hidden" id="pio_address_value" name="pio_address" value="{{ old('pio_address') }}">
                                <input type="text" id="pio_address" readonly aria-readonly="true"
                                       placeholder="Filled in automatically from the selected office"
                                       class="form-control @error('pio_address') is-invalid @enderror"
                                       aria-describedby="pio_addressError">
                                <div id="pio_addressError" class="invalid-feedback @error('pio_address') d-block @enderror">@error('pio_address'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="pio_response_display" class="form-label fw-bold">Nature of Response of PIO (Ground for Appeal) {!! $star !!}</label>
                                <select id="pio_response_display" name="pio_response_display"
                                        class="form-select @error('pio_response_display') is-invalid @enderror"
                                        data-validate data-required="true" data-msg="Select the ground for appeal for the RTI application."
                                        required aria-required="true" aria-describedby="pio_response_displayError">
                                    <option value="">Select Department</option>
                                    @foreach ($natureOfResponse as $id => $resp)
                                        <option value="{{ $resp->id }}" {{ (string) old('pio_response_display') === (string) $resp->id ? 'selected' : '' }}>{{ $resp->reason }}</option>
                                    @endforeach
                                </select>
                                <div id="pio_response_displayError" class="invalid-feedback @error('pio_response_display') d-block @enderror">@error('pio_response_display'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="rti_application_doc" class="form-label fw-bold">Copy of RTI Application (Only Pdf) {!! $star !!}</label>
                                <input type="file" id="rti_application_doc" name="rti_application_doc" accept="application/pdf,.pdf"
                                       class="form-control @error('rti_application_doc') is-invalid @enderror"
                                       data-validate data-required="true" data-msg="Choose the RTI application PDF."
                                       required aria-required="true" aria-describedby="rti_application_docHelp rti_application_docError">
                                <div id="rti_application_docHelp" class="form-text">Only PDF files up to 5 MB are accepted.</div>
                                <div id="rti_application_docError" class="invalid-feedback @error('rti_application_doc') d-block @enderror">@error('rti_application_doc'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="user_text" class="form-label fw-bold">Requested Text {!! $star !!}</label>
                                <textarea id="user_text" name="user_text" rows="7"
                                          class="form-control @error('user_text') is-invalid @enderror"
                                          data-validate data-required="true" data-msg="Enter the supporting text for the RTI application."
                                          required aria-required="true" aria-describedby="user_textError">{{ old('user_text') }}</textarea>
                                <div id="user_textError" class="invalid-feedback @error('user_text') d-block @enderror">@error('user_text'){{ $message }}@enderror</div>
                            </div>
                        </section>

                        {{-- ===== Step 4: Details of First Appeal Application ===== --}}
                        <section data-step aria-labelledby="step4Title" hidden>
                            <h2 id="step4Title" class="h5 dbim-text-key mb-3" tabindex="-1">Details of first appeal application</h2>

                            <div class="mb-3">
                                <label for="fa_reg_no" class="form-label fw-bold">RTI First Appeal Registration Number {!! $star !!}</label>
                                <input type="text" id="fa_reg_no" name="fa_reg_no" value="{{ old('fa_reg_no') }}"
                                       placeholder="Requested Registration Number"
                                       class="form-control @error('fa_reg_no') is-invalid @enderror"
                                       data-validate data-required="true" data-msg="Enter the first appeal registration number."
                                       required aria-required="true" aria-describedby="fa_reg_noError">
                                <div id="fa_reg_noError" class="invalid-feedback @error('fa_reg_no') d-block @enderror">@error('fa_reg_no'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="fa_reg_date" class="form-label fw-bold">RTI FA Request Filed on (Date) {!! $star !!}</label>
                                <input type="date" id="fa_reg_date" name="fa_reg_date" value="{{ old('fa_reg_date') }}"
                                       class="form-control @error('fa_reg_date') is-invalid @enderror"
                                       data-validate data-required="true" data-msg="Enter the date the first appeal was filed."
                                       required aria-required="true" aria-describedby="fa_reg_dateError">
                                <div id="fa_reg_dateError" class="invalid-feedback @error('fa_reg_date') d-block @enderror">@error('fa_reg_date'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="faa_name" class="form-label fw-bold">FAA User Name {!! $star !!}</label>
                                {{-- Shown as a read-only box; the id the server expects goes in the hidden field --}}
                                <input type="hidden" id="faa_name_value" name="faa_name" value="{{ old('faa_name') }}">
                                <input type="text" id="faa_name" readonly aria-readonly="true"
                                       placeholder="Filled in automatically from the selected office"
                                       class="form-control @error('faa_name') is-invalid @enderror"
                                       data-validate data-required="true" data-msg="No FAA user found for the selected office."
                                       required aria-required="true" aria-describedby="faa_nameError">
                                <div id="faa_nameError" class="invalid-feedback @error('faa_name') d-block @enderror">@error('faa_name'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="faa_address" class="form-label fw-bold">FAA User Address {!! $star !!}</label>
                                <input type="hidden" id="faa_address_value" name="faa_address" value="{{ old('faa_address') }}">
                                <input type="text" id="faa_address" readonly aria-readonly="true"
                                       placeholder="Filled in automatically from the selected office"
                                       class="form-control @error('faa_address') is-invalid @enderror"
                                       data-validate data-required="true" data-msg="No FAA user address found for the selected office."
                                       required aria-required="true" aria-describedby="faa_addressError">
                                <div id="faa_addressError" class="invalid-feedback @error('faa_address') d-block @enderror">@error('faa_address'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="fa_application_doc" class="form-label fw-bold">Copy of Reply of FA (Only Pdf) {!! $star !!}</label>
                                <input type="file" id="fa_application_doc" name="fa_application_doc" accept="application/pdf,.pdf"
                                       class="form-control @error('fa_application_doc') is-invalid @enderror"
                                       data-validate data-required="true" data-msg="Choose the reply of FA PDF."
                                       required aria-required="true" aria-describedby="fa_application_docHelp fa_application_docError">
                                <div id="fa_application_docHelp" class="form-text">Only PDF files up to 5 MB are accepted.</div>
                                <div id="fa_application_docError" class="invalid-feedback @error('fa_application_doc') d-block @enderror">@error('fa_application_doc'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="faa_requested_text" class="form-label fw-bold">Requested Text {!! $star !!}</label>
                                <textarea id="faa_requested_text" name="faa_requested_text" rows="6"
                                          class="form-control @error('faa_requested_text') is-invalid @enderror"
                                          data-validate data-required="true" data-msg="Enter the requested text."
                                          required aria-required="true" aria-describedby="faa_requested_textError">{{ old('faa_requested_text') }}</textarea>
                                <div id="faa_requested_textError" class="invalid-feedback @error('faa_requested_text') d-block @enderror">@error('faa_requested_text'){{ $message }}@enderror</div>
                            </div>
                        </section>

                        {{-- ===== Step 5: Second Appeal Details + Applicant Personal Information ===== --}}
                        <section data-step aria-labelledby="step5Title" hidden>
                            <h2 id="step5Title" class="h5 dbim-text-key mb-3" tabindex="-1">Second appeal details</h2>

                            <div class="mb-3">
                                <label for="faa_resp_dropdown" class="form-label fw-bold">Nature of Response of FAA (Ground for Appeal) {!! $star !!}</label>
                                <select id="faa_resp_dropdown" name="faa_resp_dropdown"
                                        class="form-select @error('faa_resp_dropdown') is-invalid @enderror"
                                        data-validate data-required="true" data-msg="Select a response from the list."
                                        required aria-required="true" aria-describedby="faa_resp_dropdownError">
                                    <option value="">Select Response</option>
                                    <option value="1" {{ old('faa_resp_dropdown') == '1' ? 'selected' : '' }}>Refused access to the Information Requested</option>
                                    <option value="2" {{ old('faa_resp_dropdown') == '2' ? 'selected' : '' }}>No Response within the Time Limit</option>
                                    <option value="3" {{ old('faa_resp_dropdown') == '3' ? 'selected' : '' }}>Unreasonable Amount of Fee is Required to Pay</option>
                                    <option value="4" {{ old('faa_resp_dropdown') == '4' ? 'selected' : '' }}>Provided Incomplete, Misleading or False Information</option>
                                    <option value="5" {{ old('faa_resp_dropdown') == '5' ? 'selected' : '' }}>Any Other Ground</option>
                                </select>
                                <div id="faa_resp_dropdownError" class="invalid-feedback @error('faa_resp_dropdown') d-block @enderror">@error('faa_resp_dropdown'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="sa_application_doc" class="form-label fw-bold">Copy of Reply of FA (Only Pdf) {!! $star !!}</label>
                                <input type="file" id="sa_application_doc" name="sa_application_doc" accept="application/pdf,.pdf"
                                       class="form-control @error('sa_application_doc') is-invalid @enderror"
                                       data-validate data-required="true" data-msg="Choose the PDF file."
                                       required aria-required="true" aria-describedby="sa_application_docHelp sa_application_docError">
                                <div id="sa_application_docHelp" class="form-text">Only PDF files up to 5 MB are accepted.</div>
                                <div id="sa_application_docError" class="invalid-feedback @error('sa_application_doc') d-block @enderror">@error('sa_application_doc'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="sa_document1" class="form-label fw-bold">Supporting Document 1</label>
                                <input type="file" id="sa_document1" name="sa_document1"
                                       class="form-control @error('sa_document1') is-invalid @enderror"
                                       data-validate aria-describedby="sa_document1Help sa_document1Error">
                                <div id="sa_document1Help" class="form-text">File type .pdf, .doc, .docx, .txt, .xls, .xlsx, .jpg, .jpeg</div>
                                <div id="sa_document1Error" class="invalid-feedback @error('sa_document1') d-block @enderror">@error('sa_document1'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="sa_document2" class="form-label fw-bold">Supporting Document 2</label>
                                <input type="file" id="sa_document2" name="sa_document2"
                                       class="form-control @error('sa_document2') is-invalid @enderror"
                                       data-validate aria-describedby="sa_document2Help sa_document2Error">
                                <div id="sa_document2Help" class="form-text">File type .pdf, .doc, .docx, .txt, .xls, .xlsx, .jpg, .jpeg</div>
                                <div id="sa_document2Error" class="invalid-feedback @error('sa_document2') d-block @enderror">@error('sa_document2'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="sa_document1_supporting_text" class="form-label fw-bold">Supporting Text for Document 1</label>
                                <div id="sa_document1_supporting_textHelp" class="form-text mb-1">Only alphabets A-Z a-z, numbers 0-9 and special characters , . - _ ( ) / @ : &amp; \ % are allowed in Text for RTI Request application. Required when Supporting Document 1 is attached.</div>
                                <textarea id="sa_document1_supporting_text" name="sa_document1_supporting_text" rows="4" maxlength="3000"
                                          class="form-control @error('sa_document1_supporting_text') is-invalid @enderror"
                                          data-validate
                                          aria-describedby="sa_document1_supporting_textHelp sa_document1_supporting_textError">{{ old('sa_document1_supporting_text') }}</textarea>
                                <div id="sa_document1_supporting_textError" class="invalid-feedback @error('sa_document1_supporting_text') d-block @enderror">@error('sa_document1_supporting_text'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="sa_document2_supporting_text" class="form-label fw-bold">Supporting Text for Document 2</label>
                                <div id="sa_document2_supporting_textHelp" class="form-text mb-1">Only alphabets A-Z a-z, numbers 0-9 and special characters , . - _ ( ) / @ : &amp; \ % are allowed in Text for RTI Request application. Required when Supporting Document 2 is attached.</div>
                                <textarea id="sa_document2_supporting_text" name="sa_document2_supporting_text" rows="4" maxlength="3000"
                                          class="form-control @error('sa_document2_supporting_text') is-invalid @enderror"
                                          data-validate
                                          aria-describedby="sa_document2_supporting_textHelp sa_document2_supporting_textError">{{ old('sa_document2_supporting_text') }}</textarea>
                                <div id="sa_document2_supporting_textError" class="invalid-feedback @error('sa_document2_supporting_text') d-block @enderror">@error('sa_document2_supporting_text'){{ $message }}@enderror</div>
                            </div>

                            <h3 class="h6 dbim-text-key mt-4 mb-3">Applicant personal information</h3>

                            <div class="mb-3">
                                <label for="id_proof" class="form-label fw-bold">Id Proof {!! $star !!}</label>
                                <select id="id_proof" name="id_proof" class="form-select @error('id_proof') is-invalid @enderror"
                                        data-validate data-required="true" data-msg="Select the ID proof document type."
                                        required aria-required="true" aria-describedby="id_proofError">
                                    <option value="">Select Document</option>
                                    <option value="2" {{ old('id_proof') == '2' ? 'selected' : '' }}>Pan Card</option>
                                    <option value="3" {{ old('id_proof') == '3' ? 'selected' : '' }}>Driving Licence</option>
                                    <option value="4" {{ old('id_proof') == '4' ? 'selected' : '' }}>Ration Card</option>
                                    <option value="5" {{ old('id_proof') == '5' ? 'selected' : '' }}>Govt. Id Card (if any)</option>
                                    <option value="5" {{ old('id_proof') == '6' ? 'selected' : '' }}>Other</option>
                                </select>
                                <div id="id_proofError" class="invalid-feedback @error('id_proof') d-block @enderror">@error('id_proof'){{ $message }}@enderror</div>
                            </div>

                            <div class="mb-3">
                                <label for="id_proof_file" class="form-label fw-bold">ID Proof File {!! $star !!}</label>
                                <input type="file" id="id_proof_file" name="id_proof_file" accept=".pdf,.jpg,.jpeg"
                                       class="form-control @error('id_proof_file') is-invalid @enderror"
                                       data-validate data-required="true" data-msg="Choose the ID proof file."
                                       required aria-required="true" aria-describedby="id_proof_fileHelp id_proof_fileError">
                                <div id="id_proof_fileHelp" class="form-text">File type .pdf, .jpg, .jpeg</div>
                                <div id="id_proof_fileError" class="invalid-feedback @error('id_proof_file') d-block @enderror">@error('id_proof_file'){{ $message }}@enderror</div>
                            </div>
                        </section>

                        {{-- ===== Step 6: Declaration ===== --}}
                        <section data-step aria-labelledby="step6Title" hidden>
                            <h2 id="step6Title" class="h5 dbim-text-key mb-3" tabindex="-1">Declaration</h2>

                            <div class="form-check mb-3">
                                <input type="checkbox" id="declaration" name="declaration" class="form-check-input"
                                       data-validate aria-describedby="declarationError">
                                <label class="form-check-label" for="declaration">The Information as provided above is true to the best of my knowledge {!! $star !!}</label>
                                <div id="declarationError" class="invalid-feedback @error('declaration') d-block @enderror">@error('declaration'){{ $message }}@enderror</div>
                            </div>

                            <x-captcha />
                        </section>

                        {{-- ===== Actions ===== --}}
                        <div class="d-flex justify-content-end flex-wrap gap-2">
                            <button type="reset" class="download-outline-btn text-uppercase w-ft" title="Reset">Reset</button>
                            <button type="button" id="btnBack" class="download-outline-btn text-uppercase w-ft" title="Back" hidden>Back</button>
                            <button type="button" id="btnNext" class="download-btn text-uppercase w-ft" title="Next">Next</button>
                            <button type="submit" id="submit_button" name="submit_button" class="download-btn text-uppercase w-ft" title="Submit" hidden>Submit</button>
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
    var form = $('secondAppealOfflineForm');
    var backBtn = $('btnBack'), nextBtn = $('btnNext'), submitBtn = $('submit_button');
    var statusEl = $('submitStatus');
    var summary = $('errorSummary');
    var busy = false;

    /* ---------- field rules ---------- */
    var TEXT_ALLOWED = /^[A-Za-z0-9\s,.\-_()\/\u0040:&\\%]+$/;
    var TEXT_MSG = 'The text has characters that are not allowed. Use only letters, numbers, spaces and , . - _ ( ) / @ : & \\ %';

    function fileRule(extensions, maxMb, typeMsg) {
        return function (el) {
            var file = el.files && el.files[0];
            if (!file) { return ''; }
            var ext = file.name.split('.').pop().toLowerCase();
            if (extensions.indexOf(ext) === -1) { return typeMsg; }
            if (maxMb && file.size > maxMb * 1024 * 1024) { return 'File size should not exceed ' + maxMb + 'MB.'; }
            return '';
        };
    }

    var pdfRule = fileRule(['pdf'], 5, 'Only PDF files are allowed.');
    $('rti_application_doc').dataCheck = pdfRule;
    $('fa_application_doc').dataCheck = pdfRule;
    $('sa_application_doc').dataCheck = pdfRule;

    var supportRule = fileRule(['pdf', 'doc', 'docx', 'txt', 'xls', 'xlsx', 'jpg', 'jpeg'], 0,
        'Only PDF, DOC, DOCX, TXT, XLS, XLSX, JPG, and JPEG files are allowed.');
    $('sa_document1').dataCheck = supportRule;
    $('sa_document2').dataCheck = supportRule;

    $('id_proof_file').dataCheck = fileRule(['pdf', 'jpg', 'jpeg'], 0, 'Only PDF, JPG, and JPEG files are allowed.');

    function supportingText(docId, number) {
        return function (el) {
            var hasFile = $(docId).files && $(docId).files[0];
            if (hasFile && el.value.trim() === '') { return 'Enter text for Supporting Document ' + number + '.'; }
            if (el.value.trim() !== '' && !TEXT_ALLOWED.test(el.value)) { return TEXT_MSG; }
            return '';
        };
    }
    $('sa_document1_supporting_text').dataCheck = supportingText('sa_document1', 1);
    $('sa_document2_supporting_text').dataCheck = supportingText('sa_document2', 2);

    $('edu_status').dataCheck = function () {
        return form.querySelector('input[name="edu_status"]:checked') ? '' : 'Select your education status.';
    };
    $('is_not_bpl').dataCheck = function () {
        return form.querySelector('input[name="is_bpl"]:checked') ? '' : 'Select your financial status.';
    };
    $('declaration').dataCheck = function (el) {
        return el.checked ? '' : 'Confirm the declaration.';
    };

    /* ---------- dropdown helpers ---------- */
    var initialPending = {
        sub: @json((string) old('applied_subdept')),
        office: @json((string) old('applied_office')),
        pio: @json((string) old('pio_name')),
        faa: @json((string) old('faa_name')),
        district: @json((string) old('district')),
        taluka: @json((string) old('taluka')),
        village: @json((string) old('village'))
    };
    var pending = Object.assign({}, initialPending);

    function option(value, text) {
        var o = document.createElement('option');
        o.value = value;
        o.textContent = text;
        return o;
    }
    function fail(err) { console.error('Dropdown request failed', err); }

    function fill(selectId, rows, valueKey, textKey, selectedValue) {
        var sel = $(selectId);
        sel.textContent = '';
        Object.keys(rows).forEach(function (k) {
            var o = option(rows[k][valueKey], rows[k][textKey]);
            if (selectedValue && String(rows[k][valueKey]) === selectedValue) { o.selected = true; }
            sel.appendChild(o);
        });
    }
    function reset(selectId, text) {
        var sel = $(selectId);
        sel.textContent = '';
        sel.appendChild(option('', text));
    }

    /* ---------- authority dropdowns ---------- */
    function loadPioAndFaa() {
        var deptId = $('applied_dept').value, subId = $('applied_subdept').value;
        if (!deptId || !subId) {
            setPio(null);
            setFaa(null);
            syncButtons();
            return;
        }
        DbimForm.getJSON('/pioDropdown/' + deptId + '/' + subId).then(function (rows) {
            var keys = Object.keys(rows);
            var chosen = null;
            keys.forEach(function (k) {
                if (pending.pio && String(rows[k].id) === pending.pio) { chosen = rows[k]; }
            });
            if (!chosen && keys.length) { chosen = rows[keys[0]]; }   /* otherwise the first PIO */
            setPio(chosen);
            pending.pio = '';
            syncButtons();
        }).catch(fail);

        DbimForm.getJSON('/faaDropdown/' + deptId + '/' + subId).then(function (rows) {
            var keys = Object.keys(rows);
            var chosen = null;
            keys.forEach(function (k) {
                if (pending.faa && String(rows[k].id) === pending.faa) { chosen = rows[k]; }
            });
            if (!chosen && keys.length) { chosen = rows[keys[0]]; }   /* otherwise the first FAA user */
            setFaa(chosen);
            pending.faa = '';
            syncButtons();
        }).catch(fail);
    }

    /* PIO name and address are read-only boxes; the hidden fields carry the id */
    function setPio(row) {
        $('pio_name_value').value    = row ? row.id : '';
        $('pio_address_value').value = row ? row.id : '';
        $('pio_name').value          = row ? row.name : '';
        $('pio_address').value       = row ? row.address1 : '';
    }

    /* FAA name and address are read-only boxes; the hidden fields carry the id */
    function setFaa(row) {
        $('faa_name_value').value    = row ? row.id : '';
        $('faa_address_value').value = row ? row.id : '';
        $('faa_name').value          = row ? row.name : '';
        $('faa_address').value       = row ? row.address1 : '';
    }

    function loadOffices() {
        var deptId = $('applied_dept').value, subId = $('applied_subdept').value;
        if (deptId && subId) {
            DbimForm.getJSON('/officeDropdown/' + deptId + '/' + subId).then(function (rows) {
                fill('applied_office', rows, 'suboffice_code', 'department', pending.office);
                pending.office = '';
                loadPioAndFaa();
            }).catch(fail);
        } else {
            reset('applied_office', 'Select Sub Office');
            loadPioAndFaa();
        }
    }

    function loadSubDepts() {
        var deptId = $('applied_dept').value;
        if (deptId) {
            DbimForm.getJSON('/subDeptDropdown/' + deptId).then(function (rows) {
                fill('applied_subdept', rows, 'office_code', 'department', pending.sub);
                pending.sub = '';
                loadOffices();
            }).catch(fail);
        } else {
            reset('applied_subdept', 'Select Office');
            loadOffices();
        }
    }

    $('applied_dept').addEventListener('change', loadSubDepts);
    $('applied_subdept').addEventListener('change', loadOffices);

    /* ---------- location dropdowns ---------- */
    function loadVillages() {
        var talukaId = $('taluka').value;
        if (talukaId === '0') {
            $('village').textContent = '';
            $('village').appendChild(option('0', 'Others'));
            syncButtons();
        } else if (talukaId) {
            DbimForm.getJSON('/villageDropdown/' + talukaId).then(function (rows) {
                fill('village', rows, 'id', 'name', pending.village);
                pending.village = '';
                syncButtons();
            }).catch(fail);
        } else {
            $('village').textContent = '';
            syncButtons();
        }
    }

    function loadTalukas() {
        var districtId = $('district').value;
        if (districtId === '0') {
            $('taluka').textContent = '';
            $('taluka').appendChild(option('0', 'Others'));
            loadVillages();
        } else if (districtId) {
            DbimForm.getJSON('/talukaDropdown/' + districtId).then(function (rows) {
                fill('taluka', rows, 'id', 'name', pending.taluka);
                pending.taluka = '';
                loadVillages();
            }).catch(fail);
        } else {
            $('taluka').textContent = '';
            loadVillages();
        }
    }

    function loadDistricts() {
        var stateId = $('state').value;
        if (stateId === '0') {
            $('district').textContent = '';
            $('district').appendChild(option('0', 'Others'));
            loadTalukas();
        } else if (stateId) {
            DbimForm.getJSON('/districtDropdown/' + stateId).then(function (rows) {
                fill('district', rows, 'id', 'name', pending.district);
                pending.district = '';
                loadTalukas();
            }).catch(fail);
        } else {
            reset('district', 'Select District');
            loadTalukas();
        }
    }

    $('state').addEventListener('change', loadDistricts);
    $('district').addEventListener('change', loadTalukas);
    $('taluka').addEventListener('change', loadVillages);

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

    /* ---------- reset (with warning) ---------- */
    form.addEventListener('reset', function (event) {
        if (!window.confirm('Clear everything you have entered and start again?')) {
            event.preventDefault();
            return;
        }
        setTimeout(function () {
            DbimForm.clearAll(form);
            pending = Object.assign({}, initialPending);
            loadSubDepts();
            loadDistricts();
            stepper.show(0, false);
        }, 0);
    });

    /* ---------- start ---------- */
    DbimForm.bind(form, syncButtons);
    loadSubDepts();
    loadDistricts();

    /* After a server-side validation error, open the step that has it */
    var hadServerErrors = !summary.hidden;
    var firstError = form.querySelector('.is-invalid, .invalid-feedback.d-block');
    stepper.show(firstError ? stepper.stepOf(firstError) : 0, false);
    if (hadServerErrors) { summary.hidden = false; summary.focus(); }
})();
</script>
@endpush