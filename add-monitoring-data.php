<?php
$pageName = "Strategic Plans";
$replacement_array = array(
    'planlabel' => "CIDP",
    'plan_id' => base64_encode(6),
);

$page = "view";

require('includes/head.php');
if ($permission) {
    $pageTitle ="Monitor Project";
    try {

      $editFormAction = $_SERVER['PHP_SELF'];
      if (isset($_SERVER['QUERY_STRING'])) {
        $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
      }

      $currentdate = date("Y-m-d");
      $results = '';

      $validate = false;
      $projname = $projcode = $projstatus = $locationName = '';

      if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "pmfrm")) {
        if (trim($_POST["mainformid"]) !== '' || !empty(trim($_POST["mainformid"]))) {
          $projid = $_POST['projid'];
          $mainformid = $_POST['mainformid'];
          $latitude = '12345677'; //$_POST['latitude'];
          $longitude = '23536788'; //$_POST['longitude'];
          $geoerror = '';
          if (isset($_POST['geoerror'])) {
            $geoerror = $_POST['geoerror'];
          }
     ;
          $level3 = $_POST['lev3id'];
          $lev4id = NULL;
          $location = NULL;
          if (isset($_POST['lev4id'])) {
            $lev4id = $_POST['lev4id'];
            $location = $_POST['lev4id'];
          }

          $insertSQL = $db->prepare("INSERT INTO tbl_monitoring (projid, formid, projlatitude, projlongitude, projgeopositionerror,level3,location, adate, user_name) VALUES (:projid, :formid, :projlat, :projlong, :projgeopositionerror,:level3,:location, :adate, :username)");
          $Result1  = $insertSQL->execute(array(":projid" => $projid, ":formid" => $mainformid, ':projlat' => $latitude, ':projlong' => $longitude, ':projgeopositionerror' => $geoerror, ":level3" => $level3, ":location" => $location,   ":adate" => $currentdate, ":username" => $user_name));

          $last_id = $db->lastInsertId(); // get the project id
          $count = count($_POST["opdetailsid"]);

          for ($k = 0; $k < $count; $k++) {
            $opid = $_POST['opdetailsid'][$k];
            $countp = count($_POST["outputprogress$opid"]);
            for ($j = 0; $j < $countp; $j++) {
              $key = $_POST["key$opid"][$j];
              $insertquery = $db->prepare("INSERT INTO tbl_monitoringoutput (monitoringid, projid, formid, opid, level3, level4, key_unique, actualoutput, created_by, date_created) VALUES (:monitoringid, :projid, :formid, :opid, :level3, :location, :key, :output, :username, :adate)");
              $insertquery->execute(array(":monitoringid" => $last_id, ":projid" => $projid, ":formid" => $mainformid, ':opid' => $_POST['opid'][$k], ":level3" => $level3, ":location" => $location, ":key" => $key, ':output' =>  $_POST["outputprogress$opid"][$j], ":username" => $user_name, ":adate" => $currentdate));
            }
          }

          if ($Result1) {
            $origin = "monitoring";
            if ($_POST["opdetailsid"] !== '' || !empty($_POST["opdetailsid"])) {
              $opcount = count($_POST["opdetailsid"]);
              for ($j = 0; $j < $opcount; $j++) {
                $projopid = $_POST['opdetailsid'][$j];
                $opdetailsid = $_POST["opdetailsid"][$j];
                if ($_POST["progress" . $projopid] !== '' || !empty($_POST["progress" . $projopid])) {
                  $number = count($_POST["progress" . $projopid]);
                  for ($i = 0; $i < $number; $i++) {
                    $tskPrgid = $_POST['tskid' . $projopid][$i];
                    $tskPrg = trim($_POST['progress' . $projopid][$i]);

                    $query_rsTasks = $db->prepare("SELECT * FROM tbl_task WHERE tkid = :tkid");
                    $query_rsTasks->execute(array(":tkid" => $tskPrgid));
                    $row_rsTasks = $query_rsTasks->fetch();
                    $taskProgress = $row_rsTasks["progress"];
                    $taskStatus = $row_rsTasks["status"];
                    $msid = $row_rsTasks["msid"];

                    $SQLinsert = $db->prepare("INSERT INTO tbl_task_progress (monitoringid, formid, opid, opdetailsid, tkid, progress, level3, level4, date) VALUES (:monitoringid, :formid, :opid, :opdetailsid, :tkid, :progress, :lv3, :lv4, :date)");
                    $Rst  = $SQLinsert->execute(array(":monitoringid" => $last_id, ':formid' => $mainformid, ":opid" => $projopid, ":opdetailsid" => $opdetailsid, ":tkid" => $tskPrgid, ':progress' => $tskPrg, ":lv3" => $level3, ":lv4" => $location, ":date" => $currentdate));

                    if (!$Rst) {
                      $msg = 'Can not insert progress!!';
                      $results =
                        "<script type=\"text/javascript\">
                        swal({
                          title: \"Error!\",
                          text: \" $msg \",
                          type: 'Danger',
                          timer: 10000,
                          icon:'error',
                          showConfirmButton: false
                        });
                      </script>";
                    }
                  }
                }

                if (isset($_POST["issuedescription" . $projopid])) {
                  $nmb = count($_POST["issuedescription" . $projopid]);
                  for ($k = 0; $k < $nmb; $k++) {
                    if (trim($_POST["issuedescription" . $projopid][$k]) !== '' || !empty(trim($_POST["issuedescription" . $projopid][$k]))) {
                      $SQLinsert = $db->prepare("INSERT INTO tbl_projissues (monitoringid, formid, projid, origin, opid, risk_category, observation, created_by, date_created) VALUES (:monitoringid, :formid, :projid, :origin, :opid, :riskcat, :obsv, :user, :date)");
                      $Rst  = $SQLinsert->execute(array(":monitoringid" => $last_id, ":formid" => $mainformid, ":projid" => $projid, ':origin' => $origin, ':opid' => $projopid, ':riskcat' => $_POST['issue' . $projopid][$k], ':obsv' => $_POST['issuedescription' . $projopid][$k], ':user' => $user_name, ':date' => $currentdate));
                    }
                  }
                }

                if (isset($_POST['observation' . $projopid])) {
                  $observ = $_POST['observation' . $projopid];
                  $SQLinsert = $db->prepare("INSERT INTO tbl_monitoring_observations (projid, monitoringid, formid, opid, observation, created_by, date_created) VALUES (:projid, :monitoringid, :formid, :opid, :observ, :user, :date)");
                  $Rst  = $SQLinsert->execute(array(":projid" => $projid, ":monitoringid" => $last_id, ":formid" => $mainformid, ':opid' => $projopid, ':observ' => $observ, ':user' => $user_name, ':date' => $currentdate));
                }

                $nmb1 = count($_POST["monitorurl" . $projopid]);
                for ($q = 0; $q < $nmb1; $q++) {
                  if (trim($_POST["monitorurl" . $projopid][$q]) !== '' || !empty(trim($_POST["monitorurl" . $projopid][$q]))) {
                    $SQLinsert = $db->prepare("INSERT INTO tbl_monitoring_links (projid, monitoringid, formid, opid, url, urlpurpose) VALUES (:projid, :monitoringid, :formid, :opid, :monitorurl, :urlpurpose)");
                    $Rst  = $SQLinsert->execute(array(":projid" => $projid, ":monitoringid" => $last_id, ":formid" => $mainformid, ':opid' => $projopid, ':monitorurl' => $_POST['monitorurl' . $projopid][$q], ':urlpurpose' => $_POST['attachmentpurposeurl' . $projopid][$q]));
                  }
                }


                $stage = 10;
                $myprojid = $projid;
                $filecategory = "Monitoring";
                $count = count($_POST["attachmentpurpose" . $projopid]);

                for ($cnt = 0; $cnt < $count; $cnt++) {
                  if (!empty($_FILES['monitorattachment' . $projopid]['name'][$cnt])) {
                    $purpose = $_POST["attachmentpurpose" . $projopid][$cnt];
                    $filename = basename($_FILES['monitorattachment' . $projopid]['name'][$cnt]);
                    $ext = substr($filename, strrpos($filename, '.') + 1);
                    if (($ext != "exe") && ($_FILES["monitorattachment" . $projopid]["type"][$cnt] != "application/x-msdownload")) {
                      $newname = $projid . "-" . $filecategory . "-" . $projopid . "-" . $filename;
                      if ($ext == "jpg" || $ext == "png" || $ext == "jpeg") {
                        $filepath = "uploads/monitoring/photos/" . $newname;
                        if (!file_exists($filepath)) {
                          if (move_uploaded_file($_FILES['monitorattachment' . $projopid]['tmp_name'][$cnt], $filepath)) {
                            $qry2 = $db->prepare("INSERT INTO tbl_project_photos (projid, monitoringid, form_id, opid, projstage, fcategory, filename, ftype, description, floc, uploaded_by, date_uploaded) VALUES (:projid,:monitoringid,:formid,:opid, :stage, :fcat, :filename, :ftype, :desc, :floc, :user, :date)");
                            $data = $qry2->execute(array(':projid' => $projid, ':monitoringid' => $last_id, ':formid' => $mainformid, ":opid" => $projopid, ':stage' => $stage, ':fcat' => $filecategory, ':filename' => $newname, ":ftype" => $ext, ":desc" => $purpose, ":floc" => $filepath, ':user' => $user_name, ':date' => $currentdate));

                            if($data){
                              var_dump("Winning inserted into db");
                            }else{
                              var_dump("There was an error inserting data into the db");
                            }

                            echo  "<pre/>";
                            var_dump($filename);
                          } else {
                            echo "Could not move the file";
                          }
                        } else {
                          $results =
                            "<script type=\"text/javascript\">
                            swal({
                              title: \"Error!\",
                              text: \" $msg \",
                              type: 'Danger',
                              timer: 10000,
                              icon:'error',
                              showConfirmButton: false
                            });
                          </script>";
                        }
                      } else {
                        $filepath = "uploads/monitoring/other-files/" . $newname;
                        if (!file_exists($filepath)) {
                          if (move_uploaded_file($_FILES['monitorattachment' . $projopid]['tmp_name'][$cnt], $filepath)) {
                            $qry2 = $db->prepare("INSERT INTO tbl_files (projid, projstage, monitoringid, form_id, filename, ftype, floc, fcategory, reason, uploaded_by, date_uploaded)  VALUES (:projid, :projstage, :monitoringid, :formid, :filename, :ftype, :floc, :fcat, :desc, :user, :date)");
                            $result =  $qry2->execute(array(':projid' => $projid, ':projstage' => $stage, ':monitoringid' => $last_id, ':formid' => $mainformid, ':filename' => $newname, ":ftype" => $ext, ":floc" => $filepath, ':fcat' => $filecategory, ":desc" => $purpose, ':user' => $user_name, ':date' => $currentdate));
                          } else {
                            echo "could not move the file ";
                          }
                        } else {
                          $msg = 'File you are uploading already exists, try another file!!';
                          $results =
                            "<script type=\"text/javascript\">
                            swal({
                              title: \"Error!\",
                              text: \" $msg \",
                              type: 'Danger',
                              timer: 10000,
                              icon:'error',
                              showConfirmButton: false
                            });
                          </script>";
                        }
                      }
                    } else {
                      $msg = 'This file type is not allowed, try another file!!';
                      $results =
                        "<script type=\"text/javascript\">
                        swal({
                          title: \"Error!\",
                          text: \" $msg \",
                          type: 'Danger',
                          timer: 10000,
                          icon:'error',
                          showConfirmButton: false
                        });
                      </script>";
                    }
                  } else {
                    $msg = 'You have not attached any file!!';
                    $results = "<script type=\"text/javascript\">
    								swal({
                      title: \"Error!\",
                      text: \" $msg \",
                      type: 'Danger',
                      timer: 10000,
                      icon:'error',
                      showConfirmButton: false
                    });
    							  </script>";
                  }
                }

                $msg = 'The Project Successfully Monitored.';
                $results =
                  "<script type=\"text/javascript\">
                    swal({
                      title: \"Success!\",
                      text: \" $msg\",
                      type: 'Success',
                      timer: 5000,
                      icon:'error',
                      showConfirmButton: false
                    });
                      setTimeout(function(){
                      window.location.href = 'projects-monitoring';
                      }, 5000);
                </script>";
              }
            }
          }
        } else {
          $type = 'error';
          $msg = 'Please fill all mandatory fields and try again.';
          $results = "<script type=\"text/javascript\">
    			  swal({
    			  title: \"Error!\",
    			  text: \" $msg \",
    			  type: 'Danger',
    			  timer: 10000,
            icon:'error',
    			  showConfirmButton: false });
    			</script>";
        }
      }

      function makeTree($indid)
      {
        global $db;
        $query_rsdata = $db->prepare("SELECT disaggregation_type as id, parent, t.category as name
          FROM tbl_indicator_measurement_variables_disaggregation_type m
          INNER JOIN tbl_indicator_disaggregation_type t ON t.id = m.disaggregation_type
          WHERE indicatorid ='$indid'");
        $query_rsdata->execute();
        $indparendata = $query_rsdata->rowCount();
        $data = $query_rsdata->fetchAll();

        $tree = array(
          array('id' => 'root', 'parent' => -1, "name" => 'name', 'children' => array())
        );
        $treePtr = array(0 => &$tree[0]);
        foreach ($data as $item) {
          $children = &$treePtr[$item['parent']]['children'];
          if ($children  != '') {
            $c = count($children);
            $children[$c] = $item;
            $children[$c]['children'] = array();
            $treePtr[$item['id']] = &$children[$c];
            $treePtr[$item['name']] = &$children[$c];
          }
        }
        return $tree;
      }

      function printNode($node, $level = 0, $islast = false, $element_arr = array())
      {
        global $db, $indid, $opdetailsid, $opprogress, $totalSum;
        $id = $node['id'];
        $depth = $node['parent'];
        $total = count($node);
        static $c = 0;
        $last_id = end($node);
        if (empty($last_id)) {
          $total = count($node);
          $c = null;
          $c = $total;
          if ($id != "root" && $id != 1) {
            $query_rschild = $db->prepare("SELECT * FROM tbl_indicator_disaggregations  WHERE  disaggregation_type='$id' AND indicatorid='$indid' ORDER BY id");
            $query_rschild->execute();
            $indparenchild = $query_rschild->rowCount();
            $row = $query_rschild->fetch();
            print("<div class='row clearfix'>");
            do {
              $disa = $row['id'];
              $data = end($element_arr);
              $flex = $data;

              if (!$data) {
                $flex = 0;
              }

              print('
                  <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4">
                    <div class="form-input">
                      <span for="" id="" >Cumulative Measurement *:</span>
                      <input type="text" value="' . number_format($totalSum) . '" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" readonly/>
                    </div>
                  </div>
                  <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4">
                    <div class="form-input">
                      <span for="" id="" >Previous Measurement *:</span>
                      <input type="text" value="' . $opprogress . '" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" readonly/>
                    </div>
                  </div>
                  <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4" id="">
                    <span for="" id="" >' . $row['disaggregation'] . ' *:</span>
                    <div class="form-input">
                      <input type="hidden" name="key' . $opdetailsid . '[]" value="' . $flex . '" id="">
                      <input type="number" name="outputprogress' . $opdetailsid . '[]" placeholder="Enter current progress value" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required="required" />
                    </div>
                  </div>');
              foreach ($node['children'] as $child) {
                printNode($child, $level = 0, $islast = false, $element_arr);
              }
            } while ($row = $query_rschild->fetch());
            print("</div>");
          } else {
            $data = 1;
            print('
              <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4">
                <div class="form-input">
                  <span for="" id="" >Cumulative Measurement *:</span>
                  <input type="text" value="' . number_format($totalSum) . '" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" readonly/>
                </div>
              </div>
              <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4">
                <div class="form-input">
                  <span for="" id="" >Previous Measurement *:</span>
                  <input type="text" value="' . number_format($opprogress) . '" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" readonly/>
                </div>
              </div>
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4" id="">
                  <div class="form-input">
                    <span for="" id="" >Location *:</span>
                    <input type="hidden" name="key' . $opdetailsid . '[]" value="' . $data . '" id="">
                    <input type="number" name="outputprogress' . $opdetailsid . '[]" placeholder="Enter current progress value" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required="required" />
                  </div>
                </div>');
            foreach ($node['children'] as $child) {
              printNode($child, $level = 0, $islast = false, $element_arr);
            }
          }
        } else {
          if ($id != "root" &&  $id != 1) {
            $query_rschild = $db->prepare("SELECT * FROM tbl_indicator_disaggregations  WHERE  disaggregation_type='$id' AND indicatorid='$indid' ORDER BY id");
            $query_rschild->execute();
            $indparenchild = $query_rschild->rowCount();
            $row = $query_rschild->fetch();
            do {
              foreach ($node['children'] as $child) {
                $islast1 = false;
                if (($c == $total)) {
                  $islast1 = true;
                } else {
                  $c++;
                }
                array_push($element_arr, $row['id']);
                printNode($child, $respondent, $placeholder, $level++, $islast1, $element_arr);
              }
            } while ($row = $query_rschild->fetch());
          } else {
            foreach ($node['children'] as $child) {
              $islast1 = false;
              if (($c == $total)) {
                $islast1 = true;
              } else {
                $c++;
              }
              array_push($element_arr, '');
              printNode($child, $level++, $islast1, $element_arr);
            }
          }
        }
      }


      function non_disaggregated($opdetailsid)
      {
        $data = 1;
        global $opprogress, $outputname, $totalSum;
        echo '
          <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4">
            <div class="form-input">
              <span for="" id="" >Cumulative Measurement *:</span>
              <input type="text" value="' . number_format($totalSum) . '" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" readonly/>
            </div>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4">
            <div class="form-input">
              <span for="" id="" >Previous Measurement :</span>
              <input type="text" value="' . $opprogress . '" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" readonly/>
            </div>
          </div>
    	<div class="col-lg-3 col-md-3 col-sm-4 col-xs-4">
    	  <div class="form-input">
    		<span for="" id="" >' . $outputname . ' *:</span>
    		<input type="hidden" name="key' . $opdetailsid . '[]" value="' . $data . '" id="">
    		<input type="number" name="outputprogress' . $opdetailsid . '[]" placeholder="Enter current progress value" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" required="required" />
    	  </div>
    	</div> ';
      }

      function validate($projid, $location)
      {
        global $db, $currentdate, $user_name;
        // disaggregated
        $query_rsDissOutput_details = $db->prepare("SELECT * FROM tbl_project_outputs_mne_details d  INNER JOIN tbl_projects_location_targets t ON d.outputid = t.outputid WHERE d.projid = :projid AND t.responsible = :responsible ORDER BY opid ASC");
        $query_rsDissOutput_details->execute(array(":projid" => $projid, ":responsible" => $user_name));
        $row_rsDissOutput_details = $query_rsDissOutput_details->fetch();
        $totalRows_rsDissOutput_details = $query_rsDissOutput_details->rowCount();

        // non disaggregated
        $query_rsIndOutput_details = $db->prepare("SELECT * FROM tbl_project_outputs_mne_details d  INNER JOIN tbl_output_disaggregation t ON d.outputid = t.outputid WHERE d.projid = :projid AND t.responsible = :responsible AND outputstate =:location ORDER BY opid ASC");
        $query_rsIndOutput_details->execute(array(":projid" => $projid, ":responsible" => $user_name, ":location" => $location));
        $row_rsIndOutput_details = $query_rsIndOutput_details->fetch();
        $totalRows_rsIndOutput_details = $query_rsIndOutput_details->rowCount();


        if ($totalRows_rsIndOutput_details > 0 || $totalRows_rsDissOutput_details > 0) {
          return true;
        } else {
          return false;
        }
      }

      function incrementalHash($len = 5)
      {
        $charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $base = strlen($charset);
        $result = '';

        $now = explode(' ', microtime())[1];
        while ($now >= $base) {
          $i = $now % $base;
          $result = $charset[$i] . $result;
          $now /= $base;
        }
        return substr($result, -5);
      }

      $pmtid = incrementalHash();

      $locate = '';
      if (isset($_GET['proj']) && isset($_GET['level3']) && isset($_GET['level4'])) {
        $projid = base64_decode(htmlspecialchars(trim($_GET['proj'])));
        $location = base64_decode(htmlspecialchars(trim($_GET['level4'])));
        $level3 = base64_decode(htmlspecialchars(trim($_GET['level3'])));

        $locate = $level3;
        if ($location > 0) {
          $locate = $location;
        }

        $handler = validate($projid, $location);
        $handler = true;
        if ($handler) {
          $validate = true;
          $query_rsProject = $db->prepare("SELECT * FROM tbl_projects WHERE projid = :projid");
          $query_rsProject->execute(array(":projid" => $projid));
          $row_rsProject = $query_rsProject->fetch();
          $totalRows_rsProject = $query_rsProject->rowCount();
          $projname = $row_rsProject['projname'];
          $projcode = $row_rsProject['projcode'];
          $projstatus = $row_rsProject['projstatus'];

          // project status
          $query_rsstatus = $db->prepare("SELECT * FROM tbl_status WHERE statusid = :projstatus");
          $query_rsstatus->execute(array(":projstatus" => $projstatus));
          $row_rsstatus = $query_rsstatus->fetch();
          $totalRows_rsstatus = $query_rsstatus->rowCount();
          $status_of_project = $row_rsstatus['statusname'];


          // project percentage progress
          $query_rsMlsProg =  $db->prepare("SELECT COUNT(*) as nmb, SUM(progress) AS mlprogress FROM tbl_milestone WHERE projid = :projid");
          $query_rsMlsProg->execute(array(":projid" => $projid));
          $row_rsMlsProg = $query_rsMlsProg->fetch();
          $prjprogress = $row_rsMlsProg["mlprogress"] / $row_rsMlsProg["nmb"];
          $percent2 = round($prjprogress, 2);

          // get level 3
          $query_rslevel3 = $db->prepare("SELECT * FROM tbl_state WHERE id = :location");
          $query_rslevel3->execute(array(":location" => $level3));
          $row_rslevel3 = $query_rslevel3->fetch();
          $totalRows_rslevel3 = $query_rslevel3->rowCount();
          $level3Name = $row_rslevel3['state'];


          $level4string = '';
          if ($location > 0) {
            $query_rslevel4 = $db->prepare("SELECT * FROM tbl_indicator_level3_disaggregations WHERE id = :lev4id");
            $query_rslevel4->execute(array(":lev4id" => $location));
            $row_rslevel4 = $query_rslevel4->fetch();
            $totalRows_rslevel4 = $query_rslevel4->rowCount();
            $level4 = $row_rslevel4['disaggregations'];
            $level4string = '
            <div  class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
              <label>Level 4:</label>
              <div>
                <input name="projlocation" type="text" id="projstatus" class="form-control" value="' . $level4 . '" readonly="readonly" style="border:#CCC thin solid; border-radius: 5px"/>
                <input type="hidden" name="lev4id" id="lev4id" value="' . $location . '"/>
              </div>
            </div>';
          }
        } else {
          $msg = 'You cannot monitor the project.';
          $results = "<script type=\"text/javascript\">
              swal({
                title: \"Success!\",
                text: \" $msg\",
                type: 'Success',
                timer: 5000,
                showConfirmButton: false });
              setTimeout(function(){
                window.location.href = 'projects-monitoring';
              }, 5000);
            </script>";
        }

        function risk_category_select_box($opdetailsid)
        {
          global $db, $projid;
          $risk = '';
          $query_allrisks = $db->prepare("SELECT C.rskid, C.category FROM tbl_projrisk_categories C INNER JOIN tbl_projectrisks R ON C.rskid=R.rskid where R.projid = :projid and R.outputid = :opid and R.type=3 ORDER BY R.id ASC");
          $query_allrisks->execute(array(":projid" => $projid, ":opid" => $opdetailsid));
          $rows_allrisks = $query_allrisks->fetchAll();
          foreach ($rows_allrisks as $row) {
            $risk .= '<option value="' . $row["rskid"] . '">' . $row["category"] . '</option>';
          }
          return $risk;
        }
      } else {
        $msg = 'You cannot monitor the project.';
        $results = "<script type=\"text/javascript\">
    				swal({
    					title: \"Success!\",
    					text: \" $msg\",
    					type: 'Success',
    					timer: 5000,
    					showConfirmButton: false });
    				setTimeout(function(){
    					window.location.href = 'projects-monitoring';
    				}, 5000);
    			</script>";
      }

    } catch (PDOException $ex) {

      $result = flashMessage("An error occurred: " . $ex->getMessage());
      echo $result;
    }
?>
<link rel="stylesheet" href="assets/custom-css/add-monitoring-data.css">
<style>
  .bar {
    background: #CDDC39;
    width: <?php echo $percent2; ?>%;
    height: 24px;
    -moz-border-radius: 0px;
    -webkit-border-radius: 0px;
  }

  .cornflowerblue {
    background-color: CornflowerBlue;
    box-shadow: inset 0px 0px 6px 2px rgba(255, 255, 255, .3);
    width: <?php echo $percent2;
            ?>%;
  }
</style>
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
                          if ($validate) {
                            ?>
                            <form id="pmfrm" name="pmfrm" method="POST" action="" style="width:100%" enctype="multipart/form-data" autocomplete="off">
                              <fieldset class="scheduler-border">
                                <legend class="scheduler-border" style="background-color:#c7e1e8;  border:#CCC thin dashed; border-radius:3px">Project Details</legend>
                                <p id="geoposx"></p>
                                <p id="geoposy"></p>
                                <p id="geoposz"></p>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                  <label>Project Name:</label>
                                  <input type="text" class="form-control" value="<?php echo $projname; ?>" readonly="readonly" style="border:#CCC thin solid; border-radius: 5px" />
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                  <label><?= $level3label ?>:</label>
                                  <div>
                                    <input name="projlocation" type="text" id="projstatus" class="form-control" value="<?php echo $level3Name; ?>" readonly="readonly" style="border:#CCC thin solid; border-radius: 5px" />
                                    <input type="hidden" name="lev3id" id="lev3id" value="<?php echo $level3; ?>" />
                                  </div>
                                </div>
                                <?= $level4string ?>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                  <label>Project Status:</label>
                                  <div>
                                    <input name="projstatus" type="hidden" id="projstatus" value="<?php echo $projstatus; ?>" readonly="readonly" />
                                    <input name="projstatus" type="text" id="projstatus" class="form-control" value="<?php echo $status_of_project; ?>" readonly="readonly" style="border:#CCC thin solid; border-radius: 5px" />
                                  </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                  <label>Project Progress: </label>
                                  <div style="height:25px">
                                    <div class="barBg" style="background-color:#CDDC39; margin-top:0px; width:100%; border-radius:5px">
                                      <div class="bar hundred cornflowerblue">
                                        <div id="label" class="barFill" style="margin-top:0px; border-radius:1px"><?php echo $percent2 ?>%</div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <?php
                              $username = 15;
                              $query_rsOutput_details = $db->prepare("SELECT * FROM tbl_project_outputs_mne_details WHERE projid = :projid ORDER BY opid ASC");
                              $query_rsOutput_details->execute(array(":projid" => $projid));
                              $row_rsOutput_details = $query_rsOutput_details->fetch();
                              $totalRows_rsOutput_details = $query_rsOutput_details->rowCount();
                              $nm = 0;
                              if ($totalRows_rsOutput_details > 0) {
                                do {
                                  $indid = $row_rsOutput_details['indicator'];
                                  $opdetailsid = $row_rsOutput_details['outputid'];

                                  $opid = $opdetailsid;
                                  $query_cummOP = $db->prepare("SELECT sum(actualoutput) as total FROM tbl_monitoringoutput WHERE projid=:projid AND opid=:opid ");
                                  $query_cummOP->execute(array(":projid" => $projid, ":opid" => $opdetailsid));
                                  $row_cummOP = $query_cummOP->fetch();
                                  $cummvalue = 0;
                                  if ($row_cummOP['total'] > 0) {
                                    $cummvalue = $row_cummOP['total'];
                                  }

                                  $query_rsIndicator = $db->prepare("SELECT * FROM tbl_indicator WHERE indid = :indid");
                                  $query_rsIndicator->execute(array(":indid" => $indid));
                                  $row_rsIndicator = $query_rsIndicator->fetch();
                                  $totalRows_rsIndicator = $query_rsIndicator->rowCount();
                                  $indicator = $row_rsIndicator['indicator_name'];
                                  $unitid = $row_rsIndicator['indicator_unit'];
                                  $disaggragation = $row_rsIndicator['indicator_disaggregation'];

                                  $query_rsmeasurement = $db->prepare("SELECT * FROM tbl_measurement_units WHERE id = :unitid");
                                  $query_rsmeasurement->execute(array(":unitid" => $unitid));
                                  $row_rsmeasurement = $query_rsmeasurement->fetch();
                                  $totalRows_rsmeasurement = $query_rsmeasurement->rowCount();
                                  $unit = $row_rsmeasurement['unit'];

                                  $query_rsoutput = $db->prepare("SELECT * FROM tbl_project_details WHERE id = :outputid");
                                  $query_rsoutput->execute(array(":outputid" => $opdetailsid));
                                  $row_rsoutput = $query_rsoutput->fetch();
                                  $totalRows_rsoutput = $query_rsoutput->rowCount();
                                  $pid = $row_rsoutput['outputid'];

                                  $query_rsoutputid = $db->prepare("SELECT * FROM tbl_progdetails WHERE id = :outputid");
                                  $query_rsoutputid->execute(array(":outputid" => $pid));
                                  $row_rsoutputid = $query_rsoutputid->fetch();
                                  $totalRows_rsoutputid = $query_rsoutputid->rowCount();
                                  $outputname = $row_rsoutputid['output'];

                                  if ($location > 0) {
                                    $query_rslevel3 = $db->prepare("SELECT * FROM tbl_projects_location_targets WHERE  level3=:level3 AND locationdisid = :location AND responsible=:responsible");
                                    $query_rslevel3->execute(array(":location" => $location, ":level3" => $level3, ":responsible" => $user_name));
                                  } else {
                                    $query_rslevel3 = $db->prepare("SELECT * FROM tbl_output_disaggregation WHERE outputstate = :level3 AND responsible=:responsible");
                                    $query_rslevel3->execute(array(":level3" => $level3, ":responsible" => $user_name));
                                  }

                                  $totalRows_rslevel3 = $query_rslevel3->rowCount();

                                  if ($totalRows_rslevel3 > 0) {
                                    $nm++;
                              ?>

                                    <div class="panel panel-primary">
                                      <div class="panel-heading list-group-item list-group-item list-group-item-action active collapse careted" data-toggle="collapse" data-target=".output<?php echo $opdetailsid ?>">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                        <strong> Output <?= $nm ?>:
                                          <span class="">
                                            <?= $outputname ?>
                                          </span>
                                        </strong>
                                      </div>
                                      <div class="collapse output<?php echo $opdetailsid ?>" style="padding:5px">

                                        <fieldset class="scheduler-border">
                                          <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Project Output <?= $nm ?> (Tasks/Activities Progress)</legend>
                                          <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <label>Output Name :</label>
                                            <div class="form-line">
                                              <input type="text" class="form-control" value="<?php echo $outputname; ?>" readonly="readonly" style="border:#CCC thin solid; border-radius: 5px" />
                                            </div>
                                          </div>
                                          <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <label>Output Indicator :</label>
                                            <div class="form-line">
                                              <input type="text" class="form-control" value="<?php echo $unit ." of ".$indicator; ?>" readonly="readonly" style="border:#CCC thin solid; border-radius: 5px" />
                                            </div>
                                          </div>
                                          <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                            <label>Output Progress (<?= $unit ?>): </label>
                                            <div class="form-line">
                                              <input type="text" class="form-control" value="<?php echo $cummvalue; ?>" readonly="readonly" style="border:#CCC thin solid; border-radius: 5px" />
                                            </div>
                                          </div>
                                          <?php
                                          $query_rsopprogress = '';
                                          $query_rsSumopprogress = '';
                                          if ($location > 0) {
                                            $lev4id = $location;
                                            $query_rsopprogress = $db->prepare("SELECT * FROM tbl_monitoringoutput WHERE projid = :projid and opid=:opid AND level3=:level3 AND  level4=:level4 ORDER BY moid DESC LIMIT 1");
                                            $query_rsopprogress->execute(array(":projid" => $projid, ":opid" => $opdetailsid, ":level3" => $level3, ":level4" => $lev4id));

                                            // cumulative progress
                                            $query_rsSumopprogress = $db->prepare("SELECT SUM(actualoutput) as sumoutput FROM tbl_monitoringoutput WHERE projid = :projid and opid=:opid AND level3=:level3 AND  level4=:level4");
                                            $query_rsSumopprogress->execute(array(":projid" => $projid, ":opid" => $opdetailsid, ":level3" => $level3, ":level4" => $lev4id));
                                          } else {
                                            // previous progress
                                            $query_rsopprogress = $db->prepare("SELECT * FROM tbl_monitoringoutput WHERE projid = :projid and opid=:opid AND level3=:level3 AND  level4 IS NULL ORDER BY moid DESC LIMIT 1");
                                            $query_rsopprogress->execute(array(":projid" => $projid, ":opid" => $opdetailsid, ":level3" => $level3));

                                            // cumulative progress
                                            $query_rsSumopprogress = $db->prepare("SELECT SUM(actualoutput) as sumoutput  FROM tbl_monitoringoutput WHERE projid = :projid and opid=:opid AND level3=:level3 AND  level4 IS NULL");
                                            $query_rsSumopprogress->execute(array(":projid" => $projid, ":opid" => $opdetailsid, ":level3" => $level3));
                                          }

                                          // previous progress
                                          $row_rsopprogress = $query_rsopprogress->fetch();
                                          $totalRows_rsopprogress = $query_rsopprogress->rowCount();

                                          // sum progress
                                          $row_rssumopprogress = $query_rsSumopprogress->fetch();
                                          $totalRows_rssumopprogress = $query_rsSumopprogress->rowCount();
                                          $opprogress = '';
                                          $totalSum = '';
                                          if ($totalRows_rsopprogress > 0) {
                                            $opprogress = number_format($row_rsopprogress["actualoutput"]);
                                            $totalSum = $row_rssumopprogress['sumoutput'];
                                          } else {
                                            $opprogress = "Not Yet Monitored";
                                            $totalSum = 0;
                                          }

                                          if ($location > 0) {
                                            $tree =  makeTree($indid);
                                            printNode($tree[0]);
                                          } else {
                                            non_disaggregated($opdetailsid);
                                          }
                                          ?>

                                          <input type="hidden" name="opid[]" id="opid" value="<?php echo $opid; ?>" />
                                          <input type="hidden" name="opdetailsid[]" id="opid" value="<?php echo $opdetailsid; ?>" />
                                          <input type="hidden" name="opformid" id="opformid" value="<?php echo $pmtid; ?>" />
                                          <input type="hidden" name="myprojid" id="myprojid" value="<?php echo $projid; ?>" />
                                          <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                              <div class="card">
                                                <div class="body">
                                                  <div class="row clearfix">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                      <div class="table-responsive">
                                                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable" style="width: 100%;">
                                                          <thead>
                                                            <tr id="colrow">
                                                              <td width="3%">SN</td>
                                                              <td width="30%">Tasks (In Progress)</td>
                                                              <td width="30%">Milestone Name</td>
                                                              <td width="15%">Current Status</td>
                                                              <td width="8%">Previous Record</td>
                                                              <td width="9%">Checklist</td>
                                                              <td width="5%">Score (%)</td>
                                                            </tr>
                                                          </thead>
                                                          <tbody>
                                                            <?php
                                                            $query_maxdate = $db->prepare("SELECT formid FROM tbl_task t inner join tbl_task_progress p ON p.opid=t.outputid WHERE date IN (SELECT MAX(date) FROM tbl_task_progress) and projid = :projid and outputid = :opid and level3 = :lv3 and level4 IS NULL GROUP BY formid");
                                                            $query_maxdate->execute(array(":projid" => $projid, ":opid" => $opdetailsid, ":lv3" => $level3));
                                                            $totalRows_maxdate = $query_maxdate->rowCount();
                                                            $rows_maxdate = $query_maxdate->fetch();
                                                            $formidd = $rows_maxdate['formid'];

                                                            $query_moncount = $db->prepare("SELECT * FROM tbl_task t inner join tbl_task_progress p ON p.opid=t.outputid WHERE  projid = :projid and outputid = :opid and level3 = :lv3 and level4 IS NULL");
                                                            $query_moncount->execute(array(":projid" => $projid, ":opid" => $opdetailsid, ":lv3" => $level3));
                                                            $totalRows_moncount = $query_moncount->rowCount();
                                                            //echo $totalRows_moncount;
                                                            if ($totalRows_moncount > 0) {
                                                              $query_rsTaskPrg = $db->prepare("SELECT * FROM tbl_task_progress p inner join tbl_task t ON p.tkid=t.tkid WHERE formid=:formid and (status=4 OR status=11) ORDER BY t.tkid ASC");
                                                              $query_rsTaskPrg->execute(array(':formid' => $formidd));
                                                              $row_rsTaskPrg = $query_rsTaskPrg->fetch();
                                                              $totalRows_rsTaskPrg = $query_rsTaskPrg->rowCount();
                                                            } else {
                                                              $query_rsTaskPrg = $db->prepare("SELECT * FROM tbl_task WHERE projid = :projid and outputid = :opid and (status=4 OR status=11) ORDER BY msid ASC");
                                                              $query_rsTaskPrg->execute(array(":projid" => $projid, ":opid" => $opdetailsid));
                                                              $row_rsTaskPrg = $query_rsTaskPrg->fetch();
                                                              $totalRows_rsTaskPrg = $query_rsTaskPrg->rowCount();
                                                            }

                                                            if ($totalRows_rsTaskPrg > 0) {
                                                              $num = 0;
                                                              do {
                                                                $num = $num + 1;
                                                                $tskid = $row_rsTaskPrg['tkid'];
                                                                $tkmsid = $row_rsTaskPrg['msid'];
                                                                $tsksts = $row_rsTaskPrg['status'];
                                                                $Prg = $row_rsTaskPrg["progress"];

                                                                if ($totalRows_moncount > 0) {
                                                                  $Prg = $row_rsTaskPrg["progress"];
                                                                } else {
                                                                  $Prg = 0;
                                                                }

                                                                $query_tskstatus = $db->prepare("SELECT statusname FROM tbl_task_status WHERE statusid='$tsksts'");
                                                                $query_tskstatus->execute();
                                                                $row_tskstatus = $query_tskstatus->fetch();
                                                                $tasksts = $row_tskstatus["statusname"];

                                                                $query_tkMs = $db->prepare("SELECT milestone FROM tbl_milestone WHERE msid='$tkmsid' ORDER BY msid DESC LIMIT 1");
                                                                $query_tkMs->execute();
                                                                $row_tkMs = $query_tkMs->fetch();

                                                                if ($Prg < 100) {
                                                                  $Testno = "'" . $pmtid . "'";
                                                                  $tskid = $row_rsTaskPrg['tkid'];
                                                                  $query_checklistscore = $db->prepare("SELECT sum(score) AS totalscore, COUNT(id) as id FROM tbl_project_monitoring_checklist_score WHERE taskid='$tskid' and formid='$pmtid'");
                                                                  $query_checklistscore->execute();
                                                                  $row = $query_checklistscore->fetch();
                                                                  $total_checklistscore = $row["totalscore"];
                                                                  $id = $row["id"];
                                                                  $percscore = '';

                                                                  $link = '<td>
                                                                  <button type="button" class="btn bg-light-green waves-effect" onclick="GetTaskChecklist(' . $tskid . ',' . $Testno . ')" data-toggle="tooltip" data-placement="bottom" id="btn' . $tskid . '" title="Click here to see the checklist">Add Score</button></td>';
                                                                  if ($total_checklistscore > 0) {
                                                                    $percscore = round(($total_checklistscore / ($id * 10)) * 100, 2);
                                                                    $link = '<td>
                                                                  <button type="button" class="btn bg-light-green waves-effect" onclick="GetTaskChecklist(' . $tskid . ',' . $Testno . ')" data-toggle="tooltip" data-placement="bottom" id="btn' . $tskid . '" title="Click here to see the checklist">Edit Score</button></td>';
                                                                  }
                                                            ?>
                                                                  <tr id="rowlines">
                                                                    <td><?php echo $num; ?></td>
                                                                    <td><?php echo $row_rsTaskPrg['task']; ?></td>
                                                                    <td><?php echo $row_tkMs['milestone']; ?></td>
                                                                    <td><?php echo $tasksts; ?></td>
                                                                    <td><?php echo $Prg . "%"; ?></td>
                                                                    <?= $link ?>
                                                                    <td>
                                                                      <input type="text" name="progress<?= $opid ?>[]" id="<?php echo $row_rsTaskPrg['tkid']; ?>" class="form-control tasks" value="<?= $percscore ?>" readonly="readonly" style="border:#CCC thin solid; border-radius: 5px; width:60px" />
                                                                    </td>

                                                                    <input type="hidden" name="tskid<?= $opid ?>[]" id="tskid<?= $opid ?>" value="<?php echo $row_rsTaskPrg['tkid']; ?>" />
                                                                    <input type="hidden" name="formid[]" id="formid" value="<?php echo $pmtid; ?>" />
                                                                  </tr>
                                                            <?php
                                                                }
                                                              } while ($row_rsTaskPrg = $query_rsTaskPrg->fetch());
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
                                          </div>
                                          <fieldset class="scheduler-border">
                                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Observation(s) & Recommendation(s)</legend>
                                            <!-- Task Checklist Questions -->
                                            <div class="row clearfix">
                                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="card" style="margin-bottom:-20px">
                                                  <div class="body">
                                                    <table class="table table-bordered" id="observations_table<?= $opid ?>" style="width:100%">
                                                      <tr>
                                                        <th style="width:100%">Add Observation</th>
                                                      </tr>
                                                      <tr>
                                                        <td>
                                                          <input type="text" name="observation<?= $opid ?>" class="form-control" placeholder="Enter your observation here" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
                                                        </td>
                                                      </tr>
                                                    </table>
                                                    <script type="text/javascript">
                                                      function add_list<?= $opid ?>() {
                                                        $rowno = $("#observations_table<?= $opid ?> tr").length;
                                                        $rowno = $rowno + 1;
                                                        $listno = $rowno - 1;
                                                        $("#observations_table<?= $opid ?> tr:last").after('<tr id="row' + $rowno + '"><td>' + $listno + '</td><td><div class="form-line"><select name="issue<?= $opid ?>[]" id="issue<?= $opid ?>[]" class="form-control topic" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px"><option value="" selected="selected" class="selection">... Select ...</option><?php echo risk_category_select_box($opdetailsid); ?></select></div></td><td><input type="text" name="issuedescription<?= $opid ?>[]" id="issuedescription<?= $opid ?>[]" class="form-control" placeholder="Description the issue here" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"></td><td><button type="button" class="btn btn-danger btn-sm"  onclick=delete_list<?= $opid ?>("row' + $rowno + '")><span class="glyphicon glyphicon-minus"></span></button></td></tr>');
                                                      }

                                                      function delete_list<?= $opid ?>(rowno) {
                                                        $('#' + rowno).remove();
                                                      }
                                                    </script>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="card" style="margin-bottom:-20px">
                                                  <div class="body">
                                                    <table class="table table-bordered" id="issues_table<?= $opid ?>" style="width:100%">
                                                      <tr>
                                                        <th style="width:2%">#</th>
                                                        <th style="width:21%">Issue</th>
                                                        <th style="width:75%">Description</th>
                                                        <th style="width:2%"><button type="button" name="addplus" onclick="add_issues<?= $opid ?>();" title="Add another question" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th>
                                                      </tr>
                                                      <tr>
                                                        <td>1</td>
                                                        <td>
                                                          <div class="form-line">
                                                            <select name="issue<?= $opid ?>[]" id="issue<?= $opid ?>[]" class="form-control topic" data-live-search="true" style="border:#CCC thin solid; border-radius:5px">
                                                              <option value="" selected="selected" class="selection">... Select ...</option>
                                                              <?php

                                                              echo risk_category_select_box($opdetailsid);
                                                              ?>
                                                            </select>
                                                          </div>
                                                        </td>
                                                        <td>
                                                          <input type="text" name="issuedescription<?= $opid ?>[]" id="issuedescription<?= $opid ?>[]" class="form-control" placeholder="Description the issue here" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif">
                                                        </td>
                                                        <td></td>
                                                      </tr>
                                                    </table>
                                                    <script type="text/javascript">
                                                      function add_issues<?= $opid ?>() {
                                                        $rowno = $("#issues_table<?= $opid ?> tr").length;
                                                        $rowno = $rowno + 1;
                                                        $listno = $rowno - 1;
                                                        $("#issues_table<?= $opid ?> tr:last").after('<tr id="row' + $rowno + '"><td>' + $listno + '</td><td><div class="form-line"><select name="issue<?= $opid ?>[]" id="issue<?= $opid ?>[]" class="form-control topic" data-live-search="true"  style="border:#CCC thin solid; border-radius:5px"><option value="" selected="selected" class="selection">... Select ...</option><?php echo risk_category_select_box($opdetailsid); ?></select></div></td><td><input type="text" name="issuedescription<?= $opid ?>[]" id="issuedescription<?= $opid ?>[]" class="form-control" placeholder="Description the issue here" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" ></td><td><button type="button" class="btn btn-danger btn-sm"  onclick=delete_issues<?= $opid ?>("row' + $rowno + '")><span class="glyphicon glyphicon-minus"></span></button></td></tr>');
                                                      }

                                                      function delete_issues<?= $opid ?>(rowno) {
                                                        $('#' + rowno).remove();
                                                      }
                                                    </script>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                            <!-- Task Checklist Questions -->
                                          </fieldset>
                                          <fieldset class="scheduler-border">
                                            <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px">Means of Verification (Files/Documents)</legend>
                                            <!-- Task Checklist Questions -->
                                            <div class="row clearfix">
                                              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="card" style="margin-bottom:-20px">
                                                  <div class="body">
                                                    <div class="table-responsive">
                                                      <h4>Files/Documents Attachments </h4>

                                                      <table class="table table-bordered" id="attachments_table<?= $opid ?>">
                                                        <tr>
                                                          <th style="width:2%">#</th>
                                                          <th style="width:40%">Attachments</th>
                                                          <th style="width:58%">Attachment Purpose</th>
                                                          <th style="width:2%"><button type="button" name="addplus" onclick="add_attachment<?= $opid ?>();" title="Add another document" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th>
                                                        </tr>
                                                        <tr>
                                                          <td>1</td>
                                                          <td>
                                                            <input type="file" name="monitorattachment<?= $opid ?>[]" id="monitorattachment<?= $opid ?>[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"/>
                                                          </td>
                                                          <td>
                                                            <input type="text" name="attachmentpurpose<?= $opid ?>[]" id="attachmentpurpose<?= $opid ?>[]" class="form-control" placeholder="Enter the purpose of this document" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
                                                          </td>
                                                          <td></td>
                                                        </tr>
                                                      </table>
                                                      <script type="text/javascript">
                                                        function add_attachment<?= $opid ?>() {
                                                          $rownm = $("#attachments_table<?= $opid ?> tr").length;
                                                          $rownm = $rownm + 1;
                                                          $attno = $rownm - 1;
                                                          $("#attachments_table<?= $opid ?> tr:last").after('<tr id="rw' + $rownm + '"><td>' + $attno + '</td><td><input type="file" name="monitorattachment<?= $opid ?>[]"  id="monitorattachment<?= $opid ?>[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" /></td><td><input type="text" name="attachmentpurpose<?= $opid ?>[]" id="attachmentpurpose<?= $opid ?>[]" class="form-control"  placeholder="Enter the purpose of this document" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"/></td><td><button type="button" class="btn btn-danger btn-sm"  onclick=delete_attach<?= $opid ?>("rw' + $rownm + '")><span class="glyphicon glyphicon-minus"></span></button></td></tr>');
                                                        }

                                                        function delete_attach<?= $opid ?>(rownm) {
                                                          $('#' + rownm).remove();
                                                        }
                                                      </script>
                                                    </div>

                                                    <div class="table-responsive">
                                                      <h4>URL Attachments </h4>
                                                      <table class="table table-bordered" id="url_table<?= $opid ?>">
                                                        <tr>
                                                          <th style="width:2%">#</th>
                                                          <th style="width:40%">URL</th>
                                                          <th style="width:58%">Attachment Purpose</th>
                                                          <th style="width:2%"><button type="button" name="addplus" onclick="add_url<?= $opid ?>();" title="Add another document" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th>
                                                        </tr>
                                                        <tr>
                                                          <td>1</td>
                                                          <td>
                                                            <input type="url" name="monitorurl<?= $opid ?>[]" multiple id="monitorurl<?= $opid ?>[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
                                                          </td>
                                                          <td>
                                                            <input type="text" name="attachmentpurposeurl<?= $opid ?>[]" id="attachmentpurposeurl<?= $opid ?>[]" class="form-control" placeholder="Enter the purpose of this URL" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" />
                                                          </td>
                                                          <td></td>
                                                        </tr>
                                                      </table>
                                                      <script type="text/javascript">
                                                        function add_url<?= $opid ?>() {
                                                          $rownm = $("#url_table<?= $opid ?> tr").length;
                                                          $rownm = $rownm + 1;
                                                          $attno = $rownm - 1;
                                                          $("#url_table<?= $opid ?> tr:last").after('<tr id="rwl' + $rownm + '"><td>' + $attno + '</td><td><input type="url" name="monitorurl<?= $opid ?>[]" multiple id="monitorurl<?= $opid ?>[]" class="form-control" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" /></td><td><input type="text" name="attachmentpurposeurl<?= $opid ?>[]" id="attachmentpurposeurl<?= $opid ?>[]" class="form-control"  placeholder="Enter the purpose of this URL" style="height:35px; width:99%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif"/></td><td><button type="button" class="btn btn-danger btn-sm"  onclick=delete_url<?= $opid ?>("rwl' + $rownm + '")><span class="glyphicon glyphicon-minus"></span></button></td></tr>');
                                                        }

                                                        function delete_url<?= $opid ?>(rownm) {
                                                          $('#' + rownm).remove();
                                                        }
                                                      </script>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                            <!-- Task Checklist Questions -->
                                          </fieldset>
                                        </fieldset>
                                      </div>
                                    </div>
                              <?php
                                  }
                                } while ($row_rsOutput_details = $query_rsOutput_details->fetch());
                              } else {
                                echo "No output found";
                              }

                              ?>
                              <div class="row clearfix">
                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" align="center">
                                  <input name="user_name" type="hidden" id="user_name" value="<?php echo $user_name; ?>" />
                                  <input name="projid" type="hidden" id="projid" value="<?php echo $projid; ?>" />
                                  <input name="mainformid" type="hidden" id="mainformid" value="<?php echo $pmtid; ?>" />
                                  <div class="btn-group">
                                    <input name="submit" type="submit" class="btn bg-light-blue waves-effect waves-light" id="submit" value="Submit" />
                                  </div>
                                  <input type="hidden" name="MM_insert" value="pmfrm" />
                                </div>
                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                </div>
                              </div>
                            </form>
                            <?php
                          } else {
                            ?>
                            <div style="color:#333; background-color:#EEE; width:98%; height:30px; padding-top:5px; padding-left:2px">
              								<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-3px">
              									<tr>
              										<td width="50%" style="font-size:14px; font-weight:bold" align="center">
              											<br><br><br><br>
              											<font color="red" size="4">Sorry, you are not allowed to monitor this project!!!!</font>
              											<br><br><br><br>
              										</td>
              									</tr>
              								</table>
              							</div>
                            <?php
                          }
                             ?>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- end body  -->
    <!-- #START# Modal -->
    <div class="modal fade" id="myModal" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header" style="background-color:#03A9F4">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h3 class="modal-title" align="center">
              <font color="#FFF">Task CheckList </font>
            </h3>
          </div>
          <form class="tagForm" action="savechecklistscore" method="post" id="tag-form" enctype="multipart/form-data">
            <div class="modal-body" id="formcontent">

            </div>
            <div class="modal-footer">
              <div class="col-md-4">
              </div>
              <div class="col-md-4" align="center">
                <input name="submit" type="submit" class="btn btn-success waves-effect waves-light" id="tag-form-submit" value="Assign Score" />
                <input type="hidden" name="responsible" id="responsible" value="<?php echo $user_name; ?>" />
                <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
              </div>
              <div class="col-md-4">
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- #END# Modal -->
<?php
} else {
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');
?>
<script src="assets/custom js/monitoring.js"></script>
