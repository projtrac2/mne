<?php
try {

require('includes/head.php');
if ($permission) {

		$query_rsProjects = $db->prepare("SELECT g.progid, g.progname, g.projsector, p.projcode, p.projid, p.projname, p.projinspection, p.projstage, p.projstatus, s.sector FROM tbl_projects p inner join tbl_programs g on g.progid=p.progid inner join tbl_sectors s on s.stid=g.projdept WHERE p.deleted='0'");
		$query_rsProjects->execute();
		$totalRows_rsProjects = $query_rsProjects->rowCount();

        $query_rsTP = $db->prepare("SELECT COUNT(projname) FROM tbl_projects WHERE deleted='0' and projplanstatus='1'");
        $query_rsTP->execute();
        $row_rsTP = $query_rsTP->fetch();

        $query_rsTPList = $db->prepare("SELECT projname, COUNT(projname) FROM tbl_projects WHERE deleted='0' and projplanstatus='1' GROUP BY projname");
        $query_rsTPList->execute();
        $row_rsTPList = $query_rsTPList->fetch();

        $query_srcSector = $db->prepare("SELECT DISTINCT projdept, g.projsector FROM tbl_programs g inner join tbl_projects p on p.progid=g.progid where projplanstatus='1' ORDER BY g.projsector ASC");
        $query_srcSector->execute();
    
?>
    <style>
        #links a {
            color: #FFFFFF;
            text-decoration: none;
        }

        hr {
            display: block;
            margin-top: 0.5em;
            margin-bottom: 0.5em;
            margin-left: auto;
            margin-right: auto;
            border-style: inset;
            border-width: 1px;
        }

        @media (min-width: 1200px) {
            .modal-lg {
                width: 90%;
            }
        }
    </style>
    <!-- start body  -->
    <section class="content">
        <div class="container-fluid">
            <div class="block-header bg-blue-grey" width="100%" height="55" style="margin-top:10px; padding-top:5px; padding-bottom:5px; padding-left:15px; color:#FFF">
                <h4 class="contentheader">
                    <?= $icon ?>
                    <?php echo $pageTitle ?>
                    <div class="btn-group" style="float:right">
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
                                <table class="table table-bordered table-striped table-hover js-basic-example " id="manageItemTable">
                                    <thead>
                                        <tr id="colrow">
                                            <th style="width:5%" align="center">#</th>
                                            <th style="width:7%">Project Code</th>
                                            <th style="width:50%">Project Name </th>
                                            <th style="width:25%">Project Department</th>
                                            <th style="width:13%">Project Issues</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($totalRows_rsProjects > 0) {
                                            $counter = 0;
                                            while ($row_rsProjects = $query_rsProjects->fetch()) {
                                                $projid = $row_rsProjects['projid'];

                                                $query_project_issues = $db->prepare("SELECT * FROM tbl_projissues WHERE projid = :projid");
                                                $query_project_issues->execute(array(":projid" => $projid));
												$totalRows_project_issues = $query_project_issues->rowCount();
												
												if($totalRows_project_issues > 0){
												
													$progid = $row_rsProjects['progid'];
													$projsector = $row_rsProjects['projsector'];
													$workflow_stage = $row_rsProjects['projstage'];
													$department = $row_rsProjects['sector'];

													$query_projsector = $db->prepare("SELECT * FROM tbl_sectors WHERE stid = :sector");
													$query_projsector->execute(array(":sector" => $projsector));
													$row_projsector = $query_projsector->fetch();
													$sector = $row_projsector['sector'];

													$query_projteam = $db->prepare("SELECT * FROM tbl_projmembers WHERE projid = :projid");
													$query_projteam->execute(array(":projid" => $projid));
													$row_projteam = $query_projteam->fetch();
													$totalRows_projteam = $query_projteam->rowCount();

													$query_projstage = $db->prepare("SELECT stage FROM tbl_project_workflow_stage WHERE id = :projstageid");
													$query_projstage->execute(array(":projstageid" => $workflow_stage));
													$row_projstage = $query_projstage->fetch();
													$projstage = $row_projstage['stage'];

													// if($totalRows_projteam < 6){
													$query_rsPrograms = $db->prepare("SELECT * FROM tbl_programs WHERE progid = :progid");
													$query_rsPrograms->execute(array(":progid" => $progid));
													$row_rsPrograms = $query_rsPrograms->fetch();
													$totalRows_rsPrograms = $query_rsPrograms->rowCount();

													$project_department = $totalRows_rsPrograms > 0 ?  $row_rsPrograms['projsector'] : "";
													$project_section = $totalRows_rsPrograms > 0 ?  $row_rsPrograms['projdept'] : "";
													$project_directorate = $totalRows_rsPrograms > 0 ?  $row_rsPrograms['directorate'] : "";

													$filter_department = view_record($project_department, $project_section, $project_directorate);
													$query_rsProjissues =  $db->prepare("SELECT * FROM tbl_projissues WHERE projid = :projid");
													$query_rsProjissues->execute(array(":projid" => $projid));
													$projissues = $query_rsProjissues->rowCount();
													$projid_hashed = base64_encode("projrisk047{$projid}");

													if ($filter_department) {
														$counter++;
														?>
														<tr class="projects">
															<td align="center"><?= $counter ?></td>
															<td><?php echo $row_rsProjects['projcode'] ?></td>
															<td><?php echo $row_rsProjects['projname'] ?></td>
															<td><?php echo $department ?></td>
															<td>
																<a href="projectissueslist.php?proj=<?=$projid_hashed?>" style="color:#FF5722">
																	<?php echo '<i class="fa fa-exclamation-triangle fa-2x" aria-hidden="true" title="Project Issues"></i> <font size="5px">' . $projissues . '</font>'; ?>
																</a>
															</td>
														</tr>
														<?php
													}
												}
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="7">No Approved projects without team</td>
                                            </tr>
                                        <?php
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

<?php
} else {
    $results =  restriction();
    echo $results;
}

require('includes/footer.php');

} catch (PDOException $th) {
    customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine()); 
}
?>