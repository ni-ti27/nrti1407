{{-- ============================================================
     Application Status  (DBIM / GIGW 3.0, keyboard + screen reader friendly)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     Pagination: resources/views/pagination.blade.php
     No inline styles, no inline event handlers.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Application Status | RTI Online, Government of Goa')

@section('content')
@php
    $active = $divSelector ?? 'display1';
@endphp
<div class="container px-3 px-lg-5 py-4">

    <h1 class="h2 mb-4">Application Status</h1>

    {{-- ===== Choose which applications to view + search ===== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <fieldset class="mb-3">
                <legend class="fs-6 fw-bold">Applications to view</legend>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="divSelector" id="view_display1"
                           value="display1" aria-controls="display1" {{ $active == 'display1' ? 'checked' : '' }}>
                    <label class="form-check-label" for="view_display1">View New Request Applications</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="divSelector" id="view_display2"
                           value="display2" aria-controls="display2" {{ $active == 'display2' ? 'checked' : '' }}>
                    <label class="form-check-label" for="view_display2">View First Appeal Applications</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="divSelector" id="view_display3"
                           value="display3" aria-controls="display3" {{ $active == 'display3' ? 'checked' : '' }}>
                    <label class="form-check-label" for="view_display3">View Second Appeal Applications</label>
                </div>
            </fieldset>

            <form method="GET" action="{{ route('applicant.applicationStatus') }}" role="search"
                  aria-label="Search applications">
                <label for="search" class="form-label fw-bold">Search</label>
                <div class="input-group">
                    <input type="search" class="form-control" id="search" name="search"
                           value="{{ request('search') }}"
                           placeholder="Registration Number / Applicant Name / Department Name"
                           autocomplete="off">
                    {{-- keeps the selected list when searching --}}
                    <input type="hidden" id="hiddenDivSelector" name="divSelector" value="{{ $active }}">
                    <button type="submit" class="download-btn text-uppercase mt-0" title="Search">Search</button>
                </div>
            </form>

        </div>
    </div>

    {{-- Announces which list is showing when the radio button changes --}}
    <p id="statusLive" class="visually-hidden" role="status" aria-live="polite"></p>

    {{-- ========================================================= --}}
    {{-- NEW REQUEST APPLICATIONS                                   --}}
    {{-- ========================================================= --}}
    <section id="display1" aria-labelledby="display1Heading" data-status-panel @if($active != 'display1') hidden @endif>
        <h2 id="display1Heading" class="h5">View New Request Applications</h2>

        @if($nrStatus->count() == 0)
            <div class="alert alert-info" role="status">No Data Present</div>
        @else
            <div class="table-responsive" role="region" tabindex="0" aria-labelledby="display1Heading">
                <table class="table table-striped table-bordered align-middle">
                    <caption class="visually-hidden">New request applications</caption>
                    <thead>
                        <tr>
                            <th scope="col">Sr No.</th>
                            <th scope="col">Registration Number</th>
                            <th scope="col">e-Filing Date</th>
                            <th scope="col">Applicant Name</th>
                            <th scope="col">Department Name</th>
                            <th scope="col">Current Action</th>
                            <th scope="col">Action Date</th>
                            <th scope="col">Reply Text</th>
                            <th scope="col">Download Reply</th>
                            <th scope="col">Additional Payment (Rs)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $serialNumber = $nrStatus->firstItem();
                        @endphp
                        @foreach($nrStatus as $st)
                            <tr>
                                <td>{{ $serialNumber++ }}</td>
                                <td><a href="{{ route('commonData', ['id' => $st->id]) }}">{{ $st->reg_no }}</a></td>
                                <td>{{ \Carbon\Carbon::parse($st->date_of_efiling)->format('d-m-Y H:i') }}</td>
                                <td>{{ $st->applicant_name }}</td>
                                <td>{{ $st->department }}</td>
                                <td>{{ $st->action_ }}</td>
                                <td>{{ \Carbon\Carbon::parse($st->app_history_created)->format('d-m-Y H:i') }}</td>
                                <td>{{ $st->reply_text ?? 'NA' }}</td>
                                <td>
                                    @if($st->download_reply)
                                        <a href="{{ Storage::url($st->download_reply) }}" target="_blank" rel="noopener noreferrer">
                                            Download<span class="visually-hidden"> reply for registration number {{ $st->reg_no }} (opens in a new window)</span>
                                        </a>
                                    @else
                                        NA
                                    @endif
                                </td>
                                <td>
                                    @if($st->action_ == 'ADDITIONAL PAYMENT PAID')
                                        <strong>Amount Paid (Rs.): {{ $st->addl_payment }}</strong>
                                    @elseif($st->status_id == 9 && $st->action_ == 'ADDITIONAL PAYMENT REQUIRED FOR INFORMATION')
                                        <form method="post" action="{{ route('payment') }}">
                                            @csrf
                                            <input type="hidden" name="reg_no_details" value="{{ $st }}">
                                            <button type="submit" class="download-btn text-uppercase w-ft mt-0">
                                                Pay Rs. {{ $st->addl_payment }}<span class="visually-hidden"> for registration number {{ $st->reg_no }}</span>
                                            </button>
                                        </form>
                                    @else
                                        <span aria-hidden="true">--</span><span class="visually-hidden">Not applicable</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $nrStatus->appends(['search' => request('search'), 'divSelector' => 'display1'])
                        ->onEachSide(1)
                        ->links('Pagination', ['label' => 'New request applications pages']) }}
        @endif
    </section>

    {{-- ========================================================= --}}
    {{-- FIRST APPEAL APPLICATIONS                                  --}}
    {{-- ========================================================= --}}
    <section id="display2" aria-labelledby="display2Heading" data-status-panel @if($active != 'display2') hidden @endif>
        <h2 id="display2Heading" class="h5">View First Appeal Applications</h2>

        @if($faStatus->count() == 0)
            <div class="alert alert-info" role="status">No Data Present</div>
        @else
            <div class="table-responsive" role="region" tabindex="0" aria-labelledby="display2Heading">
                <table class="table table-striped table-bordered align-middle">
                    <caption class="visually-hidden">First appeal applications</caption>
                    <thead>
                        <tr>
                            <th scope="col">Sr No.</th>
                            <th scope="col">Registration Number</th>
                            <th scope="col">e-Filing Date</th>
                            <th scope="col">Applicant Name</th>
                            <th scope="col">Department Name</th>
                            <th scope="col">Current Action</th>
                            <th scope="col">Action Date</th>
                            <th scope="col">Reply Text</th>
                            <th scope="col">Download Reply</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $serialNumber = $faStatus->firstItem();
                        @endphp
                        @foreach($faStatus as $st)
                            <tr>
                                <td>{{ $serialNumber++ }}</td>
                                <td><a href="{{ route('FAdetailedRequest', ['id' => $st->id]) }}">{{ $st->reg_no }}</a></td>
                                <td>{{ \Carbon\Carbon::parse($st->efiling_date)->format('d-m-Y H:i') }}</td>
                                <td>{{ $st->applicant_name }}</td>
                                <td>{{ $st->department }}</td>
                                <td>{{ $st->changes }}</td>
                                <td>{{ $st->app_history_created ? \Carbon\Carbon::parse($st->app_history_created)->format('d-m-Y H:i') : 'NA' }}</td>
                                <td>{{ $st->reply_text ?? 'NA' }}</td>
                                <td>
                                    @if($st->reply_doc)
                                        <a href="{{ Storage::url($st->reply_doc) }}" target="_blank" rel="noopener noreferrer">
                                            Download<span class="visually-hidden"> reply for registration number {{ $st->reg_no }} (opens in a new window)</span>
                                        </a>
                                    @else
                                        NA
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $faStatus->appends(['search' => request('search'), 'divSelector' => 'display2'])
                        ->onEachSide(1)
                        ->links('Pagination', ['label' => 'First appeal applications pages']) }}
        @endif
    </section>

    {{-- ========================================================= --}}
    {{-- SECOND APPEAL APPLICATIONS                                 --}}
    {{-- ========================================================= --}}
    <section id="display3" aria-labelledby="display3Heading" data-status-panel @if($active != 'display3') hidden @endif>
        <h2 id="display3Heading" class="h5">View Second Appeal Applications</h2>

        @if($saStatus->count() == 0)
            <div class="alert alert-info" role="status">No Data Present</div>
        @else
            <div class="table-responsive" role="region" tabindex="0" aria-labelledby="display3Heading">
                <table class="table table-striped table-bordered align-middle">
                    <caption class="visually-hidden">Second appeal applications</caption>
                    <thead>
                        <tr>
                            <th scope="col">Sr No.</th>
                            <th scope="col">Registration Number</th>
                            <th scope="col">e-Filing Date</th>
                            <th scope="col">Applicant Name</th>
                            <th scope="col">Department Name</th>
                            <th scope="col">Current Action</th>
                            <th scope="col">Action Date</th>
                            <th scope="col">Reply Text</th>
                            <th scope="col">Download Reply</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $serialNumber = $saStatus->firstItem();
                        @endphp
                        @foreach($saStatus as $st)
                            <tr>
                                <td>{{ $serialNumber++ }}</td>
                                <td><a href="{{ route('SAdetailedRequest', ['id' => $st->id]) }}">{{ $st->reg_no }}</a></td>
                                <td>{{ \Carbon\Carbon::parse($st->efiling_date)->format('d-m-Y H:i') }}</td>
                                <td>{{ $st->applicant_name }}</td>
                                <td>{{ $st->department }}</td>
                                <td>{{ $st->status }}</td>
                                <td>{{ $st->reply_date ? \Carbon\Carbon::parse($st->reply_date)->format('d-m-Y H:i') : 'NA' }}</td>
                                <td>{{ $st->reply_text ?? 'NA' }}</td>
                                <td>
                                    @if($st->reply_doc)
                                        <a href="{{ Storage::url($st->reply_doc) }}" target="_blank" rel="noopener noreferrer">
                                            Download<span class="visually-hidden"> reply for registration number {{ $st->reg_no }} (opens in a new window)</span>
                                        </a>
                                    @else
                                        NA
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $saStatus->appends(['search' => request('search'), 'divSelector' => 'display3'])
                        ->onEachSide(1)
                        ->links('Pagination', ['label' => 'Second appeal applications pages']) }}
        @endif
    </section>

</div>
@endsection

@push('scripts')
<script>
    (function () {
        var radios = document.querySelectorAll('input[name="divSelector"]');
        var panels = document.querySelectorAll('[data-status-panel]');
        var hidden = document.getElementById('hiddenDivSelector');
        var live   = document.getElementById('statusLive');

        function showPanel(id, announce) {
            panels.forEach(function (p) { p.hidden = (p.id !== id); });
            hidden.value = id;

            /* keep the address bar in step, without reloading */
            var url = new URL(window.location.href);
            url.searchParams.set('divSelector', id);
            window.history.replaceState({}, '', url.toString());

            if (announce) {
                var label = document.querySelector('label[for="view_' + id + '"]');
                live.textContent = '';
                setTimeout(function () { live.textContent = 'Showing ' + (label ? label.textContent.trim() : id); }, 50);
            }
        }

        radios.forEach(function (radio) {
            radio.addEventListener('change', function () {
                if (radio.checked) { showPanel(radio.value, true); }
            });
        });
    })();
</script>
@endpush