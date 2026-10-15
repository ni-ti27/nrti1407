{{-- ============================================================
     Policy  (DBIM / GIGW 3.0)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     Styles: .dbim-form-card and .dbim-about (sections 16 and 17).
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Policy | RTI Online, Government of Goa')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-9">

            <div class="card dbim-form-card">
               <h1 class="card-header h4 mb-0">About Us</h1>
                <div class="card-body dbim-about">

                    <section aria-labelledby="policy-disclaimer">
                        <h2 id="policy-disclaimer" class="h3">Disclaimer</h2>
                        <p>
                            This Website is designed &amp; developed by National Informatics Centre, Goa and maintained
                            by Department of Information and Publicity, Government of Goa.
                        </p>
                        <p>
                            The contents of this website are for information purposes only, enabling public to have a
                            quick and an easy access to information. We are taking every effort to provide accurate and
                            updated information. However, it is likely that the details such as telephone numbers, name
                            of the officer holding a post, etc may change prior to their updating in the web site.
                            Hence, we do not assume any legal liability on the completeness, accuracy or usefulness of
                            the contents provided in this web site.
                        </p>
                        <p>
                            The links are provided to other external sites in some documents. We are not responsible for
                            the accuracy of the contents in those sites. The links given to external sites do not
                            constitute an endorsement of information, products or services offered by these sites.
                        </p>
                        <p>
                            Despite our best efforts, we do not guarantee that the documents in this site are free from
                            infection by computer viruses etc. We welcome your suggestions to improve our site and
                            request that any error found may kindly be brought to our notice.
                        </p>
                    </section>

                    <section aria-labelledby="policy-copyright">
                        <h2 id="policy-copyright" class="h3">Copyright Policy</h2>
                        <p>
                            Material featured on this site may be reproduced free of charge in any format or media
                            without requiring specific permission. This is subject to the material being reproduced
                            accurately and not being used in a derogatory manner or in a misleading context. Where the
                            material is being published or issued to others, the source must be prominently
                            acknowledged. However, the permission to reproduce this material does not extend to any
                            material on this site, which is explicitly identified as being the copyright of a third
                            party. Authorisation to reproduce such material must be obtained from the copyright holders
                            concerned.
                        </p>
                    </section>

                    <section aria-labelledby="policy-hyperlink">
                        <h2 id="policy-hyperlink" class="h3">Hyperlink Policy</h2>
                        <p>
                            We do not object to you linking directly to the information that is hosted on our site and
                            no prior permission is required for the same. However, we would like you to inform us about
                            any links provided to our site so that you can be informed of any changes or updations
                            therein. Also, we do not permit our pages to be loaded into frames on your site. Our
                            Department's pages must load into a newly opened browser window of the user.
                        </p>
                    </section>

                    <section aria-labelledby="policy-privacy">
                        <h2 id="policy-privacy" class="h3">Privacy Statement</h2>
                        <p>
                            As a general rule, this web site does not collect Personal Information about you when you
                            visit the site. You can generally visit this site without revealing personal information,
                            unless you choose to provide such information. Any personal information collected shall be
                            used only for the stated purpose and shall NOT be shared with any other department or
                            organization (public/private).
                        </p>
                        <p>
                            This site may contain links to non-Government sites whose data protection and privacy
                            practices may differ from ours. We are not responsible for the content and privacy
                            practices of these other websites and encourage you to consult the privacy notices of those
                            sites.
                        </p>
                        <div class="dbim-support">
                            <p>Thanks for visiting our site.</p>
                            <p class="mb-0"><strong>Web Site Administration Team, Goa</strong></p>
                        </div>
                    </section>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection