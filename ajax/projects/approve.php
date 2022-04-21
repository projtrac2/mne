<?php 
//Include database configuration file 


include_once '../../projtrac-dashboard/resource/Database.php';
include_once '../../projtrac-dashboard/resource/utilities.php';
include_once("../../includes/system-labels.php");

if (isset($_POST["approveProj"]) && $_POST["approveProj"] == "approveProj") {
   $projid = $_POST['itemId'];

   $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid='$projid'");
   $query_rsProjects->execute();
   $row_rsProjects = $query_rsProjects->fetch();
   $totalRows_rsProjects = $query_rsProjects->rowCount();

   if ($totalRows_rsProjects > 0) {
      $progid = $row_rsProjects['progid'];
      $projname = $row_rsProjects['projname'];
      $projdurationInDays = $row_rsProjects['projduration'];
      $projfscyear = $row_rsProjects['projfscyear'];
      $projcode = $row_rsProjects['projcode'];
      $projbudget = $row_rsProjects['projbudget'];
      $projenddate = $row_rsProjects['projenddate'];
      $projstartdate = $row_rsProjects['projstartdate'];
      $projduration = $row_rsProjects['projduration'];

      $query_rsYear =  $db->prepare("SELECT id, yr FROM tbl_fiscal_year WHERE id = '$projfscyear'");
      $query_rsYear->execute();
      $row_rsYear = $query_rsYear->fetch();
      $projstartyear =  $row_rsYear['yr'];
      $projstart = $projstartyear  . '-07-01';

      //fetch program details 
      $query_item = $db->prepare("SELECT * FROM tbl_programs WHERE tbl_programs.progid = '$progid'");
      $query_item->execute();
      $row_item = $query_item->fetch();
      $program_type = $row_item['program_type'];

      $progstartDate = $row_item['syear'] . '-07-01'; //program start date  
      $progduration = $row_item['years']; //program duration in years 
      $sdate = $row_item['syear'] . '-06-30'; //for calculating program end year   
      $progendDate = date('Y-m-d', strtotime($sdate . " + {$progduration} years"));  //program end date 
      $projectendDate = date('Y-m-d', strtotime($projstart . " + {$projdurationInDays} days"));
      $yr = date("Y");
      $mnth = date("m");


      if ($mnth >= 7 && $mnth <= 12) {
         $year = $yr;
      } elseif ($mnth >= 1 && $mnth <= 6) {
         $year = $yr - 1;
      }

      $yearnxt = $year + 1;
      $finyear = $year . "/" . $yearnxt;

      //fetch program details 
      $query_projects = $db->prepare("SELECT sum(b.amount) as amount FROM tbl_project_approved_yearly_budget b INNER JOIN tbl_projects p ON p.projid = b.projid WHERE progid = '$progid' AND year='$year'");
      $query_projects->execute();
      $row_projects = $query_projects->fetch();
      $project_budget = $row_projects['amount'];

      //fetch program details 
      $query_programs = $db->prepare("SELECT  sum(budget) as budget FROM tbl_programs_based_budget WHERE progid = '$progid' and finyear='$year'");
      $query_programs->execute();
      $row_programs = $query_programs->fetch();
      $program_budget = $row_programs['budget'];
      $project_budget_ceiling = ($program_budget > $project_budget) ? $program_budget - $project_budget : 0;


      $query_rsProject_details = $db->prepare("SELECT SUM(budget) as budget FROM `tbl_project_details` WHERE projid ='$projid'");
      $query_rsProject_details->execute();
      $row_rsProject_details = $query_rsProject_details->fetch();
      $totalRows_rsProject_details = $query_rsProject_details->rowCount();
      $project_cost = ($row_rsProject_details['budget'] > 0) ? $row_rsProject_details['budget'] : 0;

      $budget_name = "";
      if ($projduration > 365) {
         $budget_name = '
         <div class="col-md-6">
            <label for="projduration"> Approved Budget Ceiling*:</label>
            <div class="form-line">
               <input type="text"  name="projapprovedbudget2" id="projapprovedbudget2" placeholder="" class="form-control" value="' . number_format($project_budget_ceiling, 2) . '" class="form-control"  disabled/>
            </div>
         </div>
         <div class="col-md-6">
            <label for="projduration">Final Approved Budget for the Financial Year ' . $finyear . '*:</label>
            <div class="form-line">
               <input type="text"  name="projapprovedbudget" onchnage="approved_budget()" onkeyup="approved_budget()" value="" id="projapprovedbudget" placeholder="Enter the project approved budget for the financial year ' . $finyear . '" class="form-control" class="form-control" required />					  
               <input type="hidden"  name="projapprovedbudget1" id="projapprovedbudget1" value="' . $project_budget_ceiling . '">
            </div>
         </div>';
      }

      $budget_name = $program_type == 1 ? $budget_name : "";

      $project_cost = '
      <div class="col-md-6">
         <label for="projduration">Project Cost *:</label>
         <div class="form-line">
            <input type="text"  name="project_cost1" id="project_cost1" placeholder="" class="form-control" value="' . number_format($project_cost, 2) . '" class="form-control"  disabled/>
            <input type="hidden"  name="project_cost" id="project_cost" placeholder="" class="form-control" value="' . $project_cost . '" class="form-control" />
         </div>
      </div>';

      $approve = '
      <div class="row clearfix">
         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card"> 
               <input type="hidden"  name="budgetyear" id="budgetyear" value="' . $year . '">
               <div class="header" >
                  <div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
                  <h5 class="list-group-item list-group-item list-group-item-action active"><strong>Project Name:</strong> ' . $projname . ' </h5> 
                  </div>
               </div>
               <div class="body"> 
               <div class="row clearfix">
                  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                  <label for="syear">Program Start Date *:</label>  
                  <div class="form-line">
                     <input type="hidden"  name="progid" id="progid" class="form-control" value="' . $progid . '" required>
                     <input type="hidden"  name="projid" id="projid" class="form-control" value="' . $projid . '" required>
                     <input type="hidden" name="projfscyear[]" id="projfscyear" value="' . $projfscyear . '" />
                     <input type="text" name="progstartyear" id="progstartyear" value="' . date('d M Y', strtotime($progstartDate)) . '"  class="form-control" disabled>
                  </div>
                  </div>  
                  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                  <label for="syear">Program End Date *:</label>  
                  <div class="form-line">
                     <input type="text" name="programendyear" id="programendyear" value="' . date('d M Y', strtotime($progendDate)) . '"  class="form-control" disabled>
                  </div>
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                  <label for="syear">Program Duration (Years)*:</label>  
                  <div class="form-line">
                     <input type="text" name="programduration" id="programduration" value="' . $progduration . '"  class="form-control" disabled>
                  </div>
                  </div>
               </div> 
               <div class="row clearfix">
                  <div class="col-md-4">
                     <label for="projstartYear">Project Tentative Start Date *:</label>
                     <div class="form-line">
                     <input type="text" name="projstartYears" id="startYear1" class="form-control"  value="' . date('d M Y', strtotime($projstart)) . '" disabled>
                     </div>
                  </div> 
                  <div class="col-md-4">
                     <label for="projendYear">Project Tentative End Date *:</label>
                     <div class="form-line"> 
                     <input type="text" name="projendYears" id="projendyearDate" class="form-control"  value="' . date('d M Y', strtotime($projectendDate)) . '"  disabled>
                     </div>
                  </div> 
                  <div class="col-md-4">
                  <label for="projduration">Project Duration (Days)*:</label>
                  <div class="form-line">
                     <input type="text"  name="projduration" id="projduration1" class="form-control" value="' . $projduration . '" required disabled>
                  </div>
                  </div> 
                  ' . $project_cost . $budget_name . '
               </div>
               </div>
            </div>
         </div>
      </div>
	  <div class="row clearfix" id="">
		  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card">
			  <div class="body">  
				<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover" id="approve_financier_table" style="width:100%">
							<thead>
								<tr>
									<th width="5%">#</th>
									<th width="25%">Source Category</th>
									<th width="25%">Financier</th>
									<th width="15%">Amount (Ksh)</th> 
									<th width="5%">
									  <button type="button" name="addplus" id="addplus" onclick="add_row_approve_financier();" class="btn btn-success btn-sm">
										  <span class="glyphicon glyphicon-plus"></span>
									  </button>
									</th> 
								</tr>
							</thead>
							<tbody id="approve_financier_table_body">
							  <tr></tr>
							  <tr id="remove_approve_tr"><td colspan="7">Add Financier </td></tr>
							</tbody>
						</table>
					</div>
				</div> 
			  </div>
			</div>
		  </div>
	  </div>
 
	  <div class="row clearfix " id="">
		  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card">
			  <div class="header">
				  <h5 class="list-group-item list-group-item list-group-item-action active">
				  <strong> Add new file/s </strong></h5> 
			  </div>
			  <div class="body"> 
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
					<tbody id="meetings_table">
					  <tr></tr> 
					  <tr id="add_new_file">
						<td colspan="4"> Add file </td>
					  </tr>  
					</tbody>
				  </table>
				</div>
			  </div>
			</div>
		  </div>
	  </div>';

      echo $approve;
   }
}

