{{-- ============================================================
     Home Page (DBIM / GIGW 3.0, clean full-width layout)
     Styles: section 37 in public/css/dbim.css (existing variables only)
     Bands: white (notice + About) / light blue (Services) / white (How it works)
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

<div class="dbim-hp">

    {{-- 0. IMPORTANT NOTICE
         Moving, slow, with Pause/Play. Shows as normal static text if JS is off
         or the visitor has reduced motion turned on (WCAG 2.2.2 / GIGW 3.0). --}}
    <div class="dbim-hp-notice" id="dbimNotice" role="note">
        <div class="dbim-hp-notice__inner">
            <span class="dbim-hp-notice__icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" height="32 " viewBox="0 -960 960 960" width="32" fill="2d2d2d" style="display: flex; justify-content: center;"><path d="M479.98-280q14.02 0 23.52-9.48t9.5-23.5q0-14.02-9.48-23.52t-23.5-9.5q-14.02 0-23.52 9.48t-9.5 23.5q0 14.02 9.48 23.52t23.5 9.5ZM453-433h60v-253h-60v253Zm27.27 353q-82.74 0-155.5-31.5Q252-143 197.5-197.5t-86-127.34Q80-397.68 80-480.5t31.5-155.66Q143-709 197.5-763t127.34-85.5Q397.68-880 480.5-880t155.66 31.5Q709-817 763-763t85.5 127Q880-563 880-480.27q0 82.74-31.5 155.5Q817-252 763-197.68q-54 54.31-127 86Q563-80 480.27-80Zm.23-60Q622-140 721-239.5t99-241Q820-622 721.19-721T480-820q-141 0-240.5 98.81T140-480q0 141 99.5 240.5t241 99.5Zm-.5-340Z" fill="currentColor"/></svg>
            </span>
            <strong class="dbim-hp-notice__label">Important:</strong>
            <div class="dbim-hp-notice__viewport">
                <p class="dbim-hp-notice__text" id="dbimNoticeText">
                    Please file RTI application/first appeal through this portal for Departments/Public
                    Authorities under the Goa State Government only. If filed wrongly, the application
                    would be returned, without a refund of amount.
                </p>
            </div>
            <button type="button" class="dbim-hp-notice__btn" id="dbimNoticeToggle"
                    aria-label="Pause scrolling notice" hidden>Pause</button>
        </div>
    </div>

    {{-- 1. ABOUT (white) ---------------------------------------- --}}
    <section class="dbim-hp-section" aria-labelledby="homeHeading">
        <div class="dbim-hp-wrap">


            <div class="dbim-hp-about">

                <div class="dbim-hp-about__text">
                    <h1 id="homeHeading" class="dbim-hp-heading">About this Portal</h1>

                    <p class="dbim-hp-text">
                        This is a portal to file RTI application / first appeal / second appeal online
                        along with an eChallan payment gateway.
                    </p>
                    <p class="dbim-hp-text">
                        Through this portal, RTI application / first appeal / second appeal can be filed by
                        Indian Citizens for all Departments / Offices / Sub-Offices Public Authorities of
                        Goa State Government.
                    </p>

                    <h2 class="dbim-hp-subheading">Payment options</h2>
                    <ul class="dbim-hp-chips">
                        <li>Internet banking</li>
                        <li>Debit / credit cards (Master, Visa)</li>
                        <li>UPI</li>
                    </ul>

                    <h2 class="dbim-hp-subheading">Instructions</h2>
                    <p class="dbim-hp-text mb-0">
                        Please read the instructions carefully while submitting your RTI application,
                        first appeal or second appeal.
                    </p>
                </div>

                <figure class="dbim-hp-panel">
                    <img src="{{ asset('images/CM_image.png') }}"
                         alt="Dr. Pramod Sawant, Chief Minister of Goa"
                         class="dbim-hp-panel__photo"
                         width="360" height="352">
                    <figcaption class="dbim-hp-panel__caption">
                        <span class="dbim-hp-panel__name">Dr. Pramod Sawant</span>
                        <span class="dbim-hp-panel__role">Chief Minister</span>
                        <span class="dbim-hp-panel__role">Government of Goa</span>
                    </figcaption>
                </figure>

            </div>
        </div>
    </section>

    {{-- 2. ONLINE SERVICES (light blue) -------------------------- --}}
    <section class="dbim-hp-section dbim-hp-band" aria-labelledby="servicesHeading">
        <div class="dbim-hp-wrap">
            <h2 id="servicesHeading" class="dbim-hp-heading">Online Services</h2>

            <div class="dbim-hp-cards">
                <article class="dbim-hp-card">
                    <h3 class="dbim-hp-card__title">RTI Application</h3>
                    <p class="dbim-hp-card__text">
                        Submit a new RTI application to the Public Authority of your choice.
                        Fee payment is online, for non-BPL applicants only.
                    </p>
                    <a href="{{ $links['rti'] }}" class="dbim-hp-card__link">
                        File RTI application<span class="dbim-sr-only"> online</span>
                    </a>
                </article>

                <article class="dbim-hp-card">
                    <h3 class="dbim-hp-card__title">First Appeal</h3>
                    <p class="dbim-hp-card__text">
                        Not satisfied with the reply, or no reply received? File a first appeal before the First Appellate Authority.
                    </p>
                    <a href="{{ $links['first'] }}" class="dbim-hp-card__link">
                        File first appeal<span class="dbim-sr-only"> online</span>
                    </a>
                </article>

                <article class="dbim-hp-card">
                    <h3 class="dbim-hp-card__title">Second Appeal</h3>
                    <p class="dbim-hp-card__text">
                        Still not resolved at the first appeal stage? Escalate by filing a second appeal.
                    </p>
                    <a href="{{ $links['second'] }}" class="dbim-hp-card__link">
                        File second appeal<span class="dbim-sr-only"> online</span>
                    </a>
                </article>
            </div>
        </div>
    </section>

    {{-- 3. HOW IT WORKS (white) ---------------------------------- --}}
    <section class="dbim-hp-section" aria-labelledby="stepsHeading">
        <div class="dbim-hp-wrap">
            <h2 id="stepsHeading" class="dbim-hp-heading">How it works</h2>

            <ol class="dbim-hp-steps">
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

@if(session('is_valid'))
    @push('scripts')
    <script>
        document.getElementById('validTestSubmit').addEventListener('click', function () {
            document.getElementById('valid_test').submit();
        });
    </script>
    @endpush
@endif

@push('scripts')
<script>
    (function () {
        var box  = document.getElementById('dbimNotice');
        var text = document.getElementById('dbimNoticeText');
        var btn  = document.getElementById('dbimNoticeToggle');
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