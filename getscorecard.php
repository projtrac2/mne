<?php
include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';

if(isset($_POST['prjid'])) 
{
	$projid = $_POST["prjid"];
	//$progress = $_POST["scprog"];
	$query_dates = $db->prepare("SELECT projcost, projstartdate, projenddate FROM tbl_projects WHERE projid=:projid");
	$query_dates->execute(array(":projid" =>$projid));		
	$row_dates = $query_dates->fetch();
						
	$now = time();
	$prjsdate = strtotime($row_dates['projstartdate']);
	$prjedate = strtotime($row_dates['projenddate']);
	$prjdatediff = $prjedate - $prjsdate;
	$prjnowdiff = $now - $prjsdate;
	$prjtimelinerate = round(($prjnowdiff/$prjdatediff)*100,1);
	if($prjtimelinerate >100):
		$prjtimelinerate = 100;
	else:
		$prjtimelinerate = $prjtimelinerate;
	endif;
	
	$query_rsMlsProg = $db->prepare("SELECT COUNT(*) as nmb, SUM(progress) AS mlprogress FROM tbl_milestone WHERE projid =:projid");
	$query_rsMlsProg->execute(array(":projid" =>$projid));		
	$row_rsMlsProg = $query_rsMlsProg->fetch();

	$prjprogress = $row_rsMlsProg["mlprogress"]/$row_rsMlsProg["nmb"];
	$progress = round($prjprogress,2);
	if($progress==0):
		$projquality = 0;
	else:
		$projquality = 87.3;
	Endif;
	
	$query_Funds = $db->prepare("SELECT SUM(tbl_funding.amtreq) AS TotalReq, SUM(tbl_funding.amtdis) AS TotalDis, @curRow := @curRow + 1 AS sn FROM tbl_funding JOIN (SELECT @curRow := 0) r WHERE tbl_funding.projid = :projid ORDER BY tbl_funding.projid DESC");
	$query_Funds->execute(array(":projid" =>$projid));		
	$row_funds = $query_Funds->fetch();
	
	$totaldis = $row_funds['TotalDis'];
	$projcost = $row_dates['projcost'];	
	$totalreq = $row_funds['TotalReq'];
	$abrate = ($totaldis / $projcost) * 100;
	$abrate = round($abrate, 2);
					
echo '<ul class="menu tasks" style="font-size:10px">
		<li>
			<a href="javascript:void(0);">
				<h5>
					Project Progress Rate:
					<font color="blue">'.$progress.'%</font>
				</h5>
				<div class="progress" style="height:15px; font-size:10px; color:black">
					<div class="progress-bar bg-purple" role="progressbar" aria-valuenow="'.$progress.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$progress.'%; height:15px">
					</div>
				</div>
			</a>
		</li>
		<li>
			<a href="javascript:void(0);">
				<h5>
					Project Funds Absorption Rate:
					<font color="blue">'.$abrate.'%</font>
				</h5>
				<div class="progress" style="height:15px; font-size:10px; color:black">
					<div class="progress-bar bg-cyan" role="progressbar" aria-valuenow="'.$abrate.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$abrate.'%; height:15px">
					</div>
				</div>
			</a>
		</li>
		<li>
			<a href="javascript:void(0);">
				<h5>
					Project Timeline:
					<font color="blue">'.$prjtimelinerate.'%</font>
				</h5>
				<div class="progress" style="height:15px; font-size:10px; color:black">
					<div class="progress-bar bg-teal" role="progressbar" aria-valuenow="'.$prjtimelinerate.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$prjtimelinerate.'%; height:15px">
					</div>
				</div>
			</a>
		</li>
	</ul>
';
/* 	echo	'<li>
			<a href="javascript:void(0);">
				<h5>
					Projects Work Quality Rate:
					<font color="blue">'.$projquality.'%</font>
				</h5>
				<div class="progress" style="height:15px; font-size:10px; color:black">
					<div class="progress-bar bg-orange" role="progressbar" aria-valuenow="'.$projquality.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$projquality.'%; height:15px">
					</div>
				</div>
			</a>
		</li>'; */
}
?>