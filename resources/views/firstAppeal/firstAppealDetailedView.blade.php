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
                            <h4 class="mb-0"> {{session('faaDeptName')}} </h4>
                            @if(session('role_name' != 'Nodal Officer') || session('role_name' != 'First Appellete Authority'))
                            <h4 class="mb-0"> {{session('subDepartmentName')}} </h4>
                            <h4 class="mb-0"> {{session('officeName')}} </h4>
                            @endif
                            <br>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-start">
                                    <h5 class="mb-0">Username: {{ $userDetails['name'] }}</h5>
                                </div>
                                <div class="text-end">
                                    <h5 class="mb-0">Role: {{ $userDetails['role_name'] }} </h5>
                                </div>
                            </div>
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
        <br>
        <br>
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
                                        <td>{{ $departmentDetails['dept'] === null ? 'N/A' : $departmentDetails['dept']}}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-right"><strong>Office:</strong></td>
                                        <td>{{ $departmentDetails['office'] === null ? 'N/A' : $departmentDetails['office'] }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-right"><strong>Sub Office:</strong></td>
                                        <td>{{ $departmentDetails['suboffice'] === null ? 'N/A' : $departmentDetails['suboffice'] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h5 class="card-header">Personal Details of RTI Applicant</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Name : </strong>{{ $faDetails->name }} </p>
                                <p><strong>Address Line1 : </strong>{{ $faDetails->address_line1 }} </p>
                                <p><strong>Address Line3 : </strong>{{ $faDetails->address_line3 }} </p>
                                <p><strong>Country : </strong> India </p>
                                <p><strong>District : </strong> {{ $uploadedDocs['district']}} </p>
                                <p><strong>Phone No. : </strong>{{ $faDetails->phone_no == '0' ? 'NA' : $faDetails->phone_no }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Gender : </strong>{{ $faDetails->gender }} </p>
                                <p><strong>Address Line2 : </strong>{{ $faDetails->address_line2 }} </p>
                                <p><strong>Pincode : </strong>{{ $faDetails->pincode }} </p>
                                <p><strong>State : </strong> {{ $faDetails->stateName ?? 'Others'}}</p>
                                <p><strong>Village : </strong> {{ $uploadedDocs['village'] }}</p>
                                <p><strong>Mobile No. : </strong> {{ $faDetails->mobile_no }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <br>

                <div class="card">
                    <h5 class="card-header">Details of RTI Application</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>RTI Filed Reg_No : </strong> {{ $faDetails->reg_no }}</p>
                                <p><strong>Requested Text : </strong> {{$faDetails->sr_req_note}} </p>
                            </div>

                            <div class="col-md-6">
                            <p><strong>RTI Filed Date : </strong> {{ \Carbon\Carbon::parse($faDetails->submitted_date)->format('d-m-Y') }} </p>
                                <p><strong>Copy of the RTI Application : </strong><a href="{{ Storage::url($faDetails->document_path) }}" target="_blank"> {{$faDetails->document_name_original}} </a></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-9">
                            </div>
                        </div>
                    </div>
                </div>

                <br>
                <div class="card">
                    <h5 class="card-header">PIO Response</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>PIO Response : </strong>{{ $uploadedDocs['details']->reason}} </p>
                            </div>

                            <div class="col-md-6">
                                <p><strong>Reply of PIO : </strong>
                                @if($uploadedDocs['pioReplyDoc'] != null)
                                &nbsp &nbsp<a href="{{ Storage::url($uploadedDocs['pioReplyDoc']['document_path']) }}" target="_blank">{{ $uploadedDocs['pioReplyDoc']['document_name_original'] }}</a>
                                @else
                                File Not Found
                                @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <br>
                <div class="card">
                    <h5 class="card-header">Details of FAA Application</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Registration Number : </strong>{{ $faDetails->fa_reg_no }} </p>

                                <p><strong>Requested Text : </strong> {{$faDetails->fa_req_note}} </p>

                            </div>
                            <div class="col-md-6">
                                <p><strong>FA Received : </strong> {{ \Carbon\Carbon::parse($faDetails->created_at)->format('d-m-Y') }} </p>

                                <p><strong>Supporting Document : </strong>
                                @if($uploadedDocs['suppDoc'] != null)
                                &nbsp &nbsp<a href="{{ Storage::url($uploadedDocs['suppDoc']['document_path']) }}" target="_blank">{{ $uploadedDocs['suppDoc']['document_name_original'] }}</a>
                                @else
                                File Not Found
                                @endif
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                            </div>
                        </div>
                    </div>
                </div>
                <br>
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
                            <div class="col-md-6">
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
                        <a href="/inprocess-pdf/{{$faDetails->id}}" target="_blank">Print As PDF</a>
                    </div>
                </div>
                <br>

                @if ($faDetails->status_id != 5)
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('changeFAForm.form') }}" name="faForm" id="faForm" enctype="multipart/form-data" method="POST">
                            @csrf
                            <input type="hidden" name="faSubmitDetails" id="faSubmitDetails" value="{{ $faDetails }}">
                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <p><strong>Take Actions:</strong></p>
                                    <select name="selectedAction" id="selectedAction" class="form-select">
                                        <option value="">Select Action</option>
                                        @foreach ($actionsData as $aData)
                                            <option value="{{ $aData->id }}">{{ $aData->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            @for($i=10;$i<=13;$i++)

                            <div class="row justify-content-center hiddenAction" data-action="{{ $i }}" style="display: none">

                                <div class="col-md-12">
                                    @if($i == 10)
                                    <div class="row justify-content-center hiddenAction10">
                                        <br>
                                        <h4 class="d-block text-center" style="margin-top: 20px">ADMIT APPEAL FOR EXAMINATION</h4>
                                        <br>
                                    </div>
                                    @endif

                                    @if($i == 11)
                                    <div class="row justify-content-center hiddenAction11">
                                        <br>
                                        <h4 class="d-block text-center" style="margin-top: 20px">APPEAL DISPOSE OF</h4>
                                        <br>

                                        <div class="form-group col-md-8 mb-4">
                                            <label for="disposal_reason">Reason for Disposal</label><span class="text-danger">*</span>
                                            <textarea class="form-control" name="disposal_reason" id="disposal_reason" style="height: 160px"></textarea>
                                        </div>

                                        <div class="form-group col-md-4 mb-2 ">
                                            <label for="fa_reply_doc">Reply Document</label><span class="text-danger">*</span>
                                            <span for="fa_reply_doc" style="color: blue; font-size: 12px; display: block;">
        (File Type .pdf ; File size must not exceed 1 MB)
    </span>
                                            <input type="file" class="form-control" name="fa_reply_doc" id="fa_reply_doc" placeholder="Choose file">
                                        </div>
                                    </div>
                                    @endif

                                    @if($i == 12)
                                    <div class="row justify-content-center hiddenAction12">
                                        <br>
                                        <h4 class="d-block text-center" style="margin-top: 20px">SEEK COMMENTS FROM SPIO</h4>
                                        <div class="form-group col-md-8 mb-4">
                                            <label for="spio_comments">Comments from SPIO (Max 2000 characters)</label><span class="text-danger">*</span>
                                            <textarea class="form-control" name="spio_comments" id="spio_comments" style="height: 160px"></textarea>
                                        </div>
                                    </div>
                                    @endif

                                    @if($i == 13)
                                    <div class="row justify-content-center hiddenAction13">
                                        <br>
                                        <h4 class="d-block text-center" style="margin-top: 20px">RETURN FIRST APPEAL TO APPLICANT</h4>
                                        <div class="form-group col-md-8 mb-4">
                                            <label for="return_reason">Return to Applicant Reason(50 characters)</label><span class="text-danger">*</span>
                                            <textarea class="form-control" name="return_reason" id="return_reason" style="height: 160px"></textarea>
                                        </div>
                                    </div>
                                    @endif

                                    @if($i == 14)
                                    <div class="row justify-content-center hiddenAction14">
                                        <br>
                                        <h4 class="d-block text-center" style="margin-top: 20px">FORWARD THE FIRST APPEAL TO FIRST APPELLATE AUTHORITY</h4>

                                    </div>
                                    @endif

                                </div>
                            </div>
                            @endfor

                            <div class="d-grid mt-3">
                                <input type="submit" name="send" value="Submit" class="btn btn-dark btn-block" onclick="validateForm()">
                            </div>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <br><br>

    </div>
    @include('UsersView.footer')
</body>

<style>
    .table {
    border: none;
}

.table td, .table th {
    border: none;
    /* padding: 0.5rem; */
}

.text-right {
    text-align: right;
}

</style>

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
        // validateForm(selectedAction);
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
        break;

        default:
            alert('Wrong Option Selected');
            event.preventDefault();
            return false;
            break;
        }
    }


</script>