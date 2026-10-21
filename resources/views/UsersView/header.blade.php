{{-- ============================================================
     RTI Online - common header (DBIM / GIGW 3.0 accessible)
     Included from layouts/app.blade.php
     Styles : public/css/dbim.css

     Keyboard support
       Tab / Shift+Tab ... move between controls
       1st Tab stop ...... Skip to main content
       2nd Tab stop ...... Skip to navigation
       Nav links ......... Left/Right (or Up/Down) arrows, Home, End
       Language menu ..... Enter/Space open, Up/Down arrows move, Esc closes
       Accessibility ..... Enter/Space opens the side panel, Esc closes it
                           Arrow keys / Home / End move between its buttons
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

    {{-- ================= Row 1 : brand | search | tools ================= --}}
    <div class="dbim-header__top">
        <div class="container-fluid px-3 px-lg-5">
            <div class="row align-items-center g-3">

                {{-- Emblem + title (plain block, not a link) --}}
                <div class="col-12 col-lg-5">
                    <div class="dbim-header__brand d-flex align-items-center justify-content-start gap-5">
                        <img src="{{ asset('images/Emblem_of_Goa.png') }}" alt="Emblem of Goa" width="57" height="100">
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
                <div class="col-12 col-md-7 col-lg-4">
                    <form class="dbim-header__search" role="search" aria-label="Site search"
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

                {{-- Utility icons --}}
                <div class="col-12 col-md-5 col-lg-3">
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

                        {{-- Accessibility (opens the side panel defined below the header) --}}
                        <div class="tool">
                            <button type="button" id="a11yMenuButton" data-bs-toggle="offcanvas"
                                    data-bs-target="#a11yPanel" aria-controls="a11yPanel"
                                    aria-haspopup="dialog"
                                    aria-label="Accessibility panel" title="Accessibility panel">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 64 64" aria-hidden="true" focusable="false" fill="none" style="display: flex; justify-content: center;">
                                    <g id="a11yIconGlyph">
                                        <path d="M24.2921 29.7656C21.4604 29.7656 18.7402 29.7704 16.0199 29.7608C15.4887 29.7608 14.9503 29.7419 14.4286 29.6565C12.2182 29.296 10.6482 27.4043 10.6672 25.1638C10.6861 22.9779 12.3652 21.041 14.5305 20.7305C14.9977 20.6641 15.4744 20.6451 15.9488 20.6451C26.6163 20.6403 37.2838 20.6356 47.9515 20.6522C48.6534 20.6522 49.3862 20.7091 50.0505 20.9154C52.0307 21.5294 53.2593 23.4166 53.1265 25.491C52.9982 27.4968 51.4521 29.2771 49.4526 29.6256C48.7294 29.7512 47.9798 29.7536 47.2425 29.7584C44.7025 29.7725 42.1601 29.7632 39.3638 29.7632C39.5203 32.2075 39.3779 34.6163 39.8785 36.8827C41.0523 42.1933 42.5465 47.4328 43.8817 52.7077C44.5102 55.1947 43.1987 57.6104 40.8555 58.4072C38.2041 59.308 35.4126 57.7765 34.7414 54.9837C33.8307 51.188 32.9817 47.3757 32.1043 43.5731C32.0686 43.4165 32.0166 43.2648 31.7985 43.1059C31.3595 45.0168 30.9185 46.9301 30.4774 48.8408C30.0126 50.8584 29.5785 52.8832 29.0758 54.8912C28.4046 57.5632 25.9072 59.0613 23.3008 58.4141C20.7893 57.7883 19.2785 55.2325 19.9402 52.6128C21.1403 47.8523 22.2834 43.0752 23.6376 38.3573C24.4439 35.548 24.24 32.7384 24.2921 29.7656ZM31.9929 23.6868C26.6875 23.6868 21.3822 23.6868 16.0745 23.6868C15.7187 23.6868 15.3582 23.6797 15.012 23.7366C14.2341 23.8599 13.7218 24.4573 13.7147 25.1946C13.7076 25.9462 14.1914 26.5318 14.9788 26.6717C15.2966 26.7285 15.6286 26.7237 15.9535 26.7237C18.7093 26.7285 21.4675 26.7096 24.2234 26.7333C25.8219 26.7475 27.195 27.7456 27.2473 29.1704C27.335 31.6219 27.5222 34.1349 27.0977 36.52C26.429 40.2659 25.2906 43.9285 24.3467 47.6272C23.8629 49.5189 23.3483 51.4013 22.881 53.2981C22.613 54.3837 23.1443 55.2563 24.138 55.4885C25.0582 55.7043 25.8859 55.1379 26.1728 54.0923C26.1966 54.0069 26.2037 53.9168 26.2227 53.8315C27.2899 49.1587 28.3523 44.4859 29.4385 39.8176C29.5643 39.2795 29.7374 38.7128 30.0433 38.2672C30.5699 37.5037 31.3761 37.1933 32.3035 37.3664C33.3779 37.5677 34.0062 38.2435 34.2505 39.3032C35.3534 44.1184 36.4633 48.9333 37.571 53.7485C37.5899 53.8339 37.5945 53.9264 37.6158 54.0117C37.8958 55.1211 38.7259 55.7187 39.6697 55.4957C40.6942 55.2539 41.1947 54.3624 40.9054 53.2173C40.1987 50.4365 39.4777 47.6579 38.7521 44.8816C37.4878 40.0477 36.0009 35.2659 36.4419 30.1448C36.6363 27.8712 37.623 26.7285 39.9307 26.7213C42.5678 26.7144 45.2075 26.7213 47.8449 26.7192C48.1411 26.7192 48.4401 26.7237 48.7294 26.6741C49.5974 26.527 50.1334 25.8798 50.0859 25.0785C50.0385 24.2985 49.4907 23.7722 48.6227 23.6963C48.3286 23.6702 48.0297 23.6797 47.7334 23.6797C42.4873 23.6797 37.2411 23.6797 31.9929 23.6797V23.6868Z" fill="#162F6A"/>
                                        <path d="M31.6523 17.6787C28.1841 17.6715 25.4626 14.9253 25.4796 11.4504C25.4965 8.07201 28.3001 5.3138 31.6982 5.33311C35.0867 5.35482 37.8323 8.13473 37.8251 11.5397C37.8179 14.9519 35.0699 17.6859 31.6523 17.6787ZM34.7339 11.4938C34.7219 9.7974 33.2958 8.4002 31.6041 8.42433C29.9435 8.44844 28.5731 9.83841 28.5707 11.4962C28.5659 13.2313 29.9195 14.5826 31.6547 14.5802C33.395 14.5778 34.7483 13.2216 34.7339 11.4938Z" fill="#162F6A"/>
                                    </g>
                                </svg>
                            </button>
                        </div>

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

{{-- ================= Accessibility Controls side panel =================
     No backdrop + page scroll allowed, so changes can be seen live.
     Esc or the X button closes it and returns focus to the header icon. --}}
<div class="offcanvas offcanvas-end dbim-a11y" tabindex="-1" id="a11yPanel"
     aria-labelledby="a11yPanelTitle" data-bs-backdrop="false" data-bs-scroll="true">

    <div class="offcanvas-header">
        <h2 class="offcanvas-title d-flex align-items-center gap-2" id="a11yPanelTitle">
            <svg viewBox="0 0 64 64" fill="none" aria-hidden="true" focusable="false"><use href="#a11yIconGlyph"></use></svg>
            Accessibility Controls
        </h2>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close accessibility panel"></button>
    </div>

    <div class="offcanvas-body">

        {{-- Reset buttons --}}
        <div class="d-flex flex-wrap gap-2 mb-3">
            <button type="button" class="a11y-btn a11y-reset" id="a11yResetFilter">Reset Filter</button>
            <button type="button" class="a11y-btn a11y-reset" id="fontReset">Resize Text</button>
        </div>

        {{-- Option buttons --}}
        <div class="row g-2" role="group" aria-label="Accessibility options">
            <div class="col-6">
                <button type="button" class="a11y-btn" id="themeToggle" aria-pressed="false">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="12" cy="12" r="3"/><path d="M12 9a3 3 0 0 0 0 6z" fill="currentColor"/></svg>
                    <span>Dark Contrast</span>
                </button>
            </div>
            <div class="col-6">
                <button type="button" class="a11y-btn" id="a11yInvert" aria-pressed="false">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="9"/><path d="M12 3a9 9 0 0 1 0 18z" fill="currentColor"/></svg>
                    <span>Invert</span>
                </button>
            </div>
            <div class="col-6">
                <button type="button" class="a11y-btn" id="a11ySaturation" aria-pressed="false">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M12 3s6 6.5 6 11a6 6 0 0 1-12 0c0-4.5 6-11 6-11z"/><path d="M12 3s6 6.5 6 11a6 6 0 0 1-6 6z" fill="currentColor"/></svg>
                    <span>Saturation</span>
                </button>
            </div>
            <div class="col-6">
                <button type="button" class="a11y-btn" id="fontIncrease">
                    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><text x="12" y="17" text-anchor="middle" font-size="14" font-weight="700" fill="currentColor">A+</text></svg>
                    <span>Text Size Increase</span>
                </button>
            </div>
            <div class="col-6">
                <button type="button" class="a11y-btn" id="fontDecrease">
                    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><text x="12" y="17" text-anchor="middle" font-size="14" font-weight="700" fill="currentColor">A-</text></svg>
                    <span>Text Size Decrease</span>
                </button>
            </div>
            <div class="col-6">
                <button type="button" class="a11y-btn" id="a11yLinks" aria-pressed="false">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M10 13a5 5 0 0 0 7 0l3-3a5 5 0 0 0-7-7l-1 1"/><path d="M14 11a5 5 0 0 0-7 0l-3 3a5 5 0 0 0 7 7l1-1"/></svg>
                    <span>Highlight Links</span>
                </button>
            </div>
            <div class="col-6">
                <button type="button" class="a11y-btn" id="a11yImages" aria-pressed="false">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="1.5"/><path d="M21 15l-5-5L5 21"/><line x1="3" y1="3" x2="21" y2="21"/></svg>
                    <span>Hide Images</span>
                </button>
            </div>
            <div class="col-6">
                <button type="button" class="a11y-btn" id="a11yCursor" aria-pressed="false">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 3l14 7-6 2-2 6z"/></svg>
                    <span id="a11yCursorLabel">Large Cursor</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        var root   = document.documentElement;
        var status = document.getElementById('dbimStatus');
        var store  = {
            get: function (k) { try { return localStorage.getItem(k); } catch (e) { return null; } },
            set: function (k, v) { try { localStorage.setItem(k, v); } catch (e) {} }
        };
        function announce(msg) {
            status.textContent = '';
            setTimeout(function () { status.textContent = msg; }, 50);
        }

        /* ---- Sticky header height: lets in-page links (#faq-1 ...) stop BELOW the header ---- */
        var header = document.querySelector('.dbim-header');
        function setHeaderHeight() {
            root.style.setProperty('--dbim-header-h', header.offsetHeight + 'px');
        }
        setHeaderHeight();
        window.addEventListener('load', setHeaderHeight);
        window.addEventListener('resize', setHeaderHeight);
        if ('ResizeObserver' in window) { new ResizeObserver(setHeaderHeight).observe(header); }

        /* ---- Theme: standard / DBIM dark contrast ---- */
        var toggle = document.getElementById('themeToggle');

        function applyTheme(theme, speak) {
            var dark = theme === 'dark';
            if (dark) { root.setAttribute('data-theme', 'dark'); }
            else      { root.removeAttribute('data-theme'); }
            root.setAttribute('data-bs-theme', dark ? 'dark' : 'light');
            toggle.setAttribute('aria-pressed', dark ? 'true' : 'false');
            store.set('dbim-theme', theme);
            if (speak) { announce(dark ? 'Dark contrast on' : 'Dark contrast off'); }
        }
        applyTheme(store.get('dbim-theme') === 'dark' ? 'dark' : 'light', false);
        toggle.addEventListener('click', function () {
            applyTheme(root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark', true);
        });

        /* ---- Text size: A+ / A- / Resize Text (reset) ---- */
        var size = parseInt(store.get('dbim-font-size') || '100', 10);
        function applySize(s, speak) {
            size = Math.min(130, Math.max(80, s));
            root.style.fontSize = size + '%';
            store.set('dbim-font-size', size);
            if (speak) { announce('Text size ' + size + ' percent'); }
        }
        applySize(size, false);

        var fDec = document.getElementById('fontDecrease');
        var fRes = document.getElementById('fontReset');
        var fInc = document.getElementById('fontIncrease');
        fInc.addEventListener('click', function () { applySize(size + 10, true); });
        fDec.addEventListener('click', function () { applySize(size - 10, true); });
        fRes.addEventListener('click', function () { applySize(100, true); });

        /* ---- Invert, saturation, highlight links, hide images, cursor ---- */
        var opts = { invert: false, sat: 0, links: false, images: false, cursor: false };
        try {
            var saved = JSON.parse(store.get('dbim-a11y-opts') || '{}');
            Object.keys(opts).forEach(function (k) { if (k in saved) { opts[k] = saved[k]; } });
        } catch (e) {}

        var satFilter = ['', 'saturate(0.5)', 'saturate(2)', 'grayscale(1)'];
        var satText   = ['Normal saturation', 'Low saturation', 'High saturation', 'Monochrome'];
        var elResetFilter = document.getElementById('a11yResetFilter');
        var elInvert = document.getElementById('a11yInvert');
        var elSat    = document.getElementById('a11ySaturation');
        var elLinks  = document.getElementById('a11yLinks');
        var elImages = document.getElementById('a11yImages');
        var elCursor = document.getElementById('a11yCursor');
        var elCursorLabel = document.getElementById('a11yCursorLabel');

        function pressed(el, on) { el.setAttribute('aria-pressed', on ? 'true' : 'false'); }

        function applyOpts(msg) {
            var f = [];
            if (opts.invert) { f.push('invert(1)'); }
            if (satFilter[opts.sat]) { f.push(satFilter[opts.sat]); }
            root.style.filter = f.join(' ');
            root.classList.toggle('dbim-highlight-links', opts.links);
            root.classList.toggle('dbim-hide-images', opts.images);
            root.classList.toggle('dbim-big-cursor', opts.cursor);
            pressed(elInvert, opts.invert);
            pressed(elSat, opts.sat > 0);
            pressed(elLinks, opts.links);
            pressed(elImages, opts.images);
            pressed(elCursor, opts.cursor);
            elCursorLabel.textContent = opts.cursor ? 'Default Cursor' : 'Large Cursor';
            store.set('dbim-a11y-opts', JSON.stringify(opts));
            if (msg) { announce(msg); }
        }
        applyOpts(null);

        elInvert.addEventListener('click', function () { opts.invert = !opts.invert; applyOpts(opts.invert ? 'Invert colours on' : 'Invert colours off'); });
        elSat.addEventListener('click',    function () { opts.sat = (opts.sat + 1) % satFilter.length; applyOpts(satText[opts.sat]); });
        elLinks.addEventListener('click',  function () { opts.links = !opts.links; applyOpts(opts.links ? 'Highlight links on' : 'Highlight links off'); });
        elImages.addEventListener('click', function () { opts.images = !opts.images; applyOpts(opts.images ? 'Images hidden' : 'Images shown'); });
        elCursor.addEventListener('click', function () { opts.cursor = !opts.cursor; applyOpts(opts.cursor ? 'Large cursor on' : 'Default cursor'); });

        /* Reset Filter: dark contrast + invert + saturation back to normal */
        elResetFilter.addEventListener('click', function () {
            applyTheme('light', false);
            opts.invert = false;
            opts.sat = 0;
            applyOpts('Filters reset');
        });

        /* ---- Keyboard inside the panel: arrows / Home / End ----
             Row 1 = Reset Filter, Resize Text. Then a 2-column grid. */
        var panelBtns = [elResetFilter, fRes, toggle, elInvert, elSat, fInc, fDec, elLinks, elImages, elCursor];
        panelBtns.forEach(function (btn, i) {
            btn.addEventListener('keydown', function (e) {
                var last = panelBtns.length - 1, n = null;
                if (e.key === 'ArrowRight')     { n = Math.min(i + 1, last); }
                else if (e.key === 'ArrowLeft') { n = Math.max(i - 1, 0); }
                else if (e.key === 'ArrowDown') { n = i <= 1 ? i + 2 : Math.min(i + 2, last); }
                else if (e.key === 'ArrowUp')   { n = i <= 1 ? i : i - 2; }
                else if (e.key === 'Home')      { n = 0; }
                else if (e.key === 'End')       { n = last; }
                if (n !== null) { e.preventDefault(); panelBtns[n].focus(); }
            });
        });
        /* Move focus into the panel when it opens */
        document.getElementById('a11yPanel').addEventListener('shown.bs.offcanvas', function () {
            elResetFilter.focus();
        });

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