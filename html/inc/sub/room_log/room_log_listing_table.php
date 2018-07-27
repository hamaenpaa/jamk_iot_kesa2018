<?php
	define("WITH_ROOMS", "1");
	define("WITH_COURSES", "0");

	$begin_time = get_post_or_get($conn, "begin_time");
	$end_time = get_post_or_get($conn, "end_time");
	$lesson = get_post_or_get($conn, "lesson");
	if ((isset($begin_time) && isset($end_time) &&
		$begin_time != "" && $end_time != "") ||
		(isset($lesson) && $lesson != "")) {
		if (!isset($begin_time) || !isset($end_time) ||
			$begin_time == "" || $end_time == "") {
			$sql_times = "SELECT ca_lesson.begin_time, ca_lesson.end_time FROM ca_lesson WHERE ID = ?";
			$q_times = $conn->prepare($sql_times);
			$q_times->bind_param("i", $lesson);	
			$q_times->execute();		
			$q_times->store_result();				
			$q_times->bind_result($begin_time, $end_time);
			$q_times->fetch();			
		}
		if ($seek_with == "course") {
			$course = get_post_or_get($conn, "course");
		}
		else if ($seek_with == "room") {
			$room = get_post_or_get($conn, "room");
		}		
		
		/* 					     
			ca_guest.firstName, ca_guest.lastName, 
			ca_course.course_ID, ca_course.course_name,
			ca_student.student_id, ca_student.firstName, ca_student.lastName,
			ca_room.room_name FROM ca_roomlog 
		*/
		
		$sql_room_log = "SELECT ca_roomlog.ID, ca_roomlog.NFC_ID, ca_roomlog.dt ";
		if (WITH_COURSES)
			$sql_room_log .= ", ca_course.course_ID, ca_course.course_name ";		
		if (WITH_ROOMS)
			$sql_room_log .= ", ca_room.room_name ";
		$sql_room_log .= "FROM ca_roomlog ";
		if (WITH_ROOMS)
			$sql_room_log .= "INNER JOIN ca_room ON ca_roomlog.room_id = ca_room.ID ";
		if (WITH_COURSES)
			$sql_room_log .= "INNER JOIN ca_course ON ca_roomlog.course_id = ca_course.ID ";
		
/*		$sql_room_log .= ",LEFT JOIN ca_student ON ca_student.ID = ca_roomlog.student_id
						  LEFT JOIN ca_guest ON ca_guest.ID = ca_roomlog.guest_id "; */
		// $sql_room_log .= "WHERE ca_roomlog.removed = 0 ";
		$sql_room_log .= "WHERE ca_roomlog.dt >= ? AND ca_roomlog.dt <= ? ";
		if ($seek_with == "course") {
			if (WITH_COURSES)
				$sql_room_log .= " AND ca_roomlog.course_id = ?";
		} else {
			if (WITH_ROOMS)
				$sql_room_log .= " AND ca_roomlog.room_id = ?";
		}
		
		// echo "sql_room_log " . $sql_room_log . "\n";
		$begin_time = from_ui_to_db($begin_time);
		$end_time = from_ui_to_db($end_time);
		$q_room_logs = $conn->prepare($sql_room_log);
		if (isset($room) && $room != "") {
			// echo "begin_time " . $begin_time . ", end_time " . $end_time . " room " . $room;
			if (WITH_ROOMS)
				$q_room_logs->bind_param("ssi", $begin_time, $end_time, $room);
			else
				$q_room_logs->bind_param("ss", $begin_time, $end_time);
		} else {
			// echo "begin_time " . $begin_time . ", end_time " . $end_time . " course " . $course;
			if (WITH_COURSES)
				$q_room_logs->bind_param("ssi", $begin_time, $end_time, $course);
			else 
				$q_room_logs->bind_param("ss", $begin_time, $end_time);
		}
		$q_room_logs->execute();		
		$q_room_logs->store_result();
		$nfc_cols = "0"; $course_cols = "0"; $room_cols = "0";
		if (!WITH_COURSES && !WITH_ROOMS) {
			$q_room_logs->bind_result($room_log_id, $nfc_id, $dt);
			$dt_cols = "8";
			$nfc_cols = "2";
		}
		else if (WITH_COURSES && !WITH_ROOMS) {
			$q_room_logs->bind_result($room_log_id, $nfc_id, $dt, $ui_course_ID, $course_name);
			$dt_cols = "5";
			$course_cols = "3";
			$nfc_cols = "2";
		}
		else if (WITH_ROOMS && !WITH_COURSES) {
			$q_room_logs->bind_result($room_log_id, $nfc_id, $dt, $room_name);
			$dt_cols = "5";
			$room_cols = "3";
			$nfc_cols = "2";			
		}
		else {
			$q_room_logs->bind_result($room_log_id, $nfc_id, $dt, $ui_course_ID, $course_name, $room_name);
			$dt_cols = "3";
			$nfc_cols = "2";
			$room_cols = "2";
			$course_cols = "3";
		}
//		$q_room_logs->bind_result(
//			$room_log_id, $nfc_id, $dt, $room_name
/*			,
			$guest_first_name, $guest_last_name,
			$ui_course_ID, $course_name,          
			$student_id, $student_first_name, $student_last_name,
			*/ 
// );			
		if ($q_room_logs->num_rows > 0) {	
		
?>

<div class="room_log_listing_table">
	<div class="row">
				<div class="col-sm-<?php echo $dt_cols; ?>"><b>Sisääntuloaika</b></div>
<?php 		if ($room_cols != "0") { ?>			
				<div class="col-sm-<?php echo $room_cols; ?>"><b>Luokka</b></div>
<?php 		} 
			if ($course_cols != "0") {	
?>	
				<div class="col-sm-<?php echo $course_cols; ?>"><b>Kurssi</b></div>
<?php
			}
?>
				<div class="col-sm-<?php echo $nfc_cols; ?>"><b>NFC ID</b></div>
				<div class="col-sm-1"><b>Muokkaa</b></div>
				<div class="col-sm-1"><b>Poista</b></div>
	</div>
	
	
<?php
			while($room_logs = $q_room_logs->fetch()) {
?>
				<div class="row">
<?php /*					
<?php
					if ($student_first_name != "" || $student_last_name != "") {
?>
						<div class="col-sm-2">
<?php
							echo $student_last_name;
?>	
						</div>
						<div class="col-sm-2">
<?php
						echo $student_first_name;
?>	
						</div>
<?php
					} else {
?>						
						<div class="col-sm-2">
<?php
							echo $guest_last_name;
?>	
						</div>
						<div class="col-sm-2">
<?php
							echo $guest_first_name;
?>	
						</div>
<?php
					}
?>
<?php */ ?>						
					<div class="col-sm-<?php echo $dt_cols; ?>"><?php echo $dt; ?></div>
<?php if ($room_cols != "0") { ?>			
					<div class="col-sm-<?php echo $room_cols; ?>"><?php echo $room_name;  ?></div>
<?php 
	} 
	if ($course_cols != "0") {	
?>	
					<div class="col-sm-<?php echo $course_cols; ?>">
						<?php echo $ui_course_ID . " ". $course_name;  ?>
					</div>
<?php } ?>
					<div class="col-sm-<?php echo $nfc_cols; ?>"><?php echo $nfc_id; ?></div>
					
					<div class="col-sm-1">
						<form method="post" action="list_room_logs.php">
							<input type="hidden" name="id" value="<?php echo $res['ID']; ?>"/>
							<input class="button" type="submit" value="Muokkaa" />
						</form>
					</div>
					<div class="col-sm-1">
						<form method="post" action="inc/sub/room/remove_room_log.php">
							<input type="hidden" name="id" value="<?php echo $res['ID']; ?>"/>
							<input class="button" type="submit" value="Poista" />
						</form>
					</div>
				</div>
<?php		
		}
?>


</div>

<?php
		}
	}
?>