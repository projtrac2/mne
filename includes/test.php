<?php
	function projfy()
	{
		global $db;
		$projfy = $db->prepare("SELECT * FROM tbl_fiscal_year WHERE status=1");
		$projfy->execute();
		while ($row = $projfy->fetch()) {
			echo '<option value="' . $row['id'] . '">' . $row['year'] . '</option>';
		}
	}

	$yr = date("Y");
	$mnth = date("m");
	$startmnth = 07;
	$endmnth = 06;

	if ($mnth >= 7 && $mnth <= 12) {
		$startyear = $yr;
		$endyear = $yr + 1;
	} elseif ($mnth >= 1 && $mnth <= 6) {
		$startyear = $yr - 1;
		$endyear = $yr;
	}

	$base_url = "";

	//$quarter_dates_arr = ["-07-01", "-09-30", "-10-01", "-12-31", "-01-01", "-03-30", "-04-01","-06-30"];

	$query_rsFscYear =  $db->prepare("SELECT id, yr FROM tbl_fiscal_year where yr =:year ");
	$query_rsFscYear->execute(array(":year" => $startyear));
	$row_rsFscYear = $query_rsFscYear->fetch();
	$fyid = $row_rsFscYear['id'];
	$financialyear = $startyear . "/" . $endyear;

	$basedate = $startyear . "-06-30";
	$startq1 = $startyear . "-07-01";
	$endq1 = $startyear . "-09-30";
	$startq2 = $startyear . "-10-01";
	$endq2 = $startyear . "-12-31";
	$startq3 = $endyear . "-01-01";
	$endq3 = $endyear . "-03-31";
	$startq4 = $endyear . "-04-01";
	$endq4 = $endyear . "-06-30";

	$quarter_one_rate = "N/A";
	$quarter_two_rate = "N/A";
	$quarter_three_rate = "N/A";
	$quarter_four_rate = "N/A";

	$years = $target_rows = $target_quarters = '';





