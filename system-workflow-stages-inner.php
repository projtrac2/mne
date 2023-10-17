<?php

?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="card">
        <div class="header">
            <div style="color:#333; background-color:#EEE; width:100%; height:30px">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-5px">
                    <tr>
                        <td width="80%" height="35" style="padding-left:5px; background-color:#607D8B; color:#FFF">
                            <div align="left" style="vertical-align: text-bottom">
                                <font size="3" color="#FFC107"><i class="fa fa-cog" aria-hidden="true"></i> <strong>System Workflow Stages</strong></font>
                            </div>
                        </td>
                        <td width="20%" height="35" style="padding-left:5px; background-color:#607D8B; color:#FFF">
                            <button type="button" id="modal_button" class="pull-right btn bg-deep-purple" data-toggle="modal" id="addItemModalBtn" data-target="#addItemModal">
                                <i class="fa fa-plus-square"></i> Add Item
                            </button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Stage</th>
                            <th>Parent</th>
                            <th>Descrption</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = $db->prepare("SELECT * FROM `tbl_project_workflow_stage` ORDER BY `id` ASC");
                        $sql->execute();
                        $rows_count = $sql->rowCount();
                        $output = array('data' => array());
                        if ($rows_count > 0) {
                            $active = "";
                            $sn = 0;
                            while ($row = $sql->fetch()) {
                                $sn++;
                                $itemId = $row['id'];
                                $stage = $row["stage"];
                                $description = $row["description"];
                                $parentid = $row["parent"];
                                $priority = $row["priority"];
                                // status
                                $active = ($row['active'] == 1) ? "<label class='label label-success'>Enabled</label>" : "<label class='label label-danger'>Disabled</label>";
                                if ($parentid > 0) {
                                    $sqlparent = $db->prepare("SELECT * FROM `tbl_project_workflow_stage` WHERE id='$parentid' ");
                                    $sqlparent->execute();
                                    $rowparent = $sqlparent->fetch();
                                    $parent = $rowparent['stage'];
                                } else {
                                    $parent = "N/A";
                                }


                                $details = "{
                                    id:$itemId,
                                    stage:$stage,
                                    description:$description,
                                    parentid:$parentid,
                                    priority:$priority,
                                }"
                        ?>
                                <tr>
                                    <td><?= $sn ?></td>
                                    <td><?= $stage ?></td>
                                    <td><?= $parent ?></td>
                                    <td><?= $description ?></td>
                                    <td><?= $priority ?></td>
                                    <td><?= $active ?></td>
                                    <td>
                                        <!-- Single button -->
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Options <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a type="button" data-toggle="modal" id="editItemModalBtn" data-target="#addItemModal" onclick="edit_workflow(<?= $details ?>)">
                                                        <i class="glyphicon glyphicon-edit"></i> Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <a type="button" data-toggle="modal" id="addTimelineModalBTN" data-target="#addTimelineModal" onclick="editItem(<?= $itemId ?>)">
                                                        <i class="glyphicon glyphicon-edit"></i> Timelines
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                        <?php
                            } // /while

                        } // if num_rows
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>

