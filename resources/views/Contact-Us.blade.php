{{-- ============================================================
     Contact Us  (DBIM / GIGW 3.0, same look as the Search page)
     Styles: section 37 (home) + 39 (contact) + 48 (contact, calm) in public/css/dbim.css
     White page, grey cards, white inner panels. No light-blue band.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Contact Us | RTI Online, Government of Goa')

@section('content')
<div class="dbim-hp">

    {{-- 1. HEADING + NOTE (white) --}}
    <section class="dbim-hp-section" aria-labelledby="contactHeading">
        <div class="dbim-hp-wrap">
            <h1 id="contactHeading" class="dbim-hp-heading">Contact Us</h1>

            <div class="alert alert-info mb-0" role="note">
                <strong>Note:</strong>
                If the applicant's query doesn't get resolved through
                <strong>@obfuscateEmail('support.rtionline@goa.gov.in')</strong>
                then they may contact below mentioned officials.
            </div>
        </div>
    </section>

    {{-- 2. CONTACT DETAILS (white, thin line above) --}}
    <section class="dbim-hp-section dbim-hp-section--line" aria-labelledby="detailsHeading">
        <div class="dbim-hp-wrap">
            <h2 id="detailsHeading" class="dbim-sr-only">Contact Details</h2>

            <div class="dbim-hp-contact">

                {{-- Details --}}
                <div class="dbim-hp-card dbim-hp-card--grey">
                    <div class="dbim-hp-inset">
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
                </div>

                {{-- Map --}}
                <div class="dbim-hp-card dbim-hp-card--grey">
                    <div class="dbim-hp-inset">
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
        </div>
    </section>

</div>
@endsection