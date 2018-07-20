<?php
    include("../../db_connect_inc.php");
	include("../../utils/request_param_utils.php");
	
	$student_id = $_POST['student_id'];
	$course_id = $_POST['course_id'];	
	
    $seek_course_id = get_post_or_get($conn, "seek_course_ID");
	$seek_course_name = get_post_or_get($conn, "seek_course_name");	
	$seek_params_get = possible_get_param("seek_course_ID",$seek_course_id, false);
	$seek_params_get .= possible_get_param("seek_course_name",$seek_course_name, false);	
	
	$q = $conn->prepare("INSERT INTO ca_course_student (student_id,course_id) VALUES (?,?)");
	if ($q) {
		$q->bind_param("ii", $student_id, $course_id);
		$q->execute();
	}	

    include("../../db_disconnect_inc.php");
	
	header("Location: ../../../list_courses.php?id=".$course_id."&add_student=1".$seek_params_get);
?>