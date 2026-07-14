<head>
<style>
    @page {
        size: A4;
        margin: 15mm;
    }

    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        line-height: 1.5; /* Increased overall line height */
    }

    /* Restoring original header sizes and weights */
    h1 { text-align: center; font-size: 2rem; margin-bottom: 30px; }
    h2 { font-size: 1.5rem; margin-top: 35px; margin-bottom: 20px; font-weight: bold; }

    /* Layout structure with increased vertical spacing */
    .row-item {
        width: 100%;
        clear: both;
        display: block;
        padding: 8px 0; /* Increased padding between logical rows */
    }

    .column-left {
        float: left;
        width: 48%;
        text-align: left;
    }

    .column-right {
        float: right;
        width: 48%;
        text-align: right;
    }

    .center-block {
        width: 100%;
        text-align: center;
        margin: 20px 0;
        clear: both;
    }

    .full-width {
        width: 100%;
        clear: both;
        padding-top: 12px;
        padding-bottom: 12px;
    }

    /* Clearfix for floats */
    .clearfix::after {
        content: "";
        clear: both;
        display: table;
    }

    /* Blue link style */
    .file-link {
        color: #0000FF;
        text-decoration: underline;
    }

    .table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 25px;
    }

    .table th, .table td {
        border: 1px solid #000;
        padding: 10px;
        text-align: left;
    }

    .page-break { page-break-before: always; }

    /* Spacing between individual lines of text */
    p { margin: 0; padding: 5px 0; }
    strong { font-weight: bold; }
</style>
</head>

