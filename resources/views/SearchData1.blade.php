<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<style>
.container.mt-5
{
    border: 2px solid black;
    /* border-bottom: none; */
    border-top:none;
    margin-top:0 !important;
}
</style>

<body>
    @include('OfficerView.Header')
    <div class="container mt-5">
        <br>
        <div class="row">
            <div class="">
                <div class="card">
                    <h5 class="card-header text-center">Request Details</h5>
                    <div class="row">
                        <br>
                        <div class="col-md-6" style="padding-left:5%">
                            <br>
                            <p><strong>Registration No:</strong> @if($requestType == 'New Request') {{ $requestData->reg_no }} @else {{ $requestData->fa_reg_no }} @endif</p>
                            <p><strong>Applicant Name:</strong> {{ $requestData->name }}</p>
                            <p><strong>Address:</strong><br>
                                {{ $requestData->address_line1 }}<br>
                                {{ $requestData->address_line2 }}<br>
                                {{ $requestData->address_line3 }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <br>
                            <p><strong>e_Filling Date:</strong> {{ \Carbon\Carbon::parse($requestData->created_at)->format('d-m-Y H:i') }}</p>

                            <p><strong>Mobile:</strong> {{ $requestData->mobile_no }}</p>
                            <p><strong>Email:</strong> {{ $requestData->email_id }}</p>
                            <p><strong>Pincode:</strong> {{ $requestData->pincode }} </p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6" style="padding-left:5%">
                            <p><strong>Department:</strong> {{ $departmentDetails1 }}</p>
                            <p> {{ $departmentDetails2 }}</p>
                            <p> {{ $departmentDetails3 }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Pending with Name (Username):</strong>{{ $requestData->deptusername }} ({{ $requestData->username }})</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12" style="padding-left:5%">
                            <p><strong>Actions:</strong></p>

                            <!-- @foreach($reqStatus as $req)
                                {{$req}}
                            @endforeach -->

                            <table class="table table-bordered">
                                <tr>
                                    <th>Sr. No</th>
                                    <th>Action Taken</th>
                                    <th>Action Date</th>
                                </tr>
                                @foreach($reqStatus as $index => $req)
                                <tr>
                                    <td>{{$index + 1}}</td>
                                    <td>{{$req->action_}}</td>
                                    <td>{{ \Carbon\Carbon::parse($req->app_history_created)->format('d-m-Y') }}</td>
                                </tr>
                                @endforeach
                            </table>

                        </div>
                    </div>
                    <!-- <div class="row">
                        <div class="col-md-6" style="padding-left:5%">
                        <p><strong>Education:</strong> {{ $requestData->edu_status === 1 ? 'Educated' : 'Non-Educated' }}</p>
                        <p><strong>BPL:</strong> {{ $requestData->is_bpl == 1 ? 'BPL User' : 'Non BPL User' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Qualification:</strong>
                                @if($requestData->edu_status === 0)
                                    N/A
                                @elseif($requestData->qualification == 1)
                                    SSC
                                @elseif($requestData->qualification == 2)
                                    HSSC
                                @elseif($requestData->qualification == 3)
                                    Graduate
                                @elseif($requestData->qualification == 4)
                                    Post Graduate
                                @endif
                            </p>
                            <p><strong>Card No.:</strong> {{ $requestData->card_no === 0 ? 'N/A' : $requestData->card_no }}</p>
                        </div>
                    </div> -->

                </div>
                <div class="card">
                    <h5 class="card-header">Print Details:</h5>
                    <div class="card-body">
                        <a href="/search-pdf/@if($requestType == 'New Request') {{ base64_encode($requestData->reg_no) }} @else {{ base64_encode($requestData->fa_reg_no) }} @endif" target="_blank">Print As PDF</a>
                    </div>
                </div>
                <br>
            </div>
        </div>
        <br>
    </div>