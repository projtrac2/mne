<?php
session_start();
$projid = (isset($_GET['plan'])) ? $_GET['plan'] : "";

$user_name = $_SESSION['MM_Username'];

//include_once 'projtrac-dashboard/resource/session.php';

include_once '../projtrac-dashboard/resource/Database.php';
include_once '../projtrac-dashboard/resource/utilities.php';
require_once __DIR__ . '../../vendor/autoload.php';

try {
   $projid = $_GET["projid"];

   $query_logged_in_user =  $db->prepare("SELECT title, fullname FROM users u inner join tbl_projteam2 t on t.ptid=u.pt_id where userid=$user_name");
   $query_logged_in_user->execute(array(":stid" => $stid));
   $row_user = $query_logged_in_user->fetch();
   $printedby = $row_user["title"] . "." . $row_user["fullname"];

   $query_rsProject = $db->prepare("SELECT * FROM tbl_projects WHERE projid='$projid'");
   $query_rsProject->execute();
   $row_rsProject = $query_rsProject->fetch();
   $projname = $row_rsProject['projname'];
   $projcode = $row_rsProject['projcode'];
   $sdate = $row_rsProject['projstartdate'];
   $enddate = $row_rsProject['projenddate'];
   $projduration = $row_rsProject['projduration'];
   $projfscyear = $row_rsProject['projfscyear'];
   $state = explode(",", $row_rsProject['projstate']);

   $query_rsProjectYear = $db->prepare("SELECT yr FROM tbl_fiscal_year WHERE id='$projfscyear'");
   $query_rsProjectYear->execute();
   $row_rsProjectYear = $query_rsProjectYear->fetch();
   $projstartyear = $row_rsProjectYear['yr'];

   $years = floor($projduration / 365);
   $remainder = $projduration % 365;
   if ($remainder > 0) {
      $years = $years + 1;
   }
   $logo = 'logo.jpg';
   $mpdf = new \Mpdf\Mpdf(['setAutoTopMargin' => 'pad']);
   $mpdf->SetWatermarkImage($logo);
   $mpdf->showWatermarkImage = true;
   $mpdf->SetProtection(array(), 'UserPassword', 'password');

   $mpdf->AddPage('l');
   $mpdf->WriteHTML('
      <div style="text-align: center;">
         <img src="' . $logo . '" height="180px" style="max-height: 200px; text-align: center;"/>
         <h2 style="" >COUNTY GOVERNMENT OF UASIN GISHU</h2>
         <br/>
         <hr/>
         <h3 style="margin-top:10px;" >PROJECT SUCCESS STORIES</h3>
         <hr/>
         <div style="margin-top:80px;" >
            <address>
               <h5>The County Treasury P. O. Box 40-30100 ELDORET, KENYA </h5>
               <h5>Email: info@uasingishu.go.ke </h5>
               <h5>Website: www.uasingishu.go.ke </h5>
            </address>
            <h4>' . date('d M Y') . '</h4>
         </div>
      </div>
      ');

   $mpdf->SetHTMLHeader(
      '
      <div style="text-align: right;">
        <img src="' . $logo . '" height="80px" style="max-height: 100px; text-align: center;"/>
         <p><i>Project success stories</i></p>
      </div>'
   );

   $mpdf->AddPage('L');
   $mpdf->WriteHTML('
    <div style="text-align: center;">
       <h2 style="" >' . $projname . '</h2>
       <br/>
       <div style="margin-top:80px;" >
          <address>
             <h5>Project Code:' . $projcode . '</h5>
             <h5>Project Start Date:' . $sdate . '</h5>
             <h5>Project Expected duration:' . $projduration . ' days</h5>
          </address>
       </div>
    </div>
    ');

   $mpdf->AddPage('L');
   $body = '';
   $mpdf->WriteHTML($body);
   $mpdf->WriteHTML('<h5 style="color:green">Printed By: ' . $printedby . '</h5>');
   $mpdf->SetFooter('{DATE j-m-Y} Uasin Gishu County');
   $mpdf->Output();
} catch (PDOException $ex) {
   $result = flashMessage("An error occurred: " . $ex->getMessage());
   echo $result;
}
