<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Applicant Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<style>
    .invalid-feedback {
    color: red;
    font-size: 14px;
}
</style>

</head>
<body>

@include('UsersView.header')
<div class="container mt-5" style="margin-top: 0px !important;">
    <div class="row justify-content-center" style="border:none">
        <div class="col-md-8">
                @if(session('warning'))
                    <br>
                    <div class="alert alert-warning text-center">
                        {{ session('warning') }}
                    </div>
                @endif
                @if(session('error'))
                    <br>
                    <div class="alert alert-warning text-center">
                        {{ session('error') }}
                    </div>
                @endif

            <div class="card" style="margin: 20px 20px">
                <h5 class="card-header">RTI Applicant Login</h5>
                <div class="card-body">
                    <form id="rtiRequestForm" method="post" enctype="multipart/form-data" action="{{ route('sessionForm.save') }}" novalidate>
                        @csrf
                        <div class="row mb-3 justify-content-center">
                            <div class="col-md-6">
                                <label for="username" class="form-label">Name:</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Firstname Lastname" value="{{ old('username') }}" required     class="@error('username') is-invalid @enderror">
                                @if($errors->has('username'))
                                <br>
                                <div>
                                    @error('username')
                                    <span style="color:red" class="invalid feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                @endif
                            </div>

                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-md-6">
                                <label for="email_id" class="form-label">Email Id:</label>
                                <input type="email" class="form-control" id="email_id" name="email_id" placeholder="Enter your email" value="{{ old('email_id') }}">
                                @if($errors->has('email_id'))
                                <br>
                                <div>
                                    @error('email_id')
                                    <span style="color:red" class="invalid feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-md-4">
                                <label for="mobile_no" class="form-label">Mobile Number:</label>
                                <input type="text" class="form-control" id="mobile_no" name="mobile_no" placeholder="Enter your mobile number" value="{{ old('mobile_no') }}" required>
                                @if($errors->has('mobile_no'))
                                <br>
                                <div>
                                    @error('mobile_no')
                                    <span style="color:red" class="invalid feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                @endif
                            </div>
                            <div class="col-md-2">
                                <label for="sendOTP" class="form-label">Send OTP:</label>
                                <button type="button" class="btn btn-secondary" id="sendOTP">Send</button>
                                <button type="button" class="btn btn-secondary" style="display:none" id="resendOTP">Re Send</button>
                                <div id="mobileNoError" class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div id="timerContainer" class="text-center mt-2"></div>

                        <div class="row mb-3 justify-content-center">
                            <div class="col-md-6">
                                <label for="otp" class="form-label">Enter OTP:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="otp" name="otp" placeholder="Enter OTP">
                                    @if($errors->has('otp'))
                                    <br>
                                    <div class="col-md-12">
                                        @error('otp')
                                        <span style="color:red" class="invalid feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <br>
                        <div class="row justify-content-center">
                            <div class="col-md-3 mb-2">
                                <button id="loginButton" style="width: 100%" type="submit" class="btn btn-primary btn-block">Log-In</button>
                            </div>
                            <!-- <div class="col-md-3 mb-2">
                                <button style="width:100%" type="reset" class="btn btn-secondary btn-block">Reset</button>
                            </div> -->
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="row mb-3 justify-content-center">
                        <div class="col-md-6">
                            <strong>Refer this User Manual if required: </strong>
                            <a href="../Applicant_Module_User_Manual.pdf" target="_blank" download>User Manual</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('UsersView.footer')

<script>
    // document.getElementById("loginButton").disabled = true;
    document.getElementById('rtiRequestForm').addEventListener('submit', function(event) {
        event.preventDefault();
        validateForm('submission');
    });

    document.getElementById('sendOTP').addEventListener('click', function()
    {
        if(validateForm('otp'))
        {
            this.disabled = true;

            // var timerContainer = document.getElementById('timerContainer');
            // var count = 4;
            // timerContainer.textContent = '00:' + (count < 10 ? '0' : '') + count;

            var username = document.getElementById('username').value;
            var mobileNo = document.getElementById('mobile_no').value;
            var emailId = document.getElementById('email_id').value;

            // var timer = setInterval(function()
            // {
            //     count--;
            //     timerContainer.textContent = '00:' + (count < 10 ? '0' : '') + count;
            //     if (count <= 0)
            //     {
            //         clearInterval(timer);
            //         timerContainer.textContent = ''; // Remove the timer text
            //         document.getElementById('sendOTP').style.display = 'none';
            //         document.getElementById('resendOTP').style.display = 'block';
            //     }
            // },1000);
            document.getElementById("loginButton").disabled = false;
        }
    });

    function validateForm(method) {
        var username = document.getElementById('username').value;
        var mobileNo = document.getElementById('mobile_no').value;
        var emailId = document.getElementById('email_id').value;
        var usernameError = document.getElementById('usernameError');
        var mobileNoError = document.getElementById('mobileNoError');
        var emailIdError = document.getElementById('emailIdError');
        var isValid = true;

        if (username.trim() === '') {
            isValid = false;
            alert('Name is required.');
            return false;
        }

        if (emailId.trim() === '') {
            isValid = false;
            alert('Email is required.');
            return false;
        }

        if (mobileNo.trim() === '') {
            isValid = false;
            alert('Mobile number is required.');
            return false;
        }
        else
        {
            if (isNaN(mobileNo))
            {
                alert('Inproper Mobile number.');
            }

            if(mobileNo.length != 10)
            {
                isValid = false;
                alert('Mobile number should be of 10 digits.');
                return false;
            }
        }

        if (isValid) {
            if(method != 'otp')
                document.getElementById('rtiRequestForm').submit();
            else
            {
                var additionalDataField = document.createElement("input");
                additionalDataField.setAttribute("type", "hidden");
                additionalDataField.setAttribute("name", "sendOtp");
                additionalDataField.setAttribute("value", "true");
                document.getElementById('rtiRequestForm').appendChild(additionalDataField);
                document.getElementById('rtiRequestForm').submit();
                return true;
            }
        }
        else
            return false;
    }
</script>


</body>
</html>
