<?php
require '../vendor/autoload.php';
require 'Connection.php';
require 'Email.php';
class CompletionCertificate
{
    protected $current_date, $db;
    public function __construct()
    {
        $this->current_date = date("d-m-Y");
        $conn = new Connection();
        $this->db = $conn->openConnection();
    }

    public function get_certificates($milestone_id)
    {
        $query_rsCertificates = $this->db->prepare("SELECT * FROM tbl_milestone_certificate WHERE status='0' AND msid=:msid ORDER BY msid");
        $query_rsCertificates->execute(array(":msid" => $milestone_id));
        $row_rsCertificates = $query_rsCertificates->fetch();
        $count_rsCertificates = $query_rsCertificates->rowCount();
        if ($count_rsCertificates > 0) {
            return $row_rsCertificates;
        } else {
            return false;
        }
    }

    public function get_milestone($msid)
    {
        $query_rsCertificates = $this->db->prepare("SELECT * FROM tbl_milestone WHERE status= '5' AND msid=:msid LIMIT 1");
        $query_rsCertificates->execute(array(":msid" => $msid));
        $row_rsCertificates = $query_rsCertificates->fetch();
        $count_rsCertificates = $query_rsCertificates->rowCount();
        if ($count_rsCertificates > 0) {
            return $row_rsCertificates;
        } else {
            return false;
        }
    }

    public function get_project_details($projid)
    {
        $query_rsProjectDetails = $this->db->prepare("SELECT p.*, g.projsector as sector FROM tbl_projects p inner join tbl_programs g ON g.progid=p.progid WHERE p.deleted='0' AND projid = :projid Order BY projid ASC");
        $query_rsProjectDetails->execute(array(":projid" => $projid));
        $row_rsProjectDetails = $query_rsProjectDetails->fetch();
        $count_rsProjectDetails = $query_rsProjectDetails->rowCount();
        if ($count_rsProjectDetails > 0) {
            return $row_rsProjectDetails;
        } else {
            return false;
        }
    }

    public function get_department($stid)
    {
        $query_rsSector = $this->db->prepare("SELECT * FROM tbl_sectors WHERE stid=:stid LIMIT 1");
        $query_rsSector->execute(array(":stid" => $stid));
        $row_rsSector = $query_rsSector->fetch();
        $count_rsSector = $query_rsSector->rowCount();
        if ($count_rsSector > 0) {
            return $row_rsSector;
        } else {
            return false;
        }
    }

    public function get_tender_details($projid)
    {
        $query_rsTender = $this->db->prepare("SELECT * FROM tbl_tenderdetails WHERE projid=:projid LIMIT 1");
        $query_rsTender->execute(array(":projid" => $projid));
        $row_rsTender = $query_rsTender->fetch();
        $count_rsTender = $query_rsTender->rowCount();
        if ($count_rsTender > 0) {
            return $row_rsTender;
        } else {
            return false;
        }
    }

    public function get_controctor($projcontractor)
    {
        $query_rsTender = $this->db->prepare("SELECT * FROM tbl_contractor WHERE contrid=:projcontractor LIMIT 1");
        $query_rsTender->execute(array(":projcontractor" => $projcontractor));
        $row_rsTender = $query_rsTender->fetch();
        $count_rsTender = $query_rsTender->rowCount();
        if ($count_rsTender > 0) {
            return $row_rsTender;
        } else {
            return false;
        }
    }

    public function get_dates($msid)
    {
        $query_tender =  $this->db->prepare("SELECT MIN(sdate) as sdate, MAX(edate) as edate FROM `tbl_task` WHERE msid=:msid");
        $query_tender->execute(array(":msid" => $msid));
        $row_tender = $query_tender->fetch();
        $count_tender = $query_tender->rowCount();
        if ($count_tender > 0) {
            return $row_tender;
        } else {
            return false;
        }
    }


    public function get_inspection_dates($projid)
    {
        $query_tender =  $this->db->prepare("SELECT MAX(created_at) as created_at,created_by FROM `tbl_general_inspection` WHERE projid=:projid");
        $query_tender->execute(array(":projid" => $projid));
        $row_tender = $query_tender->fetch();
        $count_tender = $query_tender->rowCount();
        if ($count_tender > 0) { 
            return $row_tender;
        } else {
            return false;
        }
    }

