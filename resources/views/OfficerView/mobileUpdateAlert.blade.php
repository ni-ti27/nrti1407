<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Mobile Number</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .alert-card {
            max-width: 450px;
            margin: 40px auto; /* 40px top/bottom, centered horizontally */
            background: #ffffff;

            border-top: 5px solid #52a9e8;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            padding: 30px;
            text-align: center;
        }

        .icon-circle {
            width: 65px;
            height: 65px;
            /* Soft yellow background */
            background: #fff9e6;
            /* Yellow danger color */
            color: #ffc107;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

@include('OfficerView.Header')

<div class="container">
    <div class="alert-card">
    <div class="icon-circle">
            <i class="fas fa-exclamation-triangle"></i>
        </div>

        <h3 class="fw-bold h4">Action Required</h3>

        <p class="text-muted mb-4 mt-2">
            Please update the registered mobile number to receive login OTP.
        </p>

        <a href="{{ route('showOfficerDetailForm') }}" class="btn btn-primary px-4 shadow-sm">
           Ok
        </a>
    </div>
</div>

@include('UsersView.footer')

</body>
</html>