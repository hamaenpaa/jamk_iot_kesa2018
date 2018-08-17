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
	
	$seek_params_get = possible_get_param("name_seek",$name_seek, false);
	$seek_params_get .= possible_get_param("description_seek",$description_seek, false);
	$seek_params_get .= possible_get_param("topic_seek",$topic_seek, false);
	$seek_params_get .= possible_get_param("lesson_add_begin_time_seek",$lesson_add_begin_time_seek, false);
	$seek_params_get .= possible_get_param("lesson_add_end_time_seek",$lesson_add_end_time_seek, false);
	$seek_params_get .= possible_get_param("lesson_add_room_seek",$lesson_add_room_seek, false);
	$seek_params_get .= possible_get_param("lesson_add_topic_seek",$lesson_add_topic_seek, false);

	$q = $conn->prepare("UPDATE ca_course SET removed = 1 WHERE ID = ?");
	if ($q) {
		$q->bind_param("i", $id);
		$q->execute();
	}

	include("../../db_disconnect_inc.php");
	header("Location: ../../../index.php?screen=2".$seek_params_get);
?>