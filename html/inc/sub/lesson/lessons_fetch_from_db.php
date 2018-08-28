<?php
	define("PAGE_SIZE", 50);

	function get_lessons($conn, $begin_time, $end_time, $room_seek, $topic_seek, $page) {
		if (!isDateTime($begin_time) || !isDateTime($end_time) ||
			!isDatetime1Before($begin_time, $end_time)) {
			return array();			
		}
		if (!is_integerable($page) || $page == "" || $page == "0") {
			return array();	
		}
		$room_seek = purifyParam($conn, $room_seek);
		$topic_seek = purifyParam($conn, $topic_seek);
		if (strlen($room_seek) > 50 || strlen($topic_seek) > 150) {
			return array();	
		}
		
		$total_fields = "ca_lesson.ID, ca_lesson.begin_time,
                ca_lesson.end_time, ca_lesson.room_identifier,			
				ca_lesson.topic ";
		$sql_end_without_page_def = "FROM ca_lesson WHERE 
			    ((? <= ca_lesson.begin_time AND ca_lesson.begin_time <= ?) OR
				 (? <= ca_lesson.end_time AND ca_lesson.end_time <= ?))
			  AND ca_lesson.room_identifier LIKE '%" .$room_seek ."%'
			  AND ca_lesson.topic LIKE '%" . $topic_seek . "%' 
			  AND ca_lesson.removed = 0 
			  ORDER BY begin_time DESC, room_identifier ASC";
		$sql_lessons = 
			"SELECT " .  $total_fields . $sql_end_without_page_def .
			 " LIMIT " . (($page - 1) * PAGE_SIZE) . "," . PAGE_SIZE;
			 
		$q_lessons = $conn->prepare($sql_lessons);
		$q_lessons->bind_param("ssss", $begin_time, $end_time, $begin_time, $end_time);
		$q_lessons->execute();		
		$q_lessons->store_result();
		$q_lessons->bind_result($lesson_id, $begin_time, $end_time, 
			$room_identifier, $topic);		

		$sql_lessons_count = "SELECT COUNT(*) " . $sql_end_without_page_def;
		
		$q_lessons_count = $conn->prepare($sql_lessons_count);
		$q_lessons_count->bind_param("ssss", $begin_time, $end_time, $begin_time, $end_time);	
		$q_lessons_count->execute();		
		$q_lessons_count->store_result();
		$q_lessons_count->bind_result($count);
		$q_lessons_count->fetch();
		
		$lessons = array();
		if ($q_lessons->num_rows > 0) {
			while($q_lessons->fetch()) {
				$lessons[] = array("lesson_id" => $lesson_id,
					"time_interval" => str_replace(" ", "&nbsp;",
						from_db_datetimes_to_same_day_date_plus_times(
							$begin_time, $end_time)),
					"room_identifier" => str_replace(" ", "&nbsp;", $room_identifier),
					"topic" => str_replace(" ", "&nbsp;", $topic));
			}
		}
		
		$page_count = intdiv($count, PAGE_SIZE);
		if ($page_count * PAGE_SIZE < $count) { $page_count++; }	
		$page_page_count = intdiv($page_count, PAGE_PAGE_SIZE);
		if ($page_page_count * PAGE_PAGE_SIZE < $page_count) { $page_page_count++; }
		
		if ($page_count == 0) { $page_count = 1; }
		if ($page_page_count == 0) { $page_page_count = 1; }
		
		$lessons_arr = array(
			"lessons" => $lessons, 
			"count" => $count,
			"page_count" => $page_count,
			"page_page_count" => $page_page_count);
		
		return $lessons_arr;		
	}
?>