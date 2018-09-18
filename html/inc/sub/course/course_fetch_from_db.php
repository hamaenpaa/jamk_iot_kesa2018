<?php
	function get_courses($conn, $name_seek, $description_seek, $topic_ids, $page) {
		if (!is_integerable($page) || $page == "" || $page == "0") {
			return array();
		}
		if (mb_strlen($name_seek) > 50 || mb_strlen($description_seek) > 500) {
			return array();
		}
		
		list($page_size, $page_page_size) =
			get_page_and_page_page_sizes($conn);
		
		$total_fields = "ca_course.ID, ca_course.name, ca_course.description";
		$topic_part  = "";
		if ($topic_ids != "") {
			$topic_part = "AND EXISTS (SELECT ca_lesson.id FROM ca_lesson WHERE
				ca_lesson.course_id = ca_course.id AND
				EXISTS(SELECT id FROM ca_lesson_topic WHERE ca_lesson_topic.lesson_id =
				ca_lesson.id AND ca_lesson_topic.topic_id IN (". $topic_ids .")) AND 
				ca_lesson.removed = 0)";
		}
		
		$end_part_without_pages = " FROM ca_course WHERE 
				name LIKE '%" .$name_seek ."%'
				AND description LIKE '%" .$description_seek ."%'
				AND removed = 0 " . $topic_part . 
			    " ORDER BY name ASC";
		$sql_courses_total = "SELECT " . $total_fields . $end_part_without_pages .
			" LIMIT " . (($page - 1) * $page_size) . "," . $page_size;
		$sql_course_count = "SELECT COUNT(*) " . $end_part_without_pages;
		
		$q_courses = $conn->prepare($sql_courses_total);
		$q_courses->execute();		
		$q_courses->store_result();
		$q_courses->bind_result($course_id, $name, $description);
		
		$q_courses_count = "SELECT COUNT(*) " . $end_part_without_pages;
		$q_courses_count = $conn->prepare($sql_course_count);
		$q_courses_count->execute();		
		$q_courses_count->store_result();
		$q_courses_count->bind_result($count);
		$q_courses_count->fetch();		
	
		$courses = array();
		if ($q_courses->num_rows > 0) {
			while($q_courses->fetch()) {
				$courses[] = array("course_id" => $course_id,
					"name" => $name, 
					"description" => $description);
			}
		}

		$page_count = intdiv($count, $page_size);
		if ($page_count * $page_size < $count) { $page_count++; }		
		$page_page_count = intdiv($page_count, $page_page_size);
		if ($page_page_count * $page_page_size < $page_count) { $page_page_count++; }
		
		if ($page_count == 0) { $page_count = 1; }
		if ($page_page_count == 0) { $page_page_count = 1; }
		
		$courses_arr = array(
			"courses" => $courses,
			"count" => $count,
			"page_count" => $page_count,
			"page_page_count" => $page_page_count			
		);
		
		return $courses_arr;		
	}
	
	function get_course_lessons($conn, $course_id) {
		$sql_course_lessons = 
			"SELECT ca_lesson.ID, ca_lesson.room_identifier,
				ca_lesson.begin_time, ca_lesson.end_time FROM 
				ca_lesson WHERE ca_lesson.removed = 0 AND ca_lesson.course_id = ?
				ORDER BY ca_lesson.begin_time ";
		$q = $conn->prepare($sql_course_lessons);
		$course_lessons_arr = array();
		if ($q) {
			$q->bind_param("i", $course_id);
			$q->execute();
			$q->store_result(); 		
			$q->bind_result($lesson_id, $room_identifier, $begin_time, $end_time);
			while ($q->fetch()) {
				$lesson_period = 
					from_db_datetimes_to_same_day_date_plus_times(
							$begin_time, $end_time);
				$course_lessons_arr[] = 
					array(
						"lesson_id" => $lesson_id,
						"topic" => "",
						"lesson_period" => $lesson_period,
						"room_identifier" => $room_identifier,
						"remove_call" => 
							java_script_call("removeCourseLesson", array($course_id, $lesson_id))						
					);
			}
		}
		return $course_lessons_arr;
	}
	
	function get_lessons_without_course($conn,
	    $begin_time_seek, $end_time_seek, $room_seek, 
		$topic_name_parts, $selected_topic_ids, $page) {
		list($page_size, $page_page_size) =
			get_page_and_page_page_sizes($conn);	
		$topic_ids = get_total_topic_ids($conn, 
			$topic_name_parts, $selected_topic_ids);
		$topic_part  = "";
		if ($topic_ids != "") {
			$topic_part = " AND 
				EXISTS(SELECT id FROM ca_lesson_topic WHERE 
				ca_lesson_topic.lesson_id =	ca_lesson.id AND 
				ca_lesson_topic.topic_id IN (". $topic_ids ."))";
		}
		$total_fields = " ca_lesson.ID, ca_lesson.room_identifier,
				ca_lesson.begin_time, ca_lesson.end_time ";
		$sql_end_part_without_paging = " FROM ca_lesson WHERE 
				ca_lesson.room_identifier LIKE '%".$room_seek."%' AND
			    ((? <= ca_lesson.begin_time AND ca_lesson.begin_time <= ?) OR
				 (? <= ca_lesson.end_time AND ca_lesson.end_time <= ?))	AND		
				ca_lesson.removed = 0 AND ca_lesson.course_id IS NULL " .
				$topic_part .
				" ORDER BY ca_lesson.begin_time DESC";
		$sql_lessons_without_course = 
			"SELECT " . $total_fields . $sql_end_part_without_paging .
			" LIMIT " . (($page - 1) * $page_size) . "," . $page_size;
		$sql_lessons_count = "SELECT COUNT(*) " . $sql_end_part_without_paging;			
		$q = $conn->prepare($sql_lessons_without_course);
		$q->bind_param("ssss", $begin_time_seek, $end_time_seek, 
			$begin_time_seek, $end_time_seek);		
		$q_count = $conn->prepare($sql_lessons_count);
		$q_count->bind_param("ssss", $begin_time_seek, $end_time_seek, 
			$begin_time_seek, $end_time_seek);	
		$count = 0;
		if ($q_count) {
			$q_count->execute();
			$q_count->store_result(); 		
			$q_count->bind_result($count);		
			$q_count->fetch();
		}
		$lessons_without_course = array();
		$lesson_ids = array();
		if ($q) {
			$q->execute();
			$q->store_result(); 		
			$q->bind_result($lesson_id, $room_identifier, $begin_time, $end_time);
			while ($q->fetch()) {
				$lesson_period =  
					from_db_datetimes_to_same_day_date_plus_times(
						$begin_time, $end_time);
				$lessons_without_course[] = 
					array(
						"topics" => "",
						"lesson_id" => $lesson_id,
						"lesson_period" => $lesson_period,
						"room_identifier" => $room_identifier
					);
				$lesson_ids[] = $lesson_id;
			}
		}
		if (count($lesson_ids) > 0) {
			$arr_lessons_to_topics = array();
			$sql_lesson_topics = 
				"SELECT ca_topic.name, ca_topic.id, ca_lesson.id FROM 
					ca_topic, ca_lesson_topic, ca_lesson 
				 WHERE 
				    ca_topic.id = ca_lesson_topic.topic_id AND 
					ca_lesson_topic.lesson_id = ca_lesson.id AND 
					ca_lesson.id IN (". implode(",", $lesson_ids) . ")";
			$q_lessons_to_topics = $conn->prepare($sql_lesson_topics);
			if ($q_lessons_to_topics) {
				$q_lessons_to_topics->execute();
				$q_lessons_to_topics->store_result(); 		
				$q_lessons_to_topics->bind_result($topic_name,$topic_id,$lesson_id);
				while ($q_lessons_to_topics->fetch()) {
					if (!array_key_exists($lesson_id, $arr_lessons_to_topics)) {
						$arr_lessons_to_topics[$lesson_id] = 
							array("topic_ids" => array(), "topics" => "");
					}
					if (!in_array($topic_id, $arr_lessons_to_topics[$lesson_id]["topic_ids"])) {
						$arr_lessons_to_topics[$lesson_id]["topic_ids"][] = $topic_id;
						$curr_topics = $arr_lessons_to_topics[$lesson_id]["topics"];
						if ($curr_topics != "") {
							$curr_topics .= ",";
						}
						$curr_topics .= $topic_name;
						$arr_lessons_to_topics[$lesson_id]["topics"] = $curr_topics;
					}
				}
			}
			foreach($lessons_without_course as $key => $lesson_without_course) {
				if (array_key_exists($lesson_without_course["lesson_id"],
					$arr_lessons_to_topics)) {
					$lessons_without_course[$key]["topics"] = 
						$arr_lessons_to_topics[
							$lesson_without_course["lesson_id"]]["topics"];
				}
			}
		}
		$page_count = intdiv($count, $page_size);
		if ($page_count * $page_size < $count) { $page_count++; }		
		$page_page_count = intdiv($page_count, $page_page_size);
		if ($page_page_count * $page_page_size < $page_count) { $page_page_count++; }
		if ($page_count == 0) { $page_count = 1; }
		if ($page_page_count == 0) { $page_page_count = 1; }
		$lessons_without_course_arr = array(
			"lessons" => $lessons_without_course, 
			"count" => $count,
			"page_count" => $page_count,
			"page_page_count" => $page_page_count);	
		return $lessons_without_course_arr;
	}
?>