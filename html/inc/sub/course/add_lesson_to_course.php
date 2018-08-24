<?php
    include("../../db_connect_inc.php");
	include("../../utils/request_param_utils.php");
	
	$course_id = intval(get_post_or_get($conn, 'course_id'));	
	$lesson_id = intval(get_post_or_get($conn, 'lesson_id'));
	
	$sql_set_course_id = "UPDATE ca_lesson SET course_id = ? WHERE id = ?";
	$q = $conn->prepare($sql_set_course_id);
	if ($q) {
		$q->bind_param("ii", $course_id, $lesson_id);
		$q->execute();		
	}
    include("../../db_disconnect_inc.php");
	
	echo "{}";
?>