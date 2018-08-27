<?php
	if (!isset($_SESSION)) {
		session_start();	
	} 
	if (!isset($_SESSION['user_id'])) {
		return;	
	}

    include("../../db_connect_inc.php");
    include("../../utils/request_param_utils.php");

	$id = get_post_or_get($conn, "id");
	
	$sql = "SELECT ca_course.name, ca_course.description FROM
			ca_course WHERE id=?";
	$q_course = $conn->prepare($sql);
	$q_course->bind_param("i", $id);
	$q_course->execute();		
	$q_course->store_result();	
	$q_course->bind_result($name, $description);
	$course = array();
	if ($q_course->fetch()) {	
		$course["name"] = $name;
		$course["description"] = $description;
	}
	include("../../db_disconnect_inc.php");
	echo json_encode($course);
?>