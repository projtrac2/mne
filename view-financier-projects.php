<?php
require('includes/head.php');
if ($permission) {
    try {
        if (isset($_GET['fndid'])) {
            $finid = base64_decode($_GET['fndid']);
            if (!empty($finid)) {
                $query_dnrprojs = $db->prepare("SELECT p.* FROM tbl_projects p inner join tbl_myprojfunding m on p.projid=m.projid WHERE p.deleted='0' and m.financier = :fnid GROUP BY p.projid ORDER BY m.id ASC");
                $query_dnrprojs->execute(array(":fnid" => $finid));
                $count_projects = $query_dnrprojs->rowCount();
            }
        }

        function get_project_status($status)
        {
            global $db;
            $query_Projstatus =  $db->prepare("SELECT * FROM tbl_status WHERE statusid = :projstatus");
            $query_Projstatus->execute(array(":projstatus" => $status));
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
                    <?= $icon ?>
                    <?= $pageTitle ?>
                    <div class="btn-group" style="float:right">
                        <div class="btn-group" style="float:right">
                            <button type="button" onclick="history.back()" class="btn btn-warning" name="button">Go Back</button>
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
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr style="background-color:#607D8B; color:#FFF">
                                            <th width="4%"><strong>#</strong></th>
                                            <th>Project Name</th>
                                            <th>Status</th>
                                            <th>Cost (Ksh)</th>
                                            <th>Amount Funding</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        if ($count_projects > 0) {
                                            $sn = 0;
                                            while ($detail = $query_dnrprojs->fetch()) {
                                                $sn++;
                                                $status = $detail['projstatus'];
                                                $project_status = get_project_status($status);
                                                $projid = $detail['projid'];
                                                $query_dnrprojsFunds = $db->prepare("SELECT sum(amountfunding) AS amnt FROM tbl_myprojfunding  WHERE projid='$projid' and financier = '$finid'");
                                                $query_dnrprojsFunds->execute();
                                                $count_projectsFunds = $query_dnrprojsFunds->rowCount();
                                                $count_projectsFunds = $query_dnrprojsFunds->rowCount();
                                                $row_projectsFunds = $query_dnrprojsFunds->fetch();
                                                $amount = $row_projectsFunds['amnt'];
                                        ?>
                                                <tr>
                                                    <td><?php echo $sn; ?></td>
                                                    <td><?php echo $detail['projname']; ?></td>
                                                    <td><?php echo $project_status; ?></td>
                                                    <td><?php echo number_format($detail['projcost'], 2); ?></td>
                                                    <td><?php echo  number_format($amount, 2); ?></td>
                                                    <td><?php echo date("d M Y", strtotime($detail['projstartdate'])); ?></td>
                                                    <td><?php echo date("d M Y", strtotime($detail['projenddate'])); ?></td>
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
    </section>
    <!-- end body  -->
<?php
} else {
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');
?>