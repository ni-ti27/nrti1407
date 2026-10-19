{{-- ============================================================
     Officer: Application Stats / RTI Action Reports
     (DBIM / GIGW 3.0, keyboard + screen reader friendly)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     Pagination: resources/views/pagination.blade.php
     Colours and styles: dbim.css section 22 (Blue colour group only).
     No inline styles, no inline event handlers.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Application Stats | RTI Online, Government of Goa')

@section('content')
@php
    $showSubDept = !in_array(session('role_name'), ['Nodal Officer', 'First Appellete Authority']);
@endphp
<div class="container-fluid px-3 px-lg-5 py-4">

    <h1 class="h2 mb-4">Application Stats</h1>

    {{-- ===== Department / user details ===== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="text-center mb-3">
                <h2 class="h5 mb-1">{{ session('faaDeptName') }}</h2>
                @if($showSubDept)
                    <p class="fs-6 fw-bold mb-1">{{ session('subDepartmentName') }}</p>
                    <p class="fs-6 fw-bold mb-0">{{ session('officeName') }}</p>
                @endif
            </div>
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <p class="fs-6 fw-bold mb-0">Username: <span class="fw-normal">{{ session('username') }}</span></p>
                <p class="fs-6 fw-bold mb-0">Role: <span class="fw-normal">{{ session('role_name') }}</span></p>
            </div>
        </div>
    </div>

    {{-- ===== Flash messages ===== --}}
    @include('partials.flash-messages')

    <div class="row g-4">

             {{-- ===== Report categories ===== --}}
        <div class="col-12 col-lg-3">
            <nav class="card shadow-sm dbim-report-card" aria-labelledby="categoryHeading">
               <div class="card-header dbim-category-head">
                    <h2 id="categoryHeading" class="h6 mb-0">Select Report Category</h2>
                </div>
                <ul class="dbim-category-list">
                    @foreach($actions as $action)
                        @php $isActive = $selectedActionName == $action->name; @endphp
                        <li>
                            <a href="{{ route('getActions', ['action_name' => $action->name]) }}"
                               class="dbim-category-item {{ $isActive ? 'is-active' : '' }} {{ $action->is_important ? 'is-important' : '' }}"
                               @if($isActive) aria-current="page" @endif>
                                <span>
                                    {{ $action->name }}
                                    @if($action->is_important)
                                        <span class="visually-hidden">(important)</span>
                                    @endif
                                </span>
                                @if(isset($action->request_count))
                                    <span class="dbim-count">
                                        <span class="visually-hidden">Count: </span>{{ $action->request_count }}
                                    </span>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>

                @if(collect($actions)->contains(fn ($a) => $a->is_important))
                    <p class="dbim-category-legend">
                        <span class="dbim-category-legend__swatch" aria-hidden="true"></span>
                        Highlighted categories are important.
                    </p>
                @endif
            </nav>
        </div>

        {{-- ===== Report area ===== --}}
        <div class="col-12 col-lg-9">

            @if($selectedActionName)

                {{-- Date filter --}}
                <div class="card shadow-sm dbim-report-card mb-4">
                    <div class="card-body">
                        <h2 class="h5 mb-3">{{ $selectedActionName }}</h2>

                        <form action="{{ route('getActions') }}" method="GET" class="row g-3"
                              aria-label="Filter {{ $selectedActionName }} by date">
                            {{-- keeps the selected action in the request --}}
                            <input type="hidden" name="action_name" value="{{ $selectedActionName }}">

                            <div class="col-12 col-md-3">
                                <label for="start_date" class="form-label fw-bold">
                                    From Date <span class="dbim-required" aria-hidden="true">*</span>
                                </label>
                                <input type="date" id="start_date" name="start_date" class="form-control"
                                       value="{{ request('start_date') }}" required aria-required="true">
                            </div>
                            <div class="col-12 col-md-3">
                                <label for="end_date" class="form-label fw-bold">
                                    To Date <span class="dbim-required" aria-hidden="true">*</span>
                                </label>
                                <input type="date" id="end_date" name="end_date" class="form-control"
                                       value="{{ request('end_date') }}" required aria-required="true">
                            </div>

                            @if($deptId === '430000')
                                <div class="col-12 col-md-3 d-flex align-items-end">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="all_dept" id="all_dept"
                                               value="1" {{ request('all_dept') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="all_dept">All Departments</label>
                                    </div>
                                </div>
                            @endif

                            <div class="col-12 col-md d-flex align-items-end">
                                <button type="submit" class="download-btn dbim-filter-btn text-uppercase">Show Data</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Results --}}
                @if($requestLines)
                    @php
                        $from = request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d-m-Y') : '';
                        $to   = request('end_date')   ? \Carbon\Carbon::parse(request('end_date'))->format('d-m-Y')   : '';
                    @endphp

                    <section class="card shadow-sm mb-3" aria-labelledby="resultsHeading">
                        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <h2 id="resultsHeading" class="h6 mb-0">Showing results for {{ $from }} to {{ $to }}</h2>
                            <span class="dbim-count dbim-count--solid">Total Records: {{ $requestLines->total() }}</span>
                        </div>

                        <div class="card-body p-0">
                            @if($requestLines->count() == 0)
                                <div class="alert alert-info m-3" role="status">No records found.</div>
                            @else
                                <div class="table-responsive dbim-report-table" role="region" tabindex="0"
                                     aria-labelledby="resultsHeading">
                                    <table class="table table-striped align-middle mb-0">
                                        <caption class="visually-hidden">{{ $selectedActionName }}, {{ $from }} to {{ $to }}</caption>
                                        <thead>
                                            <tr>
                                                <th scope="col">Sr. No</th>
                                                @if($actionType === 'fa_request')
                                                    <th scope="col">FA Reg. No</th>
                                                @elseif($actionType === 'sa_request')
                                                    <th scope="col">SA Reg. No</th>
                                                @endif
                                                <th scope="col">Reg. No</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Last Action Date</th>
                                                <th scope="col">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $serialNumber = $requestLines->firstItem();
                                            @endphp
                                            @foreach($requestLines as $row)
                                                <tr>
                                                    <td>{{ $serialNumber++ }}</td>
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
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </section>

                    {{ $requestLines->withQueryString()
                                    ->onEachSide(1)
                                    ->links('Pagination', ['label' => $selectedActionName . ' pages']) }}
                @else
                    <div class="alert alert-info" role="status">
                        Please select a <strong>From Date</strong> and a <strong>To Date</strong> above and choose
                        "Show Data" to generate the list.
                    </div>
                @endif

            @else
                <div class="card shadow-sm dbim-report-card">
                    <div class="card-body text-center py-5">
                        <span class="dbim-report-icon mb-3" aria-hidden="true">
                            <svg viewBox="0 0 24 24" focusable="false">
                                <path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/>
                            </svg>
                        </span>
                        <h2 class="h4">RTI Action Reports</h2>
                        <p class="mb-0">Select a category from the list to start generating your report.</p>
                    </div>
                </div>
            @endif

        </div>
    </div>

</div>
@endsection