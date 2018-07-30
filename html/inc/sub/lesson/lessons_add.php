
<script src="js/datepicker.js"></script>
<script src="js/lessons_mass_add.js"></script>

<?php
echo '
<form method="POST" action="' . $_SERVER['PHP_SELF']	 . '">
<input placeholder="Aloitusaika + Tunti" size="16" alt="Päivämäärä suomalaisessa muodossa esim: 31.07.2018 07:00" id="date_start" class="datetime_picker" name="lesson_start_date">
<input placeholder="Lopetusaika + Tunti" size="16" alt="Päivämäärä suomalaisessa muodossa esim: 31.07.2018 08:00" id="date_end" class="datetime_picker" name="lesson_end_date">
<br><br>
<label for="list_rooms">Luokka</label>';
include 'list_rooms.php';
echo '<label for="lesson_course">Luento</label>';
include 'list_courses.php';
echo '
<br><br>
<label for="lesson_monday">Ma</label><input id="lesson_monday" type="checkbox" name="lesson_monday">
<label for="lesson_tuesday">Ti</label><input id="lesson_tuesday" type="checkbox" name="lesson_tuesday">
<label for="lesson_wednesday">Ke</label><input id="lesson_wednesday" type="checkbox" name="lesson_wednesday">
<label for="lesson_thursday">To</label><input id="lesson_thursday" type="checkbox" name="lesson_thursday">
<label for="lesson_friday">Pe</label><input id="lesson_friday" type="checkbox" name="lesson_friday">
<p><label for="user_error_check" >Olen varmistanut aikavälin, kellonajan, luokan ja luennon.</label><input id="user_error_check" name="user_error_check" type="checkbox"><br><input id="lesson_add_mass" type="submit"></p>
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

if (isset($_POST['lesson_start_date']) && isset($_POST['lesson_end_date']) && isset($_POST['lesson_course']) && isset($_POST['lesson_room']) && $_POST['lesson_course'] != 0 && $_POST['lesson_room'] != 0) {

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
		echo "succ";
		//header("Location: list_lessons.php");
		} else {
		echo "err-X";	
		}
	
	//echo "<hr>";
	//echo "Query values: " . $query_string;
	} else {
	echo "Invalid Dates";	
	}

}
?>

