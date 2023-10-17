<style>
.sidemenu a:link {
  text-decoration: none;
}
</style>
<div class="menu">
	<div class="span2 sidemenu">
		<ul id="accordion" class="accordion">
			<?php if (!isset($_SESSION['MM_UserGroup']) || $_SESSION['MM_UserGroup'] != 'Administrator' || $_SESSION['MM_UserGroup'] != 'SuperAdmin'){ ?>  
				<li>
					<div class="link"><i class="fa fa-dashboard" style="color:white"></i>Dashboards<i class="fa fa-chevron-down" style="color:white"></i></div>
					<ul class="submenu">
						<li><a href="/dashboard">•&nbsp; General Dashboard</a></li>
						<li><a href="/financial-dashboard">•&nbsp; Financial Dashboard</a></li>
						<!--<li><a href="strategic-plan-output-dashboard">•&nbsp; Strategic Plan Dashboard</a></li>-->
						<li><a href="/output-map-dashboard">•&nbsp; Outputs GIS Dashboard</a></li>
						<li><a href="/project-map-dashboard">•&nbsp; Projects GIS Dashboard</a></li>
						<!--<li><a href="area-map">•&nbsp; Area GIS</a></li>
						<li><a href="waypoints-map">•&nbsp; Way-Points GIS</a></li>
						<!--<li><a href="projects-distribution">•&nbsp; Projects Distribution Map</a></li>-->
					</ul>
				</li>
			<?php } ?>
			<?php if (!isset($_SESSION['MM_UserGroup']) || $_SESSION['MM_UserGroup'] != 'Administrator' || $_SESSION['MM_UserGroup'] != 'SuperAdmin'){ ?>  
				<li>
					<div class="link"><i class="fa fa-columns" style="color:white"></i>Strategic Plan<i class="fa fa-chevron-down" style="color:white"></i></div>
					<ul class="submenu">
						<li><a href="/strategicplan/view-strategic-plans">•&nbsp; Strategic Plans</a></li>
						<li><a href="/view-program">•&nbsp; Strategic Plan Programs</a></li>
						<li><a href="/strategic-plan-projects">•&nbsp; Strategic Plan Projects</a></li>
						<li><a href="/view-adps">•&nbsp; Annual Plans (ADPs)</a></li>
					</ul>
				</li>
			<?php } ?>
			<?php if (!isset($_SESSION['MM_UserGroup']) || $_SESSION['MM_UserGroup'] != 'Administrator' || $_SESSION['MM_UserGroup'] != 'SuperAdmin'){ ?>  
				<li>
					<div class="link"><i class="fa fa-object-group" style="color:white"></i>Programs<i class="fa fa-chevron-down" style="color:white"></i></div>
					<ul class="submenu">
						<li><a href="/programs/view-programs.php">•&nbsp; View All Programs</a></li>
						<li><a href="/programs/add-program.php">•&nbsp; Add Indepedent Program</a></li>
					</ul>
				</li>
			<?php } ?>
			<?php if (!isset($_SESSION['MM_UserGroup']) || $_SESSION['MM_UserGroup'] != 'Administrator' || $_SESSION['MM_UserGroup'] != 'SuperAdmin'){ ?>  
				<li>
					<div class="link"><i class="fa fa-tasks" style="color:white"></i>Projects<i class="fa fa-chevron-down" style="color:white"></i></div>
					<ul class="submenu">
						<li><a href="/projects">•&nbsp; All Projects</a></li>
						<li><a href="/myprojects">•&nbsp; My Projects</a></li>
						<li><a href="/add-project-team">•&nbsp; Add Project Team</a></li>
						<li><a href="/project-mapping">•&nbsp; Projects Mapping</a></li>
						<li><a href="/add-project-activities?action=add">•&nbsp; Add Project Activities</a></li>
						<!--<li><a href="add-project-activities?action=view">•&nbsp; View Project Activities</a></li>-->
						<!-- <li><a href="add-project-checklist?action=add">•&nbsp; Add Project Checklists</a></li> -->
						<li><a href="/view-workplan">•&nbsp; Add Project Workplan</a></li>
					</ul>
				</li>
			<?php } ?>
			<?php if (!isset($_SESSION['MM_UserGroup']) || $_SESSION['MM_UserGroup'] != 'Administrator' || $_SESSION['MM_UserGroup'] != 'SuperAdmin'){ ?>  
				<li>
					<div class="link"><i class="fa fa-compass" style="color:white"></i>Monitoring<i class="fa fa-chevron-down" style="color:white"></i></div>
					<ul class="submenu">
						<li><a href="/projects-monitoring">•&nbsp; Monitoring</a></li>
						<li><a href="/view-mne-plan">•&nbsp; Add Project M&E Plan</a></li>
					</ul>
				</li>
			<?php } ?>
			<?php if (!isset($_SESSION['MM_UserGroup']) || $_SESSION['MM_UserGroup'] != 'Administrator' || $_SESSION['MM_UserGroup'] != 'SuperAdmin'){ ?>  
				<li>
					<div class="link"><i class="fa fa-list-ol" style="color:white"></i>Quality Control<i class="fa fa-chevron-down" style="color:white"></i></div>
					<ul class="submenu">
						<li><a href="/qualitycontrol/view-projects-inpection-list.php">•&nbsp; Inspection</a></li>
						<li><a href="/qualitycontrol/assign-inspection-team.php">•&nbsp; Assign Officer</a></li>
						<li><a href="/qualitycontrol/view-checklist.php">•&nbsp; Quality Checklists</a></li>
						<li><a href="/qualitycontrol/view-checklist-topics.php">•&nbsp; Manage Checklist Topics</a></li>
					</ul>
				</li>
			<?php } ?>
			<?php if (!isset($_SESSION['MM_UserGroup']) || $_SESSION['MM_UserGroup'] != 'Administrator' || $_SESSION['MM_UserGroup'] != 'SuperAdmin'){ ?>  
				<li>
					<div class="link"><i class="fa fa-newspaper-o" style="color:white"></i>Evaluation<i class="fa fa-chevron-down" style="color:white"></i></div>
					<ul class="submenu">
						<li><a href="/project-survey">•&nbsp; Projects Evaluation</a></li>
						<!--<li><a href="projects-evaluation">•&nbsp; Rapid Evaluation</a></li>
						<li><a href="projects-evaluation">•&nbsp; Projects Survey</a></li>-->
						<li><a href="/project-concluded-evaluations">•&nbsp; Concluded Evaluations</a></li>
						<!--<li><a href="projects-impact">•&nbsp; Impact Assessment</a></li>-->
					</ul>
				</li>
			<?php } ?>
			<?php if (!isset($_SESSION['MM_UserGroup']) || $_SESSION['MM_UserGroup'] != 'Administrator' || $_SESSION['MM_UserGroup'] != 'SuperAdmin'){ ?>  
				<li>
					<div class="link"><i class="fa fa-microchip" style="color:white"></i>Indicators<i class="fa fa-chevron-down" style="color:white"></i></div>
					<ul class="submenu">
						<li><a href="/indicators/index.php">•&nbsp;Indicators</a></li> 
						<li><a href="/view-normal-indicators">•&nbsp; Baseline Indicators</a></li>
					</ul>
				</li>
			<?php } ?>
			<?php if (!isset($_SESSION['MM_UserGroup']) || $_SESSION['MM_UserGroup'] != 'Administrator' || $_SESSION['MM_UserGroup'] != 'SuperAdmin'){ ?>  
				<!-- <li>
					<div class="link"><i class="fa fa-check" style="color:white"></i>Indicators<i class="fa fa-chevron-down" style="color:white"></i></div>
					<ul class="submenu">
						<li><a href="allindicators">•&nbsp; View Indicators</a></li>
						<!--<li><a href="indicatorgroups">•&nbsp; Add/View Indicators Groups</a></li>--
						<li><a href="addindicators">•&nbsp; Add Indicators</a></li>
					</ul>
				</li>-->
			<?php } ?>
			<?php if (!isset($_SESSION['MM_UserGroup']) || $_SESSION['MM_UserGroup'] != 'Administrator' || $_SESSION['MM_UserGroup'] != 'SuperAdmin'){ ?>  
				<li>
					<div class="link"><i class="fa fa-exclamation-circle" style="color:white"></i>Issues/Risk Register<i class="fa fa-chevron-down" style="color:white"></i></div>
					<ul class="submenu">
						<li><a href="/projects-escalated-issues">•&nbsp; Escalated Issues</a></li>
						<li><a href="/risk-categories">•&nbsp; Risk Categories</a></li>
						<li><a href="/project-risk-mitigation">•&nbsp; Risk Mitigations</a></li>
						<!--<li><a href="riskmatrix">•&nbsp; Risk Matrix</a></li>-->
					</ul>
				</li>
			<?php } ?>
			<?php if (!isset($_SESSION['MM_UserGroup']) || $_SESSION['MM_UserGroup'] != 'Administrator' || $_SESSION['MM_UserGroup'] != 'SuperAdmin'){ ?>  
				<li>
					<div class="link"><i class="fa fa-slideshare" style="color:white"></i>Partners<i class="fa fa-chevron-down" style="color:white"></i></div>
					<ul class="submenu">
						<li><a href="/financiers" style="text-decoration: none;">•&nbsp; Funding Partners</a></li>
						<li><a href="/development-partners">•&nbsp; Development Partners</a></li>
					</ul>
				</li>
			<?php } ?>
			<?php if (!isset($_SESSION['MM_UserGroup']) || $_SESSION['MM_UserGroup'] != 'Administrator' || $_SESSION['MM_UserGroup'] != 'SuperAdmin'){ ?>  
				<li>
					<div class="link"><i class="fa fa-money" style="color:white"></i>Finance<i class="fa fa-chevron-down" style="color:white"></i></div>
					<ul class="submenu">
						<li><a href="/add-project-financial-plan">•&nbsp; Project Financial Plan</a></li>
						<li><a href="/funding">•&nbsp; Funding</a></li>
						<li><a href="/projectfinancials-contractor">•&nbsp; Payments Request</a></li>
						<li><a href="/financialrequests">•&nbsp; Payments Approval</a></li>
						<li><a href="/projectpayments">•&nbsp; Payment Disbursement</a></li>
					</ul>
				</li>
			<?php } ?>
			<?php if (!isset($_SESSION['MM_UserGroup']) || $_SESSION['MM_UserGroup'] != 'Administrator' || $_SESSION['MM_UserGroup'] != 'SuperAdmin'){ ?>  
				<li>
					<div class="link"><i class="fa fa-shopping-bag" style="color:white"></i>Procurement<i class="fa fa-chevron-down" style="color:white"></i></div>
					<ul class="submenu">
						<li><a href="/add-project-procurement-details">•&nbsp; Procurement</a></li>
						<li><a href="/contractorslist">•&nbsp; Manage Contractors</a></li>
					</ul>
				</li>
			<?php } ?>
			<?php if (!isset($_SESSION['MM_UserGroup']) || $_SESSION['MM_UserGroup'] != 'Administrator' || $_SESSION['MM_UserGroup'] != 'SuperAdmin'){ ?>  
				<li>
					<div class="link"><i class="fa fa-users" style="color:white"></i>Personnel<i class="fa fa-chevron-down" style="color:white"></i></div>
					<ul class="submenu">
						<li><a href="/projteam">•&nbsp; View Project Team</a></li>
						<li><a href="/addeditmember">•&nbsp; Add Project Team</a></li>
						<li><a href="/administrators">•&nbsp; View Administrators</a></li>
						<li><a href="/addeditadministrators">•&nbsp; Add Administrators</a></li>
					</ul>
				</li>
			<?php } ?>
			<?php if (!isset($_SESSION['MM_UserGroup']) || $_SESSION['MM_UserGroup'] != 'Administrator' || $_SESSION['MM_UserGroup'] != 'SuperAdmin'){ ?>  
				<!-- <li>
					<div class="link"><i class="fa fa-wrench" style="color:white"></i>Assets<i class="fa fa-chevron-down" style="color:white"></i></div>
					<ul class="submenu">
						<li><a href="#">•&nbsp; Manage Project Assets</a></li>
						<li><a href="#">•&nbsp; Add Project Asset</a></li>
					</ul>
				</li>-->
			<?php } ?>
			<?php if (!isset($_SESSION['MM_UserGroup']) || $_SESSION['MM_UserGroup'] != 'Administrator' || $_SESSION['MM_UserGroup'] != 'SuperAdmin'){ ?>  
				<li>
					<div class="link"><i class="fa fa-folder-open-o" style="color:white"></i>Files<i class="fa fa-chevron-down" style="color:white"></i></div>
					<ul class="submenu">
						<li><a href="/projectfiles">•&nbsp; View Project Files</a></li>
					</ul>
				</li>
			<?php } ?>
			<?php if (!isset($_SESSION['MM_UserGroup']) || $_SESSION['MM_UserGroup'] != 'Administrator' || $_SESSION['MM_UserGroup'] != 'SuperAdmin'){ ?>  
				<li>
					<div class="link"><i class="fa fa-file-text-o" aria-hidden="true" style="color:white"></i>Reports<i class="fa fa-chevron-down" style="color:white"></i></div>
					<ul class="submenu">
						<li><a href="/project-indicators-tracking-table">•&nbsp; Projects Performance </a></li>
						<li><a href="/view-workplan-reports">•&nbsp; Outputs Progress</a></li>
						<!--<li><a href="output-progress-report">•&nbsp; Indicators Performance</a></li>-->
						<li><a href="/output-indicators-tracking">•&nbsp; Indicators Performance</a></li>
						<li><a href="/projfundingreport">•&nbsp; Financial</a></li>
						<li><a href="/projfinanciersreport">•&nbsp; Funders</a></li>
						<!--<li><a href="projpendingbillsreport">•&nbsp; Pending Bills</a></li>-->
					</ul>
				</li>
			<?php } ?> 
			<?php //if (isset($_SESSION['MM_UserGroup']) && $_SESSION['MM_UserGroup']=='SuperAdmin'){ ?>  
				<li>
					<div class="link"><i class="fa fa-cog fa-spin" style="font-size:16px; color:red;"></i>Settings<i class="fa fa-chevron-down" style="color:white"></i></div>
					<ul class="submenu fa-ul">
						<li><a href="/global-configuration"><i class="fa fa-cogs"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Global Configuration</a></li>
						<li><a href="/sectors"><i class="fa fa-puzzle-piece" aria-hidden="true" style="color:white"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Add/View Sectors</a></li>
						<li><a href="/locations"><i class="fa fa-map-marker" aria-hidden="true" style="color:white"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Add/View Locations</a></li>
					</ul>
				</li>
			<?php// } ?>
		</ul>
		<script>
		$(function() {
			var Accordion = function(el, multiple) {
				this.el = el || {};
				this.multiple = multiple || false;

				// Variables privadas
				var links = this.el.find('.link');
				// Evento
				links.on('click', {el: this.el, multiple: this.multiple}, this.dropdown)
			}

			Accordion.prototype.dropdown = function(e) {
				var $el = e.data.el;
					$this = $(this),
					$next = $this.next();

				$next.slideToggle();
				$this.parent().toggleClass('open');

				if (!e.data.multiple) {
					$el.find('.submenu').not($next).slideUp().parent().removeClass('open');
				};
			}	

			var accordion = new Accordion($('#accordion'), false);
		});
		</script>
    </div>
</div>