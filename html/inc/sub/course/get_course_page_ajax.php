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
	include("../../utils/topic_selection.php");
	include("course_fetch_from_db.php");

	$description_seek = get_post_or_get($conn, "description_seek");
	$name_seek = get_post_or_get($conn, "name_seek");
	
	$topic_seek_selection_ids = get_post_or_get($conn, "topic_seek_selection_ids");
	$topic_seek_name_parts = get_post_or_get($conn, "topic_seek_name_parts");
	
	$topic_ids = get_total_topic_ids($conn, $topic_seek_name_parts, $topic_seek_selection_ids);
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
	if (strlen($name_seek) > 50 || strlen($description_seek) > 500) {
		include("../../db_disconnect_inc.php");
		return;
	}

	$courses = fetch_courses($conn, $name_seek, $description_seek, $topic_ids, $page);

	if ($page > $courses["page_count"]) {
		$page = $courses["page_count"];
		if ($page_page > $courses["page_page_count"]) {
			$page_page = $courses["page_page_count"];
		}
		// refetch because page has changed:
		$courses = fetch_courses($conn, $name_seek, $description_seek, $topic_ids, $page);
	} 	
	
	$courses['page'] = $page;
	$courses['page_page'] = $page_page;
	$courses["page_list"] = generate_js_page_list("get_course_page", 
			array(),
			$page_size, $page_page_size,
			$courses["page_count"], $page, $page_page,
			"course_pages", "",
			"curr_page", "other_page");

	include("../../db_disconnect_inc.php");
	echo json_encode($courses);

	function fetch_courses($conn, $name_seek, $description_seek, $topic_ids, $page) {
		$courses = 
			get_courses($conn, $name_seek, $description_seek, $topic_ids, $page);
		$courses_with_more_info = array();
		foreach($courses['courses'] as $course) {
			$course['remove_call'] = 
				java_script_call("removeCourse", array($course['course_id']));
			$course['modify_call'] = 
				java_script_call("modifyCourse", array($course['course_id']));		
			$courses_with_more_info[] = $course;
		}
		$courses["courses"] = $courses_with_more_info;
		return $courses;	
	}
?>