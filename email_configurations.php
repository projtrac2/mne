<?php 
try {
   //code...

include 'db_connect.php' 

?>
<div class="col-lg-12">
   <div class="card card-outline card-success">
      <div class="card-header">
         <div class="card-tools">
            <a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index.php?page=new_email_configuration"><i class="fa fa-plus"></i> Add New Email Configuration</a>
         </div>
      </div>
      <div class="card-body">
         <table class="table tabe-hover table-bordered" id="list">
            <thead>
               <tr>
                  <th class="text-center">#</th>
                  <th>SMTP Debug</th>
                  <th data-orderable="false">Host #</th>
                  <th data-orderable="false">SMTP Auth #</th>
                  <th data-orderable="false">Username #</th>
                  <th data-orderable="false">Password #</th>
                  <th data-orderable="false">SMTP Secure #</th>
                  <th data-orderable="false">Port #</th>
                  <th>Status #</th>
                  <th data-orderable="false">Action</th>
               </tr>
            </thead>
            <tbody>
               <?php
               $i = 1;
               $qry = $conn->query("SELECT * FROM email_configuration order by id asc");
               while ($row = $qry->fetch_assoc()) :
               ?>
                  <tr>
                     <th class="text-center"><?php echo $i++ ?></th>
                     <td><b><?php echo ucwords($row['type']) ?></b></td>
                     <td><b><?php echo $row['title'] ?></b></td>
                     <td><b><?php echo $row['content'] ?></b></td>
                     <td><b> <span class="badge badge-<?php echo $row['status'] == 1 ? "success" : "warning"; ?>"></span> <?php echo $row['status'] == 1 ? "Active" : "Disabled"; ?></b></td>
                     <td class="text-center">
                        <button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                           Action
                        </button>
                        <div class="dropdown-menu">
                           <button class="dropdown-item view_email_configuration" button="button" data-id="<?php echo $row['id'] ?>">View</button>
                           <div class="dropdown-divider"></div>
                           <a class="dropdown-item" href="./index.php?page=edit_email_configuration&id=<?php echo $row['id'] ?>">Edit</a>
                           <div class="dropdown-divider"></div>
                           <a class="dropdown-item delete_email_configuration" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Delete</a>
                        </div>
                     </td>
                  </tr>
               <?php endwhile; ?>
            </tbody>
         </table>
      </div>
   </div>
</div>
<?php

} catch (\PDOException $ex) {
	customErrorHandler($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
}

?>
<script>
   $(document).ready(function() {
      $('#list').dataTable()
      $('.view_user').click(function() {
         uni_modal("<i class='fa fa-id-card'></i> User Details", "view_user.php?id=" + $(this).attr('data-id'))
      })
      $('.delete_user').click(function() {
         _conf("Are you sure to delete this user?", "delete_user", [$(this).attr('data-id')])
      })
   });

   function delete_user($id) {
      start_load()
      $.ajax({
         url: 'ajax.php?action=delete_user',
         method: 'POST',
         data: {
            id: $id
         },
         success: function(resp) {
            if (resp == 1) {
               alert_toast("Data successfully deleted", 'success')
               setTimeout(function() {
                  location.reload()
               }, 1500)

            }
         }
      })
   }
</script>