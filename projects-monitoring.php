<?php
$pageName = "Strategic Plans";
$replacement_array = array(
   'planlabel' => "CIDP",
   'plan_id' => base64_encode(6),
);

$page = "view";
require('includes/head.php');
if ($permission) {
  $pageTitle = "Project Monitoring";
  try {
      $currentPage = $_SERVER["PHP_SELF"];
      $query_monitored = $db->prepare("SELECT * FROM tbl_projects p INNER JOIN tbl_monitoring m ON p.projid=m.projid  WHERE p.deleted='0'  AND  m.user_name=:user_name ");
      $query_monitored->execute(array(":user_name" => $user_name));
      $count_monitored = $query_monitored->rowCount();
  } catch (PDOException $ex) {
      $results = flashMessage("An error occurred: " . $ex->getMessage());
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
                    <!-- start body -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            <thead>
                                <tr class="bg-blue-grey">
                                    <th style="width:3%">#</th>
                                    <th style="width:30%">Project Name</th>
                                    <th style="width:30%">Project Location</th>
                                    <th style="width:10%">Monitoring Date</th>
                                    <th style="width:10%">Status</th>
                                    <th style="width:7%">Monitor</th>
                                </tr>
                            </thead>
                            <tbody id="monitoring_table_body">
                                <?php
                                $responsible = $user_name;
                                $query = 'SELECT * FROM  tbl_projects  WHERE projstage=10 AND (projstatus=4 OR projstatus=11)';
                                $query_projects = $db->prepare($query);
                                $query_projects->execute();
                                $row_projects = $query_projects->fetch();
                                $totalrow_projects = $query_projects->rowCount();

                                $table_body = '';
                                if ($totalrow_projects > 0) {
                                    do {
                                        $projid = $row_projects['projid'];
                                        $projname = $row_projects['projname'];
                                        $projstatus = $row_projects['projstatus'];

                                        $query = 'SELECT * FROM tbl_output_disaggregation WHERE projid =:projid AND responsible=:responsible GROUP BY outputstate';
                                        $query_locations = $db->prepare($query);
                                        $query_locations->execute(array(":projid" => $projid, ":responsible" => $responsible));
                                        $row_locations = $query_locations->fetch();
                                        $totalrow_locations = $query_locations->rowCount();

                                        if ($totalrow_locations > 0) {
                                            do {
                                                $outputstate = $row_locations['outputstate'];
                                                $query1 = 'SELECT * FROM tbl_output_disaggregation d INNER JOIN tbl_project_outputs_mne_details m ON m.outputid = d.outputid WHERE d.projid=:projid AND outputstate=:outputstate GROUP BY  m.next_monitoring_date';
                                                $query_date = $db->prepare($query1);
                                                $query_date->execute(array(":projid" => $projid, ":outputstate" => $outputstate));
                                                $row_date = $query_date->fetch();
                                                $totalrow_date = $query_date->rowCount();
                                                if ($totalrow_date > 0) {
                                                    $counter = 0;
                                                    do {
                                                        $counter++;
                                                        $next_monitoring_date = $row_date['next_monitoring_date'];
                                                        $today = time();
                                                        $your_date = strtotime($next_monitoring_date);
                                                        $datediff = $today - $your_date;
                                                        $days =  round($datediff / (60 * 60 * 24));

                                                        $current_date = date("Y-m-d");
                                                        if ($current_date == $next_monitoring_date) {
                                                            $status = "Monitoring Due";
                                                        } elseif ($current_date < $next_monitoring_date) {
                                                            $status = "Monitoring Pending";
                                                        } elseif ($current_date > $next_monitoring_date) {
                                                            $status = "Monitoring Overdue";
                                                        }

                                                        $query_locs =  $db->prepare("SELECT state FROM tbl_state WHERE id = '$outputstate'");
                                                        $query_locs->execute();
                                                        $row_locs = $query_locs->fetch();
                                                        $loc = $row_locs["state"];
                                                        $projid = base64_encode($projid);
                                                        $outputstate = base64_encode($outputstate);
                                                        if ($days <= 30) {
                                                            $table_body .= '
                                                            <tr>
                                                                <td>' . $counter . '</td>
                                                                <td>' . $projname . '</td>
                                                                <td>' . $loc . '</td>
                                                                <td>' . $next_monitoring_date . '</td>
                                                                <td>' . $status . '</td>
                                                                <td>
                                                                    <div align="center">
                                                                        <a href="add-monitoring-data.php?proj=' . $projid . '&level3=' . $outputstate . '&level4=0" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="All Project Monitoring Sessions Report">
                                                                            <i class="fa fa-binoculars fa-2x text-success" aria-hidden="true"></i></a>
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>';
                                                        }
                                                    } while ($row_date = $query_date->fetch());
                                                }
                                            } while ($row_locations = $query_locations->fetch());
                                        }

                                        $query = 'SELECT * FROM tbl_projects_location_targets  WHERE  projid =:projid AND responsible=:responsible GROUP BY locationdisid';
                                        $query_locations = $db->prepare($query);
                                        $query_locations->execute(array(":projid" => $projid, ":responsible" => $responsible));
                                        $row_locations = $query_locations->fetch();
                                        $totalrow_locations = $query_locations->rowCount();

                                        if ($totalrow_locations > 0) {
                                            do {
                                                $locationdisid = $row_locations['locationdisid'];
                                                $outputstate = $row_locations['level3'];
                                                $query1 = 'SELECT * FROM tbl_projects_location_targets d INNER JOIN tbl_project_outputs_mne_details m ON m.outputid = d.outputid WHERE d.projid=:projid AND locationdisid=:locationdisid GROUP BY  m.next_monitoring_date';
                                                $query_date = $db->prepare($query1);
                                                $query_date->execute(array(":projid" => $projid, ":locationdisid" => $locationdisid));
                                                $row_date = $query_date->fetch();
                                                $totalrow_date = $query_date->rowCount();
                                                if ($totalrow_date > 0) {
                                                    $counter = 0;
                                                    do {
                                                        $counter++;
                                                        $next_monitoring_date = $row_date['next_monitoring_date'];
                                                        $today = time();
                                                        $your_date = strtotime($next_monitoring_date);
                                                        $datediff = $today - $your_date;
                                                        $days =  round($datediff / (60 * 60 * 24));
                                                        if ($days <= 30) {
                                                            $current_date = date("Y-m-d");

                                                            if ($current_date == $next_monitoring_date) {
                                                                $status = "Monitoring Due";
                                                            } elseif ($current_date < $next_monitoring_date) {
                                                                $status = "Monitoring Pending";
                                                            } elseif ($current_date > $next_monitoring_date) {
                                                                $status = "Monitoring Overdue";
                                                            }
                                                            $query_locs =  $db->prepare("SELECT disaggregations FROM tbl_indicator_level3_disaggregations WHERE id = '$locationdisid'");
                                                            $query_locs->execute();
                                                            $row_locs = $query_locs->fetch();
                                                            $loc = $row_locs["disaggregations"];
                                                            $projid = base64_encode($projid);
                                                            $outputstate = base64_encode($outputstate);
                                                            $table_body .= '
                                                            <tr>
                                                                <td>' . $counter . '</td>
                                                                <td>' . $projname . '</td>
                                                                <td>' . $loc . '</td>
                                                                <td>' . $next_monitoring_date . '</td>
                                                                <td>' . $status . '</td>
                                                                <td>
                                                                    <div align="center">
                                                                        <a href="add-monitoring-data.php?proj=' . $projid . '&level3=' . $outputstate . '&level4=' . $locationdisid . '" width="16" height="16" data-toggle="tooltip" data-placement="bottom" title="All Project Monitoring Sessions Report">
                                                                            <i class="fa fa-binoculars fa-2x text-success" aria-hidden="true"></i></a>
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>';
                                                        }
                                                    } while ($row_date = $query_date->fetch());
                                                }
                                            } while ($row_locations = $query_locations->fetch());
                                        }
                                    } while ($row_projects = $query_projects->fetch());
                                }
                                echo $table_body;
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- end body -->
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
