<?php
if (!isset($_SESSION)) {
session_start();	
}
if (isset($_SESSION['staff_permlevel']) && $_SESSION['staff_permlevel'] == 0 || isset($_SESSION['staff_permlevel']) && $_SESSION['staff_permlevel'] == 1) { //Validate permission levels
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
	<div class="col-sm-2"><b>Luokka</b></div>
	<div class="col-sm-2"><b>Kurssit</b></div>
	<div class="col-sm-2"><b>Hae</b></div>
	</div>
				
	<div class="row">
	<div class="col-sm-2"><input name="lesson_date_start" class="datetime_picker" value="<?php if (isset($_POST['lesson_date_start'])) { echo $_POST['lesson_date_start'];	} ?>" size="16"></div>
	<div class="col-sm-2"><input name="lesson_date_end" class="datetime_picker" size="16" value="<?php if (isset($_POST['lesson_date_end'])) { echo $_POST['lesson_date_end'];	} ?>"></div>
	<div class="col-sm-2">
	<?php
	if (!isset($_POST['Lesson_fetch'])) {
	$room_prefill = false;
	$course_prefill = false;	
	} else {
	$room_prefill = true;
	$course_prefill = true;		
	}
	include 'inc/sub/lesson/list_rooms.php';
	?>
	</div>
	
	
	<div class="col-sm-2">
	<?php
	include 'inc/sub/lesson/list_courses.php';
	?>
	</div>
	<div class="col-sm-2"><input type="submit" name="Lesson_fetch" value="Suorita"></div>
	</div></form>
	<?php
	/* Remove Lesson Script */
	if(isset($_POST['check_list']) && !empty($_POST['check_list'])) {
		if (!isset($conn)) {
		include "inc/db_connect_inc.php";
		}
		
		echo "<br>";
		$count = 0;
		foreach($_POST['check_list'] as $check) {	
		 	if ($remLes = $conn->prepare("DELETE ca_lesson FROM ca_lesson
				INNER JOIN ca_course_teacher ON ca_lesson.course_id = ca_course_teacher.course_id
				WHERE ca_lesson.id = ? AND ca_course_teacher.staff_id = ?")) {
			$remLes->bind_param("ii", $check, $_SESSION['staff_id']);
				
				if ($remLes->execute()) {
				$count++;
				} else {
				//$remLes->ERROR->EXECUTE	
				}
			} else {
			//$remLes->ERROR->PREPARE	
			}
		}
		echo "<pre>" .  $count . " luentoa on poistettu onnistuneesti.</pre>";
	//echo "<pre>Array of checkbox values:\n"; 
    //print_r($_POST["RemLesson"]); 
    //echo "</pre>"; 
	}
	
	
	if (isset($_POST['lesson_date_start']) && isset($_POST['lesson_date_end']) && 
	!empty($_POST['lesson_date_start']) && !empty($_POST['lesson_date_end'])) {
	 
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
				
				/*
				echo "<hr>";
				echo $_SESSION['staff_id'];
				echo "<hr>";
				*/
				
				if ($rows_get_lesson > 0) {
					$res_get_lesson->bind_result($lesson_id, $lesson_room_id, $lessons_course_id, $lesson_begin_time, $lesson_end_time);
					
					echo "<h2>Hakutulokset</h2>";
					echo "<form style='' method='POST' action='" . $_SERVER['PHP_SELF'] . "'>";
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
						
					echo "<p style='margin-top:4px;vertical-align:middle;'><input type='checkbox' value='$lesson_id' name='check_list[]'>Luennon ID: " . $lesson_id . " | Huone: " . $lesson_room_id . " | Kurssi: " . $lesson_course_id . " | " . $date_start_eng_to_fin_1 . " - " . $date_start_eng_to_fin_2 . "<input type='hidden' name='RemLesson' value='" . $lesson_id . "'></p>";
					}
					echo "<input type='Submit' value='Remove Selected'></form>";
				} else {
					
				/*
				echo "<hr>";
				echo $query_select;
				echo "<br>";
				echo "$start_date_converted ~ $end_date_converted";
				echo "<hr>";
				*/
				
				/* 
				SELECT ca_lesson.id, ca_lesson.room_id, ca_lesson.course_id, ca_lesson.begin_time, ca_lesson.end_time FROM ca_lesson 
				INNER JOIN ca_course_teacher ON ca_course_teacher.course_id = ca_lesson.course_id 
				WHERE ca_course_teacher.staff_id = ? AND ca_lesson.begin_time >= ? AND ca_lesson.end_time <= ?
				
				Between: 2018-02-27 10:06:00 ~ 2019-03-30 10:06:00
				*/
				
					
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
}
?>