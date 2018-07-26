<?php
	$begin_time = get_post_or_get($conn, "begin_time");
	$end_time = get_post_or_get($conn, "end_time");
	$lesson = get_post_or_get($conn, "lesson");
	if ((isset($begin_time) && isset($end_time) &&
		$begin_time != "" && $end_time != "") ||
		(isset($lesson) && $lesson != "")) {
		if (!isset($begin_time) || !isset($end_time) ||
			$begin_time == "" || $end_time == "") {
			$sql_times = "SELECT ca_lesson.begin_time, ca_lesson.end_time WHERE ID = ?";
			$q_times = $conn->prepare($sql_lessons);
			$q_times->bind_param("i", $lesson);	
			$q_times->execute();		
			$q_times->store_result();				
			$q_times->bind_result($begin_time, $end_time);
			$q_times->fetch_assoc();			
		}
		if ($seek_with == "course") {
			$course = get_post_or_get($conn, "course");
		}
		else if ($seek_with == "room") {
			$room = get_post_or_get($conn, "room");
		}		
		
		$sql_room_log = "SELECT ca_roomlog.ID, ca_roomlog.NFC_ID, ca_roomlog.dt,
					     ca_guest.firstName, ca_guest.lastName, 
						 ca_course.course_ID, ca_course.course_name,
						 ca_student.student_id, ca_student.firstName, ca_student.lastName,
						 ca_room.room_name FROM ca_roomlog ";
		$sql_room_log .= "INNER JOIN ca_course ON ca_roomlog.course_id = ca_course.ID  
						  INNER JOIN ca_room ON ca_roomlog.room_id = ca_room.ID  
						  LEFT JOIN ca_student ON ca_student.ID = ca_roomlog.student_id
						  LEFT JOIN ca_guest ON ca_guest.ID = ca_roomlog.guest_id ";
		// $sql_room_log .= "WHERE ca_roomlog.removed = 0 ";
		$sql_room_log .= "AND ca_roomlog.dt >= ? AND ca_roomlog.dt <= ? AND ";
		if ($seek_with == "course") {
			$sql_room_log .= " ca_roomlog.course_id = ?";
		} else {
			$sql_room_log .= " ca_roomlog.room_id = ?";
		}
		
		$q_room_logs = $conn->prepare($sql_room_log);
		if (isset($room) && $room != "") {
			$q_room_logs->bind_param("ssi", $begin_time, $end_time, $room);
		} else {
			$q_room_logs->bind_param("ssi", $begin_time, $end_time, $course);
		}
		$q_room_logs->execute();		
		$q_room_logs->store_result();				
		$q_room_logs->bind_result(
			$room_log_id, $nfc_id, $dt,
			$guest_first_name, $guest_last_name,
			$ui_course_ID, $course_name,          
			$student_id, $student_first_name, $student_last_name,
			$room_name);			
		if ($q_room_logs->num_rows > 0) {	
		
?>

<div id="room_log_listing_table">
	
	
	
<?php
			while($room_logs = $q_room_logs->fetch_assoc())	{
?>
				<div class="row">
						
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
					<div class="col-sm-2"><?php echo $dt; ?></div>
					
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