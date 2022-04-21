<?php
require('functions/strategicplan.php');
$pageName = "Strategic Plans";
$replacement_array = array(
    'planlabel' => "CIDP",
    'plan_id' => base64_encode(6),
);

$page = "view";

require('includes/head.php');

if ($permission) {

    $status_array = array(
        'all' => array('stage' => '', "status" => ''),
        'complete' => array('stage' => '', "status" => 5),
        'in-progress' => array('stage' => '= 10', "status" => 4),
        'pending' => array('stage' => '> 6', "status" => '0'),
        'behind-schedule' => array('stage' => '=10', "status" => 11),
        'approved' => array('stage' => '< 7', "status" => '0'),
        'on-hold' => array('stage' => '= 10', "status" => 6),
        'cancelled' => array('stage' => '= 10', "status" => 2)
    );

    try {
        function widgets_filter($from = null, $to = null, $level1 = null, $level2 = null, $level3 = null, $prjstatus)
        {
            $widget_array = '';
            if ($from != null) {
                if ($to != null) {
                    if ($level1 != null) {
                        if ($level2 != null) {
                            if ($level3 != null) {
                                // select for only from, to, level 1, 2 and 3
                                $sql = "p.projfscyear >=" . $from . "  and p.projfscyear <= " . $to;
                                $widget_array = widgets($sql, $level1, $level2, $level3, $prjstatus);
                            } else {
                                // select for only from, to, level 1 and 2
                                $sql = "p.projfscyear >=" . $from . "  and p.projfscyear <= " . $to;
                                $widget_array = widgets($sql, $level1, $level2, $level3 = null, $prjstatus);
                            }
                        } else {
                            // select for only from, to and level 1
                            $sql = "p.projfscyear >=" . $from . "  and p.projfscyear <= " . $to;
                            $widget_array = widgets($sql, $level1, $level2 = null, $level3 = null, $prjstatus);
                        }
                    } else {
                        // select for only from, to and to
                        $sql = "p.projfscyear >=" . $from . "  and p.projfscyear <= " . $to;
                        $widget_array = widgets($sql, $level1 = null, $level2 = null, $level3 = null, $prjstatus);
                    }
                } else {
                    if ($level1 != null) {
                        if ($level2 != null) {
                            if ($level3 != null) {
                                // select for only from, level 1, 2 and 3
                                $sql = "p.projfscyear >=" . $from;
                                $widget_array = widgets($sql, $level1, $level2, $level3, $prjstatus);
                            } else {
                                // select for only from, level 1 and 2
                                $sql = "p.projfscyear >=" . $from;
                                $widget_array = widgets($sql, $level1, $level2, $level3 = null, $prjstatus);
                            }
                        } else {
                            // select for only from and level 1
                            $sql = "p.projfscyear >=" . $from;
                            $widget_array = widgets($sql, $level1, $level2 = null, $level3 = null, $prjstatus);
                        }
                    } else {
                        // select for only from
                        $sql = "p.projfscyear >=" . $from;
                        $widget_array = widgets($sql, $level1 = null, $level2 = null, $level3 = null, $prjstatus);
                    }
                }
            } else {
                if ($level1 != null) {
                    if ($level2 != null) {
                        if ($level3 != null) {
                            // select for only level 1, 2 and 3 
                            $widget_array = widgets($sql = null, $level1, $level2, $level3, $prjstatus);
                        } else {
                            // select for only level 1 and 2 
                            $widget_array =  widgets($sql = null, $level1, $level2, $level3 = null, $prjstatus);
                        }
                    } else {
                        // select for only level 1 
                        $widget_array = widgets($sql = null, $level1, $level2 = null, $level3 = null, $prjstatus);
                    }
                }
            }
            return $widget_array;
        }

        function widgets($query = NULL, $level1 = NULL, $level2 = NULL, $level3 = NULL, $projstatus = null)
        {
            global $status_array, $db;
            $projids_array  = array();
            $project_status = $status_array[$projstatus];

            $stage = $project_status['stage'];
            $status = $project_status['status'];
            $stmt = '';
            if ($status != '' && $stage != '') {
                $stmt = "projstatus =" . $status  . ' AND projstage ' . $stage;
            } else if ($status != '' && $stage  == '') {
                $stmt = "projstatus =" . $status;
            } else if ($status == '' && $stage != '') {
                $stmt = 'projstage ' . $stage;
            }

            $where = $stmt != "" ? "AND " . $stmt : "";
            $where  = $query != null ? $where . " AND " . $query : $where;

            $sql = "SELECT * FROM tbl_projects p INNER JOIN tbl_annual_dev_plan d ON p.projid= d.projid  WHERE d.status = 1 and deleted = '0' " . $where;
            $query_rsprojects = $db->prepare($sql);
            $query_rsprojects->execute();
            $row_projects = $query_rsprojects->fetch();
            $allprojects = $query_rsprojects->rowCount();
            if ($allprojects > 0) {
                $projids_array = [];
                do {
                    $projid = $row_projects['projid'];
                    $projcommunity = explode(",", $row_projects['projcommunity']);
                    $projlga = explode(",", $row_projects['projlga']);
                    $projstate = explode(",", $row_projects['projstate']);

                    if ($level1 != null) {
                        if ($level2 != null) {
                            if ($level3 != null) {
                                if (in_array($level3, $projstate)) {
                                    $projids_array[] = $projid;
                                }
                            } else {
                                if (in_array($level2, $projlga)) {
                                    $projids_array[] = $projid;
                                }
                            }
                        } else {
                            if (in_array($level1, $projcommunity)) {
                                $projids_array[] = $projid;
                            }
                        }
                    } else {
                        $projids_array[] = $projid;
                    }
                } while ($row_projects = $query_rsprojects->fetch());
            }

            return $projids_array;
        }

        if (isset($_GET['btn_search']) and $_GET['btn_search'] == "FILTER") {
            $prjstatus = $_GET['prjstatus'];
            $prjfyfrom = $_GET['projfyfrom'];
            $prjfyto = $_GET['projfyto'];
            $prjsc = $_GET['projscounty'];
            $prjward = $_GET['projward'];
            $prjloc = $_GET['projlocation'];

            if (empty($prjfyfrom) && empty($prjsc)) {
                $widget_data = widgets($query = NULL, $prjsc = NULL, $prjward = NULL, $prjloc = NULL, $prjstatus);
                $projects = (count($widget_data) > 0) ?  array_unique($widget_data) : array();
            } else {
                $widget_data = widgets_filter($prjfyfrom, $prjfyto, $prjsc, $prjward, $prjloc, $prjstatus);
                $projects = (count($widget_data) > 0) ?  array_unique($widget_data) : array();
            }
        } else {
            $prjstatus = 'all';
            if (isset($_GET['prjstatus'])) {
                $prjstatus = $_GET['prjstatus'];
            }
            $widget_data = widgets($query = NULL, $prjsc = NULL, $prjward = NULL, $prjloc = NULL, $prjstatus);
            $projects = (count($widget_data) > 0) ?  array_unique($widget_data) : array();
        }
    } catch (PDOException $ex) {
        $results = flashMessage("An error occurred: " . $ex->getMessage());
    }

    $prjstatus = "All";
    if (isset($_GET['prjstatus'])) {
        $status_array = array(
            'all' => "All",
            'complete' => "Complete",
            'in-progress' => "In-Progress",
            'pending' => "Pending",
            'behind-schedule' => "Behind-Schedule",
            'approved' => "Awaiting Procurement",
            'on-hold' => "On-hold",
            'cancelled' => "Cancelled"
        );

        $prjstatusid = $_GET['prjstatus'];
        $prjstatus = $status_array[$prjstatusid];
    }
    $pageTitle = $prjstatus . " Projects";
?>

    <!-- start body  -->
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                    <i class="fa fa-columns" aria-hidden="true"></i>
                    <?php echo $pageTitle ?>
                    <div class="btn-group" style="float:right">
                        <div class="btn-group" style="float:right; margin: left 5px;">
                            <a href="dashboard.php" type="button" class="btn bg-orange waves-effect" style="float:right; margin-top:-5px">Go Back to Dashboard</a>
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
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr>
                                            <th style="width:5%">#</th>
                                            <th style="width:50%">Project Name</th>
                                            <th>Progress</th>
                                            <th>Department</th>
                                            <th>Cost (Ksh)</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (count($projects) > 0) {
                                            $sn = 0;
                                            foreach ($projects as $project) {
                                                $sn++;
                                                $sql = "SELECT * FROM tbl_projects p INNER JOIN tbl_annual_dev_plan d ON p.projid= d.projid  WHERE d.status = 1 and deleted = '0' and p.projid = " . $project;
                                                $query_rsprojects = $db->prepare($sql);
                                                $query_rsprojects->execute();
                                                $detail = $query_rsprojects->fetch();
                                                $allprojects = $query_rsprojects->rowCount();

                                                $prjID =  $detail['projid'];
                                                $prgID =  $detail['progid'];
                                                $projname =  $detail['projname'];

                                                $projsect = $db->prepare("SELECT s.sector FROM tbl_sectors s inner join tbl_programs g ON g.projsector=s.stid WHERE g.progid='$prgID' AND s.deleted='0'");
                                                $projsect->execute();
                                                $prjsect = $projsect->fetch();

                                                $projdept = $db->prepare("SELECT sector FROM tbl_sectors s inner join tbl_programs g ON g.projdept=s.stid WHERE g.progid='$prgID'");
                                                $projdept->execute();
                                                $prjdept = $projdept->fetch();
                                                $dept = $prjdept["sector"];

                                                $query_rsMlsProg = $db->prepare("SELECT COUNT(*) as nmb, SUM(progress) AS mlprogress FROM tbl_milestone WHERE projid ='$prjID'");
                                                $query_rsMlsProg->execute();
                                                $row_rsMlsProg = $query_rsMlsProg->fetch();

                                                $query_rsDates = $db->prepare("SELECT MIN(sdate) as start_date, MAX(edate) as end_date FROM `tbl_task` WHERE projid ='$prjID'");
                                                $query_rsDates->execute();
                                                $row_rsDates = $query_rsDates->fetch();

                                                $start_date  = ($row_rsDates['start_date'] != "") ? $row_rsDates['start_date']  : $detail['projstartdate'];;
                                                $end_date  = ($row_rsDates['end_date'] != "") ? $row_rsDates['end_date']  : $detail['projenddate'];;

                                                $prjprogress = ($row_rsMlsProg["mlprogress"] > 0 && $row_rsMlsProg["nmb"] > 0) ? $row_rsMlsProg["mlprogress"] / $row_rsMlsProg["nmb"] : 0;
                                                $percent2 = round($prjprogress, 2);
                                        ?>
                                                <tr>
                                                    <td><?php echo $sn; ?></td>
                                                    <td><?php echo $projname; ?></td>
                                                    <td align="center">
                                                        <input type="hidden" id="scardprog" value="<?php echo $percent2; ?>">
                                                        <?php
                                                        if ($percent2 < 100) {
                                                            echo '
                                                         <div class="progress" style="height:20px; font-size:10px; color:black">
                                                            <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="' . $percent2 . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percent2 . '%; height:20px; font-size:10px; color:black">
                                                               ' . $percent2 . '%
                                                            </div>
                                                         </div>';
                                                        } elseif ($percent2 == 100) {
                                                            echo '
                                                         <div class="progress" style="height:20px; font-size:10px; color:black">
                                                            <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="' . $percent2 . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $percent2 . '%; height:20px; font-size:10px; color:black">
                                                            ' . $percent2 . '%
                                                            </div>
                                                         </div>';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo $dept; ?></td>
                                                    <td><?php echo number_format($detail['projcost'], 2); ?></td>
                                                    <td><?php echo date("d M Y", strtotime($start_date)) ?></td>
                                                    <td><?php echo date("d M Y",strtotime($end_date)); ?></td>
                                                </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
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