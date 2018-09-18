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
	if (!is_integerable($id)) {
		include("../../db_disconnect_inc.php");
		return;
	}	
	
	$lesson_date = date_from_ui_to_db(strip_tags($_GET['lesson_date']));
	$begin_time = strip_tags($_GET['begin_time']) . ":00";
	$end_time = strip_tags($_GET['end_time']). ":00";
	$begin_date_time = $lesson_date . " " . $begin_time;
	$end_date_time = $lesson_date . " " . $end_time;
	
	if (!isDateTime($begin_date_time) || !isDateTime($end_date_time) ||
		!isTime1Before($_GET['begin_time'], $_GET['end_time'])) {
		include("../../db_disconnect_inc.php");
		return;		
	}
	
	$room_identifier = strip_tags($_GET['room_identifier']);
	if (mb_strlen($room_identifier) > 50 || mb_strlen($room_identifier) < 1) {
		include("../../db_disconnect_inc.php");
		return;				
	}
	
	// check if same day contains another lesson with same room identifier
	$sql_check_overlapping_lesson =
	     "SELECT COUNT(*) FROM ca_lesson WHERE 
		  DATE(ca_lesson.begin_time) = DATE(?) AND ca_lesson.room_identifier = ? AND ca_lesson.removed = 0";
	if ($id != "") {
		$sql_check_overlapping_lesson .= " AND ca_lesson.id <> ?";
	}
	$q_check = $conn->prepare($sql_check_overlapping_lesson);
	if ($id != "") {
		$q_check->bind_param("ssi", $begin_date_time, $room_identifier, $id);
	} else {
		$q_check->bind_param("ss", $begin_date_time, $room_identifier);
	}
	$q_check->execute();		
	$q_check->store_result();
	$q_check->bind_result($count_overlapping);
	$q_check->fetch();
	if ($count_overlapping > 0) {
		$overlapping = "1";
	} else {
		$overlapping = "0";
	}
	if ($overlapping == "0") {
		if ($id != "") {
			$q = $conn->prepare("UPDATE ca_lesson SET 
				begin_time = ?, end_time = ?, room_identifier = ? WHERE ID = ?");
			if ($q) {
				$q->bind_param("sssi", $begin_date_time, $end_date_time, $room_identifier, $id);
				$q->execute();
			}
		} else {
			$q = $conn->prepare(
				"INSERT INTO 
					ca_lesson (begin_time, end_time, room_identifier) 
					VALUES (?,?,?)");
			if ($q) {
				$q->bind_param("sss", $begin_date_time, $end_date_time, $room_identifier);
				$q->execute();
			}		
		}
	}
	include("../../db_disconnect_inc.php");
	echo json_encode(array("overlapping" => $overlapping));
?>