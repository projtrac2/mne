<?php
$hashfnid = base64_encode("fn918273AxZID{$fnid}");
require('includes/head.php');
if ($permission) {

    try {
        $query_title =  $db->prepare("SELECT id,title FROM tbl_mbrtitle");
        $query_title->execute();

        $query_financiertype =  $db->prepare("SELECT * FROM tbl_financier_type ORDER BY id ASC");
        $query_financiertype->execute();

        $query_country =  $db->prepare("SELECT id,country FROM countries");
        $query_country->execute();

        if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addfinancierfrm")) {
            $current_date = date("Y-m-d");
            if (!empty($_POST['financier']) && !empty($_POST['type']) && !empty($_POST['contactperson']) && !empty($_POST['title']) && !empty($_POST['designation']) && !empty($_POST['address']) && !empty($_POST['city']) && !empty($_POST['state']) && !empty($_POST['country']) && !empty($_POST['phone']) && !empty($_POST['email']) && !empty($_POST['comments']) && !empty($_POST['user_name'])) {
                $insertSQL = $db->prepare("UPDATE tbl_financiers SET financier=:financier, type=:type, contact=:contact, title=:title,designation=:designation, address=:address,city=:city, state=:state, country=:country, phone=:phone, email=:email, comments=:comments, updated_by=:user, date_created=:date WHERE id=:id");
                $insertSQL->execute(array(':financier' => $_POST['financier'], ':type' => $_POST['type'], ':contact' => $_POST['contactperson'], ':title' => $_POST['title'], ':designation' => $_POST['designation'], ':address' => $_POST['address'], ':city' => $_POST['city'],  ':state' => $_POST['state'], ':country' => $_POST['country'], ':phone' => $_POST['phone'], ':email' => $_POST['email'], ':comments' => $_POST['comments'], ':user' => $_POST['user_name'], ':date' => $current_date, ':id' => $_POST['fndid']));

                $last_id = $_POST['fndid'];
                if ($insertSQL->rowCount() == 1) {
                    $filecategory = "Financiers";
                    $catid = $last_id;
                    $myUser = $_POST['user_name'];

                    $count = count($_POST["attachmentpurpose"]);

                    for ($cnt = 0; $cnt < $count; $cnt++) {
                        if (!empty($_FILES['financierattachment']['name'][$cnt])) {
                            $reason = $_POST["attachmentpurpose"][$cnt];
                            $filename = basename($_FILES['financierattachment']['name'][$cnt]);
                            $ext = substr($filename, strrpos($filename, '.') + 1);

                            if (($ext != "exe") && ($_FILES["financierattachment"]["type"][$cnt] != "application/x-msdownload")) {
                                $newname = $catid . "-" . $filename;
                                $filepath = "uploads/financiers/" . $newname;

                                if (!file_exists($filepath)) {
                                    if (move_uploaded_file($_FILES['financierattachment']['tmp_name'][$cnt], $filepath)) {
                                        $qry2 = $db->prepare("INSERT INTO tbl_files (`projstage`, `filename`, `ftype`, `floc`, `fcategory`, `reason`, `uploaded_by`, `date_uploaded`) VALUES (:stage, :fname, :ext, :floc, :fcat, :reason, :user, :date)");
                                        $qry2->execute(array(':stage' => $catid, ':fname' => $newname, ':ext' => $ext, ':floc' => $filepath, ':fcat' => $filecategory, ':reason' => $reason, ':user' => $myUser, ':date' => $current_date));
                                    }
                                } else {
                                    $msg = 'File you are uploading already exists, try another file!!';
                                    $results = "<script type=\"text/javascript\">
    									swal({
    										title: \"Error!\",
    										text: \" $msg \",
    										type: 'Danger',
    										timer: 3000,
                        icon:'error',
    										showConfirmButton: false });
    								</script>";
                                }
                            } else {
                                $msg = 'This file type is not allowed, try another file!!';
                                $results = "<script type=\"text/javascript\">
    								swal({
    									title: \"Error!\",
    									text: \" $msg \",
    									type: 'Danger',
    									timer: 3000,
                      icon:'error',
    									showConfirmButton: false });
    								</script>";
                            }
                        } else {
                            $msg = 'You have not attached any file!!';
                            $results = "<script type=\"text/javascript\">
    							swal({
    								title: \"Error!\",
    								text: \" $msg \",
    								type: 'Danger',
    								timer: 3000,
                    icon:'error',
    								showConfirmButton: false });
    						</script>";
                        }
                    }

                    $msg = 'Financier successfully updated.';
                    $results = "<script type=\"text/javascript\">
    					swal({
    						title: \"Success!\",
    						text: \" $msg\",
    						type: 'Success',
    						timer: 2000,
                icon:'success',
    						showConfirmButton: false });
    					setTimeout(function(){
    						window.location.href = 'view-financiers.php';
    					}, 2000);
    				</script>";
                }
            }
        }

        if (isset($_GET['fn'])) {
            $hash = $_GET['fn'];
            $decode_fndid = base64_decode($hash);
            $fndid_array = explode("fn918273AxZID", $decode_fndid);
            $fn = $fndid_array[1];
        }

        $query_financier = $db->prepare("SELECT * FROM tbl_financiers WHERE id=:fn");
        $query_financier->execute(array(":fn" => $fn));
        $row_financier = $query_financier->fetch();
    } catch (PDOException $ex) {
        $results = flashMessage("An error occurred: " . $ex->getMessage());
    }
