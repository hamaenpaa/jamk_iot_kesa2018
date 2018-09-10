<?php
	if (!isset($_SESSION)) {
		session_start();	
	} 
	if (!isset($_SESSION['user_id'])) {
		return;	
	}

    include("../../db_connect_inc.php");
	include("../../utils/request_param_utils.php");
	
	$lesson_id = get_post_or_get($conn,'lesson_id');
	if (!is_integerable($lesson_id) || $lesson_id == "" || $lesson_id == "0") {
		include("../../db_disconnect_inc.php");
		return;
	}	
	
	$topic_id = get_post_or_get($conn,'topic_id');
	if (!is_integerable($topic_id) || $topic_id == "" || $topic_id == "0") {
		include("../../db_disconnect_inc.php");
		return;
	}	
	
	$already_exists = false;
	$sql_check_if_already_exists = "SELECT COUNT(*) FROM ca_lesson_topic WHERE topic_id=?
	    and lesson_id = ?";
		
	$c = 0;
	$already_exists = false;
	$ret_val = "{}";
	$q_check = $conn->prepare($sql_check_if_already_exists);
	if ($q_check) {
		$q_check->bind_param("ii", $topic_id, $lesson_id);
		$q_check->execute();
		$q_check->store_result();
		$q_check->bind_result($c);
		$q_check->fetch();
		if ($c > 0) {
			$already_exists = true;
			$ret_val = "{'error' : 'already exists'}";
		}
	}	
	
	if (!$already_exists) {
		echo "!already_exists -> add\n";
		$sql_add_lesson_topic = 
			"INSERT INTO ca_lesson_topic (topic_id, lesson_id) VALUES (?,?)"; 
		$q = $conn->prepare($sql_add_lesson_topic);
		if ($q) {
			$q->bind_param("ii", $topic_id, $lesson_id);
			$q->execute();		
		}
	}
    include("../../db_disconnect_inc.php");
	
	echo $ret_val;
?>