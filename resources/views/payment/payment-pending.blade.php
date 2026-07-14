<section class="container-fluid image_content" style="width: auto;min-height: 100% ">
    <section class="container">
        <br>
        <div class="box">
            <br>
            <div class="panel-body">
                <div class="card row clearfix">
                    <div class="body">
                        <div>
                            <h2>
                                <span style="color: purple; font-size: 20px; font-weight: bold ;font-style: italic;"><?php echo "PAYMENT PENDING "; ?></span>&nbsp;<span style="font-size: 15px;color: blue">(<?php echo ($date_form); ?>)</span>
                            </h2>
                        </div>
                        <div class="row clearfix">
                            <div class="panel-group" id="accordion_1" role="tablist" aria-multiselectable="true">
                                <div class="panel-body">
                                    <div id="collapseTwo_1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo_1" aria-expanded="true">
                                        <div class="panel-body">
                                            <h3>Payment History</h3>
                                            <div class="col-xs-12" align="center">
                                                <div class="body table-responsive">
                                                    <table border="1" cellspacing="3" cellpadding="3" width="100%" class="table table-bordered table-striped table-hover no-head-5no">
                                                        <thead>
                                                            <tr height="50px" style="color: white; background-color:#1b438b ">
                                                                <th style="text-align: center;" scope="col">Sl.No</th>
                                                                <th style="text-align: center;" scope="col">Transaction
                                                                    Date
                                                                </th>
                                                                <th style="text-align: center;" scope="col">Transaction
                                                                    ID
                                                                </th>
                                                                <th style="text-align: center;" scope="col">Fees</th>
                                                                <th style="text-align: center;" scope="col">Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody style="font-weight: bolder;padding-left: 10px;">
                                                        @if(count($sqlpay2) > 0)
                                                            @foreach($sqlpay2 as $row2)
                                                                <tr>
                                                                    <td style="padding-left: 20px;">{{$sll}}</td>
                                                                    <td height="45" style="padding-left: 20px;">{{$row->entry_date}}></td>
                                                                    <td height="45" style="padding-left: 20px;">{{ $row2->id }}</td>
                                                                    <td height="45" style="padding-left: 20px;font-size: 30px;color: <?php echo $clr; ?>;"> {{$row->amount}}</td>
                                                                    <td height="45" style="padding-left: 20px; color: White">
                                                                        <a href="payment_verify_goa.php?token_lr={{$_SESSION['Token_gbl']}} ">
                                                                            <input type="button" id="btn_retry" name="btn_retry" class="btn  btn-lg m-l-15 waves-effect opt1" style="background-color:#3f51b5; color: white" value="Check Payment Status"></a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                        <tr><H5>No Data Found</H5></tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</section>
<style>
    /* .image_content {
        background-color: #f1f1f1;
        background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url("../../home/images/payment_bg.jpg");
        padding-bottom: 1%;

    } */
</style>