<?php
$uri = 1;
if (isset($_GET['menuItem'])) {
	$_GET['menuItem'];
	$decode_menuItem =  base64_decode($_GET['menuItem']);
	$menuItem_array = explode("projid54321", $decode_menuItem);
	$uri = $menuItem_array[1];
}


$color = 'bg-light-blue';
$default_color = 'bg-light-blue';
switch ($uri) {
	case '1':
		$color = 'bg-grey';
		break;
	case '2':
		$color = 'bg-grey';
		break;
	case '3':
		$color = 'bg-grey';
		break;
	case '4':
		$color = 'bg-grey';
		break;
	case '5':
		$color = 'bg-grey';
		break;
	case '6':
		$color = 'bg-grey';
		break;
	default:
		$color = 'bg-light-blue';
		break;
}

$item_id1 = 1;
$hash_one = base64_encode("projid54321{$item_id1}");
$item_id1 = 3;
$hash_three = base64_encode("projid54321{$item_id1}");
$item_id1 = 4;
$hash_four = base64_encode("projid54321{$item_id1}");
$item_id1 = 5;
$hash_five = base64_encode("projid54321{$item_id1}");
$item_id1 = 6;
$hash_six = base64_encode("projid54321{$item_id1}");
?>
<div class="button-demo" style="margin-top:-15px">
	<div class="btn-group" style="margin-top:10px; margin-left:5px; background-color: transparent; border-color: transparent; box-shadow: none;">
		<a type="button" class="btn <?php if ($uri == 1) { ?> <?= $color ?> <?php } else { ?> <?= $default_color ?> <?php } ?> waves-effect dropdown-toggle" data-toggle="dropdown">
			<span class="sr-only">Settings</span>
			<span class="caret"></span>
		</a>
		<ul class="dropdown-menu" style="position:absolute; padding-left:1px; margin-left:-10px; margin-bottom:1px; padding-top:12px; background-color:#ebf3f5">
			<li style="width:100%"><a href="organization-details.php?menuItem=<?= $hash_one ?>">Organization Details</a></li>
			<li style="width:100%"><a href="system-terminologies.php?menuItem=<?= $hash_one ?>&menuItem=<?= $hash_one ?>">Global Terminologies</a></li>
			<li style="width:100%"><a href="global-configuration.php?workflow=<?= $hash_one ?>&menuItem=<?= $hash_one ?>">System Workflow Stages</a></li>
			<li style="width:100%"><a href="email_configuration.php?menuItem=<?= $hash_one ?>">Email Configuration</a></li>
			<li style="width:100%"><a href="email_templates.php?menuItem=<?= $hash_one ?>">Email Templates</a></li>
			<li style="width:100%"><a href="global-configuration.php?titles=<?= $hash_one ?>&menuItem=<?= $hash_one ?>">Titles</a></li>
		</ul>
	</div>
	<div class="btn-group" style="margin-top:10px; margin-left:-9px; background-color: light-blue; border-color: transparent; box-shadow: none;">
		<a type="button" class="btn <?php if ($uri == 3) { ?> <?= $color ?> <?php } else { ?> <?= $default_color ?> <?php } ?> waves-effect dropdown-toggle" data-toggle="dropdown">
			<span class="sr-only">Status</span>
			<span class="caret"></span>
		</a>
		<ul class="dropdown-menu" style="position:absolute; padding-left:1px; margin-left:-10px; margin-bottom:1px; padding-top:12px; background-color:#ebf3f5">
			<li style="width:100%"><a href="global-configuration.php?paymentstatus=<?= $hash_one ?>&menuItem=<?= $hash_three ?>">Payment Status</a></li>
			<li style="width:100%"><a href="global-configuration.php?projectstatus=<?= $hash_one ?>&menuItem=<?= $hash_three ?>">Project Status</a></li>
		</ul>
	</div>
	<div class="btn-group" style="margin-top:10px; margin-left:-9px; background-color: light-blue; border-color: transparent; box-shadow: none;">
		<a type="button" class="btn <?php if ($uri == 4) { ?> <?= $color ?> <?php } else { ?> <?= $default_color ?> <?php } ?> waves-effect dropdown-toggle" data-toggle="dropdown">
			<span class="sr-only">Projects</span>
			<span class="caret"></span>
		</a>
		<ul class="dropdown-menu" style="position:absolute; padding-left:1px; margin-left:-10px; margin-bottom:1px; padding-top:12px; background-color:#ebf3f5">
			<li style="width:100%"><a href="global-configuration.php?priorities=<?= $hash_one ?>&menuItem=<?= $hash_four ?>">Priorities</a></li>
			<li style="width:100%"><a href="global-configuration.php?implmethod=<?= $hash_one ?>&menuItem=<?= $hash_four ?>">Implementation Method</a></li>
			<li style="width:100%"><a href="global-configuration.php?fiscalyear=<?= $hash_one ?>&menuItem=<?= $hash_four ?>">Financial Years</a></li>
			<li style="width:100%"><a href="global-configuration.php?dtfreq=<?= $hash_one ?>&menuItem=<?= $hash_four ?>">Data Collection Frequencies</a></li>
			<li style="width:100%"><a href="global-configuration.php?roles=<?= $hash_one ?>&menuItem=<?= $hash_four ?>">Project Roles</a></li>
			<li style="width:100%"><a href="global-configuration.php?partnerRoles=<?= $hash_one ?>&menuItem=<?= $hash_four ?>">Partner Roles</a></li>
		</ul>
	</div>
	<div class="btn-group" style="margin-top:10px; margin-left:-9px; background-color: light-blue; border-color: transparent; box-shadow: none;">
		<a type="button" class="btn <?php if ($uri == 5) { ?> <?= $color ?> <?php } else { ?> <?= $default_color ?> <?php } ?> waves-effect dropdown-toggle" data-toggle="dropdown">
			<span class="sr-only">Permissions</span>
			<span class="caret"></span>
		</a>
		<ul class="dropdown-menu" style="position:absolute; padding-left:1px; margin-left:-10px; margin-bottom:1px; padding-top:12px; background-color:#ebf3f5">
			<li style="width:100%"><a href="global-configuration.php?designations=<?= $hash_one ?>&menuItem=<?= $hash_five ?>">Designations</a></li>
			<li style="width:100%"><a href="global-configuration.php?permission=<?= $hash_one ?>&menuItem=<?= $hash_five ?>">Permissions</a></li>
			<li style="width:100%"><a href="global-configuration.php?mainmenu=<?= $hash_one ?>&menuItem=<?= $hash_five ?>">Main Menu</a></li>
		</ul>
	</div>
	<div class="btn-group" style="margin-top:10px; margin-left:-9px; background-color: light-blue; border-color: transparent; box-shadow: none;">
		<a type="button" class="btn <?php if ($uri == 6) { ?> <?= $color ?> <?php } else { ?> <?= $default_color ?> <?php } ?> waves-effect dropdown-toggle" data-toggle="dropdown">
			<span class="sr-only">Fin & Proc</span>
			<span class="caret"></span>
		</a>
		<ul class="dropdown-menu" style="position:absolute; padding-left:1px; margin-left:-10px; margin-bottom:1px; padding-top:12px; background-color:#ebf3f5">
			<li style="width:100%"><a href="global-configuration.php?fundingtype=<?= $hash_one ?>&menuItem=<?= $hash_six ?>">Funding Type</a></li>
			<li style="width:100%"><a href="global-configuration.php?procurementmethod=<?= $hash_one ?>&menuItem=<?= $hash_six ?>">Procurement Methods</a></li>
			<li style="width:100%"><a href="global-configuration.php?tendercat=<?= $hash_one ?>&menuItem=<?= $hash_six ?>">Tender Categories</a></li>
			<li style="width:100%"><a href="global-configuration.php?tendertype=<?= $hash_one ?>&menuItem=<?= $hash_six ?>">Tender Types</a></li>
			<li style="width:100%"><a href="global-configuration.php?biztype=<?= $hash_one ?>&menuItem=<?= $hash_six ?>">Contractor Business Types</a></li>
			<li style="width:100%"><a href="global-configuration.php?contractornationality=<?= $hash_one ?>&menuItem=<?= $hash_six ?>">Contractor Nationalities</a></li>
			<li style="width:100%"><a href="global-configuration.php?cooptype=<?= $hash_one ?>&menuItem=<?= $hash_six ?>">Coorporate Types</a></li>
		</ul>
	</div>
</div>