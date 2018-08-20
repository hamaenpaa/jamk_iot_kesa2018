<?php
    include("../../db_connect_inc.php");
	include("../../utils/request_param_utils.php");
	
	$course_id = intval($_POST['course_id']);	
	$lesson_id = intval($_POST['lesson_id']);
	
    $name_seek = get_post_or_get($conn, "name_seek");
	$description_seek = get_post_or_get($conn, "description_seek");	
	$topic_seek = get_post_or_get($conn, "topic_seek");
	$lesson_add_begin_time_seek = get_post_or_get($conn, "lesson_add_begin_time_seek");
	$lesson_add_end_time_seek = get_post_or_get($conn, "lesson_add_end_time_seek");
	$lesson_add_room_seek = get_post_or_get($conn, "lesson_add_room_seek");
	$lesson_add_topic_seek = get_post_or_get($conn, "lesson_add_topic_seek");
	$page = get_post_or_get($conn, "page");
	$page_page = get_post_or_get($conn, "page_page");	
	
	$seek_params_get = possible_get_param("name_seek",$name_seek, false);
	$seek_params_get .= possible_get_param("description_seek",$description_seek, false);		
	$seek_params_get .= possible_get_param("topic_seek",$topic_seek, false);
	$seek_params_get .= possible_get_param("lesson_add_begin_time_seek",$lesson_add_begin_time_seek, false);
	$seek_params_get .= possible_get_param("lesson_add_end_time_seek",$lesson_add_end_time_seek, false);
	$seek_params_get .= possible_get_param("lesson_add_room_seek",$lesson_add_room_seek, false);
	$seek_params_get .= possible_get_param("lesson_add_topic_seek",$lesson_add_topic_seek, false);
	$seek_params_get .= possible_get_param("page",$page, false);
	$seek_params_get .= possible_get_param("page_page", $page_page, false);	
	
	/* adding lessons will remove possibly added lessons, so add lesson page
	   is not preserved here */
	
	$sql_set_course_id = "UPDATE ca_lesson SET course_id = ? WHERE id = ?";
	$q = $conn->prepare($sql_set_course_id);
	if ($q) {
		$q->bind_param("ii", $course_id, $lesson_id);
		$q->execute();		
	}
    include("../../db_disconnect_inc.php");
	
	header("Location: ../../../index.php?screen=2&id=".$course_id.$seek_params_get);
?>