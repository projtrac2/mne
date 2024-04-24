<?php
include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';
include_once "system-labels.php";

function get_financiers($projid){
	// query project financier
    global $db;
	$query_financiers =  $db->prepare("SELECT *, f.financier AS funder,t.description FROM tbl_myprojfunding m inner join tbl_financiers f ON f.id=m.financier inner join tbl_financier_type t on t.id=m.sourcecategory WHERE projid = :projid ORDER BY amountfunding desc");
	$query_financiers->execute(array(":projid" => $projid));
	$row_financiers = $query_financiers->fetchAll();
	$totalRows_financiers = $query_financiers->rowCount();

    if($totalRows_financiers > 0){
        return $row_financiers; 
    }else{
        return false;
    }	
}

if (isset($_POST['prjid'])) {
    $projid = $_POST["prjid"];
    $query_Projdetails = $db->prepare("SELECT * FROM tbl_projects WHERE deleted='0' AND projid='$projid'");
    $query_Projdetails->execute();
    $row_projdetails = $query_Projdetails->fetch();

    $progid = $row_projdetails["progid"];
	// get departent from programs 
	$query_Progdetails = $db->prepare("SELECT projsector, projdept FROM tbl_programs WHERE progid ='$progid'");
    $query_Progdetails->execute();
    $row_projgdetails = $query_Progdetails->fetch(); 
    $prjsect = $row_projgdetails["projsector"];
    $prjdept = $row_projgdetails["projdept"];

    $query_ProjSector = $db->prepare("SELECT sector FROM tbl_sectors WHERE stid ='$prjsect'");
    $query_ProjSector->execute();
    $row_ProjSector = $query_ProjSector->fetch();

    $query_ProjDept = $db->prepare("SELECT sector FROM tbl_sectors WHERE stid ='$prjdept'");
    $query_ProjDept->execute();
    $row_ProjDept = $query_ProjDept->fetch();

    // $query_PrjDet = $db->prepare("SELECT tbl_projects.*, tbl_outputs.output AS output, tbl_indicator.indname AS indicator, tbl_expprojoutput.expoutputvalue AS target, tbl_expprojoutput.outputbaseline AS baseline, @curRow := @curRow + 1 AS sn FROM tbl_projects JOIN (SELECT @curRow := 0) r LEFT JOIN tbl_expprojoutput ON tbl_projects.projid = tbl_expprojoutput.projid  LEFT JOIN tbl_indicator ON tbl_expprojoutput.expoutputindicator = tbl_indicator.indid  LEFT JOIN tbl_outputs ON tbl_expprojoutput.expoutputname = tbl_outputs.opid WHERE tbl_projects.deleted='0' AND tbl_projects.projid='$projid'");
    // $query_PrjDet->execute();
    // $row_PrjDet = $query_PrjDet->fetch();

    /* $query_PrjFund = $db->prepare("SELECT tbl_myprojfunding.sourcecategory AS source, tbl_myprojfunding.sourceid AS sourceid, FORMAT(tbl_myprojfunding.amountfunding, 2) AS amount, tbl_currency.code AS currency FROM tbl_myprojfunding LEFT JOIN tbl_currency ON tbl_myprojfunding.currency = tbl_currency.id WHERE tbl_myprojfunding.progid='$progid'");
    $query_PrjFund->execute(); */

    $query_PrjRisk = $db->prepare("SELECT category FROM tbl_projrisk_categories C LEFT JOIN tbl_projectrisks R ON R.rskid = C.rskid WHERE R.projid='$projid'");
    $query_PrjRisk->execute();

    echo '
<div class="table-responsive">
	<font color="#174082">
		<table class="table table-bordered" style="width:100%"  align="center">
			<tr style="background-color:skyblue; color:white">
				<th>Project Code</th>
				<th colspan="3">Project Name</th>
			</tr>
			<tr>
				<td>' . $row_projdetails['projcode'] . '</td>
				<td colspan="3">' . $row_projdetails['projname'] . '</td>
			</tr>
			<tr style="background-color:skyblue; color:white">
				<th colspan="2">Project ' . $ministrylabel . '</th>
				<th colspan="2">Project ' . $departmentlabel . '</th>
			</tr>
			<tr>
				<td colspan="2">' . $row_ProjSector['sector'] . '</td>
				<td colspan="2">' . $row_ProjDept['sector'] . '</td>
			</tr>

			<tr style="background-color:skyblue; color:white">
				<th colspan="4">Project Description</th>
			</tr>
			<tr>
				<td colspan="4">' . $row_projdetails['projdesc'] . '</td>
			</tr>
			<tr style="background-color:skyblue; color:white">
				<th colspan="4">Expected Project Outcome:</th>
			</tr>
			<tr>
				<td colspan="4">Outputs</td>
			</tr>
			<tr style="background-color:skyblue; color:white">
				<th colspan="4">Project Assumptions</th>
			</tr>';
			$sn = 0;
			while ($row_PrjRisk = $query_PrjRisk->fetch()) {
				$sn = $sn + 1;
				echo '
				<tr>
					<td colspan="4">' . $sn . '. ' . $row_PrjRisk['category'] . '</td>
				</tr>';
			}

			echo '
			<tr style="background-color:skyblue; color:white">
				<th>Output</th>
				<th>Indicator</th>
				<th>Baseline</th>
				<th>Target</th>
			</tr>
			<tr>';
				// <td>' . $row_PrjDet['output'] . '</td>
				// <td>' . $row_PrjDet['indicator'] . '</td>
				// <td>' . $row_PrjDet['baseline'] . '</td>
				// <td>' . $row_PrjDet['target'] . '</td>
			echo '
			</tr>
			<tr style="background-color:skyblue; color:white">
				<th>Funding Source</th>
				<th>Funder Name</th>
				<th>Funding Amount</th>
				<th>Currency</th>
			</tr>';
			$nm = 0;
			
			$financiers = get_financiers($projid);
			foreach ($financiers as $row_PrjFund) {
				$nm = $nm + 1;
				//$srcid = $row_PrjFund["sourceid"];
				$source = $row_PrjFund["description"];
				$funder = $row_PrjFund["funder"];
				$currency = "$";
				if(!empty($row_PrjFund['currency'])){
					$currency = $row_PrjFund['currency'];
				}

				echo '
				<tr>
					<td>' . $nm . '. ' . $source . '</td>
					<td>' . $funder . '</td>
					<td>' . $row_PrjFund['amountfunding'] . '</td>
					<td>' . $currency . '</td>
				</tr>';
			}
			echo '
		</table>
	</font>
</div>'; 
}
