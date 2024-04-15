<?php
try {

require('includes/head.php');

if ($permission) {
        $editFormAction = $_SERVER['PHP_SELF'];
        if (isset($_SERVER['QUERY_STRING'])) {
            $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
        }

        if ((isset($_GET["edit"])) && ($_GET["edit"] == "1")) {
            $sctid = "-1";
            if (isset($_GET['sctid'])) {
                $sctid = $_GET['sctid'];
            }

            $query_stEdit = $db->prepare("SELECT * FROM tbl_sectors WHERE stid='$sctid'");
            $query_stEdit->execute();
            $row_rsSTEdit = $query_stEdit->fetch();
            $totalRows_rsSTEdit = $query_stEdit->rowCount();

            $dptparent = $row_rsSTEdit['parent'];
            if ($dptparent == 0) {
                $deptparent = $row_rsSTEdit['stid'];
            } else {
                $deptparent = $row_rsSTEdit['parent'];
            }

            $query_rsDeptParent = $db->prepare("SELECT * FROM tbl_sectors WHERE deleted='0' and stid='$deptparent'");
            $query_rsDeptParent->execute();
            $row_rsDeptParent = $query_rsDeptParent->fetch();
            $totalRows_rsDeptParent = $query_rsDeptParent->rowCount();

            $action = "Edit";
            $submitAction = "MM_update";
            $formName = "editsectorfrm";
            $submitValue = "Update";
            if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "editsectorfrm")) {
                $parent = $_POST['parent'];
                $sector = $_POST['sector'];
                $SQLUpdate = $db->prepare("Update tbl_sectors set parent='$parent',sector='$sector' WHERE stid='$sctid'");
                $result = $SQLUpdate->execute();
                if ($result) {
                    $msg = 'Sector successfully updated.';
                    $results = "<script type=\"text/javascript\">
                        swal({
                        title: \"Success!\",
                        text: \" $msg\",
                        type: 'Success',
                        timer: 5000, 
                        showConfirmButton: false });
                        setTimeout(function(){
                                window.location.href = 'sectors.php';
                            }, 5000);
                    </script>";
                } else {
                    $type = 'error';
                    $msg = 'Error updating the sector, kindly try again!!';
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
        } elseif ((isset($_GET["del"])) && ($_GET["del"] == "1")) {
            $sctid = "-1";
            if (isset($_GET['sctid'])) {
                $sctid = $_GET['sctid'];
            }

            $SQLUpdate = $db->prepare("UPDATE tbl_sectors SET deleted='1' WHERE stid='$sctid'");
            $result = $SQLUpdate->execute();

            if ($result) {
                $msg = 'Sector successfully deleted.';
                $results = "<script type=\"text/javascript\">
                    swal({
                    title: \"Success!\",
                    text: \" $msg\",
                    type: 'Success',
                    timer: 5000, 
                    showConfirmButton: false });
                    setTimeout(function(){
                            window.location.href = 'sectors.php';
                        }, 5000);
                </script>";
            } else {
                $type = 'error';
                $msg = 'Error deleting the sector, kindly try again!!';

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
                $qry1 = $db->prepare("INSERT INTO tbl_sectors (parent,sector) VALUES (:parent, :sector)");
                $result = $qry1->execute(array(":parent" => $_POST['parent'], ":sector" => $_POST['sector']));
                if ($result) {
                    $msg = 'The sector successfully added.';
                    $results = "<script type=\"text/javascript\">
                        swal({
                        title: \"Success!\",
                        text: \" $msg\",
                        type: 'Success',
                        timer: 5000, 
                        showConfirmButton: false });
                        setTimeout(function(){
                                window.location.href = 'sectors.php';
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


        $query_rsSector = $db->prepare("SELECT stid,sector FROM tbl_sectors WHERE deleted='0' and parent='0'");
        $query_rsSector->execute();
        $row_rsSector = $query_rsSector->fetch();
        $totalRows_rsSector = $query_rsSector->rowCount();

        $query_rsAllSectors = $db->prepare("SELECT stid,sector FROM tbl_sectors WHERE deleted='0' and parent='0' ORDER BY sector ASC");
        $query_rsAllSectors->execute();
        $row_rsAllSectors = $query_rsAllSectors->fetch();
    
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

                            <form role="form" id="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                                <fieldset class="scheduler-border row setup-content" id="step-1" style="padding:10px">
                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><?php echo $action; ?> Sector</legend>
                                    <div class="col-md-6">
                                        <label class="control-label">Parent Sector *:</label>
                                        <div class="form-line">
                                            <?php
                                            $parent = "";
                                            if (isset($_GET['sctid'])) {
                                                $sctid = $_GET['sctid'];
                                                $query_sctparent = $db->prepare("SELECT * FROM `tbl_sectors` WHERE stid ='$sctid'");
                                                $query_sctparent->execute();
                                                $row_sctparent = $query_sctparent->fetch();
                                                $parent = $row_sctparent["parent"];
                                            }
                                            ?>
                                            <select name="parent" class="form-control" id="parent" required="required" style="border:#CCC thin solid; border-radius:5px">
                                                <option value="">.... Select Sector from list ....</option>
                                                <option value="0" <?php if (empty($parent)) {
                                                                        echo "selected";
                                                                    } ?>>Parent</option>
                                                <?php
                                                do {
                                                ?>
                                                    <option value="<?php echo $row_rsSector['stid'] ?>" <?php if ($row_rsSector['stid'] == $parent) {
                                                                                                            echo "selected";
                                                                                                        } ?>> <?php echo $row_rsSector['sector'] ?> </option>
                                                <?php
                                                } while ($row_rsSector = $query_rsSector->fetch());
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="projduration">Sector/Department Name (If Department, first Select Parent Sector) *:</label><span id="dept" style="color:darkgoldenrod"></span>
                                        <div class="form-input">
                                            <input type="text" name="sector" class="form-control" id="sector" value="<?php echo (isset($_GET['sctid'])) ? $row_sctparent['sector'] : ""; ?>" style="height:35px; width:98%" placeholder="Enter sector/department" required />
                                            <span id="projdurationmsg1" style="color:red"></span>
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
                            <!-- js-basic-example dataTable -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover ">
                                    <thead>
                                        <tr id="colrow">
                                            <th width="10%" height="35">
                                                <div align="center"><strong id="colhead">SN</strong></div>
                                            </th>
                                            <th width="80%">
                                                <div align="center"><strong id="colhead">Sector Name</strong></div>
                                            </th>
                                            <th align="center" data-orderable="false">Action</th>
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
                                                    <div align="left">&nbsp;&nbsp;<?php echo $row_rsAllSectors['sector']; ?></div>
                                                </td>
                                                <td width="5%">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" onchange="checkBoxes()" aria-haspopup="true" aria-expanded="false">
                                                            Options <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a type="button" id="edit_location" href="sectors.php?edit=1&amp;sctid=<?php echo $row_rsAllSectors['stid']; ?>">
                                                                    <i class="fa fa-pencil"></i> Edit
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a type="button" id="edit_location" href="sectors.php?del=1&amp;sctid=<?php echo $row_rsAllSectors['stid']; ?>" onclick="return confirm('Are you sure you want to delete this record?')">
                                                                    <i class="fa fa-trash"></i> Delete
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                            $sect = $row_rsAllSectors['stid'];

                                            $query_rsAllDepts =  $db->prepare("SELECT * FROM tbl_sectors WHERE tbl_sectors.deleted='0' and tbl_sectors.parent='$sect' ORDER BY stid ASC");
                                            $query_rsAllDepts->execute();
                                            $row_rsAllDepts = $query_rsAllDepts->fetch();

                                            do { ?>
                                                <tr id="rowlines">
                                                    <td width="10%" height="35">
                                                        <div align="center"><b> - </b></div>
                                                    </td>
                                                    <td width="80%">
                                                        <div align="left">&nbsp;&nbsp;<?php echo $row_rsAllDepts['sector']; ?></div>
                                                    </td>
                                                    <td width="5%">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" onchange="checkBoxes()" aria-haspopup="true" aria-expanded="false">
                                                                Options <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a type="button" id="edit_location" href="sectors.php?edit=1&amp;sctid=<?php echo $row_rsAllDepts['stid']; ?>">
                                                                        <i class="fa fa-pencil"></i> Edit
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a type="button" id="edit_location" href="sectors.php?del=1&amp;sctid=<?php echo $row_rsAllDepts['stid']; ?>" onclick="return confirm('Are you sure you want to delete this record?')">
                                                                        <i class="fa fa-trash"></i> Delete
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } while ($row_rsAllDepts = $query_rsAllDepts->fetch()); ?>
                                        <?php } while ($row_rsAllSectors = $query_rsAllSectors->fetch()); ?>

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

} catch (PDOException $th) {
    customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine()); 

}
?>