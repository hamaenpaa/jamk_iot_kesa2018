<?php
	include("../../db_connect_inc.php");
	include("../../utils/request_param_utils.php");
	include("../../utils/date_utils.php");
	include("fetch_room_log_data_from_db.php");
	
	$begin_time = get_post_or_get($conn, "begin_time");
	$end_time = get_post_or_get($conn, "end_time");
	$seek_room = get_post_or_get($conn, "seek_room");
	$seek_nfc_id = get_post_or_get($conn, "seek_nfc_id");
	$seek_topic = get_post_or_get($conn, "seek_topic");
	$seek_course_name = get_post_or_get($conn, "seek_course_name");
	
	if (strlen($seek_room) > 50 || strlen($seek_nfc_id) > 50 ||
		strlen($seek_topic) > 150  || strlen($seek_course_name) > 50) {
		include("../../db_disconnect_inc.php");
		return;
	}
	
	$begin_time = from_ui_to_db($begin_time);
	$end_time = from_ui_to_db($end_time);

	if (!isDateTime($begin_time) || !isDateTime($end_time) ||
		!isDatetime1Before($begin_time, $end_time)) {
		return;			
	}
	
	$room_logs = get_room_log($conn,
		$begin_time, $end_time, $seek_room, $seek_nfc_id, $seek_topic,
		$seek_course_name, -1, "");

	include("../../db_disconnect_inc.php");
		
	$to_csv_arr = array();
	$to_csv_arr[] = array(
		"NFC ID",
		"Sisääntuloaika",
		"Luokka",
		"Oppitunnin aihe",
		"Kurssin nimi");
	
	foreach($room_logs['room_logs'] as $room_log) {
		$to_csv_arr[] = array(
			$room_log["nfc_id"], 
			$room_log["dt"], 
			$room_log["room_identifier"],
			$room_log["topic"],
			$room_log["course_name"]);
	}
		
	// file so no temp files needed, you might run out of memory though
    $f = fopen('php://memory', 'w'); 
    // loop over the input array
    foreach ($to_csv_arr as $line) { 
        // generate csv lines from the inner arrays
        fputcsv($f, mb_convert_encoding($line, 'UTF-16LE', 'UTF-8'), ";"); 
    }
    // reset the file pointer to the start of the file
    fseek($f, 0);
    // tell the browser it's going to be a csv file
    header('Content-Type: application/csv; charset=UTF-8');
    // tell the browser we want to save it instead of displaying it
    header('Content-Disposition: attachment; filename="huonelokit.csv";');
    // make php send the generated csv lines to the browser
    fpassthru($f);	
?>