<?php
    $pageName ="Indicators"; 
    require('includes/head.php');
    require('includes/header.php');
    // load all function required
    require('functions/indicator.php');
    require('functions/department.php');

    $total_output_indicators =0;    
    $total_outcome_indicators =0; 
    $total_impact_indicators =0; 
    $output_indicators = get_output_indicators(); 
    $outcome_indicators = get_outcome_indicators(); 
    $impact_indicators = get_impact_indicators();

    if($output_indicators){
        $total_output_indicators = count($output_indicators);    
    }

    if($outcome_indicators){
        $total_outcome_indicators = count($outcome_indicators);    
    }

    if($impact_indicators){
        $total_impact_indicators = count($impact_indicators);    
    }

?> 

<div class="header">
    <ul class="nav nav-tabs" style="font-size:14px">
        <li class="active">
            <a data-toggle="tab" href="#output"><i class="fa fa-caret-square-o-down bg-deep-orange" aria-hidden="true"></i> Output &nbsp;<span class="badge bg-orange"><?=$total_output_indicators?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
        </li>
        <li>
            <a data-toggle="tab" href="#outcome"><i class="fa fa-caret-square-o-up bg-blue" aria-hidden="true"></i> Outcome &nbsp;<span class="badge bg-blue"><?=$total_outcome_indicators?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
        </li>
        <li>
            <a data-toggle="tab" href="#impact"><i class="fa fa-caret-square-o-up bg-blue" aria-hidden="true"></i> Impact &nbsp;<span class="badge bg-green"><?=$total_impact_indicators?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
        </li>
    </ul>
</div>
 