//approve item
if (isset($_POST["approveitem"])) {
   $projid = $_POST['projid'];
   $progid = $_POST['progid'];
   $approved = $_POST['approveitem'];
   $user_name = $_POST['user_name'];
   $year = $_POST['budgetyear'];
   $date = date("Y-m-d");

   $query_rsProjects = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' and projid='$projid'");
   $query_rsProjects->execute();
   $row_rsProjects = $query_rsProjects->fetch();
   $totalRows_rsProjects = $query_rsProjects->rowCount();
   $projplanstatus = $row_rsProjects['projplanstatus'];
   $progid = $row_rsProjects['progid'];


   //fetch program details 
   $query_item = $db->prepare("SELECT * FROM tbl_programs WHERE tbl_programs.progid = '$progid'");
   $query_item->execute();
   $row_item = $query_item->fetch();
   $program_type = $row_item['program_type'];

   if ($projplanstatus > 0) {
      $valid['success'] = true;
      $valid['messages'] = "Successfully Approved Project";
   } else {
      if (isset($_POST['amountfunding'])) {
         for ($i = 0; $i < count($_POST['amountfunding']); $i++) {
            $sourcecategory = $_POST['source_category'][$i];
            $source = $_POST['source'][$i];
            $amountfunding = $_POST['amountfunding'][$i];
            $insertSQL1 = $db->prepare("INSERT INTO `tbl_myprojfunding`(progid, projid, sourcecategory, financier,  amountfunding, created_by, date_created) VALUES(:progid, :projid, :sourcecategory,:financier, :amountfunding, :created_by, :date_created)");
            $result1  = $insertSQL1->execute(array(":progid" => $progid, ":projid" => $projid, ":sourcecategory" => $sourcecategory, ":financier" => $source, ":amountfunding" => $amountfunding, ":created_by" => $user_name, ":date_created" => $date));
         }
      }

      if (isset($_POST['attachmentpurpose'])) {
         $countP = count($_POST["attachmentpurpose"]);
         $stage = 2;
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
                     $results = "<script type=\"text/javascript\">
                                    swal({
                                    title: \"Error!\",
                                    text: \" $msg \",
                                    type: 'Danger',
                                    timer: 10000,
                                    showConfirmButton: false });
                                </script>";
                  }
               } else {
                  $type = 'error';
                  $msg = 'This file type is not allowed, try another file!!';
                  $results = "<script type=\"text/javascript\">
                                swal({
                                title: \"Error!\",
                                text: \" $msg \",
                                type: 'Danger',
                                timer: 10000,
                                showConfirmButton: false });
                            </script>";
               }
            }
         }
      }

      $query_rsProject_details = $db->prepare("SELECT SUM(budget) as budget FROM `tbl_project_details` WHERE projid ='$projid'");
      $query_rsProject_details->execute();
      $row_rsProject_details = $query_rsProject_details->fetch();
      $totalRows_rsProject_details = $query_rsProject_details->rowCount();

      $projcost = ($totalRows_rsProject_details > 0) ? $row_rsProject_details['budget'] : 0;
      $projbudget = isset($_POST['projapprovedbudget']) ? $_POST['projapprovedbudget'] : $projcost;
      $stage = 2;
      $status = 1;
      $user_name = 1;

      $insertSQL1 = $db->prepare("INSERT INTO `tbl_project_approved_yearly_budget`(projid, year, amount, created_by, date_created) VALUES(:projid, :year,:amount, :created_by, :date_created)");
      $result1  = $insertSQL1->execute(array(":projid" => $projid, ":year" => $year, ":amount" => $projbudget, ":created_by" => $user_name, ":date_created" => $date));

      $approveItemQuery = $db->prepare("UPDATE `tbl_projects` SET projcost=:projcost,  projplanstatus=:approved, projstage=:stage, approved_date=:approved_date, approved_by=:approved_by WHERE projid=:projid");
      $approveItemQuery->execute(array(":projcost" => $projcost, ":approved" => $approved, ":stage" => $stage, ":approved_date" => $date, ":approved_by" => $user_name, ':projid' => $projid));

      if ($program_type == 1) {
         $approveQuery = $db->prepare("UPDATE `tbl_annual_dev_plan` SET status=:status, approved_by=:approved_by, date_approved=:approved_date WHERE projid=:projid");
         $approved = $approveQuery->execute(array(':status' => $status, ":approved_by" => $user_name, ":approved_date" => $date, ':projid' => $projid));
      }

      if ($approved) {
         $valid['success'] = true;
         $valid['messages'] = "Successfully Approved Project";
      } else {
         $valid['success'] = false;
         $valid['messages'] = "Error while approving!!";
      }
   }
   echo json_encode($valid);
}

