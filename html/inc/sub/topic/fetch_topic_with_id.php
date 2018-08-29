<?php
	if (!isset($_SESSION)) {
		session_start();	
	} 
	if (!isset($_SESSION['user_id'])) {
		return;	
	}

    include("../../db_connect_inc.php");
	include("../../utils/date_utils.php");
	include("../../utils/request_param_utils.php");
	
	$id = get_post_or_get($conn, "id");
	
	if (!is_integerable($id) || $id == "" || $id == "0") {
		include("../../db_disconnect_inc.php");
		return;
	}	
	
	$total_fields = "ca_topic.name ";	
	$sql_topic = "SELECT " . $total_fields . " FROM ca_topic WHERE id=?";
	$q_topic = $conn->prepare($sql_topic);
	$q_topic->bind_param("i", $id);
	$q_topic->execute();		
	$q_topic->store_result();
	$q_topic->bind_result($name);	
	$topic = array();
	if ($q_topic->fetch()) {
		$topic = array("name" => $name);
	}
	include("../../db_disconnect_inc.php");
	echo json_encode($topic);
?>