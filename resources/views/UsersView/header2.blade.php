{{-- ============================================================
     RTI Online - Header (DBIM / GIGW style)
     Layout reference : meity.gov.in
     Assets           : public/Compiled/css/compiled.min.css
                        public/Compiled/js/compiled.bundle.min.js
                        public/images/Emblem_of_Goa.png
     ============================================================ --}}

<!-- COMPILED CSS (Bootstrap 5.3.3 from DBIM toolkit zip) -->
<link rel="stylesheet" href="{{ asset('Compiled/css/compiled.min.css') }}">

<style>
    /* ---------- DBIM colour tokens : Light theme + Classic Blue ---------- */
    :root {
        --banner-transparency: rgba(41, 19, 108, 0.95);
        --wintergreen-dream: #162F6A;
        --powder-blue: #214AAB;
        --columbia-blue: #5279D7;
        --azureish-white: #A3BBF3;
        --azureish-white-bg: #D2DFFF;

        --deep-blue: #1D0A69;
        --bright-gray: #EBEAEA;
        --anti-flash-white: #f1f1f1;
        --rhythm: #3D4043;
        --white: #FFFFFF;
        --black: #150202;
        --black-pure: #000000;
        --profile-border-color: #D5CBBD;
        --profile-card-secondary: #DBEBEB;

        --hdr-nav-bg: var(--wintergreen-dream);
        --hdr-nav-text: var(--white);
        --hdr-nav-hover: var(--powder-blue);
        --hdr-nav-active: var(--columbia-blue);
        --hdr-topbar-bg: var(--anti-flash-white);
        --hdr-topbar-text: var(--rhythm);
        --hdr-brand-bg: var(--white);
        --hdr-brand-text: var(--deep-blue);
        --hdr-border: var(--azureish-white);
        --hdr-user-bg: var(--azureish-white-bg);
        --hdr-user-text: var(--wintergreen-dream);
        --hdr-focus: #FFBF47;
    }

    /* ---------- DBIM Dark (high contrast) theme ---------- */
    [data-theme="dark"] {
        --banner-transparency: rgba(38, 24, 0, 0.95);
        --wintergreen-dream: #0B0C0E;
        --powder-blue: #0B0C0E;
        --columbia-blue: #0B0C0E;
        --azureish-white: #23232a;
        --azureish-white-bg: #bcb9c6;

        --deep-blue: #ffff00;
        --bright-gray: #EBEAEA;
        --anti-flash-white: #ffff00;
        --rhythm: #ffff00;
        --white: #0B0C0E;
        --black: #ffff00;
        --black-pure: #ffff00;
        --profile-border-color: #23232a;
        --profile-card-secondary: #23232a;

        --hdr-nav-bg: #0B0C0E;
        --hdr-nav-text: #ffff00;
        --hdr-nav-hover: #23232a;
        --hdr-nav-active: #23232a;
        --hdr-topbar-bg: #23232a;
        --hdr-topbar-text: #ffff00;
        --hdr-brand-bg: #0B0C0E;
        --hdr-brand-text: #ffff00;
        --hdr-border: #ffff00;
        --hdr-user-bg: #23232a;
        --hdr-user-text: #ffff00;
        --hdr-focus: #ffff00;
    }

    /* ---------- Accessibility (GIGW) ---------- */
    .dbim-skip-link {
        position: absolute; left: -9999px; top: 0; z-index: 10000;
        background: var(--hdr-focus); color: #000; padding: 8px 16px; font-weight: 600;
    }
    .dbim-skip-link:focus { left: 0; }

    .dbim-header a:focus-visible,
    .dbim-header button:focus-visible {
        outline: 3px solid var(--hdr-focus);
        outline-offset: 2px;
    }

    /* ---------- Top utility bar ---------- */
    .dbim-topbar {
        background: var(--hdr-topbar-bg);
        color: var(--hdr-topbar-text);
        font-size: .8125rem;
        border-bottom: 1px solid var(--hdr-border);
    }
    .dbim-topbar a,
    .dbim-topbar button {
        color: var(--hdr-topbar-text);
        text-decoration: none;
        background: none;
        border: 1px solid transparent;
        padding: 2px 8px;
        line-height: 1.5;
    }
    .dbim-topbar a:hover,
    .dbim-topbar button:hover { text-decoration: underline; }
    .dbim-topbar .dbim-sep { border-left: 1px solid var(--hdr-border); height: 18px; margin: 0 6px; }
    .dbim-topbar .dbim-fs button { font-weight: 700; border-color: var(--hdr-border); margin: 0 1px; min-width: 30px; }

    /* ---------- Brand row ---------- */
    .dbim-brand {
        background: var(--hdr-brand-bg);
        color: var(--hdr-brand-text);
        padding: 14px 0;
    }
    .dbim-brand img { height: 72px; width: auto; }
    .dbim-brand .dbim-title { color: var(--hdr-brand-text); font-weight: 700; margin: 0; line-height: 1.2; }
    .dbim-brand .dbim-subtitle { margin: 2px 0 0; font-size: .875rem; color: var(--hdr-topbar-text); }
    .dbim-brand .dbim-version {
        display: inline-block; margin-left: 6px; padding: 1px 8px; font-size: .75rem;
        border: 1px solid var(--hdr-border); border-radius: 20px; vertical-align: middle;
    }
    .dbim-brand .dbim-pa-link {
        display: inline-block; padding: 8px 18px; font-weight: 600; text-decoration: none;
        color: var(--hdr-nav-text); background: var(--hdr-nav-bg);
        border: 2px solid var(--hdr-nav-bg); border-radius: 4px;
    }
    .dbim-brand .dbim-pa-link:hover { background: var(--hdr-nav-hover); }

    /* ---------- Main navigation ---------- */
    .dbim-nav {
        background: var(--hdr-nav-bg);
        border-top: 3px solid var(--hdr-border);
    }
    .dbim-nav .navbar { padding: 0; }
    .dbim-nav .nav-link {
        color: var(--hdr-nav-text);
        font-weight: 600;
        padding: 14px 16px;
        border-bottom: 4px solid transparent;
    }
    .dbim-nav .nav-link:hover { background: var(--hdr-nav-hover); color: var(--hdr-nav-text); }
    .dbim-nav .nav-link.active {
        background: var(--hdr-nav-active);
        border-bottom-color: var(--hdr-focus);
        color: var(--hdr-nav-text);
    }
    .dbim-nav .navbar-toggler {
        border-color: var(--hdr-nav-text); margin: 8px 0;
    }
    .dbim-nav .navbar-toggler-icon { filter: invert(1); }
    [data-theme="dark"] .dbim-nav .navbar-toggler-icon { filter: none; }
    @media (max-width: 991.98px) {
        .dbim-nav .nav-link { border-bottom: 1px solid var(--hdr-border); }
    }

    /* ---------- Logged-in user strip ---------- */
    .dbim-user {
        background: var(--hdr-user-bg);
        color: var(--hdr-user-text);
        border-bottom: 1px solid var(--hdr-border);
        font-size: .9375rem;
    }
    .dbim-user a {
        color: var(--hdr-user-text); font-weight: 700; text-decoration: none; padding: 8px 12px; display: inline-block;
    }
    .dbim-user a:hover { text-decoration: underline; }
