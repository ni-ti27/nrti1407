{{-- ============================================================
     Payment Verification  (DBIM / GIGW 3.0, keyboard + screen reader friendly)
     resources/views/payment/dashboard.blade.php
     Uses layouts/app.blade.php. Server-rendered (no jQuery, no DataTables):
     the database returns only one page of rows at a time.
     Pagination: resources/views/pagination.blade.php
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Payment Verification | RTI Online, Government of Goa')

@section('content')
<div class="container-fluid px-3 px-lg-5 py-4">

    <h1 class="h2 mb-4">Payment Verification</h1>

    {{-- ===== Filters ===== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            {{-- Error summary (server side validation) --}}
            @if($errors->any())
                <div id="errorSummary" class="alert alert-danger" role="alert" tabindex="-1">
                    <p class="fw-bold mb-2">Please correct the following:</p>
                    <ul class="mb-0">
                        @foreach($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="GET" action="{{ route('dashboard') }}" aria-label="Filter payment records">
                <div class="row g-3 align-items-end">

                    <div class="col-12 col-md-6 col-lg-3">
                        <label for="paymentMode" class="form-label fw-bold">Payment Mode</label>
                        <select class="form-select @error('paymentMode') is-invalid @enderror" id="paymentMode" name="paymentMode"
                                aria-describedby="paymentModeError">
                            <option value="" {{ (request('paymentMode') == '') ? 'selected' : '' }}>All</option>
                            <option value="Success" {{ (request('paymentMode') == 'Success') ? 'selected' : '' }}>Success</option>
                            <option value="Failed" {{ (request('paymentMode') == 'Failed') ? 'selected' : '' }}>Failed</option>
                            <option value="verfication_pending" {{ (request('paymentMode') == 'verfication_pending') ? 'selected' : '' }}>Payment verification pending</option>
                        </select>
                        <div id="paymentModeError" class="invalid-feedback @error('paymentMode') d-block @enderror">@error('paymentMode'){{ $message }}@enderror</div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-2">
                        <label for="start_date" class="form-label fw-bold">From Date</label>
                        <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date"
                               value="{{ request('start_date') }}" aria-describedby="start_dateError">
                        <div id="start_dateError" class="invalid-feedback @error('start_date') d-block @enderror">@error('start_date'){{ $message }}@enderror</div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-2">
                        <label for="end_date" class="form-label fw-bold">To Date</label>
                        <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date"
                               value="{{ request('end_date') }}" aria-describedby="end_dateError">
                        <div id="end_dateError" class="invalid-feedback @error('end_date') d-block @enderror">@error('end_date'){{ $message }}@enderror</div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3">
                        <label for="q" class="form-label fw-bold">Search</label>
                        <input type="search" class="form-control @error('q') is-invalid @enderror" id="q" name="q"
                               value="{{ request('q') }}" placeholder="Name / Registration No / eChallan Number"
                               autocomplete="off" aria-describedby="qError">
                        <div id="qError" class="invalid-feedback @error('q') d-block @enderror">@error('q'){{ $message }}@enderror</div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-2">
                        <label for="per_page" class="form-label fw-bold">Rows per page</label>
                        <select class="form-select" id="per_page" name="per_page">
                            @foreach([10, 25, 50, 100] as $size)
                                <option value="{{ $size }}" {{ ((int) request('per_page', 10) === $size) ? 'selected' : '' }}>{{ $size }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="{{ route('dashboard') }}" class="download-outline-btn text-uppercase w-ft mt-0">Clear</a>
                    <button type="submit" class="download-btn text-uppercase w-ft mt-0" title="Search">Search</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== Results ===== --}}
    <section aria-labelledby="resultsHeading">
        <h2 id="resultsHeading" class="h5">Payment records</h2>

        @if($payments->count() == 0)
            <div class="alert alert-info" role="status">No Data Present</div>
        @else
            <p class="mb-2">
                Showing {{ $payments->firstItem() }} to {{ $payments->lastItem() }} of {{ $payments->total() }} records
            </p>

            <div class="table-responsive" role="region" tabindex="0" aria-labelledby="resultsHeading">
                <table class="table table-striped table-bordered align-middle">
                    <caption class="visually-hidden">Payment verification records</caption>
                    <thead>
                        <tr>
                            <th scope="col">Sr.</th>
                            <th scope="col">Name</th>
                            <th scope="col">Registration No</th>
                            <th scope="col">eChallan Number</th>
                            <th scope="col">Amount (Rs.)</th>
                            <th scope="col">Transaction Date</th>
                            <th scope="col">Payment Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $row)
                            @php
                                $statusClass = is_null($row->status) || $row->status == 'Failed'
                                    ? 'dbim-status-error fw-bold'
                                    : ($row->status == 'Success' ? 'dbim-status-success fw-bold' : '');
                            @endphp
                            <tr>
                                <td>{{ $payments->firstItem() + $loop->index }}</td>
                                <td>{{ $row->name }}</td>
                                <td>{{ $row->reg_no }}</td>
                                <td>{{ $row->echallan_no }}</td>
                                <td class="text-end">{{ $row->total_amt }}</td>
                                <td>{{ $row->trans_dt ? date('d-m-Y', strtotime($row->trans_dt)) : 'NA' }}</td>
                                <td><span class="{{ $statusClass }}">{{ $row->status ?? 'Verify' }}</span></td>
                                <td>
                                    @if($row->latest_status == 'Success')
                                        <a href="{{ route('payment-verify', $row->id) }}" class="download-outline-btn text-uppercase w-ft mt-0">
                                            Payment Success<span class="visually-hidden"> for registration number {{ $row->reg_no }}, eChallan {{ $row->echallan_no }}</span>
                                        </a>
                                    @elseif($row->latest_status == 'Failed')
                                        <a href="{{ route('payment-verify', $row->id) }}" class="download-outline-btn text-uppercase w-ft mt-0">
                                            Payment Failed<span class="visually-hidden"> for registration number {{ $row->reg_no }}, eChallan {{ $row->echallan_no }}</span>
                                        </a>
                                    @else
                                        <a href="{{ route('payment-verify', $row->id) }}" class="download-btn text-uppercase w-ft mt-0">
                                            Payment Verify<span class="visually-hidden"> for registration number {{ $row->reg_no }}, eChallan {{ $row->echallan_no }}</span>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $payments->onEachSide(1)->links('Pagination', ['label' => 'Payment records pages']) }}
        @endif
    </section>

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