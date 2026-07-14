<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add additional CSS or stylesheets here -->
</head>

<body>
@include('UsersView.header')

    <div class="container mt-5">
        <br>
        <h4>Officer's Password Reset</h4><br>
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

                    @if(session('VerifiedResetOTP') != true)
                    <div class="card">
                        <h5 class="card-header">Verify Reset OTP</h5>

                        <div class="card-body">
                            <div class="row justify-content-center">
                                <form id="otpForm" method="POST" action="{{ route('verifyResetOTP') }}" enctype="multipart/form-data">
                                @csrf

                                    <div class="row justify-content-center">
                                        <div class="form-group">
                                            <label for="username">Officer Username<span class="text-danger">*</span></label><br>
                                            <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" id="username" value="{{ old('username', $username ?? '') }}" readonly>
                                        </div>
                                    </div>

                                    <br>
                                    <div class="row justify-content-center">
                                        <div class="form-group">
                                            <label for="mobile_no">Officer Mobile Number<span class="text-danger">*</span></label><br>
                                            <input type="text" class="form-control @error('mobile_no') is-invalid @enderror" name="mobile_no" id="mobile_no" value="{{ old('mobile_no', $mobileNumber ?? '') }}" readonly>
                                        </div>
                                    </div>

                                    <br>
                                    <div class="row justify-content-center">
                                        <div class="form-group">
                                            <label for="otp">Enter OTP<span class="text-danger">*</span></label>
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
                                    </div>
                                    <br>
                                    <div class="row justify-content-center">
                                        <div class="form-group" style="text-align:center">
                                            <button type="button" class="btn btn-primary" id="verifyOtp">Verify</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
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


<script type="text/javascript">

document.getElementById('verifyOtp').addEventListener('click', function(event)
{
    event.preventDefault();

    // Get the form and form elements
    var form = document.getElementById('otpForm');
    var mobileNo = document.getElementById('mobile_no');
    var otp = document.getElementById('otp');

    // Validate Mobile Number (should be 10 digits)
    var mobileRegex = /^\d{10}$/;
    if (!mobileRegex.test(mobileNo.value)) {
        alert("Invalid Mobile Number");
        mobileNo.focus();
        return;
    }

    // Validate OTP (if necessary, this can also be added here)
    if (otp.value.trim() === "") {
        alert("Please enter the OTP.");
        otp.focus();
        return;
    }

    // Submit the form if all validations pass
    form.submit();
});

</script>
</html>