<body>
    <h1><strong>Detailed Information</strong></h1>

    <div class="section clearfix">
        <h2>Second Appeal Request Details:</h2>

        <div class="row-item clearfix">
            <div class="column-left">
                <p><strong>SA Reg No.:</strong> {{ $saSubmitRequest->sa_reg_no }}</p>
            </div>
            <div class="column-right">
                <p><strong>SA Req Filed Date:</strong> {{ \Carbon\Carbon::parse($saSubmitRequest->created_at)->format('d-m-Y H:i') }}</p>
            </div>
        </div>

        <div class="center-block">
            <p><strong>Department:</strong> {{ $saDeptDetails['dept']['department'] ?? 'N/A' }}</p>
            <p><strong>Office:</strong> {{ $saDeptDetails['subDept']['department'] ?? 'N/A' }}</p>
            <p><strong>Sub Office:</strong> {{ $saDeptDetails['office']['department'] ?? 'N/A' }}</p>
        </div>

        <div class="row-item clearfix">
            <div class="column-left">
                <p><strong>PIO Name:</strong> {{ $saSubmitRequest->pio_name ?? 'N/A' }}</p>
                <p><strong>FAA Name:</strong> {{ $saSubmitRequest->faa_name ?? 'N/A' }}</p>
            </div>
            <div class="column-right">
                <p><strong>PIO Address:</strong> {{ $saSubmitRequest->pio_address ?? 'N/A' }}</p>
                <p><strong>FAA Address:</strong> {{ $saSubmitRequest->faa_address ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="row-item clearfix">
            <div class="column-left">
                <p><strong>SA Document:</strong>
                    @if(isset($saFiles['saFile']))
                        <a href="#" class="file-link">{{ $saFiles['saFile']['document_name_original'] }}</a>
                    @else
                        N/A
                    @endif
                </p>
            </div>
            <div class="column-right">
                <p><strong>ID Proof:</strong>
                    @if(isset($saFiles['idProof']))
                        <a href="#" class="file-link">{{ $saFiles['idProof']['document_name_original'] }}</a>
                    @else
                        N/A
                    @endif
                </p>
            </div>
        </div>

        @if(isset($saFiles['supporting_doc']) && count($saFiles['supporting_doc']) > 0)
        <div class="full-width">
            <p><strong>Supporting Documents:</strong></p>
            <ul style="list-style-type: square; padding-left: 20px; margin-top: 10px;">
                @foreach($saFiles['supporting_doc'] as $document)
                    <li style="padding: 3px 0;"><a href="#" class="file-link">{{ $document['document_name_original'] }}</a></li>
                @endforeach
            </ul>
        </div>
        @endif

    @if($SA_Reply_To_Applicant != null)
    <div class="full-width">
        <h2>Officer's Reply:</h2>

        @if(!empty($SA_Reply_To_Applicant->reason))
            <div class="row-item clearfix">
                <div class="column-left">
                    <p>
                        <strong>Reverted Date & Time:</strong>
                        {{ \Carbon\Carbon::parse($SA_Reply_To_Applicant->created_at)->format('d-m-Y H:i') }}
                    </p>
                </div>
            </div>

            <div class="row-item clearfix">
                <div class="full-width">
                    <p>
                        <strong>Reverted Reason:</strong>
                    </p>
                    <p>
                        {{ $SA_Reply_To_Applicant->reason }}
                    </p>
                </div>
            </div>
        @endif
    </div>
    @endif

    </div>

    <div class="section clearfix">
        <h2>First Appeal Request Details:</h2>

        <div class="row-item clearfix">
            <div class="column-left">
                <p><strong>FA Reg No.:</strong> {{ $faSubmitRequest->fa_reg_no }}</p>
            </div>
            <div class="column-right">
                <p><strong>FA Req Filed Date:</strong> {{ \Carbon\Carbon::parse($faSubmitRequest->created_at)->format('d-m-Y H:i') }}</p>
            </div>
        </div>

        <div class="center-block">
            <p><strong>Department:</strong> {{ $faDeptDetails['dept'] ?? 'N/A' }}</p>
            <p><strong>Office:</strong> {{ $faDeptDetails['subDept'] ?? 'N/A' }}</p>
            <p><strong>Sub Office:</strong> {{ $faDeptDetails['office'] ?? 'N/A' }}</p>
        </div>

        <div class="row-item clearfix">
            <div class="column-left">
                <p><strong>Pio Name:</strong> {{ $faSubmitRequest->pioname }}</p>
            </div>
            <div class="column-right">
                <p><strong>Pio Address:</strong> {{ $faSubmitRequest->pioaddr }}</p>
            </div>
        </div>

        <div class="full-width">
            <p><strong>Pio Response:</strong> {{ $faSubmitRequest->pioResponseData['reason'] ?? 'N/A' }}</p>
            <p><strong>Text From Applicant:</strong> {{ $faSubmitRequest->requested_note }}</p>
        </div>

        <div class="row-item clearfix">
            <div class="column-left">
                @if(isset($filePaths['First Appeal']))
                    <p><strong>FA Document: </strong><a href="{{ asset($filePaths['First Appeal']) }}" class="file-link" target="_blank">First Appeal Document</a></p>
                @else
                    <p><strong>FA Document: </strong>No File Attached.</p>
                @endif
            </div>
            <div class="column-right">
                @if(isset($filePaths['Copy of PIO Reply']))
                    <p><strong>PIO Reply: </strong><a href="{{ asset($filePaths['Copy of PIO Reply']) }}" class="file-link" target="_blank">Copy of PIO Reply</a></p>
                @endif
                @if(isset($filePaths['Unknown Category']))
                    <p><strong>Support File: </strong><a href="{{ asset($filePaths['Unknown Category']) }}" class="file-link" target="_blank">Supporting Document</a></p>
                @endif
            </div>
        </div>
    </div>

    <div class="page-break"></div>

    <div class="section clearfix">
        <h2>Request Details:</h2>

        <div class="row-item clearfix">
            <div class="column-left">
                <p><strong>Reg No.:</strong> {{ $submitRequest->reg_no }}</p>
            </div>
            <div class="column-right">
                <p><strong>RTI Req Filed Date:</strong> {{ \Carbon\Carbon::parse($submitRequest->created_at)->format('d-m-Y H:i') }}</p>
            </div>
        </div>

        <div class="center-block">
            <p><strong>Department:</strong> {{ $deptDetails['dept'] ?? 'N/A' }}</p>
            <p><strong>Office:</strong> {{ $deptDetails['subDept'] ?? 'N/A' }}</p>
            <p><strong>Sub Office:</strong> {{ $deptDetails['office'] ?? 'N/A' }}</p>
        </div>

        <div class="row-item clearfix">
            <div class="column-left">
                <p><strong>Applicant Name:</strong> {{ $faSubmitRequest->name }}</p>
                <p><strong>Gender:</strong> {{ $faSubmitRequest->gender === 'female' ? 'Female' : ($faSubmitRequest->gender === 'male' ? 'Male' : 'Other') }}</p>
            </div>
            <div class="column-right">
                <p><strong>Village:</strong> {{ $faSubmitRequest->Village->name ?? 'Others' }}</p>
                <p><strong>District:</strong> {{ $faSubmitRequest->District->name ?? 'Others' }}</p>
            </div>
        </div>

        <div class="row-item clearfix">
            <div class="column-left">
                <p><strong>Address:</strong><br>
                {{ $faSubmitRequest->address_line1 }}<br>
                {{ $faSubmitRequest->address_line2 }}<br>
                {{ $faSubmitRequest->address_line3 }}</p>
            </div>
            <div class="column-right">
                <p><strong>State:</strong> {{ $faSubmitRequest->State->name ?? 'Others' }}</p>
                <p><strong>Pincode:</strong> {{ $faSubmitRequest->pincode }}</p>
            </div>
        </div>

        <div class="row-item clearfix">
            <div class="column-left">
                <p><strong>Phone:</strong> {{ $faSubmitRequest->phone_no }}</p>
                <p><strong>Education:</strong> {{ $faSubmitRequest->edu_status === 1 ? 'Educated' : 'Non-Educated' }}</p>
            </div>
            <div class="column-right">
                <p><strong>Email:</strong> {{ $faSubmitRequest->email_id }}</p>
                <p><strong>Mobile:</strong> {{ $faSubmitRequest->mobile_no }}</p>
            </div>
        </div>

        <div class="row-item clearfix">
            <div class="column-left">
                <p><strong>BPL Status:</strong> {{ $faSubmitRequest->is_bpl == 1 ? 'BPL User' : 'Non BPL User' }}</p>
                <p><strong>Qualification:</strong>
                    @if($submitRequest->edu_status === 0) N/A
                    @elseif($submitRequest->qualification == 1) SSC
                    @elseif($submitRequest->qualification == 2) HSSC
                    @elseif($submitRequest->qualification == 3) Graduate
                    @elseif($submitRequest->qualification == 4) Post Graduate
                    @endif
                </p>
            </div>
            <div class="column-right">
                <p><strong>Card No.:</strong> {{ $submitRequest->card_no ?: 'N/A' }}</p>
            </div>
        </div>

        <div class="full-width">
            <p><strong>Text From Applicant:</strong> {{ $submitRequest->request_text }}</p>
            <p><strong>File Uploaded by Applicant: </strong><a href="{{ asset($reqFilePath) }}" class="file-link" target="_blank">{{ $submitRequest->document_name_original }}</a></p>
        </div>
    </div>

    @if(!$paymentDetails->isEmpty())
    <div class="section clearfix">
        <h2>Payment Details:</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Sr. No</th>
                    <th>Registration No.</th>
                    <th>Amount (Rs.)</th>
                    <th>e-Challan No.</th>
                    <th>Date & Time</th>
                    <th>Mobile Number</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
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
            </tbody>
        </table>
    </div>
    @endif

    @if($reqestDisposeOf != null)
    <div class="page-break"></div>
    <div class="section clearfix">
        <h2>Officer's Reply:</h2>
        <div class="row-item clearfix">
            <div class="column-left">
                <p><strong>Disposed Date & Time: </strong>{{ \Carbon\Carbon::parse($reqestDisposeOf->created_at)->format('d-m-Y H:i') }}</p>
                <p><strong>Disposal Type: </strong> {{$reqestDisposeOf->disposal_type}} </p>
            </div>
            <div class="column-right">
                <p><strong>File Number : </strong> {{ $reqestDisposeOf->file_no ?: 'N/A'}}</p>
                <p><strong>Officer Attached File: </strong> <a href="{{ asset($reqestDisposeOf->document_path) }}" class="file-link" target="_blank">{{ $reqestDisposeOf->original_doc_name }}</a></p>
            </div>
        </div>
        <div class="full-width">
            <p><strong>Disposal Reason: </strong> {{$reqestDisposeOf->reason}} </p>
        </div>
    </div>
    @endif
</body>