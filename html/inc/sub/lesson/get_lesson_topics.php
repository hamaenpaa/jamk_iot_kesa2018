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
	include("lessons_fetch_from_db.php");

	$id = "";
	if (isset($_POST['id']) || isset($_GET['id'])) {
		$id = get_post_or_get($conn, 'id');
	}
	
	if (!is_integerable($id) || $id == "" || $id == "0") {
		include("../../db_disconnect_inc.php");
		return;
	}		
	
	$lesson_topics = get_lesson_topics($conn, $id);

	include("../../db_disconnect_inc.php");
	echo json_encode($lesson_topics);
?>