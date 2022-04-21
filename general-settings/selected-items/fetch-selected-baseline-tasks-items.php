<?php 
include_once "controller.php";
function get_location($db, $disaggregated, $locationid){
    if($disaggregated == 1){
      $query_rs = 'SELECT disaggregations FROM  tbl_indicator_level3_disaggregations  WHERE id =:location'; 
      $stmt_rs = $db->prepare($query_rs);
      $stmt_rs->execute(array(":location" => $locationid)); 
      $row_rs = $stmt_rs->fetch();  
      $location_name = $row_rs['disaggregations'];
    }else{
      $query_rs = 'SELECT s.state FROM tbl_indicator_baseline_survey_details o  INNER JOIN tbl_state s ON s.id =o.level3  WHERE o.level3 =:stid'; 
      $stmt_rs = $db->prepare($query_rs);
      $stmt_rs->execute(array(":stid" => $locationid)); 
      $row_rs = $stmt_rs->fetch();
      $row_count_rs = $stmt_rs->rowCount();
      $location_name = $row_rs['state'];         
    } 
    return $location_name;
}

function confirm_data($db,$baseformid,$level3, $location, $userid){

  $query = 'SELECT * FROM tbl_indicator_baseline_values WHERE form_id=:baseformid AND level3=:level3 AND location=:location AND respondent=:respondent '; 
  $stmt = $db->prepare($query); 
  $stmt->execute(array(":baseformid"=>$baseformid,":level3"=>$level3, ":location"=>$location, ":respondent"=>$userid));
  $result = $stmt->rowCount(); 
  $data =0;
  if($result > 0){
    $data = 1;
  } 
  return $data; 
}

function read($userid ) {
	Global $db;
    $query = 'SELECT  f.id as form_id, f.form_name,i.indicator_name, p.projname,p.projid, d.level3, d.location_disaggregation, f.form_type, f.type
    FROM tbl_indicator_baseline_survey_forms f
    INNER JOIN tbl_indicator_baseline_survey_details d ON f.id = d.formid  
    INNER JOIN tbl_projects p ON p.projid = f.projid
    INNER JOIN  tbl_indicator i ON i.indid = f.indid WHERE d.respondents =:userid';
    $stmt = $db->prepare($query);
    $stmt->execute(array(":userid"=>$userid));
    $num = $stmt->rowCount();
    $data =''; 
//echo $num;
    if($num > 0) { 
	//echo $userid;
          $base_arr = array();
          $base_arr['data'] = array();
           
          while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row); 
            $locationid ='';
            $disaggregated =0;

            if($location_disaggregation != NULL){
              $disaggregated =1;
              $locationid = $location_disaggregation;

            }else{ 
              $disaggregated =0;
              $locationid =0;
            } 
            $level3 = $level3;
            $surveyed = confirm_data($db, $form_id,$level3, $locationid, $userid);
            $location_name = get_location($db, $disaggregated, $locationid);
    
            $base_item = array(
              'formid' => $form_id,  
              'form_name' => $form_name,  
              'projname' => $projname,  
              'projid' => $projid,  
              'indicator_name'=>$indicator_name,
              'location'=>$location_name,
              'form_type'=>$form_type, 
              'type'=>$type, 
              'surveyed'=>$surveyed
              );  

            array_push($base_arr['data'], $base_item);
          } 
          $data = json_encode($base_arr);
    } else { 
          $data =  json_encode(
            array('message' => 'No Baseline value Found')
          );
    }
    return $data;
} 

if(isset($_GET['userid'])){
    $userid = $_GET['userid'];
	//echo $userid;
    echo read($userid);
}