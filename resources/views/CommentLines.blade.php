{{-- ============================================================
     Officer: View Comments / Reply (DBIM / GIGW 3.0)
     One card on the grey band (.dbim-sd frame).
     Pagination: resources/views/pagination.blade.php
     Styles: dbim.css sections 50, 58, 79, 84, 85.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'View Comments / Reply | RTI Online, Government of Goa')

@section('content')
<div class="dbim-sd">
  <div class="dbim-sd-section">
    <div class="dbim-sd-wrap">

      @include('partials.flash-messages')

      <div class="dbim-sd-card">

        <h1 class="dbim-sd-heading">View Comments / Reply</h1>

        {{-- ===== Office / user details ===== --}}
        @include('OfficerView.officer-header-card')

        {{-- ===== Comments table ===== --}}
        <section aria-labelledby="commentsHeading">
            <h2 id="commentsHeading" class="visually-hidden">Comments and replies list</h2>

            @php
                $colCount = session('role') == 1 ? 7 : 6;
            @endphp

            <div class="dbim-sd-scroll" role="region" tabindex="0" aria-labelledby="commentsHeading">
                <table class="dbim-sd-table dbim-hl-table">
                    <caption class="visually-hidden">View comments / reply</caption>
                    <thead>
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
                        @forelse($requestLines as $request)
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
                        @empty
                            <tr>
                                <td colspan="{{ $colCount }}" class="dbim-nr-none">No Data Present</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($requestLines->count() > 0)
                <div class="mt-4">
                    {{ $requestLines->onEachSide(1)
                                    ->links('Pagination', ['label' => 'Comments and replies pages']) }}
                </div>
            @endif
        </section>

      </div>{{-- .dbim-sd-card --}}

    </div>{{-- .dbim-sd-wrap --}}
  </div>{{-- .dbim-sd-section --}}
</div>{{-- .dbim-sd --}}
@endsection