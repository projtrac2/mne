<?php
require('includes/head.php');
if ($permission) {
    try {
        $sql_ind_programs = $db->prepare("SELECT * FROM `tbl_programs`  ORDER BY `progid` ASC");
        $sql_ind_programs->execute();
        $totalRows_ind_programs = $sql_ind_programs->rowCount();

        function get_sector($sector_id)
        {
            global $db;
            $query_rsDepart = $db->prepare("SELECT stid,sector FROM tbl_sectors WHERE stid=:sector_id ");
            $query_rsDepart->execute(array(":sector_id" => $sector_id));
            $row_rsDepart = $query_rsDepart->fetch();
            return $row_rsDepart ? $row_rsDepart['sector'] : '';
        }

?>
        <!-- start body  -->
        <section class="content">
            <div class="container-fluid">
                <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                    <h4 class="contentheader">
                        <?= $icon . " " . $pageTitle ?>
                        <div class="btn-group" style="float:right">
                            <div class="btn-group" style="float:right">
                                <a href="add-program.php" class="btn btn-success">New Program </a>
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
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <!-- ============================================================== -->
                                        <!-- Start Page Content -->
                                        <!-- ============================================================== -->
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="indepedentPrograms" style="width:100%" id="indepedentPrograms">
                                                <thead style="width:100%">
                                                    <tr class="bg-orange" style="width:100%">
                                                        <th width="3%">#</th>
                                                        <th width="40%">Program</th>
                                                        <th width="40%"><?= $ministrylabel ?></th>
                                                        <th width="40%"><?= $departmentlabel ?></th>
                                                        <th width="40%"><?= $directoratelabel ?></th>
                                                        <th width="7%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sql = $db->prepare("SELECT * FROM `tbl_programs`  ORDER BY `progid` ASC");
                                                    $sql->execute();
                                                    $rows_count = $sql->rowCount();
                                                    $output = array('data' => array());
                                                    if ($rows_count > 0) {
                                                        $sn = 0;
                                                        while ($row_rsProgram = $sql->fetch()) {
                                                            $progid = $row_rsProgram['progid'];
                                                            $progname =  $row_rsProgram['progname'];
                                                            $created_by = $row_rsProgram['createdby'];
                                                            $progid_hashed = base64_encode("progid54321{$progid}");
                                                            $project_department = $row_rsProgram['projsector'];
                                                            $project_section = $row_rsProgram['projdept'];
                                                            $project_directorate = $row_rsProgram['directorate'];
                                                            $filter_department = view_record($project_department, $project_section, $project_directorate);
                                                            if ($filter_department) {
                                                                $sn++;
                                                    ?>
                                                                <tr>
                                                                    <td><?= $sn ?></td>
                                                                    <td><?= $progname ?></td>
                                                                    <td><?= get_sector($project_department) ?></td>
                                                                    <td><?= get_sector($project_section) ?></td>
                                                                    <td><?= get_sector($project_directorate) ?></td>
                                                                    <td>
                                                                        <!-- Single button -->
                                                                        <div class="btn-group">
                                                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                Options <span class="caret"></span>
                                                                            </button>
                                                                            <ul class="dropdown-menu">
                                                                                <li><a type="button" data-toggle="modal" data-target="#progmoreInfoModal" id="progmoreInfoModalBtn" onclick="program_info(<?= $progid ?>)">
                                                                                        <i class="glyphicon glyphicon-file"></i> More Info</a>
                                                                                </li>
                                                                                <li>
                                                                                    <a type="button" data-toggle="modal" id="editprogram" href="add-program-details.php?progid=<?= $progid_hashed ?>">
                                                                                        <i class="glyphicon glyphicon-edit"></i> Add to Strategic Plan
                                                                                    </a>
                                                                                </li>
                                                                                <li>
                                                                                    <a type="button" data-toggle="modal" id="editprogram" href="edit-program?progid=<?= $progid_hashed ?>">
                                                                                        <i class="glyphicon glyphicon-edit"></i> Edit
                                                                                    </a>
                                                                                </li>
                                                                                <li>
                                                                                    <a type="button" data-toggle="modal" data-target="#removeItemModal" id="removeItemModalBtn" onclick="removeItem(<?= $itemId ?>)"> <i class="glyphicon glyphicon-trash"></i> Delete
                                                                                    </a>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                    <?php
                                                            }
                                                        } // /while

                                                    } // if num_rows
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- ============================================================== -->
                                        <!-- End PAge Content -->
                                        <!-- ============================================================== -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
        <!-- end body  -->
        <!-- Start Item more -->
        <div class="modal fade" tabindex="-1" role="dialog" id="progmoreInfoModal">
            <div class="modal-dialog  modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="background-color:#03A9F4">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info" style="font-size:24px"></i> Program More Information</h4>
                    </div>
                    <div class="modal-body" id="progmoreinfo">
                    </div>
                    <div class="modal-footer">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Close</button>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!-- End Item more -->
<?php
    } catch (PDOException $ex) {
        var_dump($ex);
        $results = flashMessage("An error occurred: " . $ex->getMessage());
    }
} else {
    $results =  restriction();
    echo $results;
}
require('includes/footer.php');
?>
<script src="assets/js/programs/view-programs.js"></script>