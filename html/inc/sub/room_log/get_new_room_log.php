<?php
include("../../db_connect_inc.php");
include("../../utils/request_param_utils.php");
include("../../utils/date_utils.php");

$seek_room = get_post_or_get($conn, "seek_room");
$seek_nfc_id = get_post_or_get($conn, "seek_nfc_id");
$seek_course_name = get_post_or_get($conn, "seek_course_name");
$seek_topic = get_post_or_get($conn, "seek_topic");

$last_fetch_time = from_unix_time_to_db(
	intval(get_post_or_get($conn, "last_fetch_time")));

if (!isset($seek_room)) {
	$seek_room = "";
}
if (!isset($seek_nfc_id)) {
	$seek_nfc_id = "";
}	
	
if (isset($last_fetch_time)) {
	$sql_room_logs_total = 
		"SELECT ca_roomlog.ID, ca_roomlog.NFC_ID, 
				ca_roomlog.dt, ca_roomlog.room_identifier,
				ca_lesson.topic, ca_course.name
         FROM ca_roomlog 
		 LEFT JOIN ca_lesson ON 
			DATE(ca_lesson.begin_time) = DATE(ca_roomlog.dt) AND 
			ca_lesson.room_identifier = ca_roomlog.room_identifier	
			AND ca_lesson.topic LIKE '%" . $seek_topic . "%'					
			AND ca_lesson.removed = 0			
		 LEFT JOIN ca_course ON ca_course.id = ca_lesson.course_id
			  AND ca_course.name LIKE '%" . $seek_course_name . "%'
			  AND ca_course.removed = 0 		 
		 WHERE 
			ca_roomlog.dt > ?  
			  AND ca_roomlog.room_identifier LIKE '%" .$seek_room ."%'
			  AND ca_roomlog.NFC_ID LIKE '%" . $seek_nfc_id ."%'
			  ORDER BY NFC_ID ASC, dt DESC";
	if ($res_getRL = $conn->prepare($sql_room_logs_total)) {
		$res_getRL->bind_param("s", $last_fetch_time);
		$res_getRL->execute();		
		$res_getRL->store_result();			 
		if ($res_getRL->execute()) {
			$res_getRL->store_result();
			$res_getRL_rows = $res_getRL->num_rows();
			if ($res_getRL_rows > 0) {
				$res_getRL->bind_result(
					$roomlogID,$NFC_ID,$roomlogdt,$room_identifier,$topic,$course_name);
				$new_rows = array();
				while($res_getRL->fetch()) {
					$new_rows[] = array( 
						"NFC_ID" => $NFC_ID,
						"roomlog_id" => $roomlogID,
						"roomlog_dt" => from_db_to_ui($roomlogdt),
						"room_identifier" => $room_identifier,
						"topic" => $topic,
						"course_name" => $course_name
					);
				}
				$new_rows['count'] = $res_getRL_rows;
				$new_rows['last_fetch_time'] = time();
				echo json_encode($new_rows);
			} else {
				echo "{}";
			}		
		} else {
			//ERROR->$res_getRL->EXECUTE
		}
	} else {
		//ERROR->$res_getRL->PREPARE	
	}
}

include("../../db_disconnect_inc.php");
?>