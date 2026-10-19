{{-- ============================================================
     Officer: Application list (DBIM / GIGW 3.0, keyboard + screen reader friendly)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     Pagination: resources/views/pagination.blade.php
     No inline styles, no inline event handlers.
     ============================================================ --}}
@extends('layouts.app')

@section('title', $category . ' | RTI Online, Government of Goa')

@section('content')
<div class="container-fluid px-3 px-lg-5 py-4">

    <h1 class="h2 mb-4">{{ $category }}</h1>

    {{-- ===== Office / user details ===== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2 class="h5 text-center mb-3">{{ session('officeName') }}</h2>
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <p class="fs-6 fw-bold mb-0">Username: <span class="fw-normal">{{ session('username') }}</span></p>
                <p class="fs-6 fw-bold mb-0">Role: <span class="fw-normal">{{ session('role_name') }}</span></p>
            </div>
        </div>
    </div>

    {{-- ===== Search ===== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{  request()->url() }}" role="search"
                  aria-label="Search applications">
                <label for="search" class="form-label fw-bold">Search</label>
                <div class="input-group">
                    <input type="search" class="form-control" id="search" name="search"
                           value="{{ request('search') }}"
                           placeholder="Registration Number / Applicant Name"
                           autocomplete="off">
                    <button type="submit" class="download-btn text-uppercase mt-0" title="Search">Search</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== Flash messages ===== --}}
    @include('partials.flash-messages')

    {{-- ===== Applications table ===== --}}
    <section aria-labelledby="statusHeading">
        <h2 id="statusHeading" class="visually-hidden">{{ $category }} list</h2>

        @if($status->count() == 0)
            <div class="alert alert-info" role="status">No Data Present</div>
        @else
            <div class="table-responsive" role="region" tabindex="0" aria-labelledby="statusHeading">
                <table class="table table-striped table-bordered align-middle">
                    <caption class="visually-hidden">{{ $category }}</caption>
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Sr No.</th>
                            <th scope="col">Registration Number</th>
                            <th scope="col">e-Filing Date</th>
                            <th scope="col">Applicant Name</th>
                            <th scope="col">Public Authority</th>
                            <th scope="col">Updated Date</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $serialNumber = $status->firstItem();
                        @endphp
                        @foreach($status as $st)
                            <tr>
                                <td>{{ $serialNumber++ }}</td>
                                <td>{{ $st->reg_no }}</td>
                                <td>{{ \Carbon\Carbon::parse($st->date_of_efiling)->format('d-m-Y H:i') }}</td>
                                <td>{{ $st->applicant_name }}</td>
                                <td>{{ $st->department }}</td>
                                <td>{{ \Carbon\Carbon::parse($st->app_updated)->format('d-m-Y H:i') }}</td>
                                <td>{{ $st->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $status->withQueryString()
                      ->onEachSide(1)
                      ->links('Pagination', ['label' => $category . ' pages']) }}
        @endif
    </section>

</div>
@endsection