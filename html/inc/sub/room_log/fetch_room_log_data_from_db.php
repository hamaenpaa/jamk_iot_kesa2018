<?php
	define("ROOM_LOG_PAGE_SIZE","50");
	
	function get_room_log($conn,
		$begin_time, $end_time, $room_seek, $nfc_id_seek, $topic_seek,
		$course_name_seek, $page, $last_new_fetch_time) {

		if (!isDateTime($begin_time) || !isDateTime($end_time) ||
			!isDatetime1Before($begin_time, $end_time)) {
			return array();			
		}		
		if (strlen($room_seek) > 50 || strlen($nfc_id_seek) > 50 ||
			strlen($topic_seek) > 150  || strlen($course_name_seek) > 50) {
			return array();
		}
		if (!is_integerable($page) || $page == "" || $page == "0") {
			return array();
		}	
		if ($last_new_fetch_time != "" && !is_integerable($last_new_fetch_time)) {
			return array();
		}
		
		$fields = "ca_roomlog.ID, ca_roomlog.NFC_ID, 
				ca_roomlog.dt, ca_roomlog.room_identifier, ca_lesson.topic,
				ca_course.name ";
		$date_part = " ca_roomlog.dt >= ? AND ca_roomlog.dt <= ? ";
		$tables_and_conditions_begin = "FROM ca_roomlog 
				LEFT JOIN ca_lesson ON 
					DATE(ca_lesson.begin_time) = DATE(ca_roomlog.dt) AND 
					ca_lesson.room_identifier = ca_roomlog.room_identifier	
					AND ca_lesson.topic LIKE '%" . $topic_seek . "%'					
					AND ca_lesson.removed = 0
			    LEFT JOIN 
					ca_course ON ca_course.id = ca_lesson.course_id
					AND ca_course.name LIKE '%" . $course_name_seek . "%'
					AND ca_course.removed = 0 
			  WHERE ";
		$tables_and_conditions_end = "
			  AND ca_roomlog.room_identifier LIKE '%" .$room_seek ."%'
			  AND ca_roomlog.NFC_ID LIKE '%" . $nfc_id_seek ."%' 
			  ORDER BY NFC_ID ASC, dt DESC";
		$usual_tables_and_conditions = $tables_and_conditions_begin . $date_part .
		    $tables_and_conditions_end;
		$sql_room_logs_total = "SELECT " . $fields . $usual_tables_and_conditions;
		if ($page != -1) {
			$sql_room_logs_total .= " LIMIT " . (($page - 1) * ROOM_LOG_PAGE_SIZE) . "," . ROOM_LOG_PAGE_SIZE;
		}
		$sql_count = "SELECT COUNT(*) " . $usual_tables_and_conditions;
		$sql_new = "SELECT COUNT(*) " . 
			$tables_and_conditions_begin . "ca_roomlog.dt > ?" .
		    $tables_and_conditions_end;
	
		$q_room_logs = $conn->prepare($sql_room_logs_total);
		$q_room_logs->bind_param("ss", $begin_time, $end_time);
		$q_room_logs->execute();		
		$q_room_logs->store_result();		
		$q_room_logs->bind_result($room_log_id, $nfc_id, $dt, 
			$room_identifier, $topic, $course_name);

		$q_room_logs_count = $conn->prepare($sql_count);
		$q_room_logs_count->bind_param("ss", $begin_time, $end_time);
		$q_room_logs_count->execute();		
		$q_room_logs_count->store_result();
		$q_room_logs_count->bind_result($count);
		$q_room_logs_count->fetch();
	
		$count_new = "0";
		if ($last_new_fetch_time != "") {
			$q_room_logs_count_new = $conn->prepare($sql_new);
			$q_room_logs_count_new->bind_param("s", $last_new_fetch_time);
			$q_room_logs_count_new->execute();		
			$q_room_logs_count_new->store_result();
			$q_room_logs_count_new->bind_result($count_new);
			$q_room_logs_count_new->fetch();
		}
	
		$topic = str_replace(" ", "&nbsp;", $topic);
		if ($topic == "") { $topic = "%nbsp;"; }
		$room_identifier = str_replace(" ", "&nbsp;", $room_identifier);
		if ($room_identifier == "") { $room_identifier = "%nbsp;"; }	
		$course_name = str_replace(" ", "&nbsp;", $course_name);
		if ($course_name == "") { $course_name = "%nbsp;"; }
		
		$room_log_arr = array();
		if ($q_room_logs->num_rows > 0) {
			while($room_logs = $q_room_logs->fetch()) {
				$room_log_arr[] = array("room_log_id " => $room_log_id,
					"dt" => str_replace(" ", "&nbsp;", from_db_to_ui($dt)), 
					"nfc_id" => str_replace(" ", "&nbsp;", $nfc_id), 
					"room_identifier" => $room_identifier,
					"topic" => $topic,
					"course_name" => $course_name);
			}
		}
		
		$page_count = intdiv($count, ROOM_LOG_PAGE_SIZE);
		if ($page_count * ROOM_LOG_PAGE_SIZE < $count) { $page_count++; }			
		
		$ret_arr = array("count" => $count, "page_count" => $page_count,
			"count_new" => $count_new, 
			"room_logs" => $room_log_arr);
			
		return $ret_arr;
	}
?>