<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Applicant Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
    {{-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> --}}
</head>
<body>

@include('UsersView.header')
<div class="container mt-5" style="margin-top: 0px !important;">
    <div class="row justify-content-center" style="border:none;">
        <div class="col-md-8">
            <div class="card" style="margin: 20px 20px">
                <h5 class="card-header">RTI Applicant Login</h5>
                <div class="card-body">
                    <form id="rtiRequestForm" method="post" enctype="multipart/form-data" action="{{ route('sessionForm.save') }}" novalidate>
                        @csrf
                        <div class="row mb-3 justify-content-center">
                            <div class="col-md-6">
                                <label for="username" class="form-label">Name:</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Firstname Lastname" value="{{ old('username') }}" required>
                                <div id="usernameError" class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row mb-3 justify-content-center">
                            <div class="col-md-6">
                                <label for="mobile_no" class="form-label">Mobile Number:</label>
                                <input type="text" class="form-control" id="mobile_no" name="mobile_no" placeholder="Enter your mobile number" value="{{ old('mobile_no') }}" required>
                                <div id="mobileNoError" class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row mb-3 justify-content-center">
                            <div class="col-md-6">
                                <label for="email_id" class="form-label">Email Id:</label>
                                <input type="email" class="form-control" id="email_id" name="email_id" placeholder="Enter your email" value="{{ old('email_id') }}">
                                <div id="emailIdError" class="invalid-feedback"></div>
                            </div>
                        </div>
                        <br>
                        <div class="row justify-content-center">
                            <div class="col-md-3 mb-2">
                                <button style="width: 100%" type="submit" class="btn btn-primary btn-block">Submit</button>
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
    document.getElementById('rtiRequestForm').addEventListener('submit', function(event) {
        event.preventDefault();
        validateForm();
    });

    function validateForm() {
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
            usernameError.textContent = 'Name is required.';
        }

        if (mobileNo.trim() === '') {
            isValid = false;
            alert('Mobile number is required.');
            mobileNoError.textContent = 'Mobile number is required.';
        }

        if (emailId.trim() === '') {
            isValid = false;
            alert('Email is required.');
            emailIdError.textContent = 'Email is required.';
        }

        if (isValid) {
            document.getElementById('rtiRequestForm').submit();
        }
    }
</script>


</body>
</html>
