<div class="body">
	<table class="table tabe-hover table-bordered" id="list">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th data-orderable="false">Title</th>
				<th data-orderable="false">Content</th>
				<th>Status</th>
				<th data-orderable="false">Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i = 1;
			while ($row = $query_templates->fetch()) :
			?>
				<tr>
					<th class="text-center"><?php echo $i++ ?></th>
					<td><b><?php echo $row['title'] ?></b></td>
					<td><b><?php echo $row['content'] ?></b></td>
					<td><b> <span class="badge badge-<?php echo $row['active'] == 1 ? "success" : "warning" ; ?>"></span> <?php echo $row['active'] == 1 ? "Active" : "Disabled" ; ?></b></td>
					<td class="text-center">						
						<div class="btn-group">
							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Action <span class="caret"></span>
							</button>
							<ul class="dropdown-menu">
								<li><a type="button" class="dropdown-item" href="email_template.php?tempid=<?php echo $row['id'] ?>"> <i class="glyphicon glyphicon-file"></i>View</a></li>       
								<li><a type="button" class="dropdown-item" href="email_template.php?tempid=<?php echo $row['id'] ?>"><i class="glyphicon glyphicon-edit"></i> Edit</a></li>
								<li><a type="button" class="dropdown-item change_status" onchange="change_status()" data-id="<?php echo $row['id'] ?>"><i class="glyphicon glyphicon-trash"></i> <?php echo $row['active'] == 1 ? "Disable" : "Activate" ; ?></a></li>       
							</ul>
						</div>
					</td>
				</tr>
			<?php endwhile; ?>
		</tbody>
	</table>
</div>