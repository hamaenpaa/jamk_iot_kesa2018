<?php
   	include("db_connect_inc.php");
    include("utils/request_param_utils.php");
	include("utils/sql_utils.php");
	include("utils/html_utils.php");

    $seek_first_name = get_post_or_get($conn, "seek_first_name");
	$seek_last_name = get_post_or_get($conn, "seek_last_name");

	$page = get_post_or_get($conn, "page");
	if (!isset($page) || $page == "") { $page = 1; }		
	
	$seek_params_get = possible_get_param("seek_first_name",$seek_first_name);
	$first = true;
	if ($seek_first_name != "") { $first = false; }
	$seek_params_get .= possible_get_param("seek_last_name",$seek_last_name, $first);								 
	
	include("sub/teacher/seek_teachers_form.php");
	
	include("sub/teacher/teacher_listing_table.php");
	include("sub/teacher/modify_or_add_teacher_form.php");
   	
   	include("db_disconnect_inc.php");
?>