<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact Us</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
</head>

<style>

    .container.mt-5
    {
        /* border: 2px solid black; */
        border-top:none;
        margin-top:0 !important;
    }

</style>
<body>
    @include('UsersView.header')

    <div class="container mt-5" style="border-bottom:0px">
        <br>
        <h2>About Us</h2>
        <br>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <p class="card-text">
                            The Department of Information and Publicity, Government of Goa, is a nodal agency for dissemination of information on policies and programmes of the government through print, electronic and traditional media. The Department plays a pivotal role in the promotion of schemes and policies that lead to a greater and better understanding between the Government and the people. It also works as a communication medium between the government and media and initiates measures to take the welfare schemes to the doorsteps of the people.
                        <p>
                            The Department of Information and Publicity is headed by the Director, who is the Head of Department and assisted by the Joint Director of Information & Publicity, who is the Head of Office.</p>
                        <p>
                            There are two Information Officers in the department looking after advertisements, films, field publicity, etc. There is a sub-office of the Department at Margao, catering to the publicity needs of the Government in South Goa District.
                        </p>
                        <p>
                            RTI Online Portal implementation is part of the Apex Court Mandate to enable RTI Applicants in e-filing of RTI Applications, First Appeal, and Second Appeal, and also to receive replies in an online manner.
                            The portal facilitates e-filing of RTI Applications/First Appeal/Second Appeal by Indian Citizens for any of the Departments/Offices/Sub-Offices/Public Authorities of the Goa State Government.
                            The portal manages RTI information at three levels for Public Authorities: Department, Office, and Sub Office.
                        </p>

                        <p>The following roles exist on the portal to manage the RTI Applications/First Appeal/Second Appeal:</p>
                        <ul class="features-list">
                            <li>Nodal Officer (NODAL)</li>
                            <li>Public Information Officer (PIO)</li>
                            <li>Assistant Public Information Officer (APIO)</li>
                            <li>First Appellate Authority (FAA)</li>
                            <li>Clerk (CLERK)</li>
                            <li>State Information Commissioner (SIC)</li>
                        </ul>

                       <p>
                            The portal facilitates e-filing of applications online along with the e-Challan Payment Gateway. Payment can be made through Internet Banking, Debit/Credit cards of Master/Visa cards, and UPI.
                       </p>
                       <p>
                            RTI Support Email Id: @obfuscateEmail('support.rtionline@goa.gov.in') has been shared for user assistance. <br>
                            <strong>Department of Information and Publicity, Panaji Goa</strong>
                       </p>
                    </div>
                </div>
            </div>
        </div>
        <br>
    </div>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    @include('UsersView.footer')
</body>
</html>






