<?php
	define("WITH_ROOMS", "1");
	define("WITH_COURSES", "0");
	include("fetch_room_log_data_from_db.php");
	$begin_time = get_post_or_get($conn, "begin_time");
	$end_time = get_post_or_get($conn, "end_time");
	$lesson = get_post_or_get($conn, "lesson");
	if ((isset($begin_time) && isset($end_time) &&
		$begin_time != "" && $end_time != "") ||
		(isset($lesson) && $lesson != "")) {
		if (!isset($begin_time) || !isset($end_time) ||
			$begin_time == "" || $end_time == "") {
			$lesson_times = get_lesson_times($conn, $lesson);
			$begin_time = $lesson_times['begin_time']; 
			$end_time = $lesson_times['end_time'];
		} else {
			$begin_time = from_ui_to_db($begin_time);
			$end_time = from_ui_to_db($end_time);			
		}
		if ($seek_with == "course") {
			$course = get_post_or_get($conn, "course");
			$room = "";
		}
		else if ($seek_with == "room") {
			$course = "";
			$room = get_post_or_get($conn, "room");
		}		
		$sql_room_log_end_part = get_room_log_sql_query_end_part(
			$seek_with, WITH_COURSES, WITH_ROOMS, true);
		$room_logs_students = get_room_log($conn, WITH_COURSES, WITH_ROOMS, 
			$sql_room_log_end_part, $begin_time, $end_time, $room, $course, true);
		$room_log_distinct_students = get_room_log_distinct_students($conn, 
			WITH_COURSES, WITH_ROOMS,
			$sql_room_log_end_part,
			$begin_time, $end_time, $course, $room);
		$course_students_not_at_room_log = 
			get_course_students_not_at_room_log($conn, $sql_room_log_end_part,
			$room_log_distinct_students, $course);	
		if ($course != "") {
			$all_course_students = get_all_course_students($conn, $course);
			$room_log_course_students = get_room_logs_of_student_ids($room_logs_students, $all_course_students, true);
		}

			
		if (count($course_students_not_at_room_log) > 0) {
?>
<h2>Kurssin oppilaat ilman tuntikirjausta</h2>
<div class="room_log_listing_table">
<?php
			include("ui_table_column_widths_for_unsigned_students.php");
			include("room_log_column_header_row.php");
			foreach($course_students_not_at_room_log as $course_student) {
				$student_first_name = $course_student['firstName'];
				$student_last_name = $course_student['lastName'];	
				$nfc_id = $course_student['nfc_id'];
				$ui_course_ID = $course_student['ui_course_ID'];
				$course_name = $course_student['course_name'];
				$guest_first_name = ""; $guest_last_name = "";
				include("room_log_data_row.php");
			}
?>	
</div>
<?php
		}
		if (count($room_logs_students) > 0) {
			include("ui_table_column_widths_for_signed_in_students.php");
?>
<h2>Oppilaat, joille on tuntikirjaus</h2>
<div class="room_log_listing_table">
<?php
			$guest_first_name = ""; $guest_last_name = "";
			include("room_log_column_header_row.php");
			foreach($room_logs_students as $room_log) {
				$student_first_name = $room_log['student_first_name'];
				$student_last_name = $room_log['student_last_name'];
				$dt = $room_log['dt'];
				$room_name = $room_log['room_name'];
				$ui_course_ID = $room_log['ui_course_ID'];
				$course_name = $room_log['course_name'];
				$nfc_id = $room_log['nfc_id'];
				include("room_log_data_row.php"); 
			}
?>
</div>
<?php
		}
		$sql_room_log_end_part_guests = get_room_log_sql_query_end_part(
			$seek_with, WITH_COURSES, WITH_ROOMS, false);
		$room_logs_guests = get_room_log($conn, WITH_COURSES, WITH_ROOMS, 
			$sql_room_log_end_part_guests, $begin_time, $end_time, $room, $course, false);	
		if (count($room_logs_guests) > 0) {
?>
<h2>Vierailijat koulun ulkopuolelta</h2>
<div class="room_log_listing_table">
<?php			
			$student_first_name = ""; $student_last_name = "";
			include("room_log_column_header_row.php");
			foreach($room_logs_guests as $room_log) {
				$guest_first_name = $room_log['guest_first_name'];
				$guest_last_name = $room_log['guest_last_name'];				
				$dt = $room_log['dt'];
				$room_name = $room_log['room_name'];
				$ui_course_ID = $room_log['ui_course_ID'];
				$course_name = $room_log['course_name'];
				$nfc_id = $room_log['nfc_id'];
				include("room_log_data_row.php"); 
			}		
		}
?>
</div>
<?php
	}
?>