<?php
require('includes/head.php');

if ($permission) {

    try {
        $currentPage = $_SERVER["PHP_SELF"];
        $query_mainfunder = $db->prepare("SELECT sum(amount) as ttamt, year FROM tbl_main_funding f inner join tbl_fiscal_year y on y.id=f.financialyear GROUP BY financialyear");
        $query_mainfunder->execute();
        $ttrows_mainfunder = $query_mainfunder->rowCount();

        $query_otherfunders = $db->prepare("SELECT sum(amount) as ttamt, year FROM tbl_other_funding f inner join tbl_fiscal_year y on y.id=f.financialyear  GROUP BY financialyear");
        $query_otherfunders->execute();
        $ttrows_otherfunders = $query_otherfunders->rowCount();

        $query_rsfundingtype = $db->prepare("SELECT * FROM tbl_funding_type WHERE status=1 ORDER BY id ASC");
        $query_rsfundingtype->execute();
        $row_fundingtype = $query_rsfundingtype->fetchAll();
        $ttrows_rsfundingtype = $query_rsfundingtype->rowCount();

        $cy = date("Y");
        $cm = date("m");
        if ($cm < 7) {
            $currentyear = $cy - 1;
        } else {
            $currentyear = $cy;
        }
        $nxtyr = $currentyear + 1;
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
                        </div>
                    </div>
                </h4>
            </div>
            <div class="row clearfix">
                <div class="block-header">
                    <div class="col-md-12">
                        <div class="header" style="padding-bottom:0px">
                            <div class="button-demo" style="margin-top:-15px">
                                <span class="label bg-black" style="font-size:18px">
                                    <img src="assets/images/proj-icon.png" alt="Project" title="Project" style="vertical-align:middle; height:25px" /> Menu
                                </span>
                                <a href="view-financiers.php" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:4px"><i class="fa fa-university"></i> &nbsp; &nbsp; Financiers</a>
                                <a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; margin-left:-9px"><i class="fa fa-money"></i> &nbsp; &nbsp; Funding</a>
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
                                <ul class="nav nav-tabs" style="font-size:14px">
                                    <?php
                                    if ($ttrows_rsfundingtype > 0) {
                                        foreach ($row_fundingtype as $row) {
                                            $ftid = $row["id"];
                                            $fundingtype = $row["type"];
                                            $fundingcatid = $row["category"];

                                            // get adps
                                            $query_funds =  $db->prepare("SELECT * FROM tbl_funds f inner join tbl_financiers fc on fc.id=f.funder WHERE fc.type=:fcid");
                                            $query_funds->execute(array(":fcid" => $fundingcatid));
                                            $totalrows_funds = $query_funds->rowCount();
                                            if ($fundingcatid == 1) {
                                    ?>
                                                <li class="active">
                                                    <a data-toggle="tab" href="#home"><i class="fa fa-briefcase bg-brown" aria-hidden="true"></i> <?= $fundingtype ?>s &nbsp;<span class="badge bg-brown"><?php echo $totalrows_funds; ?></span></a>
                                                </li>
                                            <?php
                                            } elseif ($fundingcatid == 2) {
                                            ?>
                                                <li>
                                                    <a data-toggle="tab" href="#menu<?= $fundingcatid ?>"><i class="fa fa-flag bg-orange" aria-hidden="true"></i> <?= $fundingtype ?>s &nbsp;<span class="badge bg-orange"><?php echo $totalrows_funds; ?></span></a>
                                                </li>
                                            <?php
                                            } elseif ($fundingcatid == 3) {
                                            ?>
                                                <li>
                                                    <a data-toggle="tab" href="#menu<?= $fundingcatid ?>"><i class="fa fa-university bg-orange" aria-hidden="true"></i> <?= $fundingtype ?>s &nbsp;<span class="badge bg-orange"><?php echo $totalrows_funds; ?></span></a>
                                                </li>
                                            <?php
                                            } else {
                                            ?>
                                                <li>
                                                    <a data-toggle="tab" href="#menu<?= $fundingcatid ?>"><i class="fa fa-group bg-orange" aria-hidden="true"></i> <?= $fundingtype ?>s &nbsp;<span class="badge bg-orange"><?php echo $totalrows_funds; ?></span></a>
                                                </li>
                                    <?php
                                            }
                                        }
                                    }
                                    ?>
                                </ul>
                                <div class="tab-content">
                                    <?php
                                    try {
                                        if ($ttrows_rsfundingtype > 0) {
                                            foreach ($row_fundingtype as $row) {
                                                $ftid = $row["id"];
                                                $fundingtype = $row["type"];
                                                $fundingcatid = $row["category"];
                                                if ($fundingcatid == 1) {
                                                    $titlescolor = "bg-brown";
                                                } else {
                                                    $titlescolor = "bg-orange";
                                                }

                                                // get adps
                                                $query_financierfunds =  $db->prepare("SELECT *, y.id AS yid, f.id AS fnid, fc.id AS FID, fc.type AS ftype FROM tbl_funds f inner join tbl_financiers fc on fc.id=f.funder inner join tbl_fiscal_year y on y.id=f.financial_year inner join tbl_currency c ON c.id=f.currency WHERE fc.type=:fcid ORDER BY y.id DESC");
                                                $query_financierfunds->execute(array(":fcid" => $fundingcatid));

                                                if ($fundingcatid == 1) {
                                    ?>
                                                    <div id="home" class="tab-pane fade in active">
                                                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                            <thead>
                                                                <tr class="<?= $titlescolor ?>">
                                                                    <th width="4%"><strong>#</strong></th>
                                                                    <th width="29%"><strong>Financier</strong></th>
                                                                    <th width="12%"><strong>Financial Year</strong></th>
                                                                    <th width="15%"><strong>Amount Financing (Ksh)</strong></th>
                                                                    <th width="15%"><strong>Amount Received (Ksh)</strong></th>
                                                                    <th width="15%"><strong>Amount Utilized (Ksh)</strong></th>
                                                                    <th width="10%"><strong>Action</strong></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $nm = 0;
                                                                while ($row_financierfunds = $query_financierfunds->fetch()) {
                                                                    $nm = $nm + 1;
                                                                    $fid = $row_financierfunds['FID'];
                                                                    $fnid = $row_financierfunds['fnid'];
                                                                    $year = $row_financierfunds['year'];
                                                                    $fyr = $row_financierfunds['yr'];
                                                                    $fyear = $row_financierfunds['yid'];
                                                                    $amount = $row_financierfunds['amount'];
                                                                    $rate = $row_financierfunds['exchange_rate'];
                                                                    $localamt = $amount * $rate;
                                                                    $currency = $row_financierfunds['code'];
                                                                    $financier = $row_financierfunds['financier'];

                                                                    $query_plannedfunds =  $db->prepare("SELECT SUM(amountfunding) AS planned FROM tbl_myprojfunding f inner join tbl_annual_dev_plan p on p.projid=f.projid WHERE p.status=1 AND p.financial_year=:fyear AND f.financier=:fid");
                                                                    $query_plannedfunds->execute(array(":fyear" => $fyear, ":fid" => $fid));
                                                                    $row_plannedfunds = $query_plannedfunds->fetch();
                                                                    $plannedfunds = $row_plannedfunds["planned"];

                                                                    $query_utilisedfunds =  $db->prepare("SELECT SUM(amount_requested) AS planned FROM tbl_payments_request r inner join tbl_annual_dev_plan p on p.projid=r.projid left join tbl_payment_request_financiers f on f.request_id=r.id WHERE p.status = 1 AND p.financial_year = :fyear AND f.financier_id = :fid AND request_type=1");
                                                                    $query_utilisedfunds->execute(array(":fyear" => $fyear, ":fid" => $fid));
                                                                    $row_utilisedfunds = $query_utilisedfunds->fetch();
                                                                    $utilisedInhouse = !is_null($row_utilisedfunds["planned"]) ?  $row_utilisedfunds["planned"] : 0;

                                                                    $query_utilisedContractor =  $db->prepare("SELECT SUM(requested_amount) AS planned FROM tbl_contractor_payment_requests r inner join tbl_annual_dev_plan p on p.projid=r.projid left join tbl_payment_request_financiers f on f.request_id=r.id WHERE p.status = 1 AND p.financial_year = :fyear AND f.financier_id = :fid AND request_type=2");
                                                                    $query_utilisedContractor->execute(array(":fyear" => $fyear, ":fid" => $fid));
                                                                    $row_utilisedContractor = $query_utilisedContractor->fetch();
                                                                    $utilisedContractor = !is_null($row_utilisedContractor["planned"]) ? $row_utilisedContractor["planned"] : 0;

                                                                    $utilisedfunds = $utilisedContractor + $utilisedInhouse;

                                                                    $query_distr =  $db->prepare("SELECT id FROM tbl_departments_allocation WHERE fundid = :fid");
                                                                    $query_distr->execute(array(":fid" => $fnid));
                                                                    $count_distr = $query_distr->rowCount();

                                                                    if ($count_distr > 0) {
                                                                        $txtcolor = "#4CAF50";
                                                                    } else {
                                                                        $txtcolor = "#FF5722";
                                                                    }

                                                                    $hashfnid = base64_encode("fd918273AxZID{$fnid}");
                                                                    $hashfnid2 = base64_encode("fd918273ZaYID{$fnid}");
                                                                    $hashfyear = base64_encode("fd918273ZaYID{$fyear}");

                                                                    $totalutilized = 0;
                                                                    $action =
                                                                        '<div class="btn-group">
                                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"  onchange="checkBoxes()"  aria-haspopup="true" aria-expanded="false">
                                                                        Options <span class="caret"></span>
                                                                    </button>
                                                                    <ul class="dropdown-menu">
                                                                        <li>
                                                                            <a type="button" href="./reports/financier-funding-pdf.php?fnid=' . $hashfnid . '"  target="_blank"><i class="fa fa-info"></i>Report</a>
                                                                        </li>';
                                                                    if ($plannedfunds == 0 && $fyr == $currentyear) {
                                                                        if (in_array("updated", $page_actions)) {
                                                                            $action .=
                                                                                '<li>
                                                                                    <a type="button"  href="add-development-funds.php?fnd=' . $hashfnid2 . '">
                                                                                    <i class="glyphicon glyphicon-edit"></i> Edit </a>
                                                                                </li> ';
                                                                        }
                                                                        if (in_array("delete", $page_actions)) {
                                                                            $action .=
                                                                                '<li>
                                                                                    <a type="button" data-toggle="modal" data-target="#removeItemModal" id="removeItemModalBtn" onclick="removeItem(' . $fnid . ')" alt="Delete"  title="Delete Funds"> <i class="glyphicon glyphicon-trash"></i>Remove</a>
                                                                                </li>';
                                                                        }
                                                                    }
                                                                    $action .=
                                                                        '</ul>
                                                                </div>';
                                                                ?>
                                                                    <tr style="background-color:#eff9ca">
                                                                        <td width="4%" align="center"><?php echo $nm; ?></td>
                                                                        <td width="29%"><?php echo $financier; ?></td>
                                                                        <td width="12%"><?php echo $year; ?></td>
                                                                        <td width="15%" style="color:<?= $txtcolor ?>"><?php echo number_format($localamt, 2); ?></td>
                                                                        <td width="15%"><?php echo number_format($plannedfunds, 2); ?></td>
                                                                        <td width="15%"><?php echo number_format($utilisedfunds, 2); ?></td>
                                                                        <td width="10%"><?php echo $action; ?></td>
                                                                    </tr>
                                                                <?php
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                <?php
                                                } else {
                                                ?>
                                                    <div id="menu<?= $fundingcatid ?>" class="tab-pane fade">
                                                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                            <thead>
                                                                <tr class="<?= $titlescolor ?>">
                                                                    <th><strong>#</strong></th>
                                                                    <th><strong>Financier</strong></th>
                                                                    <th><strong>Financial Year</strong></th>
                                                                    <th width="15%"><strong>Amount Financing (Ksh)</strong></th>
                                                                    <th width="15%"><strong>Amount Received (Ksh)</strong></th>
                                                                    <th width="15%"><strong>Amount Utilized (Ksh)</strong></th>
                                                                    <th><strong>Action</strong></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $nm = 0;
                                                                while ($row_financierfunds = $query_financierfunds->fetch()) {

                                                                    $nm = $nm + 1;
                                                                    $fid = $row_financierfunds['FID'];
                                                                    $fnid = $row_financierfunds['fnid'];
                                                                    $year = $row_financierfunds['year'];
                                                                    $fyr = $row_financierfunds['yr'];
                                                                    $fyear = $row_financierfunds['yid'];
                                                                    $amount = $row_financierfunds['amount'];
                                                                    $rate = $row_financierfunds['exchange_rate'];
                                                                    $localamt = $amount * $rate;
                                                                    $currency = $row_financierfunds['code'];
                                                                    $financier = $row_financierfunds['financier'];
                                                                    $ftype = $row_financierfunds['ftype'];

                                                                    $query_plannedfunds =  $db->prepare("SELECT SUM(amountfunding) AS planned FROM tbl_myprojfunding f inner join tbl_annual_dev_plan p on p.projid=f.projid WHERE p.status=1 AND p.financial_year=:fyear AND f.financier=:fid");
                                                                    $query_plannedfunds->execute(array(":fyear" => $fyear, ":fid" => $fid));
                                                                    $row_plannedfunds = $query_plannedfunds->fetch();
                                                                    $plannedfunds = $row_plannedfunds["planned"];

                                                                    $query_utilisedfunds =  $db->prepare("SELECT SUM(amount_requested) AS planned FROM tbl_payments_request r inner join tbl_annual_dev_plan p on p.projid=r.projid left join tbl_payment_request_financiers f on f.request_id=r.id WHERE p.status = 1 AND p.financial_year = :fyear AND f.financier_id = :fid AND request_type=1");
                                                                    $query_utilisedfunds->execute(array(":fyear" => $fyear, ":fid" => $fid));
                                                                    $row_utilisedfunds = $query_utilisedfunds->fetch();
                                                                    $utilisedInhouse = !is_null($row_utilisedfunds["planned"]) ?  $row_utilisedfunds["planned"] : 0;

                                                                    $query_utilisedContractor =  $db->prepare("SELECT SUM(requested_amount) AS planned FROM tbl_contractor_payment_requests r inner join tbl_annual_dev_plan p on p.projid=r.projid left join tbl_payment_request_financiers f on f.request_id=r.id WHERE p.status = 1 AND p.financial_year = :fyear AND f.financier_id = :fid AND request_type=2");
                                                                    $query_utilisedContractor->execute(array(":fyear" => $fyear, ":fid" => $fid));
                                                                    $row_utilisedContractor = $query_utilisedContractor->fetch();
                                                                    $utilisedContractor = !is_null($row_utilisedContractor["planned"]) ? $row_utilisedContractor["planned"] : 0;
                                                                    $utilisedfunds = $utilisedContractor + $utilisedInhouse;

                                                                    if ($ftype == 2) {
                                                                        $query_distr =  $db->prepare("SELECT a.id FROM tbl_departments_allocation a inner join tbl_funds f on f.id=a.fundid inner join tbl_financiers d ON d.id=f.funder WHERE a.fundid = :fid AND d.type=:ftype");
                                                                        $query_distr->execute(array(":fid" => $fnid, ":ftype" => $ftype));
                                                                        $count_distr = $query_distr->rowCount();

                                                                        if ($count_distr > 0) {
                                                                            $txtcolor = "#4CAF50";
                                                                        } else {
                                                                            $txtcolor = "#FF5722";
                                                                        }
                                                                    } else {
                                                                        $txtcolor = "#000";
                                                                    }

                                                                    $hashfnid = base64_encode("fd918273AxZID{$fnid}");
                                                                    $hashfnid2 = base64_encode("fd918273ZaYID{$fnid}");
                                                                    $hashfyear = base64_encode("fd918273ZaYID{$fyear}");

                                                                    $totalutilized = 0;
                                                                    $action = '<div class="btn-group">
                                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"  onchange="checkBoxes()"  aria-haspopup="true" aria-expanded="false">
                                                                        Options <span class="caret"></span>
                                                                    </button>
                                                                    <ul class="dropdown-menu">
                                                                        <li>
                                                                            <a type="button" href="./reports/financier-funding-pdf.php?fnid=' . $hashfnid . '"><i class="fa fa-info"></i>Report</a>
                                                                        </li>';
                                                                    if ($plannedfunds == 0 && $fyr == $currentyear) {
                                                                        if (in_array("update", $page_actions)) {
                                                                            $action .= '
                                                                            <li>
                                                                                <a type="button"  href="add-development-funds.php?fnd=' . $hashfnid2 . '">
                                                                                <i class="glyphicon glyphicon-edit"></i> Edit </a>
                                                                            </li> ';
                                                                        }

                                                                        if (in_array("delete", $page_actions)) {
                                                                            $action .= '
                                                                            <li>
                                                                            <a type="button" data-toggle="modal" data-target="#removeItemModal" id="removeItemModalBtn" onclick="removeItem(' . $fnid . ')" alt="Delete"  title="Delete Funds"> <i class="glyphicon glyphicon-trash"></i>Remove</a>
                                                                        </li>';
                                                                        }
                                                                    }
                                                                    $action .= '</ul>
                                                                    </div>';
                                                                ?>
                                                                    <tr style="background-color:#eff9ca">
                                                                        <td width="4%" align="center"><?php echo $nm; ?></td>
                                                                        <td width="29%"><?php echo $financier; ?></td>
                                                                        <td width="12%"><?php echo $year; ?></td>
                                                                        <td width="15%" style="color:<?= $txtcolor ?>"><?php echo number_format($localamt, 2); ?></td>
                                                                        <td width="15%"><?php echo number_format($plannedfunds, 2); ?></td>
                                                                        <td width="15%"><?php echo number_format($totalutilized, 2); ?></td>
                                                                        <td width="10%"><?php echo $action; ?></td>
                                                                    </tr>
                                                                <?php
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                    <?php
                                                }
                                            }
                                        }
                                    } catch (PDOException $ex) {
                                        $result = flashMessage("An error occurred: " . $ex->getMessage());
                                        print($result);
                                    }
                                    ?>
                                </div>
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
    <!-- End Item more -->

    <!-- Start Item Delete -->
    <div class="modal fade" tabindex="-1" role="dialog" id="removeItemModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#03A9F4">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="color:#fff" align="center"><i class="glyphicon glyphicon-trash"></i> Delete Item</h4>
                </div>
                <div class="modal-body">
                    <div class="removeItemMessages"></div>
                    <p align="center">Are you sure you want to delete this record?</p>
                </div>
                <div class="modal-footer removeProductFooter">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-success" id="removeItemBtn"> <i class="fa fa-check-square-o"></i> Delete</button>
                        <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> <i class="fa fa-remove"></i> Cancel</button>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- Start Item Delete -->

<?php
} else {
    $results =  restriction();
    echo $results;
}
require('includes/footer.php');
?>
<script src="general-settings/js/fetch-funding.js"></script>