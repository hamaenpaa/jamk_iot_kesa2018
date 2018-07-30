<?php
	function get_lesson_times($conn, $lesson_id) {
		$sql_times = "SELECT ca_lesson.begin_time, ca_lesson.end_time FROM ca_lesson WHERE ID = ?";
		$q_times = $conn->prepare($sql_times);
		$q_times->bind_param("i", $lesson);	
		$q_times->execute();		
		$q_times->store_result();				
		$q_times->bind_result($begin_time, $end_time);
		$q_times->fetch();					
		return array("begin_time" => $begin_time, "end_time" => $end_time);
	}

	function get_room_log_distinct_students($conn, 
		$with_courses, $with_rooms,
		$sql_room_log_end_part,
		$begin_time, $end_time, $course, $room) {
		$sql = "SELECT DISTINCT(ca_student.ID) " . $sql_room_log_end_part;
		$q_room_log_distinct_students = $conn->prepare($sql);
		if (isset($room) && $room != "") {
			if ($with_rooms)
				$q_room_log_distinct_students->bind_param("ssi", $begin_time, $end_time, $room);
			else
				$q_room_log_distinct_students->bind_param("ss", $begin_time, $end_time);
		} else {
			if ($with_courses)
				$q_room_log_distinct_students->bind_param("ssi", $begin_time, $end_time, $course);
			else 
				$q_room_log_distinct_students->bind_param("ss", $begin_time, $end_time);
		}
		$q_room_log_distinct_students->execute();		
		$q_room_log_distinct_students->store_result();			
		$q_room_log_distinct_students->bind_result($student_id);
		$student_ids = array();
		if ($q_room_log_distinct_students->num_rows > 0) {
			while($room_log_students = $q_room_log_distinct_students->fetch()) {		
				$student_ids[] = $student_id;
			}
		}
		return $student_ids;
	}
	
	function get_room_log_sql_query_end_part($seek_with, $with_courses, $with_rooms, $students) {
		$sql_room_log_end_part = "FROM ca_roomlog ";
		if ($with_rooms)
			$sql_room_log_end_part .= "INNER JOIN ca_room ON ca_roomlog.room_id = ca_room.ID ";
		if ($with_courses)
			$sql_room_log_end_part .= "INNER JOIN ca_course ON ca_roomlog.course_id = ca_course.ID ";
		if ($students) 
			$sql_room_log_end_part .= " LEFT JOIN ca_student ON ca_student.ID = ca_roomlog.student_id ";
		else 
			$sql_room_log_end_part .= " LEFT JOIN ca_guest ON ca_guest.ID = ca_roomlog.guest_id "; 
		// $sql_room_log .= "WHERE ca_roomlog.removed = 0 ";
		$sql_room_log_end_part .= "WHERE ca_roomlog.dt >= ? AND ca_roomlog.dt <= ? ";
		if ($seek_with == "course") {
			if ($with_courses)
				$sql_room_log_end_part .= " AND ca_roomlog.course_id = ?";
		} else {
			if ($with_rooms)
				$sql_room_log_end_part .= " AND ca_roomlog.room_id = ?";	
		}
		return $sql_room_log_end_part;
	}
	
	function get_course_students_not_at_room_log($conn, $sql_room_log_sql_query_end_part,
		$student_ids_at_room_log, $course) {
			
		$room_log_student_ids_list = implode(",", $student_ids_at_room_log);
		
		$sql_condition_of_id_list = "";
		if ($room_log_student_ids_list != "") {
			$sql_condition_of_id_list = " AND ca_course_student.student_id NOT IN (".
				$room_log_student_ids_list . ")";
		}			
		
		$sql_course_students_not_at_room_log =
			"SELECT ca_student.ID, ca_student.student_id, 
			 ca_student.FirstName, ca_student.LastName, ca_student.NFC_ID,
			 ca_course.course_id, ca_course.course_name
			 FROM ca_student, ca_course_student, ca_course WHERE 
			 ca_student.ID = ca_course_student.student_id AND
			 ca_course_student.course_id = ca_course.ID AND 
			 ca_course_student.course_id = ? ". $sql_condition_of_id_list;
			 
		$q_course_students = $conn->prepare($sql_course_students_not_at_room_log);
		$q_course_students->bind_param("i", $course);
		$q_course_students->execute();		
		$q_course_students->store_result();
		$q_course_students->bind_result($student_id, $ui_student_id, 
			$studentFirstName, $studentLastName, $NFC_ID, 
			$ui_course_ID, $course_name);
		$course_students_arr = array();
		if ($q_course_students->num_rows > 0) {
			while($course_students = $q_course_students->fetch()) {
				$course_students_arr[] = array(
					"student_id" => $student_id, "ui_student_id" => $ui_student_id,
					"firstName" => $studentFirstName, "lastName" => $studentLastName, 
					"nfc_id" => $NFC_ID,
					"ui_course_ID" => $ui_course_ID, "course_name" => $course_name);
			}
		}		
		return $course_students_arr;
	}
	
	function get_room_log($conn, $with_courses, $with_rooms, $sql_room_log_sql_query_end_part,
		$begin_time, $end_time, $room, $course, $students) {
			
		/*
			ca_course.course_ID, ca_course.course_name,
			,
			ca_room.room_name FROM ca_roomlog 
		*/			
			
		$room_log_queried_fields_total = "ca_roomlog.ID, ca_roomlog.NFC_ID, ca_roomlog.dt ";
		if ($with_courses)
			$room_log_queried_fields_total .= ", ca_course.course_ID, ca_course.course_name ";		
		if ($with_rooms)
			$room_log_queried_fields_total .= ", ca_room.room_name ";
		if ($students)
			$room_log_queried_fields_total .= ", ca_student.student_id, ca_student.firstName, ca_student.lastName ";
		else 
			$room_log_queried_fields_total .= ", ca_guest.firstName, ca_guest.lastName ";	
		$sql_room_logs_total = "SELECT " . $room_log_queried_fields_total . 
									$sql_room_log_sql_query_end_part;
		$q_room_logs = $conn->prepare($sql_room_logs_total);
		if (isset($room) && $room != "") {
			if ($with_rooms)
				$q_room_logs->bind_param("ssi", $begin_time, $end_time, $room);
			else
				$q_room_logs->bind_param("ss", $begin_time, $end_time);
		} else {
			if ($with_courses)
				$q_room_logs->bind_param("ssi", $begin_time, $end_time, $course);
			else 
				$q_room_logs->bind_param("ss", $begin_time, $end_time);
		}
		$q_room_logs->execute();		
		$q_room_logs->store_result();		
		
		$student_id = ""; $student_first_name = ""; $student_last_name = "";
		$guest_first_name = ""; $guest_last_name = "";
		if (!$with_courses && !$with_rooms) {
			$room_name = ""; $ui_course_ID = ""; $course_name = "";
			if ($students) 
				$q_room_logs->bind_result($room_log_id, $nfc_id, $dt,
					$student_id, $student_first_name, $student_last_name);
			else 
				$q_room_logs->bind_result($room_log_id, $nfc_id, $dt,
					$guest_first_name, $guest_last_name);				
		}
		else if ($with_courses && !$with_rooms) {
			$room_name = "";
			if ($students)
				$q_room_logs->bind_result($room_log_id, $nfc_id, $dt, $ui_course_ID, $course_name,
					$student_id, $student_first_name, $student_last_name);
			else 
				$q_room_logs->bind_result($room_log_id, $nfc_id, $dt, $ui_course_ID, $course_name,
					$guest_first_name, $guest_last_name);				
		}
		else if ($with_rooms && !$with_courses) {
			$ui_course_ID = ""; $course_name = ""; 
			if ($students) 
				$q_room_logs->bind_result($room_log_id, $nfc_id, $dt, $room_name,
					$student_id, $student_first_name, $student_last_name);
			else 
				$q_room_logs->bind_result($room_log_id, $nfc_id, $dt, $room_name,
					$guest_first_name, $guest_last_name);
		}
		else {
			if ($students) 
				$q_room_logs->bind_result(
					$room_log_id, $nfc_id, $dt, $ui_course_ID, $course_name, $room_name,
					$student_id, $student_first_name, $student_last_name);
			else
				$q_room_logs->bind_result(
					$room_log_id, $nfc_id, $dt, $ui_course_ID, $course_name, $room_name,
					$guest_first_name, $guest_last_name);				
		}		
		$room_log_arr = array();
		if ($q_room_logs->num_rows > 0) {
			while($room_logs = $q_room_logs->fetch()) {
				$room_log_arr[] = array("room_log_id " => $room_log_id,
					"nfc_id" => $nfc_id, "dt" => $dt, "ui_course_ID" => $ui_course_ID,
					"course_name" => $course_name, "room_name" => $room_name,
					"student_id" => $student_id, "student_first_name" => $student_first_name,
						"student_last_name" => $student_last_name,
					"guest_first_name" => $guest_first_name, "guest_last_name" => $guest_last_name);
			}
		}
		return $room_log_arr;
	}
?>