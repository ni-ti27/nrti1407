{{-- ============================================================
     Officer: Application list (DBIM / GIGW 3.0, keyboard + screen reader friendly)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     Pagination: resources/views/pagination.blade.php
     No inline styles, no inline event handlers.
     Look: same as the Officer Dashboard (Layout B: grey band + one white card,
     title inside the card, light-blue table header, status pill).
     Styles: dbim.css sections 50, 64, 76 and 79.
     ONLY THE MARKUP AND CLASSES CHANGED: every variable, column, search field and
     the pagination call is the same as before.
     ============================================================ --}}
@extends('layouts.app')

@section('title', $category . ' | RTI Online, Government of Goa')

@section('content')
<div class="dbim-sd dbim-as dbim-nr-page">
  <div class="dbim-sd-section">
    <div class="dbim-sd-wrap">
      <div class="dbim-sd-card">

        <div class="dbim-nr-head">
            <h1 class="dbim-sd-heading">{{ $category }}</h1>
        </div>

        @include('OfficerView.officer-header-card')

        {{-- ===== Flash messages ===== --}}
        @include('partials.flash-messages')

        {{-- ===== Search ===== --}}
        <form method="GET" action="{{ request()->url() }}" role="search"
              class="dbim-as-search" aria-label="Search applications">
            <label for="search" class="dbim-sd-label">Search</label>
            <div class="input-group">
                <input type="search" class="form-control" id="search" name="search"
                       value="{{ request('search') }}"
                       placeholder="Registration Number / Applicant Name"
                       autocomplete="off">
                <button type="submit" class="dbim-sd-btn" title="Search">Search</button>
            </div>
        </form>

        <hr class="dbim-sd-divider">

        {{-- ===== Applications table ===== --}}
        <section aria-labelledby="statusHeading">
            <h2 id="statusHeading" class="visually-hidden">{{ $category }} list</h2>

            <div class="dbim-sd-scroll" role="region" tabindex="0" aria-labelledby="statusHeading">
                <table class="dbim-sd-table dbim-nr-table">
                    <caption class="visually-hidden">{{ $category }}</caption>
                    <thead>
                        <tr>
                            <th scope="col" class="dbim-nr-col-sr">Sr No.</th>
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
                        @forelse($status as $st)
                            <tr>
                                <td>{{ $serialNumber++ }}</td>
                                <td>{{ $st->reg_no }}</td>
                                <td class="dbim-nr-num">{{ \Carbon\Carbon::parse($st->date_of_efiling)->format('d-m-Y H:i') }}</td>
                                <td>{{ $st->applicant_name }}</td>
                                <td>{{ $st->department }}</td>
                                <td class="dbim-nr-num">{{ \Carbon\Carbon::parse($st->app_updated)->format('d-m-Y H:i') }}</td>
                                <td><span class="dbim-nr-status">{{ $st->status }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="dbim-nr-none">No Data Present</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($status->count() > 0)
                <div class="dbim-pv-pager">
                    {{ $status->withQueryString()
                              ->onEachSide(1)
                              ->links('Pagination', ['label' => $category . ' pages']) }}
                </div>
            @endif
        </section>

      </div>
    </div>
  </div>
</div>
@endsection