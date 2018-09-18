<?php
	include("../../db_connect_inc.php");
	include("../../utils/request_param_utils.php");
	include("../../utils/date_utils.php");
	include("../setting/fetch_settings_from_db.php");
	include("fetch_room_log_data_from_db.php");
	
	$begin_time = get_post_or_get($conn, "begin_time");
	$end_time = get_post_or_get($conn, "end_time");
	$seek_room = get_post_or_get($conn, "seek_room");
	$seek_nfc_id = get_post_or_get($conn, "seek_nfc_id");
	$topic_ids = get_post_or_get($conn, "topic_ids");
	$seek_course_name = get_post_or_get($conn, "seek_course_name");
	
	if (mb_strlen($seek_room) > 50 || mb_strlen($seek_nfc_id) > 50 ||
		mb_strlen($seek_course_name) > 50) {
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
		$begin_time, $end_time, $seek_room, $seek_nfc_id, $topic_ids,
		$seek_course_name, -1, "");

	$settings = getSettings($conn);
		
	include("../../db_disconnect_inc.php");
		
	$to_csv_arr = array();
	$to_csv_arr[] = array(
		"NFC ID",
		"Sisääntuloaika",
		"Luokka",
		"Oppitunnin aiheet",
		"Kurssin nimi");
	
	foreach($room_logs['room_logs'] as $room_log) {
		$to_csv_arr[] = array(
			$room_log["nfc_id"], 
			$room_log["dt"], 
			$room_log["room_identifier"],
			$room_log["topics"],
			$room_log["course_name"]);
	}
	
	if ($settings['usage_type'] == 1) {
		$to_csv_arr[] = array("Sisäänkirjautuneiden ihmisten aiheittainen osallistuminen");
		$to_csv_arr[] = array();
		foreach($room_logs['NFC_ID_topics_and_lessons']
			as $NFC_ID_topic_and_lesson_item) {
			$to_csv_arr[] =	array("NFC ID: " . $NFC_ID_topic_and_lesson_item['nfc_id']);
			$to_csv_arr[] = array("Aiheet:");
			$topics = "";
			foreach($NFC_ID_topic_and_lesson_item['topics'] as $topic) {
				if ($topics != "") {
					$topics .= ",";
				}
				$topics .= $topic['topic_name'];
			}
			$to_csv_arr[] = array($topics);
			$to_csv_arr[] = array();
			$to_csv_arr[] = array("Aiheet oppitunneittain");
			foreach($NFC_ID_topic_and_lesson_item['topics'] as $topic) {
				$to_csv_arr[] = array("Aihe: ", $topic['topic_name']);
				$to_csv_arr[] = array("oppitunnit:");	
				$to_csv_arr[] = array();
				foreach($topic['lessons'] as $lesson) {
					if ($lesson['course'] != "" && $lesson['course'] != null && $lesson['course'] != "NULL") {
						$to_csv_arr[] = array("Kurssi:", $lesson['course']);
					}
					$to_csv_arr[] = array("Huone:", $lesson['room_identifier'], "Aika:", $lesson['time_interval']);
				}
			}
		}
	} else {
		foreach($room_logs['NFC_ID_monthly_counts']['year_counts'] as $year_counts) {
			$to_csv_arr[] = array("Kävijät vuonna " . $year_counts['year']);
			$to_csv_arr[] = array("Koko vuonna oli valitulla ajalla yhteensä " . 
					$year_counts['count'] . " kävijää.");
			$to_csv_arr[] = array("Vuonna oli kuukausittain valitulla ajalla kävijöitä seuraavasti:");

			$arr_months = array();
			foreach($room_logs['NFC_ID_monthly_counts']['month_counts'] as $months_of_years) {
				if ($months_of_years['year'] == $year_counts['year']) {
					$arr_months = $months_of_years['months'];
				}
			}
				
			if (count($arr_months) > 0) {
				foreach($arr_months as $month_key => $month_counts) {
					$to_csv_arr[] = array($arr_month_names[$month_key], $month_counts);
				}
			}
			$to_csv_arr[] = array();
		}
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