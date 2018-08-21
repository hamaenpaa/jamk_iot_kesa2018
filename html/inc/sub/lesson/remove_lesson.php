<?php
    include("../../db_connect_inc.php");
	include("../../utils/request_param_utils.php");
	
	$id = get_post_or_get($conn, "id");
	
    $begin_time_seek = get_post_or_get($conn, "begin_time_seek");
	$end_time_seek = get_post_or_get($conn, "end_time_seek");
	$room_seek = get_post_or_get($conn, "room_seek");
	$topic_seek = get_post_or_get($conn, "topic_seek");
	$page = get_post_or_get($conn, "page");
	$page_page = get_post_or_get($conn, "page_page");	
	$page_size = get_post_or_get($conn, "page_size");
	$page_count = get_post_or_get($conn, "page_count");
	$page_page_size = get_post_or_get($conn, "page_page_size");
	$lesson_count = get_post_or_get($conn, "lesson_count");
	
	$seek_params_get = possible_get_param("begin_time_seek",$begin_time_seek, false);
	$seek_params_get .= possible_get_param("end_time_seek",$end_time_seek, false);
	$seek_params_get .= possible_get_param("room_seek",$room_seek, false);
	$seek_params_get .= possible_get_param("topic_seek", $topic_seek, false);
	
	// if all fits inside one page less and on the last page,
	// page is decremented and o
	if ($lesson_count - 1 <= ($page_count - 1) * $page_size && 
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

	$q = $conn->prepare("UPDATE ca_lesson SET removed = 1 WHERE ID = ?");
	if ($q) {
		$q->bind_param("i", $id);
		$q->execute();
	}

	include("../../db_disconnect_inc.php");
	header("Location: ../../../index.php?screen=1".$seek_params_get);
?>