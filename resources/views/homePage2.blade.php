{{-- ============================================================
     Home Page (DBIM / GIGW 3.0, Widescreen Layout)
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Home | RTI Online, Government of Goa')

@section('content')
<div class="dbim-homepage-wrapper">

    {{-- Hero Section --}}
    <section class="dbim-hero-section py-3 mb-4" aria-labelledby="homeHeading">
        <div class="container-fluid px-4 px-lg-5">
            <div class="row align-items-center">
                <div class="col-12">
                    <h1 id="homeHeading" class="dbim-hero-title mb-1">About RTI Online</h1>
                    <p class="dbim-hero-lead mb-0 text-nowrap-lg">
                        Empowering citizens with transparent, efficient, and direct access to public authorities and government departments through secure e-filing and e-Challan payment integration.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Main Content Section --}}
    <div class="container-fluid px-4 px-lg-5 pb-5">

        {{-- Important Notice Alert --}}
        <div class="alert alert-warning mb-4 shadow-sm" role="note">
            Please file RTI application/first appeal through this portal for Departments/Public Authorities under the Goa State Government only. If filed wrongly, the application would be returned, without a refund of amount.
        </div>

        {{-- Core Content Grid --}}
        <div class="row g-4 align-items-stretch">

            {{-- Portal Description --}}
            <div class="col-12 col-lg-7 col-xl-8">
                <div class="card h-100 shadow-sm border-0 dbim-content-card">
                    <div class="card-body p-4 p-lg-5">
                        <p class="dbim-portal-text">
                            This is a portal to file RTI application / first appeal / second appeal online along with eChallan payment gateway. Payment can be made through internet banking, debit / credit cards of Master / Visa cards and UPI. Through this portal, RTI application / first appeal / second appeal can be filed by Indian Citizens for all Departments / Offices / Sub-Offices Public Authorities of Goa State Government
                        </p>

                        <div class="p-3 bg-light rounded border-start border-4 border-primary mt-4">
                            <p class="fst-italic dbim-text-key mb-0">
                                Please read instructions carefully while submitting your RTI application, first appeal, or second appeal.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Chief Minister Column --}}
            <div class="col-12 col-lg-5 col-xl-4">
                <div class="card h-100 shadow-sm border-0 dbim-content-card text-center">
                    <div class="card-body p-4 d-flex flex-column justify-content-center align-items-center">
                        <figure class="mb-0 w-100">
                            <img src="{{ asset('images/CM_image.png') }}"
                                 alt="Photograph of the Chief Minister of Goa"
                                 class="dbim-home__photo-enlarged mb-3">
                            <figcaption class="mt-2">
                                <div class="fw-bold fs-6 text-dark">Dr. Pramod Sawant</div>
                                <div class="small text-muted">Chief Minister</div>
                                <div class="small text-muted">Government of Goa</div>
                            </figcaption>
                        </figure>
                    </div>
                </div>
            </div>

        </div>
    </div>
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