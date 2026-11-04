{{-- ============================================================
     Accessibility tool (DBIM / GIGW 3.0)  -  ONE shared partial
     Save as : resources/views/partials/accessibility.blade.php

     Use it in ANY header with a single line, inside the utility icons row
     (where the old accessibility icon used to be):

         @include('partials.accessibility')

     It outputs:
       1. the header icon button (wrapped in <div class="tool">)
       2. the "Accessibility Controls" side panel (moved to <body> by the script,
          so header styles never touch it)
       3. all the logic: dark contrast, invert, saturation, text size,
          highlight links, hide images, large cursor, keyboard arrows

     Needs only: public/css/dbim.css (section "Accessibility Controls side panel")
                 and Bootstrap's offcanvas JS (already in compiled.bundle.min.js)

     Keyboard
       Enter/Space on the icon ... opens the panel (focus moves inside)
       Arrow keys / Home / End ... move between the panel buttons
       Esc or X .................. closes it and returns focus to the icon
     ============================================================ --}}

{{-- ================= 1. Header icon ================= --}}
<div class="tool">
    <button type="button" id="a11yMenuButton" data-bs-toggle="offcanvas"
            data-bs-target="#a11yPanel" aria-controls="a11yPanel"
            aria-haspopup="dialog"
            aria-label="Accessibility panel" title="Accessibility panel">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 64 64" aria-hidden="true" focusable="false" fill="none" style="display: flex; justify-content: center;">
            <g id="a11yIconGlyph">
                <path d="M24.2921 29.7656C21.4604 29.7656 18.7402 29.7704 16.0199 29.7608C15.4887 29.7608 14.9503 29.7419 14.4286 29.6565C12.2182 29.296 10.6482 27.4043 10.6672 25.1638C10.6861 22.9779 12.3652 21.041 14.5305 20.7305C14.9977 20.6641 15.4744 20.6451 15.9488 20.6451C26.6163 20.6403 37.2838 20.6356 47.9515 20.6522C48.6534 20.6522 49.3862 20.7091 50.0505 20.9154C52.0307 21.5294 53.2593 23.4166 53.1265 25.491C52.9982 27.4968 51.4521 29.2771 49.4526 29.6256C48.7294 29.7512 47.9798 29.7536 47.2425 29.7584C44.7025 29.7725 42.1601 29.7632 39.3638 29.7632C39.5203 32.2075 39.3779 34.6163 39.8785 36.8827C41.0523 42.1933 42.5465 47.4328 43.8817 52.7077C44.5102 55.1947 43.1987 57.6104 40.8555 58.4072C38.2041 59.308 35.4126 57.7765 34.7414 54.9837C33.8307 51.188 32.9817 47.3757 32.1043 43.5731C32.0686 43.4165 32.0166 43.2648 31.7985 43.1059C31.3595 45.0168 30.9185 46.9301 30.4774 48.8408C30.0126 50.8584 29.5785 52.8832 29.0758 54.8912C28.4046 57.5632 25.9072 59.0613 23.3008 58.4141C20.7893 57.7883 19.2785 55.2325 19.9402 52.6128C21.1403 47.8523 22.2834 43.0752 23.6376 38.3573C24.4439 35.548 24.24 32.7384 24.2921 29.7656ZM31.9929 23.6868C26.6875 23.6868 21.3822 23.6868 16.0745 23.6868C15.7187 23.6868 15.3582 23.6797 15.012 23.7366C14.2341 23.8599 13.7218 24.4573 13.7147 25.1946C13.7076 25.9462 14.1914 26.5318 14.9788 26.6717C15.2966 26.7285 15.6286 26.7237 15.9535 26.7237C18.7093 26.7285 21.4675 26.7096 24.2234 26.7333C25.8219 26.7475 27.195 27.7456 27.2473 29.1704C27.335 31.6219 27.5222 34.1349 27.0977 36.52C26.429 40.2659 25.2906 43.9285 24.3467 47.6272C23.8629 49.5189 23.3483 51.4013 22.881 53.2981C22.613 54.3837 23.1443 55.2563 24.138 55.4885C25.0582 55.7043 25.8859 55.1379 26.1728 54.0923C26.1966 54.0069 26.2037 53.9168 26.2227 53.8315C27.2899 49.1587 28.3523 44.4859 29.4385 39.8176C29.5643 39.2795 29.7374 38.7128 30.0433 38.2672C30.5699 37.5037 31.3761 37.1933 32.3035 37.3664C33.3779 37.5677 34.0062 38.2435 34.2505 39.3032C35.3534 44.1184 36.4633 48.9333 37.571 53.7485C37.5899 53.8339 37.5945 53.9264 37.6158 54.0117C37.8958 55.1211 38.7259 55.7187 39.6697 55.4957C40.6942 55.2539 41.1947 54.3624 40.9054 53.2173C40.1987 50.4365 39.4777 47.6579 38.7521 44.8816C37.4878 40.0477 36.0009 35.2659 36.4419 30.1448C36.6363 27.8712 37.623 26.7285 39.9307 26.7213C42.5678 26.7144 45.2075 26.7213 47.8449 26.7192C48.1411 26.7192 48.4401 26.7237 48.7294 26.6741C49.5974 26.527 50.1334 25.8798 50.0859 25.0785C50.0385 24.2985 49.4907 23.7722 48.6227 23.6963C48.3286 23.6702 48.0297 23.6797 47.7334 23.6797C42.4873 23.6797 37.2411 23.6797 31.9929 23.6797V23.6868Z" fill="#162F6A"/>
                <path d="M31.6523 17.6787C28.1841 17.6715 25.4626 14.9253 25.4796 11.4504C25.4965 8.07201 28.3001 5.3138 31.6982 5.33311C35.0867 5.35482 37.8323 8.13473 37.8251 11.5397C37.8179 14.9519 35.0699 17.6859 31.6523 17.6787ZM34.7339 11.4938C34.7219 9.7974 33.2958 8.4002 31.6041 8.42433C29.9435 8.44844 28.5731 9.83841 28.5707 11.4962C28.5659 13.2313 29.9195 14.5826 31.6547 14.5802C33.395 14.5778 34.7483 13.2216 34.7339 11.4938Z" fill="#162F6A"/>
            </g>
        </svg>
    </button>
