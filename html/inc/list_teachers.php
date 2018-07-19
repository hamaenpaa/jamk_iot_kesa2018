<?php
    include("utils/request_param_utils.php");
	include("utils/sql_utils.php");

    $seek_first_name = get_post_or_get("seek_first_name");
	$seek_last_name = get_post_or_get("seek_last_name");

	include("sub/teacher/seek_teachers_form.php");

   	include("db_connect_inc.php");
	
	include("sub/teacher/teacher_listing_table.php");
	include("sub/teacher/modify_or_add_teacher_form.php");
   	
   	include("db_disconnect_inc.php");
?>