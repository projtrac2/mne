<?php
require('includes/head.php');

if ($permission) {
    try {
        $editFormAction = $_SERVER['PHP_SELF'];
        if (isset($_SERVER['QUERY_STRING'])) {
            $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
        }

        if ((isset($_GET["del"])) && ($_GET["del"] == "1")) {
            $stid = "-1";
            if (isset($_GET['stid'])) {
                $stid = $_GET['stid'];
            }

            $deleteSQL = $db->prepare("DELETE FROM tbl_state WHERE id='$stid'");
            $result = $deleteSQL->execute();

            if ($result) {
                $msg = 'Location successfully deleted.';
                $results = "<script type=\"text/javascript\">
                    swal({
                    title: \"Success!\",
                    text: \" $msg\",
                    type: 'Success',
                    timer: 5000,
                    showConfirmButton: false });
                    setTimeout(function(){
                            window.location.href = 'locations.php';
                        }, 5000);
                </script>";
            } else {
                $type = 'error';
                $msg = 'Error deleting the location, kindly try again!!';

                $results = "<script type=\"text/javascript\">
                    swal({
                    title: \"Error!\",
                    text: \" $msg \",
                    type: 'Danger',
                    timer: 5000,
                    showConfirmButton: false });
                </script>";
            }
        } else {
            $action = "Add";
            $submitAction = "MM_insert";
            $formName = "addsectorfrm";
            $submitValue = "Submit";

            if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addsectorfrm")) {

                $parent = $_POST['wards'];

                if (!empty($parent) || $parent !== '') {
                    $query_rsLocID = $db->prepare("SELECT * FROM tbl_state WHERE id='$parent'");
                    $query_rsLocID->execute();
                    $row_rsLocID = $query_rsLocID->fetch();

                    if (empty($row_rsLocID["parent"]) || $row_rsLocID["parent"] == '') {
                        $islocation = 0;
                    } else {
                        $islocation = 1;
                    }
                } else {
                    $islocation = 0;
                }

                if (!empty($parent)) {
                    $insertSQL = $db->prepare("INSERT INTO tbl_state (parent,state,location) VALUES (:parent, :state, :location)");
                    $result = $insertSQL->execute(array(":parent" => $_POST['wards'], ":state" => $_POST['location'], ":location" => $islocation));
                } else {
                    $insertSQL = $db->prepare("INSERT INTO tbl_state (state,location) VALUES (:state, :location)");
                    $result = $insertSQL->execute(array(":state" => $_POST['location'], ":location" => $islocation));
                }

                if ($result) {
                    $msg = 'The location successfully added.';
                    $results = "<script type=\"text/javascript\">
                        swal({
                        title: \"Success!\",
                        text: \" $msg\",
                        type: 'Success',
                        timer: 5000,
                        showConfirmButton: false });
                        setTimeout(function(){
                                window.location.href = 'locations.php';
                            }, 5000);
                    </script>";
                } else {
                    $type = 'error';
                    $msg = 'Error saving the sector, kindly try again!!';

                    $results = "<script type=\"text/javascript\">
                        swal({
                        title: \"Error!\",
                        text: \" $msg \",
                        type: 'Danger',
                        timer: 5000,
                        showConfirmButton: false });
                    </script>";
                }
            }
        }

        $query_rsAllLocations = $db->prepare("SELECT id,state FROM tbl_state WHERE parent IS NULL and id<>'1' ORDER BY state ASC");
        $query_rsAllLocations->execute();
        $row_rsAllLocations = $query_rsAllLocations->fetch();

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
                                <!-- ============================================================== -->
                                <!-- Start Page Content -->
                                <!-- ============================================================== -->
                                <!-- js-basic-example dataTable -->
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover ">
                                        <thead>
                                            <tr id="colrow">
                                                <th width="10%" height="35">
                                                    <div align="center"><strong id="colhead">SN</strong></div>
                                                </th>
                                                <th width="85%">
                                                    <div align="center"><strong id="colhead">Level-1/Level-2</strong></div>
                                                </th>
                                                <th width="5%" align="center" data-orderable="false">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sn = 0;
                                            $query_rsState = $db->prepare("SELECT id,state FROM tbl_state WHERE parent IS NULL");
                                            $query_rsState->execute();
                                            $rows_rsState = $query_rsState->rowCount();
                                            while ($row_rsState = $query_rsState->fetch()) {
                                                $sn++;
                                            ?>
                                                <tr id="rowlines" style="background-color:#e8eef7">
                                                    <td width="10%" height="35">
                                                        <div align="center"><?php echo $sn; ?></div>
                                                    </td>
                                                    <td width="80%">
                                                        <div align="left">&nbsp;&nbsp;<?php echo $row_rsState['state']; ?></div>
                                                    </td>
                                                    <td width="5%">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" onchange="checkBoxes()" aria-haspopup="true" aria-expanded="false">
                                                                Options <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a type="button" id="edit_location" href="edit-locations.phpedit=1&amp;stid=<?php echo $row_rsState['id']; ?>">
                                                                        <i class="fa fa-pencil"></i> Edit
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a type="button" id="edit_location" href="locations?del=1&amp;stid=<?php echo $row_rsState['id']; ?>" onclick="return confirm('Are you sure you want to delete this record?')">
                                                                        <i class="fa fa-trash"></i> Delete
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                                $ward = $row_rsState['id'];
                                                $query_rsAllWards = $db->prepare("SELECT * FROM `tbl_state` WHERE parent='$ward' ORDER BY id ASC");
                                                $query_rsAllWards->execute();
                                                $rows_rsAllWards = $query_rsAllWards->rowCount();
                                                if ($rows_rsAllWards > 0) {
                                                    while ($row_rsAllWards = $query_rsAllWards->fetch()) {
                                                ?>
                                                        <tr id="rowlines" style="background-color:#f9fbfc; border-bottom:#000 thin dashed">
                                                            <td width="10%" height="35">
                                                                <div align="center"><b> . </b></div>
                                                            </td>
                                                            <td width="80%">
                                                                <div align="left">&nbsp;&nbsp;-- <?php echo $row_rsAllWards['state']; ?></div>
                                                            </td>
                                                            <td width="5%">
                                                                <div class="btn-group">
                                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" onchange="checkBoxes()" aria-haspopup="true" aria-expanded="false">
                                                                        Options <span class="caret"></span>
                                                                    </button>
                                                                    <ul class="dropdown-menu">
                                                                        <li>
                                                                            <a type="button" id="edit_location" href="edit-locations.php?edit=1&amp;stid=<?php echo $row_rsAllWards['id']; ?>">
                                                                                <i class="fa fa-pencil"></i> Edit
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a type="button" data-toggle="modal" id="approveItemModalBtn" data-target="#myModal" onclick="add_state(<?= $projid ?>)">
                                                                                <i class="fa fa-check-square-o"></i> Edit
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a type="button" id="edit_location" href="locations?del=1&amp;stid=<?php echo $row_rsAllWards['id']; ?>" onclick="return confirm('Are you sure you want to delete this record?')">
                                                                                <i class="fa fa-trash"></i> Delete
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            <?php
                                            }
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
        </section>
        <!-- end body  -->
        <!-- Modal Request Payment -->
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="background-color:#03A9F4">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h3 class="modal-title" align="center">
                            <font color="#FFF"><span id="locationName"></span> Add Locations</font>
                        </h3>
                    </div>
                    <form class="tagForm" action="" method="post" id="state_form" enctype="multipart/form-data">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="modal-body">
                                    <label for="base_val" id=""> <span id="label_name"></span> *:</label>
                                    <div class="form-input">
                                        <input type="number" name="state_id" id="state_id" value="" placeholder="Enter Value" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    <input type="hidden" name="add_state" id="add_state" class="form-control" value="new">
                                    <input type="hidden" name="parent_id" id="parent_id" class="form-control" value="0">
                                    <input name="submit" type="submit" class="btn btn-success waves-effect waves-light" id="tag-form-base-submit" value="Save" />
                                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- #END# Modal Request Payment -->
<?php
    } catch (PDOException $ex) {
        $result = "An error occurred: " . $ex->getMessage();
        print($result);
    }
} else {
    $results =  restriction();
    echo $results;
}
require('includes/footer.php');
?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#subcounty').on('change', function() {
            var scID = $(this).val();
            if (scID) {
                $.ajax({
                    type: 'POST',
                    url: 'addLocations',
                    data: 'sc_id=' + scID,
                    success: function(html) {
                        $('#wards').html(html);
                    }
                });
            } else {
                $('#wards').html('<option value="">Select Level-1 first</option>');
            }
        });

        $('#wards').on('change', function() {
            var wdID = $(this).val();
            var subCID = $("#subcounty").val();
            if (wdID) {
                $.ajax({
                    type: 'POST',
                    url: 'addWards',
                    data: 'wd_id=' + wdID + '&subc_ID=' + subCID,
                    success: function(html) {
                        $('.locations').html(html);
                    }
                });
            }
        });
    });
</script>