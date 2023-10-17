<?php

require('includes/head.php');
if ($permission) {
    try {
        $currentPage = $_SERVER["PHP_SELF"];

        if (isset($_GET['contrid'])) {
            $contrid_rsInfo = $_GET['contrid'];
            $decode_projid = (isset($_GET['contrid']) && !empty($_GET["contrid"])) ? base64_decode($_GET['contrid']) : "";
            $projid_array = explode("projid54321", $decode_projid);
            $contrid_rsInfo = $projid_array[1]; 

            $query_rsContrInfo = $db->prepare("SELECT *, dateregistered AS dtregistered FROM tbl_contractor WHERE contrid = '$contrid_rsInfo'");
            $query_rsContrInfo->execute();
            $row_rsContrInfo = $query_rsContrInfo->fetch();

            $BusinessType = $row_rsContrInfo["businesstype"];
            $pinStatus = $row_rsContrInfo["pinstatus"];
            $ContrVat = $row_rsContrInfo["vatregistered"];
            $ContrCounty = $row_rsContrInfo["county"];

            $query_rsBzType = $db->prepare("SELECT * FROM tbl_contractorbusinesstype WHERE id='$BusinessType'");
            $query_rsBzType->execute();
            $row_rsBzType = $query_rsBzType->fetch();

            $query_rsContrPinStatus = $db->prepare("SELECT * FROM tbl_contractorpinstatus WHERE id='$pinStatus'");
            $query_rsContrPinStatus->execute();
            $row_rsContrPinStatus = $query_rsContrPinStatus->fetch();

            $query_rsContrVat = $db->prepare("SELECT * FROM tbl_contractorvat WHERE id='$ContrVat'");
            $query_rsContrVat->execute();
            $row_rsContrVat = $query_rsContrVat->fetch();

            $query_rsContrCounty = $db->prepare("SELECT * FROM counties WHERE id='$ContrCounty'");
            $query_rsContrCounty->execute(); 
            $row_rsContrCounty = $query_rsContrCounty->fetch();

            $query_rsContrDir = $db->prepare("SELECT * FROM tbl_contractordirectors WHERE contrid = '$contrid_rsInfo' Order BY id ASC");
            $query_rsContrDir->execute();
            $totalRows_rsContrDir = $query_rsContrDir->rowCount();

            $query_rsContrDocs = $db->prepare("SELECT * FROM tbl_contractordocuments WHERE contrid = '$contrid_rsInfo' Order BY id ASC");
            $query_rsContrDocs->execute();
            $row_rsContrDocs = $query_rsContrDocs->fetch();
            $totalRows_rsContrDocs = $query_rsContrDocs->rowCount();


            $query_rsContrProj = $db->prepare("SELECT p.*, g.projsector as sector FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid WHERE p.deleted='0' AND projcontractor = '$contrid_rsInfo' Order BY projid ASC");
            $query_rsContrProj->execute();
            $totalRows_rsContrProj = $query_rsContrProj->rowCount();

            $query_rsPFiles = $db->prepare("SELECT *, date_created AS ufdate, @curRow := @curRow + 1 AS sn FROM tbl_contractordocuments WHERE contrid = '$contrid_rsInfo' Order BY id ASC");
            $query_rsPFiles->execute();
            $row_rsPFiles = $query_rsPFiles->fetch();
            $totalRows_rsPFiles = $query_rsPFiles->rowCount();
        }
    } catch (PDOException $ex) {
        $results = flashMessage("An error occurred: " . $ex->getMessage());
    }
	$icon = '<i class="fa fa-list"></i>';
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
                            <button onclick="history.back()" type="button" class="btn bg-orange waves-effect" style="float:right; margin-top:-5px">Go Back </button>
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
                                <table class="table table-striped table-bordered table-hover" width="98%">
                                    <thead>
                                        <tr id="colrow">
                                            <th style="width:30%">Title</th>
                                            <th style="width:70%">Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Contractor Name</td>
                                            <td><?php echo $row_rsContrInfo["contractor_name"]; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Business Type</td>
                                            <td><?php echo $row_rsBzType["type"]; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Phone Number</td>
                                            <td><?php echo $row_rsContrInfo["phone"]; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Email Address</td>
                                            <td><?php echo $row_rsContrInfo["email"]; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Postal Address</td>
                                            <td><?php echo $row_rsContrInfo["contact"]; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Physical Address</td>
                                            <td><?php echo $row_rsContrInfo["address"]; ?></td>
                                        </tr>
                                        <tr>
                                            <td>City/Town</td>
                                            <td><?php echo $row_rsContrInfo["city"]; ?></td>
                                        </tr>
                                        <tr>
                                            <td>County</td>
                                            <td><?php echo $row_rsContrCounty["name"]; ?></td>
                                        </tr>
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
<script>
    $(document).ready(function() {
        $('.contractor_info').DataTable();
    });
</script>