<?php
try {
    require('includes/head.php');
    if ($permission &&  isset($_GET['status'])) {
        $status = $_GET['status'];
        $start_year = $_GET['year'];
        $end_year = $start_year + 1;
        $start_date = date("Y-m-d", strtotime($start_year . "-07-01"));
        $end_date = date("Y-m-d", strtotime($end_year . "-06-30"));

        $financial_year = $start_year . '/' . $end_year;

        // recorded
        $query_rsProjissues =  $db->prepare("SELECT c.catid, c.category, i.id, i.issue_description, i.issue_area, i.issue_impact, i.issue_priority, i.date_created, i.status, i.projid, i.date_updated, i.date_closed FROM tbl_projrisk_categories c left join tbl_projissues i on c.catid = i.risk_category  WHERE i.date_created >= :start_date AND i.date_created <= :end_date ");
        if ($status == "pending") {
            $query_rsProjissues =  $db->prepare("SELECT c.catid, c.category, i.id, i.issue_description, i.issue_area, i.issue_impact, i.issue_priority, i.date_created, i.status, i.projid, i.date_updated, i.date_closed FROM tbl_projrisk_categories c left join tbl_projissues i on c.catid = i.risk_category  WHERE i.status <> 7 AND i.date_created >= :start_date AND i.date_created <= :end_date");
        } else if ($status == "resolved") {
            $query_rsProjissues =  $db->prepare("SELECT c.catid, c.category, i.id, i.issue_description, i.issue_area, i.issue_impact, i.issue_priority, i.date_created, i.status, i.projid, i.date_updated, i.date_closed FROM tbl_projrisk_categories c left join tbl_projissues i on c.catid = i.risk_category  WHERE i.status = 7 AND i.date_created >= :start_date AND i.date_created <= :end_date");
        }

        $query_rsProjissues->execute(array(":start_date" => $start_date, ":end_date" => $end_date));
        $totalRows_rsProjissues = $query_rsProjissues->rowCount();

?>
        <style>
            .container {

                margin-top: 100px;
            }

            .modal.fade .modal-bottom,
            .modal.fade .modal-left,
            .modal.fade .modal-right,
            .modal.fade .modal-top {
                position: fixed;
                z-index: 1055;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                margin: 0;
                max-width: 100%
            }

            .modal.fade .modal-right {
                left: auto !important;
                transform: translate3d(100%, 0, 0);
                transition: transform .3s cubic-bezier(.25, .8, .25, 1)
            }

            .modal.fade.show .modal-bottom,
            .modal.fade.show .modal-left,
            .modal.fade.show .modal-right,
            .modal.fade.show .modal-top {
                transform: translate3d(0, 0, 0)
            }

            .w-xl {
                width: 55%
            }

            .modal-content,
            .modal-footer,
            .modal-header {
                border: none
            }

            .h-100 {
                height: 100% !important
            }

            .list-group.no-radius .list-group-item {
                border-radius: 0 !important
            }

            .btn-light {
                color: #212529;
                background-color: #f5f5f6;
                border-color: #f5f5f6
            }

            .btn-light:hover {
                color: #212529;
                background-color: #e1e1e4;
                border-color: #dadade
            }

            .modal-footer {
                align-items: center
            }

            /* Important part */
            .modal-dialog {
                overflow-y: initial !important
            }

            .modal-body {
                height: 80vh;
                overflow-y: auto;
            }
        </style>
        <style src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"></style>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <section class="content">
            <div class="container-fluid">
                <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                    <h4 class="contentheader">
                        <?= $icon . ' ' . $financial_year . ' ' . ucfirst($status) ?> Issues
                        <div class="btn-group" style="float:right; padding-right:5px">
                            <a type="button" id="outputItemModalBtnrow" onclick="history.back()" class="btn btn-warning pull-right">
                                Go Back
                            </a>
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
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover js-basic-example" id="guarantees_table">
                                            <thead>
                                                <tr>
                                                    <th style="width:4%">#</th>
                                                    <th style="width:30%">Issue</th>
                                                    <th style="width:30%">Project</th>
                                                    <th style="width:11%">Issue Area</th>
                                                    <th style="width:11%">Resolution </th>
                                                    <th style="width:14%">Resolution Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if ($totalRows_rsProjissues > 0) {
                                                    $rowno = 0;
                                                    while ($row_issues = $query_rsProjissues->fetch()) {
                                                        $rowno++;
                                                        $issue_id = $row_issues["id"];
                                                        $issue = $row_issues["issue_description"];
                                                        $priorityid = $row_issues["issue_priority"];
                                                        $issuestatusis = $row_issues["status"];
                                                        $issue_areaid = $row_issues['issue_area'];
                                                        $resolution_updated = date('d M Y', strtotime($row_issues['date_updated']));
                                                        $projid = $row_issues["projid"];

                                                        if ($issue_areaid == 2) {
                                                            $issue_area = "Scope";
                                                        } elseif ($issue_areaid == 3) {
                                                            $issue_area = "Schedule";
                                                        } elseif ($issue_areaid == 4) {
                                                            $issue_area = "Cost";
                                                        } else {
                                                            $issue_area = "Others";
                                                        }

                                                        $styled = 'style="color:blue"';

                                                        if ($issuestatusis == 0) {
                                                            $issuestatus = "Pending Action";
                                                            $resolution_updated = date('d M Y', strtotime($row_issues['date_created']));
                                                        } elseif ($issuestatusis == 1) {
                                                            $issuestatus = "Ignore the Issue and Continue";
                                                        } elseif ($issuestatusis == 2) {
                                                            $issuestatus = "Project Put On Hold";
                                                        } elseif ($issuestatusis == 3) {
                                                            $issuestatus = "Project Restored";
                                                        } elseif ($issuestatusis == 4) {
                                                            $issuestatus = "Request Approved";
                                                        } elseif ($issuestatusis == 5) {
                                                            $issuestatus = "Project Restored & Request Approved";
                                                        } elseif ($issuestatusis == 2) {
                                                            $issuestatus = "Project Cancelled";
                                                        } elseif ($issuestatusis == 7) {
                                                            $issuestatus = "Issue Closed";
                                                            $styled = 'style="color:green"';
                                                            $resolution_updated = date('d M Y', strtotime($row_issues['date_closed']));
                                                        }

                                                        $query_projdetails = $db->prepare("SELECT * FROM tbl_projects WHERE projid=:projid");
                                                        $query_projdetails->execute(array(":projid" => $projid));
                                                        $row_projdetails = $query_projdetails->fetch();
                                                        $project = $row_projdetails ?  $row_projdetails['projname'] : '';
                                                ?>
                                                        <tr id="guarantee_row">
                                                            <td><?= $rowno ?></td>
                                                            <td>
                                                                <a data-toggle="modal" data-target="#modal-right" data-toggle-class="modal-open-aside" style="font-family:Verdana, Geneva, sans-serif; text-decoration: none; padding-top:0px" onclick="issue_more_info(<?= $projid ?>,<?= $issue_id ?>)" class="text-primary">
                                                                    <?= $issue ?>
                                                                </a>
                                                            </td>
                                                            <td><?= $project ?></td>
                                                            <td><?= $issue_area ?></td>
                                                            <td <?= $styled ?>><?= $issuestatus ?></td>
                                                            <td><?= $resolution_updated ?></td>
                                                        </tr>
                                                <?php
                                                    }
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

        <div id="modal-right" class="modal fade" data-backdrop="true">
            <div class="modal-dialog modal-right w-xl">
                <div class="modal-content h-100 no-radius">
                    <div class="modal-header" style="background-color:#03A9F4">
                        <h3 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-warning" style="color:yellow"></i> <span id="modal_info"> PROJECT ISSUE DETAILS</span></h3>
                    </div>
                    <div class="modal-body">
                        <div class="p-4" id="issue_details">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                            <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end body  -->
<?php
    } else {
        $results =  restriction();
        echo $results;
    }

    require('includes/footer.php');
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>
<script src="assets/js/risk/index.js"></script>