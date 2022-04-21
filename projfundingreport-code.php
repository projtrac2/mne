<?php
			if($totalRows_rsUpP == 0){
				?>
				<tr id="rows" style="border:#EEE thin solid">
					<td  colspan="9" height="30" id="formcells4"><div align="center" id="formcells9" style="color:red; font-size:14px"><strong>Sorry No Record Found!!</strong></div></td>
				</tr>
            <?php }
			else{
				do { 
					$myprojid = $row_rsUpP['projid'];
					$projsector = $row_rsUpP['SCT'];
					
					$query_rsUpPF = $db->prepare("SELECT SUM(tbl_funding.amtreq) AS TotalReq, SUM(tbl_funding.amtdis) AS TotalDis, @curRow := @curRow + 1 AS sn FROM tbl_funding JOIN (SELECT @curRow := 0) r WHERE tbl_funding.projid LIKE '%" . $myprojid . "%' ORDER BY tbl_funding.projid DESC");
					$query_rsUpPF->execute();		
					$row_rsUpPF = $query_rsUpPF->fetch();
		
					$totaldis = $row_rsUpPF['TotalDis'];
					$projcost = $row_rsUpP['projcost'];	
					$totalreq = $row_rsUpPF['TotalReq'];
					$abrate = ($totaldis / $projcost) * 100;
					$otbal= $projcost - $totaldis;
					$myprojcost = number_format($projcost, 2);
					$mytotaldis = number_format($totaldis, 2);
					$mytotalreq = number_format($totalreq, 2);
					$outstandingbal = number_format($otbal, 2);
					$abrate = round($abrate, 2);	
					
					$query_rwSector = $db->prepare("SELECT sector FROM tbl_sectors WHERE stid='$projsector'");
					$query_rwSector->execute();		
					$row_rwSector = $query_rwSector->fetch();
					$totalRows_rwSector = $query_rwSector->rowCount();
					
					$myprojsector = $row_rwSector['sector'];
				?>
					<tr>
						<td width="3%"><?php echo $row_rsUpP['sn']; ?><br/><a href="printfproject?projid=<?php echo $myprojid; ?>" target="new"><img src="images/report.png" alt="report" width="18" height="18" data-toggle="tooltip" data-placement="right" title="Click here to view detailed report"/></a></td>
						<td width="22%"><?php echo $row_rsUpP['name']; ?></td>
						<td width="6%" align="center"><?php echo $row_rsUpP['projstatus']; ?></td>
						<td width="12%"><?php echo $myprojsector; ?></td>
						<td width="13%"><?php echo $myprojcost; ?></td>
						<td width="10%"><?php echo $mytotaldis; ?></td>
						<td width="13%"><?php echo $outstandingbal; ?></td>
						<td width="7%"><?php echo $abrate."%"; ?></td>
						<td width="7%"><?php echo date("d M Y", strtotime($row_rsUpP['stdate'])); ?></td>
						<td width="7%"><?php echo date("d M Y", strtotime($row_rsUpP['endate'])); ?></td>
					</tr>
            <?php 
				} while ($row_rsUpP = $query_rsUpP->fetch()); 	
			}
			?>