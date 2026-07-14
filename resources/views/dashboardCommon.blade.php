<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
    <!-- Add additional CSS or stylesheets here -->
</head>

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

<body>
    @include('OfficerView.Header')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12 justify-content-center">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <br>
                        <div class="text-center">
                        <h4 class="mb-0"> {{session('officeName')}} </h4>
                        <br>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-start">
                                <h5 class="mb-0">Username: {{ $userDetails['name'] }}</h5>
                            </div>
                            <div class="text-end">
                                <h5 class="mb-0">Role: {{ $userDetails['role_name'] }} </h5>
                            </div>
                        </div>
                        </div>
                        <br>
                    </div>
                </div>
                <hr>
                <div class="text-center"> <!-- Added class text-center here -->
                    @if ($req_type == '{new_req}' || $req_type == '')
                        <h5 class="mb-0">New Requests</h5>
                    @elseif ($req_type == '{in_process}')
                        <h5 class="mb-0">In Process Requests</h5>
                    @elseif ($req_type == '{closed}')
                        <h5 class="mb-0">Closed Requests</h5>
                    @elseif ($req_type == '{transForward}')
                        <h5 class="mb-0">Transfered / Forwarded Requests</h5>
                    @endif
                </div>
                <br>
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

        <div class="table-responsive table">
            <table class="table table-striped table-bordered">
                <thead class="tableHead">
                    <tr>
                        <th>Sr. No</th>
                        <th>Registration Number</th>
                        <th>Name</th>
                        <th>e-Filing Date</th>
                        @if ($req_type != '{transForward}')
                            @if ($req_type == '{closed}')
                                <th>Action (in Days)</th>
                            @else
                                <th>Pending Since (in Days)</th>
                            @endif
                            @if(session('role') == 1)
                                <th>Assigned to (username)</th>
                            @endif
                        @endif

                        @if ($req_type == '{transForward}' || $req_type == '{closed}' )
                            <th>Last Action Date</th>
                        @endif
                            <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $serialNumber = $submitRequests->firstItem(); // Get the first serial number on the page
                    @endphp
                    @foreach($submitRequests as $index => $request)
                    <tr>
                        <td>{{ $serialNumber++ }}</td>
                        <td>
                            @if($req_type == '{closed}')
                            <a href="{{ route('closedRequest', ['id' => $request->id]) }}" class="btn btn-link">{{ $request->reg_no }}</a>
                            @else
                            <a href="{{ route('detailedFormRoute', ['id' => $request->id]) }}" class="btn btn-link">{{ $request->reg_no }}</a>
                            @endif
                        </td>
                        <td>{{ $request->name }}</td>
                        <td>{{ $request->created_at->format('d-m-Y H:i') }}</td>

                        @if ($req_type != '{transForward}')

                            @php
                                if($req_type == '{closed}')
                                {
                                    $createdAt = \Carbon\Carbon::parse($request->created_at);
                                    $daysPassed = $createdAt->diffInDays($request->action_updated);
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

                        @endif

                        @if ($req_type == '{transForward}')
                            <td>{{ \Carbon\Carbon::parse($request->action_updated)->format('d-m-Y H:i') }}</td>
                            <td>{{ $request->changes }}</td>
                        @elseif($req_type == '{closed}')
                            <td>{{ \Carbon\Carbon::parse($request->action_updated)->format('d-m-Y H:i') }}</td>
                            <td>{{ $request->status }}</td>
                        @else
                            <td>{{ $request->status }}</td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $submitRequests->links() }}
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
    </html>
