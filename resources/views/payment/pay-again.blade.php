<section class="content">
    <div class="row">
        <section class="col-lg-12 connectedSortable">
            <div class="col-md-12">
                <div class="box box-solid">
                    <div class="ui-panel ui-widget ui-widget-content ui-corner-all" style="background-color: #E6E6E6;padding: 2%;margin: 3%;/*! margin-right: 4%; */">

                        <div class="modal-content">
                            <div class="modal-header" style="background: #FBDABD">

                                <h3 style="color: purple;margin-top: 0px;" class="hmodel3">E-Payment</h3>
                            </div>
                            <?php //if ($error) { ?>
                                <div class="alert alert-<?php //echo killstring($errdiv); ?>  alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
                                    </button><?php // echo killstring($error); ?>
                                </div>
                            <?php //} ?>
                            <div class="modal-body" style="overflow: visible;">
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="card">
                                            <!--                                            <div class="header">-->
                                            <div class="row">
                                                <div class="col-lg-10">
                                                    <h2>Services </h2>
                                                    <br>
                                                </div>
                                                <div class="col-sm-2" style="text-align: right;">
                                                </div>
                                            </div>
                                            <ul class="header-dropdown m-r-0">
                                            </ul>
                                            <!--                                            </div>-->
                                            <div class="body">
                                                <div class="" style="background-color:#EDEDED;  border-radius:10px;">
                                                    <div class="row" style="background-color: white;">
                                                        <div class="col-xs-12" align="center">
                                                            <div class="body table-responsive" style="overflow-x: auto">
                                                                <table id="table_id" class="display" class="table table-bordered table-striped table-hover js-basic-example dataTable" style="width: 100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Sl.No</th>
                                                                            <th>Service</th>
                                                                            <th>Fees</th>
                                                                            <th>Required
                                                                            </th>
                                                                            <th>Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody style="font-weight: bolder;padding-left: 10px;">
                                                                        <?php
                                                                        // $appln_reg_id = $_SESSION['id_appl_reg'];
                                                                        // $query = "select a.reg_no,srv.service_name as service_name,srv.service_code as service_code,to_char(a.date_of_appln,'DD/MM/YYYY')
                                                                        //         as date_of_appln,a.fees_calculated_system, a.id_appl_reg, a.fees_fully_paid  as fees_fully_paid, a.current_appln_status ,
                                                                        //         a.amount_paid_collected,
                                                                        //         ast.status_name from application_register a
                                                                        //         join services srv on a.service_code=srv.service_code
                                                                        //         join application_status_code ast on a.current_appln_status = ast.application_status
                                                                        //         left join service_authority sa on sa.service_code=a.service_code and sa.state_code= :statecode_sn
                                                                        //         where a.reg_no= :regno and a.rc='n' and a.lc='n' and id_appl_reg = :id_appln";
                                                                        // $stmt = $pdconn->prepare($query);
                                                                        // $stmt->execute(array(':regno' => $regno, ':statecode_sn' => $statecode_sn, ':id_appln' => $appln_reg_id));
                                                                        // $norows = $stmt->rowCount();
                                                                        // $pngi = 1;
                                                                        // if ($norows != 0) {
                                                                        //     while ($row = $stmt->fetch()) {
                                                                        //         $pngregno = $row['reg_no'];
                                                                        //         $pngservice = $row['service_name'];
                                                                        //         $pngservice_code = $row['service_code'];
                                                                        //         $pngdateofappln = $row['date_of_appln'];
                                                                        //         $pngfeesystem = $row['fees_calculated_system'];
                                                                        //         $pngamountpaid = $row['amount_paid_collected'];
                                                                        //         $pngapplnstatus = $row['status_name'];
                                                                        //         $appl_reg_id = $row['id_appl_reg'];
                                                                        //         $fee_paid_status = $row['fees_fully_paid'];
                                                                        //         $current_file_status = $row['current_appln_status'];
                                                                        ?>
                                                                                <td style="padding-left: 20px;"><?php //echo $pngi; ?></td>
                                                                                <td height="34" style="padding-left: 20px;"><?php //echo $pngservice . '  ' . '[' . $pngregno . ']'; ?></td>
                                                                                <td style="height:34 ;padding-left: 20px;color: purple; text-align: right; font-size: 18px;"> <?php //echo '₹' . $pngfeesystem . '/-'; ?></td>
                                                                                <input name="fees_accept" type="hidden" value="<?php //echo  $pngfeesystem;  ?> ">
                                                                                <?php
                                                                                // $sqlqux = "select paid_status from payment_initial where  reg_no = :pngregno and service_code = :pngservice_code and appl_id = :appl_id order by entry_date";
                                                                                // $stmtx1 = $pdconn->prepare($sqlqux);
                                                                                // $stmtx1->execute(array(':pngregno' => $pngregno, ':pngservice_code' => $pngservice_code, ':appl_id' => $appl_reg_id));
                                                                                // $norowsx = $stmtx1->rowCount();
                                                                                // if ($norowsx > 0) {
                                                                                //     $res1 = $stmtx1->fetchAll();
                                                                                //     foreach ($res1 as $row) {
                                                                                //         $paid_status_code = $row['paid_status'];
                                                                                //         if ($paid_status_code == 'S') {
                                                                                //             break;
                                                                                //         }
                                                                                //     }

                                                                                ?>
                                                                                    <?php //if (($paid_status_code == 'P') || $payment_reconsile_flag  == "false") { ?>
                                                                                        <td style="padding-left: 20px;"><label style="font-size: 81%;background-color: #e6e6e6;    padding: 2%; color: #db1313;" value="Pay Now">Payment waiting </label></td>

                                                                                    <?php
                                                                                   // } else if ($paid_status_code == 'S') { ?>
                                                                                        <td style="padding-left: 20px;"><label style="font-size: 81%;background-color:#0a6720;     padding: 2%; color: white" value="Pay Now">Payment Sucess </label></td>
                                                                                        <?php
                                                                                       // if ($current_file_status == 'E' || $current_file_status == 'C') {
                                                                                            //if ($pngservice_code == 'RC' || $pngservice_code == 'LC'  || $pngservice_code == 'LR') { ?>
                                                                                                <td style="padding-left: 20px;"> <button type="button" id="forward_btn" onclick="ForwardForm('<?php //echo $pngservice_code ?>' ,'<?php //echo $appl_reg_id ?>')" style=" color: white; background-color: #136ea5;"> Forward for Verification </button></td>

                                                                                            <?php
                                                                                            //} else { ?>
                                                                                                <td style="padding-left: 20px;"> <button type="button" id="forward_btn" onclick="backtohome('<?php //echo $_SESSION['service_code'] = $pngservice_code ?>' ,'<?php //echo $appl_reg_id ?>')" style=" color: white; background-color: #136ea5;"> Back to home </button></td>

                                                                                            <?php  //}
                                                                                        //} else {
                                                                                            ?>
                                                                                            <td style="padding-left: 20px;"><label style="font-size: 81%;background-color:#4d3bdb;    padding: 2%; color: white">Forwarded for Verification </label></td>

                                                                                        <?php
                                                                                        //}
                                                                                    //} else if ($paid_status_code == 'F') {
                                                                                        ?>
                                                                                        <td style="padding-left: 20px;"><input type="button" data-toggle="modal" data-target="#myModalDoc" class="btn  btn-lg m-l-15 waves-effect opt2" style="font-size: 81%;background-color:#0a6720; color: white" value="Pay Now"></td>

                                                                                    <?php
                                                                                //     }
                                                                                // } else {
                                                                                    ?>
                                                                                    <td style="padding-left: 20px;"><input type="button" data-toggle="modal" data-target="#myModalDoc" class="btn  btn-lg m-l-15 waves-effect opt2" style="font-size: 81%;background-color:#0a6720; color: white" value="Pay Now"></td>


                                                                                <?php
                                                                                // }


                                                                                //                                                                            if($pngservice_code =='LR')
                                                                                //                                                                            {
                                                                                //
                                                                                //                                                                            
                                                                                ?>
                                                                                <!--                                                                                                                                                        <td  style="padding-left: 20px;"> <div class="text"><span style="color: blue"  onclick="lc_ForwardForm()"> Forward License Renewal   to Department </span></div></td>-->
                                                                                <?php
                                                                                //                                                                            }
                                                                                //                                                                            else if($fee_paid_status == 'Y')
                                                                                //                                                                            {
                                                                                //                                                                                $service = $pngservice_code;
                                                                                //                                                                                include("../home/other_redirectpage_public.php");
                                                                                //                                                                            }
                                                                                ?>
                                                                                <!-- <td  style="padding-left: 20px;"> <div class="text"><span style="color: blue"  onclick="ForwardForm()"> Forward  to Department </span></div></td> -->
                                                                                </tr>
                                                                            <?php
                                                                            //     $pngi = $pngi + 1;
                                                                            // }
                                                                            ?>
                                                                        <?php
                                                                        // }
                                                                        ?>
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
                            <div class="modal-footer">
                                <!--<button class="btn" data-dismiss="modal">OK</button>-->
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
</section>

