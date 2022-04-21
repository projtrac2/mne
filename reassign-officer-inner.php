<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h5 style="color:#FF5722; font-size:15px">
                    <strong>Assign Officer </strong>
                    <a href="task-inspection.php" class="btn btn-primary pull-right" id="nextT" type="button">Go Back</a>
                </h5>
            </div>
            <div class="card-body" style="margin-top:5px">
                <form id="assignofficerform" method="POST" name="assignofficerform" action="" enctype="multipart/form-data" autocomplete="off">
                <!-- <div class="row clearfix">  -->
                        <div class="col-md-6">
                            <label class="control-label">Officers *:</label>
                            <div class="form-line">
                                <select name="officer" id="officer" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                                        <?php
                                            if($count_rsTeam > 0){
                                                echo '<option value="">.... Select Officer ....</option>';
                                                do{
                                                    $selected = ($officer == $row_rsTeam['ptid']) ? "selected" : "";
                                                    echo '<option value="'.$row_rsTeam['ptid'].'" '.$selected.'>'.$row_rsTeam['fullname'].'</option>';
                                                }while($row_rsTeam = $query_rsTeam->fetch());
                                            }else{
                                                echo '<option value="">.... Contact Admin ....</option>';
                                            }
                                        ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label">Inspection Date *:</label>
                            <div class="form-line">
                                <input type="date" name="inspection_date" value="<?php echo $inspection_date?>" onchange="validateDate()" class="form-control" id="inspection_date" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="control-label">Projects *:</label>
                            <div class="form-line">
                                <select name="projid" id="projid" onchange="getOutput()" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                                    <?php
                                        if($count_rsProjects > 0){
                                            echo '<option value="">.... Select Project ....</option>';
                                            do{
                                                $selected = ($projid == $row_rsProjects['projid']) ? "selected" : "";
                                                echo '<option value="'.$row_rsProjects['projid'].'" '.$selected.'>'.$row_rsProjects['projname'].'</option>';
                                            }while($row_rsProjects = $query_rsProjects->fetch());
                                        }else{
                                            echo '<option value="">.... Contact Admin ....</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="control-label">Outputs *:</label>
                            <div class="form-line">
                                <select name="outputid" id="outputid" onchange="getLevel3()" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                                    <option value="">.... Select Output ....</option>
                                    <?php
                                        $query_rsOutputs = $db->prepare("SELECT g.output, d.id FROM `tbl_projects` p INNER JOIN tbl_project_details d ON d.projid = p.projid INNER JOIN tbl_progdetails g ON g.id = d.outputid WHERE p.projid = $projid");
                                        $query_rsOutputs->execute();
                                        $row_rsOutputs = $query_rsOutputs->fetch();
                                        $count_rsOutputs = $query_rsOutputs->rowCount();
                                        if($count_rsOutputs > 0){
                                            $outputs = '<option value="">.... Select Output ....</option>';
                                            do{
                                                $selected = ($outputid == $row_rsOutputs['id']) ? "selected" : "";
                                                echo '<option value="'.$row_rsOutputs['id'].'" '.$selected.'>'.$row_rsOutputs['output'].'</option>';
                                            }while($row_rsOutputs = $query_rsOutputs->fetch());
                                        }else{
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label">Level 3 *:</label>
                            <div class="form-line">
                                <select name="level3" id="level3" onchange="getLevel4()" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                                    <option value="">.... Select Level 3 ....</option>
                                    <?php
                                        $query_rsLevel3 = $db->prepare("SELECT s.id, s.state FROM `tbl_output_disaggregation` o INNER JOIN tbl_state s ON o.outputstate = s.id WHERE o.projid = $projid AND o.outputid = $outputid");
                                        $query_rsLevel3->execute();
                                        $row_rsLevel3 = $query_rsLevel3->fetch();
                                        $count_rsLevel3 = $query_rsLevel3->rowCount();
                                
                                        if($count_rsLevel3 > 0){
                                            echo '<option value="">.... Select Level 3 ....</option>';
                                            do{
                                                $selected = ($level3 == $row_rsLevel3['id']) ? "selected" : "";
                                                echo '<option value="'.$row_rsLevel3['id'].'" '.$selected.'>'.$row_rsLevel3['state'] .'</option>';
                                            }while($row_rsLevel3 = $query_rsLevel3->fetch());
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label">Level 4 *:</label>
                            <div class="form-line">
                                <select name="level4" id="level4" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="false" required="required">
                                    <?php
                                        if($level4 != 0){
                                            $query_rsLevel4 = $db->prepare("SELECT d.id,d.disaggregations FROM `tbl_project_results_level_disaggregation` p INNER JOIN tbl_indicator_level3_disaggregations d ON d.id = p.name WHERE p.projid = $projid AND p.projoutputid = $outputid AND p.opstate = $level3");
                                            $query_rsLevel4->execute();
                                            $row_rsLevel4 = $query_rsLevel4->fetch();
                                            $count_rsLevel4 = $query_rsLevel4->rowCount();
                                            if($count_rsLevel4 > 0){
                                                $outputs = '<option value="">.... Select Level3 ....</option>';
                                                do{
                                                    $selected = ($level4 == $row_rsLevel4['id']) ? "selected" : "";
                                                    echo '<option value="'.$row_rsLevel4['id'].'"  '.$selected.'>'.$row_rsLevel4['disaggregations'].'</option>';
                                                }while($row_rsLevel4 = $query_rsLevel4->fetch());
                                            }else{
                                                echo '<option value="">.... Select Level 4 ....</option> <option value="0" selected>N/A</option>';
                                            }
                                        }else{
                                            echo '<option value="">.... Select Level 4 ....</option> <option value="0" selected>N/A</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="control-label">Update Reason *: <font align="left"  style="background-color:#eff2f4">(Give Comments about the about the assignments) </font></label>
                            <p align="left">
                                <textarea name="comments" cols="45" rows="5" class="txtboxes" id="comments" required="required" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" placeholder="Give comments that shall be show to the officer"></textarea>
                                <script>
                                    CKEDITOR.replace('comments', {
                                        height: 200,
                                        on: {
                                            instanceReady: function(ev) {
                                                // Output paragraphs as <p>Text</p>.
                                                this.dataProcessor.writer.setRules('p', {
                                                    indent: false,
                                                    breakBeforeOpen: false,
                                                    breakAfterOpen: false,
                                                    breakBeforeClose: false,
                                                    breakAfterClose: false
                                                });
                                                this.dataProcessor.writer.setRules('ol', {
                                                    indent: false,
                                                    breakBeforeOpen: false,
                                                    breakAfterOpen: false,
                                                    breakBeforeClose: false,
                                                    breakAfterClose: false
                                                });
                                                this.dataProcessor.writer.setRules('ul', {
                                                    indent: false,
                                                    breakBeforeOpen: false,
                                                    breakAfterOpen: false,
                                                    breakBeforeClose: false,
                                                    breakAfterClose: false
                                                });
                                                this.dataProcessor.writer.setRules('li', {
                                                    indent: false,
                                                    breakBeforeOpen: false,
                                                    breakAfterOpen: false,
                                                    breakBeforeClose: false,
                                                    breakAfterClose: false
                                                });
                                            }
                                        }
                                    });
                                </script>
                            </p>
                        </div>
                        <div class="col-md-12" style="margin-top:15px" align="center">
                            <input type="hidden" name="formid" value="<?=$formid?>">
                            <input type="hidden" name="MM_insert" value="assignofficerform">
                            <button class="btn btn-success" type="submit">Save</button>
                        </div>
                <!-- </div> -->
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    const getOutput = function(){
        let projid = $("#projid").val();
        if(projid != ""){
            $.ajax({
                type: "post",
                url: "ajax/inspection/index",
                data: {
                    get_output: "get_output", 
                    projid:projid,
                },
                dataType: "json",
                success: function (response) {
                    if(response.success){
                        let output = response.outputs;
                        $("#outputid").html(output);
                        $("#level3").html("<option value=''>Select Output First</option>");
                        $("#level4").html("<option value=''>Select  Output  First</option>");
                    }else{
                        $("#outputid").html("<option value=''>Select Project First</option>");
                        $("#level3").html("<option value=''>Select Project First</option>");
                        $("#level4").html("<option value=''>Select Project First</option>");
                    }
                }
            });
        }else{
            $("#outputid").html("<option value=''>Select Project First</option>");
            $("#level3").html("<option value=''>Select Project First</option>");
            $("#level4").html("<option value=''>Select Project First</option>");
        }
    }

    const getLevel3 = function(){
        let projid = $("#projid").val();
        let outputid = $("#outputid").val();
        if(projid != "" && outputid != ""){
            $.ajax({
                type: "post",
                url: "ajax/inspection/index",
                data: {
                    get_level3: "get_level3",
                    projid:projid,
                    outputid:outputid,
                },
                dataType: "json",
                success: function (response) {
                    if(response.success){
                        let level3 = response.level3;
                        $("#level3").html(level3);
                        $("#level4").html("<option value=''>Select Level 3 First</option>");
                    }else{
                        $("#level3").html("<option value=''>Select Output First</option>");
                        $("#level4").html("<option value=''>Select Output First</option>");
                    }
                }
            });
        }else{
            $("#level3").html("<option value=''>Select Output First</option>");
            $("#level4").html("<option value=''>Select Output First</option>");
        }
    }

    const getLevel4 = function(){
        let projid = $("#projid").val();
        let outputid = $("#outputid").val();
        let level3 = $("#level3").val();
        if(projid != "" && outputid != "" && level3 != ""){
            $.ajax({
                type: "post",
                url: "ajax/inspection/index",
                data: {
                    get_level4: "get_level4",
                    projid:projid,
                    outputid:outputid,
                    level3:level3,
                },
                dataType: "json",
                success: function (response) {
                    if(response.success){
                        let level4 = response.level4;
                        $("#level4").html(level4);
                    }else{
                        $("#level4").html("<option value=''>Select Level 4 First</option><option value='0'>Not Applicable</option>");
                    }
                }
            });
        }else{
            $("#level4").html("<option value=''>Select Level 3 First</option>");
        }
    }

    const validateDate = function(){
        let inspection_date = $("#inspection_date").val();
        const today = new Date().setHours(0,0,0,0);
        const y = new Date(inspection_date).setHours(0,0,0,0);
        if(today > y){
            $("#inspection_date").val("");
            swal("You cannot select date less than today.");
        }
    }
</script>

