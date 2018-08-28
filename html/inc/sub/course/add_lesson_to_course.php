<?php
	if (!isset($_SESSION)) {
		session_start();	
	} 
	if (!isset($_SESSION['user_id'])) {
		return;	
	}

    include("../../db_connect_inc.php");
	include("../../utils/request_param_utils.php");
	
	$course_id = get_post_or_get($conn, 'course_id');	
	$lesson_id = get_post_or_get($conn, 'lesson_id');
	
	if (!is_integerable($course_id) || $course_id == "" || $course_id == "0") {
		include("../../db_disconnect_inc.php");
		return;
	}			
	if (!is_integerable($lesson_id) || $lesson_id == "" || $lesson_id == "0") {
		include("../../db_disconnect_inc.php");
		return;
	}	
	
	$sql_set_course_id = "UPDATE ca_lesson SET course_id = ? WHERE id = ?";
	$q = $conn->prepare($sql_set_course_id);
	if ($q) {
		$q->bind_param("ii", $course_id, $lesson_id);
		$q->execute();		
	}
    include("../../db_disconnect_inc.php");
	
	echo "{}";
?>