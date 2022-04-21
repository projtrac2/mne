<div class="body">
    <div class="table-responsive">
        <ul class="nav nav-tabs" style="font-size:14px">
            <li class="active">
                <a data-toggle="tab" href="#home">
                    <i class="fa fa-hourglass-half bg-light-blue" aria-hidden="true"></i> New Tasks&nbsp;
                    <span class="badge bg-light-blue"><?php echo $count_pendinginspections; ?></span>
                </a>
            </li>
            <li>
                <a data-toggle="tab" href="#menu1">
                    <i class="fa fa-check-square-o bg-light-green" aria-hidden="true"></i>Failed Tasks &nbsp;
                    <span class="badge bg-light-green"><?php echo $count_inspectionsdone; ?></span>
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="home" class="tab-pane fade in active">
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                    <thead>
                        <tr class="bg-blue-grey">
                            <th style="width:3%">#</th>
                            <th style="width:54%">Tasks</th>
                            <th style="width:54%">Project</th>
                            <th style="width:20%">Level 3</th>
                            <th style="width:20%">Level 4</th>
                            <th style="width:20%">Inspector</th>
                            <th style="width:12%">Due Date</th>
                            <th style="width:12%">Status</th>
                            <th style="width:7%">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div id="menu1" class="tab-pane fade">
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                    <thead>
                        <tr class="bg-light-blue">
                            <th style="width:3%">#</th>
                            <th style="width:23%">Task</th>
                            <th style="width:25%">Project</th>
                            <th style="width:20%">Level 3</th>
                            <th style="width:20%">Level 4</th>
                            <th style="width:20%">Inspector</th>
                            <th style="width:7%">Score</th>
                            <th style="width:10%">Date Inspected</th>
                            <th style="width:12%">Report</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>