<?php
	include("fetch_room_log_data_from_db.php");
	$begin_time = get_post_or_get($conn, "begin_time");
	$end_time = get_post_or_get($conn, "end_time");
	
	if ((isset($begin_time) && isset($end_time) &&
		$begin_time != "" && $end_time != "")) {

		$begin_time = from_ui_to_db($begin_time);
		$end_time = from_ui_to_db($end_time);			
			
		$room_logs_students = get_room_log($conn,
			$begin_time, $end_time, $seek_room, $seek_nfc_id);
?>
		<div id="new_room_log_notifications"></div>
		<div id="last_fetch_time" style="display:none"><?php echo time(); ?></div>
		
		<h2>Sisäänkirjautuneet ihmiset</h2>
		<div class="room_log_listing_table">
			<div class="row">
				<div class="col-sm-3"><h5>NFC ID</h5></div>
				<div class="col-sm-3"><h5>Sisääntuloaika</h5></div>
				<div class="col-sm-3"><h5>Luokka</h5></div>
				<div class="col-sm-3"><h5>Oppitunnin aihe</h5></div>
			</div>
<?php
			foreach($room_logs_students as $room_log) {
				$dt = $room_log['dt'];
				$room_identifier = $room_log['room_identifier'];
				$nfc_id = $room_log['nfc_id'];
				$topic = $room_log['topic'];
				if ($topic == null || $topic == "") $topic = "&nbsp;";
?>				
				<div class="row datarow">
					<div class="col-sm-3">
						<?php echo $nfc_id; ?>
					</div>				
					<div class="col-sm-3">
						<?php echo from_db_to_ui($dt); ?>
					</div>
					<div class="col-sm-3">
						<?php echo $room_identifier;  ?>
					</div>
					<div class="col-sm-3">
						<?php echo $topic;  ?>
					</div>					
				</div>				
<?php				
			}
?>
		</div>
<?php
	}
?>
