<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
include_once '../projtrac-dashboard/resource/Database.php';
include_once '../projtrac-dashboard/resource/utilities.php';
require_once __DIR__ . '../../vendor/autoload.php';

session_start();
$user_name = $_SESSION['MM_Username'];
function restriction()
{
    return "
	<script type='text/javascript'>
		swal({
		title: 'Success!',
		text: 'Sorry you are not permitted to access this page',
		type: 'Error',
		timer: 3000,
		icon:'error',
		showConfirmButton: false });
		setTimeout(function(){
			window.history.back();
		}, 3000);
	</script>";
}



if (isset($_GET["fn"]) && !empty($_GET["fn"])) {
    try {
        $hash = $_GET['fn'];
        $decode_fndid = base64_decode($hash);
        $fndid_array = explode("fn918273AxZID", $decode_fndid);
        $financier_id = $fndid_array[1];

        $query_financier = $db->prepare("SELECT * FROM tbl_financiers WHERE id=:fn");
        $query_financier->execute(array(":fn" => $financier_id));
        $row_financier = $query_financier->fetch();
        $financier = $row_financier ?  $row_financier['financier'] : '';


        function get_amount_funding($projid)
        {
            global $db, $financier_id;
            $query_plannedfunds = $db->prepare("SELECT SUM(amountfunding) as planned FROM tbl_myprojfunding WHERE financier=:fid AND projid=:projid");
            $query_plannedfunds->execute(array(":fid" => $financier_id, ":projid" => $projid));
            $row_plannedfunds = $query_plannedfunds->fetch();
            return  !is_null($row_plannedfunds["planned"]) ? $row_plannedfunds["planned"] : 0;
        }

        function get_utilized_amount($projid)
        {
            global $db, $financier_id;
            $query_utilizedfunds = $db->prepare("SELECT SUM(amount) as utilized FROM tbl_payment_request_financiers WHERE financier_id=:fid AND projid=:projid");
            $query_utilizedfunds->execute(array(":fid" => $financier_id, ":projid" => $projid));
            $row_utilizedfunds = $query_utilizedfunds->fetch();
            return  !is_null($row_utilizedfunds["utilized"]) ? $row_utilizedfunds["utilized"] : 0;
        }

        function get_status($status_id)
        {
            global $db;
            $query_Projstatus =  $db->prepare("SELECT * FROM tbl_status WHERE statusid = :projstatus");
            $query_Projstatus->execute(array(":projstatus" => $status_id));
            $row_Projstatus = $query_Projstatus->fetch();
            $total_Projstatus = $query_Projstatus->rowCount();
            $status = "";
            if ($total_Projstatus > 0) {
                $status_name = $row_Projstatus['statusname'];
                $status_class = $row_Projstatus['class_name'];
                $status = '<button type="button" class="' . $status_class . '" style="width:100%">' . $status_name . '</button>';
            }

            return $status;
        }

        function get_financier_planned_amount()
        {
            global $db, $financier_id;
            $query_plannedfunds = $db->prepare("SELECT SUM(amountfunding) as planned FROM tbl_myprojfunding WHERE financier=:fid ");
            $query_plannedfunds->execute(array(":fid" => $financier_id));
            $row_plannedfunds = $query_plannedfunds->fetch();
            return  !is_null($row_plannedfunds["planned"]) ? $row_plannedfunds["planned"] : 0;
        }

        function get_financier_utilized_funds()
        {
            global $db, $financier_id;
            $query_utilizedfunds = $db->prepare("SELECT SUM(amount) as utilized FROM tbl_payment_request_financiers WHERE financier_id=:fid");
            $query_utilizedfunds->execute(array(":fid" => $financier_id));
            $row_utilizedfunds = $query_utilizedfunds->fetch();
            return  !is_null($row_utilizedfunds["utilized"]) ? $row_utilizedfunds["utilized"] : 0;
        }


        $query_company =  $db->prepare("SELECT * FROM tbl_company_settings");
        $query_company->execute();
        $row_company = $query_company->fetch();

        $query_logged_in_user =  $db->prepare("SELECT title, fullname FROM users u inner join tbl_projteam2 t on t.ptid=u.pt_id where userid=:user_name");
        $query_logged_in_user->execute(array(":user_name" => $user_name));
        $row_user = $query_logged_in_user->fetch();
        $printedby = $row_user["title"] . "." . $row_user["fullname"];


        $logo = 'logo.jpg';
        $stylesheet = file_get_contents('bootstrap.css');
        $mpdf = new \Mpdf\Mpdf(['setAutoTopMargin' => 'pad']);
        $mpdf->SetWatermarkImage($logo);
        $mpdf->showWatermarkImage = true;
        // $mpdf->SetProtection(array(), 'UserPassword', 'password');

        $cover_page =
            '<div style="text-align: center;">
                <img src="' . $logo . '" height="180px" style="max-height: 200px; text-align: center;"/>
                <h2 style="" >' . $row_company["company_name"] . '</h2>
                <br/>
                <hr/>
                <h3 style="margin-top:10px;" > FUNDING REPORT</h3>
                <hr/>
                <div style="margin-top:80px;" >
                    <address>
                        <h5>The County Treasury ' . $row_company["postal_address"] . ', KENYA </h5>
                        <h5>Email: ' . $row_company["email_address"] . ' </h5>
                        <h5>Website: ' . $row_company["domain_address"] . ' </h5>
                    </address>
                </div>
            </div>';

        $header =
            '<div style="text-align: right;">
                <img src="' . $logo . '" height="80px" style="max-height: 100px; text-align: center;"/>
                <p> <i><small> FUNDING REPORT</small></i></p>
            </div>';

        $table = '
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <ul class="list-group" style="list-style-type:none">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <li class="list-group-item list-group-item list-group-item-action active" style="list-style-type:none">Financier: ' . $financier . ' </li>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="list-style-type:none">
                        <li class="list-group-item"><strong>Amount Financing: </strong> ' . number_format(get_financier_planned_amount(), 2) . ' </li>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="list-style-type:none">
                        <li class="list-group-item"><strong>Amount Utilized: </strong> ' . number_format(get_financier_utilized_funds(), 2) . ' </li>
                    </div>
                </ul>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                    <thead>
                        <tr>
                            <th width="5%">#&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            <th width="25%">Project &nbsp;&nbsp;&nbsp;&nbsp;</th>
                            <th width="10%">Status &nbsp;&nbsp;&nbsp;&nbsp;</th>
                            <th width="10%">Start Date &nbsp;&nbsp;&nbsp;&nbsp;</th>
                            <th width="10%">End Date &nbsp;&nbsp;&nbsp;&nbsp;</th>
                            <th width="10%">Project Cost &nbsp;&nbsp;&nbsp;&nbsp;</th>
                            <th width="10%">Amount Financing &nbsp;&nbsp;&nbsp;&nbsp;</th>
                            <th width="10%">Amount Utilized &nbsp;&nbsp;&nbsp;&nbsp;</th>
                            <th width="10%">Rate &nbsp;&nbsp;&nbsp;&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>';

        $sql = $db->prepare("SELECT * FROM `tbl_programs` g left join `tbl_projects` p on p.progid=g.progid left join tbl_fiscal_year y on y.id=p.projfscyear left join tbl_status s on s.statusid=p.projstatus WHERE  p.deleted='0' ORDER BY `projfscyear` DESC");
        $sql->execute();
        $rows_count = $sql->rowCount();
        if ($rows_count > 0) {
            $counter = 0;
            while ($row_project = $sql->fetch()) {
                $projid = $row_project['projid'];
                $project_name = $row_project['projname'];
                $project_cost = $row_project['projcost'];
                $project_start_date = $row_project['projstartdate'];
                $project_end_date = $row_project['projenddate'];
                $status = $row_project['projstatus'];

                $query_plannedfunds = $db->prepare("SELECT * FROM tbl_myprojfunding WHERE financier=:fid AND projid=:projid");
                $query_plannedfunds->execute(array(":fid" => $financier_id, ":projid" => $projid));
                $row_plannedfunds = $query_plannedfunds->rowCount();

                if ($row_plannedfunds > 0) {
                    $counter++;
                    $planned_funds = get_amount_funding($projid);
                    $utilized_funds = get_utilized_amount($projid);
                    $rate = $utilized_funds > 0 && $planned_funds > 0 ? ($utilized_funds / $planned_funds) * 100 : 0;
                    $table .=
                        '<tr>
                            <td width="5%">' . $counter . '</td>
                            <td width="35%">' . $project_name . '</td>
                            <td width="20%">' . get_status($status) . '</td>
                            <td width="20%">' . $project_start_date . '</td>
                            <td width="20%">' . $project_end_date . '</td>
                            <td width="5%">' . number_format($project_cost, 2) . '</td>
                            <td width="35%">' . number_format($planned_funds, 2) . '</td>
                            <td width="20%">' . number_format($utilized_funds, 2) . '</td>
                            <td width="20%">' . number_format($rate, 2) . '</td>
                        </tr>';
                }
            }
        } else {
            $table .= "<tr><td colspan='8'>No Record Found</td></tr>";
        }

        $table .= "
                    </tbody>
                </table>
            </div>
        </div>";

        $mpdf->AddPage('p');
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($cover_page, \Mpdf\HTMLParserMode::HTML_BODY);
        $mpdf->SetHTMLHeader($header);

        $mpdf->AddPage('l');
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($table, \Mpdf\HTMLParserMode::HTML_BODY);


        $mpdf->WriteHTML('<h5 style="color:green">Printed By: ' . $printedby . '</h5>');
        $mpdf->SetFooter('{DATE j-m-Y} Uasin Gishu County');
        $mpdf->SetFooter('{DATE j-m-Y} Uasin Gishu County {PAGENO}');
        // $mpdf->Output("Output.pdf", "D");
        $mpdf->Output();
    } catch (PDOException $ex) {
        $results = flashMessage("An error occurred: " . $ex->getMessage());
    }
} else {
    var_dump("Error could notn not be");
    $results =  restriction();
    echo $results;
}
