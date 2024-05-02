<?php
try {
    require('includes/head.php');
    if ($permission) {
        $totalrows_allcats = 0;
        $query_allcats = $db->prepare("SELECT catid, category FROM tbl_projrisk_categories");
        $query_allcats->execute();
        $totalrows_allcats = $query_allcats->rowCount();
?>

        <!-- start body  -->
        <section class="content">
            <div class="container-fluid">
                <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                    <h4 class="contentheader">
                        <?= $icon ?>
                        <?= $pageTitle ?>
                        <div class="btn-group" style="float:right">
                            <div class="btn-group" style="float:right">
                                <?php
                                if (in_array("create", $page_actions)) {
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
                                            if (in_array("create", $page_actions) || in_array("create", $page_actions)) {
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
                                            <?php
                                        } else {
                                            $nm = 0;
                                            while ($row_allcats = $query_allcats->fetch()) {
                                                $nm++;
                                                $catid = $row_allcats['catid'];
                                                $checklistname = $row_allcats['category'];
                                            ?>
                                                <tr class="careted" data-toggle="collapse" data-target=".order<?php echo $nm; ?>" style="background-color:#eff9ca">
                                                    <td align="center" class="mb-0">
                                                        <button class="btn btn-link" title="Click to expand and collapse!!">
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
                                                    <?php
                                                    if (in_array("update", $page_actions) || in_array("delete", $page_actions)) {
                                                    ?>
                                                        <th>Action</th>
                                                    <?php
                                                    }
                                                    ?>
                                                </tr>
                                                <?php
                                                $query_riskcat = $db->prepare("SELECT * FROM tbl_projrisk_response WHERE cat = :cat");
                                                $query_riskcat->execute(array(":cat" => $catid));
                                                $totalRows_riskcat = $query_riskcat->rowCount();
                                                if ($totalRows_riskcat == 0) {
                                                ?>
                                                    <tr class="collapse order<?php echo $nm; ?>">
                                                        <td colspan="4">
                                                            <div style="color:red; font-size:14px"><strong>Sorry No Record Found!!</strong></div>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                } else {
                                                    $num = 0;
                                                    while ($row_riskcat = $query_riskcat->fetch()) {
                                                        $num++;
                                                        $mtid = $row_riskcat['id'];
                                                        $mitigation = $row_riskcat['response'];
                                                    ?>
                                                        <tr data-toggle="collapse" data-target=".topic<?php echo $nm . $num; ?>" class="collapse order<?php echo $nm; ?>" style="background-color:#FFF">
                                                            <td align="center" class="mb-0">
                                                            </td>
                                                            <td align="center"> <?php echo $nm . "." . $num; ?></td>
                                                            <td><?php echo $mitigation; ?></td>
                                                            <?php
                                                            if (in_array("update", $page_actions)  || in_array("delete", $page_actions)) {
                                                            ?>
                                                                <td>
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Options <span class="caret"></span>
                                                                        </button>
                                                                        <ul class="dropdown-menu">
                                                                            <?php
                                                                            if (in_array("update", $page_actions)) {
                                                                            ?>
                                                                                <li>
                                                                                    <a type="button" href="edit-risk-mitigation.php?mtid=<?= base64_encode($mtid) ?>" id="addFormModalBtn">
                                                                                        <i class="fa fa-pencil-square"></i> </i> Edit
                                                                                    </a>
                                                                                </li>
                                                                            <?php
                                                                            }
                                                                            if (in_array("delete", $page_actions)) {
                                                                            ?>
                                                                                <li>
                                                                                    <a type="button" onclick="removeItem('<?php echo $mtid ?>', 2)">
                                                                                        <i class="fa fa-trash-o"></i> Delete
                                                                                    </a>
                                                                                </li>
                                                                            <?php
                                                                            }
                                                                            ?>
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