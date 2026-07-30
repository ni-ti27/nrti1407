<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>First Form Submission</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

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

                @if(Session::has('success'))
                    <div class="alert alert-success text-center">
                        {{Session::get('success')}}
                    </div>
                @endif
                <form id="first_req_form" name="first_req_form" method="post" enctype="multipart/form-data" action="{{ route('store-profile') }}" novalidate>
                    @csrf
                    @if ($errors->has('address_length_error'))
                        <div class="alert alert-danger">
                            <ul>
                                <li>{{ $errors->first('address_length_error') }}</li>
                            </ul>
                        </div>
                    @endif
                    <br>       <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <h5 class="card-header">User Profile</h5>
                                <div class="card-body">
                                    <div class="row justify-content-center">
                                        <div class="form-group col-md-6 mb-2">
                                            <label for="name">Name<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name', $profileData->name ?? '') ?: session('username') }}" placeholder="Firstname Lastname">
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
                                            <input type="text" class="form-control @error('mobile_no') is-invalid @enderror" name="mobile_no" id="mobile_no" value="{{ old('mobile_no', $profileData->mobile_no ?? '') ?: session('mobile_no') }}" placeholder="+91 9865478864" readonly>
                                            @error('mobile_no')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group mb-2 col-md-4">
                                            <label for="email_id">Email Address<span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('email_id') is-invalid @enderror" name="email_id" id="email_id" value="{{ old('email_id', $profileData->email_id ?? '') ?: session('email_id') }}"  placeholder="WJrjv@example.com" readonly>
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
                                        <div class="form-group col-md-4 mb-2 hiddenQualificationDiv" style="{{ old('edu_status', $profileData->edu_status ?? '') == 1 ? '' : 'display:none;' }}">
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

       <div class="d-grid gap-2 d-md-flex mt-3">
    <button type="submit" name="send" class="btn btn-success flex-fill">
        Update
    </button>
    @if($show_skip == 1)
    <button type="submit" name="skip" value="1" class="btn btn-outline-secondary flex-fill">
        Skip
    </button>
    @endif
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


            // if (fileUpload.value === "") {
            //     alert("Please upload a file.");
            //     fileUpload.focus();
            //     event.preventDefault();
            //     return;
            // }

            // console.log(fileUpload);
            // console.log(fileUpload.value);



            // console.log(fileUpload.files);
            // event.preventDefault();
            //         return false;
        if (event.submitter && event.submitter.name === 'skip') {
            return true;
        }

        if (!confirm("Are you sure you want to update?")) {
            event.preventDefault();
            return false;
        }

            form.submit();
        });
    });



$(document).ready(function()
{

    //$('.hiddenCardDiv').hide();

    populateDistrictDropdown();

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






});

</script>
</html>
