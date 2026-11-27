{{-- ============================================================
     RTI Online - common header (DBIM / GIGW 3.0 accessible)
     Included from layouts/app.blade.php
     Styles : public/css/dbim.css
     Accessibility tool : resources/views/partials/accessibility.blade.php
                          (one @include line, see the utility icons row)

     Keyboard support
       Tab / Shift+Tab ... move between controls
       1st Tab stop ...... Skip to main content
       2nd Tab stop ...... Skip to navigation
       Nav links ......... Left/Right (or Up/Down) arrows, Home, End
       Language menu ..... Enter/Space open, Up/Down arrows move, Esc closes
       Accessibility ..... Enter/Space opens the side panel, Esc closes it
     ============================================================ --}}
{{-- "RTI New Request" is active on every page of that flow.
     ADD any other URL / route patterns of the new-request pages to this list.
     Keep both variables in this single PHP block. --}}
@php
    $pending = $hasPendingRecords ?? false;
    $rtiNewRequestActive = request()->is('profile*')
        || request()->routeIs('pending-records-list*');
@endphp

{{-- Self-contained fallback: if the page layout did NOT already load the DBIM
     assets (layouts/app.blade.php sets $dbimAssetsLoaded), load them here. --}}
@unless($dbimAssetsLoaded ?? false)
    <link href="https://fonts.bunny.net/css?family=noto-sans:400,500,600,700|noto-sans-devanagari:400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('Compiled/css/compiled.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dbim.css') }}">
    <script src="{{ asset('Compiled/js/compiled.bundle.min.js') }}"></script>
@endunless

{{-- Skip links: first two Tab stops on every page --}}
<a class="dbim-skip-link" href="#main-content">Skip to main content</a>
<a class="dbim-skip-link" href="#navbarNav" id="skipToNav">Skip to navigation</a>

{{-- Screen-reader announcements (text size / theme / filter changes) --}}
<div id="dbimStatus" class="visually-hidden" role="status" aria-live="polite" aria-atomic="true"></div>

<header class="dbim-header" role="banner">

<div class="dbim-header__top">
    <div class="container-fluid px-3 px-lg-5">
        <div class="row align-items-center g-3">

            {{-- Emblem + title --}}
            <div class="col-12 col-lg-4">
                <div class="dbim-header__brand d-flex align-items-center justify-content-start gap-4">
                    <img class="dbim-header__emblem" src="{{ asset('images/Emblem_of_Goa.png') }}" alt="Emblem of Goa" width="57" height="100">
                    <span>
                        <span class="gov">Government of Goa</span>
                        <span class="name">RTI Online </span>
                        <span class="tag d-none d-md-block">
                            An Initiative of Department of Information &amp; Publicity, Govt. of Goa
                        </span>
                    </span>
                </div>
            </div>

            {{-- Search --}}
            <div class="col-12 col-md-6 col-lg-3">
                <form class="dbim-header__appsearch" role="search" aria-label="Site search"
                      method="GET" action="{{ url('/searchDepartment') }}">
                    <label for="headerSearch" class="visually-hidden">Search public authorities</label>
                    <input type="search" id="headerSearch" name="search" placeholder="Search..."
                           value="{{ request('search') }}" autocomplete="off">
                    <button type="submit" aria-label="Submit search" title="Search">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                            <circle cx="11" cy="11" r="7"></circle>
                            <line x1="20" y1="20" x2="16.5" y2="16.5"></line>
                        </svg>
                    </button>
                </form>
            </div>

            {{-- Digital India logo --}}
            <div class="col-6 col-md-3 col-lg-2">
                <div class="dbim-header__logo">
                    <a href="https://www.digitalindia.gov.in/" target="_blank" rel="noopener noreferrer"
                       aria-label="Digital India, opens in a new tab">
