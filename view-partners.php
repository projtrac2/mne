<?php
try {
    include 'includes/head.php';
    if ($permission) {
        $query_rspartner = $db->prepare("SELECT * FROM tbl_partners");
        $query_rspartner->execute();
        $totalRows_rspartner = $query_rspartner->rowCount();
?>
        <!-- start body  -->
        <section class="content">
            <div class="container-fluid">
                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                            <h4 class="contentheader">
                                <?= $icon ?>
                                <?= $pageTitle ?>
                                <div class="btn-group" style="float:right">
                                    <div class="btn-group" style="float:right">
                                        <?php
                                        if (in_array("create", $page_actions)) {
                                        ?>
                                            <div style="float:right; margin-top:0px; margin-right:5px">
                                                <a href="add-partner.php" class="btn btn-primary pull-right">Add Partner</a>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </h4>
                        </div>
                    </div>
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
                                            <tr id="colrow">
                                                <th width="4%"><strong>#</strong></th>
                                                <th width="35%"><strong>Partner</strong></th>
                                                <th width="15%"><strong>Contact</strong></th>
                                                <th width="10%"><strong>Phone</strong></th>
                                                <th width="15%"><strong>Projects</strong></th>
                                                <th width="5%"><strong>Status</strong></th>
                                                <th width="10%"><strong>Action</strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- =========================================== -->
                                            <?php
                                            if ($totalRows_rspartner > 0) {
                                                $sn = 0;
                                                while ($row_rspartner = $query_rspartner->fetch()) {
                                                    $sn++;
                                                    $country = $row_rspartner['country'];
                                                    $finstatus = $row_rspartner['active'];
                                                    $fnid = $row_rspartner['id'];
                                                    $hashfnid = base64_encode("fn918273AxZID{$fnid}");

                                                    $success = "Successfully enabled partner ";
                                                    $status_text = " Are you sure you want to enable partner";
                                                    $update_status = 1;
                                                    if ($finstatus == 1) {
                                                        $status_text = " Are you sure you want to disable partner";
                                                        $success = "Successfully disabled Partner ";
                                                        $update_status = 0;
                                                    }

                                                    $query_partnerprojs = $db->prepare("SELECT p.* FROM tbl_projects p inner join tbl_myprojpartner m on p.projid=m.projid WHERE p.deleted='0' and m.partner_id = :fnid ORDER BY m.id ASC");
                                                    $query_partnerprojs->execute(array(":fnid" => $fnid));
                                                    $row_partnerprojs = $query_partnerprojs->rowCount();

                                                    if ($row_rspartner['active'] == 1) {
                                                        $active = '<i class="fa fa-check-square" style="font-size:18px;color:green" title="Active"></i>';
                                                    } else {
                                                        $active = '<i class="fa fa-exclamation-triangle" style="font-size:18px;color:#bc2d10" title="Disabled"></i>';
                                                    }
                                            ?>
                                                    <tr style="border-bottom:thin solid #EEE">
                                                        <td><?php echo $sn; ?></td>
                                                        <td><?php echo $row_rspartner['partner']; ?></td>
                                                        <td><?php echo $row_rspartner['contact']; ?> (<?php echo $row_rspartner['designation']; ?>)</td>
                                                        <td><a href="tel:<?php echo $row_rspartner['phone']; ?>"><?php echo $row_rspartner['phone']; ?></a></td>
                                                        <td align="center">
                                                            <span class="badge bg-brown">
                                                                <a href="view-partner-projects.php?fndid=<?php echo base64_encode($fnid); ?>" style="font-family:Verdana, Geneva, sans-serif; color:white; font-size:12px; padding-top:0px">
                                                                    <?php echo $row_partnerprojs; ?>
                                                                </a>
                                                        </td>
                                                        <td align="center"><?= $active ?></td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" onchange="checkBoxes()" aria-haspopup="true" aria-expanded="false">
                                                                    Options <span class="caret"></span>
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li>
                                                                        <a type="button" href="view-partner-info.php?fn=<?php echo $hashfnid; ?>"><i class="fa fa-plus-square"></i> More Info</a>
                                                                    </li>
                                                                    <li>
                                                                        <a type="button" onclick="update_partner_status(<?= $fnid ?>, '<?= $status_text ?>', '<?= $success ?>', <?= $update_status ?>)"><i class="fa fa-plus-square"></i> <?= $finstatus == 0 ? "Activate" : "Deactivate" ?></a>
                                                                    </li>
                                                                    <?php
                                                                    if ($finstatus == 1) {
                                                                        if (in_array("create", $page_actions)) {
                                                                    ?>
                                                                            <li>
                                                                                <a type="button" href="edit-partner.php?fn=<?php echo $hashfnid; ?>">
                                                                                    <i class="glyphicon glyphicon-edit"></i> Edit </a>
                                                                            </li>
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </ul>
                                                            </div>
                                                        </td>

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
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>
<script>
    function update_partner_status(partner_id, status_text, success, status) {
        swal({
                title: "Are you sure?",
                text: status_text,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: "post",
                        url: "ajax/partners/others",
                        data: {
                            update_status: 'update_status',
                            partner_id: partner_id,
                            status: status,
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.success == true) {
                                swal({
                                    title: "Financier !",
                                    text: success,
                                    icon: "success",
                                });
                            } else {
                                swal({
                                    title: "Financier !",
                                    text: "Error changing status",
                                    icon: "error",
                                });
                            }
                            
                            setTimeout(function() {
                                window.location.reload(true);
                            }, 3000);
                        },
                    });
                } else {
                    swal("You cancelled the action!");
                }
            });
    }
</script>