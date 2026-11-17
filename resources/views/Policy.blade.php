{{-- ============================================================
     Website Policies  (DBIM / GIGW 3.0, WCAG 2.1 AA)
     Same look as Search, Contact, Login, About Us and Sitemap: grey band
     outside, ONE white card inside, main heading with the blue bar.
     The four policies are shown as TABS (Disclaimer, Copyright Policy,
     Hyperlink Policy, Privacy Statement).
     Works without JavaScript: the tab bar is a row of in-page links and all
     four policies are shown one after the other. With JavaScript the bar
     becomes real tabs (arrow keys, Home / End) and one policy shows at a time.
     A link such as /Policy#policy-privacy opens that tab directly.
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     Styles: section 50 (frame, heading) + section 57 (Policy tabs) in public/css/dbim.css
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Policy | RTI Online, Government of Goa')

@section('content')
<div class="dbim-sd">
    <section class="dbim-sd-section" aria-labelledby="policyHeading">
        <div class="dbim-sd-wrap">
            <div class="dbim-sd-card dbim-pl-card">

                <h1 id="policyHeading" class="dbim-sd-heading">Website Policies</h1>

                <div id="policyTabs" class="dbim-pl-tabs">

                    {{-- Tab bar (in-page links; script turns them into tabs) --}}
                    <nav aria-label="Website policies">
                        <ul class="dbim-pl-tablist">
                            <li><a href="#policy-disclaimer">Disclaimer</a></li>
                            <li><a href="#policy-copyright">Copyright Policy</a></li>
                            <li><a href="#policy-hyperlink">Hyperlink Policy</a></li>
                            <li><a href="#policy-privacy">Privacy Statement</a></li>
                        </ul>
                    </nav>

                    <section id="policy-disclaimer" class="dbim-pl-panel">
                        <h2 class="dbim-pl-heading">Disclaimer</h2>
                        <p class="dbim-pl-text">
                            This Website is designed &amp; developed by National Informatics Centre, Goa and maintained
                            by Department of Information and Publicity, Government of Goa.
                        </p>
                        <p class="dbim-pl-text">
                            The contents of this website are for information purposes only, enabling public to have a
                            quick and an easy access to information. We are taking every effort to provide accurate and
                            updated information. However, it is likely that the details such as telephone numbers, name
                            of the officer holding a post, etc may change prior to their updating in the web site.
                            Hence, we do not assume any legal liability on the completeness, accuracy or usefulness of
                            the contents provided in this web site.
                        </p>
                        <p class="dbim-pl-text">
                            The links are provided to other external sites in some documents. We are not responsible for
                            the accuracy of the contents in those sites. The links given to external sites do not
                            constitute an endorsement of information, products or services offered by these sites.
                        </p>
                        <p class="dbim-pl-text">
                            Despite our best efforts, we do not guarantee that the documents in this site are free from
                            infection by computer viruses etc. We welcome your suggestions to improve our site and
                            request that any error found may kindly be brought to our notice.
                        </p>
                    </section>

                    <section id="policy-copyright" class="dbim-pl-panel">
                        <h2 class="dbim-pl-heading">Copyright Policy</h2>
                        <p class="dbim-pl-text">
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

                    <section id="policy-hyperlink" class="dbim-pl-panel">
                        <h2 class="dbim-pl-heading">Hyperlink Policy</h2>
                        <p class="dbim-pl-text">
                            We do not object to you linking directly to the information that is hosted on our site and
                            no prior permission is required for the same. However, we would like you to inform us about
                            any links provided to our site so that you can be informed of any changes or updations
                            therein. Also, we do not permit our pages to be loaded into frames on your site. Our
                            Department's pages must load into a newly opened browser window of the user.
                        </p>
                    </section>

                    <section id="policy-privacy" class="dbim-pl-panel">
                        <h2 class="dbim-pl-heading">Privacy Statement</h2>
                        <p class="dbim-pl-text">
                            As a general rule, this web site does not collect Personal Information about you when you
                            visit the site. You can generally visit this site without revealing personal information,
                            unless you choose to provide such information. Any personal information collected shall be
                            used only for the stated purpose and shall NOT be shared with any other department or
                            organization (public/private).
                        </p>
                        <p class="dbim-pl-text">
                            This site may contain links to non-Government sites whose data protection and privacy
                            practices may differ from ours. We are not responsible for the content and privacy
                            practices of these other websites and encourage you to consult the privacy notices of those
                            sites.
                        </p>
                        <div class="dbim-pl-thanks">
                            <p>Thanks for visiting our site.</p>
                            <p><strong>Web Site Administration Team, Goa</strong></p>
                        </div>
                    </section>

                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    var root = document.getElementById('policyTabs');
    if (!root) { return; }

    var list = root.querySelector('.dbim-pl-tablist');
    var tabs = Array.prototype.slice.call(list.querySelectorAll('a'));
    var panels = tabs.map(function (t) {
        return document.getElementById(t.getAttribute('href').slice(1));
    });
    if (!tabs.length || panels.indexOf(null) !== -1) { return; }

    /* Upgrade the links to real tabs (WAI-ARIA tabs pattern) */
    root.classList.add('is-tabs');
    list.setAttribute('role', 'tablist');
    tabs.forEach(function (tab, i) {
        tab.parentNode.setAttribute('role', 'presentation');
        tab.setAttribute('role', 'tab');
        tab.id = 'tab-' + panels[i].id;
        tab.setAttribute('aria-controls', panels[i].id);
        panels[i].setAttribute('role', 'tabpanel');
        panels[i].setAttribute('aria-labelledby', tab.id);
        panels[i].setAttribute('tabindex', '0');
    });

    function select(index, moveFocus, updateHash) {
        tabs.forEach(function (tab, j) {
            var on = (j === index);
            tab.setAttribute('aria-selected', on ? 'true' : 'false');
            tab.tabIndex = on ? 0 : -1;
            panels[j].hidden = !on;
        });
        if (moveFocus) { tabs[index].focus(); }
        if (updateHash && window.history && history.replaceState) {
            history.replaceState(null, '', '#' + panels[index].id);
        }
    }

    tabs.forEach(function (tab, i) {
        tab.addEventListener('click', function (e) {
            e.preventDefault();
            select(i, false, true);
        });
        tab.addEventListener('keydown', function (e) {
            var next = null;
            if (e.key === 'ArrowRight')      { next = (i + 1) % tabs.length; }
            else if (e.key === 'ArrowLeft')  { next = (i - 1 + tabs.length) % tabs.length; }
            else if (e.key === 'Home')       { next = 0; }
            else if (e.key === 'End')        { next = tabs.length - 1; }
            if (next !== null) {
                e.preventDefault();
                select(next, true, true);
            }
        });
    });

    /* Open the tab named in the address (for example /Policy#policy-privacy) */
    var start = 0;
    if (window.location.hash) {
        var wanted = window.location.hash.slice(1);
        for (var k = 0; k < panels.length; k++) {
            if (panels[k].id === wanted) { start = k; break; }
        }
    }
    select(start, false, false);

    window.addEventListener('hashchange', function () {
        var id = window.location.hash.slice(1);
        for (var k = 0; k < panels.length; k++) {
            if (panels[k].id === id) { select(k, false, false); break; }
        }
    });
})();
</script>
@endpush