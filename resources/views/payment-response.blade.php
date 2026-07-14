<!DOCTYPE html>
<html>
<head>
    <title>Payment Gateway Response</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Meta viewport for responsiveness -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        /* Custom CSS for enhanced styling */
        body {
            background-color: #f8f9fa;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container mt-5">

           {{-- if response is success --}}
           status = success;
           {{-- else if response is failed --}}
           status = failure;
           {{-- else if response is pending --}}
           status = pending;

        <h1 class="text-center mb-4">RTI Fee Payment : $status</h1>


        {{--
            psuedo code

            if -=> response of the payment call is success,
            => MSG => PAYMENT sUCCESSFULL
                    <h1 class="text-center mb-4">MSG</h1>

            else => MSG => PAYMENT FAILED
                    <h1 class="text-center mb-4">MSG</h1>

        @endif --}}

        {{-- in case of Success Transaction --}}
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card border-success">
                        <h2 class="card-title text-success">Transaction Details</h2>
                        <p><strong>Transaction ID:</strong> 323432434 </p>
                        <p><strong>Receipt Number:</strong> 3432 </p>
                        <p><strong>eChallan Number:</strong> 2024001254 </p>
                        <p><strong>Amount:</strong> 34 </p>
                        <p><strong>Bank Reference Number:</strong> 324234234324 </p>
                        <p><strong>Bank Received:</strong> 2342342343243243 </p>
                    </div>
                </div>
            </div>
            <br><br>
            <div class="text-center">
                if($status == success)
                {
                    <h6>Your RTI Request filled successfully : reg_no : 049233/RQ/202401/000432</h6>
                }
                else
                {

                }

            </div>


        {{-- in case of Failed Transaction --}}

            {{-- no such data has to be presented --}}
            <br><br>
            <div class="text-center">

            if($status  == failure // pending)
            {
                <h6>Payment failure reason</h6>

            }
            <br>
            <a href="http://127.0.0.1:8000">Home Page</a><br>
            <a href="/submitreqform">File New Request</a>
        </div>

            {{-- // 10 seconds delay and then redirect to dashboard --}}
        <!-- Button to trigger modal -->
    </div>
