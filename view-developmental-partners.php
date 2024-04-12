<?php
try { 

$Id = 9;
$subId =30;
 $pageName ="Developmental Partners"; 
 require('includes/head.php');
 require('includes/header.php'); 

    $query_rsfinancier = $db->prepare("SELECT * FROM tbl_partners ORDER BY ptnid ASC");
    $query_rsfinancier->execute();	
    $row_rsfinancier = $query_rsfinancier->fetch();
    $totalRows_rsfinancier = $query_rsfinancier->rowCount(); 
 

?>

    <div class="body">
        <div class="row cleaerfix">
            <div class="col-md-12" style="margin-top:10px">
                <h5>
                    <a href="add-developmental-partner" class="btn btn-primary pull-right">Add Developmental Partner</a>
                </h5>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                <thead>
                    <tr id="colrow">
                    <th width="3%"><strong>SN</strong></th>
                    <th width="30%"><strong>Partner</strong></th> 
                    <th width="15%"><strong>Contact</strong></th>
                    <th width="9%"><strong>Phone</strong></th>
                    <th width="8%"><strong>Projects</strong></th> 
                    <th width="5%"><strong>Status</strong></th> <!--COLSPAN=4--> 
                    <th colspan="7%"><strong>Action</strong></th> <!--COLSPAN=4--> 
                    </tr>
                </thead>
                <tbody>
                    <!-- =========================================== -->
                    <?php
                    
                    if($totalRows_rsfinancier > 0){
                        $sn = 0;

                        do { 
                            $sn = $sn + 1;
                            $country = $row_rsfinancier['country'];
                            $fnid = $row_rsfinancier['ptnid'];
                            $hashfnid = base64_encode("fn918273AxZID{$fnid}");
                               
                            if($row_rsfinancier['active'] == 1){
                                $active = '<i class="fa fa-check-square" style="font-size:18px;color:green" title="Active"></i>';
                            } else{
                                $active = '<i class="fa fa-exclamation-triangle" style="font-size:18px;color:#bc2d10" title="Disabled"></i>';
                            } 
                            ?>
                            <tr style="border-bottom:thin solid #EEE">
                                <td><?php echo $sn; ?></td>
                                <td><?php echo $row_rsfinancier['partnername']; ?></td> 
                                <td><?php echo $row_rsfinancier['contact']; ?> (<?php echo $row_rsfinancier['designation']; ?>)</td>
                                <td><a href="tel:<?php echo $row_rsfinancier['phone'];?>"><?php echo $row_rsfinancier['phone']; ?></a></td>
                                <td align="center">
                                    <span class="badge bg-brown">
                                        <a href="view-financier-projects?fndid=<?php echo base64_encode($row_rsfinancier['id']); ?>" 
                                         style="font-family:Verdana, Geneva, sans-serif; color:white; font-size:12px; padding-top:0px">
                                            <?php  echo 0; ?>
                                        </a>
                                </td> 
                                <td align="center"><?=$active?></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"  onchange="checkBoxes()"  aria-haspopup="true" aria-expanded="false">
                                            Options <span class="caret"></span>
                                        </button> 
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a  type="button" href="manage-developmental-partner?fn=<?php echo $hashfnid; ?>"><i class="fa fa-plus-square"></i> Manage</a>
                                            </li>
                                            <li>
                                                <a type="button"  href="edit-developmental-partner?fn=<?php echo $hashfnid; ?>">
                                                    <i class="glyphicon glyphicon-edit"></i> Edit </a>
                                            </li>       
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php 
                        } while ($row_rsfinancier = $query_rsfinancier->fetch());
                    } 
                    ?>
                </tbody>
            </table>
        </div>
    </div>

<?php
    require('includes/footer.php');

} catch (PDOException $th) {
    customErrorHandler($th->getCode(), $th->getMessage(), $th->getFile(), $th->getLine());
}  
?>