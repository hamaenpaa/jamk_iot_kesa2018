<div>
	<form id="seek_specific_room_or_course_form" 
		action="list_room_logs.php" method="POST">
		<input  
			placeholder="Aloitusaika" size="16" 
			alt="Päivämäärä suomalaisessa muodossa esim: 31.07.2018 07:00" 
			id="date_start" class="datetime_picker"
			name="begin_time" <?php echo $begin_time_value_param; ?> />
		<input 
			placeholder="Lopetusaika" size="16" 
			alt="Päivämäärä suomalaisessa muodossa esim: 31.07.2018 09:00" 
			id="date_end" class="datetime_picker"
			name="end_time" <?php echo $end_time_value_param; ?> />		
		
		<input type="text" name="seek_room" value="<?php echo $seek_room_input; ?>" />
		<input type="text" name="seek_nfc_id" value="<?php echo $seek_nfc_id; ?>" />

		<input class="button" type="submit" value="Valitse"/>	
	</form>
</div>