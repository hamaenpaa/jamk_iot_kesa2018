<?php
	if (!isset($_SESSION)) {
		session_start();	
	} 
	if (!isset($_SESSION['user_id'])) {
		return;	
	}

    include("../../db_connect_inc.php");
	include("../../utils/request_param_utils.php");
	
	$default_roomidentifier = get_post_or_get($conn, 'default_roomidentifier');
	$usage_type = get_post_or_get($conn, 'usage_type');
	$page_size = get_post_or_get($conn, 'page_size');
	$page_page_size = get_post_or_get($conn, 'page_page_size');
	
	if (mb_strlen($default_roomidentifier) > 50) {
		include("../../db_disconnect_inc.php");
		header("Location: ../../../index.php?screen=5");
		return;		
	}
	if (!is_integerable($usage_type) || $usage_type == "" || $usage_type == "0") {
		include("../../db_disconnect_inc.php");
		header("Location: ../../../index.php?screen=5");
		return;
	}	
	if (!is_integerable($page_size) || $page_size == "" || $page_size == "0") {
		include("../../db_disconnect_inc.php");
		header("Location: ../../../index.php?screen=5");
		return;
	}	
	if (!is_integerable($page_page_size) || $page_page_size == "" || $page_page_size == "0") {
		include("../../db_disconnect_inc.php");
		header("Location: ../../../index.php?screen=5");
		return;
	}
	$page_size_int = intval($page_size);
	$page_page_size_int = intval($page_page_size);
	if ($page_size_int < 0 || $page_page_size_int < 0) {
		include("../../db_disconnect_inc.php");
		header("Location: ../../../index.php?screen=5");
		return;		
	}

	$sql_update_setting = 
		"UPDATE ca_setting SET 
			default_roomidentifier=?, usage_type=?,
			page_size=?, page_page_size=? WHERE id=1";
	$q_setting = $conn->prepare($sql_update_setting);
	$q_setting->bind_param("siii", $default_roomidentifier, $usage_type, $page_size, $page_page_size);
	$q_setting->execute();	
	
	include("../../db_disconnect_inc.php");
	header("Location: ../../../index.php?screen=5");
?>