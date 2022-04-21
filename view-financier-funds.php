<?php
$pageName = "Strategic Plans";
$replacement_array = array(
    'planlabel' => "CIDP",
    'plan_id' => base64_encode(6),
);

$page = "view";

require('includes/head.php');
if ($permission) {
$pageTitle = "Manage Financier";
try {
    if (isset($_GET['fn'])) {
        $hash = $_GET['fn'];
        $decode_fndid = base64_decode($hash);
        $fndid_array = explode("fn918273AxZID", $decode_fndid);
        $fn = $fndid_array[1];
    }

    $query_financier = $db->prepare("SELECT * FROM tbl_financiers WHERE id=:fn");
    $query_financier->execute(array(":fn" => $fn));
    $row_financier = $query_financier->fetch();

    $query_rsDonorGrant = $db->prepare("SELECT f.id AS fid, fund_code, year, amount, exchange_rate, date_funds_released, c.currency AS curr, c.code AS currcode FROM tbl_funds f inner join tbl_currency c ON f.currency=c.id INNER JOIN tbl_fiscal_year y ON y.id=f.financial_year WHERE funder=:fn ORDER BY f.id ASC");
    $query_rsDonorGrant->execute(array(":fn" => $fn));
    $row_rsDonorGrant = $query_rsDonorGrant->fetch();
    $totalRows_rsDonorGrant = $query_rsDonorGrant->rowCount();
} catch (PDOException $ex) {
    $result = flashMessage("An error occurred: " . $ex->getMessage());
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

                    </div>
                </div>
            </h4>
        </div>
        <div class="row clearfix">
            <div class="block-header">
                <?= $results; ?>
                <div class="col-md-12">
                    <div class="header" style="padding-bottom:0px">
                        <div class="button-demo" style="margin-top:-15px">
                            <span class="label bg-black" style="font-size:18px"><img src="assets/images/proj-icon.png" alt="Project Menu" title="Project Menu" style="vertical-align:middle; height:25px" />Financier Menu</span>
                            <a href="view-financier-info.php?fn=<?php echo $hash; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; padding-left:-5px">Financier Details</a>
                            <a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-5px">Funds Contributed</a>
                            <?php
                            if ($file_rights->edit) {
                            ?>
                                <a href="view-financier-status.php?fn=<?php echo $hash; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px;  margin-left:-9px">Financier Status</a>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
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
                                        <th width="13%"><strong>Fund Code</strong></th>
                                        <th width="13%"><strong>Financial Year</strong></th>
                                        <th width="13%"><strong>Fund Currency</strong></th>
                                        <th width="15%"><strong>Fund Amount</strong></th>
                                        <th width="12%"><strong>Exchange Rate</strong></th>
                                        <th width="15%"><strong>Local Amount (Ksh)</strong></th>
                                        <th width="10%"><strong>Date Received</strong></th>
                                        <th width="7%" data-orderable="false"><strong>Action</strong></th>
                                        <!--COLSPAN=4-->
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- =========================================== -->
                                    <?php
                                    if ($totalRows_rsDonorGrant > 0) {
                                        $sn = 0;
                                        do {
                                            $sn++;
                                            $fndid = $row_rsDonorGrant['fid'];
                                            $rate = $row_rsDonorGrant['exchange_rate'];
                                            $amnt = $row_rsDonorGrant['amount'] * $rate;
                                            $datereceived = date("d M Y", strtotime($row_rsDonorGrant['date_funds_released']));
                                    ?>
                                            <tr style="border-bottom:thin solid #EEE">
                                                <td><?php echo $sn; ?></td>
                                                <td><?php echo $row_rsDonorGrant['fund_code']; ?></td>
                                                <td><?php echo $row_rsDonorGrant['year']; ?></td>
                                                <td><?php echo $row_rsDonorGrant['curr']; ?></td>
                                                <td><?php echo number_format($row_rsDonorGrant['amount'], 2); ?></td>
                                                <td><?php echo $row_rsDonorGrant['exchange_rate']; ?></td>
                                                <td><?php echo number_format($amnt, 2); ?></td>
                                                <td><?php echo $datereceived; ?></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" onchange="checkBoxes()" aria-haspopup="true" aria-expanded="false">
                                                            Options <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a type="button" data-toggle="modal" data-target="#moreItemModal" id="moreItemModalBtn" onclick="moreInfo(<?= $fndid ?>)"><i class="fa fa-info"></i> More Info</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                    <?php
                                        } while ($row_rsDonorGrant = $query_rsDonorGrant->fetch());
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
<!-- Start Item more -->
<div class="modal fade" tabindex="-1" role="dialog" id="moreItemModal">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#03A9F4">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="color:#fff" align="center"><i class="fa fa-info-circle"></i> More Information</h4>
            </div>
            <div class="modal-body" id="moreinfo">
            </div>
            <div class="modal-footer">
                <div class="col-md-12 text-center">
                    <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Close</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<?php
} else {
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');
?>


<script src="general-settings/js/fetch-funding.js"></script>
