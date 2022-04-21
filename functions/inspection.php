<?php

// get departments 
function get_inpectection_checklist_topics(){
    global $db;
    $query_topics = $db->prepare("SELECT * FROM tbl_inspection_checklist_topics WHERE active='1'");
	$query_topics->execute();
	$row_topics = $query_topics->fetchAll();
	$totalRows_topics = $query_topics->rowCount();

    if($totalRows_topics > 0){
        return $row_topics; 
    }else{
        return false;
    }
}

// get departments 
function get_checklist_topic($tp_id){
    global $db;
    $query_topic = $db->prepare("SELECT * FROM tbl_inspection_checklist_topics WHERE  id = $tp_id LIMIT 1");
	$query_topic->execute();
	$row_topic = $query_topic->fetch();
	$totalRows_topic = $query_topic->rowCount();

    if($totalRows_topic > 0){
        return $row_topic; 
    }else{
        return false;
    }
}

function get_checklists(){
    global $db;
    $query_rchecklist = $db->prepare("SELECT * FROM tbl_inspection_checklist  WHERE active='1'");
	$query_rchecklist->execute();
	$row_rchecklist = $query_rchecklist->fetchAll();
	$totalrows_rchecklist = $query_rchecklist->rowCount(); 
    if($totalrows_rchecklist > 0){
        return $row_rchecklist; 
    }else{
        return false;
    }
}

function get_checklist($cklstid){
    global $db;
    $query_checlist = $db->prepare("SELECT * FROM tbl_inspection_checklist WHERE  id = $cklstid LIMIT 1");
	$query_checlist->execute();
	$row_checlist = $query_checlist->fetch();
	$totalRows_checlist = $query_checlist->rowCount();

    if($totalRows_checlist > 0){
        return $row_checlist; 
    }else{
        return false;
    }
}

function fill_unit_select_box($tc_id = null){ 
    global $db;
    $topic = '';
    $query_alltopics = $db->prepare("SELECT id, topic FROM tbl_inspection_checklist_topics WHERE active='1'");
    $query_alltopics->execute();
    $rows_alltopics = $query_alltopics->fetchAll();
    foreach($rows_alltopics as $row){
        $selected = $tc_id == $row["id"]  ? "selected" : "";
        $topic .= '<option value="'.$row["id"].'" '.$selected.'>'.$row["topic"].'</option>';
    }
    return $topic;
}

function get_checklist_questions($cklstid){
    global $db;
    $query_rchecklist_questions = $db->prepare("SELECT * FROM tbl_inspection_checklist_questions  WHERE checklistname='$cklstid'");
	$query_rchecklist_questions->execute();
	$row_rchecklist_questions = $query_rchecklist_questions->fetchAll();
	$totalrows_rchecklist_questions = $query_rchecklist_questions->rowCount(); 
    if($totalrows_rchecklist_questions > 0){
        return $row_rchecklist_questions; 
    }else{
        return false;
    }
}