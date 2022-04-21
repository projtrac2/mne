<?php 

require 'authentication.php';

try{	
		
	
	if (isset($_GET['srcfyear'])) {
	  $pfscyr_rsUpP = $_GET['srcfyear'];
	}

	if (isset($_GET['srcsct'])) {
	  $psector_rsUpP = $_GET['srcsct'];
	}

	if (isset($_GET['srcdept'])) {
	  $pdept_rsUpP = $_GET['srcdept'];
	}

	if (isset($_GET['srccomm'])) {
	  $pcomm_rsUpP = $_GET['srccomm'];
	}

	if (isset($_GET['srcstate'])) {
	  $pstate_rsUpP = $_GET['srcstate'];
	}

	if (isset($_GET['srcstatus'])) {
	  $pstatus_rsUpP = $_GET['srcstatus'];
	}

	if (isset($_GET['srctype'])) {
	  $ptype_rsUpP = $_GET['srctype'];
	}
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

	if(isset($_GET['btn_csv']) && $_GET['btn_csv']=="CSV"){
		if(!empty($pfscyr_rsUpP) && !empty($psector_rsUpP) && !empty($pdept_rsUpP) && !empty($pcomm_rsUpP) && !empty($pstate_rsUpP)){
			$query_rsUpP =  $db->prepare("SELECT p.*, p.projname AS name, p.projdepartment AS SCT, p.projcost AS pcost, p.projstartdate AS stdate, p.projenddate AS endate, SUM(amountrequested) AS TotalReq, SUM(amountpaid) AS TotalDis, sector FROM tbl_projects p inner join tbl_payments_disbursed d on d.projid=p.projid inner join tbl_payments_request r on r.id=d.reqid inner join tbl_sectors s on s.stid=p.projsector WHERE p.deleted ='0' AND p.projstatus = 'Completed' AND p.projfscyear = '$pfscyr_rsUpP' AND p.projsector = '$psector_rsUpP' AND p.projdepartment = '$pdept_rsUpP' AND p.projcommunity = '$pcomm_rsUpP' AND p.projlga = '$pstate_rsUpP' ORDER BY p.projdepartment ASC");
		}
		elseif(!empty($pfscyr_rsUpP) && !empty($psector_rsUpP) && !empty($pdept_rsUpP) && !empty($pcomm_rsUpP) && empty($pstate_rsUpP)){
			$query_rsUpP =  $db->prepare("SELECT p.*, p.projname AS name, p.projdepartment AS SCT, p.projcost AS pcost, p.projstartdate AS stdate, p.projenddate AS endate, SUM(amountrequested) AS TotalReq, SUM(amountpaid) AS TotalDis, sector FROM tbl_projects p inner join tbl_payments_disbursed d on d.projid=p.projid inner join tbl_payments_request r on r.id=d.reqid inner join tbl_sectors s on s.stid=p.projsector WHERE p.deleted ='0' AND p.projstatus = 'Completed' AND p.projfscyear = '$pfscyr_rsUpP' AND p.projsector = '$psector_rsUpP' AND p.projdepartment = '$pdept_rsUpP' AND p.projcommunity = '$pcomm_rsUpP' ORDER BY p.projdepartment ASC");
		}
		elseif(!empty($pfscyr_rsUpP) && !empty($psector_rsUpP) && empty($pdept_rsUpP) && !empty($pcomm_rsUpP) && !empty($pstate_rsUpP)){
			$query_rsUpP =  $db->prepare("SELECT p.*, p.projname AS name, p.projdepartment AS SCT, p.projcost AS pcost, p.projstartdate AS stdate, p.projenddate AS endate, SUM(amountrequested) AS TotalReq, SUM(amountpaid) AS TotalDis, sector FROM tbl_projects p inner join tbl_payments_disbursed d on d.projid=p.projid inner join tbl_payments_request r on r.id=d.reqid inner join tbl_sectors s on s.stid=p.projsector WHERE p.deleted ='0' AND p.projstatus = 'Completed' AND p.projfscyear = '$pfscyr_rsUpP' AND p.projsector = '$psector_rsUpP' AND AND p.projcommunity = '$pcomm_rsUpP' AND p.projlga = '$pstate_rsUpP' ORDER BY p.projdepartment ASC");
		}
		elseif(!empty($pfscyr_rsUpP) && !empty($psector_rsUpP) && !empty($pdept_rsUpP) && empty($pcomm_rsUpP) && empty($pstate_rsUpP)){
			$query_rsUpP =  $db->prepare("SELECT p.*, p.projname AS name, p.projdepartment AS SCT, p.projcost AS pcost, p.projstartdate AS stdate, p.projenddate AS endate, SUM(amountrequested) AS TotalReq, SUM(amountpaid) AS TotalDis, sector FROM tbl_projects p inner join tbl_payments_disbursed d on d.projid=p.projid inner join tbl_payments_request r on r.id=d.reqid inner join tbl_sectors s on s.stid=p.projsector WHERE p.deleted ='0' AND p.projstatus = 'Completed' AND p.projfscyear = '$pfscyr_rsUpP' AND p.projsector = '$psector_rsUpP' AND p.projdepartment = '$pdept_rsUpP' AND ORDER BY p.projdepartment ASC");
		}
		elseif(!empty($pfscyr_rsUpP) && empty($psector_rsUpP) && !empty($pdept_rsUpP) && empty($pcomm_rsUpP) && empty($pstate_rsUpP)){
			$query_rsUpP =  $db->prepare("SELECT p.*, p.projname AS name, p.projdepartment AS SCT, p.projcost AS pcost, p.projstartdate AS stdate, p.projenddate AS endate, SUM(amountrequested) AS TotalReq, SUM(amountpaid) AS TotalDis, sector FROM tbl_projects p inner join tbl_payments_disbursed d on d.projid=p.projid inner join tbl_payments_request r on r.id=d.reqid inner join tbl_sectors s on s.stid=p.projsector WHERE p.deleted ='0' AND p.projstatus = 'Completed' AND p.projfscyear = '$pfscyr_rsUpP' AND p.projdepartment = '$pdept_rsUpP' ORDER BY p.projdepartment ASC");
		}
		elseif(empty($pfscyr_rsUpP) && !empty($psector_rsUpP) && !empty($pdept_rsUpP) && empty($pcomm_rsUpP) && empty($pstate_rsUpP)){
			$query_rsUpP =  $db->prepare("SELECT p.*, p.projname AS name, p.projdepartment AS SCT, p.projcost AS pcost, p.projstartdate AS stdate, p.projenddate AS endate, SUM(amountrequested) AS TotalReq, SUM(amountpaid) AS TotalDis, sector FROM tbl_projects p inner join tbl_payments_disbursed d on d.projid=p.projid inner join tbl_payments_request r on r.id=d.reqid inner join tbl_sectors s on s.stid=p.projsector WHERE p.deleted ='0' AND p.projstatus = 'Completed' AND p.projsector = '$psector_rsUpP' AND p.projdepartment = '$pdept_rsUpP' ORDER BY p.projdepartment ASC");
		}
		elseif(!empty($pfscyr_rsUpP) && !empty($psector_rsUpP) && empty($pdept_rsUpP) && empty($pcomm_rsUpP) && empty($pstate_rsUpP)){
			$query_rsUpP =  $db->prepare("SELECT p.*, p.projname AS name, p.projdepartment AS SCT, p.projcost AS pcost, p.projstartdate AS stdate, p.projenddate AS endate, SUM(amountrequested) AS TotalReq, SUM(amountpaid) AS TotalDis, sector FROM tbl_projects p inner join tbl_payments_disbursed d on d.projid=p.projid inner join tbl_payments_request r on r.id=d.reqid inner join tbl_sectors s on s.stid=p.projsector WHERE p.deleted ='0' AND p.projstatus = 'Completed' AND p.projfscyear = '$pfscyr_rsUpP' AND p.projsector = '$psector_rsUpP' ORDER BY p.projdepartment ASC");
		}
		elseif(!empty($pfscyr_rsUpP) && empty($psector_rsUpP) && empty($pdept_rsUpP) && empty($pcomm_rsUpP) && empty($pstate_rsUpP)){
			$query_rsUpP =  $db->prepare("SELECT p.*, p.projname AS name, p.projdepartment AS SCT, p.projcost AS pcost, p.projstartdate AS stdate, p.projenddate AS endate, SUM(amountrequested) AS TotalReq, SUM(amountpaid) AS TotalDis, sector FROM tbl_projects p inner join tbl_payments_disbursed d on d.projid=p.projid inner join tbl_payments_request r on r.id=d.reqid inner join tbl_sectors s on s.stid=p.projsector WHERE p.deleted ='0' AND p.projstatus = 'Completed' AND p.projfscyear = '$pfscyr_rsUpP' ORDER BY p.projdepartment ASC");
		}
		elseif(empty($pfscyr_rsUpP) && !empty($psector_rsUpP) && empty($pdept_rsUpP) && empty($pcomm_rsUpP) && empty($pstate_rsUpP)){
			$query_rsUpP =  $db->prepare("SELECT p.*, p.projname AS name, p.projdepartment AS SCT, p.projcost AS pcost, p.projstartdate AS stdate, p.projenddate AS endate, SUM(amountrequested) AS TotalReq, SUM(amountpaid) AS TotalDis, sector FROM tbl_projects p inner join tbl_payments_disbursed d on d.projid=p.projid inner join tbl_payments_request r on r.id=d.reqid inner join tbl_sectors s on s.stid=p.projsector WHERE p.deleted ='0' AND p.projstatus = 'Completed' AND p.projsector = '$psector_rsUpP'  ORDER BY p.projdepartment ASC");
		}
		elseif(empty($pfscyr_rsUpP) && empty($psector_rsUpP) && !empty($pdept_rsUpP) && empty($pcomm_rsUpP) && empty($pstate_rsUpP)){
			$query_rsUpP =  $db->prepare("SELECT p.*, p.projname AS name, p.projdepartment AS SCT, p.projcost AS pcost, p.projstartdate AS stdate, p.projenddate AS endate, SUM(amountrequested) AS TotalReq, SUM(amountpaid) AS TotalDis, sector FROM tbl_projects p inner join tbl_payments_disbursed d on d.projid=p.projid inner join tbl_payments_request r on r.id=d.reqid inner join tbl_sectors s on s.stid=p.projsector WHERE p.deleted ='0' AND p.projstatus = 'Completed' AND p.projdepartment = '$pdept_rsUpP' ORDER BY p.projdepartment ASC");
		}
		elseif(empty($pfscyr_rsUpP) && empty($psector_rsUpP) && !empty($pdept_rsUpP) && !empty($pcomm_rsUpP) && !empty($pstate_rsUpP)){
			$query_rsUpP =  $db->prepare("SELECT p.*, p.projname AS name, p.projdepartment AS SCT, p.projcost AS pcost, p.projstartdate AS stdate, p.projenddate AS endate, SUM(amountrequested) AS TotalReq, SUM(amountpaid) AS TotalDis, sector FROM tbl_projects p inner join tbl_payments_disbursed d on d.projid=p.projid inner join tbl_payments_request r on r.id=d.reqid inner join tbl_sectors s on s.stid=p.projsector WHERE p.deleted ='0' AND p.projstatus = 'Completed' AND p.projdepartment = '$pdept_rsUpP' AND p.projcommunity = '$pcomm_rsUpP' AND p.projlga = '$pstate_rsUpP' ORDER BY p.projdepartment ASC");
		}
		elseif(empty($pfscyr_rsUpP) && empty($psector_rsUpP) && empty($pdept_rsUpP) && !empty($pcomm_rsUpP) && !empty($pstate_rsUpP)){
			$query_rsUpP =  $db->prepare("SELECT p.*, p.projname AS name, p.projdepartment AS SCT, p.projcost AS pcost, p.projstartdate AS stdate, p.projenddate AS endate, SUM(amountrequested) AS TotalReq, SUM(amountpaid) AS TotalDis, sector FROM tbl_projects p inner join tbl_payments_disbursed d on d.projid=p.projid inner join tbl_payments_request r on r.id=d.reqid inner join tbl_sectors s on s.stid=p.projsector WHERE p.deleted ='0' AND p.projstatus = 'Completed' AND p.projcommunity = '$pcomm_rsUpP' AND p.projlga = '$pstate_rsUpP' ORDER BY p.projdepartment ASC");
		}
		elseif(empty($pfscyr_rsUpP) && empty($psector_rsUpP) && empty($pdept_rsUpP) && !empty($pcomm_rsUpP) && empty($pstate_rsUpP)){
			$query_rsUpP =  $db->prepare("SELECT p.*, p.projname AS name, p.projdepartment AS SCT, p.projcost AS pcost, p.projstartdate AS stdate, p.projenddate AS endate, SUM(amountrequested) AS TotalReq, SUM(amountpaid) AS TotalDis, sector FROM tbl_projects p inner join tbl_payments_disbursed d on d.projid=p.projid inner join tbl_payments_request r on r.id=d.reqid inner join tbl_sectors s on s.stid=p.projsector WHERE p.deleted ='0' AND p.projstatus = 'Completed' AND p.projcommunity = '$pcomm_rsUpP' ORDER BY p.projdepartment ASC");
		}
		elseif(empty($pfscyr_rsUpP) && empty($psector_rsUpP) && empty($pdept_rsUpP) && empty($pcomm_rsUpP) && !empty($pstate_rsUpP)){
			$query_rsUpP =  $db->prepare("SELECT p.*, p.projname AS name, p.projdepartment AS SCT, p.projcost AS pcost, p.projstartdate AS stdate, p.projenddate AS endate, SUM(amountrequested) AS TotalReq, SUM(amountpaid) AS TotalDis, sector FROM tbl_projects p inner join tbl_payments_disbursed d on d.projid=p.projid inner join tbl_payments_request r on r.id=d.reqid inner join tbl_sectors s on s.stid=p.projsector WHERE p.deleted ='0' AND p.projstatus = 'Completed' AND p.projlga = '$pstate_rsUpP' ORDER BY p.projdepartment ASC");
		}
		elseif(!empty($pfscyr_rsUpP) && !empty($psector_rsUpP) && !empty($pdept_rsUpP) && empty($pcomm_rsUpP) && !empty($pstate_rsUpP)){
			$query_rsUpP =  $db->prepare("SELECT p.*, p.projname AS name, p.projdepartment AS SCT, p.projcost AS pcost, p.projstartdate AS stdate, p.projenddate AS endate, SUM(amountrequested) AS TotalReq, SUM(amountpaid) AS TotalDis, sector FROM tbl_projects p inner join tbl_payments_disbursed d on d.projid=p.projid inner join tbl_payments_request r on r.id=d.reqid inner join tbl_sectors s on s.stid=p.projsector WHERE p.deleted ='0' AND p.projstatus = 'Completed' AND p.projfscyear = '$pfscyr_rsUpP' AND p.projsector = '$psector_rsUpP' AND p.projdepartment = '$pdept_rsUpP' AND p.projlga = '$pstate_rsUpP' ORDER BY p.projdepartment ASC");
		}
		elseif(empty($pfscyr_rsUpP) && empty($psector_rsUpP) && empty($pdept_rsUpP) && empty($pcomm_rsUpP) && empty($pstate_rsUpP)){
			$query_rsUpP =  $db->prepare("SELECT p.*, p.projname AS name, p.projdepartment AS SCT, p.projcost AS pcost, p.projstartdate AS stdate, p.projenddate AS endate, SUM(amountrequested) AS TotalReq, SUM(amountpaid) AS TotalDis, sector FROM tbl_projects p inner join tbl_payments_disbursed d on d.projid=p.projid inner join tbl_payments_request r on r.id=d.reqid inner join tbl_sectors s on s.stid=p.projsector WHERE p.deleted ='0' AND p.projstatus = 'Completed' ORDER BY p.projdepartment ASC");
		}
		
		$query_rsUpP->execute();		
		//$row_rsUpP = $query_rsUpP->fetch();
		$totalRows_rsUpP = $query_rsUpP->rowCount();
		
		if($totalRows_rsUpP > 0){				
			$delimiter = ",";
			$rd2 = mt_rand(10,99);
			$filename = "projfunding_" . $rd2 . "_". date('Y-m-d') . ".csv";
			
			//create a file pointer
			$f = fopen('php://memory', 'w');
			
			//set column headers
			$fields = array('SN', 'Sector', 'Project Name', 'Budget (Ksh)', 'Actual Expenditure (Ksh)', 'Variance (Ksh)', 'Rate of Utilization', 'Start Date', 'End Date');
			fputcsv($f, $fields, $delimiter);
			
			//output each row of the data, format line as csv and write to file pointer
			//$ns = 0;
			while($row = $query_rsUpP->fetch()){
			//	$ns = $ns + 1;
				$myprojid = $row['projid'];
				$projsector = $row['SCT'];	
				
				$query_rsSumFund =  $db->prepare("SELECT SUM(amtreq) AS TotalReq, SUM(amtdis) AS TotalDis FROM tbl_funding WHERE projid ='$myprojid' ORDER BY projid DESC");
				$query_rsSumFund->execute();		
				$row_rsSumFund = $query_rsSumFund->fetch();
				//$totalRows_rsSumFund = $query_rsSumFund->rowCount();
			
				$totaldis = $row_rsSumFund['TotalDis'];
				$projcost = $row['projcost'];	
				$totalreq = $row_rsSumFund['TotalReq'];
				$abrate = ($totaldis / $projcost) * 100;
				$otbal= $projcost - $totaldis;
				$myprojcost = number_format($projcost, 2);
				$mytotaldis = number_format($totaldis, 2);
				$mytotalreq = number_format($totalreq, 2);
				$outstandingbal = number_format($otbal, 2);
				$abrate = round($abrate, 2);
				$absop = $abrate."%";	
				
				$query_rwSector =  $db->prepare("SELECT sector FROM tbl_sectors WHERE stid='$projsector'");
				$query_rwSector->execute();		
				$row_rwSector = $query_rwSector->fetch();
						
				$myprojsector = $row_rwSector['sector'];
						
				$lineData = array($row['sn'], $myprojsector, $row['name'], $myprojcost, $mytotaldis, $outstandingbal, $absop, $row['stdate'], $row['endate']);
				fputcsv($f, $lineData, $delimiter);
			}
			
			//move back to beginning of file
			fseek($f, 0);
			
			//set headers to download file rather than displayed
			header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="' . $filename . '";');
			
			//output all remaining data on a file pointer
			fpassthru($f);
		}
		exit;
	}
		
		
	/* if (isset($_GET['srcfyear']) || isset($_GET['srcsct']) || isset($_GET['srcdept']) || isset($_GET['srccomm']) || isset($_GET['srcstate']) || isset($_GET['srcstatus']) || isset($_GET['srctype'])){
		if(!empty($pfscyr_rsUpP) && !empty($psector_rsUpP) && !empty($pdept_rsUpP) && !empty($pcomm_rsUpP) && !empty($pstate_rsUpP)){
			$query_rsUpP =  $db->prepare("SELECT p.*, p.projname AS name, p.projdepartment AS SCT, p.projcost AS pcost, p.projstartdate AS stdate, p.projenddate AS endate, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r WHERE p.deleted ='0' AND p.projstatus = 'Completed' AND p.projfscyear = '$pfscyr_rsUpP' AND p.projsector = '$psector_rsUpP' AND p.projdepartment = '$pdept_rsUpP' AND p.projcommunity = '$pcomm_rsUpP' AND p.projlga = '$pstate_rsUpP' ORDER BY p.projdepartment ASC");
		}
		elseif(!empty($pfscyr_rsUpP) && !empty($psector_rsUpP) && !empty($pdept_rsUpP) && !empty($pcomm_rsUpP) && empty($pstate_rsUpP)){
			$query_rsUpP =  $db->prepare("SELECT p.*, p.projname AS name, p.projdepartment AS SCT, p.projcost AS pcost, p.projstartdate AS stdate, p.projenddate AS endate, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r WHERE p.deleted ='0' AND p.projstatus = 'Completed' AND p.projfscyear = '$pfscyr_rsUpP' AND p.projsector = '$psector_rsUpP' AND p.projdepartment = '$pdept_rsUpP' AND p.projcommunity = '$pcomm_rsUpP' ORDER BY p.projdepartment ASC");
		}
		elseif(!empty($pfscyr_rsUpP) && !empty($psector_rsUpP) && empty($pdept_rsUpP) && !empty($pcomm_rsUpP) && !empty($pstate_rsUpP)){
			$query_rsUpP =  $db->prepare("SELECT p.*, p.projname AS name, p.projdepartment AS SCT, p.projcost AS pcost, p.projstartdate AS stdate, p.projenddate AS endate, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r WHERE p.deleted ='0' AND p.projstatus = 'Completed' AND p.projfscyear = '$pfscyr_rsUpP' AND p.projsector = '$psector_rsUpP' AND AND p.projcommunity = '$pcomm_rsUpP' AND p.projlga = '$pstate_rsUpP' ORDER BY p.projdepartment ASC");
		}
		elseif(!empty($pfscyr_rsUpP) && !empty($psector_rsUpP) && !empty($pdept_rsUpP) && empty($pcomm_rsUpP) && empty($pstate_rsUpP)){
			$query_rsUpP =  $db->prepare("SELECT p.*, p.projname AS name, p.projdepartment AS SCT, p.projcost AS pcost, p.projstartdate AS stdate, p.projenddate AS endate, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r WHERE p.deleted ='0' AND p.projstatus = 'Completed' AND p.projfscyear = '$pfscyr_rsUpP' AND p.projsector = '$psector_rsUpP' AND p.projdepartment = '$pdept_rsUpP' AND ORDER BY p.projdepartment ASC");
		}
		elseif(!empty($pfscyr_rsUpP) && empty($psector_rsUpP) && !empty($pdept_rsUpP) && empty($pcomm_rsUpP) && empty($pstate_rsUpP)){
			$query_rsUpP =  $db->prepare("SELECT p.*, p.projname AS name, p.projdepartment AS SCT, p.projcost AS pcost, p.projstartdate AS stdate, p.projenddate AS endate, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r WHERE p.deleted ='0' AND p.projstatus = 'Completed' AND p.projfscyear = '$pfscyr_rsUpP' p.projdepartment = '$pdept_rsUpP' ORDER BY p.projdepartment ASC");
		}
		elseif(empty($pfscyr_rsUpP) && !empty($psector_rsUpP) && !empty($pdept_rsUpP) && empty($pcomm_rsUpP) && empty($pstate_rsUpP)){
			$query_rsUpP =  $db->prepare("SELECT p.*, p.projname AS name, p.projdepartment AS SCT, p.projcost AS pcost, p.projstartdate AS stdate, p.projenddate AS endate, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r WHERE p.deleted ='0' AND p.projstatus = 'Completed' AND p.projsector = '$psector_rsUpP' AND p.projdepartment = '$pdept_rsUpP' ORDER BY p.projdepartment ASC");
		}
		elseif(!empty($pfscyr_rsUpP) && !empty($psector_rsUpP) && empty($pdept_rsUpP) && empty($pcomm_rsUpP) && empty($pstate_rsUpP)){
			$query_rsUpP =  $db->prepare("SELECT p.*, p.projname AS name, p.projdepartment AS SCT, p.projcost AS pcost, p.projstartdate AS stdate, p.projenddate AS endate, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r WHERE p.deleted ='0' AND p.projstatus = 'Completed' AND p.projfscyear = '$pfscyr_rsUpP' AND p.projsector = '$psector_rsUpP' ORDER BY p.projdepartment ASC");
		}
		elseif(!empty($pfscyr_rsUpP) && empty($psector_rsUpP) && empty($pdept_rsUpP) && empty($pcomm_rsUpP) && empty($pstate_rsUpP)){
			$query_rsUpP =  $db->prepare("SELECT p.*, p.projname AS name, p.projdepartment AS SCT, p.projcost AS pcost, p.projstartdate AS stdate, p.projenddate AS endate, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r WHERE p.deleted ='0' AND p.projstatus = 'Completed' AND p.projfscyear = '$pfscyr_rsUpP' ORDER BY p.projdepartment ASC");
		}
		elseif(empty($pfscyr_rsUpP) && !empty($psector_rsUpP) && empty($pdept_rsUpP) && empty($pcomm_rsUpP) && empty($pstate_rsUpP)){
			$query_rsUpP =  $db->prepare("SELECT p.*, p.projname AS name, p.projdepartment AS SCT, p.projcost AS pcost, p.projstartdate AS stdate, p.projenddate AS endate, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r WHERE p.deleted ='0' AND p.projstatus = 'Completed' AND p.projsector = '$psector_rsUpP'  ORDER BY p.projdepartment ASC");
		}
		elseif(empty($pfscyr_rsUpP) && empty($psector_rsUpP) && !empty($pdept_rsUpP) && empty($pcomm_rsUpP) && empty($pstate_rsUpP)){
			$query_rsUpP =  $db->prepare("SELECT p.*, p.projname AS name, p.projdepartment AS SCT, p.projcost AS pcost, p.projstartdate AS stdate, p.projenddate AS endate, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r WHERE p.deleted ='0' AND p.projstatus = 'Completed' AND p.projdepartment = '$pdept_rsUpP' ORDER BY p.projdepartment ASC");
		}
		elseif(empty($pfscyr_rsUpP) && empty($psector_rsUpP) && !empty($pdept_rsUpP) && !empty($pcomm_rsUpP) && !empty($pstate_rsUpP)){
			$query_rsUpP =  $db->prepare("SELECT p.*, p.projname AS name, p.projdepartment AS SCT, p.projcost AS pcost, p.projstartdate AS stdate, p.projenddate AS endate, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r WHERE p.deleted ='0' AND p.projstatus = 'Completed' AND p.projdepartment = '$pdept_rsUpP' AND p.projcommunity = '$pcomm_rsUpP' AND p.projlga = '$pstate_rsUpP' ORDER BY p.projdepartment ASC");
		}
		elseif(empty($pfscyr_rsUpP) && empty($psector_rsUpP) && empty($pdept_rsUpP) && !empty($pcomm_rsUpP) && !empty($pstate_rsUpP)){
			$query_rsUpP =  $db->prepare("SELECT p.*, p.projname AS name, p.projdepartment AS SCT, p.projcost AS pcost, p.projstartdate AS stdate, p.projenddate AS endate, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r WHERE p.deleted ='0' AND p.projstatus = 'Completed' AND p.projcommunity = '$pcomm_rsUpP' AND p.projlga = '$pstate_rsUpP' ORDER BY p.projdepartment ASC");
		}
		elseif(empty($pfscyr_rsUpP) && empty($psector_rsUpP) && empty($pdept_rsUpP) && !empty($pcomm_rsUpP) && empty($pstate_rsUpP)){
			$query_rsUpP =  $db->prepare("SELECT p.*, p.projname AS name, p.projdepartment AS SCT, p.projcost AS pcost, p.projstartdate AS stdate, p.projenddate AS endate, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r WHERE p.deleted ='0' AND p.projstatus = 'Completed' AND p.projcommunity = '$pcomm_rsUpP' ORDER BY p.projdepartment ASC");
		}
		elseif(empty($pfscyr_rsUpP) && empty($psector_rsUpP) && empty($pdept_rsUpP) && empty($pcomm_rsUpP) && !empty($pstate_rsUpP)){
			$query_rsUpP =  $db->prepare("SELECT p.*, p.projname AS name, p.projdepartment AS SCT, p.projcost AS pcost, p.projstartdate AS stdate, p.projenddate AS endate, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r WHERE p.deleted ='0' AND p.projstatus = 'Completed' AND p.projlga = '$pstate_rsUpP' ORDER BY p.projdepartment ASC");
		}
		elseif(!empty($pfscyr_rsUpP) && !empty($psector_rsUpP) && !empty($pdept_rsUpP) && empty($pcomm_rsUpP) && !empty($pstate_rsUpP)){
			$query_rsUpP =  $db->prepare("SELECT p.*, p.projname AS name, p.projdepartment AS SCT, p.projcost AS pcost, p.projstartdate AS stdate, p.projenddate AS endate, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r WHERE p.deleted ='0' AND p.projstatus = 'Completed' AND p.projfscyear = '$pfscyr_rsUpP' AND p.projsector = '$psector_rsUpP' AND p.projdepartment = '$pdept_rsUpP' AND p.projlga = '$pstate_rsUpP' ORDER BY p.projdepartment ASC");
		}
		elseif(empty($pfscyr_rsUpP) && empty($psector_rsUpP) && empty($pdept_rsUpP) && empty($pcomm_rsUpP) && empty($pstate_rsUpP)){
			$query_rsUpP =  $db->prepare("SELECT p.*, p.projname AS name, p.projdepartment AS SCT, p.projcost AS pcost, p.projstartdate AS stdate, p.projenddate AS endate, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r WHERE p.deleted ='0' AND p.projstatus = 'Completed' ORDER BY p.projdepartment ASC");
		}
	} */
	else{
		$query_rsUpP =  $db->prepare("SELECT p.*, p.projname AS name, p.projdepartment AS SCT, p.projcost AS pcost, p.projstartdate AS stdate, p.projenddate AS endate, SUM(amountrequested) AS TotalReq, SUM(amountpaid) AS TotalDis FROM tbl_projects p inner join tbl_payments_disbursed d on d.projid=p.projid inner join tbl_payments_request r on r.id=d.reqid WHERE p.deleted ='0' AND p.projstatus = 'Completed' ORDER BY p.projdepartment ASC");
	}
	$query_rsUpP->execute();		
	$row_rsUpP = $query_rsUpP->fetch();
	$totalRows_rsUpP = $query_rsUpP->rowCount();

	if (isset($_GET['totalRows_rsUpP'])) {
		$totalRows_rsUpP = $_GET['totalRows_rsUpP'];
	} else {
		$totalRows_rsUpP = $query_rsUpP->rowCount();
	}
	
	$query_rsYear =  $db->prepare("SELECT DISTINCT projfscyear FROM tbl_projects WHERE projfscyear IS NOT NULL ORDER BY projid ASC");
	$query_rsYear->execute();		
	$row_rsYear = $query_rsYear->fetch();
	$totalRows_rsYear = $query_rsYear->rowCount();
	
	$query_rsSector =  $db->prepare("SELECT DISTINCT projsector FROM tbl_projects WHERE projsector IS NOT NULL ORDER BY projid ASC");
	$query_rsSector->execute();		
	$row_rsSector = $query_rsSector->fetch();
	$totalRows_rsSector = $query_rsSector->rowCount();
	
	$query_rsDept =  $db->prepare("SELECT DISTINCT projdepartment FROM tbl_projects WHERE projdepartment IS NOT NULL ORDER BY projid ASC");
	$query_rsDept->execute();		
	$row_rsDept = $query_rsDept->fetch();
	$totalRows_rsDept = $query_rsDept->rowCount();
	
	$query_rsPName =  $db->prepare("SELECT projname FROM tbl_projects ORDER BY projname ASC");
	$query_rsPName->execute();		
	$row_rsPName = $query_rsPName->fetch();
	$totalRows_rsPName = $query_rsPName->rowCount();
	
	$query_rsComm =  $db->prepare("SELECT DISTINCT projcommunity FROM tbl_projects ORDER BY projcommunity ASC");
	$query_rsComm->execute();		
	$row_rsComm = $query_rsComm->fetch();
	$totalRows_rsComm = $query_rsComm->rowCount();
	
	$query_rsState =  $db->prepare("SELECT DISTINCT projlga FROM tbl_projects ORDER BY projlga ASC");
	$query_rsState->execute();		
	$row_rsState = $query_rsState->fetch();
	$totalRows_rsState = $query_rsState->rowCount();
	
	$query_rsPType =  $db->prepare("SELECT DISTINCT projtype FROM tbl_projects ORDER BY projtype ASC");
	$query_rsPType->execute();		
	$row_rsPType = $query_rsPType->fetch();
	$totalRows_rsPType = $query_rsPType->rowCount();
	
	$query_rsPStatus =  $db->prepare("SELECT DISTINCT projstatus FROM tbl_projects ORDER BY projstatus ASC");
	$query_rsPStatus->execute();		
	$row_rsPStatus = $query_rsPStatus->fetch();
	$totalRows_rsPStatus = $query_rsPStatus->rowCount();

	if (isset($_GET['srccost1'])) {
	  $colname_rsCost = $_GET['srccost1'];
	}

	if (isset($_GET['srccost2'])) {
	  $scrcost2_rsCost = $_GET['srccost2'];
	}
	
	$query_rsCost =  $db->prepare("SELECT p.*, FORMAT(p.projcost, 2), DATE_FORMAT( p.projstartdate,  '%%d %%M %%Y' ) AS stdate, DATE_FORMAT( p.projenddate,  '%%d %%M %%Y' ) AS endate, @curRow := @curRow + 1 AS sn FROM tbl_projects p JOIN (SELECT @curRow := 0) r WHERE (p.projcost >= '$colname_rsCost' AND p.projcost <= '$scrcost2_rsCost') OR (p.projcost >= '$colname_rsCost')  OR (p.projcost <= '$scrcost2_rsCost') ORDER BY p.projid DESC");
	$query_rsCost->execute();		
	$row_rsCost = $query_rsCost->fetch();
	$totalRows_rsCost = $query_rsCost->rowCount();

	$queryString_rsMyP = "";
	if (!empty($_SERVER['QUERY_STRING'])) {
		$params = explode("&", $_SERVER['QUERY_STRING']);
		$newParams = array();
		foreach ($params as $param) {
			if (stristr($param, "pageNum_rsMyP") == false && stristr($param, "totalRows_rsMyP") == false) {
				array_push($newParams, $param);
			}
		}
		if (count($newParams) != 0) {
			$queryString_rsMyP = "&" . htmlentities(implode("&", $newParams));
		}
	}

	$queryString_rsUpP = "";
	if (!empty($_SERVER['QUERY_STRING'])) {
		$params = explode("&", $_SERVER['QUERY_STRING']);
		$newParams = array();
		foreach ($params as $param) {
			if (stristr($param, "pageNum_rsUpP") == false && stristr($param, "totalRows_rsUpP") == false) {
				array_push($newParams, $param);
			}
		}
		if (count($newParams) != 0) {
			$queryString_rsUpP = "&" . htmlentities(implode("&", $newParams));
		}
	}
	
	

}catch (PDOException $ex){
    $result = flashMessage("An error occurred: " .$ex->getMessage());
	echo $result;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Projtrac M&E - Pending Bills Report</title>
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="projtrac-dashboard/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="projtrac-dashboard/plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="projtrac-dashboard/plugins/animate-css/animate.css" rel="stylesheet" />

    <!--WaitMe Css-->
    <link href="projtrac-dashboard/plugins/waitme/waitMe.css" rel="stylesheet" />

    <!-- Multi Select Css -->
    <link href="projtrac-dashboard/plugins/multi-select/css/multi-select.css" rel="stylesheet">

    <!-- Bootstrap Spinner Css -->
    <link href="projtrac-dashboard/plugins/jquery-spinner/css/bootstrap-spinner.css" rel="stylesheet">

    <!-- Bootstrap Tagsinput Css -->
    <link href="projtrac-dashboard/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">

    <!-- Bootstrap Select Css -->
    <link href="projtrac-dashboard/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

    <!-- JQuery DataTable Css -->
    <link href="projtrac-dashboard/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

    <!-- Custom Css -->
    <link href="projtrac-dashboard/css/style.css" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="projtrac-dashboard/css/themes/all-themes.css" rel="stylesheet" />
	<!-- InstanceBeginEditable name="head" -->
	<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('.tabs .tab-links a').on('click', function(e)  {
			var currentAttrValue = jQuery(this).attr('href');
	 
			// Show/Hide Tabs
			jQuery('.tabs ' + currentAttrValue).show().siblings().hide();
	 
			// Change/remove current tab to active
			jQuery(this).parent('li').addClass('active').siblings().removeClass('active');
	 
			e.preventDefault();
		});
	});
	</script>

	<script type="text/javascript" >
	$(document).ready(function()
	{
	$(".account").click(function()
	{
	var X=$(this).attr('id');

	if(X==1)
	{
	$(".submenus").hide();
	$(this).attr('id', '0');	
	}
	else
	{

	$(".submenus").show();
	$(this).attr('id', '1');
	}
		
	});

	//Mouseup textarea false
	$(".submenus").mouseup(function()
	{
	return false
	});
	$(".account").mouseup(function()
	{
	return false
	});


	//Textarea without editing.
	$(document).mouseup(function()
	{
	$(".submenus").hide();
	$(".account").attr('id', '');
	});
		
	});</script>
	<link rel="stylesheet" href="projtrac-dashboard/ajxmenu.css" type="text/css" />
	<script src="projtrac-dashboard/ajxmenu.js" type="text/javascript"></script>

    <link href="css/left_menu.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<style>
	#links a {
		color: #FFFFFF;
		text-decoration: none;
	}  </style>
	<script type="text/javascript">
	function GetProjInfo(id)
	{
		$.ajax({
			type: 'post',
			url: 'getprojinfo.php',
			data: {member:id,req:'1'},
			success: function (data) {
				$('#formcontent').html(data);
				$("#myModal").modal({backdrop: "static"});
			}
		});
	}

	</script>
