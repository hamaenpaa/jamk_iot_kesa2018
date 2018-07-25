<script src="../../../libs/jquery/jquery-3.3.1.min.js" /></script>
<link rel="stylesheet" type="text/css" href="../../../libs/dtp/jquery.datetimepicker.min.css"/ >
<script src="../../../libs/dtp/jquery.datetimepicker.full.min.js"></script>
<script>
/* 
Last Mod: 19.7.2018 
Title: Datepicker global asetukset 
More info: https://xdsoft.net/jqplugins/datetimepicker/
----------
Asentaa kyseisen valikon classeihin nimeltĆ¤ datetime_picker.
*/
$(function() {
	$.datetimepicker.setLocale('fi');
	jQuery('.datetime_picker').datetimepicker({ 
	datepicker:true,
	allowTimes:[
	'06:00', '06:15', '06:30', '06:45', //6
	'07:00', '07:15', '07:30', '07:45', //7
	'08:00', '08:15', '08:30', '08:45', //8
	'09:00', '09:15', '09:30', '09:45', //9
	'10:00', '10:15', '10:30', '10:45', //10
	'11:00', '11:15', '11:30', '11:45', //11
	'12:00', '12:15', '12:30', '12:45', //12
	'13:00', '13:15', '13:30', '13:45',
	'14:00', '14:15', '14:30', '14:45',
	'15:00', '15:15', '15:30', '15:45',
	'16:00', '16:15', '16:30', '16:45',
	'17:00', '17:15', '17:30', '17:45',
	'18:00', '18:15', '18:30', '18:45',
	'19:00', '19:15', '19:30', '19:45',
	'20:00', '20:15', '20:30', '20:45',  //20
	'21:00'
	],
	format:'d.m.Y H:i' //Finnish format
	});
});
</script>
<script>
$(function() {
$("#lesson_add_mass").prop("disabled", true);

	$("#user_error_check").on("click", function() {
		if ($("#lesson_add_mass").prop("disabled") == true) {
		ds = $("#date_start").val();
		de = $("#date_end").val();
		
		
		mo = $("#lesson_monday").prop("checked");
		tu = $("#lesson_tuesday").prop("checked");
		we = $("#lesson_wednesday").prop("checked");
		th = $("#lesson_thursday").prop("checked");
		fr = $("#lesson_friday").prop("checked");
		
		
		if (mo == true) { mo_msg = "Maanantai "; } else { mo_msg = ""; }
		if (tu == true) { tu_msg = "Tiistai "; } else { tu_msg = ""; }
		if (we == true) { we_msg = "Keskiviikko "; } else { we_msg = ""; }
		if (th == true) { th_msg = "Torstai "; } else { th_msg = ""; }
		if (fr == true) { fr_msg = "Perjantai"; } else { fr_msg = ""; }
		
		message = "Vahvistus\n\n"
		message += "Lisää luennot väliltä: " + ds + " ~ " + de + "";
		message += "\n Joka: " + mo_msg + tu_msg + we_msg + th_msg + fr_msg;
		message += "\n » Voit nyt lähettää luennot palvelimelle.";
		
		
		alert(message);
		$("#lesson_add_mass").prop("disabled", false)
		} else {
		$("#lesson_add_mass").prop("disabled", true);	
		}
	});

});
</script>


<form method="GET" action="lessons_add.php">
<input placeholder="Aloitusaika + Tunti" size="16" alt="Päivämäärä suomalaisessa muodossa esim: 31.07.2018 07:00" id="date_start" class="datetime_picker" name="lesson_start_date">
<input placeholder="Lopetusaika + Tunti" size="16" alt="Päivämäärä suomalaisessa muodossa esim: 31.07.2018 08:00" id="date_end" class="datetime_picker" name="lesson_end_date">

<br><br>
<label for="lesson_room">Luokka</label>
<select name="lesson_room">
<option value="0">Valitse</option>
<option value="4">LXO-305</option>
<option value="7">EFF-106</option>
</select>

<label for="lesson_course">Luento</label>
<select name="lesson_course">
<option value="0">Valitse</option>
<option value="23">Venäjä - Alkeet</option>
<option value="734">Kiina - Kehittynyt</option>
</select>