    public function get_amounts($msid)
    {
        $query_rsCost =  $this->db->prepare("SELECT SUM(d.unit_cost * d.units_no) as cost FROM tbl_project_tender_details d INNER JOIN tbl_task t ON t.tkid = d.tasks WHERE t.msid=:msid");
        $query_rsCost->execute(array(":msid" => $msid));
        $row_rsCost = $query_rsCost->fetch();
        $count_rsCost = $query_rsCost->rowCount();
        if ($count_rsCost > 0) {
            return $row_rsCost;
        } else {
            return false;
        }
    }


    public function get_user($stid)
    {
        $query_rsUser = $this->db->prepare("SELECT * FROM tbl_projteam2 WHERE ministry=:stid AND designation=4 LIMIT 1");
        $query_rsUser->execute(array(":stid" => $stid));
        $row_rsUser = $query_rsUser->fetch();
        $count_rsUser = $query_rsUser->rowCount();
        if ($count_rsUser > 0) {
            return $row_rsUser;
        } else {
            return false;
        }
    }

    public function get_user_details($ptid)
    {
        $query_rsUser = $this->db->prepare("SELECT * FROM tbl_projteam2 WHERE ptid=:ptid LIMIT 1");
        $query_rsUser->execute(array(":ptid" => $ptid));
        $row_rsUser = $query_rsUser->fetch();
        $count_rsUser = $query_rsUser->rowCount();
        if ($count_rsUser > 0) {
            return $row_rsUser;
        } else {
            return false;
        }
    }

