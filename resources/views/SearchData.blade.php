{{-- ============================================================
     Request Details (officer)  -  DBIM / GIGW 3.0
     Uses layouts/app.blade.php for <head>, header, <main>, footer.
     $requestType: 'New Request' or First Appeal; $regNo is built below.
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Request Details | RTI Online, Government of Goa')

@section('content')
@php
    $regNo = $requestType == 'New Request' ? $requestData->reg_no : $requestData->fa_reg_no;
@endphp

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12">

            @include('partials.flash-messages')

            {{-- ============ Request details ============ --}}
            <div class="card shadow-sm dbim-detail mb-4">
                <h1 class="card-header h4 mb-0">Request Details</h1>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Registration No:</dt>
                                <dd class="col-sm-8">{{ $regNo }}</dd>

                                <dt class="col-sm-4">Applicant Name:</dt>
                                <dd class="col-sm-8">{{ $requestData->name }}</dd>

                                <dt class="col-sm-4">Address:</dt>
                                <dd class="col-sm-8">
                                    {{ $requestData->address_line1 }}<br>
                                    {{ $requestData->address_line2 }}<br>
                                    {{ $requestData->address_line3 }}
                                </dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">e_Filling Date:</dt>
                                <dd class="col-sm-8">{{ \Carbon\Carbon::parse($requestData->created_at)->format('d-m-Y H:i') }}</dd>

                                <dt class="col-sm-4">Mobile:</dt>
                                <dd class="col-sm-8">{{ $requestData->mobile_no }}</dd>

                                <dt class="col-sm-4">Email:</dt>
                                <dd class="col-sm-8">{{ $requestData->email_id }}</dd>

                                <dt class="col-sm-4">Pincode:</dt>
                                <dd class="col-sm-8">{{ $requestData->pincode }}</dd>
                            </dl>
                        </div>
                    </div>

                    <hr>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Department:</dt>
                                <dd class="col-sm-8">
                                    {{ $departmentDetails1 }}<br>
                                    {{ $departmentDetails2 }}<br>
                                    {{ $departmentDetails3 }}
                                </dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Pending with Name (Username):</dt>
                                <dd class="col-sm-8">{{ $requestData->deptusername }} ({{ $requestData->username }})</dd>
                            </dl>
                        </div>
                    </div>

                    <hr>

                    <h2 class="h3 dbim-detail__heading">Actions:</h2>
                    <div class="table-responsive">
                        <table class="table table-light table-bordered">
                            <caption class="visually-hidden">Actions taken on this request</caption>
                            <thead>
                                <tr>
                                    <th scope="col">Sr. No</th>
                                    <th scope="col">Action Taken</th>
                                    <th scope="col">Action Date</th>
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
                </div>
            </div>

            {{-- ============ Print ============ --}}
            <div class="card shadow-sm mb-4">
                <h2 class="card-header h4 mb-0">Print Details:</h2>
                <div class="card-body">
                    <a href="{{ url('/search-pdf/' . base64_encode($regNo)) }}" class="download-outline-btn w-ft mt-0"
                       target="_blank" rel="noopener">
                        Print As PDF<span class="visually-hidden"> (opens in a new tab)</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection