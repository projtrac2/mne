<style>
	.sidemenu a:link {
		text-decoration: none;
	}

	.sidemenu .active {
		background-color: #b63b4d;
	}
</style>
<div class="menu">
	<div class="span2 sidemenu">
		<ul id="accordion" class="accordion">
			<?php
			$query_current_strategic_plan =  $db->prepare("SELECT * FROM tbl_strategicplan WHERE current_plan=1");
			$query_current_strategic_plan->execute();
			$row_current_strategic_plan = $query_current_strategic_plan->fetch();
			$count_current_strategic_plan = $query_current_strategic_plan->rowCount();
			if($row_current_strategic_plan){
				$planid = $row_current_strategic_plan['id'];
				$strplanid = base64_encode("strplan1{$planid}");
			}
		
			foreach ($parent_sidebar as $sidebar) {
				$parent_validation = $permissions->validation($sidebar);
				if ($parent_validation) { 
					$parent_id = $sidebar->id;
					$icons = $sidebar->icons;
					$parent_name = $sidebar->Name;
					$children = $permissions->get_child_sidebar($parent_id);
					if ($children) {
						?>
						<li class="<?php echo ($parent_id == $Id) ? "open" : ""; ?>">
							<div class="link">
								<?= $icons ?> <?= $parent_name ?>
								<i class="fa fa-chevron-down" style="color:white"></i>
							</div>
							<ul class="submenu" style="<?php echo $parent_id == $Id ? "display: block;" : ""; ?>">
								<?php
								foreach ($children as $child) {
									$child_validation = $permissions->validation($child);
									if ($child_validation) {
										$child_id = $child->id;
										$child_name = $child->Name;
										$child_url = $child->url;
										if($child_name == "C-APR"){
											$child_url = $child_url."?plan=".$strplanid."&orig=2";
										}
										?>
										<li class="<?php echo $child_id == $subId ? 'active' : ''; ?>">
											<a href="<?= $child_url ?>.php">&nbsp; <?= $child_name ?></a>
										</li>
								<?php
									}
								}
								?>
							</ul>
						<?php
					}
						?>
						</li>
				<?php
				}
			}
				?>
				<li>
					<div class="link">
						<!-- <i class="fa fa-folder-open-o" style="color:white"></i> -->
					</div>
				</li>
		</ul>
	</div>
</div>