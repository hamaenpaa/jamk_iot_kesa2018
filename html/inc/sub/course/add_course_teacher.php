<?php
    include("../../db_connect_inc.php");
	include("../../utils/request_param_utils.php");
	
	$staff_id = $_POST['staff_id'];
	$course_id = $_POST['course_id'];	
	
    $seek_course_id = get_post_or_get($conn, "seek_course_ID");
	$seek_course_name = get_post_or_get($conn, "seek_course_name");	
	$seek_params_get = possible_get_param("seek_course_ID",$seek_course_id, false);
	$seek_params_get .= possible_get_param("seek_course_name",$seek_course_name, false);		
	
	if (isset($_POST['page'])) {
		$page = strip_tags($_POST['page']);
	} else {
		$page = 1;
	}	
	if (isset($_POST['course_student_page'])) {
		$course_student_page = strip_tags($_POST['course_student_page']);
	} else {
		$course_student_page = 1;
	}	
	$seek_params_get .= possible_get_param("page",$page, false);
	$seek_params_get .= possible_get_param("course_student_page",$course_student_page, false);	
	
	$q = $conn->prepare("INSERT INTO ca_course_teacher (staff_id,course_id) VALUES (?,?)");
	if ($q) {
		$q->bind_param("ii", $staff_id, $course_id);
		$q->execute();
	}	

    include("../../db_disconnect_inc.php");
	
	header("Location: ../../../list_courses.php?id=".$course_id."&add_teacher=1".$seek_params_get);
?>