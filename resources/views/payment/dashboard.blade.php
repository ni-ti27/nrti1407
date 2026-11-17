{{-- ============================================================
     Payment Verification  (DBIM / GIGW 3.0, keyboard + screen reader friendly)
     resources/views/payment/dashboard.blade.php
     Uses layouts/app.blade.php. Server-rendered (no jQuery, no DataTables):
     the database returns only one page of rows at a time.
     Pagination: resources/views/pagination.blade.php
     Look: same as Applicant Login (banner + grey band + white card), CSS sections 50 and 60.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Payment Verification | RTI Online, Government of Goa')

@section('content')
<div class="dbim-sd dbim-pv">

    {{-- ===== Page banner ===== --}}
    <div class="dbim-pb">
        <div class="dbim-pb-wrap">
            <ol class="dbim-pb-crumb">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li aria-current="page">Payment Verification</li>
            </ol>
            <h1 class="dbim-pb-title">Payment Verification</h1>
        </div>
    </div>

    <div class="dbim-sd-section">
        <div class="dbim-sd-wrap">
            <div class="dbim-sd-card">

                {{-- Error summary (server side validation) --}}
                @if($errors->any())
                    <div id="errorSummary" class="alert alert-danger dbim-pv-alert" role="alert" tabindex="-1">
                        <p class="fw-bold mb-2">Please correct the following:</p>
                        <ul class="mb-0">
                            @foreach($errors->all() as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- ===== Filters ===== --}}
               <form method="GET" action="{{ route('dashboard') }}" class="dbim-pv-form" aria-label="Filter payment records">
                <div class="dbim-pv-filters">

                    <div class="dbim-pv-field">
                        <label for="paymentMode" class="dbim-sd-label">Payment Mode</label>
                        <select class="form-select @error('paymentMode') is-invalid @enderror" id="paymentMode" name="paymentMode"
                                aria-describedby="paymentModeError">
                            <option value="" {{ (request('paymentMode') == '') ? 'selected' : '' }}>All</option>
                            <option value="Success" {{ (request('paymentMode') == 'Success') ? 'selected' : '' }}>Success</option>
                            <option value="Failed" {{ (request('paymentMode') == 'Failed') ? 'selected' : '' }}>Failed</option>
                            <option value="verfication_pending" {{ (request('paymentMode') == 'verfication_pending') ? 'selected' : '' }}>Payment verification pending</option>
                        </select>
                        <div id="paymentModeError" class="invalid-feedback @error('paymentMode') d-block @enderror">@error('paymentMode'){{ $message }}@enderror</div>
                    </div>

                    <div class="dbim-pv-field">
                        <label for="start_date" class="dbim-sd-label">From Date</label>
                        <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date"
                            value="{{ request('start_date') }}" aria-describedby="start_dateError">
                        <div id="start_dateError" class="invalid-feedback @error('start_date') d-block @enderror">@error('start_date'){{ $message }}@enderror</div>
                    </div>

                    <div class="dbim-pv-field">
                        <label for="end_date" class="dbim-sd-label">To Date</label>
                        <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date"
                            value="{{ request('end_date') }}" aria-describedby="end_dateError">
                        <div id="end_dateError" class="invalid-feedback @error('end_date') d-block @enderror">@error('end_date'){{ $message }}@enderror</div>
                    </div>

                    <div class="dbim-pv-field">
                        <label for="per_page" class="dbim-sd-label">Rows per page</label>
                        <select class="form-select" id="per_page" name="per_page">
                            @foreach([10, 25, 50, 100] as $size)
                                <option value="{{ $size }}" {{ ((int) request('per_page', 10) === $size) ? 'selected' : '' }}>{{ $size }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="dbim-pv-field dbim-pv-field--wide">
                        <label for="q" class="dbim-sd-label">Search</label>
                        <input type="search" class="form-control @error('q') is-invalid @enderror" id="q" name="q"
                            value="{{ request('q') }}" placeholder="Name / Registration No / eChallan Number"
                            autocomplete="off" aria-describedby="qError">
                        <div id="qError" class="invalid-feedback @error('q') d-block @enderror">@error('q'){{ $message }}@enderror</div>
                    </div>

                    <div class="dbim-pv-actions">
                        <a href="{{ route('dashboard') }}" class="dbim-pv-clear">Clear</a>
                        <button type="submit" class="dbim-sd-btn" title="Search">Search</button>
                    </div>

                </div>
            </form>
                <hr class="dbim-sd-divider">

                {{-- ===== Results ===== --}}
                <section aria-labelledby="resultsHeading">
                    <h2 id="resultsHeading" class="dbim-sd-results-heading">Payment records</h2>

                    @if($payments->count() == 0)
                        <p class="dbim-pv-empty" role="status">No Data Present</p>
                    @else
                        <p class="dbim-pv-count">
                            Showing {{ $payments->firstItem() }} to {{ $payments->lastItem() }} of {{ $payments->total() }} records
                        </p>

                        <div class="dbim-sd-scroll" role="region" tabindex="0" aria-labelledby="resultsHeading">
                            <table class="dbim-sd-table dbim-pv-table">
                                <caption class="visually-hidden">Payment verification records</caption>
                                <thead>
                                    <tr>
                                        <th scope="col">Sr.</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Registration No</th>
                                        <th scope="col">eChallan Number</th>
                                        <th scope="col" class="dbim-pv-num">Amount (Rs.)</th>
                                        <th scope="col">Transaction Date</th>
                                        <th scope="col">Payment Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $row)
                                        @php
                                            $badgeClass = is_null($row->status) || $row->status == 'Failed'
                                                ? 'dbim-pv-badge--error'
                                                : ($row->status == 'Success' ? 'dbim-pv-badge--success' : 'dbim-pv-badge--pending');
                                        @endphp
                                        <tr>
                                            <td>{{ $payments->firstItem() + $loop->index }}</td>
                                            <td>{{ $row->name }}</td>
                                            <td>{{ $row->reg_no }}</td>
                                            <td>{{ $row->echallan_no }}</td>
                                            <td class="dbim-pv-num">{{ $row->total_amt }}</td>
                                            <td>{{ $row->trans_dt ? date('d-m-Y', strtotime($row->trans_dt)) : 'NA' }}</td>
                                            <td><span class="dbim-pv-badge {{ $badgeClass }}">{{ $row->status ?? 'Verify' }}</span></td>
                                            <td>
                                                @if($row->latest_status == 'Success')
                                                    <a href="{{ route('payment-verify', $row->id) }}" class="dbim-pv-btn dbim-pv-btn--outline">
                                                        Payment Success<span class="visually-hidden"> for registration number {{ $row->reg_no }}, eChallan {{ $row->echallan_no }}</span>
                                                    </a>
                                                @elseif($row->latest_status == 'Failed')
                                                    <a href="{{ route('payment-verify', $row->id) }}" class="dbim-pv-btn dbim-pv-btn--outline">
                                                        Payment Failed<span class="visually-hidden"> for registration number {{ $row->reg_no }}, eChallan {{ $row->echallan_no }}</span>
                                                    </a>
                                                @else
                                                    <a href="{{ route('payment-verify', $row->id) }}" class="dbim-pv-btn">
                                                        Payment Verify<span class="visually-hidden"> for registration number {{ $row->reg_no }}, eChallan {{ $row->echallan_no }}</span>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="dbim-pv-pager">
                            {{ $payments->onEachSide(1)->links('Pagination', ['label' => 'Payment records pages']) }}
                        </div>
                    @endif
                </section>

            </div>
        </div>
    </div>
</div>
@endsection

@if($errors->any())
    @push('scripts')
    <script>
        /* Validation errors from the server: move focus to the summary */
        document.getElementById('errorSummary').focus();
    </script>
    @endpush
@endif