{{-- ============================================================
     Search for Department / Office / Sub-Office
     (DBIM / GIGW 3.0, same look as the Home page)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     Styles: section 50 in public/css/dbim.css (existing variables only)
     Layout: ONE grey (Linen) card holding the heading, the search form
             and, after a search, the results table.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Search Public Authorities | RTI Online, Government of Goa')

@section('content')
<div class="dbim-sd">
    <section class="dbim-sd-section" aria-labelledby="searchHeading">
        <div class="dbim-sd-wrap">
            <div class="dbim-sd-card">

                {{-- Main heading of the page: the only one with the blue bar --}}
                <h1 id="searchHeading" class="dbim-sd-heading">Search for Department / Office / Sub-Office</h1>

                {{-- SEARCH FORM --}}
                <form method="POST" action="{{ route('department.show') }}"
                      role="search" aria-label="Search for department, office or sub-office">
                    @csrf

                    <label for="search" class="dbim-sd-label">Enter characters to search</label>
                    <div class="dbim-sd-search">
                        <input type="text" id="search" name="search" class="form-control"
                               value="{{ old('search', $search ?? '') }}"
                               placeholder="Enter characters of the department, office or sub-office name"
                               autocomplete="off">
                        <button type="submit" class="dbim-sd-btn" title="Search">Search</button>
                    </div>
                </form>

                {{-- RESULTS (only after a search) --}}
                @if(!empty($search))
                    <hr class="dbim-sd-divider">

                    <section aria-labelledby="searchResultsHeading">
                        <h2 id="searchResultsHeading" class="dbim-sd-results-heading" tabindex="-1">
                            Search results for: "{{ $search }}"
                        </h2>

                        @php $n = count($hierarchy ?? []); @endphp
                        <div class="dbim-sd-bar">
                            <h3 class="dbim-sd-bar__title">Detailed Hierarchy</h3>
                            <span class="dbim-sd-count">{{ $n }} {{ $n === 1 ? 'record' : 'records' }}</span>
                        </div>

                        {{-- scrollable on small screens; focusable so keyboard users can scroll it --}}
                        <div class="dbim-sd-scroll" role="region" tabindex="0"
                             aria-labelledby="searchResultsHeading">
                            <table class="dbim-sd-table">
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
                                                    <span class="dbim-sd-dash" aria-hidden="true">-</span><span class="visually-hidden">Not applicable</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($suboffice)
                                                    {{ $suboffice }}
                                                @else
                                                    <span class="dbim-sd-dash" aria-hidden="true">-</span><span class="visually-hidden">Not applicable</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" role="status" class="dbim-sd-empty">No records found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>
                @endif

            </div>
        </div>
    </section>
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