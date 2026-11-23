{{-- ============================================================
     Officer Second Appeal Dashboard (DBIM / GIGW 3.0, keyboard + screen reader friendly)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     Pagination: resources/views/pagination.blade.php
     No inline styles, no inline event handlers.
     Look: same as the Officer Application list (Layout B: grey band + one white card,
     title inside the card, light-blue table header, status pill).
     Styles: dbim.css sections 50, 76, 79, 80 and 82.
     ONLY THE MARKUP AND CLASSES CHANGED: every variable, condition, route, column
     and the pagination call is the same as before.
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

    // columns in the table, for the "No Data Present" row
    $colCount = 6 + ($isAdmin ? 1 : 0) + ($isClosed ? 1 : 0);
@endphp
<div class="dbim-sd dbim-nr-page">
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

            <div class="dbim-sd-scroll" role="region" tabindex="0" aria-labelledby="requestsHeading">
                <table class="dbim-sd-table dbim-nr-table">
                    <caption class="visually-hidden">{{ $pageTitle }}</caption>
                    <thead>
                        <tr>
                            <th scope="col" class="dbim-nr-col-sr">Sr. No</th>
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
                        @if($secondAppealData->count() == 0)
                            <tr>
                                <td colspan="{{ $colCount }}" class="dbim-nr-none">No Data Present</td>
                            </tr>
                        @endif
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
                                <td class="dbim-nr-num">{{ $request->created_at->format('d-m-Y H:i') }}</td>
                                <td class="dbim-nr-num">{{ $daysPassed }}</td>
                                @if($isAdmin)
                                    <td>{{ $request->username ?? '' }}</td>
                                @endif
                                @if ($isClosed)
                                    <td class="dbim-nr-num">{{ $request->updated_at->format('d-m-Y H:i') }}</td>
                                @endif
                                <td><span class="dbim-nr-status">{{ $request->status }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($secondAppealData->count() > 0)
                <div class="dbim-pv-pager">
                    {{ $secondAppealData->onEachSide(1)
                                        ->links('Pagination', ['label' => $pageTitle . ' pages']) }}
                </div>
            @endif
        </section>

      </div>
    </div>
  </div>
</div>
@endsection