<?php
    try {

$riskid = (isset($_GET['risk'])) ? base64_decode($_GET['risk']) : "";
require('includes/head.php');
if ($permission) { 
        $action = "Add";
        $button = "Add";
        $actionName = "MM_insert";
        $riskcategory = '';
        $typec = '';
        if (isset($_GET['risk'])) {
            $action = "Edit";
            $button = "Update";
            $actionName = "MM_Update";
            $query_rsRisk = $db->prepare("SELECT * FROM tbl_projrisk_categories WHERE catid = '$riskid'");
            $query_rsRisk->execute();
            $row_rsRisk = $query_rsRisk->fetch();
            $total_rs = $query_rsRisk->rowCount();

            if ($total_rs > 0) {
                $riskcategory = $row_rsRisk["category"];
            } else {
                header("Location: view-risk-categories.php");
            }
        }

        if (isset($_POST["MM_Update"]) && $_POST["MM_Update"] == "Edit") {
            $riskcat = $_POST['riskcat'];
            $riskid = $_POST['riskid'];
            $current_date = date("Y-m-d");
            $results = "";
            if (!empty($riskcat)) {
                $updateSQL = $db->prepare("UPDATE tbl_projrisk_categories SET category=:cat, changed_by=:user, date_changed=:date WHERE catid=:rskid");
                $updateSQL->execute(array(':cat' => $riskcat, ':user' => $user_name, ':date' => $current_date, ':rskid' => $riskid));
                if ($updateSQL->rowCount() == 1) {
                    $msg = 'Risk Category Successfully Updated';
                    $results = "<script type=\"text/javascript\">
                    swal({
                        title: \"Success!\",
                        text: \" $msg\",
                        type: 'Success',
                        timer: 2000,
                        'icon':'success',
                    showConfirmButton: false });
                    setTimeout(function(){
                        window.location.href = 'view-risk-categories.php';
                    }, 2000);
                </script>";
                } else {
                    $msg = 'Sorry could not update this Risk Category!!';
                    $results = "<script type=\"text/javascript\">
                    swal({
                        title: \"Error!\",
                        text: \" $msg \",
                        type: 'Danger',
                        timer: 3000,
                        'icon':'error',
                    showConfirmButton: false });
                </script>";
                }
            } else {
                $msg = 'Please fill all required fields!!';
                $results = "<script type=\"text/javascript\">
                swal({
                    title: \"Error!\",
                    text: \" $msg \",
                    type: 'Danger',
                    timer: 3000,
                    'icon':'error',
                showConfirmButton: false });
            </script>";
            }

            echo $results;
        }

        if (isset($_POST["MM_insert"]) && $_POST["MM_insert"] == "Add") {
            $riskcat = $_POST['riskcat'];
            $current_date = date("Y-m-d");
            $results = "";
            if (!empty($riskcat)) {
                $insertSQL = $db->prepare("INSERT INTO tbl_projrisk_categories(category, created_by, date_created) VALUES (:cat, :user, :date)");
                $results = $insertSQL->execute(array(':cat' => $riskcat, ':user' => $user_name, ':date' => $current_date));

                if ($insertSQL->rowCount() == 1) {
                    $msg = 'Risk Category Successfully Added';

                    $results = "<script type=\"text/javascript\">
                        swal({
                            title: \"Success!\",
                            text: \" $msg\",
                            type: 'Success',
                            timer: 2000,
                            icon:'success',
                            showConfirmButton: false
                        });
                        setTimeout(function(){
                            window.location.href = 'view-risk-categories.php';
                        }, 2000);
                    </script>";
                } else {
                    $msg = 'Sorry could not add the risk category!!';
                    $results = "<script type=\"text/javascript\">
                        swal({
                            title: \"Error!\",
                            text: \" $msg \",
                            type: 'Danger',
                            timer: 3000,
                            'icon':'error',
                            showConfirmButton: false
                        });
                    </script>";
                }
            } else {
                $msg = 'Please fill all required fields!!';
                $results = "<script type=\"text/javascript\">
                    swal({
                        title: \"Error!\",
                        text: \" $msg \",
                        type: 'Danger',
                        timer: 3000,
                        'icon':'error',
                        showConfirmButton: false
                    });
                </script>";
            }
        }
   
?>

    <!-- start body  -->
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                    <?= $icon ?>
                    <?php echo $pageTitle ?>
                    <div class="btn-group" style="float:right">
                        <div class="btn-group" style="float:right">
                            <button onclick="history.go(-1)" class="btn bg-orange waves-effect pull-right" style="margin-right: 10px">
                                Go Back
                            </button>
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
                            <form id="addprojrisks" method="POST" name="addprojrisks" action="" enctype="multipart/form-data" autocomplete="off">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-plus-square" aria-hidden="true"></i> <?= $action ?> Risk/Issue Category</legend>
                                    <div class="col-md-12">
                                        <label>Risk/Issue Category Name *:</label>
                                        <div>
                                            <input name="riskcat" type="text" class="form-control" id="riskcat" value="<?php echo (isset($_GET['risk'])) ?  htmlentities($riskcategory) : "" ?>" placeholder="Add write category name here" style="border:#CCC thin solid; border-radius: 5px" required />
                                        </div>
                                    </div>

                                    <div class="row clearfix">
                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" align="center">
                                            <input name="user_name" type="hidden" id="user_name" value="<?php echo $user_name; ?>" />
                                            <input type="hidden" name="<?= $actionName ?>" value="<?= $action ?>" />
                                            <?php
                                            echo (isset($_GET['risk'])) ?  '<input type="hidden" name="riskid" value="' . $riskid . '" />' : "";
                                            ?>
                                            <div class="btn-group">
                                                <input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" value="<?= $button ?>" />
                                            </div>
                                        </div>
                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
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
} catch (PDOException $ex) {
    customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}
require('includes/footer.php');
?>