<?php
	$seek_with = get_post_or_get($conn, "seek_with");
	if (isset($seek_with) && $seek_with != "") {
		$courses = get_teacher_courses_for_now($conn, $_SESSION['staff_id']);
		if (count($courses) > 0) {
			$seek_with_value = $courses[0];
		}
?>
		<form id="seek_specific_room_or_course_form" action="list_room_logs.php" method="POST">
			<input type="hidden" name="seek_with" value="<?php echo $seek_with; ?>" />
			<select id="seek_specific_room_or_course" name="<?php echo $seek_with; ?>">
<?php
				$seek_with_value = get_post_or_get($conn, $seek_with);
				if ($seek_with == "course") {
					$sql_courses = "SELECT ID, Course_ID, Course_name FROM ca_course";
					$result = $conn->query($sql_courses);
					while($course_res = $result->fetch_assoc()) {
?>
						<option value="<?php echo $course_res['ID']; ?>" 
							<?php if (isset($seek_with_value) && $seek_with_value == 
									$course_res['ID']) { echo " selected=\"selected\" "; } ?>
						>
							<?php echo $course_res['Course_ID']." ".$course_res['Course_name']; ?>
						</option>
<?php
					}
				} else if ($seek_with == "room") {
					$sql_rooms = "SELECT ID, room_name FROM ca_room";
					$result = $conn->query($sql_rooms);
					while($room_res = $result->fetch_assoc()) {
?>
						<option value="<?php echo $room_res['ID']; ?>" 
							<?php if (isset($seek_with_value) && $seek_with_value == 
									$room_res['ID']) { echo " selected=\"selected\" "; } ?>
						>						
							<?php echo $room_res['room_name']; ?>
						</option>
<?php
					}						
				}
?>
			</select>
		</form>
<?php
	}
?>
