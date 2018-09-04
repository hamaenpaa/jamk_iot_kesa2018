<?php
	include("../../utils/request_param_utils.php");
	include("../../utils/date_utils.php");
	include("../../utils/html_utils.php");
	include("../setting/fetch_settings_from_db.php");
	include("fetch_room_log_data_from_db.php");
	include("../../db_connect_inc.php");

	$begin_time = get_post_or_get($conn, "begin_time");
	$end_time = get_post_or_get($conn, "end_time");
	$seek_room = get_post_or_get($conn, "seek_room");
	$seek_nfc_id = get_post_or_get($conn, "seek_nfc_id");
	$seek_topic = get_post_or_get($conn, "seek_topic");
	$seek_course_name = get_post_or_get($conn, "seek_course_name");
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
	if (strlen($seek_room) > 50 || strlen($seek_nfc_id) > 50 ||
		strlen($seek_topic) > 150  || strlen($seek_course_name) > 50) {
		include("../../db_disconnect_inc.php");
		return;
	}
	
	$room_logs = get_room_log($conn,
		$begin_time, $end_time, $seek_room, $seek_nfc_id, $seek_topic,
		$seek_course_name, $page, "", true);

	$room_logs["page_list"] = generate_js_page_list("get_js_room_log_page", 
		array(),
		$room_logs["page_count"], $page, $page_page,
		"roomlog_pages", "",
		"curr_page", "other_page");

	include("../../db_disconnect_inc.php");
	echo json_encode($room_logs);
?>