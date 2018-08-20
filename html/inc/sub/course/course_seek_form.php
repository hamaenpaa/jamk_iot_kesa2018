<h2>Kurssi haku</h2>
<?php
	$name_seek = get_post_or_get($conn, "name_seek");
	$description_seek = get_post_or_get($conn, "description_seek");
	$topic_seek = get_post_or_get($conn, "topic_seek");
	
	$lesson_add_begin_time_seek = get_post_or_get($conn, "lesson_add_begin_time_seek");
	$lesson_add_end_time_seek = get_post_or_get($conn, "lesson_add_end_time_seek");
	$lesson_add_room_seek = get_post_or_get($conn, "lesson_add_room_seek");
	$lesson_add_topic_seek = get_post_or_get($conn, "lesson_add_topic_seek");
	
	if (!isset($lesson_add_begin_time_seek) || $lesson_add_begin_time_seek == "") {
		$lesson_add_begin_time_seek_value_param = "";
	} else {
		$lesson_add_begin_time_seek_value_param = " value=\"".$lesson_add_begin_time_seek."\" ";
	}
	if (!isset($lesson_add_end_time_seek) || $lesson_add_end_time_seek == "") {
		$lesson_add_end_time_seek_value_param = "";
	} else {
		$lesson_add_end_time_seek_value_param = " value=\"".$lesson_add_end_time_seek."\" ";
	}	
	$page = get_post_or_get($conn, "page");
	if (!isset($page) || $page == "") {
		$page = "1";
	}
	$page_page = get_post_or_get($conn, "page_page");
	if (!isset($page_page) || $page_page == "") {
		$page_page = "1";
	}	
	$page_lesson_add = get_post_or_get($conn, "page_lesson_add");
	if (!isset($page_lesson_add) || $page_lesson_add == "") {
		$page_lesson_add = "1";
	}
	$page_lesson_add_page = get_post_or_get($conn, "page_lesson_add_page");
	if (!isset($page_lesson_add_page) || $page_lesson_add_page == "") {
		$page_lesson_add_page = "1";
	}	
	
	$seek_params_hidden_inputs = 
		hidden_input("name_seek", $name_seek) .
		hidden_input("description_seek", $description_seek).
		hidden_input("topic_seek", $topic_seek).
		hidden_input("lesson_add_begin_time_seek", $lesson_add_begin_time_seek) .
		hidden_input("lesson_add_end_time_seek", $lesson_add_end_time_seek) .
		hidden_input("lesson_add_room_seek", $lesson_add_room_seek) .
		hidden_input("lesson_add_topic_seek", $lesson_add_topic_seek) .	
		hidden_input("page", $page) .
		hidden_input("page_page", $page_page) .	
		hidden_input("page_lesson_add", $page_lesson_add) .
		hidden_input("page_lesson_add_page", $page_lesson_add_page);			

	$seek_params_get = 
		possible_get_param("name_seek",$name_seek,false).
		possible_get_param("description_seek",$description_seek,false).
		possible_get_param("topic_seek",$topic_seek,false).
		possible_get_param("lesson_add_begin_time_seek",$lesson_add_begin_time_seek,false).
		possible_get_param("lesson_add_end_time_seek",$lesson_add_end_time_seek,false).
		possible_get_param("lesson_add_room_seek", $lesson_add_room_seek) .
		possible_get_param("lesson_add_topic_seek", $lesson_add_topic_seek);		
?>
<form name="courses_seek" action="<?php echo $index_page; ?>" method="POST" >	
	<div class="row-type-2">
		<label>Nimi:</label>
		<input name="name_seek" 
			id="name_seek" placeholder="Nimi" 
			value="<?php echo $name_seek; ?>" />
	</div>
	<div class="row-type-2">
		<label>Kuvaus:</label>
		<input name="description_seek" placeholder="Kuvaus"
			id="description_seek" 
			value="<?php echo $description_seek; ?>"  />
	</div>
	<div class="row-type-2">
		<label>Oppitunnin aihe:</label>
		<input name="topic_seek" placeholder="Aihe"
			id="topic_seek" 
			value="<?php echo $topic_seek; ?>"  />
	</div>	
	
	
	<div class="row-type-5">
		<input class="button" type="submit" value="Hae"/>
	</div>
</form>