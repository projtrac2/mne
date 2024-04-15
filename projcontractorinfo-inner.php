<!--<div class="clearfix m-b-20">
    <div class="content" style="margin-top:-10px">-->
<?php 
try {
	//code...

?>
<div class="body">
	<div class="table-responsive">
		<ul class="nav nav-tabs" style="font-size:14px">
			<li class="active">
				<a data-toggle="tab" href="#home"><span class="fa fa-address-card-o"></span> Contractor Info</a>
			</li>
			<li>
				<a data-toggle="tab" href="#menu1"><span class="fa fa-users"></span> Contractor List of Directors &nbsp;<span class="badge bg-blue"><?php echo $totalRows_rsContrDir; ?></span></a>
			</li>
			<li>
				<a data-toggle="tab" href="#menu2"><span class="fa fa-certificate"></span> Contractor Documents&nbsp;<span class="badge bg-light-green"><?php echo $totalRows_rsContrDocs; ?></span></a>
			</li>
			<li>
				<a data-toggle="tab" href="#menu3"><span class="fa fa-list-alt"></span> Contractor Projects &nbsp;<span class="badge bg-deep-orange"><?php echo $totalRows_rsContrProj; ?></span></a>
			</li>
		</ul>
		<div class="tab-content">
			<div id="home" class="tab-pane fade in active">
				<div style="color:#333; background-color:#EEE; width:100%; height:30px">
					<h4><i class="fa fa-list" style="font-size:25px;color:blue"></i> Project Contractor Information</h4>
				</div>
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover tSortable22" width="98%">
						<thead>
							<tr id="colrow">
								<th style="width:20%">Title</th>
								<th style="width:80%">Details</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Contractor Name</td>
								<td><?php echo $row_rsContrInfo["contractor_name"]; ?></td>
							</tr>
							<tr>
								<td>Pin Number</td>
								<td><?php echo $row_rsContrInfo["pinno"]; ?></td>
							</tr>
							<tr>
								<td>Business Registration Number</td>
								<td><?php echo $row_rsContrInfo["busregno"]; ?></td>
							</tr>
							<tr>
								<td>Business Type</td>
								<td><?php echo $row_rsBzType["type"]; ?></td>
							</tr>
							<tr>
								<td>Date Registered</td>
								<td><?php echo $row_rsContrInfo["dtregistered"]; ?></td>
							</tr>
							<tr>
								<td>Pin Status</td>
								<td><?php echo $row_rsContrPinStatus["pin_status"]; ?></td>
							</tr>
							<tr>
								<td>Is VAT Registered</td>
								<td><?php echo $row_rsContrVat["vat"]; ?></td>
							</tr>
							<tr>
								<td>Phone Number</td>
								<td><?php echo $row_rsContrInfo["phone"]; ?></td>
							</tr>
							<tr>
								<td>Email Address</td>
								<td><?php echo $row_rsContrInfo["email"]; ?></td>
							</tr>
							<tr>
								<td>Postal Address</td>
								<td><?php echo $row_rsContrInfo["contact"]; ?></td>
							</tr>
							<tr>
								<td>Physical Address</td>
								<td><?php echo $row_rsContrInfo["address"]; ?></td>
							</tr>
							<tr>
								<td>City/Town</td>
								<td><?php echo $row_rsContrInfo["city"]; ?></td>
							</tr>
							<tr>
								<td>County</td>
								<td><?php echo $row_rsContrCounty["name"]; ?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div id="menu1" class="tab-pane fade">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover tSortable22" width="98%">
						<thead>
							<tr id="colrow">
								<td width="5%">
									<div align="center">#</div>
								</td>
								<td width="50%">Director Full Name</td>
								<td width="20%">ID/Passport Number</td>
								<td width="20%">Nationality</td>
								<?php
								if ($crud_permissions) {
								?>
									<td width="5%"></td>
								<?php
								}
								?>
							</tr>
						</thead>
						<tbody>
							<?php
							$sn = 0;
							while ($row_rsContrDir = $query_rsContrDir->fetch()) {
								$sn = $sn + 1;
								$Dirfullname = $row_rsContrDir['fullname'];
								$DirID = $row_rsContrDir['pinpassport'];
								$DirNat = $row_rsContrDir['nationality'];

								$query_rsDirNat = $db->prepare("SELECT * FROM tbl_contractornationality WHERE id='$DirNat'");
								$query_rsDirNat->execute();
								$row_rsDirNat = $query_rsDirNat->fetch();
							?>
								<tr>
									<td align="center"><?php echo $sn; ?></td>
									<td style="padding-left:15px"><?php echo $Dirfullname; ?></td>
									<td style="padding-left:15px"><?php echo $DirID; ?></td>
									<td style="padding-left:15px"><?php echo $row_rsDirNat['nationality']; ?></td>
									<?php
									if ($crud_permissions) {
									?>
										<td align="center">
											<a href="addcontractor?edit=1&contrid=<?php echo $row_rsContrDir['id']; ?>" class="edit-link" title="Edit"><i class="fa fa-edit text-primary" style="font-size:20px"></i></a>
										</td>
									<?php
									}
									?>

								</tr>
							<?php
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
			<div id="menu2" class="tab-pane fade">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover tSortable22" width="98%" border="1">
						<thead>
							<tr id="colrow">
								<td width="3%">
									<div align="center"><strong># </strong></div>
								</td>
								<td width="52%"> File Name</td>
								<td width="10%"> File Type</td>
								<td width="15%"> File Date</td>
								<td width="20%">
									<div align="center"><i class="fa fa-cloud-download" style="font-size:24px;color:red" alt="download"></i></div>
								</td>
							</tr>
						</thead>
						<tbody>
							<?php
							if ($totalRows_rsPFiles > 0) {
								if ($row_rsPFiles['file_format'] == "rtf" || $row_rsPFiles['file_format'] == "doc" || $row_rsPFiles['file_format'] == "docx") {
									$docformat = "Word";
								} elseif ($row_rsPFiles['file_format'] == "pdf" || $row_rsPFiles['file_format'] == "PDF") {
									$docformat = "PDF";
								} else {
									$docformat = $row_rsPFiles['file_format'];
								}
								$sn = 0;
								do {
									$sn = $sn + 1;
							?>
									<tr>
										<td width="3%" height="35">
											<div align="center"><?php echo $sn; ?></div>
										</td>
										<td width="52%" height="35">
											<div><?php echo $row_rsPFiles['attachment_purpose']; ?></div>
										</td>
										<td width="10%" height="35">
											<div align="center"><?php echo $docformat; ?></div>
										</td>
										<td width="15%">
											<div align="center"><?php echo $row_rsPFiles['ufdate']; ?></div>
										</td>
										<td width="20%">
											<div align="center"><a href="<?php echo $row_rsPFiles['floc']; ?>" type="button" class="btn bg-light-green waves-effect" title="Download File" target="new">Download</a></div>
										</td>
									</tr>
								<?php } while ($row_rsPFiles = $query_rsPFiles->fetch());
							} else {
								?>
								<!-- <tr>
									<td colspan="5">
										<div align="center">
											<font color="red">No Document(s) Found</font>
										</div>
									</td>
								</tr> -->
							<?php
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
			<div id="menu3" class="tab-pane fade">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover tSortable22" width="98%">
						<thead>
							<tr id="colrow">
								<th style="width:2%"></th>
								<th style="width:3%">#</th>
								<th style="width:28%">Project Name</th>
								<th style="width:13%">Project Location</th>
								<th style="width:15%">Project Budget (Ksh)</th>
								<th style="width:20%">Project Sector</th>
								<th style="width:9%">Project Financial Year</th>
								<th style="width:10%">Project Status</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$nm = 0;
							while ($row_rsContrProj = $query_rsContrProj->fetch()) {
								$nm = $nm + 1;
								$projsc = $row_rsContrProj['projcommunity'];
								$projwd = $row_rsContrProj['projlga'];
								$projloc = $row_rsContrProj['projstate'];
								$finyear = $row_rsContrProj['projfscyear'];
								$sector = $row_rsContrProj['sector'];

								$query_projsc = $db->prepare("SELECT state FROM tbl_state WHERE id='$projsc'");
								$query_projsc->execute();
								$row_projsc = $query_projsc->fetch();
								$subcounty = $row_projsc["state"];

								$query_projwd = $db->prepare("SELECT state FROM tbl_state WHERE id='$projwd'");
								$query_projwd->execute();
								$row_projwd = $query_projwd->fetch();
								$ward = $row_projwd["state"];

								$query_projloc = $db->prepare("SELECT state FROM tbl_state WHERE id='$projloc'");
								$query_projloc->execute();
								$row_projloc = $query_projloc->fetch();
								$loc = $row_projloc["state"];

								if ($subcounty == "All") {
									$ContrProjLoc = $subcounty . " " . $level1labelplural . "; " . $ward . " " . $level2labelplural . "; " . $loc . " " . $level3labelplural;
								} else {
									$ContrProjLoc = $subcounty . " " . $level1label . "; " . $ward . " " . $level2label . "; " . $loc . " " . $level3label;
								}

								$query_rsProjSect = $db->prepare("SELECT * FROM tbl_sectors WHERE stid = '$sector'");
								$query_rsProjSect->execute();
								$row_rsProjSect = $query_rsProjSect->fetch();

								$query_rsProjFY = $db->prepare("SELECT * FROM tbl_fiscal_year WHERE id = '$finyear'");
								$query_rsProjFY->execute();
								$row_rsProjFY = $query_rsProjFY->fetch();

								if ($row_rsContrProj["projstatus"]) {
									$statusid = $row_rsContrProj["projstatus"];
									$query_rsProjStatus = $db->prepare("SELECT statusname FROM tbl_status WHERE statusid = '$statusid'");
									$query_rsProjStatus->execute();
									$row_rsProjStatus = $query_rsProjStatus->fetch();
									$projstatus = $row_rsProjStatus["statusname"];
								} else {
									$projstatus = "Approved";
								}
							?>
								<tr data-toggle="collapse" data-target=".order<?php echo $nm; ?>">
									<td align="center" class="mb-0">
										<button class="btn btn-link" title="Click once to expand and Click twice to Collapse!!">
											<i class="fa fa-plus-square" style="font-size:16px"></i>
										</button>
									</td>
									<td align="center"><?php echo $nm; ?></td>
									<td><?php echo $row_rsContrProj['projname']; ?></td>
									<td><?php echo $ContrProjLoc; ?></td>
									<td><?php echo number_format($row_rsContrProj['projcost'], 2); ?></td>
									<td><?php echo $row_rsProjSect['sector']; ?></td>
									<td><?php echo $row_rsProjFY['year']; ?></td>
									<td><?php echo $projstatus; ?></td>
								</tr>
								<tr class="collapse order<?php echo $nm; ?>">
									<td style="height:1px; background-color:#000" COLSPAN=8></td>
								</tr>
								<?php
								$contrPrj = $row_rsContrProj["projid"];

								$query_rsProjTenderInfo = $db->prepare("SELECT * FROM tbl_tenderdetails WHERE projid = '$contrPrj'");
								$query_rsProjTenderInfo->execute();
								$row_rsProjTenderInfo = $query_rsProjTenderInfo->fetch();
								$totalRows_rsProjTenderInfo = $query_rsProjTenderInfo->rowCount();

								$tndid = $row_rsProjTenderInfo["td_id"];
								$tndType = $row_rsProjTenderInfo["tendertype"];
								$tndCat = $row_rsProjTenderInfo["tendercat"];
								$procMethod = $row_rsProjTenderInfo["procurementmethod"];

								$query_rsTDTP = $db->prepare("SELECT * FROM tbl_tender_type WHERE id = '$tndType'");
								$query_rsTDTP->execute();
								$row_rsTDTP = $query_rsTDTP->fetch();

								$query_rsTDCat = $db->prepare("SELECT * FROM tbl_tender_category WHERE id = '$tndCat'");
								$query_rsTDCat->execute();
								$row_rsTDCat = $query_rsTDCat->fetch();

								$query_rsPcMtd = $db->prepare("SELECT * FROM tbl_procurementmethod WHERE id = '$procMethod'");
								$query_rsPcMtd->execute();
								$row_rsPcMtd = $query_rsPcMtd->fetch();

								$query_rsAtt = $db->prepare("SELECT * FROM tbl_files WHERE projid = '$contrPrj'");
								$query_rsAtt->execute();
								$totalRows_rsAtt = $query_rsAtt->rowCount();

								if ($totalRows_rsProjTenderInfo > 0) {
								?>
									<tr class="collapse order<?php echo $nm; ?>">
										<td COLSPAN=1></td>
										<td style="width:20%" COLSPAN=2>Contract Ref Number</td>
										<td COLSPAN=5><?php echo $row_rsProjTenderInfo["contractrefno"]; ?></td>
									</tr>

									<tr class="collapse order<?php echo $nm; ?>">
										<td COLSPAN=1></td>
										<td style="width:20%" COLSPAN=2>Tender Title</td>
										<td COLSPAN=5><?php echo $row_rsProjTenderInfo["tendertitle"]; ?></td>
									</tr>

									<tr class="collapse order<?php echo $nm; ?>">
										<td COLSPAN=1></td>
										<td style="width:20%" COLSPAN=2>Tender Number</td>
										<td COLSPAN=5><?php echo $row_rsProjTenderInfo["tenderno"]; ?></td>
									</tr>

									<tr class="collapse order<?php echo $nm; ?>">
										<td COLSPAN=1></td>
										<td style="width:20%" COLSPAN=2>Tender Amount</td>
										<td COLSPAN=5><?php echo "Ksh." . number_format($row_rsProjTenderInfo["tenderamount"], 2); ?></td>
									</tr>

									<tr class="collapse order<?php echo $nm; ?>">
										<td COLSPAN=1></td>
										<td style="width:20%" COLSPAN=2>Tender Type</td>
										<td COLSPAN=5><?php echo $row_rsTDTP["type"]; ?></td>
									</tr>

									<tr class="collapse order<?php echo $nm; ?>">
										<td COLSPAN=1></td>
										<td style="width:20%" COLSPAN=2>Tender category</td>
										<td COLSPAN=5><?php echo $row_rsTDCat["category"]; ?></td>
									</tr>

									<tr class="collapse order<?php echo $nm; ?>">
										<td COLSPAN=1></td>
										<td style="width:20%" COLSPAN=2>Procurement Method</td>
										<td COLSPAN=5><?php echo $row_rsPcMtd["method"]; ?></td>
									</tr>

									<tr class="collapse order<?php echo $nm; ?>">
										<td COLSPAN=1></td>
										<td style="width:20%" COLSPAN=2>Evaluation Completion Date</td>
										<td COLSPAN=5><?php echo date("d M Y", strtotime($row_rsProjTenderInfo["evaluationdate"])); ?></td>
									</tr>

									<tr class="collapse order<?php echo $nm; ?>">
										<td COLSPAN=1></td>
										<td style="width:20%" COLSPAN=2>Tender Award Date</td>
										<td COLSPAN=5><?php echo date("d M Y", strtotime($row_rsProjTenderInfo["awarddate"])); ?></td>
									</tr>

									<tr class="collapse order<?php echo $nm; ?>">
										<td COLSPAN=1></td>
										<td style="width:20%" COLSPAN=2>Award Notification Date</td>
										<td COLSPAN=5><?php echo date("d M Y", strtotime($row_rsProjTenderInfo["notificationdate"])); ?></td>
									</tr>

									<tr class="collapse order<?php echo $nm; ?>">
										<td COLSPAN=1></td>
										<td style="width:20%" COLSPAN=2>Date of Signature</td>
										<td COLSPAN=5><?php echo date("d M Y", strtotime($row_rsProjTenderInfo["signaturedate"])); ?></td>
									</tr>

									<tr class="collapse order<?php echo $nm; ?>">
										<td COLSPAN=1></td>
										<td style="width:20%" COLSPAN=2>Contract Start Date</td>
										<td COLSPAN=5><?php echo date("d M Y", strtotime($row_rsProjTenderInfo["startdate"])); ?></td>
									</tr>

									<tr class="collapse order<?php echo $nm; ?>">
										<td COLSPAN=1></td>
										<td style="width:20%" COLSPAN=2>Contract End Date</td>
										<td COLSPAN=5><?php echo date("d M Y", strtotime($row_rsProjTenderInfo["enddate"])); ?></td>
									</tr>

									<tr class="collapse order<?php echo $nm; ?>">
										<td COLSPAN=1></td>
										<td style="width:20%" COLSPAN=2>Financial Score</td>
										<td COLSPAN=5><?php echo round($row_rsProjTenderInfo["financialscore"], 2) . " Marks"; ?></td>
									</tr>

									<tr class="collapse order<?php echo $nm; ?>">
										<td COLSPAN=1></td>
										<td style="width:20%" COLSPAN=2>Technical Score</td>
										<td COLSPAN=5><?php echo round($row_rsProjTenderInfo["technicalscore"], 2) . " Marks"; ?></td>
									</tr>
									<?php if ($totalRows_rsAtt > 0) {
										$nmb = 0;
										while ($row_rsAtt = $query_rsAtt->fetch()) {
											$nmb = $nmb + 1;
									?>
											<tr class="collapse order<?php echo $nm; ?>">
												<td COLSPAN=1></td>
												<td COLSPAN=4>Attachment <?php echo $nmb; ?> [Purpose: <font color="green"><?php echo $row_rsAtt["reason"]; ?></font>] </td>
												<td COLSPAN=3><?php echo $row_rsAtt["filename"]; ?> <a href="<?php echo $row_rsAtt["floc"]; ?>" type="button" class="btn bg-light-blue waves-effect" target="_blank"> Download</a></td>
											</tr>
										<?php
										}
									} else {
										?>
										<tr class="collapse order<?php echo $nm; ?>">
											<td COLSPAN=1></td>
											<td COLSPAN=7>
												<font color="red">NO ATTACHED TENDER DOCUMENT(S)</font>
											</td>
										</tr>
									<?php  } ?>
									<tr class="collapse order<?php echo $nm; ?>">
										<td style="height:2px; background-color:#000" COLSPAN=8></td>
									</tr>
								<?php
								} else {
								?>
									<tr class="collapse order<?php echo $nm; ?>">
										<td COLSPAN=1></td>
										<td COLSPAN=7 align="center">
											<font color="red">NO TENDER INFORMATION AVAILABLE</font>
										</td>
									</tr>
							<?php
								}
							}
							?>
						</tbody>
					</table>
				</div>

				<script src="others/js/dataTable/jquery.dataTables.min.js"></script>

				<script>
					$(document).ready(function() {
						$('.tSortable22').dataTable({
							"bPaginate": true,
							"bLengthChange": true,
							"bFilter": true,
							"bInfo": false,
							"bAutoWidth": true
						});
					});
				</script>
			</div>
		</div>
	</div>
</div>
<?php 

} catch (\PDOException $th) {
	customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());

}

?>