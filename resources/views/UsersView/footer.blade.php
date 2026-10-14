{{-- resources/views/partials/footer.blade.php --}}
{{-- Usage:  @include('partials.footer')  just before </body>
     Needs:  Bootstrap grid (container-fluid) + public/css/dbim.css (section 7) linked in the layout <head>
     Keep page content inside <div class="main-content"> so the footer sticks to the bottom. --}}

<footer class="dbim-footer">
    <div class="footerbox">
        <div class="container-fluid px-3 px-md-4 px-xl-5">
            <div class="row g-4">

                {{-- ===== Useful links ===== --}}
                <div class="col-12 col-lg-9">
                    <nav aria-labelledby="footer-links-heading">
                        <h2 class="usefula" id="footer-links-heading">Useful Links</h2>

                        <ul class="footer-links list-unstyled row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 gx-3 gy-1 mb-0">
                            <li class="col"><a href="/" target="_self">Home</a></li>
                            <li class="col"><a class="external-link" href="https://www.goa.gov.in/" target="_blank" rel="noopener noreferrer">Govt. of Goa<span class="dbim-sr-only"> (opens in a new window)</span></a></li>
                            <li class="col"><a class="external-link" href="https://www.india.gov.in/" target="_blank" rel="noopener noreferrer">National Portal of India<span class="dbim-sr-only"> (opens in a new window)</span></a></li>
                            <li class="col"><a class="external-link" href="https://gsic.goa.gov.in/" target="_blank" rel="noopener noreferrer">Complaint &amp; Second Appeal to SIC<span class="dbim-sr-only"> (opens in a new window)</span></a></li>
                            <li class="col"><a href="/FAQ" target="_self">FAQ</a></li>
                            <li class="col"><a href="/AboutUs" target="_self">About Us</a></li>
                            <li class="col"><a href="/Policy" target="_self">Policy</a></li>
                            <li class="col"><a href="/feedback" target="_self">Feedback</a></li>
                        </ul>
                    </nav>

                    <div class="footer-info">
                        <p class="footer-email">
                            RTI Support Email Id: <span>@obfuscateEmail('support.rtionline@goa.gov.in')</span>
                        </p>
                        <div class="footer-stats">
                            <p>Total Visitors: <strong>{{ $totalHits ?? 0 }}</strong></p>
                            <p>Today's Visitors: <strong>{{ $todayHits ?? 0 }}</strong></p>
                            <p>Today's Unique Visitors: <strong>{{ $todayUnique ?? 0 }}</strong></p>
                        </div>
                    </div>
                </div>

                {{-- ===== Right: Follow Us, NIC logo, last updated ===== --}}
                <div class="col-12 col-lg-3 footer-right">
                    <h2 class="usefula" id="footer-social-heading">Follow Us</h2>

                    {{-- REPLACE the # in each href with the official account URL --}}
                    <ul class="d-flex sociala" aria-labelledby="footer-social-heading">
                        <li>
                            <a class="external-link" href="#" target="_blank" rel="noopener noreferrer" aria-label="X (formerly Twitter) (opens in a new window)">
                                <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </a>
                        </li>
                        <li>
                            <a class="external-link" href="#" target="_blank" rel="noopener noreferrer" aria-label="YouTube (opens in a new window)">
                                <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.6 12 3.6 12 3.6s-7.5 0-9.4.5A3 3 0 0 0 .5 6.2C0 8.1 0 12 0 12s0 3.9.5 5.8a3 3 0 0 0 2.1 2.1c1.9.5 9.4.5 9.4.5s7.5 0 9.4-.5a3 3 0 0 0 2.1-2.1c.5-1.9.5-5.8.5-5.8s0-3.9-.5-5.8zM9.6 15.6V8.4l6.2 3.6-6.2 3.6z"/></svg>
                            </a>
                        </li>
                        <li>
                            <a class="external-link" href="#" target="_blank" rel="noopener noreferrer" aria-label="Facebook (opens in a new window)">
                                <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M13.5 21.5v-8h2.7l.4-3.2h-3.1V8.3c0-.9.3-1.5 1.6-1.5h1.7V3.9c-.3 0-1.3-.1-2.4-.1-2.4 0-4.1 1.5-4.1 4.2v2.3H7.5v3.2h2.8v8h3.2z"/></svg>
                            </a>
                        </li>
                        <li>
                            <a class="external-link" href="#" target="_blank" rel="noopener noreferrer" aria-label="Instagram (opens in a new window)">
                                <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false" style="fill:none;stroke:currentColor;stroke-width:1.8"><rect x="3.5" y="3.5" width="17" height="17" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.3" cy="6.7" r="1" style="fill:currentColor;stroke:none"/></svg>
                            </a>
                        </li>
                    </ul>

                    <div class="footer-logo-wrap">
                        <a class="footer-logo external-link" href="https://www.nic.in/" target="_blank" rel="noopener noreferrer">
                            <img src="/images/nic-logo-revise.png" alt="National Informatics Centre">
                            <span class="dbim-sr-only">(opens in a new window)</span>
                        </a>
                    </div>

                    <p class="footer-updated">Page Last Updated on: <time datetime="2026-09-28">28/09/2026</time></p>
                </div>

                {{-- ===== Ownership / copyright ===== --}}
                <div class="col-12">
                    <p class="footer-below-text mb-0">
                        Contents of the site provided by Department of Information &amp; Publicity, Government of Goa.
                        Copyright &copy; 2024. All rights reserved. Designed, Developed and Hosted by National Informatics Centre, Goa on the instructions of DIP.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <button type="button" class="footer-top-btn" id="dbimBackToTop" aria-label="Back to top">
        <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M12 19V5M5 12l7-7 7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </button>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Confirm before leaving the RTI Portal (kept from original footer)
        document.querySelectorAll('.dbim-footer a[target="_blank"]').forEach(function (link) {
            link.addEventListener('click', function (event) {
                if (link.getAttribute('href') === '#') { event.preventDefault(); return; }
                if (!confirm('You are about to leave the RTI Portal and visit an external website. Do you want to continue?')) {
                    event.preventDefault();
                }
            });
        });

        // Back to top
        var btn = document.getElementById('dbimBackToTop');
        var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        window.addEventListener('scroll', function () {
            btn.classList.toggle('is-visible', window.scrollY > 300);
        }, { passive: true });
        btn.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' });
        });
    });
</script>