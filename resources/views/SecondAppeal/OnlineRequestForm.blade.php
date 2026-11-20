{{-- ============================================================
     Second Appeal Form (Online Request)  (DBIM / GIGW 3.0, keyboard accessible, five steps)
     Same layout as the RTI New Request form: note on the left, form card on the right.
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     Styles: section 65 / 65b in public/css/dbim.css (.dbim-fa-*)
     No inline styles, no jQuery.
     Pre-filled data is shown in read-only boxes. Where the server needs the value,
     a hidden field with the original name carries it.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Second Appeal (Online Request) | RTI Online, Government of Goa')

@section('content')
@php
    $star = '<span class="dbim-required" aria-hidden="true">*</span>';

    $submitReq = $data['submitReq'];
    $genderText = ucfirst($faRequestDetails->gender ?? '');
    $eduValue = isset($submitReq->edu_status) ? (int) $submitReq->edu_status : '';
    $eduText = $eduValue === 1 ? 'Educated' : ($eduValue === 0 ? 'Non-Educated' : '');
    $bplValue = (int) ($submitReq->is_bpl ?? 0);
    $bplText = $bplValue === 1 ? 'Yes' : 'No';
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
                        <li>Details from your RTI request and first appeal are filled in for you and cannot be changed.</li>
                        <li>The Submit button is enabled only when all required fields are complete.</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Right column: form card --}}
        <div class="col-12 col-lg-8">
            <section class="dbim-fa-card" aria-labelledby="pageTitle">

                <h1 id="pageTitle" class="dbim-fa-title">Second Appeal Form (Online Request)</h1>
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

                <form id="secondAppealOnlineForm" name="sa-online" method="post" enctype="multipart/form-data"
                      action="{{ route('secondAppealOnlineSave') }}" novalidate>
                    @csrf

                    {{-- ===== Step 1: Public authority and applicant (read-only) ===== --}}
                    <section data-step aria-labelledby="step1Title">
                        <h2 id="step1Title" class="dbim-fa-card__title" tabindex="-1">Public authority and applicant details</h2>

                        <div class="dbim-fa-field">
                            <label for="applied_dept_text" class="dbim-fa-label">Department or public authority</label>
                            <input type="hidden" id="applied_dept" name="applied_dept" value="{{ $data['deptData']['applied_dept']->dept_code }}">
                            <input type="text" id="applied_dept_text" class="form-control" readonly aria-readonly="true"
                                   value="{{ $data['deptData']['applied_dept']->department }}">
                        </div>

                        <div class="dbim-fa-field">
                            <label for="applied_subdept_text" class="dbim-fa-label">Office</label>
                            <input type="hidden" id="applied_subdept" name="applied_subdept" value="{{ $data['deptData']['applied_subdept']->office_code }}">
                            <input type="text" id="applied_subdept_text" class="form-control" readonly aria-readonly="true"
                                   value="{{ $data['deptData']['applied_subdept']->department }}">
                        </div>

                        <div class="dbim-fa-field">
                            <label for="applied_office_text" class="dbim-fa-label">Sub-office</label>
                            <input type="hidden" id="applied_office" name="applied_office" value="{{ $data['deptData']['applied_office']->suboffice_code }}">
                            <input type="text" id="applied_office_text" class="form-control" readonly aria-readonly="true"
                                   value="{{ $data['deptData']['applied_office']->department }}">
                        </div>

                        <div class="dbim-fa-field">
                            <label for="name" class="dbim-fa-label">Applicant name</label>
                            <input type="text" id="name" name="name" class="form-control" readonly aria-readonly="true"
                                   value="{{ $faRequestDetails->name ?? '' }}">
                        </div>

                        <div class="dbim-fa-field">
                            <label for="gender_text" class="dbim-fa-label">Gender</label>
                            <input type="hidden" id="gender" name="gender" value="{{ $faRequestDetails->gender ?? '' }}">
                            <input type="text" id="gender_text" class="form-control" readonly aria-readonly="true" value="{{ $genderText }}">
                        </div>

                        <fieldset class="dbim-fa-field">
                            <legend class="dbim-fa-label dbim-fa-legend-label">Address</legend>
                            <div class="mb-2">
                                <label for="address_line1" class="visually-hidden">Address Line 1</label>
                                <input type="text" id="address_line1" name="address_line1" class="form-control" readonly aria-readonly="true"
                                       value="{{ $faRequestDetails->address_line1 ?? '' }}">
                            </div>
                            <div class="mb-2">
                                <label for="address_line2" class="visually-hidden">Address Line 2</label>
                                <input type="text" id="address_line2" name="address_line2" class="form-control" readonly aria-readonly="true"
                                       value="{{ $faRequestDetails->address_line2 ?? '' }}">
                            </div>
                            <div class="mb-0">
                                <label for="address_line3" class="visually-hidden">Address Line 3</label>
                                <input type="text" id="address_line3" name="address_line3" class="form-control" readonly aria-readonly="true"
                                       value="{{ $faRequestDetails->address_line3 ?? '' }}">
                            </div>
                        </fieldset>

                        <div class="dbim-fa-field dbim-field-sm">
                            <label for="pincode" class="dbim-fa-label">Pincode</label>
                            <input type="text" id="pincode" name="pincode" class="form-control" readonly aria-readonly="true"
                                   value="{{ $faRequestDetails->pincode ?? '' }}">
                        </div>

                        <div class="dbim-fa-field">
                            <label for="country_text" class="dbim-fa-label">Country</label>
                            <input type="hidden" id="country" name="country" value="1">
                            <input type="text" id="country_text" class="form-control" readonly aria-readonly="true" value="India">
                        </div>

                        <div class="dbim-fa-field">
                            <label for="state_text" class="dbim-fa-label">State</label>
                            <input type="hidden" id="state" name="state" value="{{ $data['stateData']->id }}">
                            <input type="text" id="state_text" class="form-control" readonly aria-readonly="true" value="{{ $data['stateData']->name }}">
                        </div>

                        <div class="dbim-fa-field">
                            <label for="district_text" class="dbim-fa-label">District</label>
                            <input type="hidden" id="district" name="district" value="{{ $data['district'] ? $data['district']->id : 0 }}">
                            <input type="text" id="district_text" class="form-control" readonly aria-readonly="true"
                                   value="{{ $data['district'] ? $data['district']->name : 'Others' }}">
                        </div>

                        <div class="dbim-fa-field">
                            <label for="taluka_text" class="dbim-fa-label">Taluka</label>
                            <input type="hidden" id="taluka" name="taluka" value="{{ $data['taluka'] ? $data['taluka']->id : 0 }}">
                            <input type="text" id="taluka_text" class="form-control" readonly aria-readonly="true"
                                   value="{{ $data['taluka'] ? $data['taluka']->name : 'Others' }}">
                        </div>

                        <div class="dbim-fa-field">
                            <label for="village_text" class="dbim-fa-label">Village</label>
                            <input type="hidden" id="village" name="village" value="{{ $data['village'] ? $data['village']->id : 0 }}">
                            <input type="text" id="village_text" class="form-control" readonly aria-readonly="true"
                                   value="{{ $data['village'] ? $data['village']->name : 'Others' }}">
                        </div>

                        <div class="dbim-fa-field">
                            <label for="phone_no" class="dbim-fa-label">Phone number</label>
                            <input type="text" id="phone_no" name="phone_no" class="form-control" readonly aria-readonly="true"
                                   value="{{ $faRequestDetails->phone_no ?? '' }}">
                        </div>

                        <div class="dbim-fa-field">
                            <label for="mobile_no" class="dbim-fa-label">Mobile number</label>
                            <input type="text" id="mobile_no" name="mobile_no" class="form-control" readonly aria-readonly="true"
                                   value="{{ $faRequestDetails->mobile_no ?? '' }}">
                        </div>

                        <div class="dbim-fa-field">
                            <label for="email_id" class="dbim-fa-label">Email address</label>
                            <input type="email" id="email_id" name="email_id" class="form-control" readonly aria-readonly="true"
                                   value="{{ $faRequestDetails->email_id ?? '' }}">
                        </div>

                        <div class="dbim-fa-field">
                            <label for="origin_country" class="dbim-fa-label">Citizenship</label>
                            <input type="text" id="origin_country" name="origin_country" class="form-control" readonly aria-readonly="true" value="Indian">
                        </div>

                        <div class="dbim-fa-field">
                            <label for="edu_status_text" class="dbim-fa-label">Education</label>
                            <input type="hidden" id="edu_status" name="edu_status" value="{{ $eduValue }}">
                            <input type="text" id="edu_status_text" class="form-control" readonly aria-readonly="true" value="{{ $eduText }}">
                        </div>

                        <div class="dbim-fa-field">
                            <label for="is_bpl_text" class="dbim-fa-label">Is the applicant below the poverty line (BPL)?</label>
                            <input type="hidden" id="is_bpl" name="is_bpl" value="{{ $bplValue }}">
                            <input type="text" id="is_bpl_text" class="form-control" readonly aria-readonly="true" value="{{ $bplText }}">
                        </div>
                    </section>

                    {{-- ===== Step 2: Details of RTI application (read-only) ===== --}}
                    <section data-step aria-labelledby="step2Title" hidden>
                        <h2 id="step2Title" class="dbim-fa-card__title" tabindex="-1">Details of RTI application</h2>

                        <div class="dbim-fa-field">
                            <label for="reg_no" class="dbim-fa-label">RTI request registration number</label>
                            <input type="text" id="reg_no" name="reg_no" class="form-control" readonly aria-readonly="true"
                                   value="{{ $submitReq->reg_no ?? '' }}">
                        </div>

                        <div class="dbim-fa-field">
                            <label for="reg_date" class="dbim-fa-label">RTI request filed on (date)</label>
                            <input type="text" id="reg_date" class="form-control" readonly aria-readonly="true"
                                   value="{{ isset($submitReq->created_at) ? $submitReq->created_at->format('d/m/Y') : '' }}">
                            <input type="hidden" id="hidden_reg_date" name="hidden_reg_date" value="{{ $submitReq->created_at }}">
                        </div>

                        <div class="dbim-fa-field">
                            <label for="pio_name" class="dbim-fa-label">PIO name</label>
                            <input type="text" id="pio_name" name="pio_name" class="form-control" readonly aria-readonly="true"
                                   value="{{ $faRequestDetails->pioname ?? '' }}">
                            <input type="hidden" id="pio_id" name="pio_id" value="{{ $faRequestDetails->id }}">
                        </div>

                        <div class="dbim-fa-field">
                            <label for="pio_address" class="dbim-fa-label">PIO address</label>
                            <input type="text" id="pio_address" name="pio_address" class="form-control" readonly aria-readonly="true"
                                   value="{{ $faRequestDetails->pioaddr ?? '' }}">
                        </div>

                        <div class="dbim-fa-field">
                            <label for="pio_response_reason" class="dbim-fa-label">Nature of response of PIO (ground for appeal)</label>
                            <input type="text" id="pio_response_reason" name="pio_response_reason" class="form-control" readonly aria-readonly="true"
                                   value="{{ $faRequestDetails->pioResponseData['reason'] ?? '' }}">
                            <input type="hidden" id="pio_response_display" name="pio_response_display" value="{{ $faRequestDetails->pioResponseData['id'] }}">
                        </div>

                        <div class="dbim-fa-field">
                            <span class="dbim-fa-label">Copy of the RTI application (PDF)</span>
                            <p class="mb-0">
                                <a href="{{ asset($data['filePath']) }}" target="_blank" rel="noopener">{{ $submitReq->document_name_original }}<span class="dbim-sr-only"> (opens in a new tab)</span></a>
                            </p>
                        </div>

                        <div class="dbim-fa-field">
                            <label for="user_text" class="dbim-fa-label">Requested text</label>
                            <textarea id="user_text" name="user_text" rows="7" class="form-control" readonly aria-readonly="true">{{ $submitReq->request_text ?? '' }}</textarea>
                        </div>
                    </section>

                    {{-- ===== Step 3: Details of first appeal application (read-only) ===== --}}
                    <section data-step aria-labelledby="step3Title" hidden>
                        <h2 id="step3Title" class="dbim-fa-card__title" tabindex="-1">Details of first appeal application</h2>

                        <div class="dbim-fa-field">
                            <label for="fa_reg_no" class="dbim-fa-label">RTI first appeal registration number</label>
                            <input type="text" id="fa_reg_no" name="fa_reg_no" class="form-control" readonly aria-readonly="true"
                                   value="{{ $faRequestDetails->fa_reg_no ?? '' }}">
                        </div>

                        <div class="dbim-fa-field">
                            <label for="fa_reg_date" class="dbim-fa-label">RTI first appeal filed on (date)</label>
                            <input type="text" id="fa_reg_date" class="form-control" readonly aria-readonly="true"
                                   value="{{ isset($faRequestDetails->created_at) ? $faRequestDetails->created_at->format('d/m/Y') : '' }}">
                            <input type="hidden" id="hidden_faa_date" name="hidden_faa_date" value="{{ $faRequestDetails->created_at }}">
                        </div>

                        <div class="dbim-fa-field">
                            <label for="faa_name_text" class="dbim-fa-label">FAA user name</label>
                            <input type="hidden" id="faa_name" name="faa_name" value="{{ $requestStatus->name ?? '' }}">
                            <input type="text" id="faa_name_text" class="form-control" readonly aria-readonly="true" value="{{ $requestStatus->name ?? '' }}">
                            <input type="hidden" id="faa_id" name="faa_id" value="{{ $requestStatus->id }}">
                        </div>

                        <div class="dbim-fa-field">
                            <label for="faa_address_text" class="dbim-fa-label">FAA user address</label>
                            <input type="hidden" id="faa_address" name="faa_address" value="{{ $requestStatus->address1 ?? '' }}">
                            <input type="text" id="faa_address_text" class="form-control" readonly aria-readonly="true" value="{{ $requestStatus->address1 ?? '' }}">
                        </div>

                        @if(isset($filePaths['First Appeal']))
                            <div class="dbim-fa-field">
                                <span class="dbim-fa-label">FAA document (PDF)</span>
                                <p class="mb-0">
                                    <a href="{{ asset($filePaths['First Appeal']) }}" target="_blank" rel="noopener">First Appeal Document<span class="dbim-sr-only"> (opens in a new tab)</span></a>
                                </p>
                            </div>
                        @endif

                        @if(!empty($filePaths['Support']))
                            <div class="dbim-fa-field">
                                <span class="dbim-fa-label">FAA support document (PDF)</span>
                                <p class="mb-0">
                                    <a href="{{ asset($filePaths['Support']) }}" target="_blank" rel="noopener">First Appeal Support Document<span class="dbim-sr-only"> (opens in a new tab)</span></a>
                                </p>
                            </div>
                        @endif

                        <div class="dbim-fa-field">
                            <label for="faa_requested_text" class="dbim-fa-label">Requested text</label>
                            <textarea id="faa_requested_text" name="faa_requested_text" rows="6" class="form-control" readonly aria-readonly="true">{{ trim($faRequestDetails->requested_note ?? '') }}</textarea>
                        </div>
                    </section>

                    {{-- ===== Step 4: Second appeal details + ID proof ===== --}}
                    <section data-step aria-labelledby="step4Title" hidden>
                        <h2 id="step4Title" class="dbim-fa-card__title" tabindex="-1">Second appeal details</h2>

                        <div class="dbim-fa-field">
                            <label for="faa_resp_dropdown" class="dbim-fa-label">Nature of response of FAA (ground for appeal) {!! $star !!}</label>
                            <select id="faa_resp_dropdown" name="faa_resp_dropdown"
                                    class="form-select @error('faa_resp_dropdown') is-invalid @enderror"
                                    data-validate data-required="true" data-msg="Select a response from the list."
                                    required aria-required="true" aria-describedby="faa_resp_dropdownError">
                                <option value="">Select response</option>
                                <option value="1" {{ old('faa_resp_dropdown') == '1' ? 'selected' : '' }}>Refused access to the Information Requested</option>
                                <option value="2" {{ old('faa_resp_dropdown') == '2' ? 'selected' : '' }}>No Response within the Time Limit</option>
                                <option value="3" {{ old('faa_resp_dropdown') == '3' ? 'selected' : '' }}>Unreasonable Amount of Fee is Required to Pay</option>
                                <option value="4" {{ old('faa_resp_dropdown') == '4' ? 'selected' : '' }}>Provided Incomplete, Misleading or False Information</option>
                                <option value="5" {{ old('faa_resp_dropdown') == '5' ? 'selected' : '' }}>Any Other Ground</option>
                            </select>
                            <div id="faa_resp_dropdownError" class="invalid-feedback @error('faa_resp_dropdown') d-block @enderror">@error('faa_resp_dropdown'){{ $message }}@enderror</div>
                        </div>

                        <div class="dbim-fa-field">
                            <label for="sa_application_doc" class="dbim-fa-label">Copy of reply of FA (PDF) {!! $star !!}</label>
                            <input type="file" id="sa_application_doc" name="sa_application_doc" accept="application/pdf,.pdf"
                                   class="form-control @error('sa_application_doc') is-invalid @enderror"
                                   data-validate data-required="true" data-msg="Choose the PDF file."
                                   required aria-required="true" aria-describedby="sa_application_docHelp sa_application_docError">
                            <div id="sa_application_docHelp" class="form-text">Only PDF files up to 5 MB are accepted.</div>
                            <div id="sa_application_docError" class="invalid-feedback @error('sa_application_doc') d-block @enderror">@error('sa_application_doc'){{ $message }}@enderror</div>
                        </div>

                        <div class="dbim-fa-field">
                            <label for="sa_document1" class="dbim-fa-label">Supporting document 1</label>
                            <input type="file" id="sa_document1" name="sa_document1"
                                   class="form-control @error('sa_document1') is-invalid @enderror"
                                   data-validate aria-describedby="sa_document1Help sa_document1Error">
                            <div id="sa_document1Help" class="form-text">File type .pdf, .doc, .docx, .txt, .xls, .xlsx, .jpg, .jpeg</div>
                            <div id="sa_document1Error" class="invalid-feedback @error('sa_document1') d-block @enderror">@error('sa_document1'){{ $message }}@enderror</div>
                        </div>

                        <div class="dbim-fa-field">
                            <label for="sa_document2" class="dbim-fa-label">Supporting document 2</label>
                            <input type="file" id="sa_document2" name="sa_document2"
                                   class="form-control @error('sa_document2') is-invalid @enderror"
                                   data-validate aria-describedby="sa_document2Help sa_document2Error">
                            <div id="sa_document2Help" class="form-text">File type .pdf, .doc, .docx, .txt, .xls, .xlsx, .jpg, .jpeg</div>
                            <div id="sa_document2Error" class="invalid-feedback @error('sa_document2') d-block @enderror">@error('sa_document2'){{ $message }}@enderror</div>
                        </div>

                        <div class="dbim-fa-field">
                            <label for="sa_document1_supporting_text" class="dbim-fa-label">Supporting text for document 1</label>
                            <div id="sa_document1_supporting_textHelp" class="form-text mb-1">Only alphabets A-Z a-z, numbers 0-9 and special characters , . - _ ( ) / @ : &amp; \ % are allowed in Text for RTI Request application. Required when Supporting Document 1 is attached.</div>
                            <textarea id="sa_document1_supporting_text" name="sa_document1_supporting_text" rows="4" maxlength="3000"
                                      class="form-control @error('sa_document1_supporting_text') is-invalid @enderror"
                                      data-validate
                                      aria-describedby="sa_document1_supporting_textHelp sa_document1_supporting_textError">{{ old('sa_document1_supporting_text') }}</textarea>
                            <div id="sa_document1_supporting_textError" class="invalid-feedback @error('sa_document1_supporting_text') d-block @enderror">@error('sa_document1_supporting_text'){{ $message }}@enderror</div>
                        </div>

                        <div class="dbim-fa-field">
                            <label for="sa_document2_supporting_text" class="dbim-fa-label">Supporting text for document 2</label>
                            <div id="sa_document2_supporting_textHelp" class="form-text mb-1">Only alphabets A-Z a-z, numbers 0-9 and special characters , . - _ ( ) / @ : &amp; \ % are allowed in Text for RTI Request application. Required when Supporting Document 2 is attached.</div>
                            <textarea id="sa_document2_supporting_text" name="sa_document2_supporting_text" rows="4" maxlength="3000"
                                      class="form-control @error('sa_document2_supporting_text') is-invalid @enderror"
                                      data-validate
                                      aria-describedby="sa_document2_supporting_textHelp sa_document2_supporting_textError">{{ old('sa_document2_supporting_text') }}</textarea>
                            <div id="sa_document2_supporting_textError" class="invalid-feedback @error('sa_document2_supporting_text') d-block @enderror">@error('sa_document2_supporting_text'){{ $message }}@enderror</div>
                        </div>

                        <h3 class="dbim-fa-card__title">Applicant personal information</h3>

                        <div class="dbim-fa-field">
                            <label for="id_proof" class="dbim-fa-label">ID proof {!! $star !!}</label>
                            <select id="id_proof" name="id_proof" class="form-select @error('id_proof') is-invalid @enderror"
                                    data-validate data-required="true" data-msg="Select the ID proof document type."
                                    required aria-required="true" aria-describedby="id_proofError">
                                <option value="">Select document</option>
                                <option value="1" {{ old('id_proof') == '1' ? 'selected' : '' }}>Aadhar Card</option>
                                <option value="2" {{ old('id_proof') == '2' ? 'selected' : '' }}>Pan Card</option>
                                <option value="3" {{ old('id_proof') == '3' ? 'selected' : '' }}>Driving Licence</option>
                                <option value="4" {{ old('id_proof') == '4' ? 'selected' : '' }}>Ration Card</option>
                                <option value="5" {{ old('id_proof') == '5' ? 'selected' : '' }}>Govt. Id Card (if any)</option>
                                <option value="5" {{ old('id_proof') == '6' ? 'selected' : '' }}>Other</option>
                            </select>
                            <div id="id_proofError" class="invalid-feedback @error('id_proof') d-block @enderror">@error('id_proof'){{ $message }}@enderror</div>
                        </div>

                        <div class="dbim-fa-field">
                            <label for="id_proof_file" class="dbim-fa-label">ID proof file {!! $star !!}</label>
                            <input type="file" id="id_proof_file" name="id_proof_file" accept=".pdf,.jpg,.jpeg"
                                   class="form-control @error('id_proof_file') is-invalid @enderror"
                                   data-validate data-required="true" data-msg="Choose the ID proof file."
                                   required aria-required="true" aria-describedby="id_proof_fileHelp id_proof_fileError">
                            <div id="id_proof_fileHelp" class="form-text">File type .pdf, .jpg, .jpeg</div>
                            <div id="id_proof_fileError" class="invalid-feedback @error('id_proof_file') d-block @enderror">@error('id_proof_file'){{ $message }}@enderror</div>
                        </div>
                    </section>

                    {{-- ===== Step 5: Declaration ===== --}}
                    <section data-step aria-labelledby="step5Title" hidden>
                        <h2 id="step5Title" class="dbim-fa-card__title" tabindex="-1">Declaration</h2>

                        <div class="dbim-fa-field">
                            <div class="dbim-fa-option">
                                <input type="checkbox" id="declaration" name="declaration" class="form-check-input"
                                       data-validate aria-describedby="declarationError">
                                <label for="declaration">The information as provided above is true to the best of my knowledge {!! $star !!}</label>
                            </div>
                            <div id="declarationError" class="invalid-feedback @error('declaration') d-block @enderror">@error('declaration'){{ $message }}@enderror</div>
                        </div>

                        <x-captcha />
                    </section>

                    {{-- ===== Actions ===== --}}
                    <div class="dbim-fa-actions">
                        <button type="button" id="btnBack" class="dbim-fa-btn dbim-fa-btn--outline" hidden>Back</button>
                        <button type="button" id="btnNext" class="dbim-fa-btn">Next</button>
                        <button type="submit" id="submit_button" name="submit_button" class="dbim-fa-btn" hidden>Submit</button>
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
    var form = $('secondAppealOnlineForm');
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

    $('sa_application_doc').dataCheck = fileRule(['pdf'], 5, 'Only PDF files are allowed.');

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

    $('declaration').dataCheck = function (el) {
        return el.checked ? '' : 'Confirm the declaration.';
    };

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