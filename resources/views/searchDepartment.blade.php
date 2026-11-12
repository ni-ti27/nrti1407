{{-- ============================================================
     Search for Department / Office / Sub-Office
     (DBIM / GIGW 3.0, same look as the Home page)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     Styles: section 37 (home) + section 40 (search) in public/css/dbim.css
     Bands: white (heading + form) / light blue (results, only after a search)
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Search Public Authorities | RTI Online, Government of Goa')

@section('content')
<div class="dbim-hp">

    {{-- 1. HEADING + SEARCH FORM (white) --}}
    <section class="dbim-hp-section" aria-labelledby="searchHeading">
        <div class="dbim-hp-wrap">
            <h1 id="searchHeading" class="dbim-hp-heading">Search for Department / Office / Sub-Office</h1>

            <form method="POST" action="{{ route('department.show') }}"
                  role="search" aria-label="Search for department, office or sub-office">
                @csrf

                <label for="search" class="form-label fw-bold">Enter characters to search</label>
                <div class="dbim-hp-search">
                    <input type="text" id="search" name="search" class="form-control"
                           value="{{ old('search', $search ?? '') }}"
                           placeholder="Enter characters of the department, office or sub-office name"
                           autocomplete="off">
                    <button type="submit" class="download-btn text-uppercase" title="Search">
                        Search
                    </button>
                </div>
            </form>
        </div>
    </section>

        {{-- 2. RESULTS (light blue) --}}
    @if(!empty($search))
        <section class="dbim-hp-section" aria-labelledby="searchResultsHeading">
            <div class="dbim-hp-wrap">
                <h2 id="searchResultsHeading" class="dbim-hp-heading" tabindex="-1">Search results for: "{{ $search }}"</h2>

                @php $n = count($hierarchy ?? []); @endphp
<div class="dbim-hp-card dbim-hp-card--table">
    <div class="dbim-hp-card__bar">
        <h3 class="dbim-hp-card__title">Detailed Hierarchy</h3>
        <span class="dbim-count">{{ $n }} {{ $n === 1 ? 'record' : 'records' }}</span>
    </div>


                    {{-- scrollable on small screens; focusable so keyboard users can scroll it --}}
                    <div class="table-responsive" role="region" tabindex="0"
                         aria-labelledby="searchResultsHeading">
                        <table class="table table-bordered align-middle mb-0">
                            <caption class="visually-hidden">
                                Departments, offices and sub-offices matching "{{ $search }}"
                            </caption>
                            <thead>
                                <tr>
                                    <th scope="col">Department</th>
                                    <th scope="col">Office</th>
                                    <th scope="col">Sub-Office</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($hierarchy ?? []) as $entry)
                                    @php
                                        $office    = $entry['office_details']->office ?? null;
                                        $suboffice = $entry['suboffice_details']->suboffice ?? null;
                                    @endphp
                                    <tr>
                                        <td>{{ $entry['dept_details']->department }}</td>
                                        <td>
                                            @if($office)
                                                {{ $office }}
                                            @else
                                                <span aria-hidden="true">-</span><span class="visually-hidden">Not applicable</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($suboffice)
                                                {{ $suboffice }}
                                            @else
                                                <span aria-hidden="true">-</span><span class="visually-hidden">Not applicable</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="dbim-table-empty">
                                        <td colspan="3" role="status">No records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    @endif
</div>
@endsection

@if(!empty($search))
    @push('scripts')
    <script>
        /* After a search, put keyboard / screen-reader focus on the results heading */
        (function () {
            var heading = document.getElementById('searchResultsHeading');
            if (heading) { heading.focus(); }
        })();
    </script>
    @endpush
@endif