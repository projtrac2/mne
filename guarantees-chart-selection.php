<?php
try {
    require('includes/head.php');
    $data = (string) $_GET['data'];
    $icon = '<i class="fa fa-cogs" aria-hidden="true"></i>';
    $pageTitle = '';
    $guarantees = [];

    function contractGuaranteesExpiring()
    {
        global $db;
        $guarantees_expiring = [];
        $guarantees_stmt = $db->prepare('SELECT * FROM tbl_contract_guarantees');
        $guarantees_stmt->execute();
        $guarantees_obj = $guarantees_stmt->fetchAll(PDO::FETCH_OBJ);

        foreach ($guarantees_obj as $key => $guarantee) {
            $start_date = $guarantee->start_date;
            $end_date = date('Y-m-d', strtotime("+$guarantee->duration days", strtotime($start_date)));
            $today = date('Y-m-d');
            $thirty_days = date('Y-m-d', strtotime("+30 days", strtotime($today)));
            
            // expiring
            $date_differences = date_diff(date_create($end_date), date_create($thirty_days));
            $is_expiring = (int) $date_differences->format("%a");
            if ($is_expiring <= 30) {
                array_push($guarantees_expiring, $guarantee);
            }
            
        }

        return $guarantees_expiring;
    }

    function contractGuaranteesHealthy() 
    {
        global $db;
        $guarantees_healthy = [];
        $guarantees_stmt = $db->prepare('SELECT * FROM tbl_contract_guarantees');
        $guarantees_stmt->execute();
        $guarantees_obj = $guarantees_stmt->fetchAll(PDO::FETCH_OBJ);

        foreach ($guarantees_obj as $key => $guarantee) {
            $start_date = $guarantee->start_date;
            $today = date('Y-m-d');
            $thirty_days = date('Y-m-d', strtotime("+30 days", strtotime($today)));
            
            // healthy
            $end_date = date('Y-m-d', strtotime("+$guarantee->duration days", strtotime($start_date)));
            $date_difference = date_diff(date_create($end_date), date_create($thirty_days));
            $is_healthy = (int) $date_difference->format("%a");
            if ($is_healthy >= 30 && $today <= $end_date) {
                array_push($guarantees_healthy, $guarantee);
            }
        }

        return $guarantees_healthy;
    }

    function contractGuaranteesExpired() 
    {
        global $db;
        $guarantees_expired = [];
        $guarantees_stmt = $db->prepare('SELECT * FROM tbl_contract_guarantees');
        $guarantees_stmt->execute();
        $guarantees_obj = $guarantees_stmt->fetchAll(PDO::FETCH_OBJ);

        foreach ($guarantees_obj as $key => $guarantee) {
            $start_date = $guarantee->start_date;
            $end_date = date('Y-m-d', strtotime("+$guarantee->duration days", strtotime($start_date)));
            $today = date('Y-m-d');
            // expired
            if ($today >= $end_date) {
                array_push($guarantees_expired, $guarantee);
            }
            
        }

        return $guarantees_expired;
    }

    if ($data === 'About to expire') {
        $guarantees = contractGuaranteesExpiring();
        $pageTitle = 'Contract Guarantees About To Expire';
    }

    if ($data === 'Healthy') {
        $guarantees = contractGuaranteesHealthy();
        $pageTitle = 'Contract Guarantees Healthy';
    }

    if ($data === 'Expired') {
        $guarantees = contractGuaranteesExpired();
        $pageTitle = 'Contract Guarantees Expired';
    }
    // if ($permission) {

?>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                    <?= $icon . ' ' . $pageTitle ?>
                    <div class="btn-group" style="float:right; padding-right:5px">
                        <input type="button" VALUE="Go Back" class="btn btn-warning pull-right" onclick="location.href='dashboard'" id="btnback">
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
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border bg-primary" style="border-radius:3px"><i class="fa fa-tasks" aria-hidden="true"></i> Contract Guarantees</legend>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover js-basic-example" id="guarantees_table">
                                        <thead>
                                            <tr>
                                                <th style="width:5%">#</th>
                                                <th style="width:55%">Guarantee</th>
                                                <th style="width:15%">Start Date</th>
                                                <th style="width:15%">Duration</th>
                                                <th style="width:10%">End Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php  
                                                $hash = 1;
                                                foreach ($guarantees as $key => $value) {
                                                    $end_date = date('Y-m-d', strtotime("+$value->duration days", strtotime($value->start_date)));
                                                ?>
                                                <tr>
                                                    <td style="width:5%"><?= $hash ?></td>
                                                    <td style="width:55%"><?= $value->guarantee ?></td>
                                                    <td style="width:15%"><?= $value->start_date ?></td>
                                                    <td style="width:15%"><?= $value->duration ?></td>
                                                    <td style="width:10%"><?= $end_date ?></td>
                                                </tr>
                                                <?php  
                                                    $hash++;
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
    </section>
<?php
    require('includes/footer.php');

    // }
} catch (PDOException $th) {
    var_dump($th);
    customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
?>