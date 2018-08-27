<?php
	if (!isset($_SESSION)) {
		session_start();	
	} 
	if (!isset($_SESSION['user_id'])) {
		return;	
	}

    include("../../db_connect_inc.php");
	include("../../utils/request_param_utils.php");
	
	$id = "";
	if (isset($_POST['id']) || isset($_GET['id'])) {
		$id = get_post_or_get($conn, 'id');
	}
	$name = strip_tags(get_post_or_get($conn, 'name'));
	$description = strip_tags(get_post_or_get($conn, 'description'));
	
	if ($id != "") {
		$q = $conn->prepare("UPDATE ca_course SET 
			name = ?, description = ? WHERE ID = ?");
		if ($q) {
			$q->bind_param("ssi", $name, $description, $id);
			$q->execute();
		}
	} else {
		$q = $conn->prepare(
			"INSERT INTO 
				ca_course (name, description) 
				VALUES (?,?)");
		if ($q) {
			$q->bind_param("ss", $name, $description);
			$q->execute();
		}		
	}

	include("../../db_disconnect_inc.php");
	echo "{}";
?>