<br><br>

<label for="lesson_monday">Ma</label><input id="lesson_monday" type="checkbox" name="lesson_monday">
<label for="lesson_tuesday">Ti</label><input id="lesson_tuesday" type="checkbox" name="lesson_tuesday">
<label for="lesson_wednesday">Ke</label><input id="lesson_wednesday" type="checkbox" name="lesson_wednesday">
<label for="lesson_thursday">To</label><input id="lesson_thursday" type="checkbox" name="lesson_thursday">
<label for="lesson_friday">Pe</label><input id="lesson_friday" type="checkbox" name="lesson_friday">
<p><label for="user_error_check" >Olen varmistanut aikavälin, kellonajan, luokan ja luennon.</label><input id="user_error_check" name="user_error_check" type="checkbox"><br><input id="lesson_add_mass" type="submit"></p>
</form>

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

if (isset($_GET['lesson_start_date']) && isset($_GET['lesson_end_date']) && isset($_GET['lesson_course']) && isset($_GET['lesson_room']) && $_GET['lesson_course'] != 0 && $_GET['lesson_room'] != 0) {


include "../../db_connect_inc.php";

$course = $conn->real_escape_string(intval($_GET['lesson_course'])); //Get course from post
$room = $conn->real_escape_string(($_GET['lesson_room'])); //Get room from post


//die($_GET['lesson_start_date'] . "<hr>" . $_GET['lesson_end_date']);

$lesson_sdE = $conn->real_escape_string($_GET['lesson_start_date']);
$lesson_edE = $conn->real_escape_string($_GET['lesson_end_date']);
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
	if (isset($_GET['lesson_monday'])) {
	$get_mondays = getDateForSpecificDayBetweenDates($lesson_start_date, $lesson_end_date, 1);
		foreach ($get_mondays as $monday) {
		$query_values .= "($room,$course,'" . $monday . " $lesson_start_date_his','" . $monday . " $lesson_end_date_his'),";	
		}
	}
	
	/* Filters through tuesdays if exists */
	if (isset($_GET['lesson_tuesday'])) {
	$get_tuesdays = getDateForSpecificDayBetweenDates($lesson_start_date, $lesson_end_date, 2);
		foreach ($get_tuesdays as $tuesday) {
		$query_values .= "($room,$course,'" . $tuesday . " $lesson_start_date_his','" . $tuesday . " $lesson_end_date_his'),";	
		}
	}	

	/* Filters through wednesdays if exists */
	if (isset($_GET['lesson_wednesday'])) {
	$get_wednesdays = getDateForSpecificDayBetweenDates($lesson_start_date, $lesson_end_date, 3);
		foreach ($get_wednesdays as $wednesday) {
		$query_values .= "($room, $course,'" . $wednesday . " $lesson_start_date_his','" . $wednesday . " $lesson_end_date_his'),";	
		}
	}		
	
	/* Filters through thursdays if exists */
	if (isset($_GET['lesson_thursday'])) {
	$get_thursdays = getDateForSpecificDayBetweenDates($lesson_start_date, $lesson_end_date, 4);
		foreach ($get_thursdays as $thursday) {
		$query_values .= "($room,$course,'" . $thursday . " $lesson_start_date_his','" . $thursday . " $lesson_end_date_his'),";	
		}
	}	
	
	/* Filters through fridays if exists */
	if (isset($_GET['lesson_friday'])) {
	$get_fridays = getDateForSpecificDayBetweenDates($lesson_start_date, $lesson_end_date, 5);
		foreach ($get_fridays as $friday) {
		$query_values .= "($room, $course,'" . $friday . " $lesson_start_date_his','" . $friday . " $lesson_end_date_his'),";	
		}
	}	
	
	$query_values = substr($query_values, 0, -1); //Removes the last comma not used
	$query_string = "INSERT INTO ca_lesson (course_id, room_id, begin_time, end_time) VALUES $query_values";
	
	/*
		if ($res_update_lessons = $conn->query($query_string)) {
		echo "Success";
		} else {
		echo "err-X";	
		}
	*/
	echo "<hr>";
	echo "Query values: " . $query_string;
	} else {
	echo "Invalid Dates";	
	}

}
?>

