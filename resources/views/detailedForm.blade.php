<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<style>
.container.mt-5
    {
        /* border: 2px solid black; */
        /* border-bottom: none; */
        border-top:none;
        margin-top:0 !important;
    }
</style>

<body>
    @include('OfficerView.Header')
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="text-center">
                                <br>
                            <h5 class="mb-0"> {{ $deptDetails }}</h5><br>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-start">
                                        <h5 class="mb-0">Username: {{ $userDetails['name'] }}</h5>
                                    </div>
                                    <div class="text-end">
                                        <h5 class="mb-0">Role: {{ $userDetails['role_name'] }} </h5>
                                    </div>
                                </div>
                                <br>
                                @if ($submitRequest->actionDetails->first()->status_id == 1)
                                    <h5 class="mb-0">New Request</h5>
                                @elseif ($submitRequest->actionDetails->first()->status_id == 2)
                                    <h5 class="mb-0">In Process Request</h5>
                                @endif
                                <br>

                                <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <div class="container mt-5">
                <br>
                <br>
                <div class="row justify-content-center">
                    <div class="col-md-9">
                        <!-- Public Authority Details -->
                        <div class="card mb-4 mx-auto">
                            <h5 class="card-header">Public Authority Details:</h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p style="text-align: left;"><strong>Reg No.:</strong> {{ $submitRequest->reg_no }}</p>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <p><strong>Filed Date:</strong> {{ \Carbon\Carbon::parse($submitRequest->created_at)->format('d-m-Y H:i') }}</p>
                                    </div>
                                </div>
                                <br>
                                <div class="row justify-content-center">
                                    <div class="col-md-3">
                                        <p><strong>Department:</strong></p>
                                        <p><strong>Office:</strong></p>
                                        <p><strong>Sub Office:</strong></p>
                                    </div>
                                    <div class="col-md-3">
                                        <p>{{ $deptDetails }}</p>
                                        <p>{{ $officeDetails }}</p>
                                        <p>{{ $subOfficeDetails }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Personal Details -->
                        <div class="card">
                            <h5 class="card-header">Personal Details of RTI Applicant</h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Applicant Name:</strong> {{ $submitRequest->name }}</p>
                                        <p><strong>Gender:</strong> {{ $submitRequest->gender === 'female' ? 'Female' : ($submitRequest->gender === 'male' ? 'Male' : 'Other') }}</p>
                                        <p><strong>Address:</strong><br>
                                            {{ $submitRequest->address_line1 }}<br>
                                            {{ $submitRequest->address_line2 }}<br>
                                            {{ $submitRequest->address_line3 }}
                                        </p>
                                        <p><strong>Pincode:</strong> {{ $submitRequest->pincode }} </p>

                                    </div>
                                    <div class="col-md-6">
                                        @if($submitRequest->state === 0)
                                            <p><strong>Village:</strong> Others</p>
                                            <p><strong>District:</strong> Others</p>
                                            <p><strong>State:</strong> Others</p>
                                        @else
                                            <p><strong>Village:</strong> {{ $submitRequest->Village->name ?? 'Others' }}</p>
                                            <p><strong>District:</strong> {{ $submitRequest->District->name ?? 'Others' }}</p>
                                            <p><strong>State:</strong> {{ $submitRequest->State->name ?? 'Others' }}</p>
                                        @endif
                                        <p><strong>Phone:</strong> {{ $submitRequest->phone_no }}</p>
                                        <p><strong>Mobile:</strong> {{ $submitRequest->mobile_no }}</p>
                                        <p><strong>Email:</strong> {{ $submitRequest->email_id }}</p>
                                    </div>

                                    <div class="col-md-6">
                                    <p><strong>Education:</strong> {{ $submitRequest->edu_status === 1 ? 'Educated' : 'Non-Educated' }}</p>
                                    <p><strong>BPL:</strong> {{ $submitRequest->is_bpl == 1 ? 'BPL User' : 'Non BPL User' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Qualification:</strong>
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
                                        </p>
                                        <p><strong>Card No.:</strong> {{ $submitRequest->card_no === 0 ? 'N/A' : $submitRequest->card_no }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <!-- Application Details -->
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    @if($submitRequest->is_bpl === 0)
                                    <div class="col-md-6">
                                        <p><strong>Amount Paid (Rs.):</strong> 10 </p>
                                    </div>
                                    @endif
                                    <div class="col-md-6">
                                        <p><strong>Other Options:</strong> N/A</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="padding-top: 15px"><strong>Text From Applicant:</strong></p>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="border p-3">
                                        <p>{{ $submitRequest->request_text }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="padding-top: 10px">
                                    <div class="col-md-8">
                                        <p><strong>Does it concern life or liberty of a person: </strong> N/A</p>
                                    </div>
                                </div>
                                <div class="row" style="padding-top: 10px">
                                    <div class="col-md-6">
                                        <p><strong>File Uploaded by Applicant: </strong><a href="{{ asset($filePath) }}" target="_blank">{{ $submitRequest->document_name_original }}</a></p>
                                    </div>
                                    {{-- <embed src="{{ asset($filePath) }}" type="application/pdf" width="100%" height="600px" /> --}}
                                </div>
                            </div>
                        </div>
                        <br>

                        @if(!$paymentDetails->isEmpty())
                        <div class="card">
                            <h5 class="card-header">Payment Details:</h5>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Sr. No</th>
                                        <th>Registration No.</th>
                                        <th>Amount (Rs.)</th>
                                        <th>e-Challan No.</th>
                                        <th>Transaction Date & Time</th>
                                        <th>Mobile Number</th>
                                        <th>Status</th>
                                        <!-- <th>Reprint Challan</th> -->
                                    </tr>
                                    @foreach($paymentDetails as $index => $payment)
                                    <tr>
                                        <td>{{$index + 1}}</td>
                                        <td>{{$submitRequest->reg_no}}</td>
                                        <td>{{$payment->total_amt}}</td>
                                        <td>{{$payment->echallan_no}}</td>
                                        <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('d-m-Y H:i') }}</td>
                                        <td>{{$submitRequest->mobile_no}}</td>
                                        <td>Success</td>
                                        <!-- <td><a href="https://echallanpg.goa.gov.in/print_ereceipt.aspx"></a></td> -->
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                        @endif
                        <br>

                        <div class="card">
                            <h5 class="card-header">Print Details:</h5>
                            <div class="card-body">
                                <a href="/generate-pdf/{{$submitRequest->id}}" target="_blank">Print As PDF</a>
                            </div>
                        </div>

                        <br>
                        <div class="card">
                            <div class="card-body">
                                <form id="actionForm" enctype="multipart/form-data" action="{{ route('changeAction.form') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="submitRequestDetails" value="{{ $submitRequest }}">
                                    <div class="row justify-content-center">
                                        <div class="col-md-6">
                                            <p><strong>Take Action:</strong>
                                                <select name="selectedAction" id="selectedAction" class="form-select">
                                                    <option value="">Select Action</option>
                                                    @foreach($actionDetails as $action)
                                                        <option value="{{ $action->id }}">{{ $action->name }}</option>
                                                    @endforeach
                                                </select>
                                            </p>
                                        </div>
                                    </div>

                                    @for ($i = 1; $i <= 18; $i++)
                                    @if (!in_array($i, [1, 2, 3, 4, 5, 6, 7, 8, 9, 18]))
                                        @continue;
                                    @endif
                                        <div class="row justify-content-center hiddenAction" data-action="{{ $i }}" style="display: none">
                                            <div class="col-md-12">
                                                {{-- working --}}
                                                @if ($i == 1)
                                                <div class="row justify-content-center hiddenAction1">
                                                    <br>
                                                    <h4 class="d-block text-center">Acceptance of Request (For Processing)</h4>
                                                    {{-- <input type="text" class="form-control" name="acceptance" id="acceptance" placeholder="Enter Remarks" required> --}}
                                                    <br>
                                                </div>
                                                @endif

                                                @if($i == 2 || $i == 3)
                                                <div class="row justify-content-center hiddenAction5">
                                                    <div class="col-md-12 ">
                                                        <br>
                                                        @if($i == 2)
                                                        <h4 class="d-block text-center">Forward Request to concerned PIO</h4>
                                                        @elseif($i == 3)
                                                        <h4 class="d-block text-center">Forward Request to concerned APIO</h4>
                                                        @endif
                                                        <br>
                                                        <div class="row justify-content-center">
                                                            <div class="form-group col-md-4 mb-2 ">
                                                                <label>Department / Public Authority</label><span class="text-danger">*</span>
                                                                <select name="applied_dept_forward" class="form-control" id="applied_dept_forward" readonly>
                                                                    <option value="{{ $fullDeptData['department']->dept_code }}">{{$fullDeptData['department']->department}} </option>
                                                                </select>
                                                                @error('applied_dept_forward')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>

                                                            @if($i == 2)
                                                            <div class="form-group col-md-4 mb-2">
                                                                <label for="applied_subdept_forward">Select Office</label><span class="text-danger">*</span>
                                                                <select name="applied_subdept_forward" class="form-control" id="applied_subdept_forward">
                                                                    <option value="">Select Office</option>
                                                                </select>
                                                                @error('applied_subdept_forward')
                                                                <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </div>

                                                            <div class="form-group col-md-4 mb-2">
                                                                <label for="applied_office_forward">Select Sub Office</label><span class="text-danger">*</span>
                                                                <select name="applied_office_forward" class="form-control" id="applied_office_forward">
                                                                    <option value="">Select Sub Office</option>
                                                                </select>
                                                                @error('applied_office_forward')
                                                                <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </div>
                                                            @elseif($i == 3)
                                                            <div class="form-group col-md-4 mb-2">
                                                                <label for="applied_subdept_forward_apio">Select Office</label><span class="text-danger">*</span>
                                                                <select name="applied_subdept_forward_apio" class="form-control" id="applied_subdept_forward_apio">
                                                                    <option value="">Select Office</option>
                                                                </select>
                                                                @error('applied_subdept_forward_apio')
                                                                <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </div>

                                                            <div class="form-group col-md-4 mb-2">
                                                                <label for="applied_office_forward_apio">Select Sub Office</label><span class="text-danger">*</span>
                                                                <select name="applied_office_forward_apio" class="form-control" id="applied_office_forward_apio">
                                                                    <option value="">Select Sub Office</option>
                                                                </select>
                                                                @error('applied_office_forward_apio')
                                                                <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </div>
                                                            @endif
                                                        </div>
                                                        <br>

                                                        <div class="row justify-content-center">
                                                            @if($i == 2)
                                                            <div class="form-group col-md-4 mb-2 ">
                                                                <label for="pioUser">PIO Officer Name</label>
                                                                <input  class="form-control" type="text" id="pioUser" name="pioUser" readonly>
                                                                @error('pioUser')
                                                                <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </div>
                                                            @elseif($i == 3)
                                                            <div class="form-group col-md-4 mb-2 ">
                                                                <label for="apioUser">APIO Officer Name</label>
                                                                <input  class="form-control" type="text" id="apioUser" name="apioUser" readonly>
                                                                @error('apioUser')
                                                                <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </div>
                                                            @endif
                                                        </div>

                                                        <div class="row justify-content-center">
                                                            @if($i == 2)
                                                            <div class="form-group col-md-8 mb-2">
                                                                <label for="forwardReasonPio">Reason for Forwarding</label><span class="text-danger">*</span>
                                                                <textarea style="height: 100px" class="form-control" name="forwardReasonPio" maxlength="3000" id="forwardReasonPio"></textarea>
                                                                @error('forwardReasonPio')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </div>
                                                            @elseif($i == 3)
                                                            <div class="form-group col-md-8 mb-2">
                                                                <label for="forwardReasonApio">Reason for Forwarding</label><span class="text-danger">*</span>
                                                                <textarea style="height: 100px" class="form-control" name="forwardReasonApio" maxlength="3000" id="forwardReasonApio"></textarea>
                                                                @error('forwardReasonApio')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                {{-- working  --}}
                                                @if ($i == 4)
                                                <div class="row justify-content-center hiddenAction4">
                                                    <br>
                                                    <br>
                                                    <h4 class="d-block text-center">RTI Application Document Upload</h4>
                                                    <br>
                                                    <div class="row justify-content-center">
                                                        <div class="form-group col-md-8 mb-2">
                                                            <label for="req_dispose_reason">Enter Reason for RTI Application Document Upload (1000 Characters Max)</label><span class="text-danger">*</span>
                                                            <textarea class="form-control" name="req_dispose_reason" id="req_dispose_reason" style="height: 160px" maxlength="1000"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                {{-- working --}}
                                                @if ($i == 5)
                                                <div class="row justify-content-center hiddenAction5">
                                                    <div class="col-md-12 ">
                                                        <br>
                                                        <h4 class="d-block text-center">Transfering request to another dept</h4>
                                                        <br>
                                                        <div class="row justify-content-center">
                                                            <div class="form-group col-md-4 mb-2 ">
                                                                <label>Select Department / Public Authority</label><span class="text-danger">*</span>
                                                                <select name="applied_dept" class="form-control" id="applied_dept">
                                                                    <option value="">Select Department</option>
                                                                    @foreach ($deptData as $id => $department)
                                                                        <option value="{{ $department->dept_code }}" {{ old('applied_dept') == $department->dept_code ? 'selected' : '' }}>
                                                                            {{ $department->department }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('applied_dept')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>

                                                            <div class="form-group col-md-4 mb-2">
                                                                <label for="applied_subdept">Select Office</label><span class="text-danger">*</span>
                                                                <select name="applied_subdept" class="form-control" id="applied_subdept">
                                                                    <option value="">Select Office</option>
                                                                </select>
                                                                @error('applied_subdept')
                                                                <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </div>

                                                            <div class="form-group col-md-4 mb-2">
                                                                <label for="applied_office">Select Sub Office</label><span class="text-danger">*</span>
                                                                <select name="applied_office" class="form-control" id="applied_office">
                                                                    <option value="">Select Sub Office</option>
                                                                </select>
                                                                @error('applied_office')
                                                                <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="row justify-content-center">
                                                            <div class="form-group col-md-4 mb-2 ">
                                                                <label for="nodel_name">Nodal Officer Name</label>
                                                                <input  class="form-control" type="text" id="nodel_name" name="nodel_name" readonly>
                                                            </div>
                                                            <div class="form-group col-md-4 mb-2 ">
                                                                <label for="pio_name">PIO Officer Name</label>
                                                                <input  class="form-control" type="text" id="pio_name" name="pio_name" readonly>
                                                            </div>
                                                            <div class="form-group col-md-4 mb-2 ">
                                                                <label for="apio_name">APIO Officer Name</label>
                                                                <input  class="form-control" type="text" id="apio_name" name="apio_name" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row justify-content-center">
                                                            <div class="form-group col-md-8 mb-2">
                                                                <label for="transferReason">Reason for Forwarding</label><span class="text-danger">*</span>
                                                                <textarea style="height: 100px" class="form-control" name="transferReason" maxlength="3000" id="transferReason"></textarea>
                                                                @error('transferReason')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                {{-- working --}}
                                                @if ($i == 6)
                                                <div class="row justify-content-center hiddenAction6">
                                                    <br>
                                                    <br>
                                                    <h4 class="d-block text-center">Request Return to Applicant</h4>
                                                    <br>
                                                    <div class="row justify-content-center">
                                                        <div class="form-group col-md-8 mb-2">
                                                            <label for="doc_req_reason">Enter Reason for Request Return</label><span class="text-danger">*</span>
                                                            <textarea class="form-control" name="doc_req_reason" id="doc_req_reason" style="height: 160px" maxlength="500"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                @if ($i == 7)
                                                <div class="row justify-content-center hiddenAction7">
                                                    <br>
                                                    <br>
                                                    <h4 class="d-block text-center">Additional Payment Required For Information</h4>
                                                    <br>
                                                    <div class="row justify-content-center">
                                                        <div class="form-group col-md-8 mb-2 ">
                                                            <label for="mode_info_suppy">Select Model of Information Supply</label>
                                                            <select name="mode_info_suppy" id="mode_info_suppy" class="form-control">
                                                                <option value="" class="d-none">Select Model</option>
                                                                <option value="1">Hard Copy</option>
                                                                <option value="2">Electronic Media</option>
                                                                <option value="3">Priced Publications</option>
                                                                <option value="4">Certified Samples of Material</option>
                                                                <option value="5">Inspection of Work,Documents,Records</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-4 mb-2 ">
                                                            <label for="additional_amount">Additional Payment Demanded</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">₹</span>
                                                                </div>
                                                                <input type="text" name="additional_amount" id="additional_amount" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row justify-content-center">
                                                        <div class="form-group col-md-8 mb-4">
                                                            <label for="additional_amount_reason">Reason for Additional Payment (Max 255 characters)</label><span class="text-danger">*</span>
                                                            <textarea class="form-control" name="additional_amount_reason" id="additional_amount_reason" style="height: 160px"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                @if($i == 8)
                                                <div class="row justify-content-center hiddenAction8">
                                                    <br>
                                                    <br>
                                                    <h4 class="d-block text-center">Request Dispose of</h4>
                                                    <br>
                                                    <div class="row justify-content-center">
                                                        <div class="form-group col-md-4 mb-2 ">
                                                            <label for="dispose_of">Interim / Final Action</label>
                                                            <select name="dispose_of" id="dispose_of" class="form-control">
                                                                <option value="1">Request Dispose of</option>
                                                                <option value=""></option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-4 mb-2 ">
                                                            <label for="file_no_dispose">File Number</label>
                                                            <input type="text" name="file_no_dispose" id="file_no_dispose" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row justify-content-center">
                                                        <div class="form-group col-md-8 mb-2">
                                                            <label for="info_provided_citizen">Information Provided to Citizen</label>
                                                            <textarea name="info_provided_citizen" id="info_provided_citizen" class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="row justify-content-center">
                                                        <div class="form-group col-md-4 mb-2">
                                                            <label for="disposal_type">Type of Disposal</label>
                                                            <select name="disposal_type" id="disposal_type" class="form-control">
                                                                <option value="0">Select Disposal Type</option>
                                                                <option value="FullInfo">Full Information Supplied</option>
                                                                <option value="PartInfo">Part of Information Supplied</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-4 mb-2 ">
                                                            <label for="reply_doc">Upload Document 1</label>
                                                            <span for="reply_doc" style="color: blue; font-size: 12px"> (File Type .pdf)</span>
                                                            <input type="file" class="form-control" name="reply_doc" id="reply_doc" placeholder="Choose file">
                                                        </div>
                                                    </div>
                                                    <br><br><br><br>
                                                    <div class="row justify-content-center">
                                                        <div class="form-group col-md-4 mb-2">
                                                            <label for="reply_doc1">Upload Document 2</label>
                                                            <span for="reply_doc1" style="color: blue; font-size: 12px">(File Type .pdf, .doc, .docx, .txt, .xls, .xlsx, .jpg, .jpeg)</span>
                                                            <input type="file" class="form-control" name="reply_doc1" id="reply_doc1" placeholder="Choose file">
                                                        </div>
                                                        <div class="form-group col-md-4 mb-2">
                                                            <label for="reply_doc2">Upload Document 3</label>
                                                            <span for="reply_doc2" style="color: blue; font-size: 12px">(File Type .pdf, .doc, .docx, .txt, .xls, .xlsx, .jpg, .jpeg)</span>
                                                            <input type="file" class="form-control" name="reply_doc2" id="reply_doc2" placeholder="Choose file">
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                @if($i == 9)
                                                <div class="row justify-content-center hiddenAction9">
                                                    <br>
                                                    <br>
                                                    <h4 class="d-block text-center">Applicant Request Rejected</h4>
                                                    <br>
                                                    <div class="row justify-content-center">
                                                        <div class="form-group col-md-4 mb-2 ">
                                                            <label for="file_no">File Number</label>
                                                            <input type="text" name="file_no" id="file_no" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-4 mb-2 ">
                                                            <label for="reason_therefore">Reason Therefore</label>
                                                            <select name="reason_therefore" id="reason_therefore" class="form-control">
                                                                <option value="0" class="d-none">Select Reason</option>
                                                                <option value="1">Section 9</option>
                                                                <option value="2">Section 11</option>
                                                                <option value="3">Section 24</option>
                                                                <option value="4">Section 8(1)(a)</option>
                                                            </select>
                                                            <br>
                                                            <a href="{{ asset($filePath) }}" target="_blank">View Details of Sections for Rejection</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                @if($i == 18)
                                                <div class="row justify-content-center hiddenAction18">
                                                    <br>
                                                    <br>
                                                    <h4 class="d-block text-center">Partial Transfer Request to Another Authority</h4>

                                                    <div class="form-group text-center">
                                                        <button type="button" class="btn btn-dark btn-secondary" id="add-dropdown">Add Dropdown</button>
                                                    </div>
                                                    <br>

                                                    <div class="form-group">
                                                        <div id="dropdown-container">
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                            </div>
                                        </div>
                                    @endfor
                                    <br>
                                    <div class="d-grid mt-3" id="submitDivElement">
                                        <input type="submit" name="send" value="Submit" class="btn btn-dark btn-block" onclick="validateForm()">
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>
                </div>
                <br>
            </div>
        </div>
    @include('UsersView.footer')
</body>


<script type="text/javascript">

    let dropdownCount = 0;

    document.getElementById('add-dropdown').addEventListener('click', () =>
    {
        dropdownCount++;
        const container = document.getElementById('dropdown-container');

        const newDropdown = document.createElement('div');
        newDropdown.id = `dropdown_${dropdownCount}`;
        // newDropdown.classList.add('row');

        // Department dropdown
        const departmentOptions = @json($deptData); // Pass departments from Laravel to JS
        let partialDepartmentDropdown = '<div class="col-md-4 mb-2"> <label>Select Department</label><span class="text-danger">*</span> <select name="partial_departments[]" id="department-${dropdownCount}" class="department-dropdown form-control" data-id="${dropdownCount}">';
        partialDepartmentDropdown += '<option value="">Select Department</option>';
        departmentOptions.forEach(dept =>
        {
            partialDepartmentDropdown += `<option value="${dept.dept_code}">${dept.department}</option>`;
        });
        partialDepartmentDropdown += '</select></div>';

        // Initial SubDepartment dropdown (empty, will be populated based on department)
        const subpartialDepartmentDropdown = `<div class="col-md-4 mb-2"><label>Select Office</label><span class="text-danger">*</span> <select name="partial_subdepartments[]" class="sub-department-dropdown form-control" data-id="${dropdownCount}">
            <option value="">Select Office</option>
        </select></div>`;

        // Initial SubSubDepartment dropdown (empty, will be populated based on sub-department)
        const partialOfficeDropdown = `<div class="col-md-4 mb-2"> <label>Select Sub Office</label><span class="text-danger">*</span> <select name="partial_offices[]" class="office-department-dropdown form-control" data-id="${dropdownCount}">
            <option value="">Select Sub Office</option>
        </select> </div>`;

        const partialNodalOfficer = `<div class="col-md-4 mb-2"> <label>Nodal Officer</label>
        <input type="text" name="partial_nodal_officer[]" class="form-control partial_nodal" data-id="${dropdownCount}" readonly> </div>`;

        const partialPioOfficer = `<div class="col-md-4 mb-2"> <label>PIO Officer </label>
        <input type="text" name="partial_pio_officer[]" class="form-control partial_pio" data-id="${dropdownCount}" readonly> </div>`;

        const partialApioOfficer = `<div class="col-md-4 mb-2"> <label> APIO Officer </label>
        <input type="text" name="partial_apio_officer[]" class="form-control partial_apio" data-id="${dropdownCount}" readonly> </div>`;

        const partialReason = `<br> <div class="row"> <div class="align-items-center"> <div class="col-md-9 mb-4"> <label>Give Reason for Partial Transfer </label><textarea class="form-control" name="partialReason[]"> </textarea> </div> </div> </div>`;

        newDropdown.innerHTML = '<br> <hr> <div class="row">' + partialDepartmentDropdown + subpartialDepartmentDropdown + partialOfficeDropdown + "</div>" + '<br>' + '<div class="row">' + partialNodalOfficer + partialPioOfficer + partialApioOfficer + '</div>' + partialReason + '<button type="button" class="form-control remove-dropdown btn-danger" data-id="' + dropdownCount + '">Remove</button> ';

        container.appendChild(newDropdown);

        // Event listener for department change
        document.querySelector(`#dropdown_${dropdownCount} .department-dropdown`).addEventListener('change', (event) => {
            updatePartialNodal(dropdownCount);
            updateSubDepartments(event.target.value, dropdownCount);
        });

        // Event listener for sub-department change
        document.querySelector(`#dropdown_${dropdownCount} .sub-department-dropdown`).addEventListener('change', (event) => {
            updateOfficeDepartments(dropdownCount);
        });

        document.querySelector(`#dropdown_${dropdownCount} .office-department-dropdown`).addEventListener('change', (event) => {
            updatePartialPioApio(dropdownCount);
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
                const reasonTextareas = document.querySelectorAll('textarea[name="partialReason"]');

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
