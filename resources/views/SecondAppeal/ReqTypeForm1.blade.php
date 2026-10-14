<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Online RTI Second Appeal Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
    {{-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> --}}


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
                <h5 class="card-header">Request Type Check Form</h5>
                <div class="card-body">
                    <form method="post" enctype="multipart/form-data" action="{{ route('request-form-handle') }}" novalidate>
                        @csrf
                        <div class="row mb-3 justify-content-center">
                            <label class="form-label">Request Type:</label>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="request_type" id="online_request" value="online" {{ old('request_type') == 'online' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="online_request">
                                        Previously Filed First Appeal Was Online
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="request_type" id="offline_request" value="offline" {{ old('request_type') == 'offline' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="offline_request">
                                    Previously Filed First Appeal Was Offline
                                    </label>
                                </div>
                            </div>
                            @error('request_type')
                            <br><br>
                            <span class="invalid-feedback" role="alert" style="display:block; text-align:center">
                            <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

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