<div class="modal fade" id="getCodeModal" tabindex="-1" role="dialog" style=" padding-right: 10px;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title">E Payment Status</h4>
                <hr>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group " style="display: none" id="Pay_fail">
                            <img src="../home/images/payment_failed.png" width='100' height="100">
                        </div>
                        <div class="form-group " style="display: none" id="Pay_sucess">
                            <img src="../home/images/payment_success.png" width='100' height="100">
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="form-group ">

                            <h4 style="color: darkred" id="getCode">Failed</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="getForwardModal" tabindex="-1" role="dialog" style=" padding-right: 10px;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title">Forward To Department</h4>
                <hr>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3">

                        <div class="form-group " style="display: none" id="for_fail">
                            <img src="../home/images/payment_failed.png" width='100' height="100">
                        </div>
                        <div class="form-group " style="display: none" id="for_sucess">
                            <img src="../home/images/payment_success.png" width='100' height="100">
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="form-group " id="status_img">

                            <h4 style="color: darkred" id="getStatus">Failed</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>

<!--<>//////////////////////////////////////////////////////////////////////////////////////////////////-->
<form method="post" action="#" name="payment_page" id="payment_page" enctype="application/x-www-form-urlencoded">
    <input type="hidden" name='token_hr' id='token_hr' value='<?php //echo $_SESSION['Token_gbl']; ?>'>
    <div id="myModalDoc" class="modal fade in" tabindex="-1" role="dialog" style="display: none; padding-right: 17px;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                    <h4 class="modal-title">Terms and Condition</h4>
                    <hr>
                </div>
                <div class="modal-body">

                    <b>The Registration Fees and Application Fees once paid against each department will not be refunded in any case.</b>
                    <br>
                    <br>
                    <input name="md_checkbox1" type="checkbox" class="filled-in chk-col-green" id="md_checkbox1" value="Y">
                    <label for="md_checkbox1"><b>I read this message and understood completely</b></label>
                    <input type="hidden" name="statecode" id="statecode" value="<?php //echo $statecode_sn ?> ">

                    <input type="hidden" name="registration_num" id="registration_num" value="<?php //echo $pngregno ?> ">
                    <input type="hidden" name="id_appl_reg" id="id_appl_reg" value="<?php //echo $appl_reg_id ?> ">
                    <!-- 12-10-2022 added sv_code -->
                    <input type="hidden" name="service_code" id="service_code" value="<?php //echo $pngservice_code ?> ">
                    <input type="hidden" name="registration_service" id="registration_service" value="<?php //echo $pngservice_code ?> ">
                    <input type="hidden" name="Pay_amount" id="Pay_amount" value="<?php //echo $pngfeesystem ?> ">
                    <input type="submit" name="bnksubmit" id="bnksubmit" class="btn  btn-lg m-l-15 waves-effect" style="font-size: 95%;background-color:#0a6720; color: white; display: none;" value="Pay Now">

                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
