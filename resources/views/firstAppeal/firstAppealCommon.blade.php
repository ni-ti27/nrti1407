<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Online RTI First Appeal Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
    {{-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> --}}

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

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
                <h5 class="card-header">Online RTI First Appeal Form</h5>
                <div class="card-body">
                    <form method="post" enctype="multipart/form-data" action="{{ route('first-appeal') }}" novalidate>
                        @csrf
                        <div class="row mb-3 justify-content-center">
                            <div class="col-md-6">
                                <label for="reg_no" class="form-label">RTI Request Registration Number<span class="text-danger">*</span></label><br>
                                <span style="color: blue; font-size: 12px">( First Appeal can be filed only after 30 days of e-filing of initial RTI Request or
 It can be filed immediately if the reply received is incomplete or unsatisfactory )
                                </span>
                                <input type="text" class="form-control @error('reg_no') is-invalid @enderror" id="reg_no" name="reg_no" placeholder="Registration Number" value="{{ old('reg_no') }}" required>
                                @error('reg_no')
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
                <div class="row justify-content-center">
                    <div class="col-md-12 text-center">
                        <strong>Refer this Manual if required: </strong>
                        <a href="../FAA_User_Manual.pdf" target="_blank" download>FA User Manual</a>
                    </div>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


</html>
