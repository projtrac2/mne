<div class="body">
    <div class="table-responsive">
        <ul class="nav nav-tabs" style="font-size:14px">
            <li class="active" id="output_tab">
                <a data-toggle="tab" href="#output">
                    <i class="fa fa-hourglass-half bg-orange" aria-hidden="true"></i> Output Baseline Survey Tasks &nbsp;
                    <span class="badge bg-orange" id="output_counter"> 0</span>
                </a>
            </li>
            <li id="outcome_tab">
                <a data-toggle="tab" href="#outcome">
                    <i class="fa fa-file-text-o bg-blue-grey" aria-hidden="true"></i> Outcome Survey Tasks&nbsp;
                    <span class="badge bg-blue-grey" id="outcome_counter"> 0</span>
                </a>
            </li>
            <li id="impact_tab">
                <a data-toggle="tab" href="#impact">
                    <i class="fa fa-pencil-square-o bg-light-blue" aria-hidden="true"></i> Impact Survey Tasks&nbsp;
                    <span class="badge bg-light-blue" id="impact_counter"> 0</span>
                </a>
            </li>  
        </ul>
        <div class="tab-content">
            <div id="output" class="tab-pane fade in active">
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                    <thead>
                        <tr class="bg-orange">
                            <th style="width:5%">#</th>
                            <th style="width:60%">Indicator</th>
                            <th style="width:25%">Location</th>
                            <th style="width:10%">Action</th> 
                        </tr>
                    </thead>
                    <tbody id="tbody_output">
                         
                    </tbody>
                </table>
            </div>
            <div id="outcome" class="tab-pane fade">
                <ul class="nav nav-tabs" style="font-size:14px">  
                    <li id="outcome_base_tab" class="active">
                        <a data-toggle="tab" href="#outcome_baseline">
                            <i class="fa fa-file-text-o bg-blue-grey" aria-hidden="true"></i>Baseline Tasks&nbsp;
                            <span class="badge bg-blue-grey" id="outcome_baseline_counter"> 0</span>
                        </a>
                    </li>
                    <li id="outcome_eval_tab">
                        <a data-toggle="tab" href="#outcome_evaluation">
                            <i class="fa fa-pencil-square-o bg-light-blue" aria-hidden="true"></i>Evaluation Tasks&nbsp;
                            <span class="badge bg-light-blue" id="outcome_evaluation_counter"> 0</span>
                        </a>
                    </li>  
                </ul> 
                <div class="tab-content">
                    <div id="outcome_baseline" class="tab-pane fade in active">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            <thead>
                                <tr class="bg-blue-grey">
                                    <th style="width:5%">#</th> 
                                    <th style="width:60%">Project Name</th>
                                    <th style="width:25%">Location</th> 
                                    <th style="width:10%">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_outcome_baseline">
                                
                            </tbody>
                        </table>
                    </div>    

        
                    <div id="outcome_evaluation" class="tab-pane fade">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            <thead>
                                <tr class="bg-blue-grey">
                                    <th style="width:5%">#</th> 
                                    <th style="width:60%">Project Name</th>
                                    <th style="width:25%">Location</th> 
                                    <th style="width:10%">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_outcome_evaluation">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> 
            <div id="impact" class="tab-pane fade">
                <ul class="nav nav-tabs" style="font-size:14px">  
                    <li id="impact_base_tab">
                        <a data-toggle="tab" href="#impact_baseline">
                            <i class="fa fa-file-text-o bg-blue-grey" aria-hidden="true"></i> Baseline Tasks&nbsp;
                            <span class="badge bg-blue-grey" id="impact_baseline_counter"> 0</span>
                        </a>
                    </li>
                    <li id="impact_eval_tab">
                        <a data-toggle="tab" href="#impact_evaluation">
                            <i class="fa fa-pencil-square-o bg-light-blue" aria-hidden="true"></i> Evaluation Tasks&nbsp;
                            <span class="badge bg-light-blue" id="impact_evaluation_counter"> 0</span>
                        </a>
                    </li>  
                </ul> 
                <div class="tab-content">
                    <div id="impact_baseline" class="tab-pane fade">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">   
                            <thead>
                                <tr class="bg-light-blue">
                                    <th style="width:5%">#</th> 
                                    <th style="width:60%">Project Name</th>
                                    <th style="width:25%">Location</th> 
                                    <th style="width:10%">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_impact_baseline">
                                
                            </tbody>
                        </table> 
                    </div>
                    <div id="impact_evaluation" class="tab-pane fade">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">   
                            <thead>
                                <tr class="bg-light-blue">
                                    <th style="width:5%">#</th> 
                                    <th style="width:60%">Project Name</th>
                                    <th style="width:25%">Location</th> 
                                    <th style="width:10%">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_impact_evaluation">
                                
                            </tbody>
                        </table> 
                    </div> 
                </div>
            </div> 
        </div>
    </div>
</div>
<script src="general-settings/js/fecth-selected-baseline-tasks-items.js"></script>