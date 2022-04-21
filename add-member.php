<?php
$pageName = "Strategic Plans";
$replacement_array = array(
    'planlabel' => "CIDP",
    'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');
$pageTitle = $planlabelplural;

if ($permission) {
    function create_password($str_length)
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < $str_length; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }


    function sendMail($fullname, $email, $password)
    {
        global $db;
        $query_url =  $db->prepare("SELECT * FROM tbl_company_settings");
        $query_url->execute();
        $row_url = $query_url->fetch();
        $url = $row_url["main_url"];
        $org = $row_url["company_name"];
        $org_email = $row_url["email_address"];
        $receipient = $email;

        $detailslink = '<a href="' . $url . 'login.php" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">Login</a>';
        $mainmessage = ' Dear ' . $fullname . ',
            <p>Use the following details to login in to the system:</p>
            <p>Email:' . $email . '<br>
                Password: ' . $password . '<br> 
            <p>Prepare the required resources. </p>';

        $subject = "Login";
        $receipientName = $fullname;
        $title = "";

        include("assets/processor/baseline-email-body.php");
        require 'PHPMailer/PHPMailerAutoload.php';
        include("email-conf-settings.php");
    }


    function alert_message($title, $msg, $type, $icon)
    {
        return "<script type=\"text/javascript\">
                    swal({
                        title: '$title',
                        text: '$msg',
                        type: '$type',
                        timer: 5000,
                        icon:'$icon',
                        showConfirmButton: false 
                    });
                    setTimeout(function(){
                        window.location.href = 'view-members.php';
                    }, 3000);
                </script>";
    }

    try {
        $editFormAction = $_SERVER['PHP_SELF'];
        if (isset($_SERVER['QUERY_STRING'])) {
            $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
        }
        // email conif-settings 
        $currentPage = $_SERVER["PHP_SELF"];
        if (isset($_GET["ptid"]) && !empty($_GET["ptid"])) {
            $ptid = $_GET["ptid"];
            if (isset($_GET["action"]) && $_GET["action"] == '2') {
                $query_rsAdmin = $db->prepare("SELECT * FROM tbl_projcountyadmin WHERE ptid='$ptid'");
                $query_rsAdmin->execute();
                $row_rsAdmin = $query_rsAdmin->fetch();
            } elseif (isset($_GET["action"]) && $_GET["action"] == '3') {
                $delete_rsAdmin = $db->prepare("DELETE FROM tbl_projcountyadmin WHERE ptid='$ptid'");
                $delete_rsAdmin->execute();
            }
        }

        $userid = "";
        if (isset($_GET['ptid'])) {
            $userid = $_GET['ptid'];
            $mbraction = "Edit";
            $formname = "MM_update";
            $formvalue = "editmemberfrm";
        } else {
            $mbraction = "Add New";
            $formname = "MM_insert";
            $formvalue = "addmemberfrm";
        }



        if (isset($_POST["submit"]) && $_POST["MM_insert"] == "addmemberfrm") {
            if (!empty($_POST['title']) && !empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['email']) && !empty($_POST['phone']) && !empty($_POST['username'])) {

                $existusername = $_POST['username'];
                $existemail = $_POST['email'];

                $query_rsExistMember = $db->prepare("SELECT * FROM tbl_projteam2 WHERE email = '$existemail'");
                $query_rsExistMember->execute();
                $totalRows_rsExistMember = $query_rsExistMember->rowCount();

                if ($totalRows_rsExistMember > 0) {
                    $msg = 'Sorry, this member already exists. Please use unique username and email address';
                    $results = alert_message('Warning', $msg, 'Warning', 'warning');
                } else {
                    if ($_FILES['photofile']['size'] >= 1048576 * 500) {
                        $msg = 'The file selected exceeds 500MB size limit';
                        $results = alert_message('Warning', $msg, 'Warning', 'warning');
                    } else {
                        //upload random name/number
                        $rd2 = mt_rand(1000, 9999) . "_File";

                        //Check that we have a file
                        if (!empty($_FILES["photofile"])) {
                            //Check if the file is JPEG image and it's size is less than 350Kb
                            $filename = basename($_FILES['photofile']['name']);

                            $ftype = substr($filename, strrpos($filename, '.') + 1);

                            if (($ftype != "exe") && ($_FILES["photofile"]["type"] != "application/x-msdownload")) {
                                //Determine the path to which we want to save this file      
                                $newname = $rd2 . "_" . $filename;
                                $floc = "uploads/staff/" . $newname;
                                //Check if the file with the same name already exists in the server
                                if (!file_exists($floc)) {
                                    //Attempt to move the uploaded file to it's new place
                                    move_uploaded_file($_FILES['photofile']['tmp_name'], $floc);
                                } else {
                                    $msg = 'Selected file exists';
                                    $results = alert_message('Warning', $msg, 'Warning', 'warning');
                                }
                            } else {
                                $msg = 'Selected file type not allowed';
                                $results = alert_message('Warning', $msg, 'Warning', 'warning');
                            }
                        }
                    }

                    $fullname = $_POST['firstname'] . " " . $_POST['lastname'];

                    if (empty($_POST['conservancy']) || $_POST['conservancy'] == '') {
                        $level1 = 0;
                    } else {
                        $level1 = $_POST['conservancy'];
                    }

                    if (empty($_POST['ecosystem']) || $_POST['ecosystem'] == '') {
                        $level2 = 0;
                    } else {
                        $level2 = $_POST['ecosystem'];
                    }

                    if (empty($_POST['station']) || $_POST['station'] == '') {
                        $level3 = 0;
                    } else {
                        $level3 = $_POST['station'];
                    }

                    $department = isset($_POST['department']) && !empty($_POST['department']) ? $_POST['department'] : 0;
                    $ministry = isset($_POST['ministry']) && !empty($_POST['ministry']) ? $_POST['ministry'] : 0;

                    // $createdby = $_POST['user_id'];
                    $createdby = 4;
                    $insertSQL = $db->prepare("INSERT INTO tbl_projteam2 (fullname, firstname, middlename, lastname, title, designation,ministry, department, levelA, levelB, levelC, floc, filename, ftype, email, phone, createdby, datecreated)  VALUES( :fullname, :firstname, :middlename, :lastname, :title, :designation,:ministry,:department, :level1, :level2, :level3,:floc, :filename, :ftype, :email, :phone, :createdby, :datecreated)");
                    $Rest = $insertSQL->execute(array(":fullname" => $fullname, ":firstname" => $_POST['firstname'], ":middlename" => $_POST['middlename'], ":lastname" => $_POST['lastname'], ":title" => $_POST['title'], ":designation" => $_POST['designation'], ":ministry" => $ministry, ":department" => $department, ":level1" => $level1, ":level2" => $level2, ":level3" => $level3, ":floc" => $floc, ":filename" => $newname, ":ftype" => $ftype, ":email" => $_POST['email'], ":phone" => $_POST['phone'], ":createdby" => $createdby, ":datecreated" => date('Y-m-d')));


                    if ($Rest) {
                        $last_id = $db->lastInsertId();
                        $type = 1;
                        $password = create_password(8);
                        $hash_pass = password_hash($password, PASSWORD_DEFAULT);
                        $email = $_POST['email'];

                        $insertSQL = $db->prepare("INSERT INTO `users` (pt_id, username,email, password, type) VALUES( :ptid, :username,:email, :password, :type)");
                        $insertSQL->execute(array(":ptid" => $last_id, ":username" => $_POST['username'],":email"=> $email, ":password" => $hash_pass, ":type" => $type));
                        sendMail($fullname, $email, $password);

                        $msg = 'You have successfully registered ' . $fullname . ' as an administrator.';
                        $results = alert_message('Success', $msg, 'Success', 'success');
                    } else {
                        $msg = 'Can not add administrator, please review your info and try again.';
                        $results = alert_message('Warning', $msg, 'Warning', 'warning');
                    }
                }
            } else {
                $msg = 'Some fields have not been filled.';
                $results = alert_message('Warning', $msg, 'Warning', 'warning');
            }
        } elseif (isset($_POST["submit"]) && $_POST["MM_update"] == "editmemberfrm") {
            //Check that we have a file
            $myphoto = $ftype = $filename = $level = '';
            if ($_FILES['photofile']['size'] != 0) {
                if ($_FILES['photofile']['size'] >= 1048576 * 500) {
                    $msg = 'File selected exceeds 500MB size limit';
                    $results = alert_message('Warning', $msg, 'Warning', 'warning');
                } else {
                    //upload random name/number
                    $rd2 = mt_rand(1000, 9999) . "_File";

                    //Check if the file is JPEG image and it's size is less than 350Kb
                    $filename = basename($_FILES['photofile']['name']);

                    $ftype = substr($filename, strrpos($filename, '.') + 1);

                    if (($ftype != "exe") && ($_FILES["photofile"]["type"] != "application/x-msdownload")) {
                        //Determine the path to which we want to save this file      
                        $newname = $rd2 . "_" . $filename;
                        $floc = "uploads/staff/" . $newname;
                        //Check if the file with the same name already exists in the server
                        if (!file_exists($floc)) {
                            //Attempt to move the uploaded file to it's new place
                            if (move_uploaded_file($_FILES['photofile']['tmp_name'], $floc)) {
                                $myphoto = $floc;
                            }
                        } else {
                            $msg = 'The selected file already exists';
                            $results = alert_message('Warning', $msg, 'Warning', 'warning');
                        }
                    } else {
                        $msg = 'Selected file type not allowed';
                        $results = alert_message('Warning', $msg, 'Warning', 'warning');
                    }
                }
            } else {
                $query_rsPhoto = $db->prepare("SELECT * FROM tbl_projteam2 WHERE ptid='$userid'");
                $query_rsPhoto->execute();
                $row_rsPhoto = $query_rsPhoto->fetch();
                $totalRows_rsPhoto = $query_rsPhoto->rowCount();
                $myphoto = $row_rsPhoto['floc'];
                $ftype = $row_rsPhoto['ftype'];
                $filename = $row_rsPhoto['filename'];
                $level = $row_rsPhoto['level'];
            }

            $username = $_POST['username'];
            $title = $_POST['title'];
            $firstname = $_POST['firstname'];
            $middlename = $_POST['middlename'];
            $lastname = $_POST['lastname'];
            $fullname = $firstname . " " . $lastname;
            $designation = $_POST['designation'];
            $department = isset($_POST['department']) && !empty($_POST['department']) ? $_POST['department'] : 0;
            $ministry = isset($_POST['ministry']) && !empty($_POST['ministry']) ? $_POST['ministry'] : 0;
            $floc = $myphoto;
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $username = $_POST['username'];
            $level = isset($_POST['level']) ? $_POST['level'] : 0;

            $queryupdate = $db->prepare("UPDATE tbl_projteam2 SET fullname=:fullname, firstname=:firstname, middlename=:middlename, lastname=:lastname, title=:title, designation=:designation,ministry=:ministry, department=:department, floc=:floc, filename=:filename, ftype=:ftype, level=:level, email=:email, phone=:phone WHERE ptid=:ptid");
            $retval = $queryupdate->execute(array(":fullname" => $fullname, ":firstname" => $firstname, ":middlename" => $middlename, ":lastname" => $lastname, ":title" => $title, ":designation" => $designation, ":ministry" => $ministry, ":department" => $department, ":floc" => $floc, ":filename" => $filename, ":ftype" => $ftype, ":level" => $level, ":email" => $_POST['email'], ":phone" => $_POST['phone'], ":ptid" => $userid));

            if ($retval) {
                if (isset($username) && !empty($username)) {
                    $query_rsExistMember = $db->prepare("SELECT * FROM tbl_users WHERE pt_id = '$userid'");
                    $query_rsExistMember->execute();
                    $totalRows_rsExistMember = $query_rsExistMember->rowCount();

                    if ($totalRows_rsExistMember > 0) {
                        $queryupdate = $db->prepare("UPDATE tbl_users SET username=:username, email=:email level=:level WHERE pt_id=:ptid");
                        $queryupdate->execute(array(":username" => $username, ":email"=> $email, ":level" => $level, ":ptid" => $userid));
                    } else {
                        $insertSQL = $db->prepare("INSERT INTO `users` (pt_id, username, email, type) VALUES(:ptid, :username,:email, :type)");
                        $insertSQL->execute(array(":ptid" => $userid, ":username" => $_POST['username'],":email"=> $email, ":type" => $type));
                    }
                }
                $msg = 'User info successfully updated.';
                $results = alert_message("Success", $msg, "Success", "success");
            } else {
                $msg = 'User details was not updated. Please confirm the information provided';
                $results = alert_message('Error', $msg, 'Error', 'error');
            }
        }

        $query_rsTitle =  $db->prepare("SELECT id,title FROM tbl_mbrtitle");
        $query_rsTitle->execute();
        $row_rsTitle = $query_rsTitle->fetch();

        $query_rsPMDesignation =  $db->prepare("SELECT * FROM tbl_pmdesignation ORDER BY moid ASC");
        $query_rsPMDesignation->execute();
        $row_rsPMDesignation = $query_rsPMDesignation->fetch();

        $query_rsSector =  $db->prepare("SELECT * FROM tbl_sectors WHERE parent=0 and deleted='0'");
        $query_rsSector->execute();
        $row_rsSector = $query_rsSector->fetch();

        $query_country =  $db->prepare("SELECT id,country FROM countries");
        $query_country->execute();

        $query_rsPTeam = $db->prepare("SELECT *, CONCAT(`lastname`,', ',`firstname`,' ', '(',`title`,')') AS fullnames FROM tbl_projteam2 WHERE ptid = '$userid'");
        $query_rsPTeam->execute();
        $row_rsPTeam = $query_rsPTeam->fetch();
        $totalRows_rsPTeam = $query_rsPTeam->rowCount();


        $query_rsUser = $db->prepare("SELECT * FROM tbl_users WHERE pt_id = '$userid'");
        $query_rsUser->execute();
        $row_rsUser = $query_rsUser->fetch();
        $totalRows_rsUser = $query_rsUser->rowCount();


        $query_rsPMLevel = $db->prepare("SELECT * FROM tbl_level ORDER BY level_id ASC");
        $query_rsPMLevel->execute();
        $row_rsPMLevel = $query_rsPMLevel->fetch();
        $totalRows_rsPMLevel = $query_rsPMLevel->rowCount();

        $query_rsTitle = $db->prepare("SELECT * FROM tbl_mbrtitle ORDER BY id ASC");
        $query_rsTitle->execute();
        $row_rsTitle = $query_rsTitle->fetch();
        $totalRows_rsTitle = $query_rsTitle->rowCount();

        $query_rsLocation = $db->prepare("SELECT id,state FROM tbl_state WHERE location=0 and parent IS NULL");
        $query_rsLocation->execute();
        $row_rsLocation = $query_rsLocation->fetch();
        $totalRows_rsLocation = $query_rsLocation->rowCount();

        $query_rsDesignation = $db->prepare("SELECT moid, designation FROM tbl_pmdesignation");
        $query_rsDesignation->execute();
        $row_rsDesignation = $query_rsDesignation->fetch();
        $totalRows_rsDesignation = $query_rsDesignation->rowCount();
        $profid = 1;
    } catch (PDOException $ex) {
        $result = flashMessage("An error occurred: " . $ex->getMessage());
        echo $result;
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
                            <?php
                            if (!isset($_GET["ptid"])) {
                            ?>
                                <form role="form" id="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                                    <div class="alert alert-warning" align="center"><strong>Please NOTE THAT username and email must be unique!</strong></div>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">MEMBER DETAILS</legend>
                                        <div class="col-md-2">
                                            <label for="Title">Title *:</label>
                                            <div class="form-line">
                                                <select name="title" id="title" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                                                    <option value="">... Select Title ...</option>
                                                    <?php
                                                    do {
                                                    ?>
                                                        <option value="<?php echo $row_rsTitle['title'] ?>"><?php echo $row_rsTitle['title'] ?></option>
                                                    <?php
                                                    } while ($row_rsTitle = $query_rsTitle->fetch());
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="Title">First Name *:</label>
                                            <div class="form-line">
                                                <input name="firstname" type="text" id="firstname" class="form-control" style="border:#CCC thin solid; border-radius: 5px" placeholder="Enter First Name" required />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="Title">Middle Name:</label>
                                            <div class="form-line">
                                                <input name="middlename" type="text" id="middlename" class="form-control" style="border:#CCC thin solid; border-radius: 5px" placeholder="Enter Middle Name" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="Title">Last Name *:</label>
                                            <div class="form-line">
                                                <input name="lastname" type="text" id="lastname" class="form-control" style="border:#CCC thin solid; border-radius: 5px" placeholder="Enter Last Name" required />
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="Title">Username *:</label>
                                            <div class="form-line">
                                                <input name="username" type="text" id="username" class="form-control" style="border:#CCC thin solid; border-radius: 5px" placeholder="Enter Username" required />
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="Title">Email *:</label>
                                            <div class="form-line">
                                                <input name="email" type="email" id="email" class="form-control" style="border:#CCC thin solid; border-radius: 5px" placeholder="Enter Email Address" required />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="Title">Phone *:</label>
                                            <div class="form-line">
                                                <input name="phone" type="text" id="phone" class="form-control" style="border:#CCC thin solid; border-radius: 5px" placeholder="Enter Phone No." required />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="Title">Attach Passport Photo *:</label>
                                            <div class="form-line">
                                                <input type="file" name="photofile" id="photofile" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <table class="table table-bordered table-striped table-hover js-basic-example table-responsive" id="item_table" style="width:100%">
                                                <tr>
                                                    <th style="width:30%">Designation</th>
                                                    <th style="width:40%" class="showministry"><?= $ministrylabel ?></th>
                                                    <th style="width:30%" class="showdepartment"><?= $departmentlabel ?></th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <select name="designation" id="designation" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                                                            <option value="">.... Select Designation ....</option>
                                                            <?php
                                                            do {
                                                            ?>
                                                                <option value="<?php echo $row_rsPMDesignation['moid'] ?>"><?php echo $row_rsPMDesignation['designation'] ?></option>
                                                            <?php
                                                            } while ($row_rsPMDesignation = $query_rsPMDesignation->fetch());
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td class="showministry">
                                                        <select name="ministry" id="ministry" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true">
                                                            <option value="">.... Select <?= $ministrylabel ?> ....</option>
                                                            <?php
                                                            do {
                                                            ?>
                                                                <option value="<?php echo $row_rsSector['stid'] ?>"><?php echo $row_rsSector['sector'] ?></option>
                                                            <?php
                                                            } while ($row_rsSector = $query_rsSector->fetch());
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td class="showdepartment">
                                                        <div class="form-line">
                                                            <select name="department" id="department" class="form-control" style="border:#CCC thin solid; border-radius:5px" data-live-search="true">
                                                                <option value="">....Select <?= $ministrylabel ?> First....</option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                    </fieldset>
                                    <fieldset class="scheduler-border">
                                        <div class="col-md-12" align="center">
                                            <div class="form-line" align="center" style="padding-top:15px">
                                                <input name="user_name" type="hidden" id="user_name" value="<?php echo $row_rsAdm['username']; ?>" />
                                                <input name="user_id" type="hidden" id="user_id" value="<?php echo $row_rsAdm['adm_id']; ?>" />
                                                <input name="submit" type="submit" class="btn btn-success" id="submit" value="<?php echo $mbraction . " Member"; ?>" />
                                                <input type="hidden" name="<?php echo $formname ?>" value="<?php echo $formvalue; ?>" />
                                            </div>
                                        </div>
                                    </fieldset>
                                </form>
                            <?php
                            } elseif (isset($_GET["ptid"]) && (!empty($_GET["ptid"]) || $_GET["ptid"] != '')) {
                            ?>

                                <form role="form" id="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                                    <div class="alert alert-warning" align="center"><strong>Please NOTE THAT username and email must be unique!<?php echo $profid; ?></strong></div>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">MEMBER DETAILS</legend>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="Title">Name: <?php echo $row_rsPTeam['fullnames']; ?></label>
                                            </div>
                                            <div class="col-md-5">
                                            </div>
                                            <div class="col-md-3" align="right">
                                                <div class="form-line">
                                                    <img src="<?php echo $row_rsPTeam['floc']; ?>" id="mbr" class="img img-rounded" width="120" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="Title">Title *:</label>
                                            <div class="form-line">
                                                <select name="title" id="title" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                                                    <option value="">... Select Title ...</option>
                                                    <?php
                                                    do {
                                                    ?>
                                                        <option value="<?php echo $row_rsTitle['title'] ?>" <?php
                                                                                                            if (!(strcmp($row_rsPTeam['title'], $row_rsTitle['title']))) {
                                                                                                                echo "selected=\"selected\"";
                                                                                                            } ?>>
                                                            <?php echo $row_rsTitle['title'] ?></option>
                                                    <?php
                                                    } while ($row_rsTitle = $query_rsTitle->fetch());
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="Title">First Name *:</label>
                                            <div class="form-line">
                                                <input name="firstname" value="<?php echo $row_rsPTeam['firstname']; ?>" type="text" id="firstname" class="form-control" style="border:#CCC thin solid; border-radius: 5px" placeholder="Enter First Name" required />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="Title">Middle Name:</label>
                                            <div class="form-line">
                                                <input name="middlename" value="<?php echo $row_rsPTeam['middlename']; ?>" type="text" id="middlename" class="form-control" style="border:#CCC thin solid; border-radius: 5px" placeholder="Enter Middle Name" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="Title">Last Name *:</label>
                                            <div class="form-line">
                                                <input name="lastname" value="<?php echo $row_rsPTeam['lastname']; ?>" type="text" id="lastname" class="form-control" style="border:#CCC thin solid; border-radius: 5px" placeholder="Enter Last Name" required />
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="Title">Username *:</label>
                                            <div class="form-line">
                                                <input name="username" value="<?php echo $row_rsUser['username']; ?>" type="text" id="username" class="form-control" style="border:#CCC thin solid; border-radius: 5px" placeholder="Enter Username" required />
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="Title">Email *:</label>
                                            <div class="form-line">
                                                <input name="email" value="<?php echo $row_rsPTeam['email']; ?>" type="email" id="email" class="form-control" style="border:#CCC thin solid; border-radius: 5px" placeholder="Enter Email Address" required />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="Title">Phone *:</label>
                                            <div class="form-line">
                                                <input name="phone" value="<?php echo $row_rsPTeam['phone']; ?>" type="text" id="phone" class="form-control" style="border:#CCC thin solid; border-radius: 5px" placeholder="Enter Phone No." required />
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <table class="table table-bordered table-striped table-hover js-basic-example table-responsive" id="item_table" style="width:100%">
                                                <tr>
                                                    <th style="width:30%">Designation</th>
                                                    <th style="width:40%" class="showministry"><?= $ministrylabel ?></th>
                                                    <th style="width:30%" class="showdepartment"><?= $departmentlabel ?></th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <select name="designation" id="designation" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                                                            <option value="">.... Select Designation ....</option>
                                                            <?php
                                                            do {
                                                            ?>
                                                                <option value="<?php echo $row_rsPMDesignation['moid'] ?>" <?php if (!(strcmp($row_rsPTeam['designation'], $row_rsPMDesignation['moid']))) {
                                                                                                                                echo "selected=\"selected\"";
                                                                                                                            } ?>><?php echo $row_rsPMDesignation['designation'] ?></option>
                                                            <?php
                                                            } while ($row_rsPMDesignation = $query_rsPMDesignation->fetch());
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td class="showministry">
                                                        <select name="ministry" id="ministry" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true">
                                                            <option value="">.... Select <?= $ministrylabel ?> ....</option>
                                                            <?php
                                                            do {
                                                            ?>
                                                                <option value="<?php echo $row_rsSector['stid'] ?>" <?php
                                                                                                                    if (!(strcmp($row_rsPTeam["ministry"], $row_rsSector['stid']))) {
                                                                                                                        echo "selected=\"selected\"";
                                                                                                                    }
                                                                                                                    ?>>
                                                                    <?php echo $row_rsSector['sector'] ?></option>
                                                            <?php
                                                            } while ($row_rsSector = $query_rsSector->fetch());
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td class="showdepartment">
                                                        <div class="form-line">
                                                            <select name="department" id="department" class="form-control" style="border:#CCC thin solid; border-radius:5px" data-live-search="true">
                                                                <?php
                                                                $stid = $row_rsPTeam['ministry'];
                                                                if (!empty($stid)) {
                                                                    $query_rsDepartment =  $db->prepare("SELECT * FROM tbl_sectors WHERE parent=$stid and deleted='0'");
                                                                    $query_rsDepartment->execute();
                                                                    $row_rsDepartment = $query_rsDepartment->fetch();
                                                                ?>
                                                                    <option value="">....Select..<?php echo $department; ?></option>
                                                                    <?php
                                                                    do {
                                                                    ?>
                                                                        <option value="<?php echo $row_rsDepartment['stid'] ?>" <?php
                                                                                                                                if (!(strcmp($row_rsPTeam["department"], $row_rsDepartment['stid']))) {
                                                                                                                                    echo "selected=\"selected\"";
                                                                                                                                }
                                                                                                                                ?>>
                                                                            <?php echo $row_rsDepartment['sector'] ?></option>
                                                                    <?php
                                                                    } while ($row_rsDepartment = $query_rsDepartment->fetch());
                                                                } else {
                                                                    ?>
                                                                    <option value="">....Select<?= $ministrylabel ?> First....<?php echo $department; ?></option>
                                                                <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-8">
                                            <label for="Title">Attach Passport Photo *:</label>
                                            <div class="form-line">
                                                <input type="file" name="photofile" id="photofile" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset class="scheduler-border">
                                        <div class="col-md-12" align="center">
                                            <div class="form-line" align="center" style="padding-top:15px">
                                                <input name="user_name" type="hidden" id="user_name" value="<?php echo $row_rsAdm['username']; ?>" />
                                                <input name="user_id" type="hidden" id="user_id" value="<?php echo $row_rsAdm['adm_id']; ?>" />
                                                <input name="submit" type="submit" class="btn btn-success" id="submit" value="<?php echo $mbraction . " Member"; ?>" />
                                                <input type="hidden" name="<?php echo $formname ?>" value="<?php echo $formvalue; ?>" />
                                            </div>
                                        </div>
                                    </fieldset>
                                </form>
                            <?php
                            }
                            ?>

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
<script>
    <?php
    $level = 0;
    if (isset($_GET["ptid"]) && !empty($_GET["ptid"])) {
        $moid = $row_rsPTeam['designation'];
        $query_rsPMDesignation =  $db->prepare("SELECT * FROM tbl_pmdesignation WHERE moid= '$moid' ");
        $query_rsPMDesignation->execute();
        $row_rsPMDesignation = $query_rsPMDesignation->fetch();
        $count_rsPMDesignation = $query_rsPMDesignation->rowCount();
        $level = $count_rsPMDesignation > 0 ? $row_rsPMDesignation['level'] : 0;
    }
    if ($level == 1) {
    ?>
        level1();
    <?php
    } else if ($level == 2) {
    ?>
        level2();
    <?php
    } else {
    ?>
        levelo();
    <?php
    }
    ?>

    function levelo() {
        $(".showministry").hide();
        $("#ministry").val("");
        $(".showdepartment").hide();
        $("#department").val("");
    }

    function level1() {
        $(".showdepartment").hide();
        $("#department").val("");
        $('.showministry').show();
    }

    function level2() {
        $(".showdepartment").show();
        $('.showministry').show();
    }

    $(document).ready(function() {
        $('#designation').on('change', function() {
            var id = $('#designation').val();
            var action = "level";
            $.ajax({
                url: "ajax/personnel/index.php",
                method: "POST",
                data: {
                    id: id,
                    action: action
                },
                success: function(data) {
                    var pop = JSON.parse(data);
                    var level = pop[0].level;
                    if (level == 0) {
                        levelo();
                    } else if (level == 1) {
                        level1();
                    } else if (level == 2) {
                        level2();
                    }
                }
            });
        });

        $("#ministry").on('change', function() {
            var id = $(this).val();
            if (id != '') {
                var action = "department";
                $.ajax({
                    url: "ajax/personnel/index.php",
                    method: "POST",
                    data: {
                        stid: id,
                        action: action
                    },
                    success: function(data) {
                        $('#department').html(data);
                    }
                })
            } else {
                $('#department').html('<option value="">Please Select <?= $ministrylabel ?> First</option>');
            }
        });
    });
</script>