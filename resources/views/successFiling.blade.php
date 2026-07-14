<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Filed-Success </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

</head>
<style>
    .img-fluid {
    max-width: 100%;
    height: auto;
}

.container.mt-5
        {
            /* border: 2px solid black; */
            /* border-top: none; */
            border-bottom: none;
            margin-top: 0 !important;
        }

</style>
<body>
    @include('UsersView.header')
    <div>
        <!-- Nothing in life is to be feared, it is only to be understood. Now is the time to understand more, so that we may fear less. - Marie Curie -->

        <div class="container mt-5" >
            <div class="row justify-content-center">
                <div class="col-md-8 col-12">
                    <div class="card" style="margin: 20px">
                            <div class="container-sm bg-green" style="margin: 1%">
                                <h2><span> Success </span> </h2>
                                <span style="color: green">RTI Request Filed Successfully</span>
                            </div>
                            {{-- <p>{{ $submissionId }}</p>
                            <p>{{ $departmentInfo }}</p> --}}
                        <span style="margin-left: 2%">Please note down the details of Registration</span>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="color: rgb(63, 138, 209)">Registration Number</th>
                                    <td><b> {{ $submissionId->reg_no }}</b></td>
                                </tr>
                                <tr>
                                    <th style="color: rgb(63, 138, 209)">Name</th>
                                    <td><b> {{ $submissionId->name }} </b></td>
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
                                    <td><b>{{ $submissionId->mobile_no }}</b></td>
                                </tr>
                                <tr>
                                    <th style="color: rgb(63, 138, 209)">Email Id</th>
                                    <td><b>{{ $submissionId->email_id }}</b></td>
                                </tr>
                            </table>
                            <div class="col-md-9" style="padding-bottom: 2%">
                                <a class="btn  btn-primary" id='treeviewtitle' href="{{url()->previous() }}" style="margin: auto;
                                color: white;
                                background-color: #125892;
                                border: 16%;">File New Request</a>
                                &nbsp;&nbsp;
                                <a class="btn  btn-primary" style="margin: auto; color: white; background-color: #125892; border: 16%;" href="/">Home</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('UsersView.footer')
</body>
