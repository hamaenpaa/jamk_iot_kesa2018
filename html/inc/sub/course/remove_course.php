<?php
    include("../../db_connect_inc.php");
	include("../../utils/request_param_utils.php");
	
	$id = get_post_or_get($conn, "id");
    $seek_course_id = get_post_or_get($conn, "seek_course_ID");
	$seek_course_name = get_post_or_get($conn, "seek_course_name");
	
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
	if (isset($_POST['course_teacher_page'])) {
		$course_teacher_page = strip_tags($_POST['course_teacher_page']);
	} else {
		$course_teacher_page = 1;
	}	
	
	$seek_params_get = possible_get_param("seek_course_ID",$seek_course_id, true);
	$seek_params_get .= possible_get_param("seek_course_name",$seek_course_name, $seek_params_get == "");
	$seek_params_get .= possible_get_param("page", $page, $seek_params_get == "");
	$seek_params_get .= possible_get_param("course_student_page", $page, $course_student_page == "");
	$seek_params_get .= possible_get_param("course_teacher_page", $page, $course_teacher_page == "");
	
	$q = $conn->prepare("UPDATE ca_course SET removed=1 WHERE ID = ?");
	if ($q) {
		$q->bind_param("i", $id);
		$q->execute();
	}	

	include("../../db_disconnect_inc.php");
	header("Location: ../../../list_courses.php" . $seek_params_get);
?>