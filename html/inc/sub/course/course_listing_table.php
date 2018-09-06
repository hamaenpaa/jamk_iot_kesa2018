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
						"<div class=\"col-sm-1\">" . 
							button_elem($modify_js_call, "Muokkaa").
						"</div><div class=\"col-sm-1\">" .
							button_elem($remove_js_call, "Poista").
						"</div>"));
			}
?>
		</div>
<?php
		echo generate_js_page_list("get_course_page",
			array(), 
			$courses_arr['page_count'], 1, 1,
			"course_pages", "",
			"curr_page", "other_page");
	} else {
?>
		<b>Haulla ei löytynyt yhtään kurssia</b>
<?php
	}
/*
	These are because seek fields etc. can be changes after
     last query and other user and also to make js functions
	 work easier
*/
	echo div_elem("page", null, true, 1).
		 div_elem("page_page", null, true, 1).
		 div_elem("last_query_name_seek", null, true, $name_seek).
		 div_elem("last_query_description_seek", null, true, $description_seek).
		 div_elem("last_query_lesson_topics_seek_selection", null, true, 
			$last_query_lesson_topics_seek_selection).
		 div_elem("last_query_lesson_topics_topic_seek", null, true, 
			$last_query_lesson_topics_topic_seek);
?>
