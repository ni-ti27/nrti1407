{{-- ============================================================
     RTI Request Details  (DBIM / GIGW 3.0, keyboard accessible)
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     Same fields, labels and conditions as the original page.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Request Details | RTI Online, Government of Goa')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">

            @include('partials.flash-messages')

            <h1 class="h4 mb-4">Request Details</h1>

            {{-- ===== Request Details ===== --}}
            <section class="card shadow-sm mb-4" aria-labelledby="requestHeading">
                <h2 id="requestHeading" class="card-header h5 mb-0">Request Details</h2>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-md-3">Reg No.</dt>
                        <dd class="col-md-3">{{ $submitRequest->reg_no }}</dd>

                        <dt class="col-md-3">Filed Date</dt>
                        <dd class="col-md-3">{{ \Carbon\Carbon::parse($submitRequest->created_at)->format('d-m-Y H:i') }}</dd>

                        <dt class="col-sm-4 col-lg-3">Department</dt>
                        <dd class="col-sm-8 col-lg-9">{{ $deptDetails }}</dd>

                        <dt class="col-sm-4 col-lg-3">Office</dt>
                        <dd class="col-sm-8 col-lg-9">{{ $officeDetails }}</dd>

                        <dt class="col-sm-4 col-lg-3">Sub Office</dt>
                        <dd class="col-sm-8 col-lg-9 mb-0">{{ $subOfficeDetails }}</dd>
                    </dl>
                </div>
            </section>

            {{-- ===== Applicant Details ===== --}}
            <section class="card shadow-sm mb-4" aria-labelledby="applicantHeading">
                <h2 id="applicantHeading" class="card-header h5 mb-0">Applicant Details</h2>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-5">Applicant Name</dt>
                                <dd class="col-sm-7">{{ $submitRequest->name }}</dd>

                                <dt class="col-sm-5">Gender</dt>
                                <dd class="col-sm-7">{{ $submitRequest->gender === 'female' ? 'Female' : ($submitRequest->gender === 'male' ? 'Male' : 'Other') }}</dd>

                                <dt class="col-sm-5">Address</dt>
                                <dd class="col-sm-7">
                                    {{ $submitRequest->address_line1 }}<br>
                                    {{ $submitRequest->address_line2 }}<br>
                                    {{ $submitRequest->address_line3 }}
                                </dd>

                                <dt class="col-sm-5">Pincode</dt>
                                <dd class="col-sm-7">{{ $submitRequest->pincode }}</dd>
                            </dl>
                        </div>

                        <div class="col-12 col-md-6">
                            <dl class="row mb-0">
                                @if($submitRequest->state === 0)
                                    <dt class="col-sm-5">Village</dt>
                                    <dd class="col-sm-7">Others</dd>
                                    <dt class="col-sm-5">District</dt>
                                    <dd class="col-sm-7">Others</dd>
                                    <dt class="col-sm-5">State</dt>
                                    <dd class="col-sm-7">Others</dd>
                                @else
                                    <dt class="col-sm-5">Village</dt>
                                    <dd class="col-sm-7">{{ $submitRequest->Village->name ?? 'Others' }}</dd>
                                    <dt class="col-sm-5">District</dt>
                                    <dd class="col-sm-7">{{ $submitRequest->District->name ?? 'Others' }}</dd>
                                    <dt class="col-sm-5">State</dt>
                                    <dd class="col-sm-7">{{ $submitRequest->State->name ?? 'Others' }}</dd>
                                @endif

                                <dt class="col-sm-5">Phone</dt>
                                <dd class="col-sm-7">{{ $submitRequest->phone_no }}</dd>

                                <dt class="col-sm-5">Mobile</dt>
                                <dd class="col-sm-7">{{ $submitRequest->mobile_no }}</dd>

                                <dt class="col-sm-5">Email</dt>
                                <dd class="col-sm-7 text-break">{{ $submitRequest->email_id }}</dd>
                            </dl>
                        </div>

                        <div class="col-12 col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-5">Education</dt>
                                <dd class="col-sm-7">{{ $submitRequest->edu_status === 1 ? 'Educated' : 'Non-Educated' }}</dd>

                                <dt class="col-sm-5">BPL</dt>
                                <dd class="col-sm-7">{{ $submitRequest->is_bpl == 1 ? 'BPL User' : 'Non BPL User' }}</dd>
                            </dl>
                        </div>

                        <div class="col-12 col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-5">Qualification</dt>
                                <dd class="col-sm-7">
                                    @if($submitRequest->edu_status === 0)
                                        N/A
                                    @elseif($submitRequest->qualification == 1)
                                        SSC
                                    @elseif($submitRequest->qualification == 2)
                                        HSSC
                                    @elseif($submitRequest->qualification == 3)
                                        Graduate
                                    @elseif($submitRequest->qualification == 4)
                                        Post Graduate
                                    @endif
                                </dd>

                                <dt class="col-sm-5">Card No.</dt>
                                <dd class="col-sm-7">{{ $submitRequest->card_no === 0 ? 'N/A' : $submitRequest->card_no }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ===== Request Details (application) ===== --}}
            <section class="card shadow-sm mb-4" aria-labelledby="applicationHeading">
                <h2 id="applicationHeading" class="card-header h5 mb-0">Request Details</h2>
                <div class="card-body">
                    <dl class="row mb-0">
                        @if($submitRequest->is_bpl === 0)
                            <dt class="col-sm-4 col-lg-3">Amount Paid (Rs.)</dt>
                            <dd class="col-sm-8 col-lg-9">10</dd>
                        @endif

                        <dt class="col-sm-4 col-lg-3">Text From Applicant</dt>
                        <dd class="col-sm-8 col-lg-9 text-break">{{ $submitRequest->request_text }}</dd>

                        <dt class="col-sm-4 col-lg-3">Does it concern life or liberty of a person</dt>
                        <dd class="col-sm-8 col-lg-9">N/A</dd>

                        <dt class="col-sm-4 col-lg-3">File Uploaded by Applicant</dt>
                        <dd class="col-sm-8 col-lg-9 mb-0">
                            <a href="{{ asset($filePath) }}" target="_blank" rel="noopener">
                                {{ $submitRequest->document_name_original }}<span class="visually-hidden"> (opens in a new tab)</span>
                            </a>
                        </dd>
                    </dl>
                </div>
            </section>

            {{-- ===== Payment Details ===== --}}
            @if(!$paymentDetails->isEmpty())
                <section class="card shadow-sm mb-4" aria-labelledby="paymentHeading">
                    <h2 id="paymentHeading" class="card-header h5 mb-0">Payment Details</h2>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <caption class="visually-hidden">Payment details</caption>
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">Sr. No</th>
                                        <th scope="col">Registration No.</th>
                                        <th scope="col">Amount (Rs.)</th>
                                        <th scope="col">e-Challan No.</th>
                                        <th scope="col">Transaction Date &amp; Time</th>
                                        <th scope="col">Mobile Number</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($paymentDetails as $index => $payment)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $submitRequest->reg_no }}</td>
                                            <td>{{ $payment->total_amt }}</td>
                                            <td>{{ $payment->echallan_no }}</td>
                                            <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('d-m-Y H:i') }}</td>
                                            <td>{{ $submitRequest->mobile_no }}</td>
                                            <td>Success</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            @endif

            {{-- ===== Officer's Reply (disposed) ===== --}}
            @if($reqestDisposeOf != null)
                <section class="card shadow-sm mb-4" aria-labelledby="disposalHeading">
                    <h2 id="disposalHeading" class="card-header h5 mb-0">Officer's Reply</h2>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-4 col-lg-3">Disposed Date &amp; Time</dt>
                            <dd class="col-sm-8 col-lg-9">{{ \Carbon\Carbon::parse($reqestDisposeOf->created_at)->format('d-m-Y H:i') }}</dd>

                            <dt class="col-sm-4 col-lg-3">File Number</dt>
                            <dd class="col-sm-8 col-lg-9">{{ $reqestDisposeOf->file_no ? $reqestDisposeOf->file_no : 'N/A' }}</dd>

                            <dt class="col-sm-4 col-lg-3">Disposal Type</dt>
                            <dd class="col-sm-8 col-lg-9">{{ $reqestDisposeOf->disposal_type }}</dd>

                            <dt class="col-sm-4 col-lg-3">Officer Attached File1</dt>
                            <dd class="col-sm-8 col-lg-9">
                                <a href="{{ Storage::url($reqestDisposeOf->document_path) }}" target="_blank" rel="noopener">
                                    {{ $reqestDisposeOf->original_doc_name }}<span class="visually-hidden"> (opens in a new tab)</span>
                                </a>
                            </dd>

                            <dt class="col-sm-4 col-lg-3">Disposal Reason</dt>
                            <dd class="col-sm-8 col-lg-9 text-break">{{ $reqestDisposeOf->reason }}</dd>

                            <dt class="col-sm-4 col-lg-3">Officer Attached File2</dt>
                            <dd class="col-sm-8 col-lg-9">
                                @if($reqestDisposeOf->document_path1 != null)
                                    <a href="{{ Storage::url($reqestDisposeOf->document_path1) }}" target="_blank" rel="noopener">
                                        {{ $reqestDisposeOf->original_doc_name1 }}<span class="visually-hidden"> (opens in a new tab)</span>
                                    </a>
                                @else
                                    NA
                                @endif
                            </dd>

                            <dt class="col-sm-4 col-lg-3">Officer Attached File3</dt>
                            <dd class="col-sm-8 col-lg-9 mb-0">
                                @if($reqestDisposeOf->document_path2 != null)
                                    <a href="{{ Storage::url($reqestDisposeOf->document_path2) }}" target="_blank" rel="noopener">
                                        {{ $reqestDisposeOf->original_doc_name2 }}<span class="visually-hidden"> (opens in a new tab)</span>
                                    </a>
                                @else
                                    NA
                                @endif
                            </dd>
                        </dl>
                    </div>
                </section>
            @endif

            {{-- ===== Officer's Reply (reverted) ===== --}}
            @if($requestToApplicant != null)
                <section class="card shadow-sm mb-4" aria-labelledby="revertHeading">
                    <h2 id="revertHeading" class="card-header h5 mb-0">Officer's Reply</h2>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-4 col-lg-3">Reverted Date &amp; Time</dt>
                            <dd class="col-sm-8 col-lg-9">{{ \Carbon\Carbon::parse($requestToApplicant->created_at)->format('d-m-Y H:i') }}</dd>

                            <dt class="col-sm-4 col-lg-3">Reverted Reason</dt>
                            <dd class="col-sm-8 col-lg-9 mb-0 text-break">{{ $requestToApplicant->reason }}</dd>
                        </dl>
                    </div>
                </section>
            @endif

            {{-- ===== Print Details ===== --}}
            <section class="card shadow-sm mb-4" aria-labelledby="printHeading">
                <h2 id="printHeading" class="card-header h5 mb-0">Print Details</h2>
                <div class="card-body">
                    <a href="/generate-pdf/{{ $submitRequest->id }}" target="_blank" rel="noopener"
                       class="download-outline-btn text-uppercase w-ft">
                        Print As PDF<span class="visually-hidden"> (opens in a new tab)</span>
                    </a>
                </div>
            </section>

        </div>
    </div>
</div>
@endsection