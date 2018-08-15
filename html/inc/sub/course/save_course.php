<?php
    include("../../db_connect_inc.php");
	include("../../utils/request_param_utils.php");
	
	$id = "";
	if (isset($_POST['id']) || isset($_GET['id'])) {
		$id = get_post_or_get($conn, 'id');
	}
	$name = strip_tags($_POST['name']);
	$description = strip_tags($_POST['description']);
	
	$name_seek = get_post_or_get($conn, "name_seek");
	$description_seek = get_post_or_get($conn, "description_seek");
	
	$seek_params_get = possible_get_param("name_seek", $name_seek, false);
	$seek_params_get .= possible_get_param("description_seek", $description_seek, false);
	
	
/* if (strlen($room_name) > 40) {
		header("Location: ../../../list_rooms.php".$seek_params_get);
		exit;
	}	
	if (strlen($seek_room_name) > 40) {
		header("Location: ../../../list_rooms.php".$seek_params_get);
		exit;
	}
*/	
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
	
	header("Location: ../../../index.php?screen=2".$seek_params_get);
?>