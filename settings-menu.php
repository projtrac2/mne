<?php
$uri = isset($_GET['menuItem']) ? $_GET['menuItem'] : 1;
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
?>
<div class="button-demo" style="margin-top:-15px">
	<div class="btn-group" style="margin-top:10px; margin-left:5px; background-color: transparent; border-color: transparent; box-shadow: none;">
		<a type="button" class="btn <?php if($uri == 1) { ?> <?= $color ?> <?php } else { ?> <?= $default_color ?> <?php } ?> waves-effect dropdown-toggle" data-toggle="dropdown">
			<span class="sr-only">Settings</span>
			<span class="caret"></span>
		</a>
		<ul class="dropdown-menu" style="position:absolute; padding-left:1px; margin-left:-10px; margin-bottom:1px; padding-top:12px; background-color:#ebf3f5">
			<li style="width:100%"><a href="organization-details.php?menuItem=1">Organization Details</a></li>
			<li style="width:100%"><a href="system-terminologies.php?menuItem=1&menuItem=1">Global Terminologies</a></li>
			<li style="width:100%"><a href="global-configuration.php?workflow=1&menuItem=1">System Workflow Stages</a></li>
			<li style="width:100%"><a href="email_configuration.php?menuItem=1">Email Configuration</a></li>
			<li style="width:100%"><a href="email_templates.php?menuItem=1">Email Templates</a></li>
			<li style="width:100%"><a href="global-configuration.php?titles=1&menuItem=1">Titles</a></li>
		</ul>
	</div>
	<a href="global-configuration.php?timelines=1&menuItem=2" class="btn <?php if($uri == 2) { ?> <?= $color ?> <?php } else { ?> <?= $default_color ?> <?php } ?> waves-effect" style="margin-top:10px; margin-left:-9px">Timelines</a>
	<div class="btn-group" style="margin-top:10px; margin-left:-9px; background-color: light-blue; border-color: transparent; box-shadow: none;">
		<a type="button" class="btn <?php if($uri == 3) { ?> <?= $color ?> <?php } else { ?> <?= $default_color ?> <?php } ?> waves-effect dropdown-toggle" data-toggle="dropdown">
			<span class="sr-only">Status</span>
			<span class="caret"></span>
		</a>
		<ul class="dropdown-menu" style="position:absolute; padding-left:1px; margin-left:-10px; margin-bottom:1px; padding-top:12px; background-color:#ebf3f5">
			<li style="width:100%"><a href="global-configuration.php?paymentstatus=1&menuItem=3">Payment Status</a></li>
			<li style="width:100%"><a href="global-configuration.php?projectstatus=1&menuItem=3">Project Status</a></li>
		</ul>
	</div>
	<div class="btn-group" style="margin-top:10px; margin-left:-9px; background-color: light-blue; border-color: transparent; box-shadow: none;">
		<a type="button" class="btn <?php if($uri == 4) { ?> <?= $color ?> <?php } else { ?> <?= $default_color ?> <?php } ?> waves-effect dropdown-toggle" data-toggle="dropdown">
			<span class="sr-only">Projects</span>
			<span class="caret"></span>
		</a>
		<ul class="dropdown-menu" style="position:absolute; padding-left:1px; margin-left:-10px; margin-bottom:1px; padding-top:12px; background-color:#ebf3f5">
			<li style="width:100%"><a href="global-configuration.php?indcat=1&menuItem=4">Indicator Categories</a></li>
			<li style="width:100%"><a href="global-configuration.php?priorities=1&menuItem=4">Priorities</a></li>
			<li style="width:100%"><a href="global-configuration.php?maptype=1&menuItem=4">Map Types</a></li>
			<li style="width:100%"><a href="global-configuration.php?implmethod=1&menuItem=4">Implementation Method</a></li>
			<li style="width:100%"><a href="global-configuration.php?fiscalyear=1&menuItem=4">Financial Years</a></li>
			<li style="width:100%"><a href="global-configuration.php?dtfreq=1&menuItem=4">Data Collection Frequencies</a></li>
			<li style="width:100%"><a href="global-configuration.php?roles=1&menuItem=4">Project Roles</a></li>
			<li style="width:100%"><a href="global-configuration.php?partnerRoles=1&menuItem=4">Partner Roles</a></li>
		</ul>
	</div>
	<div class="btn-group" style="margin-top:10px; margin-left:-9px; background-color: light-blue; border-color: transparent; box-shadow: none;">
		<a type="button" class="btn <?php if($uri == 5) { ?> <?= $color ?> <?php } else { ?> <?= $default_color ?> <?php } ?> waves-effect dropdown-toggle" data-toggle="dropdown">
			<span class="sr-only">Permissions</span>
			<span class="caret"></span>
		</a>
		<ul class="dropdown-menu" style="position:absolute; padding-left:1px; margin-left:-10px; margin-bottom:1px; padding-top:12px; background-color:#ebf3f5">
			<li style="width:100%"><a href="global-configuration.php?designations=1&menuItem=5">Designations</a></li>
			<li style="width:100%"><a href="global-configuration.php?permission=1&menuItem=5">Permissions</a></li>
			<li style="width:100%"><a href="global-configuration.php?mainmenu=1&menuItem=5">Main Menu</a></li>
		</ul>
	</div>
	<div class="btn-group" style="margin-top:10px; margin-left:-9px; background-color: light-blue; border-color: transparent; box-shadow: none;">
		<a type="button" class="btn <?php if($uri == 6) { ?> <?= $color ?> <?php } else { ?> <?= $default_color ?> <?php } ?> waves-effect dropdown-toggle" data-toggle="dropdown">
			<span class="sr-only">Fin & Proc</span>
			<span class="caret"></span>
		</a>
		<ul class="dropdown-menu" style="position:absolute; padding-left:1px; margin-left:-10px; margin-bottom:1px; padding-top:12px; background-color:#ebf3f5">
			<li style="width:100%"><a href="global-configuration.php?financialplan=1&menuItem=6">Financial Plan</a></li>
			<li style="width:100%"><a href="global-configuration.php?fundingtype=1&menuItem=6">Funding Type</a></li>
			<li style="width:100%"><a href="global-configuration.php?procurementmethod=1&menuItem=6">Procurement Methods</a></li>
			<li style="width:100%"><a href="global-configuration.php?tendercat=1&menuItem=6">Tender Categories</a></li>
			<li style="width:100%"><a href="global-configuration.php?tendertype=1&menuItem=6">Tender Types</a></li>
			<li style="width:100%"><a href="global-configuration.php?biztype=1&menuItem=6">Contractor Business Types</a></li>
			<li style="width:100%"><a href="global-configuration.php?contractornationality=1&menuItem=6">Contractor Nationalities</a></li>
			<li style="width:100%"><a href="global-configuration.php?cooptype=1&menuItem=6">Coorporate Types</a></li>
		</ul>
	</div>
</div>