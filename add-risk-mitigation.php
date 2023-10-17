<?php 
require('includes/head.php');
if ($permission) { 
    try {
        $query_allcategories = $db->prepare("SELECT rskid, category FROM tbl_projrisk_categories");
        $query_allcategories->execute();
        $rows_allcategories = $query_allcategories->fetch();
        $count_allcategories = $query_allcategories->rowCount();

        if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addmitigation")) {
            if (!empty($_POST['category']) && !empty($_POST['mitigation'])) {
                $cat = $_POST['category'];
                $current_date = date("Y-m-d");
                $results = "";
                for ($cnt = 0; $cnt < count($_POST['mitigation']); $cnt++) {
                    $mitigation = $_POST['mitigation'][$cnt];
                    $qry2 = $db->prepare("INSERT INTO tbl_projrisk_response (cat,response) VALUES (:cat,:response)");
                    $qry2->execute(array(':cat' => $cat, ':response' => $mitigation));
                }

                $msg = 'Mitigations Successfully Added';
                $results = "<script type=\"text/javascript\">
                     swal({
                         title: \"Success!\",
                         text: \" $msg\",
                         type: 'Success',
                         timer: 2000,
                         icon:'success',
                 showConfirmButton: false });
                     setTimeout(function(){
                         window.location.href = 'view-risk-mitigation.php';
                     }, 2000);
                 </script>";
            } else {
                $msg = 'Please fill all required fields!!';
                $results = "<script type=\"text/javascript\">
                     swal({
                         title: \"Error!\",
                         text: \" $msg \",
                         type: 'Danger',
                         timer: 3000,
                         icon:'error',
                     showConfirmButton: false });
                     setTimeout(function(){
                         window.location.href = 'view-risk-mitigation.php';
                     }, 3000);
                 </script>";
            }

            echo $results;
        }
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
                                        <div class="col-md-6">
                                            <label>Risk Category *:</label>
                                            <div class="form-line">
                                                <select name="category" id="category" class="form-control show-tick" data-live-search="true" style="border:#CCC thin solid; border-radius:5px" required>
                                                    <option value="" selected="selected" class="selection">.... Select Category ....</option>
                                                    <?php
                                                    do {
                                                    ?>
                                                        <option value="<?php echo $rows_allcategories['rskid'] ?>"><?php echo $rows_allcategories['category'] ?></option>
                                                    <?php
                                                    } while ($rows_allcategories = $query_allcategories->fetch());
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-md-12">
                                            <div class="body table-responsive">
                                                <table class="table table-bordered" id="meetings_table" style="width:100%">
                                                    <tr>
                                                        <th style="width:98%">Mitigation Measure</th>
                                                        <th style="width:2%"><button type="button" name="addplus1" onclick="add_row1();" title="Add another document" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input type="text" name="mitigation[]" id="mitigation[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </table>
                                            </div>
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
                                            <input type="hidden" name="MM_insert" value="addmitigation" />
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

<script type="text/javascript">
    function add_row1() {
        $rowno = $("#meetings_table tr").length;
        $rowno = $rowno + 1;
        $("#meetings_table tr:last").after('<tr id="mtng' + $rowno + '"><td>' +
            '<input type="text" name="mitigation[]" id="mitigation[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"></td><td><button type="button" class="btn btn-danger btn-sm"  onclick=delete_row1("mtng' + $rowno + '")><span class="glyphicon glyphicon-minus"></span></button></td></tr>');
    }

    function delete_row1(rowno) {
        $('#' + rowno).remove();
    }
</script>