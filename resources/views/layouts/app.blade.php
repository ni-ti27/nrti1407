<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    {{-- Do NOT add maximum-scale / user-scalable=no: users must be able to zoom (WCAG 1.4.4) --}}
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Each page should set its own title:  @section('title', 'Page name - RTI Online') --}}
    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Fonts : Noto Sans (Regular, Medium, Semi Bold, Bold) + Devanagari script -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=noto-sans:400,500,600,700|noto-sans-devanagari:400,600,700" rel="stylesheet">

    <!-- DBIM Toolkit COMPILED CSS (Bootstrap) - must load before our own styles -->
    <link rel="stylesheet" href="{{ asset('Compiled/css/compiled.min.css') }}">

    <!-- DBIM theme, typography, header styles (plain CSS, loaded after toolkit CSS) -->
    <link rel="stylesheet" href="{{ asset('css/dbim.css') }}">

    <!-- Apply saved theme / text size before first paint (avoids flicker) -->
    <script>
        (function () {
            try {
                var r = document.documentElement;
                if (localStorage.getItem('dbim-theme') === 'dark') {
                    r.setAttribute('data-theme', 'dark');
                    r.setAttribute('data-bs-theme', 'dark');
                }
                var s = localStorage.getItem('dbim-font-size');
                if (s) { r.style.fontSize = s + '%'; }
            } catch (e) {}
        })();
    </script>

    @stack('styles')
</head>
@php
    // Officer logins set session('role') (the dashboard already relies on it).
    // Change this condition if your applicant/officer check is different,
    // e.g. auth()->guard('officer')->check().
    $isOfficer = session()->has('role');
@endphp

<body>
    {{-- Shown only if JavaScript is switched off (menus and text-size tools need it) --}}
    <noscript>
        <div class="alert alert-warning rounded-0 mb-0 text-center" role="alert">
            JavaScript is turned off. Menus and accessibility options may not work.
        </div>
    </noscript>
    <div id="app">

        {{-- Common header (DBIM / GIGW): contains the skip links, banner and main navigation --}}
        @php($dbimAssetsLoaded = true)
        @if($isOfficer)
            @include('OfficerView.Header')
        @else
            @include('UsersView.header')
        @endif

        {{-- Target of "Skip to main content". tabindex=-1 lets it receive focus from the skip link. --}}
        <main id="main-content" tabindex="-1" class="py-4">
            @yield('content')
        </main>

    </div>

   @include('UsersView.footer')

    <script src="{{ asset('Compiled/js/compiled.bundle.min.js') }}"></script>

@include('partials.tanslate')
 @stack('scripts')
</body>
</html>
