<?php
$replacement_array = array(
    'planlabel' => "CIDP",
    'plan_id' => base64_encode(6),
);

$page = "view";

require('includes/head.php');
if ($permission) {
$pageTitle = "Risk Mitigations";
try {
    $query_allcategories = $db->prepare("SELECT rskid, category FROM tbl_projrisk_categories");
    $query_allcategories->execute();
    $rows_allcategories = $query_allcategories->fetch();
    $count_allcategories = $query_allcategories->rowCount();

    $query_allcats = $db->prepare("SELECT rskid, category FROM tbl_projrisk_categories");
    $query_allcats->execute();
    $rows_allcats = $query_allcats->fetch();
    $totalrows_allcats = $query_allcats->rowCount();
} catch (PDOException $ex) {
    $results = flashMessage("An error occurred: " . $ex->getMessage());
}
?>

<!-- start body  -->
<section class="content">
    <div class="container-fluid">
        <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
            <h4 class="contentheader">
                <i class="fa fa-columns" aria-hidden="true"></i>
                <?php echo $pageTitle ?>
                <div class="btn-group" style="float:right">
                    <div class="btn-group" style="float:right">
                        <?php
                        if ($file_rights->add) {
                        ?>
                            <a href="add-risk-mitigation.php" class="btn btn-primary pull-right">Add Risk Mitigation</a>
                        <?php
                        }
                        ?>
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
                        <!-- start body -->
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            <thead>
                                <tr class="bg-orange">
                                    <th style="width:2%"></th>
                                    <th style="width:5%">#</th>
                                    <th style="width:88%">Risk Category Name</th>
                                    <?php
                                    if ($file_rights->edit && $file_rights->delete_permission) {
                                    ?>
                                        <th style="width:5%">Action</th>
                                    <?php
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($totalrows_allcats == 0) {
                                ?>
                                    <tr>
                                        <td colspan="4">
                                            <div style="color:red; font-size:14px"><strong>Sorry No Record Found!!</strong></div>
                                        </td>
                                    </tr>
                                    <?php } else {
                                    $nm = 0;
                                    while ($row_allcats = $query_allcats->fetch()) {
                                        $nm = $nm + 1;
                                        $catid = $row_allcats['rskid'];
                                        $checklistname = $row_allcats['category'];
                                    ?>
                                        <tr class="careted" data-toggle="collapse" data-target=".order<?php echo $nm; ?>" style="background-color:#eff9ca">
                                            <td align="center" class="mb-0">
                                                <button class="btn btn-link" title="Click once to expand and Click twice to Collapse!!">
                                                    <i class="fa fa-plus-square" style="font-size:16px"></i>
                                                </button>
                                            </td>
                                            <td align="center"><?php echo $nm; ?></td>
                                            <td><?php echo $checklistname; ?></td>
                                            <td>

                                            </td>
                                        </tr>
                                        <tr class="collapse order<?php echo $nm; ?>" style="background-color:#8BC34A; color:#FFF">
                                            <th></th>
                                            <th>#</th>
                                            <th>Mitigation Measure</th>
                                            <?php if ($file_rights->edit && $file_rights->delete_permission) {?>
                                            <th>Action</th>
                                          <?php } ?>
                                        </tr>
                                        <?php
                                        $query_riskcat = $db->prepare("SELECT * FROM tbl_projrisk_response WHERE cat = '$catid'");
                                        $query_riskcat->execute();
                                        $totalRows_riskcat = $query_riskcat->rowCount();
                                        if ($totalRows_riskcat == 0) {
                                        ?>
                                            <tr class="collapse order<?php echo $nm; ?>">
                                                <td colspan="4">
                                                    <div style="color:red; font-size:14px"><strong>Sorry No Record Found!!</strong></div>
                                                </td>
                                            </tr>
                                            <?php } else {
                                            $num = 0;
                                            while ($row_riskcat = $query_riskcat->fetch()) {
                                                $num = $num + 1;
                                                $mtid = $row_riskcat['id'];
                                                $mitigation = $row_riskcat['response'];
                                            ?>
                                                <tr data-toggle="collapse" data-target=".topic<?php echo $nm . $num; ?>" class="collapse order<?php echo $nm; ?>" style="background-color:#FFF">
                                                    <td align="center" class="mb-0">
                                                    </td>
                                                    <td align="center"> <?php echo $nm . "." . $num; ?></td>
                                                    <td><?php echo $mitigation; ?></td>
                                                    <?php
                                                    if ($file_rights->edit && $file_rights->delete_permission) {
                                                    ?>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    Options <span class="caret"></span>
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li>
                                                                        <a type="button" href="edit-risk-mitigation.php?mtid=<?= base64_encode($mtid) ?>" id="addFormModalBtn">
                                                                            <i class="fa fa-pencil-square"></i> </i> Edit
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a type="button" onclick="removeItem('<?php echo $mtid ?>', 2)">
                                                                            <i class="fa fa-trash-o"></i> Delete
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            </a>
                                                        </td>
                                                    <?php
                                                    }
                                                    ?>
                                                </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                        <!-- end body -->
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
?>

<script>
    /*******************************
     * ACCORDION WITH TOGGLE ICONS
     *******************************/
    function toggleIcon(e) {
        $(e.target)
            .find(".more-less")
            .toggleClass('fa fa-plus-square fa fa-minus-square');
    }
    $('.mb-0').on('hidden.bs.collapse', toggleIcon);
    $('.mb-0').on('shown.bs.collapse', toggleIcon);


    $(".careted").click(function(e) {
        e.preventDefault();
        $(this)
            .find("i")
            .toggleClass("fa fa-plus-square fa fa-minus-square");
    });

    function removeItem(mtid) {
        var url = "ajax/issuesandrisks/risk-categories";
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: "post",
                    url: url,
                    data: {
                        delete_mitigation: "delete_mitigation",
                        mtid: mtid
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            swal("Success! Mitigation has been deleted!", {
                                icon: "success",
                            });
                            setTimeout(() => {
                                location.reload(true);
                            }, 3000);
                        } else {
                            swal("Error! Could not delete mitigation!", {
                                icon: "error",
                            });
                            setTimeout(() => {
                                location.reload(true);
                            }, 3000);
                        }
                    }
                });
            } else {
                swal("You have cancelled the action!");
            }
        });
    }
</script>
