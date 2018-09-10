<?php
	include("course_fetch_from_db.php");
	$topic_ids = get_total_topic_ids($conn, 
		$last_query_lesson_topics_topic_seek, 
		$last_query_lesson_topics_seek_selection);
	$courses_arr = get_courses($conn, $name_seek, $description_seek, $topic_ids, 1);
?>
	<h2>Kurssit</h2>
<?php
	if ($courses_arr['count'] > 0) {
?>
		<div id="course_listing_table" class="datatable">
<?php
			echo heading_row(null, array(4,6,"1-wrap"), 
				array("<h5>Nimi</h5>", "<h5>Kuvaus</h5>",
					  "<div class=\"col-sm-1\"></div>" .
					  "<div class=\"col-sm-1\"></div>"));
			foreach($courses_arr['courses'] as $course) {
				$course_params = array($course['course_id']);
				$modify_js_call = java_script_call("modifyCourse", $course_params);
				$remove_js_call = java_script_call("removeCourse", $course_params);					
				echo data_row(null, array(4,6,"1-wrap"),
					array($course['name'], $course['description'], 
						modify_and_remove_btn_block($modify_js_call, $remove_js_call)));
			}
?>
		</div>
<?php
		echo generate_js_page_list("get_course_page",
			array(), 
			$page_size, $page_page_size,
			$courses_arr['page_count'], 1, 1,
			"course_pages", "",
			"curr_page", "other_page");
	} else {
?>
		<div id="course_listing_table" class="datatable">
		<b>Haulla ei löytynyt yhtään kurssia</b>
<?php
	}
/*
	These are because seek fields etc. can be changes after
     last query and other user and also to make js functions
	 work easier
*/
	echo div_nodisplay_elem_group(array(
			"page" => 1, "page_page" => 1, 
			"last_query_name_seek" => $name_seek,
			"last_query_description_seek" => $description_seek,
			"last_query_lesson_topics_seek_selection" => 
			$last_query_lesson_topics_seek_selection,
			"last_query_lesson_topics_topic_seek" => 
			$last_query_lesson_topics_topic_seek));	
?>
