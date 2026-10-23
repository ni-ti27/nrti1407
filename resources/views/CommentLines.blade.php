{{-- ============================================================
     Officer: View Comments / Reply (DBIM / GIGW 3.0)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     Pagination: resources/views/pagination.blade.php
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'View Comments / Reply | RTI Online, Government of Goa')

@section('content')
<div class="container px-3 px-lg-5 py-4">

    <h1 class="h2 mb-4 dbim-text-key">View Comments / Reply</h1>

    {{-- ===== Reusable Department / User Header Card ===== --}}
    @include('OfficerView.officer-header-card')

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
                                <td class="text-center">{{ $serialNumber++ }}</td>
                                <td>
                                    <a href="{{ route('detailedComments', ['id' => $request->id]) }}">
                                        {{ $role == 4 ? $request->fa_reg_no : $request->reg_no }}
                                    </a>
                                </td>
                                <td>{{ $request->name }}</td>
                                <td>{{ $request->created_at->format('d-m-Y H:i') }}</td>
                                <td class="text-number">{{ \Carbon\Carbon::parse($request->created_at)->diffInDays(now()) }}</td>
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