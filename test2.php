<?php
try {
    include_once 'projtrac-dashboard/resource/Database.php';
    include_once 'projtrac-dashboard/resource/utilities.php';
    include_once 'includes/app-security.php';
    include_once 'Models/Email.php';
    include_once 'Models/Connection.php';
    require 'vendor/autoload.php';

    // function get_data($formid)
    // {
    //     global $level2label, $db;
    //     // require 'PHPMailer/PHPMailerAutoload.php';
    //     if ($formid != "") {
    //         $queryrs_projects = $db->prepare("SELECT m.startdate, m.enddate, m.enumerator_type, m.form_name, m.resultstype, d.level3, d.location_disaggregation, d.enumerators, s.id, s.state, p.projid AS proj, p.projname, p.projcode FROM tbl_indicator_baseline_survey_forms m INNER JOIN tbl_indicator_baseline_survey_details d ON d.formid = m.id INNER JOIN tbl_state s ON s.id = d.level3 INNER JOIN tbl_projects p ON p.projid = m.projid WHERE m.id = :form_id");
    //         $queryrs_projects->execute(array(':form_id' => $formid));
    //         $rows_projects = $queryrs_projects->fetchAll();
    //         $totalrows_rs_projects = $queryrs_projects->rowCount();

    //         if ($totalrows_rs_projects > 0) {
    //             foreach ($rows_projects as $rows_rs_projects) {
    //                 $projid = $rows_rs_projects["proj"];
    //                 $projname = $rows_rs_projects["projname"];
    //                 $projcode = $rows_rs_projects["projcode"];
    //                 $startdate = $rows_rs_projects["startdate"];
    //                 $enddate = $rows_rs_projects["enddate"];
    //                 $resultstype = $rows_rs_projects["resultstype"];
    //                 $formname = $rows_rs_projects["form_name"];
    //                 $state = $rows_rs_projects["state"];
    //                 $level2id = $rows_rs_projects["id"];
    //                 $enumerator_type = $rows_rs_projects["enumerator_type"];
    //                 $receipient = $rows_rs_projects["enumerators"];
    //                 $location_disaggregation = $rows_rs_projects["location_disaggregation"];
    //                 $fullname = "Enumerator";

    //                 $resultstypename = $resultstype == 1 ? "Impact " : "Outcome ";

    //                 if ($enumerator_type == 1) {
    //                     $query_rsteam = $db->prepare("SELECT t.email AS email, title, fullname FROM tbl_projteam2 t left join users u on u.pt_id=t.ptid WHERE userid = '$receipient'");
    //                     $query_rsteam->execute();
    //                     $row_rsteam = $query_rsteam->fetch();
    //                     $totalRows_rsteam = $query_rsteam->rowCount();
    //                     $title = $row_rsteam['title'];
    //                     $fullname = $title . "." . $row_rsteam['fullname'];
    //                     $receipient = $row_rsteam['email'];
    //                 }

    //                 $receipient = 'biwottech@gmail.com';
    //                 $locationName = "";
    //                 if ($location_disaggregation != NULL) {
    //                     $query_rslocation = $db->prepare("SELECT disaggregations FROM tbl_indicator_level3_disaggregations WHERE id = '$location_disaggregation'");
    //                     $query_rslocation->execute();
    //                     $row_rslocation = $query_rslocation->fetch();
    //                     $totalRows_rslocation = $query_rslocation->rowCount();
    //                     $locationName = "Location :" . $row_rslocation['disaggregations'];
    //                 }

    //                 $query_url =  $db->prepare("SELECT * FROM tbl_company_settings");
    //                 $query_url->execute();
    //                 $row_url = $query_url->fetch();
    //                 $url = $row_url["main_url"];
    //                 $org = $row_url["company_name"];
    //                 $org_email = $row_url["email_address"];

    //                 //encode enumerator link details
    //                 $encode_prjid = base64_encode("evprj{$projid}");
    //                 $encode_frmid = base64_encode("evfrm{$formid}");
    //                 $encode_emailid = base64_encode("eveml{$receipient}");
    //                 $encode_level2id = base64_encode("evloc{$level2id}");

    //                 $detailslink = '<a href="' . $url . 'public-survey-form?prj=' . $encode_prjid . '&fm=' . $encode_frmid . '&em=' . $encode_emailid . '&loc=' . $encode_level2id . '" class="btn bg-light-blue waves-effect" style="margin-top:10px; margin-left:-9px">CLICK HERE TO OPEN FORM</a>';

    //                 $mainmessage = ' Dear ' . $fullname . ',
    //                     <p>Please note you have been assigned to do project ' . $resultstypename . $formname . ' survey as per the details below:</p>
    //                     <p>Project Code:' . $projcode . '<br>
    //                     Project Name: ' . $projname . '<br>
    //                     Survey start date: ' . $startdate . '<br>
    //                     Survey end date : ' . $enddate . '<br>
    //                     ' . $level2label . ': ' . $state . '<br>
    //                     ' . $locationName . '
    //                     <p>Prepare the required resources. </p>';


    //                 $title = "Project " . $resultstypename . "Evaluation Survey";
    //                 $subject = "Project " . $resultstypename . $formname . " Survey";
    //                 $receipientName = $fullname;
    //                 $mail = new Email();
    //                 $body = $mail->email_body_template($subject, $mainmessage, $detailslink);
    //                 return $mail->sendMail($subject, $body, $receipient, $fullname, []);
    //                 // include("email-body.php");
    //                 // include("email-conf-settings.php");
    //             }
    //         }
    //     }
    // }
    // $formid = 20;
    // get_data($formid);

    // phpinfo();
    echo ini_get("session.gc_maxlifetime");
} catch (PDOException $ex) {
    customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
