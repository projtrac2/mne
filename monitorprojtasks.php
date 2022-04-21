<?php
//$num = count($rsTaskPrg);
$num = 0;
do {
	$num = $num + 1;
	$tskid = $row_rsTaskPrg['tkid'];
	$tkmsid = $row_rsTaskPrg['msid'];
	$tsksts = $row_rsTaskPrg['status'];
	if ($tsksts == "Task Behind Schedule") {
		$tsksts = "Behind Schedule";
	} elseif ($tsksts == "Task In Progress") {
		$tsksts = "In Progress";
	}

	$indicatorid = $row_rsTaskPrg["taskindicator"];
	mysql_select_db($database_ProjMonEva, $ProjMonEva);
	$query_rsIndicators = sprintf("SELECT * FROM tbl_indicator WHERE indid=%s ORDER BY indname ASC", GetSQLValueString($indicatorid, "int"));
	$rsIndicators = mysql_query($query_rsIndicators, $ProjMonEva) or die(mysql_error());
	$row_rsIndicators = mysql_fetch_assoc($rsIndicators);
	$totalRows_rsIndicators = mysql_num_rows($rsIndicators);

	mysql_select_db($database_ProjMonEva, $ProjMonEva);
	$query_tkMs = "SELECT milestone FROM tbl_milestone WHERE msid='$tkmsid' ORDER BY msid DESC LIMIT 1";
	$tkMs = mysql_query($query_tkMs, $ProjMonEva) or die(mysql_error());
	$row_tkMs = mysql_fetch_assoc($tkMs);

	$Prg = $row_rsTaskPrg["progress"];
	$remainingPrg = 100 - $Prg;

	if ($Prg < 100) {
?>
		<tr id="rowlines">
			<td><?php echo $num; ?></td>
			<td><?php echo $row_rsTaskPrg['task']; ?></td>
			<td><?php echo $row_tkMs['milestone']; ?></td>
			<td><?php echo $tsksts; ?></td>
			<td><?php echo $Prg . "%"; ?></td>
			<td><button type="button" class="btn bg-light-green waves-effect" onclick="javascript:GetTaskChecklist(<?php echo $row_rsTaskPrg['tkid']; ?>)" data-toggle="tooltip" data-placement="bottom" title="Click here to see the checklist">Remaining: <span class="badge"><?php echo $remainingPrg . "%"; ?></span></button></td>
			<td>
				<input type="text" name="progress[]" id="<?php echo $row_rsTaskPrg['tkid']; ?>" class="form-control" value="" readonly="readonly" style="border:#CCC thin solid; border-radius: 5px; width:60px" />
			</td>
			<input type="hidden" name="indicator[]" id="indicator" value="<?php echo $row_rsIndicators['indid']; ?>" />
			<input type="hidden" name="tskid[]" id="tskid" value="<?php echo $row_rsTaskPrg['tkid']; ?>" />
			<input type="hidden" name="formid[]" id="formid" value="<?php echo $pmtid; ?>" />
		</tr>
<?php
	}
} while ($row_rsTaskPrg = mysql_fetch_assoc($rsTaskPrg));
?>