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
	
	$sql_delete_lesson_topic = "DELETE FROM ca_lesson_topic WHERE 
		ca_lesson_topic.topic_id = ? AND ca_lesson_topic.lesson_id = ?";
	$q = $conn->prepare($sql_delete_lesson_topic);
	if ($q) {
		$q->bind_param("ii", $topic_id, $lesson_id);
		$q->execute();		
	}
    include("../../db_disconnect_inc.php");
	
	echo "{}";
?>