<svg xmlns="http://www.w3.org/2000/svg" id="Layer_1"    width="112" height="60" class="dbim-header__logo-svg" data-name="Layer 1" viewBox="0 0 280 150" style="display: flex; justify-content: center;"><defs><style>.cls-1{fill:#0e6eb6;}.cls-1,.cls-2,.cls-3,.cls-4{fill-rule:evenodd;}.cls-2{fill:#f26329;}.cls-3{fill:#09a14a;}</style></defs><title>Digital India</title><path class="cls-1" d="M14.26,75.78l-1.09-1.1a10.69,10.69,0,0,1,15.24,0L27.3,75.74a9.15,9.15,0,0,0-13,0m3.84,3.68-1-1a5.21,5.21,0,0,1,7.48,0l-1,1a3.89,3.89,0,0,0-2.71-1.12,3.77,3.77,0,0,0-2.71,1.12m2.71,2.68-1.94-1.9a3,3,0,0,1,1.94-.82,2.87,2.87,0,0,1,1.92.82Zm0-6.36a6.16,6.16,0,0,1,4.52,1.92l1.21-1.21a8,8,0,0,0-11.5,0l1.2,1.22a6.26,6.26,0,0,1,4.56-1.93" fill="undefined"/><path class="cls-1" d="M13.59,83.42H27.84c-10.22,25.85-12,41-1.63,40.6-22.75,1.34-19.8-20.76-12.61-40.6" fill="undefined"/><path class="cls-2" d="M26.69,43.77c13-21.18,34.08-23.34,49.1-15.63C92.45,36.71,97.59,56.54,93.44,74c-3.65,15.88-14.11,30.39-27.63,39.72a68.62,68.62,0,0,1-27.55,10.95A44.75,44.75,0,0,0,57.88,111.8a60.38,60.38,0,0,0,8.45-11.73C74,89.73,77.55,79.6,77.8,70.78c.5-11.83-4.65-23.57-15.52-29-9.46-4.81-22.2-4.56-35.59,2" fill="undefined"/><path class="cls-3" d="M16.87,67.66c10.6-21,32.43-22.47,45.29-13.38a25.21,25.21,0,0,1,10,15C73.91,77,72.63,85,69.73,92.63A58.38,58.38,0,0,1,57.88,111.8c-7.32,8.09-17.8,14.26-29,14.14a24.79,24.79,0,0,1-12.73-3.44c5.29,2.55,13.52,1.65,18.55-.36,8.32-3.41,15-10.48,19.17-18.3,5.15-9.58,9.21-25,1-33.83-7.45-8.2-22.31-11-38-2.36" fill="undefined"/><path d="M265.87,79.69l2.52-7.23c-.3,0-.62,0-.89,0a5.71,5.71,0,0,0-4.25,1.65,7.82,7.82,0,0,0-2.18,4.05,3.21,3.21,0,0,0,.07,2.13,1.43,1.43,0,0,0,1.38.78,5.85,5.85,0,0,0,3.36-1.33m8-10.49L268.6,84.07h-4.29l.49-1.41a7.87,7.87,0,0,1-4.52,1.71,3.47,3.47,0,0,1-3.21-1.83,6.34,6.34,0,0,1-.41-4.68,11.1,11.1,0,0,1,4.16-6.61,12.77,12.77,0,0,1,8.27-2.57,20.29,20.29,0,0,1,4.74.51M256.35,69,251,84.07h-4.29L252.08,69Zm-2.92-4.22a2.86,2.86,0,0,1,2.69-2.31,1.76,1.76,0,0,1,1.43.67,1.86,1.86,0,0,1,.3,1.64,3.14,3.14,0,0,1-1,1.69,2.79,2.79,0,0,1-1.74.64,1.77,1.77,0,0,1-1.41-.64,2,2,0,0,1-.25-1.69M240,80.52l2.75-7.83a6.26,6.26,0,0,0-1.07-.08,6.09,6.09,0,0,0-3.89,1.3,5.65,5.65,0,0,0-2.08,3.25,2.93,2.93,0,0,0,.47,2.58,3.22,3.22,0,0,0,2.6,1,6.84,6.84,0,0,0,1.22-.18m11-19.17-7.91,22.51a30.69,30.69,0,0,1-5.05.51c-2.57,0-4.46-.66-5.68-2s-1.56-3.07-1.07-5.27a10.44,10.44,0,0,1,3.72-6,10.59,10.59,0,0,1,7-2.46,16.9,16.9,0,0,1,2.06.16l2.63-7.49ZM221.72,69l-.65,1.69a9.69,9.69,0,0,1,5.36-2A2.77,2.77,0,0,1,229,69.92a4.47,4.47,0,0,1,.25,3.42,10.83,10.83,0,0,1-.37,1.35l-3.29,9.39h-4.27l3.17-9a3.56,3.56,0,0,0,.26-.79c.3-1.49,0-2.22-.87-2.22s-2.38.75-4.09,2.32l-3.43,9.71h-4.29L217.43,69Zm-6.1-7.61-8,22.72H203l8-22.72Zm-17.8,0-8,22.72h-4.25l8-22.72Zm-19,18.34,2.5-7.23c-.3,0-.62,0-.89,0a5.67,5.67,0,0,0-4.23,1.65A7.84,7.84,0,0,0,174,78.11a3,3,0,0,0,.09,2.13,1.4,1.4,0,0,0,1.36.78,6,6,0,0,0,3.37-1.33m8-10.49-5.25,14.87h-4.27l.5-1.41a8,8,0,0,1-4.51,1.71A3.51,3.51,0,0,1,170,82.55a6.5,6.5,0,0,1-.41-4.68,11.06,11.06,0,0,1,4.16-6.61,12.82,12.82,0,0,1,8.29-2.57,20.37,20.37,0,0,1,4.73.51m-18.58-3.42L166.93,69h3.15l-1.29,3.3h-3.17l-1.88,4.92a5.23,5.23,0,0,0-.36,1.16c-.31,1.35,0,2,.87,2a7.51,7.51,0,0,0,2.65-.91l-.87,4a9.09,9.09,0,0,1-3.88,1,3.42,3.42,0,0,1-3-1.39,4.71,4.71,0,0,1-.41-3.64c.13-.43.26-.87.41-1.31l2.29-5.77h-2.9l1.26-3.3h2.92l1.21-3.19ZM157.74,69,152.4,84.07h-4.27L153.45,69Zm-2.95-4.22a3.05,3.05,0,0,1,1-1.64,2.78,2.78,0,0,1,1.73-.67,1.76,1.76,0,0,1,1.44.67,2.06,2.06,0,0,1,.3,1.64,3.4,3.4,0,0,1-1,1.69,2.89,2.89,0,0,1-1.73.64,1.69,1.69,0,0,1-1.41-.64,2,2,0,0,1-.3-1.69M140.87,87.41c.14-.7-.43-1.22-1.69-1.49l-1.79-.43a4.7,4.7,0,0,0-1.7.43,1.88,1.88,0,0,0-1.33,1.35c-.22,1.06.72,1.57,2.84,1.57a8,8,0,0,0,2.63-.33,1.49,1.49,0,0,0,1-1.1m-.18-10c1.55,0,2.55-1.1,3-3.26a2,2,0,0,0-.17-1.57,1.54,1.54,0,0,0-1.34-.58c-1.56,0-2.59,1.09-3.11,3.32-.29,1.37.22,2.09,1.58,2.09M151,69l-1.14,3.3h-2.29a6.28,6.28,0,0,1,0,2.42,6.85,6.85,0,0,1-2.49,3.92,7.44,7.44,0,0,1-4.75,1.59,10.31,10.31,0,0,0-2,.14.82.82,0,0,0-.73.68c-.05.18,0,.34.28.54a19.65,19.65,0,0,0,3,.64c1.77.31,3,.81,3.58,1.62a3.42,3.42,0,0,1,.56,3c-.71,3.26-3.55,4.92-8.51,4.92a9.18,9.18,0,0,1-4.9-1A2.44,2.44,0,0,1,130.44,88c.33-1.44,1.75-2.61,4.29-3.37a2.46,2.46,0,0,1-1.37-2.81c.31-1.4,1.48-2.33,3.51-2.78a3.94,3.94,0,0,1-1.55-4.36,7,7,0,0,1,2.69-4A7.66,7.66,0,0,1,143,69Zm-17,0-5.34,15.11h-4.27L129.66,69Zm-3-4.22a3.07,3.07,0,0,1,1-1.64,2.81,2.81,0,0,1,1.71-.67,1.82,1.82,0,0,1,1.44.67,1.87,1.87,0,0,1,.27,1.64,3.36,3.36,0,0,1-1,1.69,2.8,2.8,0,0,1-1.75.64,1.72,1.72,0,0,1-1.4-.64,2.19,2.19,0,0,1-.3-1.69m-18.83.39-5.28,15.14h4.3A8.06,8.06,0,0,0,116.95,78a10.54,10.54,0,0,0,3.17-5.67,6.14,6.14,0,0,0-.83-5.21,5.79,5.79,0,0,0-4.9-2Zm-3.23-3.78h7.18c3.62,0,6.16,1,7.63,3.08s1.83,4.73,1.13,8a15.47,15.47,0,0,1-4.76,8.34,11.53,11.53,0,0,1-8,3.32H100.95Z" fill="undefined"/><path class="cls-4" d="M102.75,99.6h2.47a3.52,3.52,0,0,0,2.25-.72,2.32,2.32,0,0,0,.79-1.88,2.39,2.39,0,0,0-.79-1.91,3.31,3.31,0,0,0-2.25-.69h-2.47Zm0,1.37v5.78h-1.46V93h3.61a5.88,5.88,0,0,1,3.6,1A3.63,3.63,0,0,1,109.83,97a3.56,3.56,0,0,1-1.33,2.92,5.69,5.69,0,0,1-3.6,1.06Z" fill="undefined"/><path class="cls-4" d="M115.71,105.42a3.2,3.2,0,0,0,2.4-1,3.56,3.56,0,0,0,1-2.54,3.45,3.45,0,0,0-1-2.53,3.16,3.16,0,0,0-2.4-1.08,3.12,3.12,0,0,0-2.43,1.08,3.48,3.48,0,0,0-1,2.53,3.58,3.58,0,0,0,1,2.54,3.16,3.16,0,0,0,2.43,1m0,1.36a4.56,4.56,0,0,1-3.47-1.39,5.32,5.32,0,0,1,0-7.13,4.95,4.95,0,0,1,6.93,0,5.37,5.37,0,0,1,0,7.13,4.55,4.55,0,0,1-3.45,1.39" fill="undefined"/><polygon class="cls-4" points="126.28 104.29 128.98 96.67 129.87 96.67 132.62 104.28 135.5 96.81 136.84 96.81 132.98 106.76 132.1 106.76 129.4 99.3 126.67 106.76 125.8 106.76 121.94 96.81 123.42 96.81 126.28 104.29"/><path class="cls-4" d="M145,100.62a3.11,3.11,0,0,0-.64-1.43,2.56,2.56,0,0,0-2.12-.93,2.69,2.69,0,0,0-2,1,4.5,4.5,0,0,0-.69,1.37Zm-5.65,1.18h0a3.45,3.45,0,0,0,1.05,2.5,3.39,3.39,0,0,0,2.53,1.06,4.89,4.89,0,0,0,1.7-.3,6.18,6.18,0,0,0,1.6-.94l.32-.27v1.54l-.1,0a5.59,5.59,0,0,1-1.68,1,5.42,5.42,0,0,1-5.42-1.06,4.79,4.79,0,0,1-1.39-3.55,5.3,5.3,0,0,1,1.2-3.58,3.89,3.89,0,0,1,3.09-1.4,4,4,0,0,1,3.19,1.31,5.27,5.27,0,0,1,1.1,3.39l0,.2Z" fill="undefined"/><path class="cls-4" d="M151,98.26a3,3,0,0,1,4.12-1.19.1.1,0,0,1,.09.07V98.7c-.09-.05,0,0-.12,0-2.26-1.26-3.64.55-4.09,1.78v6.26h-1.39v-10H151Z" fill="undefined"/><polygon class="cls-4" points="165.37 94.44 160.96 94.44 160.96 93.18 171.26 93.18 171.26 94.44 166.85 94.44 166.85 106.61 165.37 106.61 165.37 94.44"/><polygon class="cls-4" points="189.38 94.65 189.38 99.04 194.96 99.04 194.96 100.34 189.38 100.34 189.38 105.25 196 105.25 196 106.56 187.91 106.56 187.91 93.34 195.74 93.34 195.74 94.65 189.38 94.65"/><path class="cls-4" d="M200,98.07a3.56,3.56,0,0,1,2.77-1.33,3.43,3.43,0,0,1,2.61,1.06,4.17,4.17,0,0,1,.57.78l.2-.33a3.75,3.75,0,0,1,2.95-1.52,3.41,3.41,0,0,1,2.61,1.06,3.7,3.7,0,0,1,1,2.71v5.92h-1.45v-5.86a2.51,2.51,0,0,0-.63-1.79,2.05,2.05,0,0,0-1.67-.68,2.78,2.78,0,0,0-2.1,1.12,3.12,3.12,0,0,0-.48.62v6.59h-1.45v-5.86a2.47,2.47,0,0,0-.63-1.79,2.1,2.1,0,0,0-1.67-.68,2.84,2.84,0,0,0-2.12,1.12,6.16,6.16,0,0,0-.49.62v6.59H198.6v-9.5H200Z" fill="undefined"/><path d="M215.81,96.94h1.45v1.2a2.42,2.42,0,0,1,1.35-1.07,4.79,4.79,0,0,1,5.11,1,5.7,5.7,0,0,1,.05,7.21c-1.74,1.9-4.29,1.47-6.37.37l-.14-.09v4.63h-1.45Zm5.84,1.73c-1.2-.78-2.95-.6-4.36,1.29v3.87c1.47,1.58,3.37,1.73,4.63.81a3.8,3.8,0,0,0-.27-6" fill="undefined"/><rect x="98" y="86.35" width="27.4" height="1.58"/><rect x="147.46" y="86.35" width="124.66" height="1.58"/><path class="cls-4" d="M175.2,105.16a3.13,3.13,0,0,0,2.4-1,3.59,3.59,0,0,0,1-2.53,3.68,3.68,0,0,0-1-2.55,3,3,0,0,0-2.4-1,3.14,3.14,0,0,0-2.42,1,3.59,3.59,0,0,0-1,2.55,3.51,3.51,0,0,0,1,2.53,3.24,3.24,0,0,0,2.42,1m0,1.37a4.65,4.65,0,0,1-3.47-1.39,4.89,4.89,0,0,1-1.37-3.55A4.82,4.82,0,0,1,171.73,98a4.61,4.61,0,0,1,3.47-1.41A4.52,4.52,0,0,1,178.65,98a4.86,4.86,0,0,1,1.39,3.55,4.93,4.93,0,0,1-1.39,3.55,4.65,4.65,0,0,1-3.45,1.39" fill="undefined"/><path class="cls-4" d="M231.65,105.16a3.21,3.21,0,0,0,2.4-1,3.57,3.57,0,0,0,1-2.53,3.66,3.66,0,0,0-1-2.55,3.3,3.3,0,0,0-4.81,0,3.53,3.53,0,0,0-1,2.55,3.45,3.45,0,0,0,1,2.53,3.21,3.21,0,0,0,2.4,1m0,1.37a4.58,4.58,0,0,1-3.47-1.39,4.84,4.84,0,0,1-1.37-3.55A4.77,4.77,0,0,1,228.19,98a4.55,4.55,0,0,1,3.47-1.41A4.62,4.62,0,0,1,235.13,98a4.94,4.94,0,0,1,1.35,3.55,5,5,0,0,1-1.35,3.55,4.66,4.66,0,0,1-3.47,1.39" fill="undefined"/><polygon class="cls-4" points="242.23 104.41 244.94 96.75 245.85 96.75 248.56 104.39 251.44 96.91 252.82 96.91 248.96 106.84 248.08 106.84 245.36 99.4 242.65 106.84 241.76 106.84 237.9 96.91 239.36 96.91 242.23 104.41"/><path class="cls-4" d="M261,100.62a3,3,0,0,0-.64-1.43,2.54,2.54,0,0,0-2.11-.93,2.58,2.58,0,0,0-2,1,3.88,3.88,0,0,0-.69,1.37Zm-5.64,1.18h0a3.43,3.43,0,0,0,1.05,2.5,3.39,3.39,0,0,0,2.51,1.06,5,5,0,0,0,1.7-.3,5.35,5.35,0,0,0,1.58-.94l.35-.27v1.54l-.07,0a5.81,5.81,0,0,1-1.71,1,5.44,5.44,0,0,1-5.44-1.06,4.86,4.86,0,0,1-1.38-3.55,5.43,5.43,0,0,1,1.19-3.58,4.31,4.31,0,0,1,6.28-.08,5.26,5.26,0,0,1,1.12,3.39v.2Z" fill="undefined"/><path class="cls-4" d="M267,98.16A3,3,0,0,1,271.09,97s.07,0,.11.05v1.62c-.12-.13,0,0-.12-.07-2.27-1.27-3.64.56-4.09,1.76v6.26h-1.39V96.66H267Z" fill="currentColor"/></svg>
                    </a>
                </div>
            </div>

            {{-- Utility icons (keep your existing tools block unchanged) --}}
            <div class="col-6 col-md-3 col-lg-3">
                <div class="dbim-header__tools" role="group" aria-label="Utility links">
                      {{-- Skip to main content --}}
                        <div class="tool">
                            <a id="skipFromMenu" href="#main-content" title="Skip to main Content" aria-label="Skip to main Content">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 64 64" fill="none" aria-hidden="true" focusable="false" style="display: flex; justify-content: center;">
<path d="M57 10H14.5C13.1739 10 11.9021 10.5268 10.9645 11.4645C10.0268 12.4021 9.5 13.6739 9.5 15V25H14.5V15H57V50H14.5V40H9.5V50C9.5 51.3261 10.0268 52.5979 10.9645 53.5355C11.9021 54.4732 13.1739 55 14.5 55H57C58.3261 55 59.5979 54.4732 60.5355 53.5355C61.4732 52.5979 62 51.3261 62 50V15C62 13.6739 61.4732 12.4021 60.5355 11.4645C59.5979 10.5268 58.3261 10 57 10ZM19.5 40V35H2V30H19.5V25L29.5 32.5L19.5 40ZM52 35H34.5V30H52V35ZM52 25H34.5V20H52V25ZM44.5 45H34.5V40H44.5V45Z" fill="#162F6A"/>
</svg>
                            </a>
                        </div>

                        {{-- Language --}}
                        <div class="tool dropdown">
                            <button type="button" id="langMenuButton" data-bs-toggle="dropdown"
                                    aria-expanded="false" aria-haspopup="true"
                                    aria-label="Translate this page!" title="Translate this page!">
<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 64 64" fill="none" aria-hidden="true" focusable="false" style="display: flex; justify-content: center;">
<path d="M37.6672 9.95973V31.9997H34.4272V9.95973H31.5071V7.11973H41.8271V9.95973H37.6672ZM22.5871 6.71973C24.6671 6.71973 26.2538 7.23973 27.3471 8.27973C28.4671 9.31973 29.0271 10.6264 29.0271 12.1997C29.0271 13.3464 28.7205 14.3864 28.1071 15.3197C27.5205 16.2264 26.6405 16.9464 25.4671 17.4797C24.2938 18.0131 22.8271 18.3064 21.0671 18.3597L20.8671 15.5597C22.6805 15.5064 23.9605 15.1864 24.7071 14.5997C25.4805 14.0131 25.8671 13.2264 25.8671 12.2397C25.8671 11.2797 25.5471 10.5864 24.9071 10.1597C24.2938 9.73306 23.5738 9.51973 22.7471 9.51973C21.7605 9.51973 20.8671 9.65306 20.0671 9.91973C19.2671 10.1864 18.4138 10.5464 17.5071 10.9997L16.5071 8.23973C17.2005 7.86639 18.0538 7.51973 19.0671 7.19973C20.1071 6.87973 21.2805 6.71973 22.5871 6.71973ZM29.4671 23.2797C29.4671 24.5064 29.1871 25.5331 28.6271 26.3597C28.0671 27.1864 27.3071 27.7997 26.3471 28.1997C25.4138 28.5997 24.3471 28.7997 23.1471 28.7997C21.6271 28.7997 20.2138 28.4264 18.9071 27.6797C17.6271 26.9331 16.4005 25.7464 15.2271 24.1197C14.0805 22.4931 12.9471 20.3731 11.8271 17.7597L14.6671 16.7197C15.4405 18.6131 16.2405 20.2531 17.0671 21.6397C17.9205 22.9997 18.8271 24.0531 19.7871 24.7997C20.7471 25.5197 21.7738 25.8797 22.8671 25.8797C23.8805 25.8797 24.7071 25.6531 25.3471 25.1997C25.9871 24.7197 26.3071 23.9597 26.3071 22.9197C26.3071 21.6397 25.8671 20.5331 24.9871 19.5997C24.1071 18.6664 23.0405 17.8131 21.7871 17.0397L24.1471 16.9197L25.8671 16.5597C26.2405 16.8797 26.6538 17.2664 27.1071 17.7197C27.5605 18.1731 27.9205 18.6264 28.1871 19.0797L28.3872 19.8397C28.7338 20.3464 29.0005 20.8797 29.1871 21.4397C29.3738 21.9997 29.4671 22.6131 29.4671 23.2797ZM30.1071 17.9997C31.3871 17.9997 32.4938 17.9064 33.4272 17.7197C34.3605 17.5064 35.4538 17.1731 36.7071 16.7197V19.5997C35.5605 20.1064 34.5205 20.4397 33.5871 20.5997C32.6805 20.7597 31.6805 20.8397 30.5871 20.8397C30.1871 20.8397 29.7205 20.8131 29.1871 20.7597C28.6538 20.6797 28.1471 20.5997 27.6671 20.5197C27.2138 20.4131 26.8805 20.3197 26.6671 20.2397L24.7871 17.9997L25.0271 17.3997C25.8005 17.5864 26.6138 17.7331 27.4671 17.8397C28.3205 17.9464 29.2005 17.9997 30.1071 17.9997Z" fill="#162F6A"/>
<path d="M52.3467 58.6664L49.136 50.4158H38.5707L35.3973 58.6664H32L42.416 31.8984H45.44L55.8187 58.6664H52.3467ZM48.128 47.4291L45.1413 39.3651C45.0667 39.1659 44.9421 38.8051 44.768 38.2824C44.5939 37.7598 44.4195 37.2246 44.2453 36.6771C44.096 36.1046 43.9715 35.6691 43.872 35.3704C43.6728 36.1419 43.4613 36.9011 43.2373 37.6478C43.0381 38.3696 42.864 38.9419 42.7147 39.3651L39.6907 47.4291H48.128Z" fill="#162F6A"/>
</svg>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end notranslate" aria-labelledby="langMenuButton">
                                @foreach(config('languages.supported') as $code => $name)
                                    <li><a class="dropdown-item" href="#" data-lang="{{ $code }}" lang="{{ $code }}" hreflang="{{ $code }}">{{ $name }}</a></li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- Accessibility: icon + side panel + logic, all in one shared partial --}}
                        @include('partials.accessibility')
                </div>
            </div>

        </div>
    </div>
