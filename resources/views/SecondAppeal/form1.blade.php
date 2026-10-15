<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Online RTI Second Appeal Form</title>

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
</head>

<style>

.container.mt-5
{
    /* border: 2px solid black; */
    /* border-bottom: none; */
    border-top:none;
    margin-top:0 !important;
}

</style>

<body>

@include('UsersView.header')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 justify-content-center">
            <br>
            <div style="margin: 20px">
                @if(session('success'))
                    <div class="alert alert-success" id="successAlert">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning" id="warningAlert">
                        {{ session('warning') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger" id="errorAlert">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
            <div class="card" style="margin: 20px 20px">
                <h5 class="card-header">Online RTI Second Appeal Form (Online Request)</h5>
                <div class="card-body">
                    <form method="post" enctype="multipart/form-data" action="{{ route('sa-form1.submit') }}" novalidate>
                        @csrf
                        <div class="row mb-3 justify-content-center">
                            <div class="col-md-6">
                                <label for="fa_reg_no" class="form-label">RTI First Appeal Registration Number<span class="text-danger">*</span></label><br>
                                <ul style="color: blue; font-size: 14px; line-height: 1.6; font-family: sans-serif;">
                                    <li>If the First Appeal has not been disposed of, a Second Appeal can be filed only after 30 days from the First Appeal submission date.</li>
                                    <li>A Second Appeal can be filed only within 90 days of First Appeal disposal.</li>
                                </ul>
                                <input type="text" class="form-control @error('fa_reg_no') is-invalid @enderror" id="fa_reg_no" name="fa_reg_no" placeholder="First Appeal Registration Number" value="{{ old('fa_reg_no') }}" required>
                                @error('fa_reg_no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3 justify-content-center">
                            <div class="col-md-6">
                                <label for="emailId" class="form-label">Email Address<span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('emailId') is-invalid @enderror" id="emailId" name="emailId" placeholder="Enter your Email ID" value="{{ old('emailId') }}" required>
                                @error('emailId')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <br>
                        <div class="row justify-content-center">
                            <div class="col-md-3 mb-2">
                                <button style="width: 100%" type="submit" class="btn btn-primary btn-block">Submit</button>
                            </div>
                            <div class="col-md-3 mb-2">
                                <button style="width:100%" type="reset" class="btn btn-secondary btn-block">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>
@include('UsersView.footer')

</body>

<script>

    setTimeout(() => {
        const successAlert = document.getElementById("successAlert");
        const warningAlert = document.getElementById("warningAlert");
        const errorAlert = document.getElementById("errorAlert");

        if (successAlert) successAlert.style.display = "none";
        if (warningAlert) warningAlert.style.display = "none";
        if (errorAlert) errorAlert.style.display = "none";

    }, 10000);

</script>



</html>
