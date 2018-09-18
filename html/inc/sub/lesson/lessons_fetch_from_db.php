<?php
	function get_lessons($conn, $begin_time, $end_time, $room_seek, $topic_ids, $page) {
		if (!isDateTime($begin_time) || !isDateTime($end_time) ||
			!isDatetime1Before($begin_time, $end_time)) {
			return array();			
		}
		if (!is_integerable($page) || $page == "" || $page == "0") {
			return array();	
		}
		$room_seek = purifyParam($conn, $room_seek);
		if (mb_strlen($room_seek) > 50) {
			return array();	
		}
		
		list($page_size, $page_page_size) =
			get_page_and_page_page_sizes($conn);	
		
		$total_fields = "ca_lesson.ID, ca_lesson.begin_time,
                ca_lesson.end_time, ca_lesson.room_identifier ";
		$topic_part  = "";
		if ($topic_ids != "") {
			$topic_part = " AND 
				EXISTS(SELECT id FROM ca_lesson_topic WHERE 
				ca_lesson_topic.lesson_id =	ca_lesson.id AND 
				ca_lesson_topic.topic_id IN (". $topic_ids ."))";
		}
				
		$sql_end_without_page_def = "FROM ca_lesson WHERE 
			    ((? <= ca_lesson.begin_time AND ca_lesson.begin_time <= ?) OR
				 (? <= ca_lesson.end_time AND ca_lesson.end_time <= ?))
			  AND ca_lesson.room_identifier LIKE '%" .$room_seek ."%' ".
			  $topic_part .
			  " AND ca_lesson.removed = 0 
			  ORDER BY begin_time DESC, room_identifier ASC";
		$sql_lessons = 
			"SELECT " .  $total_fields . $sql_end_without_page_def .
			 " LIMIT " . (($page - 1) * $page_size) . "," . $page_size;
		$q_lessons = $conn->prepare($sql_lessons);
		
		$q_lessons->bind_param("ssss", $begin_time, $end_time, $begin_time, $end_time);
		$q_lessons->execute();		
		$q_lessons->store_result();
		$q_lessons->bind_result($lesson_id, $begin_time, $end_time, 
			$room_identifier);		

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
					"time_interval" => 
						from_db_datetimes_to_same_day_date_plus_times(
							$begin_time, $end_time),
					"room_identifier" => $room_identifier);
			}
		}
		
		$page_count = intdiv($count, $page_size);
		if ($page_count * $page_size < $count) { $page_count++; }	
		$page_page_count = intdiv($page_count, $page_page_size);
		if ($page_page_count * $page_page_size < $page_count) { $page_page_count++; }
		
		if ($page_count == 0) { $page_count = 1; }
		if ($page_page_count == 0) { $page_page_count = 1; }
		
		$lessons_arr = array(
			"lessons" => $lessons, 
			"count" => $count,
			"page_count" => $page_count,
			"page_page_count" => $page_page_count);
		
		return $lessons_arr;		
	}
	
	function get_lesson_topics($conn, $id) {
		$sql = "SELECT ca_topic.id, ca_topic.name FROM 
					ca_lesson, ca_lesson_topic, ca_topic
		        WHERE ca_topic.id = ca_lesson_topic.topic_id AND 
				      ca_lesson_topic.lesson_id = ca_lesson.id AND
					  ca_lesson.id = ?";
		$q = $conn->prepare($sql);
		$q->bind_param("i", $id);
		$q->execute();	
		$q->store_result();
		$q->bind_result($topic_id, $name);
		$topics = array();
		while($q->fetch()) {
			$topics[] = array(
				"name" => $name, 
				"remove_call" => 
					java_script_call("removeLessonTopic", array($id, $topic_id))						
			);
		}
		return $topics;
	}
	
	function get_new_avail_lesson_topics($conn, $id) {
		$sql = "SELECT ca_topic.id, ca_topic.name FROM 
					ca_topic LEFT JOIN ca_lesson_topic
					ON ca_topic.id = ca_lesson_topic.topic_id WHERE
		            NOT EXISTS (SELECT ca_lesson_topic.topic_id FROM 
						ca_lesson_topic WHERE
						ca_lesson_topic.lesson_id = ? AND 
						ca_lesson_topic.topic_id = ca_topic.id) AND
					ca_topic.removed = 0 ORDER by ca_topic.name";
		$q = $conn->prepare($sql);
		$q->bind_param("i", $id);
		$q->execute();	
		$q->store_result();
		$q->bind_result($topic_id, $name);
		$topics = array();
		while($q->fetch()) {
			$topic_already_contained = false;
			foreach($topics as $topic) {
				if ($topic["id"] == $topic_id) {
					$topic_already_contained = true;
				}
			}
			if (!$topic_already_contained) {
				$topics[] = array(
					"id" => $topic_id,
					"name" => $name, 
					"add_call" => 
						java_script_call("addTopicToLesson", array($topic_id, $id))						
				);
			}
		}
		return $topics;		
	}
?>