<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add additional CSS or stylesheets here -->
</head>

<style>
    .container.mt-5
    {
        /* border: 2px solid black; */
        border-bottom: none;
        /* border-top:none; */
        margin-top:0 !important;
    }
</style>

<body>
@include('UsersView.header')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6" style="margin: 20px 20px">
                <div class="card">
                    <h5 class="card-header text-center text-white" style="background-color:#52a9e8;">Officers Login</h5>
                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" value="{{ old('username') }}" required autofocus>
                                @error('username')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="systemPass" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="systemPass" name="systemPass" placeholder="Enter your system password" value ="rTiGoA@#2005">
                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                        <img id="eyeIcon" src="{{ asset('/images/closeeye.svg') }}" alt="Show password" width="24" height="24">
                                    </button>
                                </div>
                                @error('systemPass')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Passcode (Digit Code)</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your passcode">
                                @error('password')
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

                            <div class="d-grid gap-2">
                                <button name="action" type="submit" value="login" class="btn btn-secondary">Login</button>
                            </div>

                            <br>

                            <div class="d-grid gap-2" style="text-align:center">
                                <button name="action" type="submit" value="forgot_password" class="btn btn-link">Forgot Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@include('UsersView.footer')
</body>


<script>

document.getElementById('togglePassword').addEventListener('click', function ()
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

// document.getElementById('forgotPasswordBtn').addEventListener('click', function ()
// {
//     const form = document.querySelector('form');

//     // Remove required attributes
//     document.getElementById('systemPass').removeAttribute('required');
//     document.getElementById('password').removeAttribute('required');

//     // Add hidden input to indicate action
//     const hiddenInput = document.createElement('input');
//     hiddenInput.type = 'hidden';
//     hiddenInput.name = 'action';
//     hiddenInput.value = 'forgot_password';
//     form.appendChild(hiddenInput);

//     form.submit();
// });

</script>

</html>
