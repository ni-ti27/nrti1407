<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Dashboard</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
        <meta name="csrf-token" content="{{ csrf_token() }}">

    </head>

    <style>

        .container.mt-5
        {
            /* border: 2px solid black; */
            /* border-top: none; */
            border-bottom: none;
            margin-top: 0 !important;
        }

    </style>

    <body>
        @include('UsersView.header')
        <div class="container mt-5">
            <br>
            <h2>Payment Verification</h2>

            <div class="row">
                <div class=" col-lg-4 col-md-4">
                    <div class="form-group tab">
                    <label>Payment Mode</label>
                    <select class="form-control filter select_icondropdown_" id="paymentMode" name="paymentMode">
                        <option value="" selected Disabled>Select Payment Mode</option>
                        <option value="" >All</option>
                        <option value="Success">Success</option>
                        <option value="Failed">Failed</option>
                        <option value="verfication_pending">Payment verification pending</option>
                    </select>
                    </div>
                </div>

                <div class="col-lg-2 cont">
                    <div class="form-group tab1">
                        <label>From Date</label>
                        <input type="text" class="form-control datetimepicker-input reservationdate" name="start_date" id="start_date" placeholder="dd/mm/yyyy" data-target="#start_date" data-toggle="datetimepicker" autocomplete="off"/>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group tab2">
                    <label>To Date</label>
                    <input type="text" class="form-control datetimepicker-input reservationdate" name="end_date" id="end_date" placeholder="dd/mm/yyyy" data-target="#end_date" data-toggle="datetimepicker" autocomplete="off"/>
                    </div>
                </div>
            </div>
            <br>
            <div class="col-lg-12 col-md-12">
                <div class="card border-none bg-transparent box-showd-none">
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="payment_verify_table">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Name</th>
                                        <th>Registration No</th>
                                        <th>eChallan Number</th>
                                        <th>Amount (Rs.)</th>
                                        <th>Transaction Date</th>
                                        <th>Payment Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <br>
        </div>
        @include('UsersView.footer')
    </body>
    <script src="{{ asset('jquery/jquery-3.6.4.min.js') }}"></script>

    <script src="{{ asset('jquery/jquery.dataTables.min.js') }}"></script>
    <!-- Custome javascript/jQuery functions -->


<script type="text/javascript">
   $(function () {
    var token = $('meta[name="csrf-token"]').attr('content');
    $(function() {
      $(".reservationdate").datepicker(
        {
            dateFormat: 'dd-mm-yy',
        }
      );
    });
        // $('.reservationdate').datetimepicker({
        //     format: 'd-m-Y',
        // });

        // $('.reservationdate').datetimepicker();

        paymentData();
    });

    $('#paymentMode').on('change', function(){
        paymentData();
    });

    $('#start_date').on('change', function(){
        paymentData();
    });

    $('#end_date').on('change', function(){
        paymentData();
    });
    function paymentData() {
    $('#payment_verify_table').DataTable().destroy();
    $('#payment_verify_table').DataTable({
        processing: true,
        serverSide: true,
        paging: true,
        lengthChange: true,
        lengthMenu: [10, 50, 100, 250, 500, 1000],
        searching: true,
        info: true,
        autoWidth: false,
        // responsive: true,
        // dom: "Bfrtip",
        dom: "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'B><'col-sm-12 col-md-4'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        "order": [
            [0, "asc"]
        ],
        "ajax": {
            url: "{{ route('dashboard') }}",
            data: function(d) {
                d.paymentMode = $('#paymentMode').find('option:selected').val();
                d.start_date = $('#start_date').val();
                d.end_date = $('#end_date').val();
            }
        },
        // "buttons": ["copy", "csv", "excel", "pdf", "print"],
        columns: [
            // {data: 'id', name: 'id'},
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'name', name: 'name' },
            { data: 'reg_no', name: 'reg_no' },
            { data: 'echallan_no', name: 'echallan_no' },
            { data: 'total_amt', name: 'total_amt' },
            { data: 'trans_dt', name: 'trans_dt' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action' },
        ],
        // Apply Bootstrap classes to the DataTable elements
        initComplete: function () {
            // Add Bootstrap classes to the DataTable elements
            var table = $('#payment_verify_table').DataTable();
            $(table.table().container()).addClass('table-responsive');
            $(table.table().container()).find('input[type="search"]').addClass('form-control');
            $(table.table().container()).find('select[name="payment_verify_table_length"]').addClass('form-select');
        }
    });
}
</script>
</html>
