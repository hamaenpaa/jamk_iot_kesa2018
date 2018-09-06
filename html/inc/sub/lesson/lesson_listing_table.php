<?php
	if ($begin_time_seek_value_param != "" && $end_time_seek_value_param != "") {
		include("lessons_fetch_from_db.php");
		$begin_time_seek = from_ui_to_db($begin_time_seek);
		$end_time_seek = from_ui_to_db($end_time_seek);	
		$topic_ids = get_total_topic_ids($conn, 
			$last_query_lesson_topics_topic_seek, 
			$last_query_lesson_topics_seek_selection);	
		$lessons_arr = get_lessons($conn, 
			$begin_time_seek, $end_time_seek, $room_seek, $topic_ids, $page);
?>
		<h2>Haetut koulutukset tai oppitunnit</h2>
<?php
		if ($lessons_arr['count'] > 0) {
?>
			<div id="lesson_listing_table" class="datatable">
<?php
				echo heading_row(null, array(5,5,"1-wrap"),
					array("<h5>Aikaväli<h5>","<h5>Huone</h5>",
						"<div class=\"col-sm-1\"></div>".
						"<div class=\"col-sm-1\"></div>"));
				foreach($lessons_arr['lessons'] as $lesson) {
					$lesson_params = array($lesson['lesson_id']);
					$modify_js_call = java_script_call("modifyLesson", 
						$lesson_params);
					$remove_js_call = java_script_call("removeLesson", 
						$lesson_params);					
					echo data_row(null, array(5,5,"1-wrap"), 
						array($lesson['time_interval'], $lesson['room_identifier'],
							modify_and_remove_btn_block($modify_js_call, $remove_js_call)));
				}
?>
			</div>
<?php
			echo generate_js_page_list("get_lessons_page",
				array(), 
				$lessons_arr['page_count'], $page, $page_page,
				"lesson_pages", "",
				"curr_page", "other_page");
		} else {
?>
			<b>Haulla ei löytynyt yhtään koulutusta/oppituntia</b>
<?php
		}
	}
/*
	These are because seek fields etc. can be changes after
     last query and other user and also to make js functions
	 work easier
*/
	echo div_nodisplay_elem_group(array(
			"page" => $page, "page_page" => $page_page, 
			"last_query_begin_time_seek" => $begin_time_seek,
			"last_query_end_time_seek" => $end_time_seek,
			"last_query_topic_seek" => $topic_seek,
			"last_query_room_seek" => $room_seek,
			"last_query_lesson_topics_seek_selection" => 
				$last_query_lesson_topics_seek_selection,
			"last_query_lesson_topics_topic_seek" => 
				$last_query_lesson_topics_topic_seek));	
?>