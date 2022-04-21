<?php
$replacement_array = array(
    'planlabel' => "CIDP",
    'plan_id' => base64_encode(6),
);

$page = "view";
include 'includes/head.php';
if ($permission) {
    $pageTitle = "Financiers";
    try {
        $query_rsfinancier = $db->prepare("SELECT *, f.id as fnid, t.type as ftype, t.id as fid FROM tbl_financiers f inner join tbl_funding_type t ON t.id=f.type ORDER BY f.id ASC");
        $query_rsfinancier->execute();
        $row_rsfinancier = $query_rsfinancier->fetch();
        $totalRows_rsfinancier = $query_rsfinancier->rowCount();
    } catch (PDOException $ex) {
        $results = flashMessage("An error occurred: " . $ex->getMessage());
    }
?>
    <!-- start body  -->
    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                        <h4 class="contentheader">
                            <i class="fa fa-columns" aria-hidden="true"></i>
                            <?php echo $pageTitle ?>
                            <div class="btn-group" style="float:right">
                                <div class="btn-group" style="float:right">
                                    <?php
                                    if ($file_rights->add) {
                                    ?>
                                        <a href="add-financier.php" class="btn btn-primary pull-right">Add Financier</a>
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
                    <div class="col-md-12">
                        <div class="header" style="padding-bottom:0px">
                            <div class="button-demo" style="margin-top:-15px">
                                <span class="label bg-black" style="font-size:18px">
                                    <img src="assets/images/proj-icon.png" alt="Project" title="Project" style="vertical-align:middle; height:25px" /> Menu
                                </span>
                                <a href="view-funding.php" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:4px"><i class="fa fa-money"></i> &nbsp; &nbsp; Funding</a>
                                <a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px"><i class="fa fa-university"></i> &nbsp; &nbsp; Financiers</a>
                            </div>
                        </div>
                    </div>
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
                                            <th width="3%"><strong>SN</strong></th>
                                            <th width="27%"><strong>Financier</strong></th>
                                            <th width="8%"><strong>Type</strong></th>
                                            <th width="15%"><strong>Contact</strong></th>
                                            <th width="9%"><strong>Phone</strong></th>
                                            <th width="8%"><strong>Projects</strong></th>
                                            <th width="15%"><strong>Total Amt (Ksh)</strong></th>
                                            <th width="5%"><strong>Status</strong></th>
                                            <th width="10%"><strong>Action</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- =========================================== -->
                                        <?php
                                        if ($totalRows_rsfinancier > 0) {
                                            $sn = 0;
                                            do {
                                                $sn = $sn + 1;
                                                $country = $row_rsfinancier['country'];
                                                $fnid = $row_rsfinancier['fnid'];
                                                $sourcecat = $row_rsfinancier['fid'];
                                                $hashfnid = base64_encode("fn918273AxZID{$fnid}");

                                                $query_financierprojs = $db->prepare("SELECT p.projid FROM tbl_projects p inner join tbl_myprojfunding m on p.projid=m.projid WHERE p.deleted='0' and m.sourcecategory=:sourcecat and m.financier = :fnid GROUP BY p.projid ORDER BY m.id ASC");
                                                $query_financierprojs->execute(array(":sourcecat" => $sourcecat, ":fnid" => $fnid));
                                                $row_financierprojs = $query_financierprojs->rowCount();

                                                $query_totalfunds = $db->prepare("SELECT * FROM tbl_funds WHERE funder = :fnid");
                                                $query_totalfunds->execute(array(":fnid" => $fnid));
                                                $tdn = 0;
                                                while ($ttamt = $query_totalfunds->fetch()) {
                                                    $amnt = $ttamt["amount"] * $ttamt["exchange_rate"];
                                                    $tdn = $tdn + $amnt;
                                                }

                                                if ($row_rsfinancier['active'] == 1) {
                                                    $active = '<i class="fa fa-check-square" style="font-size:18px;color:green" title="Active"></i>';
                                                } else {
                                                    $active = '<i class="fa fa-exclamation-triangle" style="font-size:18px;color:#bc2d10" title="Disabled"></i>';
                                                }
                                        ?>
                                                <tr style="border-bottom:thin solid #EEE">
                                                    <td><?php echo $sn; ?></td>
                                                    <td><?php echo $row_rsfinancier['financier']; ?></td>
                                                    <td><?php echo $row_rsfinancier['ftype']; ?></td>
                                                    <td><?php echo $row_rsfinancier['contact']; ?> (<?php echo $row_rsfinancier['designation']; ?>)</td>
                                                    <td><a href="tel:<?php echo $row_rsfinancier['phone']; ?>"><?php echo $row_rsfinancier['phone']; ?></a></td>
                                                    <td align="center">
                                                        <span class="badge bg-brown">
                                                            <a href="view-financier-projects.php?fndid=<?php echo base64_encode($row_rsfinancier['id']); ?>" style="font-family:Verdana, Geneva, sans-serif; color:white; font-size:12px; padding-top:0px">
                                                                <?php echo $row_financierprojs; ?>
                                                            </a>
                                                    </td>
                                                    <td><?php echo number_format($tdn, 2); ?></td>
                                                    <td align="center"><?= $active ?></td>

                                                    <td>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" onchange="checkBoxes()" aria-haspopup="true" aria-expanded="false">
                                                                Options <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a type="button" href="view-financier-info.php?fn=<?php echo $hashfnid; ?>"><i class="fa fa-plus-square"></i> Manage</a>
                                                                </li>
                                                                <?php
                                                                if ($file_rights->edit && $file_rights->delete_permission) {
                                                                ?>
                                                                    <li>
                                                                        <a type="button" href="add-development-funds.php?fn=<?php echo $hashfnid; ?>">
                                                                            <i class="fa fa-money"></i> Add Funds </a>
                                                                    </li>
                                                                    <li>
                                                                        <a type="button" href="edit-financier.php?fn=<?php echo $hashfnid; ?>">
                                                                            <i class="glyphicon glyphicon-edit"></i> Edit </a>
                                                                    </li>
                                                                <?php
                                                                }
                                                                ?>
                                                            </ul>
                                                        </div>
                                                    </td>

                                                </tr>
                                        <?php
                                            } while ($row_rsfinancier = $query_rsfinancier->fetch());
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
