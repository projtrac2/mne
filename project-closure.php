<?php
require('includes/head.php');
if ($permission) {
    try {
        $query_rsProjects = $db->prepare("SELECT p.*, s.sector, g.projsector, g.projdept, g.directorate FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid inner join tbl_sectors s on g.projdept=s.stid WHERE p.deleted='0' AND p.projstage = :workflow_stage ORDER BY p.projid DESC");
        $query_rsProjects->execute(array(":workflow_stage" => $workflow_stage));
        $totalRows_rsProjects = $query_rsProjects->rowCount();
?>
        <!-- start body  -->
        <section class="content">
            <div class="container-fluid">
                <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                    <h4 class="contentheader">
                        <?= $icon . " " . $pageTitle ?>
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
                                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                        <thead>
                                            <tr style="background-color:#0b548f; color:#FFF">
                                                <th style="width:5%" align="center">#</th>
                                                <th style="width:10%">Code</th>
                                                <th style="width:50%">Project </th>
                                                <th style="width:10">Due Date</th>
                                                <th style="width:10">Status</th>
                                                <th style="width:5%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($totalRows_rsProjects > 0) {
                                                $counter = 0;
                                                while ($row_rsProjects = $query_rsProjects->fetch()) {
                                                    $projid = $row_rsProjects['projid'];
                                                    $projid_hashed = base64_encode("projid54321{$projid}");
                                                    $projname = $row_rsProjects['projname'];
                                                    $sub_stage = $row_rsProjects['proj_substage'];
                                                    $project_department = $row_rsProjects['projsector'];
                                                    $project_section = $row_rsProjects['projdept'];
                                                    $project_directorate = $row_rsProjects['directorate'];

                                                    $filter_department = view_record($project_department, $project_section, $project_directorate);
                                                    $assigned_responsible = check_if_assigned($projid, $workflow_stage, $sub_stage, 1);
                                                    $assign_responsible = in_array("assign_data_entry_responsible", $page_actions) || in_array("assign_approval_responsible", $page_actions) ? true : false;

                                                    if ($filter_department) {
                                                        $counter++;
                                                        $today = date('Y-m-d');
                                                        $due_date = get_due_date($projid, $workflow_stage);
                                                        $activity_status =  ($today > $due_date) ? "Behind Schedule" :  "Pending";
                                                        $details = "{projid:$projid,workflow_stage:$workflow_stage, project_name:'$projname',}";
                                            ?>
                                                        <tr>
                                                            <td align="center"><?= $counter ?></td>
                                                            <td><?= $row_rsProjects['projcode'] ?></td>
                                                            <td><?= $row_rsProjects['projname'] ?></td>
                                                            <td><?= date('Y M d', strtotime($due_date))  ?></td>
                                                            <td><label class='label label-success'><?= $activity_status; ?></label></td>
                                                            <td>
                                                                <div class="btn-group">
                                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        Options <span class="caret"></span>
                                                                    </button>
                                                                    <ul class="dropdown-menu">
                                                                        <li>
                                                                            <a type="button" data-toggle="modal" data-target="#moreModal" id="moreModalBtn" onclick="project_info(<?= $projid ?>)">
                                                                                <i class="fa fa-file-text"></i> View More
                                                                            </a>
                                                                        </li>
                                                                        <?php
                                                                        if ($assigned_responsible) {
                                                                        ?>
                                                                            <li>
                                                                                <a type="button" data-toggle="modal" data-target="#closureItemModal" data-backdrop="static" data-keyboard="false" onclick="add_project_closure(<?= $details ?>)">
                                                                                    <i class="fa fa-exclamation-triangle text-danger"></i> Closure
                                                                                </a>
                                                                            </li>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </ul>
                                                                </div>
                                                            </td>
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
        </section>
        <!-- end body  -->

        <div class="modal fade" id="closureItemModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="background-color:#03A9F4">
                        <h4 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-plus"></i> <span id="modal_info"> Project</span></h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12">
                            <ul class="list-group">
                                <li class="list-group-item list-group-item list-group-item-action active">Project : <span id="project"></span> </li>
                            </ul>
                        </div>
                        <form class="form-horizontal" id="add_closure_details" action="" method="POST">
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                    <i class="fa fa-comment" aria-hidden="true"></i> Closure Remark(s)
                                </legend>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label class="control-label">Remarks *:</label>
                                    <div class="form-line">
                                        <textarea name="comments" cols="" rows="7" class="form-control" id="comment" placeholder="Enter Comments if necessary" style="width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required></textarea>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">
                                    <i class="fa fa-paperclip" aria-hidden="true"></i> Means of Verification (Files/Documents)
                                </legend>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th style="width:2%">#</th>
                                                        <th style="width:40%">Attachments</th>
                                                        <th style="width:58%">Attachment Purpose</th>
                                                        <th style="width:2%"><button type="button" name="addplus" onclick="add_attachment();" title="Add another document" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="attachments_table1">
                                                    <tr>
                                                        <td>1</td>
                                                        <td>
                                                            <input type="file" name="closureattachment[]" id="closureattachment[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
                                                        </td>
                                                        <td>
                                                            <input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control" placeholder="Enter the purpose of this document" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="modal-footer">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    <input type="hidden" name="store_closure" id="store_closure" value="store_closure">
                                    <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
                                    <input type="hidden" name="workflow_stage" id="workflow_stage" value="<?= $workflow_stage ?>">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light" value="button" id="tag-form-submit1"> Save</button>
                                </div>
                            </div> <!-- /modal-footer -->
                        </form>
                    </div> <!-- /modal-footer -->
                </div> <!-- /modal-content -->
            </div> <!-- /modal-dailog -->
        </div>
        <!-- End add item -->
<?php
    } catch (PDOException $ex) {
        customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
    }
} else {
    $results =  restriction();
    echo $results;
}
require('includes/footer.php');
?>
<script>
    const redirect_url = "project-closure.php";
</script>
<script src="assets/js/projects/view-project.js"></script>

<script>
    var closure_ajax_url = "ajax/closure/index";
    $(document).ready(function() {
        $("#add_closure_details").submit(function(e) {
            e.preventDefault();
            $("#tag-form-submit1").prop("disabled", false);

            var form = $('#add_closure_details')[0];
            var form_data = new FormData(form);
            $.ajax({
                type: "post",
                url: closure_ajax_url,
                data: form_data,
                processData: false,
                contentType: false,
                cache: false,
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        success_alert("Closure successful");
                        setTimeout(() => {
                            window.location.reload(true);
                        }, 3000);
                    } else {
                        error_alert("Error !!! Could not closure project");
                        setTimeout(() => {
                            window.location.reload(true);
                        }, 3000);
                    }
                }
            });

        });
    });

    function add_project_closure(details) {
        $("#projid").val(details.projid);
        $("#project").html(details.project_name);
        $("#workflow_stage").val(details.workflow_stage);
    }

    function add_attachment() {
        var rand = Math.floor(Math.random() * 6) + 1;
        var rowno = $("#attachments_table1 tr").length + "" + rand + "" + Math.floor(Math.random() * 7) + 1;
        $("#attachments_table1 tr:last").after(`
            <tr id="rw${rowno}">
                <td>1</td>
                <td>
                    <input type="file" name="closureattachment[]"  id="closureattachment[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
                </td>
                <td>
                    <input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control"  placeholder="Enter the purpose of this document" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"/>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm"  onclick=delete_attach("rw${rowno}")>
                        <span class="glyphicon glyphicon-minus"></span>
                    </button>
                </td>
            </tr>`);
        number_table();
    }

    function delete_attach(rownm) {
        $("#" + rownm).remove();
        number_table();
    }

    function number_table() {
        $("#attachments_table1 tr").each(function(idx) {
            $(this)
                .children()
                .first()
                .html(idx + 1);
        });
    }
</script>