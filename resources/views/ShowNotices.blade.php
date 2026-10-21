{{-- ============================================================
     Officer: View Notices (DBIM / GIGW 3.0, keyboard + screen reader friendly)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     Pagination: resources/views/pagination.blade.php
     No inline styles, no inline event handlers.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'View Notices | RTI Online, Government of Goa')

@section('content')
<div class="container px-3 px-lg-5 py-4">

    <h1 class="h2 mb-4">View Notices</h1>

    {{-- ===== User details ===== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <p class="fs-6 fw-bold mb-0">Username: <span class="fw-normal">{{ session('username') }}</span></p>
                <p class="fs-6 fw-bold mb-0">Role: <span class="fw-normal">{{ session('role_name') }}</span></p>
            </div>
        </div>
    </div>

    {{-- ===== Flash messages ===== --}}
    @include('partials.flash-messages')

    {{-- ===== Notices table ===== --}}
    <section aria-labelledby="noticesHeading">
        <h2 id="noticesHeading" class="visually-hidden">Notices list</h2>

        @if($requestLines->count() == 0)
            <div class="alert alert-info" role="status">No Data Present</div>
        @else
            <div class="table-responsive" role="region" tabindex="0" aria-labelledby="noticesHeading">
                <table class="table table-striped table-bordered align-middle">
                    <caption class="visually-hidden">Notices</caption>
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Sr. No</th>
                            <th scope="col">Registration Number</th>
                            <th scope="col">Applicant Name</th>
                            <th scope="col">e-Filing Date</th>
                            <th scope="col">Notice</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $serialNumber = $requestLines->firstItem();
                        @endphp
                        @foreach($requestLines as $request)
                            <tr>
                                <td>{{ $serialNumber++ }}</td>
                                <td>{{ $request->sa_reg_no }}</td>
                                <td>{{ $request->name }}</td>
                                <td>{{ $request->efiling_date }}</td>
                                <td>
                                    @if($request->file_path)
                                        <a href="{{ $request->file_path }}" target="_blank" rel="noopener noreferrer">
                                            Download<span class="visually-hidden"> notice for registration number {{ $request->sa_reg_no }} (opens in a new window)</span>
                                        </a>
                                    @else
                                        NA
                                    @endif
                                </td>
                                <td>{{ $request->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $requestLines->onEachSide(1)
                            ->links('Pagination', ['label' => 'Notices pages']) }}
        @endif
    </section>

</div>
@endsection