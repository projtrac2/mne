<?php 
$Id = 6;
$subId = 47;
    $pageName  = "Projects Inspection Checklists Topics";

    require('includes/head.php');
    require('includes/header.php'); 
    // get the functions selecting data 
    require('functions/inspection.php');  

    try{
        // get al active topics 
        $topics = get_inpectection_checklist_topics(); 
    } catch (PDOException $ex) {
        $result = flashMessage("An error occurred: " .$ex->getMessage()); 
    } 
?> 
<div class="card-header">
    <div class="pull-right">
        <a href="add-checklist-topics.php" class="btn btn-primary"> Add </a>
    </div> 
</div>
<br>
<br>
<div class="body"> 
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
            <thead>
                <tr id="colrow">
                    <td width="5%"><strong id="colhead">SN</strong></td>
                    <td width="28%"><strong id="colhead">Topic</strong></td>
                    <td width="60%"><strong id="colhead">Description</strong></td>
                    <td width="7%"><strong id="colhead">Action</strong></td>
                </tr>
            </thead>
            <tbody>
            <?php
            if(!$topics){
                ?> 
                <tr>
                    <td  colspan="9"><div style="color:red; font-size:14px"><strong>Sorry No Record Found!!</strong></div></td>
                </tr>
                <?php 
            } else{ 
                $sn = 0;
                foreach($topics as $topic) { 
                    $sn = $sn + 1;
                    $r_id = $topic['id'];
                    $er_id = base64_encode($r_id); 
                    $r_topic = $topic['topic'];
                    $r_description = $topic['description']; 
                    ?>

                    <tr id="rowlines">
                        <td>
                            <?php echo $sn; ?>
                        </td>
                        <td>
                            <?php echo $r_topic; ?>
                        </td>
                        <td>
                            <?php echo $r_description; ?> 
                        </td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Options 
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu"> 
                                    <li>
                                        <a type="button" href="add-checklist-topics.php?tp_id=<?= $er_id ?>" id="addFormModalBtn">
                                            <i class="fa fa-pencil-square"></i> </i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a type="button" id="#removeItemModalBtn" onclick="removeItem('<?php echo $r_id ?>')">
                                            <i class="fa fa-trash-o"></i> Delete
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <?php 
                } 
            }
            ?>
            </tbody>
        </table>
    </div> 
</div>

<?php  
    include_once ('includes/footer.php');
?> 
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script >
    function removeItem(tp_id = null){
        const url1 = 'ajax/inspection/index.php';
        if(tp_id){
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this data!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: "post",
                        url: url1,
                        data: {
                            tp_id:tp_id
                        },
                        dataType: "json",
                        success: function (response) {
                            if(response.success){
                                swal(response.msg, {
                                    icon: "success",
                                });
								setTimeout(function(){ location.reload(true) }, 3000);
                            }else{
                                swal(response.msg, {
                                    icon: "error",
                                });
								setTimeout(function(){ location.reload(true) }, 3000);
                            }
                        }
                    });
                } else {
                    swal("You have canceled the action!");
                }
            });

        }else{
            swal("Error id does not exist", {
                icon: "error",
            });
        }
    }
</script>