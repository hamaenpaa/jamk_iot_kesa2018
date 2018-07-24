<?php
    include("../../db_connect_inc.php");
	include("../../utils/request_param_utils.php");
	
	$id = get_post_or_get($conn, "id");
 	
    $seek_room_name = get_post_or_get($conn, "seek_room_name");
	
	if (isset($_POST['page'])) {
		$page = strip_tags($_POST['page']);
	} else {
		$page = 1;
	}		
	
	$seek_params_get = possible_get_param("seek_room_name",$seek_room_name);
	$seek_params_get .= possible_get_param("page", $page, $seek_params_get == "");

	$q = $conn->prepare("UPDATE ca_room SET removed = 1 WHERE ID = ?");
	if ($q) {
		$q->bind_param("i", $id);
		$q->execute();
	}

	include("../../db_disconnect_inc.php");
	header("Location: ../../../list_rooms.php".$seek_params_get);
?>