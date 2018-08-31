<h2>Kurssi haku</h2>
<?php
	$topic_seek = "";
	$name_seek = get_post_or_get($conn, "name_seek");
	$description_seek = get_post_or_get($conn, "description_seek");
	$container_id = "lesson_topics_seek";
	$last_query_lesson_topics_topic_seek = 
		get_post_or_get($conn, $container_id . "_topic_parts_seek");
	$last_query_lesson_topics_seek_selection = 
		get_post_or_get($conn, $container_id . "_selected_topic_ids");
?>
<form id="courses_seek" name="courses_seek" action="<?php echo $index_page; ?>" method="POST" >	
	<div class="row-type-2">
		<label>Nimi:</label>
		<input name="name_seek" 
			id="name_seek" placeholder="Nimi" 
			value="<?php echo $name_seek; ?>" maxlength="50" />
	</div>
	<div class="row-type-2">
		<label>Kuvaus:</label>
		<input name="description_seek" placeholder="Kuvaus"
			id="description_seek" 
			value="<?php echo $description_seek; ?>" maxlength="500" />
	</div>
	<div class="row-type-2">
		<label>Oppitunnin aihe:</label>
	</div>
	<div class="row-type-5">
		<?php echo getTopicsHandlingContainer($conn, "lesson_topics_seek"); ?>
	</div>
	
	<div class="row-type-5">
		<input class="button" type="submit" 
			onsubmit="return checkCourseSeek();" value="Hae"/>
	</div>
</form>
<div id="course_seek_validation_msgs"></div>