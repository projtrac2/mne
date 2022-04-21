<?php
$mtid = (isset($_GET['mtid'])) ? base64_decode($_GET['mtid']) : "";

$pageName = "Strategic Plans";
$replacement_array = array(
    'planlabel' => "CIDP",
    'plan_id' => base64_encode(6),
);

$page = "view";

require('includes/head.php');
if ($permission) {
$pageTitle = "Risk Mitigations";

try {
    $query_allcategories = $db->prepare("SELECT rskid, category FROM tbl_projrisk_categories");
    $query_allcategories->execute();
    $rows_allcategories = $query_allcategories->fetch();
    $count_allcategories = $query_allcategories->rowCount();

    $query_rsMitigation = $db->prepare("SELECT * FROM tbl_projrisk_response WHERE id=:mtid");
    $query_rsMitigation->execute(array(':mtid' => $mtid));
    $rows_rsMitigation = $query_rsMitigation->fetch();
    $count_rsMitigation = $query_rsMitigation->rowCount();


    $cat = $rows_rsMitigation['cat'];
    $response = $rows_rsMitigation['response'];

    if ((isset($_POST["MM_edit"])) && ($_POST["MM_edit"] == "editmitigation")) {
        if (!empty($_POST['category']) && !empty($_POST['mitigation'])) {
            $cat = $_POST['category'];
            $current_date = date("Y-m-d");
            $results = "";
            $mitigation = $_POST['mitigation'];
            $mtid = $_POST['mtid'];
            $qry2 = $db->prepare("UPDATE tbl_projrisk_response  SET cat=:cat,response=:response WHERE id=:mtid ");
            $qry2->execute(array(':cat' => $cat, ':response' => $mitigation, ":mtid" => $mtid));

            $msg = 'Mitigations Successfully Updated';
            $results = "<script type=\"text/javascript\">
                    swal({
                        title: \"Success!\",
                        text: \" $msg\",
                        type: 'Success',
                        timer: 5000,
                        icon:'success',
                        showConfirmButton: false
                    });
                    setTimeout(function(){
                        window.location.href = 'view-risk-mitigation.php';
                    }, 5000);
                 </script>";
        } else {
            $msg = 'Please fill all required fields!!';
            $results = "
                <script type=\"text/javascript\">
                    swal({
                        title: \"Error!\",
                        text: \" $msg \",
                        type: 'Danger',
                        timer: 5000,
                        icon:'error',
                    showConfirmButton: false });
                    setTimeout(function(){
                        window.location.href = 'view-risk-mitigation.php';
                    }, 5000);
                </script>";
        }

        echo $results;
    }
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
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="body">
                        <!-- start body -->
                        <form id="addinspectionchecklist" method="POST" name="addinspectionchecklist" action="" enctype="multipart/form-data" autocomplete="off">
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-plus-square" aria-hidden="true"></i> Add Mitigation Measures</legend>
                                <div class="col-md-12" style="padding-left:0px">
                                    <label>Risk Category *:</label>
                                    <div class="form-line">
                                        <select name="category" id="category" class="form-control show-tick" data-live-search="true" style="border:#CCC thin solid; border-radius:5px" required>
                                            <option value="" selected="selected" class="selection">.... Select Category ....</option>
                                            <?php
                                            do {
                                                $selected = $cat == $rows_allcategories['rskid'] ? "selected" : "";
                                            ?>
                                                <option value="<?php echo $rows_allcategories['rskid'] ?>" <?= $selected ?>><?php echo $rows_allcategories['category'] ?></option>
                                            <?php
                                            } while ($rows_allcategories = $query_allcategories->fetch());
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-md-12">
                                        <input type="text" name="mitigation" id="mitigation" value="<?= $response ?>" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" align="center">
                                        <input name="user_name" type="hidden" id="user_name" value="<?php echo $user_name; ?>" />

                                        <div class="btn-group">
                                            <input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" value="Submit" />
                                        </div>
                                        <input type="hidden" name="MM_edit" value="editmitigation" />
                                        <input type="hidden" name="mtid" value="<?= $mtid ?>" />
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

require('includes/footer.php');
?>