</style>

<header class="dbim-header" role="banner">

    <a class="dbim-skip-link" href="#main-content">Skip to main content</a>

    {{-- ===== Top utility bar ===== --}}
    <div class="dbim-topbar">
        <div class="container-fluid px-3 px-lg-5">
            <div class="d-flex flex-wrap justify-content-between align-items-center py-1">
                <span class="d-none d-md-inline">Government of Goa</span>

                <div class="d-flex flex-wrap align-items-center ms-md-auto">
                    <a href="#main-content">Skip to Main Content</a>
                    <span class="dbim-sep" aria-hidden="true"></span>

                    <div class="dbim-fs d-flex align-items-center" role="group" aria-label="Text size">
                        <button type="button" id="fontDecrease" aria-label="Decrease text size" title="Decrease text size">A-</button>
                        <button type="button" id="fontReset" aria-label="Normal text size" title="Normal text size">A</button>
                        <button type="button" id="fontIncrease" aria-label="Increase text size" title="Increase text size">A+</button>
                    </div>
                    <span class="dbim-sep" aria-hidden="true"></span>

                    <button type="button" id="themeToggle" aria-pressed="false" title="Switch to high contrast theme">
                        <span id="themeToggleLabel">High Contrast</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Brand row ===== --}}
    <div class="dbim-brand">
        <div class="container-fluid px-3 px-lg-5">
            <div class="row align-items-center g-3">
                <div class="col-12 col-md-8 d-flex align-items-center">
                    <a href="/" class="me-3 flex-shrink-0">
                        <img src="{{ asset('images/Emblem_of_Goa.png') }}" alt="Emblem of Goa - Government of Goa">
                    </a>
                    <div>
                        <h1 class="dbim-title h3">
                            RTI Online
                            <span class="dbim-version">Version 1.0</span>
                        </h1>
                        <p class="dbim-subtitle">
                            An Initiative of Department of Information &amp; Publicity, Govt. of Goa
                        </p>
                    </div>
                </div>
                <div class="col-12 col-md-4 text-md-end">
                    <a class="dbim-pa-link" href="/searchDepartment">Public Authorities Available</a>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Main navigation ===== --}}
    <div class="dbim-nav">
        <div class="container-fluid px-3 px-lg-5">
            <nav class="navbar navbar-expand-lg" aria-label="Main navigation">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNav" aria-controls="navbarNav"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/"
                               @if(request()->is('/')) aria-current="page" @endif>Home</a>
                        </li>

                        <li class="nav-item">
                            @if($hasPendingRecords)
                                <a class="nav-link {{ request()->routeIs('pending-records-list') ? 'active' : '' }}"
                                   href="{{ route('pending-records-list') }}">RTI New Request</a>
                            @else
                                <a class="nav-link {{ request()->is('profile') ? 'active' : '' }}"
                                   href="{{ url('/profile') }}">RTI New Request</a>
                            @endif
                        </li>

                        @if(session()->has('mobile_no'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('first-appeal-detail') ? 'active' : '' }}"
                                   href="{{ route('first-appeal-detail') }}">Submit First Appeal</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('request-type-form') ? 'active' : '' }}"
                                   href="{{ route('request-type-form') }}">Submit Second Appeal</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('application-status') ? 'active' : '' }}"
                                   href="/application-status">Application Status</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                   href="{{ route('dashboard') }}">Payment Status</a>
                            </li>
                        @endif

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}"
                               href="{{ route('login') }}">Dept. Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('contactUs') ? 'active' : '' }}"
                               href="/contactUs">Contact Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('FAQ') ? 'active' : '' }}"
                               href="/FAQ">FAQ</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>

    {{-- ===== Logged-in user strip ===== --}}
    @if(session()->has('mobile_no'))
        <div class="dbim-user">
            <div class="container-fluid px-3 px-lg-5">
                <div class="d-flex flex-wrap justify-content-center justify-content-md-end align-items-center">
                    <a href="{{ route('profile') }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="My Profile">
                        {{ session('username') }} | {{ session('mobile_no') }}
                    </a>
                    <a href="{{ route('sessionForm.destroy') }}">Logout</a>
                </div>
            </div>
        </div>
    @endif
