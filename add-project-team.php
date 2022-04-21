<?php
$pageName = "Strategic Plans";
$replacement_array = array(
    'planlabel' => "CIDP",
    'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');
$pageTitle = "Add Project Team";

if ($permission) {
    try {
        if (isset($_POST["search"])) {
            $projcode = trim($_POST["srccode"]);
            $projsector = $_POST["srcsector"];
            if (!empty(($projcode)) && empty($projsector)) {
                $query_rsProjects = $db->prepare("SELECT g.progid, g.progname, g.projsector, p.projcode, p.projid, p.projname, p.projinspection, s.sector FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid inner join tbl_sectors s on s.stid=g.projdept WHERE p.projcode = :projcode and p.deleted='0' and p.projstage=2");
                $query_rsProjects->execute(array(":projcode" => $projcode));
                $row_rsProjects = $query_rsProjects->fetch();
                $totalRows_rsProjects = $query_rsProjects->rowCount();
            } elseif (empty(($projcode)) && !empty($projsector)) {
                $query_rsProjects = $db->prepare("SELECT g.progid, g.progname, g.projsector, p.projcode, p.projid, p.projname, p.projinspection, s.sector FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid inner join tbl_sectors s on s.stid=g.projdept WHERE g.projdept = :projsector and p.deleted='0' and p.projstage=2");
                $query_rsProjects->execute(array(":projsector" => $projsector));
                $row_rsProjects = $query_rsProjects->fetch();
                $totalRows_rsProjects = $query_rsProjects->rowCount();
            } elseif (!empty(($projcode)) && !empty($projsector)) {
                $query_rsProjects = $db->prepare("SELECT g.progid, g.progname, g.projsector, p.projcode, p.projid, p.projname, p.projinspection, s.sector FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid inner join tbl_sectors s on s.stid=g.projdept WHERE p.projcode = :projcode and g.projdept = :projsector and p.deleted='0' and p.projstage=2");
                $query_rsProjects->execute(array(":projcode" => $projcode, ":projsector" => $projsector));
                $row_rsProjects = $query_rsProjects->fetch();
                $totalRows_rsProjects = $query_rsProjects->rowCount();
            }
        } else {
            $query_rsProjects = $db->prepare("SELECT g.progid, g.progname, g.projsector, p.projcode, p.projid, p.projname, p.projinspection, s.sector FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid inner join tbl_sectors s on s.stid=g.projdept WHERE p.deleted='0' and p.projstage=2");
            $query_rsProjects->execute();
            $row_rsProjects = $query_rsProjects->fetch();
            $totalRows_rsProjects = $query_rsProjects->rowCount();
        }

        $query_rsTP = $db->prepare("SELECT COUNT(projname) FROM tbl_projects WHERE deleted='0' and projplanstatus='1'");
        $query_rsTP->execute();
        $row_rsTP = $query_rsTP->fetch();

        $query_rsTPList = $db->prepare("SELECT projname, COUNT(projname) FROM tbl_projects WHERE deleted='0' and projplanstatus='1' GROUP BY projname");
        $query_rsTPList->execute();
        $row_rsTPList = $query_rsTPList->fetch();

        $query_srcSector = $db->prepare("SELECT DISTINCT projdept, g.projsector FROM tbl_programs g inner join tbl_projects p on p.progid=g.progid where projplanstatus='1' ORDER BY g.projsector ASC");
        $query_srcSector->execute();
    } catch (PDOException $ex) {
        $results = flashMessage("An error occurred: " . $ex->getMessage());
    }
?>
    <style>
        #links a {
            color: #FFFFFF;
            text-decoration: none;
        }

        hr {
            display: block;
            margin-top: 0.5em;
            margin-bottom: 0.5em;
            margin-left: auto;
            margin-right: auto;
            border-style: inset;
            border-width: 1px;
        }

        @media (min-width: 1200px) {
            .modal-lg {
                width: 90%;
            }
        }
    </style>
    <!-- start body  -->
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                <i class="fa fa-users" aria-hidden="true"></i> 
                    <?php echo $pageTitle ?>
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
                                <table class="table table-bordered table-striped table-hover js-basic-example " id="manageItemTable">
                                    <thead>
                                        <tr style="background-color:#0b548f; color:#FFF">
                                            <th style="width:4%" align="center">#</th>
                                            <th style="width:12%">Project Code</th>
                                            <th style="width:50%">Project Name </th>
                                            <th style="width:25">Project Department</th>
                                            <th style="width:9%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($totalRows_rsProjects > 0) {
                                            $counter = 0;
                                            do {
                                                $projid = $row_rsProjects['projid'];
                                                $progid = $row_rsProjects['progid'];
                                                $projsector = $row_rsProjects['projsector'];
                                                $department = $row_rsProjects['sector'];

                                                $query_projsector = $db->prepare("SELECT * FROM tbl_sectors WHERE stid = :sector");
                                                $query_projsector->execute(array(":sector" => $projsector));
                                                $row_projsector = $query_projsector->fetch();
                                                $sector = $row_projsector['sector'];

                                                $query_projteam = $db->prepare("SELECT * FROM tbl_projmembers WHERE projid = :projid");
                                                $query_projteam->execute(array(":projid" => $projid));
                                                $row_projteam = $query_projteam->fetch();
                                                $totalRows_projteam = $query_projteam->rowCount();

                                                // if($totalRows_projteam < 6){
                                                $counter++;
                                        ?>
                                                <tr class="projects" style="background-color:#eff9ca">
                                                    <td align="center"><?= $counter ?></td>
                                                    <td><?php echo $row_rsProjects['projcode'] ?></td>
                                                    <td><?php echo $row_rsProjects['projname'] ?></td>
                                                    <td><?php echo $department ?></td>
                                                    <td>
                                                        <?php if ($totalRows_projteam == 0 && $file_rights->add) { ?>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    Options <span class="caret"></span>
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li>
                                                                        <a type="button" data-toggle="modal" data-target="#addFormModal" id="addFormModalBtn" onclick="getAddTeamForm(<?php echo $projid ?>)">
                                                                            <i class="fa fa-plus-square-o"></i> Add Team
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    Options <span class="caret"></span>
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li>
                                                                        <a type="button" data-toggle="modal" data-target="#moreModal" id="moreModalBtn" onclick="more(<?php echo $row_rsProjects['projid'] ?>, 1)">
                                                                            <i class="fa fa-file-text"></i> View Team
                                                                        </a>
                                                                    </li>
                                                                    <?php
                                                                    if ($file_rights->edit && $file_rights->delete_permission) {
                                                                    ?>
                                                                        <li>
                                                                            <a type="button" data-toggle="modal" data-target="#addFormModal" id="addFormModalBtn" onclick="getEditTeamForm(<?php echo $row_rsProjects['projid'] ?>)">
                                                                                <i class="fa fa-pencil-square"></i> </i> Edit Team
                                                                            </a>
                                                                        </li>
                                                                        <li><a type="button" data-toggle="modal" data-target="#removeItemModal" id="#removeItemModalBtn" onclick="removeItem(<?php echo $row_rsProjects['projid'] ?>, 2)">
                                                                                <i class="fa fa-trash-o"></i> Delete Whole Team
                                                                            </a>
                                                                        </li>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </ul>
                                                            </div>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php
                                                // }
                                            } while ($row_rsProjects = $query_rsProjects->fetch());
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="7">No Approved projects without team</td>
                                            </tr>
                                        <?php
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
    <!-- Start Item more -->
<div class="modal fade" tabindex="-1" role="dialog" id="moreModal">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#03A9F4">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info-circle"></i> More Information</h4>
            </div>
            <div class="modal-body" id="moreinfo">
            </div>
            <div class="modal-footer">
                <div class="col-md-12 text-center">
                    <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Close</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- End Item more -->

<!-- Start Item Delete -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeItemModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#03A9F4">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="color:#fff" align="center"><i class="glyphicon glyphicon-trash"></i> Delete Item</h4>
            </div>
            <div class="modal-body">
                <div class="removeItemMessages"></div>
                <p align="center">Are you sure you want to delete this record?</p>
            </div>
            <div class="modal-footer removeProductFooter">
                <div class="col-md-12 text-center">
                    <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Cancel</button>
                    <button type="button" class="btn btn-success" id="removeItemBtn"> <i class="fa fa-check-square-o"></i> Delete</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Start Item Delete -->

<!-- add item -->
<div class="modal fade" id="addFormModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" id="teamformsubmit" action="" method="POST" enctype="multipart/form-data">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center" id="addModal"><div id="title"><i class="fa fa-plus"></i> Add Team</div></h4>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="body" id="teamForm">

                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- /modal-body -->
                <div class="modal-footer">
                    <div class="col-md-12 text-center">
                        <input type="hidden" name="newitem" id="newitem" value="new">
                        <input type="hidden" name="user_name" id="user_name" value="<?=$user_name?>">
                        <input name="save" type="submit" class="btn btn-primary waves-effect waves-light" id="team-form-submit" value="Save" />
                        <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                    </div>
                </div> <!-- /modal-footer -->
            </form> <!-- /.form -->
        </div> <!-- /modal-content -->
    </div> <!-- /modal-dailog -->
</div>
<!-- End add item -->
<?php
} else {
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');
?>
<script src="general-settings/js/fetch-selected-project-team.js"></script>

<script type="text/javascript">
    function CallRiskAction(id) {
        $.ajax({
            type: 'post',
            url: 'callriskaction.php',
            data: {
                rskid: id
            },
            success: function(data) {
                $('#riskaction').html(data);
                $("#riskModal").modal({
                    backdrop: "static"
                });
            }
        });
    }

    $(document).ready(function() {
        $(".account").click(function() {
            var X = $(this).attr('id');
            if (X == 1) {
                $(".submenus").hide();
                $(this).attr('id', '0');
            } else {
                $(".submenus").show();
                $(this).attr('id', '1');
            }
        });
        //Mouseup textarea false
        $(".submenus").mouseup(function() {
            return false
        });
        $(".account").mouseup(function() {
            return false
        });

        //Textarea without editing.
        $(document).mouseup(function() {
            $(".submenus").hide();
            $(".account").attr('id', '');
        });

    });
</script>