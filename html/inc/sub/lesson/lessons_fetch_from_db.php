<?php
	function get_lessons($conn, $begin_time, $end_time, $room_seek, $topic_seek) {
		$sql_lessons = 
			"SELECT ca_lesson.ID, ca_lesson.begin_time,
                ca_lesson.end_time, ca_lesson.room_identifier,			
				ca_lesson.topic 
              FROM ca_lesson WHERE 
			    ((? <= ca_lesson.begin_time AND ca_lesson.begin_time <= ?) OR
				 (? <= ca_lesson.end_time AND ca_lesson.end_time <= ?))
			  AND ca_lesson.room_identifier LIKE '%" .$room_seek ."%'
			  AND ca_lesson.topic LIKE '%" . $topic_seek .
			  "%' ORDER BY begin_time DESC, room_identifier ASC";
		$q_lessons = $conn->prepare($sql_lessons);
		$begin_time = from_ui_to_db($begin_time);
		$end_time = from_ui_to_db($end_time);
		echo $sql_lessons;
		echo "begin_time" . $begin_time;
		echo "end_time" . $end_time;
		$q_lessons->bind_param("ssss", $begin_time, $end_time, $begin_time, $end_time);
		$q_lessons->execute();		
		$q_lessons->store_result();
		$q_lessons->bind_result($lesson_id, $begin_time, $end_time, 
			$room_identifier, $topic);			
		
		$lessons_arr = array();
		if ($q_lessons->num_rows > 0) {
			while($lessons = $q_lessons->fetch()) {
				$lessons_arr[] = array("lesson_id " => $lesson_id,
					"begin_time" => $begin_time, "end_time" => $end_time,
					"room_identifier" => $room_identifier,
					"topic" => $topic);
			}
		}
		return $lessons_arr;		
	}
?>