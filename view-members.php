<?php
$replacement_array = array(
    'planlabel' => "CIDP",
    'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');

if ($permission) {
    $pageTitle = "Project Team";
    try {
        if (isset($_GET['projid'])) {
            $projid = $_GET['projid'];
        }

        if (isset($_GET["ptid"]) && !empty($_GET["ptid"])) {
            $mbr = $_GET["ptid"];
            if (isset($_GET["action"]) && $_GET["action"] == '2') {
                $delete_rsUserProf = $db->prepare("DELETE FROM tbl_projteam2 WHERE ptid='$mbr'");
                $delete_rsUserProf->execute();

                $delete_rsUser = $db->prepare("DELETE FROM tbl_users WHERE pt_id='$mbr'");
                $delete_rsUser->execute();

                $msg = 'You have successfully deleted the user';
                $results =
                    "<script type=\"text/javascript\">
                    swal({
                        title: \"Success!\",
                        text: \" $msg\",
                        type: 'Success',
                        timer: 5000,
                        'icon':'success',
                        showConfirmButton: false });
                    setTimeout(function(){
                        window.location.href = 'view-project-team.php';
                    }, 3000);
                </script>";
            }
        }

        $query_rsPTeam = $db->prepare("SELECT * FROM tbl_projteam2 WHERE disabled='0'");
        $query_rsPTeam->execute();
        $row_rsPTeam = $query_rsPTeam->fetch();
        $totalRows_rsPTeam = $query_rsPTeam->rowCount();
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
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                            <!-- start body -->
                            <?php if (isset($_GET["projid"])) { ?>
                                <div class="table-responsive">
                                    <form name="addprojmembers" method="POST" action="insertprojmembers.php">
                                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                            <thead>
                                                <tr id="colrow">
                                                    <th width="3%"><strong>Select</strong></th>
                                                    <th width="7%"><strong>Photo</strong></th>
                                                    <th width="30%"><strong>Full Name</strong></th>
                                                    <!--COLSPAN=4-->
                                                    <th width="25%"><strong>Designation</strong></th>
                                                    <th width="25%"><strong>Email</strong></th>
                                                    <th width="10%"><strong>Phone</strong></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- =========================================== -->
                                                <?php
                                                do {
                                                    $mbrdesg = $row_rsPTeam['designation'];

                                                    $query_rsPMbrDesg = $db->prepare("SELECT * FROM tbl_pmdesignation WHERE moid = '$mbrdesg' ORDER BY moid ASC");
                                                    $query_rsPMbrDesg->execute();
                                                    $row_rsPMbrDesg = $query_rsPMbrDesg->fetch();
                                                ?>
                                                    <tr>
                                                        <td align="center">
                                                            <div class="demo-checkbox">
                                                                <input type="checkbox" name="members[]" value="<?= $row_rsPTeam['ptid'] ?>" id="mbr<?php echo $row_rsPTeam['ptid']; ?>" class="filled-in chk-col-green" />
                                                                <label for="mbr<?php echo $row_rsPTeam['ptid']; ?>"></label>
                                                            </div>
                                                        </td>
                                                        <td><img src="<?php echo $row_rsPTeam['floc']; ?>" alt="" style="width:30px; height:30px; margin-bottom:0px" /></td>
                                                        <td><?php echo $row_rsPTeam['lastname']; ?>, <?php echo $row_rsPTeam['firstname']; ?> (<?php echo $row_rsPTeam['title']; ?>)</td>
                                                        <td><?php echo $row_rsPMbrDesg['designation']; ?></td>
                                                        <td><?php echo $row_rsPTeam['email']; ?></td>
                                                        <td><?php echo $row_rsPTeam['phone']; ?></td>
                                                    </tr>
                                                <?php } while ($row_rsPTeam = $query_rsPTeam->fetch()); ?>
                                            </tbody>
                                            <tr>
                                                <td colspan="6" align="center">
                                                    <input name="projid" type="hidden" id="projid" value="<?php echo $row_rsMyP['projid']; ?>" />
                                                    <input name="user_name" type="hidden" id="user_name" value="<?php echo $row_rsAdm['username']; ?>" />
                                                    <button type="submit" id="submit" name="addprojmember" class="btn btn-primary">Add Select Members&nbsp;&nbsp;<span class="glyphicon glyphicon-check"></button>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            <?php } else { ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                        <thead>
                                            <tr id="colrow">
                                                <th width="3%"><strong>Photo</strong></th>
                                                <th width="18%"><strong>Fullname</strong></th>
                                                <th width="13%"><strong>Designation</strong></th>
                                                <th width="12%"><strong>Availability</strong></th>
                                                <th width="19%"><strong><?= $ministrylabel ?></strong></th>
                                                <th width="19%"><strong><?= $departmentlabel ?></strong></th>
                                                <th width="8%"><strong>Projects</strong></th>
                                                <!--COLSPAN=4-->
                                                <th width="8%"><strong>Action</strong></th>
                                                <!--COLSPAN=4-->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- =========================================== -->
                                            <?php
                                            $nm = 0;
                                            do {
                                                $mbrid = $row_rsPTeam['ptid'];
                                                $desig = $row_rsPTeam["designation"];
                                                $mnst = $row_rsPTeam["ministry"];
                                                $dept = $row_rsPTeam["department"];
                                                $avail = $row_rsPTeam["availability"];

                                                $query_rsNoPrj = $db->prepare("SELECT projid FROM tbl_projmembers WHERE ptid='$mbrid' GROUP BY projid");
                                                $query_rsNoPrj->execute();
                                                $row_num = $query_rsNoPrj->fetch();
                                                $totalRows_rsNoPrj = $query_rsNoPrj->rowCount();

                                                $query_rsPMDesignation = $db->prepare("SELECT * FROM tbl_pmdesignation WHERE moid='$desig'");
                                                $query_rsPMDesignation->execute();
                                                $row_rsPMDesignation = $query_rsPMDesignation->fetch();
                                                $totalRows_rsPMDesignation = $query_rsPMDesignation->rowCount();

                                                if ($mnst == 0) {
                                                    $ministry = "All Departments";
                                                } else {
                                                    $query_rsSC = $db->prepare("SELECT * FROM tbl_sectors WHERE stid='$mnst'");
                                                    $query_rsSC->execute();
                                                    $row_rsSC = $query_rsSC->fetch();
                                                    $totalRows_rsSC = $query_rsSC->rowCount();
                                                    $ministry = $row_rsSC["sector"];
                                                }

                                                if ($dept == 0) {
                                                    $department = "All Divisions";
                                                } else {
                                                    $query_rsDept = $db->prepare("SELECT * FROM tbl_sectors WHERE stid='$dept'");
                                                    $query_rsDept->execute();
                                                    $row_rsDept = $query_rsDept->fetch();
                                                    $totalRows_rsDept = $query_rsDept->rowCount();
                                                    $department = $row_rsDept["sector"];
                                                }
                                                if ($avail == 0) {
                                                    $availability = "<font color='#FF5722'>Unavailable</font>";
                                                } else {
                                                    $availability = "<font color='#4CAF50'>Available</font>";
                                                }
                                            ?>
                                                <tr style="border-bottom:thin solid #EEE">
                                                    <td align="center"><img src="<?php echo $row_rsPTeam['floc']; ?>" alt="" style="width:30px; height:30px; margin-bottom:0px" /></td>
                                                    <td><?php echo $row_rsPTeam['lastname']; ?>, <?php echo $row_rsPTeam['firstname']; ?> (<?php echo $row_rsPTeam['title']; ?>)</td>
                                                    <td><?php echo ($totalRows_rsPMDesignation > 0) ? $row_rsPMDesignation['designation'] : ""; ?></td>
                                                    <td><?php echo $availability; ?></td>
                                                    <td><?php echo $ministry; ?></td>
                                                    <td><?php echo $department; ?></td>
                                                    <td align="center"><span class="badge bg-purple"><a href="memberprojects?mbrid=<?php echo $row_rsPTeam['ptid']; ?>" style="font-family:Verdana, Geneva, sans-serif; color:white; font-size:12px; padding-top:0px"><?php echo $totalRows_rsNoPrj; ?></a></span></td>
                                                    <td align="center">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" onchange="checkBoxes()" aria-haspopup="true" aria-expanded="false">
                                                                Options <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a type="button" href="view-member-info.php?staff=<?php echo $row_rsPTeam['ptid']; ?>"><i class="fa fa-plus-square"></i> Manage</a>
                                                                </li>
                                                                <?php
                                                                if ($file_rights->edit && $file_rights->delete_permission) {
                                                                ?>
                                                                    <li>
                                                                        <a type="button" href="add-member.php?action=1&ptid=<?php echo $row_rsPTeam['ptid']; ?>"><i class="glyphicon glyphicon-edit"></i> Edit </a>
                                                                    </li>
                                                                    <li>
                                                                        <a type="button" href="view-project-team.php?action=2&ptid=<?php echo $row_rsPTeam['ptid']; ?>" onclick="return confirm('Are you sure you want to delete this user?')"><i class="glyphicon glyphicon-trash"></i> Delete </a>
                                                                    </li>
                                                                <?php
                                                                }
                                                                ?>
                                                            </ul>
                                                        </div>
                                                </tr>
                                            <?php
                                                $nm = $nm + 1;
                                            } while ($row_rsPTeam = $query_rsPTeam->fetch()); ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php
                            } ?>
                            <!-- end body -->
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