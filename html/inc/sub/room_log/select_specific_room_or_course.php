<?php
	$seek_with = get_post_or_get($conn, "seek_with");
	if (isset($seek_with) && $seek_with != "") {
?>
		<form action="list_room_logs.php" method="POST">
			<input type="hidden" name="seek_with" value="<?php echo $seek_with; ?>" />
			<select name="<?php echo $seek_with; ?>">
<?php
				if ($seek_with == "course") {
					$sql_courses = "SELECT ID, Course_ID, Course_name FROM ca_course";
					$result = $conn->query($sql_courses);
					while($course_res = $result->fetch_assoc()) {
?>
						<option value="<?php echo $course_res['ID']; ?>" >
							<?php echo $course_res['Course_ID']." ".$course_res['Course_name']; ?>
						</option>
<?php
					}
				} else if ($seek_with == "room") {
					$sql_rooms = "SELECT ID, room_name FROM ca_room";
					$result = $conn->query($sql_rooms);
					while($room_res = $result->fetch_assoc()) {
?>
						<option value="<?php echo $room_res['ID']; ?>" >
							<?php echo $room_res['room_name']; ?>
						</option>
<?php
					}						
				}
?>
			</select>
			<input type="submit" value="Valitse" />
		</form>
<?php
	}
?>
