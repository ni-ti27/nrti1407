<style>
    .header-background {
        background-image: url("../images/light-blue-header.jpg");
        background-position: center bottom;
        background-repeat: no-repeat;
        background-size: cover;
        padding: 30px 0;
        width: 100%;
    }

    .header-content img {
        width: 120px;
        height: auto;
    }

    .header-content a {
        color: #000;
        text-decoration: none;
        font-weight: 600;
    }

    .navigation-row {
        margin-top: 10px;
        font-weight: 600;
    }

    .navigation-link {
        margin: 0 10px;
    }

    .row-divider hr {
        margin: 10px 0;
    }

    .user-info a {
        font-weight: bold;
    }


    /* Tooltip container */
    .custom-cursor-tooltip {
        position: fixed;
        pointer-events: none;  /* tooltip doesn't block mouse events */
        z-index: 9999;
        background: #333;
        color: #fff;
        padding: 10px;
        border-radius: 8px;
        font-size: 14px;
        max-width: 220px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transition: opacity 0.1s ease;
    }

    /* Tooltip arrow */
    .custom-cursor-tooltip::after {
        content: "";
        position: absolute;
        width: 0;
        height: 0;
        border: 6px solid transparent; /* size of arrow */
        border-top-color: #333;        /* arrow color */
    }

</style>


<div class="header-background">
    <div class="container-fluid header-content text-center">
        <div class="row align-items-center">
            <!-- Logo -->
            <div class="offset-md-2 col-md-3 text-md-start">
                <img src="../images/Emblem_of_Goa.png" alt="Goa Govt Logo">
            </div>
            <!-- Header Text -->
            <div class="col-md-5 text-md-end">
                <!-- <p>Public Authorities Available</p> -->
                <a class="nav-link" href="/searchDepartment">Public Authorities Available</a>
                <h2>RTI Online</h2>
                <p>Version 1.0</p>
                <p>An Initiative of Department of Information & Publicity, Govt. of Goa</p>
            </div>
        </div>

        <div class="row row-divider">
            <div class="col">
                <hr>
            </div>
        </div>

        <!-- Navigation -->
        <div class="row justify-content-center navigation-row">
            <div class="col-auto navigation-link"><a href="/">Home</a></div>
           <div class="col-auto navigation-link">
                @if($hasPendingRecords)
                    <a href="{{ route('pending-records-list') }}">RTI New Request</a>
                @else
                    <a href="{{ url('/profile') }}">RTI New Request</a>
                @endif
            </div>
            <div class="col-auto navigation-link"><a href="{{ route('first-appeal-detail') }}">Submit First Appeal</a></div>
            <div class="col-auto navigation-link"><a href="{{ route('request-type-form') }}">Submit Second Appeal</a></div>
            <div class="col-auto navigation-link"><a href="/application-status">Application Status</a></div>
            <div class="col-auto navigation-link"><a href="{{ route('dashboard') }}">Payment Status</a></div>
            <div class="col-auto navigation-link"><a href="{{ route('login') }}">Dept. Login</a></div>
            <div class="col-auto navigation-link"><a href="/contactUs">Contact Us</a></div>
            <div class="col-auto navigation-link"><a href="/FAQ">FAQ</a></div>
        </div>

        @if(session()->has('mobile_no'))
            <div class="row row-divider">
                <div class="col">
                    <hr>
                </div>
            </div>
            <div class="row user-info">
                <div class="col-md-6 text-md-end text-center mb-2">
                     <a href="{{ route('profile') }}" class="btn btn-link cursor-tooltip" data-tooltip="My Profile">{{ session('username') }} | {{ session('mobile_no') }}</a>
                </div>
                <div class="col-md-6 text-md-start text-center">
                    <a href="{{ route('sessionForm.destroy') }}" class="btn btn-link">Logout</a>
                </div>
            </div>
        @endif
    </div>
</div>
