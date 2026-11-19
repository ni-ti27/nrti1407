{{-- ============================================================
     Online RTI Second Appeal - Request Type Check Form
     (DBIM / GIGW 3.0, keyboard accessible)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     No inline styles, no auto-hiding messages.
     Styles: section 65 in public/css/dbim.css (.dbim-fa-*)
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Online RTI Second Appeal Form | RTI Online, Government of Goa')

@section('content')
<div class="container pt-4 pb-5">

    @include('partials.flash-messages')

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <section class="dbim-fa-card" aria-labelledby="pageTitle">

                <h1 id="pageTitle" class="dbim-fa-title">Online RTI Second Appeal Form</h1>
                <p class="dbim-fa-legend">
                    Fields marked with (<span class="dbim-required" aria-hidden="true">*</span><span class="dbim-sr-only">asterisk</span>) are required.
                </p>

                <h2 id="formTitle" class="dbim-fa-card__title">Select how your First Appeal was filed</h2>

                {{-- Error summary: filled by script or by the server; receives focus --}}
                <div id="errorSummary" class="alert alert-danger" role="alert" tabindex="-1"
                     @if(!$errors->any()) hidden @endif>
                    <p class="fw-bold mb-2">Please correct the following:</p>
                    <ul class="mb-0" id="errorSummaryList">
                        @foreach($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>

                <form id="requestTypeForm" method="post" enctype="multipart/form-data"
                      action="{{ route('request-form-handle') }}" novalidate>
                    @csrf

                    <fieldset class="dbim-fa-field" aria-describedby="request_typeError">
                        <legend class="dbim-fa-label dbim-fa-legend-label">
                            Request Type <span class="dbim-required" aria-hidden="true">*</span>
                        </legend>

                        <div class="dbim-fa-option">
                            <input class="form-check-input @error('request_type') is-invalid @enderror" type="radio"
                                   name="request_type" id="online_request" value="online"
                                   required {{ old('request_type') == 'online' ? 'checked' : '' }}>
                            <label for="online_request">Previously Filed First Appeal Was Online</label>
                        </div>
                        <div class="dbim-fa-option">
                            <input class="form-check-input @error('request_type') is-invalid @enderror" type="radio"
                                   name="request_type" id="offline_request" value="offline"
                                   required {{ old('request_type') == 'offline' ? 'checked' : '' }}>
                            <label for="offline_request">Previously Filed First Appeal Was Offline</label>
                        </div>

                        <div id="request_typeError" class="invalid-feedback @error('request_type') d-block @enderror">@error('request_type'){{ $message }}@enderror</div>
                    </fieldset>

                    <div class="dbim-fa-actions">
                        <button type="reset" class="dbim-fa-btn dbim-fa-btn--outline">Reset</button>
                        <button type="submit" class="dbim-fa-btn">Submit</button>
                    </div>
                </form>

            </section>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        var form        = document.getElementById('requestTypeForm');
        var summary     = document.getElementById('errorSummary');
        var summaryList = document.getElementById('errorSummaryList');
        var radios      = form.querySelectorAll('input[name="request_type"]');
        var errorBox    = document.getElementById('request_typeError');
        var message     = 'Select whether the previously filed first appeal was online or offline.';

        function chosen() {
            return Array.prototype.some.call(radios, function (r) { return r.checked; });
        }

        function setInvalid(on) {
            Array.prototype.forEach.call(radios, function (r) { r.classList.toggle('is-invalid', on); });
            errorBox.textContent = on ? message : '';
            errorBox.classList.toggle('d-block', on);
        }

        form.addEventListener('submit', function (event) {
            if (chosen()) { setInvalid(false); return; }

            event.preventDefault();
            setInvalid(true);

            summaryList.textContent = '';
            var li = document.createElement('li');
            var a  = document.createElement('a');
            a.href = '#online_request';
            a.textContent = message;
            a.addEventListener('click', function (e) { e.preventDefault(); radios[0].focus(); });
            li.appendChild(a);
            summaryList.appendChild(li);
            summary.hidden = false;
            summary.focus();
        });

        Array.prototype.forEach.call(radios, function (r) {
            r.addEventListener('change', function () { setInvalid(false); });
        });

        form.addEventListener('reset', function () {
            setInvalid(false);
            summary.hidden = true;
        });

        if (!summary.hidden) { summary.focus(); }
    })();
</script>
@endpush