if (isset($_POST['unapproveitem'])) { 
   $projid = $_POST['itemId'];

   // delete tbl_project_output_details table 
   $deleteQuery = $db->prepare("DELETE FROM `tbl_myprojfunding` WHERE projid=:projid");
   $results = $deleteQuery->execute(array(':projid' => $projid));


   // delete tbl_project_output_details table 
   $deleteQuery = $db->prepare("DELETE FROM `tbl_files` WHERE projid=:projid AND projstage=2");
   $results = $deleteQuery->execute(array(':projid' => $projid));

   // delete tbl_project_output_details table 
   $deleteQuery = $db->prepare("DELETE FROM `tbl_project_approved_yearly_budget` WHERE projid=:projid ");
   $results = $deleteQuery->execute(array(':projid' => $projid));

   $approveItemQuery = $db->prepare("UPDATE `tbl_projects` SET projcost=:projcost,  projplanstatus=:approved, projstage=:stage, approved_date=:approved_date, approved_by=:approved_by WHERE projid=:projid");
  $approved = $approveItemQuery->execute(array(":projcost" => 0, ":approved" => 0, ":stage" => 1, ":approved_date" => null, ":approved_by" => 0, ':projid' => $projid));

   // $approved = "";
   // if ($program_type == 1) {
   //    $approveQuery = $db->prepare("UPDATE `tbl_annual_dev_plan` SET status=:status, approved_by=:approved_by, date_approved=:approved_date WHERE projid=:projid");
   //    $approved = $approveQuery->execute(array(':status' => 0, ":approved_by" => 0, ":approved_date" => null, ':projid' => $projid));
   // }

   if ($approved) {
      $valid['success'] = true;
      $valid['messages'] = "Successfully unapproved Project";
   } else {
      $valid['success'] = false;
      $valid['messages'] = "Error while unapproving!!";
   }

   echo json_encode($valid);
}

