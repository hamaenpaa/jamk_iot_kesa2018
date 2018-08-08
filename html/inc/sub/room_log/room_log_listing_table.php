<?php
	include("fetch_room_log_data_from_db.php");
	$begin_time = get_post_or_get($conn, "begin_time");
	$end_time = get_post_or_get($conn, "end_time");
	
	if (!isset($room)) {
		$room = "";
	}
	if (!isset($course)) {
		$course = "";
	}	
	
	if ((isset($begin_time) && isset($end_time) &&
		$begin_time != "" && $end_time != "")) {
		if (!isset($begin_time) || !isset($end_time) ||
			$begin_time == "" || $end_time == "") {
			$begin_time = $lesson_times['begin_time']; 
			$end_time = $lesson_times['end_time'];
		} else {
			$begin_time = from_ui_to_db($begin_time);
			$end_time = from_ui_to_db($end_time);			
		}

		$course = get_post_or_get($conn, "course");
		$room = get_post_or_get($conn, "room");
			
		$room_logs_students = get_room_log($conn,
			$begin_time, $end_time, $seek_room, $seek_nfc_id);




		if (count($room_logs_students) > 0) {
			$dt_extra_css_classes = "";
			$room_extra_css_classes = "";
			$nfc_extra_css_classes = "";
	
			$dt_cols = "4";
			$nfc_cols = "4";
			$room_cols = "4";			
?>
			<h2>Sisäänkirjautuneet ihmiset</h2>
			<div class="room_log_listing_table">
				<div class="heading-row">
					<div class="row">
					<div class="col-sm-<?php echo $dt_cols; ?>"><b>Sisääntuloaika</b></div>
					<div class="col-sm-<?php echo $room_cols; ?>"><h5>Luokka</h5></div>
					<div class="col-sm-<?php echo $nfc_cols; ?>"><h5>NFC ID</h5></div>
				</div>
			</div>
<?php
			foreach($room_logs_students as $room_log) {
				$dt = $room_log['dt'];
				$room_identifier = $room_log['room_identifier'];
				$nfc_id = $room_log['nfc_id'];
?>				
				<div class="row">
					<div class="col-sm-<?php echo $dt_cols . " ". $dt_extra_css_classes; ?>">
						<?php echo $dt; ?>
					</div>
					<div class="col-sm-<?php echo $room_cols . " " . $room_extra_css_classes; ?>">
						<?php echo $room_identifier;  ?>
					</div>
					<div class="col-sm-<?php echo $nfc_cols; ?>">
						<?php echo $nfc_id; ?>
					</div>
				</div>				
<?php				
			}
		}
	}
?>
</div>