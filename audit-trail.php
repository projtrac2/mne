<?php
    try {

require('includes/head.php');
if ($permission) {
        $query_rsAudit_Trail = $db->prepare("SELECT * FROM tbl_audit_log ");
        $query_rsAudit_Trail->execute(array(":projcontractor" => $user_name));
        $totalRows_rsAudit_Trail = $query_rsAudit_Trail->rowCount();

        function get_user($user_id)
        {
            global $db;
            $query_rsPMbrs = $db->prepare("SELECT t.*, t.email AS email, tt.title AS ttitle, u.userid FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid inner join tbl_titles tt on tt.id=t.title WHERE userid = :user_id ORDER BY ptid ASC");
            $query_rsPMbrs->execute(array(":user_id" => $user_id));
            $row_rsPMbrs = $query_rsPMbrs->fetch();
            $count_row_rsPMbrs = $query_rsPMbrs->rowCount();
            return $count_row_rsPMbrs > 0 ?  $row_rsPMbrs['ttitle'] . ". " . $row_rsPMbrs['fullname'] : "";
        }
?>
        <section class="content">
            <div class="container-fluid">
                <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                    <h4 class="contentheader">
                        <?= $icon . ' ' . $pageTitle ?>
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
                                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="manageItemTable">
                                        <thead>
                                            <tr style="background-color:#0b548f; color:#FFF">
                                                <th style="width:5%" align="center">#</th>
                                                <th style="width:30%">User</th>
                                                <th style="width:30%">Page </th>
                                                <th style="width:10%">Action</th>
                                                <th style="width:10%">Outcome</th>
                                                <th style="width:15%">Action Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($totalRows_rsAudit_Trail > 0) {
                                                $counter = 0;
                                                while ($row_rsAudit_Trail = $query_rsAudit_Trail->fetch()) {
                                                    $counter++;
                                                    $user_id = $row_rsAudit_Trail['user_id'];
                                                    $page_url = $row_rsAudit_Trail['page_url'];
                                                    $action = $row_rsAudit_Trail['action'];
                                                    $outcome = $row_rsAudit_Trail['outcome'];
                                                    $created_at = $row_rsAudit_Trail['created_at'];
                                                    $user = get_user($user_id);
                                            ?>
                                                    <tr>
                                                        <td align="center"><?= $counter ?></td>
                                                        <td><?= $user ?></td>
                                                        <td><?= $page_url ?></td>
                                                        <td><?= $action ?></td>
                                                        <td><?= $outcome ?></td>
                                                        <td><?= $created_at ?></td>
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

} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}

require('includes/footer.php');
?>