<?php
require('includes/head.php');

if ($permission) {

    try {
        if ((isset($_GET["del"])) && ($_GET["del"] == "1")) {
            if (isset($_GET['ctid'])) {
                $contrid = $_GET['ctid'];
            }

            $deleteSQL = $db->prepare("UPDATE tbl_contractor SET deleted='1', updated_by=:user, date_updated=:changedon WHERE contrid=:contrid");
            $Result1 = $deleteSQL->execute(array(':contrid' => $contrid, ':user' => $user_name, ':changedon' => $changedon));

            if ($Result1) {
                $msg = 'Partner successfully deactivated.';
                $results = "<script type=\"text/javascript\">
							swal({
								title: \"success!\",
								text: \" $msg\",
								type: 'success',
								timer: 2000,
                                'icon': 'success',
								showConfirmButton: false });
							setTimeout(function(){
								window.location.href = 'view-contractors.php';
							}, 5000);
						</script>";
                echo $results;
            }
        }

        $query_rsContrList = "";
        if ((isset($_GET['srccontrator'])) || (isset($_GET['srcbizna'])) || (isset($_GET['srcpin'])) || (isset($_GET['fscyear']))) {
            $search_rsContractor = $_GET['srccontrator'];
            $search_rsBNo = $_GET['srcbizna'];
            $search_rsPin = $_GET['srcpin'];
            $search_rsFY = $_GET['fscyear'];
            $query_rsContrList = $db->prepare("SELECT *, dateregistered AS dtregistered FROM tbl_contractor WHERE deleted='0' AND (contrid='$search_rsContractor' OR contrid='$search_rsBNo' OR contrid='$search_rsPin') ORDER BY contrid DESC");
        } else {
            $query_rsContrList = $db->prepare("SELECT *, dateregistered AS dtregistered FROM tbl_contractor WHERE deleted='0' ORDER BY contrid DESC");
        }

        $query_rsContrList->execute();
        $totalRows_rsContrList = $query_rsContrList->rowCount();
    } catch (PDOException $ex) {
        $results = flashMessage("An error occurred: " . $ex->getMessage());
    }
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
                                <a href="add-contractor.php" class="btn btn-success" style="height:27px; ; margin-top:-1px; vertical-align:center">Add New Contractor</a>
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
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr id="colrow">
                                            <th style="width:3%"><strong> # </strong></th>
                                            <th style="width:30%"><strong>Contractor Name</strong></th>
                                            <th style="width:12%"><strong>Business Number</strong></th>
                                            <th style="width:12%"><strong>Business Type</strong></th>
                                            <th style="width:14%"><strong>Date Registered</strong></th>
                                            <th style="width:10%"><strong>County</strong></th>
                                            <th style="width:10%"><strong>Pin Status</strong></th>
                                            <th style="width:9%"><strong>Action</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- =========================================== -->
                                        <?php
                                        if ($totalRows_rsContrList > 0) {

                                            $sn = 0;
                                            while ($row_rsContrList = $query_rsContrList->fetch()) {
                                                $sn = $sn + 1;
                                                $bizType = $row_rsContrList['businesstype'];
                                                $pinStatus = $row_rsContrList['pinstatus'];
                                                $contrCounty = $row_rsContrList['county'];

                                                $query_rsBizType = $db->prepare("SELECT * FROM tbl_contractorbusinesstype WHERE id='$bizType'");
                                                $query_rsBizType->execute();
                                                $row_rsBizType = $query_rsBizType->fetch();

                                                $query_rsContrPinStatus = $db->prepare("SELECT * FROM tbl_contractorpinstatus WHERE id='$pinStatus'");
                                                $query_rsContrPinStatus->execute();
                                                $row_rsContrPinStatus = $query_rsContrPinStatus->fetch();

                                                $query_rsContrCounty = $db->prepare("SELECT * FROM counties WHERE id='$contrCounty'");
                                                $query_rsContrCounty->execute();
                                                $row_rsContrCounty = $query_rsContrCounty->fetch();
                                        ?>
                                                <tr style="border-bottom:thin solid #EEE">
                                                    <td><?php echo $sn; ?></td>
                                                    <td>
                                                        <a href="view-contractor-info.php?contrid=<?php echo $row_rsContrList['contrid']; ?>" style="color:#4CAF50" title="More Details"><?php echo $row_rsContrList['contractor_name']; ?></a>
                                                    </td>
                                                    <td><?php echo $row_rsContrList['busregno']; ?></td>
                                                    <td><?php echo $row_rsBizType['type']; ?></td>
                                                    <td><?php echo $row_rsContrList['dtregistered']; ?></td>
                                                    <td><?php echo $row_rsContrCounty['name']; ?></td>
                                                    <td><?php echo $row_rsContrPinStatus['pin_status']; ?></td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                Options <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a title="More Details" href="view-contractor-info.php?contrid=<?php echo $row_rsContrList['contrid']; ?>">
                                                                        <i class="fa fa-file-text"></i> More Info
                                                                    </a>
                                                                </li>
                                                                <?php
                                                                if (in_array("update", $page_actions)) {
                                                                ?>
                                                                    <li> 
                                                                        <a title="Edit Contractor" href="add-contractor.php?edit=1&contrid=<?php echo $row_rsContrList['contrid']; ?>">
                                                                            <i class="fa fa-pencil"></i> Edit
                                                                        </a>
                                                                    </li>
                                                                <?php
                                                                }
                                                                if (in_array("update", $page_actions)) {
                                                                ?>
                                                                    <li>
                                                                        <a title="Delete Contractor" href="view-contractors.php?del=1&amp;ctid=<?php echo $row_rsContrList['contrid']; ?>" onclick="return confirm('Are you sure you want to delete this record?')">
                                                                            <i class="fa fa-trash"></i> Delete
                                                                        </a>
                                                                    </li>
                                                                <?php
                                                                }
                                                                ?>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                        <?php }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
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