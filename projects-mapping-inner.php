    <section class="content" style="margin-top:-20px; padding-bottom:0px">
        <div class="container-fluid">
			<div class="row clearfix" style="margin-top:10px">
            <!-- Advanced Form Example With Validation -->
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
							<form action="" method="post" id="myform">
								<div class="row">
									<h4 style="margin-left:20px">PROJECTS MAPPING</h4>
									<div class="col-md-3">
										<label for="sublocation"><?=$level1label?></label>
										<select name="projcommunity" id="projcommunity" class="form-control show-tick" style="border:#CCC thin solid; border-radius:5px; width:98%" data-live-search="true" required>
											<option value="">.... Select from list ....</option>
											<?php
											do {  
											?>
												<font color="black"> <option value="<?php echo $row_rsCommunity['id']?>"><?php echo $row_rsCommunity['state']?></option></font>
											<?php
											} while ($row_rsCommunity = $query_rsCommunity->fetch());
											?>
										</select>
									</div>
									<div class="col-md-3">
										<label for="location"><?=$level2label?></label>
										<select name="projlga" id="projlga" class="form-control">
											<option value="">.... Select Subcounty First list ....</option>
										</select>
									</div>
									<div class="col-md-3">
										<label for="waypoints">Way Points</label>
										<select name="waypoints" class="form-control show-tick" id="waypoints" style="border:#CCC thin solid; border-radius:5px; width:98%" data-live-search="true" required>
											<option value="">.... Select ....</option>
											<?php
											do {  
											?>
												<option value="<?php echo $row_rsMapType['typeid'];?>"><?php echo $row_rsMapType['type'] ?></option>
											<?php
											} while ($row_rsMapType = $query_rsMapType->fetch());
											?>
										</select> 
									</div>
									<div class="col-md-3">
										<label for="submit">&nbsp;</label><br>
										<button class="btn btn-primary submit" id="submit">Submit</button>
									</div>
								</div>
							</form>
                        </div>
                        <div class="body">
							<div style="margin-top:5px">
								<div class="row">
									<div class="col-md-12" id="table">
									
									</div>
								</div>
							</div>
                        </div>
                    </div>
                </div>
            </div>
			
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
			<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
			<script src="mapping.js"></script>
            <!-- #END# Advanced Form Example With Validation -->
        </div>
    </section>