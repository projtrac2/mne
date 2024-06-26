<?php
try {
    require('includes/head.php');
    if ($permission) {
        if (isset($_GET['fn'])) {
            $hash = $_GET['fn'];
            $decode_fndid = base64_decode($hash);
            $fndid_array = explode("fn918273AxZID", $decode_fndid);
            $fn = $fndid_array[1];
        } else {
            header('Location: view-financiers.php');
        }

        $query_financier = $db->prepare("SELECT * FROM tbl_financiers WHERE id=:fn");
        $query_financier->execute(array(":fn" => $fn));
        $row_financier = $query_financier->fetch();
        $typeid = $row_financier['type'];

?>

        <!-- start body  -->
        <section class="content">
            <div class="container-fluid">
                <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                    <h4 class="contentheader">
                        <?= $icon ?>
                        <?= $pageTitle ?>
                        <div class="btn-group" style="float:right">
                            <button onclick="history.go(-1)" class="btn bg-orange waves-effect pull-right" style="margin-right: 10px">
                                Go Back
                            </button>
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
                                    <a href="#" class="btn bg-grey waves-effect" style="margin-top:10px; padding-left:-5px">Financier Details</a>
                                    <a href="view-financier-funds.php?fn=<?php echo $hash; ?>" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-5px">Funds Contributed</a>
                                    <?php
                                    if ($typeid != 1 && $typeid != 2) {
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
                                <form id="add_financier" method="POST" name="addfinancierfrm" action="" enctype="multipart/form-data" autocomplete="off">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">DETAILS</legend>
                                        <div class="col-md-8">
                                            <label>Financier Name :</label>
                                            <div>
                                                <input type="text" class="form-control" value="<?php echo $row_financier['financier']; ?>" readonly style="border:#CCC thin solid; border-radius: 5px" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Financier Type :</label>
                                            <div class="form-line">
                                                <?php
                                                $query_type = $db->prepare("SELECT * FROM tbl_financier_type WHERE id=:typeid");
                                                $query_type->execute(array(":typeid" => $typeid));
                                                $row_type = $query_type->fetch();
                                                $type = $row_type["type"];
                                                ?>
                                                <input type="text" class="form-control" value="<?php echo $type; ?>" readonly style="border:#CCC thin solid; border-radius: 5px" />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Contact Person :</label>
                                            <div class="form-line">
                                                <input type="text" class="form-control" value="<?php echo $row_financier['contact']; ?>" readonly style="border:#CCC thin solid; border-radius: 5px">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Designation :</label>
                                            <div class="form-line">
                                                <input type="text" class="form-control" value="<?php echo $row_financier['designation']; ?>" readonly style="border:#CCC thin solid; border-radius: 5px">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Address :</label>
                                            <div class="form-line">
                                                <input type="text" class="form-control" value="<?php echo $row_financier['address']; ?>" readonly style="border:#CCC thin solid; border-radius: 5px">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label>City :</label>
                                            <div class="form-line">
                                                <input type="text" class="form-control" value="<?php echo $row_financier['city']; ?>" readonly style="border:#CCC thin solid; border-radius: 5px">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label>State/Province :</label>
                                            <div class="form-line">
                                                <input type="text" class="form-control" value="<?php echo $row_financier['state']; ?>" readonly style="border:#CCC thin solid; border-radius: 5px">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Financier Country :</label>
                                            <div class="form-line">
                                                <?php
                                                $cntid = $row_financier['country'];
                                                $query_country = $db->prepare("SELECT * FROM countries WHERE id=:cntid");
                                                $query_country->execute(array(":cntid" => $cntid));
                                                $row_country = $query_country->fetch();
                                                $country = $row_country["country"];
                                                ?>
                                                <input type="text" class="form-control" value="<?php echo $country; ?>" readonly style="border:#CCC thin solid; border-radius: 5px">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Phone Number :</label>
                                            <div class="form-line">
                                                <a href="tel:<?php echo $row_financier['phone']; ?>" class="form-control" style="border:#CCC thin solid; border-radius: 5px"><?php echo $row_financier['phone']; ?></a>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Email :</label>
                                            <div class="form-line">
                                                <a href="mailto: <?php echo $row_financier['email']; ?>" class="form-control" style="border:#CCC thin solid; border-radius: 5px"><?php echo $row_financier['email']; ?></a>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Comments :</label>
                                            <div class="form-line">
                                                <span cols="45" rows="5" class="form-control" style="height:50px; width:100%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"><?php echo strip_tags($row_financier['comments']); ?></span>
                                            </div>
                                        </div>
                                        <?php
                                        $counter = 0;
                                        $projstage = $fn;
                                        $cat = "Financiers";
                                        $query_rsFile = $db->prepare("SELECT * FROM tbl_files WHERE projstage=:projstage and projid IS NULL and fcategory=:cat");
                                        $query_rsFile->execute(array(":projstage" => $projstage, ":cat" => $cat));
                                        $row_rsFile = $query_rsFile->fetch();
                                        $totalRows_rsFile = $query_rsFile->rowCount();
                                        if ($totalRows_rsFile > 0) {
                                        ?>

                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:10px">
                                                <div class="row clearfix ">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="card">
                                                            <div class="header bg-grey">
                                                                <strong> Financier Files </strong>
                                                            </div>
                                                            <div class="body">
                                                                <div class="body table-responsive">
                                                                    <table class="table table-bordered" style="width:100%">
                                                                        <thead>
                                                                            <tr>
                                                                                <th style="width:2%">#</th>
                                                                                <th style="width:40%">File Name</th>
                                                                                <th style="width:10%">File Type</th>
                                                                                <th style="width:38%">Purpose</th>
                                                                                <th style="width:10%">Action</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                            do {
                                                                                $pdfname = $row_rsFile['filename'];
                                                                                $type = $row_rsFile['ftype'];
                                                                                $filepath = $row_rsFile['floc'];
                                                                                $attachmentPurpose = $row_rsFile['reason'];
                                                                                $action =  '<a href="' . $filepath . '" style="color:#06C; padding-left:2px; padding-right:2px; font-weight:bold" title="Download File" target="new"><i class="fa fa-cloud-download fa-2x" aria-hidden="true"></i></a>';

                                                                                $counter++;
                                                                                echo '<tr>
                                                                                <td>
                                                                                    ' . $counter . '
                                                                                </td>
                                                                                <td>
                                                                                ' . $pdfname . '
                                                                                </td>
                                                                                <td>
                                                                                ' . $type . '
                                                                                </td>
                                                                                <td>
                                                                                ' . $attachmentPurpose . '
                                                                                </td>
                                                                                <td align="center">
                                                                                ' . $action . '
                                                                                </td>
                                                                            </tr>';
                                                                            } while ($row_rsFile = $query_rsFile->fetch());
                                                                            ?>
                                                                            <script type="text/javascript">
                                                                                $(document).ready(function() {
                                                                                    $(".fancybox").fancybox({
                                                                                        openEffect: "none",
                                                                                        closeEffect: "none"
                                                                                    });
                                                                                });
                                                                            </script>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php
                                        }
                                        ?>
                                    </fieldset>
                                </form>
                                <!-- end body -->
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