?>
    <script src="assets/ckeditor/ckeditor.js"></script>

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
                            <form id="add_financier" method="POST" name="addfinancierfrm" action="" enctype="multipart/form-data" autocomplete="off">

                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">DETAILS</legend>
                                    <div class="col-md-12">
                                        <label>Financier Name *:</label>
                                        <div>
                                            <input name="financier" type="text" class="form-control" value="<?= $row_financier['financier'] ?>" placeholder="Enter name of financing institution" style="border:#CCC thin solid; border-radius: 5px" required />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Financier Type *:</label>
                                        <div class="form-line">
                                            <select name="type" class="form-control show-tick" data-live-search="true" style="border:#CCC thin solid; border-radius:5px" required>
                                                <option value="" selected="selected" class="selection">...Select Financier Type...</option>
                                                <?php
                                                while ($row_financiertype = $query_financiertype->fetch()) {
                                                    $selected = ($row_financier['type'] == $row_financiertype['id'])  ? "selected" : '';
                                                ?>
                                                    <option value="<?php echo $row_financiertype['id'] ?>" <?= $selected ?>><?php echo $row_financiertype['type'] ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Contact Person *:</label>
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="contactperson" value="<?= $row_financier['contact'] ?>" placeholder="Enter contact person name" style="border:#CCC thin solid; border-radius: 5px" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Title *:</label>
                                        <div class="form-line">
                                            <select name="title" class="form-control show-tick" data-live-search="true" style="border:#CCC thin solid; border-radius:5px" required>
                                                <option value="" selected="selected" class="selection">...Select Contact Person Title...</option>
                                                <?php
                                                while ($row_title = $query_title->fetch()) {
                                                    $selected = ($row_financier['title'] == $row_title['id'])  ? "selected" : '';
                                                ?>
                                                    <option value="<?php echo $row_title['id'] ?>" <?= $selected ?>><?php echo $row_title['title'] ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Designation *:</label>
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="designation" value="<?= $row_financier['designation'] ?>" placeholder="Enter contact person designation" style="border:#CCC thin solid; border-radius: 5px" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label>Address *:</label>
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="address" value="<?= $row_financier['address'] ?>" placeholder="Enter the financial institution address" style="border:#CCC thin solid; border-radius: 5px" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label>City *:</label>
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="city" value="<?= $row_financier['city'] ?>" placeholder="Enter the financier city" style="border:#CCC thin solid; border-radius: 5px" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label>State/Province *:</label>
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="state" value="<?= $row_financier['state'] ?>" placeholder="Enter the located state/province/county" style="border:#CCC thin solid; border-radius: 5px" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Financier Country *:</label>
                                        <div class="form-line">
                                            <select name="country" class="form-control show-tick" data-live-search="true" style="border:#CCC thin solid; border-radius:5px" required>
                                                <option value="" selected="selected" class="selection">...Select Financier Country...</option>
                                                <?php
                                                while ($row_country = $query_country->fetch()) {
                                                    $selected = ($row_financier['country'] == $row_country['id'])  ? "selected" : '';
                                                ?>
                                                    <option value="<?php echo $row_country['id'] ?>" <?= $selected ?>><?php echo $row_country['country'] ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Phone Number *:</label>
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="phone" value="<?= $row_financier['phone'] ?>" placeholder="Enter financier phone number" style="border:#CCC thin solid; border-radius: 5px" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Email *:</label>
                                        <div class="form-line">
                                            <input type="email" class="form-control" name="email" value="<?= $row_financier['email'] ?>" placeholder="Enter financier email" style="border:#CCC thin solid; border-radius: 5px" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label>Comments :</label>
                                        <div class="form-line">
                                            <textarea name="comments" cols="45" rows="5" class="txtboxes" id="comments" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"><?= $row_financier['comments'] ?></textarea>
                                            <script>
                                                // Replace the <textarea id="editor1"> with a CKEditor
                                                // instance, using default configuration.
                                                CKEDITOR.replace('comments', {
                                                    on: {
                                                        instanceReady: function(ev) {
                                                            // Output paragraphs as <p>Text</p>.
                                                            this.dataProcessor.writer.setRules('p', {
                                                                indent: false,
                                                                breakBeforeOpen: false,
                                                                breakAfterOpen: false,
                                                                breakBeforeClose: false,
                                                                breakAfterClose: false
                                                            });
                                                            this.dataProcessor.writer.setRules('ol', {
                                                                indent: false,
                                                                breakBeforeOpen: false,
                                                                breakAfterOpen: false,
                                                                breakBeforeClose: false,
                                                                breakAfterClose: false
                                                            });
                                                            this.dataProcessor.writer.setRules('ul', {
                                                                indent: false,
                                                                breakBeforeOpen: false,
                                                                breakAfterOpen: false,
                                                                breakBeforeClose: false,
                                                                breakAfterClose: false
                                                            });
                                                            this.dataProcessor.writer.setRules('li', {
                                                                indent: false,
                                                                breakBeforeOpen: false,
                                                                breakAfterOpen: false,
                                                                breakBeforeClose: false,
                                                                breakAfterClose: false
                                                            });
                                                        }
                                                    }
                                                });
                                            </script>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">FINANCIER ATTACHMENTS</legend>
                                    <!-- File Upload | Drag & Drop OR With Click & Choose -->
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="card" style="margin-bottom:-20px">
                                                <div class="header">
                                                    <i class="ti-link"></i>MULTIPLE FILES UPLOAD - WITH CLICK & CHOOSE
                                                </div>
                                                <div class="body">
                                                    <table class="table table-bordered" id="donation_table">
                                                        <tr>
                                                            <th style="width:40%">Attachments</th>
                                                            <th style="width:58%">Attachment Purpose</th>
                                                            <th style="width:2%"><button type="button" name="addplus" onclick="add_row();" title="Add another document" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <input type="file" name="financierattachment[]" multiple id="financierattachment[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
                                                            </td>
                                                            <td>
                                                                <input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control" placeholder="Enter the purpose of this document" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                    </table>
                                                    <script type="text/javascript">
                                                        function add_row() {
                                                            $rowno = $("#donation_table tr").length;
                                                            $rowno = $rowno + 1;
                                                            $("#donation_table tr:last").after('<tr id="row' + $rowno + '"><td><input type="file" name="financierattachment[]" multiple id="financierattachment[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required></td><td><input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control"  placeholder="Enter the purpose of this document" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required></td><td><button type="button" class="btn btn-danger btn-sm"  onclick=delete_row("row' + $rowno + '")><span class="glyphicon glyphicon-minus"></span></button></td></tr>');
                                                            // <input type='text' name='funding[]' placeholder='Enter Name'></td><td><input type='button' value='DELETE' onclick=delete_row('row"+$rowno+"')></td></tr>");
                                                        }

                                                        function delete_row(rowno) {
                                                            $('#' + rowno).remove();
                                                        }
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- #END# File Upload | Drag & Drop OR With Click & Choose -->
                                </fieldset>
                                <div class="row clearfix">
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" align="center">
                                        <input name="user_name" type="hidden" id="user_name" value="<?php echo $user_name; ?>" />
                                        <input name="fndid" type="hidden" id="fndid" value="<?= $row_financier['id'] ?>" />
                                        <div class="btn-group">
                                            <input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" value="Save" />
                                        </div>
                                        <input type="hidden" name="MM_insert" value="addfinancierfrm" />
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                    </div>
                                </div>
                            </form>
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