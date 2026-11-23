{{-- ============================================================
     Home Page (DBIM / GIGW 3.0), Blue colour group
     All styles: section 87 of dbim.css (classes start with .dbim-home)
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Home | RTI Online, Government of Goa')

@section('content')
@php
    $links = [
        'rti'    => '#',   // new RTI application
        'first'  => '#',   // first appeal
        'second' => '#',   // second appeal
    ];
@endphp

<div class="dbim-home">

    {{-- 0. ANNOUNCEMENTS (grey bar, moving text with Pause / Play) --}}
    <section class="dbim-home-notice" id="dbimTicker" aria-label="Announcements">
        <div class="dbim-home-notice__inner">
            <strong class="dbim-home-notice__label">
                Important
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor"
                     stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                     aria-hidden="true" focusable="false">
                    <path d="M11 5 6 9H3v6h3l5 4V5z"/><path d="M15.5 8.5a5 5 0 0 1 0 7M18.5 6a9 9 0 0 1 0 12"/>
                </svg>
            </strong>
            <div class="dbim-home-notice__viewport">
                <p class="dbim-home-notice__text" id="dbimTickerText">
                    Please file RTI application/first appeal through this portal for Departments/Public
                    Authorities under the Goa State Government only. If filed wrongly, the application
                    would be returned, without a refund of amount.
                </p>
            </div>
            <button type="button" class="dbim-home-notice__btn" id="dbimTickerToggle"
                    aria-label="Pause scrolling announcement" hidden>
                <svg class="icon-pause" viewBox="0 0 24 24" width="20" height="20" fill="currentColor"
                     aria-hidden="true" focusable="false">
                    <rect x="6" y="5" width="4" height="14" rx="1"/><rect x="14" y="5" width="4" height="14" rx="1"/>
                </svg>
                <svg class="icon-play" viewBox="0 0 24 24" width="20" height="20" fill="currentColor"
                     aria-hidden="true" focusable="false">
                    <path d="M8 5v14l11-7z"/>
                </svg>
            </button>
        </div>
    </section>

    {{-- 1. ABOUT (grey band, round photo on the left) --}}
    <section class="dbim-home-hero" aria-labelledby="homeHeading">
        <div class="dbim-home-wrap">
            <div class="dbim-home-hero__grid">

                <figure class="dbim-home-person">
                    <img src="{{ asset('images/CM_image.png') }}"
                         alt="Dr. Pramod Sawant, Chief Minister of Goa"
                         class="dbim-home-person__photo" width="220" height="220">
                    <figcaption class="dbim-home-person__caption">
                        <span class="dbim-home-person__name">Dr. Pramod Sawant</span>
                        <span>Chief Minister</span>
                        <span>Government of Goa</span>
                    </figcaption>
                </figure>

                <div class="dbim-home-hero__body">
                    <h1 id="homeHeading" class="dbim-home-title dbim-home-title--lg">
                        <svg class="dbim-home-title__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true" focusable="false">
                            <circle cx="12" cy="4.5" r="2.5"/><path d="M12 7v8M5 15v-2.5a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1V15"/>
                            <rect x="2.5" y="15" width="5" height="5" rx="1"/><rect x="9.5" y="15" width="5" height="5" rx="1"/>
                            <rect x="16.5" y="15" width="5" height="5" rx="1"/>
                        </svg>
                        About this Portal
                    </h1>

                    <div class="dbim-home-lead">
                        <p>This is a portal to file RTI application / first appeal / second appeal online
                           along with an eChallan payment gateway.</p>
                        <p>Through this portal, RTI application / first appeal / second appeal can be filed by
                           Indian Citizens for all Departments / Offices / Sub-Offices Public Authorities of
                           Goa State Government.</p>
                    </div>

                    <a href="{{ $links['rti'] }}" class="dbim-home-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                            <path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5M12 12v6M9 15h6"/>
                        </svg>
                        File RTI application<span class="dbim-sr-only"> online</span>
                    </a>
                </div>

            </div>
        </div>
    </section>

    {{-- 2. PAYMENT OPTIONS + INSTRUCTIONS (light blue band, white panels) --}}
    <section class="dbim-home-info" aria-label="Payment options and instructions">
        <div class="dbim-home-wrap">
            <div class="dbim-home-info__grid">
                <div class="dbim-home-panel">
                    <h2 class="dbim-home-title">
                        <svg class="dbim-home-title__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true" focusable="false">
                            <rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 10h18M7 15h4"/>
                        </svg>
                        Payment options
                    </h2>
                    <ul class="dbim-home-chips">
                        <li>Internet banking</li>
                        <li>Debit / credit cards (Master, Visa)</li>
                        <li>UPI</li>
                    </ul>
                </div>
                <div class="dbim-home-panel">
                    <h2 class="dbim-home-title">
                        <svg class="dbim-home-title__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true" focusable="false">
                            <circle cx="12" cy="12" r="9"/><path d="M12 11v6M12 7.5h.01"/>
                        </svg>
                        Instructions
                    </h2>
                    <p>Please read the instructions carefully while submitting your RTI application,
                       first appeal or second appeal.</p>
                </div>
            </div>
        </div>
    </section>

        {{-- 3. ONLINE SERVICES (grey band, one white panel, one row per service) --}}
    <section class="dbim-home-section dbim-home-services" aria-labelledby="servicesHeading">
        <div class="dbim-home-wrap">
            <h2 id="servicesHeading" class="dbim-home-title">
                <svg class="dbim-home-title__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"
                     aria-hidden="true" focusable="false">
                    <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                </svg>
                Online Services
            </h2>

            <ul class="dbim-home-list">

                <li class="dbim-home-row">
                    <span class="dbim-home-row__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round" focusable="false">
                            <path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/>
                            <path d="M14 3v5h5M9 13h6M9 17h6"/>
                        </svg>
                    </span>
                    <div class="dbim-home-row__body">
                        <h3 class="dbim-home-row__title">RTI Application</h3>
                        <p class="dbim-home-row__text">Submit a new RTI application to the Public Authority of
                            your choice. Fee payment is online, for non-BPL applicants only.</p>
                    </div>
                    <a href="{{ $links['rti'] }}" class="dbim-home-btn">
                        File RTI application<span class="dbim-sr-only"> online</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                            <path d="m9 6 6 6-6 6"/>
                        </svg>
                    </a>
                </li>

                <li class="dbim-home-row">
                    <span class="dbim-home-row__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round" focusable="false">
                            <path d="M4 12a8 8 0 0 1 14-5l2 2M20 4v5h-5M20 12a8 8 0 0 1-14 5l-2-2M4 20v-5h5"/>
                        </svg>
                    </span>
                    <div class="dbim-home-row__body">
                        <h3 class="dbim-home-row__title">First Appeal</h3>
                        <p class="dbim-home-row__text">Not satisfied with the reply, or no reply received? File a
                            first appeal before the First Appellate Authority.</p>
                    </div>
                    <a href="{{ $links['first'] }}" class="dbim-home-btn">
                        File first appeal<span class="dbim-sr-only"> online</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                            <path d="m9 6 6 6-6 6"/>
                        </svg>
                    </a>
                </li>

                <li class="dbim-home-row">
                    <span class="dbim-home-row__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round" focusable="false">
                            <path d="M12 20V5M5 12l7-7 7 7"/>
                        </svg>
                    </span>
                    <div class="dbim-home-row__body">
                        <h3 class="dbim-home-row__title">Second Appeal</h3>
                        <p class="dbim-home-row__text">Still not resolved at the first appeal stage? Escalate by
                            filing a second appeal.</p>
                    </div>
                    <a href="{{ $links['second'] }}" class="dbim-home-btn">
                        File second appeal<span class="dbim-sr-only"> online</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                            <path d="m9 6 6 6-6 6"/>
                        </svg>
                    </a>
                </li>

            </ul>
        </div>
    </section>

    {{-- 4. HOW IT WORKS --}}
    <section class="dbim-home-section dbim-home-how" aria-labelledby="stepsHeading">
        <div class="dbim-home-wrap">
            <h2 id="stepsHeading" class="dbim-home-title dbim-home-title--lg">
                <svg class="dbim-home-title__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"
                     aria-hidden="true" focusable="false">
                    <path d="M9 6h11M9 12h11M9 18h11M4 6h.01M4 12h.01M4 18h.01" stroke-width="2.4"/>
                </svg>
                How it works
            </h2>

            <ol class="dbim-home-steps">
                <li><strong>Sign in with OTP</strong>
                    Verify your mobile number and email to log in as an applicant.</li>
                <li><strong>Fill in the form</strong>
                    Select the Public Authority, enter your request and upload documents (PDF only).</li>
                <li><strong>Pay online, if applicable</strong>
                    Fee payment through the eChallan gateway is required only for a new RTI application
                    filed by a non-BPL applicant. BPL applicants, first appeals and second appeals can be
                    filed without payment.</li>
                <li><strong>Submit and note the registration number</strong>
                    Keep it handy to follow up on your request or file an appeal later.</li>
            </ol>
        </div>
    </section>

</div>

@if(session('is_valid'))
    <form id="valid_test" action="{{ route('is_valid_req') }}" method="POST" hidden>
        @csrf
    </form>
    <button type="button" id="validTestSubmit" hidden>Submit Request</button>
@endif
@endsection

@push('scripts')
@if(session('is_valid'))
<script>
    document.getElementById('validTestSubmit').addEventListener('click', function () {
        document.getElementById('valid_test').submit();
    });
</script>
@endif

<script>
    (function () {
        var box  = document.getElementById('dbimTicker');
        var text = document.getElementById('dbimTickerText');
        var btn  = document.getElementById('dbimTickerToggle');
        if (!box || !text || !btn) { return; }
        if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) { return; }

        var SPEED = 90; // pixels per second (lower = slower)

        function setSpeed() {
            var view = text.parentNode.clientWidth;
            text.style.animationDuration = ((text.scrollWidth + view) / SPEED) + 's';
        }

        box.classList.add('is-moving');
        btn.hidden = false;
        setSpeed();

        var timer;
        window.addEventListener('resize', function () {
            clearTimeout(timer);
            timer = setTimeout(setSpeed, 200);
        });

        // The icon switches by CSS (.is-paused); only the class and the label change here.
        btn.addEventListener('click', function () {
            var paused = box.classList.toggle('is-paused');
            btn.setAttribute('aria-label', paused ? 'Play scrolling announcement' : 'Pause scrolling announcement');
        });
    })();
</script>
@endpush