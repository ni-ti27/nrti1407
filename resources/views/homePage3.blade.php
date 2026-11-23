{{-- ============================================================
     Home Page (DBIM / GIGW 3.0), Blue colour group
     Styles: section 49 (services, steps) + section 68 (headline bar, hero, info cards)
     Order : moving headline / blue hero / info cards / Online Services / How it works
     Header and footer come from layouts.app (unchanged).
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Home | RTI Online, Government of Goa')

@section('content')
@php
    // Change these to your real route names / URLs.
    $links = [
        'rti'    => '#',   // new RTI application
        'first'  => '#',   // first appeal
        'second' => '#',   // second appeal
    ];
@endphp

<div class="dbim-hm dbim-hb">

    {{-- 0. MOVING HEADLINE
         Slow moving text with Pause / Play. Static text when JS is off or when the visitor
         has reduced motion turned on (WCAG 2.2.2, GIGW 3.0). --}}
    <section class="dbim-hb-ticker" id="dbimTicker" aria-label="Important notice">
        <div class="dbim-hb-ticker__inner">
            <strong class="dbim-hb-ticker__label">Important</strong>
            <div class="dbim-hb-ticker__viewport">
                <p class="dbim-hb-ticker__text" id="dbimTickerText">
                    Please file RTI application/first appeal through this portal for Departments/Public
                    Authorities under the Goa State Government only. If filed wrongly, the application
                    would be returned, without a refund of amount.
                </p>
            </div>
            <button type="button" class="dbim-hb-ticker__btn" id="dbimTickerToggle"
                    aria-label="Pause scrolling notice" hidden>Pause</button>
        </div>
    </section>

    {{-- 1. HERO (blue) ----------------------------------------- --}}
    <section class="dbim-hb-hero" aria-labelledby="homeHeading">
        <div class="dbim-hm-wrap">
            <div class="dbim-hb-hero__grid">

                <div class="dbim-hb-hero__body">
                    <h1 id="homeHeading" class="dbim-hb-hero__title">About this Portal</h1>

                    <div class="dbim-hb-hero__lead">
                        <p>
                            This is a portal to file RTI application / first appeal / second appeal online
                            along with an eChallan payment gateway.
                        </p>
                        <p>
                            Through this portal, RTI application / first appeal / second appeal can be filed by
                            Indian Citizens for all Departments / Offices / Sub-Offices Public Authorities of
                            Goa State Government.
                        </p>
                    </div>

                    <a href="{{ $links['rti'] }}" class="dbim-hb-cta">
                        File RTI application<span class="dbim-sr-only"> online</span>
                    </a>
                </div>

                <figure class="dbim-hb-person">
                    <img src="{{ asset('images/CM_image.png') }}"
                         alt="Dr. Pramod Sawant, Chief Minister of Goa"
                         class="dbim-hb-person__photo"
                         width="220" height="220">
                    <figcaption class="dbim-hb-person__caption">
                        <span class="dbim-hb-person__name">Dr. Pramod Sawant</span>
                        <span>Chief Minister</span>
                        <span>Government of Goa</span>
                    </figcaption>
                </figure>

            </div>
        </div>
    </section>

    {{-- 2. PAYMENT OPTIONS + INSTRUCTIONS (light blue band, white cards) --}}
    <section class="dbim-hb-info" aria-label="Payment options and instructions">
        <div class="dbim-hm-wrap">
            <div class="dbim-hb-info__grid">
                <div class="dbim-hb-panel">
                    <h2 class="dbim-hb-panel__title">Payment options</h2>
                    <ul class="dbim-hm-chips">
                        <li>Internet banking</li>
                        <li>Debit / credit cards (Master, Visa)</li>
                        <li>UPI</li>
                    </ul>
                </div>
                <div class="dbim-hb-panel">
                    <h2 class="dbim-hb-panel__title">Instructions</h2>
                    <p>
                        Please read the instructions carefully while submitting your RTI application,
                        first appeal or second appeal.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. ONLINE SERVICES (white) ------------------------------ --}}
    <section class="dbim-hm-section" aria-labelledby="servicesHeading">
        <div class="dbim-hm-wrap">
            <h2 id="servicesHeading" class="dbim-hm-heading">Online Services</h2>

            <div class="dbim-hm-cards">

                <article class="dbim-hm-card">
                    <span class="dbim-hm-card__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" focusable="false">
                            <path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/>
                            <path d="M14 3v5h5M9 13h6M9 17h6"/>
                        </svg>
                    </span>
                    <h3 class="dbim-hm-card__title">RTI Application</h3>
                    <p class="dbim-hm-card__text">
                        Submit a new RTI application to the Public Authority of your choice.
                        Fee payment is online, for non-BPL applicants only.
                    </p>
                    <a href="{{ $links['rti'] }}" class="dbim-hm-btn">
                        File RTI application<span class="dbim-sr-only"> online</span>
                    </a>
                </article>

                <article class="dbim-hm-card">
                    <span class="dbim-hm-card__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" focusable="false">
                            <path d="M4 12a8 8 0 0 1 14-5l2 2M20 4v5h-5M20 12a8 8 0 0 1-14 5l-2-2M4 20v-5h5"/>
                        </svg>
                    </span>
                    <h3 class="dbim-hm-card__title">First Appeal</h3>
                    <p class="dbim-hm-card__text">
                        Not satisfied with the reply, or no reply received? File a first appeal before the
                        First Appellate Authority.
                    </p>
                    <a href="{{ $links['first'] }}" class="dbim-hm-btn">
                        File first appeal<span class="dbim-sr-only"> online</span>
                    </a>
                </article>

                <article class="dbim-hm-card">
                    <span class="dbim-hm-card__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" focusable="false">
                            <path d="M12 20V5M5 12l7-7 7 7"/>
                        </svg>
                    </span>
                    <h3 class="dbim-hm-card__title">Second Appeal</h3>
                    <p class="dbim-hm-card__text">
                        Still not resolved at the first appeal stage? Escalate by filing a second appeal.
                    </p>
                    <a href="{{ $links['second'] }}" class="dbim-hm-btn">
                        File second appeal<span class="dbim-sr-only"> online</span>
                    </a>
                </article>

            </div>
        </div>
    </section>

    {{-- 4. HOW IT WORKS (white, light-blue step boxes) --------- --}}
    <section class="dbim-hm-section dbim-hm-how" aria-labelledby="stepsHeading">
        <div class="dbim-hm-wrap">
            <h2 id="stepsHeading" class="dbim-hm-heading">How it works</h2>

            <ol class="dbim-hm-steps">
                <li>
                    <strong>Sign in with OTP</strong>
                    Verify your mobile number and email to log in as an applicant.
                </li>
                <li>
                    <strong>Fill in the form</strong>
                    Select the Public Authority, enter your request and upload documents (PDF only).
                </li>
                <li>
                    <strong>Pay online, if applicable</strong>
                    Fee payment through the eChallan gateway is required only for a new RTI application
                    filed by a non-BPL applicant. BPL applicants, first appeals and second appeals can be
                    filed without payment.
                </li>
                <li>
                    <strong>Submit and note the registration number</strong>
                    Keep it handy to follow up on your request or file an appeal later.
                </li>
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

        var SPEED = 90; // pixels per second: normal reading speed (lower = slower)

        function setSpeed() {
            var view = text.parentNode.clientWidth;
            // distance = text width + one blank viewport; duration = distance / speed
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

        btn.addEventListener('click', function () {
            var paused = box.classList.toggle('is-paused');
            btn.textContent = paused ? 'Play' : 'Pause';
            btn.setAttribute('aria-label', paused ? 'Play scrolling notice' : 'Pause scrolling notice');
        });
    })();
</script>
@endpush