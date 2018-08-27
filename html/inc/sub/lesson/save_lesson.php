<?php
	if (!isset($_SESSION)) {
		session_start();	
	} 
	if (!isset($_SESSION['user_id'])) {
		return;	
	}

    include("../../db_connect_inc.php");
	include("../../utils/date_utils.php");
	include("../../utils/request_param_utils.php");
	$id = "";
	if (isset($_GET['id'])) {
		$id = get_post_or_get($conn, 'id');
	}
	
	$lesson_date = date_from_ui_to_db(strip_tags($_GET['lesson_date']));
	$begin_time = strip_tags($_GET['begin_time']) . ":00";
	$end_time = strip_tags($_GET['end_time']). ":00";
	$begin_date_time = $lesson_date . " " . $begin_time;
	$end_date_time = $lesson_date . " " . $end_time;
	$topic = strip_tags($_GET['topic']);
	$room_identifier = strip_tags($_GET['room_identifier']);

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
	echo "{}";
?>