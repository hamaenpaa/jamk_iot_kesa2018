<?php
	if (!isset($_SESSION)) {
		session_start();	
	} 
	if (!isset($_SESSION['user_id'])) {
		return;	
	} 

	include("../../db_connect_inc.php");
	include("../../utils/request_param_utils.php");
	include("../../utils/html_utils.php");
	include("../../utils/date_utils.php");
	include("../../utils/sql_utils.php");
	include("../../utils/topic_selection.php");
	include("course_fetch_from_db.php");

	$course_id = get_post_or_get($conn, 'id');
	$begin_time_seek = get_post_or_get($conn, 'begin_time_seek');
	$end_time_seek = get_post_or_get($conn, 'end_time_seek');
	$room_seek = get_post_or_get($conn, 'room_seek');
	$topic_name_parts = get_post_or_get($conn, 'topic_name_parts');
	$topic_ids = get_post_or_get($conn, 'topic_ids');
	$page = get_post_or_get($conn, "page");
	$page_page = get_post_or_get($conn, "page_page");
	$begin_time_seek = from_ui_to_db($begin_time_seek);
	$end_time_seek = from_ui_to_db($end_time_seek);	
	
	if (!isDateTime($begin_time_seek) || !isDateTime($end_time_seek) ||
	    !isDatetime1Before($begin_time_seek, $end_time_seek)) {
		include("../../db_disconnect_inc.php");
		return;			
	}
	
	if (!is_integerable($course_id) || $course_id == "" || $course_id == "0") {
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
	if (mb_strlen($room_seek) > 50) {
		include("../../db_disconnect_inc.php");
		return;
	}
	
	$lessons = fetch_lessons_without_course($conn, $course_id,
	    $begin_time_seek, $end_time_seek, $room_seek, 
		$topic_name_parts, $topic_ids, $page);
	
	if ($page > $lessons["page_count"]) {
		$page = $lessons["page_count"];
		if ($page_page > $lessons["page_page_count"]) {
			$page_page = $lessons["page_page_count"];
		}
		// refetch because page has changed:
		$lessons = fetch_lessons_without_course($conn, $course_id,
			$begin_time_seek, $end_time_seek, $room_seek, 
			$topic_name_parts, $topic_ids, $page);
	} 	
	
	$lessons['page'] = $page;
	$lessons['page_page'] = $page_page;
	$lessons["page_list"] = generate_js_page_list("fetch_available_new_course_lessons", 
		array($course_id),
		$page_size, $page_page_size,
		$lessons["page_count"], $page, $page_page,
		"lessons_without_course_pages", "",
		"curr_page", "other_page");
	if ($lessons["page_list"] == "") {
		$lessons["page_list"] = "<div id=\"lessons_without_course_pages\"></div>";
	}	
	include("../../db_disconnect_inc.php");
	echo json_encode($lessons);
	
	
	function fetch_lessons_without_course($conn, 
		$course_id, 
		$begin_time_seek, $end_time_seek, 
		$room_seek, $topic_name_parts, $topic_ids, $page) {
		$lessons = 
			get_lessons_without_course(
				$conn, $begin_time_seek, $end_time_seek, $room_seek, 
				$topic_name_parts, $topic_ids, $page);
		$lessons_with_more_info = array();
		foreach($lessons['lessons'] as $lesson) {
			$lesson['add_call'] = 
				java_script_call("addLessonCourse", 
					array("seek_topics_for_new_lessons_of_course", 
					$course_id, $lesson['lesson_id']));
			$lessons_with_more_info[] = $lesson;
		}
		$lessons["lessons"] = $lessons_with_more_info;
		return $lessons;			
	}
?>