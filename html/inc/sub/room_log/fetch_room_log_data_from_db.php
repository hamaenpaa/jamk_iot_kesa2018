<?php
	define("ROOM_LOG_PAGE_SIZE","50");
	
	function get_room_log($conn,
		$begin_time, $end_time, $room_seek, $nfc_id_seek, $topic_ids,
		$course_name_seek, $page, $last_new_fetch_time, $use_nbsp) {

		if (!isDateTime($begin_time) || !isDateTime($end_time) ||
			!isDatetime1Before($begin_time, $end_time)) {
			return array();			
		}		
		if (strlen($room_seek) > 50 || strlen($nfc_id_seek) > 50 ||
			strlen($course_name_seek) > 50) {
			return array();
		}
		if (!is_integerable($page) || $page == "" || $page == "0") {
			return array();
		}	
		if ($last_new_fetch_time != "" && !isDateTime($last_new_fetch_time)) {
			return array();
		}
		
		$settings = getSettings($conn);
		
		$fields = "ca_roomlog.ID, ca_roomlog.NFC_ID, 
				ca_roomlog.dt, ca_roomlog.room_identifier, ca_course.name, ca_lesson.id ";
		$date_part = " ca_roomlog.dt >= ? AND ca_roomlog.dt <= ? ";
		$topic_part  = "";
		if ($topic_ids != "") {
			$topic_part = 
				" AND EXISTS(SELECT id FROM ca_lesson_topic WHERE 
				ca_lesson_topic.lesson_id =	ca_lesson.id AND 
				ca_lesson_topic.topic_id IN (". $topic_ids ."))";
		}
		$tables_and_conditions_begin = "FROM ca_roomlog 
				LEFT JOIN ca_lesson ON 
					DATE(ca_lesson.begin_time) = DATE(ca_roomlog.dt) AND 
					ca_lesson.room_identifier = ca_roomlog.room_identifier ".	
					$topic_part	.				
					" AND ca_lesson.removed = 0
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
			$room_identifier, $course_name, $lesson_id);

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
		$room_log_arr = array();
		$lesson_ids = array();
		$lesson_topics_arr = array();
		$lesson_courses = array();
		if ($q_room_logs->num_rows > 0) {
			while($room_logs = $q_room_logs->fetch()) {
				if ($use_nbsp) {
					$room_identifier = str_replace(" ", "&nbsp;", $room_identifier);
					if ($room_identifier == "") { $room_identifier = "&nbsp;"; }
				}	
				if ($use_nbsp) {
					$course_name = str_replace(" ", "&nbsp;", $course_name);
					if ($course_name == "") { $course_name = "&nbsp;"; }
				}
				$dt = from_db_to_ui($dt);
				$topics_val = "";
				if ($use_nbsp) {
					$dt = str_replace(" ", "&nbsp;", $dt);
					$nfc_id = str_replace(" ", "&nbsp;", $nfc_id);
					$topics_val = "&nbsp;";
				}	
				$room_log_arr[] = array(
					"room_log_id " => $room_log_id,
					"dt" => $dt, 
					"nfc_id" => $nfc_id, 
					"room_identifier" => $room_identifier,
					"course_name" => $course_name,
					"lesson_id" => $lesson_id,
					"topics" => $topics_val);
				$lesson_courses[$lesson_id] = $course_name;
				if ($lesson_id != null && $lesson_id != "null" && $lesson_id != "") {
					if (!in_array($lesson_id, $lesson_ids)) {
						$lesson_ids[] = $lesson_id;
					}
					if ($use_nbsp) {
						$lesson_topics_arr[$lesson_id] = "&nbsp;";
					} else {
						$lesson_topics_arr[$lesson_id] = "";
					}
				}
			}
		}
		
		$lesson_topic_ids = array();
		if (count($lesson_ids) > 0) {
			$sql_lesson_topics = "SELECT ca_lesson.id, ca_topic.name, ca_topic.id 
				FROM ca_lesson, ca_topic, ca_lesson_topic 
				WHERE ca_lesson.id = ca_lesson_topic.lesson_id AND 
			      ca_lesson_topic.topic_id = ca_topic.id AND
				  ca_lesson.id IN (". implode(",", $lesson_ids) . ")";
			$q_lesson_topics = $conn->prepare($sql_lesson_topics);
			$q_lesson_topics->execute();		
			$q_lesson_topics->store_result();		
			$q_lesson_topics->bind_result($lesson_id, $topic_name, $topic_id);
			if (!in_array($topic_id, $lesson_topic_ids)) {
				$lesson_topic_ids[] = $topic_id;
			}
			while($lesson_topics = $q_lesson_topics->fetch()) {
				if ($lesson_id != "null" && $lesson_id != null && $lesson_id != "") {
					if ($use_nbsp) {
						$topic_name = str_replace(" ", "&nbsp;", $topic_name);
						if ($topic_name == "") { $topic_name = "&nbsp;"; }
					}			
					if ($lesson_topics_arr[$lesson_id] != "" && $lesson_topics_arr[$lesson_id] != "&nbsp;") {
						$lesson_topics_arr[$lesson_id] .= "," . $topic_name;
					} else {
						$lesson_topics_arr[$lesson_id] = $topic_name;
					}
				}
			}
			foreach($room_log_arr as $key => $room_log) {
				if ($room_log['lesson_id'] != null && $room_log['lesson_id'] != "" && 
					$room_log['lesson_id'] != "&nbsp;") {
					$topics_val = $lesson_topics_arr[$room_log['lesson_id']];
					if ($use_nbsp) {
						$topics_val = str_replace(" ", "&nbsp;", $topics_val);
						if ($topics_val == "") { $topics_val = "&nbsp;"; }
					}
					$room_log_arr[$key]["topics"] = $topics_val;
				} else {
					$topics_val = "";
					if ($use_nbsp) {
						$topics_val = $topics_val = "&nbsp;";
					}					
					$room_log_arr[$key]["topics"] = $topics_val;
				}
			}
		}
		
		$page_count = intdiv($count, ROOM_LOG_PAGE_SIZE);
		if ($page_count * ROOM_LOG_PAGE_SIZE < $count) { $page_count++; }			
		$ret_arr = array("count" => $count, "page_count" => $page_count,
			"count_new" => $count_new, 
			"room_logs" => $room_log_arr);	
		if ($settings['usage_type'] == 1) {
			$ret_arr["NFC_ID_topics_and_lessons"] = 
				getSummaryForUsageType1(
					$conn, 
					$begin_time, $end_time, $room_seek, $nfc_id_seek,
					$lesson_courses,
					$use_nbsp);
		} else {
			$ret_arr["NFC_ID_monthly_counts"] = 
				getSummaryForUsageType2(
					$conn, $usual_tables_and_conditions, 
					$begin_time, $end_time);			
		}
		return $ret_arr;
	}
	
	
	function getSummaryForUsageType1(
		$conn, $begin_time, $end_time, $room_seek, $nfc_id_seek,
		$lesson_courses, $use_nbsp) {
		$arr_NFC_ID_topics_and_lessons = array();
		$sql_NFC_ID_topics_and_lessons = 
			"SELECT 
				ca_roomlog.NFC_ID, 
				ca_lesson.id, ca_lesson.begin_time, ca_lesson.end_time, 
				ca_lesson.room_identifier,
				ca_topic.id, ca_topic.name
			FROM 
				ca_roomlog, ca_lesson, ca_lesson_topic, ca_topic
			WHERE 
				DATE(ca_lesson.begin_time) = DATE(ca_roomlog.dt) AND 
				ca_lesson.room_identifier = ca_roomlog.room_identifier
				AND ca_lesson.removed = 0
				AND ca_lesson.id = ca_lesson_topic.lesson_id
				AND ca_lesson_topic.topic_id = ca_topic.id 
				AND ca_topic.removed = 0
				AND ca_roomlog.dt >= ? AND ca_roomlog.dt <= ? 
				AND ca_roomlog.room_identifier LIKE '%" .$room_seek ."%'
				AND ca_roomlog.NFC_ID LIKE '%" . $nfc_id_seek ."%'";
				
		$q_NFC_ID_topics_and_lessons = $conn->prepare($sql_NFC_ID_topics_and_lessons);
		$q_NFC_ID_topics_and_lessons->bind_param("ss", $begin_time, $end_time);	
		$q_NFC_ID_topics_and_lessons->execute();		
		$q_NFC_ID_topics_and_lessons->store_result();		
		$q_NFC_ID_topics_and_lessons->bind_result(
			$nfc_id, 
			$lesson_id,	$lesson_begin_time, $lesson_end_time, $room_identifier,
			$topic_id, $topic_name);		
		
//		echo "lesson_courses:\n";
//		var_dump($lesson_courses);
		
		$last_nfc_id_key = 0;
		while($NFC_ID_topics_and_lessons = $q_NFC_ID_topics_and_lessons->fetch()) {
			$update_nfc_id_item_key = -1;
			foreach($arr_NFC_ID_topics_and_lessons as $nfc_id_item_key => $nfc_id_item) {
				if ($nfc_id_item['nfc_id'] == $nfc_id) {
					$update_nfc_id_item = $nfc_id_item;
					$update_nfc_id_item_key = $nfc_id_item_key;
					break;
				}
			}
			if ($update_nfc_id_item_key == -1) {
				$update_nfc_id_item = array("nfc_id" => $nfc_id, "topics" => array());
				$update_nfc_id_item_key = $last_nfc_id_key;
				$last_nfc_id_key++;
			}
			$update_topic_item_key = -1;
			foreach($update_nfc_id_item['topics'] as $topic_key => $topic_item) {
				if ($topic_item['topic_id'] == $topic_id) {
					$update_topic_item_key = $topic_key;
					$update_topic_item = $topic_item;
					break;
				}
			}
			if ($update_topic_item_key == -1) {
				$update_topic_item_key = count($update_nfc_id_item['topics']);
				$update_topic_item = array("topic_id" => $topic_id,
											"topic_name" => $topic_name,
											"lessons" => array());
			}
			$update_lesson_item_key = -1;
			foreach($update_topic_item['lessons'] as $lesson_key => $lesson_item) {
				if ($lesson_item['lesson_id'] == $lesson_id) {
					$update_lesson_item_key = $lesson_key;
					$update_lesson_item = $lesson_item;
					break;
				}
			}
			if ($update_lesson_item_key == -1) {
				$update_lesson_item_key = count($update_topic_item['lessons']);
				$time_interval = 
					from_db_datetimes_to_same_day_date_plus_times(
						$begin_time, $end_time);
				if ($use_nbsp) {
					$time_interval = str_replace(" ", "&nbsp;", $time_interval);
				}
				if (array_key_exists($lesson_id, $lesson_courses)) {
					$course = $lesson_courses[$lesson_id];
				} else {
					if ($use_nbsp) {
						$course = "&nbsp;";
					} else {
						$course = "";
					}
				}			
				$update_lesson_item = array(
					"lesson_id" => $lesson_id,
					"course" => $course,
					"room_identifier" => $room_identifier,
					"time_interval" => $time_interval);
			}
			$update_topic_item['lessons'][$update_lesson_item_key] = $update_lesson_item;
			$update_nfc_id_item['topics'][$update_topic_item_key] = $update_topic_item;
			$arr_NFC_ID_topics_and_lessons[$update_nfc_id_item_key] = $update_nfc_id_item;
		}		
		return $arr_NFC_ID_topics_and_lessons;
	}
	
	function getSummaryForUsageType2($conn, 
		$usual_tables_and_conditions,
		$begin_time, $end_time) {
			
		$sql_roomlog_summary = "SELECT NFC_ID, dt " . $usual_tables_and_conditions;
		$q_room_logs_summary = $conn->prepare($sql_roomlog_summary);
		$q_room_logs_summary->bind_param("ss", $begin_time, $end_time);
		$q_room_logs_summary->execute();		
		$q_room_logs_summary->store_result();		
		$q_room_logs_summary->bind_result($nfc_id, $dt);	
		$room_log_summary_arr = array();
		while($room_logs_summary = $q_room_logs_summary->fetch()) {
			$room_log_summary_arr[] = array("nfc_id" => $nfc_id, "dt" => $dt);	
		}
		$begin_time_parts = get_date_parts_from_db_datetime($begin_time);
		$end_time_parts = get_date_parts_from_db_datetime($end_time);
		
		$i_begin_year = intval($begin_time_parts['year']);
		$i_end_year = intval($end_time_parts['year']);
		$i_begin_month = intval($begin_time_parts['month']);
		$i_end_month = intval($end_time_parts['month']);	
		
		$nfc_ids_at_months = array();
		$nfc_ids_at_years = array();
		foreach($room_log_summary_arr as $room_log_summary) {
			$dt_parts = get_date_parts_from_db_datetime($room_log_summary['dt']);
			$month_key = $dt_parts['month'];
			if (strlen($month_key) == 1) {
				$month_key = "0" . $month_key;
			}
			$year_month_key = $dt_parts['year'] . " " . $month_key;
			if (!array_key_exists($year_month_key, $nfc_ids_at_months)) {
				$nfc_ids_at_months[$year_month_key] = array($room_log_summary['nfc_id']);
			} else {
				if (!in_array($room_log_summary['nfc_id'], $nfc_ids_at_months[$year_month_key])) {
					$nfc_ids_at_months[$year_month_key][] = $room_log_summary['nfc_id'];
				}
			}
			if (!array_key_exists($dt_parts['year'], $nfc_ids_at_years)) {
				$nfc_ids_at_years[$dt_parts['year']] = array($room_log_summary['nfc_id']);
			} else {
				if (!in_array($room_log_summary['nfc_id'], $nfc_ids_at_years[$dt_parts['year']])) {
					$nfc_ids_at_years[$dt_parts['year']][] = $room_log_summary['nfc_id'];
				}			
			}
		}
		
		$monthly_nfc_id_counts = array();
		for($i_year = $i_begin_year; $i_year <= $i_end_year; $i_year++) {
			if ($i_year == $i_begin_year && $i_begin_year != $i_end_year) {
				$year_stuff =
					get_counts_for_year($nfc_ids_at_months, $i_year, $i_begin_month, 12);
			} else if ($i_year == $i_end_year && $i_begin_year != $i_end_year) {
				$year_stuff =
					get_counts_for_year($nfc_ids_at_months, $i_year, 1, $i_end_month);				
			} else if ($i_begin_year == $i_end_year) {
				$year_stuff =
					get_counts_for_year($nfc_ids_at_months, $i_year, $i_begin_month, $i_end_month);					
			} else { // full year
				$year_stuff =
					get_counts_for_year($nfc_ids_at_months, $i_year, 1, 12);							
			}
			$monthly_nfc_id_counts[] = $year_stuff;		
		}
		$yearly_nfc_id_counts = array();
		for($i_year = $i_begin_year; $i_year <= $i_end_year; $i_year++) {
			$yearly_nfc_id_counts[] = 
				array(
					"year" => $i_year, 
					"count" => count($nfc_ids_at_years[$i_year]));
		}
		
		return array("year_counts" => $yearly_nfc_id_counts, "month_counts" => $monthly_nfc_id_counts);
	}
	
	function get_counts_for_year($nfc_ids_at_months, $year, $begin_month, $end_month) {
		$year_arr = array("year" => $year, "months" => array());
		$year_total = 0;
		for($i_month = $begin_month; $i_month <= $end_month; $i_month++) {
			$c_month = $i_month;
			if (strlen($c_month) == 1) { $c_month = "0" . $c_month; }
			$year_month_key = $year . " " . $c_month;
			if (array_key_exists($year_month_key, $nfc_ids_at_months)) {
				$year_arr['months'][$i_month] = count($nfc_ids_at_months[$year_month_key]);
			} else {
				$year_arr['months'][$i_month] = 0;
			}
		}
		return $year_arr;
	}
?>