<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
    <!-- Add additional CSS or stylesheets here -->
    <style>

        .tableHead{
            background-color: #92bddc;
        }

        .container.mt-5
        {
            /* border: 2px solid black; */
            border-top:none;
            margin-top:0 !important;
        }

    </style>
</head>
<body>
@include('OfficerView.Header')

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <br>
                    <div class="text-center">
                    <h4 class="mb-0"> {{session('faaDeptName')}} </h4>
                        @if(session('role_name' != 'Nodal Officer') || session('role_name' != 'First Appellete Authority'))
                        <h4 class="mb-0"> {{session('subDepartmentName')}} </h4>
                        <h4 class="mb-0"> {{session('officeName')}} </h4>
                        @endif
                        <br>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-start">
                                <h5 class="mb-0">Username: {{ session('username') }}</h5>
                            </div>
                            <div class="text-end">
                                <h5 class="mb-0">Role: {{ session('role_name') }} </h5>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
            <hr>
            <div class="text-center">
                @if ($req_type == '{new_FA}' || $req_type == '')
                    <h5 class="mb-0">New First Appeal Request</h5>
                @elseif ($req_type == '{in_process_FA}')
                    <h5 class="mb-0">In Process First Appeal Request</h5>
                @elseif ($req_type == '{closed_FA}')
                    <h5 class="mb-0">Closed First Appeal Request</h5>
                @endif
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
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
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="tableHead">
                <tr>
                    <th>Sr. No</th>
                    <th>Registration Number</th>
                    <th>Name</th>
                    <th>e-Filing Date</th>
                    @if ($req_type == '{closed_FA}')
                    <th>Action (in Days)</th>
                    @else
                    <th>Pending Since (in Days)</th>
                    @endif
                    @if(session('role') == 1)
                        <th>Assigned to (username)</th>
                    @endif
                    @if ($req_type == '{closed_FA}')
                    <th>Last Action Date</th>
                    @endif
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php

                $firstAppealNumber = $firstAppealData->firstItem(); // Get the first serial number on the page
                @endphp
                @foreach($firstAppealData as $index => $request)
                <tr>
                    <td>{{ $firstAppealNumber++ }}</td>
                    <td>
                        <a href="{{ route('detailedFARequest', ['id' => $request->id]) }}" class="btn btn-link">{{ $request->fa_reg_no }}</a>
                    </td>
                    <td>{{ $request->name }}</td>
                    <td>{{ $request->created_at->format('d-m-Y H:i') }}</td>

                    @php

                    if($req_type == '{closed_FA}')
                    {
                        $createdAt = \Carbon\Carbon::parse($request->created_at);
                        $daysPassed = $createdAt->diffInDays($request->updated_at);
                    }
                    else
                    {
                        $createdAt = \Carbon\Carbon::parse($request->created_at);
                        $daysPassed = $createdAt->diffInDays(now());
                    }
                    @endphp
                    <td>{{ $daysPassed }}</td>
                    @if(session('role') == 1)
                        <td>{{$request->username}}</td>
                    @endif
                    @if ($req_type == '{closed_FA}')
                    <td>{{$request->updated_at->format('d-m-Y H:i')}}</td>
                    @endif
                    <td>{{ $request->status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $firstAppealData->links() }}
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
