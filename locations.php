<?php
$replacement_array = array(
    'planlabel' => "CIDP",
    'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');

if ($permission) {
    $pageTitle = "Project Locations";
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

        $query_rsState = $db->prepare("SELECT id,state FROM tbl_state WHERE location='0' and parent IS NULL");
        $query_rsState->execute();
        $row_rsState = $query_rsState->fetch();

        $query_rsAllLocations = $db->prepare("SELECT id,state FROM tbl_state WHERE location='0' and parent IS NULL and id<>'1' ORDER BY state ASC");
        $query_rsAllLocations->execute();
        $row_rsAllLocations = $query_rsAllLocations->fetch();
    } catch (PDOException $ex) {
        $result = "An error occurred: " . $ex->getMessage();
        print($result);
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
                            if ($action_permission) {
                            ?>

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
                            <!-- ============================================================== -->
                            <!-- Start Page Content -->
                            <!-- ============================================================== -->

                            <form role="form" id="<?= $formName ?>" name="<?= $formName ?>" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                                <fieldset class="scheduler-border row setup-content" id="step-1" style="padding:10px">
                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><?php echo $action; ?> Location (If adding Level-2/Level-3, first Select Parent Location .i.e Level-1/Level-2)</legend>
                                    <div class="col-md-4">
                                        <label class="control-label">Level-1 *:</label>
                                        <div class="form-line">
                                            <select name="subcounty" class="form-control" id="subcounty" required="required" style="border:#CCC thin solid; border-radius:5px">
                                                <option value="">.... Select Level-1 ....</option>
                                                <?php
                                                do {
                                                    if ($row_rsState['id'] == '0') {
                                                        $SCounty = "Level-1";
                                                    } else {
                                                        $SCounty = $row_rsState['state'];
                                                    }
                                                ?>
                                                    <option value="<?php echo $row_rsState['id'] ?>"><?php echo $row_rsState['state']; ?></option>
                                                <?php
                                                } while ($row_rsState = $query_rsState->fetch());
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="projduration">Level-2 *:</label><span id="dept" style="color:darkgoldenrod"></span>
                                        <div class="form-line">
                                            <div class="myward">
                                                <select name="wards" class="form-control" id="wards" required="required" style="border:#CCC thin solid; border-radius:5px">
                                                    <option value="">.... Select Level-1 first ....</option>
                                                </select>
                                            </div>
                                            <div class="myward2"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <span id="error"></span>
                                        <label for="projduration">Level-3 *:</label><span id="error" style="color:darkgoldenrod"></span>
                                        <div class="form-line">
                                            <div class="locations">
                                                <input type="text" name="location" class="form-control" id="location" required="required" style="height:35px; width:98%" />
                                            </div>
                                            <div class="myward2"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <ul class="list-inline" align="center">
                                            <li>
                                                <input name="<?= $submitValue ?>" type="submit" class="btn btn-success" id="submit" value="<?= $submitValue ?>" style="margin-bottom:10px" />
                                                <input type="hidden" name="<?= $submitAction ?>" value="<?= $formName ?>" />
                                            </li>
                                        </ul>
                                    </div>
                                </fieldset>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr id="colrow">
                                            <th width="10%" height="35">
                                                <div align="center"><strong id="colhead">SN</strong></div>
                                            </th>
                                            <th width="85%">
                                                <div align="center"><strong id="colhead">Level-1/Level-2/Level-3 Name</strong></div>
                                            </th>
                                            <th width="5%" align="center" data-orderable="false">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sn = 0;
                                        do {
                                            $sn++;
                                        ?>
                                            <tr id="rowlines" style="background-color:#e8eef7">
                                                <td width="10%" height="35">
                                                    <div align="center"><?php echo $sn; ?></div>
                                                </td>
                                                <td width="80%">
                                                    <div align="left">&nbsp;&nbsp;<?php echo $row_rsAllLocations['state']; ?></div>
                                                </td> 
                                                <td width="5%">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" onchange="checkBoxes()" aria-haspopup="true" aria-expanded="false">
                                                                Options <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a type="button" id="edit_location" href="editlocations?edit=1&amp;stid=<?php echo $row_rsAllLocations['id']; ?>">
                                                                        <i class="fa fa-plus-square"></i> Edit
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a type="button" id="edit_location" href="locations?del=1&amp;stid=<?php echo $row_rsAllLocations['id']; ?>" onclick="return confirm('Are you sure you want to delete this record?')">
                                                                        <i class="fa fa-plus-square"></i> Delete
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                            </tr>
                                            <?php
                                            $ward = $row_rsAllLocations['id'];

                                            $query_rsAllWards = $db->prepare("SELECT * FROM `tbl_state` WHERE location='0' and parent='$ward' ORDER BY id ASC");
                                            $query_rsAllWards->execute();
                                            $row_rsAllWards = $query_rsAllWards->fetch();
                                            do { ?>
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
                                                                    <a type="button" id="edit_location" href="editlocations?edit=1&amp;stid=<?php echo $row_rsAllWards['id']; ?>">
                                                                        <i class="fa fa-plus-square"></i> Edit
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a type="button" id="edit_location" href="locations?del=1&amp;stid=<?php echo $row_rsAllWards['id']; ?>" onclick="return confirm('Are you sure you want to delete this record?')">
                                                                        <i class="fa fa-plus-square"></i> Delete
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                                $loc = $row_rsAllWards['id'];

                                                $query_rsAllLocs = $db->prepare("SELECT * FROM `tbl_state` WHERE location='1' and parent='$loc' ORDER BY id ASC");
                                                $query_rsAllLocs->execute();
                                                $row_rsAllLocs = $query_rsAllLocs->fetch();
                                                do {
                                                    if (empty($row_rsAllLocs['state']) || $row_rsAllLocs['state'] == '') {
                                                        $projloc = "Location Not Defined";
                                                    } else {
                                                        $projloc = $row_rsAllLocs['state'];
                                                    }
                                                ?>

                                                    <tr id="rowlines">
                                                        <td width="10%" height="35">
                                                            <div align="center"><b> . </b></div>
                                                        </td>
                                                        <td width="85%">
                                                            <div align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;---- <?php echo $projloc; ?></div>
                                                        </td>
                                                        <td width="5%">
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" onchange="checkBoxes()" aria-haspopup="true" aria-expanded="false">
                                                                    Options <span class="caret"></span>
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li>
                                                                        <a type="button" id="edit_location" href="editlocations?edit=1&amp;stid=<?php echo $row_rsAllLocs['id']; ?>">
                                                                            <i class="fa fa-plus-square"></i> Edit
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a type="button" id="edit_location" href="locations?del=1&amp;stid=<?php echo $row_rsAllLocs['id']; ?>" onclick="return confirm('Are you sure you want to delete this record?')">
                                                                            <i class="fa fa-plus-square"></i> Delete
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php } while ($row_rsAllLocs = $query_rsAllLocs->fetch()); ?>
                                            <?php } while ($row_rsAllWards = $query_rsAllWards->fetch()); ?>
                                        <?php } while ($row_rsAllLocations = $query_rsAllLocations->fetch()); ?>
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
<?php
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