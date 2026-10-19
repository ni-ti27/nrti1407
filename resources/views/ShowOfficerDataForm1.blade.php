<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Update</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
    <!-- Add additional CSS or stylesheets here -->
</head>

<style>
    .container.mt-5 {
        /* border: 2px solid black; */
        border-bottom: none;
        /* border-top:none; */
        margin-top: 0 !important;
    }

    .custom-margin {
        margin-left: 10px; /* Adjust margin as needed */
    }

    .datepicker {
    width: 280px !important;
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    font-size: 14px;
}

/* Header styling (month/year) */
.datepicker table tr td.day,
.datepicker table tr td.disabled,
.datepicker table tr td.today,
.datepicker table tr td.active {
    width: 36px;
    height: 36px;
    line-height: 36px;
    border-radius: 50%;
}

/* Highlight today */
.datepicker table tr td.today {
    background-color: #e0f7fa;
    color: #007bff;
}

/* Highlight selected day */
.datepicker table tr td.active {
    background-color: #007bff;
    color: #fff;
}

/* Navigation arrows bigger */
.datepicker .prev, .datepicker .next {
    font-size: 16px;
    width: 30px;
    height: 30px;
    line-height: 30px;
}

/* Responsive on small screens */
@media (max-width: 576px) {
    .datepicker {
        width: 90vw !important;
    }
}
</style>
<!-- jQuery (required) -->

