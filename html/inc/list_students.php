<?php
	include("db_connect_inc.php");
    include("utils/request_param_utils.php");
	include("utils/sql_utils.php");
	include("utils/html_utils.php");

    $seek_first_name = get_post_or_get($conn, "seek_first_name");
	$seek_last_name = get_post_or_get($conn, "seek_last_name");
	$seek_student_id = get_post_or_get($conn, "seek_student_id");
	
	include("sub/student/seek_students_form.php");
	
	include("sub/student/student_listing_table.php");
	include("sub/student/modify_or_add_student_form.php");
		 
    include("db_disconnect_inc.php");
?>