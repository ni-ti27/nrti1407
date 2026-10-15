{{-- resources/views/UsersView/partials/dbim-form-scripts.blade.php
     Shared by the RTI request form and the profile form. No jQuery.
     Include inside @push('scripts'), before the page's own script.

     DbimForm.validate(scope, display)  -> invalid controls (display = write messages)
     DbimForm.summary(bad) / hideSummary()  -> #errorSummary with links to the fields
     DbimForm.bind(form, onChange)      -> live re-check
     DbimForm.stepper(form, onShow)     -> multi-step helper
     DbimForm.getJSON(url)              -> fetch helper
     DbimForm.resetLocation(), syncEducation()

     A control is any element with data-validate (input, select, textarea or a
     <fieldset> of radios). Its message box is #<id>Error.
       data-required="true"  data-msg  data-pattern  data-pattern-msg
       el.dataCheck(el)  optional function that returns a message
     Controls inside [data-conditional][hidden] are ignored. --}}
<script>
(function (w, d) {
    'use strict';

    var EMAIL = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    var changeHandler = null;
    var api = {};

    function toArray(list) { return [].slice.call(list); }
    function notify() { if (typeof changeHandler === 'function') { changeHandler(); } }

    /* ---------- validation ---------- */
    function errorBox(c) { return d.getElementById(c.id + 'Error'); }
    function messageOf(c) { var b = errorBox(c); return b ? b.textContent.trim() : ''; }

    function isSkipped(c) { return c.disabled || !!c.closest('[data-conditional][hidden]'); }

    function isEmpty(c) {
        if (c.tagName === 'FIELDSET') { return !c.querySelector('input:checked'); }
        return String(c.value || '').trim() === '';
    }

    function evaluate(c) {
        if (isSkipped(c)) { return ''; }
        if (isEmpty(c)) {
            return c.getAttribute('data-required') === 'true'
                ? (c.getAttribute('data-msg') || 'This field is required.') : '';
        }
        if (c.tagName === 'FIELDSET') { return ''; }

        var value = String(c.value).trim();
        var pattern = c.getAttribute('data-pattern');
        if (pattern && !new RegExp(pattern).test(value)) {
            return c.getAttribute('data-pattern-msg') || 'Enter a valid value.';
        }
        if (c.type === 'email' && !EMAIL.test(value)) {
            return c.getAttribute('data-pattern-msg') || 'Enter a valid email address.';
        }
        if (typeof c.dataCheck === 'function') { return c.dataCheck(c) || ''; }
        return '';
    }

    function show(c, message) {
        var box = errorBox(c);
        if (box) {
            box.textContent = message || '';
            box.classList.toggle('d-block', !!message);
        }
        if (c.tagName !== 'FIELDSET') {
            c.classList.toggle('is-invalid', !!message);
            c.setAttribute('aria-invalid', message ? 'true' : 'false');
        }
    }

    function validate(scope, display) {
        var bad = [];
        toArray(scope.querySelectorAll('[data-validate]')).forEach(function (c) {
            var message = evaluate(c);
            if (display) { show(c, message); }
            if (message) { bad.push(c); }
        });
        return bad;
    }

    function clearAll(scope) {
        toArray(scope.querySelectorAll('[data-validate]')).forEach(function (c) { show(c, ''); });
    }

    function focusControl(c) {
        var target = c.tagName === 'FIELDSET'
            ? (c.querySelector('input:checked') || c.querySelector('input')) : c;
        if (target) { target.focus(); }
    }

    /* ---------- error summary ---------- */
    function showSummary(bad) {
        var box = d.getElementById('errorSummary');
        var list = d.getElementById('errorSummaryList');
        if (!box || !list || !bad.length) { return; }
        list.textContent = '';
        bad.forEach(function (c) {
            var li = d.createElement('li');
            var a = d.createElement('a');
            a.href = '#' + c.id;
            a.textContent = messageOf(c);
            a.addEventListener('click', function (e) {
                e.preventDefault();
                if (typeof api.reveal === 'function') { api.reveal(c); }
                focusControl(c);
            });
            li.appendChild(a);
            list.appendChild(li);
        });
        box.hidden = false;
        box.focus();
    }

    function hideSummary() {
        var box = d.getElementById('errorSummary');
        if (box) { box.hidden = true; }
    }

    /* Errors show when a filled field is left, and clear as soon as it is fixed.
       "Required" errors show on Next / Submit. */
    function bind(form, onChange) {
        changeHandler = onChange;
        function control(t) { return t && t.closest ? t.closest('[data-validate]') : null; }

        function recheck(e) {
            var c = control(e.target);
            if (c && messageOf(c)) { show(c, evaluate(c)); }
            notify();
        }
        form.addEventListener('input', recheck);
        form.addEventListener('change', recheck);
        form.addEventListener('focusout', function (e) {
            var c = control(e.target);
            if (c && !isEmpty(c)) { show(c, evaluate(c)); }
            notify();
        });

        toArray(form.querySelectorAll('.is-invalid')).forEach(function (el) {
            el.setAttribute('aria-invalid', 'true');
        });
    }

    /* ---------- steps ---------- */
    function stepper(form, onShow) {
        var steps = toArray(form.querySelectorAll('[data-step]'));
        var counter = d.getElementById('stepCount');
        var nav = d.getElementById('stepNav');
        var s = { steps: steps, current: 0 };

        /* numbered stepper: 1 ---- 2 ---- 3 */
        var items = steps.map(function (el, k) {
            var li = d.createElement('li');
            var num = d.createElement('span');
            li.className = 'dbim-stepper__item';
            num.className = 'dbim-stepper__num';
            num.textContent = String(k + 1);
            li.appendChild(num);
            if (nav) { nav.appendChild(li); }
            return li;
        });

        s.stepOf = function (c) {
            for (var i = 0; i < steps.length; i++) { if (steps[i].contains(c)) { return i; } }
            return 0;
        };

        s.show = function (index, focusTitle) {
            s.current = index;
            steps.forEach(function (el, k) { el.hidden = k !== index; });
            var title = steps[index].querySelector('h2');
            counter.textContent = 'Step ' + (index + 1) + ' of ' + steps.length + ': ' + title.textContent.trim();
            items.forEach(function (li, k) {
                li.classList.toggle('is-done', k < index);
                li.classList.toggle('is-current', k === index);
            });
            hideSummary();
            if (typeof onShow === 'function') { onShow(index); }
            if (focusTitle) { title.focus(); }
        };

        /* true when the current step is complete; otherwise shows the summary */
        s.validateCurrent = function () {
            var bad = validate(steps[s.current], true);
            if (bad.length) { showSummary(bad); return false; }
            hideSummary();
            return true;
        };

        api.reveal = function (c) { s.show(s.stepOf(c), false); };
        return s;
    }

    /* ---------- location dropdowns ---------- */
    var initial = {
        district: @json((string) old('district', $profileData->district ?? '')),
        taluka:   @json((string) old('taluka',   $profileData->taluka   ?? '')),
        village:  @json((string) old('village',  $profileData->village  ?? ''))
    };
    var pending = Object.assign({}, initial);

    function getJSON(url) {
        return fetch(url, {
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        }).then(function (r) {
            if (!r.ok) { throw new Error('HTTP ' + r.status); }
            return r.json();
        });
    }

    function option(value, text) {
        var o = d.createElement('option');
        o.value = value;
        o.textContent = text;
        return o;
    }
    function reset(sel, text) { sel.textContent = ''; sel.appendChild(option('', text)); }
    function others(sel) { sel.textContent = ''; sel.appendChild(option('0', 'Others')); }

    function fill(sel, rows, key, text) {
        reset(sel, text);
        Object.keys(rows).forEach(function (k) {
            var row = rows[k];
            var o = option(row.id, row.name);
            if (String(row.id) === pending[key]) { o.selected = true; }
            sel.appendChild(o);
        });
        pending[key] = '';
    }

    function fail(err) { console.error('Dropdown request failed', err); }

    function loadDistricts() {
        var state = d.getElementById('state').value, sel = d.getElementById('district');
        if (state === '0') { others(sel); loadTalukas(); }
        else if (state) {
            getJSON('/districtDropdown/' + state).then(function (rows) {
                fill(sel, rows, 'district', 'Select district');
                loadTalukas();
            }).catch(fail);
        } else { reset(sel, 'Select district'); loadTalukas(); }
        notify();
    }

    function loadTalukas() {
        var district = d.getElementById('district').value, sel = d.getElementById('taluka');
        if (district === '0') { others(sel); loadVillages(); }
        else if (district) {
            getJSON('/talukaDropdown/' + district).then(function (rows) {
                fill(sel, rows, 'taluka', 'Select taluka');
                loadVillages();
            }).catch(fail);
        } else { reset(sel, 'Select taluka'); loadVillages(); }
        notify();
    }

    function loadVillages() {
        var taluka = d.getElementById('taluka').value, sel = d.getElementById('village');
        if (taluka === '0') { others(sel); }
        else if (taluka) {
            getJSON('/villageDropdown/' + taluka).then(function (rows) {
                fill(sel, rows, 'village', 'Select village');
                notify();
            }).catch(fail);
        } else { reset(sel, 'Select village'); }
        notify();
    }

    /* ---------- education > qualification ---------- */
    function syncEducation() {
        var wrap = d.getElementById('qualification-wrap');
        if (!wrap) { return; }
        var checked = d.querySelector('input[name="edu_status"]:checked');
        wrap.hidden = !(checked && checked.value === 'educated');
    }

    function resetLocation() {
        pending = Object.assign({}, initial);
        syncEducation();
        loadDistricts();
    }

    api.validate = validate;
    api.clearAll = clearAll;
    api.focusControl = focusControl;
    api.summary = showSummary;
    api.hideSummary = hideSummary;
    api.bind = bind;
    api.stepper = stepper;
    api.getJSON = getJSON;
    api.resetLocation = resetLocation;
    api.syncEducation = syncEducation;
    w.DbimForm = api;

    if (d.getElementById('state')) {
        d.getElementById('state').addEventListener('change', loadDistricts);
        d.getElementById('district').addEventListener('change', loadTalukas);
        d.getElementById('taluka').addEventListener('change', loadVillages);
        toArray(d.querySelectorAll('input[name="edu_status"]')).forEach(function (r) {
            r.addEventListener('change', syncEducation);
        });
        syncEducation();
        loadDistricts();
    }
})(window, document);
</script>