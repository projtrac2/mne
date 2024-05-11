<?php
try {
    require('includes/head.php');
    if ($permission && isset($_GET['fndid']) && !empty($_GET['fndid'])) {
        $finid = base64_decode($_GET['fndid']);
        $query_dnrprojs = $db->prepare("SELECT p.*, m.role FROM tbl_projects p inner join tbl_myprojpartner m on p.projid=m.projid WHERE p.deleted='0' and m.partner_id = :fnid ORDER BY m.id ASC");
        $query_dnrprojs->execute(array(":fnid" => $finid));
        $count_projects = $query_dnrprojs->rowCount();

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
                                                <th>Role</th>
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
                                                    $query_Projstatus =  $db->prepare("SELECT * FROM tbl_status WHERE statusid = :projstatus");
                                                    $query_Projstatus->execute(array(":projstatus" => $detail['projstatus']));
                                                    $row_Projstatus = $query_Projstatus->fetch();
                                                    $total_Projstatus = $query_Projstatus->rowCount();
                                                    $status = "";
                                                    if ($total_Projstatus > 0) {
                                                        $status_name = $row_Projstatus['statusname'];
                                                        $status_class = $row_Projstatus['class_name'];
                                                        $status = '<button type="button" class="' . $status_class . '" style="width:100%">' . $status_name . '</button>';
                                                    }

                                                    $role_id = $detail['role'];
                                                    $query_rsParners =  $db->prepare("SELECT * FROM tbl_partner_roles WHERE id=:role_id");
                                                    $query_rsParners->execute(array(":role_id" => $role_id));
                                                    $row_rsParners = $query_rsParners->fetch();
                                                    $totalRows_rsParners = $query_rsParners->rowCount();
                                                    $role =  ($totalRows_rsParners > 0) ? $row_rsParners['role'] : "";

                                            ?>
                                                    <tr>
                                                        <td><?php echo $sn; ?></td>
                                                        <td><?php echo $detail['projname']; ?></td>
                                                        <td><?php echo $status; ?></td>
                                                        <td><?php echo $role; ?></td>
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
            </div>
        </section>
        <!-- end body  -->
<?php
    } else {
        $results =  restriction();
        echo $results;
    }

    require('includes/footer.php');
} catch (PDOException $ex) {
    var_dump($ex);
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>