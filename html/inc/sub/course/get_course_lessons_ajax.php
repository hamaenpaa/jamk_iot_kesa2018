<?php
	include("../../utils/request_param_utils.php");
	include("../../utils/html_utils.php");
	include("../../utils/date_utils.php");
	include("course_fetch_from_db.php");
	include("../../db_connect_inc.php");

	$id = "";
	if (isset($_POST['id']) || isset($_GET['id'])) {
		$id = get_post_or_get($conn, 'id');
	}
	
	$course_lessons = get_course_lessons($conn, $id);

	include("../../db_disconnect_inc.php");
	echo json_encode($course_lessons);
?>