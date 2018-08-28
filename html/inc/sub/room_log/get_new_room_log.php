<?php
	include("../../db_connect_inc.php");
	include("../../utils/request_param_utils.php");
	include("../../utils/date_utils.php");
	include("../../utils/html_utils.php");
	include("fetch_room_log_data_from_db.php");

	$begin_time = get_post_or_get($conn, "begin_time");
	$end_time = get_post_or_get($conn, "end_time");
	$seek_room = get_post_or_get($conn, "seek_room");
	$seek_nfc_id = get_post_or_get($conn, "seek_nfc_id");
	$seek_topic = get_post_or_get($conn, "seek_topic");
	$seek_course_name = get_post_or_get($conn, "seek_course_name");
	$page = get_post_or_get($conn, "page");
	$page_page = get_post_or_get($conn, "page_page");
	
	$last_fetch_time_str = get_post_or_get($conn, "last_fetch_time");
	if (!is_integerable($last_fetch_time_str)) {
		include("../../db_disconnect_inc.php");
		return;
	}	
	$last_fetch_time = from_unix_time_to_db(intval($last_fetch_time_str));

	if (!isset($seek_room)) {
		$seek_room = "";
	}
	if (!isset($seek_nfc_id)) {
		$seek_nfc_id = "";
	}
	if (!is_integerable($page) || $page == "" || $page == "0") {
		include("../../db_disconnect_inc.php");
		return;
	}
	if (!is_integerable($page_page) || $page_page == "" || $page_page == "0") {
		include("../../db_disconnect_inc.php");
		return;
	}	
	if (strlen($seek_room) > 50 || strlen($seek_nfc_id) > 50 ||
		strlen($seek_topic) > 150  || strlen($seek_course_name) > 50) {
		include("../../db_disconnect_inc.php");
		return;
	}
	
	$room_logs = array();
	if (isset($last_fetch_time)) {
		$begin_time = from_ui_to_db($begin_time);
		$end_time = from_ui_to_db($end_time);		
		$room_logs = get_room_log($conn,
			$begin_time, $end_time, $seek_room, $seek_nfc_id, $seek_topic,
			$seek_course_name, $page, $last_fetch_time);
		$room_logs['last_fetch_time'] = time();
		$room_logs['page_list'] = 
			generate_page_list("index.php".$seek_params_get, 
				$room_logs['page_count'], $page, $page_page,
				"page", "page_page",
				"roomlog_pages", "",
				"curr_page", "other_page");
	}
	include("../../db_disconnect_inc.php");
	echo json_encode($room_logs);
?>