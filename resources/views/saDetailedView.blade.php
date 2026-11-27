{{-- ============================================================
     Second Appeal Details (officer, read-only)
     DBIM / GIGW 3.0. One card on the grey band (.dbim-sd frame).
     Same layout as request-processing.blade.php.
     Styles: dbim.css sections 50, 81, 82, 83 (.dbim-rq-*), 82 (header strip)
     ============================================================ --}}
@extends('layouts.app')

@php
    $saStatusId = optional($saSubmitRequest->saActionDetails->first())->status_id;
    $pageTitle  = $saStatusId == 1 ? 'New Request' : ($saStatusId == 2 ? 'In Process Request' : 'Second Appeal Details');
@endphp

@section('title', 'Second Appeal Details | RTI Online, Government of Goa')

@section('content')
<div class="dbim-sd">
  <div class="dbim-sd-section">
    <div class="dbim-sd-wrap">

      @include('partials.flash-messages')

      <div class="dbim-rq-card">

        {{-- ============ Title + department / username / role strip ============ --}}
        <h1 class="dbim-rq-title">{{ $pageTitle }}</h1>

        @if ($saStatusId == 1)
            <p class="dbim-rq-status" role="status">
                @if($saSubmitRequest->online_request == true)
                    (Online)
                @else
                    (Offline)
                @endif
            </p>
        @endif

        {{-- ============ Public Authority Details ============ --}}
        <section class="dbim-rq-sec" aria-labelledby="authorityHeading">
            <h2 class="dbim-rq-sec__head" id="authorityHeading">Public Authority Details</h2>
            <div class="dbim-rq-sec__body">
                <dl class="dbim-rq-meta">
                    <div>
                        <dt>Reg No.:</dt>
                        <dd>{{ $saSubmitRequest->sa_reg_no }}</dd>
                    </div>
                    <div>
                        <dt>Filed Date:</dt>
                        <dd>{{ \Carbon\Carbon::parse($saSubmitRequest->created_at)->format('d-m-Y') }}</dd>
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

        {{-- ============ Personal Details of RTI Applicant ============ --}}
        <section class="dbim-rq-sec" aria-labelledby="applicantHeading">
            <h2 class="dbim-rq-sec__head" id="applicantHeading">Personal Details of RTI Applicant</h2>
            <div class="dbim-rq-sec__body">
                <div class="dbim-rq-cols">
                    <dl class="dbim-rq-list">
                        <div class="dbim-rq-pair">
                            <dt>Applicant Name:</dt>
                            <dd>{{ $saSubmitRequest->name }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>Gender:</dt>
                            <dd>{{ $saSubmitRequest->gender === 'female' ? 'Female' : ($saSubmitRequest->gender === 'male' ? 'Male' : 'Other') }}</dd>
                        </div>
                        <div class="dbim-rq-pair dbim-rq-pair--block">
                            <dt>Address:</dt>
                            <dd>
                                {{ $saSubmitRequest->address_line1 }}<br>
                                {{ $saSubmitRequest->address_line2 }}<br>
                                {{ $saSubmitRequest->address_line3 }}
                            </dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>Pincode:</dt>
                            <dd>{{ $saSubmitRequest->pincode }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>Education:</dt>
                            <dd>{{ $saSubmitRequest->edu_status === 1 ? 'Educated' : 'Non-Educated' }}</dd>
                        </div>
                    </dl>

                    <dl class="dbim-rq-list">
                        @if($saSubmitRequest->state === 0)
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
                                <dd>{{ $saSubmitRequest->Village->name ?? 'Others' }}</dd>
                            </div>
                            <div class="dbim-rq-pair">
                                <dt>District:</dt>
                                <dd>{{ $saSubmitRequest->District->name ?? 'Others' }}</dd>
                            </div>
                            <div class="dbim-rq-pair">
                                <dt>State:</dt>
                                <dd>{{ $saSubmitRequest->State->name ?? 'Others' }}</dd>
                            </div>
                        @endif
                        <div class="dbim-rq-pair">
                            <dt>Phone:</dt>
                            <dd>{{ $saSubmitRequest->phone_no }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>Mobile:</dt>
                            <dd>{{ $saSubmitRequest->mobile_no }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>Email:</dt>
                            <dd>{{ $saSubmitRequest->email_id }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>BPL:</dt>
                            <dd>{{ $saSubmitRequest->is_bpl == 1 ? 'BPL User' : 'Non BPL User' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </section>

        {{-- ============ Details of RTI Application ============ --}}
        <section class="dbim-rq-sec" aria-labelledby="rtiHeading">
            <h2 class="dbim-rq-sec__head" id="rtiHeading">Details of RTI Application</h2>
            <div class="dbim-rq-sec__body">
                <div class="dbim-rq-cols">
                    <dl class="dbim-rq-list">
                        <div class="dbim-rq-pair">
                            <dt>RTI Request Registration Number:</dt>
                            <dd>{{ $saSubmitRequest->reg_no }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>PIO Name:</dt>
                            <dd>{{ $saSubmitRequest->pio_name }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>RTI Application File:</dt>
                            <dd>
                                <a href="{{ asset($saFiles['newRequestFile']['document_path']) }}" target="_blank" rel="noopener">{{ $saFiles['newRequestFile']['document_name_original'] }}<span class="visually-hidden"> (opens in a new tab)</span></a>
                            </dd>
                        </div>
                    </dl>
                    <dl class="dbim-rq-list">
                        <div class="dbim-rq-pair">
                            <dt>RTI Request Filed on (Date):</dt>
                            <dd>{{ \Carbon\Carbon::parse($saSubmitRequest->newrequest_date)->format('d-m-Y') }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>PIO Address:</dt>
                            <dd>{{ $saSubmitRequest->pio_address }}</dd>
                        </div>
                    </dl>
                </div>

                <hr class="dbim-rq-hr">

                <label for="rti_user_text" class="form-label">Requested Text:</label>
                <textarea class="form-control mb-3" name="user_text" id="rti_user_text" rows="8" readonly>{{ isset($requestedText['rti_req']) ? $requestedText['rti_req'] : '' }}</textarea>
            </div>
        </section>

        {{-- ============ Details of First Appeal Application ============ --}}
        <section class="dbim-rq-sec" aria-labelledby="faHeading">
            <h2 class="dbim-rq-sec__head" id="faHeading">Details of First Appeal Application</h2>
            <div class="dbim-rq-sec__body">
                <div class="dbim-rq-cols">
                    <dl class="dbim-rq-list">
                        <div class="dbim-rq-pair">
                            <dt>First Appeal Registration Number:</dt>
                            <dd>{{ $saSubmitRequest->fa_reg_no }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>PIO Name:</dt>
                            <dd>{{ $saSubmitRequest->pio_name }}</dd>
                        </div>
                    </dl>
                    <dl class="dbim-rq-list">
                        <div class="dbim-rq-pair">
                            <dt>First Appeal Filed on (Date):</dt>
                            <dd>{{ \Carbon\Carbon::parse($saSubmitRequest->faa_date)->format('d-m-Y') }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>PIO Address:</dt>
                            <dd>{{ $saSubmitRequest->pio_address }}</dd>
                        </div>
                    </dl>
                </div>

                <hr class="dbim-rq-hr">

                <dl class="dbim-rq-grid">
                    @if(isset($saFiles['faRequestFile']) &&
                        isset($saFiles['faRequestFile']['document_path']) &&
                        isset($saFiles['faRequestFile']['document_name_original']))
                        <dt>First Appeal File:</dt>
                        <dd>
                            <a href="{{ asset($saFiles['faRequestFile']['document_path']) }}" target="_blank" rel="noopener">{{ $saFiles['faRequestFile']['document_name_original'] }}<span class="visually-hidden"> (opens in a new tab)</span></a>
                        </dd>
                    @else
                        <dt>First Appeal File:</dt>
                        <dd>No file available for the first appeal.</dd>
                    @endif

                    @if(isset($faFiles['faFile']))
                        <dt>First Appeal Application Document:</dt>
                        <dd>
                            <a href="{{ asset($faFiles['faFile']['document_path']) }}" target="_blank" rel="noopener">{{ $faFiles['faFile']['document_name_original'] }}<span class="visually-hidden"> (opens in a new tab)</span></a>
                        </dd>
                    @endif

                    @if(isset($faFiles['supporting_doc']))
                        <dt>First Appeal Supporting File:</dt>
                        <dd>
                            <a href="{{ asset($faFiles['supporting_doc']['document_path']) }}" target="_blank" rel="noopener">{{ $faFiles['supporting_doc']['document_name_original'] }}<span class="visually-hidden"> (opens in a new tab)</span></a>
                        </dd>
                    @endif
                </dl>

                <hr class="dbim-rq-hr">

                <label for="fa_user_text" class="form-label">Requested Text:</label>
                <textarea class="form-control mb-3" name="user_text" id="fa_user_text" rows="8" readonly>{{ isset($requestedText['faa_req']) ? $requestedText['faa_req'] : '' }}</textarea>
            </div>
        </section>

        {{-- ============ Details of Second Appeal Application ============ --}}
        <section class="dbim-rq-sec" aria-labelledby="saHeading">
            <h2 class="dbim-rq-sec__head" id="saHeading">Details of Second Appeal Application</h2>
            <div class="dbim-rq-sec__body">
                <div class="dbim-rq-cols">
                    <dl class="dbim-rq-list">
                        <div class="dbim-rq-pair">
                            <dt>Second Appeal Registration Number:</dt>
                            <dd>{{ $saSubmitRequest->sa_reg_no }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>PIO Name:</dt>
                            <dd>{{ $saSubmitRequest->pio_name }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>FAA Name:</dt>
                            <dd>{{ $saSubmitRequest->faa_name }}</dd>
                        </div>
                    </dl>
                    <dl class="dbim-rq-list">
                        <div class="dbim-rq-pair">
                            <dt>Second Appeal Filed on (Date):</dt>
                            <dd>{{ \Carbon\Carbon::parse($saSubmitRequest->created_at)->format('d-m-Y') }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>PIO Address:</dt>
                            <dd>{{ $saSubmitRequest->pio_address }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>FAA Address:</dt>
                            <dd>{{ $saSubmitRequest->faa_address }}</dd>
                        </div>
                    </dl>
                </div>

                <hr class="dbim-rq-hr">

                <dl class="dbim-rq-grid">
                    <dt>Second Appeal Document:</dt>
                    <dd>
                        <a href="{{ asset($saFiles['saFile']['document_path']) }}" target="_blank" rel="noopener">{{ $saFiles['saFile']['document_name_original'] }}<span class="visually-hidden"> (opens in a new tab)</span></a>
                    </dd>

                    <dt>Id Proof Document:</dt>
                    <dd>
                        <a href="{{ asset($saFiles['idProof']['document_path']) }}" target="_blank" rel="noopener">{{ $saFiles['idProof']['document_name_original'] }}<span class="visually-hidden"> (opens in a new tab)</span></a>
                    </dd>

                    @if(isset($saFiles['supporting_doc']))
                        @foreach($saFiles['supporting_doc'] as $index => $document)
                            <dt>Second Appeal Supporting File {{ $index + 1 }}:</dt>
                            <dd>
                                <a href="{{ asset($document['document_path']) }}" target="_blank" rel="noopener">{{ $document['document_name_original'] }}<span class="visually-hidden"> (opens in a new tab)</span></a>
                            </dd>
                        @endforeach
                    @endif
                </dl>
            </div>
        </section>

        {{-- ============ Officer's Reply ============ --}}
        @if($SA_Reply_To_Applicant != null)
            <section class="dbim-rq-sec" aria-labelledby="replyHeading">
                <h2 class="dbim-rq-sec__head" id="replyHeading">Officer's Reply</h2>
                <div class="dbim-rq-sec__body">
                    @if(!empty($SA_Reply_To_Applicant->reason))
                        <dl class="dbim-rq-list">
                            <div class="dbim-rq-pair">
                                <dt>Reverted Date &amp; Time:</dt>
                                <dd>{{ \Carbon\Carbon::parse($SA_Reply_To_Applicant->created_at)->format('d-m-Y H:i') }}</dd>
                            </div>
                            <div class="dbim-rq-pair dbim-rq-pair--block">
                                <dt>Reverted Reason:</dt>
                                <dd class="dbim-rq-text">{{ $SA_Reply_To_Applicant->reason }}</dd>
                            </div>
                        </dl>
                    @endif
                </div>
            </section>
        @endif

        {{-- ============ Print ============ --}}
        <section class="dbim-rq-sec" aria-labelledby="printHeading">
            <h2 class="dbim-rq-sec__head" id="printHeading">Print Details</h2>
            <div class="dbim-rq-sec__body">
                <a href="/sa-pdf/{{ $saSubmitRequest->id }}" class="download-outline-btn w-ft mt-0"
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