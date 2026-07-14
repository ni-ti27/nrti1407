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
        <h2>Contact Us</h2>
        <br>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <h5 class="card-header"><span style="color:red">NOTE:</span><br> If the applicant's query doesn't get resolved through, <span style="color:blue">@obfuscateEmail('support.rtionline@goa.gov.in')</span>
then they may contact below mentioned officials.</h5>
                    <!-- <h5 class="card-header">Department of Information and Publicity</h5> -->
                    <div class="card-body">
                        <p class="card-text">Department of Information and Publicity</p>
                        <p class="card-text">Government of Goa</p>
                        <p class="card-text">Udyog Bhavan, 3rd Floor,</p>
                        <p class="card-text">Panaji – Goa. Pin 403001, India.</p>
                        <p class="card-text">Email: <a href="mailto:dir-dip.goa@gov.in">@obfuscateEmail('dir-dip.goa@gov.in')</a></p>
                        <p class="card-text">Telephone: 2223157 / 2422675 / 2226047</p>
                    </div>
                </div>
            </div>
        </div>
        <br>
    </div>

    @include('UsersView.footer')
</body>
</html>