</form>
<script>
    function ForwardForm(val, id) {
        if (val == 'RC') {
            var urls = '../home/ajax/Forward_for_verify.php';
        }
        if (val == 'LC' || val == 'LR') {
            var urls = '../home/ajax/Forward_for_verify_lc.php';
        }
        var ask = window.confirm("Are you sure you want to Forward ");
        if (ask) {
            ajaxindicatorstart('loading data.. please wait..');
            $.ajax({
                type: 'POST',
                url: urls,
                data: {
                    id_appl_reg: id
                },
                dataType: "json",
                success: function(rData) {
                    $("#forward_btn").hide();
                    ajaxindicatorstop();
                    $("#getForwardModal").modal("show");
                    if (rData.err_status == '1') {
                        $('#for_fail').show();
                    } else {
                        $('#for_sucess').show();
                    }
                    $("#getStatus").html(rData.message);

                }
            });
        }
    }

    function backtohome(val, id) {
        $('#service_code').val(val);
        $('#id_appl_reg').val(id);
        document.getElementById("payment_page").method = "post";
        document.payment_page.action = "../home/other_redirectpage_public.php";
        document.getElementById("payment_page").submit();
    }

    function payForm(val) {

        var urls = 'gujarat/payment1_sucess.php';


        ajaxindicatorstart('loading data.. please wait..');
        $.ajax({
            type: 'POST',
            url: urls,
            data: {
                appl_id: val
            },
            dataType: "json",
            success: function(rData) {
                $("#forward_btn").hide();
                ajaxindicatorstop();
                $("#getForwardModal").modal("show");
                if (rData.err_status == '1') {
                    $('#for_fail').show();
                } else {
                    $('#for_sucess').show();
                }
                $("#getStatus").html(rData.message);

            }
        });
    }
</script>

<script>
    $(function() {

        $('#md_checkbox1').change(function() {
            if (this.checked)
                $('#bnksubmit').fadeIn();
            else
                $('#bnksubmit').fadeOut();
        }).change();


    });
</script>
<script>
    $(document).ready(function() {
        $('#table_id').DataTable({
            responsive: true
        })
    });
</script>