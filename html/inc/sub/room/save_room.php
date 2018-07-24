<?php
    include("../../db_connect_inc.php");
	include("../../utils/request_param_utils.php");
	$id = "";
	if (isset($_POST['id']) || isset($_GET['id'])) {
		$id = get_post_or_get($conn, 'id');
	}
	
	$room_name = strip_tags($_POST['room_name']);
	if (isset($_POST['page'])) {
		$page = strip_tags($_POST['page']);
	} else {
		$page = 1;
	}
	
	$seek_room_name = get_post_or_get($conn, "seek_room_name");
	$seek_params_get = possible_get_param("seek_room_name",$seek_room_name);
	$seek_params_get .= possible_get_param("page", $page, $seek_params_get == "");	
	
	if (strlen($room_name) > 40) {
		header("Location: ../../../list_rooms.php".$seek_params_get);
		exit;
	}	
	if (strlen($seek_room_name) > 40) {
		header("Location: ../../../list_rooms.php".$seek_params_get);
		exit;
	}		
	
	if ($id != "") {
		$q = $conn->prepare("UPDATE ca_room SET room_name = ? WHERE ID = ?");
		if ($q) {
			$q->bind_param("si", $room_name, $id);
			$q->execute();
		}
	} else {
		$q = $conn->prepare("INSERT INTO ca_room (room_name) VALUES (?)");
		if ($q) {
			$q->bind_param("s", $room_name);
			$q->execute();
		}		
	}

	include("../../db_disconnect_inc.php");
	
	header("Location: ../../../list_rooms.php".$seek_params_get);
?>