{{-- ============================================================
     Officer: View Notices (DBIM / GIGW 3.0, keyboard + screen reader friendly)
     One card on the grey band (.dbim-sd frame).
     Pagination: resources/views/pagination.blade.php
     Styles: dbim.css sections 50, 58, 79, 84, 85.
     No inline styles, no inline event handlers.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'View Notices | RTI Online, Government of Goa')

@section('content')
<div class="dbim-sd">
  <div class="dbim-sd-section">
    <div class="dbim-sd-wrap">

      @include('partials.flash-messages')

      <div class="dbim-sd-card">

        <h1 class="dbim-sd-heading">View Notices</h1>

        {{-- ===== Office / user details ===== --}}
        @include('OfficerView.officer-header-card')

        {{-- ===== Notices table ===== --}}
        <section aria-labelledby="noticesHeading">
            <h2 id="noticesHeading" class="visually-hidden">Notices list</h2>

            <div class="dbim-sd-scroll" role="region" tabindex="0" aria-labelledby="noticesHeading">
                <table class="dbim-sd-table dbim-hl-table">
                    <caption class="visually-hidden">Notices</caption>
                    <thead>
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
                        @forelse($requestLines as $request)
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
                        @empty
                            <tr>
                                <td colspan="6" class="dbim-nr-none">No Data Present</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($requestLines->count() > 0)
                <div class="mt-4">
                    {{ $requestLines->onEachSide(1)
                                    ->links('Pagination', ['label' => 'Notices pages']) }}
                </div>
            @endif
        </section>

      </div>{{-- .dbim-sd-card --}}

    </div>{{-- .dbim-sd-wrap --}}
  </div>{{-- .dbim-sd-section --}}
</div>{{-- .dbim-sd --}}
@endsection