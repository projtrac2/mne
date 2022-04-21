<?php

include_once 'projtrac-dashboard/resource/Database.php';
include_once 'projtrac-dashboard/resource/utilities.php';

try{
	$query_rsDelProjs =  $db->prepare("SELECT projid FROM tbl_projects where deleted='1' ORDER BY projid ASC");
	$query_rsDelProjs->execute();	

	while($row = $query_rsDelProjs->fetch()){
		$projid = $row["projid"];

		$query_rsProjMstn =  $db->prepare("SELECT msid FROM tbl_milestone WHERE projid='$projid' ORDER BY msid ASC");
		$query_rsProjMstn->execute();		
		
		while($row_rsProjMstn = $query_rsProjMstn->fetch()){
			$msid = $row_rsProjMstn["msid"];
			
			$query_rsMiltsk =  $db->prepare("SELECT tkid FROM tbl_task WHERE msid='$msid' ORDER BY tkid ASC");
			$query_rsMiltsk->execute();	
			
			while($rowTsk = $query_rsMiltsk->fetch()){
				$tkid = $rowTsk["tkid"];
			
				$query_rsTskChk = $db->prepare("SELECT ckid FROM tbl_task_checklist WHERE taskid='$tkid' ORDER BY ckid ASC");
				$query_rsTskChk->execute();		
			
				while($rowTskChk = $query_rsTskChk->fetch()){
					$ckid = $rowTsk["ckid"];
					
					$del_rsTskChkSc = $db->prepare("DELETE FROM tbl_task_checklist_score WHERE checklistid='$ckid'");
					$del_rsTskChkSc->execute();
				}
				
				$query_rsInspChk = $db->prepare("SELECT ckid, assigneecomments FROM tbl_project_inspection_checklist WHERE taskid='$tkid' ORDER BY ckid ASC");
				$query_rsInspChk->execute();		
			
				while($rowInspChk = $query_rsInspChk->fetch()){
					$Inspckid = $rowInspChk["ckid"];
					$asscomnts = $rowInspChk["assigneecomments"];
					
					$del_rsInspChkSc = $db->prepare("DELETE FROM tbl_project_inspection_checklist_comments WHERE id='$asscomnts'");
					$del_rsInspChkSc->execute();
					
					$del_rsTskChkCmt = $db->prepare("DELETE FROM tbl_project_checklist_noncompliance_comments WHERE ckid='$Inspckid'");
					$del_rsTskChkCmt->execute();
				}
				
				$query_rsTskFundReq = $db->prepare("SELECT id FROM tbl_payments_request WHERE itemid='$tkid' AND itemcategory=1 ORDER BY id ASC");
				$query_rsTskFundReq->execute();	
			
				while($rowTskFundReq = $query_rsTskFundReq->fetch()){
					$TskFundReqId = $rowTskFundReq["id"];
					
					$del_rsTskFunddisb = $db->prepare("DELETE FROM tbl_payments_disbursed WHERE reqid='$TskFundReqId'");
					$del_rsTskFunddisb->execute();
				
					$del_rsTskFundReqComm = $db->prepare("DELETE FROM tbl_payment_request_comments WHERE reqid='$TskFundReqId'");
					$del_rsTskFundReqComm->execute();
				
					$del_rsTskFundReq = $db->prepare("DELETE FROM tbl_payments_request WHERE id='$TskFundReqId'");
					$del_rsTskFundReq->execute();
				}
				
				$del_rsTskPrg = $db->prepare("DELETE FROM tbl_task_progress WHERE tkid='$tkid'");
				$del_rsTskPrg->execute();
				
				$del_rsTskChk = $db->prepare("DELETE FROM tbl_project_inspection_checklist WHERE taskid='$tkid'");
				$del_rsTskChk->execute();
				
				$del_rsTskCklst = $db->prepare("DELETE FROM tbl_task_checklist WHERE taskid='$tkid'");
				$del_rsTskCklst->execute();
				
				$del_rsTskCert = $db->prepare("DELETE FROM tbl_certificates WHERE category=1 AND itemid='$tkid'");
				$del_rsTskCert->execute();
				
				$del_rsTsk = $db->prepare("DELETE FROM tbl_task WHERE tkid='$tkid'");
				$del_rsTsk->execute();
			}
				
			$query_rsMstnFundReq = $db->prepare("SELECT id FROM tbl_payments_request WHERE itemid='$msid' AND itemcategory=2 ORDER BY id ASC");
			$query_rsMstnFundReq->execute();	
		
			while($rowMstnFundReq = $query_rsMstnFundReq->fetch()){
				$rowMstnFundReqId = $rowMstnFundReq["id"];
				
				$del_rsMstnFunddisb = $db->prepare("DELETE FROM tbl_payments_disbursed WHERE reqid='$rowMstnFundReqId'");
				$del_rsMstnFunddisb->execute();
			
				$del_rsMstnFundReqComm = $db->prepare("DELETE FROM tbl_payment_request_comments WHERE reqid='$rowMstnFundReqId'");
				$del_rsMstnFundReqComm->execute();
			
				$del_rsMstnFundReq = $db->prepare("DELETE FROM tbl_payments_request WHERE id='$rowMstnFundReqId'");
				$del_rsMstnFundReq->execute();
			}
				
			$del_rsMstInspGisLoc = $db->prepare("DELETE FROM tbl_project_inspection_gis_location WHERE msid='$msid'");
			$del_rsMstInspGisLoc->execute();
				
			$del_rsTskCert = $db->prepare("DELETE FROM tbl_certificates WHERE category=2 AND itemid='$msid'");
			$del_rsTskCert->execute();
			
			$del_rsMstn = $db->prepare("DELETE FROM tbl_milestone WHERE msid='$msid'");
			$del_rsMstn->execute();
		}
				
		$query_rsTenderDetails = $db->prepare("SELECT td_id FROM tbl_tenderdetails WHERE projid='$projid' ORDER BY td_id ASC");
		$query_rsTenderDetails->execute();	

		while($rowTenderDetails = $query_rsTenderDetails->fetch()){
			$rowTenderId = $rowTenderDetails["td_id"];
			
			$del_rsTenderDoc = $db->prepare("DELETE FROM tbl_tenderdocuments WHERE td_id='$rowTenderId'");
			$del_rsTenderDoc->execute();
		}
		
		$del_rsTenderDetails = $db->prepare("DELETE FROM tbl_tenderdetails WHERE projid='$projid'");
		$del_rsTenderDetails->execute();
			 
		$del_rsPrjStatusChangeReason = $db->prepare("DELETE FROM tbl_projstatuschangereason WHERE projid='$projid'");
		$del_rsPrjStatusChangeReason->execute();
		
		$del_rsPrjMembers = $db->prepare("DELETE FROM tbl_projmembers WHERE projid='$projid'");
		$del_rsPrjMembers->execute();
		
		$del_rsPrjIssues = $db->prepare("DELETE FROM tbl_projissues WHERE projid='$projid'");
		$del_rsPrjIssues->execute();
		
		$del_rsPrjRiskScore = $db->prepare("DELETE FROM tbl_project_riskscore WHERE projid='$projid'");
		$del_rsPrjRiskScore->execute();
		
		$del_rsPrjPhotos = $db->prepare("DELETE FROM tbl_projects_photos WHERE projid='$projid'");
		$del_rsPrjPhotos->execute();
		
		$del_rsPrjStages = $db->prepare("DELETE FROM tbl_projectstages WHERE projid='$projid'");
		$del_rsPrjStages->execute();
		
		$del_rsPrjRisk = $db->prepare("DELETE FROM tbl_projectrisks WHERE projid='$projid'");
		$del_rsPrjRisk->execute();
		
		$del_rsPrjNotifs = $db->prepare("DELETE FROM tbl_notifications WHERE projid='$projid'");
		$del_rsPrjNotifs->execute();
		
		$del_rsPrjPartners = $db->prepare("DELETE FROM tbl_myprojpartner WHERE projid='$projid'");
		$del_rsPrjPartners->execute();
		
		$del_rsPrjMsgComm = $db->prepare("DELETE FROM tbl_msgcomments WHERE projid='$projid'");
		$del_rsPrjMsgComm->execute();
		
		$del_rsPrjMonOP = $db->prepare("DELETE FROM tbl_monitoringoutput WHERE projid='$projid'");
		$del_rsPrjMonOP->execute();
		
		$del_rsPrjMonitor = $db->prepare("DELETE FROM tbl_monitoring WHERE projid='$projid'");
		$del_rsPrjMonitor->execute();
		
		$del_rsPrjMsg = $db->prepare("DELETE FROM tbl_messages WHERE projid='$projid'");
		$del_rsPrjMsg->execute();
		
		$del_rsPrjRdMarkers = $db->prepare("DELETE FROM tbl_map_markers_road WHERE projid='$projid'");
		$del_rsPrjRdMarkers->execute();
		
		$del_rsPrjGenMarkers = $db->prepare("DELETE FROM tbl_map_markers WHERE projid='$projid'");
		$del_rsPrjGenMarkers->execute();
		
		$del_rsPrjImpact = $db->prepare("DELETE FROM tbl_impact WHERE projid='$projid'");
		$del_rsPrjImpact->execute();
		
		$del_rsPrjFunding = $db->prepare("DELETE FROM tbl_funding WHERE projid='$projid'");
		$del_rsPrjFunding->execute();
		
		$del_rsPrjFiles = $db->prepare("DELETE FROM tbl_files WHERE projid='$projid'");
		$del_rsPrjFiles->execute();
		
		$del_rsPrjExpOP = $db->prepare("DELETE FROM tbl_expprojoutput WHERE projid='$projid'");
		$del_rsPrjExpOP->execute();
		
		$del_rsPrjEvaluation = $db->prepare("DELETE FROM tbl_evaluation WHERE projid='$projid'");
		$del_rsPrjEvaluation->execute();
		
		$del_rsPrjAssumption = $db->prepare("DELETE FROM tbl_assumption WHERE projid='$projid'");
		$del_rsPrjAssumption->execute();
		
		$del_rsProject = $db->prepare("DELETE FROM tbl_projects WHERE projid='$projid'");
		$del_rsProject->execute(); 
	}
}
catch (PDOException $ex){
    $result = flashMessage("An error occurred: " .$ex->getMessage());
    print($result);
}

?>