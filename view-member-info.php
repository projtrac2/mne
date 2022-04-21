<?php
require('functions/strategicplan.php');
$pageName = "Strategic Plans";
$replacement_array = array(
    'planlabel' => "CIDP",
    'plan_id' => base64_encode(6),
);

$page = "view";

require('includes/head.php');
if ($permission) {
    $pageTitle = "Project Team";

    try {

        $editFormAction = $_SERVER['PHP_SELF'];
        if (isset($_SERVER['QUERY_STRING'])) {
            $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
        }

        if (isset($_POST["catid"])) {
            $catid = $_POST['catid'];
            $startdate = $_POST['startdate'];
            $comments = $_POST['comments'];
            $employeeid = $_POST['employee'];
            $reassigneeid = $_POST['reassignee'];
            $username = $_POST['username'];
            $leavedays = $_POST['leavedays'];
            $remainingleavedays = $_POST['remleavedays'];
            $current_date = date("Y-m-d");
            function add_work_days($stdate, $day)
            {
                if ($day == 0)
                    return $stdate;
                $stdate->add(new DateInterval('P1D'));
                if (!in_array($stdate->format('N'), array('6', '7')))
                    $day--;
                return add_work_days($stdate, $day);
            }
            $stdate  = add_work_days(new DateTime(), $leavedays);
            $leavenddate = $stdate->format('Y-m-d');
            $sdate = strtotime($startdate);
            $leavestartdate = date("d m Y", $sdate);

            if (!empty($leavedays)) {
                $insertSQL = $db->prepare("INSERT INTO tbl_employee_leave (employee, leavecategory, days, startdate, enddate, comments, added_by, date_added) VALUES (:ptid, :catid, :days, :startdate, :enddate, :comments, :user, :recorddate)");
                $insertSQL->execute(array(':ptid' => $employeeid, ':catid' => $catid, ':days' => $leavedays, ':startdate' => $startdate, ':enddate' => $leavenddate, ':comments' => $_POST['comments'],  ':user' => $username, ':recorddate' => $current_date));
                $last_id = $db->lastInsertId();

                if ($insertSQL->rowCount() == 1) {
                    $availability = 0;
                    $leave = 1;
                    $queryupdate = $db->prepare("UPDATE tbl_projteam2 SET availability=:availability WHERE ptid=:ptid");
                    $queryupdate->execute(array(":availability" => $availability, ":ptid" => $employeeid));

                    $query_rsProjLeaved =  $db->prepare("SELECT p.projid AS prjid FROM tbl_projects p inner join tbl_projmembers m on m.projid=p.projid WHERE m.ptid = '$employeeid' AND (p.projstatus <> 'Completed' OR p.projstatus <> 'Cancelled' OR p.projstatus <> 'On Hold')");
                    $query_rsProjLeaved->execute();

                    while ($row_rsProjLeaved = $query_rsProjLeaved->fetch()) {
                        $LProjid = $row_rsProjLeaved["prjid"];
                        $queryreassign = $db->prepare("UPDATE tbl_projmembers SET ptleave=:leave, reassignee=:reassignee, datereassigned=:rdate WHERE ptid=:ptid AND projid=:projid");
                        $queryreassign->execute(array(":leave" => $leave, ":reassignee" => $reassigneeid, ":rdate" => $current_date, ":ptid" => $employeeid, ":projid" => $LProjid));
                    }

                    $query_rsLeavename =  $db->prepare("SELECT leavename FROM tbl_employees_leave_categories WHERE id = '$catid'");
                    $query_rsLeavename->execute();
                    $leavename = $query_rsLeavename->fetch();

                    $filecategory = "Leave";
                    $fileid = $last_id;
                    $reason = $leavename["leavename"];

                    $date_uploaded = date('Y-m-d');
                    if (!empty($_FILES['leavefile']['name'])) {
                        $filename = basename($_FILES['leavefile']['name']);
                        $ext = substr($filename, strrpos($filename, '.') + 1);

                        if (($ext != "exe") && ($_FILES["leavefile"]["type"] != "application/x-msdownload")) {
                            $newname = $employeeid . "-" . $fileid . "-" . $filename;
                            $filepath = "uploads/leave/" . $newname;

                            if (!file_exists($filepath)) {
                                if (move_uploaded_file($_FILES['leavefile']['tmp_name'], $filepath)) {
                                    $qry2 = $db->prepare("INSERT INTO tbl_files (`filename`, `projstage`, `fcategory`, `catid`, `ftype`, `reason`, `floc`, `uploaded_by`, `date_uploaded`) VALUES (:fname,:projstage, :filecategory, :catid, :ext, :reason, :floc, :myUser, :date_uploaded)");
                                    $qry2->execute(array(':fname' => $newname, ':projstage' => 0, ':filecategory' => $filecategory, ':catid' => $fileid, ':ext' => $ext, ':reason' => $reason, ':floc' => $filepath, ':myUser' => $username, ":date_uploaded" => $date_uploaded));
                                }
                            } else {
                                $msg = 'File you are uploading already exists, try another file!!';
                                $results = "<script type=\"text/javascript\">
									swal({
										title: \"Error!\",
										text: \" $msg \",
										type: 'Danger',
										timer: 3000,
                                        'icon':'warning',
										showConfirmButton: false });
								</script>";
                            }
                        } else {
                            $msg = 'This file type is not allowed, try another file!!';
                            $results = "<script type=\"text/javascript\">
								swal({
									title: \"Error!\",
									text: \" $msg \",
									type: 'Danger',
									timer: 3000,
                                    'icon':'warning',
									showConfirmButton: false });
								</script>";
                        }
                    } else {
                        $msg = 'You have not attached any file!!';
                        $results = "<script type=\"text/javascript\">
							swal({
								title: \"Error!\",
								text: \" $msg \",
								type: 'Danger',
								timer: 3000,
                                'icon':'warning',
								showConfirmButton: false });
						</script>";
                    }


                    $msg = 'Record successfully updated.';
                    $results = "<script type=\"text/javascript\">
						swal({
							title: \"Success!\",
							text: \" $msg\",
							type: 'Success',
							timer: 2000,
                            'icon':'success',
							showConfirmButton: false });
						setTimeout(function(){
							window.location.href = 'view-project-team-info.php?staff=$employeeid';
						}, 2000);
					</script>";
                }
            } else {
                echo "empty leave days ";
            }
        }

        if (isset($_SESSION['MM_Username'])) {
            $user = $_SESSION['MM_Username'];
        }

        if (isset($_GET['staff'])) {
            $ptid = $_GET['staff'];
        }

        $query_rsStaff =  $db->prepare("SELECT t.*, t.designation AS design, d.designation AS desgn FROM tbl_projteam2 t inner join tbl_pmdesignation d ON t.designation=d.position WHERE t.ptid = '$ptid'");
        $query_rsStaff->execute();
        $row_rsStaff = $query_rsStaff->fetch();
        $count_row_rsStaff = $query_rsStaff->rowCount();

        $mydesign = $myministry = $mydept = $emplststus = "";

        if ($$count_row_rsStaff > 0) {
            $mydesign = $row_rsStaff['design'];
            $myministry = $row_rsStaff['ministry'];
            $mydept = $row_rsStaff['department'];
        }


        if ($row_rsStaff["availability"] == 1) {
            $emplststus =  "<font color='indigo'>Available</font>";
        } else {
            $emplststus =  "<font color='deep-orange'>Unavailable</font>";
        }

        $query_rsLeave =  $db->prepare("SELECT id, leavename FROM tbl_employees_leave_categories ORDER BY id ASC");
        $query_rsLeave->execute();
        $row_rsLeave = $query_rsLeave->fetch();

        $query_mbrprojs =  $db->prepare("SELECT projid FROM tbl_projmembers WHERE ptid='$ptid' GROUP BY projid");
        $query_mbrprojs->execute();
        $count_mbrprojs = $query_mbrprojs->rowCount();

        $query_rsOtherEMployees =  $db->prepare("SELECT * FROM tbl_projteam2 WHERE ptid<>'$ptid'  AND ministry='$myministry' AND availability=1");
        $query_rsOtherEMployees->execute();
        $row_rsOtherEMployees = $query_rsOtherEMployees->fetch();
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
                            <a href="projteam" class="btn btn-warning" style="height:27px; ; margin-top:-1px; vertical-align:center">Go Back</a>
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
                        <div class="body">
                            <fieldset class="scheduler-border" style="font-size:15px">
                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">DETAILS</legend>
                                <div class="col-md-12" align="left">
                                    <div class="form-line">
                                        <img src="<?php echo $row_rsStaff['floc']; ?>" id="mbr" class="img img-rounded" width="120" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <i class="fa fa-user" aria-hidden="true"></i> Staff Full Name: <font color="indigo"><?php echo $row_rsStaff["title"] . ". " . $row_rsStaff["fullname"] . " (" . $row_rsStaff["desgn"] . ")"; ?></font>
                                </div>
                                <div class="col-md-4">
                                    <label>Current Status: <?php echo $emplststus; ?></label>
                                </div>
                                <div class="col-md-4">
                                    <label>Phone Number: <font color="indigo"><?php echo $row_rsStaff['phone'] ?></font></label>
                                </div>
                                <div class="col-md-4">
                                    <label>Email Address: <font color="indigo"><?php echo $row_rsStaff['email'] ?></font></label>
                                </div>
                                <input name="ptid" type="hidden" id="ptid" value="<?php echo $ptid; ?>" />
                                <?php
                                // Note editing feature applies for leave $file_rights->edit
                                if ($row_rsStaff["availability"] == 1 && $file_rights->edit) {
                                ?>
                                    <div class="col-md-6">
                                        <label>Leave Type:</label>
                                        <div class="form-line">
                                            <select name="leave" id="leave" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                                                <option value="">... Select Leave ...</option>
                                                <?php
                                                do {
                                                ?>
                                                    <option value="<?php echo $row_rsLeave['id'] ?>"><?php echo $row_rsLeave['leavename'] ?></option>
                                                <?php
                                                } while ($row_rsLeave = $query_rsLeave->fetch());
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                <?php
                                } else if ($row_rsStaff["availability"] != 1) {
                                    $query_rsLvDetails =  $db->prepare("SELECT C.leavename, L.days, L.startdate, L.enddate, L.comments FROM tbl_employee_leave L INNER JOIN tbl_employees_leave_categories C ON L.leavecategory=C.id WHERE L.employee = '$ptid'  ORDER BY L.id DESC LIMIT 1");
                                    $query_rsLvDetails->execute();
                                    $row_rsLvDetails = $query_rsLvDetails->fetch();
                                ?>
                                    <div class="col-md-3">
                                        <label>Leave Type: <font color="indigo"><?php echo $row_rsLvDetails["leavename"]; ?></font></label>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Leave Days: <font color="indigo"><?php echo $row_rsLvDetails["days"]; ?> Working Days</font></label>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Leave Start Date: <font color="indigo"><?php echo date("d M Y", strtotime($row_rsLvDetails["startdate"])); ?></font></label>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Leave End Date: <font color="indigo"><?php echo date("d M Y", strtotime($row_rsLvDetails["enddate"])); ?></font></label>
                                    </div>
                                    <div class="col-md-12">
                                        <label>Leave Notes: <font color="indigo"><?php echo $row_rsLvDetails["comments"]; ?></font></label>
                                    </div>
                                <?php
                                }
                                ?>
                            </fieldset>

                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">ASSIGNED PROJECTS</legend>
                                <div class="row clearfix">
                                    <div class="col-md-12 table-responsive">
                                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                            <thead>
                                                <tr id="colrow">
                                                    <td width="3%"><strong>SN</strong></td>
                                                    <td width="40%"><strong>Project Name</strong></td>
                                                    <td width="11%"><strong>Project Status</strong></td>
                                                    <td width="13%"><strong>Start Date</strong></td>
                                                    <td width="13%"><strong>End Date</strong></td>
                                                    <td width="10%"><strong>Project Role</strong></td>
                                                    <?php
                                                    // if ($row_rsStaff["availability"] == 0) {
                                                    ?>
                                                    <td width="10%"><strong>Responsible</strong></td>
                                                    <?php
                                                    // }
                                                    ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $nm = 0;
                                                while ($row_mbrprojs = $query_mbrprojs->fetch()) {
                                                    $nm = $nm + 1;
                                                    $projid = $row_mbrprojs['projid'];

                                                    $query_rsProjects =  $db->prepare("SELECT * FROM tbl_projects WHERE projid='$projid'");
                                                    $query_rsProjects->execute();
                                                    $row_rsProjects = $query_rsProjects->fetch();
                                                    $statusid = $row_rsProjects["projstatus"];

                                                    $query_projroles =  $db->prepare("SELECT r.role FROM tbl_projmembers m inner join tbl_project_team_roles r on r.id=m.role WHERE ptid='$ptid' and projid='$projid'");
                                                    $query_projroles->execute();
                                                    $roles = array();
                                                    while ($row_projroles = $query_projroles->fetch()) {
                                                        $rl = $row_projroles["role"];
                                                        $roles[] = $rl;
                                                    }

                                                    if ($statusid == 0) {
                                                        $projstatus = "Under Planning";
                                                    } else {
                                                        $query_projstatus =  $db->prepare("SELECT statusname FROM tbl_status WHERE statusid='$statusid'");
                                                        $query_projstatus->execute();
                                                        $row_projstatus = $query_projstatus->fetch();
                                                        $projstatus = $row_projstatus['statusname'];
                                                    }

                                                    $project = $row_rsProjects['projname'];
                                                    $projstage = $row_rsProjects['projstage'];

                                                    $query_projresp = $db->prepare("SELECT T.title, T.fullname FROM tbl_projteam2 T INNER JOIN tbl_projmembers M ON T.ptid=M.reassignee WHERE M.projid='$projid' AND M.ptleave=1");
                                                    $query_projresp->execute();
                                                    $row_projresp = $query_projresp->fetch();
                                                    $count_row_projresp = $query_projresp->rowCount();

                                                    if ($projstage > 9) {
                                                        $query_tenderdates = $db->prepare("SELECT startdate, enddate FROM tbl_tenderdetails WHERE projid='$projid'");
                                                        $query_tenderdates->execute();
                                                        $row_tender = $query_tenderdates->fetch();

                                                        $prjsdate = $row_tender['startdate'];
                                                        $prjedate = $row_tender['enddate'];
                                                    } else {
                                                        $prjsdate = $row_rsProjects['projstartdate'];
                                                        $prjedate = $row_rsProjects['projenddate'];
                                                    }

                                                    $projsdate = date("d M Y", strtotime($prjsdate));
                                                    $projedate = date("d M Y", strtotime($prjedate));
                                                ?>
                                                    <tr>
                                                        <td><?php echo $projid; ?></td>
                                                        <td><?php echo $project; ?></td>
                                                        <td align="center"><?php echo $projstatus; ?></td>
                                                        <td><?php echo $projsdate; ?></td>
                                                        <td><?php echo $projedate; ?></td>
                                                        <td>
                                                            <?php
                                                            foreach ($roles as $rl) {
                                                                echo $rl . ", ";
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?= ($row_rsStaff["availability"] == 0 && $count_row_projresp > 0) ? $row_projresp["title"] . '.' . $row_projresp["fullname"] : "N/A"; ?>
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- #END# File Upload | Drag & Drop OR With Click & Choose -->
                            </fieldset>

                            <!-- end body -->
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- end body  -->


    <!-- Leave modal -->
    <div class="modal fade" tabindex="-1" role="dialog" id="staffleaveModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-blue">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" align="center"><i class="glyphicon glyphicon-edit"></i> Leave Authorization</h4>
                </div>
                <form class="leaveForm" action="view-project-team-info.php?staff=<?= $ptid ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                    <div class="modal-body form-horizontal" style="max-height:500px; overflow:auto;">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="card">
                                    <div class="body">
                                        <div class="table-responsive" style="background:#eaf0f9">
                                            <div id="leaveformcontent"></div>
                                            <div class="col-md-6">
                                                <label>Leave Days</label>
                                                <div class="form-line">
                                                    <input name="leavedays" type="number" class="form-control" style="border:#CCC thin solid; border-radius: 5px; padding-left:5px" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Leave Start Date</label>
                                                <div class="form-line">
                                                    <input name="startdate" type="date" class="form-control" style="border:#CCC thin solid; border-radius: 5px; padding-left:5px" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Reassign Projects</label>
                                                <div class="form-line">
                                                    <select name="reassignee" id="reassignee" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                                                        <option value="">... Select a Member ...</option>
                                                        <?php
                                                        do {
                                                        ?>
                                                            <option value="<?php echo $row_rsOtherEMployees['ptid'] ?>"><?php echo $row_rsOtherEMployees['title'] . ". " . $row_rsOtherEMployees['fullname'] ?></option>
                                                        <?php
                                                        } while ($row_rsOtherEMployees = $query_rsOtherEMployees->fetch());
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label>Comments:</label>
                                                <div class="form-line">
                                                    <textarea name="comments" id="comments" cols="60" rows="5" style="padding:5px; font-size:13px; color:#000; width:99.5%"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Attachments:</label>
                                                <div class="form-line">
                                                    <input type="file" name="leavefile" id="leavefile" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/modal-body-->
                    <div class="modal-footer">
                        <input name="save" type="submit" class="btn btn-success waves-effect waves-light" id="tag-form-submit" value="Save" data-loading-text="Loading...">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
                        <input type="hidden" name="username" id="username" value="<?php echo $user_name; ?>" />
                        <input type="hidden" name="employee" id="employee" value="<?php echo $ptid; ?>" />
                    </div>
                </form>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- /edit Leave modal-->


<?php
} else {
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');
?>



<script type="text/javascript">
    $(document).ready(function() {
        $('#leave').on('change', function(event) {
            var stleave = $("#leave").val();
            var staffid = $("#ptid").val();
            $.ajax({
                type: 'post',
                url: 'ajax/personnel/index.php',
                data: {
                    leaveid: stleave,
                    ptid: staffid
                },
                success: function(data) {
                    $('#leaveformcontent').html(data);
                    $("#staffleaveModal").modal({
                        backdrop: "static"
                    });
                }
            });
        });
    });
</script>