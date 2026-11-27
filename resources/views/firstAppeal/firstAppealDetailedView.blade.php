{{-- ============================================================
     First Appeal request details (officer): New / In Process / Closed
     DBIM / GIGW 3.0. One card on the grey band (.dbim-sd frame).
     Same layout as request-processing.blade.php.
     Styles: dbim.css sections 50, 81, 82, 83 (.dbim-rq-*), 82 (header strip)
     Take Action panels are shown/hidden by the script below
     (data-action = action id).
     ============================================================ --}}
@extends('layouts.app')

@php
    $faStatusId = $faDetails->status_id;
    $pageTitle  = $faStatusId == 1 ? 'First Appeal New Request'
                : ($faStatusId == 2 ? 'First Appeal In Process Request'
                : ($faStatusId == 5 ? 'First Appeal Closed Request' : 'First Appeal Request'));

    $showSubDept = !in_array(session('role_name'), ['Nodal Officer', 'First Appellete Authority']);

    $actionTitles = [
        10 => 'ADMIT APPEAL FOR EXAMINATION',
        11 => 'APPEAL DISPOSE OF',
        12 => 'SEEK COMMENTS FROM SPIO',
        13 => 'RETURN FIRST APPEAL TO APPLICANT',
    ];
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
        @include('OfficerView.officer-header-card', ['showSubDept' => $showSubDept])

        @if ($faDetails->status_id == 5)
            <p class="dbim-rq-status" role="status">Status - Closed</p>
        @endif

        {{-- ============ Public Authority Details ============ --}}
        <section class="dbim-rq-sec" aria-labelledby="rqAuthority">
            <h2 class="dbim-rq-sec__head" id="rqAuthority">Public Authority Details</h2>
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
                    <dd>{{ $departmentDetails['dept'] === null ? 'N/A' : $departmentDetails['dept'] }}</dd>
                    <dt>Office:</dt>
                    <dd>{{ $departmentDetails['office'] === null ? 'N/A' : $departmentDetails['office'] }}</dd>
                    <dt>Sub Office:</dt>
                    <dd>{{ $departmentDetails['suboffice'] === null ? 'N/A' : $departmentDetails['suboffice'] }}</dd>
                </dl>
            </div>
        </section>

        {{-- ============ Personal Details of RTI Applicant ============ --}}
        <section class="dbim-rq-sec" aria-labelledby="rqPersonal">
            <h2 class="dbim-rq-sec__head" id="rqPersonal">Personal Details of RTI Applicant</h2>
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
        <section class="dbim-rq-sec" aria-labelledby="rqRti">
            <h2 class="dbim-rq-sec__head" id="rqRti">Details of RTI Application</h2>
            <div class="dbim-rq-sec__body">
                <div class="dbim-rq-cols">
                    <dl class="dbim-rq-list">
                        <div class="dbim-rq-pair">
                            <dt>RTI Filed Reg_No:</dt>
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
        <section class="dbim-rq-sec" aria-labelledby="rqPio">
            <h2 class="dbim-rq-sec__head" id="rqPio">PIO Response</h2>
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
        <section class="dbim-rq-sec" aria-labelledby="rqFaa">
            <h2 class="dbim-rq-sec__head" id="rqFaa">Details of FAA Application</h2>
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

        {{-- ============ Officer's Reply (disposed) ============ --}}
        @if($reqestDisposeOf != null)
            <section class="dbim-rq-sec" aria-labelledby="rqDispose">
                <h2 class="dbim-rq-sec__head" id="rqDispose">Officer's Reply</h2>
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
                        <div class="dbim-rq-cols">
                            <dl class="dbim-rq-list">
                                @if($reqestDisposeOf->document_path1)
                                    <div class="dbim-rq-pair">
                                        <dt>Upload Document 2:</dt>
                                        <dd>
                                            <a href="{{ asset($officerFile[1]) }}" target="_blank" rel="noopener">{{ $reqestDisposeOf->original_doc_name1 }}<span class="visually-hidden"> (opens in a new tab)</span></a>
                                        </dd>
                                    </div>
                                @endif
                            </dl>
                            <dl class="dbim-rq-list">
                                @if($reqestDisposeOf->document_path2)
                                    <div class="dbim-rq-pair">
                                        <dt>Upload Document 3:</dt>
                                        <dd>
                                            <a href="{{ asset($officerFile[2]) }}" target="_blank" rel="noopener">{{ $reqestDisposeOf->original_doc_name2 }}<span class="visually-hidden"> (opens in a new tab)</span></a>
                                        </dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    @endif
                </div>
            </section>
        @endif

        {{-- ============ Officer's Reply (reverted) ============ --}}
        @if($requestToApplicant != null)
            <section class="dbim-rq-sec" aria-labelledby="rqRevert">
                <h2 class="dbim-rq-sec__head" id="rqRevert">Officer's Reply</h2>
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
        <section class="dbim-rq-sec" aria-labelledby="rqPrint">
            <h2 class="dbim-rq-sec__head" id="rqPrint">Print Details</h2>
            <div class="dbim-rq-sec__body">
                <a href="/inprocess-pdf/{{ $faDetails->id }}" class="download-outline-btn w-ft mt-0"
                   target="_blank" rel="noopener">
                    Print As PDF<span class="visually-hidden"> (opens in a new tab)</span>
                </a>
            </div>
        </section>

        {{-- ============ Take Action ============ --}}
        @if ($faDetails->status_id != 5)
            <section class="dbim-rq-sec" aria-labelledby="rqAction">
                <h2 class="dbim-rq-sec__head" id="rqAction">Take Action</h2>
                <div class="dbim-rq-sec__body">
                    <form action="{{ route('changeFAForm.form') }}" name="faForm" id="faForm" enctype="multipart/form-data" method="POST">
                        @csrf
                        <input type="hidden" name="faSubmitDetails" id="faSubmitDetails" value="{{ $faDetails }}">

                        <div class="row">
                            <div class="col-md-6">
                                <label for="selectedAction" class="form-label fw-bold">Take Actions:</label>
                                <select name="selectedAction" id="selectedAction" class="form-select">
                                    <option value="">Select Action</option>
                                    @foreach ($actionsData as $aData)
                                        <option value="{{ $aData->id }}">{{ $aData->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @foreach([10, 11, 12, 13] as $i)
                            <div class="hiddenAction dbim-rq-action mt-4" data-action="{{ $i }}" role="group"
                                 aria-labelledby="actionTitle{{ $i }}" style="display: none">

                                <h3 class="dbim-rq-action__title" id="actionTitle{{ $i }}">{{ $actionTitles[$i] }}</h3>

                                {{-- 10: Admit appeal - no extra fields --}}

                                {{-- 11: Dispose of --}}
                                @if($i == 11)
                                    <div class="row g-3">
                                        <div class="col-md-8">
                                            <label for="disposal_reason" class="form-label">Reason for Disposal <span class="dbim-required" aria-hidden="true">*</span></label>
                                            <textarea class="form-control" name="disposal_reason" id="disposal_reason" rows="6" aria-required="true"></textarea>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="fa_reply_doc" class="form-label">Reply Document <span class="dbim-required" aria-hidden="true">*</span></label>
                                            <input type="file" class="form-control" name="fa_reply_doc" id="fa_reply_doc" aria-required="true" aria-describedby="fa_reply_doc_hint">
                                            <div id="fa_reply_doc_hint" class="form-text">(File Type .pdf ; File size must not exceed 1 MB)</div>
                                        </div>
                                    </div>
                                @endif

                                {{-- 12: Seek comments from SPIO --}}
                                @if($i == 12)
                                    <div class="row g-3">
                                        <div class="col-md-8">
                                            <label for="spio_comments" class="form-label">Comments from SPIO (Max 2000 characters) <span class="dbim-required" aria-hidden="true">*</span></label>
                                            <textarea class="form-control" name="spio_comments" id="spio_comments" rows="6" aria-required="true"></textarea>
                                        </div>
                                    </div>
                                @endif

                                {{-- 13: Return to applicant --}}
                                @if($i == 13)
                                    <div class="row g-3">
                                        <div class="col-md-8">
                                            <label for="return_reason" class="form-label">Return to Applicant Reason (100 characters) <span class="dbim-required" aria-hidden="true">*</span></label>
                                            <textarea class="form-control" name="return_reason" id="return_reason" rows="6" maxlength="100" aria-required="true"></textarea>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        <div class="d-flex justify-content-end mt-4" id="submitDivElement">
                            <button type="submit" name="send" value="Submit" class="download-btn text-uppercase w-ft mt-0" onclick="validateForm()">Submit</button>
                        </div>
                    </form>
                </div>
            </section>
        @endif

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
    $('select[name="selectedAction"]').on('change', function() {
        getSubForm();
    });

    function getSubForm() {
        var selectedAction = $('select[name="selectedAction"]').val();

        // Hide all elements with the common class
        $('.hiddenAction').hide();

        // Show the specific element based on the selected action
        var specificClass = '.hiddenAction[data-action="' + selectedAction + '"]';
        $(specificClass).show();
    }
});

    function validateForm() {
        // Reset previous validation errors
        $('.error').remove();
        var selectedAction = $('select[name="selectedAction"]').val();

        switch (selectedAction) {

        case '10':
        case '14':
            break;

        case '11':
            var spio_comments = $('#disposal_reason').val();
            if (!spio_comments)
            {
                alert('Please Enter Disposal Reason');
                event.preventDefault();
                return false;
            }

            var replyDocInput = document.getElementById("fa_reply_doc");
            var file = replyDocInput.files[0];

            if (!file) {
                alert('Please select Reply Document');
                event.preventDefault();
                return false;
            }

            if (file.type != "application/pdf") {
                alert('Please select PDF File Only.');
                event.preventDefault();
                return false;
            }

            if (file.size > 1 * 1024 * 1024) {
                alert("File size exceeds 1MB limit.");
                event.preventDefault();
                return false;
            }

            break;

        case '12':
            var spio_comments = $('#spio_comments').val();
            if (!spio_comments)
            {
                alert('Please Enter Comments from SPIO');
                event.preventDefault();
                return false;
            }
            break;

        case '13':
            var return_reason = $('#return_reason').val();
            if(!return_reason)
            {
                alert('Please Enter Return Reason');
                event.preventDefault();
                return false;
            }

            if (return_reason.length > 100) {
                alert('Return Reason should not exceed 100 characters');
                event.preventDefault();
                return false;
            }
        break;

        default:
            alert('Wrong Option Selected');
            event.preventDefault();
            return false;
            break;
        }
    }

</script>
@endpush