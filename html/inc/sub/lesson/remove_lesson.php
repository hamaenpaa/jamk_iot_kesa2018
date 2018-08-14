<?php
    include("../../db_connect_inc.php");
	include("../../utils/request_param_utils.php");
	
	$id = get_post_or_get($conn, "id");
	
    $begin_time_seek = get_post_or_get($conn, "begin_time_seek");
	$end_time_seek = get_post_or_get($conn, "end_time_seek");
	$room_seek = get_post_or_get($conn, "room_seek");
	$topic_seek = get_post_or_get($conn, "topic_seek");
	
	$seek_params_get = possible_get_param("begin_time_seek",$begin_time_seek, false);
	$seek_params_get .= possible_get_param("end_time_seek",$end_time_seek, false);
	$seek_params_get .= possible_get_param("room_seek",$room_seek, false);
	$seek_params_get .= possible_get_param("topic_seek", $topic_seek, false);

	$q = $conn->prepare("UPDATE ca_lesson SET removed = 1 WHERE ID = ?");
	if ($q) {
		$q->bind_param("i", $id);
		$q->execute();
	}

	include("../../db_disconnect_inc.php");
	header("Location: ../../../index.php?screen=1".$seek_params_get);
?>