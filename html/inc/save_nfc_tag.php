<?php
    include("db_connect_inc.php");
	$id = "";
	if (isset($_POST['id'])) {
		$id = $_POST['id'];
	}
	$NFC_ID = $_POST['NFC_ID'];
	$active = "";
	if (isset($_POST['active']))
		$active = "1";	
	
	if ($id != "") {
		$q = $conn->prepare("UPDATE ca_nfc_tag SET NFC_ID = ?,active=? WHERE ID = ?");
		if ($q) {
			$q->bind_param("ssi", $NFC_ID, $active, $id);
			$q->execute();
		}
	} else {
		$q = $conn->prepare("INSERT INTO ca_nfc_tag (NFC_ID, active) VALUES (?,?)");
		if ($q) {
			$q->bind_param("ss", $NFC_ID, $active);
			$q->execute();
		}		
	}

	include("db_disconnect_inc.php");
	
	header("Location: ../list_nfc_tags.php");
?>