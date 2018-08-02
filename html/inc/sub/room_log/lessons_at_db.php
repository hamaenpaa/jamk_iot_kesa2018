<?php
	function get_teacher_courses_for_now($conn, $staff_id) {
		$sql_teacher_courses_for_now = 
			"SELECT ca_course.ID FROM ca_course, ca_course_teacher, ca_lesson
			 WHERE ca_course.ID = ca_course_teacher.course_id AND
			 ca_course_teacher.staff_id = ? AND
			 ca_lesson.course_id = ca_course.ID AND 
			 ca_lesson.begin_time <= NOW() AND ca_lesson.end_time >= NOW() 
			 ORDER BY ca_lesson.begin_time DESC";
		$q_teacher_courses_for_now = $conn->prepare($sql_teacher_courses_for_now);
		$q_teacher_courses_for_now->bind_param("i", $staff_id);
		$q_teacher_courses_for_now->execute();		
		$q_teacher_courses_for_now->store_result();		
		$q_teacher_courses_for_now->bind_result($course_id);
		$course_ids = array();
		
		while($row = $q_teacher_courses_for_now->fetch()) {
			$course_ids[] = $course_id;
		}
		return $course_ids;
	}


	function get_current_time_lessons_for_course_or_room($conn, $course, $room) {
		$sql_get_current_time_lessons = 
			"SELECT ca_lesson.ID, ca_lesson.begin_time, ca_lesson.end_time,
			 ca_lesson.course_id, ca_lesson.room_id FROM
			 ca_lesson WHERE ca_lesson.begin_time <= NOW() AND ca_lesson.end_time >= NOW() ";
		if (isset($course) && $course != null && $course != "") {
			$sql_get_current_time_lessons .= " AND ca_lesson.course_id = ?";
		}
		if (isset($room) && $room != null && $room != "") {
			$sql_get_current_time_lessons .= " AND ca_lesson.room_id = ?";
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
		$earliest_begin_time_at_now = null;
		if ($q_current_time_lessons->num_rows > 0) {
			while($row = $q_current_time_lessons->fetch()) {
				if (!in_array($room_id, $room_ids)) {
					$room_ids[] = $room_id;
				}
				if (!in_array($course_id, $course_ids)) {
					$course_ids[] = $course_id;
				}				
				if ($earliest_begin_time_at_now == null) {
					$earliest_begin_time_at_now = from_db_to_unix_milliseconds($begin_time);
				} else {
					$comparison_time = from_db_to_unix_milliseconds($begin_time);
					if ($comparison_time < $earliest_begin_time_at_now) {
						$earliest_begin_time_at_now = $comparison_time;
					}
				}
				$lessons[] = array(
					"lesson_id" => $lesson_id,
					"begin_time" => $begin_time, "end_time" => $end_time,
					"course_id" => $course_id, "room_id" => $room_id);
			}
		}
		
		// find the previous lessons on same rooms and fill in the lessons array
		
		// 2 or more courses at same room at same time for exceptional arrangement:
		// * simultaneous training/education; teachers training at many subjects
		// * students studying different things at same room
		
		// 2 or more rooms for same course at same time for exceptional arrangement:
		// * video teaching: students sitting at different rooms of school or remotely through
		//   for example Skype etc. and still belonging to same course teaching.

		$db_earliest_begin_time_at_now = from_ui_to_db($earliest_begin_time_at_now);
		
		$sql_previous_lessons = "SELECT end_time FROM ca_lesson WHERE end_time < ?";
		if (isset($course) && $course != null && $course != "") {
			$sql_previous_lessons .= " AND ca_lesson.course_id = ?";
		}
		if (isset($room) && $room != null && $room != "") {
			$sql_previous_lessons .= " AND ca_lesson.room = ?";
		}	
		$q_previous_lessons = $conn->prepare($sql_previous_lessons);
		if (isset($course) && $course != null && $course != "" && 
			isset($room) && $room != null && $room != "") {
			$q_previous_lessons->bind_param("sii", $db_earliest_begin_time_at_now, $course, $room);
		}
		else if (isset($course) && $course != null && $course != "") {
			$q_previous_lessons->bind_param("si", $db_earliest_begin_time_at_now, $course);
		}
		else if (isset($room) && $room != null && $room != "") {
			$q_previous_lessons->bind_param("si", $db_earliest_begin_time_at_now, $room);
		}		
		$q_previous_lessons->bind_result($end_time);		
		$latest_previous_end_time = null;
		if ($q_previous_lessons->num_rows > 0) {
			while($row = $q_previous_lessons->fetch()) {
				if ($latest_previous_end_time == null) {
					$latest_previous_end_time = from_db_to_unix_milliseconds($end_time);
				} else {
					$comparison_time = from_db_to_unix_milliseconds($end_time);
					if ($comparison_time > $latest_previous_end_time) {
						$latest_previous_end_time = $comparison_time;
					}					
				}
			}
		}
		
		if ($latest_previous_end_time != null) {
			$lessons['begin_time'] = from_unix_time_to_ui($latest_previous_end_time);
		} else {
			$lessons['begin_time'] = get_db_time_of_school_day_begin();
		}
		
		return $lessons;
	}
?>