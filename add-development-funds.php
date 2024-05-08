<?php
try {
	require('includes/head.php');
	if ($permission) {
		$fnd = '';
		if (isset($_GET["fn"]) && !empty($_GET["fn"])) {
			$hash = $_GET['fn'];
			$decode_fndid = base64_decode($hash);
			$fndid_array = explode("fn918273AxZID", $decode_fndid);
			$fnd = $fndid_array[1];

			$action = "UPDATE";
			$fundsfrmid = "MM_update";
			$fundsfrm = "editfundsfrm";

			$query_financier = $db->prepare("SELECT * FROM tbl_financiers WHERE id=:fndid");
			$query_financier->execute(array(":fndid" => $fnd));
			$row_financier = $query_financier->fetch();

			$fundtype = $row_financier["type"];
			$financiername = $row_financier["financier"];

			$query_financiertype = $db->prepare("SELECT * FROM tbl_financier_type WHERE id=:fundtype");
			$query_financiertype->execute(array(":fundtype" => $fundtype));
			$row_financiertype = $query_financiertype->fetch();
			$financiertype = $row_financiertype["description"];

			$edit_form = false;
			if (isset($_GET["edit"]) && !empty($_GET["edit"])) {
				$query_rsfunding = $db->prepare("SELECT *, s.type AS type FROM tbl_funds f inner join tbl_financiers s on s.id=f.funder WHERE f.id=:fndid");
				$query_rsfunding->execute(array(":fndid" => $fnd));
				$row_funding = $query_rsfunding->fetch();
				$edit_form = true;
				$fundcode = $row_funding["fund_code"];
				$financier = $row_funding["funder"];
				$finyear = $row_funding["financial_year"];
				$amount = $row_funding["amount"];
				$currency = $row_funding["currency"];
				$rate = $row_funding["exchange_rate"];
				$funddate = $row_funding["date_funds_released"];
				$purpose = $row_funding["funds_purpose"];
				$grantspan = $row_funding["grant_life_span"];
				$grantinstallments = $row_funding["grant_installments"];
				$installmentdate = $row_funding["grant_installment_date"];

				$query_rsfunders = $db->prepare("SELECT * FROM tbl_financiers WHERE type=:fundtype");
				$query_rsfunders->execute(array(":fundtype" => $fundtype));
			} else {
				$action = "ADD";
				$fundsfrmid = "MM_insert";
				$fundsfrm = "addfundsfrm";
			}
		}


		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addfundsfrm")) {
			$grantlifespan = $grantinstallments = $grantinstallmentdate = NULL;

			$fund_date = $_POST['fundsdate'];
			$fdate = strtotime($fund_date);
			$funddate = date("Y-m-d", $fdate);
			$current_date = date("Y-m-d");
			$grantlifespan = $_POST['grantlifespan'];
			$grantinstallments = $_POST['grantinstallments'];
			// $grantinstallmentdate = $_POST['grantinstallmentdate'];
			$grantlifespan = $grantinstallments =  $grantinstallmentdate = 0;
			$financier_id = $_POST['financier'];
			$financial_year = $_POST['year'];

			if (!empty($_POST['code']) && !empty($_POST['year']) && !empty($_POST['amount']) && !empty($funddate) && !empty($_POST['user_name'])) {
				$insertSQL = $db->prepare("INSERT INTO tbl_funds (funder, fund_code, financial_year, amount, currency, exchange_rate, date_funds_released, funds_purpose, grant_life_span, grant_installments, grant_installment_date, recorded_by, date_recorded)  VALUES (:funder, :code, :year, :amount, :currency, :rate, :funddate, :purpose, :lifespan, :installments, :installmentsdate, :recordedby, :recorddate)");
				$result = $insertSQL->execute(array(':funder' => $financier_id, ':code' => $_POST['code'], ':year' => $financial_year, ':amount' => $_POST['amount'], ':currency' => $_POST['currency'], ':rate' => $_POST['rate'], ':funddate' => $funddate, ':purpose' => $_POST['purpose'], ':lifespan' => $grantlifespan, ':installments' => $grantinstallments, ':installmentsdate' => $grantinstallmentdate, ':recordedby' => $_POST['user_name'], ':recorddate' => $current_date));

				if ($result) {
					$last_id = $db->lastInsertId();

					$filecategory = "Funding";
					$catid = $last_id;
					$myUser = $_POST['user_name'];

					$count = count($_POST["attachmentpurpose"]);
					for ($cnt = 0; $cnt < $count; $cnt++) {
						if (!empty($_FILES['fundsattachment']['name'][$cnt])) {
							$purpose = $_POST["attachmentpurpose"][$cnt];
							$filename = basename($_FILES['fundsattachment']['name'][$cnt]);
							$ext = substr($filename, strrpos($filename, '.') + 1);
							if (($ext != "exe") && ($_FILES["fundsattachment"]["type"][$cnt] != "application/x-msdownload")) {
								$newname = $catid . "-" . $filename;
								$filepath = "uploads/financiers/" . $newname;
								if (!file_exists($filepath)) {
									if (move_uploaded_file($_FILES['fundsattachment']['tmp_name'][$cnt], $filepath)) {
										$qry2 = $db->prepare("INSERT INTO tbl_files (`projstage`, `filename`, `ftype`, `floc`, `fcategory`, `reason`, `uploaded_by`, `date_uploaded`) VALUES (:projstage, :fname, :ext, :floc, :filecat, :purpose, :myUser, :date)");
										$qry2->execute(array(':projstage' => $catid, ':fname' => $newname, ':ext' => $ext, ':floc' => $filepath, ':filecat' => $filecategory, ':purpose' => $purpose, ':myUser' => $myUser, ':date' => $current_date));
									}
								} else {
									$msg = 'File you are uploading already exists, try another file!!';
									$results = error_message($msg, 2, 'view-financiers.php');
								}
							} else {
								$msg = 'This file type is not allowed, try another file!!';
								$results = error_message($msg, 2, 'view-financiers.php');
							}
						} else {
							$msg = 'You have not attached any file!!';
							$results = error_message($msg, 2, 'view-financiers.php');
						}
					}
					$msg = 'Funds successfully added.';
					$results = success_message($msg, 2, 'view-financiers.php');
					echo $results;
				}
			}
		} elseif ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "editfundsfrm")) {
			$grantlifespan = $grantinstallments = $grantinstallmentdate = NULL;
			$funderid = $_POST['funderid'];
			$fund_date = $_POST['fundsdate'];
			$fdate = strtotime($fund_date);
			$funddate = date("Y-m-d", $fdate);
			$current_date = date("Y-m-d");
			$grantlifespan .= $_POST['grantlifespan'];
			$grantinstallments .= $_POST['grantinstallments'];
			// $grantinstallmentdate .= $_POST['grantinstallmentdate'];


			if (!empty($_POST['code']) && !empty($_POST['year']) && !empty($_POST['amount']) && !empty($funddate) && !empty($_POST['user_name'])) {
				$insertSQL = $db->prepare("UPDATE tbl_funds SET funder=:funder, fund_code=:code, financial_year=:year, amount=:amount, currency=:currency, exchange_rate=:rate, date_funds_released=:funddate, funds_purpose=:purpose, grant_life_span=:lifespan, grant_installments=:installments, grant_installment_date=:installmentsdate, updated_by=:recordedby, date_updated=:recorddate WHERE id=:funderid");
				$result = $insertSQL->execute(array(':funder' => $_POST['financier'], ':code' => $_POST['code'], ':year' => $_POST['year'], ':amount' => $_POST['amount'], ':currency' => $_POST['currency'], ':rate' => $_POST['rate'], ':funddate' => $funddate, ':purpose' => $_POST['purpose'], ':lifespan' => $grantlifespan, ':installments' => $grantinstallments, ':installmentsdate' => $grantinstallmentdate, ':recordedby' => $_POST['user_name'], ':recorddate' => $current_date, ':funderid' => $funderid));

				if ($result) {

					$filecategory = "Funding";
					$catid = $funderid;
					$myUser = $_POST['user_name'];

					$count = count($_POST["attachmentpurpose"]);

					for ($cnt = 0; $cnt < $count; $cnt++) {
						if (!empty($_FILES['fundsattachment']['name'][$cnt])) {
							$purpose = $_POST["attachmentpurpose"][$cnt];
							$filename = basename($_FILES['fundsattachment']['name'][$cnt]);
							$ext = substr($filename, strrpos($filename, '.') + 1);

							if (($ext != "exe") && ($_FILES["fundsattachment"]["type"][$cnt] != "application/x-msdownload")) {
								$newname = $catid . "-" . $filename;
								$filepath = "uploads/financiers/" . $newname;
								if (!file_exists($filepath)) {
									if (move_uploaded_file($_FILES['fundsattachment']['tmp_name'][$cnt], $filepath)) {
										$qry2 = $db->prepare("INSERT INTO tbl_files (`projstage`, `filename`, `ftype`, `floc`, `fcategory`, `reason`, `uploaded_by`, `date_uploaded`) VALUES (:projstage, :fname, :ext, :floc, :filecat, :purpose, :myUser, :date)");
										$qry2->execute(array(':projstage' => $catid, ':fname' => $newname, ':ext' => $ext, ':floc' => $filepath, ':filecat' => $filecategory, ':purpose' => $purpose, ':myUser' => $myUser, ':date' => $current_date));
									}
								} else {
									$msg = 'File you are uploading already exists, try another file!!';
									$results = error_message($msg, 2, 'view-financiers.php');
								}
							} else {
								$msg = 'This file type is not allowed, try another file!!';
								$results = error_message($msg, 2, 'view-financiers.php');
							}
						} else {
							$msg = 'You have not attached any file!!';
							$results = error_message($msg, 2, 'view-financiers.php');
						}
					}
					$msg = 'Funds successfully updated.';
					$results = success_message($msg, 2, 'view-financiers.php');
				}
			}
		}

		$cy = date("Y");
		$cm = date("m");
		if ($cm < 7) {
			$currentyear = $cy - 1;
		} else {
			$currentyear = $cy;
		}
		$nxtyr = $currentyear + 1;

		$query_rsFyear =  $db->prepare("SELECT * FROM tbl_fiscal_year WHERE yr >=:currentyear AND yr <= :nxtyr ORDER BY id ASC");
		$query_rsFyear->execute(array(":currentyear" => $currentyear, ":nxtyr" => $nxtyr));

		$query_rsfundingtype =  $db->prepare("SELECT * FROM tbl_funding_type where status=1 ORDER BY id ASC");
		$query_rsfundingtype->execute();

		$query_financiercurrency =  $db->prepare("SELECT id, currency FROM tbl_currency ORDER BY id ASC");
		$query_financiercurrency->execute();

		$query_programs =  $db->prepare("SELECT progid, progname FROM tbl_programs ORDER BY progid ASC");
		$query_programs->execute();

