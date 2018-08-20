<?php
    include("../../db_connect_inc.php");
	include("../../utils/date_utils.php");
	include("../../utils/request_param_utils.php");
	$id = "";
	if (isset($_POST['id']) || isset($_GET['id'])) {
		$id = get_post_or_get($conn, 'id');
	}
	$lesson_date = date_from_ui_to_db(strip_tags($_POST['lesson_date']));
	$begin_time = strip_tags($_POST['begin_time']) . ":00";
	$end_time = strip_tags($_POST['end_time']). ":00";
	$begin_date_time = $lesson_date . " " . $begin_time;
	$end_date_time = $lesson_date . " " . $end_time;
	$topic = strip_tags($_POST['topic']);
	$room_identifier = strip_tags($_POST['room_identifier']);
	
	$begin_time_seek = get_post_or_get($conn, "begin_time_seek");
	$end_time_seek = get_post_or_get($conn, "end_time_seek");
	$room_seek = get_post_or_get($conn, "room_seek");
	$topic_seek = get_post_or_get($conn, "topic_seek");
	$page = get_post_or_get($conn, "page");
	$page_page = get_post_or_get($conn, "page_page");	
	
	$seek_params_get = possible_get_param("begin_time_seek", $begin_time_seek, false);
	$seek_params_get .= possible_get_param("end_time_seek", $end_time_seek, false);
	$seek_params_get .= possible_get_param("room_seek", $room_seek, false);		
	$seek_params_get .= possible_get_param("topic_seek", $topic_seek, false);	
	$seek_params_get .= possible_get_param("page",$page, false);
	$seek_params_get .= possible_get_param("page_page", $page_page, false);		
	
/* if (strlen($room_name) > 40) {
		header("Location: ../../../list_rooms.php".$seek_params_get);
		exit;
	}	
	if (strlen($seek_room_name) > 40) {
		header("Location: ../../../list_rooms.php".$seek_params_get);
		exit;
	}
*/	
	if ($id != "") {
		$q = $conn->prepare("UPDATE ca_lesson SET 
			begin_time = ?, end_time = ?, room_identifier = ?, topic=? WHERE ID = ?");
		if ($q) {
			$q->bind_param("ssssi", $begin_date_time, $end_date_time, $room_identifier, $topic, $id);
			$q->execute();
		}
	} else {
		$q = $conn->prepare(
			"INSERT INTO 
				ca_lesson (begin_time, end_time, room_identifier, topic) 
				VALUES (?,?,?,?)");
		if ($q) {
			$q->bind_param("ssss", $begin_date_time, $end_date_time, $room_identifier, $topic);
			$q->execute();
		}		
	}

	include("../../db_disconnect_inc.php");
	
	header("Location: ../../../index.php?screen=1".$seek_params_get);
?>