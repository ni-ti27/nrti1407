<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notices</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

</head>

<style>

.tableHead
{
    background-color: #92bddc;
}

.container.mt-5
{
    /* border: 2px solid black; */
    /* border-bottom: none; */
    border-top:none;
    margin-top:0 !important;
}
</style>

@include('OfficerView.Header')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <br>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-start">
                            <h5 class="mb-0">Username: {{ session('username') }}</h5>
                        </div>
                        <div class="text-end">
                            <h5 class="mb-0">Role: {{ session('role_name') }} </h5>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
        <hr>
        <div class="text-center">
            <h5 class="mb-0">View Notices</h5>
            <br>
        </div>
        <br>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="tableHead">
                    <tr>
                        <th>Sr. No</th>
                        <th>Registration Number</th>
                        <th>Applicant Name</th>
                        <th>e-Filing Date</th>
                        <th>Notice</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $secondAppealNumber = $requestLines->firstItem();
                    @endphp
                    @foreach($requestLines as $index => $request)
                    <tr>
                        <td>{{ $secondAppealNumber++ }}</td>
                        <td>{{ $request->sa_reg_no }}</td>
                        <td>{{ $request->name }}</td>
                        <td>{{ $request->efiling_date }}</td>
                        <td><a href="{{ $request->file_path }}" target="_blank"><strong>Download</strong></a></td>
                        <td>{{ $request->status }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $requestLines->links() }}
    </div>
</div>
@include('UsersView.footer')