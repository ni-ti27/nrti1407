{{-- ============================================================
     First Appeal Status - Filed Successfully  (DBIM / GIGW 3.0)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     Same details as the original page.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'First Appeal Status | RTI Online, Government of Goa')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">

            @include('partials.flash-messages')

            <div class="card shadow-sm">
                <h1 class="card-header h4 mb-0">Filed Successfully</h1>

                <div class="card-body">

                    <div class="alert alert-success" role="status">
                        First Appeal Request Filed Successfully
                    </div>

                    <p>Please note down the details of Registration</p>

                    <dl class="row mb-4">
                        <dt class="col-sm-5 col-lg-4">Registration Number</dt>
                        <dd class="col-sm-7 col-lg-8 fw-bold">{{ $submittedDetails->fa_reg_no }}</dd>

                        <dt class="col-sm-5 col-lg-4">Name</dt>
                        <dd class="col-sm-7 col-lg-8 fw-bold">{{ $submittedDetails->name }}</dd>

                        <dt class="col-sm-5 col-lg-4">Date &amp; Time of e-Filing</dt>
                        <dd class="col-sm-7 col-lg-8 fw-bold">{{ $submittedDetails->created_at }}</dd>

                        <dt class="col-sm-5 col-lg-4">Request Filed with</dt>
                        <dd class="col-sm-7 col-lg-8 fw-bold mb-0">{{ $departmentInfo->department }}</dd>
                    </dl>

                    <h2 class="h5 dbim-text-key border-bottom pb-2 mb-3">Contact Details</h2>

                    <dl class="row mb-0">
                        <dt class="col-sm-5 col-lg-4">Mobile Number</dt>
                        <dd class="col-sm-7 col-lg-8 fw-bold">{{ $submittedDetails->mobile_no }}</dd>

                        <dt class="col-sm-5 col-lg-4">Email Id</dt>
                        <dd class="col-sm-7 col-lg-8 fw-bold mb-0 text-break">{{ $submittedDetails->email_id }}</dd>
                    </dl>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection