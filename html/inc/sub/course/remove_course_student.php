<?php
    include("../../db_connect_inc.php");
	include("../../utils/request_param_utils.php");
    $seek_course_id = get_post_or_get($conn, "seek_course_ID");
	$seek_course_name = get_post_or_get($conn, "seek_course_name");
    $course_id = get_post_or_get($conn, "course_id");
	$student_id = get_post_or_get($conn, "student_id");	
	
	$seek_params_get = possible_get_param("seek_course_ID",$seek_course_id, false);
	$seek_params_get .= possible_get_param("seek_course_name",$seek_course_name, false);	

	$q = $conn->prepare("DELETE FROM ca_course_student WHERE student_id = ? AND course_id = ?");
	if ($q) {
		$q->bind_param("ii", $student_id, $course_id);
		$q->execute();
	}		

	include("../../db_disconnect_inc.php");
	
	header("Location: ../../../list_courses.php?id=" . $course_id . $seek_params_get);
?>