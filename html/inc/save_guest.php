<?php
    include("db_connect_inc.php");
	$id = "";
	if (isset($_POST['id'])) {
		$id = $_POST['id'];
	}
	$guest_firstname = $_POST['guest_firstname'];
	$guest_lastname = $_POST['guest_lastname'];
	
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
	
	header("Location: ../list_guests.php");
?>