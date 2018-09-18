<?php
	include("fetch_room_log_data_from_db.php");
	if ((isset($begin_time) && isset($end_time) && $begin_time != "" && $end_time != "")) {
		$begin_time = from_ui_to_db($begin_time);
		$end_time = from_ui_to_db($end_time);			
		$page = get_post_or_get($conn, "page");
		$page_page = get_post_or_get($conn, "page_page");
		if (!isset($page) || $page == "") {	$page = "1"; }	
		if (!isset($page_page) || $page_page == "") { $page_page = "1";	}		
		$topic_ids = get_total_topic_ids($conn, 
			$last_query_lesson_topics_topic_seek, 
			$last_query_lesson_topics_seek_selection);	
		$room_logs = get_room_log($conn,
			$begin_time, $end_time, $seek_room, $seek_nfc_id, $topic_ids, 
			$seek_course_name, $page, "");
		$download_csv_url = "inc/sub/room_log/download_as_csv.php?&seek_room=".
			$seek_room . "&seek_nfc_id=" . $seek_nfc_id .
			"&seek_course_name=". $seek_course_name .
			"&topic_ids=" . $topic_ids .
			"&begin_time=" . $begin_time .
			"&end_time=" . $end_time;
?>
		<div id="new_room_log_notifications"></div>
<?php 	if (count($room_logs) > 0) { ?>
			<div id="download_csv"><a href="<?php echo $download_csv_url; ?>" download>Lataa</a></div>
<?php 
		} 
		echo div_nodisplay_elem_group(array(
			"last_fetch_time" => time(), "page" => 1, "page_page" => 1, 
			"last_query_begin_time" => $begin_time,
			"last_query_end_time" => $end_time,
			"last_query_room" => $seek_room,
			"last_query_topic" => $seek_topic,
			"last_query_nfc_id" => $seek_nfc_id,
			"last_query_course_name" => $seek_course_name));	
?>		
		<h2>Sisäänkirjautuneet ihmiset</h2>
		<div id="room_log_listing_table" class="datatable">
<?php
			echo heading_row("null", array(2,4,2,2,2), 
				array("<h5>NFC ID</h5>","<h5>Sisääntuloaika</h5>",
					"<h5>Luokka</h5>","<h5>Oppitunnin aiheet</h5>",
					"<h5>Kurssin nimi</h5>"));
			foreach($room_logs['room_logs'] as $room_log) {
				$dt = $room_log['dt'];
				$room_identifier = $room_log['room_identifier'];
				$nfc_id = $room_log['nfc_id'];
				$topics = $room_log['topics'];
				$course_name = $room_log['course_name'];
				if ($topics == null) $topics = "";
				if ($course_name == null) $course_name = "";
				echo 
					data_row(null, array(2,4,2,2,2), 
						array($nfc_id, $dt, $room_identifier, $topics, $course_name));
			}
?>
		</div>
<?php
		$seek_params = array();		
		echo generate_js_page_list("get_js_room_log_page",
			$seek_params, 
			$page_size, $page_page_size,
			$room_logs['page_count'], $page, $page_page,
			"roomlog_pages", "",
		    "curr_page", "other_page");
		if ($settings['usage_type'] == 1) {
?>
			<div id="dynamit_summary">
			<h2>Sisäänkirjautuneiden ihmisten aiheittainen osallistuminen</h2>
<?php
			foreach($room_logs['NFC_ID_topics_and_lessons']
				as $NFC_ID_topic_and_lesson_item) {
?>
				<h3><b>NFC ID: <?php echo $NFC_ID_topic_and_lesson_item['nfc_id']; ?></b></h3>
				<h4><b>Aiheet:</b></h4>
<?php
				$topics = "";
				foreach($NFC_ID_topic_and_lesson_item['topics'] as $topic) {
					if ($topics != "") {
						$topics .= ",";
					}
					$topics .= $topic['topic_name'];
				}
				echo $topics . "<br>";
?>			
				<h4><b>Aiheet oppitunneittain</b></h4>
<?php
				foreach($NFC_ID_topic_and_lesson_item['topics'] as $topic) {
					echo "<b>Aihe: " . $topic['topic_name']. "</b><br>";
					echo "<b>oppitunnit</b>:<br> ";
					foreach($topic['lessons'] as $lesson) {
						if ($lesson['course'] != "" && $lesson['course'] != null && 
							$lesson['course'] != "NULL") {
							echo "<b>Kurssi: </b>" . $lesson['course']. " ";
						}
						echo "<b>Huone:</b> " . $lesson['room_identifier'] . " ". 
							"<b>Aika:</b> " . $lesson['time_interval'] . "<br>";
					}
					echo "<br>";
				}
			}
?>
			</div>
<?php
		} else {
?>
			<div id="expa_summary">
<?php
			
			foreach($room_logs['NFC_ID_monthly_counts']['year_counts'] as $year_counts) {
				echo "<h2>Kävijät vuonna " . $year_counts['year'] . "</h2>";
				echo "Koko vuonna oli valitulla ajalla yhteensä " . 
					$year_counts['count'] . " kävijää.<br><br>";
				echo "Vuonna oli kuukausittain valitulla ajalla kävijöitä seuraavasti: <br><br>";

				$arr_months = array();
				foreach($room_logs['NFC_ID_monthly_counts']['month_counts'] as $months_of_years) {
					if ($months_of_years['year'] == $year_counts['year']) {
						$arr_months = $months_of_years['months'];
					}
				}
				
				if (count($arr_months) > 0) {
					foreach($arr_months as $month_key => $month_counts) {
						echo $arr_month_names[$month_key] . ": " . $month_counts . " kävijää.";
					}
				}
				echo "<br>";
			}
		}
?>
		</div>
<?php
	}
	echo div_nodisplay_elem_group(array(
		"last_query_lesson_topics_seek_selection" => 
			$last_query_lesson_topics_seek_selection, 
		"last_query_lesson_topics_topic_seek" => 
			$last_query_lesson_topics_topic_seek));
?>