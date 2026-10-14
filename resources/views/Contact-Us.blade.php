{{-- ============================================================
     Contact Us  (DBIM / GIGW 3.0, keyboard + screen reader friendly)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     No inline styles.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Contact Us | RTI Online, Government of Goa')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">

            {{-- Page heading: 24px bold, starts at the card's left edge --}}
            <h1 class="h2 mb-4">Contact Us</h1>

            {{-- Note: information (blue bar), not an error, so no red text --}}
            <div class="alert alert-info mb-4" role="note">
                <strong>Note:</strong>
                If the applicant's query doesn't get resolved through
                <strong>@obfuscateEmail('support.rtionline@goa.gov.in')</strong>
                then they may contact below mentioned officials.
            </div>

            <div class="card shadow-sm">
                <h2 class="card-header h5 mb-0">Contact Details</h2>

                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-3">Address</dt>
                        <dd class="col-sm-9">
                            <address class="mb-0">
                                Department of Information and Publicity<br>
                                Government of Goa<br>
                                Udyog Bhavan, 3rd Floor,<br>
                                Panaji – Goa. Pin 403001, India.
                            </address>
                        </dd>

                        <dt class="col-sm-3">Email</dt>
                        <dd class="col-sm-9">
                            <a href="mailto:dir-dip.goa@gov.in">@obfuscateEmail('dir-dip.goa@gov.in')</a>
                        </dd>

                        <dt class="col-sm-3">Telephone</dt>
                        <dd class="col-sm-9 mb-0">2223157 / 2422675 / 2226047</dd>
                    </dl>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection