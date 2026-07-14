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
                <h4>Second Appeal Form (Offline Request)</h4><br>
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

                <form method="post" enctype="multipart/form-data" action="{{ route('secondAppealOfflineSave') }}" novalidate>
                @csrf

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <h5 class="card-header">Public Authority Details:</h5>
                            <div class="card-body">
                                <div class="row justify-content-center">
                                    <div class="form-group col-md-4 mb-2 ">
                                        <label>Select Department/Public Authority<span class="text-danger">*</span></label>
                                        <select name="applied_dept" class="form-control" id="applied_dept">
                                            <option value="">Select Department</option>
                                            @foreach ($deptData as $id => $department)
                                                <option value="{{ $department->dept_code }}" {{ old('applied_dept') == $department->dept_code ? 'selected' : '' }}>
                                                    {{ $department->department }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('applied_dept')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4 mb-2">
                                        <label for="applied_subdept">Select Office<span class="text-danger">*</span></label>
                                        <select name="applied_subdept" class="form-control" id="applied_subdept">
                                            <option value="">Select Office</option>
                                        </select>
                                        @error('applied_subdept')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4 mb-2">
                                        <label for="applied_office">Select Sub Office<span class="text-danger">*</span></label>
                                        <select name="applied_office" class="form-control" id="applied_office">
                                            <option value="">Select Sub Office</option>
                                        </select>
                                        @error('applied_office')
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
                            <h5 class="card-header">Personal Details of RTI Applicant</h5>
                            <div class="card-body">
                                <div class="row justify-content-center">
                                    <div class="form-group col-md-6 mb-2">
                                        <label for="name">Applicant Name<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" id="name" placeholder="Firstname Lastname">
                                    </div>

                                    <div class="form-group col-md-6 mb-2">
                                        <label for="gender">Gender<span class="text-danger">*</span></label>
                                        <select class="form-control" name="gender" id="gender">
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <label for="address_line1">Address<span class="text-danger">*</span></label>
                                    <div class="form-group col-md-4 mb-2">
                                        <input type="text" class="form-control" name="address_line1" id="address_line1" placeholder="Address Line 1">
                                    </div>
                                    <div class="form-group col-md-4 mb-2">
                                        <input type="text" class="form-control" name="address_line2" id="address_line2" placeholder="Address Line 2">
                                    </div>
                                    <div class="form-group col-md-4 mb-2">
                                        <input type="text" class="form-control" name="address_line3" id="address_line3" placeholder="Address Line 3">
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="form-group col-md-4 mb-2">
                                        <label for="pincode">Pincode<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="pincode" id="pincode" placeholder="Pin Code">
                                    </div>
                                    <div class="form-group col-md-4 mb-2">
                                        <label for="country">Country</label>
                                        <select class="form-control @error('country') is-invalid @enderror" name="country" id="country">
                                            <option value="1">India</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4 mb-2">
                                        <label for="state">State<span class="text-danger">*</span></label>
                                        <select name="state" class="form-control" id="state">
                                            <option value="6">Goa</option>
                                            <option value="0">Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="form-group col-md-4 mb-2">
                                        <label for="district">District<span class="text-danger">*</span></label>
                                        <select class="form-control @error('district') is-invalid @enderror" name="district" id="district">
                                        <option value="">Select District</option>
                                        </select>
                                        @error('district')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4 mb-2">
                                        <label for="taluka">Taluka<span class="text-danger">*</span></label>
                                        <select class="form-control @error('taluka') is-invalid @enderror" name="taluka" id="taluka">
                                            <option value="">Select Taluka</option>
                                        </select>
                                        @error('taluka')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4 mb-2">
                                        <label for="village">Village<span class="text-danger">*</span></label>
                                        <select class="form-control @error('village') is-invalid @enderror" name="village" id="village">
                                            <option value="">Select Village</option>
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
                                        <input type="text" class="form-control" name="phone_no" id="phone_no" placeholder="0832 2347874">
                                    </div>

                                    <div class="form-group mb-2 col-md-4">
                                        <label>Mobile Number<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="mobile_no" id="mobile_no" placeholder="+91 9865478864">
                                    </div>

                                    <div class="form-group mb-2 col-md-4">
                                        <label>Email Address<span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email_id" id="email_id">
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="form-group col-md-4 mb-2">
                                        <label>Citizenship</label>
                                        <input type="text" readonly class="form-control" name="origin_country" id="origin_country" value="Indian" placeholder="Bharat">
                                    </div>

                                    <div class="form-group col-md-4 mb-2">
                                        <label>Education<span class="text-danger">*</span></label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="edu_status" id="edu_status1" value="1">
                                            <label class="form-check-label" for="edu_status1">Educated</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="edu_status" id="edu_status2" value="0">
                                            <label class="form-check-label" for="edu_status2">Non-Educated</label>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4 mb-2">
                                        <label>Is Applicant Below Poverty Line?<span class="text-danger">*</span></label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="is_bpl" id="is_not_bpl" value="0">
                                            <label class="form-check-label" for="is_not_bpl">No</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="is_bpl" id="is_bpl" value="1">
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
                                        <label for="reg_no">RTI Request Registration Number<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="reg_no" id="reg_no" placeholder="Requested Registration Number">
                                        @error('reg_no')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6 mb-2">
                                        <label for="reg_date">RTI Request Filed on (Date)<span class="text-danger">*</span></label>
                                        <input type="date" class="form-control reservationdate" name="reg_date" id="reg_date" data-toggle="datetimepicker" max="{{ date('Y-m-d') }}">
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
                                        <label for="pio_name">PIO Name<span class="text-danger">*</span></label>
                                        <select class="form-control @error('pio_name') is-invalid @enderror" name="pio_name" id="pio_name">
                                        <option value="">Select Pio User</option>
                                        </select>
                                        @error('pio_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6 mb-2">
                                        <label for="pio_address">PIO Address</label>
                                        <select class="form-control @error('pio_address') is-invalid @enderror" name="pio_address" id="pio_address">
                                        <option value="">Select Pio User</option>
                                        </select>
                                        @error('pio_address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <br>
                                <div class="row justify-content-center">
                                    <div class="form-group col-md-6 mb-2">
                                        <label for="pio_response_display">Nature of Response of PIO (Ground for Appeal)<span class="text-danger">*</span></label>
                                        <select name="pio_response_display" class="form-control" id="pio_response_display">
                                            <option value="">Select Department</option>
                                            @foreach ($natureOfResponse as $id => $resp)
                                                <option value="{{ $resp->id }}" {{ old('pio_response_display') == $resp->reason ? 'selected' : '' }}>
                                                    {{ $resp->reason }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6 mb-2">
                                        <label>Copy of RTI Application (Only Pdf)<span class="text-danger">*</span></label>
                                        <input type="file" class="form-control @error('rti_application_doc') is-invalid @enderror" name="rti_application_doc" id="rti_application_doc" placeholder="Choose file" value="{{old('rti_application_doc')}}">
                                        @error('rti_application_doc')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <br>
                                <div class="row justify-content-center">
                                    <div class="form-group col-md-8 mb-2">
                                    <label for="user_text">Requested Text<span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="user_text" id="user_text" style="height: 180px"></textarea>
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
                                        <input type="text" class="form-control" name="fa_reg_no" id="fa_reg_no" placeholder="Requested Registration Number">
                                    </div>
                                    <div class="form-group col-md-6 mb-2">
                                        <label for="fa_reg_date">RTI FA Request Filed on (Date)<span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="fa_reg_date" id="fa_reg_date" data-toggle="datetimepicker">
                                    </div>
                                </div>
                                <br>
                                <div class="row justify-content-center">
                                    <div class="form-group col-md-6 mb-2">
                                        <label for="faa_name">FAA User Name<span class="text-danger">*</span></label>
                                        <select class="form-control @error('faa_name') is-invalid @enderror" name="faa_name" id="faa_name">
                                        <option value="">Select FAA User</option>
                                        </select>
                                        @error('faa_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>
                                    <div class="form-group col-md-6 mb-2">
                                        <label for="faa_address">FAA User Address<span class="text-danger">*</span></label>
                                        <select class="form-control @error('faa_address') is-invalid @enderror" name="faa_address" id="faa_address">
                                        <option value="">Select FAA User</option>
                                        </select>
                                        @error('faa_address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <br>
                                <div class="row justify-content-center">
                                    <div class="form-group col-offset-4 col-md-4 col-offset-4 mb-2">
                                        <label>Copy of Reply of FA (Only Pdf)<span class="text-danger">*</span></label>
                                        <input type="file" class="form-control @error('fa_application_doc') is-invalid @enderror" name="fa_application_doc" id="fa_application_doc" placeholder="Choose file" value="{{old('fa_application_doc')}}">
                                        @error('fa_application_doc')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <br>
                                <div class="row justify-content-center">
                                    <div class="form-group col-md-8 mb-2">
                                        <label for="faa_requested_text">Requested Text<span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="faa_requested_text" id="faa_requested_text" style="height: 140px"></textarea>
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
                                        <span for="sa_document1" style="color: blue; font-size: 12px">(File Type .pdf, .doc, .docx, .txt, .xls, .xlsx, .jpg, .jpeg)</span>
                                        <input type="file" class="form-control @error('sa_document1') is-invalid @enderror" name="sa_document1" id="sa_document1" placeholder="Choose file" value="{{old('sa_document1')}}">
                                        @error('sa_document1')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-5 mb-2">
                                        <label>Supporting Document 2</label>
                                        <span for="sa_document2" style="color: blue; font-size: 12px">(File Type .pdf, .doc, .docx, .txt, .xls, .xlsx, .jpg, .jpeg)</span>
                                        <input type="file" class="form-control @error('sa_document2') is-invalid @enderror" name="sa_document2" id="sa_document2" placeholder="Choose file" value="{{old('sa_document2')}}">
                                        @error('sa_document2')
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
                                            <!-- <option value="1" {{ old('id_proof') == '1' ? 'selected' : '' }}>Aadhar Card</option> -->
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
                                        <span for="id_proof_file" style="color: blue; font-size: 12px">(File Type .pdf, .jpg, .jpeg)</span>
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
                            <h5 class="card-header">Declaration</h5>
                            <div class="card-body">
                                <div class="row justify-content-center">
                                    <div class="form-group col-md-7 mb-2">
                                        <input type="checkbox" id="declaration" name="declaration" class="custom-control-input" onclick="enableSubmit()">
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
                    <button type="submit" id="submit_button" name="submit_button" class="btn btn-dark btn-block" disabled>Submit</button>
                </div>
                </form>
                <br>
            </div>
        </div>
    </div>
    <br>

    </body>

    <script type="text/javascript">

    function enableSubmit()
    {
        let declarationCheckBox = document.querySelector('#declaration');
        let submit_button = document.querySelector('#submit_button');

        // console.log('Checkbox checked: ' + declarationCheckBox.checked);

        if (declarationCheckBox.checked) {
            console.log('Enabling the submit button');
            submit_button.disabled = false; // Enable the submit button
        } else {
            console.log('Disabling the submit button');
            submit_button.disabled = true;  // Disable the submit button
        }
    }

    $(document).ready(function()
    {
        $('select[name="applied_dept"]').on('change', function() {
            populateSubDeptDropdown();
        });

        // Event listener for District dropdown change
        $('select[name="applied_subdept"]').on('change', function() {
            populateOfficeDropdown();
        });

        populateSubDeptDropdown();
        populateDistrictDropdown();

        // Event listener for State dropdown change
        $('select[name="state"]').on('change', function() {
            populateDistrictDropdown();
        });

        // Event listener for District dropdown change
        $('select[name="district"]').on('change', function() {
            populateTalukaDropdown();
        });

        $('select[name="taluka"]').on('change', function() {
            populateVillageDropdown();
        });

        $('select[name="applied_subdept"]').on('change', function() {
            populatePioDropdown();
            populateFaaDropdown();
        });

        // Function to populate Sub Department dropdown
        function populateSubDeptDropdown() {
            var deptId = $('select[name="applied_dept"]').val();
            if (deptId) {
                $.ajax({
                    url: '/subDeptDropdown/' + deptId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        // alert(JSON.stringify(data));
                        var oldSubDept = '{{ old("applied_subdept") }}';
                        $('select[name="applied_subdept"]').empty();
                        $.each(data, function(key, value) {
                            var option = $('<option value="' + value.office_code + '">' + value.department + '</option>');
                            // Check if the option matches the old input value
                            if (value.office_code == oldSubDept) {
                                option.prop('selected', true);
                            }

                            $('select[name="applied_subdept"]').append(option);
                        });
                        populateOfficeDropdown();
                    }
                });
            } else {
                var subDeptSelect = $('select[name="applied_subdept"]');
                subDeptSelect.empty().append('<option value="">Select Office</option>');
            }
        }

        function populatePioDropdown()
        {
            var deptId = $('select[name="applied_dept"]').val();
            var subDeptId = $('select[name="applied_subdept"]').val();

            $.ajax({
                url: '/pioDropdown/' + deptId +'/'+ subDeptId,
                type: "GET",
                dataType: "json",
                success: function(data)
                {
                    var oldPioname = '{{ old("pio_name") }}';
                    $('select[name="pio_name"]').empty();
                    $('select[name="pio_address"]').empty();

                    $.each(data, function(key, value)
                    {
                        var option = $('<option value="' + value.id + '">' + value.name + '</option>');
                        var addoption = $('<option value="' + value.id + '">' + value.address1 + '</option>');
                        // Check if the option matches the old input value
                        if (value.name == oldPioname) {
                            option.prop('selected', true);
                        }

                        $('select[name="pio_name"]').append(option);
                        $('select[name="pio_address"]').append(addoption);
                    });
                }
            });
        }


        function populateFaaDropdown()
        {
            var deptId = $('select[name="applied_dept"]').val();
            var subDeptId = $('select[name="applied_subdept"]').val();

            $.ajax({
                url: '/faaDropdown/' + deptId +'/'+ subDeptId,
                type: "GET",
                dataType: "json",
                success: function(data)
                {
                    var oldFaaname = '{{ old("faa_name") }}';

                    $('select[name="faa_name"]').empty();
                    $('select[name="faa_address"]').empty();
                    $.each(data, function(key, value)
                    {
                        var option = $('<option value="' + value.id + '">' + value.name + '</option>');
                        var addoption = $('<option value="' + value.id + '">' + value.address1 + '</option>');
                        // Check if the option matches the old input value
                        if (value.name == oldFaaname) {
                            option.prop('selected', true);
                        }

                        $('select[name="faa_name"]').append(option);
                        $('select[name="faa_address"]').append(addoption);
                    });
                }
            });
        }

        // Function to populate Sub Department dropdown
        function populateOfficeDropdown() {
            var deptId = $('select[name="applied_dept"]').val();
            var subDeptId = $('select[name="applied_subdept"]').val();
            if (subDeptId) {
                $.ajax({
                    url: '/officeDropdown/' + deptId +'/'+ subDeptId,
                    type: "GET",
                    dataType: "json",

                    success: function(data) {
                        // alert(JSON.stringify(data));
                        var oldOffice = '{{ old("applied_office") }}';
                        $('select[name="applied_office"]').empty();
                        $.each(data, function(key, value) {
                            var option = $('<option value="' + value.suboffice_code + '">' + value.department + '</option>');
                            // Check if the option matches the old input value
                            if (value.suboffice_code == oldOffice) {
                                option.prop('selected', true);
                            }

                            $('select[name="applied_office"]').append(option);
                        });
                        populatePioDropdown();
                        populateFaaDropdown();
                    }
                });
            } else {
                $('select[name="applied_office"]').append('<option value="0">Sub Office</option>');
            }
        }

        // Function to populate District dropdown
        function populateDistrictDropdown() {
            var stateId = $('select[name="state"]').val();
            if (stateId == 0)
            {
                var district = $('select[name="district"]');
                district.empty().append('<option value="0">Others</option>');
                populateTalukaDropdown();
            }
            else if (stateId) {
                $.ajax({
                    url: '/districtDropdown/' + stateId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        var oldDistrict = '{{ old("district") }}';
                        $('select[name="district"]').empty();
                        $.each(data, function(key, value) {
                            var option = $('<option value="' + value.id + '">' + value.name + '</option>');

                            if(value.id == oldDistrict)
                            {
                                option.prop('selected', true);
                            }
                            $('select[name="district"]').append(option);
                        });
                        populateTalukaDropdown();
                    }
                });
            } else {
                var district = $('select[name="district"]');
                district.empty().append('<option value="">Select District</option>');
            }
        }

        // Function to populate Taluka dropdown
        function populateTalukaDropdown() {
            var districtId = $('select[name="district"]').val();
            if (districtId == 0)
            {
                var taluka = $('select[name="taluka"]');
                taluka.empty().append('<option value="0">Others</option>');
                populateVillageDropdown();
            }
            else if (districtId) {
                $.ajax({
                    url: '/talukaDropdown/' + districtId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        var oldTaluka = '{{ old("taluka") }}';
                        $('select[name="taluka"]').empty();
                        $.each(data, function(key, value) {
                            var option = $('<option value="' + value.id + '">' + value.name + '</option>');
                            if(value.id == oldTaluka)
                            {
                                option.prop('selected', true);
                            }
                            $('select[name="taluka"]').append(option);
                        });
                        populateVillageDropdown();
                    }
                });
            } else {
                $('select[name="taluka"]').empty();
            }
        }

        // Function to populate Village dropdown
        function populateVillageDropdown() {
            var talukaId = $('select[name="taluka"]').val();
            if (talukaId == 0)
            {
                var village = $('select[name="village"]');
                village.empty().append('<option value="0">Others</option>');
            }
            else if (talukaId) {
                $.ajax({
                    url: '/villageDropdown/' + talukaId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        var oldVillage = '{{ old("village") }}';
                        $('select[name="village"]').empty();
                        $.each(data, function(key, value) {
                            // $('select[name="village"]').append('<option value="' + value.id + '">' + value.name + '</option>');
                            var option = $('<option value="' + value.id + '">' + value.name + '</option>');
                            if(value.id == oldVillage)
                            {
                                option.prop('selected', true);
                            }
                            $('select[name="village"]').append(option);
                        });
                    }
                });
            } else {
                $('select[name="village"]').empty();
            }
        }

        $('#submit_button').click(function(e)
        {
            e.preventDefault();

            $('.invalid-feedback').remove();
            $('.form-control').removeClass('is-invalid');

            var isValid = true;

            if($('#applied_dept').val() === "")
            {
                isValid = false;
                $('#applied_dept').addClass('is-invalid');
                $('#applied_dept').after('<span class="invalid-feedback">Please select Department from the dropdown.</span>');
            }

            if($('#applied_subdept').val() === "")
            {
                isValid = false;
                $('#applied_subdept').addClass('is-invalid');
                $('#applied_subdept').after('<span class="invalid-feedback">Please select Office from the dropdown.</span>');
            }

            if($('#applied_office').val() === "")
            {
                isValid = false;
                $('#applied_office').addClass('is-invalid');
                $('#applied_office').after('<span class="invalid-feedback">Please select Sub Office from the dropdown.</span>');
            }

            if(($('#name').val() === ""))
            {
                isValid = false;
                $('#name').addClass('is-invalid');
                $('#name').after('<span class="invalid-feedback">Please enter Full Name.</span>');
            }

            if(($('#address_line1').val() === ""))
            {
                isValid = false;
                $('#address_line1').addClass('is-invalid');
                $('#address_line1').after('<span class="invalid-feedback">Please enter Address 1.</span>');
            }

            if(($('#address_line2').val() === ""))
            {
                isValid = false;
                $('#address_line2').addClass('is-invalid');
                $('#address_line2').after('<span class="invalid-feedback">Please enter Address 2.</span>');
            }

            if(($('#address_line3').val() === ""))
            {
                isValid = false;
                $('#address_line3').addClass('is-invalid');
                $('#address_line3').after('<span class="invalid-feedback">Please enter Address 3.</span>');
            }

            if(($('#pincode').val() === ""))
            {
                isValid = false;
                $('#pincode').addClass('is-invalid');
                $('#pincode').after('<span class="invalid-feedback">Please enter Pincode.</span>');
            }

            if(($('#mobile_no').val() === ""))
            {
                isValid = false;
                $('#mobile_no').addClass('is-invalid');
                $('#mobile_no').after('<span class="invalid-feedback">Please enter Mobile Number.</span>');
            }

            if(($('#email_id').val() === ""))
            {
                isValid = false;
                $("#email_id").addClass('is-invalid');
                $('#email_id').after('<span class="invalid-feedback">Please enter EmailId.</span>');
            }

            if ($('input[name="edu_status"]:checked').length === 0)
            {
                isValid = false;
                $("input[name='edu_status']").addClass('is-invalid');
                $('input[name="edu_status"]').after('<span class="invalid-feedback">Please select your Education Status.</span>');
            }

            if ($('input[name="is_bpl"]:checked').length === 0)
            {
                isValid = false;
                $("input[name='is_bpl']").addClass('is-invalid');
                $('input[name="is_bpl"]').after('<span class="invalid-feedback">Please select your Financial Status.</span>');
            }

            if(($('#reg_no').val() === ""))
            {
                isValid = false;
                $("#reg_no").addClass('is-invalid');
                $('#reg_no').after('<span class="invalid-feedback">Please enter Registration Number.</span>');
            }

            if(($('#reg_date').val() === ""))
            {
                isValid = false;
                $("#reg_date").addClass('is-invalid');
                $('#reg_date').after('<span class="invalid-feedback">Please enter Registration Date.</span>');
            }

            if ($('#pio_name').val() === "")
            {
                isValid = false;
                $('#pio_name').addClass('is-invalid');
                $('#pio_name').after('<span class="invalid-feedback">Please select a Pio Name from the dropdown.</span>');
            }

            if ($('#pio_adress').val() === "")
            {
                isValid = false;
                $('#pio_adress').addClass('is-invalid');
                $('#pio_adress').after('<span class="invalid-feedback">Please select a Pio Address from the dropdown.</span>');
            }

            if ($('#pio_response_display').val() === "")
            {
                isValid = false;
                $('#pio_response_display').addClass('is-invalid');
                $('#pio_response_display').after('<span class="invalid-feedback">Please select Ground For Appeal for RTI Application.</span>');
            }

            if ($('#user_text').val() === "")
            {
                isValid = false;
                $('#user_text').addClass('is-invalid');
                $('#user_text').after('<span class="invalid-feedback">Please Enter supported text for RTI Application.</span>');
            }

            if($('#fa_reg_no').val() === "")
            {
                isValid = false;
                $('#fa_reg_no').addClass('is-invalid');
                $('#fa_reg_no').after('<span class="invalid-feedback">Please Enter First Appeal Number.</span>');
            }

            if(($('#fa_reg_date').val() === ""))
            {
                isValid = false;
                $("#fa_reg_date").addClass('is-invalid');
                $('#fa_reg_date').after('<span class="invalid-feedback">Please enter First Appeal Request Date.</span>');
            }

            if($('#faa_name').val() === "")
            {
                isValid = false;
                $("#faa_name").addClass('is-invalid');
                $('#faa_name').after('<span class="invalid-feedback">Please Select First Appeal Name.</span>');
            }

            if($('#faa_address').val() === "")
            {
                isValid = false;
                $("#faa_address").addClass('is-invalid');
                $('#faa_address').after('<span class="invalid-feedback">Please Select First Appeal User Address.</span>');
            }

            if($('#faa_requested_text').val() === "")
            {
                isValid = false;
                $("#faa_requested_text").addClass('is-invalid');
                $('#faa_requested_text').after('<span class="invalid-feedback">Please Enter Requested Text.</span>');
            }

            if ($('#faa_resp_dropdown').val() === "")
            {
                isValid = false;
                $('#faa_resp_dropdown').addClass('is-invalid');
                $('#faa_resp_dropdown').after('<span class="invalid-feedback">Please select a response from the dropdown.</span>');
            }

            var rtiReplyDocument = $('#rti_application_doc')[0].files[0];
            if (!rtiReplyDocument)
            {
                isValid = false;
                $('#rti_application_doc').addClass('is-invalid');
                $('#rti_application_doc').after('<span class="invalid-feedback">This field is required.</span>');
            }
            else
            {
                var fileSize = rtiReplyDocument.size / 1024 / 1024; // in MB
                var fileExtension = rtiReplyDocument.name.split('.').pop().toLowerCase();

                if (fileExtension !== 'pdf')
                {
                    isValid = false;
                    $('#rti_application_doc').addClass('is-invalid');
                    $('#rti_application_doc').after('<span class="invalid-feedback">Only PDF files are allowed.</span>');
                }

                if (fileSize > 5)
                {
                    isValid = false;
                    $('#rti_application_doc').addClass('is-invalid');
                    $('#rti_application_doc').after('<span class="invalid-feedback">File size should not exceed 5MB.</span>');
                }
            }

            var faaReplyDocument = $('#fa_application_doc')[0].files[0];
            if (!faaReplyDocument)
            {
                isValid = false;
                $('#fa_application_doc').addClass('is-invalid');
                $('#fa_application_doc').after('<span class="invalid-feedback">This field is required.</span>');
            }
            else
            {
                var fileSize = faaReplyDocument.size / 1024 / 1024; // in MB
                var fileExtension = faaReplyDocument.name.split('.').pop().toLowerCase();

                if (fileExtension !== 'pdf')
                {
                    isValid = false;
                    $('#fa_application_doc').addClass('is-invalid');
                    $('#fa_application_doc').after('<span class="invalid-feedback">Only PDF files are allowed.</span>');
                }

                if (fileSize > 5)
                {
                    isValid = false;
                    $('#fa_application_doc').addClass('is-invalid');
                    $('#fa_application_doc').after('<span class="invalid-feedback">File size should not exceed 5MB.</span>');
                }
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

                if (fileSize > 5)
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