<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h5 style="color:#FF5722; font-size:15px"><strong>Assign Officer </strong></h5>
            </div>
            <div class="header" align="center">
                <div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
                    <div class="col-md-12">
                        <a href="task-inspection.php" class="btn btn-primary pull-right" id="nextT" type="button">Go Back</a>
                    </div>
                </div>
            </div>
            <div class="body">
                <form id="assignofficerform" method="POST" name="assignofficerform" action="" enctype="multipart/form-data" autocomplete="off">
                    <div class="col-md-6">
                        <label class="control-label">Projects *:</label>
                        <div class="form-line">
                            <select name="projid" id="projid" onchange="getOutput()" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
                                <option value="">.... Select Project ....</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label">Outputs *:</label>
                        <div class="form-line">
                            <select name="outputid" id="outputid" onchange="getLevel3()" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
                                <option value="">.... Select Output ....</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label">Level 3 *:</label>
                        <div class="form-line">
                            <select name="leel3" id="level3" onchange="getLevel4()" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
                                <option value="">.... Select Level 3 ....</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label">Level 4 *:</label>
                        <div class="form-line">
                            <select name="level4" id="level4" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
                                <option value="">.... Select Level 4 ....</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label">Officers *:</label>
                        <div class="form-line">
                            <select name="officer" id="officer" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
                                <option value="">.... Select Officer ....</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="control-label">Comments *: <font align="left"  style="background-color:#eff2f4">(Give Comments about the about the assignments) </font></label>
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
                        <input type="hidden" name="MM_insert" value="assignofficerform">
                        <button class="btn btn-success" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="assets/ckeditor/ckeditor.js"></script>
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
                        $("#level4").html("<option value=''>Select Level 3 First</option>");
                    }
                }
            });
        }else{
            $("#level4").html("<option value=''>Select Level 3 First</option>");
        }
    }
</script>

