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
	if (!is_integerable($id) || $id == "" || $id == "0") {
		include("../../db_disconnect_inc.php");
		return;
	}		

	$q = $conn->prepare("UPDATE ca_course SET removed = 1 WHERE ID = ?");
	if ($q) {
		$q->bind_param("i", $id);
		$q->execute();
	}

	include("../../db_disconnect_inc.php");
	return "{}";
?>