<!-- add item -->
<div class="modal fade" id="addItemModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" id="submitItemForm" action="" method="POST" enctype="multipart/form-data">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-plus"></i> Add Workflow Stage</h4>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="body">
                                    <div id="add-item-messages"></div>
                                    <div class="col-md-12 form-input">
                                        <label>
                                            <font color="#174082">Stage Name: </font>
                                        </label>
                                        <input type="text" class="form-control" id="stage" placeholder="Enter stage name" name="stage" required autocomplete="off">
                                    </div>
                                    <div class="col-md-12 form-input">
                                        <label>
                                            <font color="#174082">Stage Parent: </font>
                                        </label>
                                        <select name="parent" id="parent" class="form-control show-tick selectpicker" data-live-search="false" required>
                                            <option value="" selected="selected" class="selection">Choose Parent</option>
                                            <option value="0">N/A</option>
                                            <?php
                                            $query_stage =  $db->prepare("SELECT * FROM tbl_project_workflow_stage WHERE active=1 ORDER BY id ASC");
                                            $query_stage->execute();
                                            while ($row_stage = $query_stage->fetch()) {
                                            ?>
                                                <option value="<?php echo $row_stage['id'] ?>"><?php echo $row_stage['stage'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-12 form-input">
                                        <label>
                                            <font color="#174082">Stage Priority: </font>
                                        </label>
                                        <select name="parent" id="parent" class="form-control show-tick selectpicker" data-live-search="false" required>
                                            <option value="" selected="selected" class="selection">Choose Priority</option>
                                            <option value="0">0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                            <option value="13">13</option>
                                            <option value="14">14</option>
                                            <option value="15">15</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 form-input">
                                        <label>
                                            <font color="#174082">Description: </font>
                                        </label>
                                        <input type="text" class="form-control" id="description" placeholder="Describe the entered stage" name="description" autocomplete="off">
                                    </div>
                                    <!-- /form-group-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- /modal-body -->

                <div class="modal-footer">
                    <div class="col-md-12 text-center">
                        <input type="hidden" name="newitem" id="newitem" value="new">
                        <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                        <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
                    </div>
                </div> <!-- /modal-footer -->
            </form> <!-- /.form -->
        </div> <!-- /modal-content -->
    </div> <!-- /modal-dailog -->
</div>
<!-- End add item -->

<!-- add item -->
<div class="modal fade" id="addTimelineModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" id="submitPermissionForm" action="" method="POST" enctype="multipart/form-data">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-plus"></i> Add Stage Timeline</h4>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <fieldset class="scheduler-border row setup-content" id="step-1" style="padding:10px">
                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Stage Timline</legend>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="financier_table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th>Workflow Stage</th>
                                            <th>Description</th>
                                            <th>Units</th>
                                            <th>Time Frame </th>
                                            <th>Escalate After </th>
                                            <th>Escalate To</th>
                                            <th>Status</th>
                                            <th width="5%">
                                                <button type="button" name="addplus" id="addplus_financier" onclick="add_row_financier();" class="btn btn-success btn-sm">
                                                    <span class="glyphicon glyphicon-plus">
                                                    </span>
                                                </button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="stage_timelines">
                                        <tr>
                                            <td>
                                                <select name="category" id="category" class="form-control show-tick" data-live-search="true" required>
                                                    <option value="" selected="selected" class="selection">Choose Category</option>
                                                    <?php
                                                    $query_workflow =  $db->prepare("SELECT * FROM tbl_project_workflow_stage WHERE active=1 ORDER BY id ASC");
                                                    $query_workflow->execute();
                                                    $rows_workflow = $query_workflow->fetch();
                                                    $totalRows_workflow = $query_workflow->rowCount();
                                                    while ($rows_workflow = $query_workflow->fetch()) {
                                                    ?>
                                                        <option value="<?php echo $rows_workflow['id'] ?>"><?php echo $rows_workflow['stage'] ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="workflow" id="workflow" class="form-control show-tick" data-live-search="true" required>
                                                    <option value="" selected="selected" class="selection">Choose workflow stage</option>
                                                    <?php
                                                    $query_workflow =  $db->prepare("SELECT * FROM tbl_project_workflow_stage WHERE active=1 ORDER BY id ASC");
                                                    $query_workflow->execute();
                                                    $rows_workflow = $query_workflow->fetch();
                                                    $totalRows_workflow = $query_workflow->rowCount();
                                                    while ($rows_workflow = $query_workflow->fetch()) {
                                                    ?>
                                                        <option value="<?php echo $rows_workflow['id'] ?>"><?php echo $rows_workflow['stage'] ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <textarea class="form-control" id="description" placeholder="More info about the timeline if necessary" name="description" autocomplete="off"></textarea>
                                            </td>
                                            <td>
                                                <select name="units" id="units" class="form-control show-tick selectpicker" data-live-search="false" required>
                                                    <option value="" selected="selected" class="selection">Choose Timeline Unit</option>
                                                    <?php
                                                    $query_designation =  $db->prepare("SELECT * FROM tbl_pmdesignation WHERE Reporting <= 6 and active=1 ORDER BY moid ASC");
                                                    $query_designation->execute();;
                                                    $totalRows_designation = $query_designation->rowCount();
                                                    while ($rows_designation = $query_designation->fetch()) {
                                                    ?>
                                                        <option value="<?php echo $rows_designation['moid'] ?>"><?php echo $rows_designation['designation'] ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" id="time" placeholder="Amount of time in numbers" name="time" required autocomplete="off">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" id="time" placeholder="Amount of time in numbers" name="esacalte_after" required autocomplete="off">
                                            </td>
                                            <td>
                                                <select name="escalateto" id="escalateto" class="form-control show-tick selectpicker" data-live-search="false" required>
                                                    <option value="" selected="selected" class="selection">Choose designation to escalate to</option>
                                                    <?php
                                                    $query_designation =  $db->prepare("SELECT * FROM tbl_pmdesignation WHERE Reporting <= 6 and active=1 ORDER BY moid ASC");
                                                    $query_designation->execute();;
                                                    $totalRows_designation = $query_designation->rowCount();
                                                    while ($rows_designation = $query_designation->fetch()) {
                                                    ?>
                                                        <option value="<?php echo $rows_designation['moid'] ?>"><?php echo $rows_designation['designation'] ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="status" id="status" class="form-control show-tick" data-live-search="false" required>
                                                    <option value="" class="selection">Select Status</option>
                                                    <option value="1">Enable</option>
                                                    <option value="0">Disable</option>
                                                </select>
                                            </td>
                                            <td width="5%"> </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </fieldset>
                    </div>
                </div> <!-- /modal-body -->
                <div class="modal-footer">
                    <div class="col-md-12 text-center">
                        <input type="hidden" name="store_timeline" id="store_timeline" value="new">
                        <input type="hidden" name="stage_id" id="stage_id" value="">
                        <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="tag-form-submit" value="Save" />
                        <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                    </div>
                </div> <!-- /modal-footer -->
            </form> <!-- /.form -->
        </div> <!-- /modal-content -->
    </div> <!-- /modal-dailog -->
</div>
<!-- End add item -->


<script src="assets/js/settings/permission.js"></script>

<script>
    const edit_workflow = (details)=>{
        console.log(details);
    }
</script>