</head>

<body class="theme-blue">
    <!-- Page Loader --
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    <!-- Top Bar -->
    <nav class="navbar" style="height:69px; padding-top:-10px">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <img src="images/logo.png" alt="logo" width="239" height="39">
            </div>
			
        </div>
    </nav>
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info">
                <div class="image">
                    <img src="images/user.png" width="48" height="48" alt="User" />
                </div>
				<?php
				include_once("includes/user-info.php");
				?>
            </div>
            <!-- #User Info -->
            <!-- Menu -->        
			<?php
			 include_once("includes/sidebar.php");
			?>
            <!-- #Menu -->
            <!-- Footer -->
			<div class="legal">
				<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 copyright">
					ProjTrac M&E - Your Best Result-Based Monitoring & Evaluation System.
				</div>
				<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 version" align="right">
					Copyright @ 2017 - 2019. ProjTrac Systems Ltd.
				</div>
			</div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
    </section>

    <section class="content" style="margin-top:-20px; padding-bottom:0px">
        <div class="container-fluid">
			<div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="header">
                            <div class="button-demo">
								<span class="label bg-black" style="font-size:19px"><img src="images/proj-icon.png" alt="img" style="vertical-align:middle" /> Reports Menu</span>
                                <a href="projgeneralreport" class="btn bg-light-blue waves-effect" style="margin-top:10px">General Report</a>
                                <a href="alloutputsreport" class="btn bg-light-blue waves-effect" style="margin-top:10px">Outputs Progress Report</a>
                                <a href="projfundingreport" class="btn bg-light-blue waves-effect" style="margin-top:10px">Financial Report</a>
                                <a href="#" class="btn bg-grey waves-effect" style="margin-top:10px">Pending Bills Report</a>
							</div>
						</div>
				</div>
            </div>
            <div class="block-header">
				<?php 
				if(isset($_GET["msg"]) && $_GET["type"] == "fail"){
				?>
					<div class="alert alert-warning">
						<strong>Warning!</strong> <?php echo $_GET["msg"]; ?>
					</div>
				<?php
				}
				elseif(isset($_GET["msg"]) && $_GET["type"] == "success"){
				?>
					<div class="alert alert-success">
						<strong>Success!</strong> <?php echo $_GET["msg"]; ?>
					</div>
				<?php
				}
				?>
            </div>
            <!-- Exportable Table -->
			<?php  include_once('projpendingbillsreport-insider.php');?>
            <!-- #END# Exportable Table -->
        </div>
    </section>

    <!-- Jquery Core Js -->
    <script src="projtrac-dashboard/plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="projtrac-dashboard/plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    <script src="projtrac-dashboard/plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="projtrac-dashboard/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="projtrac-dashboard/plugins/node-waves/waves.js"></script>

    <!-- Input Mask Plugin Js -->
    <script src="projtrac-dashboard/plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>

    <!-- Multi Select Plugin Js -->
    <script src="projtrac-dashboard/plugins/multi-select/js/jquery.multi-select.js"></script>
	
    <!-- Jquery Spinner Plugin Js -->
    <script src="projtrac-dashboard/plugins/jquery-spinner/js/jquery.spinner.js"></script>
	
    <!-- Bootstrap Tags Input Plugin Js -->
    <script src="projtrac-dashboard/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>

    <!-- Jquery DataTable Plugin Js -->
    <script src="projtrac-dashboard/plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="projtrac-dashboard/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="projtrac-dashboard/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
    <script src="projtrac-dashboard/plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
    <script src="projtrac-dashboard/plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
    <script src="projtrac-dashboard/plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
    <script src="projtrac-dashboard/plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
    <script src="projtrac-dashboard/plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
    <script src="projtrac-dashboard/plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>

    <!-- Custom Js -->
    <script src="projtrac-dashboard/js/admin.js"></script>
    <script src="projtrac-dashboard/js/pages/tables/jquery-datatable.js"></script>
    <script src="projtrac-dashboard/js/pages/ui/tooltips-popovers.js"></script>

    <!-- Demo Js -->
    <script src="projtrac-dashboard/js/demo.js"></script>
</body>

</html>