<div id="seek_courses">

<?php
	include("db_connect_inc.php");
    include("utils/request_param_utils.php");
	include("utils/sql_utils.php");

   	$seek_course_ID = get_post_or_get($conn, "seek_course_ID");
    $seek_course_name = get_post_or_get($conn, "seek_course_name");
	$id = get_post_or_get($conn, "id");
	
	$seek_params_hidden_inputs = hidden_input("seek_course_ID", $seek_course_ID).
	                             hidden_input("seek_course_name", $seek_course_name);

   	include("sub/course/seek_course_form.php");

   	
   	include("sub/course/course_listing_table.php");
	include("sub/course/modify_or_add_course_form.php");
	include("sub/course/course_teachers_modification.php");
	include("sub/course/course_students_modification.php");

   	include("db_disconnect_inc.php");
?>

</div>