?>

		<!-- start body  -->
		<section class="content">
			<div class="container-fluid">
				<div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
					<h4 class="contentheader">
						<?= $icon ?>
						<?php echo $pageTitle ?>
						<div class="btn-group" style="float:right">
							<button onclick="history.go(-1)" class="btn bg-orange waves-effect pull-right" style="margin-right: 10px">
								Go Back
							</button>
						</div>
					</h4>
				</div>
				<div class="row clearfix">
					<div class="block-header">
						<?= $results; ?>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="card">
							<div class="body">
								<!-- start body -->
								<form id="add_funds" method="POST" name="<?= $fundsfrm ?>" action="" enctype="multipart/form-data" autocomplete="off">
									<?= csrf_token_html(); ?>
									<fieldset class="scheduler-border">
										<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">DETAILS</legend>
										<div class="col-md-8">
											<label>Financier *:</label>
											<div class="form-line">
												<div class="form-control" style="border:#CCC thin solid; border-radius: 5px"><?= $financiername ?></div>
												<input type="hidden" name="financier" value="<?= $fnd ?>">
												<input type="hidden" name="fundingtype" id="fundingtype" value="<?= $fundtype ?>">
											</div>
										</div>
										<div class="col-md-4">
											<label>Fund Type *:</label>
											<?php
											if ($fundtype == 1 || $fundtype == 2) {
											?>
												<div class="form-line">
													<select name="fund_type" class="form-control show-tick" data-live-search="true" style="border:#CCC thin solid; border-radius:5px" required>
														<option value="" selected="selected" class="selection">...Select Fund Type...</option>
														<option value="1"> Local Revenue</option>
														<option value="2"> Equitable Share</option>
													</select>
												</div>
											<?php
											} else {
												$fund_name = "";
												if ($fundtype == 3) {
													$fund_name = "Grant";
												} else if ($fundtype == 4) {
													$fund_name = "Donor";
												} else if ($fundtype == 4) {
													$fund_name = "Others";
												}
											?>
												<div class="form-line">
													<div class="form-control" style="border:#CCC thin solid; border-radius: 5px"><?= $fund_name ?></div>
												</div>
											<?php
											}
											?>
										</div>
										<div class="col-md-4">
											<label>Funds Code *:</label>
											<div class="form-line">
												<input type="text" class="form-control" name="code" id="code" placeholder="Enter funds identification number" style="border:#CCC thin solid; border-radius: 5px" value="<?= ($edit_form) ? $fundcode : "" ?>" required>
											</div>
										</div>
										<div class="col-md-4">
											<label>Financial Year *:</label>
											<div class="form-line">
												<select name="year" id="year" class="form-control show-tick" data-live-search="true" style="border:#CCC thin solid; border-radius:5px" required>
													<option value="" selected="selected" class="selection">...Select...</option>
													<?php
													while ($row_rsFyear = $query_rsFyear->fetch()) {
														if ($edit_form) {
															if ($row_rsFyear['id'] == $finyear) {
													?>
																<option value="<?php echo $row_rsFyear['id'] ?>" selected="selected"><?php echo $row_rsFyear['year'] ?></option>
															<?php
															} else {
															?>
																<option value="<?php echo $row_rsFyear['id'] ?>"><?php echo $row_rsFyear['year'] ?></option>
															<?php
															}
														} else {
															?>
															<option value="<?php echo $row_rsFyear['id'] ?>"><?php echo $row_rsFyear['year'] ?></option>
													<?php
														}
													}
													?>
												</select>
											</div>
										</div>
										<script src="http://afarkas.github.io/webshim/js-webshim/minified/polyfiller.js"></script>
										<script type="text/javascript">
											webshims.setOptions('forms-ext', {
												replaceUI: 'auto',
												types: 'number'
											});
											webshims.polyfill('forms forms-ext');
										</script>
										<div class="col-md-4">
											<label>Funding Amount *:</label>
											<div class="form-line">
												<input name="amount" type="number" onchange="calculate_funds()" onkeyup="calculate_funds()" id="amount_funds" placeholder="Enter funding amount" min="0" step="1" data-number-to-fixed="0" data-number-stepfactor="100" class="form-control" id="c2" style="border:#CCC thin solid; border-radius: 5px" value="<?= ($edit_form) ? $amount : ""; ?>" required>
											</div>
										</div>
										<div class="col-md-4">
											<label>Financier Currency *:</label>
											<div class="form-line">
												<select name="currency" id="currency" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
													<option value="" selected="selected" class="selection">...Select Currency...</option>
													<?php
													while ($row_financiercurrency = $query_financiercurrency->fetch()) {
														if ($edit_form) {
															if ($row_financiercurrency['id'] == $currency) {
													?>
																<option value="<?php echo $row_financiercurrency['id'] ?>" selected="selected"><?php echo $row_financiercurrency['currency'] ?></option>
															<?php
															} else {
															?>
																<option value="<?php echo $row_financiercurrency['id'] ?>"><?php echo $row_financiercurrency['currency'] ?></option>
															<?php
															}
														} else {
															?>
															<option value="<?php echo $row_financiercurrency['id'] ?>"><?php echo $row_financiercurrency['currency'] ?></option>
													<?php
														}
													}
													?>
												</select>
											</div>
										</div>
										<div class="col-md-4">
											<label>Financier Exchange Rate *:</label>
											<div class="form-line">
												<input name="rate" type="number" class="form-control" placeholder="Enter financier exchange rate" value="<?= ($edit_form) ? $rate : ""; ?>" style="border:#CCC thin solid; border-radius: 5px; padding-left:10px">
											</div>
										</div>
										<div class="col-md-4">
											<label>Date Funds Release *:</label>
											<div class="form-line">
												<input name="fundsdate" type="date" class="form-control" placeholder="Please choose a date..." value="<?= ($edit_form) ? $funddate : ""; ?>" style="border:#CCC thin solid; border-radius: 5px; padding-left:10px">
											</div>
										</div>
										<?php
										if ($fundtype == 3 || $fundtype == 4) {
										?>
											<div class="col-md-12" id="ppprogram">
												<label>Funds Purpose *:</label>
												<div class="form-line">
													<input name="purpose" type="text" placeholder="Describe the funds purpose" class="form-control" style="border:#CCC thin solid; border-radius: 5px" value="<?= $edit_form ? $purpose : ""; ?>" required>
												</div>
											</div>
										<?php
										} else {
										?>
											<div class="col-md-12" id="ppgeneral">
												<label>Funds Purpose *:</label>
												<div class="form-line">
													<input name="purpose" type="text" placeholder="Describe the funds purpose" class="form-control" style="border:#CCC thin solid; border-radius: 5px" value="<?= $edit_form ? $purpose : ""; ?>" required>
												</div>
											</div>
										<?php
										}
										?>
										<div id="grants">
											<div class="col-md-4" id="grantperiod">
												<label>Projected Grant Lifespan *:</label>
												<div class="form-line">
													<input name="grantlifespan" type="number" placeholder="Enter funding amount" min="0" step="1" data-number-to-fixed="0" data-number-stepfactor="100" class="form-control" id="c2" style="border:#CCC thin solid; border-radius: 5px" value="<?= ($edit_form) ? $grantspan : ""; ?>" required>
												</div>
											</div>
											<div class="col-md-4" id="grantinstallment">
												<label>Grant Installments *:</label>
												<div class="form-line">
													<input name="grantinstallments" type="number" placeholder="Enter proposed payment schedule" min="0" step="1" data-number-to-fixed="0" data-number-stepfactor="100" class="form-control" id="c2" style="border:#CCC thin solid; border-radius: 5px" value="<?= ($edit_form) ? $grantinstallments : ""; ?>" required>
												</div>
											</div>
										</div>
									</fieldset>
									<fieldset class="scheduler-border">
										<legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">FUND ATTACHMENTS</legend>
										<!-- File Upload | Drag & Drop OR With Click & Choose -->
										<div class="row clearfix">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="card" style="margin-bottom:-20px">
													<?php
													if (isset($_GET["edit"]) && !empty($_GET["edit"])) {
														$counter = 0;
														$fcategory = "Funding";
														$query_rsFile = $db->prepare("SELECT * FROM tbl_files WHERE fcategory=:cat and projstage=:projstage");
														$query_rsFile->execute(array(":cat" => $fcategory, ":projstage" => $fnd));
														$row_rsFile = $query_rsFile->fetch();
														$totalRows_rsFile = $query_rsFile->rowCount();

														if ($totalRows_rsFile > 0) {
													?>
															<div class="header table-responsive">
																<i class="ti-link"></i>MULTIPLE FILES UPLOAD - WITH CLICK & CHOOSE
																<table class="table table-bordered" id="donation-attachment">
																	<thead>
																		<tr>
																			<th style="width:2%">#</th>
																			<th style="width:45%">File Name</th>
																			<th style="width:45%">Attachment Purpose</th>
																			<th style="width:8%">Action</th>
																		</tr>
																	</thead>
																	<tbody>
																		<?php
																		do {
																			$flid = $row_rsFile['fid'];
																			$fname = $row_rsFile['filename'];
																			$type = $row_rsFile['ftype'];
																			$filepath = $row_rsFile['floc'];
																			$attachmentPurpose = $row_rsFile['reason'];
																			$act =  '<a href="' . $filepath . '" style="color:#06C; padding-left:2px; padding-right:2px; font-weight:bold" title="Download File" target="new"><i class="fa fa-cloud-download fa-2x" aria-hidden="true"></i></a>
																		<a type="button" data-toggle="modal" data-target="#removeItemModal" id="removeItemModalBtn" onclick="removeItem(' . $flid . ')"> <i class="glyphicon glyphicon-trash"></i></a>';
																			//<a style="color:#06C; padding-left:2px; padding-right:2px; font-weight:bold" title="Delete File"><i class="fa fa-clse fa-2x" aria-hidden="true"></i></a>';

																			$counter++;
																			echo '<tr>
																			<td>
																			  ' . $counter . '
																			</td>
																			<td>
																			' . $fname . '
																			</td>
																			<td>
																			' . $attachmentPurpose . '
																			</td>
																			<td align="center">
																			' . $act . '
																			</td>
																		</tr>';
																		} while ($row_rsFile = $query_rsFile->fetch());
																		?>
																	</tbody>
																</table>
															</div>
													<?PHP
														}
													}

													?>
													<div class="body">
														<table class="table table-bordered" id="donation_table">
															<tr>
																<th style="width:40%">Attachments</th>
																<th style="width:58%">Attachment Purpose</th>
																<th style="width:2%"><button type="button" name="addplus" onclick="add_row();" title="Add another document" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th>
															</tr>
															<tr>
																<td>
																	<input type="file" name="fundsattachment[]" multiple id="fundsattachment[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
																</td>
																<td>
																	<input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control" placeholder="Enter the purpose of this document" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
																</td>
																<td></td>
															</tr>
														</table>
														<script type="text/javascript">
															function add_row() {
																$rowno = $("#donation_table tr").length;
																$rowno = $rowno + 1;
																$("#donation_table tr:last").after('<tr id="row' + $rowno + '"><td><input type="file" name="fundsattachment[]" multiple id="fundsattachment[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required></td><td><input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control"  placeholder="Enter the purpose of this document" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required></td><td><button type="button" class="btn btn-danger btn-sm"  onclick=delete_row("row' + $rowno + '")><span class="glyphicon glyphicon-minus"></span></button></td></tr>');
																// <input type='text' name='funding[]' placeholder='Enter Name'></td><td><input type='button' value='DELETE' onclick=delete_row('row"+$rowno+"')></td></tr>");
															}

															function delete_row(rowno) {
																$('#' + rowno).remove();
															}
														</script>
													</div>
												</div>
											</div>
										</div>
										<!-- #END# File Upload | Drag & Drop OR With Click & Choose -->
									</fieldset>
									<div class="row clearfix">
										<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
										</div>
										<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" align="center">
											<input name="fundtype" type="hidden" id="fundtype" value="<?php echo $fundtype; ?>" />
											<input name="funderid" type="hidden" id="funderid" value="<?php echo $fnd; ?>" />
											<input name="user_name" type="hidden" id="user_name" value="<?php echo $user_name; ?>" />
											<div class="btn-group">
												<input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" value="<?= $action ?>" />
											</div>
											<input type="hidden" name="<?= $fundsfrmid ?>" value="<?= $fundsfrm ?>" />
										</div>
										<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
										</div>
									</div>
								</form>
								<!-- end body -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- end body  -->
		<!-- Bootstrap Datepicker Plugin Js -->
		<!-- <script src="projtrac-dashboard/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script> -->
<?php
	} else {
		$results =  restriction();
		echo $results;
	}

	require('includes/footer.php');
} catch (PDOException $ex) {
	var_dump($ex);
	customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>

<script src="assets/custom js/funding.js"></script>
<script>
	const amount_funds = () => {
		console.log($('#amount_funds').val());
	}
</script>