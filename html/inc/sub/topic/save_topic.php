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
	
	$id = "";
	if (isset($_GET['id'])) {
		$id = get_post_or_get($conn, 'id');
	}
	if (!is_integerable($id)) {
		include("../../db_disconnect_inc.php");
		return;
	}	
	
	$name = strip_tags($_GET['name']);

	if (strlen($name) > 150 || strlen($name) < 1 ) {
		include("../../db_disconnect_inc.php");
		return;				
	}

	if ($id != "") {
		$q = $conn->prepare("UPDATE ca_topic SET name = ? WHERE ID = ?");
		if ($q) {
			$q->bind_param("si", $name, $id);
			$q->execute();
		}
	} else {
		$q = $conn->prepare(
			"INSERT INTO 
				ca_topic (name) 
				VALUES (?)");
		if ($q) {
			$q->bind_param("s", $name);
			$q->execute();
		}		
	}

	include("../../db_disconnect_inc.php");
	echo "{}";
?>