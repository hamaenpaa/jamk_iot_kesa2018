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

	$page_add_lesson = get_post_or_get($conn, "page_add_lesson");
	$page_add_lesson_count = get_post_or_get($conn, "page_add_lesson_count");
	$page_add_lesson_page_size = get_post_or_get($conn, "page_add_lesson_page_size");
	$page_add_lesson_page = get_post_or_get($conn, "page_add_lesson_page");
	$lesson_count = get_post_or_get($conn, "lesson_count");
	
	$seek_params_get = possible_get_param("name_seek",$name_seek, false);
	$seek_params_get .= possible_get_param("description_seek",$description_seek, false);		
	$seek_params_get .= possible_get_param("topic_seek",$topic_seek, false);
	$seek_params_get .= possible_get_param("lesson_add_begin_time_seek",$lesson_add_begin_time_seek, false);
	$seek_params_get .= possible_get_param("lesson_add_end_time_seek",$lesson_add_end_time_seek, false);
	$seek_params_get .= possible_get_param("lesson_add_room_seek",$lesson_add_room_seek, false);
	$seek_params_get .= possible_get_param("lesson_add_topic_seek",$lesson_add_topic_seek, false);
	$seek_params_get .= possible_get_param("page",$page, false);
	$seek_params_get .= possible_get_param("page_page", $page_page, false);	
	
	
	// if all fits inside one page less and on the last page,
	// page is decremented and o
	if ($lesson_count - 1 <= ($page_add_lesson_count - 1) * $page_add_lesson_page_size && 
		$page_add_lesson == $page_add_lesson_count) {
		$page_add_lesson--;
		// if page is decremented and it was last page on page page,
		// also page page must be decremented
		if ($page_add_lesson_count % $page_add_lesson_page_size == 1) {
			$page_add_lesson_page--;
		}
	} 	
	
	$seek_params_get .= possible_get_param("page_lesson_add",$page_add_lesson, false);
	$seek_params_get .= possible_get_param("page_lesson_add_page", $page_add_lesson_page, false);		
	
	$sql_set_course_id = "UPDATE ca_lesson SET course_id = ? WHERE id = ?";
	$q = $conn->prepare($sql_set_course_id);
	if ($q) {
		$q->bind_param("ii", $course_id, $lesson_id);
		$q->execute();		
	}
    include("../../db_disconnect_inc.php");
	
	header("Location: ../../../index.php?screen=2&id=".$course_id.$seek_params_get);
?>