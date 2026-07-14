<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <title>Policy</title>
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
        <h2>Policy</h2>
        <br>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <h3 class="card-header">Disclaimer</h3>
                    <div class="card-body">
                        <p class="card-text">
                            This Website is designed & developed by National Informatics Center, Goa and maintained by Department of Information and Publicity, Government of Goa.
                        </p>
                        <p class="card-text">
                            The contents of this website are for information purposes only, enabling public to have a quick and an easy access to information. We are taking every effort to provide accurate and updated information. However, it is likely that the details such as telephone numbers, name of the officer holding a post, etc may change prior to their updating in the web site. Hence, we do not assume any legal liability on the completeness, accuracy or usefulness of the contents provided in this web site.
                        </p>
                        <p class="card-text">
                            The links are provided to other external sites in some documents. We are not responsible for the accuracy of the contents in those sites. The links given to external sites do not constitute an endorsement of information, products or services offered by these sites.
                        </p>
                        <p class="card-text">
                            Despite our best efforts, we do not guarantee that the documents in this site are free from infection by computer viruses etc.
                            We welcome your suggestions to improve our site and request that any error found may kindly be brought to our notice.
                        </p>
                    </div>
                </div>
                <br>
                <div class="card">
                    <h3 class="card-header">Copyright Policy</h3>
                    <div class="card-body">
                        <p class="card-text">
                            Material featured on this site may be reproduced free of charge in any format or media without requiring specific permission. This is subject to the material being reproduced accurately and not being used in a derogatory manner or in a misleading context. Where the material is being published or issued to others, the source must be prominently acknowledged. However, the permission to reproduce this material does not extend to any material on this site, which is explicitly identified as being the copyright of a third party. Authorisation to reproduce such material must be obtained from the copyright holders concerned.
                        </p>
                    </div>
                </div>

                <br>

                <div class="card">
                    <h3 class="card-header">Hyperlink Policy</h3>
                    <div class="card-body">
                        <p class="card-text">
                            We do not object to you linking directly to the information that is hosted on our site and no prior permission is required for the same. However, we would like you to inform us about any links provided to our site so that you can be informed of any changes or updations therein. Also, we do not permit our pages to be loaded into frames on your site. Our Department's pages must load into a newly opened browser window of the user.
                        </p>
                    </div>
                </div>

                <br>

                <div class="card">
                    <h3 class="card-header">Privacy Statement</h3>
                    <div class="card-body">
                        <p class="card-text">
                            As a general rule, this web site does not collect Personal Information about you when you visit the site. You can generally Visit this Site, without revealing personal information, unless you choose to provide such information. Any personal information collected shall be used only for the stated purpose and shall NOT be shared with any other department organization (public/private).
                        </p>
                        <p>
                            This site may contain links to non-Government sites whose data protection and privacy practices may differ from ours. We are not responsible for the content and privacy practices of these other websites and encourage you to consult the privacy notices of those site.
                        </p>
                        <p>Thanks for visiting our site.</p>
                        <p>Web Site Administration Team Goa</p>
                    </div>
                </div>
            </div>
        </div>
        <br>
    </div>

    @include('UsersView.footer')
</body>
</html>






