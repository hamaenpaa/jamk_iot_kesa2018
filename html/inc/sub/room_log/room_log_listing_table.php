<?php
	include("fetch_room_log_data_from_db.php");
/*	$begin_time = get_post_or_get($conn, "begin_time");
	$end_time = get_post_or_get($conn, "end_time");*/
	
	if ((isset($begin_time) && isset($end_time) &&
		$begin_time != "" && $end_time != "")) {

		$begin_time = from_ui_to_db($begin_time);
		$end_time = from_ui_to_db($end_time);			
			
		$page = get_post_or_get($conn, "page");
		if (!isset($page) || $page == "") {
			$page = "1";
		}
		$page_page = get_post_or_get($conn, "page_page");
		if (!isset($page_page) || $page_page == "") {
			$page_page = "1";
		}		
	

	
		$room_logs = get_room_log($conn,
			$begin_time, $end_time, $seek_room, $seek_nfc_id, $seek_topic, 
			$seek_course_name, $page, "");
		$download_csv_url = "inc/sub/room_log/download_as_csv.php?&seek_room=".
			$seek_room . "&seek_nfc_id=" . $seek_nfc_id .
			"&seek_course_name=". $seek_course_name .
			"&seek_topic=" . $seek_topic .
			"&begin_time=" . $begin_time .
			"&end_time=" . $end_time;
?>
		<div id="new_room_log_notifications"></div>
		<div id="download_csv"><a href="<?php echo $download_csv_url; ?>" download>Lataa</a></div>
		
		<div id="last_fetch_time" style="display:none"><?php echo time(); ?></div>
		<div id="page" style="display:none">1</div>
		<div id="page_page" style="display:none">1</div>
		<div id="last_query_begin_time" style="display:none"><?php echo $begin_time; ?></div>
		<div id="last_query_end_time" style="display:none"><?php echo $end_time; ?></div>
		<div id="last_query_room" style="display:none"><?php echo $seek_room; ?></div>	
		<div id="last_query_topic" style="display:none"><?php echo $seek_topic; ?></div>
		<div id="last_query_nfc_id" style="display:none"><?php echo $seek_nfc_id; ?></div>
		<div id="last_query_course_name" style="display:none"><?php echo $seek_course_name; ?></div>
		
		<h2>Sisäänkirjautuneet ihmiset</h2>
		<div id="room_log_listing_table" class="datatable">
			<div class="row heading-row">
				<div class="col-sm-2"><h5>NFC&nbsp;ID</h5></div>
				<div class="col-sm-4"><h5>Sisääntuloaika</h5></div>
				<div class="col-sm-2"><h5>Luokka</h5></div>
				<div class="col-sm-2"><h5>Oppitunnin&nbsp;aihe</h5></div>
				<div class="col-sm-2"><h5>Kurssin nimi</h5></div>
			</div>
<?php
			foreach($room_logs['room_logs'] as $room_log) {
				$dt = $room_log['dt'];
				$room_identifier = $room_log['room_identifier'];
				$nfc_id = $room_log['nfc_id'];
				$topic = $room_log['topic'];
				$course_name = $room_log['course_name'];
				if ($topic == null || $topic == "") $topic = "&nbsp;";
				if ($course_name == null || $course_name == "") $course_name = "&nbsp;";
?>				
				<div class="row datarow">
					<div class="col-sm-2">
						<?php echo $nfc_id; ?>
					</div>				
					<div class="col-sm-4">
						<?php echo $dt; ?>
					</div>
					<div class="col-sm-2">
						<?php echo $room_identifier;  ?>
					</div>
					<div class="col-sm-2">
						<?php echo $topic;  ?>
					</div>	
					<div class="col-sm-2">
						<?php echo $course_name; ?>
					</div>						
				</div>				
<?php				
			}
?>
		</div>
<?php
		$seek_params = array();		
		echo generate_js_page_list("get_js_room_log_page",
			$seek_params, 
			$room_logs['page_count'], $page, $page_page,
			"roomlog_pages", "",
		    "curr_page", "other_page");
	}
?>
