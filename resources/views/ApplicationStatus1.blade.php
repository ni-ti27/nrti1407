<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Requests Status</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">

    <style>
        .container.mt-5 {
            border-top: none;
            margin-top: 0 !important;
        }

        .table-responsive {
            display: none;
        }

        .radio-label {
            font-weight: 500;
            cursor: pointer;
        }
    </style>
</head>

<body>

@include('UsersView.header')

<div class="container mt-5">

    <br>

    <h2 style="margin-left:10px">Application Status</h2>

    <br>

    <!-- ================= RADIO BUTTONS ================= -->

    <div class="row justify-content-center">

        <!-- NR -->
        <div class="col-md-3">
            <label class="radio-label">
                <input type="radio"
                       name="divSelector"
                       value="display1"
                       onclick="showDiv('display1')"
                       {{ $divSelector == 'display1' ? 'checked' : '' }}>

                View New Request Applications
            </label>
        </div>

        <!-- FA -->
        <div class="col-md-3">
            <label class="radio-label">
                <input type="radio"
                       name="divSelector"
                       value="display2"
                       onclick="showDiv('display2')"
                       {{ $divSelector == 'display2' ? 'checked' : '' }}>

                View First Appeal Applications
            </label>
        </div>

        <!-- SA -->
        <div class="col-md-3">
            <label class="radio-label">
                <input type="radio"
                       name="divSelector"
                       value="display3"
                       onclick="showDiv('display3')"
                       {{ $divSelector == 'display3' ? 'checked' : '' }}>

                View Second Appeal Applications
            </label>
        </div>

    </div>

    <br><br>

    <!-- ================= SEARCH FORM ================= -->

    <div class="row justify-content-center mb-3">
        <div class="col-md-6">

            <form method="GET" action="{{ route('applicant.applicationStatus') }}">

                <div class="input-group">

                    <input type="text"
                           class="form-control"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Registration Number / Applicant Name / Department Name">

                    <!-- ACTIVE TAB -->
                    <input type="hidden"
                           id="hiddenDivSelector"
                           name="divSelector"
                           value="{{ $divSelector }}">

                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            Search
                        </button>
                    </div>

                </div>

            </form>

        </div>
    </div>

    <br><br>

    <!-- ========================================================= -->
    <!-- ================= NEW REQUEST TABLE ===================== -->
    <!-- ========================================================= -->

    <div class="table-responsive"
         id="display1"
         style="{{ $divSelector == 'display1' ? 'display:block;' : '' }}">

        <h6>View New Request Applications</h6>

        @if($nrStatus->count() == 0)

            <div class="row justify-content-center">
                No Data Present
            </div>

        @else

            <table class="table table-striped table-bordered">

                <thead>
                <tr>
                    <th>Sr No.</th>
                    <th>Registration Number</th>
                    <th>e-Filing Date</th>
                    <th>Applicant Name</th>
                    <th>Department Name</th>
                    <th>Current Action</th>
                    <th>Action Date</th>
                    <th>Reply Text</th>
                    <th>Download Reply</th>
                    <th>Additional Payment (Rs)</th>
                </tr>
                </thead>

                <tbody>

                @php
                    $serialNumber = $nrStatus->firstItem();
                @endphp

                @foreach($nrStatus as $st)

                    <tr>

                        <td>{{ $serialNumber++ }}</td>

                        <td>
                            <a style="color:black"
                               href="{{ route('commonData', ['id' => $st->id]) }}"
                               class="btn btn-link">

                                {{ $st->reg_no }}

                            </a>
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($st->date_of_efiling)->format('d-m-Y H:i') }}
                        </td>

                        <td>{{ $st->applicant_name }}</td>

                        <td>{{ $st->department }}</td>

                        <td>{{ $st->action_ }}</td>

                        <td>
                            {{ \Carbon\Carbon::parse($st->app_history_created)->format('d-m-Y H:i') }}
                        </td>

                        <td>{{ $st->reply_text ?? 'NA' }}</td>

                        <td>

                            @if($st->download_reply)

                                <a href="{{ Storage::url($st->download_reply) }}"
                                   target="_blank">

                                    <strong>Download</strong>

                                </a>

                            @else

                                <strong>NA</strong>

                            @endif

                        </td>

                        <td>

                            @if($st->action_ == 'ADDITIONAL PAYMENT PAID')

                                <strong>
                                    Amount Paid (Rs.): {{ $st->addl_payment }}
                                </strong>

                            @elseif($st->status_id == 9 && $st->action_ == 'ADDITIONAL PAYMENT REQUIRED FOR INFORMATION')

                                <form method="post"
                                      action="{{ route('payment') }}">

                                    @csrf

                                    <input type="hidden"
                                           name="reg_no_details"
                                           value="{{ $st }}">

                                    <button type="submit"
                                            class="btn btn-primary">

                                        Pay Rs. {{ $st->addl_payment }}

                                    </button>

                                </form>

                            @else

                                --

                            @endif

                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

            {{ $nrStatus->appends([
                'search' => request('search'),
                'divSelector' => 'display1'
            ])->links() }}

        @endif

    </div>

    <!-- ========================================================= -->
    <!-- ================= FIRST APPEAL TABLE ==================== -->
    <!-- ========================================================= -->

    <div class="table-responsive"
         id="display2"
         style="{{ $divSelector == 'display2' ? 'display:block;' : '' }}">

        <h6>View First Appeal Applications</h6>

        @if($faStatus->count() == 0)

            <div class="row justify-content-center">
                No Data Present
            </div>

        @else

            <table class="table table-striped table-bordered">

                <thead>
                <tr>
                    <th>Sr No.</th>
                    <th>Registration Number</th>
                    <th>e-Filing Date</th>
                    <th>Applicant Name</th>
                    <th>Department Name</th>
                    <th>Current Action</th>
                    <th>Action Date</th>
                    <th>Reply Text</th>
                    <th>Download Reply</th>
                </tr>
                </thead>

                <tbody>

                @php
                    $serialNumber = $faStatus->firstItem();
                @endphp

                @foreach($faStatus as $st)

                    <tr>

                        <td>{{ $serialNumber++ }}</td>

                        <td>
                            <a style="color:black"
                               href="{{ route('FAdetailedRequest', ['id' => $st->id]) }}"
                               class="btn btn-link">

                                {{ $st->reg_no }}

                            </a>
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($st->efiling_date)->format('d-m-Y H:i') }}
                        </td>

                        <td>{{ $st->applicant_name }}</td>

                        <td>{{ $st->department }}</td>

                        <td>{{ $st->changes }}</td>

                        <td>
                            {{ $st->app_history_created
                                ? \Carbon\Carbon::parse($st->app_history_created)->format('d-m-Y H:i')
                                : 'NA'
                            }}
                        </td>

                        <td>{{ $st->reply_text ?? 'NA' }}</td>

                        <td>

                            @if($st->reply_doc)

                                <a href="{{ Storage::url($st->reply_doc) }}"
                                   target="_blank">

                                    <strong>Download</strong>

                                </a>

                            @else

                                <strong>NA</strong>

                            @endif

                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

            {{ $faStatus->appends([
                'search' => request('search'),
                'divSelector' => 'display2'
            ])->links() }}

        @endif

    </div>

    <!-- ========================================================= -->
    <!-- ================= SECOND APPEAL TABLE =================== -->
    <!-- ========================================================= -->

    <div class="table-responsive"
         id="display3"
         style="{{ $divSelector == 'display3' ? 'display:block;' : '' }}">

        <h6>View Second Appeal Applications</h6>

        @if($saStatus->count() == 0)

            <div class="row justify-content-center">
                No Data Present
            </div>

        @else

            <table class="table table-striped table-bordered">

                <thead>
                <tr>
                    <th>Sr No.</th>
                    <th>Registration Number</th>
                    <th>e-Filing Date</th>
                    <th>Applicant Name</th>
                    <th>Department Name</th>
                    <th>Current Action</th>
                    <th>Action Date</th>
                    <th>Reply Text</th>
                    <th>Download Reply</th>
                </tr>
                </thead>

                <tbody>

                @php
                    $serialNumber = $saStatus->firstItem();
                @endphp

                @foreach($saStatus as $st)

                    <tr>

                        <td>{{ $serialNumber++ }}</td>

                        <td>
                            <a style="color:black"
                               href="{{ route('SAdetailedRequest', ['id' => $st->id]) }}"
                               class="btn btn-link">

                                {{ $st->reg_no }}

                            </a>
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($st->efiling_date)->format('d-m-Y H:i') }}
                        </td>

                        <td>{{ $st->applicant_name }}</td>

                        <td>{{ $st->department }}</td>

                        <td>{{ $st->status }}</td>

                        <td>
                            {{ $st->reply_date
                                ? \Carbon\Carbon::parse($st->reply_date)->format('d-m-Y H:i')
                                : 'NA'
                            }}
                        </td>

                        <td>{{ $st->reply_text ?? 'NA' }}</td>

                        <td>

                            @if($st->reply_doc)

                                <a href="{{ Storage::url($st->reply_doc) }}"
                                   target="_blank">

                                    <strong>Download</strong>

                                </a>

                            @else

                                <strong>NA</strong>

                            @endif

                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

            {{ $saStatus->appends([
                'search' => request('search'),
                'divSelector' => 'display3'
            ])->links() }}

        @endif

    </div>

</div>

@include('UsersView.footer')

<!-- ================= JS ================= -->

<script>

    function showDiv(divId)
    {
        // hide all tables
        document.querySelectorAll('.table-responsive')
            .forEach(function(div)
            {
                div.style.display = 'none';
            });

        // show selected table
        const selectedDiv = document.getElementById(divId);

        if(selectedDiv)
        {
            selectedDiv.style.display = 'block';
        }

        // check correct radio button
        const radio = document.querySelector(`input[value="${divId}"]`);

        if(radio)
        {
            radio.checked = true;
        }

        // update hidden field
        document.getElementById('hiddenDivSelector').value = divId;

        // update URL without reload
        const url = new URL(window.location.href);

        url.searchParams.set('divSelector', divId);

        window.history.replaceState({}, '', url.toString());

        // update pagination links
        selectedDiv.querySelectorAll('.pagination a')
            .forEach(function(link)
            {
                let linkUrl = new URL(link.href);

                linkUrl.searchParams.set('divSelector', divId);

                link.href = linkUrl.toString();
            });
    }

    document.addEventListener('DOMContentLoaded', function()
    {
        let activeDiv = "{{ $divSelector ?? 'display1' }}";

        showDiv(activeDiv);
    });

</script>

</body>
</html>