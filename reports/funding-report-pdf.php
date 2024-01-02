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


        function get_financier()
        {
            global $db, $financier_id;
            $query_financier = $db->prepare("SELECT * FROM tbl_financiers WHERE id=:financier_id");
            $query_financier->execute(array(":financier_id" => $financier_id));
            $row_financier = $query_financier->fetch();
            $rows_financier = $query_financier->rowCount();
            return $rows_financier > 0 ? $row_financier['financier'] : '';
        }

        
        $financier = get_financier();

        if ($financier != '') {
            $logo = 'logo.jpg';
            $stylesheet = file_get_contents('bootstrap.css');
            $mpdf = new \Mpdf\Mpdf(['setAutoTopMargin' => 'pad']);
            $mpdf->SetWatermarkImage($logo);
            $mpdf->showWatermarkImage = true;
            $mpdf->SetProtection(array(), 'UserPassword', 'password');

            function get_username()
            {
                global $db, $user_name;
                $query_rsPMbrs = $db->prepare("SELECT t.*, t.email AS email, tt.title AS ttitle, u.userid FROM tbl_projteam2 t inner join users u on u.pt_id=t.ptid inner join tbl_titles tt on tt.id=t.title WHERE userid = :userid ORDER BY ptid ASC");
                $query_rsPMbrs->execute(array(":userid" => $user_name));
                $row_rsPMbrs = $query_rsPMbrs->fetch();
                $count_row_rsPMbrs = $query_rsPMbrs->rowCount();
                return $count_row_rsPMbrs > 0 ?  $row_rsPMbrs['ttitle'] . ". " . $row_rsPMbrs['fullname'] : "";
            }

            function get_amount_funding($projid)
            {
                global $db, $financier_id;
                $query_plannedfunds = $db->prepare("SELECT SUM(amountfunding) as planned FROM tbl_myprojfunding WHERE financier=:fid AND projid=:projid");
                $query_plannedfunds->execute(array(":fid" => $financier_id, ":projid" => $projid));
                $row_plannedfunds = $query_plannedfunds->fetch();
                return  !is_null($row_plannedfunds["planned"]) ? $row_plannedfunds["planned"] : 0;
            }

            function get_received_amount($projid)
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

            function get_project_status($status)
            {
                global $db;
                $query_Projstatus =  $db->prepare("SELECT * FROM tbl_status WHERE statusid = :projstatus");
                $query_Projstatus->execute(array(":projstatus" => $status));
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

            function get_cover_page()
            {
                global $logo, $db, $financier;
                $query_company =  $db->prepare("SELECT * FROM tbl_company_settings");
                $query_company->execute();
                $row_company = $query_company->fetch();
                $rows_company = $query_company->rowCount();
                $cover_page = '';
                if ($rows_company > 0) {
                    $company_name = $row_company["company_name"];
                    $postal_address = $row_company["postal_address"];
                    $email_address = $row_company["email_address"];
                    $domain = $row_company["domain_address"];
                    $cover_page =
                        '<div style="text-align: center;">
                        <img src="' . $logo . '" height="180px" style="max-height: 200px; text-align: center;"/>
                        <h2 style="" >' . $company_name . '</h2>
                        <br/>
                        <br/>
                        <br/>
                        <br/>
                        <hr/>
                        <h3 style="margin-top:10px;" > ' . $financier . ' REPORT</h3>
                        <hr/>
                        <div style="margin-top:80px;" >
                            <address>
                                <h5>The County Treasury ' . $postal_address . ', KENYA </h5>
                                <h5>Email: ' . $email_address . ' </h5>
                                <h5>Website: ' . $domain . ' </h5>
                            </address>
                        </div>
                    </div>';
                }

                return $cover_page;
            }

            function get_header()
            {
                global $logo, $financier;
                return
                    '<div style="text-align: right;">
                    <img src="' . $logo . '" height="80px" style="max-height: 100px; text-align: center;"/>
                    <p> <i><small>' . $financier . ' REPORT</small></i></p>
                </div>';
            }

            function get_table()
            {
                global $db, $financier_id;
                $total_received_funds = $total_project_cost = $total_amount_utilized  =  $total_planned_funds = 0;
                $body = '';

                $sql = $db->prepare("SELECT p.* FROM tbl_projects p inner join tbl_myprojfunding m on p.projid=m.projid WHERE p.deleted='0' and m.financier = :fnid GROUP BY p.projid ORDER BY m.id ASC");
                $sql->execute(array(":fnid" => $financier_id));
                $rows_count = $sql->rowCount();

                if ($rows_count > 0) {
                    $counter = 0;
                    while ($row_project = $sql->fetch()) {
                        $counter++;
                        $projid = $row_project['projid'];
                        $project_name = $row_project['projname'];
                        $project_cost = $row_project['projcost'];
                        $project_start_date = $row_project['projstartdate'];
                        $project_end_date = $row_project['projenddate'];
                        $status = $row_project['projstatus'];
                        $project_status = get_project_status($status);
                        $planned_funds = get_amount_funding($projid);

                        $received_funds = get_amount_funding($projid);
                        $utilized_funds = get_utilized_amount($projid);
                        $total_project_cost += $project_cost;
                        $total_received_funds += $received_funds;
                        $total_amount_utilized += $utilized_funds;
                        $total_planned_funds += $planned_funds;
                        $rate = $utilized_funds > 0 && $received_funds > 0 ? ($utilized_funds / $received_funds) * 100 : 0;
                        $body .= '
                            <tr>
                                <td width="5%">' . $counter . '</td>
                                <td width="35%">' . $project_name . '</td>
                                <td width="20%">' . $project_status . '</td>
                                <td width="20%">' . $project_start_date . '</td>
                                <td width="20%">' . $project_end_date . '</td>
                                <td width="5%">' . number_format($project_cost, 2) . '</td>
                                <td width="35%">' . number_format($planned_funds, 2) . '</td>
                                <td width="20%">' . number_format($received_funds, 2) . '</td>
                                <td width="20%">' . number_format($utilized_funds, 2) . '</td>
                                <td width="20%">' . number_format($rate, 2) . '</td>
                            </tr>';
                    }
                }

                $rate = $utilized_funds > 0 && $received_funds > 0 ? $utilized_funds / $received_funds * 100 : 0;

                return '
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
                                    <th width="10%">Planned Cost &nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th width="10%">Cost &nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th width="10%">Received Amount &nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th width="10%">Amount Utilized &nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th width="10%">Rate &nbsp;&nbsp;&nbsp;&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                            ' . $body . '
                            </tbody>
                            <tfoot>
                                <tr >
                                    <th colspan="5">Total </th>
                                    <th>' . number_format($total_project_cost, 2) . '</th>
                                    <th>' . number_format($total_planned_funds, 2) . '</th>
                                    <th>' . number_format($total_received_funds, 2) . '</th>
                                    <th>' . number_format($total_amount_utilized, 2) . '</th>
                                    <th>' . number_format($rate, 2) . '</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>';
            }

            $header = get_header();
            $cover_page = get_cover_page();
            $table = get_table();

            $mpdf->AddPage('l');
            $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
            $mpdf->WriteHTML($cover_page, \Mpdf\HTMLParserMode::HTML_BODY);
            $mpdf->SetHTMLHeader($header);

            $mpdf->AddPage('l');
            $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
            $mpdf->WriteHTML($table, \Mpdf\HTMLParserMode::HTML_BODY);

            $mpdf->WriteHTML('<h5 style="color:green">Printed By: ' . get_username() . '</h5>');
            $mpdf->SetFooter('{DATE j-m-Y} Uasin Gishu County');
            $mpdf->SetFooter('{DATE j-m-Y} Uasin Gishu County {PAGENO}');
            $mpdf->Output("Output.pdf", "D");
            // $mpdf->Output();
        } else {
            $results =  restriction();
            echo $results;
        }
    } catch (PDOException $ex) {
    }
} else {
    var_dump("Error could notn not be");
    $results =  restriction();
    echo $results;
}
