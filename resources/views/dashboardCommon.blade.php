{{-- ============================================================
     Officer Dashboard (DBIM / GIGW 3.0, keyboard + screen reader friendly)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     Pagination: resources/views/pagination.blade.php
     No inline styles, no inline event handlers.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Dashboard | RTI Online, Government of Goa')

@section('content')
@php
    if ($req_type == '{in_process}') {
        $pageTitle = 'In Process Requests';
    } elseif ($req_type == '{closed}') {
        $pageTitle = 'Closed Requests';
    } elseif ($req_type == '{transForward}') {
        $pageTitle = 'Transferred / Forwarded Requests';
    } else {
        $pageTitle = 'New Requests';
    }
@endphp
<div class="container-fluid px-3 px-lg-5 py-4">

    <h1 class="h2 mb-4">{{ $pageTitle }}</h1>

    {{-- ===== Office / user details ===== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2 class="h5 text-center mb-3">{{ session('officeName') }}</h2>
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <p class="fs-6 fw-bold mb-0">Username: <span class="fw-normal">{{ $userDetails['name'] }}</span></p>
                <p class="fs-6 fw-bold mb-0">Role: <span class="fw-normal">{{ $userDetails['role_name'] }}</span></p>
            </div>
        </div>
    </div>

    {{-- ===== Flash messages ===== --}}
    @include('partials.flash-messages')

    {{-- ===== Requests table ===== --}}
    <section aria-labelledby="requestsHeading">
        <h2 id="requestsHeading" class="visually-hidden">{{ $pageTitle }} list</h2>

        @if($submitRequests->count() == 0)
            <div class="alert alert-info" role="status">No Data Present</div>
        @else
            <div class="table-responsive" role="region" tabindex="0" aria-labelledby="requestsHeading">
                <table class="table table-striped table-bordered align-middle">
                    <caption class="visually-hidden">{{ $pageTitle }}</caption>
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Sr. No</th>
                            <th scope="col">Registration Number</th>
                            <th scope="col">Name</th>
                            <th scope="col">e-Filing Date</th>
                            @if ($req_type != '{transForward}')
                                @if ($req_type == '{closed}')
                                    <th scope="col">Action (in Days)</th>
                                @else
                                    <th scope="col">Pending Since (in Days)</th>
                                @endif
                                @if(session('role') == 1)
                                    <th scope="col">Assigned to (username)</th>
                                @endif
                            @endif
                            @if ($req_type == '{transForward}' || $req_type == '{closed}')
                                <th scope="col">Last Action Date</th>
                            @endif
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $serialNumber = $submitRequests->firstItem();
                        @endphp
                        @foreach($submitRequests as $request)
                            <tr>
                                <td>{{ $serialNumber++ }}</td>
                                <td>
                                    @if($req_type == '{closed}')
                                        <a href="{{ route('closedRequest', ['id' => $request->id]) }}">{{ $request->reg_no }}</a>
                                    @else
                                        <a href="{{ route('detailedFormRoute', ['id' => $request->id]) }}">{{ $request->reg_no }}</a>
                                    @endif
                                </td>
                                <td>{{ $request->name }}</td>
                                <td>{{ $request->created_at->format('d-m-Y H:i') }}</td>

                                @if ($req_type != '{transForward}')
                                    @php
                                        $createdAt  = \Carbon\Carbon::parse($request->created_at);
                                        $daysPassed = $req_type == '{closed}'
                                            ? $createdAt->diffInDays($request->action_updated)
                                            : $createdAt->diffInDays(now());
                                    @endphp
                                    <td>{{ $daysPassed }}</td>
                                    @if(session('role') == 1)
                                        <td>{{ $request->username }}</td>
                                    @endif
                                @endif

                                @if ($req_type == '{transForward}')
                                    <td>{{ \Carbon\Carbon::parse($request->action_updated)->format('d-m-Y H:i') }}</td>
                                    <td>{{ $request->changes }}</td>
                                @elseif ($req_type == '{closed}')
                                    <td>{{ \Carbon\Carbon::parse($request->action_updated)->format('d-m-Y H:i') }}</td>
                                    <td>{{ $request->status }}</td>
                                @else
                                    <td>{{ $request->status }}</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $submitRequests->onEachSide(1)
                              ->links('Pagination', ['label' => $pageTitle . ' pages']) }}
        @endif
    </section>

</div>
@endsection