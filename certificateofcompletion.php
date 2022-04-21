<?php
$pageName = "Strategic Plans";
$replacement_array = array(
    'planlabel' => "CIDP",
    'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');
$pageTitle = $planlabelplural;

if ($permission) {
    try {
        $query_rsUsers =  $db->prepare("SELECT * FROM tbl_projteam2 t inner join tbl_users u on u.pt_id=t.ptid WHERE u.username = '$username'");
        $query_rsUsers->execute();
        $row_rsUsers = $query_rsUsers->fetch();
        $totalRows_rsUsers = $query_rsUsers->rowCount();

        $query_rsMilestoneCert =  $db->prepare("SELECT tbl_projects.*, tbl_contractor.*, tbl_milestone.msid, tbl_milestone.milestone, tbl_milestone.progress, tbl_milestone.status, tbl_milestone.sdate AS mlstdate, tbl_milestone.edate AS mlendate FROM tbl_projects  LEFT JOIN tbl_milestone ON tbl_projects.projid=tbl_milestone.projid LEFT JOIN tbl_payments_request ON tbl_payments_request.itemid=tbl_milestone.msid LEFT JOIN tbl_contractor ON tbl_projects.projcontractor=tbl_contractor.contrid WHERE tbl_projects.deleted='0' AND tbl_milestone.paymentstatus = 0 AND tbl_payments_request.status = 2 AND tbl_projects.projcategory = '2' AND tbl_milestone.status=5");
        $query_rsMilestoneCert->execute();
        $Rows_rsMilestoneCert = $query_rsMilestoneCert->rowCount();

        $query_rsTaskCert =  $db->prepare("SELECT tbl_projects.*, tbl_projmembers.ptid, tbl_task.tkid, tbl_task.task, tbl_task.taskbudget AS taskbudget, tbl_task.inspectionscore, tbl_task.description, tbl_task.responsible, tbl_task.sdate AS stdate, tbl_task.edate AS endate FROM tbl_projects  LEFT JOIN tbl_task ON tbl_projects.projid=tbl_task.projid LEFT JOIN tbl_projmembers ON tbl_projects.projid=tbl_projmembers.projid WHERE tbl_projects.deleted='0' AND tbl_task.paymentstatus = '0' AND tbl_task.inspectionstatus = '1' AND tbl_projects.projcategory = '1' AND tbl_task.status LIKE '%Completed Task%'");
        $query_rsTaskCert->execute();
        $Rows_rsTaskCert = $query_rsTaskCert->rowCount();
    } catch (PDOException $ex) {
        $results = flashMessage("An error occurred: " . $ex->getMessage());
    }
?>
    <!-- start body  -->
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                    <i class="fa fa-columns" aria-hidden="true"></i>
                    <?php echo $pageTitle ?>
                    <div class="btn-group" style="float:right">
                        <div class="btn-group" style="float:right">
                        </div>
                    </div>
                </h4>
            </div>
            <div class="row clearfix">
                <div class="block-header">
                    <?= $results; ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="header" style="padding-bottom:0px">
                            <div class="button-demo" style="margin-top:-15px">
                                <span class="label bg-black" style="font-size:18px"><img src="images/proj-icon.png" alt="Project Menu" title="Project Menu" style="vertical-align:middle; height:25px" /> Menu</span>
                                <a href="project-milestones-payment" class="btn bg-light-blue waves-effect" style="margin-top:10px">Contractor</a>
                                <a href="project-inhouse-payment" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">In House</a>
                                <a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px">Completion Certificates</a>
                                <a href="paymentsreport" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Payments Report</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="header">
                                <div style="color:#333; background-color:#EEE; width:100%; height:30px">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-5px">
                                        <tr>
                                            <td width="100%" height="35" style="padding-left:5px; background-color:#7883a3; color:#FFF" bgcolor="#000000">
                                                <div align="left"><i class="fa fa-certificate" aria-hidden="true"></i> Project/Milestone Completion Certificates</strong></div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <ul class="nav nav-tabs" style="font-size:14px">
                                <li class="active">
                                    <a data-toggle="tab" href="#home"><i class="fa fa-caret-square-o-down bg-purple" aria-hidden="true"></i> Milestone Certificates &nbsp;<span class="badge bg-purple"><?php echo $Rows_rsMilestoneCert; ?></span></a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#menu1"><i class="fa fa-caret-square-o-up bg-deep-purple" aria-hidden="true"></i> Project Certificates &nbsp;<span class="badge bg-deep-purple">0<?php //echo $Rows_rsTaskCert; 
                                                                                                                                                                                                                ?></span></a>
                                </li>
                            </ul>
                        </div>
                        <div class="body">

                            <div class="tab-content">
                                <div id="home" class="tab-pane fade in active">
                                    <div style="color:#333; background-color:#EEE; width:100%; height:30px">
                                        <h4 style="width:100%"><i class="fa fa-list" style="font-size:25px;color:#9C27B0"></i> Milestones Completion Certificates</h4>
                                    </div>
                                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                        <thead>
                                            <tr class="bg-purple">
                                                <th style="width:5%">#</th>
                                                <th style="width:10%">Cerfiticate No.</th>
                                                <th style="width:30%">Project</th>
                                                <th style="width:20%">Milestone</th>
                                                <th style="width:10%">Date Completed</th>
                                                <th style="width:20%">Contractor</th>
                                                <th style="width:5%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $nm = 0;
                                            while ($row_rsMilestoneCert = $query_rsMilestoneCert->fetch()) {
                                                $nm = $nm + 1;
                                                $requestid = $row_rsMilestoneCert['id'];
                                                $mstid = $row_rsMilestoneCert['msid'];
                                                $project = $row_rsMilestoneCert['projname'];
                                                $projid = $row_rsMilestoneCert['projid'];
                                                $milestone = $row_rsMilestoneCert['milestone'];
                                                $mlendate = $row_rsMilestoneCert['mlendate'];
                                                $reqstatus = $row_rsMilestoneCert['status'];
                                                $contractor = $row_rsMilestoneCert['contractor_name'];
                                                $requestedby = $row_rsMilestoneCert['requestedby'];
                                                $reqamnt = number_format($row_rsMilestoneCert['amountrequested'], 2);

                                                $msendate = strtotime($mlendate);
                                                $mstnendate = date("d M Y", $msendate);

                                                $query_reqstatus = $db->prepare("SELECT status FROM tbl_payment_status WHERE id = '$reqstatus'");
                                                $query_reqstatus->execute();
                                                $row_reqstatus = $query_reqstatus->fetch();
                                                $requeststatus = $row_reqstatus["status"];

                                                $query_cert = $db->prepare("SELECT id, certificateno FROM tbl_certificates WHERE projid = '$projid' AND category = 2 AND itemid='$mstid'");
                                                $query_cert->execute();
                                                $row_cert = $query_cert->fetch();
                                                $certno = $row_cert["certificateno"];

                                            ?>
                                                <tr>
                                                    <td align="center"><?php echo $nm; ?></td>
                                                    <td><?php echo $certno; ?></td>
                                                    <td><?php echo $project; ?></td>
                                                    <td><?php echo $milestone; ?></td>
                                                    <td><?php echo $mstnendate; ?></td>
                                                    <td><?php echo $contractor; ?></td>
                                                    <td>
                                                        <a href="milestonecertificate?doni=<?php echo $row_cert['id']; ?>" style="color:#06C; padding-left:2px; padding-right:2px; font-weight:bold" data-toggle="tooltip" data-placement="bottom" title="Click Here to Download Certificate" target="new"><i class="fa fa-cloud-download fa-2x" aria-hidden="true"></i></a>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div id="menu1" class="tab-pane fade">
                                    <div style="color:#333; background-color:#EEE; width:100%; height:30px">
                                        <h4><i class="fa fa-list" style="font-size:25px;color:#673AB7"></i> Projects Completion Certificates</h4>
                                    </div>
                                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                        <thead>
                                            <tr class="bg-deep-purple">
                                                <th style="width:5%">#</th>
                                                <th style="width:10%">Certificate No.</th>
                                                <th style="width:30%">Project Name</th>
                                                <th style="width:30%">Contractor</th>
                                                <th style="width:15%">Completion Date</th>
                                                <th style="width:10%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            /*$nm = 0;
												while($row_rsTaskCert = $query_rsTaskCert->fetch())
												{ 
												    $nm = $nm + 1;
													$approvedid = $row_rsTaskCert['id'];
													$task = $row_rsTaskCert['task'];
													$project = $row_rsTaskCert['projname'];
													$projid = $row_rsTaskCert['projid'];
													$category = $row_rsTaskCert['itemcategory'];
													$itemid = $row_rsTaskCert['itemid'];
													$approvedstatus = $row_rsTaskCert['status'];
													$approveddate = $row_rsTaskCert['approvaldate'];
													$approvedby = $row_rsTaskCert['approvalby'];
													$approvedamnt = number_format($row_rsTaskCert['amountpaid'], 2);
													
													$appduedate = strtotime($approveddate."+ 30 days");
													$paymentduedate = date("d M Y",$appduedate);
													
													$payappdate = strtotime($approveddate);
													$paymentappdate = date("d M Y",$payappdate);
															 
													if($category == 1){
														$itemcat = "Task";
														$query_item = $db->prepare("SELECT * FROM tbl_task WHERE tkid = '$itemid'");
														$query_item->execute();
														$row_item = $query_item->fetch();
														$itemname = $row_item["task"];
														$itemstatus = $row_item["status"];
													}else{
														$itemcat = "Milestone";
														$query_item = $db->prepare("SELECT * FROM tbl_milestone WHERE msid = '$itemid'");
														$query_item->execute();
														$row_item = $query_item->fetch();
														$itemname = $row_item["milestone"];
														$itemstatus = $row_item["status"];
													}
																
													$query_appstatus = $db->prepare("SELECT status FROM tbl_payment_status WHERE id = '$approvedstatus'");
													$query_appstatus->execute();
													$row_appstatus = $query_appstatus->fetch();
													$approvalstatus = $row_appstatus["status"];
													
													$query_approver = $db->prepare("SELECT fullname FROM admin WHERE username = '$approvedby'");
													$query_approver->execute();
													$row_approver = $query_approver->fetch();
													$approver = $row_approver["fullname"]; */
                                            ?>
                                            <!--<tr>
														<td align="center"><?php //echo $nm; 
                                                                            ?></td>
														<td><?php //echo $reqidno; 
                                                            ?></td>
														<td><?php //echo $itemcat; 
                                                            ?></td>
														<td><?php //echo $itemname; 
                                                            ?></td>
														<td><?php //echo $approvedamnt; 
                                                            ?></td>
														<td style="color:#F44336">
															<a href="<?php //echo $rsPayReceived['floc']; 
                                                                        ?>" style="color:#06C; padding-left:2px; padding-right:2px; font-weight:bold" data-toggle="tooltip" data-placement="bottom" title="Click Here to Download Certificate" target="new"><i class="fa fa-cloud-download fa-2x" aria-hidden="true"></i></a>
														</td>
													</tr>-->
                                            <?php
                                            //}
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- end body  -->
<?php
} else {
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');
?>