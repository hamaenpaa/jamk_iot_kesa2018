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
	include("../../utils/sql_utils.php");
	include("user_fetch_from_db.php");

	$username_seek = get_post_or_get($conn, "username_seek");
	$page = get_post_or_get($conn, "page");
	$page_page = get_post_or_get($conn, "page_page");

	if (strlen($username_seek) > 65) {
		include("../../db_disconnect_inc.php");
		return "";
	}
	if (!is_integerable($page) || $page == "" || $page == "0") {
		include("../../db_disconnect_inc.php");
		return;
	}
	if (!is_integerable($page_page) || $page_page == "" || $page_page == "0") {
		include("../../db_disconnect_inc.php");
		return;
	}
	
	$users = get_users($conn, $username_seek, $page);

	if ($page > $users["page_count"]) {
		$page = $users["page_count"];
		if ($page_page > $users["page_page_count"]) {
			$page_page = $users["page_page_count"];
		}
		// refetch because page has changed:
		$users = get_users($conn, $username_seek, $page);
	} 	
	
	$users['page'] = $page;
	$users['page_page'] = $page_page;
	$users["page_list"] = generate_js_page_list("get_user_page", 
		array(),
		$page_size, $page_page_size,
		$users["page_count"], $page, $page_page,
		"user_pages", "",
		"curr_page", "other_page");
	$users['perm'] = $_SESSION['user_permlevel'];
	$users['curr_user_id'] = $_SESSION['user_id'];
	include("../../db_disconnect_inc.php");
	echo json_encode($users);
?>