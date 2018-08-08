<?php
	include("fetch_room_log_data_from_db.php");
	$begin_time = get_post_or_get($conn, "begin_time");
	$end_time = get_post_or_get($conn, "end_time");
	
	if (!isset($room)) {
		$room = "";
	}
	if (!isset($course)) {
		$course = "";
	}	
	
	if ((isset($begin_time) && isset($end_time) &&
		$begin_time != "" && $end_time != "")) {
		if (!isset($begin_time) || !isset($end_time) ||
			$begin_time == "" || $end_time == "") {
			$begin_time = $lesson_times['begin_time']; 
			$end_time = $lesson_times['end_time'];
		} else {
			$begin_time = from_ui_to_db($begin_time);
			$end_time = from_ui_to_db($end_time);			
		}

		$course = get_post_or_get($conn, "course");
		$room = get_post_or_get($conn, "room");
			
		$sql_room_log_end_part = get_room_log_sql_query_end_part();
		$room_logs_students = get_room_log($conn, WITH_COURSES, WITH_ROOMS, 
			$sql_room_log_end_part, $begin_time, $end_time, $room, $course, true);
		if ($course != "") {
			$all_course_students = get_all_course_students($conn, $course);
			$room_log_course_students = get_room_logs_of_student_ids($room_logs_students, $all_course_students, true);
			$room_logs_not_course_students = get_room_logs_of_student_ids($room_logs_students, $all_course_students, false);
		}

		if (count($room_log_course_students) > 0) {
			$dt_extra_css_classes = "";
			$room_extra_css_classes = "";
			$nfc_extra_css_classes = "";
	
			$dt_cols = "4";
			$nfc_cols = "4";
			$room_cols = "4";			
?>
			<h2>Sisäänkirjautuneet ihmiset</h2>
			<div class="room_log_listing_table">
				<div class="heading-row">
					<div class="row">
					<div class="col-sm-<?php echo $dt_cols; ?>"><b>Sisääntuloaika</b></div>
					<div class="col-sm-<?php echo $room_cols; ?>"><h5>Luokka</h5></div>
					<div class="col-sm-<?php echo $nfc_cols; ?>"><h5>NFC ID</h5></div>
				</div>
			</div>
<?php
			foreach($room_log_course_students as $room_log) {
				$dt = $room_log['dt'];
				$room_name = $room_log['room_name'];
				$nfc_id = $room_log['nfc_id'];
?>				
				<div class="row">
					<div class="col-sm-<?php echo $dt_cols . " ". $dt_extra_css_classes; ?>">
						<?php echo $dt; ?>
					</div>
					<div class="col-sm-<?php echo $room_cols . " " . $room_extra_css_classes; ?>">
						<?php echo $room_name;  ?>
					</div>
					<div class="col-sm-<?php echo $nfc_cols; ?>">
						<?php echo $nfc_id; ?>
					</div>
				</div>				
<?php				
			}
		}
	}
?>
</div>