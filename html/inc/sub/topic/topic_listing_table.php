<?php
	include("topics_fetch_from_db.php");
	$topics_arr = get_topics($conn, $name_seek, $page);
?>
	<h2>Haetut aiheet</h2>
<?php
	if ($topics_arr['count'] > 0) {
?>
		<div id="topics_listing_table" class="datatable">
<?php
			echo heading_row(null, array(10,"1-wrap"), 
				array("<h5>Nimi</h5>", 
					"<div class=\"col-sm-1\"></div><div class=\"col-sm-1\"></div>"));
			foreach($topics_arr['topics'] as $topic) {
				$topic_params = array($topic['topic_id']);
				$modify_js_call = java_script_call("modifyTopic", $topic_params);
				$remove_js_call = java_script_call("removeTopic", $topic_params);
				echo data_row(null, array(10,"1-wrap"),
					array($topic['name'],
						modify_and_remove_btn_block($modify_js_call, $remove_js_call)));
			}
?>
		</div>
<?php
		echo generate_js_page_list("get_topics_page",
			array(), 
			$page_size, $page_page_size,
			$topics_arr['page_count'], $page, $page_page,
			"topic_pages", "",
			"curr_page", "other_page");
	} else {
?>
		<div id="no_topic_findings">
			<b>Haulla ei löytynyt yhtään aihetta</b>
		</div>
		<div id="topics_listing_table" class="datatable"></div>
<?php
	}
/*
	These are because seek fields etc. can be changes after
     last query and other user and also to make js functions
	 work easier
*/
	echo div_nodisplay_elem_group(array("page" => $page, 
		"page_page" => $page_page, "last_query_name_seek" => $name_seek));
?>
