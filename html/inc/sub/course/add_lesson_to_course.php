<?php
    include("../../db_connect_inc.php");
	include("../../utils/request_param_utils.php");
	
	$course_id = intval($_POST['course_id']);	
	$lesson_id = intval($_POST['lesson_id']);
	
    $name_seek = get_post_or_get($conn, "name_seek");
	$description_seek = get_post_or_get($conn, "description_seek");	
	$seek_params_get = possible_get_param("name_seek",$name_seek, false);
	$seek_params_get .= possible_get_param("description_seek",$description_seek, false);		

	$sql_set_course_id = "UPDATE ca_lesson SET course_id = ? WHERE id = ?";
	$q = $conn->prepare($sql_set_course_id);
	if ($q) {
		$q->bind_param("ii", $course_id, $lesson_id);
		$q->execute();		
	}
    include("../../db_disconnect_inc.php");
	
	header("Location: ../../../index.php?screen=2&id=".$course_id.$seek_params_get);
?>