{{-- ============================================================
     Contact Us  (DBIM / GIGW 3.0, keyboard + screen reader friendly)
     Same card as the Officers Login page (dbim-login__*), wider.
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     No inline styles. Styles: sections 27 and 29 of public/css/dbim.css
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Contact Us | RTI Online, Government of Goa')

@section('content')
<div class="dbim-login dbim-login--wide">
    <section class="dbim-login__card" aria-labelledby="contactHeading">

        <div class="dbim-login__head">
            <h1 id="contactHeading" class="dbim-login__title">Contact Us</h1>
        </div>

        <div class="dbim-login__body">

            <div class="alert alert-info mb-4" role="note">
                <strong>Note:</strong>
                If the applicant's query doesn't get resolved through
                <strong>@obfuscateEmail('support.rtionline@goa.gov.in')</strong>
                then they may contact below mentioned officials.
            </div>

            <h2 class="dbim-contact__heading">Contact Details</h2>

            <div class="row g-4">

                {{-- Details --}}
                <div class="col-12 col-md-6">
                    <dl class="row dbim-detail mb-0">
                        <dt class="col-sm-4">Address</dt>
                        <dd class="col-sm-8">
                            <address class="mb-0">
                                Department of Information and Publicity<br>
                                Government of Goa<br>
                                Udyog Bhavan, 3rd Floor,<br>
                                Panaji – Goa. Pin 403001, India.
                            </address>
                        </dd>

                        <dt class="col-sm-4">Email</dt>
                        <dd class="col-sm-8">
                            <a href="mailto:dir-dip.goa@gov.in">@obfuscateEmail('dir-dip.goa@gov.in')</a>
                        </dd>

                        <dt class="col-sm-4">Telephone</dt>
                        <dd class="col-sm-8 mb-0">2223157 / 2422675 / 2226047</dd>
                    </dl>
                </div>

                {{-- Map --}}
                <div class="col-12 col-md-6">
                    <p class="mb-2">
                        <a href="https://www.google.com/maps/search/?api=1&amp;query=Udyog+Bhavan+Panaji+Goa+403001"
                           target="_blank" rel="noopener noreferrer">
                            View larger map<span class="visually-hidden"> (opens in a new tab)</span>
                        </a>
                    </p>
                    <div class="ratio ratio-4x3 dbim-contact__map">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d36357.43718072619!2d73.8247638715045!3d15.506937242850059!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bbfc0889fe782b9%3A0x30968ce4389ef129!2sDepartment%20of%20Information%20%26%20Publicity!5e0!3m2!1sen!2sin!4v1792066006982!5m2!1sen!2sin"
                                title="Map showing the location of the Department of Information and Publicity, Udyog Bhavan, Panaji, Goa"
                                width="100%" height="100%" allowfullscreen loading="lazy"></iframe>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
@endsection