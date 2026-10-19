{{-- ============================================================
     Officer Second Appeal Dashboard (DBIM / GIGW 3.0, keyboard + screen reader friendly)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     Pagination: resources/views/pagination.blade.php
     No inline styles, no inline event handlers.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Dashboard | RTI Online, Government of Goa')

@section('content')
@php
    if ($req_type == '{in_process_SA}') {
        $pageTitle = 'In Process Second Appeal Requests';
    } elseif ($req_type == '{closed_SA}') {
        $pageTitle = 'Closed Second Appeal Requests';
    } else {
        $pageTitle = 'New Second Appeal Requests';
    }

    $isClosed = $req_type == '{closed_SA}';
    $isAdmin  = session('role') == 1;
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

        @if($secondAppealData->count() == 0)
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
                            @if ($isClosed)
                                <th scope="col">Action (in Days)</th>
                            @else
                                <th scope="col">Pending Since (in Days)</th>
                            @endif
                            @if($isAdmin)
                                <th scope="col">Assigned to (username)</th>
                            @endif
                            @if ($isClosed)
                                <th scope="col">Last Action Date</th>
                            @endif
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $serialNumber = $secondAppealData->firstItem();
                        @endphp
                        @foreach($secondAppealData as $request)
                            @php
                                $createdAt  = \Carbon\Carbon::parse($request->created_at);
                                $daysPassed = $isClosed
                                    ? $createdAt->diffInDays($request->updated_at)
                                    : $createdAt->diffInDays(now());
                            @endphp
                            <tr>
                                <td>{{ $serialNumber++ }}</td>
                                <td>
                                    <a href="{{ route('detailedSaRequest', ['id' => $request->id]) }}">{{ $request->sa_reg_no }}</a>
                                </td>
                                <td>{{ $request->name }}</td>
                                <td>{{ $request->created_at->format('d-m-Y H:i') }}</td>
                                <td>{{ $daysPassed }}</td>
                                @if($isAdmin)
                                    <td>{{ $request->username ?? '' }}</td>
                                @endif
                                @if ($isClosed)
                                    <td>{{ $request->updated_at->format('d-m-Y H:i') }}</td>
                                @endif
                                <td>{{ $request->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $secondAppealData->onEachSide(1)
                                ->links('Pagination', ['label' => $pageTitle . ' pages']) }}
        @endif
    </section>

</div>
@endsection