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
						"<div class=\"col-sm-1\">".
							button_elem($modify_js_call, "Muokkaa").
						"</div><div class=\"col-sm-1\">".
							button_elem($remove_js_call, "Poista").
						"</div>"));
			}
?>
		</div>
<?php
		echo generate_js_page_list("get_topics_page",
			array(), 
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
	echo div_elem("page", null, true, $page).
		 div_elem("page_page", null, true, $page_page).
		 div_elem("last_query_name_seek", null, true, $name_seek);
?>
