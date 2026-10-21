{{-- ============================================================
     Officer: View Comments / Reply (DBIM / GIGW 3.0, keyboard + screen reader friendly)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     Pagination: resources/views/pagination.blade.php
     No inline styles, no inline event handlers.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'View Comments / Reply | RTI Online, Government of Goa')

@section('content')
<div class="container px-3 px-lg-5 py-4">

    <h1 class="h2 mb-4">View Comments / Reply</h1>

    {{-- ===== Department / user details ===== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="text-center mb-3">
                <h2 class="h5 mb-1">{{ session('faaDeptName') }}</h2>
                @if(session('role_name') != 'Nodal Officer' && session('role_name') != 'First Appellete Authority')
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

    {{-- ===== Comments table ===== --}}
    <section aria-labelledby="commentsHeading">
        <h2 id="commentsHeading" class="visually-hidden">Comments and replies list</h2>

        @if($requestLines->count() == 0)
            <div class="alert alert-info" role="status">No Data Present</div>
        @else
            <div class="table-responsive" role="region" tabindex="0" aria-labelledby="commentsHeading">
                <table class="table table-striped table-bordered align-middle">
                    <caption class="visually-hidden">View comments / reply</caption>
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Sr. No</th>
                            <th scope="col">Registration Number</th>
                            <th scope="col">Name</th>
                            <th scope="col">e-Filing Date</th>
                            <th scope="col">Pending Since (in Days)</th>
                            @if(session('role') == 1)
                                <th scope="col">Assigned to (username)</th>
                            @endif
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
                                <td>
                                    <a href="{{ route('detailedComments', ['id' => $request->id]) }}">
                                        {{ $role == 4 ? $request->fa_reg_no : $request->reg_no }}
                                    </a>
                                </td>
                                <td>{{ $request->name }}</td>
                                <td>{{ $request->created_at->format('d-m-Y H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($request->created_at)->diffInDays(now()) }}</td>
                                @if(session('role') == 1)
                                    <td>{{ $request->username }}</td>
                                @endif
                                <td>{{ $request->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $requestLines->onEachSide(1)
                            ->links('Pagination', ['label' => 'Comments and replies pages']) }}
        @endif
    </section>

</div>
@endsection