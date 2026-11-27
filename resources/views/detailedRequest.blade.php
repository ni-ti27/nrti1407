{{-- ============================================================
     RTI Request Details (officer, closed request)
     DBIM / GIGW 3.0. One card on the grey band (.dbim-sd frame).
     Same layout as request-processing.blade.php.
     Styles: dbim.css sections 50, 81, 82, 83 (.dbim-rq-*), 82 (header strip)
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Request Details | RTI Online, Government of Goa')

@section('content')
<div class="dbim-sd">
  <div class="dbim-sd-section">
    <div class="dbim-sd-wrap">

      @include('partials.flash-messages')

      <div class="dbim-rq-card">

        {{-- ============ Title + department / username / role strip ============ --}}
        <h1 class="dbim-rq-title">Request Details</h1>

        {{-- ============ Request Details ============ --}}
        <section class="dbim-rq-sec" aria-labelledby="requestHeading">
            <h2 class="dbim-rq-sec__head" id="requestHeading">Request Details</h2>
            <div class="dbim-rq-sec__body">
                <dl class="dbim-rq-meta">
                    <div>
                        <dt>Reg No.:</dt>
                        <dd>{{ $submitRequest->reg_no }}</dd>
                    </div>
                    <div>
                        <dt>Filed Date:</dt>
                        <dd>{{ \Carbon\Carbon::parse($submitRequest->created_at)->format('d-m-Y H:i') }}</dd>
                    </div>
                </dl>

                <hr class="dbim-rq-hr">

                <dl class="dbim-rq-grid">
                    <dt>Department:</dt>
                    <dd>{{ $deptDetails }}</dd>
                    <dt>Office:</dt>
                    <dd>{{ $officeDetails }}</dd>
                    <dt>Sub Office:</dt>
                    <dd>{{ $subOfficeDetails }}</dd>
                </dl>
            </div>
        </section>

        {{-- ============ Applicant Details ============ --}}
        <section class="dbim-rq-sec" aria-labelledby="applicantHeading">
            <h2 class="dbim-rq-sec__head" id="applicantHeading">Applicant Details</h2>
            <div class="dbim-rq-sec__body">
                <div class="dbim-rq-cols">
                    <dl class="dbim-rq-list">
                        <div class="dbim-rq-pair">
                            <dt>Applicant Name:</dt>
                            <dd>{{ $submitRequest->name }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>Gender:</dt>
                            <dd>{{ $submitRequest->gender === 'female' ? 'Female' : ($submitRequest->gender === 'male' ? 'Male' : 'Other') }}</dd>
                        </div>
                        <div class="dbim-rq-pair dbim-rq-pair--block">
                            <dt>Address:</dt>
                            <dd>
                                {{ $submitRequest->address_line1 }}<br>
                                {{ $submitRequest->address_line2 }}<br>
                                {{ $submitRequest->address_line3 }}
                            </dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>Pincode:</dt>
                            <dd>{{ $submitRequest->pincode }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>Education:</dt>
                            <dd>{{ $submitRequest->edu_status === 1 ? 'Educated' : 'Non-Educated' }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>BPL:</dt>
                            <dd>{{ $submitRequest->is_bpl == 1 ? 'BPL User' : 'Non BPL User' }}</dd>
                        </div>
                    </dl>

                    <dl class="dbim-rq-list">
                        @if($submitRequest->state === 0)
                            <div class="dbim-rq-pair">
                                <dt>Village:</dt>
                                <dd>Others</dd>
                            </div>
                            <div class="dbim-rq-pair">
                                <dt>District:</dt>
                                <dd>Others</dd>
                            </div>
                            <div class="dbim-rq-pair">
                                <dt>State:</dt>
                                <dd>Others</dd>
                            </div>
                        @else
                            <div class="dbim-rq-pair">
                                <dt>Village:</dt>
                                <dd>{{ $submitRequest->Village->name ?? 'Others' }}</dd>
                            </div>
                            <div class="dbim-rq-pair">
                                <dt>District:</dt>
                                <dd>{{ $submitRequest->District->name ?? 'Others' }}</dd>
                            </div>
                            <div class="dbim-rq-pair">
                                <dt>State:</dt>
                                <dd>{{ $submitRequest->State->name ?? 'Others' }}</dd>
                            </div>
                        @endif
                        <div class="dbim-rq-pair">
                            <dt>Phone:</dt>
                            <dd>{{ $submitRequest->phone_no }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>Mobile:</dt>
                            <dd>{{ $submitRequest->mobile_no }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>Email:</dt>
                            <dd>{{ $submitRequest->email_id }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>Qualification:</dt>
                            <dd>
                                @if($submitRequest->edu_status === 0) N/A
                                @elseif($submitRequest->qualification == 1) SSC
                                @elseif($submitRequest->qualification == 2) HSSC
                                @elseif($submitRequest->qualification == 3) Graduate
                                @elseif($submitRequest->qualification == 4) Post Graduate
                                @endif
                            </dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>Card No.:</dt>
                            <dd>{{ $submitRequest->card_no === 0 ? 'N/A' : $submitRequest->card_no }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </section>

        {{-- ============ Request Details (application) ============ --}}
        <section class="dbim-rq-sec" aria-labelledby="applicationHeading">
            <h2 class="dbim-rq-sec__head" id="applicationHeading">Application Details</h2>
            <div class="dbim-rq-sec__body">
                <div class="dbim-rq-cols">
                    <dl class="dbim-rq-list">
                        @if($submitRequest->is_bpl === 0)
                            <div class="dbim-rq-pair">
                                <dt>Amount Paid (Rs.):</dt>
                                <dd>10</dd>
                            </div>
                        @endif
                        <div class="dbim-rq-pair">
                            <dt>Does it concern life or liberty of a person:</dt>
                            <dd>N/A</dd>
                        </div>
                    </dl>
                    <dl class="dbim-rq-list">
                        <div class="dbim-rq-pair">
                            <dt>File Uploaded by Applicant:</dt>
                            <dd>
                                <a href="{{ asset($filePath) }}" target="_blank" rel="noopener">{{ $submitRequest->document_name_original }}<span class="visually-hidden"> (opens in a new tab)</span></a>
                            </dd>
                        </div>
                    </dl>
                </div>

                <hr class="dbim-rq-hr">

                <dl class="dbim-rq-list">
                    <div class="dbim-rq-pair dbim-rq-pair--block">
                        <dt>Text From Applicant:</dt>
                        <dd class="dbim-rq-text">{{ $submitRequest->request_text }}</dd>
                    </div>
                </dl>
            </div>
        </section>

        {{-- ============ Payment Details ============ --}}
        @if(!$paymentDetails->isEmpty())
            <section class="dbim-rq-sec" aria-labelledby="paymentHeading">
                <h2 class="dbim-rq-sec__head" id="paymentHeading">Payment Details</h2>
                <div class="dbim-rq-sec__body">
                    <div class="table-responsive">
                        <table class="table table-light table-bordered mb-0">
                            <caption class="visually-hidden">Payment details</caption>
                            <thead>
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

        {{-- ============ Officer's Reply (disposed) ============ --}}
        @if($reqestDisposeOf != null)
            <section class="dbim-rq-sec" aria-labelledby="disposalHeading">
                <h2 class="dbim-rq-sec__head" id="disposalHeading">Officer's Reply</h2>
                <div class="dbim-rq-sec__body">
                    <div class="dbim-rq-cols">
                        <dl class="dbim-rq-list">
                            <div class="dbim-rq-pair">
                                <dt>Disposed Date &amp; Time:</dt>
                                <dd>{{ \Carbon\Carbon::parse($reqestDisposeOf->created_at)->format('d-m-Y H:i') }}</dd>
                            </div>
                            <div class="dbim-rq-pair">
                                <dt>File Number:</dt>
                                <dd>{{ $reqestDisposeOf->file_no ? $reqestDisposeOf->file_no : 'N/A' }}</dd>
                            </div>
                            <div class="dbim-rq-pair">
                                <dt>Disposal Type:</dt>
                                <dd>{{ $reqestDisposeOf->disposal_type }}</dd>
                            </div>
                        </dl>
                        <dl class="dbim-rq-list">
                            <div class="dbim-rq-pair">
                                <dt>Officer Attached File1:</dt>
                                <dd>
                                    <a href="{{ Storage::url($reqestDisposeOf->document_path) }}" target="_blank" rel="noopener">{{ $reqestDisposeOf->original_doc_name }}<span class="visually-hidden"> (opens in a new tab)</span></a>
                                </dd>
                            </div>
                            <div class="dbim-rq-pair">
                                <dt>Officer Attached File2:</dt>
                                <dd>
                                    @if($reqestDisposeOf->document_path1 != null)
                                        <a href="{{ Storage::url($reqestDisposeOf->document_path1) }}" target="_blank" rel="noopener">{{ $reqestDisposeOf->original_doc_name1 }}<span class="visually-hidden"> (opens in a new tab)</span></a>
                                    @else
                                        NA
                                    @endif
                                </dd>
                            </div>
                            <div class="dbim-rq-pair">
                                <dt>Officer Attached File3:</dt>
                                <dd>
                                    @if($reqestDisposeOf->document_path2 != null)
                                        <a href="{{ Storage::url($reqestDisposeOf->document_path2) }}" target="_blank" rel="noopener">{{ $reqestDisposeOf->original_doc_name2 }}<span class="visually-hidden"> (opens in a new tab)</span></a>
                                    @else
                                        NA
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <hr class="dbim-rq-hr">

                    <dl class="dbim-rq-list">
                        <div class="dbim-rq-pair dbim-rq-pair--block">
                            <dt>Disposal Reason:</dt>
                            <dd class="dbim-rq-text">{{ $reqestDisposeOf->reason }}</dd>
                        </div>
                    </dl>
                </div>
            </section>
        @endif

        {{-- ============ Officer's Reply (reverted) ============ --}}
        @if($requestToApplicant != null)
            <section class="dbim-rq-sec" aria-labelledby="revertHeading">
                <h2 class="dbim-rq-sec__head" id="revertHeading">Officer's Reply</h2>
                <div class="dbim-rq-sec__body">
                    <dl class="dbim-rq-list">
                        <div class="dbim-rq-pair">
                            <dt>Reverted Date &amp; Time:</dt>
                            <dd>{{ \Carbon\Carbon::parse($requestToApplicant->created_at)->format('d-m-Y H:i') }}</dd>
                        </div>
                        <div class="dbim-rq-pair dbim-rq-pair--block">
                            <dt>Reverted Reason:</dt>
                            <dd class="dbim-rq-text">{{ $requestToApplicant->reason }}</dd>
                        </div>
                    </dl>
                </div>
            </section>
        @endif

        {{-- ============ Print ============ --}}
        <section class="dbim-rq-sec" aria-labelledby="printHeading">
            <h2 class="dbim-rq-sec__head" id="printHeading">Print Details</h2>
            <div class="dbim-rq-sec__body">
                <a href="/generate-pdf/{{ $submitRequest->id }}" class="download-outline-btn w-ft mt-0"
                   target="_blank" rel="noopener">
                    Print As PDF<span class="visually-hidden"> (opens in a new tab)</span>
                </a>
            </div>
        </section>

      </div>{{-- .dbim-rq-card --}}

    </div>{{-- .dbim-sd-wrap --}}
  </div>{{-- .dbim-sd-section --}}
</div>{{-- .dbim-sd --}}
@endsection