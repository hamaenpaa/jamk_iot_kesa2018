<?php
    include("../../db_connect_inc.php");
	include("../../utils/request_param_utils.php");
	$id = "";
	if (isset($_POST['id'])) {
		$id = $_POST['id'];
	}
	$room_name = $_POST['room_name'];
	$seek_room_name = get_post_or_get($conn, "seek_room_name");
	$seek_params_get = possible_get_param("seek_room_name",$seek_room_name);
	
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