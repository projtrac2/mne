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
			<li>
				<div class="link">
					<i class="fa fa-folder-open-o" style="color:white"></i>
					Role Group: <?= $role_group ?>
				</div>
			</li>
			<li>
				<div class="link">
					<i class="fa fa-folder-open-o" style="color:white"></i>
					Designation : <?= $designation ?>
				</div>
			</li>
			<?php
			if ($sidebar_details) {
				foreach ($sidebar_details as $sidebar_detail) {
					$parent_id = $sidebar_detail->id;
					$role = explode(',', $sidebar_detail->view_group);
					$icons = $sidebar_detail->icons;
					$children = get_sidebar_children($parent_id);
					if (in_array($role_group, $role)) {
			?>
						<li class="<?php echo ($parent_id == $Id) ? "open" : ""; ?>">
							<div class="link">
								<?= $icons ?> <?= $sidebar_detail->Name ?>
								<i class="fa fa-chevron-down" style="color:white"></i>
							</div>
							<?php
							if ($children) {
							?>
								<ul class="submenu" style="<?php echo $parent_id == $Id ? "display: block;" : ""; ?>">
									<?php
									foreach ($children as $child) {
										$child_role = explode(",", $child->view_group);
										if (in_array($role_group, $child_role)) {
									?>
											<li class="<?php echo $child->id == $subId ? 'active' : ''; ?>">
												<a href="<?= $child->url ?>.php">&nbsp; <?= $child->Name ?></a>
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