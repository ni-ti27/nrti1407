<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<head>

<style>

    .body
    {
        margin: 0;
        padding: 0;
    }
    .columnleft
    {
        text-align: left;
        margin-bottom: -50px;
    }
    .columnRight
    {
        text-align: right;
        margin-bottom: -50px;
    }

    .table
    {
        border-collapse: collapse;
        width: 100%;
        margin-top: 20px;
    }

    .table th
    {
        border: 1px solid;
        padding: 10px;
        text-align: left;
    }

    .page-break { page-break-before: always; }

    .table td
    {
        border: 1px solid;
        padding: 8px;
    }
    </style>
</head>

<body>
    <h1 style="text-align:center;"><strong>Detailed Information</strong></h1>
    <div class="row">
        <h2>First Appeal Request Details:</h2>
        <div class="columnleft">
            <p><strong>FA Reg No.:</strong> {{ $faSubmitRequest->fa_reg_no }}</p>
        </div>
        <div class="columnRight">
            <p><strong>FA Req Filed Date:</strong> {{ \Carbon\Carbon::parse($faSubmitRequest->created_at)->format('d-m-Y H:i') }}</p>
        </div>
        <br><br>

        <div style="text-align:center">
            <p><strong>Department:</strong>
                {{ $faDeptDetails['dept'] === null ? 'N/A' : $faDeptDetails['dept']}}
            </p>
            <p><strong>Sub - Department:</strong>
                {{ $faDeptDetails['subDept'] === null ? 'N/A' : $faDeptDetails['subDept']}}
            </p>
            <p><strong>Office:</strong>
                {{ $faDeptDetails['office'] === null ? 'N/A' : $faDeptDetails['office']}}
            </p>
        </div>

        <div class="columnleft">
            <p><strong>Pio Name:</strong> {{ $faSubmitRequest->pioname }}</p>
        </div>
        <div class="columnRight">
            <p><strong>Pio Address:</strong> {{ $faSubmitRequest->pioaddr }}</p>
        </div>
        <br><br>
        <div class="columnleft">
            <p><strong>Pio Response:</strong> {{ $faSubmitRequest->pioResponseData['reason'] }}</p>
        </div>
        <br><br>
        <div class="columnleft">
            <p><strong>Text From Applicant:</strong> {{ $faSubmitRequest->requested_note }}</p>
        </div>

        <br><br>
    @if(!empty($filePaths))

    <div class="columnleft">
        @if(isset($filePaths['First Appeal']))
            <p>
                <strong>FA Document: </strong>
                <a href="{{ asset($filePaths['First Appeal']) }}" target="_blank">
                    First Appeal Document
                </a>
            </p>
        @else
            <p><strong>FA Document: </strong>No File Attached.</p>
        @endif
    </div>

    <div class="columnRight">
        @if(isset($filePaths['Copy of PIO Reply']))
            <p>
                <strong>PIO Reply: </strong>
                <a href="{{ asset($filePaths['Copy of PIO Reply']) }}" target="_blank">
                    Copy of PIO Reply
                </a>
            </p>
        @endif

        @if(isset($filePaths['Unknown Category']))
            <p>
                <strong>Support File: </strong>
                <a href="{{ asset($filePaths['Unknown Category']) }}" target="_blank">
                    Supporting Document
                </a>
            </p>
        @endif
    </div>

