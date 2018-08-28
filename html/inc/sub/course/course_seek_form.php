<h2>Kurssi haku</h2>
<?php
	$name_seek = get_post_or_get($conn, "name_seek");
	$description_seek = get_post_or_get($conn, "description_seek");
	$topic_seek = get_post_or_get($conn, "topic_seek");
?>
<form name="courses_seek" action="<?php echo $index_page; ?>" method="POST" >	
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
		<input name="topic_seek" placeholder="Aihe"
			id="topic_seek" 
			value="<?php echo $topic_seek; ?>" maxlength="150" />
	</div>	
	
	
	<div class="row-type-5">
		<input class="button" type="submit" 
			onsubmit="return checkCourseSeek();" value="Hae"/>
	</div>
</form>
<div id="course_seek_validation_msgs"></div>