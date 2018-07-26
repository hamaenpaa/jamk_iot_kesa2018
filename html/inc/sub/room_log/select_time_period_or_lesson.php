<?php 
	if (isset($seek_with) && $seek_with != "") {
		if ($seek_with == "course") {
			$course = get_post_or_get($conn, "course");
		}
		else if ($seek_with == "room") {
			$room = get_post_or_get($conn, "room");
		}
		if ((isset($room) && $room != "") || (isset($course) && $course != "")) {
?>
			<form>
				<input  
					placeholder="Aloitusaika" size="16" 
					alt="Päivämäärä suomalaisessa muodossa esim: 31.07.2018 07:00" 
					id="date_start" class="datetime_picker"
					name="begin_time" />
				<input 
					placeholder="Lopetusaika" size="16" 
					alt="Päivämäärä suomalaisessa muodossa esim: 31.07.2018 07:00" 
					id="date_end" class="datetime_picker"
					name="end_time" />
<?php
				if ($seek_with == "course") {
					echo hidden_input("course", $course);
				} else {
					echo hidden_input("room", $room);
				}
				echo hidden_input("seek_with", $seek_with);
				$sql_lessons = "SELECT ca_lesson.ID, ca_lesson.room_id, 
								ca_lesson.course_id, 
								ca_lesson.begin_time, ca_lesson.end_time ";
				$sql_lessons .= ", ca_course.course_ID, ca_course.course_name, ca_room.room_name FROM 
								 ca_lesson, ca_course, ca_room WHERE 
								 ca_lesson.course_id = ca_course.id AND 
								 ca_lesson.room_id = ca_room.id AND ";								
				if (isset($room) && $room != "") {
					$sql_lessons .= "ca_lesson.room_id = ?";
				} else {
					$sql_lessons .= "ca_lesson.course_id = ?";	
				}
				$sql_lessons .= " AND ca_lesson.removed = 0 ";
				$q_lessons = $conn->prepare($sql_lessons);
				if (isset($room) && $room != "") {
					$q_lessons->bind_param("i", $room);
				} else {
					$q_lessons->bind_param("i", $course);
				}
				$q_lessons->execute();		
				$q_lessons->store_result();				
				$q_lessons->bind_result(
					$lesson_id, $room_id, $course_id, $begin_time, $end_time, 
					$ui_course_ID, $course_name, $room_name);				
				
				if ($q_lessons->num_rows > 0) {
?>
					<select name="lesson">
<?php					
						while($lessons = $q_lessons->fetch_assoc())	{
?>
							<option value="<?php echo $lesson_id; ?>" >
<?php
								if (isset($room) && $room != "") {
									echo $ui_course_ID . " ". $course_name;
								}
								else {
									echo $room_name;
								}
								echo " ".$begin_time - $end_time;
?>
							</option>
<?php							
						}
?>
					</select>
<?php					
				}
?>
			<input type="submit" value="Valitse"/>
		</form>
<?php
		}
	}
?>