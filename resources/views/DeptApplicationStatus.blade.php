{{-- ============================================================
     Officer: Department / Application wise actions completed
     (DBIM / GIGW 3.0, keyboard + screen reader friendly)
     Shared by "Department wise" and "Application wise" pages.
     One card on the grey band (.dbim-sd frame).
     Pagination: resources/views/pagination.blade.php
     No inline styles, no inline event handlers.
     ============================================================ --}}
@extends('layouts.app')

@section('title', $category . ' | RTI Online, Government of Goa')

@section('content')
<div class="dbim-sd">
  <div class="dbim-sd-section">
    <div class="dbim-sd-wrap">

      @include('partials.flash-messages')

      <div class="dbim-sd-card">

        <h1 class="dbim-sd-heading">{{ $category }}</h1>

        {{-- ===== Office / user details ===== --}}
        @include('OfficerView.officer-header-card')

        {{-- ===== Search (submits to the page you are on) ===== --}}
        <form method="GET" action="{{ request()->url() }}" role="search"
              aria-label="Search {{ $category }}">
            <label for="search" class="dbim-sd-label">Search</label>
            <div class="dbim-sd-search">
                <input type="search" class="form-control" id="search" name="search"
                       value="{{ request('search') }}"
                       placeholder="Registration Number / Applicant Name"
                       autocomplete="off">
                <button type="submit" class="dbim-sd-btn">Search</button>
            </div>
        </form>

        <hr class="dbim-sd-divider">

        {{-- ===== Applications table ===== --}}
        <section aria-labelledby="statusHeading">
            <h2 id="statusHeading" class="visually-hidden">{{ $category }} list</h2>

            @if($status->count() == 0)
                <div class="alert alert-info mb-0" role="status">No Data Present</div>
            @else
                <div class="dbim-sd-scroll" role="region" tabindex="0" aria-labelledby="statusHeading">
                    <table class="dbim-sd-table">
                        <caption class="visually-hidden">{{ $category }}</caption>
                        <thead>
                            <tr>
                                <th scope="col">Sr No.</th>
                                <th scope="col">Registration Number</th>
                                <th scope="col">Applicant Name</th>
                                <th scope="col">Public Authority</th>
                                <th scope="col">Action Date</th>
                                <th scope="col">Action Completed</th>
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
                                    <td>{{ $st->applicant_name }}</td>
                                    <td>{{ $st->department }}</td>
                                    <td>{{ \Carbon\Carbon::parse($st->app_history_created)->format('d-m-Y H:i') }}</td>
                                    <td>{{ $st->action_ }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $status->withQueryString()
                              ->onEachSide(1)
                              ->links('Pagination', ['label' => $category . ' pages']) }}
                </div>
            @endif
        </section>

      </div>{{-- .dbim-sd-card --}}

    </div>{{-- .dbim-sd-wrap --}}
  </div>{{-- .dbim-sd-section --}}
</div>{{-- .dbim-sd --}}
@endsection