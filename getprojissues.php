 <?php
include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';

if(isset($_POST['prjid'])) 
{
	$projid = $_POST["prjid"];
	
	$query_Projdetails = $db->prepare("SELECT projname FROM tbl_projects WHERE deleted='0' AND projid='$projid'");
	$query_Projdetails->execute();
	$row_projdetails = $query_Projdetails->fetch();
	
	$query_Riskcat = $db->prepare("SELECT rskid, category FROM tbl_projissues I inner join tbl_projrisk_categories C on I.risk_category=C.rskid WHERE I.projid = '$projid' GROUP BY I.risk_category");
	$query_Riskcat->execute();
	$totalRows_Riskcat = $query_Riskcat->rowCount();
	
	
	echo '<div class="table-responsive"><font color="#000">		
		<h5><b>Project Name: '.$row_projdetails['projname'].'</b></h5></font><font color="#174082">';
	if($totalRows_Riskcat>0){
		while($row_Riskcat = $query_Riskcat->fetch()){
			$rskid = $row_Riskcat["rskid"];
			$category = $row_Riskcat["category"];
							
			$query_rsProjissues =  $db->prepare("SELECT observation, recommendation, status FROM tbl_projissues WHERE projid = '$projid' and risk_category='$rskid'");
			$query_rsProjissues->execute();		
			$totalRows_rsProjissues = $query_rsProjissues->rowCount();
			
			echo '
			<table class="table table-bordered" style="width:100%"  align="center">
			<tr style="background-color:#03A9F4; color:white">
			<th colspan="3">Risk Category: '.$category.'</th
			</tr>
			<tr style="background-color:skyblue; color:white">
			<th width="70%">Issue Observed</th><th width="30%">Status</th>
			</tr>';
			while($row_rsProjissues = $query_rsProjissues->fetch()){
				
				$status = $row_rsProjissues["status"];
				$observ = $row_rsProjissues["observation"];
				$recomm = $row_rsProjissues["recommendation"];
				
				if($status==1){
					$issuestatus = "Pending";
				}elseif($status==2){
					$issuestatus = "Analysis";
				}elseif($status==3){
					$issuestatus = "Analysed";
				}elseif($status==4){
					$issuestatus = "Escalated";
				}elseif($status==7){
					$issuestatus = "Closed";
				}
				
				echo'<tr>
				<td>'.$observ.'</td><td>'.$issuestatus.'</td>
				</tr>';
			}
			echo '</table>
			';
		}
	}else{
		echo'<table class="table table-bordered" style="width:100%"  align="center">
			<tr style="background-color:#8BC34A; color:white">
			<td>No issue record found!!!</td
			</tr>
			</table>';
	}
	echo '</font>
	</div>';
//}
}
?>