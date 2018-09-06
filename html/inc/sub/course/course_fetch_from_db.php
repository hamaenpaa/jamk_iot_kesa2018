<?php
	define("PAGE_SIZE", 2);

	function get_courses($conn, $name_seek, $description_seek, $topic_ids, $page) {
		if (!is_integerable($page) || $page == "" || $page == "0") {
			return array();
		}
		if (strlen($name_seek) > 50 || strlen($description_seek) > 500) {
			return array();
		}
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
			" LIMIT " . (($page - 1) * PAGE_SIZE) . "," . PAGE_SIZE;
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
				$name = str_replace(" ", "&nbsp;", $name);
				if ($name == "") { $name = "&nbsp;"; }
				$description = str_replace(" ", "&nbsp;", $description);
				if ($description == "") { $description = "&nbsp;"; }			
				$courses[] = array("course_id" => $course_id,
					"name" => $name, 
					"description" => $description);
			}
		}

		$page_count = intdiv($count, PAGE_SIZE);
		if ($page_count * PAGE_SIZE < $count) { $page_count++; }		
		$page_page_count = intdiv($page_count, PAGE_PAGE_SIZE);
		if ($page_page_count * PAGE_PAGE_SIZE < $page_count) { $page_page_count++; }
		
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
			"SELECT ca_lesson.ID, ca_lesson.topic, ca_lesson.room_identifier,
				ca_lesson.begin_time, ca_lesson.end_time FROM 
				ca_lesson WHERE ca_lesson.removed = 0 AND ca_lesson.course_id = ?
				ORDER BY ca_lesson.begin_time DESC, ca_lesson.topic ASC";
		$q = $conn->prepare($sql_course_lessons);
		$course_lessons_arr = array();
		if ($q) {
			$q->bind_param("i", $course_id);
			$q->execute();
			$q->store_result(); 		
			$q->bind_result($lesson_id, $topic, $room_identifier, $begin_time, $end_time);
			while ($q->fetch()) {
				$lesson_period = 
					str_replace(" ", "&nbsp;", 
						from_db_datetimes_to_same_day_date_plus_times(
							$begin_time, $end_time));
				$course_lessons_arr[] = 
					array(
						"lesson_id" => $lesson_id,
						"topic" => str_replace(" ", "&nbsp;", $topic),
						"lesson_period" => $lesson_period,
						"room_identifier" => str_replace(" ", "&nbsp;", $room_identifier),
						"remove_call" => 
							java_script_call("removeCourseLesson", array($course_id, $lesson_id))						
					);
			}
		}
		return $course_lessons_arr;
	}
	
	function get_lessons_without_course($conn,
	    $begin_time_seek, $end_time_seek, $room_seek, $topic_seek, $page) {
		$total_fields = " ca_lesson.ID, ca_lesson.room_identifier,
				ca_lesson.begin_time, ca_lesson.end_time ";
		$sql_end_part_without_paging = " FROM ca_lesson WHERE 
				ca_lesson.room_identifier LIKE '%".$room_seek."%' AND
			    ((? <= ca_lesson.begin_time AND ca_lesson.begin_time <= ?) OR
				 (? <= ca_lesson.end_time AND ca_lesson.end_time <= ?))	AND		
				ca_lesson.removed = 0 AND ca_lesson.course_id IS NULL 
				ORDER BY ca_lesson.begin_time DESC";
		$sql_lessons_without_course = 
			"SELECT " . $total_fields . $sql_end_part_without_paging .
			" LIMIT " . (($page - 1) * PAGE_SIZE) . "," . PAGE_SIZE;
		$sql_lessons_count = "SELECT COUNT(*) " . $sql_end_part_without_paging;			

		// echo "sql_lessons_without_course ". $sql_lessons_without_course . "\n";
		// echo "begin_time_seek ". $begin_time_seek . " end_time_seek " . $end_time_seek . "\n";
		
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
		if ($q) {
			$q->execute();
			$q->store_result(); 		
			$q->bind_result($lesson_id, $room_identifier, $begin_time, $end_time);
			while ($q->fetch()) {
				$lesson_period = str_replace(" ", "&nbsp;", 
					from_db_datetimes_to_same_day_date_plus_times(
						$begin_time, $end_time));
				$lessons_without_course[] = 
					array(
						"lesson_id" => $lesson_id,
						"lesson_period" => $lesson_period,
						"room_identifier" => str_replace(
							" ", "&nbsp;", $room_identifier)
					);
			}
		}
		
		$page_count = intdiv($count, PAGE_SIZE);
		if ($page_count * PAGE_SIZE < $count) { $page_count++; }		
		$page_page_count = intdiv($page_count, PAGE_PAGE_SIZE);
		if ($page_page_count * PAGE_PAGE_SIZE < $page_count) { $page_page_count++; }
		
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