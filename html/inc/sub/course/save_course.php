<?php
    include("../../db_connect_inc.php");
	include("../../utils/request_param_utils.php");
	
	$id = "";
	if (isset($_POST['id']) || isset($_GET['id'])) {
		$id = get_post_or_get($conn, 'id');
	}
	$name = strip_tags($_POST['name']);
	$description = strip_tags($_POST['description']);
	
	$name_seek = get_post_or_get($conn, "name_seek");
	$description_seek = get_post_or_get($conn, "description_seek");
	$topic_seek = get_post_or_get($conn, "topic_seek");
	$lesson_add_begin_time_seek = get_post_or_get($conn, "lesson_add_begin_time_seek");
	$lesson_add_end_time_seek = get_post_or_get($conn, "lesson_add_end_time_seek");
	$lesson_add_room_seek = get_post_or_get($conn, "lesson_add_room_seek");
	$lesson_add_topic_seek = get_post_or_get($conn, "lesson_add_topic_seek");	
	$page = get_post_or_get($conn, "page");
	$page_page = get_post_or_get($conn, "page_page");		
	
	$seek_params_get = possible_get_param("name_seek", $name_seek, false);
	$seek_params_get .= possible_get_param("description_seek", $description_seek, false);
	$seek_params_get .= possible_get_param("topic_seek",$topic_seek, false);
	$seek_params_get .= possible_get_param("lesson_add_begin_time_seek",$lesson_add_begin_time_seek, false);
	$seek_params_get .= possible_get_param("lesson_add_end_time_seek",$lesson_add_end_time_seek, false);
	$seek_params_get .= possible_get_param("lesson_add_room_seek",$lesson_add_room_seek, false);
	$seek_params_get .= possible_get_param("lesson_add_topic_seek",$lesson_add_topic_seek, false);
	$seek_params_get .= possible_get_param("page",$page, false);
	$seek_params_get .= possible_get_param("page_page", $page_page, false);	
	
	if ($id != "") {
		$q = $conn->prepare("UPDATE ca_course SET 
			name = ?, description = ? WHERE ID = ?");
		if ($q) {
			$q->bind_param("ssi", $name, $description, $id);
			$q->execute();
		}
	} else {
		$q = $conn->prepare(
			"INSERT INTO 
				ca_course (name, description) 
				VALUES (?,?)");
		if ($q) {
			$q->bind_param("ss", $name, $description);
			$q->execute();
		}		
	}

	include("../../db_disconnect_inc.php");
	header("Location: ../../../index.php?screen=2".$seek_params_get);
?>