@endif
        <br><br><br>

        <h2>Request Details:</h2>

        <div class="columnleft">
            <p><strong>Reg No.:</strong> {{ $submitRequest->reg_no }}</p>
        </div>
        <div class="columnRight">
            <p><strong>RTI Req Filed Date:</strong> {{ \Carbon\Carbon::parse($submitRequest->created_at)->format('d-m-Y H:i') }}</p>
        </div>
        <br><br>

        <div style="text-align:center">
            <p><strong>Department:</strong>
                {{ $deptDetails['dept'] === null ? 'N/A' : $deptDetails['dept']}}
            </p>
            <p><strong>Sub - Department:</strong>
                {{ $deptDetails['subDept'] === null ? 'N/A' : $deptDetails['subDept']}}
            </p>
            <p><strong>Office:</strong>
                {{ $deptDetails['office'] === null ? 'N/A' : $deptDetails['office']}}
            </p>
        </div>

        <div class="columnleft">
            <p><strong>Applicant Name:</strong> {{ $faSubmitRequest->name }}</p>
        </div>
        <div class="columnRight">
            @if($faSubmitRequest->Village === null)
                <p><strong>Village:</strong> Others</p>
            @else
                <p><strong>Village:</strong> {{ $faSubmitRequest->Village->name }}</p>
            @endif
        </div>
        <br><br>
        <div class="columnleft">
            <p><strong>Gender:</strong> {{ $faSubmitRequest->gender === 'female' ? 'Female' : ($faSubmitRequest->gender ===     'male' ? 'Male' : 'Other') }}</p>
        </div>
        <div class="columnRight">
            @if($faSubmitRequest->District === null)
                <p><strong>District:</strong> Others</p>
            @else
                <p><strong>District:</strong> {{ $faSubmitRequest->District->name }}</p>
            @endif
        </div>
        <br><br>
        <div class="columnleft">
            <p>
                <strong>Address:</strong><br>
                {{ $faSubmitRequest->address_line1 }}<br>
                {{ $faSubmitRequest->address_line2 }}<br>
                {{ $faSubmitRequest->address_line3 }}
            </p>
        </div>
        <div style="margin-top: -100px; text-align:right">
            @if($faSubmitRequest->state === 0)
                <p><strong>State:</strong> Others</p>
            @else
                <p><strong>State:</strong> {{ $faSubmitRequest->State->name }}</p>
            @endif
        </div>
        <br><br>
        <div class="columnleft">
            <p><strong>Pincode:</strong> {{ $faSubmitRequest->pincode }} </p>
        </div>
        <div class="columnRight">
            <p><strong>Phone:</strong> {{ $faSubmitRequest->phone_no }}</p>
        </div>
        <br><br>
        <div class="columnleft">
            <p><strong>Education:</strong> {{ $faSubmitRequest->edu_status === 1 ? 'Educated' : 'Non-Educated' }}</p>
        </div>
        <div class="columnRight">
            <p><strong>Email:</strong> {{ $faSubmitRequest->email_id }}</p>
        </div>

        <br><br>
        <div class="columnleft">
            <p><strong>Mobile:</strong> {{ $faSubmitRequest->mobile_no }}</p>
        </div>
        <div class="columnRight">
            <p><strong>BPL:</strong> {{ $faSubmitRequest->is_bpl == 1 ? 'BPL User' : 'Non BPL User' }}</p>
        </div>
        <br><br>

        <div class="columnleft">
            <p>
                <strong>Qualification:</strong>
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
        </div>

        <div class="columnRight">
            <p><strong>Card No.:</strong> {{ $submitRequest->card_no === 0 ? 'N/A' : $submitRequest->card_no }}</p>
        </div>

        <br><br>

        <div class="columnleft">
            <p><strong>Text From Applicant:</strong> {{ $submitRequest->request_text }}</p>
        </div>

        <br><br>
        <div class="columnleft">
            <p><strong>File Uploaded by Applicant: </strong><a href="{{ asset($reqFilePath) }}" target="_blank">{{ $submitRequest->document_name_original }}</a></p>
        </div>

        <br><br><br>
        @if(!$paymentDetails->isEmpty())

        <h2>Payment Details:</h2>
        <table class="table">
            <tr>
                <th>Sr. No</th>
                <th>Registration No.</th>
                <th>Amount (Rs.)</th>
                <th>e-Challan No.</th>
                <th>Transaction Date & Time</th>
                <th>Mobile Number</th>
                <th>Status</th>
            </tr>
            @foreach($paymentDetails as $index => $payment)
            <tr>
                <td>{{$index + 1}}</td>
                <td>{{$faSubmitRequest->reg_no}}</td>
                <td>{{$payment->total_amt}}</td>
                <td>{{$payment->echallan_no}}</td>
                <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('d-m-Y H:i') }}</td>
                <td>{{$faSubmitRequest->mobile_no}}</td>
                <td>Success</td>
            </tr>
            @endforeach
        </table>
        @endif

        @if($reqestDisposeOf != null)
            <div class="page-break"></div>
        @endif

        @if($reqestDisposeOf != null)
        <h2>Officer's Reply:</h2>
        <div class="columnleft">
            <p><strong>Disposed Date & Time: </strong>{{ \Carbon\Carbon::parse($reqestDisposeOf->created_at)->format('d-m-Y H:i') }}</p>
        </div>
        <div class="columnRight">
            <p><strong>File Number : </strong> {{ $reqestDisposeOf->file_no ? $reqestDisposeOf->file_no : 'N/A'}}</p>
        </div>

        <br><br>

        <div class="columnleft">
            <p><strong>Disposal Type: </strong> {{$reqestDisposeOf->disposal_type}} </p>
        </div>
        <div class="columnRight">
            <p><strong>Officer Attached File: </strong> <a href="{{ asset($reqestDisposeOf->document_path) }}" target="_blank">{{ $reqestDisposeOf->original_doc_name }}</a></p>
        </div>

        <br><br>

        <div class="columnleft">
            <p><strong>Disposal Reason: </strong> {{$reqestDisposeOf->reason}} </p>
        </div>
        @endif
    </div>

</body>
