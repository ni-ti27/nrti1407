{{-- ============================================================
     Home Page  (DBIM / GIGW 3.0, keyboard + screen reader friendly)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     No inline styles.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Home | RTI Online, Government of Goa')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">

            {{-- Page heading for screen readers (remove "visually-hidden" to show it) --}}
            <h1 class="h2 mb-4 visually-hidden">RTI Online, Government of Goa</h1>

            {{-- Important notice: Mustard Yellow (warning) bar from the DBIM functional palette --}}
            <div class="alert alert-warning mb-4" role="note">
                Please file RTI application/first appeal through this portal for Departments/Public Authorities under the Goa State Government only. If filed wrongly, the application would be returned, without a refund of amount.
            </div>

            <div class="row g-4">
                <div class="col-12 col-md-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <p>This is a portal to file RTI application / first appeal / second appeal online along with eChallan payment gateway. Payment can be made through internet banking, debit / credit cards of Master / Visa cards and UPI. Through this portal, RTI application / first appeal / second appeal can be filed by Indian Citizens for all Departments / Offices / Sub-Offices Public Authorities of Goa State Government</p>
                            <p class="fst-italic dbim-text-key mb-0">Please read instructions carefully while submitting the RTI application/first appeal/second appeal.</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="card h-100 shadow-sm text-center">
                        <div class="card-body">
                            <figure class="mb-0">
                                <img src="{{ asset('images/CM_image.png') }}" alt="Photograph of the Chief Minister of Goa" class="img-fluid w-75">
                                <figcaption class="mt-3">
                                    <div>Dr. Pramod Sawant</div>
                                    <div>Chief Minister</div>
                                    <div>Government of Goa</div>
                                </figcaption>
                            </figure>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
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