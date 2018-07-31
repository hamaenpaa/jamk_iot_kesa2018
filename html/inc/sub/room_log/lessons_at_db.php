<?php
	function get_current_time_lessons_for_course_or_room($conn, $course, $room) {
		$sql_get_current_time_lessons = 
			"SELECT ca_lesson.ID, ca_lesson.begin_time, ca_lesson.end_time,
			 ca_lesson.course_id, ca_lesson.room_id FROM
			 ca_lesson WHERE ca_lesson.begin_time <= NOW() AND ca_lesson.end_time >= NOW() ";
		if (isset($course) && $course != null && $course != "") {
			$sql_get_current_time_lessons .= " AND ca_lesson.course_id = ?";
		}
		if (isset($room) && $room != null && $room != "") {
			$sql_get_current_time_lessons .= " AND ca_lesson.room = ?";
		}		

		$q_current_time_lessons = $conn->prepare($sql_get_current_time_lessons);
		if (isset($course) && $course != null && $course != "" && 
			isset($room) && $room != null && $room != "") {
			$q_current_time_lessons->bind_param("ii", $course, $room);
		}
		else if (isset($course) && $course != null && $course != "") {
			$q_current_time_lessons->bind_param("i", $course);
		}
		else if (isset($room) && $room != null && $room != "") {
			$q_current_time_lessons->bind_param("i", $room);
		}
		
		$q_current_time_lessons->execute();		
		$q_current_time_lessons->store_result();
		$q_current_time_lessons->bind_result($lesson_id, $begin_time, 
			$end_time, $course_id, $room_id);
		
		$room_ids = array();
		$course_ids = array();
		$lessons = array();
		if ($q_current_time_lessons->num_rows > 0) {
			while($row = $q_current_time_lessons->fetch()) {
				if (!in_array($room_id, $room_ids)) {
					$room_ids[] = $room_id;
				}
				if (!in_array($course_id, $course_ids)) {
					$course_ids[] = $course_id;
				}				
				$lessons[] = array(
					"lesson_id" => $lesson_id,
					"begin_time" => $begin_time, "end_time" => $end_time,
					"course_id" => $course_id, "room_id" => $room_id);
			}
		}
		
		// find the previous lessons on same rooms and fill in the lessons array
		
		// 2 or more courses at same room at same time for exceptional arrangement?
		// 2 or more rooms for same course at same time for exceptional arrangement?
		
		
		
		return $lessons;
		
	}

?>