<?php
	function get_room_log($conn,
		$begin_time, $end_time, $room_seek, $nfc_id_seek) {
			
		$sql_room_logs_total = 
			"SELECT ca_roomlog.ID, ca_roomlog.NFC_ID, 
				ca_roomlog.dt, ca_roomlog.room_identifier, ca_lesson.topic
              FROM ca_roomlog 
				LEFT JOIN ca_lesson ON 
					DATE(ca_lesson.begin_time) = DATE(ca_roomlog.dt) AND 
					ca_lesson.room_identifier = ca_roomlog.room_identifier				
			  WHERE ca_roomlog.dt >= ? AND ca_roomlog.dt <= ? 
			  AND ca_roomlog.room_identifier LIKE '%" .$room_seek ."%'
			  AND ca_roomlog.NFC_ID LIKE '%" . $nfc_id_seek ."%' ORDER BY NFC_ID ASC, dt DESC";
		$q_room_logs = $conn->prepare($sql_room_logs_total);
		$q_room_logs->bind_param("ss", $begin_time, $end_time);
		$q_room_logs->execute();		
		$q_room_logs->store_result();		
		$q_room_logs->bind_result($room_log_id, $nfc_id, $dt, $room_identifier, $topic);		
		
		$room_log_arr = array();
		if ($q_room_logs->num_rows > 0) {
			while($room_logs = $q_room_logs->fetch()) {
				$room_log_arr[] = array("room_log_id " => $room_log_id,
					"dt" => $dt, 
					"nfc_id" => $nfc_id, 
					"room_identifier" => $room_identifier,
					"topic" => $topic);
			}
		}
		return $room_log_arr;
	}
?>