</div>

{{-- ================= 2. Side panel =================
     No backdrop + page scroll allowed, so changes can be seen live. --}}
<div class="offcanvas offcanvas-end dbim-a11y" tabindex="-1" id="a11yPanel"
     aria-labelledby="a11yPanelTitle" data-bs-backdrop="false" data-bs-scroll="true">

    <div class="offcanvas-header">
        <h2 class="offcanvas-title d-flex align-items-center gap-2" id="a11yPanelTitle">
            <svg viewBox="0 0 64 64" fill="none" aria-hidden="true" focusable="false"><use href="#a11yIconGlyph"></use></svg>
            Accessibility Controls
        </h2>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close accessibility panel"></button>
    </div>

    <div class="offcanvas-body">

        {{-- Reset buttons --}}
        <div class="d-flex flex-wrap gap-2 mb-3">
            <button type="button" class="a11y-btn a11y-reset" id="a11yResetFilter">Reset Filter</button>
            <button type="button" class="a11y-btn a11y-reset" id="fontReset">Resize Text</button>
        </div>

        {{-- Option buttons --}}
        <div class="row g-2" role="group" aria-label="Accessibility options">
            <div class="col-6">
                <button type="button" class="a11y-btn" id="themeToggle" aria-pressed="false">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="12" cy="12" r="3"/><path d="M12 9a3 3 0 0 0 0 6z" fill="currentColor"/></svg>
                    <span>Dark Contrast</span>
                </button>
            </div>
            <div class="col-6">
                <button type="button" class="a11y-btn" id="a11yInvert" aria-pressed="false">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="9"/><path d="M12 3a9 9 0 0 1 0 18z" fill="currentColor"/></svg>
                    <span>Invert</span>
                </button>
            </div>
            <div class="col-6">
                <button type="button" class="a11y-btn" id="a11ySaturation" aria-pressed="false">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M12 3s6 6.5 6 11a6 6 0 0 1-12 0c0-4.5 6-11 6-11z"/><path d="M12 3s6 6.5 6 11a6 6 0 0 1-6 6z" fill="currentColor"/></svg>
                    <span>Saturation</span>
                </button>
            </div>
            <div class="col-6">
                <button type="button" class="a11y-btn" id="fontIncrease">
                    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><text x="12" y="17" text-anchor="middle" font-size="14" font-weight="700" fill="currentColor">A+</text></svg>
                    <span>Text Size Increase</span>
                </button>
            </div>
            <div class="col-6">
                <button type="button" class="a11y-btn" id="fontDecrease">
                    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><text x="12" y="17" text-anchor="middle" font-size="14" font-weight="700" fill="currentColor">A-</text></svg>
                    <span>Text Size Decrease</span>
                </button>
            </div>
            <div class="col-6">
                <button type="button" class="a11y-btn" id="a11yLinks" aria-pressed="false">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M10 13a5 5 0 0 0 7 0l3-3a5 5 0 0 0-7-7l-1 1"/><path d="M14 11a5 5 0 0 0-7 0l-3 3a5 5 0 0 0 7 7l1-1"/></svg>
                    <span>Highlight Links</span>
                </button>
            </div>
            <div class="col-6">
                <button type="button" class="a11y-btn" id="a11yImages" aria-pressed="false">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="1.5"/><path d="M21 15l-5-5L5 21"/><line x1="3" y1="3" x2="21" y2="21"/></svg>
                    <span>Hide Images</span>
                </button>
            </div>
            <div class="col-6">
                <button type="button" class="a11y-btn" id="a11yCursor" aria-pressed="false">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 3l14 7-6 2-2 6z"/></svg>
                    <span id="a11yCursorLabel">Large Cursor</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ================= 3. Logic ================= --}}
