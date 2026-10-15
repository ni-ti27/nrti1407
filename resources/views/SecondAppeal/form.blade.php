{{-- ============================================================
     Online RTI Second Appeal Form  (DBIM / GIGW 3.0, keyboard accessible)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     No inline styles, no jQuery, no auto-hiding messages.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Online RTI Second Appeal Form | RTI Online, Government of Goa')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-6">

            {{-- success / warning / error messages from the session --}}
            @include('partials.flash-messages')

            <div class="card dbim-form-card">
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

                    <div class="alert alert-info dbim-note" role="note">
                        <p class="mb-1"><strong>Note:</strong></p>
                        <ul class="mb-2">
                            <li>If the First Appeal has not been disposed of, a Second Appeal can be filed only after 30 days from the First Appeal submission date.</li>
                            <li>A Second Appeal can be filed only within 90 days of First Appeal disposal.</li>
                        </ul>
                        <p>
                            Fields marked with (<span class="dbim-required" aria-hidden="true">*</span>) are required.
                        </p>
                    </div>

                    <form id="secondAppealForm" method="post" enctype="multipart/form-data"
                          action="{{ route('sa-form1.submit') }}" novalidate>
                        @csrf

                        {{-- First appeal registration number --}}
                        <div class="mb-3">
                            <label for="fa_reg_no" class="form-label fw-bold">
                                RTI First Appeal Registration Number <span class="dbim-required" aria-hidden="true">*</span>
                            </label>
                            <input type="text" class="form-control @error('fa_reg_no') is-invalid @enderror"
                                   id="fa_reg_no" name="fa_reg_no" value="{{ old('fa_reg_no') }}"
                                   autocomplete="off" required aria-required="true"
                                   aria-invalid="{{ $errors->has('fa_reg_no') ? 'true' : 'false' }}"
                                   aria-describedby="fa_reg_noError">
                            <div id="fa_reg_noError" class="invalid-feedback @error('fa_reg_no') d-block @enderror">@error('fa_reg_no'){{ $message }}@enderror</div>
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="emailId" class="form-label fw-bold">
                                Email Address <span class="dbim-required" aria-hidden="true">*</span>
                            </label>
                            <input type="email" class="form-control @error('emailId') is-invalid @enderror"
                                   id="emailId" name="emailId" value="{{ old('emailId') }}"
                                   placeholder="For example name@example.com"
                                   autocomplete="email" required aria-required="true"
                                   aria-invalid="{{ $errors->has('emailId') ? 'true' : 'false' }}"
                                   aria-describedby="emailIdError">
                            <div id="emailIdError" class="invalid-feedback @error('emailId') d-block @enderror">@error('emailId'){{ $message }}@enderror</div>
                        </div>

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
        var form        = document.getElementById('secondAppealForm');
        var summary     = document.getElementById('errorSummary');
        var summaryList = document.getElementById('errorSummaryList');

        var fields = {
            fa_reg_no: { id: 'fa_reg_no', errorId: 'fa_reg_noError', required: 'Enter the RTI first appeal registration number.' },
            emailId:   { id: 'emailId',   errorId: 'emailIdError',   required: 'Enter your email address.',
                         invalid: 'Enter a valid email address, for example name@example.com.' }
        };

        function showError(f, message) {
            var input = document.getElementById(f.id);
            var box   = document.getElementById(f.errorId);
            input.classList.add('is-invalid');
            input.setAttribute('aria-invalid', 'true');
            box.textContent = message;
            box.classList.add('d-block');
        }

        function clearError(f) {
            var input = document.getElementById(f.id);
            var box   = document.getElementById(f.errorId);
            input.classList.remove('is-invalid');
            input.setAttribute('aria-invalid', 'false');
            box.textContent = '';
            box.classList.remove('d-block');
        }

        function check(f) {
            var input = document.getElementById(f.id);
            if (input.value.trim() === '') { return f.required; }
            if (f.invalid && !input.validity.valid) { return f.invalid; }
            return '';
        }

        form.addEventListener('submit', function (event) {
            var problems = [];
            Object.keys(fields).forEach(function (key) {
                var message = check(fields[key]);
                if (message) { showError(fields[key], message); problems.push({ key: key, message: message }); }
                else { clearError(fields[key]); }
            });

            if (problems.length === 0) { return; }

            event.preventDefault();
            summaryList.textContent = '';
            problems.forEach(function (p) {
                var li = document.createElement('li');
                var a  = document.createElement('a');
                a.href = '#' + fields[p.key].id;
                a.textContent = p.message;
                a.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.getElementById(fields[p.key].id).focus();
                });
                li.appendChild(a);
                summaryList.appendChild(li);
            });
            summary.hidden = false;
            summary.focus();
        });

        /* Reset also clears messages */
        form.addEventListener('reset', function () {
            Object.keys(fields).forEach(function (key) { clearError(fields[key]); });
            summary.hidden = true;
        });

        /* Clear an error as soon as the user fixes the field */
        Object.keys(fields).forEach(function (key) {
            document.getElementById(fields[key].id).addEventListener('input', function () {
                if (!check(fields[key])) { clearError(fields[key]); }
            });
        });

        if (!summary.hidden) { summary.focus(); }
    })();
</script>
@endpush