if (isset($_POST['get_category'])) {
   $projid = $_POST['projid'];
   $query_rsFunding =  $db->prepare("SELECT p.amountfunding, p.sourcecategory, f.type  FROM tbl_projfunding p INNER JOIN tbl_funding_type f ON  p.sourcecategory= f.id WHERE projid =:projid");
   $query_rsFunding->execute(array(":projid" => $projid));
   $row_rsFunding = $query_rsFunding->fetch();
   $totalRows_rsFunding = $query_rsFunding->rowCount();
   $inputs = '<option value="">Select Category from list</option>';

   if ($totalRows_rsFunding > 0) {
      do {
         $source_category = $row_rsFunding['sourcecategory'];
         $source_name = $row_rsFunding['type'];
         $progfunds = $row_rsFunding['amountfunding'];
         $inputs .= '<option value="' . $source_category . '">' . $source_name . '</option>';
      } while ($row_rsFunding = $query_rsFunding->fetch());
   }
   echo $inputs;
}

if (isset($_POST['get_source'])) {
   $inputs = '<option value="">Select Source list</option>';
   $source_category = $_POST['sourcecategory']; 

   $query_rsFunder = $db->prepare("SELECT * FROM tbl_financiers WHERE type=:sourcecategory");
   $query_rsFunder->execute(array(":sourcecategory" => $source_category));
   $row_rsFunder = $query_rsFunder->fetch();
   $totalRows_rsFunder = $query_rsFunder->rowCount();

   do {
      $fndid = $row_rsFunder['id'];
      $funder = $row_rsFunder['financier'];
      
      $inputs .= '<option value="' . $fndid . '">' . $funder . '</option>';
   } while ($row_rsFunder = $query_rsFunder->fetch());
   $arr = array("source" => $inputs);
   echo json_encode($arr);
}