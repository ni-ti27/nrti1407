{{-- ============================================================
     First Appeal list (New / In Process / Closed)
     Same layout as OfficerView dashboard: Linen band, one white card.
     Styles: dbim.css sections 50, 80, 82 (header strip), 79 (status, no-data)
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Dashboard | RTI Online, Government of Goa')

@section('content')
@php
    $reqType = $req_type ?? '';
    $pageTitle = match (true) {
        $reqType === '{in_process_FA}' => 'In Process First Appeal Request',
        $reqType === '{closed_FA}'     => 'Closed First Appeal Request',
        default                        => 'New First Appeal Request',
    };
    $isClosed    = $reqType === '{closed_FA}';
    $isAdmin     = session('role') == 1;
    $showSubDept = !in_array(session('role_name'), ['Nodal Officer', 'First Appellete Authority']);
    $serial      = $firstAppealData->firstItem();
    $colCount    = 6 + ($isAdmin ? 1 : 0) + ($isClosed ? 1 : 0);
@endphp
<div class="dbim-sd dbim-nr-page">
  <div class="dbim-sd-section">
    <div class="dbim-sd-wrap">
      <div class="dbim-sd-card">

        <div class="dbim-nr-head">
            <h1 class="dbim-sd-heading">{{ $pageTitle }}</h1>
        </div>

        {{-- ===== Office / user details ===== --}}
        @include('OfficerView.officer-header-card', ['showSubDept' => $showSubDept])

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
                            <th scope="col">{{ $isClosed ? 'Action (in Days)' : 'Pending Since (in Days)' }}</th>
                            @if($isAdmin)
                                <th scope="col">Assigned to (username)</th>
                            @endif
                            @if($isClosed)
                                <th scope="col">Last Action Date</th>
                            @endif
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($firstAppealData as $request)
                            @php
                                $createdAt  = \Carbon\Carbon::parse($request->created_at);
                                $daysPassed = $createdAt->diffInDays($isClosed ? $request->updated_at : now());
                            @endphp
                            <tr>
                                <td>{{ $serial++ }}</td>
                                <td>
                                    <a href="{{ route('detailedFARequest', ['id' => $request->id]) }}">{{ $request->fa_reg_no }}</a>
                                </td>
                                <td>{{ $request->name }}</td>
                                <td class="dbim-nr-num">{{ $request->created_at->format('d-m-Y H:i') }}</td>
                                <td class="dbim-nr-num">{{ $daysPassed }}</td>
                                @if($isAdmin)
                                    <td>{{ $request->username }}</td>
                                @endif
                                @if($isClosed)
                                    <td class="dbim-nr-num">{{ $request->updated_at->format('d-m-Y H:i') }}</td>
                                @endif
                                <td><span class="dbim-nr-status">{{ $request->status }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $colCount }}" class="dbim-nr-none">No Data Present</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($firstAppealData->count() != 0)
                {{ $firstAppealData->onEachSide(1)
                                   ->links('Pagination', ['label' => $pageTitle . ' pages']) }}
            @endif
        </section>

      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(el => el.style.display = 'none');
    }, 10000);
</script>
@endpush