<body>
    @include('OfficerView.Header')
    <div class="container mt-5">
        <br>
        <h4>Officer's Profile Update</h4><br>
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="row justify-content-center">
                    <div>
                        @if(session('success'))
                            <br>
                            <div class="alert alert-success text-center">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if(session('warning'))
                            <br>
                            <div class="alert alert-warning text-center">
                                {{ session('warning') }}
                            </div>
                        @endif
                        @if(session('error'))
                            <br>
                            <div class="alert alert-danger text-center">
                                {{ session('error') }}
                            </div>
                        @endif
                    </div>

                    @if(session('VerifiedOTP') != true)
                    <div class="card">
                        <h5 class="card-header">Verify Registered Mobile Number:</h5>

                        <div class="card-body">
                            <div class="row justify-content-center">
                                <form method="POST" action="{{ route('officerVerifyOtp') }}" id="sendOTPForm" name="sendOTPForm">
                                    @csrf
                                    <div class="row justify-content-center">
                                        <div class="form-group offset-md-3 col-md-3">
                                            <label for="mobile_no">Officer Mobile Number<span class="text-danger">*</span></label><br>
                                            <span style="color: blue; font-size: 12px">(For receiving SMS alerts)</span>
                                            <input type="text" class="form-control @error('mobile_no') is-invalid @enderror" name="mobile_no" id="mobile_no" value="{{ old('mobile_no', $mobileTest ?? '') }}">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <br><br>
                                            <button type="button" class="btn btn-secondary" id="sendOTP">Send OTP</button>
                                            <button type="button" class="btn btn-secondary" style="display:none" id="resendOTP">Re Send</button>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row justify-content-center">
                                        <div class="row justify-content-center verifyOtpBlock">
                                            <div class="form-group col-md-3">
                                                <input type="text" class="form-control" id="otp" name="otp" placeholder="Enter OTP">
                                                @if($errors->has('otp'))
                                                    <br>
                                                    <div class="col-md-5">
                                                        @error('otp')
                                                            <span style="color:red" class="invalid feedback">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="form-group col-md-3">
                                                <button type="button" class="btn btn-secondary" id="verifyOtp">Verify</button>
                                            </div>
                                        </div>
                                        <div class="row justify-content-end" style="margin-top:20px">
                                            <div class="col-auto">
                                                <button type="button" class="btn btn-success w-100" id="closeForm">Ask Later</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(session('VerifiedOTP') == true)
                    <div class="row justify-content-center">
                        <div class="col-md-12" style="margin: 20px 20px">
                            <form method="POST" action="{{ route('updateOfficerData') }}" id="profileUpdate" name="profileUpdate">
                                @csrf
                                @if($errors->has('address_length_error'))
                                    <div class="alert alert-danger">
                                        {{ $errors->first('address_length_error') }}
                                    </div>
                                @endif
                                <div class="card">
                                    <h5 class="card-header">Department Details:</h5>
                                    <div class="card-body">
                                        <div class="row justify-content-center">
                                            <div class="col-md-2">
                                                <p><strong>Department: </strong></p>
                                                <p><strong>Office: </strong></p>
                                                <p><strong>Sub Office: </strong></p>
                                            </div>
                                            <div class="col-md-3">
                                                <p>{{ Session::get('department_name') }}</p>
                                                <p>{{ Session::get('subDepartment_name') }}</p>
                                                <p>{{ Session::get('officeDepartment_name') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>

                                <div class="card">
                                    <h5 class="card-header">Personal Details:</h5>
                                    <div class="card-body">
                                        <div class="row justify-content-center">
                                            <div class="form-group col-md-6">
                                                <p><strong>UserName: </strong>{{ Session::get('username') }}</p>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <p><strong>Role: </strong>{{ Session::get('roleName') }}</p>
                                            </div>
                                        </div>

                                        <div class="row justify-content-center">
                                            <div class="form-group col-md-6">
                                                <label for="officerName">Officer Name<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('officerName') is-invalid @enderror" name="officerName" id="officerName" value="{{ old('officerName', $getUserDetails->name ?? '') }}">
                                                @error('officerName')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="gender">Gender</label>
                                                <select class="form-control" name="gender" id="gender">
                                                    <option value="M" {{old('gender', $getUserDetails->gender ?? '') == 'M' ? 'selected' : '' }}>Male</option>
                                                    <option value="F" {{old('gender', $getUserDetails->gender ?? '')== 'F' ? 'selected' : '' }}>Female</option>
                                                    <option value="O" {{ old('gender', $getUserDetails->gender ?? '')== 'O' ? 'selected' : '' }}>Other</option>
                                                </select>
                                                @error('gender')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <br>

                                        <div class="row justify-content-center">
                                            <div class="form-group col-md-6">
                                                <label for="designation">Official Designation<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('designation') is-invalid @enderror" name="designation" id="designation" value="{{ old('designation', $getUserDetails->designation ?? '') }}">
                                                @error('designation')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="email_address">Email Address<span class="text-danger">*</span></label>
                                                <span style="color: blue; font-size: 12px">(For receiving alerts)</span>
                                                <input type="text" class="form-control @error('email_address') is-invalid @enderror" name="email_address" id="email_address" value="{{ old('email_address', $getUserDetails->email ?? '') }}">
                                                @error('email_address')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <br>
                                        <div class="row justify-content-center">
                                            <div class="form-group col-md-3">
                                                <label for="mobile_no">Officer Mobile Number</label><br>
                                                <span style="color: blue; font-size: 12px">(For receiving SMS alerts)</span>
                                                <input type="text" class="form-control @error('officerMobileNumber') is-invalid @enderror" name="mobile_no" id="mobile_no" value="{{ session::get('officerMobileNumber') }}" readonly>
                                            </div>
                                        </div>

                                        <br>
                                    </div>
                                </div>
                                <br>

                                <div class="card">
                                    <h5 class="card-header">Additional Details:</h5>
                                    <div class="card-body">
                                        <!-- Added new fields -->
                                        <div class="row justify-content-center">
                                            <div class="form-group col-md-6">
                                                <label for="address1">Official Address Line 1</label>
                                                <input type="text" class="form-control @error('address1') is-invalid @enderror" name="address1" id="address1" value="{{ old('address1', $getUserDetails->address1 ?? '') }}">
                                                @error('address1')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="address2">Official Address Line 2</label>
                                                <input type="text" class="form-control @error('address2') is-invalid @enderror" name="address2" id="address2" value="{{ old('address2', $getUserDetails->address2 ?? '') }}">
                                                @error('address2')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <br>
                                        <div class="row justify-content-center">
                                            <div class="form-group col-md-6">
                                                <label for="address3">Official Address Line 3</label>
                                                <input type="text" class="form-control @error('address3') is-invalid @enderror" name="address3" id="address3" value="{{ old('address3', $getUserDetails->address3 ?? '') }}">
                                                @error('address3')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="service_retirement_dt">Service Retirement Date</label>
                                                <input type="text"
       class="form-control @error('service_retirement_dt') is-invalid @enderror"
       name="service_retirement_dt"
       id="service_retirement_dt"
       value="{{ old('service_retirement_dt', $getUserDetails->service_retirement_dt ? \Carbon\Carbon::parse($getUserDetails->service_retirement_dt)->format('d-m-Y') : '') }}">
                                                @error('service_retirement_dt')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <br>
                                        <div class="row justify-content-center">
                                            <div class="form-group col-md-6">
                                                <label for="phone_no">Office Phone Number</label><br>
                                                <input type="text" class="form-control @error('phone_no') is-invalid @enderror" name="phone_no" id="phone_no" value="{{ old('phone_no', $getUserDetails->phone_no ?? '') }}">
                                                @error('phone_no')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="state">State</label>

                                                <select class="form-control @error('state') is-invalid @enderror" id="state_display" disabled>
                                                    @foreach($states as $state)
                                                        <option value="{{ $state->id }}" {{ $state->id == $goaId ? 'selected' : '' }}>
                                                            {{ $state->name }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <input type="hidden" name="state" value="{{ $goaId }}">

                                                @error('state')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                        </div>
                                        <br>
                                    </div>
                                </div>

                                <br>
                                <div class="row justify-content-center">
                                    <div class="col-md-6">
                                        <div class="form-group text-center">
                                            <button type="button" class="btn btn-primary" id="submitData" name="submitData" onclick="validate()">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif
                    <br><br>
                </div>
            </div>
        </div>
    </div>
    <br>
    @include('UsersView.footer')
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">

$(document).ready(function(){
    $('#service_retirement_dt').datepicker({
        format: 'dd-mm-yyyy',   // Display format
        autoclose: true,        // Close picker when date is selected
        todayHighlight: true    // Highlight today
    });
});
document.addEventListener("DOMContentLoaded", function() {

    var closeFormButton = document.getElementById('closeForm');

    if (closeFormButton) {
        closeFormButton.addEventListener('click', function() {
            var userRole = @json(session('role'));
            switch(userRole) {
                case 4:
                    window.location.href = '/RTIMIS/dashboard/{new_FA}';
                    break;
                case 5:
                case 6:
                case 7:
                    window.location.href = '/RTIMIS/dashboard/{new_SA}';
                    break;
                default:
                    window.location.href = '/RTIMIS/dashboard/';
                    break;
            }
        });
    }

    var sendOTPButton = document.getElementById('sendOTP');
    var verifyOtpButton = document.getElementById('verifyOtp');

    // Check if the elements exist before attaching event listeners
    if (sendOTPButton) {
        sendOTPButton.addEventListener('click', handleSubmitForm);
    }

    if (verifyOtpButton) {
        verifyOtpButton.addEventListener('click', handleSubmitForm);
    }
});

    function validate() {
        var form = document.getElementById('profileUpdate');
        var emailId = document.getElementById('email_address');
        var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        var mobile_no = document.getElementById('mobile_no');
        var designation = document.getElementById('designation');
        var officerName = document.getElementById('officerName');

        if (officerName.value.trim() == '') {
            alert("Invalid Officer Name");
            officerName.focus();
            event.preventDefault();
            return;
        }

        if (designation.value.trim() == '') {
            alert("Invalid Designation");
            designation.focus();
            event.preventDefault();
            return;
        }

        if (regex.test(emailId.value) != true) {
            alert("Invalid Email Id");
            emailId.focus();
            event.preventDefault();
            return;
        }

        form.submit();
    };

    function handleSubmitForm() {
        var form = document.getElementById('sendOTPForm');
        var mobileNo = document.getElementById('mobile_no');
        var verifyCode = document.getElementById('otp');

        var regex = /^\d{10}$/;
        if (regex.test(mobileNo.value) != true) {
            alert("Invalid Mobile Number");
            mobileNo.focus();
            event.preventDefault();
            return;
        }

        form.submit();
    }

</script>
</html>