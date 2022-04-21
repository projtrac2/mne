<?php
$pageName = "Strategic Plans";
$replacement_array = array(
    'planlabel' => "CIDP",
    'plan_id' => base64_encode(6),
);

$page = "view";

require('includes/head.php');
if ($permission) {
    $pageTitle ="Financial Requests Dashboard";
    try {
    	if (isset($_GET['contrid'])) {
    		$contrid_rsInfo = $_GET['contrid'];
    	}
    	$query_rsPayRequest = $db->prepare("SELECT * FROM tbl_payments_request R INNER JOIN tbl_projects P ON R.projid=P.projid WHERE status = 1 OR status = 7 Order BY itemcategory ASC");
    	$query_rsPayRequest->execute();
    	$totalRows_rsPayRequest = $query_rsPayRequest->rowCount();

    	$query_rsPayApproved = $db->prepare("SELECT * FROM tbl_payments_request r INNER JOIN tbl_projects p ON r.projid=p.projid WHERE status = 2 OR status = 6 Order BY itemcategory ASC");
    	$query_rsPayApproved->execute();
    	$totalRows_rsPayApproved = $query_rsPayApproved->rowCount();

    	$query_rsPayRejected = $db->prepare("SELECT * FROM tbl_payments_request R INNER JOIN tbl_projects P ON R.projid=P.projid WHERE status = 3 Order BY itemcategory ASC");
    	$query_rsPayRejected->execute();
    	$totalRows_rsPayRejected = $query_rsPayRejected->rowCount();

    	$query_rsPayReceived = $db->prepare("SELECT *, r.itemid AS ritem,r.requestid AS req FROM tbl_payments_disbursed d INNER JOIN tbl_payments_request r ON d.reqid=r.id Order BY itemcategory ASC");
    	$query_rsPayReceived->execute();
    	$totalRows_rsPayReceived = $query_rsPayReceived->rowCount();
    } catch (PDOException $ex) {
    	$result = flashMessage("An error occurred: " . $ex->getMessage());
    	print($result);
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
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                      <div class="card-header">
                        <ul class="nav nav-tabs" style="font-size:14px">
                          <li class="active">
                            <a data-toggle="tab" href="#home"><i class="fa fa-caret-square-o-down bg-deep-orange" aria-hidden="true"></i> Payment Requests &nbsp;<span class="badge bg-orange"><?php echo $totalRows_rsPayRequest; ?></span></a>
                          </li>
                          <li>
                            <a data-toggle="tab" href="#menu1"><i class="fa fa-caret-square-o-up bg-blue" aria-hidden="true"></i> Payments Approved &nbsp;<span class="badge bg-blue"><?php echo $totalRows_rsPayApproved; ?></span></a>
                          </li>
                          <li>
                            <a data-toggle="tab" href="#menu2"><i class="fa fa-caret-square-o-left bg-red" aria-hidden="true"></i> Payments Rejected &nbsp;<span class="badge bg-red"><?php echo $totalRows_rsPayRejected; ?></span></a>
                          </li>
                          <li>
                            <a data-toggle="tab" href="#menu3"><i class="fa fa-caret-square-o-right bg-green" aria-hidden="true"></i> Requests Paid &nbsp;<span class="badge bg-light-green"><?php echo $totalRows_rsPayReceived; ?></span></a>
                          </li>
                        </ul>
                      </div>

                        <div class="body">
                          <div class="tab-content">
                            <div id="home" class="tab-pane fade in active">
                              <div style="color:#333; background-color:#EEE; width:100%; height:30px">
                                <h4 style="width:100%"><i class="fa fa-list" style="font-size:25px;color:#FF9800"></i> Project Payment Request Pending</h4>
                              </div>
                              <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr class="bg-orange">
                                            <th style="width:2%"></th>
                                            <th style="width:3%">#</th>
                                            <th style="width:8%">Category</th>
                                            <th style="width:27%">Item Name</th>
                                            <th style="width:10%">Amount (Ksh)</th>
                                            <th style="width:10%">Requester</th>
                                            <th style="width:10%">Date Requested</th>
                                            <th style="width:10%">Due Date</th>
                                            <th style="width:10%">Request Status</th>
                                            <th style="width:10%" data-orderable="false">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                      $nm = 0;
                                      while($row_rsPayRequest = $query_rsPayRequest->fetch())
                                      {
                                        $nm = $nm + 1;
                                        $requestid = $row_rsPayRequest['id'];
                                        $project = $row_rsPayRequest['projname'];
                                        $projid = $row_rsPayRequest['projid'];
                                        $category = $row_rsPayRequest['itemcategory'];
                                        $itemid = $row_rsPayRequest['itemid'];
                                        $reqstatus = $row_rsPayRequest['status'];
                                        $reqdate = $row_rsPayRequest['daterequested'];
                                        $requestedby = $row_rsPayRequest['requestedby'];
                                        $prjsc = $row_rsPayRequest["projcommunity"];
                                        $prjwd = $row_rsPayRequest["projlga"];
                                        $Prjloc = $row_rsPayRequest["projstate"];

                                        $reqamnt = number_format($row_rsPayRequest['amountrequested'], 2);

                                        $duedate = strtotime($reqdate."+ 5 days");
                                        $approvalduedate = date("d M Y",$duedate);
                                        $payrdate = strtotime($reqdate);
                                        $paymentreqdate = date("d M Y",$payrdate);

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

                                        $query_reqstatus = $db->prepare("SELECT status FROM tbl_payment_status WHERE id = '$reqstatus'");
                                        $query_reqstatus->execute();
                                        $row_reqstatus = $query_reqstatus->fetch();
                                        $requeststatus = $row_reqstatus["status"];

                                        $query_requester = $db->prepare("SELECT fullname FROM users u left join tbl_projteam2 t on t.ptid=u.pt_id WHERE userid = '$requestedby'");
                                        $query_requester->execute();
                                        $row_requester = $query_requester->fetch();
                                        $requester = $row_requester["fullname"];
                                        ?>
                                        <tr data-toggle="collapse" data-target=".order<?php echo $nm; ?>" style="background-color:#eff9ca">
                                          <input type="hidden" name="requestid" id="requestid" value="<?php echo $requestid; ?>"/>
                                          <td align="center" class="mb-0">
                                            <button class="btn btn-link" title="Click once to expand and Click twice to Collapse!!">
                                              <i class="fa fa-plus-square" style="font-size:16px"></i>
                                            </button>
                                          </td>
                                          <td align="center"><?php echo $nm; ?></td>
                                          <td><?php echo $itemcat; ?></td>
                                          <td><?php echo $itemname; ?></td>
                                          <td><?php echo $reqamnt; ?></td>
                                          <td><?php echo $requester; ?></td>
                                          <td><?php echo $paymentreqdate; ?></td>
                                          <td><?php echo $approvalduedate; ?></td>
                                          <td><?php echo $requeststatus; ?></td>
                                          <td>
                                            <div align="center">
                                              <a href="requestaction?request=<?php echo $requestid; ?>&action=2" alt="View Project Details" name="view" width="16" height="16" id="view" data-toggle="tooltip" data-placement="bottom" title="Approve This Request"><i class="fa fa-check-square-o fa-2x text-success" aria-hidden="true"></i></a>
                                              <a href="requestaction?request=<?php echo $requestid; ?>&action=3" alt="Edit Project" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Reject This Request"><i class="fa fa-window-close fa-2x text-danger" aria-hidden="true"></i></a>
                                            </div>

                                                           <!--  <select class="form-control" name="approvalstatus" id="approvalstatus" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" onchange="javascript:CallApprovalForm(<?php //echo $requestid; ?>)">
                                                                  <option value="" selected>..Select..</option>
                                                                  <option value="2">Approve</option>
                                                                  <option value="3">Reject</option>
                                                              </select>-->
                                          </td>
                                        </tr>
                                        <?php
                                        $query_rsProjSc = $db->prepare("SELECT state FROM tbl_state WHERE id = '$Prjsc'");
                                        $query_rsProjSc->execute();
                                        $row_rsProjSc = $query_rsProjSc->fetch();

                                        $query_rsProjWd = $db->prepare("SELECT state FROM tbl_state WHERE id = '$Prjwd'");
                                        $query_rsProjWd->execute();
                                        $row_rsProjWd = $query_rsProjWd->fetch();

                                        $projsubcounties = explode(",",$prjsc);
                                        $projwards = explode(",",$prjwd);
                                        $projlocs = explode(",",$Prjloc);
                                        $projlocation = "";
                                        //$projlocation .= $projsubcounties." Sub-County; ".$projwards." Ward; ".$projlocs." Location; ";
                                        /* foreach($projsubcounties as $projsubcounty){ $projlocation .= $projsubcounty." Sub-County; ";}

                                        foreach($projwards as $projward){ $projlocation .= $projward." Ward; ";}

                                        $projlocations = array();
                                        foreach($projlocs as $projloc){
                                          $query_rsProjLoc = $db->prepare("SELECT state FROM tbl_state WHERE id = '$projloc'");
                                          $query_rsProjLoc->execute();
                                          $row_rsProjLoc = $query_rsProjLoc->fetch();
                                          $projlocations[]= $row_rsProjLoc["state"];
                                        } */

                                        //$projlocation .= $projsubcounties." Sub-County; ".$projwards." Ward; ".$projlocs." Location; ";

                                        $query_TotalPaid = $db->prepare("SELECT SUM(amountpaid) AS totalpaid FROM tbl_payments_disbursed INNER JOIN tbl_payments_request ON tbl_payments_disbursed.reqid=tbl_payments_request.id WHERE tbl_payments_request.projid = '$projid'");
                                        $query_TotalPaid->execute();
                                        $row_TotalPaid = $query_TotalPaid->fetch();
                                        if(empty($row_TotalPaid["totalpaid"]) || $row_TotalPaid["totalpaid"]==''){
                                          $totalpaid = 0;
                                        }else{
                                          $totalpaid = $row_TotalPaid["totalpaid"];
                                        }

                                        $query_projteam = $db->prepare("SELECT * FROM tbl_projmembers t left join tbl_projteam2 m ON m.ptid=t.ptid left join tbl_project_team_roles r on r.id=t.role WHERE t.projid = '$projid'");
                                        $query_projteam->execute();

                                        $query_milestone_status = $db->prepare("SELECT * FROM tbl_status WHERE statusid = '$itemstatus'");
                                        $query_milestone_status->execute();
                                        $row_milestone_status = $query_milestone_status->fetch();
                                        $itemstatus = $row_milestone_status['statusname'];

                                        $projstatusid = $row_rsPayRequest["projstatus"];
                                        $query_proj_status = $db->prepare("SELECT * FROM tbl_status WHERE statusid = '$projstatusid'");
                                        $query_proj_status->execute();
                                        $row_proj_status = $query_proj_status->fetch();
                                        $projstatus = $row_proj_status['statusname'];

                                        $query_projcost = $row_rsPayRequest["projstatus"];
                                        $query_projcost = $db->prepare("SELECT SUM(d.unit_cost * d.units_no) as cost FROM tbl_project_tender_details d INNER JOIN tbl_task t ON t.tkid = d.tasks WHERE t.projid=:projid");
                                        $query_projcost->execute(array(":projid" => $projid));
                                        $row_projcost = $query_projcost->fetch();
                                        $projcost = $row_projcost['cost'];


                                        $totalbalbudget = $projcost - $totalpaid;

                                        $absrate = ($totalpaid/$projcost)*100;
                                        ?>
                                        <div class="collapse order<?php echo $nm; ?>"><b><i>
                                          <tr class="collapse order<?php echo $nm; ?>" style="background-color:#b8f9cb">
                                            <td COLSPAN=1></td>
                                            <td COLSPAN=2>Request ID</td>
                                            <td COLSPAN=2><?php echo $row_rsPayRequest['requestid']; ?></td>
                                            <?php if($category == 1){ ?>
                                            <td COLSPAN=2>Task Status</td>
                                            <?php }else{ ?>
                                            <td COLSPAN=2>Milestone Status</td>
                                            <?php } ?>
                                            <td COLSPAN=3><?php echo $itemstatus;?></td>
                                          </tr>

                                          <tr class="collapse order<?php echo $nm; ?>" style="background-color:#b8f9cb">
                                            <td COLSPAN=1></td>
                                            <td COLSPAN=2>Project Name</td>
                                            <td COLSPAN=2><?php echo $project;?></td>
                                            <td COLSPAN=2>Project Status</td>
                                            <td COLSPAN=3><?php echo $projstatus;?></td>
                                          </tr>

                                          <tr class="collapse order<?php echo $nm; ?>" style="background-color:#b8f9cb">
                                            <td COLSPAN=1></td>
                                            <td COLSPAN=2>Project Type</td>
                                            <td COLSPAN=2><?php echo $row_rsPayRequest["projtype"];?></td>
                                            <td COLSPAN=2>Project Location</td>
                                            <td COLSPAN=3><?php echo $projlocation;?></td>
                                          </tr>

                                          <tr class="collapse order<?php echo $nm; ?>" style="background-color:#b8f9cb">
                                            <td COLSPAN=1></td>
                                            <td COLSPAN=2>Project Cost</td>
                                            <td COLSPAN=2>Ksh.<?php echo number_format($projcost, 2);?></td>
                                            <td COLSPAN=2>Project Amount Paid</td>
                                            <td COLSPAN=3>Ksh.<?php echo number_format($totalpaid, 2);?></td>
                                          </tr>

                                          <tr class="collapse order<?php echo $nm; ?>" style="background-color:#b8f9cb">
                                            <td COLSPAN=1></td>
                                            <td COLSPAN=2>Project Balance</td>
                                            <td COLSPAN=2>Ksh.<?php echo number_format($totalbalbudget, 2);?></td>
                                            <td COLSPAN=2>Project Absorption Rate</td>
                                            <td COLSPAN=3><?php echo $absrate."%";?></td>
                                          </tr>
                                          <?php while($row_projteam = $query_projteam->fetch()){
                                            $role = $row_projteam["role"];
                                            $fullname = $row_projteam["fullname"];
                                            ?>
                                            <tr class="collapse order<?php echo $nm; ?>" style="background-color:#cfcfcf">
                                              <td COLSPAN=1></td>
                                              <td COLSPAN=2><?=$role?></td>
                                              <td COLSPAN=2><?php echo $row_projteam["title"].". ".$fullname;?></td>
                                              <td COLSPAN=2>Project Team Leader Phone</td>
                                              <td COLSPAN=3><?php echo $row_projteam["phone"];?></td>
                                            </tr>
                                          <?php
                                          }
                                          ?>
                                          </i></b>
                                        </div>
                                          <?php
                                      }
                                      ?>
                                    </tbody>
                                </table>
                              </div>
                            </div>
                            <div id="menu1" class="tab-pane fade">
                              <div style="color:#333; background-color:#EEE; width:100%; height:30px">
                                <h4><i class="fa fa-list" style="font-size:25px;color:blue"></i> Project Payment Requests Approved</h4>
                              </div>
                              <div class="table-responsive">
                                  <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                      <thead>
                                          <tr id="colrow">
                                              <th style="width:3%">#</th>
                                              <th style="width:8%">Request ID</th>
                                              <th style="width:10%">Category</th>
                                              <th style="width:29%">Item Name</th>
                                              <th style="width:10%">Amount(Ksh)</th>
                                              <th style="width:10%">Approver</th>
                                              <th style="width:10%">Date Approved</th>
                                              <th style="width:10%">Payment Due Date</th>
                                              <th style="width:10%">Payment Status</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                        <?php
                                        $nm = 0;
                                        while($rsPayApproved = $query_rsPayApproved->fetch())
                                        {
                                          $nm = $nm + 1;
                                          $approvedid = $rsPayApproved['id'];
                                          $reqidno = $rsPayApproved['requestid'];
                                          $project = $rsPayApproved['projname'];
                                          $projid = $rsPayApproved['projid'];
                                          $category = $rsPayApproved['itemcategory'];
                                          $itemid = $rsPayApproved['itemid'];
                                          $approvedstatus = $rsPayApproved['status'];
                                          $approveddate = $rsPayApproved['approvaldate'];
                                          $approvedby = $rsPayApproved['approvalby'];
                                          $approvedamnt = number_format($rsPayApproved['amountrequested'], 2);

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
                                          $approver = $row_approver["fullname"];
                                          ?>
                                          <tr>
                                            <td align="center"><?php echo $nm; ?></td>
                                            <td><?php echo $reqidno; ?></td>
                                            <td><?php echo $itemcat; ?></td>
                                            <td><?php echo $itemname; ?></td>
                                            <td><?php echo $approvedamnt; ?></td>
                                            <td><?php echo $approver; ?></td>
                                            <td><?php echo $paymentappdate; ?></td>
                                            <td><?php echo $paymentduedate; ?></td>
                                            <?php if($approvedstatus == 6){?>
                                              <td style="color:#F44336"><b><?php echo $approvalstatus; ?></b></td>
                                            <?php }else{?>
                                              <td><?php echo $approvalstatus; ?></td>
                                            <?php }?>
                                          </tr>
                                        <?php
                                        }
                                        ?>
                                      </tbody>
                                  </table>
                              </div>
                           </div>
                            <div id="menu2" class="tab-pane fade">
                              <div style="color:#333; background-color:#EEE; width:100%; height:30px">
                                <h4><i class="fa fa-list" style="font-size:25px;color:#F44336"></i> Project Payment Requests Rejected</h4>
                              </div>
                                <div class="table-responsive">
                                  <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                      <thead>
                                          <tr class="bg-red">
                                              <th style="width:3%">#</th>
                                              <th style="width:7%">Request ID</th>
                                              <th style="width:23%">Project</th>
                                              <th style="width:10%">Category</th>
                                              <th style="width:20%">Item Name</th>
                                              <th style="width:10%">Amount (Ksh)</th>
                                              <th style="width:11%">Rejected By</th>
                                              <th style="width:8%">Date Rejected</th>
                                              <th style="width:8%">Reason</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                        <?php
                                        $nm = 0;
                                        while($rsPayRejected = $query_rsPayRejected->fetch())
                                        {
                                          $nm = $nm + 1;
                                          $rejectedid = $rsPayRejected['id'];
                                          $reqidno = $rsPayRejected['requestid'];
                                          $project = $rsPayRejected['projname'];
                                          $projid = $rsPayRejected['projid'];
                                          $category = $rsPayRejected['itemcategory'];
                                          $itemid = $rsPayRejected['itemid'];
                                          $rejectedstatus = $rsPayRejected['status'];
                                          $rejecteddate = $rsPayRejected['approvaldate'];
                                          $rejectedby = $rsPayRejected['approvalby'];
                                          $rejectedamnt = number_format($rsPayRejected['amountrequested'], 2);

                                          $payrejdate = strtotime($rejecteddate);
                                          $paymentrejdate = date("d M Y",$payrejdate);

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

                                          $query_rejstatus = $db->prepare("SELECT status FROM tbl_payment_status WHERE id = '$rejectedstatus'");
                                          $query_rejstatus->execute();
                                          $row_rejstatus = $query_rejstatus->fetch();
                                          $rejectionstatus = $row_rejstatus["status"];

                                          $query_rejecter = $db->prepare("SELECT fullname FROM admin WHERE username = '$rejectedby'");
                                          $query_rejecter->execute();
                                          $row_rejecter = $query_rejecter->fetch();
                                          $rejecter = $row_rejecter["fullname"];

                                          $query_rejcomments = $db->prepare("SELECT comments FROM tbl_payment_request_comments WHERE reqid = '$rejectedid'");
                                          $query_rejcomments->execute();
                                          $row_rejcomments = $query_rejcomments->fetch();
                                          $rejcomments = $row_rejcomments["comments"];
                                          ?>
                                          <tr>
                                            <td align="center"><?php echo $nm; ?></td>
                                            <td><?php echo $reqidno; ?></td>
                                            <td><?php echo $project; ?></td>
                                            <td><?php echo $itemcat; ?></td>
                                            <td><?php echo $itemname; ?></td>
                                            <td><?php echo $rejectedamnt; ?></td>
                                            <td><?php echo $rejecter; ?></td>
                                            <td><?php echo $paymentrejdate; ?></td>
                                            <td>
                                              <a href="#" style="color:#06C; padding-left:2px; padding-right:2px; font-weight:bold" onclick="javascript:CallRequestComments(<?php echo $rejectedid; ?>)" data-toggle="tooltip" data-placement="bottom" title="Click Here to View Comments"><i class="fa fa-comments fa-2x" aria-hidden="true"></i></a>
                                            </td>
                                          </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                  </table>
                                </div>
                            </div>
                            <div id="menu3" class="tab-pane fade">
                              <div style="color:#333; background-color:#EEE; width:100%; height:30px">
                                <h4><i class="fa fa-list" style="font-size:25px;color:green"></i> Project Payment Requests Paid</h4>
                              </div>
                              <div class="table-responsive">
                                      <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                          <thead>
                                              <tr class="bg-green">
                                                  <th style="width:3%">#</th>
                                                  <th style="width:10%">Request ID</th>
                                                  <th style="width:10%">Reference ID</th>
                                                  <th style="width:10%">Category</th>
                                                  <th style="width:25%">Item Name</th>
                                                  <th style="width:10%">Amount paid (Ksh)</th>
                                                  <th style="width:15%">Paid By</th>
                                                  <th style="width:10%">Date Paid</th>
                                                  <th style="width:7%">Receipt</th>
                                              </tr>
                                          </thead>
                                          <tbody>
                                            <?php
                                            $nm = 0;
                                            while($rsPayReceived = $query_rsPayReceived->fetch())
                                            {
                                              $nm = $nm + 1;
                                              $receivedid = $rsPayReceived['id'];
                                              $reqidno = $rsPayReceived['req'];
                                              $refno = $rsPayReceived['refid'];
                                              $category = $rsPayReceived['itemcategory'];
                                              $itemid = $rsPayReceived['ritem'];
                                              $receivedstatus = $rsPayReceived['status'];
                                              $pmntdate = $rsPayReceived['datepaid'];
                                              $paidbyid = $rsPayReceived['paidby'];
                                              $receiveddate = $rsPayReceived['daterecorded'];
                                              $receivedby = $rsPayReceived['recordedby'];
                                              $amountpaid = number_format($rsPayReceived['amountpaid'], 2);

                                              $paymdate = strtotime($pmntdate);
                                              $paymentdate = date("d M Y",$paymdate);

                                              $payrecdate = strtotime($receiveddate);
                                              $paymentrecdate = date("d M Y",$payrecdate);

                                              $row_file = "";
                                              if($category == 1){
                                                $itemcat = "Task";
                                                $query_item = $db->prepare("SELECT * FROM tbl_task WHERE tkid = '$itemid'");
                                                $query_item->execute();
                                                $row_item = $query_item->fetch();
                                                $itemname = $row_item["task"];
                                                $itemstatus = $row_item["status"];

                                                $query_file = $db->prepare("SELECT floc FROM tbl_files WHERE tkid = '$itemid'");
                                                $query_file->execute();
                                                $row_file = $query_file->fetch();
                                              }else{
                                                $itemcat = "Milestone";
                                                $query_item = $db->prepare("SELECT * FROM tbl_milestone WHERE msid = '$itemid'");
                                                $query_item->execute();
                                                $row_item = $query_item->fetch();
                                                $itemname = $row_item["milestone"];
                                                $itemstatus = $row_item["status"];

                                                $query_file = $db->prepare("SELECT floc FROM tbl_files WHERE form_id = '$itemid'");
                                                $query_file->execute();
                                                $row_file = $query_file->fetch();
                                              }

                                              $query_user = $db->prepare("SELECT fullname FROM tbl_projteam2 t left join users u on u.pt_id=t.ptid WHERE userid = '$paidbyid'");
                                              $query_user->execute();
                                              $row_user = $query_user->fetch();
                                              $paidby = $row_user["fullname"];
                                              ?>
                                              <tr>
                                                <td align="center"><?php echo $nm; ?></td>
                                                <td><?php echo $reqidno; ?></td>
                                                <td><?php echo $refno; ?></td>
                                                <td><?php echo $itemcat; ?></td>
                                                <td><?php echo $itemname; ?></td>
                                                <td><?php echo $amountpaid; ?></td>
                                                <td><?php echo $paidby; ?></td>
                                                <td><?php echo $paymentdate; ?></td>
                                                <td>
                                                  <?php if(!empty($row_file)) {?>
                                                  <a href="<?php echo (!empty($row_file)) ? $row_file['floc'] : ""; ?>" style="color:#06C; padding-left:2px; padding-right:2px; font-weight:bold" data-toggle="tooltip" data-placement="bottom" title="Click Here to Download Payment Receipt" target="new"><i class="fa fa-cloud-download fa-2x" aria-hidden="true"></i></a>
                                                <?php } ?>
                                                </td>
                                              </tr>
                                            <?php
                                            }
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
    </section>
    <!-- end body  -->


    <!-- Modal Receive Payment -->
    <div class="modal fade" id="actModal" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header" style="background-color:#03A9F4">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h3 class="modal-title" align="center">
              <font color="#FFF">PAYMENT REQUEST ACTION FORM</font>
            </h3>
          </div>
          <form class="tagForm" action="paymentapproval" method="post" id="payment-approval-form" enctype="multipart/form-data" autocomplete="off">
            <div class="modal-body">
              <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="card">
                    <div class="body">
                      <div class="table-responsive" style="background:#eaf0f9">
                        <div id="actionformcontent">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
            <div class="modal-footer">
              <div class="col-md-4">
              </div>
              <div class="col-md-4" align="center">
                <input name="save" type="submit" class="btn btn-success waves-effect waves-light" id="tag-form-submit" value="Save" />
                <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
                <input type="hidden" name="username" id="username" value="<?php echo $user_name; ?>" />
              </div>
              <div class="col-md-4">
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- #END# Modal Receive Payment-->

    <!-- Modal -->
    <div class="modal fade" id="commModal" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header" style="background-color:#03A9F4">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h3 class="modal-title" align="center">
              <font color="#FFF">Funds/Payment Request Comments</font>
            </h3>
          </div>
          <div class="modal-body" id="commentcontent">

          </div>
          <div class="modal-footer">
            <div class="col-md-4">
            </div>
            <div class="col-md-4" align="center">
              <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
            </div>
            <div class="col-md-4">
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- #END# Modal -->
<?php
} else {
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');
?>


	<script type="text/javascript">
  $(document).ready(function() {
    $('#approvalstatus').on('change', function() {
      var actionID = $(this).val();
      var reqid = $("#requestid").val();

      $.ajax({
        type: 'post',
        url: 'callapprovalform.php',
        data: "actid=" + actionID + "&rqid=" + reqid,
        success: function(data) {
          $('#actionformcontent').html(data);
          $("#actModal").modal({
            backdrop: "static"
          });
        }
      });
    })
  })
		function CallRequestComments(id) {
			$.ajax({
				type: 'post',
				url: 'getreqcomments.php',
				data: {
					reqid: id
				},
				success: function(data) {
					$('#commentcontent').html(data);
					$("#commModal").modal({
						backdrop: "static"
					});
				}
			});
		}

    $(document).ready(function() {
      $('#payment-approval-form').on('submit', function(event) {
        event.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
          type: "POST",
          url: "paymentapproval.php",
          data: form_data,
          dataType: "json",
          success: function(response) {
            if (response) {
              alert('Record successfully saved');
              window.location.reload();
            }
          },
          error: function() {
            alert('Error');
          }
        });
        return false;
      });
    });
	</script>
