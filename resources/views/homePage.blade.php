{{-- ============================================================
     Home Page  (DBIM / GIGW 3.0, keyboard + screen reader friendly)
     Same card as the Officers Login page (dbim-login__*), wide version.
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     No inline styles. Styles: sections 27, 29 and 32 of public/css/dbim.css
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Home | RTI Online, Government of Goa')

@section('content')
<div class="dbim-login dbim-login--wide">
    <section class="dbim-login__card" aria-labelledby="homeHeading">

        <div class="dbim-login__head">
            <h1 id="homeHeading" class="dbim-login__title">About RTI Online</h1>
        </div>

        <div class="dbim-login__body">

            {{-- Important notice: Mustard Yellow (warning) bar from the DBIM functional palette --}}
            <div class="alert alert-warning mb-4" role="note">
                Please file RTI application/first appeal through this portal for Departments/Public Authorities under the Goa State Government only. If filed wrongly, the application would be returned, without a refund of amount.
            </div>

            <div class="row g-4">

                {{-- Portal introduction --}}
                <div class="col-12 col-md-7">
                    <p>This is a portal to file RTI application / first appeal / second appeal online along with eChallan payment gateway. Payment can be made through internet banking, debit / credit cards of Master / Visa cards and UPI. Through this portal, RTI application / first appeal / second appeal can be filed by Indian Citizens for all Departments / Offices / Sub-Offices Public Authorities of Goa State Government</p>
                    <p class="fst-italic dbim-text-key mb-0">Please read instructions carefully while submitting the RTI application/first appeal/second appeal.</p>
                </div>

                {{-- Chief Minister --}}
                <div class="col-12 col-md-5 dbim-home__side">
                    <figure class="mb-0">
                        <img src="{{ asset('images/CM_image.png') }}"
                             alt="Photograph of the Chief Minister of Goa"
                             class="dbim-home__photo">
                        <figcaption class="mt-3">
                            <div class="fw-bold">Dr. Pramod Sawant</div>
                            <div>Chief Minister</div>
                            <div>Government of Goa</div>
                        </figcaption>
                    </figure>
                </div>

            </div>
        </div>
    </section>
</div>

{{-- Kept from the old page: hidden form + hidden button (not shown, not focusable) --}}
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