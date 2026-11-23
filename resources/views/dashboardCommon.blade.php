{{-- ============================================================
     Officer Dashboard (DBIM / GIGW 3.0, keyboard + screen reader friendly)
     Layout B: Linen band, one white card.
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     Pagination: resources/views/pagination.blade.php
     Styles: section 50 (frame, table), 76 and 78 in public/css/dbim.css
     No inline styles, no inline event handlers.
     ONLY THE MARKUP AND CLASSES CHANGED: every variable, condition, route,
     column and the pagination call is the same as before.
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
<div class="dbim-sd">
  <div class="dbim-sd-section">
    <div class="dbim-sd-wrap">
      <div class="dbim-sd-card">

        <div class="dbim-nr-head">
            <h1 class="dbim-sd-heading">{{ $pageTitle }}</h1>
        </div>

        {{-- ===== Office / user details ===== --}}
        @include('OfficerView.officer-header-card')

        {{-- ===== Flash messages ===== --}}
        @include('partials.flash-messages')

        {{-- ===== Requests table ===== --}}
        <section aria-labelledby="requestsHeading">
            <h2 id="requestsHeading" class="visually-hidden">{{ $pageTitle }} list</h2>

            @php
                $colCount = 5;
                if ($req_type != '{transForward}') {
                    $colCount += 1;
                    if (session('role') == 1) { $colCount += 1; }
                }
                if ($req_type == '{transForward}' || $req_type == '{closed}') { $colCount += 1; }
            @endphp
                <div class="dbim-sd-scroll" role="region" tabindex="0" aria-labelledby="requestsHeading">
                    <table class="dbim-sd-table dbim-nr-table">
                        <caption class="visually-hidden">{{ $pageTitle }}</caption>
                        <thead>
                            <tr>
                                <th scope="col" class="dbim-nr-col-sr">Sr. No</th>
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
                            @if($submitRequests->count() == 0)
                                <tr>
                                    <td colspan="{{ $colCount }}" class="dbim-nr-none">No Data Present</td>
                                </tr>
                            @endif
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
                                    <td class="dbim-nr-num">{{ $request->created_at->format('d-m-Y H:i') }}</td>

                                    @if ($req_type != '{transForward}')
                                        @php
                                            $createdAt  = \Carbon\Carbon::parse($request->created_at);
                                            $daysPassed = $req_type == '{closed}'
                                                ? $createdAt->diffInDays($request->action_updated)
                                                : $createdAt->diffInDays(now());
                                        @endphp
                                        <td class="dbim-nr-num">{{ $daysPassed }}</td>
                                        @if(session('role') == 1)
                                            <td>{{ $request->username }}</td>
                                        @endif
                                    @endif

                                    @if ($req_type == '{transForward}')
                                        <td class="dbim-nr-num">{{ \Carbon\Carbon::parse($request->action_updated)->format('d-m-Y H:i') }}</td>
                                        <td><span class="dbim-nr-status">{{ $request->changes }}</span></td>
                                    @elseif ($req_type == '{closed}')
                                        <td class="dbim-nr-num">{{ \Carbon\Carbon::parse($request->action_updated)->format('d-m-Y H:i') }}</td>
                                        <td><span class="dbim-nr-status">{{ $request->status }}</span></td>
                                    @else
                                        <td><span class="dbim-nr-status">{{ $request->status }}</span></td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            @if($submitRequests->count() != 0)
                {{ $submitRequests->onEachSide(1)
                                  ->links('Pagination', ['label' => $pageTitle . ' pages']) }}
            @endif
        </section>

      </div>
    </div>
  </div>
</div>
@endsection