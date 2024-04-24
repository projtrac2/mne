<?php
require('includes/head.php');
if ($permission && isset($_GET["fn"]) && !empty($_GET["fn"])) {
    try {
        $hash = $_GET['fn'];
        $decode_fndid = base64_decode($hash);
        $fndid_array = explode("fn918273AxZID", $decode_fndid);
        $financier_id = $fndid_array[1];


        $query_financier = $db->prepare("SELECT * FROM tbl_financiers WHERE id=:fn");
        $query_financier->execute(array(":fn" => $financier_id));
        $row_financier = $query_financier->fetch();
        $financier = $row_financier ?  $row_financier['financier'] : '';


        function get_amount_funding($projid, $fyear)
        {
            global $db, $financier_id;
            $query_plannedfunds = $db->prepare("SELECT SUM(amountfunding) as planned FROM tbl_myprojfunding WHERE financier=:fid AND projid=:projid");
            $query_plannedfunds->execute(array(":fid" => $financier_id, ":projid" => $projid));
            $row_plannedfunds = $query_plannedfunds->fetch();
            return  !is_null($row_plannedfunds["planned"]) ? $row_plannedfunds["planned"] : 0;
        }

        function get_utilized_amount($projid, $fyear)
        {
            global $db, $financier_id;
            $query_utilizedfunds = $db->prepare("SELECT SUM(amount) as utilized FROM tbl_payment_request_financiers WHERE financier_id=:fid AND projid=:projid");
            $query_utilizedfunds->execute(array(":fid" => $financier_id, ":projid" => $projid));
            $row_utilizedfunds = $query_utilizedfunds->fetch();
            return  !is_null($row_utilizedfunds["utilized"]) ? $row_utilizedfunds["utilized"] : 0;
        }

        function get_status($status_id)
        {
            global $db;
            $query_Projstatus =  $db->prepare("SELECT * FROM tbl_status WHERE statusid = :projstatus");
            $query_Projstatus->execute(array(":projstatus" => $status_id));
            $row_Projstatus = $query_Projstatus->fetch();
            $total_Projstatus = $query_Projstatus->rowCount();
            $status = "";
            if ($total_Projstatus > 0) {
                $status_name = $row_Projstatus['statusname'];
                $status_class = $row_Projstatus['class_name'];
                $status = '<button type="button" class="' . $status_class . '" style="width:100%">' . $status_name . '</button>';
            }

            return $status;
        }

        function get_financier_planned_amount()
        {
            global $db, $fyear, $financier_id;
            $query_plannedfunds = $db->prepare("SELECT SUM(amountfunding) as planned FROM tbl_myprojfunding WHERE financier=:fid ");
            $query_plannedfunds->execute(array(":fid" => $financier_id));
            $row_plannedfunds = $query_plannedfunds->fetch();
            return  !is_null($row_plannedfunds["planned"]) ? $row_plannedfunds["planned"] : 0;
        }

        function get_financier_utilized_funds()
        {
            global $db, $financier_id, $fyear;
            $query_utilizedfunds = $db->prepare("SELECT SUM(amount) as utilized FROM tbl_payment_request_financiers WHERE financier_id=:fid");
            $query_utilizedfunds->execute(array(":fid" => $financier_id));
            $row_utilizedfunds = $query_utilizedfunds->fetch();
            return  !is_null($row_utilizedfunds["utilized"]) ? $row_utilizedfunds["utilized"] : 0;
        }
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
                    <?php echo $pageTitle ?>
                    <div class="btn-group" style="float:right">
                        <div class="btn-group" style="float:right">
                            <a type="button" id="outputItemModalBtnrow" href="./view-financiers.php" class="btn btn-warning pull-right">
                                Go Back
                            </a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="./reports/funding-report-pdf.php?fn=<?= $hash ?>" target="_blank" class="btn btn-danger btn-sm" type="button">
                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF
                            </a>
                        </div>
                    </div>
                </h4>
            </div>
            <div class="card">
                <div class="row clearfix">
                    <div class="block-header">
                        <?= $results; ?>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card-header">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <ul class="list-group">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <li class="list-group-item list-group-item list-group-item-action active">Financier: <?= $financier ?> </li>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <li class="list-group-item"><strong>Amount Financing: </strong> <?= number_format(get_financier_planned_amount(), 2) ?> </li>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <li class="list-group-item"><strong>Amount Utilized: </strong> <?= number_format(get_financier_utilized_funds(), 2) ?> </li>
                                        </div>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr>
                                            <th width="5%">#&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <th width="25%">Project &nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <th width="10%">Status &nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <th width="10%">Start Date &nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <th width="10%">End Date &nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <th width="10%">Project Cost &nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <th width="10%">Amount Financing &nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <th width="10%">Amount Utilized &nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <th width="10%">Rate &nbsp;&nbsp;&nbsp;&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = $db->prepare("SELECT * FROM `tbl_programs` g left join `tbl_projects` p on p.progid=g.progid left join tbl_fiscal_year y on y.id=p.projfscyear left join tbl_status s on s.statusid=p.projstatus WHERE g.program_type=0 AND p.deleted='0' ORDER BY `projfscyear` DESC");
                                        $sql->execute();
                                        $rows_count = $sql->rowCount();
                                        if ($rows_count > 0) {
                                            $counter = 0;
                                            while ($row_project = $sql->fetch()) {
                                                $projid = $row_project['projid'];
                                                $project_name = $row_project['projname'];
                                                $project_cost = $row_project['projcost'];
                                                $project_start_date = $row_project['projstartdate'];
                                                $project_end_date = $row_project['projenddate'];
                                                $status = $row_project['projstatus'];

                                                $query_plannedfunds = $db->prepare("SELECT * FROM tbl_myprojfunding WHERE financier=:fid AND projid=:projid");
                                                $query_plannedfunds->execute(array(":fid" => $financier_id, ":projid" => $projid));
                                                $row_plannedfunds = $query_plannedfunds->rowCount();

                                                if ($row_plannedfunds > 0) {
                                                    $counter++;
                                                    $planned_funds = get_amount_funding($projid, $fyear);
                                                    $utilized_funds = get_utilized_amount($projid, $fyear);
                                                    $rate = $utilized_funds > 0 && $planned_funds > 0 ? ($utilized_funds / $planned_funds) * 100 : 0;

                                        ?>
                                                    <tr>
                                                        <td width="5%"><?= $counter ?></td>
                                                        <td width="35%"><?= $project_name ?></td>
                                                        <td width="20%"><?= get_status($status) ?></td>
                                                        <td width="20%"><?= $project_start_date ?></td>
                                                        <td width="20%"><?= $project_end_date ?></td>
                                                        <td width="5%"><?= number_format($project_cost, 2) ?></td>
                                                        <td width="35%"><?= number_format($planned_funds, 2) ?></td>
                                                        <td width="20%"><?= number_format($utilized_funds, 2) ?></td>
                                                        <td width="20%"><?= number_format($rate, 2) ?></td>
                                                    </tr>
                                        <?php
                                                }
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
    </section>
<?php
} else {
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');
?>