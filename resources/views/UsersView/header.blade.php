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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<div class="header-background">
    <div class="container-fluid header-content text-center">
        <!-- Logo and Title Section -->
        <div class="row align-items-center">
            <div class="col-md-3 offset-md-2 text-md-start">
                <img src="../images/Emblem_of_Goa.png" alt="Goa Govt Logo">
            </div>
            <div class="col-md-5 text-md-end">
                <a class="nav-link" href="/searchDepartment">Public Authorities Available</a>
                <h2>RTI Online</h2>
                <p>Version 1.0</p>
                <p>An Initiative of Department of Information & Publicity, Govt. of Goa</p>
            </div>
        </div>

        <hr>

        <!-- Bootstrap Navigation -->
        <nav class="navbar navbar-expand-lg justify-content-center">
            <button class="navbar-toggler navbar-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item px-2"><a class="nav-link" href="/">Home</a></li>
                    <li class="nav-item px-2">
                        @if($hasPendingRecords)
                            <a class="nav-link" href="{{ route('pending-records-list') }}">RTI New Request</a>
                        @else
                            <a class="nav-link" href="{{ url('/profile') }}">RTI New Request</a>
                        @endif
                    </li>
                    @if(session()->has('mobile_no'))
                    <li class="nav-item px-2"><a class="nav-link" href="{{ route('first-appeal-detail') }}">Submit First Appeal</a></li>
                    <li class="nav-item px-2"><a class="nav-link" href="{{ route('request-type-form') }}">Submit Second Appeal</a></li>
                    <li class="nav-item px-2"><a class="nav-link" href="/application-status">Application Status</a></li>
                    <li class="nav-item px-2"><a class="nav-link" href="{{ route('dashboard') }}">Payment Status</a></li>
                     @endif
                    <li class="nav-item px-2"><a class="nav-link" href="{{ route('login') }}">Dept. Login</a></li>
                    <li class="nav-item px-2"><a class="nav-link" href="/contactUs">Contact Us</a></li>
                    <li class="nav-item px-2"><a class="nav-link" href="/FAQ">FAQ</a></li>
                </ul>
            </div>
        </nav>

        @if(session()->has('mobile_no'))
            <hr>
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

