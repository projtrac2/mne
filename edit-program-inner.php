<script src="assets/custom js/add-program.js"></script>
<section class="content" style="margin-top:-20px; padding-bottom:0px">
    <div class="container-fluid">
        <div class="row clearfix" style="margin-top:10px">
            <div class="block-header">
                <?php
                echo $results;
                ?>
            </div>
            <!-- Advanced Form Example With Validation -->
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div style="color:#333; background-color:#EEE; width:100%; height:30px">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:-5px">
                                <tr>
                                    <td width="100%" height="35" style="padding-left:5px; background-color:#000; color:#FFF" bgcolor="#000000">
                                        <div align="left"><i class="fa fa-list-alt" aria-hidden="true"></i> EDIT PROGRAM</strong></div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="body">
                        <div style="margin-top:5px">
                            <form id="addprogform" method="POST" name="addprogform" action="" enctype="multipart/form-data" autocomplete="off">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border" style="background-color:#c7e1e8; border-radius:3px"><i class="fa fa-plus-square" aria-hidden="true"></i> Edit Program Details</legend>
                                    <div class="col-md-12">
                                        <label for="">Program Name *:</label>
                                        <div class="form-line">
                                            <input type="hidden" name="progid" value="<?php echo $progid; ?>" id="progid" class="form-control" required>
                                            <input type="text" name="progname" value="<?php echo $progname; ?>" id="progname" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="control-label">Program Problem Statement *:</label>
                                        <div class="form-line">
                                            <input type="text" name="progstatement" id="progstatement" value="<?= $problem_statement ?>" placeholder="Program Problem Statement" class="form-control" style="border:#CCC thin solid; border-radius: 5px" required>
                                        </div>
                                    </div>
                                    <?php
                                    if ($kpi != null) {
                                        echo '
										<div class="col-md-12">
											<label for="" class="control-label">Link to Strategic Objective? *:</label>
											<div class="form-line">
												<input name="progstrategyobjective" type="radio" checked="checked" value="0" id="strat1" class="with-gap radio-col-green insp" required="required" />
												<label for="strat1">YES</label>
												<input name="progstrategyobjective" type="radio" value="1" id="strat2" class="with-gap radio-col-red insp" required="required" />
												<label for="strat2">NO</label> 
											</div>
										</div>
										<div class="col-md-12">
											<div id="strat_div">
												<label>KPI *:</label>
												<div class="form-line">
													<select name="strategic_objective" id="strategic_objective" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
														<option value="">.... Select Strategy from list ....</option>';
                                        do {
                                            if ($kpi == $row_rsKpi['id']) {
                                                echo '<option value="' . $row_rsKpi['id'] . '" selected>' . $row_rsKpi['objective'] . '</option>';
                                            } else {
                                                echo '<option value="' . $row_rsKpi['id'] . '">' . $row_rsKpi['objective'] . '</option>';
                                            }
                                        } while ($row_rsKpi = $query_rsKpi->fetch());

                                        echo '</select>
												</div>
											</div>
										</div>
										<script>
											$(document).ready(function() {
												$("#strat_div").show(); 
											});
										</script>
                                        ';
                                    } else {
                                        echo '
										<div class="col-md-12">
											<label for="" class="control-label">Link to Strategic Objective? *:</label>
											<div class="form-line">
												<input name="progstrategyobjective" type="radio" value="0" id="strat1" class="with-gap radio-col-green insp" required="required" />
												<label for="strat1">YES</label>
												<input name="progstrategyobjective" type="radio" checked="checked"  value="1" id="strat2" class="with-gap radio-col-red insp" required="required" />
												<label for="strat2">NO</label> 
											</div>
										</div> 
										<script>
											$(document).ready(function() { 
												$("#strat_div").hide();
											});
										</script>
										';
                                    }
                                    ?>
                                    <div class="col-md-6">
                                        <label><?= $ministrylabel ?> *:</label>
                                        <div class="form-line">
                                            <select name="projsector" id="projsector" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                                                <option value="">.... Select <?= $ministrylabel ?> from list ....</option>
                                                <?php
                                                do {
                                                    if ($projsector == $row_rsSector['stid']) {
                                                ?>
                                                        <option value="<?php echo $row_rsSector['stid'] ?>" selected><?php echo $row_rsSector['sector'] ?></option>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <option value="<?php echo $row_rsSector['stid'] ?>"><?php echo $row_rsSector['sector'] ?></option>
                                                <?php
                                                    }
                                                } while ($row_rsSector = $query_rsSector->fetch());
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label><?= $departmentlabel ?> *:</label>
                                        <div class="form-line">
                                            <select name="projdept" id="projdept" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                                                <option value="">... Select <?= $ministrylabel ?> first ...</option>
                                                <?php
                                                do {
                                                    if ($row_depts["stid"] == $stid) {
                                                ?>
                                                        <option value="<?php echo $stid ?>" selected><?php echo $projdept ?></option>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <option value="<?php echo $row_depts["stid"] ?>"><?php echo $row_depts['sector'] ?></option>
                                                <?php
                                                    }
                                                } while ($row_depts = $query_depts->fetch());
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <script src="http://afarkas.github.io/webshim/js-webshim/minified/polyfiller.js"></script>
                                    <script type="text/javascript">
                                        webshims.setOptions('forms-ext', {
                                            replaceUI: 'auto',
                                            types: 'number'
                                        });
                                        webshims.polyfill('forms forms-ext');
                                    </script>
                                    <div class="col-md-12">
                                        <label class="control-label">Program Description *: <font align="left" style="background-color:#eff2f4">(Briefly describe goals and objectives of the program, approaches and execution methods, and other relevant information that explains the need for program.) </font></label>
                                        <p align="left">
                                            <textarea name="progdesc" cols="45" rows="5" class="txtboxes" id="projdesc" required="required" style="height:50px; width:98%; color:#000; font-size:12px; font-family:Verdana, Geneva, sans-serif" placeholder="Briefly describe the goals and objectives of the project, the approaches and execution methods, resource estimates, people and organizations involved, and other relevant information that explains the need for project as well as the amount of work planned for implementation.">
											<?php
                                            echo $description;
                                            ?>
											</textarea>
                                            <script>
                                                CKEDITOR.replace('projdesc', {
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
                                    <div id="form_slip">
                                        <?php
                                        if ($kpi != null) {
                                        ?>
                                            <div class="col-md-4">
                                                <label for="syear">Strategic plan Start Year *:</label>
                                                <div class="form-line">
                                                    <input type="text" name="stratplanstartYear" id="stratplanstartYear" value="<?php echo $syear ?>" class="form-control" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="syear">Strategic plan End Year *:</label>
                                                <div class="form-line">
                                                    <input type="text" name="stratplanendyear" id="stratplanendyear" value="<?php echo $endyear ?>" class="form-control" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="syear">Strategic plan Years *:</label>
                                                <div class="form-line">
                                                    <input type="text" name="stratplanyears" id="stratplanyears" value="<?php echo $years ?>" class="form-control" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Strategy *:</label>
                                                <div class="form-line">
                                                    <select name="progstrategy" id="progstrategy" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>
                                                        <option value="">.... Select Strategy ....</option>
                                                        <?php
                                                        do {
                                                            if ($row_strategies['id'] == $progstrategy) {
                                                        ?>
                                                                <option value="<?php echo $row_strategies['id']; ?>" selected><?php echo $row_strategies['strategy']; ?></option>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <option value="<?php echo $row_strategies['id'] ?>"><?php echo $row_strategies['strategy'] ?></option>
                                                        <?php
                                                            }
                                                        } while ($row_strategies = $query_strategies->fetch());

                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="control-label">Project Financial Year *:</label>
                                                <div class="form-line">
                                                    <select name="syear" id="syear" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
                                                        <option value="">.... Select Year from list ....</option>
                                                        <?php
                                                        do {
                                                            $fscyear = $row_rsYear['yr'];
                                                            if ($fscyear >= $syear && $fscyear <= $endyear) {
                                                                if ($row_rsYear['yr'] == $syear) {
                                                        ?>
                                                                    <option value="<?php echo $row_rsYear['yr'] ?>" selected><?php echo $row_rsYear['year'] ?></option>

                                                                <?php
                                                                } else {
                                                                ?>
                                                                    <option value="<?php echo $row_rsYear['yr'] ?>"><?php echo $row_rsYear['year'] ?></option>
                                                                <?php
                                                                }
                                                                ?>
                                                        <?php
                                                            }
                                                        } while ($row_rsYear = $query_rsYear->fetch());
                                                        ?>
                                                    </select>
                                                    <span id="projfscyearmsg" style="color:red"></span>
                                                </div>
                                            </div>
                                            <div id="theadyears">
                                                <?php
                                                $query_progYears = $db->prepare("SELECT year FROM tbl_progdetails WHERE progid = :progid GROUP BY year");
                                                $query_progYears->execute(array(":progid" => $progid));
                                                $row_progYears = $query_progYears->fetch();
                                                $total_progYears = $query_progYears->rowCount();
                                                do {
                                                    $dispyear = $row_progYears['year'];
                                                    echo '<input type="hidden" name="progyear[]" value="' . $dispyear . '" />';
                                                } while ($row_progYears = $query_progYears->fetch());
                                                ?>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="years">Number of Years *:</label>
                                                <div class="form-line">
                                                    <input type="text" name="years" id="years" value="<?php echo $progduration ?>" placeholder="Program Duration" class="form-control" required>
                                                    <span id="info1" style="color:red"></span>
                                                </div>
                                            </div>
                                            <script>
                                                $("#years").keyup(function(e) {
                                                    $("#theadyears").html("");
                                                    var progstrategy = $("#progstrategy").val(); //program objective 
                                                    var syear = $("#syear").val(); // program start year
                                                    var programDuration = $(this).val(); // program  duration in years 
                                                    var programStartYear = parseInt(syear); //program start year number 												
                                                    var stratPlanStartYear = parseInt($("#stratplanstartYear").val()); //strategic plan start year
                                                    var stratPlanEndyear = parseInt($("#stratplanendyear").val()); //strategic plan start year
                                                    var stratPlanduration = parseInt($("#stratplanyears").val()); //strategic plan start year
                                                    if (progstrategy != '') {
                                                        if (syear != '') {
                                                            if (programDuration != '') {
                                                                programDuration = parseInt(programDuration);
                                                                if (programDuration > 0) {
                                                                    var programEndYear = ((programStartYear + programDuration) - 1);
                                                                    if (programStartYear >= stratPlanStartYear && programStartYear <= stratPlanEndyear &&
                                                                        programEndYear >= stratPlanStartYear && programEndYear <= stratPlanEndyear) {
                                                                        $("#info1").hide();
                                                                        $("#program").show();
                                                                        var dispyear = parseInt(syear);
                                                                        var finyear = parseInt(syear) + 1;
                                                                        var containerH = $('<tr class="source bg-grey">' +
                                                                            '<th rowspan="2">Output &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>' +
                                                                            '<th rowspan="2">Indicator &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>');
                                                                        var content = $('<tr>');
                                                                        var contain = $('<div>');
                                                                        for (var i = 0; i < programDuration; i++) {
                                                                            containerH.append('<th colspan="2"> ' + dispyear + "/" + finyear + '</th>');
                                                                            content.append('<th>Target &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>' +
                                                                                '<th>Budget (ksh) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>');
                                                                            contain.append('<input type="hidden" name="progyear[]" value="' + dispyear + ' " />');
                                                                            dispyear++;
                                                                            finyear++;
                                                                        }
                                                                        containerH.append('<th rowspan="2"><button type="button" name="addprogramplus" id="addprogramplus" onclick="add_rowprogram();" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th> ' +
                                                                            '</tr>');

                                                                        content.append('</tr>');
                                                                        contain.append('</div>');
                                                                        $("#theadyears").html(contain);
                                                                        Array.prototype.push.apply(containerH, content);
                                                                        $(".thead").html(containerH);
                                                                    } else {
                                                                        $("#info1").show();
                                                                        $("#program").hide();
                                                                        $("#info1").html("Program duration should be less than Strategic Plan duration")
                                                                        $(this).val('');
                                                                    }
                                                                } else {
                                                                    $("#info1").show();
                                                                    $("#program").hide();
                                                                    $("#info1").html("Program Duration cannot be equal or less than 0");
                                                                    $(this).val('');
                                                                }
                                                            } else {
                                                                $("#info1").show();
                                                                $("#program").hide();
                                                                $("#info1").html("Program Duration canot be empty");
                                                                $(this).val('');
                                                            }
                                                        } else {
                                                            $("#info1").show();
                                                            $("#program").hide();
                                                            $("#info1").html("Select Financial Year first");
                                                            $(this).val('');
                                                        }
                                                    } else {
                                                        $("#info1").show();
                                                        $("#program").hide();
                                                        $("#info1").html("Select Strategy first");
                                                        $(this).val('');
                                                    }
                                                });
                                                $(document).ready(function() {
                                                    $("#projfscyearmsg").hide();
                                                });
                                                $("#syear").change(function(e) {
                                                    var progstrategy = $("#progstrategy").val(); //program objective 
                                                    var programDuration = $("#years").val(); //  program  duration in years 
                                                    var syear = $(this).val(); // program start year
                                                    var programStartYear = parseInt(syear); //program start year number 												
                                                    var stratPlanStartYear = parseInt($("#stratplanstartYear").val()); //strategic plan start year
                                                    var stratPlanEndyear = parseInt($("#stratplanendyear").val()); //strategic plan start year
                                                    var stratPlanduration = parseInt($("#stratplanyears").val()); //strategic plan start year 
                                                    if (syear != '' && programDuration != '') {
                                                        programDuration = parseInt(programDuration);
                                                        var programEndYear = ((programStartYear + programDuration) - 1);
                                                        if (programStartYear >= stratPlanStartYear && programStartYear <= stratPlanEndyear &&
                                                            programEndYear >= stratPlanStartYear && programEndYear <= stratPlanEndyear) {
                                                            $("#info1").hide();
                                                            $("#projfscyearmsg").hide();
                                                            $("#program").show();
                                                            var dispyear = parseInt(syear);
                                                            var finyear = parseInt(syear) + 1;
                                                            var containerH = $('<tr class="source bg-grey">' +
                                                                '<th rowspan="2">Output &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>' +
                                                                '<th rowspan="2">Indicator &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>');
                                                            var content = $('<tr>');
                                                            var contain = $('<div>');
                                                            for (var i = 0; i < programDuration; i++) {
                                                                containerH.append('<th colspan="2"> ' + dispyear + "/" + finyear + '</th>');
                                                                content.append('<th>Target &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>' +
                                                                    '<th>Budget (ksh) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>');
                                                                contain.append('<input type="hidden" name="progyear[]" value="' + dispyear + ' " />');
                                                                dispyear++;
                                                                finyear++;
                                                            }
                                                            containerH.append('<th rowspan="2"><button type="button" name="addprogramplus" id="addprogramplus" onclick="add_rowprogram();" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th> ' +
                                                                '</tr>');

                                                            content.append('</tr>');
                                                            contain.append('</div>');
                                                            $("#theadyears").html(contain);
                                                            Array.prototype.push.apply(containerH, content);
                                                            $(".thead").html(containerH);
                                                        } else {
                                                            $("#info1").show();
                                                            $("#program").hide();
                                                            $("#info1").html("Program duration should be less than Strategic Plan duration")
                                                            $("#years").val("");
                                                        }
                                                    } else if (syear != '' && programDuration == '') {
                                                        $("#info1").show();
                                                        $("#projfscyearmsg").hide();
                                                        $("#program").hide();
                                                        $("#info1").html("Enter Program Duration");
                                                    } else {
                                                        $("#projfscyearmsg").show();
                                                        $("#projfscyearmsg").html("Select Financial Year first");
                                                        $("#info1").html("Select Financial Year first");
                                                        $("#program").hide();
                                                        $("#years").val("");
                                                    }
                                                });
                                            </script>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="card">
                                                    <div class="header">
                                                        <div class="clearfix" style="margin-top:5px; margin-bottom:5px">
                                                            <label>Output Details</label>
                                                        </div>
                                                    </div>
                                                    <div class="body">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-striped table-hover thead" id="program" style="width:100%">
                                                                <thead class="">
                                                                    <tr class="source bg-grey">
                                                                        <th rowspan="2">Output &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                                        <th rowspan="2">Indicator &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                                        <?php
                                                                        $dispyear  = $syear;
                                                                        for ($j = 0; $j < $progduration; $j++) {
                                                                            $dispyear++;
                                                                            echo '<th colspan="2">' . $syear . '/' . $dispyear . '</th>';
                                                                            $syear++;
                                                                        }
                                                                        ?>
                                                                        <th rowspan="2">
                                                                            <button type="button" name="addprogramplus" id="addprogramplus" onclick="add_rowprogram();" class="btn btn-success btn-sm">
                                                                                <span class="glyphicon glyphicon-plus"></span>
                                                                            </button>
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <?php
                                                                        for ($j = 0; $j < $progduration; $j++) {
                                                                        ?>
                                                                            <th>Target &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                                            <th>Budget (ksh) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="program_body">
                                                                    <?php
                                                                    $query_outputIndicator = $db->prepare("SELECT d.output, i.indicator_name, i.indid, i.indicator_dept FROM tbl_progdetails d INNER JOIN tbl_indicator i ON i.indid = d.indicator WHERE d.progid = '$progid' GROUP BY d.indicator");
                                                                    $query_outputIndicator->execute();
                                                                    $row_outputIndicator = $query_outputIndicator->fetch();
                                                                    $total_outputIndicator = $query_outputIndicator->rowCount();
                                                                    $rowno = 0;
                                                                    $counter = 1;
                                                                    do {
                                                                        $rowno++;
                                                                        $row = "row" . $rowno;
                                                                        $progsyear = $row_rsProgram['syear'];
                                                                        $output = $row_outputIndicator['output'];
                                                                        $indicator = $row_outputIndicator['indicator_name'];
                                                                        $indid = $row_outputIndicator['indid'];
                                                                        $inddeptid = $row_outputIndicator['indicator_dept'];

                                                                        $query_Indicator = $db->prepare("SELECT tbl_measurement_units.unit FROM tbl_indicator  INNER JOIN tbl_measurement_units ON tbl_measurement_units.id =tbl_indicator.indicator_unit WHERE tbl_indicator.indid =:indicator");
                                                                        $query_Indicator->execute(array(":indicator" => $indid));
                                                                        $row = $query_Indicator->fetch();
                                                                        $unit = $row['unit'];
                                                                    ?>
                                                                        <tr id="<?php echo $row ?>">
                                                                            <td>
                                                                                <div class="form-line">
                                                                                    <input name="output[]" type="text" id="output<?php echo $row ?>" value="<?php echo $output ?>" class="form-control" required>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <select name="indicator[]" id="indicator<?php echo $row ?>" onchange=outputChange("<?php echo $row ?>") class="form-control selectOutput show-tick indicator" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>' +
                                                                                    <option value="">... Select Indicator ...</option>
                                                                                    <?php
                                                                                    $query_rsIndicator = $db->prepare("SELECT * FROM tbl_indicator where indicator_category='Output' and active='1' and indicator_dept='$inddeptid'");
                                                                                    $query_rsIndicator->execute();
                                                                                    $row_rsIndicator = $query_rsIndicator->fetchAll();

                                                                                    foreach ($row_rsIndicator as $val) {
                                                                                        $indicatorvid = $val['indid'];
                                                                                        $indicatorVal = $val['indicator_name'];
                                                                                        if ($indicatorvid == $indid) {
                                                                                    ?>
                                                                                            <option value="<?php echo $indicatorvid ?>" selected><?php echo $indicatorVal ?></option>
                                                                                        <?php
                                                                                        } else {
                                                                                        ?>
                                                                                            <option value="<?php echo $indicatorvid ?>"><?php echo $indicatorVal ?></option>
                                                                                    <?php
                                                                                        }
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                            </td>
                                                                            <?php
                                                                            for ($i = 0; $i < $progduration; $i++) {
                                                                                $query_progdetails = $db->prepare("SELECT * FROM tbl_progdetails WHERE progid = '$progid' and year = '$progsyear' and indicator = '$indid'");
                                                                                $query_progdetails->execute();
                                                                                $row_progdeatils = $query_progdetails->fetch();
                                                                                $total_progdetails = $query_progdetails->rowCount();
                                                                                do {
                                                                                    $target =  $row_progdeatils['target'];
                                                                                    $budget =  $row_progdeatils['budget'];
                                                                                    $output =  $row_progdeatils['budget'];


                                                                            ?>
                                                                                    <td>
                                                                                        <input name="target<?php echo $indid ?>[]" placeholder="<? $unit ?>" id="targetrow<?php echo $counter ?>" class="form-control targetrow<?php echo $counter ?>" value="<?php echo $target ?>" type="number" min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" style="border:#CCC thin solid; border-radius: 5px" required>
                                                                                    </td>
                                                                                    <td>
                                                                                        <input name="budget<?php echo $indid ?>[]" id="budgetrow<?php echo $counter ?>" class="form-control currency targetrow<?php echo $counter ?>" value="<?php echo $budget ?>" type="number" min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" style="border:#CCC thin solid; border-radius: 5px" required>
                                                                                    </td>
                                                                            <?php
                                                                                } while ($row_progdeatils = $query_progdetails->fetch());
                                                                                $progsyear++;
                                                                            }
                                                                            ?>
                                                                            <td>
                                                                                <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_program_row("<?php echo $row ?>")>
                                                                                    <span class="glyphicon glyphicon-minus"></span>
                                                                                </button>
                                                                            </td>
                                                                        </tr>
                                                                    <?php
                                                                        $counter++;
                                                                    } while ($row_outputIndicator = $query_outputIndicator->fetch());
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        } else {
                                        ?>
                                            <div class="col-md-6">
                                                <label class="control-label">Project Financial Year *:</label>
                                                <div class="form-line">
                                                    <select name="syear" id="syear" class="form-control show-tick" style="border:1px #CCC thin solid; border-radius:5px" data-live-search="true" required="required">
                                                        <option value="">.... Select Year from list ....</option>
                                                        <?php
                                                        do {
                                                            $currentYear =  date("Y") - 1;
                                                            $yearVal = $row_rsYear['yr'];
                                                            if ($currentYear <= $yearVal) {
                                                                if ($row_rsYear['yr'] == $syear) {
                                                                    echo '<option value="' . $yearVal . '" selected>' . $row_rsYear['year'] . '</option>';
                                                                } else {
                                                                    echo '<option value="' . $yearVal . '">' . $row_rsYear['year'] . '</option>';
                                                                }
                                                            }
                                                        } while ($row_rsYear = $query_rsYear->fetch());
                                                        ?>
                                                    </select>
                                                    <span id="projfscyearmsg" style="color:red"></span>
                                                </div>
                                            </div>
                                            <div id="theadyears">
                                                <?php
                                                $query_progYears = $db->prepare("SELECT  year FROM tbl_progdetails WHERE progid = '$progid' GROUP BY year");

                                                $query_progYears->execute();
                                                $row_progYears = $query_progYears->fetch();
                                                $total_progYears = $query_progYears->rowCount();
                                                do {
                                                    $dispyear = $row_progYears['year'];
                                                    echo '<input type="hidden" name="progyear[]" value="' . $dispyear . '" />';
                                                } while ($row_progYears = $query_progYears->fetch());
                                                ?>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="years">Number of Years *:</label>
                                                <div class="form-line">
                                                    <input type="text" name="years" id="years" value="<?php echo $progduration ?>" placeholder="Program Duration" class="form-control" required>
                                                    <span id="info1" style="color:red"></span>
                                                </div>
                                            </div>
                                            <script>
                                                $("#years").keyup(function(e) {
                                                    $("#theadyears").html("");
                                                    var syear = $("#syear").val(); // program start year
                                                    var programDuration = $(this).val(); // program  duration in years 
                                                    var programStartYear = parseInt(syear); //program start year number
                                                    if (syear != '') {
                                                        if (programDuration != '') {
                                                            programDuration = parseInt(programDuration);
                                                            if (programDuration > 0) {
                                                                var programEndYear = ((programStartYear + programDuration) - 1);
                                                                $("#info1").hide();
                                                                $("#program").show();
                                                                var dispyear = parseInt(syear);
                                                                var finyear = parseInt(syear) + 1;
                                                                var containerH = $('<tr class="source bg-grey"">' +
                                                                    '<th rowspan="2">Output &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>' +
                                                                    '<th rowspan="2">Indicator &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>');
                                                                var content = $('<tr>');
                                                                var contain = $('<div>');
                                                                for (var i = 0; i < programDuration; i++) {
                                                                    containerH.append('<th colspan="2"> ' + dispyear + "/" + finyear + '</th>');
                                                                    content.append('<th>Target &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>' +
                                                                        '<th>Budget (ksh) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>');
                                                                    contain.append('<input type="hidden" name="progyear[]" value="' + dispyear + ' " />');
                                                                    dispyear++;
                                                                    finyear++;
                                                                }
                                                                containerH.append('<th rowspan="2"><button type="button" name="addprogramplus" id="addprogramplus" onclick="add_rowprogram();" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th> ' +
                                                                    '</tr>');

                                                                content.append('</tr>');
                                                                contain.append('</div>');
                                                                $("#theadyears").html(contain);
                                                                Array.prototype.push.apply(containerH, content);
                                                                $(".thead").html(containerH);

                                                            } else {
                                                                $("#info1").show();
                                                                $("#program").hide();
                                                                $("#info1").html("Program Duration cannot be equal or less than 0");
                                                                $(this).val('');
                                                            }
                                                        } else {
                                                            $("#info1").show();
                                                            $("#program").hide();
                                                            $("#info1").html("Program Duration canot be empty");
                                                            $(this).val('');
                                                        }
                                                    } else {
                                                        $("#info1").show();
                                                        $("#program").hide();
                                                        $("#info1").html("Select Financial Year first");
                                                        $(this).val('');
                                                    }
                                                });
                                                $(document).ready(function() {
                                                    $("#projfscyearmsg").hide();
                                                });
                                                $("#syear").change(function(e) {
                                                    var programDuration = $("#years").val(); //  program  duration in years 
                                                    var syear = $(this).val(); // program start year
                                                    var programStartYear = parseInt(syear); //program start year number 												

                                                    if (syear != '' && programDuration != '') {
                                                        programDuration = parseInt(programDuration);
                                                        var programEndYear = ((programStartYear + programDuration) - 1);
                                                        $("#info1").hide();
                                                        $("#projfscyearmsg").hide();
                                                        $("#program").show();
                                                        var dispyear = parseInt(syear);
                                                        var finyear = parseInt(syear) + 1;
                                                        var containerH = $('<tr class="source bg-grey">' +
                                                            '<th rowspan="2">Output &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>' +
                                                            '<th rowspan="2">Indicator &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>');
                                                        var content = $('<tr>');
                                                        var contain = $('<div>');
                                                        for (var i = 0; i < programDuration; i++) {
                                                            containerH.append('<th colspan="2"> ' + dispyear + "/" + finyear + '</th>');
                                                            content.append('<th>Target &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>' +
                                                                '<th>Budget (ksh) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>');
                                                            contain.append('<input type="hidden" name="progyear[]" value="' + dispyear + ' " />');
                                                            dispyear++;
                                                            finyear++;
                                                        }
                                                        containerH.append('<th rowspan="2"><button type="button" name="addprogramplus" id="addprogramplus" onclick="add_rowprogram();" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th> ' +
                                                            '</tr>');

                                                        content.append('</tr>');
                                                        contain.append('</div>');
                                                        $("#theadyears").html(contain);
                                                        Array.prototype.push.apply(containerH, content);
                                                        $(".thead").html(containerH);

                                                    } else if (syear != '' && programDuration == '') {
                                                        $("#info1").show();
                                                        $("#projfscyearmsg").hide();
                                                        $("#program").hide();
                                                        $("#info1").html("Enter Program Duration");
                                                    } else {
                                                        $("#projfscyearmsg").show();
                                                        $("#projfscyearmsg").html("Select Financial Year first");
                                                        $("#info1").html("Select Financial Year first");
                                                        $("#program").hide();
                                                        $("#years").val("");
                                                    }
                                                });
                                            </script>
                                            <div class="row clearfix">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="card">
                                                        <div class="header">
                                                            <div class="clearfix" style="margin-top:5px; margin-bottom:5px">
                                                                <h5>Output Details</h5>
                                                            </div>
                                                        </div>
                                                        <div class="body">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-striped table-hover thead" id="program" style="width:100%">
                                                                    <thead class="">
                                                                        <tr>
                                                                            <th rowspan="2">Output &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                                            <th rowspan="2">Indicator &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>

                                                                            <?php
                                                                            $dispyear  = $syear;
                                                                            for ($j = 0; $j < $progduration; $j++) {
                                                                                $dispyear++;
                                                                                echo '<th colspan="2">' . $syear . '/' . $dispyear . '</th>';
                                                                                $syear++;
                                                                            }
                                                                            ?>
                                                                            <th rowspan="2">
                                                                                <button type="button" name="addprogramplus" id="addprogramplus" onclick="add_rowprogram();" class="btn btn-success btn-sm">
                                                                                    <span class="glyphicon glyphicon-plus"></span>
                                                                                </button>
                                                                            </th>
                                                                        </tr>
                                                                        <tr>
                                                                            <?php
                                                                            for ($j = 0; $j < $progduration; $j++) {
                                                                            ?>
                                                                                <th>Target &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                                                <th>Budget (ksh) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="program_body">

                                                                        <?php
                                                                        $query_outputIndicator = $db->prepare("SELECT tbl_progdetails.output,  tbl_indicator.indicator_name, tbl_indicator.indid FROM tbl_progdetails INNER JOIN tbl_indicator ON tbl_indicator.indid = tbl_progdetails.indicator WHERE tbl_progdetails.progid = :progid GROUP BY tbl_progdetails.indicator ORDER BY tbl_progdetails.id");
                                                                        $query_outputIndicator->execute(array(":progid" => $progid));
                                                                        $total_outputIndicator = $query_outputIndicator->rowCount();
                                                                        $rowno = 0;
                                                                        $counter = 1;
                                                                        if ($total_outputIndicator > 0) {
                                                                            while ($row_outputIndicator = $query_outputIndicator->fetch()) {
                                                                                $rowno++;
                                                                                $row = "row" . $rowno;

                                                                                $progsyear = $row_rsProgram['syear'];
                                                                                $output = $row_outputIndicator['output'];
                                                                                $indicator = $row_outputIndicator['indicator_name'];
                                                                                $indid = $row_outputIndicator['indid'];
                                                                        ?>
                                                                                <tr id="<?php echo $row ?>">
                                                                                    <td>
                                                                                        <div class="form-line">
                                                                                            <input name="output[]" type="text" id="output<?php echo $row ?>" value="<?php echo $output ?>" class="form-control" required>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <select name="indicator[]" id="indicator<?php echo $row ?>" onchange=outputChange("<?php echo $row ?>") class="form-control selectOutput show-tick indicator" style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>' +
                                                                                            <option value="">... Select Indicator ...</option>
                                                                                            <?php
                                                                                            $query_rsIndicator = $db->prepare("SELECT * FROM tbl_indicator WHERE  indicator_dept ='$stid'");
                                                                                            $query_rsIndicator->execute();
                                                                                            $row_rsIndicator = $query_rsIndicator->fetchAll();

                                                                                            foreach ($row_rsIndicator as $val) {
                                                                                                $indicatorvid = $val['indid'];
                                                                                                $indicatorVal = $val['indicator_name'];
                                                                                                if ($indicatorvid == $indid) {
                                                                                            ?>
                                                                                                    <option value="<?php echo $indicatorvid ?>" selected><?php echo $indicatorVal ?></option>
                                                                                                <?php
                                                                                                } else {
                                                                                                ?>
                                                                                                    <option value="<?php echo $indicatorvid ?>"><?php echo $indicatorVal ?></option>
                                                                                            <?php
                                                                                                }
                                                                                            }
                                                                                            ?>
                                                                                        </select>
                                                                                    </td>
                                                                                    <?php
                                                                                    for ($i = 0; $i < $progduration; $i++) {
                                                                                        $query_progdetails = $db->prepare("SELECT * FROM tbl_progdetails  
                                                                                WHERE progid = :progid and year = '$progsyear' and indicator = '$indid'");
                                                                                        $query_progdetails->execute(array(":progid" => $progid));
                                                                                        $total_progdetails = $query_progdetails->rowCount();
                                                                                        while ($row_progdeatils = $query_progdetails->fetch()) {
                                                                                            $target =  $row_progdeatils['target'];
                                                                                            $budget =  $row_progdeatils['budget'];
                                                                                            $output =  $row_progdeatils['budget'];
                                                                                            $indicator =  $row_progdeatils['budget'];
                                                                                    ?>
                                                                                            <td>
                                                                                                <input name="target<?php echo $indid ?>[]" id="targetrow<?php echo $counter ?>" class="form-control targetrow<?php echo $counter ?>" value="<?php echo $target ?>" type="number" min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" style="border:#CCC thin solid; border-radius: 5px" required>
                                                                                            </td>
                                                                                            <td>
                                                                                                <input name="budget<?php echo $indid ?>[]" id="budgetrow<?php echo $counter ?>" class="form-control currency targetrow<?php echo $counter ?>" value="<?php echo  $budget ?>" type="number" min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" style="border:#CCC thin solid; border-radius: 5px" required>
                                                                                            </td>
                                                                                    <?php
                                                                                        };
                                                                                        $progsyear++;
                                                                                    }
                                                                                    ?>
                                                                                    <td>
                                                                                        <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_program_row("<?php echo $row ?>")>
                                                                                            <span class="glyphicon glyphicon-minus"></span>
                                                                                        </button>
                                                                                    </td>
                                                                                </tr>
                                                                        <?php
                                                                                $counter++;
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
                                            <script>
                                                function add_rowprogram() {
                                                    var $rowno = parseInt($("#program_body tr").length);
                                                    var years = $("#years").val();
                                                    var projdept = $("#projdept").val();
                                                    if (years != '') {
                                                        if (projdept != '') {
                                                            $rowno = $rowno + 1;
                                                            var containerB = $('<tr class="" id="row' + $rowno + '">' +
                                                                '<td>' +
                                                                '<div class="form-line">' +
                                                                '<input  type="text" name="output[]" id="output' + $rowno + '"  class="form-control"    required>' +
                                                                '</div> ' +
                                                                '</td>' +
                                                                '<td>' +
                                                                '<div class="form-line">' +
                                                                '<select name="indicator[]" id="indicatorrow' + $rowno + '" onchange=outputChange("row' + $rowno + '")   class="form-control selectOutput show-tick indicator"  style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>' +
                                                                '<option value="">... Select Division first ...</option>' +
                                                                '</select>' +
                                                                '</div> ' +
                                                                '</td>');
                                                            for (var i = 0; i < years; i++) {
                                                                containerB.append('<td> ' +
                                                                    '<input name="targetrow' + $rowno + '[]" id="targetrow' + $rowno + '" class="form-control targetrow' + $rowno + '" type="number" min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" style="border:#CCC thin solid; border-radius: 5px"  required>' +
                                                                    '</td><td><input name="budgetrow' + $rowno + '[]" id="budgetrow' + $rowno + '" class="form-control currency budgetrow' + $rowno + '" type="number" min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" style="border:#CCC thin solid; border-radius: 5px"  required>' +
                                                                    '</td>');
                                                            }
                                                            containerB.append('<td><button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_program_row("row' + $rowno + '")><span class="glyphicon glyphicon-minus"></span></button></td></tr>');
                                                            var sourceid = 'source' + $rowno;
                                                            $("#program tr:last").after(containerB);
                                                            getIndicator($rowno);
                                                        } else {
                                                            alert("Select Division First");
                                                        }
                                                    } else {
                                                        alert("Enter Years ");
                                                    }
                                                }

                                                function outputChange(rowno) {
                                                    var rw = rowno;
                                                    var output = 'output' + rw;
                                                    var indicator = 'indicator' + rw;
                                                    var target = '.target' + rw;
                                                    var budget = '.budget' + rw;
                                                    var indicatorVal = $("#" + indicator).val();

                                                    if (indicatorVal != "") {
                                                        $.ajax({
                                                            type: 'POST',
                                                            url: 'assets/processor/add-program-process',
                                                            data: 'getUnits=' + indicatorVal,
                                                            dataType: "json",
                                                            success: function(html) {
                                                                $(target).attr('placeholder', html);
                                                                var targets = 'target' + indicatorVal + '[]';
                                                                var budgets = 'budget' + indicatorVal + '[]';
                                                                $(target).attr('name', targets);
                                                                $(budget).attr('name', budgets);
                                                            }
                                                        });
                                                    }
                                                }


                                                function delete_program_row(rowno) {
                                                    $('#' + rowno).remove();
                                                }
                                            </script>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-hover" id="funding_table" style="width:100%">
                                            <thead>
                                                <tr class="bg-grey">
                                                    <th width="5%">#</th>
                                                    <th width="40%">Category</th>
                                                    <th width="50%">Amount</th>
                                                    <th width="5%"><button type="button" name="addplus" id="addplus" onclick="add_row_financier();" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus"></span></button></th>
                                                </tr>
                                            </thead>
                                            <tbody id="financier_table_body">
                                                <tr></tr>
                                                <?php
                                                $rowno = 0;
                                                do {
                                                    $rowno++;
                                                    $sourceCategory = $row_myprogfunding['sourcecategory'];
                                                    $amountfunding = $row_myprogfunding['amountfunding'];
                                                    $myprojfundingid = $row_myprogfunding['id'];
                                                    $row = "financerow" . $rowno;
                                                ?>
                                                    <tr id="<?php echo $row ?>">
                                                        <td><?= $rowno ?></td>
                                                        <td>
                                                            <input type="hidden" name="myprojfunding[]" value="<?php echo $myprojfundingid; ?>" class="form-control" required="required" />
                                                            <select name="source_category[]" id="source_categoryrow<?php echo $row ?>" class="form-control selected_category" required="required">
                                                                <option value="">Select Funds Source Category</option>
                                                                <?php
                                                                $query_rsFunding_type =  $db->prepare("SELECT * FROM tbl_funding_type");
                                                                $query_rsFunding_type->execute();
                                                                $row_rsFunding_type = $query_rsFunding_type->fetch();
                                                                $totalRows_rsFunding_type = $query_rsFunding_type->rowCount();
                                                                $input = '<option value="">Select Funds Source </option>';
                                                                do {
                                                                    if ($sourceCategory == $row_rsFunding_type['id']) {
                                                                ?>
                                                                        <option value="<?= $row_rsFunding_type['id'] ?>" selected> <?= $row_rsFunding_type['type'] ?></option>';
                                                                    <?php
                                                                    } else {
                                                                    ?>
                                                                        <option value="<?= $row_rsFunding_type['id'] ?>"><?= $row_rsFunding_type['type'] ?></option>';
                                                                <?php
                                                                    }
                                                                } while ($row_rsFunding_type = $query_rsFunding_type->fetch());
                                                                //echo $input;
                                                                ?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="amountfunding[]" placeholder="Enter Amount Funding" value="<?php echo $amountfunding; ?>" class="form-control" required="required" />
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_row_financier("<?php echo $row ?>")>
                                                                <span class="glyphicon glyphicon-minus"></span>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php
                                                } while ($row_myprogfunding = $query_myprogfunding->fetch());
                                                ?>
                                            </tbody>
                                        </table>

                                        <script type="text/javascript">
                                            $rowno = $("#program_body tr").length;
                                            $rowno = $rowno + 1;

                                            function add_rowprogram() {
                                                var years = $("#years").val();
                                                var projdept = $("#projdept").val();
                                                if (years != '') {
                                                    if (projdept != '') {
                                                        var containerB = $('<tr class="" id="row' + $rowno + '">' +
                                                            '<td>' +
                                                            '<div class="form-line">' +
                                                            '<input  type="text" name="output[]" id="output' + $rowno + '"  class="form-control"    required>' +
                                                            '</div> ' +
                                                            '</td>' +
                                                            '<td>' +
                                                            '<div class="form-line">' +
                                                            '<select name="indicator[]" id="indicatorrow' + $rowno + '" onchange=outputChange("row' + $rowno + '")   class="form-control selectOutput show-tick output"  style="border:#CCC thin solid; border-radius:5px" data-live-search="true" required>' +
                                                            '<option value="">... Select <?= $departmentlabel ?> first ...</option>' +
                                                            '</select>' +
                                                            '</div> ' +
                                                            '</td>');
                                                        for (var i = 0; i < years; i++) {
                                                            containerB.append('<td> ' +
                                                                '<input name="targetrow' + $rowno + '[]" id="targetrow' + $rowno + '" class="form-control targetrow' + $rowno + '" type="number" min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" style="border:#CCC thin solid; border-radius: 5px"  required>' +
                                                                '</td><td><input name="budgetrow' + $rowno + '[]" id="budgetrow' + $rowno + '" class="form-control currency budgetrow' + $rowno + '" type="number" min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" style="border:#CCC thin solid; border-radius: 5px"  required>' +
                                                                '</td>');
                                                        }
                                                        containerB.append('<td><button type="button" class="btn btn-danger btn-sm" id="delete" onclick=delete_program_row("row' + $rowno + '")><span class="glyphicon glyphicon-minus"></span></button></td></tr>');
                                                        var sourceid = 'source' + $rowno;
                                                        $rowno = $rowno + 1;
                                                        $("#program tr:last").after(containerB);
                                                        getIndicator($rowno);
                                                    } else {
                                                        alert("Select <?= $departmentlabel ?> First");
                                                    }
                                                } else {
                                                    alert("Enter Years ");
                                                }
                                            }

                                            function outputChange(rowno) {
                                                var rw = rowno;
                                                var output = 'output' + rw;
                                                var indicator = 'indicator' + rw;
                                                var target = '.target' + rw;
                                                var budget = '.budget' + rw;
                                                var indicatorVal = $("#" + indicator).val();

                                                if (indicatorVal != "") {
                                                    $.ajax({
                                                        type: 'POST',
                                                        url: 'assets/processor/add-program-process',
                                                        data: 'getUnits=' + indicatorVal,
                                                        dataType: "json",
                                                        success: function(html) {
                                                            $(target).attr('placeholder', html);
                                                            var targets = 'target' + indicatorVal + '[]';
                                                            var budgets = 'budget' + indicatorVal + '[]';
                                                            $(target).attr('name', targets);
                                                            $(budget).attr('name', budgets);
                                                        }
                                                    });
                                                }
                                            }

                                            function delete_program_row(rowno) {
                                                $('#' + rowno).remove();
                                            }

                                            //function to get output
                                            function getIndicator(rowno) {
                                                var projdeptid = $("#projdept").val();
                                                var indicator = '#indicatorrow' + (rowno - 1);
                                                if (projdeptid) {
                                                    $.ajax({
                                                        type: 'POST',
                                                        url: 'assets/processor/add-program-process',
                                                        data: 'getprogindicator=' + projdeptid,
                                                        success: function(html) {
                                                            $(indicator).html(html);
                                                        }
                                                    });
                                                } else {
                                                    $(indicator).html('<option value="">... Select <?= $departmentlabel ?> First ... </option>');
                                                }
                                            }
                                        </script>
                                    </div>
                                    <div class="col-md-12" style="margin-top:15px" align="center">
                                        <input type="hidden" name="MM_update" value="editprogramfrm">
                                        <button class="btn btn-success" type="submit">Save</button>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>