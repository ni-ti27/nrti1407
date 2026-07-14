<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add additional CSS or stylesheets here -->
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
    @include('OfficerView.Header')

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6" style="margin: 20px 20px">
                <div class="row justify-content-center">
                    <div style="margin: 20px">
                        @if(session('success'))
                            <div class="alert alert-success" id="successAlert">
                                {{ nl2br(e(session('success'))) }}
                            </div>
                        @endif

                        @if(session('warning'))
                            <div class="alert alert-warning" id="warningAlert">
                                {{  nl2br(e(session('warning'))) }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger" id="errorAlert">
                                {{  nl2br(e(session('error'))) }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <h5 class="card-header text-center text-white" style="background-color:#52a9e8;">Change Password</h5>
                    <div class="card-body">
                        <form method="POST" action="{{ route('changePassword') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" value="{{ old('username') ? old('username') : session('username') }}" required autofocus readonly>
                                @error('username')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <input type="hidden" id="defaultPassword" name="defaultPassword" value = "{{ session('defaultPassword') }}">

                            <div class="mb-3" style="{{ session('defaultPassword') ? 'display: none;' : 'display: block;' }}">
                                <label for="systemPass" class="form-label">Enter Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="systemPass" name="systemPass" placeholder="System Password" maxlength='20' readonly minlength='8' value="rTiGoA@#2005">
                                    <button type="button" class="btn btn-outline-secondary" id="toggleSysPassword">
                                    <img id="eyeIconSys" src="{{ asset('/images/closeeye.svg') }}" alt="Show password" width="24" height="24">
                                    </button>
                                </div>
                                @error('systemPass')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3" style="{{ session('defaultPassword') ? 'display: block;' : 'display: none;'}}">
                                <label for="oldPass" class="form-label">Enter Previous Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="oldPass" name="oldPass" placeholder="Enter your old password">
                                    <button type="button" class="btn btn-outline-secondary" id="toggleOldPassword">
                                    <img id="eyeIconSys" src="{{ asset('/images/closeeye.svg') }}" alt="Show password" width="24" height="24">
                                    </button>
                                </div>
                                @error('oldPass')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="newPassword1" class="form-label">Enter New Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="newPassword1" name="newPassword1" placeholder="Enter your password" required maxlength='20' minlength='8' >
                                    <button type="button" class="btn btn-outline-secondary" id="toggleNewPassword1">
                                    <img id="eyeIconSys" src="{{ asset('/images/closeeye.svg') }}" alt="Show password" width="24" height="24">
                                    </button>
                                </div>
                                @error('newPassword1')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="newPassword2" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="newPassword2" name="newPassword2" placeholder="Enter your password again" required maxlength='20' minlength='8' >

                                    <button type="button" class="btn btn-outline-secondary" id="toggleNewPassword2">
                                        <img id="eyeIconSys" src="{{ asset('/images/closeeye.svg') }}" alt="Show password" width="24" height="24">
                                    </button>
                                </div>
                                @error('newPassword2')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                    <span>{{ $error }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <br>

                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" class="btn btn-primary w-100" id="validateDetails">Change</button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-success w-100" id="closeForm">Continue</button>
                                </div>
                            </div>

                            <br>

                            <div class="row justify-content-center">
                                <h4>Rules for creating new password:</h4>
                                <li>Should contain minimum 8 characters</li>
                                <li>Should contain maximum 20 characters</li>
                                <li>Should contain minimum 1 upper characters</li>
                                <li>Should contain minimum 1 lower characters</li>
                                <li>Should contain 1 special character</li>
                                <li>Should contain 1 minimum number</li>
                                <li>Should not contain consecutive numbers</li>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('UsersView.footer')
</body>

<script type="text/javascript">


document.getElementById('closeForm').addEventListener('click', function()
{
    var userRole = @json(session('role'));
    switch(userRole)
    {
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


document.getElementById('toggleOldPassword').addEventListener('click', function ()
{
    var passwordInput = document.getElementById('oldPass');
    var toggleButton = this;

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleButton.innerHTML = `<img id="eyeIcon" src="{{ asset('/images/openeye.svg') }}" alt="Show password" width="24" height="24">`;
    } else {
        passwordInput.type = 'password';
        toggleButton.innerHTML = `<img id="eyeIcon" src="{{ asset('/images/closeeye.svg') }}" alt="Hide password" width="24" height="24">`;
    }
});

document.getElementById('toggleNewPassword1').addEventListener('click', function ()
{
    var passwordInput = document.getElementById('newPassword1');
    var toggleButton = this;

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleButton.innerHTML = `<img id="eyeIcon" src="{{ asset('/images/openeye.svg') }}" alt="Show password" width="24" height="24">`;
    } else {
        passwordInput.type = 'password';
        toggleButton.innerHTML = `<img id="eyeIcon" src="{{ asset('/images/closeeye.svg') }}" alt="Hide password" width="24" height="24">`;
    }
});


document.getElementById('toggleNewPassword2').addEventListener('click', function ()
{
    var passwordInput = document.getElementById('newPassword2');
    var toggleButton = this;

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleButton.innerHTML = `<img id="eyeIcon" src="{{ asset('/images/openeye.svg') }}" alt="Show password" width="24" height="24">`;
    } else {
        passwordInput.type = 'password';
        toggleButton.innerHTML = `<img id="eyeIcon" src="{{ asset('/images/closeeye.svg') }}" alt="Hide password" width="24" height="24">`;
    }
});


document.getElementById('toggleSysPassword').addEventListener('click', function ()
{
    var passwordInput = document.getElementById('systemPass');
    var toggleButton = this;

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleButton.innerHTML = `<img id="eyeIcon" src="{{ asset('/images/openeye.svg') }}" alt="Show password" width="24" height="24">`;
    } else {
        passwordInput.type = 'password';
        toggleButton.innerHTML = `<img id="eyeIcon" src="{{ asset('/images/closeeye.svg') }}" alt="Hide password" width="24" height="24">`;
    }
});


document.getElementById('validateDetails').addEventListener('click', function ()
{
    var passwordInput1 = document.getElementById('newPassword1');
    var passwordInput2 = document.getElementById('newPassword2');

    if(passwordInput1.value != passwordInput2.value)
    {
        alert('Entered Password does not match with the confirm password field');
        event.preventDefault();
        return false;
    }
});


document.getElementById('newPassword1').addEventListener('change', function()
{
    var passwordInput1 = document.getElementById('newPassword1');

    if (passwordInput1.length < 8 || passwordInput1.length > 20)
    {
        alert("Password must be between 8 and 20 characters.");
        event.preventDefault();
        return false;
    }

    if (!/[A-Z]/.test(passwordInput1.value))
    {
        alert("Password must contain at least one uppercase letter.");
        event.preventDefault();
        return false;
    }

    if (!/[a-z]/.test(passwordInput1.value)) {
        alert("Password must contain at least one lowercase letter.");
        event.preventDefault();
        return false;
    }

    if (!/[!@#$&().?:{}|<>]/.test(passwordInput1.value)) {
        alert("Password must contain at least one special character.");
        event.preventDefault();
        return false;
    }

    if (/(012|123|234|345|456|567|678|789)/.test(passwordInput1.value)) {
        alert("Password cannot contain consecutive numbers.");
        event.preventDefault();
        return false;
    }

});


</script>

</html>
