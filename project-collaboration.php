<?php
require('includes/head.php');
if ($permission) {
    try {
        $decode_projid = (isset($_GET['projid']) && !empty($_GET["projid"])) ? base64_decode($_GET['projid']) : "";
        $projid_array = explode("projid54321", $decode_projid);
        $projid = $projid_array[1];

        if (isset($_POST['send'])) {
            $projid = $_POST['projid'];
            $comments = $_POST['comments'];
            $file_path = $file_type = '';
            if (!empty($_FILES['file']['name'])) {
                $filename = basename($_FILES['file']['name']);
                $file_type = substr($filename, strrpos($filename, '.') + 1);
                if (($file_type != "exe") && ($_FILES["file"]["type"] != "application/x-msdownload")) {
                    $newname = time() . "_" . $filename;
                    $file_path = "uploads/discussions/" . $newname;
                    if (!file_exists($file_path)) {
                        if (!move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
                            $message = "Error";
                        }
                    }
                }
            }

            $sql = $db->prepare("INSERT INTO tbl_project_discussions (projid,message,file_path,file_type,created_by) VALUES(:projid,:message,:file_path,:file_type,:created_by)");
            $sql->execute(array(":projid" => $projid, ":message" => $comments, ":file_path" => $file_path, ":file_type" => $file_type, ":created_by" => $user_name));
            $projid_hashed = base64_encode("projid54321{$projid}");
            $redirect_url = "project-discussion.php?projid=" . $projid_hashed;
            $msg = 'Project Successfully Added';
            $results = "<script type=\"text/javascript\">
				swal({
					title: \"Success!\",
					text: \" $msg\",
					type: 'Success',
					timer: 2000,
					'icon':'success',
				showConfirmButton: false });
				setTimeout(function(){
					window.location.href = '$redirect_url';
				}, 2000);
			</script>";
        }

        //get active module
        $query_discussion = $db->prepare("SELECT * FROM `tbl_project_discussion_topics` d INNER JOIN users u  ON u.userid=d.created_by INNER JOIN tbl_projteam2 p  ON u.pt_id = p.ptid WHERE projid = :projid ORDER BY id ASC");
        $query_discussion->execute(array(':projid' => $projid));
        $totalRows_issuediscussion = $query_discussion->rowCount();
    } catch (PDOException $ex) {
        $result = flashMessage("An error occurred: " . $ex->getMessage());
        print($result);
    }
?>
    <link rel="stylesheet" href="assets/css/discussion.css">
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; padding-right:15px; color:#FFF">
                <h4 class="contentheader">
                    <?= $icon . ' ' . $pageTitle ?>
                    <div class="btn-group" style="float:right">
                        <button type="button" data-toggle="modal" data-target="#addFormModal" data-backdrop="static" data-keyboard="false" class="btn btn-success btn-sm" style="float:right; margin-top:-5px">
                            <span class="glyphicon glyphicon-plus"></span> Add Subject
                        </button>
                        <a type="button" id="outputItemModalBtnrow" href="project-output-monitoring-checklist.php" class="btn btn-warning pull-right" style="margin-right:10px; margin-top:-5px">
                            Go Back
                        </a>
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
                            <div id="right_panel">
                                <div class="wrap-table">
                                    <table style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th width="10%" scope="col" class="sticky-col">Author</th>
                                                <th width="60%" scope="col" class="sticky-col">Subject</th>
                                                <th width="10%" scope="col" class="sticky-col">Contributors</th>
                                                <th width="10%" scope="col">Comments</th>
                                                <th width="10%" scope="col" class="sticky-col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($totalRows_issuediscussion > 0) {
                                                while ($row_issuediscussion = $query_discussion->fetch()) {
                                                    $poster = $row_issuediscussion["title"] . "." . $row_issuediscussion["fullname"];
                                                    $subject = $row_issuediscussion['topic'];
                                                    $topic_id = $row_issuediscussion['id'];
                                                    $created_at = $row_issuediscussion['created_at'];
                                                    $topic_id_hashed = base64_encode("projid54321{$topic_id}");


                                                    $query_rsUsers = $db->prepare("SELECT * FROM `tbl_project_discussions` WHERE topic_id = :topic_id GROUP BY created_by ");
                                                    $query_rsUsers->execute(array(':topic_id' => $topic_id));
                                                    $totalRows_rsUsers = $query_rsUsers->rowCount();

                                                    $query_discussions = $db->prepare("SELECT * FROM `tbl_project_discussions` d INNER JOIN users u  ON u.userid=d.created_by INNER JOIN tbl_projteam2 p  ON u.pt_id = p.ptid WHERE topic_id = :topic_id ORDER BY id ASC");
                                                    $query_discussions->execute(array(':topic_id' => $topic_id));
                                                    $totalRows_issuediscussions = $query_discussions->rowCount();
                                            ?>
                                                    <tr>
                                                        <td>
                                                            <img class="user__photo" src="<?= $avatar ?>" alt="">
                                                        </td>
                                                        <td class="sticky-col" scope="row" data-label="Customer">
                                                            <a href="project-discussion.php?topic=<?= $topic_id_hashed ?>" class="user__info">
                                                                <label>
                                                                    <span>
                                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                        </svg>
                                                                    </span>
                                                                </label>
                                                                <div>
                                                                    <div class="user__name"><?= $subject ?></div>
                                                                    <div class="user__email"><?= $created_at ?></div>
                                                                </div>
                                                            </a>
                                                        </td>
                                                        <td data-label="Enrolled" align="center">
                                                            <i class="fa fa-users" aria-hidden="true"></i> <?= $totalRows_rsUsers ?>
                                                        </td>
                                                        <td data-label="Enrolled" align="center">
                                                            <i class="fa fa-comments-o" aria-hidden="true"></i> <?= $totalRows_issuediscussions ?>
                                                        </td>
                                                        <td data-label="Enrolled">
                                                            <a href="project-discussion.php?topic=<?= $topic_id_hashed ?>" type="button" class="btn btn-info">
                                                                Add Your Comment
                                                            </a>
                                                        </td>
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
    </section>
    <!-- end body  -->
    <!-- add item -->
    <div class="modal fade" id="addFormModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="form-horizontal" id="modal_form_submit" action="" method="POST" enctype="multipart/form-data">
                    <?= csrf_token_html(); ?>
                    <div class="modal-header" style="background-color:#03A9F4">
                        <h4 class="modal-title" style="color:#fff" align="center" id="addModal"><i class="fa fa-plus"></i> <span id="modal_info">Add New Discussion Subject</span></h4>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="body" id="add_modal_form">
                                        <div id="budget_line">
                                            <div class="row clearfix" style="margin-top:5px; margin-bottom:5px">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label for="projduration">Subject *:</label>
                                                    <div class="form-input">
                                                        <input type="text" name="subject" min="0" value="" id="subject" placeholder="Enter your subject" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- /modal-body -->
                        <div class="modal-footer">
                            <div class="col-md-12 text-center">
                                <input type="hidden" name="projid" id="projid" value="<?= $projid ?>">
                                <input type="hidden" name="store_topic" id="store_topic" value="store_topic">
                                <button name="save" type="" class="btn btn-primary waves-effect waves-light" id="modal-form-submit" value=""> Save </button>
                                <button type="button" class="btn btn-warning waves-effect waves-light" data-dismiss="modal"> Cancel</button>
                            </div>
                        </div> <!-- /modal-footer -->
                </form> <!-- /.form -->
            </div> <!-- /modal-content -->
        </div> <!-- /modal-dailog -->
    </div>
    <!-- End add item -->
<?php
} else {
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');
?>

<script>
    var ajax_url = "ajax/monitoring/discussion";
    $(document).ready(function() {
        $("#modal_form_submit").submit(function(e) {
            e.preventDefault();
            $("#tag-form-submit").prop("disabled", true);
            var data = $(this)[0];
            var form = new FormData(data);
            $.ajax({
                type: "post",
                url: ajax_url,
                data: form,
                processData: false,
                contentType: false,
                cache: false,
                timeout: 600000,
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        success_alert("Success!");
                    } else {
                        sweet_alert("Error!");
                    }
                    $("#tag-form-submit").prop("disabled", false);
                    setTimeout(() => {
                        window.location.reload(true);
                    }, 3000);
                }
            });
        });
    });

    const BORDER_SIZE = 14;
    const panel = document.getElementById("right_panel");

    let m_pos;

    function resize(e) {
        const dx = m_pos - e.x;
        m_pos = e.x;
        panel.style.width = (parseInt(getComputedStyle(panel, '').width) - dx) + "px";
    }

    panel.addEventListener("mousedown", function(e) {
        if (e.offsetX < BORDER_SIZE) {
            m_pos = e.x;
            document.addEventListener("mousemove", resize, false);
        }
    }, false);

    document.addEventListener("mouseup", function() {
        document.removeEventListener("mousemove", resize, false);
    }, false);
</script>