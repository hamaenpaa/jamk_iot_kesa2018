<h2>Koulutus/oppitunti Haku</h2>
<?php
	$begin_time_seek = get_post_or_get($conn, "begin_time_seek");
	$end_time_seek = get_post_or_get($conn, "end_time_seek");
	$room_seek = get_post_or_get($conn, "room_seek");
	$topic_seek = get_post_or_get($conn, "topic_seek");
	$page = get_post_or_get($conn, "page");
	$page_page = get_post_or_get($conn, "page_page");	
	
	if (!isset($room_seek)) {
		$room_seek = "";	
	}
	if (!isset($topic_seek)) {
		$topic_seek = "";	
	}	
	if (!isset($begin_time_seek) || $begin_time_seek == "") {
		$begin_time_seek_value_param = "";
	} else {
		$begin_time_seek_value_param = " value=\"".$begin_time_seek."\" ";
	}
	if (!isset($end_time_seek) || $end_time_seek == "") {
		$end_time_seek_value_param = "";
	} else {
		$end_time_seek_value_param = " value=\"".$end_time_seek."\" ";
	}
	$page = get_post_or_get($conn, "page");
	if (!isset($page) || $page == "") {
		$page = "1";
	}
	$page_page = get_post_or_get($conn, "page_page");
	if (!isset($page_page) || $page_page == "") {
		$page_page = "1";
	}	
	$container_id = "lesson_topics_seek";
	$last_query_lesson_topics_topic_seek = 
		get_post_or_get($conn, $container_id . "_topic_parts_seek");
	$last_query_lesson_topics_seek_selection = 
		get_post_or_get($conn, $container_id . "_selected_topic_ids");
?>

<form name="lessons_seek" action="<?php echo $index_page; ?>" method="POST"
	onsubmit="return validateSeekForm()" >	
	<div class="row-type-2">
		<label>Aloitusaika:</label>
		<input name="begin_time_seek" class="datetime_picker" 
			id="begin_time_seek" placeholder="Aloitusaika" 
			autocomplete="off"
			alt="Päivämäärä suomalaisessa muodossa esim: 31.07.2018 07:00"
			<?php echo $begin_time_seek_value_param; ?> required />
	</div>
	<div class="row-type-2">
		<label>Lopetusaika:</label>
		<input name="end_time_seek" class="datetime_picker" 
			id="end_time_seek" placeholder="Lopetusaika" 
			autocomplete="off"
			alt="Päivämäärä suomalaisessa muodossa esim: 31.07.2018 07:00"
			<?php echo $end_time_seek_value_param; ?> required />
	</div>
	<div class="row-type-2">
		<label>Etsittävä luokan tunnisteen osa:</label>
		<input id="room_seek" name="room_seek" value="<?php echo $room_seek; ?>"
			placeholder="Huoneen tunnus" maxlength="50"/>
	</div>
	<div class="row-type-2">
		<label>Oppitunnin aihe:</label>
	</div>
	<div class="row-type-2">
		<label>(jos mitään ei valita, haetaan aiheista riippumatta)</label>
	</div>	
	
	<div class="row-type-5">
		<?php echo getTopicsHandlingContainer($conn, "lesson_topics_seek"); ?>
	</div
	<div class="row-type-5">
		<input class="button" type="submit" value="Hae"/>
	</div>
</form>
<div id="seekform_validation_errors"></div>