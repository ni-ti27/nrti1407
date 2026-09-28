<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Edit Form Submission</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<style>
    .mainContainer
    {
        border-top: none;
        border-bottom: none;
    }
</style>

<body>
@include('UsersView.header')
    <div class="container mt-5" style="margin-top: 0 !important">
        <div class="mainContainer row justify-content-center">
            <div class="col-md-10" style="margin: 20px 20px">
                <h4>Edit RTI Request</h4><br>
                @if(Session::has('success'))
                    <div class="alert alert-success text-center">
                        {{Session::get('success')}}
                    </div>
                @endif

                <!-- Changed Action Route to your update route and passing the ID of the record -->
                <form id="edit_req_form" name="edit_req_form" method="post" enctype="multipart/form-data" action="{{ route('update.form', $requestData->id) }}" novalidate>
                    @csrf
                    @method('PUT') <!-- Added PUT method directive for updating data -->

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
                                                    <option value="{{ $department->dept_code }}" {{ old('applied_dept', $requestData->applied_dept ?? '') == $department->dept_code ? 'selected' : '' }}>
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
                                                <!-- Populate existing subdept via backend data loop if applicable, otherwise handled by your dynamic JS -->
                                                @foreach($subDeptData ?? [] as $subdept)
                                                    <option value="{{ $subdept->office_code }}" {{ old('applied_subdept', $requestData->applied_subdept ?? '') == $subdept->office_code ? 'selected' : '' }}>
                                                        {{ $subdept->department }}
                                                    </option>
                                                @endforeach
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
                                                @foreach($officeData ?? [] as $office)
                                                    <option value="{{ $office->suboffice_code }}" {{ old('applied_office', $requestData->applied_office ?? '') == $office->suboffice_code ? 'selected' : '' }}>
                                                        {{ $office->department }}
                                                    </option>
                                                @endforeach
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
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name', $requestData->name ?? '') }}" placeholder="Firstname Lastname">
                                            @error('name')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6 mb-2">
                                            <label for="gender">Gender<span class="text-danger">*</span></label>
                                            <select class="form-control @error('gender') is-invalid @enderror" name="gender" id="gender">
                                                <option value="male" {{ old('gender', $requestData->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                                <option value="female" {{ old('gender', $requestData->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                                <option value="other" {{ old('gender', $requestData->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
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
                                            <input type="text" class="form-control @error('address_line1') is-invalid @enderror" name="address_line1" id="address_line1" value="{{ old('address_line1', $requestData->address_line1 ?? '') }}" placeholder="Address Line 1">
                                            @error('address_line1')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-4 mb-2">
                                            <input type="text" class="form-control @error('address_line2') is-invalid @enderror" name="address_line2" id="address_line2" value="{{ old('address_line2', $requestData->address_line2 ?? '') }}" placeholder="Address Line 2">
                                            @error('address_line2')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-4 mb-2">
                                            <input type="text" class="form-control @error('address_line3') is-invalid @enderror" name="address_line3" id="address_line3" value="{{ old('address_line3', $requestData->address_line3 ?? '') }}" placeholder="Address Line 3">
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
                                            <input type="text" class="form-control @error('pincode') is-invalid @enderror" name="pincode" id="pincode" value="{{ old('pincode', $requestData->pincode ?? '') }}" maxlength="6" placeholder="Pin Code">
                                            @error('pincode')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-4 mb-2">
                                            <label for="state">State<span class="text-danger">*</span></label>
                                            <select name="state" id="state" class="form-control @error('state') is-invalid @enderror">
                                                <option value="">Select State</option>
                                                <option value="0" {{ old('state', $requestData->state ?? '') == 0 ? 'selected' : '' }}>Others</option>
                                                @foreach ($stateData as $state)
                                                    <option value="{{ $state->id }}" {{ old('state', $requestData->state ?? '') == $state->id ? 'selected' : '' }}>
                                                        {{ $state->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-4 mb-2">
                                            <label for="country">Country<span class="text-danger">*</span></label>
                                            <select class="form-control @error('country') is-invalid @enderror" name="country" id="country">
                                                <option value="1" {{ old('country', $requestData->country ?? '') == '1' ? 'selected' : '' }}>India</option>
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
                                                @foreach($districtData ?? [] as $district)
                                                    <option value="{{ $district->id }}" {{ old('district', $requestData->district ?? '') == $district->id ? 'selected' : '' }}>
                                                        {{ $district->name }}
                                                    </option>
                                                @endforeach
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
                                                    <option value="{{ $taluka->id }}" {{ old('taluka', $requestData->taluka ?? '') == $taluka->id ? 'selected' : '' }}>
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
                                                @foreach($villageData ?? [] as $village)
                                                    <option value="{{ $village->id }}" {{ old('village', $requestData->village ?? '') == $village->id ? 'selected' : '' }}>
                                                        {{ $village->name }}
                                                    </option>
                                                @endforeach
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
                                            <input type="text" class="form-control @error('phone_no') is-invalid @enderror" name="phone_no" id="phone_no" value="{{ old('phone_no', $requestData->phone_no ?? '') }}" placeholder="0832 2410816">
                                            @error('phone_no')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group mb-2 col-md-4">
                                            <label for="mobile_no">Mobile Number<span class="text-danger">*</span></label>
                                            <span style="color: blue; font-size: 12px">(For receiving SMS alerts)</span>
                                            <input type="text" class="form-control @error('mobile_no') is-invalid @enderror" name="mobile_no" id="mobile_no" value="{{ old('mobile_no', $requestData->mobile_no ?? '') }}" placeholder="+91 9865478864">
                                            @error('mobile_no')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group mb-2 col-md-4">
                                            <label for="email_id">Email Address<span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('email_id') is-invalid @enderror" name="email_id" id="email_id" value="{{ old('email_id', $requestData->email_id ?? '') }}"  placeholder="WJrjv@example.com">
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
                                                <input class="form-check-input" type="radio" name="edu_status" id="edu_status1" value="educated" {{ old('edu_status', $requestData->edu_status ?? '') == 'educated' || old('edu_status', $requestData->edu_status ?? '') == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="edu_status1">Educated</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="edu_status" id="edu_status2" value="non_educated" {{ old('edu_status', $requestData->edu_status ?? '') == 'non_educated' || old('edu_status', $requestData->edu_status ?? '') == 0 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="edu_status2">Non-Educated</label>
                                            </div>
                                            @error('edu_status')
                                                <span class="invalid-feedback" role="alert" style="display:block">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <!-- Visibility logic maintained natively via inline Blade evaluation -->
                                        <div class="form-group col-md-4 mb-2 hiddenQualificationDiv" style="{{ old('edu_status', $requestData->edu_status ?? '') == 'educated' || old('edu_status', $requestData->edu_status ?? '') == 1 ? '' : 'display:none;' }}">
                                            <label for="qualification">Qualification<span class="text-danger">*</span></label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="qualification" id="qualification1" value="1" {{ old('qualification', $requestData->qualification ?? '') == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="qualification1">10th Standard</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="qualification" id="qualification2" value="2" {{ old('qualification', $requestData->qualification ?? '') == 2 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="qualification2">12th Standard</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="qualification" id="qualification3" value="3" {{ old('qualification', $requestData->qualification ?? '') == 3 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="qualification3">Graduate</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="qualification" id="qualification4" value="4" {{ old('qualification', $requestData->qualification ?? '') == 4 ? 'checked' : '' }}>
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
                                            <span style="font-size: 12px; color: blue;">Only alphabets A-Z a-z number 0-9 and special characters , . - _ ( ) / @ : & \ % are allowed in Text for RTI Request application</span>
                                            <textarea style="height: 100px" class="form-control @error('request_text') is-invalid @enderror" name="request_text" maxlength="3000" id="request_text">{{ old('request_text', $requestData->request_text ?? '') }}</textarea>
                                            @error('request_text')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-2 col-md-4">
                                            <label for="document">RTI Application</label>
                                            <br>
                                            <span style="color: blue; font-size: 12px">(Only PDF up to 5 MB. Leave blank to keep existing file)</span>
                                            <input type="file" class="form-control @error('document') is-invalid @enderror" name="document" id="document" accept=".pdf">

                                            <!-- Display existing file -->
                                            <div id="currentFile" class="mt-1">
                                                @if($filePath)
                                                    <small>Current File:
                                                        <a href="{{ $filePath }}" target="_blank" id="fileLink">View Document</a>
                                                    </small>
                                                @else
                                                    <small>No file uploaded yet.</small>
                                                @endif
                                            </div>

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
                                            <span style="color: blue; font-size:12px">(Only Indian citizens can file RTI request application)</span>
                                            <input type="text" readonly class="form-control" name="origin_country" id="origin_country" value="Indian">
                                        </div>

                                        <div class="form-group col-md-4 mb-2">
                                            <label for="is_bpl">Is Applicant Below Poverty Line?</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="is_bpl" id="is_not_bpl" value="no" {{ old('is_bpl', $requestData->is_bpl ?? '') == 'no' || old('is_bpl', $requestData->is_bpl ?? '') == 0 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_not_bpl">No</label>
                                                <span style="color: blue; font-size: 12px; margin-left: 10px">(You are required to pay RTI Fee of Rs. 10)</span>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="is_bpl" id="is_bpl" value="yes" {{ old('is_bpl', $requestData->is_bpl ?? '') == 'yes' || old('is_bpl', $requestData->is_bpl ?? '') == 1 ? 'checked' : '' }}>
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

                                    <!-- Dynamic BPL section card wrapper display rule logic -->
                                    <div class="row hiddenCardDiv justify-content-center" style="{{ old('is_bpl', $requestData->is_bpl ?? '') == 'yes' || old('is_bpl', $requestData->is_bpl ?? '') == 1 ? '' : 'display: none' }}">
                                        <div class="row justify-content-center">
                                            <div class="form-group col-md-8 mb-2">
                                                <div class="d-flex">
                                                    <label for="card_no" class="me-2">BPL Card No./Yellow Ration Card/Certificate issued by local bodies/Govt. (Municipal Corporation/Municipal Council/Municipality/Gram Panchayat)</label>
                                                    <input type="text" class="form-control @error('card_no') is-invalid @enderror" name="card_no" id="card_no" value="{{ old('card_no', $requestData->card_no ?? '') }}">
                                                </div>
                                                @error('card_no')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-group mb-2 col-md-2">
                                                <label for="issued_year">Issued Year</label>
                                                <input type="text" class="form-control @error('issued_year') is-invalid @enderror" name="issued_year" id="issued_year" value="{{ old('issued_year', $requestData->issued_year ?? '') }}">
                                                @error('issued_year')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="form-group mb-2 col-md-2">
                                                <label for="issuing_authority">Issuing Authority</label>
                                                <input type="text" class="form-control @error('issuing_authority') is-invalid @enderror" name="issuing_authority" id="issuing_authority" value="{{ old('issuing_authority' , $requestData->issuing_authority ?? '') }}">
                                                @error('issuing_authority')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row justify-content-center">
                                    <x-captcha />
                                        <button type="submit" name="send" value="" class="btn btn-success pay_btn">Proceed to Fee Payment</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="d-grid mt-3 primarySubmitBtn" style="display: none">
                        <input type="submit" name="send" value="Submit" class="btn btn-dark btn-block send">
                    </div>
                </form>
            </div>
        </div>
    </div>

 <script>

$(document).ready(function(){

    // Initialize all dropdowns on page load
    populateSubDeptDropdown();
    populateOfficeDropdown();
    populateDistrictDropdown();
    populateTalukaDropdown();
    populateVillageDropdown();

    // STATE → DISTRICT → TALUKA → VILLAGE
    $('select[name="state"]').on('change', function(){
        populateDistrictDropdown();
    });

    $('select[name="district"]').on('change', function(){
        populateTalukaDropdown();
    });

    $('select[name="taluka"]').on('change', function(){
        populateVillageDropdown();
    });

    function populateDistrictDropdown(){
        var stateId = $('select[name="state"]').val();
        if(!stateId) return;

        $.get('/districtDropdown/' + stateId, function(data){
            var oldDistrict = '{{ old("district", $requestData->district ?? "") }}';
            var districtSelect = $('select[name="district"]');
            districtSelect.empty().append('<option value="">Select District</option>');
            $.each(data, function(i,v){
                var selected = (v.id == oldDistrict) ? 'selected' : '';
                districtSelect.append('<option value="'+v.id+'" '+selected+'>'+v.name+'</option>');
            });
            populateTalukaDropdown();
        });
    }

    function populateTalukaDropdown(){
        var districtId = $('select[name="district"]').val();
        if(!districtId) return;

        $.get('/talukaDropdown/' + districtId, function(data){
            var oldTaluka = '{{ old("taluka", $requestData->taluka ?? "") }}';
            var talukaSelect = $('select[name="taluka"]');
            talukaSelect.empty().append('<option value="">Select Taluka</option>');
            $.each(data, function(i,v){
                var selected = (v.id == oldTaluka) ? 'selected' : '';
                talukaSelect.append('<option value="'+v.id+'" '+selected+'>'+v.name+'</option>');
            });
            populateVillageDropdown();
        });
    }

    function populateVillageDropdown(){
        var talukaId = $('select[name="taluka"]').val();
        if(!talukaId) return;

        $.get('/villageDropdown/' + talukaId, function(data){
            var oldVillage = '{{ old("village", $requestData->village ?? "") }}';
            var villageSelect = $('select[name="village"]');
            villageSelect.empty().append('<option value="">Select Village</option>');
            $.each(data, function(i,v){
                var selected = (v.id == oldVillage) ? 'selected' : '';
                villageSelect.append('<option value="'+v.id+'" '+selected+'>'+v.name+'</option>');
            });
        });
    }

    // DEPARTMENT → OFFICE → SUB-OFFICE (single, callback-aware versions only)
    $('select[name="applied_dept"]').on('change', function(){
        populateSubDeptDropdown(function(){
            checkPioStatus();
        });
    });

    $('select[name="applied_subdept"]').on('change', function(){
        populateOfficeDropdown(function(){
            checkPioStatus();
        });
    });

    $('select[name="applied_office"]').on('change', function(){
        checkPioStatus();
    });

    function populateSubDeptDropdown(callback){
        var deptId = $('select[name="applied_dept"]').val();
        if(!deptId) {
            if (typeof callback === 'function') callback();
            return;
        }

        $.get('/subDeptDropdown/' + deptId, function(data){
            var oldSubDept = '{{ old("applied_subdept", $requestData->applied_subdept ?? "") }}';
            var subDeptSelect = $('select[name="applied_subdept"]');
            subDeptSelect.empty().append('<option value="">Select Office</option>');
            $.each(data, function(i,v){
                var selected = (v.office_code == oldSubDept) ? 'selected' : '';
                subDeptSelect.append('<option value="'+v.office_code+'" '+selected+'>'+v.department+'</option>');
            });
            populateOfficeDropdown(callback);
        });
    }

    function populateOfficeDropdown(callback){
        var deptId = $('select[name="applied_dept"]').val();
        var subDeptId = $('select[name="applied_subdept"]').val();
        if(!subDeptId) {
            if (typeof callback === 'function') callback();
            return;
        }

        $.get('/officeDropdown/' + deptId + '/' + subDeptId, function(data){
            var oldOffice = '{{ old("applied_office", $requestData->applied_office ?? "") }}';
            var officeSelect = $('select[name="applied_office"]');
            officeSelect.empty().append('<option value="">Select Sub Office</option>');
            $.each(data, function(i,v){
                var selected = (v.suboffice_code == oldOffice) ? 'selected' : '';
                officeSelect.append('<option value="'+v.suboffice_code+'" '+selected+'>'+v.department+'</option>');
            });
            if (typeof callback === 'function') callback();
        });
    }

    // EDU STATUS (Educated / Non-Educated)
    function toggleQualificationDiv() {
        var eduStatus = $('input[name="edu_status"]:checked').val();
        if(eduStatus === 'educated' || eduStatus == 1){
            $('.hiddenQualificationDiv').show();
        } else {
            $('.hiddenQualificationDiv').hide();
        }
    }
    toggleQualificationDiv();
    $('input[name="edu_status"]').change(toggleQualificationDiv);

    // BPL Yes / No
    function toggleBPLDiv() {
        var bplStatus = $('input[name="is_bpl"]:checked').val();
        if(bplStatus === 'yes' || bplStatus == 1){
            $('.hiddenCardDiv').show();
        } else {
            $('.hiddenCardDiv').hide();
        }
    }
    toggleBPLDiv();
    $('input[name="is_bpl"]').change(toggleBPLDiv);

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

    document.getElementById('document').addEventListener('change', function(e){
        const fileInput = e.target;
        const currentFileDiv = document.getElementById('currentFile');

        if(fileInput.files && fileInput.files[0]){
            const fileName = fileInput.files[0].name;
            currentFileDiv.innerHTML = '<small>Selected File: ' + fileName + '</small>';
        } else {
            @if($filePath)
                currentFileDiv.innerHTML = '<small>Current File: <a href="{{ $filePath }}" target="_blank" id="fileLink">View Document</a></small>';
            @else
                currentFileDiv.innerHTML = '<small>No file uploaded yet.</small>';
            @endif
        }
    });

    // SUBMIT: full field validation, then PIO check
    var editForm = document.querySelector("form[name='edit_req_form']");

    editForm.addEventListener("submit", function(event) {
        var appliedDeptSelect = editForm.querySelector("#applied_dept");
        if (appliedDeptSelect.value === "") {
            alert("Please select a department.");
            appliedDeptSelect.focus();
            event.preventDefault();
            return;
        }

        var appliedSubDeptSelect = editForm.querySelector("#applied_subdept");
        if (appliedSubDeptSelect.value === "") {
            alert("Please select a sub department.");
            appliedSubDeptSelect.focus();
            event.preventDefault();
            return;
        }

        var appliedOffice = editForm.querySelector("#applied_office");
        if (appliedOffice.value === "") {
            alert("Please select a sub office.");
            appliedOffice.focus();
            event.preventDefault();
            return;
        }

        var applicantName = editForm.querySelector("#name");
        if (applicantName.value === "") {
            alert("Please enter your name.");
            applicantName.focus();
            event.preventDefault();
            return;
        }

        var address_line1 = editForm.querySelector("#address_line1");
        if (address_line1.value === "") {
            alert("Please enter your address line 1.");
            address_line1.focus();
            event.preventDefault();
            return;
        }

        var address_line2 = editForm.querySelector("#address_line2");
        if (address_line2.value === "") {
            alert("Please enter your address line 2.");
            address_line2.focus();
            event.preventDefault();
            return;
        }

        var address_line3 = editForm.querySelector("#address_line3");
        if (address_line3.value === "") {
            alert("Please enter your address line 3.");
            address_line3.focus();
            event.preventDefault();
            return;
        }

        var pincode = editForm.querySelector("#pincode");
        if (pincode.value === "") {
            alert("Please enter your pincode.");
            pincode.focus();
            event.preventDefault();
            return;
        }

        var state = editForm.querySelector("#state");
        if (state.value === "") {
            alert("Please select your state.");
            state.focus();
            event.preventDefault();
            return;
        }

        var district = editForm.querySelector("#district");
        if (district.value === "") {
            alert("Please select your district.");
            district.focus();
            event.preventDefault();
            return;
        }

        var taluka = editForm.querySelector("#taluka");
        if (taluka.value === "") {
            alert("Please select your taluka.");
            taluka.focus();
            event.preventDefault();
            return;
        }

        var village = editForm.querySelector("#village");
        if (village.value === "") {
            alert("Please select your village.");
            village.focus();
            event.preventDefault();
            return;
        }

        var mobile_no = editForm.querySelector("#mobile_no");
        var mobileNumberPattern = /^\d{10}$/;
        if (mobile_no.value === "" || !mobileNumberPattern.test(mobile_no.value)) {
            alert("Please enter a valid 10-digit mobile number.");
            mobile_no.focus();
            event.preventDefault();
            return;
        }

        var email = editForm.querySelector("#email_id");
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email.value === "" || !emailPattern.test(email.value)) {
            alert("Please enter a valid email address.");
            email.focus();
            event.preventDefault();
            return;
        }

        var eduStatusInputs = editForm.querySelectorAll('input[name="edu_status"]');
        var eduStatusChecked = false;
        eduStatusInputs.forEach(function(input) {
            if (input.checked) eduStatusChecked = true;
        });
        if (!eduStatusChecked) {
            alert("Please select Education status.");
            event.preventDefault();
            return;
        }

        if (editForm.querySelector('input[name="edu_status"]:checked').value === "educated") {
            var qualificationInputs = editForm.querySelectorAll('input[name="qualification"]');
            var qualificationChecked = false;
            qualificationInputs.forEach(function(input) {
                if (input.checked) qualificationChecked = true;
            });
            if (!qualificationChecked) {
                alert("Please select Qualification.");
                event.preventDefault();
                return;
            }
        }

        var textarea = editForm.querySelector("textarea[name='request_text']");
        if (textarea.value === "") {
            alert("Please explain your request.");
            textarea.focus();
            event.preventDefault();
            return;
        }

        if (textarea.value.length > 3000) {
            alert("Maximum length of request text is 3000.");
            textarea.focus();
            event.preventDefault();
            return;
        }

        // File is optional on edit — no file means "keep existing file"
        var fileUpload = editForm.querySelector("input[type='file']");
        if (fileUpload.files.length > 0 && fileUpload.files[0].size > 5 * 1024 * 1024) {
            alert("File size exceeds the limit of 5MB.");
            fileUpload.value = "";
            event.preventDefault();
            return;
        }

        // All field validation passed — now run PIO check before actually submitting
        event.preventDefault();
        checkPioStatus(function(canSubmit) {
            if (canSubmit) {
                editForm.submit();
            }
            // else: alert already shown inside checkPioStatus
        });
    });

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
                       // "For \n" +
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

</body>
</html>