<?php
require('includes/head.php');
if ($permission) {
    try {
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
                                                <th width="55%">Project Name</th>
                                                <th width="10%">Budget</th>
                                                <th width="10%">Financial Year</th>
                                                <th width="10%">Status</th>
                                                <th width="10%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = $db->prepare("SELECT * FROM tbl_projects  ");
                                            $sql->execute();
                                            $rows_count = $sql->rowCount();
                                            if ($rows_count > 0) {
                                                $sn = 0;
                                                while ($row = $sql->fetch()) {
                                                    $projid = $row['projid'];
                                                    $budget = $row['projcost'];
                                                    $projname = $row["projname"];
                                                    $progid = $row["progid"];
                                                    $srcfyear = $row["projfscyear"];
                                                    $approved = $row['projplanstatus'];

                                                    //get financial year
                                                    $query_projYear = $db->prepare("SELECT * FROM `tbl_fiscal_year` WHERE id=:srcfyear LIMIT 1");
                                                    $query_projYear->execute(array(":srcfyear" => $srcfyear));
                                                    $rowprojYear = $query_projYear->fetch();
                                                    $projYear  = $rowprojYear ?  $rowprojYear['year'] : '';
                                                    $status = "Pending";
                                                    $sn++;
                                            ?>
                                                    <tr>
                                                        <td><?= $sn ?> </td>
                                                        <td><?= $projname ?> </td>
                                                        <td><?= number_format($budget, 2) ?> </td>
                                                        <td><?= $projYear ?> </td>
                                                        <td><?= $status ?> </td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    Options <span class="caret"></span>
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li>
                                                                        <a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="project_info(<?= $projid ?>)">
                                                                            <i class="glyphicon glyphicon-file"></i> More Info
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a type="button" href="add-project-mne-plan.php?projid=<?= $projid_hashed ?>">
                                                                            <i class="glyphicon glyphicon-plus"></i>Add M&E Plan
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a type="button" href="add-project-mne-plan.php?projid=<?= $projid_hashed ?>">
                                                                            <i class="glyphicon glyphicon-plus"></i>Add Risk Plan
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a type="button" id="removeItemModalBtn" onclick="removeItem(<?= $projid ?>)">
                                                                            <i class="glyphicon glyphicon-trash"></i> Review
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a type="button" id="removeItemModalBtn" onclick="removeItem(<?= $projid ?>)">
                                                                            <i class="glyphicon glyphicon-trash"></i> Approve
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                            <?php

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
    } catch (PDOException $ex) {
        $result = flashMessage("An error occurred: " . $ex->getMessage());
    }
} else {
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');
?>
<script src="assets/js/projects/view-project.js"></script>