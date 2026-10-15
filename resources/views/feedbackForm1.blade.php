<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact Us</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
</head>

<style>

    .container.mt-5
    {
        /* border: 2px solid black; */
        border-top:none;
        margin-top:0 !important;
    }

</style>
<body>
    @include('UsersView.header')

    <div class="container mt-5" style="border-bottom:0px">
        <br>
        <h2>Feedback Form</h2>
        <br>
       <!-- Display Success Message -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Display Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Feedback Form -->
        <form action="{{ route('feedback.submit') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Name<span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label for="mobile" class="form-label">Mobile Number<span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">+91</span>
                    <input type="text" name="mobile" id="mobile" class="form-control"
                        value="{{ old('mobile') }}" required
                        pattern="\d{10}" maxlength="10" title="Enter a 10-digit mobile number">
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email<span class="text-danger">*</span></label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            <div class="mb-3">
                <label for="message" class="form-label">Your Feedback<span class="text-danger">*</span></label>
                <textarea name="message" id="message" class="form-control" rows="5" required>{{ old('message') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Send Feedback</button>
        </form>
            <br>
        </div>

    @include('UsersView.footer')
</body>
</html>