</div>


    {{-- ================= Row 2 : main navigation ================= --}}
    <div class="dbim-header__nav">
        <div class="container-fluid px-3 px-lg-5">
            <nav class="navbar navbar-expand-lg" aria-label="Main navigation">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNav" aria-controls="navbarNav"
                        aria-expanded="false" aria-label="Toggle navigation menu">
                    <span class="navbar-toggler-icon" aria-hidden="true"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav gap-lg-4 flex-lg-wrap">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}"
                               @if(request()->is('/')) aria-current="page" @endif>Home</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ $rtiNewRequestActive ? 'active' : '' }}"
                               href="{{ $pending ? route('pending-records-list') : url('/profile') }}"
                               @if($rtiNewRequestActive) aria-current="page" @endif>RTI New Request</a>
                        </li>

                        @if(session()->has('mobile_no'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('first-appeal-detail') ? 'active' : '' }}"
                                   href="{{ route('first-appeal-detail') }}"
                                   @if(request()->routeIs('first-appeal-detail')) aria-current="page" @endif>Submit First Appeal</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('request-type-form') ? 'active' : '' }}"
                                   href="{{ route('request-type-form') }}"
                                   @if(request()->routeIs('request-type-form')) aria-current="page" @endif>Submit Second Appeal</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('application-status') ? 'active' : '' }}"
                                   href="{{ url('/application-status') }}"
                                   @if(request()->is('application-status')) aria-current="page" @endif>Application Status</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                   href="{{ route('dashboard') }}"
                                   @if(request()->routeIs('dashboard')) aria-current="page" @endif>Payment Status</a>
                            </li>
                        @endif

                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('searchDepartment') ? 'active' : '' }}"
                               href="{{ url('/searchDepartment') }}"
                               @if(request()->is('searchDepartment')) aria-current="page" @endif>Public Authorities</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('login') ? 'active' : '' }}"
                               href="{{ url('/login') }}"
                               @if(request()->is('login')) aria-current="page" @endif>Dept. Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('contactUs') ? 'active' : '' }}"
                               href="{{ url('/contactUs') }}"
                               @if(request()->is('contactUs')) aria-current="page" @endif>Contact Us</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>

    {{-- ================= Row 3 : logged-in user ================= --}}
    @if(session()->has('mobile_no'))
        <nav class="dbim-header__user" aria-label="User account">
            <div class="container-fluid px-3 px-lg-5">
                <div class="d-flex flex-wrap justify-content-center justify-content-md-end align-items-center">
                    <a href="{{ route('profile') }}" title="My Profile">
                        <span class="visually-hidden">My Profile: </span>{{ session('username') }} | {{ session('mobile_no') }}
                    </a>
                    <a href="{{ route('sessionForm.destroy') }}">Logout</a>
                </div>
            </div>
        </nav>
    @endif
