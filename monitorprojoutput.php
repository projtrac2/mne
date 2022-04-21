<?php
//$num = count($rsTaskPrg);
if ($totalRows_rsPrjOP > 0) {
	$nm = 0;
	do {
		$nm = $nm + 1;
		$opid = $row_rsPrjOP['opid'];
		$expoutput = $row_rsPrjOP['expoutputname'];
		$expopindicator = $row_rsPrjOP['expoutputindicator'];
		$expopvalue = $row_rsPrjOP['expoutputvalue'];
		$opbaseline = $row_rsPrjOP['outputbaseline'];


		mysql_select_db($database_ProjMonEva, $ProjMonEva);
		$query_expOP = "SELECT output FROM tbl_outputs WHERE opid='$expoutput'";
		$expOP = mysql_query($query_expOP, $ProjMonEva) or die(mysql_error());
		$row_expOP = mysql_fetch_assoc($expOP);
		//$cnt = count($rsTaskPrg);
		//$Prg = $row_tkPrg["tot"];

		mysql_select_db($database_ProjMonEva, $ProjMonEva);
		$query_pjInd = "SELECT indname FROM tbl_indicator WHERE indid='$expopindicator' ORDER BY indid DESC LIMIT 1";
		$pjInd = mysql_query($query_pjInd, $ProjMonEva) or die(mysql_error());
		$row_pjInd = mysql_fetch_assoc($pjInd);

		mysql_select_db($database_ProjMonEva, $ProjMonEva);
		$query_cummOP = "SELECT sum(actualoutput) as total FROM tbl_monitoringoutput WHERE projid='$taskprojid'";
		$cummOP = mysql_query($query_cummOP, $ProjMonEva) or die(mysql_error());
		$row_cummOP = mysql_fetch_assoc($cummOP);
		$cummvalue = $row_cummOP['total'];

?>
		<tr id="rowlines">
			<td width="5%"><?php echo $nm; ?></td>
			<td width="40%"><?php echo $row_expOP['output']; ?></td>
			<td width="30%"><?php echo $row_pjInd['indname']; ?></td>
			<td width="10%"><?php echo $expopvalue; ?></td>
			<td width="10%"><?php echo $cummvalue; ?></td>
			<td width="7%">
				<?php
				if ($expopvalue == $cummvalue) {
				?>
					<input class="form-control" type="text" name="actualoutput" id="actualoutput" style="border:#CCC thin solid; border-radius: 5px; width:60px" readonly="readonly" value="0" required="required" />
				<?php
				} else {
					$opdiff = $expopvalue - $cummvalue;
				?>
					<input class="form-control" type="text" name="actualoutput" id="actualoutput" data-toggle="tooltip" data-placement="bottom" title="Please enter value between 1 and <?= $opdiff ?>" style="border:#CCC thin solid; border-radius: 5px; width:60px" required="required" />
				<?php
				}
				?>
			</td>
			<input type="hidden" name="opid" id="opid" value="<?php echo $opid; ?>" />
			<input type="hidden" name="opformid" id="opformid" value="<?php echo $pmtid; ?>" />
			<input type="hidden" name="targetoutput" id="targetoutput" value="<?php echo $expopvalue; ?>" />
			<input type="hidden" name="myprojid" id="myprojid" value="<?php echo $taskprojid; ?>" />
		</tr>
	<?php
	} while ($row_rsPrjOP = mysql_fetch_assoc($rsPrjOP));
} else { ?>
	<tr id="rowlines">
		<td width="5%" height="35">
			<div align="center">&nbsp;</div>
		</td>
		<td width="30%">
			<div align="left" style="margin-left:5px">There is no defined output(s) for this project</div>
		</td>
		<td width="30%">
			<div align="left">&nbsp;&nbsp;</div>
		</td>
		<td width="20%">
			<div align="left">&nbsp;&nbsp;</div>
		</td>
		<td width="15%">&nbsp;</td>
	</tr>
<?php
}
?>