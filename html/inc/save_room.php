<?php
    include("db_connect_inc.php");
	$id = "";
	if (isset($_POST['id'])) {
		$id = $_POST['id'];
	}
	$room_name = $_POST['room_name'];
	
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

	include("db_disconnect_inc.php");
	
	header("Location: ../list_rooms.php");
?>