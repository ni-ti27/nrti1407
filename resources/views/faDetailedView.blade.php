{{-- ============================================================
     First Appeal Details  (DBIM / GIGW 3.0, keyboard accessible)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     Same fields, labels and conditions as the original page.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'First Appeal Details | RTI Online, Government of Goa')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">

            @include('partials.flash-messages')

            {{-- Department name and page title --}}
            <div class="text-center mb-4">
                <p class="h4 mb-2">{{ session('faaDeptName') }}</p>
                <h1 class="h5 mb-0">
                    @if ($faDetails->status_id == 1)
                        First Appeal New Request
                    @elseif ($faDetails->status_id == 2)
                        First Appeal In Process Request
                    @elseif ($faDetails->status_id == 5)
                        First Appeal Closed Request
                    @endif
                </h1>
            </div>

            @if ($faDetails->status_id == 5)
                <div class="alert alert-success text-center" role="status">
                    Status - Closed
                </div>
            @endif

            {{-- ===== Public Authority Details ===== --}}
            <section class="card shadow-sm mb-4" aria-labelledby="authorityHeading">
                <h2 id="authorityHeading" class="card-header h5 mb-0">Public Authority Details</h2>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-md-3">FA Reg No.</dt>
                        <dd class="col-md-3">{{ $faDetails->fa_reg_no }}</dd>

                        <dt class="col-md-3">Filed Date</dt>
                        <dd class="col-md-3">{{ \Carbon\Carbon::parse($faDetails->created_at)->format('d-m-Y H:i') }}</dd>

                        <dt class="col-sm-4 col-lg-3">Department</dt>
                        <dd class="col-sm-8 col-lg-9">{{ $departmentDetails['dept'] ?? 'N/A' }}</dd>

                        <dt class="col-sm-4 col-lg-3">Office</dt>
                        <dd class="col-sm-8 col-lg-9">{{ $departmentDetails['office'] ?? 'N/A' }}</dd>

                        <dt class="col-sm-4 col-lg-3">Sub Office</dt>
                        <dd class="col-sm-8 col-lg-9 mb-0">{{ $departmentDetails['suboffice'] ?? 'N/A' }}</dd>
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
                                <dt class="col-sm-5">Name</dt>
                                <dd class="col-sm-7">{{ $faDetails->name }}</dd>

                                <dt class="col-sm-5">Address Line1</dt>
                                <dd class="col-sm-7">{{ $faDetails->address_line1 }}</dd>

                                <dt class="col-sm-5">Address Line3</dt>
                                <dd class="col-sm-7">{{ $faDetails->address_line3 }}</dd>

                                <dt class="col-sm-5">Country</dt>
                                <dd class="col-sm-7">India</dd>

                                <dt class="col-sm-5">District</dt>
                                <dd class="col-sm-7">{{ $uploadedDocs['district'] }}</dd>

                                <dt class="col-sm-5">Phone No.</dt>
                                <dd class="col-sm-7">{{ $faDetails->phone_no == '0' ? 'NA' : $faDetails->phone_no }}</dd>
                            </dl>
                        </div>

                        <div class="col-12 col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-5">Gender</dt>
                                <dd class="col-sm-7">{{ $faDetails->gender }}</dd>

                                <dt class="col-sm-5">Address Line2</dt>
                                <dd class="col-sm-7">{{ $faDetails->address_line2 }}</dd>

                                <dt class="col-sm-5">Pincode</dt>
                                <dd class="col-sm-7">{{ $faDetails->pincode }}</dd>

                                <dt class="col-sm-5">State</dt>
                                <dd class="col-sm-7">{{ $faDetails->stateName ?? 'Others' }}</dd>

                                <dt class="col-sm-5">Village</dt>
                                <dd class="col-sm-7">{{ $uploadedDocs['village'] }}</dd>

                                <dt class="col-sm-5">Mobile No.</dt>
                                <dd class="col-sm-7">{{ $faDetails->mobile_no }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ===== Details of RTI Application ===== --}}
            <section class="card shadow-sm mb-4" aria-labelledby="rtiHeading">
                <h2 id="rtiHeading" class="card-header h5 mb-0">Details of RTI Application</h2>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-5">RTI Filed Reg No</dt>
                                <dd class="col-sm-7">{{ $faDetails->reg_no }}</dd>

                                <dt class="col-sm-5">Requested Text</dt>
                                <dd class="col-sm-7 text-break">{{ $faDetails->sr_req_note }}</dd>
                            </dl>
                        </div>

                        <div class="col-12 col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-5">RTI Filed Date</dt>
                                <dd class="col-sm-7">{{ \Carbon\Carbon::parse($faDetails->submitted_date)->format('d-m-Y') }}</dd>

                                <dt class="col-sm-5">Copy of the RTI Application</dt>
                                <dd class="col-sm-7">
                                    <a href="{{ Storage::url($faDetails->document_path) }}" target="_blank" rel="noopener">
                                        {{ $faDetails->document_name_original }}<span class="visually-hidden"> (opens in a new tab)</span>
                                    </a>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ===== PIO Response ===== --}}
            <section class="card shadow-sm mb-4" aria-labelledby="pioHeading">
                <h2 id="pioHeading" class="card-header h5 mb-0">PIO Response</h2>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-5">PIO Response</dt>
                                <dd class="col-sm-7 text-break">{{ $uploadedDocs['details']->reason }}</dd>
                            </dl>
                        </div>

                        <div class="col-12 col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-5">Reply of PIO</dt>
                                <dd class="col-sm-7">
                                    @if($uploadedDocs['pioReplyDoc'] != null)
                                        <a href="{{ Storage::url($uploadedDocs['pioReplyDoc']['document_path']) }}" target="_blank" rel="noopener">
                                            {{ $uploadedDocs['pioReplyDoc']['document_name_original'] }}<span class="visually-hidden"> (opens in a new tab)</span>
                                        </a>
                                    @else
                                        File Not Found
                                    @endif
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ===== Details of FAA Application ===== --}}
            <section class="card shadow-sm mb-4" aria-labelledby="faaHeading">
                <h2 id="faaHeading" class="card-header h5 mb-0">Details of FAA Application</h2>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-5">Registration Number</dt>
                                <dd class="col-sm-7">{{ $faDetails->fa_reg_no }}</dd>

                                <dt class="col-sm-5">Requested Text</dt>
                                <dd class="col-sm-7 text-break">{{ $faDetails->fa_req_note }}</dd>
                            </dl>
                        </div>

                        <div class="col-12 col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-5">FA Received</dt>
                                <dd class="col-sm-7">{{ \Carbon\Carbon::parse($faDetails->created_at)->format('d-m-Y') }}</dd>

                                <dt class="col-sm-5">Supporting Document</dt>
                                <dd class="col-sm-7">
                                    @if($uploadedDocs['suppDoc'] != null)
                                        <a href="{{ Storage::url($uploadedDocs['suppDoc']['document_path']) }}" target="_blank" rel="noopener">
                                            {{ $uploadedDocs['suppDoc']['document_name_original'] }}<span class="visually-hidden"> (opens in a new tab)</span>
                                        </a>
                                    @else
                                        File Not Found
                                    @endif
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ===== Officer's Reply ===== --}}
            @if($faAction != null)
                <section class="card shadow-sm mb-4" aria-labelledby="replyHeading">
                    <h2 id="replyHeading" class="card-header h5 mb-0">Officer's Reply</h2>
                    <div class="card-body">
                        @if(!empty($faAction->return_to_applicant_reason))
                            <dl class="row mb-0">
                                <dt class="col-sm-4 col-lg-3">Reverted Date &amp; Time</dt>
                                <dd class="col-sm-8 col-lg-9">{{ \Carbon\Carbon::parse($faAction->created_at)->format('d-m-Y H:i') }}</dd>

                                <dt class="col-sm-4 col-lg-3">Reverted Reason</dt>
                                <dd class="col-sm-8 col-lg-9 mb-0 text-break">{{ $faAction->return_to_applicant_reason }}</dd>
                            </dl>
                        @elseif(!empty($faAction->disposal_reason))
                            <dl class="row mb-0">
                                <dt class="col-sm-4 col-lg-3">Disposal Date &amp; Time</dt>
                                <dd class="col-sm-8 col-lg-9">{{ \Carbon\Carbon::parse($faAction->created_at)->format('d-m-Y H:i') }}</dd>

                                <dt class="col-sm-4 col-lg-3">Disposal Reason</dt>
                                <dd class="col-sm-8 col-lg-9 text-break">{{ $faAction->disposal_reason }}</dd>

                                @if(!empty($faAction->document_path) && !empty($faAction->document_name))
                                    <dt class="col-sm-4 col-lg-3">Disposal Reply</dt>
                                    <dd class="col-sm-8 col-lg-9 mb-0">
                                        <a href="{{ asset($faAction->document_path) }}" target="_blank" rel="noopener">
                                            {{ $faAction->document_name }}<span class="visually-hidden"> (opens in a new tab)</span>
                                        </a>
                                    </dd>
                                @endif
                            </dl>
                        @else
                            <p class="mb-0">No officer reply available yet.</p>
                        @endif
                    </div>
                </section>
            @endif

            {{-- ===== Print Details ===== --}}
            <section class="card shadow-sm mb-4" aria-labelledby="printHeading">
                <h2 id="printHeading" class="card-header h5 mb-0">Print Details</h2>
                <div class="card-body">
                    <a href="/inprocess-pdf/{{ $faDetails->id }}" target="_blank" rel="noopener"
                       class="download-btn text-uppercase w-ft">
                        Print As PDF<span class="visually-hidden"> (opens in a new tab)</span>
                    </a>
                </div>
            </section>

        </div>
    </div>
</div>
@endsection