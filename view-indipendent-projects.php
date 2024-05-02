<?php
try {
    require('includes/head.php');
    if ($permission) {
        $decode_progid = (isset($_GET['prg']) && !empty($_GET["prg"])) ? base64_decode($_GET['prg']) : "";
        $progid_array = explode("progid54321", $decode_progid);
        $progid = $progid_array[1];

?>
        <!-- start body  -->
        <section class="content">
            <div class="container-fluid">
                <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                    <h4 class="contentheader">
                        <?= $icon ?>
                        <?= $pageTitle ?>
                        <div class="btn-group" style="float:right">
                            <button onclick="history.go(-1)" class="btn bg-orange waves-effect pull-right" style="margin-right: 10px">
                                Go Back
                            </button>
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
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="28%">Project Name</th>
                                                <th width="27%">Program Name</th>
                                                <th width="10%">Budget</th>
                                                <th width="10%">Financial Year</th>
                                                <th width="8%">Status</th>
                                                <th width="8%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = $db->prepare("SELECT * FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid inner join tbl_sectors s on s.stid=g.projdept WHERE g.progid = :progid ORDER BY `projfscyear` ASC");
                                            $sql->execute(array(":progid" => $progid));
                                            $rows_count = $sql->rowCount();
                                            if ($rows_count > 0) {
                                                $sn = 0;
                                                while ($row = $sql->fetch()) {
                                                    $projid = $row['projid'];
                                                    $budget = $row['projcost'];
                                                    $projname = $row["projname"];
                                                    $progid = $row["progid"];
                                                    $srcfyear = $row["projfscyear"];
                                                    $project_department = $row['projsector'];
                                                    $project_section = $row['projdept'];
                                                    $project_directorate = $row['directorate'];
                                                    $approved = $row['projplanstatus'];

                                                    //get financial year
                                                    $query_projYear = $db->prepare("SELECT * FROM `tbl_fiscal_year` WHERE id=:srcfyear LIMIT 1");
                                                    $query_projYear->execute(array(":srcfyear" => $srcfyear));
                                                    $rowprojYear = $query_projYear->fetch();
                                                    $projYear  = $rowprojYear ?  $rowprojYear['year'] : '';

                                                    // get department
                                                    $query_rsDept =  $db->prepare("SELECT stid, sector FROM tbl_sectors WHERE parent IS NOT NULL  and stid =:sector LIMIT 1");
                                                    $query_rsDept->execute(array(":sector" => $project_section));
                                                    $row_rsDept = $query_rsDept->fetch();
                                                    $department = $row_rsDept['sector'];
                                                    $totalRows_rsDept = $query_rsDept->rowCount();

                                                    $progname =
                                                        '<span data-container="body" data-toggle="tooltip" data-html="true" data-placement="right" title="' . $department . '" style="color:#2196F3">' . $row["progname"] . '</span>';

                                                    $projstatus = "<label class='label label-danger'>Pending Approval</div>";
                                                    if ($approved == 1) {
                                                        $projstatus = "<label class='label label-success'>Approved</div>";
                                                    } else {
                                                        $query_rsOutput =  $db->prepare("SELECT * FROM  tbl_project_details WHERE  projid=:projid ");
                                                        $query_rsOutput->execute(array(":projid" => $projid));
                                                        $totalRows_rsOutput = $query_rsOutput->rowCount();
                                                        if ($totalRows_rsOutput == 0) {
                                                            $projstatus = "<label class='label label-danger'>Pending M&E Plan</div>";
                                                        }
                                                    }

                                                    $month =  date('m');
                                                    $filter_department = view_record($project_department, $project_section, $project_directorate);
                                                    if ($filter_department) {
                                                        $sn++;
                                            ?>
                                                        <tr>
                                                            <td><?= $sn ?> </td>
                                                            <td><?= $projname ?> </td>
                                                            <td><?= $progname ?> </td>
                                                            <td><?= number_format($budget, 2) ?> </td>
                                                            <td><?= $projYear ?> </td>
                                                            <td><?= $projstatus ?> </td>
                                                            <td>
                                                                <a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="project_info(<?= $projid ?>)" class="btn btn-info btn-xm"> <i class="fa fa-info fa-lg" aria-hidden="true"></i> More Info</a>
                                                            </td>
                                                        </tr>
                                            <?php
                                                    }
                                                }
                                            } // if num_rows
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

        <!-- Start Item more Info -->
        <div class="modal fade" tabindex="-1" role="dialog" id="moreItemModal">
            <div class="modal-dialog  modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="background-color:#03A9F4">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info"></i> More Information</h4>
                    </div>
                    <div class="modal-body" id="moreinfo">
                    </div>
                    <div class="modal-footer">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                            <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Close</button>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <!-- End  Item more Info -->
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
<script src="assets/js/projects/view-project.js"></script>