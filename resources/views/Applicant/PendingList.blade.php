<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Requests Status</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
</head>

<style>
    .container.mt-5 {
        border-top:none;
        margin-top:0 !important;
    }

    .hidden {
        display:none;
    }

    .tableHead {
        background-color: #f8f9fa;
    }
</style>

<body>
    @include('UsersView.header')

    <div class="container mt-5">
       <div class="d-flex justify-content-between align-items-center py-3" style="padding-left:10px; padding-right:10px;">
        <h2 class="mb-0">Pending Applications</h2>

        <a href="{{ url('/profile') }}" class="btn btn-success">
            Proceed to File New RTI
        </a>
        </div>
        <div class="table-responsive table" id="display1" style="display: {{ $divSelector == 'display1' ? 'block' : 'none' }};">


            @if(count($nrStatus) == 0)
                <div class="row justify-content-center">No Data Present</div>
            @else
                <table class="table table-striped table-bordered">
                    <thead class="tableHead">
                        <tr>
                            <th>Sr No.</th>
                            <th>Registration Number</th>
                            <th>Applied Date</th>
                            <th>Applicant Name</th>
                            <th>Payment Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $serialNumber = $nrStatus->firstItem(); @endphp
                        @foreach ($nrStatus as $st)
                            <tr>
                                <td>{{ $serialNumber++ }}</td>
                                <td>
                                    <a style="color:black" href="{{ route('pending-edit-view', $st->id)}}" class="btn btn-link">
                                        <strong>{{ $st->registration_no }}</strong>
                                    </a>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($st->created_at)->format('d-m-Y H:i') }}</td>
                                <td>{{ $st->name }}</td>
                                <td>
                                    @if($st->payment_details == 'Failed')
                                        <span class="badge bg-danger">Failed</span>
                                    @elseif($st->payment_details == 'Success')
                                        <span class="badge bg-success text-dark">Success</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>

                               {{--<td class="text-center">
                                    <form method="GET" action="{{ route('payment') }}">
                                        @csrf
                                        <input name="reg_no_details" id="reg_no_details" type="hidden" value="{{$st->registration_no ?? ''}}" >
                                        <input type="hidden" name="challan_no" value="{{ $st->echallan_no ?? '' }}">
                                        <input type="hidden" name="reg_no" value="{{ $st->registration_no ?? '' }}">
                                        <input type="hidden" name="addl_payment" value="{{ $st->total_amt ?? '' }}">
                                        <input type="hidden" name="pid" value="{{ $st->transaction_id ?? '' }}">


                                        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('Do you want to try paying for this application again?')">
                                            Payment Verify
                                        </button>
                                    </form>


                                </td> --}}


                                <td>
                                @if(is_null($st->transaction_id))
                                    {{-- No payment record: show Payment Verify as a form styled like a button --}}
                                    <form method="POST" action="{{ route('payment') }}" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="reg_no_details" value="{{ $st->registration_no ?? '' }}">
                                        <input type="hidden" name="challan_no" value="{{ $st->echallan_no ?? '' }}">
                                        <input type="hidden" name="reg_no" value="{{ $st->registration_no ?? '' }}">
                                        <input type="hidden" name="addl_payment" value="{{ $st->total_amt ?? '' }}">
                                        <input type="hidden" name="pid" value="{{ $st->transaction_id ?? '' }}">

                                        <button type="submit"
                                                class="btn btn-outline-info btn-sm fw-bold"
                                                style="text-decoration: none;"
                                                onclick="return confirm('Do you want to try paying for this application again?')">
                                            <i class="bi bi-arrow-repeat"></i>Complete Payment
                                        </button>
                                    </form>
                                @else
                                    {{-- Payment record exists: show status buttons --}}
                                    @if(isset($st->online_pay_status) && $st->online_pay_status == 'Success')
    {{-- Success State: No need for a "failed" confirmation --}}
    <a href="{{ route('payment-verify', $st->transaction_id) }}"
       class="btn btn-outline-success btn-sm"
       target="_self">
        <i class="bi bi-check-circle"></i> Payment Success
    </a>

@elseif(isset($st->online_pay_status) && $st->online_pay_status == 'Failed')
    {{-- Failed State: Keep the confirmation for retry/verify --}}
    <a href="{{ route('payment-verify', $st->transaction_id) }}"
       class="btn btn-outline-danger btn-sm fw-bold"
       onclick="return confirm('Payment failed. Would you like to verify the status or try again?')">
        <i class="bi bi-exclamation-triangle"></i> Payment Failed
    </a>

@else
    {{-- Pending/Unknown State --}}
    <a href="{{ route('payment-verify', $st->transaction_id) }}"
       class="btn btn-outline-info btn-sm fw-bold">
        <i class="bi bi-arrow-repeat"></i> Verify Status
    </a>
@endif
                                @endif
                            </td>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $nrStatus->appends(['divSelector' => $divSelector])->links() }}
                <br>
            @endif
        </div>
    </div>

    @include('UsersView.footer')

    <script>
        function showDiv(divId) {
            // Update the URL and refresh to show correct div
            var url = new URL(window.location.href);
            url.searchParams.set('page', 1);
            url.searchParams.set('divSelector', divId);
            window.location.href = url.href;
        }
    </script>
</body>
</html>