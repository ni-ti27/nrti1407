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
    @include('OfficerView.Header')
    <div class="container mt-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="text-center">
                                <br>
                            <h5 class="mb-0"> {{ session('officeName') }}</h5><br>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-start">
                                        <h5 class="mb-0">Username: {{ $userDetails['name'] }}</h5>
                                    </div>
                                    <div class="text-end">
                                        <h5 class="mb-0">Role: {{ $userDetails['role_name'] }} </h5>
                                    </div>
                                </div>
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
                <br>
                <div class="row justify-content-center">
                    <div class="col-md-9">
                        <div style="margin: 20px">
                            @if(session('success'))
                                <div class="alert alert-success" id="successAlert">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if(session('warning'))
                                <div class="alert alert-warning" id="warningAlert">
                                    {{ session('warning') }}
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger" id="errorAlert">
                                    {{ session('error') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <br>
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
                                            <p><strong>District:</strong> {{ $saSubmitRequest->District->name ?? 'Others'}}</p>
                                            <p><strong>State:</strong> {{ $saSubmitRequest->State->name  ?? 'Others' }}</p>
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
                                        <p style="padding-top: 15px"><strong>First Appeal Filed on (Date): </strong> {{ \Carbon\Carbon::parse($saSubmitRequest->created_at)->format('d-m-Y') }}</p>
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

                        <br>
                        <div class="card">
                            <div class="card-body">
                                <form id="actionForm" enctype="multipart/form-data" action="{{ route('secondAppeal-Action') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="saSubmitRequestDetails" value="{{ $saSubmitRequest }}">
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

                                    @for ($i = 18; $i <= 23; $i++)
                                    @if (!in_array($i, [19,20,21,23]))
                                        @continue;
                                    @endif
                                        <div class="row justify-content-center hiddenAction" data-action="{{ $i }}" style="display: none">
                                            <div class="col-md-12">
                                                @if ($i == 19)
                                                <div class="row justify-content-center">
                                                    <br>
                                                    <br>
                                                    <h4 class="d-block text-center">Request Return to Applicant</h4>
                                                    <br>
                                                    <div class="row justify-content-center">
                                                        <div class="form-group col-md-8 mb-2">
                                                            <label for="doc_req_reason">Enter Reason for Request Return</label>
                                                            <textarea class="form-control" name="doc_req_reason" id="doc_req_reason" style="height: 160px" maxlength="500"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                @if($i == 21)
                                                    <div class="row justify-content-center">
                                                        <br>
                                                        <br>
                                                        <h4 class="d-block text-center">Issue Notice to Parties</h4>
                                                        <br>
                                                        <div class="row justify-content-center">
                                                            <div class="form-group col-md-4 mb-2">
                                                                <label for="applicant_user">Applicant Name</label>
                                                                    <input type="text" class="form-control" name="applicant_user" id="applicant_user" value="{{ $saSubmitRequest->name }}">
                                                                </select>
                                                            </div>

                                                            <div class="form-group col-md-4 mb-2">
                                                                <label for="pio_user">PIO User Name</label>
                                                                <select name="pio_user" class="form-control" id="pio_user">
                                                                    <option value="">Select PIO User</option>
                                                                </select>
                                                            </div>

                                                            <div class="form-group col-md-4 mb-2">
                                                                <label for="faa_user">FAA User Name</label>
                                                                <select name="faa_user" class="form-control" id="faa_user">
                                                                    <option value="">Select FAA User</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="row justify-content-center">
                                                            <div class="form-group col-md-4 mb-2">
                                                                <label for="applicant_address">Applicant Address</label>

                                                                <textarea style="height: 120px" class="form-control @error('applicant_address') is-invalid @enderror" name="applicant_address" maxlength="3000" id="applicant_address">{{ old('applicant_address', $saSubmitRequest->address_line1."\n".$saSubmitRequest->address_line2."\n".$saSubmitRequest->address_line3)."\n".$saSubmitRequest->pincode }}</textarea>

                                                                @error('applicant_address')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>

                                                            <div class="form-group col-md-4 mb-2">
                                                                <label for="pio_address">Pio Address</label>
                                                                <textarea style="height: 120px" class="form-control @error('pio_address') is-invalid @enderror" name="pio_address" maxlength="3000" id="pio_address"></textarea>
                                                                @error('pio_address')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="row justify-content-center">
                                                            <a href="#" onClick="generateNotice()">Generate Notice</a>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if($i == 23)
                                                    <div class="row justify-content-center">
                                                        <br><br>
                                                        <h4 class="d-block text-center">Final Judgement on Apppeal</h4>
                                                        <br>

                                                        <div class="row justify-content-center">
                                                            <div class="form-group col-md-7 mb-2">
                                                                <label>Judgement Upload</label>
                                                                <span for="judgement_doc" style="color: blue; font-size: 12px">(File Type .pdf)</span>
                                                                <input type="file" class="form-control @error('judgement_doc') is-invalid @enderror" name="judgement_doc" id="judgement_doc" placeholder="Choose file" value="{{old('judgement_doc')}}">
                                                                @error('judgement_doc')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="row justify-content-center">
                                                            <div class="form-group col-md-7 mb-2">
                                                                <label>Judgement Text</label>
                                                                <br>
                                                                <span style="font-size: 12px; color: blue;" for="">Only alphabets A-Z a-z number 0-9 and special characters , . - _ ( ) / @ : & \ % are allowed in Text for RTI Request application</span>
                                                                <textarea style="height: 100px" value="{{ old('judgement_text')}}" class="form-control @error('judgement_text') is-invalid @enderror" maxlength="3000" name="judgement_text" id="judgement_text"></textarea>
                                                                @error('judgement_text')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <br><br><br><br>

                                                        <div class="row justify-content-center">
                                                            <div class="form-group col-md-5 mb-2">
                                                                <label>Supporting Document 1</label>
                                                                <span for="judgement_s1" style="color: blue; font-size: 12px">(File Type .pdf, .doc, .docx, .txt, .xls, .xlsx, .jpg, .jpeg)</span>
                                                                <input type="file" class="form-control @error('judgement_s1') is-invalid @enderror" name="judgement_s1" id="judgement_s1" placeholder="Choose file" value="{{old('judgement_s1')}}">
                                                                @error('judgement_s1')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group col-md-5 mb-2">
                                                                <label>Supporting Document 2</label>
                                                                <span for="judgement_s2" style="color: blue; font-size: 12px">(File Type .pdf, .doc, .docx, .txt, .xls, .xlsx, .jpg, .jpeg)</span>
                                                                <input type="file" class="form-control @error('judgement_s2') is-invalid @enderror" name="judgement_s2" id="judgement_s2" placeholder="Choose file" value="{{old('judgement_s2')}}">
                                                                @error('judgement_s2')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <!-- _supporting_text -->
                                                        <div class="row justify-content-center">
                                                            <div class="form-group col-md-5 mb-2">
                                                                <label>Supporting Text for Document 1</label>
                                                                <br>
                                                                <span style="font-size: 12px; color: blue;" for="">Only alphabets A-Z a-z number 0-9 and special characters , . - _ ( ) / @ : & \ % are allowed in Text for RTI Request application</span>
                                                                <textarea style="height: 100px" value="{{ old('judgement_text_s1')}}" class="form-control @error('judgement_text_s1') is-invalid @enderror" maxlength="3000" name="judgement_text_s1" id="judgement_text_s1"></textarea>
                                                                @error('judgement_text_s1')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group col-md-5 mb-2">
                                                                <label>Supporting Text for Document 2</label>
                                                                <br>
                                                                <span style="font-size: 12px; color: blue;" for="">Only alphabets A-Z a-z number 0-9 and special characters , . - _ ( ) / @ : & \ % are allowed in Text for RTI Request application</span>
                                                                <textarea style="height: 100px" value="{{ old('judgement_text_s2')}}" class="form-control @error('judgement_text_s2') is-invalid @enderror" maxlength="3000" name="judgement_text_s2" id="judgement_text_s2"></textarea>
                                                                @error('judgement_text_s2')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endfor
                                    <br>
                                    <div class="d-grid mt-3" id="submitDivElement">
                                        <input type="submit" name="submitButton" id="submitButton" value="Submit" class="btn btn-dark btn-block" onclick="validateForm()" disable>
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

setTimeout(() => {
    const successAlert = document.getElementById("successAlert");
    const warningAlert = document.getElementById("warningAlert");
    const errorAlert = document.getElementById("errorAlert");

    if (successAlert) successAlert.style.display = "none";
    if (warningAlert) warningAlert.style.display = "none";
    if (errorAlert) errorAlert.style.display = "none";

}, 10000);

$(document).ready(function()
{
    $('select[name="selectedAction"]').on('change', function()
    {
        var selectedOption = $('select[name="selectedAction"]').val();
        if(selectedOption == 21)
        {
            populatePioUser();
            populateFaaUser();
        }

        getSubForm();
    });
});

function generateNotice()
{
    var pio_user = $('#pio_user').val();
    var faa_user = $('#faa_user').val();
    var applicant_address = $('#applicant_address').val();
    var pio_address = $('#pio_address').val();
    var caseId = "<?php echo $saSubmitRequest->id; ?>";

    $.ajax({
        url: '/generate-notice',
        type: "POST",  // Change to POST request
        dataType: "json",
        data: {
            case_id: caseId,
            pio_user: pio_user,
            faa_user: faa_user,
            pio_address: pio_address,
            applicant_address: applicant_address,
            _token: "<?php echo csrf_token(); ?>"
        },
        success: function(data) {
            if (data.pdfUrl) {
                window.open(data.pdfUrl, '_blank');
            } else {
                alert("Error generating PDF: " + data.error);
            }

            document.getElementById("submitButton").disabled = false;
        },
        error: function(xhr, status, error) {
            alert("There was an error: " + error);
            console.log(error);
            document.getElementById("submitButton").disabled = false;
        }
    });
}

function populatePioUser()
{
    var deptId = "<?php echo $datajs[0]; ?>";
    var subdeptId = "<?php echo $datajs[1]; ?>";

    $.ajax({
        url: '/pioDropdown/' + deptId + '/' + subdeptId,
        type: "GET",
        dataType: "json",
        success: function(data)
        {
            var oldPioname = '{{ old("pio_user") }}';
            $('select[name="pio_user"]').empty();

            $.each(data, function(key, value)
            {
                var option = $('<option value="' + value.id + '">' + value.name + ' | ' + value.department + '</option>');

                if (value.name == oldPioname) {
                    option.prop('selected', true);
                }

                $('select[name="pio_user"]').append(option);
            });
        }
    });
}

function populateFaaUser()
{
    var deptId = "<?php echo $datajs[0]; ?>";
    var subdeptId = "<?php echo $datajs[1]; ?>";

    $.ajax({
        url: '/faaDropdown/' + deptId + '/' + subdeptId,
        type: "GET",
        dataType: "json",
        success: function(data)
        {
            var oldFaaname = '{{ old("faa_user") }}';
            $('select[name="faa_user"]').empty();

            $.each(data, function(key, value)
            {
                var option = $('<option value="' + value.id + '">' + value.name + ' | ' + value.department + '</option>');

                if (value.name == oldFaaname) {
                    option.prop('selected', true);
                }

                $('select[name="faa_user"]').append(option);
            });
        }
    });
}


function validateForm()
{
    var selectedAction = $('select[name="selectedAction"]').val();

    switch(selectedAction)
    {
        case '19':
            var return_reason = $('#doc_req_reason').val();
            console.log(return_reason);
            if(return_reason.length < 5)
            {
                alert('Please Enter Reason for Return to Applicant & Length should be minimum 5 Characters');
                event.preventDefault();
                return false;
            }
            break;

        case '23':
            var judgement_doc = $('#judgement_doc')[0].files[0];
            var allowedExtensions = ['pdf', 'doc', 'docx', 'txt', 'xls', 'xlsx', 'jpg', 'jpeg'];

            if (!judgement_doc)
            {
                alert('Please select a file.');
                event.preventDefault();
                return false;
            }

            var file_extension = judgement_doc.name.split('.').pop().toLowerCase();
            if(file_extension != 'pdf')
            {
                alert('Allowed Files Types are PDF');

                $('#judgement_doc').addClass('is-invalid');
                $('#judgement_doc').after('<span class="invalid-feedback">Please upload a valid PDF file.</span>');

                event.preventDefault();
                return false;
            }

            var judgement_reason = $('#judgement_text').val();
            if (judgement_reason.length < 5)
            {
                alert('Please Enter Judgement text to Applicant & Length should be minimum 5 Characters');
                $('#judgement_text').addClass('is-invalid');
                $('#judgement_text').after('<span class="invalid-feedback">Judgement should be at least 5 characters long.</span>');
                event.preventDefault();
                return false;
            }

            break;

        default:
            break;
    }
    confirmSubmit();
}

function confirmSubmit()
{
    var confirmed = confirm("Are you sure you want to submit?");
    if(confirmed != true)
    {
        event.preventDefault();
        return false;
    }
}


function getSubForm()
{
    var selectedAction = $('select[name="selectedAction"]').val();
    console.log('Selected Action:', selectedAction);

    // Hide all elements with the common class
    $('.hiddenAction').hide();

    // Show the specific element based on the selected action
    var specificClass = '.hiddenAction[data-action="' + selectedAction + '"]';
    $(specificClass).show();
}

</script>
