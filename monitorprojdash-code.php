				<?php
				try {
					//code...
				
				$nm = 0;
				do {
					$nm = $nm + 1;
					$mndate = strtotime($row_rsPuM['madate']);
					$prjmndate = date("d M Y", $mndate);
					if (isset($_GET['projid'])) {
						$colname_rsPuM = $_GET['projid'];
					}
					$query_rsTaskPrg =  $db->prepare("SELECT * FROM tbl_task WHERE projid='$colname_rsPuM' and (status='Task In Progress' OR status='Overdue Task' OR status='Task Behind Schedule') ORDER BY msid ASC");
					$query_rsTaskPrg->execute();
					$row_rsTaskPrg = $query_rsTaskPrg->fetch();
					$totalRows_rsTaskPrg = $query_rsTaskPrg->rowCount();
				?>
					<tr>
						<td width="3%"><?php echo $nm; ?></td>
						<td width="32%"><?php do {
											echo "-" . $row_rsTaskPrg['task'] . "; <br>";
										} while ($row_rsTaskPrg = $query_rsTaskPrg->fetch()); ?></td>
						<td width="20%"><?php echo $row_rsPuM['observations']; ?></td>
						<td width="20%"><?php echo $row_rsPuM['lessons']; ?></td>
						<td width="10%"><?php echo $prjmndate; ?></td>
						<td width="5%"><a href="vmproject?projid=<?php echo $row_rsMyP['projid']; ?>&amp;mid=<?php echo $row_rsPuM['mid']; ?>"><img src="images/more.png" alt="" width="51" height="17" title="Preview" /></a></td>
						<td width="5%">
							<?php if ($LastRows_rsLastMonitor == $row_rsPuM['mid']) {
							?><a href="editmonitor?mtid=<?php echo $row_rsPuM['mid']; ?>"><img src="images/edit.png" alt="edit" /></a>
							<?php } ?></td>
						<td width="5%">
							<?php if ($LastRows_rsLastMonitor == $row_rsPuM['mid']) {
							?><a href="delmonitor.php?mtid=<?php echo $row_rsPuM['mid']; ?>&projid=<?php echo $row_rsMyP['projid']; ?>" onclick="return confirm('Are you sure you want to delete this monitoring record?')"><img src="images/delete.png" alt="del" /></a>
							<?php } ?></td>
					</tr>
				<?php
				} while ($row_rsPuM = $query_rsPuM->fetch()); 
				
				} catch (\PDOException $th) {
					customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
				}
				?>