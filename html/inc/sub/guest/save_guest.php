<?php
    include("../../db_connect_inc.php");
	include("../../utils/request_param_utils.php");
    $id = "";
	if (isset($_POST['id'])) {
		$id = $_POST['id'];
	}
	$guest_firstname = strip_tags($_POST['guest_firstname']);
	$guest_lastname = strip_tags($_POST['guest_lastname']);
    $seek_first_name = get_post_or_get($conn, "seek_first_name");
	$seek_last_name = get_post_or_get($conn, "seek_last_name");	
	$seek_params_get = possible_get_param("seek_first_name",$seek_first_name);
	$seek_params_get .= possible_get_param("seek_last_name",$seek_last_name, $seek_params_get == "");	
	
	if ($id != "") {
		$q = $conn->prepare("UPDATE ca_guest SET FirstName = ?, LastName = ? WHERE ID = ?");
		if ($q) {
			$q->bind_param("ssi", $guest_firstname, $guest_lastname, $id);
			$q->execute();
		}
	} else {
		$q = $conn->prepare("INSERT INTO ca_guest (FirstName,LastName) VALUES (?,?)");
		if ($q) {
			$q->bind_param("ss", $guest_firstname, $guest_lastname);
			$q->execute();
		}		
	}

	include("db_disconnect_inc.php");
	
	header("Location: ../../../list_guests.php".$seek_params_get);
?>