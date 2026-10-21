{{-- ============================================================
     Second Appeal Details  (DBIM / GIGW 3.0, keyboard accessible)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     Same fields, labels and conditions as the original page.
     Field pairs sit side by side from the md breakpoint and stack on mobile.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Second Appeal Details | RTI Online, Government of Goa')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">

            @include('partials.flash-messages')

            {{-- Page title --}}
            <div class="text-center mb-4">
                @if ($saSubmitRequest->saActionDetails->first()->status_id == 1)
                    <h1 class="h5 mb-1">New Request</h1>
                    <p class="mb-0">
                        @if($saSubmitRequest->online_request == true)
                            (Online)
                        @else
                            (Offline)
                        @endif
                    </p>
                @elseif ($saSubmitRequest->saActionDetails->first()->status_id == 2)
                    <h1 class="h5 mb-0">In Process Request</h1>
                @endif
            </div>

            {{-- ===== Public Authority Details ===== --}}
            <section class="card shadow-sm mb-4" aria-labelledby="authorityHeading">
                <h2 id="authorityHeading" class="card-header h5 mb-0">Public Authority Details</h2>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-md-3">Reg No.</dt>
                        <dd class="col-md-3">{{ $saSubmitRequest->sa_reg_no }}</dd>

                        <dt class="col-md-3">Filed Date</dt>
                        <dd class="col-md-3">{{ \Carbon\Carbon::parse($saSubmitRequest->created_at)->format('d-m-Y') }}</dd>

                        <dt class="col-md-3">Department</dt>
                        <dd class="col-md-9">{{ $deptDetails }}</dd>

                        <dt class="col-md-3">Office</dt>
                        <dd class="col-md-9">{{ $officeDetails }}</dd>

                        <dt class="col-md-3">Sub Office</dt>
                        <dd class="col-md-9 mb-0">{{ $subOfficeDetails }}</dd>
                    </dl>
                </div>
            </section>

            {{-- ===== Personal Details of RTI Applicant ===== --}}
            <section class="card shadow-sm mb-4" aria-labelledby="applicantHeading">
                <h2 id="applicantHeading" class="card-header h5 mb-0">Personal Details of RTI Applicant</h2>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-5">Applicant Name</dt>
                                <dd class="col-sm-7">{{ $saSubmitRequest->name }}</dd>

                                <dt class="col-sm-5">Gender</dt>
                                <dd class="col-sm-7">{{ $saSubmitRequest->gender === 'female' ? 'Female' : ($saSubmitRequest->gender === 'male' ? 'Male' : 'Other') }}</dd>

                                <dt class="col-sm-5">Address</dt>
                                <dd class="col-sm-7">
                                    {{ $saSubmitRequest->address_line1 }}<br>
                                    {{ $saSubmitRequest->address_line2 }}<br>
                                    {{ $saSubmitRequest->address_line3 }}
                                </dd>

                                <dt class="col-sm-5">Pincode</dt>
                                <dd class="col-sm-7">{{ $saSubmitRequest->pincode }}</dd>

                                <dt class="col-sm-5">Education</dt>
                                <dd class="col-sm-7">{{ $saSubmitRequest->edu_status === 1 ? 'Educated' : 'Non-Educated' }}</dd>
                            </dl>
                        </div>

                        <div class="col-12 col-md-6">
                            <dl class="row mb-0">
                                @if($saSubmitRequest->state === 0)
                                    <dt class="col-sm-5">Village</dt>
                                    <dd class="col-sm-7">Others</dd>
                                    <dt class="col-sm-5">District</dt>
                                    <dd class="col-sm-7">Others</dd>
                                    <dt class="col-sm-5">State</dt>
                                    <dd class="col-sm-7">Others</dd>
                                @else
                                    <dt class="col-sm-5">Village</dt>
                                    <dd class="col-sm-7">{{ $saSubmitRequest->Village->name ?? 'Others' }}</dd>
                                    <dt class="col-sm-5">District</dt>
                                    <dd class="col-sm-7">{{ $saSubmitRequest->District->name ?? 'Others' }}</dd>
                                    <dt class="col-sm-5">State</dt>
                                    <dd class="col-sm-7">{{ $saSubmitRequest->State->name ?? 'Others' }}</dd>
                                @endif

                                <dt class="col-sm-5">Phone</dt>
                                <dd class="col-sm-7">{{ $saSubmitRequest->phone_no }}</dd>

                                <dt class="col-sm-5">Mobile</dt>
                                <dd class="col-sm-7">{{ $saSubmitRequest->mobile_no }}</dd>

                                <dt class="col-sm-5">Email</dt>
                                <dd class="col-sm-7 text-break">{{ $saSubmitRequest->email_id }}</dd>

                                <dt class="col-sm-5">BPL</dt>
                                <dd class="col-sm-7">{{ $saSubmitRequest->is_bpl == 1 ? 'BPL User' : 'Non BPL User' }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ===== Details of RTI Application ===== --}}
            <section class="card shadow-sm mb-4" aria-labelledby="rtiHeading">
                <h2 id="rtiHeading" class="card-header h5 mb-0">Details of RTI Application</h2>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-md-3">RTI Request Registration Number</dt>
                        <dd class="col-md-3">{{ $saSubmitRequest->reg_no }}</dd>

                        <dt class="col-md-3">RTI Request Filed on (Date)</dt>
                        <dd class="col-md-3">{{ \Carbon\Carbon::parse($saSubmitRequest->newrequest_date)->format('d-m-Y') }}</dd>

                        <dt class="col-md-3">PIO Name</dt>
                        <dd class="col-md-3">{{ $saSubmitRequest->pio_name }}</dd>

                        <dt class="col-md-3">PIO Address</dt>
                        <dd class="col-md-3 text-break">{{ $saSubmitRequest->pio_address }}</dd>

                        <dt class="col-md-3">RTI Application File</dt>
                        <dd class="col-md-9">
                            <a href="{{ asset($saFiles['newRequestFile']['document_path']) }}" target="_blank" rel="noopener">
                                {{ $saFiles['newRequestFile']['document_name_original'] }}<span class="visually-hidden"> (opens in a new tab)</span>
                            </a>
                        </dd>
                    </dl>

                    <div class="mb-0">
                        <label for="rti_user_text" class="form-label fw-bold">Requested Text</label>
                        <textarea class="form-control" name="user_text" id="rti_user_text" rows="8" readonly>{{ isset($requestedText['rti_req']) ? $requestedText['rti_req'] : '' }}</textarea>
                    </div>
                </div>
            </section>

            {{-- ===== Details of First Appeal Application ===== --}}
            <section class="card shadow-sm mb-4" aria-labelledby="faHeading">
                <h2 id="faHeading" class="card-header h5 mb-0">Details of First Appeal Application</h2>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-md-3">First Appeal Registration Number</dt>
                        <dd class="col-md-3">{{ $saSubmitRequest->fa_reg_no }}</dd>

                        <dt class="col-md-3">First Appeal Filed on (Date)</dt>
                        <dd class="col-md-3">{{ \Carbon\Carbon::parse($saSubmitRequest->faa_date)->format('d-m-Y') }}</dd>

                        <dt class="col-md-3">PIO Name</dt>
                        <dd class="col-md-3">{{ $saSubmitRequest->pio_name }}</dd>

                        <dt class="col-md-3">PIO Address</dt>
                        <dd class="col-md-3 text-break">{{ $saSubmitRequest->pio_address }}</dd>
                    </dl>

                    @if(isset($saFiles['faRequestFile']) &&
                        isset($saFiles['faRequestFile']['document_path']) &&
                        isset($saFiles['faRequestFile']['document_name_original']))
                        <dl class="row">
                            <dt class="col-md-3">First Appeal File</dt>
                            <dd class="col-md-9 mb-0">
                                <a href="{{ asset($saFiles['faRequestFile']['document_path']) }}" target="_blank" rel="noopener">
                                    {{ $saFiles['faRequestFile']['document_name_original'] }}<span class="visually-hidden"> (opens in a new tab)</span>
                                </a>
                            </dd>
                        </dl>
                    @else
                        <p class="fw-bold">No file available for the first appeal.</p>
                    @endif

                    @if(isset($faFiles['faFile']) || isset($faFiles['supporting_doc']))
                        <dl class="row">
                            @if(isset($faFiles['faFile']))
                                <dt class="col-md-3">First Appeal Application Document</dt>
                                <dd class="col-md-3">
                                    <a href="{{ asset($faFiles['faFile']['document_path']) }}" target="_blank" rel="noopener">
                                        {{ $faFiles['faFile']['document_name_original'] }}<span class="visually-hidden"> (opens in a new tab)</span>
                                    </a>
                                </dd>
                            @endif

                            @if(isset($faFiles['supporting_doc']))
                                <dt class="col-md-3">First Appeal Supporting File</dt>
                                <dd class="col-md-3">
                                    <a href="{{ asset($faFiles['supporting_doc']['document_path']) }}" target="_blank" rel="noopener">
                                        {{ $faFiles['supporting_doc']['document_name_original'] }}<span class="visually-hidden"> (opens in a new tab)</span>
                                    </a>
                                </dd>
                            @endif
                        </dl>
                    @endif

                    <div class="mb-0">
                        <label for="fa_user_text" class="form-label fw-bold">Requested Text</label>
                        <textarea class="form-control" name="user_text" id="fa_user_text" rows="8" readonly>{{ isset($requestedText['faa_req']) ? $requestedText['faa_req'] : '' }}</textarea>
                    </div>
                </div>
            </section>

            {{-- ===== Details of Second Appeal Application ===== --}}
            <section class="card shadow-sm mb-4" aria-labelledby="saHeading">
                <h2 id="saHeading" class="card-header h5 mb-0">Details of Second Appeal Application</h2>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-md-3">Second Appeal Registration Number</dt>
                        <dd class="col-md-3">{{ $saSubmitRequest->sa_reg_no }}</dd>

                        <dt class="col-md-3">Second Appeal Filed on (Date)</dt>
                        <dd class="col-md-3">{{ \Carbon\Carbon::parse($saSubmitRequest->created_at)->format('d-m-Y') }}</dd>

                        <dt class="col-md-3">PIO Name</dt>
                        <dd class="col-md-3">{{ $saSubmitRequest->pio_name }}</dd>

                        <dt class="col-md-3">PIO Address</dt>
                        <dd class="col-md-3 text-break">{{ $saSubmitRequest->pio_address }}</dd>

                        <dt class="col-md-3">FAA Name</dt>
                        <dd class="col-md-3">{{ $saSubmitRequest->faa_name }}</dd>

                        <dt class="col-md-3">FAA Address</dt>
                        <dd class="col-md-3 text-break">{{ $saSubmitRequest->faa_address }}</dd>

                        <dt class="col-md-3">Second Appeal Document</dt>
                        <dd class="col-md-3">
                            <a href="{{ asset($saFiles['saFile']['document_path']) }}" target="_blank" rel="noopener">
                                {{ $saFiles['saFile']['document_name_original'] }}<span class="visually-hidden"> (opens in a new tab)</span>
                            </a>
                        </dd>

                        <dt class="col-md-3">Id Proof Document</dt>
                        <dd class="col-md-3">
                            <a href="{{ asset($saFiles['idProof']['document_path']) }}" target="_blank" rel="noopener">
                                {{ $saFiles['idProof']['document_name_original'] }}<span class="visually-hidden"> (opens in a new tab)</span>
                            </a>
                        </dd>

                        @if(isset($saFiles['supporting_doc']))
                            @foreach($saFiles['supporting_doc'] as $index => $document)
                                <dt class="col-md-3">Second Appeal Supporting File {{ $index + 1 }}</dt>
                                <dd class="col-md-3">
                                    <a href="{{ asset($document['document_path']) }}" target="_blank" rel="noopener">
                                        {{ $document['document_name_original'] }}<span class="visually-hidden"> (opens in a new tab)</span>
                                    </a>
                                </dd>
                            @endforeach
                        @endif
                    </dl>
                </div>
            </section>

            {{-- ===== Officer's Reply ===== --}}
            @if($SA_Reply_To_Applicant != null)
                <section class="card shadow-sm mb-4" aria-labelledby="replyHeading">
                    <h2 id="replyHeading" class="card-header h5 mb-0">Officer's Reply</h2>
                    <div class="card-body">
                        @if(!empty($SA_Reply_To_Applicant->reason))
                            <dl class="row mb-0">
                                <dt class="col-md-3">Reverted Date &amp; Time</dt>
                                <dd class="col-md-9">{{ \Carbon\Carbon::parse($SA_Reply_To_Applicant->created_at)->format('d-m-Y H:i') }}</dd>

                                <dt class="col-md-3">Reverted Reason</dt>
                                <dd class="col-md-9 mb-0 text-break">{{ $SA_Reply_To_Applicant->reason }}</dd>
                            </dl>
                        @endif
                    </div>
                </section>
            @endif

            {{-- ===== Print Details ===== --}}
            <section class="card shadow-sm mb-4" aria-labelledby="printHeading">
                <h2 id="printHeading" class="card-header h5 mb-0">Print Details</h2>
                <div class="card-body">
                    <a href="/sa-pdf/{{ $saSubmitRequest->id }}" target="_blank" rel="noopener"
                       class="download-btn text-uppercase w-ft">
                        Print As PDF<span class="visually-hidden"> (opens in a new tab)</span>
                    </a>
                </div>
            </section>

        </div>
    </div>
</div>
@endsection