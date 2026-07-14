<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>First Appeal Status</title>
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
    <!-- <div class="container mt-5" style="border: 2px solid black;"> -->
    <form name="json_form" method="post" action="{{ route('payment') }}">
    @csrf
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8 col-12">
                    <div class="card" style="margin: 20px">

                        <div class="container-sm bg-green" style="margin: 1%">
                            <h2><span>Filed Successfully</span> </h2>
                            <span style="color: green">First Appeal Request Filed Successfully</span>
                        </div>

                        <span style="margin-left: 2%">Please note down the details of Registration</span>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="color: rgb(63, 138, 209)">Registration Number</th>
                                    <td><b>{{ $submittedDetails->fa_reg_no }} </b></td>
                                </tr>
                                <tr>
                                    <th style="color: rgb(63, 138, 209)">Name</th>
                                    <td><b> {{ $submittedDetails->name }} </b></td>
                                </tr>
                                <tr>
                                    <th style="color:  rgb(63, 138, 209)">Date & Time of e-Filing</th>
                                    <td><b>{{ $submittedDetails->created_at }}</b></td>
                                </tr>
                                <tr>
                                    <th style="color:  rgb(63, 138, 209)">Request Filed with</th>
                                    <td><b> {{ $departmentInfo->department }} </b></td>
                                </tr>

                                <tr class="justify-content-center">
                                    <th colspan="2" style="text-align: center"><span>Contact Details</span></th>
                                </tr>
                                <tr>
                                    <th style="color:rgb(63, 138, 209)">Mobile Number</th>
                                    <td><b>{{ $submittedDetails->mobile_no }}</b></td>
                                </tr>
                                <tr>
                                    <th style="color: rgb(63, 138, 209)">Email Id</th>
                                    <td><b>{{ $submittedDetails->email_id }}</b></td>
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@include('UsersView.footer')
</body>
