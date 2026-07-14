<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Treasury Payment </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet"></head>
<style>
    .img-fluid {
    max-width: 100%;
    height: auto;
}

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
    <form name="json_form" method="post" action="{{ route('payment') }}">
    @csrf
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8 col-12">
                    <div class="card" style="margin: 20px">
                        <?php

                        if ($status_code == 'F') {
                            $st = "Failure"; ?>
                            <div class="container-sm bg-red" style="margin: 1%">
                                <h2><span>Payment Failure</span></h2>
                            </div>
                        <?php } elseif ($status_code == 'S') {
                            $st = "Success"; ?>
                            <div class="container-sm bg-green" style="margin: 1%">
                                <h2><span> Payment Success</span> </h2>
                                @if($additionalPaymentFlag == false)
                                    <span style="color: green">RTI Request Filed Successfully</span>
                                @endif
                            </div>
                        <?php } elseif ($status_code == 'P' || empty($status_code)) {
                            $st = "Pending"; ?>
                            <div class="container-sm  bg-orange" style="margin: 1%">
                                <h2> <span>Payment Pending</span></h2>
                            </div>
                        <?php } ?>

                        <!-- {{$paidDetails}} -->
                        @if($additionalPaymentFlag == true)
                            <span style="margin-left: 2%">Please note down the details</span>
                        @else
                            <span style="margin-left: 2%">Please note down the details of Registration</span>
                        @endif
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr class="justify-content-center">
                                    <th colspan="2" style="text-align: center"><span>Registration Details</span></th>
                                </tr>
                                <tr>
                                    <th style="color: rgb(63, 138, 209)">Registration Number</th>
                                    <td><b> {{ $newRegNumber }}</b></td>
                                </tr>
                                <tr>
                                    <th style="color: rgb(63, 138, 209)">Name</th>
                                    <td><b> {{ $query->name }} </b></td>
                                </tr>
                                <tr>
                                    <th style="color:  rgb(63, 138, 209)">Date & Time of Transaction</th>
                                    <td><b>{{ $payment_date ? $payment_date : '-' }}</b></td>
                                </tr>
                                <tr>
                                    <th style="color:  rgb(63, 138, 209)">Request Filed with</th>
                                    <td><b> {{ $departmentInfo->department }} </b></td>
                                </tr>

                                @if($paidDetails != null)
                                <tr class="justify-content-center">
                                    <th colspan="2" style="text-align: center"><span>Payment Details</span></th>
                                </tr>
                                <tr>
                                    <th style="color:  rgb(63, 138, 209)">Echallan Number</th>
                                    <td><b>{{ $cin ? $cin : '-' }}</b></td>
                                </tr>
                                <tr>
                                    <th style="color:  rgb(63, 138, 209)">Amount</th>
                                    <td><b>{{ $amount ? $amount : '0' }}</b></td>
                                </tr>
                                <tr>
                                    <th style="color:  rgb(63, 138, 209)">Transaction Type</th>
                                    @if($additionalPaymentFlag == true)
                                    <td><b>Additional Payment for Information</b></td>
                                    @else
                                    <td><b>New RTI Application Fee</b></td>
                                    @endif
                                </tr>
                                <tr>
                                    <th style="color:  rgb(63, 138, 209)">Transaction Date</th>
                                    <td><b>{{ $payment_date ? $payment_date : '-' }}</b></td>
                                </tr>
                                <tr>
                                    <th style="color:  rgb(63, 138, 209)">Status</th>
                                    <td><b>{{ $st }}</b></td>
                                </tr>
                                @endif
                                <tr class="justify-content-center">
                                    <th colspan="2" style="text-align: center"><span>Contact Details</span></th>
                                </tr>
                                <tr>
                                    <th style="color:rgb(63, 138, 209)">Mobile Number</th>
                                    <td><b>{{ $query->phone }}</b></td>
                                </tr>
                                <tr>
                                    <th style="color: rgb(63, 138, 209)">Email Id</th>
                                    <td><b>{{ $query->email }}</b></td>
                                </tr>
                            </table>

                            <div class="col-md-4" style="padding-bottom: 2%">
                                <?php
                                if ($status_code == 'S' || $status_code == 'P')
                                {
                                    ?>
                                    <a class="btn  btn-primary" id='treeviewtitle' href="{{url()->previous() }}" style="margin:auto; color: white; background-color: #125892; border: 16%;">Back to home</a>
                                    <?php

                                }
                                if ($status_code == 'F')
                                {
                                ?>
                                    <input name="bsubmit" id="bsubmit" type="hidden" value="Pay Again" >
                                    <input name="reg_no_details" id="reg_no_details" type="hidden" value="{{$query->reg_no}}" >

                                    <input name="addl_payment" id="addl_payment" type="hidden" value="{{$query->total_amt}}" >

                                    <input name="pid" id="pid" type="hidden" value="{{$query->transaction_id}}" >

                                    <input name="reg_no" id="reg_no" type="hidden" value="{{$query->reg_no}}" >
                                    <input name="challan_no" id="challan_no" type="hidden" value="{{$cin}}" >
                                    <button class="btn  btn-primary btn-flat pay-again" type="Submit" style="   margin: auto;
                                    color: white;
                                    background-color: #125892;
                                    border: 16%; ">Pay Again</button>

                                    <a class="btn  btn-primary" id='treeviewtitle' href="{{url()->previous() }}" style="margin: auto;
                                    color: white;
                                    background-color: #125892;
                                    border: 16%;">Back to home</a>

                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@include('UsersView.footer')
</body>

<script>
     $(document).ready(function() {
        $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
        // Handle project name click event
        $('.pay-again').click(function() {
            // ... (previous code)

            // alert('in');
            // Get the division name from the data attribute
            var reg_no = '{{$query->reg_no}}';
            var challan_no = '{{$cin}}';

            // Send an AJAX request to store the division name in the session
            $.ajax({
                type: 'POST',
                url: '{{ route("payment") }}',
                data: { reg_no: reg_no },
                success: function(response) {
                    console.log(response); // Handle success response
                },
                error: function(error) {
                    console.log(error); // Handle error
                }
            });
        });
    });
</script>