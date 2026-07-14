<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Home Page</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
</head>
<style>
    .img-fluid {
    max-width: 70%;
    height: auto;
}

.container.mt-5
    {
        /* border: 2px solid black; */
        /* border-bottom: none; */
        /* border-top:none; */
        margin-top:0 !important;
    }
</style>
<body>
    @include('UsersView.header')

    @if(session('is_valid'))
        <form id="valid_test" action="{{ route('is_valid_req') }}" method="POST" style="display:none;">
            @csrf
        </form>

        <button onclick="document.getElementById('valid_test').submit()" style="display:none">Submit Request</button>
    @endif
    <div class="container mt-5" style="border:none; margin-top:0 !important">

        <div class="row justify-content-center">
            <div class="col-md-10" style="margin: 20px 20px">

                <div class="row justify-content-center">
                    <div class="col-md-12 col-12">
                        <div class="card">
                            <div class="card-body">
                                <p style="color: ff0000;">
                                Please file RTI application/first appeal through this portal for Departments/Public Authorities under the Goa State Government only. If filed wrongly, the application would be returned, without a refund of amount.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <br>
                <div class="row justify-content-center">
                    <div class="col-md-6 col-12">
                        <div class="card">
                            <div class="card-body">
                                <p>This is a portal to file RTI application / first appeal / second appeal online along with eChallan payment gateway. Payment can be made through internet banking, debit / credit cards of Master / Visa cards and UPI. Through this portal, RTI application / first appeal / second appeal can be filed by Indian Citizens for all Departments / Offices / Sub-Offices Public Authorities of Goa State Government</p>
                                <p style="font-style: italic; color: #0066cc;">Please read instructions carefully while submitting the RTI application/first appeal/second appeal.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="card text-center">
                            <div class="card-body">
                                <img src="images/CM_image.png" alt="goa-cm-photo" class="img-fluid" />
                                <!-- <img src="images/flow.webP" alt="rti-flow" class="img-fluid" /> -->
                                <div class="mt-3">Dr. Pramod Sawant</div>
                                <div>Chief Minister</div>
                                <div class="mt">Government of Goa</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@include('UsersView.footer')
</body>
