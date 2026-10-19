<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Application Stats</title>
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
    .list-group-item.active
    {
        background-color: #52a9e8 !important;
        border: none;
    }
    .border-card{
        border-color: #92bddc !important;
    }
</style>

<body>
    @include('OfficerView.Header')
<div class="container mt-4">
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

    <div class="row">
        <div class="col-md-3 mb-2">
            <div class="card shadow-sm sidebar-sticky">
                    <div class="card-header tableHead text-dark">
                        <h6 class="mb-0"><i class="bi bi-list-ul me-2"></i>Select Report Category</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach($actions as $action)
                            <a href="{{ route('getActions', ['action_name' => $action->name]) }}"
                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center
                            {{ $selectedActionName == $action->name ? 'active' : '' }}
                            {{-- Apply yellow background and dark text for important actions --}}
                            {{ $action->is_important ? 'background-color: #d1ecf1; border-color: #bee5eb;' : '' }}"
                            style="{{ $action->is_important ? 'background-color: #d1ecf1; border-color: #bee5eb;' : '' }}"
                            >

                                <div>
                                    {{-- Icon remains dark to stand out against the yellow --}}
                                    <i class="bi bi-file-earmark-text me-2"></i>
                                    {{ $action->name }}
                                </div>

                                @if(isset($action->request_count))
                                    {{-- Darker badge for the important rows to keep contrast high --}}
                                    <span class="badge rounded-pill {{ $action->is_important ? 'bg-dark text-white' : 'bg-secondary' }}">
                                        {{ $action->request_count }}
                                    </span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
        </div>


        <div class="col-md-9">
            @if($selectedActionName)
                <div class="card border-card mb-4 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <h5 class="text-primary mb-0">
                           <div class="d-flex flex-column">
                                <div><strong>{{ $selectedActionName }}</strong></div>
                            </div>
                            </h5>
                        </div>

                        <form action="{{ route('getActions') }}" method="GET" class="row g-3">
                            {{-- HIDDEN: Keep the selected action name in the request --}}
                            <input type="hidden" name="action_name" value="{{ $selectedActionName }}">

                            <div class="col-md-3">
                                <label class="form-label small fw-bold">From Date</label>
                                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold">To Date</label>
                                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" required>
                            </div>

                            @if($deptId === '430000')
                                <div class="col-md-2 d-flex align-items-center">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" name="all_dept" id="all_dept" value="1" {{ request('all_dept') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label small fw-bold" for="all_dept">
                                            All Departments
                                        </label>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100 fw-bold">
                                    <i class="bi bi-search me-1"></i> SHOW DATA
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                @if($requestLines)
                    <div class="card shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 text-muted">Showing results for {{ request('start_date') }} to {{ request('end_date') }}</h6>
                            <span class="badge bg-info text-dark">Total Records: {{ $requestLines->total() }}</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0">
                                    <thead class="tableHead">
                                        <tr>
                                            <th>Sr. No</th>
                                            {{-- Only show FA column if the current report type is FA --}}
                                            @if($actionType === 'fa_request')
                                                <th>FA Reg. No</th>
                                            @elseif($actionType === 'sa_request')
                                                <th>SA Reg. No</th>
                                            @endif
                                            <th>Reg. No</th>
                                            <th>Name</th>
                                            <th>Last Action Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($requestLines as $index => $row)
                                            <tr>
                                                <td>{{ $requestLines->firstItem() + $index }}</td>
                                                 {{-- Data for FA column --}}
                                                @if($actionType === 'fa_request')
                                                    <td>{{ $row->fa_reg_no }}</td>
                                                @elseif($actionType === 'sa_request')
                                                    <td>{{ $row->sa_reg_no }}</td>
                                                @endif
                                                <td>{{ $row->reg_no }}</td>
                                                <td>{{ $row->applicant_name }}</td>
                                                <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d-m-Y H:i') }}</td>
                                                <td>{{ $row->status }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ ($actionType === 'request') ? 6 : 7 }}" class="text-center py-5 text-muted">
                                                    No records found.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-center">
                        {{ $requestLines->links() }}
                    </div>
                @else
                    <div class="alert alert-info border-info shadow-sm">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Please select <strong>Start</strong> and <strong>End</strong> dates above and click "Show Data" to generate the list.
                    </div>
                @endif
            @else
                <div class="card border-card shadow-sm  bg-white py-5">
                    <div class="card-body text-center">
                        <i class="bi bi-folder2-open display-1 text-light-emphasis"></i>
                        <h3 class="mt-3 text-secondary">RTI Action Reports</h3>
                        <p class="text-muted">Select a category from the left menu to start generating your report.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
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
