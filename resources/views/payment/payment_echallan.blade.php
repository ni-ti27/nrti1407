<form name="json_form" action="https://echallanpg.goa.gov.in/haveechallan.aspx" method="post">
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
										</button><?php //echo killstring($error); ?>
									</div>
								<?php //} ?>
								<div class="modal-body" style="overflow: visible;">
									<div class="row clearfix">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="card">
												<div class="row">
													<div class="col-lg-12">
														<h2 style="font-weight: bold;text-align: center; ">Echallan Payment Details</h2>
														<br>
													</div>
													<div class=" col-sm-2" style="text-align: right;">
													</div>
												</div>
												<ul class="header-dropdown m-r-0">
												</ul>
												<div class="body">
													<div class="" style="background-color:#EDEDED;  border-radius:10px;">
														<div class="row justify-content-center" style="background-color: white;">
															<div class="col-xs-1" align="center"></div>
																<div class="col-xs-10" align="center">
																	<div class="body table-responsive" style="overflow-x: auto">
																		<input type="hidden" name="demand" value=56>
																		<input type="hidden" name="encdata" value="{{ $encrypted_paymentReq }}">
																		<table id="table_id" class="display" class="table table-bordered table-striped table-hover js-basic-example dataTable" style="width: 100%; border-bottom: 1px solid #111;">
																			<thead>
																				<tr>
																					<th style="color: white;font-weight: bold; font-size: 20px;text-align: center; height: 40px; vertical-align: middle;">Name</th>
																					<th style="color: white;font-weight: bold; font-size: 20px;text-align: center; height: 40px; vertical-align: middle;">EChallan No</th>
																					<th style="color: white;font-weight: bold; font-size: 20px;text-align: center; height: 40px; vertical-align: middle;">Total Amount</th>
																				</tr>
																			</thead>
																			<tbody>
																				<tr>
																					<td style=" text-align: center;">{{ isset($name) ? $name : '-'}}</td>
																					<td style=" text-align: center;">{{ isset($echallanno) ? $echallanno : '' }}</td>
																					<td style="height:34 ;color: purple; font-size: 18px;text-align: center;font-weight: bold;">{{isset($totalAmt) ? $totalAmt : ''}}</td>
																				</tr>
																			</tbody>
																		</table>
																		<input type="submit" style="background-color:#0a6720; color: white; margin-top: 20px;" value="Initiate Payment">
																		<br><br>
																		<h2 style="text-align:center">Please Note: After completion of Payment Process, it is required to do "Verify Transaction" in Payment Status form to update Final Payment Status</h2>
																	</div>
																</div>
															</div>
														</div>
														<!-- <div class="row justify-content-center">
															<h2 style="text-align:center">Please Note: After completion of Payment Process, it is required to do Verify Transaction in Payment Status form to update Final Payment Status</h2>
														</div> -->
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
		</div>
	</section>
</form>