<?php
$replacement_array = array(
    'planlabel' => "CIDP",
    'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');

if ($permission) {
    $pageTitle = " Projects Risk Response";
    try {
        $currentPage = $_SERVER["PHP_SELF"];
        $query_issues = $db->prepare("SELECT tbl_projects.projid, projname, projstatus, projcommunity, projlga, projstate, projcategory, count(id) AS issues, tbl_projissues.* FROM tbl_projissues INNER JOIN tbl_projects ON tbl_projects.projid=tbl_projissues.projid INNER JOIN tbl_projrisk_categories ON tbl_projrisk_categories.rskid=tbl_projissues.risk_category WHERE tbl_projects.deleted='0' GROUP BY tbl_projects.projid");
        $query_issues->execute();
        $row_issues = $query_issues->fetch();
        $count_issues = $query_issues->rowCount(); 
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
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr class="bg-brown">
                                            <th style="width:3%">#</th>
                                            <th style="width:37%">Project Name</th>
                                            <th style="width:13%">Project Status</th>
                                            <th style="width:11%">Risk Level</th>
                                            <th style="width:6%">Issues</th>
                                            <th style="width:15%">Project Start Date</th>
                                            <th style="width:15%">Project End Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $nm = 0;
                                        do {
                                            $nm = $nm + 1;
                                            $id = $row_issues['id'];
                                            $projid = $row_issues['projid'];
                                            $project = $row_issues['projname'];
                                            $projstatus = $row_issues['projstatus'];
                                            $issuesno = $row_issues['issues'];
                                            $recommendation = $row_issues['recommendation'];
                                            $projcategory = $row_issues['projcategory'];

                                            $query_analysedissues = $db->prepare("SELECT count(id) AS analysedissues FROM tbl_projissues WHERE projid='$projid' AND status=1");
                                            $query_analysedissues->execute();
                                            $count_analysedissues = $query_analysedissues->fetch();
                                            $analysedissues = $count_analysedissues["analysedissues"];

                                            $query_projstatus = $db->prepare("SELECT statusname, projstatus, projchangedstatus FROM tbl_projects p left join tbl_status s on s.statusid=p.projstatus WHERE projid='$projid'");
                                            $query_projstatus->execute();
                                            $row_projstatus = $query_projstatus->fetch();
                                            $projectstatus = $row_projstatus["statusname"];
                                            $currentprojstatus = $row_projstatus["projstatus"];
                                            $projchangedstatus = $row_projstatus["projchangedstatus"];

                                            $level = $style ="";
                                            if (empty($projchangedstatus) || $projchangedstatus == '' || $currentprojstatus == 'On Hold') {
                                                if ($issuesno == $analysedissues) {
                                                    $query_projriskscore = $db->prepare("SELECT MAX(score) AS maxscore FROM tbl_project_riskscore WHERE projid='$projid'");
                                                    $query_projriskscore->execute();
                                                    $row_projriskscore = $query_projriskscore->fetch();
                                                    $maxiscore = $row_projriskscore["maxscore"];
                                                    if ($maxiscore == 1) {
                                                        $level = "Negligible";
                                                        $style = 'style="background-color:#4CAF50; color:#fff"';
                                                    } elseif ($maxiscore == 2) {
                                                        $level = "Minor";
                                                        $style = 'style="background-color:#CDDC39; color:#fff"';
                                                    } elseif ($maxiscore == 3) {
                                                        $level = "Moderate";
                                                        $style = 'style="background-color:#FFEB3B; color:#000"';
                                                    } elseif ($maxiscore == 4) {
                                                        $level = "Significant";
                                                        $style = 'style="background-color:#FF9800; color:#fff"';
                                                    } elseif ($maxiscore == 5) {
                                                        $level = "Severe";
                                                        $style = 'style="background-color:#F44336; color:#fff"';
                                                    }
                                                    $actionlink = '<a onclick="javascript:CallRiskResponse(' . $projid . ')" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Risk Response"><i class="fa fa-gavel fa-2x text-primary" aria-hidden="true"></i></a>';
                                                } else {
                                                    $level = "Not Analysed";
                                                    $style = 'style="background-color:#9E9E9E; color:#fff"';
                                                }
                                            } elseif ($currentprojstatus == $projchangedstatus || $currentprojstatus == "Cancelled") {
                                                $level = "Addressed";
                                                $style = 'style="background-color:#795548; color:#fff"';
                                                $actionlink = '<a onclick="javascript:CallRiskResponseReport(' . $projid . ')" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="Risk Response History Report"><i class="fa fa-folder fa-2x text-success" aria-hidden="true"></i></a>';
                                            }

                                            if ($projcategory == 1) {
                                                $query_projdates = $db->prepare("SELECT projstartdate AS prjstartdate, projenddate AS prjenddate FROM tbl_projects WHERE projid='$projid'");
                                            } else {
                                                $query_projdates = $db->prepare("SELECT startdate AS prjstartdate, enddate AS prjenddate FROM tbl_tenderdetails WHERE projid='$projid'");
                                            }
                                            $query_projdates->execute();
                                            $row_projdates = $query_projdates->fetch();
                                            $startdate = date("d M Y", strtotime($row_projdates["prjstartdate"]));
                                            $enddate = date("d M Y", strtotime($row_projdates["prjenddate"]));
                                        ?>
                                            <tr style="background-color:#eff9ca">
                                                <td align="center"><?php echo $nm; ?></td>
                                                <td><?php echo $project; ?></td>
                                                <td><?php echo $projectstatus; ?></td>
                                                <td <?php echo $style; ?> align="center"><?php echo $level; ?></td>
                                                <td align="center">
                                                    <a href="#" onclick="javascript:GetProjIssues(<?php echo $projid; ?>)"><span class="badge bg-purple"><?php echo $issuesno; ?></a></span></a>
                                                </td>
                                                <td><?php echo $startdate; ?></td>
                                                <td><?php echo $enddate; ?></td>
                                            </tr>
                                        <?php
                                        } while ($row_issues = $query_issues->fetch());
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- end body -->
                        </div>
                    </div>
                </div>
            </div>
    </section>


    <!-- Modal Issue Action -->
    <div class="modal fade" id="riskModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#795548">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title" align="center">
                        <font color="#FFF">PROJECT RISK RESPONSE</font>
                    </h3>
                </div>
                <form class="tagForm" action="issueanalysis" method="post" id="risk-response-form" enctype="multipart/form-data" autocomplete="off">
                    <div class="modal-body">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="card">
                                    <div class="body">
                                        <div class="table-responsive" style="background:#eaf0f9">
                                            <div id="riskresponse">
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
                            <input type="hidden" name="stchange" value="1" />
                        </div>
                        <div class="col-md-4">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- #END# Modal Issue Response -->
    <!-- Modal Issue Response Report -->
    <div class="modal fade" id="riskResponseModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title" align="center">
                        <font color="#FFF">Project Issues Response Report</font>
                    </h3>
                </div>
                <div class="modal-body">
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="card">
                                <div class="body">
                                    <div class="table-responsive" style="background:#eaf0f9">
                                        <div id="riskresponsereport">
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
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                    </div>
                    <div class="col-md-4">
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- #END# Modal Issue Response Report -->


    <!-- end body  -->
    <!-- Modal Project Issues -->
    <div class="modal fade" id="projIssues" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h2 class="modal-title" align="center" style="color:#FF5722; font-size:24px">Project Issues</h2>
                </div>
                <div class="modal-body" id="detailscontent">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

<?php
} else {
    $results =  restriction();
    echo $results;
}
require('includes/footer.php');
?>
<script>
    function CallRiskResponse(projid) {
        $.ajax({
            type: 'post',
            url: 'callriskresponse.php',
            data: {
                projid: projid
            },
            success: function(data) {
                $('#riskresponse').html(data);
                $("#riskModal").modal({
                    backdrop: "static"
                });
            }
        });
    }

    function CallEscalationResponseReport(projid) {
        $.ajax({
            type: 'post',
            url: 'callescalationresponsereport.php',
            data: {
                projid: projid
            },
            success: function(data) {
                $('#riskresponsereport').html(data);
                $("#riskResponseModal").modal({
                    backdrop: "static"
                });
            }
        });
    }


    function GetProjIssues(projid) {
        $.ajax({
            type: 'post',
            url: 'ajax/issuesandrisks/index.php',
            data: {
                get_getprojissues: "getprojissues",
                prjid: projid
            },
            success: function(data) {
                $('#detailscontent').html(data);
                $("#projIssues").modal({
                    backdrop: "static"
                });
            }
        });
    }


    $(document).ready(function() {
        $('#risk-response-form').on('submit', function(event) {
            event.preventDefault();
            var form_data = $(this).serialize();
            $.ajax({
                type: "POST",
                url: "riskresponse.php",
                data: form_data,
                dataType: "json",
                success: function(response) {
                    if (response) {
                        alert('Record Successfully Saved');
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