<script>
    (function () {
        /* Safety: never run twice on one page */
        if (window.__dbimA11yReady) { return; }
        window.__dbimA11yReady = true;

        var root = document.documentElement;

        /* Move the panel to <body>: header CSS (icon fill etc.) must not reach it,
           and it is never clipped by the sticky header */
        var panel = document.getElementById('a11yPanel');
        if (panel && panel.parentNode !== document.body) { document.body.appendChild(panel); }

        /* Screen-reader announcements: use the header's #dbimStatus, or create one */
        var status = document.getElementById('dbimStatus');
        if (!status) {
            status = document.createElement('div');
            status.id = 'dbimStatus';
            status.className = 'visually-hidden';
            status.setAttribute('role', 'status');
            status.setAttribute('aria-live', 'polite');
            status.setAttribute('aria-atomic', 'true');
            document.body.appendChild(status);
        }
        function announce(msg) {
            status.textContent = '';
            setTimeout(function () { status.textContent = msg; }, 50);
        }

        var store = {
            get: function (k) { try { return localStorage.getItem(k); } catch (e) { return null; } },
            set: function (k, v) { try { localStorage.setItem(k, v); } catch (e) {} }
        };

        /* ---- Theme: standard / DBIM dark contrast ---- */
        var toggle = document.getElementById('themeToggle');

        function applyTheme(theme, speak) {
            var dark = theme === 'dark';
            if (dark) { root.setAttribute('data-theme', 'dark'); }
            else      { root.removeAttribute('data-theme'); }
            root.setAttribute('data-bs-theme', dark ? 'dark' : 'light');
            toggle.setAttribute('aria-pressed', dark ? 'true' : 'false');
            store.set('dbim-theme', theme);
            if (speak) { announce(dark ? 'Dark contrast on' : 'Dark contrast off'); }
        }
        applyTheme(store.get('dbim-theme') === 'dark' ? 'dark' : 'light', false);
        toggle.addEventListener('click', function () {
            applyTheme(root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark', true);
        });

        /* ---- Text size: A+ / A- / Resize Text (reset) ---- */
        var size = parseInt(store.get('dbim-font-size') || '100', 10);
        function applySize(s, speak) {
            size = Math.min(130, Math.max(80, s));
            root.style.fontSize = size + '%';
            store.set('dbim-font-size', size);
            if (speak) { announce('Text size ' + size + ' percent'); }
        }
        applySize(size, false);

        var fDec = document.getElementById('fontDecrease');
        var fRes = document.getElementById('fontReset');
        var fInc = document.getElementById('fontIncrease');
        fInc.addEventListener('click', function () { applySize(size + 10, true); });
        fDec.addEventListener('click', function () { applySize(size - 10, true); });
        fRes.addEventListener('click', function () { applySize(100, true); });

        /* ---- Invert, saturation, highlight links, hide images, cursor ---- */
        var opts = { invert: false, sat: 0, links: false, images: false, cursor: false };
        try {
            var saved = JSON.parse(store.get('dbim-a11y-opts') || '{}');
            Object.keys(opts).forEach(function (k) { if (k in saved) { opts[k] = saved[k]; } });
        } catch (e) {}

        var satFilter = ['', 'saturate(0.5)', 'saturate(2)', 'grayscale(1)'];
        var satText   = ['Normal saturation', 'Low saturation', 'High saturation', 'Monochrome'];
        var elResetFilter = document.getElementById('a11yResetFilter');
        var elInvert = document.getElementById('a11yInvert');
        var elSat    = document.getElementById('a11ySaturation');
        var elLinks  = document.getElementById('a11yLinks');
        var elImages = document.getElementById('a11yImages');
        var elCursor = document.getElementById('a11yCursor');
        var elCursorLabel = document.getElementById('a11yCursorLabel');

        function pressed(el, on) { el.setAttribute('aria-pressed', on ? 'true' : 'false'); }

        function applyOpts(msg) {
            var f = [];
            if (opts.invert) { f.push('invert(1)'); }
            if (satFilter[opts.sat]) { f.push(satFilter[opts.sat]); }
            root.style.filter = f.join(' ');
            root.classList.toggle('dbim-highlight-links', opts.links);
            root.classList.toggle('dbim-hide-images', opts.images);
            root.classList.toggle('dbim-big-cursor', opts.cursor);
            pressed(elInvert, opts.invert);
            pressed(elSat, opts.sat > 0);
            pressed(elLinks, opts.links);
            pressed(elImages, opts.images);
            pressed(elCursor, opts.cursor);
            elCursorLabel.textContent = opts.cursor ? 'Default Cursor' : 'Large Cursor';
            store.set('dbim-a11y-opts', JSON.stringify(opts));
            if (msg) { announce(msg); }
        }
        applyOpts(null);

        elInvert.addEventListener('click', function () { opts.invert = !opts.invert; applyOpts(opts.invert ? 'Invert colours on' : 'Invert colours off'); });
        elSat.addEventListener('click',    function () { opts.sat = (opts.sat + 1) % satFilter.length; applyOpts(satText[opts.sat]); });
        elLinks.addEventListener('click',  function () { opts.links = !opts.links; applyOpts(opts.links ? 'Highlight links on' : 'Highlight links off'); });
        elImages.addEventListener('click', function () { opts.images = !opts.images; applyOpts(opts.images ? 'Images hidden' : 'Images shown'); });
        elCursor.addEventListener('click', function () { opts.cursor = !opts.cursor; applyOpts(opts.cursor ? 'Large cursor on' : 'Default cursor'); });

        /* Reset Filter: dark contrast + invert + saturation back to normal */
        elResetFilter.addEventListener('click', function () {
            applyTheme('light', false);
            opts.invert = false;
            opts.sat = 0;
            applyOpts('Filters reset');
        });

        /* ---- Keyboard inside the panel: arrows / Home / End ----
             Row 1 = Reset Filter, Resize Text. Then a 2-column grid. */
        var panelBtns = [elResetFilter, fRes, toggle, elInvert, elSat, fInc, fDec, elLinks, elImages, elCursor];
        panelBtns.forEach(function (btn, i) {
            btn.addEventListener('keydown', function (e) {
                var last = panelBtns.length - 1, n = null;
                if (e.key === 'ArrowRight')     { n = Math.min(i + 1, last); }
                else if (e.key === 'ArrowLeft') { n = Math.max(i - 1, 0); }
                else if (e.key === 'ArrowDown') { n = i <= 1 ? i + 2 : Math.min(i + 2, last); }
                else if (e.key === 'ArrowUp')   { n = i <= 1 ? i : i - 2; }
                else if (e.key === 'Home')      { n = 0; }
                else if (e.key === 'End')       { n = last; }
                if (n !== null) { e.preventDefault(); panelBtns[n].focus(); }
            });
        });

        /* Move focus into the panel when it opens */
        panel.addEventListener('shown.bs.offcanvas', function () {
            elResetFilter.focus();
        });
    })();
</script>