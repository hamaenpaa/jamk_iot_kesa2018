<?php
	define("PAGE_SIZE", 20);

	function get_courses($conn, $name_seek, $description_seek, $topic_seek, $page) {
		$total_fields = "ca_course.ID, ca_course.name, ca_course.description";
		$topic_part = "";
		if ($topic_seek != "") {
			$topic_part = "AND (EXISTS (SELECT id FROM ca_lesson WHERE
				ca_lesson.course_id = ca_course.id AND
				ca_lesson.topic LIKE '%". $topic_seek. "%' AND ca_lesson.removed = 0)";
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
				$courses[] = array("course_id" => $course_id,
					"name" => $name, 
					"description" => $description);
			}
		}

		$page_count = intdiv($count, PAGE_SIZE);
		if ($page_count * PAGE_SIZE < $count) { $page_count++; }		

		$courses_arr = array(
			"courses" => $courses,
			"count" => $count,
			"page_count" => $page_count			
	
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
				$course_lessons_arr[] = 
					array(
						"lesson_id" => $lesson_id,
						"topic" => $topic,
						"begin_time" => $begin_time,
						"end_time" => $end_time,
						"room_identifier" => $room_identifier
					);
			}
		}
		return $course_lessons_arr;
	}
	
	function get_lessons_without_course($conn,
	    $begin_time_seek, $end_time_seek, $room_seek, $topic_seek, $page) {
		$total_fields = " ca_lesson.ID, ca_lesson.topic, ca_lesson.room_identifier,
				ca_lesson.begin_time, ca_lesson.end_time ";
		$sql_end_part_without_paging = " FROM ca_lesson WHERE 
				ca_lesson.room_identifier LIKE '%".$room_seek."%' AND
				ca_lesson.topic LIKE '%".$topic_seek."%' AND 
			    ((? <= ca_lesson.begin_time AND ca_lesson.begin_time <= ?) OR
				 (? <= ca_lesson.end_time AND ca_lesson.end_time <= ?))	AND		
				ca_lesson.removed = 0 AND ca_lesson.course_id IS NULL 
				ORDER BY ca_lesson.begin_time DESC, ca_lesson.topic ASC ";
		$sql_lessons_without_course = 
			"SELECT " . $total_fields . $sql_end_part_without_paging .
			" LIMIT " . (($page - 1) * PAGE_SIZE) . "," . PAGE_SIZE;
		$sql_lessons_count = "SELECT COUNT(*) " . $sql_end_part_without_paging;			
		
		$begin_time = from_ui_to_db($begin_time_seek);
		$end_time = from_ui_to_db($end_time_seek);			
		$q = $conn->prepare($sql_lessons_without_course);
		$q->bind_param("ssss", $begin_time, $end_time, $begin_time, $end_time);		
		
		$q_count = $conn->prepare($sql_lessons_count);
		$q_count->bind_param("ssss", $begin_time, $end_time, $begin_time, $end_time);	
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
			$q->bind_result($lesson_id, $topic, $room_identifier, $begin_time, $end_time);
			while ($q->fetch()) {
				$lessons_without_course[] = 
					array(
						"lesson_id" => $lesson_id,
						"topic" => $topic,
						"begin_time" => $begin_time,
						"end_time" => $end_time,
						"room_identifier" => $room_identifier
					);
			}
		}
		
		$page_count = intdiv($count, PAGE_SIZE);
		if ($page_count * PAGE_SIZE < $count) { $page_count++; }	
		
		$lessons_without_course_arr = array(
			"lessons" => $lessons_without_course, 
			"count" => $count,
			"page_count" => $page_count);	
		
		return $lessons_without_course_arr;
	}
?>