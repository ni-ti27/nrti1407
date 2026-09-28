<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>First Form Submission</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- <script src="https://google.com/recaptcha/api.js" async defer></script> -->

</head>

<style>
    .mainContainer
    {
        /* border : 2px solid black; */
        border-top: none;
        border-bottom: none;
    }
</style>

<body>
@include('UsersView.header')
    <div class="container mt-5" style="margin-top: 0 !important">
        <div class="mainContainer row justify-content-center">
            <div class="col-md-10" style="margin: 20px 20px">
                <h4>RTI New Request</h4><br>
                @if(Session::has('success'))
                    <div class="alert alert-success text-center">
                        {{Session::get('success')}}
                    </div>
                @endif
                <form id="first_req_form" name="first_req_form" method="post" enctype="multipart/form-data" action="{{ route('validate.form') }}" novalidate>
                    @csrf
                    @if ($errors->has('address_length_error'))
                        <div class="alert alert-danger">
                            <ul>
                                <li>{{ $errors->first('address_length_error') }}</li>
                            </ul>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <h5 class="card-header">Public Authority Details:</h5>
                                <div class="card-body">
                                    <div class="row justify-content-center">
                                        <div class="form-group col-md-4 mb-2 ">
                                            <label>Select Department/Public Authority<span class="text-danger">*</span></label>
                                            <select name="applied_dept" class="form-control" id="applied_dept">
                                                <option value="">Select Department</option>
                                                @foreach ($deptData as $id => $department)
                                                    <option value="{{ $department->dept_code }}" {{ old('applied_dept') == $department->dept_code ? 'selected' : '' }}>
                                                        {{ $department->department }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('applied_dept')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-4 mb-2">
                                            <label for="applied_subdept">Select Office<span class="text-danger">*</span></label>
                                            <select name="applied_subdept" class="form-control" id="applied_subdept">
                                                <option value="">Select Office</option>
                                            </select>
                                            @error('applied_subdept')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-4 mb-2">
                                            <label for="applied_office">Select Sub Office<span class="text-danger">*</span></label>
                                            <select name="applied_office" class="form-control" id="applied_office">
                                                <option value="">Select Sub Office</option>
                                            </select>
                                            @error('applied_office')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <h5 class="card-header">Personal Details of RTI Applicant</h5>
                                <div class="card-body">
                                    <div class="row justify-content-center">
                                        <div class="form-group col-md-6 mb-2">
                                            <label for="name">Name<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name', $profileData->name ?? '') }}" placeholder="Firstname Lastname">
                                            @error('name')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6 mb-2">
                                            <label for="gender">Gender<span class="text-danger">*</span></label>
                                            <select class="form-control @error('gender') is-invalid @enderror" name="gender" id="gender">
                                            <option value="male"
                                                {{ old('gender', $profileData->gender ?? '') == 'male' ? 'selected' : '' }}>
                                                Male
                                            </option>

                                            <option value="female"
                                                {{ old('gender', $profileData->gender ?? '') == 'female' ? 'selected' : '' }}>
                                                Female
                                            </option>

                                            <option value="other"
                                                {{ old('gender', $profileData->gender ?? '') == 'other' ? 'selected' : '' }}>
                                                Other
                                            </option>
                                            </select>
                                            @error('gender')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row justify-content-center">
                                        <label for="address_line1">Address<span class="text-danger">*</span></label>
                                        <div class="form-group col-md-4 mb-2">
                                            <input type="text" class="form-control @error('address_line1') is-invalid @enderror" name="address_line1" id="address_line1" value="{{ old('address_line1', $profileData->address_line1 ?? '') }}" placeholder="Address Line 1">
                                            @error('address_line1')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-4 mb-2">
                                            <input type="text" class="form-control @error('address_line2') is-invalid @enderror" name="address_line2" id="address_line2" value="{{ old('address_line2', $profileData->address_line2 ?? '') }}" placeholder="Address Line 2">
                                            @error('address_line2')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-4 mb-2">
                                            <input type="text" class="form-control @error('address_line3') is-invalid @enderror" name="address_line3" id="address_line3" value="{{ old('address_line3', $profileData->address_line3 ?? '') }}" placeholder="Address Line 3">
                                            @error('address_line3')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row justify-content-center">
                                        <div class="form-group col-md-4 mb-2">
                                            <label for="pincode">Pincode<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('pincode') is-invalid @enderror" name="pincode" id="pincode" value="{{ old('pincode', $profileData->pincode ?? '') }}" maxlength="6" placeholder="Pin Code">
                                            @error('pincode')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-4 mb-2">
                                            <label for="state">State<span class="text-danger">*</span></label>

                                            <select name="state"
                                                    id="state"
                                                    class="form-control @error('state') is-invalid @enderror">

                                                <option value="">Select State</option>

                                                <option value="0"
                                                    {{ old('state', $profileData->state ?? '') == 0 ? 'selected' : '' }}>
                                                    Others
                                                </option>

                                                @foreach ($stateData as $state)
                                                    <option value="{{ $state->id }}"
                                                        {{ old('state', $profileData->state ?? '') == $state->id ? 'selected' : '' }}>
                                                        {{ $state->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-4 mb-2">
                                            <label for="country">Country<span class="text-danger">*</span></label>
                                            <!-- <input type="text" class="form-control @error('country') is-invalid @enderror" name="country" id="country" value="India"> -->
                                            <select class="form-control @error('country') is-invalid @enderror" name="country" id="country">
                                                <option value="1">India</option>
                                            </select>
                                            @error('country')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row justify-content-center">
                                        <div class="form-group col-md-4 mb-2">
                                            <label for="district">District<span class="text-danger">*</span></label>
                                            <select class="form-control @error('district') is-invalid @enderror" name="district" id="district">
                                            <option value="">Select District</option>
                                            </select>
                                            @error('district')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-4 mb-2">
                                            <label for="taluka">Taluka<span class="text-danger">*</span></label>
                                            <select class="form-control @error('taluka') is-invalid @enderror" name="taluka" id="taluka">
                                                <option value="">Select Taluka</option>
                                                @foreach($talukaData ?? [] as $taluka)
                                                <option value="{{ $taluka->id }}"
                                                    {{ old('taluka', $profileData->taluka ?? '') == $taluka->id ? 'selected' : '' }}>
                                                    {{ $taluka->name }}
                                                </option>
                                            @endforeach
                                            </select>
                                            @error('taluka')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-4 mb-2">
                                            <label for="village">Village<span class="text-danger">*</span></label>
                                            <select class="form-control @error('village') is-invalid @enderror" name="village" id="village">
                                                <option value="">Select Village</option>
                                            </select>
                                            @error('village')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row justify-content-center">
                                        <div class="form-group mb-2 col-md-4">
                                            <label for="phone_no">Phone Number</label>
                                            <input type="text" class="form-control @error('phone_no') is-invalid @enderror" name="phone_no" id="phone_no" value="{{ old('phone_no', $profileData->phone_no ?? '') }}" placeholder="0832 2410816">
                                            @error('phone_no')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group mb-2 col-md-4">
                                            <label for="mobile_no">Mobile Number<span class="text-danger">*</span></label>
                                            <span style="color: blue; font-size: 12px">(For receiving SMS alerts)</span>
                                            <input type="text" class="form-control @error('mobile_no') is-invalid @enderror" name="mobile_no" id="mobile_no" value="{{ old('mobile_no', $profileData->mobile_no ?? '') }}" placeholder="+91 9865478864">
                                            @error('mobile_no')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group mb-2 col-md-4">
                                            <label for="email_id">Email Address<span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('email_id') is-invalid @enderror" name="email_id" id="email_id" value="{{ old('email_id', $profileData->email_id ?? '') }}"  placeholder="WJrjv@example.com">
                                            @error('email_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row justify-content-center">
                                        <div class="form-group col-md-4 mb-2">
                                            <label for="edu_status">Educational Status<span class="text-danger">*</span></label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="edu_status" id="edu_status1" value="educated" {{ old('edu_status', $profileData->edu_status ?? '') == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="edu_status1">Educated</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="edu_status" id="edu_status2" value="non_educated" {{ old('edu_status', $profileData->edu_status ?? '') == 0 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="edu_status2">Non-Educated</label>
                                            </div>
                                            @error('edu_status')
                                                <span class="invalid-feedback" role="alert" style="display:block">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-4 mb-2 hiddenQualificationDiv"  style="{{ old('edu_status', $profileData->edu_status ?? '') == 1 ? '' : 'display:none;' }}">
                                            <label for="qualification">Qualification<span class="text-danger">*</span></label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="qualification" id="qualification1" value="1" {{ old('qualification', $profileData->qualification ?? '')  == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="qualification1">10th Standard</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="qualification" id="qualification2" value="2" {{ old('qualification', $profileData->qualification ?? '') == 2 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="qualification2">12th Standard</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="qualification" id="qualification3" value="3" {{ old('qualification', $profileData->qualification ?? '') == 3 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="qualification3">Graduate</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="qualification" id="qualification4" value="4" {{ old('qualification', $profileData->qualification ?? '') == 4 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="qualification4">Post Graduate</label>
                                            </div>
                                            @error('qualification')
                                                <span class="invalid-feedback" role="alert" style="display:block">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <h5 class="card-header">(Enter Text for RTI Request application upto 3000 words)</h5>
                                <div class="card-body">
                                    <div class="row justify-content-center">
                                        <div class="form-group col-md-8 mb-2">
                                            <label for="request_text">Request Text<span class="text-danger">*</span></label>
                                            <br>
                                            <span style="font-size: 12px; color: blue;" for="">Only alphabets A-Z a-z number 0-9 and special characters , . - _ ( ) / @ : & \ % are allowed in Text for RTI Request application</span>
                                            <textarea style="height: 100px" class="form-control" @error('request_text') is-invalid @enderror name="request_text" maxlength="3000" id="request_text">{{ old('request_text') }}</textarea>
                                            @error('request_text')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group mb-2 col-md-4">
                                        <label for="document">RTI Application<span class="text-danger">*</span></label>
                                            <br>
                                            <span for="document" style="color: blue; font-size: 12px"> (Only pdf upto 5 MB)</span>
                                            <input type="file" class="form-control @error('document') is-invalid @enderror" name="document" id="document" placeholder="Choose file">
                                                @error('document')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="card">
                                <h5 class="card-header">Request Details</h5>
                                <div class="card-body">
                                    <div class="row justify-content-center">
                                        <div class="form-group col-md-4 mb-2">
                                            <label for="origin_country">Citizenship</label>
                                            <span for="origin_country" style="color: blue; font-size:12px">(Only Indian citizens can file RTI request application)</span>
                                            <input type="text" readonly class="form-control @error('origin_country') is-invalid @enderror" name="origin_country" id="origin_country" value="Indian" placeholder="Bharat">
                                            @error('origin_country')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-4 mb-2">
                                            <label for="is_bpl">Is Applicant Below Poverty Line?</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="is_bpl" id="is_not_bpl" value="no" checked>
                                                {{-- <input class="form-check-input" type="radio" name="is_bpl" id="is_not_bpl" value="no" {{ old('is_bpl') == 'no' ? 'checked' : '' }}> --}}
                                                <label class="form-check-label" for="is_not_bpl">No</label>
                                                <span for="" style="color: blue; font-size: 12px; margin-left: 10px">(You are required to pay RTI Fee of Rs. 10)</span>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="is_bpl" id="is_bpl" value="yes">
                                                <label class="form-check-label" for="is_bpl">Yes</label>
                                            </div>
                                            @error('is_bpl')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                    </div>
                                    <br>
                                    <div class="row hiddenCardDiv justify-content-center" style="display: none">
                                        <div class="row justify-content-center">
                                            <div class="form-group col-md-8 mb-2">
                                                <div class="d-flex">
                                                    <label for="card_no" class="mr-2">BPL Card No./Yellow Ration Card/Certificate issued by local bodies/Govt. (Municipal Corporation/Municipal Council/Municipality/Gram Panchayat)</label>
                                                    <input type="text" class="form-control @error('card_no') is-invalid @enderror" name="card_no" id="card_no" value="{{ old('card_no') }}">
                                                </div>
                                                @error('card_no')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-group mb-2 col-md-2">
                                                <label for="issued_year">Issued Year</label>
                                                <input type="text" class="form-control @error('issued_year') is-invalid @enderror" name="issued_year" id="issued_year" value="{{ old('issued_year') }}">
                                                @error('issued_year')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-group mb-2 col-md-2">
                                                <label for="issuing_authority">Issuing Authority</label>
                                                <input type="text" class="form-control @error('issuing_authority') is-invalid @enderror" name="issuing_authority" id="issuing_authority" value="{{ old('issuing_authority') }}">
                                                @error('issuing_authority')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <br>
                                    <div class="row justify-content-center">
                                    <x-captcha />
                                        {{-- <div class="form-group col-md-4 mb-2">
                                            <div class="g-recaptcha @error('g-recaptcha-response') is-invalid @enderror" data-sitekey="{{ env('GOOGLE_SITE_KEY') }}"></div>
                                            @error('g-recaptcha-response')
                                                <span class="text-danger" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <br>
                                        </div> --}}
                                            {{-- <button type="button" id="paymentButton" class="btn btn-success">Proceed to Fee Payment</button> --}}
                                        <button type="submit" name="send" onclick="validateForm()" value="" class="btn btn-success pay_btn">Proceed to Fee Payment</button>
                                    </div>
                                </div>
                            </div>
                            <br>
                        </div>
                    </div>
                    {{-- <div class="d-grid mt-3 primarySubmitBtn" style="display: none"> --}}
                    <div class="d-grid mt-3 primarySubmitBtn" style="display: none">
                        <input type="submit" name="send" value="Submit" class="btn btn-dark btn-block send">
                    </div>
                </form>
            </div>
        </div>
    </div>
@include('UsersView.footer')
</body>

<script type="text/javascript">


    document.addEventListener("DOMContentLoaded", function()
    {
        var form = document.querySelector("form[name='first_req_form']");

        form.addEventListener("submit", function(event)
        {
            var appliedDeptSelect = form.querySelector("#applied_dept");
            if (appliedDeptSelect.value === "") {
                alert("Please select a department.");
                appliedDeptSelect.focus();
                event.preventDefault();
                return ;
            }

            var appliedSubDeptSelect = form.querySelector("#applied_subdept");
            if (appliedSubDeptSelect.value === "") {
                alert("Please select a sub department.");
                appliedSubDeptSelect.focus();
                event.preventDefault();
                return ;
            }

            var appliedOffice = form.querySelector("#applied_office");
            if (appliedOffice.value === "") {
                alert("Please select a sub office.");
                appliedOffice.focus();
                event.preventDefault();
                return ;
            }

            var applicantName = form.querySelector("#name");
            if (applicantName.value === "") {
                alert("Please enter your name.");
                applicantName.focus();
                event.preventDefault();
                return ;
            }

            var address_line1 = form.querySelector("#address_line1");
            if (address_line1.value === "") {
                alert("Please enter your address line 1.");
                address_line1.focus();
                event.preventDefault();
                return ;
            }

            var address_line2 = form.querySelector("#address_line2");
            if (address_line2.value === "") {
                alert("Please enter your address line 2.");
                address_line2.focus();
                event.preventDefault();
                return ;
            }

            var address_line3 = form.querySelector("#address_line3");
            if (address_line3.value === "") {
                alert("Please enter your address line 3.");
                address_line3.focus();
                event.preventDefault();
                return ;
            }

            var pincode = form.querySelector("#pincode");
            if (pincode.value === "") {
                alert("Please enter your pincode.");
                pincode.focus();
                event.preventDefault();
                return ;
            }

            var state = form.querySelector("#state");
            if (state.value == "") {
                alert("Please select your state.");
                state.focus();
                event.preventDefault();
                return;
            }

            var district = form.querySelector("#district");
            if (district.value == "") {
                alert("Please select your district.");
                district.focus();
                event.preventDefault();
                return;
            }

            var taluka = form.querySelector("#taluka");
            if (taluka.value == "") {
                alert("Please select your taluka.");
                taluka.focus();
                event.preventDefault();
                return;
            }

            var village = form.querySelector("#village");
            if (village.value === "") {
                alert("Please select your village.");
                village.focus();
                event.preventDefault();
                return;
            }

            var mobile_no = form.querySelector("#mobile_no");
            var mobileNumberPattern = /^\d{10}$/;
            if (mobile_no.value === "" || !mobileNumberPattern.test(mobile_no.value)) {
                alert("Please enter a valid 10-digit mobile number.");
                mobile_no.focus();
                event.preventDefault();
                return;
            }

            var email = form.querySelector("#email_id");
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email.value === "" || !emailPattern.test(email.value)) {
                alert("Please enter a valid email address.");
                email.focus();
                event.preventDefault();
                return;
            }

            var eduStatusInputs = document.querySelectorAll('input[name="edu_status"]');
            var eduStatusChecked = false;

            eduStatusInputs.forEach(function(input) {
                if (input.checked) {
                    eduStatusChecked = true;
                }
            });

            if (!eduStatusChecked) {
                alert("Please select Education status.");
                event.preventDefault(); // Prevent form submission
                return false;
            }

            if (document.querySelector('input[name="edu_status"]:checked').value === "educated") {
                var qualificationInputs = document.querySelectorAll('input[name="qualification"]');
                var qualificationChecked = false;

                qualificationInputs.forEach(function(input) {
                    if (input.checked) {
                        qualificationChecked = true;
                    }
                });

                // If no qualification option is checked, show alert and prevent form submission
                if (!qualificationChecked) {
                    alert("Please select Qualification.");
                    event.preventDefault(); // Prevent form submission
                    return false;
                }
            }

            var eduStatusChecked = false;
            var eduStatusInputs = document.querySelectorAll('input[name="edu_status"]');
            eduStatusInputs.forEach(function(input) {
                if (input.checked) {
                    eduStatusChecked = true;
                }
            });

            if (!eduStatusChecked) {
                alert("Please select Education status.");
                event.preventDefault();
                return;
            }

            if (document.querySelector('input[name="edu_status"]:checked').value === "educated") {
                var qualificationChecked = false;
                var qualificationInputs = document.querySelectorAll('input[name="qualification"]');
                qualificationInputs.forEach(function(input) {
                    if (input.checked) {
                        qualificationChecked = true;
                    }
                });

                if (!qualificationChecked) {
                    alert("Please select Qualification.");
                    event.preventDefault();
                    return;
                }
            }

            var textarea = form.querySelector("textarea[name='request_text']");
            if (textarea.value === "") {
                alert("Please explain your request.");
                textarea.focus();
                event.preventDefault();
                return;
            }

            if(textarea.value.length >3000){
                alert("Maxumun length of request text is 3000.");
                textarea.focus();
                event.preventDefault();
                return;
            }

            var fileUpload = form.querySelector("input[type='file']");
            // if (fileUpload.value === "") {
            //     alert("Please upload a file.");
            //     fileUpload.focus();
            //     event.preventDefault();
            //     return;
            // }

            // console.log(fileUpload);
            // console.log(fileUpload.value);
            if(fileUpload.value == '')
            {   var confirmed = confirm("Submit request without attaching application file?");
                if (!confirmed)
                {
                    event.preventDefault();
                    return false;
                }
            }


            // console.log(fileUpload.files);
            // event.preventDefault();
            //         return false;

            if (fileUpload.files[0].size > 5 * 1024 * 1024) {
                alert("File size exceeds the limit of 5MB.");
                fileUpload.value = "";
                event.preventDefault();
                return;
            }

            // 2. Stop the form from submitting immediately
            event.preventDefault();

            // 3. Call checkPioStatus and pass a callback function to handle submission
            checkPioStatus(function(canSubmit) {
                if (canSubmit) {
                    form.submit();
                }
                // else: alert already shown inside checkPioStatus
            });

        });
    });



$(document).ready(function()
{
    populateSubDeptDropdown();
    populateDistrictDropdown();
    $('.hiddenCardDiv').hide();

    // Event listener for State dropdown change
    $('select[name="state"]').on('change', function() {
        populateDistrictDropdown();
    });

    // Event listener for District dropdown change
    $('select[name="district"]').on('change', function() {
        populateTalukaDropdown();
    });

    $('select[name="taluka"]').on('change', function() {
        populateVillageDropdown();
    });

    // Function to populate District dropdown
    function populateDistrictDropdown() {
        var stateId = $('select[name="state"]').val();
        if (stateId == 0)
        {
            var district = $('select[name="district"]');
            district.empty().append('<option value="0">Others</option>');
            populateTalukaDropdown();
        }
        else if (stateId) {
            $.ajax({
                url: '/districtDropdown/' + stateId,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    var oldDistrict = '{{ old("district", $profileData->district ?? "") }}';
                    $('select[name="district"]').empty();
                    $.each(data, function(key, value) {
                        var option = $('<option value="' + value.id + '">' + value.name + '</option>');

                        if(value.id == oldDistrict)
                        {
                            option.prop('selected', true);
                        }
                        $('select[name="district"]').append(option);
                    });
                    populateTalukaDropdown();
                }
            });
        } else {
            var district = $('select[name="district"]');
            district.empty().append('<option value="">Select District</option>');
        }
    }

    // Function to populate Taluka dropdown
    function populateTalukaDropdown() {
         //debugger;
        var districtId = $('select[name="district"]').val();
        if (districtId == 0)
        {
            var taluka = $('select[name="taluka"]');
            taluka.empty().append('<option value="0">Others</option>');
            populateVillageDropdown();
        }
        else if (districtId) {
            $.ajax({
                url: '/talukaDropdown/' + districtId,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    var oldTaluka ='{{ old("taluka", $profileData->taluka ?? "") }}';
                    $('select[name="taluka"]').empty();
                    $.each(data, function(key, value) {
                        var option = $('<option value="' + value.id + '">' + value.name + '</option>');
                        if(value.id == oldTaluka)
                        {
                            option.prop('selected', true);
                        }
                        $('select[name="taluka"]').append(option);
                    });
                    populateVillageDropdown();
                }
            });
        } else {
            $('select[name="taluka"]').empty();
        }
    }

    // Function to populate Village dropdown
    function populateVillageDropdown() {
        var talukaId = $('select[name="taluka"]').val();
        if (talukaId == 0)
        {
            var village = $('select[name="village"]');
            village.empty().append('<option value="0">Others</option>');
        }
        else if (talukaId) {
            $.ajax({
                url: '/villageDropdown/' + talukaId,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    var oldVillage = '{{ old("village", $profileData->village ?? "") }}';
                    $('select[name="village"]').empty();
                    $.each(data, function(key, value) {
                        // $('select[name="village"]').append('<option value="' + value.id + '">' + value.name + '</option>');
                        var option = $('<option value="' + value.id + '">' + value.name + '</option>');
                        if(value.id == oldVillage)
                        {
                            option.prop('selected', true);
                        }
                        $('select[name="village"]').append(option);
                    });
                }
            });
        } else {
            $('select[name="village"]').empty();
        }
    }

    // 1. Department Change
    $('select[name="applied_dept"]').on('change', function() {
        // If populateSubDeptDropdown uses a callback or Promise:
        populateSubDeptDropdown(function() {
            // Reset subdept/office values if needed, then check status
            checkPioStatus();
        });
    });

    // 2. Sub-Department Change
    $('select[name="applied_subdept"]').on('change', function() {
        populateOfficeDropdown(function() {
            checkPioStatus();
        });
    });
    // Event listener for Sub Office dropdown change
    $('select[name="applied_office"]').on('change', function() {
        checkPioStatus(); // Check with all three values
    });

    // Function to populate Sub Department dropdown
    function populateSubDeptDropdown(callback) {
        var deptId = $('select[name="applied_dept"]').val();
        if (deptId) {
            $.ajax({
                url: '/subDeptDropdown/' + deptId,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    // alert(JSON.stringify(data));
                    var oldSubDept = '{{ old("applied_subdept") }}';
                    $('select[name="applied_subdept"]').empty();
                    $.each(data, function(key, value) {
                        var option = $('<option value="' + value.office_code + '">' + value.department + '</option>');
                        // Check if the option matches the old input value
                        if (value.office_code == oldSubDept) {
                            option.prop('selected', true);
                        }

                        $('select[name="applied_subdept"]').append(option);
                    });
                    populateOfficeDropdown(callback);
                }
            });
        } else {
            var subDeptSelect = $('select[name="applied_subdept"]');
            subDeptSelect.empty().append('<option value="">Select Office</option>');
            if (typeof callback === 'function') callback(); // still fire it in the empty case
        }
    }

    // Function to populate Sub Department dropdown
    function populateOfficeDropdown(callback) {
        var deptId = $('select[name="applied_dept"]').val();
        var subDeptId = $('select[name="applied_subdept"]').val();
        if (subDeptId) {
            $.ajax({
                url: '/officeDropdown/' + deptId +'/'+ subDeptId,
                type: "GET",
                dataType: "json",

                success: function(data) {
                    // alert(JSON.stringify(data));
                    var oldOffice = '{{ old("applied_office") }}';
                    $('select[name="applied_office"]').empty();
                    $.each(data, function(key, value) {
                        var option = $('<option value="' + value.suboffice_code + '">' + value.department + '</option>');
                        // Check if the option matches the old input value
                        if (value.suboffice_code == oldOffice) {
                            option.prop('selected', true);
                        }

                        $('select[name="applied_office"]').append(option);
                    });
                    if (typeof callback === 'function') callback(); // still fire it in the empty case
                }
            });
        } else {
            $('select[name="applied_office"]').append('<option value="0">Sub Office</option>');
             if (typeof callback === 'function') callback();
        }
    }

    // Handler for educated radio button change
    $('input[name="edu_status"]').change(function() {
        if ($(this).val() === 'educated') {
            $('.hiddenQualificationDiv').show();
        } else {
            $('.hiddenQualificationDiv').hide();

            // $('input[name="qualification"]').prop('checked', false);
            // $('input[name="qualification"][value="default"]').prop('checked', true); // Check the default option
        }
    });

    $('input[name="is_bpl"]').change(function() {
        if ($(this).val() === 'no') {
            $('.hiddenCardDiv').show();
            $('.primarySubmitBtn').hide();
            $('.send').hide();
        } else {
            $('.hiddenCardDiv').hide();
            $('.primarySubmitBtn').show();
            $('.send').show();
            // Set default value for the Qualification radio buttons when Non-Educated is selected
            // $('input[name="card_no"]').value = 'N/A';
            // $('input[name="qualification"][value="N/A"]');
        }
    });

    $('input[name="is_bpl"]').change(function() {
        if ($(this).val() === 'yes')
        {
            $('.hiddenCardDiv').show();
            $('.send').show();
            $('.pay_btn').hide();
        }
        else
        {
            $('.hiddenCardDiv').hide();
            $('.send').hide();
            $('.pay_btn').show();
        }
    });

    $('.send').hide();

});
// Function to check PIO status via AJAX
function checkPioStatus(callback) {
    var deptSelect = $('select[name="applied_dept"]');
    var subDeptSelect = $('select[name="applied_subdept"]');
    var officeSelect = $('select[name="applied_office"]');

    var deptId = deptSelect.val();
    var subDeptId = subDeptSelect.val() || 0;
    var officeId = officeSelect.val() || 0;

    var deptText = deptSelect.find(':selected').text().trim();
    var subDeptText = subDeptSelect.find(':selected').text().trim();
    var officeText = officeSelect.find(':selected').text().trim();

    if (deptId && deptId !== "0" && deptId !== "") {
        $.ajax({
            url: '/check-pio-status/' + deptId + '/' + subDeptId + '/' + officeId,
            type: "GET",
            dataType: "json",
            success: function(response) {
                if (response.inactive) {
                    alert(
                        //"For \n"+
    "Department: " + deptText + "\n" +
    "Office: " + subDeptText + "\n" +
    "Sub-Office: " + officeText + "\n\n" +
    "PIO not appointed at this level."
);
                    if (typeof callback === 'function') callback(false);
                    return;
                }
                if (typeof callback === 'function') callback(true);
            },
            error: function(xhr) {
                console.error('check-pio-status failed', xhr.status, xhr.responseText);
                if (typeof callback === 'function') callback(true); // fail-open on unexpected error
            }
        });
    } else if (typeof callback === 'function') {
        callback(true);
    }
}

</script>
</html>
