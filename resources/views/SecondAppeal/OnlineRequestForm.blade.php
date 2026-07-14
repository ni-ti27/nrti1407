<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>First Form Submission</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


</head>

<style>
    .mainContainer
    {
        /* border : 2px solid black; */
        border-top: none;
    }
</style>

<body>
@include('UsersView.header')
<div class="container mt-5" style="margin-top: 0 !important">
    <div class="mainContainer row justify-content-center">
        <div class="col-md-10" style="margin: 20px 20px">
            <h4>Second Appeal Form (Online Request)</h4><br>
            @if(Session::has('success'))
                <div class="alert alert-success text-center">
                    {{Session::get('success')}}
                </div>
            @endif

            @if(Session::has('warning'))
                <div class="alert alert-warning text-center">
                    {{Session::get('warning')}}
                </div>
            @endif

            @if(Session::has('error'))
                <div class="alert alert-error text-center">
                    {{Session::get('error')}}
                </div>
            @endif

            <form name="sa-online" id="sa-online" method="post" enctype="multipart/form-data" action="{{ route('secondAppealOnlineSave') }}" novalidate>
            @csrf

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <h5 class="card-header">Public Authority Details:</h5>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="form-group col-md-4 mb-2 ">
                                    <label>Department/Public Authority</label>
                                    <select name="applied_dept" class="form-control" id="applied_dept" @readonly(true)>
                                        <option value="{{ $data['deptData']['applied_dept']->dept_code}}">
                                            {{ $data['deptData']['applied_dept']->department }}
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group col-md-4 mb-2 ">
                                    <label>Sub Department</label>
                                    <select name="applied_subdept" class="form-control" id="applied_subdept" @readonly(true)>
                                        <option value="{{ $data['deptData']['applied_subdept']->office_code}}">
                                            {{ $data['deptData']['applied_subdept']->department }}
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group col-md-4 mb-2 ">
                                    <label>Sub Office</label>
                                    <select name="applied_office" class="form-control" id="applied_office" @readonly(true)>
                                        <option value="{{ $data['deptData']['applied_office']->suboffice_code}}">
                                            {{ $data['deptData']['applied_office']->department }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="row justify-content-center">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <h5 class="card-header">Personal Details of RTI Applicant</h5>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="form-group col-md-6 mb-2">
                                    <label for="name">Applicant Name</label>
                                    <input type="text" class="form-control" name="name" id="name" value="{{ isset($faRequestDetails->name) ? $faRequestDetails->name : ''}}" placeholder="Firstname Lastname" readonly>
                                </div>

                                <div class="form-group col-md-6 mb-2" style="pointer-events: none;">
                                    <label for="gender">Gender</label>
                                    <select class="form-control" name="gender" id="gender" readonly>
                                    <option value="male" {{ isset($faRequestDetails->gender) && $faRequestDetails->gender === 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ isset($faRequestDetails->gender) && $faRequestDetails->gender === 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other"{{ isset($faRequestDetails->gender) && $faRequestDetails->gender === 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row justify-content-center">
                                <label for="address_line1">Address</label>
                                <div class="form-group col-md-4 mb-2">
                                    <input type="text" class="form-control" name="address_line1" id="address_line1" value="{{ isset($faRequestDetails->address_line1) ? $faRequestDetails->address_line1 : ''}}" placeholder="Address Line 1" readonly>
                                </div>
                                <div class="form-group col-md-4 mb-2">
                                    <input type="text" class="form-control" name="address_line2" id="address_line2" value="{{ isset($faRequestDetails->address_line2) ? $faRequestDetails->address_line2 : ''}}" placeholder="Address Line 2" readonly>
                                </div>
                                <div class="form-group col-md-4 mb-2">
                                    <input type="text" class="form-control" name="address_line3" id="address_line3" value="{{ isset($faRequestDetails->address_line3) ? $faRequestDetails->address_line3 : ''}}" placeholder="Address Line 3" readonly>
                                </div>
                            </div>

                            <div class="row justify-content-center">
                                <div class="form-group col-md-4 mb-2">
                                    <label for="pincode">Pincode</label>
                                    <input type="text" class="form-control" name="pincode" id="pincode" value="{{ isset($faRequestDetails->pincode) ? $faRequestDetails->pincode : ''}}" placeholder="Pin Code" readonly>
                                </div>
                                <div class="form-group col-md-4 mb-2" style="pointer-events: none;">
                                    <label for="country">Country</label>
                                    <select class="form-control @error('country') is-invalid @enderror" name="country" id="country" readonly>
                                        <option value="1">India</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4 mb-2" style="pointer-events: none;">
                                    <label for="state">State</label>
                                    <select name="state" class="form-control" id="state" readonly>
                                        <option value="{{$data['stateData']->id}}">{{$data['stateData']->name}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row justify-content-center" style="pointer-events: none;">
                                <div class="form-group col-md-4 mb-2">
                                    <label>District</label>
                                    <select class="form-control @error('district') is-invalid @enderror" name="district" id="district" readonly>
                                        @if($data['district'] == null)
                                            <option value="0">Others</option>
                                        @else
                                            <option value="{{$data['district']->id}}" selected>{{$data['district']->name}}</option>
                                        @endif
                                    </select>
                                    @error('district')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4 mb-2" style="pointer-events: none;">
                                    <label>Taluka</label>
                                    <select class="form-control @error('taluka') is-invalid @enderror" name="taluka" id="taluka" readonly>
                                        @if($data['taluka'] == null)
                                            <option value="0">Others</option>
                                        @else
                                            <option value="{{$data['taluka']->id}}">{{ $data['taluka']->name }}</option>
                                        @endif
                                    </select>
                                    @error('taluka')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4 mb-2" style="pointer-events: none;">
                                    <label>Village</label>
                                    <select class="form-control @error('village') is-invalid @enderror" name="village" id="village" readonly>
                                        @if($data['village'] == null)
                                        <h4>ahsdgajsdgasdg</h4>
                                            <option value="0">Others</option>
                                        @else
                                        <option value="{{$data['village']->id}}">{{ $data['village']->name }}</option>
                                        @endif
                                    </select>
                                    @error('village')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row justify-content-center">
                                <div class="form-group mb-2 col-md-4">
                                    <label>Phone Number</label>
                                    <input type="text" class="form-control" name="phone_no" id="phone_no" value="{{ isset($faRequestDetails->phone_no) ? $faRequestDetails->phone_no : ''}}" placeholder="0832 2347874" readonly>
                                </div>

                                <div class="form-group mb-2 col-md-4">
                                    <label>Mobile Number</label>
                                    <input type="text" class="form-control" name="mobile_no" id="mobile_no" value="{{ isset($faRequestDetails->mobile_no) ? $faRequestDetails->mobile_no : ''}}" placeholder="+91 9865478864" readonly>
                                </div>

                                <div class="form-group mb-2 col-md-4">
                                    <label>Email Address</label>
                                    <input type="email" class="form-control" name="email_id" id="email_id" value="{{ isset($faRequestDetails->email_id) ? $faRequestDetails->email_id : ''}}" readonly>
                                </div>
                            </div>

                            <div class="row justify-content-center">
                                <div class="form-group col-md-4 mb-2">
                                    <label>Citizenship</label>
                                    <input type="text" readonly class="form-control" name="origin_country" id="origin_country" value="Indian" placeholder="Bharat">
                                </div>

                                <div class="form-group col-md-4 mb-2" style="pointer-events: none;">
                                    <label>Education</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="edu_status" id="edu_status1" value="1" {{ isset($data['submitReq']->edu_status) && $data['submitReq']->edu_status == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="edu_status1">Educated</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="edu_status" id="edu_status2" value="0"
                                        {{ isset($data['submitReq']->edu_status) && $data['submitReq']->edu_status === 0 ? 'selected' : '' }}                                            <label class="form-check-label" for="edu_status2">Non-Educated</label>
                                    </div>
                                </div>

                                <div class="form-group col-md-4 mb-2" style="pointer-events: none;">
                                    <label>Is Applicant Below Poverty Line?</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="is_bpl" id="is_not_bpl" value="0" {{ $data['submitReq']->is_bpl == 0 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_not_bpl">No</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="is_bpl" id="is_bpl" value="1" {{ $data['submitReq']->is_bpl == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_bpl">Yes</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <h5 class="card-header">Details of RTI Application</h5>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="form-group col-md-6 mb-2">
                                    <label for="reg_no">RTI Request Registration Number</label>
                                    <input type="text" class="form-control" name="reg_no" id="reg_no" value="{{ isset($data['submitReq']->reg_no) ? $data['submitReq']->reg_no : ''}}" placeholder="Requested Registration Number" readonly>
                                    @error('reg_no')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6 mb-2">
                                    <label for="reg_date">RTI Request Filed on (Date)</label>
                                    <input type="text" class="form-control reservationdate" name="reg_date" id="reg_date" value="{{ isset($data['submitReq']->created_at) ? $data['submitReq']->created_at->format('d/m/Y') : ''}}" data-toggle="datetimepicker" disabled>
                                    <input type="hidden" name="hidden_reg_date" id="hidden_reg_date" value="{{$data['submitReq']->created_at}}">
                                    @error('reg_date')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <br>
                            <div class="row justify-content-center">
                                <div class="form-group col-md-6 mb-2">
                                    <label for="pio_name">PIO Name</label>
                                    <input type="text" class="form-control" name="pio_name" id="pio_name" value="{{ isset($faRequestDetails->pioname) ? $faRequestDetails->pioname : ''}}" placeholder="Requested Registration Number" readonly>
                                    <input type="hidden" id="pio_id" name="pio_id" value="{{$faRequestDetails->id}}">
                                </div>
                                <div class="form-group col-md-6 mb-2">
                                    <label for="pio_address">PIO Address</label>
                                    <input type="text" class="form-control reservationdate" name="pio_address" id="pio_address" value="{{ isset($faRequestDetails->pioaddr) ? $faRequestDetails->pioaddr : ''}}" readonly>
                                </div>
                            </div>
                            <br>
                            <div class="row justify-content-center">
                                <div class="form-group col-md-6 mb-2">
                                    <label for="pio_response_reason">Nature of Response of PIO (Ground for Appeal)</label>
                                    <input type="text" class="form-control" name="pio_response_reason" id="pio_response_reason" value="{{ isset($faRequestDetails->pioResponseData['reason']) ? $faRequestDetails->pioResponseData['reason'] : ''}}" readonly>
                                    <input type="hidden" id="pio_response_display" name="pio_response_display" value="{{$faRequestDetails->pioResponseData['id']}}">
                                </div>
                                <div class="form-group col-md-6 mb-2">
                                    <label>Copy of the RTI Application (Only Pdf)</label><br>
                                    <a href="{{ asset($data['filePath']) }}" target="_blank">{{ $data['submitReq']->document_name_original }}</a>
                                </div>
                            </div>
                            <br>
                            <div class="row justify-content-center">
                                <div class="form-group col-md-8 mb-2">
                                <label for="user_text">Requested Text</label>
                                    <textarea class="form-control" name="user_text" id="user_text" style="height: 180px" readonly>{{ isset($data['submitReq']->request_text) ? $data['submitReq']->request_text : ''}}</textarea>

                                    <!-- <input type="text" class="form-control" name="user_text" id="user_text" value="{{ isset($data['submitReq']->request_text) ? $data['submitReq']->request_text : ''}}" readonly> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <h5 class="card-header">Details of First Appeal Application</h5>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="form-group col-md-6 mb-2">
                                    <label for="fa_reg_no">RTI First Appeal Registration Number<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="fa_reg_no" id="fa_reg_no" value="{{ isset($faRequestDetails->fa_reg_no) ? $faRequestDetails->fa_reg_no : ''}}" readonly>
                                </div>
                                <div class="form-group col-md-6 mb-2">
                                    <label for="fa_reg_date">RTI FA Request Filed on (Date)<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control reservationdate" name="fa_reg_date" id="fa_reg_date" value="{{ isset($faRequestDetails->created_at) ? $faRequestDetails->created_at->format('d/m/Y') : ''}}" data-toggle="datetimepicker" disabled>
                                    <input type="hidden" name="hidden_faa_date" id="hidden_faa_date" value="{{$faRequestDetails->created_at}}">
                                </div>
                            </div>
                            <br>
                            <div class="row justify-content-center">
                                <div class="form-group col-md-6 mb-2">
                                    <label for="faa_name">FAA User Name<span class="text-danger">*</span></label>
                                    <select class="form-control @error('faa_name') is-invalid @enderror" name="faa_name" id="faa_name" readonly>
                                        <option value="{{ isset($requestStatus->name) ? $requestStatus->name : ''}}">{{{$requestStatus->name}}}</option>
                                    </select>
                                    <input type="hidden" id="faa_id" name="faa_id" value="{{$requestStatus->id}}">
                                </div>
                                <div class="form-group col-md-6 mb-2">
                                    <label for="faa_address">FAA User Address<span class="text-danger">*</span></label>
                                    <select class="form-control @error('faa_address') is-invalid @enderror" name="faa_address" id="faa_address" readonly>
                                        <option value="{{ isset($requestStatus->address1) ? $requestStatus->address1 : ''}}">{{{$requestStatus->address1}}}</option>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row justify-content-center">
                                <div class="form-group col-md-6 mb-2">
                                    <label for="pio_name">PIO Name<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="pio_name" id="pio_name" value="{{ isset($faRequestDetails->pioname) ? $faRequestDetails->pioname : ''}}" placeholder="Requested Registration Number" readonly>
                                </div>
                                <div class="form-group col-md-6 mb-2">
                                    <label for="pio_address">PIO Address<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control reservationdate" name="pio_address" id="pio_address" value="{{ isset($faRequestDetails->pioaddr) ? $faRequestDetails->pioaddr : ''}}" readonly>
                                </div>
                            </div>

                            <br>

                            <div class="row justify-content-center">
                            @if(isset($filePaths['First Appeal']))
                                    <div class="form-group col-md-6 mb-2" style="text-align:center">
                                        <label for="fa_application_doc">FAA Document PDF<span class="text-danger">*</span></label>
                                        <br>
                                        <a href="{{ asset($filePaths['First Appeal']) }}" target="_blank">First Appeal Document</a></p>
                                    </div>
                                <br>
                            @endif

                            @if(!empty($filePaths['Support']))
                                    <div class="form-group col-md-6 mb-2" style="text-align:center">
                                        <label for="fa_support_doc">FAA Support Document PDF<span class="text-danger">*</span></label>
                                        <br>
                                        <a href="{{ asset($filePaths['Support']) }}" target="_blank">First Appeal Support Document</a></p>
                                    </div>
                                <br>
                            @endif
                            </div>

                            <div class="row justify-content-center">
                                <div class="form-group col-md-8 mb-2">
                                    <label for="faa_requested_text">Requested Text<span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="faa_requested_text" id="faa_requested_text" style="height: 140px" readonly>{{ $faRequestDetails->requested_note }} </textarea>
                                </div>
                            </div>
                            <br>

                        </div>
                    </div>
                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <h5 class="card-header">Second Appeal Details</h5>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="form-group col-md-5 mb-2">
                                    <label>Nature of Response of FAA (Ground for Appeal)<span class="text-danger">*</span></label>
                                    <select class="form-control @error('faa_resp_dropdown') is-invalid @enderror" name="faa_resp_dropdown" id="faa_resp_dropdown">
                                        <option value="">Select Response</option>
                                        <option value="1" {{ old('faa_resp_dropdown') == '1' ? 'selected' : '' }}>Refused access to the Information Requested</option>
                                        <option value="2" {{ old('faa_resp_dropdown') == '2' ? 'selected' : '' }}>No Response within the Time Limit</option>
                                        <option value="3" {{ old('faa_resp_dropdown') == '3' ? 'selected' : '' }}>Unreasonable Amount of Fee is Required to Pay</option>
                                        <option value="4" {{ old('faa_resp_dropdown') == '4' ? 'selected' : '' }}>Provided Incomplete, Misleading or False Information</option>
                                        <option value="5" {{ old('faa_resp_dropdown') == '5' ? 'selected' : '' }}>Any Other Ground</option>
                                    </select>
                                    @error('faa_resp_dropdown')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-5 mb-2">
                                    <label>Copy of Reply of FA (Only Pdf)<span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('sa_application_doc') is-invalid @enderror" name="sa_application_doc" id="sa_application_doc" placeholder="Choose file" value="{{old('sa_application_doc')}}">
                                    @error('sa_application_doc')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <br>
                            <div class="row justify-content-center">
                                <div class="form-group col-md-5 mb-2">
                                    <label>Supporting Document 1</label>
                                    <input type="file" class="form-control @error('sa_document1') is-invalid @enderror" name="sa_document1" id="sa_document1" placeholder="Choose file" value="{{old('sa_document1')}}">
                                    @error('sa_document1')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-5 mb-2">
                                    <label>Supporting Document 2</label>
                                    <input type="file" class="form-control @error('sa_document2') is-invalid @enderror" name="sa_document2" id="sa_document2" placeholder="Choose file" value="{{old('sa_document2')}}">
                                    @error('sa_document2')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <br>
                            <div class="row justify-content-center">
                                <div class="form-group col-md-5 mb-2">
                                    <label>Supporting Text for Document 1</label>
                                    <br>
                                    <span style="font-size: 12px; color: blue;" for="">Only alphabets A-Z a-z number 0-9 and special characters , . - _ ( ) / @ : & \ % are allowed in Text for RTI Request application</span>
                                    <textarea style="height: 100px" value="{{ old('sa_document1_supporting_text')}}" class="form-control @error('sa_document1_supporting_text') is-invalid @enderror" maxlength="3000" name="sa_document1_supporting_text" id="sa_document1_supporting_text"></textarea>
                                    @error('sa_document1_supporting_text')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-5 mb-2">
                                    <label>Supporting Text for Document 2</label>
                                    <br>
                                    <span style="font-size: 12px; color: blue;" for="">Only alphabets A-Z a-z number 0-9 and special characters , . - _ ( ) / @ : & \ % are allowed in Text for RTI Request application</span>
                                    <textarea style="height: 100px" value="{{ old('sa_document2_supporting_text')}}" class="form-control @error('sa_document2_supporting_text') is-invalid @enderror" maxlength="3000" name="sa_document2_supporting_text" id="sa_document2_supporting_text"></textarea>
                                    @error('sa_document2_supporting_text')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <h5 class="card-header">Applicant Personal Information</h5>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="form-group col-md-5 mb-2">
                                    <label>Id Proof<span class="text-danger">*</span></label>
                                    <select class="form-control @error('id_proof') is-invalid @enderror" name="id_proof" id="id_proof">
                                        <option value="">Select Document</option>
                                        <option value="1" {{ old('id_proof') == '1' ? 'selected' : '' }}>Aadhar Card</option>
                                        <option value="2" {{ old('id_proof') == '2' ? 'selected' : '' }}>Pan Card</option>
                                        <option value="3" {{ old('id_proof') == '3' ? 'selected' : '' }}>Driving Liscence</option>
                                        <option value="4" {{ old('id_proof') == '4' ? 'selected' : '' }}>Ration Card</option>
                                        <option value="5" {{ old('id_proof') == '5' ? 'selected' : '' }}>Govt. Id Card (if any)</option>
                                        <option value="5" {{ old('id_proof') == '6' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('id_proof')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-5 mb-2">
                                    <label>ID Proof File<span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('id_proof_file') is-invalid @enderror" name="id_proof_file" id="id_proof_file" placeholder="Choose file" value="{{old('id_proof_file')}}">
                                    @error('id_proof_file')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <h5 class="card-header">Declaration<span class="text-danger">*</span></h5>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="form-group col-md-7 mb-2">
                                    <input type="checkbox" id="declaration" name="declaration" class="custom-control-input" onclick="checkSubmit()">
                                    <label class="custom-control-label" for="declaration">The Information as provided above is true to the best of my knowledge</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <x-captcha />
            <div class="row justify-content-center">
                <button type="submit" id="submit_button" name="submit_button" class="btn btn-dark btn-block" onclick="validateForm()" disabled>Submit</button>
            </div>
            </form>
            <br>
        </div>
    </div>
</div>
<br>

</body>

<script type="text/javascript">

$(document).ready(function()
{
    $('#submit_button').click(function(e)
        {
            e.preventDefault();

            $('.invalid-feedback').remove();
            $('.form-control').removeClass('is-invalid');

            var isValid = true;

            if ($('#faa_resp_dropdown').val() === "")
            {
                isValid = false;
                $('#faa_resp_dropdown').addClass('is-invalid');
                $('#faa_resp_dropdown').after('<span class="invalid-feedback">Please select a response from the dropdown.</span>');
            }

            var faaReplyDocument = $('#sa_application_doc')[0].files[0];
            if (!faaReplyDocument)
            {
                isValid = false;
                $('#sa_application_doc').addClass('is-invalid');
                $('#sa_application_doc').after('<span class="invalid-feedback">This field is required.</span>');
            }
            else
            {
                var fileSize = faaReplyDocument.size / 1024 / 1024; // in MB
                var fileExtension = faaReplyDocument.name.split('.').pop().toLowerCase();

                if (fileExtension !== 'pdf')
                {
                    isValid = false;
                    $('#sa_application_doc').addClass('is-invalid');
                    $('#sa_application_doc').after('<span class="invalid-feedback">Only PDF files are allowed.</span>');
                }

                if (fileSize > 5 * 1024 * 1024)
                {
                    isValid = false;
                    $('#sa_application_doc').addClass('is-invalid');
                    $('#sa_application_doc').after('<span class="invalid-feedback">File size should not exceed 5MB.</span>');
                }
            }

            var faDocument1 = $('#sa_document1')[0].files[0];
            if (faDocument1)
            {
                var allowedExtensions = ['pdf', 'doc', 'docx', 'txt', 'xls', 'xlsx', 'jpg', 'jpeg'];
                var faDoc1Extension = faDocument1.name.split('.').pop().toLowerCase();
                if (!allowedExtensions.includes(faDoc1Extension))
                {
                    isValid = false;
                    $('#sa_document1').addClass('is-invalid');
                    $('#sa_document1').after('<span class="invalid-feedback">Only PDF, DOC, DOCX, TXT, XLS, XLSX, JPG, and JPEG files are allowed.</span>');
                }
            }

            var faDocument2 = $('#sa_document2')[0].files[0];
            if (faDocument2)
            {
                var allowedExtensions = ['pdf', 'doc', 'docx', 'txt', 'xls', 'xlsx', 'jpg', 'jpeg'];
                var faDoc2Extension = faDocument2.name.split('.').pop().toLowerCase();
                if (!allowedExtensions.includes(faDoc2Extension))
                {
                    isValid = false;
                    $('#sa_document2').addClass('is-invalid');
                    $('#sa_document2').after('<span class="invalid-feedback">Only PDF, DOC, DOCX, TXT, XLS, XLSX, JPG, and JPEG files are allowed.</span>');
                }
            }

            if ($('#sa_document1').val() && $('#sa_document1_supporting_text').val() === "")
            {
                isValid = false;
                $('#sa_document1_supporting_text').addClass('is-invalid');
                $('#sa_document1_supporting_text').after('<span class="invalid-feedback">Please enter text for Supporting Document 1.</span>');
            }

            if ($('#sa_document2').val() && $('#sa_document2_supporting_text').val() === "")
            {
                isValid = false;
                $('#sa_document2_supporting_text').addClass('is-invalid');
                $('#sa_document2_supporting_text').after('<span class="invalid-feedback">Please enter text for Supporting Document 2.</span>');
            }

            if ($('#id_proof').val() === "")
            {
                isValid = false;
                $('#id_proof').addClass('is-invalid');
                $('#id_proof').after('<span class="invalid-feedback">Please select ID Proof document type</span>');
            }

            var idProofFile = $('#id_proof_file')[0].files[0];
            if (idProofFile)
            {
                var allowedExtensions = ['pdf','jpg', 'jpeg'];
                var idProofExtension = idProofFile.name.split('.').pop().toLowerCase();
                if (!allowedExtensions.includes(idProofExtension))
                {
                    isValid = false;
                    $('#id_proof_file').addClass('is-invalid');
                    $('#id_proof_file').after('<span class="invalid-feedback">Only PDF, JPG, and JPEG files are allowed.</span>');
                }
            }
            else
            {
                isValid = false;
                $('#id_proof_file').addClass('is-invalid');
                $('#id_proof_file').after('<span class="invalid-feedback">This field is required.</span>');
            }

            // If all validations pass, submit the form
            if (isValid) {
                $('form').submit(); // Manually submit the form
            }
        });
    });


function checkSubmit()
{
    let declarationCheckBox = document.querySelector('#declaration');
    let submit_button = document.querySelector('#submit_button');

    // console.log('Checkbox checked: ' + declarationCheckBox.checked);

    if (declarationCheckBox.checked) {
        // console.log('Enabling the submit button');
        submit_button.disabled = false; // Enable the submit button
    } else {
        // console.log('Disabling the submit button');
        submit_button.disabled = true;  // Disable the submit button
    }
}

    // Wait until the page is fully loaded
    document.addEventListener('DOMContentLoaded', function() {

let tooltip;

document.querySelectorAll('.cursor-tooltip').forEach(el => {

    el.addEventListener('mouseenter', function(e) {
        tooltip = document.createElement('div');
        tooltip.className = 'custom-cursor-tooltip';
        tooltip.innerHTML = el.dataset.tooltip;
        document.body.appendChild(tooltip);
    });

    el.addEventListener('mousemove', function(e) {
        if (!tooltip) return;

        // Tooltip position relative to cursor
        const offsetX = 15; // distance from cursor
        const offsetY = 15;

        tooltip.style.left = (e.clientX + offsetX) + 'px';
        tooltip.style.top  = (e.clientY + offsetY) + 'px';
        tooltip.style.opacity = 1;

        // Arrow points to cursor (bottom-left of tooltip)
        tooltip.style.setProperty('--arrow-left', '10px');
        tooltip.style.setProperty('--arrow-top', '-6px'); // above tooltip box
    });

    el.addEventListener('mouseleave', function() {
        if (tooltip) tooltip.remove();
        tooltip = null;
    });

});
});
</script>