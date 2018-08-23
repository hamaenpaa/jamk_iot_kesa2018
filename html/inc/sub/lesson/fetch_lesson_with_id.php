<?php
    include("../../db_connect_inc.php");
	include("../../utils/date_utils.php");
	include("../../utils/request_param_utils.php");
	
	$id = get_post_or_get($conn, "id");
	
	$total_fields = "ca_lesson.begin_time,
        ca_lesson.end_time, ca_lesson.room_identifier,			
		ca_lesson.topic ";	
	$sql_lesson = "SELECT " . $total_fields . " FROM ca_lesson WHERE id=?";
	$q_lesson = $conn->prepare($sql_lesson);
	$q_lesson->bind_param("i", $id);
	$q_lesson->execute();		
	$q_lesson->store_result();
	$q_lesson->bind_result($begin_time, $end_time, 
		$room_identifier, $topic);	
	$lesson = array();
	if ($q_lesson->fetch()) {
		$begin_time_parts = from_db_to_separate_date_and_time_ui($begin_time);
		$end_time_parts = from_db_to_separate_date_and_time_ui($end_time);
		$begin_time = from_db_to_ui($begin_time);
		$end_time = from_db_to_ui($end_time);		
		$lesson = 
			array("date" => $begin_time_parts['date_part'],
				  "begin_time" => $begin_time_parts['time_part'],
				  "end_time" => $end_time_parts['time_part'],
  				  "room_identifier" => $room_identifier,
				  "topic" => $topic);
	}
	include("../../db_disconnect_inc.php");
	echo json_encode($lesson);
?>