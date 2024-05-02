<?php
try {
    require('includes/head.php');
    if ($permission) {
        $query_contract_guarantees = $db->prepare("SELECT * FROM tbl_contract_guarantees g INNER JOIN tbl_projects p ON p.projid=g.projid INNER JOIN tbl_contractor c ON p.projcontractor = c.contrid");
        $query_contract_guarantees->execute();
        $totalRows_contract_guarantees = $query_contract_guarantees->rowCount();

?>
        <section class="content">
            <div class="container-fluid">
                <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                    <h4 class="contentheader">
                        <?= $icon . ' ' . $pageTitle ?>
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
                                <div class="table-responsive">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="guarantees_table">
                                            <thead>
                                                <tr>
                                                    <th style="width:5%">#</th>
                                                    <th style="width:25%">Guarantee</th>
                                                    <th style="width:20%">Project</th>
                                                    <th style="width:20%">Contractor</th>
                                                    <th style="width:15%">End Date</th>
                                                    <th style="width:10%">Duration</th>
                                                    <th style="width:10%">Due In</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if ($totalRows_contract_guarantees > 0) {
                                                    $rowno = 0;
                                                    while ($row_contract_guarantees = $query_contract_guarantees->fetch()) {
                                                        $rowno++;
                                                        $start_date = $row_contract_guarantees['start_date'];
                                                        $project = $row_contract_guarantees['projname'];
                                                        $duration = $row_contract_guarantees['duration'];
                                                        $contractor = $row_contract_guarantees['contractor_name'];
                                                        $end_date = date('Y-m-d', strtotime($start_date . ' + ' . $duration . ' days'));

                                                        $today = date('Y-m-d');
                                                        $origin = date_create($today);
                                                        $target = date_create($end_date);
                                                        $interval = date_diff($origin, $target);
                                                        $remaining_time = $interval->format('%a');

                                                        if ($remaining_time >= 30) {
                                                            $badge = "bg-green";
                                                        } elseif ($remaining_time < 30 && $remaining_time >= 10) {
                                                            $badge = "bg-orange";
                                                        } elseif ($remaining_time < 10) {
                                                            $badge = "bg-danger";
                                                        }
                                                ?>
                                                        <tr id="guarantee_row">
                                                            <td style="width:5%"><?= $rowno ?></td>
                                                            <td style="width:35%"><?= $row_contract_guarantees['guarantee'] ?></td>
                                                            <td style="width:20%"><?= $project ?></td>
                                                            <td style="width:20%"><?= $contractor ?></td>
                                                            <td style="width:15%"><?= $end_date ?></td>
                                                            <td style="width:10%"><?= $duration ?> Days</td>
                                                            <td style="width:10%"><span class="badge <?= $badge ?>"><?= $remaining_time ?> Days</span></td>
                                                        </tr>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
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
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
?>