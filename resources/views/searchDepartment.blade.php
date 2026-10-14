{{-- ============================================================
     Search for Department / Office / Sub-Office
     (DBIM / GIGW 3.0, keyboard accessible)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Search Public Authorities | RTI Online, Government of Goa')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">

            {{-- ===== Search form ===== --}}
            <div class="card shadow-sm">
                <h1 class="card-header h4 mb-0">Search for Department / Office / Sub-Office</h1>

                <div class="card-body">
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
                </div>
            </div>

            {{-- ===== Results ===== --}}
            @if(!empty($search))
                <section class="mt-4" aria-labelledby="searchResultsHeading">
                    <h2 id="searchResultsHeading" class="h4" tabindex="-1">Search results for: "{{ $search }}"</h2>

                    @if($departments->isEmpty())
                        <div class="alert alert-info" role="status">No departments found.</div>
                    @else
                        @if(!empty($hierarchy))
                            <h3 class="h5 mt-4">Detailed Hierarchy</h3>

                            {{-- scrollable on small screens; focusable so keyboard users can scroll it --}}
                            <div class="table-responsive" role="region" tabindex="0"
                                 aria-labelledby="searchResultsHeading">
                                <table class="table table-bordered align-middle">
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
    </div>
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