<div class="body">

    <div class="tab-content"> 
        <div id="output" class="tab-pane fade in active">
            <div class="card-header">
                <div class="pull-right">
                    <a href="add-output-indicator.php" class="btn btn-warning"> New Indicator </a>
                </div> 
            </div> 
            <br> 
            <br> 
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="spPrograms">
                        <thead>
                            <tr class="bg-orange">
                                <th width="3%"><strong id="colhead">SN</strong></th>
                                <th width="5%"><strong id="colhead">Code</strong></th>
                                <th width="55%"><strong id="colhead">Indicator</strong></th> 
                                <th width="30%"><strong id="colhead"><?=$departmentlabel?></strong></th>
                                <th width="7%"><strong id="colhead">Action</strong></th>
                            </tr>
                            <tbody>
                                <?php
                                    if($total_output_indicators == 0){
                                        ?>
                                        <tr>
                                            <td  colspan="9">
                                                <div style="color:red; font-size:14px">
                                                    <strong>Sorry No Record Found!!</strong>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php }else{
                                        $num=0;
                                        foreach($output_indicators as $output_indicator) { 
                                            $indid = base64_encode($output_indicator['indid']);
                                            $ind = $output_indicator['indid'];
                                            $inddept = $output_indicator['indicator_dept'];
                                            $num = $num + 1;

                                            $result_department = get_department($inddept); 
                                            $department = "N/A"; 
                                            if($result_department){
                                                $department = $result_department['sector'];
                                            } 
                                            ?>
                                            <tr id="rowlines">
                                                <td><?php echo $num; ?></td>
                                                <td><?php echo $output_indicator['indicator_code']; ?></td>
                                                <td><?php echo $output_indicator['indicator_name']; ?></td> 
                                                <td><?php echo $department; ?></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Options <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a type="button" data-toggle="modal" data-target="#moreModal" id="moreModalBtn" onclick="get_more('<?php echo $ind ?>')">
                                                                    <i class="fa fa-file-text"></i> More Info
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a type="button" href="edit-output-indicator.php?ind=<?= $indid ?>" id="addFormModalBtn">
                                                                    <i class="fa fa-pencil-square"></i> </i> Edit
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a type="button" data-toggle="modal" data-target="#removeItemModal" id="#removeItemModalBtn" onclick="removeItem('<?php echo $ind ?>')">
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
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div id="outcome" class="tab-pane fade">
            <div class="card-header">
                <div class="pull-right">
                    <a href="add-outcome-indicator.php" class="btn btn-primary"> New Indicator</a>
                </div> 
            </div>
            <br> 
            <br> 
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="indepedentPrograms" style="width:100%">
                        <thead style="width:100%">
                            <tr class="bg-blue" style="width:100%">
                                <th width="3%"><strong id="colhead">SN</strong></th>
                                <th width="5%"><strong id="colhead">Code</strong></th>
                                <th width="55%"><strong id="colhead">Indicator</strong></th> 
                                <th width="30%"><strong id="colhead"><?=$departmentlabel?></strong></th>
                                <th width="7%"><strong id="colhead">Action</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if($total_outcome_indicators == 0){
                                    ?>
                                     
                                <?php }else{
                                    $num=0;
                                    foreach($outcome_indicators as $outcome_indicator) { 
                                        $indid = base64_encode($outcome_indicator['indid']);
                                        $ind = $outcome_indicator['indid'];
                                        $inddept = $outcome_indicator['indicator_dept'];
                                        $num = $num + 1;

                                        $result_department = get_department($inddept); 
                                        $department = "N/A"; 
                                        if($result_department){
                                            $department = $result_department['sector'];
                                        } 
                                        ?>
                                        <tr id="rowlines">
                                            <td width="3%"><?php echo $num; ?></td>
                                            <td width="5%"><?php echo $outcome_indicator['indicator_code']; ?></td>
                                            <td width="55%"><?php echo $outcome_indicator['indicator_name']; ?></td> 
                                            <td width="30%"><?php echo $department; ?></td>
                                            <td width="7%">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Options <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a type="button" data-toggle="modal" data-target="#moreModal" id="moreModalBtn" onclick="get_more(<?php echo $ind ?>)">
                                                                <i class="fa fa-file-text"></i> More Info
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a type="button" href="edit-outcome-indicator.php?ind=<?= $indid ?>" id="addFormModalBtn">
                                                                <i class="fa fa-pencil-square"></i> </i> Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a type="button" data-toggle="modal" data-target="#removeItemModal" id="#removeItemModalBtn" onclick="removeItem(<?php echo $ind ?>)">
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
        </div>
        <div id="impact" class="tab-pane fade">
            <div class="card-header">
                <div class="pull-right">
                    <a href="add-impact-indicator.php" class="btn btn-success"> New Indicator</a>
                </div> 
            </div>
            <br> 
            <br> 
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="indepedentPrograms" style="width:100%">
                        <thead style="width:100%">
                            <tr class="bg-green" style="width:100%">
                                <th width="3%"><strong id="colhead">SN</strong></th>
                                <th width="5%"><strong id="colhead">Code</strong></th>
                                <th width="55%"><strong id="colhead">Indicator</strong></th> 
                                <th width="30%"><strong id="colhead"><?=$departmentlabel?></strong></th>
                                <th width="7%"><strong id="colhead">Action</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if($total_impact_indicators == 0){
                                    ?>
                                    
                                <?php }else{
                                    $num=0;
                                    foreach($impact_indicators as $impact_indicator) { 
                                        $indid = base64_encode($impact_indicator['indid']);
                                        $ind = $impact_indicator['indid']; 
                                        $inddept = $impact_indicator['indicator_dept'];
                                        $num = $num + 1;

                                        $result_department = get_department($inddept); 
                                        $department = "N/A"; 
                                        if($result_department){
                                            $department = $result_department['sector'];
                                        } 
                                        ?>
                                        <tr id="rowlines">
                                            <td width="3%"><?php echo $num; ?></td>
                                            <td width="5%"><?php echo $impact_indicator['indicator_code']; ?></td>
                                            <td width="55%"><?php echo $impact_indicator['indicator_name']; ?></td> 
                                            <td width="30%"><?php echo $department; ?></td>
                                            <td width="7%">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Options <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a type="button" data-toggle="modal" data-target="#moreModal" id="moreModalBtn" onclick="get_more(<?php echo $ind ?>)">
                                                                <i class="fa fa-file-text"></i> More Info
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a type="button" href="edit-impact-indicator.php?ind=<?= $indid ?>" id="addFormModalBtn">
                                                                <i class="fa fa-pencil-square"></i> </i> Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a type="button" data-toggle="modal" data-target="#removeItemModal" id="#removeItemModalBtn" onclick="removeItem(<?php echo $ind ?>)">
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
        </div>
    </div>

</div>

<?php
    require('indicators/partials/view-modal.php');
    require('includes/footer.php');
?>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    const url1 = 'ajax/indicators/index';
    function get_more(indid = null){
        if(indid){
            $.ajax({
                type: "get",
                url: url1,
                data: {
                    more:"more",
                    indid:indid
                },
                dataType: "json",
                success: function (response) { 
                    if(response.success){
                        var indicator = response.msg; 
                        $("#moreinfo").html(indicator);
                    }else{
                        swal(response.msg, {
                            icon: "error",
                        }); 
                    }
                }
            });
        }else{
            swal("Error id does not exist", {
                icon: "error",
            });
        }
    }


    function removeItem(indid = null){
        if(indid){
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
                            delete:"delete",
                            indid:indid
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