{{-- ============================================================
     Search for Department / Office / Sub-Office
     (DBIM / GIGW 3.0, keyboard accessible)
     Same card as the Officers Login page (dbim-login__*), wider.
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     Styles: sections 27 and 30 of public/css/dbim.css
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Search Public Authorities | RTI Online, Government of Goa')

@section('content')
<div class="dbim-login dbim-login--wide">
    <section class="dbim-login__card" aria-labelledby="searchHeading">

        <div class="dbim-login__head">
            <h1 id="searchHeading" class="dbim-login__title">Search for Department / Office / Sub-Office</h1>
        </div>

        <div class="dbim-login__body">

            {{-- ===== Search form ===== --}}
            <form method="POST" action="{{ route('department.show') }}"
                  role="search" aria-label="Search for department, office or sub-office">
                @csrf

                <div class="mb-3">
                    <label for="search" class="form-label fw-bold">Enter characters to search</label>
                    <input type="text" id="search" name="search" class="form-control"
                           value="{{ old('search', $search ?? '') }}"
                           placeholder="Enter characters of the department, office or sub-office name"
                           autocomplete="off">
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="download-btn text-uppercase w-ft" title="Search">
                        Search
                    </button>
                </div>
            </form>

            {{-- ===== Results ===== --}}
            @if(!empty($search))
                <section class="dbim-search__results" aria-labelledby="searchResultsHeading">
                    <h2 id="searchResultsHeading" class="dbim-search__heading" tabindex="-1">Search results for: "{{ $search }}"</h2>

                    @if($departments->isEmpty())
                        <div class="alert alert-info mb-0" role="status">No departments found.</div>
                    @else
                        @if(!empty($hierarchy))
                            <h3 class="dbim-search__subheading">Detailed Hierarchy</h3>

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
                                        @foreach($hierarchy as $entry)
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
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @endif
                </section>
            @endif

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