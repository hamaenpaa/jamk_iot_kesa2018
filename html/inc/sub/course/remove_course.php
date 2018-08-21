<?php
    include("../../db_connect_inc.php");
	include("../../utils/request_param_utils.php");
	
	$id = get_post_or_get($conn, "id");
	
    $name_seek = get_post_or_get($conn, "name_seek");
	$description_seek = get_post_or_get($conn, "description_seek");
	$topic_seek = get_post_or_get($conn, "topic_seek");
	$lesson_add_begin_time_seek = get_post_or_get($conn, "lesson_add_begin_time_seek");
	$lesson_add_end_time_seek = get_post_or_get($conn, "lesson_add_end_time_seek");
	$lesson_add_room_seek = get_post_or_get($conn, "lesson_add_room_seek");
	$lesson_add_topic_seek = get_post_or_get($conn, "lesson_add_topic_seek");	
	$page = get_post_or_get($conn, "page");
	$page_page = get_post_or_get($conn, "page_page");	
	$page_size = get_post_or_get($conn, "page_size");
	$page_count = get_post_or_get($conn, "page_count");
	$page_page_size = get_post_or_get($conn, "page_page_size");
	$course_count = get_post_or_get($conn, "course_count");	
	
	$seek_params_get = possible_get_param("name_seek",$name_seek, false);
	$seek_params_get .= possible_get_param("description_seek",$description_seek, false);
	$seek_params_get .= possible_get_param("topic_seek",$topic_seek, false);
	$seek_params_get .= possible_get_param("lesson_add_begin_time_seek",$lesson_add_begin_time_seek, false);
	$seek_params_get .= possible_get_param("lesson_add_end_time_seek",$lesson_add_end_time_seek, false);
	$seek_params_get .= possible_get_param("lesson_add_room_seek",$lesson_add_room_seek, false);
	$seek_params_get .= possible_get_param("lesson_add_topic_seek",$lesson_add_topic_seek, false);

	// if all fits inside one page less and on the last page,
	// page is decremented
	if ($course_count - 1 <= ($page_count - 1) * $page_size && 
		$page == $page_count) {
		$page--;
		// if page is decremented and it was last page on page page,
		// also page page must be decremented
		if ($page_count % $page_page_size == 1) {
			$page_page--;
		}
	} 
	
	$seek_params_get .= possible_get_param("page", $page, false);
	$seek_params_get .= possible_get_param("page_page", $page_page, false);	
	
	$q = $conn->prepare("UPDATE ca_course SET removed = 1 WHERE ID = ?");
	if ($q) {
		$q->bind_param("i", $id);
		$q->execute();
	}

	include("../../db_disconnect_inc.php");
	header("Location: ../../../index.php?screen=2".$seek_params_get);
?>