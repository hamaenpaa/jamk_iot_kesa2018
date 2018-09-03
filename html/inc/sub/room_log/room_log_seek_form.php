<?php
	$begin_time = get_post_or_get($conn, "begin_time");
	$end_time = get_post_or_get($conn, "end_time");
	$seek_room = get_post_or_get($conn, "seek_room");
	$seek_nfc_id = get_post_or_get($conn, "seek_nfc_id");	
	$seek_course_name = get_post_or_get($conn, "seek_course_name");
	$seek_topic = get_post_or_get($conn, "seek_topic");	

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
	if (!isset($seek_topic)) {
		$seek_topic = "";
	}	
	if (!isset($seek_course_name)) {
		$seek_course_name = "";
	}	
	$container_id = "lesson_topics_seek";
	$last_query_lesson_topics_topic_seek = 
		get_post_or_get($conn, $container_id . "_topic_parts_seek");
	$last_query_lesson_topics_seek_selection = 
		get_post_or_get($conn, $container_id . "_selected_topic_ids");
?>

<div>
	<form name="seek_room_log_form" id="seek_room_log_form" 
		action="index.php" method="POST" onsubmit="return validateForm()" >
		<div class="row-type-2">
			<label>Aloitusaika:</label>
			<input  
				placeholder="Aloitusaika" 
				alt="Päivämäärä suomalaisessa muodossa esim: 31.07.2018 07:00" 
				id="begin_time" class="datetime_picker"
				autocomplete="off"
				name="begin_time" <?php echo $begin_time_value_param; ?> required />
		</div>
		<div class="row-type-2">
			<label>Lopetusaika:</label>
			<input 
				placeholder="Lopetusaika" 
				alt="Päivämäärä suomalaisessa muodossa esim: 31.07.2018 09:00" 
				id="end_time" class="datetime_picker"
				autocomplete="off"
				name="end_time" <?php echo $end_time_value_param; ?> required />
		</div>
		<div class="row-type-2">
			<label>Etsittävä luokan tunnisteen osa:</label>
			<input id="seek_room" type="text" name="seek_room" 
				maxlength="50" value="<?php echo $seek_room; ?>" 
				placeholder="Etsittävä luokan tunnisteen osa" />
		</div>
		<div class="row-type-2">
			<label>Etsittävä NFC ID:n osa:</label>
			<input id="seek_nfc_id" type="text" name="seek_nfc_id" 
				maxlength="50" value="<?php echo $seek_nfc_id; ?>" 
				placeholder="Etsittävä NFC ID:n osa" />
		</div>
		<div class="row-type-2">
			<label>Oppitunnin aihe:</label>
		</div>
		<div class="row-type-2">
			<label>(jos mitään ei valita, haetaan aiheista riippumatta)</label>
		</div>	
	
		<div class="row-type-5">
			<?php echo getTopicsHandlingContainer($conn, "lesson_topics_seek"); ?>
		</div>
		<div class="row-type-2">
			<label>Etsittävä kurssin nimen osa:</label>
			<input id="seek_course_name" type="text" name="seek_course_name" 
				maxlength="50" value="<?php echo $seek_course_name; ?>" 
				placeholder="Etsittävä kurssin nimen osa" />
		</div>		
		<div class="row-type-5">		
			<input class="button" type="submit" value="Hae"/>
		</div>
	</form>
</div>
<div id="validation_errors"></div>