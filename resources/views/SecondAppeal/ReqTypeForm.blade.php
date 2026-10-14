{{-- ============================================================
     Online RTI Second Appeal - Request Type Check Form
     (DBIM / GIGW 3.0, keyboard accessible)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     No inline styles, no auto-hiding messages.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Online RTI Second Appeal Form | RTI Online, Government of Goa')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-6">

            @include('partials.flash-messages')

            <div class="card shadow-sm">
                <h1 class="card-header h4 mb-0">Online RTI Second Appeal Form</h1>

                <div class="card-body">

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

                        <p class="text-end mb-4">Note: Field marked with (<span class="dbim-required">*</span>) are required.</p>

                        <fieldset class="mb-4" aria-describedby="request_typeError">
                            <legend class="fs-6 fw-bold">
                                Request Type <span class="dbim-required" aria-hidden="true">*</span>
                            </legend>

                            <div class="form-check">
                                <input class="form-check-input @error('request_type') is-invalid @enderror" type="radio"
                                       name="request_type" id="online_request" value="online"
                                       required {{ old('request_type') == 'online' ? 'checked' : '' }}>
                                <label class="form-check-label" for="online_request">
                                    Previously Filed First Appeal Was Online
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input @error('request_type') is-invalid @enderror" type="radio"
                                       name="request_type" id="offline_request" value="offline"
                                       required {{ old('request_type') == 'offline' ? 'checked' : '' }}>
                                <label class="form-check-label" for="offline_request">
                                    Previously Filed First Appeal Was Offline
                                </label>
                            </div>

                            <div id="request_typeError" class="invalid-feedback @error('request_type') d-block @enderror">@error('request_type'){{ $message }}@enderror</div>
                        </fieldset>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="reset" class="download-outline-btn text-uppercase w-ft" title="Reset">
                                Reset
                            </button>
                            <button type="submit" class="download-btn text-uppercase w-ft" title="Submit">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>

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