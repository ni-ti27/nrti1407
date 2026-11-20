{{-- ============================================================
     Request processing (officer): New / In Process request
     DBIM / GIGW 3.0. One card on the grey band (.dbim-sd frame).
     Take Action panels are shown/hidden by the script below
     (data-action = action id).
     ============================================================ --}}
@extends('layouts.app')

@php
    $statusId  = optional($submitRequest->actionDetails->first())->status_id;
    $pageTitle = $statusId == 1 ? 'New Request' : ($statusId == 2 ? 'In Process Request' : 'Request Details');

    $actionTitles = [
        1  => 'Acceptance of Request (For Processing)',
        2  => 'Forward Request to concerned PIO',
        3  => 'Forward Request to concerned APIO',
        4  => 'RTI Application Document Upload',
        5  => 'Transfering request to another dept',
        6  => 'Request Return to Applicant',
        7  => 'Additional Payment Required For Information',
        8  => 'Request Dispose of',
        9  => 'Applicant Request Rejected',
        18 => 'Partial Transfer Request to Another Authority',
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

        <dl class="dbim-rq-strip">
            <div>
                <dt>Department:</dt>
                <dd>{{ $deptDetails }}</dd>
            </div>
            <div>
                <dt>Username:</dt>
                <dd>{{ $userDetails['name'] }}</dd>
            </div>
            <div>
                <dt>Role:</dt>
                <dd>{{ $userDetails['role_name'] }}</dd>
            </div>
        </dl>

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

        {{-- ============ Take Action ============ --}}
        <section class="dbim-rq-sec" aria-labelledby="rqAction">
            <h2 class="dbim-rq-sec__head" id="rqAction">Take Action</h2>
            <div class="dbim-rq-sec__body">
                <form id="actionForm" enctype="multipart/form-data" action="{{ route('changeAction.form') }}" method="POST">
                    @csrf
                    <input type="hidden" name="submitRequestDetails" value="{{ $submitRequest }}">

                    <div class="row">
                        <div class="col-md-6">
                            <label for="selectedAction" class="form-label fw-bold">Select Action:</label>
                            <select name="selectedAction" id="selectedAction" class="form-select">
                                <option value="">Select Action</option>
                                @foreach($actionDetails as $action)
                                    <option value="{{ $action->id }}">{{ $action->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @foreach([1, 2, 3, 4, 5, 6, 7, 8, 9, 18] as $i)
                        <div class="hiddenAction dbim-rq-action mt-4" data-action="{{ $i }}" role="group"
                             aria-labelledby="actionTitle{{ $i }}" style="display: none">

                            <h3 class="dbim-rq-action__title" id="actionTitle{{ $i }}">{{ $actionTitles[$i] }}</h3>

                            {{-- 1: Acceptance - no extra fields --}}

                            {{-- 2 / 3: Forward to PIO / APIO --}}
                            @if($i == 2 || $i == 3)
                                @php
                                    $deptField   = $i == 2 ? 'applied_dept_forward' : 'applied_dept_forward_apio';
                                    $subField    = $i == 2 ? 'applied_subdept_forward' : 'applied_subdept_forward_apio';
                                    $officeField = $i == 2 ? 'applied_office_forward' : 'applied_office_forward_apio';
                                    $userField   = $i == 2 ? 'pioUser' : 'apioUser';
                                    $userLabel   = $i == 2 ? 'PIO Officer Name' : 'APIO Officer Name';
                                    $reasonField = $i == 2 ? 'forwardReasonPio' : 'forwardReasonApio';
                                @endphp
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="{{ $deptField }}" class="form-label">Department / Public Authority <span class="dbim-required" aria-hidden="true">*</span></label>
                                        <select name="applied_dept_forward" class="form-select" id="{{ $deptField }}" readonly aria-required="true">
                                            <option value="{{ $fullDeptData['department']->dept_code }}">{{ $fullDeptData['department']->department }}</option>
                                        </select>
                                        @error('applied_dept_forward')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="{{ $subField }}" class="form-label">Select Office <span class="dbim-required" aria-hidden="true">*</span></label>
                                        <select name="{{ $subField }}" class="form-select @error($subField) is-invalid @enderror" id="{{ $subField }}" aria-required="true">
                                            <option value="">Select Office</option>
                                        </select>
                                        @error($subField)
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="{{ $officeField }}" class="form-label">Select Sub Office <span class="dbim-required" aria-hidden="true">*</span></label>
                                        <select name="{{ $officeField }}" class="form-select @error($officeField) is-invalid @enderror" id="{{ $officeField }}" aria-required="true">
                                            <option value="">Select Sub Office</option>
                                        </select>
                                        @error($officeField)
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-md-4">
                                        <label for="{{ $userField }}" class="form-label">{{ $userLabel }}</label>
                                        <input class="form-control @error($userField) is-invalid @enderror" type="text" id="{{ $userField }}" name="{{ $userField }}" readonly>
                                        @error($userField)
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-md-8">
                                        <label for="{{ $reasonField }}" class="form-label">Reason for Forwarding <span class="dbim-required" aria-hidden="true">*</span></label>
                                        <textarea class="form-control @error($reasonField) is-invalid @enderror" name="{{ $reasonField }}" id="{{ $reasonField }}" rows="4" maxlength="3000" aria-required="true"></textarea>
                                        @error($reasonField)
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            {{-- 4: Document upload reason --}}
                            @if($i == 4)
                                <div class="row g-3">
                                    <div class="col-md-8">
                                        <label for="req_dispose_reason" class="form-label">Enter Reason for RTI Application Document Upload (1000 Characters Max) <span class="dbim-required" aria-hidden="true">*</span></label>
                                        <textarea class="form-control" name="req_dispose_reason" id="req_dispose_reason" rows="6" maxlength="1000" aria-required="true"></textarea>
                                    </div>
                                </div>
                            @endif

                            {{-- 5: Transfer to another department --}}
                            @if($i == 5)
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="applied_dept" class="form-label">Select Department / Public Authority <span class="dbim-required" aria-hidden="true">*</span></label>
                                        <select name="applied_dept" class="form-select @error('applied_dept') is-invalid @enderror" id="applied_dept" aria-required="true">
                                            <option value="">Select Department</option>
                                            @foreach ($deptData as $id => $department)
                                                <option value="{{ $department->dept_code }}" {{ old('applied_dept') == $department->dept_code ? 'selected' : '' }}>
                                                    {{ $department->department }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('applied_dept')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="applied_subdept" class="form-label">Select Office <span class="dbim-required" aria-hidden="true">*</span></label>
                                        <select name="applied_subdept" class="form-select @error('applied_subdept') is-invalid @enderror" id="applied_subdept" aria-required="true">
                                            <option value="">Select Office</option>
                                        </select>
                                        @error('applied_subdept')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="applied_office" class="form-label">Select Sub Office <span class="dbim-required" aria-hidden="true">*</span></label>
                                        <select name="applied_office" class="form-select @error('applied_office') is-invalid @enderror" id="applied_office" aria-required="true">
                                            <option value="">Select Sub Office</option>
                                        </select>
                                        @error('applied_office')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-md-4">
                                        <label for="nodel_name" class="form-label">Nodal Officer Name</label>
                                        <input class="form-control" type="text" id="nodel_name" name="nodel_name" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="pio_name" class="form-label">PIO Officer Name</label>
                                        <input class="form-control" type="text" id="pio_name" name="pio_name" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="apio_name" class="form-label">APIO Officer Name</label>
                                        <input class="form-control" type="text" id="apio_name" name="apio_name" readonly>
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-md-8">
                                        <label for="transferReason" class="form-label">Reason for Forwarding <span class="dbim-required" aria-hidden="true">*</span></label>
                                        <textarea class="form-control @error('transferReason') is-invalid @enderror" name="transferReason" id="transferReason" rows="4" maxlength="3000" aria-required="true"></textarea>
                                        @error('transferReason')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            {{-- 6: Return to applicant --}}
                            @if($i == 6)
                                <div class="row g-3">
                                    <div class="col-md-8">
                                        <label for="doc_req_reason" class="form-label">Enter Reason for Request Return <span class="dbim-required" aria-hidden="true">*</span></label>
                                        <textarea class="form-control" name="doc_req_reason" id="doc_req_reason" rows="6" maxlength="500" aria-required="true"></textarea>
                                    </div>
                                </div>
                            @endif

                            {{-- 7: Additional payment --}}
                            @if($i == 7)
                                <div class="row g-3">
                                    <div class="col-md-8">
                                        <label for="mode_info_suppy" class="form-label">Select Model of Information Supply</label>
                                        <select name="mode_info_suppy" id="mode_info_suppy" class="form-select">
                                            <option value="" class="d-none">Select Model</option>
                                            <option value="1">Hard Copy</option>
                                            <option value="2">Electronic Media</option>
                                            <option value="3">Priced Publications</option>
                                            <option value="4">Certified Samples of Material</option>
                                            <option value="5">Inspection of Work,Documents,Records</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="additional_amount" class="form-label">Additional Payment Demanded</label>
                                        <div class="input-group">
                                            <span class="input-group-text">₹</span>
                                            <input type="text" name="additional_amount" id="additional_amount" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3 mt-1">
                                    <div class="col-md-8">
                                        <label for="additional_amount_reason" class="form-label">Reason for Additional Payment (Max 255 characters) <span class="dbim-required" aria-hidden="true">*</span></label>
                                        <textarea class="form-control" name="additional_amount_reason" id="additional_amount_reason" rows="6" aria-required="true"></textarea>
                                    </div>
                                </div>
                            @endif

                            {{-- 8: Dispose of --}}
                            @if($i == 8)
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="dispose_of" class="form-label">Interim / Final Action</label>
                                        <select name="dispose_of" id="dispose_of" class="form-select">
                                            <option value="1">Request Dispose of</option>
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="file_no_dispose" class="form-label">File Number</label>
                                        <input type="text" name="file_no_dispose" id="file_no_dispose" class="form-control">
                                    </div>
                                </div>
                                <div class="row g-3 mt-1">
                                    <div class="col-md-8">
                                        <label for="info_provided_citizen" class="form-label">Information Provided to Citizen</label>
                                        <textarea name="info_provided_citizen" id="info_provided_citizen" class="form-control" rows="4"></textarea>
                                    </div>
                                </div>
                                <div class="row g-3 mt-1">
                                    <div class="col-md-4">
                                        <label for="disposal_type" class="form-label">Type of Disposal</label>
                                        <select name="disposal_type" id="disposal_type" class="form-select">
                                            <option value="0">Select Disposal Type</option>
                                            <option value="FullInfo">Full Information Supplied</option>
                                            <option value="PartInfo">Part of Information Supplied</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="reply_doc" class="form-label">Upload Document 1</label>
                                        <input type="file" class="form-control" name="reply_doc" id="reply_doc" aria-describedby="reply_doc_hint">
                                        <div id="reply_doc_hint" class="form-text">(File Type .pdf)</div>
                                    </div>
                                </div>
                                <div class="row g-3 mt-1">
                                    <div class="col-md-4">
                                        <label for="reply_doc1" class="form-label">Upload Document 2</label>
                                        <input type="file" class="form-control" name="reply_doc1" id="reply_doc1" aria-describedby="reply_doc1_hint">
                                        <div id="reply_doc1_hint" class="form-text">(File Type .pdf, .doc, .docx, .txt, .xls, .xlsx, .jpg, .jpeg)</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="reply_doc2" class="form-label">Upload Document 3</label>
                                        <input type="file" class="form-control" name="reply_doc2" id="reply_doc2" aria-describedby="reply_doc2_hint">
                                        <div id="reply_doc2_hint" class="form-text">(File Type .pdf, .doc, .docx, .txt, .xls, .xlsx, .jpg, .jpeg)</div>
                                    </div>
                                </div>
                            @endif

                            {{-- 9: Rejected --}}
                            @if($i == 9)
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="file_no" class="form-label">File Number</label>
                                        <input type="text" name="file_no" id="file_no" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="reason_therefore" class="form-label">Reason Therefore</label>
                                        <select name="reason_therefore" id="reason_therefore" class="form-select">
                                            <option value="0" class="d-none">Select Reason</option>
                                            <option value="1">Section 9</option>
                                            <option value="2">Section 11</option>
                                            <option value="3">Section 24</option>
                                            <option value="4">Section 8(1)(a)</option>
                                        </select>
                                        <a href="{{ asset($filePath) }}" target="_blank" rel="noopener" class="d-inline-block mt-2">View Details of Sections for Rejection<span class="visually-hidden"> (opens in a new tab)</span></a>
                                    </div>
                                </div>
                            @endif

                            {{-- 18: Partial transfer (blocks are added by the script) --}}
                            @if($i == 18)
                                <div>
                                    <button type="button" class="download-btn text-uppercase w-ft mt-0" id="add-dropdown">Add Dropdown</button>
                                </div>
                                <div id="dropdown-container" class="mt-3"></div>
                            @endif
                        </div>
                    @endforeach

                    <div class="d-flex justify-content-end mt-4" id="submitDivElement">
                        <button type="submit" name="send" value="Submit" class="download-btn text-uppercase w-ft mt-0" onclick="validateForm()">Submit</button>
                    </div>
                </form>
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

    /* ---------- Partial transfer: add / remove blocks ---------- */
    let dropdownCount = 0;

    document.getElementById('add-dropdown').addEventListener('click', () => {
        dropdownCount++;
        const n = dropdownCount;
        const container = document.getElementById('dropdown-container');

        const block = document.createElement('div');
        block.id = `dropdown_${n}`;

        const departmentOptions = @json($deptData);
        let deptOptions = '<option value="">Select Department</option>';
        departmentOptions.forEach(dept => {
            deptOptions += `<option value="${dept.dept_code}">${dept.department}</option>`;
        });

        block.innerHTML = `
            <hr>
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="partial_department_${n}" class="form-label">Select Department <span class="dbim-required" aria-hidden="true">*</span></label>
                    <select name="partial_departments[]" id="partial_department_${n}" class="department-dropdown form-select" data-id="${n}" aria-required="true">${deptOptions}</select>
                </div>
                <div class="col-md-4">
                    <label for="partial_subdepartment_${n}" class="form-label">Select Office <span class="dbim-required" aria-hidden="true">*</span></label>
                    <select name="partial_subdepartments[]" id="partial_subdepartment_${n}" class="sub-department-dropdown form-select" data-id="${n}" aria-required="true">
                        <option value="">Select Office</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="partial_office_${n}" class="form-label">Select Sub Office <span class="dbim-required" aria-hidden="true">*</span></label>
                    <select name="partial_offices[]" id="partial_office_${n}" class="office-department-dropdown form-select" data-id="${n}" aria-required="true">
                        <option value="">Select Sub Office</option>
                    </select>
                </div>
            </div>
            <div class="row g-3 mt-1">
                <div class="col-md-4">
                    <label for="partial_nodal_${n}" class="form-label">Nodal Officer</label>
                    <input type="text" id="partial_nodal_${n}" name="partial_nodal_officer[]" class="form-control partial_nodal" data-id="${n}" readonly>
                </div>
                <div class="col-md-4">
                    <label for="partial_pio_${n}" class="form-label">PIO Officer</label>
                    <input type="text" id="partial_pio_${n}" name="partial_pio_officer[]" class="form-control partial_pio" data-id="${n}" readonly>
                </div>
                <div class="col-md-4">
                    <label for="partial_apio_${n}" class="form-label">APIO Officer</label>
                    <input type="text" id="partial_apio_${n}" name="partial_apio_officer[]" class="form-control partial_apio" data-id="${n}" readonly>
                </div>
            </div>
            <div class="row g-3 mt-1">
                <div class="col-md-9">
                    <label for="partial_reason_${n}" class="form-label">Give Reason for Partial Transfer</label>
                    <textarea id="partial_reason_${n}" class="form-control" name="partialReason[]" rows="3"></textarea>
                </div>
            </div>
            <button type="button" class="download-outline-btn text-uppercase w-ft remove-dropdown" data-id="${n}">Remove</button>
        `;

        container.appendChild(block);

        block.querySelector('.department-dropdown').addEventListener('change', (event) => {
            updatePartialNodal(n);
            updateSubDepartments(event.target.value, n);
        });
        block.querySelector('.sub-department-dropdown').addEventListener('change', () => {
            updateOfficeDepartments(n);
        });
        block.querySelector('.office-department-dropdown').addEventListener('change', () => {
            updatePartialPioApio(n);
        });
    });

    function updatePartialNodal(dropdownId)
    {
        const deptDropdown = document.querySelector(`#dropdown_${dropdownId} .department-dropdown`);
        const partialNodal = document.querySelector(`#dropdown_${dropdownId} .partial_nodal`);

        fetch(`/getNodalName/${deptDropdown.value}`)
            .then(response => response.json())
            .then(data => {
                console.log(data);
                partialNodal.value = `${data.username} - ${data.name}`;
                partialNodal.readOnly = true;
            });

    }

    function updatePartialPioApio(dropdownId)
    {
        const deptDropdown = document.querySelector(`#dropdown_${dropdownId} .department-dropdown`);
        const subDeptDropdown = document.querySelector(`#dropdown_${dropdownId} .sub-department-dropdown`);
        const officeDeptDropdown = document.querySelector(`#dropdown_${dropdownId} .office-department-dropdown`);
        const partialPio = document.querySelector(`#dropdown_${dropdownId} .partial_pio`);
        const partialApio = document.querySelector(`#dropdown_${dropdownId} .partial_apio`);

        let baseUrl = `/getOfficerName/2`;
        let url = new URL(baseUrl, window.location.origin);
        const params = new URLSearchParams({
            deptId: deptDropdown.value,
            subDeptId: subDeptDropdown.value,
            officeId: officeDeptDropdown.value
        });

        url.search = params.toString();

        fetch(url)
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if(data.id == '' || data.id == null)
                    partialPio.value = `PIO Not Available`;
                else
                    partialPio.value = `${data.username} - ${data.name}`;

                partialPio.readOnly = true;

            });


        baseUrl = `/getOfficerName/3`;
        url = new URL(baseUrl, window.location.origin);
        url.search = params.toString();

        fetch(url)
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if(data.id == '' || data.id == null)
                    partialApio.value = `APIO Not Available`;
                else
                    partialApio.value = `${data.username} - ${data.name}`;

                partialApio.readOnly = true;

            });

    }


    function updateSubDepartments(departmentCode, dropdownId)
    {
        const subDeptDropdown = document.querySelector(`#dropdown_${dropdownId} .sub-department-dropdown`);
        subDeptDropdown.innerHTML = '<option value="">Select Office</option>'; // Clear existing options

        fetch(`/subDeptDropdown/${departmentCode}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(subDept => {
                    // console.log(subDept);
                    subDeptDropdown.innerHTML += `<option value="${subDept.office_code}">${subDept.department}</option>`;
                });
            });
    }

    function updateOfficeDepartments(dropdownId)
    {
        const officeDropdown = document.querySelector(`#dropdown_${dropdownId} .office-department-dropdown`);
        const deptDropdown = document.querySelector(`#dropdown_${dropdownId} .department-dropdown`);
        const subDeptDropdown = document.querySelector(`#dropdown_${dropdownId} .sub-department-dropdown`);
        officeDropdown.innerHTML = '<option value="">Select Sub Office</option>'; // Clear existing options

        fetch(`/officeDropdown/${deptDropdown.value}/${subDeptDropdown.value}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(office => {
                    // console.log(office);
                    officeDropdown.innerHTML += `<option value="${office.suboffice_code}">${office.department}</option>`;
                });
            });
    }


    document.getElementById('dropdown-container').addEventListener('click', (e) =>
    {
        if (e.target.classList.contains('remove-dropdown')) {
            const id = e.target.getAttribute('data-id');
            const dropdown = document.getElementById(`dropdown_${id}`);
            if (dropdown) {
                dropdown.remove();
            }
        }
    });

    $(document).ready(function()
    {
        populateSubDeptDropdown(true);

    $('select[name="selectedAction"]').on('change', function() {
        getSubForm();
    });

    $('select[name="applied_dept"]').on('change', function() {
        populateSubDeptDropdown();
    });

    // Event listener for District dropdown change
    $('select[name="applied_subdept"]').on('change', function() {
        populateOfficeDropdown();
    });

    $('select[name="applied_subdept_forward"]').on('change', function() {
        populateOfficeDropdown(true);
    });

    $('select[name="applied_subdept_forward_apio"]').on('change', function() {
        populateOfficeDropdown(true);
    });

    $('select[name="applied_office_forward"]').on('change', function() {
        populateOfficerName($('select[name="selectedAction"]').val(),true);
    });

    $('select[name="applied_office_forward_apio"]').on('change', function() {
        populateOfficerName($('select[name="selectedAction"]').val(),true);
    });

    $('select[name="applied_office"').on('change', function(){
        populateOfficerName(2);
        populateOfficerName(3);
    });

     // Function to populate Sub Department dropdown
    function populateSubDeptDropdown(forwardFlag = false)
    {
        var deptSelector = forwardFlag ? 'select[name="applied_dept_forward"]' : 'select[name="applied_dept"]';
        var subDeptSelector = forwardFlag ? 'select[name="applied_subdept_forward"]' : 'select[name="applied_subdept"]';
        var subDeptSelector_apio = forwardFlag ? 'select[name="applied_subdept_forward_apio"]' : 'select[name="applied_subdept"]';

        var deptId = $(deptSelector).val();
        var subDeptSelect = $(subDeptSelector);
        var subDeptSelect_apio = $(subDeptSelector_apio);

        if (deptId) {
            $.ajax({
                url: '/subDeptDropdown/' + deptId,
                type: "GET",
                dataType: "json",
                success: function(data) {
                if(forwardFlag)
                {
                    subDeptSelect_apio.empty().append('<option value="">Select Office</option>');

                    if (data && data.length > 0) {
                    $.each(data, function(key, value) {
                        var option = $('<option value="' + value.office_code + '">' + value.department + '</option>');
                        subDeptSelect_apio.append(option);
                        subDeptSelect.append(option.clone());
                    });
                    } else {
                        subDeptSelect_apio.append('<option value="">No offices found</option>');
                    }
                }
                else
                {
                    subDeptSelect.empty().append('<option value="">Select Office</option>');

                    if (data && data.length > 0) {
                    $.each(data, function(key, value) {
                        var option = $('<option value="' + value.office_code + '">' + value.department + '</option>');
                        subDeptSelect.append(option);
                    });
                    } else {
                        subDeptSelect.append('<option value="">No offices found</option>');
                    }
                }

                if (!forwardFlag) {
                    populateOfficeDropdown();
                } else {
                    populateOfficeDropdown(true);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching sub departments:', error);
                // Optionally handle errors, e.g., display a message to the user
            }
            });
        } else {
            subDeptSelect.empty().append('<option value="">Select Office</option>');
            subDeptSelect_apio.empty().append('<option value="">Select Office</option>');

            // Optionally call populateOfficeDropdown based on forwardFlag
            if (!forwardFlag) {
                populateOfficeDropdown();
            } else {
                populateOfficeDropdown(true);
            }
        }
    }


        // Function to populate Nodal name dynamically
        function populateNodalName(forwardFlag = false)
        {
            var deptSelector = forwardFlag ? 'select[name="applied_dept_forward"]' : 'select[name="applied_dept"]';
            var targetField = forwardFlag ? '#nodel_name' : '#nodel_name';
            var deptId = $(deptSelector).val();

            if (deptId) {
                $.ajax({
                    url: '/getNodalName/' + deptId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        // $(targetField).val(data['username']+ '[' +data['id']+'] - '+data['name']);
                        $(targetField).val(data['username']+ ' - '+data['name']);
                    }
                });
            }
        }

        // Function to populate pio name dynamically
        function populateOfficerName(role, forwardFlag = false)
        {
            var deptSelector = forwardFlag ? 'select[name="applied_dept_forward"]' : 'select[name="applied_dept"]';
            var subDeptSelector = forwardFlag ? 'select[name="applied_subdept_forward"]' : 'select[name="applied_subdept"]';
            var subDeptSelector_apio = forwardFlag ? 'select[name="applied_subdept_forward_apio"]' : 'select[name="applied_subdept"]';
            var officeSelector = forwardFlag ? 'select[name="applied_office_forward"]' : 'select[name="applied_office"]';
            var officeSelector_apio = forwardFlag ? 'select[name="applied_office_forward_apio"]' : 'select[name="applied_office"]';

            var deptId = $(deptSelector).val();
            var subDeptId = $(subDeptSelector).val() || 0;
            var officeId = $(officeSelector).val() || 0;

            var subDeptId_apio = $(subDeptSelector_apio).val() || 0;
            var officeId_apio = $(officeSelector_apio).val() || 0;

            if(subDeptId == 0)
            {
                subDeptId = subDeptId_apio;
                officeId = officeId_apio;
            }

            if (deptId) {
                $.ajax({
                    url: '/getOfficerName/' + role,
                    type: "GET",
                    dataType: "json",
                    data: {
                        'deptId': deptId,
                        'subDeptId': subDeptId,
                        'officeId': officeId,
                    },
                    success: function(data) {
                        // console.log(data['name'] + '--' + data['id']);
                        if (role == 2) {
                            var targetField = forwardFlag ? '#pioUser' : '#pio_name';
                            var defaultText = forwardFlag ? 'PIO not available' : 'PIO not available';
                        } else if(role == 3){
                            var targetField = forwardFlag ? '#apioUser' : '#apio_name';
                            var defaultText = forwardFlag ? 'APIO not available' : 'APIO not available';
                        }

                        if (typeof data['name'] !== 'undefined' && data['name'] !== null)
                            // $(targetField).val(data['username']+ '[' +data['id']+'] - '+data['name']);
                            $(targetField).val(data['username']+ ' - ' +data['name']);
                        else
                            $(targetField).val(defaultText);
                    }
                });
            }
        }

    // Function to populate Sub Department dropdown
    function populateOfficeDropdown(forwardFlag = false)
    {
        var deptSelector = forwardFlag ? 'select[name="applied_dept_forward"]' : 'select[name="applied_dept"]';
        var subDeptSelector = forwardFlag ? 'select[name="applied_subdept_forward"]' : 'select[name="applied_subdept"]';
        var subDeptSelector_apio = forwardFlag ? 'select[name="applied_subdept_forward_apio"]' : 'select[name="applied_subdept"]';
        var officeSelector = forwardFlag ? 'select[name="applied_office_forward"]' : 'select[name="applied_office"]';
        var officeSelector_apio = forwardFlag ? 'select[name="applied_office_forward_apio"]' : 'select[name="applied_office"]';

        var deptId = $(deptSelector).val();
        var subDeptId = $(subDeptSelector).val();
        var subDeptId_apio = $(subDeptSelector_apio).val();

        // Initialize officeSelect and officeSelect_apio as jQuery objects
        var officeSelect = $(officeSelector);
        var officeSelect_apio = $(officeSelector_apio);

        if (subDeptId) {
            $.ajax({
                url: '/officeDropdown/' + deptId + '/' + subDeptId,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    if (data && Object.keys(data).length > 0) {
                        officeSelect.empty();
                        $.each(data, function(key, value) {
                            var option = $('<option value="' + value.suboffice_code + '">' + value.department + '</option>');
                            officeSelect.append(option.clone());
                        });
                    } else {
                        officeSelect.empty().append('<option value="0">Select Sub Office</option>');
                    }
                }
            });
        } else {
            if(subDeptId_apio == '')
                return 0;

            $.ajax({
                url: '/officeDropdown/' + deptId + '/' + subDeptId_apio,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    if (data && Object.keys(data).length > 0) {
                        officeSelect_apio.empty();
                        $.each(data, function(key, value) {
                            var option = $('<option value="' + value.suboffice_code + '">' + value.department + '</option>');
                            officeSelect_apio.append(option.clone());
                        });
                    } else {
                        officeSelect_apio.empty().append('<option value="0">Select Sub Office</option>');
                    }
                }
            });
        }

        if (forwardFlag) {
            populateOfficerName($('select[name="selectedAction"]').val(), true);
        } else {
            populateNodalName();
            populateOfficerName(2);
            populateOfficerName(3);
        }
    }

    function getSubForm() {
        var selectedAction = $('select[name="selectedAction"]').val();
        console.log('Selected Action:', selectedAction);

        // Hide all elements with the common class
        $('.hiddenAction').hide();

        // Show the specific element based on the selected action
        var specificClass = '.hiddenAction[data-action="' + selectedAction + '"]';
        $(specificClass).show();
    }

});

    function handleForwardingAction(userType)
    {
        var userSelector = '';
        var userLabel = '';
        var reasonSelector = '';

        if (userType === 'pio')
        {
            userSelector = '#pioUser';
            userLabel = 'PIO';
            reasonSelector = '#forwardReasonPio';
        }
        else if (userType === 'apio')
        {
            userSelector = '#apioUser';
            userLabel = 'APIO';
            reasonSelector = '#forwardReasonApio';
        }
        else
        {
            alert('Please Select ' + userLabel + ' User');
            event.preventDefault();
            return false;
        }

        var user = $(userSelector).val();
        var forwardReason = $(reasonSelector).val();

        if (!user)
        {
            alert('Please Select ' + userLabel + ' User');
            event.preventDefault();
            return false;
        }
        else if (user === userLabel + ' not available')
        {
            alert(userLabel + ' User is Not Available');
            event.preventDefault();
            return false;
        }

        if (!forwardReason)
        {
            alert('Please Enter Forwarding Reason');
            event.preventDefault();
            return false;
        }

        var confirmed = confirm("Forward Request to " + userLabel + " : " + user);
        if (!confirmed)
        {
            event.preventDefault();
            return false;
        }

        // If all checks pass
        return true;
    }

    function validateForm()
    {
        // Reset previous validation errors
        $('.error').remove();
        var selectedAction = $('select[name="selectedAction"]').val();

        switch (selectedAction) {
            case '1':
                var confirmed = confirm("Are you sure you want to submit?");
                if(confirmed != true)
                {
                    event.preventDefault();
                    return false;
                }
                break;

            case '2':
                if (!handleForwardingAction('pio')) {
                    return false;
                }
                break;

            case '3':
                if (!handleForwardingAction('apio')) {
                    return false;
                }
                break;

            case '4':
                var requestDisposeReason = $('#req_dispose_reason').val();

                if(requestDisposeReason == null || requestDisposeReason.trim() == '')
                {
                    alert('Please Enter Dispose Reason');
                    event.preventDefault();
                    return false;
                }
                else if(requestDisposeReason.length > 1000)
                {
                    alert('Limit for the dispose reason is 1000 words.');
                    event.preventDefault();
                    return false;
                }

                var confirmed = confirm("Are you sure you want to submit?");
                if(confirmed != true)
                {
                    event.preventDefault();
                    return false;
                }
                break;

            case '5':
                // Transfer to another dept
                var appliedDept = document.getElementById("applied_dept").value;
                if (!appliedDept)
                {
                    alert('Please Select Applied Dept');
                    event.preventDefault();
                    return false;
                }

                var appliedOffice = $('#applied_subdept').val();
                if (!appliedOffice)
                {
                    alert('Please Select Applied Office');
                    event.preventDefault();
                    return false;
                }

                var appliedSubOffice = $('#applied_office').val();
                if (!appliedSubOffice)
                {
                    alert('Please Select Applied Sub Office');
                    event.preventDefault();
                    return false;
                }

                var transferReason = $('#transferReason').val();
                if(!transferReason)
                {
                    alert('Please Enter Reason for Transfering Request');
                    event.preventDefault();
                    return false;
                }

                var applied_deptName = $('#applied_dept option:selected').text();
                var applied_subdeptName = $('#applied_subdept option:selected').text();
                var applied_officeName = $('#applied_office option:selected').text();

                var nodel_name = $('#nodel_name').val();
                var pio_name = $('#pio_name').val();
                var apio_name = $('#apio_name').val();

                var msg = "Transfer Request to : "+applied_deptName.trim()+" => "+applied_subdeptName.trim()+ "=> "+applied_officeName.trim()+ "? \n\n Nodal Officer Name : "+nodel_name+ "\n PIO Officer Name : "+pio_name+"\n APIO Officer Name : "+apio_name;

                var confirmed = confirm(msg);
                if(confirmed != true)
                {
                    event.preventDefault();
                    return false;
                }
                break;

            case '6':
                // Request Return to Applicant

                var documentRequireReason = $('#doc_req_reason').val();
                if(documentRequireReason == null || documentRequireReason.trim() == '')
                {
                    alert('Please Enter Return Reason');
                    event.preventDefault();
                    return false;
                }
                else if(documentRequireReason.length > 1000)
                {
                    alert('Limit for the return reason is 1000 words.');
                    event.preventDefault();
                    return false;
                }

                var confirmed = confirm("Are you sure you want to submit?");
                if(confirmed != true)
                {
                    event.preventDefault();
                    return false;
                }
                break;

            case '7':
                // Additional Payment Required for Info
                var infoSuppyMode = document.getElementById("mode_info_suppy").value;

                if(infoSuppyMode == null || infoSuppyMode == '')
                {
                    alert('Please Select Model of Information Supply');
                    event.preventDefault();
                    return false;
                }

                var amount = $('#additional_amount').val();
                if(isNaN(amount))
                {
                    alert('Please Enter Valid Amount');
                    event.preventDefault();
                    return false;
                }
                else if(amount < 25)
                {
                    alert("Online RTI Services .. emphasis on providing RTI Information in Digital SoftCopy Form. \n\nSo in case the Volume of Information Replied/Supplied is Low, then it is suggested to Scan and Upload the reply to the Applicant. \n\nAdditional Payment may be insisted for Genuine cases wherein Cost involved is Rs. 25/- and above.");
                    event.preventDefault();
                    return false;
                }

                var reasonAdditionalAmount = $('#additional_amount_reason').val();
                if(reasonAdditionalAmount == null || reasonAdditionalAmount.trim() == '')
                {
                    alert('Please Enter Reason for Additional Payment');
                    event.preventDefault();
                    return false;
                }
                else if(reasonAdditionalAmount.length > 255)
                {
                    alert('Limit for the Additional Payment reason is 255 words.');
                    event.preventDefault();
                    return false;
                }

                var confirmed = confirm("Charge applicant additional payment of ₹ "+amount+ "?");
                if(confirmed != true)
                {
                    event.preventDefault();
                    return false;
                }

                break;

            case '8':
                // Request Dispose of
                var disposeOf = document.getElementById("dispose_of").value;
                if(disposeOf == null || disposeOf == '')
                {
                    alert('Please Select Dispose of');
                    event.preventDefault();
                    return false;
                }

                var fileNumber = $('#file_no_dispose').val();
                if(fileNumber == null || fileNumber.trim() == '')
                {
                    alert('Please Enter File Number');
                    event.preventDefault();
                    return false;
                }

                var informationForApplicant = $('#info_provided_citizen').val();
                if(informationForApplicant == null || informationForApplicant.trim() == '')
                {
                    alert('Please Enter Information Provided to Citizen');
                    event.preventDefault();
                    return false;
                }
                else if(informationForApplicant.length > 300)
                {
                    alert('Limit for the Information Provided to Citizen is 300 words.');
                    event.preventDefault();
                    return false;
                }

                var disposalType = document.getElementById("disposal_type").value;
                if(disposalType == 0)
                {
                    alert('Please Select Disposal Type');
                    event.preventDefault();
                    return false;
                }

                var replyDocInput = document.getElementById("reply_doc");
                var file = replyDocInput.files[0];
                if (file.type != "application/pdf") {
                    alert('Please select PDF File Only.');
                    event.preventDefault();
                    return false;
                }
                else if (file.size > 5 * 1024 * 1024) {
                    alert("Maximum 5MB upload allowed as per Web Security guidelines. If information supplied is voluminious then please use other medias such as USB Drives, CDs, Hard Drives, etc to supply information to Applicant.");
                    event.preventDefault();
                    return false;
                }

                var replyDocInput2 = document.getElementById("reply_doc2");
                var file2 = replyDocInput2.files[0];
                var allowedTypes = [
                    "application/pdf",         // PDF
                    "application/vnd.ms-excel", // Excel (.xls)
                    "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", // Excel (.xlsx)
                    "image/jpeg",              // JPEG
                    "image/jpg",               // JPG
                    "image/png",               // PNG (if needed)
                    "application/msword",      // Word (.doc)
                    "application/vnd.openxmlformats-officedocument.wordprocessingml.document", // Word (.docx)
                    "text/plain"               // Text (.txt)
                ];

                var replyDocInput1 = document.getElementById("reply_doc1");
                var file1 = replyDocInput1.files[0];
                if (!allowedTypes.includes(file1.type)) {
                    alert('Please select PDF, EXCEL, IMAGE or WORD Document File Only.');
                    event.preventDefault();
                    return false;
                }
                else if (file1.size > 5 * 1024 * 1024) {
                    alert("Maximum 5MB upload allowed as per Web Security guidelines. If information supplied is voluminious then please use other medias such as USB Drives, CDs, Hard Drives, etc to supply information to Applicant.");
                    event.preventDefault();
                    return false;
                }

                var replyDocInput2 = document.getElementById("reply_doc2");
                var file2 = replyDocInput2.files[0];
                if (!allowedTypes.includes(file2.type)) {
                    alert('Please select PDF, EXCEL, IMAGE or WORD Document File Only.');
                    event.preventDefault();
                    return false;
                }
                else if (file2.size > 5 * 1024 * 1024) {
                    alert("Maximum 5MB upload allowed as per Web Security guidelines. If information supplied is voluminious then please use other medias such as USB Drives, CDs, Hard Drives, etc to supply information to Applicant.");
                    event.preventDefault();
                    return false;
                }

                var confirmed = confirm("Are you sure to File the Disposal Request?");
                if(confirmed != true)
                {
                    event.preventDefault();
                    return false;
                }

                break;

            case '9':
                // Applicant Request Rejected
                var fileNo = $('#file_no').val();
                if(fileNo == '' || fileNo == null)
                {
                    alert('Please Enter File Number');
                    event.preventDefault();
                    return false;
                }

                var reasonTherefore = document.getElementById("reason_therefore").value;
                if(reasonTherefore == '' || reasonTherefore == 0)
                {
                    alert('Please Enter Reason Therefore');
                    event.preventDefault();
                    return false;
                }
                break;

            case '18':

                // event.preventDefault();

                let isValid = true;
                const errorMessages = [];
                let firstInvalidElement = null;

                const departmentDropdowns = document.querySelectorAll('.department-dropdown');
                const subDepartmentDropdowns = document.querySelectorAll('.sub-department-dropdown');
                const officeDropdowns = document.querySelectorAll('.office-department-dropdown');
                // FIX: the fields are named partialReason[], so the old selector never matched
                const reasonTextareas = document.querySelectorAll('textarea[name="partialReason[]"]');

                departmentDropdowns.forEach(dropdown => {
                    if (dropdown.value === '') {
                        if (!firstInvalidElement) firstInvalidElement = dropdown;
                        isValid = false;
                        errorMessages.push('Please select a department.');
                        return;
                    }
                });

                subDepartmentDropdowns.forEach(dropdown => {
                    if (dropdown.value === '') {
                        if (!firstInvalidElement) firstInvalidElement = dropdown;
                        isValid = false;
                        errorMessages.push('Please select a sub-department.');
                    }
                });

                officeDropdowns.forEach(dropdown => {
                    if (dropdown.value === '') {
                        if (!firstInvalidElement) firstInvalidElement = dropdown;
                        isValid = false;
                        errorMessages.push('Please select an office.');
                    }
                });

                reasonTextareas.forEach(textarea => {
                    if (textarea.value.trim() === '') {
                        if (!firstInvalidElement) firstInvalidElement = textarea;
                        isValid = false;
                        errorMessages.push('Please provide a reason for partial transfer.');
                    }
                });

                if (!isValid) {
                    alert(errorMessages.join('\n'));
                    if (firstInvalidElement) {
                        firstInvalidElement.focus();
                    }
                    event.preventDefault();
                }
                break;

            default:
                // Handle other actions or no action selected
                break;
        }
    }
</script>
@endpush