</header>

<!-- COMPILED JS (Bootstrap bundle from DBIM toolkit zip) -->
<script src="{{ asset('Compiled/js/compiled.bundle.min.js') }}"></script>
<script>
    (function () {
        var root = document.documentElement;
        var store = {
            get: function (k) { try { return localStorage.getItem(k); } catch (e) { return null; } },
            set: function (k, v) { try { localStorage.setItem(k, v); } catch (e) {} }
        };

        /* ---- Theme (light / DBIM dark high-contrast) ---- */
        var toggle = document.getElementById('themeToggle');
        var label  = document.getElementById('themeToggleLabel');

        function applyTheme(theme) {
            var dark = theme === 'dark';
            if (dark) { root.setAttribute('data-theme', 'dark'); }
            else      { root.removeAttribute('data-theme'); }
            root.setAttribute('data-bs-theme', dark ? 'dark' : 'light');
            toggle.setAttribute('aria-pressed', dark ? 'true' : 'false');
            label.textContent = dark ? 'Standard Contrast' : 'High Contrast';
            store.set('dbim-theme', theme);
        }
        applyTheme(store.get('dbim-theme') === 'dark' ? 'dark' : 'light');
        toggle.addEventListener('click', function () {
            applyTheme(root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
        });

        /* ---- Text size (A- / A / A+) ---- */
        var size = parseInt(store.get('dbim-font-size') || '100', 10);
        function applySize(s) {
            size = Math.min(130, Math.max(80, s));
            root.style.fontSize = size + '%';
            store.set('dbim-font-size', size);
        }
        applySize(size);
        document.getElementById('fontIncrease').addEventListener('click', function () { applySize(size + 10); });
        document.getElementById('fontDecrease').addEventListener('click', function () { applySize(size - 10); });
        document.getElementById('fontReset').addEventListener('click', function () { applySize(100); });

        /* ---- Tooltips (replaces the old custom cursor tooltip) ---- */
        if (window.bootstrap) {
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
                new bootstrap.Tooltip(el);
            });
        }
    })();
</script>