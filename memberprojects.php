<?php
    try {

require('includes/head.php');

if ($permission) {
        if (isset($_GET['mbrid'])) {
            $mbrid = $_GET['mbrid'];
            if (!empty($mbrid)) {
                $query_mbrprojs = $db->prepare("SELECT * FROM tbl_projmembers WHERE ptid='$mbrid' GROUP BY projid");
                $query_mbrprojs->execute();

                $query_mbrdetails = $db->prepare("SELECT title, fullname FROM tbl_projteam2 WHERE ptid='$mbrid' AND disabled='0'");
                $query_mbrdetails->execute();
                $row_mbrdetails = $query_mbrdetails->fetch();
            }
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
                    <?= $results; ?>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">

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