<?php
	$begin_time = get_post_or_get($conn, "begin_time");
	$end_time = get_post_or_get($conn, "end_time");
	$seek_room = get_post_or_get($conn, "seek_room");
	$seek_nfc_id = get_post_or_get($conn, "seek_nfc_id");	

	if (!isset($begin_time)) {
		$begin_time_value_param = "";
	} else {
		$begin_time_value_param = " value=\"".$begin_time."\" ";
	}
	if (!isset($end_time)) {
		$end_time_value_param = "";
	} else {
		$end_time_value_param = " value=\"".$end_time."\" ";
	}
	if (!isset($seek_room)) {
		$seek_room = "";
	} 
	if (!isset($seek_nfc_id)) {
		$seek_nfc_id = "";
	} 	
?>

<!--  -->
<div>
	<form name="seek_room_log_form" id="seek_room_log_form" 
		action="index.php" method="POST" onsubmit="return validateForm()" >
		<input  
			placeholder="Aloitusaika" size="16" 
			alt="Päivämäärä suomalaisessa muodossa esim: 31.07.2018 07:00" 
			id="date_start" class="datetime_picker"
			name="begin_time" <?php echo $begin_time_value_param; ?> required />
		<input 
			placeholder="Lopetusaika" size="16" 
			alt="Päivämäärä suomalaisessa muodossa esim: 31.07.2018 09:00" 
			id="date_end" class="datetime_picker"
			name="end_time" <?php echo $end_time_value_param; ?> required />		
		
		<input id="seek_room" type="text" name="seek_room" value="<?php echo $seek_room; ?>" placeholder="Etsittävä luokan tunnisteen osa" />
		<input id="seek_nfc_id" type="text" name="seek_nfc_id" value="<?php echo $seek_nfc_id; ?>" placeholder="Etsittävä NFC ID:n osa" />

		<!-- <button onclick="validateForm()">Here</button> -->
		<input type="submit" value="Valitse"/>
	</form>
</div>
<div id="validation_errors"></div>