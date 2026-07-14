<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<style>
    .container.mt-5 {
        /* border: 2px solid black; */
        /* border-bottom: none; */
        border-top: none;
        margin-top: 0 !important;
    }

    .table {
        border: none;
    }

    .table td,
    .table th {
        border: none;
        /* padding: 0.5rem; */
    }

    .text-right {
        text-align: right;
    }
</style>

<body>
    @include('UsersView.header')

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="text-center">
                            <br>
                            <h4 class="mb-0">{{ session('faaDeptName') }}</h4>
                            <br>
                            @if ($faDetails->status_id == 1)
                                <h5 class="mb-0">First Appeal New Request</h5>
                            @elseif ($faDetails->status_id == 2)
                                <h5 class="mb-0">First Appeal In Process Request</h5>
                            @elseif ($faDetails->status_id == 5)
                                <h5 class="mb-0">First Appeal Closed Request</h5>
                            @endif
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <br><br>
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="row">
                    @if ($faDetails->status_id == 5)
                        <div class="text-center alert alert-success">
                            Status - Closed
                        </div>
                    @endif
                </div>

                <!-- Public Authority Details -->
                <div class="card mb-4 mx-auto">
                    <h5 class="card-header">Public Authority Details:</h5>
                    <div class="card-body text-center">
                        <div class="row">
                            <div class="col-md-6">
                                <p style="text-align: left;"><strong>FA Reg No.:</strong> {{ $faDetails->fa_reg_no }}</p>
                            </div>
                            <div class="col-md-6 text-end">
                                <p><strong>Filed Date:</strong> {{ \Carbon\Carbon::parse($faDetails->created_at)->format('d-m-Y H:i') }}</p>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table mx-auto">
                                <tbody>
                                    <tr>
                                        <td class="text-right"><strong>Department:</strong></td>
                                        <td>{{ $departmentDetails['dept'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-right"><strong>Office:</strong></td>
                                        <td>{{ $departmentDetails['office'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-right"><strong>Sub Office:</strong></td>
                                        <td>{{ $departmentDetails['suboffice'] ?? 'N/A' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Personal Details of RTI Applicant -->
                <div class="card">
                    <h5 class="card-header">Personal Details of RTI Applicant</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Name:</strong> {{ $faDetails->name }}</p>
                                <p><strong>Address Line1:</strong> {{ $faDetails->address_line1 }}</p>
                                <p><strong>Address Line3:</strong> {{ $faDetails->address_line3 }}</p>
                                <p><strong>Country:</strong> India</p>
                                <p><strong>District:</strong> {{ $uploadedDocs['district'] }}</p>
                                <p><strong>Phone No.:</strong> {{ $faDetails->phone_no == '0' ? 'NA' : $faDetails->phone_no }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Gender:</strong> {{ $faDetails->gender }}</p>
                                <p><strong>Address Line2:</strong> {{ $faDetails->address_line2 }}</p>
                                <p><strong>Pincode:</strong> {{ $faDetails->pincode }}</p>
                                <p><strong>State:</strong> {{ $faDetails->stateName ?? 'Others' }}</p>
                                <p><strong>Village:</strong> {{ $uploadedDocs['village'] }}</p>
                                <p><strong>Mobile No.:</strong> {{ $faDetails->mobile_no }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <br>

                <!-- Details of RTI Application -->
                <div class="card">
                    <h5 class="card-header">Details of RTI Application</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>RTI Filed Reg_No:</strong> {{ $faDetails->reg_no }}</p>
                                <p><strong>Requested Text:</strong> {{ $faDetails->sr_req_note }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>RTI Filed Date:</strong> {{ \Carbon\Carbon::parse($faDetails->submitted_date)->format('d-m-Y') }}</p>
                                <p><strong>Copy of the RTI Application:</strong>
                                    <a href="{{ Storage::url($faDetails->document_path) }}" target="_blank">
                                        {{ $faDetails->document_name_original }}
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <br>

                <!-- PIO Response -->
                <div class="card">
                    <h5 class="card-header">PIO Response</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>PIO Response:</strong> {{ $uploadedDocs['details']->reason }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Reply of PIO:</strong>
                                    @if($uploadedDocs['pioReplyDoc'] != null)
                                        &nbsp;&nbsp;
                                        <a href="{{ Storage::url($uploadedDocs['pioReplyDoc']['document_path']) }}" target="_blank">
                                            {{ $uploadedDocs['pioReplyDoc']['document_name_original'] }}
                                        </a>
                                    @else
                                        File Not Found
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <br>

                <!-- Details of FAA Application -->
                <div class="card">
                    <h5 class="card-header">Details of FAA Application</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Registration Number:</strong> {{ $faDetails->fa_reg_no }}</p>
                                <p><strong>Requested Text:</strong> {{ $faDetails->fa_req_note }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>FA Received:</strong> {{ \Carbon\Carbon::parse($faDetails->created_at)->format('d-m-Y') }}</p>
                                <p><strong>Supporting Document:</strong>
                                    @if($uploadedDocs['suppDoc'] != null)
                                        &nbsp;&nbsp;
                                        <a href="{{ Storage::url($uploadedDocs['suppDoc']['document_path']) }}" target="_blank">
                                            {{ $uploadedDocs['suppDoc']['document_name_original'] }}
                                        </a>
                                    @else
                                        File Not Found
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <br>

                <!-- Officer's Reply -->
                @if($faAction != null)
                    <div class="card">
                        <h5 class="card-header">Officer's Reply:</h5>
                        <div class="card-body">
                            @if(!empty($faAction->return_to_applicant_reason))
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>
                                            <strong>Reverted Date & Time:</strong>
                                            {{ \Carbon\Carbon::parse($faAction->created_at)->format('d-m-Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p>
                                            <strong>Reverted Reason:</strong> {{ $faAction->return_to_applicant_reason }}
                                        </p>
                                    </div>
                                </div>
                            @elseif(!empty($faAction->disposal_reason))
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>
                                            <strong>Disposal Date & Time:</strong>
                                            {{ \Carbon\Carbon::parse($faAction->created_at)->format('d-m-Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p>
                                            <strong>Disposal Reason:</strong> {{ $faAction->disposal_reason }}
                                        </p>
                                        @if(!empty($faAction->document_path) && !empty($faAction->document_name))
                                            <p>
                                                <strong>Disposal Reply:</strong>
                                                <a href="{{ asset($faAction->document_path) }}" target="_blank">
                                                    {{ $faAction->document_name }}
                                                </a>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <p>No officer reply available yet.</p>
                            @endif
                        </div>
                    </div>
                @endif
               <!-- Print Details -->
                <div class="card">
                    <h5 class="card-header">Print Details:</h5>
                    <div class="card-body">
                        <a href="/inprocess-pdf/{{$faDetails->id}}" target="_blank">Print As PDF</a>
                    </div>
                </div>


            </div>
        </div>
        <br><br>
    </div>

    @include('UsersView.footer')
</body>