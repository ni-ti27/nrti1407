{{-- ============================================================
     First Appeal Details (officer, read-only)
     DBIM / GIGW 3.0. One card on the grey band (.dbim-sd frame).
     Same layout as request-processing.blade.php.
     Styles: dbim.css sections 50, 81, 82, 83 (.dbim-rq-*), 82 (header strip)
     ============================================================ --}}
@extends('layouts.app')

@php
    $faStatusId  = $faDetails->status_id;
    $pageTitle   = $faStatusId == 1 ? 'First Appeal New Request'
                 : ($faStatusId == 2 ? 'First Appeal In Process Request'
                 : ($faStatusId == 5 ? 'First Appeal Closed Request' : 'First Appeal Details'));
    $showSubDept = !in_array(session('role_name'), ['Nodal Officer', 'First Appellete Authority']);
@endphp

@section('title', 'First Appeal Details | RTI Online, Government of Goa')

@section('content')
<div class="dbim-sd">
  <div class="dbim-sd-section">
    <div class="dbim-sd-wrap">

      @include('partials.flash-messages')

      <div class="dbim-rq-card">

        {{-- ============ Title + department / username / role strip ============ --}}
        <h1 class="dbim-rq-title">{{ $pageTitle }}</h1>

        @if ($faDetails->status_id == 5)
            <p class="dbim-rq-status" role="status">Status - Closed</p>
        @endif

        {{-- ============ Public Authority Details ============ --}}
        <section class="dbim-rq-sec" aria-labelledby="authorityHeading">
            <h2 class="dbim-rq-sec__head" id="authorityHeading">Public Authority Details</h2>
            <div class="dbim-rq-sec__body">
                <dl class="dbim-rq-meta">
                    <div>
                        <dt>FA Reg No.:</dt>
                        <dd>{{ $faDetails->fa_reg_no }}</dd>
                    </div>
                    <div>
                        <dt>Filed Date:</dt>
                        <dd>{{ \Carbon\Carbon::parse($faDetails->created_at)->format('d-m-Y H:i') }}</dd>
                    </div>
                </dl>

                <hr class="dbim-rq-hr">

                <dl class="dbim-rq-grid">
                    <dt>Department:</dt>
                    <dd>{{ $departmentDetails['dept'] ?? 'N/A' }}</dd>
                    <dt>Office:</dt>
                    <dd>{{ $departmentDetails['office'] ?? 'N/A' }}</dd>
                    <dt>Sub Office:</dt>
                    <dd>{{ $departmentDetails['suboffice'] ?? 'N/A' }}</dd>
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
                            <dt>Name:</dt>
                            <dd>{{ $faDetails->name }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>Address Line1:</dt>
                            <dd>{{ $faDetails->address_line1 }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>Address Line3:</dt>
                            <dd>{{ $faDetails->address_line3 }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>Country:</dt>
                            <dd>India</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>District:</dt>
                            <dd>{{ $uploadedDocs['district'] }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>Phone No.:</dt>
                            <dd>{{ $faDetails->phone_no == '0' ? 'NA' : $faDetails->phone_no }}</dd>
                        </div>
                    </dl>

                    <dl class="dbim-rq-list">
                        <div class="dbim-rq-pair">
                            <dt>Gender:</dt>
                            <dd>{{ $faDetails->gender }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>Address Line2:</dt>
                            <dd>{{ $faDetails->address_line2 }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>Pincode:</dt>
                            <dd>{{ $faDetails->pincode }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>State:</dt>
                            <dd>{{ $faDetails->stateName ?? 'Others' }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>Village:</dt>
                            <dd>{{ $uploadedDocs['village'] }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>Mobile No.:</dt>
                            <dd>{{ $faDetails->mobile_no }}</dd>
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
                            <dt>RTI Filed Reg No:</dt>
                            <dd>{{ $faDetails->reg_no }}</dd>
                        </div>
                    </dl>
                    <dl class="dbim-rq-list">
                        <div class="dbim-rq-pair">
                            <dt>RTI Filed Date:</dt>
                            <dd>{{ \Carbon\Carbon::parse($faDetails->submitted_date)->format('d-m-Y') }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>Copy of the RTI Application:</dt>
                            <dd>
                                <a href="{{ Storage::url($faDetails->document_path) }}" target="_blank" rel="noopener">{{ $faDetails->document_name_original }}<span class="visually-hidden"> (opens in a new tab)</span></a>
                            </dd>
                        </div>
                    </dl>
                </div>

                <hr class="dbim-rq-hr">

                <dl class="dbim-rq-list">
                    <div class="dbim-rq-pair dbim-rq-pair--block">
                        <dt>Requested Text:</dt>
                        <dd class="dbim-rq-text">{{ $faDetails->sr_req_note }}</dd>
                    </div>
                </dl>
            </div>
        </section>

        {{-- ============ PIO Response ============ --}}
        <section class="dbim-rq-sec" aria-labelledby="pioHeading">
            <h2 class="dbim-rq-sec__head" id="pioHeading">PIO Response</h2>
            <div class="dbim-rq-sec__body">
                <div class="dbim-rq-cols">
                    <dl class="dbim-rq-list">
                        <div class="dbim-rq-pair dbim-rq-pair--block">
                            <dt>PIO Response:</dt>
                            <dd class="dbim-rq-text">{{ $uploadedDocs['details']->reason }}</dd>
                        </div>
                    </dl>
                    <dl class="dbim-rq-list">
                        <div class="dbim-rq-pair">
                            <dt>Reply of PIO:</dt>
                            <dd>
                                @if($uploadedDocs['pioReplyDoc'] != null)
                                    <a href="{{ Storage::url($uploadedDocs['pioReplyDoc']['document_path']) }}" target="_blank" rel="noopener">{{ $uploadedDocs['pioReplyDoc']['document_name_original'] }}<span class="visually-hidden"> (opens in a new tab)</span></a>
                                @else
                                    File Not Found
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </section>

        {{-- ============ Details of FAA Application ============ --}}
        <section class="dbim-rq-sec" aria-labelledby="faaHeading">
            <h2 class="dbim-rq-sec__head" id="faaHeading">Details of FAA Application</h2>
            <div class="dbim-rq-sec__body">
                <div class="dbim-rq-cols">
                    <dl class="dbim-rq-list">
                        <div class="dbim-rq-pair">
                            <dt>Registration Number:</dt>
                            <dd>{{ $faDetails->fa_reg_no }}</dd>
                        </div>
                    </dl>
                    <dl class="dbim-rq-list">
                        <div class="dbim-rq-pair">
                            <dt>FA Received:</dt>
                            <dd>{{ \Carbon\Carbon::parse($faDetails->created_at)->format('d-m-Y') }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>Supporting Document:</dt>
                            <dd>
                                @if($uploadedDocs['suppDoc'] != null)
                                    <a href="{{ Storage::url($uploadedDocs['suppDoc']['document_path']) }}" target="_blank" rel="noopener">{{ $uploadedDocs['suppDoc']['document_name_original'] }}<span class="visually-hidden"> (opens in a new tab)</span></a>
                                @else
                                    File Not Found
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>

                <hr class="dbim-rq-hr">

                <dl class="dbim-rq-list">
                    <div class="dbim-rq-pair dbim-rq-pair--block">
                        <dt>Requested Text:</dt>
                        <dd class="dbim-rq-text">{{ $faDetails->fa_req_note }}</dd>
                    </div>
                </dl>
            </div>
        </section>

        {{-- ============ Officer's Reply ============ --}}
        @if($faAction != null)
            <section class="dbim-rq-sec" aria-labelledby="replyHeading">
                <h2 class="dbim-rq-sec__head" id="replyHeading">Officer's Reply</h2>
                <div class="dbim-rq-sec__body">
                    @if(!empty($faAction->return_to_applicant_reason))
                        <dl class="dbim-rq-list">
                            <div class="dbim-rq-pair">
                                <dt>Reverted Date &amp; Time:</dt>
                                <dd>{{ \Carbon\Carbon::parse($faAction->created_at)->format('d-m-Y H:i') }}</dd>
                            </div>
                            <div class="dbim-rq-pair dbim-rq-pair--block">
                                <dt>Reverted Reason:</dt>
                                <dd class="dbim-rq-text">{{ $faAction->return_to_applicant_reason }}</dd>
                            </div>
                        </dl>
                    @elseif(!empty($faAction->disposal_reason))
                        <dl class="dbim-rq-list">
                            <div class="dbim-rq-pair">
                                <dt>Disposal Date &amp; Time:</dt>
                                <dd>{{ \Carbon\Carbon::parse($faAction->created_at)->format('d-m-Y H:i') }}</dd>
                            </div>
                            @if(!empty($faAction->document_path) && !empty($faAction->document_name))
                                <div class="dbim-rq-pair">
                                    <dt>Disposal Reply:</dt>
                                    <dd>
                                        <a href="{{ asset($faAction->document_path) }}" target="_blank" rel="noopener">{{ $faAction->document_name }}<span class="visually-hidden"> (opens in a new tab)</span></a>
                                    </dd>
                                </div>
                            @endif
                            <div class="dbim-rq-pair dbim-rq-pair--block">
                                <dt>Disposal Reason:</dt>
                                <dd class="dbim-rq-text">{{ $faAction->disposal_reason }}</dd>
                            </div>
                        </dl>
                    @else
                        <p class="mb-3">No officer reply available yet.</p>
                    @endif
                </div>
            </section>
        @endif

        {{-- ============ Print ============ --}}
        <section class="dbim-rq-sec" aria-labelledby="printHeading">
            <h2 class="dbim-rq-sec__head" id="printHeading">Print Details</h2>
            <div class="dbim-rq-sec__body">
                <a href="/inprocess-pdf/{{ $faDetails->id }}" class="download-outline-btn w-ft mt-0"
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