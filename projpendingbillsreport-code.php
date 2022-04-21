<?php
			if($totalRows_rsUpP == 0){
				?>
				<tr>
					<td  colspan="9"><div style="color:red; font-size:14px"><strong>Sorry No Record Found!!</strong></div></td>
				</tr>
            <?php }
			else{
				$totaloutstandingbal = 0;
				while ($row_rsUpP = $query_rsUpP->fetch()){ 
					$myprojid = $row_rsUpP['projid'];
					$projsector = $row_rsUpP['SCT'];
					$projcat = $row_rsUpP['projcategory'];
					//$projcost = $row_rsUpP['projcost'];
					
					if($projcat == 2){
						$query_rstenderdetails = $db->prepare("SELECT tenderamount FROM tbl_tenderdetails WHERE projid = '$myprojid'");
						$query_rstenderdetails->execute();		
						$row_rstenderdetails = $query_rstenderdetails->fetch();
						$projcost = $row_rstenderdetails['tenderamount'];
					}else{
						$projcost = $row_rsUpP['projcost'];
					}
		
					$contractorid = $row_rsUpP['projcontractor'];	
					$totaldis = $row_rsUpP['TotalDis'];
					$projcost = $row_rsUpP['projcost'];	
					$totalreq = $row_rsUpP['TotalReq'];
					$abrate = ($totaldis / $projcost) * 100;
					$otbal= $projcost - $totaldis;
					$myprojcost = number_format($projcost, 2);
					$mytotaldis = number_format($totaldis, 2);
					$mytotalreq = number_format($totalreq, 2);
					$outstandingbal = number_format($otbal, 2);
					$abrate = round($abrate, 2);	
					
					$myprojsector = $row_rsUpP['sector'];
					$projscounty = $row_rsUpP['projcommunity'];
					$projward = $row_rsUpP['projlga'];
					$projloc = $row_rsUpP['projstate'];
					
					$query_rsContractorName = $db->prepare("SELECT contractor_name FROM tbl_contractor WHERE contrid='$contractorid'");
					$query_rsContractorName->execute();		
					$row_rsContractorName = $query_rsContractorName->fetch();
					
					$sc = $row_rsUpP['projcommunity'];
					$wards = $row_rsUpP['projlga'];
					$locs = $row_rsUpP['projstate'];					

					$query_sc =  $db->prepare("SELECT state FROM tbl_state WHERE id = '$sc'");
					$query_sc->execute();		
					$row_sc = $query_sc->fetch();
					$subcounty = $row_sc["state"];
					
					$query_ward =  $db->prepare("SELECT state FROM tbl_state WHERE id = '$wards'");
					$query_ward->execute();		
					$row_ward = $query_ward->fetch();
					$ward = $row_ward["state"];
					
					$query_locs =  $db->prepare("SELECT state FROM tbl_state WHERE id = '$locs'");
					$query_locs->execute();		
					$row_locs = $query_locs->fetch();
					$loc = $row_locs["state"];

					if($subcounty=="All"){
						$location = $subcounty." ".$level1labelplural."; ".$ward." ".$level2labelplural."; ".$loc." ".$level3labelplural;
					}else{
						$location = $subcounty." ".$level1label."; ".$ward." ".$level2label."; ".$loc." ".$level3label;
					}
				
					?>
					<tr>
						<td width="3%"><?php echo $row_rsUpP['sn']; ?></td>
						<td width="22%"><a href="projmoredetails?projid=<?php echo $row_rsUpP['projid']; ?>" style="color:#2563c6"><b><?php echo $row_rsUpP['name']; ?></b></a></td>
						<td width="12%"><?php echo $myprojsector; ?></td>
						<td width="11%"><?php echo $projlocation; ?></td>
						<td width="12%"><?php echo $myprojcost; ?></td>
						<td width="11%"><?php echo $mytotaldis; ?></td>
						<td width="12%" style="background-color:#efa6a2"><?php echo $outstandingbal; ?></td>
						<td width="7%"><?php echo $row_rsUpP['endate']; ?></td>
						<td width="10%"><a href="projcontractorinfo?contrid=<?php echo $contractorid; ?>" style="color:green"><?php echo $row_rsContractorName['contractor_name']; ?></a></td>
					</tr>
					<?php 
					$totaloutstandingbal = $totaloutstandingbal + $otbal;
				}  
				
				$TotalPendingBills = number_format($totaloutstandingbal, 2);
				?>
				<tfoot>
                    <tr style="background-color:#d4d6d8; font-size:13px">
                        <th></th>
                        <th colspan="5"><b>Total Pending Bills</b></th>
                        <th width="10%" style="background-color:#efa6a2"><b><?php echo $TotalPendingBills; ?></b></th>
                        <th colspan="2"></th>
                    </tr>
                </tfoot>
			<?php	
			}
			?>