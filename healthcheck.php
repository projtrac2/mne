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
                                                <th style="width:30%"> Item</th>
                                                <th style="width:30%"> Status </th>
                                                <th style="width:30%"> Time </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Server load</td>
                                                <td>
                                                    <label class='label label-success'>Healthy
                                                </td>
                                                <td>2024-03-30 13:11:10</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>File system</td>
                                                <td>
                                                    <label class='label label-success'>Healthy
                                                </td>
                                                <td>2024-03-30 13:11:10</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Database</td>
                                                <td>
                                                    <label class='label label-success'>Healthy
                                                </td>
                                                <td>2024-03-30 13:11:10</td>
                                            </tr>
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