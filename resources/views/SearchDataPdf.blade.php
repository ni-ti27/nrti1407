<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Request Details - {{ $requestData->reg_no ?? $requestData->fa_reg_no }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif; /* Good for PDF character support */
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }
        .header-title {
            text-align: center;
            background-color: #f8f9fa;
            padding: 10px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            vertical-align: top;
            padding: 8px 5px;
            width: 50%;
        }
        .label {
            font-weight: bold;
        }
        hr {
            border: 0;
            border-top: 1px solid #ccc;
            margin: 10px 0;
        }
        /* Action History Table */
        .history-table {
            width: 100%;
            border: 1px solid #ddd;
        }
        .history-table th {
            background-color: #f2f2f2;
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .history-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .section-title {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 10px;
            display: block;
        }
    </style>
</head>
<body>

    <div class="header-title">Request Details</div>

    <table class="info-table">
        <tr>
            <td>
                <span class="label">Registration No:</span>
                {{ $requestType == 'New Request' ? $requestData->reg_no : $requestData->fa_reg_no }}<br>

                <span class="label">Applicant Name:</span> {{ $requestData->name }}<br>

                <span class="label">Address:</span><br>
                {{ $requestData->address_line1 }}<br>
                {{ $requestData->address_line2 }}<br>
                {{ $requestData->address_line3 }}
            </td>
            <td>
                <span class="label">e_Filling Date:</span> {{ \Carbon\Carbon::parse($requestData->created_at)->format('d-m-Y H:i') }}<br>
                <span class="label">Mobile:</span> {{ $requestData->mobile_no }}<br>
                <span class="label">Email:</span> {{ $requestData->email_id }}<br>
                <span class="label">Pincode:</span> {{ $requestData->pincode }}
            </td>
        </tr>
    </table>

    <hr>

    <table class="info-table">
        <tr>
            <td>
                <span class="label">Department:</span><br>
                {{ $departmentDetails1 }}<br>
                {{ $departmentDetails2 }}<br>
                {{ $departmentDetails3 }}
            </td>
            <td>
                <span class="label">Pending with Name (Username):</span><br>
                {{ $requestData->deptusername }} ({{ $requestData->username }})
            </td>
        </tr>
    </table>

    <div style="margin-top: 20px;">
        <span class="section-title">Actions:</span>
        <table class="history-table">
            <thead>
                <tr>
                    <th style="width: 10%;">Sr. No</th>
                    <th style="width: 60%;">Action Taken</th>
                    <th style="width: 30%;">Action Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reqStatus as $index => $req)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $req->action_ }}</td>
                    <td>{{ \Carbon\Carbon::parse($req->app_history_created)->format('d-m-Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>
</html>