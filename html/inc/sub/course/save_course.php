<?php
    include("../../db_connect_inc.php");
	include("../../utils/request_param_utils.php");
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
	
	$seek_params_get = possible_get_param("seek_course_ID",$seek_course_id, true);
	$seek_params_get .= possible_get_param("seek_course_name",$seek_course_name, $seek_params_get == "");	
	$seek_params_get .= possible_get_param("page", $page, $seek_params_get == "");
	$seek_params_get .= possible_get_param("course_student_page",$course_student_page, false);
	
	$id = "";
	if (isset($_POST['id'])) {
		$id = $_POST['id'];
	}
	$course_id = strip_tags($_POST['course_id']);
	$course_name = strip_tags($_POST['course_name']);
	$course_description= strip_tags($_POST['course_description']);

	if (strlen($course_id) > 20 || strlen($course_name) > 50 ||
		strlen($course_description) > 500 || 
		strlen($seek_course_id) > 20 || strlen($seek_course_name) > 25) {
		header("Location: ../../../list_courses.php" . $seek_params_get);
		exit;
	}
	
	if ($id != "") {
		$q = $conn->prepare("UPDATE ca_course SET course_id = ?, course_name = ?, course_description = ? WHERE ID = ?");
		if ($q) {
			$q->bind_param("sssi", $course_id, $course_name, $course_description, $id);
			$q->execute();
		}
	} else {
		$q = $conn->prepare("INSERT INTO ca_course (course_id,course_name,course_description) VALUES (?,?,?)");
		if ($q) {
			$q->bind_param("sss", $course_id, $course_name, $course_description);
			$q->execute();
		}		
	}

	include("../../db_disconnect_inc.php");
	
	header("Location: ../../../list_courses.php" . $seek_params_get);
?>