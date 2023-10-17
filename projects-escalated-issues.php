<?php
require('includes/head.php');

if ($permission) {
    try {
        $query_escalatedissues = $db->prepare("SELECT *, c.category as cat FROM tbl_projects p inner join tbl_projissues i on i.projid=p.projid inner join tbl_projrisk_categories c on c.catid=i.risk_category inner join tbl_project_riskscore s on s.issueid=i.id inner join tbl_projissue_severity v on v.id=s.score inner join tbl_priorities o on o.id=i.priority inner join tbl_escalations e on e.itemid=i.id WHERE e.category='issue' and e.owner='$user_name' and (i.status=4 or i.status=5)");
        $query_escalatedissues->execute();
    } catch (PDOException $ex) {
        $results = flashMessage("An error occurred: " . $ex->getMessage());
    }
?>
    <!-- start body  -->
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
					<?= $icon ?>
					<?= $pageTitle ?>
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
                            <div class="table-responsive">
                                <div class="tab-content">
                                    <div id="home" class="tab-pane fade in active">
                                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                            <thead>
                                                <tr class="bg-brown">
                                                    <th style="width:3%">#</th>
                                                    <th style="width:32%">Project Name</th>
                                                    <th style="width:12%">Project Status</th>
                                                    <th style="width:13%">Project Issue</th>
                                                    <th style="width:12%">Severity Level</th>
                                                    <th style="width:12%">Issue Priority</th>
                                                    <th style="width:12%">Date Escalated</th>
                                                    <th style="width:6%" data-orderable="false">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $nm = 0;
                                                while ($rows = $query_escalatedissues->fetch()) {
                                                    $nm = $nm + 1;
                                                    $evalid = $rows['id'];
                                                    $issueid = $rows['issueid'];
                                                    $projid = $rows['projid'];
                                                    $project = $rows['projname'];
                                                    $projstatusid = $rows['projstatus'];
                                                    $severity = $rows['name'];
                                                    $severityvalue = $rows['score'];
                                                    $priority = $rows['priority'];
                                                    $dateescalated = $rows['date_escalated'];
                                                    $issuesno = $rows['issues'];
                                                    $recommendation = $rows['recommendation'];
                                                    $issueassessment = $rows['assessment'];
                                                    $issuedate = $rows['issuedate'];
                                                    $escalatedissues = $rows['cat'];
                                                    $escalateditemid = $rows['itemid'];

                                                    $query_projstatus = $db->prepare("SELECT * FROM tbl_status WHERE statusid='$projstatusid'");
                                                    $query_projstatus->execute();
                                                    $row_projstatus = $query_projstatus->fetch();
                                                    $projstatus = $row_projstatus["statusname"];

                                                    $query_escalationstage = $db->prepare("SELECT * FROM tbl_projissue_comments WHERE projid='$projid' and rskid='$escalateditemid'");
                                                    $query_escalationstage->execute();
                                                    $escalationstage_count = $query_escalationstage->rowCount();
                                                    $assessmentcomments = array();
                                                    while ($row_escalationstage = $query_escalationstage->fetch()) {
                                                        $assessmentcomments[] = $row_escalationstage["stage"];
                                                    }
                                                    //$escalationstage = $row_escalationstage["stage"];

                                                    if ($severityvalue == 1) {
                                                        $style = 'style="background-color:#4CAF50; color:#fff"';
                                                    } elseif ($severityvalue == 2) {
                                                        $style = 'style="background-color:#CDDC39; color:#fff"';
                                                    } elseif ($severityvalue == 3) {
                                                        $style = 'style="background-color:#FFEB3B; color:#000"';
                                                    } elseif ($severityvalue == 4) {
                                                        $style = 'style="background-color:#FF9800; color:#fff"';
                                                    } elseif ($severityvalue == 5) {
                                                        $style = 'style="background-color:#F44336; color:#fff"';
                                                    }

                                                    $query_subcounty = $db->prepare("SELECT state FROM tbl_state WHERE id='$subcounty'");
                                                    $query_subcounty->execute();
                                                    $count_subcounty = $query_subcounty->fetch();
                                                    $subcounty = $count_subcounty["state"];

                                                    $query_ward = $db->prepare("SELECT state FROM tbl_state WHERE id='$ward'");
                                                    $query_ward->execute();
                                                    $count_ward = $query_ward->fetch();
                                                    $ward = $count_ward["state"];
                                                    $loc = $count_loc["state"];

                                                    $query_loc = $db->prepare("SELECT state FROM tbl_state WHERE id='$loc'");
                                                    $query_loc->execute();
                                                    $count_loc = $query_loc->fetch();

                                                    //$location = $count_subcounty["state"]." Sub-County; ".$count_ward["state"]." Ward; ".$count_loc["state"]." Location";

                                                    if ($subcounty == "All") {
                                                        $location = $subcounty . " " . $level1labelplural . "; " . $ward . " " . $level2labelplural . "; " . $loc . " " . $level3labelplural;
                                                    } else {
                                                        $location = $subcounty . " " . $level1label . "; " . $ward . " " . $level2label . "; " . $loc . " " . $level3label;
                                                    }
                                                ?>
                                                    <tr style="background-color:#eff9ca">
                                                        <td align="center"><?php echo $nm; ?></td>
                                                        <td><?php echo $project; ?></td>
                                                        <td><?php echo $projstatus; ?></td>
                                                        <td><?php echo $escalatedissues; ?></td>
                                                        <td <?php echo $style; ?> align="center"><?php echo $severity; ?></td>
                                                        <td><?php echo $priority; ?></td>
                                                        <td><?php echo date("d M Y", strtotime($dateescalated)); ?></td>
                                                        <td align="center">
                                                            <?php
                                                            if ($escalationstage_count > 0) {
                                                                if (in_array(4, $assessmentcomments) && !in_array(5, $assessmentcomments)) { ?>
                                                                    <?php
                                                                    if ($issueassessment == 1) { ?>
                                                                        <a href="project-escalated-issue-assessment.php?issueid=<?php echo $issueid; ?>" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Add issue assessment feedback"><i class="fa fa-file-code-o fa-2x text-primary" aria-hidden="true"></i></a>
                                                                    <?php
                                                                    } else {
                                                                    ?>
                                                                        <a href="project-escalated-issue.php?issueid=<?php echo $issueid; ?>"><i class="fa fa-gavel fa-2x text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Action required"></i></a>
                                                                    <?php
                                                                    }
                                                                } elseif (in_array(4, $assessmentcomments) && in_array(5, $assessmentcomments)) {
                                                                    ?>
                                                                    <a href="project-escalated-issue.php?issueid=<?php echo $issueid; ?>"><i class="fa fa-gavel fa-2x text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Issue assessment report ready"></i></a>
                                                                <?php
                                                                } else {
                                                                ?>
                                                                    <a href="project-escalated-issue.php?issueid=<?php echo $issueid; ?>"><i class="fa fa-gavel fa-2x text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Issue requiring your ACTION!!"></i></a>
                                                            <?php
                                                                }
                                                            } ?>
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
<?php
} else {
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');
?>
<script type="text/javascript">
    $(document).ready(function() {
        $(".account").click(function() {
            var X = $(this).attr('id');

            if (X == 1) {
                $(".submenus").hide();
                $(this).attr('id', '0');
            } else {

                $(".submenus").show();
                $(this).attr('id', '1');
            }

        });

        //Mouseup textarea false
        $(".submenus").mouseup(function() {
            return false
        });
        $(".account").mouseup(function() {
            return false
        });


        //Textarea without editing.
        $(document).mouseup(function() {
            $(".submenus").hide();
            $(".account").attr('id', '');
        });

    });

    function CallRiskAction(id) {
        $.ajax({
            type: 'post',
            url: 'callriskaction',
            data: {
                rskid: id
            },
            success: function(data) {
                $('#riskaction').html(data);
                $("#riskModal").modal({
                    backdrop: "static"
                });
            }
        });
    }
</script>