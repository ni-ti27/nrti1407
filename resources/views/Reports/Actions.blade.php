{{-- ============================================================
     Officer: Application Stats / RTI Action Reports
     (DBIM / GIGW 3.0, keyboard + screen reader friendly)
     One card on the grey band (.dbim-sd frame).
     Pagination: resources/views/pagination.blade.php
     Styles: dbim.css sections 22, 50, 79, 84, 85, 86.
     No inline styles, no inline event handlers.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Application Stats | RTI Online, Government of Goa')

@section('content')
@php
    $showSubDept = !in_array(session('role_name'), ['Nodal Officer', 'First Appellete Authority']);
@endphp
<div class="dbim-sd">
  <div class="dbim-sd-section">
    <div class="dbim-sd-wrap">

      @include('partials.flash-messages')

      <div class="dbim-sd-card">

        <h1 class="dbim-sd-heading dbim-nr-head">Application Stats</h1>

        {{-- ===== Department / office / username / role strip ===== --}}
        <dl class="dbim-nr-strip">
            <div>
                <dt>Department:</dt>
                <dd>{{ session('faaDeptName') }}</dd>
            </div>
            @if($showSubDept)
                <div>
                    <dt>Office:</dt>
                    <dd>{{ session('subDepartmentName') }}</dd>
                </div>
                <div>
                    <dt>Sub Office:</dt>
                    <dd>{{ session('officeName') }}</dd>
                </div>
            @endif
            <div>
                <dt>Username:</dt>
                <dd>{{ session('username') }}</dd>
            </div>
            <div>
                <dt>Role:</dt>
                <dd>{{ session('role_name') }}</dd>
            </div>
        </dl>

        <div class="dbim-ar-grid">

            {{-- ===== Left: report categories ===== --}}
            <nav class="card dbim-report-card" aria-labelledby="categoryHeading">
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

            {{-- ===== Right: report area ===== --}}
            <div class="dbim-ar-main">

                @if($selectedActionName)

                    <section class="card dbim-report-card dbim-ar-panel" aria-labelledby="reportHeading">

                        <div class="dbim-ar-bar">
                            <h2 id="reportHeading" class="dbim-ar-title">{{ $selectedActionName }}</h2>
                            @if($requestLines)
                                <span class="dbim-count dbim-count--solid">Total Records: {{ $requestLines->total() }}</span>
                            @endif
                        </div>

                        {{-- Error summary (shown only when the server returns validation errors) --}}
                        @if($errors->any())
                            <div class="alert alert-danger" role="alert" tabindex="-1" id="errorSummary">
                                <p class="fw-bold mb-1">Please correct the following:</p>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Date filter --}}
                        <form action="{{ route('getActions') }}" method="GET" class="dbim-ar-filter"
                              role="search" aria-label="Filter {{ $selectedActionName }} by date">
                            {{-- keeps the selected action in the request --}}
                            <input type="hidden" name="action_name" value="{{ $selectedActionName }}">

                            <div class="dbim-ar-field">
                                <label for="start_date" class="form-label fw-bold">
                                    From Date <span class="dbim-required" aria-hidden="true">*</span>
                                    <span class="visually-hidden">(required)</span>
                                </label>
                                <input type="date" id="start_date" name="start_date"
                                       class="form-control @error('start_date') is-invalid @enderror"
                                       value="{{ request('start_date') }}" required aria-required="true"
                                       @error('start_date') aria-invalid="true" aria-describedby="start_date_error" @enderror>
                                @error('start_date')
                                    <div class="invalid-feedback d-block" id="start_date_error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="dbim-ar-field">
                                <label for="end_date" class="form-label fw-bold">
                                    To Date <span class="dbim-required" aria-hidden="true">*</span>
                                    <span class="visually-hidden">(required)</span>
                                </label>
                                <input type="date" id="end_date" name="end_date"
                                       class="form-control @error('end_date') is-invalid @enderror"
                                       value="{{ request('end_date') }}" min="{{ request('start_date') }}"
                                       required aria-required="true"
                                       @error('end_date') aria-invalid="true" aria-describedby="end_date_error" @enderror>
                                @error('end_date')
                                    <div class="invalid-feedback d-block" id="end_date_error">{{ $message }}</div>
                                @enderror
                            </div>

                            @if($deptId === '430000')
                                <div class="dbim-ar-check">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="all_dept" id="all_dept"
                                               value="1" {{ request('all_dept') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="all_dept">All Departments</label>
                                    </div>
                                </div>
                            @endif

                            <div class="dbim-ar-submit">
                                <button type="submit" class="download-btn dbim-filter-btn text-uppercase">Show Data</button>
                            </div>
                        </form>

                        {{-- Results --}}
                        @if($requestLines)
                            @php
                                $from = request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d-m-Y') : '';
                                $to   = request('end_date')   ? \Carbon\Carbon::parse(request('end_date'))->format('d-m-Y')   : '';
                            @endphp

                            <hr class="dbim-sd-divider">

                            <h3 id="resultsHeading" class="dbim-ar-results">Showing results for {{ $from }} to {{ $to }}</h3>

                            <div class="dbim-sd-scroll" role="region" tabindex="0" aria-labelledby="resultsHeading">
                                <table class="dbim-sd-table">
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
                                            $colCount = in_array($actionType, ['fa_request', 'sa_request']) ? 6 : 5;
                                        @endphp
                                        @forelse($requestLines as $row)
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
                                        @empty
                                            <tr>
                                                <td colspan="{{ $colCount }}" class="dbim-nr-none">No Data Present</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if($requestLines->count() > 0)
                                <div class="mt-4">
                                    {{ $requestLines->withQueryString()
                                                    ->onEachSide(1)
                                                    ->links('Pagination', ['label' => $selectedActionName . ' pages']) }}
                                </div>
                            @endif
                        @else
                            <p class="dbim-ar-note" role="status">
                                Please select a <strong>From Date</strong> and a <strong>To Date</strong> above and choose
                                "Show Data" to generate the list.
                            </p>
                        @endif

                    </section>

                @else
                    <div class="card dbim-report-card">
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

            </div>{{-- .dbim-ar-main --}}
        </div>{{-- .dbim-ar-grid --}}

      </div>{{-- .dbim-sd-card --}}

    </div>{{-- .dbim-sd-wrap --}}
  </div>{{-- .dbim-sd-section --}}
</div>{{-- .dbim-sd --}}
@endsection

@push('scripts')
<script>
    /* To Date can never be before From Date */
    (function () {
        var from = document.getElementById('start_date');
        var to   = document.getElementById('end_date');
        var summary = document.getElementById('errorSummary');
        if (from && to) {
            from.addEventListener('change', function () { to.min = from.value; });
        }
        if (summary) { summary.focus(); }
    })();
</script>
@endpush