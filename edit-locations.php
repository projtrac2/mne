<?php
require('includes/head.php');
if ($permission) {

    try {
        $editFormAction = $_SERVER['PHP_SELF'];
        if (isset($_SERVER['QUERY_STRING'])) {
            $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
        }

        if ((isset($_GET["edit"])) && ($_GET["edit"] == "1")) {
            if (isset($_GET['stid'])) {
                $stid = $_GET['stid'];
            }

            $query_stEdit = $db->prepare("SELECT * FROM tbl_state WHERE id='$stid'");
            $query_stEdit->execute();
            $row_rsSTEdit = $query_stEdit->fetch();
            $totalRows_rsSTEdit = $query_stEdit->rowCount();

            $locname = $row_rsSTEdit['state'];

            $action = "Edit";
            $submitAction = "MM_update";
            $formName = "editlocfrm";
            $submitValue = "Update";
            if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "editlocfrm")) {

                $state = $_POST['location'];
                $username = $_POST['user_name'];
                $changedon = date("Y-m-d H:i:s");

                $SQLUpdate = $db->prepare("Update tbl_state set state='$state',changed_by='$username',date_changed='$changedon' WHERE id='$stid'");
                $updresult = $SQLUpdate->execute();
                var_dump($updresult);

                if ($updresult) {
                    $msg = 'Location successfully updated.';
                    $results =
                        "<script type=\"text/javascript\">
                        swal({
                        title: \"Success!\",
                        text: \" $msg\",
                        type: 'Success',
                        timer: 5000,
                        icon:'success',
                        showConfirmButton: false
                    });
                        setTimeout(function(){
                                window.location.href = 'locations.php';
                            }, 5000);
                    </script>";
                    echo $results;
                } else {
                    $type = 'error';
                    $msg = 'Error updating the location, kindly try again!!';

                    $results =
                        "<script type=\"text/javascript\">
                        swal({
                            title: \"Error!\",
                            text: \" $msg \",
                            type: 'Danger',
                            timer: 5000,
                            icon:'error',
                            showConfirmButton: false
                        });
                        setTimeout(function(){
                            window.location.href = 'locations.php';
                        }, 5000);
                    </script>";
                    echo $results;
                }
            }
        }




        $query_rsState = $db->prepare("SELECT id,state FROM tbl_state WHERE location='0' and parent IS NULL");
        $query_rsState->execute();
        $rsState = $query_rsState->fetch();
        $row_rsState = $query_rsState->rowCount();

        $query_rsAllLocations = $db->prepare("SELECT * FROM tbl_state WHERE location='0' and parent IS NULL and id<>'1' ORDER BY state ASC");
        $query_rsAllLocations->execute();
        $row_rsAllLocations = $query_rsAllLocations->fetch();
    } catch (PDOException $ex) {
        $result = "An error occurred: " . $ex->getMessage();
        print($result);
    }

?>
    <link rel="stylesheet" href="css/addprojects.css">
    <style>
        @media (min-width: 1200px) {
            .modal-lg {
                width: 90%;
                height: 100%;
            }
        }
    </style>
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
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="card">
                                        <div class="body" style="margin-top:5px">
                                            <form role="form" id="<?= $formName ?>" name="<?= $formName ?>" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                                                <?= csrf_token_html(); ?>
                                                <fieldset class="scheduler-border row setup-content" id="step-1" style="padding:10px">
                                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><?php echo $action; ?> Location (If adding Level-2/Level-3, first Select Parent Location .i.e Level-1/Level-2)</legend>
                                                    <div class="col-md-3">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="projduration">Location Name *:</label><span id="dept" style="color:darkgoldenrod"></span>
                                                        <div class="form-line">
                                                            <div class="locations">
                                                                <input type="text" name="location" class="form-control" id="location" value="<?php echo htmlentities($locname); ?>" required="required" style="height:35px; width:98%" />
                                                            </div>
                                                            <div class="myward2"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                    </div>
                                                    <div class="col-md-12">
                                                        <ul class="list-inline" align="center">
                                                            <li>
                                                                <input name="user_name" type="hidden" id="user_name" value="<?php echo $user_name; ?>" />
                                                                <input name="<?= $submitValue ?>" type="submit" class="btn btn-success" id="submit" value="<?= $submitValue ?>" style="margin-bottom:10px" />
                                                                <input type="hidden" name="<?= $submitAction ?>" value="<?= $formName ?>" />
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </fieldset>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="card">
                                        <div class="body" style="margin-top:5px">
                                            <fieldset class="scheduler-border row setup-content" id="step-1" style="padding:10px">
                                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Locations List</legend>
                                                <div class="row clearfix">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="col-md-12 table-responsive">
                                                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                                <thead>
                                                                    <tr id="colrow">
                                                                        <th width="10%" height="35">
                                                                            <div align="center"><strong id="colhead">SN</strong></div>
                                                                        </th>
                                                                        <th width="80%">
                                                                            <div align="center"><strong id="colhead">Level-1/Level-2/Level-3 Name</strong></div>
                                                                        </th>
                                                                        <th colspan="2" align="center">Action</th>
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
                                                                            <td>
                                                                                <div class="btn-group">
                                                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" onchange="checkBoxes()" aria-haspopup="true" aria-expanded="false">
                                                                                        Options <span class="caret"></span>
                                                                                    </button>
                                                                                    <ul class="dropdown-menu">
                                                                                        <li>
                                                                                            <a type="button" id="edit_location" href="edit-locations.php.php.php.php.php.php.php?edit=1&amp;stid=<?php echo $row_rsAllLocations['id']; ?>">
                                                                                                <i class="fa fa-pencil"></i> Edit
                                                                                            </a>
                                                                                        </li>
                                                                                        <li>
                                                                                            <a type="button" id="edit_location" href="locations?del=1&amp;stid=<?php echo $row_rsAllLocations['id']; ?>" onclick="return confirm('Are you sure you want to delete this record?')">
                                                                                                <i class="fa fa-trash"></i> Delete
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
                                                                                <td>
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
                                                                                                <a type="button" id="edit_location" href="locations?del=1&amp;stid=<?php echo $row_rsAllWards['id']; ?>" onclick="return confirm('Are you sure you want to delete this record?')">
                                                                                                    <i class="fa fa-trash"></i> Delete
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
                                                                                    <td width="80%">
                                                                                        <div align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;---- <?php echo $projloc; ?></div>
                                                                                    </td>
                                                                                    <td width="5%">
                                                                                        <div class="btn-group">
                                                                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" onchange="checkBoxes()" aria-haspopup="true" aria-expanded="false">
                                                                                                Options <span class="caret"></span>
                                                                                            </button>
                                                                                            <ul class="dropdown-menu">
                                                                                                <li>
                                                                                                    <a type="button" id="edit_location" href="edit-locations.php?edit=1&amp;stid=<?php echo $row_rsAllLocs['id']; ?>">
                                                                                                        <i class="fa fa-pencil"></i> Edit
                                                                                                    </a>
                                                                                                </li>
                                                                                                <li>
                                                                                                    <a type="button" id="edit_location" href="locations?del=1&amp;stid=<?php echo $row_rsAllLocs['id']; ?>" onclick="return confirm('Are you sure you want to delete this record?')">
                                                                                                        <i class="fa fa-trash"></i> Delete
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
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
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
?>