    private function calculate_duration($date2, $date1)
    {
        $date1 = strtotime($date1);
        $date2 = strtotime($date2);
        $diff = abs($date2 - $date1);
        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24)
            / (30 * 60 * 60 * 24));
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 -
            $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));

        $t_year = ($years > 0) ? $years . " year(s) " : "";
        $t_month = ($months > 0) ? $months . " month(s) " : "";
        $t_day = ($days > 0)  ? $days . " day(s) " : "";
        return $t_year . $t_month . $t_day;
    }

    public function certificate_of_completion($projid, $controctor_details, $milestone_body)
    {

        $project = $this->get_project_details($projid);
        $projname = $project->projname;
        $stid = $project->sector;
        $sector = $this->get_department($stid);
        $department = $sector->sector;
        $tender_details = $this->get_tender_details($projid);
        $contractrefno = $tender_details->contractrefno;
        $tenderamount = $tender_details->tenderamount;
        $tender_start_date = $tender_details->startdate;
        $tender_end_date = $tender_details->enddate;
        $tender_duration = $this->calculate_duration($tender_end_date, $tender_start_date);


        $user = $this->get_user($stid);
        $co_name = ($user) ? $user->fullname : "";

        $contractor_contact = $contractor_town = $contractor_name = "";
        if ($controctor_details) {
            $contractor_contact = $controctor_details->contact;
            $contractor_town = $controctor_details->city;
            $contractor_name = $controctor_details->contractor_name;
        }

        $inspection_details = $this->get_inspection_dates($projid);
        $inspection_date  = $inspected_by = "";
         
        if ($inspection_details) {
            $inspection_date  = ($inspection_details->created_at) ? date("d M Y", strtotime($inspection_details->created_at)) : "";
            $user = $this->get_user($inspection_details->created_by);
            $inspected_by = ($user) ? $user->fullname : "";
        }

        $logo = '../reports/logo.jpg';
        $stylesheet = file_get_contents('../reports/bootstrap.css');
        $mpdf = new \Mpdf\Mpdf(['setAutoTopMargin' => 'pad']);
        $mpdf->SetWatermarkImage($logo);
        $mpdf->showWatermarkImage = true;
        $mpdf->SetProtection(array(), 'UserPassword', 'password');


        $mpdf->AddPage('l');
        $body = '
        <div class="container">
            <div class="row">
                <div class="text-center">
                <h4>REPUBLIC OF KENYA </h4>
                <h4>COUNTY GOVERNMENT OF UASIN GISHU</h4>
                <h4>DEPARTMENT OF ' . strtoupper($department) . '</h4>
                </div>  
                <div class="col-md-12" align="center"> 
                    <table class="table table-borderless table-condensed table-hover pull-right"  style="border: 0; width:100%">
                        <tr>
                            <td>
                                <address>
                                Tel.Nos: direct line: 020-33754,<br/>
                                    020239037 <br/>
                                +254-053-2033737 <br/>
                                +254-053-2061330 <br/>
                                +254-053-2062208 <br/>
                                +254-053-2062884 <br/> 
                                Fax: +254-053-2062884, <br/>
                                Website: www.uasingishu.go.ke, <br/>
                                Email: uasingishu.go.ke
                                </address>
                            </td>
                            <td>
                                <div style="text-align: center;">
                                    <img src="' . $logo . '" alt="Logo" style=""/>
                                </div>
                            </td>
                            <td>
                                When Replying, Please address to:
                                <address>
                                County Secretary, <br/> 
                                Uasin Gishu County, <br/>
                                P.O. Box 40 -30100,  <br/>
                                Eldoret, Kenya <br/>
                                </address>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-12 text-center" align="center"> 
                <hr style="color:black;" />
                <h2> <strong>COMPLETION/TAKING OVER CERTIFICATE </strong></h2>
                <hr style="color:black;" />
                <h4><strong>' . strtoupper($projname) . ' </strong></h4>
                <h4><strong>CONTRACT NO: ' . $contractrefno . '</strong></h4>
                <hr style="color:black;" />
                </div>'; 
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($body, \Mpdf\HTMLParserMode::HTML_BODY);
        $mpdf->AddPage('l');

        $body = '
                <div class="col-md-12"> 
                    <table class="table table-borderless table-condensed table-hover" style="border: 0;">
                        <tr>
                            <th>EMPLOYER</th>
                            <th>SUPERVISIOR</th>
                            <th>CONTRACTOR</th>
                        </tr>
                        <tr>
                            <td>The County Secretary</td>
                            <td>Chief Officer (' . ucfirst($department) . ')</td>
                            <td>' . $contractor_name . '</td>
                        </tr>
                        <tr>
                            <td>Uasin Gishu County Government</td>
                            <td>Uasin Gishu County Government</td>
                            <td>' . $contractor_contact . '0</td>
                        </tr>
                        <tr>
                            <td>EMPLOYER</td>
                            <td>P.O. Box 40 -30100 </td>
                            <td> </td>
                        </tr>
                        <tr>
                            <td>ELDORET</td>
                            <td>ELDORET</td>
                            <td>' . $contractor_town . '</td>
                        </tr>
                    </table>
                </div>
                <hr style="color:black;" />
                <div class="col-md-12">
                    <table class="table table-borderless table-condensed table-hover" style="border: 0;">
                        <tr>
                            <td>Contract Sum</td>
                            <td> : </td>
                            <td> Ksh. ' . number_format($tenderamount, 2) . '</td>
                        </tr>
                        <tr>
                            <td>Contract Period </td>
                            <td> : </td>
                            <td> ' . $tender_duration . '</td>
                        </tr> 
                    </table>
                </div>
                <hr style="color:black;" />

                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Work complted(Milestones)</th>
                                    <th scope="col">Start Date</th>
                                    <th scope="col">End Date</th>
                                    <th scope="col">Amount</th> 
                                </tr>
                            </thead>
                            <tbody>
                                ' . $milestone_body . '
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr style="color:black;" />

                <div class="col-md-12">
                    <p>
                        Pursuant of Clause 48.1 of the Conditions of Contract,
                        it is hereby certified that the Contractor has substantially
                        completed the works detailed above to the satisfaction of Engineer.
                        Inspection of these works was carried on ' . $inspection_date . ' by the  ' . $inspected_by . '.
                    </p>
                </div>

                <div class="col-md-12"> 
                    <table class="table table-borderless table-condensed table-hover" style="border: 0;">
                        <tr>
                            <th>CHIEF OFFICER</th>
                        </tr>
                        <tr>
                            <td>Name: ' . $co_name . '</td>
                        </tr>
                        <tr>
                            <td>Chief Officer (C.O) Director</td>
                        </tr>
                        <tr>
                            <td>P. O. Box 9021-30100 </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>';
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($body, \Mpdf\HTMLParserMode::HTML_BODY);
        $mpdf->SetFooter('{DATE j-m-Y} Uasin Gishu County {PAGENO}');
        $pdf = $mpdf->output("", "S");
        return $pdf;
    }

    public function index()
    {
        $projects = $this->get_projects();
        foreach ($projects as $project) {
            $projid = $project->projid;
            $projsector = $project->projsector;
            $projcontractor = $project->projcontractor;
            $milestones = $this->get_milestones($projid);
            $array = array();
            if ($milestones) {
                foreach ($milestones as $milestone) {
                    $msid = $milestone->msid;
                    $paymentrequired = $milestone->paymentrequired;
                    $array[] = $msid; //h3
                    if ($paymentrequired) {
                        $certificate = $this->get_certificates($msid);
                        if ($certificate) {
                            $total_amount = 0;
                            $milestone_data = "";
                            for ($i = 0; $i < count($array); $i++) {
                                $milestone_details = $this->get_milestone($msid);
                                if($milestone_details){
                                    $milestone = $milestone_details->milestone;
                                    $milestone_dates = $this->get_dates($msid);
                                    $milestone_start_date = $milestone_dates->sdate;
                                    $milestone_end_date = $milestone_dates->edate;
                                    $milestone_amounts = $this->get_amounts($msid);
                                    $milestone_amount = $milestone_amounts->cost;
                                    $total_amount += $milestone_amount;

                                    $milestone_data .=
                                        '<tr>
                                        <td scope="row">' . $milestone . '</td>
                                        <td>' . $milestone_start_date . '</td>
                                        <td>' . $milestone_end_date . '</td>
                                        <td>' . number_format($milestone_amount, 2) . '</td> 
                                    </tr>';
                                }
                            }

                            $milestone_data .=
                                '<tr>
                                <th colspan="3" align="right">Total amount: </th>
                                <th>' . number_format($total_amount, 2) . '</th> 
                            </tr>';

                            $controctor_details = $this->get_controctor($projcontractor);
                            $pdf = $this->certificate_of_completion($projid, $controctor_details, $milestone_data);
                            $email = new Email();
                            // send email to contractor

                            if ($controctor_details) {
                                $recipient = $controctor_details->email;
                                $data = array(
                                    "sitename" => "Projtrac",
                                    "firstname" => "Evans",
                                    "contact" => "0727143163",
                                    "password" => "password",
                                    "recipient" => $recipient,
                                );

                                $template = $email->email_template(1, $data);
                                $data = array(
                                    "subject" => "Certificate of Completion",
                                    "title" => "Certificate of Completion",
                                    "receipient" => $recipient,
                                    "receipient_name" => "Evans",
                                    "template" => $template,
                                    "page_url" => "project-mapping.php",
                                    "attachment" => $pdf
                                );
                                $mail_response = $email->send_mail($data);
                            }

                            $user = $this->get_user($projsector);

                            if ($user) {
                                $recipient = $user->email;
                                // send email to co
                                $data = array(
                                    "subject" => "Certificate of Completion",
                                    "title" => "Certificate of Completion",
                                    "receipient" => $recipient,
                                    "receipient_name" => "Evans",
                                    "template" => $template,
                                    "page_url" => "project-mapping.php",
                                    "attachment" => ""
                                );
                                $mail_response = $email->send_mail($data);
                            }
                            $array = [];
                        } else {
                            $array = [];
                        }
                    } 
                }
            }  
        }
    }

    public function get_milestones($projid)
    {
        $query_rsCertificates = $this->db->prepare("SELECT * FROM tbl_milestone WHERE status = '5' AND projid=:projid ORDER BY msid ASC");
        $query_rsCertificates->execute(array(":projid" => $projid));
        $row_rsCertificates = $query_rsCertificates->fetchAll();
        $count_rsCertificates = $query_rsCertificates->rowCount();
        if ($count_rsCertificates > 0) {
            return $row_rsCertificates;
        } else {
            return false;
        }
    }

    // get projects 
    public function get_projects()
    {
        $query_rsProj = $this->db->prepare("SELECT * FROM `tbl_projects` p INNER JOIN tbl_programs g ON g.progid = p.progid WHERE p.deleted='0' AND projstage=10 AND (projstatus<>5 AND projstatus<>6 AND projstatus<>2) ORDER BY projid ASC");
        $query_rsProj->execute();
        $row_rsProj = $query_rsProj->fetchAll();
        $count_projects = $query_rsProj->rowCount();
        if ($count_projects > 0) {
            return $row_rsProj;
        } else {
            return false;
        }
    }
}
$certificat = new CompletionCertificate();
$certificat->index();
