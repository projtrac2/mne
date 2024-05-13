<?php
try {
   include '../controller.php';
   if (isset($_POST["approveProj"]) && $_POST["approveProj"] == "approveProj") {
      $projid = $_POST['projid'];
      $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid='$projid'");
      $query_rsProjects->execute();
      $row_rsProjects = $query_rsProjects->fetch();
      $totalRows_rsProjects = $query_rsProjects->rowCount();

      if ($totalRows_rsProjects > 0) {
         $progid = $row_rsProjects['progid'];
         $projname = $row_rsProjects['projname'];
         $projdurationInDays = $row_rsProjects['projduration'];
         $projcode = $row_rsProjects['projcode'];
         $projduration = $row_rsProjects['projduration'];
         $project_cost = $row_rsProjects['projcost'];
         $project_type = $row_rsProjects['project_type'];

         $approve = '
         <fieldset class="scheduler-border row setup-content" id="step-1" style="padding:10px">
            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Project Details</legend>
            <div class="row clearfix">
               <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="card">
                     <div class="header" >
                        <div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
                        <h5 class="list-group-item list-group-item list-group-item-action active"><strong>Project Name:</strong> ' . $projname . ' </h5>
                        </div>
                     </div>
                     <div class="body">
                     <div class="row clearfix">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                           <label for="projduration">Project Duration (Days)*:</label>
                           <div class="form-line">
                              <input type="text"  name="projduration" id="projduration1" class="form-control" value="' . $projduration . '" required disabled>
                              <input type="hidden"  name="progid" id="progid" class="form-control" value="' . $progid . '" required>
                              <input type="hidden"  name="projid" id="projid" class="form-control" value="' . $projid . '" required>
                           </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                           <label for="projduration">Project Cost *:</label>
                           <div class="form-line">
                              <input type="text"  name="project_cost1" id="project_cost1" placeholder="" class="form-control" value="' . number_format($project_cost, 2) . '" class="form-control"  disabled/>
                              <input type="hidden"  name="project_cost" id="project_cost" placeholder="" class="form-control" value="' . $project_cost . '" class="form-control" />
                           </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                           <label for="projduration">Direct Cost Budget *:</label>
                           <div class="form-line">
                              <input type="number"  name="direct_budget" id="direct_budget" onchange="distribute_project_cost(1)" onkeyup="distribute_project_cost(1)" min="1" max="' . $project_cost . '" placeholder="Enter Direct Cost budget" class="form-control" value="" class="form-control" required/>
                           </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                           <label for="projduration">Administrative Budget *:</label>
                           <div class="form-line">
                              <input type="number"  name="administrative_budget" id="administrative_budget" min="0" onchange="distribute_project_cost(3)" onkeyup="distribute_project_cost(3)" max="' . $project_cost . '" placeholder="Enter Administrative budget" class="form-control" value="" class="form-control" required/>
                           </div>
                        </div>
                     </div>
                     </div>
                  </div>
               </div>
            </div>
         </fieldset>
         <fieldset class="scheduler-border row setup-content" id="step-1" style="padding:10px">
            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Financial Partners</legend>
            <div class="row clearfix" id="">
               <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="table-responsive">
                     <table class="table table-bordered table-striped table-hover" id="approve_financier_table" style="width:100%">
                        <thead>
                           <tr>
                              <th width="5%">#</th>
                              <th width="25%">Source Category</th>
                              <th width="25%">Financier</th>
                              <th width="15%">Amount (Ksh)</th>
                              <th width="5%">
                              <button type="button" name="addplus" id="addplus" onclick="add_row_financier();" class="btn btn-success btn-sm">
                                 <span class="glyphicon glyphicon-plus"></span>
                              </button>
                              </th>
                           </tr>
                        </thead>
                        <tbody id="financier_table_body">
                        <tr></tr>
                        <tr id="removeTr"><td colspan="6">Add Financier </td></tr>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </fieldset>
         <fieldset class="scheduler-border row setup-content" id="step-1" style="padding:10px">
            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Other Partners</legend>
            <div class="row clearfix" id="">
               <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="table-responsive">
                     <table class="table table-bordered table-striped table-hover" id="approve_partners_table" style="width:100%">
                        <thead>
                           <tr>
                              <th width="5%">#</th>
                              <th width="20%">Partner</th>
                              <th width="20%">Role of Partner</th>
                              <th width="50%">Description</th>
                              <th width="5%">
                                 <button type="button" name="addplus" id="addplus" onclick="add_row_partners();" class="btn btn-success btn-sm">
                                    <span class="glyphicon glyphicon-plus"></span>
                                 </button>
                              </th>
                           </tr>
                        </thead>
                        <tbody id="partners_table">
                        <tr></tr>
                        <tr id="add_new_partner"><td colspan="7">Add partners </td></tr>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </fieldset>
         <fieldset class="scheduler-border row setup-content" id="step-1" style="padding:10px">
            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Attachments</legend>
            <div class="row clearfix " id="">
               <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="body table-responsive">
                     <table class="table table-bordered" style="width:100%">
                        <thead>
                           <tr>
                              <th style="width:2%">#</th>
                              <th style="width:30%">Attachment</th>
                              <th style="width:66%">Purpose</th>
                              <th style="width:2%">
                              <button type="button" name="addplus1" onclick="add_row_files();" title="Add another document" class="btn btn-success btn-sm">
                                 <span class="glyphicon glyphicon-plus">
                                 </span>
                              </button>
                              </th>
                           </tr>
                        </thead>
                        <tbody id="documents_table">
                           <tr></tr>
                           <tr id="add_new_file">
                              <td colspan="4"> Add file </td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </fieldset>';
         echo $approve;
      }
   }

   //approve item
   if (isset($_POST["approveitem"])) {
      $projid = $_POST['projid'];
      $progid = $_POST['progid'];
      $approved = $_POST['approveitem'];
      $user_name = $_POST['user_name'];
      $date = date("Y-m-d");
      $valid = [];
      $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid=:projid");
      $query_rsProjects->execute(array(":projid" => $projid));
      $row_rsProjects = $query_rsProjects->fetch();
      $totalRows_rsProjects = $query_rsProjects->rowCount();

      if ($totalRows_rsProjects > 0) {
         $project = $row_rsProjects['projname'];
         $projplanstatus = $row_rsProjects['projplanstatus'];
         $projcategory = $row_rsProjects['projcategory'];
         $projcost = $row_rsProjects['projcost'];
         $project_type = $row_rsProjects['project_type'];
         $stage_id = $row_rsProjects['stage_id'];
         $progid = $row_rsProjects['progid'];

         if ($stage_id > 0) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Approved Project";
         } else {
            // financial partners
            if (isset($_POST['amountfunding'])) {
               for ($i = 0; $i < count($_POST['amountfunding']); $i++) {
                  $sourcecategory = $_POST['source_category'][$i];
                  $source = $_POST['financier'][$i];
                  $amountfunding = $_POST['amountfunding'][$i];
                  $insertSQL1 = $db->prepare("INSERT INTO `tbl_myprojfunding`(progid, projid, sourcecategory, financier,  amountfunding, created_by, date_created) VALUES(:progid, :projid, :sourcecategory,:financier, :amountfunding, :created_by, :date_created)");
                  $result1  = $insertSQL1->execute(array(":progid" => $progid, ":projid" => $projid, ":sourcecategory" => $sourcecategory, ":financier" => $source, ":amountfunding" => $amountfunding, ":created_by" => $user_name, ":date_created" => $date));
               }
            }

            // other partners
            if (isset($_POST['partner_id'])) {
               for ($i = 0; $i < count($_POST['partner_id']); $i++) {
                  $partner_role = $_POST['partner_role'][$i];
                  $partner_id = $_POST['partner_id'][$i];
                  $description = $_POST['description'][$i];
                  $insertSQL1 = $db->prepare("INSERT INTO `tbl_myprojpartner`(projid,partner_id,role,description,created_by,created_at) VALUES(:projid, :partner_id,:partner_role,:description,:created_by,:created_at)");
                  $result1  = $insertSQL1->execute(array(":projid" => $projid, ":partner_id" => $partner_id, ":partner_role" => $partner_role, ":description" => $description, ":created_by" => $user_name, ":created_at" => $date));
               }
            }

            if (isset($_POST['attachmentpurpose'])) {
               $countP = count($_POST["attachmentpurpose"]);
               $stage = 1;
               // insert new data
               for ($cnt = 0; $cnt < $countP; $cnt++) {
                  $purpose = $_POST["attachmentpurpose"][$cnt];
                  if (!empty($_FILES['pfiles']['name'][$cnt])) {
                     $filename = basename($_FILES['pfiles']['name'][$cnt]);
                     $ext = substr($filename, strrpos($filename, '.') + 1);
                     if (($ext != "exe") && ($_FILES["pfiles"]["type"][$cnt] != "application/x-msdownload")) {
                        $newname = $projid . "_" . $stage . "_" . $filename;
                        $filepath = "../../uploads/project-approval/" . $newname;
                        if (!file_exists($filepath)) {
                           if (move_uploaded_file($_FILES['pfiles']['tmp_name'][$cnt], $filepath)) {
                              $fname = $newname;
                              $mt = "uploads/project-approval/" . $newname;
                              $filecategory = "Project Approval";
                              $qry1 = $db->prepare("INSERT INTO tbl_files (`projid`, `projstage`, `filename`, `ftype`, `floc`, `fcategory`, `reason`, `uploaded_by`, date_uploaded) VALUES (:projid, :stage, :fname, :ext, :mt, :filecat, :reason, :user, :date)");
                              $qry1->execute(array(':projid' => $projid, ":stage" => $stage, ':fname' => $fname, ':ext' => $ext, ':mt' => $mt, ':filecat' => $filecategory, ':reason' => $purpose, ':user' => $user_name, ':date' => $date));
                           }
                        } else {
                           $type = 'error';
                           $msg = 'File you are uploading already exists, try another file!!';
                        }
                     } else {
                        $type = 'error';
                        $msg = 'This file type is not allowed, try another file!!';
                     }
                  }
               }
            }

            $stage_id = 0;
            $substage_id = 7;
            if ($project_type == 0) {
               $stage_id = 1;
               $substage_id = 9;
               $sql = $db->prepare("INSERT INTO tbl_project_stage_actions (projid,stage,sub_stage,created_by,created_at) VALUES (:projid,:stage,:sub_stage,:created_by,:created_at)");
               $result = $sql->execute(array(":projid" => $projid, ':stage' => $substage_id, ':sub_stage' => 0, ':created_by' => $user_name, ':created_at' => $date));
            }

            $direct_cost = $_POST['direct_budget'];
            $administrative_cost = $_POST['administrative_budget'];
            $approveItemQuery = $db->prepare("UPDATE `tbl_projects` SET projstatus=:project_status, direct_cost=:direct_cost,administrative_cost=:administrative_cost, projplanstatus=:approved, stage_id=:stage_id,projstage=:stage, approved_date=:approved_date, approved_by=:approved_by WHERE projid=:projid");
            $approveItemQuery->execute(array(":project_status" => 3, ":direct_cost" => $direct_cost, ":administrative_cost" => $administrative_cost, ":approved" => $approved, ":stage_id" => $stage_id, ":stage" => $substage_id, ":approved_date" => $date, ":approved_by" => $user_name, ':projid' => $projid));

            if ($approved) {
               $mail = new Email();
               $response =  $mail->send_master_data_email($projid, 6, '');
               $valid['success'] = $response;
               $valid['messages'] = "Successfully Approved Project";
            } else {
               $valid['success'] = false;
               $valid['messages'] = "Error while approving!!";
            }
         }
      }
      echo json_encode($valid);
   }

   if (isset($_POST['unapproveitem'])) {
      $projid = $_POST['projid'];
      $year = $_POST['year'];

      $approveQuery = $db->prepare("UPDATE `tbl_annual_dev_plan` SET status=0 WHERE projid=:projid and financial_year=:year");
      $approved = $approveQuery->execute(array(':projid' => $projid, ':year' => $year));

      $approved_adp_budget = $db->prepare("UPDATE `tbl_adp_projects_budget` SET  adjusted_amount=:adjusted_amount WHERE projid=:projid AND year=:year");
      $approved_adp_budget->execute(array(":adjusted_amount" => null, ':projid' => $projid, ':year' => $year));

      $approveItemQuery = $db->prepare("UPDATE `tbl_projects` SET  projplanstatus=:approved, projstage=:stage, approved_date=:approved_date, approved_by=:approved_by WHERE projid=:projid");
      $approved = $approveItemQuery->execute(array(":approved" => 0, ":stage" => 8, ":approved_date" => null, ":approved_by" => 0, ':projid' => $projid));

      if ($approved) {

         $valid['success'] = true;
         $valid['messages'] = "Successfully unapproved Project";
      } else {
         $valid['success'] = false;
         $valid['messages'] = "Error while unapproving!!";
      }

      echo json_encode($valid);
   }

   if (isset($_GET['get_finacier'])) {
      $source_category = $_GET['source_category'];
      $query_rsFunder = $db->prepare("SELECT f.id, f.financier FROM tbl_financiers f INNER JOIN tbl_financier_type t ON t.id = f.type WHERE active=1 AND  t.id=:source_category ");
      $query_rsFunder->execute(array(":source_category" => $source_category));
      $totalRows_rsFunder = $query_rsFunder->rowCount();

      $inputs = '<option value="">Select Financier list</option>';
      if ($totalRows_rsFunder > 0) {
         while ($row_rsFunder = $query_rsFunder->fetch()) {
            $fndid = $row_rsFunder['id'];
            $funder = $row_rsFunder['financier'];
            $inputs .= '<option value="' . $fndid . '">' . $funder . '</option>';
         }
      }
      echo json_encode(array("financiers" => $inputs, "sucess" => true));
   }

   if (isset($_POST['change_start_date'])) {
      $projid = $_POST['projid'];
      $projsdate = $_POST['projsdate'];

      $query_project = $db->prepare("SELECT * FROM tbl_projects WHERE projid=:projid");
      $query_project->execute(array(":projid" => $projid));
      $row_project = $query_project->fetch();
      $projduration = $row_project['projduration'];

      $projectedate = date('d M Y', strtotime($projsdate . " + {$projduration} days"));
      echo json_encode(array("projectedate" => $projectedate));
   }


   if (isset($_POST["addADPBudget"]) && $_POST["addADPBudget"] == 1) {
      $projid = $_POST['projid'];
      $year = $_POST['year'];

      $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid=:projid");
      $query_rsProjects->execute(array(":projid" => $projid));
      $row_rsProjects = $query_rsProjects->fetch();
      $totalRows_rsProjects = $query_rsProjects->rowCount();

      $projname = $row_rsProjects['projname'];
      $projstartdate = $row_rsProjects['projstartdate'];
      $projenddate = $row_rsProjects['projenddate'];
      $projdurationInDays = $row_rsProjects['projduration'];
      $projfscyear = $row_rsProjects['projfscyear'];
      $projcode = $row_rsProjects['projcode'];
      $projbudget = number_format($row_rsProjects['projcost'], 2);

      //convert project duration to years, months, and days
      $date1 = new DateTime($projstartdate);
      $date2 = new DateTime($projenddate);

      $interval = $date2->diff($date1);
      $project_duration = $interval->format('%Y Years, %m Months, %d Days');

      $query_rsYear =  $db->prepare("SELECT id, yr FROM tbl_fiscal_year WHERE id = '$year'");
      $query_rsYear->execute();
      $row_rsYear = $query_rsYear->fetch();
      $projstartyear =  $row_rsYear['yr'];
      $projstart = $projstartyear  . '-07-01';

      $yr = date("Y");
      $mnth = date("m");

      if ($mnth >= 7 && $mnth <= 12) {
         $yr = $yr;
      } elseif ($mnth >= 1 && $mnth <= 6) {
         $yr = $yr - 1;
      }

      $yearnxt = $yr + 1;
      $finyear = $yr . "/" . $yearnxt;


      $TargetB = '
      <div class="row clearfix">
         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
               <div class="header" >
                  <div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
                     <h5 class="list-group-item list-group-item list-group-item-action active"><strong>Project Name:</strong> ' . $projname . ' </h5>
                  </div>
               </div>
               <div class="body">
                  <input type="hidden"  name="projid" id="projid" class="form-control" value="' . $projid . '" required>
                  <input type="hidden"  name="budgetyear" id="budgetyear" value="' . $year . '">
                  <div class="row clearfix">
                     <div class="col-lg-6 col-md-4 col-sm-12 col-xs-12">
                        <label for="projduration">Project Budget *:</label>
                        <div class="form-line">
                           <div class="form-control"  style="border:1px solid #f0f0f0; border-radius:3px;">' . $projbudget . '</div>
                        </div>
                     </div>
                     <div class="col-lg-6 col-md-4 col-sm-12 col-xs-12">
                        <label for="projduration">Requesting Budget *:</label>
                        <div class="form-line">
                           <input type="number"  name="projadpbudget" value="" id="projadpbudget" placeholder="Enter project ADP budget for the financial year ' . $finyear . '" class="form-control" class="form-control" required />
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>';
      echo $TargetB;
   }

   if (isset($_POST["addProjADPBudget"])) {
      $projid = $_POST['projid'];
      $budget = $_POST['projadpbudget'];
      $budgetyear = $_POST['budgetyear'];
      $date = date("Y-m-d");

      $query_progid =  $db->prepare("SELECT progid FROM tbl_projects WHERE projid = :projid");
      $query_progid->execute(array(":projid" => $projid));
      $row_progid = $query_progid->fetch();
      $progid =  $row_progid['progid'];

      $insertbudget = $db->prepare("INSERT INTO `tbl_adp_projects_budget`(projid, progid, year, amount, created_by, date_created)  VALUES(:projid, :progid, :year, :amount, :createdby, :datecreated)");
      $results  = $insertbudget->execute(array(":projid" => $projid, ":progid" => $progid, ":year" => $budgetyear, ":amount" => $budget, ":createdby" => $user_name, ":datecreated" => $date));


      if ($results === TRUE) {
         $projstage = 9;
         $update_sub_stage = $db->prepare("UPDATE `tbl_projects` SET projstage=:projstage, updated_by=:updated_by, date_updated=:date_updated WHERE projid=:projid");
         $update_sub_stage->execute(array(":projstage" => $projstage, ":updated_by" => $user_name, ":date_updated" => $date, ":projid" => $projid));

         $sql = $db->prepare("INSERT INTO tbl_project_stage_actions (projid,stage,sub_stage,created_by,created_at) VALUES (:projid,:stage,:sub_stage,:created_by,:created_at)");
         $result = $sql->execute(array(":projid" => $projid, ':stage' => $projstage, ':sub_stage' => 0, ':created_by' => $user_name, ':created_at' => $date));

         return;

         $valid['success'] = true;
         $valid['messages'] = "Budget successfully requested";
      } else {
         $valid['success'] = false;
         $valid['messages'] = "Error while requesting the budget!!";
      }
      echo json_encode($valid);
   }


   if (isset($_POST["addApprovedADPBudget"]) && $_POST["addApprovedADPBudget"] == 1) {
      $projid = $_POST['projid'];
      $year = $_POST['year'];

      $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid=:projid");
      $query_rsProjects->execute(array(":projid" => $projid));
      $row_rsProjects = $query_rsProjects->fetch();
      $totalRows_rsProjects = $query_rsProjects->rowCount();

      $projname = $row_rsProjects['projname'];
      $projstartdate = $row_rsProjects['projstartdate'];
      $projenddate = $row_rsProjects['projenddate'];
      $projdurationInDays = $row_rsProjects['projduration'];
      $projfscyear = $row_rsProjects['projfscyear'];
      $projcode = $row_rsProjects['projcode'];
      $projbudget = number_format($row_rsProjects['projcost'], 2);

      $query_requested_budget = $db->prepare("SELECT * FROM tbl_adp_projects_budget WHERE projid=:projid AND year=:year");
      $query_requested_budget->execute(array(":projid" => $projid, ":year" => $year));
      $row_requested_budget = $query_requested_budget->fetch();
      $requested_budget = number_format($row_requested_budget['amount'], 2);

      //convert project duration to years, months, and days
      $date1 = new DateTime($projstartdate);
      $date2 = new DateTime($projenddate);

      $interval = $date2->diff($date1);
      $project_duration = $interval->format('%Y Years, %m Months, %d Days');

      $query_rsYear =  $db->prepare("SELECT id, yr FROM tbl_fiscal_year WHERE id = '$year'");
      $query_rsYear->execute();
      $row_rsYear = $query_rsYear->fetch();
      $projstartyear =  $row_rsYear['yr'];
      $projstart = $projstartyear  . '-07-01';

      $yr = date("Y");
      $mnth = date("m");

      if ($mnth >= 7 && $mnth <= 12) {
         $yr = $yr;
      } elseif ($mnth >= 1 && $mnth <= 6) {
         $yr = $yr - 1;
      }

      $yearnxt = $yr + 1;
      $finyear = $yr . "/" . $yearnxt;


      $TargetB = '
      <div class="row clearfix">
         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
               <div class="header" >
                  <div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
                     <h5 class="list-group-item list-group-item list-group-item-action active"><strong>Project Name:</strong> ' . $projname . ' </h5>
                  </div>
               </div>
               <div class="body">
                  <input type="hidden"  name="projid" id="projid" class="form-control" value="' . $projid . '" required>
                  <input type="hidden"  name="budgetyear" id="budgetyear" value="' . $year . '">
                  <div class="row clearfix">
                     <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label for="projduration">Project Budget *:</label>
                        <div class="form-line">
                           <div class="form-control"  style="border:1px solid #f0f0f0; border-radius:3px;">' . $projbudget . '</div>
                        </div>
                     </div>
                     <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label for="projduration">Requested Budget *:</label>
                        <div class="form-line">
                           <div class="form-control"  style="border:1px solid #f0f0f0; border-radius:3px;">' . $requested_budget . '</div>
                        </div>
                     </div>
                     <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label for="projduration">Approved Budget *:</label>
                        <div class="form-line">
                           <input type="number"  name="projapprovedbudget" value="" id="projapprovedbudget" placeholder="Enter appoved budget for the year ' . $finyear . '" class="form-control" class="form-control" required />
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>';
      echo $TargetB;
   }

   if (isset($_POST["approvedbudget"])) {
      $projid = $_POST['projid'];
      $adjusted_amount = $_POST['projapprovedbudget'];
      $budgetyear = $_POST['budgetyear'];
      $userid = $_POST['user_name'];
      $date = date("Y-m-d");

      $update_approved_budget = $db->prepare("UPDATE `tbl_adp_projects_budget` SET adjusted_amount=:adjusted_amount, updated_by=:updated_by, date_updated=:date_updated WHERE projid=:projid AND year=:year");
      $results = $update_approved_budget->execute(array(":adjusted_amount" => $adjusted_amount, ":updated_by" => $userid, ":date_updated" => $date, ":projid" => $projid, ":year" => $budgetyear));


      if ($results === TRUE) {
         $projstage = 9;
         $adp_status = 1;

         $update_adp_status = $db->prepare("UPDATE `tbl_annual_dev_plan` SET status=:status, approved_by=:approved_by, date_approved=:date_approved WHERE projid=:projid AND financial_year=:year");
         $update_adp_status->execute(array(":status" => $adp_status, ":approved_by" => $userid, ":date_approved" => $date, ":projid" => $projid, ":year" => $budgetyear));

         $update_sub_stage = $db->prepare("UPDATE `tbl_projects` SET projstage=:projstage, updated_by=:updated_by, date_updated=:date_updated WHERE projid=:projid");
         $update_sub_stage->execute(array(":projstage" => $projstage, ":updated_by" => $userid, ":date_updated" => $date, ":projid" => $projid));

         $valid['success'] = true;
         $valid['messages'] = "Budget successfully approved";
      } else {
         $valid['success'] = false;
         $valid['messages'] = "Error while approving the budget!!";
      }
      echo json_encode($valid);
   }
} catch (PDOException $ex) {
   var_dump($ex);
   customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}
