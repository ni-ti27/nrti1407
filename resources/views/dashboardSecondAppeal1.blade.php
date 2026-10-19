
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<style>
.container.mt-5
    {
        /* border: 2px solid black; */
        /* border-bottom: none; */
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
                <div class="text-center">
                    @if ($req_type == '{new_SA}' || $req_type == '')
                        <h5 class="mb-0">New Requests</h5>
                    @elseif ($req_type == '{in_process_SA}')
                        <h5 class="mb-0">In Process Requests</h5>
                    @elseif ($req_type == '{closed_SA}')
                        <h5 class="mb-0">Closed Requests</h5>
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
                            @if ($req_type == '{closed}')
                                <th>Action (in Days)</th>
                            @else
                                <th>Pending Since (in Days)</th>
                            @endif
                            @if(session('role') == 1)
                                <th>Assigned to (username)</th>
                            @endif
                        @if($req_type == '{closed_SA}')
                        <th>Last Action Date</th>
                        @endif
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $serialNumber = $secondAppealData->firstItem(); // Get the first serial number on the page
                    @endphp
                    @foreach($secondAppealData as $index => $request)
                    <tr>
                        <td>{{ $serialNumber++ }}</td>
                        <td>
                            @if($req_type == '{closed}')
                            <a href="{{ route('closedRequest', ['id' => $request->id]) }}" class="btn btn-link">{{ $request->sa_reg_no }}</a>
                            @else
                            <a href="{{ route('detailedSaRequest', ['id' => $request->id]) }}" class="btn btn-link">{{ $request->sa_reg_no }}</a>
                            @endif
                        </td>
                        @php
                            if($req_type == '{closed_SA}')
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

                        <td>{{ $request->name }}</td>
                        <td>{{ $request->created_at->format('d-m-Y H:i') }}</td>
                        <td>{{ $daysPassed }}</td>
                        @if($req_type == '{closed_SA}')
                        <td>{{ $request->updated_at->format('d-m-Y H:i') }}</td>
                        @endif
                        <td>{{ $request->status }}</td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $secondAppealData->links() }}
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