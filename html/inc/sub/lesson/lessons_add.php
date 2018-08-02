<?php
if (!isset($_SESSION)) {
session_start();	
}

if (isset($_SESSION['staff_id'])) {
?>
	<script>
	$(function() {
		$.datetimepicker.setLocale('fi');
		jQuery('.datetime_picker2').datetimepicker({
		timepicker:false,
		format:'d.m.Y' //Finnish format
		});
	});
	</script>
	<script src="js/lessons_mass_add.js"></script>

	<?php
	echo '
	<h2>Lisää luentoja</h2>
	<form method="POST" action="' . $_SERVER['PHP_SELF']	 . '">
	<input placeholder="Aloitus pvm" size="16" alt="Päivämäärä suomalaisessa muodossa esim: 31.07.2018 07:00" id="date_start" class="datetime_picker2" name="lesson_start_date">
	<input placeholder="Lopetus pvm + Tunti" size="16" alt="Päivämäärä suomalaisessa muodossa esim: 31.07.2018 08:00" id="date_end" class="datetime_picker2" name="lesson_end_date">
	<br><br>';
	$timespan = array("06:00","06:15","06:30","06:45","07:00","07:15","07:30","07:45","08:00","08:15","08:30","08:45","09:00","09:15","09:30","09:45","10:00","10:15","10:30","10:45",
					"11:00","11:15","11:30","11:45","12:00","12:15","12:30","12:45","13:00","13:15","13:30","13:45","14:00","14:15","14:30","14:45","15:00","15:15","15:30","15:45",
					"16:00","16:16","16:30","16:45","17:00","17:17","17:30","17:45","18:00","18:18","18:30","18:45","19:00","19:19","19:30","19:45","20:00","20:20","20:30","20:45","21:00"
					);
	echo 'Klo: <select id="date_start_h" name="lesson_start_time_h">';
	foreach($timespan as $time) {
		if ($time == '08:00') {
		echo "<option selected value='$time'>$time</option>";	
		} else {
		echo "<option value='$time'>$time</option>";	
		}	
	}
	echo '</select>';

	echo ' - <select id="date_end_h" name="lesson_end_time_h">';
	foreach($timespan as $time) {
		if ($time == '09:00') {
		echo "<option selected value='$time'>$time</option>";	
		} else {
		echo "<option value='$time'>$time</option>";	
		}
	}
	echo '</select>';

	echo '
	<br><br>
	<label for="list_rooms">Luokka</label>';
	//isset($room_prefill) && $room_prefill == true
	if (!isset($_POST['Lesson_add'])) {
	$room_prefill = false;
	$course_prefill = false;	
	} else {
	$room_prefill = true;
	$course_prefill = true;		
	}
	include 'list_rooms.php';
	echo '<label for="lesson_course">Kurssi</label>';
	include 'list_courses.php';
	echo '<br><br>
	<label for="lesson_monday">Ma</label><input id="lesson_monday" type="checkbox" name="lesson_monday">
	<label for="lesson_tuesday">Ti</label><input id="lesson_tuesday" type="checkbox" name="lesson_tuesday">
	<label for="lesson_wednesday">Ke</label><input id="lesson_wednesday" type="checkbox" name="lesson_wednesday">
	<label for="lesson_thursday">To</label><input id="lesson_thursday" type="checkbox" name="lesson_thursday">
	<label for="lesson_friday">Pe</label><input id="lesson_friday" type="checkbox" name="lesson_friday">
	<p><label for="user_error_check" >Olen varmistanut aikavälin, kellonajan, luokan ja luennon.</label><input id="user_error_check" name="user_error_check" type="checkbox"><br><input name="Lesson_add" id="lesson_add_mass" type="submit"></p>
	</form>';
	?>

	<?php
	function getDateForSpecificDayBetweenDates($startDate,$endDate,$day_number){
	$endDate = strtotime($endDate);
	$days=array('1'=>'Monday','2' => 'Tuesday','3' => 'Wednesday','4'=>'Thursday','5' =>'Friday','6' => 'Saturday','7'=>'Sunday');
	for($i = strtotime($days[$day_number], strtotime($startDate)); $i <= $endDate; $i = strtotime('+1 week', $i))
	$date_array[]=date('Y-m-d',$i);
	return $date_array;
	}


	function validateDate($date, $format = 'Y-m-d H:i:s') {
	$d = DateTime::createFromFormat($format, $date);
	return $d && $d->format($format) == $date;
	}


	/*
	$start = date("Y-m-d H:i:s");
	$end = date("2018-08-24 00:00:00");
	$list_mondays = getDateForSpecificDayBetweenDates($start,$end,1);
	*/

	if (isset($_POST['lesson_start_date']) && isset($_POST['lesson_end_date']) && isset($_POST['lesson_course']) && isset($_POST['lesson_room']) && $_POST['lesson_course'] != 0 && $_POST['lesson_room'] != 0 && isset($_POST['lesson_start_time_h']) && isset($_POST['lesson_end_time_h'])) {
	if (!isset($conn)) {
	include "../../db_connect_inc.php";
	}

	$course = $conn->real_escape_string(intval($_POST['lesson_course'])); //Get course from post
	$room = $conn->real_escape_string((intval($_POST['lesson_room']))); //Get room from post

	/* Validate if teacher is on right course before inserting mass data. */

	if ($get_teacher_status = $conn->prepare("SELECT * FROM ca_course_teacher INNER JOIN ca_course WHERE ca_course.id=ca_course_teacher.course_id AND ca_course.teacher.staff_id = ''")) {
		
	} else {
	//ERROR-PREPARE	
	}





	//die($_GET['lesson_start_date'] . "<hr>" . $_GET['lesson_end_date']);
	$lesson_sdE = $conn->real_escape_string($_POST['lesson_start_date']);
	$lesson_edE = $conn->real_escape_string($_POST['lesson_end_date']);
	$lesson_sdE .= " " . $conn->real_escape_string($_POST['lesson_start_time_h']);
	$lesson_edE .= " " . $conn->real_escape_string($_POST['lesson_end_time_h']);

	/* TROUBLESHOOTING 
	echo $lesson_sdE;
	echo "<hr>";
	echo $lesson_edE;
	echo "<hr>";
	*/

	//Our YYYY-MM-DD H:I date.
	$start_date_fin = $lesson_sdE;
	$dateObj_start= DateTime::createFromFormat('d.m.Y H:i', $start_date_fin);
	$newDateString_start = $dateObj_start->format('Y-m-d H:i');

	$end_date_fin = $lesson_edE;
	$dateObj_end = DateTime::createFromFormat('d.m.Y H:i', $end_date_fin);
	$newDateString_end = $dateObj_end->format('Y-m-d H:i');


	$lesson_sd = $newDateString_start . ":00";
	$lesson_ed = $newDateString_end . ":00";

	/*
	TROUBLESHOOTING HELP
	echo "<hr>";
	echo $lesson_sd;
	echo "<br>";
	echo $_GET['lesson_end_date'];
	echo "<hr>";
	*/

		if (validateDate($lesson_sd) == true && validateDate($lesson_ed) == true) {
		$lesson_start_date = $lesson_sd;
		$lesson_start_date_his = substr($lesson_start_date, -8);

		$lesson_end_date = $lesson_ed;
		$lesson_end_date_his = substr($lesson_end_date, -8);

		$query_values = ""; //LET THE VARIABLE BUILD

		/* Filters through mondays if exists */
		if (isset($_POST['lesson_monday'])) {
		$get_mondays = getDateForSpecificDayBetweenDates($lesson_start_date, $lesson_end_date, 1);
			foreach ($get_mondays as $monday) {
			$query_values .= "($room,$course,'" . $monday . " $lesson_start_date_his','" . $monday . " $lesson_end_date_his'),";	
			}
		}
		
		/* Filters through tuesdays if exists */
		if (isset($_POST['lesson_tuesday'])) {
		$get_tuesdays = getDateForSpecificDayBetweenDates($lesson_start_date, $lesson_end_date, 2);
			foreach ($get_tuesdays as $tuesday) {
			$query_values .= "($room,$course,'" . $tuesday . " $lesson_start_date_his','" . $tuesday . " $lesson_end_date_his'),";	
			}
		}	

		/* Filters through wednesdays if exists */
		if (isset($_POST['lesson_wednesday'])) {
		$get_wednesdays = getDateForSpecificDayBetweenDates($lesson_start_date, $lesson_end_date, 3);
			foreach ($get_wednesdays as $wednesday) {
			$query_values .= "($room, $course,'" . $wednesday . " $lesson_start_date_his','" . $wednesday . " $lesson_end_date_his'),";	
			}
		}		
		
		/* Filters through thursdays if exists */
		if (isset($_POST['lesson_thursday'])) {
		$get_thursdays = getDateForSpecificDayBetweenDates($lesson_start_date, $lesson_end_date, 4);
			foreach ($get_thursdays as $thursday) {
			$query_values .= "($room,$course,'" . $thursday . " $lesson_start_date_his','" . $thursday . " $lesson_end_date_his'),";	
			}
		}	
		
		/* Filters through fridays if exists */
		if (isset($_POST['lesson_friday'])) {
		$get_fridays = getDateForSpecificDayBetweenDates($lesson_start_date, $lesson_end_date, 5);
			foreach ($get_fridays as $friday) {
			$query_values .= "($room, $course,'" . $friday . " $lesson_start_date_his','" . $friday . " $lesson_end_date_his'),";	
			}
		}	
		
		$query_values = substr($query_values, 0, -1); //Removes the last comma not used
		$query_string = "INSERT INTO ca_lesson (course_id, room_id, begin_time, end_time) VALUES $query_values";
		
		
			if ($res_update_lessons = $conn->query($query_string)) {
			echo "<pre>Olet lisännyt luennot onnistuneesti sivuille</pre>";
			//header("Location: list_lessons.php");
			} else {
			echo "err-X";	
			}
		
		//echo "<hr>";
		//echo "Query values: " . $query_string;
		} else {
		echo "Invalid Dates";	
		}

	} else {
	/*
	TROUBLESHOOTING
	echo "<pre>Start Date: " . $_POST['lesson_start_date'] . "</pre>";
	echo "<pre>End Date: " . $_POST['lesson_end_date'] . "</pre>";
	echo "<pre>Course: " . $_POST['lesson_course'] . "</pre>";
	echo "<pre>Room: " . $_POST['lesson_room'] . "</pre>";
	echo "<pre>Start Hour: " . $_POST['lesson_start_time_h'] . "</pre>";
	echo "<pre>End Hour: " . $_POST['lesson_end_time_h'] . "</pre>";
	*/
	}
} else {
echo "<p>Login required</p>";	
}
?>

