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
            <div class="col-md-12">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <br>
                        <div class="text-center">
                            <h4 class="mb-0"> {{session('officeName')}} </h4>
                            <br>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-start">
                                    <h5 class="mb-0">Username: {{session('username')}} </h5>
                                </div>
                                <div class="text-end">
                                    <h5 class="mb-0">Role: {{session('role_name')}}  </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <br>
                        <div class="text-center">
                            <h5 class="mb-0">{{$category}}</h5>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
        <!-- <h2 style="margin-left:10px">Application Status</h2> -->
        <br>
        <div class="row justify-content-center">
            <div class="table-responsive table">
                <div class="row justify-content-center mb-3">
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('fetchDetailAppData.deptFullData') }}">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Registration Number / Applicant Name">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <table class="table table-striped table-bordered">
                    <thead class="tableHead">
                        <tr>
                            <th>Sr No.</th>
                            <th>Registration Number</th>
                            <th>Applicant Name</th>
                            <th>Public Authority</th>
                            <th>Action Date</th>
                            <th>Action Completed</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($status as $st)
                        <tr>
                            <td>{{ ($currentPage - 1) * $perPage + $loop->iteration }}</td>
                            <td>{{ $st->reg_no }}</td>
                            <td>{{ $st->applicant_name }}</td>
                            <td>{{ $st->department }}</td>
                            <td>{{ \Carbon\Carbon::parse($st->app_history_created)->format('d-m-Y H:i') }}</td>
                            <td>{{ $st->action_ }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $status->links() }}
                <br>
            </div>
        </div>
    </div>
    @include('UsersView.footer')

</body>