</header>

{{-- Header-only script. Theme, text size and all accessibility options
     live in partials/accessibility.blade.php --}}
<script>
    (function () {
        var root = document.documentElement;

        /* ---- Keyboard vs mouse: the search ring shows only for Tab users ---- */
        document.addEventListener('keydown', function (e) { if (e.key === 'Tab') { root.classList.add('dbim-kbd'); } });
        document.addEventListener('mousedown', function () { root.classList.remove('dbim-kbd'); });
        document.addEventListener('touchstart', function () { root.classList.remove('dbim-kbd'); }, { passive: true });

        /* ---- Sticky header height: lets in-page links (#faq-1 ...) stop BELOW the header ---- */
        var header = document.querySelector('.dbim-header');
        function setHeaderHeight() {
            root.style.setProperty('--dbim-header-h', header.offsetHeight + 'px');
        }
        setHeaderHeight();
        window.addEventListener('load', setHeaderHeight);
        window.addEventListener('resize', setHeaderHeight);
        if ('ResizeObserver' in window) { new ResizeObserver(setHeaderHeight).observe(header); }

        /* ---- Skip to navigation ---- */
        document.getElementById('skipToNav').addEventListener('click', function (e) {
            e.preventDefault();
            var first = document.querySelector('#navbarNav .nav-link');
            if (first && first.offsetParent !== null) { first.focus(); }
            else { document.querySelector('.dbim-header__nav .navbar-toggler').focus(); }
        });

        /* ---- Main navigation: arrow keys, Home, End ---- */
        var navLinks = Array.prototype.slice.call(document.querySelectorAll('#navbarNav .nav-link'));
        navLinks.forEach(function (link, i) {
            link.addEventListener('keydown', function (e) {
                var target = null;
                if (e.key === 'ArrowRight' || e.key === 'ArrowDown') { target = navLinks[(i + 1) % navLinks.length]; }
                else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') { target = navLinks[(i - 1 + navLinks.length) % navLinks.length]; }
                else if (e.key === 'Home') { target = navLinks[0]; }
                else if (e.key === 'End')  { target = navLinks[navLinks.length - 1]; }
                if (target) { e.preventDefault(); target.focus(); }
            });
        });

        /* ---- Esc closes the mobile menu and returns focus to its button ---- */
        var navToggler = document.querySelector('.dbim-header__nav .navbar-toggler');
        var navPanel   = document.getElementById('navbarNav');
        navPanel.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && navPanel.classList.contains('show') && navToggler.offsetParent !== null) {
                navToggler.click();
                navToggler.focus();
            }
        });
    })();
</script>