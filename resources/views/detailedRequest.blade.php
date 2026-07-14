<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
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
@include('UsersView.header')

    <div class="container mt-5">
        <br>
        <br>
        <div class="row justify-content-center">
            <div class="col-md-9">
                <!-- Request Details -->
                <div class="card mb-4 mx-auto">
                    <h5 class="card-header">Request Details:</h5>
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
                        <div class="row">
                            <div class="col-md-4">
                                <p style="text-align: left;"><strong>Department:</strong> <br>{{ $deptDetails }}</p>
                            </div>
                            <div class="col-md-4">
                                <p style="text-align:center;"><strong>Office:</strong> <br>{{ $officeDetails }}</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <p><strong>Sub Office:</strong> <br>{{ $subOfficeDetails }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Details -->
                <div class="card">
                    <h5 class="card-header">Applicant Details</h5>
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
                    <h5 class="card-header">Request Details</h5>
                    <div class="card-body">
                        <div class="row">
                            @if($submitRequest->is_bpl === 0)
                            <div class="col-md-6">
                                <p><strong>Amount Paid (Rs.):</strong> 10 </p>
                            </div>
                            @endif

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <p><strong>Text From Applicant:</strong> {{ $submitRequest->request_text }}</p>
                            </div>
                        </div>
                        <div class="row" style="padding-top: 10px">
                            <div class="col-md-6">
                                <p><strong>Does it concern life or liberty of a person: </strong> N/A</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>File Uploaded by Applicant: </strong><a href="{{ asset($filePath) }}" target="_blank">{{ $submitRequest->document_name_original }}</a></p>
                            </div>
                        </div>
                        <div class="row" style="padding-top: 10px">

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
                                <p><strong>File Number : </strong> {{ $reqestDisposeOf->file_no ? $reqestDisposeOf->file_no : 'N/A'}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Disposal Type: </strong> {{$reqestDisposeOf->disposal_type}} </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Officer Attached File1: </strong> <a href="{{ Storage::url($reqestDisposeOf->document_path) }}" target="_blank">{{ $reqestDisposeOf->original_doc_name }}</a></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Disposal Reason: </strong> {{$reqestDisposeOf->reason}} </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Officer Attached File2:</strong>
                                    @if (($reqestDisposeOf->document_path1 != null))
                                        <a href="{{ Storage::url($reqestDisposeOf->document_path1) }}" target="_blank">{{ $reqestDisposeOf->original_doc_name1 }}</a>
                                    @else
                                        NA
                                    @endif
                            </div>
                            <div class="col-md-6">
                                <p><strong>Officer Attached File3: </strong>
                                    @if (($reqestDisposeOf->document_path2 != null))
                                        <a href="{{ Storage::url($reqestDisposeOf->document_path2) }}" target="_blank">{{ $reqestDisposeOf->original_doc_name2 }}</a>
                                    @else
                                        NA
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
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
                                <div class="col-md-12">
                                    <p><strong>Reverted Reason: </strong> {{$requestToApplicant->reason}} </p>
                                </div>
                            </div>

                        </div>
                    </div>
                    <br>
                    @endif
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
@include('UsersView.footer')
</body>


<script type="text/javascript">



</script>
