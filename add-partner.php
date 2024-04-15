<?php
try {

require('includes/head.php');

if ($permission) {
        $query_title =  $db->prepare("SELECT id,title FROM tbl_mbrtitle");
        $query_title->execute();

        $query_country =  $db->prepare("SELECT id,country FROM countries");
        $query_country->execute();

        $msg = 'Partner successfully added.';
        if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addpartnerfrm")) {
            $current_date = date("Y-m-d");
            $results = "";
            if (!empty($_POST['partner']) && !empty($_POST['contactperson']) && !empty($_POST['title']) && !empty($_POST['designation']) && !empty($_POST['address']) && !empty($_POST['city']) && !empty($_POST['state']) && !empty($_POST['country']) && !empty($_POST['phone']) && !empty($_POST['email']) && !empty($_POST['comments'])) {
                $insertSQL = $db->prepare("INSERT INTO tbl_partners (partner, contact, title, designation, address, city, state, country, phone, email, comments, created_by, date_created) VALUES (:partner, :contact, :title, :designation, :address, :city, :state, :country, :phone, :email, :comments, :user, :date)");
                $insertSQL->execute(array(':partner' => $_POST['partner'], ':contact' => $_POST['contactperson'], ':title' => $_POST['title'], ':designation' => $_POST['designation'], ':address' => $_POST['address'], ':city' => $_POST['city'],  ':state' => $_POST['state'], ':country' => $_POST['country'], ':phone' => $_POST['phone'], ':email' => $_POST['email'], ':comments' => $_POST['comments'], ':user' => $user_name, ':date' => $current_date));
                $last_id = $db->lastInsertId();
                if ($insertSQL->rowCount() == 1) {
                    $filecategory = "Partners";
                    $catid = $last_id;
                    $myUser = $_POST['user_name'];
                    $count = count($_POST["attachmentpurpose"]);
                    for ($cnt = 0; $cnt < $count; $cnt++) {
                        if (!empty($_FILES['partnerattachment']['name'][$cnt])) {
                            $reason = $_POST["attachmentpurpose"][$cnt];
                            $filename = basename($_FILES['partnerattachment']['name'][$cnt]);
                            $ext = substr($filename, strrpos($filename, '.') + 1);

                            if (($ext != "exe") && ($_FILES["partnerattachment"]["type"][$cnt] != "application/x-msdownload")) {
                                $newname = $catid . "-" . $filename;
                                $filepath = "uploads/partners/" . $newname;
                                if (!file_exists($filepath)) {
                                    if (move_uploaded_file($_FILES['partnerattachment']['tmp_name'][$cnt], $filepath)) {
                                        $qry2 = $db->prepare("INSERT INTO tbl_files (`projstage`, `filename`, `ftype`, `floc`, `fcategory`, `reason`, `uploaded_by`, `date_uploaded`) VALUES (:stage, :fname, :ext, :floc, :fcat, :reason, :user, :date)");
                                        $qry2->execute(array(':stage' => $catid, ':fname' => $newname, ':ext' => $ext, ':floc' => $filepath, ':fcat' => $filecategory, ':reason' => $reason, ':user' => $myUser, ':date' => $current_date));
                                    }
                                } else {
                                    $msg = 'File you are uploading already exists, try another file!!';
                                }
                            } else {
                                $msg = 'This file type is not allowed, try another file!!';
                            }
                        } else {
                            $msg = 'You have not attached any file!!';
                        }
                    }

                    $results =
                        "<script type=\"text/javascript\">
                            swal({
                                title: \"Success!\",
                                text: \" $msg\",
                                type: 'Success',
                                timer: 2000,
                                icon:'success',
                                showConfirmButton: false
                            });
                            setTimeout(function(){
                                window.location.href = 'view-partners.php';
                            }, 2000);
                        </script>";
                }
            }

            echo $results;
        }
    
?>
    <script src="assets/ckeditor/ckeditor.js"></script>

    <!-- start body  -->
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                    <?= $icon ?>
                    <?php echo $pageTitle ?>
                    <div class="btn-group" style="float:right">
                        <div class="btn-group" style="float:right">
                            <a type="button" id="outputItemModalBtnrow" href="./view-partners" class="btn btn-warning pull-right">
                                Go Back
                            </a>
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
                            <form id="add_partner" method="POST" name="addpartnerfrm" action="" enctype="multipart/form-data" autocomplete="off">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">DETAILS</legend>
                                    <div class="col-md-12">
                                        <label>Name *:</label>
                                        <div>
                                            <input name="partner" type="text" class="form-control" placeholder="Enter name of financing institution" style="border:#CCC thin solid; border-radius: 5px" required />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Contact Person *:</label>
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="contactperson" placeholder="Enter contact person name" style="border:#CCC thin solid; border-radius: 5px" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Title *:</label>
                                        <div class="form-line">
                                            <select name="title" class="form-control show-tick" data-live-search="true" style="border:#CCC thin solid; border-radius:5px" required>
                                                <option value="" selected="selected" class="selection">...Select Contact Person Title...</option>
                                                <?php
                                                while ($row_title = $query_title->fetch()) {
                                                ?>
                                                    <option value="<?php echo $row_title['id'] ?>"><?php echo $row_title['title'] ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Designation *:</label>
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="designation" placeholder="Enter contact person designation" style="border:#CCC thin solid; border-radius: 5px" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label>Address *:</label>
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="address" placeholder="Enter the financial institution address" style="border:#CCC thin solid; border-radius: 5px" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label>City *:</label>
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="city" placeholder="Enter the partner city" style="border:#CCC thin solid; border-radius: 5px" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label>State/Province *:</label>
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="state" placeholder="Enter the located state/province/county" style="border:#CCC thin solid; border-radius: 5px" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Partner Country *:</label>
                                        <div class="form-line">
                                            <select name="country" class="form-control show-tick" data-live-search="true" style="border:#CCC thin solid; border-radius:5px" required>
                                                <option value="" selected="selected" class="selection">...Select Partner Country...</option>
                                                <?php
                                                while ($row_country = $query_country->fetch()) {
                                                ?>
                                                    <option value="<?php echo $row_country['id'] ?>"><?php echo $row_country['country'] ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Phone Number *:</label>
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="phone" placeholder="Enter partner phone number" style="border:#CCC thin solid; border-radius: 5px" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Email *:</label>
                                        <div class="form-line">
                                            <input type="email" class="form-control" name="email" placeholder="Enter partner email" style="border:#CCC thin solid; border-radius: 5px" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label>Comments :</label>
                                        <div class="form-line">
                                            <textarea name="comments" cols="45" rows="5" class="txtboxes" id="comments" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"></textarea>
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
                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">ATTACHMENTS</legend>
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
                                                                <input type="file" name="partnerattachment[]" multiple id="partnerattachment[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
                                                            </td>
                                                            <td>
                                                                <input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control" placeholder="Enter the purpose of this document" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                    </table>
                                                    <script type="text/javascript">
                                                        function add_row() {
                                                            $rowno = $("#donation_table tr").length;
                                                            $rowno = $rowno + 1;
                                                            $("#donation_table tr:last").after('<tr id="row' + $rowno + '"><td><input type="file" name="partnerattachment[]" multiple id="partnerattachment[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required></td><td><input type="text" name="attachmentpurpose[]" id="attachmentpurpose[]" class="form-control"  placeholder="Enter the purpose of this document" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"  required></td><td><button type="button" class="btn btn-danger btn-sm"  onclick=delete_row("row' + $rowno + '")><span class="glyphicon glyphicon-minus"></span></button></td></tr>');
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
                                        <div class="btn-group">
                                            <input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" value="Save" />
                                        </div>
                                        <input type="hidden" name="MM_insert" value="addpartnerfrm" />
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

} catch (PDOException $ex) {
    customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}

require('includes/footer.php');
?>