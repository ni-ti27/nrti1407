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
                        <div>
                            <div class="text-center alert alert-success">
                            @if($submitRequest->actionDetails[0]['status_id'] == 3)
                                Status - Reverted
                            @elseif($submitRequest->actionDetails[0]['status_id'] == 4)
                                Status - Rejected
                            @elseif($submitRequest->actionDetails[0]['status_id'] == 5)
                                Status - Closed
                            @endif
                            </div>

                        </div>
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
                                            <p><strong>District:</strong> {{ $submitRequest->District->name  ?? 'Others' }}</p>
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
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Sr. No</th>
                                                <th>Registration No.</th>
                                                <th>Amount (Rs.)</th>
                                                <th>e-Challan No.</th>
                                                <th>Transaction Date & Time</th>
                                                <th>Mobile Number</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($paymentDetails as $index => $payment)
                                            <tr>
                                                <td>{{$index + 1}}</td>
                                                <td>{{$submitRequest->reg_no}}</td>
                                                <td>{{$payment->total_amt}}</td>
                                                <td>{{$payment->echallan_no}}</td>
                                                <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('d-m-Y H:i') }}</td>
                                                <td>{{$submitRequest->mobile_no}}</td>
                                                <td>Success</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <br>
                    @endif


                        @if($reqestDisposeOf != null)
                        <div class="card">
                                <h5 class="card-header">Officer's Reply:</h5>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Disposed Date & Time: </strong>{{ \Carbon\Carbon::parse($reqestDisposeOf->created_at)->format('d-m-Y H:i') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Disposed Date & Time (Re-Uploaded): </strong>{{ \Carbon\Carbon::parse($reqestDisposeOf->updated_at)->format('d-m-Y H:i') }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>File Number: </strong>{{ $reqestDisposeOf->file_no ? $reqestDisposeOf->file_no : 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Disposal Type: </strong>{{ $reqestDisposeOf->disposal_type }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Upload Document 1: </strong> <a href="{{ asset($officerFile[0]) }}" target="_blank">{{ $reqestDisposeOf->original_doc_name }}</a></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Disposal Reason: </strong>{{ $reqestDisposeOf->reason }}</p>
                                    </div>
                                </div>
                                @if($reqestDisposeOf->document_path1 || $reqestDisposeOf->document_path2)
                                <hr>
                                <div class="row">
                                    <p><strong>Additional Files: </strong></p>
                                    @if($reqestDisposeOf->document_path1)
                                    <div class="col-md-6">
                                        <p><strong>Upload Document 2: </strong> <a href="{{ asset($officerFile[1]) }}" target="_blank">{{ $reqestDisposeOf->original_doc_name1 }}</a></p>
                                    </div>
                                    @endif

                                    @if($reqestDisposeOf->document_path2)
                                    <div class="col-md-6">
                                        <p><strong>Upload Document 3: </strong> <a href="{{ asset($officerFile[2]) }}" target="_blank">{{ $reqestDisposeOf->original_doc_name2 }}</a></p>
                                    </div>
                                    @endif
                                </div>
                                @endif

                                @if($reqestDisposeOf->updated_at >= \Carbon\Carbon::now()->subDays(3) || $reqestDisposeOf->allow_reupload == true)
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <form id="actionForm" enctype="multipart/form-data" action="{{ route('updateOfficerReplyFile') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="submitRequestDetails" value="{{ $submitRequest }}">

                                            <div class="row">
                                                <button type="button" id="edit-button" style="width:100%" class="btn btn-primary">Edit</button>
                                            </div>

                                            <br>

                                            <div class="row" style="display:none" id="file-upload">
                                                <p style="margin-top:20px"><strong>Re-Upload Reply: &nbsp;</strong></p>

                                                <div class="col-md-4">
                                                    <label for="officerFile1">Replacement for File 1:</label>
                                                    <input type="file" id="file-input1" name="officerFile1" class="form-control">
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="officerFile2">Replacement for File 2:</label>
                                                    <input type="file" id="file-input2" name="officerFile2" class="form-control">
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="officerFile3">Replacement for File 3:</label>
                                                    <input type="file" id="file-input3" name="officerFile3" class="form-control">
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row justify-content-center">
                                                <div class="col-auto">
                                                    <button type="submit" id="submit-button" class="btn btn-success" style="display: none;">Submit</button>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if($requestToApplicant != null)
                        <div class="card">
                            <h5 class="card-header">Officer's Reply:</h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Reverted Date & Time: </strong>{{ \Carbon\Carbon::parse($requestToApplicant->created_at)->format('d-m-Y H:i') }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Reverted Reason: </strong> {{$requestToApplicant->reason}} </p>
                                    </div>
                                </div>

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
                    </div>
                </div>
                <br>
            </div>
        </div>
        <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    @include('UsersView.footer')
</body>


<script type="text/javascript">
$(document).ready(function() {
    $('#file-upload').hide();

    $('#edit-button').click(function() {
        $('#file-upload').show().addClass('d-flex');
    });

    $('input[type="file"]').on('change', function(){
        const anyFileSelected = $('input[type="file"]').toArray().some(input => input.files.length > 0);
        $('#submit-button').toggle(anyFileSelected);
    });

    // $('#file-input1').change(function() {
    //     $('#submit-button').toggle(!!$(this).val());
    // });
});
</script>

