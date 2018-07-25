<?php
if (!isset($_SESSION)) {
session_start();	
}
if (isset($_SESSION['staff_permlevel']) && $_SESSION['staff_permlevel'] == 0) { //Validate permission levels
	if (isset($course_id)) { unset($course_id); } //Removes CourseID variable if it exists, not really necessary
	
	echo "<form method='POST' action='" . $_SERVER['PHP_SELF'] . "'>";
		if (!isset($conn)) {
		include "inc/db_connect_inc.php";	
		}
		
		
		
	?>
	<h2>Tunti Haku</h2>
	<div class="row">
	<div class="col-sm-2"><b>Aloitus aika</b></div>
	<div class="col-sm-2"><b>Lopetus aika</b></div>
	<div class="col-sm-1"><b>Luokka</b></div>
	<div class="col-sm-1"><b>Kurssit</b></div>
	<div class="col-sm-2"><b>Etsi/Lisää</b></div>
	<div class="col-sm-1"></div>
	</div>
				
	<div class="row">
	<div class="col-sm-2"><input name="lesson_date_start" class="datetime_picker" value="<?php if (isset($_POST['lesson_date_start'])) { echo $_POST['lesson_date_start'];	} ?>" size="16"></div>
	<div class="col-sm-2"><input name="lesson_date_end" class="datetime_picker" size="16" value="<?php if (isset($_POST['lesson_date_end'])) { echo $_POST['lesson_date_start'];	} ?>"></div>
	<div class="col-sm-1">
	<?php
	if ($res_get_roomnames = $conn->prepare("SELECT ID, room_name FROM ca_room ORDER BY room_name ASC")) {
		if ($res_get_roomnames->execute()) {
		$res_get_roomnames->bind_result($room_id, $room_name);
		echo "<select name='lesson_room'>";
		echo "<option value='0'>Valitse</option>";
			while ($res_get_roomnames->fetch()) {
				
				if (isset($_POST['lesson_room']) && $_POST['lesson_room'] == $room_id) {
				echo "<option value='$room_id' selected>$room_name</option>";
				} else {
				echo "<option value='$room_id'>$room_name</option>";	
				}
				
			}
		echo "</select>";
		} else {
		//ERROR->$res_get_roomnames->EXECUTE	
		}
	} else {
		echo "sx";
	//ERROR->$res_get_roomnames->PREPARE
	}
	?>
	</div>
	
	
	<div class="col-sm-1">
	<?php
	if ($res_get_courses_list = $conn->prepare("SELECT ca_course_teacher.course_id, ca_course.course_name FROM ca_course 
		INNER JOIN ca_course_teacher WHERE ca_course.ID = ca_course_teacher.course_id AND ca_course_teacher.staff_id = ?")) {
		$res_get_courses_list->bind_param("s",$_SESSION['staff_id']);	
		if ($res_get_courses_list->execute()) {
		$res_get_courses_list->bind_result($course_id, $course_name);
		echo "<select name='lesson_course'>";
		echo "<option value='0'>Valitse</option>";
			while ($res_get_courses_list->fetch()) {
				
			
				if (isset($_POST['lesson_course']) && $_POST['lesson_course'] == $course_id) {
				echo "<option value='$course_id' selected>$course_name</option>";
				} else {
				echo "<option value='$course_id'>$course_name</option>";	
				}
			
			}
		echo "</select>";
		} else {
		//ERROR->$res_get_courses_list->EXECUTE	
		}
	} else {
		echo "sx";
	//ERROR->$res_get_courses_list->PREPARE
	}
	?>
	</div>
	<div class="col-sm-2"><select name="method"><option value="0">Etsi</option><option value="1">Lisää</option></select></div>
	<div class="col-sm-1"><input type="submit" value="Suorita"></div>
	</div></form>
	
	<?php
	if (isset($_POST['lesson_date_start']) && isset($_POST['lesson_date_end']) && 
	!empty($_POST['lesson_date_start']) && !empty($_POST['lesson_date_end']) && 
	isset($_POST['method'])
	) {
	 
	//Our YYYY-MM-DD date.
	$start_date_fin = $_POST['lesson_date_start'];
	$dateObj_start= DateTime::createFromFormat('d.m.Y H:i', $start_date_fin);
	$newDateString_start = $dateObj_start->format('Y-m-d H:i');
	$start_date_converted = $newDateString_start . ":00";
	
	$end_date_fin = $_POST['lesson_date_end'];
	$dateObj_end = DateTime::createFromFormat('d.m.Y H:i', $end_date_fin);
	$newDateString_end = $dateObj_end->format('Y-m-d H:i');
	$end_date_converted = $newDateString_end . ":00";;
	
	
	//$start_date_converted;
	//$end_date_converted;
	
			$query_select = "SELECT ca_lesson.id, ca_lesson.room_id, ca_lesson.course_id, ca_lesson.begin_time, ca_lesson.end_time FROM 
			ca_lesson INNER JOIN ca_course_teacher ON ca_course_teacher.course_id = ca_lesson.course_id
			WHERE ca_course_teacher.staff_id = ? AND";
			
			if (isset($_POST['lesson_room']) && $_POST['lesson_room'] != 0) {
			$room = $_POST['lesson_room'];
			$query_select .= " ca_lesson.room_id = ? AND ";
			} else {
			$room = false;
			}
			
			if (isset($_POST['lesson_course']) && $_POST['lesson_course'] != 0) {
			$course = $_POST['lesson_course'];
			$query_select .= " ca_lesson.course_id = ? AND ";
			} else {
			$course = false;
			}
	
			if (!isset($conn)) {
			include "inc/db_connect_inc.php";	
			}
			
		
			
			$query_select .= " ca_lesson.begin_time >= ? AND ca_lesson.end_time <= ?";
			
			
			if ($res_get_lesson = $conn->prepare($query_select)) {
				/* BRAINSTORMING */ 	
				if ($room != false && $course != false) { //All 4 exists
				$res_get_lesson->bind_param("iiiss",$_SESSION['staff_id'],$room,$course,$start_date_converted,$end_date_converted);
				} else if ($room != false && $course == false) { //Lesson exists, course doesnt
				$res_get_lesson->bind_param("iiss",$_SESSION['staff_id'],$room,$start_date_converted,$end_date_converted);
				} else if ($room == false && $course != false) { //Course exists, lesson doesnt
				$res_get_lesson->bind_param("iiss",$_SESSION['staff_id'],$course,$start_date_converted,$end_date_converted);
				} else { //Neither lesson or course exists
				$res_get_lesson->bind_param("iss",$_SESSION['staff_id'],$start_date_converted,$end_date_converted);
				}
				
				
				//echo "<hr>";
				//echo $room . " - " .  $course . " - " .  $start_date_converted . " - " .  $end_date_converted;
				//echo "<hr>";
				
				if ($res_get_lesson->execute()) {
					$res_get_lesson->store_result();
					
					$rows_get_lesson = $res_get_lesson->num_rows();
					
					if ($rows_get_lesson > 0 ) {
					
						$res_get_lesson->bind_result($lesson_id, $lesson_room_id, $lessons_course_id, $lesson_begin_time, $lesson_end_time);
						

						
						echo "<h2>Hakutulokset</h2>";
						while ($res_get_lesson->fetch()) {
						
							$convert_date_eng_fin_1 = $lesson_begin_time;
							$dateObj_start= DateTime::createFromFormat('Y-m-d H:i:s', $convert_date_eng_fin_1);
							$date_start_eng_to_fin_1 = $dateObj_start->format('d.m.Y H:i:s');
						
		
							$convert_date_eng_fin_2 = $lesson_end_time;
							$dateObj_start= DateTime::createFromFormat('Y-m-d H:i:s', $convert_date_eng_fin_2);
							$date_start_eng_to_fin_2 = $dateObj_start->format('d.m.Y H:i:s');
							
							
								if (!isset($lesson_course_id)) {
								$lesson_course_id = "(Tyhjä)";	
								}
								if (!isset($lesson_room_id)) {
								$lesson_room_id = "(Tyhjä)";	
								}
						
						
						echo "Luennon ID: " . $lesson_id . " | Huone: " . $lesson_room_id . " | Kurssi: " . $lesson_course_id . " | " . $date_start_eng_to_fin_1 . " ~ " . $date_start_eng_to_fin_2 . " <input type='button' value='Poista'>";
						}
					} else {
					
					//echo "<hr>";
					//echo $query_select;
					//echo "<br>";
					//echo "$start_date_converted ~ $end_date_converted";
					//echo "<hr>";
					
					
					if (!isset($room) || $room == false) { $room = "(Tyhjä)"; }
					if (!isset($course) || $course == false ) { $course = "(Tyhjä)"; }	
						
					echo "<h2>Haun Tulokset</h2>";
					echo "<p>Tuloksia haulle ei löytynyt.</p><hr>";
					echo "<h4>Hakuparametrit</h4>
					<ul>
					<li><b>Aikaväli</b>: $start_date_fin ~ $end_date_fin</li>
					<li><b>Luokka</b>: $room</li>
					<li><b>Kurssi</b>: $course</li>
					</ul>";
					}
					
					
				} else {
					echo "ERROR-EXECUTE";
				//ERROR->$res_get_lessons->EXECUTE	
				}
				
				
				
				
			} else {
				//echo $query_select;
				echo "asd";
			//ERROR->$res_get_lesson->PREPARE
			//echo $query_select  -> troubleshoot
			}
			
	}
	?>

	
	
	
<?php
}
?>