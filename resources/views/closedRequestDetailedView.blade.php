{{-- ============================================================
     Request details (officer): Reverted / Rejected / Closed
     DBIM / GIGW 3.0. One card on the grey band (.dbim-sd frame).
     ============================================================ --}}
@extends('layouts.app')

@php
    $statusId = optional($submitRequest->actionDetails->first())->status_id;

    $pageTitle = match ((int) $statusId) {
        1       => 'New Request',
        2       => 'In Process Request',
        3       => 'Reverted Request',
        4       => 'Rejected Request',
        5       => 'Closed Request',
        default => 'Request Details',
    };

    $statusText = match ((int) $statusId) {
        3       => 'Status - Reverted',
        4       => 'Status - Rejected',
        5       => 'Status - Closed',
        default => null,
    };
@endphp

@section('title', $pageTitle . ' | RTI Online, Government of Goa')

@section('content')
<div class="dbim-sd">
  <div class="dbim-sd-section">
    <div class="dbim-sd-wrap">

      @include('partials.flash-messages')

      <div class="dbim-rq-card">

        {{-- ============ Title + department / username / role strip ============ --}}
        <h1 class="dbim-rq-title">{{ $pageTitle }}</h1>

        {{-- ===== Office / user details ===== --}}
        @include('OfficerView.officer-header-card')


        @if($statusText)
            <p class="dbim-rq-status" role="status">{{ $statusText }}</p>
        @endif

        {{-- ============ Public Authority Details ============ --}}
        <section class="dbim-rq-sec" aria-labelledby="rqAuthority">
            <h2 class="dbim-rq-sec__head" id="rqAuthority">Public Authority Details</h2>
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

        {{-- ============ Personal Details ============ --}}
        <section class="dbim-rq-sec" aria-labelledby="rqPersonal">
            <h2 class="dbim-rq-sec__head" id="rqPersonal">Personal Details of RTI Applicant</h2>
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
                                @foreach(array_filter([$submitRequest->address_line1, $submitRequest->address_line2, $submitRequest->address_line3]) as $line)
                                    <span class="d-block">{{ $line }}</span>
                                @endforeach
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
                        <div class="dbim-rq-pair">
                            <dt>Village:</dt>
                            <dd>{{ $submitRequest->state === 0 ? 'Others' : ($submitRequest->Village->name ?? 'Others') }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>District:</dt>
                            <dd>{{ $submitRequest->state === 0 ? 'Others' : ($submitRequest->District->name ?? 'Others') }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>State:</dt>
                            <dd>{{ $submitRequest->state === 0 ? 'Others' : ($submitRequest->State->name ?? 'Others') }}</dd>
                        </div>
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

        {{-- ============ Application Details ============ --}}
        <section class="dbim-rq-sec" aria-labelledby="rqApplication">
            <h2 class="dbim-rq-sec__head" id="rqApplication">Application Details</h2>
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
                            <dt>Other Options:</dt>
                            <dd>N/A</dd>
                        </div>
                    </dl>
                    <dl class="dbim-rq-list">
                        <div class="dbim-rq-pair">
                            <dt>Does it concern life or liberty of a person:</dt>
                            <dd>N/A</dd>
                        </div>
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
            <section class="dbim-rq-sec" aria-labelledby="rqPayment">
                <h2 class="dbim-rq-sec__head" id="rqPayment">Payment Details</h2>
                <div class="dbim-rq-sec__body">
                    <div class="table-responsive">
                        <table class="table table-light table-bordered mb-0">
                            <caption class="visually-hidden">Payments made for this request</caption>
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

        {{-- ============ Officer's Reply: disposed ============ --}}
        @if($reqestDisposeOf != null)
            <section class="dbim-rq-sec" aria-labelledby="rqReplyDisposed">
                <h2 class="dbim-rq-sec__head" id="rqReplyDisposed">Officer's Reply</h2>
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
                                <dt>Upload Document 1:</dt>
                                <dd>
                                    <a href="{{ asset($officerFile[0]) }}" target="_blank" rel="noopener">{{ $reqestDisposeOf->original_doc_name }}<span class="visually-hidden"> (opens in a new tab)</span></a>
                                </dd>
                            </div>
                        </dl>
                        <dl class="dbim-rq-list">
                            <div class="dbim-rq-pair">
                                <dt>Disposed Date &amp; Time (Re-Uploaded):</dt>
                                <dd>{{ \Carbon\Carbon::parse($reqestDisposeOf->updated_at)->format('d-m-Y H:i') }}</dd>
                            </div>
                            <div class="dbim-rq-pair">
                                <dt>Disposal Type:</dt>
                                <dd>{{ $reqestDisposeOf->disposal_type }}</dd>
                            </div>
                            <div class="dbim-rq-pair">
                                <dt>Disposal Reason:</dt>
                                <dd>{{ $reqestDisposeOf->reason }}</dd>
                            </div>
                        </dl>
                    </div>

                    @if($reqestDisposeOf->document_path1 || $reqestDisposeOf->document_path2)
                        <hr class="dbim-rq-hr">
                        <h3 class="dbim-rq-action__title">Additional Files</h3>
                        <dl class="dbim-rq-list">
                            @if($reqestDisposeOf->document_path1)
                                <div class="dbim-rq-pair">
                                    <dt>Upload Document 2:</dt>
                                    <dd>
                                        <a href="{{ asset($officerFile[1]) }}" target="_blank" rel="noopener">{{ $reqestDisposeOf->original_doc_name1 }}<span class="visually-hidden"> (opens in a new tab)</span></a>
                                    </dd>
                                </div>
                            @endif
                            @if($reqestDisposeOf->document_path2)
                                <div class="dbim-rq-pair">
                                    <dt>Upload Document 3:</dt>
                                    <dd>
                                        <a href="{{ asset($officerFile[2]) }}" target="_blank" rel="noopener">{{ $reqestDisposeOf->original_doc_name2 }}<span class="visually-hidden"> (opens in a new tab)</span></a>
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    @endif

                    @if($reqestDisposeOf->updated_at >= \Carbon\Carbon::now()->subDays(3) || $reqestDisposeOf->allow_reupload == true)
                        <hr class="dbim-rq-hr">

                        <form id="actionForm" enctype="multipart/form-data" action="{{ route('updateOfficerReplyFile') }}" method="POST">
                            @csrf
                            <input type="hidden" name="submitRequestDetails" value="{{ $submitRequest }}">

                            <button type="button" id="edit-button" class="download-btn text-uppercase w-ft mt-0"
                                    aria-expanded="false" aria-controls="file-upload">Edit</button>

                            <div class="row g-3 mt-2" style="display:none" id="file-upload">
                                <h3 class="dbim-rq-action__title">Re-Upload Reply</h3>

                                <div class="col-md-4">
                                    <label for="file-input1" class="form-label">Replacement for File 1:</label>
                                    <input type="file" id="file-input1" name="officerFile1" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label for="file-input2" class="form-label">Replacement for File 2:</label>
                                    <input type="file" id="file-input2" name="officerFile2" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label for="file-input3" class="form-label">Replacement for File 3:</label>
                                    <input type="file" id="file-input3" name="officerFile3" class="form-control">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" id="submit-button" class="download-btn text-uppercase w-ft mt-0" style="display: none;">Submit</button>
                            </div>
                        </form>
                    @endif
                </div>
            </section>
        @endif

        {{-- ============ Officer's Reply: reverted to applicant ============ --}}
        @if($requestToApplicant != null)
            <section class="dbim-rq-sec" aria-labelledby="rqReplyReverted">
                <h2 class="dbim-rq-sec__head" id="rqReplyReverted">Officer's Reply</h2>
                <div class="dbim-rq-sec__body">
                    <dl class="dbim-rq-list">
                        <div class="dbim-rq-pair">
                            <dt>Reverted Date &amp; Time:</dt>
                            <dd>{{ \Carbon\Carbon::parse($requestToApplicant->created_at)->format('d-m-Y H:i') }}</dd>
                        </div>
                        <div class="dbim-rq-pair">
                            <dt>Reverted Reason:</dt>
                            <dd>{{ $requestToApplicant->reason }}</dd>
                        </div>
                    </dl>
                </div>
            </section>
        @endif

        {{-- ============ Print ============ --}}
        <section class="dbim-rq-sec" aria-labelledby="rqPrint">
            <h2 class="dbim-rq-sec__head" id="rqPrint">Print Details</h2>
            <div class="dbim-rq-sec__body">
                <a href="{{ url('/generate-pdf/' . $submitRequest->id) }}" class="download-outline-btn w-ft mt-0"
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

@push('scripts')
{{-- jQuery: loads only if the layout has not already loaded it --}}
<script>
    window.jQuery || document.write('<script src="https://code.jquery.com/jquery-3.6.4.min.js"><\/script>');
</script>

<script type="text/javascript">
$(document).ready(function() {
    $('#file-upload').hide();

    $('#edit-button').on('click', function() {
        $('#file-upload').show().addClass('d-flex');
        $(this).attr('aria-expanded', 'true');
    });

    $('input[type="file"]').on('change', function() {
        const anyFileSelected = $('input[type="file"]').toArray().some(input => input.files.length > 0);
        $('#submit-button').toggle(anyFileSelected);
    });
});
</script>
@endpush