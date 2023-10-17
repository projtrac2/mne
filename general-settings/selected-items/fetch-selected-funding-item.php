<?php


include_once "controller.php"; 
$itemId = $_POST['itemId'];
$query_item = $db->prepare("SELECT s.financier AS financier, fund_code, y.year AS fyear, amount, c.code AS ccode, c.currency AS currency, exchange_rate, date_funds_released, funds_purpose, grant_life_span, grant_installments, grant_installment_date, t.id AS tid, t.type AS ttype FROM tbl_funds f inner join tbl_financiers s on s.id=f.funder inner join tbl_fiscal_year y on y.id=f.financial_year inner join tbl_currency c on c.id=f.currency inner join tbl_funding_type t ON t.id=s.type WHERE f.id = '$itemId'");
$query_item->execute();
$row_item = $query_item->fetch();
$rows_count = $query_item->rowCount();
$input = '';

if ($rows_count > 0) {
    $financier = $row_item['financier'];
    $fundcode = $row_item['fund_code'];
    $fyear = $row_item['fyear'];
    $fundingamount = $row_item['amount'];
    $currencycode = $row_item['ccode'];
    $currency = $row_item['currency'];
    $exchangerate = $row_item['exchange_rate'];
    $datefundsreleased = date("d M Y", strtotime($row_item['date_funds_released']));
    $fundspurpose = $row_item['funds_purpose'];
    $grantlifespan = $row_item['grant_life_span'];
    $grantinstallments = $row_item['grant_installments'];
    $grantinstallmentdate = $row_item['grant_installment_date'];
    $fundtypeid = $row_item['tid'];
    $fundtype = $row_item['ttype'];
    $fundingamountlocal = $fundingamount * $exchangerate;
	
	if($fundtypeid ==3 || $fundtypeid ==4){
		$query_prg = $db->prepare("SELECT progname FROM tbl_programs WHERE progid = '$fundspurpose'");
		$query_prg->execute();
		$row_prg = $query_prg->fetch();
		$fundspurpose = $row_prg["progname"];
	}
    
    $view = '
	<div class="row clearfix">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card"> 
				<div class="header">
					<div class=" clearfix" style="margin-top:5px; margin-bottom:5px">
						<div class="row"> 
							<div class="col-md-4"><h5><strong><font color="#9C27B0"> Funding Type: </font></strong>'. $fundtype . '</h5></div>
							<div class="col-md-8"><h5><strong><font color="#9C27B0"> Financier: </font></strong>'. $financier . '</h5> </div>
						</div> 
					</div>  
				</div>
				<div class="body" style="margin-top:5px; margin-bottom:5px">
					<div class="row"> 
						<div class="col-md-3" id="">
							<label for="" id="" class="control-label">Funds Code:</label>
							<div class="form-input">
								<input type="text" value="' . $fundcode . '" class="form-control" disabled readonly>
							</div>
						</div> 
						<div class="col-md-3" id="">
							<label for="" id="" class="control-label">Financial Year:</label>
							<div class="form-input">
								<input type="text" value="' . $fyear . '" class="form-control" disabled readonly>
							</div>
						</div> 
						<div class="col-md-3" id="">
							<label for="" id="" class="control-label">Funding Amount:</label>
							<div class="form-input">
								<input type="text" value="' . number_format($fundingamount, 2) . '" class="form-control" disabled readonly>
							</div>
						</div> 
						<div class="col-md-3" id="">
							<label for="" id="" class="control-label">Funding Currency:</label>
							<div class="form-input">
								<input type="text" value="' . $currency . '" class="form-control" disabled readonly>
							</div>
						</div>
					</div> 
					<div class="row"> 
						<div class="col-md-3" id="">
							<label for="" id="" class="control-label">Exchange Rate:</label>
							<div class="form-input">
								<input type="text" value="' . $exchangerate . '" class="form-control" disabled readonly>
							</div>
						</div> 
						<div class="col-md-6" id="">
							<label for="" id="" class="control-label">Funds Purpose:</label>
							<div class="form-input">
								<input type="text" value="' . $fundspurpose . '" class="form-control" disabled readonly>
							</div>
						</div>
						<div class="col-md-3" id="">
							<label for="" id="" class="control-label">Date Funds Released:</label>
							<div class="form-input">
								<input type="text" value="' . $datefundsreleased . '" class="form-control" disabled readonly>
							</div>
						</div> 
					</div>';
					if($fundtypeid ==3){
						$view .= '
						<div class="row"> 
							<div class="col-md-4" id="">
								<label for="" id="" class="control-label">Grant Life Span:</label>
								<div class="form-input">
									<input type="text" value="' . $grantlifespan . ' Years" class="form-control" disabled readonly>
								</div>
							</div> 
							<div class="col-md-4" id="">
								<label for="" id="" class="control-label">Grant Installments:</label>
								<div class="form-input">
									<input type="text" value="' . $grantinstallments . '" class="form-control" disabled readonly>
								</div>
							</div>
							<div class="col-md-4" id="">
								<label for="" id="" class="control-label">Grant Installment Date:</label>
								<div class="form-input">
									<input type="text" value="' . $grantinstallmentdate . '" class="form-control" disabled readonly>
								</div>
							</div>
						</div>';
					}
					$view .= ' 
				</div>
			</div>
		</div>
	</div>
	
	<div class="row clearfix">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card" style="margin-bottom:-20px">
				<div class="header table-responsive">
					<i class="ti-link"></i>FILES & DOCUMENTS
					<table class="table table-bordered" id="donation_table">
						<thead>
							<tr>
								<th style="width:2%">#</th>
								<th style="width:45%">File Name</th>
								<th style="width:45%">Attachment Purpose</th>
								<th style="width:8%">Action</th>
							</tr>
						</thead>
						<tbody>';
							$counter = 0;
							$fcategory = "Funding";
							$query_rsFile = $db->prepare("SELECT * FROM tbl_files WHERE fcategory=:cat and projstage=:projstage");
							$query_rsFile->execute(array(":cat" => $fcategory, ":projstage" => $itemId));
							$row_rsFile = $query_rsFile->fetch();
							$totalRows_rsFile = $query_rsFile->rowCount();
							
							if ($totalRows_rsFile > 0) {
								do {
									$flid = $row_rsFile['fid'];
									$fname = $row_rsFile['filename'];
									$type = $row_rsFile['ftype'];
									$filepath = $row_rsFile['floc'];
									$attachmentPurpose = $row_rsFile['reason'];
									$act =  '<a href="'.$filepath.'" style="color:#06C; padding-left:2px; padding-right:2px; font-weight:bold" title="Download File" target="new"><i class="fa fa-cloud-download fa-2x" aria-hidden="true"></i></a>';
										
									$counter++;
									$view .= '<tr>
										<td>
										  ' . $counter . '
										</td>
										<td>
										' . $fname . '
										</td>
										<td> 
										' . $attachmentPurpose . '  
										</td>
										<td align="center"> 
										' . $act . '  
										</td>
									</tr>';
								} while ($row_rsFile = $query_rsFile->fetch());
							} else {
								$view .= '<tr>
										<td colspan="4">
											No Files Found!!
										</td>
									</tr>';
							}
						$view .= '</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>';

    echo $view;
}
