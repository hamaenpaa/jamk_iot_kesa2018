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

	if (strlen($name) > 150 || strlen($name) < 1 || strpos($name, ",") !== FALSE) {
		include("../../db_disconnect_inc.php");
		return;				
	}

	$sql_check_not_another_similar = "SELECT COUNT(*) FROM ca_topic WHERE name = ?";
	if ($id != "") {
		$sql_check_not_another_similar .= " AND id <> ?";
	}
	$q_check_similar = $conn->prepare($sql_check_not_another_similar);
	if ($id != "") {
		$q_check_similar->bind_param("si", $name, $id);
	} else {
		$q_check_similar->bind_param("s", $name);
	}
	$q_check_similar->execute();
	$q_check_similar->store_result();
	$q_check_similar->bind_result($count);
	$q_check_similar->fetch();
	if ($count == 0) {
		if ($id != "") {
			$q = $conn->prepare("UPDATE ca_topic SET name = ? WHERE ID = ?");
			if ($q) {
				$q->bind_param("si", $name, $id);
				$q->execute();
			}
		} else {
			$q = $conn->prepare("INSERT INTO ca_topic(name) VALUES (?)");
			if ($q) {
				$q->bind_param("s", $name);
				$q->execute();
			} 
		}
		$ret_val = "{}";
	} else {
		$ret_val = "{'error': 'found_similar_name'}";
	}

	include("../../db_disconnect_inc.php");
	echo $ret_val;
?>