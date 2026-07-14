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
        <h2>Request Details:</h2>
        <div class="columnleft">
            <p><strong>Reg No.:</strong> {{ $submitRequest->reg_no }}</p>
        </div>
        <div class="columnRight">
            <p><strong>Filed Date:</strong> {{ \Carbon\Carbon::parse($submitRequest->created_at)->format('d-m-Y H:i') }}</p>
        </div>
        <br><br>
        <div style="text-align:center">
        @if(!empty($orgDeptDetails))

            <h4>Original Department</h4>
            <p><strong>Department:</strong> {{ $orgDeptDetails['dept'] ?? 'N/A' }}</p>
            <p><strong>Sub - Department:</strong> {{ $orgDeptDetails['subDept'] ?? 'N/A' }}</p>
            <p><strong>Office:</strong> {{ $orgDeptDetails['office'] ?? 'N/A' }}</p>
            <hr style="border-top: 1px solid #000;">
            <br>
            <h4>Partially Transferred Department</h4>
        @endif

        <p><strong>Department:</strong> {{ $deptDetails ?? 'N/A' }}</p>
        <p><strong>Sub - Department:</strong> {{ $officeDetails ?? 'N/A' }}</p>
        <p><strong>Office:</strong> {{ $subOfficeDetails ?? 'N/A' }}</p>

        </div>
        <div class="columnleft">
            <p><strong>Applicant Name:</strong> {{ $submitRequest->name }}</p>
        </div>
        <div class="columnRight">
            @if($submitRequest->state === 0)
                <p><strong>Village:</strong> Others</p>
            @else
                <p><strong>Village:</strong> {{ $submitRequest->Village->name ?? 'Others' }}</p>
            @endif
        </div>
        <br><br>
        <div class="columnleft">
            <p><strong>Gender:</strong> {{ $submitRequest->gender === 'female' ? 'Female' : ($submitRequest->gender ===     'male' ? 'Male' : 'Other') }}</p>
        </div>
        <div class="columnRight">
            @if($submitRequest->state === 0)
                <p><strong>District:</strong> Others</p>
            @else
                <p><strong>District:</strong> {{ $submitRequest->District->name ?? 'Others' }}</p>
            @endif
        </div>
        <br><br>
        <div class="columnleft">
            <p>
                <strong>Address:</strong><br>
                {{ $submitRequest->address_line1 }}<br>
                {{ $submitRequest->address_line2 }}<br>
                {{ $submitRequest->address_line3 }}
            </p>
        </div>
        <div style="margin-top: -100px; text-align:right">
            @if($submitRequest->state === 0)
                <p><strong>State:</strong> Others</p>
            @else
                <p><strong>State:</strong> {{ $submitRequest->State->name ?? 'Others' }}</p>
            @endif
        </div>
        <br><br>
        <div class="columnleft">
            <p><strong>Pincode:</strong> {{ $submitRequest->pincode }} </p>
        </div>
        <div class="columnRight">
            <p><strong>Phone:</strong> {{ $submitRequest->phone_no }}</p>
        </div>
        <br><br>
        <div class="columnleft">
            <p><strong>Education:</strong> {{ $submitRequest->edu_status === 1 ? 'Educated' : 'Non-Educated' }}</p>
        </div>
        <div class="columnRight">
            <p><strong>Email:</strong> {{ $submitRequest->email_id }}</p>
        </div>

        <br><br>
        <div class="columnleft">
            <p><strong>Mobile:</strong> {{ $submitRequest->mobile_no }}</p>
        </div>
        <div class="columnRight">
            <p><strong>BPL:</strong> {{ $submitRequest->is_bpl == 1 ? 'BPL User' : 'Non BPL User' }}</p>
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
            <p><strong>File Uploaded by Applicant: </strong><a href="{{ asset($filePath) }}" target="_blank">{{ $submitRequest->document_name_original }}</a></p>
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
                <td>{{$submitRequest->reg_no}}</td>
                <td>{{$payment->total_amt}}</td>
                <td>{{$payment->echallan_no}}</td>
                <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('d-m-Y H:i') }}</td>
                <td>{{$submitRequest->mobile_no}}</td>
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
            <p><strong>Officer Attached File: </strong> <a href="{{ asset($officerFile) }}" target="_blank">{{ $reqestDisposeOf->original_doc_name }}</a></p>
        </div>

        <br><br>

        <div class="columnleft">
            <p><strong>Disposal Reason: </strong> {{$reqestDisposeOf->reason}} </p>
        </div>
        @endif

        @if($requestToApplicant != null)
        <br><br>
        <h2>Officer's Reply:</h2>
           <br><br>
        <div class="columnleft">
            <p><strong>Reverted Date & Time:  </strong>{{ \Carbon\Carbon::parse($requestToApplicant->created_at)->format('d-m-Y H:i') }}</p>
        </div>
        <br><br>
        <div class="columnleft">
            <p><strong>Reverted Reason: </strong> {{$requestToApplicant->reason}}</p>
        </div>
        @endif
    </div>

</body>

