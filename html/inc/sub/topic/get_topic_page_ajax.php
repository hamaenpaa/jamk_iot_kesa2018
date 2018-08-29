<?php
	if (!isset($_SESSION)) {
		session_start();	
	} 
	if (!isset($_SESSION['user_id'])) {
		return;	
	}

	include("../../utils/request_param_utils.php");
	include("../../utils/date_utils.php");
	include("../../utils/html_utils.php");
	include("topics_fetch_from_db.php");

	include("../../db_connect_inc.php");

	$name_seek = get_post_or_get($conn, "name_seek");
	$page = get_post_or_get($conn, "page");
	$page_page = get_post_or_get($conn, "page_page");

	if (!is_integerable($page) || $page == "" || $page == "0") {
		include("../../db_disconnect_inc.php");
		return;
	}
	if (!is_integerable($page_page) || $page_page == "" || $page_page == "0") {
		include("../../db_disconnect_inc.php");
		return;
	}
	if (strlen($name_seek) > 150) {
		include("../../db_disconnect_inc.php");
		return;
	}

	$topics = fetch_topics($conn, $name_seek, $page);
	
	if ($page > $topics["page_count"]) {
		$page = $topics["page_count"];
		if ($page_page > $topics["page_page_count"]) {
			$page_page = $topics["page_page_count"];
		}
		// refetch because page has changed:
		$topics = fetch_topics($conn, $name_seek, $page);
	} 	
	
	$topics['page'] = $page;
	$topics['page_page'] = $page_page;
	$topics["page_list"] = generate_js_page_list("get_topics_page", 
		array(),
		$topics["page_count"], $page, $page_page,
		"topic_pages", "",
		"curr_page", "other_page");
	
	include("../../db_disconnect_inc.php");
	echo json_encode($topics);


	function fetch_topics($conn, $name_seek, $page) {
		$topics = get_topics($conn, $name_seek, $page);
		$topics_with_more_info = array();
		foreach($topics['topics'] as $topic) {
			$topic['remove_call'] = 
				java_script_call("removeTopic", array($topic['topic_id']));
			$topic['modify_call'] = 
				java_script_call("modifyTopic", array($topic['topic_id']));		
			$topics_with_more_info[] = $topic;
		}
		$topics['topics'] = $topics_with_more_info;
		return $topics;	
	}
?>