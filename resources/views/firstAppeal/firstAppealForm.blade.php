<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>First Appeal Form Submission</title>

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- <script src="https://www.google.com/recaptcha/api.js" async defer></script> -->

</head>

<style>

    .container.mt-5
    {
        /* border: 2px solid black; */
        border-bottom: none;
        /* border-top:none; */
        margin-top:0 !important;
    }
</style>

<body>
@include('UsersView.header')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <br>

                <form method="post" enctype="multipart/form-data" action="{{ route('firstAppealSave') }}" novalidate>
                @csrf
                    <div class="row">
                        <br>
                        <br>
                        <br>
                        <h3>First Appeal Request</h3>
                        <div class="col-md-12">
                            <div class="card">
                                <h5 class="card-header">Public Authority Details:</h5>
                                <div class="card-body">
                                    <div class="row justify-content-center">

                                        <div class="form-group col-md-4 mb-2 " style="pointer-events: none;">
                                            <label>Select Department / Public Authority</label>
                                            <select name="applied_dept" class="form-control" id="applied_dept" readonly>
                                                <option value="{{ $data['deptDropdownKeys'][0] }}"> {{ $data['deptDropdownValues'][0] }}</option>
                                            </select>
                                            @error('applied_dept')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-4 mb-2" style="pointer-events: none;">
                                            <label for="applied_subdept">Select Office</label>
                                            <select name="applied_subdept" class="form-control" id="applied_subdept" readonly>
                                            <option value="{{ $data['deptDropdownKeys'][1] }}"> {{ $data['deptDropdownValues'][1] }}</option>
                                            </select>
                                            @error('applied_subdept')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-4 mb-2" style="pointer-events: none;">
                                            <label for="applied_office">Select Sub Office</label>
                                            <select name="applied_office" class="form-control" id="applied_office" readonly>
                                            <option value="{{ $data['deptDropdownKeys'][2] }}"> {{ $data['deptDropdownValues'][2] }}</option>
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
                                            <label for="name">Applicant Name</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ isset($data['submitRequest']->name) ? $data['submitRequest']->name : ''}}" placeholder="Firstname Lastname" readonly>
                                            @error('name')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6 mb-2" style="pointer-events: none;">
                                            <label for="gender">Gender</label>
                                            <select class="form-control @error('gender') is-invalid @enderror" name="gender" id="gender" readonly>
                                                <option value="male"
                                                    @selected(isset($data['submitRequest']->gender) && $data['submitRequest']->gender == 'male')>
                                                    Male
                                                </option>

                                                <option value="female"
                                                    @selected(isset($data['submitRequest']->gender) && $data['submitRequest']->gender == 'female')>
                                                    Female
                                                </option>

                                                <option value="other"
                                                    @selected(isset($data['submitRequest']->gender) && $data['submitRequest']->gender == 'other')>
                                                    Other
                                                </option>
                                            </select>
                                            @error('gender')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row justify-content-center">
                                        <label for="address_line1">Address</label>
                                        <div class="form-group col-md-4 mb-2">
                                            <input type="text" class="form-control @error('address_line1') is-invalid @enderror" name="address_line1" id="address_line1" value="{{ isset($data['submitRequest']->address_line1) ? $data['submitRequest']->address_line1 : ''}}" placeholder="Address Line 1" readonly>
                                            @error('address_line1')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-4 mb-2">
                                            <input type="text" class="form-control @error('address_line2') is-invalid @enderror" name="address_line2" id="address_line2" value="{{ isset($data['submitRequest']->address_line2) ? $data['submitRequest']->address_line2 : ''}}" placeholder="Address Line 2" readonly>
                                            @error('address_line2')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-4 mb-2">
                                            <input type="text" class="form-control @error('address_line3') is-invalid @enderror" name="address_line3" id="address_line3" value="{{ isset($data['submitRequest']->address_line3) ? $data['submitRequest']->address_line3 : ''}}" placeholder="Address Line 2" readonly>
                                            @error('address_line3')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row justify-content-center">
                                        <div class="form-group col-md-4 mb-2">
                                            <label for="pincode">Pincode</label>
                                            <input type="text" class="form-control @error('pincode') is-invalid @enderror" name="pincode" id="pincode" value="{{ isset($data['submitRequest']->pincode) ? $data['submitRequest']->pincode : ''}}" placeholder="Pin Code" readonly>
                                            @error('pincode')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-4 mb-2" style="pointer-events: none;">
                                            <label for="country">Country</label>
                                            <!-- <input type="text" class="form-control @error('country') is-invalid @enderror" name="country" id="country" value="India"> -->
                                            <select class="form-control @error('country') is-invalid @enderror" name="country" id="country" readonly>
                                                <option value="1">India</option>
                                            </select>
                                            @error('country')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-4 mb-2" style="pointer-events: none;">
                                            <label for="state">State</label>
                                            <select name="state" class="form-control" id="state" readonly>
                                                <option value="{{$data['stateData']->id}}">{{$data['stateData']->name}}</option>
                                            </select>

                                            @error('state')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
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
                                            <input type="text" class="form-control @error('phone_no') is-invalid @enderror" name="phone_no" id="phone_no" value="{{ isset($submitRequest->phone_no) ? $submitRequest->phone_no : ''}}" placeholder="0832 2347874" readonly>
                                            @error('phone_no')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group mb-2 col-md-4">
                                            <label>Mobile Number</label>
                                            <input type="text" class="form-control @error('mobile_no') is-invalid @enderror" name="mobile_no" id="mobile_no" value="{{ isset($data['submitRequest']->mobile_no) ? $data['submitRequest']->mobile_no : ''}}" placeholder="+91 9865478864" readonly>
                                            @error('mobile_no')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group mb-2 col-md-4">
                                            <label>Email Address</label>
                                            <input type="email" class="form-control @error('email_id') is-invalid @enderror" name="email_id" id="email_id" value="{{ isset($data['submitRequest']->email_id) ? $data['submitRequest']->email_id : ''}}" readonly>
                                            @error('email_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row justify-content-center">
                                        <!-- <div class="form-group col-md-4 mb-2">
                                            <label>Status</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="status" id="status1" value="rural" {{ old('status') == 'rural' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="status1">Rural</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="status" id="status2" value="urban" {{ old('status') == 'urban' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="status2">Urban</label>
                                            </div>
                                            @error('status')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div> -->

                                        <div class="form-group col-md-4 mb-2">
                                        {{$data['submitRequest']->edu_status}}


                                            <label>Citizenship</label>
                                            <input type="text" readonly class="form-control @error('origin_country') is-invalid @enderror" name="origin_country" id="origin_country" value="Indian" placeholder="Bharat">
                                            @error('origin_country')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-4 mb-2" style="pointer-events: none;">
                                            <label>Education</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="edu_status" id="edu_status1" value="1" {{ isset($data['submitRequest']->edu_status) && $data['submitRequest']->edu_status == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="edu_status1">Educated</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="edu_status" id="edu_status2" value="0" {{ isset($data['submitRequest']->edu_status) && $data['submitRequest']->edu_status == 0 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="edu_status2">Non-Educated</label>
                                            </div>
                                            @error('edu_status')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>


                                        <div class="form-group col-md-4 mb-2" style="pointer-events: none;">
                                            <label>Is Applicant Below Poverty Line?</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="is_bpl" id="is_not_bpl" value="0" {{ $data['submitRequest']->is_bpl == 0 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_not_bpl">No</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="is_bpl" id="is_bpl" value="1" {{ $data['submitRequest']->is_bpl == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_bpl">Yes</label>
                                            </div>
                                            @error('is_bpl')
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
                                <h5 class="card-header">Details of RTI Application</h5>
                                <div class="card-body">
                                    <div class="row justify-content-center">
                                        <div class="form-group col-md-6 mb-2">
                                            <label for="reg_no">RTI Request Registration Number</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="reg_no" id="reg_no" value="{{ isset($data['submitRequest']->reg_no) ? $data['submitRequest']->reg_no : ''}}" placeholder="Requested Registration Number" readonly>
                                            @error('reg_no')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6 mb-2">
                                            <label for="reg_date">RTI Request Filed on (Date)</label>
                                            <input type="text" class="form-control reservationdate @error('reg_date') is-invalid @enderror" name="reg_date" id="reg_date" value="{{ isset($data['submitRequest']->created_at) ? $data['submitRequest']->created_at->format('d/m/Y') : ''}}" data-toggle="datetimepicker" disabled>
                                            @error('reg_date')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row justify-content-center">
                                        <div class="form-group col-md-4 mb-2">
                                            <label>Copy of the RTI Application (Only Pdf)</label><br>
                                            <a href="{{ asset($data['filePath']) }}" target="_blank">{{ $data['submitRequest']->document_name_original }}</a>

                                            @error('applicant_upload')
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

                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="card">
                                <h5 class="card-header">PIO Details</h5>
                                <div class="card-body">
                                    <div class="row justify-content-center">
                                        <div class="form-group col-md-4 mb-2">
                                            <label for="pioname">PIO Name<span class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('pioname') is-invalid @enderror"
                                                name="pioname"
                                                id="pioname"
                                                value="{{ $data['pioDetails']->name ?? '' }}"
                                                placeholder="PIO Name" >

                                            @error('pioname')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-4 mb-2">
                                            <label for="pioaddr">PIO Address<span class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('pioaddr') is-invalid @enderror"
                                                name="pioaddr"
                                                id="pioaddr"
                                                value="{{ $data['pioDetails']->address1 ?? '' }}"
                                                placeholder="PIO Address"
                                                required>

                                            @error('pioaddr')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <br>

                            <div class="card">
                                <h5 class="card-header">PIO Response</h5>
                                <div class="card-body">
                                    <div class="row justify-content-center">
                                        <div class="form-group col-md-5 mb-2">
                                            <label>Nature of Response of PIO (Ground for Appeal)<span class="text-danger">*</span></label>
                                            <select class="form-control @error('pio_resp_dropdown') is-invalid @enderror" name="pio_resp_dropdown" id="pio_resp_dropdown">
                                                <option value="">Select Response</option>
                                                <option value="1" {{ old('pio_resp_dropdown') == '1' ? 'selected' : '' }}>Refused access to the Information Requested</option>
                                                <option value="2" {{ old('pio_resp_dropdown') == '2' ? 'selected' : '' }}>No Response within the Time Limit</option>
                                                <option value="3" {{ old('pio_resp_dropdown') == '3' ? 'selected' : '' }}>Unreasonable Amount of Fee is Required to Pay</option>
                                                <option value="4" {{ old('pio_resp_dropdown') == '4' ? 'selected' : '' }}>Provided Incomplete, Misleading or False Information</option>
                                                <option value="5" {{ old('pio_resp_dropdown') == '5' ? 'selected' : '' }}>Any Other Ground</option>
                                            </select>
                                            @error('pio_resp_dropdown')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-5 mb-2">
                                            <label>Copy of Reply of PIO (Only Pdf)</label>
                                            <input type="file" class="form-control @error('pio_reply_document') is-invalid @enderror" name="pio_reply_document" id="pio_reply_document" placeholder="Choose file" value="{{old('pio_reply_document')}}">
                                            @error('pio_reply_document')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-4 mb-2"></div>
                                    </div>
                                </div>
                            </div>

                            <br>
                            <div class="card">
                                <h5 class="card-header">(Enter Text for RTI Request application upto 3000 words)</h5>
                                <div class="card-body">
                                    <div class="row justify-content-center">
                                        <div class="form-group col-md-10 mb-2">
                                            <label>Text for RTI first appeal application<span class="text-danger">*</span></label>
                                            <br>
                                            <span style="font-size: 12px; color: blue;" for="">Only alphabets A-Z a-z number 0-9 and special characters , . - _ ( ) / @ : & \ % are allowed in Text for RTI Request application</span>
                                            <textarea style="height: 100px" value="{{ old('request_text')}}" class="form-control @error('request_text') is-invalid @enderror" maxlength="3000" name="request_text" id="request_text">{{isset($submitRequest->request_text)}}</textarea>
                                            @error('request_text')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <br>

                            <div class="card">
                                <h5 class="card-header">Supporting Document</h5>
                                <div class="card-body">
                                    <div class="row justify-content-center">
                                        <div class="form-group col-md-4 mb-2">
                                            <label>Document Name</label>
                                            <input type="text" class="form-control" id="doc_name" name="doc_name" placeholder="Enter Text" value="{{old('doc_name')}}">
                                        </div>
                                        <div class="form-group col-md-4 mb-2">
                                            <label>Upload Supporting Document - Optional</label>
                                            <input type="file" value="{{old('document')}}" class="form-control @error('document') is-invalid @enderror" name="document" id="document" placeholder="Choose file">
                                            @error('document')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>

                            <div class="card">
                                <div class="card-body" {{isset($firstAppealRequest) ? "pointer-events: none;" : ''}}>
                                    <div class="row justify-content-center">
                                    <x-captcha />
                                        {{-- <div class="form-group col-md-4 mb-2">
                                            <div class="row justify-content-center align-items-center">
                                                <div class="g-recaptcha @error('g-recaptcha-response') is-invalid @enderror" data-sitekey="{{ env('GOOGLE_SITE_KEY') }}"></div>
                                                @error('g-recaptcha-response')
                                                    <span class="text-danger" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                                <br>
                                            </div>
                                            <br>
                                        </div>
                                        <br> --}}
                                        <button type="submit" class="btn btn-dark btn-block">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
        <br>
    </div>
@include('UsersView.footer')
</body>


<script type="text/javascript">

$(function() {
    $(".reservationdate").datepicker({
        dateFormat: 'dd-mm-yy'
    });
});

$(document).ready(function() {

    // populateSubDeptDropdown();
    // populateDistrictDropdown();
    // $('.hiddenCardDiv').hide();

    // // Event listener for State dropdown change
    // $('select[name="state"]').on('change', function() {
    //     populateDistrictDropdown();
    // });

    // // Event listener for District dropdown change
    // $('select[name="district"]').on('change', function() {
    //     populateTalukaDropdown();
    // });

    // $('select[name="taluka"]').on('change', function() {
    //     populateVillageDropdown();
    // });

    // Function to populate District dropdown
    function populateDistrictDropdown() {
        var stateId = $('select[name="state"]').val();
        if (stateId) {
            $.ajax({
                url: '/districtDropdown/' + stateId,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    var oldDistrict = '{{ old("district") }}';
                    $('select[name="district"]').empty();
                    $.each(data, function(key, value) {
                        var option = $('select[name="district"]').append('<option value="' + value.id + '">' + value.name + '</option>');

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
        if (districtId) {
            $.ajax({
                url: '/talukaDropdown/' + districtId,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('select[name="taluka"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="taluka"]').append('<option value="' + value.id + '">' + value.name + '</option>');
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
        if (talukaId) {
            $.ajax({
                url: '/villageDropdown/' + talukaId,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('select[name="village"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="village"]').append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            });
        } else {
            $('select[name="village"]').empty();
        }
    }

    function populateDeptDropdown()
    {
        $(this).val();
    }



    $('select[name="applied_dept"]').on('change', function() {
        populateSubDeptDropdown();
    });

    // Event listener for District dropdown change
    $('select[name="applied_subdept"]').on('change', function() {
        populateOfficeDropdown();
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
                        var option = $('<option selected value="' + value.office_code + '">' + value.department + '</option>');
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
                }
            });
        } else {
            $('select[name="applied_office"]').append('<option value="0">Sub Office</option>');
        }
    }


});

</script>
</html>
