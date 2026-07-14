<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>First Form Submission</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="../../public/style.css"> -->
</head>
<body>
@include('UsersView.header')
    <div class="container mt-5">
        @if(Session::has('success'))
            <div class="alert alert-success text-center">
                {{Session::get('success')}}
            </div>
        @endif
        <form  method="post" action="{{ route('validate.form') }}" novalidate>
            @csrf
            <div class="form-group mb-2">
                <label>Applied Dept</label>
                <select class="form-control @error('applied_dept') is-invalid @enderror" name="applied_dept" id="applied_dept">
                    <option value="0">Select Dept</option>
                    <option value="Transport Dept Goa" {{ old('applied_dept') === 'Transport Dept Goa' ? 'selected' : '' }}>Transport Dept Goa</option>
                    <option value="Agriculture Dept Goa" {{ old('applied_dept') === 'Agriculture Dept Goa' ? 'selected' : '' }}>Agriculture Dept Goa</option>
                </select>
                @error('applied_dept')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="row">
                <div class="form-group col-md-6 mb-2">
                    <label for="name">Applicant Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name') }}">
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group col-md-6 mb-2">
                    <label for="gender">Gender</label>
                    <select class="form-control @error('gender') is-invalid @enderror" name="gender" id="gender">
                    <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')
                    <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="row">
                <label>Address</label>
                <div class="form-group col-md-4 mb-2">
                    <input type="text" class="form-control @error('address_line1') is-invalid @enderror" name="address_line1" id="address_line1" placeholder="H.no 653, Shanta Colony" value="{{ old('address_line1') }}">
                    @error('address_line1')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group col-md-4 mb-2">
                    <input type="text" class="form-control @error('address_line2') is-invalid @enderror" name="address_line2" id="address_line2" placeholder="Near High Court" value="{{ old('address_line2') }}">
                    @error('address_line2')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group col-md-4 mb-2">
                    <input type="text" class="form-control @error('address_line3') is-invalid @enderror" name="address_line3" id="address_line3" placeholder="Porvori" value="{{ old('address_line3') }}">
                    @error('address_line3')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-3 mb-2">
                    <label>Pincode</label>
                    <input type="text" class="form-control @error('pincode') is-invalid @enderror" name="pincode" id="pincode" placeholder="845796" value="{{ old('pincode') }}">
                    @error('pincode')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group col-md-3 mb-2">
                    <label>Country</label>
                    <input type="text" class="form-control @error('country') is-invalid @enderror" name="country" id="country" placeholder="Bharat" value="India">
                    @error('country')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group col-md-3 mb-2">
                    <label>State</label>
                    <input type="text" class="form-control @error('state') is-invalid @enderror" name="state" id="state" placeholder="Goa" value="Goa">
                    @error('state')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group mb-2 col-md-3">
                    <label>District</label>
                    <select class="form-control @error('district') is-invalid @enderror" name="district" id="district">
                        <option value="north_goa" {{ old('district') === 'north_goa' ? 'selected' : '' }}>North Goa</option>
                        <option value="south_goa" {{ old('district') === 'south_goa' ? 'selected' : '' }}>South Goa</option>
                    </select>
                    @error('district')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="form-group mb-2 col-md-3">
                    <label>Phone</label>
                    <input type="text" class="form-control @error('phone_no') is-invalid @enderror" name="phone_no" id="phone_no" value="{{ old('phone_no') }}">
                    @error('phone_no')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group mb-2 col-md-3">
                    <label>Mobile</label>
                    <input type="text" class="form-control @error('mobile_no') is-invalid @enderror" name="mobile_no" id="mobile_no" value="{{ old('mobile_no') }}">
                    @error('mobile_no')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group mb-2 col-md-3">
                    <label>Email</label>
                    <input type="email" class="form-control @error('email_id') is-invalid @enderror" name="email_id" id="email_id" value="{{ old('email_id') }}">
                    @error('email_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group mb-2 col-md-3">
                    <label>Country of Origin</label>
                    <input type="text" class="form-control @error('is_Indian') is-invalid @enderror" name="is_Indian" id="is_Indian" value="Indian" placeholder="Bharat">
                    @error('is_Indian')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-4 mb-2">
                    <label>Education</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="edu_status" id="edu_status" value="educated" {{ old('edu_status') == 'educated' ? 'checked' : '' }}>
                        <label class="form-check-label" for="educated">Educated</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="edu_status" id="edu_status" value="non_educated" {{ old('edu_status') == 'non_educated' ? 'checked' : '' }}>
                        <label class="form-check-label" for="non_educated">Non-Educated</label>
                    </div>
                    @error('edu_status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group col-md-4 mb-2">
                    <label>Below Poverty Line?</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="is_bpl" id="is_bpl" value="educated" {{ old('is_bpl') == 'Yes' ? 'checked' : '' }}>
                        <label class="form-check-label" for="yes">Yes</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="is_bpl" id="is_bpl" value="non_educated" {{ old('is_bpl') == 'No' ? 'checked' : '' }}>
                        <label class="form-check-label" for="no">No</label>
                    </div>
                    @error('is_bpl')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group mb-2">
                <label>Card Number</label>
                <input type="text" class="form-control @error('card_no') is-invalid @enderror" name="card_no" id="card_no" value="{{ old('card_no') }}">
                @error('card_no')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group mb-2">
                <label>Issuing Authority</label>
                <select class="form-control @error('issuing_authority') is-invalid @enderror" name="issuing_authority" id="issuing_authority">
                    <option value="0">Select Dept</option>
                    <option value="Transport Dept Goa" {{ old('issuing_authority') === 'Transport Dept Goa' ? 'selected' : '' }}>Transport Dept Goa</option>
                    <option value="Agriculture Dept Goa" {{ old('issuing_authority') === 'Agriculture Dept Goa' ? 'selected' : '' }}>Agriculture Dept Goa</option>
                </select>
                @error('issuing_authority')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mb-2">
                <label>Comments</label>
                <input type="text" class="form-control @error('request_text') is-invalid @enderror" name="request_text" id="request_text" value="{{ old('request_text') }}">
                @error('request_text')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="row">
                <div class="form-group mb-2 col-md-4">
                    <label>Issued Year</label>
                    <input type="text" class="form-control @error('issued_year') is-invalid @enderror" name="issued_year" id="issued_year" value="{{ old('issued_year') }}">
                    @error('issued_year')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group mb-2 col-md-4">
                    <label>Document</label>
                    <input type="text" class="form-control @error('document') is-invalid @enderror" name="document" id="document" value="{{ old('document') }}">
                    @error('document')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="d-grid mt-3">
              <input type="submit" name="send" value="Submit" class="btn btn-dark btn-block">
            </div>
        </form>
    </div>
</body>
</html>
