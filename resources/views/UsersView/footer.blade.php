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
                            <li class="col"><a href="/related-links" target="_self">Related Links</a></li>
                            <!-- <li class="col"><a class="external-link" href="https://www.goa.gov.in/" target="_blank" rel="noopener noreferrer">Govt. of Goa<span class="dbim-sr-only"> (opens in a new window)</span></a></li>
                            <li class="col"><a class="external-link" href="https://www.india.gov.in/" target="_blank" rel="noopener noreferrer">National Portal of India<span class="dbim-sr-only"> (opens in a new window)</span></a></li>
                            <li class="col"><a class="external-link" href="https://gsic.goa.gov.in/" target="_blank" rel="noopener noreferrer">Complaint &amp; Second Appeal to SIC<span class="dbim-sr-only"> (opens in a new window)</span></a></li>-->
                            <li class="col"><a href="/Help" target="_self">Help</a></li>
                            <li class="col"><a href="/FAQ" target="_self">FAQ</a></li>
                            <li class="col"><a href="/Policy" target="_self">Website Policies</a></li>
                            <li class="col"><a href="/AboutUs" target="_self">About Us</a></li>
                            <li class="col"><a href="/Sitemap" target="_self">Sitemap</a></li>
                            <li class="col"><a href="/feedback" target="_self">Feedback</a></li>
                        </ul>
                    </nav>

                    <div class="footer-info">
                        <p class="footer-email">
                            <svg aria-hidden="true" focusable="false" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                            <span>RTI Support Email Id: @obfuscateEmail('support.rtionline@goa.gov.in')</span>
                        </p>
                        <div class="footer-stats" role="group" aria-label="Visitor statistics">
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
                        <!-- <li>
                            <a class="external-link" href="#" target="_blank" rel="noopener noreferrer" aria-label="X (formerly Twitter) (opens in a new window)">
                                <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </a>
                        </li>
                        <li>
                            <a class="external-link" href="#" target="_blank" rel="noopener noreferrer" aria-label="YouTube (opens in a new window)">
                                <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.6 12 3.6 12 3.6s-7.5 0-9.4.5A3 3 0 0 0 .5 6.2C0 8.1 0 12 0 12s0 3.9.5 5.8a3 3 0 0 0 2.1 2.1c1.9.5 9.4.5 9.4.5s7.5 0 9.4-.5a3 3 0 0 0 2.1-2.1c.5-1.9.5-5.8.5-5.8s0-3.9-.5-5.8zM9.6 15.6V8.4l6.2 3.6-6.2 3.6z"/></svg>
                            </a>
                        </li> -->
                        <li>
                            <a class="external-link" href="https://www.facebook.com/IPGoa/" target="_blank" rel="noopener noreferrer" aria-label="Facebook (opens in a new window)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32 " viewBox="0 0 65 65" fill="none" style="display: flex; justify-content: center;">
                                    <g clip-path="url(#clip0_2690_16455)">
                                        <path d="M35.8501 0.443442C30.0491 1.68828 25.7794 5.55971 24.2358 11.0121C23.5511 13.452 23.4017 14.7839 23.4017 18.73V21.6554H20.3146C16.6174 21.6554 16.1942 21.7425 15.2979 22.6388C14.3393 23.5973 14.3145 23.7343 14.3145 29.697C14.3145 32.834 14.3642 35.0747 14.4514 35.4357C14.6257 36.2449 15.136 36.942 15.8705 37.4025L16.468 37.776L19.9287 37.8133L23.4017 37.8507L23.4266 49.826L23.464 61.8013L23.7378 62.3117C24.0491 62.9092 24.7711 63.5689 25.3437 63.8055C25.9536 64.0544 37.4061 64.0544 38.0161 63.8055C38.5887 63.5689 39.3107 62.9092 39.6219 62.3117L39.8958 61.8013L39.9331 49.8135L39.958 37.8382H43.8544C46.232 37.8382 47.925 37.7884 48.2237 37.7013C48.8212 37.5395 49.7549 36.6805 50.0536 36.0332C50.2652 35.5726 50.2901 35.0872 50.2901 29.7468V23.9583L49.9167 23.2986C49.4561 22.5019 48.7466 21.9666 47.9125 21.7923C47.5515 21.7052 45.7589 21.6554 43.6178 21.6554H39.9331L39.9829 19.0039C40.0078 17.5599 40.0701 16.2777 40.1323 16.1657C40.3439 15.6927 40.7796 15.6304 44.6884 15.5557C48.2735 15.4935 48.51 15.481 48.9831 15.2196C49.5059 14.9458 49.954 14.4976 50.3648 13.8752C50.5889 13.5266 50.6014 13.1905 50.6387 8.14896C50.6636 5.19871 50.6387 2.6219 50.6014 2.41028C50.5018 1.83766 49.9042 1.01607 49.2943 0.60527L48.759 0.244268L42.7091 0.256717C38.0285 0.256717 36.4725 0.30651 35.8501 0.443442ZM46.8046 7.83775V11.6967H44.0784C40.9663 11.6967 39.8335 11.8461 38.6261 12.4436C37.3065 13.0909 36.4849 14.3233 36.2235 16.0288C36.1364 16.5267 36.099 18.3939 36.1239 20.5475L36.1613 24.2322L36.4974 24.6679C37.1323 25.5019 37.1945 25.5144 42.0618 25.5144H46.4312V29.7468V33.9668L41.9747 34.0042L37.5057 34.0415L37.0576 34.3527C36.7961 34.527 36.4849 34.888 36.348 35.1743C36.099 35.6598 36.099 35.9585 36.099 47.8965V60.1208H31.6799H27.2607V47.9338C27.2607 39.6308 27.2234 35.6224 27.1238 35.3859C26.9371 34.8631 26.2773 34.2407 25.7669 34.1037C25.5304 34.0415 23.7254 33.9793 21.7586 33.9793H18.1734V29.7468V25.5144H21.9204C26.103 25.5144 26.2275 25.4895 26.8624 24.6679L27.1985 24.2322L27.2732 19.4272C27.3479 14.2984 27.4101 13.7507 28.0823 11.7216C28.8292 9.48093 30.1238 7.63858 31.8417 6.3564C33.4724 5.16136 35.4144 4.38956 37.7173 4.05346C37.8916 4.02856 40.0078 4.00367 42.4228 3.99122L46.8046 3.97877V7.83775Z" fill="#FFF"/>
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_2690_16455">
                                            <rect width="63.7355" height="63.86" fill="white" transform="translate(0.621094 0.25)"/>
                                        </clipPath>
                                    </defs>
                                </svg>
                            </a>
                        </li>
                        <!--<li>
                            <a class="external-link" href="#" target="_blank" rel="noopener noreferrer" aria-label="Instagram (opens in a new window)">
                                <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false" style="fill:none;stroke:currentColor;stroke-width:1.8"><rect x="3.5" y="3.5" width="17" height="17" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.3" cy="6.7" r="1" style="fill:currentColor;stroke:none"/></svg>
                            </a>
                        </li> -->
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