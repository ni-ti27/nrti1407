{{-- ============================================================
     Contact Us  (DBIM / GIGW 3.0, same look as the Search for Department page)
     Styles: section 50 (page frame, grey band, white card, heading)
             + section 51 (contact details, map) in public/css/dbim.css
     Layout: grey (Linen) band outside, ONE white card inside.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Contact Us | RTI Online, Government of Goa')

@section('content')
<div class="dbim-sd">
    <section class="dbim-sd-section" aria-labelledby="contactHeading">
        <div class="dbim-sd-wrap">
            <div class="dbim-sd-card">

                {{-- Main heading of the page: the only one with the blue bar --}}
                <h1 id="contactHeading" class="dbim-sd-heading">Contact Us</h1>

                {{-- Note --}}
                <div class="dbim-ct-note" role="note">
                    <strong>Note:</strong>
                    If the applicant's query doesn't get resolved through
                    <strong>@obfuscateEmail('support.rtionline@goa.gov.in')</strong>
                    then they may contact below mentioned officials.
                </div>

                <hr class="dbim-sd-divider">

                <div class="dbim-ct-grid">

                    {{-- Contact details --}}
                    <section aria-labelledby="detailsHeading">
                        <h2 id="detailsHeading" class="dbim-ct-heading">Contact Details</h2>

                        <dl class="dbim-ct-dl">
                            <div class="dbim-ct-row">
                                <dt>Address</dt>
                                <dd>
                                    <address class="dbim-ct-address">
                                        Department of Information and Publicity<br>
                                        Government of Goa<br>
                                        Udyog Bhavan, 3rd Floor,<br>
                                        Panaji – Goa. Pin 403001, India.
                                    </address>
                                </dd>
                            </div>
                            <div class="dbim-ct-row">
                                <dt>Email</dt>
                                <dd>
                                    <a href="mailto:dir-dip.goa@gov.in">@obfuscateEmail('dir-dip.goa@gov.in')</a>
                                </dd>
                            </div>
                            <div class="dbim-ct-row">
                                <dt>Telephone</dt>
                                <dd>2223157 / 2422675 / 2226047</dd>
                            </div>
                        </dl>
                    </section>

                    {{-- Map --}}
                    <section aria-labelledby="mapHeading">
                        <h2 id="mapHeading" class="dbim-ct-heading">Location</h2>

                        <p class="dbim-ct-maplink">
                            <a href="https://www.google.com/maps/search/?api=1&amp;query=Udyog+Bhavan+Panaji+Goa+403001"
                               target="_blank" rel="noopener noreferrer">
                                View larger map<span class="visually-hidden"> (opens in a new tab)</span>
                            </a>
                        </p>
                        <div class="ratio ratio-4x3 dbim-ct-map">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d36357.43718072619!2d73.8247638715045!3d15.506937242850059!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bbfc0889fe782b9%3A0x30968ce4389ef129!2sDepartment%20of%20Information%20%26%20Publicity!5e0!3m2!1sen!2sin!4v1792066006982!5m2!1sen!2sin"
                                    title="Map showing the location of the Department of Information and Publicity, Udyog Bhavan, Panaji, Goa"
                                    width="100%" height="100%" allowfullscreen loading="lazy"></iframe>
                        </div>
                    </section>

                </div>
            </div>
        </div>
    </section>
</div>
@endsection