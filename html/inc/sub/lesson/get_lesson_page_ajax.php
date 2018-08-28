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
	include("lessons_fetch_from_db.php");

	include("../../db_connect_inc.php");

	$begin_time_seek = get_post_or_get($conn, "begin_time_seek");
	$end_time_seek = get_post_or_get($conn, "end_time_seek");
	$room_seek = get_post_or_get($conn, "room_seek");
	$topic_seek = get_post_or_get($conn, "topic_seek");
	$page = get_post_or_get($conn, "page");
	$page_page = get_post_or_get($conn, "page_page");

	$begin_time_seek = from_ui_to_db($begin_time_seek);
	$end_time_seek = from_ui_to_db($end_time_seek);	
	
	if (!isDateTime($begin_time_seek) || !isDateTime($end_time_seek) ||
	    !isDatetime1Before($begin_time_seek, $end_time_seek) {
		include("../../db_disconnect_inc.php");
		return;			
	}
	if (!is_integerable($page) || $page == "" || $page == "0") {
		include("../../db_disconnect_inc.php");
		return;
	}
	if (!is_integerable($page_page) || $page_page == "" || $page_page == "0") {
		include("../../db_disconnect_inc.php");
		return;
	}
	if (strlen($room_seek) > 50 || strlen($topic_seek) > 150) {
		include("../../db_disconnect_inc.php");
		return;
	}

	$lessons = fetch_lessons($conn, $begin_time_seek, $end_time_seek, $room_seek, $topic_seek, $page);
	
	if ($page > $lessons["page_count"]) {
		$page = $lessons["page_count"];
		if ($page_page > $lessons["page_page_count"]) {
			$page_page = $lessons["page_page_count"];
		}
		// refetch because page has changed:
		$lessons = fetch_lessons($conn, $begin_time_seek, $end_time_seek, $room_seek, $topic_seek, $page);
	} 	
	
	$lessons['page'] = $page;
	$lessons['page_page'] = $page_page;
	$lessons["page_list"] = generate_js_page_list("get_lessons_page", 
		array(),
		$lessons["page_count"], $page, $page_page,
		"lesson_pages", "",
		"curr_page", "other_page");
	
	include("../../db_disconnect_inc.php");
	echo json_encode($lessons);


	function fetch_lessons($conn, $begin_time_seek, $end_time_seek, $room_seek, $topic_seek, $page) {
		$lessons = 
			get_lessons($conn, $begin_time_seek, $end_time_seek, $room_seek, $topic_seek, $page);
		$lessons_with_more_info = array();
		foreach($lessons['lessons'] as $lesson) {
			$lesson['remove_call'] = 
				java_script_call("removeLesson", array($lesson['lesson_id']));
			$lesson['modify_call'] = 
				java_script_call("modifyLesson", array($lesson['lesson_id']));		
			$lessons_with_more_info[] = $lesson;
		}
		$lessons['lessons'] = $lessons_with_more_info;
		return $lessons;	
	}
?>