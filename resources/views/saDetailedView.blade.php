<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
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
    @include('UsersView.header')
    <div class="container mt-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="text-center">
                                <br>
                                <br>
                                @if ($saSubmitRequest->saActionDetails->first()->status_id == 1)
                                    <h5 class="mb-0">New Request</h5>
                                    @if($saSubmitRequest->online_request == true)
                                        <h8 class="mb-0">(Online)</h8>
                                    @else
                                        <h8 class="mb-0">(Offline)</h8>
                                    @endif
                                @elseif ($saSubmitRequest->saActionDetails->first()->status_id == 2)
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
                <div class="row justify-content-center">
                    <div class="col-md-9">
                        <!-- Public Authority Details -->
                        <div class="card mb-4 mx-auto">
                            <h5 class="card-header">Public Authority Details:</h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p style="text-align: left;"><strong>Reg No.:</strong> {{ $saSubmitRequest->sa_reg_no }}</p>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <p><strong>Filed Date:</strong> {{ \Carbon\Carbon::parse($saSubmitRequest->created_at)->format('d-m-Y') }}</p>
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
                                        <p><strong>Applicant Name:</strong> {{ $saSubmitRequest->name }}</p>
                                        <p><strong>Gender:</strong> {{ $saSubmitRequest->gender === 'female' ? 'Female' : ($saSubmitRequest->gender === 'male' ? 'Male' : 'Other') }}</p>
                                        <p><strong>Address:</strong><br>
                                            {{ $saSubmitRequest->address_line1 }}<br>
                                            {{ $saSubmitRequest->address_line2 }}<br>
                                            {{ $saSubmitRequest->address_line3 }}
                                        </p>
                                        <p><strong>Pincode:</strong> {{ $saSubmitRequest->pincode }} </p>
                                        <p><strong>Education:</strong> {{ $saSubmitRequest->edu_status === 1 ? 'Educated' : 'Non-Educated' }}</p>

                                    </div>
                                    <div class="col-md-6">
                                        @if($saSubmitRequest->state === 0)
                                            <p><strong>Village:</strong> Others</p>
                                            <p><strong>District:</strong> Others</p>
                                            <p><strong>State:</strong> Others</p>
                                        @else
                                            <p><strong>Village:</strong> {{ $saSubmitRequest->Village->name ?? 'Others'}}</p>
                                            <p><strong>District:</strong> {{ $saSubmitRequest->District->name  ?? 'Others'}}</p>
                                            <p><strong>State:</strong> {{ $saSubmitRequest->State->name ?? 'Others' }}</p>
                                        @endif
                                        <p><strong>Phone:</strong> {{ $saSubmitRequest->phone_no }}</p>
                                        <p><strong>Mobile:</strong> {{ $saSubmitRequest->mobile_no }}</p>
                                        <p><strong>Email:</strong> {{ $saSubmitRequest->email_id }}</p>
                                        <p><strong>BPL:</strong> {{ $saSubmitRequest->is_bpl == 1 ? 'BPL User' : 'Non BPL User' }}</p>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <br>
                        <!-- Application Details -->

                        <div class="card">
                            <h5 class="card-header">Details of RTI Application</h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <p style="padding-top: 15px"><strong>RTI Request Registration Number: </strong>{{$saSubmitRequest->reg_no}}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p style="padding-top: 15px"><strong>RTI Request Filed on (Date): </strong> {{ \Carbon\Carbon::parse($saSubmitRequest->newrequest_date)->format('d-m-Y') }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <p style="padding-top: 15px"><strong>PIO Name: </strong>{{ $saSubmitRequest->pio_name }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p style="padding-top: 15px"><strong>PIO Address: </strong> {{$saSubmitRequest->pio_address }}</p>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-md-12" style="text-align:center">
                                        <p style="padding-top: 15px"><strong>RTI Application File: </strong>  <a href="{{ asset($saFiles['newRequestFile']['document_path']) }}" target="_blank">{{ $saFiles['newRequestFile']['document_name_original']}}</a></p>


                                        {{-- <p><strong>File Uploaded by Applicant: </strong><a href="{{ asset($filePath) }}" target="_blank">{{ $submitRequest->document_name_original }}</a></p> --}}

                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="form-group col-md-8 mb-2">
                                    <label for="user_text">Requested Text</label>
                                    <textarea class="form-control" name="user_text" id="user_text" style="height: 180px" readonly>{{ isset($requestedText['rti_req']) ? $requestedText['rti_req'] : ''}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>

                        <div class="card">
                            <h5 class="card-header">Details of First Appeal Application</h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <p style="padding-top: 15px"><strong>First Appeal Registration Number: </strong>{{$saSubmitRequest->fa_reg_no}}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p style="padding-top: 15px"><strong>First Appeal Filed on (Date): </strong> {{ \Carbon\Carbon::parse($saSubmitRequest->faa_date)->format('d-m-Y') }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <p style="padding-top: 15px"><strong>PIO Name: </strong>{{$saSubmitRequest->pio_name}}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p style="padding-top: 15px"><strong>PIO Address: </strong> {{$saSubmitRequest->pio_address }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12" style="text-align:center">
                                    @if(isset($saFiles['faRequestFile']) &&
                                    isset($saFiles['faRequestFile']['document_path']) &&
                                    isset($saFiles['faRequestFile']['document_name_original']))
                                        <p style="padding-top: 15px">
                                            <strong>First Appeal File: </strong>
                                            <a href="{{ asset($saFiles['faRequestFile']['document_path']) }}" target="_blank">
                                                {{ $saFiles['faRequestFile']['document_name_original'] }}
                                            </a>
                                        </p>
                                    @else
                                        <p style="padding-top: 15px"><strong>No file available for the first appeal.</strong></p>
                                    @endif
                                    </div>
                                </div>

                                <div class="row">
                                    @if(isset($faFiles['faFile']))
                                    <div class="col-md-6">
                                        <p style="padding-top: 15px"><strong>First Appeal Application Document: </strong>  <a href="{{ asset($faFiles['faFile']['document_path']) }}" target="_blank">{{ $faFiles['faFile']['document_name_original']}}</a></p>
                                    </div>
                                    @endif

                                    @if(isset($faFiles['supporting_doc']))
                                    <div class="col-md-6">
                                        <p style="padding-top: 15px"><strong>First Appeal Supporting File: </strong>  <a href="{{ asset($faFiles['supporting_doc']['document_path']) }}" target="_blank">{{ $faFiles['supporting_doc']['document_name_original']}}</a></p>
                                    </div>
                                    @endif
                                </div>

                                <div class="row justify-content-center">
                                    <div class="form-group col-md-8 mb-2">
                                    <label for="user_text">Requested Text</label>
                                    <textarea class="form-control" name="user_text" id="user_text" style="height: 180px" readonly>{{ isset($requestedText['faa_req']) ? $requestedText['faa_req'] : ''}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>

                        <div class="card">
                            <h5 class="card-header">Details of Second Appeal Application</h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <p style="padding-top: 15px"><strong>Second Appeal Registration Number: </strong>{{$saSubmitRequest->sa_reg_no}}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p style="padding-top: 15px"><strong>Second Appeal Filed on (Date): </strong> {{ \Carbon\Carbon::parse($saSubmitRequest->created_at)->format('d-m-Y') }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <p style="padding-top: 15px"><strong>PIO Name: </strong>{{$saSubmitRequest->pio_name}}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p style="padding-top: 15px"><strong>PIO Address: </strong> {{$saSubmitRequest->pio_address }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <p style="padding-top: 15px"><strong>FAA Name: </strong>{{$saSubmitRequest->faa_name}}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p style="padding-top: 15px"><strong>FAA Address: </strong> {{$saSubmitRequest->faa_address }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <p style="padding-top: 15px"><strong>Second Appeal Document: </strong>  <a href="{{ asset($saFiles['saFile']['document_path']) }}" target="_blank">{{ $saFiles['saFile']['document_name_original']}}</a></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p style="padding-top: 15px"><strong>Id Proof Document: </strong>  <a href="{{ asset($saFiles['idProof']['document_path']) }}" target="_blank">{{ $saFiles['idProof']['document_name_original']}}</a></p>
                                    </div>
                                </div>

                                @if(isset($saFiles['supporting_doc']))
                                    <div class="row">
                                        @foreach($saFiles['supporting_doc'] as $index => $document)
                                            <div class="col-md-6">
                                                <p style="padding-top: 15px">
                                                    <strong>Second Appeal Supporting File {{ $index + 1 }}: </strong>
                                                    <a href="{{ asset($document['document_path']) }}" target="_blank">{{ $document['document_name_original'] }}</a>
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                            </div>
                        </div>
                        <br>
                        @if($SA_Reply_To_Applicant != null)
                        <div class="card">
                            <h5 class="card-header">Officer's Reply:</h5>
                            <div class="card-body">
                                @if(!empty($SA_Reply_To_Applicant->reason))
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p>
                                                <strong>Reverted Date & Time: </strong>
                                                {{ \Carbon\Carbon::parse($SA_Reply_To_Applicant->created_at)->format('d-m-Y H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p>
                                                <strong>Reverted Reason: </strong>
                                                {{ $SA_Reply_To_Applicant->reason }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <br>
                         @endif
                         <br>
            <!-- Print Details -->
                        <div class="card">
                            <h5 class="card-header">Print Details:</h5>
                            <div class="card-body">
                                <a href="/sa-pdf/{{$saSubmitRequest->id}}" target="_blank">Print As PDF</a>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
            </div>
        </div>
        @include('UsersView.footer')
    </body>

