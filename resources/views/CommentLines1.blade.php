<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Comments / Reply</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
</head>

<style>
    .tableHead {
        background-color: #92bddc;
    }

    .container.mt-5 {
        border-top: none;
        margin-top: 0 !important;
    }
</style>

<body>
    @include('OfficerView.Header')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <br>
                        <div class="text-center">
                            <h4 class="mb-0"> {{ session('faaDeptName') }} </h4>
                            @if(session('role_name') != 'Nodal Officer' && session('role_name') != 'First Appellete Authority')
                                <h4 class="mb-0"> {{ session('subDepartmentName') }} </h4>
                                <h4 class="mb-0"> {{ session('officeName') }} </h4>
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
                            <br>
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <div class="text-center">
                <h5 class="mb-0">View Comments / Reply</h5>
            </div>
            <br>
        </div>
        <br>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="tableHead">
                    <tr>
                        <th>Sr. No</th>
                        <th>Registration Number</th>
                        <th>Name</th>
                        <th>e-Filing Date</th>
                        <th>Pending Since (in Days)</th>
                        @if(session('role') == 1)
                            <th>Assigned to (username)</th>
                        @endif
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $firstAppealNumber = $requestLines->firstItem();
                    @endphp
                    @foreach($requestLines as $index => $request)
                        <tr>
                            <td>{{ $firstAppealNumber++ }}</td>
                            <td>
                                @if($role == 4)
                                    <a href="{{ route('detailedComments', ['id' => $request->id]) }}" class="btn btn-link">{{ $request->fa_reg_no }}</a>
                                @else
                                    <a href="{{ route('detailedComments', ['id' => $request->id]) }}" class="btn btn-link">{{ $request->reg_no }}</a>
                                @endif
                            </td>

                            <td>{{ $request->name }}</td>
                            <td>{{ $request->created_at->format('d-m-Y H:i') }}</td>

                            @php
                                $createdAt = \Carbon\Carbon::parse($request->created_at);
                                $daysPassed = $createdAt->diffInDays(now());
                            @endphp
                            <td>{{ $daysPassed }}</td>

                            @if(session('role') == 1)
                                <td>{{ $request->username }}</td>
                            @endif

                            <td>{{ $request->status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $requestLines->links() }}
        <br>
    </div>

    @include('UsersView.footer')

    <script>
        setTimeout(() => {
            ['successAlert', 'warningAlert', 'errorAlert'].forEach(id => {
                const alertElement = document.getElementById(id);
                if (alertElement) alertElement.style.display = "none";
            });
        }, 10000);
    </script>
</body>

</html>
